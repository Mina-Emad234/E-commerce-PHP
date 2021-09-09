<?php
    include "admin/conn.php";


    $tpl="includes/templates/";
    $lang="includes/languages/";
    $funcs="includes/functions/";
    $css="layout/css/";
    $js="layout/js/";

    $sessionUser='';
    if (isset($_SESSION['user'])) {
        $sessionUser = $_SESSION['user'];
    }

    include $funcs . "functions.php";
    include $tpl . "header.php";
    // include $tpl . "footer.php";

    //classes
    require "includes/classes/login.class.php";
    require "includes/classes/profile.class.php";
    require "includes/classes/new_ad.class.php";
    require "includes/classes/items.class.php";
    require "includes/classes/pay.class.php";
