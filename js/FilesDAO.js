var FilesDAO = {
	url : '../controller/ControllerCobrast.php',
	doPost : function ( xcommand , xaction, xdata , f_before, f_success, f_error ) {
		
		$.ajax(
			{
				url : this.url,
				type : 'POST',
				dataType : 'json',
				data : { 
						command : xcommand, 
						action : xaction, 
						data : xdata 
						},
				beforeSend : function ( ) 
					{
						if( $.isFunction(f_before) ) {
							f_before();
						}
					},
				success : function ( obj )
					{
						if( $.isFunction(f_success) ) {
							f_success(obj);
						}
					},
				error : function ( ) 
					{
						if( $.isFunction(f_error) ) {
							f_error();
						}
					}
			}
			);

	},
	doGet : function ( xcommand , xaction, xdata, f_before, f_success, f_error ) {
		
		$.ajax(
			{
				url : this.url,
				type : 'GET',
				dataType : 'json',
				data : { 
						command : xcommand, 
						action : xaction, 
						data : xdata 
						},
				beforeSend : function ( ) 
					{
						if( $.isFunction(f_before) ) {
							f_before();
						}
					},
				success : function ( obj )
					{
						if( $.isFunction(f_success) ) {
							f_success(obj);
						}
					},
				error : function ( ) 
					{
						if( $.isFunction(f_error) ) {
							f_error();
						}
					}
			}
			);

	},
	read_directory : function ( xrouter, xdirectorio ) {
		
		var xnombre_servicio = $('#hdNomServicio').val();
		
		this.doGet(
					"files",
					"read_directory",
					{ nombre_servicio : xnombre_servicio, router : xrouter, directorio : xdirectorio },
					function(){},
					function( obj )
						{
							var html = '';
							if( obj.rst ) {
								
								for( i=0;i<obj.data.length;i++ ){
									if( obj.data[i].tipo == 'dir' ) {
										html+='<li ondblclick="read_directory(\''+obj.data[i].nombre+'\')" title="'+obj.data[i].nombre+'" class="ui-state-default ui-corner-all" style="float:left;cursor:pointer;position:relative;padding:4px 0;margin:2px;list-style:none outside none;">';
									}else{
										html+='<li title="'+obj.data[i].nombre+'" class="ui-state-default ui-corner-all" style="float:left;cursor:pointer;position:relative;padding:4px 0;margin:2px;list-style:none outside none;">';
									}
									//html+='<li title="'+obj.data[i].nombre+'" class="ui-state-default ui-corner-all" style="float:left;cursor:pointer;position:relative;padding:4px 0;margin:2px;list-style:none outside none;">';
										html+='<table>';
											html+='<tr>';
												if( obj.data[i].tipo == 'dir' ) {
													html+='<td onclick="delete_directory(\''+obj.data[i].nombre+'\')" >';
												}else{
													html+='<td onclick="delete_file(\''+obj.data[i].nombre+'\')" >';
												}
													html+='<span title="ELIMINAR" class="ui-icon ui-icon-trash"></span>';
												html+='</td>';
												if( obj.data[i].tipo != 'dir' ) {
													html+='<td onclick="download_file(\''+obj.data[i].nombre+'\')" >';
													html+='<span title="DESCARGAR" class="ui-icon ui-icon-arrowstop-1-s"></span>';
												}else{
													html+='<td>';
												}
													
												html+='</td>';
											html+='</tr>';
											html+='<tr>';
												html+='<td style="width:130px;">';
													if( obj.data[i].tipo == 'doc' || obj.data[i].tipo == 'docx' ) {
														html+='<img src="../img/1316219906_Office Word.png" />';
													}else if( obj.data[i].tipo == 'xls' || obj.data[i].tipo == 'xlsx' ){
														html+='<img src="../img/1316219957_Office Excel.png" />';
													}else if( obj.data[i].tipo == 'jpeg' || obj.data[i].tipo == 'jpg' || obj.data[i].tipo == 'gif' || obj.data[i].tipo == 'png' ) {
														html+='<img src="../img/1316219986_iPhoto.png" />';
													}else if( obj.data[i].tipo == 'pptx' || obj.data[i].tipo == 'ppt' || obj.data[i].tipo == 'pps' ) {
														html+='<img src="../img/1316220187_ppt.png" />';
													}else if( obj.data[i].tipo == 'ogg' || obj.data[i].tipo == 'wav' || obj.data[i].tipo == 'flac' || obj.data[i].tipo == 'gsm' || obj.data[i].tipo == 'au' || obj.data[i].tipo == 'mp4' || obj.data[i].tipo == 'wma' ){
														html+='<img src="../img/1316219970_file_document.png" />';
													}else if( obj.data[i].tipo == 'avi' || obj.data[i].tipo == 'mov' || obj.data[i].tipo == 'ogm' || obj.data[i].tipo == 'mpeg' ) {
														html+='<img src="../img/1316219999_camera.png" />';
													}else if( obj.data[i].tipo == 'pdf' ) {
														html+='<img src="../img/1316219943_pdf.png" />';
													}else if( obj.data[i].tipo == 'rar' || obj.data[i].tipo == 'zip' || obj.data[i].tipo == 'gzip' || obj.data[i].tipo == 'tar' ) {
														html+='<img src="../img/1316220048_zip2.png" />';
													}else if( obj.data[i].tipo == 'dir' ) {
														html+='<img src="../img/1316224460_Directory.png" />';
													}else if( obj.data[i].tipo == 'txt' ){
														html+='<img src="../img/1316220033_edit.png" />';
													}else if( obj.data[i].tipo == 'mp3' ){
														html+='<img src="../img/1316220215_document_mp3.png" />';
													}else{
														html+='<img src="../img/1316220025_unknown.png" />';
													}
												html+='<td>';
											html+='</tr>';
											html+='<tr>';
												html+='<td align="center">';
													if( (obj.data[i].nombre).length>14 ) {
														html+=(obj.data[i].nombre).substring(0,11)+'...';
													}else{
														html+=obj.data[i].nombre;
													}
												html+='<td>';
											html+='</tr>';
										html+='</table>';
									html+='</li>';
								}
								$('#table_directory').html(html);
								$('#table_directory li').hover(function(){ $(this).addClass('ui-state-hover'); },function(){ $(this).removeClass('ui-state-hover'); });

								var router = xrouter + xdirectorio + '/';
								
								$('#router_directory').val(router);
								$('#lb_router_directory').text(router);
								if( router == '/' ) {
									$('#layer_back_directory').css('display','none');
								}else{
									$('#layer_back_directory').css('display','block');
								}

							}

						},
					function( ){ 
						FilesDAO.Message.Error("Error en ejecucion de proceso");
					}
				);
	},
	Message : {
		Error : function ( msg ) {
				$('#layerMessage').html(templates.MsgError(msg,'500px'));
				FilesDAO.Message.Effect();
			},
		Info : function ( msg ) {
				$('#layerMessage').html(templates.MsgInfo(msg,'500px'));
				FilesDAO.Message.Effect();
			},
		Effect : function ( ) {
				$('#layerMessage').fadeIn().delay(8000).fadeOut();
			}
	}
}
