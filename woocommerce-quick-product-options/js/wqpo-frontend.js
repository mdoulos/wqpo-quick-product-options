// ---------------------------------------------------------------------------------------
// Variable Definitions ------------------------------------------------------------------
// ---------------------------------------------------------------------------------------

let defaultPriceElement = document.querySelector('.simple-product-page .woocommerce-Price-amount');
let simplePriceElement = document.querySelector('.wqpo-simple-price');
let wqpoReplacePrice = document.querySelector('.wqpo-replace-price');
let wqpoRadioOptions = document.querySelectorAll('.wqpo-radio-option, .wqpo-swatches-radio-option, .wqpo-color-radio-option');
let wqpoSelectOptions = document.querySelectorAll('.wqpo-select');
let variationForms = document.querySelectorAll('.variations_form');
let wqpoDescriptions = document.querySelectorAll('.wqpo-choice-description');
let wqpoInputHiddenOptions = document.getElementById('wqpo-hidden-options');
let wqpoInputHiddenTags = document.getElementById('wqpo-hidden-tags');
let wqpoHiddenOptions = [];
let wqpoHiddenTags = [];

// ---------------------------------------------------------------------------------------
// Script Actions ------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------

document.addEventListener('DOMContentLoaded', function() { // The page is fully loaded
    let wqpoProductPage = document.querySelector('.product-page');
    if (wqpoProductPage) {
        if (!document.getElementsByClassName('wqpo-option').length > 0) { return; } // If there are no options, exit the script.
        wqpoProductPage.classList.add('wqpo-product-page');

        // Default hide elements with showtags defined.
        updateShowTags();

        // Click the first choice in each radio option.
        if (wqpoRadioOptions.length > 0) {
            wqpoRadioOptions.forEach(function(wqpoRadioOption) {
                if (wqpoRadioOption.classList.contains('wqpo-selectfirst')) {
                    let firstOption = wqpoRadioOption.querySelector('.wqpo-choice input');
                    if (firstOption) {
                        firstOption.click();
                    }
                }
            });
        }

        // Attach event listeners to wqpoDescriptions to click the parent's input.
        if (wqpoDescriptions.length > 0) {
            wqpoDescriptions.forEach(function(wqpoDescription) {
                wqpoDescription.addEventListener('click', function() {
                    let input = wqpoDescription.closest('.wqpo-choice').querySelector('input');
                    input.click();
                });
            });
        }

        // Toggle the change event for select options to update the price.
        if (wqpoSelectOptions.length > 0) {
            wqpoSelectOptions.forEach(function(wqpoSelectOption) {
                wqpoSelectOption.dispatchEvent(new Event('change'));
            });
        }
        
        // Alert the user to choose a checkbox choice if they submit the form without selecting one.
        document.querySelector('form.cart').addEventListener('submit', function(event) {
            var hiddenInputs = document.getElementsByClassName('wqpo-checkbox-option-required');
            for (var i = 0; i < hiddenInputs.length; i++) {
                if (hiddenInputs[i].value === '') {
                    event.preventDefault(); // Prevent form submission
                    alert('Please choose a required checkbox option.');
                    break;
                }
            }
        });

        // Simple Product Pages
        if (document.getElementsByClassName('simple-product-page').length > 0) {
            let modifiesPrice = hasOptionsThatModifyPrice();
            if (modifiesPrice === false) {
                simplePriceElement.style.display = 'none';
            } else if (defaultPriceElement) {
                defaultPriceElement.innerHTML = 'Base Price: ' + defaultPriceElement.innerHTML;
                if (wqpoReplacePrice && defaultPriceElement) {
                    defaultPriceElement.style.display = 'none';
                }

                updateSimplePrice();
            }
        } else {
            // Variable Product Pages
            // Tracks clicks to options outside of the wqpo element to combine the price.
            variationForms.forEach(function(variationForm) {
                variationForm.addEventListener('click', function(event) {
                    let targetElement = event.target;

                    if (hasParentWithClass(targetElement, 'wqpo-option')) {
                        return;
                    }

                    updateVariationPrice();
                });
            });
        }
    }
});



// ----------------------------------------------------------------------------------------------------------
// Event Listeners ------------------------------------------------------------------------------------------
// ----------------------------------------------------------------------------------------------------------

// Radio
function wqpoClickRadio() {
    toggleModifiers();
    updateShowTags();
    updateRadioLabels();
    updateOptionPrices();
    updateVariationPrice();
    updateSimplePrice();
}

