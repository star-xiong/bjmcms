<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\model\LogRecordModel;

class Log extends Base
{
    /**
     * 日志管理
     *
     * @time at 2019年01月18日
     * @param LogRecordModel $logRecordModel
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }
	
	/**
	 * 日志列表
	 *
	 * @time at 2019年01月18日
	 * @param LogRecordModel $logRecordModel
	 * @return mixed
	 */
	public function getList(LogRecordModel $logRecordModel)
	{
	    $params = $this->request->param();
	    $this->checkParams($params);
		$data = $logRecordModel->getAll($params, $this->limit);

		return json($this->layuiPaginator($logRecordModel->getAll($params, $this->limit)));
	}
}