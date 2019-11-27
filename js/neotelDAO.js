var neotelDAO = {
	url : '../controller/ControllerCobrast.php',
	idLayerMessage : 'layerMessage',
	getIdTelefonoCliente:function(telefonox,data,successOk,successFail){
		$.ajax({
		    url : this.url,
		    type:'POST',
		    dataType : 'json',
		    data : {
			    command:'neotel',
			    action:'getIdTelefonoCliente',
			    telefono:telefonox,
			    codCli:$('#CodigoClienteMain').val(),
			    idCartera:$('#IdCartera').val(),
			    idClicar:data
			},
		    beforeSend : function ( ) {
			},
		   	success : function ( obj ) {
		   		if(obj.rst){
		   			successOk(obj.data[0]);
		   		}else{
					successFail(obj);
				}
 		    },
	    	error : this.error_ajax
		    }
	    );
	},
	Logout:function(){
		$.ajax({
		    url : this.url,
		    type:'POST',
		    async:true,
		    dataType : 'json',
		    data : {
			    command:'neotel',
			    action:'Logout',
			    usu_neotel:$('#txtUsuarioN').val()
			},
		    beforeSend : function ( ) {
			},
		   	success : function ( obj ) {
		   		
 		    },
	    	error : this.error_ajax
		    }
	    );
	},
	setCloseContact:function(){
		$.ajax({
		    url : this.url,
		    type:'POST',
		    async:false,
		    dataType : 'json',
		    data : {
			    command:'neotel',
			    action:'setCloseContact',
			    base:$('#txtBaseN').val(),
			    idcontacto:$('#txtIdContactoN').val(),
			},
		    beforeSend : function ( ) {
			},
		   	success : function ( obj ) {
		   		if(obj.rst){
		   			$('#layerMessageNeotel').html(templates.MsgInfo(obj.msg,'250px'));
					$('#layerMessageNeotel').effect('pulsate',{},1500,function(){ $(this).empty(); });
		   		}else{
					$('#layerMessageNeotel').html(templates.MsgError(obj.msg,'300px'));
                    $('#layerMessageNeotel').effect('pulsate',{},1500,function(){ $(this).empty(); });
                }
 		    },
	    	error : this.error_ajax
		    }
	    );
	},
	AddScheduleCall:function(){
		$.ajax({
		    url : this.url,
		    type:'POST',
		    async:false,
		    dataType : 'json',
		    data : {
			    command:'neotel',
			    action:'AddScheduleCall',
			    usu_neotel:$('#txtUsuarioN').val(),
			    base:$('#txtBaseN').val(),
			    idcontacto:$('#txtIdContactoN').val(),
			    data:$('#txtDataN').val(),
			    telefono:$('#tagNumLlamN').html(),
			    fecha_agenda:$('#txtFechaAgendaN').val()
			},
		    beforeSend : function ( ) {
			},
		   	success : function ( obj ) {
		   		if(obj.rst){
		   			$('#layerMessageNeotel').html(templates.MsgInfo(obj.msg,'250px'));
					$('#layerMessageNeotel').effect('pulsate',{},1500,function(){ $(this).empty(); });
		   		}else{
					$('#layerMessageNeotel').html(templates.MsgError(obj.msg,'300px'));
                    $('#layerMessageNeotel').effect('pulsate',{},1500,function(){ $(this).empty(); });
                }
 		    },
	    	error : this.error_ajax
		    }
	    );
	},
	AddScheduleCallTelefono:function(){
		$.ajax({
		    url : this.url,
		    type:'POST',
		    async:false,
		    dataType : 'json',
		    data : {
			    command:'neotel',
			    action:'AddScheduleCall',
			    usu_neotel:$('#txtUsuarioN').val(),
			    base:$('#txtBaseN').val(),
			    idcontacto:$('#txtIdContactoN').val(),
			    data:$('#txtDataN').val(),
			    telefono:$('#cboNumeroAlertaTelefono').val(),
			    fecha_agenda:$('#txtFechaAgendaN').val()
			},
		    beforeSend : function ( ) {
			},
		   	success : function ( obj ) {
		   		if(obj.rst){
		   			$('#layerMessageNeotel').html(templates.MsgInfo(obj.msg,'250px'));
					$('#layerMessageNeotel').effect('pulsate',{},1500,function(){ $(this).empty(); });
		   		}else{
					$('#layerMessageNeotel').html(templates.MsgError(obj.msg,'300px'));
                    $('#layerMessageNeotel').effect('pulsate',{},1500,function(){ $(this).empty(); });
                }
 		    },
	    	error : this.error_ajax
		    }
	    );
	},	
	ponerShowingContact:function(basex,idcontactox,datax,success){
		$.ajax({
		    url : this.url,
		    type:'POST',
		    dataType : 'json',
		    data : {
			    command:'neotel',
			    action:'setShowingContact',
			    usu_neotel:$('#txtUsuarioN').val(),
			    base:basex,
			    idcontacto:idcontactox,
			    data:datax
			},
		    beforeSend : function ( ) {
			},
		   	success : function ( obj ) {
		   		if(obj.rst){
		   			success();
		   		}else{
					$('#'+neotelDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
					$('#'+neotelDAO.idLayerMessage).effect('pulsate',{},1500,function(){ $(this).empty(); });
				}
 		    },
	    	error : this.error_ajax
		    }
	    );
	},
	ponerCrmUnAvailable:function(){
		$.ajax({
		    url : this.url,
		    type:'POST',
		    dataType : 'json',
		    data : {
			    command:'neotel',
			    action:'setCrmUnAvailable',
			    usu_neotel:$('#txtUsuarioN').val()
			    },
		    beforeSend : function ( ) {
			},
		   	success : function ( obj ) {
		   		if(obj.rst){
				}else{
					$('#'+neotelDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
					$('#'+neotelDAO.idLayerMessage).effect('pulsate',{},1500,function(){ $(this).empty(); });
				}
 		    },
	    	error : this.error_ajax
		    }
	    );
	},
	ponerCrmAvailable:function(success){
		$.ajax({
		    url : this.url,
		    type:'POST',
		    dataType : 'json',
		    data : {
			    command:'neotel',
			    action:'setCrmAvailable',
			    usu_neotel:$('#txtUsuarioN').val()
			    },
		    beforeSend : function ( ) {
			},
		   	success : function ( obj ) {
		   		if(obj.rst){
		   			success();
				}else{
					$('#'+neotelDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
					$('#'+neotelDAO.idLayerMessage).effect('pulsate',{},1500,function(){ $(this).empty(); });
				}
 		    },
	    	error : this.error_ajax
		    }
	    );
	},
	ponerUnPausa:function(){
		$.ajax({
		    url : this.url,
		    type:'POST',
		    dataType : 'json',
		    data : {
			    command:'neotel',
			    action:'setUnPause',
			    usu_neotel:$('#txtUsuarioN').val()
			    },
		    beforeSend : function ( ) {
			},
		   	success : function ( obj ) {
		   		if(obj.rst){
				}else{
					$('#'+neotelDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
					$('#'+neotelDAO.idLayerMessage).effect('pulsate',{},1500,function(){ $(this).empty(); });
				}
 		    },
	    	error : this.error_ajax
		    }
	    );
	},
	ponerPausa:function(idsubtipo_descansox){
        desc_std={"0":"...","1":"Descanso","2":"Tiempo Administrativo","3":"Capacitacion",
            "8":"SSHH","9":"Consulta Supervisor","10":"FeedBack","11":"Topico","12":"Almuerzo","13":"Esperando Contingencia","4":"Break"};//desc_subtipodescanso            		
		$.ajax({
		    url : this.url,
		    type:'POST',
		    dataType : 'json',
		    data : {
			    command:'neotel',
			    action:'setPause',
			    usu_neotel:$('#txtUsuarioN').val(),
			    idsubtipo_descanso:idsubtipo_descansox
			    },
		    beforeSend : function ( ) {
			},
		   	success : function ( obj ) {
		   		if(obj.rst){
		   			$('#tagPausaN').html(desc_std[idsubtipo_descansox]);
				}else{
					$('#'+neotelDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
					$('#'+neotelDAO.idLayerMessage).effect('pulsate',{},1500,function(){ $(this).empty(); });
				}
 		    },
	    	error : this.error_ajax
		    }
	    );
	},
	setDial:function(){
		$.ajax({
		    url : this.url,
		    type:'POST',
		    dataType : 'json',
		    data : {
			    command:'neotel',
			    action:'setDial',
			    usu_neotel:$('#txtUsuarioN').val(),
			    numero : $.trim( $('#txtAtencionClienteNumeroCall').val() )
			    },
		    beforeSend : function ( ) {
			},
		   	success : function ( objDial ) {
		   		if(objDial.rst){
					neotelDAO.getPosition(function(obj){
				        idllamada=obj['IDLLAMADA'];
				        $('#txtIdLlamadaN').val(idllamada);
    				})		   			
				}else{
					$('#'+neotelDAO.idLayerMessage).html(templates.MsgInfo(objDial.msg,'250px'));
					$('#'+neotelDAO.idLayerMessage).effect('pulsate',{},1500,function(){ $(this).empty(); });
				}
 		    },
	    	error : this.error_ajax
		    }
	    );
	},
	setDialManual:function(){//para gestion manual bsucando cliente y llamando
		$.ajax({
		    url : this.url,
		    type:'POST',
		    dataType : 'json',
		    data : {
			    command:'neotel',
			    action:'setDial',
			    usu_neotel:$('#txtUsuarioManualNeotel').val(),
			    numero : $.trim( $('#txtAtencionClienteNumeroCall').val() )
			    },
		    beforeSend : function ( ) {
			},
		   	success : function ( objDial ) {
		   		if(objDial.rst){
		   			setTimeout(function(){
			           neotelDAO.getPositionManual(function(obj){
							        idllamada=obj['IDLLAMADA'];
							        $('#txtIdLlamadaN').val(idllamada);
			    				},'txtUsuarioManualNeotel')
			           },2000);
					
					setTimeout(function(){
					           neotelDAO.getPositionManual(function(obj){
									        idllamada=obj['IDLLAMADA'];
									        $('#txtIdLlamadaN').val(idllamada);
					    				},'txtUsuarioManualNeotel')
					           },4000);
					
				}else{
					$('#'+neotelDAO.idLayerMessage).html(templates.MsgInfo(objDial.msg,'250px'));
					$('#'+neotelDAO.idLayerMessage).effect('pulsate',{},1500,function(){ $(this).empty(); });
				}
 		    },
	    	error : this.error_ajax
		    }
	    );
	},	
	setHungup:function(){
		$.ajax({
		    url : this.url,
		    type:'POST',
		    dataType : 'json',
		    data : {
			    command:'neotel',
			    action:'setHungup',
			    usu_neotel:$('#txtUsuarioN').val()
			    },
		    beforeSend : function ( ) {
			},
		   	success : function ( obj ) {
		   		if(obj.rst){
				}else{
					$('#'+neotelDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
					$('#'+neotelDAO.idLayerMessage).effect('pulsate',{},1500,function(){ $(this).empty(); });
				}
 		    },
	    	error : this.error_ajax
		    }
	    );
	},		
	ponerLogoutCampania:function(){
		$.ajax({
		    url : this.url,
		    type:'POST',
		    dataType : 'json',
		    data : {
			    command:'neotel',
			    action:'setLogoutCampania',
			    usu_neotel:$('#txtUsuarioN').val()
			    },
		    beforeSend : function ( ) {
			},
		   	success : function ( obj ) {
		   		if(obj.rst){
				}else{
					$('#'+neotelDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
					$('#'+neotelDAO.idLayerMessage).effect('pulsate',{},1500,function(){ $(this).empty(); });
				}
 		    },
	    	error : this.error_ajax
		    }
	    );
	},
	ponerCampania:function(idcampaniax){
		$.ajax({
		    url : this.url,
		    type:'POST',
		    dataType : 'json',
		    data : {
			    command:'neotel',
			    action:'setCampania',
			    usu_neotel:$('#txtUsuarioN').val(),
			    idcampania:idcampaniax
			    },
		    beforeSend : function ( ) {
			},
		   	success : function ( obj ) {
		   		if(obj.rst){
		   			

				
				}else{
					$('#'+neotelDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
					$('#'+neotelDAO.idLayerMessage).effect('pulsate',{},1500,function(){ $(this).empty(); });
					$('#isNeotel').html('');
					$('#btnAtencionClientePhoneCallNeotel,#btnAtencionClientePhoneHungupNeotel').css('display','none');
					$('#btnAtencionClientePhoneCall,#btnAtencionClientePhoneHungup').css('display','block');
					
				}
 		    },
	    	error : this.error_ajax
		    }
	    );
	},
	ponerCampaniaManual:function(idcampaniax, etiqueta_valor ){
		$.ajax({
		    url : this.url,
		    type:'POST',
		    dataType : 'json',
		    data : {
			    command:'neotel',
			    action:'setCampania',
			    usu_neotel:$('#'+etiqueta_valor).val(),
			    idcampania:idcampaniax
			    },
		    beforeSend : function ( ) {
			},
		   	success : function ( obj ) {
		   		if(obj.rst){
		   			

				
				}else{
					$('#'+neotelDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
					$('#'+neotelDAO.idLayerMessage).effect('pulsate',{},1500,function(){ $(this).empty(); });
					$('#isNeotel').html('');
					$('#btnAtencionClientePhoneCallNeotel,#btnAtencionClientePhoneHungupNeotel').css('display','none');
					$('#btnAtencionClientePhoneCall,#btnAtencionClientePhoneHungup').css('display','block');
					
				}
 		    },
	    	error : this.error_ajax
		    }
	    );
	},
	getPosition:function(success){
		$.ajax({
		    url : this.url,
		    type:'POST',
		    dataType : 'json',
		    data : {
			    command:'neotel',
			    action:'getPosition',
			    usu_neotel:$('#txtUsuarioN').val()
			    },
		    beforeSend : function ( ) {
			},
		   	success : function ( obj ) {
		   		if(obj.rst){
					success(obj.data);
				}else{
					$('#'+neotelDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
					$('#'+neotelDAO.idLayerMessage).effect('pulsate',{},1500,function(){ $(this).empty(); });

					$('#tagStatusN').html('...');
			        $('#tagAnexoN').html('...');
			        $('#tagCampaniaN').html('...');
			        $('#tagPausaN').html('...');
			        $('#tagEstadoCrmN').html('...');
			        $('#tagNumLlamN').html('...');
				}
 		    },
	    	error : this.error_ajax
		    }
	    );
	},
	getPositionManual:function(success, etiqueta_valor){
		$.ajax({
		    url : this.url,
		    type:'POST',
		    dataType : 'json',
		    data : {
			    command:'neotel',
			    action:'getPosition',
			    usu_neotel:$('#'+etiqueta_valor).val()
			    },
		    beforeSend : function ( ) {
			},
		   	success : function ( obj ) {
		   		if(obj.rst){
					success(obj.data);
				}else{
					$('#'+neotelDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
					$('#'+neotelDAO.idLayerMessage).effect('pulsate',{},1500,function(){ $(this).empty(); });

					$('#tagStatusN').html('...');
			        $('#tagAnexoN').html('...');
			        $('#tagCampaniaN').html('...');
			        $('#tagPausaN').html('...');
			        $('#tagEstadoCrmN').html('...');
			        $('#tagNumLlamN').html('...');
				}
 		    },
	    	error : this.error_ajax
		    }
	    );
	},
	getStatus : function (success) {
		$.ajax({
		    url : this.url,
		    type:'POST',
		    dataType : 'json',
		    data : {
			    command:'neotel',
			    action:'getstatus',
			    usu_neotel:$('#txtUsuarioN').val()
			    },
		    beforeSend : function ( ) {
				_displayBeforeSend('verificando STATUS NEOTEL',320);
		    },
		   	success : function ( obj ) {
		   		_noneBeforeSend();
				if(obj.rst){
					success();
				}else{
					$('#'+neotelDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
					$('#'+neotelDAO.idLayerMessage).effect('pulsate',{},1500,function(){ $(this).empty(); });
				}
 		    },
	    	error : this.error_ajax
		    }
	    );
	},
	getStatusByUser : function (success, etiqueta_valor) {
		$.ajax({
		    url : this.url,
		    type:'POST',
		    dataType : 'json',
		    data : {
			    command:'neotel',
			    action:'getstatus',
			    usu_neotel:$('#'+etiqueta_valor).val()
			    },
		    beforeSend : function ( ) {
				_displayBeforeSend('verificando STATUS NEOTEL',320);
		    },
		   	success : function ( obj ) {
		   		_noneBeforeSend();
				if(obj.rst){
					success();
				}else{
					$('#'+neotelDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
					$('#'+neotelDAO.idLayerMessage).effect('pulsate',{},1500,function(){ $(this).empty(); });
				}
 		    },
	    	error : this.error_ajax
		    }
	    );
	},
	error_ajax : function ( ) {
		_noneBeforeSend();
		$('#'+neotelDAO.idLayerMessage).html(templates.MsgError('Error en el servidor','200px'));
		$('#'+neotelDAO.idLayerMessage).effect('pulsate',{},1500,function(){ $(this).empty(); });
	},
	showDataCliente:function(datax,idserviciox){
		//~ Vic I
		$.ajax({
		    url : this.url,
		    type:'POST',
		    dataType : 'json',
		    data : {
				command:'neotel',
				action:'setShowDataCliente',
				idservicio:idserviciox,
				data:datax
			},
		   	success : function ( obj ) {
		   		if(obj.rst){
					$('#lbMessageGlobalGest').text(obj.msg[0]["nombre"]);
					$('#lbMessageGlobalGest').slideDown();
		   		}else{
					$('#'+neotelDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
					$('#'+neotelDAO.idLayerMessage).effect('pulsate',{},1500,function(){ $(this).empty(); });
				}
 		    },
	    	error : this.error_ajax
		});
	}
}