// Swatches (Radio)
function wqpoClickSwatchesRadio() {
    toggleModifiers();
    updateShowTags();
    updateOptionPrices();
    updateVariationPrice();
    updateSimplePrice();
}


// Swatches (Checkbox)
function wqpoClickSwatchesCheckbox() {
    toggleModifiers();
    updateShowTags();
    toggleHiddenRequiredCheckboxInputs();
    updateCheckboxOptionPrices();
    updateVariationPrice();
    updateSimplePrice();
}

// Checkbox
function wqpoClickCheckbox() {
    toggleModifiers();
    updateShowTags();
    toggleHiddenRequiredCheckboxInputs();
    updateCheckboxOptionPrices();
    updateVariationPrice();
    updateSimplePrice();
}

// Dropdown
function wqpoChangeSelect() {
    toggleModifiers();
    updateShowTags();
    updateSelectPriceTags();
    updateOptionPrices();
    updateVariationPrice();
    updateSimplePrice();
}

// Number
function numberInputChanged(wqpoNumberInput) {
    let inputPrice = 0;
    if (modifiedPrice = wqpoNumberInput.getAttribute("data-modifiedprice")) {
        inputPrice = parseFloat(modifiedPrice);
    } else {
        inputPrice = parseFloat(wqpoNumberInput.getAttribute("data-price"));
    }

    let price = 0;
    let inputValue = parseInt(wqpoNumberInput.value) || 0;

    if (wqpoNumberInput.closest('.wqpo-option').classList.contains('wqpo-flatrate')) {
        if (inputValue > 0) {
            price = inputPrice;
        }
    } else {
        price = inputPrice * (inputValue || 0);
    }

    toggleModifiers();
    updateOptionPrice(price, wqpoNumberInput);
    updateVariationPrice();
    updateSimplePrice();
}

// Text
function textInputChanged(wqpoTextInput) {
    let textContainer = wqpoTextInput.closest('.wqpo-option-text-container');
    let charCountSpan = textContainer.querySelector('.wqpo-text-char-count');
    let inputLength = wqpoTextInput.value.length;
    const maxLength = parseInt(charCountSpan.getAttribute("data-maxlength"));
    charCountSpan.textContent = `${inputLength}/${maxLength}`;

    let price = calculateTextPrice(wqpoTextInput, inputLength);
    updateOptionPrice(price, wqpoTextInput);
    updateVariationPrice();
    updateSimplePrice();
}

// Textarea
function textareaInputChanged(wqpoTextareaInput) {
    let price = 0;
    if (wqpoTextareaInput.value.length > 0) {
        price = parseFloat(wqpoTextareaInput.getAttribute("data-price"))
    }

    updateOptionPrice(price, wqpoTextareaInput);
    updateVariationPrice();
    updateSimplePrice();
}




// ----------------------------------------------------------------------------------------------------------
// Update Elements ------------------------------------------------------------------------------------------
// ----------------------------------------------------------------------------------------------------------

function toggleHiddenRequiredCheckboxInputs() {
    let requiredCheckboxInputs = document.querySelectorAll('.wqpo-checkbox-option-required');
    if (requiredCheckboxInputs.length === 0) { return; }

    requiredCheckboxInputs.forEach(function(requiredCheckboxInput) {
        let checkboxParent = requiredCheckboxInput.closest(".wqpo-option");
        let checkboxes = checkboxParent.querySelectorAll(".wqpo-choice input");
        let hiddenInputValue = "";

        if (requiredCheckboxInput) {
            checkboxes.forEach(function(checkbox) {
                if (checkbox.checked) {
                    hiddenInputValue = "1";
                    return;
                }
            });
        
            requiredCheckboxInput.value = hiddenInputValue;
        }
    });
}

function updateRadioLabels() {
    // Fetch All Radio Options and Loop Through Them
    let radioOptions = document.querySelectorAll('.wqpo-radio-option');
    radioOptions.forEach(function(radioOption) {
        // Get the Radio Options Price Labels and Selected Choice.
        let priceLabels = radioOption.getElementsByClassName("wqpo-choice-price");
        let selectedRadioChoice = radioOption.querySelector('.wqpo-choice:has(input:checked)');
        if (selectedRadioChoice == null) { return; }
        let selectedPrice = 0;

        // Identify the Governing Price of the Selected Choice. This Price Will Be Used to Calculate the Price Difference for All Other Choices.
        if (modifiedPrice = selectedRadioChoice.querySelector('.wqpo-choice-price').getAttribute("data-modifiedprice")) {
            selectedPrice = parseFloat(modifiedPrice);
        } else {
            selectedPrice = parseFloat(selectedRadioChoice.querySelector('.wqpo-choice-price').getAttribute("data-price"));
        }

        // Loop Through All Price Labels and Update Them Based on the Selected Price.
        Array.from(priceLabels).forEach(function(priceLabel) {
            let loopedPrice = 0;
            if (priceLabel.getAttribute("data-modifiedprice")) {
                loopedPrice = parseFloat(priceLabel.getAttribute("data-modifiedprice"));
            } else {
                loopedPrice = parseFloat(priceLabel.getAttribute("data-price"));
            }
    
            if (loopedPrice >= selectedPrice) {
                priceLabel.innerHTML = "+" + formatter.format(Math.abs(selectedPrice - loopedPrice));
            } else {
                priceLabel.innerHTML = "-" + formatter.format(Math.abs(selectedPrice - loopedPrice));
            }
        });
    });
}

