var ControlGestionJQGRID = {
		
		gestiones_servicio : function ( xusuario_modificacion ) {
				
				$("#tableGestionesControlGestion").jqGrid({
													url : '../controller/ControllerCobrast.php?command=cartera&action=jqgrid_gestiones_servicio&idservicio='+$('#hdCodServicio').val(),
													datatype : 'json',
													gridview : true,
													height : 400,
													colNames : ['Campania','Cartera','Fecha Carga','Fecha Inicio','Fecha Fin','Status','Meta Cliente','Meta Cuenta','Meta Monto','Registros','Cliente','Cuenta','Detalle','Flag_provincia'],
													colModel : [
															{ name:'cam.nombre',index:'cam.nombre',align:'center',width:150 },
															{ name:'car.nombre_cartera',index:'car.nombre_cartera',align:'center',width:200, editable:true },
															{ name:'car.fecha_carga',index:'car.fecha_carga',align:'center',width:80 },
															{ name:'car.fecha_inicio',index:'car.fecha_inicio',align:'center',width:80,editable:true},
															{ name:'car.fecha_fin',index:'car.fecha_fin',align:'center',width:80,editable:true},
															{ name:'car.status',index:'car.status',align:'center',width:60 },
															{ name:'car.meta_cliente',index:'car.meta_cliente',align:'center',width:60,editable:true,sorttype:'int'},
															{ name:'car.meta_cuenta',index:'car.meta_cuenta',align:'center',width:60,editable:true,sorttype:'int'},
															{ name:'car.meta_monto',index:'car.meta_monto',align:'center',width:60,editable:true,sorttype:'int'},
															{ name:'car.cantidad',index:'car.cantidad',align:'center',width:60 },
															{ name:'clientes',index:'clientes',align:'center',width:60 },
															{ name:'cuenta',index:'cuenta',align:'center',width:60 },
															{ name:'detalle',index:'detalle',align:'center',width:60 },
															{ name:'flag_provincia',index:'flag_provincia',align:'center',width:60,editable:true }
															],
													rowNum : 20,
													rowList : [20,30],
													ondblClickRow : function ( rowid, irow, icol,e ) {
															$('#tableGestionesControlGestion').jqGrid('editRow',rowid,true,
																		function ( ) {},
																		function ( response ) {
																				var json_d = $.parseJSON(response.responseText);
																				if( json_d.rst ) {
																					$('#layerMessage').html(templates.MsgInfo(json_d.msg,'450px'));
																					$('#tableGestionesControlGestion').jqGrid().trigger('reloadGrid');
																				}else{
																					$('#layerMessage').html(templates.MsgInfo(json_d.msg,'450px'));
																				}
																				$('#layerMessage').fadeIn().delay(10000).fadeOut();
																			},
																		'../controller/ControllerCobrast.php',
																		{ command : 'cartera', action : 'update_meta_fecha', usuario_modificacion : xusuario_modificacion  }
																		);
														},
													rownumbers : true,
													multiselect : true,
													toolbar : [true,"top"],
													pager : '#pagerGestionesControlGestion',
													sortname : 'car.fecha_carga',
													sortorder :'desc',
													loadui : "block"
												});
												$("#tableGestionesControlGestion").jqGrid('navGrid','#pagerGestionesControlGestion',{edit:false,add:false,del:false,view:true,refresh:true,search:false});
												$("#tableGestionesControlGestion").jqGrid('filterToolbar');
												$('#t_tableGestionesControlGestion').html('<button onclick="delete_cartera()" id="btnControlGestionServicioEliminar" >Eliminar</button><button onclick="desactive_cartera()" id="btnControlGestionServicioDesactivar" >Desactivar</button><button onclick="active_cartera()" id="btnControlGestionServicioActivar" >Activar</button>');
												
												$('#btnControlGestionServicioEliminar').button({icons : { primary : "ui-icon-trash" }});
												$('#btnControlGestionServicioDesactivar').button({icons : { primary : "ui-icon-closethick" }});
												$('#btnControlGestionServicioActivar').button({icons : { primary : "ui-icon-check" }});
				
			}
		
	}