<div class="block">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>
		
		<h2>Ajouter Unité d'Organisation</h2>
		<ul>
			<li><a href="?cat=user&do=create">Ajouter utilisateur</a></li>
			<li><a href="?cat=group&do=create">Ajouter groupe</a></li>
		</ul>
	</div>		<!-- .block_head ends -->

	<div class="block_content">
		<div style="height:550px;">
			<?php 
				if(isset($_POST['btnValider']) && $_POST['btnValider']=="Valider"){

					$attributes=array(
						"ou_name"	=>$_POST['nom'],
						"description"	=>$_POST['desc'],
						"container"		=>$_POST['parent']
						);
					$result = $adldap->folder()->create($attributes);
					if ($result) {
						echo"<script>document.location='manage.php?cat=ou&do=create&result=1'</script>";
					}else{
						echo"<script>document.location='manage.php?cat=ou&do=create&result=0'</script>";
					}
					
				}
				if (isset($_REQUEST['result']) && $_REQUEST['result']==1) {
					$msgType="success";
					$message="l'Unité d'Organisation a été crée avec succès";
				}else if (isset($_REQUEST['result']) && $_REQUEST['result']==0){
					$msgType="error";
					$message="l'Unité d'Organisation n'a pas été crée,une erreur c'est produite!";
				}
			?>
			<?php 
				if (!empty($message)) {
					echo "<div class=\"message $msgType\"><p>$message</p></div>";
				}
			?>
			<form action="" method="post">
			
				<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td  width="130">Nom de l'unité</td>
						<td>
							<input type="text" name="nom"  class="text small" placeholder="Nom de l'unité">
						</td>
					</tr>
					<tr>
						<td>Description</td>
						<td>
							<input type="text" name="desc"  class="text small" placeholder="Description de l'unité">
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