<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\request;

use app\admin\validates\FieldValidate;

class FieldRequest extends FormRequest
{
    public function validate()
    {
        return (new FieldValidate())->getErrors($this->post());
    }
}