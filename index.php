<?php 
    include_once("includes/funciones/sesiones.php");
    include_once("includes/funciones/funciones.php");
    include_once("includes/templates/header.php"); 
    include_once("includes/templates/barra.php");


    //Obtener el ID de la barra
    if(isset($_GET["id_proyecto"])){
        $id_proyecto = $_GET["id_proyecto"];
    }else{
        $id_proyecto = "";
    }
?>


<div class="contenedor">
    
<?php
    include_once("includes/templates/sidebar.php");
?>

    <main class="contenido-principal">
    <?php 
        $proyecto = obtenerNombre($id_proyecto);
        if($proyecto):
    ?>            
    
        <h1>
            <?php 
                foreach($proyecto as $nombre):       
            ?>

            <span><?php echo $nombre["nombre"] ?></span>

            <?php endforeach; ?>
        </h1>

        <form action="#" class="agregar-tarea">
            <div class="campo">
                <label for="tarea">Tarea:</label>
                <input type="text" placeholder="Nombre Tarea" class="nombre-tarea"> 
            </div>
            <div class="campo enviar">
                <input type="hidden" id="id_proyecto" value="<?php echo $id_proyecto; ?>">
                <input type="submit" class="boton nueva-tarea" value="Agregar">
            </div>
        </form>
    
    <?php else:

        echo "<p> Debes elegir un Proyecto de la izquierda para agregar Tareas </p>";
    
        endif;?>
        
 

        <h2>Listado de tareas:</h2>

        <div class="listado-pendientes">
            <ul>
                    <?php 
                    //Obtiene las tareas del proyecto actual
                    $tareas = obtenerTareasProyectos($id_proyecto);
                    if(is_object($tareas)):
                        if($tareas->num_rows > 0):
                            //Si hay tareas.?>
                            <?php foreach($tareas as $tarea):?>

                            <li id="tarea:<?php echo $tarea['id'] ?>" class="tarea">
                                <p><?php echo $tarea["tarea"]; ?></p>
                                <div class="acciones">
                                    <i class="far fa-check-circle <?php echo ($tarea["estado"]) == 1 ? 'completo' : ''; ?>"></i>
                                    <i class="fas fa-trash"></i>
                                </div>
                            </li> 

                            <?php endforeach; ?>

                        <?php else:
                        //No hay tareas.
                        echo "<p class='lista-vacia'>No hay tareas en este proyecto.</p>";
                         
                        endif;    
                    else:
                        //No hay tareas.
                        echo "<p class='lista-vacia'>No hay tareas en este proyecto.</p>";
                        
                    endif;
                    ?>
                 
            </ul>
        </div>
        <div>
                    <h2>Porcentaje Completado del Proyecto</h2>
                    <div id="barra-progreso" class="barra-progreso">
                        <div id="porcentaje" class="porcentaje"></div>
                    </div>
        </div>
    </main>
</div><!--.contenedor-->

<?php include_once("includes/templates/footer.php"); ?>