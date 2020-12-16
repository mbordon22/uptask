<?php 
$accion = $_POST["tipo"];
$password = $_POST["password"];
$usuario = $_POST["usuario"];

if($accion == "crear"){
    //Crear administradores

    ///Hashear  password

    $opcion = array(
        "cost" => 12
    );

    $hash_password = password_hash($password, PASSWORD_BCRYPT, $opcion);

    //Incluimos la conexion a la base de datos
    include_once("../funciones/conexion.php");
    
    try{
    
        //Insertamos los datos en la base de datos
        $stmt = $conn->prepare(" INSERT INTO usuarios (usuario, password) VALUES (?, ?) ");
        $stmt->bind_param("ss", $usuario, $hash_password);

        //Ejecutamos
        $stmt->execute();

        //Mandamos nuestra respuesta en caso que se inserte correctamente

        if($stmt->affected_rows > 0){
            
            $respuesta = array(
                "respuesta" => "correcto",
                "id_insertado" => $stmt->insert_id,
                "tipo" => $accion
            );

        }

        //Cerramos la conexión
        $stmt->close();
        $conn->close();

        echo json_encode($respuesta);
        
    }catch(Exception $e){
        
        //En caso de un error, tomar la exepcion.
        $respuesta = array(
            "pass" => $e->getMessage()
        );

        echo json_encode($respuesta);
    }



}elseif($accion == "login"){
    //Si el admin se loguea.
    //Importo la conexion a la base de datos
    include_once("../funciones/conexion.php");

    try{

        //Seleccionar el administrador de la base de datos.
        $stmt = $conn->prepare("SELECT usuario, id, password FROM usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();

        //Obtenemos los valores
        $stmt->bind_result($nombre_usuario, $id_usuario, $pass_usuario);
        $stmt->fetch();
        if($nombre_usuario){
            //Si el usuario existe
            
            if(password_verify($password, $pass_usuario)){
                //Password correcto

                session_start();
                $_SESSION["nombre"] = $nombre_usuario;
                $_SESSION["id"] = $id_usuario;
                $_SESSION["login"] = true;

                $respuesta = array(
                    "respuesta" => "correcto",
                    "usuario" => $nombre_usuario,
                    "tipo" => $accion
                );
            }else{
                //Password incorrecto
                $respuesta = array(
                    "resultado" => "Usuario o Password Incorrecto"
                );
            }

        }else{
            //Si el usuario no existe
            $respuesta = array(
                "error" => "Usuario no encontrado"
            );
        }

        //Cerramos la conexion.
        $stmt->close();
        $conn->close();

        echo json_encode($respuesta);
    }catch(Exception $e){
        
        //En caso de un error, tomar la exepcion.
        $respuesta = array(
            "pass" => $e->getMessage()
        );

        echo json_encode($respuesta);
    }
}
?>