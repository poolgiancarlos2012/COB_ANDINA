var SpeechJQGRID = {
		Listar : function ( ) {
				$("#table_speech").jqGrid({
                                        url:'../controller/ControllerCobrast.php?command=speech&action=jqgrid_ListarSpeech&Servicio='+$('#hdCodServicio').val(),
                                        datatype:'json',
                                        gridview:true,
                                        height:150,
                                        colNames:['Feha Creacion','Nombre','Tipo'],
                                        colModel:[
                                                        { name:'ag.fecha_creacion',index:'ag.fecha_creacion',align:'center',width:150 },
                                                        { name:'ag.ruta',index:'ag.ruta',align:'center',width:300 },
														{ name:'tag.nombre',index:'tag.nombre',align:'center',width:100 }
                                                    ],
                                        rowNum:10,
                                        rowList:[10,15,20],
                                        rownumbers:true,
                                        pager:'#pager_table_speech',
                                        sortname:'ag.fecha_creacion',
                                        sortorder:'desc',
                                        loadui: "block"
                                        });
				$("#table_speech").jqGrid('navGrid','#pager_table_speech',{edit:false,add:false,del:false,view:true});
			}
	}