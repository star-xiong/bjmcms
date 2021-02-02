<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\traits;

use think\Config;
use think\Db;

trait FieldTrait
{
	
	private function getInput($formtype, $default_value = '', $values = ''){
	    $html = '';
	    $typename = '';
	    switch ($formtype) {
	        case 'text':
	            $typename = '默认值';
	            $html = '<input class="layui-input" size="60" name="default_value" type="text" value="'.$default_value.'">';
	            break;
	        case 'textarea':
	            $typename = '默认值';
	            $html = '<textarea name="default_value" class="layui-textarea">'.$default_value.'</textarea>';
	            break;
	        case 'editor':
	            $typename = '默认值';
	            $html = '<textarea name="default_value" class="layui-textarea">'.$default_value.'</textarea>';
	            break;
	        case 'radio':
	            $typename = '单选列表';
	            $html = '<textarea name="values" class="layui-textarea">'.$values.'</textarea><font color="red">每行一个值</font>';
	            break;
	        case 'checkbox':
	            $typename = '复选列表';
	            $html = '<textarea name="values" class="layui-textarea">'.$values.'</textarea><font color="red">每行一个值</font>';
	            break;
	        case 'select':
	            $typename = '下拉列表';
	            $html = '<textarea name="values" class="layui-textarea">'.$values.'</textarea><font color="red">每行一个值</font>';
	            break;
	        case 'number':
	            $typename = '默认值';
	            $default_value = $default_value ? $default_value : 0;
	            $html = '<input class="layui-input" size="30" lay-verify="number" name="default_value" value="'.$default_value.'" type="text">';
	            break;
	    }
	    return ['typename' => $typename, 'html' => $html];
	}
	
	private function addField($param, $class)
	{
	    $info = model('ModelModel')->get($param['mid']);
	    $tablename = config('database.prefix')."form_".$info['table_name'];
	    $fieldclass = $class[$param['class']];
	    $defaultvalue = array_key_exists("default_value", $param) ? $param['default_value'] : '';
	    $length = $param['maxlength'] ? $param['maxlength'] : 0;
	    $field = $param['field'];	
		$res = '';
	    switch ($fieldclass) {
	        case 'varchar':
	            $sql = "ALTER TABLE `{$tablename}` ADD `{$field}` VARCHAR({$length}) DEFAULT '{$defaultvalue}'";
	            $res = Db::execute($sql);
	            break;
	        case 'int':
	            $defaultvalue = intval($defaultvalue);
	            $sql = "ALTER TABLE `{$tablename}` ADD `{$field}` INT(11) UNSIGNED DEFAULT '{$defaultvalue}'";
	            $res = Db::execute($sql);
	            break;
	        case 'smallint':
	            $defaultvalue = intval($defaultvalue);
	            $sql = "ALTER TABLE `{$tablename}` ADD `{$field}` SMALLINT(5) UNSIGNED DEFAULT '{$defaultvalue}'";
	            $res = Db::execute($sql);
	            break;
	        case 'text':
	            $res = Db::execute("ALTER TABLE `{$tablename}` ADD `{$field}` TEXT");
	            break;
	    }
		
		return $res;
	}
	
	private function editField($param, $oldfield, $class)
	{
	    $info = model('ModelModel')->get($param['mid']);
	    $tablename = config('database.prefix')."form_".$info['table_name'];
	    $fieldclass = $class[$param['class']];
	    $defaultvalue = array_key_exists("default_value", $param) ? $param['default_value'] : '';
	    $length = $param['maxlength'] ? $param['maxlength'] : 0;
	    $field = $param['field'];
		$res = '';
	    switch ($fieldclass) {
	        case 'varchar':
	            $res = Db::execute("ALTER TABLE `{$tablename}` CHANGE `{$oldfield}` `{$field}` VARCHAR({$length}) DEFAULT '{$defaultvalue}'");
	            break;
	        case 'int':
	            $defaultvalue = intval($defaultvalue);
	            $res = Db::execute("ALTER TABLE `{$tablename}` CHANGE `{$oldfield}` `{$field}` INT(10) UNSIGNED  DEFAULT '{$defaultvalue}'");
	            break;
	        case 'smallint':
	            $defaultvalue = intval($defaultvalue);
	            $res = Db::execute("ALTER TABLE `{$tablename}` CHANGE `{$oldfield}` `{$field}` SMALLINT(5) UNSIGNED  DEFAULT '{$defaultvalue}'");
	            break;
	        case 'text':
	            $res = Db::execute("ALTER TABLE `{$tablename}` CHANGE `{$oldfield}` `{$field}` TEXT");
	            break;
	    }
		return $res;
	}
	
