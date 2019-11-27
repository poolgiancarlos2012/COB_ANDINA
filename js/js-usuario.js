$(document).ready(function(){
	$('#btnClusterServEdit,#btnClusterServAdd,#btnAddClusterUsuSer').button();
	//UsuarioDAO.ListarServicios();
	UsuarioDAO.ListarPrivilegios();
	UsuarioDAO.ListarTipoUsuario();
	UsuarioDAO.ListarClusterServicio();
	/***************************/
    UsuarioJQGRID.usuarios_activos();
	UsuarioJQGRID.mantenimiento_cluster();
	listar_notificador();
	listar_operador_cluster();
	/***************************/
	$('#mantenimientoCluster').dialog({
							  	height : 300,
								autoOpen : false,
								width : 500 ,
								title : 'MANTENIMIENTO CLUSTER',
								modal : true,
							  });
	$('#dialogMantenimientoClusterEdit').dialog({
										height : 150,
										autoOpen : false,
										width : 300 ,
										title : 'Modificar Cluster',
										modal:true,
							  });
	$('#dialogMantenimientoClusterAdd').dialog({
										height : 130,
										autoOpen : false,
										width : 300 ,
										title : 'Agregar Cluster',
										modal:true,
							  });
	$('#dialogCampania').dialog({
							  	height : 300,
								autoOpen : false,
								width : 420 ,
								title : 'Crear Campania',
								modal : true,
								buttons : {
										Cancel : function ( ) {
												$(this).dialog('close');
											},
										Aceptar : function ( ) {
												save_campania();
												//UsuarioDAO.insertCampania();
											}
									}
							  });
	/*************************/
	$("#FechaInicio,#FechaFin,#txtCampaniaFechaInicio,#txtCampaniaFechaFin").datepicker({dateFormat:'yy-mm-dd',dayNamesMin:['D','L','M','M','J','V','S'],monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']});
	$('#layerDatepicker').datepicker({inline:true,autoSize:true,dayNamesMin:['D','L','M','M','J','V','S'],monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']});

});
save_usuario = function ( ) {
	
	var rs=validacion.check([
			{id:'Nombre',required:true,isAlphaNumeric:true,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese nombre de usuario','350px'));
					UsuarioDAO.setTimeOut_hide_message();
				},errorAlphaNumericFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese solo texto y numero','350px'));
					UsuarioDAO.setTimeOut_hide_message();
				} },
			{id:'Paterno',required:true,isAlphaNumeric:true,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese apellido paterno','350px'));
					UsuarioDAO.setTimeOut_hide_message();
				},errorAlphaNumericFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese solo texto y numero','350px'));
					UsuarioDAO.setTimeOut_hide_message();
				} },
			{id:'Materno',required:true,isAlphaNumeric:true,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese apellido materno','350px'));
					UsuarioDAO.setTimeOut_hide_message();
				},errorAlphaNumericFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese solo texto y numero','350px'));
					UsuarioDAO.setTimeOut_hide_message();
				} },
			{id:'DNI',required:true,isDNI:true,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese dni','350px'));
					UsuarioDAO.setTimeOut_hide_message();
				},errorDniFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese solo numero','350px'));
					UsuarioDAO.setTimeOut_hide_message();
				} },
			{id:'Email',required:false,isEmail:true,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese email','350px'));
					UsuarioDAO.setTimeOut_hide_message();
				},errorEmailFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Formato incorrecto de correo','350px'));
					UsuarioDAO.setTimeOut_hide_message();
				} },
			{id:'TipoUsuario',required:true,isNotValue:0,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Seleccione una opcion','350px'));
					UsuarioDAO.setTimeOut_hide_message();
				},errorNotValueFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Seleccione otra opcion','350px'));
					UsuarioDAO.setTimeOut_hide_message();	
				} },
			{id:'Privilegio',required:true,isNotValue:0,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Seleccione una opcion','350px'));
					UsuarioDAO.setTimeOut_hide_message();
				},errorNotValueFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Selccioen otra opcion','350px'));
					UsuarioDAO.setTimeOut_hide_message();	
				} },
			{id:'FechaInicio',required:true,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese fecha de inicio','350px'));
					UsuarioDAO.setTimeOut_hide_message();
				} },
			{id:'FechaFin',required:true,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese fecha fin','350px'));
					UsuarioDAO.setTimeOut_hide_message();
				} },
			{id:'Clave',required:true,isNumber:true,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese clave','350px'));
					UsuarioDAO.setTimeOut_hide_message();
				},errorNumberFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese solo numeros','350px'));
					UsuarioDAO.setTimeOut_hide_message();	
				} },
			{id:'ConfClave',required:true,isConfirm:true,idConfirm:'Clave',errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese confirmacion de clave','350px'));
					UsuarioDAO.setTimeOut_hide_message();
				},errorConfirmFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Confirmacion Incorrecta','350px'));
					UsuarioDAO.setTimeOut_hide_message();	
				} }
		]);
		
	if( rs ){
		var rsC=confirm("Verifique los datos antes de  grabar");
		if( rsC ) {
			UsuarioDAO.insert();	
		}
  	}
}
update_usuario = function ( ) {
	var id=$('#IdUsuario').val();
	if( id=='' ){
		$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Seleccione Usuario','350px'));
		UsuarioDAO.setTimeOut_hide_message();	
		return false;	
	}
	var rs=validacion.check([
			{id:'Nombre',required:true,isAlphaNumeric:true,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese nombre de usuario','350px'));
					UsuarioDAO.setTimeOut_hide_message();	
				},errorAlphaNumericFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese solo texto y numero','350px'));
					UsuarioDAO.setTimeOut_hide_message();						
				} },
			{id:'Paterno',required:true,isAlphaNumeric:true,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese apellido paterno','350px'));
					UsuarioDAO.setTimeOut_hide_message();
				},errorAlphaNumericFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese solo texto y numero','350px'));
					UsuarioDAO.setTimeOut_hide_message();	
				} },
			{id:'Materno',required:true,isAlphaNumeric:true,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese apellido materno','350px'));
					UsuarioDAO.setTimeOut_hide_message();
				},errorAlphaNumericFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese solo texto y numero','350px'));
					UsuarioDAO.setTimeOut_hide_message();	
				} },
			{id:'DNI',required:true,isDNI:true,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese dni','350px'));
					UsuarioDAO.setTimeOut_hide_message();	
				},errorDniFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese solo numero','350px'));
					UsuarioDAO.setTimeOut_hide_message();
				} },
			{id:'Email',required:false,isEmail:true,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese email','350px'));
					UsuarioDAO.setTimeOut_hide_message();
				},errorEmailFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Formato incorrecto de correo','350px'));
					UsuarioDAO.setTimeOut_hide_message();	
				} },
			{id:'TipoUsuario',required:true,isNotValue:0,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Seleccione una opcion','350px'));
					UsuarioDAO.setTimeOut_hide_message();
				},errorNotValueFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Seleccione otra opcion','350px'));
					UsuarioDAO.setTimeOut_hide_message();	
				} },
			{id:'Privilegio',required:true,isNotValue:0,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Seleccione una opcion','350px'));
					UsuarioDAO.setTimeOut_hide_message();
				},errorNotValueFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Selccioen otra opcion','350px'));
					UsuarioDAO.setTimeOut_hide_message();	
				} },
			{id:'FechaInicio',required:true,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese fecha de inicio','350px'));
					UsuarioDAO.setTimeOut_hide_message();
				} },
			{id:'FechaFin',required:true,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese fecha fin','350px'));
					UsuarioDAO.setTimeOut_hide_message();
				} }
		]);
		
	if( rs ) {
		var rsC=confirm("Verifique los datos son correctos");
		if( rsC ) {
			UsuarioDAO.update();
		}
	}
    
}
delete_usuario = function ( ) {
	var id=$('#IdUsuario').val();
	if( id=='' ){
		$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Seleccione Usuario','350px'));
		UsuarioDAO.setTimeOut_hide_message();	
		return false;	
	}
	var rsC=confirm("Desea eliminar al usuario seleccionado");
	if( rsC ) {
		UsuarioDAO.Delete();
	}
}
cancel = function ( ) {
	var usuarioCreacion=$('#hdNomUsuario').val();
	$('#UsuarioCreacion').val(usuarioCreacion);
	$('#IdUsuarioServicio,#IdUsuario').val('');
	$('#panelNuevoUsuario :text').not('#UsuarioCreacion').val('');
	//$('#panelNuevoUsuario select').val(0);
	$('#panelNuevoUsuario :password').val('');
}
edit_usuario = function ( ) {
	var id=$("#table_user_teleoperador").jqGrid("getGridParam",'selrow');
	UsuarioDAO.DataById(id);
	$('#IdUsuarioServicio').val(id);	
}
agregar_servicio_usuario = function ( ) {
	var option='';
	var id=$("#cbServicioSystem").val();
	var nombre=$("#cbServicioSystem option:selected").text();
	
	if(id=='' || nombre==''){ return false; }
	
	option='<option value="'+id+'">'+ nombre+'</option>';
	$("#cbServicioUsuario").append(option);
	$("#cbServicioSystem option:selected").remove();
}
eliminar_servicio_usuario = function ( ) {
	var option='';
	var id=$("#cbServicioUsuario").val();
	var nombre=$("#cbServicioUsuario option:selected").text();
	
	if(id=='' || nombre==''){ return false; }
	
	option='<option value="'+id+'">'+ nombre+'</option>';
	$("#cbServicioSystem").append(option);
	$("#cbServicioUsuario option:selected").remove();
}
save_campania = function ( ) {
	var rs=validacion.check([
		{id:'txtCampaniaNombre',required:true,isAlphaNumeric:true,errorRequiredFunction : function ( ) {
				$('#CampaniaLayerMessage').html(templates.MsgError('Ingrese Nombre de Campa&ntilde;a','250px'));
				$('#CampaniaLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });	
			},errorAlphaNumericFunction : function ( ) {
				$('#CampaniaLayerMessage').html(templates.MsgError('Ingrese solo texto y numero','250px'));
				$('#CampaniaLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });	
			} },
		{id:'txtCampaniaFechaInicio',required:true,errorRequiredFunction : function ( ) {
				$('#CampaniaLayerMessage').html(templates.MsgError('Ingrese Fecha de Inicio','250px'));
				$('#CampaniaLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });	
			} },
		{id:'txtCampaniaFechaFin',required:true,errorRequiredFunction : function ( ) {
				$('#CampaniaLayerMessage').html(templates.MsgError('Ingrese Fecha Fin','250px'));
				$('#CampaniaLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });	
			} },
		{id:'txtCampaniDescripcion',required:true,isAlphaNumeric:true,errorRequiredFunction : function ( ) {
				$('#CampaniaLayerMessage').html(templates.MsgError('Ingrese Descripcion de Campa&ntilde;a','250px'));
				$('#CampaniaLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });	
			},errorAlphaNumericFunction : function ( ) {
				$('#CampaniaLayerMessage').html(templates.MsgError('Ingrese solo texto y numero','250px'));
				$('#CampaniaLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });	
			} }
	]);
	
	if( rs ) {
		var rsC=confirm("Verique que los datos ingresados son los correctos");
		if( rsC ) {
			UsuarioDAO.insertCampania();
		}
	}
}
listar_notificador = function ( ) {
	
	UsuarioDAO.ListarNotificador( $('#hdCodServicio').val(), function ( obj ) {
			var html = '';
			for( i=0;i<obj.length;i++ ) {
				html+='<tr id="'+obj[i].idnotificador+'">';
					html+='<td align="center" class="ui-widget-header" style="width:30px;padding:3px 0;" >'+(i+1)+'</td>';
					html+='<td align="center" class="ui-widget-content" style="width:100px;padding:3px 0;" >'+obj[i].nombre+'</td>';
					html+='<td align="center" class="ui-widget-content" style="width:100px;padding:3px 0;" >'+obj[i].paterno+'</td>';
					html+='<td align="center" class="ui-widget-content" style="width:100px;padding:3px 0;" >'+obj[i].materno+'</td>';
					html+='<td align="center" class="ui-widget-content" style="width:100px;padding:3px 0;">'+obj[i].correo+'</td>';
					html+='<td align="center" class="ui-widget-content" style="width:100px;padding:3px 0;">'+obj[i].telefono+'</td>';
					html+='<td align="center" class="ui-widget-content" style="width:100px;padding:3px 0;">'+obj[i].direccion+'</td>';
					html+='<td onclick="listar_notificador_por_id('+obj[i].idnotificador+')" align="center" class="ui-widget-content" style="width:30px;padding:3px 0;"><span class="ui-icon ui-icon-pencil"></span></td>';
					html+='<td onclick="delete_notificador('+obj[i].idnotificador+')" align="center" class="ui-widget-content" style="width:30px;padding:3px 0;"><span class="ui-icon ui-icon-trash"></span></td>';
				html+='</tr>';
			}
			$('#tableNotificadores').html(html);
		} );
	
}
listar_notificador_por_id = function ( idnotificador ) {
	UsuarioDAO.ListarNotificadorPorId( idnotificador, function ( obj ) {
			
			if( obj.length > 0 ) {
		
				$('#hdCodNotificador').val(obj[0].idnotificador);
				$('#txtNombreNotificador').val(obj[0].nombre);
				$('#txtPaternoNotificador').val(obj[0].paterno);
				$('#txtMaternoNotificador').val(obj[0].materno);
				$('#txtTelefonoNotificador').val(obj[0].telefono);
				$('#txtDireccionNotificador').val(obj[0].direccion);
				$('#txtCorreoNotificador').val(obj[0].correo);
			}
			
		} );
	
}
cancel_notificador = function ( ) {
	$('#table_form_notificador').find(':text,:hidden').val('');
}
guardar_notificador = function ( ) {
	
	var idservicio = $('#hdCodServicio').val();
	var nombre = $.trim( $('#txtNombreNotificador').val() );
	var paterno = $.trim( $('#txtPaternoNotificador').val() );
	var materno = $.trim( $('#txtMaternoNotificador').val() );
	var telefono = $.trim( $('#txtTelefonoNotificador').val() );
	var direccion = $.trim( $('#txtDireccionNotificador').val() );
	var correo = $.trim( $('#txtCorreoNotificador').val() );
	var usuario_creacion = $('#hdCodUsuario').val();
	UsuarioDAO.InsertNotificador( idservicio, nombre, paterno, materno, correo, telefono, direccion, usuario_creacion, function ( obj ) {
			if( obj.rst ) {
				listar_notificador();
				cancel_notificador();
				$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
				UsuarioDAO.setTimeOut_hide_message();
			}else{
				$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError(obj.msg,'250px'));
				UsuarioDAO.setTimeOut_hide_message();
			}
			
		} );
}
save_notificador = function ( ) {
	if( $('#hdCodNotificador').val() == '' ) {
		var rs = confirm("Verifique si los datos ingresados son correctos");
		if( rs ) {
			guardar_notificador();
		}
	}else{
		var rs = confirm("Desea actualizar los datos");
		if( rs ) {
			actualizar_notificador();
		}
	}
}
actualizar_notificador = function ( ) {
	
	var idnotificador = $('#hdCodNotificador').val();
	var nombre = $.trim( $('#txtNombreNotificador').val() );
	var paterno = $.trim( $('#txtPaternoNotificador').val() );
	var materno = $.trim( $('#txtMaternoNotificador').val() );
	var telefono = $.trim( $('#txtTelefonoNotificador').val() );
	var direccion = $.trim( $('#txtDireccionNotificador').val() );
	var correo = $.trim( $('#txtCorreoNotificador').val() );
	var usuario_modificacion = $('#hdCodUsuario').val();
	UsuarioDAO.UpdateNotificador( idnotificador, nombre, paterno, materno, correo, telefono, direccion, usuario_modificacion, function ( obj ) {
			if( obj.rst ) {
				listar_notificador();
				cancel_notificador();
				$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
				UsuarioDAO.setTimeOut_hide_message();
			}else{
				$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError(obj.msg,'250px'));
				UsuarioDAO.setTimeOut_hide_message();
			}
			
		} );
}
delete_notificador = function ( xidnotificador ) {
	var usuario_modificacion = $('#hdCodUsuario').val();
	UsuarioDAO.DeleteNotificador ( xidnotificador, usuario_modificacion, function ( obj ) {
			if( obj.rst ) {
				listar_notificador();
				$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
				UsuarioDAO.setTimeOut_hide_message();
			}else{
				$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError(obj.msg,'250px'));
				UsuarioDAO.setTimeOut_hide_message();
			}
		} ) ;
	
}
edit_notificador = function ( element ) {
	var nombre = $(element).parent().children('td:eq(1)').text();
	var paterno = $(element).parent().children('td:eq(2)').text();
	var materno = $(element).parent().children('td:eq(3)').text();
	var correo = $(element).parent().children('td:eq(4)').text();
	var telefono = $(element).parent().children('td:eq(5)').text();
	var direccion = $(element).parent().children('td:eq(6)').text();
	
	$(element).parent().children('td:eq(1)').html('<input class="cajaForm" style="width:90px;" type="text" value="'+nombre+'" />');
	$(element).parent().children('td:eq(2)').html('<input class="cajaForm" style="width:90px;" type="text" value="'+paterno+'" />');
	$(element).parent().children('td:eq(3)').html('<input class="cajaForm" style="width:90px;" type="text" value="'+materno+'" />');
	$(element).parent().children('td:eq(4)').html('<input class="cajaForm" style="width:90px;" type="text" value="'+correo+'" />');
	$(element).parent().children('td:eq(5)').html('<input class="cajaForm" style="width:90px;" type="text" value="'+telefono+'" />');
	$(element).parent().children('td:eq(6)').html('<input class="cajaForm" style="width:90px;" type="text" value="'+direccion+'" />');
	
	$(element).parent().children('td:eq(1)').find(':text').focus();
}
search_operadores_distribucion = function ( xtext, xidtable ) {
	var text = xtext;
	text = text.toUpperCase();
	$('#'+xidtable).find('tr').css('display','none');
	$('#'+xidtable+' tr').find('td:contains("'+text+'")').parent().css('display','block');
}
load_data_mantenimiento_cluster = function ( ) {
	var id=$("#table_mantenimiento_cluster").jqGrid("getGridParam",'selrow');
	if( id==null ) {
		return false;
	}
	UsuarioDAO.DataMantenimientoCluster(id,UsuarioDAO.FillFormDialogMantenimientoCluster);
	$('#dialogMantenimientoClusterEdit').dialog('open');
}
agregar_cluster_servicio = function ( ) {
	$('#dialogMantenimientoClusterAdd').dialog('open');
}
update_cluster_servicio = function ( ) {
	var id=$("#table_mantenimiento_cluster").jqGrid("getGridParam",'selrow');
	if( id==null ) {
		return false;
	}
	var nombre=$('#nombreClusterUpd').val();
	if(nombre==''){alert("Ingrese Nombre");return false}
	var rs=confirm("Verifique si los datos ingresados son los correctos");
	if( rs ){
		UsuarioDAO.update_cluster_servicio(id);
	}
}
save_cluster_servicio = function ( ) {
	var nombre=$('#nombreClusterAdd').val();
	if(nombre==''){alert("Ingrese Nombre");return false}
	var rs=confirm("Verifique si los datos ingresados son los correctos");
	if( rs ){
		UsuarioDAO.InsertClusterServicio();
	}
}
cancel_dialog = function ( idDialog ) {
	$('#'+idDialog).dialog('close');
}
listar_operador_cluster = function ( ) {
	
	UsuarioDAO.ListarOperadorCluster( $('#hdCodServicio').val(), function ( obj ) {
			var html = '';
			for( i=0;i<obj.length;i++ ) {
				html+='<tr class="ui-state-active" style="cursor:pointer;" id="'+obj[i].idusuario_servicio+'" onclick="_selectedRow(\'tableOperadoresCluster\','+obj[i].idusuario_servicio+');listar_data_cluster_operador_detalle('+obj[i].idusuario_servicio+');_sliderFadeLayer(\'layerDetalleCluster\')" onmouseover="_overRow(\'tableOperadoresCluster\','+obj[i].idusuario_servicio+')" style="font-weight:bold">';
					html+='<td align="center" class="backPanel headerPanel" style="width:30px;padding:3px 0;" >'+(i+1)+'</td>';
					html+='<td align="left" style="width:300px;padding:3px 5px;" >'+obj[i].Teleoperador+'</td>';
					html+='<td align="center" style="width:100px;padding:3px 0;" >'+obj[i].DNI+'</td>';
					html+='<td onclick="listar_data_cluster_operador_detalle('+obj[i].idusuario_servicio+');_sliderFadeLayer(\'layerDetalleCluster\')" align="center" style="width:30px;padding:3px 0;cursor:pointer"><span class="ui-icon ui-icon-person"></span></td>';
				html+='</tr>';
			}
			$('#tableOperadoresCluster').html(html);
		} );
	
}
listar_data_cluster_operador_detalle = function ( idusuario_servicio ) {
	
	UsuarioDAO.ListarOperadorClusterDetalle( idusuario_servicio, function ( obj ) {
			var html = '';
			for( i=0;i<obj.length;i++ ) {
				if(obj[i].estado=="ACTIVO"){icon="ui-icon-circle-close"}else{icon="ui-icon-check"}
				html+='<tr id="'+obj[i].idusuario_servicio_cluster+'">';
					html+='<td align="center" class="ui-state-default ui-th-column ui-th-ltr" style="width:30px;padding:3px 0;" >'+(i+1)+'</td>';
					html+='<td align="left" class="ui-widget-content" style="width:100px;padding:3px 5px;background-color:#FFF;" >'+obj[i].nombre+'</td>';
					html+='<td align="center" class="ui-widget-content" style="width:100px;padding:3px 0;" >'+obj[i].estado+'</td>';
					html+='<td onclick="cambiar_estado_cluster_operador('+obj[i].idusuario_servicio_cluster+','+idusuario_servicio+')" align="center" class="ui-widget-content" style="width:30px;padding:3px 0;cursor:pointer"><span class="ui-icon '+icon+'"></span></td>';
				html+='</tr>';
			}
			$('#tableOperadoresClusterDetalle').html(html);
			$('#idususerAddCluster').val(idusuario_servicio);
		} );
	
}
cambiar_estado_cluster_operador = function ( idususerclu,idususer ) {
	var usuario_modificacion = $('#hdCodUsuario').val();
	
	UsuarioDAO.ChangeEstadoUsuSerCluster( idususerclu,usuario_modificacion, function ( obj ) {
			if( obj.rst ) {
				listar_data_cluster_operador_detalle(idususer);
			}else{
				$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError(obj.msg,'250px'));
				UsuarioDAO.setTimeOut_hide_message();
			}
			
		} );
}
add_cluster_operador = function ( ) {
	var idususer=$('#idususerAddCluster').val();
	var idcluster=$('#tipoCluster').val();
	if(idususer==''){alert("Seleccione un Operador");return false}
	if(idcluster==0){alert("Seleccione un Cluster");return false}
	var rs=confirm("Confirme que desea Añadir Cluster al Operador");
	if( rs ){
		UsuarioDAO.InsertClusterServicioOperador(idususer,idcluster);
	}
}
