<?php
include '../../control.php'; 

class myMy extends control
{
	/**
     * Change password 成功修改后将调用svn密码修改接口进行svn密码修改
     * 
     * @access public
     * @return void
     */
    public function changePassword()
    {
    	$this->loadModel('user');
        if($this->app->user->account == 'guest') die(js::alert('guest') . js::locate('back'));
        if(!empty($_POST))
        {
            $this->user->updatePassword($this->app->user->id);
            if(!dao::isError()){//svn密码修改
        		$this->app->loadClass('svntools');
        		$u = $this->user->getById($this->app->user->id, 'id');
        		svntools::post($u->commiter,$this->post->password1);
            }
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink('my', 'profile'), 'parent'));
        }

        $this->view->title      = $this->lang->my->common . $this->lang->colon . $this->lang->my->changePassword;
        $this->view->position[] = $this->lang->my->changePassword;
        $this->view->user       = $this->user->getById($this->app->user->account);

        $this->display();
    }
}