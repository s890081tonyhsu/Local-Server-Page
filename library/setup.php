<?php
require_once('library/utils.php')

class Setup{
	private $setting;
	public function __construct(){

	}
	public function readJSON(){}
	public function readParams($params){
			$this->setSystem($params['Server']);
			$this->setPorts($params['ports']);
			$this->setFolder($params['folder']);
			$this->sethideFolder($params['hidefolder']);
			$this->settemplate($params['template']);
			$this->setshow($params['show']);
	}

	private function setSystem($Server){
		$system = array(
			'OS' => PHP_OS,
			'Server' => $Server
		);
		$this->setting['system'] = $system;
	}
	private function setPorts($ports){
		$portsList = array();
		foreach($ports as $port){
			$unit = array($port['name']=>$port['value']);
			array_push($portList, $unit);
		}
		$this->setting['ports'] = $portList;
	}
	private function setFolder($folderList){
		$folders = array();
		foreach($folderList as $folder){
			$unit = array($one['badge']=>$one);
			array_push($folders, $unit);
		}
		$this->setting['folder'] = $folders;
	}
	private function sethideFolder($hidefolder){
		$this->setting['hidefolder'] = $hidefolder;
	}
	private function settemplate($template){
		$this->setting['template'] = $template;
	}
	private function setshow($show){
		$this->setting['show'] = $show;
	}
}
