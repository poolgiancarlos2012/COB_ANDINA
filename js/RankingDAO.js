var RankingDAO = {
		url:'../controller/ControllerCobrast.php',
		idLayerMessage : 'layerMessage',
		ResultData : function ( ){
				$.ajax({
						url : RankingDAO.url,
						type : 'POST',
						dataType : 'json',
						data : {
								command : 'ranking_operador',
								action : 'RankingCartera',
								Servicio : $('#hdCodServicio').val(),
								cartera : $('#tbRKCartera_por_contacto').find(':checked').map(function( ) { return this.value; }).get().join(","),
								Fecha : $('#txtRKFecha').val(),
								fechaf : $('#txtRKFechaFin').val()
								},
						beforeSend : function ( ) {
								$('#layerDataRanking').html(templates.IMGloadingContentLayer());
							},
						success : function ( obj ) {
								var xcategories = new Array();
								var xindices = new Array();
								var xseries = new Array();
								var html='';
								html+='<table cellpadding="0" cellspacing="0" border="0">';
									html+='<tr class="ui-state-default">'
										html+='<td style="border:1px solid #E0CFC2;width:25px;" align="center">#</td>';
								for(i=0;i<obj.cabeceras.length;i++){
									if( obj.cabeceras[i]=='operador' ) {
										html+='<td style="width:300px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;padding:3px 0;" align="center" ><strong>'+obj.cabeceras[i]+'</strong></td>';
									}else if( obj.cabeceras[i]=='idusuario_servicio' ){
										
									}else{
										html+='<td style="width:90px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;padding:3px 0;" align="center"><strong>'+obj.cabeceras[i]+'</strong></td>';
										xindices.push(obj.cabeceras[i]);
										xseries[obj.cabeceras[i]] = new Array();
									}
								}
										html+='<td style="width:25px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;padding:3px 0;" align="center">&nbsp;</td>';
									html+='</tr>';
								//html+='</table>';
								var count=0;
								//html+='<table cellpadding="0" cellspacing="0" border="0">';
								for( i=0;i<obj.data.length;i++ ) {
									var data=eval(obj.data[i]);
									count++;
									html+='<tr class="ui-widget-content" >';
										html+='<td class="ui-state-default" style="width:25px;padding:3px 0;border:1px solid #E0CFC2;" align="center" >'+count+'</td>';
									for( index in data ){
										if( index=='operador' ){
											html+='<td align="center" style="width:300px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+obj.data[i][index]+'</td>';
										}else if( index=='idusuario_servicio' ){
											
										}else{
											html+='<td align="center" style="width:90px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+obj.data[i][index]+'</td>';
											xseries[index].push( parseFloat(obj.data[i][index]) );
										}
									}
										html+='<td align="center" class="ui-state-default" style="width:25px;padding:3px 0;border-bottom:1px solid #E0CFC2;" >&nbsp;</td>';
									html+='</tr>';
									xcategories.push(obj.data[i].operador);
								}
								html+='</table>';
								$('#layerDataRanking').html(html);
								$('#layerDataRanking').find('table:eq(0)').find('tr:gt(0)').hover(function(){ $(this).addClass('ui-state-hover'); },function(){ $(this).removeClass('ui-state-hover'); });
								$('#layerDataRanking').find('table:eq(0)').find('tr:gt(0)').click(function(){ 
									$(this).addClass("ui-state-highlight").siblings().removeClass("ui-state-highlight");
								});
								//alert(xseries['llamadas']);
								var dataseries = '['+$.map(xindices, function ( n, i ){
									return '{"name" : "'+n+'","data" : ['+$.map( xseries[n], function ( value, index ) {
										return value;
									} ).join(",")+']}';
								})+']';
								//alert(dataseries);
								var chartPago = new Highcharts.Chart({
																	chart : { renderTo : 'layer_chart_ranking_contacto', defaultSeriesType : 'column' },
																	title : { text : 'Ranking de Contactos' },
																	xAxis : { categories : xcategories },
																	yAxis : { min : 0, title : { text : 'Contactos' } },
																	legend : { 
																				layout : 'vertical', 
																				backgroundColor: Highcharts.theme.legendBackgroundColor || '#FFFFFF', 
																				align : 'left',
																				verticalAlign : 'top',
																				x : 100,
																				y : 70,
																				floating: true,
																				shadown : true
																			},
																	tooltip : {  
																				formatter : function ( ){
																						return ''+this.x+' : '+this.y;
																					}
																			},
																	plotOptions : {
																				column: {
																					pointPadding: 0.2,
																					borderWidth: 0
																				 }
																			},
																	series : $.parseJSON( dataseries )
																});
								
							},
						error : function ( ) {
								RankingDAO.error_ajax();
							}
					});
			},
		Ranking : {
				
				Pago : function ( xidcartera, f_success, f_before ) {
						
						$.ajax({
								url : RankingDAO.url,
								type : 'GET',
								dataType : 'json',
								data : { command : 'ranking_operador', action : 'ranking_pago', idcartera : xidcartera },
								beforeSend : function ( ) {
										f_before();
									},
								success : function ( obj ) {
										f_success(obj);
									},
								error : function ( ) {}
							});
						
					},
				Estado : function ( xidcartera, xanio, xmes, xdiai, xdiaf, f_success, f_before ) {
						
						$.ajax({
								url : RankingDAO.url,
								type : 'GET',
								dataType : 'json',
								data : { command : 'ranking_operador', action : 'ranking_estado', idcartera : xidcartera, anio : xanio, mes: xmes, diai : xdiai, diaf : xdiaf },
								beforeSend : function ( ) {
										f_before();
									},
								success : function ( obj ) {
										f_success(obj);
									},
								error : function ( ) {}
							});
						
					},
				Abonado_Llamada : function ( xidcartera, xanio, xmes, xdiai, xdiaf, f_success, f_before ) {
						
						$.ajax({
								url : RankingDAO.url,
								type : 'GET',
								dataType : 'json',
								data : { command : 'ranking_operador', action : 'ranking_abonado_llamada', idcartera : xidcartera, anio : xanio, mes: xmes, diai : xdiai, diaf : xdiaf },
								beforeSend : function ( ) {
										f_before();
									},
								success : function ( obj ) {
										f_success(obj);
									},
								error : function ( ) {}
							});
						
					},
				Llamada_Hora : function ( xidservicio, xidcartera, xfecha_inicio, xfecha_fin,  f_success, f_before ) {
						
						$.ajax({
								url : RankingDAO.url,
								type : 'GET',
								dataType : 'json',
								data : { command : 'ranking_operador', action : 'ranking_llamada_hora', idcartera : xidcartera, idservicio: xidservicio, fecha_inicio : xfecha_inicio, fecha_fin : xfecha_fin },
								beforeSend : function ( ) {
										f_before();
									},
								success : function ( obj ) {
										f_success(obj);
									},
								error : function ( ) {}
							});
						
					},
				Llamada_Hora_Detalle : function ( xidservicio, xidcartera, xanio, xmes, xdiai, xdiaf, f_success, f_before ) {
						
						$.ajax({
								url : RankingDAO.url,
								type : 'GET',
								dataType : 'json',
								data : { command : 'ranking_operador', action : 'ranking_llamada_hora_detalle', idcartera : xidcartera, idservicio: xidservicio, anio : xanio, mes: xmes, diai : xdiai, diaf : xdiaf },
								beforeSend : function ( ) {
										f_before();
									},
								success : function ( obj ) {
										f_success(obj);
									},
								error : function ( ) {}
							});
						
					},
				visita : function ( xidcartera, xanio, xmes, xdiai, xdiaf, f_success, f_before ) {
						
						$.ajax({
								url : RankingDAO.url,
								type : 'GET',
								dataType : 'json',
								data : { command : 'ranking_operador', action : 'ranking_visita', idcartera : xidcartera, anio : xanio, mes: xmes, diai : xdiai, diaf : xdiaf },
								beforeSend : function ( ) {
										f_before();
									},
								success : function ( obj ) {
										f_success(obj);
									},
								error : function ( ) {}
							});
						
					},
				semaforo : function ( xidcartera, xfecha_inicio, xfecha_fin,  f_success, f_before ) {
						
						$.ajax({
								url : RankingDAO.url,
								type : 'GET',
								dataType : 'json',
								data : { command : 'ranking_operador', action : 'ranking_semaforo', idcartera : xidcartera, fecha_inicio : xfecha_inicio, fecha_fin : xfecha_fin },
								beforeSend : function ( ) {
										f_before();
									},
								success : function ( obj ) {
										f_success(obj);
									},
								error : function ( ) {}
							});
						
					},
				compromiso_pago : function ( xidcartera, xanio, xmes, xdiai, xdiaf, f_success, f_before ) {
						
						$.ajax({
								url : RankingDAO.url,
								type : 'GET',
								dataType : 'json',
								data : { command : 'ranking_operador', action : 'ranking_compromisos_pago', idcartera : xidcartera, anio : xanio, mes: xmes, diai : xdiai, diaf : xdiaf },
								beforeSend : function ( ) {
										f_before();
									},
								success : function ( obj ) {
										f_success(obj);
									},
								error : function ( ) {}
							});
						
					},
				carga_fecha : function ( xidcartera, xanio, xmes, xdiai, xdiaf, xtipo, f_success, f_before ) {
						
						$.ajax({
								url : RankingDAO.url, 
								type : 'GET', 
								dataType : 'json',
								data : { command : 'ranking_operador', action : 'ranking_carga_fecha', idcartera : xidcartera, anio : xanio, mes: xmes, diai : xdiai, diaf : xdiaf, tipo : xtipo },
								beforeSend : function ( ) {
										f_before();
									},
								success : function ( obj ) {
										f_success(obj);
									},
								error : function ( ) { } 
						});
						
					},
				cont_diario : function ( xidcartera, xanio, xmes, xdiai, xdiaf, f_success, f_before ) {
						
						$.ajax({
								url : RankingDAO.url,
								type : 'GET',
								dataType : 'json',
								data : { command : 'ranking_operador', action : 'ranking_fija_contactabilidad_diario', idcartera : xidcartera, anio : xanio, mes: xmes, diai : xdiai, diaf : xdiaf },
								beforeSend : function ( ) {
										f_before();
									},
								success : function ( obj ) {
										f_success(obj);
									},
								error : function ( ) {}
							});
						
					},
				rpt_gestion : function ( xidcartera, xanio, xmes, xdiai, xdiaf, f_success, f_before ) {
						
						$.ajax({
								url : RankingDAO.url,
								type : 'GET',
								dataType : 'json',
								data : { command : 'ranking_operador', action : 'ranking_fija_rpt_gestion', idcartera : xidcartera, anio : xanio, mes: xmes, diai : xdiai, diaf : xdiaf },
								beforeSend : function ( ) {
										f_before();
									},
								success : function ( obj ) {
										f_success(obj);
									},
								error : function ( ) {}
							});
					
					},
				cont_hora : function ( xidcartera, xfecha_inicio, xfecha_fin,  f_success, f_before ) {
						
						$.ajax({
								url : RankingDAO.url,
								type : 'GET',
								dataType : 'json',
								data : { command : 'ranking_operador', action : 'ranking_contactabilidad_hora', idcartera : xidcartera, fecha_inicio : xfecha_inicio, fecha_fin : xfecha_fin },
								beforeSend : function ( ) {
										f_before();
									},
								success : function ( obj ) {
										f_success(obj);
									},
								error : function ( ) {}
							});
						
					},
				ranking_final : function ( xidcartera, xfecha_inicio, xfecha_fin, xidservicio,  f_success, f_before ) {
						
						$.ajax({
								url : RankingDAO.url,
								type : 'GET',
								dataType : 'json',
								data : { command : 'ranking_operador', action : 'ranking_fija_final', idcartera : xidcartera, fecha_inicio : xfecha_inicio, fecha_fin : xfecha_fin, idservicio : xidservicio },
								beforeSend : function ( ) {
										f_before();
									},
								success : function ( obj ) {
										f_success(obj);
									},
								error : function ( ) {}
							});
						
					},
				semaforo_formato_fija : function ( xidcartera, xidcarga, xfecha_inicio, xfecha_fin, xmeta, f_success, f_before ) {
						
						$.ajax({
								url : RankingDAO.url,
								type : 'GET',
								dataType : 'json',
								data : { command : 'ranking_operador', action : 'ranking_semaforo_fija', idcartera : xidcartera, idcarga : xidcarga , fecha_inicio : xfecha_inicio, fecha_fin : xfecha_fin, meta : xmeta },
								beforeSend : function ( ) {
										f_before();
									},
								success : function ( obj ) {
										f_success(obj);
									},
								error : function ( ) {}
							});
						
					},
				cp_dia_fija : function ( xidcartera, xanio, xmes, xdiai, xdiaf, xidcarga, f_success, f_before ) {
						
						$.ajax({
								url : RankingDAO.url,
								type : 'GET',
								dataType : 'json',
								data : { command : 'ranking_operador', action : 'ranking_fija_cp_dia', idcartera : xidcartera, anio : xanio, mes: xmes, diai : xdiai, diaf : xdiaf, idcarga : xidcarga },
								beforeSend : function ( ) {
										f_before();
									},
								success : function ( obj ) {
										f_success(obj);
									},
								error : function ( ) {}
							});
					
					}
			},
		Listar : {
				
				Campania : function ( f_fill ) {
					
					$.ajax({
							url : RankingDAO.url,
							type : 'GET',
							dataType : 'json',
							data : {command:'atencion_cliente',action:'ListarCampanias',Servicio:$('#hdCodServicio').val()},
							beforeSend : function ( ) {},
							success : function ( obj ) {
									f_fill(obj);
								},
							error : function ( ) {}
						});
					
				},
				Cartera : function ( idCampania, f_fill, idCB ) {
					
					$.ajax({
							url : RankingDAO.url,
							type : 'GET',
							dataType : 'json',
							data : {command:'carga-cartera',action:'ListCartera',Campania:idCampania},
							beforeSend : function ( ) {},
							success : function ( obj ) {
									f_fill(obj,idCB);
								},
							error : function ( ) {
								
								}
						});
					
				},
				CarteraTbRpteRank : function ( idCampania,filtroEstadoCart, f_fill, idCB ) {
					$.ajax({
							url : RankingDAO.url,
							type : 'GET',
							dataType : 'json',
							data : {command:'carga-cartera',action:'ListCarteraRpteRank',Campania:idCampania,Estado:filtroEstadoCart},
							beforeSend : function ( ) {},
							success : function ( obj ) {
									f_fill(obj,idCB);
								},
							error : function ( ) {
								
								}
						});
					
				},
				Carga : function ( xidservicio, f_fill ) {
						
					$.ajax({
							url : RankingDAO.url,
							type : 'GET',
							dataType : 'json',
							data : {command:'ranking_operador',action:'ListCargaServicio', idservicio:xidservicio },
							beforeSend : function ( ) {},
							success : function ( obj ) {
									f_fill(obj);
								},
							error : function ( ) {
								
								}
						});
						
				}
				
			},
		Fill : {
				
				Campania : function ( obj ) {
						var html='';
							html+='<option value="0">--Seleccione--</option>';
						for( i=0;i<obj.length;i++ ) {
							html+='<option value="'+obj[i].idcampania+'">'+obj[i].nombre+'</option>';
						}
						//$('#cbRKCampania').html(html);
						$('select[id^="cbRKCampania"]').html(html);
					},
				Cartera : function ( obj, idCb ) {
						var html='';
							html+='<option value="0">--Seleccione--</option>';
						for( i=0;i<obj.length;i++ ) {
							html+='<option value="'+obj[i].idcartera+'">'+obj[i].nombre_cartera+'</option>';
						}
						//$('#cbRKCartera').html(html);
						$('#'+idCb).html(html);
					},
				CarteraTB : function ( obj, idCb ) {
						var html='';
						var alto='0px';
						if(obj.length>0){alto='120px'}
						html+='<tr><td><div style="height:'+alto+';"><table border="0" cellspacing="0" cellpadding="0">';
						for( i=0;i<obj.length;i++ ) {
							html+='<tr>';
								html+='<td align="center" class="ui-widget-header" style="width:20px;padding:2px 0px;">'+(i+1)+'</td>';
								html+='<td align="center" class="ui-widget-content" style="width:200px;padding:2px 0px;">'+obj[i].nombre_cartera+'</td>';
								html+='<td align="center" class="ui-widget-content" style="width:200px;padding:2px 0px;">'+obj[i].fecha_inicio+'</td>';
								html+='<td align="center" class="ui-widget-content" style="width:200px;padding:2px 0px;">'+obj[i].fecha_fin+'</td>';
								html+='<td align="center" class="ui-widget-content" style="width:20px;padding:2px 0px;"><input  type="checkbox" value="'+obj[i].idcartera+'"  ></td>';
							html+='</tr>';
						}
						html+='</table></div></td></tr>';
						$('#'+idCb).html(html);
					}
				
			},
		hide_message : function ( ) {
				$('#'+RankingDAO.idLayerMessage).effect('blind',{direction:'vertical'},'slow',function(){ $(this).empty().css('display','block'); });
				
			},
		setTimeOut_hide_message : function ( ) {
				setTimeout("RankingDAO.hide_message()",4000);
			},
		error_ajax : function ( ) {
				_noneBeforeSend();
				$('#'+RankingDAO.idLayerMessage).html(templates.MsgError('Error en ejecucion de proceso','400px'));
				RankingDAO.setTimeOut_hide_message();
			}
		
	}