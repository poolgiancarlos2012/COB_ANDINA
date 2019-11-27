// JavaScript Document
$(document).ready(function( ){
	$('#layerDatepicker').datepicker({inline:true,autoSize:true,dateFormat:'yy-mm-dd',dayNamesMin:['D','L','M','M','J','V','S'],monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']});	
	/****/
	listar_alertas();
	setInterval("listar_alertas()",5000);
	/****/
});
listar_alertas = function ( ) {
	
	AlertaAdminDAO.ListarAlerta( $('#hdCodServicio').val(), function ( obj ) {
			var html = '';
			var dataHoy = obj.hoy ;
			var dataAyer = obj.ayer ;
			var dataAntigua = obj.antigua;
			if( _countAlert == 0 ) {
				//html+='<ul>';
				
				for( i=0;i<dataHoy.length;i++ ) {
					var xclass = '';
					var srcimg = '';
					if( dataHoy[i].estado == "1" ) {
						xclass = ' ui-state-highlight ';
						srcimg = '../img/user_orange.png';
					}else if( dataHoy[i].estado == "0" ){
						xclass = ' ui-state-default ';
						srcimg = '../img/user_green.png';
					}
					html+='<li id="content_'+dataHoy[i].idalerta+'" style="float:left;padding:0;width:250px;margin:2px;height:70px;" class="ui-state-active ui-corner-all" >';
						html+='<div id="header_'+dataHoy[i].idalerta+'" class="'+xclass+' ui-corner-top" align="center" style="padding:2px;" >';
							html+='<table border="0" cellpadding="0" cellspacing="0" style="width:100%;" >';
								html+='<tr>';
									html+='<td align="center" style="width:20px;"><img src="'+srcimg+'" /></td>';
									html+='<td align="center">'+dataHoy[i].nombre_cliente+'</td>';
								html+='</tr>';
							html+='</table>';
						html+='</div>';
						html+='<table border="0" cellpadding="0" cellspacing="0" style="width:100%;" >';
							html+='<tr>';
								html+='<td>Propietario:</td>';
								html+='<td>'+dataHoy[i].nombre_usuario_servicio+'</td>';
							html+='</tr>';
							html+='<tr>';
								html+='<td>Descripcion:</td>';
								html+='<td>'+dataHoy[i].descripcion+'</td>';
							html+='</tr>';
						html+='</table>';
					html+='</li>';
				}
				//html+='</ul>';
				$('#ulAlertasHoy').html(html);
				
				html='';
				for( i=0;i<dataAyer.length;i++ ) {
					
					var xclass = '';
					var srcimg = '';
					if( dataAyer[i].estado == "1" ) {
						xclass = ' ui-state-error ';
						srcimg = '../img/user_red.png';
					}else if( dataAyer[i].estado == "0" ) {
						xclass = ' ui-state-default ';
						srcimg = '../img/user_green.png';
					}
					html+='<li id="content_'+dataAyer[i].idalerta+'" style="float:left;padding:0;width:250px;margin:2px;height:70px;" class="ui-state-active ui-corner-all" >';
						html+='<div id="header_'+dataAyer[i].idalerta+'" class="'+xclass+' ui-corner-top" align="center" style="padding:2px;" >';
							html+='<table border="0" cellpadding="0" cellspacing="0" style="width:100%;" >';
								html+='<tr>';
									html+='<td align="center" style="width:20px;"><img src="'+srcimg+'" /></td>';
									html+='<td align="center">'+dataAyer[i].nombre_cliente+'</td>';
								html+='</tr>';
							html+='</table>';
						html+='</div>';
						html+='<table border="0" cellpadding="0" cellspacing="0" style="width:100%;" >';
							html+='<tr>';
								html+='<td>Propietario:</td>';
								html+='<td>'+dataAyer[i].nombre_usuario_servicio+'</td>';
							html+='</tr>';
							html+='<tr>';
								html+='<td>Descripcion:</td>';
								html+='<td>'+dataAyer[i].descripcion+'</td>';
							html+='</tr>';
						html+='</table>';
					html+='</li>';
					
				}
				
				$('#ulAlertasAyer').html(html);
				
				html='';
				for( i=0;i<dataAntigua.length;i++ ) {
					
					var xclass = '';
					var srcimg = '';
					if( dataAntigua[i].estado == "1" ) {
						xclass = ' ui-state-error ';
						srcimg = '../img/user_red.png';
					}else if( dataAntigua[i].estado == "0" ) {
						xclass = ' ui-state-default ';
						srcimg = '../img/user_green.png';
					}
					html+='<li id="content_'+dataAntigua[i].idalerta+'" style="float:left;padding:0;width:250px;margin:2px;height:70px;" class="ui-state-active ui-corner-all" >';
						html+='<div id="header_'+dataAntigua[i].idalerta+'" class="'+xclass+' ui-corner-top" align="center" style="padding:2px;" >';
							html+='<table border="0" cellpadding="0" cellspacing="0" style="width:100%;" >';
								html+='<tr>';
									html+='<td align="center" style="width:20px;"><img src="'+srcimg+'" /></td>';
									html+='<td align="center">'+dataAntigua[i].nombre_cliente+'</td>';
								html+='</tr>';
							html+='</table>';
						html+='</div>';
						html+='<table border="0" cellpadding="0" cellspacing="0" style="width:100%;" >';
							html+='<tr>';
								html+='<td>Propietario:</td>';
								html+='<td>'+dataAntigua[i].nombre_usuario_servicio+'</td>';
							html+='</tr>';
							html+='<tr>';
								html+='<td>Descripcion:</td>';
								html+='<td>'+dataAntigua[i].descripcion+'</td>';
							html+='</tr>';
						html+='</table>';
					html+='</li>';
					
				}
				
				$('#ulAlertasAntiguas').html(html);
				
			}else{
				
				for( i=0;i<dataHoy.length;i++ ) {
					if( dataHoy[i].estado == "1" ) {
						$('#ulAlertasHoy').find('li').find('div[id="header_'+dataHoy[i].idalerta+'"]').removeClass('ui-state-default').addClass('ui-state-highlight');
						$('#ulAlertasHoy').find('li').find('div[id="header_'+dataHoy[i].idalerta+'"]').find('img').attr('src','../img/user_orange.png');
					}else if( dataHoy[i].estado == "0" ){
						$('#ulAlertasHoy').find('li').find('div[id="header_'+dataHoy[i].idalerta+'"]').removeClass('ui-state-highlight').addClass('ui-state-default');
						$('#ulAlertasHoy').find('li').find('div[id="header_'+dataHoy[i].idalerta+'"]').find('img').attr('src','../img/user_green.png');
					}
				}
				
				for( i=0;i<dataAyer.length;i++ ) {
					if( dataAyer[i].estado == "1" ) {
						$('#ulAlertasAyer').find('li').find('div[id="header_'+dataAyer[i].idalerta+'"]').removeClass('ui-state-default').addClass('ui-state-highlight');
						$('#ulAlertasAyer').find('li').find('div[id="header_'+dataAyer[i].idalerta+'"]').find('img').attr('src','../img/user_orange.png');
					}else if( dataAyer[i].estado == "0" ){
						$('#ulAlertasAyer').find('li').find('div[id="header_'+dataAyer[i].idalerta+'"]').removeClass('ui-state-highlight').addClass('ui-state-default');
						$('#ulAlertasAyer').find('li').find('div[id="header_'+dataAyer[i].idalerta+'"]').find('img').attr('src','../img/user_green.png');
					}
				}
				
			}
			
			_countAlert++;
		} );
	
}
