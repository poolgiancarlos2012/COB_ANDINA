$(document).ready(function(){
			
    $('#btnUploadFile,#btnGenerateTable,#btnLimpiar,#btnLimpiarCartera,#btnCargarPagos,#btnGenerateTableAutomatic,#btnCargarPagosAutomatica,#btnCargarTelefono,#btnCargarDetalle,#btnUploadFileReclamo,uploadFileCarteraRRLL,#btnBuscarFechaEnvioRP3,#btnCargarProvisionTotal,#btnDescargarCabecera,#btnUpdateMontoPagado,#btnAcuPago,#btnNormalizarTelefono2').button();
    // covinoc 01-10-2015
    $('#btnAcuDatDem,#btnAcuOblMor,#btnAcuDatAdiObl,#btnAcuOtrDatAdiObl,#btnAcuDatTel,#btnAcuDatDir,#btnAcuDatEma,#btnDescargarCovinoc').button();
    /*********/
    $('#layerDatepicker').datepicker({
        inline:true,
        autoSize:true,
        dateFormat:'yy-mm-dd',
        dayNamesMin:['D','L','M','M','J','V','S'],
        monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']
        });
    /*******/
    $('#txtFechaEnvioInicioRP3,#txtFechaEnvioFinRP3,#txtFechaInicioRespuestaComercial,#txtFechaFinRespuestaComercial,#txtFechaInicioRespuestaBanco,#txtFechaFinRespuestaBanco,#txtCagCarFechaVencimiento,#txtCruceTelefonoFechaInicio,#txtCruceTelefonoFechaFin,#txtFechaProvisionTotal,#txtFechaProcesoCovinoc').datepicker({
        autoSize:true,
        dateFormat:'yy-mm-dd',
        dayNamesMin:['D','L','M','M','J','V','S'],
        monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']
        });
    /*******/
    $('#txtNombreCabecera').alphanumeric({
        allow:"_"
    });
    $('#txtLongitudCabecera').numeric();
    /*******/
    CargaCarteraDAO.loadCampania();
    listar_carteras_servicio();
	listar_carteras_llamadas();
	//~ listar_estados_llamadas();
    listar_modulo_cabecera_por_servicio_cartera();
    listar_estado_llamadaG();
    /****************/
    /*jmore*/
    $('#procesarNormalizacion').click(procesar_normalizacion_telefono);
    /*jmore*/
    $('#aDisplayPanelCargarPago').one('click', 
        function ( ) 
        {
            listar_modulo_cabecera_por_servicio_pago();
        }
    );
    $('#aDisplayPanelTelefono').one('click',
        function ( ) 
        {
            load_origen();
            load_tipo_telefono();
        }
    );
    $('#aDisplayPanelComision').one('click',
        function ( ) 
        {
            CargaCarteraDAO.ListarTramoServicio();
            CargaCarteraDAO.ListarTramoGenericoServicio();
        }
    );
    $('#aDisplayPanelCabeceras').one('click',
        function ( )
        {
            listar_modulo_cabecera_por_servicio();
        }
    );
    $('#aDisplayPanelRP3').one('click',
        function ( )
        {
            ListarTodasCarteras();
        }
    );
    /****************/
    upLoadFile();
    upLoadFilePagoMulti();
    upLoadFileNocPre();
    upLoadFileRetiro();
    upLoadFileIVR();
    upLoadFileCorteFocalizado();
    uploadFileFacturacion();
    CargaCarteraDAO.UploadCourier();
    CargaCarteraDAO.UploadEstadoCuenta();
    CargaCarteraDAO.UploadSaldoTotal();
    CargaCarteraDAO.UploadDetalleM();

    // Carga de archivos planos COVINOC - Piro 02-10-2015
    $('#btnAcuDatDem').click(function(){
        if ( alerta('fileDatosDemograficos') ){
            $('#btnAcuDatDem').val('Verificando');
            CargaCarteraDAO.verificarArchivoPlanoCovinoc('fileDatosDemograficos','btnAcuDatDem');
        }
    
    });
    $('#btnAcuOblMor').click(function(){
        if ( alerta('fileObligacionesMora') ){
            $('#btnAcuOblMor').val('Verificando');
            CargaCarteraDAO.verificarArchivoPlanoCovinoc('fileObligacionesMora','btnAcuOblMor');
        }
    });
    $('#btnAcuDatAdiObl').click(function(){
        if( alerta('fileDatosAdicObligaciones') ){
            $('#btnAcuDatAdiObl').val('Verificando');
            CargaCarteraDAO.verificarArchivoPlanoCovinoc('fileDatosAdicObligaciones','btnAcuDatAdiObl');
        }
    });
    $('#btnAcuOtrDatAdiObl').click(function(){
        if ( alerta('fileOtrosDatosAdicObligaciones') ){
            $('#btnAcuOtrDatAdiObl').val('Verificando');
            CargaCarteraDAO.verificarArchivoPlanoCovinoc('fileOtrosDatosAdicObligaciones','btnAcuOtrDatAdiObl');
        }
    });
    $('#btnAcuDatTel').click(function(){
        if (alerta('fileDatosTelefonos') ){
            $('#btnAcuDatTel').val('Verificando');
            CargaCarteraDAO.verificarArchivoPlanoCovinoc('fileDatosTelefonos','btnAcuDatTel');
        }
    });
    $('#btnAcuDatDir').click(function(){
        if ( alerta('fileDatosDirecciones') ){
            $('#btnAcuDatDir').val('Verificando');
            CargaCarteraDAO.verificarArchivoPlanoCovinoc('fileDatosDirecciones','btnAcuDatDir');
        }
    });
    $('#btnAcuDatEma').click(function(){
        if ( alerta('fileDatosEmails') ){
            $('#btnAcuDatEma').val('Verificando');
            CargaCarteraDAO.verificarArchivoPlanoCovinoc('fileDatosEmails','btnAcuDatEma');
        }
    });
    // fin carga de archivos planos COVINOC

    // inicio preparacionde archivo plano de pago
    $('#btnAcuPago').click(function(){
        cantCarteras = $('#tbCarterasCargaPrepararPagoSaga').find(':checked').
                    map(function(){return $(this).val();
                    }).get().length;

        carteras = $('#tbCarterasCargaPrepararPagoSaga').find(':checked').
                    map(function(){
                           return $(this).val();
                    }).get().join(',');

        if( cantCarteras==0 || cantCarteras>1 ){
            $('#msgCovinocPreparacion').css('opacity','1').
            html('<span class="fa fa-exclamation" style="margin-left:15px;margin-right:15px"></span>Seleccionar solo una cartera');
            setTimeout(function(){
                $('#msgCovinocPreparacion').css('opacity','0').
                html('<span class="fa fa-exclamation" style="margin-left:15px;margin-right:15px"></span>');
            },1000)
            return false;
        }

        if ( alerta('filePago') ){
            $('#btnAcuPago').val('Verificando');
           CargaCarteraDAO.verificarArchivoPlanoPagoSaga('filePago','btnAcuPago');
        }
    });
});
//funciones para covinoc
function alerta(file){
    if( $('#'+file).val()=='' || $('#'+file).val()== undefined ){
            $('#msgCovinocPreparacion').css('opacity','1').
            html('<span class="fa fa-exclamation" style="margin-left:15px;margin-right:15px"></span>Seleccionar archivo txt');
            setTimeout(function(){
                $('#msgCovinocPreparacion').css('opacity','0').
                html('<span class="fa fa-exclamation" style="margin-left:15px;margin-right:15px"></span>');
            },1000)
            return false;
        }
    if( $('#'+file).val().substr('-4')!='.txt' ){
            $('#msgCovinocPreparacion').css('opacity','1').
            html('<span class="fa fa-exclamation" style="margin-left:15px;margin-right:15px"></span>Verificar que el formato sea txt');
            setTimeout(function(){
                $('#msgCovinocPreparacion').css('opacity','0').
                html('<span class="fa fa-exclamation" style="margin-left:15px;margin-right:15px"></span>');
            },1000)
            return false;
        }

    return true;
}

