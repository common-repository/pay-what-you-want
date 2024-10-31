<?php
if ( ! class_exists( 'BW_PWYW_Settings_Page' ) ) {
	class BW_PWYW_Settings_Page {
		
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'pwyw_create_page' ) );
			add_action( 'admin_post_pwyw_admin_settings', array( $this, 'pwyw_save_settings' ) );
			add_filter( 'plugin_action_links_' . PWYW_PLUGIN_BASENAME , array( $this , 'pwyw_add_settings_link' ) );
		}

		public function pwyw_create_page() {
	    
	    	$parent_slug	= __( 'edit.php?post_type=product', 'pwyw');
			$page_title 	= __( 'Pay What You Want', 'pwyw' );
			$menu_title 	= __( 'Pay What You Want', 'pwyw' );
			$capability 	= 'manage_options';
			$menu_slug 		= 'bw-pwyw';
			$callback   	= array( $this, 'pwyw_page_content' );

	    	add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $callback );
		}

		public function pwyw_page_content() {
	    	require_once plugin_dir_path( __FILE__ )."/form.php";
		}

		public function pwyw_save_settings() {

			check_admin_referer( "pwyw" );

	        // Get values from user
	        $pwyw_enable_plugin       	= isset( $_POST['pwyw_enable_plugin'] ) ? 'enabled' : 'disabled';
	        $pwyw_allow_own_price       = isset( $_POST['pwyw_allow_own_price'] ) ? 'enabled' : 'disabled';
	        $add_to_cart_btn   		    = isset( $_POST['pwyw_add_to_cart_btn_inside_loop'] ) ? 'enabled' : 'disabled';
	        $pwyw_products_area 		= sanitize_text_field( $_POST['pwyw_products_area'] );
	        $pwyw_price_group   		= sanitize_text_field( $_POST['pwyw_price_group'] );
	        $pwyw_price_fraction   		= sanitize_text_field( $_POST['pwyw_price_fraction'] );
	        $pwyw_min_price   			= sanitize_text_field( $_POST['pwyw_min_price'] );
	        $pwyw_price_text   			= sanitize_text_field( $_POST['pwyw_price_text'] );
	        $pwyw_product_categories	= isset( $_POST['pwyw_product_categories'] ) ? pwyw_hf_recursive_sanitize_array( $_POST['pwyw_product_categories'] ) : array();
	        $pwyw_predefined_price_set	= isset( $_POST['pwyw_predefined_price_set'] ) ? pwyw_hf_recursive_sanitize_array( $_POST['pwyw_predefined_price_set'] ) : array();
				
			update_option( 'pwyw_enable_plugin', $pwyw_enable_plugin );
			update_option( 'pwyw_allow_own_price', $pwyw_allow_own_price );
			update_option( 'pwyw_add_to_cart_btn_inside_loop', $add_to_cart_btn );
			update_option( 'pwyw_products_area', $pwyw_products_area );
			update_option( 'pwyw_price_group', $pwyw_price_group );
			update_option( 'pwyw_price_fraction', $pwyw_price_fraction );
			update_option( 'pwyw_min_price', $pwyw_min_price );
			update_option( 'pwyw_price_text', $pwyw_price_text );
			update_option( 'pwyw_product_categories', $pwyw_product_categories );
			update_option( 'pwyw_predefined_price_set', $pwyw_predefined_price_set );
			
			wp_redirect('edit.php?post_type=product&page=bw-pwyw');

		}

		public function pwyw_add_settings_link( $links ) {
	        $settings = sprintf( "<a href='%s'>%s</a>" , admin_url( 'edit.php?post_type=product&page=bw-pwyw' ) , __( 'Settings' , 'pwya' ) );
	        $pro = sprintf( "<a href='%s' style='color: red; font-weight: 700'>%s</a>" , admin_url( 'edit.php?post_type=product&page=bw-pwyw-pricing' ) , __( 'Get pro' , 'pwya' ) );

	        $links[] = $settings;
	        if ( pwyw_fs()->is_not_paying() ) {
		        $links[] = $pro;
		    }


	        return $links;
	    }

	}

	new BW_PWYW_Settings_Page();

}