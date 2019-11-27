var AyudaGestionUsuarioDAO = {
		url : '../controller/ControllerCobrast.php',
		idLayerMessage : 'layerMessage',
		ListarUsuarioServicio : function ( ) {
				$.ajax({
						url : AyudaGestionUsuarioDAO.url,
						type : 'GET',
						dataType : 'json',
						data : {command:'atencion_cliente',action:'ListarOperadores',Servicio:$('#hdCodServicio').val()},
						beforeSend : function ( ) {
								
							},
						success : function ( obj ) {
								AyudaGestionUsuarioDAO.FillUsuarioServicio(obj);
							},
						error : function ( ) {
								
							}
					});
			},
		FillUsuarioServicio : function ( obj ) {
				var html='';
					html+='<option value="0" >--Seleccione--</option>';
				for( i=0;i<obj.length;i++ ) {
					html+='<option value="'+obj[i].idusuario_servicio+'" >'+obj[i].nombre+'</option>';
				}
				$('#cbUsuarioServicio').html(html);
			},
		ListarCampanias : function ( ) {
				$.ajax({
						url : AyudaGestionUsuarioDAO.url,
						type : 'GET',
						dataType : 'json',
						data : {command:'distribucion',action:'ListCampania',Servicio:$('#hdCodServicio').val()},
						beforeSend : function ( ) {
								
							},
						success : function ( obj ) {
								AyudaGestionUsuarioDAO.FillCampania(obj);
							},
						error : function ( ) {}
					});
			},
		FillCampania : function ( obj ) {
				var html='';
					html+='<option value="0" >--Seleccione--</option>';
				for( i=0;i<obj.length;i++ ) {
					html+='<option value="'+obj[i].idcampania+'" >'+obj[i].nombre+'</option>';
				}
				$('#cbCampania').html(html);
			},
		ListarCartera : function ( idCampania, f_fill ) {
				$.ajax({
						url : AyudaGestionUsuarioDAO.url,
						type : 'GET',
						dataType : 'json',
						data : {command:'carga-cartera',action:'ListCartera',Campania:idCampania},
						beforeSend : function ( ) {
								
							},
						success : function ( obj ) {
								f_fill(obj);
							},
						error : function ( ) {}
					});
			},
		FillCartera : function ( obj ) {
				var html='';
					html+='<option value="0" >--Seleccione--</option>';
				for( i=0;i<obj.length;i++ ) {
					html+='<option value="'+obj[i].idcartera+'" >'+obj[i].nombre_cartera+'</option>';
				}
				$('#cbCartera').html(html);
			},
		ListarUsuariosAyudar : function ( ) {
				$.ajax({
						url : AyudaGestionUsuarioDAO.url,
						type : 'GET',
						dataType : 'json',
						data : {
								command:'ayuda_gestion_usuario',
								action:'ListarUsuariosAyudar',
								Cartera:$('#cbCartera').val(),
								Servicio:$('#hdCodServicio').val(),
								UsuarioServicio:$('#cbUsuarioServicio').val()
								},
						beforeSend : function ( ) {
								$('#LayerTableUsuariosAyudar').html(templates.IMGloadingContentLayer());
							},
						success : function ( obj ) {
								AyudaGestionUsuarioDAO.FillTableUsuariosAyudar(obj);
							},
						error : function ( ) {}
					});
			},
		FillTableUsuariosAyudar : function ( obj ) {
				var html='';
					html+='<table cellspacing="0" cellpadding="0" border="0" class="ui-corner-top" >';
						html+='<tr class="ui-state-default" >';
							html+='<td style="width:25px;padding:3px 0;border:1px solid #E0CFC2;text-align:center;" class="ui-corner-tl" >&nbsp;</td>';
							html+='<td style="width:250px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Usuario</td>';
							html+='<td style="width:200px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Clientes Asignados</td>';
							html+='<td style="width:150px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Clientes Gestionados</td>';
							html+='<td style="width:150px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Clientes Sin Gestionar</td>';
							html+='<td style="width:25px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" align="center" ><input type="checkbox" onClick="checked_all_usuarios_ayudar(this.checked)" /></td>';
							html+='<td style="width:20px;padding:3px 0;border-left:1px solid #E0CFC2;" align="center" class="ui-corner-tr" >&nbsp;</td>';
						html+='</tr>';
					html+='</table>';
					html+='<div id="DataLayerTableUsuariosAyudar" style="height:170px;overflow-x:auto;width:830px;">';
					html+='<table id="tb_teleoperadores_ayudar_data" cellspacing="0" cellpadding="0" border="0" >';
				for( i=0;i<obj.length;i++ ) {
					html+='<tr id="'+obj[i].idusuario_servicio+'" class="ui-widget-content" style="display:block;float:left;border:0px;" >';
						html+='<td align="center" style="width:25px;padding:3px 0;" class="ui-state-default">'+(i+1)+'</td>';
						html+='<td align="center" style="width:250px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+obj[i].operador+'</td>';
						html+='<td align="center" style="width:200px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+obj[i].clientes_asignados+'</td>';
						html+='<td align="center" style="width:150px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+obj[i].clientes_gestionados+'</td>';
						html+='<td align="center" style="width:150px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+obj[i].clientes_sin_gestionar+'</td>';
						html+='<td align="center" style="width:25px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;"><input value="'+obj[i].idusuario_servicio+'" type="checkbox" /></td>';
					html+='</tr>';
				}
					html+='</table>';
					html+='<div>';
				$('#LayerTableUsuariosAyudar').html(html);
			},
		ListarUsuariosAsignar : function ( ) {
				$.ajax({
						url : AyudaGestionUsuarioDAO.url,
						type : 'GET',
						dataType : 'json',
						data : {
								command:'ayuda_gestion_usuario',
								action:'ListarUsuariosAsignar',
								Cartera:$('#cbCartera').val(),
								UsuarioServicio:$('#cbUsuarioServicio').val(),
								Servicio:$('#hdCodServicio').val()
								},
						beforeSend : function ( ) {
								$('#LayerTableUsuariosAsignar').html(templates.IMGloadingContentLayer());
							},
						success : function ( obj ) {
								AyudaGestionUsuarioDAO.FillTableUsuariosAsignar(obj)
							},
						error : function ( ) {}
					});
			},
		FillTableUsuariosAsignar : function ( obj ) {
				var html='';
					html+='<table cellspacing="0" cellpadding="0" border="0" class="ui-corner-top" >';
						html+='<tr class="ui-state-default" >';
							html+='<td style="width:25px;padding:3px 0;border:1px solid #E0CFC2;" class="ui-corner-tl" >&nbsp;</td>';
							html+='<td style="width:250px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Usuario</td>';
							html+='<td style="width:200px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Clientes Asignados</td>';
							html+='<td style="width:150px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Clientes Gestionados</td>';
							html+='<td style="width:150px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Clientes Sin Gestionar</td>';
							html+='<td style="width:25px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" align="center" ><input type="checkbox" onClick="checked_all_usuarios_asignar(this.checked)" /></td>';
							html+='<td style="width:20px;padding:3px 0;border:1px solid #E0CFC2;" align="center" class="ui-corner-tr" >&nbsp;</td>';
						html+='</tr>';
					html+='</table>';
					html+='<div id="DataLayerTableUsuariosAsignar" style="height:170px;overflow-x:auto;width:830px;">';
					html+='<table id="tb_teleoperadores_asignar_data"  cellspacing="0" cellpadding="0" border="0" >';
				for( i=0;i<obj.length;i++ ) {
					html+='<tr id="'+obj[i].idusuario_servicio+'" class="ui-widget-content" style="display:block;float:left;border:0px;" >';
						html+='<td align="center" style="width:25px;padding:3px 0;" class="ui-state-default">'+(i+1)+'</td>';
						html+='<td align="center" style="width:250px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+obj[i].operador+'</td>';
						html+='<td align="center" style="width:200px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+obj[i].clientes_asignados+'</td>';
						html+='<td align="center" style="width:150px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+obj[i].clientes_gestionados+'</td>';
						html+='<td align="center" style="width:150px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+obj[i].clientes_sin_gestionar+'</td>';
						html+='<td align="center" style="width:25px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;"><input value="'+obj[i].idusuario_servicio+'" type="checkbox" /></td>';
					html+='</tr>';
				}
					html+='</table>';
					html+='<div>';
				$('#LayerTableUsuariosAsignar').html(html);
			},
		SaveUsuarioAyudar : function ( ids ) {
				$.ajax({
						url : AyudaGestionUsuarioDAO.url,
						type : 'POST',
						dataType : 'json',
						data : {
								command:'ayuda_gestion_usuario',
								action:'SaveUsuarioAyudar',
								Cartera:$('#cbCartera').val(),
								UsuarioServicio:$('#cbUsuarioServicio').val(),
								IdsUsuarioServicio:ids,
								UsuarioCreacion:$('#hdCodUsuario').val()
								},
						beforeSend : function ( ) {
								_displayBeforeSend('Guardando usuarios...',250);
							},
						success : function ( obj ) {
								_noneBeforeSend();
								if( obj.rst ){
									$('#'+AyudaGestionUsuarioDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
									$('#'+AyudaGestionUsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });
									AyudaGestionUsuarioDAO.ListarUsuariosAsignar();
									AyudaGestionUsuarioDAO.ListarUsuariosAyudar();
								}else{
									$('#'+AyudaGestionUsuarioDAO.idLayerMessage).html(templates.MsgError(obj.msg,'350px'));
									$('#'+AyudaGestionUsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });
								}
							},
						error : function ( ) {
								_noneBeforeSend();
							}
					});
			},
		DeleteUsuarioAyudar : function ( ids ) {
				$.ajax({
						url : AyudaGestionUsuarioDAO.url,
						type : 'POST',
						dataType : 'json',
						data : {
								command:'ayuda_gestion_usuario',
								action:'DeleteUsuarioAyudar',
								Cartera:$('#cbCartera').val(),
								UsuarioServicio:$('#cbUsuarioServicio').val(),
								IdsUsuarioServicio:ids,
								UsuarioModificacion : $('#hdCodUsuario').val()
								},
						beforeSend : function ( ) {
								_displayBeforeSend('Eliminando usuarios...',250);
							},
						success : function ( obj ) {
								_noneBeforeSend();
								if( obj.rst ){
									$('#'+AyudaGestionUsuarioDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
									$('#'+AyudaGestionUsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });
									AyudaGestionUsuarioDAO.ListarUsuariosAsignar();
									AyudaGestionUsuarioDAO.ListarUsuariosAyudar();
								}else{
									$('#'+AyudaGestionUsuarioDAO.idLayerMessage).html(templates.MsgError(obj.msg,'350px'));
									$('#'+AyudaGestionUsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });
								}
							},
						error : function ( ) {
								_noneBeforeSend();
							}
					});
			},
		hide_message : function ( ) {
				$('#'+AyudaGestionUsuarioDAO.idLayerMessage).effect('blind',{direction:'vertical'},'slow',function(){ $(this).empty().css('display','block'); });
			
			},
		setTimeOut_hide_message : function ( ) {
				setTimeout("AyudaGestionUsuarioDAO.hide_message()",4000);
			}
	}