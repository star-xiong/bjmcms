/**
 @Name：position 友情链接
 @Author：star1029
 @Site：http://www.layui.com/admin/
 @License：LPPL
    
 */

layui.define(['table', 'form'], function(exports){
  var $ = layui.$
  ,table = layui.table
  ,form = layui.form;

	//站点管理
	table.render({
	  elem: '#LAY-position-back'
	  ,url: '/admin/position/getlist.html'
	  ,cols: [[
	    {type: 'checkbox', fixed: 'left'}
	    ,{field: 'id', width: 80, title: 'ID', sort: true}
			,{field: 'title', title: '模板位置标题'}
			,{field: 'param_name', title: '模板调用变量名'}
			,{field: 'type', title: '模板调用数据类型', templet: '#typeTpl', align: 'center'}
			,{field: 'sort', title: '排序'}
			,{field: 'status', title: '状态', templet: '#statusTpl', align: 'center', width: 80}
	    ,{title: '操作', width: 160, align: 'center', fixed: 'right', toolbar: '#table-position-admin'}
	  ]]
	  ,text:{ none:'暂无相关数据'}
	});
	
	//监听工具条
	table.on('tool(LAY-position-back)', function(obj){
	  var data = obj.data;
	  if(obj.event === 'del'){
			
			layer.confirm('确定删除此模板位置？', function(index){
				$.ajax({
						url:'/admin/position/delete.html',
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
	      ,title: '编辑模板位置'
	      ,content: '/admin/position/edit/id/'+data['id']+'.html'
				,maxmin: true
	      ,area: ['600px', '630px']
	      ,btn: ['确定', '取消']
	      ,yes: function(index, layero){
	        var iframeWindow = window['layui-layer-iframe'+ index]
	        ,submit = layero.find('iframe').contents().find("#LAY-position-back-submit");
	
	        //监听提交
	        iframeWindow.layui.form.on('submit(LAY-position-back-submit)', function(data){
	          //提交 Ajax 成功后，静态更新表格中的数据
	          $.ajax({
	          		url:'/admin/position/edit.html',
	          		method:'post',
	          		data:data.field,
	          		dataType:'JSON',
	          		success:function(res){
	          				if(res.code == '0'){
	          						table.reload('LAY-position-back'); //数据刷新
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

  exports('position', {})
});