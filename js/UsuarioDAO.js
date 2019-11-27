var UsuarioDAO={
    url:'../controller/ControllerCobrast.php',
	idLayerMessage : 'layerMessage',
	speedLayerMessage : 1500,
	retornarData : function ( ) {
		var dataJson={Id:$('#Usuario').val(),Nombre:$('#Nombre').val(),Paterno:$('#Paterno').val(),Materno:$('#Materno').val(),Dni:$('#DNI').val(),Clave:$('#Clave').val(),UsuarioCreacion:$('#hdCodUsuario').val(),UsuarioModificacion:$('#hdCodUsuario').val(),Email:$('#Email').val(),Servicio:$('#hdCodServicio').val(),TipoUsuario:$('#TipoUsuario').val(),Privilegio:$('#Privilegio').val(),FechaInicio:$('#FechaInicio').val(),FechaFin:$('#FechaFin').val()};
		return dataJson;
	},
    insert : function ( ) {
		
        $.ajax({
            url : this.url ,
            type : 'POST' ,
            dataType : 'json',
            data : {
					command:'usuario',
					action:'save_usuario',
					Nombre:$('#Nombre').val(),
					Paterno:$('#Paterno').val(),
					Materno:$('#Materno').val(),
					Dni:$('#DNI').val(),
					Clave:$('#Clave').val(),
					UsuarioCreacion:$('#hdCodUsuario').val(),
					Email:$('#Email').val(),
					Servicio:$('#hdCodServicio').val(),
					TipoUsuario:$('#TipoUsuario').val(),
					Privilegio:$('#Privilegio').val(),
					FechaInicio:$('#FechaInicio').val(),
					FechaFin:$('#FechaFin').val()
					} ,
			beforeSend : function ( ) {
					_displayBeforeSend('Grabando Usuario...',300);
				},
            success : function ( obj ) {
					_noneBeforeSend();
					if(obj.rst){
						$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
						UsuarioDAO.setTimeOut_hide_message();
						cancel();
						$('#table_user_teleoperador').jqGrid().trigger('reloadGrid');
					}else{
						$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError(obj.msg,'250px'));
						UsuarioDAO.setTimeOut_hide_message();
					}
				} ,
            error : this.error_ajax
        });
    },
	update : function ( ) {
		$.ajax({
            url : this.url ,
            type : 'POST' ,
            dataType : 'json' ,
            data : {
					command:'usuario',
					action:'update_usuario',
					Id:$('#IdUsuario').val(),
					UsuarioServicio:$('#IdUsuarioServicio').val(),
					Nombre:$('#Nombre').val(),
					Paterno:$('#Paterno').val(),
					Materno:$('#Materno').val(),
					Dni:$('#DNI').val(),
					Email:$('#Email').val(),
					UsuarioModificacion:$('#hdCodUsuario').val(),
					Servicio:$('#hdCodServicio').val(),
					TipoUsuario:$('#TipoUsuario').val(),
					Privilegio:$('#Privilegio').val(),
					FechaInicio:$('#FechaInicio').val(),
					FechaFin:$('#FechaFin').val()
					},
			beforeSend : function ( ) {
					_displayBeforeSend('Actualizando Usuario...',300);
				},
            success : function ( obj ) {
					_noneBeforeSend();
					if(obj.rst){
						$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
						UsuarioDAO.setTimeOut_hide_message();
						cancel();
						$('#table_user_teleoperador').jqGrid().trigger('reloadGrid');
					}else{
						$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError(obj.msg,'250px'));
						UsuarioDAO.setTimeOut_hide_message();
					}
				} ,
            error : this.error_ajax
        });
    },
    Delete : function ( ) {
		
		$.ajax({
            url : this.url ,
            type : 'POST' ,
            dataType : 'json' ,
            data: {
					command:'usuario',
					action:'delete_usuario',
					UsuarioServicio:$('#IdUsuarioServicio').val(),
					UsuarioModificacion:$('#hdCodUsuario').val()
					} ,
			beforeSend : function ( ) {
					_displayBeforeSend('Eliminando Usuario...',300);
				},
            success : function ( obj ) {
					_noneBeforeSend();
					if(obj.rst){
						$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
						UsuarioDAO.setTimeOut_hide_message();
						cancel();
						$('#table_user_teleoperador').jqGrid().trigger('reloadGrid');
					}else{
						$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError(obj.msg,'250px'));
						UsuarioDAO.setTimeOut_hide_message();
					}
				},
            error : this.error_ajax
        });
    },
	ListarServicios : function ( ) {
		$.ajax({
			   url : this.url,
			   type : 'GET',
			   dataType : 'json',
			   data : {command:'usuario',action:'ListarServicio'},
			   success : function ( obj ) {
				   		var html='';
				   		$.each(obj,function(key,data){
							html+='<option value="'+data.id+'">'+data.nombre+'</option>';
						});
						$("#cbServicioSystem").html(html);
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
						$("#TipoUsuario").html(html);
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
						$("#Privilegio").html(html);
				   },
			   error : this.error_ajax
			   });	
	},
	DataById : function ( id ) {
					$.ajax({
						   url : this.url,
						   type : 'GET',
						   dataType : 'json',
						   data : {command:'usuario',action:'DataById',UsuarioServicio:id},
						   beforeSend : function ( ) {
							   		_displayBeforeSend('Trayendo datos...',300);
							   },
						   success : function ( obj ){
							   		_noneBeforeSend();
									if(obj.length==1){
										
										$('#IdUsuarioServicio').val(obj[0].idusuario_servicio);
										$('#IdUsuario').val(obj[0].idusuario);
										$('#Nombre').val(obj[0].nombre);
										$('#Paterno').val(obj[0].paterno);
										$('#Materno').val(obj[0].materno);
										$('#DNI').val(obj[0].dni);
										$('#Email').val(obj[0].email);
										$('#TipoUsuario').val(obj[0].idtipo_usuario)
										$('#Privilegio').val(obj[0].idprivilegio)
										$('#FechaInicio').val(obj[0].fecha_inicio);
										$('#FechaFin').val(obj[0].fecha_fin);
										
										if(obj[0].usuario_creacion!=$('#hdCodUsuario').val()){
											$('#UsuarioCreacion').val(obj[0].nombre_usuario_creacion);
										}
										
										_display_panel('panelNuevoUsuario');
										
									}
							   },
						   error : function ( ) {
							   	_noneBeforeSend();
							   	$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Error al traer datos','200px'));
								UsuarioDAO.setTimeOut_hide_message();
							   }
						   });
	},
	DataMantenimientoCluster : function ( idCluster, f_fill ) {
		$.ajax({
				url : UsuarioDAO.url,
				type : 'GET',
				dataType : 'json',
				data : {command:'usuario',action:'load_mantenimiento_cluster',Id:idCluster},
				beforeSend : function ( ) {
						_displayBeforeSend('Trayendo datos...',300);
					},
				success : function ( obj ) {
						_noneBeforeSend();
						f_fill(obj);
					},
				error : function ( ) {
						_noneBeforeSend();
					}
			});
	},
	FillFormDialogMantenimientoCluster : function ( obj ) {
		if( obj.length!=0 ){
			$('#IdClusterUpd').val(obj[0].idcluster);
			$('#nombreClusterUpd').val(obj[0].nombre);
			$('#descripClusterUpd').val(obj[0].descripcion);
			$('#estadoClusterUpd').val(obj[0].estado);
		}
	},
	update_cluster_servicio : function ( idCluster ) {
		$.ajax({
				url : UsuarioDAO.url,
				type : 'POST',
				dataType : 'json',
				data : {
						command:'usuario',action:'update_cluster_servicio',
						Id:idCluster,
						idServicio : $('#hdCodServicio').val(),
						nombre:$('#nombreClusterUpd').val(),
						descripcion:$('#descripClusterUpd').val(),
						estado:$('#estadoClusterUpd').val(),
						usuarioModificacion:$('#hdCodUsuario').val()
						},
				beforeSend : function ( ) {
						_displayBeforeSend('Actualizando servicio...',300);
					},
				success : function ( obj ) {
						_noneBeforeSend();
						if(obj.rst){
							$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
							$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},UsuarioDAO.speedLayerMessage,function(){ $(this).empty(); });
							cancel_dialog('dialogMantenimientoClusterEdit');
							$("#table_mantenimiento_cluster").jqGrid().trigger('reloadGrid');
							UsuarioDAO.ListarClusterServicio();
						}else{
							$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError(obj.msg,'350px'));
							$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},UsuarioDAO.speedLayerMessage,function(){ $(this).empty(); });
						}
					},
				error : function ( ) {
						_noneBeforeSend();
					}
			});
	},
	InsertClusterServicio : function ( idCluster ) {
		$.ajax({
				url : UsuarioDAO.url,
				type : 'POST',
				dataType : 'json',
				data : {
						command:'usuario',action:'insert_cluster_servicio',
						idServicio : $('#hdCodServicio').val(),
						nombre:$('#nombreClusterAdd').val(),
						descripcion:$('#descripClusterAdd').val(),
						usuarioCreacion:$('#hdCodUsuario').val()
						},
				beforeSend : function ( ) {
						_displayBeforeSend('Guardando servicio...',300);
					},
				success : function ( obj ) {
						_noneBeforeSend();
						if(obj.rst){
							$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
							$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},UsuarioDAO.speedLayerMessage,function(){ $(this).empty(); });
							cancel_dialog('dialogMantenimientoClusterAdd');
							$("#table_mantenimiento_cluster").jqGrid().trigger('reloadGrid');
							UsuarioDAO.ListarClusterServicio();
						}else{
							$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError(obj.msg,'350px'));
							$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},UsuarioDAO.speedLayerMessage,function(){ $(this).empty(); });
						}
					},
				error : function ( ) {
						_noneBeforeSend();
					}
			});
	},
	InsertClusterServicioOperador : function ( xidususer,xidcluster ) {
		$.ajax({
				url : UsuarioDAO.url,
				type : 'POST',
				dataType : 'json',
				data : {
						command:'usuario',action:'insert_cluster_servicio_operador',
						idususer:xidususer,
						idcluster:xidcluster,
						usuarioCreacion:$('#hdCodUsuario').val()
						},
				beforeSend : function ( ) {
						_displayBeforeSend('Agregando Cluster a Operador...',300);
					},
				success : function ( obj ) {
						_noneBeforeSend();
						if(obj.rst){
							//$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
							//$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},UsuarioDAO.speedLayerMessage,function(){ $(this).empty(); });
							//cancel_dialog('dialogMantenimientoClusterAdd');
							listar_data_cluster_operador_detalle(xidususer);
						}else{
							$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError(obj.msg,'350px'));
							$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},UsuarioDAO.speedLayerMessage,function(){ $(this).empty(); });
						}
					},
				error : function ( ) {
						_noneBeforeSend();
					}
			});
	},
	ChangeEstadoUsuSerCluster : function ( xidususerclu,xusuario_modificacion, f_success ) {
			$.ajax({
					url : this.url,
					type : 'POST',
					dataType : 'json',
					data : { 
							command : 'usuario', 
							action : 'change_state_ususerclu', 
							idususerclu : xidususerclu,
							usuario_modificacion : xusuario_modificacion
							}, 
					beforeSend : function ( ) {
							_displayBeforeSend('Cambiando Estado...',320);
						},
					success : function ( obj ) {
							_noneBeforeSend();
							f_success( obj );
						},
					error : function ( ) {
							_noneBeforeSend();
							UsuarioDAO.error_ajax();
						}
				});
			
		},	
	ListarClusterServicio : function ( ) {
		$.ajax({
			   url : this.url,
			   type : 'GET',
			   dataType : 'json',
			   data : {command:'usuario',action:'load_data_cluster_servicio'},
			   success : function ( obj ) {
				   		var html='<option value="0">--Selec--</option>';
				   		$.each(obj,function(key,data){
							html+='<option value="'+data.idcluster+'">'+data.nombre+'</option>';
						});
						$("#tipoCluster").html(html);
				   },
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
		},
	ListarNotificador : function ( xidservicio, f_success ) {
			
			$.ajax({
					url : this.url,
					type : 'GET',
					dataType : 'json',
					data : { command : 'usuario', action : 'ListarNotificadores', idservicio : xidservicio },
					beforeSend : function ( ) {},
					success : function ( obj ) {
							f_success(obj);
						}
				});
			
		},
	ListarNotificadorPorId : function ( xidnotificador, f_success ) {
			
			$.ajax({
					url : this.url,
					type : 'GET',
					dataType : 'json',
					data : { command : 'usuario', action : 'ListarNotificadorPorId', idnotificador : xidnotificador },
					beforeSend : function ( ) {},
					success : function ( obj ) {
							f_success(obj);
						},
					error : function ( ) {
							
						}
				});
			
		},
	InsertNotificador : function ( xidservicio, xnombre, xpaterno, xmaterno, xcorreo, xtelefono, xdireccion, xusuario_creacion, f_success ) {
			
			$.ajax({
					url : this.url,
					type : 'POST',
					dataType : 'json',
					data : { 
							command : 'usuario', 
							action : 'insert_notificador', 
							idservicio : xidservicio, 
							nombre : xnombre, 
							paterno : xpaterno, 
							materno : xmaterno, 
							correo : xcorreo ,
							telefono : xtelefono,
							direccion : xdireccion,
							usuario_creacion : xusuario_creacion
							}, 
					beforeSend : function ( ) {
							_displayBeforeSend('Guardando Notificador...',320);
						},
					success : function ( obj ) {
							_noneBeforeSend();
							f_success( obj );
						},
					error : function ( ) {
							_noneBeforeSend();
							UsuarioDAO.error_ajax();
						}
				});
			
		},
	UpdateNotificador : function ( xidnotificador, xnombre, xpaterno, xmaterno, xcorreo, xtelefono, xdireccion, xusuario_modificacion, f_success ) {
			
			$.ajax({
					url : this.url,
					type : 'POST',
					dataType : 'json',
					data : { 
							command : 'usuario', 
							action : 'update_notificador', 
							idnotificador : xidnotificador, 
							nombre : xnombre, 
							paterno : xpaterno, 
							materno : xmaterno, 
							correo : xcorreo ,
							telefono : xtelefono,
							direccion : xdireccion,
							usuario_modificacion : xusuario_modificacion
							}, 
					beforeSend : function ( ) {
							_displayBeforeSend('Actualizando Notificador...',320);
						},
					success : function ( obj ) {
							_noneBeforeSend();
							f_success( obj );
						},
					error : function ( ) {
							_noneBeforeSend();
							UsuarioDAO.error_ajax();
						}
				});
			
		},	
	DeleteNotificador : function ( xidnotificador, xusuario_modificacion, f_success ) {
			
			$.ajax({
					url : this.url,
					type : 'POST',
					dataType : 'json',
					data : { 
							command : 'usuario', 
							action : 'delete_notificador', 
							idnotificador : xidnotificador,
							usuario_modificacion : xusuario_modificacion
							}, 
					beforeSend : function ( ) {
							_displayBeforeSend('Eliminando Notificador...',320);
						},
					success : function ( obj ) {
							_noneBeforeSend();
							f_success( obj );
						},
					error : function ( ) {
							_noneBeforeSend();
							UsuarioDAO.error_ajax();
						}
				});
			
		},
	ListarOperadorCluster : function ( xidservicio, f_success ) {
			$.ajax({
					url : this.url,
					type : 'GET',
					dataType : 'json',
					data : { command : 'usuario', action : 'ListarOperadorCluster', idservicio : xidservicio },
					beforeSend : function ( ) {},
					success : function ( obj ) {
							f_success(obj);
						}
				});
			
		},	
	ListarOperadorClusterDetalle : function ( xidusuario_servicio, f_success ) {
			$.ajax({
					url : this.url,
					type : 'GET',
					dataType : 'json',
					data : { command : 'usuario', action : 'ListarOperadorClusterDetalle', idusuario_servicio : xidusuario_servicio },
					beforeSend : function ( ) {},
					success : function ( obj ) {
							f_success(obj);
						}
				});
			
		},	
	
	ListarCentroCosto : function ( f_success ) {
			
			$.ajax({
					url : this.url,
					type : 'POST',
					dataType : 'json',
					data : { command : 'usuario', action : 'listar_centro_costo' }, 
					beforeSend : function ( ) {},
					success : function ( obj ) {
							f_success( obj );
						},
					error : function ( ) {}
				});
			
		},
	hide_message : function ( ) {
			$('#'+UsuarioDAO.idLayerMessage).effect('blind',{direction:'vertical'},'slow',function(){ $(this).empty().css('display','block'); });
		
		},
	setTimeOut_hide_message : function ( ) {
			setTimeout("UsuarioDAO.hide_message()",4000);
		},
	error_ajax : function ( ) {
		_noneBeforeSend();
		$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Error en el servidor','200px'));
		$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},UsuarioDAO.speedLayerMessage,function(){ $(this).empty(); });
	}
}