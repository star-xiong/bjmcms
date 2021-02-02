/**
 @Name：attribute 首页属性
 @Author：star1029
 @Site：http://www.layui.com/admin/
 @License：LPPL
    
 */

layui.define(['table', 'form'], function(exports){
  var $ = layui.$
  ,table = layui.table
  ,form = layui.form;

	//属性管理
	table.render({
	  elem: '#LAY-attribute-back'
	  ,url: '/admin/attribute/getlist.html'
	  ,cols: [[
	    {type: 'checkbox', fixed: 'left'}
	    ,{field: 'id', width: 80, title: 'ID', sort: true}
			,{field: 'type_title', title: '所属类型', sort: true}
			,{field: 'title', title: '属性名称'}
			,{field: 'values', title: '选项值'}
			,{field: 'class', title: '字段类型'}
			,{field: 'attr_index', title: '检索', templet: '#indexTpl', align: 'center'}
			,{field: 'is_linked', title: '关联', templet: '#linkedTpl', align: 'center'}
			,{field: 'attr_type', title: '属性', templet: '#typeTpl', align: 'center'}
			,{field: 'in_image', title: '属性相册', templet: '#imageTpl', align: 'center'}
			,{field: 'sort', title: '排序', width: 90}
	    ,{title: '操作', width: 160, align: 'center', fixed: 'right', toolbar: '#table-attribute-admin'}
	  ]]
	  ,text:{ none:'暂无相关数据'}
	});
	
	//监听工具条
	table.on('tool(LAY-attribute-back)', function(obj){
	  var data = obj.data;
	  if(obj.event === 'del'){
			
			layer.confirm('确定删除此属性？', function(index){
				$.ajax({
						url:'/admin/attribute/delete.html',
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
	      ,title: '编辑属性'
	      ,content: '/admin/attribute/edit/id/'+data['id']+'.html'
				,maxmin: true
	      ,area: ['60%', '90%']
	      ,btn: ['确定', '取消']
	      ,yes: function(index, layero){
	        var iframeWindow = window['layui-layer-iframe'+ index]
	        ,submit = layero.find('iframe').contents().find("#LAY-attribute-back-submit");
	
	        //监听提交
	        iframeWindow.layui.form.on('submit(LAY-attribute-back-submit)', function(data){
	          //提交 Ajax 成功后，静态更新表格中的数据
	          $.ajax({
	          		url:'/admin/attribute/edit.html',
	          		method:'post',
	          		data:data.field,
	          		dataType:'JSON',
	          		success:function(res){
	          				if(res.code == '0'){
	          						table.reload('LAY-attribute-back'); //数据刷新
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

  exports('attribute', {})
});