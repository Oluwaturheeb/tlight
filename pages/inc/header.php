<!DOCTYPE html>
<html>

<head>
	<base href="/">
	<meta charset="utf-8">
	<meta lang="<?php echo Config::get("project/lang"); ?>">
	<?php echo Utils::meta(); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scaleable=no">
		<meta name="theme_color" content="#F2A238">
		<link rel="icon" href="assets/pwa/pwa72.png">
	<link rel="manifest" href="/manifest.json">
	<title><?php echo $title, " @ ", PNAME ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo STYLE . "app.css" ?>">
	<script src="<?php echo jQuery ?>"></script>
	<script src="<?php echo JS, "Validate.js" ?>"></script>
</head>
<body>
	<?php require_once 'defaultHeader.php';