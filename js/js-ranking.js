$(document).ready(function( ){
	$('#layerDatepicker').datepicker({inline:true,autoSize:true,dayNamesMin:['D','L','M','M','J','V','S'],monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']});
	/*********/
	RankingDAO.Listar.Campania(RankingDAO.Fill.Campania);
	/**********/
	load_carga_final_servicio();
	/*********/
	generar_anio(
				[
				{id:'cbAnio_pago'},
				{id:'cbAnio_estado'},
				{id:'cbAnio_abonado_llamada'},
				{id:'cbAnio_llamada_hora_detalle'},
				{id:'cbAnio_visita'},
				{id:'cbAnio_cp'},
				{id:'cbAnio_fija_estado'},
				{id:'cbAnio_fija_rpt_gest'},
				{id:'cbAnio_fija_cp'},
				{id:'cbAnio_carga_fecha'}
				]
				);
	generar_mes(
				[
				{id:'cbMes_pago'},
				{id:'cbMes_estado'},
				{id:'cbMes_abonado_llamada'},
				{id:'cbMes_llamada_hora_detalle'},
				{id:'cbMes_visita'},
				{id:'cbMes_cp'},
				{id:'cbMes_fija_estado'},
				{id:'cbMes_fija_rpt_gest'},
				{id:'cbMes_fija_cp'},
				{id:'cbMes_carga_fecha'}
				]
				);
	generar_dia(
				[
				{id:'cbDiaI_pago'},{id:'cbDiaF_pago'},
				{id:'cbDiaI_estado'},{id:'cbDiaF_estado'},
				{id:'cbDiaI_abonado_llamada'},{id:'cbDiaF_abonado_llamada'},
				{id:'cbDiaI_llamada_hora_detalle'},{id:'cbDiaF_llamada_hora_detalle'},
				{id:'cbDiaI_visita'},{id:'cbDiaF_visita'},
				{id:'cbDiaI_cp'},{id:'cbDiaF_cp'},
				{id:'cbDiaI_fija_estado'},{id:'cbDiaF_fija_estado'},
				{id:'cbDiaI_fija_rpt_gest'},{id:'cbDiaF_fija_rpt_gest'},
				{id:'cbDiaI_fija_cp'},{id:'cbDiaF_fija_cp'},
				{id:'cbDiaI_carga_fecha'},{id:'cbDiaF_carga_fecha'}
				]
				);
	/**********/
	
	//$('#txtRKFecha').datepicker({dateFormat:'yy-mm-dd',dayNamesMin:['D','L','M','M','J','V','S'],monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']});
	$(':text[id^="txtRKFecha"]').datepicker({dateFormat:'yy-mm-dd',dayNamesMin:['D','L','M','M','J','V','S'],monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']});
});
load_carga_final_servicio = function ( ) {
	RankingDAO.Listar.Carga( $('#hdCodServicio').val(), function ( obj ) {
			html = '';
			for( i=0;i<obj.length;i++ ) {
				html+='<tr>';
					html+='<td align="center" class="ui-widget-header" style="padding:2px 0;width:20px;">'+(i+1)+'</td>';
					html+='<td align="center" class="ui-widget-content" style="padding:2px 0;width:100px;">'+obj[i].nombre+'</td>';
					html+='<td align="center" class="ui-widget-content" style="padding:2px 0;width:20px;"><input type="checkbox" value="'+obj[i].idcarga_final+'" /></td>';
				html+='</tr>';
			}
			$('#tbRK_Carga_Cartera_fija_semaforo,#tbRK_Carga_Cartera_fija_cp').html(html);
		} );
}
load_ranking_cartera = function ( idCampania, idCB ) {
	RankingDAO.Listar.Cartera(idCampania,RankingDAO.Fill.Cartera, idCB );
}
load_ranking_cartera_tb = function ( idCampania, idTB ) {
	RankingDAO.Listar.Cartera(idCampania,RankingDAO.Fill.CarteraTB, idTB );
}
load_ranking_cartera_tb_rpte_rank = function ( idCampania, idTB ) {
	var filtroEstadoCart=$('#tbEstadoCarteraReporte').find(':checked').map(function(){return this.value;}).get().join(",");
	if(filtroEstadoCart==''){
		alert("Seleccione Estado de Cartera (No Vencido / Vencido)");	
		return false;
	}
	RankingDAO.Listar.CarteraTbRpteRank(idCampania,filtroEstadoCart,RankingDAO.Fill.CarteraTB, idTB );
}
limpiaCamposReporte = function(){
	//$('#cbCampaniaLlaEst').val('0');
	$('#cobrastHOME').find('select[id^="cbRKCampania"]').val('0');
	html_tb='<tr><td><div style="height:0;"><table border="0" cellspacing="0" cellpadding="0">';
	$('#cobrastHOME').find('[id^="tbRKCartera"]').html(html_tb);
	}
load_data_ranking = function ( ) {
	var servicio=$('#hdCodServicio').val();
	//var cartera=$('#cbRKCartera').val();
	var cartera = $('#tbRKCartera_por_contacto').find(':checked').map(function( ) { return this.value; }).get().join(",");
	var fecha=$('#txtRKFecha').val();
	var fechaf=$('#txtRKFechaFin').val();
	
	
	/*var rs=validacion.check([
			{id:'cbRKCartera',isNotValue:0,errorNotValueFunction:function ( ) {
					$('#'+RankingDAO.idLayerMessage).html(templates.MsgError('Seleccione cartera','400px'));
					RankingDAO.setTimeOut_hide_message();
				}},
			{id:'txtRKFecha',required:true,errorRequiredFunction:function( ){
					$('#'+RankingDAO.idLayerMessage).html(templates.MsgError('Ingrese fecha','400px'));
					RankingDAO.setTimeOut_hide_message();
				}}
			]);*/
	
	if(fecha=='' || fechaf==''){
		alert('Seleccione Fecha');
		return false;
	}		
	if( cartera.length>0 ){
		RankingDAO.ResultData();
	}else{
		alert('Seleccione Cartera');
		return false;
	}
		
}
load_data_ranking_rpte = function () {
	var servicio=$('#hdCodServicio').val();
	var cartera = $('#tbRKCartera_por_contacto').find(':checked').map(function( ) { return this.value; }).get().join(",");
	var fecha=$('#txtRKFecha').val();
	var fechaf=$('#txtRKFechaFin').val();
	
	if(fecha=='' || fechaf==''){
		alert('Seleccione Fecha');
		return false;
	}		
	if( cartera.length>0 ){
		window.location.href="../rpt/excel/gestion/ranking_operador.php?Servicio="+servicio+"&Cartera="+cartera+"&Fecha="+fecha+"&fechaf="+fechaf; //cartera=Gestion
	}else{
		alert('Seleccione Cartera');
		return false;
	}
		
}
load_ranking_pago_rpte = function () {
	var cartera = $('#tbRKCartera_ranking_pago').find(':checked').map(function( ) { return this.value; }).get().join(",");
	if( cartera.length>0 ){
		window.location.href="../rpt/excel/ranking_pagos.php?Cartera="+cartera; //cartera=Gestion
	}else{
		alert('Seleccione Cartera');
		return false;
	}
		
}
load_ranking_pago = function ( ) {
	
	var cartera = $('#tbRKCartera_ranking_pago').find(':checked').map(function( ) { return this.value; }).get().join(",");
	if(cartera.length==0){
		alert('Seleccione Cartera');
		return false;
	}
	
	RankingDAO.Ranking.Pago( cartera, function ( obj ) {
			var xcategories = new Array();
			var xmontos = new Array();
			var xpagos = new Array();
			var html = '';
				html+= '<tr class="ui-state-default">';
					html+='<td align="center" style="width:30px; border-top:1px solid #E0CFC2;border-bottom:0px;border-right:1px solid #E0CFC2;border-left:1px solid #E0CFC2;padding:0;">&nbsp;</td>';
					html+='<td align="center" style="width:300px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;padding:3px 0;">TELEOPERADOR</td>';
					html+='<td align="center" style="width:100px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;padding:3px 0;">DEUDA</td>';
					html+='<td align="center" style="width:100px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;padding:3px 0;">PAGO</td>';
				html+= '<tr>';
			for( i=0;i<obj.length;i++ ) {
				html+='<tr class="ui-widget-content">';
					html+='<td align="center" class="ui-state-default" style="width:30px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:0px;" >'+(i+1)+'</td>';
					html+='<td align="center" style="width:300px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" >'+obj[i].TELEOPERADOR+'</td>';
					html+='<td align="center" style="width:100px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" >'+obj[i].DEUDA_TOTAL+'</td>';
					html+='<td align="center" style="width:100px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" >'+obj[i].PAGO+'</td>';
					html+='<td align="center" style="width:100px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" >'+obj[i].PORCEN+'%</td>';
				html+='</tr>';
				xcategories.push(obj[i].TELEOPERADOR);
				xmontos.push( parseFloat(obj[i].DEUDA_TOTAL) );
				xpagos.push( parseFloat(obj[i].PAGO) );
			}
			$('#table_ranking_pago').html(html);
			$('#table_ranking_pago tr:gt(0)').hover(function(){ $(this).addClass('ui-state-hover'); },function(){ $(this).removeClass('ui-state-hover'); });
			$('#table_ranking_pago tr:gt(0)').click(function( ){
					$(this).addClass('ui-state-highlight').siblings().removeClass('ui-state-highlight');
				});
				
			
			var chartPago = new Highcharts.Chart({
													chart : { renderTo : 'layer_chart_ranking_pago', defaultSeriesType : 'area' },
													title : { text : 'Ranking de Pagos' },
													xAxis : { categories : xcategories },
													yAxis : { min : 0, title : { text : 'Montos' } },
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
													series : [ { name : 'Montos', data : xmontos }, { name : 'Pagos', data : xpagos } ]
												});
				
				
		}, function ( ) {
				$('#table_ranking_pago').html(templates.IMGloadingContentTable());
			} );
	
}
load_ranking_estado = function ( ) {
	var idcartera=$('#tbRKCartera_ranking_estado').find(':checked').map(function(){return this.value;}).get().join(",");
	var anio = $('#cbAnio_estado').val();
	var mes = $('#cbMes_estado').val();
	var diai = $('#cbDiaI_estado').val();
	var diaf = $('#cbDiaF_estado').val();
	if(idcartera==''){alert("Seleccione Cartera");return false;}
	RankingDAO.Ranking.Estado( idcartera, anio, mes, diai, diaf, function ( obj ) {
			var data = new Array();
			var indices = new Array();
			var teleoperador = obj[0].TELEOPERADOR;
			indices.push(teleoperador);
			data[teleoperador] = new Array();
			for( i=0;i<obj.length;i++ ) {
				var datos = eval( obj[i] );
				if( teleoperador == obj[i].TELEOPERADOR ) {
					var estados = new Array();
					for( index in datos ) {
						if( index != 'TELEOPERADOR' && index != 'CODIGO_TELEOPERADOR' ) {
							estados.push(datos[index]);
						}
					}
					data[obj[i].TELEOPERADOR].push(estados);
				}else{
					teleoperador = obj[i].TELEOPERADOR;
					indices.push(teleoperador);
					data[teleoperador] = new Array();
					var estados = new Array();
					for( index in datos ) {
						if( index != 'TELEOPERADOR' && index != 'CODIGO_TELEOPERADOR' ) {
							estados.push(datos[index]);
						}
					}
					data[obj[i].TELEOPERADOR].push(estados);
				}
			}
			
			var html='';
			var cabeceras = eval(obj[0]);
			html+='<tr class="ui-state-default">';
				//html+='<td align="center">&nbsp;</td>';
			for( index in cabeceras ) {
				if( index!='CODIGO_TELEOPERADOR' ) {
					html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >'+index+'</td>';
				}
			}
			html+='</tr>';
			for( k=0;k<indices.length;k++ ) {
					if( k != ( indices.length -1 ) ) {
					html+='<tr class="ui-widget-content">';
						//html+='<td style="width:250px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" align="center" rowspan="'+(data[indices[k]].length-1)+'" >'()+'</td>';
						html+='<td style="width:250px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" align="center" rowspan="'+(data[indices[k]].length-1)+'" >'+indices[k]+'</td>';
					}else{
					html+='<tr class="ui-widget-content">';
						html+='<td style="width:250px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" align="center" rowspan="'+(data[indices[k]].length-2)+'" >'+indices[k]+'</td>';
					}
					
					for( i=0;i<data[indices[k]].length;i++ ) {
//						alert(data[indices[k]][i]);
						if( i!=0 ) {
							html+='<tr>';
						}
						if(  k != ( indices.length -1 ) ) {
							
							if( i == data[indices[k]].length -1  ) {
								html+='<td colspan="2" align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;"> TOTAL '+indices[k]+' </td>';
								for( j=1;j<data[indices[k]][i].length;j++ ) {
									html+='<td align="center" style="width:100px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" >'+data[indices[k]][i][j]+'</td>';
								}
							}else{
								for( j=0;j<data[indices[k]][i].length;j++ ) {
									html+='<td align="center" style="width:100px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" >'+data[indices[k]][i][j]+'</td>';
								}
							}
								
						}else{
							
							if( i == data[indices[k]].length -1  ) {
								html+='<td colspan="2" align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;"> TOTALES </td>';
								for( j=1;j<data[indices[k]][i].length;j++ ) {
									html+='<td align="center" style="width:100px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" >'+data[indices[k]][i][j]+'</td>';
								}
							}else if( i == data[indices[k]].length -2 ){
								html+='<td colspan="2" align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;"> TOTAL '+indices[k]+' </td>';
								for( j=1;j<data[indices[k]][i].length;j++ ) {
									html+='<td align="center" style="width:100px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" >'+data[indices[k]][i][j]+'</td>';
								}
							}else{
								for( j=0;j<data[indices[k]][i].length;j++ ) {
									html+='<td align="center" style="width:100px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" >'+data[indices[k]][i][j]+'</td>';
								}
							}
							
						}
						
						html+='</tr>';
					}
			}
			//alert(html);
			$('#table_ranking_estado').html(html);
			$('#table_ranking_estado tr:gt(0)').hover(function(){ $(this).addClass('ui-state-hover'); },function(){ $(this).removeClass('ui-state-hover'); });
			$('#table_ranking_estado tr:gt(0)').click(function(){
					$(this).addClass('ui-state-highlight').siblings().removeClass('ui-state-highlight');
				});
		}, function ( ) {
				$('#table_ranking_estado').html(templates.IMGloadingContentTable());
			} );
	
}
load_ranking_estado_rpte = function ( ) {
	var idcartera=$('#tbRKCartera_ranking_estado').find(':checked').map(function(){return this.value;}).get().join(",");
	var anio = $('#cbAnio_estado').val();
	var mes = $('#cbMes_estado').val();
	var diai = $('#cbDiaI_estado').val();
	var diaf = $('#cbDiaF_estado').val();
	if(idcartera==''){alert("Seleccione Cartera");return false;}
	window.location.href="../rpt/excel/ranking_estado.php?cartera="+idcartera+"&anio="+anio+"&mes="+mes+"&diai="+diai+"&diaf="+diaf;
}
load_ranking_abonado_llamada = function ( ) {
	var idcartera = $('#tbRKCartera_ranking_abonado_llamadas').find(':checked').map(function(){return this.value;}).get().join(",");
	var anio = $('#cbAnio_abonado_llamada').val();
	var mes = $('#cbMes_abonado_llamada').val();
	var diai = $('#cbDiaI_abonado_llamada').val();
	var diaf = $('#cbDiaF_abonado_llamada').val();
	if(idcartera==''){alert("Seleccione Cartera");return false;}
	RankingDAO.Ranking.Abonado_Llamada( idcartera, anio, mes, diai, diaf, function ( obj ) {
			var html = '';
			for( i=0;i<obj.length;i++ ) {
				
				var data = eval(obj[i]);
				if( i==0 ) {
					html+='<tr class="ui-state-default">';
					var fechas = new Array();
					for( index in data ) {
						if( index == 'TELEOPERADOR' ) {
							html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;"></td>';
						}else{
							fechas.push(index);
						}
					}
					for( j=0;j<(fechas.length/2);j++ ) {
						var campo = fechas[j].split("_");
						html+='<td align="center" colspan="2" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;">'+campo[1]+'</td>';
					}
					html+='</tr>';
					html+='<tr class="ui-state-default">';
					for( index in data ) {
						if( index == 'TELEOPERADOR' ) {
							html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+index+'</td>';
						}else{
							var campo = index.split("_");
							html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+campo[0]+'</td>';
						}
					}
					html+='</tr>';
					
				}
				
				if( i == ( obj.length - 1 ) ) {
					html+='<tr class="ui-state-default">';
					for( index in data ) {
						if( index == 'TELEOPERADOR' ) {
							html+='<td align="center" style="width:300px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">TOTALES</td>';
						}else{
							html+='<td align="center" style="width:100px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
						}
					}
					html+='</tr>';
				}else{
					html+='<tr class="ui-widget-content">';
					for( index in data ) {
						html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
					}
					html+='</tr>';
				}
			}
			
			$('#table_ranking_abonado_llamada').html(html);
			$('#table_ranking_abonado_llamada tr:gt(1)').hover(function(){ $(this).addClass('ui-state-hover'); },function(){ $(this).removeClass('ui-state-hover'); });
			$('#table_ranking_abonado_llamada tr:gt(1)').click(function(){
					$(this).addClass('ui-state-highlight').siblings().removeClass('ui-state-highlight');
				});
			
		},function ( ) {
				$('#table_ranking_abonado_llamada').html(templates.IMGloadingContentTable());
			});
	
}
load_ranking_abonado_llamada_rpte = function ( ) {
	var idcartera = $('#tbRKCartera_ranking_abonado_llamadas').find(':checked').map(function(){return this.value;}).get().join(",");
	var anio = $('#cbAnio_abonado_llamada').val();
	var mes = $('#cbMes_abonado_llamada').val();
	var diai = $('#cbDiaI_abonado_llamada').val();
	var diaf = $('#cbDiaF_abonado_llamada').val();
	if(idcartera==''){alert("Seleccione Cartera");return false;}
	window.location.href="../rpt/excel/ranking_abonado_llamada.php?cartera="+idcartera+"&anio="+anio+"&mes="+mes+"&diai="+diai+"&diaf="+diaf;
	}
load_ranking_llamada_hora = function ( ) {
	var idcartera = $('#tbRKCartera_ranking_llamadas_hora').find(':checked').map(function(){return this.value;}).get().join(",");
	var idservicio = $('#hdCodServicio').val();
	var fecha_inicio = $('#txtRKFechaInicio_llamada_hora').val();
	var fecha_fin = $('#txtRKFechaFin_llamada_hora').val();
	if(idcartera==''){alert("Seleccione Cartera");return false;}
	if(fecha_inicio==''){alert("Seleccion Fecha Inicio");return false;}
	if(fecha_fin==''){alert("Seleccion Fecha Fin");return false;}
	RankingDAO.Ranking.Llamada_Hora( idservicio ,idcartera, fecha_inicio, fecha_fin, function ( objectJSON ) {
			var html = '';
			var carga = objectJSON.carga; 
			var obj = objectJSON.data;
			for( i=0;i<obj.length;i++ ) {
				
				var data = eval(obj[i]);
				if( i==0 ) {
					html+='<tr class="ui-state-default">';
					var fechas = new Array();
					for( index in data ) {
						if( index == 'TELEOPERADOR' ) {
							html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;"></td>';
						}else{
							fechas.push(index);
						}
					}
					
					for( j=0;j<fechas.length;j=j+carga.length ) {
						var campo = fechas[j].split("_");
						html+='<td align="center" colspan="'+carga.length+'" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;">'+campo[1]+'</td>';
					}
					html+='</tr>';
					html+='<tr class="ui-state-default">';
					for( index in data ) {
						if( index == 'TELEOPERADOR' ) {
							html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+index+'</td>';
						}else{
							var campo = index.split("_");
							html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+campo[0]+'</td>';
						}
					}
					html+='</tr>';
					
				}
				
				if( i == ( obj.length - 1 ) ) {
					html+='<tr class="ui-state-default">';
					for( index in data ) {
						if( index == 'TELEOPERADOR' ) {
							html+='<td align="center" style="width:300px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">TOTALES</td>';
						}else{
							html+='<td align="center" style="width:100px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
						}
					}
					html+='</tr>';
				}else{
					html+='<tr class="ui-widget-content">';
					for( index in data ) {
						html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
					}
					html+='</tr>';
				}
			}
			
			$('#table_ranking_llamada_hora').html(html);
			$('#table_ranking_llamada_hora tr:gt(1)').hover(function(){ $(this).addClass('ui-state-hover'); },function(){ $(this).removeClass('ui-state-hover'); });
			$('#table_ranking_llamada_hora tr:gt(1)').click(function(){
					$(this).addClass('ui-state-highlight').siblings().removeClass('ui-state-highlight');
				});
			
		}, function ( ) {
				$('#table_ranking_llamada_hora').html(templates.IMGloadingContentTable());
			});
	
}
load_ranking_llamada_hora_rpte = function ( ) {
	var idcartera = $('#tbRKCartera_ranking_llamadas_hora').find(':checked').map(function(){return this.value;}).get().join(",");
	var idservicio = $('#hdCodServicio').val();
	var fecha_inicio = $('#txtRKFechaInicio_llamada_hora').val();
	var fecha_fin = $('#txtRKFechaFin_llamada_hora').val();
	if(idcartera==''){alert("Seleccione Cartera");return false;}
	if(fecha_inicio==''){alert("Seleccion Fecha Inicio");return false;}
	if(fecha_fin==''){alert("Seleccion Fecha Fin");return false;}
	window.location.href="../rpt/excel/ranking_llamada_hora.php?servicio="+idservicio+"&cartera="+idcartera+"&fecha_inicio="+fecha_inicio+"&fecha_fin="+fecha_fin;
	}
load_ranking_llamada_hora_detalle = function ( ) {
	var idservicio = $('#hdCodServicio').val();
	var idcartera = $('#tbRKCartera_ranking_llamadas_hora_detalle').find(':checked').map(function(){return this.value;}).get().join(",");
	var anio = $('#cbAnio_llamada_hora_detalle').val();
	var mes = $('#cbMes_llamada_hora_detalle').val();
	var diai = $('#cbDiaI_llamada_hora_detalle').val();
	var diaf = $('#cbDiaF_llamada_hora_detalle').val();
	if(idcartera==''){alert("Seleccione Cartera");return false;}
	RankingDAO.Ranking.Llamada_Hora_Detalle( idservicio, idcartera, anio, mes, diai, diaf, function ( object ) {
			var carga = object.carga;
			var obj = object.data;
			var data = new Array();
			var indices = new Array();
			var teleoperador = obj[0].TELEOPERADOR;
			indices.push(teleoperador);
			data[teleoperador] = new Array();
			for( i=0;i<obj.length;i++ ) {
				var datos = eval( obj[i] );
				if( teleoperador == obj[i].TELEOPERADOR ) {
					var estados = new Array();
					for( index in datos ) {
						if( index != 'TELEOPERADOR' && index != 'CODIGO_TELEOPERADOR' ) {
							estados.push(datos[index]);
						}
					}
					data[obj[i].TELEOPERADOR].push(estados);
				}else{
					teleoperador = obj[i].TELEOPERADOR;
					indices.push(teleoperador);
					data[teleoperador] = new Array();
					var estados = new Array();
					for( index in datos ) {
						if( index != 'TELEOPERADOR' && index != 'CODIGO_TELEOPERADOR' ) {
							estados.push(datos[index]);
						}
					}
					data[obj[i].TELEOPERADOR].push(estados);
				}
			}
			
			var html='';
			var cabeceras = eval(obj[0]);
			var cabereasTop = new Array();
			html+='<tr class="ui-state-default">';
				html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;">&nbsp;</td><td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;">&nbsp;</td>';
			for( index in cabeceras ) {
				if( index != 'TELEOPERADOR' && index != 'HORA' ) {
					cabereasTop.push(index);
				}
			}
			for( n=0;n<cabereasTop.length;n=n+carga.length ) {
				var campo = cabereasTop[n].split("_"); 
				html+='<td align="center" colspan="'+carga.length+'" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >'+campo[1]+'</td>'; 
			}
			html+='</tr>';
			html+='<tr class="ui-state-default">';
				//html+='<td align="center">&nbsp;</td>';
			for( index in cabeceras ) {
				if( index!='CODIGO_TELEOPERADOR' ) {
					var campo = index.split("_");
					html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+campo[0]+'</td>';
				}
			}
			html+='</tr>';
			for( k=0;k<indices.length;k++ ) {
					if( k != ( indices.length -1 ) ) {
					html+='<tr class="ui-widget-content">';
						//html+='<td style="width:250px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" align="center" rowspan="'+(data[indices[k]].length-1)+'" >'()+'</td>';
						html+='<td style="width:250px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" align="center" rowspan="'+(data[indices[k]].length-1)+'" >'+indices[k]+'</td>';
					}else{
					html+='<tr class="ui-widget-content">';
						html+='<td style="width:250px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" align="center" rowspan="'+(data[indices[k]].length-2)+'" >'+indices[k]+'</td>';
					}
					
					for( i=0;i<data[indices[k]].length;i++ ) {
//						alert(data[indices[k]][i]);
						if( i!=0 ) {
							html+='<tr>';
						}
						if(  k != ( indices.length -1 ) ) {
							
							if( i == data[indices[k]].length -1  ) {
								html+='<td colspan="2" align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;"> TOTAL '+indices[k]+' </td>';
								for( j=1;j<data[indices[k]][i].length;j++ ) {
									html+='<td align="center" style="width:100px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" >'+data[indices[k]][i][j]+'</td>';
								}
							}else{
								for( j=0;j<data[indices[k]][i].length;j++ ) {
									html+='<td align="center" style="width:100px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" >'+data[indices[k]][i][j]+'</td>';
								}
							}
								
						}else{
							
							if( i == data[indices[k]].length -1  ) {
								html+='<td colspan="2" align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;"> TOTALES </td>';
								for( j=1;j<data[indices[k]][i].length;j++ ) {
									html+='<td align="center" style="width:100px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" >'+data[indices[k]][i][j]+'</td>';
								}
							}else if( i == data[indices[k]].length -2 ){
								html+='<td colspan="2" align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;"> TOTAL '+indices[k]+' </td>';
								for( j=1;j<data[indices[k]][i].length;j++ ) {
									html+='<td align="center" style="width:100px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" >'+data[indices[k]][i][j]+'</td>';
								}
							}else{
								for( j=0;j<data[indices[k]][i].length;j++ ) {
									html+='<td align="center" style="width:100px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" >'+data[indices[k]][i][j]+'</td>';
								}
							}
							
						}
						
						html+='</tr>';
					}
			}
			//alert(html);
			$('#table_ranking_llamada_hora_detalle').html(html);
			$('#table_ranking_llamada_hora_detalle tr:gt(0)').hover(function(){ $(this).addClass('ui-state-hover'); },function(){ $(this).removeClass('ui-state-hover'); });
			$('#table_ranking_llamada_hora_detalle tr:gt(0)').click(function(){
					$(this).addClass('ui-state-highlight').siblings().removeClass('ui-state-highlight');
				});
		}, function ( ) {
				$('#table_ranking_llamada_hora_detalle').html(templates.IMGloadingContentTable());
			} );
	
}
load_ranking_llamada_hora_detalle_rpte = function ( ) {
	var idservicio = $('#hdCodServicio').val();
	var idcartera = $('#tbRKCartera_ranking_llamadas_hora_detalle').find(':checked').map(function(){return this.value;}).get().join(",");
	var anio = $('#cbAnio_llamada_hora_detalle').val();
	var mes = $('#cbMes_llamada_hora_detalle').val();
	var diai = $('#cbDiaI_llamada_hora_detalle').val();
	var diaf = $('#cbDiaF_llamada_hora_detalle').val();
	if(idcartera==''){alert("Seleccione Cartera");return false;}
	window.location.href="../rpt/excel/ranking_llamada_hora_detalle.php?servicio="+idservicio+"&cartera="+idcartera+"&anio="+anio+"&mes="+mes+"&diai="+diai+"&diaf="+diaf;
	}
load_ranking_visita = function ( ) {
	var idcartera = $('#tbRKCartera_ranking_visita').find(':checked').map(function(){return this.value}).get().join(",");
	var anio = $('#cbAnio_visita').val();
	var mes = $('#cbMes_visita').val();
	var diai = $('#cbDiaI_visita').val();
	var diaf = $('#cbDiaF_visita').val();
	if(idcartera==''){alert("Seleccione Cartera");return false;}
	RankingDAO.Ranking.visita( idcartera, anio, mes, diai, diaf, function ( obj ) {
			var html = '';
			for( i=0;i<obj.length;i++ ) {
				
				var data = eval(obj[i]);
				if( i==0 ) {
					html+='<tr class="ui-state-default">';
					for( index in data ) {
						html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;">'+index+'</td>';
					}
					html+='</tr>';
				}
				
				if( i == ( obj.length - 1 ) ) {
					html+='<tr class="ui-state-default">';
					for( index in data ) {
						if( index == 'NOTIFICADOR' ) {
							html+='<td align="center" style="width:300px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">TOTALES</td>';
						}else{
							html+='<td align="center" style="width:100px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
						}
					}
					html+='</tr>';
				}else{
					html+='<tr class="ui-widget-content">';
					for( index in data ) {
						html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
					}
					html+='</tr>';
				}
			}
			
			$('#table_ranking_visita').html(html);
			$('#table_ranking_visita tr:gt(0)').hover(function(){ $(this).addClass('ui-state-hover'); },function(){ $(this).removeClass('ui-state-hover'); });
			$('#table_ranking_visita tr:gt(0)').click(function(){
					$(this).addClass('ui-state-highlight').siblings().removeClass('ui-state-highlight');
				});
			
		},function ( ) {
				$('#table_ranking_visita').html(templates.IMGloadingContentTable());
			});
	
}
load_ranking_visita_rpte = function ( ) {
	var idcartera = $('#tbRKCartera_ranking_visita').find(':checked').map(function(){return this.value}).get().join(",");
	var anio = $('#cbAnio_visita').val();
	var mes = $('#cbMes_visita').val();
	var diai = $('#cbDiaI_visita').val();
	var diaf = $('#cbDiaF_visita').val();
	if(idcartera==''){alert("Seleccione Cartera");return false;}
	window.location.href="../rpt/excel/ranking_visita.php?cartera="+idcartera+"&anio="+anio+"&mes="+mes+"&diai="+diai+"&diaf="+diaf;
	}
load_ranking_semaforo = function ( ) {
	var idcartera = $('#tbRKCartera_ranking_semaforo').find(':checked').map(function(){return this.value}).get().join(",");
	var fecha_inicio = $('#txtRKFechaInicio_semaforo').val();
	var fecha_fin = $('#txtRKFechaFin_semaforo').val();
	if(idcartera==''){alert("Seleccione Cartera");return false;}
	if(fecha_inicio==''){alert("Seleccion Fecha Inicio");return false;}
	if(fecha_fin==''){alert("Seleccion Fecha Fin");return false;}
	RankingDAO.Ranking.semaforo( idcartera, fecha_inicio, fecha_fin, function ( obj ) {
			var html = '';
			for( i=0;i<obj.length;i++ ) {
				
				var data = eval(obj[i]);
				if( i==0 ) {
					html+='<tr class="ui-state-default">';
					for( index in data ) {
						html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;">'+index+'</td>';
					}
					html+='</tr>';
				}
				
				if( i == ( obj.length - 1 ) ) {
					html+='<tr class="ui-state-default">';
					for( index in data ) {
						if( index == 'TELEOPERADOR' ) {
							html+='<td align="center" style="width:300px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">TOTALES</td>';
						}else{
							html+='<td align="center" style="width:100px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
						}
					}
					html+='</tr>';
				}else{
					html+='<tr class="ui-widget-content">';
					for( index in data ) {
						if( index == 'TELEOPERADOR' || index == 'TOTAL_LLAMADAS' ) {
							html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
						}else{
							var llamadas = parseFloat( data[index] );
							var backgroun_color = '';
							if( llamadas<20 ) {
								backgroun_color = '#FF0000';
							}else if( llamadas>20 && llamadas<25 ) {
								backgroun_color = '#FFFF00';
							}else{
								backgroun_color = '#00B050';
							}
							html+='<td align="center" style="background-color:'+backgroun_color+';white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
						}
						
					}
					html+='</tr>';
				}
			}
			
			$('#table_ranking_semaforo').html(html);
			$('#table_ranking_semaforo tr:gt(0)').hover(function(){ $(this).addClass('ui-state-hover'); },function(){ $(this).removeClass('ui-state-hover'); });
			$('#table_ranking_semaforo tr:gt(0)').click(function(){
					$(this).addClass('ui-state-highlight').siblings().removeClass('ui-state-highlight');
				});
			
		}, function ( ) {
				$('#table_ranking_semaforo').html(templates.IMGloadingContentTable());
			});
	
}
load_ranking_semaforo_rpte = function ( ) {
	var idcartera = $('#tbRKCartera_ranking_semaforo').find(':checked').map(function(){return this.value}).get().join(",");
	var fecha_inicio = $('#txtRKFechaInicio_semaforo').val();
	var fecha_fin = $('#txtRKFechaFin_semaforo').val();
	if(idcartera==''){alert("Seleccione Cartera");return false;}
	if(fecha_inicio==''){alert("Seleccion Fecha Inicio");return false;}
	if(fecha_fin==''){alert("Seleccion Fecha Fin");return false;}
	window.location.href="../rpt/excel/ranking_semaforo.php?cartera="+idcartera+"&fecha_inicio="+fecha_inicio+"&fecha_fin="+fecha_fin;
	}
load_ranking_compromiso_pago = function ( ) {
	var idcartera = $('#tbRKCartera_ranking_cp').find(':checked').map(function(){return this.value}).get().join(",");
	var anio = $('#cbAnio_cp').val();
	var mes = $('#cbMes_cp').val();
	var diai = $('#cbDiaI_cp').val();
	var diaf = $('#cbDiaF_cp').val();
	if(idcartera==''){alert("Seleccione Cartera");return false;}
	RankingDAO.Ranking.compromiso_pago( idcartera, anio, mes, diai, diaf, function ( obj ) {
			var html = '';
			var xcategories = new Array();
			var xindices = new Array();
			var xseries = new Array();
			var xteleoperador = '';
			for( i=0;i<obj.length;i++ ) {
				
				var data = eval(obj[i]);
				if( i==0 ) {
					html+='<tr class="ui-state-default">';
					for( index in data ) {
						html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;">'+index+'</td>';
						if( index != 'TELEOPERADOR' && index!='TOTAL_CP' ) {
							xcategories.push(index);
						}
					}
					html+='</tr>';
				}
				
				if( i == ( obj.length - 1 ) ) {
					html+='<tr class="ui-state-default">';
					for( index in data ) {
						if( index == 'TELEOPERADOR' ) {
							html+='<td align="center" style="width:300px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">TOTALES</td>';
						}else{
							html+='<td align="center" style="width:100px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
						}
					}
					html+='</tr>';
				}else{
					html+='<tr class="ui-widget-content">';
					for( index in data ) {
						html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
						if( index == 'TELEOPERADOR' ) {
							xindices.push(data[index]);
							xteleoperador = data[index];
							xseries[data[index]]=new Array();
						}else if( index == 'TOTAL_CP' ) {
							
						}else{
							xseries[xteleoperador].push(data[index]);
						}
					}
					html+='</tr>';
				}
			}
			
			$('#table_ranking_cp').html(html);
			$('#table_ranking_cp tr:gt(0)').hover(function(){ $(this).addClass('ui-state-hover'); },function(){ $(this).removeClass('ui-state-hover'); });
			$('#table_ranking_cp tr:gt(0)').click(function(){
					$(this).addClass('ui-state-highlight').siblings().removeClass('ui-state-highlight');
				});
			var text_series = '';
			text_series = '['+$.map( xindices, function ( v, i ) {
				return '{ "name":"'+v+'","data":['+xseries[v].join(",")+']}';
			}).join(",")+']';
			
			new Highcharts.Chart({
								  chart: {
									 renderTo: 'layer_chart_cp',
									 defaultSeriesType: 'line'
								  },
								  title: {text: 'Ranking Compromiso de Pago'},
								  xAxis: { categories: xcategories },
								  yAxis: {
									 title: {
										text: 'Cantidad de Promesas'
									 }
								  },
								  tooltip: {
									 enabled: false,
									 formatter: function() {
										return '<b>'+ this.series.name +'</b><br/>'+
										   this.x +': '+ this.y ;
									 }
								  },
								  plotOptions: {
									 line: {
										dataLabels: {
										   enabled: true
										},
										enableMouseTracking: false
									 }
								  },
								  series: $.parseJSON(text_series)
							   });

			
		},function ( ) {
				$('#table_ranking_cp').html(templates.IMGloadingContentTable());
			});
	
}

load_ranking_compromiso_pago_rpte = function ( ) {
	var idcartera = $('#tbRKCartera_ranking_cp').find(':checked').map(function(){return this.value}).get().join(",");
	var anio = $('#cbAnio_cp').val();
	var mes = $('#cbMes_cp').val();
	var diai = $('#cbDiaI_cp').val();
	var diaf = $('#cbDiaF_cp').val();
	if(idcartera==''){alert("Seleccione Cartera");return false;}
	window.location.href="../rpt/excel/ranking_cp.php?cartera="+idcartera+"&anio="+anio+"&mes="+mes+"&diai="+diai+"&diaf="+diaf;
	}
	
load_ranking_carga_fecha = function ( ) {
	
	var idcartera = $('#tbRKCartera_ranking_carga_fecha').find(':checked').map(function(){return this.value}).get().join(",");
	var tipo = $('#cbRKTipo_carga_fecha').val();
	var anio = $('#cbAnio_carga_fecha').val();
	var mes = $('#cbMes_carga_fecha').val();
	var diai = $('#cbDiaI_carga_fecha').val();
	var diaf = $('#cbDiaF_carga_fecha').val();
	if( idcartera=='' ){
		alert("Seleccione Cartera");
		return false;
	}
	
	RankingDAO.Ranking.carga_fecha( idcartera, anio, mes, diai, diaf, tipo, 
		function ( obj ) {
			
			var tele = new Array();
			var html = '';
			var last_tel = "";
			for( i=0;i<obj.length;i++ ) {
				
				if( !$.isArray( tele[ obj[i].TELEOPERADOR ] ) ) {
					tele[ obj[i].TELEOPERADOR ] = new Array();
				}
				(tele[ obj[i].TELEOPERADOR ]).push( obj[i] );
				last_tel = obj[i].TELEOPERADOR ;
			}
			
			if( obj.length > 0 ) {
				
				var header = eval( obj[0] );
				html+='<tr class="ui-state-default" >';
					html+='<td align="center" style="width:20px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >&nbsp;</td>';
				for( index in header ) {
					html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >'+index+'</td>';
				}
				html+='</tr>';
			}
			
			var count = 0;
			for( index in tele ) {
				count++;
				
				var rowspan = 0;
				var rowspan2 = 0;
				if( index == last_tel ) {
					rowspan = tele[index].length - 1 ;
					rowspan2 = tele[index].length - 2  ;
				}else{
					rowspan = tele[index].length ;
					rowspan2 = tele[index].length - 1 ;
				}
				
				html+='<tr class="ui-widget-content">';
					html+='<td rowspan="'+(rowspan )+'" align="center" class="ui-state-default">'+(count)+'</td>';
					html+='<td rowspan="'+( rowspan2 )+'" align="center" valign="middle">'+index+'</td>';
					for( i=0;i<tele[index].length;i++ ) {
						
						var data = eval( tele[index][i] );
						
						if( index == last_tel ) {
						
							if( i == (tele[index].length - 1) ) {
								html+='</tr>';
								html+='<tr class="ui-widget-content">';
									html+='<td align="center" class="ui-state-default;">&nbsp;</td>';
							}else if( i>0 ){
								html+='</tr>';
								html+='<tr class="ui-widget-content">';
							}
						
						}else{
							if( i>0 ) {
								html+='</tr>';
								html+='<tr class="ui-widget-content">';
							}
						}
						
						if( i == (tele[index].length - 1) ) {
							
							if( index == last_tel ) {
								
								for( key in data ) {
									if( key=='TELEOPERADOR' ) {
										html+='<td class="ui-state-default" colspan="2" align="center">TOTALES</td>';
									}else if( key=='CARGA' ){

									}else{
										html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[key]+'</td>';
									}
								}
								
							}else{
							
								for( key in data ) {
									if( key=='TELEOPERADOR' ) {
										html+='<td class="ui-state-default" colspan="2" align="center">TOTAL '+data[key]+'</td>';
									}else if( key=='CARGA' ){
	
									}else{
										html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[key]+'</td>';
									}
								}
							
							}
							
						}else if( i == (tele[index].length - 2) ){
							
							if( index == last_tel ) {
								
								for( key in data ) {
								if( key=='TELEOPERADOR' ) {
									html+='<td class="ui-state-default" colspan="2" align="center">TOTAL '+data[key]+'</td>';
								}else if( key=='CARGA' ){

								}else{
									html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[key]+'</td>';
								}
							}
								
							}else{
								for( key in data ) {
									if( key!='TELEOPERADOR' ) {
										html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[key]+'</td>';
									}
								}
							}
							
						}else{
						
							for( key in data ) {
								if( key!='TELEOPERADOR' ) {
									html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[key]+'</td>';
								}
							}
						
						}
						
					}
					
				html+='</tr>';
			}
			
			$('#table_ranking_carga_fecha').html( html );
			$('#table_ranking_carga_fecha tr:gt(0)').hover(function(){ $(this).addClass('ui-state-hover'); },function(){ $(this).removeClass('ui-state-hover'); });
			$('#table_ranking_carga_fecha tr:gt(0)').click(function(){
					$(this).addClass('ui-state-highlight').siblings().removeClass('ui-state-highlight');
				});
			
		},
		function ( ) {
			$('#table_ranking_carga_fecha').html(templates.IMGloadingContentTable());
		}
	);
	
}

load_ranking_carga_fecha_rpte = function ( ) {
	
	var idcartera = $('#tbRKCartera_ranking_carga_fecha').find(':checked').map(function(){return this.value}).get().join(",");
	var tipo = $('#cbRKTipo_carga_fecha').val();
	var anio = $('#cbAnio_carga_fecha').val();
	var mes = $('#cbMes_carga_fecha').val();
	var diai = $('#cbDiaI_carga_fecha').val();
	var diaf = $('#cbDiaF_carga_fecha').val();
	if( idcartera=='' ){
		alert("Seleccione Cartera");
		return false;
	}
	
	window.location.href="../rpt/excel/ranking_carga_fecha.php?cartera="+idcartera+"&anio="+anio+"&mes="+mes+"&diai="+diai+"&diaf="+diaf+"&tipo="+tipo; 
	
}

load_ranking_semaforo_fija = function ( ) {
	var campania=$('#cbRKCampania_fija_semaforo').val();
	var idcartera = $('#tbRKCartera_fija_semaforo').find(':checked').map(function( ) { return this.value; }).get().join(",");
	var fecha_inicio = $('#txtRKFecha_inicio_fija_semaforo').val();
	var fecha_fin = $('#txtRKFecha_fin_fija_semaforo').val();
	var meta = $('#txtRK_meta_fija_semaforo').val();
	var idcarga = $('#tbRK_Carga_Cartera_fija_semaforo').find(':checked').map(function( ) { return this.value; }).get().join(",");
	
	if( campania == 0 ) {
		alert("Seleccione Campa\xf1a");
		return false;
	}
	if( idcartera == '' ) {
		alert("Seleccione Cartera");
		return false;
	}
	if( idcarga == '' ) {
		alert("Seleccione Valores Carga");
		return false;
	}
	if( fecha_inicio == '' ) {
		alert("Ingrese Fecha Inicio");
		return false;
	}
	if( fecha_fin == '' ) {
		alert("Ingrese Fecha Fin");
		return false;
	}
	
	RankingDAO.Ranking.semaforo_formato_fija( idcartera, idcarga, fecha_inicio, fecha_fin, meta, function ( obj ) {
			var html = '';
			var data_chart = new Array();
						
			for( i=0;i<obj.Ini.length;i++ ) {
				
				var data = eval(obj.Ini[i]);
				if( i==0 ) {
					html+='<tr class="ui-state-default">';
					for( index in data ) {
						html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;">'+index+'</td>';
					}
					html+='</tr>';
				}
				
				if( i == ( obj.Ini.length - 1 ) ) {
					html+='<tr class="ui-state-default">';
					for( index in data ) {
						if( index == 'TELEOPERADOR' ) {
							html+='<td align="center" style="width:300px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">TOTALES</td>';
						}else{
							html+='<td align="center" style="width:100px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
						}
					}
					html+='</tr>';
				}else{
					html+='<tr class="ui-widget-content">';
					var data_t = new Array();
					for( index in data ) {
						if( index == 'TELEOPERADOR' || index == 'TOTAL_LLAMADAS' ) {
							data_t.push(data[index]);
							html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
						}else{
							var llamadas = parseFloat( data[index] );
							var backgroun_color = '';
							if( llamadas<=20 && llamadas > 0 ) {
								backgroun_color = '#FF0000';
							}else if( llamadas>20 && llamadas<25 ) {
								backgroun_color = '#FFFF00';
							}else if( llamadas>=25 ){
								backgroun_color = '#00B050';
							}else if( llamadas == 0 ) {
								backgroun_color = '';
							}
							html+='<td align="center" style="background-color:'+backgroun_color+';white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
						}
						
					}
					html+='</tr>';
					data_chart.push(data_t);
				}
			}
			
			/*$('#table_ranking_fija_semaforo').html(html);
			$('#table_ranking_fija_semaforo tr:gt(0)').hover(function(){ $(this).addClass('ui-state-hover'); },function(){ $(this).removeClass('ui-state-hover'); });
			$('#table_ranking_fija_semaforo tr:gt(0)').click(function(){
					$(this).addClass('ui-state-highlight').siblings().removeClass('ui-state-highlight');
				});*/
			
			//var html = '';
			html+='<tr><td colspan="11" style="height:20px;"></td></tr>';
			for( i=0;i<obj.Meta.length;i++ ) {
				var data = eval(obj.Meta[i]);
				html+='<tr class="ui-widget-content">';
				for( index in data ) {
					if( index == 'VALOR' ) {
						html+='<td class="ui-state-default" align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-left:1px solid #E0CFC2;">'+data[index]+'</td>';
					}else{
						html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
					}
				}
				html+='</tr>';
			}
					
			$('#table_ranking_fija_semaforo_objetivo').html(html);
			$('#table_ranking_fija_semaforo_objetivo tr:gt(0)').hover(function(){ $(this).addClass('ui-state-hover'); },function(){ $(this).removeClass('ui-state-hover'); });
			$('#table_ranking_fija_semaforo_objetivo tr:gt(0)').click(function(){
					$(this).addClass('ui-state-highlight').siblings().removeClass('ui-state-highlight');
				});
				
			var data = new google.visualization.DataTable();
			data.addColumn('string', 'TELEOPERADOR');
			data.addColumn('number', 'TOTAL');
			data.addRows(data_chart.length);
			for( i=0;i<data_chart.length;i++ ) {
				data.setValue(i,0,data_chart[i][0]);
				data.setValue(i,1,parseFloat(data_chart[i][1]));
			}
			
			var chart = new google.visualization.BarChart(document.getElementById('layer_chart_semaforo_fija'));
	        chart.draw(data, {width: 750, height: 400, title: 'TOTALES',
                          vAxis: {title: 'Teleoperador', titleTextStyle: {color: 'red'}}
                         });
			
		}, function ( ) {
				$('#table_ranking_fija_semaforo_objetivo').html(templates.IMGloadingContentTable());
			});
	
}
load_ranking_fija_compromiso_pago = function ( ) {
	var campania=$('#cbRKCampania_fija_cp').val();
	var idcartera = $('#tbRKCartera_fija_cp').find(':checked').map(function( ) { return this.value; }).get().join(",");
	var anio = $('#cbAnio_fija_cp').val();
	var mes = $('#cbMes_fija_cp').val();
	var diai = $('#cbDiaI_fija_cp').val();
	var diaf = $('#cbDiaF_fija_cp').val();
	var idcarga = $('#tbRK_Carga_Cartera_fija_cp').find(':checked').map(function( ) { return this.value; }).get().join(",");
	
	if( campania == 0 ) {
		alert("Seleccione Campa\xf1a");
		return false;
	}
	if( idcartera == '' ) {
		alert("Seleccione Cartera");
		return false;
	}
	if( idcarga == '' ) {
		alert("Seleccione Valores Carga");
		return false;
	}
	
	RankingDAO.Ranking.cp_dia_fija( idcartera, anio, mes, diai, diaf, idcarga, function ( obj ) {
			var html = '';
			for( i=0;i<obj.Ini.length;i++ ) {
				
				var data = eval(obj.Ini[i]);
				if( i==0 ) {
					html+='<tr class="ui-state-default">';
					for( index in data ) {
						html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;">'+index+'</td>';
					}
					html+='</tr>';
				}
				
				if( i == ( obj.Ini.length - 1 ) ) {
					html+='<tr class="ui-state-default">';
					for( index in data ) {
						if( index == 'TELEOPERADOR' ) {
							html+='<td align="center" style="width:300px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">TOTALES</td>';
						}else{
							html+='<td align="center" style="width:100px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
						}
					}
					html+='</tr>';
				}else{
					html+='<tr class="ui-widget-content">';
					for( index in data ) {
						html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
					}
					html+='</tr>';
				}
			}
			
			html+='<tr><td style="height:20px;"></td></tr>';				
			for( i=0;i<obj.MData.length;i++ ) {
				var data = eval(obj.MData[i]);
				if( i==0 ) {
					html+='<tr class="ui-state-default">';
					for( index in data ) {
						html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;">'+index+'</td>';
					}
					html+='</tr>';
				}
				
				html+='<tr class="ui-widget-content">';
				for( index in data ) {
					if( index == 'VALOR_EFECTIVO' ) {
						html+='<td class="ui-state-default" align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
					}else{
						html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
					}
				}
				html+='</tr>';
				
			}
			
			$('#table_ranking_fija_cp_dia').html(html);
			$('#table_ranking_fija_cp_dia tr:gt(0)').hover(function(){ $(this).addClass('ui-state-hover'); },function(){ $(this).removeClass('ui-state-hover'); });
			$('#table_ranking_fija_cp_dia tr:gt(0)').click(function(){
					$(this).addClass('ui-state-highlight').siblings().removeClass('ui-state-highlight');
				});
			
			/*var text_series = '';
			text_series = '['+$.map( xindices, function ( v, i ) {
				return '{ "name":"'+v+'","data":['+xseries[v].join(",")+']}';
			}).join(",")+']';
			
			new Highcharts.Chart({
								  chart: {
									 renderTo: 'layer_chart_cp',
									 defaultSeriesType: 'line'
								  },
								  title: {text: 'Ranking Compromiso de Pago'},
								  xAxis: { categories: xcategories },
								  yAxis: {
									 title: {
										text: 'Cantidad de Promesas'
									 }
								  },
								  tooltip: {
									 enabled: false,
									 formatter: function() {
										return '<b>'+ this.series.name +'</b><br/>'+
										   this.x +': '+ this.y ;
									 }
								  },
								  plotOptions: {
									 line: {
										dataLabels: {
										   enabled: true
										},
										enableMouseTracking: false
									 }
								  },
								  series: $.parseJSON(text_series)
							   });*/

			
		},function ( ) {
				$('#table_ranking_fija_cp_dia').html(templates.IMGloadingContentTable());
			});
	
}

load_ranking_fija_respuesta_gestion = function ( ) {
	var campania=$('#cbRKCampania_fija_rpt_gest').val();
	var idcartera = $('#tbRKCartera_fija_rpt_gest').find(':checked').map(function( ) { return this.value; }).get().join(",");
	var anio = $('#cbAnio_fija_rpt_gest').val();
	var mes = $('#cbMes_fija_rpt_gest').val();
	var diai = $('#cbDiaI_fija_rpt_gest').val();
	var diaf = $('#cbDiaF_fija_rpt_gest').val();
	
	if( campania == 0 ) {
		alert("Seleccione Campa\xf1a");
		return false;
	}
	if( idcartera == '' ) {
		alert("Seleccione Cartera");
		return false;
	}
	
	RankingDAO.Ranking.rpt_gestion( idcartera, anio, mes, diai, diaf, function ( obj ) {
			var html = '';
			var estados = new Array();
			var long_estados = new Array();
			var carga = obj[0].ESTADO;
			var rpt_gestion = obj[0].RESPUESTA_GESTION;
			var count_es = 0;
			estados[carga] = new Array();
			estados[carga][rpt_gestion] = new Array(); 
			for( i=0;i<obj.length;i++ ) {
				
				if( carga == obj[i].ESTADO ) {
					count_es++;
					var data = eval(obj[i]);
					if( rpt_gestion == obj[i].RESPUESTA_GESTION ) {
						
					}else{
						rpt_gestion = obj[i].RESPUESTA_GESTION;
						estados[carga][rpt_gestion] = new Array(); 
					}
					
				}else{
					long_estados[carga] = count_es;
					count_es=1;
					carga = obj[i].ESTADO;
					rpt_gestion = obj[i].RESPUESTA_GESTION;
					estados[carga] = new Array();
					estados[carga][rpt_gestion] = new Array(); 
				}
				var data_e = new Array();
				for( index in data ) {
					if( index != 'ESTADO' && index != 'RESPUESTA_GESTION' ) {
						data_e.push(data[index]);
					}
					if( i == 0 ) {
						html+='<td align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >'+index+'</td>';
					}
				}
				estados[carga][rpt_gestion].push(data_e);
			}
			
			long_estados[carga] = count_es;
			
			html='<tr class="ui-state-default" >'+html+'</tr>';
			
			for( i in estados ) {
				var count_c = 0;
				for( j in estados[i] ) {
					//var html_d = '';
					var cont_rg = 0;
					if( count_c == 0 ) {
						html+='<tr class="ui-widget-content"><td align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" rowspan="'+long_estados[i]+'" >'+i+'</td>';
					}else{
						html+='<tr class="ui-widget-content">';
					}
					for( k = 0; k<estados[i][j].length;k++ ) {
						//alert(estados[i][j][k]);
						if( cont_rg == 0 ) {
							html+='<td align="center" rowspan="'+estados[i][j].length+'" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" >'+j+'</td>';
						}else{
							html+='<tr class="ui-widget-content">';
						}
						for( p=0;p<estados[i][j][k].length;p++ ) {
							html += '<td align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" >'+estados[i][j][k][p]+'</td>';
						}
						html+='</tr>';
						cont_rg++;
						count_c++;
					}
					
				}
					
			}
			
			$('#table_ranking_fija_rpt_gest').html(html);
			
		}, function ( ) {
				$('#table_ranking_fija_rpt_gest').html(templates.IMGloadingContentTable());
			} );
		
}
loda_ranking_fija_contactabilidad_diario = function ( ) {
	var campania=$('#cbRKCampania_fija_estado').val();
	var idcartera = $('#tbRKCartera_fija_estado').find(':checked').map(function( ) { return this.value; }).get().join(",");
	var anio = $('#cbAnio_fija_estado').val();
	var mes = $('#cbMes_fija_estado').val();
	var diai = $('#cbDiaI_fija_estado').val();
	var diaf = $('#cbDiaF_fija_estado').val();
	
	if( campania == 0 ) {
		alert("Seleccione Campa\xf1a");
		return false;
	}
	if( idcartera == '' ) {
		alert("Seleccione Cartera");
		return false;
	}
	
	RankingDAO.Ranking.cont_diario( idcartera, anio, mes, diai, diaf, function ( obj ) {
			
			var html = '';
			html+='<tr class="ui-state-default"><td align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;">Cuenta de valor</td><td align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;">FECHA</td></tr>';
			for( i=0;i<obj.callIni.length;i++ ) {
				if( i == 0 ) {
					html += '<tr class="ui-state-default">';
						var data = eval(obj.callIni[i]);
						for( index in data ) {
							html += '<td align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;">'+index+'</td>';
						}
					html += '</tr>';
				}
				
				if( i == ( obj.callIni.length - 1 ) ) {
					html += '<tr class="ui-state-default">';
						var data = eval(obj.callIni[i]);
						for( index in data ) {
							if( index == 'VALOR' ) {
								html += '<td align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">Total General</td>';
							}else{
								html += '<td align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" >'+data[index]+'</td>';
							}
						}
					html += '</tr>';
				}else{
					html += '<tr class="ui-widget-content">';
						var data = eval(obj.callIni[i]);
						for( index in data ) {
							if( index == 'VALOR' ) {
								html += '<td class="ui-state-default" align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
							}else{
								html += '<td align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" >'+data[index]+'</td>';
							}
						}
					html += '</tr>';
				}
				
			}
			
			/*$('#table_ranking_fija_estado').html(html);
			$('#table_ranking_fija_estado tr:gt(0)').hover(function(){ $(this).addClass('ui-state-hover'); },function(){ $(this).removeClass('ui-state-hover'); });
			$('#table_ranking_fija_estado tr:gt(0)').click(function(){
					$(this).addClass('ui-state-highlight').siblings().removeClass('ui-state-highlight');
				});*/
				
			//var html = '';
			var chd = new Array();
			var chdl = new Array();
			var chxl = new Array();
			html+='<tr><td style="height:20px;"></td></tr>';
			html+='<tr class="ui-state-default"><td align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;">CALL</td><td align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;">FECHA</td></tr>';
			for( i=0;i<obj.callPor.length;i++ ) {
				if( i == 0 ) {
					html += '<tr class="ui-state-default">';
						var data = eval(obj.callPor[i]);
						for( index in data ) {
							html += '<td align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;">'+index+'</td>';
							if( index != 'VALOR' ) {
								chxl.push(index);
							}
						}
					html += '</tr>';
				}
				
				html += '<tr class="ui-widget-content">';
					var data = eval(obj.callPor[i]);
					var data_chd = new Array();
					for( index in data ) {
						if( index == 'VALOR' ) {
							html += '<td class="ui-state-default" align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
							chdl.push(data[index]);
						}else{
							data_chd.push(parseFloat(data[index]));
							
							html += '<td align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" >'+data[index]+'%</td>';
						}
					}
					chd.push(data_chd.join(","));
				html += '</tr>';
				
			}
			
			var chartgoogle = '<img src="https://chart.googleapis.com/chart?cht=lc&chd=t:'+chd.join("|")+'&chdl='+chdl.join("|")+'&chxt=x,y&chxl=0:|'+chxl.join("|")+'|1:|0|10|20|30|40|50|60|70|80|90|100&chs=500x200" />';
			
			$('#layer_chart_cont_diaria_fija').html(chartgoogle);
			
			$('#table_ranking_fija_estado_porcentaje').html(html);
			$('#table_ranking_fija_estado_porcentaje tr:gt(0)').hover(function(){ $(this).addClass('ui-state-hover'); },function(){ $(this).removeClass('ui-state-hover'); });
			$('#table_ranking_fija_estado_porcentaje tr:gt(0)').click(function(){
					$(this).addClass('ui-state-highlight').siblings().removeClass('ui-state-highlight');
				});
			
		}, function ( ) {
				$('#table_ranking_fija_estado_porcentaje').html(templates.IMGloadingContentTable());
			} );
	
}

load_ranking_fija_contactabilidad_hora = function ( ) {
	var campania=$('#cbRKCampania_fija_cont_hora').val();
	var idcartera = $('#tbRKCartera_fija_cont_hora').find(':checked').map(function( ) { return this.value; }).get().join(",");
	var fecha_inicio = $('#txtRKFecha_inicio_cont_hora').val();
	var fecha_fin = $('#txtRKFecha_fin_cont_hora').val();
	var xcategories = new Array();
	var xindices = new Array();
	var xdata_series = new Array();
	
	if( campania == 0 ) {
		alert("Seleccione Campa\xf1a");
		return false;
	}
	if( idcartera == '' ) {
		alert("Seleccione Cartera");
		return false;
	}
	if( fecha_inicio == '' ) {
		alert("Ingrese Fecha Inicio");
		return false;
	}
	if( fecha_fin == '' ) {
		alert("Ingrese Fecha Fin");
		return false;
	}
	
	RankingDAO.Ranking.cont_hora( idcartera, fecha_inicio, fecha_fin, function ( obj ) {
			var html = '';
			html+='<tr><td><td class="ui-state-default" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >FECHAS</td></tr>';
			for( i=0;i<obj.Ini.length;i++ ) {
				if( i == 0 ) {
					html+='<tr class="ui-state-default">';
						var data = eval(obj.Ini[i]);
						for( index in data ) {
							html+='<td align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >'+index+'</td>';
						}
					html+='</tr>';
				}
				
				if( i == (obj.Ini.length-1) ) {
					html+='<tr class="ui-state-default">';
					var data = eval(obj.Ini[i]);
					for( index in data ) {
						if( index == 'ESTADO' ) {
							html+='<td align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" >TOTAL GENERAL</td>';
						}else{
							html+='<td align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" >'+data[index]+'</td>';
						}
					}	
					html+='</tr>';
				}else{
					html+='<tr class="ui-widget-content">';
					var data = eval(obj.Ini[i]);
					for( index in data ) {
						html+='<td align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
					}	
					html+='</tr>';
				}
			}
			
			/*$('#table_ranking_fija_cont_hora').html(html);
			$('#table_ranking_fija_cont_hora tr:gt(0)').hover(function(){ $(this).addClass('ui-state-hover'); },function(){ $(this).removeClass('ui-state-hover'); });
			$('#table_ranking_fija_cont_hora tr:gt(0)').click(function(){
					$(this).addClass('ui-state-highlight').siblings().removeClass('ui-state-highlight');
				});
			
			var html = '';*/
			html+='<tr><td colspan="11" style="height:40px;" ></td></tr>';
			for( i=0;i<obj.Por.length;i++ ) {
				if( i == 0 ) {
					html+='<tr class="ui-state-default">';
						var data = eval(obj.Por[i]);
						for( index in data ) {
							if( index != 'RESULTADO' ) {
								xcategories.push(index);
							}
							html+='<td align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >'+index+'</td>';
							
						}
					html+='</tr>';
				}
				
				html+='<tr class="ui-widget-content">';
				var data = eval(obj.Por[i]);
				var resultado = '';
				for( index in data ) {
					if( index == 'RESULTADO' ) {
						html+='<td class="ui-state-default" align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
						resultado = data[index];
						xindices.push(resultado);
						xdata_series[resultado] = new Array();
					}else{
						html+='<td align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'%</td>';
						xdata_series[resultado].push(data[index]);
					}
				}	
				html+='</tr>';
			}
			
			$('#table_ranking_fija_cont_hora_por').html(html);
			$('#table_ranking_fija_cont_hora_por tr:gt(0)').hover(function(){ $(this).addClass('ui-state-hover'); },function(){ $(this).removeClass('ui-state-hover'); });
			$('#table_ranking_fija_cont_hora_por tr:gt(0)').click(function(){
					$(this).addClass('ui-state-highlight').siblings().removeClass('ui-state-highlight');
				});
				
			var text_series = '';
			text_series = '['+$.map( xindices, function ( v, i ) {
				return '{ "name":"'+v+'","data":['+xdata_series[v].join(",")+']}';
			}).join(",")+']';
			
							
			new Highcharts.Chart({
								  chart: {
									 renderTo: 'layer_chart_ranking_fija_cont_hora_por',
									 defaultSeriesType: 'spline'
								  },
								  title : { text : "CONTACTABILIDAD POR HORA" },
								  xAxis: { categories: xcategories },
								  yAxis: {
									 title: { text : '%' }
								  },
								  tooltip: {
									 enabled: false,
									 formatter: function() {
										return '<b>'+ this.series.name +'</b><br/>'+
										   this.x +': '+ this.y ;
									 }
								  },
								  plotOptions: {
									 line: {
										dataLabels: {
										   enabled: true
										},
										enableMouseTracking: false
									 }
								  },
								  series: $.parseJSON(text_series)
							   });
							   
				new Highcharts.Chart({
								  chart: {
									 renderTo: 'layer_chart_ranking_fija_cont_hora_por_2',
									 defaultSeriesType: 'area'
								  },
								  title : { text : "CONTACTABILIDAD POR HORA" },
								  xAxis: { 
								  	categories : xcategories,
								  	tickmarkPlacement: 'on',
									 title: {
										enabled: false
									 }

									},
								  yAxis: {
									 title: { text : '%' }
								  },
								  tooltip: {
									 formatter: function() {
										   return ''+
											this.x +': '+ Highcharts.numberFormat(this.percentage, 1) +'% ('+
											Highcharts.numberFormat(this.y, 0, ',') +' )';
									 }

								  },
								  plotOptions: {
									 area: {
										stacking: 'percent',
										lineColor: '#ffffff',
										lineWidth: 1,
										marker: {
										   lineWidth: 1,
										   lineColor: '#ffffff'
										}
									 }
								  },
								  series: $.parseJSON(text_series)
							   });
			
		}, function ( ) {
				$('#table_ranking_fija_cont_hora_por').html(templates.IMGloadingContentTable());
			} );
	
}
load_ranking_fija_final = function ( ) {
	var campania=$('#cbRKCampania_fija_final').val();
	var idcartera = $('#tbRKCartera_fija_final').find(':checked').map(function( ) { return this.value; }).get().join(",");
	var fecha_inicio = $('#txtRKFecha_inicio_final').val();
	var fecha_fin = $('#txtRKFecha_fin_final').val();
	var idservicio = $('#hdCodServicio').val();
	
	var xcategories = new Array();
	var xindices = new Array();
	var xdata_series = new Array();
	
	if( campania == 0 ) {
		alert("Seleccione Campa\xf1a");
		return false;
	}
	if( idcartera == '' ) {
		alert("Seleccione Cartera");
		return false;
	}
	if( fecha_inicio == '' ) {
		alert("Ingrese Fecha Inicio");
		return false;
	}
	if( fecha_fin == '' ) {
		alert("Ingrese Fecha Fin");
		return false;
	}
	
	RankingDAO.Ranking.ranking_final( idcartera, fecha_inicio, fecha_fin, idservicio, function ( obj ) {
			var html = '';
			//////	
			html+='<tr><td colspan="11" style="height:40px;" ></td></tr>';
			for( i=0;i<obj.Ini.length;i++ ) {
				if( i == 0 ) {
					html+='<tr class="ui-state-default">';
					var data = eval(obj.Ini[i]);
					for( index in data ) {
						/*if( index != 'teleoperador' ) {
								xcategories.push(index);
						}*/
						html+='<td align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >'+index+'</td>';
					}
					html+='</tr>';
				}
				
				html+='<tr class="ui-widget-content">';
				var data = eval(obj.Ini[i]);
				var resultado = '';
				for( index in data ) {
					/*if( index == 'teleoperador' ) {
						html+='<td class="ui-state-default" align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
						resultado = data[index];
						xindices.push(resultado);
						
						xdata_series[resultado] = new Array();
					}else{*/
						html+='<td align="center" style="white-space:pre-line;padding:3px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
					/*	xdata_series[resultado].push(data[index]);
					}*/
				}	
				html+='</tr>';
			}	
						
			$('#table_ranking_fija_final').html(html);
			$('#table_ranking_fija_final tr:gt(0)').hover(function(){ $(this).addClass('ui-state-hover'); },function(){ $(this).removeClass('ui-state-hover'); });
			$('#table_ranking_fija_final tr:gt(0)').click(function(){
					$(this).addClass('ui-state-highlight').siblings().removeClass('ui-state-highlight');
				});
				
			/*var text_series = '';
			text_series = '['+$.map( xindices, function ( v, i ) {
				return '{ "name":"'+v+'","data":['+xdata_series[v].join(",")+']}';
			}).join(",")+']';*/
			
			/*var chart;
				chart = new Highcharts.Chart({
					chart: {
						renderTo: 'layer_chart_ranking_fija_final',
						defaultSeriesType: 'line'
					},
					title: {
						text: 'RANKING FINAL',
						x: -20 //center
					},
					xAxis: {
						categories: xcategories
					},
					yAxis: {
						title: {
							text: 'Valores'
						},
						plotLines: [{
							value: 0,
							width: 1,
							color: '#808080'
						}]
					},
					tooltip: {
						formatter: function() {
				                return '<b>'+ this.series.name +'</b><br/>'+
								this.x +': '+ this.y;
						}
					},
					
					series: $.parseJSON(text_series)
				});*/
				
		}, function ( ) {
				$('#table_ranking_fija_final').html(templates.IMGloadingContentTable());
			} );
	
}
loda_ranking_fija_contactabilidad_diario_rpte_xls_jc = function ( ) {
	var campania=$('#cbRKCampania_fija_estado').val();
	var idcartera = $('#tbRKCartera_fija_estado').find(':checked').map(function( ) { return this.value; }).get().join(",");
	var anio = $('#cbAnio_fija_estado').val();
	var mes = $('#cbMes_fija_estado').val();
	var diai = $('#cbDiaI_fija_estado').val();
	var diaf = $('#cbDiaF_fija_estado').val();
	
	if( campania == 0 ) {
		alert("Seleccione Campa\xf1a");
		return false;
	}
	if( idcartera == '' ) {
		alert("Seleccione Cartera");
		return false;
	}
	
	window.location.href="../rpt/excel/procesa_rpt_contactabilidad.php?campania="+campania+"&cartera="+idcartera+"&anio="+anio+"&mes="+mes+"&diai="+diai+"&diaf="+diaf; //cartera=Gestion
}
loda_ranking_fija_contactabilidad_hora_rpte_xls_jc = function ( ) {
	var idcampania=$('#cbRKCampania_fija_cont_hora').val();
	var idcartera = $('#tbRKCartera_fija_cont_hora').find(':checked').map(function( ) { return this.value; }).get().join(",");
	var fecha_inicio = $('#txtRKFecha_inicio_cont_hora').val();
	var fecha_fin = $('#txtRKFecha_fin_cont_hora').val();
	
	if( idcampania == 0 ) {
		alert("Seleccione Campa\xf1a");
		return false;
	}
	if( idcartera == '' ) {
		alert("Seleccione Cartera");
		return false;
	}
	if( fecha_inicio == '' ) {
		alert("Ingrese Fecha Inicio");
		return false;
	}
	if( fecha_fin == '' ) {
		alert("Ingrese Fecha Fin");
		return false;
	}
	
	window.location.href="../rpt/excel/procesa_rpt_contactabilidad_hora.php?idcampania="+idcampania+"&idcartera="+idcartera+"&fecha_inicio="+fecha_inicio+"&fecha_fin="+fecha_fin; //cartera=Gestion
}

load_ranking_fija_compromiso_pago_dia_rpte_xls_jc = function ( ) {
	var idcampania=$('#cbRKCampania_fija_cp').val();
	var idcartera = $('#tbRKCartera_fija_cp').find(':checked').map(function( ) { return this.value; }).get().join(",");
	var anio = $('#cbAnio_fija_cp').val();
	var mes = $('#cbMes_fija_cp').val();
	var diai = $('#cbDiaI_fija_cp').val();
	var diaf = $('#cbDiaF_fija_cp').val();
	var idcarga = $('#tbRK_Carga_Cartera_fija_cp').find(':checked').map(function( ) { return this.value; }).get().join(",");
	
	if( idcampania == 0 ) {
		alert("Seleccione Campa\xf1a");
		return false;
	}
	if( idcartera == '' ) {
		alert("Seleccione Cartera");
		return false;
	}
	if( idcarga == '' ) {
		alert("Seleccione Valores Carga");
		return false;
	}
	
	window.location.href="../rpt/excel/procesa_rpt_compromiso_pago_dia.php?idcampania="+idcampania+"&idcarga="+idcarga+"&idcartera="+idcartera+"&anio="+anio+"&mes="+mes+"&diai="+diai+"&diaf="+diaf; 
}
load_ranking_fija_respuesta_gestion_rpte_xls_jc = function ( ) {
	var idcampania=$('#cbRKCampania_fija_rpt_gest').val();
	var idcartera = $('#tbRKCartera_fija_rpt_gest').find(':checked').map(function( ) { return this.value; }).get().join(",");
	var anio = $('#cbAnio_fija_rpt_gest').val();
	var mes = $('#cbMes_fija_rpt_gest').val();
	var diai = $('#cbDiaI_fija_rpt_gest').val();
	var diaf = $('#cbDiaF_fija_rpt_gest').val();
	
	if( idcampania == 0 ) {
		alert("Seleccione Campa\xf1a");
		return false;
	}
	if( idcartera == '' ) {
		alert("Seleccione Cartera");
		return false;
	}
	
	window.location.href="../rpt/excel/procesa_rpt_respuesta_gestion.php?idcampania="+idcampania+"&idcartera="+idcartera+"&anio="+anio+"&mes="+mes+"&diai="+diai+"&diaf="+diaf; 
}

load_ranking_semaforo_fija_rpte_xls_jc = function ( ) {
	var idcampania=$('#cbRKCampania_fija_semaforo').val();	
	var idcartera = $('#tbRKCartera_fija_semaforo').find(':checked').map(function( ) { return this.value; }).get().join(",");
	var fecha_inicio = $('#txtRKFecha_inicio_fija_semaforo').val();
	var fecha_fin = $('#txtRKFecha_fin_fija_semaforo').val();
	var meta = $('#txtRK_meta_fija_semaforo').val();
	var idcarga = $('#tbRK_Carga_Cartera_fija_semaforo').find(':checked').map(function( ) { return this.value; }).get().join(",");
	
	if( idcampania == 0 ) {
		alert("Seleccione Campa\xf1a");
		return false;
	}
	if( idcartera == '' ) {
		alert("Seleccione Cartera");
		return false;
	}
	if( idcarga == '' ) {
		alert("Seleccione Valores Carga");
		return false;
	}
	if( fecha_inicio == '' ) {
		alert("Ingrese Fecha Inicio");
		return false;
	}
	if( fecha_fin == '' ) {
		alert("Ingrese Fecha Fin");
		return false;
	}
	
	window.location.href="../rpt/excel/procesa_rpte_semaforo_fija.php?idcampania="+idcampania+"&idcartera="+idcartera+"&fecha_inicio="+fecha_inicio+"&fecha_fin="+fecha_fin+"&meta="+meta+"&idcarga="+idcarga;
}

load_ranking_fija_final_rpte_xls_jc = function ( ) {
	var idcampania=$('#cbRKCampania_fija_final').val();
	var idcartera = $('#tbRKCartera_fija_final').find(':checked').map(function( ) { return this.value; }).get().join(",");
	var fecha_inicio = $('#txtRKFecha_inicio_final').val();
	var fecha_fin = $('#txtRKFecha_fin_final').val();
	var idservicio = $('#hdCodServicio').val();
	
	if( idcampania == 0 ) {
		alert("Seleccione Campa\xf1a");
		return false;
	}
	if( idcartera == '' ) {
		alert("Seleccione Cartera");
		return false;
	}
	if( fecha_inicio == '' ) {
		alert("Ingrese Fecha Inicio");
		return false;
	}
	if( fecha_fin == '' ) {
		alert("Ingrese Fecha Fin");
		return false;
	}
	
	window.location.href="../rpt/excel/procesa_rpt_ranking_fija_final.php?idcampania="+idcampania+"&idcartera="+idcartera+"&fecha_inicio="+fecha_inicio+"&fecha_fin="+fecha_fin+"&idservicio="+idservicio; 
}

generar_anio = function ( obj ) {
	var date=new Date();
	var anioAct=date.getFullYear();
	var html='';
	for( i=(anioAct-2);i<(anioAct+2);i++ ){
		if( i==anioAct ){
			html+='<option value="'+i+'" selected="selected">'+i+'</option>';
		}else{
			html+='<option value="'+i+'">'+i+'</option>';
		}
	}
	$.each(obj,function( key,value ){
		$('#'+value.id).html(html);
	});
}
generar_mes = function ( obj ) {
	var date=new Date();
	var html='';
	var mes=['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre'];	
	for( i=0;i<mes.length;i++ ) {
		if( i==(date.getMonth()) ) {
			html+='<option value="'+(i+1)+'" selected="selected" >'+mes[i]+'</option>';
		}else{
			html+='<option value="'+(i+1)+'">'+mes[i]+'</option>';
		}
	}
	$.each(obj,function( key,value ){
		$('#'+value.id).html(html);
	});
}
generar_dia = function ( obj ) {
	var date=new Date();
	var html='';
	for( i=1;i<=31;i++ ) {
		if( i==date.getDate()){
			html+='<option value="'+i+'" selected="selected">'+i+'</option>';
		}else{
			html+='<option value="'+i+'" >'+i+'</option>';
		}
	}
	$.each(obj,function( key,value ){
		$('#'+value.id).html(html);
	});
}
checked_all_carteras_asignar_fija_estado = function ( element ) {
	if( element ) {
		$('#tbRKCartera_fija_estado').find(':checkbox').attr('checked',true);
	}else{
		$('#tbRKCartera_fija_estado').find(':checkbox').attr('checked',false);
	}
}
des_checked_fija_estado = function ( element ) {
	$('#sel_all_fija_estado').find(':checkbox').attr('checked',false);
}

checked_all_carteras_asignar_fija_rpt_gest = function ( element ) {
	if( element ) {
		$('#tbRKCartera_fija_rpt_gest').find(':checkbox').attr('checked',true);
	}else{
		$('#tbRKCartera_fija_rpt_gest').find(':checkbox').attr('checked',false);
	}
}
des_checked_fija_rpt_gest = function ( element ) {
	$('#sel_all_fija_rpt_gest').find(':checkbox').attr('checked',false);
}

checked_all_carteras_asignar_fija_cont_hora = function ( element ) {
	if( element ) {
		$('#tbRKCartera_fija_cont_hora').find(':checkbox').attr('checked',true);
	}else{
		$('#tbRKCartera_fija_cont_hora').find(':checkbox').attr('checked',false);
	}
}
des_checked_fija_cont_hora = function ( element ) {
	$('#sel_all_fija_cont_hora').find(':checkbox').attr('checked',false);
}

checked_all_carteras_asignar_fija_semaforo = function ( element ) {
	if( element ) {
		$('#tbRKCartera_fija_semaforo').find(':checkbox').attr('checked',true);
	}else{
		$('#tbRKCartera_fija_semaforo').find(':checkbox').attr('checked',false);
	}
}
des_checked_fija_semaforo = function ( element ) {
	$('#sel_all_fija_semaforo').find(':checkbox').attr('checked',false);
}

checked_all_carteras_asignar_por_contacto = function ( element ) {
	if( element ) {
		$('#tbRKCartera_por_contacto').find(':checkbox').attr('checked',true);
	}else{
		$('#tbRKCartera_por_contacto').find(':checkbox').attr('checked',false);
	}
}
des_checked_por_contacto = function ( element ) {
	$('#sel_all_fija_cp').find(':checkbox').attr('checked',false);
}


checked_all = function ( element,idtb ) {
	if( element ) {
		$('#'+idtb).find(':checkbox').attr('checked',true);
	}else{
		$('#'+idtb).find(':checkbox').attr('checked',false);
	}
}
des_checked = function ( id ) {
	$('#'+id).attr('checked',false);
}



