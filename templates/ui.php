<?php
	require_once('./library/utils.php');
	$setting = getSetting();
	$template = $setting->template;
	$needed = $setting->show;
	require_once($template.'.php');
	echo '<link rel="stylesheet" href="assets/css/framework/'.$template.'.css">';
	echo '<link rel="stylesheet" href="assets/css/custom/'.$template.'.css">';
	echo '<script>
					require([\'jquery.min\', \'assets/javascript/library/'.$template.'.js\', \'assets/javascript/custom/'.$template.'.js\'], function(){
						jQuery.noConflict();
					});
				</script>';
