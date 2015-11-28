<?php //error_reporting(0); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="icon" href="favicon.png" sizes="16x16" type="image/png">
	
	<title>Administration Active Directory v0.1</title>
    <style type="text/css" media="all">
		@import url("css/style.css");
		@import url("css/dragDrop.css");
		@import url("css/jquery.mCustomScrollbar.css");
		@import url("css/visualize.css");
		@import url("css/date_input.css");
		@import url("css/dtree.css");
		@import url("css/conteneur.css");
    </style>
	
	<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=7" /><![endif]-->
	<!--[if lt IE 8]><style type="text/css" media="all">@import url("css/ie.css");</style><![endif]-->

	<!--[if IE]><script type="text/javascript" src="js/excanvas.js"></script><![endif]-->
	
	<script type="text/javascript" src="js/jquery-1.6.1.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.7.2.custom.min.js"></script>
	<script type="text/javascript" src="js/jquery.img.preload.js"></script>
	<script type="text/javascript" src="js/jquery.filestyle.mini.js"></script>
	<script type="text/javascript" src="js/jquery.wysiwyg.js"></script>
	<script type="text/javascript" src="js/jquery.date_input.pack.js"></script>
	<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
	<script type="text/javascript" src="js/jquery.paging.js"></script>
	<script type="text/javascript" src="js/jquery.mCustomScrollbar.concat.min.js"></script>

	<script type="text/javascript" src="js/jquery.visualize.js"></script>
	<script type="text/javascript" src="js/jquery.select_skin.js"></script>
	<script type="text/javascript" src="js/ajaxupload.js"></script>
	<script type="text/javascript" src="js/jquery.pngfix.js"></script>
	<script type="text/javascript" src="js/custom.js"></script>
	<script type="text/javascript" src="js/dtree.js"></script>
	<script type="text/javascript" src="js/ldap_php.js"></script>
</head>
<body>
	<?php
		session_start();
		if(!isset($_SESSION['userinfo']) || !isset($_COOKIE['PHPSESSID'])){
			echo"<script>document.location='index.html'</script>";
		}else{
			$userRDN=$_SESSION['userinfo'][0]["samaccountname"][0];
		}
		//initialisation et instantiation de la classe adLDAP
		include (dirname(__FILE__) . "./src/adLDAP.php");
		//initialisation et instantiation de la classe config
		include (dirname(__FILE__) . "./getConfig.php");

		$ini_array["admin_username"]=$userRDN;
		$ini_array["admin_password"]=$_SESSION["userinfo"]["ldapPass"];

		try {
			$adldap = new adLDAP($ini_array);
		}
		catch (adLDAPException $e) {
			//die("Erreur fatal: ".$e);
			echo"<script>document.location='index.html?q=logout&error=unable'</script>";
			exit();
		}
	?>
	<div id="hld">
		<div class="wrapper">		<!-- wrapper begins -->
			<div id="header">
				<div class="hdrl"></div>

				<div class="hdrr"></div>
				
				<h1><a href="/manage.php">Gestion AD</a></h1>
				
				<ul id="nav">
					<li <?php if (isset($_REQUEST['do']) && $_REQUEST['do']=='create') echo'class="active"'; ?>>
						<a href="#">Ajouter</a>
						<ul>
							<li><a href="?cat=user&do=create">Utilisateur</a></li>
							<li><a href="?cat=group&do=create">Groupe</a></li>
							<li><a href="?cat=ou&do=create">Unité</a></li>
						</ul>
					</li>
					<li <?php if (isset($_REQUEST['do']) && $_REQUEST['do']=='list') echo'class="active"'; ?>>
						<a href="#">Lister</a>
						<ul>
							<li><a href="?cat=user&do=list">Utilisateurs</a></li>
							<li><a href="?cat=group&do=list">Groupes</a></li>
							<li><a href="?cat=ou&do=list">Unités</a></li>
						</ul>
					</li>
					<li <?php if (isset($_REQUEST['cat']) && $_REQUEST['cat']=='account') echo'class="active"'; ?>>
						<a href="#">Rapport</a>
						<ul>
							<li><a href="?cat=account&do=disabled">Comptes désactivés</a></li>
							<li><a href="?cat=account&do=expired">Comptes exiprés</a></li>
							<li><a href="?cat=account&do=unused">Comptes Unitilisés</a></li>
						</ul>
					</li>
					<li>
						<a href="/setConfig.php">Configuration</a>
					</li>
				</ul>
				
				<p class="user">Bienvenu 
					<a href="/manage.php?cat=user&do=current">
					<?php if (!empty($userRDN)) echo($userRDN); ?>
					</a> | <a href="index.html?q=logout">Logout</a></p>

			</div>		<!-- #header ends -->

			<?php
				$fileName="";
				if (isset($_REQUEST['cat'])) {
					$objetCategory	=$_REQUEST['cat'];
				}
				if(isset($_REQUEST['do'])){
					$objetAction	=$_REQUEST['do'];
				}
				if (isset($objetCategory) && isset($objetAction)) {
						$fileName	=$objetCategory."_".$objetAction.".php";
				}else{
					$fileName	="home.php";
				}
				include file_exists($fileName) ? $fileName : "home.php";
			?>
			<div id="footer">
				<p class="left"><a href="#">ldap.net</a></p>
				<p class="right">Apropos de <a href="about.html">ldapAdmin</a> v0.1</p>
			</div>
		</div>			<!-- wrapper ends -->
		
	</div>				<!-- #hld ends -->
</body>
</html>