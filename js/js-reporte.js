// JavaScript Document
$(document).ready(function(){
	$('#layerDatepicker').datepicker({inline:true,autoSize:true,dateFormat:'yy-mm-dd',dayNamesMin:['D','L','M','M','J','V','S'],monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']});
	/*******/
	//$('#txtFecha').datepicker({dateFormat:'yy-mm-dd'});
	$(':text[id^="txtFecha"]').datepicker({dateFormat:'yy-mm-dd',dayNamesMin:['D','L','M','M','J','V','S'],monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']});
	/*******/
	ReporteDAO.ListarCampania();
	//~ Vic I
	//ReporteDAO.ListarCarteraHistory();
	ReporteDAO.ListarTodasCartera( ReporteDAO.FillTCarteraTB, 'tbRKCartera_divReportes' );
	load_estado_llamada();
	ReporteDAO.load_gestor_campo();
	// ReporteDAO.Listar_Cartera_Opcion();




	/*******/
	generar_anio([
		{id:'cbAnioGestionDiaria'},
		{id:'cbAnioGestionDiariaFija'}
	]);
	generar_mes([
		{id:'cbMesGestionDiaria'},
		{id:'cbMesGestionDiariaFija'}
	]);
	generar_dia([
		{id:'cbDiaIGestionDiaria'},{id:'cbDiaFGestionDiaria'},
		{id:'cbDiaIGestionDiariaFija'},{id:'cbDiaFGestionDiariaFija'}
	]);
	/*******/
	
	$('#cboReporte').find('option').css('display','none');
	$('#cboReporte').find('option[name*="'+ $('#hdNomServicio').val() +'"]').css('display','block');

});
disabledCboCampania = function(idCombo,idCheckbox)
{
	var value = $('#'+idCheckbox).attr('checked');
	$('#'+idCombo).attr('disabled',value);
}
generar_link = function ( id ) {
	var servicio=$('#hdCodServicio').val();
	var cartera=$('#submenuCartera #cbCartera').val();
	//var fecha=$.trim( $('#submenuCartera #txtFecha').val() );
	/**********/
	var anio=$('#cbReporteAnio').val();
	var mes=$('#cbReporteMes').val();
	var diaI=$('#cbReporteDiaInicio').val();
	var diaF=$('#cbReporteDiaFin').val();
	var xdiaI,xmes;
	if( diaI.length==2 ){xdiaI=diaI;}else{xdiaI='0'+diaI;}
	if( mes.length==2 ){xmes=mes;}else{xmes='0'+mes;}
	var xfecha=anio+'-'+xmes+'-'+xdiaI;
	/**********/
	if( xfecha=='' ) {
		return false;
	}if( cartera==0 ) {
		return false;
	}
	
	var html='';
	
	var urlFotoCartera="../rpt/excel/cartera/FotoCartera.php?Cartera="+cartera+"&Fecha="+xfecha+'&Servicio='+servicio;
	var urlVisitas="../rpt/excel/direccion/Visitas.php?Cartera="+cartera+"&Fecha="+xfecha+'&Servicio='+servicio;
	var urlContacto="../rpt/excel/gestion/contacto.php?Cartera="+cartera+"&Servicio="+servicio+"&Fecha="+xfecha;
	var urlNoGestionados="../rpt/excel/gestion/no_gestionados.php?Cartera="+cartera+'&Fecha='+xfecha+'&Servicio='+servicio;
	var urlLlamadas="../rpt/excel/telefono/Llamadas.php?Cartera="+cartera+"&Servicio="+servicio+'&Fecha='+xfecha;
	var urlGestionLlamadas="../rpt/excel/telefono/GestionLlamadas.php?Cartera="+cartera+"&Servicio="+servicio+'&Fecha='+xfecha;
	var urlUltimasGestionLlamadas="../rpt/excel/telefono/UltimaGestionLlamadas.php?Cartera="+cartera+'&Fecha='+xfecha+'&Servicio='+servicio;
	var urlContactabilidad="../rpt/excel/gestion/contactabilidad.php?Cartera="+cartera+'&Fecha='+xfecha+'&Servicio='+servicio+'&Anio='+anio+'&Mes='+mes+'&DiaI='+diaI+'&DiaF='+diaF;
	
	html+="<li><a href='"+urlFotoCartera+"'>Exportar Foto Cartera</a></li>";
	html+="<li><a href='"+urlVisitas+"'>Exportar Visitas</a></li>";
	html+="<li><a href='"+urlContacto+"'>Exportar Contacto</a><li>";
	html+="<li><a href='"+urlNoGestionados+"'>Exportar No Gestionados</a></li>";
	html+="<li><a href='"+urlLlamadas+"'>Exportar Llamadas</a></li>";
	html+="<li><a href='"+urlGestionLlamadas+"'>Exportar Gestion de Llamadas</a></li>";
	html+="<li><a href='"+urlUltimasGestionLlamadas+"'>Exportar Ultimas Gestion de Llamadas</a></li>";
	html+="<li><a href='"+urlContactabilidad+"'>Contactabilidad</a></li>";
	
	$('#cobrastHOME #ulExportarExcel').html(html);
	
}
load_cartera = function ( idCampania ) {
	ReporteDAO.ListarCartera(idCampania,ReporteDAO.FillCartera);
}
load_cartera_by_id = function ( idCampania, cbCartera ) {
	ReporteDAO.ListarCartera(idCampania,function( obj ) {ReporteDAO.FillCarteraById(obj,cbCartera);});
}
load_cartera_by_id_rpte_rank = function ( idCampania, cbCartera ) {
	var filtroEstadoCart=$('#tbEstadoCarteraReporte').find(':checked').map(function(){return this.value;}).get().join(",");
	if(filtroEstadoCart==''){
		alert("Seleccione Estado de Cartera (No Vencido / Vencido)");	
		return false;
	}
	ReporteDAO.ListarCarteraRpteRank(idCampania,filtroEstadoCart,function( obj ) {ReporteDAO.FillCarteraById(obj,cbCartera);});
}
load_reporte_cartera_tb_rpte_rank = function ( idCampania, idTB ) {
	var filtroEstadoCart=$('#tbEstadoCarteraReporte').find(':checked').map(function(){return this.value;}).get().join(",");
	if(filtroEstadoCart==''){
		alert("Seleccione Estado de Cartera (No Vencido / Vencido)");	
		return false;
	}
	ReporteDAO.ListarCarteraTbRpteRank(idCampania,filtroEstadoCart,ReporteDAO.FillCarteraTB, idTB );
}
limpiaCamposReporte = function(){
	//$('#cbCampaniaLlaEst').val('0');
	$('#panelHomeReporte').find('select[id^="cbCampania"]').val('0');
	var html='<option value="0">--Seleccione--</option>';
	$('#panelHomeReporte').find('select[id^="cbCartera"]').html(html);
	html_tb='<tr><td><div style="height:0;"><table border="0" cellspacing="0" cellpadding="0">';
	$('#panelHomeReporte').find('[id^="tbRKCartera"]').html(html_tb);
	}
load_provincia_by_id = function ( idCampania, cbProvincia ) {
	ReporteDAO.ListarProvincia(idCampania,function( obj ) {ReporteDAO.FillProvinciaById(obj,cbProvincia);});
}
generar_anio = function ( obj ) {
	var date=new Date();
	var anioAct=date.getFullYear();
	var html='';
	for( i=(anioAct-2);i<(anioAct+2);i++ ){
		if( i==anioAct ){
			html+='<option value="'+i+'" selected="selected">'+i+'</option>';
		}else{
			html+='<option value="'+i+'">'+i+'</option>';
		}
	}
	$.each(obj,function( key,value ){
		$('#'+value.id).html(html);
	});
	//$('#cbReporteAnio').html(html);
}
generar_mes = function ( obj ) {
	var date=new Date();
	var html='';
	var mes=['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre'];	
	for( i=0;i<mes.length;i++ ) {
		if( i==(date.getMonth()) ) {
			html+='<option value="'+(i+1)+'" selected="selected" >'+mes[i]+'</option>';
		}else{
			html+='<option value="'+(i+1)+'">'+mes[i]+'</option>';
		}
	}
	$.each(obj,function( key,value ){
		$('#'+value.id).html(html);
	});
	//$('#cbReporteMes').html(html);
}
generar_dia = function ( obj ) {
	var date=new Date();
	var html='';
	for( i=1;i<=31;i++ ) {
		if( i==date.getDate()){
			html+='<option value="'+i+'" selected="selected">'+i+'</option>';
		}else{
			html+='<option value="'+i+'" >'+i+'</option>';
		}
	}
	$.each(obj,function( key,value ){
		$('#'+value.id).html(html);
	});
	//$('#cbReporteDiaInicio,#cbReporteDiaFin').html(html);
}
listar_cabeceras_cartera = function ( xcartera, idcombo ) {
	if( xcartera == 0 ) {
		return false;
	}
	ReporteDAO.ListarCabecerasCartera( xcartera, function ( obj ) {
			var html='';
			var field = eval( obj[0] );
			for( index in field ) {
				html+='<option value="'+index+'">'+index+'</option>';
			}
			$('#'+idcombo).html(html);
		} );
}
agregar_cabecera_reporte = function ( idcombocabeceras, idcombocebecerasreporte ) {
	
	var field = $('#'+idcombocabeceras+' option:selected').val();
	if( field == null || field == '' ) {
		return false;
	}
	var html = '';
	html+='<option value="'+field+'">'+field+'</option>';
	$('#'+idcombocebecerasreporte).append(html);
	$('#'+idcombocabeceras+' option:selected').remove();
	
}
link_gestion_llamadas = function ( ) {
	
	var servicio = $('#hdCodServicio').val();
	var cartera = $('#cbCarteraGestLlama').val();
	var fecha_inicio = $('#txtFechaInicioGestLlama').val();
	var fecha_fin = $('#txtFechaFinGestLlama').val();
	
	if( cartera == 0 ) {
		alert("Seleccione cartera");
		return false;
	}
	if( fecha_inicio == '' ) {
		alert("Ingrese Fecha Inicio");
		return false;
	}
	if( fecha_fin == '' ) {
		alert("Ingrese Fecha Fin");
		return false;
	}
	
	window.location.href="../rpt/excel/GestionLlamadas.php?Servicio="+servicio+"&Cartera="+cartera+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
	
}

link_cantidad_llamadas_por_estado = function ( ) {
	var servicio = $('#hdCodServicio').val();
	var cartera = $('#cbCarteraLlaEst').val();
	var fecha_inicio = $('#txtFechaLlaEstInicio').val();
	var fecha_fin = $('#txtFechaLlaEstFin').val();
	
	if( cartera == 0 ) {
		alert("Seleccione cartera");
		return false;
	}
	if( fecha_inicio == '' ) {
		alert("Ingrese Fecha Inicio");
		return false;
	}
	if( fecha_fin == '' ) {
		alert("Ingrese Fecha Fin");
		return false;
	}
	
	window.location.href="../rpt/excel/LlamadasPorEstado.php?Servicio="+servicio+"&Cartera="+cartera+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
}
//<jc>
link_rptgral_jc = function ( ) { 
	var servicio = $('#hdCodServicio').val();
	var cartera = $('#cbCartera_rptgral').val();
	var fecha_inicio = $('#txtFecha_inicio_rptgral').val();
	var fecha_fin = $('#txtFecha_fin_rptgral').val();
	
	if( cartera == 0 ) {
		alert("Seleccione cartera");
		return false;
	}
	if( fecha_inicio == '' ) {
		alert("Ingrese Fecha Inicio");
		return false;
	}
	if( fecha_fin == '' ) {
		alert("Ingrese Fecha Fin");
		return false;
	}
	
	window.location.href="../rpt/excel/procesa_rpt_prueba.php?Servicio="+servicio+"&Cartera="+cartera+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin; 
} //</jc>
//<jc>
link_valida_sig_jc = function () {
	var campania =$('#cbCampania_sig').val();
	var carteras = $('#tbRKCartera_fija_estado').find(':checked').map(function( ) {return this.value;}).get().join(",");
	var fecha_inicio=$('#txtFecha_inicio_sig').val();
	var fecha_fin=$('#txtFecha_fin_sig').val();
	if( carteras == '' ) {
		alert("Seleccione Cartera");
		return false;
	}
	if( fecha_inicio == '' ) {
		alert("Ingrese Fecha Inicio");
		return false;
	}
	if( fecha_fin == '' ) {
		alert("Ingrese Fecha Fin");
		return false;
	}
	window.location.href="../rpt/excel/procesa_rpt.php?campania="+campania+"&carteras="+carteras+"&fecha_inicio="+fecha_inicio+"&fecha_fin="+fecha_fin;
	
} //</jc>
link_valida_corte_focalizado = function()
{
	var todos = $('#chkAllServicio').attr('checked');
        var servicio = $('#hdCodServicio').val();
	if(!todos)
	{
		var campania =$('#cbCampania_corte_focalizado').val();	
		var idcartera = $('#tbRKCartera_corte_focalizado').find(':checked').map(function( ) {return this.value;}).get().join(",");
		if( campania == 0 ) {
			alert("Seleccione Campa\xf1a");
			return false;
		}
		if( idcartera == '' ) {
			alert("Seleccione Cartera");
			return false;
		}	
	}
	window.location.href="../rpt/excel/procesa_rpte_corte_focalizado.php?campania="+campania+"&cartera="+idcartera+'&todos='+todos+'&servicio='+servicio; //cartera=Gestion
}
link_valida_clientes_sin_recibos = function()
{
	var todos = false;//$('#chkAllServicioCliSinReci').attr('checked');
        var servicio = $('#hdCodServicio').val();
	/*if(!todos)
	{*/
		var campania =$('#cbCampania_clientes_sin_recibos').val();
		var idcartera = $('#tbRKCartera_clientes_sin_recibos').find(':checked').map(function( ) {return this.value;}).get().join(",");
		if( campania == 0 ) {
			alert("Seleccione Campa\xf1a");
			return false;
		}
		if( idcartera == '' ) {
			alert("Seleccione Cartera");
			return false;
		}	
	//}
	window.location.href="../rpt/excel/procesa_rpt_clientes_sin_recibos.php?campania="+campania+"&cartera="+idcartera+'&todos='+todos+'&servicio='+servicio;
}
link_valida_tmo = function()
{
	var todos = false;//$('#chkAllServicioTmo').attr('checked');
        var servicio = $('#hdCodServicio').val();
	/*if(!todos)
	{*/
		var campania =$('#cbCampania_tmo').val();	
		var idcartera = $('#tbRKCartera_tmo').find(':checked').map(function( ) {return this.value;}).get().join(",");
		if( campania == 0 ) {
			alert("Seleccione Campa\xf1a");
			return false;
		}
		if( idcartera == '' ) {
			alert("Seleccione Cartera");
			return false;
		}	
	//}
	window.location.href="../rpt/excel/procesa_rpt_tmo.php?campania="+campania+"&cartera="+idcartera+'&todos='+todos+'&servicio='+servicio; 
}
link_valida_facturacion = function()
{
    var todos = false;//$('#chkAllServicioTmo').attr('checked');
    var servicio = $('#hdCodServicio').val();
	/*if(!todos)
	{*/
            var campania =$('#cbCampania_facturacion').val();	
            var idcartera = $('#tbRKCartera_facturacion').find(':checked').map(function( ) {return this.value;}).get().join(",");
            if( campania == 0 ) {
                    alert("Seleccione Campa\xf1a");
                    return false;
            }
            if( idcartera == '' ) {
                    alert("Seleccione Cartera");
                    return false;
            }	
    //}
    window.location.href="../rpt/excel/procesa_rpt_facturacion.php?campania="+campania+"&cartera="+idcartera+'&todos='+todos+'&servicio='+servicio; 
}
link_valida_factura_digital = function()
{
	/*var todo = $('#chkAllServicios').attr('checked');
	if(!todo)
	{*/
		var campania =$('#cbCampania_factura_digital').val();	
		var idcartera = $('#tbRKCartera_factura_digital').find(':checked').map(function( ) {return this.value;}).get().join(",");
		if( campania == 0 ) {
			alert("Seleccione Campa\xf1a");
			return false;
		}
		if( idcartera == '' ) {
			alert("Seleccione Cartera");
			return false;
		}
	//}
	window.location.href="../rpt/excel/procesa_rpt_factura_digital.php?campania="+campania+"&cartera="+idcartera; //cartera=Gestion
}
link_valida_retiro = function()
{
	var campania =$('#cbCampania_retiro').val();	
	var idcartera = $('#tbRKCartera_fija_retiro').find(':checked').map(function( ) {return this.value;}).get().join(",");
	if( campania == 0 ) {
		alert("Seleccione Campa\xf1a");
		return false;
	}
	if( idcartera == '' ) {
		alert("Seleccione Cartera");
		return false;
	}
	window.location.href="../rpt/excel/procesa_rpt_retiro.php?campania="+campania+"&cartera="+idcartera; //cartera=Gestion
}
link_valida_retiro_sin_formato = function()
{
	var campania =$('#cbCampania_retiro').val();	
	var idcartera = $('#tbRKCartera_fija_retiro').find(':checked').map(function( ) {return this.value;}).get().join(",");
	if( campania == 0 ) {
		alert("Seleccione Campa\xf1a");
		return false;
	}
	if( idcartera == '' ) {
		alert("Seleccione Cartera");
		return false;
	}
	window.location.href="../rpt/excel/procesa_rpt_retiro_sinformato.php?campania="+campania+"&cartera="+idcartera; //cartera=Gestion
}
link_valida_empresa = function()
{
	var campania =$('#cbCampania_empresa').val();	
	var idcartera = $('#tbRKCartera_fija_empresa').find(':checked').map(function( ) {return this.value;}).get().join(",");
	if( campania == 0 ) {
		alert("Seleccione Campa\xf1a");
		return false;
	}
	if( idcartera == '' ) {
		alert("Seleccione Cartera");
		return false;
	}
	window.location.href="../rpt/excel/procesa_rpt_empresa.php?cartera="+idcartera; //cartera=Gestion
}
link_valida_DirCor = function()
{
	var campania =$('#cbCampania_DirCor').val();	
	var idcartera = $('#tbRKCartera_fija_DirCor').find(':checked').map(function( ) {return this.value;}).get().join(",");
	if( campania == 0 ) {
		alert("Seleccione Campa\xf1a");
		return false;
	}
	if( idcartera == '' ) {
		alert("Seleccione Cartera");
		return false;
	}
	window.location.href="../rpt/excel/procesa_rpt_DirCor.php?cartera="+idcartera; //cartera=Gestion
}
handlerChangeCboReporte = function()
{
    if($('#cboReporte').val()=='147' || $('#cboReporte').val()=='149' || $('#cboReporte').val()=='142' || $('#cboReporte').val()=='139' || $('#cboReporte').val()=='140' || $('#cboReporte').val()=='141' || $('#cboReporte').val()=='143' || $('#cboReporte').val()=='144' || $('#cboReporte').val() == '12' || $('#cboReporte').val() == '13' || $('#cboReporte').val() == '16' || $('#cboReporte').val() == '17' || $('#cboReporte').val() == '27' || $('#cboReporte').val() == '22' || $('#cboReporte').val() == '23' || $('#cboReporte').val() == '30' || $('#cboReporte').val() == '31' || $('#cboReporte').val() == '28' || $('#cboReporte').val() == '38' || $('#cboReporte').val() == '40' || $('#cboReporte').val() == '41' || $('#cboReporte').val() == '43' || $('#cboReporte').val() == '44' || $('#cboReporte').val() == '39' || $('#cboReporte').val() == '45' || $('#cboReporte').val() == '55' || $('#cboReporte').val() == '73' || $('#cboReporte').val()=='76' || $('#cboReporte').val()=='78' || $('#cboReporte').val() == '83' || $('#cboReporte').val() == '92' || $('#cboReporte').val()=='100' || $('#cboReporte').val()=='106' || $('#cboReporte').val()=='112' || $('#cboReporte').val()=='108' || $('#cboReporte').val()=='113' || $('#cboReporte').val()=='120' || $('#cboReporte').val()=='119' || $('#cboReporte').val()=='125' || $('#cboReporte').val()=='128' || $('#cboReporte').val()=='129' || $('#cboReporte').val()=='132' || $('#cboReporte').val()=='133' || $('#cboReporte').val()=='136' || $('#cboReporte').val()=='137' || $('#cboReporte').val()=='152'  || $('#cboReporte').val()=='153' || $('#cboReporte').val()=='154' || $('#cboReporte').val()=='157')
    {
        $('#trFecha').css('display','block');
    }else{
        $('#trFecha').css('display','none');
    }

//~ BBVA
	if( $('#cboReporte').val() == '94' ||  $('#cboReporte').val() == '116' ||  $('#cboReporte').val() == '131' ) {
		$('#trFechaBBVA').css('display','block');
	}else{
		$('#trFechaBBVA').css('display','none');
	}
	if( $('#cboReporte').val() == '101' ) {
		$('#trClienteNuevoRetirado').css('display','block');
	}else{
		$('#trClienteNuevoRetirado').css('display','none');
	}
	if( $('#cboReporte').val() == '102' ) {
		$('#trInformeCarterizacion').css('display','block');
	}else{
		$('#trInformeCarterizacion').css('display','none');
	}
	if( $('#cboReporte').val() == '111' ) {
		$('#trReporteCobertura').css('display','block');
	}else{
		$('#trReporteCobertura').css('display','none');
	}

	if( $('#cboReporte').val() == '34' ) {
		$('#trTipoTelefono').css('display','block');
	}else{
		$('#trTipoTelefono').css('display','none');
	}
	
	if( $('#cboReporte').val() == '38' || $('#cboReporte').val() == '43' ) {
		$('#trTipoLLamada').css('display','block');
	}else{
		$('#trTipoLLamada').css('display','none');
	}
	
    if($('#cboReporte').val() == '97' || $('#cboReporte').val() == '122' || $('#cboReporte').val() == '98' || $('#cboReporte').val() == '99' || $('#cboReporte').val() == '109' || $('#cboReporte').val() == '127'  || $('#cboReporte').val() == '155' || $('#cboReporte').val() == '158' )
    {
        $('#trFechaUnica').css('display','block');
    }else{
        $('#trFechaUnica').css('display','none');
    }
	
	if( $('#cboReporte').val() == '15' ) {
		$('#trImportPCampania').css('display','block');
	}else{
		$('#trImportPCampania').css('display','none');
	}
	
    if( $('#cboReporte').val() == '95' || $('#cboReporte').val() == '118' || $('#cboReporte').val() == '108' || $('#cboReporte').val() == '113' || $('#cboReporte').val() == '120' || $('#cboReporte').val() == '119' || $('#cboReporte').val() == '107' || $('#cboReporte').val() == '14' || $('#cboReporte').val() == '31' || $('#cboReporte').val() == '28' || $('#cboReporte').val() == '33' || $('#cboReporte').val() == '52' )
    {
        $('#divEstadoTransferenciaInsatisfaccion').css('display','block');
    }else{
        $('#divEstadoTransferenciaInsatisfaccion').css('display','none');
    }
    if($('#cboReporte').val() == '15')
    {
        $('#trMontoReporte').css('display','block');
        $('#sProvinciaReporte').css('visibility','visible');
    }else{
        $('#trMontoReporte').css('display','none');
        $('#sProvinciaReporte').css('visibility','hidden');
    }
    if( $('#cboReporte').val() == '19' || $('#cboReporte').val() == '48' || $('#cboReporte').val() == '49' )
    {
        $('#trFiltroFechaReporte').css('display','block');
    }else{
        $('#trFiltroFechaReporte').css('display','none');
    }
	
    //if($('#cboReporte').val() == '21')
	if($('#cboReporte').val() == '-1')
    {
        $('#trTablaCarteras').css('display','none');
    }else{
        $('#trTablaCarteras').css('display','block');
    }
	
	if( $('#cboReporte').val() == '48' || $('#cboReporte').val() == '49' ) {
		$('#trTipoTransaccionReporte').css('display','block');
	}else{
		$('#trTipoTransaccionReporte').css('display','none');
	}

	if( $('#cboReporte').val() == '95' || $('#cboReporte').val() == '107' || $('#cboReporte').val() == '118') {
		$('#trSinGestion').css('display','block');
	}else{
		$('#trSinGestion').css('display','none');
	}

	if($('#cboReporte').val()=='96'){
    	$('#divDistritosporCartera').css('display','block');	
    }else{
    	$('#divDistritosporCartera').css('display','none');	
    }

	if($('#cboReporte').val()=='96' || $('#cboReporte').val()=='97' || $('#cboReporte').val() == '98' || $('#cboReporte').val() == '104' || $('#cboReporte').val() == '109' || $('#cboReporte').val() == '128'){
    	$('#divFProceso').css('display','block');	
    }else{
    	$('#divFProceso').css('display','none');	
    }    
	
	/*if($('#cboReporte').val()=='99'){
    	$('#divFProcesoMultiple').css('display','block');	
    }else{
    	$('#divFProcesoMultiple').css('display','none');	
    } */   

	if($('#cboReporte').val()=='97' || $('#cboReporte').val() == '98' || $('#cboReporte').val() == '109'){
    	$('#divTerritorio').css('display','block');	
    }else{
    	$('#divTerritorio').css('display','none');	
    }        

	if($('#cboReporte').val()=='96' || $('#cboReporte').val()=='99' || $('#cboReporte').val()=='100' || $('#cboReporte').val()=='101' || $('#cboReporte').val()=='110' || $('#cboReporte').val()=='124' || $('#cboReporte').val()=='127' || $('#cboReporte').val()=='128' || $('#cboReporte').val()=='129'){
    	$('#divTipoCambio').css('display','block');	
    }else{
    	$('#divTipoCambio').css('display','none');	
    }    

	if($('#cboReporte').val()=='99'){
    	$('#divBotonDetalle').css('display','block');	
    }else{
    	$('#divBotonDetalle').css('display','none');	
    }    

    if($('#cboReporte').val()=='134'){
    	$('#trProvTotal').css({'display':'block'});
    }else{
    	$('#trProvTotal').css({'display':'none'});
    }

    // if($('#cboReporte').val()=='155'){
    // 	$("#trCobertura_Diaria_Opcion").css({'display':'block'});
    // }else{
    // 	$('#trCobertura_Diaria_Opcion').css({'display':'none'});
    // }

}
link_valida_reporte = function()
{
    var servicio = $('#hdCodServicio').val();
	var nombre_servicio = $('#hdNomServicio').val();
    var todos = '';
    var fecha_inicio = $('#txtFechaInicioReporte').val();
    var fecha_fin = $('#txtFechaFinReporte').val();
    var campania =$('#cbCampania_reporte').val();
    var idfinal = $('#layerContent_estado_transferencia_insatisfaccion :checked').map(function(){return $(this).val();}).get().join(",");
    var provincia =$('#cbProvincia_reporte').val();
    var monto_menor = $.trim( $('#txtMontoMenorReporte').val() );
    var monto_mayor = $.trim( $('#txtMontoMayorReporte').val() );
	var long_cartera = $('#tbRKCartera_divReportes').find(':checked').length;
	var long_fproceso=$('#listarFproceso').find(':checked').length;
	var long_fproceso_multiple=$('#listarFprocesoMultiple').find(':checked').length;	
	var long_territorio=$('#listarterritorio').find(':checked').length;	
	var long_nomdistritos=$('#listarDistrito').find(':checked').length;
    var idcartera = $('#tbRKCartera_divReportes').find(':checked').map(function( ) {return this.value;}).get().join(",");
    var anio = $('#cbAnioReporte').val();
    var mes = $('#cbMesReporte').val();
    var diai = $('#cbDiaIReporte').val();
    var diaf = $('#cbDiaFReporte').val();
    var fecha_unica=$('#txtFechaUnicaReporte').val();
	var tipo_llamada = $(':radio[name="rdTipoLlamada"]:checked').val();
	var tipo_reporte = $('#cboReporte').val();
	var tipo_transaccion = $('#cbTipoTransacccionReporte').val();
	var tipo_telefono = $(':radio[name="rdTipoTelefono"]:checked').val();
	var flgSinGestion=$('#flgSinGestion').attr('checked');
	var flgCDO=$('#flgCDO').attr('checked');
	var fproceso = $('#listarFproceso').find(':checked').map(function(){return "'"+this.value+"'";}).get().join(",");
	var fprocesomultiple = $('#listarFprocesoMultiple').find(':checked').map(function(){return "'"+this.value+"'";}).get().join(",");
	var territorios = $('#listarterritorio').find(':checked').map(function(){return "'"+this.value+"'";}).get().join(",");
	var nomdistritos = $('#listarDistrito').find(':checked').map(function(){return "'"+this.value+"'";}).get().join(",");
	var gestorcampo = $('#listGestorCampo').val();
	var tipocambio = $('#txttipocambio').val();
	var tipovac = $('#txttipovac').val();
	var tipoCambioProvTotl = $('#txtTipoCambioProvtot').val();
	var fechaProvTot= $('#txtFechaProvTot').val();
	var VACProvTot = $('#txtVacProvtot').val();
    /*if( campania == 0 ) {
            alert("Seleccione Campa\xf1a");
            return false;
    }*/
	

	if( tipo_reporte == '18' || tipo_reporte == '93' || tipo_reporte == '94' || tipo_reporte == '102' || tipo_reporte == '103' || tipo_reporte == '46' || tipo_reporte == '90' || tipo_reporte == '91' || tipo_reporte == '81' || tipo_reporte=='84' || tipo_reporte=='85' || tipo_reporte=='96' || tipo_reporte=='97' || tipo_reporte=='98' || tipo_reporte=='105' || tipo_reporte=='106' || tipo_reporte=='109' || tipo_reporte=='110' || tipo_reporte=='116' || tipo_reporte=='131' || tipo_reporte=='151' || tipo_reporte=='156' ) {
		
		if( long_cartera>1 ){
			
			alert("El reporte seleccionado solo permite seleccionar una cartera");
			return false;
		}
		
	}
	
    //if( idcartera == '' && $('#cboReporte').val() != '21') {
	if( idcartera == '' && $('#cboReporte').val() != '-1') {
            alert("Seleccione Cartera");
            return false;
    }
	
    if($('#cboReporte').val() == '12' || $('#cboReporte').val()=='149' || $('#cboReporte').val() == '13' || $('#cboReporte').val() == '16' || $('#cboReporte').val() == '17' || $('#cboReporte').val() == '27' || $('#cboReporte').val() == '22' || $('#cboReporte').val() == '23' || $('#cboReporte').val() == '30' || $('#cboReporte').val() == '31' || $('#cboReporte').val() == '28' || $('#cboReporte').val() == '38' || $('#cboReporte').val() == '40' || $('#cboReporte').val() == '41' || $('#cboReporte').val() == '43' || $('#cboReporte').val() == '44' || $('#cboReporte').val() == '39' || $('#cboReporte').val() == '45' || $('#cboReporte').val() == '55'|| $('#cboReporte').val() == '83' || $('#cboReporte').val() == '92' || $('#cboReporte').val() == '100' || $('#cboReporte').val() == '106' || $('#cboReporte').val() == '125' || $('#cboReporte').val() == '129' || $('#cboReporte').val() == '152' || $('#cboReporte').val()=='153' || $('#cboReporte').val()=='154' || $('#cboReporte').val()=='157')
    {
        if(fecha_fin == '' || fecha_inicio == ''){
            alert('ingrese un rango de fechas correctos');
            return false;
        }
    }
    if( $('#cboReporte').val() == '14' || $('#cboReporte').val() == '31' || $('#cboReporte').val() == '28' || $('#cboReporte').val() == '33' || $('#cboReporte').val() == '52' )
    {
        if( idfinal == '' ) {
            alert("Seleccione estados");
            return false;
        }
    }
    if($('#cboReporte').val() == '15')
    {
        if( monto_menor == '' ) {
		alert("Ingrese monto menor");
		return false;
	}
	if( monto_mayor == '' ) {
		alert("Ingrese monto mayor");
		return false;
	}
        if( provincia == 0 ) {
		alert("Seleccione Provincia");
		return false;
	}
    }
    if($('#cboReporte').val()=='96'){
    	if (idcartera==''){
    		alert('selecciona cartera');
    		return false;
    	}
    	if($('#listGestorCampo').val()=='0'){
    		alert('Seleccione Gestor');
    		return false;
    	}
    	if(long_nomdistritos<=0){
    		alert('Seleccione un distrito');
    		return false;
    	}
    }

    if($('#cboReporte').val()=='96' || $('#cboReporte').val()=='97' || $('#cboReporte').val()=='98' || $('#cboReporte').val()=='104' || $('#cboReporte').val()=='109' ){
    	if(long_fproceso<=0 ){
    		alert('Selecciones una fecha de proceso');
    		return false;
    	}
    }

    if($('#cboReporte').val()=='97' || $('#cboReporte').val()=='98' || $('#cboReporte').val()=='109'){
    	if(long_territorio<=0 ){
    		alert('Selecciones un Territorio');
    		return false;
    	}    	
    }


    if($('#cboReporte').val() == '97' || $('#cboReporte').val()=='122' || $('#cboReporte').val()=='98' || $('#cboReporte').val()=='99' || $('#cboReporte').val()=='109' || $('#cboReporte').val()=='127' || $('#cboReporte').val()=='155' || $('#cboReporte').val()=='158')
    {
        if( fecha_unica == '' ) {
            alert("Seleccione Fecha");
            return false;
        }
    }
    switch ($('#cboReporte').val()) {
        case '1':
            window.location.href="../rpt/excel/procesa_rpt_avance_gestion.php?carteras="+idcartera;
            break;
        case '2':
            window.location.href="../rpt/excel/procesa_rpt_gestion_diaria.php?campania="+campania+"&cartera="+idcartera+"&servicio="+servicio; //cartera=Gestion
            break;
        case '3':
            window.location.href="../rpt/excel/procesa_rpte_ivr.php?campania="+campania+"&cartera="+idcartera; //cartera=Gestion
            break;
        case '4':
            window.location.href="../rpt/excel/procesa_rpt_retiro.php?campania="+campania+"&cartera="+idcartera; //cartera=Gestion
            break;
        case '5':
            window.location.href="../rpt/excel/procesa_rpt_empresa.php?cartera="+idcartera; //cartera=Gestion
            break;
        case '6':
            window.location.href="../rpt/excel/procesa_rpt_DirCor.php?cartera="+idcartera; //cartera=Gestion
            break;
        case '7':
            window.location.href="../rpt/excel/procesa_rpte_corte_focalizado.php?cartera="+idcartera+'&todos='+todos+'&servicio='+servicio; //cartera=Gestion
            break;
        case '8':
            window.location.href="../rpt/excel/procesa_rpt_factura_digital.php?cartera="+idcartera; //cartera=Gestion
            break;
        case '9':
            window.location.href="../rpt/excel/procesa_rpt_clientes_sin_recibos.php?cartera="+idcartera+'&todos='+todos+'&servicio='+servicio;
            break;
        case '10':
            window.location.href="../rpt/excel/procesa_rpt_tmo.php?cartera="+idcartera+'&todos='+todos+'&servicio='+servicio; 
            break;
        case '11':
            window.location.href="../rpt/excel/procesa_rpt_facturacion.php?cartera="+idcartera+'&todos='+todos+'&servicio='+servicio; 
            break;
        case '12':
            window.location.href="../rpt/excel/GestionLlamadas2.php?Servicio="+servicio+"&Cartera="+idcartera+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin; 
            break;
        case '13':
            window.location.href="../rpt/excel/procesa_rpt.php?campania="+campania+"&carteras="+idcartera+"&fecha_inicio="+fecha_inicio+"&fecha_fin="+fecha_fin;
            break;
        case '14':
            window.location.href="../rpt/excel/transferencia_por_insatisfaccion.php?idcartera="+idcartera+"&idfinal="+idfinal;
            break;
        case '15':
            window.location.href="../rpt/excel/p_campania.php?campania="+campania+"&provincia="+provincia+"&cartera="+idcartera+"&monto_menor="+monto_menor+"&monto_mayor="+monto_mayor+"&servicio="+servicio; //cartera=Gestion
            break;
        case '16':
            window.location.href="../rpt/excel/LlamadasPorEstado.php?Servicio="+servicio+"&Cartera="+idcartera+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
            break;
        case '17':
            window.location.href="../rpt/excel/contactabilidad.php?Servicio="+servicio+"&Cartera="+idcartera+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
            break;
        case '18':
            window.location.href="../rpt/excel/fotocartera_bbva.php?Servicio="+servicio+"&Cartera="+idcartera+"&NombreServicio="+nombre_servicio;
            break;
        case '19':
            window.location.href="../rpt/excel/gestion_diaria_movil.php?servicio="+servicio+"&cartera="+idcartera+"&anio="+anio+"&mes="+mes+"&diai="+diai+"&diaf="+diaf;
            break;
        case '20':
            window.location.href="../rpt/excel/reporte_premiun.php?Servicio="+servicio+"&Cartera="+idcartera;
            break;
        case '21':
            window.location.href="../rpt/excel/ResumenCargas.php?Campania="+campania+"&Cartera="+idcartera;
            break;
        case '22':
            window.location.href="../rpt/excel/GestionLlamadas2.php?Servicio="+servicio+"&Cartera="+idcartera+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
            break;
        case '23':
            window.location.href="../rpt/excel/visitas.php?Servicio="+servicio+"&Cartera="+idcartera+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
            break;
        case '24':
            window.location.href="../rpt/excel/notificacion.php?servicio="+servicio+"&cartera="+idcartera;
            break;
        case '25':
            window.location.href="../rpt/excel/relacion_gestor.php?servicio="+servicio+"&cartera="+idcartera;
            break;
        case '26':
            window.location.href="../rpt/excel/clientes.php?Servicio="+servicio+"&Cartera="+idcartera;
            break;
        case '27':
            window.location.href="../rpt/excel/envio_call_campo.php?Servicio="+servicio+"&Cartera="+idcartera+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
            break;
        case '28':
            //window.location.href="../rpt/excel/procesa_rpt_marcaciones.php?Cartera="+idcartera+"&fecha_unica="+fecha_unica;
			window.location.href="../rpt/excel/Marcaciones.php?Cartera="+idcartera+"&Servicio="+servicio+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin+"&Estados="+idfinal;
            break;
		case '29':
			window.location.href="../rpt/excel/facturas.php?Cartera="+idcartera+"&Servicio="+servicio;
			break;
		case '30':
			window.location.href="../rpt/excel/pagos.php?Cartera="+idcartera+"&Servicio="+servicio+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
			break;
		case '31':
			window.location.href="../rpt/excel/Contact_horaria_operador.php?Cartera="+idcartera+"&Servicio="+servicio+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin+"&Estados="+idfinal;
			break;
		case '32':
			window.location.href="../rpt/excel/direccion_carteras.php?Cartera="+idcartera+"&Servicio="+servicio;
			break;
		case '33':
			window.location.href="../rpt/excel/compromiso_pago.php?Cartera="+idcartera+"&Servicio="+servicio+"&Estados="+idfinal;
			break;
		case '34':
			window.location.href="../rpt/excel/telefonos.php?Cartera="+idcartera+"&Servicio="+servicio+"&Tipo="+tipo_telefono;
			break;
		case '35':
			window.location.href="../rpt/excel/resumen_gestion.php?Cartera="+idcartera+"&Servicio="+servicio;
			break;
		case '36':
			window.location.href="../rpt/excel/ClientesMas2Cuentas.php?Cartera="+idcartera+"&Servicio="+servicio; 
			break;
		case '37':
			alert("Reporte en construccion");
		break;
		case '38':
			window.location.href="../rpt/excel/GestionLlamadasCencosud.php?Cartera="+idcartera+"&Servicio="+servicio+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin+"&TipoLlamada="+tipo_llamada; 
			break;
		case '39':
			window.location.href="../rpt/excel/refinanciamiento.php?Cartera="+idcartera+"&Servicio="+servicio+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin; 
		break;
		case '40':
			window.location.href="../rpt/excel/PrioridadCencosud.php?Cartera="+idcartera+"&Servicio="+servicio+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin; 
		break;
		case '41':
			window.location.href="../rpt/excel/DiarioCencosud.php?Cartera="+idcartera+"&Servicio="+servicio+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin; 
		break;
		case '42':
			window.location.href="../rpt/excel/DiarioResumenCencosud.php?Cartera="+idcartera+"&Servicio="+servicio; 
		break;
		case '43':
			window.location.href="../rpt/excel/LlamadasCencosudOp.php?Cartera="+idcartera+"&Servicio="+servicio+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin+"&TipoLlamada="+tipo_llamada; 
		break;
		case '44':
			window.location.href="../rpt/excel/ContactabilidadHoraCencosud.php?Cartera="+idcartera+"&Servicio="+servicio+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin; 
		break;
		case '45':
			window.location.href="../rpt/excel/ProductividadCencosud.php?Cartera="+idcartera+"&Servicio="+servicio+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin; 
		break;
		case '46':
			window.location.href="../rpt/excel/CampoCencosud.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio; 
		break;
		case '47':
			window.location.href="../rpt/excel/analisis_cartera_zonal_movil.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio; 
		break;
		case '48':
			window.location.href="../rpt/excel/ranking_carga_fecha_movil.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&Anio="+anio+"&Mes="+mes+"&DiaI="+diai+"&DiaF="+diaf+"&Tipo="+tipo_transaccion; 
		break;
		case '49':
			window.location.href="../rpt/excel/ranking_contacto_fecha_movil.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&Anio="+anio+"&Mes="+mes+"&DiaI="+diai+"&DiaF="+diaf+"&Tipo="+tipo_transaccion; 
		break;
		case '50':
			window.location.href="../rpt/excel/ranking_abonado_llamada.php?cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&anio="+anio+"&mes="+mes+"&diai="+diai+"&diaf="+diaf+"&Tipo="+tipo_transaccion; 
		break;
		case '51':
			window.location.href="../rpt/excel/EstadisticoAbonMontosPorGestYDistrMovil.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio; 
		break;
		case '52':
			window.location.href="../rpt/excel/Inubicados_negocios_movil.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&Estado="+idfinal; 
		break;
		case '53':
			window.location.href="../rpt/excel/modelo_devolucion_detalle_movil.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio; 
		break;
		case '54':
			window.location.href="../rpt/excel/contactabilidad_x_zonal_movil.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio; 
		break;
		case '55':
			window.location.href="../rpt/excel/NroVueltasMovil.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin; 
		break;
		case '56':
			window.location.href="../rpt/excel/tematica_movil.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio; 
		break;
		case '57':
			window.location.href="../rpt/excel/ranking_pagos.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio; 
		break;
		case '58':
			window.location.href="../rpt/excel/detalle_saldo_movil.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio; 
		break;
		case '60':
			window.location.href="../rpt/excel/modelo_devolucion_resumen_movil.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio; 
		break;
		case '62':
			window.location.href="../rpt/excel/recupero_detalle_movil.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio; 
		break;
		case '63':
			window.location.href="../rpt/excel/recupero_ranking_movil.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio; 
		break;
		case '64':
			window.location.href="../rpt/excel/plan_detalle_movil.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio; 
		break;
		case '65':
			window.location.href="../rpt/excel/ranking_planes_movil.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio; 
		break;
		case '66':
			window.location.href="../rpt/excel/Saldo_Cartera_Distrito_movil.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio; 
		break;
		case '67':
			window.location.href="../rpt/excel/ranking_teleoperador_movil.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio; 
		break;
		case '68':
			window.location.href="../rpt/excel/ranking_rando_deuda_status_movil.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio; 
		break;
		case '69':
			window.location.href="../rpt/excel/ranking_por_distrito_movil.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio; 
		break;
		case '70':
			window.location.href="../rpt/excel/ranking_por_status_movil.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio; 
		break;
		case '71':
			window.location.href="../rpt/excel/ranking_ciclo_fact_movil.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio; 
		break;
		case '72':
			window.location.href="../rpt/excel/ranking_planes_movil.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio; 
		break;
		case '73':
			window.location.href="../rpt/excel/ranking_gestores_telef_movil.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin; 
		break;
		case '74':
			window.location.href="../rpt/excel/campania_movil.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio; 
		break;
		case '90':
			window.location.href="../rpt/excel/incremental_provisiones_detalle.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio; 
		break;
		case '91':
			window.location.href="../rpt/excel/incremental_provisiones_resumen.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio; 
		break;
        case '75':
                window.location.href="../rpt/excel/reporte_telefono_direccion.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio;
        break;
        case '76':
                window.location.href="../rpt/excel/reporte_horizontal_llamada.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
        break;
        case '77':
                window.location.href="../rpt/excel/fotocartera_gestion.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
        break;                
        case '78':
                window.location.href="../rpt/excel/MejorLlamada.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
        break;
        case '79':
                window.location.href="../rpt/excel/telefonos_nuevos.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio;
        break;                
        case '80':
                window.location.href="../rpt/excel/telefono_horizontal.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio;
        break;                                
        case '81':
                window.location.href="../rpt/excel/visita_por_cliente.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio;
        break;                                                
        case '82':
                window.location.href="../rpt/excel/telefono_correcto_incorrecto.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio;
        break;                                                                
        case '83':
                window.location.href="../rpt/excel/llamada_por_cliente.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
        break;
        case '84':
                window.location.href="../rpt/excel/cuenta_por_cliente.php?Servicio="+servicio+"&Cartera="+idcartera+"&NombreServicio="+nombre_servicio;
        break;
        case '85':
                window.location.href="../rpt/excel/direccion_correcto_incorrecto.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio;
        break;
        case '86':
                window.location.href="../rpt/excel/direccion_vertical.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio;
        break;                
        case '87':
                window.location.href="../rpt/excel/estado_cliente.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio;
        break;
		case '92':
    			window.location.href="../rpt/excel/GestionLlamadasSGC.php?Servicio="+servicio+"&Cartera="+idcartera+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
    	break; 
    	case '93':
            window.location.href="../rpt/excel/fotocartera_saga.php?Servicio="+servicio+"&Cartera="+idcartera+"&NombreServicio="+nombre_servicio;
            break;              
		case '94':
			var fecha_inicio_bbva = $('#txtFechaInicioRptBBVA').val();
			var fecha_fin_bbva = $('#txtFechaFinRptBBVA').val();
			var fecha_proceso_bbva = $('#txtFechaProcesoRptBBVA').val();

			var fecha_inicio_vi_bbva = $('#txtFechaInicioVisitaRptBBVA').val();
			var fecha_fin_vi_bbva = $('#txtFechaFinVisitaRptBBVA').val();

			if(fecha_fin_bbva == '' || fecha_inicio_bbva == '') {
				alert('ingrese un rango de fechas correctos');
				return false;
			}
			if (fecha_proceso_bbva == '')
			{
				alert('seleccionar la fecha de proceso');
				return false;
			}
			if(fecha_inicio_vi_bbva == '' || fecha_fin_vi_bbva == '') {
				alert('Ingrese un rango de fechas de visitas');
				return false;
			}
			window.location.href="../rpt/excel/respuesta_rpt_bbva.php?carteras="+idcartera+"&fecha_inicio="+fecha_inicio_bbva+"&fecha_fin="+fecha_fin_bbva+"&fecha_proceso="+fecha_proceso_bbva+"&fecha_inicio_vis="+fecha_inicio_vi_bbva+"&fecha_fin_vis="+fecha_fin_vi_bbva+"&servicio="+servicio;
			break;
		case '95':
                window.location.href="../rpt/excel/telefono_horizontal_neotel.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&idfinal="+idfinal+"&singestion="+flgSinGestion+"&concdo="+flgCDO;
        break; 
        case '96':
                window.location.href="../rpt/excel/carta_campo.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&distritos="+nomdistritos+"&tipocambio="+tipocambio+"&gestorcampo="+gestorcampo+"&fproceso="+fproceso+"&tipovac="+tipovac;        
        break;
        case '97':
        		ReporteDAO.ListarLlamada(idcartera,'reporte_contactabilidad');
        //		window.location.href="../rpt/excel/reporte_contactabilidad.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&fproceso="+fproceso+"&fecha_unica="+fecha_unica+"&territorio="+territorios;
        break;
        case '98':
        		ReporteDAO.ListarLlamada(idcartera,'reporte_contactabilidad_corte');        
        		//window.location.href="../rpt/excel/reporte_contactabilidad_corte.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&fproceso="+fproceso+"&fecha_unica="+fecha_unica+"&territorio="+territorios;
        break;
        case '99':
        		window.location.href="../rpt/excel/reporte_efectividad_diaria.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&fecha_unica="+fecha_unica+"&tipocambio="+tipocambio+"&tipovac="+tipovac;
        break;        
        case '100':
        		window.location.href="../rpt/excel/gestion_llamadas_cdo.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&tipocambio="+tipocambio+"&tipovac="+tipovac+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
        break;                
		case '101':
			//~ Vic I
			var fecha_inicio_bbva = $('#sltCliNewRetIni').val();
			var fecha_fin_bbva = $('#sltCliNewRetFin').val();
			var agencia_bbva = $('#sltCliNewRetAgencia').val();
			var agencia_detalle_bbva = $('#sltCliNewRetDetalleAgencia').val();
			if(fecha_fin_bbva == 0 || fecha_inicio_bbva == 0 || agencia_bbva == 0) {
				alert('Seleccionar la Fecha Correcta');
				return false;
			}
			window.location.href="../rpt/excel/cliente_new_retiro_bbva.php?carteras="+idcartera+"&fecha_inicio="+fecha_inicio_bbva+"&fecha_fin="+fecha_fin_bbva+"&agencias="+agencia_bbva+"&agenciaDetalle="+agencia_detalle_bbva+"&tipocambio="+tipocambio+"&tipovac="+tipovac;
			break;
		case '102':
			var fecha_inicio_bbva = $('#txtFechaInfoCarteraIni').val();
			var fecha_fin_bbva = $('#txtFechaInfoCarteraFin').val();
			var fecha_proceso_bbva = $('#sltInfoCartera').val();
			var info_cartera_bbva = $('#sltInformeCarteraAgencia').val();
			if(fecha_fin_bbva == "" || fecha_inicio_bbva == "" || fecha_proceso_bbva==0 || info_cartera_bbva==0) {
				alert('Seleccinar un filtro correcto !');
				return false;
			}
			window.location.href="../rpt/excel/informe_carterizacion_bbva.php?carteras="+idcartera+"&fecha_inicio="+fecha_inicio_bbva+"&fecha_fin="+fecha_fin_bbva+"&fecha_proceso="+fecha_proceso_bbva+"&info_cartera="+info_cartera_bbva;
			break;
		case '103':
			window.location.href="../rpt/excel/visita_por_cliente_cdo.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio;
			break;
        case '104':
        		window.location.href="../rpt/excel/fotocartera_cdo.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&fproceso="+fproceso+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
        break;                			
        case '105':
        		window.location.href="../rpt/excel/sin_gestion_por_cartera.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio;
        break;     
        case '106':
        		window.location.href="../rpt/excel/sin_gestion_por_fecha.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
        break;
		case '107':
			window.location.href="../rpt/excel/telefono_vertical.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&idfinal="+idfinal+"&singestion="+flgSinGestion;
		break;
		case '108':
			window.location.href="../rpt/excel/telefono_estados_neotel.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&idfinal="+idfinal+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
		break;	                   			        
		case '109':
			window.location.href="../rpt/excel/reporte_agencia_entregable.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&fproceso="+fproceso+"&fecha_unica="+fecha_unica+"&territorio="+territorios;									
		break;	
		case '110':
            window.location.href="../rpt/excel/reporte_resumen_distribucion.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&tipocambio="+tipocambio+"&tipovac="+tipovac;        		
		break;                   			        		
		case '111':
			var tipocambioCober = $('#txtDolarCober').val();
			var tipovacCober = $('#txtVacCober').val();
			var fechaProcesoCober = $('#sltProcesoCober').val();
			if ($.trim(tipocambioCober)=='' || $.trim(tipovacCober)=='' || $.trim(fechaProcesoCober)==0)
			{
				alert("Ingresar Valores");
				return false;
			}
			window.location.href="../rpt/excel/cobertura_diaria_bbva.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&tipocambio="+tipocambioCober+"&tipovac="+tipovacCober+"&fechaProceso="+fechaProcesoCober;
		break;
		case '112':
    			window.location.href="../rpt/excel/por_gestionar_campo.php?Servicio="+servicio+"&Cartera="+idcartera+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
    	break; 		
		case '113':
			window.location.href="../rpt/excel/telefono_cliente_estados_neotel.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&idfinal="+idfinal+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
		break;	   
		case '114':
            window.location.href="../rpt/excel/fotocartera_credito.php?Servicio="+servicio+"&Cartera="+idcartera+"&NombreServicio="+nombre_servicio;
        break;		                			            	
		case '115':
            window.location.href="../rpt/excel/telefono_sin_gestion_neotel.php?Servicio="+servicio+"&Cartera="+idcartera+"&NombreServicio="+nombre_servicio;
        break;		                			            	        
		case '116':
			var fecha_inicio_bbva = $('#txtFechaInicioRptBBVA').val();
			var fecha_fin_bbva = $('#txtFechaFinRptBBVA').val();
			var fecha_proceso_bbva = $('#txtFechaProcesoRptBBVA').val();

			var fecha_inicio_vi_bbva = $('#txtFechaInicioVisitaRptBBVA').val();
			var fecha_fin_vi_bbva = $('#txtFechaFinVisitaRptBBVA').val();

			if(fecha_fin_bbva == '' || fecha_inicio_bbva == '') {
				alert('ingrese un rango de fechas correctos');
				return false;
			}
			if (fecha_proceso_bbva == '')
			{
				alert('seleccionar la fecha de proceso');
				return false;
			}
			if(fecha_inicio_vi_bbva == '' || fecha_fin_vi_bbva == '') {
				alert('Ingrese un rango de fechas de visitas');
				return false;
			}		
			window.location.href="../rpt/excel/respuesta_rpt_bbva2.php?carteras="+idcartera+"&fecha_inicio="+fecha_inicio_bbva+"&fecha_fin="+fecha_fin_bbva+"&fecha_proceso="+fecha_proceso_bbva+"&fecha_inicio_vis="+fecha_inicio_vi_bbva+"&fecha_fin_vis="+fecha_fin_vi_bbva+"&servicio="+servicio;
        break;		  
		case '117':
            window.location.href="../rpt/excel/reporte_estado_cuenta.php?Servicio="+servicio+"&Cartera="+idcartera+"&NombreServicio="+nombre_servicio;
        break;	
		case '118':
            window.location.href="../rpt/excel/telefono_horizontal_neotel_progresivo.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&idfinal="+idfinal+"&singestion="+flgSinGestion+"&concdo="+flgCDO;
        break;    
		case '119':
			window.location.href="../rpt/excel/telefono_estados_neotel_progresivo.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&idfinal="+idfinal+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
		break;	   
		case '120':
			window.location.href="../rpt/excel/telefono_cliente_estados_neotel_progresivo.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&idfinal="+idfinal+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
		break;	   		                			                     	                			            	                              			            	                
		case '121':
            window.location.href="../rpt/excel/telefono_sin_gestion_neotel_progresivo.php?Servicio="+servicio+"&Cartera="+idcartera+"&NombreServicio="+nombre_servicio;
        break;
    	case '122':
    		window.location.href="../rpt/excel/resumen_carga_bbva.php?Servicio="+servicio+"&Cartera="+idcartera+"&FechaUnica="+fecha_unica;    	
    	break;
		case '123':
            window.location.href="../rpt/excel/fotocartera_forum.php?Servicio="+servicio+"&Cartera="+idcartera+"&NombreServicio="+nombre_servicio;
        break;
		case '124':
            window.location.href="../rpt/excel/reporte_acumulado.php?Servicio="+servicio+"&Cartera="+idcartera+"&NombreServicio="+nombre_servicio+"&tipocambio="+tipocambio+"&tipovac="+tipovac;
        break;
        case '125':        		                			            	    			                			            	        		
            window.location.href="../rpt/excel/gestion_llamadas_forum.php?Servicio="+servicio+"&Cartera="+idcartera+"&NombreServicio="+nombre_servicio+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
        break;        
        case '126':        		                			            	    			                			            	        		
            window.location.href="../rpt/excel/carga_facturacion.php?Servicio="+servicio+"&Cartera="+idcartera+"&NombreServicio="+nombre_servicio;
        break;          
        case '127':        		                			            	    			                			            	        		
            window.location.href="../rpt/excel/resumen_provision.php?Servicio="+servicio+"&Cartera="+idcartera+"&NombreServicio="+nombre_servicio+"&FechaUnica="+fecha_unica+"&tipocambio="+tipocambio+"&tipovac="+tipovac;
        break;
        case '128':        		                			            	    			                			            	        		
            window.location.href="../rpt/excel/reporte_contactabilidad_gestion.php?Servicio="+servicio+"&Cartera="+idcartera+"&NombreServicio="+nombre_servicio+"&tipocambio="+tipocambio+"&tipovac="+tipovac+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin+"&fproceso="+fproceso;
        break;      
        case '129':
            window.location.href="../rpt/excel/modelo_gestion_call.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin+"&tipocambio="+tipocambio+"&tipovac="+tipovac;
        break;                                                        
        case '130':
            window.location.href="../rpt/excel/reporte_direcciones_hipotecario.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio;
        break;                                                                
		case '131':
			var fecha_inicio_bbva = $('#txtFechaInicioRptBBVA').val();
			var fecha_fin_bbva = $('#txtFechaFinRptBBVA').val();
			var fecha_proceso_bbva = $('#txtFechaProcesoRptBBVA').val();

			var fecha_inicio_vi_bbva = $('#txtFechaInicioVisitaRptBBVA').val();
			var fecha_fin_vi_bbva = $('#txtFechaFinVisitaRptBBVA').val();

			if(fecha_fin_bbva == '' || fecha_inicio_bbva == '') {
				alert('ingrese un rango de fechas correctos');
				return false;
			}
			if (fecha_proceso_bbva == '')
			{
				alert('seleccionar la fecha de proceso');
				return false;
			}
			if(fecha_inicio_vi_bbva == '' || fecha_fin_vi_bbva == '') {
				alert('Ingrese un rango de fechas de visitas');
				return false;
			}
			window.location.href="../rpt/excel/respuesta_rpt_bbva3.php?carteras="+idcartera+"&fecha_inicio="+fecha_inicio_bbva+"&fecha_fin="+fecha_fin_bbva+"&fecha_proceso="+fecha_proceso_bbva+"&fecha_inicio_vis="+fecha_inicio_vi_bbva+"&fecha_fin_vis="+fecha_fin_vi_bbva+"&servicio="+servicio;
		break;  
        case '132':
            window.location.href="../rpt/excel/reporte_enviar_cargo.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
        break;
        case '133':
            window.location.href="../rpt/excel/reporte_respuesta_gestion_covinoc.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
        break;
        case '134':
        	
        	if($('#txtTipoCambioProvtot').val() ==""){
        		alert('Rellenar Tipo Cambio');
        		return false;
        	}
        	if($('#txtVacProvtot').val()==""){
        		alert('Rellenar campo VAC');
        		return false;
        	}
            window.location.href="../rpt/excel/resumen_provision_total.php?Cartera="+idcartera+"&FechaUnica="+fechaProvTot+"&TipoCambio="+tipoCambioProvTotl+"&VAC="+VACProvTot;
		break;
		case '135':
			if( $('#tbRKCartera_divReportes').find(':checked').map(function( ) {return this.value;}).get().length > 2 ){
				alert('No disponible');
				return false;
			}
            window.location.href="../rpt/excel/rpt_indicadores_piro.php?Servicio="+servicio+"&Cartera="+idcartera+"&NombreServicio="+nombre_servicio;
        break;
        case '136':
			
			$.ajax({
				url:'../rpt/excel/gestion_diaria_covinoc_estudios_externos.php',
				type:'POST',
				dataType:'json',
				data : {
					Servicio : servicio,
					Cartera : idcartera,
					NombreServicio : nombre_servicio,
					fechaInicio : fecha_inicio,
					fechaFin : fecha_fin
				},
				beforeSend : function(){
					$('#msgProceso').html('Por favor espere..').css('opacity','1');
				},
				success: function(obj){
					$('#msgProceso').html('Descargando..').css('opacity','1');
					setTimeout(function(){
						$('#msgProceso').html('').css('opacity','0');

					},2000);
					if(obj.rst){
						window.location.href="../documents/reporte_txt/proceso/download_saga.php?rutafile="+obj.rutafile+"&namefile="+obj.namefile;
					}else{
						alert('archivo no encontrado');
					}
				}

			});
          
        break;
        case '137':
			
			window.location.href="../rpt/excel/gestion_diaria_covinoc_hdec.php?Servicio="+servicio+"&Cartera="+idcartera+"&NombreServicio="+nombre_servicio+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
        break;
        case '138':
			
			$.ajax({
				url:'../rpt/excel/archivo_plano_gestiones_covinoc_en_servicio_extrajudicial.php',
				type:'POST',
				dataType:'json',
				data : {
					Servicio : servicio,
					Cartera : idcartera,
					NombreServicio : nombre_servicio,
					fechaInicio : fecha_inicio,
					fechaFin : fecha_fin
				},
				beforeSend : function(){
					$('#msgProceso').html('Por favor espere..').css('opacity','1');
				},
				success: function(obj){
					$('#msgProceso').html('Descargando..').css('opacity','1');
					setTimeout(function(){
						$('#msgProceso').html('').css('opacity','0');

					},2000);
					if(obj.rst){
						window.location.href="../documents/reporte_txt/proceso/covinoc/download_archivo_plano.php?rutafile="+obj.rutafile+"&namefile="+obj.namefile;
					}else{
						alert('archivo no encontrado');
					}
				}

			});
        break;
        case '139':
			$.ajax({
				url:'../rpt/excel/archivo_plano_acuerdos_pago.php',
				type:'POST',
				dataType:'json',
				data : {
					Servicio : servicio,
					Cartera : idcartera,
					NombreServicio : nombre_servicio,
					fechaInicio : fecha_inicio,
					fechaFin : fecha_fin
				},
				beforeSend : function(){
					$('#msgProceso').html('Por favor espere..').css('opacity','1');
				},
				success: function(obj){
					$('#msgProceso').html('Descargando..').css('opacity','1');
					setTimeout(function(){
						$('#msgProceso').html('').css('opacity','0');

					},2000);
					if(obj.rst){
						window.location.href="../documents/reporte_txt/proceso/covinoc/download_archivo_plano.php?rutafile="+obj.rutafile+"&namefile="+obj.namefile;
					}else{
						alert('archivo no encontrado');
					}
				}

			});
        break;
        case '140':
			$.ajax({
				url:'../rpt/excel/archivo_plano_obligaciones_acuerdos_pago.php',
				type:'POST',
				dataType:'json',
				data : {
					Servicio : servicio,
					Cartera : idcartera,
					NombreServicio : nombre_servicio,
					fechaInicio : fecha_inicio,
					fechaFin : fecha_fin
				},
				beforeSend : function(){
					$('#msgProceso').html('Por favor espere..').css('opacity','1');
				},
				success: function(obj){
					$('#msgProceso').html('Descargando..').css('opacity','1');
					setTimeout(function(){
						$('#msgProceso').html('').css('opacity','0');

					},2000);
					if(obj.rst){
						window.location.href="../documents/reporte_txt/proceso/covinoc/download_archivo_plano.php?rutafile="+obj.rutafile+"&namefile="+obj.namefile;
					}else{
						alert('archivo no encontrado');
					}
				}

			});
        break;
        case '141':
			$.ajax({
				url:'../rpt/excel/archivo_plano_cuotas_acuerdos_pago.php',
				type:'POST',
				dataType:'json',
				data : {
					Servicio : servicio,
					Cartera : idcartera,
					NombreServicio : nombre_servicio,
					fechaInicio : fecha_inicio,
					fechaFin : fecha_fin
				},
				beforeSend : function(){
					$('#msgProceso').html('Por favor espere..').css('opacity','1');
				},
				success: function(obj){
					$('#msgProceso').html('Descargando..').css('opacity','1');
					setTimeout(function(){
						$('#msgProceso').html('').css('opacity','0');

					},2000);
					if(obj.rst){
						window.location.href="../documents/reporte_txt/proceso/covinoc/download_archivo_plano.php?rutafile="+obj.rutafile+"&namefile="+obj.namefile;
					}else{
						alert('archivo no encontrado');
					}
				}

			});
        break;
        case '142':
        
			$.ajax({
				url:'../rpt/excel/archivo_plano_telefonos_covinoc.php',
				type:'POST',
				dataType:'json',
				data : {
					Servicio : servicio,
					Cartera : idcartera,
					NombreServicio : nombre_servicio,
					fechaInicio : fecha_inicio,
					fechaFin : fecha_fin
				},
				beforeSend : function(){
					$('#msgProceso').html('Por favor espere..').css('opacity','1');
				},
				success: function(obj){
					$('#msgProceso').html('Descargando..').css('opacity','1');
					setTimeout(function(){
						$('#msgProceso').html('').css('opacity','0');

					},2000);
					if(obj.rst){
						window.location.href="../documents/reporte_txt/proceso/covinoc/download_archivo_plano.php?rutafile="+obj.rutafile+"&namefile="+obj.namefile;
					}else{
						alert('archivo no encontrado');
					}
				}

			});
        break;
        case '143':
			
			$.ajax({
				url:'../rpt/excel/archivo_plano_direcciones_covinoc.php',
				type:'POST',
				dataType:'json',
				data : {
					Servicio : servicio,
					Cartera : idcartera,
					NombreServicio : nombre_servicio,
					fechaInicio : fecha_inicio,
					fechaFin : fecha_fin
				},
				beforeSend : function(){
					$('#msgProceso').html('Por favor espere..').css('opacity','1');
				},
				success: function(obj){
					$('#msgProceso').html('Descargando..').css('opacity','1');
					setTimeout(function(){
						$('#msgProceso').html('').css('opacity','0');

					},2000);
					if(obj.rst){
						window.location.href="../documents/reporte_txt/proceso/covinoc/download_archivo_plano.php?rutafile="+obj.rutafile+"&namefile="+obj.namefile;
					}else{
						alert('archivo no encontrado');
					}
				}

			});
        break;
        case '144':
			
			$.ajax({
				url:'../rpt/excel/archivo_plano_emails_covinoc.php',
				type:'POST',
				dataType:'json',
				data : {
					Servicio : servicio,
					Cartera : idcartera,
					NombreServicio : nombre_servicio,
					fechaInicio : fecha_inicio,
					fechaFin : fecha_fin
				},
				beforeSend : function(){
					$('#msgProceso').html('Por favor espere..').css('opacity','1');
				},
				success: function(obj){
					$('#msgProceso').html('Descargando..').css('opacity','1');
					setTimeout(function(){
						$('#msgProceso').html('').css('opacity','0');

					},2000);
					if(obj.rst){
						window.location.href="../documents/reporte_txt/proceso/covinoc/download_archivo_plano.php?rutafile="+obj.rutafile+"&namefile="+obj.namefile;
					}else{
						alert('archivo no encontrado');
					}
				}

			});
        break;
        case '145':
			
			$.ajax({
				url:'../rpt/excel/archivo_plano_codigo_gestion_covinoc.php',
				type:'POST',
				dataType:'json',
				data : {
					Servicio : servicio,
					Cartera : idcartera,
					NombreServicio : nombre_servicio,
					fechaInicio : fecha_inicio,
					fechaFin : fecha_fin
				},
				beforeSend : function(){
					$('#msgProceso').html('Por favor espere..').css('opacity','1');
				},
				success: function(obj){
					$('#msgProceso').html('Descargando..').css('opacity','1');
					setTimeout(function(){
						$('#msgProceso').html('').css('opacity','0');

					},2000);
					if(obj.rst){
						window.location.href="../documents/reporte_txt/proceso/covinoc/download_archivo_plano.php?rutafile="+obj.rutafile+"&namefile="+obj.namefile;
					}else{
						alert('archivo no encontrado');
					}
				}

			});
        break;
        case '146':
			
			$.ajax({
				url:'../rpt/excel/archivo_plano_codigo_gestion_2014_covinoc.php',
				type:'POST',
				dataType:'json',
				data : {
					Servicio : servicio,
					Cartera : idcartera,
					NombreServicio : nombre_servicio,
					fechaInicio : fecha_inicio,
					fechaFin : fecha_fin
				},
				beforeSend : function(){
					$('#msgProceso').html('Por favor espere..').css('opacity','1');
				},
				success: function(obj){
					$('#msgProceso').html('Descargando..').css('opacity','1');
					setTimeout(function(){
						$('#msgProceso').html('').css('opacity','0');

					},2000);
					if(obj.rst){
						window.location.href="../documents/reporte_txt/proceso/covinoc/download_archivo_plano.php?rutafile="+obj.rutafile+"&namefile="+obj.namefile;
					}else{
						alert('archivo no encontrado');
					}
				}

			});
        break;
        case '147':
			
			$.ajax({
				url:'../rpt/excel/archivo_plano_gestiones_covinoc.php',
				type:'POST',
				dataType:'json',
				data : {
					Servicio : servicio,
					Cartera : idcartera,
					NombreServicio : nombre_servicio,
					fechaInicio : fecha_inicio,
					fechaFin : fecha_fin
				},
				beforeSend : function(){
					$('#msgProceso').html('Por favor espere..').css('opacity','1');
				},
				success: function(obj){
					$('#msgProceso').html('Descargando..').css('opacity','1');
					setTimeout(function(){
						$('#msgProceso').html('').css('opacity','0');

					},2000);
					if(obj.rst){
						window.location.href="../documents/reporte_txt/proceso/covinoc/download_archivo_plano.php?rutafile="+obj.rutafile+"&namefile="+obj.namefile;
					}else{
						alert('archivo no encontrado');
					}
				}

			});
        break;
        case  '148':
            window.location.href="../rpt/excel/rpt_telefonos_total.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
    	break;
    	case  '149':
            window.location.href="../rpt/excel/GestionLlamadasSoloCall.php?Servicio="+servicio+"&Cartera="+idcartera+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
    	break;
    	case  '150':
            window.location.href="../rpt/excel/fotocarteraConecta.php?Servicio="+servicio+"&Cartera="+idcartera;
    	break;

    	case  '151':
            window.location.href="../rpt/excel/fotocartera_opcion.php?Servicio="+servicio+"&Cartera="+idcartera;
    	break;

    	case  '152':            
            window.location.href="../rpt/excel/visita_opcion.php?Servicio="+servicio+"&Cartera="+idcartera+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;  
    	break;
    	case  '153':
    		window.location.href="../rpt/excel/reporte_diaro_llamadas.php?Cartera="+idcartera+"&Servicio="+servicio+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;            
    	break;
    	case  '154':
    		window.location.href="../rpt/excel/reporte_diaro_visitas.php?Cartera="+idcartera+"&Servicio="+servicio+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;            
    	break;
    	case  '155':
    		window.location.href="../rpt/excel/cobertura_diario_opcion.php?Cartera="+idcartera+"&Servicio="+servicio+"&fechaProceso="+$('#txtFechaUnicaReporte').val();            
    	break;
    	case  '156':
    		window.location.href="../rpt/excel/fotocartera_andina_cobranzas.php?Servicio="+servicio+"&Cartera="+idcartera;
    	break;
    	case  '157':
    		window.location.href="../rpt/excel/GestionLlamadas_Cobranzas_Andina.php?Servicio="+servicio+"&Cartera="+idcartera+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;	
    	break;
    	case  '158':
    		window.location.href="../rpt/excel/status_colocacion_cobranzas_andina.php?Servicio="+servicio+"&Cartera="+idcartera+"&FechaUnica="+fecha_unica;
    	break;

        
	}

}
link_valida_avance_jc = function ( idform ) {
	var campania =$('#cbCampania_av').val();
	var idcartera = $('#tbRKCartera_fija_estado_avance').find(':checked').map(function( ) {return this.value;}).get().join(",");
	if( campania == 0 ) {
		alert("Seleccione Campa\xf1a");
		return false;
	}
	if( idcartera == '' ) {
		alert("Seleccione Cartera");
		return false;
	}
	window.location.href="../rpt/excel/procesa_rpt_avance_gestion.php?carteras="+idcartera; //cartera=Gestion
} //</jc>
link_valida_gstdi_jc = function ( idform ) {
	var campania =$('#cbCampania_gstdi').val();
	
	var campania=$('#cbCampania_gstdi').val();
	var idcartera = $('#tbRKCartera_fija_estado_gstdi').find(':checked').map(function( ) {return this.value;}).get().join(",");
	
	if( campania == 0 ) {
		alert("Seleccione Campa\xf1a");
		return false;
	}
	if( idcartera == '' ) {
		alert("Seleccione Cartera");
		return false;
	}
	window.location.href="../rpt/excel/procesa_rpt_gestion_diaria.php?campania="+campania+"&cartera="+idcartera; //cartera=Gestion
	
} //</jc>
link_valida_gstdi_sin_formato_jc = function ( idform ) {
	var campania=$('#cbCampania_gstdi').val();
	var idcartera = $('#tbRKCartera_fija_estado_gstdi').find(':checked').map(function( ) {return this.value;}).get().join(",");
	
	if( campania == 0 ) {
		alert("Seleccione Campa\xf1a");
		return false;
	}
	if( idcartera == '' ) {
		alert("Seleccione Cartera");
		return false;
	}
	window.location.href="../rpt/excel/procesa_rpt_gestion_diaria_sin_formato.php?campania="+campania+"&cartera="+idcartera; //cartera=Gestion
	
} //</jc>
link_valida_pcampania_jc = function ( idform ) {

	var campania =$('#cbCampania_pcampania').val();
	var provincia =$('#cbProvincia_pcampania').val();
	var idcartera = $('#tbRKCartera_fija_estado_pcampania').find(':checked').map(function( ) {return this.value;}).get().join(",");
	var monto_menor = $.trim( $('#txtMontoMenorPCampania').val() );
	var monto_mayor = $.trim( $('#txtMontoMayorPCampania').val() );
	
	if( campania == 0 ) {
		alert("Seleccione Campa\xf1a");
		return false;
	}if( provincia == 0 ) {
		alert("Seleccione Provincia");
		return false;
	}
	if( idcartera == '' ) {
		alert("Seleccione Cartera");
		return false;
	}
	if( monto_menor == '' ) {
		alert("Ingrese monto menor");
		return false;
	}
	if( monto_mayor == '' ) {
		alert("Ingrese monto mayor");
		return false;
	}
	/*if(idcartera.length>3){
		alert("Elegir como maximo 2 Carteras");
		return false;
	}*/
	window.location.href="../rpt/excel/p_campania.php?campania="+campania+"&provincia="+provincia+"&cartera="+idcartera+"&monto_menor="+monto_menor+"&monto_mayor="+monto_mayor; //cartera=Gestion
} //</jc>
link_gestion_llamadas2 = function ( ) {
	var cartera = $('#tbRKCartera_Gestion_Llamadas2').find(':checked').map(function( ) {return this.value;}).get().join(",");
	var servicio = $('#hdCodServicio').val();
	var fecha_inicio = $('#txtFechaInicioGestion_Llamadas2').val();
	var fecha_fin = $('#txtFechaFinGestion_Llamadas2').val();
	
	if( cartera == 0 ) {
		alert("Seleccione cartera");
		return false;
	}
	if( fecha_inicio == '' ) {
		alert("Ingrese Fecha Inicio");
		return false;
	}
	if( fecha_fin == '' ) {
		alert("Ingrese Fecha Fin");
		return false;
	}
	
	window.location.href="../rpt/excel/GestionLlamadas2.php?Servicio="+servicio+"&Cartera="+cartera+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
	
}
link_valida_pcampania_sin_formato_jc = function ( idform ) {

	var campania =$('#cbCampania_pcampania').val();
	var provincia =$('#cbProvincia_pcampania').val();
	var idcartera = $('#tbRKCartera_fija_estado_pcampania').find(':checked').map(function( ) {return this.value;}).get().join(",");
	var monto_menor = $.trim( $('#txtMontoMenorPCampania').val() );
	var monto_mayor = $.trim( $('#txtMontoMayorPCampania').val() );
	
	if( campania == 0 ) {
		alert("Seleccione Campa\xf1a");
		return false;
	}
	if( provincia == 0 ) {
		alert("Seleccione Provincia");
		return false;
	}
	if( idcartera == '' ) {
		alert("Seleccione Cartera");
		return false;
	}
	if( monto_menor == '' ) {
		alert("Ingrese monto menor");
		return false;
	}
	if( monto_mayor == '' ) {
		alert("Ingrese monto mayor");
		return false;
	}
	
	window.location.href="../rpt/excel/p_campania_sin_formato.php?campania="+campania+"&provincia="+provincia+"&cartera="+idcartera+"&monto_menor="+monto_menor+"&monto_mayor="+monto_mayor; //cartera=Gestion
}

link_valida_ivr_jc = function ( idform ) {

	var campania =$('#cbCampania_ivr').val();
	
	var idcartera = $('#tbRKCartera_fija_estado_ivr').find(':checked').map(function( ) {return this.value;}).get().join(",");
	
	if( campania == 0 ) {
		alert("Seleccione Campa\xf1a");
		return false;
	}
	if( idcartera == '' ) {
		alert("Seleccione Cartera");
		return false;
	}
	
	window.location.href="../rpt/excel/procesa_rpte_ivr.php?campania="+campania+"&cartera="+idcartera; //cartera=Gestion
} //</jc>

link_visita = function ( ) {
	
	var servicio = $('#hdCodServicio').val();
	var cartera = $('#cbCarteraVisita').val();
	var fecha_inicio = $('#txtFechaInicioVisita').val();
	var fecha_fin = $('#txtFechaFinVisita').val();
	
	
	if( cartera == 0 ) {
		alert("Seleccione cartera");
		return false;
	}
	if( fecha_inicio == '' ) {
		alert("Ingrese Fecha Inicio");
		return false;
	}
	if( fecha_fin == '' ) {
		alert("Ingrese Fecha Fin");
		return false;
	}
	
	window.location.href="../rpt/excel/visitas.php?Servicio="+servicio+"&Cartera="+cartera+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
	
}
link_fotocartera = function ( ) {
	
	var servicio = $('#hdCodServicio').val();
	var cartera = $('#cbCarteraFoto').val();
	
	if( cartera == 0 ) {
		alert("Seleccione cartera");
		return false;
	}
	
	window.location.href="../rpt/excel/fotocartera.php?Servicio="+servicio+"&Cartera="+cartera;
	
}
link_contactabilidad = function ( ) {
	var servicio = $('#hdCodServicio').val();
	var cartera = $('#cbCarteraContac').val();
	var fecha_inicio = $.trim( $('#txtFechaInicioContac').val() );
	var fecha_fin = $.trim( $('#txtFechaFinContac').val() );
	
	if( cartera == 0 ) {
		alert("Seleccione cartera");
		return false;
	}
	if( fecha_inicio == '' ) {
		alert("Ingrese Fecha Inicio");
		return false;
	}
	if( fecha_fin == '' ) {
		alert("Ingrese Fecha Fin");
		return false;
	}
	
	window.location.href="../rpt/excel/contactabilidad.php?Servicio="+servicio+"&Cartera="+cartera+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
}
link_resumen_carga = function ( ) {
	var campania = $('#cbCampaniaResCar').val();
	
	if( campania == 0 ) {
		alert("Seleccione campaña");
		return false;
	}
	
	window.location.href="../rpt/excel/ResumenCargas.php?Campania="+campania;
}
link_gestion_diaria = function ( ) {
	
	var cartera = $('#cbCarteraGestionDiaria').val();
	var servicio = $('#hdCodServicio').val();
	var anio = $('#cbAnioGestionDiaria').val();
	var mes = $('#cbMesGestionDiaria').val();
	var diai = $('#cbDiaIGestionDiaria').val();
	var diaf = $('#cbDiaFGestionDiaria').val();
	//var field = '['+$('#cbCabecerasReporteGestionDiaria').find('option').map(function(){
//			return '{"campo":"'+$(this).text()+'"}';
//		}).get().join(",")+']';
	if( cartera == 0 ) {
		alert("Seleccione cartera");
		return false;
	}
	/*if( field == '[]' ) {
		alert("Agrege cabeceras");
		return false;
	}*/

	//window.location.href="../rpt/excel/gestion_diaria_movil.php?servicio="+servicio+"&cartera="+cartera+"&anio="+anio+"&mes="+mes+"&diai="+diai+"&diaf="+diaf+"&campos="+escape(field);
	window.location.href="../rpt/excel/gestion_diaria_movil.php?servicio="+servicio+"&cartera="+cartera+"&anio="+anio+"&mes="+mes+"&diai="+diai+"&diaf="+diaf;
	
}

link_notificacion = function ( ) {
	var cartera = $('#cbCarteraNotificacion').val();
	var servicio = $('#hdCodServicio').val();
	if( cartera == 0 ) {
		alert("Seleccione cartera");
		return false;
	}
	
	window.location.href="../rpt/excel/notificacion.php?servicio="+servicio+"&cartera="+cartera;
}
link_relacion_de_gestores = function ( ) {
	var cartera = $('#cbCarteraRelacionGestor').val();
	var servicio = $('#hdCodServicio').val();
	if( cartera == 0 ) {
		alert("Seleccione cartera");
		return false;
	}
	window.location.href="../rpt/excel/relacion_gestor.php?servicio="+servicio+"&cartera="+cartera;
}
link_clientes = function ( ) {
	var servicio = $('#hdCodServicio').val();
	var cartera = $('#cbCarteraClientes').val();
	
	if( cartera == 0 ) {
		alert("Seleccione cartera");
		return false;
	}
	
	window.location.href="../rpt/excel/clientes.php?Servicio="+servicio+"&Cartera="+cartera;
}
link_envio_call_campo = function ( ) {
	var servicio = $('#hdCodServicio').val();
	var cartera = $('#cbCarteraEnvioCallaCampo').val();
	var fecha_inicio = $.trim( $('#txtFechaInicioEnvioCallaCampo').val() );
	var fecha_fin = $.trim( $('#txtFechaFinEnvioCallaCampo').val() );
	
	if( cartera == 0 ) {
		alert("Seleccione cartera");
		return false;
	}
	if( fecha_inicio == '' ) {
		alert("Ingrese Fecha Inicio");
		return false;
	}
	if( fecha_fin == '' ) {
		alert("Ingrese Fecha Fin");
		return false;
	}
	
	window.location.href="../rpt/excel/envio_call_campo.php?Servicio="+servicio+"&Cartera="+cartera+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin;
}
link_premiun = function ( ) {
	var servicio = $('#hdCodServicio').val();
	var cartera = $('#cbCarteraPremiun').val();
	
	if( cartera == 0 ) {
		alert("Seleccione cartera");
		return false;
	}
	
	window.location.href="../rpt/excel/reporte_premiun.php?Servicio="+servicio+"&Cartera="+cartera;
}
link_transferencia_insatisfaccion = function ( ) {
	
	var idcartera = $('#tbRKCartera_transferencia_insatisfaccion :checked').map(function(){return $(this).val();}).get().join(",");
	var idfinal = $('#layerContent_estado_transferencia_insatisfaccion :checked').map(function(){return $(this).val();}).get().join(",");
	
	if( idcartera == '' ) {
		alert("Seleccione carteras");
		return false;
	}
	if( idfinal == '' ) {
		alert("Seleccione estados");
		return false;
	}
	
	window.location.href="../rpt/excel/transferencia_por_insatisfaccion.php?idcartera="+idcartera+"&idfinal="+idfinal;
	
}
checked_all = function ( element,idtb ) {
	if( element ) {
		$('#'+idtb).find(':checkbox').attr('checked',true);
	}else{
		$('#'+idtb).find(':checkbox').attr('checked',false);
	}
}
des_checked = function ( id ) {
	$('#'+id).attr('checked',false);
}
load_estado_llamada = function ( ) {
	var idservicio = $('#hdCodServicio').val();
	
	ReporteDAO.ListarEstado( idservicio, function ( obj_json ) {
			var obj = obj_json.llamada;
			var html='';
			for( i=0;i<obj.length;i++ ) {
				var data = (obj[i].data).split('|');
				html+='<label class="ui-helper-reset ui-state-highlight" style="font-size:14px;font-weight:bold;" >'+obj[i].CARGA+'</label>';
				html+='<div>';
					html+='<table cellpadding="0" cellspacing="0">';
					for( j=0;j<data.length;j++ ) {
						var final = data[j].split('@#');
						html+='<tr>';
							html+='<td align="center" style="width:20px;"><input type="checkbox" value="'+final[0]+'" /><td>';
							html+='<td>'+final[1]+'<td>';
						html+='<tr>';
					}
					html+='</table>';
				html+='</div>';
			}
			$('#layerContent_estado_transferencia_insatisfaccion').html(html);			
		} );
}

import_pcampania = function ( ) {

	var servicio = $('#hdCodServicio').val();
	var nombre_servicio = $('#hdNomServicio').val();
	var campania = $('#cbCampania_reporte').val();
	var idcartera = $('#tbRKCartera_divReportes').find(':checked').map(function( ) {return this.value;}).get().join(",");
	
	if( campania == 0 ) {
			alert("Seleccione Campa\xf1a");
			return false;
	}
	if( idcartera == '' ) {
			alert("Seleccione Cartera");
			return false;
	}
	
	$('#fileImportPCampania').upload(
									'../controller/ControllerCobrast.php',
									{
									command:'carga-cartera',
									action:'upload_file_import_pcampania',
									Servicio:$('#hdCodServicio').val(),
									CaracterSeparador : 'tab',
									UsuarioCreacion:$('#hdCodUsuario').val(),
									NombreServicio:$('#hdNomServicio').val()
									},
									function(obj){
										if( obj.rst ){
											window.location.href="../rpt/excel/facturas_pcampania.php?servicio="+servicio+"&cartera="+idcartera+"&campania="+campania+"&file="+obj.file+'&nombre_servicio='+nombre_servicio;
										}else{
											DistribucionDAO.error_ajax();
										}
									},
									'json'
									);
	
}
search_text_table = function ( xtext, idtable ) {
	var text = $.trim( xtext );
	text = text.toUpperCase();
	$('#'+idtable+' tr').css('display','none');
	$('#'+idtable+' tr').find('td:contains("'+text+'")').parent().css('display','block');
	
}
searchLugar=function(valor){
	var flag_provincia;
	if(valor==''){
		$('#tbRKCartera_divReportes tr').css('display','block');            
	}else{
		$('#tbRKCartera_divReportes tr').map(function(){
		    flag_provincia=$.trim($(this).find('td:eq(5)').text());
		    if(flag_provincia == valor ){
		        $(this).css('display','block');            
		    }else if(flag_provincia!=''){
		        $(this).css('display','none');              
		    }   
		})
	}
}
listarDistritoCartera = function (){
	var numcartera = $('#tbRKCartera_divReportes').find(':checked').length;

	if (numcartera==0){
		alert('Seleccione una cartera');
		return false;
	}
	if (numcartera>1) {
		alert('Seleccione solo una cartera');
		return false;
	}else{
		ReporteDAO.ListarDistritoCartera();
	}

}
listarfproceso = function (){
	var numcartera = $('#tbRKCartera_divReportes').find(':checked').length;

	if (numcartera==0){
		alert('Seleccione una cartera');
		return false;
	}

	if (numcartera>1) {
		alert('Seleccione solo una cartera');
		return false;
	}else{
		ReporteDAO.listarfproceso();
	}

}
listarfproceso2 = function (){
	var numcartera = $('#tbRKCartera_divReportes').find(':checked').length;

	if (numcartera==0){
		alert('Seleccione una cartera');
		return false;
	}

		ReporteDAO.ListarCarteraHistory();

}
listarfprocesomultiple = function (){
	var numcartera = $('#tbRKCartera_divReportes').find(':checked').length;

	if (numcartera==0){
		alert('Seleccione cartera');
		return false;
	}

		ReporteDAO.listarfprocesomultiple();

}
listarterritorio = function (){
	var numcartera = $('#tbRKCartera_divReportes').find(':checked').length;
	var fproceso = $('#listarFproceso').find(':checked').length;

	if (numcartera==0){
		alert('Seleccione una cartera');
		return false;
	}

	if(fproceso==0){
		alert('Seleccione una Fecha de Proceso');
		return false;
	}

	if (numcartera>1) {
		alert('Seleccione solo una cartera');
		return false;
	}else{
		ReporteDAO.listarterritorio();
	}	
}
reportedetalle = function(){
   	var servicio = $('#hdCodServicio').val();
	var nombre_servicio = $('#hdNomServicio').val();
    var idcartera = $('#tbRKCartera_divReportes').find(':checked').map(function( ) {return this.value;}).get().join(",");
    var fecha_unica=$('#txtFechaUnicaReporte').val();
	var fproceso = $('#listarFproceso').find(':checked').map(function(){return "'"+this.value+"'";}).get().join(",");
	var fprocesomultiple = $('#listarFprocesoMultiple').find(':checked').map(function(){return "'"+this.value+"'";}).get().join(",");
	var tipocambio = $('#txttipocambio').val();
	var tipovac = $('#txttipovac').val();

    window.location.href="../rpt/excel/reporte_efectividad_diaria_detalle.php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&fecha_unica="+fecha_unica+"&tipocambio="+tipocambio+"&tipovac="+tipovac;	
}
