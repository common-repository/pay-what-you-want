<?php

if ( ! function_exists( 'pwyw_predefined_price_set' ) ) {

    function pwyw_predefined_price_set(){

        $pwyw_predefined_price_set = get_option( 'pwyw_predefined_price_set', PWYW_PREDEFINED_PRICE );
        $pwyw_min_price = get_option( 'pwyw_min_price', 5 );

        foreach ( $pwyw_predefined_price_set as $price ) { ?>
            <div class='price-set-input'>
                <input type='number' pattern='[0-9]+(\\.[0-9][0-9]?)?' name='pwyw_predefined_price_set[]' value='<?php esc_html_e( $price ); ?>' min='<?php esc_html_e( $pwyw_min_price ); ?>' />
                <span class='pwyw-remove'> x </span>
            </div> <?php 
        }
    }

}

if ( ! function_exists( 'pwyw_single_product_price_set' ) ) {

    function pwyw_single_product_price_set( $post_id ){

        $price_set = get_post_meta( $post_id, 'pwyw-single-price', true );
        $pwyw_min_price = get_option( 'pwyw_min_price', PWYW_MIN_PRICE );
        if ( empty( $price_set ) ) {
            $price_set = get_option( 'pwyw_predefined_price_set', PWYW_PREDEFINED_PRICE );
        }

        foreach ( $price_set as $price ) { ?>
            <div class='price-set-input'>
                <input type='number' pattern='[0-9]+(\\.[0-9][0-9]?)?' name='pwyw-single-price[]' value='<?php esc_html_e( $price ); ?>' min='<?php esc_html_e( $pwyw_min_price ); ?>'>
                <span class='pwyw-remove'> x </span>
            </div>
        <?php }
    }

}

if ( ! function_exists( 'pwyw_product_categories_checkbox' ) ) {

    function pwyw_product_categories_checkbox(){

        $selected_categories = get_option( 'pwyw_product_categories' );
        $product_categories = get_categories( ['taxonomy' => 'product_cat'] );

        $selected = '';
        foreach ( $product_categories as $category ) {

            $cat_id = $category->term_id;
            $cat_name = $category->name;

            if ( !empty( $selected_categories ) ) {
                $selected = ( in_array( $cat_id, $selected_categories ) ) ? 'checked' : '';
            } ?>    

            <input type='checkbox' name='pwyw_product_categories[]' value='<?php esc_html_e( $cat_id ); ?>' id='product-cat-<?php esc_html_e( $cat_id ); ?>' <?php esc_html_e( $selected ); ?> />
            <label for='product-cat-<?php esc_html_e( $cat_id ); ?>'> <?php esc_html_e( $cat_name ); ?> </label> <?php 
        }
    }

}

// Get Fraction of the price
if ( ! function_exists( 'pwyw_get_fraction_of_the_price' ) ) {

    function pwyw_get_fraction_of_the_price( $price, $fraction ){

        $price_set = [];
        $difference = $price / $fraction;

        for ( $i = 1; $i <= $fraction ; $i++ ) {

            $number = (int) ( $i * $difference );
            $price_set[] = $number;     
            $price -= ( $price - $number );
        }

        return $price_set;
    }
}

// Get the price set
if ( ! function_exists( 'pwyw_get_the_price_set' ) ) {

    function pwyw_get_the_price_set( $product_id, $price ){
        
        $price_set = [];
        $pwyw_price_group = get_option( 'pwyw_price_group', PWYW_PRICE_GROUP );
        // 0 = predefined price
        // 1 = Depend on product price

        if ( 0 == $pwyw_price_group ) {
            $price_set = get_option( 'pwyw_predefined_price_set', PWYW_PREDEFINED_PRICE );
        }else{
            $pwyw_price_fraction = get_option( 'pwyw_price_fraction', PWYW_PRICE_FRACTION );
            $price_set = pwyw_get_fraction_of_the_price( $price, $pwyw_price_fraction );
        }

        return $price_set;
    }
}

// Is eligible to action
if ( ! function_exists( 'pwyw_is_eligible_to_take_action' ) ) {

    function pwyw_is_eligible_to_take_action($product){

        $action = true;

        $product_id = $product->get_id();

        $pwyw_enable_plugin     = get_option( 'pwyw_enable_plugin', PWYW_ENABLE_PLUGIN );

        if ( 'enabled' != $pwyw_enable_plugin ) {
            $action = false;
        }

        $pwyw_products_area = get_option( 'pwyw_products_area', PWYW_PRODUCTS_AREA );
        // 1 = all products
        // 0 = specific categories

        if ( 0 == $pwyw_products_area ) {
            
            $pwyw_product_categories = get_option( 'pwyw_product_categories' );
            if( ! has_term( $pwyw_product_categories, 'product_cat', $product_id ) ) {
                $action = false;
            }
        }

        return $action;
    }
}

// Sanitize Array
function pwyw_hf_recursive_sanitize_array( $array ) {
    foreach ( $array as $key => &$value ) {
        if ( is_array( $value ) ) {
            $value = recursive_sanitize_text_field( $value );
        }
        else {
            $value = sanitize_text_field( $value );
        }
    }

    return $array;
}