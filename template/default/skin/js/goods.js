$(function() {
	goods_price();
	/*商品详情页tab切换 strat*/
	$('.itemContentHead li').click(function() {
		type = '#' + $(this).attr('data-position');
		$('html,body').animate({
			scrollTop: $(type).offset().top - 15
		}, 300);
	})
	/*商品详情页tab切换 end*/

	/*商品详情页滚动tab切换 strat*/
	$(window).scroll(function() {
		docT = $(document).scrollTop();
		objD1 = $('#D1').offset().top - 15;
		objD2 = $('#D2').offset().top - 15;
		objD3 = $('#D3').offset().top - 15;
		if (docT > objD1) {
			$('.itemBar').fadeIn();
		} else {
			$('.itemBar').fadeOut();
		}
		if (docT > objD1) {
			$('#iteamBarHead li').removeAttr('class');
			$('#H1').attr('class', 'itemContentHeadFocus');
		}
		if (docT > objD2 - 1) {
			$('#iteamBarHead li').removeAttr('class');
			$('#H2').attr('class', 'itemContentHeadFocus');
		}
		if (docT > objD3 - 1) {
			$('#iteamBarHead li').removeAttr('class');
			$('#H3').attr('class', 'itemContentHeadFocus');
		}
	})
	/*商品详情页滚动tab切换 end*/

	$(".item-thumbs").slide({
		mainCell: ".bd ul",
		autoPage: true,
		effect: "left",
		vis: 6
	});

	$("#item-thumbs li a").click(function() {
		$("#item-thumbs li").removeClass("current");
		$(this).parent().addClass("current");
	})

	/*会员价，折扣*/
	$("#membership,.membership_con").mouseenter(function() {
		$(".membership_con").show();
	})

	$("#membership,.membership_con").mouseleave(function() {
		$(".membership_con").hide();
	})

	// $(".seemore_items").slide({mainCell:".bd ul",effect:"top",autoPage:true,scroll:3,vis:3});

	$("#skunum").on('click', 'span', function(e) {
		if ($(this).hasClass("add")) {
			countNum(1);
		} else {
			countNum(-1);
		}
		return false;
	});
	
	$(".attr_list").on('click', 'a', function(e) {
		var gallery_id = $(this).data("galleryid");
		var attrtype = $(this).data("attrtype");
		var image = $(this).data("image");
		/*thumbs list 当前状态*/
		
		if (gallery_id) {
			var thumbs_img = "#goods_img_" + gallery_id;
			$(".item-thumbs .bd ul li").removeAttr('class');
			$(thumbs_img).attr('class', 'current');
			$('#J_prodImg').attr('src', image);
			$('#Zoomer').attr('href', image);
			$('.MagicZoomBigImageCont img').attr('src', image);
		}
		
		/* 商品规格选定状态 */
		/* 1:单选;
		 * 2:多选; 
		 */

		if (attrtype == 1) {
			if (!$(this).hasClass('cattsel')) {
				$(this).parent().find("a").removeAttr('class');
				$(this).attr('class', 'cattsel');
				$(this).parent().find("input[type='radio']").attr("checked",false);
				$(this).find("input[type='radio']").get(0).checked=true;
			}
		} else if(attrtype == 2) {
			//多选
			if ($(this).hasClass('cattsel')) {
				$(this).removeClass("cattsel")
				$(this).find("input[type='checkbox']").attr("checked",false);
			} else {
				$(this).addClass("cattsel");
				$(this).find("input[type='checkbox']").prop('checked',true);		
			}
		}
		
		/*计算商品价格*/
		if(attrtype) goods_price();

	});

})

/*计算商品价格与库存*/
function goods_price() {
	getSelectedAttributes(function (res){
		if(res.code == 0){
			
			$('#shows_number').html(res.data.goods_number);
			$('#SELLINGPRICE').html(parseInt(res.data.goods_price).toFixed(2));
		}
	});
}

function countNum(i) {
	getSelectedAttributes(function (res){
		if(res.code == 0){
			var $count_box = $("#skunum");
			var $input = $count_box.find('input');
			var num = $input.val();
			if (!$.isNumeric(num)) {
				alert("请您输入正确的购买数量!")
				$input.val('1');
				return;
			}
			num = parseInt(num) + i;
			stocks = parseInt(res.data.goods_number);
			switch (true) {
				case num == 0:
					$input.val('1');
					alert('您至少得购买1件商品！');
					break;
				case num > stocks:
					$input.val(stocks);
					alert('您最多只能购买'+stocks+'件商品！');
					break;
				default:
					$input.val(num);
					break;
			}
		}
	});	
}