//fin funciones para covinoc
ListarTodasCarteras = function ( ) {
    
    ReporteDAO.ListarTodasCartera( 
        function ( obj ) {
            
            var html='';
                        
            for( i=0;i<obj.length;i++ ) {
            
                html+='<tr style="display:block;" >';
                    html+='<td style="width:30px;padding:2px 0;" align="center" class="ui-state-default">'+(i+1)+'</td>';
                    html+='<td style="width:240px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].campania+'</td>';
                    html+='<td style="width:240px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].nombre_cartera+'</td>';
                    html+='<td style="width:80px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].fecha_inicio+'</td>';
                    html+='<td style="width:80px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].fecha_fin+'</td>';
                    html+='<td style="width:20px;padding:2px 0;" align="center" class="ui-widget-content" ><input type="checkbox" name="rd_RP3_pagos_comercial_l" value="'+obj[i].idcartera+'" /></td>';
                html+='</tr>';
            
            }
            
            $('#table_list_carteras_comercial,#table_list_carteras_banco').html(html);
            
        } ,''
    );
    
}
/*jmore*/
procesar_normalizacion_telefono=function(){
    var campania=$('#cboCampaniaNormalizacionTelefono').val();
    var cartera=$('#cboCarteraNormalizacionTelefono').val();
    if (campania==0){
        alert('Seleccione Campania');
        return false;
    }
    if (cartera==0){
        alert('Seleecione Cartera');
        return false;
    }
    CargaCarteraDAO.procesarNormalizacionTelefono();
}
/*jmore*/
//~ Vic I
file_cargar_llamadas = function(){
	CargaCarteraDAO.file_cargar_llamadas();
}
file_cargar_cuota=function(){
    CargaCarteraDAO.file_cargar_cuota();
}
file_cliente_contrato_new = function(){
	var cliente = $("#uploadFileCargaClienteNew").val();
	var contrato = $("#uploadFileCargaContratoNew").val();
	if ($.trim(cliente)=='' || $.trim(contrato)=='')
	{
		alert("Seleccionar Archivos");
		return false;
	}
	CargaCarteraDAO.file_cliente_contrato_new();
}
file_fiadores_txt = function(){
	var fiador = $("#uploadFileFiadores").val();
	var sltCatera = $("#cboCarteraFiadores").val();
	if ($.trim(fiador)=='' || sltCatera==0)
	{
		alert("Seleccionar Datos");
		return false;
	}
	CargaCarteraDAO.file_fiadores_txt();
}
file_carga_facturacion=function(){
    var facturacion=$('#uploadFileCarteraCargaFacturacion').val();
    if($.trim(facturacion)==''){
        alert("Seleccione archivo");
        return false;
    }
    CargaCarteraDAO.file_carga_facturacion();
}
file_carga_provision=function(){
    var provision=$('#uploadFileCarteraCargaProvision').val();
    if($.trim(provision)==''){
        alert("Seleccione archivo");
        return false;
    }
    CargaCarteraDAO.file_carga_provision();
}
generateCargaFacturacion=function(){
   var archivo=$('#uploadFileCarteraCargaFacturacion').val();
   if($.trim(archivo)==''){
    alert("seleccionar archivo");
    return false;
   } 

   var idcartera=$('#tbCarterasCargaFacturacion').find(':checked').map(function(){
                    return $(this).val()
                    }).get().join(",");
    if($.trim(idcartera)==''){
    alert("Seleccionar Carteras");
    return false;
    } 
    
   var rsC=confirm("Esta Seguro de Cargar Facturas");
    if( rsC ){
        CargaCarteraDAO.generateCargaFacturacion(); 
    }   
}
generateCargaProvision=function(){
   var archivo=$('#uploadFileCarteraCargaProvision').val();
   if($.trim(archivo)==''){
    alert("seleccionar archivo");
    return false;
   } 

   var idcartera=$('#tbCarterasCargaProvision').find(':checked').map(function(){
                    return $(this).val()
                    }).get().join(",");
    if($.trim(idcartera)==''){
    alert("Seleccionar Carteras");
    return false;
    } 
    
   var rsC=confirm("Esta Seguro de Cargar Provision");
    if( rsC ){
        CargaCarteraDAO.generateCargaProvision(); 
    }   
}
btn_generar_join_carteras = function(){
	var cliente = $("#txtJoinClienteRspta").val();
	var contrato = $("#txtJoinContratoRspta").val();
	if ($.trim(cliente)!=1 || $.trim(contrato)!=1)
	{
		alert("Existe un error en la carga de archivos, por favor validarlo.");
		return false;
	}
	var clienteTime = $("#txtJoinClienteTime").val();
	var contratoTime = $("#txtJoinContratoTime").val();
	CargaCarteraDAO.btn_generar_join_carteras(clienteTime, contratoTime);

}
cruce_llamadas = function(){
	CargaCarteraDAO.cruce_llamadas();
}
agregarTelefonoManual = function(){
	CargaCarteraDAO.insertar_new_fonos();
}
insertarLlamadasManual = function(){
	CargaCarteraDAO.insertarLlamadasManual();
}
listar_carteras_llamadas = function ( ) {
	CargaCarteraDAO.CarterasServicio( $('#hdCodServicio').val(), function ( obj ) {
		var html = "<select class='sltCarteraLlama combo'>";
		html += '<option value="0">-- Seleccionar Cartera --</option>';
		for( i=0;i<obj.length;i++ ) {
			html+='<option value="'+obj[i].idcartera+'">'+obj[i].nombre_cartera+'</option>';
		}
		html += "</select>";
		$('.sltCarterasLlam').html(html);
	});
}
listar_estados_llamadas = function ( ) {
	CargaCarteraDAO.ListaTipificacionServicio( $('#hdCodServicio').val(), function ( obj ) {
		var html = "<select class='sltTipificacionLlama combo'>";
		html += '<option value="0">-- Seleccionar Estado --</option>';
		for( i=0;i<obj.length;i++ ) {
			html+='<option value="'+obj[i].idfinal+'">'+obj[i].nombre+'</option>';
		}
		html += "</select>";
		$('.sltTipificacionLlam').html(html);
	});
}
//~ Vic F
upLoadFile = function(){
    CargaCarteraDAO.Upload();
}
upLoadFilePagoMulti = function ( ) {
    CargaCarteraDAO.UploadPagoMulti();
}
upLoadFileNocPre = function(){
    CargaCarteraDAO.UploadNocPre();
}
upLoadFileRetiro = function(){
    CargaCarteraDAO.UploadRetiroMasivo();
}
upLoadFileCorteFocalizado = function()
{
    CargaCarteraDAO.UploadCorteFocalizado();
}
uploadFileFacturacion = function()
{
    CargaCarteraDAO.UploadFacturacion();
}
upLoadFileIVR = function(){
    CargaCarteraDAO.UploadIVRMasivo();
}
uploadFilePago = function ( ) {
    CargaCarteraDAO.UploadPago();
}
uploadLimpiarCartera = function ( ) {
    CargaCarteraDAO.UploadLimpiarCartera();
}
uploadFileCentroPago = function ( ) {
    CargaCarteraDAO.UploadCentroPago();
}
uploadFileCarteraPlanta = function ( ) {
    CargaCarteraDAO.UploadCarteraPlanta();
}
uploadFileTelefono = function ( ) {
    CargaCarteraDAO.UploadTelefono();
}
uploadFileDetalle = function ( ) {
    CargaCarteraDAO.UploadDetalle();
}
uploadFileReclamo = function ( ) {
    /*var cartera = $('#cbCarteraReclamo').val();
	if( cartera == 0 ) {
		alert("Seleccione cartera");
		return false;
	}*/
    CargaCarteraDAO.UploadReclamo();
}
uploadFileRRLL = function ( ) {
    /*var cartera = $('#cbCarteraReclamo').val();
	if( cartera == 0 ) {
		alert("Seleccione cartera");
		return false;
	}*/
    CargaCarteraDAO.UploadRRLL();
}
generatePlanta = function ( ) {
    CargaCarteraDAO.generateTablePlanta();
}
load_cartera_tb_a_d = function ( idCampania, idTB ) {
    
    CargaCarteraDAO.ListarCarteraPorCampania(idCampania,CargaCarteraDAO.FillCarteraTB, idTB );
}
actuliazar_corte_focalizado = function()
{
    var files = $('#hddFileCorteFocalizado').val();
    CargaCarteraDAO.Update_corte_focalizado(files);
}
downloadFileFacturacion = function()
{
    var nameTable = $('#hddFileFacturacion').val();
    $('#msg_resultado_facturacion').html('');
    $('#hddFileFacturacion').val('');
    location.href = '../controller/ControllerCobrast.php?command=carga-cartera&action=downloadFileFacturacion&tabla='+nameTable;
}
cancelarFacturacion = function()
{
    $('#hddFileFacturacion').val('');
    $('#msg_resultado_facturacion').html('');
}
generateCartera = function ( ) {
    var rs=false;
    var proceso=$('#cboTipoProceso').val();
    if( proceso=='carga' ){
        rs=validacion.check([
        {
            id:'cboCampania',
            isNotValue:0,
            errorNotValueFunction:function( ){
                $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Seleccione campaña','400px'));
                CargaCarteraDAO.setTimeOut_hide_message();
            }
        }
        /*,
		{id:'txtNombreCartera',required:true,errorRequiredFunction:function( ){
				$('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Ingrese nombre de cartera','400px'));
				CargaCarteraDAO.setTimeOut_hide_message();
			}}*/
        ]);
}else if( proceso=='actualizacion' || proceso=='agregar' ) {
    rs=validacion.check([
    {
        id:'cboCampania',
        isNotValue:0,
        errorNotValueFunction:function( ){
            $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Seleccione campaña','400px'));
            CargaCarteraDAO.setTimeOut_hide_message();
        }
    },

    /*{id:'txtNombreCartera',required:true,errorRequiredFunction:function( ){
				$('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Ingrese nombre de cartera','400px'));
				CargaCarteraDAO.setTimeOut_hide_message();
			}}
			,*/

    {
        id:'cboCarteraActualizar',
        isNotValue:0,
        errorNotValueFunction:function( ){
            $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Seleccione cartera a actualizar','400px'));
            CargaCarteraDAO.setTimeOut_hide_message();
        }
    }
]);
}else{
    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Proceso no reconocido','400px'));
    CargaCarteraDAO.setTimeOut_hide_message();
    return false;
}

var TipoData = $(':radio[name="rdTipoCargaCarteraMain"]:checked').val();
var CabeceraDepartamento = $('#cbCabeceraDepartamentoCargaCarteraMain').val();

if( ( TipoData == '2' || TipoData == '3' ) && CabeceraDepartamento == '0' ) {
    alert("Seleccione cabecera de departamento");
    return false;
}
	
var LENGTH_Cliente=$("#layerTabCargaCarteraCliente select").find("option:selected").not("option[value='0']").length;
var LENGTH_Cuenta=$("#layerTabCargaCarteraCuenta select").find("option:selected").not("option[value='0']").length;
var LENGTH_Operacion=$("#layerTabCargaCarteraOperacion select").find("option:selected").not("option[value='0']").length;
	
if( LENGTH_Cliente==0 ){
    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Seleccione cabeceras de cliente','400px'));
    CargaCarteraDAO.setTimeOut_hide_message();
    return false;
}else if( LENGTH_Cuenta==0 ){
    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Seleccione cabeceras de cuenta','400px'));
    CargaCarteraDAO.setTimeOut_hide_message();
    return false;
}/*else if( LENGTH_Operacion==0 ){
		$('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Seleccione cabeceras de operacion','400px'));
		CargaCarteraDAO.setTimeOut_hide_message();
		return false;
	}*/
	
if( rs ){
    var rsC=confirm("Verifique que los datos seleccionados son los correctos");
    if( rsC ){
        CargaCarteraDAO.generateTable();	
    }
}
}
generateNOCpre_masivamente = function ( ) {
    var arch=$('#hddFileNocPre').val();
    var carSep=$('#cbCaracterSeparadorNOCPre').val();
    var formatFechas=$('#cbFormatoFechasNOCPre option:selected').text();
    if(arch==''){
        alert("No ha subido Archivo(s)");
        return false;
    }
    var rsC=confirm("Caracter Separador = "+carSep+"\nFormato de Fechas = "+formatFechas+"\n\nCONFIRMA que los datos son Correctos");
    if( rsC ){
        CargaCarteraDAO.generateNOCpreMasivo();	
    }
} 
generateCourier_masivamente = function ( ) {
    var arch=$('#hddFileCourier').val();
    var carSep=$('#cbCaracterSeparadorCourier').val();
    var tipo=$('#cbTipoCargaCourier option:selected').text();
    var formatFechas=$('#cbFormatoFechasCourier option:selected').text();
    if(arch==''){
        alert("No ha subido Archivo(s)");
        return false;
    }
    var rsC=confirm("Caracter Separador = "+carSep+"\nFormato de Fechas = "+formatFechas+"\nTipo = "+tipo+"\n\nCONFIRMA que los datos son Correctos");
    if( rsC ){
        CargaCarteraDAO.generateCourierMasivo();
    }
    
}
generateEstadoCuenta_masivamente = function ( ) {
    
    var arch=$('#hddFileEstadoCuenta').val();
    var campania = $('#cboCampaniaEstadoCuenta').val();
    var carSep=$('#cbCaracterSeparadorEstadoCuenta').val();
    if(arch==''){
        alert("No ha subido Archivo(s)");
        return false;
    }
    
    if( campania == '0' ) {
        alert("Seleccione campania");
        return false;
    }
    
    var rsC=confirm("Caracter Separador = "+carSep+"\n\nCONFIRMA que los datos son Correctos");
    if( rsC ) {
        CargaCarteraDAO.generateEstadoCuentaMasivo();
    }
    
}
generateSaldoTotal_masivamente = function ( ) {
    
    var arch=$('#hddFileSaldoTotal').val();
    var carSep=$('#cbCaracterSeparadorSaldoTotal').val();
    if(arch==''){
        alert("No ha subido Archivo(s)");
        return false;
    }

    var rsC=confirm("Caracter Separador = "+carSep+"\n\nCONFIRMA que los datos son Correctos");
    if( rsC ) {
        
        CargaCarteraDAO.generateSaldoTotalMasivo();
        
    }
    
}
generateDetalleM_masivamente = function ( ) {
    
    var arch=$('#hddFileDetalleM').val();
    var campania = $('#cboCampaniaDetalleM').val();
    var cartera = $('#cboCarteraDetalleM').val();
    var carSep=$('#cbCaracterSeparadorDetalleM').val();
    if(arch==''){
        alert("No ha subido Archivo(s)");
        return false;
    }
    
    if( cartera == '0' || cartera == '' ) {
        alert("Seleccione cartera");
        return false;
    }
    
    var rsC=confirm(" Cartera = "+$('#cboCarteraDetalleM option:selected').text()+"\n\nCaracter Separador = "+carSep+"\n\nCONFIRMA que los datos son Correctos");
    if( rsC ) {
        
        CargaCarteraDAO.generateDetalleMMasivo();
        
    }
    
}
generateIVR_masivamente = function ( ) {
    var arch=$('#hddFileIVR').val();
    var carSep=$('#cbCaracterSeparadorIVR').val();
    var formatFechas=$('#cbFormatoFechasIVR option:selected').text();
    var idcampania=$('#cboCampaniaIVR').val();
    if(idcampania==''){
        alert("Seleccione Campa\xf1a");
        return false;
    }
    if(arch==''){
        alert("No ha subido Archivo(s)");
        return false;
    }
    var rsC=confirm("Caracter Separador = "+carSep+"\nFormato de Fechas = "+formatFechas+"\n\nCONFIRMA que los datos son Correctos");
    if( rsC ){
        CargaCarteraDAO.generateIVRMasivo();	
    }
} 
generateRetiro_masivamente = function ( ) {
    var arch=$('#hddFileRetiro').val();
    var carSep=$('#cbCaracterSeparadorRetiro').val();
    var formatFechas=$('#cbFormatoFechasRetiro option:selected').text();
    var carteras = $('#tb2CarterasCargaRetiro').find(':checked').map( function (  ) { return $(this).val(); } ).get().join(",");
    if(arch==''){
        alert("No ha subido Archivo(s)");
        return false;
    }
    if( carteras == '' ){
        
        var rs = confirm("No ha seleccionado ninguna gestion, desea cargar el archivo de retiro");
        if( rs ) {
            var rsC=confirm("Caracter Separador = "+carSep+"\nFormato de Fechas = "+formatFechas+"\n\nCONFIRMA que los datos son Correctos");
            if( rsC ){
                CargaCarteraDAO.generateRetiroMasivo(carteras);	
            }
        }
        
    }else{
        var rsC=confirm("Caracter Separador = "+carSep+"\nFormato de Fechas = "+formatFechas+"\n\nCONFIRMA que los datos son Correctos");
        if( rsC ){
            CargaCarteraDAO.generateRetiroMasivo(carteras);	
        }
    }
} 

