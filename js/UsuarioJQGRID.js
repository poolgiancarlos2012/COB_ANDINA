var UsuarioJQGRID={
    administrador: function ( ) {
        $("#table_user_admin").jqGrid({
                                        url:'../controller/ControllerCobrast.php?command=usuario&action=jqgrid_usuario_administrador',
                                        datatype:'json',
                                        gridview:true,
                                        height:150,
                                        colNames:['Nombre','Email','Servicio'],
                                        colModel:[
													{ name:'usuario',index:'usuario',align:'center',width:300 },
													{ name:'usu.email',index:'usu.email',align:'center',width:250 },
													{ name:'servicio',index:'servicio',align:'center',width:200 }
                                                    ],
                                        rowNum:10,
                                        rowList:[10,15,20],
                                        rownumbers:true,
                                        pager:'#pager_table_user_admin',
                                        sortname:'usuario',
										toolbar: [true,"top"],
                                        sortorder:'desc',
                                        loadui: "block"
                                        });
										
		$("#table_user_admin").jqGrid('navGrid','#pager_table_user_admin',{edit:false,add:false,del:false,view:true});
		$('#t_table_user_admin').addClass('ui-corner-top');
		$('#t_table_user_admin').attr('align','left');
		$("#t_table_user_admin").append("<div id='toolbar_usuarios_admin_icon_edit' class='ui-pg-button ui-corner-all' style='width:19px;margin:0 2px;' onclick='' ><div class='ui-pg-div'><span class='ui-icon ui-icon-pencil'></span></div></div>");
		$('#toolbar_usuarios_admin_icon_edit').hover(function(){$('#toolbar_usuarios_admin_icon_edit').addClass('ui-state-hover');},function(){$('#toolbar_usuarios_admin_icon_edit').removeClass('ui-state-hover');});
    },
    usuarios_activos: function ( ) {
        $("#table_user_teleoperador").jqGrid({
                                        url:'../controller/ControllerCobrast.php?command=usuario&action=jqgrid_usuario_teleoperador_gestor_campo&Servicio='+$('#hdCodServicio').val(),
                                        datatype:'json',
                                        gridview:true,
                                        height:200,
                                        colNames:['Nombre','Dni','Email','Tipo','Privilegio','Fecha Inicio','Fecha Fin','Fecha Registro'],
                                        colModel:[
													{ name:'usu.nombre',index:'usu.nombre',align:'center',width:200 },
													{ name:'usu.dni',index:'usu.dni',align:'center',width:80 },
													{ name:'usu.email',index:'usu.email',align:'center',width:80 },
													{ name:'tipo_usuario',index:'tipo_usuario',align:'center',width:80 },
													{ name:'privilegio',index:'privilegio',align:'center',width:80 },
													{ name:'ususer.fecha_inicio',index:'ususer.fecha_inicio',align:'center',width:80 },
													{ name:'ususer.fecha_fin',index:'ususer.fecha_fin',align:'center',width:80 },
													{ name:'usu.fecha_creacion',index:'usu.fecha_creacion',align:'center',width:100 }
                                                    ],
                                        rowNum:10,
                                        rowList:[10,15,20],
                                        rownumbers:true,
                                        pager:'#pager_table_user_teleoperador',
                                        sortname:'usuario',
                                        sortorder:'desc',
										toolbar: [true,"top"],
                                        loadui: "block"
                                        });
										
		$("#table_user_teleoperador").jqGrid('navGrid','#pager_table_user_teleoperador',{edit:false,add:false,del:false,view:true});
		$("#table_user_teleoperador").jqGrid('filterToolbar');
		$('#t_table_user_teleoperador').addClass('ui-corner-top');
		$('#t_table_user_teleoperador').attr('align','left');
		$("#t_table_user_teleoperador").append("<div id='toolbar_usuarios_activos_icon_edit' class='ui-pg-button ui-corner-all' style='width:19px;margin:0 2px;' onclick='edit_usuario()' ><div class='ui-pg-div'><span class='ui-icon ui-icon-pencil'></span></div></div>");
		$('#toolbar_usuarios_activos_icon_edit').hover(function(){$('#toolbar_usuarios_activos_icon_edit').addClass('ui-state-hover');},function(){$('#toolbar_usuarios_activos_icon_edit').removeClass('ui-state-hover');});
    },
	mantenimiento_cluster : function ( ) {
			$("#table_mantenimiento_cluster").jqGrid({
                                        url:'../controller/ControllerCobrast.php?command=usuario&action=jqgrid_mantenimiento_cluster',
                                        datatype:'json',
                                        gridview:true,
                                        height:150,
                                        colNames:['Nombre','Descripcion','Estado'],
                                        colModel:[
													{ name:'nombre',index:'nombre',align:'center',width:100 },
													{ name:'descripcion',index:'descripcion',align:'center',width:150 },
													{ name:'estado',index:'estado',align:'center',width:120 }
													],
                                        rowNum:10,
                                        rowList:[10,15,20],
                                        rownumbers:true,
                                        pager:'#pager_table_mantenimiento_cluster',
                                        sortname:'nombre',
										toolbar: [true,"top"],
                                        sortorder:'desc',
                                        loadui: "block"
                                        });
										
			$("#table_mantenimiento_cluster").jqGrid('navGrid','#pager_table_mantenimiento_cluster',{edit:false,add:false,del:false,view:true});
			$('#t_table_mantenimiento_cluster').addClass('ui-corner-top');
			$('#t_table_mantenimiento_cluster').attr('align','left');
			$('#t_table_mantenimiento_cluster').css('height','25px');
				var html="";
					html+="<table>";
						html+="<tr>";
							html+="<td><div id='toolbar_mantenimiento_cluster_icon_edit' class='ui-pg-button ui-corner-all' style='width:19px;margin:0 2px;' onclick='load_data_mantenimiento_cluster()' ><div class='ui-pg-div'><span class='ui-icon ui-icon-pencil'></span></div></div></td>";
							html+="<td><div id='toolbar_mantenimiento_cluster_icon_add' class='ui-pg-button ui-corner-all' style='width:19px;margin:0 2px;' onclick='agregar_cluster_servicio()' ><div class='ui-pg-div'><span class='ui-icon ui-icon-plus'></span></div></div></td>";
						html+="</tr>";
					html+="</table>";
			$('#t_table_mantenimiento_cluster').append(html);
			$('#toolbar_mantenimiento_cluster_icon_edit').hover(function(){$(this).addClass('ui-state-hover');},function(){$(this).removeClass('ui-state-hover');});
			$('#toolbar_mantenimiento_cluster_icon_add').hover(function(){$(this).addClass('ui-state-hover');},function(){$(this).removeClass('ui-state-hover');});
		}
	

}