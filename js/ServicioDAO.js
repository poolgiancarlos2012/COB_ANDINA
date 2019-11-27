var ServicioDAO={
			url:'../controller/ControllerCobrast.php',
			idLayerMessage : 'layerMessage',
			retornarData : function ( ) {
					return {command:'servicio',Id:$('#IdServicio').val(),Nombre:$('#txtNombre').val(),Descripcion:$('#txtDescripcion').val(),UsuarioCreacion:$('#hdCodUsuario').val(),UsuarioModificacion:$('#hdCodUsuario').val()};
				},
			insert : function ( ) {
					$.ajax({
						   	url : this.url,
							type : 'POST',
							dataType : 'json',
							data : {
								command:'servicio',
								action:'save_servicio',
								Nombre:$('#txtNombre').val(),
								Descripcion:$('#txtDescripcion').val(),
								UsuarioCreacion:$('#hdCodUsuario').val()
								},
							beforeSend : function ( ) {
									_displayBeforeSend('Guardando Servicio...',200);
								},
							success : function( obj ){
									_noneBeforeSend();
									if(obj.rst){
										$('#layerMessage').html(templates.MsgInfo(obj.msg,'250px'));
										$('#layerMessage').effect('pulsate',{},1500,function(){ $(this).empty(); });
									}else{
										$('#layerMessage').html(templates.MsgError(obj.msg,'250px'));	
										$('#layerMessage').effect('pulsate',{},1500,function(){ $(this).empty(); });
									}
								},
							error : function ( ) {
									_noneBeforeSend();
									$('#layerMessage').html(templates.MsgError('Error en el servidor','200px'));
									$('#layerMessage').effect('pulsate',{},1500,function(){ $(this).empty(); });
								}
						   })
				},
			Delete : function( ){
					$.ajax({
						   url : this.url,
						   type : 'POST',
						   dataType : 'json',
						   data : {
							   		command:'servicio',
						   			action:'delete_servicio',
									Id:$('#IdServicio').val(),
									UsuarioModificacion:$('#hdCodUsuario').val()
									},
						   beforeSend : function ( ) {
							   		_displayBeforeSend('Eliminando Servicio...',300);
							   },
						   success : function ( obj ){
							   		_noneBeforeSend();
							   		if(obj.rst){
										//var usuarioCreacion=$('#hdNomUsuario').val();
										//$('#txtUsuarioCreacion').val(usuarioCreacion);
										$('#layerMessage').html(templates.MsgInfo(obj.msg,'250px'));
										$('#layerMessage').effect('pulsate',{},1500,function(){ $(this).empty(); });
										reloadJQGRIDServicio();
										cancel();
									}else{
										$('#layerMessage').html(templates.MsgError(obj.msg,'250px'));	
										$('#layerMessage').effect('pulsate',{},1500,function(){ $(this).empty(); });
									}
							   },
						   error : function (  ){
							   		_noneBeforeSend();
							   		$('#layerMessage').html(templates.MsgError('Error en el servidor','200px'));
									$('#layerMessage').effect('pulsate',{},1500,function(){ $(this).empty(); });
							   }
						   });
				},
			update : function( ){
					$.ajax({
						   url : this.url,
						   type : 'POST',
						   dataType : 'json',
						   data : {
							   		command:'servicio',
									action:'update_servicio',
									Id:$('#IdServicio').val(),
									Nombre:$('#txtNombre').val(),
									Descripcion:$('#txtDescripcion').val(),
									UsuarioModificacion:$('#hdCodUsuario').val()
							   },
						   beforeSend : function ( ) {
							   		_displayBeforeSend('Actualizando Servicio...',300);
							   },
						   success : function ( obj ){
							   		_noneBeforeSend();
							   		if(obj.rst){
										//var usuarioCreacion=$('#hdNomUsuario').val();
										//$('#txtUsuarioCreacion').val(usuarioCreacion);
										$('#layerMessage').html(templates.MsgInfo(obj.msg,'250px'));
										$('#layerMessage').effect('pulsate',{},1500,function(){ $(this).empty(); });
										reloadJQGRIDServicio();
										cancel();
									}else{
										$('#layerMessage').html(templates.MsgError(obj.msg,'250px'));	
										$('#layerMessage').effect('pulsate',{},1500,function(){ $(this).empty(); });
									}
							   },
						   error : function ( ) {
							   		_noneBeforeSend();
							   		$('#layerMessage').html(templates.MsgError('Error en el servidor','200px'));		
									$('#layerMessage').effect('pulsate',{},1500,function(){ $(this).empty(); });
							   }
						   });
				},
			DataById : function ( Servicio ) {
					$.ajax({
						   url : this.url,
						   type : 'GET',
						   dataType : 'json',
						   data : {command:'servicio',action:'DataById',Id:Servicio},
						   success : function ( obj ){
							   		//$('#IdServicio').val(obj[0].id);
									if(obj.length==1){
										$('#txtNombre').val(obj[0].nombre);
										$('#txtDescripcion').val(obj[0].descripcion);
										if(obj[0].usuario_creacion!=$('#hdCodUsuario').val()){
											$('#txtUsuarioCreacion').val(obj[0].nombre_usuario_creacion);
										}
										_display_panel('panelCrearServicio');
										
									}
							   },
						   error : function ( ) {
							   	_noneBeforeSend();
							   	$('#layerMessage').html(templates.MsgError('Error al traer datos','200px'));
								$('#layerMessage').effect('pulsate',{},1500,function(){ $(this).empty(); });
							   }
						   });
				},
			ListarTipoUsuario : function ( ) {
				$.ajax({
					   url : this.url,
					   type : 'GET',
					   dataType : 'json',
					   data : {command:'usuario',action:'ListarTipoUsuario'},
					   success : function ( obj ) {
								var html='';
								$.each(obj,function(key,data){
									html+='<option value="'+data.id+'">'+data.nombre+'</option>';
								});
								$("#cbTipoUsuario").html(html);
						   },
					   error : this.error_ajax
					   });
				},
			ListarPrivilegios : function ( ) {
				$.ajax({
					   url : this.url,
					   type : 'GET',
					   dataType : 'json',
					   data : {command:'usuario',action:'ListarPrivilegios'},
					   success : function ( obj ) {
								var html='';
								$.each(obj,function(key,data){
									html+='<option value="'+data.id+'">'+data.nombre+'</option>';
								});
								$("#cbPrivilegioUsuario").html(html);
						   },
					   error : this.error_ajax
					   });	
				},
			insertUsuario : function ( ) {
		
				$.ajax({
					url : this.url ,
					type : 'POST' ,
					dataType : 'json',
					data : {
							command:'usuario',
							action:'save_usuario',
							Nombre:$('#txtUsuarioNombre').val(),
							Paterno:$('#txtUsuarioPaterno').val(),
							Materno:$('#txtUsuarioMaterno').val(),
							Dni:$('#txtUsuarioDni').val(),
							Clave:$('#txtUsuarioClave').val(),
							UsuarioCreacion:$('#hdCodUsuario').val(),
							Email:$('#txtServicioEmail').val(),
							Servicio:$('#hdCodServicio').val(),
							TipoUsuario:$('#cbTipoUsuario').val(),
							Privilegio:$('#cbPrivilegioUsuario').val(),
							FechaInicio:$('#txtUsuarioFechaInicio').val(),
							FechaFin:$('#txtUsuarioFechaFin').val()
							} ,
					beforeSend : function ( ) {
							_displayBeforeSend('Grabando Usuario...',300);
						},
					success : function ( obj ) {
							_noneBeforeSend();
							if(obj.rst){
								$('#UsuarioLayerMessage').html(templates.MsgInfo(obj.msg,'250px'));
								$('#UsuarioLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });

							}else{
								$('#UsuarioLayerMessage').html(templates.MsgError(obj.msg,'250px'));
								$('#UsuarioLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });
							}
						} ,
					error : this.error_ajax
				});
			},
		insertCampania : function ( ) {

				$.ajax({
					   url : this.url,
					   type:'POST',
					   dataType : 'json',
					   data : {
						   command:'campania',
						   action:'save_campania',
						   Servicio:$('#hdCodServicio').val(),
						   Nombre:$('#txtCampaniaNombre').val(),
						   FechaInicio:$('#txtCampaniaFechaInicio').val(),
						   FechaFin:$('#txtCampaniaFechaFin').val(),
						   Descripcion:$('#txtCampaniDescripcion').val(),
						   UsuarioCreacion:$('#hdCodUsuario').val()
						   },
					   beforeSend : function ( ) {
						   		_displayBeforeSend('Guardando Campa&ntilde;a...',320);
						   },
					   success : function ( obj ) {
						   		_noneBeforeSend();
								if(obj.rst){
									$('#CampaniaLayerMessage').html(templates.MsgInfo(obj.msg,'250px'));
									$('#CampaniaLayerMessage').effect('pulsate',{},1000,function(){ $(this).empty(); });
									
								}else{
									$('#CampaniaLayerMessage').html(templates.MsgError(obj.msg,'250px'));
									$('#CampaniaLayerMessage').effect('pulsate',{},1000,function(){ $(this).empty(); });
								}
						   },
					   error : this.error_ajax
					   });
			}
			
	};