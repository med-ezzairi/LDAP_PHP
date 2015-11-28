<style type="text/css">
        h1{
            text-align: center;
            text-decoration: underline;
        }
        h3{
            text-align: center;
            color: red;
        }
        th{
            /*height: 20px;*/
            padding-left: 4px;
            border: 0.5px solid gray;
            vertical-align: middle;
        }
        .rapport td{
            height: 20px;
            padding-left: 4px;
            border-bottom: 1px dotted gray;
        }
</style>
<?php
    if(!isset($_REQUEST['type']))die('<h3>erreur de chargement de type de rapport!</h3>');
    $type=$_REQUEST['type'];

    if($type=='expired'){
        $title="Lite des comptes dont le mot de passe est expéré";
    }else if($type=='disabled'){
        $title="Lite des comptes désactivés";
    }else if($type=='unused'){
        $title="Lite des comptes non-utilisés";
    }else{
        die('<h3>erreur de chargement de type de rapport!</h3>');
    }
 ?>
<page backtop="10mm" backbottom="10mm" backleft="5mm" backright="5mm">
    <page_header>
        <table style="width: 100%; border:1px solid  black;">
            <tr>
                <td style="text-align:left;width:33%">Géstion Active Directory avec PHP</td>
                <td style="text-align:center;width:34%"></td>
                <td style="text-align:right;width:33%"><?php echo date('d/m/Y H:i'); ?></td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <table style="width: 100%; border: solid 1px black;">
            <tr>
                <td style="text-align: left;    width: 50%"></td>
                <td style="text-align: right;    width: 50%">page [[page_cu]]/[[page_nb]]</td>
            </tr>
        </table>
    </page_footer>
    <h1><?php echo $title; ?></h1><br /><br />
    <?php
        $users=$adldap->user()->all();
    ?>
    <table border="0" cellpadding="2" cellspacing="0" style="width: 100%;" class="rapport">
        <tr>
            <th style="width: 20%;">Nom Utilisateur</th>
            <th style="width: 20%;">Compte</th>
            <th style="width: 42%;">Conteneur</th>
            <th style="width: 17%;">Dernière ouverture session</th>
        </tr>
        <?php
            switch ($type) {
                case 'expired':
                    //expired accounts
                    foreach ($users as $user) {
                        if ($user['expired']==0) {
                            if(strlen($user['container'])>30)$user['container']=substr($user['container'], 0,30)."...";
                            echo "
                                <tr>
                                    <td>".$user['name']."</td>
                                    <td>".$user['compte']."</td>
                                    <td>".$user['container']."</td>
                                    <td>".$user['lastlogon']."</td>
                                </tr>";
                        }
                    }
                    break;
                case 'disabled':
                    //expired accounts
                    foreach ($users as $user) {
                        if ($user['active']==0) {
                            if(strlen($user['container'])>30)$user['container']=substr($user['container'], 0,30)."...";
                            echo "
                                <tr>
                                    <td>".$user['name']."</td>
                                    <td>".$user['compte']."</td>
                                    <td>".$user['container']."</td>
                                    <td>".$user['lastlogon']."</td>
                                </tr>";
                        }
                    }
                    break;
                case 'unused':
                    //expired accounts
                    foreach ($users as $user) {
                        if ($user['logoncount']==0) {
                            if(strlen($user['container'])>30)$user['container']=substr($user['container'], 0,30)."...";
                            echo "
                                <tr>
                                    <td>".$user['name']."</td>
                                    <td>".$user['compte']."</td>
                                    <td>".$user['container']."</td>
                                    <td>".$user['lastlogon']."</td>
                                </tr>";
                        }
                    }
                    break;
                default:
                    break;
            }
                    

        ?>
    </table>

</page>