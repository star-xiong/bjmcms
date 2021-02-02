var machine_time;
var isBegin = false;
$(function() {
	//初始化购物车
	cartInit();
	/*鲜一下 strat*/
	//   $(".item-list .p-img").hover(function(){
	// 	$(this).find(".p-price").animate({"bottom":"24"},250);
	// 	$(this).find(".p-addcart").animate({"bottom":"0"},250);
	// },function(){
	// 	$(this).find(".p-price").animate({"bottom":"0"},250);
	// 	$(this).find(".p-addcart").animate({"bottom":"-24"},250);
	// });
	// $(".product-list .item").hover(function(){
	// 	$(this).addClass("hover");
	// },function(){
	// 	$(this).removeClass("hover");
	// });

	// var u = 210;
	// $('.arm-btn').click(function(){
	// 	clearInterval(machine_time);
	// 	$(".arm-operator").addClass("arm-curr");
	// 	$(".arm-btn").animate({"top":"40"},250,function(){$(".arm-btn").animate({"top":"0"},250,function(){$(".arm-operator").removeClass("arm-curr")})});
	// 	$(".product-item .item").css('top',0);		
	// 	if(isBegin) return false;
	// 	isBegin = true;		
	// 	var num_arr = numRand();
	// 	$(".product-item .item").each(function(index){
	// 		var _num = $(this);
	// 		setTimeout(function(){
	// 			_num.animate({ 
	// 				top: -(u*num_arr[index])
	// 			},{
	// 				duration: 300+index*300,
	// 				easing: "easeInOutQuad",
	// 				complete: function(){
	// 					if(!_num.find(".item-list").eq(num_arr[index]).hasClass("price_list0")){_num.find(".item-list").eq(num_arr[index]).addClass("price_list0 flag"+index+num_arr[index]);}
	// 					if(index==3) isBegin = false;	
	// 				}
	// 			});
	// 		}, index * 200);
	// 	});
	// });
	//    arm_machine();	
	// $(".arm-btn").hover(function(){
	// 	clearInterval(machine_time);
	// 	$(".arm-btn").animate({"top":"5"},250);
	// },function(){
	// 	$(".arm-btn").animate({"top":"0"},250);
	// 	arm_machine();	
	// });
	/*鲜一下 end*/

	/*品牌列表 strat*/
	//$(".brandWrap").hover(function(){$(this).find(".brandDesc").animate({top:"-20px"},400,"swing")},function(){$(this).find(".brandDesc").stop(!0,!1).animate({top:"0px"},400,"swing")});
	/*品牌列表 end*/

	/*购物车弹出 strat*/
	$("#BJM_CARTINFO_TOP").on("mouseenter", function() {
		$(this).addClass("topbar-cart-active")
		$(this).find(".cart-menu").slideDown();
	}).on("mouseleave", function() {
		$(this).removeClass("topbar-cart-active");
		$(this).find(".cart-menu").stop().slideUp(300, function() {
			$(".cart-section").removeClass("cart-section-on")
		});
	})
	/*购物车弹出 end*/

	$('.dianqi-activity a').hover(
		function() {
			$(this).animate({
				left: '-5px'
			}, 300);
		},
		function() {
			$(this).animate({
				left: '0'
			}, 300);
		}
	);

	/*顶部下拉 strat*/
	$(".J_userInfo").on("mouseenter", ".user", function() {
		$('.user-menu').slideDown(200);
		$(this).addClass("user-active");
	}).on("mouseleave", ".user", function() {
		$('.user-menu').slideUp(200);
		$(this).removeClass("user-active");
	})
	/*顶部下拉 end*/

	/*分类导航*/
	if ($('.j-extendCate').hasClass('dis-n')) {
		$('.j-allCate').on('mouseenter', function() {
			$(this).find('.catetit').addClass('hover');
			$(this).find('.j-extendCate').show();
		}).on('mouseleave', function() {
			$(this).find('.catetit').removeClass('hover');
			$(this).find('.j-extendCate').hide();
		});
	}
	$.fn.extendCate = function() {
		$.each(this, function() {
			var timer1 = null,
				timer2 = null,
				flag = false;
			$(this).on("mouseenter", function() {
				if (flag) {
					clearTimeout(timer2);
				} else {
					var _this = $(this);
					timer1 = setTimeout(function() {
						if (parseInt(_this.find(".catetwo").css("left")) == 200) {
							_this.find('.cateone').addClass('hover');
							_this.find(".catetwo").fadeIn(100).stop(true, true).animate({
								"left": 210
							}, 100, function() {
								$(".catetwo").css("left", 210);
							});
						} else {
							_this.find('.cateone').addClass('hover');
							_this.find(".catetwo").show();
						}
						flag = true;
					}, 100);
				}
			}).on("mouseleave", function() {
				if (flag) {
					var _this = $(this);
					timer2 = setTimeout(function() {
						_this.find(".catetwo").hide();
						_this.find('.cateone').removeClass('hover');
						flag = false;
					}, 150);
				} else {
					clearTimeout(timer1);
				}
			});
		});
	}
	$(".j-extendCate li").extendCate();
	$(".j-extendCate").on("mouseleave", function() {
		$(this).find('.cateone').removeClass('hover');
		$(this).find('.catetwo').css("left", 210).hide();
	});

	/*明星单品 start*/
	// $(".J_starGoodsCarousel").slide({
	// 	prevCell:".box-hd .more .control-prev",
	// 	nextCell:".box-hd .more .control-next",
	// 	mainCell:".rainbow-list",
	// 	autoPage:true,
	// 	effect:"left",
	// 	autoPlay:false,
	// 	vis:5,
	// 	scroll:5,
	// 	trigger:"click",
	// 	pnLoop:false
	// });
	/*明星单品 end*/

	/*分类楼层单品鼠标经过效果*/
	$(".brick-item-m").mouseenter(function() {
		$(this).addClass("brick-item-active");
	}).mouseleave(function() {
		$(this).removeClass("brick-item-active");
	})

	/*购物车鼠标移入效果 start*/
	$("#BJM_CARTINFO").on("mouseenter", function() {
		$("#BJM_CARTINFO").animate(200, function() {
			$("#BJM_CARTINFO").addClass("hd_cart_hover");
			$("p.fail").show();
		})
	}).on("mouseleave", function() {
		$("#BJM_CARTINFO").animate(200, function() {
			$("#BJM_CARTINFO").removeClass("hd_cart_hover");
			$("p.fail").hide();
		})
	});
	/*购物车鼠标移入效果 end*/

	/*分类导航鼠标移入效果 start*/
	h = this;
	b = $("#J_mainCata");
	e = $("#J_subCata");
	i = $("#main_nav");
	l = null;
	k = null;
	d = false;
	g = false;
	f = false;

	i.on("mouseenter", function() {
		var m = $(this);
		if (l !== null) {
			clearTimeout(l);
		}
		if (f) {
			return;
		}
		l = setTimeout(function() {
			m.addClass("main_nav_hover");
			b.stop().show().animate({
				opacity: 1
			}, 300);
		}, 200);
	}).on("mouseleave", function() {
		if (l !== null) {
			clearTimeout(l);
		}
		l = setTimeout(function() {
			e.css({
				opacity: 0,
				left: "100px"
			}).find(".J_subView").hide();
			b.hide();
			g = false;
			if (!f) {
				b.stop().delay(200).animate({
					opacity: 0
				}, 300, function() {
					i.removeClass("main_nav_hover");
					b.hide().find("li").removeClass("current");
				});
			} else {
				b.find("li").removeClass("current");
			}
		}, 200);
	});


	$("#J_mainCata li").mouseenter(function() {
		m = $(this);
		n = $("#J_mainCata li").index($(this));

		/*
		if (n > 4) {
			m.addClass("current").siblings("li").removeClass("current");
			e.find(".J_subView").hide();
			return false;
		}
		*/
		if (n > 1) {
			subView_h = (e.find(".J_subView").eq(n).height());
			b_h = b.height();
			m_h = m.height();
			m_p = m.position();


			x = b_h - subView_h;
			x = (x / 2);

			v = parseInt(m_p.top) + m_h;


			if (parseInt(subView_h + x) > v) {
				x += 35;
				e.css({
					top: x
				});
			} else {

				s = v - x - subView_h;
				x += s;
				x += 35;

				e.css({
					top: x
				});

			}


		} else {
			e.css({
				top: "35px"
			});
		}

		if (g) {
			m.addClass("current").siblings("li").removeClass("current");
			e.find(".J_subView").hide().eq(n).show();
		} else {
			if (k !== null) {
				clearTimeout(k);
			}
			k = setTimeout(function() {
				m.addClass("current").siblings("li").removeClass("current");
				g = true;
				if (d) {
					e.css({
						opacity: 1,
						left: "213px"
					}).find(".J_subView").eq(n).show();
				} else {
					c(n);
				}
			}, 200);
		}
	})

	function c(m) {
		e.css({
			opacity: 1,
			left: "213px"
		}).find(".J_subView").eq(m).show();
		d = true;
	}
	/*分类导航鼠标移入效果 end*/

	// $("#h_box h3").click(function(){
	// 	var i = $("#h_box h3").index($(this));

	// 	if($("#h_box ul").eq(i).is(":hidden"))
	// 	{
	// 		$(this).addClass("h3_all");
	// 		$("#h_box ul").eq(i).show();
	// 	}
	// 	else
	// 	{
	// 		$(this).removeClass("h3_all");
	// 		$("#h_box ul").eq(i).hide();
	// 	}
	// })
})

