<?php
@session_start();
include_once "../../app/Controllers/Utilities/Utilities.controller.php";
include_once "../../config/connection.php";

class Admin extends connection
{
    public function viewOwnAcount()
    {
        extract($_POST);
        $ruta = "../../views/Admin/Files/";
        $sqlAdmin = "SELECT * FROM usuario WHERE correo = '$userId' ";
        $Admin = $this->execute($sqlAdmin);

        include_once "../../views/Admin/view.Admin.php";
    }
 
    
}
