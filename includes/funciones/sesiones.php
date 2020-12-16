<?php 
//Verificamos que el usuario este autenticado
function usuario_autenticado(){
    if(!revisarUsuario()){
        header("Location: login.php");
    }
}

//Revisamos que haya iniciado sesion.
function revisarUsuario(){
    return isset($_SESSION["nombre"]);
}

session_start();
usuario_autenticado();