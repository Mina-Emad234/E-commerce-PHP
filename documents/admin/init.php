<?php
    include "conn.php";


    $tpl="includes/templates/";
    $lang="includes/languages/";
    $funcs="includes/functions/";
    $css="layout/css/";
    $js="layout/js/";


    include $funcs . "functions.php";
    //classes
    require "includes/classes/admin_login.class.php";
    require "includes/classes/dashboard.class.php";
    require "includes/classes/category.class.php";
    require "includes/classes/items.class.php";
    require "includes/classes/member.class.php";
    require "includes/classes/comment.class.php";
    require "includes/classes/order.class.php";





    include $tpl . "header.php";
    if(!isset($noNavbar)){
        include $tpl . "navbar.php";
    }

    include $tpl . "footer.php";
