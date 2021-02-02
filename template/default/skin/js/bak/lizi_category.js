$(function(){
	var $box = $('#priceform'),
    	$form = $box.find('form'),
        $input = $form.find('input[type=text]'),
        $hp = $("#hidden_price");
	$input.on('focus', function() {
        $box.addClass('focus');
     });
	$(document).on("click", function(e) {
        if ($(e.target).parents("#priceform").length == 0) {
 			$box.removeClass('focus');
		}
	});
})