// JavaScript Document
var UsuarioAdminDAO = {
		url : '../controller/ControllerCobrast.php',
		idLayerMessage : 'layerMessage',
		ListarTipoUsuario : function ( ) {
			$.ajax({
				   url : this.url,
				   type : 'GET',
				   dataType : 'json',
				   data : {command:'usuario_admin',action:'ListarTipoUsuario'},
				   success : function ( obj ) {
							var html='';
							html+='<option value="0">--Seleccione--</option>';
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
				   data : {command:'usuario_admin',action:'ListarPrivilegios'},
				   success : function ( obj ) {
							var html='';
							html+='<option value="0">--Seleccione--</option>';
							$.each(obj,function(key,data){
								html+='<option value="'+data.id+'">'+data.nombre+'</option>';
							});
							$("#Privilegio").html(html);
					   },
				   error : this.error_ajax
				   });	
		},
		ListarServicio : function ( ) {
			$.ajax({
				   url : this.url,
				   type : 'GET',
				   dataType : 'json',
				   data : {command:'usuario_admin',action:'ListarServicio'},
				   success : function ( obj ) {
							var html='';
								html+='<option value="0">--Seleccione--</option>';
							$.each(obj,function(key,data){
								html+='<option value="'+data.id+'">'+data.nombre+'</option>';
							});
							$("#cbServicio").html(html);
					   },
				   error : this.error_ajax
				   });	
		},
		insert : function ( ) {
		
			$.ajax({
				url : this.url ,
				type : 'POST' ,
				dataType : 'json',
				data : {
						command:'usuario_admin',
						action:'save_usuario',
						Nombre : $.trim( $('#Nombre').val() ),
						Paterno : $.trim( $('#Paterno').val() ),
						Materno : $.trim( $('#Materno').val() ),
						Dni : $.trim( $('#DNI').val() ),
						Codigo : $.trim( $('#Codigo').val() ),
						Celular : $.trim( $('#Celular').val() ),
						Telefono : $.trim( $('#Telefono').val() ),
						Telefono2 : $.trim( $('#Telefono2').val() ),
						Direccion : $.trim( $('#Direccion').val() ),
						FechaNacimiento : $.trim( $('#FechaNacimiento').val() ),
						EstadoCivil : $.trim( $('#EstadoCivil').val() ),
						Genero : $.trim( $('#Genero').val() ),
						TipoTrabajo : $.trim( $('#TipoTrabajo').val() ),
						Planilla : $.trim( $('#Planilla').val() ),
						Clave : $.trim( $('#Clave').val() ),
						UsuarioCreacion:$('#hdCodUsuario').val(),
						Email:$('#Email').val()
						} ,
				beforeSend : function ( ) {
						_displayBeforeSend('Grabando Usuario...',300);
					},
				success : function ( obj ) {
						_noneBeforeSend();
						if(obj.rst){
							$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
							$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},UsuarioDAO.speedLayerMessage,function(){ $(this).empty(); });
							cancel();
							$('#table_user_admin').jqGrid().trigger('reloadGrid');
							$('#table_servicios_usuario').jqGrid('setGridParam',{url:'../controller/ControllerCobrast.php?command=usuario_admin&action=jqgrid_servicios_usuario'}).trigger('reloadGrid');
						}else{
							$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError(obj.msg,'250px'));
							$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},UsuarioDAO.speedLayerMessage,function(){ $(this).empty(); });
						}
					} ,
				error : this.error_ajax
			});
		},
		update : function ( ) {
			$.ajax({
				url : this.url ,
				type : 'POST' ,
				dataType : 'json',
				data : {
						command:'usuario_admin',
						action:'update_usuario',
						Id:$('#IdUsuario').val(),
						Nombre : $.trim( $('#Nombre').val() ),
						Paterno : $.trim( $('#Paterno').val() ),
						Materno : $.trim( $('#Materno').val() ),
						Dni : $.trim( $('#DNI').val() ),
						Codigo : $.trim( $('#Codigo').val() ),
						Celular : $.trim( $('#Celular').val() ),
						Telefono : $.trim( $('#Telefono').val() ),
						Telefono2 : $.trim( $('#Telefono2').val() ),
						Direccion : $.trim( $('#Direccion').val() ),
						FechaNacimiento : $.trim( $('#FechaNacimiento').val() ),
						EstadoCivil : $.trim( $('#EstadoCivil').val() ),
						Genero : $.trim( $('#Genero').val() ),
						TipoTrabajo : $.trim( $('#TipoTrabajo').val() ),
						Planilla : $.trim( $('#Planilla').val() ),
						Clave : $.trim( $('#Clave').val() ),
						UsuarioModificacion:$('#hdCodUsuario').val(),
						Email:$('#Email').val()
						} ,
				beforeSend : function ( ) {
						_displayBeforeSend('Actualizando Usuario...',300);
					},
				success : function ( obj ) {
						_noneBeforeSend();
						if(obj.rst){
							$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
							$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},UsuarioDAO.speedLayerMessage,function(){ $(this).empty(); });
							cancel();
							$('#table_user_admin').jqGrid().trigger('reloadGrid');
							$('#table_servicios_usuario').jqGrid('setGridParam',{url:'../controller/ControllerCobrast.php?command=usuario_admin&action=jqgrid_servicios_usuario'}).trigger('reloadGrid');
						}else{
							$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError(obj.msg,'250px'));
							$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},UsuarioDAO.speedLayerMessage,function(){ $(this).empty(); });
						}
					} ,
				error : this.error_ajax
			});
		},
		Delete : function ( ) {
			$.ajax({
				url : this.url ,
				type : 'POST' ,
				dataType : 'json',
				data : {
						command:'usuario_admin',
						action:'delete_usuario',
						Id:$('#IdUsuario').val(),
						UsuarioModificacion:$('#hdCodUsuario').val()
						} ,
				beforeSend : function ( ) {
						_displayBeforeSend('Eliminando Usuario...',300);
					},
				success : function ( obj ) {
						_noneBeforeSend();
						if(obj.rst){
							$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
							$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},UsuarioDAO.speedLayerMessage,function(){ $(this).empty(); });
							cancel();
							$('#table_user_admin').jqGrid().trigger('reloadGrid');
							$('#table_servicios_usuario').jqGrid('setGridParam',{url:'../controller/ControllerCobrast.php?command=usuario_admin&action=jqgrid_servicios_usuario'}).trigger('reloadGrid');
						}else{
							$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError(obj.msg,'250px'));
							$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},UsuarioDAO.speedLayerMessage,function(){ $(this).empty(); });
						}
					} ,
				error : this.error_ajax
			});
		},
		DataUsuario : function ( idUsuario, f_fill ) {
			$.ajax({
					url : UsuarioAdminDAO.url,
					type : 'GET',
					dataType : 'json',
					data : {command:'usuario_admin',action:'load_data_usuario',Usuario:idUsuario},
					beforeSend : function ( ) {
							_displayBeforeSend('Trayendo datos de usuario...',300);
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
		InsertUsuarioServicio : function ( idUsuario ) {
			$.ajax({
					url : UsuarioAdminDAO.url,
					type : 'POST',
					dataType : 'json',
					data : {
							command:'usuario_admin',
							action:'insert_usuario_servicio',
							Usuario:idUsuario,
							TipoUsuario:$('#TipoUsuario').val(),
							Privilegio:$('#Privilegio').val(),
							Servicio:$('#cbServicio').val(),
							FechaInicio:$('#FechaInicio').val(),
							FechaFin:$('#FechaFin').val(),
							UsuarioCreacion:$('#hdCodUsuario').val()
							},
					beforeSend : function ( ) {
							_displayBeforeSend('Guardando servicio...',300);
						},
					success : function ( obj ) {
							_noneBeforeSend();
							if(obj.rst){
								$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
								$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},UsuarioDAO.speedLayerMessage,function(){ $(this).empty(); });
								cancel_usuario_servicio();
								$("#table_servicios_usuario").jqGrid().trigger('reloadGrid');
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
		update_usuario_servicio : function ( idUsuario ) {
			$.ajax({
					url : UsuarioAdminDAO.url,
					type : 'POST',
					dataType : 'json',
					data : {
							command:'usuario_admin',
							action:'update_usuario_servicio',
							Id:$('#IdUsuarioServicio').val(),
							Usuario : idUsuario ,
							TipoUsuario:$('#TipoUsuario').val(),
							Privilegio:$('#Privilegio').val(),
							Servicio:$('#cbServicio').val(),
							FechaInicio:$('#FechaInicio').val(),
							FechaFin:$('#FechaFin').val(),
							UsuarioModificacion:$('#hdCodUsuario').val()
							},
					beforeSend : function ( ) {
							_displayBeforeSend('Actualizando servicio...',300);
						},
					success : function ( obj ) {
							_noneBeforeSend();
							if(obj.rst){
								$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
								$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},UsuarioDAO.speedLayerMessage,function(){ $(this).empty(); });
								cancel_usuario_servicio();
								$("#table_servicios_usuario").jqGrid().trigger('reloadGrid');
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
		delete_usuario_servicio : function ( ) {
			$.ajax({
					url : UsuarioAdminDAO.url,
					type : 'POST',
					dataType : 'json',
					data : {
							command:'usuario_admin',
							action:'delete_usuario_servicio',
							Id:$('#IdUsuarioServicio').val(),
							UsuarioModificacion:$('#hdCodUsuario').val()
							},
					beforeSend : function ( ) {
							_displayBeforeSend('Eliminando servicio...',300);
						},
					success : function ( obj ) {
							_noneBeforeSend();
							if(obj.rst){
								$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
								$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},UsuarioDAO.speedLayerMessage,function(){ $(this).empty(); });
								cancel_usuario_servicio();
								$("#table_servicios_usuario").jqGrid().trigger('reloadGrid');
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
		DataServicioUsuario : function ( idServicioUsuario, f_fill ) {
			$.ajax({
					url : UsuarioAdminDAO.url,
					type : 'GET',
					dataType : 'json',
					data : {command:'usuario_admin',action:'load_servicio_usuario',Id:idServicioUsuario},
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
		FillFormServicioUsuario : function ( obj ) {
			if( obj.length!=0 ){
				$('#IdUsuarioServicio').val(obj[0].idusuario_servicio);
				$('#cbServicio').val(obj[0].idservicio);
				$('#TipoUsuario').val(obj[0].idtipo_usuario);
				$('#Privilegio').val(obj[0].idprivilegio);
				$('#FechaInicio').val(obj[0].fecha_inicio);
				$('#FechaFin').val(obj[0].fecha_fin);
			}
		},
		FillFormUsuario : function ( obj ) {
			//var rs = $.parseJSON(obj);
			var rs = obj;
			if( rs.length!=0 ) {
				$('#IdUsuario').val(rs[0].idusuario);
				$('#Codigo').val(rs[0].codigo);
				$('#Nombre').val(rs[0].nombre);
				$('#Paterno').val(rs[0].paterno);
				$('#Materno').val(rs[0].materno);
				$('#DNI').val(rs[0].dni);
				$('#Email').val(rs[0].email);
				$('#Celular').val(rs[0].celular);
				$('#Telefono').val(rs[0].telefono);
				$('#Telefono2').val(rs[0].telefono2);
				$('#Direccion').val(rs[0].direccion);
				$('#FechaNacimiento').val(rs[0].fecha_nacimiento);
				if( rs[0].estado_civil != '' ) {
					$('#EstadoCivil').val( rs[0].estado_civil );
				}
				if( rs[0].genero != '' ) {
					$('#Genero').val( rs[0].genero );
				}
				if( rs[0].tipo_trabajo != '' ) {
					$('#TipoTrabajo').val( rs[0].tipo_trabajo );
				}
				if( rs[0].planilla != '' ) {
					$('#Planilla').val( rs[0].planilla );
				}
				$('#Clave').val('');
				$('#ConfClave').val('');
				$('#ApinNuevo').trigger('click');
			}
		},
		error_ajax : function ( ) {
			_noneBeforeSend();
			$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Error en el servidor','200px'));
			$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},UsuarioDAO.speedLayerMessage,function(){ $(this).empty(); });
		}
	}