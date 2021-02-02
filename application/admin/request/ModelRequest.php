<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\request;

use app\admin\validates\ModelValidate;

class ModelRequest extends FormRequest
{
    public function validate()
    {
        return (new ModelValidate())->getErrors($this->post());
    }
}