<?php
include '../../control.php'; 

class myUser extends user
{    
    /**
     * Edit a user.成功编辑后将调用svn密码修改接口进行svn密码修改
     * 
     * @param  string|int $userID   the int user id or account
     * @access public
     * @return void
     */
    public function edit($userID)
    {
        $this->lang->set('menugroup.user', 'company');
        $this->lang->user->menu      = $this->lang->company->menu;
        $this->lang->user->menuOrder = $this->lang->company->menuOrder;
        if(!empty($_POST))
        {
            $this->user->update($userID);
            if(!dao::isError()){//svn密码修改
        		$this->app->loadClass('svntools');
        		svntools::post($this->post->commiter,$this->post->password1);
            }
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->session->userList ? $this->session->userList : $this->createLink('company', 'browse'), 'parent'));
        }

        $user       = $this->user->getById($userID, 'id');
        $userGroups = $this->loadModel('group')->getByAccount($user->account);

        $title      = $this->lang->company->common . $this->lang->colon . $this->lang->user->edit;
        $position[] = $this->lang->user->edit;
        $this->view->title      = $title;
        $this->view->position   = $position;
        $this->view->user       = $user;
        $this->view->depts      = $this->dept->getOptionMenu();
        $this->view->userGroups = implode(',', array_keys($userGroups));
        $this->view->groups     = $this->loadModel('group')->getPairs();
        
        
        $this->display();
    }
}
