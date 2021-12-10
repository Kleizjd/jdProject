
<?php

include_once "../../config/connection.php";

class HelpUser extends connection
{

    public function viewHelpUser()
    {
        $sqlNotificaciones = "SELECT count(*) as notificaciones FROM reunion WHERE (DATEDIFF(fecha,NOW())) <= '3'";
        $notification = $this->execute($sqlNotificaciones);
       $sqlDelete = "DELETE FROM reunion WHERE (DATEDIFF(fecha,NOW())) >= '1'";
       $notificationDelete = $this->execute($sqlDelete);
        
        
       
        include_once "../../views/HelpUser/view.modalSearchHelpUser.php";
    }
    public function modalSearchHelpUser(){
        // $ruta = "../../views/Admin/Files/";
        include_once "../../views/HelpUser/view.modalSearchHelpUser.php";
    }

    public function generateAlarm(){
        $answer = false;
        $sql = "INSERT INTO reunion (fecha, titulo, descripcion, correo) VALUES (CURDATE(), 'AYUDA', 'Me estan maltratando','".$_SESSION["correo_login"]."')";

        $sqlNoti = $this->execute($sql);
        if ($sqlNoti != 0) {
            $answer = true;
        }
        echo json_encode(array("typeAnswer" => $answer));

    }
    public function notificationVerify(){
        $answer = false;
        $sqlNotificaciones = "SELECT count(*) as notificaciones FROM reunion WHERE (DATEDIFF(fecha,NOW())) <= '0    '";
        $sqlNoti = $this->execute($sqlNotificaciones);
        $row = $sqlNoti->fetch_assoc();
        $numero = $row['notificaciones'];

        
        if ($numero != 0) {
            $answer = true;
        }
        echo json_encode(array("typeAnswer" => $answer));
    }
   
    public function viewGetHelpUser()
    {
        $datos = array(); 

        $sqlMeeting = "SELECT * FROM reunion WHERE (DATEDIFF(fecha,NOW())) <= '0' ORDER BY Id DESC ";
        // echo "SELECT titulo, descripcion, fecha FROM reunion WHERE (DATEDIFF(fecha,NOW())) <= '0' ORDER BY Id DESC";
        $sql = $this->execute($sqlMeeting);
        $sqlNotificaciones = "SELECT count(*) as notificaciones FROM reunion WHERE (DATEDIFF(fecha,NOW())) <= '0    '";
        $sqlNoti = $this->execute($sqlNotificaciones);
        $row = $sqlNoti->fetch_assoc();
        $numero = $row['notificaciones'];
    

        // include_once "../../views/HelpUser/viewHelpUser.php";
         foreach ($sql as $list) {
            array_push($datos, array(
                "correo" => $list["correo"],
                "titulo" => $list["titulo"],
                "descripcion" => $list["descripcion"],
                "fecha" => $list["fecha"],
                "info" => '<button type="button" class="text-white btn btn-info" id="viewWatchHelpUser"><i class="fa fa-eye"></i></button>',

            ));
        }
            $table = array("data" => $datos, "cantNotificaciones" => $numero);
            // var_dump($datos);

            echo json_encode($table);
    }
    public function viewWatchHelpUser()
    {
        extract($_POST);
        // $listUserInfo = array();
        // $listUserInfo = $this->execute("SELECT CONCAT(nombres, ' ', apellidos) AS nombre_completo, imagen_usuario, usuario.correo, usuario.direccion FROM usuario, reunion WHERE usuario.correo = reunion.correo AND usuario.correo = '$correo'"); 
        $listUserInfo = $this->execute("SELECT CONCAT(nombres, ' ', apellidos) AS nombre_completo, imagen_usuario, usuario.correo, usuario.direccion, celular, descripcion_persona FROM usuario, reunion WHERE usuario.correo = reunion.correo AND usuario.correo = '$correo'"); 

        // echo "SELECT CONCAT(nombres, ' ', apellidos) AS nombre_completo FROM usuario, reunion WHERE usuario.correo = reunion.correo AND usuario.correo = '$correo'"; 
        // SELECT * FROM usuario, reunion WHERE usuario.correo = 'jose.jdgo97@gmail.com'
        // SELECT * FROM usuario AS u, reunion AS r WHERE u.correo = 'jose.jdgo97@gmail.com'
        include_once "../../views/HelpUser/viewHelpUser.php";
        // include_once "../../views/HelpUser/view.WatchUserHelp.php";
        

    }
   
}
