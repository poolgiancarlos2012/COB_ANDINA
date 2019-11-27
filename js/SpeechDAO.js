var SpeechDAO = {
		url : '../controller/ControllerCobrast.php',
		idLayerMessage : 'layerMessage',
		Upload : function ( ) {
				$('#layerOverlay,#layerLoading').css('display','block');
				$('#uploadFileSpeech').upload(
											'../controller/ControllerCobrast.php',
											{
											command:'speech',
											action:'upload',
											Servicio:$('#hdCodServicio').val(),
											Nombre:$.trim( $('#txtNombreAyudaGestionNoText').val() ),
											UsuarioCreacion:$('#hdCodUsuario').val(),
											NombreServicio:$('#hdNomServicio').val(),
											TipoAyudaGestion:$('#cbTipoAyudaGestion').val()
											},
											function(obj){
												$('#layerOverlay,#layerLoading').hide(); 
												//alert(obj);
												if( obj.rst ){
													$('#'+SpeechDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
													$('#'+SpeechDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });
													SpeechDAO.ListadoSpeech();
												}else{
													$('#'+SpeechDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
													$('#'+SpeechDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });
												}
											},
											'json'
											);
			},
		LoadTipoAyudaGestion : function ( ) {
				$.ajax({
						url : this.url,
						type : 'GET',
						dataType : 'json',
						data : {command:'speech',action:'LoadTipoAyudaGestion'},
						beforeSend : function ( ) {
								$('#cbTipoAyudaGestion').html(templates.IMGloadingContent());
							},
						success : function ( obj ) {
								SpeechDAO.FillTipoAyudaGestion(obj);
								SpeechDAO.FillTipoAyudaGestionModoTexto(obj);
							},
						error : function ( ) {
								SpeechDAO.error_ajax();
							}
					});
			},
		FillTipoAyudaGestion : function ( obj ) {
				var html='';
					html+='<option value="0" >--Seleccione--</option>';
				$.each(obj,function( key,data ){
					html+='<option value="'+data.idtipo_ayuda_gestion+'" >'+data.nombre+'</option>';
				});
				$('#cbTipoAyudaGestion').html(html);
			},
		FillTipoAyudaGestionModoTexto : function ( obj ) {
				var html='';
					html+='<option value="0" >--Seleccione--</option>';
				$.each(obj,function( key,data ){
					html+='<option value="'+data.idtipo_ayuda_gestion+'" >'+data.nombre+'</option>';
				});
				$('#cbTipoAyudaGestionModoTexto').html(html);
			},
		ListadoSpeech : function ( ) {
				$.ajax({
						url : this.url,
						type : 'GET',
						dataType : 'json',
						data : {command:'speech',action:'ListarSpeech',Servicio:$('#hdCodServicio').val()},
						beforeSend : function ( ) {
								$('#ListDownloadAyudaGestion').html(templates.IMGloadingContentTable());
							},
						success : function ( obj ) {
								var html='';
									html+='<tr>';
										html+='<td><div class="ui-widget-header ui-corner-all" style="width:250px;padding:2px 5px;" align="center">Nombre</div></td>';
										html+='<td><div class="ui-widget-header ui-corner-all" style="width:120px;padding:2px 5px;" align="center">Fecha Creacion</div></td>';
										html+='<td><div class="ui-widget-header ui-corner-all" style="width:200px;padding:2px 5px;" align="center">Tipo Ayuda Gestion</div></td>';
										html+='<td><div class="ui-widget-header ui-corner-all" style="width:60px;height:18px;" align="center"></div></td>';
									html+='<tr>';
								$.each(obj,function( key,data){
									var nombre=data.ruta.split('/');
									html+='<tr id="'+data.idayuda_gestion+'" >';
										 html+='<td align="center" ><a href="'+data.ruta+'">'+nombre[3]+'</a></td>';
										 html+='<td align="center" >'+data.fecha_creacion+'</td>';
										 html+='<td align="center" >'+data.tipo_ayuda_gestion+'</td>';
										 html+='<td align="center"><button onclick="read_file('+data.idayuda_gestion+')" class="ui-state-default ui-corner-all" ><span class="ui-icon ui-icon-search"></span></button></td>';
									html+='</tr>';
								});
								$('#ListDownloadAyudaGestion').html(html);
							},
						error : this.error_ajax
						});
			},
		save_speech_modo_texto : function ( ) {
				$.ajax({
						url : SpeechDAO.url,
						type : 'POST',
						dataType : 'json',
						data : {command:'speech',
								action:'GuardarSpeechModoTexto',
								Nombre:$.trim( $('#txtNombreAyudaGestionModoTexto').val() ),
								Texto:$('#txtRichTextSpeech').html(),
								TipoAyudaGestion:$('#cbTipoAyudaGestionModoTexto').val(),
								Servicio:$('#hdCodServicio').val(),
								UsuarioCreacion:$('#hdCodUsuario').val()
								},
						beforeSend : function ( ) {
								_displayBeforeSend('Guardando Speech...',320);
							},
						success : function ( obj ) {
								_noneBeforeSend();
								if( obj.rst ){
									SpeechDAO.listar_speech_is_text();
									$('#'+SpeechDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
									SpeechDAO.setTimeOut_hide_message();
									cancel_speech_modo_texto();
								}else{
									$('#'+SpeechDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
									SpeechDAO.setTimeOut_hide_message();
								}
							},
						error : function ( ) {
								_noneBeforeSend();
								SpeechDAO.error_ajax();
							}
					});
			},
		update_speech_modo_texto : function ( ) {
				$.ajax({
						url : SpeechDAO.url,
						type : 'POST',
						dataType : 'json',
						data : {
								command:'speech',
								action:'UpdateSpeechModoTexto',
								Id:$('#hdIdSpeechIsText').val(),
								Nombre:$.trim( $('#txtNombreAyudaGestionModoTexto').val() ),
								Texto:$('#txtRichTextSpeech').html(),
								TipoAyudaGestion:$('#cbTipoAyudaGestionModoTexto').val(),
								UsuarioModificacion:$('#hdCodUsuario').val()
								},
						beforeSend : function ( ) {
								_displayBeforeSend('Actualizando Speech...',320);
							},
						success : function ( obj ) {
								_noneBeforeSend();
								if( obj.rst ){
									SpeechDAO.listar_speech_is_text();
									$('#'+SpeechDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
									SpeechDAO.setTimeOut_hide_message();
									cancel_speech_modo_texto();
								}else{
									$('#'+SpeechDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
									SpeechDAO.setTimeOut_hide_message();
								}
							},
						error : function ( ) {
								_noneBeforeSend();
								SpeechDAO.error_ajax();
							}
					});
			},
		listar_speech_is_text : function ( ) {
				$.ajax({
						url : this.url,
						type : 'GET',
						dataType : 'json',
						data : {command:'speech',action:'ListarSpeechIsText',Servicio:$('#hdCodServicio').val()},
						beforeSend : function ( ) {
								$('#ListAyudaGestionIsText').html(templates.IMGloadingContentTable());
							},
						success : function ( obj ) {
								var html='';
									html+='<tr>';
										html+='<td style="width:140px;"><div class="ui-widget-header ui-corner-all" style="padding:2px 0;" align="center">Nombre</div></td>';
										html+='<td style="width:140px;"><div class="ui-widget-header ui-corner-all" style="padding:2px 0;" align="center">Fecha Creacion</div></td>';
										html+='<td style="width:140px;"><div class="ui-widget-header ui-corner-all" style="padding:2px 0;" align="center">Tipo Ayuda</div></td>';
										html+='<td></td>';
										html+='<td></td>';
									html+='</tr>';
								$.each(obj,function( key,data){
									html+='<tr id="'+data.idayuda_gestion+'" >';
										 html+='<td align="center" style="width:140px;" >'+data.nombre+'</td>';
										 html+='<td align="center" style="width:140px;" >'+data.fecha_creacion+'</td>';
										 html+='<td align="center" style="width:140px;" id="'+data.idtipo_ayuda_gestion+'" >'+data.tipo_ayuda_gestion+'</td>';
										 html+='<td ><button onclick="read_text('+data.idayuda_gestion+')" class="ui-state-default ui-corner-all" ><span class="ui-icon ui-icon-search"></span></button></td>';
										 html+='<td ><button onclick="getParamUpdate('+data.idayuda_gestion+')" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-pencil"></span></button></td>';
									html+='</tr>';
								});
								$('#ListAyudaGestionIsText').html(html);
							},
						error : this.error_ajax
						});
			},
		read_file : function ( xaction, idSpeech, f_fill ) {
				$.ajax({
						url : SpeechDAO.url,
						type : 'POST',
						dataType : 'json',
						data : {command:'speech',action:xaction,Id:idSpeech},
						beforeSend : function ( ) {
								_displayBeforeSend('Trayendo data...',320);
							},
						success : function ( obj ) {
								_noneBeforeSend();
								f_fill(obj);
							},
						error : function ( ) {
								_noneBeforeSend();
							}
					});
			},
		show_read_file : function ( obj ) {
				var html='';
				for(  i=0;i<obj.msg.length;i++ ) {
					html+='<span>'+obj.msg[i].line+'</span><br>';
				}
				$('#DataReadFileAndText #DataSpeechArgument').html(html);
				$('#DataReadFileAndText').dialog('open');
			},
		show_read_text : function ( obj ) {
				if( obj.length>0 ){
					$('#DataReadFileAndText #DataSpeechArgument').html(obj[0]['texto']);
					$('#DataReadFileAndText').dialog('open');
				}
			},
		DataText : function ( idSpeech ) {
				$.ajax({
						url : SpeechDAO.url,
						type : 'POST',
						dataType : 'json',
						data : {command:'speech',action:'DataText',Id:idSpeech},
						beforeSend : function ( ) {
								_displayBeforeSend('Trayendo data...',320);
							},
						success : function ( obj ) {
								_noneBeforeSend();
								if( obj.length>0 ) {
									$('#hdIdSpeechIsText').val(obj[0].idayuda_gestion); 
									$('#txtNombreAyudaGestionModoTexto').val(obj[0].nombre); 
									$('#cbTipoAyudaGestionModoTexto').val(obj[0].idtipo_ayuda_gestion);   
									$('#txtRichTextSpeech').tinymce().execCommand('mceSetContent',false,obj[0].texto);
								}
							},
						error : function ( ) {
								_noneBeforeSend();
							}
					});
			},
		hide_message : function ( ) {
				$('#'+SpeechDAO.idLayerMessage).effect('blind',{direction:'vertical'},'slow',function(){ $(this).empty().css('display','block'); });
			
			},
		setTimeOut_hide_message : function ( ) {
				setTimeout("SpeechDAO.hide_message()",4000);
			},
		error_ajax : function ( ) {
				$('#'+SpeechDAO.idLayerMessage).html(templates.MsgError('Error en el servidor','200px'));
				$('#'+SpeechDAO.idLayerMessage).effect('pulsate',{},'normal',function () { $(this).empty(); });
			}
	
	};