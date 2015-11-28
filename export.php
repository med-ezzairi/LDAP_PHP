<?php
    session_start();
    if(!isset($_SESSION['userinfo']) || !isset($_COOKIE['PHPSESSID'])){
        header('Location:/index.html');
    }else{
        $userRDN=$_SESSION['userinfo'][0]["samaccountname"][0];
    }
    //initialisation et instantiation de la classe adLDAP
    require_once(dirname(__FILE__)."/src/adLDAP.php");
    //initialisation et instantiation de la classe config
    include ("/getConfig.php");
    $ini_array["admin_username"]=$userRDN;
    $ini_array["admin_password"]=$_SESSION["userinfo"]["ldapPass"];

    try {
        $adldap = new adLDAP($ini_array);
    }
    catch (adLDAPException $e) {
        header('Location:/index.html');
        exit();
    }

    // get the HTML
    //débu du contenu qui sera affiché dans le pdf
    ob_start();
    include(dirname(__FILE__).'/account2pdf.php');
    $content = ob_get_clean();
    //fin du contenu qui sera affiché dans le pdf
    /*********************************************************************/
    // convert in PDF
    require_once(dirname(__FILE__).'/pdf/html2pdf.class.php');
    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'fr');
        //$html2pdf->setModeDebug();
        // $html2pdf->setDefaultFont('times');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($content);
        //$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        ob_end_clean();
        $html2pdf->Output('expired.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    } 

 ?>