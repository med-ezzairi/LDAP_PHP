
<?php
	$error="";
	if (!file_exists("config.ini")) {
		$error="fatal";
	}else{
		$ini_array = parse_ini_file("config.ini");
		if(!isset($ini_array["domain_controllers"])){
			$error="error";
		}else{
			$ini_array["domain_controllers"]=explode("*", $ini_array["domain_controllers"]);
		}
		if(!isset($ini_array["domain"])) $error="error";
		if(!isset($ini_array["use_ssl"])){
			$ini_array["use_ssl"]=false;
		}
	}
	if (!empty($error)) {
		echo"<script>document.location='setConfig.php'</script>";
	}
	$baseDN=explode('.', $ini_array["domain"]);
	$ini_array["base_dn"]=implode(",DC=", $baseDN);
	$ini_array["base_dn"]='DC='.$ini_array["base_dn"];
	$ini_array["account_suffix"]='@'.$ini_array["domain"];

?>
