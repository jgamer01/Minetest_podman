<?php
   define('DB_SERVER', 'localhost');
   define('DB_USERNAME', 'jhernandeza01');
   define('DB_PASSWORD', 'usuario');
   define('DB_NAME', 'user_web');
    
   $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
   // Chequeo de la conexión
   if($link === false){
       die("ERROR: Could not connect. " . mysqli_connect_error());
   }
?>
