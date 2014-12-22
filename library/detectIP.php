<?php
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		//Use windows command to detect server available IP
		$ip_shell = shell_exec("ipconfig  | findstr \"IPv4\"");
	}else{
		$ip_shell = shell_exec("ifconfig | grep 'inet '| cut -d: -f2 | awk '{ print $1}'");
	}
	preg_match_all( '/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $ip_shell, $ip_list );
	foreach($ip_list[0] as $ip){
		echo '<h4>'.$ip.'</h4>';
	}