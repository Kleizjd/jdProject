<?php
include_once "helpers.php";
extract($_POST);


if (!empty($_POST)) {

    if (isset($module) && isset($controller) && isset($nameFunction)) {
        if (is_dir("../../app/Controllers/" . $module)) {

            if (file_exists("../../app/Controllers/" . $module . "/" . $controller . ".controller.php")) {
                include_once "../../app/Controllers/" . $module . "/" . $controller . ".controller.php";

                $className = $controller;

                $objController = new $className();

                if (method_exists($objController, $nameFunction)) {
                    $objController->$nameFunction();
                } else {
                    die("La función especificada no existe");
                }
            } else {
                die("El controlador especificado no existe");
            }
        } else {
            die("El módulo especificado no existe");
        }
    } else {

        if (!isset($module)) {
            die("El módulo no esta definido");
        } else if (!isset($controlador)) {
            die("El controlador no esta definido");
        } else if (!isset($funcion)) {
            die("La función no esta definida");
        }
    }
} else {
    if ($_SESSION['rol_usuario'] == 2) {
        include_once "../../views/helpUser/askForHelp.php";
    }
}
