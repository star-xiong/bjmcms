layui.use(['form'], function(){
	var $ = layui.$,
		form = layui.form;

	
	//大文件分片上传
	$(".feild_name-list").click(function(){
		var _id = $(this).attr("data-id");
		$(".upload-formId").attr("data-id",_id);
	});
	var allMaxSize = 2048;
	var uploader = WebUploader.create({
		resize: false, // 不压缩image
		swf: '/static/webuploader/js/uploader.swf', // swf文件路径
		server: '/admin/upload/uploadFile.html', // 文件接收服务端。
		chunked: true, //是否要分片处理大文件上传
		chunkSize:2*1024*1024, //分片上传，每片2M，默认是5M
		pick:{
			id: '.feild_name-list',
			multiple: false // false 单选，true 多选
		}, // 选择文件的按钮。可选
		fileSizeLimit: allMaxSize*1024*1024, //限制大小2048M，所有被选文件，超出选择不上
		auto: false,	 //选择文件后是否自动上传
		chunkRetry : 2, //如果某个分片由于网络问题出错，允许自动重传次数
		//runtimeOrder: 'html5,flash',
		accept: {
		   title: 'Files',
		   extensions: 'gif,jpg,jpeg,bmp,png,psd,zip,rar,tar,gz',
		   mimeTypes: 'file/*'
		}
	});
	
	// 当有文件被添加进队列的时候
	uploader.on( 'fileQueued', function( file) {
		var _fId = $(".upload-formId").attr("data-id");
		var _html = '<div id="'+file.id+'" class="item">';
		_html += '<div class="thelist">';
		_html += '<div class="info">'+file.name+'</div>';
		_html += '<div class="progress progress-striped active">';
		_html += '<div class="progress-bar" role="progressbar"></div>';
		_html += '</div>';
		_html += '<div id="formId" data-id="'+_fId+'"></div>';
		_html += '<div class="dbtn"><button type="button" class="layui-btn ctlBtn">确认上传</button></div>';
		_html += '</div>';
		_html += '</div>';
		layer.open({
			type: 1,
			area: ['380px', '240px'],
			skin: 'layui-layer-demo',
			title: '上传文件',
			close: 0,
			content: _html,
			end: function(){
				uploader.removeFile(file);
			}
		});
	});
	
	//文件上传过程中创建进度条实时显示。
	uploader.on( 'uploadProgress', function( file, percentage ) {
		var $li = $( '#'+file.id );
		var	$percent = $li.find('.progress .progress-bar');
		$percent.css( 'width', percentage * 100 + '%' );
		var pwcent = $li.find(".layui-btn").attr('disabled',"true");
	});
	
	// 文件上传成功
	uploader.on( 'uploadSuccess', function( file, res ) {
		var formId = $("#formId").attr("data-id");
		if(res.status==1){
			layer.alert('上传成功',{
				end:function(){
					layer.closeAll('page');
					$( '#'+formId ).val(res.result.info.filePaht);
				}
			});
		}
	});
	
	//  验证大小
	uploader.on("error",function (file){
		if(file == "Q_EXCEED_SIZE_LIMIT"){
			layer.alert('单个文件大小不超过2G');
		}
	});
	
	// 文件上传失败，显示上传出错
	uploader.on( 'uploadError', function( file ) {
		layer.alert('上传出错');
	});
	
	// 完成上传完
	uploader.on( 'uploadComplete', function( file ) {
		
	});
	
	$("body").on('click', '.ctlBtn', function () {
		if ($(this).hasClass('disabled')) {
			return false;
		}
		uploader.upload();
		// if (state === 'ready') {
		//     uploader.upload();
		// } else if (state === 'paused') {
		//     uploader.upload();
		// } else if (state === 'uploading') {
		//     uploader.stop();
		// }
	});
	
})