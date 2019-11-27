/******* davis ******/
var AdicionalesDAO={
    xurl:'../controller/ControllerCobrast.php',
	idLayerMessage:'layerMessage',
    xdataType:'json',
    insertFinalxService:function( idFinal, xprioridad, xpeso, xefecto ){
		
        $.ajax({
            url:this.xurl,
            dataType:this.xdataType,
            type:'POST',
            data:{
                action:'insert',
                command:'finalesxservicio',
                Servicio:$("#hdCodServicio").val(),
     			Final : idFinal,
				Prioridad : xprioridad,
				Peso : xpeso,
				Efecto : xefecto,
				UsuarioCreacion : $('#hdCodUsuario').val()
            },
            beforeSend:function(){
				_displayBeforeSend('Guardando...',320);
            },
            success:function(obj){
				_noneBeforeSend();
  				if(obj.rst){
					$('#'+AdicionalesDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'400px'));
					AdicionalesDAO.setTimeOut_hide_message();
					cancel_dialog_final_servicio();
					$('#table_final_servicios').jqGrid().trigger('reloadGrid');
                }else{
                    $('#'+AdicionalesDAO.idLayerMessage).html(templates.MsgError(obj.msg,'400px'));
					AdicionalesDAO.setTimeOut_hide_message();
					
                }
            },
			error : function ( ) {
					_noneBeforeSend();
				}
        });
    },
	deleteFinalServicio : function ( idFinalServicio ) {
		$.ajax({
            url:this.xurl,
            dataType:this.xdataType,
            type:'POST',
            data:{
                action:'delete',
                command:'finalesxservicio',
               	FinalServicio : idFinalServicio,
				UsuarioModificacion : $('#hdCodUsuario').val()
            },
            beforeSend:function(){
				_displayBeforeSend('Eliminando...',320);
            },
            success:function(obj){
				_noneBeforeSend();
  				if(obj.rst){
					$('#'+AdicionalesDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'400px'));
					AdicionalesDAO.setTimeOut_hide_message();
					cancel_dialog_final();
					$('#table_final_servicios').jqGrid().trigger('reloadGrid');
                }else{
                    $('#'+AdicionalesDAO.idLayerMessage).html(templates.MsgError(obj.msg,'400px'));
					AdicionalesDAO.setTimeOut_hide_message();
					
                }
            },
			error : function ( ) {
					_noneBeforeSend();
				}
        });
	},
    buscarFinal:function(xid){
        $.ajax({
            url:this.xurl,
            dataType:this.xdataType,
            type:'GET',
            data:{
                action:'buscar',
                command:'finales',
                id:xid
            },
            beforeSend:function(){

            },
            success:function(obj){
                AdicionalesDAO.showFormFinal(obj);
            }
        });
    },
    insertFinal:function(){
        
        $.ajax({
            url:this.xurl,
            dataType:this.xdataType,
            type:'POST',
            data:{
				command:'finales',
				action:'insert',
				Nombre:$.trim( $('#txtNombreFinal').val() ),
				Descripcion:$.trim( $('#txtDescripcionFinal').val() ),
				Tipo:$('#cbTipoFinal').val(),
				Carga:$('#cbCargaFinal').val(),
				Clase:$('#cbClaseFinal').val(),
				Nivel:$('#cbNivelFinal').val(),
				UsuarioCreacion:$('#hdCodUsuario').val()
				},
			beforeSend:function(){
            	_displayBeforeSend('Guardando final...',320);
            },
            success:function(obj){
				_noneBeforeSend();
                if(obj.rst){
					$('#'+AdicionalesDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'400px'));
					AdicionalesDAO.setTimeOut_hide_message();
					cancel_dialog_final();
					$('#table_final').jqGrid().trigger('reloadGrid');
                }else{
                    $('#'+AdicionalesDAO.idLayerMessage).html(templates.MsgError(obj.msg,'400px'));
					AdicionalesDAO.setTimeOut_hide_message();
					
                }
            },
			error : function ( ) {
					_noneBeforeSend();
				}
        });
    },
	updateFinal : function ( ) {
		/** hdIdFinal **/
		$.ajax({
            url:this.xurl,
            dataType:this.xdataType,
            type:'POST',
            data:{
				command:'finales',
				action:'update',
				Id:$('#hdIdFinal').val(),
				Nombre:$.trim( $('#txtNombreFinal').val() ),
				Descripcion:$.trim( $('#txtDescripcionFinal').val() ),
				Tipo:$('#cbTipoFinal').val(),
				Carga:$('#cbCargaFinal').val(),
				Clase:$('#cbClaseFinal').val(),
				Nivel:$('#cbNivelFinal').val(),
				UsuarioModificacion:$('#hdCodUsuario').val()
				},
			beforeSend:function(){
				_displayBeforeSend('Actualizando final...',320);
            },
            success:function(obj){
				_noneBeforeSend();
                if(obj.rst){
					$('#'+AdicionalesDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'400px'));
					AdicionalesDAO.setTimeOut_hide_message();
					cancel_dialog_final();
					$('#table_final').jqGrid().trigger('reloadGrid');
                }else{
                    $('#'+AdicionalesDAO.idLayerMessage).html(templates.MsgError(obj.msg,'400px'));
					AdicionalesDAO.setTimeOut_hide_message();
                }
            },
			error : function ( ) {
					_noneBeforeSend();
				}
        });
		
	},
	DataFinal : function ( xid,f_fill ) {
		$.ajax({
            url:this.xurl,
            dataType:this.xdataType,
            type:'GET',
            data:{
				command:'finales',
				action:'getFinalById',
				Id: xid,
				},
			beforeSend:function(){
                _displayBeforeSend('Trayendo datos...',320);
            },
            success:function(obj){
				_noneBeforeSend();
            	f_fill(obj);
            },
			error : function ( ) {
				_noneBeforeSend();
			}
			
        });
		
	},
	FillFormDialogFinal : function ( obj ) {
		if( obj.length>0 ){
		$('#hdIdFinal').val(obj[0].idfinal);
		$('#txtNombreFinal').val(obj[0].nombre);
		$('#txtDescripcionFinal').val(obj[0].descripcion);
		$('#cbTipoFinal').val(obj[0].idtipo_final);
		$('#cbCargaFinal').val(obj[0].idcarga_final);
		$('#cbClaseFinal').val(obj[0].idclase_final);
		$('#cbNivelFinal').val(obj[0].idnivel);
		show_dialog_final();
		}
	},
    loadTipoFinal:function( f_fill ){
        $.ajax({
            url:this.xurl,
            dataType:this.xdataType,
            type:'GET',
            data:{
                action:'ListarTipoFinalAll',
                command:'atencion_cliente'
            },
            beforeSend:function(){
                
            },
            success:function(obj){
                if( f_fill ){
					f_fill(obj);
				}else{
					AdicionalesDAO.FillTipoFinal(obj);
				}
            },
			error : function ( ) {
				
			}
        });
    },
	FillTipoFinal : function ( obj ) {
		var html='';
		html+='<option value="0" >--Seleccione--</option>';
		$.each(obj,function( key,data ){
			html+='<option value="'+data.idtipo_final+'">'+data.nombre+'</option>';
		});
		$('#cbTipoFinal').html(html);
	},
    loadCargaFinal:function( f_fill ){
        $.ajax({
            url:this.xurl,
            dataType:this.xdataType,
            type:'GET',
            data:{
                action:'ListarCargaFinalAll',
                command:'atencion_cliente'
            },
            beforeSend:function(){
               
            	},
            success:function(obj){
               		if( f_fill ){
						f_fill(obj);
					}else{
						AdicionalesDAO.FillCargaFinal(obj);
					}
            	},
			error : function ( ) {
				}
        });
    },
	FillCargaFinal : function ( obj ) {
		var html='';
		html+='<option value="0" >--Seleccione--</option>';
		$.each(obj,function( key,data ){
			html+='<option value="'+data.idcarga_final+'">'+data.nombre+'</option>';
		});
		$('#cbCargaFinal').html(html);
	},
    loadClaseFinal:function( f_fill ){
        $.ajax({
            url:this.xurl,
            dataType:this.xdataType,
            type:'GET',
            data:{
                action:'ListarClaseFinal',
                command:'atencion_cliente'
            },
            beforeSend:function(){
               
            },
            success:function(obj){
            	if( f_fill ){
					f_fill(obj);
				}else{
					AdicionalesDAO.FillClaseFinal(obj);
				}
            },
			error : function ( ) {
				
			}
        });
    },
	FillClaseFinal : function ( obj ) {
		var html='';
		html+='<option value="0" >--Seleccione--</option>';
		$.each(obj,function( key,data ){
			html+='<option value="'+data.idclase_final+'">'+data.nombre+'</option>';
		});
		$('#cbClaseFinal').html(html);
	},
    loadNivelFinal:function( f_fill ){
        $.ajax({
            url:this.xurl,
            dataType:this.xdataType,
            type:'GET',
            data:{
                action:'ListarNivelAll',
                command:'atencion_cliente'
            },
            beforeSend:function(){
                
            },
            success:function(obj){
            	if( f_fill ){
					f_fill(obj);
				}else{
					AdicionalesDAO.FillNivelFinal(obj);
				}
            }
        });
    },
	FillNivelFinal : function ( obj ) {
		var html='';
		html+='<option value="0" >--Seleccione--</option>';
		$.each(obj,function( key,data ){
			html+='<option value="'+data.idnivel+'">'+data.nombre+'</option>';
		});
		$('#cbNivelFinal').html(html);
	},
    loadServicios:function(xid){
        $.ajax({
            url:this.xurl,
            dataType:this.xdataType,
            data:{
                action:'listar_servicio',
                command:'servicio'
            },
            beforeSend:function(){
                $("#"+xid).html("<option>Cargando..</option>");
            },
            success:function(obj){
                var html="";
                $.each(obj,function(k,v){
                    html+="<option value='"+v.id+"'>";
                    html+=v.nombre;
                    html+="</option>";
                });
                $("#"+xid).html(html);
            }
        })
    },
	hide_message : function ( ) {
			$('#'+AdicionalesDAO.idLayerMessage).effect('blind',{direction:'vertical'},'slow',function(){ $(this).empty().css('display','block'); });
				
		},
	setTimeOut_hide_message : function ( ) {
			setTimeout("AdicionalesDAO.hide_message()",4000);
		},
	error_ajax : function ( ) {
			$('#'+AdicionalesDAO.idLayerMessage).html(templates.MsgError('Error en ejecucion de proceso','400px'));
			AdicionalesDAO.setTimeOut_hide_message();
		}
            
}