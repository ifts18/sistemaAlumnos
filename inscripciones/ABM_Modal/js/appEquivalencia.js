	function load(page){
		
			var palabra = document.getElementById("palabra").value;
			var filtro = document.getElementById("filtro").value;
			var parametros = {"action":"ajax","page":page,"method":"buscar","filtro":filtro,"palabra":palabra};
		

		$("#loader").fadeIn('slow');
		$.ajax({
			url:'ajax/equivalencias_ajax.php',
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
		  var alumno = button.data('alumno') // Extraer la información de atributos de datos
		  var materia = button.data('materia') // Extraer la información de atributos de datos
                  var id = button.data('id') // Extraer la información de atributos de datos
		 
		  
		  var modal = $(this)
		  modal.find('.modal-title').text('Modificar Equivalencia ');
		  modal.find('.modal-body #id').val(id)
		  modal.find('.modal-body #alumno').val(alumno).change();
		  modal.find('.modal-body #materia').val(materia).change();

		  $('.alert').hide();//Oculto alert
		})
		
		$('#dataDelete').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Botón que activó el modal
                  var alumno = button.data('alumno') // Extraer la información de atributos de datos
		  var materia = button.data('materia') // Extraer la información de atributos de datos
		  var id = button.data('id') // Extraer la información de atributos de datos
                  
		  var modal = $(this)
		  modal.find('#idAlumnoEquivalencia').val(id)
                  modal.find('#alumno').val(alumno);
		  modal.find('#materia').val(materia);
		})

                $( "#actualidarDatos" ).submit(function( event ) {
		var parametros = $(this).serialize();
			 $.ajax({
					type: "POST",
					url: "controller/modificarEquivalencia.php",
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
					url: "controller/agregarEquivalencia.php",
					data: parametros,
					 beforeSend: function(objeto){
						$("#datos_ajax_register").html("Mensaje: Cargando...");
					  },
					success: function(datos){
					$("#datos_ajax_register").html(datos);
					
					load(1);
				  }
			});
                       $('#alumno').val('');
                       $('#alumno').selectpicker('render');
                       $('#materia').val('');
                       $('#materia').selectpicker('render');
		  event.preventDefault();
		});
		
		$( "#eliminarDatos" ).submit(function( event ) {
		var parametros = $(this).serialize();
			 $.ajax({
					type: "POST",
					url: "controller/eliminarEquivalencia.php",
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

