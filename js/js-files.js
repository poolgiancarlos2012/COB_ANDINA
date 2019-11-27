$(document).ready(function( ){
	$('#update_directory_btn').button({icons:{primary:"ui-icon-refresh"}});
});
create_directory = function ( ) {

	var xnombre = $.trim( $('#txtNameDirectory').val() );
	var xnombre_servicio = $('#hdNomServicio').val();
	var xrouter = $('#router_directory').val();

	if( xnombre == '' ) {
		FilesDAO.Message.Error("Ingrese nombre de directorio");
		return false;
	}

	FilesDAO.doPost( 
		"files",
		"create_directory", 
		{ nombre : xnombre, nombre_servicio : xnombre_servicio, router : xrouter } , 
		function ( ) {}, 
		function ( obj )
			{
				if( obj.rst ) {
					refresh_directory();
				}else{
					FilesDAO.Message.Error(obj.msg);
				}
			}, 
		function ( )
			{
			FilesDAO.Message.Error("Error en ejecucion de proceso");
			}  
		);

}
upload_file = function ( ) {

	var xnombre_servicio = $('#hdNomServicio').val();
	var xrouter = $('#router_directory').val();

	var rs = confirm("Verifique si el archivo seleccionado es el correcto");

	if( rs ) {

		$('#_fl_upload_file_vf_').upload(
			FilesDAO.url,
			{
				command : "files",
				action : "upload_file",
				router : xrouter,
				filename : "_fl_upload_file_vf_",
				nombre_servicio : xnombre_servicio
			},
			function ( obj )
			{
				if( obj.rst ) {
					$('#_fl_upload_file_vf_').val('');
					FilesDAO.Message.Info(obj.msg);
					refresh_directory();
				}else{
					FilesDAO.Message.Error(obj.msg);
				}
			},
			'json'
		);
		
	}

}
download_file = function ( xfile ) {
	var router = escape( $('#router_directory').val() );
	var nombre_servicio = escape( $('#hdNomServicio').val() );
	var file = escape(xfile);
	window.open("download_file.php?file="+file+"&router="+router+"&servicio="+nombre_servicio,'_blank');
}
back_directory = function ( ) {
	var router = $('#router_directory').val();
	if( router != '/' ) {
		router = router.substring(0, router.lastIndexOf("/",router.lastIndexOf("/")-1) );
		FilesDAO.read_directory( router, "" );
		if( router == '/' ) {
			$('#layer_back_directory').css('display','none');
		}
	}else{
		$('#layer_back_directory').css('display','none');
	}
	
}
read_directory = function ( directorio ) {
	var router = $('#router_directory').val();
	FilesDAO.read_directory( router , directorio );
}
delete_directory = function ( xdirectorio ) {

	var xnombre_servicio = $('#hdNomServicio').val();	
	var xrouter = $('#router_directory').val();

	FilesDAO.doPost(
		"files",
		"delete_directory",
		{ nombre_servicio : xnombre_servicio, router : xrouter, directorio : xdirectorio },
		function ( ) {},
		function ( obj ) 
		{
			if( obj.rst ) {
				$('#table_directory li[title="'+xdirectorio+'"]').remove();
				FilesDAO.Message.Info(obj.msg);
			}else{
				FilesDAO.Message.Error(obj.msg);
			}
		},
		function ( ) {
			FilesDAO.Message.Error("Error en ejecucion de proceso");
		}
	);

}
refresh_directory = function ( ) {
	var router = $('#router_directory').val();
	router = router.substring(0,router.length-1);
	FilesDAO.read_directory( router , "" );
}
delete_file = function ( xfile ) {

	var xnombre_servicio = $('#hdNomServicio').val();
	var xrouter = $('#router_directory').val();

	FilesDAO.doPost(
		"files",
		"delete_file",
		{ nombre_servicio : xnombre_servicio, router : xrouter, file : xfile },
		function ( ) {},
		function ( obj ) 
			{
				if( obj.rst ) {
					$('#table_directory li[title="'+xfile+'"]').remove();
					FilesDAO.Message.Info(obj.msg);
				}else{
					FilesDAO.Message.Error(obj.msg);
				}
			},
		function ( ) {
			FilesDAO.Message.Error("Error en ejecucion de proceso");
		}
		);

}

