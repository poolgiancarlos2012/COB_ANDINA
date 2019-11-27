var CampaniaJQGRID = {
		Campania : function ( ) {
				$('#table_campanias').jqGrid({
										  url:'../controller/ControllerCobrast.php?command=campania&action=jqgrid_campania&Servicio='+$('#hdCodServicio').val(),
										  datatype:'json',
										  height:250,
										  colNames:['Nombre','Estado','Fecha Inicio','Fecha Fin','Descripion'],
										  colModel:[
													{name:'nombre',index:'nombre',align:'center',width:160},
													{name:'status',index:'status',align:'center',width:120,editable:true,edittype:'select',editoptions:{ value : { 'ACTIVO':'ACTIVO','INACTIVO':'INACTIVO' } } },
													{name:'fecha_inicio',index:'fecha_inicio',align:'center',width:100},
													{name:'fecha_fin',index:'fecha_fin',align:'center',width:100},
													{name:'descripcion',index:'descripcion',align:'center',width:360}
													],
										  rowList:[15,25],
										  rownumbers:true,
										  pager:'#pager_table_campanias',
										  sortname:'fecha_inicio',
										  sortorder:'desc',
										  ondblClickRow : function ( rowid, irow, icol,e ) {
										  	$('#table_campanias').jqGrid('editRow',rowid,true,
												function ( ) {},
												function ( response ) {
													
													var json_d = $.parseJSON(response.responseText);
													if( json_d.rst ){
														$('#table_campanias').jqGrid().trigger('reloadGrid');
													}else{
	
													}
													_displayBeforeSendDl(json_d.msg,450);
													
												},
												'../controller/ControllerCobrast.php',
												{ command : 'campania', action : 'ActEstadoCampania', usuario_modificacion : $('#hdCodUsuario').val() });
												
										  },
										  toolbar: [true,"top"],
										  loadui:'block'
										  });
				
				$("#table_campanias").jqGrid('navGrid','#pager_table_campanias',{edit:false,add:false,del:false,view:true});
				$("#t_table_campanias").addClass('ui-corner-top');
				$('#t_table_campanias').attr('align','left');
				$("#t_table_campanias").append("<div id='toolbar_campania_icon_edit' class='ui-pg-button ui-corner-all' style='width:19px;margin:0 2px;float:left;' onclick='edit_campania()' ><div class='ui-pg-div'><span class='ui-icon ui-icon-pencil'></span></div></div>");
				$('#toolbar_campania_icon_edit').hover(function(){$('#toolbar_campania_icon_edit').addClass('ui-state-hover');},function(){$('#toolbar_campania_icon_edit').removeClass('ui-state-hover');});
				
			}
	}