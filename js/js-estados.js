// JavaScript Document
$(document).ready(function (){
	$('#layerDatepicker').datepicker({inline:true,autoSize:true});
	/*******/
	EstadosPrioridadJQGRID.estados();
	EstadosPrioridadJQGRID.prioridad();
	/*******/
	EstadoPrioridadDAO.ListarTipoTransaccion();
	/*******/
	$('#dialogPrioridad').dialog({
								height : 150,
								autoOpen : false,
								width : 350 ,
								title : 'Crear prioridad',
								buttons : {
										Cancel : function ( ) {
												$(this).dialog('close');
												cancel_prioridad();
											},
										Grabar : function ( ) {
												save_prioridad();
											},
										Actualizar : function ( ) {
												update_prioridad();
											},
										Eliminar : function ( ) {
												delete_prioridad();
											}
									}
							  });
	/*******/
	$('#dialogUsuario').dialog({
								height : 400,
								autoOpen : false,
								width : 280 ,
								title : 'Crear Usuario',
								modal : true,
								buttons : {
										Cancel : function ( ) {
												$(this).dialog('close');
											},
										Grabar : function ( ) {
												//save_usuario();
												//ServicioDAO.insertUsuario();
											}
									}
							  });
});
save_estado = function ( ) {
	var rs=validacion.check([
		{id:'tFormTableEstado #cbTipoTransaccion',isNotValue:0,errorNotValueFunction:function ( ) {
				$('#'+EstadoPrioridadDAO.idLayerMessage).html(templates.MsgError('Seleccione tipo de estado','400px'));
				EstadoPrioridadDAO.setTimeOut_hide_message();
			}},
		{id:'tFormTableEstado #txtNombreEstado',required:true,errorRequiredFunction:function( ){
				$('#'+EstadoPrioridadDAO.idLayerMessage).html(templates.MsgError('Ingrese nombre de estado','400px'));
				EstadoPrioridadDAO.setTimeOut_hide_message();
			}},
		{id:'tFormTableEstado #txtPesoEstado',required:true,errorRequiredFunction:function( ){
				$('#'+EstadoPrioridadDAO.idLayerMessage).html(templates.MsgError('Ingrese peso de estado','400px'));
				EstadoPrioridadDAO.setTimeOut_hide_message();
			}}
		]);
	
	if( rs ) {
		var rsC=confirm("Verifique si los datos ingresados son los correctos");
		if( rsC ) {
			EstadoPrioridadDAO.Estados.saveEstado('#tFormTableEstado',function(){ $('#table_estado').jqGrid().trigger('reloadGrid');cancel_estado(); });
		}
	}
}
update_estado = function ( ) {
	var rs=validacion.check([
		{id:'tFormTableEstado #IdEstado',required:true,errorNotValueFunction:function ( ) {
				$('#'+EstadoPrioridadDAO.idLayerMessage).html(templates.MsgError('Seleccione estado a actualizar','400px'));
				EstadoPrioridadDAO.setTimeOut_hide_message();
			}},
		{id:'tFormTableEstado #cbTipoTransaccion',isNotValue:0,errorNotValueFunction:function ( ) {
				$('#'+EstadoPrioridadDAO.idLayerMessage).html(templates.MsgError('Seleccione tipo de transaccion','400px'));
				EstadoPrioridadDAO.setTimeOut_hide_message();
			}},
		{id:'tFormTableEstado #txtNombreEstado',required:true,errorRequiredFunction:function( ){
				$('#'+EstadoPrioridadDAO.idLayerMessage).html(templates.MsgError('Ingrese nombre de estado','400px'));
				EstadoPrioridadDAO.setTimeOut_hide_message();
			}},
		{id:'tFormTableEstado #txtPesoEstado',required:true,errorRequiredFunction:function( ){
				$('#'+EstadoPrioridadDAO.idLayerMessage).html(templates.MsgError('Ingrese peso de estado','400px'));
				EstadoPrioridadDAO.setTimeOut_hide_message();
			}}
		]);
	
	if( rs ) {
		var rsC=confirm("Verifique si los datos ingresados son los correctos");
		if( rsC ) {
			EstadoPrioridadDAO.Estados.updateEstado('#tFormTableEstado',function(){ $('#table_estado').jqGrid().trigger('reloadGrid');cancel_estado(); });
		}
	}
}
delete_estado = function ( ) {
	var rs=confirm("Desea eliminar estado seleccionado");
	if( rs ) {
		EstadoPrioridadDAO.Estados.deleteEstado('#tFormTableEstado',function(){ $('#table_estado').jqGrid().trigger('reloadGrid');cancel_estado(); });
	}
}
cancel_estado = function ( ) {
	$('#tFormTableEstado').find(':hidden,:text,textarea').not('#tFormTableEstado #txtUsuarioCreacion').val('');
	$('#tFormTableEstado').find('select').val(0);
}
getParamEstadoEdit = function ( ) {
	var idEstado=$("#table_estado").jqGrid("getGridParam",'selrow');
	EstadoPrioridadDAO.Estados.getParam(idEstado,EstadoPrioridadDAO.Fill.EstadoForm);
	
}
save_prioridad = function ( ) {
	var idEstado=$("#table_estado").jqGrid("getGridParam",'selrow');
	if( idEstado==null ){
		$('#'+EstadoPrioridadDAO.idLayerMessage).html(templates.MsgError('Seleccione estado','400px'));
		EstadoPrioridadDAO.setTimeOut_hide_message();
	}
	var rs=validacion.check([
		{id:'dialogPrioridad #txtPesoPrioridad',required:true,errorRequiredFunction:function(){
				$('#'+EstadoPrioridadDAO.idLayerMessage).html(templates.MsgError('Ingrese prioridad','400px'));
				EstadoPrioridadDAO.setTimeOut_hide_message();
			}}
		]);
	
	if( rs ) {
		var rsC=confirm("Verifique si los datos ingresados son los correctos");
		if( rsC ) {
			EstadoPrioridadDAO.Prioridad.savePrioridad('dialogPrioridad',idEstado,function ( ) { $('#table_prioridad').jqGrid().trigger('reloadGrid');cancel_prioridad(); });
		}
	}
}
update_prioridad = function ( ) {
	var idEstado=$("#table_estado").jqGrid("getGridParam",'selrow');
	var rs=validacion.check([
		{id:'dialogPrioridad #HdIdPrioridad',required:true,errorRequiredFunction:function(){
				$('#'+EstadoPrioridadDAO.idLayerMessage).html(templates.MsgError('Seleccione prioridad a actualizar','400px'));
				EstadoPrioridadDAO.setTimeOut_hide_message();
			}},
		{id:'dialogPrioridad #txtPesoPrioridad',required:true,errorRequiredFunction:function(){
				$('#'+EstadoPrioridadDAO.idLayerMessage).html(templates.MsgError('Ingrese prioridad','400px'));
				EstadoPrioridadDAO.setTimeOut_hide_message();
			}}
		]);
	
	if( rs ) {
		var rsC=confirm("Verifique si los datos ingresados son los correctos");
		if( rsC ){
			EstadoPrioridadDAO.Prioridad.updatePrioridad('dialogPrioridad',idEstado,function ( ) { $('#table_prioridad').jqGrid().trigger('reloadGrid');cancel_prioridad(); });
		}
	}
}
delete_prioridad = function ( ) {
	var rs=confirm("Desea eliminar a prioridad seleccionada");
	if( rs ) {
		EstadoPrioridadDAO.Prioridad.deletePrioridad('dialogPrioridad',function ( ) { $('#table_prioridad').jqGrid().trigger('reloadGrid');cancel_prioridad(); });
	}
}
cancel_prioridad = function ( ) {
	$('#dialogPrioridad').find(':hidden,:text').val('');
}
reloadJQGRID_prioridades = function ( idEstado ) {
	$("#table_prioridad").jqGrid('setGridParam',{url:'../controller/ControllerCobrast.php?command=estado_prioridad&action=jqgrid_prioridad&EstadoTransaccion='+idEstado}).trigger('reloadGrid');
	$('#dialogPrioridad').dialog('close');
}
display_form_prioridad = function ( ) {
	var idEstado=$("#table_estado").jqGrid("getGridParam",'selrow');
	if( idEstado!=null ) {
		$('#dialogPrioridad').dialog('open');
	}
}
getParamEditPrioridad = function ( ) {
	var id=$("#table_prioridad").jqGrid("getGridParam",'selrow');
	if( id!=null ) {
		EstadoPrioridadDAO.Prioridad.getParam(id,EstadoPrioridadDAO.Fill.PrioridadForm);
	}
}

