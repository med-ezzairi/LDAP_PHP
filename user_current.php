<div class="block">
	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>
		
		<h2>Session en cours</h2>
	</div>		<!-- .block_head ends -->
	<div class="block_content">
		<div id="in_content">
			<?php
				if(isset($_SESSION['userinfo']) && isset($_SESSION['time'])){
				$user=$_SESSION['userinfo'][0];
				$time=date('d/m/Y H:i:s',$_SESSION['time']);
				$userName=$user['cn'][0];
				$userAccount=$user['samaccountname'][0];
				$userDN=$user['container'].$userName;
				//$userDN=$user['dn'];
				$userMemberof=$user['memberof'];
			?>
			<ul>
				<h3><u>Utlisateur</u></h3>
					<li><?php echo $userName; ?></li>
				<h3><u>Compte</u></h3>
					<li><?php echo $userAccount; ?></li>
				<h3><u>DN Utilisateur</u></h3>
					<li><?php echo $userDN; ?></li>
				
				<h3><u>Membre de</u></h3>
						<?php
						foreach ($userMemberof as $key => $group) {
							echo  "<li>".$group["name"]."</li>";
						}
						?>
				<h3><u>Heure début</u></h3>
					<li><?php echo $time; ?></li>
			</ul>
			<?php }else{ ?>

			<?php } ?>
		</div>
	</div>		<!-- .block_content ends -->
	<div class="bendl"></div>
	<div class="bendr"></div>
</div>		<!-- .block ends -->