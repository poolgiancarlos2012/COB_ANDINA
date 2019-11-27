var ServicioJQGRID={
    usuarioAdmin:function(){
        $("#table_usuario_admin").jqGrid({
                                        url:'../controller/ControllerCobrast.php?command=servicio&action=jqgrid_usuarioAdmin_servicio',
                                        datatype:'json',
                                        gridview:true,
                                        height:150,
                                        colNames:['Nombre','Dni','Email','Fecha de Registro','Servicios'],
                                        colModel:[
                                                        { name:'usuario',index:'usuario',align:'center',width:240 },
                                                        { name:'usu.dni',index:'usu.dni',align:'center',width:100 },
														{ name:'usu.email',index:'usu.email',align:'center',width:140 },
														{ name:'usu.fecha_creacion',index:'usu.fecha_creacion',align:'center',width:150 },
														{ name:'servicios',index:'servicios',align:'center',width:100 }
                                                    ],
                                        rowNum:10,
                                        rowList:[10,15,20],
                                        rownumbers:true,
                                        pager:'#pager_table_usuario_admin',
                                        sortname:'usu.dni',
                                        sortorder:'desc',
                                        loadui: "block"
                                        });
		
	
		
    },
	usuarioOpera:function(){
        $("#table_usuario_opera").jqGrid({
                                        url:'../controller/ControllerCobrast.php?command=servicio&action=jqgrid_usuarioOpera_servicio',
                                        datatype:'json',
                                        gridview:true,
                                        height:150,
                                        colNames:['Nombre','Dni','Email','Fecha de Registro','Servicios'],
                                        colModel:[
                                                        { name:'usuario',index:'usuario',align:'center',width:250 },
                                                        { name:'usu.dni',index:'usu.dni',align:'center',width:100 },
														{ name:'usu.email',index:'usu.email',align:'center',width:130 },
														{ name:'usu.fecha_creacion',index:'usu.fecha_creacion',align:'center',width:150 },
														{ name:'servicios',index:'servicios',align:'center',width:100 }
                                                    ],
                                        rowNum:10,
                                        rowList:[10,15,20],
                                        rownumbers:true,
                                        pager:'#pager_table_usuario_opera',
                                        sortname:'usu.dni',
                                        sortorder:'desc',
                                        loadui: "block"
                                        });
		
	
		
    },
    servicio:function(){
        $("#table_servicio").jqGrid({
                                        url:'../controller/ControllerCobrast.php?command=servicio&action=jqgrid_servicio',
                                        datatype:'json',
                                        gridview:true,
                                        height:100,
                                        colNames:['Nombre','Descripcion'],
                                        colModel:[
                                                        { name:'nombre',index:'nombre',align:'center',width:150 },
                                                        { name:'descripcion',index:'descripcion',align:'center',width:550 }
                                                        ],
                                        rowNum:10,
                                        rowList:[10,15],
                                        rownumbers:true,
                                        pager:'#pager_table_servicio',
                                        sortname:'nombre',
                                        sortorder:'desc',
										toolbar: [true,"top"],
                                        loadui: "block"
                                    });
		
		$("#table_servicio").jqGrid('navGrid','#pager_table_servicio',{edit:false,add:false,del:false,view:true});
		$('#t_table_servicio').addClass('ui-corner-top');
		$('#t_table_servicio').attr('align','left');
		$("#t_table_servicio").append("<div id='toolbar_servicio_icon_edit' class='ui-pg-button ui-corner-all' style='width:19px;margin:0 2px;' onclick='edit_servicio()' ><div class='ui-pg-div'><span class='ui-icon ui-icon-pencil'></span></div></div>");
		$('#toolbar_servicio_icon_edit').hover(function(){$('#toolbar_servicio_icon_edit').addClass('ui-state-hover');},function(){$('#toolbar_servicio_icon_edit').removeClass('ui-state-hover');});
		
    }
}