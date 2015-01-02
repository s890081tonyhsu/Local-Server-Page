<?php
	require_once('library/utils.php');
	require_once('library/template.php');	
	$themeList = getThemeList();
	foreach ( $themeList as $theme ){
		require_once('templates/'.$theme.'.php');
	}
	unset($theme);
	
	class ThemeImport{
		private $theme;
		private $setting;
		private $needed;
		
		public function __construct($theme){
			$this->setup($theme);
		}

		private function setup($theme = 'default'){
			$this->setting = getSetting();
			$this->theme = $this->themeExist($theme);
			$this->needed = $this->setting->show;
		}

		private function themeExist($theme){
			if(in_array($theme, $GLOBALS['themeList'])){
				return $theme;
			}else{
				return $this->setting->template;
			}
		}

		public function headRequire(){
			$template = $this->theme;
			if($template !== 'none'){
				echo '<link rel="stylesheet" href="assets/css/framework/'.$template.'.css">';
				echo '<link rel="stylesheet" href="assets/css/custom/'.$template.'.css">';
				echo '
					<script>
						require([\'jquery.min\', \'jquery.min!assets/javascript/library/'.$template.'.js\', \'assets/javascript/custom/'.$template.'.js\'], function(){
							jQuery.noConflict();
						});
					</script>
						 ';
			}
		}

		public function contentRequire(){
			$template = $this->theme;
			if($template !== 'none'){
				$page = new $template($this->needed);
			}
		}
	}
