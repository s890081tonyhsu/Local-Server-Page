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

	function getThemeList(){
		$themeFolder = new DirectoryIterator('templates');
		$themeList = array();
		foreach ( $themeFolder as $node ){
			if ( !$node->isDot() && $node->getExtension() == 'php'){
				$theme =  mb_convert_encoding($node->getBasename('.php'), "UTF-8", "BIG5");
				$themeList[] = $theme;
			}
		}
		unset($theme);
		return $themeList;
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
					$data[] = mb_convert_encoding($node->getFilename(), "UTF-8", "BIG5");
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
			$this->Project['badge'] = $detail->badge;
			$this->Project['commit'] = $this->getLastCommit($detail->path.$name);
			$this->Project['markdown'] = array(
				'show' => false,
				'html' => $this->MarkdownToHTML($detail->path.$name)
			);
			$this->Project['structure'] = $this->getDirectory($detail->path.$name);
			$this->Project['demo'] = preg_replace('/\{name\}/', $name, $detail->root);
		}

		private function getLastCommit($path){
			$output = array();
			chdir($path);
			exec("git log -1",$output);
			$commit = array();
			$commit['message'] = '';
			foreach ($output as $line){
    		// Clean Line
    		$line = trim($line);

				// Proceed If There Are Any Lines
    		if (!empty($line)){
        	if (strpos($line, 'commit') !== false){
            // Commit
						$hash = explode(' ', $line);
            $hash = trim(end($hash));
            $commit['hash'] = $hash;
					}else if (strpos($line, 'Author') !== false) {
						// Author
            $author = explode(':', $line);
            $author = trim(end($author));
            $commit['author'] = $author;
					}else if (strpos($line, 'Date') !== false) {
    	    	// Date
            $date = explode(':', $line, 2);
            $date = trim(end($date));
            $commit['timestamp'] = strtotime($date);
					}else {
						// Message
            $commit['message'].= $line ."<br>";
        	}
    		}
			}
			return $commit;
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
				'file' => [],
				'show' => false
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
						array_push($structure['directory'], mb_convert_encoding($file, "UTF-8", "BIG5"));
					} else {
						// Put it into structure['file']
						array_push($structure['file'], mb_convert_encoding($file, "UTF-8", "BIG5"));
					}
				}
			}
			closedir( $dh );
			// Close the directory handle
			return $structure;
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
	

