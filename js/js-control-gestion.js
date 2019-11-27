$(document).ready(function( ){
	
    ControlGestionJQGRID.gestiones_servicio( $('#hdCodUsuario').val() );
 	
});
listar_carteras_servicio = function ( ) {
	
    var idservicio = $('#').val();
	
    ControlGestionDAO.Listar.CarterasServicio( idservicio, function ( obj ) {
        var html = '';
        for( i=0;i<obj.length;i++ ) {
            html+='<tr>';
            html+='<td align="center" style="padding : 2px 0;" class="ui-widget-header" >'+(i+1)+'</td>';
            html+='<td align="center" style="padding : 2px 0;" ></td>';
            html+='<td align="center" style="padding : 2px 0;" ></td>';
            html+='<td align="center" style="padding : 2px 0;" ></td>';
            html+='<td align="center" style="padding : 2px 0;" ></td>';
            html+='<td align="center" style="padding : 2px 0;" ></td>';
            html+='<td align="center" style="padding : 2px 0;" ></td>';
            html+='<td align="center" style="padding : 2px 0;" ></td>';
            html+='<td align="center" style="padding : 2px 0;" ></td>';
            html+='<td align="center" style="padding : 2px 0;" ></td>';
            html+='<td align="center" style="padding : 2px 0;" ></td>';
            html+='</tr>';
        }
        $('#tbGestionesControlGestion').html(html);
    }, function ( ) {
				
        } ); 
		
}
delete_cartera = function ( idcartera ) {
    var usuario_modificacion = $('#hdCodUsuario').val();
    var idcartera = ( $('#tableGestionesControlGestion').jqGrid('getGridParam','selarrrow') ).join(",");

    var rs = confirm("Desea eliminar las carteras seleccionadas, esta operacion es irreversible");
    
    if( rs ) {

        ControlGestionDAO.Update._delete( idcartera, usuario_modificacion, function ( obj ) {
            _noneBeforeSend();
            if( obj.rst ) {
                $('#layerMessage').html(templates.MsgInfo(obj.msg,'450px'));
                $('#tableGestionesControlGestion').jqGrid().trigger('reloadGrid');
            }else{
                $('#layerMessage').html(templates.MsgError(obj.msg,'450px'));
            }
            $('#layerMessage').fadeIn().delay(10000).fadeOut();
        },function ( ) {
            _displayBeforeSend('Eliminando carteras...',250);
        }, function ( ){
            _noneBeforeSend();
            $('#layerMessage').html(templates.MsgError("ERROR EN EJECUCION DE PROCESO",'450px'));
            $('#layerMessage').fadeIn().delay(10000).fadeOut();
        } );
    
    }
	
}
active_cartera = function ( ) {
    var usuario_modificacion = $('#hdCodUsuario').val();
    var idcartera = ($('#tableGestionesControlGestion').jqGrid('getGridParam','selarrrow')).join(",");
	
    var rs = confirm("Desea activar las carteras seleccionadas");
    
    if( rs ) {
    
        ControlGestionDAO.Update.off_on( 'active', idcartera, usuario_modificacion, function ( obj ) {
            _noneBeforeSend();
            if( obj.rst ) {
                $('#layerMessage').html(templates.MsgInfo(obj.msg,'450px'));
                $('#tableGestionesControlGestion').jqGrid().trigger('reloadGrid');
            }else{
                $('#layerMessage').html(templates.MsgError(obj.msg,'450px'));
            }
            $('#layerMessage').fadeIn().delay(10000).fadeOut();
        },function ( ) {
            _displayBeforeSend('Actualizando carteras...',250);
        }, function ( ){ 
            _noneBeforeSend();
            $('#layerMessage').html(templates.MsgError("ERROR EN EJECUCION DE PROCESO",'450px'));
            $('#layerMessage').fadeIn().delay(10000).fadeOut();
        });
    
    }
	
}
desactive_cartera = function ( ) {
    var usuario_modificacion = $('#hdCodUsuario').val();
    var idcartera = ($('#tableGestionesControlGestion').jqGrid('getGridParam','selarrrow')).join(",");
	
    var rs = confirm("Desea desactivar las carteras seleccionadas");
    
    if( rs ) {
    
        ControlGestionDAO.Update.off_on( 'desactive', idcartera, usuario_modificacion, function ( obj ) {
            _noneBeforeSend();
            if( obj.rst ) {
                $('#layerMessage').html(templates.MsgInfo(obj.msg,'450px'));
                $('#tableGestionesControlGestion').jqGrid().trigger('reloadGrid');
            }else{
                $('#layerMessage').html(templates.MsgError(obj.msg,'450px'));
            }
            $('#layerMessage').fadeIn().delay(10000).fadeOut();
        },function ( ) {
            _displayBeforeSend('Actualizando carteras...',250);
        }, function ( ) {
            _noneBeforeSend();
            $('#layerMessage').html(templates.MsgError("ERROR EN EJECUCION DE PROCESO",'450px'));
            $('#layerMessage').fadeIn().delay(10000).fadeOut();
        } );
    
    }
	
}




