// JavaScript Document
var CartaDAO = {
		
		url:'../controller/ControllerCobrast.php',
		idLayerMessage : 'layerMessage',
		ListarCampania : function( ) {
			$.ajax({
					url : CartaDAO.url,
					type : 'GET',
					dataType : 'json',
					data : {
							command:'atencion_cliente',action:'ListarCampanias',Servicio:$('#hdCodServicio').val()
						},
					success : function ( obj ) {
							CartaDAO.FillCampania(obj);
						},
					error : function ( ) {
							CartaDAO.error_ajax();
						}
				});
		},
		FillCampania : function ( obj ) {
			var html='';
				html+='<option value="0">--Seleccione--</option>';
			$.each(obj,function(key,data){
				html+='<option value="'+data.idcampania+'">'+data.nombre+'</option>';
			});
			$(document.body).find('select[id^="cbCampania"]').html(html);
		},
		ListarCartera : function ( idCampania, f_fill ) {
			$.ajax({
					url : CartaDAO.url,
					type : 'GET',
					dataType : 'json',
					data : {
							command:'carga-cartera',action:'ListCartera',Campania:idCampania 
						},
					success : function ( obj ) {
							f_fill(obj);
						},
					error : function ( ) {
							CartaDAO.error_ajax();
						}
				});
		},
		FillCarteraById : function ( obj,cbCartera ) {
			var html='';
				html+='<option value="0">--Seleccione--</option>';
			$.each(obj,function(key,data){
				html+='<option value="'+data.idcartera+'">'+data.nombre_cartera+'</option>';
			});
			$('#'+cbCartera).html(html);
		},
		ListarEstado : function ( xidservicio, f_success ) {
			
			$.ajax({
					url : CartaDAO.url,
					type : 'GET',
					dataType : 'json',
					data : {command:'atencion_cliente',action:'ListState',Servicio : xidservicio },
					beforeSend : function ( ) {},
					success : function ( obj ) {
							//AtencionClienteDAO.FillAtencionEstadoLLamadaBusquedaEstado(obj.llamada);
							f_success(obj);
						},
					error : function ( ) {}
				});
			
		},
		ListarDepartamentos : function ( xidcartera, f_fill ) {
				
			$.ajax({
					url : CartaDAO.url,
					type : 'GET',
					dataType : 'json',
					data : { command : 'distribucion', action : 'ListarDepartamentosPorCartera', idcartera : xidcartera },
					beforeSend : function ( ) {},
					success : function ( obj ) {
							f_fill(obj);
						},
					error : function ( ) {}
				});
			
		},
		error_ajax : function ( ) {
			_noneBeforeSend();
			$('#'+CartaDAO.idLayerMessage).html(templates.MsgError('Error en el servidor','250px'));
			$('#'+CartaDAO.idLayerMessage).effect('pulsate',{},AtencionClienteDAO.speedLayerMessage,function () { $(this).empty(); });
		},
		
		
	}

