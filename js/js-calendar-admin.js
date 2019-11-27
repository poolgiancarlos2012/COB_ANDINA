// JavaScript Document
$(document).ready(function ( ) {
	CalendarJQGRID.tareas();
	CalendarJQGRID.eventos();
	/*******/
	CalendarAdminDAO.Calendar.initCalendar(1);
	CalendarAdminDAO.Calendar.initWeek(1);
	/******/
	ListarUsuarioServicio();
	/******/
	$('#layerDatepicker').datepicker({inline:true,autoSize:true,dateFormat:'yy-mm-dd',dayNamesMin:['D','L','M','M','J','V','S'],monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']});
	$('#FormCalendar').find('#txtTiempoEvento,#txtTiempoTarea').timepicker({showSecond:true,dateFormat:'yy-mm-dd',timeFormat:'hh:mm:ss'});
	$('#FormCalendarWeek').find('#txtTiempoEvento_w,#txtTiempoTarea_w').timepicker({showSecond:true,dateFormat:'yy-mm-dd',timeFormat:'hh:mm:ss'});
	$('#FormCalendar2 #txtTiempoEvento').timepicker({showSecond:true,timeFormat:'hh:mm:ss'});

});
nextCalendar = function ( ) {
	CalendarAdminDAO.Calendar.initCalendar(2);
	
}
backCalendar = function ( ) {
	CalendarAdminDAO.Calendar.initCalendar(3);
	
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
				$('#'+CalendarAdminDAO.idLayerMessage).html(templates.MsgError('Ingrese hora','400px'));
				CalendarAdminDAO.setTimeOut_hide_message();
			}},
		{id:'SubFormCalendarEvent #txtEvento',required:true,errorRequiredFunction:function ( ) { 
				$('#'+CalendarAdminDAO.idLayerMessage).html(templates.MsgError('Ingrese evento','400px'));
				CalendarAdminDAO.setTimeOut_hide_message();
			}}
		]); 
	
	
	if( rs ){
		var rsC=confirm("Verifique los datos antes de grabar");
		if( rsC ) {
			CalendarAdminDAO.SaveRangeEvento();	
		}
	}
	
	//var ObjectDiv=$('#'+CalendarAdminDAO.Calendar.IdLayerCalendar).find('div[class="SelectedHeaderDayCalendar"]');
//	var LengthHeaders=$('#'+CalendarAdminDAO.Calendar.IdLayerCalendar).find('div[class="SelectedHeaderDayCalendar"]').length;
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
//			$('#'+CalendarAdminDAO.Calendar.IdLayerCalendar+' #RangEvent_'+id).html(html);
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
//			$('#'+CalendarAdminDAO.Calendar.IdLayerCalendar+' #RangEvent_'+id).html(html);
//		}
//	});
}
guardar_evento = function ( ) {
	var rs=validacion.check([
		{id:'SubFormCalendarEvent #txtTiempoEvento',required:true,errorRequiredFunction:function ( ) {
				$('#'+CalendarAdminDAO.idLayerMessage).html(templates.MsgError('Ingrese hora','400px'));
				CalendarAdminDAO.setTimeOut_hide_message();
			}},
		{id:'SubFormCalendarEvent #txtEvento',required:true,errorRequiredFunction:function ( ) { 
				$('#'+CalendarAdminDAO.idLayerMessage).html(templates.MsgError('Ingrese evento','400px'));
				CalendarAdminDAO.setTimeOut_hide_message();
			}}
		]);
		
	var xoperadores = '['+$('#table_operadores_evento').find(':checked').map(function(){
			return '{"operador":"'+$(this).val()+'"}';
		}).get().join(",")+']';
	if( xoperadores == '[]' ) {
		alert("Seleccione operadores");
		return false;
	}
	
	if( rs ){
		var rsC=confirm("Verifique los datos antes de grabar");
		if( rsC ) {
			CalendarAdminDAO.SaveEvento($('#FormCalendar #HdIdEvento').val(), xoperadores );	
		}
	}
}
guardar_tarea = function ( ) {
	var rs=validacion.check([
		{id:'SubFormCalendarWork #txtTarea',required:true,errorRequiredFunction:function ( ) {
				$('#'+CalendarAdminDAO.idLayerMessage).html(templates.MsgError('Ingrese tarea','400px'));
				CalendarAdminDAO.setTimeOut_hide_message();
			}},
		{id:'SubFormCalendarWork #txtTiempoTarea',required:true,errorRequiredFunction:function ( ) { 
				$('#'+CalendarAdminDAO.idLayerMessage).html(templates.MsgError('Ingrese hora','400px'));
				CalendarAdminDAO.setTimeOut_hide_message();
			}},
		{id:'SubFormCalendarWork #txtNota',required:true,errorRequiredFunction:function ( ) {
				$('#'+CalendarAdminDAO.idLayerMessage).html(templates.MsgError('Ingrese nota','400px'));
				CalendarAdminDAO.setTimeOut_hide_message();
			}}
		]); 
		
	var xoperadores = '['+$('#table_operadores_tarea').find(':checked').map(function( ){
			return '{"operador":"'+$(this).val()+'"}';
		}).get().join(",")+']';
	
	if( xoperadores == '[]' ) {
		alert("Seleccione operadores");
		return false;
	}
	
	if( rs ){
		var rsC=confirm("Verifique los datos antes de grabar");
		if( rsC ) {
			CalendarAdminDAO.SaveTarea($('#FormCalendar #HdIdTarea').val(), xoperadores );
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
	CalendarAdminDAO.Calendar.initWeek(2);
}
BackWeek = function ( ) {
	CalendarAdminDAO.Calendar.initWeek(3);
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
		CalendarAdminDAO.SaveEventWeek($('#FormCalendarWeek #HdIdPanel_w').val());
	}

}
guardar_tarea_w = function ( ) {

	var rsC=confirm("Verifique los datos antes de grabar");
	if( rsC ) {
		CalendarAdminDAO.SaveWorkWeek($('#FormCalendarWeek #HdIdPanel_w').val());
	}

}
ListarUsuarioServicio = function ( ) {
	CalendarAdminDAO.ListarUsuarioServicio( $('#hdCodServicio').val(), function ( obj ) {
			var html='';
			for( i=0;i<obj.length;i++ ) {
				html+='<tr>';
					html+='<td align="center" class="ui-widget-header" style="width:30px;">'+(i+1)+'</td>';
					html+='<td align="center" class="ui-widget-content" style="width:250px;">'+obj[i].nombre+'</td>';
					html+='<td align="center" class="ui-widget-content" style="width:30px;"><input type="checkbox" value="'+obj[i].idusuario_servicio+'" /></td>';
				html+='</tr>';
			}
			$('#table_operadores_evento,#table_operadores_tarea').html(html);
			$('#table_operadores_evento,#table_operadores_tarea').find('tr').hover(function(){ $(this).find('td:gt(0)').addClass('ui-state-hover'); },function(){ $(this).find('td:gt(0)').removeClass('ui-state-hover'); });
			$('#table_operadores_evento,#table_operadores_tarea').find('tr').click(function( ){
					$(this).find('td:gt(0)').addClass('ui-state-highlight').parent().siblings().find('td:gt(0)').removeClass('ui-state-highlight');
				});
		} );
}
check_all_table = function ( idtable, checked ) {
	if( checked ) {
		$('#'+idtable).find(':checkbox').attr('checked',true);
	}else{
		$('#'+idtable).find(':checkbox').attr('checked',false);
	}
}

