// JavaScript Document
var AlertaAdminDAO = {
		
		url:'../controller/ControllerCobrast.php',
		idLayerMessage : 'layerMessage',
	    xTypeData:'json',
		ListarAlerta : function ( xidservicio, f_success ) {
				
				$.ajax({
						url : AlertaAdminDAO.url,
						type : 'GET',
						dataType : 'json',
						data : { command : 'alerta_admin', action : 'ListarAlertas', idservicio : xidservicio },
						beforeSend : function ( ) {},
						success : function ( obj ) {
								
								f_success(obj);
								
							},
						error : function ( ) {}
					});
					
			}
		
	}