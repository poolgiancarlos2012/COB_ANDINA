$(document).ready(function(){
		ServicioJQGRID.servicio();
		ServicioJQGRID.usuarioAdmin();
		ServicioJQGRID.usuarioOpera();
        /*****************/
		ServicioDAO.ListarTipoUsuario();
		ServicioDAO.ListarPrivilegios();
		/*****************/
		$('#layerDatepicker').datepicker({inline:true,autoSize:true});
		$('#txtCampaniaFechaInicio,#txtCampaniaFechaFin,#txtUsuarioFechaInicio,#txtUsuarioFechaFin').datepicker({dateFormat:'yy-mm-dd'});
		/****************/
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
												//ServicioDAO.insertCampania();
											}
									}
							  });
	
		$('#dialogUsuario').dialog({
									height : 400,
									autoOpen : false,
									width : 290 ,
									title : 'Crear Usuario',
									modal : true,
									buttons : {
											Cancel : function ( ) {
													$(this).dialog('close');
												},
											Aceptar : function ( ) {
													save_usuario();
													//ServicioDAO.insertUsuario();
												}
										}
								  });
								  
	/*********/
	
});
save_servicio=function(){
	
	var rs=validacion.check(
			[
			{id:'txtNombre',required:true,isAlphaNumeric:true,errorRequiredFunction : function ( ) {
					$('#'+ServicioDAO.idLayerMessage).html(templates.MsgError('Ingrese Nombre de Servicio','200px'));
					$('#'+ServicioDAO.idLayerMessage).effect('pulsate',{},'fast',function(){ $(this).empty(); });	
				},errorAlphaNumericFunction : function ( ) {
					$('#'+ServicioDAO.idLayerMessage).html(templates.MsgError('Ingrese solo texto y numero','200px'));
					$('#'+ServicioDAO.idLayerMessage).effect('pulsate',{},'fast',function(){ $(this).empty(); });	
					} },  
			{id:'txtDescripcion',required:true,isAlphaNumeric:true,errorRequiredFunction : function ( ) {
					$('#'+ServicioDAO.idLayerMessage).html(templates.MsgError('Ingrese Descripcion','200px'));
					$('#'+ServicioDAO.idLayerMessage).effect('pulsate',{},'fast',function(){ $(this).empty(); });	
				},errorAlphaNumericFunction : function ( ) {
					$('#'+ServicioDAO.idLayerMessage).html(templates.MsgError('Ingrese solo texto y numero','200px'));
					$('#'+ServicioDAO.idLayerMessage).effect('pulsate',{},'fast',function(){ $(this).empty(); });	
					} }
			]	
		);
	//alert(rs);
	if( rs ){
		var rsC=confirm("Verifique los datos antes de grabar");
		if(rsC){
			ServicioDAO.insert();		
		}
	}
	//ServicioDAO.insert();
	
}
update_servicio=function(){
	var id=$('#IdServicio').val();
	if( id=='' ) {
		$('#'+ServicioDAO.idLayerMessage).html(templates.MsgError('Seleccione servicio a actualizar','200px'));
		$('#'+ServicioDAO.idLayerMessage).effect('pulsate',{},'fast',function(){ $(this).empty(); });	
	}
	var rs=validacion.check(
			[
			{id:'txtNombre',required:true,isAlphaNumeric:true,errorRequiredFunction : function ( ) {
					$('#'+ServicioDAO.idLayerMessage).html(templates.MsgError('Ingrese Nombre de Servicio','200px'));
					$('#'+ServicioDAO.idLayerMessage).effect('pulsate',{},'fast',function(){ $(this).empty(); });	
				},errorAlphaNumericFunction : function ( ) {
					$('#'+ServicioDAO.idLayerMessage).html(templates.MsgError('Ingrese solo texto y numero','200px'));
					$('#'+ServicioDAO.idLayerMessage).effect('pulsate',{},'fast',function(){ $(this).empty(); });	
					} },  
			{id:'txtDescripcion',required:true,isAlphaNumeric:true,errorRequiredFunction : function ( ) {
					$('#'+ServicioDAO.idLayerMessage).html(templates.MsgError('Ingrese Descripcion','200px'));
					$('#'+ServicioDAO.idLayerMessage).effect('pulsate',{},'fast',function(){ $(this).empty(); });	
				},errorAlphaNumericFunction : function ( ) {
					$('#'+ServicioDAO.idLayerMessage).html(templates.MsgError('Ingrese solo texto y numero','200px'));
					$('#'+ServicioDAO.idLayerMessage).effect('pulsate',{},'fast',function(){ $(this).empty(); });	
					} }
			]	
		);
	
	//var xid=$("#hdIdServicio").val();
    //var xidusuario=$("#hdIdUsuario").val();
	
	if(rs){
		var rsC=confirm("Verifique los datos antes de grabar");
		if( rsC ){
			ServicioDAO.update();	
		}
	}
	
	//ServicioDAO.update();
}
delete_servicio=function(){
	var id=$('#IdServicio').val();
	if( id=='' ) {
		$('#'+ServicioDAO.idLayerMessage).html(templates.MsgError('Seleccione servicio a eliminar','200px'));
		$('#'+ServicioDAO.idLayerMessage).effect('pulsate',{},'fast',function(){ $(this).empty(); });	
	}
	
	var rsC=confirm("Desesa eliminar el servicio seleccionado");
	
	if( rsC ){
		ServicioDAO.Delete();
	}
	
	//ServicioDAO.Delete();
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
	
	if( rs ){
		var rsC=confirm("Verifique que los datos ingresados son los correctos");
		if( rsC ) {
			ServicioDAO.insertCampania();
		}
	}	
	
}
save_usuario = function ( ) {
	var rs=validacion.check([
			{id:'txtUsuarioNombre',required:true,isAlphaNumeric:true,errorRequiredFunction : function ( ) {
					$('#UsuarioLayerMessage').html(templates.MsgError('Ingrese nombre de usuario','200px'));
					$('#UsuarioLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });	
				},errorAlphaNumericFunction : function ( ) {
					$('#UsuarioLayerMessage').html(templates.MsgError('Ingrese solo texto y numero','200px'));
					$('#UsuarioLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });						
				} },
			{id:'txtUsuarioPaterno',required:true,isAlphaNumeric:true,errorRequiredFunction : function ( ) {
					$('#UsuarioLayerMessage').html(templates.MsgError('Ingrese apellido paterno','200px'));
					$('#UsuarioLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });
				},errorAlphaNumericFunction : function ( ) {
					$('#UsuarioLayerMessage').html(templates.MsgError('Ingrese solo texto y numero','200px'));
					$('#UsuarioLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });	
				} },
			{id:'txtUsuarioMaterno',required:true,isAlphaNumeric:true,errorRequiredFunction : function ( ) {
					$('#UsuarioLayerMessage').html(templates.MsgError('Ingrese apellido materno','200px'));
					$('#UsuarioLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });
				},errorAlphaNumericFunction : function ( ) {
					$('#UsuarioLayerMessage').html(templates.MsgError('Ingrese solo texto y numero','200px'));
					$('#UsuarioLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });	
				} },
			{id:'txtUsuarioDni',required:true,isDNI:true,errorRequiredFunction : function ( ) {
					$('#UsuarioLayerMessage').html(templates.MsgError('Ingrese dni','200px'));
					$('#UsuarioLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });	
				},errorDniFunction : function ( ) {
					$('#UsuarioLayerMessage').html(templates.MsgError('Ingrese solo numero','200px'));
					$('#UsuarioLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });
				} },
			{id:'txtServicioEmail',required:false,isEmail:true,errorRequiredFunction : function ( ) {
					$('#UsuarioLayerMessage').html(templates.MsgError('Ingrese email','200px'));
					$('#UsuarioLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });
				},errorEmailFunction : function ( ) {
					$('#UsuarioLayerMessage').html(templates.MsgError('Formato incorrecto de correo','200px'));
					$('#UsuarioLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });	
				} },
			{id:'cbTipoUsuario',required:true,isNotValue:0,errorRequiredFunction : function ( ) {
					$('#UsuarioLayerMessage').html(templates.MsgError('Seleccione una opcion','200px'));
					$('#UsuarioLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });
				},errorNotValueFunction : function ( ) {
					$('#UsuarioLayerMessage').html(templates.MsgError('Seleccione otra opcion','200px'));
					$('#UsuarioLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });	
				} },
			{id:'cbPrivilegioUsuario',required:true,isNotValue:0,errorRequiredFunction : function ( ) {
					$('#UsuarioLayerMessage').html(templates.MsgError('Seleccione una opcion','200px'));
					$('#UsuarioLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });
				},errorNotValueFunction : function ( ) {
					$('#UsuarioLayerMessage').html(templates.MsgError('Selccioen otra opcion','200px'));
					$('#UsuarioLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });	
				} },
			{id:'txtUsuarioFechaInicio',required:true,errorRequiredFunction : function ( ) {
					$('#UsuarioLayerMessage').html(templates.MsgError('Ingrese fecha de inicio','200px'));
					$('#UsuarioLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });
				} },
			{id:'txtUsuarioFechaFin',required:true,errorRequiredFunction : function ( ) {
					$('#UsuarioLayerMessage').html(templates.MsgError('Ingrese fecha fin','200px'));
					$('#UsuarioLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });
				} },
			{id:'txtUsuarioClave',required:true,isNumber:true,errorRequiredFunction : function ( ) {
					$('#UsuarioLayerMessage').html(templates.MsgError('Ingrese clave','200px'));
					$('#UsuarioLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });
				},errorNumberFunction : function ( ) {
					$('#UsuarioLayerMessage').html(templates.MsgError('Ingrese solo numeros','200px'));
					$('#UsuarioLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });	
				} },
			{id:'txtUsuarioConfClave',required:true,isConfirm:true,idConfirm:'txtUsuarioClave',errorRequiredFunction : function ( ) {
					$('#UsuarioLayerMessage').html(templates.MsgError('Ingrese confirmacion de clave','200px'));
					$('#UsuarioLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });
				},errorConfirmFunction : function ( ) {
					$('#UsuarioLayerMessage').html(templates.MsgError('Confirmacion Incorrecta','200px'));
					$('#UsuarioLayerMessage').effect('pulsate',{},'slow',function(){ $(this).empty(); });	
				} }
		]);
		
	if( rs ) {
		var rsC=confirm("Verifique los datos antes de grabar");
		if( rsC ) {
			ServicioDAO.insertUsuario();
		} 
	}
}

cancel=function(){
	var usuarioCreacion=$('#hdNomUsuario').val();
	$('#txtUsuarioCreacion').val(usuarioCreacion);
	$('#IdServicio').val('');
	$("#panelCrearServicio #txtNombre").val('');
	$("#panelCrearServicio #txtDescripcion").val('');
}
edit_servicio = function ( ) {
	var id=$("#table_servicio").jqGrid("getGridParam",'selrow');
	ServicioDAO.DataById(id);
	$('#IdServicio').val(id);
	//_display_panel('panelCrearServicio');
}
reloadJQGRIDServicio = function ( ){
	$('#table_servicio').trigger('reloadGrid');	
}
