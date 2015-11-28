<div class="block">
	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>
		<h2>Liste Unités d'Organisation</h2>
		
		<ul>
			<li><a href="?cat=ou&do=list">List utilisateurs</a></li>
			<li><a href="?cat=ou&do=create">Nouvelle Unité</a></li>
		</ul>
	</div>		<!-- .block_head ends -->

	<div class="block_content">
		<div  style="min-height:550px;">
			<?php
				//connexion au serveur ldap
				$ldapBase	=$adldap->getBaseDn();
				$filter="(|(objectclass=Organizationalunit)(name=*))";
				$fields = array("*");
				$sr = ldap_search($adldap->getLdapConnection(), $adldap->getBaseDn(), $filter);
				$folders = ldap_get_entries($adldap->getLdapConnection(), $sr);
			?>
			<?php
				if (isset($_REQUEST['info'])) {
					$info=$_REQUEST['info'];
					if ($info=='success') {
						$msgType="success";
						$message="L'Unité a été supprimée avec succès";
					}else{
						$msgType="error";
						$message="L'Unité n'a pas été supprimée,une erreur c'est produite!";
					}
				}
				if (isset($message) && !empty($message)) {
					echo "<div class=\"message $msgType\"><p>$message</p></div>";
				}
			?>
			<div class="oulist">
				<h2>Lite de toutes les Unités d'organisation</h2>
				<?php
					echo "<div class='dtree'>
					<p><a href='javascript: lst.openAll();'>Développer tout</a> | <a href='javascript: d.closeAll();'>Réduire tout</a></p>
					<script type='text/javascript'>
					lst = new dTree('lst');
					lst.add(\"".strtoupper($ldapBase)."\",-1,\"".strtoupper($ldapBase)."\",\"javascript:getRD('".$ldapBase."')\");";
					for ($i=0; $i < $folders ["count"]; $i++){
						$nomobj    =$folders[$i]["name"][0];
						$refdn     =strtoupper($folders[$i]["distinguishedname"][0]);
						$posvir    =strpos($refdn,",");
						$reflen    =strlen($refdn);
						$ref       =strtoupper(substr($refdn,$posvir+1,$reflen-$posvir));
						$lien="javascript:showInfo(\'".$refdn."\')";
						$objetCategory 	=$folders[$i]["objectcategory"][0];
						$posfin 		=strpos($objetCategory,",");
						$categoryName 	=substr($objetCategory,3,$posfin-3);
						if($categoryName=='Organizational-Unit'){
							echo 'lst.add("'.$refdn.'","'.$ref.'","'.$nomobj.'","'.$lien.'","","","images/imgtree/folder.gif");';
						}
					}
					echo "document.write(lst);
				     </script>
				     </div>
					 <br>";
				?>
			</div>
			<!-- div right for OU's infos -->
			<div id="ouRight">
				<div id="ouInfos">
					<center>
						<h2>Clicker sur une unité pour affciher ses informations</h2>
					</center>
				</div>
				<div style="padding-left:150px;display:none;" id="ouOperation">
					<input type="button" id="moveTo" name="moveTo" value="déplacer" class="submit long">
					<input type="button" name="delete" value="Supprimer" class="submit long" id="btnaction">
				</div>
			</div>
			<!-- div for moving AD object -->
			<form>
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
		</div>
	</div>		<!-- .block_content ends -->
	
	<div class="bendl"></div>
	<div class="bendr"></div>
</div>		<!-- .block ends -->