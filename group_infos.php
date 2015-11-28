<div class="block">
	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>
		
		<h2>Infos. Groupe</h2>
		<ul>
			<li>
				<a href="/manage.php?cat=group&do=list">List Groupes</a>
			</li>
			<li>
				<a href="/manage.php?cat=group&do=create">Ajouter Nouveau</a>
			</li>
		</ul>
	</div>		<!-- .block_head ends -->
	

	<div class="block_content">
		<div id="in_content">
			<?php 
				$groupToFind= isset($_REQUEST['group']) && $_REQUEST['group']!='*' ? $_REQUEST['group'] : NULL;
				$group=true;
				if ($groupToFind) {
					$group =$adldap->group()->all($groupToFind);
					$group=$group[0];
				}else{
					$msgType="error";
					$message="Aucun groupe n'a été séléctioné pour afficher ses informations!";
				}
				if (!$group) {
					$msgType="error";
					$message="Erreur: groupe séléctioné n'existe pas dans Active Directory!";
				}
			 ?>
			<?php 
				if (isset($message) && !empty($message)) {
					echo "<div class=\"message $msgType\"><p>$message</p></div>";
				}else{
			?>
			<div id="left" style="width:49%;float:left;clear:none;">
				<ul>
					<h3><u>Nom du groupe</u></h3>
						<li><?php echo $group["cn"][0]; ?></li>
					<h3><u>Description</u></h3>
						<li><?php echo isset($group["description"][0])?$group["description"][0]:"Acune description"; ?></li>
					<h3><u>Type & étendu du groupe</u></h3>
						<li><?php echo $group["type"]; ?></li>
					<h3><u>Conteneur</u></h3>
						<li><?php echo  $group["container"]; ?></li>
				</ul>
				<div>
					<input type="button" id="moveTo" name="moveTo" value="déplacer" class="submit long">
					<input type="button" id="btnaction" name="delete" value="Supprimer le groupe" class="submit long">
				</div>
			</div>
			
				<div class="block small right">		<!-- .block.small.right -->
					<div class="block_head">
						<div class="bheadl"></div>
						<div class="bheadr"></div>
						<h2>membre du group</h2>
					</div>		<!-- .block_head ends -->
					<div class="block_content">
						<div id="Members" class="mCustomScrollbar">
							<div id="memeberColRight" class="columnNoMov"><!-- div contenant allGroups -->
								<?php
									$allGroups=$adldap->group()->getPossibleGroups($group["samaccountname"][0]);
									if (array_key_exists('member', $group)) {
										for ($i=0;$i<$group["member"]['count'];$i++) {
											$groupName=$adldap->getName($group["member"][$i]);
											echo "<div class=\"dragbox\"><h2 id=\"primary\">".$groupName."</h2></div>";
										}
									}
								?>
							</div><!-- div contenant allGroups ends -->
						</div>
					</div>		<!-- .block_content ends -->
					<div class="bendl"></div>
					<div class="bendr"></div>
				</div>		<!-- .block.small.right ends -->
			
			
			<div> <!-- just a div for groups -->
				<div class="block small left">		<!-- .block.small.left -->
					<div class="block_head">
						<div class="bheadl"></div>
						<div class="bheadr"></div>
						<h2>Membre de</h2>
					</div>		<!-- .block_head ends -->
					<div class="block_content">
						<div id="inGroups" class="mCustomScrollbar">
							<div id="colLeft" class="column ui-sortable"><!-- div contenant groups -->
								<?php
									if(isset($group["memberof"])){
										for ($i=0; $i < $group["memberof"]["count"]; $i++) {
											$groupName=$adldap->getName($group["memberof"][$i]);
											echo "<div class=\"dragbox\"><h2>".$groupName."</h2></div>"; 
										}
									}
								?>
							</div><!-- div contenant groups ends -->
						</div>
					</div>		<!-- .block_content ends -->
					<div class="bendl"></div>
					<div class="bendr"></div>
				</div>		<!-- .block.small.left ends -->
				<div class="block small right">		<!-- .block.small.right -->
					<div class="block_head">
						<div class="bheadl"></div>
						<div class="bheadr"></div>
						<h2>rendre membre de</h2>
						<form name="frmsearch" id="frmsearch" method="post" action="">
						    <input class="text" id="userSearch" name="userfilter" type="text" placeholder="Rechercher" />
						</form>
					</div>		<!-- .block_head ends -->
					<div class="block_content">
						<div id="allGroups" class="mCustomScrollbar">
							<div id="colRight" class="column ui-sortable"><!-- div contenant allGroups -->
								<?php
									$allGroups=$adldap->group()->getPossibleGroups($group["samaccountname"][0]);
									foreach ($allGroups as $key => $curGroup) {
										echo "<div class=\"dragbox\"><h2>".$curGroup["samaccountname"][0]."</h2></div>";
									}
								?>
							</div><!-- div contenant allGroups ends -->
						</div>
					</div>		<!-- .block_content ends -->
					<div class="bendl"></div>
					<div class="bendr"></div>
				</div>		<!-- .block.small.right ends -->
			</div> <!-- just a div for groups ends-->
		<?php } ?>
		</div>
		<!-- div for moving AD object -->
		<form>
			<input type="hidden" name="usersn" value="<?php echo $group['samaccountname'][0]; ?>">
			<input type="hidden" name="<?php echo $_REQUEST['cat']; ?>" id="objetCN" value="<?php echo $group['samaccountname'][0]; ?>">
			<div id="divNoir"></div>
			<!-- div message confirmation -->
			<div id="userDelete">
				<div id="msgText"></div>
				<div id="divButton">
					<input type="button" name="cancel" value="Annuler" class="submit small">
					<input type="button" name="submit" value="Valider" class="submit small">
				</div>
			</div>
			
			<!-- div loading and ajaxRequest -->
			<div id="loading">Déplacement de l'objet en cours..</div>


			<!-- end div for moving Ad object -->
			<?php include('moveTo.php'); ?>
		</form>
	</div>		<!-- .block_content ends -->
	
	<div class="bendl"></div>
	<div class="bendr"></div>
</div>		<!-- .block ends -->