function updateSelectPriceTags() {
    let wqpoSelects = document.querySelectorAll('.wqpo-select');
    wqpoSelects.forEach(function(wqpoSelect) {
        let selectOptions = wqpoSelect.options;
        for (let i = 0; i < selectOptions.length; i++) {
            let selectOption = selectOptions[i];
            let selectOptionContent = selectOption.textContent;

            let textPrice = selectOptionContent.match(/^(.*)\s*\$\s*(\d+(\.\d{1,2})?)\s*$/);
            if (textPrice) {
                let string1 = textPrice[1].trim(); // Text before the price
                let editedPrice = 0;

                if (modifiedPrice = selectOption.getAttribute("data-modifiedprice")) {
                    editedPrice = parseFloat(modifiedPrice);
                } else {
                    editedPrice = parseFloat(selectOption.getAttribute("data-price"));
                }

                let updatedOptionContent = `${string1} $${editedPrice}`;
                selectOption.textContent = updatedOptionContent;
            }
        }
    });
}

function updateCheckboxOptionPrices() {
    let checkboxOptions = document.querySelectorAll('.wqpo-checkbox-option, .wqpo-swatches-checkbox-option');
    checkboxOptions.forEach(function(checkboxOption) {
        let optionPrice = 0;
        let checkboxes = checkboxOption.querySelectorAll('.wqpo-choice:has(input:checked)');
        let optionType = checkboxOption.getAttribute('data-type');

        if (checkboxes) { 
            checkboxes.forEach(function(checkbox) {
                let checkboxPrice = 0;
                if (optionType === 'checkbox') {
                    if (modifiedPrice = checkbox.querySelector('.wqpo-choice-price').getAttribute("data-modifiedprice")) {
                        checkboxPrice = parseFloat(modifiedPrice);
                    } else {
                        checkboxPrice = parseFloat(checkbox.querySelector('.wqpo-choice-price').getAttribute('data-price'));
                    }
                } else if (optionType === 'swatches-checkbox') {
                    if (modifiedPrice = checkbox.getAttribute("data-modifiedprice")) {
                        checkboxPrice = parseFloat(modifiedPrice);
                    } else {
                        checkboxPrice = parseFloat(checkbox.getAttribute('data-price'));
                    }
                }

                optionPrice += checkboxPrice;
            });
        }

        checkboxOption.querySelector('.wqpo-option-price').value = optionPrice;
        checkboxOption.querySelector('.wqpo-option-price').setAttribute('data-price', optionPrice);
    });
}

function updateCheckboxLabelPrices() {
    // Fetch All Checkbox Options and Loop Through Them
    let checkboxOptions = document.querySelectorAll('.wqpo-checkbox-option');
    checkboxOptions.forEach(function(checkboxOption) {
        // Get the Checkbox Options Price Labels.
        let priceLabels = checkboxOption.getElementsByClassName("wqpo-choice-price");
        // Loop through all priceLabels, if they have a modified price, set the price to the modified price, else set the price to the data-price.
        Array.from(priceLabels).forEach(function(priceLabel) {
            let price = 0;
            if (priceLabel.getAttribute("data-modifiedprice")) {
                price = parseFloat(priceLabel.getAttribute("data-modifiedprice"));
            } else {
                price = parseFloat(priceLabel.getAttribute("data-price"));
            }

            // Set the price label to be +$price
            priceLabel.innerHTML = "+" + formatter.format(price);
        });
    });
}

function updateOptionPrice(price, thisChoice) {
    let thisOption = thisChoice.closest(".wqpo-option");
    let optionPrice = thisOption.querySelector(".wqpo-option-price");
    optionPrice.value = price;
    optionPrice.setAttribute("data-price", price);
}

