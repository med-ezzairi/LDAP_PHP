<div class="block">
	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>
		
		<h2>Infos. Utilisateur</h2>
		<ul>
			<li>
				<a href="/manage.php?cat=user&do=list">List Utilisateurs</a>
			</li>
			<li>
				<a href="/manage.php?cat=user&do=create">Ajouter Nouveau</a>
			</li>
		</ul>
	</div>		<!-- .block_head ends -->
	

	<div class="block_content">
		<div id="in_content">
			<?php 
				$userCompte= isset($_REQUEST['user']) && $_REQUEST['user']!='*' ? $_REQUEST['user'] : NULL;
				$users=true;
				if ($userCompte) {
					$users =$adldap->user()->infos($userCompte);
				}else{
					$msgType="error";
					$message="Aucun utilisateur n'a été séléctioné pour afficher ses informations!";
				}
				if (!$users) {
					$msgType="error";
					$message="Erreur: L'utilisateur séléctioné n'existe pas dans Active Directory!";
				}
			 ?>
			<?php 
				if (isset($message) && !empty($message)) {
					echo "<div class=\"message $msgType\"><p>$message</p></div>";
				}else{
			?>
			<div id="divtable">
				<table cellpadding="0" cellspacing="0" width="100%">
				
					<tr>
						<td width="30%">Nom & Prenom</td>
						<td><?php echo $users[0]["cn"][0]; ?></td>
					</tr>
					<tr>
						<td>Compte</td>
						<td><?php echo isset($users[0]["userprincipalname"][0])?$users[0]["userprincipalname"][0]:$users[0]["samaccountname"][0]; ?></td>
					</tr>
					<tr>
						<td>Dernière Ouverture de session</td>
						<td><?php echo $users[0]["lastlogon"]; ?></td>
					</tr>
					<tr>
						<td>Total Ouverture de session</td>
						<td><?php echo $users[0]["logoncount"][0]; ?></td>
					</tr>
					<tr>
						<td>Compte vérouillé</td>
						<td>
							<?php 
								$etatCompte=(isset($users[0]["lockouttime"][0]) && ($users[0]["lockouttime"][0]))!=0 ? "<div id=\"passEx\">Vérouillé</div>": "<div id=\"passVal\">Normal</div>";
								echo ($etatCompte);
							?>
						</td>
					</tr>
					<tr>
						<td>Etat du compte</td>
						<td>
							<?php
								$etatCompte=$users[0]["active"]==true ? "<div id=\"passVal\">Active</div>": "<div id=\"passEx\">Désactivé</div>";
								echo ($etatCompte);
							?>
						</td>
					</tr>
					<tr>
						<td>Mot de passe changé le</td>
						<td><?php echo $users[0]["pwdlastset"][0]!=0 ? $users[0]["pwdlastset"][0]:"indisponible"; ?></td>
					</tr>
					<tr>
						<td>Mot de passe expire le</td>
						<td>
							<?php
								echo $adldap->user()->passwordExpiry($users[0]["samaccountname"][0]);
							?>
						</td>
					</tr>
					<tr>
						<td>Conteneur</td>
						<td><?php echo $users[0]["container"]; ?></td>
					</tr>
				</table>
			</div>
			<div id="operations">
				<input type="button" id="btnaction" name="unlock" value="Déverouiller" class="submit long"><br>
				<?php if($users[0]["active"]!=true){ ?>
					<input type="button" id="btnaction" name="enable" id="" value="Activer" class="submit long"><br>
				<?php }else{ ?>
					<input type="button" id="btnaction" name="disable" value="Désactiver" class="submit long"><br>
				<?php } ?>
				<input type="button" id="btnReset" name="resetPass" value="Changer Mot de Passe" class="submit long"><br>
				<input type="button" id="moveTo" name="moveTo" value="déplacer" class="submit long"><br>
				<input type="button" id="btnaction" name="delete" value="Supprimer le compte" class="submit long"><br>
			</div>
			
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
								//echo $users[0]["memberof"]["count"];
								if(isset($users[0]["memberof"])){
									for ($i=0; $i <= $users[0]["memberof"]["count"]; $i++) {
										$curGroup=$users[0]["memberof"][$i];
										$isPrimary=$users[0]["memberof"][$i]["primary"]==true? 'id="primary"':'';
										//echo $users[0]["memberof"][$i]."<br>";
										echo "<div class=\"dragbox\"><h2 $isPrimary>".$curGroup["name"]."</h2></div>"; 
									}
								}else{
									echo '<font style="color:red;">indisponible</font>';
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
						<div id="colRight" class="column ui-sortable"><!-- div contenant groups -->
							<?php
								$groups=$adldap->group()->search(null);
								if(!empty($groups)){
									$rien=array_shift($groups);
									foreach ($groups as $key => $group) {
										echo "<div class=\"dragbox\"><h2>".$group["samaccountname"][0]."</h2></div>"; 
									}
								}else{
									echo '<font style="color:red;">Aucun groupe</font>';
								}
							?>
						</div><!-- div contenant groups ends -->
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
			<input type="hidden" name="usersn" value="<?php echo $users[0]["samaccountname"][0]; ?>">
			<input type="hidden" name="<?php echo $_REQUEST['cat']; ?>" id="objetCN" value="<?php echo $users[0]["samaccountname"][0]; ?>">
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
			<!-- div reset password -->
			<div id="resetpassword">
				<h3 id="UserName"></h3>
				<div id="passerror">Le mot de passe et la confirmation sont differents</div>
				<label for="pass">Mot de pass</label><br />
				<input type="password" name="pass" placeholder="mot de passe" class="text small"><br />
				<label for="confirmation">Confirmation</label><br />
				<input type="password" name="confirmation" placeholder="confirmation" class="text small"><br/>
				<input type="checkbox" name="mustchange" checked="true" class="checkbox">
				<label for="mustchange">Doit changer le mot de passe à l'ouverture de séssion</label>
				
				<br /><br />
				<div id="divButton">
					<input type="button" name="cancel" value="Annuler" class="submit small">
					<input type="button" name="submit" value="Changer" class="submit small" id="resetpwd">
				</div>
			</div>
			<!-- div reset password ends -->
			<!-- end div for moving Ad object -->
			<?php include('moveTo.php'); ?>
		</form>
	</div>		<!-- .block_content ends -->
	
	<div class="bendl"></div>
	<div class="bendr"></div>
</div>		<!-- .block ends -->