/******分类页商品数量加减****/
// function modifyBuyNum(d, a) {
// 	var b;
//     var c;
//    if (a == -1) {
//         c = $(d).parents(".shopping_num").find("input");
//         b = parseInt(c.val()) || 1;
//         if (b == 1) {
//             return
//         } else {
//             if (b == 2) {
//                 $(d).attr("class", "p-reduce disable")
//             } else {
//                 $(d).prev().attr("class", "add")
//             }
//             c.val(b + a)
//         }
//     } else {
//         c = $(d).parents(".shopping_num").find("input");
//         b = parseInt(c.val()) || 1;
//         $(d).next().attr("class", "p-reduce")
//       	c.val(b + a)
//     }		
// }

// function arm_machine(){
// 	machine_time = setInterval(function(){
// 		$(".arm-txt").stop().animate({"top":"37"},300,function(){
// 			$(".arm-txt").animate({"top":"25"},300,function(){$(".arm-txt").animate({"top":"37"},300,function(){$(".arm-txt").animate({"top":"25"},300)})})});
// 	},5000);
// }

// function numRand() {
// 	var rand= [];
// 	for(var i = 0;i<5;i++){
// 		var itemId = $(".product-item .item").eq(i).attr("item-id");
// 		var len = $(".product-item .item").eq(i).find(".item-list").length - 1;
// 		var num = parseInt((Math.random() * len + 1));
// 		while (itemId == num) {
// 			num = parseInt((Math.random() * len + 1));
// 		}
// 	    $(".product-item .item").eq(i).attr("item-id",num);
// 		rand.push(num);
// 	}
// 	return rand;  
// }

