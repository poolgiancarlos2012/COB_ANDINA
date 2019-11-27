var AdministratorDAO = {
		url : 'controller/ControllerCobrast.php',
		idLayerMessage : 'layerMessage',
		login : function ( ) {
				$.ajax({
						url : AdministratorDAO.url,
						type : 'POST',
						dataType : 'json',
						data : {command:'administrator',action:'login',Usuario:$('#').val(),Password:$('#').val()},
						beforeSend : function ( ) {
								
							},
						success : function ( obj ) {
								
							},
						error : function ( ) {}
					});
			}
		
	}