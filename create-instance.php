<?php
   session_start();
    
   if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
       header("location: login.php");
       exit;
   }
   ?>
<?php
   // Incluir fichero de configuración de mysql
   require_once "config.php";
    
   // definición de variables
   $nom_bd = "use `minetest`;" . "\n";
   $nombre_contenedor = $nombre_servidor = $username = $email = $password = $confirm_password = "";
   $nombre_contenedor_err = $nombre_servidor_err = $username_err = $email_err = $password_err = $confirm_password_err = "";
   $fileSQL = $comando = $sql = "";
   $port = rand(30000,31000);

   
   if($_SERVER["REQUEST_METHOD"] == "POST"){
       $fileSQL=fopen('sql/update.sql', 'w');
       fwrite($fileSQL, $nom_bd);
   
       if(empty(trim($_POST["nombre_contenedor"]))){
           $nombre_contenedor_err = "Por favor ingrese el nombre del contenedor.";
       } else{
           $nombre_contenedor = $_POST["nombre_contenedor"];        
   
           // reemplazar el nombre del pod por defecto
           copy($file_sample, $copy);
           file_put_contents($copy,str_replace('nombreContainer',"$nombre_contenedor",file_get_contents($copy))); 
   
       }
   
       if(empty(trim($_POST["nombre_servidor"]))){
           $nombre_servidor_err = "Por favor ingrese el nombre para su servidor.";
       } else{
           $nombre_servidor = $_POST["nombre_servidor"];
           // preparando sql para actulizar el título de la web de wordpress
           fwrite($fileSQL, $sql);
       }
   
       if(empty(trim($_POST["password"]))){
           $password_err = "Por favor ingresa una contraseña.";     
       } elseif(strlen(trim($_POST["password"])) < 6){
           $password_err = "La contraseña al menos debe tener 6 caracteres.";
       } else{
           $password = trim($_POST["password"]);
       }
           
       if(empty(trim($_POST["confirm_password"]))){
           $confirm_password_err = "Confirma tu contraseña.";     
       } else{
           $confirm_password = trim($_POST["confirm_password"]);
           if(empty($password_err) && ($password != $confirm_password)){
               $confirm_password_err = "No coincide la contraseña.";
           }
       }
   
       if(empty(trim($_POST["email"]))){
           $email_err = "Por favor ingrese un email";
       }
   
       if(empty(trim($_POST["username"]))){
           $username_err = "Por favor ingrese un nombre de usuario";
       } else if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err)){
   
           $username = $_POST["username"];
           $nombre_dominio = $_POST["nombre_dominio"] . ".proyecto.ccff.site:8080";
           $email = $_POST["email"];
           $password = $_POST["password"];
       
           // preparando sql para actualizar el usuario de administrador de wordpress
           $sql = "UPDATE wp_users SET user_login = '$username',
                   user_pass = MD5('$password'), user_email = '$email', 
                   WHERE ID = 1;" . "\n";
               
           fwrite($fileSQL, $sql);
           fclose($fileSQL);
   
           sleep(2);
           shell_exec("sudo sh scripts/script.sh $nombre_servidor $puerto");
           header("location: instance.php");
       }
   }
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <title>Crear contenedor</title>
      <meta name="viewport" content="width=p, initial-scale=1.0">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
      <link rel="stylesheet" type="text/css" href="styles/create-instance.css">
   </head>
   <body>
      <div class="wrapper">
         <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="n_cont">
               <h1>Crear contenedor</h1>
               <p>Completa este formulario para crear tu servidor de Minetest.</p>
               <hr>
               <div class="form-group <?php echo (!empty($nombre_contenedor_err)) ? 'has-error' : ''; ?>">
                  <label for="nombre_servidor"><b>Nombre del servidor</b></label>
                  <input type="text" placeholder="Añadir nombre del servidor" name="nombre_servidor" value="<?php echo $nombre_servidor; ?>" required>
                  <span class="help-block"><?php echo $nombre_servidor_err; ?></span>
	       </div>
			 <h2><?php echo $port ?></h2>
               </div>

               <h4>
               Minetest</h3>
               <br>
               <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                  <label for="username"><b>Usuario</b></label>
                  <input type="text" placeholder="Añadir usuario" name="username" value="<?php echo $username; ?>" required>
                  <span class="help-block"><?php echo $username_err; ?></span>
               </div>
               <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                  <label for="email"><b>E-mail</b></label>
                  <input type="email" placeholder="Añadir Email"  name="email" value="<?php echo $email; ?>" required>
                  <span class="help-block"><?php echo $email_err; ?></span>
               </div>
               <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                  <label for="password"><b>Contraseña</b></label>
                  <input type="password" placeholder="Añadir Contraseña" name="password" value="<?php echo $password; ?>" required>
                  <span class="help-block"><?php echo $password_err; ?></span>
               </div>
               <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                  <label for="password"><b>Repite tu Contraseña</b></label>
                  <input type="password" placeholder="Añadir Contraseña" name="confirm_password" value="<?php echo $confirm_password; ?>" required>
                  <span class="help-block"><?php echo $confirm_password_err; ?></span>
               </div>
               <hr>
               <div class="form-group">
                  <button type="submit" class="createbtn">Crear</button>
               </div>
            </div>
            <div class="container index">
               <a href="instance.php">Volver</a>
            </div>
         </form>
      </div>
   </body>
</html>

