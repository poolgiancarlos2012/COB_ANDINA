var DistribucionDAO = {
		url: '../controller/ControllerCobrast.php',
		CampaniaAjax : function ( ) {
				$.ajax({
					   url : this.url,
					   type : 'GET',
					   dataType : 'json',
					   data : {command:'distribucion',action:'ListCampania',Servicio:$('#hdCodServicio').val()},
					   success : function ( obj ) {
					   			DistribucionDAO.campaniaAll(obj);
						   		/*DistribucionDAO.campaniaDistribucionAutomatica(obj);
								DistribucionDAO.campaniaClientesGestionadosSinGestionar(obj);
								DistribucionDAO.campaniaDistribucionManual(obj);
								DistribucionDAO.campaniaRetirarClientes(obj);
								DistribucionDAO.campaniaDistribucionCB(obj);
								DistribucionDAO.campaniaDistribucionPorOperador(obj);
								DistribucionDAO.campaniaDistribucionPorDepartamento(obj);
								DistribucionDAO.campaniaDistribucionPorTramo(obj);
								DistribucionDAO.campaniaDistribucionPorCampos(obj);
								DistribucionDAO.campaniaDistribucionEspecial(obj);
								DistribucionDAO.campaniaDistribucionMontosIguales(obj);
								DistribucionDAO.campaniaRegistroZona(obj);
								DistribucionDAO.campaniaDistribucionConstante(obj);
								DistribucionDAO.campaniaDistribucionSinGestion(obj);*/
						   },
					   error : this.error_ajax
					   });
			},
		ListarClusterServicio : function ( ) {
			$.ajax({
				   url : this.url,
				   type : 'GET',
				   dataType : 'json',
				   data : {command:'distribucion',action:'load_data_cluster_servicio'},
				   success : function ( obj ) {
							var html='<option value="0">Todos</option>';
							$.each(obj,function(key,data){
								html+='<option value="'+data.idcluster+'">'+data.nombre+'</option>';
							});
							$("#FiltroClusterManual").html(html);
							$("#FiltroClusterDepartamento").html(html);
							$("#FiltroClusterTramo").html(html);
							$("#FiltroClusterCampos").html(html);
							$("#FiltroClusterMontosIguales").html(html);
							$("#FiltroClusterDistribucionConstante").html(html);
							$("#FiltroClusterSinGestion").html(html);
					   },
				   error : this.error_ajax
				   });
		},
		campaniaDistribucionManual : function ( obj ) {
				var html='';
					html+='<option value="0">--Seleccione--</option>';
				$.each(obj,function(key,data){
					html+='<option value="'+data.idcampania+'">'+data.nombre+'</option>';
				});
				$('#cbCampaniaDistribucionManual').html(html);
				//$(document.body).find('select[id^="cbCampania"]').html(html);
			},
		campaniaAll : function ( obj )	 {
				var html='';
					html+='<option value="0">--Seleccione--</option>';
				$.each(obj,function(key,data){
					html+='<option value="'+data.idcampania+'">'+data.nombre+'</option>';
				});
				$('select[id^="cbCampania"]').html(html);
			},
		campaniaDistribucionAutomatica : function ( obj ) {
				var html='';
					html+='<option value="0">--Seleccione--</option>';
				$.each(obj,function(key,data){
					html+='<option value="'+data.idcampania+'">'+data.nombre+'</option>';
				});
				$('#cbCampaniaDistribucionAutomatica').html(html);
			},
		campaniaDistribucionPorOperador : function ( obj ) {
				var html='';
					html+='<option value="0">--Seleccione--</option>';
				$.each(obj,function(key,data){
					html+='<option value="'+data.idcampania+'">'+data.nombre+'</option>';
				});
				$('#cbCampaniaDistribucionPorOperador').html(html);
			},
		campaniaDistribucionPorDepartamento : function ( obj ) {
				var html='';
					html+='<option value="0">--Seleccione--</option>';
				$.each(obj,function(key,data){
					html+='<option value="'+data.idcampania+'">'+data.nombre+'</option>';
				});
				$('#cbCampaniaDistribucionPorDepartamento').html(html);
			},
		campaniaDistribucionPorTramo : function ( obj ) {
				var html='';
					html+='<option value="0">--Seleccione--</option>';
				$.each(obj,function(key,data){
					html+='<option value="'+data.idcampania+'">'+data.nombre+'</option>';
				});
				$('#cbCampaniaDistribucionPorTramo').html(html);
			},
		campaniaDistribucionPorCampos : function ( obj ) {
				var html='';
					html+='<option value="0">--Seleccione--</option>';
				$.each(obj,function(key,data){
					html+='<option value="'+data.idcampania+'">'+data.nombre+'</option>';
				});
				$('#cbCampaniaDistribucionCampos').html(html);
			},
		campaniaDistribucionEspecial : function ( obj ) {
				var html='';
					html+='<option value="0">--Seleccione--</option>';
				$.each(obj,function(key,data){
					html+='<option value="'+data.idcampania+'">'+data.nombre+'</option>';
				});
				$('#cbCampaniaDistribucionEspecial').html(html);
			},
		campaniaDistribucionMontosIguales : function ( obj ) {
				var html='';
					html+='<option value="0">--Seleccione--</option>';
				$.each(obj,function(key,data){
					html+='<option value="'+data.idcampania+'">'+data.nombre+'</option>';
				});
				$('#cbCampaniaDistribucionMontosIguales').html(html);
			},
		campaniaDistribucionConstante : function ( obj ) {
				var html='';
					html+='<option value="0">--Seleccione--</option>';
				$.each(obj,function(key,data){
					html+='<option value="'+data.idcampania+'">'+data.nombre+'</option>';
				});
				$('#cbCampaniaDistribucionConstante').html(html);
			},
		campaniaDistribucionSinGestion : function ( obj ) {
				var html='';
					html+='<option value="0">--Seleccione--</option>';
				$.each(obj,function(key,data){
					html+='<option value="'+data.idcampania+'">'+data.nombre+'</option>';
				});
				$('#cbCampaniaDistribucionSinGestion').html(html);
			},
		campaniaRegistroZona : function ( obj ) {
				var html='';
					html+='<option value="0">--Seleccione--</option>';
				$.each(obj,function(key,data){
					html+='<option value="'+data.idcampania+'">'+data.nombre+'</option>';
				});
				$('#cdCampaniaRegistroZona').html(html);
			},	
		campaniaClientesGestionadosSinGestionar : function ( obj ) {
				var html='';
					html+='<option value="0">--Seleccione--</option>';
				$.each(obj,function(key,data){
					html+='<option value="'+data.idcampania+'">'+data.nombre+'</option>';
				});
				$('#cbCampaniaClientesGestSinGest').html(html);
				
			},
		campaniaRetirarClientes : function ( obj ) {
				var html='';
					html+='<option value="0">--Seleccione--</option>';
				$.each(obj,function(key,data){
					html+='<option value="'+data.idcampania+'">'+data.nombre+'</option>';
				});
				$('#cbCampaniaRetirarCliente').html(html);
				
			},	
		campaniaDistribucionCB : function ( obj ) {
				var html='';
					html+='<option value="0">--Seleccione--</option>';
				$.each(obj,function(key,data){
					html+='<option value="'+data.idcampania+'">'+data.nombre+'</option>';
				});
				$('#cbCampaniaTraspasoCartera').html(html);
				$('#cbCampaniaRedistribSinPago').html(html);
				$('#cbCampaniaRedistribAmortizado').html(html);
				
				
			},	
		operadores_retirar_cliente : function ( idCartera ) {
				$.ajax({
					   url : this.url,
					   type : 'GET',
					   dataType : 'json',
					   data : {
						   		command:'distribucion',
								action:'ListarGestionOperador',
								Cartera:idCartera,
								Servicio:$('#hdCodServicio').val()
								},
					   beforeSend : function ( ) {
						   		$('#table_retirar_clientes').html(templates.IMGloadingContentTable());
						   },
					   success : function ( obj ) {
						   		var html='';
									/*html+='<tr>';
										html+='<td align="center"><div class="ui-widget-header ui-corner-all" style="padding:2px 4px;margin:2px;">Operador</div></td>';
										html+='<td align="center"><div class="ui-widget-header ui-corner-all" style="padding:2px 4px;margin:2px;">Clts. Asignados</div></td>';
										html+='<td align="center"><div class="ui-widget-header ui-corner-all" style="padding:2px 4px;margin:2px;">Clts. Gestionados</div></td>';
										html+='<td align="center"><div class="ui-widget-header ui-corner-all" style="padding:2px 4px;margin:2px;">Clts. Sin Gestionar</div></td>';
										html+='<td></td>';
										html+='<td></td>';
										html+='<td></td>';
										html+='<td></td>';
									html+='</tr>';*/
								$.each(obj,function(key,data){
									html+='<tr id="'+data.idusuario_servicio+'">';
										html+='<td align="center" style="width:200px;padding:2px;white-space:pre-line;">'+data.operador+'</td>';
										html+='<td align="center" style="width:105px;padding:2px;">'+data.clientes_asignados+'</td>';
										html+='<td align="center" style="width:115px;padding:2px;">'+data.clientes_gestionados+'</td>';
										html+='<td align="center" style="width:120px;padding:2px;">'+data.clientes_sin_gestionar+'</td>';
										html+='<td class="ui-pg-button ui-corner-all" ><div class="ui-pg-div ui-corner-all"><span class="ui-icon ui-icon-pencil"></span></div></td>';
										html+='<td class="ui-pg-button ui-corner-all" ><div class="ui-pg-div ui-corner-all"><span class="ui-icon ui-icon-disk"></span></div></td>';
										html+='<td class="ui-pg-button ui-corner-all" onclick="retirar_todo_clientes_sin_gestionar(this)"><div class="ui-pg-div ui-corner-all"><span class="ui-icon ui-icon-trash"></span></div></td>';
										html+='<td title = "Retirar todo los clientes" class="ui-pg-button ui-corner-all" onclick="ELIMINAR_TODO_CLIENTE_USUARIO('+data.idusuario_servicio+','+idCartera+')"><div class="ui-pg-div ui-corner-all"><span class="ui-icon ui-icon-closethick"></span></div></td>';
									html+='</tr>';
								});
								$('#table_retirar_clientes').html(html);
								$('#table_retirar_clientes tr:gt(0)').find('td:eq(4)').bind('click',function(){edit_retirar_clientes(this);})
								$('#table_retirar_clientes tr:gt(0)').find('td:eq(5)').bind('click',function(){ save_retirar_clientes(this); });
								//$('#table_retirar_clientes tr:gt(0)').find('td:gt(3)').hover(function(){$(this).addClass('ui-state-hover')},function(){$(this).removeClass('ui-state-hover')});
								//$('#table_retirar_clientes tr:gt(0):odd').css('background-color','#7F694F');
								//$('#table_retirar_clientes').selectable({filter:'tr:gt(0)'});
						   },
					   error : function ( ) {
							   DistribucionDAO.error_ajax();
						   }
					   });
			},
		operadores_traspaso_cliente : function ( idCartera ) {
				$.ajax({
					   url : this.url,
					   type : 'GET',
					   dataType : 'json',
					   data : {
						   		command:'distribucion',
								action:'ListarGestionOperador',
								Cartera:idCartera,
								Servicio:$('#hdCodServicio').val()
								},
					   beforeSend : function ( ) {
						   		$('#table_traspaso_clientes').html(templates.IMGloadingContentTable());
						   },
					   success : function ( obj ) {
						   		var html_DE='';
								var html_PARA='';
								$.each(obj,function(key,data){
									html_DE+='<tr id="'+data.idusuario_servicio+'">';
										html_DE+='<td align="left" style="padding:2px 2px;width:255px">'+data.operador+'</td>';
										html_DE+='<td align="center" style="padding:2px 2px;width:60px">'+data.clientes_asignados+'</td>';
										html_DE+='<td align="center" style="padding:2px 2px;width:60px">'+data.clientes_gestionados+'</td>';
										html_DE+='<td align="center" style="padding:2px 2px;width:80px">'+data.clientes_sin_gestionar+'</td>';
										html_DE+='<td class="ui-pg-button ui-corner-all" style="padding:2px 2px;width:10px"><input type="radio" value="'+data.idusuario_servicio+'" name="idusuario_servicio_DE" id="idusuario_servicio_DE"></td>';									
									html_DE+='</tr>';
									html_PARA+='<tr id="'+data.idusuario_servicio+'">';
										html_PARA+='<td align="left" style="padding:2px 2px;width:255px">'+data.operador+'</td>';
										html_PARA+='<td align="center" style="padding:2px 2px;width:60px">'+data.clientes_asignados+'</td>';
										html_PARA+='<td align="center" style="padding:2px 2px;width:60px">'+data.clientes_gestionados+'</td>';
										html_PARA+='<td align="center" style="padding:2px 2px;width:80px">'+data.clientes_sin_gestionar+'</td>';
										html_PARA+='<td class="ui-pg-button ui-corner-all" style="padding:2px 2px;width:10px"><input type="radio" value="'+data.idusuario_servicio+'" name="idusuario_servicio_PARA" id="idusuario_servicio_PARA"></td>';									
									html_PARA+='</tr>';
								});
								$('#table_traspaso_clientes_DE').html(html_DE);
								$('#table_traspaso_clientes_PARA').html(html_PARA);
								/*$('#table_traspaso_clientes tr:gt(0)').find('td:eq(4)').bind('click',function(){edit_retirar_clientes(this);})
								$('#table_traspaso_clientes tr:gt(0)').find('td:eq(5)').bind('click',function(){ save_retirar_clientes(this); });*/
						   },
					   error : function ( ) {
							   DistribucionDAO.error_ajax();
						   }
					   });
			},
		delete_all_clientes : function ( xidusuario_servicio, xidcartera, f_success ) {
				
				$.ajax({
						url : this.url ,
						type : 'POST',
						dataType : 'json',
						data : { command : 'distribucion', action : 'RetirarTodocliente', idusuario_servicio : xidusuario_servicio, idcartera : xidcartera },
						beforeSend : function ( ) {
							
							},
						success : function ( obj ) {
								f_success(obj);
							},
						error : function ( ) {
								DistribucionDAO.error_ajax();
							}
					});
				
			},
		delete_all_clientes_sin_gestionar : function ( idusuario_servicio , element ) {
				var clientes_asignados=parseInt( $(element).parent().children('td:eq(1)').text() );
				var clientes_sin_gestionar=parseInt( $(element).parent().children('td:eq(3)').text() );
				$.ajax({
					   url : this.url ,
					   type : 'POST',
					   dataType : 'json',
					   data : {command:'distribucion',action:'RetirarTodoclienteSinGestionar',Cartera:$('#cbCarteraRetirarCliente').val(),UsuarioServicio:idusuario_servicio},
					   beforeSend : function ( ) {
						   		$(element).find('div').html(templates.IMGloadingContent);
						   },
					   success : function ( obj ) { 
					   			$(element).find('div').html('<span class="ui-icon ui-icon-trash"></span>');
					   			if(obj.rst){
									$(element).parent().children('td:eq(1)').text(clientes_asignados-clientes_sin_gestionar);
									$(element).parent().children('td:eq(3)').text(0);
									$('#layerMessage').html( templates.MsgInfo(obj.msg,'300px') );
								}else{
									$('#layerMessage').html( templates.MsgError(obj.msg,'300px') );
								}
								$('#layerMessage').effect('pulsate',{},'slow',function( ) { $(this).empty(); });
					   		},
					   error : function ( ) {
						   		$(element).find('div').html('<span class="ui-icon ui-icon-trash"></span>');
							   DistribucionDAO.error_ajax();
						   }
					   });
			},
		delete_retirar_clientes_sin_gestionar_ingresados : function ( element, usuario_servicio, clientes ) {
				$.ajax({
					   url : this.url,
					   type :'POST',
					   dataType : 'json',
					   data : {
						   		command:'distribucion',
					   			action:'RetirarIngresadosClientesSinGestionar',
								Cartera:$('#cbCarteraRetirarCliente').val(),
								UsuarioServicio:usuario_servicio,
								Cantidad:clientes
								},
					   beforeSend : function ( ) {
								$(element).find('div').html(templates.IMGloadingContent());
								$(element).parent().find(':text').attr('disabled',true);
								$(element).unbind('click');
						   },
					   success : function ( obj ) {
						   		$(element).find('div').html('<span class="ui-icon ui-icon-disk"></span>');
								$(element).parent().find(':text').attr('disabled',false);
								$(element).bind('click',function(){save_retirar_clientes(this);} );
								
								if( obj.rst ){
									var xclientes=parseInt( $(element).parent().find(':hidden').val() );
									var xclientes_asignados=parseInt( $(element).parent().children('td:eq(1)').text() );
									$(element).parent().children('td:eq(4)').find('div').html('<span class="ui-icon ui-icon-pencil"></span>')
									$(element).parent().children('td:eq(4)').unbind('click');
									$(element).parent().children('td:eq(4)').bind('click',function(){ edit_retirar_clientes(this); });
									$(element).parent().children('td:eq(3)').html(xclientes-clientes);
									$(element).parent().children('td:eq(1)').html(xclientes_asignados-clientes);
									$('#layerMessage').html(templates.MsgInfo(obj.msg,'250px'));
								}else{
									$('#layerMessage').html(templates.MsgError(obj.msg,'250px'));
								}
								
								$('#layerMessage').effect('pulsate',{},'slow',function( ) { $(this).empty(); });
						   },
					   error : function ( ) {
						   		$(element).parent().find(':text').attr('disabled',false);
						   		$(element).find('div').html('<span class="ui-icon ui-icon-disk"></span>');
								$(element).bind('click',function(){ save_retirar_clientes(this);} );
						   		DistribucionDAO.error_ajax();
					   		}
					   });
			},
		generar_distribucion_automatica : function ( ) {
				$.ajax({
					   url : this.url ,
					   type : 'POST',
					   dataType : 'json',
					   data : {
						   	command:'distribucion',
							action:'generar_distribucion_automatica',
							Cartera:$('#cbCarteraDistribucionAutomatica').val(),
							Servicio:$('#hdCodServicio').val()
							},
					   beforeSend : function ( ) {
						   		_displayBeforeSend('Generando Distribucion...',300);
						   },
					   success : function ( obj ) {
						   		_noneBeforeSend();
						   		if(obj.rst){
									$('#layerMessage').html( templates.MsgInfo(obj.msg,'300px') );
									$('#txtClientesSinAsignar').val('');
									$('#txtClientesAsignados').val('');
									$('#txtCantidadOperadores').val('');
									$('#txtClientesXOperador').val('');
									$('#cbCampaniaDistribucionAutomatica').val(0);
								}else{
									$('#layerMessage').html( templates.MsgError(obj.msg,'300px') );
								}
								$('#layerMessage').effect('pulsate',{},'slow',function( ) { $(this).empty(); });
						   },
					   error : function ( ) {
							   	_noneBeforeSend();
							   DistribucionDAO.error_ajax();
						   }
					   });
			},
		generar_distribucion_manual : function ( dataJson ) {
				$.ajax({
					   url : this.url,
					   type : 'POST',
					   dataType : 'json',
					   data : {command:'distribucion',action:'generar_distribucion_manual',Cartera:$('#cbCarteraDistribucionManual').val(),Servicio:$('#hdCodServicio').val(),DataManual:dataJson},
					   beforeSend : function ( ) {
						   		_displayBeforeSend('Generando Distribucion...',300);
						   },
					   success : function ( obj ) {
						   		_noneBeforeSend();
								if(obj.rst){
									$('#layerMessage').html(templates.MsgInfo('Distribucion manual generada correctamente','400px'));
									$('#table_asignacion').html('<tr id="placeHolder" ><td>Arrastre operadores aqui..</td></tr>');
									DistribucionDAO.ListarGestionOperador( $('#cbCarteraDistribucionManual').val() );
									DistribucionDAO.clientes_sin_asignar( $('#cbCarteraDistribucionManual').val() );
								}else{
									$('#layerMessage').html(templates.MsgError('Error al generar distribucion manual','400px'));
								}
								$('#layerMessage').effect('pulsate',{},'slow',function( ) { $(this).empty(); });
						   },
					   error : function ( ) {
						   		_noneBeforeSend();
						   		DistribucionDAO.error_ajax();
					   		}
					   });
			},
		generar_distribucion_sinpago : function ( dataJson ) {
				$.ajax({
					   url : this.url,
					   type : 'POST',
					   dataType : 'json',
					   data : {command:'distribucion',action:'generar_distribucion_sinpago',Cartera:$('#cbCarteraRedistribSinPago').val(),Servicio:$('#hdCodServicio').val(),DataManual:dataJson},
					   beforeSend : function ( ) {
						   		_displayBeforeSend('Generando Distribucion...',300);
						   },
					   success : function ( obj ) {
						   		_noneBeforeSend();
								if(obj.rst){
									$('#layerMessage').html(templates.MsgInfo('Distribucion Sin Pagos generada correctamente','400px'));
									$('#table_asignacion_RedistribSinPago').html('<tr id="placeHolder" ><td>Arrastre operadores aqui..</td></tr>');
									DistribucionDAO.ListarDataGestionOperador( $('#cbCarteraRedistribSinPago').val(),'table_operador_RedistribSinPago' );
									DistribucionDAO.clientes_sin_asignar_sinpago( $('#cbCarteraRedistribSinPago').val() );
								}else{
									$('#layerMessage').html(templates.MsgError('Error al generar distribucion Sin Pagos','400px'));
								}
								$('#layerMessage').effect('pulsate',{},'slow',function( ) { $(this).empty(); });
						   },
					   error : function ( ) {
						   		_noneBeforeSend();
						   		DistribucionDAO.error_ajax();
					   		}
					   });
			},	
		generar_distribucion_amortizado : function ( dataJson ) {
				$.ajax({
					   url : this.url,
					   type : 'POST',
					   dataType : 'json',
					   data : {command:'distribucion',action:'generar_distribucion_amortizado',Cartera:$('#cbCarteraRedistribAmortizado').val(),Servicio:$('#hdCodServicio').val(),DataManual:dataJson},
					   beforeSend : function ( ) {
						   		_displayBeforeSend('Generando Distribucion...',300);
						   },
					   success : function ( obj ) {
						   		_noneBeforeSend();
								if(obj.rst){
									$('#layerMessage').html(templates.MsgInfo('Distribucion Amortizados generada correctamente','400px'));
									$('#table_asignacion_RedistribAmortizados').html('<tr id="placeHolder" ><td>Arrastre operadores aqui..</td></tr>');
									DistribucionDAO.ListarDataGestionOperadorAmortizado( $('#cbCarteraRedistribAmortizado').val(),'table_operador_RedistribAmortizado' );
									DistribucionDAO.clientes_sin_asignar_amortizado( $('#cbCarteraRedistribAmortizado').val() );
								}else{
									$('#layerMessage').html(templates.MsgError('Error al generar distribucion Amortizados','400px'));
								}
								$('#layerMessage').effect('pulsate',{},'slow',function( ) { $(this).empty(); });
						   },
					   error : function ( ) {
						   		_noneBeforeSend();
						   		DistribucionDAO.error_ajax();
					   		}
					   });
			},	
		generar_traspaso_cartera : function ( idususerDE,idususerPARA,idcart,xfiltros ) {
				$.ajax({
					   url : this.url,
					   type : 'POST',
					   dataType : 'json',
					   data : {
					   			command:'distribucion',
								action:'generar_traspaso_cartera',
								idusuario_servicio_DE:idususerDE,
								idusuario_servicio_PARA:idususerPARA,
								idcartera:idcart,
								filtros : xfiltros
								},
					   beforeSend : function ( ) {
						   		_displayBeforeSend('Traspasando Carteras entre Operadores...',600);
						   },
					   success : function ( obj ) {
						   		_noneBeforeSend();
								if(obj.rst){
									$('#layerMessage').html(templates.MsgInfo('Traspaso generado Correctamente','400px'));
									DistribucionDAO.operadores_traspaso_cliente( $('#cbCarteraTraspasoCartera').val() );
								}else{
									$('#layerMessage').html(templates.MsgError('Error al Traspasar carteras','400px'));
								}
								$('#layerMessage').effect('pulsate',{},'slow',function( ) { $(this).empty(); });
						   },
					   error : function ( ) {
						   		_noneBeforeSend();
						   		DistribucionDAO.error_ajax();
					   		}
					   });
			},
		DataDistribucionAutomatica : function ( idCartera ) {
				$.ajax({
					   url : this.url,
					   type : 'GET',
					   dataType : 'json',
					   data : {command:'distribucion',action:'DataDistribucionAutomatica',Cartera:idCartera,Servicio:$('#hdCodServicio').val()},
					   success : function ( obj ) {
								$('#txtClientesSinAsignar').val(obj[0].clientes_sin_asignar);
								$('#txtClientesAsignados').val(obj[0].clientes_asignados);
								$('#txtCantidadOperadores').val(obj[0].cantidad_operadores);
								$('#txtClientesXOperador').val(Math.round(obj[0].clientes_sin_asignar/obj[0].cantidad_operadores));
						   },
					   error : this.error_ajax
					   });
			},
		ListarGestionOperador : function ( idCartera ) {
				$.ajax({
					   url : this.url,
					   type : 'GET',
					   dataType : 'json',
					   data : {command:'distribucion',action:'ListarGestionOperador',Cartera:idCartera,Servicio:$('#hdCodServicio').val()},
					   beforeSend : function ( ) {
						   		$('#table_operador_distribucion_manual').html(templates.IMGloadingContentTable());
						   },
					   success : function ( obj ) {
						   		var html='';
								$.each(obj,function(key,data){
									html+='<tr id="'+data.idusuario_servicio+'">';
										html+='<td align="center" style="width:240px;" name="operador'+data.idusuario_servicio+'" ><input type="hidden" value="'+data.idusuario_servicio+'" ><span>'+data.operador+'<span></td>';
										html+='<td align="center" style="width:40px;">'+data.clientes_asignados+'</td>';
										html+='<td align="center" style="width:60px;">'+data.clientes_sin_gestionar+'</td>';
										html+='<td align="center" style="width:40px;">'+data.clientes_gestionados+'</td>';
										html+='<td class="ui-pg-button ui-corner-all" ><div class="ui-pg-div ui-corner-all "><span class="ui-icon ui-icon-plus"></span></div></td>';
									html+='</tr>';
								});
								$('#table_operador_distribucion_manual').html(html);
								$('#table_operador_distribucion_manual tr').find('td:eq(4)').bind('click',function(){ agregar_asignacion(this);})
								$('#table_operador_distribucion_manual td[name^="operador"]').draggable({ opacity: 0.7, helper: 'clone' });
						   },
					   error : this.error_ajax
					   });
			},
		ListarGestionOperadorPorCluster : function ( idCartera,idCluster ) {
				$.ajax({
					   url : this.url,
					   type : 'GET',
					   dataType : 'json',
					   data : {command:'distribucion',action:'ListarGestionOperadorPorCluster',Cartera:idCartera,idcluster:idCluster,Servicio:$('#hdCodServicio').val()},
					   beforeSend : function ( ) {
						   		$('#table_operador_distribucion_manual').html(templates.IMGloadingContentTable());
						   },
					   success : function ( obj ) {
						   		var html='';
								$.each(obj,function(key,data){
									html+='<tr id="'+data.idusuario_servicio+'">';
										html+='<td align="center" style="width:240px;" name="operador'+data.idusuario_servicio+'" ><input type="hidden" value="'+data.idusuario_servicio+'" ><span>'+data.operador+'<span></td>';
										html+='<td align="center" style="width:40px;">'+data.clientes_asignados+'</td>';
										html+='<td align="center" style="width:60px;">'+data.clientes_sin_gestionar+'</td>';
										html+='<td align="center" style="width:40px;">'+data.clientes_gestionados+'</td>';
										html+='<td class="ui-pg-button ui-corner-all" ><div class="ui-pg-div ui-corner-all "><span class="ui-icon ui-icon-plus"></span></div></td>';
									html+='</tr>';
								});
								$('#table_operador_distribucion_manual').html(html);
								$('#table_operador_distribucion_manual tr').find('td:eq(4)').bind('click',function(){ agregar_asignacion(this);})
								$('#table_operador_distribucion_manual td[name^="operador"]').draggable({ opacity: 0.7, helper: 'clone' });
						   },
					   error : this.error_ajax
					   });
			},	
		ListarOperadoresCantidadGestionPorCluster : function ( idCartera,idCluster, f_fill ) {
				
				$.ajax({
						url : this.url,
						async : false,
					    type : 'GET',
					    dataType : 'json',
					    data : {command:'distribucion',action:'ListarGestionOperadorPorCluster',Cartera:idCartera,idcluster:idCluster,Servicio:$('#hdCodServicio').val()},
						success : function ( obj ) {
								f_fill(obj);
							},
						error : function ( ) {
								
							}
					});
				
			},
			
		ListarDataGestionOperador : function ( idCartera,idTable ) {
				$.ajax({
					   url : this.url,
					   type : 'GET',
					   async : false,
					   dataType : 'json',
					   data : {command:'distribucion',action:'ListarGestionOperador',Cartera:idCartera,Servicio:$('#hdCodServicio').val()},
					   beforeSend : function ( ) {
						   		$('#'+idTable).html(templates.IMGloadingContentTable());
						   },
					   success : function ( obj ) {
						   		var html='';
									/*html+='<tr>';
										html+='<td align="center"><div class="ui-widget-header ui-corner-all" style="padding:2px 1px;margin:1px;">Operador</div></td>';
										html+='<td align="center"><div class="ui-widget-header ui-corner-all" style="padding:2px 1px;margin:1px;">Asignados</div></td>';
										html+='<td align="center"><div class="ui-widget-header ui-corner-all" style="padding:4px 1px;margin:1px;font-size:8px;">Sin Gest.</div></td>';
										html+='<td align="center"><div class="ui-widget-header ui-corner-all" style="padding:2px 1px;margin:1px;">Gestionados</div></td>';
										html+='<td></td>';
									html+='</tr>';*/
						   		$.each(obj,function(key,data){
									html+='<tr id="'+data.idusuario_servicio+'">';
										html+='<td class="pointer" align="left" style="width:240px;" name="operador'+data.idusuario_servicio+'" ><input type="hidden" value="'+data.idusuario_servicio+'" ><span>'+data.operador+'<span></td>';
										html+='<td align="center" style="width:40px;">'+data.clientes_asignados+'</td>';
										html+='<td align="center" style="width:60px;">'+data.clientes_sin_gestionar+'</td>';
										html+='<td align="center" style="width:40px;">'+data.clientes_gestionados+'</td>';
										html+='<td class="ui-pg-button ui-corner-all" ><div class="ui-pg-div ui-corner-all "><span class="ui-icon ui-icon-plus"></span></div></td>';
									html+='</tr>';
								});
								$('#'+idTable).html(html);
								$('#'+idTable+' tr').find('td:eq(4)').bind('click',function(){ agregar_asignacion_sinpago(this);})
								$('#'+idTable+' td[name^="operador"]').draggable({ opacity: 0.7, helper: 'clone' });
								//$('#table_operador_distribucion_manual .ui-pg-div').hover(function(){ $(this).addClass('ui-state-hover'); },function(){ $(this).removeClass('ui-state-hover'); })
						   },
					   error : this.error_ajax
					   });
			},	
		ListarDataGestionOperadorAmortizado : function ( idCartera,idTable ) {
				$.ajax({
					   url : this.url,
					   type : 'GET',
					   dataType : 'json',
					   data : {command:'distribucion',action:'ListarGestionOperador',Cartera:idCartera,Servicio:$('#hdCodServicio').val()},
					   beforeSend : function ( ) {
						   		$('#'+idTable).html(templates.IMGloadingContentTable());
						   },
					   success : function ( obj ) {
						   		var html='';
									/*html+='<tr>';
										html+='<td align="center"><div class="ui-widget-header ui-corner-all" style="padding:2px 1px;margin:1px;">Operador</div></td>';
										html+='<td align="center"><div class="ui-widget-header ui-corner-all" style="padding:2px 1px;margin:1px;">Asignados</div></td>';
										html+='<td align="center"><div class="ui-widget-header ui-corner-all" style="padding:4px 1px;margin:1px;font-size:8px;">Sin Gest.</div></td>';
										html+='<td align="center"><div class="ui-widget-header ui-corner-all" style="padding:2px 1px;margin:1px;">Gestionados</div></td>';
										html+='<td></td>';
									html+='</tr>';*/
						   		$.each(obj,function(key,data){
									html+='<tr id="'+data.idusuario_servicio+'">';
										html+='<td class="pointer" align="left" style="width:240px;" name="operador'+data.idusuario_servicio+'" ><input type="hidden" value="'+data.idusuario_servicio+'" ><span>'+data.operador+'<span></td>';
										html+='<td align="center" style="width:40px;">'+data.clientes_asignados+'</td>';
										html+='<td align="center" style="width:60px;">'+data.clientes_sin_gestionar+'</td>';
										html+='<td align="center" style="width:40px;">'+data.clientes_gestionados+'</td>';
										html+='<td class="ui-pg-button ui-corner-all" ><div class="ui-pg-div ui-corner-all "><span class="ui-icon ui-icon-plus"></span></div></td>';
									html+='</tr>';
								});
								$('#'+idTable).html(html);
								$('#'+idTable+' tr').find('td:eq(4)').bind('click',function(){ agregar_asignacion_amortizado(this);})
								$('#'+idTable+' td[name^="operador"]').draggable({ opacity: 0.7, helper: 'clone' });
								//$('#table_operador_distribucion_manual .ui-pg-div').hover(function(){ $(this).addClass('ui-state-hover'); },function(){ $(this).removeClass('ui-state-hover'); })
						   },
					   error : this.error_ajax
					   });
			},	
		ListarOperadoresCantidadGestion : function ( xidcartera, f_fill ) {
				
				$.ajax({
						url : this.url,
					    type : 'GET',
					    dataType : 'json',
					    data : {command:'distribucion',action:'ListarGestionOperador',Cartera : xidcartera,Servicio:$('#hdCodServicio').val()},
						success : function ( obj ) {
								f_fill(obj);
							},
						error : function ( ) {
								
							}
					});
				
			},
		TeleoperadoresDistribucionPorDepartamento : function ( obj ) {
				var html='';
				for( i=0;i<obj.length;i++ ) {
					html+='<tr style="display:block;" >';
						html+='<td style="width:30px;padding:2px 0;" align="center" class="ui-state-default" ></td>';
						html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].idusuario_servicio+'</td>';
						html+='<td style="width:300px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].operador+'</td>';
						html+='<td style="width:60px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].clientes_asignados+'</td>';
						html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].clientes_gestionados+'</td>';
						html+='<td style="width:30px;padding:2px 0;" align="center" class="ui-widget-content" ><input type="checkbox" value="'+obj[i].idusuario_servicio+'" /></td>';
					html+='</tr>';
				}
				$('#tableOperadoresDistribucionPorDepartamento').html(html);
				$('#tableOperadoresDistribucionPorDepartamento').find('tr').hover(function(){ $(this).find('td:gt(0)').addClass('ui-state-hover'); },function(){ $(this).find('td:gt(0)').removeClass('ui-state-hover'); }); 
				$('#tableOperadoresDistribucionPorDepartamento tr').click(function() {
								  $(this).find('td:gt(0)').addClass("ui-state-highlight").parent().siblings().find('td:gt(0)').removeClass("ui-state-highlight");
					});
			},
		TeleoperadoresDistribucionPorTramo : function ( obj ) {
				var html='';
				for( i=0;i<obj.length;i++ ) {
					html+='<tr style="display:block;" >';
						html+='<td style="width:30px;padding:2px 0;" align="center" class="ui-state-default" ></td>';
						html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].idusuario_servicio+'</td>';
						html+='<td style="width:300px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].operador+'</td>';
						html+='<td style="width:60px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].clientes_asignados+'</td>';
						html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].clientes_gestionados+'</td>';
						html+='<td style="width:30px;padding:2px 0;" align="center" class="ui-widget-content" ><input type="checkbox" value="'+obj[i].idusuario_servicio+'" /></td>';
					html+='</tr>';
				}
				$('#tableOperadoresDistribucionPorTramos').html(html);
				$('#tableOperadoresDistribucionPorTramos').find('tr').hover(function(){ $(this).find('td:gt(0)').addClass('ui-state-hover'); },function(){ $(this).find('td:gt(0)').removeClass('ui-state-hover'); }); 
				$('#tableOperadoresDistribucionPorTramos tr').click(function() {
								  $(this).find('td:gt(0)').addClass("ui-state-highlight").parent().siblings().find('td:gt(0)').removeClass("ui-state-highlight");
					});

			},
		TeleoperadoresDistribucionPorCampos : function ( obj ) {
				var html='';
				for( i=0;i<obj.length;i++ ) {
					html+='<tr style="display:block;" >';
						html+='<td style="width:30px;padding:2px 0;" align="center" class="ui-state-default" ></td>';
						html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].idusuario_servicio+'</td>';
						html+='<td style="width:300px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].operador+'</td>';
						html+='<td style="width:60px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].clientes_asignados+'</td>';
						html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].clientes_gestionados+'</td>';
						html+='<td style="width:30px;padding:2px 0;" align="center" class="ui-widget-content" ><input type="checkbox" value="'+obj[i].idusuario_servicio+'" /></td>';
					html+='</tr>';
				}
				$('#tableOperadoresDistribucionPorCampos').html(html);
				$('#tableOperadoresDistribucionPorCampos').find('tr').hover(function(){ $(this).find('td:gt(0)').addClass('ui-state-hover'); },function(){ $(this).find('td:gt(0)').removeClass('ui-state-hover'); }); 
				$('#tableOperadoresDistribucionPorCampos tr').click(function() {
								  $(this).find('td:gt(0)').addClass("ui-state-highlight").parent().siblings().find('td:gt(0)').removeClass("ui-state-highlight");
					});

			},
		TeleoperadoresDistribucionMontosIguales : function ( obj ) {
				var html='';
				for( i=0;i<obj.length;i++ ) {
					html+='<tr style="display:block;" >';
						html+='<td style="width:30px;padding:2px 0;" align="center" class="ui-state-default" ></td>';
						html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].idusuario_servicio+'</td>';
						html+='<td style="width:300px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].operador+'</td>';
						html+='<td style="width:60px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].clientes_asignados+'</td>';
						html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].clientes_gestionados+'</td>';
						html+='<td style="width:30px;padding:2px 0;" align="center" class="ui-widget-content" ><input type="checkbox" value="'+obj[i].idusuario_servicio+'" /></td>';
					html+='</tr>';
				}
				$('#tableOperadoresDistribucionMontosIguales').html(html);
				$('#tableOperadoresDistribucionMontosIguales').find('tr').hover(function(){ $(this).find('td:gt(0)').addClass('ui-state-hover'); },function(){ $(this).find('td:gt(0)').removeClass('ui-state-hover'); }); 
				$('#tableOperadoresDistribucionMontosIguales tr').click(function() {
								  $(this).find('td:gt(0)').addClass("ui-state-highlight").parent().siblings().find('td:gt(0)').removeClass("ui-state-highlight");
					});

			},
		TeleoperadoresDistribucionConstante : function ( obj ) {
				var html='';
				for( i=0;i<obj.length;i++ ) {
					html+='<tr style="display:block;" >';
						html+='<td style="width:30px;padding:2px 0;" align="center" class="ui-state-default" ></td>';
						html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].idusuario_servicio+'</td>';
						html+='<td style="width:300px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].operador+'</td>';
						html+='<td style="width:60px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].clientes_asignados+'</td>';
						html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].clientes_gestionados+'</td>';
						html+='<td style="width:30px;padding:2px 0;" align="center" class="ui-widget-content" ><input type="checkbox" value="'+obj[i].idusuario_servicio+'" /></td>';
					html+='</tr>';
				}
				$('#tableOperadoresDistribucionConstante').html(html);
				$('#tableOperadoresDistribucionConstante').find('tr').hover(function(){ $(this).find('td:gt(0)').addClass('ui-state-hover'); },function(){ $(this).find('td:gt(0)').removeClass('ui-state-hover'); }); 
				$('#tableOperadoresDistribucionConstante tr').click(function() {
								  $(this).find('td:gt(0)').addClass("ui-state-highlight").parent().siblings().find('td:gt(0)').removeClass("ui-state-highlight");
					});

			},
		TeleoperadoresDistribucionSinGestion : function ( obj ) {
				var html='';
				for( i=0;i<obj.length;i++ ) {
					html+='<tr style="display:block;" >';
						html+='<td style="width:30px;padding:2px 0;" align="center" class="ui-state-default" ></td>';
						html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].idusuario_servicio+'</td>';
						html+='<td style="width:300px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].operador+'</td>';
						html+='<td style="width:60px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].clientes_asignados+'</td>';
						html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].clientes_gestionados+'</td>';
						html+='<td style="width:30px;padding:2px 0;" align="center" class="ui-widget-content" ><input type="checkbox" value="'+obj[i].idusuario_servicio+'" /></td>';
					html+='</tr>';
				}
				$('#tableOperadoresDistribucionSinGestion').html(html);
				$('#tableOperadoresDistribucionSinGestion').find('tr').hover(function(){ $(this).find('td:gt(0)').addClass('ui-state-hover'); },function(){ $(this).find('td:gt(0)').removeClass('ui-state-hover'); }); 
				$('#tableOperadoresDistribucionSinGestion tr').click(function() {
								  $(this).find('td:gt(0)').addClass("ui-state-highlight").parent().siblings().find('td:gt(0)').removeClass("ui-state-highlight");
					});

			},
		TeleoperadoresDistribucionMecanico : function ( obj ) {
				var html='';
				for( i=0;i<obj.length;i++ ) {
					html+='<tr style="display:block;" >';
						html+='<td style="width:30px;padding:2px 0;" align="center" class="ui-state-default" ></td>';
						html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].idusuario_servicio+'</td>';
						html+='<td style="width:300px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].operador+'</td>';
						html+='<td style="width:60px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].clientes_asignados+'</td>';
						html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].clientes_gestionados+'</td>';
						html+='<td style="width:30px;padding:2px 0;" align="center" class="ui-widget-content" ><input type="checkbox" value="'+obj[i].idusuario_servicio+'" /></td>';
					html+='</tr>';
				}
				$('#tableOperadoresDistribucionMecanica').html(html);
				$('#tableOperadoresDistribucionMecanica').find('tr').hover(function(){ $(this).find('td:gt(0)').addClass('ui-state-hover'); },function(){ $(this).find('td:gt(0)').removeClass('ui-state-hover'); }); 
				$('#tableOperadoresDistribucionMecanica tr').click(function() {
								  $(this).find('td:gt(0)').addClass("ui-state-highlight").parent().siblings().find('td:gt(0)').removeClass("ui-state-highlight");
					});

			},
		TeleoperadoresDistribucionPagos : function ( obj ) {
				var html='';
				for( i=0;i<obj.length;i++ ) {
					html+='<tr style="display:block;" >';
						html+='<td style="width:30px;padding:2px 0;" align="center" class="ui-state-default" ></td>';
						html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].idusuario_servicio+'</td>';
						html+='<td style="width:300px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].operador+'</td>';
						html+='<td style="width:60px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].clientes_asignados+'</td>';
						html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].clientes_gestionados+'</td>';
						html+='<td style="width:30px;padding:2px 0;" align="center" class="ui-widget-content" ><input type="checkbox" value="'+obj[i].idusuario_servicio+'" /></td>';
					html+='</tr>';
				}
				$('#tableOperadoresDistribucionPagos').html(html);
				$('#tableOperadoresDistribucionPagos').find('tr').hover(function(){ $(this).find('td:gt(0)').addClass('ui-state-hover'); },function(){ $(this).find('td:gt(0)').removeClass('ui-state-hover'); }); 
				$('#tableOperadoresDistribucionPagos tr').click(function() {
								  $(this).find('td:gt(0)').addClass("ui-state-highlight").parent().siblings().find('td:gt(0)').removeClass("ui-state-highlight");
					});

			},
		clientes_sin_asignar : function ( idCartera ) {
				$.ajax({
					   url : this.url,
					   type : 'GET',
					   dataType : 'json',
					   data : {command:'distribucion',action:'clientes_sin_gestionar',Servicio:$('#hdCodServicio').val(),Cartera:idCartera},
					   success : function ( obj ) {
								$('#txtClienteSinAsignarManual').val(obj[0].clientes_sin_asignar);
								$('#hdClienteSinAsignar').val(obj[0].clientes_sin_asignar);
						   },
					   error : this.error_ajax
					   });
			},
		clientes_sin_asignar_sinpago : function ( idCartera ) {
				$.ajax({
					   url : this.url,
					   type : 'GET',
					   dataType : 'json',
					   data : {command:'distribucion',action:'clientes_sin_pago',Servicio:$('#hdCodServicio').val(),Cartera:idCartera},
					   success : function ( obj ) {
								$('#txtClienteSinPago').val(obj[0].clientes_sin_pago);
								$('#hdClienteSinPago').val(obj[0].clientes_sin_pago);
						   },
					   error : this.error_ajax
					   });
			},	
		clientes_sin_asignar_amortizado : function ( idCartera ) {
				$.ajax({
					   url : this.url,
					   type : 'GET',
					   dataType : 'json',
					   data : {command:'distribucion',action:'clientes_amortizado',Servicio:$('#hdCodServicio').val(),Cartera:idCartera},
					   success : function ( obj ) {
								$('#txtClienteAmortizado').val(obj[0].clientes_amortizado);
								$('#hdClienteAmortizado').val(obj[0].clientes_amortizado);
						   },
					   error : this.error_ajax
					   });
			},		
		numeroClientesCartera : function ( idCartera,idTxt ) {
				$.ajax({
					   url : this.url,
					   type : 'GET',
					   dataType : 'json',
					   data : {command:'distribucion',action:'numero_clientes_cartera',Cartera:idCartera},
					   success : function ( obj ) {
								$('#'+idTxt).html(obj[0].clientes);
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
									cancel_campania();
								}else{
									$('#CampaniaLayerMessage').html(templates.MsgError(obj.msg,'250px'));
									$('#CampaniaLayerMessage').effect('pulsate',{},1000,function(){ $(this).empty(); });
								}
						   },
					   error : function ( ) {
							   	_noneBeforeSend();
							   DistribucionDAO.error_ajax();
						   }
					   });
		},
		ListarCarteraRpteRank : function ( idCampania,filtroEstadoCart, f_fill ) {
			$.ajax({
					url : DistribucionDAO.url,
					type : 'GET',
					dataType : 'json',
					data : {
							command:'carga-cartera',action:'ListCarteraRpteRank',Campania:idCampania,Estado:filtroEstadoCart 
						},
					success : function ( obj ) {
							f_fill(obj);
						},
					error : function ( ) {
							DistribucionDAO.error_ajax();
						}
				});
		},
		ListCartera : function ( idCampania, f_fill ) {
			$.ajax({
					url : DistribucionDAO.url,
					type : 'GET',
					dataType : 'json',
					data : {command:'carga-cartera',action:'ListCartera',Campania:idCampania},
					success : function ( obj ) {
							f_fill(obj);
						},
					error : function ( ) { 
							CargaCarteraDAO.error_ajax();
						}
				});
		},
		FillCarteraById : function ( obj,cbCartera ) {
				var html='';
					html+='<option value="0">--Seleccione--</option>';
				$.each(obj,function(key,data){
					if(data.vencido==1){html+='<option value="'+data.idcartera+'" style="color:#F00;">'+data.nombre_cartera+'</option>';}
					else{html+='<option value="'+data.idcartera+'">'+data.nombre_cartera+'</option>';}
					
				});
				//$('#submenuCartera #'+cbCartera).html(html); //keny
				$('#cobrastHOME #'+cbCartera).html(html);
			},
		FillCarteraDistribucionManual : function ( obj ) {
			var html='';
			html+='<option value="0">--Seleccione--</option>';
			$.each(obj,function(key,data){
				html+='<option value="'+data.idcartera+'">'+data.nombre_cartera+'</option>';
			});
			$('#cbCarteraDistribucionManual').html(html);
		},
		FillCarteraDistribucionAutomatica : function ( obj ) {
			var html='';
			html+='<option value="0">--Seleccione--</option>';
			$.each(obj,function(key,data){
				html+='<option value="'+data.idcartera+'">'+data.nombre_cartera+'</option>';
			});
			$('#cbCarteraDistribucionAutomatica').html(html);
		},
		FillCarteraDistribucionPorOperador : function ( obj ) {
			var html='';
			html+='<option value="0">--Seleccione--</option>';
			$.each(obj,function(key,data){
				html+='<option value="'+data.idcartera+'">'+data.nombre_cartera+'</option>';
			});
			$('#cbCarteraDistribucionPorOperador').html(html);
		},
		FillCarteraDistribucionPorDepartamento : function ( obj ) {
			var html='';
			html+='<option value="0">--Seleccione--</option>';
			$.each(obj,function(key,data){
				html+='<option value="'+data.idcartera+'">'+data.nombre_cartera+'</option>';
			});
			$('#cbCarteraDistribucionPorDepartamento').html(html);
		},
		FillCarteraDistribucionPorTramo : function ( obj ) {
			var html='';
			html+='<option value="0">--Seleccione--</option>';
			$.each(obj,function(key,data){
				html+='<option value="'+data.idcartera+'">'+data.nombre_cartera+'</option>';
			});
			$('#cbCarteraDistribucionPorTramo').html(html);
		},
		FillCarteraDistribucionPorCampo : function ( obj ) {
			var html='';
			html+='<option value="0">--Seleccione--</option>';
			$.each(obj,function(key,data){
				html+='<option value="'+data.idcartera+'">'+data.nombre_cartera+'</option>';
			});
			$('#cbCarteraDistribucionCampos').html(html);
		},
		FillCarteraDistribucionEspecial : function ( obj ) {
			var html='';
			html+='<option value="0">--Seleccione--</option>';
			$.each(obj,function(key,data){
				html+='<option value="'+data.idcartera+'">'+data.nombre_cartera+'</option>';
			});
			$('#cbCarteraDistribucionEspecial').html(html);
		},
		FillCarteraDistribucionMontosIguales : function ( obj ) {
			var html='';
			html+='<option value="0">--Seleccione--</option>';
			$.each(obj,function(key,data){
				html+='<option value="'+data.idcartera+'">'+data.nombre_cartera+'</option>';
			});
			$('#cbCarteraDistribucionMontosIguales').html(html);
		},
		FillCarteraDistribucionConstante : function ( obj ) {
			var html='';
			html+='<option value="0">--Seleccione--</option>';
			$.each(obj,function(key,data){
				html+='<option value="'+data.idcartera+'">'+data.nombre_cartera+'</option>';
			});
			$('#cbCarteraDistribucionConstante').html(html);
		},
		FillCarteraDistribucionSinGestion : function ( obj ) {
			var html='';
			html+='<option value="0">--Seleccione--</option>';
			$.each(obj,function(key,data){
				html+='<option value="'+data.idcartera+'">'+data.nombre_cartera+'</option>';
			});
			$('#cbCarteraDistribucionSinGestion').html(html);
		},
		FillCarteraRegistroZona : function ( obj ) {
			var html='';
			html+='<option value="0">--Seleccione--</option>';
			$.each(obj,function(key,data){
				html+='<option value="'+data.idcartera+'">'+data.nombre_cartera+'</option>';
			});
			$('#cdCarteraRegistroZona').html(html);
		},
		FillCarteraClientes_GSG : function ( obj ) {
			var html='';
			html+='<option value="0">--Seleccione--</option>';
			$.each(obj,function(key,data){
				html+='<option value="'+data.idcartera+'">'+data.nombre_cartera+'</option>';
			});
			$('#cbCarteraClientesGestSinGest').html(html);
		},
		FillCarteraRetirarClientes : function ( obj ) {
			var html='';
			html+='<option value="0">--Seleccione--</option>';
			$.each(obj,function(key,data){
				html+='<option value="'+data.idcartera+'">'+data.nombre_cartera+'</option>';
			});
			$('#cbCarteraRetirarCliente').html(html);
		},
		save_distribucion_por_operador : function ( xids ) {
				$.ajax({
						url : DistribucionDAO.url,
						type : 'POST',
						dataType : 'json',
						data : {
								command:'distribucion',
								action:'distribucion_por_operador',
								UsuarioServicio:$('#cbOperadoresDistribucionPorOperador').val(),
								Ids : xids 
								},
						beforeSend : function ( ) {
								_displayBeforeSend('Realizando Distribucion...',320);
							},
						success : function ( obj ) {
								_noneBeforeSend();
								if(obj.rst){
									$('#layerMessage').html(templates.MsgInfo(obj.msg,'350px'));
									$('#layerMessage').effect('pulsate',{},1000,function(){ $(this).empty(); });
									$('#tableDataClientesDistribucionPorOperador').empty();
									$('#table_asignacion_por_operador').jqGrid().trigger('reloadGrid');
								}else{
									$('#layerMessage').html(templates.MsgError(obj.msg,'350px'));
									$('#layerMessage').effect('pulsate',{},1000,function(){ $(this).empty(); });
								}
							},
						error : function ( ) {
								_noneBeforeSend();
								DistribucionDAO.error_ajax();
							}
					});
			},
		ListOperadores : function ( f_fill ) {
				$.ajax({
					url : DistribucionDAO.url,
					type : 'GET',
					dataType : 'json',
					data : {command:'atencion_cliente',action:'ListarOperadores',Servicio:$('#hdCodServicio').val()},
					beforeSend : function ( ) {
							
						},
					success : function ( obj ) {
							if( f_fill ){
								f_fill(obj);	
							}else{
								DistribucionDAO.FillListarOperadoresDistribucionPorOperador(obj);
								DistribucionDAO.FillListarOperadoresDistribucionEspecial(obj);
							}
						},
					error : function ( ) {
							
						}
					});
			},
		FillListarOperadoresDistribucionPorOperador : function ( obj ) {
				var html='';
					html+='<option value="0">--Seleccione--</option>';
				$.each(obj,function(key,data){
					if( $('#hdCodUsuarioServicio').val()!=data.idusuario_servicio ) {
						html+='<option value="'+data.idusuario_servicio+'">'+data.nombre+'</option>';
					}
				});
				$("#cbOperadoresDistribucionPorOperador").html(html);
			},
		FillListarOperadoresDistribucionEspecial : function ( obj ) {
				var html='';
					html+='<option value="0">--Seleccione--</option>';
				$.each(obj,function(key,data){
					if( $('#hdCodUsuarioServicio').val()!=data.idusuario_servicio ) {
						html+='<option value="'+data.idusuario_servicio+'">'+data.nombre+'</option>';
					}
				});
				$("#cbOperadorDistribucionEspecial").html(html);
			},
		ListarDepartamentos : function ( xidcartera, f_fill ) {
				
				$.ajax({
						url : DistribucionDAO.url,
						async : false,
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
		ListarProvincias : function (xidcartera,f_fill ) {
				
				$.ajax({
						url : DistribucionDAO.url,
						async : false,
						type : 'GET',
						dataType : 'json',
						data : { command : 'distribucion',
								 action : 'ListarProvinciasPorCartera',
								 departamento : $('#cbFiltroDepartamento').val(),
								 idcartera : xidcartera },
						beforeSend : function ( ) {},
						success : function ( obj ) {
								f_fill(obj);
							},
						error : function ( ) {}
					});
				
			},					
		FillDepartamentosDistribucionPorDepartamento : function ( obj ) {
				var html = '';
				html+='<option value="0">--Seleccione--</option>';
				for( i=0;i<obj.length;i++ ) {
					html+='<option value="'+obj[i].departamento+'">'+obj[i].departamento+'</option>';
				}
				$('#cbDepartamento').html(html);
			},
		ListarTramos : function ( xidcartera, f_fill ) {
			
				$.ajax({
						url : DistribucionDAO.url,
						type : 'GET',
						dataType : 'json',
						data : { command : 'atencion_cliente',action : 'ListTramo', Cartera : xidcartera },
						beforeSend : function ( ) {},
						success : function ( obj ) {
								f_fill(obj);
							},
						error : function ( ) {}
					});
				
			},
		FillTramosDistribucionPorTramos : function ( obj ) {
				var html = '';
				html+='<option value="0">--Seleccione--</option>';
				for( i=0;i<obj.length;i++ ) {
					html+='<option value="'+obj[i].tramo+'">'+obj[i].tramo+'</option>';
				}
				$('#cbTramo').html(html);
			},
		ListarCantidadClientesPorDepartamento : function ( xidcartera, xdepartamento, f_fill ) {
				
				$.ajax({
						url : DistribucionDAO.url,
						type : 'GET',
						dataType : 'json',
						data : { command : 'distribucion',action : 'CantidadClientesPorDepartamento', Cartera : xidcartera, Departamento : xdepartamento },
						beforeSend : function ( ) {},
						success : function ( obj ) {
								f_fill(obj);
							},
						error : function ( ) {}
					});
				
			},
		ListarCantidadClientesPorTramo : function ( xidcartera, xtramo, f_fill ) { 
				$.ajax({
						url : DistribucionDAO.url,
						type : 'GET',
						dataType : 'json',
						data : { command : 'distribucion',action : 'CantidadClientesPorTramo', Cartera : xidcartera, Tramo : xtramo },
						beforeSend : function ( ) {},
						success : function ( obj ) {
								f_fill(obj);
							},
						error : function ( ) {}
					});
			},
		ListarCantidadClientesPorTramoEspecial : function ( xidcartera, xtramo, f_fill ) { 
				$.ajax({
						url : DistribucionDAO.url,
						type : 'GET',
						dataType : 'json',
						data : { command : 'distribucion',action : 'CantidadClientesPorTramoEspecial', Cartera : xidcartera, Tramo : xtramo },
						beforeSend : function ( ) {},
						success : function ( obj ) {
								f_fill(obj);
							},
						error : function ( ) {}
					});
			},			

		save_distribucion_por_tramo : function ( xidcartera, xtramo, xoperadores, xmodo ) {
			
				$.ajax({
						url : DistribucionDAO.url,
						type : 'POST',
						dataType : 'json',
						data : { 
								command : 'distribucion',
								action : 'save_distribucion_por_tramo', 
								Cartera : xidcartera, 
								Tramo : xtramo, 
								operadores : xoperadores ,
								Modo : xmodo
								},
						beforeSend : function ( ) {
								_displayBeforeSend('Realizando Distribucion...',320);
							},
						success : function ( obj ) {
								_noneBeforeSend();
								if(obj.rst){
									$('#layerMessage').html(templates.MsgInfo(obj.msg,'350px'));
									$('#layerMessage').effect('pulsate',{},1000,function(){ $(this).empty(); });
									//$('#tableOperadoresDistribucionPorDepartamento').find(':checkbox').attr('checked',false);
									$('#cbCarteraDistribucionPorTramo').trigger('change');
								}else{
									$('#layerMessage').html(templates.MsgError(obj.msg,'350px'));
									$('#layerMessage').effect('pulsate',{},1000,function(){ $(this).empty(); });
								}
							},
						error : function ( ) {
								_noneBeforeSend();
								DistribucionDAO.error_ajax();
							}
					});
			
			},
		save_distribucion_por_departamento : function ( xidcartera, xdepartamento, xoperadores ) {
			
				$.ajax({
						url : DistribucionDAO.url,
						type : 'POST',
						dataType : 'json',
						data : { command : 'distribucion',action : 'save_distribucion_por_departamento', Cartera : xidcartera, Departamento : xdepartamento, operadores : xoperadores },
						beforeSend : function ( ) {
								_displayBeforeSend('Realizando Distribucion...',320);
							},
						success : function ( obj ) {
								_noneBeforeSend();
								if(obj.rst){
									$('#layerMessage').html(templates.MsgInfo(obj.msg,'350px'));
									$('#layerMessage').effect('pulsate',{},1000,function(){ $(this).empty(); });
									//$('#tableOperadoresDistribucionPorDepartamento').find(':checkbox').attr('checked',false);
									$('#cbCarteraDistribucionPorDepartamento').trigger('change');
								}else{
									$('#layerMessage').html(templates.MsgError(obj.msg,'350px'));
									$('#layerMessage').effect('pulsate',{},1000,function(){ $(this).empty(); });
								}
							},
						error : function ( ) {
								_noneBeforeSend();
								DistribucionDAO.error_ajax();
							}
					});
			
			},
		ListarCampos : function ( xidcartera, xcampo, xreferencia, f_success ) {
				
				$.ajax({
						url : DistribucionDAO.url,
						type : 'GET',
						dataType : 'json',
						data : { command : 'distribucion', action : 'ListarCampos', idcartera : xidcartera, campo : xcampo, referencia: xreferencia },
						beforeSend : function ( ) {},
						success : function ( obj ) {
								f_success(obj);
							},
						error : function ( ) {}
						
					});
				
			},
		ListarDataCampo : function ( xcartera, xtabla, xcampo, f_success ) {
				
				$.ajax({
						url : DistribucionDAO.url,
						type : 'GET',
						dataType : 'json',
						data : { command : 'distribucion', action : 'ListarDataCampo', servicio : $('#hdCodServicio').val() , cartera : xcartera, tabla : xtabla, campo : xcampo },
						beforeSend : function ( ) {},
						success : function ( obj ) {
								f_success(obj);
							},
						error : function ( ) {}
					});
				
			},
		MostrarCantidadClientesSinGestionar : function ( xcartera, xtabla, xcampo, xdato, f_success ) {
				
				$.ajax({
						url : DistribucionDAO.url,
						type : 'GET',
						dataType : 'json',
						data : { command : 'distribucion', action : 'MostrarCantidadClienteSinGestionar', 
								servicio : $('#hdCodServicio').val(),
								cartera : xcartera, 
								tabla : xtabla, 
								campo : xcampo,
								dato : xdato 
							},
						beforeSend : function ( ) {},
						success : function ( obj ) {
								f_success(obj);
							},
						error : function ( ) {}
					});
					
			},
		MostrarCantidadClientesSinGestionarPorUsuario : function ( xcartera, xtabla, xcampo, xdato, xreferencia, xusuario_servicio,  f_success ) {
				
				$.ajax({
						url : DistribucionDAO.url,
						type : 'GET',
						dataType : 'json',
						data : { command : 'distribucion', action : 'MostrarCantidadClienteSinGestionarPorUsuario', 
								servicio : $('#hdCodServicio').val(),
								usuario_servicio : xusuario_servicio, 
								cartera : xcartera, 
								tabla : xtabla, 
								campo : xcampo,
								dato : xdato,
								referencia : xreferencia 
							},
						beforeSend : function ( ) {},
						success : function ( obj ) {
								f_success(obj);
							},
						error : function ( ) {}
					});
					
			},
		save_distribucion_por_campos : function ( xcartera, xtabla, xcampo, xdato, xoperadores, xclientes, f_success ) {
			
				$.ajax({
						url : DistribucionDAO.url,
						type : 'POST',
						dataType : 'json',
						data : { 
								command : 'distribucion',
								action : 'save_distribucion_por_campo', 
								cartera : xcartera, 
								tabla : xtabla, 
								campo : xcampo, 
								dato : xdato, 
								operadores : xoperadores, 
								clientes : xclientes 
								},
						beforeSend : function ( ) {
								_displayBeforeSend('Realizando Distribucion...',320);
							},
						success : function ( obj ) {
								_noneBeforeSend();
								f_success(obj);
							},
						error : function ( ) {
								_noneBeforeSend();
								DistribucionDAO.error_ajax();
							}
					});
			
			},
		save_cliente_distribucion_especial : function ( xcliente_cartera, xusuario_servicio, f_success ) {
				
				$.ajax({
						url : DistribucionDAO.url,
						type : 'POST',
						dataType : 'json',
						data : { command : 'distribucion', action : 'save_cliente_especial', idcliente_cartera : xcliente_cartera, usuario_servicio : xusuario_servicio  },
						beforeSend : function ( ) {},
						success : function ( obj ) {
								f_success(obj);
							},
						error : function ( ) {}
					});
				
			},
		delete_cliente_distribucion_especial : function ( xcliente_cartera, f_success ) {
				
				$.ajax({
						url : DistribucionDAO.url,
						type : 'POST',
						dataType : 'json',
						data : { command : 'distribucion', action : 'delete_cliente_especial', idcliente_cartera : xcliente_cartera },
						beforeSend : function ( ) {},
						success : function ( obj ) {
								f_success(obj);
							},
						error : function ( ) {}
					});
				
			},
		distribucion_montos_iguales : function ( xcartera, xzona, xoperadores, f_success ) {
				
				$.ajax({
						url : DistribucionDAO.url,
						type : 'POST',
						dataType : 'json',
						data : { command : 'distribucion', action : 'distribucion_montos_iguales', cartera : xcartera , zona : xzona, operadores : xoperadores },
						beforeSend : function ( ) {
								_displayBeforeSend('Realizando Distribucion...',320);
							},
						success : function ( obj ) {
								_noneBeforeSend();
								f_success(obj);
							},
						error : function ( ) {
								_noneBeforeSend();
								DistribucionDAO.error_ajax();
							}
					});
				
			},
		listar_departamentos_zonas : function ( xidservicio, f_success ) {
				
				$.ajax({
						url : DistribucionDAO.url,
						type : 'GET',
						dataType : 'json',
						data : { command : 'distribucion', action : 'listar_departamento_zonas', idservicio : xidservicio  },
						beforeSend : function ( ) {},
						success : function ( obj ) {
								f_success(obj);
							},
						error : function ( ) {
								DistribucionDAO.error_ajax();
							}
					});
				
			},
		grabar_zonas : function ( xidcartera, xdata, f_success ) {
			
				$.ajax({
						url : DistribucionDAO.url,
						type : 'POST',
						dataType : 'json',
						data : { command : 'distribucion', action : 'grabar_zonas', idcartera : xidcartera, data : xdata  },
						beforeSend : function ( ) { 
								_displayBeforeSend('Grabando Zonas...',320);
							},
						success : function ( obj ) {
								_noneBeforeSend();
								f_success(obj);
							},
						error : function ( ) {
								_noneBeforeSend();
								DistribucionDAO.error_ajax();
							}
					});
				
			},
		grabar_departamentos_zonas : function ( xidservicio ,xidcartera, xusuario_creacion, f_success ) {
				
				$.ajax({
						url : DistribucionDAO.url,
						type : 'POST',
						dataType : 'json',
						data : { 
								command : 'distribucion', 
								action : 'grabar_departamentos_zonas', 
								idservicio : xidservicio, 
								idcartera : xidcartera ,
								usuario_creacion : xusuario_creacion 
								},
						beforeSend : function ( ) { 
								_displayBeforeSend('Grabando Departamentos...',320);
							},
						success : function ( obj ) {
								_noneBeforeSend();
								f_success(obj);
							},
						error : function ( ) {
								_noneBeforeSend();
								DistribucionDAO.error_ajax();
							}
					});
				
			},
		ListarZonas : function ( xidcartera, f_success ) {
				
				$.ajax({
						url : DistribucionDAO.url,
						type : 'GET',
						dataType : 'json',
						data : { 
								command : 'distribucion', 
								action : 'listar_zonas', 
								idcartera : xidcartera 
								},
						beforeSend : function ( ) { 
							},
						success : function ( obj ) {
								f_success(obj);
							},
						error : function ( ) {
								DistribucionDAO.error_ajax();
							}
					});
				
			},
		CantidadClientesSinAsignarZonas : function ( xidcartera, xzona, f_success ) {
				
				$.ajax({
						url : DistribucionDAO.url,
						type : 'GET',
						dataType : 'json',
						data : { 
								command : 'distribucion', 
								action : 'CantidadClientesSinAsignarZonas', 
								idcartera : xidcartera ,
								zona : xzona
								},
						beforeSend : function ( ) { 
							},
						success : function ( obj ) {
								f_success(obj);
							},
						error : function ( ) {
								DistribucionDAO.error_ajax();
							}
					});
				
			},
		CantidadClientesSinAsignarCartera : function ( xidcartera, f_success ) {
				
				$.ajax({
						url : DistribucionDAO.url,
						type : 'GET',
						dataType : 'json',
						data : { 
								command : 'distribucion', 
								action : 'CantidadClientesSinAsignarCartera', 
								idcartera : xidcartera
								},
						beforeSend : function ( ) { 
							},
						success : function ( obj ) {
								f_success(obj);
							},
						error : function ( ) {
								DistribucionDAO.error_ajax();
							}
					});
				
			},
			
		CantidadCuentasPorCartera : function ( xidcartera, f_success ) {
				
				$.ajax({
						url : DistribucionDAO.url,
						type : 'GET',
						dataType : 'json',
						data : { 
								command : 'distribucion', 
								action : 'CantidadCuentasPorCartera', 
								idcartera : xidcartera
								},
						beforeSend : function ( ) { 
							},
						success : function ( obj ) {
								f_success(obj);
							},
						error : function ( ) {
								DistribucionDAO.error_ajax();
							}
					});
				
			},	
			
		CarterasServicio : function ( xidservicio, f_success ) {
				
				$.ajax({
						url : DistribucionDAO.url,
						type : 'GET',
						dataType : 'json',
						data : { 
								command : 'distribucion', 
								action : 'ListarCarterasServicio', 
								idservicio : xidservicio 
								},
						beforeSend : function ( ) { 
							},
						success : function ( obj ) {
								f_success(obj);
							},
						error : function ( ) {
								DistribucionDAO.error_ajax();
							}
					});
				
			},
		distribucion_constante : function ( xidcartera, xidcartera_referencia, xoperadores, f_success ) {
				
				$.ajax({
						url : DistribucionDAO.url,
						type : 'POST',
						dataType : 'json',
						data : { command : 'distribucion', action : 'DistribucionConstante', idcartera : xidcartera, idcartera_referencia : xidcartera_referencia, operadores : xoperadores },
						beforeSend : function ( ) {
								_displayBeforeSend('Generando distribucion...',320);
							},
						success : function ( obj ) {
								_noneBeforeSend();
								f_success(obj);
							},
						error : function ( ) {
								_noneBeforeSend();
								DistribucionDAO.error_ajax();
							}
					});
				
			},
		CantidadClientesSinAsignarConstante : function ( xidcartera, f_success ) {
				
				$.ajax({
						url : DistribucionDAO.url,
						type : 'GET',
						dataType : 'json',
						data : { command : 'distribucion', action : 'CantidadClientesSinAsignarConstante', idcartera : xidcartera },
						beforeSend : function ( ) {
							},
						success : function ( obj ) {
								f_success(obj);
							},
						error : function ( ) {
								DistribucionDAO.error_ajax();
							}
					});
				
			},
		distribucion_sin_gestion : function ( xidcartera, xoperadores, f_success ) {
				
				$.ajax({
						url : DistribucionDAO.url,
						type : 'POST',
						dataType : 'json',
						data : { command : 'distribucion', action : 'DistribucionSinGestion', idcartera : xidcartera, operadores : xoperadores },
						beforeSend : function ( ) {
								_displayBeforeSend('Generando distribucion...',320);
							},
						success : function ( obj ) {
								_noneBeforeSend();
								f_success(obj);
							},
						error : function ( ) {
								_noneBeforeSend();
								DistribucionDAO.error_ajax();
							}
					});
				
			},
		CantidadClientesSinAsignarSinGestion : function ( xidcartera, f_success ) {
				
				$.ajax({
						url : DistribucionDAO.url,
						type : 'GET',
						dataType : 'json',
						data : { command : 'distribucion', action : 'CantidadClientesSinAsignarSinGestion', idcartera : xidcartera },
						beforeSend : function ( ) {
							},
						success : function ( obj ) {
								f_success(obj);
							},
						error : function ( ) {
								DistribucionDAO.error_ajax();
							}
					});
				
			},
		CantidadClientesSinAsignarDistrPagos : function ( xidcartera, xdata, f_success ) {

				$.ajax({
						url : DistribucionDAO.url,
						type : 'GET',
						async : false,
						dataType : 'json',
						data : { 
								command : 'distribucion', 
								action : 'CantidadClientesSinAsignarDistrPagos', 
								idcartera : xidcartera ,
								dataPagos : xdata
								},
						beforeSend : function ( ) {
							},
						success : function ( obj ) {
								f_success(obj);
							},
						error : function ( ) {
								DistribucionDAO.error_ajax();
							}
					});

			},
		LoadHeaderFile : function ( xfile, xseparador, f_success, f_before ) {

				$.ajax({
						url : DistribucionDAO.url,
						type : 'POST',
						dataType : 'json',
						data : { 
								command : 'carga-cartera', 
								action : 'loadHeaderFileDistribucionMecanico', 
								file : xfile, 
								separador : xseparador ,
								NombreServicio : $('#hdNomServicio').val()
								},
						beforeSend : function ( ) {
								f_before();
							},
						success : function ( obj ) {
								f_success(obj);
							},
						error : function ( ) {
								DistribucionDAO.error_ajax();
							}
					});	

			},
		save_distribucion_mecanica : function ( xidcartera, xoperadores, xmodo, xseparador, xdata_generate, xfile, f_success ) {

				$.ajax({
						url : DistribucionDAO.url,
						type : 'POST',
						dataType : 'json',
						data : { 
								command : 'carga-cartera', 
								action : 'save_distribucion_manual',
								idcartera : xidcartera, 
								NombreServicio : $('#hdNomServicio').val(),
								Servicio : $('#hdCodServicio').val(),
								usuario_creacion : $('#hdCodUsuario').val(),
								operadores : xoperadores,
								modo : xmodo,
								separador : xseparador,
								data_generate : xdata_generate,
								file : xfile },
						before : function ( ) {
								_displayBeforeSend('Generando distribucion...',320);
							},
						success : function ( obj ) {
								_noneBeforeSend();
								f_success(obj);
							},
						error : function ( ) {
								_noneBeforeSend();
								DistribucionDAO.error_ajax();
							}
					});

			},
		save_distribucion_pagos : function ( xidcartera, xoperadores, xmodo, xdataPagos, f_success ) {

				$.ajax({
						url : DistribucionDAO.url,
						type : 'POST',
						async : false,
						dataType : 'json',
						data : { 
								command : 'distribucion', 
								action : 'save_distribucion_pagos',
								idcartera : xidcartera, 
								NombreServicio : $('#hdNomServicio').val(),
								Servicio : $('#hdCodServicio').val(),
								usuario_creacion : $('#hdCodUsuario').val(),
								operadores : xoperadores,
								modo : xmodo,
								dataPagos : xdataPagos 
								},
						before : function ( ) {
								_displayBeforeSend('Generando distribucion...',320);
							},
						success : function ( obj ) {
								_noneBeforeSend();
								f_success(obj);
							},
						error : function ( ) {
								_noneBeforeSend();
								DistribucionDAO.error_ajax();
							}
					});

			},
		error_ajax : function ( ) {
				$('#layerMessage').html(templates.MsgError('Error en ejecucion de proceso','400px'));
				$('#layerMessage').effect('pulsate',{},'slow',function( ) { $(this).empty(); } );
				_noneBeforeSend();
			}
	}