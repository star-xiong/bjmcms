/**
 @Name：model 模型管理 
 @Author：star1029
 @Site：http://www.layui.com/admin/
 @License：LPPL
    
 */

layui.define(['table', 'form'], function(exports){
  var $ = layui.$
  ,table = layui.table
  ,form = layui.form;

	//模型管理
	table.render({
	  elem: '#LAY-model-back'
	  ,url: '/admin/model/getlist.html'
	  ,cols: [[
	    {type: 'checkbox', fixed: 'left'}
	    ,{field: 'id', width: 80, title: 'ID', sort: true}
		,{field: 'title', title: '模型名称'}
		,{field: 'table_name', title: '表名'}
		,{field: 'name', title: '类型'}
		,{field: 'description', title: '描叙'}	
		,{field: 'issystem', title: '是否为系统', templet: '#isSystemTpl', align: 'center'}
	    ,{title: '操作', width: 260, align: 'center', fixed: 'right', toolbar: '#table-model-admin'}
	  ]]
	  ,text:{ none:'暂无相关数据'}
	});
	
	//监听工具条
	table.on('tool(LAY-model-back)', function(obj){
	  var data = obj.data;
	  if(obj.event === 'del'){
			
			layer.confirm('此操作不可逆，请再次确认是否要操作。', function(index){
				$.ajax({
						url:'/admin/model/delete.html',
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
	      ,title: '编辑模型'
	      ,content: '/admin/model/edit/id/'+data['id']+'.html'
	      ,area: ['60%', '70%']
	      ,btn: ['确定', '取消']
	      ,yes: function(index, layero){
	        var iframeWindow = window['layui-layer-iframe'+ index]
	        ,submit = layero.find('iframe').contents().find("#LAY-model-back-submit");
	
	        //监听提交
	        iframeWindow.layui.form.on('submit(LAY-model-back-submit)', function(data){
	          //提交 Ajax 成功后，静态更新表格中的数据
	          $.ajax({
	          		url:'/admin/model/edit.html',
	          		method:'post',
	          		data:data.field,
	          		dataType:'JSON',
	          		success:function(res){
						if(res.code == '0'){
							table.reload('LAY-model-back'); //数据刷新
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

  exports('model', {})
});