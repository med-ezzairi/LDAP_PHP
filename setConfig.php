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
	<style type="text/css">
		#setconfig{
			width: 100%;
			/*margin: auto auto;*/
			position: absolute;
			top: 5px;
			height: 600px;
		}
		#center{
			width: 500px;
			margin: 0 auto;
			margin-top: 80px;
		}
		#centerbutton{
			margin: 0 auto;
			padding-left: 100px;
		}
		
	</style>
</head>
<body>
	
	<!-- Ecreture de la configuration saisie par l'administrateur-->
	<?php
		if (isset($_REQUEST['Valider']) && !empty($_REQUEST['Valider'])) {
			$message=NULL;
			$setConfig=FALSE;
			//domaine (config.ini)
			$config="[LDAP]\n";
			if (isset($_REQUEST['dc']) && !empty($_REQUEST['dc'])) {
				$dc=$_REQUEST['dc'];
				$config.="domain_controllers=\"".$dc."\"\r\n";
			}
			if (isset($_REQUEST['domain']) && !empty($_REQUEST['domain'])) {
				$domain=$_REQUEST['domain'];
				$config.="domain=\"".$domain."\"\r\n";
			}
			if (isset($_REQUEST['ssl']) && !empty($_REQUEST['ssl'])) {
				$config.="use_ssl=1\n";
			}else{
				$config.="use_ssl=0\n";
			}
			//ecriture dans le fichier config.ini
			$file=fopen("config.ini",'w+');
			if(fwrite($file, $config)=== FALSE){
				$type="error";
				$message="Impossible d'ecrire la configuration dans le fichier";
			}else{
				$setConfig=true;
			}

		}
	?>
	<!--chargement de la configuration en cours s'elle existe-->
	<?php

		if (file_exists("config.ini")) {
			$ini = parse_ini_file("config.ini");
			if(isset($ini["domain_controllers"]))$dc=$ini["domain_controllers"];
			if(isset($ini["domain"])) $domain=$ini["domain"];
			if(isset($ini["use_ssl"])) $ssl=$ini["use_ssl"];
		}else{
			$msgType="error";
			$message="Le fichier de configuration (config.ini) n'exite pas,<br>vous devez saisir la configuration de votre domaine!";
		}
	?>
	<div id="setconfig">
		<div id="center">
			<div class="block">
				<div class="block_head">
					<div class="bheadl"></div>
					<div class="bheadr"></div>
					
					<h2>Configuration</h2>
					<ul>
						<li><a href="/manage.php">Accueil</a></li>
					</ul>
				</div>		<!-- .block_head ends -->

				<div class="block_content">
						<?php 
							if (!empty($message)) {
								echo "<div class=\"message $msgType\"><p>$message</p></div>";
							}
						?>
						<form name="frmUser" action="" method="post">
							<fieldset>
								<legend>Domaine</legend>
								
								<label for="dc">Contrôlleurs de domaine</label><br />
								<input <?php if(isset($dc)&& !empty($dc)) echo('value="'.$dc.'"'); ?> type="text" name="dc" placeholder="Adresse IP ou Nom DNS" class="text small">
								<br /><br />
								<label for="domain">Nom de domaine (FQDN)</label><br />
								<input <?php if(isset($domain)&& !empty($domain)) echo('value="'.$domain.'"'); ?> type="text" name="domain" placeholder="exemple.com" class="text small"><br />
								<input <?php if(isset($ssl)&& !empty($ssl) && $ssl==true) echo('checked="true"'); ?> type="checkbox" name="ssl" class="checkbox">
								<label for="ssl">Utiliser LDAP sur SSL</label><br />
							</fieldset>
							<!--
							<fieldset>
								<legend>Session</legend>
								<label for="cookieName">Nom de cookie</label><br />
								<input type="text" name="cookieName" placeholder="PHPID" class="text small">
								<br />
								<br />
								<label for="cookieLife">Durée de vie de la cookie (seconde)</label><br />
								<input type="text" name="cookieLife" placeholder="180" class="text small"><br />
							</fieldset>
							-->
							<div id="centerbutton">
								<input type="reset" name="reset"  value="Effacer" class="submit long">
								<input type="submit" name="Valider"  value="Valider" class="submit long">
							</div>
						</form>
				</div>		<!-- .block_content ends -->
				
				<div class="bendl"></div>
				<div class="bendr"></div>
			</div>		<!-- .block ends -->
		</div>
	</div>
</body>
</html>