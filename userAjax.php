<?php
	if (isset($_REQUEST['action']) && !empty($_REQUEST['action'])) {
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
		//requperations des valeurs des variable passees en parametres
		$action	=$_REQUEST['action'];
		$user	=$_REQUEST['objet'];
		$result	=NULL;
		//selection de l'action a faire
		switch ($action) {
			case 'enable':
				$result=$adldap->user()->enable($user);
				break;
			case 'disable':
				$result=$adldap->user()->disable($user);
				break;
			case 'unlock':
				$result=$adldap->user()->unlock($user);
				break;
			case 'resetpwd':
				$mustchange=0;
				if($_REQUEST['mustchange'])$mustchange=1;
				$result=$adldap->user()->password($user,$_REQUEST['password'],$mustchange);
				//$result=false;
				break;
			case 'moveTo':
				$objetType	=$_REQUEST['objetType'];
				if($objetType=='user'){
					$result=$adldap->user()->move($user,$_REQUEST['container']);
				}else if($objetType=='group'){
					$result=$adldap->group()->move($user,$_REQUEST['container']);
				}elseif ($objetType=='ou') {
					$result=$adldap->folder()->move($user,$_REQUEST['container']);
				}
				break;
			case 'delete':
				$objetType	=$_REQUEST['objetType'];
				if($objetType=='user'){
					$result=$adldap->user()->delete($user);
				}else if($objetType=='group'){
					$result=$adldap->group()->delete($user);
				}elseif ($objetType=='ou') {
					$result=$adldap->folder()->delete($user);
				}
				break;
			case 'addTo':
				$objetType	=$_REQUEST['objetType'];
				$group 		=$_REQUEST['group'];
				if($objetType=='user'){
					$result=$adldap->group()->addUser($group,$user);
				}else if($objetType=='group'){
					$child=$user;
					$result=$adldap->group()->addGroup($group,$child);
				}
				break;
			case 'removeFrom':
				$objetType	=$_REQUEST['objetType'];
				$group 		=$_REQUEST['group'];
				if($objetType=='user'){
					$result=$adldap->group()->removeUser($group,$user);
				}else if($objetType=='group'){
					$child=$user;
					$result=$adldap->group()->removeGroup($group,$child);
				}
				break;
			default:
				$result=false;
				break;
		}

		if ($result==true) {
			echo "success";
		}else{
			echo "error";
		}
	}

?>
