<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\request;

use app\admin\validates\PermissionValidate;

class PermissionRequest extends FormRequest
{
    public function validate()
    {
        return (new PermissionValidate())->getErrors($this->post());
    }
}