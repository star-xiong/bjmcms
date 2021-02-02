<?php 
/*************************************************************
	is_write 判断文件或者文件夹是否有对象权限
	@param $file string  文件或者文件夹路径
    @return int
*************************************************************/	
	function is_write($file)
    {
		 $F = new \files\File();
         return $F->is_write($file);
    }
	
	
/*************************************************************
	is_php_system 判断系统类模块函数是否被支持
	@param $data array 系统设置参数
    @return array
*************************************************************/	
	function get_php_system($data)
    {
		  if(!is_array($data) || count($data) <=0) return [];
		  $returnData=[];
		  foreach($data as $k => $v)
		  {
			   if(!$v) continue;
			   
			   if(is_callable($v))
			   {
				    $param = ['key'=>$k];
				    $returnData[] = $v($param);
			   }
			   elseif($v == "class")
			   {
				    
					$returnData[] = [$k,'类',class_exists($k)];
			   }
			   elseif($v == "function")
			   {
				    
					$returnData[] = [$k,'函数',function_exists($k)];
			   }
			   elseif($v == "model")
			   {
				    
					$returnData[] = [$k,'模块',extension_loaded($k)];
			   }
			   else
			   {
				    $returnData[] = [$k,'未知',false];
			   }			   
		  }
		  
		  return $returnData;
    }
	
	
/*************************************************************
	is_write_array 判断文件或者文件夹是否有读写权限
	@param $data array 系统设置参数
    @return array
*************************************************************/	
	function is_write_array($data)
    {
		  if(!is_array($data) || count($data) <=0) return [];
		  $returnData=[];
		  foreach($data as $k => $v)
		  {
			   if(!$v) continue;

			   $returnData[]=[$v,is_write(\App::getRootPath().$v)];
		  }
		  
		  return $returnData;
    }
	
	
/*************************************************************
	is_write_array 环境检测
	@param $data array 系统设置参数
    @return array
*************************************************************/	
	function is_system($data)
    {
		  if(!is_array($data) || count($data) <=0) return [];
		  $returnData=[];
		  foreach($data as $k => $v)
		  {
			   if(!$v || !is_array($v) ) continue;
               
			   $is = is_callable($v[2]) ?  $v[2]($v[1]) : $v[2];

			   $returnData[]=[$v[0],$v[1],$is];
		  }
		  
		  return $returnData;
    }
	
/*************************************************************
	reg_sql 对sql语句正则处理，并放到数组中 环境检测
	@param $sql string sql字符串
	@param $pre array 数组 表前缀
    @return array
*************************************************************/	
	function reg_sql($sql,$pre=['mkcms_'=>'mkcms_'])
    {
		  if(trim($sql)=="") return [];
		  $returnData = [];
		  
		  if(count($pre) > 0 )
		  {
			  $pre_place = current($pre); //要替换的前缀值
			  $pre_original = current(array_flip($pre)); //被替换的原始前缀
		  }
		  
		  // 多行注释标记
          $comment = false;
		  
		  // 按行分割，兼容多个平台
		  $sql = str_replace(["\r\n", "\r"], "\n", $sql);
		  $sql = explode("\n", trim($sql));	
		  
		  // 循环处理每一行
		  foreach ($sql as $key => $line) {
			  // 跳过空行
			  if ($line == '') continue;

			  // 跳过以#或者--开头的单行注释
			  if (preg_match("/^(#|--)/", $line)) continue;

			  // 跳过以/**/包裹起来的单行注释
			  if (preg_match("/^\/\*(.*?)\*\//", $line)) continue;

			  // 多行注释开始
			  if (substr($line, 0, 2) == '/*') {
				  $comment = true;
				  continue;
			  }

			  // 多行注释结束
			  if (substr($line, -2) == '*/') {
				  $comment = false;
				  continue;
			  }

			  // 多行注释没有结束，继续跳过
			  if ($comment) continue;

			  // 替换表前缀
			  if ($pre_original != '') {
				  $line = str_replace('`'.$pre_original, '`'.$pre_place, $line);
			  }
			  if ($line == 'BEGIN;' || $line =='COMMIT;') {
				  continue;
			  }
			  // sql语句
			  array_push($returnData, $line);
		  }	 
		  
		  $returnData = implode($returnData, "\n");
          $returnData = explode(";\n", $returnData); 
		  return $returnData;
    }

?>