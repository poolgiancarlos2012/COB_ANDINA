var CargaCarteraDAO = {
    url:'../controller/ControllerCobrast.php',
    idLayerMessage : 'layerMessage',
    xTypeData:'json',
    header : new Array(), 
    headerPlanta : new Array(),
    headerDetalle : new Array(),
    LoadOrigen : function ( f_success ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListarOrigen'
            },
            success : function ( obj ) {
                f_success(obj);
            },
            error : function ( ) {
                $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError("Error en ejecucion de proceso",'300px'));
                CargaCarteraDAO.setTimeOut_hide_message();
            }
        });
    },
    LoadTipoTelefono : function ( f_success ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListarTipoTelefono'
            },
            success : function ( obj ) {
                f_success(obj);
            },
            error : function ( ) {
                $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError("Error en ejecucion de proceso",'300px'));
                CargaCarteraDAO.setTimeOut_hide_message();
            }
        });
    },
    CarterasServicio : function ( xidservicio, f_success ) {
				
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'GET',
            dataType : 'json',
            data : { 
                command : 'distribucion', 
                action : 'ListarCarterasServicio', 
                idservicio : xidservicio 
            },
            beforeSend : function ( ) { 
            },
            success : function ( obj ) {
                f_success(obj);
            },
            error : function ( ) {
                $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError("Error en ejecucion de proceso",'300px'));
                CargaCarteraDAO.setTimeOut_hide_message();
            }
        });
				
    },
	ListaTipificacionServicio : function ( xidservicio, f_success ) {
		//~ Vic I
		$.ajax({
			url : '../controller/ControllerCobrast.php',
			type : 'POST',
			dataType : 'json',
			data : { 
				command : 'carga-cartera', 
				action : 'listaTipificacionLlam', 
				idservicio : xidservicio 
			},
			beforeSend : function ( ) { 
			},
			success : function ( obj ) {
				f_success(obj);
			},
			error : function ( ) {
				$('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError("Error en ejecucion de proceso",'300px'));
				CargaCarteraDAO.setTimeOut_hide_message();
			}
		});
	},
    ListarCarteraPorCampania : function ( idCampania, f_fill, idCB ) {

            $.ajax({
                    url : CargaCarteraDAO.url,
                    type : 'GET',
                    dataType : 'json',
                    data : {command:'carga-cartera',action:'ListCarteraRpteRank',Campania:idCampania,Estado:'0,1'},
                    beforeSend : function ( ) {},
                    success : function ( obj ) {
                            f_fill(obj,idCB);
                        },
                    error : function ( ) {

                        }
                });

    },
    FillCarteraTB : function ( obj, idCb ) {
            var html='';
            var alto='0px';
            if(obj.length>0){alto='120px'}
            html+='<tr><td><div style="height:'+alto+';"><table border="0" cellspacing="0" cellpadding="0">';
            for( i=0;i<obj.length;i++ ) {
                html+='<tr>';
                    html+='<td align="center" class="ui-widget-header" style="width:20px;padding:2px 0px;">'+(i+1)+'</td>';
                    html+='<td align="center" class="ui-widget-content" style="width:200px;padding:2px 0px;">'+obj[i].nombre_cartera+'</td>';
                    html+='<td align="center" class="ui-widget-content" style="width:200px;padding:2px 0px;">'+obj[i].fecha_inicio+'</td>';
                    html+='<td align="center" class="ui-widget-content" style="width:200px;padding:2px 0px;">'+obj[i].fecha_fin+'</td>';
                    html+='<td align="center" class="ui-widget-content" style="width:20px;padding:2px 0px;"><input  type="checkbox" value="'+obj[i].idcartera+'"  ></td>';
                html+='</tr>';
            }
            html+='</table></div></td></tr>';
          
            $('#'+idCb).html(html);
    },
    DataTemplate : function ( xidcartera, f_success ) {
			
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command : 'carga-cartera', 
                action : 'DataTemplate', 
                idcartera : xidcartera
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                f_success(obj);
            },
            error : function ( ) {
                $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError("Error en ejecucion de proceso",'300px'));
                CargaCarteraDAO.setTimeOut_hide_message();
            }
        });
			
    },
    Upload : function ( ) {
    
        var files_cartera = new Array();
        $('#file_upload').fileUploadUI({
            url : CargaCarteraDAO.url,
            fieldName : 'uploadFileCartera',
            uploadTable: $('#files'),
            autoUpload : false,
            buildUploadRow: function (files, index, handler) {
                return $('<tr><td>' + files[index].name + '<\/td>' +
                    '<td class="file_upload_progress"><div><\/div><\/td>' +
                    '<td class="file_upload_cancel">' +
                    '<button class="ui-state-default ui-corner-all" title="Cancel">' +
                    '<span class="ui-icon ui-icon-cancel">Cancel<\/span>' +
                    '<\/button><\/td><\/tr>');
            },
            buildDownloadRow: function (file, handler) {
            //return $('<tr><td>' + file.msg + '<\/td><\/tr>');
            },
            onSend : function (event, files, index, xhr, handler) {
				
            },
            onCompleteAll : function (event, files, index, xhr, handler) {					
            
                $('#hddFile').val( files_cartera.join(":") );
                CargaCarteraDAO.loadHeaderFile(files_cartera[0]);
                
                $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo($('#loadHeaderErrorMsg').val(),'300px'));
            
            },
            onComplete : function (event, files, index, xhr, handler) {
                var obj;
                if (typeof xhr.responseText !== 'undef') {
                    obj = $.parseJSON(xhr.responseText);
                } else {
                    // Instead of an XHR object, an iframe is used for legacy browsers:
                    obj = $.parseJSON(xhr.contents().text());
                }
                if( obj.rst ){
                    //$('#hddFile').val($('#hddFile').val()+obj.file+':');
                    //CargaCarteraDAO.loadHeaderFile(obj.file);
					files_cartera = [];
                    files_cartera.push(obj.file);
                }else{
                    $('#loadHeaderErrorMsg').val(obj.msg);
                //$('#layerOverlay,#layerLoading').hide(); 
                //$('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                //CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            beforeSend : function (event, files, index, xhr, handler, callBack) {
                //alert(dump(files));
                //$('#layerOverlay,#layerLoading').css('display','block');
                $('#hddFile').val('');
                handler.formData = [
                        { name : 'command', value : 'carga-cartera' },
                        { name : 'action', value : 'upload' },
                        { name : 'Servicio', value : $('#hdCodServicio').val() },
                        { name : 'Campania', value : $('#cboCampania').val() },
                        { name : 'idCabecera', value : $('#cbCabecerasCarteraMain').val() },
                        { name : 'CaracterSeparador', value : $('#txtCaracterSeparador').val() },
                        { name : 'ModoCarga', value : $('#cboModoProceso').val() },
                        { name : 'UsuarioCreacion', value : $('#hdCodUsuario').val() },
                        { name : 'NombreServicio', value : $('#hdNomServicio').val() }
                ];
                callBack();
            },
        });	
    },
    UploadPagoMulti : function ( ) {
        var files_pago = new Array();
        $('#file_upload_pago').fileUploadUI({
            url : CargaCarteraDAO.url,
            fieldName : 'uploadFileCarteraPagoMain',
            uploadTable: $('#files_pago'),
            autoUpload : false,
            buildUploadRow: function (files, index, handler) {
                return $('<tr><td>' + files[index].name + '<\/td>' +
                    '<td class="file_upload_progress"><div><\/div><\/td>' +
                    '<td class="file_upload_cancel">' +
                    '<button class="ui-state-default ui-corner-all" title="Cancel">' +
                    '<span class="ui-icon ui-icon-cancel">Cancel<\/span>' +
                    '<\/button><\/td><\/tr>');
            },
            buildDownloadRow: function (file, handler) {
            //return $('<tr><td>' + file.msg + '<\/td><\/tr>');
            },
            onSend : function (event, files, index, xhr, handler) {

            },
            onCompleteAll : function (event, files, index, xhr, handler) {	
            
                CargaCarteraDAO.loadHeaderFilePago(files_pago[0]);
                $('#hddFilePago').val( files_pago.join(':') );
            
                $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo($('#loadHeaderErrorPagoMsg').val(),'300px'));
                 
            },
            onComplete : function (event, files, index, xhr, handler) {
                var obj;
                if (typeof xhr.responseText !== 'undef') {
                    obj = $.parseJSON(xhr.responseText);
                } else {
                    obj = $.parseJSON(xhr.contents().text());
                }
                if( obj.rst ){
                    //$('#hddFilePago').val($('#hddFilePago').val()+obj.file+':');
                    //CargaCarteraDAO.loadHeaderFilePago(obj.file);
                    files_pago.push(obj.file);
                }else{
                    $('#loadHeaderErrorPagoMsg').val(obj.msg);
                }
            },
            beforeSend : function (event, files, index, xhr, handler, callBack) {
                
                $('#hddFilePago').val('');
                handler.formData = [
                    { name : 'command', value : 'carga-cartera' },
                    { name : 'action', value : 'uploadPago' },
                    { name : 'Servicio', value : $('#hdCodServicio').val() },
                    { name : 'idTmpFile', value : 'uploadFileCarteraPagoMain' },
                    { name : 'Campania', value : $('#cboCampania').val() },
                    { name : 'idCabecera', value : $('#cbCabecerasCarteraPago').val() },
                    { name : 'CaracterSeparador', value : $('#cbCaracterSeparadorPago').val() },
                    { name : 'UsuarioCreacion', value : $('#hdCodUsuario').val() },
                    { name : 'NombreServicio', value : $('#hdNomServicio').val() }
                ];
                callBack();
            },
        });	
    },
    UploadNocPre : function ( ) {
    
        var files_nocpre = new Array();
    
        $('#file_uploadNocPre').fileUploadUI({
            url : '../controller/ControllerCobrast.php',
            fieldName : 'uploadFileCarteraNOC',
            uploadTable: $('#filesNocPre'),
            autoUpload : false,
            buildUploadRow: function (files, index, handler) {
                return $('<tr><td>' + files[index].name + '<\/td>' +
                    '<td class="file_upload_progress"><div><\/div><\/td>' +
                    '<td class="file_upload_cancel">' +
                    '<button class="ui-state-default ui-corner-all" title="Cancel">' +
                    '<span class="ui-icon ui-icon-cancel">Cancel<\/span>' +
                    '<\/button><\/td><\/tr>');
            },
            buildDownloadRow: function (file, handler) {
            },
            onSend : function (event, files, index, xhr, handler) {
            },
            onCompleteAll : function (event, files, index, xhr, handler) {		
            
                $('#hddFileNocPre').val( files_nocpre.join(':') );
            
                var html="<br><b>ARCHIVO(S) SUBIDO(S) CORRECTAMENTE</b><br><br><table><tr><td><button class='ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all' onClick='generateNOCpre_masivamente()' id='btnCargarNocPre' ><span class='ui-button-text'>INICIAR PROCESO DE CARGA</span></button></td><td><button class='ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all' onclick='cancel_carga_cartera_masiva(\"hddFileNocPre\",\"msg_resultado_masivo_nocpre\")'><span class='ui-button-text'>Cancelar</span></button></td></tr></table><br>";
                $('#msg_resultado_masivo_nocpre').html(html);
            },
            onComplete : function (event, files, index, xhr, handler) {
                var obj;
                if (typeof xhr.responseText !== 'undef') {
                    obj = $.parseJSON(xhr.responseText);
                } else {
                    // Instead of an XHR object, an iframe is used for legacy browsers:
                    obj = $.parseJSON(xhr.contents().text());
                }
                if( obj.rst ){
                    //$('#hddFileNocPre').val($('#hddFileNocPre').val()+obj.file+':');
                    files_nocpre.push(obj.file);
                }else{
                    $('#loadHeaderErrorMsg').val(obj.msg);
                }
            },
            beforeSend : function (event, files, index, xhr, handler, callBack) {
                $('#hddFileNocPre').val('');
                handler.formData = [
                {
                    name : 'command', 
                    value : 'carga-cartera'
                },

                {
                    name : 'action', 
                    value : 'uploadNocPre'
                },

                {
                    name : 'Servicio', 
                    value : $('#hdCodServicio').val()
                    },

                    {
                    name : 'UsuarioCreacion', 
                    value : $('#hdCodUsuario').val()
                    },

                    {
                    name : 'NombreServicio', 
                    value : $('#hdNomServicio').val()
                    },
                    {
                    name : 'CaracterSeparador', 
                    value : $('#cbCaracterSeparadorNOCPre').val()
                    }
                ];
                callBack();
            },
        });	
    },
    UploadCourier : function ( ) {

        var files_courier = new Array();

        $('#file_uploadCourier').fileUploadUI({
            url : '../controller/ControllerCobrast.php',
            fieldName : 'uploadFileCarteraCourier',
            uploadTable: $('#filesCourier'),
            autoUpload : false,
            buildUploadRow: function (files, index, handler) {
                return $('<tr><td>' + files[index].name + '<\/td>' +
                    '<td class="file_upload_progress"><div><\/div><\/td>' +
                    '<td class="file_upload_cancel">' +
                    '<button class="ui-state-default ui-corner-all" title="Cancel">' +
                    '<span class="ui-icon ui-icon-cancel">Cancel<\/span>' +
                    '<\/button><\/td><\/tr>');
            },
            buildDownloadRow: function (file, handler) {
            },
            onSend : function (event, files, index, xhr, handler) {
            },
            onCompleteAll : function (event, files, index, xhr, handler) {		

                $('#hddFileCourier').val( files_courier.join(':') );

                var html="<br><b>ARCHIVO(S) SUBIDO(S) CORRECTAMENTE</b><br><br><table><tr><td><button class='ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all' onClick='generateCourier_masivamente()' id='btnCargarCourier' ><span class='ui-button-text'>INICIAR PROCESO DE CARGA</span></button></td><td><button class='ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all' onclick='cancel_carga_cartera_masiva(\"hddFileCourier\",\"msg_resultado_masivo_courier\")'><span class='ui-button-text'>Cancelar</span></button></td></tr></table><br>";
                $('#msg_resultado_masivo_courier').html(html);
            },
            onComplete : function (event, files, index, xhr, handler) {
                var obj;
                if (typeof xhr.responseText !== 'undef') {
                    obj = $.parseJSON(xhr.responseText);
                } else {
                    // Instead of an XHR object, an iframe is used for legacy browsers:
                    obj = $.parseJSON(xhr.contents().text());
                }
                if( obj.rst ){
                    files_courier.length=0;
                    files_courier.push(obj.file);
                }else{
                    $('#loadHeaderErrorMsg').val(obj.msg);
                }
            },
            beforeSend : function (event, files, index, xhr, handler, callBack) {
                $('#hddFileCourier').val('');
                handler.formData = [
                                { name : 'command', value : 'carga-cartera' },
                                { name : 'action', value : 'uploadCourier' },
                                { name : 'Servicio', value : $('#hdCodServicio').val() },
                                { name : 'UsuarioCreacion', value : $('#hdCodUsuario').val() },
                                { name : 'NombreServicio', value : $('#hdNomServicio').val() },
                                { name : 'CaracterSeparador', value : $('#cbCaracterSeparadorCourier').val() }
                                ];
                callBack();
            },
        });	
    },
    UploadEstadoCuenta : function ( ) {

        var files_estado_cuenta = new Array();

        $('#file_uploadEstadoCuenta').fileUploadUI({
            url : '../controller/ControllerCobrast.php',
            fieldName : 'uploadFileCarteraEstadoCuenta',
            uploadTable: $('#filesEstadoCuenta'),
            autoUpload : false,
            buildUploadRow: function (files, index, handler) {
                return $('<tr><td>' + files[index].name + '<\/td>' +
                    '<td class="file_upload_progress"><div><\/div><\/td>' +
                    '<td class="file_upload_cancel">' +
                    '<button class="ui-state-default ui-corner-all" title="Cancel">' +
                    '<span class="ui-icon ui-icon-cancel">Cancel<\/span>' +
                    '<\/button><\/td><\/tr>');
            },
            buildDownloadRow: function (file, handler) {
            },
            onSend : function (event, files, index, xhr, handler) {
            },
            onCompleteAll : function (event, files, index, xhr, handler) {		

                $('#hddFileEstadoCuenta').val( files_estado_cuenta.join(':') );

                var html="<br><b>ARCHIVO(S) SUBIDO(S) CORRECTAMENTE</b><br><br><table><tr><td><button class='ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all' onClick='generateEstadoCuenta_masivamente()' id='btnCargarEstadoCuenta' ><span class='ui-button-text'>INICIAR PROCESO DE CARGA</span></button></td><td><button class='ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all' onclick='cancel_carga_cartera_masiva(\"hddFileEstadoCuenta\",\"msg_resultado_masivo_estado_cuenta\")'><span class='ui-button-text'>Cancelar</span></button></td></tr></table><br>";
                $('#msg_resultado_masivo_estado_cuenta').html(html);
            },
            onComplete : function (event, files, index, xhr, handler) {
                var obj;
                if (typeof xhr.responseText !== 'undef') {
                    obj = $.parseJSON(xhr.responseText);
                } else {
                    // Instead of an XHR object, an iframe is used for legacy browsers: 
                    obj = $.parseJSON(xhr.contents().text());
                }
                if( obj.rst ){
                    files_estado_cuenta.push(obj.file);
                }else{
                    $('#loadHeaderEstadoCuentaErrorMsg').val(obj.msg);
                }
            },
            beforeSend : function (event, files, index, xhr, handler, callBack) {
                $('#hddFileEstadoCuenta').val('');
                handler.formData = [
                                { name : 'command', value : 'carga-cartera' },
                                { name : 'action', value : 'uploadEstadoCuenta' },
                                { name : 'Servicio', value : $('#hdCodServicio').val() },
                                { name : 'UsuarioCreacion', value : $('#hdCodUsuario').val() },
                                { name : 'NombreServicio', value : $('#hdNomServicio').val() },
                                { name : 'CaracterSeparador', value : $('#cbCaracterSeparadorEstadoCuenta').val() }
                                ];
                callBack();
            },
        });	
    },
    UploadSaldoTotal : function ( ) {
        
        var files_saldo_total = new Array();

        $('#file_uploadSaldoTotal').fileUploadUI({
            url : '../controller/ControllerCobrast.php',
            fieldName : 'uploadFileCarteraSaldoTotal',
            uploadTable: $('#filesSaldoTotal'),
            autoUpload : false,
            buildUploadRow: function (files, index, handler) {
                return $('<tr><td>' + files[index].name + '<\/td>' +
                    '<td class="file_upload_progress"><div><\/div><\/td>' +
                    '<td class="file_upload_cancel">' +
                    '<button class="ui-state-default ui-corner-all" title="Cancel">' +
                    '<span class="ui-icon ui-icon-cancel">Cancel<\/span>' +
                    '<\/button><\/td><\/tr>');
            },
            buildDownloadRow: function (file, handler) {
            },
            onSend : function (event, files, index, xhr, handler) {
            },
            onCompleteAll : function (event, files, index, xhr, handler) {		

                $('#hddFileSaldoTotal').val( files_saldo_total.join(':') );

                var html="<br><b>ARCHIVO(S) SUBIDO(S) CORRECTAMENTE</b><br><br><table><tr><td><button class='ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all' onClick='generateSaldoTotal_masivamente()' id='btnCargarSaldoTotal' ><span class='ui-button-text'>INICIAR PROCESO DE CARGA</span></button></td><td><button class='ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all' onclick='cancel_carga_cartera_masiva(\"hddFileSaldoTotal\",\"msg_resultado_masivo_saldo_total\")'><span class='ui-button-text'>Cancelar</span></button></td></tr></table><br>";
                $('#msg_resultado_masivo_saldo_total').html(html);
            },
            onComplete : function (event, files, index, xhr, handler) {
                var obj;
                if (typeof xhr.responseText !== 'undef') {
                    obj = $.parseJSON(xhr.responseText);
                } else {
                    obj = $.parseJSON(xhr.contents().text());
                }
                if( obj.rst ){
                    files_saldo_total.push(obj.file);
                }else{
                    $('#loadHeaderSaldoTotalErrorMsg').val(obj.msg);
                }
            },
            beforeSend : function (event, files, index, xhr, handler, callBack) {
                $('#hddFileSaldoTotal').val('');
                handler.formData = [
                                { name : 'command', value : 'carga-cartera' },
                                { name : 'action', value : 'uploadSaldoTotal' },
                                { name : 'Servicio', value : $('#hdCodServicio').val() },
                                { name : 'UsuarioCreacion', value : $('#hdCodUsuario').val() },
                                { name : 'NombreServicio', value : $('#hdNomServicio').val() },
                                { name : 'CaracterSeparador', value : $('#cbCaracterSeparadorSaldoTotal').val() }
                                ];
                callBack();
            },
        });	
        
    },
    UploadDetalleM : function ( ) {

        var files_detalleM = new Array();

        $('#file_uploadDetalleM').fileUploadUI({
            url : '../controller/ControllerCobrast.php',
            fieldName : 'uploadFileCarteraDetalleM',
            uploadTable: $('#filesDetalleM'),
            autoUpload : false,
            buildUploadRow: function (files, index, handler) {
                return $('<tr><td>' + files[index].name + '<\/td>' +
                    '<td class="file_upload_progress"><div><\/div><\/td>' +
                    '<td class="file_upload_cancel">' +
                    '<button class="ui-state-default ui-corner-all" title="Cancel">' +
                    '<span class="ui-icon ui-icon-cancel">Cancel<\/span>' +
                    '<\/button><\/td><\/tr>');
            },
            buildDownloadRow: function (file, handler) {
            },
            onSend : function (event, files, index, xhr, handler) {
            },
            onCompleteAll : function (event, files, index, xhr, handler) {		

                $('#hddFileDetalleM').val( files_detalleM.join(':') );

                var html="<br><b>ARCHIVO(S) SUBIDO(S) CORRECTAMENTE</b><br><br><table><tr><td><button class='ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all' onClick='generateDetalleM_masivamente()' id='btnCargarDetalleM' ><span class='ui-button-text'>INICIAR PROCESO DE CARGA</span></button></td><td><button class='ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all' onclick='cancel_carga_cartera_masiva(\"hddFileDetalleM\",\"msg_resultado_masivo_detalle_m\")'><span class='ui-button-text'>Cancelar</span></button></td></tr></table><br>";
                $('#msg_resultado_masivo_detalle_m').html(html);
            },
            onComplete : function (event, files, index, xhr, handler) {
                var obj;
                if (typeof xhr.responseText !== 'undef') {
                    obj = $.parseJSON(xhr.responseText);
                } else {
                    obj = $.parseJSON(xhr.contents().text());
                }
                if( obj.rst ){
                    files_detalleM.push(obj.file);
                }else{
                    $('#loadHeaderDetalleMErrorMsg').val(obj.msg);
                }
            },
            beforeSend : function (event, files, index, xhr, handler, callBack) {
                $('#hddFileDetalleM').val('');
                handler.formData = [
                                { name : 'command', value : 'carga-cartera' },
                                { name : 'action', value : 'uploadDetalleM' },
                                { name : 'Servicio', value : $('#hdCodServicio').val() },
                                { name : 'UsuarioCreacion', value : $('#hdCodUsuario').val() },
                                { name : 'NombreServicio', value : $('#hdNomServicio').val() },
                                { name : 'CaracterSeparador', value : $('#cbCaracterSeparadorDetalleM').val() }
                                ];
                callBack();
            },
        });	

    },
    UploadPago : function ( ) {
        $('#layerOverlay,#layerLoading').css('display','block');
        $('#uploadFileCarteraPagoMain').upload(
            '../controller/ControllerCobrast.php',
            {
                command : 'carga-cartera',
                action : 'uploadPago',
                idTmpFile : 'uploadFileCarteraPagoMain',
                Servicio : $('#hdCodServicio').val(),
                CaracterSeparador : $('#cbCaracterSeparadorPago').val(),
                idCabecera : $('#cbCabecerasCarteraPago').val(),
                UsuarioCreacion : $('#hdCodUsuario').val(),
                NombreServicio : $('#hdNomServicio').val()
            },
            function(obj){
                if( obj.rst ){
                    $('#hddFilePago').val(obj.file);
                    CargaCarteraDAO.loadHeaderFilePago(obj.file);
                }else{
                    $('#layerOverlay,#layerLoading').hide(); 
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            'json'
            );
    },
    UploadRetiroMasivo : function ( ) {
        var files_retiros = new Array();
        $('#file_uploadRetiro').fileUploadUI({
            url : '../controller/ControllerCobrast.php',
            fieldName : 'uploadFileCarteraRetiro',
            uploadTable: $('#filesRetiro'),
            autoUpload : false,
            buildUploadRow: function (files, index, handler) {
                return $('<tr><td>' + files[index].name + '<\/td>' +
                    '<td class="file_upload_progress"><div><\/div><\/td>' +
                    '<td class="file_upload_cancel">' +
                    '<button class="ui-state-default ui-corner-all" title="Cancel">' +
                    '<span class="ui-icon ui-icon-cancel">Cancel<\/span>' +
                    '<\/button><\/td><\/tr>');
            },
            buildDownloadRow: function (file, handler) {
            },
            onSend : function (event, files, index, xhr, handler) {
            },
            onCompleteAll : function (event, files, index, xhr, handler) {		
            
                $('#hddFileRetiro').val( files_retiros.join(':') );
                
                var html="<br><b>ARCHIVO(S) SUBIDO(S) CORRECTAMENTE</b><br><br><table><tr><td><button class='ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all' onClick='generateRetiro_masivamente()' id='btnCargarNocPre' ><span class='ui-button-text'>INICIAR PROCESO DE CARGA</span></button></td><td><button class='ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all' onclick='cancel_carga_cartera_masiva(\"hddFileRetiro\",\"msg_resultado_masivo_retiro\")'><span class='ui-button-text'>Cancelar</span></button></td></tr></table><br>";
                $('#msg_resultado_masivo_retiro').html(html);
            },
            onComplete : function (event, files, index, xhr, handler) {
                var obj;
                if (typeof xhr.responseText !== 'undef') {
                    obj = $.parseJSON(xhr.responseText);
                } else {
                    // Instead of an XHR object, an iframe is used for legacy browsers:
                    obj = $.parseJSON(xhr.contents().text());
                }
                if( obj.rst ){
                    //$('#hddFileRetiro').val($('#hddFileRetiro').val()+obj.file+':');
                    files_retiros.push( obj.file );
                }else{
                    $('#loadHeaderErrorMsg').val(obj.msg);
                }
            },
            beforeSend : function (event, files, index, xhr, handler, callBack) {
                $('#hddFileRetiro').val('');
                handler.formData = [
                        { name : 'command', value : 'carga-cartera' },
                        { name : 'action', value : 'uploadRetiro' },
                        { name : 'Servicio', value : $('#hdCodServicio').val() },
                        { name : 'UsuarioCreacion', value : $('#hdCodUsuario').val() },
                        { name : 'NombreServicio', value : $('#hdNomServicio').val() },
                        { name : 'CaracterSeparador', value : $('#cbCaracterSeparadorRetiro').val() }
                        ];
                callBack();
            },
        });	
    },
    UploadCorteFocalizado : function ( ) {
        $('#file_uploadCorteFocalizado').fileUploadUI({
            url : '../controller/ControllerCobrast.php',
            fieldName : 'uploadFileCorteFocalizado',
            uploadTable: $('#filesCorteFocalizado'),
            autoUpload : false,
            buildUploadRow: function (files, index, handler) {
                return $('<tr><td>' + files[index].name + '<\/td>' +
                    '<td class="file_upload_progress"><div><\/div><\/td>' +
                    '<td class="file_upload_cancel">' +
                    '<button class="ui-state-default ui-corner-all" title="Cancel">' +
                    '<span class="ui-icon ui-icon-cancel">Cancel<\/span>' +
                    '<\/button><\/td><\/tr>');
            },
            buildDownloadRow: function (file, handler) {
            },
            onSend : function (event, files, index, xhr, handler) {
            },
            onCompleteAll : function (event, files, index, xhr, handler) {
                var html;
                if($('#loadHeaderError').val() == '1')
                {
                    html="<br><b>"+$('#loadHeaderErrorMsg').val()+"<br><br>";
                }else{
                    html="<br><b>ARCHIVO(S) SUBIDO(S) CORRECTAMENTE</b><br><br><table><tr><td><button class='ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all' onClick='actuliazar_corte_focalizado()' id='btnCargarNocPre' ><span class='ui-button-text'>INICIAR PROCESO DE CARGA</span></button></td><td><button class='ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all' onclick='cancel_carga_cartera_masiva(\"hddFileRetiro\",\"msg_resultado_masivo_retiro\")'><span class='ui-button-text'>Cancelar</span></button></td></tr></table><br>";
                }		
                $('#msg_resultado_corte_focalizado').html(html);
            },
            onComplete : function (event, files, index, xhr, handler) {
                var obj;
                if (typeof xhr.responseText !== 'undef') {
                    obj = $.parseJSON(xhr.responseText);
                } else {
                    // Instead of an XHR object, an iframe is used for legacy browsers:
                    obj = $.parseJSON(xhr.contents().text());
                }
                if( obj.rst ){
                    $('#hddFileCorteFocalizado').val($('#hddFileCorteFocalizado').val()+obj.file+':');
                }else{
                    $('#loadHeaderError').val('1');
                    $('#loadHeaderErrorMsg').val(obj.msg);
                }
            },
            beforeSend : function (event, files, index, xhr, handler, callBack) {
                $('#hddFileRetiro').val('');
                handler.formData = [
                    {
                        name : 'command', 
                        value : 'carga-cartera'
                    },

                    {
                        name : 'action', 
                        value : 'uploadCorteFocalizado'
                    },

                    {
                        name : 'Servicio', 
                        value : $('#hdCodServicio').val()
                    },

                    {
                        name : 'UsuarioCreacion', 
                        value : $('#hdCodUsuario').val()
                    },

                    {
                        name : 'NombreServicio', 
                        value : $('#hdNomServicio').val()
                    },
                    {
                        name : 'CaracterSeparador',
                        value : $('#cbCaracterSeparadorCorteFocalizado').val()
                    }
                ];
                callBack();
            }
        });	
    },
    UploadFacturacion : function ( ) {
        $('#file_uploadFacturacion').fileUploadUI({
            url : '../controller/ControllerCobrast.php',
            fieldName : 'uploadFileFacturacion',
            uploadTable: $('#filesFacturacion'),
            autoUpload : false,
            buildUploadRow: function (files, index, handler) {
                return $('<tr><td>' + files[index].name + '<\/td>' +
                    '<td class="file_upload_progress"><div><\/div><\/td>' +
                    '<td class="file_upload_cancel">' +
                    '<button class="ui-state-default ui-corner-all" title="Cancel">' +
                    '<span class="ui-icon ui-icon-cancel">Cancel<\/span>' +
                    '<\/button><\/td><\/tr>');
            },
            buildDownloadRow: function (file, handler) {
            },
            onSend : function (event, files, index, xhr, handler) {
            },
            onCompleteAll : function (event, files, index, xhr, handler) {
                var html;
                if($('#loadHeaderError').val() == '1')
                {
                    html="<br><b>"+$('#loadHeaderErrorMsg').val()+"<br><br>";
                }else{
                    html="<br><b>ARCHIVO PROCESADO CORRECTAMENTE</b><br><br><table><tr><td><button class='ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all' onClick='downloadFileFacturacion()' id='btnCargarNocPre' ><span class='ui-button-text'>Descargar Archivo</span></button></td><td><button class='ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all' onclick='cancelarFacturacion()'><span class='ui-button-text'>Cancelar</span></button></td></tr></table><br>";
                }		
                $('#msg_resultado_facturacion').html(html);
            },
            onComplete : function (event, files, index, xhr, handler) {
                var obj;
                if (typeof xhr.responseText !== 'undef') {
                    obj = $.parseJSON(xhr.responseText);
                } else {
                    // Instead of an XHR object, an iframe is used for legacy browsers:
                    obj = $.parseJSON(xhr.contents().text());
                }
                if( obj.rst ){
                    $('#hddFileFacturacion').val($('#hddFileFacturacion').val()+obj.tabla);
                }else{
                    $('#loadHeaderError').val('1');
                    $('#loadHeaderErrorMsg').val(obj.msg);
                }
            },
            beforeSend : function (event, files, index, xhr, handler, callBack) {
                $('#hddFileRetiro').val('');
                handler.formData = [
                    {
                        name : 'command', 
                        value : 'carga-cartera'
                    },

                    {
                        name : 'action', 
                        value : 'uploadFacturacion'
                    },

                    {
                        name : 'Servicio', 
                        value : $('#hdCodServicio').val()
                    },

                    {
                        name : 'UsuarioCreacion', 
                        value : $('#hdCodUsuario').val()
                    },

                    {
                        name : 'NombreServicio', 
                        value : $('#hdNomServicio').val()
                    },
                    {
                        name : 'CaracterSeparador',
                        value : $('#cbCaracterSeparadorFacturacion').val()
                    }
                ];
                callBack();
            }
        });	
    },
	insertar_new_fonos : function () {
		//~ Vic I
		var newFonos = $("#nroAgregarFonoGestion").val();
		$('#layerOverlay,#layerLoading').css('display','block');
		$.ajax({
			url : '../controller/ControllerCobrast.php',
			dataType : 'json',
			type : 'POST',
			data : 'command=carga-cartera&action=newTelefonosManual&Servicio='+$('#hdCodServicio').val()+'&NombreServicio='+$('#hdNomServicio').val()+'&fonos='+newFonos,
			beforeSend : function () {
				$(".msgInsertarNewTelefonos").html('');
			},
			success : function(rpt){
				$(".msgInsertarNewTelefonos").html(rpt.mensaje + " <label class='text-alert'>Por favor Realizar Cruce de Llamadas, Nuevamente!</label>");
				$('#layerOverlay,#layerLoading').hide();
			}
		});
	},
	insertarLlamadasManual : function () {
		var carteraLlam = $(".sltCarteraLlama").val();
        var tipollamada=$('#slcttipollamada').val();
		//~ var estadoLlam = $(".sltTipificacionLlama").val();
		if (carteraLlam==0)
		{
			alert("Seleccionar una Cartera");
			return false;
		}
		//~ if (estadoLlam==0)
		//~ {
			//~ alert("Seleccionar un Estado");
			//~ return false;
		//~ }
		//~ data : 'command=carga-cartera&action=newInsertLlamadasManual&Servicio='+$('#hdCodServicio').val()+'&NombreServicio='+$('#hdNomServicio').val()+'&cartera='+carteraLlam+'&estado='+estadoLlam,
		$('#layerOverlay,#layerLoading').css('display','block');
		$.ajax({
			url : '../controller/ControllerCobrast.php',
			dataType : 'json',
			type : 'POST',
			data : 'command=carga-cartera&action=newInsertLlamadasManual&Servicio='+$('#hdCodServicio').val()+'&NombreServicio='+$('#hdNomServicio').val()+'&cartera='+carteraLlam+'&tipo_llamada='+tipollamada,
			beforeSend : function () {
				$(".msgInsertarLlamadasManuales").html('');
			},
			success : function(rpt){
				$(".msgInsertarLlamadasManuales").html("<div class='text-alert'>" + rpt.mensaje + "</div>");
				$('#layerOverlay,#layerLoading').hide();
			}
		});
	},
	cruce_llamadas : function () {
		var carteraLlam = $(".sltCarteraLlama").val();
        var tipollamada=$('#slcttipollamada').val();
		//~ var estadoLlam = $(".sltTipificacionLlama").val();
		if (carteraLlam==0)
		{
			alert("Seleccionar una Cartera");
			return false;
		}
		//~ if (estadoLlam==0)
		//~ {
			//~ alert("Seleccionar un Estado");
			//~ return false;
		//~ }
		//~ data : 'command=carga-cartera&action=cruceLlamada&Servicio='+$('#hdCodServicio').val()+'&NombreServicio='+$('#hdNomServicio').val()+'&cartera='+carteraLlam+'&estado='+estadoLlam,
		$('#layerOverlay,#layerLoading').css('display','block');
		$.ajax({
			url : '../controller/ControllerCobrast.php',
			dataType : 'json',
			type : 'POST',
			data : 'command=carga-cartera&action=cruceLlamada&Servicio='+$('#hdCodServicio').val()+'&NombreServicio='+$('#hdNomServicio').val()+'&cartera='+carteraLlam+'&tipo_llamada='+tipollamada,
			beforeSend : function () {
				$(".msgCruceLlamada").html('');
				$('.divMsgCruceLlamadas').show("slow");
				$('.divBotonAgregarFono, .divBotonInsertar').hide("slow");
			},
			success : function(rpt){
				if (rpt.verBoton=='INSERTAR_LLAMA') {
					$('.divBotonInsertar').show("slow");
				}
				else if(rpt.verBoton=='AGREGAR_FONO') {
					$('.divBotonAgregarFono').show("slow");
					$('#nroAgregarFonoGestion').val(rpt.nroTelefonos);
				}
				$(".msgCruceLlamada").html(rpt.mensaje);
				$('#layerOverlay,#layerLoading').hide();
			}
		});
	},
	file_cargar_llamadas : function () {
		$(".msgCargarLlamada, .msgCruceLlamada").html('');
		$('#layerOverlay,#layerLoading').css('display','block');
		$('#uploadFileInsertarLlamada').upload('../controller/ControllerCobrast.php',
			{
				command:'carga-cartera',
				action:'uploadInsertarLlamada',
				idTmpFile:'uploadFileInsertarLlamada',
				Servicio:$('#hdCodServicio').val(),
				UsuarioCreacion:$('#hdCodUsuario').val(),
				NombreServicio:$('#hdNomServicio').val()
			},
			function(obj){
				if( obj.rst ){
					$('.divCruceLlamadas').show("slow");
					$(".sltCarteraLlama option[value='0'], .sltTipificacionLlama option[value='0']").attr("selected",true);
					$('.divMsgCruceLlamadas, .divBotonInsertar, .divBotonAgregarFono').hide("slow");
				}else{
					$('.divCruceLlamadas, .divMsgCruceLlamadas').hide("slow");
				}
				$(".msgCargarLlamada").html("<div class='text-alert'>" + obj.msg + "</div>");
				$('#layerOverlay,#layerLoading').hide();
				$(".msgCruceLlamada").html("");
			},
			'json');
	},
    file_cargar_cuota : function () {
        $('#layerOverlay,#layerLoading').css('display','block');
        $('#uploadFileCargaCuota').upload('../controller/ControllerCobrast.php',
            {
                command:'carga-cartera',
                action:'uploadCargaCuota',
                Servicio:$('#hdCodServicio').val(),
                UsuarioCreacion:$('#hdCodUsuario').val(),
                NombreServicio:$('#hdNomServicio').val(),
                idcartera:$('#cboCarteraCargaCuota').val()
            },
            function(obj){
				//~ console.log(obj);
				$("#msgCargaCuota").html("");
				//~ if( obj.rst ){
					//~ $('#showbtncargarcuota').css('display','block');
				//~ }else{
					//~ $('#showbtncargarcuota').css('display','none');
				//~ }
				$("#msgCargaCuota").html("<div class='text-alert'>" + obj.msg + "</div>");
				$('#layerOverlay,#layerLoading').hide();
            },
            'json');
    },
	file_cliente_contrato_new : function () {
		$('#layerOverlay,#layerLoading').css('display','block');
		$(".msgJoinCliente, .msgJoinContrato").html('');
		$('.divExportarCarteraJoin, .divTxtDescarga').hide("slow");

		$('#uploadFileCargaClienteNew').upload('../controller/ControllerCobrast.php',{
			command:'carga-cartera',
			action:'uploadJoinClientes',
			Servicio:$('#hdCodServicio').val(),
			UsuarioCreacion:$('#hdCodUsuario').val(),
			NombreServicio:$('#hdNomServicio').val()
		}, function(obj){
			console.log(obj);
			$("#txtJoinClienteRspta").val(obj.valor);
			$("#txtJoinClienteTime").val(obj.tiempo);
			$(".msgJoinCliente").html(obj.msg);
		},'json');

		$('#uploadFileCargaContratoNew').upload('../controller/ControllerCobrast.php',{
			command:'carga-cartera',
			action:'uploadJoinContratos',
			Servicio:$('#hdCodServicio').val(),
			UsuarioCreacion:$('#hdCodUsuario').val(),
			NombreServicio:$('#hdNomServicio').val()
		}, function(obj){
			console.log(obj);
			$("#txtJoinContratoRspta").val(obj.valor);
			$("#txtJoinContratoTime").val(obj.tiempo);
			$(".msgJoinContrato").html(obj.msg);
		},'json');

		$('.divExportarCarteraJoin').show("slow");
		$('#layerOverlay,#layerLoading').hide();
	},
	file_fiadores_txt : function () {
		$('#layerOverlay,#layerLoading').css('display','block');
		$(".msgFiadores").html("");
		$('#uploadFileFiadores').upload('../controller/ControllerCobrast.php',
			{
				command:'carga-cartera',
				action:'uploadFiadores',
				Servicio:$('#hdCodServicio').val(),
				UsuarioCreacion:$('#hdCodUsuario').val(),
				NombreServicio:$('#hdNomServicio').val(),
				idcartera:$('#cboCarteraFiadores').val()
			},
			function(obj){
				$(".msgFiadores").html("<div class='text-alert'>" + obj.msg + "</div>");
				$('#layerOverlay,#layerLoading').hide();
			},'json');
	},
    file_carga_facturacion :function(){
        $('#layerOverlay,#layerLoading').css('display','block');
        $('#uploadFileCarteraCargaFacturacion').upload('../controller/ControllerCobrast.php',
            {
                command:'carga-cartera',
                action:'uploadCargaFacturacion',
                NombreServicio:$('#hdNomServicio').val(),                
                Servicio:$('#hdCodServicio').val()
            },function(obj){

                if( obj.rst ){
                    $('#tmpArchivoCargaFacturacion').val(obj.file);
                    $('#btnCargaFacturacion').css('display','block');
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();                    
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }   
                $('#layerOverlay,#layerLoading').hide();             

            },'json');
    },
    file_carga_provision :function(){
        $('#layerOverlay,#layerLoading').css('display','block');
        $('#uploadFileCarteraCargaProvision').upload('../controller/ControllerCobrast.php',
            {
                command:'carga-cartera',
                action:'uploadCargaProvision',
                NombreServicio:$('#hdNomServicio').val(),                
                Servicio:$('#hdCodServicio').val()
            },function(obj){

                if( obj.rst ){
                    $('#tmpArchivoCargaProvision').val(obj.file);
                    $('#btnCargaProvision').css('display','block');
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();                    
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }   
                $('#layerOverlay,#layerLoading').hide();             

            },'json');
    },    
    generateCargaFacturacion : function(){
        $.ajax({
            url:'../controller/ControllerCobrast.php',
            dataType:'json',
            type:'POST',
            data:{
                command     :   'carga-cartera',
                action      :   'generateCargaFacturacion',
                Servicio    :   $('#hdCodServicio').val(),
                NombreServicio: $('#hdNomServicio').val(),
                UsuarioCreacion:$('#hdCodUsuario').val(),
                archivo     :   $('#tmpArchivoCargaFacturacion').val(),
                idcartera   :   $('#tbCarterasCargaFacturacion').find(':checked').map(function(){return $(this).val()}).get().join(",")
            },
            beforeSend:function(){
                $('#layerOverlay,#layerLoading').css('display','block');
            },
            success:function(obj){
                $('#layerOverlay,#layerLoading').css('display','none');
                if( obj.rst ){
                    $('#tmpArchivoCargaFacturacion').val('');
                    $('#btnCargaFacturacion').css('display','none');
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();                    
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }   
            },
            error:function(){}
        })
    },
    generateCargaProvision : function(){
        $.ajax({
            url:'../controller/ControllerCobrast.php',
            dataType:'json',
            type:'POST',
            data:{
                command     :   'carga-cartera',
                action      :   'generateCargaProvision',
                Servicio    :   $('#hdCodServicio').val(),
                NombreServicio: $('#hdNomServicio').val(),
                UsuarioCreacion:$('#hdCodUsuario').val(),
                archivo     :   $('#tmpArchivoCargaProvision').val(),
                idcartera   :   $('#tbCarterasCargaProvision').find(':checked').map(function(){return $(this).val()}).get().join(",")
            },
            beforeSend:function(){
                $('#layerOverlay,#layerLoading').css('display','block');
            },
            success:function(obj){
                $('#layerOverlay,#layerLoading').css('display','none');
                if( obj.rst ){
                    $('#tmpArchivoCargaProvision').val('');
                    $('#uploadFileCarteraCargaProvision').val('');
                    $('#btnCargaProvision').css('display','none');
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();                    
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }   
            },
            error:function(){}
        })
    },    
	btn_generar_join_carteras : function (xtimeCliente, xtimeContrato) {
		$('#layerOverlay,#layerLoading').css('display','block');

		$.ajax({
			url : '../controller/ControllerCobrast.php',
			dataType : 'json',
			type : 'POST',
			data : 'command=carga-cartera&action=txtJoinCarteras&Servicio='+$('#hdCodServicio').val()+'&NombreServicio='+$('#hdNomServicio').val()+'&UsuarioCreacion='+$('#hdCodUsuario').val()+'&timeCli='+xtimeCliente+'&timeCon='+xtimeContrato,
			beforeSend : function () {
				$(".divTxtDescarga").html('Cargando...');
			},
			success : function(rpt){
				if (rpt.rst){
					var htmls = "<a href='../documents/cartera_unir/BBVA/cartera_"+rpt.tiempo+".zip'>DESCARGAR ARCHIVO</a>";
					$(".divTxtDescarga").html(htmls);
				} else {
					$(".divTxtDescarga").html(rpt.msg);
				}
				$(".divTxtDescarga").show('slow');
			}
		});
		$('#layerOverlay,#layerLoading').hide();

	},
    Update_corte_focalizado : function(files)
    {
        $.ajax(
        {
            url : '../controller/ControllerCobrast.php',
            dataType : 'json',
            type : 'POST',
            data : 'command=carga-cartera&action=actualizarCorteFocalizado&files='+files+'&servicio='+$('#hdNomServicio').val(),
            success : function(rpt)
            {
                var resumen = '';
                alert(rpt.msg);
                if(!rpt.rst)
                {
                    $.each(
                        rpt.resumen,
                        function(index,value)
                        {
                            resumen = rpt.resumen[index];
                        }
                        );
                    alert(resumen);
                }
            }
        }
        );
    },
    UploadIVRMasivo : function ( ) {
    
        var files_ivr = new Array();
    
        $('#file_uploadIVR').fileUploadUI({
            url : '../controller/ControllerCobrast.php',
            fieldName : 'uploadFileCarteraIVR',
            uploadTable: $('#filesIVR'),
            autoUpload : false,
            buildUploadRow: function (files, index, handler) {
                return $('<tr><td>' + files[index].name + '<\/td>' +
                    '<td class="file_upload_progress"><div><\/div><\/td>' +
                    '<td class="file_upload_cancel">' +
                    '<button class="ui-state-default ui-corner-all" title="Cancel">' +
                    '<span class="ui-icon ui-icon-cancel">Cancel<\/span>' +
                    '<\/button><\/td><\/tr>');
            },
            buildDownloadRow: function (file, handler) {
            },
            onSend : function (event, files, index, xhr, handler) {
            },
            onCompleteAll : function (event, files, index, xhr, handler) {		
            
                $('#hddFileIVR').val(files_ivr.join(':'));
            
                var html="<br><b>ARCHIVO(S) SUBIDO(S) CORRECTAMENTE</b><br><br><table><tr><td><button class='ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all' onClick='generateIVR_masivamente()' id='btnCargarIVR' ><span class='ui-button-text'>INICIAR PROCESO DE CARGA</span></button></td><td><button class='ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all' onclick='cancel_carga_cartera_masiva(\"hddFileIVR\",\"msg_resultado_masivo_IVR\")'><span class='ui-button-text'>Cancelar</span></button></td></tr></table><br>";
                $('#msg_resultado_masivo_IVR').html(html);
            },
            onComplete : function (event, files, index, xhr, handler) {
                var obj;
                if (typeof xhr.responseText !== 'undef') {
                    obj = $.parseJSON(xhr.responseText);
                } else {
                    // Instead of an XHR object, an iframe is used for legacy browsers:
                    obj = $.parseJSON(xhr.contents().text());
                }
                if( obj.rst ){
                    //$('#hddFileIVR').val($('#hddFileIVR').val()+obj.file+':');
                    files_ivr.push(obj.file);
                }else{
                    $('#loadHeaderErrorMsg').val(obj.msg);
                }
            },
            beforeSend : function (event, files, index, xhr, handler, callBack) {
                $('#hddFileRetiro').val('');
                handler.formData = [
                {
                    name : 'command', 
                    value : 'carga-cartera'
                },

                {
                    name : 'action', 
                    value : 'uploadIVR'
                },

                {
                    name : 'Servicio', 
                    value : $('#hdCodServicio').val()
                    },

                    {
                    name : 'UsuarioCreacion', 
                    value : $('#hdCodUsuario').val()
                    },

                    {
                    name : 'NombreServicio', 
                    value : $('#hdNomServicio').val()
                    },
                    {
                    name : 'CaracterSeparador',
                    value : $('#cbCaracterSeparadorIVR').val()
                    }
                ];
                callBack();
            },
        });	
    },
    UploadRetiro : function ( ) {
        $('#layerOverlay,#layerLoading').css('display','block');
        $('#uploadFileCarteraPago').upload(
            '../controller/ControllerCobrast.php',
            {
                command:'carga-cartera',
                action:'uploadRetiro',
                idTmpFile:'uploadFileCarteraRetiro',
                Servicio:$('#hdCodServicio').val(),
                UsuarioCreacion:$('#hdCodUsuario').val(),
                NombreServicio:$('#hdNomServicio').val(),
                CaracterSeparador : $('#cbCaracterSeparadorRetiro').val()
            },
            function(obj){
                if( obj.rst ){
                    $('#hdFileRetiro').val(obj.file);
                    CargaCarteraDAO.loadHeaderFileRetiro(obj.file);
                }else{
                    $('#layerOverlay,#layerLoading').hide(); 
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            'json'
            );
    },
    UploadCentroPago : function ( ) {
        $('#layerOverlay,#layerLoading').css('display','block');
        $('#uploadFileCarteraCentroPago').upload(
            '../controller/ControllerCobrast.php',
            {
                command:'carga-cartera',
                action:'uploadCentroPago',
                idTmpFile:'uploadFileCarteraCentroPago',
                Servicio:$('#hdCodServicio').val(),
                UsuarioCreacion:$('#hdCodUsuario').val(),
                NombreServicio:$('#hdNomServicio').val(),
                CaracterSeparador : $('#cbCaracterSeparadorCentroPago').val()
            },
            function(obj){
                if( obj.rst ){
                    $('#hdFileCentroPago').val(obj.file);
                    CargaCarteraDAO.loadHeaderFileCentroPago(obj.file);
                }else{
                    $('#layerOverlay,#layerLoading').hide(); 
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            'json'
            );
    },
    UploadCarteraPlanta : function ( ) {
        $('#layerOverlay,#layerLoading').css('display','block');
        $('#uploadFileCarteraPlanta').upload(
            '../controller/ControllerCobrast.php',
            {
                command:'carga-cartera',
                action:'uploadCarteraPlanta',
                Servicio:$('#hdCodServicio').val(),
                UsuarioCreacion:$('#hdCodUsuario').val(),
                NombreServicio:$('#hdNomServicio').val()
            },
            function(obj){
                if( obj.rst ){
                    $('#hddFilePlanta').val(obj.file);
                    CargaCarteraDAO.loadHeaderFileCarteraPlanta(obj.file);
                }else{
                    $('#layerOverlay,#layerLoading').hide(); 
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            'json'
            );
    },
    UploadTelefono : function ( ) {
        $('#layerOverlay,#layerLoading').css('display','block');
        $('#uploadFileCarteraTelefono').upload(
            '../controller/ControllerCobrast.php',
            {
                command:'carga-cartera',
                action:'uploadTelefono',
                idTmpFile:'uploadFileCarteraTelefono',
                Servicio:$('#hdCodServicio').val(),
                UsuarioCreacion:$('#hdCodUsuario').val(),
                NombreServicio:$('#hdNomServicio').val(),
                CaracterSeparador : $('#cbCaracterSeparadorTelefono').val()
            },
            function(obj){
                if( obj.rst ){
                    $('#hddFileTelefono').val(obj.file);
                    CargaCarteraDAO.loadHeaderFileTelefono(obj.file);
                }else{
                    $('#layerOverlay,#layerLoading').hide(); 
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            'json'
            );
    },
    UploadDetalle : function ( ) {
        $('#layerOverlay,#layerLoading').css('display','block');
        $('#uploadFileCarteraDetalle').upload(
            '../controller/ControllerCobrast.php',
            {
                command:'carga-cartera',
                action:'uploadDetalle',
                idTmpFile:'uploadFileCarteraDetalle',
                Servicio:$('#hdCodServicio').val(),
                UsuarioCreacion:$('#hdCodUsuario').val(),
                NombreServicio:$('#hdNomServicio').val()
            },
            function(obj){
                if( obj.rst ){
                    $('#hddFileDetalle').val(obj.file);
                    CargaCarteraDAO.loadHeaderFileDetalle(obj.file);
                }else{
                    $('#layerOverlay,#layerLoading').hide(); 
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            'json'
            );
    },
    UploadReclamo : function ( ) {
        $('#layerOverlay,#layerLoading').css('display','block');
        $('#uploadFileCarteraReclamo').upload(
            '../controller/ControllerCobrast.php',
            {
                command:'carga-cartera',
                action:'uploadReclamo',
                idTmpFile:'uploadFileCarteraReclamo',
                separator : $('#cbCaracterSeparadorReclamo').val(),
                Servicio:$('#hdCodServicio').val(),
                UsuarioCreacion:$('#hdCodUsuario').val(),
                NombreServicio:$('#hdNomServicio').val(),
                CaracterSeparador : $('#cbCaracterSeparadorReclamo').val()
            },
            function(obj){
                if( obj.rst ){
                    $('#hddFileReclamo').val(obj.file);
                    CargaCarteraDAO.loadHeaderFileReclamo(obj.file);
                }else{
                    $('#layerOverlay,#layerLoading').hide(); 
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            'json'
            );
    },
    UploadRRLL : function ( ) {
        $('#layerOverlay,#layerLoading').css('display','block');
        $('#uploadFileCarteraRRLL').upload(
            '../controller/ControllerCobrast.php',
            {
                command:'carga-cartera',
                action:'uploadRRLL',
                idTmpFile:'uploadFileCarteraRRLL',
                Servicio:$('#hdCodServicio').val(),
                UsuarioCreacion:$('#hdCodUsuario').val(),
                NombreServicio:$('#hdNomServicio').val(),
                CaracterSeparador : $('#cbCaracterSeparadorRRLL').val()
            },
            function(obj){
                if( obj.rst ){
                    $('#hddFileRRLL').val(obj.file);
                    CargaCarteraDAO.loadHeaderFileRRLL(obj.file);
                }else{
                    $('#layerOverlay,#layerLoading').hide(); 
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            'json'
            );
    },
    loadHeaderFileCarteraPlanta : function ( xfile ) {
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'carga-cartera',
                action:'loadHeaderCarteraPlanta',
                separator:$('#cbCaracterSeparadorPlanta').val(),
                file:xfile,
                NombreServicio:$('#hdNomServicio').val()
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                $('#layerOverlay,#layerLoading').hide(); 
                if( obj.rst ){
								
                    CargaCarteraDAO.headerPlanta=new Array();
								
                    var html='';
                    for(i=0;i<obj.header.length;i++){
                        CargaCarteraDAO.headerPlanta.push(obj.header[i]);
                        html+='<option>'+obj.header[i]+'</option>';
                    }
                    $('#panelCargarPlanta #selectHeaderPlanta').find('select').not('#ca_datos_adicionales_planta').html('<option value="0">--Seleccione--</option>'+html);
                    $('#panelCargarPlanta #selectHeaderPlanta').find('#ca_datos_adicionales_planta').html(html);
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Error al cargar cabeceras de centro de pago','350px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                $('#layerOverlay,#layerLoading').hide(); 
                CargaCarteraDAO.error_ajax();
            }
        });
    },
    UploadLimpiarCartera : function ( ) {
        $('#layerOverlay,#layerLoading').css('display','block');
        $('#uploadLimpiarCartera').upload(
            '../controller/ControllerCobrast.php',
            {
                command:'carga-cartera',
                action:'uploadLimpiarCartera',
                idTmpFile:'uploadLimpiarCartera',
                Servicio:$('#hdCodServicio').val(),
                UsuarioCreacion:$('#hdCodUsuario').val(),
                NombreServicio:$('#hdNomServicio').val()
            },
            function(obj){
                if( obj.rst ){
                    var tipo=$('#cbTipoArchivo').val();
                    if( tipo=='cartera' ) {
                        $('#hddFile').val(obj.file);
                    }else if( tipo=='pago' ) {
                        $('#hddFilePago').val(obj.file);
                    }else if( tipo=='centro_pago' ){
                        $('#hdFileCentroPago').val(obj.file);
                    }
                    CargaCarteraDAO.LimpiarCartera(obj.file);
                //CargaCarteraDAO.loadHeaderFilePago(obj.file);
                }else{
                    $('#layerOverlay,#layerLoading').hide(); 
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            'json'
            );
    },
    LimpiarCartera : function ( xfile ) {
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'carga-cartera',
                action:'LimpiarCartera',
                file : xfile,
                Servicio:$('#hdCodServicio').val(),
                UsuarioCreacion:$('#hdCodUsuario').val(),
                NombreServicio:$('#hdNomServicio').val()
            },
            success : function ( obj ) {
                $('#layerOverlay,#layerLoading').hide(); 
                if(obj.rst){
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                    var tipo=$('#cbTipoArchivo').val();
                    if( tipo=='pago' ) {
                        CargaCarteraDAO.loadHeaderFilePago($('#hddFilePago').val());
                        $('#aDisplayPanelCargarPago').trigger('click');
                    }else if( tipo=='cartera' ) {
                        CargaCarteraDAO.loadHeaderFile($('#hddFile').val());
                        $('#aDisplayPanelCargarCartera').trigger('click');
                    }else if( tipo=='centro_pago' ){
                        CargaCarteraDAO.loadHeaderFileCentroPago($('#hdFileCentroPago').val());
                        $('#aDisplayPanelCargarCentroPago').trigger('click');
                    }
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                $('#layerOverlay,#layerLoading').hide(); 
                CargaCarteraDAO.error_ajax();
            }
        });
    },
    loadHeaderFilePago : function ( xfile ) {
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'carga-cartera',
                action:'loadHeaderPago',
                separator:$('#cbCaracterSeparadorPago').val(),
                file:xfile,
                NombreServicio:$('#hdNomServicio').val()
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                $('#layerOverlay,#layerLoading').hide(); 
                if( obj.rst ){
                    var html='';
                    for(i=0;i<obj.header.length;i++){
                        html+='<option value="'+obj.header[i]+'" >'+obj.header[i]+'</option>';
                    }
                    $('#panelCargarPagoMain #layerHeaderPago').find('select').html('<option value="0">--Seleccione--</option>'+html);
								
                    if( obj.dataJsonParserBefore!=null ) {
																
                        if( obj.dataJsonParserBefore.pago ) {
                            var dataPago = eval( $.parseJSON( obj.dataJsonParserBefore.pago ) );
                            for( j=0;j<dataPago.length;j++ ) {
                                //for( index in dataPago ) {
                                var cont = 0;
                                for(i=0;i<obj.header.length;i++){
                                    if( dataPago[j].dato == obj.header[i] ) {
                                        //if( dataPago[index] == obj.header[i] ) {
                                        cont++;
                                    }
                                }
                                if( cont>0 ) {
                                    //$('#panelCargarPagoMain #layerHeaderPago').find('select[id="'+index+'"]').val(dataPago[index]);
                                    $('#panelCargarPagoMain #layerHeaderPago').find('select[id="'+dataPago[j].campoT+'"]').val(dataPago[j].dato);
                                    $('#panelCargarPagoMain #layerHeaderPago').find(':text[id="txt_'+dataPago[j].campoT+'"]').val(dataPago[j].label);
                                }
                            }
                        }
								
                    }
	
                    if( obj.headerNot.length!=0 ){
                        $('#selectHeaderNotCarteraPago #TDnewHeaderCarteraPago').html('<pre style="white-space:normal;">'+obj.headerNot.join(",\t")+'</pre>');
                        $('#selectHeaderNotCarteraPago').fadeIn(2000);
                        $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Nuevas Cabeceras en Cartera de Pago','300px'));
                        
                    }
                    
                    $('#loadHeaderErrorPagoMsg').val(obj.msg);
								
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Error al cargar cabeceras de pagos','300px'));
                    
                }
            },
            error : function ( ) {
                $('#layerOverlay,#layerLoading').hide(); 
                
            }
        });
    },
    loadHeaderFileTelefono : function ( xfile ) {
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'carga-cartera',
                action:'loadHeaderTelefono',
                separator:$('#cbCaracterSeparadorTelefono').val(),
                file:xfile,
                NombreServicio:$('#hdNomServicio').val()
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                $('#layerOverlay,#layerLoading').hide(); 
                if( obj.rst ){
                    var html='';
                    for(i=0;i<obj.header.length;i++){
                        html+='<option>'+obj.header[i]+'</option>';
                    }
                    $('#panelCargarTelefono #layerHeaderTelefono').find('select').html('<option value="0">--Seleccione--</option>'+html);
								
                    if( obj.dataJsonParserBefore!=null ) {
																
                        if( obj.dataJsonParserBefore.telefono ) {
										
                            var dataTelefono = eval( $.parseJSON( obj.dataJsonParserBefore.telefono ) );
                            var dtelefono = dataTelefono.dataTelefono;
                            $('#panelCargarTelefono #layerHeaderTelefono').find('select[id="codigo_cliente"]').val(dataTelefono.codigo_cliente);
										
                            for( index in dtelefono ) {
											
                                for( j=0;j<dtelefono[index].length;j++ ) {
                                    var cont = 0;
                                    for(i=0;i<obj.header.length;i++){
                                        if( dtelefono[index][j].dato == obj.header[i] ) {
                                            cont++;
                                        }
                                    }
                                    if( cont>0 ) {
                                        $('#panelCargarTelefono #layerHeaderTelefono').find('div[title="'+index+'"]').find('select[id="'+dtelefono[index][j].campoT+'"]').val(dtelefono[index][j].dato);
                                    }
                                }
											
                            }
                        }
								
                    }
	
                    if( obj.headerNot.length!=0 ){
                        $('#selectHeaderNotCarteraTelefono #TDnewHeaderCarteraTelefono').html('<pre style="white-space:normal;">'+obj.headerNot.join(",\t")+'</pre>');
                        $('#selectHeaderNotCarteraTelefono').fadeIn(2000);
                        $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Nuevas Cabeceras en archivo de telefonos','300px'));
                        CargaCarteraDAO.setTimeOut_hide_message();
                    }
								
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'400px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                $('#layerOverlay,#layerLoading').hide(); 
                CargaCarteraDAO.error_ajax();
            }
        });
    },
    loadHeaderFileDetalle : function ( xfile ) {
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'carga-cartera',
                action:'loadHeaderDetalle',
                separator:$('#cbCaracterSeparadorDetalle').val(),
                file:xfile,
                cartera : $('#cbCarteraDetalle').val(),
                NombreServicio:$('#hdNomServicio').val()
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                $('#layerOverlay,#layerLoading').hide(); 
                if( obj.rst ){
                    CargaCarteraDAO.headerDetalle = new Array();
                    var html='';
                    for(i=0;i<obj.header.length;i++){
                        CargaCarteraDAO.headerDetalle.push(obj.header[i]);
                        html+='<option>'+obj.header[i]+'</option>';
                    }
                    $('#panelCargarDetalle').find('#tableDataOperacionCarteraDetalle,#tableDataCuentaCarteraDetalle').find('select').html('<option value="0">-Seleccione-</option>'+html);
                    $('#panelCargarDetalle').find('#trDataAdicionalesCarteraDetalle').find('select').html(html);
								
                    if( obj.dataJsonParserBefore!=null ) {
								
                        if( obj.dataJsonParserBefore.detalle_cuenta ) {
                            var dataDetalleCuenta = eval( obj.dataJsonParserBefore.detalle_cuenta );
                            for( i=0;i<dataDetalleCuenta.length;i++ ) {
                                var cont =0 ;
                                for( j=0;j<obj.header.length;j++ ) {
                                    if( dataDetalleCuenta[i].dato == obj.header[j] ) {
                                        cont++;
                                    }
                                }
                                if( cont>0 ) { 
                                    $('#panelCargarDetalle #layerHeaderDetalle #tableDataOperacionCarteraDetalle').find('select[id="'+dataDetalleCuenta[i].campoT+'"]').val(dataDetalleCuenta[i].dato);
                                    $('#panelCargarDetalle #layerHeaderDetalle #tableDataOperacionCarteraDetalle').find(':text[id="txt_'+dataDetalleCuenta[i].campoT+'"]').val(dataDetalleCuenta[i].label);
                                }
                            }
                        }
									
									
                        var dataAdicionales = eval( $.parseJSON( obj.dataJsonParserBefore.adicionales ) );
									
                        var html='';
									
                        for( i=0;i<dataAdicionales["ca_datos_adicionales_detalle_cuenta"].length;i++ ) {
                            //									for( index2 in dataAdicionales["ca_datos_adicionales_detalle_cuenta"] ) {
                            var cont =0 ;
                            for( j=0;j<obj.header.length;j++ ) {
                                if( dataAdicionales["ca_datos_adicionales_detalle_cuenta"][i].dato == obj.header[j] ) {
                                    cont++;
                                }
                            }
                            if( cont>0 ) { 
                                html+='<option label="'+dataAdicionales["ca_datos_adicionales_detalle_cuenta"][i].dato+'" value="'+dataAdicionales["ca_datos_adicionales_detalle_cuenta"][i].dato+'">'+dataAdicionales["ca_datos_adicionales_detalle_cuenta"][i].label+'</option>';
                            }
                        }
                        //if( html!='' ) {
                        $('#panelCargarDetalle #layerHeaderDetalle').find('select[id="ca_datos_adicionales_detalle_cuenta"]').html(html);
                        //}
										
																		
                        //$('#panelCargarDetalle #layerHeaderDetalle #tableDataCuentaCarteraDetalle').find('#numero_cuenta').val(obj.dataJsonParserBefore.numero_cuenta_detalle);
                        if( $.trim( obj.dataJsonParserBefore.numero_cuenta_detalle ) != '' ) {
                            $('#panelCargarDetalle #layerHeaderDetalle #tableDataCuentaCarteraDetalle').find('#numero_cuenta').val(obj.dataJsonParserBefore.numero_cuenta_detalle);
                        }
                        if( $.trim( obj.dataJsonParserBefore.moneda_detalle ) != ''){
                            $('#panelCargarDetalle #layerHeaderDetalle #tableDataCuentaCarteraDetalle').find('#moneda').val(obj.dataJsonParserBefore.moneda_detalle);
                        }
								
                    }
								
                    //if( obj.dataJsonParserBefore!=null ) {
                    //																
                    //									if( obj.dataJsonParserBefore.pago ) {
                    //										var dataPago = eval( $.parseJSON( obj.dataJsonParserBefore.pago ) );
                    //										for( index in dataPago ) {
                    //											var cont = 0;
                    //											for(i=0;i<obj.header.length;i++){
                    //												if( dataPago[index] == obj.header[i] ) {
                    //													cont++;
                    //												}
                    //											}
                    //											if( cont>0 ) {
                    //												$('#panelCargarPagoMain #layerHeaderPago').find('select[id="'+index+'"]').val(dataPago[index]);
                    //											}
                    //										}
                    //									}
                    //								
                    //								}
                    //	
                    if( obj.headerNot.length!=0 ){
                        $('#selectHeaderNotCarteraDetalle #TDnewHeaderCarteraDetalle').html('<pre style="white-space:normal;">'+obj.headerNot.join(",\t")+'</pre>');
                        $('#selectHeaderNotCarteraDetalle').fadeIn(2000);
                        $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Nuevas Cabeceras en Cartera de Pago','300px'));
                        CargaCarteraDAO.setTimeOut_hide_message();
                    }
								
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Error al cargar cabeceras de pagos','300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                $('#layerOverlay,#layerLoading').hide(); 
                CargaCarteraDAO.error_ajax();
            }
        });
    },
    loadHeaderFileReclamo : function ( xfile ) {
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'carga-cartera',
                action:'loadHeaderReclamo',
                separator:$('#cbCaracterSeparadorReclamo').val(),
                file:xfile,
                cartera : $('#cbCarteraReclamo').val(),
                servicio : $('#hdCodServicio').val(),
                NombreServicio:$('#hdNomServicio').val()
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                $('#layerOverlay,#layerLoading').hide(); 
                if( obj.rst ){
                    //CargaCarteraDAO.headerDetalle = new Array();
                    var html='';
                    for(i=0;i<obj.header.length;i++){
                        //CargaCarteraDAO.headerDetalle.push(obj.header[i]);
                        html+='<option>'+obj.header[i]+'</option>';
                    }
                    $('#panelCargarReclamos').find('#layerHeaderReclamo').find('select').html('<option value="0">-Seleccione-</option>'+html);
								
                    if( obj.dataJsonParserBefore!=null ) {
								
                        if( obj.dataJsonParserBefore.reclamo ) {
                            var dataReclamo = eval( obj.dataJsonParserBefore.reclamo );
                            for( i=0;i<dataReclamo.length;i++ ) {
                                var cont =0 ;
                                for( j=0;j<obj.header.length;j++ ) {
                                    if( dataReclamo[i].dato == obj.header[j] ) {
                                        cont++;
                                    }
                                }
                                if( cont>0 ) { 
                                    $('#panelCargarReclamos #layerHeaderReclamo').find('select[id="'+dataReclamo[i].campoT+'"]').val(dataReclamo[i].dato);
                                }
                            }
                        }
																										
                    }
								
                    if( obj.headerNot.length!=0 ){
                        $('#selectHeaderNotCarteraReclamo #TDnewHeaderCarteraReclamo').html('<pre style="white-space:normal;">'+obj.headerNot.join(",\t")+'</pre>');
                        $('#selectHeaderNotCarteraReclamo').fadeIn(2000);
                        $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Nuevas Cabeceras en Cartera de Reclamo','300px'));
                        CargaCarteraDAO.setTimeOut_hide_message();
                    }
								
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Error al cargar cabeceras de pagos','300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                $('#layerOverlay,#layerLoading').hide(); 
                CargaCarteraDAO.error_ajax();
            }
        });
    },
    loadHeaderFileRRLL : function ( xfile ) {
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'carga-cartera',
                action:'loadHeaderRRLL',
                separator:$('#cbCaracterSeparadorRRLL').val(),
                file:xfile,
                cartera : $('#cbCarteraRRLL').val(),
                servicio : $('#hdCodServicio').val(),
                NombreServicio:$('#hdNomServicio').val()
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                $('#layerOverlay,#layerLoading').hide(); 
                if( obj.rst ){
                    //CargaCarteraDAO.headerDetalle = new Array();
                    var html='';
                    for(i=0;i<obj.header.length;i++){
                        //CargaCarteraDAO.headerDetalle.push(obj.header[i]);
                        html+='<option>'+obj.header[i]+'</option>';
                    }
                    $('#panelCargarRRLL').find('#layerHeaderRRLL').find('select').html('<option value="0">-Seleccione-</option>'+html);
								
                    if( obj.dataJsonParserBefore!=null ) {
								
                        if( obj.dataJsonParserBefore.rrll ) {
                            var dataRRLL = eval( obj.dataJsonParserBefore.rrll );
                            for( i=0;i<dataRRLL.length;i++ ) {
                                var cont =0 ;
                                for( j=0;j<obj.header.length;j++ ) {
                                    if( dataRRLL[i].dato == obj.header[j] ) {
                                        cont++;
                                    }
                                }
                                if( cont>0 ) { 
                                    $('#panelCargarRRLL #layerHeaderRRLL').find('select[id="'+dataRRLL[i].campoT+'"]').val(dataRRLL[i].dato);
                                }
                            }
                        }
																										
                    }
								
                    if( obj.headerNot.length!=0 ){
                        $('#selectHeaderNotCarteraRRLL #TDnewHeaderCarteraRRLL').html('<pre style="white-space:normal;">'+obj.headerNot.join(",\t")+'</pre>');
                        $('#selectHeaderNotCarteraRRLL').fadeIn(2000);
                        $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Nuevas Cabeceras en Cartera de Reclamo','300px'));
                        CargaCarteraDAO.setTimeOut_hide_message();
                    }
								
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Error al cargar cabeceras','300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                $('#layerOverlay,#layerLoading').hide(); 
                CargaCarteraDAO.error_ajax();
            }
        });
    },
    loadHeaderFileRetiro : function ( xfile ) {
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'carga-cartera',
                action:'loadHeaderRetiro',
                separator:$('#cbCaracterSeparadorCargaRetiro').val(),
                file:xfile,
                NombreServicio:$('#hdNomServicio').val()
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                $('#layerOverlay,#layerLoading').hide(); 
                if( obj.rst ){
                    var html='';
                    for(i=0;i<obj.header.length;i++){
                        html+='<option>'+obj.header[i]+'</option>';
                    }
                    $('#panelCargaRetiro #layerHeaderRetiro').find('select').html('<option value="0">--Seleccione--</option>'+html);
								
                    if( obj.dataJsonParserBefore!=null ) {
																
                        if( obj.dataJsonParserBefore.retiro ) {
                            var dataRetiro = eval( $.parseJSON( obj.dataJsonParserBefore.retiro ) );
                            for( index in dataRetiro ) {
                                $('#panelCargaRetiro #layerHeaderRetiro').find('select[id="'+index+'"]').val(dataRetiro[index]);
                            }
                        }
								
                    }
	
                    if( obj.headerNot.length!=0 ){
                        $('#selectHeaderNotCarteraRetiro #TDnewHeaderCarteraRetiro').html('<pre style="white-space:normal;">'+obj.headerNot.join(",\t")+'</pre>');
                        $('#selectHeaderNotCarteraRetiro').fadeIn(2000);
                        $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Nuevas Cabeceras en Cartera de Retiro','300px'));
                        CargaCarteraDAO.setTimeOut_hide_message();
                    }
								
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Error al cargar cabeceras de retiros','300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                $('#layerOverlay,#layerLoading').hide(); 
                CargaCarteraDAO.error_ajax();
            }
        });
    },
    loadHeaderFileCentroPago : function ( xfile ) {
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'carga-cartera',
                action:'loadHeaderCentroPago',
                separator:$('#cbCaracterSeparadorCentroPago').val(),
                file:xfile,
                NombreServicio:$('#hdNomServicio').val()
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                $('#layerOverlay,#layerLoading').hide(); 
                if( obj.rst ){
                    var html='';
                    for(i=0;i<obj.header.length;i++){
                        html+='<option>'+obj.header[i]+'</option>';
                    }
                    $('#panelCargarCentroPago #layerHeaderCentroPago').find('select').html('<option value="0">--Seleccione--</option>'+html);
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Error al cargar cabeceras de centro de pago','350px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                $('#layerOverlay,#layerLoading').hide(); 
                CargaCarteraDAO.error_ajax();
            }
        });
    },
    /*jmore*/
    procesarNormalizacionTelefono:function(){
        $.ajax({
            url:this.url,
            type:'GET',
            dataType:'json',
            data:{
                command:'carga-cartera',
                action:'procesarNormalizacionTelefono',
                cartera:$('#cboCarteraNormalizacionTelefono').val()
            },
            beforeSend:function(){
                $('#layerOverlay').css('display','block');
                $('#layerLoading').css('display','block');
            },
            success:function(obj){
                $('#layerOverlay').css('display','none');
                $('#layerLoading').css('display','none');                
                if (obj.rst){
                    alert(obj.msg);
                }else{
                    alert(obj.msg);
                }
            },
            error:function(){
                CargaCarteraDAO.error_ajax();                
            }
        })
    },/*jmore*/
    loadCampania:function(){
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : this.xTypeData,
            data : {
                command:'carga-cartera',
                action:'ListCampania',
                Servicio:$('#hdCodServicio').val()
            },
            beforeSend : function ( ) {
                var _html="";
                _html+="<option>";
                _html+="Cargando..";
                _html+="</option>";
                $("#cboCampania").html(_html);
            },            
            success : function ( _obj ) {
                var _html="";
                _html+='<option value="0" >--Seleccione--</option>';
                $.each(_obj,function(k,v){
                    _html+="<option value="+v.idcampania+">";
                    _html+=v.nombre;
                    _html+="</option>";
                //$("#cboCampania").html(_html);
                });
                //$('#cboCampania,#cboCampaniaPago,#cbCampaniaComision,#cbCampaniaComisionGenerico,#cboCampaniaPlanta,#cboCampaniaRetiro,#cboCampaniaTelefono,#cboCampaniaDetalle,#cboCampaniaReclamo,#cboCampaniaRRLL,#cboCampaniaNOC').html(_html);
                $('select[id^="cboCampania"]').html(_html);
            },
            error : function ( ) {
                CargaCarteraDAO.error_ajax();
            }
        });
    },
    loadHeaderFile:function(xfile){
        $.ajax({
            url:this.url,
            type:'POST',
            dataType:this.xTypeData,
            async : false,
            data:{
                command:'carga-cartera',
                action:'loadHeader',
                separator:$.trim( $('#txtCaracterSeparador').val() ),
                file:xfile,
                NombreServicio:$('#hdNomServicio').val()
            },
            beforeSend : function(){},
            success : function( obj ){
                $('#layerOverlay,#layerLoading').hide(); 
                if( obj.rst ){
                    CargaCarteraDAO.header=new Array();
                    var html='';
                    for( i=0;i<obj.header.length;i++ ) {
                        CargaCarteraDAO.header.push(obj.header[i]);
                        html+='<option>'+obj.header[i]+'</option>';
                    }
                    
                    $("div[id^='layerTabCargaCartera']").not('#layerTabCargaCarteraDatosAdicionales').find("select").html('<option value="0">-Seleccione-</option>'+html);
                    $('#cbCabeceraDepartamentoCargaCarteraMain').html('<option value="0">-Seleccione-</option>'+html);
                    //$('#layerTabCargaCarteraDatosAdicionales #ca_datos_adicionales_cliente').html(html);
                    //$('#layerTabCargaCarteraDatosAdicionales').find('select[id^="ca_datos_adicionales_"]').not('#ca_datos_adicionales_cliente').empty();
                    $('#layerTabCargaCarteraDatosAdicionales #adicionales_cartera').html(html);
                    $('#layerTabCargaCarteraDatosAdicionales').find('select[id^="ca_datos_adicionales_"]').empty();
					
                    /****** Json Parser ******/
					
                    if( obj.dataJsonParserBefore!=null ) {
					
                        if( obj.dataJsonParserBefore.cliente ) {
                            var dataCliente = eval( obj.dataJsonParserBefore.cliente ) ;
                            for( i=0;i<dataCliente.length;i++ ) {
                                var cont =0 ;
                                for( j=0;j<obj.header.length;j++ ) {
                                    if( dataCliente[i].dato == obj.header[j] ) {
                                        cont++;
                                    }
                                }
								
                                if( cont>0 ) {
                                    $('#layerTabCargaCarteraCliente').find('select[id="'+dataCliente[i].campoT+'"]').val(dataCliente[i].dato);
                                    $('#layerTabCargaCarteraCliente').find(':text[id="txt_'+dataCliente[i].campoT+'"]').val(dataCliente[i].label);
                                }
                            }
                        }
						
                        if( obj.dataJsonParserBefore.cartera ) {
                            var dataCartera = eval( $.parseJSON(obj.dataJsonParserBefore.cartera) );
                            for( i=0;i<dataCartera.length;i++ ) {
                                //for( index in dataCuenta ) {
                                var cont =0 ;
                                for( j=0;j<obj.header.length;j++ ) {
                                    if( dataCartera[i].dato == obj.header[j] ) {
                                        cont++;
                                    }
                                }
                                if( cont>0 ) {
                                    $('#layerTabCargaCarteraCartera').find('select[id="'+dataCartera[i].campoT+'"]').val(dataCartera[i].dato);
                                    $('#layerTabCargaCarteraCartera').find(':text[id="txt_'+dataCartera[i].campoT+'"]').val(dataCartera[i].label);
                                }
                            }
                        }
                        
                        if( obj.dataJsonParserBefore.cuenta ) {
                            var dataCuenta = eval( $.parseJSON(obj.dataJsonParserBefore.cuenta) );
                            for( i=0;i<dataCuenta.length;i++ ) {
                                //for( index in dataCuenta ) {
                                var cont =0 ;
                                for( j=0;j<obj.header.length;j++ ) {
                                    if( dataCuenta[i].dato == obj.header[j] ) {
                                        cont++;
                                    }
                                }
                                if( cont>0 ) {
                                    $('#layerTabCargaCarteraCuenta').find('select[id="'+dataCuenta[i].campoT+'"]').val(dataCuenta[i].dato);
                                    $('#layerTabCargaCarteraCuenta').find(':text[id="txt_'+dataCuenta[i].campoT+'"]').val(dataCuenta[i].label);
                                }
                            }
                        }
						
                        if( obj.dataJsonParserBefore.detalle_cuenta ) {
                            var dataDetalleCuenta = eval( obj.dataJsonParserBefore.detalle_cuenta );
                            for( i=0;i<dataDetalleCuenta.length;i++ ) {
                                var cont =0 ;
                                for( j=0;j<obj.header.length;j++ ) {
                                    if( dataDetalleCuenta[i].dato == obj.header[j] ) {
                                        cont++;
                                    }
                                }
                                if( cont>0 ) { 
                                    $('#layerTabCargaCarteraOperacion').find('select[id="'+dataDetalleCuenta[i].campoT+'"]').val(dataDetalleCuenta[i].dato);
                                    $('#layerTabCargaCarteraOperacion').find(':text[id="txt_'+dataDetalleCuenta[i].campoT+'"]').val(dataDetalleCuenta[i].label);									
                                }
                            }
                        }
						
                        if( obj.dataJsonParserBefore.telefono ) {
                            var dataTelefono = eval( $.parseJSON( obj.dataJsonParserBefore.telefono ) );
                            for( index in dataTelefono ) {
                                for( index2 in dataTelefono[index] ) {
                                    var cont =0 ;
                                    for( j=0;j<obj.header.length;j++ ) {
                                        if( dataTelefono[index][index2] == obj.header[j] ) {
                                            cont++;
                                        }
                                    }
                                    if( cont>0 ) {
                                        $('#layerTabCargaCarteraTelefono').find('div[title="'+index+'"]').find('select[id="'+index2+'"]').val(dataTelefono[index][index2]);
                                    }
									
                                }
                            }
                        }
						
                        if( obj.dataJsonParserBefore.direccion ) {
                            var dataDireccion = eval( $.parseJSON( obj.dataJsonParserBefore.direccion ) );
                            for( index in dataDireccion ) {
                                for( index2 in dataDireccion[index] ) {
                                    var cont =0 ;
                                    for( j=0;j<obj.header.length;j++ ) {
                                        if( dataDireccion[index][index2] == obj.header[j] ) {
                                            cont++;
                                        }
                                    }
                                    if( cont>0 ) {
                                        $('#layerTabCargaCarteraDireccion').find('div[title="'+index+'"]').find('select[id="'+index2+'"]').val(dataDireccion[index][index2]);
                                    }
                                }
                            }
                        }
                        //$('#layerTabCargaCarteraDatosAdicionales #btnLimpiar').trigger('click');
                        if( obj.dataJsonParserBefore.adicionales ) {
                            var dataAdicionales = eval( $.parseJSON( obj.dataJsonParserBefore.adicionales ) );
                            for( index in dataAdicionales ) {
                                var html='';
                                for( i=0;i<dataAdicionales[index].length;i++ ) {
                                    //for( index2 in dataAdicionales[index] ) {
                                    var cont =0 ;
                                    for( j=0;j<obj.header.length;j++ ) {
                                        if( dataAdicionales[index][i].dato == obj.header[j] ) {
                                            //if( dataAdicionales[index][index2] == obj.header[j] ) {
                                            cont++;
                                        }
                                    }
                                    if( cont>0 ) { 
                                        html+='<option label="'+dataAdicionales[index][i].dato+'" >'+dataAdicionales[index][i].label+'</option>';
                                    }
                                }
                                //if( html!='' ) {
                                $('#layerTabCargaCarteraDatosAdicionales').find('select[id="'+index+'"]').html(html);
                            //}
                            }
                        }
					
                    }
					//alert(obj.headerNot.join(",\t"));
                    if( obj.headerNot.length>0 ) {
                        $('#selectHeaderNotCarteraMain #TDnewHeaderCarteraMain').html('<pre style="white-space:normal;">'+obj.headerNot.join(",\t")+'</pre>');
                        $('#selectHeaderNotCarteraMain').fadeIn(2000);
                        $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError("Cabeceras Nuevas en Cartera",'400px'));
                        CargaCarteraDAO.setTimeOut_hide_message();
                    }
                    var html = '';
                    for( i=0;i<obj.headerNot.length;i++ ) {
                        html+='<option value="'+obj.headerNot[i]+'">'+obj.headerNot[i]+'</option>';
                    }
                    $('#layerTabCargaCarteraDatosAdicionales').find('select[id="adicionales_cartera"]').html(html);
                /*************************/
                }else{
                //$('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                //CargaCarteraDAO.setTimeOut_hide_message();
                }
                $('#loadHeaderErrorMsg').val(obj.msg)
            },
            error:function(xhr, ajaxOptions, thrownError){
                $('#layerOverlay,#layerLoading').hide(); 
                CargaCarteraDAO.error_ajax();
            }
        });
    },
    generateNOCpreMasivo : function ( ) {
        var files = $("#hddFileNocPre").val();
        
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command : 'carga-cartera',
                action : 'carga_nocpre_masivo',
                //file : $("#hddFileNOC").val(),
                file : files,
                separator : $('#cbCaracterSeparadorNOCPre').val(),
                formatoFechas :$('#cbFormatoFechasNOCPre').val(),
                Servicio : $('#hdCodServicio').val(),
                NombreServicio : $('#hdNomServicio').val(),
                usuario_creacion : $('#hdCodUsuario').val(),
                estado_noc_pre : $('#cbEstadoNocPre').val() 
            },
            beforeSend : function ( ) {
                $('#layerOverlay,#layerLoading').css('display','block');
            },
            success : function ( obj ) {
                $('#layerOverlay,#layerLoading').hide();
                if( obj.rst ){
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                    cancel_carga_cartera_masiva('hddFileNocPre','msg_resultado_masivo_nocpre');
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                $('#layerOverlay,#layerLoading').hide();
                CargaCarteraDAO.error_ajax();
            }
        });
    },	
	generateCourierMasivo : function ( ) {
        
        var files = $("#hddFileCourier").val();
        
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command : 'carga-cartera',
                action : 'carga_courier_masivo',
                file : files,
                separator : $('#cbCaracterSeparadorCourier').val(),
                formatoFechas :$('#cbFormatoFechasCourier').val(),
                Servicio : $('#hdCodServicio').val(),
                NombreServicio : $('#hdNomServicio').val(),
                Tipo : $('#cbTipoCargaCourier').val(),
                usuario_creacion : $('#hdCodUsuario').val() 
            },
            beforeSend : function ( ) {
                $('#layerOverlay,#layerLoading').css('display','block');
            },
            success : function ( obj ) {
                $('#layerOverlay,#layerLoading').hide();
                if( obj.rst ){
                    $('#hddFileCourier').val('');
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                    cancel_carga_cartera_masiva('hddFileCourier','msg_resultado_masivo_courier');
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                $('#layerOverlay,#layerLoading').hide();
                CargaCarteraDAO.error_ajax();
            }
        });
        
    },
    generateEstadoCuentaMasivo : function ( ) {

        var files = $("#hddFileEstadoCuenta").val();

        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command : 'carga-cartera',
                action : 'carga_estado_cuenta_masivo',
                file : files,
                separator : $('#cbCaracterSeparadorEstadoCuenta').val(),
                campania : $('#cboCampaniaEstadoCuenta').val(),
                Servicio : $('#hdCodServicio').val(),
                NombreServicio : $('#hdNomServicio').val(),
                usuario_creacion : $('#hdCodUsuario').val() 
            },
            beforeSend : function ( ) {
                $('#layerOverlay,#layerLoading').css('display','block');
            },
            success : function ( obj ) {
                $('#layerOverlay,#layerLoading').hide();
                if( obj.rst ){
                    $('#hddFileEstadoCuenta').val('');
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    cancel_carga_cartera_masiva('hddFileEstadoCuenta','msg_resultado_masivo_estado_cuenta');
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    
                }
            },
            error : function ( ) {
                $('#layerOverlay,#layerLoading').hide();
                CargaCarteraDAO.error_ajax();
            }
        });

    },
    generateSaldoTotalMasivo : function ( ) {

        var files = $("#hddFileSaldoTotal").val();

        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command : 'carga-cartera',
                action : 'carga_saldo_total_masivo',
                file : files,
                separator : $('#cbCaracterSeparadorSaldoTotal').val(),
                Servicio : $('#hdCodServicio').val(),
                NombreServicio : $('#hdNomServicio').val(),
                usuario_creacion : $('#hdCodUsuario').val() 
            },
            beforeSend : function ( ) {
                $('#layerOverlay,#layerLoading').css('display','block');
            },
            success : function ( obj ) {
                $('#layerOverlay,#layerLoading').hide();
                if( obj.rst ){
                    $('#hddFileSaldoTotal').val('');
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    cancel_carga_cartera_masiva('hddFileSaldoTotal','msg_resultado_masivo_saldo_total');
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));

                }
            },
            error : function ( ) {
                $('#layerOverlay,#layerLoading').hide();
                CargaCarteraDAO.error_ajax();
            }
        });

    },
    generateDetalleMMasivo : function ( ) {
        
        var files = $("#hddFileDetalleM").val();

        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command : 'carga-cartera',
                action : 'carga_detalle_m_masivo',
                file : files,
                separator : $('#cbCaracterSeparadorDetalleM').val(),
                Campania :  $('#cboCampaniaDetalleM').val(),
                Cartera : $('#cboCarteraDetalleM').val(),
                Servicio : $('#hdCodServicio').val(),
                NombreServicio : $('#hdNomServicio').val(),
                usuario_creacion : $('#hdCodUsuario').val() 
            },
            beforeSend : function ( ) {
                $('#layerOverlay,#layerLoading').css('display','block');
            },
            success : function ( obj ) {
                $('#layerOverlay,#layerLoading').hide();
                if( obj.rst ){
                    $('#hddFileDetalleM').val('');
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    cancel_carga_cartera_masiva('hddFileDetalleM','msg_resultado_masivo_detalle_m');
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));

                }
            },
            error : function ( ) {
                $('#layerOverlay,#layerLoading').hide();
                CargaCarteraDAO.error_ajax();
            }
        });
        
    },
    generateIVRMasivo : function ( ) {
        var files = $("#hddFileIVR").val();
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command : 'carga-cartera',
                action : 'carga_ivr_masivo',
                file : files,
                separator : $('#cbCaracterSeparadorIVR').val(),
                formatoFechas :$('#cbFormatoFechasIVR').val(),
                //idcampania : $('#cboCampaniaIVR').val(),
                Servicio : $('#hdCodServicio').val(),
                //Cartera : $('#cboCarteraIVR').val(),
                NombreServicio : $('#hdNomServicio').val(),
                usuario_creacion : $('#hdCodUsuario').val(),
                estado_contestado : $('#cbEstadoContacIVR').val(),
                estado_no_contestado : $('#cbEstadoNoContacIVR').val()
            },
            beforeSend : function ( ) {
                $('#layerOverlay,#layerLoading').css('display','block');
            },
            success : function ( obj ) {
                $('#layerOverlay,#layerLoading').hide();
                if( obj.rst ){
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                    cancel_carga_cartera_masiva('hddFileIVR','msg_resultado_masivo_IVR');
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                $('#layerOverlay,#layerLoading').hide();
                CargaCarteraDAO.error_ajax();
            }
        });
    },	
		
    generateRetiroMasivo : function ( xcarteras ) {
        var files = $("#hddFileRetiro").val();
        //files = files.substring(0,files.length-1);
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command : 'carga-cartera',
                action : 'carga_retiro_masivo',
                file : files,
                separator : $('#cbCaracterSeparadorRetiro').val(),
                formatoFechas :$('#cbFormatoFechasRetiro').val(),
                Servicio : $('#hdCodServicio').val(),
                NombreServicio : $('#hdNomServicio').val(),
                usuario_creacion : $('#hdCodUsuario').val(),
                Carteras : xcarteras
            },
            beforeSend : function ( ) {
                $('#layerOverlay,#layerLoading').css('display','block');
            },
            success : function ( obj ) {
                $('#layerOverlay,#layerLoading').hide();
                if( obj.rst ){
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    cancel_carga_cartera('hddFileRetiro','msg_resultado_masivo_retiro');
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    
                }
            },
            error : function ( ) {
                $('#layerOverlay,#layerLoading').hide();
                CargaCarteraDAO.error_ajax();
            }
        });
    },	
	
    generateCentroPago : function ( ) {
        var xCentroPago = '{'+$('#panelCargarCentroPago #layerHeaderCentroPago select').find('option:selected').not("option[value='0']").map(function( ){
            return '"'+ $.trim($(this).parent().attr('id'))+'":"'+$.trim( $(this).text() )+'"';
        }).get().join(",")+'}';
				
			
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'carga-cartera',
                action:'carga_centro_pago',
                file:$("#hdFileCentroPago").val(),
                separator:$.trim( $('#cbCaracterSeparadorCentroPago').val() ),
                Nombre:$('#txtCargaCentroPagoNombre').val(),
                Servicio:$('#hdCodServicio').val(),
                NombreServicio:$('#hdNomServicio').val(),
                usuario_creacion:$('#hdCodUsuario').val(),
                data_centro_pago:xCentroPago
            },
            beforeSend : function ( ) {
                $('#layerOverlay,#layerLoading').css('display','block');
            },
            success : function ( obj ) {
                $('#layerOverlay,#layerLoading').hide();
                if( obj.rst ){
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                    camcel_carga_cartera_centro_pago();
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                $('#layerOverlay,#layerLoading').hide();
                CargaCarteraDAO.error_ajax();
            }
        });
			
    },
    generatePago : function ( ) {
		
        var xpago = '['+$('#panelCargarPagoMain #layerHeaderPago select').find('option:selected').not("option[value='0']").map(function( ){
            //return '"'+ $.trim($(this).parent().attr('id'))+'":"'+$.trim( $(this).text() )+'"';
            var idselect = $(this).parent().attr('id') ;
            var label = '';
            if( $.trim( $('#panelCargarPagoMain #layerHeaderPago #txt_'+idselect).val() ) == '' ) {
                label = $.trim($(this).text());
            }else{
                label = $.trim( $('#panelCargarPagoMain #layerHeaderPago #txt_'+idselect).val() );
            }
            return '{"campoT":"'+$.trim($(this).parent().attr('id'))+'","dato":"'+$.trim( $(this).text() )+'","label":"'+label+'"}';
        }).get().join(",")+']';
				
        //			var xcodigo_cliente=$.trim($('#layerHeaderPago #codigo_cliente option:selected').text());
        //			var xnumero_cuenta=$.trim($('#layerHeaderPago #numero_cuenta option:selected').text());
        //			var xcodigo_operacion=$.trim($('#layerHeaderPago #codigo_operacion option:selected').text());
        //			var xmoneda = $.trim( $('#layerHeaderPago #moneda option:selected').text() );
		var files_pagos = $("#hddFilePago").val();
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'carga-cartera',
                action:'carga_pago',
                file : files_pagos,
                separator:$.trim( $('#cbCaracterSeparadorPago').val() ),
                Servicio:$('#hdCodServicio').val(),
                NombreServicio:$('#hdNomServicio').val(),
                //Campania:$('#cboCampaniaPago').val(),
                Cartera:$('#cboCarteraPago').val(),
                Proceso:$('#cbProcesoPago').val(),
                usuario_creacion:$('#hdCodUsuario').val(),
                data_pago:xpago
            },
            beforeSend : function ( ) {
                $('#layerOverlay,#layerLoading').css('display','block');
            },
            success : function ( obj ) {
                $('#layerOverlay,#layerLoading').hide();
                if( obj.rst ){
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                    cancel_carga_cartera_pago();
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                $('#layerOverlay,#layerLoading').hide();
                CargaCarteraDAO.error_ajax();
            }
        });
    },
    generateTelefono : function ( ) {
		
        /*var xdata = '{"codigo_cliente":"'+$('#panelCargarTelefono #layerHeaderTelefono').find('select[id="codigo_cliente"]').val()+'","dataTelefono":{'+$('#panelCargarTelefono #layerHeaderTelefono').find('div[title^="telefono_"]').map(function(){
					return '"'+$(this).attr('title')+'":' +'['+ $(this).find('select').find('option:selected').not("option[value='0']").map(function(){
							return '{"campoT":"'+$(this).parent().attr('id')+'","dato":"'+$.trim( $(this).text() )+'"}';
						}).get().join(",")+']';
				}).get().join(",")+'}}';*/
				
        var xdata = '{'+$('#panelCargarTelefono #layerHeaderTelefono tr[id="tr_ids_carga_telefono"]').find('select option:selected').not("option[value='0']").map(function( ){
            return '"'+$(this).parent().attr('id')+'":"'+$(this).text()+'"';
        }).get().join(",")+',"dataTelefono":{'+$('#panelCargarTelefono #layerHeaderTelefono').find('div[title^="telefono_"]').map(function(){
            return '"'+$(this).attr('title')+'":' +'['+ $(this).find('select').find('option:selected').not("option[value='0']").map(function(){
                return '{"campoT":"'+$(this).parent().attr('id')+'","dato":"'+$.trim( $(this).text() )+'"}';
            }).get().join(",")+']';
        }).get().join(",")+'}}';
		
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command : 'carga-cartera',
                action : 'carga_telefono',
                file : $("#hddFileTelefono").val(),
                separator : $.trim( $('#cbCaracterSeparadorTelefono').val() ),
                Servicio : $('#hdCodServicio').val(),
                NombreServicio : $('#hdNomServicio').val(),
                Campania : $('#cboCampaniaTelefono').val(),
                Cartera : $('#tbCarterasCargaTelefono :checked').map(function( ) {
                    return $(this).val();
                }).get().join(",") ,
                //Cartera : $('#cbCarteraTelefono').val(),
                origen : $('#cbCargaTelefonoOrigen').val(),
                tipo : $('#cbCargaTelefonoTipo').val(),
                usuario_creacion : $('#hdCodUsuario').val(),
                data_telefono : xdata
            },
            beforeSend : function ( ) {
                $('#layerOverlay,#layerLoading').css('display','block');
            },
            success : function ( obj ) {
                $('#layerOverlay,#layerLoading').hide();
                if( obj.rst ){
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                    cancel_carga_cartera_telefono();
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                $('#layerOverlay,#layerLoading').hide();
                CargaCarteraDAO.error_ajax();
            }
        });
    },
    generateDetalle : function ( ) {
        var xnumero_cuenta = $.trim( $('#panelCargarDetalle #numero_cuenta option:selected').text() );
        var xcodigo_operacion = $.trim( $('#panelCargarDetalle #codigo_operacion option:selected').text() );
        var xmoneda_cuenta = ( $.trim( $('#panelCargarDetalle #tableDataCuentaCarteraDetalle').find('#moneda').find('option:selected').text() ) == '-Seleccione-' )?'':$.trim( $('#panelCargarDetalle #tableDataCuentaCarteraDetalle').find('#moneda').find('option:selected').text() );
		
        var xdata_detalle = '['+$('#panelCargarDetalle #tableDataOperacionCarteraDetalle').find('select').find('option:selected').not("option[value='0']").map(function( ){
            var idselect = $(this).parent().attr('id');
            var label = '';
            if( $.trim( $('#panelCargarDetalle #txt_'+idselect).val() ) == '' ) {
                label = $.trim($(this).text());
            }else{
                label = $.trim( $('#panelCargarDetalle #txt_'+idselect).val() );
            }
            return '{"campoT":"'+$(this).parent().attr('id')+'","dato":"'+ $.trim( $(this).text() )+'","label":"'+label+'"}';
        }).get().join(",")+']';

        var xdata_adicional = '{'+$('#panelCargarDetalle').find('select[id="ca_datos_adicionales_detalle_cuenta"]').map(function( ){
            //return '"'+$(this).attr('id')+'":{'+
            return '"'+$(this).attr('id')+'":['+
            $(this).find('option').map(function ( index ){
                //return '"dato'+(index+1)+'":"'+$.trim( $(this).text() )+'"';
                return '{"campoT":"dato'+(index+1)+'","dato":"'+$(this).attr('label')+'","label":"'+$(this).text()+'"}';
            }).get().join(',')+']';
        }).get().join(',')+'}';
		
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command : 'carga-cartera',
                action : 'carga_detalle',
                file : $("#hddFileDetalle").val(),
                separator : $.trim( $('#cbCaracterSeparadorDetalle').val() ),
                Servicio : $('#hdCodServicio').val(),
                NombreServicio : $('#hdNomServicio').val(),
                Campania : $('#cboCampaniaDetalle').val(),
                Cartera : $('#cbCarteraDetalle').val(),
                usuario_creacion : $('#hdCodUsuario').val(),
                numero_cuenta : xnumero_cuenta,
                moneda_cuenta : xmoneda_cuenta,
                codigo_operacion : xcodigo_operacion,
                data_detalle : xdata_detalle,
                data_adicional : xdata_adicional
            },
            beforeSend : function ( ) {
                $('#layerOverlay,#layerLoading').css('display','block');
            },
            success : function ( obj ) {
                $('#layerOverlay,#layerLoading').hide();
                if( obj.rst ){
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                //cancel_carga_cartera_pago();
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                $('#layerOverlay,#layerLoading').hide();
                CargaCarteraDAO.error_ajax();
            }
        });
    },
    generateReclamo : function ( ) {
			
        var xdata_reclamo = '['+$('#panelCargarReclamos #layerHeaderReclamo').find('select').find('option:selected').not("option[value='0']").map(function( ){
            return '{"campoT":"'+$(this).parent().attr('id')+'","dato":"'+ $.trim( $(this).text() )+'"}';
        }).get().join(",")+']';


        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command : 'carga-cartera',
                action : 'carga_reclamo',
                file : $("#hddFileReclamo").val(),
                separator : $.trim( $('#cbCaracterSeparadorReclamo').val() ),
                Servicio : $('#hdCodServicio').val(),
                NombreServicio : $('#hdNomServicio').val(),
                Campania : $('#cboCampaniaReclamo').val(),
                //Cartera : $('#cbCarteraReclamo').val(),
                Cartera : $('#tbCarterasCargaReclamo :checked').map(function( ){
                    return $(this).val();
                }).get().join(","),
                usuario_creacion : $('#hdCodUsuario').val(),
                data_reclamo : xdata_reclamo
            },
            beforeSend : function ( ) {
                $('#layerOverlay,#layerLoading').css('display','block');
            },
            success : function ( obj ) {
                $('#layerOverlay,#layerLoading').hide();
                if( obj.rst ){
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                    cancel_carga_cartera_reclamo();
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                $('#layerOverlay,#layerLoading').hide();
                CargaCarteraDAO.error_ajax();
            }
        });
    },
    generateRRLL : function ( ) {
			
        var xdata_rrll = '['+$('#panelCargarRRLL #layerHeaderRRLL').find('select').find('option:selected').not("option[value='0']").map(function( ){
            return '{"campoT":"'+$(this).parent().attr('id')+'","dato":"'+ $.trim( $(this).text() )+'"}';
        }).get().join(",")+']';


        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command : 'carga-cartera',
                action : 'carga_rrll',
                file : $("#hddFileRRLL").val(),
                separator : $.trim( $('#cbCaracterSeparadorRRLL').val() ),
                Servicio : $('#hdCodServicio').val(),
                NombreServicio : $('#hdNomServicio').val(),
                Campania : $('#cboCampaniaRRLL').val(),
                Cartera : $('#cbCarteraRRLL').val(),
                usuario_creacion : $('#hdCodUsuario').val(),
                data_rrll : xdata_rrll
            },
            beforeSend : function ( ) {
                $('#layerOverlay,#layerLoading').css('display','block');
            },
            success : function ( obj ) {
                $('#layerOverlay,#layerLoading').hide();
                if( obj.rst ){
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                    cancel_carga_cartera_rrll();
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                $('#layerOverlay,#layerLoading').hide();
                CargaCarteraDAO.error_ajax();
            }
        });
    },
    CargaAutomaticaPago : function ( ) {
		
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'carga-cartera',
                action:'carga_pago_automatica',
                file:$("#hddFilePago").val(),
                separator:$.trim( $('#txtCaracterSeparador').val() ),
                Servicio:$('#hdCodServicio').val(),
                NombreServicio:$('#hdNomServicio').val(),
                Campania:$('#cboCampaniaPago').val(),
                Cartera:$('#cbCartera').val(),
                Proceso:$('#cbProcesoPago').val(),
                usuario_creacion:$('#hdCodUsuario').val()
            },
            beforeSend : function ( ) {
                $('#layerOverlay,#layerLoading').css('display','block');
            },
            success : function ( obj ) {
                $('#layerOverlay,#layerLoading').hide();
                if( obj.rst ){
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                    cancel_carga_cartera_pago();
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                $('#layerOverlay,#layerLoading').hide();
                CargaCarteraDAO.error_ajax();
            }
        });
    },
    generateRetiro : function ( ) {
		
        var xretiro = '{'+$('#panelCargaRetiro #layerHeaderRetiro select').find('option:selected').not("option[value='0']").map(function( ){
            return '"'+ $.trim($(this).parent().attr('id'))+'":"'+$.trim( $(this).text() )+'"';
        }).get().join(",")+'}';
				
        var xcodigo_cliente = $('#panelCargaRetiro #layerHeaderRetiro #codigo_cliente').val();
				
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'carga-cartera',
                action:'carga_retiro',
                file:$("#hdFileRetiro").val(),
                separator:$.trim( $('#cbCaracterSeparadorCargaRetiro').val() ),
                Servicio:$('#hdCodServicio').val(),
                NombreServicio:$('#hdNomServicio').val(),
                Campania:$('#cboCampaniaRetiro').val(),
                Cartera:$('#cbCarteraRetiro').val(),
                usuario_creacion:$('#hdCodUsuario').val(),
                codigo_cliente : xcodigo_cliente, 
                data_retiro:xretiro
            },
            beforeSend : function ( ) {
                $('#layerOverlay,#layerLoading').css('display','block');
            },
            success : function ( obj ) {
                $('#layerOverlay,#layerLoading').hide();
                if( obj.rst ){
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                    cancel_carga_cartera_pago();
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                $('#layerOverlay,#layerLoading').hide();
                CargaCarteraDAO.error_ajax();
            }
        });
    },
    generateTable : function ( ) {
        var xcodigo_cliente=$.trim($('#layerTabCargaCarteraCliente #codigo option:selected').text());
        var xnumero_cuenta=$.trim($('#layerTabCargaCarteraCuenta #numero_cuenta option:selected').text());
        var xcodigo_operacion=$.trim($('#layerTabCargaCarteraOperacion #codigo_operacion option:selected').text());
        var xmoneda_cuenta = $.trim( $('#layerTabCargaCarteraCuenta #moneda option:selected').text() );
        var xmoneda_operacion = $.trim( $('#layerTabCargaCarteraOperacion #moneda option:selected').text() );
        var files = $("#hddFile").val();
        
        var xclientes = '['+$("#layerTabCargaCarteraCliente select").find("option:selected").not("option[value='0']").map(function(){
            var idselect = $(this).parent().attr('id');
            var label = '';
            if( $.trim( $('#layerTabCargaCarteraCliente #txt_'+idselect).val() ) == '' ) {
                label = $.trim($(this).text());
            }else{
                label = $.trim( $('#layerTabCargaCarteraCliente #txt_'+idselect).val() );
            }
            return '{"campoT":"'+$(this).parent().attr('id')+'","dato":"'+$.trim($(this).text())+'","label":"'+label+'"}';
        }).get().join(",")+']';
		
		var xcartera = '['+$("#layerTabCargaCarteraCartera select").find("option:selected").not("option[value='0']").map(function(){
            var idselect = $(this).parent().attr('id');
            var label = '';
            if( $.trim( $('#layerTabCargaCarteraCartera #txt_'+idselect).val() ) == '' ) {
                label = $.trim($(this).text());
            }else{
                label = $.trim( $('#layerTabCargaCarteraCartera #txt_'+idselect).val() );
            }
            return '{"campoT":"'+$(this).parent().attr('id')+'","dato":"'+$.trim($(this).text())+'","label":"'+label+'"}';
        }).get().join(",")+']';
			
        var xcuenta ='['+$("#layerTabCargaCarteraCuenta select").find("option:selected").not("option[value='0']").map(function(){
            var idselect = $(this).parent().attr('id');
            //return '"'+$(this).parent().attr('id')+'":"'+$.trim($(this).text())+'"';
            var label = '';
            if( $.trim( $('#layerTabCargaCarteraCuenta #txt_'+idselect).val() ) == '' ) {
                label = $.trim($(this).text());
            }else{
                label = $.trim( $('#layerTabCargaCarteraCuenta #txt_'+idselect).val() );
            }
            return '{"campoT":"'+$(this).parent().attr('id')+'","dato":"'+$.trim($(this).text())+'","label":"'+label+'"}';
        }).get().join(",")+']';
			
        var xoperacion ='['+$("#layerTabCargaCarteraOperacion select").find("option:selected").not("option[value='0']").map(function(){
            var idselect = $(this).parent().attr('id');
            var label = '';
            if( $.trim( $('#layerTabCargaCarteraOperacion #txt_'+idselect).val() ) == '' ) {
                label = $.trim($(this).text());
            }else{
                label = $.trim( $('#layerTabCargaCarteraOperacion #txt_'+idselect).val() );
            }
            return '{"campoT":"'+$(this).parent().attr('id')+'","dato":"'+$.trim($(this).text())+'","label":"'+label+'"}';
        }).get().join(",")+']';
			
        var xTelefono='{'+$('#layerTabCargaCarteraTelefono').find('div[id^="PanelTableTelefono"]').map(function ( ) {
            return '"'+$(this).attr('title')+'":{'+
            $(this).find('select').find('option:selected').not("option[value='0']").map(function( ){
                return '"'+$(this).parent().attr('id')+'":"'+$.trim( $(this).text() )+'"';
            }).get().join(',')+'}';
					
        } ).get().join(',')+'}';
				
        var xDireccion='{'+$('#layerTabCargaCarteraDireccion').find('div[id^="PanelTableDireccion"]').map(function ( ) {
            return '"'+$(this).attr('title')+'":{'+
            $(this).find('select').find('option:selected').not("option[value='0']").map(function( ){
                return '"'+$(this).parent().attr('id')+'":"'+$.trim( $(this).text() )+'"';
            }).get().join(',')+'}';
					
        } ).get().join(',')+'}';
				
        var xDatosAdicionales = '{'+$('#layerTabCargaCarteraDatosAdicionales').find('select[id^="ca_datos_adicionales"]').map(function( ){
            //return '"'+$(this).attr('id')+'":{'+
            return '"'+$(this).attr('id')+'":['+
            $(this).find('option').map(function ( index ){
                //return '"dato'+(index+1)+'":"'+$.trim( $(this).text() )+'"';
                return '{"campoT":"dato'+(index+1)+'","dato":"'+$(this).attr('label')+'","label":"'+$(this).text()+'"}';
            }).get().join(',')+']';
						
        }).get().join(',')+'}';
			
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'carga-cartera',
                action:'carga-cartera',
                file:files, //$("#hddFile").val(),
                separator:$.trim( $('#txtCaracterSeparador').val() ),
                Servicio:$('#hdCodServicio').val(),
                NombreServicio:$('#hdNomServicio').val(),
                Campania:$('#cboCampania').val(),
                NombreCartera:$.trim($('#txtNombreCartera').val()),
                Cartera:$('#cboCarteraActualizar').val(),
                Proceso:$('#cboTipoProceso').val(),
                tipo_servicio:$('#cbCabecerasCarteraMain option:selected').text(),
                TipoData : $(':radio[name="rdTipoCargaCarteraMain"]:checked').val(),
                CabeceraDepartamento : $('#cbCabeceraDepartamentoCargaCarteraMain').val(),
                codigo_cliente:xcodigo_cliente,
                numero_cuenta:xnumero_cuenta,
                moneda_cuenta:xmoneda_cuenta,
                moneda_operacion:xmoneda_operacion,
                codigo_operacion:xcodigo_operacion,
                usuario_creacion:$('#hdCodUsuario').val(),
				data_cliente:xclientes,
				data_cartera:xcartera,
                data_cuenta:xcuenta,
                data_operacion:xoperacion,
                data_telefono:xTelefono,
                data_direccion:xDireccion,
                data_adicionales:xDatosAdicionales
            },
            beforeSend : function ( ) {
                $('#layerOverlay,#layerLoading').css('display','block');
            },
            success : function ( obj ) {
                $('#layerOverlay,#layerLoading').hide();
                if( obj.rst ){
                    //$('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    //CargaCarteraDAO.setTimeOut_hide_message();
                    cancel_carga_cartera();
                }
                var html = '<div>'+obj.msg+'</div>';
                html += '<div>Resumen:</div>';
				var his = '';
                $.each(obj.resumen,
                    function(index,obj)
                    {
						if (obj.history == undefined) {
							his += ' ';
						} else {
							his += '-> '+obj.history;
						}
						html += '<div>'+obj.archivo+' -> '+obj.msg+' '+his+'</div>';
                    }
                    );
                $('body').after('<div id="modalCobrast">'+html+'</div>');
                $('#modalCobrast').dialog(
                {
                    modal : true,
                    title : 'Mensaje',
                    width : 550,
                    resizable : true,
					closeOnEscape: false,
                    buttons : {
                        Aceptar : function()
                        {
                            $(this).remove();
							window.location.reload();
                        }
                    },
                    close : function(event,ui)
                    {
                        $(this).remove();
						window.location.reload();
                    }
                }
                );
                $('#modalCobrast').parent().css({
                    top : '40%', 
                    left : '40%'
                });
            },
            error : function ( ) {
                $('#layerOverlay,#layerLoading').hide();
                CargaCarteraDAO.error_ajax();
            }
        });
			
						
    },

    generaSubirCargaLlamada : function(){
        $('#btnCargaLlamadas').upload('../controller/ControllerCobrast.php',
            {
                command :'carga-cartera',
                action  :'subirCargaLlamada',
                usuario_creacion:$('#hdCodUsuario').val()
            },function(obj){
                    if( obj.rst ){
                        $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                        CargaCarteraDAO.setTimeOut_hide_message(); 
                        $('#tmparchivoCargaLlamada').val(obj.archivo);  
                        $('#btnProcesarCargaLlamada').toggle('fast');
                        $('#btnSubirCargaLlamada').css('display','none'); 
                        $('#btnCargaLlamadas').val('');            
                    }else{
                        $('#layerOverlay,#layerLoading').hide(); 
                        $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                        CargaCarteraDAO.setTimeOut_hide_message();
                    }
            },'json'
        )
    },
    generaProcesarCargaLlamada : function(){
        $.ajax({
            url:this.url,
            type:'POST',
            dataType:'json',
            data:{
                command     :'carga-cartera',
                action      :'procesarCargaLlamada',
                usuario_creacion:$('#hdCodUsuario').val(),
                archivo     : $('#tmparchivoCargaLlamada').val(),
                cartera: $('#xcarterames').val()
            },
            beforeSend:function(){
                //$('#layerOverlay,#layerLoading').css('display','block');
                addLoading('panelLoadCallCenter');
            },
            success:function(obj){
                if( obj.rst ){
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message(); 
                    $('#tmparchivoCargaLlamada').val('');  
                    $('#btnProcesarCargaLlamada').toggle('fast');  
                    $('#layerOverlay,#layerLoading').hide();
                    $('.modal-backdrop').parent().remove();    
                    $('#btnSubirCargaLlamada').css('display','block');    
                    //$( "#msj_confirm" ).dialog( "open" );                          
                }else{
                    $('#layerOverlay,#layerLoading').hide(); 
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
                $('#layerOverlay,#layerLoading').hide();
            },
            error:function(){}
        })
    },
    
    generateTablePlanta : function ( ) {
        var xcodigo=$.trim($('#layerTabCargaCarteraPlantaData #codigo option:selected').text());
			
        var xplanta = '['+$("#layerTabCargaCarteraPlantaData select").find("option:selected").not("option[value='0']").map(function(){
            return '{"campoT":"'+$(this).parent().attr('id')+'","dato":"'+$.trim($(this).text())+'"}';
        }).get().join(",")+']';
			
        var xDatosAdicionales = '{'+$('#layerTabCargaCarteraPlantaDatosAdicionales #ca_datos_adicionales_planta').find("option").map(function(index){
					
            return '"dato'+(index+1)+'":"'+$.trim( $(this).text() )+'"';
										
        }).get().join(',')+'}';
			
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command : 'carga-cartera',
                action : 'carga-cartera_planta',
                file : $("#hddFilePlanta").val(),
                separator : $.trim( $('#cbCaracterSeparadorPlanta').val() ),
                Servicio : $('#hdCodServicio').val(),
                NombreServicio : $('#hdNomServicio').val(),
                Campania : $('#cboCampaniaPlanta').val(),
                NombreCartera : $.trim($('#txtNombrePlanta').val()),
                Proceso : $('#cbProcesoPlanta').val(),
                codigo : xcodigo,
                usuario_creacion:$('#hdCodUsuario').val(),
                data_planta : xplanta,
                data_adicionales : xDatosAdicionales
            },
            beforeSend : function ( ) {
                $('#layerOverlay,#layerLoading').css('display','block');
            },
            success : function ( obj ) {
                $('#layerOverlay,#layerLoading').hide();
                if( obj.rst ){
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                //cancel_carga-cartera();
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                $('#layerOverlay,#layerLoading').hide();
                CargaCarteraDAO.error_ajax();
            }
        });
			
						
    },
    cargarProvisionTotal : function ( ) {
        $('#esperando').css({opacity:'1'});
        $('#btnCargarProvisionTotal').attr({'disabled' : true});

        $('#uploadFileProvisionTotal').upload('../controller/ControllerCobrast.php', {
            command : 'carga-cartera',
            action : 'cargaProvisionTotal',
            idTmpFile :'cargaProvisionTotal',
            Servicio : $('#hdCodServicio').val(),
            UsuarioCreacion : $('#hdCodUsuario').val(),
            NombreServicio : $('#hdNomServicio').val(),
            fechaProvision : $('#txtFechaProvisionTotal').val(),
            idCarteras : $('#tbCarterasCargaProvisionTotal').find(':checked').map(function(){return $( this).val() }).get().join(",")
            },function(obj){
                if(obj.rst){
                    $('#esperando').css({opacity:'0'});
                    $('#btnCargarProvisionTotal').removeAttr('disabled');
                    setTimeout(function(){  _displayBeforeSendDl(obj.msg,200); },500);
                   
                }else{
                    _displayBeforeSendDl(obj.msg,300);
                   
                } 

            },'json');


    },
    CargaAutomatica : function ( ) {
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'carga-cartera',
                action:'carga-cartera_automatica',
                file:$("#hddFile").val(),
                separator:$.trim( $('#txtCaracterSeparador').val() ),
                Servicio:$('#hdCodServicio').val(),
                NombreServicio:$('#hdNomServicio').val(),
                Campania:$('#cboCampania').val(),
                NombreCartera:$.trim($('#txtNombreCartera').val()),
                Cartera:$('#cboCarteraActualizar').val(),
                Proceso:$('#cboTipoProceso').val(),
                usuario_creacion:$('#hdCodUsuario').val()
            },
            beforeSend : function ( ) {
                $('#layerOverlay,#layerLoading').css('display','block');
            },
            success : function ( obj ) {
                $('#layerOverlay,#layerLoading').hide();
                if( obj.rst ){
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                    cancel_carga_cartera();
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                $('#layerOverlay,#layerLoading').hide();
                CargaCarteraDAO.error_ajax();
            }
        });
    },
    ListCartera : function ( idCampania, f_fill, idcbo ) {
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'carga-cartera',
                action:'ListCartera',
                Campania:idCampania
            },
            success : function ( obj ) {
                f_fill(obj,idcbo);
            },
            error : function ( ) { 
                CargaCarteraDAO.error_ajax();
            }
        });
    },
    FillCargaCartera : function ( obj, idcbo ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idcartera+'">'+data.nombre_cartera+'</option>';
        });
        for( i=0;i<idcbo.length;i++ ) {
            $('#'+idcbo[i].id).html(html);
        }
        
    },
    FillPagoCartera : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idcartera+'">'+data.nombre_cartera+'</option>';
        });
        $('#cbCartera').html(html);
    },
    FillRetiroCartera : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idcartera+'">'+data.nombre_cartera+'</option>';
        });
        $('#cbCarteraRetiro').html(html);
    },
    FillComisionCartera : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idcartera+'">'+data.nombre_cartera+'</option>';
        });
        $('#AGU_layer_tramo_bottom #cbCarteraComision').html(html);
    },
    FillComisionGenericoCartera : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idcartera+'">'+data.nombre_cartera+'</option>';
        });
        $('#AGU_layer_generico_bottom #cbCarteraComisionGenerico').html(html);
    },
    FillTelefonoCartera : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idcartera+'">'+data.nombre_cartera+'</option>';
        });
        $('#cbCarteraTelefono').html(html);
    },
    FillDetalleCartera : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idcartera+'">'+data.nombre_cartera+'</option>';
        });
        $('#cbCarteraDetalle').html(html);
    },
    FillReclamoCartera : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idcartera+'">'+data.nombre_cartera+'</option>';
        });
        $('#cbCarteraReclamo').html(html);
    },
    FillRRLLCartera : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idcartera+'">'+data.nombre_cartera+'</option>';
        });
        $('#cbCarteraRRLL').html(html);
    },	
    ListarTramo : function ( idCartera ) {
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'carga-cartera',
                action:'ListarTramo',
                Id:idCartera
            },
            beforeSend : function ( ) {
                $('#AGU_layer_tramo_bottom #LayerTableComision').html(templates.IMGloadingContentLayer());
            },
            success : function ( obj ) {
                CargaCarteraDAO.FillTramoComision(obj);
            },
            error : function ( ) {
							
            }
        });
    },
    ListarTramoServicio : function ( ) {
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'carga-cartera',
                action:'ListarTramoServicio',
                IdServicio:$('#hdCodServicio').val()
                },
            beforeSend : function ( ) {
                $('#AGU_layer_tramo_bottom #LayerTableComision').html(templates.IMGloadingContentLayer());
            },
            success : function ( obj ) {
                CargaCarteraDAO.FillTramoComision(obj);
            },
            error : function ( ) {
							
            }
        });
    },
    ListarTramoGenericoServicio : function ( ) {
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'carga-cartera',
                action:'ListarTramoGenerico',
                IdServicio:$('#hdCodServicio').val()
                },
            beforeSend : function ( ) {
                $('#AGU_layer_tramo_bottom #LayerTableComision').html(templates.IMGloadingContentLayer());
            },
            success : function ( obj ) {
                //CargaCarteraDAO.FillTramoComision(obj);
                if( obj.length==0 ) {
                    $('#txtPorcentajeComisionGenerico').val('');
                }else{
                    $('#txtPorcentajeComisionGenerico').val(obj[0].porcentaje_comision);
                }
            },
            error : function ( ) {

            }
        });
    },
    FillTramoComision : function ( obj ) {
        var html='';
        html+='<table cellspacing="0" cellpadding="0" border="0" class="ui-corner-top" >';
        html+='<tr class="ui-state-default" >';
        html+='<td style="width:25px;padding:3px 0;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-right:1px solid #E0CFC2;text-align:center;" ></td>';
        html+='<td style="width:200px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Tramo</td>';
        html+='<td style="width:200px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Porcentaje de comision</td>';
        html+='<td style="width:25px;padding:3px 0;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-right:1px solid #E0CFC2;text-align:center;" ></td>';
        html+='</tr>';
        html+='</table>';
        html+='<div id="DataLayerTableComision" style="height:170px;overflow-x:auto;width:475px;">';
        html+='<table cellspacing="0" cellpadding="0" border="0" >';
        for( i=0;i<obj.length;i++ ){
            html+='<tr id="'+obj[i].tramo+'" class="ui-widget-content" >';
            html+='<td align="center" style="width:25px;padding:3px 0;" class="ui-state-default">'+(i+1)+'</td>';
            html+='<td align="center" style="width:200px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+obj[i].tramo+'</td>';
            html+='<td align="center" style="width:200px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;"><input type="text" value="'+obj[i].porcentaje_comision+'" style="width:50px;" /></td>';
            html+='<td align="center" style="width:25px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;"></td>';
            html+='</tr>';
        }
        html+='</table>';
        html+='<div>';
        $('#AGU_layer_tramo_bottom #LayerTableComision').html(html);
        $('#AGU_layer_tramo_bottom #LayerTableComision #DataLayerTableComision').find(':text').spinner({
            buttons: 'auto'
        });
    },
    save_comision : function ( xdata ) {
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'carga-cartera',
                action:'asignar_comision',
                Cartera : $('#AGU_layer_tramo_bottom #cbCarteraComision').val(),
                data:xdata
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Guardando comision...',250);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if( obj.rst ){
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                //_noneBeforeSend();
                CargaCarteraDAO.error_ajax();
            }
        });
    },
    save_comision_tramo : function ( xdata ) {
		
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'carga-cartera',
                action:'asignar_comision_tramo_servicio',
                Cartera : $('#AGU_layer_tramo_bottom #cbCarteraComision').val(),
                data:xdata,
                IdServicio:$('#hdCodServicio').val(),
                UsuarioModificacion:$('#hdCodUsuario').val()
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Guardando comision...',250);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if( obj.rst ){
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                //_noneBeforeSend();
                CargaCarteraDAO.error_ajax();
            }
        });
			
    },
    save_comision_generico : function ( ) {
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'carga-cartera',
                action:'asignar_comision_generico',
                Cartera : $('#AGU_layer_generico_bottom #cbCarteraComisionGenerico').val(),
                Porcentaje:$('#AGU_layer_generico_bottom #txtPorcentajeComisionGenerico').val()
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Guardando comision...',250);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if( obj.rst ){
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                _noneBeforeSend();
                CargaCarteraDAO.error_ajax();
            }
        });
    },
    save_comision_generico_servicio : function ( ) {
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'carga-cartera',
                action:'asignar_comision_generico_servicio',
                Cartera : $('#AGU_layer_generico_bottom #cbCarteraComisionGenerico').val(),
                Porcentaje:$('#AGU_layer_generico_bottom #txtPorcentajeComisionGenerico').val(),
                IdServicio:$('#hdCodServicio').val(),
                Usuario:$('#hdCodUsuario').val()
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Guardando comision...',250);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if( obj.rst ){
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }else{
                    $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    CargaCarteraDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                _noneBeforeSend();
                CargaCarteraDAO.error_ajax();
            }
        });
    },
    ListarModuloCabecerasPorServicio : function ( xidservicio, f_success, f_before ) {
			
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command : 'carga-cartera', 
                action : 'ListarModuloCabecerasPorServicio', 
                idservicio : xidservicio
            },
            beforeSend : function ( ) {
                f_before();
            },
            success : function ( obj ) {
                f_success(obj);
            },
            error : function ( ) {
                CargaCarteraDAO.error_ajax();
            }
        });
			
    },
    ListarModuloCabecerasPorServicioCartera : function ( xidservicio, f_success, f_before ) {
			
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command : 'carga-cartera', 
                action : 'ListarModuloCabecerasPorServicioCartera', 
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
			
    },
    ListarModuloCabecerasPorServicioPago : function ( xidservicio, f_success, f_before ) {
			
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command : 'carga-cartera', 
                action : 'ListarModuloCabecerasPorServicioPago', 
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
			
    },
    GuardarCabeceras : function ( xidservicio, xcabeceras, xnombre, xtipo, xusuario_creacion, f_success ) {
			
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : { 
                command : 'carga-cartera', 
                action : 'guardar_cabeceras', 
                idservicio : xidservicio, 
                cabeceras : xcabeceras, 
                nombre : xnombre, 
                tipo : xtipo, 
                usuario_creacion : xusuario_creacion 
            },
            beforeSend : function (  ) {
                _displayBeforeSend('Guardando Cabeceras...',250);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                f_success( obj );
            },
            error : function ( ) {
                _noneBeforeSend();
                CargaCarteraDAO.error_ajax();
            }
        });
			
    },
    ActualizarCabeceras : function ( xidcabecera, xcabeceras, xnombre, xtipo, xusuario_modificacion, f_success ) {
			
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : { 
                command : 'carga-cartera', 
                action : 'actualizar_cabeceras', 
                idcabecera : xidcabecera,
                cabeceras : xcabeceras, 
                nombre : xnombre, 
                tipo : xtipo, 
                usuario_modificacion : xusuario_modificacion 
            },
            beforeSend : function (  ) {
                _displayBeforeSend('Actualizando Cabeceras...',250);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                f_success( obj );
            },
            error : function ( ) {
                _noneBeforeSend();
                CargaCarteraDAO.error_ajax();
            }
        });
			
    },
    EliminarCabeceras : function ( xidcabecera, xusuario_modificacion, f_success ) {
			
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command : 'carga-cartera', 
                action : 'eliminar_cabeceras', 
                idcabecera : xidcabecera, 
                usuario_modificacion : xusuario_modificacion
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Eliminando Cabeceras...',250);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                f_success(obj);
            },
            error : function ( ) {
                _noneBeforeSend();
                CargaCarteraDAO.error_ajax();
            }
        });
			
    },
    ListarEstadoLlamadaG : function ( f_success )  {
        
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command : 'atencion_cliente', 
                action : 'ListarEstadoLlamadaG', 
                idservicio : $('#hdCodServicio').val()
            },
            beforeSend : function ( ) {
                
            },
            success : function ( obj ) {
                f_success(obj);
                
            },
            error : function ( ) {
                CargaCarteraDAO.error_ajax();
            }
        });
        
    },
    ListarModuloCabecerasPorId : function ( xidmodulo_cabecera, f_success ) {
			
        $.ajax({
            url : CargaCarteraDAO.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command : 'carga-cartera', 
                action : 'ListarModuloCabecerasPorId', 
                idcabecera : xidmodulo_cabecera
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Listando data...',250);
            },
            success : function ( obj ) {
                f_success(obj);
                _noneBeforeSend();
            },
            error : function ( ) {
                _noneBeforeSend();
                CargaCarteraDAO.error_ajax();
            }
        });
			
    },
    hide_message : function ( ) {
        $('#'+CargaCarteraDAO.idLayerMessage).effect('blind',{
            direction:'vertical'
        },'slow',function(){
            $(this).empty().css('display','block');
        });
    },
    setTimeOut_hide_message : function ( ) {
        setTimeout("CargaCarteraDAO.hide_message()",15000);
    },
    RP3 : {
    	EnviarRespuesta : function ( xtipo, xcarteras, xfecha_inicio, xfecha_fin, f_success ) {
            
            $.ajax({
                    url : CargaCarteraDAO.url,
                    type : 'POST',
                    dataType : 'json',
                    data : {
                        command : 'RP3', 
                        action : 'EnviarRespuesta', 
                        servicio : $('#hdCodServicio').val(),
                        tipo : xtipo,
                        carteras : xcarteras,
                        fecha_inicio : xfecha_inicio,
                        fecha_fin : xfecha_fin
                    },
                    beforeSend : function ( ) {
                        _displayBeforeSend('Procesando data...',250);
                    },
                    success : function ( obj ) {
                        _noneBeforeSend();
                        f_success(obj);
                       
                    },
                    error : function ( ) {
                        _noneBeforeSend();
                        CargaCarteraDAO.error_ajax();
                    }
                });
            
        },
    	BuscarPorFechaEnvio : function ( xfecha_inicio, xfecha_fin, f_success ) {
    		
    		$.ajax({
		            url : CargaCarteraDAO.url,
		            type : 'GET',
		            dataType : 'json',
		            data : {
		                command : 'RP3', 
		                action : 'BuscarPorFechaEnvio', 
		                fecha_inicio : xfecha_inicio,
		                fecha_fin : xfecha_fin
		            },
		            beforeSend : function ( ) {
		                _displayBeforeSend('Buscando data...',250);
		            },
		            success : function ( obj ) {
		                f_success(obj);
		                _noneBeforeSend();
		            },
		            error : function ( ) {
		                _noneBeforeSend();
		                CargaCarteraDAO.error_ajax();
		            }
		        });
    		
	    },
	    CargarDataCartera : function ( xtipo, xdata, f_success ) {
	    	
	    	$.ajax({
		            url : CargaCarteraDAO.url,
		            type : 'POST',
		            dataType : 'json',
		            data : {
		                command : 'RP3',
		                action : 'CargarDataCartera',
		                NombreServicio : $('#hdNomServicio').val(),
		                tipo : xtipo, 
		                data : xdata
		            },
		            beforeSend : function ( ) {
		                _displayBeforeSend('Iniciando data...',250);
		            },
		            success : function ( obj ) {
		                f_success(obj);
		                _noneBeforeSend();
		            },
		            error : function ( ) {
		                _noneBeforeSend();
		                CargaCarteraDAO.error_ajax();
		            }
		        });
	    	
		},
		CargarDataPagos : function ( xtipo, xdata, f_success ) {
	    	
	    	$.ajax({
		            url : CargaCarteraDAO.url,
		            type : 'POST',
		            dataType : 'json',
		            data : {
		                command : 'RP3',
		                action : 'CargarDataPagos',
		                NombreServicio : $('#hdNomServicio').val(),
		                tipo : xtipo, 
		                data : xdata
		            },
		            beforeSend : function ( ) {
		                _displayBeforeSend('Iniciando data...',250);
		            },
		            success : function ( obj ) {
		                f_success(obj);
		                _noneBeforeSend();
		            },
		            error : function ( ) {
		                _noneBeforeSend();
		                CargaCarteraDAO.error_ajax();
		            }
		        });
	    	
	    }    
    	
    },
    EditHeader : {
        
        LoadData : function ( xcartera, f_success ) {
            
            $.ajax({
                    url : CargaCarteraDAO.url,
                    type : 'GET',
                    dataType : 'json',
                    data : { command : 'cartera', action : 'ListarMetaData', cartera : xcartera },
                    beforeSend : function ( ) {},
                    success : function ( obj ) {
                        f_success( obj );
                    },
                    error : function ( ) {}
                    });
            
        }
        
    },
    CruceTelefono : {
        
        Iniciar : function ( xcarteras_fl, xcartera, xtipo, xfecha_inicio, xfecha_fin, f_success ) {
            
            $.ajax({
                    url : CargaCarteraDAO.url,
                    type : 'POST',
                    dataType : 'json',
                    data : { 
                            command : 'carga-cartera', 
                            action : 'CruceTelefonos',
                            carteras_fl : xcarteras_fl,  
                            cartera : xcartera,
                            tipo : xtipo,
                            fecha_inicio : xfecha_inicio,
                            fecha_fin : xfecha_fin,
                            usuario_creacion : $('#hdCodUsuario').val(),
                            servicio : $('#hdCodServicio').val()
                            },
                    beforeSend : function ( ) {
                        _displayBeforeSend('Cruzando telefonos...',300);
                    },
                    success : function ( obj ) {
                        _noneBeforeSend();
                        f_success( obj );
                    },
                    error : function ( ) {
                        _noneBeforeSend();
                        CargaCarteraDAO.error_ajax();
                    }
            });

        }



    },
    UpdateFechaVencimiento : function ( xidcartera, xfec_ven, f_success, f_before ) {
        
        $.ajax({
                url : CargaCarteraDAO.url,
                type : 'POST',
                dataType : 'json',
                data : {
                        command : 'carga-cartera',
                        action : 'update_fec_venc',
                        idcartera : xidcartera,
                        usuario_modificacion : $('#hdCodUsuario').val(),
                        fecha_vencimiento : xfec_ven
                    },
                beforeSend : function ( ) {
                    f_before();
                },
                success : function ( obj ) {
                    f_success( obj );
                },
                error : function ( ) {
                    CargaCarteraDAO.error_ajax();
                }
        });

    },
    error_ajax : function ( ) {
        _noneBeforeSend();
        $('#'+CargaCarteraDAO.idLayerMessage).html(templates.MsgError('Error en ejecucion de proceso','320px'));
        
        /*$('body').after('<div id="modalCobrast">Error en ejecucion de proceso</div>');
        $('#modalCobrast').dialog(
        {
            modal : true,
            title : 'Error',
            buttons : {
                Aceptar : function()
                {
                    $(this).remove();
                }
            },
            close : function(event,ui)
            {
                $(this).remove();
            }
        }
        );
        $('#modalCobrast').parent().css({
            top : '40%', 
            left : '40%'
        });*/
    },
    verificarArchivoPlanoCovinoc : function(file,btn){

        $('#'+file).upload(
            '../controller/ControllerCobrast.php',
            {
                command:'carga-cartera',
                action:'verificarArchivoPlanoCovinoc',
                idTmpFile: file,
                caso : file,
                usuario_creacion : $('#hdCodUsuario').val(),
                servicio : $('#hdCodServicio').val(),
                fecha_proceso : $('#txtFechaProcesoCovinoc').val()
            },
            function(obj){
                if(obj.rst){
                    $('#'+file).attr('disabled','disabled').css('cursor','not-allowed');
                    $('#'+btn).val('Acumulado').attr('disabled','disabled').css('cursor','not-allowed');
                    if(obj.isfile){
                        window.location.href="../documents/carteras/COVINOC/dowload/donwload-covinoc.php";
                    } 
                }else{
                    alert(obj.rst + '  ' + obj.msg);
                     $('#'+btn).val('Acumular')
                }
                
               
            },
            'json'
            );
    },
    updateMontosPagado : function(){

        $.ajax({
            url: CargaCarteraDAO.url,
            type: 'POST',
            dataType : 'json',
            data : {
                command : 'carga-cartera',
                action : 'updateMontosPagado',
                cartera : $('#tbCarterasCargaUpdateMontoPagado').find(':checked').
                        map(function(){
                            if($(this).val()!=25265 && $(this).val()!=25264 && $(this).val()!=25231 && $(this).val()!=25186 && $(this).val()!=25124 &&
                            $(this).val()!=25072 && $(this).val()!=474 && $(this).val()!=453 ){
                                return $(this).val();
                            }
                        
                        }).get().join(','),
                servicio : $('#hdCodServicio').val()
            },
            beforeSend : function(){
                $('#msgUpdateMontoPagado').css('opacity','1').html('<span>Por favor espere..</span>');
            },
            success : function (obj){
                if(obj.rst){
                    $('#msgUpdateMontoPagado').css('opacity','1').html('<span>'+obj.msg+'</span>');
                    setTimeout(function(){
                        $('#msgUpdateMontoPagado').css('opacity','0').html('<span></span>');

                    },1300);
                }else{
                    $('#msgUpdateMontoPagado').css('opacity','1').html('<span>Este servicio no puede realizar esta operaci&oacute;n</span>');
                    setTimeout(function(){
                        $('#msgUpdateMontoPagado').css('opacity','0').html('<span></span>');

                    },1300);
                }

            }
                  
        });
    },
    NormalizarTelefono2 : function(){

        $.ajax({
            url: CargaCarteraDAO.url,
            type: 'POST',
            dataType : 'json',
            data : {
                command : 'carga-cartera',
                action : 'NormalizarTelefono2',
                cartera : $('#tbCarterasCargaNormalizarTelefono2').find(':checked').
                        map(function(){
                            
                                return $(this).val();
                            
                        }).get().join(','),
                servicio : $('#hdCodServicio').val()
            },
            beforeSend : function(){
                $('#msgNormalizarTelefono2').css('opacity','1').html('<span>Por favor espere..</span>');
            },
            success : function (obj){
                if(obj.rst){
                    $('#msgNormalizarTelefono2').css('opacity','1').html('<span>'+obj.msg+'</span>');
                    setTimeout(function(){
                        $('#msgNormalizarTelefono2').css('opacity','0').html('<span></span>');

                    },1300);
                }else{
                    $('#msgNormalizarTelefono2').css('opacity','1').html('<span>Este servicio no puede realizar esta operaci&oacute;n</span>');
                    setTimeout(function(){
                        $('#msgNormalizarTelefono2').css('opacity','0').html('<span></span>');

                    },1300);
                }

            }
                  
        });
    },
    verificarArchivoPlanoPagoSaga : function(file,btn){
        $('#'+file).upload(
            '../controller/ControllerCobrast.php',
            {
                command:'carga-cartera',
                action:'verificarArchivoPlanoPagoSaga',
                idTmpFile: file,
                caso : file,
                usuario_creacion : $('#hdCodUsuario').val(),
                servicio : $('#hdCodServicio').val(),
                cartera : $('#tbCarterasCargaPrepararPagoSaga').find(':checked').
                                map(function(){
                                       return $(this).val();
                                }).get().join(',')
            },
            function(obj){
                if(obj.rst){
                    $('#'+file).attr('disabled','disabled').css('cursor','not-allowed');
                    $('#'+btn).val('Acumulado').attr('disabled','disabled').css('cursor','not-allowed');
                    if(obj.isfile){

                     window.location.href="../documents/carteras/SAGA/dowload/donwload-saga.php";
                    } 
                }else{
                    alert(obj.rst + '  ' + obj.msg);
                     $('#'+btn).val('Acumular');
                }
                
               
            },
            'json'
            );
    },
    Listar_Cartera : function(){
        $.ajax({
            url: CargaCarteraDAO.url,
            type: 'POST',
            dataType : 'json',
            data : {
                command : 'carga-cartera',
                action : 'Listar_Cartera'
            },
            beforeSend : function(){

            },
            success : function (obj){
                if(obj.rst){
                    var xoption="";
                    xoption+='<option value="0">--SELECCIONE--</option>';
                    for(var i=0;i<=obj.data.length-1;i++){
                        // alert(obj.data[i]['nombre_cartera']);
                        xoption+='<option value="'+obj.data[i]['idcartera']+'">'+obj.data[i]['nombre_cartera']+'</option>';
                    }

                    $("#xcarterames").html(xoption);
                }else{
                    
                }

            }
                  
        });
    }

};
