<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\request;

use app\admin\validates\BrandValidate;

class BrandRequest extends FormRequest
{
    public function validate()
    {
        return (new BrandValidate())->getErrors($this->post());
    }
}