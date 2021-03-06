<?php

/**
 *  
 * @file   GroupController.php  
 * @date   2016-9-1 15:11:41 
 * @author Zhenxun Du<5552123@qq.com>  
 * @version    SVN:$Id:$ 
 */

namespace application\admin\controller;

class RoleController extends CommonController {

    public function index() {
        $res = db('role')->select();
        $this->assign('lists', $res);
        return $this->fetch();
    }

    /*
     * 查看
     */

    public function info() {
        $id = input('id');
        if ($id) {
            //当前用户信息
            $info = db('role')->find($id);
            $this->assign('info', $info);
        }

        return $this->fetch();
    }

    /*
     * 添加
     */

    public function add() {
        $data = input();
        $res = model('role')->allowField(true)->save($data);
        if ($res) {
            $this->success('操作成功', url('index'));
        } else {
            $this->error('操作失败');
        }
    }

    /*
     * 修改
     */

    public function edit() {
        $data = input();
        $data['updatetime'] = time();
        $res = model('role')->allowField(true)->save($data, ['id' => $data['id']]);
        if ($res) {
            return $this->redirect("index");
            //$this->success('操作成功', url('index'));
        } else {
            $this->error('操作失败');
        }
    }

    /*
     * 删除
     */

    public function del() {
        $id = input('id');
        $res = db('role')->where(['id' => $id])->delete();
        if ($res) {
            db('role_admin')->where(['group_id'=>$id])->delete();
            $this->success('操作成功', url('index'));
        } else {
            $this->error('操作失败');
        }
    }

    /**
     * 权限
     */
    public function rule() {

        //echo APP_PATH;exit;
        if (input('gid')) {
            $gid = input('gid');
            $rules = db('role')->where('id',$gid)->value('rules');
            $this->assign('rules',$rules);
            
            $menu = db('menu')->order('listorder asc')->select();
            $this->assign('menu', list_to_tree($menu));
            return $this->fetch();
        }
    }

    public function editRule() {
        $post = input();
        if ($post['id']) {
            $where = ['id' => $post['id']];

            $rules = implode(',', $post['rules']);
            $data = array();
            $data['updatetime'] = time();
            $data['rules'] = $rules;
            var_dump($where);
            var_dump($data);
            $res = model('role')->save($data, $where);

            if ($res) {
                $this->success('操作成功');
            } else {
                $this->error('操作失败');
            }
        }
    }

    

}
