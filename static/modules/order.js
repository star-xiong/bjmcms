/**
 @Name：订单
 @Author：star1029
 @Site：http://www.layui.com/admin/
 @License：LPPL
    
 */

layui.define(['table', 'form'], function(exports){
  var $ = layui.$
  ,table = layui.table
  ,form = layui.form;

	//品牌管理
	table.render({
	  elem: '#LAY-order-back'
	  ,url: '/admin/order/getlist.html'
	  ,cols: [[
	    {type: 'checkbox', fixed: 'left'}
	    ,{field: 'id', width: 80, title: 'ID', sort: true}
		,{field: 'order_sn', title: '订单号'}
		,{field: 'add_time', title: '下单时间'}
		,{field: 'consignee', title: '收货人', templet: '#consigneeTpl'}
		,{field: 'order_amount', title: '总金额'}
		,{field: 'order_amount', title: '应付金额'}
		,{field: 'order_status_name', title: '订单状态'}
		,{field: 'pay_status_name', title: '支付状态'}
		,{field: 'shipping_status_name', title: '配送状态'}
	    ,{title: '操作', width: 160, align: 'center', fixed: 'right', toolbar: '#table-order-admin'}
	  ]]
	  ,text:{ none:'暂无相关数据'}
	});
	
	//监听工具条
	table.on('tool(LAY-order-back)', function(obj){
	  var data = obj.data;
	  if(obj.event === 'del'){
			
			layer.confirm('确定删除此订单？', function(index){
				$.ajax({
						url:'/admin/order/delete.html',
						method:'post',
						data:{'id':data['id']},
						dataType:'JSON',
						success:function(res){
							if(res.code == '0'){
								obj.del();
								layer.close(index);
								layer.alert(res.msg);
							}
							else
								layer.alert(res.msg);
						},
						error:function (data) {
						}
				});
	
			}); 
			
	  }
	  else if(obj.event === 'detail'){
	    window.location.href = '/admin/order/detail/id/'+data['id']+'.html';
	  }
	});

  exports('order', {})
});