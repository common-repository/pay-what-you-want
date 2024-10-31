;(function($) {

	$(document).ready( function() {

		// Open Options if required
		$("#pwyw-settings-form").on('click', 'input[type=radio]', function(){
			$(this).closest('div').find('.pwyw-open').slideDown();
		});

		// Remove price set
		$("#pwyw_predefined_price_set").on('click','.pwyw-remove', function(){
			$(this).closest('div').remove();
		});

		// Add new price to the set
		var newPrice = "<div class='price-set-input'><input type='number' pattern='[0-9]+(\\.[0-9][0-9]?)?' name='pwyw_predefined_price_set[]' value='0' /> <span class='pwyw-remove'> x </span></div>";
		var newSinglePrice = "<div class='price-set-input'><input type='number' pattern='[0-9]+(\\.[0-9][0-9]?)?' name='pwyw-single-price[]' value='0' /> <span class='pwyw-remove'> x </span></div>";
		
		$("#pwyw-add-price").on('click', function(){
			if ($(this).parents('.pwyw-price-box').length) {
				$("#pwyw_predefined_price_set").append(newSinglePrice);
			}else{
				$("#pwyw_predefined_price_set").append(newPrice);
			}
			
		});

  	});

})(jQuery);