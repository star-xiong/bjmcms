<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\Controller;
use app\admin\traits\Auth;
use app\admin\traits\ControllerTrait;

abstract class Base extends Controller
{
	use ControllerTrait,Auth;
	
	protected $limit = 10;

	protected $page  = 1;

	protected $middleware = ['checkLogin', 'auth', 'logRecord'];

	/**
	 * 过滤参数
	 *
	 * @time at 2019年02月26日
	 * @param $params
	 * @return void
	 */
	protected function checkParams(&$params)
	{
		$this->limit = $params['limit'] ?? $this->limit;
		$this->page  = $params['page'] ?? $this->page;

		foreach ($params as $key => $param) {
			if (!$param || $key == 'limit' || $key == 'page') {
				unset($params[$key]);
			}
		}
		$this->start = $this->start();
	}
	
	/**
	 * paginator 转layui的分页
	 *
	 * @time at 2019年02月28日
	 * @param $paginator
	 * @param $code     返回码：0成功，其它失败
	 * @param $msg      返回信息
	 * @return $data array
	 */
	protected function layuiPaginator($paginator, $code=0, $msg='')
	{
		if(is_array($paginator)){
			$data['data'] = $paginator;
			$data['count'] = count($paginator);
		}
		else{
			$data = $paginator->toArray();
			if(isset($data['total'])) {
				$data['count'] = $data['total'];
			}
			else{
				$data = [];
				$data['count'] = count($paginator);
				$data['data'] = $paginator;
			}
		}
		
		$data['code'] = 0;
		$data['msg'] = '';
		return $data;
	}

	/**
	 * Table ID Start
	 *
	 * @time at 2019年02月26日
	 * @return float|int
	 */
	protected function start()
	{
		return (int)$this->limit * ((int)$this->page - 1) + 1;
	}
}