/**
 * 获得选定的商品属性,(价格及库存)并传递给回调函数; 
 */
function getSelectedAttributes(callbackFun) {
	var field = $('#FORMBUY').serializeArray();
	$.ajax({
		url: '/getgoodsattr.html',
		type: 'post',
		data: field,
		async: true,
		dataType: 'json',
		success(result) {
			callbackFun(result); // 执行传递过来的匿名函数
		},
		error(err) {
			console.log(err.msg);
		}
	});
}

/* *
 * 初始化购物车 
 */
function cartInit() {
	$.ajax({
		url: '/flow.html',
		type: 'get',
		async: true,
		dataType: 'json',
		success(result) {
			addToCartResponse(result);
		},
		error(err) {
			console.log(err.msg);
		}
	});
}
/* *
 * 添加商品到购物车 
 */
function addToCart(goods_id) {
	var field = $('#FORMBUY').serializeArray();
	console.log(field);
	if(field.length == 0){
		field = {'goods_id':goods_id, 'number':1};
	}

	$.ajax({
		url: '/addcart.html',
		type: 'post',
		data: field,
		async: true,
		dataType: 'json',
		success(result) {
			addToCartResponse(result);
		},
		error(err) {
			console.log(err.msg);
		}
	});
}
function addToCart_quick(goodsId)
{
  var field = $('#FORMBUY').serializeArray();
  console.log(field);
  if(field.length == 0){
  	field = {'goods_id':goods_id, 'number':1};
  }
  
  $.ajax({
  	url: '/addcart.html',
  	type: 'post',
  	data: field,
  	async: true,
  	dataType: 'json',
  	success(result) {
  		addToCartResponse_quick(result);
  	},
  	error(err) {
  		console.log(err.msg);
  	}
  });

}

