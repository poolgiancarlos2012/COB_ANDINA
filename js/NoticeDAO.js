// JavaScript Document
var NoticeDAO = {
		url:'../controller/ControllerCobrast.php',
		idLayerMessage : 'layerMessage',
	    xTypeData:'json',
		save_notice : function ( xidusuario_servicio, xusuario_creacion, xidservicio, xtitulo, xdescripcion, f_success ) {
				
				$.ajax({
						url : NoticeDAO.url,
						type : 'POST',
						dataType : 'json',
						data : { 
								command : 'notice', 
								action : 'save_notice', 
								idusuario_servicio : xidusuario_servicio ,
								usuario_creacion : xusuario_creacion,
								idservicio : xidservicio,
								titulo : xtitulo,
								descripcion : xdescripcion
								},
						beforeSend : function ( ) {},
						success : function ( obj ) {
								f_success(obj);
							},
						error : function ( ) {}
					});
				
			},
		listar_noticia_hoy : {
			
				init : function ( xidservicio, f_success ) {
						
						$.ajax({
								url : NoticeDAO.url,
								type : 'POST',
								dataType : 'json',
								data : { command : 'notice', action : 'ListarNoticeHoyRealTime', idservicio : xidservicio },
								beforeSend : function ( ) {},
								success : function ( obj ) {
										f_success(obj);
									},
								error : function ( ) {}
							});
							
					},
				PeriodicalUpdater : function ( xidservicio, f_success ) {
						
						$.PeriodicalUpdater({
											url : NoticeDAO.url,
											method : 'GET',
											type : 'json',
											minTimeout : 1000,
											maxTimeout : 20000,
											sendData : { command : 'notice', action : 'ListarNoticeHoyRealTime', idservicio : xidservicio }
										}, function ( obj ) {
												f_success(obj);
											});
						
					}
				
			}
		
	}