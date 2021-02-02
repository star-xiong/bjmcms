<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\middleware;
use app\traits\Auth;

class CheckLogin
{
	use Auth;
	
    public function handle($request, \Closure $next)
    {
		
		if ($request->session(config('permissions.user'))) {
			return $next($request);
		}	
		else if($request->cookie(config('permissions.token'))) {
			$this->rememberLogin();
			return $next($request);
		}
		else{
			return redirect(url('login'));
		}
    }
}
