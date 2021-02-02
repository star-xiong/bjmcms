/**
 @Name：price 价格管理
 @Author：star1029
 @Type：http://www.layui.com/admin/
 @License：LPPL
    
 */

layui.define(['table', 'form'], function(exports){
  var $ = layui.$
  ,table = layui.table
  ,form = layui.form;

	//价格管理列表
	table.render({
	  elem: '#LAY-price-back'
	  ,url: '/admin/price/getlist.html'
	  ,cols: [[
	    {type: 'checkbox', fixed: 'left'}
	    ,{field: 'id', width: 80, title: 'ID', sort: true}
			,{field: 'title', width: 360, title: '标题', templet: '#titleTpl', align: 'left'}
			,{field: 'cname', title: '所属栏目', sort: true}
			,{field: 'istop', title: '推荐', sort: true, templet: '#isTopTpl'}		
			,{field: 'clicks', title: '浏览量', sort: true}
			,{field: 'lastprice', title: '最新价格', sort: true}
			,{field: 'price_date', width: 160, title: '更新时间', sort: true}
			,{field: 'sort', title: '排序', sort: true}
	    ,{title: '操作', width: 160, align: 'center', fixed: 'right', toolbar: '#table-price-admin'}
	  ]]
	  ,page: true
	  ,limit: 30
	  ,height: 'full-220'
	  ,text:{ none:'暂无相关数据'}
	});

	
	//监听工具条
	table.on('tool(LAY-price-back)', function(obj){
	  var data = obj.data;
	  /* if(obj.event === 'del'){
			
			layer.confirm('确定删除此类型？', function(index){
				$.ajax({
						url:'/admin/type/delete.html',
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
			
	  }else if(obj.event === 'edit'){
	    var tr = $(obj.tr);
	
	    layer.open({
	      type: 2
	      ,title: '编辑类型'
	      ,content: '/admin/type/edit/id/'+data['id']+'.html'
				,maxmin: true
	      ,area: ['60%', '90%']
	      ,btn: ['确定', '取消']
	      ,yes: function(index, layero){
	        var iframeWindow = window['layui-layer-iframe'+ index]
	        ,submit = layero.find('iframe').contents().find("#LAY-price-back-submit");
	
	        //监听提交
	        iframeWindow.layui.form.on('submit(LAY-price-back-submit)', function(data){
	          //提交 Ajax 成功后，静态更新表格中的数据
	          $.ajax({
	          		url:'/admin/type/edit.html',
	          		method:'post',
	          		data:data.field,
	          		dataType:'JSON',
	          		success:function(res){
	          				if(res.code == '0'){
	          						table.reload('LAY-price-back'); //数据刷新
	          						layer.close(index); //关闭弹层
												layer.alert(res.msg);
	          				}
	          				else
	          						layer.alert(res.msg);
	          		},
	          		error:function (data) {
	          		}
	          });
	        });  
	        
	        submit.trigger('click');
	      }
	      ,success: function(layero, index){
	      
	      }
	    })
	  } */
	});

  exports('price', {})
});