	private function deleteField($param)
	{
	    $info = model('ModelModel')->get($param['mid']);
	    $tablename = config('database.prefix')."form_".$info['table_name'];
	    $field = $param['field'];
	    return Db::execute("ALTER TABLE `{$tablename}` DROP `{$field}`;");
	}
	
	private function fieldformat($fieldlist, $vallist = []){
		$html = '';
		$script = '';
		$ueditor = '';
		foreach ($fieldlist as $k => $v) {
			$req = $v['isrequire'] ? 'lay-verify="required"' : "";
			$val = array_key_exists($v['field'], $vallist) ? $vallist[$v['field']] : $v['default_value'];
			switch ($v['class']) {
				case '------'://分组线
					$html .= '<div class="title_bar mb20">'.$v['remark'].'</div>';
					break;
				case 'text'://单行文本
					$html .= '<div class="layui-form-item">';
			        $html .= '<label class="layui-form-label">'.$v['title'].'</label>';
			        $html .= '<div class="layui-input-block">';
			        $html .= '<input class="layui-input" type="text" name="'.$v['field'].'" value="'.$val.'" placeholder='.$v['title'].' '.$req.'>';
			        $html .= '</div>';
			        $html .= '</div>';
					break;
				case 'textarea'://多行文本
					$html .= '<div class="layui-form-item">';
			        $html .= '<label class="layui-form-label">'.$v['title'].'</label>';
			        $html .= '<div class="layui-input-block">';
			        $html .= '<textarea name="'.$v['field'].'" placeholder="'.$v['title'].'" class="layui-textarea" '.$req.'>'.$val.'</textarea>';
			        $html .= '</div>';
			        $html .= '</div>';
					break;
				case 'seditor'://简约文本编辑器
	                $html .= '<div class="layui-form-item">';
	                $html .= '<label class="layui-form-label">'.$v['title'].'</label>';
	                $html .= '<div class="layui-input-block">';
	                $html .= '<textarea id="'.$v['field'].'" name="'.$v['field'].'" type="text/plain" '.$req.'>'.$val.'</textarea>';
	                $html .= '</div>';
	                $html .= '</div>';
					$ueditor .= 'CKEDITOR.replace("'.$v['field'].'" );';	
	                break;
	            case 'editor'://富文本编辑器
					$html .= '<div class="layui-form-item">';
	                $html .= '<label class="layui-form-label">'.$v['title'].'</label>';
	                $html .= '<div class="layui-input-block">';
	                $html .= '<textarea id="'.$v['field'].'" name="'.$v['field'].'" type="text/plain" '.$req.'>'.$val.'</textarea>';
					
	                $html .= '</div>';
	                $html .= '</div>';
	
	                //$ueditor .= 'UE.getEditor("'.$v['field'].'");';
					$ueditor .= 'CKEDITOR.replace("'.$v['field'].'" );';
					break;
				/*	
				case 'file':		//附件
					$html .= '<div class="layui-form-item">';
				    $html .= '<label class="layui-form-label">'.$v['title'].'</label>';
				    $html .= '<div class="layui-input-inline">';
					$html .= '<input type="text" name="'.$v['field'].'" value="'.$val.'" placeholder="请上传文件" autocomplete="off" class="layui-input" '.$req.'>';
				    $html .= '</div>';
					$html .= '<button type="button" class="layui-btn" id="test-upload-'.$v['field'].'"><i class="layui-icon"></i>上传文件</button>';
					$html .= '<a class="layui-btn layui-btn-normal" href="'.$val.'">下载</a>';
					$html .= '</div>';
					$script .= 'upload.render({';	 			//允许上传的文件后缀
					$script .= '  elem: "#test-upload-'.$v['field'].'"';
					$script .= '  ,url: "/admin/upload/file.html"';
					$script .= '  ,accept: "file"';	 			//普通文件
					$script .= '  ,exts: "jpg|png|gif|pdf|xlsx|xls|doc|docx|zip|rar|7z|mp3|mp4|psd|csv"';	 	//只允许上传压缩文件
					$script .= '  ,before: function(obj){';
					$script .= '	layer.msg("上传中", {icon:16, shade: 0.1, time:0})';
					$script .= '  }';
					$script .= '  ,done: function(res){';
					$script .= '		layer.close(layer.msg());';
					$script .= '		layer.alert(res.msg, {icon:1, time:1000});';
					$script .= '		$(this.item).prev("div").children("input").val(res.data.src);';
					$script .= '  }';
					$script .= '});';
					break;
				*/
				case 'file':		//附件	
					$html .= '<div class="layui-form-item">';
					$html .= '	<label class="layui-form-label">'.$v['title'].'</label>';
					$html .= '	<div class="layui-input-inline">';
					$html .= '		<input type="text" id="'.$v['field'].'" name="'.$v['field'].'" value="'.$val.'" placeholder="'.$v['remark'].'" autocomplete="off" class="layui-input">';
					$html .= '	</div>';
					$html .= '	<button type="button" class="layui-btn feild_name-list" data-id="'.$v['field'].'">选择文件</button>';
					$html .= '	<a class="layui-btn layui-btn-normal" href="/'.$val.'">下载</a>';
					//$html .= '	<a class="layui-btn layui-btn-danger" href="'.$val.'">删除</a>';
					$html .= '</div>';
					break;
					
				case 'image'://图片上传
					$html .= '<div class="layui-form-item">';
					$html .= '<label class="layui-form-label">'.$v['title'].'</label>';
					$html .= '<div class="layui-input-inline">';
					$html .= '<input type="text" name="'.$v['field'].'" value="'.$val.'" placeholder="请上传图片" autocomplete="off" class="layui-input" >';
					$html .= '</div>';
					$html .= '<button style="float: left;" type="button" class="layui-btn" id="layuiadmin-upload-'.$v['field'].'">上传图片</button>';
					$html .= '</div>';
					$script .= 'upload.render({';
					$script .= '	elem: "#layuiadmin-upload-'.$v['field'].'",';
					$script .= '	url: "/admin/upload/image.html",';
					$script .= '	accept: "images",';
					$script .= '	method: "post",';
					$script .= '	acceptMime: "image/*",';
					$script .= '	before: function(obj){';
					$script .= '		layer.msg("上传中", {icon:16, shade: 0.1, time:0});';
					$script .= '	},';
					$script .= '	done: function(res) {';
					$script .= '		layer.close(layer.msg());';
					$script .= '		layer.alert(res.msg, {icon:1, time:1000});';
					$script .= '		$(this.item).prev("div").children("input").val(res.data.src)';
					$script .= '	}';
					$script .= '});';
					break;
					
	            case 'images'://多图片上传
	            	$html .= '<div class="layui-form-item">';
					$html .= '	<label class="layui-form-label">'.$v['title'].'</label>';
	              	$html .= '	<div class="layui-input-block">';
					$html .= '		<div class="layui-upload">';
					$html .= '			<button type="button" class="layui-btn" id="upload-moreimages-'.$v['field'].'">多图片上传</button> ';
					$html .= '			<blockquote class="layui-elem-quote layui-quote-nm layui-clear upload-moreimages" style="margin-top: 10px;">';
					$html .= '  			预览图：';
					$html .= '  			<div class="layui-upload-list" id="upload-more-'.$v['field'].'">';
					$html .= '				<input type="hidden" name="'.$v['field'].'[]" value="">';
					if($val) {
						$images = explode(',', $val);
						if(is_array($images)) {
							foreach($images as $key => $image) {
								if($image) {
									$html .= '<div class="layui-upload-img" style="float:left; position: relative; cursor: pointer; margin-right: 2px; margin-bottom: 2px;">';
									$html .= '	<img width="200px" height="200px" src="'.$image.'" alt="'.$image.'">';
									$html .= '	<div class="layui-icon layui-icon-delete" style="position: absolute; right: 4px; top: 4px; z-index: 2; font-size: 20px;"></div>';
									$html .= '	<input type="hidden" name="'.$v['field'].'[]" value="'.$image.'">';
									$html .= '</div>';
								}
							}
						}
					}
					$html .= '				</div>';
					$html .= '			</blockquote>';
					$html .= '		</div>';
					$html .= '	</div>';
					$html .= '</div>';

					//多图片上传
					$script .= 'upload.render({';
					$script .= '  elem: "#upload-moreimages-'.$v['field'].'"';
					$script .= '  ,url: "/admin/upload/image.html"';
					$script .= '  ,multiple: true';
					
/* 					$script .= '  ,before: function(obj){';
					    //预读本地文件示例，不支持ie8
					$script .= '    obj.preview(function(index, file, result){';					
				  //$script .= '      	$("#upload-more-'.$v['field'].'").append(\'<img data-id=\'+indexnum+\' width="200px" height="200px" style="float:left;" src="\'+ result +\'" alt="\'+ file.name +\'" class="layui-upload-img">\');';
				  //$script .= '    	$("#upload-more-'.$v['field'].'").append(\'<input type="hidden" id="'.$v['field'].'-\'+indexnum+\'" name="'.$v['field'].'[]" value="\'+ result +\'">\')';
					$script .= '    });';
					$script .= '  }'; 
*/
					$script .= '	,before: function(obj){';
					$script .= '		layer.msg("上传中", {icon:16, shade: 0.1, time:0})';
					$script .= '	}';
					$script .= '  ,done: function(res){';
					$script .= '    var indexnum = $(".layui-upload-list img").length;';
					$script .= '    $("#upload-more-'.$v['field'].'").append(\'<div class="layui-upload-img" style="float:left; position: relative; cursor: pointer; margin-right: 2px; margin-bottom: 2px;"><img data-id=\'+indexnum+\' width="200px" height="200px" src="\'+ res.data.src +\'"><div class="layui-icon layui-icon-delete" style="position: absolute; right: 4px; top: 4px; z-index: 2; font-size: 20px;"></div><input type="hidden" name="'.$v['field'].'[]" value="\'+ res.data.src +\'"></div>\');';
					$script .= '	layer.close(layer.msg());';
					$script .= '	layer.alert(res.msg, {icon:1, time:1000});';
					$script .= '  }';
					$script .= '});';
					$script .= '$(".upload-moreimages").on("click",".layui-icon-delete",function(){';
					$script .= '	var _this=$(this);';
					$script .= '	layer.confirm("确定删除此图片？", function(index){';
					$script .= '	layer.close(index);';
					$script .= '		_this.parent().remove();';
					$script .= '	});';
					$script .= '});';
	                break;
				case 'datetime'://日期和时间
	                $html .= '<div class="layui-form-item">';
	                $html .= '<label class="layui-form-label">'.$v['title'].'</label>';
	                $html .= '<div class="layui-input-inline">';
	                
					$html .= '<input type="text" class="layui-input" name="'.$v['field'].'" value="'.$val.'" id="laydate-type-'.$v['field'].'" placeholder="yyyy-MM-dd HH:mm:ss">';
	                $html .= '</div>';
	                $html .= '</div>';
					
					$script .= ' laydate.render({elem: "#laydate-type-'.$v['field'].'",type: "datetime"});';
					break;
				case 'number'://数字
	                $html .= '<div class="layui-form-item">';
	                $html .= '<label class="layui-form-label">'.$v['title'].'</label>';
	                $html .= '<div class="layui-input-inline">';
	                $html .= '<input name="'.$v['field'].'" lay-verify="number" autocomplete="off" value="'.$val.'" placeholder="'.$v['title'].'" class="layui-input" type="text" style="width: 300px;" '.$req.'>';
	                $html .= '</div>';
	                $html .= '</div>';
					break;
				case 'radio'://单选按钮
	                $values = explode("\n", $v['values']);
	
	                $html .= '<div class="layui-form-item">';
	                $html .= '<label class="layui-form-label">'.$v['title'].'</label>';
	                $html .= '<div class="layui-input-block">';
	                $a = 0;
	                foreach ($values as $k1 => $v1) {
	                    $check = ($v1 == $val) || ($v['isrequire'] && $a == 0) ? "checked" : '';
	                    $html .= '<input type="radio" name="'.$v['field'].'" value="'.$v1.'" title="'.$v1.'" '.$check.'>';
	                    $a ++ ;
	                }
	                $html .= '</div>';
	                $html .= '</div>';
	                
					break;
				case 'checkbox'://复选框
	                $values = explode("\n", $v['values']);
	
	                $html .= '<div class="layui-form-item">';
	                $html .= '<label class="layui-form-label">'.$v['title'].'</label>';
	                $html .= '<div class="layui-input-block">';
	
	                $a = 0;
	                $valarr = $val ? explode(',', $val) : [];
	                foreach ($values as $k1 => $v1) {
	                    $check = in_array($v1, $valarr) ? 'checked' : '';
	                    $html .= '<input type="checkbox" name="'.$v['field'].'['.$a.']" value="'.$v1.'" title="'.$v1.'"  '.$check.'/>';
	                    $a ++ ;
	                }
	                $html .= '</div>';
	                $html .= '</div>';
					break;
				case 'select'://下拉框
					$values = explode("\n", $v['values']);
	                $html .= '<div class="layui-form-item">';
	                $html .= '<label class="layui-form-label">'.$v['title'].'</label>';
	                $html .= '<div class="layui-input-inline">';
	                $html .= '<select data-val="true" name="'.$v['field'].'" '.$req.'>';
	                $a = 0;
	                foreach ($values as $k1 => $v1) {
	                    $check = ($v1 == $val) || ($v['isrequire'] && $a == 0) ? "selected" : '';
	                    $html .= '<option value="'.$v1.'" '.$check.'>'.$v1.'</option>';
	                    $a ++ ;
	                }
	                            
	                $html .= '</select>';
	                $html .= '</div>';
	                $html .= '</div>';
					break;
				default:
					break;
			}
		}
		return ['html'=>$html, 'script'=>$script, 'ueditor'=>$ueditor];
	}
}