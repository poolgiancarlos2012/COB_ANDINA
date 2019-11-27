var DistribucionJQGRID = {
		clientes_gestionadosCOBRAST : function ( ) {
				$("#table_clientes_gestionados").jqGrid({
                                        url:'../controller/ControllerCobrast.php?command=distribucion&action=jqgrid_clientes_gestionados',
                                        datatype:'json',
                                        height:150,
                                        colNames:['Codigo','Cliente','Numero Doc','Usuario Gestion'],
                                        colModel:[
												{ name:'cli.codigo',index:'cli.codigo',align:'center',width:80 },
												{ name:'cli.nombre',index:'cli.nombre',align:'center',width:250 },
												{ name:'cli.numero_documento',index:'cli.numero_documento',align:'center',width:80 },
												{ name:'usuario_gestion',index:'usuario_gestion',align:'center',width:250 }
												],
                                        rowNum:10,
                                        rowList:[10,15,20],
                                        rownumbers:true,
                                        pager:'#pager_table_clientes_gestionados',
                                        sortname:'cliente',
                                        sortorder:'desc',
                                        loadui: "block"
                                        });
				$("#table_clientes_gestionados").jqGrid('navGrid','#pager_table_clientes_gestionados',{edit:false,add:false,del:false,view:true});
				$("#table_clientes_gestionados").jqGrid('filterToolbar');
			},
		clientes_sin_gestionarCOBRAST : function ( ) {
				$("#table_clientes_sin_gestionar").jqGrid({
                                        url:'../controller/ControllerCobrast.php?command=distribucion&action=jqgrid_clientes_sin_gestionar',
                                        datatype:'json',
                                        height:150,
                                        colNames:['Codigo','Cliente','Numero Doc','Usuario Gestion'],
                                        colModel:[
												{ name:'cli.codigo',index:'cli.codigo',align:'center',width:80 },
												{ name:'cli.nombre',index:'cli.nombre',align:'center',width:250 },
												{ name:'cli.numero_documento',index:'cli.numero_documento',align:'center',width:80 },
												{ name:'usuario_gestion',index:'usuario_gestion',align:'center',width:250 }
												],
                                        rowNum:10,
                                        rowList:[10,15,20],
                                        rownumbers:true,
                                        pager:'#pager_table_clientes_sin_gestionar',
                                        sortname:'cliente',
                                        sortorder:'desc',
                                        loadui: "block"
                                        });
				$("#table_clientes_sin_gestionar").jqGrid('navGrid','#pager_table_clientes_sin_gestionar',{edit:false,add:false,del:false,view:true});
				$("#table_clientes_sin_gestionar").jqGrid('filterToolbar');
			},
		clientes_por_cartera : function ( ) {
				$("#table_asignacion_por_operador").jqGrid({
                                        url:'../controller/ControllerCobrast.php?command=distribucion&action=jqgrid_clientes_por_cartera',
                                        datatype:'json',
                                        height:150,
                                        colNames:['Codigo','Cliente','Numero Doc','Tipo Doc'],
                                        colModel:[
												{ name:'cli.codigo',index:'cli.codigo',align:'center',width:60 },
												{ name:'cliente',index:'cliente',align:'center',width:250 },
												{ name:'cli.numero_documento',index:'cli.numero_documento',align:'center',width:60 },
												{ name:'cli.tipo_documento',index:'cli.tipo_documento',align:'center',width:60 }
												],
                                        rowNum:10,
                                        rowList:[10,15,20],
                                        rownumbers:true,
										onSelectRow : function ( id ) {
												agregar_cliente_distribucion_por_operador(id);
											},
                                        pager:'#pager_table_asignacion_por_operador',
                                        sortname:'cli.codigo',
                                        sortorder:'desc',
                                        loadui: "block"
                                        });
				$("#table_asignacion_por_operador").jqGrid('filterToolbar'); 
				$("#table_asignacion_por_operador").jqGrid('navGrid','#pager_table_asignacion_por_operador',{edit:false,add:false,del:false,view:true});
			},
		cliente_distribucion_especial : function ( ) {
				$("#table_clientes_distribucion_especial").jqGrid({
                                        url:'../controller/ControllerCobrast.php?command=distribucion&action=jqgrid_clientes',
                                        datatype:'json',
                                        height:150,
                                        colNames:['Codigo','Cliente','Numero Doc','Tipo Doc'],
                                        colModel:[
												{ name:'clicar.codigo_cliente',index:'clicar.codigo_cliente',align:'center',width:100 },
												{ name:'nombre',index:'nombre',align:'center',width:400 },
												{ name:'cli.numero_documento',index:'cli.numero_documento',align:'center',width:100 },
												{ name:'cli.tipo_documento',index:'cli.tipo_documento',align:'center',width:80 }
												],
                                        rowNum:10,
                                        rowList:[10,15,20],
                                        rownumbers:true,
                                        pager:'#pager_clientes_distribucion_especial',
                                        sortname:'cli.codigo',
                                        sortorder:'desc',
                                        loadui: "block"
                                        });
				$("#table_clientes_distribucion_especial").jqGrid('filterToolbar'); 
				$("#table_clientes_distribucion_especial").jqGrid('navGrid','#pager_clientes_distribucion_especial',{edit:false,add:false,del:false,view:true});
			},
		cliente_especiales_asignados : function ( ) {
				$("#table_clientes_asignados_distribucion_especial").jqGrid({
                                        url:'../controller/ControllerCobrast.php?command=distribucion&action=jqgrid_clientes_especiales_asignados',
                                        datatype:'json',
                                        height:150,
                                        colNames:['Codigo','Cliente','Numero Doc','Tipo Doc'],
                                        colModel:[
												{ name:'clicar.codigo_cliente',index:'clicar.codigo_cliente',align:'center',width:100 },
												{ name:'nombre',index:'nombre',align:'center',width:400 },
												{ name:'cli.numero_documento',index:'cli.numero_documento',align:'center',width:100 },
												{ name:'cli.tipo_documento',index:'cli.tipo_documento',align:'center',width:80 }
												],
                                        rowNum:10,
                                        rowList:[10,15,20],
                                        rownumbers:true,
                                        pager:'#pager_clientes_asignados_distribucion_especial',
                                        sortname:'cli.codigo',
                                        sortorder:'desc',
                                        loadui: "block"
                                        });
				$("#table_clientes_asignados_distribucion_especial").jqGrid('filterToolbar'); 
				$("#table_clientes_asignados_distribucion_especial").jqGrid('navGrid','#pager_clientes_asignados_distribucion_especial',{edit:false,add:false,del:false,view:true});
			}
	}