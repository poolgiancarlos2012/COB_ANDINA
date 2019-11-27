$(document).ready(function ( ) {
	CalendarJQGRID.tareas();
	CalendarJQGRID.eventos();
	/*******/
	CalendarDAO.Calendar.initCalendar(1);
	CalendarDAO.Calendar.initWeek(1);
	/******/
	$('#layerDatepicker').datepicker({inline:true,autoSize:true,dateFormat:'yy-mm-dd',dayNamesMin:['D','L','M','M','J','V','S'],monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']});
	$('#FormCalendar').find('#txtTiempoEvento,#txtTiempoTarea').timepicker({showSecond:true,dateFormat:'yy-mm-dd',timeFormat:'hh:mm:ss'});
	$('#FormCalendarWeek').find('#txtTiempoEvento_w,#txtTiempoTarea_w').timepicker({showSecond:true,dateFormat:'yy-mm-dd',timeFormat:'hh:mm:ss'});
	$('#FormCalendar2 #txtTiempoEvento').timepicker({showSecond:true,timeFormat:'hh:mm:ss'});

});
nextCalendar = function ( ) {
	CalendarDAO.Calendar.initCalendar(2);
	
}
backCalendar = function ( ) {
	CalendarDAO.Calendar.initCalendar(3);
	
}
DisplayFormCalendar = function ( element ) {
	$('#GestionPanelCalendar').find('div[id^="Calendar_subheader_"]').not(element).removeClass('SelectedHeaderDayCalendar').addClass('HeaderDayCalendar');
	$(element).removeClass('HeaderDayCalendar').addClass('SelectedHeaderDayCalendar');
	//Calendar_subheader_2010_8_9
	var id=$(element).attr('id').split('_');
	var meses=['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre'];
	var DiasSemana=['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'];
	
	var date=new Date();
	date.setFullYear(parseInt(id[2]));
	date.setMonth(parseInt(id[3])-1);
	date.setDate(parseInt(id[4]));
	
	var fecha=id[2]+'-'+id[3]+'-'+id[4];
	var title=DiasSemana[date.getDay()]+' '+id[4]+' de '+meses[parseInt(id[3])-1];
	var idEvento=id;
	var idTarea=id;
	idEvento[1]='subcontent_evento';
	$('#FormCalendar #HdIdEvento').val(idEvento.join("_"));
	idTarea[1]='subcontent_tarea';
	$('#FormCalendar #HdIdTarea').val(idTarea.join("_"));
	
	var p=$(element).position();
	
	$('#FormCalendar #HeaderFormCalendar #Title').html(title);
	$('#FormCalendar #HdFecha').val(fecha);
	
	//$('#FormCalendar').offset({top:(p.top+20),left:(p.left-200)});
	$('#FormCalendar').css('top',(p.top+20)+'px');
	$('#FormCalendar').css('left',(p.left-200)+'px');
	$('#FormCalendar').fadeIn();
	
}
CloseFormCalendar = function ( ) {
	$('#FormCalendar,#FormCalendar2').fadeOut();
	$('#GestionPanelCalendar').find('div[id^="Calendar_subheader_"]').removeClass('SelectedHeaderDayCalendar').addClass('HeaderDayCalendar');
}
CloseFormCalendarWeek = function ( ) {
	$('#FormCalendarWeek').fadeOut();
}
DisplaySubFormCalendar = function ( id ) {
	$('#FormCalendar').find('table[id^="SubFormCalendar"]').not('#'+id).hide();
	$('#FormCalendar #'+id).fadeIn();
}
DisplaySubFormCalendarWeek = function ( id ) {
	$('#FormCalendarWeek').find('table[id^="SubFormCalendar"]').not('#'+id).hide();
	$('#FormCalendarWeek #'+id).fadeIn();
}
guardar_evento_rango_fecha = function ( ) {
	
	var rs=validacion.check([
		{id:'SubFormCalendarEvent #txtTiempoEvento',required:true,errorRequiredFunction:function ( ) {
				$('#'+CalendarDAO.idLayerMessage).html(templates.MsgError('Ingrese hora','400px'));
				CalendarDAO.setTimeOut_hide_message();
			}},
		{id:'SubFormCalendarEvent #txtEvento',required:true,errorRequiredFunction:function ( ) { 
				$('#'+CalendarDAO.idLayerMessage).html(templates.MsgError('Ingrese evento','400px'));
				CalendarDAO.setTimeOut_hide_message();
			}}
		]); 
	
	
	if( rs ){
		var rsC=confirm("Verifique los datos antes de grabar");
		if( rsC ) {
			CalendarDAO.SaveRangeEvento();	
		}
	}
	
	//var ObjectDiv=$('#'+CalendarDAO.Calendar.IdLayerCalendar).find('div[class="SelectedHeaderDayCalendar"]');
//	var LengthHeaders=$('#'+CalendarDAO.Calendar.IdLayerCalendar).find('div[class="SelectedHeaderDayCalendar"]').length;
//	var prm=Math.ceil(Math.ceil(LengthHeaders)/2);
//	var rnd=Math.round(Math.random()*999+1);
//	var count=0;
//	$.each(ObjectDiv,function(key,data){
//		count++;
//		var rdn_id=$('#RangEvent_'+$(data).attr('id')).find('.SubLayerEvento').attr('id');
//		//alert($('#RangEvent_'+$(data).attr('id')).find('.SubLayerEvento').attr('id'));
//		if( rdn_id!='' && rdn_id!=undefined  ) {
//			$('td[id^="RangEvent_"]').find('div[id="'+rdn_id+'"]').parent().empty();
//		}
//		
//		if( count==prm ) {
//			var id=$(data).attr('id');
//			var html='';
//			if( count==1 ) {
//				html+='<div class="SubLayerEvento ui-corner-left" id="rdn_'+rnd+'" >dsfgsdfgdsfgsdfg</div>';
//			}else if( count==LengthHeaders ) {
//				html+='<div class="SubLayerEvento ui-corner-right" id="rdn_'+rnd+'">dsfgsdfgdsfgsdfg</div>';
//			}else{
//				html+='<div class="SubLayerEvento" id="rdn_'+rnd+'">dsfgsdfgdsfgsdfg</div>';
//			}
//			$('#'+CalendarDAO.Calendar.IdLayerCalendar+' #RangEvent_'+id).html(html);
//		}else{
//			var id=$(data).attr('id');
//			var html='';
//			if( count==1 ) {
//				html+='<div class="SubLayerEvento ui-corner-left" id="rdn_'+rnd+'"></div>';
//			}else if( count==LengthHeaders ){
//				html+='<div class="SubLayerEvento ui-corner-right" id="rdn_'+rnd+'"></div>';
//			}else{
//				html+='<div class="SubLayerEvento" id="rdn_'+rnd+'"></div>';
//			}
//			$('#'+CalendarDAO.Calendar.IdLayerCalendar+' #RangEvent_'+id).html(html);
//		}
//	});
}
guardar_evento = function ( ) {
	var rs=validacion.check([
		{id:'SubFormCalendarEvent #txtTiempoEvento',required:true,errorRequiredFunction:function ( ) {
				$('#'+CalendarDAO.idLayerMessage).html(templates.MsgError('Ingrese hora','400px'));
				CalendarDAO.setTimeOut_hide_message();
			}},
		{id:'SubFormCalendarEvent #txtEvento',required:true,errorRequiredFunction:function ( ) { 
				$('#'+CalendarDAO.idLayerMessage).html(templates.MsgError('Ingrese evento','400px'));
				CalendarDAO.setTimeOut_hide_message();
			}}
		]); 
	
	if( rs ){
		var rsC=confirm("Verifique los datos antes de grabar");
		if( rsC ) {
			CalendarDAO.SaveEvento($('#FormCalendar #HdIdEvento').val());	
		}
	}
}
guardar_tarea = function ( ) {
	var rs=validacion.check([
		{id:'SubFormCalendarWork #txtTarea',required:true,errorRequiredFunction:function ( ) {
				$('#'+CalendarDAO.idLayerMessage).html(templates.MsgError('Ingrese tarea','400px'));
				CalendarDAO.setTimeOut_hide_message();
			}},
		{id:'SubFormCalendarWork #txtTiempoTarea',required:true,errorRequiredFunction:function ( ) { 
				$('#'+CalendarDAO.idLayerMessage).html(templates.MsgError('Ingrese hora','400px'));
				CalendarDAO.setTimeOut_hide_message();
			}},
		{id:'SubFormCalendarWork #txtNota',required:true,errorRequiredFunction:function ( ) {
				$('#'+CalendarDAO.idLayerMessage).html(templates.MsgError('Ingrese nota','400px'));
				CalendarDAO.setTimeOut_hide_message();
			}}
		]); 
	
	if( rs ){
		var rsC=confirm("Verifique los datos antes de grabar");
		if( rsC ) {
			CalendarDAO.SaveTarea($('#FormCalendar #HdIdTarea').val());
		}
	}
}
listar_eventos_tareas = function ( xanio, xmes, xdia ) {
	var idUsuarioServicio=$('#hdCodUsuarioServicio').val();
	$("#table_eventos").jqGrid('setGridParam',{url:'../controller/ControllerCobrast.php?command=calendar&action=jqgrid_evento&UsuarioServicio='+idUsuarioServicio+'&Anio='+xanio+'&Mes='+xmes+'&Dia='+xdia}).trigger('reloadGrid');
	$("#table_tarea").jqGrid('setGridParam',{url:'../controller/ControllerCobrast.php?command=calendar&action=jqgrid_tarea&UsuarioServicio='+idUsuarioServicio+'&Anio='+xanio+'&Mes='+xmes+'&Dia='+xdia}).trigger('reloadGrid');
	$('#ApinPanelAtencionEventoTarea').trigger('click');
}
NextWeek = function ( ) {
	CalendarDAO.Calendar.initWeek(2);
}
BackWeek = function ( ) {
	CalendarDAO.Calendar.initWeek(3);
}
DisplayFormWeek = function ( e, xfecha, id_panel ) {
	var p=$(e.target).position();
	$('#FormCalendarWeek').fadeIn();
	$('#FormCalendarWeek').css('top',(p.top+20)+'px');
	$('#FormCalendarWeek').css('left',(p.left-200)+'px');
	$('#FormCalendarWeek #HdFecha_w').val(xfecha);
	$('#FormCalendarWeek #HdIdPanel_w').val(id_panel);
}
guardar_evento_w = function ( ) {

	var rsC=confirm("Verifique los datos antes de grabar");
	if( rsC ) {
		CalendarDAO.SaveEventWeek($('#FormCalendarWeek #HdIdPanel_w').val());
	}

}
guardar_tarea_w = function ( ) {

	var rsC=confirm("Verifique los datos antes de grabar");
	if( rsC ) {
		CalendarDAO.SaveWorkWeek($('#FormCalendarWeek #HdIdPanel_w').val());
	}

}


