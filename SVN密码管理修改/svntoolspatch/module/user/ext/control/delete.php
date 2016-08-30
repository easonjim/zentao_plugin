<?php
include '../../control.php'; 

class myUser extends user
{    
    /**
     * Delete a user.�ɹ�ɾ���󽫵���svn�����޸Ľӿڽ���svn�˺�ɾ��
     * 
     * @param  int    $userID 
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function delete($userID)
    {
        $user = $this->user->getByID($userID, 'id');
        if(strpos($this->app->company->admins, ",{$this->app->user->account},") !== false and $this->app->user->account == $user->account) return;
        if($_POST)
        {
            if(md5($this->post->verifyPassword) != $this->app->user->password) die(js::alert($this->lang->user->error->verifyPassword));
            $this->user->delete(TABLE_USER, $userID);
            if(!dao::isError())
            {
            	//ɾ��svn�˺�
            	$this->app->loadClass('svntools');
        		$u = $this->user->getById($userID, 'id');
        		svntools::post($u->commiter,'1','d');
                $this->loadModel('mail');
                if($this->config->mail->mta == 'sendcloud' and !empty($user->email)) $this->mail->syncSendCloud('delete', $user->email);
            }

            /* if ajax request, send result. */
            if($this->server->ajax)
            {
                if(dao::isError())
                {
                    $response['result']  = 'fail';
                    $response['message'] = dao::getError();
                }
                else
                {
                    $response['result']  = 'success';
                    $response['message'] = '';
                }
                $this->send($response);
            }
            die(js::locate($this->session->userList, 'parent.parent'));
        }

        $this->display();
    }
}
