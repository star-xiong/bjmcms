<?php
/**
 * Created by StarXiong.
 * admin: Administrator
 * Date: 2019/2/26
 * Time: 上午 11:33
 */
namespace app\service;

use think\paginator\driver\Bootstrap;

class PaginateService extends Bootstrap
{

	/**
	 * 渲染分页html
	 * @return mixed
	 */
	public function render()
	{
		if ($this->hasPages()) {
			if ($this->simple) {
				return sprintf(
					'<ul class="pager">%s %s</ul>',
					$this->getPreviousButton(),
					$this->getNextButton()
				);
			} else {
				return sprintf(
					'<ul class="pagination">%s %s %s %s</ul>',
					$this->getPreviousButton(),
					$this->getLinks(),
					$this->getNextButton(),
					$this->changeLimit()
				);
			}
		}
	}

	/**
	 * 渲染分页简单样式 熊保新修改 html
	 * @return mixed
	 */
	public function render_simple()
	{
		if ($this->hasPages()) {
			return sprintf(
				'<ul class="pager">%s %s</ul>',
				$this->getPreviousButton(),
				$this->getNextButton()
			);
		}
	}

	protected function changeLimit()
	{
		$query = $this->options['query'];
		$html = '&nbsp;<li class="project_page">';

		$pageLimit = config('admin.page_limit');
		$html .= '<select class="page-form-control limit" name="limit">';
		foreach ($pageLimit as $limit) {
			if (isset($query['limit']) && $query['limit'] == $limit) {
				$html .= sprintf('<option value="%s" selected>%s条/页</option>', $limit, $limit);
			} else {
				$html .= sprintf('<option value="%s">%s条/页</option>', $limit, $limit);
			}
		}
		$html .= '</select></li>&nbsp;<li>';

		$html .= sprintf('<input name="page" class="page-form-control-input" value="%s"> 页 ', $query['page'] ?? 1);
		$html .='</li>';

		$html .= '<li><button class="btn btn-primary btn-xs hrefTo"><i class="fa fa-location-arrow"></i> 跳转</button></li>';
		return $html;
	}
}