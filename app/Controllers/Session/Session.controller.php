
<?php

// use function PHPSTORM_META\type;

@session_start();
include_once "../Controllers/Utilities/Utilities.controller.php";
include_once "../../config/connection.php";

class Session extends connection
{
    public function createSession()
    {
        extract($_POST);
        $answer = array();
        $sqlUser = "SELECT id_rol, correo, contrasena, CONCAT(nombres, ' ', apellidos) AS nombre_completo, nombres, apellidos FROM usuario WHERE correo = '$user' ";
        $sql = $this->execute($sqlUser);

        if (mysqli_num_rows($sql) != 0) {
            $row = $sql->fetch_assoc();
            $passwordDB = $row['contrasena'];

            if (password_verify($password, $passwordDB)) {

                $_SESSION['nombre_completo'] = str_replace("*", "", $row['nombre_completo']);
                $_SESSION['nombres'] = $row['nombres'];
                $_SESSION['apellidos'] = $row['apellidos'];
                $_SESSION['rol_usuario'] = $row['id_rol'];
                $_SESSION['correo_login'] = $row['correo'];
                $answer['typeAnswer'] = true;
            }
        } else {
            $answer['typeAnswer'] = false;
        }
        echo json_encode($answer);
    }




    public function registerUser()
    {
        extract($_POST);
        $answer = array();

        if ($password == $passwordVerify) {

            $utilities = new Utilities();
            $passEncrypt = $utilities->encriptPassword($password, 10, 22); //password encripted


            $sqlRegister = "INSERT INTO usuario (id_persona, correo, contrasena, id_rol, nombres, apellidos, cedula, direccion, pais, celular) VALUES (null,'$inputEmail', '$passEncrypt' ,  2, '$user_name', '$lastName', '$cedula', '$direccion_usuario', '$pais', '$celular')";
            $sql = $this->execute($sqlRegister);
            if ($sql != null) {
                $answer['typeAnswer'] = true;
            }
        } else {
            $answer['typeAnswer'] = false;
        }
        echo json_encode($answer);
    }
    public function loadImageUser()
    {
        extract($_POST);
        $answer = array();

        $ruta = "../../views/Admin/Files/";
        $sqlUser = "SELECT imagen_usuario FROM usuario WHERE correo = '$userId' ";
        $User = $this->execute($sqlUser);
        $row = $User->fetch_assoc();
        $answer["address"] = $row['imagen_usuario'];
      
        echo json_encode($answer);
    }

    public function forgotPassword()
    {   
        extract($_POST);
        $answer = array();
        
        $obtain = $this->getConnection();
        $email_user = $obtain->real_escape_string($_POST['email_user']);
        $sql = "SELECT contrasena FROM usuario WHERE correo = '$email_user' ";
        $answerQuery = $this->execute($sql);
        if ($answerQuery) {

            $fila = $answerQuery->fetch_assoc();
            $contrabd = $fila['contrasena'];

            $sql = "UPDATE usuario SET codigo_recuperacion  = '" . md5($contrabd) . "' WHERE correo = '" . $email_user . "' ";
            $answer = $this->execute($sql);
         
            $projectName = explode('/', $_SERVER['REQUEST_URI'])[2];
            $projectName = "/".explode('/', $_SERVER['REQUEST_URI'])[1]."/".$projectName;

            $link = "http://".$_SERVER['HTTP_HOST'].$projectName."?ptk=".md5($contrabd)."&p2=".$_POST['email_user'];
               
            	$asunto = "Recuperación de Contraseña";

                $mens_email = file_get_contents("http://".$_SERVER['HTTP_HOST'].$projectName."/views/Session/correo.html");
                
    
                $mens_email = str_replace("<ENLACE>", $link, $mens_email);
            	$cabecera = "MIME-Version: 1.0"."\r\n"."Content-type:text/html;charset=UTF-8"."\r\n"."From: jose.jdgo97@gmail.com"."\r\n"."Reply-To: jose.jdgo97@gmail.com"."\r\n"."X-Mailer: PHP/" . phpversion();

            	mail($email_user, $asunto, $mens_email, $cabecera);

            	$mensaje = "Se ha enviado un correo a su bandeja de entrada. Por favor verifique su correo.";
            	$mensaje .= "<br><br>".$link;
                $answer['typeAnswer'] = true;
        } else {
          $answer['typeAnswer'] = false;
        }
        echo json_encode($answer);
    }

    public function closeSession()
    {
        @session_unset();
        @session_destroy();
    }
}
