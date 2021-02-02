<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\BaseModel;
use http\Env\Request;

class LogRecordModel extends BaseModel
{
    protected $name = 'option_log';

    /**
     * 日志列表
     *
     * @time at 2019年03月02日
     * @param array $params
     * @param int $limit
     * @return mixed
     */
    public function getAll(array $params, $limit = self::LIMIT)
    {
        if (!count($params)) {
            return $this->order('created_at', 'desc')->paginate($limit, false, ['query' => request()->param()]);
        }

        if (isset($params['name'])) {
            $list = $this->whereLike('user_name', '%'.$params['name'].'%');
        }

        return $list->order('created_at', 'desc')->paginate($limit, false, ['query' => request()->param()]);
    }
}