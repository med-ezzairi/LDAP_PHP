<div class="block">
	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>
		<h2>Liste Groupes</h2>
		
		<ul>
			<li><a href="?cat=user&do=list">List utilisateurs</a></li>
			<li><a href="?cat=group&do=create">Nouveau groupe</a></li>

		</ul>
		<form action="" method="post">
			<input type="text" class="text" name="groupName" placeholder="Rechercher" />
		</form>
	</div>		<!-- .block_head ends -->

	<div class="block_content">
		<div id="in_content">
			<?php
				$groupToFind="*";
				if(isset($_POST['groupName']) && !empty($_POST['groupName'])){
					$groupToFind=$_POST['groupName']."*";//
					$msgType="info";
					$message="Résultat de la recherche pour: ".$_POST['groupName'];
				}
				$groups=$adldap->group()->all($groupToFind);
			 ?>
			<?php
				if (isset($_REQUEST['info'])) {
					$info=$_REQUEST['info'];
					if ($info=='success') {
						$msgType="success";
						$message="Le groupe a été supprimé avec succès";
					}else{
						$msgType="error";
						$message="Le groupe n'a pas été supprimé,une erreur c'est produite!";
					}
				}
				if (isset($message) && !empty($message)) {
					echo "<div class=\"message $msgType\"><p>$message</p></div>";
				}
			?>
			<table cellpadding="0" cellspacing="0" width="100%" id="allUser">
				<thead>
					<tr class="header">
						<th width="30%">Nom groupe</th>
						<th width="10%">Total membres</th>
						<th>Conteneur</th>
						<th>Opérations</th>
					</tr>
				</thead>
				<tbody>
					<?php
						for ($i=0;$i<$groups["count"];$i++) {
							$group=$groups[$i];
							echo "
								<tr>
									<td>".$group['name']."</td>
									<td>".$group['total']."</td>
									<td>".$group['container']."</td>
									<td>".$group['links']."</td>
								</tr>";
						}
					?>
				</tbody>
			</table>
			
		</div>
	</div>		<!-- .block_content ends -->
	
	<div class="bendl"></div>
	<div class="bendr"></div>
</div>		<!-- .block ends -->