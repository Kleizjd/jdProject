<table class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline">
<?php  
    @session_start();

   foreach($datos as $dato){
        echo"<tr>";
            echo"<td>Usuario que Crea</td>";
            echo"<td colspan='3'>".$dato["Nombre_Usuario"]."</td>";
        echo"</tr>";
        
        echo"<tr>";
                    echo"<td>Usuario que Modifica</td><td>".$usuModifica."</td>";
                    echo"<td>Fecha</td><td>".$fechaModifica."</td>";     
        echo"</tr>";

        echo"<tr>";
                    echo"<td>Usuario que Anula</td><td>".$usuElimina."</td>";
                    echo"<td>Fecha</td><td>".$fechaElimina."</td>";                    
        echo"</tr>";

        echo"<tr>";
            echo"<td>Por que Anulo</td>";
            echo"<td colspan='3'>".$dato["Razon_Anula"]."</td>";
        echo"</tr>";

        echo"<tr>";
            echo"<td>Observaciones</td>";
            echo"<td colspan='3'>".$Observaciones."</td>";
        echo"</tr>";
    }
            
?>
</table>
