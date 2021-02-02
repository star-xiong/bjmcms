<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * 钩子行为
 */
if (!function_exists('hook')) {
	function hook($behavior, $params) {
		\think\facade\Hook::exec($behavior, $params);
	}
}

/**
 * switch 开关值[on/off]转为[1/0]
 */
if (!function_exists('switch_on_to_1')) {
	function switch_on_to_1($value) {
		if($value == 'on' || $value == 1){
			$value = 1;
		}
		else{
			$value = 0;
		}
		return $value;
	}
}

/**
 * 为某商品生成唯一的货号
 * @param   int     $goods_id   商品编号
 * @return  string  唯一的货号
 */
if (!function_exists('generate_goods_sn')) {
	function generate_goods_sn($site_id)
	{
		$sn_prefix = "BJM".(100+$site_id);
		$goods = model('GoodsModel')->field('goods_sn')->where('goods_sn','LIKE',$sn_prefix.'%')->order('goods_sn desc')->find();
		if(empty($goods)){
			$goods_sn = $sn_prefix.'0000001';
		}
		else{
			$goods_sn = 'BJM'.(substr($goods['goods_sn'],-10) + 1);
		}
		
		return $goods_sn;
	}
}

/**
 * 填充空格
 * @number   int     空格数量
 * @return  nbsp string
 */
if (!function_exists('generate_nbsp')) {
	function generate_nbsp($number)
	{
		return str_repeat('&nbsp;&nbsp;&nbsp;', $number);
	}
}

/**
 * 生成密码
 */
if (!function_exists('generatePassword')) {
	function generatePassword(string  $password, int $algo = PASSWORD_DEFAULT) {
		return password_hash($password, $algo);
	}
}

function show_json($status = 1, $return = NULL) {

	$ret = array('status' => $status, 'result' => array());
	if (!is_array($return)) {
		if ($return) {
			$ret['result']['message'] = $return;
		}
		exit(json_encode($ret));
	} else {
		$ret['result'] = $return;
	}
	exit(json_encode($ret));

}
/**
 * [getCsv description]  导出csv文件
 * @param  string  $csvFileName [description] 文件名
 * @param  integer $line        [description] 读取几行，默认全部读取
 * @param  integer $offset      [description] 从第几行开始读，默认从第一行读取
 * @return [type]               [description]
 */
if (!function_exists('getCsv')) {
	function getCsv($handle, $line = 0, $offset = 0){
		//fgetcsv() 出错时返回 FALSE，包括碰到文件结束时。
		$i = 0;//用于记录while的循环次数，方便与$line,$offset比较
		$list = array();//结果的存放数组
		while($data = fgetcsv($handle)){
			//小于偏移量则不读取,但$i仍然需要自增
			if($i < $offset && $offset){
				$i++;
				continue;
			}
			
			//大于读取行数则退出
			if($i > $line && $line){
				break;
			}
			$i++;
			$arr = [];
			foreach ($data as $key => $value) {
				$content = iconv("gbk","utf-8//IGNORE", $value);		//转化编码
				$arr[] = $content;										//至于如何处理这个结果，需要根据实际情况而定
			}
			$list[] = $arr;
		}
		return $list;	
	}
}




if (!function_exists('delFile')) 
{  

    /**
     * 递归删除文件夹
     *
     * @param string $path 目录路径
     * @param boolean $delDir 是否删除空目录
     * @return boolean
     */
    function delFile($path, $delDir = FALSE) {

        if(!is_dir($path))
             return FALSE;
		
        $handle = @opendir($path);

        if ($handle) {
            while (false !== ( $item = readdir($handle) )) {
                if ($item != "." && $item != "..")
                    is_dir("$path/$item") ? delFile("$path/$item", $delDir) : @unlink("$path/$item");
            }
            closedir($handle);
            if ($delDir) {
                return @rmdir($path);
            }
        }else {
            if (file_exists($path)) {
                return @unlink($path);
            } else {
                return FALSE;
            }
        }
    }
}

if (!function_exists('getDirFile')) 
{ 
    /**
     * 递归读取文件夹文件
     *
     * @param string $directory 目录路径
     * @param string $dir_name 显示的目录前缀路径
     * @param array $arr_file 是否删除空目录
     * @return boolean
     */
    function getDirFile($directory, $dir_name='', &$arr_file = array()) {
        if (!file_exists($directory) ) {
            return false;
        }

        $mydir = dir($directory);
        while($file = $mydir->read())
        {
            if((is_dir("$directory/$file")) AND ($file != ".") AND ($file != ".."))
            {
                if ($dir_name) {
                    getDirFile("$directory/$file", "$dir_name/$file", $arr_file);
                } else {
                    getDirFile("$directory/$file", "$file", $arr_file);
                }
                
            }
            else if(($file != ".") AND ($file != ".."))
            {
                if ($dir_name) {
                    $arr_file[] = "$dir_name/$file";
                } else {
                    $arr_file[] = "$file";
                }
            }
        }
        $mydir->close();

        return $arr_file;
    }
}

if (!function_exists('mk_scandir')) 
{ 
    /**
     * 部分空间为了安全起见，禁用scandir函数
     *
     * @param string $dir 路径
     * @return array
     */
    function mk_scandir($dir, $type = 'all')
    {
        if(function_exists('scandir')){
            $files = scandir($dir);
        } else {
            $files = [];
            $mydir = dir($dir);
            while($file = $mydir->read())
            {
                $files[] = "$file";
            }
            $mydir->close();
        }
        $arr_file = [];
        foreach ($files as $key => $val) {
            if(($val != ".") AND ($val != "..")){
                if ('all' == $type) {
                    $arr_file[] = "$val";
                } else if ('file' == $type && is_file($val)) {
                    $arr_file[] = "$val";
                } else if ('dir' == $type && is_dir($val)) {
                    $arr_file[] = "$val";
                }
            }
        }

        return $arr_file;
    }
}

if (!function_exists('recurse_copy')) 
{  
    /**
     * 自定义函数递归的复制带有多级子目录的目录
     * 递归复制文件夹
     *
     * @param type $src 原目录
     * @param type $dst 复制到的目录
     */                        
    //参数说明：            
    //自定义函数递归的复制带有多级子目录的目录
    function recurse_copy($src, $dst)
    {
        $now = getTime();
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== $file = readdir($dir)) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    recurse_copy($src . '/' . $file, $dst . '/' . $file);
                }
                else {
                    if (file_exists($dst . DIRECTORY_SEPARATOR . $file)) {
                        if (!is_writeable($dst . DIRECTORY_SEPARATOR . $file)) {
                            // exit($dst . DIRECTORY_SEPARATOR . $file . '不可写');
                            return '网站目录没有写入权限，请调整权限';
                        }
                        // @unlink($dst . DIRECTORY_SEPARATOR . $file);
                    }
                    // if (file_exists($dst . DIRECTORY_SEPARATOR . $file)) {
                    //     @unlink($dst . DIRECTORY_SEPARATOR . $file);
                    // }
                    $copyrt = @copy($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);
                    if (!$copyrt) {
                        // echo 'copy ' . $dst . DIRECTORY_SEPARATOR . $file . ' failed';
                        return '网站目录没有写入权限，请调整权限';
                    }
                }
            }
        }
        closedir($dir);

        return true;
    }
}
