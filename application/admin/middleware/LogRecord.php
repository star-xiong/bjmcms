<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\middleware;

use app\admin\service\LogService;

class LogRecord
{

    public function handle($request, \Closure $next)
    {
        (new LogService())->record($request);
		
        return $next($request);
    }
}