function updateOptionPrices() {
    let wqpoOptions = document.querySelectorAll('.wqpo-option');
    wqpoOptions.forEach(function(wqpoOption) {
        let price = 0;
        let optionType = wqpoOption.getAttribute('data-type');

        // Update Radio Option Price.
        if (optionType === 'radio') {
            let selectedRadioChoice = wqpoOption.querySelector('.wqpo-choice:has(input:checked)');
            if (selectedRadioChoice) {
                if (modifiedPrice = selectedRadioChoice.querySelector('.wqpo-choice-price').getAttribute("data-modifiedprice")) {
                    price = parseFloat(modifiedPrice);
                } else {
                    price = parseFloat(selectedRadioChoice.querySelector('.wqpo-choice-price').getAttribute('data-price'));
                }

                wqpoOption.querySelector(".wqpo-option-price").value = price;
                wqpoOption.querySelector(".wqpo-option-price").setAttribute("data-price", price);
            }

        } else if (optionType === 'swatches-radio' || optionType === 'color-radio') {
            let selectedRadioChoice = wqpoOption.querySelector('.wqpo-choice:has(input:checked)');
            if (selectedRadioChoice) {
                if (modifiedPrice = selectedRadioChoice.getAttribute("data-modifiedprice")) {
                    price = parseFloat(modifiedPrice);
                } else {
                    price = parseFloat(selectedRadioChoice.getAttribute('data-price'));
                }

                wqpoOption.querySelector(".wqpo-option-price").value = price;
                wqpoOption.querySelector(".wqpo-option-price").setAttribute("data-price", price);
            }

        } else if (optionType === 'dropdown') {
            let thisSelect = wqpoOption.querySelector('.wqpo-select');
            var selectedOption = thisSelect.options[thisSelect.selectedIndex];
            if (selectedOption) {
                if (modifiedPrice = selectedOption.getAttribute("data-modifiedprice")) {
                    price = parseFloat(modifiedPrice);
                } else {
                    price = parseFloat(selectedOption.getAttribute('data-price'));
                }

                wqpoOption.querySelector(".wqpo-option-price").value = price;
                wqpoOption.querySelector(".wqpo-option-price").setAttribute("data-price", price);
            }
        }
    });
}

function updateVariationPrice() {
    // The unique price that appears when a variation is selected.
    let variationWCPriceElement = document.querySelector('.variable-product-page .woocommerce-variation-price');
    if (variationWCPriceElement) {
        
        // Check if a custom price element already exists, if not, create one
        let variationPriceElement = document.querySelector('.wqpo-variation-price');
        if (!variationPriceElement) {
            // Create a new price element for displaying the updated price
            variationPriceElement = document.createElement('div');
            variationPriceElement.classList.add('wqpo-variation-price');
            variationWCPriceElement.parentElement.insertBefore(variationPriceElement, variationWCPriceElement);
        }

        // Modify the price to include the total price of the options
        let totalOptionPrice = combineOptionPrices(); // Get the total price of all option selections.
        let variationPrice = parseFloat(variationWCPriceElement.querySelector('.woocommerce-Price-amount bdi').textContent.replace(/[^\d.]/g, ''));
        let newTotalPrice = 0;
        if (wqpoReplacePrice) {
            newTotalPrice = totalOptionPrice;
        } else {
            newTotalPrice = variationPrice + totalOptionPrice;
        }

        if (variationPrice !== newTotalPrice) {
            // Hide the original price element and show the new custom price element
            variationWCPriceElement.style.display = 'none';
            variationPriceElement.style.display = 'block';
    
            // Update the new custom price element
            variationPriceElement.innerHTML = `
                <span class="price">
                    <span class="woocommerce-Price-amount amount">
                        <bdi><span class="woocommerce-Price-currencySymbol">$</span>${newTotalPrice.toFixed(2)}</bdi>
                    </span>
                </span>
            `;
        } else {
            // If the price is the same, hide the custom price element and show the original price element
            variationWCPriceElement.style.display = 'block';
            variationPriceElement.style.display = 'none';
        }
    }
}

