var ControlGestionDAO = {
    url : '../controller/ControllerCobrast.php',
    Listar : {
				
        CarterasServicio : function ( xidservicio, f_success, f_before ) {
						
            $.ajax({
                url : ControlGestionDAO.url,
                type : 'GET',
                dataType : 'json',
                data : {
                    command : '', 
                    action : '', 
                    idservicio : xidservicio
                },
                beforeSend : function ( ) {
                    f_before();
                },
                success : function ( obj ) {
                    f_success(obj);
                },
                error : function ( ) {
										
                }
            });
						
        }
				
    },
    Update : {
				
        off_on : function ( xaction, xidcartera, xusuario_modificacion, f_success, f_before, f_error ) {
						
            $.ajax({
                url : ControlGestionDAO.url,
                type : 'POST',
                dataType : 'json',
                data : {
                    command : 'cartera', 
                    action : xaction, 
                    usuario_modificacion : xusuario_modificacion, 
                    idcartera : xidcartera
                },
                beforeSend : function ( ) {
                    f_before();
                },
                success : function ( obj ) {
                    f_success(obj);
                },
                error : function ( ) {
                    f_error();
                }
								
            })
						
        },
        _delete : function ( xidcartera , xusuario_modificacion, f_success, f_before, f_error ) {
						
            $.ajax({
                url : ControlGestionDAO.url,
                type : 'POST',
                dataType : 'json',
                data : {
                    command : 'cartera', 
                    action : 'delete', 
                    idcartera : xidcartera, 
                    usuario_modificacion : xusuario_modificacion
                },
                beforeSend : function ( ) {
                    f_before();
                },
                success : function ( obj ) {
                    f_success(obj);
                },
                error : function ( ) {
                    f_error();
                }
								
            })
						
        }
								
    }
		
}