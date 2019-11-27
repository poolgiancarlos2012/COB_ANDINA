var UsuarioAdminJQGRID = {
	usuarios : function ( ) {
			$("#table_user_admin").jqGrid({
                                        url:'../controller/ControllerCobrast.php?command=usuario_admin&action=jqgrid_usuarios',
                                        datatype:'json',
                                        gridview:true,
                                        height:150,
                                        colNames:['Codigo','Nombre','Dni','Email','Fecha Registro'],
                                        colModel:[
													{ name:'codigo',index:'codigo',align:'center',width:80 },
													{ name:'nombre',index:'nombre',align:'center',width:300 },
													{ name:'dni',index:'dni',align:'center',width:80 },
													{ name:'email',index:'email',align:'center',width:200 },
													{ name:'fecha_registro',index:'fecha_registro',align:'center',width:120 }
                                                    ],
                                        rowNum:10,
                                        rowList:[10,15,20],
                                        rownumbers:true,
                                        pager:'#pager_table_user_admin',
                                        sortname:'nombre',
										onSelectRow : function ( id ){
												load_JQGRID_servicios_usuarios(id);
											},
										toolbar: [true,"top"],
                                        sortorder:'desc',
                                        loadui: "block"
                                        });
										
			$("#table_user_admin").jqGrid('navGrid','#pager_table_user_admin',{edit:false,add:false,del:false,view:true});
			$("#table_user_admin").jqGrid('filterToolbar');
			$('#t_table_user_admin').addClass('ui-corner-top');
			$('#t_table_user_admin').attr('align','left');
			$('#t_table_user_admin').css('height','25px');
				var html="";
					html+="<table>";
						html+="<tr>";
							html+="<td><div id='toolbar_usuarios_admin_icon_edit' class='ui-pg-button ui-corner-all' style='width:19px;margin:0 2px;' onclick='load_data_usuario()' ><div class='ui-pg-div'><span class='ui-icon ui-icon-pencil'></span></div></div></td>";
						html+="</tr>";
					html+="</table>";
			$('#t_table_user_admin').append(html);
			$('#toolbar_usuarios_admin_icon_edit').hover(function(){$(this).addClass('ui-state-hover');},function(){$(this).removeClass('ui-state-hover');});
		},
	servicios_usuario : function ( ) {
			$("#table_servicios_usuario").jqGrid({
                                        url:'../controller/ControllerCobrast.php?command=usuario_admin&action=jqgrid_servicios_usuario',
                                        datatype:'json',
                                        gridview:true,
                                        height:150,
                                        colNames:['Servicio','Tipo Usuario','Privilegio','Fecha Inicio','Fecha Fin','Fecha Registro'],
                                        colModel:[
													{ name:'servicio',index:'servicio',align:'center',width:180 },
													{ name:'tipo_usuario',index:'tipo_usuario',align:'center',width:120 },
													{ name:'privilegio',index:'privilegio',align:'center',width:120 },
													{ name:'fecha_inicio',index:'fecha_inicio',align:'center',width:120 },
													{ name:'fecha_fin',index:'fecha_fin',align:'center',width:120 },
													{ name:'fecha_registro',index:'fecha_registro',align:'center',width:120 }
                                                    ],
                                        rowNum:10,
                                        rowList:[10,15,20],
                                        rownumbers:true,
                                        pager:'#pager_table_servicios_usuario',
                                        sortname:'servicio',
										toolbar: [true,"top"],
                                        sortorder:'desc',
                                        loadui: "block"
                                        });
										
			$("#table_servicios_usuario").jqGrid('navGrid','#pager_table_servicios_usuario',{edit:false,add:false,del:false,view:true});
			$('#t_table_servicios_usuario').addClass('ui-corner-top');
			$('#t_table_servicios_usuario').attr('align','left');
			$('#t_table_servicios_usuario').css('height','25px');
				var html="";
					html+="<table>";
						html+="<tr>";
							html+="<td><div id='toolbar_servicios_usuario_icon_edit' class='ui-pg-button ui-corner-all' style='width:19px;margin:0 2px;' onclick='load_data_servicio_usuario()' ><div class='ui-pg-div'><span class='ui-icon ui-icon-pencil'></span></div></div></td>";
							html+="<td><div id='toolbar_servicios_usuario_icon_add' class='ui-pg-button ui-corner-all' style='width:19px;margin:0 2px;' onclick='agregar_servicio_usuario()' ><div class='ui-pg-div'><span class='ui-icon ui-icon-plus'></span></div></div></td>";
						html+="</tr>";
					html+="</table>";
			$('#t_table_servicios_usuario').append(html);
			$('#toolbar_servicios_usuario_icon_edit').hover(function(){$(this).addClass('ui-state-hover');},function(){$(this).removeClass('ui-state-hover');});
			$('#toolbar_servicios_usuario_icon_add').hover(function(){$(this).addClass('ui-state-hover');},function(){$(this).removeClass('ui-state-hover');});
		},
		
}