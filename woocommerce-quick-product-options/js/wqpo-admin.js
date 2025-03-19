let wqpoAdminOptionsGroup = document.getElementById('wqpo_admin_options_group');
let wqpoOptionCount = document.getElementById('wqpo_option_count');
let wqpoOptionCountChanged = document.getElementById('wqpo_option_count_changed');
let wqpoAdminModifiersGroup = document.getElementById('wqpo_admin_modifiers_group');
let wqpoModifierCount = document.getElementById('wqpo_modifier_count');
let wqpoModifierCountChanged = document.getElementById('wqpo_modifier_count_changed');

function wqpo_add_modifier(e) {
    e.preventDefault();
    wqpoModifierCountChanged.value = 'yes';

    // If the modifiers group is hidden, show it. Otherwise, add a new modifier.
    if (wqpoAdminModifiersGroup.classList.contains('wqpo-hidden')) {
        wqpoAdminModifiersGroup.classList.remove('wqpo-hidden');
    } else {
        // Select the first modifier, clone it, and erase the fields to make them blank.
        let wqpoAdminModifiers = document.getElementsByClassName('wqpo-admin-modifier');
        let wqpoAdminModifier = wqpoAdminModifiers[0].cloneNode(true);
        wqpo_make_modifier_blank(wqpoAdminModifier);

        // Append the new blank modifier to the bottom of the modifiers group.
        wqpoAdminModifiersGroup.appendChild(wqpoAdminModifier);

        // Update the "Modifier #" for each modifier.
        wqpo_identify_new_modifier_order(wqpoAdminModifiers);

        // Augment wqpo_modifier_count by 1.
        wqpoModifierCount.value = parseInt(wqpoModifierCount.value, 10) + 1;
    }
}

function wqpo_remove_modifier(e) {
    e.preventDefault();
    wqpoModifierCountChanged.value = 'yes';

    // Make this modifier blank.
    let wqpoThisModifier = e.target.closest('.wqpo-admin-modifier');
    wqpo_make_modifier_blank(wqpoThisModifier);

    // If there is only one modifier left, hide the modifiers group. Otherwise, remove this modifier.
    let wqpoAdminModifiers = document.getElementsByClassName('wqpo-admin-modifier');
    if (wqpoAdminModifiers.length === 1) {
        wqpo_hide(wqpoAdminModifiersGroup);
    } else {
        wqpoThisModifier.remove();
    }

    // Update the "Modifier #" for each modifier.
    wqpo_identify_new_modifier_order(wqpoAdminModifiers);

    // Decrease wqpo_modifier_count by 1.
    if (wqpoModifierCount.value > 1) {
        wqpoModifierCount.value = parseInt(wqpoModifierCount.value, 10) - 1;
    }
}

function wqpo_add_option(e) {
    e.preventDefault();
    wqpoOptionCountChanged.value = 'yes';

    // If the options group is hidden, show it. Otherwise, add a new option.
    if (wqpoAdminOptionsGroup.classList.contains('wqpo-hidden')) {
        wqpoAdminOptionsGroup.classList.remove('wqpo-hidden');
    } else {
        // Select the first option, clone it, and erase the fields to make them blank.
        let wqpoAdminOptions = document.getElementsByClassName('wqpo-admin-option');
        let wqpoAdminOption = wqpoAdminOptions[0].cloneNode(true);
        wqpo_make_option_blank(wqpoAdminOption);
        
        // Append the new blank option to the bottom of the options group.
        wqpoAdminOptionsGroup.appendChild(wqpoAdminOption);

        // Set the default Order Number wqpo-admin-osort for the new option.
        // The default Order Number is the number of options + 1.
        let wqpoAdminOptionNumber = document.getElementsByClassName('wqpo-admin-option').length;
        wqpoAdminOption.getElementsByClassName('wqpo-admin-osort')[0].getElementsByTagName('input')[0].value = wqpoAdminOptionNumber;

        // Update the "Option #" for each option.
        wqpo_identify_new_option_order();

        // Augment wqpo_option_count by 1.
        wqpoOptionCount.value = parseInt(wqpoOptionCount.value, 10) + 1;
    }
}

