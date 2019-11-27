var CampaniaDAO = {
		url : '../controller/ControllerCobrast.php',
		idLayerMessage : 'layerMessage',
		retornarData : function ( ) {
			return {command:'campania',Id:$('#IdCampania').val(),Servicio:$('#hdCodServicio').val(),Nombre:$('#txtCampania').val(),FechaInicio:$('#txtFechaInicio').val(),FechaFin:$('#txtFechaFin').val(),Descripcion:$('#txtDescripcion').val(),UsuarioCreacion:$('#hdCodUsuario').val(),UsuarioModificacion:$('#hdCodUsuario').val()};
		},
		Save : function ( ) {

				$.ajax({
					   url : this.url,
					   type:'POST',
					   dataType : 'json',
					   data : {
						   command:'campania',
						   action:'save_campania',
						   Servicio:$('#hdCodServicio').val(),
						   Nombre:$('#txtCampania').val(),
						   FechaInicio:$('#txtFechaInicio').val(),
						   FechaFin:$('#txtFechaFin').val(),
						   Descripcion:$('#txtDescripcion').val(),
						   UsuarioCreacion:$('#hdCodUsuario').val()
						   },
					   beforeSend : function ( ) {
						   		_displayBeforeSend('Guardando Campa&ntilde;a...',320);
						   },
					   success : function ( obj ) {
						   		_noneBeforeSend();
								if(obj.rst){
									$('#'+CampaniaDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
									$('#'+CampaniaDAO.idLayerMessage).effect('pulsate',{},1500,function(){ $(this).empty(); });
									cancel();
									reloadJQGRIDCampania();
								}else{
									$('#'+CampaniaDAO.idLayerMessage).html(templates.MsgError(obj.msg,'250px'));
									$('#'+CampaniaDAO.idLayerMessage).effect('pulsate',{},1500,function(){ $(this).empty(); });
								}
						   },
					   error : this.error_ajax
					   });
			},
		Delete : function ( ) {

				$.ajax({
					   url : this.url,
					   type:'POST',
					   dataType : 'json',
					   data : {
						   		command:'campania',
								action:'delete_campania',
								Id:$('#IdCampania').val(),
								UsuarioModificacion:$('#hdCodUsuario').val()
						   },
					   beforeSend : function ( ) {
						   		_displayBeforeSend('Eliminando Campa&ntilde;a...',320);
						   },
					   success : function ( obj ) {
						   		_noneBeforeSend();
								if(obj.rst){
									$('#'+CampaniaDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
									$('#'+CampaniaDAO.idLayerMessage).effect('pulsate',{},1500,function(){ $(this).empty(); });
									cancel();
									reloadJQGRIDCampania();
								}else{
									$('#'+CampaniaDAO.idLayerMessage).html(templates.MsgError(obj.msg,'250px'));
									$('#'+CampaniaDAO.idLayerMessage).effect('pulsate',{},1500,function(){ $(this).empty(); });
								}
						   },
					   error : this.error_ajax
					   });
			},
		Update : function ( ) {
				
				$.ajax({
					   url : this.url,
					   type:'POST',
					   dataType : 'json',
					   data : {
						   		command:'campania',
								action:'update_campania',
								Id:$('#IdCampania').val(),
								Nombre:$('#txtCampania').val(),
								FechaInicio:$('#txtFechaInicio').val(),
								FechaFin:$('#txtFechaFin').val(),
								Descripcion:$('#txtDescripcion').val(),
								UsuarioModificacion:$('#hdCodUsuario').val()
						   },
					   beforeSend : function ( ) {
						   		_displayBeforeSend('Actualizando Campa&ntilde;a...',320);
						   },
					   success : function ( obj ) {
						   		_noneBeforeSend();
								if(obj.rst){
									$('#'+CampaniaDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
									$('#'+CampaniaDAO.idLayerMessage).effect('pulsate',{},1500,function(){ $(this).empty(); });
									cancel();
									reloadJQGRIDCampania();
								}else{
									$('#'+CampaniaDAO.idLayerMessage).html(templates.MsgError(obj.msg,'250px'));
									$('#'+CampaniaDAO.idLayerMessage).effect('pulsate',{},1500,function(){ $(this).empty(); });	
								}
						   },
					   error : this.error_ajax
					   });
			},
		DataById : function ( Campania ) {
				$.ajax({
					   url : this.url,
					   type : 'GET',
					   dataType : 'json',
					   data : {command:'campania',action:'DataById',Id:Campania},
					   success : function ( obj ) {
						   		if(obj.length==1){
						   			$('#txtCampania').val(obj[0].nombre);
									$('#txtFechaInicio').val(obj[0].fecha_inicio);
									$('#txtFechaFin').val(obj[0].fecha_fin);
									$('#txtDescripcion').val(obj[0].descripcion);
									$('#IdCampania').val(obj[0].idcampania);
									if(obj[0].usuario_creacion!=$('#hdCodUsuario').val()){
										$('#txtUsuarioCreacion').val(obj[0].nombre_usuario_creacion);	
									}
									_display_panel('panelNuevaCampania');
								}
						   },
					   error : this.error_ajax
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
		error_ajax : function ( ) {
				_noneBeforeSend();
				$('#'+CampaniaDAO.idLayerMessage).html(templates.MsgError('Error en el servidor','200px'));
				$('#'+CampaniaDAO.idLayerMessage).effect('pulsate',{},1500,function(){ $(this).empty(); });
			}
	}