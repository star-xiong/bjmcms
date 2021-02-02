<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\api\behavior;
 
use think\Response;
 
class CORS
{
    public function appInit()
    {
        header('Access-Control-Allow-Origin: *');//允许所有访问地址
        header("Access-Control-Allow-Headers: token,Origin, X-Requested-With, Content-Type, Accept");//设置你要请求的头部信息
        header('Access-Control-Allow-Methods: POST,GET');//设置你请求的方式
        header('Access-Control-Allow-Credentials:false');//设置允许cookie跨域，这里如果设置为true上面就不能允许所有的地址了，要改为指定地址
        if (request()->isOptions()) {
            exit();
        }
    }
}