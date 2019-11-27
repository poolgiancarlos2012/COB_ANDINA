// JavaScript Document
var ReporteDAO= {
		url:'../controller/ControllerCobrast.php',
		idLayerMessage : 'layerMessage',
		ListarCampania : function( ) {
			$.ajax({
					url : ReporteDAO.url,
					type : 'GET',
					dataType : 'json',
					data : {
							command:'atencion_cliente',action:'ListarCampanias',Servicio:$('#hdCodServicio').val()
						},
					success : function ( obj ) {
							ReporteDAO.FillCampania(obj);
						},
					error : function ( ) {
							ReporteDAO.error_ajax();
						}
				});
		},
		ListarCarteraHistory : function( ) {
			//~ Vic I
			$.ajax({
					url : ReporteDAO.url,
					type : 'GET',
					dataType : 'json',
					data : {
							command:'atencion_cliente',action:'ListarCarteraHistory',Servicio:$('#hdCodServicio').val(),
							idcartera : $('#tbRKCartera_divReportes').find(':checked').map(function(){return this.value;}).get().join(",")							
						},
					success : function ( obj ) {
							var html='';
								html+='<option value="0">--Seleccione--</option>';
								$.each(obj['fecha_proceso'],function(key,data){
									html+='<option value="'+data.Fproceso+'">'+data.Fproceso+'</option>';
								});
							$('#sltCliNewRetIni, #sltCliNewRetFin, #sltInfoCartera, #sltProcesoCober').html(html);
							var html2='';
								html2+='<option value="0">--Seleccione--</option>';
								$.each(obj['agencias'],function(key,data){
									html2+='<option value="'+data.agencia+'">'+data.agencia+'</option>';
								});
							$('#sltCliNewRetAgencia, #sltInformeCarteraAgencia').html(html2);
						},
					error : function ( ) {
							ReporteDAO.error_ajax();
						}
				});
		},
		ListarProvincia : function ( idCampania, f_fill ) {
			$.ajax({
					url : ReporteDAO.url,
					type : 'GET',
					dataType : 'json',
					data : {
							command:'carga-cartera',action:'ListProvincia',Campania:idCampania 
						},
					success : function ( obj ) {
							f_fill(obj);
						},
					error : function ( ) {
							ReporteDAO.error_ajax();
						}
				});
		},
		ListarCartera : function ( idCampania, f_fill ) {
			$.ajax({
					url : ReporteDAO.url,
					type : 'GET',
					dataType : 'json',
					data : {
							command:'carga-cartera',action:'ListCartera',Campania:idCampania 
						},
					success : function ( obj ) {
							f_fill(obj);
						},
					error : function ( ) {
							ReporteDAO.error_ajax();
						}
				});
		},
		ListarCarteraRpteRank : function ( idCampania,filtroEstadoCart, f_fill ) {
			$.ajax({
					url : ReporteDAO.url,
					type : 'GET',
					dataType : 'json',
					data : {
							command:'carga-cartera',action:'ListCarteraRpteRank',Campania:idCampania,Estado:filtroEstadoCart 
						},
					success : function ( obj ) {
							f_fill(obj);
						},
					error : function ( ) {
							ReporteDAO.error_ajax();
						}
				});
		},
		ListarCarteraTbRpteRank : function ( idCampania,filtroEstadoCart, f_fill, idCB ) {
					
			$.ajax({
					url : ReporteDAO.url,
					type : 'GET',
					dataType : 'json',
					data : {command:'carga-cartera',action:'ListCarteraRpteRank',Campania:idCampania,Estado:filtroEstadoCart},
					beforeSend : function ( ) {},
					success : function ( obj ) {
							f_fill(obj,idCB);
						},
					error : function ( ) {
						
						}
				});
			
		},
		ListarCarteraTb : function ( idCampania, f_fill, idCB ) {
					
			$.ajax({
					url : ReporteDAO.url,
					type : 'GET',
					dataType : 'json',
					data : {command:'carga-cartera',action:'ListCartera',Campania:idCampania},
					beforeSend : function ( ) {},
					success : function ( obj ) {
							f_fill(obj,idCB);
						},
					error : function ( ) {
						
						}
				});
			
		},
		ListarTodasCartera : function ( f_fill, idTB ) {
			
			$.ajax({
					url : ReporteDAO.url,
					type : 'GET',
					dataType : 'json',
					data : { command:'carga-cartera',action:'ListTodasCartera',servicio : $('#hdCodServicio').val() },
					beforeSend : function ( ) {},
					success : function ( obj ) {
							
							f_fill( obj, idTB );
							
						},
					error : function ( ) {
							
						}
				});
			
		},
		load_gestor_campo : function(){
			$.ajax({
				url : ReporteDAO.url,
				type : 'GET',
				dataType : 'json',
				data : {command:'atencion_cliente',action:'ListarGestorCampo',idservicio : $('#hdCodServicio').val()},
				beforeSend : function(){},
				success : function (obj){
					var html='';
					html+='<option value="0">--Seleccione--</option>';
					for(i=0;i<obj.length;i++){
						html+='<option value="'+obj[i].idusuario_servicio+'">'+obj[i].operador+'</option>';
					}
					$('#listGestorCampo').html(html);
                    
				},
				error : function(){}
			});
		},
		ListarDistritoCartera : function (){
			$.ajax({
				url : ReporteDAO.url,
				type : 'GET',
				dataType : 'json',
				data : {command:'atencion_cliente',action : 'ListarDistritoCartera',
						idcartera : $('#tbRKCartera_divReportes').find(':checked').map(function( ){return this.value;}).get().join(",")
						},
				beforeSend : function(){},
				success : function(obj){
					var html='';
					$('#listarDistrito').html('');
					for(i=0;i<obj.length;i++){
						html+='<input style="margin-left:30px" type="checkbox" value="'+obj[i].distrito+'">'+obj[i].distrito+'</option><br>';
					}
					$('#listarDistrito').html(html);					
				},
				error : function(){}
			});
		},
		listarfproceso : function (){
			$.ajax({
				url : ReporteDAO.url,
				type : 'GET',
				dataType : 'json',
				data : {command:'atencion_cliente',action:'listarfproceso',
						idcartera : $('#tbRKCartera_divReportes').find(':checked').map(function(){return this.value;}).get().join(",")
						},
				beforeSend : function (){},
				success : function(obj){
							var html='';
							$('#listarFproceso').html('');
							for(i=0;i<obj.length;i++){
								html+='<input style="margin-left:30px" type="checkbox" value="'+obj[i].fecha+'">'+obj[i].fecha+'</option><br>';
							}
							$('#listarFproceso').html(html);
						},
				error : function(){}
			});
		},
		listarfprocesomultiple : function (){
			$.ajax({
				url : ReporteDAO.url,
				type : 'GET',
				dataType : 'json',
				data : {command:'atencion_cliente',action:'listarfprocesomultiple',
						idcartera : $('#tbRKCartera_divReportes').find(':checked').map(function(){return this.value;}).get().join(",")
						},
				beforeSend : function (){},
				success : function(obj){
							var html='';
							$('#listarFprocesoMultiple').html('');
							for(i=0;i<obj.length;i++){
								html+='<input style="margin-left:30px" type="checkbox" value="'+obj[i].fecha+'">'+obj[i].fecha+'</option><br>';
							}
							$('#listarFprocesoMultiple').html(html);
						},
				error : function(){}
			});
		},		
		listarterritorio : function(){
			$.ajax({
				url : ReporteDAO.url,
				type : 'GET',
				dataType : 'json',
				data : {command:'atencion_cliente',action:'listarterritorio',
						idcartera : $('#tbRKCartera_divReportes').find(':checked').map(function(){return this.value;}).get().join(","),
						fproceso : $('#listarFproceso').find(':checked').map(function(){return "'"+this.value+"'";}).get().join(","),
					},
				beforeSend : function(){},
				success : function(obj){
							var html='';
							$('#listarterritorio').html('');
							for(i=0;i<obj.length;i++){
								html+='<input style="margin-left:30px" type="checkbox" value="'+obj[i].Territorio+'">'+obj[i].Territorio+'</option><br>';
							}
							$('#listarterritorio').html(html);
				},
				error : function(){}
			});
		},
		FillTCarteraTB : function ( obj, idCb ) {
			var html='';
			var alto='0px';
			if(obj.length>0){alto='120px'}
			html+='<tr><td><div style="height:'+alto+';"><table border="0" cellspacing="0" cellpadding="0">';
			for( i=0;i<obj.length;i++ ) {
				html+='<tr>';
					html+='<td align="center" class="ui-widget-header" style="width:20px;padding:2px 0px;">'+(i+1)+'</td>';
					html+='<td align="center" class="ui-widget-content" style="width:260px;padding:2px 0px;">'+obj[i].campania+'</td>';
					html+='<td align="center" class="ui-widget-content" style="width:260px;padding:2px 0px;">'+obj[i].nombre_cartera+'</td>';
					html+='<td align="center" class="ui-widget-content" style="width:100px;padding:2px 0px;">'+obj[i].fecha_inicio+'</td>';
					html+='<td align="center" class="ui-widget-content" style="width:70px;padding:2px 0px;">'+obj[i].fecha_fin+'</td>';
					html+='<td align="center" class="ui-widget-content" style="width:70px;padding:2px 0px;">'+obj[i].flag_provincia+'</td>';					
					html+='<td align="center" class="ui-widget-content" style="width:20px;padding:2px 0px;"><input  type="checkbox" value="'+obj[i].idcartera+'"  ></td>';
				html+='</tr>';
			}
			html+='</table></div></td></tr>';
			$('#'+idCb).html(html);
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
			//$('#cbRKCartera').html(html);
			$('#'+idCb).html(html);
		},
	
		FillCartera : function ( obj ) {
				var html='';
					html+='<option value="0">--Seleccione--</option>';
				$.each(obj,function(key,data){
					html+='<option value="'+data.idcartera+'">'+data.nombre_cartera+'</option>';
				});
				$('#submenuCartera #cbCartera').html(html);   
			},
		FillCarteraById : function ( obj,cbCartera ) {
				var html='';
					html+='<option value="0">--Seleccione--</option>';
				$.each(obj,function(key,data){
					if(data.vencido==1){html+='<option value="'+data.idcartera+'" style="color:#F00;">'+data.nombre_cartera+'</option>';}
					else{html+='<option value="'+data.idcartera+'">'+data.nombre_cartera+'</option>';}
					
				});
				//$('#submenuCartera #'+cbCartera).html(html); //keny
				$('#panelHomeReporte #'+cbCartera).html(html);
			},
		FillProvinciaById : function ( obj,cbProvincia ) { //jc
				var html='';
					html+='<option value="0">--Seleccione--</option>';
				$.each(obj,function(key,data){
					html+='<option value="'+data.provincia+'">'+data.provincia+'</option>';
				});
				//$('#submenuCartera #'+cbCartera).html(html); //keny
				$('#panelHomeReporte #'+cbProvincia).html(html);
			},
		// jc SIG	
		FotoCartera : function ( ) {
				$.ajax({
						url : ReporteDAO.url,
						type : 'GET',
						dataType : 'json',
						data : {command:'reporte',action:'fotocartera',Campania:2},
						success : function ( obj ) {
								var html='';
								var JsonToArray=eval(obj);
								for(i=0;i<JsonToArray.length;i++){
									html+='<tr>';
									for( index in JsonToArray[i] ){
											html+='<td>'+JsonToArray[i][index]+'</td>';
									}
									html+='</tr>';
								}
								$('#table_fotocartera').html(html);
							},
						error : function ( ) {
								
							}
					});
			},
		FillCampania : function ( obj ) {
				var html='';
					html+='<option value="0">--Seleccione--</option>';
				$.each(obj,function(key,data){
					html+='<option value="'+data.idcampania+'">'+data.nombre+'</option>';
				});
				//$('#submenuCartera #cbCampania').html(html);
				//$('#submenuCartera').find('select[id^="cbCampania"]').html(html);
				$('#panelHomeReporte').find('select[id^="cbCampania"]').html(html);
				//$('#cbCampania').html(html);
			},
		ListarCabecerasCartera : function ( xidcartera, f_success ) {
				
				$.ajax({
						url : ReporteDAO.url,
						type : 'GET',
						dataType : 'json',
						data : { command : 'distribucion', action : 'ListarCabecerasCartera', idcartera : xidcartera },
						success : function ( obj ) { f_success(obj) },
						error : function ( ) {}
						});
				
			},
		ListarEstado : function ( idservicio, f_success ) {
				
				$.ajax({
						url : ReporteDAO.url,
						type : 'GET',
						dataType : 'json',
						data : {command:'atencion_cliente', action:'ListState', Servicio : idservicio },
						beforeSend : function ( ) {},
						success : function ( obj ) {
								f_success(obj);
							},
						error : function ( ) {}
					});
				
			},
			ListarLlamada : function(xidcartera,nombreReporte){
				$.ajax({
					url		: ReporteDAO.url,
					type 	: 'GET',
					dataType: 'json',
					data 	: {
							command	: 'atencion_cliente',
							action 	: 'ListLlamada',
							fecha 	: $('#txtFechaUnicaReporte').val(),
							cartera : xidcartera
					},
					beforeSend : function(){},
					success : function(obj){
						var idcartera = $('#tbRKCartera_divReportes').find(':checked').map(function( ) {return this.value;}).get().join(",");
						var servicio = $('#hdCodServicio').val();
						var nombre_servicio = $('#hdNomServicio').val();
						var fproceso = $('#listarFproceso').find(':checked').map(function(){return "'"+this.value+"'";}).get().join(",");
						var fecha_unica=$('#txtFechaUnicaReporte').val();
						var territorios = $('#listarterritorio').find(':checked').map(function(){return "'"+this.value+"'";}).get().join(",");						
						if(obj[0].COUNT>0){
        					window.location.href="../rpt/excel/"+nombreReporte+".php?Cartera="+idcartera+"&Servicio="+servicio+"&NombreServicio="+nombre_servicio+"&fproceso="+fproceso+"&fecha_unica="+fecha_unica+"&territorio="+territorios;							
						}else{
							alert('No Hay llamadas en el dia Seleccionado');
							return false;
						}
					},
					error:function(){}
				})
			},
			Listar_Cartera_Opcion : function(){
				$.ajax({
					url		: ReporteDAO.url,
					type 	: 'GET',
					dataType: 'json',
					data 	: {
							command	: 'atencion_cliente',
							action 	: 'Listar_Cartera_Opcion'
					},
					beforeSend : function(){},
					success : function(obj){
						if(obj.rst==true){
        					$("#cartera_mes_opcion").html("<option value='0'>.:Seleccione:.</option>");
        					var ar_carteras=obj.carteras;
        					for (i = 0; i <=ar_carteras.length-1 ; i++) {
        						// alert(ar_carteras[i]['nombre_cartera']);
        						$("#cartera_mes_opcion").append("<option value='"+ar_carteras[i]['idcartera']+"'>"+ar_carteras[i]['nombre_cartera']+"</option>")
        					}

        				}else{

						}
					},
					error:function(){}
				})
			},
			error_ajax : function ( ) {
				_noneBeforeSend();
				$('#'+ReporteDAO.idLayerMessage).html(templates.MsgError('Error en el servidor','250px'));
				$('#'+ReporteDAO.idLayerMessage).effect('pulsate',{},AtencionClienteDAO.speedLayerMessage,function () { $(this).empty(); });
			}
	}
