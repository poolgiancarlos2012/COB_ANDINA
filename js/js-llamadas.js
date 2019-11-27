// JavaScript Document
$(document).ready(function( ){
	
	$('#layerDatepicker').datepicker({inline:true,autoSize:true,dayNamesMin:['D','L','M','M','J','V','S'],monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']});	
	LlamadasJQGRID.clientes();
	LlamadasJQGRID.LLamadasClientes();
	RankingDAO.Listar.Campania(RankingDAO.Fill.Campania);
	
});
reload_clientes = function ( ) {
	var cartera = $('#cbCarteraLlamadas').val();
	var servicio = $('#hdCodServicio').val();
	
	$("#table_clientes").jqGrid('setGridParam',{url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_cliente_servicio&Cartera='+cartera+'&Servicio='+servicio}).trigger('reloadGrid');
}
load_llamada_cartera = function ( idCampania, idCB ) {
	RankingDAO.Listar.Cartera(idCampania,RankingDAO.Fill.Cartera, idCB );
}
reload_llamadas = function ( id ) {
	$("#table_llamadas").jqGrid('setGridParam',{url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_llamada&ClienteCartera='+id}).trigger('reloadGrid');
}
