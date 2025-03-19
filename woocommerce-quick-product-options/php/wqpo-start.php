<?php defined( 'ABSPATH' ) || exit;

class WC_Product_Options_Plugin {   
    public function __construct() {
        add_action( 'woocommerce_loaded', array( $this, 'load_plugin') );
		add_filter( 'woocommerce_product_data_tabs', array($this, 'add_wqpo_tab'), 99, 1);
        add_action( 'woocommerce_product_data_panels', array($this, 'add_wqpo_tab_fields'));
    }

    public function load_plugin() {
        require_once dirname(__FILE__).'/wqpo-functions.php';
    }

	public function add_wqpo_tab( $tabs ){
		$tabs['woocommerce_quick_product_options'] = array(
			'label' => __('Product Options', 'wqpo'),
			'target' => 'wqpo_admin_tab',
			'class'  => array(),
			'priority' => 90              
		);

		return $tabs;
	}

    public function add_wqpo_tab_fields(){
        require_once dirname( __FILE__ ) . '/admin/wqpo-admin.php';   
    }
}
new WC_Product_Options_Plugin();