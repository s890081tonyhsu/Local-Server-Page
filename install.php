<?php
	require_once('library/utils.php');
	try{
		echo "<pre>";
		var_dump($_POST);
		echo "</pre>";
		$system = array('OS'=>PHP_OS, 'Server'=>$_POST['Server']);
		$ports = array();
		foreach($_POST['ports'] as $port){
			$unit = array($port['name']=>$port['value']);
			array_push($ports, $unit);
		}
		unset($unit);
		$folders = array();
		foreach($_POST['folder'] as $folder){
			$unit = array($folder['badge']=>$folder);
			array_push($folders, $unit);
		}
		unset($unit);
		$hidefolder = $_POST['hidefolder'];
		$template = $_POST['template'];
		$show = $_POST['show'];
		$setting = array(
			'system' => $system,
			'ports' => $ports,
			'folder' => $folders,
			'hidefolder' => $hidefolder,
			'template' => $template,
			'show' => $show
		);
		echo '<pre>';
		var_dump(json_encode($setting));
		echo '</pre>';
	}catch (Exception $e){
		echo 'Error!!!';
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Install Page</title>
	<script src="assets/javascript/ractive/ractive.min.js" type="text/javascript"></script>
	<script src="assets/javascript/ractive/ractive-events-tap.min.js" type="text/javascript"></script>
</head>
<body>
	<form action="install.php" method="post">
		<div id="system">
			<h2>System</h2>
			<h3>Your system is <?php echo PHP_OS; ?></h3>
			<label for="Server">Server Name:</label>
			<input id="Server" type="text" name="Server">
		</div>
		<hr>
		<div id="portList">
			<h2>Ports</h2>
			<div id="portListContainer"></div>
		</div>
		<script id="portListTemplate" type="text/ractive">
			{{#each ports:i}}
				{{>port}}
			{{/each}}
			<a href="#portList" on-tap="addPort">Add</a>
			<!--{{>port}}-->
				<label for="ports_{{id}}n">Port Name:</label>
				<input id="ports_{{id}}n" type="text" name="ports[{{id}}][name]">
				<label for="ports_{{id}}v">Port Number:</label>
				<input id="ports_{{id}}v" type="text" name="ports[{{id}}][value]">
				<a href="#portList" on-tap="deletePort:{{id}}">Delete</a>
				<br/>
			<!--{{/port}}-->
		</script>
		<script>
			var portList = new Ractive({
				el: '#portListContainer',
				template: '#portListTemplate',
				data: {
					'ports': [],
					'amount': 0
				}
			});
			portList.on({
				'addPort': function(){
					var id = this.get('amount');
					this.push('ports', {'id': id});
					this.add('amount');
				},
				'deletePort': function(event, id){
					this.splice('ports', id, 1);
				}
			}); 
		</script>
		<hr>
		<div id="folderList">
			<h2>Project Folder</h2>
			<div id="folderListContainer"></div>
			<script id="folderListTemplate" type="text/ractive">
				{{#each folders}}
					{{>folder}}
				{{/each}}
				<a href="#folderList" on-tap="addFolder">Add</a>
				<!--{{>folder}}-->
					<label for="folder_{{id}}n">Folder Name:</label>
					<input id="folder_{{id}}n" type="text" name="folder[{{id}}][name]">
					<br>
					<label for="folder_{{id}}p">Folder Path:</label>
					<input id="folder_{{id}}p" type="text" name="folder[{{id}}][path]">
					<br>
					<label for="folder_{{id}}r">Root URL:(use "{name}" to tell the name of project in url)</label>
					<input id="folder_{{id}}r" type="text" name="folder[{{id}}][root]">
					<br>
					<label for="folder_{{id}}b">Folder Language type:</label>
					<input id="folder_{{id}}b" type="text" name="folder[{{id}}][badge]">
					<br>
					<a href="#folderList" on-tap="deleteFolder:{{id}}">Delete</a>
					<br>
				<!--{{/folder}}-->
			</script>
			<script>
				var folderList = new Ractive({
					el: '#folderListContainer',
					template: '#folderListTemplate',
					data: {
						'folders': [],
						'amount': 0
					}
				});
				folderList.on({
					'addFolder': function(){
						var id = this.get('amount');
						this.push('folders', {'id': id});
						this.add('amount');
					},
					'deleteFolder': function(event, id){
						this.splice('folders', id, 1);
					}
				}); 
			</script>
		</div>
		<hr>
		<div id="hidefolderList">
			<h2>Hide Folder</h2>
			<div id="hidefolderListContainer"></div>
			<script id="hidefolderListTemplate" type="text/ractive">
				{{#each hidefolders}}
					{{>hidefolder}}
				{{/each}}
				<a href="#hidefolderList" on-tap="addhideFolder">Add</a>
				<!--{{>hidefolder}}-->
					<label for="hidefolder_{{id}}n">HideFolder Name:</label>
					<input id="hidefolder_{{id}}n" type="text" name="hidefolder[{{id}}]">
					<a href="#hidefolderList" on-tap="deletehideFolder:{{id}}">Delete</a>
					<br>
				<!--{{/hidefolder}}-->
			</script>
			<script>
				var hidefolderList = new Ractive({
					el: '#hidefolderListContainer',
					template: '#hidefolderListTemplate',
					data: {
						'hidefolders': [],
						'amount': 0
					}
				});
				hidefolderList.on({
					'addhideFolder': function(){
						var id = this.get('amount');
						this.push('hidefolders', {'id': id});
						this.add('amount');
					},
					'deletehideFolder': function(event, id){
						this.splice('hidefolders', id, 1);
					}
				}); 
			</script>
		</div>
		<hr>
		<div id="templateList">
			<h2>Template List</h2>
			<label for="template">Choose Theme: </label>
			<select id="template" name="template">
				<?php
					$themeList = getThemeList();
					foreach($themeList as $theme){
						echo '<option value="'.$theme.'">'.$theme.'</option>';
					}
				?>
			</select>
		</div>
		<hr>
		<div id="showList">
			<h2>Show List</h2>
			<input id="showNavbar" type="checkbox" name="show" value="Navbar">
			<label for="showNavbar">Navbar</label>
			<input id="showServer" type="checkbox" name="show" value="Server">
			<label for="showServer">Server</label>
			<input id="showList" type="checkbox" name="show" value="List">
			<label for="showList">List</label>
		</div>
		<div id="submit">
			<input type="submit" value="Generate json">
			<input type="reset" value="Reset ALL Data">
		</div>
	</form>
</body>
</html>
