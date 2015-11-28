<div class="block">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>
		
		<h2>Ajouter Groupe</h2>
		<ul>
			<li><a href="?cat=user&do=create">Ajouter utilisateur</a></li>
			<li><a href="?cat=user&do=create">Ajouter Unité</a></li>
		</ul>
	</div>		<!-- .block_head ends -->

	<div class="block_content">
		<div style="height:550px;">
			<?php 
				if(isset($_POST['btnValider']) && $_POST['btnValider']=="Valider"){
					$groupType=-2147483644;//default is Local Group
					if($_POST['rdGroup']=="global")$groupType=-2147483646 ;
					if($_POST['rdGroup']=="universel")$groupType=-2147483640;
					if(!isset($_POST['desc']) || empty($_POST['desc']))$_POST['desc']="";
					$attributes=array(
						"group_name"	=>$_POST['nom'],
						"description"	=>$_POST['desc'],
						"container"		=>$_POST['parent'],
						"grouptype"=>$groupType);
					$result = $adldap->group()->create($attributes);
					if ($result) {
						echo"<script>document.location='manage.php?cat=group&do=create&result=1'</script>";
					}else{
						echo"<script>document.location='manage.php?cat=group&do=create&result=0'</script>";
					}
					
				}
				if (isset($_REQUEST['result']) && $_REQUEST['result']==1) {
					$msgType="success";
					$message="le groupe a été crée avec succès";
				}else if (isset($_REQUEST['result']) && $_REQUEST['result']==0){
					$msgType="error";
					$message="Le groupe n'a pas été crée, une erreur c'est produite!";
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
						<td  width="130">Nom Groupe</td>
						<td>
							<input type="text" name="nom"  class="text small" placeholder="Nom du groupe">
						</td>
					</tr>
					<tr>
						<td>Desc Groupe</td>
						<td>
							<input type="text" name="desc"  class="text small" placeholder="Description du groupe">
						</td>
					</tr>
					<tr>
						<td>Créer dans</td>
						<td>
							<?php include 'refDN.php'; ?>
						</td>
					</tr>
					<tr>
						<td> </td>
						<td>
							<fieldset>
								<legend>Etendue du groupe (Sécurité)</legend>
								<input type="radio" name="rdGroup" value="local" class="radio" id="rd1" checked="checked">
								<label for="rd1">Domaine Local</label>
								<br>
								<input type="radio" name="rdGroup" value="global" class="radio" id="rd2">
								<label for="rd2">Global</label>
								<br>
								<input type="radio" name="rdGroup" value="universel" class="radio" id="rd3">
								<label for="rd2">Universel</label>
							</fieldset>
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