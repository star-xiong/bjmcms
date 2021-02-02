<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\request;

use app\admin\validates\TempleteValidate;

class TempleteRequest extends FormRequest
{
    public function validate()
    {
        return (new TempleteValidate())->getErrors($this->post());
    }
}