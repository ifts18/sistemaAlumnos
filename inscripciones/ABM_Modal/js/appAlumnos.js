	function load(page){
		
			var palabra = document.getElementById("palabra").value;
			var filtro = document.getElementById("filtro").value;
			var origen = document.getElementById("origen").value;
                        var parametros = {"action":"ajax","page":page,"method":"buscar","filtro":filtro,"palabra":palabra,"origen":origen};
		

		$("#loader").fadeIn('slow');
		$.ajax({
			url:'ajax/alumnos_ajax.php',
			data: parametros,
			 beforeSend: function(objeto){
			$("#loader").html("<img src='loader.gif'>");
			},
			success:function(data){
				$(".outer_div").html(data).fadeIn('slow');
				$("#loader").html("");
			}
		})
	}

		$('#dataUpdate').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Botón que activó el modal
		  var nombre = button.data('nombre') // Extraer la información de atributos de datos
		  var apellido = button.data('apellido') // Extraer la información de atributos de datos
                  var email = button.data('email') // Extraer la información de atributos de datos
		  var dni = button.data('dni') // Extraer la información de atributos de datos
                  var password = button.data('password') // Extraer la información de atributos de datos
                  var id = button.data('idalumno') // Extraer la información de atributos de datos

		 
		  
		  var modal = $(this)
		  modal.find('.modal-title').text('Modificar Alumno ');
		  modal.find('.modal-body #idAlumno').val(id).change();
		  modal.find('.modal-body #nombre').val(nombre).change();
		  modal.find('.modal-body #apellido').val(apellido).change();
                  modal.find('.modal-body #email').val(email).change();
		  modal.find('.modal-body #password').val(password).change();
                  modal.find('.modal-body #dni').val(dni).change();
//                  modal.find('.modal-body #origen').val(origen).change();

		  $('.alert').hide();//Oculto alert
		})
		
		$('#dataDelete').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Botón que activó el modal
		  var id = button.data('idalumno') // Extraer la información de atributos de datos
		  var modal = $(this)
		  modal.find('#idAlumno').val(id)
		})

                $( "#actualidarDatos" ).submit(function( event ) {
		var parametros = $(this).serialize();
			 $.ajax({
					type: "POST",
					url: "controller/modificarAlumno.php",
					data: parametros,
					 beforeSend: function(objeto){
						$("#datos_ajax").html("Mensaje: Cargando...");
					  },
					success: function(datos){
					$("#datos_ajax").html(datos);
					
					load(1);
				  }
			});
		  event.preventDefault();
		});
		
		$( "#guardarDatos" ).submit(function( event ) {
		var parametros = $(this).serialize();
			 $.ajax({
					type: "POST",
					url: "controller/agregarAlumno.php",
					data: parametros,
					 beforeSend: function(objeto){
						$("#datos_ajax_register").html("Mensaje: Cargando...");
					  },
					success: function(datos){
					$("#datos_ajax_register").html(datos);
					
					load(1);
				  }
			});
		  event.preventDefault();
		});
		
		$( "#eliminarDatos" ).submit(function( event ) {
		var parametros = $(this).serialize();
			 $.ajax({
					type: "POST",
					url: "controller/eliminarAlumno.php",
					data: parametros,
					 beforeSend: function(objeto){
						$(".datos_ajax_delete").html("Mensaje: Cargando...");
					  },
					success: function(datos){
					$(".datos_ajax_delete").html(datos);
					
					$('#dataDelete').modal('hide');
					load(1);
				  }
			});
		  event.preventDefault();
		});