/* *
 * 处理添加商品到购物车的反馈信息
 */
function addToCartResponse(result) {
	if (result.code > 0) {
		// 如果需要缺货登记，跳转
		if (result.code == 2) {
			if (confirm(result.msg)) {
				//location.href = 'user.php?act=add_booking&id=' + result.goods_id + '&spec=' + result.product_spec;
			}
		}
		// 没选规格，弹出属性选择框
		else if (result.code == 3) {
			console.log(result);
			openSpeDiv(result.data, result.data.goods_id, result.data.goods_number);
		} else {
			alert(result.msg);
		}
	} else {
		if (result.data.number > 0) {
			var cartInfo = document.getElementById('BJM_CARTINFO');
			var cartInfo_top = document.getElementById('BJM_CARTINFO_TOP');
			var cartInfo_content = document.getElementById('BJM_CARTINFO_content');
			var cart_url = '/flow.html';
			var cartinfo_top_items = '';
			var cartinfo_content_items = '';
			var cart_items = result.data.items;

			for (var i = 0; i < cart_items.length; i++) {
				cartinfo_content_items += '<div class="cart-item">';
				cartinfo_content_items += '	<div class="item-goods">';
				cartinfo_content_items += '		<span class="p-img"><a href="/goods/' + cart_items[i]['goods_id'] +
					'.html"><img src="' + cart_items[i]['goods_img'] + '" class="loading" width="50" height="50" alt="' + cart_items[i]
					['goods_name'] + '"></a></span>';
				cartinfo_content_items += '		<div class="p-name"><a href="/goods/' + cart_items[i]['id'] + '.html" title="' +
					cart_items[i]['goods_name'] + '">' + cart_items[i]['goods_name'] + '</a></div>';
				cartinfo_content_items += '		<div class="p-price"><strong><font>￥' + cart_items[i]['goods_price'] +
					'</font></strong>×' + cart_items[i]['goods_number'] + '</div>';
				cartinfo_content_items += '		<a href="javascript:;" class="p-del" onclick="deleteCartGoods(' + cart_items[i][
					'goods_id'
				] + ')">删除</a>';
				cartinfo_content_items += '	</div>';
				cartinfo_content_items += '</div>';

				cartinfo_top_items += '	  <li class="clearfix ';
				if (i == 0) cartinfo_top_items += 'first';
				cartinfo_top_items += '">';
				cartinfo_top_items += '    	<div class="cart-item">';
				cartinfo_top_items += '         <a class="thumb" target="_blank" href="/goods/' + cart_items[i]['goods_id'] +
					'.html">';
				cartinfo_top_items += '              <img src="' + cart_items[i]['goods_img'] + '">';
				cartinfo_top_items += '          </a>';
				cartinfo_top_items += '          <a class="name" target="_blank" href="/goods/' + cart_items[i]['goods_id'] +
					'.html">' + cart_items[i]['goods_name'] + '</a>';
				cartinfo_top_items += '          <span class="price">' + cart_items[i]['goods_price'] + '元 x ' + cart_items[i][
					'goods_number'
				] + '</span>';
				cartinfo_top_items += '          <a class="btn-del delItem" href="javascript:deleteCartGoods(' + cart_items[i][
					'goods_id'
				] + ');">';
				cartinfo_top_items += '              <i class="iconfont"></i>';
				cartinfo_top_items += '          </a>';
				cartinfo_top_items += '        </div>';
				cartinfo_top_items += '    </li>';
			}

			if (cartInfo_top) {
				var cart_top_html = '';
				cart_top_html += '<a class="cart-mini" href="/flow.html"><i class="iconfont"></i>购物车';
				cart_top_html += '<span class="mini-cart-num J_cartNum" id="hd_cartnum">' + result.data.number + '</span>';
				cart_top_html += '</a>';
				cart_top_html += '<div id="J_miniCartList" class="cart-menu" style="display: none;">';
				cart_top_html += '    <ul>';
				cart_top_html += cartinfo_top_items;
				cart_top_html += '    </ul>';
				cart_top_html += '	  <div class="count clearfix">';
				cart_top_html += '	     <span class="total">';
				cart_top_html += '        <strong>合计：<em id="hd_cart_total">' + result.data.amount + '元</em></strong>';
				cart_top_html += '       </span>';
				cart_top_html += '       <a class="btnprimary" href="/flow.html">去购物车结算</a>';
				cart_top_html += '    </div>';
				cart_top_html += '</div>';

				cartInfo_top.innerHTML = cart_top_html;
			}
			if (cartInfo) {
				cartInfo.innerHTML = '<span class="cart_num">' + result.data.number + '</span>';
			}
			if (cartInfo_content) {
				var cart_html = '';
				cart_html += '<form id="formCart" name="formCart" method="post" action="/flow.html">';
				cart_html += '	<div class="sidebar-cart-box">';
				cart_html += '     <h3 class="sidebar-panel-header">';
				cart_html += '        <a href="javascript:;" class="title"><i class="cart-icon"></i><em class="title">购物车</em></a>';
				cart_html += '        <span class="close-panel"></span>';
				cart_html += '     </h3>';
				cart_html += '     <div class="cart-panel-main">';
				cart_html += '        <div class="cart-panel-content" style="height: 831px;">';
				cart_html += '            <div class="cart-list">';
				cart_html += cartinfo_content_items;
				cart_html += '               </div>';
				cart_html += '            </div>';
				cart_html += '       </div>';
				cart_html += '       <div class="cart-panel-footer">';
				cart_html += '            <div class="cart-footer-checkout">';
				cart_html += '                <div class="number"><strong class="count">' + result.data.number +
					'</strong>件商品</div>';
				cart_html += '                <div class="sum">共计：<strong class="total">' + result.data.amount + '元</strong></div>';
				cart_html += '                <a class="btn" href="/flow.html" target="_blank">去购物车结算</a>';
				cart_html += '            </div>';
				cart_html += '        </div>';
				cart_html += '   </div>';
				cart_html += '</form>';

				cartInfo_content.innerHTML = cart_html;
			}

			if (result.data.goods_id) {
				MoveBox(result.data.goods_id);
			}

			// if (result.data.one_step_buy == '1')
			// {
			//   location.href = cart_url;
			// }
			// else
			// {
			//   MoveBox(result.data.goods_id);
			// }
		}

	}
}

