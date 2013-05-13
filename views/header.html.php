<html lang="en">
	<head>
		<meta name="description" content="ezRPG Project, the free, open source browser-based game engine!" />
		<meta name="keywords" content="" />
		
		<link type="text/css" rel="stylesheet" href="<?php echo $this->app->getRootPath() ?>views/css/style.css">
		<script src="<?php echo $this->app->getRootPath() ?>views/scripts/ext/jquery/jquery.1.8.1.min.js"></script>
		<script src="<?php echo $this->app->getRootPath() ?>views/scripts/ext/jquery/plugins/run.js"></script>
		<script src="<?php echo $this->app->getRootPath() ?>views/scripts/security.js"></script>
		<title><?php echo $this->htmlEncode($this->app->getConfig('siteName')) . ' - ' . $this->get('pageTitle') ?></title>

	</head>
	<body>
	<body>

		<div id="wrapper">

			<div id="header">
				<span id="title"><a href="./">ezRPG <span>rework</span></a></span>
				<span id="time">Null:Null:Null
					<br />
					<strong>Players Online</strong>: 1SetMe</span>
			</div>

			<div id="nav">
				<ul>
					<!-- Put Check for Logged In HERE -->
						<li><a href="index.php">Home</a></li>
						<li><a href="Register">Register</a></li>
				</ul>
			</div>
			<div id="body_wrap">
				
				<div id="body">
