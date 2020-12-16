<?php 
$accion = $_REQUEST["accion"];
$proyecto = $_REQUEST["proyecto"];

if($accion == "crear"){
    //Incluimos la conexion a la base de datos
    include_once("../funciones/conexion.php");
    
    try{
    
        //Insertamos los datos en la base de datos
        $stmt = $conn->prepare(" INSERT INTO proyectos (nombre) VALUES (?) ");
        $stmt->bind_param("s", $proyecto);

        //Ejecutamos
        $stmt->execute();

        //Mandamos nuestra respuesta en caso que se inserte correctamente

        if($stmt->affected_rows > 0){
            
            $respuesta = array(
                "respuesta" => "correcto",
                "id_insertado" => $stmt->insert_id,
                "tipo" => $accion,
                "nombre_proyecto" => $proyecto
            );

        }

        //Cerramos la conexión
        $stmt->close();
        $conn->close();

        echo json_encode($respuesta);
        
    }catch(Exception $e){
        
        //En caso de un error, tomar la exepcion.
        $respuesta = array(
            "error" => $e->getMessage()
        );

        echo json_encode($respuesta);
    }
}