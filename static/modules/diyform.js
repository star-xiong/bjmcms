/**
 @Name：content 内容管理 
 @Author：star1029
 @Site：http://www.layui.com/admin/
 @License：LPPL
    
 */

layui.define(['table', 'form'], function(exports){
  var $ = layui.$
  ,table = layui.table
  ,form = layui.form
  ,cid = $('#cid').val();

	//文章管理

	table.render({
	  elem: '#LAY-content-back'
	  ,url: '/admin/content/getDiyList/cid/'+cid+'.html'
	  ,cols: [[
	    {type: 'checkbox', fixed: 'left'}
	    ,{field: 'id', width: 80, title: 'ID', sort: true}
			,{field: 'name', title: '姓名'}
			,{field: 'phone', title: '联系电话'}
			,{field: 'content', title: '内容'}
	    ,{title: '操作', width: 160, align: 'center', fixed: 'right', toolbar: '#table-content-admin'}
	  ]]
	  ,page: true
	  ,limit: 30
	  ,height: 'full-220'
	  ,text:{ none:'暂无相关数据'}
	});
	
	//监听工具条
	table.on('tool(LAY-content-back)', function(obj){
	  var data = obj.data;
	  if(obj.event === 'del'){
			
			layer.confirm('此操作不可逆，请再次确认是否要操作。', function(index){
				$.ajax({
						url:'/admin/content/delDiyForm.html',
						method:'post',
						data:{'id':data['id'], 'cid':cid},
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
	});

  exports('diyform', {})
});