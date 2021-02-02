/**
 @Name：会员等级
 @Author：star1029
 @Site：http://www.layui.com/admin/
 @License：LPPL
    
 */

layui.define(['table', 'form'], function(exports){
  var $ = layui.$
  ,table = layui.table
  ,form = layui.form;

	//品牌管理
	table.render({
	  elem: '#LAY-rank-back'
	  ,url: '/admin/rank/getlist.html'
	  ,cols: [[
	    {type: 'checkbox', fixed: 'left'}
	    ,{field: 'id', width: 80, title: 'ID', sort: true}
		,{field: 'rank_name', title: '会员等级名称'}
		,{field: 'min_points', title: '积分下限'}
		,{field: 'max_points', title: '积分上限'}
		,{field: 'discount', title: '初始折扣率(%)'}
		,{field: 'show_price', title: '显示价格', templet: '#showpriceTpl', align: 'center',}
		,{field: 'special_rank', title: '特殊会员组', templet: '#specialrankTpl', align: 'center',}
	    ,{title: '操作', width: 160, align: 'center', fixed: 'right', toolbar: '#table-rank-admin'}
	  ]]
	  ,text:{ none:'暂无相关数据'}
	});
	
	//监听工具条
	table.on('tool(LAY-rank-back)', function(obj){
	  var data = obj.data;
	  if(obj.event === 'del'){
			
			layer.confirm('确定删除此会员等级？', function(index){
				$.ajax({
						url:'/admin/rank/delete.html',
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
			,title: '编辑会员等级'
			,content: '/admin/rank/edit/id/'+data['id']+'.html'
			,maxmin: true
			,area: ['600px', '630px']
			,btn: ['确定', '取消']
			,yes: function(index, layero){
				
				var iframeWindow = window['layui-layer-iframe'+ index]
				,submit = layero.find('iframe').contents().find("#LAY-rank-back-submit");
		
				//监听提交
				iframeWindow.layui.form.on('submit(LAY-rank-back-submit)', function(data){
				  //提交 Ajax 成功后，静态更新表格中的数据
				  $.ajax({
						url:'/admin/rank/edit.html',
						method:'post',
						data:data.field,
						dataType:'JSON',
						success:function(res){
							if(res.code == '0'){
								table.reload('LAY-rank-back'); //数据刷新
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

  exports('rank', {})
});