<div class="block">
	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>
		<h2>Liste des comptes unitilises</h2>
		
		<ul>
			<li><a href="/export.php?type=unused" target="_blank">Imprimer</a></li>
			<li><a href="?cat=account&do=disabled">Comptes désactivés</a></li>
			<li><a href="?cat=account&do=expired">Comptes expirés</a></li>

		</ul>
	</div>		<!-- .block_head ends -->

	<div class="block_content">
		<div id="in_content">
			<?php
				$users=$adldap->user()->all();
			?>
			<table cellpadding="0" cellspacing="0" width="100%" id="allUser">
				<thead>
					<tr class="header">
						<th width="20%">Nom Utilisateur</th>
						<th width="10%">Compte</th>
						<th width="35%">Conteneur</th>
						<th>Dernière ouverture session</th>
						<th>Total Ouvèrture session</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($users as $user) {
							//$lastlogon=$user["lastlogon"]=="Jamais" ? "Jamais" : date("d/m/y H:i",$user["lastlogon"]);
							if ($user['logoncount']==0) {
								echo "
									<tr>
										<td>".$user['name']."</td>
										<td>".$user['compte']."</td>
										<td>".$user['container']."</td>
										<td>".$user['lastlogon']."</td>
										<td>".$user['logoncount']."</td>
									</tr>";
							}
						}
					?>
				</tbody>
			</table>
			
		</div>
	</div>		<!-- .block_content ends -->
	
	<div class="bendl"></div>
	<div class="bendr"></div>
</div>		<!-- .block ends -->