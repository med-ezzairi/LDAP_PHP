//global variables for js
var reloadTime=new Number(500);//time in milisecond,used by setTimeout function
//function show loading div
function showProgress(){
	$('#divNoir').show();
	$('#loading').show();
}
// function hide loading div
function hideProgress(){
	$('#divNoir').hide();
	$('#loading').hide();
}
$(document).ready(function(){
	//customise scrolling bar for divs
		$("#Members").mCustomScrollbar({
			axis:"y",
			setHeight:150,
			theme:"3d"
		});
		$("#allGroups").mCustomScrollbar({
			axis:"y",
			setHeight:150,
			theme:"3d"
		});
		$("#inGroups").mCustomScrollbar({
			axis:"y",
			setHeight:150,
			theme:"3d"
		});
		
		$("#in_content").mCustomScrollbar({
			axis:"y",
			setHeight:550,
			theme:"3d"
		});
		$("#ou_list").mCustomScrollbar({
			axis:"y",
			setHeight:550,
			theme:"3d"
		});

	//end customise scrolling bar for divs
	$('#allUser').tablesorter();
	
	//function post data to userAjax for: enable,disable or unlock
	$("input:button[id='btnaction']").click(
		function(){
			var action=this.name;
			if(action=="enable")$('#loading').html("Activation du compte en cours..");
			if(action=="disable")$('#loading').html("Désctivation du compte en cours..");
			if(action=="unlock")$('#loading').html("Dévérouillage du compte en cours..");
			if(action=="delete")return false;
			showProgress();
			
			var usersn=$("input[id='objetCN']").val();
			if (action=="resetpwd") {
				usersn+="&password="+"newPASS";
			}
			var ajaxData="action="+action+"&objet="+usersn;
			$.ajax({
					type: "POST",
					url: "userAjax.php",
					data: ajaxData,
					success: function(msg){
						if(msg.indexOf('success')!=-1){
							$('#loading').html("L'état du compte a été changée, Actualisation en cours ...");
						}else{
							$('#loading').html("L'état du compte n'a pas été changée, Actualisation en cours ...");
						}
						window.setTimeout(function(){location.reload();},reloadTime);
					}
			});
		}
	);
	//display moveTo div
	$('#moveTo').click(function(){
		$('#ocontent').show();
		$('#divNoir').show();
	});
	//click on div in home page
	$("div[class='home']").click(
		function(){
			var url=this.id;
			window.location.href =url;
			return false;
		}
	);

	//click on supp link display div for confirmation
	$('a#delete').click(function(){
		deleteObjet($(this).attr('href'));
		return false;
	});
	$('#btnaction[name="delete"]').click(function(){
		deleteObjet($('#objetCN').val());
	});
	// delete an AD object
	function deleteObjet(objetCN){
	    $('#divNoir').show();
		$('#userDelete').show();
		$('#objetCN').val(objetCN);
		if(objetCN.indexOf('OU=')!=-1){
			objetCN=objetCN.substring(3);
			objetCN=objetCN.substring(0,objetCN.indexOf(','));
		}
		var txtHTML="Etes-vous sûr de vouloir supprimer l'objet:\n <b>"+objetCN+"</b>!";
		$('#msgText').html(txtHTML);
	}
	//click on cancel button hide confirmation div
	$('input[name="cancel"]').click(function(){
		$('#divNoir').hide();
		$('#userDelete').hide();
		$('#resetpassword').hide();
		$('#passerror').hide();
		$('input[name="pass"]').val("");
		$('input[name="confirmation"]').val("");
	});
	//click on validate to delete folder/group/user account,show progress bar
	$('input[name="submit"][value="Valider"]').click(function(){
		$('#userDelete').hide();
		$('#loading').html("Suppréssion de l'objet en cours..");
		$('#loading').show();
		var action='delete';
		var usersn=$('#objetCN').val();
		var objetType=$('#objetCN').attr('name');
		var ajaxData="action="+action+"&objet="+usersn+"&objetType="+objetType
		$.ajax({
			type: "POST",
			url: "userAjax.php",
			data: ajaxData,
			success: function(msg){
				msg=msg.indexOf('success')!=-1 ? 'success' : 'error';
				window.setTimeout(function(){
					window.location='/manage.php?cat='+objetType+'&do=list&info='+msg;},reloadTime);
			}
		});
	});
	//click on Reset Password ,show reset password div
	$('#btnReset').click(function(){
		//affichage du nom d'utilisateur
		var textTitle="Changement du mot de passe pour: <u>"+$('#objetCN').val()+"</u>"
		$('#resetpassword #UserName').html(textTitle);
		$('#divNoir').show();
		$('#resetpassword').show();
		$('#error').hide();
	});
	//click on the Changer button to change the password
	$('#resetpwd').click(function(){
		var txtPass=$('input[name="pass"]').val();
		var confirm=$('input[name="confirmation"]').val();
		var mustchange=$('input[name="mustchange"]').attr("checked");
		mustchange= mustchange!='checked' ? false : true ;
		//return false;
		if (txtPass=="") {
			$('#passerror').html("Le mot de passe ne doit pas être vide!");
			$('#passerror').show();
		}else if(txtPass!=confirm){
			$('#passerror').html("Le mot de passe et la confirmation sont differents!");
			$('#passerror').show();
		}else {
			$('#resetpassword').hide();
			$('#loading').html("Changement du mot de passe en cours ...");
			$('#loading').show();
			usersn=$('#objetCN').val();
			//call ajax function to update password
			$.ajax({
			type: "POST",
			url: "userAjax.php",
			data: "action=resetpwd&objet="+usersn+"&password="+txtPass+"&mustchange="+mustchange,
			success: function(msg){
				//alert(mustChage);
				if(msg.indexOf('success')!=-1){
					$('#loading').html("Le mot de passe a été changé, Actualisation en cours ...");
				}else{
					$('#loading').html("Le mot de passe n'a pas été changé, Actualisation en cours ...");
				}
				window.setTimeout(function(){
					window.location='/manage.php?cat=user&do=infos&user='+usersn;},reloadTime);
			}
		});
		}
	});
	//addTo or removeFrom group by ajax supplied:action,group,user or child,type
	function addRemoveGroup(action,group,child,type){
		var ajaxData="action="+action;
		ajaxData+="&objet="+child;
		ajaxData+="&objetType="+type;
		ajaxData+="&group="+group;
		$('#loading').html('Progression de l\'opération en cours...');
		showProgress();
		//alert(ajaxData);
		$.ajax({
			type: "POST",
			url: "userAjax.php",
			data: ajaxData,
			success: function(msg){
				if(msg.indexOf('success')!=-1){
					$('#loading').html("Opération reussie!");
				}else{
					$('#loading').html("Echec de l'opération");
				}
				window.setTimeout(function(){
					hideProgress()},reloadTime);
			}
		});
	}
	//drag and drop groups using sortable
	var from='';
	var to='';
	$('.column').sortable({
		connectWith: '.column',
		handle: 'h2[id!="primary"]',
		cursor: 'move',
		placeholder: 'placeholder',
		forcePlaceholderSize: true,
		opacity: 0.4,
		start: function(event,ui){
			from=$(ui.item).parent().attr('id');
		},
		receive: function( event, ui ) {
			to=$(ui.item).parent().attr('id');
			//action to determine if addTo or RemoveFrom
			var action='addTo';
			if (to!='colLeft') action='removeFrom';
			//objetType if a user or group
			var type=$('#objetCN').attr('name');
			//child name (user name or group name)
			var child=$('#objetCN').val();
			//group name which is the parent
			var group=$(ui.item).find('h2').html();
			addRemoveGroup(action,group,child,type);
			
		},
		stop: function(event, ui){}
	})
	.disableSelection();
	//make some filter of users and groups
	$('[name="userfilter"]')
      .change( function () {
        var filter = $(this).val();
        if(filter) {
          // this finds all links in a list that contain the input,
          // and hide the ones not containing the input while showing the ones that do
          $('#colRight').find("h2:not(:Contains(" + filter + "))").parent().hide();
          //alert(id);
          //return false;
          $('#colRight').find("h2:Contains(" + filter + ")").parent().show();
        } else {
          $('#colRight').find("h2").parent().show();
        }
        return false;
      }).keyup( function () {
        // fire the above change event after every letter
        $(this).change();
    });
    // case-insensitive contains()
	 jQuery.expr[':'].Contains = function(a,i,m){
	      return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
	  };
});

//normal javascript for the RDN
function getRD(RDN){
	$('#parentpath').val(RDN);
}
function insertRDN(){
	$('#ocontent').hide();
	$('#divNoir').hide();
	$('#prentRDN').val($('#parentpath').val());
	$('#parentpath').val("");
	d.closeAll();
}
function chooseRDN(){
	$('#ocontent').show();
	$('#divNoir').show();
}
function closeBox(){
	$('#ocontent').hide();
	$('#divNoir').hide();
	$('#parentpath').val("");
	d.closeAll();
}
//show infos about a folder
function showInfo(dn){
	if(dn=='')return false;
	var ajaxData="dn="+dn;
	$('#loading').html('Chargement des données en cours...');
	showProgress();
	//alert(ajaxData);
	$.ajax({
		type: "POST",
		url: "ou_infos.php",
		data: ajaxData,
		success: function(msg){
			window.setTimeout(
				function(){
					$('#ouInfos').html(msg);
					$('#ouOperation').show();
					hideProgress();
				},reloadTime);
		}
	});
}
//end of normal javascript