function updateSimplePrice() {
    let modifiesPrice = hasOptionsThatModifyPrice();
    if (modifiesPrice && defaultPriceElement && simplePriceElement) {

        // Modify the price to include the total price of the options
        let totalOptionPrice = combineOptionPrices(); // Get the total price of all option selections.
        let defaultPrice = parseFloat(defaultPriceElement.querySelector('bdi').textContent.replace(/[^\d.]/g, ''));

        let newTotalPrice = 0;
        if (wqpoReplacePrice) {
            newTotalPrice = totalOptionPrice;
        } else {
            newTotalPrice = defaultPrice + totalOptionPrice;
        }

        if (defaultPrice !== newTotalPrice) { 
            simplePriceElement.style.display = 'block';
        }

        // Update the custom price element
        simplePriceElement.innerHTML = `
            <span class="price">
                <span class="woocommerce-Price-amount amount">
                    <bdi><span class="woocommerce-Price-currencySymbol">$</span>${newTotalPrice.toFixed(2)}</bdi>
                </span>
            </span>
        `;
    }
}

function updateHiddenOptionsInputs() {
    // Reset the values and abort if there are no hidden tags.
    wqpoInputHiddenTags.value = '';
    wqpoInputHiddenOptions.value = '';
    hiddenOptionsArray = [];
    if (wqpoHiddenTags.length == 0) { return; }

    // Update the hidden tags input.
    let tagString = '';
    for (let i = 0; i < wqpoHiddenTags.length; i++) {
        if (i != 0 && wqpoHiddenTags[i] != '') {
            tagString += ', ';
        }
        tagString += wqpoHiddenTags[i];
    }
    wqpoInputHiddenTags.value = tagString;

    // Update the hidden option numbers input.
    // For each option, if the option has a tag that is hidden, add the option's data-option attribute to the hiddenOptionsArray.
    let options = document.querySelectorAll('.wqpo-option');
    options.forEach(function(option) {
        let optionClasses = option.classList;
        optionClasses.forEach(function(className) {
            for (let i = 0; i < wqpoHiddenTags.length; i++) {
                if (wqpoHiddenTags[i] != className) {
                    continue;
                } else {
                    hiddenOptionsArray.push(option.getAttribute('data-option'));
                }
            }
        });
    });

    // Remove duplicates from the hiddenOptionsArray.
    hiddenOptionsArray = hiddenOptionsArray.filter((value, index) => hiddenOptionsArray.indexOf(value) === index);
    wqpoInputHiddenOptions.value = hiddenOptionsArray.join(', ');
}

// Loop through all choices on the page. Hide showtag elements by default, only showing if the choice is selected.
function updateShowTags() {
    let hiddenShowTags = [];
    let visibleShowTags = [];
    let hiddenHideTags = [];
    let visibleHideTags = [];
    let choices = document.querySelectorAll('.wqpo-choice');

    // Loop through each choice and hide all elements that have a showtag defined unless the choice is selected.
    // If a choice is selected, add the showtag elements to a visibleShowTags array.
    choices.forEach(function(choice) {
        let isSelected = false;

        if (choice.querySelector('input')) {
            isSelected = choice.querySelector('input').checked;
        } else if (choice.tagName === 'OPTION') {
            let selectElement = choice.closest('select'); // Find the closest <select> element, the parent of this option.
            isSelected = choice.value === selectElement.value; // Check if the current option is selected
        }

        // Handle showtags
        let showTags = choice.getAttribute('data-showtags');
        if (showTags) {
            let tagsArray = showTags.split(/\s*,\s*/); // Split the tags by commas and trim whitespace
            if (isSelected) {
                tagsArray.forEach(function(tag) {
                    visibleShowTags.push(tag.trim()); // Add selected choice's showtags to the visibleShowTags array
                });
            } else {
                tagsArray.forEach(function(tag) {
                    hiddenShowTags.push(tag.trim()); // Add unselected choice's showtags to the hiddenShowTags array
                });
            }
        }

        // Handle hidetags
        let hideTags = choice.getAttribute('data-hidetags');
        if (hideTags) {
            let tagsArray = hideTags.split(/\s*,\s*/); // Split the tags by commas and trim whitespace
            if (isSelected) {
                tagsArray.forEach(function(tag) {
                    hiddenHideTags.push(tag.trim()); // Add selected choice's hidetags to the hiddenHideTags array
                });
            } else {
                tagsArray.forEach(function(tag) {
                    visibleHideTags.push(tag.trim()); // Add unselected choice's hidetags to the visibleHideTags array
                });
            }
        }
    });

    // Remove duplicates from both arrays and remove any visibleShowTags from the hiddenShowTags array.
    hiddenShowTags = hiddenShowTags.filter((value, index) => hiddenShowTags.indexOf(value) === index); // Remove duplicates
    visibleShowTags = visibleShowTags.filter((value, index) => visibleShowTags.indexOf(value) === index); // Remove duplicates
    hiddenShowTags = hiddenShowTags.filter(tag => !visibleShowTags.includes(tag));

    // Remove duplicates from both arrays and remove any hiddenHideTags from the visibleHideTags array.
    hiddenHideTags = hiddenHideTags.filter((item, index) => hiddenHideTags.indexOf(item) === index); // Remove duplicates
    visibleHideTags = visibleHideTags.filter((item, index) => visibleHideTags.indexOf(item) === index); // Remove duplicates
    visibleHideTags = visibleHideTags.filter(tag => !hiddenHideTags.includes(tag)); // Remove any hidden tags from the visible tags.
    visibleHideTags = visibleHideTags.filter(tag => !hiddenShowTags.includes(tag)); // Remove any hiddenShowTags from the visibleHideTags.

    hiddenTags = hiddenShowTags.concat(hiddenHideTags);
    visibleTags = visibleShowTags.concat(visibleHideTags);
    hiddenTags = hiddenTags.filter((value, index) => hiddenTags.indexOf(value) === index);
    visibleTags = visibleTags.filter((value, index) => visibleTags.indexOf(value) === index);
    visibleTags = visibleTags.filter(tag => !hiddenTags.includes(tag));

    // Hide and show elements based on the tags.
    hideChoicesByTag(hiddenTags);
    showChoicesByTag(visibleTags);
}

