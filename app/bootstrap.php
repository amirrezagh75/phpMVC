<?php 
    
    //Load Config File
    
    require_once "config/Config.php";

    //Load helper
    require_once "helpers/url_helper.php";
    require_once "helpers/session_helper.php";

    //Auto Loading Libraries Files
    
    spl_autoload_register(function($className){

        require_once "libraries/". $className .".php";
    })
    
?>