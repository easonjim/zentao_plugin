<?php
include '../../control.php'; 

class myUser extends user
{    
    /**
     * Batch create users.成功添加后将调用svn密码修改接口进行svn密码修改
     * 
     * @param  int    $deptID 
     * @access public
     * @return void
     */
    public function batchCreate($deptID = 0)
    {
        $groups    = $this->dao->select('id, name, role')->from(TABLE_GROUP)->fetchAll();
        $groupList = array('' => '');
        $roleGroup = array();
        foreach($groups as $group)
        {
            $groupList[$group->id] = $group->name;
            if($group->role) $roleGroup[$group->role] = $group->id;
        }

        $this->lang->set('menugroup.user', 'company');
        $this->lang->user->menu      = $this->lang->company->menu;
        $this->lang->user->menuOrder = $this->lang->company->menuOrder;

        if(!empty($_POST))
        {
            $this->user->batchCreate();
            
            //添加svn账号
            $users    = fixer::input('post')->get(); 
	        $data     = array();
	        $accounts = array();
	        for($i = 0; $i < $this->config->user->batchCreate; $i++)
	        {
	        	
	            if($users->account[$i] != '')  
	            {
	                $u  = $users->account[$i];
	                $p  = $users->password[$i]; 
	                if(empty($p))$p = $users->password[$i-1]; 
	                //修改数据的SVN账号
	                $user['commiter'] = $users->account[$i];
	                $this->dao->update(TABLE_USER)->data($user)->autoCheck()->where('account')->eq($users->account[$i])->exec();
	                $this->app->loadClass('svntools');
        			svntools::post($u,$p);
	            }
	        }
            
            die(js::locate($this->createLink('company', 'browse'), 'parent'));
        }

        $title      = $this->lang->company->common . $this->lang->colon . $this->lang->user->batchCreate;
        $position[] = $this->lang->user->batchCreate;
        $this->view->title     = $title;
        $this->view->position  = $position;
        $this->view->depts     = $this->dept->getOptionMenu();
        $this->view->deptID    = $deptID;
        $this->view->groupList = $groupList;
        $this->view->roleGroup = $roleGroup;

        $this->display();
    }
}
