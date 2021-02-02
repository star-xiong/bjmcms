<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// | 友情链接
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\request\LinkRequest;

class Link extends Base
{
    public function index()
    {
        return $this->fetch();
    }
    
    /**
     * Get Link list
     *
     * @time at 2019年03月12日
     * @return json
     */
    public function getList()
    {
        $links = model('LinkModel')->where('siteid', session('site'))->select();

        return json($this->layuiPaginator($links));
    }
    
    /**
     * Create Link
     *
     * @time at 2019年03月07日
     * @return mixed|string
     */
    public function create(LinkRequest $request)
    {
        if ($request->isPost()) {
            $param = $request->post();
            $param['status'] = switch_on_to_1($param['status']);
            $param['siteid'] = session('site');
            $now_date = date("Y-m-d H:i:s");
            $param['created_at'] = $now_date;
            $param['update_at'] = $now_date;
            $param['delete_at'] = $now_date;
            if (model('LinkModel')->store($param)) {
                $data = ['code'=> 0, 'msg'=>'创建成功', 'data'=> null];
            } else {
                $data = ['code'=> 1, 'msg'=>'创建失败', 'data'=> null];
            }

            return json($data);
        }
    
        return $this->fetch();
    }
    
    /**
     * Edit Link
     *
     * @time at 2019年03月07日
     * @return mixed|string
     */
    public function edit(LinkRequest $request)
    {
        if ($request->isPost()) {
            $param = $request->post();
            $param['status'] = switch_on_to_1($param['status']);
            unset($param['file']);
            $now_date = date("Y-m-d H:i:s");
            $param['update_at'] = $now_date;
            $param['delete_at'] = $now_date;
            if (model('LinkModel')->updateBy($param['id'], $param) !== false) {
                $data = ['code'=> 0, 'msg'=>'编辑成功', 'data'=> null];
            } else {
                $data = ['code'=> 1, 'msg'=>'没有更改', 'data'=> null];
            }
            return json($data);
        }

        if (!$this->request->param('id')) {
            $this->error('不存在的数据');
        }
        
        $this->assign([
            'link'  => model('LinkModel')->get($this->request->param('id'))
        ]);

        return $this->fetch();
    }
    
    public function delete()
    {
        $linkId = $this->request->post('id');
        
        if (!$linkId) {
            $data = ['code'=> 1, 'msg'=>'不存在数据', 'data'=> null];
            return json($data);
        }
        
        if (model('LinkModel')->deleteBy($linkId)) {
            $data = ['code'=> 0, 'msg'=>'删除成功', 'data'=> null];
            return json($data);
        }
        
        $data = ['code'=> 3, 'msg'=>'删除失败', 'data'=> null];
        return json($data);
    }
}
