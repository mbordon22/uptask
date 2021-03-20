<aside class="contenedor-proyectos">
    <div class="panel crear-proyecto">
        <!-- Crear nuevo proyecto -->
        <a href="#" class="boton">Nuevo Proyecto<i class="fas fa-plus"></i> </a>
        <input type="hidden" id="idUsuario" value="<?php echo $_SESSION["id"]; ?>"><!-- Enviamos el id del usuario que sera dueño del proyecto -->
    </div>

    <div class="panel lista-proyectos">
        <h2>Proyectos</h2>
        <ul id="proyectos">
        <!-- Obtenemos todos los proyectos de x usuario -->
           <?php 
            $proyectos = obtenerProyectos($_SESSION["id"]);
            if($proyectos){
                foreach($proyectos as $proyecto){?>
                    <li>
                        <a href="index.php?id_proyecto=<?php echo $proyecto["id"]?>" id="proyecto:<?php echo $proyecto["id"] ?>" >
                            <?php echo $proyecto["nombre"];?>
                        </a>
                    </li>
                <?php }
            }?>
        </ul>
    </div>
</aside>