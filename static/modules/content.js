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
	  ,url: '/admin/content/getlist/cid/'+cid+'.html'
	  ,cols: [[
	    {type: 'checkbox', fixed: 'left'}
	    ,{field: 'id', width: 80, title: 'ID', sort: true}
			,{field: 'title', width: 360, title: '标题', templet: '#titleTpl', align: 'left'}
			,{field: 'cname', title: '所属栏目', sort: true}
			,{field: 'istop', title: '推荐', sort: true, templet: '#isTopTpl'}		
			,{field: 'clicks', title: '浏览量', sort: true}
			,{field: 'update_at', width: 160, title: '更新时间', sort: true}
			,{field: 'sort', title: '排序', sort: true}
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
						url:'/admin/content/delete.html',
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
	});

  exports('content', {})
});