function deselectAllChoiceTypes(choice) {

    // if choice is a select, deselect all options.
    let choiceSelect = choice.querySelector('select');
    if (choiceSelect) {
        choiceSelect.selectedIndex = 0;
        return;
    }

    // if choice is a text input, clear the value.
    let choiceTextInput = choice.querySelector('input[type="text"]');
    if (choiceTextInput) {
        choiceTextInput.value = '';
        return;
    }

    // if choice is a textarea, clear the value.
    let choiceTextarea = choice.querySelector('textarea');
    if (choiceTextarea) {
        choiceTextarea.value = '';
        return;
    }

    // if choice is a number input, clear the value.
    let choiceNumberInput = choice.querySelector('input[type="number"]');
    if (choiceNumberInput) {
        choiceNumberInput.value = 0;
        return;
    }

    // if choice is a radio, deselect all radios.
    let choiceRadios = choice.querySelectorAll('input[type="radio"]');
    if (choiceRadios.length > 0) {
        choiceRadios.forEach(function(radio) {
            radio.checked = false;
        });
        return;
    }

    // if choice is a checkbox, deselect all checkboxes.
    let choiceCheckboxes = choice.querySelectorAll('input[type="checkbox"]');
    if (choiceCheckboxes.length > 0) {
        choiceCheckboxes.forEach(function(checkbox) {
            checkbox.checked = false;
        });
        return;
    }

    updateCheckboxOptionPrices();
}

function toggleModifiers() {
    let wqpoModifiers = document.querySelectorAll('.wqpo-modifier');

    // Set the data-enabled attribute of each modifier to no if it is not enabled by default.
    wqpoModifiers.forEach(function(modifier) {
        if (modifier.getAttribute('data-enableddefault' === 'on')) {
            modifier.setAttribute('data-enabled', 'yes');
        } else {
            modifier.setAttribute('data-enabled', 'no');
        }
    });

    let enabledModifiersArray = getEnabledModifiersArray();
    if (enabledModifiersArray) {
        enabledModifiersArray = enabledModifiersArray.filter((value, index) => enabledModifiersArray.indexOf(value) === index); // Remove duplicates
        wqpoModifiers.forEach(function(modifier) {
            let modifierTag = modifier.getAttribute('data-tag');
            if (enabledModifiersArray.includes(modifierTag)) {
                modifier.setAttribute('data-enabled', 'yes');
            }
        });
    }

    updateChoicePricing();
    updateOptionPricing();
}

