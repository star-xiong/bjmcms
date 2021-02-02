/**
 @Name：layuiAdmin 管理员管理 角色管理 菜单管理
 @Author：star1029
 @Site：http://www.layui.com/admin/
 @License：LPPL
    
 */

layui.define(['table', 'form'], function(exports){
  var $ = layui.$
  ,table = layui.table
  ,form = layui.form;

  //管理员管理
  table.render({
    elem: '#LAY-user-back-manage'
    ,url: '/admin/member/getlist.html'
    ,cols: [[
      {type: 'checkbox', fixed: 'left'}
      ,{field: 'id', width: 100, title: 'ID', sort: true}
      ,{field: 'name', title: '用户名', minWidth: 100}
      ,{field: 'email', title: '邮箱'}
	  ,{field: 'phone', title: '电话'}
      ,{field: 'created_at', title: '注册时间', sort: true}
      ,{title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-useradmin-admin'},
    ]]
	,toolbar: true
	,page: true
	,limit: 30
	,height: 'full-220'
    ,text:{ none:'暂无相关数据'}
  });
  
  //监听工具条
  table.on('tool(LAY-user-back-manage)', function(obj){
    var data = obj.data;
    if(obj.event === 'del'){
			layer.confirm('确定删除此管理员？', function(index){
				$.ajax({
						url:'/admin/member/delete.html',
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
			  //obj.del();
			  //layer.close(index);
			});      
    }else if(obj.event === 'edit'){
      var tr = $(obj.tr);

      layer.open({
        type: 2
        ,title: '编辑管理员'
        ,content: '/admin/user/edit/id/'+data['id']+'.html'
        ,area: ['60%', '90%']
        ,btn: ['确定', '取消']
        ,yes: function(index, layero){
          var iframeWindow = window['layui-layer-iframe'+ index]
          ,submitID = 'LAY-user-back-submit'
          ,submit = layero.find('iframe').contents().find('#'+ submitID);

          //监听提交
          iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
            //提交 Ajax 成功后，静态更新表格中的数据
            $.ajax({
            		url:'/admin/user/edit.html',
            		method:'post',
            		data:data.field,
            		dataType:'JSON',
            		success:function(res){
            				if(res.code == '0'){
            						table.reload('LAY-user-back-manage'); //数据刷新
            						layer.close(index); //关闭弹层
												layer.alert(res.msg);
            				}
            				else
            						layer.alert(res.msg);
            		},
            		error:function (data) {
            		}
            });
            //table.reload('LAY-user-front-submit'); //数据刷新
            //layer.close(index); //关闭弹层
          });  
          
          submit.trigger('click');
        }
        ,success: function(layero, index){           
          
        }
      })
    }
  });


  exports('member', {})
});