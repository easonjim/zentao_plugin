<?php
$passwdfile="C:/Repositories/htpasswd";//svn���������ļ�
$u = (isset($_POST["u"]) ? $_POST["u"] : "");//�û���
$a = (isset($_REQUEST["a"]) ? $_REQUEST["a"] : "");//action
$postPassword = (isset($_POST["p"]) ? $_POST["p"] : "");//����
$command='"C:/phpStudy2013/Apache/bin/htpasswd.exe" -b '.$passwdfile." ".$u." ".$postPassword;//ִ���޸������Ӻ��޸Ķ����������
$command1='"C:/phpStudy2013/Apache/bin/htpasswd.exe" -D '.$passwdfile." ".$u;//ִ���޸������Ӻ��޸Ķ����������
if($a=="e")echo system($command);
else if($a=="d")echo system($command1);
?>