function updateChoicePricing() {
    // Loop through all choices on the page.
    // If the choice has a data-modifiers attribute, reset the price to the data-price attribute.
    // check if the specified modifiers are enabled.
    // If the modifiers are enabled, update the price of the choice.
    let wqpoChoices = document.querySelectorAll('.wqpo-choice');
    wqpoChoices.forEach(function(choice) {
        let choiceModifiers = choice.getAttribute('data-modifiers');
        if (choiceModifiers) {
            let modifiersArray = choiceModifiers.split(/\s*,\s*/);
            let priceHolder = null;
            let price = 0;

            if (choice.getAttribute('data-price')) {
                priceHolder = choice;
                price = parseFloat(choice.getAttribute('data-price'));
            } else if (choice.querySelector('.wqpo-choice-price').getAttribute('data-price')) {
                priceHolder = choice.querySelector('.wqpo-choice-price');
                price = parseFloat(choice.querySelector('.wqpo-choice-price').getAttribute('data-price'));
            }

            let modifiedPrice = price;
            for (let i = 0; i < modifiersArray.length; i++) {
                let modifier = document.querySelector(`.wqpo-modifier[data-tag="${modifiersArray[i]}"]`);
                if (modifier && modifier.getAttribute('data-enabled') === 'yes') {
                    let modifierValue = parseFloat(modifier.value);
                    
                    if (modifier.getAttribute('data-type') === 'adjustment') {
                        modifiedPrice += modifierValue;
                        modifiedPrice = Math.max(0, modifiedPrice); // Prevent price from going below 0.
                    } else if (modifier.getAttribute('data-type') === 'multiplier') {
                        modifiedPrice = modifiedPrice * modifierValue;
                    }
                }
            }

            if (priceHolder) {
                if (modifiedPrice !== price) {
                    modifiedPrice = modifiedPrice.toFixed(2);
                    priceHolder.setAttribute('data-modifiedprice', modifiedPrice);
                } else {
                    priceHolder.removeAttribute('data-modifiedprice');
                }
            }
        }
    });

    updateRadioLabels();
    updateSelectPriceTags();
    updateCheckboxLabelPrices();
}

function updateOptionPricing() {
    // Specifically updates the pricing for number inputs, text inputs, and textareas.
    let wqpoNumberInputs = document.querySelectorAll('.wqpo-number-option');
    let wqpoTextInputs = document.querySelectorAll('.wqpo-text-option');
    let wqpoTextareas = document.querySelectorAll('.wqpo-textarea-option');

    wqpoNumberInputs.forEach(function(wqpoNumberInput) {
        let optionModifiers = wqpoNumberInput.getAttribute('data-modifiers');
        if (optionModifiers) {
            let modifiersArray = optionModifiers.split(/\s*,\s*/);
            let priceHolder = wqpoNumberInput.querySelector('input[type="number"]');
            let price = parseFloat(priceHolder.getAttribute('data-price'));
            let modifiedPrice = price;

            for (let i = 0; i < modifiersArray.length; i++) {
                let modifier = document.querySelector(`.wqpo-modifier[data-tag="${modifiersArray[i]}"]`);
                if (modifier && modifier.getAttribute('data-enabled') === 'yes') {
                    let modifierValue = parseFloat(modifier.value);
                    
                    if (modifier.getAttribute('data-type') === 'adjustment') {
                        modifiedPrice += modifierValue;
                        modifiedPrice = Math.max(0, modifiedPrice); // Prevent price from going below 0.
                    } else if (modifier.getAttribute('data-type') === 'multiplier') {
                        modifiedPrice = modifiedPrice * modifierValue;
                    }
                }
            }

            if (priceHolder) {
                if (modifiedPrice !== price) {
                    modifiedPrice = modifiedPrice.toFixed(2);
                    priceHolder.setAttribute('data-modifiedprice', modifiedPrice);
                } else {
                    priceHolder.removeAttribute('data-modifiedprice');
                }
            }
        }
    });
}



// ----------------------------------------------------------------------------------------------------------
// Helping Functions ----------------------------------------------------------------------------------------
// ----------------------------------------------------------------------------------------------------------

function hasOptionsThatModifyPrice() {
    if (document.getElementsByClassName('wqpo-modifies-price').length > 0) { return true;
    } else { return false; }
}

function combineOptionPrices() {
    let totalOptionPrice = 0;
    let wqpoOptions = document.querySelectorAll('.wqpo-option');
    wqpoOptions.forEach(function(wqpoOption) {
        if (wqpoOption.style.display !== 'none') {
            let optionPrice = parseFloat(wqpoOption.querySelector('.wqpo-option-price').getAttribute('data-price'));
            if (!isNaN(optionPrice)) {
                totalOptionPrice += optionPrice;
            }
        }
    });
    return totalOptionPrice;
}

const formatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2,
});

// Function to check if an element has a parent with the class name provided
function hasParentWithClass(element, className) {
    while (element) {
        if (element.classList && element.classList.contains(className)) {
            return true; // Found a parent with the class, so return true
        }
        element = element.parentElement; // Move up the DOM
    }
    return false; // No parent with the class found
}