/* *
 * 处理添加商品到购物车的反馈信息
 */
function addToCartResponse_quick(result)
{
  if (result.code > 0) {
  	// 如果需要缺货登记，跳转
  	if (result.code == 2) {
  		if (confirm(result.msg)) {
  			//location.href = 'user.php?act=add_booking&id=' + result.goods_id + '&spec=' + result.product_spec;
  		}
  	}
  	// 没选规格，弹出属性选择框
  	else if (result.code == 3) {
  		openSpeDiv(result.data, result.data.goods_id, result.data.goods_number);
  	} else {
  		alert(result.msg);
  	}
  } else {
	location.href = '/flow.html';
  }
}

/**
 * 加入购物车的飞入效果
 */
function MoveBox(gid) {
	var obj1 = $('#li_' + gid);
	if (obj1.length > 0) {
		flyCollect(gid, 'collectBox'); // 飞入购物车
	} else {
		// 购物车页面加入操作，刷新页面
		//location.href = 'flow.php?step=cart';
	}
}

/*******************************************************************************
 * 飞入购物车
 */
function flyCollect(gid, mudidi) {
	var gid = ".pic_img_" + gid;
	var cart = $('#' + mudidi);
	var flyElm = $(gid).clone().css('opacity', '0.7');
	flyElm.css({
		'z-index': 9000,
		'display': 'block',
		'position': 'absolute',
		'top': $(gid).offset().top + 'px',
		'left': $(gid).offset().left + 'px',
		'width': $(gid).width() + 'px',
		'height': $(gid).height() + 'px',
		'-moz-border-radius': 100 + 'px',
		'border-radius': 100 + 'px',
		'border-width': 1 + 'px',
		'border-color': '#333',
		'border-style': 'solid',
		'text-align': 'center'
	});
	$('body').append(flyElm);
	flyElm.animate({
		top: $(cart).offset().top,
		left: $(cart).offset().left,
		width: 20,
		height: 20
	}, 'slow');
}

