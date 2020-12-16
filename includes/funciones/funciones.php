<?php 

//Obtener la pagina actual
function obtenerPaginaActual() {

    $archivo = basename($_SERVER["PHP_SELF"]);

    $pagina = str_replace(".php", "", $archivo);

    return $pagina;
}

//Consultas
//Obtener todos los proyectos
function obtenerProyectos(){
    include("conexion.php");
    try{
        return $conn->query("SELECT id, nombre FROM proyectos");
    }catch(Exception $e){
        echo "Error!: " . $e->getMessage();
        return false;
    }
}

//Obtener el nombre del proyecto
function obtenerNombre($id = null){
    include("conexion.php");
    
    try{
        $resultado = $conn->query("SELECT nombre FROM proyectos WHERE id = {$id}");
        return $resultado;
    }catch(Exception $e){
        echo "Error!: " . $e->getMessage();
        return false;
    }

}

//Obtener las tareas del proyecto
function obtenerTareasProyectos($id=null){
    include("conexion.php");
    try{
        return $conn->query("SELECT id, tarea, estado FROM tareas WHERE id_proyecto = {$id}");
    }catch(Exception $e){
        echo "Error!: " . $e->getMessage();
        return false;
    }
}