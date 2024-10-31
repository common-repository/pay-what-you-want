(function($){

	$(document).ready(function() {

		var select_price_area = $('body .bw-single-price-area');

		// excute code only when we need this
		if ( 1 == select_price_area.length ) {

			let input_price_field = select_price_area.find('.bw-price');

			select_price_area.on('click', '.bw-btn-price', function(e) {

				e.preventDefault();

				let price = $(this).data('price');
				input_price_field.val(price);

				$(this).siblings().removeClass('price-selected');
				$(this).addClass('price-selected');

				//console.log(price);

			});

			// for own price
			select_price_area.on('change', '#bw-own-price', function(e) {
				let price = $(this).val();
				input_price_field.val(price);
				$(".bw-btn-price").removeClass('price-selected');
			});

		}	
	
	//console.log(select_price_area.length);

	});
})(jQuery);