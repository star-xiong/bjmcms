<?php
// +----------------------------------------------------------------------
//	env_items 环境检测默认内容
//	dir_items 默认目录权限检测
//  func_items php函数检测
// +----------------------------------------------------------------------
return [
	'content'=>array(
		'env_items' => array
		(
			//'检测环境变量名称' =>array('说明文字','需要值','检测方法；'); 检测方法返回数组  array（'status'=>是否通过 （布尔值），'rightnow'=>当前状态显示）
			'os' => array('操作系统','WINNT|Linux',function($system='WINNT|Linux'){
				$array['rightnow'] = PHP_OS;
				$system = explode("|",$system);
				if(!is_array($system)){
					if(PHP_OS!=$system)
					{
					  $array['status'] = false;
					}
					else
					{
					  $array['status'] = true;
					}
				}else{
					if(!in_array(PHP_OS,$array))
					{
					  $array['status'] = false;
					}
					else
					{
					  $array['status'] = true;
					}
				}
				return $array;
			}),
			'php' => array('php版本','5.6.0',function(){
				$array['rightnow'] = PHP_VERSION;
				if(version_compare(PHP_VERSION,'5.6.0', '<'))
				{
					$array['status'] = false;
				}
				else
				{
					$array['status'] = true;
				}
				return $array;
			}),
			'attachmentupload' => array('附件上传','可用',function(){
				if(@ini_get('file_uploads')){
					$array['rightnow'] =  ini_get('upload_max_filesize');
					$array['status'] = true;
				}else{
					$array['rightnow'] = "不可用";;
					$array['status'] = false;
				}
				return $array;
			}),
			'gdversion' => array("GD扩展",'可用',function(){
				if(extension_loaded('gd')){
					$tmp = gd_info();
					$array['rightnow'] = empty($tmp['GD Version']) ? '' : $tmp['GD Version'];
					$array['status'] = true;
					unset($tmp);
				}else{
					$array['rightnow'] = "不可用";
					$array['status'] = false;
				}
				return $array;
			}),
		),
		'dir_items' => array
		(
			'application/install',
		),
		'func_items' => array(
			//'db'=>'class|function|model',//php是否加载类/模块 class|function|model 页可以是函数
			'file_get_contents'=>'function',
			'curl_init'=>'function',
			'mb_strlen'=>'function',
			//'finfo_open'=>'function',
			'mysqli'=>'class',
		),
	)
];
