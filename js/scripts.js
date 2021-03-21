eventListener();
//Lista de proyectos
var listaProyectos = document.querySelector("ul#proyectos");

function eventListener() {

    if(document.querySelector("#crearProyecto")){
        document.querySelector("#crearProyecto").addEventListener("click", guardarProyectoDB);
    }

    //Boton para agregar nueva Tarea
    if (document.querySelector(".nueva-tarea")) {
        document.querySelector(".nueva-tarea").addEventListener("click", nuevaTarea);
    }

    //Botones para las acciones de las tareas
    if(document.querySelector(".listado-pendientes")){
        document.querySelector(".listado-pendientes").addEventListener("click", accionesTarea);
    }
    
    if(document.querySelector("#borrarProyecto")){
        //Eliminar proyecto
        document.querySelector("#borrarProyecto").addEventListener("click", borrarProyecto);
        //Progreso del proyecto al ingresar
        document.addEventListener("DOMContentLoaded", progresoProyecto);
    }
    
}


function guardarProyectoDB(e) {
    e.preventDefault();
    //Recibimos las variables
    const idUsuario = document.querySelector("#idUsuario").value;
    const nombreProyecto = document.querySelector("#nombreProyecto").value;

    //Crear llamado a ajax
    var xhr = new XMLHttpRequest();

    //Enviar datos por formdata
    var datos = new FormData();
    datos.append("proyecto", nombreProyecto);
    datos.append("idUsuario", idUsuario);
    datos.append("accion", "crear");

    //Abrir la conexion
    xhr.open("POST", "includes/modelos/modelo-proyecto.php", true);

    //En la carga
    xhr.onload = function () {
        if (this.status === 200) {

            //Obtener datos de la respuesta
            var respuesta = JSON.parse(xhr.responseText);
            var proyecto = respuesta.nombre_proyecto,
                id_proyecto = respuesta.id_insertado,
                tipo = respuesta.tipo,
                resultado = respuesta.respuesta;

            if (resultado === "correcto") {
                //Fue exitoso.
                if (tipo === "crear") {
                    //Se creo un nuevo proyecto.
                    //Inyectar en el HTML
                    var nuevoProyecto = document.createElement("li");
                    nuevoProyecto.innerHTML = `
                    <a href="index.php?id_proyecto=${id_proyecto}" id="proyecto:${id_proyecto}">
                        ${proyecto}
                    </a>
                    `;
                    listaProyectos.appendChild(nuevoProyecto);

                    Swal.fire({
                            icon: 'success',
                            title: 'Proyecto ' + proyecto + 'Creado',
                            text: 'El proyecto fue creado correctamente',
                        })
                        .then(resultado => {
                            if (resultado.value) {
                                //Redireccionar al nuevo proyecto
                                window.location.href = "index.php?id_proyecto=" + id_proyecto;
                            }
                        })

                } else {
                    //Se actualizo o se elimino
                }
            } else {
                //Hubo un error.
                Swal.fire({
                    icon: 'error',
                    title: 'Error!!',
                    text: 'Hubo un error',
                });
            }
        }
    }

    //Enviar el Request
    xhr.send(datos);
}


//Funcion Agregar Tarea
function nuevaTarea(e) {
    e.preventDefault();
    var nombreTarea = document.querySelector(".nombre-tarea").value;

    if (nombreTarea === "") {
        //Mostramos mensaje de error.
        Swal.fire({
            icon: 'error',
            title: 'Error!!',
            text: 'La tarea no puede ir vacia',
        });
    } else {
        //Si el nombre de la tarea viene correctamente
        //Crear llamado a ajax
        var xhr = new XMLHttpRequest();

        var datos = new FormData();
        datos.append("tarea", nombreTarea);
        datos.append("accion", "crear");
        datos.append("id_proyecto", document.querySelector("#id_proyecto").value);

        //Abrimos la conexion
        xhr.open("POST", "includes/modelos/modelo-tarea.php", true);

        //Ejecutarlo y respuesta
        xhr.onload = function () {
            if (this.status === 200) {
                //Todo Correcto
                var respuesta = JSON.parse(xhr.responseText);

                if (respuesta.respuesta === "correcto") {
                    //Se agregó correctamente
                    if (respuesta.tipo === "crear") {
                        //Notificamos que se creo correctamente la tarea
                        Swal.fire({
                            icon: 'success',
                            title: 'Tarea Creada',
                            text: 'La tarea ' + respuesta.tarea + " se creo correctamente",
                        });

                        //Si a una lista de tareas vacia se le agrega una tarea, eliminamos el parrafo de lista vacia.
                        var parrafoListaVacia = document.querySelectorAll(".lista-vacia");

                        if (parrafoListaVacia.length > 0) {
                            document.querySelector(".lista-vacia").remove();
                        }

                        //Construir en el template
                        var nuevaTarea = document.createElement("li");

                        //Agregamos el id
                        nuevaTarea.id = "tarea: " + respuesta.id_insertado;

                        //Agregamos la clase tarea
                        nuevaTarea.classList.add("tarea");

                        //Insertra en el HTML
                        nuevaTarea.innerHTML = `
                        <p>${respuesta.tarea}</p>
                        <div class="acciones">
                            <i class="far fa-check-circle"></i>
                            <i class="fas fa-trash"></i>
                        </div>
                        `;

                        //Agregarlo al HTML
                        var listado = document.querySelector(".listado-pendientes ul");
                        listado.appendChild(nuevaTarea);

                        //Limpiar el formulario
                        document.querySelector(".agregar-tarea").reset();


                    }
                } else {
                    //Hubo un error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!!',
                        text: 'Hubo un error',
                    });
                }


                //Cambiamos el progreso del proyecto
                progresoProyecto();
            }
        }

        //Enviar los datos
        xhr.send(datos);
    }
}