function wqpo_remove_option(e) {
    e.preventDefault();
    wqpoOptionCountChanged.value = 'yes';

    // Make this option blank.
    let wqpoThisOption = e.target.closest('.wqpo-admin-option');
    wqpo_make_option_blank(wqpoThisOption);

    // If there is only one option left, hide the options group. Otherwise, remove this option.
    let wqpoAdminOptions = document.getElementsByClassName('wqpo-admin-option');
    if (wqpoAdminOptions.length === 1) {
        wqpo_hide(wqpoAdminOptionsGroup);
    } else {
        wqpoThisOption.remove();
    }

    // Update the "Option #" for each option.
    wqpo_identify_new_option_order(wqpoAdminOptions);

    // Decrease wqpo_option_count by 1.
    if (wqpoOptionCount.value > 1) {
        wqpoOptionCount.value = parseInt(wqpoOptionCount.value, 10) - 1;
    }
}

function wqpo_make_modifier_blank(modifier) {
    // Make all inputs blank.
    let modifierFields = modifier.getElementsByTagName('input');
    for (let i = 0; i < modifierFields.length; i++) {
        modifierFields[i].value = '';
    }

    // Make all checkboxes unchecked.
    let modifierCheckboxes = modifier.getElementsByTagName('input');
    for (let i = 0; i < modifierCheckboxes.length; i++) {
        modifierCheckboxes[i].checked = false;
    }

    // Make the modifier type dropdown the first option.
    let modifierSelect = modifier.getElementsByTagName('select')[0];
    modifierSelect.selectedIndex = 0;

    // Remove all wqpo-admin-modifier-* class names and reset it to radio.
    let modifierClasses = modifier.classList;
    for (let i = 0; i < modifierClasses.length; i++) {
        if (modifierClasses[i].match(/wqpo-admin-modifier-/)) {
            modifier.classList.remove(modifierClasses[i]);
        }
    }
    modifier.classList.add('wqpo-admin-modifier-adjustment');
}

function wqpo_make_option_blank(option) {
    // Make all inputs blank.
    let optionFields = option.getElementsByTagName('input');
    for (let i = 0; i < optionFields.length; i++) {
        optionFields[i].value = '';
    }

    // Make all checkboxes unchecked.
    let optionCheckboxes = option.getElementsByTagName('input');
    for (let i = 0; i < optionCheckboxes.length; i++) {
        optionCheckboxes[i].checked = false;
    }

    // Make the option type dropdown the first option.
    let optionSelect = option.getElementsByTagName('select')[0];
    optionSelect.selectedIndex = 0;

    // Remove all wqpo-admin-option-* class names and reset it to radio.
    let optionClasses = option.classList;
    for (let i = 0; i < optionClasses.length; i++) {
        if (optionClasses[i].match(/wqpo-admin-option-/)) {
            option.classList.remove(optionClasses[i]);
        }
    }
    option.classList.add('wqpo-admin-option-radio');

    // Remove all choices except the first one. Hide the choices group.
    let optionChoices = option.getElementsByClassName('wqpo-admin-choice');
    let optionChoicesGroup = option.getElementsByClassName('wqpo-admin-choices')[0];
    while (optionChoices.length > 1) {
        optionChoicesGroup.removeChild(optionChoices[1]);
    }

    // Make the first choice Sort Number 1.
    optionChoices[0].getElementsByClassName('wqpo-admin-csort')[0].getElementsByTagName('input')[0].value = 1;

    // Set the choice count wqpo-choice-count to 1.
    option.getElementsByClassName('wqpo-choice-count')[0].value = 1;

    // Make the Choice Labels and Placeholders "Choice Name".
    let optionChoiceLabels = option.getElementsByClassName('wqpo-admin-cname');
    for (let i = 0; i < optionChoiceLabels.length; i++) {
        optionChoiceLabels[i].getElementsByTagName('label')[0].innerHTML = 'Choice Name';
        optionChoiceLabels[i].getElementsByTagName('input')[0].placeholder = 'Choice Name';
    }
}

