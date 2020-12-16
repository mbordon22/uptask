<?php

$accion = $_REQUEST["accion"];
$id_proyecto = isset($_REQUEST["id_proyecto"])? (int) $_REQUEST["id_proyecto"] : "";
$tarea = isset($_REQUEST["tarea"])? $_REQUEST["tarea"] : "";
$estado = isset($_REQUEST["estado"])? (int) $_REQUEST["estado"] : "";
$id_tarea = isset($_REQUEST["id_tarea"])? (int) $_REQUEST["id_tarea"] : "";

if($accion == "crear"){
    //Incluimos la conexion a la base de datos
    include("../funciones/conexion.php");
    
    try{
    
        //Insertamos los datos en la base de datos
        $stmt = $conn->prepare(" INSERT INTO tareas (tarea, id_proyecto) VALUES (?, ?) ");
        $stmt->bind_param("si", $tarea, $id_proyecto);

        //Ejecutamos
        $stmt->execute();

        //Mandamos nuestra respuesta en caso que se inserte correctamente

        if($stmt->affected_rows > 0){
            
            $respuesta = array(
                "respuesta" => "correcto",
                "id_insertado" => $stmt->insert_id,
                "tipo" => $accion,
                "tarea" => $tarea
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



}elseif($accion == "actualizar"){
    //Incluimos la conexion a la base de datos
    include("../funciones/conexion.php");
    
    try{
    
        //Insertamos los datos en la base de datos
        $stmt = $conn->prepare(" UPDATE tareas SET estado = ? WHERE id = ? ");
        $stmt->bind_param("ii", $estado, $id_tarea);

        //Ejecutamos
        $stmt->execute();

        //Mandamos nuestra respuesta en caso que se inserte correctamente

        if($stmt->affected_rows > 0){
            
            $respuesta = array(
                "respuesta" => "correcto",
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
}elseif($accion == "borrar"){
    //Incluimos la conexion a la base de datos
    include("../funciones/conexion.php");
    
    try{
    
        //Eliminamos de la base de datos la tarea con el id seleccionado.
        $stmt = $conn->prepare(" DELETE FROM tareas WHERE id = ? ");
        $stmt->bind_param("i", $id_tarea);

        //Ejecutamos
        $stmt->execute();

        //Mandamos nuestra respuesta en caso que se elimine correctamente

        if($stmt->affected_rows > 0){
            
            $respuesta = array(
                "respuesta" => "correcto"
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