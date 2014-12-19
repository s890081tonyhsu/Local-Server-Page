<?php
	abstract class Template{
		private $Template;
		protected $needed;
		abstract protected function setup($needed);
		abstract protected function structPage();

		public function __construct($needed){
			$this->setup($needed);
			$this->structPage();
			$this->output();
		}

		protected function setTemplate($id, $template){
			$this->Template[$id] = $template;
		}

		private function getTemplate($id, $template){
			return '<script id="'.$id.'-template" type="text/ractive">'.$template.'</script>';
		}

		private function output(){
			foreach($this->Template as $id => $template){
				echo $this->getTemplate($id, $template);
			}
		}
	}
