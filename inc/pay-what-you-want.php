<?php
/**
 * Add before Add to cart
 */
if ( ! function_exists( 'bw_pwyw_price_select_box_before_add_to_cart' ) ) {

	function bw_pwyw_price_select_box_before_add_to_cart() {

		global $product;
		$product_id = $product->get_id();
		$pwyw_min_price = get_option( 'pwyw_min_price', PWYW_MIN_PRICE );

		if ( !pwyw_is_eligible_to_take_action($product) ) {
			return;
		}
		
		$price = $product->get_price();
		$_pwyw_override_defaults = get_post_meta( $product_id, '_pwyw_override_defaults', true );
		
		if ( 'yes' == $_pwyw_override_defaults ) {
			$price_set = get_post_meta( $product_id, 'pwyw-single-price', true );
		}else{
			$price_set = pwyw_get_the_price_set($product_id, $price);
		}

		$pwyw_allow_own_price = get_option( 'pwyw_allow_own_price', PWYW_ALLOW_OWN_PRICE );

		?>
		
		<div class='bw-single-price-area'>
			<input type="hidden" name="bw-price" class="bw-price" value="0">
			<div class="bw-select-price">

				<?php 
				foreach ($price_set as $price) {
					if ( $price >= $pwyw_min_price ) { ?>
						<button data-price='<?php esc_html_e( $price ); ?>' class='bw-btn-price'>
							<?php echo get_woocommerce_currency_symbol(); esc_html_e( $price ); ?>
						</button> <?php
					}
				}
				?>
				<?php if ( 'enabled' == $pwyw_allow_own_price ) { ?>			
				<input type="number" pattern="[0-9]+(\\.[0-9][0-9]?)?" min="<?php esc_html_e( $pwyw_min_price ); ?>" id="bw-own-price" class='my-price' value="" placeholder="My Price">
				<?php } ?>

			</div>
		</div>

		<?php
	}
	add_action( 'woocommerce_before_add_to_cart_button', 'bw_pwyw_price_select_box_before_add_to_cart', 10 );
}

/**
 * Add to cart
 */
if ( ! function_exists( 'bw_pwyw_add_price_to_cart' ) ) {

	function bw_pwyw_add_price_to_cart( $cart_item_data, $product_id, $variation_id ) {
		
		$bw_price = filter_input( INPUT_POST, 'bw-price' );
		$pwyw_min_price = get_option( 'pwyw_min_price', PWYW_MIN_PRICE );

		if ( empty( $bw_price ) || ($bw_price < $pwyw_min_price) ) {
			return $cart_item_data;
		}

		$cart_item_data['bw-price'] = $bw_price;

		return $cart_item_data;
	}
	add_filter( 'woocommerce_add_cart_item_data', 'bw_pwyw_add_price_to_cart', 10, 3 );
}

// Update Cart
if ( ! function_exists( 'pwyw_update_price_to_cart' ) ) {

	function pwyw_update_price_to_cart( $cart_obj ) {

	    foreach( $cart_obj->get_cart() as $key => $value ) {
	        if ( isset( $value['bw-price'] ) ) {

	            $price = $value['bw-price'];
	            $value['data']->set_price( $price );

	        }
	    }
	}
	add_action( 'woocommerce_before_calculate_totals', 'pwyw_update_price_to_cart', 10, 3 );
}

// Remove Add to cart text on loop
if ( ! function_exists( 'pwyw_loop_add_to_cart_option' ) ) {

	function pwyw_loop_add_to_cart_option( $html, $product ) {

		if ( !pwyw_is_eligible_to_take_action($product) ) {
			return $html;
		}

		$add_to_cart_btn = get_option( 'pwyw_add_to_cart_btn_inside_loop', PWYW_ADD_TO_CART_BTN_INSIDE_LOOP );
		if ( 'enabled' == $add_to_cart_btn ) {
			return $html;	
		}else{
			return '<a href="' . get_permalink( $product->get_id() ) . '" class="button pwyw-learn-more">' . __( 'Learn More' ) . '</a>';
		}
	}
	add_filter( 'woocommerce_loop_add_to_cart_link', 'pwyw_loop_add_to_cart_option', 10, 2 );
}

// Update price text on single product page
if ( ! function_exists( 'pwyw_product_price_text' ) ) {

	function pwyw_product_price_text( $price , $product ) {
		
		if ( !pwyw_is_eligible_to_take_action($product) ) {
			return $price;
		}

		$pwyw_price_text = get_option( 'pwyw_price_text', PWYW_PRICE_TEXT );
	    return str_replace("%price%", $price, $pwyw_price_text);
	}
	add_filter( 'woocommerce_get_price_html', 'pwyw_product_price_text', 10, 2 );
}