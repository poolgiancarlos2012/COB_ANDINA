$(document).ready(function(){
	//UsuarioDAO.ListarServicios();
	UsuarioAdminDAO.ListarPrivilegios();
	UsuarioAdminDAO.ListarTipoUsuario();
	UsuarioAdminDAO.ListarServicio();
	/***************************/
    UsuarioAdminJQGRID.usuarios();
	UsuarioAdminJQGRID.servicios_usuario();
	/***************************/
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
	$('#dialogServiciosUsuario').dialog({
										height : 200,
										autoOpen : false,
										width : 480 ,
										title : 'Asignar Servicio',
										buttons : {
												Cancel : function ( ) {
														$(this).dialog('close');
													},
												Grabar : function ( ) {
														save_servicio_usuario();
													},
												Actualizar : function ( ) {
														update_servicio_usuario();
													},
												Eliminar : function ( ) {
														delete_servicio_usuario();
													}
											}
							  });
	/*************************/
	$("#FechaInicio,#FechaFin,#txtCampaniaFechaInicio,#txtCampaniaFechaFin,#FechaNacimiento").datepicker({dateFormat:'yy-mm-dd',dateFormat:'yy-mm-dd',dayNamesMin:['D','L','M','M','J','V','S'],monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']});
	$('#layerDatepicker').datepicker({inline:true,autoSize:true,dateFormat:'yy-mm-dd',dayNamesMin:['D','L','M','M','J','V','S'],monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']});

});
save_usuario = function ( ) {
	
	var rs=validacion.check([
			{id:'Nombre',required:true,isAlphaNumeric:true,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese nombre de usuario','200px'));
					$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });	
				},errorAlphaNumericFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese solo texto y numero','200px'));
					$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });						
				} },
			{id:'Paterno',required:true,isAlphaNumeric:true,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese apellido paterno','200px'));
					$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });
				},errorAlphaNumericFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese solo texto y numero','200px'));
					$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });	
				} },
			{id:'Materno',required:true,isAlphaNumeric:true,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese apellido materno','200px'));
					$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });
				},errorAlphaNumericFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese solo texto y numero','200px'));
					$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });	
				} },
			{id:'DNI',required:true,isDNI:true,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese dni','200px'));
					$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });	
				},errorDniFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese solo numero','200px'));
					$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });
				} },
			{id:'Email',required:false,isEmail:true,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese email','200px'));
					$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });
				},errorEmailFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Formato incorrecto de correo','200px'));
					$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });	
				} },
			{id:'Clave',required:true,isNumber:true,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese clave','200px'));
					$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });
				},errorNumberFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese solo numeros','200px'));
					$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });
				} },
			{id:'ConfClave',required:true,isConfirm:true,idConfirm:'Clave',errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese confirmacion de clave','200px'));
					$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });
				},errorConfirmFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Confirmacion Incorrecta','200px'));
					$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });	
				} }
		]);
		
	if( rs ){
		var rsC=confirm("Verifique los datos antes de  grabar");
		if( rsC ) {
			UsuarioAdminDAO.insert();
		}
  	}
}
update_usuario = function ( ) {
	var id=$('#IdUsuario').val();
	if( id=='' ){
		$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Seleccione Usuario','200px'));
		$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });	
		return false;	
	}
	var rs=validacion.check([
			{id:'Nombre',required:true,isAlphaNumeric:true,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese nombre de usuario','200px'));
					$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });	
				},errorAlphaNumericFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese solo texto y numero','200px'));
					$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });						
				} },
			{id:'Paterno',required:true,isAlphaNumeric:true,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese apellido paterno','200px'));
					$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });
				},errorAlphaNumericFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese solo texto y numero','200px'));
					$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });	
				} },
			{id:'Materno',required:true,isAlphaNumeric:true,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese apellido materno','200px'));
					$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });
				},errorAlphaNumericFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese solo texto y numero','200px'));
					$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });	
				} },
			{id:'DNI',required:true,isDNI:true,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese dni','200px'));
					$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });	
				},errorDniFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese solo numero','200px'));
					$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });
				} },
			{id:'Email',required:false,isEmail:true,errorRequiredFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Ingrese email','200px'));
					$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });
				},errorEmailFunction : function ( ) {
					$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Formato incorrecto de correo','200px'));
					$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });	
				} }
		]);
		
	if( rs ) {
		var rsC=confirm("Verifique los datos son correctos");
		if( rsC ) {
			UsuarioAdminDAO.update();
		}
	}
    
}
delete_usuario = function ( ) {
	var id=$('#IdUsuario').val();
	if( id=='' ){
		$('#'+UsuarioDAO.idLayerMessage).html(templates.MsgError('Seleccione Usuario','200px'));
		$('#'+UsuarioDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });	
		return false;	
	}
	var rsC=confirm("Desea eliminar al usuario seleccionado");
	if( rsC ) {
		UsuarioAdminDAO.Delete();
	}
}
cancel = function ( ) {
	$('#panelNuevoUsuario').find(':text,:hidden,:password').not('#UsuarioCreacion,#Codigo').val('');
	$('#panelNuevoUsuario #Codigo').val('Generando');
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
load_JQGRID_servicios_usuarios = function ( idUsuario ) {
	$("#table_servicios_usuario").jqGrid('setGridParam',{url:'../controller/ControllerCobrast.php?command=usuario_admin&action=jqgrid_servicios_usuario&Usuario='+idUsuario}).trigger('reloadGrid');	
}
load_data_usuario = function ( ) {
	var id=$("#table_user_admin").jqGrid("getGridParam",'selrow');
	if( id==null ) {
		return false;
	}
	UsuarioAdminDAO.DataUsuario(id,UsuarioAdminDAO.FillFormUsuario);
}
agregar_servicio_usuario = function ( ) {
	var id=$("#table_user_admin").jqGrid("getGridParam",'selrow');
	if( id==null ) {
		return false;
	}
	$('#dialogServiciosUsuario').dialog('open');
}
save_servicio_usuario = function ( ) {
	var id=$("#table_user_admin").jqGrid("getGridParam",'selrow');
	if( id==null ) {
		return false;
	}
	var rs=confirm("Verifique si los datos ingresados son los correctos");
	if( rs ){
		UsuarioAdminDAO.InsertUsuarioServicio(id);
	}
}
update_servicio_usuario = function ( ) {
	var id=$("#table_user_admin").jqGrid("getGridParam",'selrow');
	if( id==null ) {
		return false;
	}
	var rs=confirm("Verifique si los datos ingresados son los correctos");
	if( rs ){
		UsuarioAdminDAO.update_usuario_servicio(id);
	}
}
delete_servicio_usuario = function ( ) {
	var rs=confirm("Desea eliminar servicio");
	if( rs ){
		UsuarioAdminDAO.delete_usuario_servicio();
	}
}
cancel_usuario_servicio = function ( ) {
	$('#dialogServiciosUsuario').find(':text,:hidden').val('');
	$('#dialogServiciosUsuario').find('select').val(0);
}

load_data_servicio_usuario = function ( ) {
	var id=$("#table_servicios_usuario").jqGrid("getGridParam",'selrow');
	if( id==null ) {
		return false;
	}
	UsuarioAdminDAO.DataServicioUsuario(id,UsuarioAdminDAO.FillFormServicioUsuario);
	$('#dialogServiciosUsuario').dialog('open');
}






