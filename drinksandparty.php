<?php 
    session_start();

    include("data.php");

    $sante = false;


    $pagemode = new stdClass;
    $pagemode->happycolors = ["happycolors","party","styles/happycolors.css","versions/happycolors.php", "styles/happycolors_sales.css"];  //// first one is link to the version, second is name of version, then css, then php 
    $pagemode->officiallyclean =["officiallyclean","officiallyclean","styles/clean.css","versions/clean.php","styles/clean.css"]; 
    
    $actualv = new stdClass;  


    ////get page version  //// parce que flemme de faire un autre systeme pour changer de version
    if (!empty($_GET)){
        $_SESSION['v'] = $_GET["v"]; 
    }
    if(!empty($_SESSION['v'])){  /// pif paf pouf ca garde la version dans la session, sauf si changement
        $actualv->name = $pagemode->{$_SESSION["v"]}[0];
        $actualv->html = $pagemode->{$_SESSION["v"]}[3];
        $actualv->css = $pagemode->{$_SESSION["v"]}[2];
        $actualv->css_sales = $pagemode->{$_SESSION["v"]}[4];
    }
    else{
        $actualv->name = $pagemode->happycolors[0];
        $actualv->html = $pagemode->happycolors[3];   ///this is the actual version, updated by the get
        $actualv->css = $pagemode->happycolors[2];
        $actualv->css_sales = $pagemode->happycolors[4];
    }

    //// Manage if someone press a button
    $ids;
    if ($_POST){

        if(isset($_POST['party'])){
            
        }
        else{
            submitsaledata($_POST,$conn);
            $ids = explode('_',array_keys($_POST)[0]);
        }
        
        
        header( "refresh:3;url=drinksandparty.php?" );
        
        print_r('<link rel="stylesheet" href="'.$actualv->css_sales.'">');
        $sante = '<h1>'.$ids[3]." pour ".$ids[4].", santé!</h1>";
    }


include($actualv->html);
