
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
    public function editPasswordEmail()
    {
        extract($_POST);
        $typeAnswer = "error";
        $message = "la contrasena actual no es correctassss";
        $sqlVerify = "SELECT contrasena FROM usuario WHERE correo = '$email'";
        $sql = $this->execute($sqlVerify);

        if (mysqli_num_rows($sql) != 0) {
            $row = $sql->fetch_assoc();
            $password = $row['contrasena'];

                if ($new_password == $confirm_password) {

                    $utilities = new Utilities();
                    $passEncrypt = $utilities->encriptPassword($new_password, 10, 22); //contraseña encriptada

                    $sqlUpdate = "UPDATE usuario SET contrasena ='$passEncrypt' WHERE correo ='$email'";

                    $sql = $this->execute($sqlUpdate);
                    if ($sql != 0) {
                        $typeAnswer = "success";
                        $message = "Cambio de contraseña exitoso";
                    }
                } else {
                    $typeAnswer = "warning";
                    $message = " las contraseñas no coiciden";
                }
        }
        echo json_encode(array("typeAnswer" => $typeAnswer, "message" => $message));
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
    public function editName()
    {
        extract($_POST);
        $sql = "UPDATE usuario SET nombres = '$name_user', apellidos = '$lastName' WHERE correo = '$email' ";
        $sqlUser = $this->execute($sql);

        $sql = "SELECT * FROM usuario WHERE correo = '$email' ";
        $sqlUser = $this->execute($sql);

        $row = $sqlUser->fetch_assoc();
        $nombre = $row['nombres'];
        $apellido = $row['apellidos'];

        $typeAnswer = "success";
        echo json_encode(array('typeAnswer' => $typeAnswer, 'message'=> 'Nombre Cambiado exitosamente','nombre' => $nombre, 'apellido' => $apellido));
    }
    public function editEmail()
    {
        extract($_POST);
        $obtain = $this->getConnection();
        $answer = array();
        $upDateEmail = $obtain->real_escape_string($_POST["upDateEmail"]);
        $sql = "UPDATE usuario SET correo = '$upDateEmail' WHERE correo ='$email'";
        $sqlEmail = $this->execute($sql);

        if ($sqlEmail != 0) {
            unset($_SESSION['correo_login']);
            $_SESSION['correo_login'] = $upDateEmail;
            $answer['typeAnswer'] = "success";
        }
        echo json_encode($answer);
    }
    public function editPassword()
    {
        extract($_POST);
        $typeAnswer = "error";
        $message = "la contrasena actual no es correcta";

        $sqlVerify = "SELECT contrasena FROM usuario WHERE correo = '$email'";
        $sql = $this->execute($sqlVerify);

        if (mysqli_num_rows($sql) != 0) {
            $row = $sql->fetch_assoc();
            $password = $row['contrasena'];
            if (password_verify($actual_password, $password)) {

                if ($new_password == $confirm_password) {

                    $utilities = new Utilities();
                    $passEncrypt = $utilities->encriptPassword($new_password, 10, 22); //contraseña encriptada

                    $sqlUpdate = "UPDATE usuario SET contrasena ='$passEncrypt' WHERE correo ='$email'";

                    $sql = $this->execute($sqlUpdate);
                    if ($sql != 0) {
                        $typeAnswer = "success";
                        $message = "Cambio de contraseña exitoso";
                    }
                } else {
                    $typeAnswer = "warning";
                    $message = " las contraseñas no coiciden";
                }
            }
        }
        echo json_encode(array("typeAnswer" => $typeAnswer, "message" => $message));
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
            	$estructura = "MIME-Version: 1.0"."\r\n";
                $estructura.= "Content-type:text/html;charset=UTF-8"."\r\n";
                $estructura.= "From: jose.jdgo97@gmail.com"."\r\n";
                $estructura.= "=Reply-To: jose.jdgo97@gmail.com";
                $estructura.= "\r\n"."X-Mailer: PHP/" . phpversion();

            	$m = mail($email_user, $asunto, $mens_email, $estructura);
                // var_dump($m);
               echo "".$estructura;

            	$mensaje = "Se ha enviado un correo a su bandeja de entrada. Por favor verifique su correo.";
            	$mensaje .= "<br><br>".$link;
                // $answer['typeAnswer'] = true;
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
