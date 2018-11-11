<!doctype html>
<html lang="es" class="h-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons">
        <link rel="stylesheet" href="https://unpkg.com/bootstrap-material-design@4.1.1/dist/css/bootstrap-material-design.min.css" integrity="sha384-wXznGJNEXNG1NFsbm0ugrLFMQPWswR3lds2VeinahP8N0zJw9VWSopbjv2x7WCvX" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/solid.css" integrity="sha384-VGP9aw4WtGH/uPAOseYxZ+Vz/vaTb1ehm1bwx92Fm8dTrE+3boLfF1SpAtB1z7HW" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/fontawesome.css" integrity="sha384-1rquJLNOM3ijoueaaeS5m+McXPJCGdr5HcA03/VHXxcp2kX2sUrQDmFc3jR5i/C7" crossorigin="anonymous">
        <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>css/datepicker.css">
        <title>Turnos</title>
        <base href="<?php echo Config::get('URL'); ?>">
    </head>
    <body class="h-100">
        <div class="fixed-top">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="#">CALENDARIO DE TURNOS</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item"><a class="nav-link" href="#" id="boton.turno">Ingresar datos</a></li>
                        <li class="nav-item"><a class="nav-link" href="#" id="boton.profesionales">Asignar turnos</a></li>
                        <li class="nav-item"><a class="nav-link" href="#" id="boton.pormes">Ver turnos por mes</a></li>
                        <li class="nav-item"><a class="nav-link" href="login/logout">Salir del programa</a></li>
                    </ul>
                </div>
            </nav>
            <div class="card">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col"><p>MES</p></div>
                            <div class="col">
                                <select id="fecha.mes" class="form-control">
                                    <option value="01">Enero</option>
                                    <option value="02">Febrero</option>
                                    <option value="03">Marzo</option>
                                    <option value="04">Abril</option>
                                    <option value="05">Mayo</option>
                                    <option value="06">Junio</option>
                                    <option value="07">Julio</option>
                                    <option value="08">Agosto</option>
                                    <option value="09">Septiembre</option>
                                    <option value="10">Octubre</option>
                                    <option value="11" selected>Noviembre</option>
                                    <option value="12">Diciembre</option>
                                </select>
                            </div>
                            <div class="col">
                                <p>AÑO</p>
                            </div>
                            <div class="col">
                                <select id="fecha.ano" class="form-control">
                                    <option value="2017">2017</option>
                                    <option value="2018" selected>2018</option>
                                    <option value="2019">2019</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <div class="row mt-5 pt-5">
            <div class="col mt-5">
                <div class="card ml-3">
                    <div class="card-body">
                        <table class="table table-td table-hover table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th scope="col">Días / Fecha</th>
                                    <th scope="col">Turno Diurno</th>
                                    <th scope="col">Turno Nocturno</th>
                                    <th scope="col">Comentarios</th>
                                </tr>
                            </thead>
                            <tbody id="table.calendario">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" tabindex="-1" role="dialog" id="dialog.view">
         <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h4 class="modal-title" id="dialog.title"></h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body" id="dialog.body">
               </div>
               <div class="modal-footer" id="dialog.footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
               </div>
            </div>
         </div>
      </div>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script src="https://unpkg.com/popper.js@1.12.6/dist/umd/popper.js" integrity="sha384-fA23ZRQ3G/J53mElWqVJEGJzU0sTs+SvzG8fXVWP+kJQ1lwFAOkcUOysnlKJC33U" crossorigin="anonymous"></script>
      <script src="https://unpkg.com/bootstrap-material-design@4.1.1/dist/js/bootstrap-material-design.js" integrity="sha384-CauSuKpEqAFajSpkdjv3z9t8E7RlpJ1UP0lKM/+NdtSarroVKu069AlsRPKkFBz9" crossorigin="anonymous"></script>
      <script src="<?php echo Config::get('URL'); ?>js/static/bootstrap-datepicker.js"></script>
      <style>
            body{
                -webkit-touch-callout: none;                /* prevent callout to copy image, etc when tap to hold */
                -webkit-text-size-adjust: none;             /* prevent webkit from resizing text to fit */
                -webkit-user-select: none;                  /* prevent copy paste, to allow, change 'none' to 'text' */
            }
          .table-td tbody td:hover{
              background-color:rgba(0,0,0,.075);
          }
      </style>
        <script>
        $(document).ready(function() {
            $("#fecha\\.mes").on("change", function(){
                makeCalendario();
            });

            $("#fecha\\.ano").on("change", function(){
                makeCalendario();
            });

            makeCalendario();

            $("#boton\\.turno").on("click", function(){
                $("#dialog\\.title").html("INGRESAR DATOS (fecha, horario y profesional de turno)");
                $("#dialog\\.body").html('<div class="row"><div class="form-group col"><label for="turnos.fecha.in">Fecha de turno</label><input class="form-control" type="date" id="turnos.fecha.in"></div><div class="form-group col"><label for="turnos.hora.in">Horario de turno (12 o 24 hrs)</label><select class="form-control" id="turnos.turno"><option value="0">Diurno</option><option value="1">Nocturno</option><option value="2">Completo</option></select></div></div><div class="row"><div class="form-group col"><label for="turnos.profesionales">Profesional asignado</label><select class="form-control" id="turnos.profesionales"></select></div></div>');
                $("#dialog\\.view").modal("show");
                cargarProfesionales();
                $("#dialog\\.delete").remove();
                $("#dialog\\.footer").append('<button type="button" class="btn btn-danger" id="dialog.delete">Guardar</button>');

                $("#dialog\\.delete").on("click", function(){
                    let datos = {
                        accion: "turnosNuevo",
                        profesional: $("#turnos\\.profesionales").val(),
                        profesional_nombre: $("#turnos\\.profesionales option:selected").text(), 
                        fechainic: $("#turnos\\.fecha\\.in").val(),
                        turno: $("#turnos\\.turno").val(),
                    }

                    $.post("https://turnoscat.crecimientofetal.cl/turnos/api", datos).done(function(response){
                        $("#dialog\\.view").modal("hide");
                        makeCalendario();
                    });
                });
            });

            $("#boton\\.pormes").on("click", function(){
                $("#dialog\\.title").html("Calcular horas de turno por mes");
                $("#dialog\\.body").html('<div class="row"><div class="form-group col"><label for="turnos.mes">Mes de turno</label><select class="form-control" id="turnos.mes"></select></div><div class="form-group col"><label for="turnos.profesionales">Profesional</label><select class="form-control" id="turnos.profesionales"></select></div><div class="form-group col"><label for="turnos.conteo">Total de horas</label><input type="text" class="form-control" id="turnos.conteo" disabled></div></div>');
                $("#dialog\\.view").modal("show");
                cargarProfesionales();

                var mes = ['Enero','Febrero','Marzo','Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                
                let d = new Date();
                $.each(mes, function(i, item) {
                    let option = '<option value="' + parseInt(i +1) + '">' + mes[i] + ' ' + d.getFullYear() + '</option>';
                    $("#turnos\\.mes").append(option);
                });

                $("#turnos\\.mes, #turnos\\.profesionales").on("click", function(){
                    let d = new Date();
                    let datos = {
                        accion: "sumaturnos",
                        mes: $("#turnos\\.mes").val(),
                        ano: d.getFullYear(),
                        profesional: $("#turnos\\.profesionales").val(),
                    }

                    $.post("https://turnoscat.crecimientofetal.cl/turnos/api", datos).done(function(response){
                        $("#turnos\\.conteo").val(response.conteo);
                    });
                });
            });

            $("#boton\\.profesionales").on("click", function(){
                cargarProfesionales();
                $("#dialog\\.title").html("Profesionales registrados en base de datos");
                $("#dialog\\.body").html('<div class="btn-group" role="group" aria-label="Menú"> <button type="button" class="btn btn-outline-success my-2 my-sm-0 mr-1" title="Nueva actividad" id="boton.profesional.nuevo"><i class="fas fa-pen"></i></button> <button type="button" class="btn btn-outline-success my-2 my-sm-0 mr-1 d-none" title="Nueva actividad" id="boton.profesional.guardar"><i class="fas fa-save"></i></button> <button type="button" class="btn btn-outline-success my-2 my-sm-0 mr-1 d-none" title="Nueva actividad" id="boton.profesional.cancelar"><i class="fas fa-ban"></i></button> </div><div id="div.profesional" class="my-3 mx-0 d-none"> <div class="row"><div class="form-group col"> <label for="profesional.nombre">1.- Nombre del profesional</label> <input type="text" class="form-control" id="profesional.nombre"> <input type="hidden" class="form-control" id="profesional.id"></div><div class="form-group col"> <label class="mr-3" for="profesional.rut">2.- R.U.T.</label> <input type="text" class="form-control" id="profesional.rut"></div></div><div class="row"><div class="form-group col"> <label for="profesional.telefono">3.- Teléfono</label> <input type="text" class="form-control" id="profesional.telefono"> </div><div class="form-group col"> <label class="mr-3" for="profesional.correo">4.- Correo Electrónico</label> <input type="text" class="form-control" id="profesional.correo"></div></div></div><table class="table table-hover"> <thead class="table-success"> <tr> <th scope="col">Nombre profesional</th> <th scope="col">Teléfono</th> <th scope="col">Correo Electrónico</th></tr></thead> <tbody id="tabla.profesional"></tbody> </table>');
                $("#dialog\\.view").modal("show");

                $("#dialog\\.delete").remove();

                $("#boton\\.profesional\\.nuevo").on("click", function(){
                    $("#div\\.profesional").removeClass("d-none");
                    $("#boton\\.profesional\\.nuevo").addClass("d-none");
                    $("#boton\\.profesional\\.guardar").removeClass("d-none")
                    $("#boton\\.profesional\\.cancelar").removeClass("d-none");
                    $("#tabla\\.profesional").addClass("d-none");
                    $("#profesional\\.id").val("");
                });

                $("#boton\\.profesional\\.guardar").on("click", function(){
                    $("#div\\.profesional").addClass("d-none");
                    let datos = {
                        accion: "profesionalesNuevo",
                        nombre: $("#profesional\\.nombre").val(),
                        rut: $("#profesional\\.rut").val(),
                        telefono: $("#profesional\\.telefono").val(),
                        correo: $("#profesional\\.correo").val(),
                        id: $("#profesional\\.id").val()
                    }

                    if ($("#profesional\\.id").val() !== ""){
                        datos.accion = "profesionalesUpdate";
                    }

                    $.post("https://turnoscat.crecimientofetal.cl/turnos/api", datos).done(function(response){
                        $("#profesional\\.nombre").val("");
                        $("#profesional\\.rut").val("");
                        $("#profesional\\.telefono").val("");
                        $("#profesional\\.correo").val("");
                        $("#profesional\\.id").val("");
                        $("#boton\\.profesional\\.nuevo").removeClass("d-none");
                        $("#boton\\.profesional\\.guardar").addClass("d-none");
                        $("#boton\\.profesional\\.cancelar").addClass("d-none");
                        $("#tabla\\.profesional").removeClass("d-none");
                        cargarProfesionales();
                    });
                });

                $("#boton\\.profesional\\.cancelar").on("click", function(){
                    $("#div\\.profesional").addClass("d-none");
                    $("#profesional\\.nombre").val("");
                    $("#profesional\\.rut").val("");
                    $("#profesional\\.telefono").val("");
                    $("#profesional\\.correo").val("");
                    $("#boton\\.profesional\\.nuevo").removeClass("d-none");
                    $("#boton\\.profesional\\.guardar").addClass("d-none");
                    $("#boton\\.profesional\\.cancelar").addClass("d-none");
                    $("#tabla\\.profesional").removeClass("d-none");
                });
            });
        });

      function makeCalendario(){
            let data = {
                accion : "calendario",
                mes: $("#fecha\\.mes").val(),
                ano: $("#fecha\\.ano").val()
            }

            $.post("https://turnoscat.crecimientofetal.cl/turnos/api", data).done(function(response){
                $("#table\\.calendario").empty();

                if (Object.keys(data).length > 0) {
                    let fila = "";
                    let dias = ["Lunes ", "Martes ", "Miércoles ", "Jueves ", "Viernes ", "Sábado ", "Domingo "];
                    let i = response.diaDeLaSemana;
                    let j = response.diasEnElMes;
                    let h = 1;
                    let turnos = response.turnos;
                    let comentarios = response.comentarios;

                    for (h; h <= j; h++){
                        let rojo = "";
                        let elDia = i - 1;
                        if (elDia == 5 || elDia == 6){
                            rojo = "text-danger";
                        }

                        const turnosDia = turnos.filter(turno => {
                            let dia = data.ano + '-' + data.mes + '-' + ("0" + h).slice(-2);
                            return turno.turno_fechain === dia;
                        });

                        const comentariosDia = comentarios.filter(comentario =>{
                            let dia = data.ano + '-' + data.mes + '-' + ("0" + h).slice(-2);
                            return comentario.comentario_fecha === dia;
                        });

                        let comentario = "";

                        if (comentariosDia.length > 0){
                            comentario = comentariosDia[0].comentario_text.replace(/<br \/>/g,"\n");
                            comentario = comentario.substring(0,70);
                            comentario += "...";
                        }

                        if (Object.keys(turnosDia).length > 0) {
                            let diaT = "";
                            let nocheT = "";
                            let diaI = "";
                            let nocheI = "";

                            const diaF = turnosDia.filter(elDia => {
                                return parseInt(elDia.turno_turno) === 0;
                            });

                            const nocheF = turnosDia.filter(laNoche => {
                                return parseInt(laNoche.turno_turno) === 1;
                            });

                            if (diaF.length > 0){
                                diaT = diaF[0].turno_profesional_nombre;
                                diaI = diaF[0].turno_id;
                            }

                            if (nocheF.length > 0){
                                nocheT = nocheF[0].turno_profesional_nombre;
                                nocheI = nocheF[0].turno_id;
                            }

                            fila = '<tr><td class="bg-light ' + rojo +'">' + dias[elDia] + h + '</td><td class="text-center" data-id="' + diaI +'">' + diaT +'</td><td class="text-center" data-id="' + nocheI +'">' + nocheT +'</td><td class="text-center" data-calendario="' + h + '">'+comentario+'</td></tr>';
                        }
                        else{
                            fila = '<tr><td class="bg-light ' + rojo +'">' + dias[elDia] + h + '</td><td class="text-center"></td><td class="text-center"></td><td class="text-center" data-calendario="' + h + '">'+comentario+'</td></tr>';    
                        }

                        if (i == 7){ 
                            i = 1;
                        }
                        else{
                            i++;
                        }

                        $("#table\\.calendario").append(fila);
                    }
                }

                $("#table\\.calendario tr td").on("click", function(){
                    let turno_id = $(this).data("id");
                    let calendario_id = $(this).data("calendario");

                    if (typeof turno_id === 'number'){
                        $("#dialog\\.title").html("Obteniendo datos ...");
                        $("#dialog\\.body").html('<p class="text-center">cargando</p>');
                        $("#dialog\\.view").modal("show");
                        $("#dialog\\.delete").remove();

                        let data = {
                            accion : "turnosUno",
                            id: turno_id
                        }
                        $.post("https://turnoscat.crecimientofetal.cl/turnos/api", data).done(function(response){
                            if (Object.keys(response).length > 0) {
                                let d = new Date(response.turno_fechain.replace(/-/g, '\/'));
                                let day = ("0" + d.getDate()).slice(-2);
                                let month = ("0" + (d.getMonth() + 1)).slice(-2); 
                                let dateComplete = day + "-" + month + "-" + d.getFullYear();

                                $("#dialog\\.title").html('CAMBIO PROFESIONAL DE TURNO:');
                                $("#dialog\\.body").html('<div class="row"><div class="col"><p>' + response.turno_profesional_nombre + ', fecha: ' + dateComplete +'</p></div></div><div class="row"><div class="form-group col"><label for="turnos.profesionales" class="text-danger text-center mt-3"><strong>Reemplazar por:</strong></label><select class="form-control" id="turnos.profesionales"></select></div></div>');
                                $("#dialog\\.footer").append('<button type="button" class="btn btn-danger" id="dialog.delete" data-id="' + response.turno_id + '">Guardar</button>');
                                cargarProfesionales();

                                $("#dialog\\.delete").on("click", function(){
                                    let id = $(this).data("id");
                                    let datos = {
                                        accion: "turnosCambiar",
                                        id: id,
                                        profesional: $("#turnos\\.profesionales").val(),
                                        profesional_nombre: $("#turnos\\.profesionales option:selected").text()
                                    }

                                    $.post("https://turnoscat.crecimientofetal.cl/turnos/api", datos).done(function(response){
                                        $("#dialog\\.view").modal("hide");
                                        makeCalendario();
                                    });
                                });
                            }
                        });
                    }
                    else if (typeof calendario_id === 'number'){

                        $("#dialog\\.title").html("Obteniendo datos ...");
                        $("#dialog\\.body").html('<p class="text-center">cargando</p>');
                        $("#dialog\\.view").modal("show");
                        $("#dialog\\.delete").remove();

                        let mes = $("#fecha\\.mes").val();
                        let ano = $("#fecha\\.ano").val();
                        let dia = ano + '-' + mes + '-' + ("0" + calendario_id).slice(-2);
                        let data = {
                            accion : "comentarioUno",
                            id: dia
                        }

                        let d = new Date(dia.replace(/-/g, '\/'));
                        let day = ("0" + d.getDate()).slice(-2);
                        let month = ("0" + (d.getMonth() + 1)).slice(-2); 
                        let dateComplete = day + "-" + month + "-" + d.getFullYear();

                        $.post("https://turnoscat.crecimientofetal.cl/turnos/api", data).done(function(response){
                            if (Object.keys(response).length > 0) {
                                $("#dialog\\.title").html('Comentario para el día ' + dateComplete);
                                $("#dialog\\.body").html('<div class="row"><div class="form-group col"><label for="comentarios.text">Comentario:</label><input class="form-control" id="comentarios.text" type="text" value="' + response.comentario_text.replace(/<br \/>/g,"\n") +'"></div></div>');
                                $("#dialog\\.delete").remove();
                                $("#dialog\\.footer").append('<button type="button" class="btn btn-danger" id="dialog.delete" data-id="' + response.comentario_id + '">Guardar</button>');
                                $("#dialog\\.delete").on("click", function(){
                                    let id = $(this).data("id");
                                    let datos = {
                                        accion: "comentarioUpdate",
                                        id: id,
                                        text: $("#comentarios\\.text").val().replace(/\r\n|\r|\n/g,"<br />"), 
                                    }

                                    $.post("https://turnoscat.crecimientofetal.cl/turnos/api", datos).done(function(response){
                                        $("#dialog\\.view").modal("hide");
                                        makeCalendario();
                                    });
                                });
                            }
                            else{
                                $("#dialog\\.title").html("Crear comentario para el día " + dateComplete);
                                $("#dialog\\.body").html('<div class="row"><div class="form-group col"><label for="comentarios.text">Comentario:</label><input class="form-control" id="comentarios.text" type="text" value=""></div></div>');
                                $("#dialog\\.delete").remove();
                                $("#dialog\\.footer").append('<button type="button" class="btn btn-danger" id="dialog.delete" data-id="' + data.id + '">Guardar</button>');
                                
                                $("#dialog\\.delete").on("click", function(){
                                    let id = $(this).data("id");
                                    let datos = {
                                        accion: "comentarioGuardar",
                                        fecha: id,
                                        text: $("#comentarios\\.text").val().replace(/\r\n|\r|\n/g,"<br />"), 
                                    }

                                    $.post("https://turnoscat.crecimientofetal.cl/turnos/api", datos).done(function(response){
                                        $("#dialog\\.view").modal("hide");
                                        makeCalendario();
                                    });
                                });
                            }
                        });
                    }


                });
            });
      }

      function cargarProfesionales(){
        let data = {
                accion : "profesionales",
            }

            $.post("https://turnoscat.crecimientofetal.cl/turnos/api", data).done(function(response){
                $("#tabla\\.profesional").empty();
                $("#turnos\\.profesionales").empty();
                $("#turno\\.profesional\\.in").empty();
                if (Object.keys(data).length > 0) {
                    $.each(response, function(i, item) {
                        let fila = '<tr><td data-id="'+item["profesional_id"]+'"> <i class="fas fa-pen"></i> ' + item["profesional_name"] + '</td><td>' + item["profesional_telefono"] + '<td class="columna-profesional">' + item["profesional_correo"] + '<button type="button" data-id="' + item["profesional_id"] + '" class="btn btn-outline-warning px-3 eliminar-profesional float-right d-none"><i class="fas fa-trash"></i></button></td></tr>';
                        let option = '<option value="' + item["profesional_id"] + '">' + item["profesional_name"] + '</option>';
                        $("#turnos\\.profesionales").append(option);
                        $("#tabla\\.profesional").append(fila);
                        $("#turno\\.profesional\\.in").append(fila);
                    });

                    $(".columna-profesional").on("mouseenter",function(){
                        $(this).children("button").removeClass("d-none");
                    }).on("mouseleave", function(){
                        $(this).children("button").addClass("d-none");
                    });

                    $(".eliminar-profesional").on("click", function(){
                        let profesional_id = $(this).data("id");
                        $("#dialog\\.delete").remove();
                        $("#dialog\\.title").html('Eliminar Profesional')
                        $("#dialog\\.body").html('<p class="text-center">¿Está seguro que desea eliminar el profesional seleccionado?</p>')
                        $("#dialog\\.footer").append('<button type="button" class="btn btn-danger" id="dialog.delete" data-id="' + profesional_id + '">Eliminar</button>');
            
                        $("#dialog\\.delete").on("click", function(){
                            let profesional_id = $(this).data("id");
                            var solicitud = {
                                accion: "profesionalesEliminar",
                                id: parseInt(profesional_id)
                            };
                            $.post("https://turnoscat.crecimientofetal.cl/turnos/api", solicitud).done(function(data){
                                $("#dialog\\.body").empty();
                                $("#boton\\.profesionales").trigger("click");
                            });
                        });
                        $("#dialog\\.view").modal("show");
                    });

                    $("#tabla\\.profesional tr td").on("click", function(){
                        let profesional_id = $(this).data("id");

                        let datos = {
                            accion: "profesional",
                            id: profesional_id
                        }

                        $.post("https://turnoscat.crecimientofetal.cl/turnos/api", datos).done(function(response){
                            if (Object.keys(response).length > 0) {
                                $("#profesional\\.id").val(response.profesional_id);
                                $("#profesional\\.nombre").val(response.profesional_name);
                                $("#profesional\\.rut").val(response.profesional_rut);
                                $("#profesional\\.telefono").val(response.profesional_telefono);
                                $("#profesional\\.correo").val(response.profesional_correo);
                                $("#div\\.profesional").removeClass("d-none");
                                $("#boton\\.profesional\\.nuevo").addClass("d-none");
                                $("#boton\\.profesional\\.guardar").removeClass("d-none")
                                $("#boton\\.profesional\\.cancelar").removeClass("d-none");
                                $("#tabla\\.profesional").addClass("d-none");
                            }
                        });
                    });
                }
            });
      }
        </script>
    </body>
</html>