<?php
include '../../control.php'; 

class myUser extends user
{    
    /**
     * Create a suer.成功添加后将调用svn密码修改接口进行svn密码修改
     * 
     * @param  int    $deptID 
     * @access public
     * @return void
     */
    public function create($deptID = 0)
    {
        $this->lang->set('menugroup.user', 'company');
        $this->lang->user->menu      = $this->lang->company->menu;
        $this->lang->user->menuOrder = $this->lang->company->menuOrder;

        if(!empty($_POST))
        {
            $this->user->create();
            if(!dao::isError()){//svn密码修改
        		$this->app->loadClass('svntools');
        		svntools::post($this->post->commiter,$this->post->password1);
            }
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink('company', 'browse'), 'parent'));
        }
        $groups    = $this->dao->select('id, name, role')->from(TABLE_GROUP)->fetchAll();
        $groupList = array('' => '');
        $roleGroup = array();
        foreach($groups as $group)
        {
            $groupList[$group->id] = $group->name;
            if($group->role) $roleGroup[$group->role] = $group->id;
        }

        $title      = $this->lang->company->common . $this->lang->colon . $this->lang->user->create;
        $position[] = $this->lang->user->create;
        $this->view->title     = $title;
        $this->view->position  = $position;
        $this->view->depts     = $this->dept->getOptionMenu();
        $this->view->groupList = $groupList;
        $this->view->roleGroup = $roleGroup;
        $this->view->deptID    = $deptID;

        $this->display();
    }
}
