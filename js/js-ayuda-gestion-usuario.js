$(document).ready(function ( ) {
	$('#layerDatepicker').datepicker({inline:true,autoSize:true});
	/*******/
	AyudaGestionUsuarioDAO.ListarUsuarioServicio();
	AyudaGestionUsuarioDAO.ListarCampanias();
	
} );
listar_cartera = function ( id ) {
	AyudaGestionUsuarioDAO.ListarCartera(id,AyudaGestionUsuarioDAO.FillCartera);
}
load_data_usuarios_ayudar = function ( ) {
	AyudaGestionUsuarioDAO.ListarUsuariosAyudar();
	AyudaGestionUsuarioDAO.ListarUsuariosAsignar();
}
save_usuarios_asignar = function ( ) {
	
	var ids=$('#LayerTableUsuariosAsignar #DataLayerTableUsuariosAsignar').find(':checked').map(function ( ) {
		return $(this).val();
	}).get().join(",");
	
	var idsLENGTH=$('#LayerTableUsuariosAsignar #DataLayerTableUsuariosAsignar').find(':checked').length;
	
	if( idsLENGTH==0 ){
		$('#'+AyudaGestionUsuarioDAO.idLayerMessage).html(templates.MsgError('Seleccione usuarios','400px'));
		AyudaGestionUsuarioDAO.setTimeOut_hide_message();
		return false;
	}
	
	var rs=confirm("Verifique si los usuarios seleccionados son los correctos");
	if( rs ){
		AyudaGestionUsuarioDAO.SaveUsuarioAyudar(ids);
	}
}
delete_usuarios_asignados = function ( ) {
	
	var ids=$('#LayerTableUsuariosAyudar #DataLayerTableUsuariosAyudar').find(':checked').map(function ( ) {
		return $(this).val();
	}).get().join(",");
	
	var idsLENGTH=$('#LayerTableUsuariosAyudar #DataLayerTableUsuariosAyudar').find(':checked').length;
	
	if( idsLENGTH==0 ) {
		$('#'+AyudaGestionUsuarioDAO.idLayerMessage).html(templates.MsgError('Seleccione usuarios','400px'));
		AyudaGestionUsuarioDAO.setTimeOut_hide_message();
		return false;
	}
	
	var rs=confirm("Verifique si los usuarios seleccionados son los correctos");
	if( rs ){
		AyudaGestionUsuarioDAO.DeleteUsuarioAyudar(ids);	
	}
}
checked_all_usuarios_ayudar = function ( element ) {
	if( element ) {
		$('#LayerTableUsuariosAyudar #DataLayerTableUsuariosAyudar').find(':checkbox').attr('checked',true);
	}else{
		$('#LayerTableUsuariosAyudar #DataLayerTableUsuariosAyudar').find(':checkbox').attr('checked',false);
	}
}
checked_all_usuarios_asignar = function ( element ) {
	if( element ) {
		$('#LayerTableUsuariosAsignar #DataLayerTableUsuariosAsignar').find(':checkbox').attr('checked',true);
	}else{
		$('#LayerTableUsuariosAsignar #DataLayerTableUsuariosAsignar').find(':checkbox').attr('checked',false);
	}
}
search_operadores_ayuda_gestion = function ( xtext, xidtable ) {
	var text = xtext;
	text = text.toUpperCase();
	$('#'+xidtable).find('tr').css('display','none');
	$('#'+xidtable+' tr').find('td:contains("'+text+'")').parent().css('display','block');
}