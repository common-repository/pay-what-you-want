<?php

	$pwyw_enable_plugin = get_option( 'pwyw_enable_plugin', PWYW_ENABLE_PLUGIN );
	$plugin_enabled = ( $pwyw_enable_plugin != 'disabled' ) ? 'checked' : '';
	
	$pwyw_allow_own_price = get_option( 'pwyw_allow_own_price', PWYW_ALLOW_OWN_PRICE );
	$allow_own_price = ( $pwyw_allow_own_price != 'disabled' ) ? 'checked' : '';

	$pwyw_add_to_cart_btn_inside_loop = get_option( 'pwyw_add_to_cart_btn_inside_loop', PWYW_ADD_TO_CART_BTN_INSIDE_LOOP );
	$add_to_cart_btn = ( $pwyw_add_to_cart_btn_inside_loop != 'disabled' ) ? 'checked' : '';
	
	$pwyw_products_area = get_option( 'pwyw_products_area', PWYW_PRODUCTS_AREA );
	$pwyw_price_group = get_option( 'pwyw_price_group', PWYW_PRICE_GROUP );
	
	$pwyw_product_categories = get_option( 'pwyw_product_categories' );
	$pwyw_price_fraction = get_option( 'pwyw_price_fraction', PWYW_PRICE_FRACTION );
	$pwyw_min_price = get_option( 'pwyw_min_price', PWYW_MIN_PRICE );
	$pwyw_price_text = get_option( 'pwyw_price_text', PWYW_PRICE_TEXT );
	
	$all_products = '';
	$specific_categories = '';
	$based_on_product_price = '';
	$predefined_price = '';

	if ( 1 == $pwyw_products_area ) {
		$all_products = 'checked';
	}else{
		$specific_categories = 'checked';
	}

	if ( 1 == $pwyw_price_group ) {
		$based_on_product_price = 'checked';
	}else{
		$predefined_price = 'checked';
	}
?>

<div id="bw-pwyw">

	<h1> <?php _e('Pay What You Want', 'pwyw'); ?> </h1>
	<hr />

	<form id="pwyw-settings-form" method='POST' action="<?php echo admin_url('admin-post.php'); ?>">

		<?php wp_nonce_field('pwyw'); ?>

		<input type="hidden" name="action" value="pwyw_admin_settings">

		<table class="form-table" role="presentation">

			<tbody>

				<tr>
					<th scope="row">
						<label for="pwyw_enable_plugin"> <?php _e( 'Enable This Plugin', 'pwyw' ); ?> </label>
					</th>
					<td>
						<input name="pwyw_enable_plugin" type="checkbox" id="pwyw_enable_plugin" value="enabled" <?php esc_html_e( $plugin_enabled ); ?> />
						<label for="pwyw_enable_plugin"> Enable </label>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label><?php _e('Set this option for:', 'pwyw'); ?></label>
					</th>
					<td>
						<div>
							<input type='radio' id='pwyw_all_products' name='pwyw_products_area' value="1" <?php esc_html_e( $all_products ); ?> >
							<label for='pwyw_all_products'><?php _e('All products', 'pwyw'); ?></label>
						</div>

						<div class="<?php esc_html_e( $specific_categories ); ?>" >
							<input type='radio'id='pwyw_specific_categories' name='pwyw_products_area' value="0" <?php esc_html_e( $specific_categories ); ?>>
							<label for='pwyw_specific_categories'><?php _e('Some specific categories', 'pwyw'); ?></label>
							
							<div class="pwyw_product_categories pwyw-open">
								<?php pwyw_product_categories_checkbox(); ?>
							</div>
						</div>
						
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label><?php _e('Price set will depend on:', 'pwyw'); ?></label>
					</th>
					<td>
						<div class="<?php esc_html_e( $based_on_product_price ); ?>">
							<input type='radio' id='pwyw-product-price' name='pwyw_price_group' value="1" <?php esc_html_e( $based_on_product_price ); ?>>
							<label for='pwyw-product-price'><?php _e('Product price (How many pieces it will be?)', 'pwyw'); ?></label>
							
							<div id="pwyw_price_fraction" class="pwyw-open">
								<input type="number" name="pwyw_price_fraction" value="<?php esc_html_e( $pwyw_price_fraction ); ?>">
							</div>
						</div>

						<div class="<?php esc_html_e( $predefined_price ); ?>">
							<input type='radio' id='pwyw_predefined_price' name='pwyw_price_group' value="0" <?php esc_html_e( $predefined_price ); ?>>
							<label for='pwyw_predefined_price'><?php _e('A predefined set of price', 'pwyw'); ?></label>
							
							<div class="pwyw-open">
								<div id="pwyw_predefined_price_set">
									<?php pwyw_predefined_price_set(); ?>
								</div>
								<div id="pwyw-add-price">
									Add More Price +
								</div>
							</div>
						</div>

						<p><em>You can edit this price set for each product individually.</em></p>

						<?php if ( pwyw_fs()->is_not_paying() ) { ?>
							<p><em>This is a premium feature & you can upgrade it at an affordable price. <a href="<?php echo admin_url('edit.php?post_type=product&page=bw-pwyw-pricing'); ?>" style="color: green">upgrade to premium now →</a></em></p>
						<?php } ?>
						
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="pwyw-allow-own-price"> <?php _e( 'Allow Custmer To Enter Own Price', 'pwyw' ); ?> </label>
					</th>
					<td>
						<input name="pwyw_allow_own_price" type="checkbox" id="pwyw-allow-own-price" value="enabled" <?php esc_html_e( $allow_own_price ); ?> />
						<label for="pwyw-allow-own-price"> Enable </label>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="pwyw-add-to-cart-btn"> <?php _e( 'Enable "add to cart" button inside loop', 'pwyw' ); ?> </label>
					</th>
					<td>
						<input name="pwyw_add_to_cart_btn_inside_loop" type="checkbox" id="pwyw-add-to-cart-btn" value="enabled" <?php esc_html_e( $add_to_cart_btn ); ?> />
						<label for="pwyw-add-to-cart-btn"> Enable </label>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label> <?php _e( 'Minimun Price', 'pwyw' ); ?> </label>
					</th>
					<td>
						<input type="number" pattern='[0-9]+(\\.[0-9][0-9]?)?' name="pwyw_min_price" value="<?php esc_html_e( $pwyw_min_price ); ?>">
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label> <?php _e( 'Price text on single product page', 'pwyw' ); ?> </label>
					</th>
					<td>
						<input type="text" name="pwyw_price_text" value="<?php esc_html_e( $pwyw_price_text ); ?>">
					</td>
				</tr>

			</tbody>

		</table>

		<div>
			<?php submit_button('Submit'); ?>
		</div>

	</form>
</div>