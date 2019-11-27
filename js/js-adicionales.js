$(document).ready(function(){
	AdicionalesJQGRID.tipo_final();
	AdicionalesJQGRID.carga_final();
	AdicionalesJQGRID.clase_final();
	AdicionalesJQGRID.nivel();
	AdicionalesJQGRID.tipo_gestion();
	
	AdicionalesJQGRID.finales();
    AdicionalesJQGRID.finalesxservicio( $('#hdCodUsuario').val() );
	
	AdicionalesDAO.loadTipoFinal();
	AdicionalesDAO.loadCargaFinal();
	AdicionalesDAO.loadClaseFinal();
	AdicionalesDAO.loadNivelFinal();

	$('#layerDatepicker').datepicker({inline:true,autoSize:true});
	
	$('#dialogFinal').dialog({
							  	height : 300,
								autoOpen : false,
								width : 400 ,
								title : 'Crear Final',
								modal : false,
								buttons : {
										Cancel : function ( ) {
												$(this).dialog('close');
												cancel_dialog_final();
											},
										Grabar : function ( ) {
												save_final();
											},
										Actualizar : function ( ) {
												update_final();
											}
									}
							  });
							  
	$('#dialogFinalServicio').dialog({
							  	height : 230,
								autoOpen : false,
								width : 410 ,
								title : 'Agregar Final',
								modal : false,
								buttons : {
										Cancel : function ( ) {
												$(this).dialog('close');
												cancel_dialog_final_servicio();
											},
										Grabar : function ( ) {
												save_final_servicio();
											}
									}
							  });
	
	/**********************/
	
	
});
getParamEdit = function ( ) {
	var id=$('#table_final').jqGrid("getGridParam",'selrow');
	if( id==null ){
		return false;
	}
	AdicionalesDAO.DataFinal(id,AdicionalesDAO.FillFormDialogFinal);
}
show_dialog_final = function ( ) {
	$('#dialogFinal').dialog('open');
}
cancel_dialog_final = function ( ) {
	$('#dialogFinal').find(':text,textarea,:hidden').val('');
	$('#dialogFinal').find('select').val(0);
}
save_final = function ( ) {
	var rs=validacion.check([
		{id:'txtNombreFinal',required:true,errorRequiredFunction:function(){
				$('#'+AdicionalesDAO.idLayerMessage).html(templates.MsgInfo('Ingrese Nombre','400px'));
				AdicionalesDAO.setTimeOut_hide_message();
			}},
//		{id:'cbTipoFinal',isNotValue:0,errorNotValueFunction:function(){
//				$('#'+AdicionalesDAO.idLayerMessage).html(templates.MsgInfo('Seleccione tipo','400px'));
//				AdicionalesDAO.setTimeOut_hide_message();
//			}},
		{id:'cbCargaFinal',isNotValue:0,errorNotValueFunction:function(){
				$('#'+AdicionalesDAO.idLayerMessage).html(templates.MsgInfo('Seleccione carga','400px'));
				AdicionalesDAO.setTimeOut_hide_message();
			}},
		{id:'cbClaseFinal',isNotValue:0,errorNotValueFunction:function(){
				$('#'+AdicionalesDAO.idLayerMessage).html(templates.MsgInfo('Seleccione clase','400px'));
				AdicionalesDAO.setTimeOut_hide_message();
			}}//,
//		{id:'cbNivelFinal',isNotValue:0,errorNotValueFunction:function(){
//				$('#'+AdicionalesDAO.idLayerMessage).html(templates.MsgInfo('Seleccione nivel','400px'));
//				AdicionalesDAO.setTimeOut_hide_message();
//			}}
		]);
	if( rs ){
		var rsC=confirm("Verifique si los datos ingresados son los correctos");
		if( rsC ){
			AdicionalesDAO.insertFinal();
		}
	}
}
update_final = function ( ) {
	var rs=validacion.check([
		{id:'hdIdFinal',required:true,errorRequiredFunction:function(){
				$('#'+AdicionalesDAO.idLayerMessage).html(templates.MsgInfo('Seleccione final a actualizar','400px'));
				AdicionalesDAO.setTimeOut_hide_message();
			}},
		{id:'txtNombreFinal',required:true,errorRequiredFunction:function(){
				$('#'+AdicionalesDAO.idLayerMessage).html(templates.MsgInfo('Ingrese Nombre','400px'));
				AdicionalesDAO.setTimeOut_hide_message();
			}},
//		{id:'cbTipoFinal',isNotValue:0,errorNotValueFunction:function(){
//				$('#'+AdicionalesDAO.idLayerMessage).html(templates.MsgInfo('Seleccione tipo','400px'));
//				AdicionalesDAO.setTimeOut_hide_message();
//			}},
		{id:'cbCargaFinal',isNotValue:0,errorNotValueFunction:function(){
				$('#'+AdicionalesDAO.idLayerMessage).html(templates.MsgInfo('Seleccione carga','400px'));
				AdicionalesDAO.setTimeOut_hide_message();
			}},
		{id:'cbClaseFinal',isNotValue:0,errorNotValueFunction:function(){
				$('#'+AdicionalesDAO.idLayerMessage).html(templates.MsgInfo('Seleccione clase','400px'));
				AdicionalesDAO.setTimeOut_hide_message();
			}}//,
//		{id:'cbNivelFinal',isNotValue:0,errorNotValueFunction:function(){
//				$('#'+AdicionalesDAO.idLayerMessage).html(templates.MsgInfo('Seleccione nivel','400px'));
//				AdicionalesDAO.setTimeOut_hide_message();
//			}}
		]);
	if( rs ) {
		var rsC=confirm("Verifique si los datos ingresados son los correctos");
		if( rsC ){
			AdicionalesDAO.updateFinal();
		}
	}
}
open_dialog_final_servicio = function ( ) {
	var id=$('#table_final').jqGrid("getGridParam",'selrow');
	var estado = $('#table_final').jqGrid("getRowData",id)['fin.nombre'];
	var clase = $('#table_final').jqGrid("getRowData",id)['clafin.nombre'];
	if( id==null ) {
		return false;
	}
	$('#hdServicioIdFinal').val(id);
	$('#tdServicioFinalNombre').html(estado);
	$('#tdServicioClaseNombre').text(clase);
	$('#dialogFinalServicio').dialog('open');
}
save_final_servicio = function ( ) {
	var id=$('#hdServicioIdFinal').val();
	var peso = $.trim( $('#txtPesoFinal').val() );
	var prioridad = $.trim( $('#txtPrioridadFinal').val() );
	var efecto = $.trim( $('#txtEfectoFinal').val() );
	
	if( prioridad == '' ) {
		$('#'+AdicionalesDAO.idLayerMessage).html(templates.MsgInfo('Ingrese Prioridad','400px'));
		AdicionalesDAO.setTimeOut_hide_message();
		return false;
	}
	
	var rsC=confirm("Verifique si los datos ingresados son los correctos");
	if( rsC ){
		AdicionalesDAO.insertFinalxService(id,prioridad,peso,efecto);
	}
}
cancel_dialog_final_servicio = function ( ) {
	$('#dialogFinalServicio').find(':text,:hidden').val('');
	$('#dialogFinalServicio').find('#tdServicioFinalNombre,#tdServicioClaseNombre').text('');
	$('#dialogFinalServicio').dialog('close');
}
delete_final_servicio = function ( ) {
	var id=$('#table_final_servicios').jqGrid("getGridParam",'selrow');
	if( id==null ) {
		return false;
	}
	var rsC=confirm("Desea eliminar el registro seleccionado");
	if( rsC ){
		AdicionalesDAO.deleteFinalServicio(id);
	}
}