generarPagos = function ( ) {
	
    var LENGTH=$('#panelCargarPagoMain #layerHeaderPago select').find('option:selected').not("option[value='0']").length;
	
    if( LENGTH==0 ) {
        $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Seleccione cebeceras a cargar','400px'));
        CargaCarteraDAO.setTimeOut_hide_message();
        return false;
    }
	
    var rs=true;/*validacion.check([
		{id:'cboCampaniaPago',isNotValue:0,errorNotValueFunction:function( ){
				$('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Seleccione campaña','400px'));
				CargaCarteraDAO.setTimeOut_hide_message();
			}}
			/*,
		{id:'cbCartera',isNotValue:0,errorNotValueFunction:function( ){
				$('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Seleccione cartera','400px'));
				CargaCarteraDAO.setTimeOut_hide_message();
			}}
		]);*/
	
    if( rs ){
        var rsC=confirm("Verifique que los datos seleccionados son los correctos");
        if( rsC ) {
            CargaCarteraDAO.generatePago();	
        }
    }
}
generarCentroPagos = function ( ) {
    var LENGTH=$('#panelCargarCentroPago #layerHeaderCentroPago select').find('option:selected').not("option[value='0']").length;
    if( LENGTH==0 ) {
        $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Seleccione cabeceras a cargar','400px'));
        CargaCarteraDAO.setTimeOut_hide_message();
        return false;
    }
	
    var rs=validacion.check([
    {
        id:'txtCargaCentroPagoNombre',
        required:true,
        errorRequiredFunction:function( ){
            $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Ingrese nombre general de centro de pago','400px'));
            CargaCarteraDAO.setTimeOut_hide_message();
        }
    }
    ]);
		
if( rs ){
    var rsC=confirm("Verifique que los datos seleccionados son los correctos");
    if( rsC ){
        CargaCarteraDAO.generateCentroPago();	
    }
}
}
generateDetalle = function ( ) {
    var rsC=confirm("Verifique que los datos seleccionados son los correctos");
    if( rsC ){
        CargaCarteraDAO.generateDetalle();	
    }
}
generateRRLL = function ( ) {
    var rsC=confirm("Verifique que los datos seleccionados son los correctos");
    if( rsC ){
        CargaCarteraDAO.generateRRLL();	
    }
}
generateReclamo = function ( ) {
    var rsC=confirm("Verifique que los datos seleccionados son los correctos");
    if( rsC ){
        CargaCarteraDAO.generateReclamo();	
    }
}
generateTelefono = function ( ) {
    var rsC=confirm("Verifique que los datos seleccionados son los correctos");
    if( rsC ){
        CargaCarteraDAO.generateTelefono();	
    }
}
clean_adicionales_cliente_planta = function ( ) {
    $('#layerTabCargaCarteraPlantaDatosAdicionales select[id^="ca_datos_adicionales_"]').empty();
    var field=new Array();
    var fieldAdicionales=new Array();
    var obj=$('#panelCargarPlanta div[id^="layerTabCargaCarteraPlanta"]').not('#layerTabCargaCarteraPlantaDatosAdicionales').find('select option:selected').not('option[value="0"]');
    $.each(obj,function(key,data){
        field.push($(this).text());
    });
    for( j=0;j<CargaCarteraDAO.headerPlanta.length;j++ ){
        var count=0;
        for( i=0;i<field.length;i++ ) {
            if( $.trim(field[i])==$.trim(CargaCarteraDAO.headerPlanta[j]) ) {
                count++;
            }
        }
		
        if( count==0 ) {
            fieldAdicionales.push(CargaCarteraDAO.headerPlanta[j]);
        }
    }
	
    var html='';
    for( i=0;i<fieldAdicionales.length;i++ ) {
        html+='<option>'+fieldAdicionales[i]+'</option>';
    }
    $('#layerTabCargaCarteraPlantaDatosAdicionales #ca_datos_adicionales_planta').html(html);
}
clean_adicionales = function ( ) {
    $('#layerTabCargaCarteraDatosAdicionales select[id^="ca_datos_adicionales_"]').empty();
    var field=new Array();
    var fieldAdicionales=new Array();
    //var obj=$('div[id^="layerTabCargaCartera"]').not('#layerTabCargaCarteraDatosAdicionales').find('select option:selected').not('option[value="0"]');
    var obj=$('#panelCargarCarteraMain div[id^="layerTabCargaCartera"]').not('#layerTabCargaCarteraDatosAdicionales').find('select option:selected').not('option[value="0"]');
    $.each(obj,function(key,data){
        field.push($(this).text());
    });
    for( j=0;j<CargaCarteraDAO.header.length;j++ ){
        var count=0;
        for( i=0;i<field.length;i++ ) {
            if( $.trim(field[i])==$.trim(CargaCarteraDAO.header[j]) ) {
                count++;
            }
        }
		
        if( count==0 ) {
            fieldAdicionales.push(CargaCarteraDAO.header[j]);
        }
    }
	
    var html='';
    for( i=0;i<fieldAdicionales.length;i++ ) {
        html+='<option>'+fieldAdicionales[i]+'</option>';
    }
    //$('#layerTabCargaCarteraDatosAdicionales #ca_datos_adicionales_cliente').html(html);
    $('#layerTabCargaCarteraDatosAdicionales #adicionales_cartera').html(html);
}
clean_adicionales_detalle = function ( ) {
    var html='';
    var field=new Array();
    var xobj=$('#panelCargarDetalle').find('#tableDataOperacionCarteraDetalle,#tableDataCuentaCarteraDetalle').find('select option:selected').not('option[value="0"]');
    $.each(xobj,function(key,data){
        field.push($(this).text());
    });
    for( i=0;i<CargaCarteraDAO.headerDetalle.length;i++ ) {
        var count=0;
        for( j=0;j<field.length;j++ ) {
            if( CargaCarteraDAO.headerDetalle[i]==field[j] ) {
                count++;
            }
        }
		
        if( count==0 ) {
            html+='<option>'+CargaCarteraDAO.headerDetalle[i]+'</option>';
        }
    }
    $('#panelCargarDetalle').find('#layerHeaderDetalle').find('select[id="ca_datos_adicionales_detalle_cuenta"]').empty();
    $('#panelCargarDetalle').find('#trDataAdicionalesCarteraDetalle').find('select[id="adicionales_cartera_detalle"]').html(html);
}
add_cuenta = function ( ) {
    var data=$('#ca_datos_adicionales_cliente option:selected').text();
    if(data=='') {
        return false;	
    }
    $('#ca_datos_adicionales_cuenta').append('<option>'+data+'</option>');
    $('#ca_datos_adicionales_cliente option:selected').remove();
}
remove_cuenta = function ( ) {
    var data=$('#ca_datos_adicionales_cuenta option:selected').text();
    if(data=='') {
        return false;	
    }
    $('#ca_datos_adicionales_cliente').append('<option>'+data+'</option>');
    $('#ca_datos_adicionales_cuenta option:selected').remove();
}
add_operacion = function ( ) {
    var data=$('#ca_datos_adicionales_cuenta option:selected').text();
    if(data=='') {
        return false;	
    }
    $('#ca_datos_adicionales_detalle_cuenta').append('<option>'+data+'</option>');
    $('#ca_datos_adicionales_cuenta option:selected').remove();
}
remove_detalle_cuenta = function ( ) {
    var data=$('#ca_datos_adicionales_detalle_cuenta option:selected').text();
    if(data=='') {
        return false;	
    }
    $('#ca_datos_adicionales_cuenta').append('<option>'+data+'</option>');
    $('#ca_datos_adicionales_detalle_cuenta option:selected').remove();
}
listar_carteras = function ( id, idcbo ) {
    CargaCarteraDAO.ListCartera(id,CargaCarteraDAO.FillCargaCartera,idcbo);
}
listar_cartera_pago = function ( id ) {
    CargaCarteraDAO.ListCartera(id,CargaCarteraDAO.FillPagoCartera);
}
listar_cartera_comision = function ( id ) {
    CargaCarteraDAO.ListCartera(id,CargaCarteraDAO.FillComisionCartera);
}
listar_cartera_comision_generico = function ( id ) {
    CargaCarteraDAO.ListCartera(id,CargaCarteraDAO.FillComisionGenericoCartera);
}
listar_porcentajes_comision = function ( id ) {
    CargaCarteraDAO.ListarTramo(id);
}
listar_cartera_telefono = function ( id ) {
    CargaCarteraDAO.ListCartera(id,CargaCarteraDAO.FillTelefonoCartera);
}
listar_cartera_detalle = function ( id ) {
    CargaCarteraDAO.ListCartera(id,CargaCarteraDAO.FillDetalleCartera);
}
listar_cartera_reclamo = function ( id ) {
    CargaCarteraDAO.ListCartera(id,CargaCarteraDAO.FillReclamoCartera);
}
listar_cartera_rrll = function ( id ) {
    CargaCarteraDAO.ListCartera(id,CargaCarteraDAO.FillRRLLCartera);
}
listar_estado_llamadaG = function ( ) {
    CargaCarteraDAO.ListarEstadoLlamadaG(
        function ( obj ) 
        {
            var html = '';
            html+='<option value="0">--Seleccione--</option>';
            for( i=0;i<obj.length;i++ ) {
                html+='<option value="'+obj[i].idfinal+'">'+obj[i].nombre+'</option>';
            }
            $('#cbEstadoContacIVR,#cbEstadoNoContacIVR,#cbEstadoNocPre').html(html);
        }
    );
}
save_comision = function ( ) {
    var data='['+$('#AGU_layer_tramo_bottom #LayerTableComision #DataLayerTableComision').find('tr').map(function ( ){
        return '{"tramo":"'+$(this).attr('id')+'","porcentaje":"'+$(this).find(':text:first').val()+'"}';
    }).get().join(",")+']';
	
    var rs=validacion.check([
    {
        id:'AGU_layer_tramo_bottom #cbCarteraComision',
        isNotValue:0,
        errorNotValueFunction:function( ){
            $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Seleccione cartera a aplicar comision','400px'));
            CargaCarteraDAO.setTimeOut_hide_message();
        }
    }
    ]);
		
	
if( rs ){
    var rsC=confirm("Verifique si los datos ingresados son los correctos");
    if( rsC ){
        //CargaCarteraDAO.save_comision(data);
        CargaCarteraDAO.save_comision_tramo(data);
    }
}
}
save_comision_generico = function ( ) {
    var rs=validacion.check([
    {
        id:'AGU_layer_generico_bottom #cbCarteraComisionGenerico',
        isNotValue:0,
        errorNotValueFunction:function( ){
            $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Seleccione cartera a aplicar comision','400px'));
            CargaCarteraDAO.setTimeOut_hide_message();
        }
    },

    {
        id:'AGU_layer_generico_bottom #txtPorcentajeComisionGenerico',
        required:true,
        errorRequiredFunction:function( ){
            $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Ingrese porcentaje de comision','400px'));
            CargaCarteraDAO.setTimeOut_hide_message();
        }
    }
]);
	
if( rs ){
    var rsC=confirm("Verifique si los datos ingresados son los correctos");
    if( rsC ) {
        //CargaCarteraDAO.save_comision_generico(data);
        CargaCarteraDAO.save_comision_generico_servicio();
    }
}
}
generateTableAutomatic = function ( ) {
    var rs=false;
    var proceso=$('#cboTipoProceso').val();
    if( proceso=='carga' ){
        rs=validacion.check([
        {
            id:'cboCampania',
            isNotValue:0,
            errorNotValueFunction:function( ){
                $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Seleccione campaña','400px'));
                CargaCarteraDAO.setTimeOut_hide_message();
            }
        },

        {
            id:'txtNombreCartera',
            required:true,
            errorRequiredFunction:function( ){
                $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Ingrese nombre de cartera','400px'));
                CargaCarteraDAO.setTimeOut_hide_message();
            }
        }
    ]);
}else if( proceso=='actualizacion' ) {
    rs=validacion.check([
    {
        id:'cboCampania',
        isNotValue:0,
        errorNotValueFunction:function( ){
            $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Seleccione campaña','400px'));
            CargaCarteraDAO.setTimeOut_hide_message();
        }
    },

    {
        id:'txtNombreCartera',
        required:true,
        errorRequiredFunction:function( ){
            $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Ingrese nombre de cartera','400px'));
            CargaCarteraDAO.setTimeOut_hide_message();
        }
    },
{
    id:'cboCarteraActualizar',
    isNotValue:0,
    errorNotValueFunction:function( ){
        $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Seleccione cartera a actualizar','400px'));
        CargaCarteraDAO.setTimeOut_hide_message();
    }
}
]);
}else{
    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Proceso no reconocido','400px'));
    CargaCarteraDAO.setTimeOut_hide_message();
    return false;
}
if( rs ){
    var rsC=confirm("Verifique si los datos ingresados son los correctos");
    if( rsC ) {
        CargaCarteraDAO.CargaAutomatica();
    }
}
}
generateTablePagoAutomatic = function ( ) {
    var rs=validacion.check([
    {
        id:'cboCampaniaPago',
        isNotValue:0,
        errorNotValueFunction:function( ){
            $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Seleccione campaña','400px'));
            CargaCarteraDAO.setTimeOut_hide_message();
        }
    },

    {
        id:'cbCartera',
        isNotValue:0,
        errorNotValueFunction:function( ){
            $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Seleccione cartera','400px'));
            CargaCarteraDAO.setTimeOut_hide_message();
        }
    }
]);
if( rs ){
    var rsC=confirm("Verifique si los datos ingresados son los correctos");
    if( rsC ) {
        CargaCarteraDAO.CargaAutomaticaPago();
    }
}
}
cancel_carga_cartera = function ( ) {
    var html='<option value="0">--Seleccione--</option>';
    $('#panelCargarCarteraMain').not('#layerTabCargaCarteraCliente').find(':text,:hidden,:file').val('');
    $('#panelCargarCarteraMain').not('#layerTabCargaCarteraCliente').find('select').val(0);
    $("#panelCargarCarteraMain div[id^='layerTabCargaCartera']").not('#layerTabCargaCarteraDatosAdicionales').find("select").html(html);
    $('#panelCargarCarteraMain #layerTabCargaCarteraDatosAdicionales').find('select[id^="ca_datos_adicionales_"]').empty();
    /****************/
    $('#selectHeaderNotCarteraMain #TDnewHeaderCarteraMain').empty();
    $('#selectHeaderNotCarteraMain').hide();
/****************/
}
cancel_carga_cartera_masiva = function ( hdd,msg ) {
    $('#'+hdd).val('');
    $('#'+msg).html('');
}

