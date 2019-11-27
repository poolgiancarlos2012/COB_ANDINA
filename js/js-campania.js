$(document).ready(function(){
	CampaniaJQGRID.Campania();
	/***************/
	CampaniaDAO.ListarTipoUsuario();
	CampaniaDAO.ListarPrivilegios();
	/***************/
	$('#txtFechaInicio,#txtFechaFin,#txtUsuarioFechaInicio,#txtUsuarioFechaFin').datepicker({dateFormat:'yy-mm-dd',dayNamesMin:['D','L','M','M','J','V','S'],monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']});
	$('#layerDatepicker').datepicker({inline:true,autoSize:true,dayNamesMin:['D','L','M','M','J','V','S'],monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']});
	/****************/	
	$('#dialogUsuario').dialog({
								height : 400,
								autoOpen : false,
								width : 280 ,
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
});
edit_campania = function ( ) {
	var id=$("#table_campanias").jqGrid("getGridParam",'selrow');		
	CampaniaDAO.DataById(id);
}
save_campania = function ( ) {
	var rs=validacion.check([
		{id:'txtCampania',required:true,isAlphaNumeric:true,errorRequiredFunction : function ( ) {
				$('#'+CampaniaDAO.idLayerMessage).html(templates.MsgError('Ingrese Nombre de Campa&ntilde;a','250px'));
				$('#'+CampaniaDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });	
			},errorAlphaNumericFunction : function ( ) {
				$('#'+CampaniaDAO.idLayerMessage).html(templates.MsgError('Ingrese solo texto y numero','250px'));
				$('#'+CampaniaDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });	
			} },
		{id:'txtFechaInicio',required:true,errorRequiredFunction : function ( ) {
				$('#'+CampaniaDAO.idLayerMessage).html(templates.MsgError('Ingrese Fecha de Inicio','250px'));
				$('#'+CampaniaDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });	
			} },
		{id:'txtFechaFin',required:true,errorRequiredFunction : function ( ) {
				$('#'+CampaniaDAO.idLayerMessage).html(templates.MsgError('Ingrese Fecha Fin','250px'));
				$('#'+CampaniaDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });	
			} },
		{id:'txtDescripcion',required:true,isAlphaNumeric:true,errorRequiredFunction : function ( ) {
				$('#'+CampaniaDAO.idLayerMessage).html(templates.MsgError('Ingrese Descripcion de Campa&ntilde;a','250px'));
				$('#'+CampaniaDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });	
			},errorAlphaNumericFunction : function ( ) {
				$('#'+CampaniaDAO.idLayerMessage).html(templates.MsgError('Ingrese solo texto y numero','250px'));
				$('#'+CampaniaDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });	
			} }
	]);
	
	
	if( rs ){
		var rsC=confirm('Verifique que los datos ingresados son los correctos');
		if( rsC ) {
			CampaniaDAO.Save();
		}
	}
}
update_campania = function ( ) {
	var id=$('#IdCampania').val();
	if( id=='' ){
		$('#'+CampaniaDAO.idLayerMessage).html(templates.MsgError('Seleccione campa&ntilde;a a actualizar','250px'));
		$('#'+CampaniaDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });	
		return false;	
	}
	var rs=validacion.check([
		{id:'txtCampania',required:true,isAlphaNumeric:true,errorRequiredFunction : function ( ) {
				$('#'+CampaniaDAO.idLayerMessage).html(templates.MsgError('Ingrese Nombre de Campa&ntilde;a','250px'));
				$('#'+CampaniaDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });	
			},errorAlphaNumericFunction : function ( ) {
				$('#'+CampaniaDAO.idLayerMessage).html(templates.MsgError('Ingrese solo texto y numero','250px'));
				$('#'+CampaniaDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });	
			} },
		{id:'txtFechaInicio',required:true,errorRequiredFunction : function ( ) {
				$('#'+CampaniaDAO.idLayerMessage).html(templates.MsgError('Ingrese Fecha de Inicio','250px'));
				$('#'+CampaniaDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });	
			} },
		{id:'txtFechaFin',required:true,errorRequiredFunction : function ( ) {
				$('#'+CampaniaDAO.idLayerMessage).html(templates.MsgError('Ingrese Fecha Fin','250px'));
				$('#'+CampaniaDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });	
			} },
		{id:'txtDescripcion',required:true,isAlphaNumeric:true,errorRequiredFunction : function ( ) {
				$('#'+CampaniaDAO.idLayerMessage).html(templates.MsgError('Ingrese Descripcion de Campa&ntilde;a','250px'));
				$('#'+CampaniaDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });	
			},errorAlphaNumericFunction : function ( ) {
				$('#'+CampaniaDAO.idLayerMessage).html(templates.MsgError('Ingrese solo texto y numero','250px'));
				$('#'+CampaniaDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });	
			} }
	]);
	
	if( rs ){
		var rsC=confirm('Verifique que los datos ingresados son los correctos');
		if( rsC ) {
			CampaniaDAO.Update();
		}
	}
}
delete_campania = function ( ) {
	var id=$('#IdCampania').val();
	if( id=='' ){
		$('#'+CampaniaDAO.idLayerMessage).html(templates.MsgError('Seleccione campa&ntilde;a a eliminar','250px'));
		$('#'+CampaniaDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });	
		return false;	
	}
	var rs=confirm('Desea eliminar campaña');
	if(rs){
		CampaniaDAO.Delete();
	}
}
cancel = function ( ) {
	var usuarioCreacion=$('#hdNomUsuario').val();
	$('#txtUsuarioCreacion').val(usuarioCreacion);
	$('#IdCampania').val('');
	$('#txtFechaInicio,#txtCampania,#txtFechaFin,#txtDescripcion').val('');
}
reloadJQGRIDCampania = function ( ) {
	$('#table_campanias').trigger('reloadGrid');	
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
		var rsC=confirm("Verifique que los datos ingresados son los correctos");
		if( rsC ){
			CampaniaDAO.insertUsuario();	
		}
	}
}


