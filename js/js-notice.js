// JavaScript Document
$(document).ready(function( ){
	$('#layerDatepicker').datepicker({inline:true,autoSize:true,dateFormat:'yy-mm-dd',dayNamesMin:['D','L','M','M','J','V','S'],monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']});	
	/*$.PeriodicalUpdater({
						url : NoticeDAO.url,
						method : 'GET',
						type : 'json',
						sendData : { command : 'notice', action : 'ListarNoticeHoyRealTime', idservicio : $('#hdCodServicio').val() }
					}, function ( obj ) {
							var html='';
							for( i=0;i<obj.length;i++ ) {
								html+='<div>';
									html+='<h3>';
										html+='<a href="#">'+obj[i].titulo+'</a>';
										html+='<div style="font-size:85%;font-weight:normal;"></div>';
									html+='</h3>';
									html+='<div style="font-size:110%;"></div>';
								html+='</div>';
								html+='<div style="clear: both;">'+obj[i].descripcion+'</div>';
							}
							$('#layerContentRss').html(html);
						});*/
	listar_noticia_hoy();
});
listar_noticia_hoy = function ( ) {
	
/*	NoticeDAO.listar_noticia_hoy( $('#hdCodServicio').val(), function ( obj ) {
			var html='';
			for( i=0;i<obj.length;i++ ) {
				html+='<div>';
					html+='<h3>';
						html+='<a href="#"></a>';
						html+='<div style="font-size:85%;font-weight:normal;"></div>';
					html+='</h3>';
					html+='<div style="font-size:110%;"></div>';
				html+='</div>';
				html+='<div style="clear: both;"></div>';
			}
			$('#').html(html);
		} ); */
		
		
	NoticeDAO.listar_noticia_hoy.init( $('#hdCodServicio').val(), function ( obj ) {
			var html='';
			for( i=0;i<obj.length;i++ ) {
				html+='<div>';
					html+='<h3>';
						html+='<a href="#">'+obj[i].titulo+'</a>';
						html+='<div style="font-size:85%;font-weight:normal;"></div>';
					html+='</h3>';
					html+='<div style="font-size:110%;"></div>';
				html+='</div>';
				html+='<div style="clear: both;">'+obj[i].descripcion+'</div>';
			}
			$('#layerContentRss').html(html);
			
		} );
	
}
save_notice = function ( ) {
	
	var titulo = $.trim( $('#txtTitleNotice').val() );
	var descripcion = $.trim( $('#txtDescriptionNotice').val() );
	var idusuario_servicio = $('#hdCodUsuarioServicio').val();
	var idservicio = $('#hdCodServicio').val();
	var usuario_creacion = $('#hdCodUsuario').val();
	
	NoticeDAO.save_notice( idusuario_servicio, usuario_creacion, idservicio, titulo, descripcion, function ( obj ) {
			
			if( obj.rst ) {
				
			}else{
				
			}
			
		} );
	
}
