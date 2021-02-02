<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\controller;


class System extends Base
{

    /**
     * 清空缓存
     */
    public function clearCache()
    {
		delFile(__DIR__.'/../../../runtime/temp', true);
		delFile(__DIR__.'/../../../runtime/cache', true);
		return ['code'=> 0, 'msg'=>'缓存清除成功！', 'data'=> null];
		
    }
 
	/**
	 * 清空缓存
	 */
	public function clearLog()
	{
		delFile(__DIR__.'/../../../runtime/log', true);
		
		return ['code'=> 0, 'msg'=>'日志文件清除成功！', 'data'=> null];
	}
}