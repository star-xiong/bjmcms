<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\Validate;
use think\Image;
use think\facade\Cookie;

class Upload extends Base
{
	
	/*
		*CKEDITOR UPLOAD API
		*return json
	*/
	public function image(){

		// 获取表单上传文件 例如上传了001.jpg
		$file = request()->file('file');

		// 移动到框架应用根目录/uploads/ 目录下
		$info = $file->validate(['size'=>1024000,'ext'=>'jpeg,jpg,png,gif'])
								->move( 'public/uploads/images');
		$data = [];
		if($info){
			// 成功上传后 获取上传信息
			//echo $info->getExtension();
			//echo $info->getSaveName();
			//echo $info->getFilename(); 
			$src = $info->getSaveName();
			
			$imgFileName = 'public/uploads/images/' . $src;
			
			$site_info = model("SiteModel")->get(session('site'));
			
			if($site_info['watermark']){
				$image = Image::open($imgFileName); //要加水印的图片
				if($site_info['wk_text']){
					$font_ttf = 'static/admin/font/simkai.ttf';
					$image->text($site_info['wk_text'],$font_ttf,35,'#CCCCCC',Image::WATER_SOUTHEAST,0,50)->save($imgFileName);
					$image->text($site_info['wk_text'],$font_ttf,35,'#CCCCCC',Image::WATER_SOUTHWEST,0,50)->save($imgFileName);
					$image->text($site_info['wk_text'],$font_ttf,35,'#CCCCCC',Image::WATER_NORTHWEST,0,50)->save($imgFileName);
					$image->text($site_info['wk_text'],$font_ttf,35,'#CCCCCC',Image::WATER_NORTHEAST,0,50)->save($imgFileName);
					$image->text($site_info['wk_text'],$font_ttf,35,'#CCCCCC',Image::WATER_CENTER,0,50)->save($imgFileName);
					
				}
				else{
					
				}
			}
			
			$src = Cookie::get('bjmsite_domain').'/public/uploads/images/' . str_replace('\\', '/', $src);
			$data = ['code'=> 0, 'msg'=>'上传成功', 'data'=> ['src'=>$src]];
		}else{
			// 上传失败获取错误信息
			//echo $file->getError();
			$data = ['code'=> 1, 'msg'=>$file->getError(), 'data'=> null];
		}
		return json($data);
	}
	
	/*
	*CKEDITOR UPLOAD API
	*return json
	*/
	public function ckimage(){
	
		$file = request()->file('upload');

		$info = $file->validate(['size'=>20480000,'ext'=>'jpeg,jpg,png,gif,tif'])
								->move( 'public/uploads/ckeditor/images');
		$data = [];
		if($info){
			// 成功上传后 获取上传信息
			$data = ['uploaded' => 1, 'fileName' => $info->getFilename(), 'url' => Cookie::get('bjmsite_domain').'/public/uploads/ckeditor/images/'.$info->getSaveName()];
		}else{
			// 上传失败获取错误信息
			$data = ['uploaded' => 0,   "error" => ["message" => $file->getError()]];
		}
		
		return json($data);
	}
	
	public function file(){
	
		// 获取表单上传文件 例如上传了001.jpg
		$file = request()->file('file');
	
		// 移动到框架应用根目录/uploads/ 目录下
		$info = $file->validate(['size'=>102400000,'ext'=>'jpeg,jpg,png,gif,pdf,xlsx,xls,doc,docx,zip,rar,7z,mp3,mp4,csv,psd'])
								->move( 'public/uploads/files');
		$data = [];
		if($info){
			// 成功上传后 获取上传信息
			// 输出 jpg
			//echo $info->getExtension();
			
			// 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
			//echo $info->getSaveName();
			
			// 输出 42a79759f284b767dfcb2a0197904287.jpg
			//echo $info->getFilename(); 
			$src = $info->getSaveName();
			$src = Cookie::get('bjmsite_domain').'/public/uploads/files/' . str_replace('\\', '/', $src);
			$data = ['code'=> 0, 'msg'=>'上传成功', 'data'=> ['src'=>$src]];
		}else{
			// 上传失败获取错误信息
			//echo $file->getError();
			$data = ['code'=> 1, 'msg'=>$file->getError(), 'data'=> null];
		}
		return json($data);
	}
	public function uploadFile() {
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
		    exit; // finish preflight CORS requests here
		}
		if ( !empty($_REQUEST['debug']) ) {
		    $random = rand(0, intval($_REQUEST['debug']) );
		    if ( $random === 0 ) {
		        header("HTTP/1.0 500 Internal Server Error");
		        exit;
		    }
		}
		
