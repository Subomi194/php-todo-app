<?php

    ini_set('session.cookie_httponly', 1);  
    ini_set('session.use_strict_mode', 1);   
    ini_set('session.use_only_cookies', 1);  
    ini_set('session.cookie_samesite', 'Strict'); 

    // session-config.php
    session_set_cookie_params([
        'lifetime' => 86400,      
        'path' => '/',
        'domain' => 'localhost',  
        'secure' => false,        
        'httponly' => true,       
        'samesite' => 'Strict'    
    ]);

    session_start();

    $interval = 300;

    if(!isset($_SESSION["last_regeneration"])){
        regenerate_session_id();   
    }else{
        
        if (time() - $_SESSION["last_regeneration"] >= $interval){
            regenerate_session_id(); 
        }
    }


    function regenerate_session_id(){
        session_regenerate_id();
        $_SESSION["last_regeneration"] = time();
    }


