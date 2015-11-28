<?php
if (isset($_REQUEST['dn']) && !empty($_REQUEST['dn'])) {
	//initialisation et instantiation de la classe adLDAP
	include (dirname(__FILE__) . "./src/adLDAP.php");
	//chargement de la configuration de connexion
	include (dirname(__FILE__) . "./getConfig.php");
	session_start();
	$ini_array["admin_username"]=$_SESSION['userinfo'][0]["samaccountname"][0];
	$ini_array["admin_password"]=$_SESSION["userinfo"]["ldapPass"];

	try {
		$adldap = new adLDAP($ini_array);
	}catch (adLDAPException $e) {
		echo "Erreur fatale";
		die();
	}
	//echo($_REQUEST['dn']);
	//die();
	//connexion au serveur ldap
	$ldapBase	=$adldap->getBaseDn();
	$dn=$_REQUEST['dn'];
	if(strpos($dn, '*')) return 'error';
	$filter="(&(objectclass=Organizationalunit)(distinguishedname=".$dn."))";
	$sr = ldap_search($adldap->getLdapConnection(), $adldap->getBaseDn(), $filter);
	$info = ldap_get_entries($adldap->getLdapConnection(), $sr);
	$info=$info[0];
	
	$description= (array_key_exists('description', $info) ? $info["description"][0]: "Aucune");
?>
<h2>Détails de: <u><?php echo $info["name"][0]; ?></u></h2>
<ul>
	<h3><u>Nom de l'Unité:</u></h3>
		<li><?php echo $info["name"][0]; ?></li>
	<h3><u>Déscription:</u></h3>
		<li><?php echo $description; ?></li>
	<h3><u>Nom distinctif:</u></h3>
		<li><?php echo $info["distinguishedname"][0]; ?></li>
	<h3><u>Lien GPO:</u></h3>
	<?php
		if(array_key_exists('gplink', $info)){
			for ($i=0; $i < $info['gplink']['count']; $i++) {
				 if($info["gplink"][$i]==" "){
				 	echo "<li>GPO par Héritage</li>";
				 }else{
				 	echo "<li>".$info["gplink"][$i]."</li>";
				 }
			}
		}else{
			echo "<li>Acune GPO ou GPO par Héritage depuis parent</li>";
		}
	?>
</ul>
<hr>
<input type="hidden" name="ou" id="objetCN" value="<?php echo $info["distinguishedname"][0]; ?>">
<?php } ?>