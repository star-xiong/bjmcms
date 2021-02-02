<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\facade\Config;
use think\Db;

class Database extends Base
{
	public $backup_path;
    /**
     * 初始化
     *
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $thisatabase
     * @param string $charset
     */
    function __construct(){
        parent::__construct();
		$this->backup_path = "runtime".DIRECTORY_SEPARATOR."data".DIRECTORY_SEPARATOR."backup".DIRECTORY_SEPARATOR;
    }

    /**********************
        所有数据表
    ***********************/
    public function index()
    {
        $Backup = new \database\Backup();
        $tabList = $Backup->tables();//所有数据表
        $this->assign('tabList',$tabList);
        return $this->fetch();

    }

    //备份操作
    public function backup(){       
        //备份表名数组
        $tablename = $this->request->param('tabList');
        $size = $this->request->param('size');
        //执行备份
        $Backup = new \database\Backup();
        if(!empty($tablename)){
            //备份路径
            $backup_sql_path = $this->backup_path.'table'.DIRECTORY_SEPARATOR.date('YmdHis',time()).DIRECTORY_SEPARATOR;
            $backup_num = 0;
            foreach($tablename as $val){
                $backup_sql = $Backup -> backup($val,$backup_sql_path,$size);
                if($backup_sql) $backup_num += 1;
            }
            $this->success($backup_num.'个表'.lang('备份成功'),url('database/index'));
        }else{
            //备份路径
            $backup_sql_path = $this->backup_path.'database'.DIRECTORY_SEPARATOR.date('YmdHis',time()).DIRECTORY_SEPARATOR;
            $backup_sql = $Backup -> backup('',$backup_sql_path,$size);
            if($backup_sql){
                $this->success(lang('备份成功'),url('database/index'));
            }else{
                $this->error(lang('备份失败'),url('database/index'));
            }
        }
    }

    //备份库文件列表
    public function databaseList(){

        $databases=array();
      
        $dir = $this->backup_path.'database'.DIRECTORY_SEPARATOR;//详细目录
        if(!is_dir($dir)) mkdir($dir,0777,true);// 如果文件夹不存在，将以递归方式创建该文件夹
        $dir_list = $this->listDir($dir);

        for ($k=0; $k < count($dir_list) ; $k++) { 
            $one['filename'] = $dir_list[$k];
            $databases[] = $one;

            $listFiles = $this->listFiles($dir.$dir_list[$k].DIRECTORY_SEPARATOR);
            foreach ($listFiles as $key => $value) {
                $value['filesize'] = ceil($value['filesize'] / 1024);
                $value['dir'] = $dir_list[$k];
                $databases[] = $value;
            }
        }
            
        $data['code'] = 0;
        $data['data'] = $databases;
        $data['count'] = count($databases);
        $data['msg'] = lang('not_data');

        if($this->request -> isAjax())
        {
            return json($data);
        }
        else
        {
            $this->assign('data',$data);
            return $this->fetch();
        }
    }
	
    //备份表文件列表
    public function tableList(){

        $databases=array();
      
        $dir = $this->backup_path.'table'.DIRECTORY_SEPARATOR;//详细目录
        if(!is_dir($dir)) mkdir($dir,0777,true);// 如果文件夹不存在，将以递归方式创建该文件夹
        $dir_list = $this->listDir($dir);

        for ($k=0; $k < count($dir_list) ; $k++) { 
            $one['filename'] = $dir_list[$k];
            $databases[] = $one;

            $listFiles = $this->listFiles($dir.$dir_list[$k].DIRECTORY_SEPARATOR);
            foreach ($listFiles as $key => $value) {
                $value['name'] = $value['filename'];
                $value['filesize'] = ceil($value['filesize'] / 1024);
                $value['dir'] = $dir_list[$k];
                $databases[] = $value;
            }
        }

        $data['code'] = 0;
        $data['data'] = $databases;
        $data['count'] = count($databases);
        $data['msg'] = lang('not_data');
        if($this->request -> isAjax())
        {
            return $data;
        }
        else
        {
            $this->assign('data',$data);
            return $this->fetch();
        }
    }

