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
    ,url: '/admin/user/getlist.html'
    ,cols: [[
      {type: 'checkbox', fixed: 'left'}
      ,{field: 'id', width: 100, title: 'ID', sort: true}
      ,{field: 'name', title: '用户名', minWidth: 100}
      ,{field: 'email', title: '邮箱'}
      ,{field: 'login_ip', title: 'IP'}
      ,{field: 'login_at', title: '最后登入时间', sort: true}
     // ,{field: 'check', title:'审核状态', templet: '#buttonTpl', minWidth: 80, align: 'center'}
      ,{title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-useradmin-admin'},
    ]]
    ,text:{ none:'暂无相关数据'}
  });
  
  //监听工具条
  table.on('tool(LAY-user-back-manage)', function(obj){
    var data = obj.data;
    if(obj.event === 'del'){
			layer.confirm('确定删除此管理员？', function(index){
				$.ajax({
						url:'/admin/user/delete.html',
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

	//角色管理
  table.render({
    elem: '#LAY-user-back-role'
    ,url: '/admin/role/getlist.html'
    ,cols: [[
      {type: 'checkbox', fixed: 'left'}
      ,{field: 'id', width: 80, title: 'ID', sort: true}
      ,{field: 'name', title: '角色名'}
      ,{field: 'created_at', width: 160, title: '创建时间', sort: true}
			,{field: 'updated_at', width: 160, title: '更新时间', sort: true}
      ,{title: '操作', width: 360, align: 'center', fixed: 'right', toolbar: '#table-useradmin-admin'}
    ]]
    ,text:{ none:'暂无相关数据'}
  });
  
  //监听工具条
  table.on('tool(LAY-user-back-role)', function(obj){
    var data = obj.data;
    if(obj.event === 'del'){
			
			layer.confirm('确定删除此角色？', function(index){
				$.ajax({
						url:'/admin/role/delete.html',
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
        ,title: '编辑角色'
        ,content: '/admin/role/edit/id/'+data['id']+'.html'
        ,area: ['500px', '180px']
        ,btn: ['确定', '取消']
        ,yes: function(index, layero){
          var iframeWindow = window['layui-layer-iframe'+ index]
          ,submit = layero.find('iframe').contents().find("#LAY-user-role-submit");

          //监听提交
          iframeWindow.layui.form.on('submit(LAY-user-role-submit)', function(data){
            var field = data.field; //获取提交的字段
            
            //提交 Ajax 成功后，静态更新表格中的数据
            $.ajax({
            		url:'/admin/role/edit.html',
            		method:'post',
            		data:data.field,
            		dataType:'JSON',
            		success:function(res){
            				if(res.code == '0'){
            						table.reload('LAY-user-back-role'); //数据刷新
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
    } else if(obj.event === 'permission'){
      var tr = $(obj.tr);

      layer.open({
        type: 2
        ,title: '角色管理 / 权限分配'
        ,content: '/admin/role/givepermissions/id/'+data['id']+'.html'
				,maxmin: true
        ,area: ['500px', '680px']
				,btn: ['确定', '取消']
        ,yes: function(index, layero){					
					var iframeWindow = window['layui-layer-iframe'+ index]
					,submit = layero.find('iframe').contents().find("#LAY-user-give-permissions-submit");
					
					//监听提交
					iframeWindow.layui.form.on('submit(LAY-user-give-permissions-submit)', function(data){
						$.ajax({
							url:'/admin/role/givePermissions.html',
							method:'post',
							data:data.field,
							dataType:'JSON',
							success:function(res){
								if(res.code == '0'){
									layer.alert(res.msg);
									layer.close(index); //关闭弹层
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
    } else if(obj.event === 'category'){
      var tr = $(obj.tr);

      layer.open({
        type: 2
        ,title: '角色管理 / 栏目权限分配'
        ,content: '/admin/role/givecategories/id/'+data['id']+'.html'
				,maxmin: true
        ,area: ['500px', '680px']
				,btn: ['确定', '取消']
        ,yes: function(index, layero){
					var iframeWindow = window['layui-layer-iframe'+ index]
					,submit = layero.find('iframe').contents().find("#LAY-user-give-categories-submit");
					
					//监听提交
					iframeWindow.layui.form.on('submit(LAY-user-give-categories-submit)', function(data){
						$.ajax({
							url:'/admin/role/givecategories.html',
							method:'post',
							data:data.field,
							dataType:'JSON',
							success:function(res){
								if(res.code == '0'){
									layer.alert(res.msg);
									layer.close(index); //关闭弹层
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
	
	//资源管理
	table.render({
	  elem: '#LAY-user-back-permission'
	  ,url: '/admin/permission/getlist.html'
	  ,cols: [[
	    {type: 'checkbox', fixed: 'left'}
	    ,{field: 'id', width: 80, title: 'ID', sort: true}		
	    ,{field: 'name', title: '资源名称', templet: '#nameTpl', align: 'left'}
		,{field: 'icon', title: '资源图标', templet: '#iconTpl', align: 'center'}
		,{field: 'module', title: '模块名称'}
		,{field: 'route', title: '路由', templet: '#routeTpl', align: 'center'}
		,{field: 'is_show', title: '展示', templet: '#buttonTpl', align: 'center'}
	    ,{field: 'sort', title: '排序'}
	    ,{title: '操作', width: 260, align: 'center', fixed: 'right', toolbar: '#table-useradmin-admin'}
	  ]]
	  ,text:{ none:'暂无相关数据'}
	});
	
	//监听工具条
	table.on('tool(LAY-user-back-permission)', function(obj){
	  var data = obj.data;
	  if(obj.event === 'del'){
			
			layer.confirm('确定删除此资源？', function(index){
				$.ajax({
						url:'/admin/permission/delete.html',
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
	      ,title: '编辑资源'
	      ,content: '/admin/permission/edit/id/'+data['id']+'.html'
	      ,area: ['500px', '500px']
	      ,btn: ['确定', '取消']
	      ,yes: function(index, layero){
	        var iframeWindow = window['layui-layer-iframe'+ index]
	        ,submit = layero.find('iframe').contents().find("#LAY-user-permission-submit");
	
	        //监听提交
	        iframeWindow.layui.form.on('submit(LAY-user-permission-submit)', function(data){
	          //提交 Ajax 成功后，静态更新表格中的数据
	          $.ajax({
	          		url:'/admin/permission/edit.html',
	          		method:'post',
	          		data:data.field,
	          		dataType:'JSON',
	          		success:function(res){
	          				if(res.code == '0'){
	          						table.reload('LAY-user-back-permission'); //数据刷新
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
	  }else if(obj.event === 'create'){
	    var tr = $(obj.tr);
	    layer.open({
	      type: 2
	      ,title: '添加资源'
	      ,content: '/admin/permission/create/id/'+data['id']+'.html'
	      ,area: ['500px', '500px']
	      ,btn: ['确定', '取消']
	      ,yes: function(index, layero){
	        var iframeWindow = window['layui-layer-iframe'+ index]
	        ,submit = layero.find('iframe').contents().find("#LAY-user-permission-submit");
	
	        //监听提交
	        iframeWindow.layui.form.on('submit(LAY-user-permission-submit)', function(data){
	          var field = data.field; //获取提交的字段
	          
	          //提交 Ajax 成功后，静态更新表格中的数据
	          $.ajax({
	          		url:'/admin/permission/create.html',
	          		method:'post',
	          		data:data.field,
	          		dataType:'JSON',
	          		success:function(res){
	          				if(res.code == '0'){
	          						table.reload('LAY-user-back-permission'); //数据刷新
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
	
	  //日志管理
	table.render({
	  elem: '#LAY-user-back-log'
	  ,url: '/admin/log/getlist.html'
	  ,cols: [[
	    {type: 'checkbox', fixed: 'left'}
	    ,{field: 'id', width: 100, title: 'ID', sort: true}
	    ,{field: 'user_name', title: '用户名'}
	    ,{field: 'option', title: '操作'}
			,{field: 'module', title: '模块'}
			,{field: 'controller', title: '控制器'}
			,{field: 'action', title: '方法'}
			,{field: 'method', title: '请求类型'}
	    ,{field: 'created_at', title: '时间', sort: true}
	    ,{title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-useradmin-admin'},
	  ]]
		,page: true
		,limit: 30
		,height: 'full-220'
	  ,text:{ none:'暂无相关数据'}
	});
  exports('useradmin', {})
});