cancel_carga_cartera_pago = function ( ) {
    var html='<option value="0">--Seleccione--</option>';
    $('#panelCargarPagoMain #layerHeaderPago').find('select').html(html);
    $('#panelCargarPagoMain #hddFilePago').val('');
    $('#panelCargarPagoMain').not('#layerHeaderPago').find(':text,:hidden,:file').val('');
    $('#panelCargarPagoMain').not('#layerHeaderPago').find('select').not('#cbCaracterSeparadorPago').val(0);
    /*************/
    $('#selectHeaderNotCarteraPago #TDnewHeaderCarteraPago').empty();
    $('#selectHeaderNotCarteraPago').hide();
/*************/
}
camcel_carga_cartera_centro_pago = function ( ) {
    var html='<option value="0">--Seleccione--</option>';
    $('#panelCargarCentroPago #layerHeaderCentroPago').find('select').html(html);
    $('#panelCargarCentroPago').not('#layerHeaderCentroPago').find(':text,:hidden,:file').val('');
    $('#panelCargarCentroPago').not('#layerHeaderCentroPago').find('select').not('#cbCaracterSeparadorCentroPago').val(0);
}
cancel_carga_cartera_reclamo = function ( ) {
    var html='<option value="0">--Seleccione--</option>';
    $('#panelCargarReclamos #layerHeaderReclamo').find('select').html(html);
    $('#panelCargarReclamos').not('#layerHeaderReclamo').find(':text,:hidden,:file').val('');
    /*************/
    $('#selectHeaderNotCarteraReclamo #TDnewHeaderCarteraReclamo').empty();
    $('#selectHeaderNotCarteraReclamo').hide();
}
cancel_carga_cartera_rrll = function ( ) {
    var html='<option value="0">--Seleccione--</option>';
    $('#panelCargarRRLL #layerHeaderRRLL').find('select').html(html);
    $('#panelCargarRRLL').not('#layerHeaderRRLL').find(':text,:hidden,:file').val('');
    /*************/
    $('#selectHeaderNotCarteraRRLL #TDnewHeaderCarteraRRLL').empty();
    $('#selectHeaderNotCarteraRRLL').hide();
}
cancel_carga_cartera_telefono = function ( ) {
    var html='<option value="0">--Seleccione--</option>';
    $('#panelCargarTelefono #layerHeaderTelefono').find('select').html(html);
    $('#panelCargarTelefono').not('#layerHeaderTelefono').find(':text,:hidden,:file').val('');
    /*************/
    $('#selectHeaderNotCarteraTelefono #TDnewHeaderCarteraTelefono').empty();
    $('#selectHeaderNotCarteraTelefono').hide();
}
agregar_adicional_cliente = function ( xtype ) {
    var xlabel = ( $.trim( $('#layerTabCargaCarteraDatosAdicionales #txt_adicionales_cartera').val() ) == '' )
    ?
    $('#layerTabCargaCarteraDatosAdicionales #adicionales_cartera option:selected').text()
    :
    $('#layerTabCargaCarteraDatosAdicionales #txt_adicionales_cartera').val()
    ;
    var xtext = $('#layerTabCargaCarteraDatosAdicionales #adicionales_cartera option:selected').text();
	
    var html = '<option value="'+xtext+'" label="'+xtext+'">'+xlabel+'</option>';
    if( xtype == 'cliente' ) {
        $('#layerTabCargaCarteraDatosAdicionales #ca_datos_adicionales_cliente').append(html);
    }else if( xtype == 'cuenta' ) {
        $('#layerTabCargaCarteraDatosAdicionales #ca_datos_adicionales_cuenta').append(html);
    }else if( xtype == 'detalle_cuenta' ) {
        $('#layerTabCargaCarteraDatosAdicionales #ca_datos_adicionales_detalle_cuenta').append(html);
    }
	
    $('#layerTabCargaCarteraDatosAdicionales #adicionales_cartera option:selected').remove();
    $('#layerTabCargaCarteraDatosAdicionales #txt_adicionales_cartera').val('');
	
}
remove_adicional_cartera = function ( xtype ) {
    var xfield = $('#layerTabCargaCarteraDatosAdicionales #ca_datos_adicionales_'+xtype+' option:selected').attr('label');
    var html = '<option>'+xfield+'</option>';
    $('#layerTabCargaCarteraDatosAdicionales #adicionales_cartera').append(html);
    $('#layerTabCargaCarteraDatosAdicionales #ca_datos_adicionales_'+xtype+' option:selected').remove();
}
agregar_adicional_detalle = function ( ) {
    var xlabel = ( $.trim( $('#panelCargarDetalle #txt_adicionales_cartera_detalle').val() ) == '' )
    ?
    $('#panelCargarDetalle #adicionales_cartera_detalle option:selected').text()
    :
    $('#panelCargarDetalle #txt_adicionales_cartera_detalle').val()
    ;
    var xtext = $('#panelCargarDetalle #adicionales_cartera_detalle option:selected').text();
	
    var html = '<option value="'+xtext+'" label="'+xtext+'">'+xlabel+'</option>';

    $('#panelCargarDetalle #ca_datos_adicionales_detalle_cuenta').append(html);
	
    $('#panelCargarDetalle #adicionales_cartera_detalle option:selected').remove();
	
}
remove_adicional_cartera_detalle = function ( ) {
    var xfield = $('#panelCargarDetalle #ca_datos_adicionales_detalle_cuenta option:selected').attr('label');
    var html = '<option>'+xfield+'</option>';
    $('#panelCargarDetalle #adicionales_cartera_detalle').append(html)
    $('#panelCargarDetalle #ca_datos_adicionales_detalle_cuenta option:selected').remove();
}
listar_carteras_servicio = function ( ) {
	
    CargaCarteraDAO.CarterasServicio( $('#hdCodServicio').val(), function ( obj ) {
        var htmltb = '';
        var html = '';
        html+='<option value="0">--Seleccione--</option>';
        for( i=0;i<obj.length;i++ ) {
            html+='<option value="'+obj[i].idcartera+'">'+obj[i].nombre_cartera+'</option>';
				
            htmltb+='<tr>';
            htmltb+='<td align="center" style="width:20px;" class="ui-widget-header" style="padding:2px 0;">'+(i+1)+'</td>';
            htmltb+='<td align="center" style="width:180px;" class="ui-widget-content" style="padding:2px 0;">'+obj[i].nombre_cartera+'</td>';
            htmltb+='<td align="center" style="width:20px;" class="ui-widget-content" style="padding:2px 0;"><input type="checkbox" value="'+obj[i].idcartera+'" /></td>';
            htmltb+='</tr>';
        }
        $('#cbPlantillasCargaCarteraMain').html(html);
        $('table[id^="tbCarterasCarga"]').html(htmltb);
    } );
		
}
parser_data_template = function ( idcartera ) {
	
    if( idcartera == 0 ) {
        return false;
    }
	
    CargaCarteraDAO.DataTemplate( idcartera, function ( objectJSON ) {
				
        if( CargaCarteraDAO.header.length == 0 ) {
            $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError("No se ha levantado ninguna informacion de cartera",'500px'));
            CargaCarteraDAO.setTimeOut_hide_message();
            return false;
        }
				
        if( objectJSON.length>0 ) {
            var obj = objectJSON[0];
            /************ Cliente **************/
            if( obj.cliente ) {
                var dataCliente = eval( obj.cliente ) ;
                for( i=0;i<dataCliente.length;i++ ) {
                    var cont =0 ;
                    for( j=0;j<CargaCarteraDAO.header.length;j++ ) {
                        if( dataCliente[i].dato == CargaCarteraDAO.header[j] ) {
                            cont++;
                        }
                    }
							
                    if( cont>0 ) {
                        $('#layerTabCargaCarteraCliente').find('select[id="'+dataCliente[i].campoT+'"]').val(dataCliente[i].dato);
                        $('#layerTabCargaCarteraCliente').find(':text[id="txt_'+dataCliente[i].campoT+'"]').val(dataCliente[i].label);
                    }
                }
            }
            /*********************/
            /************* Cuenta ***********/
            if( obj.cuenta ) {
                var dataCuenta = eval( $.parseJSON(obj.cuenta) );
                for( i=0;i<dataCuenta.length;i++ ) {
                    var cont =0 ;
                    for( j=0;j<CargaCarteraDAO.header.length;j++ ) {
                        if( dataCuenta[i].dato == CargaCarteraDAO.header[j] ) {
                            cont++;
                        }
                    }
                    if( cont>0 ) {
                        $('#layerTabCargaCarteraCuenta').find('select[id="'+dataCuenta[i].campoT+'"]').val(dataCuenta[i].dato);
                        $('#layerTabCargaCarteraCuenta').find(':text[id="txt_'+dataCuenta[i].campoT+'"]').val(dataCuenta[i].label);
                    }
                }
            }
            /*************************/
            /********* Detalle Cuenta *********/
            if( obj.detalle_cuenta ) {
                var dataDetalleCuenta = eval( obj.detalle_cuenta );
                for( i=0;i<dataDetalleCuenta.length;i++ ) {
                    var cont =0 ;
                    for( j=0;j<CargaCarteraDAO.header.length;j++ ) {
                        if( dataDetalleCuenta[i].dato == CargaCarteraDAO.header[j] ) {
                            cont++;
                        }
                    }
                    if( cont>0 ) { 
                        $('#layerTabCargaCarteraOperacion').find('select[id="'+dataDetalleCuenta[i].campoT+'"]').val(dataDetalleCuenta[i].dato);
                        $('#layerTabCargaCarteraOperacion').find(':text[id="txt_'+dataDetalleCuenta[i].campoT+'"]').val(dataDetalleCuenta[i].label);									
                    }
                }
            }
            /************************/
            /*********** Telefono ************/
            if( obj.telefono ) {
                var dataTelefono = eval( $.parseJSON( obj.telefono ) );
                for( index in dataTelefono ) {
                    for( index2 in dataTelefono[index] ) {
                        var cont =0 ;
                        for( j=0;j<CargaCarteraDAO.header.length;j++ ) {
                            if( dataTelefono[index][index2] == CargaCarteraDAO.header[j] ) {
                                cont++;
                            }
                        }
                        if( cont>0 ) {
                            $('#layerTabCargaCarteraTelefono').find('div[title="'+index+'"]').find('select[id="'+index2+'"]').val(dataTelefono[index][index2]);
                        }
								
                    }
                }
            }
            /********************/
            /*********** Direccion ************/
            if( obj.direccion ) {
                var dataDireccion = eval( $.parseJSON( obj.direccion ) );
                for( index in dataDireccion ) {
                    for( index2 in dataDireccion[index] ) {
                        var cont =0 ;
                        for( j=0;j<CargaCarteraDAO.header.length;j++ ) {
                            if( dataDireccion[index][index2] == CargaCarteraDAO.header[j] ) {
                                cont++;
                            }
                        }
                        if( cont>0 ) {
                            $('#layerTabCargaCarteraDireccion').find('div[title="'+index+'"]').find('select[id="'+index2+'"]').val(dataDireccion[index][index2]);
                        }
                    }
                }
            }
            /************************/
            /******** Adicionales *******/
            if( obj.adicionales ) {
                var dataAdicionales = eval( $.parseJSON( obj.adicionales ) );
                for( index in dataAdicionales ) {
                    var html='';
                    for( i=0;i<dataAdicionales[index].length;i++ ) {
                        var cont =0 ;
                        for( j=0;j<CargaCarteraDAO.header.length;j++ ) {
                            if( dataAdicionales[index][i].dato == CargaCarteraDAO.header[j] ) {
                                cont++;
                            }
                        }
                        if( cont>0 ) { 
                            html+='<option label="'+dataAdicionales[index][i].dato+'" >'+dataAdicionales[index][i].label+'</option>';
                        }
                    }
                    $('#layerTabCargaCarteraDatosAdicionales').find('select[id="'+index+'"]').html(html);
                }
            }
					
            /******************/
					
            var cabeceras = (obj.cabeceras).split(",");
            var dataCabeceras = new Array();
            for( j=0;j<CargaCarteraDAO.header.length;j++ ) {
                var count = 0;
                for( i=0;i<cabeceras.length;i++ ) {

                    if( CargaCarteraDAO.header[j] != cabeceras[i] ) {
                        count++;
                    }
                }
                if( count > 0 ) {
                    dataCabeceras.push(CargaCarteraDAO.header[j]);
                    
                }
            }
					
            if( dataCabeceras.length != 0 ) {
                $('#selectHeaderNotCarteraMain #TDnewHeaderCarteraMain').html('<pre style="white-space:normal;">'+dataCabeceras.join(",\t")+'</pre>');
                $('#selectHeaderNotCarteraMain').fadeIn(2000);
                $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError("Se encontraron cabeceras nuevas",'400px'));
                CargaCarteraDAO.setTimeOut_hide_message();
            }
					
            var html = '';
            for( i=0;i<dataCabeceras.length;i++ ) {
                html+='<option value="'+dataCabeceras[i]+'">'+dataCabeceras[i]+'</option>';
            }
            $('#layerTabCargaCarteraDatosAdicionales').find('select[id="adicionales_cartera"]').html(html);
					
        }
    } );
	
}
agregar_cabecera = function ( ) {
	
    var cabecera = $.trim( $('#txtNombreCabecera').val() );
    var longitud = parseFloat( $.trim( $('#txtLongitudCabecera').val() ) );
    var html = '';
    html='<option value="'+cabecera+'-'+longitud+'">'+cabecera+' - '+longitud+'</option>';
    $('#cbCabeceras').append(html);
}
listar_modulo_cabecera_por_servicio = function ( ) {
	
    CargaCarteraDAO.ListarModuloCabecerasPorServicio( $('#hdCodServicio').val(), function ( obj ) {
        var html = '';
        for( i=0;i<obj.length;i++ ) {
            html+='<tr>';
            html+='<td class="ui-widget-header" style="padding:2px 0;width:30px;" align="center">'+(i+1)+'</td>';
            html+='<td class="ui-widget-content" style="padding:2px 0;width:250px;" align="center">'+obj[i].nombre+'</td>';
            html+='<td class="ui-widget-content" style="padding:2px 0;width:150px;" align="center">'+obj[i].tipo+'</td>';
            html+='<td onclick = "listar_cabecera_por_id('+obj[i].idcabeceras_cartera+')" class="ui-widget-content" style="padding:2px 0;width:50px;" align="center"><span class="ui-icon ui-icon-pencil" title="Editar"></span></td>';
            html+='<td onclick = "delete_cabeceras('+obj[i].idcabeceras_cartera+')" class="ui-widget-content" style="padding:2px 0;width:50px;" align="center"><span class="ui-icon ui-icon-trash" title="Eliminar"></span></td>';
            html+='</tr>';
        }
        $('#tableModuloCabeceras').html(html);
    }, function ( ) { } ); 
	
}
listar_cabecera_por_id = function ( idcabecera ) {
	
    CargaCarteraDAO.ListarModuloCabecerasPorId( idcabecera, function ( obj ) {
        if( obj.length>0 ) {
            var cabeceras = $.parseJSON(obj[0].cabeceras);
            $('#hdIdCabeceras').val(obj[0].idcabeceras_cartera);
            $('#txtNombreGrupoCabeceras').val(obj[0].nombre);
            $('#cbTipoGrupoCabeceras').val(obj[0].tipo);
            var html = '';
            for( index in cabeceras ) {
                html+='<option value="'+index+'-'+cabeceras[index]+'">'+index+' - '+cabeceras[index]+'</option>';
            }
            $('#cbCabeceras').append(html);
        }
    } );
	
}
guardar_cabeceras = function ( ) {
    var xidservicio = $('#hdCodServicio').val();
    var xusuario_creacion = $('#hdCodUsuario').val();
    var xnombre = $('#txtNombreGrupoCabeceras').val();
    var xtipo = $('#cbTipoGrupoCabeceras').val();
    var xcabeceras = '{'+$('#cbCabeceras').find('option').map(function ( ) {
        var data = ($(this).val()).split("-");
        //return '"cabecera":"'+data[0]+'","longitud":'+data[1]+'';
        return '"'+data[0]+'":"'+data[1]+'"';
    }).get().join(",")+'}';
		
    CargaCarteraDAO.GuardarCabeceras( xidservicio, xcabeceras, xnombre, xtipo, xusuario_creacion, function ( obj ) {
        if( obj.rst ) {
            cancel_cabeceras();
            $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
            CargaCarteraDAO.setTimeOut_hide_message();
            listar_modulo_cabecera_por_servicio();
        }else{
            $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
            CargaCarteraDAO.setTimeOut_hide_message();
        }
    } );
	
}
cancel_cabeceras = function ( ) {
    $('#txtNombreGrupoCabeceras,#hdIdCabeceras').val('');
    $('#cbTipoGrupoCabeceras').val('cartera');
    $('#cbCabeceras').empty();
}
save_header = function ( ) {
    if( $('#hdIdCabeceras').val() == '' ) {
        var rs = confirm("Verifique que los datos ingresado son correctos");
        if( rs ) {
            guardar_cabeceras();
        }
    }else{
        var rs = confirm(" Desea actualizar los datos ");
        if( rs ) {
            actualizar_cabeceras();
        }
    }
}
actualizar_cabeceras = function ( ) {

    var xidcabeceras =  $('#hdIdCabeceras').val();
    var xusuario_modificacion = $('#hdCodUsuario').val();
    var xnombre = $('#txtNombreGrupoCabeceras').val();
    var xtipo = $('#cbTipoGrupoCabeceras').val();
    var xcabeceras = '{'+$('#cbCabeceras').find('option').map(function ( ) {
        var data = ($(this).val()).split("-");
        //return '{"cabecera":"'+data[0]+'","longitud":'+data[1]+'}';
        return '"'+data[0]+'":"'+data[1]+'"';
    }).get().join(",")+'}';
		
    CargaCarteraDAO.ActualizarCabeceras( xidcabeceras, xcabeceras, xnombre, xtipo, xusuario_modificacion, function ( obj ) {
        if( obj.rst ) {
            cancel_cabeceras();
            $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
            CargaCarteraDAO.setTimeOut_hide_message();
            listar_modulo_cabecera_por_servicio();
        }else{
            $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
            CargaCarteraDAO.setTimeOut_hide_message();
        }
    } );
	
}
delete_cabeceras = function ( idcabeceras ) {
	
    var xusuario_modificacion = $('#hdCodUsuario').val();
    CargaCarteraDAO.EliminarCabeceras( idcabeceras, xusuario_modificacion, function ( obj ) {
			
        if( obj.rst ) {
            listar_modulo_cabecera_por_servicio();
            $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
            CargaCarteraDAO.setTimeOut_hide_message();
        }else{
            $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
            CargaCarteraDAO.setTimeOut_hide_message();
        }
			
    } );
	
}
listar_modulo_cabecera_por_servicio_cartera = function ( ) {
	
    CargaCarteraDAO.ListarModuloCabecerasPorServicioCartera( $('#hdCodServicio').val(), function ( obj ) {
        var html = '';
        html+='<option value="0" >--Seleccione--</option>';
        for( i=0;i<obj.length;i++ ) {
            html+='<option value="'+obj[i].idcabeceras_cartera+'" >'+obj[i].nombre+'</option>';
        }
        $('#cbCabecerasCarteraMain').html(html);
    }, function ( ) { } ); 
	
}
listar_modulo_cabecera_por_servicio_pago = function ( ) {
	
    CargaCarteraDAO.ListarModuloCabecerasPorServicioPago( $('#hdCodServicio').val(), function ( obj ) {
        var html = '';
        html+='<option value="0" >--Seleccione--</option>';
        for( i=0;i<obj.length;i++ ) {
            html+='<option value="'+obj[i].idcabeceras_cartera+'" >'+obj[i].nombre+'</option>';
        }
        $('#cbCabecerasCarteraPago').html(html);
    }, function ( ) { } ); 
	
}
search_text_table = function ( xtext, idtable ) {
    var text = $.trim( xtext );
    text = text.toUpperCase();
    $('#'+idtable+' tr').css('display','none');
    $('#'+idtable+' tr').find('td:contains("'+text+'")').parent().css('display','block');
	
}
load_origen = function (  ) {
	
    CargaCarteraDAO.LoadOrigen( function ( obj ) {
        var html = '';
        html+='<option value="0">--Seleccione--</option>';
        for( i=0;i<obj.length;i++ ) {
            html+='<option value="'+obj[i].idorigen+'">'+obj[i].nombre+'</option>';
        }
        $('#cbCargaTelefonoOrigen').html(html);
    } );
		
}
rp3_buscar_por_fecha_envio = function ( ) {
	
	var fecha_inicio = $('#txtFechaEnvioInicioRP3').val();
	var fecha_fin = $('#txtFechaEnvioFinRP3').val();
	
	CargaCarteraDAO.RP3.BuscarPorFechaEnvio( fecha_inicio, fecha_fin, 
		function ( obj ) 
		{
		
			var html = '';
			for( i=0;i<obj.CarteraC.length;i++ ) {
				html+='<tr style="display:block;" >';
					html+='<td style="width:30px;padding:2px 0;" align="center" class="ui-state-default" >'+(i+1)+'</td>';
					html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj.CarteraC[i].SECUENCIA+'</td>';
					html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj.CarteraC[i].CANTIDAD+'</td>';
					html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj.CarteraC[i].FECHA+'</td>';
					html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj.CarteraC[i].HORA+'</td>';
					html+='<td style="width:20px;padding:2px 0;" align="center" class="ui-widget-content" ><input type="checkbox" name="rd_RP3_cartera_comercial_l" value="'+obj.CarteraC[i].SECUENCIA+'" datetime="'+obj.CarteraC[i].FECHA+' '+obj.CarteraC[i].HORA+'" /></td>';
				html+='</tr>';
			}
			
			$('#table_list_cartera_RP3_comercial').html(html);
			$('#table_list_cartera_RP3_comercial').find('tr').hover(function(){ $(this).find('td:gt(0)').addClass('ui-state-hover'); },function(){ $(this).find('td:gt(0)').removeClass('ui-state-hover'); }); 
			$('#table_list_cartera_RP3_comercial tr').click(function() {
								  
								  $(this).find('td:gt(0)').addClass("ui-state-highlight").parent().siblings().find('td:gt(0)').removeClass("ui-state-highlight");
					});
					
			var html = '';
			for( i=0;i<obj.CarteraB.length;i++ ) {
				html+='<tr style="display:block;" >';
					html+='<td style="width:30px;padding:2px 0;" align="center" class="ui-state-default" >'+(i+1)+'</td>';
					html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj.CarteraB[i].SECUENCIA+'</td>';
					html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj.CarteraB[i].CANTIDAD+'</td>';
					html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj.CarteraB[i].FECHA+'</td>';
					html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj.CarteraB[i].HORA+'</td>';
					html+='<td style="width:20px;padding:2px 0;" align="center" class="ui-widget-content" ><input type="checkbox" name="rd_RP3_cartera_comercial_l" value="'+obj.CarteraB[i].SECUENCIA+'" datetime="'+obj.CarteraB[i].FECHA+' '+obj.CarteraB[i].HORA+'" /></td>';
				html+='</tr>';
			}
			
			$('#table_list_cartera_RP3_banco').html(html);
			$('#table_list_cartera_RP3_banco').find('tr').hover(function(){ $(this).find('td:gt(0)').addClass('ui-state-hover'); },function(){ $(this).find('td:gt(0)').removeClass('ui-state-hover'); }); 
			$('#table_list_cartera_RP3_banco tr').click(function() {
								  
								  $(this).find('td:gt(0)').addClass("ui-state-highlight").parent().siblings().find('td:gt(0)').removeClass("ui-state-highlight");
					});
			
			var html = '';
			for( i=0;i<obj.PagosC.length;i++ ) {
				html+='<tr style="display:block;" >';
					html+='<td style="width:30px;padding:2px 0;" align="center" class="ui-state-default">'+(i+1)+'</td>';
					html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj.PagosC[i].GRUPO+'</td>';
					html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj.PagosC[i].CANTIDAD+'</td>';
					html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj.PagosC[i].FECHA+'</td>';
					html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj.PagosC[i].HORA+'</td>';
					html+='<td style="width:20px;padding:2px 0;" align="center" class="ui-widget-content" ><input type="checkbox" name="rd_RP3_pagos_comercial_l" group="'+obj.PagosC[i].GRUPO+'" value="'+obj.PagosC[i].FECHA+' '+obj.PagosC[i].HORA+'" /></td>';
				html+='</tr>';
			}
			
			$('#table_list_pagos_RP3_comercial').html(html);
			$('#table_list_pagos_RP3_comercial').find('tr').hover(function(){ $(this).find('td:gt(0)').addClass('ui-state-hover'); },function(){ $(this).find('td:gt(0)').removeClass('ui-state-hover'); }); 
			$('#table_list_pagos_RP3_comercial tr').click(function() {
								  $(this).find('td:gt(0)').addClass("ui-state-highlight").parent().siblings().find('td:gt(0)').removeClass("ui-state-highlight");
					});
					
			var html = '';
			for( i=0;i<obj.PagosB.length;i++ ) {
				html+='<tr style="display:block;" >';
					html+='<td style="width:30px;padding:2px 0;" align="center" class="ui-state-default">'+(i+1)+'</td>';
					html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj.PagosB[i].GRUPO+'</td>';
					html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj.PagosB[i].CANTIDAD+'</td>';
					html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj.PagosB[i].FECHA+'</td>';
					html+='<td style="width:70px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj.PagosB[i].HORA+'</td>';
					html+='<td style="width:20px;padding:2px 0;" align="center" class="ui-widget-content" ><input type="checkbox" name="rd_RP3_pagos_comercial_l" group="'+obj.PagosB[i].GRUPO+'" value="'+obj.PagosB[i].FECHA+' '+obj.PagosB[i].HORA+'" /></td>';
				html+='</tr>';
			}
			
			$('#table_list_pagos_RP3_banco').html(html);
			$('#table_list_pagos_RP3_banco').find('tr').hover(function(){ $(this).find('td:gt(0)').addClass('ui-state-hover'); },function(){ $(this).find('td:gt(0)').removeClass('ui-state-hover'); }); 
			$('#table_list_pagos_RP3_banco tr').click(function() {
								  $(this).find('td:gt(0)').addClass("ui-state-highlight").parent().siblings().find('td:gt(0)').removeClass("ui-state-highlight");
					});
					
				
		}
	 );
	
}
rp3_cargar_data_cartera = function ( xtipo ) {
	
	var xid = "";
	if( xtipo == 'comercial' ) {
		xid = "table_list_cartera_RP3_comercial";
	}else if( xtipo == 'banco' ){
		xid = "table_list_cartera_RP3_banco";
	}else{
		return false;
	}
	
	var data = '['+$('#'+xid).find(':checked').map(
			function()
			{
				return'{"secuencia":"'+$(this).val()+'","datetime":"'+$(this).attr('datetime')+'"}';
			}
		).get().join(",")+']';
	
	if( data == '[]' ) {
		alert("Seleccione carteras a crear");
		return false;
	}
	
	var rs = confirm("Verifique que la cartera seleccionada es la correcta");
	
	if( rs ) {
		
		CargaCarteraDAO.RP3.CargarDataCartera( xtipo, data, 
			function ( obj )  
			{
				if( obj.rst ) {
					
					$('#panelCargarCarteraMain #txtCaracterSeparador').val('tab');
					$('#panelCargarCarteraMain #hddFile').val(obj.file);
					
					CargaCarteraDAO.loadHeaderFile(obj.file); 
					
					$('#aDisplayPanelCargarCartera').trigger('click');
					
					
					$('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
					
				}else{
					$('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'350px'));
				}
			}
		);
		
	}
	
}
rp3_cargar_data_pagos = function ( xtipo ) {
	
	
	var xid = "";
	if( xtipo == 'comercial' ) {
		xid = "table_list_pagos_RP3_comercial";
	}else if( xtipo == 'banco' ){
		xid = "table_list_pagos_RP3_banco";
	}else{
		return false;
	}
	
	var data = '['+$('#'+xid).find(':checked').map(
			function()
			{
				return'{"datetime":"'+$(this).val()+'","grupo":"'+$(this).attr('group')+'"}';
			}
		).get().join(",")+']';
	
	if( data == '[]' ) {
		alert("Seleccione pagos");
		return false;
	}
	
	var rs = confirm("Verifique que la cartera seleccionada es la correcta");
	
	if( rs ) {
		
		CargaCarteraDAO.RP3.CargarDataPagos( xtipo, data, 
			function ( obj )  
			{
				if( obj.rst ) {
					
					$('#panelCargarPagoMain #cbCaracterSeparadorPago').val('tab');
					$('#panelCargarPagoMain #hddFilePago').val(obj.file);
					
					CargaCarteraDAO.loadHeaderFilePago( obj.file ); 
					
					$('#aDisplayPanelCargarPago').trigger('click'); 
					
					$('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
					
				}else{
					$('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'350px'));
				}
			}
		);
		
	}
	
}
exportar_formato_respuesta_rp3 = function ( xcarteras, xfecha_inicio, xfecha_fin ) {
    
    if( xcarteras == '' ) {
        alert("Seleccione carteras");
        return false;
    }

    if( xfecha_inicio == '' ){
        alert("Ingrese fecha inicio");
        return false;
    }

    if( xfecha_fin == '' ) {
        alert("Ingrese fecha fin");
        return false;
    }
    
    window.location.href="../rpt/excel/RespuestaRP3.php?servicio="+$('#hdCodServicio').val()+"&carteras="+xcarteras+"&fecha_inicio="+xfecha_inicio+"&fecha_fin="+xfecha_fin;
    
}
rp3_enviar_respuesta = function ( xtipo, xcarteras, xfecha_inicio, xfecha_fin ) {
    
    if( xcarteras == '' ) {
        alert("Seleccione carteras");
        return false;
    }
    
    if( xfecha_inicio == '' ){
        alert("Ingrese fecha inicio");
        return false;
    }
    
    if( xfecha_fin == '' ) {
        alert("Ingrese fecha fin");
        return false;
    }
    
    var rs = confirm("Verifique si los datos ingresados son los correctos");
    
    if( rs ) {
    
        CargaCarteraDAO.RP3.EnviarRespuesta( 
            xtipo, xcarteras, xfecha_inicio, xfecha_fin, 
            function ( obj ) 
            {
    
                if( obj.rst ) {
                
                    var xurl = 'http://200.31.105.171:7777/wsHdec/wsResultado.php';
                    if( xtipo == 'banco' ){
                        xurl = 'http://200.31.105.171:7777/wsHdec/wsResultadoBanco.php';
                    }else if( xtipo == 'comercial' ){
                        xurl = 'http://200.31.105.171:7777/wsHdec/wsResultado.php';
                    }
                    
                    $.ajax({
                            url : xurl,
                            type : 'GET',
                            dataType : 'html',
                            beforeSend : function ( ) {
                                _displayBeforeSend('Enviando data...',250);
                            },
                            success : function ( rsp_text ) {
                                _noneBeforeSend();
                                
                                $('body').after('<div id="modalCobrastRP3">'+' Cantidad de Registros Enviados : '+obj.cantidad+'<br/>'+rsp_text+'</div>');
                                $('#modalCobrastRP3').dialog(
                                {
                                    modal : true,
                                    title : 'Mensaje',
                                    width : 550,
                                    resizable : true,
                                    buttons : {
                                        Aceptar : function()
                                        {
                                            $(this).remove();
                                        }
                                    },
                                    close : function(event,ui)
                                    {
                                        $(this).remove();
                                    }
                                }
                                );
                                $('#modalCobrastRP3').parent().css({
                                    top : '40%', 
                                    left : '40%'
                                });
    
                            },
                            error : function ( ) {
                                _noneBeforeSend();
                                CargaCarteraDAO.error_ajax();
                            }
                        });
                    
                    
                }else{
                    
                    CargaCarteraDAO.error_ajax();
                    
                }
    
            }
        );
    
    }
    
},
edit_header_load_data = function ( xidcartera ) {
    
    if( xidcartera == '0' ) {
        return false;
    }
    
    CargaCarteraDAO.EditHeader.LoadData( xidcartera,
        function ( obj )
        {
            
            var cuenta = $.parseJSON( obj[0].cuenta ) ;
            var detalle_cuenta = $.parseJSON( obj[0].detalle_cuenta ) ;
            var adicionales = $.parseJSON( obj[0].adicionales ) ;
            
            var html = '';
            for( i=0;i<cuenta.length;i++ ) {
                html+='<tr name="'+cuenta[i].campoT+'" >';
                    html+='<td>Texto</td>';
                    html+='<td><input type="text" class="cajaForm" name="'+cuenta[i].label+'" /></td>';
                    html+='<td>Color Fondo</td>';
                    html+='<td><input type="text" class="cajaForm" name="background" /></td>';
                    html+='<td>Color Texto</td>';
                    html+='<td><input type="text" class="cajaForm" name="color" /></td>';
                    html+='<td>Color Texto</td>';
                    html+='<td><input type="text" class="cajaForm" name="color" /></td>';
                html+='</tr>';
            }
            
        }
    );
    
}
search_gestion_RP3 = function ( xtext, xidtable ) {
	var text = $.trim(xtext);
	text = text.toUpperCase();
	$('#'+xidtable).find('tr').css('display','none');
	$('#'+xidtable+' tr').find('td:contains("'+text+'")').parent().css('display','block');
}
load_tipo_telefono = function ( ) {
	
    CargaCarteraDAO.LoadTipoTelefono( function ( obj ) {
        var html = '';
        html+='<option value="0">--Seleccione--</option>';
        for( i=0;i<obj.length;i++ ) {
            html+='<option value="'+obj[i].idtipo_telefono+'">'+obj[i].nombre+'</option>';
        }
        $('#cbCargaTelefonoTipo').html(html);
    } );
	
}
update_fecha_vencimiento = function ( ) {
    
    var cartera = $('#cboCarteraFechaVenc').val();
    var fec_venc = $('#txtCagCarFechaVencimiento').val();

    if( cartera == "0" || cartera == "" ) {
        alert("Seleccione cartera");
        return false;
    }

    if( fec_venc == "" ) {
        alert("Ingrese fecha de vencimiento");
        return false;
    }

    var rs = confirm("Verifique si los datos ingresados son los correctos");

    if( rs ) {

        CargaCarteraDAO.UpdateFechaVencimiento( cartera, fec_venc, 
            function ( obj ) 
            {
                _noneBeforeSend();
                if( obj.rst ) {
                    $('#txtCagCarFechaVencimiento').val('');
                }

                _displayBeforeSendDl(obj.msg,400);
            },
            function ( ) {
                _displayBeforeSend('Actualizando fecha',250);
            }
        );

    }

}
checked_all= function(element,idtb){
    if( element ) {
        
        $('#'+idtb).find('tr').map( function (  ) {
            if( $(this).css('display') == 'block' || $(this).css('display') == 'table-row' ) {
                $(this).find(':checkbox').attr('checked',true);
            }else{
                $(this).find(':checkbox').attr('checked',false);
            }
        } );
        
    }else{
        $('#'+idtb).find(':checkbox').attr('checked',false);
    }
}


generaSubirCargaLlamada = function(){
    var archivo=btnCargaLlamadas.value;

    if(archivo==""){
        alert('Seleccione archivo');
        return false;
    }

    CargaCarteraDAO.generaSubirCargaLlamada();
}
generaProcesarCargaLlamada = function(){
    if($("#xcarterames").val()!="0"){
        CargaCarteraDAO.generaProcesarCargaLlamada();    
    }else{
        alert("INGRESAR CARTERA");
    }
    

}

iniciar_cruce_telefono = function ( ) {

    var carteras_fl = $('#tbCarterasCargaCruceTelefono').find(':checked').map( function ( ) { return $(this).val(); } ).get().join(",");
    var cartera = $('#cboCarteraCruceTelefono').val();
    var fecha_inicio = $('#txtCruceTelefonoFechaInicio').val();
    var fecha_fin = $('#txtCruceTelefonoFechaFin').val();
    var tipo = $(':radio[name^="rdCruceLlamada"]:checked').val();

    if( carteras_fl == '' ) {
        alert("Seleccione carteras de filtro");    
        return false;
    }
    if( cartera == '0' || cartera == '' ) {
        alert("Seleccione cartera a cruzar");
        return false;
    }
    if( fecha_inicio == '' ) {
        alert("Ingrese fecha inicio");
        return false;
    }
    if( fecha_fin == '' ) {
        alert("Ingrese fecha fin");
        return false;
    }

    var rs = confirm("Verifique que los datos completados son los correctos");

    if( rs ) {
    
        CargaCarteraDAO.CruceTelefono.Iniciar( carteras_fl, cartera, tipo, fecha_inicio, fecha_fin, 
            function ( obj ) {
                
                if( obj.rst ) {
                    
                }else{
                    
                }

                _displayBeforeSendDl(obj.msg,400);

            }
        );

    }

}

function cargarProvisionTotal (){
 
    var idcartera=$('#tbCarterasCargaProvisionTotal').find(':checked').map(function(){
                    return $(this).val()
                    }).get().join(",");

    if($.trim(idcartera)==''){
        alert("Seleccionar Carteras");
        return false;
    } 

    if($('#txtFechaProvisionTotal').val()==""){
        alert('Elegir Fecha de la provision');
        return false;
    }
    
    if($('#uploadFileProvisionTotal').val()!=''){

        var rsC=confirm("Esta Seguro de Cargar Provision");

        if( rsC ){
           CargaCarteraDAO.cargarProvisionTotal();
        } 
        
    }else{
        _displayBeforeSendDl('Elegir Archivo txt',200);
        }

}


function updateMontosPagado (){

    var cartera = $('#tbCarterasCargaUpdateMontoPagado').find(':checked').
                    map(function(){
                        if($(this).val()!=25265 && $(this).val()!=25264 && $(this).val()!=25231 && $(this).val()!=25186 && $(this).val()!=25124 &&
                        $(this).val()!=25072 && $(this).val()!=474 && $(this).val()!=453 ){
                            return $(this).val();
                        }
                    
                    }).get().join(',');

    if( cartera == '' || cartera == undefined ){
        $('#msgUpdateMontoPagado').css('opacity','1').html('<span>Seleccionar carteras</span>');
        setTimeout(function(){
            $('#msgUpdateMontoPagado').css('opacity','0').html('<span></span>');

        },1300);
        return false;
    }

    CargaCarteraDAO.updateMontosPagado();

}

function NormalizarTelefono2 (){

    var cartera = $('#tbCarterasCargaNormalizarTelefono2').find(':checked').
                    map(function(){
                        
                            return $(this).val();
                       
                    
                    }).get().join(',');

    if( cartera == '' || cartera == undefined ){
        $('#msgNormalizarTelefono2').css('opacity','1').html('<span>Seleccionar carteras</span>');
        setTimeout(function(){
            $('#msgNormalizarTelefono2').css('opacity','0').html('<span></span>');

        },1300);
        return false;
    }

    CargaCarteraDAO.NormalizarTelefono2();

}

/*TIEM WAIT*/
var imgUrl = '../img/loading87.gif';

var $backdrop = null; // let it default
var subModal = false; // let it default

function addLoading(modalId) {
    $('.modal-backdrop').parent().remove();
    subModal = false;
    if (modalId === undefined || modalId == null) {
        subModal = false;

        $backdrop = $('<div onclick="clearLoading()"><div class="modal-backdrop"></div><div class="modal"><img src="'
                    + imgUrl + '" alt="" title="" /></div></div>').appendTo(document.body);
    }
    else {
        subModal = true;
        var p = $("#" + modalId);
        var cover = "style='width:" + p.outerWidth() + "px; height: " + p.outerHeight() + "px; left: " + p.offset().left + "px; top: " + p.offset().top + "px;'";
        var modal = "style='left: " + (p.outerWidth() / 2 * 1 + p.offset().left * 1) + "px; top: " + (p.outerHeight() / 2 * 1 + p.offset().top * 1) + "px;'";


        $backdrop = $('<div onclick="clearLoading()"><div class="modal-backdrop" ' + cover + '></div><div class="modal" '
                    + modal + '><img src="' + imgUrl + '" alt="" title="" /></div></div>').appendTo(document.body);

        $(window).scroll(function () {
            if (subModal) {
                $($backdrop).children().each(function (index) {
                    if (index == 0) {
                        $(this).css({
                            'top': p.offset().top - $(window).scrollTop(),
                            'left': p.offset().left - $(window).scrollLeft()
                        });
                    }
                    else {
                        $(this).css({
                            'top': p.outerHeight() / 2 + p.offset().top - $(window).scrollTop(),
                            'left': p.outerWidth() / 2 + p.offset().left - $(window).scrollLeft()
                        });
                    }
                });
            }
        });
    }
}

CargaCarteraDAO.Listar_Cartera();
