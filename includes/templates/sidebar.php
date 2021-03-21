<aside class="contenedor-proyectos">
    <div class="panel crear-proyecto">
        <!-- Crear nuevo proyecto -->
        <button type="button" class="boton" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Nuevo Proyecto<i class="fas fa-plus"></i>
        </button>
        <input type="hidden" id="idUsuario" value="<?php echo $_SESSION["id"]; ?>"><!-- Enviamos el id del usuario que sera dueño del proyecto -->
    </div>

    <div class="panel lista-proyectos">
        <h2>Proyectos</h2>
        <ul id="proyectos">
            <!-- Obtenemos todos los proyectos de x usuario -->
            <?php
            $proyectos = obtenerProyectos($_SESSION["id"]);
            if ($proyectos) {
                foreach ($proyectos as $proyecto) { ?>
                    <li>
                        <a href="index.php?id_proyecto=<?php echo $proyecto["id"] ?>" id="proyecto:<?php echo $proyecto["id"] ?>">
                            <?php echo $proyecto["nombre"]; ?>
                        </a>
                    </li>
            <?php }
            } ?>
        </ul>
    </div>


    <!-- Button trigger modal -->


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Nuevo Proyecto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" class="form">
                    <div class="modal-body">
                        <form-group>
                            <label for="nombreProyecto" class="mb-2">Nombre del Proyecto</label>
                            <input type="text" name="nombreProyecto" id="nombreProyecto" class="form-control">
                        </form-group>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="crearProyecto">Crear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</aside>