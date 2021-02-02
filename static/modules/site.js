/**
 @Name：site 站点设置
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
	  elem: '#LAY-site-back'
	  ,url: '/admin/site/getlist.html'
	  ,cols: [[
	    {type: 'checkbox', fixed: 'left'}
	    ,{field: 'id', width: 80, title: 'ID', sort: true}
			,{field: 'name', title: '站点名称'}
			,{field: 'mark', title: '站点标识'}
			,{field: 'domain', title: '绑定域名'}
			,{field: 'default_style', title: '默认风格'}
			,{field: 'dirname', title: '站点目录'}
			,{field: 'isdefault', title: '前端默认站', templet: '#isDefaultTpl', align: 'center'}
			,{field: 'status', title: '开启/关闭', templet: '#statusTpl', align: 'center'}
	    ,{title: '操作', width: 260, align: 'center', fixed: 'right', toolbar: '#table-site-admin'}
	  ]]
	  ,text:{ none:'暂无相关数据'}
	});
	
	//监听工具条
	table.on('tool(LAY-site-back)', function(obj){
	  var data = obj.data;
	  if(obj.event === 'del'){
			
			layer.confirm('确定删除此站点？', function(index){
				$.ajax({
						url:'/admin/site/delete.html',
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
	  
	  else if(obj.event === 'edit'){
			window.location.href = '/admin/site/edit/id/'+data['id']+'.html';
	  }
	  
	  else if(obj.event === 'copy'){
	  			layer.prompt({title: '输入网站标示，并确认', formType: 3}, function(mark, index){
					layer.close(index); 
					$.ajax({
						url:'/admin/site/copy/id/'+data['id']+'.html',
						method:'post',
						data:{'name':mark,'mark':mark},
						dataType:'JSON',
						success:function(res){
							if(res.code == '0'){
								table.reload('LAY-site-back'); //数据刷新
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
	  }
	});

  exports('site', {})
});