/* 以下四个函数为属性选择弹出框的功能函数部分 */
//检测层是否已经存在
function docEle() {
	return document.getElementById(arguments[0]) || false;
}

//生成属性选择层 parent
function openSpeDiv(message, goods_id, number) {
	var _id = "speDiv";
	var m = "mask";
	if (docEle('FORMBUY'))
		document.removeChild(docEle('FORMBUY'));
	if (docEle(m))
		document.removeChild(docEle(m));
	// 计算上卷元素值
	var scrollPos;
	if (typeof window.pageYOffset != 'undefined') {
		scrollPos = window.pageYOffset;
	} else if (typeof document.compatMode != 'undefined' && document.compatMode != 'BackCompat') {
		scrollPos = document.documentElement.scrollTop;
	} else if (typeof document.body != 'undefined') {
		scrollPos = document.body.scrollTop;
	}

	var i = 0;
	var sel_obj = document.getElementsByTagName('select');
	while (sel_obj[i]) {
		sel_obj[i].style.visibility = "hidden";
		i++;
	}

	// 新激活图层
	var newDiv = document.createElement("div");
	var speAttr = document.createElement("div");
	newDiv.id = _id;
	speAttr.className = 'attr-list';
	// 生成层内内容
	newDiv.innerHTML = '<div class="pop-header"><span>请选择商品属性</span><a href="javascript:void(0);" onclick="javascript:cancel_div()" title="关闭"  class="spe-close"></a></div>';
		
	var attributes = message.attributes;
	var attr_value_list = message.attr_value_list;
	for (var spec = 0; spec < attributes.length; spec++) {

		if(attr_value_list[attributes[spec]['id']]){
			speAttr.innerHTML += '<div class="dt">' + attributes[spec]['title'] + '：</div>';
			/*--attr_type 0唯一;1单选;2复选 --*/
			if (attributes[spec]['attr_type'] == 1) {
				var speDD = document.createElement("div");
				speDD.className = 'dd radio-dd';
				for (var val_arr = 0; val_arr < attr_value_list[attributes[spec]['id']].length; val_arr++) {
					if(val_arr == 0){
						speDD.innerHTML += "<span class='attr-radio curr'><label for='spec_value_" + attr_value_list[attributes[spec]['id']][val_arr]['goods_attr_id'] + "'><input type='radio' name='spec_" + attributes[spec]['id'] + "' value='" + attr_value_list[attributes[spec]['id']][val_arr]['goods_attr_id'] + "' id='spec_value_" + attr_value_list[attributes[spec]['id']][val_arr]['goods_attr_id'] + "' checked />&nbsp;<font>" + attr_value_list[attributes[spec]['id']][val_arr]['attr_value'] + '</font> [' + attr_value_list[attributes[spec]['id']][val_arr]['attr_price'] + ']</font></label></span>';
					}
					else{
						speDD.innerHTML += "<span class='attr-radio'><label for='spec_value_" + attr_value_list[attributes[spec]['id']][val_arr]['goods_attr_id'] + "'><input type='radio' name='spec_" + attributes[spec]['id'] + "' value='" + attr_value_list[attributes[spec]['id']][val_arr]['goods_attr_id'] + "' id='spec_value_" + attr_value_list[attributes[spec]['id']][val_arr]['goods_attr_id'] + "' />&nbsp;<font>" + attr_value_list[attributes[spec]['id']][val_arr]['attr_value'] + '</font> [' + attr_value_list[attributes[spec]['id']][val_arr]['attr_price'] + ']</font></label></span>';
					}
				}
				
			} else if (attributes[spec]['attr_type'] == 2) {
				var speDD = document.createElement("div");
				speDD.className = 'dd checkbox-dd';
				for (var val_arr = 0; val_arr < attr_value_list[attributes[spec]['id']].length; val_arr++) {
					speDD.innerHTML += "<span class='attr-radio'><label for='spec_value_" + attr_value_list[attributes[spec]['id']][val_arr]['goods_attr_id'] + "'><input type='checkbox' name='spec_" + attributes[spec]['id'] + "[]' value='" + attr_value_list[attributes[spec]['id']][val_arr]['goods_attr_id'] + "' id='spec_value_" + attr_value_list[attributes[spec]['id']][val_arr]['goods_attr_id'] + "' />&nbsp;<font>" + attr_value_list[attributes[spec]['id']][val_arr]['attr_value'] + ' [' + attr_value_list[attributes[spec]['id']][val_arr]['attr_price'] + ']</font></label></span>';
				}


			}
			
			if (attributes[spec]['attr_type']){
				speDD.innerHTML += "<input type='hidden' name='spec_attr_type[]' value='" + attributes[spec]['id'] + "' />";
			}
			
			speAttr.appendChild(speDD);
			speAttr.innerHTML += "<div class='blank10'></div>"
		}
		
	}
	speAttr.innerHTML += '<input type="hidden" name="goods_id" value="'+goods_id+'" >';
	speAttr.innerHTML += '<input type="hidden" name="number" value="1" >';
	newDiv.appendChild(speAttr);
	newDiv.innerHTML += "<div class='spe-btn'><a href='javascript:submit_div(" + goods_id + ")' class='sure-btn' >购买</a><a href='javascript:cancel_div()' class='cancel-btn'>取消</a></div>";

	var skuForm = document.createElement("form");
	skuForm.id = 'FORMBUY';
	skuForm.appendChild(newDiv);
	document.body.appendChild(skuForm);

	$('#speDiv').css('top', ($(window).height() - $('#speDiv').height()) / 2);
	// mask图层
	var newMask = document.createElement("div");
	newMask.id = m;
	newMask.style.position = "fixed";
	newMask.style.zIndex = "9999";
	newMask.style.width = document.body.scrollWidth + "px";
	newMask.style.height = document.body.scrollHeight + "px";
	newMask.style.top = "0px";
	newMask.style.left = "0px";
	newMask.style.background = "#000";
	newMask.style.filter = "alpha(opacity=15)";
	newMask.style.opacity = "0.15";
	document.body.appendChild(newMask);
	$('#speDiv .radio-dd').on('click', '.attr-radio', function() {
		if(event.target.type!="radio"){
			$(this).find(":radio").click(function(){
				event.stopPropagation();
			})
		}		
		$(this).addClass('curr').siblings('.attr-radio').removeClass('curr');
	});
	$('#speDiv .checkbox-dd').on('click', '.attr-radio', function() {
		if(event.target.type!="checkbox"){
			$(this).find(":checkbox").click(function(){
				event.stopPropagation();
			})
		}
		$(this).toggleClass('curr');
	});
}

//获取选择属性后，再次提交到购物车 parentId
function submit_div(goods_id) {
	var field = $('#FORMBUY').serializeArray();
	if(field.length == 0){
		field = {'goods_id':goods_id, 'number':1};
	}

	$.ajax({
		url: '/addcart.html',
		type: 'post',
		data: field,
		async: true,
		dataType: 'json',
		success(result) {
			addToCartResponse(result);
		},
		error(err) {
			console.log(err.msg);
		}
	});
	document.body.removeChild(docEle('FORMBUY'));
	document.body.removeChild(docEle('mask'));

	var i = 0;
	var sel_obj = document.getElementsByTagName('select');
	while (sel_obj[i]) {
		sel_obj[i].style.visibility = "";
		i++;
	}

}

// 关闭mask和新图层
function cancel_div() {
	document.body.removeChild(docEle('FORMBUY'));
	document.body.removeChild(docEle('mask'));

	var i = 0;
	var sel_obj = document.getElementsByTagName('select');
	while (sel_obj[i]) {
		sel_obj[i].style.visibility = "";
		i++;
	}
}
