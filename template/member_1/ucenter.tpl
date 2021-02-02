<div class="user-container">
	<div class="container clearfix">
		<div class="user-left">
			{include file="left" /}
		</div>
		<div class="user-right">
			<div class="bg-box-radius">
				<div class="user-tabtit">修改资料</div>
				<div class="edit-data">
					<div class="edit-user-avatars" id="form-data-cover">
						<img src="{if $user['avatar']}{$user['avatar']}{else}{$web_images}/usernopic.png{/if}" id="finalImg">
						<p>修改头像</p>
					</div>
					<div class="edit-user-name">{$user['name']}</div>
					<div class="edit-data-form">
						<form onsubmit="return editForm(this);">
							<ul>
								<li>
									<div class="name">手机号</div>
									<div class="input">{$user['phone']}</div>
								</li>
								<li>
									<div class="name">用户名</div>
									<div class="input">
										<input type="text" class="j-input" id="username" name="name" value="{$user['name']}">
									</div>
								</li>
								<li>
									<div class="name">电子邮箱</div>
									<div class="input">
										<input type="text" class="j-input" id="email" name="email" value="{$user['email']}">
									</div>
								</li>
								<li class="submit">
									<div class="name"></div>
									<div class="input">
										<button class="edit-btn edit-submit" type="submit">保存</button>
										<button class="edit-btn edit-reset" type="reset">重新填写</button>
									</div>
								</li>
							</ul>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

function editForm(object){
	var username = $.trim($('#username').val());
	if(username == ''){
		show_error('用户名不能为空!');
		return false;
	}
	
	var field = $(object).serializeArray();
	$.ajax({
		url:'{:url('ucenter.edit')}{$lang}',
		type:'POST',
		data: field,
		dataType:'JSON',
		success:function(res){		
			if(res.code == '0'){
				show_error(res.msg);
			}
			else{
				show_error(res.msg);
			}
		},
		error:function (data) {
		}
	});
	return false;
}
</script>
<!--头像裁剪-->
<div class="tailoring-container hide">
	<div class="black-cloth"></div>
	<div class="tailoring-content">
		<div class="tailoring-content-one">上传头像 
			<div class="close-tailoring"  onclick="closeTailor();"></div>
		</div>
		<div class="tailoring-content-two">
			<div class="tailoring-box-parcel">
				<img id="tailoringImg" src="{if $user['avatar']}{$user['avatar']}{else}{$web_images}/usernopic.png{/if}">
			</div>
			<div class="preview-box-parcel">
				<div class="circular previewImg"></div>
				<p>头像预览</p>
			</div>
		</div>
		<div class="tailoring-content-three">
			<button class="btn-group btn-rotater">旋转</button>
			<button class="btn-group btn-zoomout">放大</button>
			<button class="btn-group btn-zoomin">搜小</button>
			<div class="btn-group btn-reset">
				<label class="inputImage" for="chooseImg" title="">
					<input type="file" accept="image/jpg,image/jpeg,image/png" name="file" id="chooseImg" class="hidden" onChange="selectImg(this)">
					<span>重新上传</span>
				</label>
			</div>
		</div>
		<div class="docs-buttons">
			<button type="button" class="btn-sureCut" id="sureCut">确定</button>
			<button type="button" class="btn-default" id="sureDef">取消</button>
        </div>
    </div>
	</div>
</div>

<script type="text/javascript" src="/template/member/skin/js/cropper.min.js"></script>
<script type="text/javascript">
    //弹出框水平垂直居中
    (window.onresize = function () {
        var win_height = $(window).height();
        var win_width = $(window).width();
        if (win_width <= 768){
            $(".tailoring-content").css({
                "top": (win_height - $(".tailoring-content").outerHeight())/2,
                "left": 0
            });
        }else{
            $(".tailoring-content").css({
                "top": (win_height - $(".tailoring-content").outerHeight())/2,
                "left": (win_width - $(".tailoring-content").outerWidth())/2
            });
        }
    })();

    //弹出图片裁剪框
    $("#form-data-cover").on("click",function () {
        $(".tailoring-container").removeClass("hide");
    });
    //图像上传
    function selectImg(file) {
        if (!file.files || !file.files[0]){
            return;
        }
        var reader = new FileReader();
        reader.onload = function (evt) {
            var replaceSrc = evt.target.result;
            //更换cropper的图片
            $('#tailoringImg').cropper('replace', replaceSrc,false);//默认false，适应高度，不失真
        }
        reader.readAsDataURL(file.files[0]);
    }
    //cropper图片裁剪
    $('#tailoringImg').cropper({
        aspectRatio: 1/1,//默认比例
        preview: '.previewImg',//预览视图
        guides: false,  //裁剪框的虚线(九宫格)
        autoCropArea: 1,  //0-1之间的数值，定义自动剪裁区域的大小，默认0.8
        movable: false, //是否允许移动图片
        dragCrop: true,  //是否允许移除当前的剪裁框，并通过拖动来新建一个剪裁框区域
        movable: true,  //是否允许移动剪裁框
        resizable: true,  //是否允许改变裁剪框的大小
        zoomable: true,  //是否允许缩放图片大小
        mouseWheelZoom: false,  //是否允许通过鼠标滚轮来缩放图片
        touchDragZoom: true,  //是否允许通过触摸移动来缩放图片
        rotatable: true,  //是否允许旋转图片
        crop: function(e) {
            
        }
    });
    //旋转
    $(".btn-rotater").on("click",function () {
        $('#tailoringImg').cropper("rotate", 90);
    });
    //放大
    $(".btn-zoomout").on("click",function () {
        $('#tailoringImg').cropper("zoom", 0.1);
    });
    //搜小
    $(".btn-zoomin").on("click",function () {
        $('#tailoringImg').cropper("zoom", -0.1);
    });

    //裁剪后的处理
    $("#sureCut").on("click",function () {
        if ($("#tailoringImg").attr("src") == null ){
            return false;
        }else{
            var cas = $('#tailoringImg').cropper('getCroppedCanvas');//获取被裁剪后的canvas
            var base64url = cas.toDataURL('image/png'); //转换为base64地址形式
            
			
			$.ajax({
				url:'{:url('ucenter.upload')}{$lang}',
				type:'POST',
				data: {'avatar': base64url},
				dataType:'JSON',
				success:function(res){		
					if(res.code == '0'){
						show_error(res.msg);
						closeTailor();
						$("#finalImg,.user-avatar img").prop("src",res.avatar_path);	//显示为图片的形式
					}
					else{
						show_error(res.msg);
					}
				},
				error:function (data) {
				}
			});	
       
        }
    });
    $("#sureDef").on("click",function () {
    	closeTailor();
    });
    //关闭裁剪框
    function closeTailor() {
        $(".tailoring-container").addClass("hide");
    }
</script>