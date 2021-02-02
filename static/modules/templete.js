/**
 @Name：templete 首页模板
 @Author：star1029
 @Site：http://www.layui.com/admin/
 @License：LPPL
    
 */

layui.define(['table', 'form'], function(exports){
  var $ = layui.$
  ,table = layui.table
  ,form = layui.form;

	//模板管理
	table.render({
	  elem: '#LAY-templete-back'
	  ,url: '/admin/templete/getlist.html'
	  ,cols: [[
	    {type: 'checkbox', fixed: 'left'}
	    ,{field: 'id', width: 80, title: 'ID', sort: true}
		,{field: 'title', title: '模板标题'}
		,{field: 'position_title', title: '模板位置'}
		,{field: 'param_name', title: '模板变量名'}
		,{field: 'url', title: '链接地址'}
		,{field: 'pic', title: '模板', templet: '#picTpl', align: 'center', width: 150}
		,{field: 'target', title: '打开类型', templet: '#targetTpl', align: 'center', width: 90}
		,{field: 'limit', title: '数据条数', width: 90}
		,{field: 'sort', title: '排序', width: 90}
		,{field: 'update_at', title: '更新时间', width: 180}
		,{field: 'status', title: '状态', templet: '#statusTpl', align: 'center', width: 80}
	    ,{title: '操作', width: 160, align: 'center', fixed: 'right', toolbar: '#table-templete-admin'}
	  ]]
	  ,text:{ none:'暂无相关数据'}
	});
	
	//监听工具条
	table.on('tool(LAY-templete-back)', function(obj){
	  var data = obj.data;
	  if(obj.event === 'del'){
			
			layer.confirm('确定删除此模板？', function(index){
				$.ajax({
						url:'/admin/templete/delete.html',
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
	      ,title: '编辑模板'
	      ,content: '/admin/templete/edit/id/'+data['id']+'.html'
				,maxmin: true
	      ,area: ['60%', '90%']
	      ,btn: ['确定', '取消']
	      ,yes: function(index, layero){
	        var iframeWindow = window['layui-layer-iframe'+ index]
	        ,submit = layero.find('iframe').contents().find("#LAY-templete-back-submit");
	
	        //监听提交
	        iframeWindow.layui.form.on('submit(LAY-templete-back-submit)', function(data){
	          //提交 Ajax 成功后，静态更新表格中的数据
	          $.ajax({
	          		url:'/admin/templete/edit.html',
	          		method:'post',
	          		data:data.field,
	          		dataType:'JSON',
	          		success:function(res){
	          				if(res.code == '0'){
	          						table.reload('LAY-templete-back'); //数据刷新
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

  exports('templete', {})
});