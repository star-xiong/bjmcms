<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\request;

use app\admin\validates\ContentValidate;

class ContentRequest extends FormRequest
{
    public function validate()
    {
        return (new ContentValidate())->getErrors($this->post());
    }
}