<?php 
    session_start();
    include_once("includes/funciones/funciones.php");
    include_once("includes/templates/header.php"); 

    //Si viene la variable por medio del GET cerramos sesión
    if(isset($_GET["cerrar_sesion"])){
        $_SESSION = array();
    }
?>

    <div class="contenedor-formulario">
        <h1>UpTask</h1>
        <form id="formulario" class="caja-login" method="post">
            <div class="campo">
                <label for="usuario">Usuario: </label>
                <input type="text" name="usuario" id="usuario" placeholder="Usuario" value="admin">
            </div>
            <div class="campo">
                <label for="password">Password: </label>
                <input type="password" name="password" id="password" placeholder="Password" value="admin123">
            </div>
            <div class="campo enviar">
                <input type="hidden" id="tipo" value="login">
                <input type="submit" class="boton" value="Iniciar Sesión">
            </div>

            <div class="campo">
                <a href="crear-cuenta.php">Crea una cuenta nueva</a>
            </div>
        </form>
    </div>

    <?php include_once("includes/templates/footer.php"); ?>