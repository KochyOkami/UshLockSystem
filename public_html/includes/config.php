<?php
    //definit toute les variable pour pouvoir penetrer la bd >w<.
    if(!defined("HOST") && !defined("DB_NAME") && !defined("USER") && !defined("PASS")){
        define('HOST', 'localhost');
        define('DB_NAME', 'lock_db');
        define('USER', 'kochy_bot');
        define('PASS', 'KochyBot');
        
    }
    try{
        //se connecte a la db.
        $db = new PDO("mysql:host=" . HOST . ";dbname=" . DB_NAME, USER, PASS);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
        
    } catch(PDOException $e){
        //non erreures TwT.
        echo $e;
    }

?>
