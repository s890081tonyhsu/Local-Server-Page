<?php
	require_once('markdown/markdown.php');
	use \Michelf\Markdown;
	
	function getSetting(){
			$setting = json_decode(file_get_contents('./settings.json'));	
			//if(!$setting){
			//	header("Refresh: 0; url=install.php");
			//}
			return $setting;
	}

	class ServiceTest{
		private $ResultList;

		public function __construct($portList){
			$list = array();
			foreach($portList as $portName => $portNum){
				array_push($list, $this->RunTest($portName, $portNum));
			}
			$this->ResultList = $list;
		}

		public function getResult(){
			return $this->ResultList;
		}

		private function RunTest($name, $port){
			$socket = @fsockopen("localhost",$port, $errorno, $errorstr, 30);
			if($socket){
				$status = 'success';
			}else{
				$status = 'fail';
			}
			$result = array(
				'name' => $name,
				'port' => $port,
				'status' => $status
			);
			return $result;
		}
	}

	class ProjectList{
		private $List;
		private $Projects;
		private $Others;

		public function __construct($detail, $hide = null){
			$dir = new DirectoryIterator($detail->path);
			$this->List = $this->fillArrayWithFileNodes($dir);
			if(!is_null($hide))$this->hidefolder($hide);
			$this->sortList($detail->path);
		}

		public function getList($pointer = 'list'){
			switch($pointer){
				case 'list':
					return $this->List;
				case 'projects':
					return $this->Projects;
				case 'others':
					return $this->Others;
			}
		}

		private function fillArrayWithFileNodes( DirectoryIterator $dir){
			$data = array();
			foreach ( $dir as $node ){
				if ( $node->isDir() && !$node->isDot() ){
					$data[] = $node->getFilename();
				}
			}
			ksort($data);
			return $data;
		}

		private function sortList($path){
			$projects = array();
			$others = array();
			foreach($this->List as $item){
				if(file_exists($path.$item.'/.git')){
					array_push($projects, $item);
				}else{
					array_push($others, $item);
				}
			}
			$this->Projects = array_values($projects);
			$this->Others = array_values($others);
		}	

		private function hidefolder($hide){
			foreach($hide as $folder){
				$ptr = array_search($folder,$this->List); 
				if($ptr)unset( $this->List[$ptr]);
			}
			$this->List = array_values($this->List);
		}
	}

	class ProjectDetail{
		private $Project;

		public function __construct($name, $detail){
			$this->setProjectDetail($name, $detail);
		}

		public function getData(){
			return $this->Project;
		}
		
		private function setProjectDetail($name, $detail){
			$this->Project['name'] = $name;
			$this->Project['type'] = $detail->name;
			$Commit = preg_split('/\s+/',$this->getLastCommit($detail->path.$name.'/.git/logs/refs/heads/master'), 7);
			$this->Project['commit'] = array(
				'hash' => $Commit[1],
				'commiter' => $Commit[2],
				'timestamp' => $Commit[4],
				'type' => explode(':', $Commit[6])[0],
				'content' => $Commit[6]
			);
			$this->Project['markdown'] = $this->MarkdownToHTML($detail->path.$name);
			$this->Project['structure'] = $this->getDirectory($detail->path.$name);
			$this->Project['demo'] = preg_replace('/\{name\}/', $name, $detail->root);
		}

		private function getLastCommit($path){
			$line = '';
			$f = fopen($path, 'r');
			$cursor = -1;
			fseek($f, $cursor, SEEK_END);
			$char = fgetc($f);
			/*
			 * Trim trailing newline chars of the file
			 */
			while ($char === "\n" || $char === "\r") {
				fseek($f, $cursor--, SEEK_END);
				$char = fgetc($f);
			}
			/*
			 * Read until the start of file or first newline char
			 */
			while ($char !== false && $char !== "\n" && $char !== "\r") {
					/*
					 * Prepend the new char
					 */
				$line = $char . $line;
				fseek($f, $cursor--, SEEK_END);
				$char = fgetc($f);
			}
			return $line;
		}

		private function MarkdownToHTML($path){
			$readme_file = ["readme.md", "Readme.md", "README.md"];
			foreach($readme_file as $readme){
				if (file_exists($path.'/'.$readme)){
					$markdown = file_get_contents($path.'/'.$readme, true);
					$html = Markdown::defaultTransform($markdown);
					return $html;
				}
			}
		}

		private function getDirectory( $path = '.'){
			$structure = array(
				'directory' => [],
				'file' => []
			);
			$ignore = array( 'cgi-bin', '.', '..' );
			// Directories to ignore when listing output. Many hosts
			// will deny PHP access to the cgi-bin.
			$dh = @opendir( $path );
			// Open the directory to the handle $dh
			while( false !== ( $file = readdir( $dh ) ) ){
				// Loop through the directory
				if( !in_array( $file, $ignore ) ){
					if( is_dir( "$path/$file" ) ){
						// Its a directory, put it into structure['directory']
						array_push($structure['directory'], '$file');
					} else {
						// Put it into structure['file']
						array_push($structure['file'], '$file');
					}
				}
			}
			closedir( $dh );
			// Close the directory handle
		} 
	}

	class LinkDetail{
		private $_link;

		public function __construct($name, $detail){
			$this->setLinkDetail($name, $detail);
		}

		public function getData(){
			return $this->_link;
		}

		private function setLinkDetail($name, $detail){
			$this->_link['name'] = $name;
			$this->_link['path'] = $detail->path.$name;
			$this->_link['href'] = preg_replace('/\{name\}/', $name, $detail->root);
			$this->_link['root'] = $detail->root;
		}
	}
	

