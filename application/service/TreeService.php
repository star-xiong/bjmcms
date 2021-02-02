<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\service;

use think\Collection;

class TreeService
{

	/**
	 * 树形结构
	 *
	 * @time at 2019年02月26日
	 * @param $menu
	 * @return Collection
	 */
	public function tree(Collection $list, int $pid = 0)
	{
		$collection = new Collection();

		$list->each(function ($item, $key) use ($pid, $list, $collection){
				if ($item->pid == $pid) {
					$collection[$item->id] = $item;
					$collection[$item->id][$item->id] = $this->tree($list, $item->id);
					if(count($collection[$item->id][$item->id]) < 1){
						$collection[$item->id][$item->id] = 0;
					}
				}
		});

		return $collection;
	}
	
	
	public function tree_array($list, int $pid = 0)
	{

		static $collection = [];
		//$list->each(function ($item, $key) use ($pid, $list, $collection){
		foreach($list as $key=>$item) {
				if ($item['pid'] == $pid) {
					$collection[$key] = $item;
					$collection[$key][$item['id']] = $this->tree_array($list, $item['id']);
					if(count($collection[$key][$item['id']]) < 1){
						$collection[$key][$item['id']] = 0;
					}
				}
		};

		return $collection;
	} 
	/**
	 * 顺序结构
	 *
	 * @time at 2019年02月26日
	 * @param $list
	 * @return Collection
	 */
	public function sort(Collection $list, int $pid = 0, int $level = 0)
	{
		$collection = [];
		foreach ($list as $row) {
			if ($row->pid == $pid) {
				$row->level = $level;
				$collection[] = $row;
				$collection  = array_merge($collection, $this->sort($list, $row->id, $level+1));
			}
		}
		return $collection;
	}
}