function wqpo_identify_new_modifier_order(wqpoAdminModifiers = null) {
    if (!wqpoAdminModifiers) {
        wqpoAdminModifiers = document.getElementsByClassName('wqpo-admin-modifier');
    }

    for (let i = 0; i < wqpoAdminModifiers.length; i++) {
        // All Modifier Checkbox Inputs have a name like wqpo_menableddefault_modifiernumber. Ensure the modifiernumber is updated.
        let wqpoAdminModifierCheckboxes = wqpoAdminModifiers[i].querySelectorAll('input[type="checkbox"]');
        for (let j = 0; j < wqpoAdminModifierCheckboxes.length; j++) {
            let parentDiv = wqpoAdminModifierCheckboxes[j].closest('.wqpo-admin-field');
            if (parentDiv) {
                let inputName = wqpoAdminModifierCheckboxes[j].name;
                let inputLabel = parentDiv.getElementsByTagName('label')[0];
                let inputNameParts = inputName.split('_');
                let newInputName = inputNameParts[0] + '_' + inputNameParts[1] + '_' + i;
                wqpoAdminModifierCheckboxes[j].name = newInputName;
                inputLabel.htmlFor = newInputName;
            }
        }
    }
}

function wqpo_identify_new_option_order(wqpoAdminOptions = null) {
    if (!wqpoAdminOptions) {
        wqpoAdminOptions = document.getElementsByClassName('wqpo-admin-option');
    }

    // Flash the option number if it is changing.
    // Update the Choice's Option Number. For example, where 3 is the option number in wqpo_o3_cname[], but the new option is 2, change it to wqpo_o2_cname[].
    for (let i = 0; i < wqpoAdminOptions.length; i++) {
        let wqpoOptionNumberSpan = wqpoAdminOptions[i].getElementsByClassName('wqpo-admin-onumber')[0].getElementsByTagName('span')[0];
        let wqpoEmbeddedNumber = parseInt(wqpoOptionNumberSpan.innerHTML.trim().match(/\d+/)[0], 10) - 1;

        // If the new option number is not the same as the previously embedded number, flash the number.
        if (i !== wqpoEmbeddedNumber) {
            wqpoOptionNumberSpan.classList.add('wqpo-admin-flash');
        }

        // Update the Option Number in Each Choice's Input Names and Label For Attributes.
        let wqpoAdminChoices = wqpoAdminOptions[i].getElementsByClassName('wqpo-admin-choice');
        for (let j = 0; j < wqpoAdminChoices.length; j++) {
            let wqpoAdminChoiceInputs = wqpoAdminChoices[j].getElementsByTagName('input');
            let wqpoAdminChoiceLabels = wqpoAdminChoices[j].getElementsByTagName('label');
            for (let k = 0; k < wqpoAdminChoiceInputs.length; k++) {
                wqpoAdminChoiceInputs[k].name = wqpoAdminChoiceInputs[k].name.replace(/wqpo_o\d+/, 'wqpo_o' + (i));
            }
            for (let k = 0; k < wqpoAdminChoiceLabels.length; k++) {
                wqpoAdminChoiceLabels[k].htmlFor = wqpoAdminChoiceLabels[k].htmlFor.replace(/wqpo_o\d+/, 'wqpo_o' + (i));
            }
        }

        // Update the Choice Count Input Name.
        let wqpoAdminChoiceCount = wqpoAdminOptions[i].getElementsByClassName('wqpo-choice-count')[0];
        wqpoAdminChoiceCount.name = wqpoAdminChoiceCount.name.replace(/wqpo_o\d+/, 'wqpo_o' + (i));

        // All Option Checkbox Inputs have a name like wqpo_oselectfirst_optionnumber. Ensure the optionnumber is updated.
        let wqpoAdminOptionCheckboxes = wqpoAdminOptions[i].querySelectorAll('input[type="checkbox"]');
        for (let j = 0; j < wqpoAdminOptionCheckboxes.length; j++) {
            let parentDiv = wqpoAdminOptionCheckboxes[j].closest('.wqpo-admin-field');
            if (parentDiv) {
                let inputName = wqpoAdminOptionCheckboxes[j].name;
                let inputLabel = parentDiv.getElementsByTagName('label')[0];
                let inputNameParts = inputName.split('_');
                let newInputName = inputNameParts[0] + '_' + inputNameParts[1] + '_' + i;
                wqpoAdminOptionCheckboxes[j].name = newInputName;
                inputLabel.htmlFor = newInputName;
            }
        }
    }

    // Remove the flash class after a short delay.
    setTimeout(function() {
        for (let i = 0; i < wqpoAdminOptions.length; i++) {
            let wqpoOptionNumberSpan = wqpoAdminOptions[i].getElementsByClassName('wqpo-admin-onumber')[0].getElementsByTagName('span')[0];

            if (wqpoOptionNumberSpan.classList.contains('wqpo-admin-flash')) {
                wqpoOptionNumberSpan.classList.remove('wqpo-admin-flash');
            }
        }

        // Update the Option # for each option regardless of whether it flashed.
        setTimeout(function() {
            for (let i = 0; i < wqpoAdminOptions.length; i++) {
                let wqpoOptionNumberSpan = wqpoAdminOptions[i].getElementsByClassName('wqpo-admin-onumber')[0].getElementsByTagName('span')[0];
                wqpoOptionNumberSpan.innerHTML = 'Option #' + (i + 1);
            }
        }, 50);
    }, 200);
}

