var EstadosPrioridadJQGRID = {
		estados : function ( ) {
				$('#table_estado').jqGrid({
										  url:'../controller/ControllerCobrast.php?command=estado_prioridad&action=jqgrid_estado&Servicio='+$('#hdCodServicio').val(),
										  datatype:'json',
										  height:100,
										  colNames:['Nombre','Peso','Tipo','Fecha Registro','Descripion'],
										  colModel:[
													{name:'nombre',index:'nombre',align:'center',width:200},
													{name:'peso',index:'peso',align:'center',width:80},
													{name:'tipo',index:'tipo',align:'center',width:80},
													{name:'fecha_creacion',index:'fecha_creacion',align:'center',width:120},
													{name:'descripcion',index:'descripcion',align:'center',width:300}
													],
										  rowList:[8,12,15],
										  rownumbers:true,
										  toolbar: [true,"top"],
										  pager:'#pager_table_estado',
										  onSelectRow : function ( id ) {
											  	reloadJQGRID_prioridades(id);
											  },
										  sortname:'nombre',
										  sortorder:'desc',
										  loadui:'block'
										  });
				
				$("#table_estado").jqGrid('navGrid','#pager_table_estado',{edit:false,add:false,del:false,view:true});
				$('#t_table_estado').addClass('ui-corner-top');
				$('#t_table_estado').attr('align','left');
				$('#t_table_estado').css('height','25px');
				var html="";
					html+="<table>";
						html+="<tr>";
							html+="<td><div id='toolbar_estado_icon_edit' class='ui-pg-button ui-corner-all' style='width:19px;margin:0 2px;' onclick='getParamEstadoEdit()' ><div class='ui-pg-div'><span class='ui-icon ui-icon-pencil'></span></div></div></td>";
						html+="</tr>";
					html+="</table>";
				$('#t_table_estado').append(html);
				$('#toolbar_estado_icon_edit').hover(function(){$('#toolbar_estado_icon_edit').addClass('ui-state-hover');},function(){$('#toolbar_estado_icon_edit').removeClass('ui-state-hover');});
			},
		prioridad : function ( ) {
				$('#table_prioridad').jqGrid({
										  url:'../controller/ControllerCobrast.php?command=estado_prioridad&action=jqgrid_prioridad',
										  datatype:'json',
										  height:100,
										  colNames:['Prioridad','Fecha Registro'],
										  colModel:[
													{name:'peso',index:'peso',align:'center',width:150},
													{name:'fecha_creacion',index:'fecha_creacion',align:'center',width:180}
													],
										  rowList:[8,12,15],
										  rownumbers:true,
										  toolbar: [true,"top"],
										  pager:'#pager_table_prioridad',
										  sortname:'peso',
										  caption:'Prioridades',
										  sortorder:'desc',
										  loadui:'block'
										  });
				
				$("#table_prioridad").jqGrid('navGrid','#pager_table_prioridad',{edit:false,add:false,del:false,view:true});
				//$('#t_table_prioridad').addClass('ui-corner-top');
				$('#t_table_prioridad').attr('align','left');
				$('#t_table_prioridad').css('height','25px');
				$('#t_table_prioridad').css('width','354px');
				var html="";
					html+="<table>";
						html+="<tr>";
							html+="<td><div id='toolbar_prioridad_icon_edit' class='ui-pg-button ui-corner-all' style='width:19px;margin:0 2px;' onclick='getParamEditPrioridad()' ><div class='ui-pg-div'><span class='ui-icon ui-icon-pencil'></span></div></div></td>";
							html+="<td><div id='toolbar_prioridad_icon_add' class='ui-pg-button ui-corner-all' style='width:19px;margin:0 2px;' onclick='display_form_prioridad()' ><div class='ui-pg-div'><span class='ui-icon ui-icon-plus'></span></div></div></td>";
						html+="</tr>";
					html+="</table>";
				$('#t_table_prioridad').append(html);
				$('#toolbar_prioridad_icon_edit').hover(function(){$('#toolbar_prioridad_icon_edit').addClass('ui-state-hover');},function(){$('#toolbar_prioridad_icon_edit').removeClass('ui-state-hover');});
				$('#toolbar_prioridad_icon_add').hover(function(){$('#toolbar_prioridad_icon_add').addClass('ui-state-hover');},function(){$('#toolbar_prioridad_icon_add').removeClass('ui-state-hover');});
			}
	}