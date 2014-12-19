<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Localhost's Server</title>
	<script data-main="assets/javascript/main" src="assets/javascript/require.js"></script>
	<?php
		require_once('templates/ui.php');
	?>
</head>
<body>
	<?php
		$page = new $template($needed);
	?>
</body>
</html>
