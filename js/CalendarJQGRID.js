var CalendarJQGRID = {
		
		eventos : function ( ) {
				$("#table_eventos").jqGrid({
											url:'../controller/ControllerCobrast.php?command=calendar&action=jqgrid_evento',
											datatype:'json',
											gridview:true,
											height:100,
											colNames:['Evento','Hora'],
											colModel:[
													{ name:'evento',index:'evento',align:'center',width:400 },
													{ name:'hora',index:'hora',align:'center',width:100 }
													],
											rowNum:10,
											rowList:[10,15,20],
											rownumbers:true,
											pager:'#pager_table_eventos',
											sortname:'hora',
											sortorder:'desc',
											loadui: "block"
											});
				$("#table_eventos").jqGrid('navGrid','#pager_table_eventos',{edit:false,add:false,del:false,view:true});
			},
		tareas : function ( ) {
				$("#table_tarea").jqGrid({
											url:'../controller/ControllerCobrast.php?command=calendar&action=jqgrid_tarea',
											datatype:'json',
											gridview:true,
											height:100,
											colNames:['Titulo','Hora','Nota'],
											colModel:[
													{ name:'titulo',index:'titulo',align:'center',width:220 },
													{ name:'hora',index:'hora',align:'center',width:60 },
													{ name:'nota',index:'nota',align:'center',width:400 }
													],
											rowNum:10,
											rowList:[10,15,20],
											rownumbers:true,
											pager:'#pager_table_tarea',
											sortname:'hora',
											sortorder:'desc',
											loadui: "block"
											});
				$("#table_tarea").jqGrid('navGrid','#pager_table_tarea',{edit:false,add:false,del:false,view:true});
			}
		
	}