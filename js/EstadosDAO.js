var EstadoPrioridadDAO = {
		url : '../controller/ControllerCobrast.php',
		idLayerMessage : 'layerMessage',
		ListarTipoTransaccion : function ( ) {
				$.ajax({
						url : EstadoPrioridadDAO.url,
						type : 'GET',
						dataType : 'json',
						data : {command:'estado_prioridad',action:'ListarTipoTransaccion'},
						beforeSend : function ( ) {
								
							},
						success : function ( obj ) {
								templates.combo(obj,'tFormTableEstado #cbTipoTransaccion');
							},
						error : function ( ) {
								
							}
					});
			},
		Estados : {
				saveEstado : function ( idLayer, f_execute ) {
						$.ajax({
								url : EstadoPrioridadDAO.url,
								type : 'POST',
								dataType : 'json',
								data : {
										command : 'estado_prioridad',
										action : 'insert_estado',
										Servicio : $('#hdCodServicio').val(),
										TipoTransaccion : $('#'+idLayer+' #cbTipoTransaccion').val(),
										Nombre : $('#'+idLayer+' #txtNombreEstado').val(),
										Peso : $('#'+idLayer+' #txtPesoEstado').val(),
										Descripcion : $('#'+idLayer+' #txtDescripcionEstado').val(),
										UsuarioCreacion : $('#hdCodUsuario').val()
										},
								beforeSend : function ( ) {
										_displayBeforeSend('Guardando estado...',320);
									},
								success : function ( obj ) {
										_noneBeforeSend();
										if( obj.rst ) {
											f_execute();
											$('#'+EstadoPrioridadDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'400px'));
											EstadoPrioridadDAO.setTimeOut_hide_message();
										}else{
											$('#'+EstadoPrioridadDAO.idLayerMessage).html(templates.MsgError(obj.msg,'400px'));
											EstadoPrioridadDAO.setTimeOut_hide_message();
										}
									},
								error : function ( ) {
										_noneBeforeSend();
									}
							});
					},
				updateEstado : function ( idLayer, f_execute ) {
						$.ajax({
								url : EstadoPrioridadDAO.url,
								type : 'POST',
								dataType : 'json',
								data : {
										command:'estado_prioridad',
										action:'update_estado',
										Id : $('#'+idLayer+' #IdEstado').val(),
										TipoTransaccion : $('#'+idLayer+' #cbTipoTransaccion').val(),
										Nombre : $('#'+idLayer+' #txtNombreEstado').val(),
										Peso : $('#'+idLayer+' #txtPesoEstado').val(),
										Descripcion : $('#'+idLayer+' #txtDescripcionEstado').val(),
										UsuarioModificacion : $('#hdCodUsuario').val()
										},
								beforeSend : function ( ) {
										_displayBeforeSend('Actualizando estado...',320);
									},
								success : function ( obj ) {
										_noneBeforeSend();
										if( obj.rst ) {
											f_execute();
											$('#'+EstadoPrioridadDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'400px'));
											EstadoPrioridadDAO.setTimeOut_hide_message();
										}else{
											$('#'+EstadoPrioridadDAO.idLayerMessage).html(templates.MsgError(obj.msg,'400px'));
											EstadoPrioridadDAO.setTimeOut_hide_message();
										}
									},
								error : function ( ) {
										_noneBeforeSend();
									}
							});
					},
				deleteEstado : function ( idLayer, f_execute ) {
						$.ajax({
								url : EstadoPrioridadDAO.url,
								type : 'POST',
								dataType : 'json',
								data : {
										command : 'estado_prioridad',
										action : 'delete_estado',
										Id : $('#'+idLayer+' #IdEstado').val(),
										UsuarioModificacion : $('#hdCodUsuario').val()
										},
								beforeSend : function ( ) {
										_displayBeforeSend('Eliminando estado...',320);
									},
								success : function ( obj ) {
										_noneBeforeSend();
										if( obj.rst ) {
											f_execute();
											$('#'+EstadoPrioridadDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'400px'));
											EstadoPrioridadDAO.setTimeOut_hide_message();
										}else{
											$('#'+EstadoPrioridadDAO.idLayerMessage).html(templates.MsgError(obj.msg,'400px'));
											EstadoPrioridadDAO.setTimeOut_hide_message();
										}
									},
								error : function ( ) {
										_noneBeforeSend();
									}
							});
					},
				getParam : function ( idEstado, f_fill ) {
						$.ajax({
								url : EstadoPrioridadDAO.url,
								type : 'GET',
								dataType : 'json',
								data : {command:'estado_prioridad',action:'getParamEstado',Id:idEstado},
								beforeSend : function ( ) {
										_displayBeforeSend('Trayendo datos...',320);
									},
								success : function ( obj ) {
										_noneBeforeSend();
										f_fill(obj);
									},
								error : function ( ) {
										_noneBeforeSend();
										
									}
							});
					}
			},
		Prioridad : {
				savePrioridad : function ( layer, idEstado, f_execute ) {
						$.ajax({
								url : EstadoPrioridadDAO.url,
								type : 'POST',
								dataType : 'json',
								data : {
										command : 'estado_prioridad',
										action : 'insert_prioridad',
										EstadoTransaccion : idEstado,
										Peso : $('#'+layer+' #txtPesoPrioridad').val(),
										UsuarioCreacion : $('#hdCodUsuario').val()
										},
								beforeSend : function ( ) {
										_displayBeforeSend('Guardando prioridad...',320);
									},
								success : function ( obj ) {
										_noneBeforeSend();
										if( obj.rst ) {
											f_execute();
											$('#'+EstadoPrioridadDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'400px'));
											EstadoPrioridadDAO.setTimeOut_hide_message();
										}else{
											$('#'+EstadoPrioridadDAO.idLayerMessage).html(templates.MsgError(obj.msg,'400px'));
											EstadoPrioridadDAO.setTimeOut_hide_message();
										}
									},
								error : function ( ) {
										_noneBeforeSend();
									}
							});
					},
				updatePrioridad : function ( layer, idEstado, f_execute ) {
						$.ajax({
								url : EstadoPrioridadDAO.url,
								type : 'POST',
								dataType : 'json',
								data : {
										command : 'estado_prioridad',
										action : 'update_prioridad',
										Id : $('#'+layer+' #HdIdPrioridad').val(),
										EstadoTransaccion : idEstado,
										Peso : $('#'+layer+' #txtPesoPrioridad').val(),
										UsuarioModificacion : $('#hdCodUsuario').val()
										},
								beforeSend : function ( ) {
										_displayBeforeSend('Actualizando prioridad...',320);
										
									},
								success : function ( obj ) {
										_noneBeforeSend();
										if( obj.rst ) {
											f_execute();
											$('#'+EstadoPrioridadDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'400px'));
											EstadoPrioridadDAO.setTimeOut_hide_message();
										}else{
											$('#'+EstadoPrioridadDAO.idLayerMessage).html(templates.MsgError(obj.msg,'400px'));
											EstadoPrioridadDAO.setTimeOut_hide_message();
										}
									},
								error : function ( ) {
										_noneBeforeSend();
										
									}
							});
					},
				deletePrioridad : function ( layer, f_execute ) {
						$.ajax({
								url : EstadoPrioridadDAO.url,
								type : 'POST',
								dataType : 'json',
								data : {
										command : 'estado_prioridad',
										action : 'delete_prioridad',
										Id:$('#'+layer+' #HdIdPrioridad').val(),
										UsuarioModificacion : $('#hdCodUsuario').val()
										},
								beforeSend : function ( ) {
										_displayBeforeSend('Eliminando prioridad...',320);
									},
								success : function ( obj ) {
										_noneBeforeSend();
										if( obj.rst ) {
											f_execute();
											$('#'+EstadoPrioridadDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'400px'));
											EstadoPrioridadDAO.setTimeOut_hide_message();
										}else{
											$('#'+EstadoPrioridadDAO.idLayerMessage).html(templates.MsgError(obj.msg,'400px'));
											EstadoPrioridadDAO.setTimeOut_hide_message();
										}
									},
								error : function ( ) {
										_noneBeforeSend();
									}
							});
					},
				getParam : function ( idPrioridad, f_fill ) {
						$.ajax({
								url : EstadoPrioridadDAO.url,
								type : 'GET',
								dataType : 'json',
								data : {command:'estado_prioridad',action:'getParamPrioridad',Id:idPrioridad},
								beforeSend : function ( ) {
										_displayBeforeSend('Trayendo datos...',320);
									},
								success : function ( obj ) {
										_noneBeforeSend();
										f_fill(obj);
									},
								error : function ( ) {
										_noneBeforeSend();
										
									}
							});
					}
				},
			Fill : {
					EstadoForm : function ( obj ) {
							$('#tFormTableEstado #IdEstado').val(obj[0].idestado_transaccion);
							$('#tFormTableEstado #txtNombreEstado').val(obj[0].nombre);
							$('#tFormTableEstado #txtPesoEstado').val(obj[0].peso);
							$('#tFormTableEstado #txtDescripcionEstado').val(obj[0].descripcion);
							$('#tFormTableEstado #cbTipoTransaccion').val(obj[0].idtipo_transaccion);
							$('#pinPanelNuevoEstadoPrioridad').trigger('click');
						},
					PrioridadForm : function ( obj ) {
							$('#dialogPrioridad #HdIdPrioridad').val(obj[0].idpeso_transaccion);
							$('#dialogPrioridad #txtPesoPrioridad').val(obj[0].peso);
							$('#dialogPrioridad').dialog('open');
						},
				},
			hide_message : function ( ) {
					$('#'+EstadoPrioridadDAO.idLayerMessage).effect('blind',{direction:'vertical'},'slow',function(){ $(this).empty().css('display','block'); });
				
				},
			setTimeOut_hide_message : function ( ) {
					setTimeout("EstadoPrioridadDAO.hide_message()",4000);
				}
		
	}