<div class="block">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>
		
		<h2>Ajouter Utilisateur</h2>
		<ul>
			<li><a href="?cat=group&do=create">Ajouter groupe</a></li>
			<li><a href="?cat=ou&do=create">Ajouter Unité</a></li>
		</ul>
	</div>		<!-- .block_head ends -->

	<div class="block_content">
		<div style="height:550px;">
			<?php 
				if(isset($_REQUEST['btnValider']) && $_REQUEST['btnValider']=="Valider"){
					$enabled	=0;
					$mustchange	=0;
					$password	="";
					if(isset($_POST['active'])) $enabled=1;
					if(isset($_POST['password'])) $password=$_POST['password'];
					if(isset($_POST['mustchange'])) $mustchange=1;
					$attributes=array(
							"username"	=>$_POST['nom'],
							"logon_name"=>$_POST['login'],
							"logonSuffix"=>$adldap->getAccountSuffix(),
							"firstname"	=>strtoupper($_POST['nom']),
							"surname"	=>$_POST['prenom'],
							"company"	=>"moda",
							"department"=>"none",
							"email"		=>$_POST['login'].$adldap->getAccountSuffix(),
							"container"	=>array($_POST['parent']),
							"enabled"	=>$enabled,
							"password"	=>$password,
							"mustchange"=>$mustchange
						);
					$result = $adldap->user()->create($attributes);
					if ($result) {
						echo"<script>document.location='manage.php?cat=user&do=create&result=1'</script>";
					}else{
						echo"<script>document.location='manage.php?cat=user&do=create&result=0'</script>";
					}
					
				}
				if (isset($_REQUEST['result']) && $_REQUEST['result']==1) {
					$msgType="success";
					$message="l'utilisateur a été crée avec succès";
				}else if (isset($_REQUEST['result']) && $_REQUEST['result']==0){
					$msgType="error";
					$message="l'utilisateur n'a pas été crée,une erreur c'est produite!";
				}
			 ?>
			<?php 
				if (!empty($message)) {
					echo "<div class=\"message $msgType\"><p>$message</p></div>";
				}
			?>
			<form name="frmUser" action="" method="post">
			
				<table cellpadding="0" cellspacing="0" width="100%">
				
					<tr>
						<td width="130">Nom</td>
						<td><input type="text" name="nom" placeholder="Nom" class="text small"></td>
					</tr>
					<tr>
						<td>Prenom</td>
						<td><input type="text" name="prenom" placeholder="Prenom" class="text small"></td>
					</tr>
					<tr>
						<td>Login</td>
						<td>
							<input type="text" name="login"  placeholder="login" class="text small">
							<input type="checkbox" class="checkbox" checked="checked" name="active" id="active" /><label for="active">Compte est activé.</label>
						</td>
					</tr>
					<tr>
						<td>Mot de passe</td>
						<td>
							<input type="password" name="password"  placeholder="mot de passe" class="text small">
							<input type="checkbox" class="checkbox" checked="checked" name="mustchange" id="mstchng" /><label for="mstchng">L'utilisateur doit changer son mot de passe.</label>
						</td>
					</tr>
					<tr>
						<td>Créer dans</td>
						<td>
							<?php include 'refDN.php'; ?>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>
							<input type="reset" name="reset"  value="Effacer" class="submit small">
							<input type="submit" name="btnValider"  value="Valider" class="submit small">
						</td>
					</tr>

				</table>
			</form>
		</div>
	</div>		<!-- .block_content ends -->
	
	<div class="bendl"></div>
	<div class="bendr"></div>
</div>		<!-- .block ends -->