//Funciones de las tareas
function accionesTarea(e) {
    e.preventDefault();

    //Si presionamos el icono para cambiar estado
    if (e.target.classList.contains("fa-check-circle")) {

        if (e.target.classList.contains("completo")) {
            e.target.classList.remove("completo");
            cambiarEstadoTarea(e.target, 0);

        } else {
            e.target.classList.add("completo");
            cambiarEstadoTarea(e.target, 1);
        }

        //Si presionamos el icono para eliminar
    } else if (e.target.classList.contains("fa-trash")) {

        Swal.fire({
            title: 'Estas Seguro/a?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, borrar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                var tareaEliminada = e.target.parentElement.parentElement;

                //Borrar de la BD

                EliminarTareaBD(tareaEliminada);

                //Borrar del HTML
                tareaEliminada.remove();

                Swal.fire(
                    'Borrado!',
                    'La tarea fue borrada.',
                    'success'
                )
            }
        })

    }

}

//Cambiar estado de la tarea
function cambiarEstadoTarea(tarea, estado) {

    var idTarea = tarea.parentElement.parentElement.id.split(":")[1];

    //Crear llamado a ajax
    var xhr = new XMLHttpRequest();

    //informacion
    var datos = new FormData();
    datos.append("id_tarea", idTarea);
    datos.append("accion", "actualizar");
    datos.append("estado", estado);

    //abrir la conexion
    xhr.open("POST", "includes/modelos/modelo-tarea.php", true);

    //onload
    xhr.onload = function () {
        if (this.status === 200) {
            var respuesta = JSON.parse(xhr.responseText);

            //Cambiamos el progreso del proyecto
            progresoProyecto();
        }
    }

    //enviar la peticion
    xhr.send(datos);
}

//Eliminar una tarea de la bd
function EliminarTareaBD(tarea) {
    var idTarea = tarea.id.split(":")[1];

    //Crear llamado a ajax
    var xhr = new XMLHttpRequest();

    //informacion
    var datos = new FormData();
    datos.append("id_tarea", idTarea);
    datos.append("accion", "borrar");

    //abrir la conexion
    xhr.open("POST", "includes/modelos/modelo-tarea.php", true);

    //onload
    xhr.onload = function () {
        if (this.status === 200) {
            var respuesta = JSON.parse(xhr.responseText);
            //console.log(respuesta);

            //Comprobamos si la lista esta vacia o no, en caso de que si mostramos un mensaje
            var listaTareasPendientes = document.querySelectorAll(".tarea");
            if (listaTareasPendientes.length === 0) {
                document.querySelector(".listado-pendientes ul").innerHTML = "<p class='lista-vacia'>No hay tareas en este proyecto.</p>";
            }

            //Cambiamos el progreso del proyecto
            progresoProyecto();
        }
    }

    //enviar la peticion
    xhr.send(datos);
}

//Barra de progreso
function progresoProyecto() {
    //Seleccionamos la cantidad de tareas
    const cantidadTareas = document.querySelectorAll(".tarea");
    /* console.log(cantidadTareas.length); */

    //Seleccionamos la cantidad de tareas terminadas
    const cantidadTareasTerminadas = document.querySelectorAll("i.completo");
    /* console.log(cantidadTareasTerminadas.length); */

    //Progreso actual
    const progresoActual = Math.round((cantidadTareasTerminadas.length / cantidadTareas.length) * 100);
    /* console.log(progresoActual); */

    //Actualizamos la barra
    document.querySelector("#porcentaje").style.width = progresoActual + "%";

    //Si el proyecto esta terminado mostramos alerta
    if (progresoActual === 100) {
        Swal.fire({
            icon: 'success',
            title: 'Proyecto Completado!!',
            text: 'Ya no tiene tareas pendientes',
        });
    }
}

//Borrar Proyecto
function borrarProyecto() {
    //Guardamos los datos necesarios en variables
    const idUsuario = document.querySelector("#idUsuario").value,
        idProyecto = document.querySelector("#id_proyecto").value;

    //Alerta para confirmar delete del proyecto        
    Swal.fire({
        title: '¿Eliminar proyecto?',
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, borrar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {

            //-----------------Borrar de la BD------------------

            //Crear llamado a ajax
            var xhr = new XMLHttpRequest();

            //Enviar datos por formdata
            var datos = new FormData();
            datos.append("idProyecto", idProyecto);
            datos.append("idUsuario", idUsuario);
            datos.append("accion", "borrar");

            //Abrir la conexion
            xhr.open("POST", "includes/modelos/modelo-proyecto.php", true);

            //En la carga
            xhr.onload = function () {
                if (this.status === 200) {

                    //Obtener datos de la respuesta
                    var respuesta = JSON.parse(xhr.responseText);
                    var resultado = respuesta.respuesta;
                    console.log(respuesta);

                    if (resultado === "correcto") {
                        //Fue exitoso.

                        Swal.fire({
                                icon: 'success',
                                title: 'Proyecto eliminado',
                                text: 'El proyecto fue eliminado correctamente',
                            })
                            .then(resultado => {
                                if (resultado.value) {
                                    //Redireccionar al inicio
                                    window.location.href = "index.php";
                                }
                            })

                    } else {
                        //Hubo un error.
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!!',
                            text: 'Hubo un error',
                        });
                    }
                }
            }

            //Enviar el Request
            xhr.send(datos);
        }
    })


}