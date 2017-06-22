<!DOCTYPE html>
<html>
<head>
	<meta charset="utf8">
	<title>C-Evaluator</title>
	<link rel="stylesheet" href="<?php echo __SITE_URL;?>/css/style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
	<script src="<?php echo __SITE_URL;?>/view/script.js"></script>
</head>
<body>

<div class="topandbottom" id="top">
		<h1>C-Evaluator</h1>
		<br>
		<nav>

			<a class="nav" href="<?php echo __SITE_URL; ?>/index.php">Home</a>

			<!--Ako je Admin ulogiran onda ispisi "Logout" i "Novi Zadatak" inače ispisi  "Login"-->
			<?php if(isset($_SESSION['user'])) {  ?>
			| <a class="nav" href="<?php echo __SITE_URL; ?>/index.php?rt=admin/postaviZadatak">Novi Zadatak</a>
			| <a class="nav" href="<?php echo __SITE_URL; ?>/index.php?rt=admin/logout">Log out</a>

			<?php echo " ( ".$_SESSION['user']['username']." ) "; }

			else { if(explode('/', $_GET['rt'])[0] !== "admin"){ ?>
							| <a class="nav" href="<?php echo __SITE_URL; ?>/index.php?rt=admin">Log in</a>

			<?php }} ?>

		</nav>
</div>
