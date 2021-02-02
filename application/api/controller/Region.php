<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\api\controller;

use think\Controller;

class Region extends Controller
{
	/*
	*@region_type 0:中国、1:省、2:市、3:区
	*@parent 上级
	*@type region_type
	*@target Object
	*@regions return json
	*/
	public function getRegions() {
		$params = $this->request->get();
		$type   = !empty($params['type']) ? intval($params['type'])   : 0;
		$parent = !empty($params['parent']) ? intval($params['parent']) : 0;
		
		$arr['regions'] = model("RegionModel")->field("region_id, region_name")
											->where('region_type',$type)
											->where('parent_id', $parent)
											->select();
		//$arr['type']    = $type;
		$arr['target']  = !empty($params['target']) ? stripslashes(trim($params['target'])) : '';
		$arr['target']  = htmlspecialchars($arr['target']);
		
		return json(['regions' => $arr['regions'], 'type' => $type, 'target' => $arr['target']]);
		
	}
}