function wqpo_add_choice(e) {
    e.preventDefault();

    let wqpoAdminOption = e.target.closest('.wqpo-admin-option');
    let wqpoAdminChoiceGroup = wqpoAdminOption.getElementsByClassName('wqpo-admin-choices')[0];

    // If the choices group is hidden, show it. Otherwise, add a new choice.
    if (wqpoAdminChoiceGroup.classList.contains('wqpo-hidden')) {
        wqpoAdminChoiceGroup.classList.remove('wqpo-hidden');
    } else {
        // Select the first choice of the parent option, clone it, and erase the fields to make them blank.
        let wqpoAdminChoices = wqpoAdminOption.getElementsByClassName('wqpo-admin-choice');
        let wqpoAdminNewChoice = wqpoAdminChoices[0].cloneNode(true);
        wqpo_make_choice_blank(wqpoAdminNewChoice);

        // Append the new blank choice to the bottom of the choices group.
        wqpoAdminOption.getElementsByClassName('wqpo-admin-choices')[0].appendChild(wqpoAdminNewChoice);

        // Set the default Order Number wqpo-admin-csort for the new choice.
        // The default Order Number is the number of choices + 1.
        let wqpoAdminChoiceNumber = wqpoAdminChoices.length;
        wqpoAdminNewChoice.getElementsByClassName('wqpo-admin-csort')[0].getElementsByTagName('input')[0].value = wqpoAdminChoiceNumber;
    }

    // Augment wqpo-choice-count by 1.
    let wqpoAdminChoiceCount = wqpoAdminOption.getElementsByClassName('wqpo-choice-count')[0];
    wqpoAdminChoiceCount.value = parseInt(wqpoAdminChoiceCount.value, 10) + 1;
}

function wqpo_remove_choice(e) {
    e.preventDefault();

    // Make this choice blank.
    let wqpoAdminThisChoice = e.target.closest('.wqpo-admin-choice');
    wqpo_make_choice_blank(wqpoAdminThisChoice);

    // If there is only one choice left, hide the choices group. Otherwise, remove this option.
    let wqpoAdminOption = e.target.closest('.wqpo-admin-option');
    let wqpoAdminChoiceGroup = wqpoAdminOption.getElementsByClassName('wqpo-admin-choices')[0];
    let wqpoAdminChoices = wqpoAdminOption.getElementsByClassName('wqpo-admin-choice');
    if (wqpoAdminChoices.length === 1) {
        wqpo_hide(wqpoAdminChoiceGroup);
    } else {
        wqpoAdminThisChoice.remove();
    }

    // Reduce wqpo-choice-count by 1.
    let wqpoAdminChoiceCount = wqpoAdminOption.getElementsByClassName('wqpo-choice-count')[0];
    wqpoAdminChoiceCount.value = parseInt(wqpoAdminChoiceCount.value, 10) - 1;
}

