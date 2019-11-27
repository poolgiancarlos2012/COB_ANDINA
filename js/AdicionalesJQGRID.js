var AdicionalesJQGRID = {
		tipo_final : function ( ) {
				        $("#table_tipo_final").jqGrid({
											url:'../controller/ControllerCobrast.php?command=tipo_final&action=jqgrid_tipo_final',
											datatype:'json',
											gridview:true,
											height:100,
											colNames:['Nombre','Descripcion'],
											colModel:[
													{ name:'nombre',index:'nombre',align:'center',width:220 },
													{ name:'descripcion',index:'descripcion',align:'center',width:500 }
													],
											rowNum:10,
											rowList:[10,15,20],
											rownumbers:true,
											pager:'#pager_table_tipo_final',
											sortname:'nombre',
											sortorder:'desc',
											loadui: "block"
											});
						$("#table_tipo_final").jqGrid('navGrid','#pager_table_tipo_final',{edit:false,add:false,del:false,view:true});
			},
		carga_final : function ( ) {
						$("#table_carga_final").jqGrid({
											url:'../controller/ControllerCobrast.php?command=carga_final&action=jqgrid_carga_final',
											datatype:'json',
											gridview:true,
											height:100,
											colNames:['Nombre','Descripcion'],
											colModel:[
													{ name:'nombre',index:'nombre',align:'center',width:220 },
													{ name:'descripcion',index:'descripcion',align:'center',width:500 }
													],
											rowNum:10,
											rowList:[10,15,20],
											rownumbers:true,
											pager:'#pager_table_carga_final',
											sortname:'nombre',
											sortorder:'desc',
											loadui: "block"
											});
						$("#table_carga_final").jqGrid('navGrid','#pager_table_carga_final',{edit:false,add:false,del:false,view:true});
			},
		clase_final : function ( ) {
						$("#table_clase_final").jqGrid({
											url:'../controller/ControllerCobrast.php?command=clase_final&action=jqgrid_clase_final',
											datatype:'json',
											gridview:true,
											height:100,
											colNames:['Nombre','Descripcion'],
											colModel:[
													{ name:'nombre',index:'nombre',align:'center',width:220 },
													{ name:'descripcion',index:'descripcion',align:'center',width:500 }
													],
											rowNum:10,
											rowList:[10,15,20],
											rownumbers:true,
											pager:'#pager_table_clase_final',
											sortname:'nombre',
											sortorder:'desc',
											loadui: "block"
											});
						$("#table_clase_final").jqGrid('navGrid','#pager_table_clase_final',{edit:false,add:false,del:false,view:true});
			},
		nivel : function ( ) {
						$("#table_nivel").jqGrid({
											url:'../controller/ControllerCobrast.php?command=nivel&action=jqgrid_nivel',
											datatype:'json',
											gridview:true,
											height:100,
											colNames:['Nombre','Descripcion'],
											colModel:[
													{ name:'nombre',index:'nombre',align:'center',width:220 },
													{ name:'descripcion',index:'descripcion',align:'center',width:500 }
													],
											rowNum:10,
											rowList:[10,15,20],
											rownumbers:true,
											pager:'#pager_table_nivel',
											sortname:'nombre',
											sortorder:'desc',
											loadui: "block"
											});
						$("#table_nivel").jqGrid('navGrid','#pager_table_nivel',{edit:false,add:false,del:false,view:true});
			},
		tipo_gestion : function ( ) {
						$("#table_tipo_gestion").jqGrid({
											url:'../controller/ControllerCobrast.php?command=tipo_gestion&action=jqgrid_tipo_gestion',
											datatype:'json',
											gridview:true,
											height:100,
											colNames:['Nombre','Descripcion'],
											colModel:[
													{ name:'nombre',index:'nombre',align:'center',width:220 },
													{ name:'descripcion',index:'descripcion',align:'center',width:500 }
													],
											rowNum:10,
											rowList:[10,15,20],
											rownumbers:true,
											pager:'#pager_table_tipo_gestion',
											sortname:'nombre',
											sortorder:'desc',
											loadui: "block"
											});
						$("#table_tipo_gestion").jqGrid('navGrid','#pager_table_tipo_gestion',{edit:false,add:false,del:false,view:true});
			},
		finales:function(){
			$("#table_final").jqGrid({
									url:'../controller/ControllerCobrast.php?command=finales&action=jqgrid_final',
									datatype:'json',
									gridview:true,
									height:200,
									colNames:['Id Final','Nombre Final','Tipo','Clase','Carga','Nivel'],
									colModel:[
											{name:'fin.idfinal',index:'fin.idfinal',align:'center',width:70},
											{name:'fin.nombre',index:'fin.nombre',align:'center',width:150},
											{name:'tipfin.nombre',index:'tipfin.nombre',align:'center',width:150},
											{name:'clafin.nombre',index:'clafin.nombre',align:'center',width:120},
											{name:'carfin.nombre',index:'carfin.nombre',align:'center',width:120},
											{name:'nv.nombre',index:'nv.nombre',align:'center',width:200}
											],
									rowNum:10,
									rowList:[10,15,20],
									rownumbers:true,
									pager:'#pager_table_final',
									toolbar: [true,"top"],
									sortname:'fin.nombre',
									sortorder:'desc',
									loadui: "block"
			});
			$("#table_final").jqGrid('navGrid','#pager_table_final',{edit:false,add:false,del:false,view:true});
			$("#table_final").jqGrid('filterToolbar');
			$('#t_table_final').addClass('ui-corner-top');
			$('#t_table_final').attr('align','left');
			$('#t_table_final').css('height','25px');
			var html="";
				html+="<table>";
					html+="<tr>";
						html+="<td><div id='toolbar_final_icon_edit' class='ui-pg-button ui-corner-all' style='width:19px;margin:0 2px;' onclick='getParamEdit()' ><div class='ui-pg-div'><span class='ui-icon ui-icon-pencil'></span></div></div></td>";
						html+="<td><div id='toolbar_final_icon_add' class='ui-pg-button ui-corner-all' style='width:19px;margin:0 2px;' onclick='show_dialog_final()' ><div class='ui-pg-div'><span class='ui-icon ui-icon-plus'></span></div></div></td>";
					html+="</tr>";
				html+="</table>";
			$('#t_table_final').append(html);
			$('#toolbar_final_icon_edit').hover(function(){$(this).addClass('ui-state-hover');},function(){$(this).removeClass('ui-state-hover');});
			$('#toolbar_final_icon_add').hover(function(){$(this).addClass('ui-state-hover');},function(){$(this).removeClass('ui-state-hover');});
	},
	finalesxservicio:function( xusuario_modificacion ){
		$("#table_final_servicios").jqGrid({
										url:'../controller/ControllerCobrast.php?command=finalesxservicio&action=jqgrid_serviciosfinales&Servicio='+$('#hdCodServicio').val(),
										datatype:'json',
										gridview:true,
										height:200,
										colNames:['Id','Nombre de Final','Codigo','Prioridad','Peso','Efecto','Clase Final','Fecha Registro','flg_volver_llamar','estado_observa'],
										colModel:[
												{name:'id',index:'id',align:'center',width:70},
												{name:'nombre_final',index:'nombre_final',align:'center',width:250},
												{name:'codigo',index:'codigo',align:'center',width:50,editable:true},
												{name:'prioridad',index:'prioridad',align:'center',width:50,editable:true,sorttype:'int'},
												{name:'peso',index:'peso',align:'center',width:50,editable:true,sorttype:'int'},
												{name:'efecto',index:'efecto',align:'center',width:50},
												{name:'clase_final',index:'clase_final',align:'center',width:80},
												{name:'fecha_registro',index:'fecha_registro',align:'center',width:110},
												{name:'flg_volver_llamar',index:'flg_volver_llamar',align:'center',width:100,editable:true,sorttype:'int'},
												{name:'estado_observa',index:'estado_observa',align:'center',width:100,editable:true}
												],
										rowNum:10,
										rowList:[10,15,20],
										rownumbers:true,
										ondblClickRow : function ( rowid, irow, icol,e ) {
															$('#table_final_servicios').jqGrid('editRow',rowid,true,
																		function ( ) {},
																		function ( response ) {
																				var json_d = $.parseJSON(response.responseText);
																				if( json_d.rst ) {
																					$('#layerMessage').html(templates.MsgInfo(json_d.msg,'450px'));
																					$('#table_final_servicios').jqGrid().trigger('reloadGrid');
																				}else{
																					$('#layerMessage').html(templates.MsgInfo(json_d.msg,'450px'));
																				}
																				$('#layerMessage').fadeIn().delay(10000).fadeOut();
																			},
																		'../controller/ControllerCobrast.php',
																		{ command : 'finalesxservicio', action : 'update_peso_prioridad', usuario_modificacion : xusuario_modificacion  }
																		);
														},
										toolbar: [true,"top"],
										pager:'#pager_table_final_servicios',
										sortname:'fs.idfinal_servicio',
										sortorder:'desc',
										loadui: "block"
									});
		$("#table_final_servicios").jqGrid('navGrid','#pager_table_final_servicios',{edit:false,add:false,del:false,view:true});
		$('#t_table_final_servicios').addClass('ui-corner-top');
		$('#t_table_final_servicios').attr('align','left');
		$('#t_table_final_servicios').css('height','25px');
		var html="";
			html+="<table>";
				html+="<tr>";
					html+="<td><div id='toolbar_final_servicio_icon_del' class='ui-pg-button ui-corner-all' style='width:19px;margin:0 2px;' onclick='delete_final_servicio()' ><div class='ui-pg-div'><span class='ui-icon ui-icon-close'></span></div></div></td>";
				html+="</tr>";
			html+="</table>";
		$('#t_table_final_servicios').append(html);
		$('#toolbar_final_servicio_icon_del').hover(function(){$(this).addClass('ui-state-hover');},function(){$(this).removeClass('ui-state-hover');});
	}
		
		/******* --------- *******/
}
