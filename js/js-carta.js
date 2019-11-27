// JavaScript Document
$(document).ready(function(){
	$('#layerDatepicker').datepicker({inline:true,autoSize:true,dateFormat:'yy-mm-dd',dayNamesMin:['D','L','M','M','J','V','S'],monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']});
	$(':text[id^="txtFecha"]').datepicker({dateFormat:'yy-mm-dd',dayNamesMin:['D','L','M','M','J','V','S'],monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']});
	/***********/
	CartaDAO.ListarCampania();
	/***********/
	listar_estado();
	
});
load_cartera_by_id = function ( idCampania, cbCartera ) {
	CartaDAO.ListarCartera(idCampania,function( obj ) { CartaDAO.FillCarteraById(obj,cbCartera); });
}
listar_estado = function ( ) {
	CartaDAO.ListarEstado( $('#hdCodServicio').val(), function ( xobject ) {
			var obj = xobject.llamada;
			var html='';
			html+='<option value="0" >--Seleccione--</option>';
			for( i=0;i<obj.length;i++ ) {
				var data = (obj[i].data).split('|');
				html+='<optgroup label="'+obj[i].CARGA+'" >';
					for( j=0;j<data.length;j++ ) {
						var final = data[j].split('@#');	
						html+='<option value="'+final[0]+'" >'+final[1]+'</option>';
					}
				html+='</optgroup>';
			}
			$('#cbEstadoCarta').html(html);
			
		} );
}
listar_data_cartera = function ( xidcartera ) {
	
	CartaDAO.ListarDepartamentos( xidcartera, function ( obj ) {
			
			var html = '';
			html+='<option value="0">--Seleccione--</option>';
			for( i=0;i<obj.length;i++ ) {
				html+='<option value="'+obj[i].departamento+'">'+obj[i].departamento+'</option>';
			}
			$('#cbDepartamentoCarta').html(html);
			
		} );
		
}
