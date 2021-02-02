/**
 @Name：field 字段管理 
 @Author：star xiong
 @License：LPPL
 */

layui.define(['table', 'form'], function(exports){
  var $ = layui.$
  ,table = layui.table
  ,form = layui.form
  ,mid = $('#mid').val();

	//字段管理
	table.render({
	  elem: '#LAY-field-back'
	  ,url: '/admin/field/getlist/id/'+mid+'.html'
	  ,cols: [[
	    {type: 'checkbox', fixed: 'left'}
	    ,{field: 'id', width: 80, title: 'ID', sort: true}
			,{field: 'title', title: '字段名称'}
			,{field: 'field', title: '字段名'}
			,{field: 'class', title: '类型'}		
			,{field: 'remark', title: '提示'}
			,{field: 'sort', title: '排序'}
	    ,{title: '操作', width: 260, align: 'center', fixed: 'right', toolbar: '#table-field-admin'}
	  ]]
	  ,text:{ none:'暂无相关数据'}
	});
	
	//监听工具条
	table.on('tool(LAY-field-back)', function(obj){
	  var data = obj.data;
	  if(obj.event === 'del'){
			
			layer.confirm('此操作不可逆，请再次确认是否要操作。', function(index){
				$.ajax({
						url:'/admin/field/delete.html',
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
	      ,title: '编辑字段'
	      ,content: '/admin/field/edit/id/'+data['id']+'.html'
	      ,area: ['60%', '90%']
	      ,btn: ['确定', '取消']
	      ,yes: function(index, layero){
	        var iframeWindow = window['layui-layer-iframe'+ index]
	        ,submit = layero.find('iframe').contents().find("#LAY-field-back-submit");
	
	        //监听提交
	        iframeWindow.layui.form.on('submit(LAY-field-back-submit)', function(data){
	          //提交 Ajax 成功后，静态更新表格中的数据
	          $.ajax({
	          		url:'/admin/field/edit.html',
	          		method:'post',
	          		data:data.field,
	          		dataType:'JSON',
	          		success:function(res){
						if(res.code == '0'){
							table.reload('LAY-field-back'); //数据刷新
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

  exports('field', {})
});