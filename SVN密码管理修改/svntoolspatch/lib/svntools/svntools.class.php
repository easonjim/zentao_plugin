<?php

class svntools
{
	public static $svnuserapi = 'http://localhost/svntoolsapi/userapi.php';//apiµØÖ·
		
	public static function post($u,$p,$a='e')
	{
		if(!empty($u) && !empty($p))
		{
			$data['u'] = $u;
			$data['p'] = $p;
			$url = svntools::$svnuserapi .'?a='.$a .'&r=' .mt_rand(10000, 99999);
	    	$postdata = http_build_query(
	            $data
	        );
	        $opts = array('http' =>
	                      array(
	                          'method'  => 'POST',
	                          'header'  => 'Content-type: application/x-www-form-urlencoded',
	                          'content' => $postdata
	                      )
	        );
	        $context = stream_context_create($opts);
	        $result = file_get_contents($url, false, $context);
        }
	}
}