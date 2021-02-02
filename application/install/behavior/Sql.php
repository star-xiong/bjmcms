<?php
namespace app\install\behavior;

class Sql 
{
	/*========================================执行sql文件行为参数=============================================
	$params = [ 
	   'files'=>['sql'=>100,'sql2'=>200], //所有执行的sql文件  sql：文件名称  100，文件名称对应的sql条数   
	   'file'=>'sql',//sql当前执行文件名称  ------------------------------------------------------------------ sqlBegin|sqlEnd 中无此参数
	   'database'=>'', //数据库名称
	   'table'=>'', //表名称 -------------------------------------------------------------------------------- sqlBegin|sqlEnd 中无此参数
	   'progress'=> 0.2,//当前执行sql文件进度，在多个sql文件中执行的进度  -----------------------------------------sqlBegin|sqlEnd 中无此参数
	   'sqltotal'=>1000,//所有sql总的sql语句条数   
	   'sqlnum'=>10 //当前执行的条数在总的sql语句中的条数位置   ---------------------------------------------------sqlBegin|sqlEnd 中无此参数
	   'filenum'=>10 //当前执行的条数在当前文件的sql语句中的条数位置  ----------------------------------------------sqlBegin|sqlEnd 中无此参数
	   ];
	========================================执行sql文件行为参数=============================================*/
	public function sqlBegin($params)
    {
		
		 
          //执行sql前 钩子名称 sql_begin
    }  
	
 	public function sqlEnd($params)
    {
          //执行sql后 钩子名称 sql_end
		  
    }
		
    public function sqlcreateBegin($params)
    {
          //创建数据表前  钩子名称 sqlcreate_begin
		   
    }
	
	public function sqlcreateEnd($params)
    {
          //创建数据表后 钩子名称 sqlcreate_end
		  
		  $params['type'] = 1; // 创建数据库
		  cache('sqlfile',$params);
    }
	
	public function sqlinsertBegin($params)
    {
          //插入数据库前 钩子名称 sqlinsert_begin
		  
    }  
	
 	public function sqlinsertEnd($params)
    {
          //插入数据库后 钩子名称 sqlinsert_end
		  $params['type'] = 2; // 插入数据库
		  cache('sqlfile',$params);
    }  
}
?>