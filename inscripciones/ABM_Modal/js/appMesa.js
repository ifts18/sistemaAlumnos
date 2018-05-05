	function load(page){
		
			var palabra = document.getElementById("palabra").value;
			var filtro = document.getElementById("filtro").value;
			var parametros = {"action":"ajax","page":page,"method":"buscar","filtro":filtro,"palabra":palabra};
		

		$("#loader").fadeIn('slow');
		$.ajax({
			url:'ajax/mesas_ajax.php',
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
		  var materia = button.data('materia') // Extraer la información de atributos de datos
                  var fecha = button.data('fecha') // Extraer la información de atributos de datos
                  var id = button.data('id') // Extraer la información de atributos de datos
		  
                  //TODO ESTO PARA LOGRAR MOSTRAR LA FECHA...
                  fecha = fecha.replace("/","-").replace("/","-");
                  var datearray = fecha.split("-");
                  var newdate = datearray[2] + '-' + datearray[1] + '-' + datearray[0];
                  
		  var modal = $(this)
		  modal.find('.modal-title').text('Modificar Mesa de Final ');
		  modal.find('.modal-body #id').val(id)
		  modal.find('.modal-body #materia').val(materia).change();
                  modal.find('.modal-body #fechaMesa').val(newdate);

		  $('.alert').hide();//Oculto alert
		})
		
		$('#dataDelete').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Botón que activó el modal
		  var id = button.data('id') // Extraer la información de atributos de datos
		  var modal = $(this)
		  modal.find('#idMesaFinal').val(id)
		})

                $( "#actualidarDatos" ).submit(function( event ) {
		var parametros = $(this).serialize();
			 $.ajax({
					type: "POST",
					url: "controller/modificarMesa.php",
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
		
		$( "#eliminarDatos" ).submit(function( event ) {
		var parametros = $(this).serialize();
			 $.ajax({
					type: "POST",
					url: "controller/eliminarMesa.php",
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

