/**
 @Name：type 类型设置
 @Author：star1029
 @Type：http://www.layui.com/admin/
 @License：LPPL
    
 */

layui.define(['table', 'form'], function(exports){
  var $ = layui.$
  ,table = layui.table
  ,form = layui.form;

	//类型管理
	table.render({
	  elem: '#LAY-type-back'
	  ,url: '/admin/type/getlist.html'
	  ,cols: [[
	    {type: 'checkbox', fixed: 'left'}
	    ,{field: 'id', width: 80, title: 'ID', sort: true}
		,{field: 'title', title: '类型名称'}
		,{field: 'group', title: '群组'}
		,{field: 'pic', title: '图标', templet: '#picTpl', align: 'center', width: 150}
		,{field: 'istop', title: '推荐'}
		,{field: 'sort', title: '排序'}
	    ,{title: '操作', width: 260, align: 'center', fixed: 'right', toolbar: '#table-type-admin'}
	  ]]
	  ,text:{ none:'暂无相关数据'}
	});
	
	//监听工具条
	table.on('tool(LAY-type-back)', function(obj){
	  var data = obj.data;
	  if(obj.event === 'del'){
			
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
	        ,submit = layero.find('iframe').contents().find("#LAY-type-back-submit");
	
	        //监听提交
	        iframeWindow.layui.form.on('submit(LAY-type-back-submit)', function(data){
	          //提交 Ajax 成功后，静态更新表格中的数据
	          $.ajax({
	          		url:'/admin/type/edit.html',
	          		method:'post',
	          		data:data.field,
	          		dataType:'JSON',
	          		success:function(res){
	          				if(res.code == '0'){
	          						table.reload('LAY-type-back'); //数据刷新
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

  exports('type', {})
});