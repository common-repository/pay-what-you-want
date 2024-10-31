<?php

// Register the Tab
if ( ! function_exists( 'pwyw_product_data_tab' ) ) {
    
    function pwyw_product_data_tab( $tabs ) {
        $tabs['pwyw-tab'] = array(
            'label'     => __( 'Pay What You Want', 'pwyw' ),
            'target'    => 'pwyw-tab',
            'class'     => array( 'show_if_simple','show_if_variable' ),
        );
        return $tabs;
    }
    add_filter( 'woocommerce_product_data_tabs', 'pwyw_product_data_tab' );
}


// Settings Fields.
if ( ! function_exists( 'pwyw_single_product_settings_fields' ) ) {
    
    function pwyw_single_product_settings_fields() {
        
        global $post;
        $post_id = $post->ID; ?>

        <div id='pwyw-tab' class = 'panel woocommerce_options_panel'>
        	<div class = 'options_group' > <?php

                if ( pwyw_fs()->is_plan('pay_what_you_want_pro') ) {

        			// Override Defaults
        			woocommerce_wp_checkbox(
        			    array(
        					'id'           => '_pwyw_override_defaults',
        					'label'        => __('Override Defaults?', 'pwyw' ),
        					'description'  => __( 'You must check this field to update this section', 'pwyw' )
        			    )
        			);
                } ?>

                <?php if ( pwyw_fs()->is_not_paying() ) { ?>
                    <p class="form-field _pwyw_override_defaults_field ">
                        <label for="_pwyw_override_defaults_">Override Defaults?</label><input type="checkbox" class="checkbox" style="" name="" id="_pwyw_override_defaults_" value="0"> <span class="description">This is a premium feature, it won't work until you upgrade, <a href="<?php echo admin_url('edit.php?post_type=product&page=bw-pwyw-pricing'); ?>" style="color: green;background: #fdc22e;display: inline-block;padding: 0px 10px;">upgrade now →</a></span>
                    </p>
                <?php } ?>

                <div class="pwyw-price-box">
                    <div id='pwyw_predefined_price_set'>
                        <label>Set Price For This product</label>

                        <?php pwyw_single_product_price_set($post_id); ?>
                    </div>
                    <div id="pwyw-add-price">
                        Add More Price +
                    </div>
                </div>

    		</div>
        </div><?php
    }
    add_action('woocommerce_product_data_panels', 'pwyw_single_product_settings_fields');
}

// Save data
if ( ! function_exists( 'woocom_save_proddata_custom_fields' ) ) {

    function woocom_save_proddata_custom_fields($post_id) {

        $_pwyw_override_defaults = isset( $_POST['_pwyw_override_defaults'] ) ? 'yes' : 'no';
        $pwyw_single_price = isset( $_POST['pwyw-single-price'] ) ? pwyw_hf_recursive_sanitize_array( $_POST['pwyw-single-price'] ) : array();

        update_post_meta( $post_id, '_pwyw_override_defaults', $_pwyw_override_defaults );
        
        if ( 'yes' == $_pwyw_override_defaults && !empty( $pwyw_single_price ) ) {
            update_post_meta( $post_id, 'pwyw-single-price', ( $pwyw_single_price ) );
        }
    }

    add_action( 'woocommerce_process_product_meta_simple', 'woocom_save_proddata_custom_fields'  );
    add_action( 'woocommerce_process_product_meta_variable', 'woocom_save_proddata_custom_fields'  );
}