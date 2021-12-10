<?php
@session_start();
include_once "../../app/Controllers/Utilities/Utilities.controller.php";
include_once "../../config/connection.php";

class Admin extends connection
{
    public function viewAcount()
    {
        extract($_POST);
        $ruta = "../../views/Admin/Files/";
        $sqlAdmin = "SELECT * FROM usuario WHERE correo = '$userId' ";
        $Admin = $this->execute($sqlAdmin);

        include_once "../../views/Admin/view.Admin.php";
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
}
