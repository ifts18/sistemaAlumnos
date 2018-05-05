	function load(page){
                
                
                var palabra = document.getElementById("palabra").value;
                var filtro = document.getElementById("filtro").value;
                var parametros = {"action":"ajax","page":page,"method":"buscar","filtro":filtro,"palabra":palabra};


		$("#loader").fadeIn('slow');
		$.ajax({
			url:'ajax/materias_ajax_modif.php',
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
                  var materia = button.data('materia') // Extraer la información de atributos de datos
		  var dni = button.data('dni') // Extraer la información de atributos de datos
                  var nota = button.data('nota') // Extraer la información de atributos de datos
                  var id = button.data('idalumno') // Extraer la información de atributos de datos
                  var idmesafinalalumno = button.data('idmesafinalalumno') // Extraer la información de atributos de datos
		  
		  var modal = $(this)
		  modal.find('.modal-title').text('Modificar Materia ');
		  modal.find('.modal-body #idAlumno').val(id).change();
		  modal.find('.modal-body #idmesafinalalumno').val(idmesafinalalumno).change();
		  modal.find('.modal-body #nombre').val(nombre).change();
		  modal.find('.modal-body #apellido').val(apellido).change();
                  modal.find('.modal-body #materia').val(materia).change();
		  modal.find('.modal-body #nota').val(nota).change();
                  modal.find('.modal-body #dni').val(dni).change();

		  $('.alert').hide();//Oculto alert
		})
	
                $( "#actualidarDatos" ).submit(function( event ) {
		var parametros = $(this).serialize();
			 $.ajax({
					type: "POST",
					url: "controller/modificarNotaAlumno.php",
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