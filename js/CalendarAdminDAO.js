// JavaScript Document
var CalendarAdminDAO = {
		url : '../controller/ControllerCobrast.php',
		idLayerMessage : 'layerMessage',
		Calendar : {
				IdLayerCalendar : 'GestionPanelCalendar',
				HeaderLayerCalendar : 'HeaderGestionPanelCalendar',
				Anio : 0,
				Mes : 0,
				MesWeek : 0,
				AnioWeek : 0,
				initCalendar : function ( init ) {
						if( init==1 ) {
							var hoy=new Date();
							CalendarAdminDAO.Calendar.Mes=hoy.getMonth();
							CalendarAdminDAO.Calendar.Anio=hoy.getFullYear();
							CalendarAdminDAO.Calendar.BuildingCalendar(hoy.getFullYear(),hoy.getMonth());
						}else if( init==2 ){
							
							CalendarAdminDAO.Calendar.Mes=CalendarAdminDAO.Calendar.Mes+1;
							if( CalendarAdminDAO.Calendar.Mes==12 ){
								CalendarAdminDAO.Calendar.Anio=CalendarAdminDAO.Calendar.Anio+1;
								CalendarAdminDAO.Calendar.Mes=0;
							}
							
							CalendarAdminDAO.Calendar.BuildingCalendar(CalendarAdminDAO.Calendar.Anio,CalendarAdminDAO.Calendar.Mes);
						}else if( init==3 ) {
							CalendarAdminDAO.Calendar.Mes=CalendarAdminDAO.Calendar.Mes-1;
							if( CalendarAdminDAO.Calendar.Mes==-1 ){
								CalendarAdminDAO.Calendar.Anio=CalendarAdminDAO.Calendar.Anio-1;
								CalendarAdminDAO.Calendar.Mes=11;
							}
							CalendarAdminDAO.Calendar.BuildingCalendar(CalendarAdminDAO.Calendar.Anio,CalendarAdminDAO.Calendar.Mes);
							
						}
					},
				Dias : ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
				Meses : ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre'],
				Semana : -1,
				BuildingCalendar : function ( anio,mes ) {
						var array=CalendarAdminDAO.Calendar.Dias;
						var meses=CalendarAdminDAO.Calendar.Meses;
						var date=new Date();
						var html='';
						date.setFullYear(anio);
						date.setMonth(mes);
						date.setDate(1);
						
						var idHeader="Calendar_subheader_"+date.getFullYear()+"_"+(date.getMonth()+1);
						var idContentEvento="Calendar_subcontent_evento_"+date.getFullYear()+"_"+(date.getMonth()+1);
						var idContentTarea="Calendar_subcontent_tarea_"+date.getFullYear()+"_"+(date.getMonth()+1);
												
						var CantidadDias=CalendarAdminDAO.Calendar.CantidadDias(date.getMonth()+1,date.getFullYear());
											
						var initDate=date.getDay();
						html+='<table border="0" cellpadding="0" cellspacing="0" >';
						html+='<tr>';
						for( i=0;i<array.length;i++ ){
							html+='<th class="ui-widget-header" style="padding:3px;">'+array[i]+'</th>';
						}
						html+='</tr>';
						var contadorDias=0;
						var contadorBreak=0;
						for ( i=0;i<6;i++ ) {
							if( i==0 ){
								var td='';
								html+='<tr>';
								for( j=0;j<7;j++ ) {
									if( j>=initDate ) {
										contadorDias++;
										html+='<td style="width:120px;height:80px;border:1px solid #E0CFC2;" valign="top" >';
											
											html+='<div id="'+idHeader+'_'+contadorDias+'" lang="'+date.getFullYear()+'-'+(date.getMonth()+1)+'-'+contadorDias+'" align="right" class="HeaderDayCalendar" title="'+contadorDias+'" >';
												html+='<strong>'+contadorDias+'</strong>';
											html+='</div>';
											html+='<div id="'+idContentEvento+'_'+contadorDias+'" onclick="listar_eventos_tareas('+date.getFullYear()+','+(date.getMonth()+1)+','+contadorDias+')" ></div>';
											html+='<div id="'+idContentTarea+'_'+contadorDias+'" onclick="listar_eventos_tareas('+date.getFullYear()+','+(date.getMonth()+1)+','+contadorDias+')" ></div>';
										html+='</td>';	
										td+='<td style="width:122px" id="RangEvent_Calendar_subheader_'+date.getFullYear()+'_'+(date.getMonth()+1)+'_'+contadorDias+'" ></td>';
									}else{
										html+='<td style="width:120px;height:80px;" valign="top"></td>';	
										td+='<td style="width:122px" ></td>';
									}
								}
								html+='</tr>';
								html+='<tr>';
									html+='<td colspan="7">';
									html+='<div>';
										html+='<table border="0" cellpadding="0" cellspacing="0" >';
											html+='<tr>';
												html+=td;
											html+='</tr>';
										html+='</table>';
									html+='</div>';
									html+='</td>';
								html+='</tr>';
							}else{
								var td='';
								
								if( contadorBreak>0 ) {
									break;
								}
								html+='<tr>';
								for( j=0;j<7;j++ ) {
									contadorDias++;
									if( contadorDias==CantidadDias ){
										html+='<td style="width:120px;height:80px;border:1px solid #E0CFC2;" valign="top" ><div id="'+idHeader+'_'+contadorDias+'" align="right" class="HeaderDayCalendar" lang="'+date.getFullYear()+'-'+(date.getMonth()+1)+'-'+contadorDias+'" title="'+contadorDias+'" ><strong>'+contadorDias+'</strong></div><div id="'+idContentEvento+'_'+contadorDias+'"  onclick="listar_eventos_tareas('+date.getFullYear()+','+(date.getMonth()+1)+','+contadorDias+')"  ></div><div id="'+idContentTarea+'_'+contadorDias+'" onclick="listar_eventos_tareas('+date.getFullYear()+','+(date.getMonth()+1)+','+contadorDias+')" ></div></td>';
										td+='<td style="width:122px" id="RangEvent_Calendar_subheader_'+date.getFullYear()+'_'+(date.getMonth()+1)+'_'+contadorDias+'" ></td>';
										contadorBreak++;
										break;
									}else{
										html+='<td style="width:120px;height:80px;border:1px solid #E0CFC2;" valign="top" ><div id="'+idHeader+'_'+contadorDias+'" align="right" class="HeaderDayCalendar" lang="'+date.getFullYear()+'-'+(date.getMonth()+1)+'-'+contadorDias+'" title="'+contadorDias+'" ><strong>'+contadorDias+'</strong></div><div id="'+idContentEvento+'_'+contadorDias+'"  onclick="listar_eventos_tareas('+date.getFullYear()+','+(date.getMonth()+1)+','+contadorDias+')"  ></div><div id="'+idContentTarea+'_'+contadorDias+'" onclick="listar_eventos_tareas('+date.getFullYear()+','+(date.getMonth()+1)+','+contadorDias+')" ></div></td>';
										td+='<td style="width:122px" id="RangEvent_Calendar_subheader_'+date.getFullYear()+'_'+(date.getMonth()+1)+'_'+contadorDias+'" ></td>';
									}
								}
								html+='</tr>';
								
								html+='<tr>';
									html+='<td colspan="7">';
									html+='<div>';
										html+='<table border="0" cellpadding="0" cellspacing="0" >';
											html+='<tr>';
												html+=td;
											html+='</tr>';
										html+='</table>';
									html+='</div>';
									html+='</td>';
								html+='</tr>';
							}
						}
						html+='</table>';
						
						$('#'+CalendarAdminDAO.Calendar.IdLayerCalendar).html(html);
						$('#'+CalendarAdminDAO.Calendar.HeaderLayerCalendar).text((meses[date.getMonth()]+' '+anio));
						//$('#'+CalendarAdminDAO.Calendar.IdLayerCalendar+' td').selectable();
						$('#'+CalendarAdminDAO.Calendar.IdLayerCalendar).find('div[id^="Calendar_subheader_"]').click(function( event ){ 
								$('#FormCalendar,#FormCalendar2').hide()
								var CountSelected=$('#'+CalendarAdminDAO.Calendar.IdLayerCalendar).find('div[class="SelectedHeaderDayCalendar"]').length;
								var p=$(event.target).position();
								if( CountSelected==0 ) {
									var Fecha=$(event.target).attr('lang');
									var IdEventTarget=$(event.target).attr('id').split("_");
									IdEventTarget[1]='subcontent_evento';
									$('#FormCalendar #HdIdEvento').val(IdEventTarget.join("_"));
									IdEventTarget[1]='subcontent_tarea';
									$('#FormCalendar #HdIdTarea').val(IdEventTarget.join("_"));
									$('#FormCalendar #HdFecha').val(Fecha);
									$(event.target).removeClass('HeaderDayCalendar').addClass('SelectedHeaderDayCalendar');
									$('#FormCalendar').fadeIn();
									$('#FormCalendar').css('top',(p.top+20)+'px');
									$('#FormCalendar').css('left',(p.left-200)+'px');
								}else{
									var FirstElement=parseInt($('#'+CalendarAdminDAO.Calendar.IdLayerCalendar).find('div[class="SelectedHeaderDayCalendar"]:last').attr('title'));
									var LastElement=parseInt($(event.target).attr('title'));
									if( LastElement==(FirstElement+1) ) {
										var FechaInicio=$('#'+CalendarAdminDAO.Calendar.IdLayerCalendar).find('div[class="SelectedHeaderDayCalendar"]:first').attr('lang');
										var FechaFin=$(event.target).attr('lang');
										$('#FormCalendar2 #HdFechaInicio').val(FechaInicio);
										$('#FormCalendar2 #HdFechaFin').val(FechaFin);
										$(event.target).removeClass('HeaderDayCalendar').addClass('SelectedHeaderDayCalendar');
										$('#FormCalendar2').fadeIn();
										$('#FormCalendar2').css('top',(p.top+20)+'px');
										$('#FormCalendar2').css('left',(p.left-200)+'px');
									}else{
										$('#FormCalendar2').fadeIn();
									}
								}
								//$(event.target).css('background-color','#F9F9D6');
						 });
						 //$('#'+CalendarAdminDAO.Calendar.IdLayerCalendar).find('div[id^="Calendar_subheader_"]').mouseup(function( event ){ 
//								$(event.target).css('background-color','#F9F9D6');
//						 });
						//CalendarAdminDAO.LastEvent(date.getFullYear(),date.getMonth()+1);
						//CalendarAdminDAO.LastWork(date.getFullYear(),date.getMonth()+1);
						CalendarAdminDAO.LastEventWork(date.getFullYear(),date.getMonth()+1);
						CalendarAdminDAO.ListCalendarEventRange(date.getFullYear(),date.getMonth()+1);
					},
				ReturnMesDias : function ( anio, mes ) {
						var date=new Date();
						date.setFullYear(anio);
						date.setMonth(mes);
						date.setDate(1);
						
						var CantidadDias=CalendarAdminDAO.Calendar.CantidadDias(date.getMonth()+1,date.getFullYear());
						var initDate=date.getDay();
						var ArrayMes=[];
						
						var contadorDias=0;
						var contadorBreak=0;
						for( i=0;i<6;i++ ) {
							if( i==0 ) {
								var ArrayWeek=new Array();
								for( j=0;j<7;j++ ) {
									if( j>=initDate ){
										contadorDias++;
										ArrayWeek.push(contadorDias);
									}else{
										ArrayWeek.push(-1);
									}
								}
								ArrayMes.push(ArrayWeek);
							}else{
								if( contadorBreak>0 ) {
									break;
								}
								
								var ArrayWeek=new Array();
								for( j=0;j<7;j++ ) {
									contadorDias++;
									if( contadorDias==CantidadDias ) {
										ArrayWeek.push(contadorDias);
										contadorBreak++;
										ArrayMes.push(ArrayWeek);
										break;
									}else{
										ArrayWeek.push(contadorDias);
									}
								}
								ArrayMes.push(ArrayWeek);
							}
							
						}
						
						
						return ArrayMes;
						
					},
				initWeek : function ( init ) {
						var Week=CalendarAdminDAO.Calendar.semana;
						
						if( init==1 ) {
							var hoy=new Date();
							var ArrayMes=CalendarAdminDAO.Calendar.ReturnMesDias(hoy.getFullYear(),hoy.getMonth());
							CalendarAdminDAO.Calendar.MesWeek=hoy.getMonth();
							CalendarAdminDAO.Calendar.AnioWeek=hoy.getFullYear();
							for( i=0;i<ArrayMes.length;i++ ) { 
								for( j=0;j<ArrayMes[i].length;j++ ) {
									if( ArrayMes[i][j]==hoy.getDate() ) {
										CalendarAdminDAO.Calendar.semana=i;
										CalendarAdminDAO.Calendar.BuildingWeek(ArrayMes[CalendarAdminDAO.Calendar.semana],CalendarAdminDAO.Calendar.AnioWeek,CalendarAdminDAO.Calendar.MesWeek);
										break;
									}
								}
							}
							
						}else if( init==2 ) {
							CalendarAdminDAO.Calendar.semana=CalendarAdminDAO.Calendar.semana+1;
							var ArrayMes=CalendarAdminDAO.Calendar.ReturnMesDias(CalendarAdminDAO.Calendar.AnioWeek,CalendarAdminDAO.Calendar.MesWeek); 
							if ( CalendarAdminDAO.Calendar.semana>=(ArrayMes.length-1) ) {
								CalendarAdminDAO.Calendar.MesWeek=CalendarAdminDAO.Calendar.MesWeek+1;
								CalendarAdminDAO.Calendar.semana=0;
							}
							
							if( CalendarAdminDAO.Calendar.MesWeek==12 ){
								CalendarAdminDAO.Calendar.AnioWeek=CalendarAdminDAO.Calendar.AnioWeek+1;
								CalendarAdminDAO.Calendar.MesWeek=0;
							}
							var ArrayMes=CalendarAdminDAO.Calendar.ReturnMesDias(CalendarAdminDAO.Calendar.AnioWeek,CalendarAdminDAO.Calendar.MesWeek); 
							CalendarAdminDAO.Calendar.BuildingWeek(ArrayMes[CalendarAdminDAO.Calendar.semana],CalendarAdminDAO.Calendar.AnioWeek,CalendarAdminDAO.Calendar.MesWeek);
							
						}else if( init==3 ) {
							CalendarAdminDAO.Calendar.semana=CalendarAdminDAO.Calendar.semana-1;
							if( CalendarAdminDAO.Calendar.semana==-1 ) {
								CalendarAdminDAO.Calendar.semana=(CalendarAdminDAO.Calendar.ReturnMesDias(CalendarAdminDAO.Calendar.AnioWeek,CalendarAdminDAO.Calendar.MesWeek-1).length-2); 
								CalendarAdminDAO.Calendar.MesWeek=CalendarAdminDAO.Calendar.MesWeek-1;
							}

							if( CalendarAdminDAO.Calendar.MesWeek==-1 ){
								CalendarAdminDAO.Calendar.AnioWeek=CalendarAdminDAO.Calendar.AnioWeek-1;
								CalendarAdminDAO.Calendar.MesWeek=11;
							}
							var ArrayMes=CalendarAdminDAO.Calendar.ReturnMesDias(CalendarAdminDAO.Calendar.AnioWeek,CalendarAdminDAO.Calendar.MesWeek); 
							CalendarAdminDAO.Calendar.BuildingWeek(ArrayMes[CalendarAdminDAO.Calendar.semana],CalendarAdminDAO.Calendar.AnioWeek,CalendarAdminDAO.Calendar.MesWeek);
						}
						
					},
				BuildingWeek : function ( ArrayWeek, anio, mes ) {
						var count=0;
						var header="";
						var html='';
						html+='<table cellspacing="0" cellpadding="0" border="0" >';
						for( i=0;i<ArrayWeek.length;i=i+2 ) {
								html+='<tr>';
							if( ArrayWeek[i]!=(-1) ) {
									if( count==0 ){
										header+=" del "+ArrayWeek[i];
									}
									count++;
									html+='<td id="calendar_panel_weeek_" style="border: 1px solid rgb(224, 207, 194);" valign="top" >';
										html+='<div class="HeaderDayCalendar" style="height:20px;">';
											html+='<table cellspacing="0" cellpadding="0" border="0" >';
												html+='<tr>';
													html+='<td align="center" width="425px"><span style="color:#003399;font-weight:bold;">'+CalendarAdminDAO.Calendar.Dias[i]+' '+ArrayWeek[i]+'</span></td>'; 
													html+='<td width="14px" align="center"><div class="icon" style="background-position:-40px -428px;height:18px;" onclick="DisplayFormWeek(event,\''+anio+'-'+(mes+1)+'-'+ArrayWeek[i]+'\',\'calendar_week_panel_'+anio+'-'+(mes+1)+'-'+ArrayWeek[i]+'\')" ></div></td>';
												html+='</tr>';
											html+='</table>';
										html+='</div>';
										html+='<div style="height:95px;width:100%;" id="calendar_week_panel_'+anio+'-'+(mes+1)+'-'+ArrayWeek[i]+'" align="center" >';
											html+='<div id="calendar_week_sub_panel_event" align="center"></div>';
											html+='<div id="calendar_week_sub_panel_work" align="center"></div>';
										html+='</div>';
									html+='</td>';
							
							}else{
								html+='<td></td>';
							}
							if( ArrayWeek[i+1]!=(-1) && ArrayWeek[i+1]!=undefined ) {
									if( count==0 ) {
										header+=" del "+ArrayWeek[i];
									}
									count++;
									html+='<td id="calendar_panel_weeek_" style="border: 1px solid rgb(224, 207, 194);" valign="top" >';
										html+='<div class="HeaderDayCalendar" style="height:20px;">';
											html+='<table cellspacing="0" cellpadding="0" border="0" >';
												html+='<tr>';
													html+='<td align="center" width="425"><span style="color:#003399;font-weight:bold;">'+CalendarAdminDAO.Calendar.Dias[i+1]+' '+ArrayWeek[i+1]+'</span></td>';
													html+='<td width="14" align="center"><div class="icon" style="background-position:-40px -428px;height:18px;" onclick="DisplayFormWeek(event,\''+anio+'-'+(mes+1)+'-'+ArrayWeek[i+1]+'\',\'calendar_week_panel_'+anio+'-'+(mes+1)+'-'+ArrayWeek[i+1]+'\')" ></div></td>';
												html+='</tr>';
											html+='</table>';
										html+='</div>';
										html+='<div style="height:95px;width:100%;" id="calendar_week_panel_'+anio+'-'+(mes+1)+'-'+ArrayWeek[i+1]+'" align="center" >';
											html+='<div id="calendar_week_sub_panel_event" align="center"></div>';
											html+='<div id="calendar_week_sub_panel_work" align="center"></div>';
										html+='</div>';
									html+='</td>';
																
							}else{
								html+='<td></td>';
							}
								html+='</tr>';
							
						}
						html+='</table>';
						header+=" al "+ArrayWeek[ArrayWeek.length-1]+" de "+CalendarAdminDAO.Calendar.Meses[mes];
						$('#GestionPanelCalendarWeek').html(html);
						$('#panelAtencionCalendarWeek #HeaderGestionPanelCalendar').text(header);
						$('#GestionPanelCalendarWeek').find('td[id^="calendar_panel_weeek"]').hover(function(){ $(this).addClass("ui-state-highlight"); },function(){ $(this).removeClass("ui-state-highlight"); });
					},
				CantidadDias : function ( humanMonth, year ) {
						return new Date(year || new Date().getFullYear(), humanMonth, 0).getDate();
					}
			},
		LastEventWork : function ( xanio ,xmes  ) {
				$.ajax({
						url : CalendarAdminDAO.url,
						type : 'GET',
						dataType : 'json',
						data : {
								command : 'calendar',
								action : 'LastEventWork',
								UsuarioServicio : $('#hdCodUsuarioServicio').val(),
								Anio : xanio,
								Mes : xmes
								},
						success : function ( obj ) {
								for( j=0;j<obj.length;j++ ) {
									var html='';
									var xlayer=obj[j].layer.split("_");
									if(xlayer[2]=='evento'){
										html='<div class="LayerEvento ui-corner-all" align="center"><table><tr><td align="center" style="width:118px;">'+obj[j].hora+'</td></tr><tr><td style="width:118px;text-align:center;white-space:pre-line;">'+obj[j].titulo+'</td></tr></table></div>';
									}else{
										html='<div class="LayerTarea ui-corner-all" align="center"><table><tr><td align="center" style="width:118px;">'+obj[j].hora+'</td></tr><tr><td style="width:118px;text-align:center;white-space:pre-line;">'+obj[j].titulo+'</td></tr></table></div>';
									}
									
									$('#'+obj[j].layer).html(html);
								}
							},
						error : function ( ) {
								
							}
					});
			},
		LastEvent : function ( xanio ,xmes  ) {
				$.ajax({
						url : CalendarAdminDAO.url,
						type : 'GET',
						dataType : 'json',
						data : {
								command : 'calendar',
								action : 'LastEvent',
								UsuarioServicio : $('#hdCodUsuarioServicio').val(),
								Anio : xanio,
								Mes : xmes
								},
						success : function ( obj ) {
								for( j=0;j<obj.length;j++ ) {
									var html='';
									html='<div class="LayerEvento ui-corner-all"><table><tr><td>'+obj[j].hora+'</td></tr><tr><td>'+obj[j].evento+'</td></tr></table></div>';
									$('#'+obj[j].layer).html(html);
								}
							},
						error : function ( ) {
								
							}
					});
			},
		LastWork : function ( xanio ,xmes ) {
				
				$.ajax({
						url : CalendarAdminDAO.url,
						type : 'GET',
						dataType : 'json',
						data : {
								command : 'calendar',
								action : 'LastWork',
								UsuarioServicio : $('#hdCodUsuarioServicio').val(),
								Anio : xanio,
								Mes : xmes
								},
						success : function ( obj ) {
								for( j=0;j<obj.length;j++ ) {
									var html='';
									html='<div class="LayerTarea ui-corner-all"><table><tr><td>'+obj[j].hora+'</td></tr><tr><td>'+obj[j].titulo+'</td></tr></table></div>';
									$('#'+obj[j].layer).html(html);
								}
							},
						error : function ( ) {
								
							}
					});

			},
		ListCalendarEventRange : function ( xanio ,xmes ) {
				$.ajax({
						url : CalendarAdminDAO.url,
						type : 'GET',
						dataType : 'json',
						data : {
								command:'calendar',
								action:'ListEventRange',
								UsuarioServicio : $('#hdCodUsuarioServicio').val(),
								Anio : xanio,
								Mes : xmes
								},
						success : function ( obj ) { 
								for( i=0;i<obj.length;i++ ){
									var prm=Math.ceil(Math.ceil(obj[i].cantidad_dia)/2);
									var rnd=Math.round(Math.random()*999+1);
									var count=0;
									for( j=obj[i].dia_inicio;j<=obj[i].dia_fin;j++ ) {
										count++;
										var rdn_id=$('#RangEvent_Calendar_subheader_'+obj[i].anio+'_'+obj[i].mes+'_'+j).find('.SubLayerEvento').attr('id');
										if( rdn_id!='' && rdn_id!=undefined ) {
											$('td[id^="RangEvent_"]').find('div[id="'+rdn_id+'"]').parent().empty();
										}
										
										if( count==prm ) {
											var html='';
											if( j==obj[i].dia_inicio ) {
												html+='<div class="SubLayerEvento ui-corner-left" id="rdn_'+rnd+'" >'+obj[i].evento+'</div>';
											}else if( j==obj[i].dia_fin ) {
												html+='<div class="SubLayerEvento ui-corner-right" id="rdn_'+rnd+'">'+obj[i].evento+'</div>';
											}else{
												html+='<div class="SubLayerEvento" id="rdn_'+rnd+'">'+obj[i].evento+'</div>';
											}
											$('#RangEvent_Calendar_subheader_'+obj[i].anio+'_'+obj[i].mes+'_'+j).html(html);
										}else{
											var html='';
											if( j==obj[i].dia_inicio ) {
												html+='<div class="SubLayerEvento ui-corner-left" id="rdn_'+rnd+'" ></div>';
											}else if( j==obj[i].dia_fin ) {
												html+='<div class="SubLayerEvento ui-corner-right" id="rdn_'+rnd+'"></div>';
											}else{
												html+='<div class="SubLayerEvento" id="rdn_'+rnd+'"></div>';
											}
											$('#RangEvent_Calendar_subheader_'+obj[i].anio+'_'+obj[i].mes+'_'+j).html(html);
										}
									}
								}
																
							},
						error : function ( ) { 
							
							}
					});
			},
		ListEvent : function ( ) {
				$.ajax({
						url : CalendarAdminDAO.url,
						type : 'GET',
						dataType : 'json',
						data : {command:'calendar',action:''},
						success : function ( obj ) { 
								
							},
						error : function ( ) { }
					});
			},
		ListWork : function ( ) {
				$.ajax({
						url : CalendarAdminDAO.url,
						type : 'GET',
						dataType : 'json',
						data : {command:'calendar',action:''},
						success : function ( obj ) { 
								
							},
						error : function ( ) { }
					});
			},
		SaveEvento : function ( id, xoperadores ) {
				$.ajax({
						url : CalendarAdminDAO.url,
						type : 'POST',
						dataType : 'json',
						data : {
								command : 'calendar',
								action : 'GuardarEventoMasivo',
								Evento : $.trim($('#FormCalendar #txtEvento').val()),
								Fecha : $('#FormCalendar #HdFecha').val(),
								Hora : $('#FormCalendar #txtTiempoEvento').val(),
								UsuarioServicio : $('#hdCodUsuarioServicio').val(),
								UsuarioCreacion : $('#hdCodUsuario').val(),
								operadores : xoperadores 
								},
						beforeSend : function ( ) {},
						success : function ( obj ) {
								if( obj.rst ) {
									var Hora=$('#FormCalendar #txtTiempoEvento').val();
									var Evento=$.trim($('#FormCalendar #txtEvento').val());
									var html='';
									html='<div class="LayerEvento ui-corner-all" align="center"><table><tr><td align="center" style="width:118px;">'+Hora+'</td></tr><tr><td style="width:118px;text-align:center;white-space:pre-line;">'+Evento+'</td></tr></table></div>';
									$('#'+id).html(html);
									CloseFormCalendar();
									$('#FormCalendar').find(':hidden,:text').val('');
								}else {
									
								}
							},
						error : function ( ) {
								
							}
					});
			},
		SaveRangeEvento : function ( id ) {
				$.ajax({
						url : CalendarAdminDAO.url,
						type : 'POST',
						dataType : 'json',
						data : {
								command : 'calendar',
								action : 'GuardarRangoEvento',
								Evento : $.trim($('#FormCalendar2 #txtEvento').val()),
								FechaInicio : $('#FormCalendar2 #HdFechaInicio').val(),
								FechaFin : $('#FormCalendar2 #HdFechaFin').val(),
								Hora : $('#FormCalendar2 #txtTiempoEvento').val(),
								UsuarioServicio : $('#hdCodUsuarioServicio').val(),
								UsuarioCreacion : $('#hdCodUsuario').val()
								},
						beforeSend : function ( ) {},
						success : function ( obj ) {
								if( obj.rst ) {
									var ObjectDiv=$('#'+CalendarAdminDAO.Calendar.IdLayerCalendar).find('div[class="SelectedHeaderDayCalendar"]');
									var LengthHeaders=$('#'+CalendarAdminDAO.Calendar.IdLayerCalendar).find('div[class="SelectedHeaderDayCalendar"]').length;
									var prm=Math.ceil(Math.ceil(LengthHeaders)/2);
									var rnd=Math.round(Math.random()*999+1);
									var count=0;
									$.each(ObjectDiv,function(key,data){
										count++;
										var rdn_id=$('#RangEvent_'+$(data).attr('id')).find('.SubLayerEvento').attr('id');
										//alert($('#RangEvent_'+$(data).attr('id')).find('.SubLayerEvento').attr('id'));
										if( rdn_id!='' && rdn_id!=undefined  ) {
											$('td[id^="RangEvent_"]').find('div[id="'+rdn_id+'"]').parent().empty();
										}
										
										if( count==prm ) {
											var id=$(data).attr('id');
											var html='';
											if( count==1 ) {
												html+='<div class="SubLayerEvento ui-corner-left" id="rdn_'+rnd+'" >'+$.trim($('#FormCalendar2 #txtEvento').val())+'</div>';
											}else if( count==LengthHeaders ) {
												html+='<div class="SubLayerEvento ui-corner-right" id="rdn_'+rnd+'">'+$.trim($('#FormCalendar2 #txtEvento').val())+'</div>';
											}else{
												html+='<div class="SubLayerEvento" id="rdn_'+rnd+'">'+$.trim($('#FormCalendar2 #txtEvento').val())+'</div>';
											}
											$('#'+CalendarAdminDAO.Calendar.IdLayerCalendar+' #RangEvent_'+id).html(html);
										}else{
											var id=$(data).attr('id');
											var html='';
											if( count==1 ) {
												html+='<div class="SubLayerEvento ui-corner-left" id="rdn_'+rnd+'"></div>';
											}else if( count==LengthHeaders ){
												html+='<div class="SubLayerEvento ui-corner-right" id="rdn_'+rnd+'"></div>';
											}else{
												html+='<div class="SubLayerEvento" id="rdn_'+rnd+'"></div>';
											}
											$('#'+CalendarAdminDAO.Calendar.IdLayerCalendar+' #RangEvent_'+id).html(html);
										}
									});
									
									CloseFormCalendar();
									$('#FormCalendar2').find(':hidden,:text').val('');
								}else {
									
								}
							},
						error : function ( ) {
								
							}
					});
			},
		SaveEventWeek : function ( id ) {
				
				$.ajax({
						url : CalendarAdminDAO.url,
						type : 'POST',
						dataType : 'json',
						data : {
								command : 'calendar',
								action : 'GuardarEvento',
								Evento : $.trim($('#FormCalendarWeek #txtEvento_w').val()),
								Fecha : $('#FormCalendarWeek #HdFecha_w').val(),
								Hora : $('#FormCalendarWeek #txtTiempoEvento_w').val(),
								UsuarioServicio : $('#hdCodUsuarioServicio').val(),
								UsuarioCreacion : $('#hdCodUsuario').val()
								},
						beforeSend : function ( ) {},
						success : function ( obj ) {
								if( obj.rst ) {
									var Hora=$('#FormCalendarWeek #txtTiempoEvento_w').val();
									var Evento=$.trim($('#FormCalendarWeek #txtEvento_w').val());
									var html='';
										html+='<table cellpadding="0" cellspacing="0" border="0" style="width:98%;margin-top:11px;border-bottom:1px solid #E0CFC2;" >';
											html+='<tr>';
												html+='<td>';
													html+='<div class="TitleIcon" style="background-position:-2px -1341px;margin-right:5px;width:25px;height:20px;float:left;"></div>';
													html+='<div style="color:#003399;font-weight:bold;float:left;">'+Evento+'</div>';
												html+='</td>';
											html+='</tr>';
											html+='<tr>';
												html+='<td>';
													html+='<div style="color:#800000;padding-left:25px;height:15px;line-height:14px;font-weight:bold;">'+Hora+'</div>';
												html+='</td>';
											html+='</tr>';
										html+='</table>';
									$('#'+id+' #calendar_week_sub_panel_event').html(html);
									CloseFormCalendarWeek();
									$('#FormCalendarWeek').find(':hidden,:text').val('');
								}else {
									
								}
							},
						error : function ( ) {
								
							}
					});
				
			},
		SaveWorkWeek : function ( id ) {
				
				$.ajax({
						url : CalendarAdminDAO.url,
						type : 'POST',
						dataType : 'json',
						data : {
								command : 'calendar',
								action : 'GuardarTarea',
								Titulo :$.trim($('#FormCalendarWeek #txtTarea_w').val()),
								Nota : $.trim($('#FormCalendarWeek #txtNota_w').val()),
								Fecha : $('#FormCalendarWeek #HdFecha_w').val(),
								Hora : $('#FormCalendarWeek #txtTiempoTarea_w').val(),
								UsuarioServicio : $('#hdCodUsuarioServicio').val(),
								UsuarioCreacion : $('#hdCodUsuario').val()
								},
						beforeSend : function ( ) {},
						success : function ( obj ) {
								if( obj.rst ) {
									var Hora=$('#FormCalendarWeek #txtTiempoTarea_w').val();
									var Tarea=$.trim($('#FormCalendarWeek #txtTarea_w').val());
									var html='';
										html+='<table cellpadding="0" cellspacing="0" border="0" style="width:99%;margin-top:11px;" >';
											html+='<tr>';
												html+='<td>';
													html+='<div class="icon" style="background-position:-44px -61px;margin-right:5px;width:25px;height:20px;float:left;"></div>';
													html+='<div style="color:#003399;font-weight:bold;float:left;">'+Tarea+'</div>';
												html+='</td>';
											html+='</tr>';
											html+='<tr>';
												html+='<td>';
													html+='<div style="color:#800000;padding-left:25px;height:15px;line-height:14px;font-weight:bold;">'+Hora+'</div>';
												html+='</td>';
											html+='</tr>';
										html+='</table>';
									$('#'+id+' #calendar_week_sub_panel_work').html(html);
									CloseFormCalendarWeek();
									$('#FormCalendarWeek').find(':hidden,:text,textarea').val('');
								}else {
									
								}
							},
						error : function ( ) {}
					});
				
			},
		ListarEventos : function ( xanio, xmes, xdia ) {
				$.ajax({
						url : CalendarAdminDAO.url,
						type : 'GET',
						dataType : 'json',
						data : {
								command : 'calendar',
								action : 'ListarEventos',
								UsuarioServicio : $('#').val(),
								Anio : xanio,
								Mes : xmes,
								Dia : xdia
							},
						beforeSend : function ( ) {},
						success : function ( obj ) {
								
							},
						error : function ( ) {}
					});
			},
		FillAccordionEventos : function ( obj ) {
				var html='';
				for( i=0;i<obj.length;i++ ) {
					
				}
			},
		ListarTareas : function ( xanio, xmes, xdia ) {
				$.ajax({
						url : CalendarAdminDAO.url,
						type : 'GET',
						dataType : 'json',
						data : {
								command : 'calendar',
								action : 'ListarTareas',
								UsuarioServicio : $('#').val(),
								Anio : xanio,
								Mes : xmes,
								Dia : xdia
							},
						beforeSend : function ( ) {},
						success : function ( obj ) {
								
							},
						error : function ( ) {}
					});
			},
		SaveTarea : function ( id, xoperadores ) {
				$.ajax({
						url : CalendarAdminDAO.url,
						type : 'POST',
						dataType : 'json',
						data : {
								command : 'calendar',
								action : 'GuardarTareaMasiva',
								Titulo :$.trim($('#FormCalendar #txtTarea').val()),
								Nota : $.trim($('#FormCalendar #txtNota').val()),
								Fecha : $('#FormCalendar #HdFecha').val(),
								Hora : $('#FormCalendar #txtTiempoTarea').val(),
								UsuarioServicio : $('#hdCodUsuarioServicio').val(),
								UsuarioCreacion : $('#hdCodUsuario').val(),
								operadores : xoperadores
								},
						beforeSend : function ( ) {},
						success : function ( obj ) {
								if( obj.rst ) {
									var Hora=$('#FormCalendar #txtTiempoTarea').val();
									var Tarea=$.trim($('#FormCalendar #txtTarea').val());
									var html='';
									html='<div class="LayerTarea ui-corner-all" align="center"><table><tr><td align="center" style="width:118px;">'+Hora+'</td></tr><tr><td style="width:118px;text-align:center;white-space:pre-line;">'+Tarea+'</td></tr></table></div>';
									$('#'+id).html(html);
									CloseFormCalendar();
									$('#FormCalendar').find(':hidden,:text,textarea').val('');
								}else {
									
								}
							},
						error : function ( ) {}
					});
			},
		ListarUsuarioServicio : function ( xidservicio, f_success ) {
				
				$.ajax({
						url : CalendarAdminDAO.url,
						type : 'GET',
						dataType : 'json',
						data : { command : 'atencion_cliente', action : 'ListarOperadores', Servicio : xidservicio },
						beforeSend : function ( ) {},
						success : function ( obj ) {
								f_success(obj);
							},
						error : function ( ) {}
					});
				
			},
		hide_message : function ( ) {
				$('#'+CalendarAdminDAO.idLayerMessage).effect('blind',{direction:'vertical'},'slow',function(){ $(this).empty().css('display','block'); });
				
			},
		setTimeOut_hide_message : function ( ) {
				setTimeout("CalendarAdminDAO.hide_message()",4000);
			},
	}