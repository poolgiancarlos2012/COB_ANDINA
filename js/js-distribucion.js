$(document).ready(function( ){
	/********************/
	DistribucionDAO.CampaniaAjax();
	DistribucionDAO.ListOperadores();
	//DistribucionDAO.ListarClusterServicio();
	LISTAR_CARTERAS_SERVICIO();
	/********************/
	$('#layerDatepicker').datepicker({inline:true,autoSize:true,dayNamesMin:['D','L','M','M','J','V','S'],monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']});
	$('#txtCampaniaFechaInicio,#txtCampaniaFechaFin').datepicker({dateFormat:'yy-mm-dd',dayNamesMin:['D','L','M','M','J','V','S'],monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']});
	/********************/
	$('#btnExportarClientesGestionados,#btnExportarClientesSinGestionar').button( { icons : { primary : "ui-icon-extlink" } } );
	/********************/
	$('#aDisplayPanelClienteGestClienteSinGest').one('click',
		function( )
		{
			DistribucionJQGRID.clientes_gestionadosCOBRAST();
			DistribucionJQGRID.clientes_sin_gestionarCOBRAST();
		}
		);
	$('#tab_distribucion_bottom_operador').one('click',
		function ( ) {
			DistribucionJQGRID.clientes_por_cartera();
		}
	);
	$('#tab_distribucion_bottom_especial').one('click', 
		function ( ) 
		{
			DistribucionJQGRID.cliente_distribucion_especial();
			DistribucionJQGRID.cliente_especiales_asignados();
		}
	);
	$('#aDisplayPanelRegistrarZona').one('click',
		function ( ) 
		{
			listar_departamentos_zonas();
		}
	);
	/********************/
	$('#dialogCampania').dialog({
							  	height : 300,
								autoOpen : false,
								width : 420 ,
								title : 'Crear Campania',
								modal : true,
								buttons : {
										Cancel : function ( ) {
												$(this).dialog('close');
												cancel_campania();
											},
										Aceptar : function ( ) {
												save_campania();
												//UsuarioDAO.insertCampania();
											}
									}
							  });
	/********************/
	$('#table_asignacion').droppable({
									 drop: function ( event, ui ) {
										 	var html='';
											var id=ui.draggable.find(":hidden").val();
											var text=ui.draggable.text();
											
											//var count=$('#table_asignacion').find('#'+id).length;
											var count = $(this).find('#'+id).length;
											
											if(count==0){
												html+='<tr id="'+id+'">';
													html+='<td style="width:220px;" align="center">'+text+'</td>';
													html+='<td style="width:50px;" align="center"><input style="width:40px;" ></td>';
													html+='<td class="ui-pg-button ui-corner-all"><div class="ui-pg-div ui-corner-all"><span class="ui-icon ui-icon-check"></span></div></td>';
												html+='</tr>';
												//$('#table_asignacion #placeHolder').remove();
												$(this).find('#placeHolder').remove();
												$('#table_asignacion').append(html);
												$('#table_asignacion tr:last').find('td:last').bind('click',function(){ check_manual(this); });
												$('#table_asignacion .ui-pg-div').hover(function(){$(this).addClass('ui-state-hover');},function(){$(this).removeClass('ui-state-hover');});
												$('#table_asignacion tr').find('td:first').draggable({
																					cursor : 'move',
																					helper : function ( event ) {
																							return '<div class="ui-widget-header ui-corner-all" style="width:70px;padding:3px 8px;">Eliminando...</div>'
																						},
																					stop : function ( ) {
																							var clientes_ingresados=0;
																							var LabelCOUNT=$(this).parent().find('label').length;
																							if( LabelCOUNT==1 ) {
																								clientes_ingresados=parseInt( $(this).parent().find('label').text() );
																							}
																							
																							$(this).parent().remove();
																							$('#txtClienteSinAsignarManual').val( parseInt($('#txtClienteSinAsignarManual').val())+clientes_ingresados )
																							var count=$('#table_asignacion').find('tr').length;
																							
																							if(count==0){
																								var html='';
																								html+='<tr id="placeHolder"><td>Arrastre operadores aqui..</td></tr>';
																								$('#table_asignacion').html(html);
																							}
																							
																						}
																					});
											}
											
											
										 }
									 });
	$('#table_asignacion_RedistribSinPago').droppable({
									 drop: function ( event, ui ) {
										 	var html='';
											var id=ui.draggable.find(":hidden").val();
											var text=ui.draggable.text();
											
											//var count=$('#table_asignacion').find('#'+id).length;
											var count = $(this).find('#'+id).length;
											
											if(count==0){
												html+='<tr id="'+id+'">';
													html+='<td style="width:220px;" align="left" class="pointer">'+text+'</td>';
													html+='<td style="width:50px;" align="center"><input style="width:40px;" ></td>';
													html+='<td class="ui-pg-button ui-corner-all"><div class="ui-pg-div ui-corner-all"><span class="ui-icon ui-icon-check"></span></div></td>';
												html+='</tr>';
												//$('#table_asignacion #placeHolder').remove();
												$(this).find('#placeHolder').remove();
												$('#table_asignacion_RedistribSinPago').append(html);
												$('#table_asignacion_RedistribSinPago tr:last').find('td:last').bind('click',function(){ check_manual_RedistribSinPago(this); });
												$('#table_asignacion_RedistribSinPago .ui-pg-div').hover(function(){$(this).addClass('ui-state-hover');},function(){$(this).removeClass('ui-state-hover');});
												$('#table_asignacion_RedistribSinPago tr').find('td:first').draggable({
																					cursor : 'move',
																					helper : function ( event ) {
																							return '<div class="ui-widget-header ui-corner-all" style="width:70px;padding:3px 8px;">Eliminando...</div>'
																						},
																					stop : function ( ) {
																							var clientes_ingresados=0;
																							var LabelCOUNT=$(this).parent().find('label').length;
																							if( LabelCOUNT==1 ) {
																								clientes_ingresados=parseInt( $(this).parent().find('label').text() );
																							}
																							
																							$(this).parent().remove();
																							$('#txtClienteSinPago').val( parseInt($('#txtClienteSinPago').val())+clientes_ingresados )
																							var count=$('#table_asignacion_RedistribSinPago').find('tr').length;
																							
																							if(count==0){
																								var html='';
																								html+='<tr id="placeHolder"><td>Arrastre operadores aqui..</td></tr>';
																								$('#table_asignacion_RedistribSinPago').html(html);
																							}
																							
																						}
																					});
											}
											
											
										 }
									 });
	$('#table_asignacion_RedistribAmortizado').droppable({
									 drop: function ( event, ui ) {
										 	var html='';
											var id=ui.draggable.find(":hidden").val();
											var text=ui.draggable.text();
											
											//var count=$('#table_asignacion').find('#'+id).length;
											var count = $(this).find('#'+id).length;
											
											if(count==0){
												html+='<tr id="'+id+'">';
													html+='<td style="width:220px;" align="left" class="pointer">'+text+'</td>';
													html+='<td style="width:50px;" align="center"><input style="width:40px;" ></td>';
													html+='<td class="ui-pg-button ui-corner-all"><div class="ui-pg-div ui-corner-all"><span class="ui-icon ui-icon-check"></span></div></td>';
												html+='</tr>';
												//$('#table_asignacion #placeHolder').remove();
												$(this).find('#placeHolder').remove();
												$('#table_asignacion_RedistribAmortizado').append(html);
												$('#table_asignacion_RedistribAmortizado tr:last').find('td:last').bind('click',function(){ check_manual_RedistribAmortizado(this); });
												$('#table_asignacion_RedistribAmortizado .ui-pg-div').hover(function(){$(this).addClass('ui-state-hover');},function(){$(this).removeClass('ui-state-hover');});
												$('#table_asignacion_RedistribAmortizado tr').find('td:first').draggable({
																					cursor : 'move',
																					helper : function ( event ) {
																							return '<div class="ui-widget-header ui-corner-all" style="width:70px;padding:3px 8px;">Eliminando...</div>'
																						},
																					stop : function ( ) {
																							var clientes_ingresados=0;
																							var LabelCOUNT=$(this).parent().find('label').length;
																							if( LabelCOUNT==1 ) {
																								clientes_ingresados=parseInt( $(this).parent().find('label').text() );
																							}
																							
																							$(this).parent().remove();
																							$('#txtClienteAmortizado').val( parseInt($('#txtClienteAmortizado').val())+clientes_ingresados )
																							var count=$('#table_asignacion_RedistribAmortizado').find('tr').length;
																							
																							if(count==0){
																								var html='';
																								html+='<tr id="placeHolder"><td>Arrastre operadores aqui..</td></tr>';
																								$('#table_asignacion_RedistribAmortizado').html(html);
																							}
																							
																						}
																					});
											}
											
											
										 }
									 });									 
									 
									 
		
});
check_manual = function ( element ) {

//~ Vic I
	var ClientesSinAsignar=parseInt( $('#txtClienteSinAsignarManual').val() );
	var cantidad = 0;
	var accion = "";
	if ( $(element).parent().find(':text').val() == undefined)
	{
		cantidad=parseInt( $.trim( $(element).parent().find('label').html() ) );
		accion = "editar";
	}
	else
	{
		cantidad=parseInt( $.trim( $(element).parent().find(':text').val() ) );
		accion = "grabar";
	}

	if (accion=='grabar')
	{
		if(cantidad<=ClientesSinAsignar){
			var html='<label>'+cantidad+'</label>';
			$(element).parent().children('td:eq(1)').html(html);
			$('#txtClienteSinAsignarManual').val(ClientesSinAsignar-cantidad);
			$(element).find('span').removeClass('ui-icon-check').addClass('ui-icon-pencil');
			$(element).unbind('click').bind('click',function(){ edit_manual(this); });
		}
	}
	else
	{
		$('#txtClienteSinAsignarManual').val(ClientesSinAsignar+cantidad);
	}

/*
	var ClientesSinAsignar=parseInt( $('#txtClienteSinAsignarManual').val() );
	var cantidad=parseInt( $.trim( $(element).parent().find(':text').val() ) );
	if(cantidad<=ClientesSinAsignar){
		var html='<label>'+cantidad+'</label>';
		$(element).parent().children('td:eq(1)').html(html);
		$('#txtClienteSinAsignarManual').val(ClientesSinAsignar-cantidad);
		$(element).find('span').removeClass('ui-icon-check').addClass('ui-icon-pencil');
		$(element).unbind('click').bind('click',function(){ edit_manual(this); });
	}
*/

}
check_manual_RedistribSinPago = function ( element ) {

	var ClientesSinPago=parseInt( $('#txtClienteSinPago').val() );
	var cantidad=parseInt( $.trim( $(element).parent().find(':text').val() ) );
	if(cantidad<=ClientesSinPago){
		var html='<label>'+cantidad+'</label>';
		$(element).parent().children('td:eq(1)').html(html);
		$('#txtClienteSinPago').val(ClientesSinPago-cantidad);
		$(element).find('span').removeClass('ui-icon-check').addClass('ui-icon-pencil');
		$(element).unbind('click').bind('click',function(){ edit_manual_RedistribSinPago(this); });
	}
		
}
check_manual_RedistribAmortizado = function ( element ) {

	var ClientesAmortizado=parseInt( $('#txtClienteAmortizado').val() );
	var cantidad=parseInt( $.trim( $(element).parent().find(':text').val() ) );
	if(cantidad<=ClientesAmortizado){
		var html='<label>'+cantidad+'</label>';
		$(element).parent().children('td:eq(1)').html(html);
		$('#txtClienteAmortizado').val(ClientesAmortizado-cantidad);
		$(element).find('span').removeClass('ui-icon-check').addClass('ui-icon-pencil');
		$(element).unbind('click').bind('click',function(){ edit_manual_RedistribAmortizado(this); });
	}
		
}
edit_manual = function ( element ) {
	
	var html='';
	var cantidad=$(element).parent().find('label').text();
	html+='<input type="text" style="width:40px;" value="'+cantidad+'" />';
	$(element).parent().children('td:eq(1)').html(html);
	$(element).find('span').removeClass('ui-icon-pencil').addClass('ui-icon-check');
	$(element).unbind('click').bind('click',function(){ check_manual(this); });
}
edit_manual_RedistribSinPago = function ( element ) {
	
	var html='';
	var cantidad=$(element).parent().find('label').text();
	html+='<input type="text" style="width:40px;" value="'+cantidad+'" />';
	$(element).parent().children('td:eq(1)').html(html);
	$(element).find('span').removeClass('ui-icon-pencil').addClass('ui-icon-check');
	$(element).unbind('click').bind('click',function(){ check_manual_RedistribSinPago(this); });
}
edit_manual_RedistribAmortizado = function ( element ) {
	
	var html='';
	var cantidad=$(element).parent().find('label').text();
	html+='<input type="text" style="width:40px;" value="'+cantidad+'" />';
	$(element).parent().children('td:eq(1)').html(html);
	$(element).find('span').removeClass('ui-icon-pencil').addClass('ui-icon-check');
	$(element).unbind('click').bind('click',function(){ check_manual_RedistribAmortizado(this); });
}
agregar_asignacion = function ( element ) {
	var html='';
	var id=$(element).parent().attr('id');
	var operador=$(element).parent().children('td:eq(0)').text();
	var count=$('#table_asignacion').find('#'+id).length;
	html+='<tr id="'+id+'" >';
		html+='<td style="width:220px;" align="center">'+operador+'</td>';
		html+='<td style="width:50px;" align="center"><input style="width:40px;" /></td>';
		html+='<td class="ui-pg-button ui-corner-all" onclick="check_manual(this)"><div class="ui-pg-div ui-corner-all"><span class="ui-icon ui-icon-check"></span></div></td>';
	html+='</tr>';
	
	if(count==1){
		return false;	
	}
	
	$('#table_asignacion').find('tr[id="placeHolder"]').remove();
	$('#table_asignacion').append(html);
	
	$('#table_asignacion .ui-pg-div').hover(function(){$(this).addClass('ui-state-hover');},function(){$(this).removeClass('ui-state-hover');});
	$('#table_asignacion tr').find('td:first').draggable({
										cursor : 'move',
										helper : function ( event ) {
												return '<div class="ui-widget-header ui-corner-all" style="width:70px;padding:3px 8px;">Eliminando...</div>'
											},
										stop : function ( ) {
												$(this).parent().remove();
												var count=$('#table_asignacion').find('tr').length;
												if(count==0){
													var html='';
													html+='<tr id="placeHolder"><td>Arrastre operadores aqui..</td></tr>';
													$('#table_asignacion').html(html);
												}
												
											}
										});
}
agregar_asignacion_sinpago = function ( element ) {
	var html='';
	var id=$(element).parent().attr('id');
	var operador=$(element).parent().children('td:eq(0)').text();
	var count=$('#table_asignacion_RedistribSinPago').find('#'+id).length;
	html+='<tr id="'+id+'" >';
		html+='<td style="width:220px;" align="left" class="pointer">'+operador+'</td>';
		html+='<td style="width:50px;" align="center"><input style="width:40px;" /></td>';
		html+='<td class="ui-pg-button ui-corner-all" onclick="check_manual_RedistribSinPago(this)"><div class="ui-pg-div ui-corner-all"><span class="ui-icon ui-icon-check"></span></div></td>';
	html+='</tr>';
	
	if(count==1){
		return false;	
	}
	
	$('#table_asignacion_RedistribSinPago').find('tr[id="placeHolder"]').remove();
	$('#table_asignacion_RedistribSinPago').append(html);
	
	$('#table_asignacion_RedistribSinPago .ui-pg-div').hover(function(){$(this).addClass('ui-state-hover');},function(){$(this).removeClass('ui-state-hover');});
	$('#table_asignacion_RedistribSinPago tr').find('td:first').draggable({
										cursor : 'move',
										helper : function ( event ) {
												return '<div class="ui-widget-header ui-corner-all" style="width:70px;padding:3px 8px;">Eliminando...</div>'
											},
										stop : function ( ) {
												$(this).parent().remove();
												var count=$('#table_asignacion_RedistribSinPago').find('tr').length;
												if(count==0){
													var html='';
													html+='<tr id="placeHolder"><td>Arrastre operadores aqui..</td></tr>';
													$('#table_asignacion_RedistribSinPago').html(html);
												}
												
											}
										});
}
agregar_asignacion_amortizado = function ( element ) {
	var html='';
	var id=$(element).parent().attr('id');
	var operador=$(element).parent().children('td:eq(0)').text();
	var count=$('#table_asignacion_RedistribAmortizado').find('#'+id).length;
	html+='<tr id="'+id+'" >';
		html+='<td style="width:220px;" align="left" class="pointer">'+operador+'</td>';
		html+='<td style="width:50px;" align="center"><input style="width:40px;" /></td>';
		html+='<td class="ui-pg-button ui-corner-all" onclick="check_manual_RedistribAmortizado(this)"><div class="ui-pg-div ui-corner-all"><span class="ui-icon ui-icon-check"></span></div></td>';
	html+='</tr>';
	
	if(count==1){
		return false;	
	}
	
	$('#table_asignacion_RedistribAmortizado').find('tr[id="placeHolder"]').remove();
	$('#table_asignacion_RedistribAmortizado').append(html);
	
	$('#table_asignacion_RedistribAmortizado .ui-pg-div').hover(function(){$(this).addClass('ui-state-hover');},function(){$(this).removeClass('ui-state-hover');});
	$('#table_asignacion_RedistribAmortizado tr').find('td:first').draggable({
										cursor : 'move',
										helper : function ( event ) {
												return '<div class="ui-widget-header ui-corner-all" style="width:70px;padding:3px 8px;">Eliminando...</div>'
											},
										stop : function ( ) {
												$(this).parent().remove();
												var count=$('#table_asignacion_RedistribAmortizado').find('tr').length;
												if(count==0){
													var html='';
													html+='<tr id="placeHolder"><td>Arrastre operadores aqui..</td></tr>';
													$('#table_asignacion_RedistribAmortizado').html(html);
												}
												
											}
										});
}


generar_distribucion_automatica = function  ( ) {
	var rs=validacion.check([
		{id:'cbCarteraDistribucionAutomatica',isNotValue:0,errorNotValueFunction:function( ){
				$('#layerMessage').html(templates.MsgError('Seleccione cartera a distribuir','400px'));
				$('#layerMessage').effect('pulsate',{},'slow',function( ) { $(this).empty(); } );
			}}
		]);
	if( rs ){
		var rsC=confirm("Desea generar distribucion automatica");
		if( rsC ){
			DistribucionDAO.generar_distribucion_automatica();
		}
	}
}
cancel_distribucion_automatica = function ( ) {
	$('#txtClientesSinAsignar').val('');
	$('#txtClientesAsignados').val('');
	$('#txtCantidadOperadores').val('');
	$('#txtClientesXOperador').val('');
	$('#cbCampaniaDistribucionAutomatica').val(0);
}
grabar_distribucion_manual = function ( ) {
	
	var cant_clientes_sin_asignar=$('#hdClienteSinAsignar').val();
	var cant_clientes_ingresados=0;
	if ( cant_clientes_sin_asignar=='' ) {
		alert('No hay clientes disponibles para asignar');
		return false;
	}
	
	var data="["+$('#table_asignacion').find('tr').map(function(){
												var count=$(this).find('label').length;
												var clientes=$(this).find('label').text();
												var usuario=this.id;
												cant_clientes_sin_asignar+=parseInt( clientes );
												if(count==1){
													return '{"usuario_servicio":"'+usuario+'","clientes":"'+clientes+'"}';
												}
												
											}).get().join(',')+"]";
											
	if( cant_clientes_sin_asignar<cant_clientes_ingresados ) {
		alert("Cantidad de clientes ingresados supera clientes sin asignar");
		return false;	
	}
											
	var rsC=confirm(" Verifique los datos ingresados ");
	
	if( rsC ) {
		DistribucionDAO.generar_distribucion_manual( data );
	}
}
grabar_distribucion_sinpago = function ( ) {
	
	var cant_clientes_sin_asignar=$('#hdClienteSinPago').val();
	var cant_clientes_ingresados=0;
	if ( cant_clientes_sin_asignar=='' ) {
		alert('No hay clientes disponibles para asignar');
		return false;
	}
	
	var data="["+$('#table_asignacion_RedistribSinPago').find('tr').map(function(){
												var count=$(this).find('label').length;
												var clientes=$(this).find('label').text();
												var usuario=this.id;
												cant_clientes_sin_asignar+=parseInt( clientes );
												if(count==1){
													return '{"usuario_servicio":"'+usuario+'","clientes":"'+clientes+'"}';
												}
												
											}).get().join(',')+"]";
											
	if( cant_clientes_sin_asignar<cant_clientes_ingresados ) {
		alert("Cantidad de clientes ingresados supera clientes sin asignar");
		return false;	
	}
											
	var rsC=confirm(" Verifique los datos ingresados ");
	
	if( rsC ) {
		DistribucionDAO.generar_distribucion_sinpago( data );
	}
}
grabar_distribucion_amortizado = function ( ) {
	
	var cant_clientes_sin_asignar=$('#hdClienteAmortizado').val();
	var cant_clientes_ingresados=0;
	if ( cant_clientes_sin_asignar=='' ) {
		alert('No hay clientes disponibles para asignar');
		return false;
	}
	
	var data="["+$('#table_asignacion_RedistribAmortizado').find('tr').map(function(){
												var count=$(this).find('label').length;
												var clientes=$(this).find('label').text();
												var usuario=this.id;
												cant_clientes_sin_asignar+=parseInt( clientes );
												if(count==1){
													return '{"usuario_servicio":"'+usuario+'","clientes":"'+clientes+'"}';
												}
												
											}).get().join(',')+"]";
											
	if( cant_clientes_sin_asignar<cant_clientes_ingresados ) {
		alert("Cantidad de clientes ingresados supera clientes sin asignar");
		return false;	
	}
											
	var rsC=confirm(" Verifique los datos ingresados ");
	
	if( rsC ) {
		DistribucionDAO.generar_distribucion_amortizado( data );
	}
}

save_traspaso_carteras_operadores = function ( ) {
	var idusuario_servicio_DE=$('#table_traspaso_clientes_DE').find(':checked').map(function(){return this.value;}).get().join(",");
	var idusuario_servicio_PARA=$('#table_traspaso_clientes_PARA').find(':checked').map(function(){return this.value;}).get().join(",");
	var idcartera=$('#cbCarteraTraspasoCartera').val();
	var xfiltro = $(':checkbox[id^="chkTrCar"]:checked').map( function ( ) {
						return $(this).val();
				} ).get().join(",");
	
	if(idcartera==0){
		alert("Seleccione Cartera");	
		return false;
	}
	if(idusuario_servicio_DE=='' || idusuario_servicio_PARA==''){
		alert("Seleccionar Operadores");	
		return false;
	}
	if(idusuario_servicio_DE==idusuario_servicio_PARA){
		alert("No se puede realizar Traspasos entre el mismo Operador");	
		return false;
	}
	var rsC=confirm("Confirma el traspado de Carteras");
	if( rsC ) {
		DistribucionDAO.generar_traspaso_cartera( idusuario_servicio_DE,idusuario_servicio_PARA,idcartera, xfiltro );
	}
}
retirar_todo_clientes_sin_gestionar = function ( element ) {
	var usuario=$(element).parent().attr('id');
	DistribucionDAO.delete_all_clientes_sin_gestionar( usuario, element );	
}
edit_retirar_clientes = function ( element ) {
	var html='';
	var clientes_sin_gestionar=$(element).parent().children('td:eq(3)').text();
	html+='<input type="hidden" value="'+clientes_sin_gestionar+'" >';
	html+='<input type="text" style="width:50px;" value="'+clientes_sin_gestionar+'" />';
	$(element).parent().children('td:eq(3)').html(html);
	$(element).find('span').removeClass('ui-icon-pencil').addClass('ui-icon-close');
	$(element).unbind('click').bind('click',function(){close_retirar_clientes(this);});
}
close_retirar_clientes = function ( element ) {
	var clientes_sin_gestionar=$(element).parent().children('td:eq(3)').find(':hidden').val();
	$(element).parent().children('td:eq(3)').text(clientes_sin_gestionar);
	$(element).find('span').removeClass('ui-icon-close').addClass('ui-icon-pencil');
	$(element).unbind('click').bind('click',function(){edit_retirar_clientes(this);});
}
save_retirar_clientes = function ( element ) {
	var count=$(element).parent().find(':text').length;
	var cantidad=0;
	if(count==0){
		return false;
	}
	var usuario_servicio=$(element).parent().attr('id');
	var clientes_tiene=parseInt( $(element).parent().find(':hidden').val() );
	var clientes_ingresados=parseInt( $(element).parent().find(':text').val() );
	
	if( clientes_ingresados>clientes_tiene ) {
		alert("Clientes ingresados es mayor a clientes sin gestionar");
		return false;	
	}else if( clientes_ingresados==clientes_tiene ) {
		cantidad=clientes_ingresados;
	}else {
		cantidad=clientes_tiene-clientes_ingresados;
	}
	
	//var rsC=confirm("Verifique los datos antes de grabar");
	//if( rsC ) {
		DistribucionDAO.delete_retirar_clientes_sin_gestionar_ingresados(element,usuario_servicio,cantidad);
	//}
}
load_cartera_by_id_rpte_rank = function ( idCampania, cbCartera ) {
	var filtroEstadoCart=$('#tbEstadoCarteraDistribucion').find(':checked').map(function(){return this.value;}).get().join(",");
	if(filtroEstadoCart==''){
		alert("Seleccione Estado de Cartera (No Vencido / Vencido)");	
		return false;
	}
	DistribucionDAO.ListarCarteraRpteRank(idCampania,filtroEstadoCart,function( obj ) { DistribucionDAO.FillCarteraById(obj,cbCartera); });
}
limpiaCamposReporte = function(){
	//$('#cbCampaniaLlaEst').val('0');
	$('#cobrastHOME').find('select[id^="cbCampania"]').val('0');
	var html='<option value="0">--Seleccione--</option>';
	$('#cobrastHOME').find('select[id^="cbCartera"]').html(html);
	}
/*cargar_cartera_distribucion_automatica = function ( idCampania ) {
	DistribucionDAO.ListCartera(idCampania,DistribucionDAO.FillCarteraDistribucionAutomatica);
}*/
cargar_data_distribucion_automatica = function ( idCartera ) {
	DistribucionDAO.DataDistribucionAutomatica(idCartera);
}
/*cargar_cartera_distribucion_manual = function ( idCampania ) {
	DistribucionDAO.ListCartera(idCampania,DistribucionDAO.FillCarteraDistribucionManual);
}*/
cargar_data_distribucion_manual = function ( idCartera ) {
	DistribucionDAO.ListarGestionOperador(idCartera);
	DistribucionDAO.clientes_sin_asignar(idCartera);
}
cargar_data_distribucion_manual_cluster = function ( idCartera,idcluster ) {
	var idcartera=$('#'+idCartera).val();
	DistribucionDAO.ListarGestionOperadorPorCluster(idcartera,idcluster);
}
cargar_departamento_distribucion_por_departamento_cluster = function ( idCartera,idcluster ) {
	var idcartera=$('#'+idCartera).val();
	DistribucionDAO.ListarOperadoresCantidadGestionPorCluster(idcartera,idcluster,DistribucionDAO.TeleoperadoresDistribucionPorDepartamento);
}
cargar_tramo_distribucion_por_tramo_cluster = function ( idCartera,idcluster ) {
	var idcartera=$('#'+idCartera).val();
	DistribucionDAO.ListarOperadoresCantidadGestionPorCluster(idcartera,idcluster,DistribucionDAO.TeleoperadoresDistribucionPorTramo);
}
cargar_teleoperadores_distribucion_por_campos_cluster = function ( idCartera,idcluster ) {
	var idcartera=$('#'+idCartera).val();
	DistribucionDAO.ListarOperadoresCantidadGestionPorCluster(idcartera,idcluster,DistribucionDAO.TeleoperadoresDistribucionPorCampos);
}
cargar_teleoperadores_distribucion_montos_iguales_cluster = function ( idCartera,idcluster ) {
	var idcartera=$('#'+idCartera).val();
	DistribucionDAO.ListarOperadoresCantidadGestionPorCluster(idcartera,idcluster,DistribucionDAO.TeleoperadoresDistribucionMontosIguales);
}
cargar_teleoperadores_distribucion_constante_cluster = function ( idCartera,idcluster ) {
	var idcartera=$('#'+idCartera).val();
	DistribucionDAO.ListarOperadoresCantidadGestionPorCluster(idcartera,idcluster,DistribucionDAO.TeleoperadoresDistribucionConstante);
}
cargar_teleoperadores_distribucion_sin_gestion_cluster = function ( idCartera,idcluster ) {
	var idcartera=$('#'+idCartera).val();
	DistribucionDAO.ListarOperadoresCantidadGestionPorCluster(idcartera,idcluster,DistribucionDAO.TeleoperadoresDistribucionSinGestion);
}

cargar_data_distribucion_sinpago = function ( idCartera,idTable,idTxtCliCar ) {
	DistribucionDAO.ListarDataGestionOperador(idCartera,idTable);
	DistribucionDAO.clientes_sin_asignar_sinpago(idCartera);
	DistribucionDAO.numeroClientesCartera(idCartera,idTxtCliCar);
}
cargar_data_distribucion_amortizado = function ( idCartera,idTable,idTxtCliCar ) {
	DistribucionDAO.ListarDataGestionOperadorAmortizado(idCartera,idTable);
	DistribucionDAO.clientes_sin_asignar_amortizado(idCartera);
	DistribucionDAO.numeroClientesCartera(idCartera,idTxtCliCar);
}
/*cargar_cartera_retirar_clientes = function ( idCampania ) {
	DistribucionDAO.ListCartera(idCampania,DistribucionDAO.FillCarteraRetirarClientes);
}*/
cargar_data_retirar_clientes = function ( idCartera ) {
	DistribucionDAO.operadores_retirar_cliente(idCartera);	
}
cargar_data_traspaso_clientes = function ( idCartera ) {
	DistribucionDAO.operadores_traspaso_cliente(idCartera);	
}
/*cargar_cartera_clientes_GSG = function ( idCampania ) {
	DistribucionDAO.ListCartera(idCampania,DistribucionDAO.FillCarteraClientes_GSG);
}*/
reload_jqgrid_clientes_GSG = function ( idCartera ) {
	//var idCartera=$('#cbCarteraClientesGestSinGest').val();
	var idServicio=$('#hdCodServicio').val();
	$("#table_clientes_gestionados").jqGrid('setGridParam',{url:'../controller/ControllerCobrast.php?command=distribucion&action=jqgrid_clientes_gestionados&Cartera='+idCartera+'&Servicio='+idServicio}).trigger('reloadGrid');
	$("#table_clientes_sin_gestionar").jqGrid('setGridParam',{url:'../controller/ControllerCobrast.php?command=distribucion&action=jqgrid_clientes_sin_gestionar&Cartera='+idCartera+'&Servicio='+idServicio}).trigger('reloadGrid');
}
save_campania = function ( ) {
	var rs=validacion.check([
		{id:'txtCampaniaNombre',required:true,isAlphaNumeric:true,errorRequiredFunction : function ( ) {
				$('#CampaniaLayerMessage').html(templates.MsgError('Ingrese Nombre de Campa&ntilde;a','250px'));
				$('#CampaniaLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });	
			},errorAlphaNumericFunction : function ( ) {
				$('#CampaniaLayerMessage').html(templates.MsgError('Ingrese solo texto y numero','250px'));
				$('#CampaniaLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });	
			} },
		{id:'txtCampaniaFechaInicio',required:true,errorRequiredFunction : function ( ) {
				$('#CampaniaLayerMessage').html(templates.MsgError('Ingrese Fecha de Inicio','250px'));
				$('#CampaniaLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });	
			} },
		{id:'txtCampaniaFechaFin',required:true,errorRequiredFunction : function ( ) {
				$('#CampaniaLayerMessage').html(templates.MsgError('Ingrese Fecha Fin','250px'));
				$('#CampaniaLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });	
			} },
		{id:'txtCampaniDescripcion',required:true,isAlphaNumeric:true,errorRequiredFunction : function ( ) {
				$('#CampaniaLayerMessage').html(templates.MsgError('Ingrese Descripcion de Campa&ntilde;a','250px'));
				$('#CampaniaLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });	
			},errorAlphaNumericFunction : function ( ) {
				$('#CampaniaLayerMessage').html(templates.MsgError('Ingrese solo texto y numero','250px'));
				$('#CampaniaLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });	
			} }
	]);
	
	if( rs ) {
		var rsC=confirm("Verique que los datos ingresados son los correctos");
		if( rsC ) {
			DistribucionDAO.insertCampania();
		}
	}
}
cancel_campania = function ( ) {
	$('#dialogCampania').find(':text,textarea').val('');
}
agregar_cliente_distribucion_por_operador = function ( id ) {
	//var idClienteCartera=$('#table_asignacion_por_operador').jqGrid("getGridParam",'selrow');
	var codigo=$('#table_asignacion_por_operador').jqGrid("getRowData",id)['cli.codigo'];
	var cliente=$('#table_asignacion_por_operador').jqGrid("getRowData",id)['cliente'];
	
	var LENGTH_check_id=$('#tableDataClientesDistribucionPorOperador').find('tr[id='+id+']').length;
	//var LENGTH_tr=$('#tableDataClientesDistribucionPorOperador').find('tr').length;
	if( LENGTH_check_id==0 ){
		var html='';
		html+='<tr class="ui-widget-content" id="'+id+'">';
			html+='<td class="ui-state-default" style="width:25px;padding:3px 0;border:1px solid #E0CFC2;" align="center" ></td>';
			html+='<td align="center" style="width:60px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+codigo+'</td>';
			html+='<td align="center" style="width:250px;white-space:pre-line;padding:3px 0;border-bottom:1px solid #E0CFC2;">'+cliente+'</td>';
			html+='<td align="center" class="ui-state-default" style="width:25px;padding:3px 0;border-bottom:1px solid #E0CFC2;" ><span onclick="$(this).parent().parent().remove()" class="ui-icon ui-icon-closethick"></span></td>';
		html+='</tr>';
		$('#tableDataClientesDistribucionPorOperador').append(html);
	}
	
}
save_distribucion_por_operador = function ( ) {
	var ids=$('#tableDataClientesDistribucionPorOperador').find('tr').map(function(){
			return $(this).attr('id');
		}).get().join(",");
	
	var rs=validacion.check([
		{id:'cbOperadoresDistribucionPorOperador',isNotValue:0,errorNotValueFunction:function(){
				$('#layerMessage').html(templates.MsgError('Seleccione operador','400px'));
				$('#layerMessage').effect('pulsate',{},'slow',function( ) { $(this).empty(); } );
			}}
		]);
	
	var LENGTH_ids=$('#tableDataClientesDistribucionPorOperador').find('tr').length;
	if( LENGTH_ids==0 ) {
		$('#layerMessage').html(templates.MsgError('Seleccione clientes a gestionar','400px'));
				$('#layerMessage').effect('pulsate',{},'slow',function( ) { $(this).empty(); } );
		return false;
	}
	
	if( rs ){
		var rsC=confirm("Verifique si los operadores seleccionados son los correctos");
		if( rsC ) {
			DistribucionDAO.save_distribucion_por_operador(ids);
		}
	}
	
}

/*cargar_cartera_distribucion_por_operador = function ( idCampania ) {
	DistribucionDAO.ListCartera(idCampania,DistribucionDAO.FillCarteraDistribucionPorOperador);
}*/
reload_jqgrid_clientes_por_cartera = function ( idCartera ) {
	var idServicio=$('#hdCodServicio').val();
	$("#table_asignacion_por_operador").jqGrid('setGridParam',{url:'../controller/ControllerCobrast.php?command=distribucion&action=jqgrid_clientes_por_cartera&Cartera='+idCartera+'&Servicio='+idServicio}).trigger('reloadGrid');
}
/*cargar_cartera_distribucion_por_departamento = function ( idCampania ) {
	DistribucionDAO.ListCartera(idCampania,DistribucionDAO.FillCarteraDistribucionPorDepartamento);
}*/
/*cargar_cartera_distribucion_por_tramo = function ( idCampania ) {
	DistribucionDAO.ListCartera(idCampania,DistribucionDAO.FillCarteraDistribucionPorTramo);
}*/
cargar_departamento_distribucion_por_departamento = function ( idCartera ) {
	DistribucionDAO.ListarDepartamentos(idCartera,DistribucionDAO.FillDepartamentosDistribucionPorDepartamento);
	DistribucionDAO.ListarOperadoresCantidadGestion(idCartera,DistribucionDAO.TeleoperadoresDistribucionPorDepartamento);
	$('#lbCantidadClientesDisponibleDistribucionDepartamento').text('');
}
cargar_tramo_distribucion_por_tramo = function ( idCartera ) {
	DistribucionDAO.ListarTramos(idCartera,DistribucionDAO.FillTramosDistribucionPorTramos);
	DistribucionDAO.ListarOperadoresCantidadGestion(idCartera,DistribucionDAO.TeleoperadoresDistribucionPorTramo);
	$('#lbCantidadClientesDisponibleDistribucionTramo').text('');
}
cargar_tramo_distribucion_por_tramo_especial = function ( idCartera ) {
	//DistribucionDAO.ListarTramos(idCartera,DistribucionDAO.FillTramosDistribucionPorTramos);
	DistribucionDAO.ListarOperadoresCantidadGestion(idCartera,DistribucionDAO.TeleoperadoresDistribucionPorTramo);
	$('#lbCantidadClientesDisponibleDistribucionTramo').text('');
}
search_operadores_distribucion = function ( xtext, xidtable ) {
	var text = xtext;
	text = text.toUpperCase();
	$('#'+xidtable).find('tr').css('display','none');
	$('#'+xidtable+' tr').find('td:contains("'+text+'")').parent().css('display','block');
}
save_distribucion_por_departamento = function ( ) {
	var xidcartera = $('#cbCarteraDistribucionPorDepartamento').val();
	var xdepartamento = $('#cbDepartamento').val();
	var xoperadores = '['+$('#tableOperadoresDistribucionPorDepartamento').find(':checked').map(function(){
			return '{"operador":'+$(this).val()+'}';
		}).get().join(",")+']';
	if( xidcartera == 0 ) {
		alert("Seleccione Cartera");
		return false;
	}
	if( xdepartamento == 0 ) {
		alert("Seleccione Departamento");
		return false;
	}
	if( xoperadores == '[]' ) {
		alert("Seleccione Operadores");
		return false;
	}
	var rs = confirm("Verifique si los datos ingresados son los correctos");
	if( rs ) {
		DistribucionDAO.save_distribucion_por_departamento(xidcartera, xdepartamento, xoperadores);
	}
}
save_distribucion_por_tramo = function ( ) {
	var xidcartera = $('#cbCarteraDistribucionPorTramo').val();
	var xtramo = $('#cbTramo').val();
	var xmodo = $('#cbModoDistribucionPorTramo').val();
	var xoperadores = '['+$('#tableOperadoresDistribucionPorTramos').find(':checked').map(function(){
			return '{"operador":'+$(this).val()+'}';
		}).get().join(",")+']';
	if( xidcartera == 0 ) {
		alert("Seleccione Cartera");
		return false;
	}
	if( xtramo == 0 ) {
		alert("Seleccione Tramo");
		return false;
	}
	if( xoperadores == '[]' ) {
		alert("Seleccione Operadores");
		return false;
	}
	var rs = confirm("Verifique si los datos ingresados son los correctos");
	if( rs ) {
		DistribucionDAO.save_distribucion_por_tramo(xidcartera, xtramo, xoperadores, xmodo);
	}
}
cantidad_de_clientes_por_departamento = function ( xdepartamento ) {
	var xcartera = $('#cbCarteraDistribucionPorDepartamento').val();
	DistribucionDAO.ListarCantidadClientesPorDepartamento(xcartera,xdepartamento,function( obj ){ 
		$('#lbCantidadClientesDisponibleDistribucionDepartamento').text(obj[0].COUNT);
	});
}
cantidad_de_clientes_por_tramo = function ( xtramo ) {
	var xcartera = $('#cbCarteraDistribucionPorTramo').val();
	DistribucionDAO.ListarCantidadClientesPorTramo(xcartera,xtramo,function( obj ){ 
		$('#lbCantidadClientesDisponibleDistribucionTramo').text(obj[0].COUNT);
	});
}
cantidad_de_clientes_por_tramo_especial = function(xtramo){
	var xcartera = $('#cbCarteraDistribucionPorTramo').val();
	DistribucionDAO.ListarCantidadClientesPorTramoEspecial(xcartera,xtramo,function( obj ){ 
		$('#lbCantidadClientesDisponibleDistribucionTramo').text(obj[0].COUNT);
	});	
}
cargar_data_campos = function ( xvalue ) {
	var xidcartera = $('#cbCarteraDistribucionCampos').val();
	var xvalue = xvalue.split("|");
	var xcampo = xvalue[1];
	var xreferencia = xvalue[0];
	DistribucionDAO.ListarCampos( xidcartera, xcampo, xreferencia, function ( obj ) { 
		var html='';
		html+='<option value="0">--Seleccione--</option>';
		for( i=0;i<obj.length;i++ ) {
			html+='<option value="'+obj[i].campoT+'">'+obj[i].campoTMP+'</option>';
		}
		$('#cbCamposDistribucionCampos').html(html);
	} );
}
/*cargar_cartera_distribucion_por_campo = function ( idCampania ) {
	DistribucionDAO.ListCartera(idCampania,DistribucionDAO.FillCarteraDistribucionPorCampo);
}*/
/*cargar_cartera_distribucion_por_montos_iguales = function ( idCampania ) {
	DistribucionDAO.ListCartera(idCampania,DistribucionDAO.FillCarteraDistribucionMontosIguales);
}*/
/*cargar_cartera_distribucion_constante = function ( idCampania ) {
	DistribucionDAO.ListCartera(idCampania,DistribucionDAO.FillCarteraDistribucionConstante);
}
cargar_cartera_distribucion_sin_gestion = function ( idCampania ) {
	DistribucionDAO.ListCartera(idCampania,DistribucionDAO.FillCarteraDistribucionSinGestion);
}


cargar_cartera_registro_zona = function ( idCampania ) {
	DistribucionDAO.ListCartera(idCampania,DistribucionDAO.FillCarteraRegistroZona);
}

cargar_cartera_distribucion_especial = function ( idCampania ) {
	DistribucionDAO.ListCartera(idCampania,DistribucionDAO.FillCarteraDistribucionEspecial);
}*/
cargar_teleoperadores_distribucion_por_campos = function ( idCartera ) {
	DistribucionDAO.ListarOperadoresCantidadGestion(idCartera,DistribucionDAO.TeleoperadoresDistribucionPorCampos);
}
cargar_teleoperadores_distribucion_montos_iguales = function ( idCartera ) {
	DistribucionDAO.ListarOperadoresCantidadGestion(idCartera,DistribucionDAO.TeleoperadoresDistribucionMontosIguales);
}
cargar_teleoperadores_distribucion_constante = function ( idCartera ) {
	DistribucionDAO.ListarOperadoresCantidadGestion(idCartera,DistribucionDAO.TeleoperadoresDistribucionConstante);
}
cargar_teleoperadores_distribucion_sin_gestion = function ( idCartera ) {
	DistribucionDAO.ListarOperadoresCantidadGestion(idCartera,DistribucionDAO.TeleoperadoresDistribucionSinGestion);
}
cargar_teleoperadores_distribucion_mecanica = function  ( idCartera ) {
	DistribucionDAO.ListarOperadoresCantidadGestion(idCartera,DistribucionDAO.TeleoperadoresDistribucionMecanico);
}
cargar_teleoperadores_distribucion_pagos = function  ( idCartera ) {
	DistribucionDAO.ListarOperadoresCantidadGestion(idCartera,DistribucionDAO.TeleoperadoresDistribucionPagos);
}

carga_lista_data_campo = function ( ) {
	
	var xcartera = $('#cbCarteraDistribucionCampos').val();
	var xtabla = $('#cbGrupoDistribucionCampos option:selected').attr('label');
	var xcampo = $('#cbCamposDistribucionCampos').val();

	DistribucionDAO.ListarDataCampo( xcartera, xtabla, xcampo, function ( obj ) {

			var html='';
			html+='<option value="0">--Seleccione--</option>';
			for( i=0;i<obj.data.length;i++ ) {
				var data = eval('obj.data['+i+'].'+xcampo);
				html+='<option value="'+data+'">'+data+'</option>';
			}
			$('#cbDataCamposDistribucionCampos').html(html);
		} );
	
}
mostrar_cantidad_clientes_sin_gestionar = function ( ) {
	
	var xcartera = $('#cbCarteraDistribucionCampos').val();
	var xtabla = $('#cbGrupoDistribucionCampos option:selected').attr('label');
	var xcampo = $('#cbCamposDistribucionCampos').val();
	var xdato = $('#cbDataCamposDistribucionCampos').val();
	
	DistribucionDAO.MostrarCantidadClientesSinGestionar( xcartera, xtabla, xcampo, xdato, function ( obj ) { 
			
			$('#lbCantidadClientesDisponibleDistribucionCampo').text(obj.data[0]['COUNT']);
			$('#hdCodigoClienteDistribucionPorCampo').val(obj.clientes);
			
		} );
}
save_distribucion_por_campos = function ( ) {
	
	var xcartera = $('#cbCarteraDistribucionCampos').val();
	var xtabla = $('#cbGrupoDistribucionCampos option:selected').attr('label');
	var xcampo = $('#cbCamposDistribucionCampos').val();
	var xdato = $('#cbDataCamposDistribucionCampos').val();
	var xclientes = $('#hdCodigoClienteDistribucionPorCampo').val();
	var xoperadores = '['+$('#tableOperadoresDistribucionPorCampos').find(':checked').map(function(){
			return '{"operador":'+$(this).val()+'}';
		}).get().join(",")+']';
		
	if( xoperadores == '[]' ) {
		alert("Seleccione operadores");
		return false;
	}
		
	DistribucionDAO.save_distribucion_por_campos( xcartera, xtabla, xcampo, xdato, xoperadores, xclientes, function ( obj ) {
			
			if( obj.rst ) {
				$('#cbCarteraDistribucionCampos').trigger('change');
				$('#hdCodigoClienteDistribucionPorCampo').val('');
				$('#cbGrupoDistribucionCampos').val(0);
				$('#cbCamposDistribucionCampos').val(0);
				$('#cbDataCamposDistribucionCampos').val(0);
				
			}else{
				
			}
			
		} );
	
}
reload_jqgrid_especial = function ( xcartera ) {
	var servicio = $('#hdCodServicio').val();
	$('#table_clientes_distribucion_especial').jqGrid('setGridParam',{url:'../controller/ControllerCobrast.php?command=distribucion&action=jqgrid_clientes&Cartera='+xcartera+'&Servicio='+servicio}).trigger('reloadGrid');
}
reload_jqgrid_especial_asignados = function ( xusuario_servicio ) {
	var xcartera = $('#cbCarteraDistribucionEspecial').val();
	var servicio = $('#hdCodServicio').val();
	$('#table_clientes_asignados_distribucion_especial').jqGrid('setGridParam',{url:'../controller/ControllerCobrast.php?command=distribucion&action=jqgrid_clientes_especiales_asignados&Cartera='+xcartera+'&Servicio='+servicio+'&UsuarioServicio='+xusuario_servicio}).trigger('reloadGrid');
}
save_cliente_distribucion_especial = function ( ) {
	var xusuario_servicio = $('#cbOperadorDistribucionEspecial').val();
	var xcliente_cartera=$('#table_clientes_distribucion_especial').getGridParam('selrow');
	
	if( xcliente_cartera == null ) {
		return false;
	}
	
	DistribucionDAO.save_cliente_distribucion_especial( xcliente_cartera, xusuario_servicio, function ( obj ) {
			
			if( obj.rst ) {
				$('#cbCarteraDistribucionEspecial,#cbOperadorDistribucionEspecial').trigger('change');
			}else{
				
			}
			
		});
	
}
delete_cliente_distribucion_especial = function ( ) {
	var xcliente_cartera=$('#table_clientes_asignados_distribucion_especial').getGridParam('selrow');
	
	if( xcliente_cartera == null ) {
		return false;
	}
	
	DistribucionDAO.delete_cliente_distribucion_especial( xcliente_cartera, function ( obj ) {
			
			if( obj.rst ) {
				$('#cbCarteraDistribucionEspecial,#cbOperadorDistribucionEspecial').trigger('change');
			}else{
				
			}
			
		});
	
}
//cargar_operadores_distribucion_por_departamento = function ( idCartera ) {
//	DistribucionDAO.ListarOperadoresCantidadGestion(idCartera,DistribucionDAO.TeleoperadoresDistribucionPorDepartamento);
//}
//cargar_operadores_distribucion_por_tramo = function ( idCartera ) {
//	DistribucionDAO.ListarOperadoresCantidadGestion(idCartera,DistribucionDAO.TeleoperadoresDistribucionPorTramo);
//}
check_all_table = function ( ischeck, idTable ) {
	if( ischeck ) {
		$('#'+idTable+' tr').find(':checkbox').attr('checked',true);
	}else{
		$('#'+idTable+' tr').find(':checkbox').attr('checked',false);
	}
}
save_distribucion_montos_iguales = function ( ) {
	var cartera = $('#cbCarteraDistribucionMontosIguales').val();
	var zona = $('#cbZonaDistribucionMontosIguales').val();
	var operadores = '['+$('#tableOperadoresDistribucionMontosIguales').find(':checked').map(function( ) {
			return '{"operador":'+$(this).val()+'}';
		}).get().join(",")+']';
		
	if( cartera == 0 ) {
		alert("Seleccione cartera");
		return false;
	}
	/*if( zona == 0 ) {
		alert("Seleccione zona");
		return false;
	}*/
	if( operadores == '[]' ) {
		alert("Seleccione operadores");
		return false;
	}
	DistribucionDAO.distribucion_montos_iguales(cartera, zona, operadores, function ( obj ){
			if( obj.rst ) {
				$('#cbCarteraDistribucionMontosIguales').trigger('change');
				$('#layerMessage').html(templates.MsgInfo(obj.msg,'400px'));
				$('#layerMessage').effect('pulsate',{},'slow',function( ) { $(this).empty(); } );
			}else{
				$('#layerMessage').html(templates.MsgError(obj.msg,'400px'));
				$('#layerMessage').effect('pulsate',{},'slow',function( ) { $(this).empty(); } );
			}
		});
}
listar_departamentos_zonas = function ( ) {
	
	var idservicio = $('#hdCodServicio').val();
	
	DistribucionDAO.listar_departamentos_zonas( idservicio, function ( obj ) {
			var html = '';
			var option = '';
				//option += '<option value="0">--Seleccione--</option>';
			for( i=0;i<obj.length;i++ ) {
				html+='<tr id="'+obj[i].idzona+'">';
					html+='<td align="center" class="ui-widget-header" style="padding:3px 0;width:30px;">'+(i+1)+'</td>';
					html+='<td align="center" class="ui-widget-content" style="padding:3px 0;width:300px;">'+obj[i].departamento+'</td>';
					html+='<td align="center" class="ui-widget-content" style="padding:3px 0;width:150px;"><input class="cajaForm" style="width:100px;" type="text" value="'+obj[i].zona+'" ></td>';
				html+='</tr>';
				//option += '<option value="'+obj[i].zona+'">'+obj[i].zona+'</option>';
			}
			$('#table_zonas').html(html);
			//$('#cbZonaDistribucionMontosIguales').html(option);
		} );
	
}
guardar_departamentos_a_zona = function ( ) {
	
	var idservicio = $('#hdCodServicio').val();
	var idcartera = $('#cbCarteraRegistroZona').val();
	var usuario_creacion = $('#hdCodUsuario').val();
	
	DistribucionDAO.grabar_departamentos_zonas( idservicio, idcartera, usuario_creacion, function ( obj ) {
			
			if( obj.rst ) {
				listar_departamentos_zonas();
				$('#cbCarteraDistribucionMontosIguales').trigger('change');
			}else{
				
			}
			
		} );
	
}
guardar_zonas = function ( ) {
	
	var idcartera = $('#cbCarteraRegistroZona').val();
	var data = '['+$('#table_zonas').find('tr').map(function( ) {
			return '{"idzona":"'+$(this).attr('id')+'","zona":"'+$.trim( $(this).find(':text').val() )+'"}';
		}).get().join(",")+']';
	
	DistribucionDAO.grabar_zonas( idcartera, data, function ( obj ) {
			if( obj.rst ) {
				$('#layerMessage').html(templates.MsgInfo(obj.msg,'400px'));
			}else{
				$('#layerMessage').html(templates.MsgError(obj.msg,'400px'));
			}
		} );
	
}
listar_zonas = function ( xidcartera ) {
	
	DistribucionDAO.ListarZonas( xidcartera, function ( obj ) {
			var html = '';
				html+='<option value="0">--Seleccione--</option>';
			for( i=0;i<obj.length;i++ ) {
				html+='<option value="'+obj[i].zona+'">'+obj[i].zona+'</option>';
			}
			$('#cbZonaDistribucionMontosIguales').html(html);
		} );
	
}
CantidadClientesSinAsignarZonas = function ( xzona ) {
	var idcartera = $('#cbCarteraDistribucionMontosIguales').val();
	
	DistribucionDAO.CantidadClientesSinAsignarZonas( idcartera, xzona, function ( obj ) {
			$('#lbCantidadClientesSinAsignarZona').text(obj[0].COUNT);
		} );
			
}

CantidadClientesSinAsignarCartera = function () {
	var idcartera = $('#cbCarteraDistribucionMontosIguales').val();
	
	DistribucionDAO.CantidadClientesSinAsignarCartera( idcartera, function ( obj ) {
			$('#lbCantidadClientesSinAsignarZona').text(obj[0].COUNT);
		} );
			
}
CantidadCuentasPorCartera = function () {
	var idcartera = $('#cbCarteraDistribucionMontosIguales').val();
	
	DistribucionDAO.CantidadCuentasPorCartera( idcartera, function ( obj ) {
			$('#lbCantidadCuentasPorCartera').text(obj[0].COUNT);
		} );
			
}

LISTAR_CARTERAS_SERVICIO = function ( ) {
	
	var idservicio = $('#hdCodServicio').val();
	
	DistribucionDAO.CarterasServicio( idservicio, function ( obj ) {
			var html = '';
				html+='<option value="0">--Seleccione--</option>';
			for( i=0;i<(obj.length-1);i++ ) {
				html+='<option value="'+obj[i].idcartera+'">'+obj[i].nombre_cartera+'</option>';
			}
			$('#cbCarteraReferenciaDistribucionConstante').html(html);
		} );
	
}
GUARDAR_DISTRIBUCION_CONSTANTE = function ( ) {
	
	var idcartera = $('#cbCarteraDistribucionConstante').val();
	var idcartera_referencia = $('#cbCarteraReferenciaDistribucionConstante').val();
	var operadores = '['+$('#tableOperadoresDistribucionConstante').find(':checked').map(function( ){
			return '{"operador":'+$(this).val()+'}';
		}).get().join(",")+']';
	
	DistribucionDAO.distribucion_constante( idcartera, idcartera_referencia, operadores , function ( obj ) {
			if( obj.rst ) {
				$('#cbCarteraDistribucionConstante').trigger('change');
				$('#layerMessage').html(templates.MsgInfo(obj.msg,'400px'));
				$('#layerMessage').effect('pulsate',{},'slow',function( ) { $(this).empty(); } );
			}else{
				$('#layerMessage').html(templates.MsgError(obj.msg,'400px'));
				$('#layerMessage').effect('pulsate',{},'slow',function( ) { $(this).empty(); } );
			}
		} );
	
}
CANTIDAD_CLIENTES_SIN_ASIGNAR_DCONSTANTE = function ( idcartera ) {
	
	DistribucionDAO.CantidadClientesSinAsignarConstante(idcartera, function ( obj ) {
			
			$('#lbCantidadClientesSinAsignarConstante').text(obj[0]['COUNT']);
			
		});
	
}
ELIMINAR_TODO_CLIENTE_USUARIO = function ( idusuario_servicio, idcartera ) {
	
	DistribucionDAO.delete_all_clientes( idusuario_servicio, idcartera, function ( obj ) {
			if( obj.rst ) {
				$('#cbCarteraRetirarCliente').trigger('change');
				$('#layerMessage').html(templates.MsgInfo(obj.msg,'400px'));
				$('#layerMessage').effect('pulsate',{},'slow',function( ) { $(this).empty(); } );
			}else{
				$('#layerMessage').html(templates.MsgError(obj.msg,'400px'));
				$('#layerMessage').effect('pulsate',{},'slow',function( ) { $(this).empty(); } );
			}
			
		} );
	
}
GUARDAR_DISTRIBUCION_SIN_GESTION = function ( ) {
	
	var idcartera = $('#cbCarteraDistribucionSinGestion').val();
	var operadores = '['+$('#tableOperadoresDistribucionSinGestion').find(':checked').map(function( ){
			return '{"operador":'+$(this).val()+'}';
		}).get().join(",")+']';
	
	DistribucionDAO.distribucion_sin_gestion( idcartera, operadores , function ( obj ) {
			if( obj.rst ) {
				$('#cbCarteraDistribucionSinGestion').trigger('change');
				$('#layerMessage').html(templates.MsgInfo(obj.msg,'400px'));
				$('#layerMessage').effect('pulsate',{},'slow',function( ) { $(this).empty(); } );
			}else{
				$('#layerMessage').html(templates.MsgError(obj.msg,'400px'));
				$('#layerMessage').effect('pulsate',{},'slow',function( ) { $(this).empty(); } );
			}
		} );
	
}
CANTIDAD_CLIENTES_SIN_ASIGNAR_SIN_GESTION = function ( idcartera ) {
	
	DistribucionDAO.CantidadClientesSinAsignarSinGestion(idcartera, function ( obj ) {
			
			$('#lbCantidadClientesSinAsignarSinGestion').text(obj[0]['COUNT']);
			
		});
	
}
CANTIDAD_CLIENTES_SIN_ASIGNAR_DISTRIBUCION_PAGOS = function ( ) {
	
	var idcartera = $('#cbCarteraDistribucionPagos').val();
	if( idcartera == '0' ) {
		alert("Seleccione cartera");
		return false;
	}
	
	var data = $(':checkbox[name^="ckbDistribucionPagos"]:checked').map(
			function ( ) 
			{
				return $(this).attr('title');
			}
		).get().join(",");
	
	DistribucionDAO.CantidadClientesSinAsignarDistrPagos( idcartera, data, 
		function ( obj ) 
		{
			$('#lbCantidadClientesSinAsignarDistribucionPagos').text(obj[0]['COUNT']);
		}
	);

}
save_distribucion_pagos = function ( ) {
	
	var idcartera = $('#cbCarteraDistribucionPagos').val();
	var modo = $('#cbModoDistribucionPagos').val();
	if( idcartera == '0' ) {
		alert("Seleccione cartera");
		return false;
	}

	var data = $(':checkbox[name^="ckbDistribucionPagos"]:checked').map(
			function ( ) 
			{
				return $(this).attr('title');
			}
		).get().join(",");
		
	var operadores = '['+$('#tableOperadoresDistribucionPagos').find(':checked').map(function( ) {
			return '{"operador":'+$(this).val()+'}';
		}).get().join(",")+']';
		
	if( operadores == '[]' ) {
		alert("Seleccione operadores");
		return false;
	}
	
	var rs = confirm("Verificar si los datos ingresados son los correctos");

	if( rs ) {
		
		DistribucionDAO.save_distribucion_pagos( idcartera, operadores, modo, data, 
			function ( obj )
			{
				if( obj.rst ) {
					$('#layerMessage').html(templates.MsgInfo(obj.msg,'400px'));
					$('#cbCarteraDistribucionPagos').trigger('change');
				}else{
					$('#layerMessage').html(templates.MsgError(obj.msg,'400px'));
				}
				$('#layerMessage').fadeIn().delay(10000).fadeOut();
			}
		);
		
	}
	
}
save_distribucion_mecanica = function  ( ) {

	var idcartera = $('#cbCarteraDistribucionMecanico').val();
	var file = $('#archivoDistribucionMecanica').val();
	var modo = $('#cbModoDistribucionMecanico').val();
	var data_generate = '['+$('#trHeaderDistribucionMecanico select').find('option:selected').not('option[value="0"]').map( 
		function ( ) {
			return '{"campoT":"'+$(this).parent().attr('id')+'","dato":"'+$(this).val()+'"}';
		} ).get().join(",")+']';
	var operadores = '['+$('#tableOperadoresDistribucionMecanica').find(':checked').map(function( ) {
			return '{"operador":'+$(this).val()+'}';
		}).get().join(",")+']';
	var separador = $('#cbSeparadorDistribucionMecanico').val();

	var rs = confirm("Verificar si los datos ingresados son los correctos");

	if( rs ) {
		DistribucionDAO.save_distribucion_mecanica( idcartera, operadores, modo, separador, data_generate, file, 
			function ( obj ) {
				if( obj.rst ) {
					$('#layerMessage').html(templates.MsgInfo(obj.msg,'400px'));
					$('#cbCarteraDistribucionMecanico').trigger('change');
				}else{
					$('#layerMessage').html(templates.MsgError(obj.msg,'400px'));
				}
				$('#layerMessage').fadeIn().delay(10000).fadeOut();
			} 
		);
	}

}
upload_file_distribucion_mecanica = function ( ) {

	$('#fileDistribucionMecanica').upload(
											'../controller/ControllerCobrast.php',
											{
											command:'carga-cartera',
											action:'upload_file_distribuion_mecanica',
											Servicio:$('#hdCodServicio').val(),
											Separador : $('#cbSeparadorDistribucionMecanico').val(),
											UsuarioCreacion:$('#hdCodUsuario').val(),
											NombreServicio:$('#hdNomServicio').val(),
											CaracterSeparador : $('#cbSeparadorDistribucionMecanico').val()
											},
											function(obj){
												if( obj.rst ){
													$('#archivoDistribucionMecanica').val(obj.file);
													load_header_file_distribucion_automatica(obj.file);
												}else{
													DistribucionDAO.error_ajax();
												}
											},
											'json'
											);

}
load_header_file_distribucion_automatica = function ( file ) {

	//var file = $('#archivoDistribucionMecanica').val();
	var separador = $('#cbSeparadorDistribucionMecanico').val();

	DistribucionDAO.LoadHeaderFile( file, separador, 
		function ( obj ) {
			var html = '';
				html+='<option value="0">--Seleccione--</option>';
			for( i=0;i<obj.header.length;i++ ) {
				html+='<option value="'+obj.header[i]+'">'+obj.header[i]+'</option>';
			}
			$('#trHeaderDistribucionMecanico select').html(html);
		}, 
		function (){

		} 
	);

}
link_exportar_clientes_sin_gestionar = function ( ) {
	var servicio = $('#hdCodServicio').val();
	var cartera = $('#cbCarteraClientesGestSinGest').val();
	if( cartera == 0 ) {
		return false;
	}
	window.location.href = "../rpt/excel/clientes_sin_gestion.php?servicio="+servicio+"&cartera="+cartera;
}
link_exportar_clientes_gestionados = function ( ) {
	var servicio = $('#hdCodServicio').val();
	var cartera = $('#cbCarteraClientesGestSinGest').val();
	if( cartera == 0 ) {
		return false;
	}
	window.location.href = "../rpt/excel/clientes_gestionados.php?servicio="+servicio+"&cartera="+cartera;
}
search_text_table = function ( xtext, idtable ) {
	var text = $.trim( xtext );
	text = text.toUpperCase();
	$('#'+idtable+' tr').css('display','none');
	$('#'+idtable+' tr').find('td:contains("'+text+'")').parent().css('display','block');
	
}
