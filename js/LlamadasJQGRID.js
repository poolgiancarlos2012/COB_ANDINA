// JavaScript Document
var LlamadasJQGRID = {
		
		clientes : function ( ) {
				$("#table_clientes").jqGrid({
                                        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_cliente_servicio',
                                        datatype:'json',
                                        gridview:true,
                                        height:100,
                                        colNames:['Codigo','Nombre','Numero Doc','Tipo Doc'],
                                        colModel:[
													{ name:'cli.codigo',index:'cli.codigo',align:'center',width:100 },
													{ name:'cli.nombre',index:'cli.nombre',align:'center',width:400 },
													{ name:'cli.numero_documento',index:'cli.numero_documento',align:'center',width:100 },
													{ name:'cli.tipo_documento',index:'cli.tipo_documento',align:'center',width:100 }
												],
                                        rowNum:10,
                                        rowList:[10,15,20],
                                        rownumbers:true,
										onSelectRow: function ( id ) { 
													reload_llamadas(id);
												},
                                        pager:'#pager_table_clientes',
                                        sortname:'cli.nombre',
                                        sortorder:'desc',
										caption : 'CLIENTES',
                                        loadui: "block"
                                        });
				$("#table_clientes").jqGrid('filterToolbar');
				$("#table_clientes").jqGrid('navGrid','#pager_table_clientes',{edit:false,add:false,del:false,view:true});
			},
		LLamadasClientes : function ( ) {
				
				$("#table_llamadas").jqGrid({
                                        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_llamada',
                                        datatype:'json',
                                        gridview:true,
                                        height:300,
                                        colNames:['Cuenta(Inscripcion)','Telefono','Fecha Llamada','Hora Llamada','Estado','Fecha CP','Monto CP','Observacion','Teleoperador'],
                                        colModel:[
													{ name:'numero_cuenta',index:'numero_cuenta',align:'center',width:90 },
													{ name:'telefono',index:'telefono',align:'center',width:70 },
													{ name:'lla.fecha',index:'lla.fecha',align:'center',width:90 },
													{ name:'lla.fecha',index:'lla.fecha',align:'center',width:80 },
													{ name:'estado',index:'estado',align:'center',width:130 },
													{ name:'gescu.fecha_cp',index:'gescu.fecha_cp',align:'center',width:90 },
													{ name:'gescu.monto_cp',index:'gescu.monto_cp',align:'center',width:80 },
													{ name:'tran.observacion',index:'tran.observacion',align:'center',width:160 },
													{ name:'tran.idusuario_servicio',index:'tran.idusuario_servicio',align:'center',width:160 }
												],
                                        rowNum:10,
                                        rowList:[10,15,20],
                                        rownumbers:true,
										toolbar: [true,"top"],
                                        pager:'#pager_table_llamadas',
                                        sortname:'tran.fecha_creacion',
                                        sortorder:'desc',
										caption : 'Llamadas',
                                        loadui: "block"
                                        });
				$("#table_llamadas").jqGrid('navGrid','#pager_table_llamadas',{edit:false,add:false,del:false,view:true});
				
			}
		
	}