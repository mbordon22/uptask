<?php 
$accion = isset($_REQUEST["accion"]) ? $_REQUEST["accion"]  : "";
$idUsuario = isset($_REQUEST["idUsuario"]) ? $_REQUEST["idUsuario"]  : "";
$proyecto = isset($_REQUEST["proyecto"]) ? $_REQUEST["proyecto"]  : "";
$idProyecto = isset($_REQUEST["idProyecto"]) ? $_REQUEST["idProyecto"] : "";

if($accion == "crear"){
    //Incluimos la conexion a la base de datos
    include_once("../funciones/conexion.php");
    
    try{
    
        //Insertamos los datos en la base de datos
        $stmt = $conn->prepare(" INSERT INTO proyectos (idUsuario, nombre) VALUES (?,?) ");
        $stmt->bind_param("is",$idUsuario, $proyecto);

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

//Borrar proyecto
else if($accion == "borrar"){

    //Incluimos la conexion a la base de datos
    include_once("../funciones/conexion.php");
    
    try{
        //Comprobamos si hay tareas pendientes en el proyecto
        $tareasPendientes =  $conn->query(" SELECT * FROM tareas WHERE id_proyecto = ${idProyecto} ");

        $tareas = $tareasPendientes->num_rows;
        
        if($tareasPendientes->num_rows > 0){
            //Eliminamos todas las tareas pendientes
            $stmt = $conn->prepare(" DELETE FROM tareas WHERE id_proyecto = ? ");    
            $stmt->bind_param("i", $idProyecto);

            //Ejecutamos el delete
            $stmt->execute();

            //Cuando ya borremos todas las tareas borramos el proyecto
            if($stmt->affected_rows > 0){

                //Borramos el proyecto
                $stmt2 = $conn->prepare(" DELETE FROM proyectos WHERE id = ? ");
                $stmt2->bind_param("i", $idProyecto);

                //Ejecutamos el delete
                $stmt2->execute();

                //Mandamos nuestra respuesta en caso que se borre correctamente
                if($stmt2->affected_rows > 0){
            
                    $respuesta = array(
                        "respuesta" => "correcto",
                        "tipo" => $accion
                    );
        
                }

                //Cerramos la conexión
                $stmt2->close();

            }

            //Cerramos la conexión
            $stmt->close();
            $conn->close();

        }else{
            //Borramos el proyecto
            $stmt = $conn->prepare(" DELETE FROM proyectos WHERE id = ? ");
            $stmt->bind_param("i", $idProyecto);

            //Ejecutamos el delete
            $stmt->execute();

            //Mandamos nuestra respuesta en caso que se borre correctamente
            if($stmt->affected_rows > 0){
        
                $respuesta = array(
                    "respuesta" => "correcto",
                    "tipo" => $accion
                );
    
            }

            //Cerramos la conexión
            $stmt->close();
            $conn->close();
        }

        echo json_encode($respuesta);
        
        
    }catch(Exception $e){
        
        //En caso de un error, tomar la exepcion.
        $respuesta = array(
            "error" => $e->getMessage()
        );

        echo json_encode($respuesta);
    }
}