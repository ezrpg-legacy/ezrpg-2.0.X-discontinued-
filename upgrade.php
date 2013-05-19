<?php
//Proof of concept upgrade script for the password conversion process.
if(!isset($_POST['submit'])){
?>
<html>
	<head>
		<title>ezRPG upgrade from 1.x</title>
	</head>
	<body>
		<form method="post">
			<strong>Path to ezRPG 1.X config.php file</strong> <input type="text" name="ezconfig" value="../ezrpg/config.php" /><br />
			<input type="submit" name="submit" value="submit" />
		</form>
	</body>
</html>
<?php
}else{
	define("IN_EZRPG", 1);
	require_once($_POST['ezconfig']); //I know this is all kinds of insecure, but it's just for testing, so I really don't care right now.
	$config_prefix = DB_PREFIX;
	$secret_key = SECRET_KEY;
	$write = <<<OUT
<?php

\$app->setConfig('siteName', 'ezRPG');

\$app->setConfig('db', array(
	'driver'   => '{$config_driver}',
	'host'     => '{$config_server}',
	'database' => '{$config_dbname}',
	'username' => '{$config_username}',
	'password' => '{$config_password}',
	'port' => '3306',
	'prefix' => '{$config_prefix}'
	));

\$app->setConfig('siteURL', 'http://localhost/');
\$app->setConfig('legacy_secret', '{$secret_key}'); // this is only needed if ezRPG was upgraded from version 1.X
?>
OUT;
	$fh = fopen("./config.php", "w+");
	fwrite($fh, $write);
	fclose($fh);
	// add the 'oldpass' field to players and clear the password field.
	$conn = mysql_connect($config_server, $config_username, $config_password);
	mysql_select_db($config_dbname);
	mysql_query("ALTER TABLE  `{$config_prefix}players` ADD  `oldpass` VARCHAR( 255 ) NOT NULL AFTER  `password`") or die(mysql_error());
	$query = mysql_query("select * from `{$config_prefix}players`");
	while($p = mysql_fetch_assoc($query)){
		mysql_query("UPDATE `{$config_prefix}players` SET `oldpass`='".$p['password']."', `password`=''");
	}
	echo "done";
}