/**
 @Name：category 栏目管理 
 @Author：star1029
 @Site：http://www.layui.com/admin/
 @License：LPPL
    
 */

layui.define(['table', 'form'], function(exports){
  var $ = layui.$
  ,table = layui.table
  ,form = layui.form;

	//栏目管理
	table.render({
	  elem: '#LAY-category-back'
	  ,url: '/admin/category/getlist.html'
	  ,cols: [[
	    {type: 'checkbox', fixed: 'left'}
	    ,{field: 'id', width: 80, title: 'ID', sort: true}
		,{field: 'title', title: '栏目名称', width: 300, templet: '#titleTpl', align: 'left'}
		,{field: 'icon', title: '栏目图标', templet: '#iconTpl', align: 'center'}
		,{field: 'model_name', title: '所属模型'}
		,{field: 'status', title: '隐藏', templet: '#statusTpl', align: 'center'}
		,{field: 'ishot', title: '热门', templet: '#ishotTpl', align: 'center'}
		,{field: 'nav_pctop', title: 'PC顶部导航', templet: '#navpctopTpl', align: 'center'}
		,{field: 'nav_pcfooter', title: 'PC底部导航', templet: '#navpcfooterTpl', align: 'center'}
		,{field: 'nav_mtop', title: '手机顶部导航', templet: '#navmtopTpl', align: 'center'}
		,{field: 'nav_mfooter', title: '手机底部导航', templet: '#navmfooterTpl', align: 'center'}
		,{field: 'sort', title: '排序', align: 'center', sort: true}
	    ,{title: '操作', width: 260, align: 'center', fixed: 'right', toolbar: '#table-category-admin'}
	  ]]
	  ,text:{ none:'暂无相关数据'}
	});
	
	//监听工具条
	table.on('tool(LAY-category-back)', function(obj){
	  var data = obj.data;
	  if(obj.event === 'del'){
			
			layer.confirm('确定删除此栏目？', function(index){
				$.ajax({
						url:'/admin/category/delete.html',
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
	      ,title: '编辑栏目'
	      ,content: '/admin/category/edit/id/'+data['id']+'.html'
	      ,area: ['60%', '90%']
	      ,btn: ['确定', '取消']
	      ,yes: function(index, layero){
	        var iframeWindow = window['layui-layer-iframe'+ index]
	        ,submit = layero.find('iframe').contents().find("#LAY-category-back-submit");
	
	        //监听提交
	        iframeWindow.layui.form.on('submit(LAY-category-back-submit)', function(data){
	          //提交 Ajax 成功后，静态更新表格中的数据
	          $.ajax({
	          		url:'/admin/category/edit.html',
	          		method:'post',
	          		data:data.field,
	          		dataType:'JSON',
	          		success:function(res){
	          				if(res.code == '0'){
	          						table.reload('LAY-category-back'); //数据刷新
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
	  }else if(obj.event === 'create'){
	    var tr = $(obj.tr);
	    layer.open({
	      type: 2
	      ,title: '添加子栏目'
	      ,content: '/admin/category/create/id/'+data['id']+'.html'
	      ,area: ['60%', '90%']
	      ,btn: ['确定', '取消']
	      ,yes: function(index, layero){
	        var iframeWindow = window['layui-layer-iframe'+ index]
	        ,submit = layero.find('iframe').contents().find("#LAY-category-back-submit");
	
	        //监听提交
	        iframeWindow.layui.form.on('submit(LAY-category-back-submit)', function(data){
	          var field = data.field; //获取提交的字段
	          
	          //提交 Ajax 成功后，静态更新表格中的数据
	          $.ajax({
	          		url:'/admin/category/create.html',
	          		method:'post',
	          		data:data.field,
	          		dataType:'JSON',
	          		success:function(res){
	          				if(res.code == '0'){
	          						table.reload('LAY-category-back'); //数据刷新
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
	  }
	});

  exports('category', {})
});