		@set_time_limit(0);
		
		$targetDir = 'public/uploads/file_material_tmp';
		$uploadDir = 'public/uploads/file_material/'.date('Ymd');
		
		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds
		$mode = 0777; // 0777 权限
		
		// Create target dir
		if (!file_exists($targetDir)) {
		    @mkdir($targetDir, $mode,true);
		    @chmod($targetDir, $mode);
		}
		// Create target dir
		if (!file_exists($uploadDir)) {
		    @mkdir($uploadDir, $mode, true);
		    @chmod($uploadDir, $mode);
		}
		// Get a file name
		if (isset($_REQUEST["name"])) {
		    $fileName = $_REQUEST["name"];
		} elseif (!empty($_FILES)) {
		    $fileName = $_FILES["file"]["name"];
		} else {
		    $fileName = uniqid("file_");
		}
		$oldName = $fileName;
		$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
		// $uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;
		// Chunking might be enabled
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;
		// Remove old temp files
		if ($cleanupTargetDir) {
		    if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
		        show_json(100, 'Failed to open temp directory.');
		    }
		    while (($file = readdir($dir)) !== false) {
		        $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;
		        // If temp file is current file proceed to the next
		        if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
		            continue;
		        }
		        // Remove temp file if it is older than the max age and is not the current file
		        if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
		            @unlink($tmpfilePath);
		        }
		    }
		    closedir($dir);
		}
		
		// Open temp file
		if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
		    show_json(102, 'Failed to open output stream.');
		}
		if (!empty($_FILES)) {
		    if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
		        show_json(103, 'Failed to move uploaded file.');
		    }
		    // Read binary input stream and append it to temp file
		    if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
		        show_json(101, 'Failed to open input stream.');
		    }
		} else {
		    if (!$in = @fopen("php://input", "rb")) {
		        show_json(101, 'Failed to open input stream.');
		    }
		}
		while ($buff = fread($in, 4096)) {
		    fwrite($out, $buff);
		}
		@fclose($out);
		@fclose($in);
		rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");
		$index = 0;
		$done = true;
		for( $index = 0; $index < $chunks; $index++ ) {
		    if ( !file_exists("{$filePath}_{$index}.part") ) {
		        $done = false;
		        break;
		    }
		}
		
		if ( $done ) {
		    $pathInfo = pathinfo($fileName);
		    $hashStr = substr(md5($pathInfo['basename']),8,16);
		    $hashName = time() . $hashStr . '.' .$pathInfo['extension'];
		    //$uploadPath = $uploadDir . DIRECTORY_SEPARATOR .$hashName;
			$uploadPath = $uploadDir . '/' .$hashName;
		
		    if (!$out = @fopen($uploadPath, "wb")) {
		        show_json(102, 'Failed to open output stream.');
		    }
		    if ( flock($out, LOCK_EX) ) {
		        for( $index = 0; $index < $chunks; $index++ ) {
		            if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
		                break;
		            }
		            while ($buff = fread($in, 4096)) {
		                fwrite($out, $buff);
		            }
		            @fclose($in);
		            @unlink("{$filePath}_{$index}.part");
		        }
		        flock($out, LOCK_UN);
		    }
		    @fclose($out);
		    $response = [
		        'success'=>true,
		        'oldName'=>$oldName,
		        'filePaht'=>Cookie::get('bjmsite_domain').$uploadPath,
		        'fileSuffixes'=>$pathInfo['extension'],
		        'hashName' => $hashName
		    ];
		
		    // 当status返回1时，为上传成功，其他则失败
		    show_json(1, array('message' => '上传成功',  'info' => $response));
		
		}
		
		show_json(200, array('message' => '上传成功中'));
	}
}