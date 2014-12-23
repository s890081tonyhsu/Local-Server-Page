<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Localhost's Server</title>
	<script data-main="assets/javascript/main" src="assets/javascript/require.js"></script>
	<?php
		require_once('library/ui.php');
		$themeName = isset($_GET['theme']) ? $_GET['theme'] : '';
		$theme = new ThemeImport($themeName);
		$theme->headRequire();
	?>
</head>
<body>
	<?php
		$theme->contentRequire();
	?>
</body>
</html>
