<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\request;

use app\admin\validates\RoleValidate;

class RoleRequest extends FormRequest
{
    public function validate()
    {
        return (new RoleValidate())->getErrors($this->post());
    }
}