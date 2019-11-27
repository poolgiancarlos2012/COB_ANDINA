login=function ( ) {
    var rs=validacion.check([
		{id:'txtUsuario',required:true,errorRequiredFunction:function( ){
				$('#'+indexDAO.idLayerMessage).html(templates.MsgError('Ingrese usuario','250px'));
				indexDAO.setTimeOut_hide_message();
			}},
		{id:'txtPsw',required:true,errorRequiredFunction:function( ){
				$('#'+indexDAO.idLayerMessage).html(templates.MsgError('Ingrese password','250px'));
				indexDAO.setTimeOut_hide_message();
			}},
		{id:'cbServicio',isNotValue:0,errorNotValueFunction:function( ){
				$('#'+indexDAO.idLayerMessage).html(templates.MsgError('Seleccioen servicio','250px'));
				indexDAO.setTimeOut_hide_message();
			}}
	]);
	
	if( rs ){
	    indexDAO.checkUser();
	}
}
login_admin=function ( ) {
    var rs=validacion.check([
		{id:'txtUsuario',required:true,errorRequiredFunction:function( ){
				$('#'+indexDAO.idLayerMessage).html(templates.MsgError('Ingrese usuario','250px'));
				indexDAO.setTimeOut_hide_message();
			}},
		{id:'txtPsw',required:true,errorRequiredFunction:function( ){
				$('#'+indexDAO.idLayerMessage).html(templates.MsgError('Ingrese password','250px'));
				indexDAO.setTimeOut_hide_message();
			}}
	]);
	
	if( rs ){
	    indexDAO.checkUserAdmin();
	}
}