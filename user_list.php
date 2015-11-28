<div class="block">
	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>
		<h2>Tous les Utilisateurs</h2>

		<ul>
			<li><a href="?cat=user&do=create">Nouvel utilisateur</a></li>
			<li><a href="?cat=group&do=list">Liste groupes</a></li>
		</ul>
		<form name="frmsearch" id="frmsearch" method="post" action="">
		    <input class="text" id="userSearch" name="userName" type="text" placeholder="Rechercher" />
		</form>
	</div>		<!-- .block_head ends -->

	<div class="block_content">
		<div id="in_content">
			<?php
				$userToFind="*";
				if(isset($_POST['userName']) && !empty($_POST['userName'])){
					$userToFind=$_POST['userName']."*";//
					$msgType="info";
					$message="Résultat de la recherche pour: ".$_POST['userName'];
				}
				$users = $adldap->user()->all($userToFind,true);
			 ?>
			<?php
				if (isset($_REQUEST['info'])) {
					$info=$_REQUEST['info'];
					if ($info=='success') {
						$msgType="success";
						$message="l'utilisateur a été supprimé avec succès";
					}else{
						$msgType="error";
						$message="l'utilisateur n'a pas été supprimé,une erreur c'est produite!";
					}
				}
				if (isset($message) && !empty($message)) {
					echo "<div class=\"message $msgType\"><p>$message</p></div>";
				}
			?>
			<table cellpadding="0" cellspacing="0" width="100%" id="allUser">
				<thead>
					<tr class="header">
						<th>Nom Prenom</th>
						<th>Compte</th>
						<th>Lastlogon</th>
						<th>Etat du compte</th>
						<th>Password</th>
						<th>Opérations</th>
					</tr>
				</thead>
				<tbody>
				<?php 
					foreach ($users as $user) {
						//var_dump($user);
						//$lastlogon=$user["lastlogon"]=="Jamais" ? "Jamais" : date("d/m/y H:i",$user["lastlogon"]);
						$etatCompte=$user["active"]==true ? "<div id=\"passVal\">active</div>": "<div id=\"passEx\">désactivé</div>";
						
						echo "
						<tr>
							<td>".$user["name"]."</td>
							<td>".$user["compte"]."</td>
							<td>".$user["lastlogon"]."</td>
							<td>".$etatCompte."</td>
							<td>".$user["passexpire"]."</td>
							<td>".$user["links"]."</td>
						</tr>";
					}
				?>
				</tbody>
			</table>
		</div>
	</div>		<!-- .block_content ends -->
	<!-- div for deletting user account -->
	<form>
	<div id="divNoir"></div>
	<!-- div message confirmation -->
	<div id="userDelete">
		<div id="msgText"></div>
		<div id="divButton">
			<input type="hidden" name="<?php echo $_REQUEST['cat']; ?>" id="objetCN">
			<input type="button" name="cancel" value="Annuler" class="submit small">
			<input type="button" name="submit" value="Valider" class="submit small">
		</div>
	</div>
	<!-- div loading and ajaxRequest -->
	<div id="loading">Suppression de l'objet en cours..</div>

	</form>
	<!-- end div for deletting user account -->
	<div class="bendl"></div>
	<div class="bendr"></div>
</div>		<!-- .block ends -->