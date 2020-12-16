eventListener();

function eventListener(){

    document.querySelector("#formulario").addEventListener("submit", validarFormulario);

}


function validarFormulario(e){

    e.preventDefault();

    var usuario = document.querySelector("#usuario").value,
        password = document.querySelector("#password").value,
        tipo = document.querySelector("#tipo").value;

    if(usuario === "" || password === ""){

        //La validación falló
        Swal.fire({
            icon: 'error',
            title: 'Error!!',
            text: 'Los dos campos deben ser llenados',
          });
    
    }else{
        //Ambos campos son correctos. Mandamos a ejecutar AJAX.

        //Datos que se envian al servidor.
        var datos = new FormData();
        datos.append("usuario", usuario);
        datos.append("password", password);
        datos.append("tipo", tipo);
        
        //Crear el llamado a ajax

        var xhr = new XMLHttpRequest();

        //Abrir la conexion.
        xhr.open("POST", "includes/modelos/modelo-admin.php", true);

        //Retorno de datos
        xhr.onload = function(){
            if(this.status === 200){

                //retorno de datos en formato json

                var respuesta = JSON.parse(xhr.responseText);
                
                //Si la respuesta es correcta
                if(respuesta.respuesta === "correcto"){
                    //Si es nuevo usuario.
                    if(respuesta.tipo === "crear"){

                        Swal.fire({
                            icon: 'success',
                            title: 'Usuario Creado',
                            text: 'El usuario fue creado correctamente',
                          })
                          .then(resultado => {
                            if(resultado.value){
                                window.location.href = "login.php";
                            }
                          })

                    }else if(respuesta.tipo === "login"){
                        //Si el usuario ya existe
                        Swal.fire({
                            icon: 'success',
                            title: 'Correcto',
                            text: 'Presione OK para ingresar',
                          })
                          .then(resultado => {
                              if(resultado.value){
                                  window.location.href = "index.php";
                              }
                          })


                    }

                }else{
                    //Hubo un error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!!',
                        text: 'Hubo un error',
                      });
                }
            }
        }

        //Enviar peticion
        xhr.send(datos);

    }
}