    //数据还原
    public function import(){
        $dir = $this->backup_path;
        $Backup = new \database\Backup();
        $type = $this->request->param('type');
        $filename = ltrim($this->request->param('filename'),'');
            
        if(empty($type) || empty($filename))
        {
             $data= ['code'=>1,'content'=>lang('参数错误')];
             return $data;
        }

        $path = $dir.$type.DIRECTORY_SEPARATOR.$filename;
		
        if(is_dir($path)){//是目录，还原数据库
            //查询第一个文件
            $file = $this->listFiles($path.DIRECTORY_SEPARATOR);
            $filename_one = $path.DIRECTORY_SEPARATOR.$file[0]['filename'];
            $Backup->restore($filename_one);
        }else{//还原数据表
            $file_path = $dir.$type.DIRECTORY_SEPARATOR.$this->request->param('dir').DIRECTORY_SEPARATOR.$filename;
            $Backup->restore($file_path);
        }
    }

    //删除目录
    public function delete(){
        $type = $this->request->param('type');
        $filename = $this->request->param('filename');

        $dir = $this->backup_path.$type.DIRECTORY_SEPARATOR.$filename.DIRECTORY_SEPARATOR;		//要删除的目录路劲
        if(is_dir($dir)){//删除目录
            $return = $this->deldir($dir);
            if($return ) {
				$data = ['code'=> 0, 'msg'=>'删除成功', 'data'=> null];
            } else {
				$data = ['code'=> 1, 'msg'=>'删除失败', 'data'=> null];
            }
        }else{
			
			//删除表文件
            $name = $this->request->param('name');
            $dir1 = $this->request->param('dir');
            $path = $this->backup_path.$type.DIRECTORY_SEPARATOR.$dir1.DIRECTORY_SEPARATOR;
			
            // 查询目录中文件
            $files = $this->listFiles($path);
            preg_match ( "/_v(\d+)\.sql/", $name, $matches);
            $str = str_replace($matches[0],"",$name);
            foreach($files as $v){
               if(strstr($v['filename'],$str)){
                unlink($path.$v['filename']);
               }
                
            }
			
            $data = ['code'=> 0, 'msg'=>'删除成功', 'data'=> null];
        }
        
        return $data;
        
    }
	
	
	//删除目录
    private function deldir($dir){
        if(file_exists($dir)){								//判断目录是否存在，如果不存在rmdir()函数会出错
            if($dir_handle=@opendir($dir)){					//打开目录返回目录资源，并判断是否成功
                while($filename=readdir($dir_handle)){		//遍历目录，读出目录中的文件或文件夹
                    if($filename!='.' && $filename!='..'){	//一定要排除两个特殊的目录
                        $subFile=$dir.$filename;			//将目录下的文件与当前目录相连
                        if(is_dir($subFile)){				//如果是目录条件则成了
                            $this->delDir($subFile);		//递归调用自己删除子目录
                        }
                        if(is_file($subFile)){				//如果是文件条件则成立
                            unlink($subFile);				//直接删除这个文件
                        }
                    }
                }
                closedir($dir_handle);						//关闭目录资源
            }
            return @rmdir($dir);							//删除空目录
        }
    }


    //    查询目录
    private function listDir($dir){
        //打开目录
        $handle=opendir($dir);
        $dir_list=array();//目录列表
        //阅读目录
        while(false != ($dir_name=readdir($handle))){
            //列出所有目录并去掉‘.’和‘..’
            if($dir_name!='.' && $dir_name!='..'){
                $dir_list[]=$dir_name;
            }
        }
        return $dir_list;
    }
	
    //    查询文件
    private function listFiles($dir){
        $files = array();
        //打开目录
        $handle=opendir($dir);
        //阅读目录
        while(false!=($file=readdir($handle))) {
            //列出所有文件并去掉'.'和'..'
            if($file!='.'&&$file!='..') {
                //所得到的文件名是否是一个目录
                if(is_dir($dir.$file)) {
                    //列出目录下的文件
                    $this->listFiles($dir.$file);
                } else {
                    //如果是文件则打开该文件
                    $fp=fopen($dir.$file,"r");
                    //阅读文件内容
                    $data=fread($fp,filesize($dir.$file));
                    if($data){
                        //将读到的内容赋值给一个数组
                        $files[]=$dir.$file;
                    }
                }
            }
        }
        if(is_array($files)) {
            asort($files);
            $other = $others = array();
            foreach($files as $id=>$file) {
                $other['filename'] = basename($file);
                $other['filesize'] = filesize($file);
                $other['maketime'] = date('Y-m-d H:i:s',filemtime($file));
                $others[] = $other;
            }
        }
        
        return $others;
      
    }
}