	function load(page){
                
                
                var palabra = document.getElementById("palabra").value;
                var filtro = document.getElementById("filtro").value;
                var parametros = {"action":"ajax","page":page,"method":"buscar","filtro":filtro,"palabra":palabra};


		$("#loader").fadeIn('slow');
		$.ajax({
			url:'ajax/materias_ajax.php',
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
	