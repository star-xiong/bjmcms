/********迷你购物车加减********/
	function flowClickCartNum(a,b)
	{		
		var b = parseInt(b);
		var c = $("#goods_number_"+a);
		var d = parseInt(c.val());
		if(d < 0 || !$.isNumeric(d))
		{
			alert("请输入正确的商品数量");
			e = 0;
		}
		
		if(b == 0)		
		{
			e = d;
		}
		else
		{
			e = b;
		}
		
		$.ajax({
			url: '/cart/update.html',
			type : 'post',
			data:{'id':a,'num':e},
			async: true,
			dataType: 'json',
			success(result) {

				if(result.code == 1){
					//除册该商品
					$('#li_'+result.data.id).remove();
				}
				if(result.code == 2){
					//更新该商品数量
					$('#goods_number_'+a).val(result.data.goods_number);
					$('#total_items_'+a).html('￥'+result.data.money.toFixed(2));
				}
				if(result.code){
					$('#selectedCount').html(result.data.total);
					$('#totalSkuPrice').html('￥'+result.data.amount.toFixed(2));
				}
				layer.msg(result.msg, {icon: 1,time: 1000});
				cartInit();
			},
			error(err) {
				console.log(err.msg);
			}
		});
	}
		
	// function flow_change_goods_number(rec_id, goods_number)
	// {     
	// 	var sel_goods = new Array();
	// 	var obj_cartgoods = document.getElementsByName("sel_cartgoods[]");
	// 	var j=0;
	//       for (i=0;i<obj_cartgoods.length;i++)
	//       {
	// 		if(obj_cartgoods[i].checked == true)
	// 		{	
	// 			sel_goods[j] = obj_cartgoods[i].value;
	// 			j++;
	// 		}
	//       }
	// 	Ajax.call('flow.php?step=ajax_update_cart', 'sel_goods='+ sel_goods +'&rec_id=' + rec_id +'&goods_number=' + goods_number, flow_change_goods_number_response, 'POST','JSON');
	// }
	// function flow_change_goods_number_response(result)
	// {              
	
	// 	if (result.error == 0)
	// 	{
	// 		var rec_id = result.rec_id;
			
	// 		$('#goods_number_' +rec_id).val(result.goods_number);//更新数量	
	// 		$('#total_items_' +rec_id).html(result.goods_subtotal);//更新小计	
	// 		$('#totalSkuPrice').html(result.total_price); //更新合计
	// 		$('#selectedCount').html(result.total_goods_count);//更新购物车数量
	// 		$('#selectedCount').html(result.total_number) //更新总数量

	// 	}

	// 	else if (result.error == 999 )
	// 	{
	// 	   if (confirm(result.message))
	// 		{
	// 			location.href = 'user.php';
	// 		}
	// 	}
	// 	else if (result.error == 888 )
	// 	{
	// 	   alert(result.message);
	// 	}

	// 	else if (result.message != '')
	// 	{
	// 		alert(result.message);                
	// 	}
	// }

	/* *
 * 检查收货地址信息表单中填写的内容
 */
// function checkConsignee(frm)
// {
//   var msg = new Array();
//   var err = false;

//   if (frm.elements['country'] && frm.elements['country'].value == 0)
//   {
//     msg.push(country_not_null);
//     err = true;
//   }

//   if (frm.elements['province'] && frm.elements['province'].value == 0 && frm.elements['province'].length > 1)
//   {
//     err = true;
//     msg.push(province_not_null);
//   }

//   if (frm.elements['city'] && frm.elements['city'].value == 0 && frm.elements['city'].length > 1)
//   {
//     err = true;
//     msg.push(city_not_null);
//   }

//   if (frm.elements['district'] && frm.elements['district'].length > 1)
//   {
//     if (frm.elements['district'].value == 0)
//     {
//       err = true;
//       msg.push(district_not_null);
//     }
//   }

//   if (Utils.isEmpty(frm.elements['consignee'].value))
//   {
//     err = true;
//     msg.push(consignee_not_null);
//   }

//   if ( ! Utils.isEmail(frm.elements['email'].value))
//   {
//     err = true;
//     msg.push(invalid_email);
//   }

//   if (frm.elements['address'] && Utils.isEmpty(frm.elements['address'].value))
//   {
//     err = true;
//     msg.push(address_not_null);
//   }

//   if (frm.elements['zipcode'] && frm.elements['zipcode'].value.length > 0 && (!Utils.isNumber(frm.elements['zipcode'].value)))
//   {
//     err = true;
//     msg.push(zip_not_num);
//   }

//   if (Utils.isEmpty(frm.elements['mobile'].value))
//   {
//     err = true;
//     msg.push(mobile_not_null);
//   }
//   else
//   {
//     if (!Utils.isMobile(frm.elements['mobile'].value))
//     {
//       err = true;
//       msg.push(mobile_invaild);
//     }
//   }

//   if (err)
//   {
//     message = msg.join("\n");
//     alert(message);
//   }
//   return ! err;
// }