// Tags are embedded in the class list of choices or options. Hide any elements that have a tag in the array.
function hideChoicesByTag(tags) {
    if (!tags) { return; }
    wqpoHiddenTags = tags;
    
    tags.forEach(function(tag) {
        let elements = document.querySelectorAll(`.${tag}`);
        if (elements) {
            elements.forEach(function(element) {
                element.style.display = 'none';

                // Deselect any choices that are hidden. If the element that has the class is an option, deselect all inputs of the option's children choices. If it's a choice, deselect the input.
                if (element.classList.contains('wqpo-option')) {
                    let choices = element.querySelectorAll('.wqpo-choice');
                    choices.forEach(function(choice) {
                        deselectAllChoiceTypes(choice);
                    });
                } else if (element.classList.contains('wqpo-choice')) {
                    deselectAllChoiceTypes(element);
                }
            });
        }
    });

    deselectHiddenSelectOptions();
    updateVariationPrice();
    updateSimplePrice();
}

// Tags are embedded in the class list of choices or options. Show any elements that have a tag in the array.
function showChoicesByTag(tags) {
    let currentlyHiddenTags = wqpoHiddenTags;
    
    tags.forEach(function(tag) {
        if (currentlyHiddenTags) {
            currentlyHiddenTags.forEach(function(hiddenTag) {
                if (hiddenTag === tag) {
                    currentlyHiddenTags = currentlyHiddenTags.filter(item => item !== tag);
                }
            });
        }

        let elements = document.querySelectorAll(`.${tag}`);
        if (elements) {
            elements.forEach(function(element) {
                element.style.display = 'block';
            });
        }
    });

    wqpoHiddenTags = currentlyHiddenTags;
    updateHiddenOptionsInputs();
}

function deselectHiddenSelectOptions() {
    let selects = document.querySelectorAll('.wqpo-select');

    selects.forEach(select => {
        let selectedIndex = select.selectedIndex;
        let selectedOption = select.options[selectedIndex];
        let foundVisibleOption = false;

        // If the currently selected option is hidden (display: none), find the first visible option
        if (selectedOption.style.display === 'none') {
            for (let i = 0; i < select.options.length; i++) {
                let option = select.options[i];
                if (option.style.display !== 'none') {
                    select.selectedIndex = i;
                    foundVisibleOption = true;
                    break;
                }
            }
            // If no visible option was found, select the first index (0)
            if (!foundVisibleOption) {
                select.selectedIndex = 0;
            }
        }

        var price = parseFloat(select.options[select.selectedIndex].getAttribute("data-price"));
        updateOptionPrice(price, select);
    });
}

function getEnabledModifiersArray() {
    let enabledModifiersArray = [];

    // Adds the modifiers of selected choices to the enabledModifiersArray.
    let selectedInputs = document.querySelectorAll('input[type="checkbox"]:checked, input[type="radio"]:checked');
    if (selectedInputs) {
        for (let i = 0; i < selectedInputs.length; i++) {
            let parentOption = selectedInputs[i].closest('.wqpo-option');
            if (parentOption.style.display === 'none') { continue; } // Skip hidden options

            let parentChoice = selectedInputs[i].closest('.wqpo-choice');
            if (parentChoice.style.display === 'none') { continue; } // Skip hidden choices

            let enableModifiers = parentChoice.getAttribute('data-enablemodifiers');
            if (enableModifiers) {
                let modifiersArray = enableModifiers.split(/\s*,\s*/);
                for (let j = 0; j < modifiersArray.length; j++) {
                    enabledModifiersArray.push(modifiersArray[j]);
                }
            }
        }
    }

    let selects = document.querySelectorAll('.wqpo-select');
    if (selects) {
        for (let i = 0; i < selects.length; i++) {
            let select = selects[i];
            if (select.style.display === 'none') { continue; } // Skip hidden selects

            let selectedOption = select.options[select.selectedIndex];
            if (!selectedOption) { continue; } // Skip selects with no selected option
            if (selectedOption.style.display === 'none') { continue; } // Skip hidden options

            let enableModifiers = selectedOption.getAttribute('data-enablemodifiers');
            if (enableModifiers) {
                let modifiersArray = enableModifiers.split(/\s*,\s*/);
                for (let j = 0; j < modifiersArray.length; j++) {
                    enabledModifiersArray.push(modifiersArray[j]);
                }
            }
        }
    }

    return enabledModifiersArray;
}


function calculateTextPrice(wqpoTextInput, inputLength) {
    let textOption = wqpoTextInput.closest('.wqpo-option');
    if (textOption.classList.contains('wqpo-chargeperchar')) {
        const pricePerChar = parseFloat(wqpoTextInput.getAttribute("data-price"));
        return pricePerChar * inputLength;
    } else if (inputLength > 0) {
        return parseFloat(wqpoTextInput.getAttribute("data-price"));
    } else {
        return 0;
    }
}