// Listen for Dropdown and Checkbox changes.
document.addEventListener('change', function(event) {
    changedInput = event.target;

    if (changedInput) {
        if (changedInput.matches('select[name="wqpo_otype[]"]')) {
            let wqpoAdminOption = changedInput.closest('.wqpo-admin-option');

            // Remove all wqpo-admin-option-* class names.
            let wqpoAdminOptionClasses = wqpoAdminOption.classList;
            for (let i = 0; i < wqpoAdminOptionClasses.length; i++) {
                if (wqpoAdminOptionClasses[i].match(/wqpo-admin-option-/)) {
                    wqpoAdminOption.classList.remove(wqpoAdminOptionClasses[i]);
                }
            }

            // Add a class name to the Option based on the Option Type: wqpo-admin-option-radio, wqpo-admin-option-checkbox, wqpo-admin-option-number, etc.
            let wqpoAdminOptionType = changedInput.value;
            let wqpoOptionClass = 'wqpo-admin-option-' + wqpoAdminOptionType;
            wqpoAdminOption.classList.add(wqpoOptionClass);

            // Show or Hide the wqpo-admin-choiceinput, wqpo-admin-cremove buttons, and wqpo-add-choice button based on the Option Type.
            // Remove all choices but the first one based on the Option Type.
            // Change wqpo-admin-cname Label and Placeholder to "Description (Optional)".
            let wqpoAdminChoiceContainer = wqpoAdminOption.getElementsByClassName('wqpo-admin-choices')[0];
            let wqpoAdminChoiceLabels = wqpoAdminOption.getElementsByClassName('wqpo-admin-cname');
            let wqpoAdminChoiceCount = wqpoAdminOption.getElementsByClassName('wqpo-choice-count')[0];
            
            if (wqpoAdminOptionType === 'text' || wqpoAdminOptionType === 'textarea' || wqpoAdminOptionType === 'number') {
                // Rename the Choice Label and Placeholder to "Description (Optional)".
                for (let i = 0; i < wqpoAdminChoiceLabels.length; i++) {
                    wqpoAdminChoiceLabels[i].getElementsByTagName('label')[0].innerHTML = 'Description (Optional)';
                    wqpoAdminChoiceLabels[i].getElementsByTagName('input')[0].placeholder = 'Description (Optional)';
                }

                if (wqpoAdminChoiceCount.value == 0) {
                    wqpo_show(wqpoAdminChoiceContainer);
                    wqpoAdminChoiceCount.value = parseInt(wqpoAdminChoiceCount.value, 10) + 1;
                }
            } else {
                for (let i = 0; i < wqpoAdminChoiceLabels.length; i++) {
                    wqpoAdminChoiceLabels[i].getElementsByTagName('label')[0].innerHTML = 'Choice Name';
                    wqpoAdminChoiceLabels[i].getElementsByTagName('input')[0].placeholder = 'Choice Name';
                }
            }

        } else if (changedInput.matches('select[name="wqpo_mtype[]"]')) {
            let wqpoAdminModifier = changedInput.closest('.wqpo-admin-modifier');

            // Remove all wqpo-admin-modifier-* class names.
            let wqpoAdminModifierClasses = wqpoAdminModifier.classList;
            for (let i = 0; i < wqpoAdminModifierClasses.length; i++) {
                if (wqpoAdminModifierClasses[i].match(/wqpo-admin-modifier-/)) {
                    wqpoAdminModifier.classList.remove(wqpoAdminModifierClasses[i]);
                }
            }

            // Add a class name to the Modifier based on the Modifier Type: wqpo-admin-modifier-radio, wqpo-admin-modifier-checkbox, wqpo-admin-modifier-number, etc.
            let wqpoAdminModifierType = changedInput.value;
            let wqpoModifierClass = 'wqpo-admin-modifier-' + wqpoAdminModifierType;
            wqpoAdminModifier.classList.add(wqpoModifierClass);
        }
    }
});



function wqpo_hide(element) {
    if (!element.classList.contains('wqpo-hidden')) {
        element.classList.add('wqpo-hidden');
    }
}

function wqpo_show(element) {
    if (element.classList.contains('wqpo-hidden')) {
        element.classList.remove('wqpo-hidden');
    }
}

function wqpo_hide_all(elements) {
    for (let i = 0; i < elements.length; i++) {
        if (!elements[i].classList.contains('wqpo-hidden')) {
            elements[i].classList.add('wqpo-hidden');
        }
    }
}

function wqpo_show_all(elements) {
    for (let i = 0; i < elements.length; i++) {
        if (elements[i].classList.contains('wqpo-hidden')) {
            elements[i].classList.remove('wqpo-hidden');
        }
    }
}

function wqpo_make_choice_blank(choice) {
    // Make all inputs blank.
    let choiceFields = choice.getElementsByTagName('input');
    for (let i = 0; i < choiceFields.length; i++) {
        choiceFields[i].value = '';
    }
}
