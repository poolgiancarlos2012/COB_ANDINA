var AtencionClienteDAO={
    url:'../controller/ControllerCobrast.php',
    idLayerMessage : 'layerMessage',
    speedLayerMessage : 1500,
    EstadosCuenta : [ ],
    EstadosLlamada : [ ],
    EstadosVisita : [ ],
    Notificadores : [ ],
    LineasTelefono : [ ],
    ArrayMotivoNoPago:[ ],//jmore17112014
    ArrayCuenta:[ ],//jmore17112014    
    ArraySustentoPago:[ ],//jmore18112014
    ArrayAlertaGestion:[ ],//jmore18112014    
    IndexCabecerasCuenta : { 
        "MOVIL" : "0,1,2,5,7,8,10,11,12,13,14,19" ,
        "CENCOSUD" : "0,1,2,3,4,9,10,11,12,13,14,16,19", 
        "COBRANZA Y EJECUCION" : "0,1,2,6,10,11,12,13,14,16,19,20,21",/*jmore300612*/                             
        "AGENCIA" : "0,1,2,6,10,11,12,13,14,16,19,20,21",
        "GLOBAL COM" : "0,1,2,6,10,11,12,13,14,16,19,20,21",
        "SCI" : "0,1,2,6,10,11,12,13,14,16,19,20,21",/*jmore300612*/
        "SAGA" : "0,1,2,6,10,11,12,13,14,16,19,20,23,24",
        "BBVA" : "0,1,2,6,10,11,12,13,14,16,19,20,23",
        "EXTRA_JUDICIAL" : "0,1,2,6,10,11,12,13,14,16,23",    
        "FORUM" : "0,1,2,6,10,11,12,13,14,16,21", 
        "PLANTA_EXTERNA" : "0,1,2,6,10,11,12,13,14,16,21",     
        "COVINOC" : "0,1,2,6,10,11,12,13,14,16,19,20,23",
        "CONECTA" : "0,1,2,6,10,11,12,13,14,16,19,20,23",
        "OPCION" : "1,3,4,5,6,7,8,9,10,11,12,13,14,15,17",
        //"ANDINA" : "2,3,4,5,6,7,8,9,10,11,12,13,14,15,16",
        "ANDINA" : "2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19",
    },
    f_LayerMessage : function ( ) {
        $('#'+AtencionClienteDAO.idLayerMessage).empty();
    },
    Ubigeo : {
    
        Listar : {
            Departamento : function ( f_success ) {
                
                $.ajax({
                        url : AtencionClienteDAO.url,
                        async : false,
                        type : 'GET',
                        dataType : 'json',
                        async : false,
                        data : { command : 'ubigeo', action : 'ListarDepartamento' } ,
                        beforeSend : function ( ) { },
                        success : function ( obj ) { 
                            f_success(obj);
                        },
                        error : function ( ) { }
                    });
                
            },
            Provincia : function ( xdepartamento, f_success ) {
                $.ajax({
                        url : AtencionClienteDAO.url,
                        type : 'GET',
                        dataType : 'json',
                        async : false,
                        data : { command : 'ubigeo', action : 'ListarProvincia', departamento : xdepartamento } ,
                        beforeSend : function ( ) { },
                        success : function ( obj ) { 
                            f_success(obj);
                        },
                        error : function ( ) { }
                    });
            },
            Distrito : function ( xdepartamento, xprovincia, f_success ) {
                $.ajax({
                        url : AtencionClienteDAO.url,
                        type : 'GET',
                        dataType : 'json',
                        async : false,
                        data : { command : 'ubigeo', action : 'ListarDistrito', departamento : xdepartamento, provincia : xprovincia } ,
                        beforeSend : function ( ) { },
                        success : function ( obj ) { 
                            f_success(obj);
                        },
                        error : function ( ) { }
                    });
            }
        }
        
    },
    ListarCuenta : function ( /*xCodigoCliente, xidcartera*/ ) {
				
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'GET',
            dataType : 'json',
            async : false,
            data : {
                command:'atencion_cliente',
                action:'ListCuenta',
                Cartera : $('#IdCartera').val(), 
                CodigoCliente : $('#CodigoClienteMain').val(),
                IdClienteCartera : $('#IdClienteCarteraMain').val()
            },
            beforeSend : function ( ) {

                $("#cargando").css("display","")
            },
            success : function ( obj ) {
								
                var html='';
                alert("hola")

                for( i=0;i<obj.length;i++ ) {
                    var estadoFacturaDigital='';
                    var corteFocalizado = '';
                    /*var selec_='';
                    selec_+='<select id="cbEstadoCuenta" class="combo" style="width:140px;" >';
                    selec_+='<option value="0">--Seleccione--</option>';

                    var idfinal = obj[i].ultimo_idfinal;
                    for( k=0;k<AtencionClienteDAO.EstadosCuenta.length;k++ ) {
                        var data = (AtencionClienteDAO.EstadosCuenta[k].data).split('|');
                        selec_+='<optgroup label="'+AtencionClienteDAO.EstadosCuenta[k].CARGA+'" >';
                        for( j=0;j<data.length;j++ ) {
                            var final = data[j].split('@#');	
                            if( idfinal==final[0] ) {
                                selec_+='<option selected="selected" value="'+final[0]+'" >'+final[1]+'</option>';
                            }else{
                                selec_+='<option value="'+final[0]+'" >'+final[1]+'</option>';
                            }

                        }
                        selec_+='</optgroup>';
                    }
    
                    selec_+='</select>';*/

 
                    if(obj[i].is_send == '1'){
                        estadoFacturaDigital = '<span style="color:red;">Enviado</span>';
                    }
                    if(obj[i].corte_focalizado == '1'){
                        corteFocalizado = '<span style="color:red;">Si</span>';
                    }
                    var xclass = "ui-widget-content";
                    if( obj[i].estado == 0 ) {
                        xclass = "ui-state-error";
                    }
                    html+='<tr class="'+xclass+'" onclick="activar_row(this);get_data_cuenta('+obj[i].idcuenta+')" value="'+obj[i].idcuenta+'">';
                    if( obj[i].estado_cuenta != '' ) {
                        html+='<td><div style="position:absolute;padding:3px;margin-top:6px;" class="ui-state-highlight ui-corner-all">'+obj[i].estado_cuenta+'</div></td>';
                    }
                    html+='<td class="ui-state-default" style="width:15px;border:1px solid #E0CFC2;" align="center"></td>';
                    html+='<td align="center" style="width:80px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-left:1px solid #E0CFC2;" >'+obj[i].RETIRADO+'</td>';

                    html+='<td align="center" style="width:190px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;" >'+obj[i].numero_cuenta+'</td>';
                    html+='<td align="center" style="width:70px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;">'+obj[i].telefono+'</td>';
                    html+='<td align="center" style="width:70px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;">'+obj[i].moneda+'</td>';
                    html+='<td align="center" style="width:70px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+obj[i].total_deuda+'</td>';
                    //html+='<td align="center" style="width:90px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;"><input class="inputText" value="'+obj[i].ultimo_fecha_cp+'" style="width:60px;" type="text" /></td>';
                    html+='<td align="center" style="width:90px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;"><input name="txtFechaCpCuenta" class="inputText" style="width:60px;height:10px; font-size:11px" type="text" /></td>';
                    html+='<td align="center" style="width:90px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;"><input name="txtMontoCpCuenta" class="inputText" style="width:60px;height:10px; font-size:11px" type="text" /></td>';
                    html+='<td align="center" style="width:90px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;"><select name="cbMonedaCpCuenta" class="combo"><option value="SOLES">SOLES</option><option value="DOLARES">DOLARES</option><option value="EUROS">EUROS</option></select></td>';
                    html+='<td align="center" style="width:55px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;"><select style="width:45px;" name="cbStatusVlCuenta" class="combo"><option value="">--</option><option value="SI">SI</option><option value="NO">NOO</option><option value="NO VALIDA">NO VALIDA</option></select></td>';
                    //html+='<td align="center" style="width:90px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;"><input class="inputText" value="'+obj[i].ultimo_monto_cp+'" style="width:60px;height:10px; font-size:11px" type="text" /></td>';
                    //html+='<td align="center" style="width:150px;white-space:pre-line;border-bottom:1px solid #E0CFC2;height:8px; font-size:10px">'+selec_+'</td>';
                    html+='<td align="center" style="width:60px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;">'+estadoFacturaDigital+'</td>';
                    html+='<td align="center" style="width:30px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;">'+corteFocalizado+'</td>';
                    var attr = '';
                    if( obj[i].estado = 1 ) {
                        attr = ' checked="checked" ';
                    }
                    html+='<td align="center" class="ui-state-default" style="width:25px;border-bottom:1px solid #E0CFC2;height:8px; font-size:10px"><input '+attr+' type="checkbox" value="'+obj[i].idcuenta+'" /></td>';
                    html+='</tr>';
                }
                $('#table_cuenta_aplicar_gestion').html(html);
                //$('#table_cuenta_aplicar_gestion tr:eq(0)').click();
                $('#table_cuenta_aplicar_gestion').find("tr").find(":text[name='txtFechaCpCuenta']").mask("2099-99-99");
                
                $("#cargando").css("display","none")
            },
            error : function ( ) {}
        });
				
    },
    ListarDataCuenta : function ( xidcartera, xidcliente_cartera, f_success ) {
				
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'GET',
            dataType : 'json',
            async : false,
            data : {
                command: 'atencion_cliente',
                action:'ListarCuenta', 
                idservicio : $('#hdCodServicio').val(),
                PorInteres : $('#hdPorInteres').val(),
                PorDescuento : $('#hdPorDescuento').val(),
                IsInteresDescuento : $('#hdIsInteresDescuento').val(),
                IsMontoCobrar : $('#hdIsMontoCobrar').val(),
                IsMontoVencidoPorVencer : $('#hdIsMontoVencidoPorVencer').val(),
                idcartera : xidcartera, 
                idcliente_cartera : xidcliente_cartera
            },
            beforeSend : function ( ) {
								
            },
            success : function ( obj ) {
                f_success(obj);
                AtencionClienteDAO.ArrayCuenta=obj;//jmore17112014
                //armar_combo_motivo_no_pago();//jmore17112014    

                tamanio = xidcliente_cartera.length;
                valor=xidcliente_cartera;
                b=0;
                if(tamanio<13){
                    b=13-tamanio;
                    valor+=""+Math.floor((Math.random(Math.pow(10,b-1),Math.pow(10,b+1)-1))*Math.pow(10,b));
                }else{
                    valor= xidcliente_cartera;
                }
                $('#txtNumeroPagareCovinoc').val(valor); 
                $('#txtidcliente_cartera').val(xidcliente_cartera);
                             
            },
            error : function () {
								
            }
        });
			
    },	
    ListarCampanias : function ( ) {
        $.ajax({
            url : this.url,
            type:'GET',
            dataType:'json',
            async : false,
            data:{
                command:'atencion_cliente',
                action:'ListarCampaniasActivas',
                Servicio:$('#hdCodServicio').val()
            },
            success: function ( obj ) {
                //AtencionClienteDAO.CampaniaBusquedaManual( obj );
                //AtencionClienteDAO.CampaniaBusquedaBase( obj );
                //AtencionClienteDAO.CampaniaMatrizBusqueda( obj );
                
				AtencionClienteDAO.AtencionCampaniaGlobales( obj );
				
				AtencionClienteDAO.CampoCampaniaGlobales( obj );
				
                
            /**********/
            },
            error: this.error_ajax
        });
    },
    ListarSupervisores : function()
    {
        $.ajax({
            url : this.url,
            type:'GET',
            dataType:'json',
            data:{
                command:'atencion_cliente',
                action:'ListarSupervisores',
                Servicio:$('#hdCodServicio').val()
            },
            success: function ( obj ) {
                //AtencionClienteDAO.CampaniaBusquedaManual( obj );
                //AtencionClienteDAO.CampaniaBusquedaBase( obj );
                //AtencionClienteDAO.CampaniaMatrizBusqueda( obj );
                /**********/
                AtencionClienteDAO.GenerarOptComboSupervisores( obj );
            /**********/
            },
            error: this.error_ajax
        });
    },
    GenerarOptComboSupervisores : function(obj)
    {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idusuario_servicio+'">'+data.usuario+'</option>';
        });
        $("#cboFacturaDigitalSupervisor").html(html);
    },
    ListarCampaniasAll : function ( ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListarCampaniasAll',
                Servicio:$('#hdCodServicio').val()
            },
            success : function ( obj ) {
                AtencionClienteDAO.AtencionCampaniaGlobales( obj );
                AtencionClienteDAO.CampoCampaniaGlobales( obj );
            },
            error : this.error_ajax
        });
    },
    ListarCampaniasWithIdServicioParam : function ( idServicio, function_fill_campania ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListarCampanias',
                Servicio:idServicio
            },
            success : function ( obj ) {
                function_fill_campania(obj);
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    ListarOperadoresWithParam : function ( function_fill_operadores ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListarOperadores',
                Servicio:$('#hdCodServicio').val()
            },
            success : function ( obj ) {
                function_fill_operadores(obj);
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    ListarOperadoresAyudar : function ( function_fill_operadores, carteras ) {
        carteras = carteras || $('#cbCarteraApoyo').val();
        $.ajax({
            url : this.url,
            async : false,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListarOperadoresAyudar',
                Cartera:carteras,
                Servicio:$('#hdCodServicio').val(),
                idusuario_servicio:$('#hdCodUsuarioServicio').val()
            },
            success : function ( obj ) {
                function_fill_operadores(obj);
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    CampaniaBusquedaManual : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idcampania+'">'+data.nombre+'</option>';
        });
        $("#cbCampaniaBusquedaManual").html(html);
    },
    AtencionCampaniaGlobales : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idcampania+'">'+data.nombre+'</option>';
        });
        $("#cbAtencionGlobalesCompania").html(html);
        $("#cbCampaniaApoyo").html(html);
    },
    CampoCampaniaGlobales : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idcampania+'">'+data.nombre+'</option>';
        });
        $("#cbCampoGlobalesCampania").html(html);
    },
    CampaniaBusquedaBase : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idcampania+'">'+data.nombre+'</option>';
        });
        $("#cbCampaniaBusquedaBase").html(html);
    },
    CampaniaMatrizBusqueda : function ( obj ){
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idcampania+'">'+data.nombre+'</option>';
        });
        $("#cbCampaniaMatrizBusqueda").html(html);
    },
    OperadoresMatrizBusqueda : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            if( $('#hdCodUsuarioServicio').val()!=data.idusuario_servicio ) {
                html+='<option value="'+data.idusuario_servicio+'">'+data.nombre+'</option>';
            }
        });
        $("#cbOperadoresMatrizBusqueda").html(html);
    },
    servicios : function ( ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListarServicio',
                Usuario:$('#hdCodUsuario').val()
            },
            success : function ( obj ) {
                AtencionClienteDAO.ServicioMatrizBusqueda( obj );
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    ServicioMatrizBusqueda : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idservicio+'">'+data.nombre+'</option>';
        });
        $("#cbServicioMatrizBusqueda").html(html);
    },
    DatosCliente : function ( ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'DatosCliente'
            },
            success : function ( obj ) {
						   		
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    DatosTotalCuenta : function ( CodigoCliente ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'DatosTotalCuenta',
                CodigoCliente:CodigoCliente,
                Cartera:$('#cbAtencionGlobalesCartera').val()
            },
            beforeSend : function ( ) {
                $('#PanelTableDatosCuenta').html(templates.IMGloadingContentLayer());
            },
            success : function ( obj ) {
                $('#PanelTableDatosCuenta').html('');
                var html='';
                for( i=0;i<obj.length;i++ ) {
									
                    html+='<table>';
									
                    html+='<tr>';
                    html+='<td align="center">';
                    html+='<table>';
                    html+='<tr>';
                    html+='<td align="right" style="font-weight:bold;">Numero Cuenta</td>';
                    html+='<td align="left" style="font-weight:bold;color:#0066FF;">'+obj[i].NumeroCuenta+'</td>';
                    html+='<td align="right" style="font-weight:bold;">Moneda</td>';
                    html+='<td align="left" style="font-weight:bold;color:#0066FF;">'+obj[i].Moneda+'</td>';
                    html+='<td align="right" style="font-weight:bold;">Comision</td>';
                    html+='<td align="left" style="font-weight:bold;color:#0066FF;">'+obj[i].Comision+'</td>';
                    html+='</tr>';
                    html+='</table>';
                    html+='</td>';
                    html+='</tr>';
										
                    html+='<tr>';
                    html+='<td align="center">';
                    html+='<table>';
                    for( j=0;j<obj[i].DataCuenta.length;j++ ) {
                        html+='<tr>'; 
                        var data = obj[i].DataCuenta[j];
                        for( index in data ) {
                            html+='<td align="right" style="width:150px;">'+index+'</td>';
                            html+='<td align="left"><input type="text" value="'+data[index]+'" readonly="readonly" class="cajaForm" style="width:50px;" /></td>';
                        }
                        html+='</tr>';
                    }
                    html+='</table>';
                    html+='<td>';
                    html+='</tr>';
                    html+='</table>';
                }
                $('#PanelTableDatosCuenta').html(html);
            //if(obj.length>0){
            //									$('#txtTotalDeuda').val(obj[0].total_deuda);
            //									$('#txtTotalDeudaSoles').val(obj[0].total_deuda_soles);
            //									$('#txtTotalDeudaDolares').val(obj[0].total_deuda_dolares);
            //									$('#txtTotalMontoMora').val(obj[0].monto_mora);
            //									$('#txtTotalMontoMoraSoles').val(obj[0].monto_mora_soles);
            //									$('#txtTotalMontoMoraDolares').val(obj[0].monto_mora_dolares);
            //									$('#txtTotalSaldoCapital').val(obj[0].saldo_capital);
            //									$('#txtTotalSaldoCapitalSoles').val(obj[0].saldo_capital_soles);
            //									$('#txtTotalSaldoCapitalDolares').val(obj[0].saldo_capital_dolares);
            //									/***********/
            //									$('#txtComisionGeneral').val(obj[0].comision_general);
            //									$('#txtComisionTotalDeuda').val(obj[0].comision_total_deuda);
            //									$('#txtComisionTotalDeudaSoles').val(obj[0].comision_total_deuda_soles);
            //									$('#txtComisionTotalDeudaDolares').val(obj[0].comision_total_deuda_dolares);
            //									$('#txtComisionTotalMontoMora').val(obj[0].comision_monto_mora);
            //									$('#txtComisionTotalMontoMoraSoles').val(obj[0].comision_monto_mora_soles);
            //									$('#txtComisionTotalMontoMoraDolares').val(obj[0].comision_monto_mora_dolares);
            //									$('#txtComisionTotalSaldoCapital').val(obj[0].comision_saldo_capital);
            //									$('#txtComisionTotalSaldoCapitalSoles').val(obj[0].comision_saldo_capital_soles);
            //									$('#txtComisionTotalSaldoCapitalDolares').val(obj[0].comision_saldo_capital_dolares);
            //									/**********/
            //								}
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    //DatosTotalComision : function ( idClienteCartera ) {
    //				$.ajax({
    //					   url : this.url,
    //					   type : 'GET',
    //					   dataType : 'json',
    //					   data : {command:'atencion_cliente',action:'DatosComisionTotalCuenta',ClienteCartera:idClienteCartera},
    //					   success : function ( obj ) {
    //						  		if(obj.length>0){
    //									$('#txtTotalDeuda').val(obj[0].total_deuda);
    //									$('#txtTotalDeudaSoles').val(obj[0].total_deuda_soles);
    //									$('#txtTotalDeudaDolares').val(obj[0].total_deuda_dolares);
    //									$('#txtTotalMontoMora').val(obj[0].monto_mora);
    //									$('#txtTotalMontoMoraSoles').val(obj[0].monto_mora_soles);
    //									$('#txtTotalMontoMoraDolares').val(obj[0].monto_mora_dolares);
    //									$('#txtTotalSaldoCapital').val(obj[0].saldo_capital);
    //									$('#txtTotalSaldoCapitalSoles').val(obj[0].saldo_capital_soles);
    //									$('#txtTotalSaldoCapitalDolares').val(obj[0].saldo_capital_dolares);
    //								}
    //						   },
    //					   error : function ( ) {
    //						   		AtencionClienteDAO.error_ajax();
    //					   		}
    //					   });
    //			},
    DatosCuentaCliente : function ( ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'DatosCuentaCliente'
            },
            success : function ( obj ) {
						   		
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    DeleteAlerta : function ( idAlerta, f_success ) {
        $.ajax({
            url : this.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'DeleteAlerta',
                Alerta:idAlerta,
                UsuarioModificacion:$('#hdCodUsuario').val()
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Eliminando Alerta...',250);
            //$(element).attr('disabled',true);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                //$(element).attr('disabled',false);
                /*if ( obj.rst ) {
									$(element).parent().parent().remove();
									$('#layerMessageAlerta').html(templates.MsgInfo(obj.msg,'200px'));
									$('#layerMessageAlerta').effect('pulsate',{},'normal',function(){ $(this).empty(); });
								}else{
									$('#layerMessageAlerta').html(templates.MsgError(obj.msg,'200px'));
									$('#layerMessageAlerta').effect('pulsate',{},'normal',function(){ $(this).empty(); });
								}*/
                f_success(obj);
            },
            error : this.error_ajax
        });
    },
    DatosAdicionalesCliente : function ( xCodigoCliente, idCartera ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'DatosAdicionalesCliente',
                Servicio:$('#hdCodServicio').val(),
                CodigoCliente:xCodigoCliente,
                Cartera:idCartera
            },
            success : function ( obj ) {
                var htmlField='';
                var htmlData='';
                var html='';
                if(obj.length==1){
									
                    for(i=1;i<51;i++){
										
                        if(eval('obj[0].dato'+i)==null || eval('obj[0].dato'+i)==''){
                            break;
                        }else{
                            htmlField+='<td><div class="ui-widget-header ui-corner-all" style="padding:2px 4px;" align="right" >'+eval('obj[0].dato'+i)+'</div></td>';
                            htmlData+='<td></td>';
                        }
                    }
                    html+='<tr>'+htmlField+'</tr><tr>'+htmlData+'</tr>';
                }else if ( obj.length==2 ) {
                    for(i=1;i<51;i++){
										
                        if(eval('obj[0].dato'+i)==null || eval('obj[0].dato'+i)==''){
                            break;
                        }else{
                            htmlField+='<td><div class="ui-widget-header ui-corner-all" style="padding:2px 4px;" align="right" >'+eval('obj[0].dato'+i)+'</div></td>';
                            htmlData+='<td align="center">'+eval('obj[1].dato'+i)+'</td>';
                        }
                    }
                    html+='<tr>'+htmlField+'</tr><tr>'+htmlData+'</tr>';
									
                }else {
                    html+='<tr></tr>';	
                }
								
                $('#table_datos_adicionales_cliente').html(html);
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    DatosAdicionalesCuenta : function ( xidservicio, xidcartera, xidcuenta, element ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'DatosAdicionalesCuenta',
                Servicio : xidservicio,
                Cartera : xidcartera,
				IdCuenta : xidcuenta
            },
            success : function ( obj ) {
                
                var html='';
				
				for( i=0;i<obj.length;i++ ) {
					var object = eval(obj[i]);
					if( i==0 ) {
						html+='<tr>';
						for( index in object ) {
							html+='<td align="center" class="ui-state-default" style="padding:4px 2px;">'+index+'</td>';
						}
						html+='</tr>';
					}
					html+='<tr>';
					for( index in object ) {
						html+='<td align="center" class="ui-widget-content" style="padding:4px 2px;" >'+object[index]+'</td>';
					}
					html+='</tr>';
				}
                
                $('#tb_adicional_cuenta').html(html);
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    actualizarRepresentanteLegal:function(){
        $.ajax({
            url:this.url,
            type:'GET',
            dataType:'json',
            data:{
                command:'atencion_cliente',
                action:'actualizarRepresentanteLegal',
                idrepresentante_legal:$('#txtidrepresentante_legal').val(),
                asesor_comercial:$('#txtasesorcomercial_representante').val(),
                representante_legal:$('#txtrepresentantelegal_representante').val(),
                responsable_pago:$('#txtresponsablepago_representante').val(),
                observacion:$('#txtobservacion_representante').val(),
                codigo_cliente:$('#CodigoClienteMain').val(),
                cartera:$('#IdCartera').val()
            },
            success:function(obj){
                listar_cliente($('#hdCodServicio').val(), $('#CodigoClienteMain').val(), $('#IdCartera').val());
                $('#DialogEditarRepresentanteLegal').hide();
            },
            error:function(){}
        })
    } , 
    nuevoRepresentanteLegal:function(){
        $.ajax({
            url:this.url,
            type:'GET',
            dataType:'json',
            data:{
                command:'atencion_cliente',
                action:'nuevoRepresentanteLegal',
                asesor_comercial:$('#txtasesorcomercial_representantenew').val(),
                representante_legal:$('#txtrepresentantelegal_representantenew').val(),
                responsable_pago:$('#txtresponsablepago_representantenew').val(),
                observacion:$('#txtobservacion_representantenew').val(),
                cartera:$('#IdCartera').val(),
                codigo_cliente:$('#CodigoClienteMain').val()
            },
            success:function(obj){
                listar_cliente($('#hdCodServicio').val(), $('#CodigoClienteMain').val(), $('#IdCartera').val());
                $('#DialogNuevoRepresentanteLegal').hide();
            },
            error:function(){}
        })
    } ,    
    deleteRepresentanteLegal:function(xid){
        $.ajax({
            url:this.url,
            type:'GET',
            dataType:'json',
            data:{
                command:'atencion_cliente',
                action:'deleteRepresentanteLegal',
                idrepresentante_legal:xid
            },            
            success:function(obj){
                listar_cliente($('#hdCodServicio').val(), $('#CodigoClienteMain').val(), $('#IdCartera').val());
            },
            error:function(){}
        })
    },          
    DatosAdicionalesOperacion : function ( xCodigoOperacion ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command : 'atencion_cliente',
                action : 'DatosAdicionalesOperacion',
                Servicio : $('#hdCodServicio').val(),
                Cartera : $('#cbAtencionGlobalesCartera').val(),
                CodigoOperacion : xCodigoOperacion
            },
            success : function ( obj ) {
                var htmlField='';
                var htmlData='';
                var html='';
                /*if(obj.length==1){
									for(i=1;i<51;i++){
										if(eval('obj[0].dato'+i)==null || eval('obj[0].dato'+i)==''){
											break;
										}else{
											var campo = eval('obj[0].dato'+i);
											htmlField+='<td style="border:1px solid #E0CFC2;padding:3px;" ><div style="padding:2px 4px;">'+(campo.toUpperCase())+'</div></td>';
											//htmlField+='<td style="border:1px solid #E0CFC2;padding:3px;" ><div style="padding:2px 4px;">'+eval('obj[0].dato'+i)+'</div></td>';
											htmlData+='<td></td>';
										}
									}
									html+='<tr class="ui-state-default" >'+htmlField+'</tr><tr class="ui-widget-content" >'+htmlData+'</tr>';
								}else if(obj.length==2){
									for(i=1;i<51;i++){
										if(eval('obj[0].dato'+i)==null || eval('obj[0].dato'+i)==''){
											break;
										}else{
												var campo = eval('obj[0].dato'+i);
												htmlField+='<td style="border:1px solid #E0CFC2;padding:3px;" ><div style="padding:2px 4px;">'+(campo.toUpperCase())+'</div></td>';
												//htmlField+='<td style="border:1px solid #E0CFC2;padding:3px;" ><div style="padding:2px 4px;">'+eval('obj[0].dato'+i)+'</div></td>';
												htmlData+='<td style="border:1px solid #E0CFC2;padding:3px;" align="center" >'+eval('obj[1].dato'+i)+'</td>';
										}
									}
									html+='<tr class="ui-state-default">'+htmlField+'</tr><tr class="ui-widget-content" >'+htmlData+'</tr>';

								}else{
									html+='<tr></tr>';	
								}*/
								
                for( i=0;i<obj.length;i++ ) {
                    var adicional = eval(obj[i]); 
                    for( index in adicional ) {
                        html+='<li style="float:left;padding:3px;">';
                        html+='<table  border="0" cellpadding="0" cellspacing="0">';
                        html+='<tr>';
                        html+='<td class="ui-state-default" style="padding:2px 3px;">'+index+'</td>';
                        html+='</tr>';
                        html+='<tr>';
                        html+='<td class="ui-widget-content" style="padding:2px 3px;">'+adicional[index]+'</td>';
                        html+='</tr>';
                        html+='</table>';
                        html+='<li>';
                    }
									
                }
								
                $('#table_datos_adicionales_operacion').html(html);
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    SearchClientByCode : function ( ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command : 'atencion_cliente',
                action : 'SearchClientByCode',
                Codigo : $.trim( $('#txtCampoCodigoSearch').val() ),
                UsuarioServicio : $('#hdCodUsuarioServicio').val(),
                Servicio : $('#hdCodServicio').val(),
                Cartera : $('#cbCampoGlobalesCartera').val()
            },
            success : function ( obj ) {
                if(obj.length>0){
                    AtencionClienteDAO.FillAllCampo(obj);									
                }
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        });
    },
     SearchClientByCode2 : function ( ) { /*piro*/
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command : 'atencion_cliente',
                action : 'SearchClientByCode2',
                Codigo : $.trim( $('#txtCampoCodigoSearch2').val() ),
                Cartera : $('#cboCartera').val()
            },
            success : function ( obj ) {
                if(obj.length>0){
                    AtencionClienteDAO.FillAllCampo2(obj);                   
                }
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    SearchClientByNumeroDocumento : function ( ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command : 'atencion_cliente',
                action : 'SearchClientByDni',
                NumeroDocumento : $.trim( $('#txtCampoNumeroDocumentoSearch').val() ),
                UsuarioServicio : $('#hdCodUsuarioServicio').val(),
                Servicio : $('#hdCodServicio').val(),
                Cartera : $('#cbCampoGlobalesCartera').val()
            },
            success : function ( obj ) {
                if(obj.length>0){
                    AtencionClienteDAO.FillAllCampo(obj);
                }
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        });
        
    },
    SearchClientByNumeroCuenta : function ( xnumero_cuenta, xcartera, f_success ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command : 'atencion_cliente',
                action : 'SearchClientByNumeroCuenta',
                NumeroCuenta : $.trim( xnumero_cuenta ),
                UsuarioServicio : $('#hdCodUsuarioServicio').val(),
                Servicio : $('#hdCodServicio').val(),
                Cartera : xcartera
            },
            success : function ( obj ) {
                if(obj.length>0){
                    f_success(obj);
                    //AtencionClienteDAO.FillAllCampo(obj);
                }
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        });
    },    
    SearchClientByTelefono : function ( xtelefono, xcartera, f_success ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command : 'atencion_cliente',
                action : 'SearchClientByTelefono',
                Telefono : $.trim( xtelefono ),
                UsuarioServicio : $('#hdCodUsuarioServicio').val(),
                Servicio : $('#hdCodServicio').val(),
                Cartera : xcartera 
            },
            success : function ( obj ) {
                if(obj.length>0){
                    f_success(obj);
                    //AtencionClienteDAO.FillAllCampo(obj);
                }
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    InitClienteGestion : function ( ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'InitDefaultGestion',
                UsuarioServicio:$('#hdCodUsuarioServicio').val(),
                Servicio:$('#hdCodServicio').val(),
                Tramo : $('#cbFiltroTramo').val() ,
                Monto : $('#cbFiltroMonto').val(),
                Cartera:$('#cbAtencionGlobalesCartera').val()
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Trayendo datos de abonado...',300);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if(obj.length>0){
                    AtencionClienteDAO.FillAllAtencionCliente(obj);
                }
            },
            error : function ( ) {
                _noneBeforeSend();
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    DefaultNext : function ( idClienteCartera ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command : 'atencion_cliente',
                action : 'DefaultNext',
                Servicio : $('#hdCodServicio').val(),
                ClienteCartera : idClienteCartera,
                Tramo : $('#cbFiltroTramo').val() ,
                Monto : $('#cbFiltroMonto').val(),
                UsuarioServicio : $('#hdCodUsuarioServicio').val(),
                Cartera : $('#cbAtencionGlobalesCartera').val()
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Trayendo datos de abonado...',300);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if(obj.length!=0){
                    AtencionClienteDAO.FillAllAtencionCliente(obj);

                }
            },
            error : function ( ) {
                _noneBeforeSend();
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    DefaultBack : function ( idClienteCartera ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'DefaultBack',
                Servicio:$('#hdCodServicio').val(),
                Tramo : $('#cbFiltroTramo').val() ,
                Monto : $('#cbFiltroMonto').val(),
                ClienteCartera:idClienteCartera,
                UsuarioServicio:$('#hdCodUsuarioServicio').val(),
                Cartera:$('#cbAtencionGlobalesCartera').val()
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Trayendo datos de abonado...',300);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if(obj.length==0){
                    $('#txtResultadoCodigoCliente').val('');
                    $('#txtResultadoNombreCodigoCliente').val('');
                    $('#txtResultadoDniCliente').val('');
                    $('#txtResultadoRucCliente').val('');
                    $('#IdClienteCartera').val('');
                }else{
                    AtencionClienteDAO.FillAllAtencionCliente(obj);
                }
						   		
            },
            error : function ( ) {
                _noneBeforeSend();
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    save_alerta : function ( idClienteCartera ) {
        $.ajax({
            url : this.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'GuardarAlerta',
                Servicio:$('#hdCodServicio').val(),
                FechaAlerta:$('#txtFechaAlerta').val(),
                Descripcion:$.trim( $('#txtDescripcionAlerta').val() ),
                ClienteCartera:idClienteCartera,
                UsuarioCreacion:$('#hdCodUsuario').val(),
                UsuarioServicio : $('#hdCodUsuarioServicio').val()
            },
            beforeSend : function ( ) {
                //coloca fecha_agenda para q este disponible para neotel, si se esta en modo neotel esta sera usada para agendar, sino solo se ignorara este campo JC
                $('#txtFechaAgendaN').val($('#txtFechaAlerta').val());
                $('#flg_guardar_agenda').val('1');
                //
                _displayBeforeSend('Guardando Alerta...',250);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if(obj.rst){
                    $('#dialogAlerta').dialog('close');
                    $('#dialogAlerta').find(':text,textarea').val('');
                    $('#txtAbonadoAlerta').empty();
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'200px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                    /******************/
                    load_alertas_recientes_hoy()
                    $('#hdAlerta').val('1');                    
                /******************/
                //AtencionClienteDAO.loadInitializeAlertas();
                }else{
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'200px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        })
    },
    saveAcuerdoPago : function (xusuarioServicio, xidClienteCartera, xidCuenta, xnumeroPagare,xnumeroCuotas,xfechaAcuerdo,xvalorAcuerdo,xdetalleAcuerdoPago){
        $.ajax({
            url: this.url,
            type:'POST',
            dataType: 'json',
            data : {
                command : 'atencion_cliente',
                action : 'saveAcuerdoPago',
                usuarioServicio : xusuarioServicio,
                idClienteCartera : xidClienteCartera,
                idCuenta : xidCuenta,
                numeroPagare : xnumeroPagare,
                numeroCuotas : xnumeroCuotas,
                fechaAcuerdo : xfechaAcuerdo,
                valorAcuerdo : xvalorAcuerdo,
                detalleAcuerdoPago : xdetalleAcuerdoPago
               
            },
            beforeSend : function(){

            },
            complete : function(){

            },
            success: function(obj){
                if(obj.rst){
                    _displayBeforeSendDl('Acuerdo Ingresado',400);

                    $('#layerTabAC2AcuerdosDePago').find('input').
                        css('cursor','not-allowed').
                        attr('readonly','readonly').
                        attr('disabled','disabled');
                    $('#btngrabar_acuerdo_de_pago_covinoc').attr('disabled','disabled')
                        .css('cursor','not-allowed');

                    $('#tblCuentasAcuerdoPago').
                    find('input').
                    removeAttr('readonly').
                    removeAttr('disabled').
                    css('cursor','default');
                    $('#tblDataAcu').html('');
                    $('#tblAcuerdoPago').find('input').not('#txtNumeroPagareCovinoc').not('#txtidcliente_cartera').
                    removeAttr('disabled').removeAttr('readonly').css('cursor','default').val('');
                    $('#btngrabar_acuerdo_de_pago_covinoc').removeAttr('disabled').
                    css({'cursor':'pointer','opacity':'0'});

                    idcliente_Cartera=$('#txtidcliente_cartera').val();
                    tamanio = idcliente_Cartera.length;
                    valor=idcliente_Cartera;
                    b=0;
                    if(tamanio<13){
                      b=13-tamanio;
                      valor+=""+Math.floor((Math.random(Math.pow(10,b-1),Math.pow(10,b+1)-1))*Math.pow(10,b));
                    }else{
                      valor= valor.substr(0,13);
                    }
                    $('#txtNumeroPagareCovinoc').val(valor); 

                }else{
                    _displayBeforeSendDl('Acuerdo no ingresado',400);
                }
            }
        });
    },
    save_alerta_telefono : function ( idClienteCartera ) {
        $.ajax({
            url : this.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'GuardarAlerta',
                Servicio:$('#hdCodServicio').val(),
                FechaAlerta:$('#txtFechaAlertaTelefono').val(),
                Descripcion:$.trim( $('#txtDescripcionAlertaTelefono').val() ),
                ClienteCartera:idClienteCartera,
                UsuarioCreacion:$('#hdCodUsuario').val(),
                UsuarioServicio : $('#hdCodUsuarioServicio').val()
            },
            beforeSend : function ( ) {
                $('#txtFechaAgendaN').val($('#txtFechaAlertaTelefono').val());                
                _displayBeforeSend('Guardando Alerta...',250);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if(obj.rst){
                    $('#dialogAlertaTelefono').dialog('close');
                    $('#dialogAlertaTelefono').find(':text,textarea').val('');
                    $('#txtAbonadoAlertaTelefono').empty();
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'200px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                    /******************/
                    load_alertas_recientes_hoy()
                    $('#hdAlerta').val('1');                    
                /******************/
                //AtencionClienteDAO.loadInitializeAlertas();
                }else{
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'200px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        })
    },    
	select_saldo_inicial : function ( idClienteCartera ) {
		//~ Vic I
		$.ajax({
			url : this.url,
			type : 'POST',
			dataType : 'json',
			data : {
				command:'atencion_cliente',
				action:'SaldoInicialNroContrato',
				ClienteCartera:idClienteCartera
			},
			beforeSend : function ( ) {
				$('#dialogSaldoInicialVigente .sltNroContrato').html("Loading...");
				$('#dialogSaldoInicialVigente .grillaSaldoInicialVigente').html("");
			},
			success : function ( obj ) {
				console.log(obj);
				var htmlV = " <label for='sltNroContratoSaldo'>Nro. Contrato:&nbsp;</label>" +
							"<select id='sltNroContratoSaldo' class='combo' onchange=\"AtencionClienteDAO.listar_saldo_inicial($(this).val());\" ><option value='0'>-- Seleccione --</option>";
				jQuery.each(obj.data, function(inPa, vaPa) {
					htmlV += "<option value='"+vaPa.valor+"'>&nbsp;&nbsp;" + vaPa.contrato + "&nbsp;&nbsp;</option>";
				});
				htmlV += "</select>";
				$('#dialogSaldoInicialVigente .sltNroContrato').html(htmlV);
			},
			error : function ( ) {
				$('#dialogSaldoInicialVigente .sltNroContrato').html("Error de Consulta");
			}
		});
		//~ Vic F
	},
	listar_saldo_inicial : function ( idClienteCartera ) {
		//~ Vic I
		if(idClienteCartera==0) {
			$('#dialogSaldoInicialVigente .grillaSaldoInicialVigente').html("Seleccionar otro Nro. de Contrato...");
			return false;
		}
		$.ajax({
			url : this.url,
			type : 'POST',
			dataType : 'json',
			data : {
				command:'atencion_cliente',
				action:'SaldoInicialListar',
				ClienteCartera:idClienteCartera
			},
			beforeSend : function ( ) {
				$('#dialogSaldoInicialVigente .grillaSaldoInicialVigente').html("Loading...");
			},
			success : function ( obj ) {
				var htmlV = "<table border='1' cellpadding='3' cellspacing='0' bordercolor='#000000' style='border-collapse:collapse;' > " +
								"<tr><th>Divisa</th><th>SaldoHoy</th><th>DiasVenc</th><th>FinCumpli</th><th>FProceso</th><th>Producto</th><th>Sub-Producto</th></tr>";
				jQuery.each(obj.data, function(inPa, vaPa) {
					htmlV += "<tr>";
					jQuery.each(vaPa, function(inHi, vaHi) {
						htmlV += "<td align='left'>&nbsp;&nbsp;" + vaHi + "&nbsp;&nbsp;</td>";
					});
					htmlV += "</tr>";
				});
				htmlV += "</table>";
				$('#dialogSaldoInicialVigente .grillaSaldoInicialVigente').html(htmlV);
			},
			error : function ( ) {
				$('#dialogSaldoInicialVigente .grillaSaldoInicialVigente').html("Error de Consulta");
			}
		});
		//~ Vic F
	},
	select_contrato_cuota : function ( idClienteCartera ) {
		//~ Vic I
		$.ajax({
			url : this.url,
			type : 'POST',
			dataType : 'json',
			data : {
				command:'atencion_cliente',
				action:'CuotasNroContrato',
				ClienteCartera:idClienteCartera
			},
			beforeSend : function ( ) {
				$('#dialogCuotas .sltNroContratoCuota').html("Loading...");
				$('#dialogCuotas .grillaCuotas').html("");
			},
			success : function ( obj ) {
				var htmlV = " <label for='sltNroContratoCuota'>Nro. Contrato:&nbsp;</label>" +
							"<select id='sltNroContratoCuota' class='combo' onchange=\"AtencionClienteDAO.listar_cuotas($(this).val());\" ><option value='0'>-- Seleccione --</option>";
				jQuery.each(obj.data, function(inCuo, vaCuo) {
					htmlV += "<option value='"+vaCuo.valor+"'>&nbsp;&nbsp;" + vaCuo.contrato + "&nbsp;&nbsp;</option>";
				});
				htmlV += "</select>";
				$('#dialogCuotas .sltNroContratoCuota').html(htmlV);
			},
			error : function ( ) {
				$('#dialogCuotas .sltNroContratoCuota').html("Error de Consulta");
			}
		});
		//~ Vic F
	},
	listar_cuotas : function ( idClienteCartera ) {
		//~ Vic I
		if(idClienteCartera==0) {
			$('#dialogCuotas .grillaCuotas').html("Seleccionar otro Nro. de Contrato...");
			return false;
		}
		$.ajax({
			url : this.url,
			type : 'POST',
			dataType : 'json',
			data : {
				command:'atencion_cliente',
				action:'ListarCuotasPendientes',
				ClienteCartera:idClienteCartera
			},
			beforeSend : function ( ) {
				$('#dialogCuotas .grillaCuotas').html("Loading...");
			},
			success : function ( obj ) {
				var htmlV = "<table border='1' cellpadding='3' cellspacing='0' bordercolor='#000000' style='border-collapse:collapse;' > " +
								"<tr><th>Vencimiento</th><th>Impagocap</th><th>Impagoint</th><th>Impago</th><th>Impagocom</th><th>Moneda</th><th>Total</th></tr>";
				jQuery.each(obj.data, function(inPa, vaPa) {
					htmlV += "<tr>";
					jQuery.each(vaPa, function(inHi, vaHi) {
						htmlV += "<td align='left'>&nbsp;&nbsp;" + vaHi + "&nbsp;&nbsp;</td>";
					});
					htmlV += "</tr>";
				});
				htmlV += "</table>";
				$('#dialogCuotas .grillaCuotas').html(htmlV);
			},
			error : function ( ) {
				$('#dialogCuotas .grillaCuotas').html("Error de Consulta");
			}
		});
		//~ Vic F
	},
	select_contrato_fiador : function ( idClienteCartera ) {
		//~ Vic I
		$.ajax({
			url : this.url,
			type : 'POST',
			dataType : 'json',
			data : {
				command:'atencion_cliente',
				action:'FiadioresNroContrato',
				ClienteCartera:idClienteCartera
			},
			beforeSend : function ( ) {
				$('#dialogFiadores .divNroContratoFiador').html("Loading...");
				$('#dialogFiadores .grillaFiadores').html("");
			},
			success : function ( obj ) {
				var htmlV = " <label>Nro. Contrato:&nbsp;</label>" +
							"<select id='sltNroContratoFiador' class='combo' onchange=\"AtencionClienteDAO.listar_fiadores($(this).val());\" ><option value='0'>-- Seleccione --</option>";
				jQuery.each(obj.data, function(inCuo, vaCuo) {
					htmlV += "<option value='"+vaCuo.valor+"'>&nbsp;&nbsp;" + vaCuo.contrato + "&nbsp;&nbsp;</option>";
				});
				htmlV += "</select>";
				$('#dialogFiadores .divNroContratoFiador').html(htmlV);
			},
			error : function ( ) {
				$('#dialogFiadores .divNroContratoFiador').html("Error de Consulta");
			}
		});
		//~ Vic F
	},
	listar_fiadores : function ( idClienteCartera ) {
		//~ Vic I
		if(idClienteCartera==0) {
			$('#dialogFiadores .grillaFiadores').html("Seleccionar otro Nro. de Contrato...");
			return false;
		}
		$.ajax({
			url : this.url,
			type : 'POST',
			dataType : 'json',
			data : {
				command:'atencion_cliente',
				action:'ListarFiadorPendientes',
				ClienteCartera:idClienteCartera
			},
			beforeSend : function ( ) {
				$('#dialogFiadores .grillaFiadores').html("Loading...");
			},
			success : function ( obj ) {
				var htmlV = "<table border='1' cellpadding='3' cellspacing='0' bordercolor='#000000' style='border-collapse:collapse;' > " +
								"<tr><th>num_contratogar</th><th>tipo_gar</th><th>subtipo_gar</th><th>mon_gar</th><th>imp_gar</th><th>sit_gar</th><th>fecha_sit</th>"
								+"<th>direcc_inmueblehip</th><th>placa_vehiculoprend</th><th>cod_centralfiador</th><th>nombre_fiador</th>"
								+"<th>direcc_fiador</th><th>ciudad</th><th>cod_postal</th><th>provincia</th><th>tel_particular</th><th>tel_trabajo</th><th>tel_movil</th>"
								+"<th>tel_4</th><th>tel_5</th></tr>";
				jQuery.each(obj.data, function(inPa, vaPa) {
					htmlV += "<tr>";
					jQuery.each(vaPa, function(inHi, vaHi) {
						htmlV += "<td align='left'>&nbsp;&nbsp;" + vaHi + "&nbsp;&nbsp;</td>";
					});
					htmlV += "</tr>";
				});
				htmlV += "</table>";
				$('#dialogFiadores .grillaFiadores').html(htmlV);
			},
			error : function ( ) {
				$('#dialogFiadores .grillaFiadores').html("Error de Consulta");
			}
		});
		//~ Vic F
	},
    save_agendado : function ( ) {
        $.ajax({
            url : this.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'GuardarAgendado',
                Servicio:$('#hdCodServicio').val(),
                ClienteCartera:$('#IdClienteCarteraMain').val(),
                Observacion:$.trim( $('#txtObservacionAgendar').val() ),
                UsuarioCreacion:$('#hdCodUsuario').val(),
                //TipoGestion:$('#cbAgendarTipoGestion').val(),
                Final:$('#cbAgendarFinal').val(),
                FechaAgendar:$('#txtFechaAgendar').val(),
                FechaCP:$.trim($('#txtAgendarFechaCP').val()),
                MontoCP:$.trim($('#txtAgendarMontoCP').val())
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Guardando Agendado...',250);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if(obj.rst){
                    $('#tdAbonadoAgendar').empty();
                    $('#IdClienteCarteraAgendar').val('');
                    $('#txtObservacionAgendar').val('');
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                }else{
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'250px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                _noneBeforeSend();
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    save_direccion : function ( ) {
        $.ajax({
            url : this.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'save_direccion',
                Cliente:$('#IdClienteCampoMain').val(),
                CodigoCliente : $('#CodigoClienteCampoMain').val(),
                IdClienteCartera : $('#IdClienteCarteraCampoMain').val(),
                Cartera : $('#IdCarteraCampoMain').val(),
                IdCuenta : ('['+$('#table_cuenta_aplicar_gestion_visita').find(':checked').map(function(){ return '{"cuenta":"'+$(this).val()+'"}'; }).get().join(",")+']'),
                Direccion : $.trim( $('#txtCampoDireccionDireccion').val() ),
                Referencia : $.trim( $('#txtCampoDireccionDireccionReferencia').val() ),
                Ubigeo : $.trim( $('#txtCampoDireccionUbigeo').val() ),
                Departamento : $.trim( $('#txtCampoDireccionDepartamento').val() ),
                Provincia : $.trim( $('#txtCampoDireccionProvincia').val() ),
                Distrito : $.trim( $('#txtCampoDireccionDistrito').val() ),
                Origen : $('#cbCampoDireccionOrigen').val(),
                TipoReferencia : $('#cbCampoDireccionReferencia').val(),
                UsuarioCreacion : $('#hdCodUsuario').val(),
                IsCampo : 1,
                Observacion : $.trim( $('#txtCampoDireccionObservacion').val() )
            },
            beforeSend : function ( obj ) {
                _displayBeforeSend('Guardando Direccion...',250);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if(obj.rst){
                
                    var xdt = $('textarea');
                    
                    $.data( xdt[1] , obj.id, { cuenta : obj.cuenta , est : 'NUEVO' } );
                
                    $('#table_campo_direcciones').jqGrid().trigger('reloadGrid');
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                    /***********/
                    
                    var html = '';
                    html+='<option value="'+obj.id+'">'+$.trim( $('#txtCampoDireccionDireccion').val() )+'</option>';
                    $('#cbCampoDireccionVisita').append(html);
                    
                /***********/
                }else{
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'250px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                }
            },
            error : this.error_ajax
        });
    },
    update_direccion : function ( ) {
        $.ajax({
            url : this.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'update_direccion',
                Id:$('#HdIdDireccionCampo').val(),
                Direccion:$.trim( $('#txtCampoDireccionDireccion').val() ),
                Referencia:$.trim( $('#txtCampoDireccionDireccionReferencia').val() ),
                Ubigeo:$.trim( $('#txtCampoDireccionUbigeo').val() ),
                Departamento:$.trim( $('#txtCampoDireccionDepartamento').val() ),
                Provincia:$.trim( $('#txtCampoDireccionProvincia').val() ),
                Distrito:$.trim( $('#txtCampoDireccionDistrito').val() ),
                Origen:$('#cbCampoDireccionOrigen').val(),
                TipoReferencia:$('#cbCampoDireccionReferencia').val(),
                UsuarioModificacion:$('#hdCodUsuario').val(),
                Observacion: $.trim( $('#txtCampoDireccionObservacion').val() )
            },
            beforeSend : function ( obj ) {
                _displayBeforeSend('Actualizando Direccion...',250);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if(obj.rst){
                    $('#table_campo_direcciones').jqGrid().trigger('reloadGrid');
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                }else{
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'250px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                }
            },
            error : this.error_ajax
        });
    },
    save_telefono : function ( ) {
        $.ajax({
            url : this.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'save_telefono',
                Cliente:$('#IdClienteCampoMain').val(),
                CodigoCliente : $('#CodigoClienteCampoMain').val(),
                IdClienteCartera : $('#IdClienteCarteraCampoMain').val(),
                Cartera:$('#IdCarteraCampoMain').val(),
                IdCuenta : ('['+$('#table_cuenta_aplicar_gestion_visita').find(':checked').map(function(){ return '{"cuenta":"'+$(this).val()+'"}'; }).get().join(",")+']'),
                Numero:$.trim( $('#txtCampoTelefonoNumero').val() ),
                Anexo:$.trim( $('#txtCampoTelefonoAnexo').val() ),
                Origen:$('#cbCampoTelefonoOrigen').val(),
                LineaTelefono:$('#cbCampoTelefonoLinea').val(),
                TipoReferencia:$('#cbCampoTelefonoReferencia').val(),
                TipoTelefono:$('#cbCampoTelefonoTipo').val(),
                UsuarioCreacion:$('#hdCodUsuario').val(),
                IsCampo : 1,
                Observacion: $.trim( $('#txtCampoTelefonoObservacion').val() )
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Guardando Telefono...',250);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if(obj.rst){
                    $('#table_campo_telefono').jqGrid().trigger('reloadGrid');
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                }else{
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'250px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                }
            },
            error : this.error_ajax
        });
    },
    update_telefono : function ( ) {
        $.ajax({
            url : this.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'update_telefono',
                Id:$('#HdIdTelefonoCampo').val(),
                Numero:$.trim( $('#txtCampoTelefonoNumero').val() ),
                Anexo:$.trim( $('#txtCampoTelefonoAnexo').val() ),
                TipoReferencia:$('#cbCampoTelefonoReferencia').val(),
                TipoTelefono:$('#cbCampoTelefonoTipo').val(),
                Origen:$('#cbCampoTelefonoOrigen').val(),
                LineaTelefono:$('#cbCampoTelefonoLinea').val(),
                UsuarioModificacion:$('#hdCodUsuario').val(),
                Observacion: $.trim( $('#txtCampoTelefonoObservacion').val() )
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Actualizando Telefono...',250);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if(obj.rst){
                    $('#table_campo_telefono').jqGrid().trigger('reloadGrid');
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                }else{
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'250px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                }
            },
            error : this.error_ajax
        });
    },
    save_visita : function ( xCuentas,xiddireccion_campo ) {
    
        var xdt = $('textarea');
        
        $.ajax({
            url : this.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command : 'atencion_cliente',
                action : 'save_visita',
                ClienteCartera : $('#IdClienteCarteraCampoMain').val(),
                IdCliente : $('#IdClienteCampoMain').val(),
                //Direccion : $('#cbCampoDireccionVisita').val(),
                Direccion : xiddireccion_campo,
                Servicio : $('#hdCodServicio').val(),
                Peso : $('#cbCampoFinal option:selected').attr('weight'),
                IdCarga : $('#cbCampoFinal option:selected').attr('carga'),
                Final : $('#cbCampoFinal').val(),
                Observacion : $.trim( $('#txtCampoObservacion').val() ),
                FechaVisita : $('#txtCampoFechaVisita').val(),
                FechaRecepcion : $('#txtCampoFechaRecepcion').val(),
                Notificador : $('#cbNotificadorCampoVisita').val(),
                Contacto : $('#cbCampoContacto').val(),
                MotivoNoPago : $('#cbCampoMotivoNoPago').val(),
                Parentesco : $('#cbCampoParentesco').val(),
                HoraUbicacion : $('#txtCampoHoraUbicacion').val(),
                HoraSalida : $('#txtCampoHoraSalida').val(),
                NombreContacto : $.trim( $('#txtCampoNombreContacto').val() ),
                Cuentas : xCuentas,
                DescripcionInmueble : $('#txtCampoDescripcionInmueble').val(),
                UsuarioCreacion : $('#hdCodUsuario').val(),
                UsuarioServicio : $('#hdCodUsuarioServicio').val(),
                DireccionEst : $.data( xdt[1] ),
                idestado_cliente : $("#cbCampoEstadoCliente").val()
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Guardando Visita...',250);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if(obj.rst){
                    $.removeData( xdt[1] );
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                    $('#table_campo_visita').jqGrid().trigger('reloadGrid');
                    var visitas = parseFloat( $('#lbCantidadVisitasCampo').text() );
                    $('#lbCantidadVisitasCampo').text( visitas + 1 );
                    // cancel_visita();
                    reloadJQGRID_visita_2($('#IdClienteCarteraCampoMain').val());

                }else{
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'250px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                }
				$("#txtCampoCodigoSearch").val('').focus();
            },
            error : function ( ) {
                _noneBeforeSend();
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    update_visita : function ( ) {
        $.ajax({
            url : this.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'update_visita',
                IdTransaccion:$('#HdIdTransaccionCampo').val(),
                IdVisita:$('#HdIdVisitaCampo').val(),
                IdCpg:$('#HdIdCpgCampo').val(),
                Direccion:$('#HdCodIdCampoDireccion').val(),
                Prioridad:$('#cbCampoPrioridadVisita').val(),
                //TipoGestion:$('#cbCampoTipoGestion').val(),
                Final:$('#cbCampoFinal').val(),
                Observacion:$.trim( $('#txtCampoObservacion').val() ),
                FechaVisita:$('#txtCampoFechaVisita').val(),
                FechaCP:$('#txtCampoFechaCP').val(),
                MontoCP:$.trim( $('#txtCampoMontoCP').val() ),
                UsuarioModificacion:$('#hdCodUsuario').val()
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Actualizando Visita...',250);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if(obj.rst){
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                    $('#table_campo_visita').jqGrid().trigger('reloadGrid');
                }else{
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'250px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                _noneBeforeSend();
                this.error_ajax
            }
        });
    },
    LoadOrigen : function ( ) {
        $.ajax({
            url : this.url,
            async : false,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListarOrigen'
            },
            success : function ( obj ) {
                AtencionClienteDAO.FillCampoTelefonoOrigen(obj);
                AtencionClienteDAO.FillCampoDireccionOrigen(obj);
                AtencionClienteDAO.FillAtencionClienteDireccionOrigen(obj);
                AtencionClienteDAO.FillAtencionClienteTelefonoOrigen(obj);
            },
            error : this.error_ajax
        });
    },
    FillCampoTelefonoOrigen : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idorigen+'">'+data.nombre+'</option>';
        });
        $('#cbCampoTelefonoOrigen').html(html);
    },
    FillCampoDireccionOrigen : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idorigen+'">'+data.nombre+'</option>';
        });
        $('#cbCampoDireccionOrigen').html(html);
    },
    FillAtencionClienteDireccionOrigen : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idorigen+'">'+data.nombre+'</option>';
        });
        $('#cbOrigenDireccionAtencionCliente').html(html);
    },
    FillAtencionClienteTelefonoOrigen : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idorigen+'">'+data.nombre+'</option>';
        });
        $('#cbOrigenTelefonoAtencion').html(html);
    },
    LoadTipoReferencia : function ( ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            async : false,
            data : {
                command:'atencion_cliente',
                action:'ListarTipoReferencia'
            },
            success : function ( obj ) {
                AtencionClienteDAO.FillCampoTelefonoTipoReferencia(obj);
                AtencionClienteDAO.FillCampoDireccionTipoReferencia(obj);
                AtencionClienteDAO.FillAtencionClienteTelefonoTipoReferencia(obj);
                AtencionClienteDAO.FillAtencionClienteDireccionTipoReferencia(obj);
            },
            error : this.error_ajax
        });
    },
    FillCampoTelefonoTipoReferencia : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idtipo_referencia+'">'+data.nombre+'</option>';
        });
        $('#cbCampoTelefonoReferencia').html(html);
    },
    FillCampoDireccionTipoReferencia : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idtipo_referencia+'">'+data.nombre+'</option>';
        });
        $('#cbCampoDireccionReferencia').html(html);
    },
    FillAtencionClienteTelefonoTipoReferencia : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idtipo_referencia+'">'+data.nombre+'</option>';
        });
        $('#cbReferenciaTelefonoAtencion').html(html);
    },
    FillAtencionClienteDireccionTipoReferencia : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idtipo_referencia+'">'+data.nombre+'</option>';
        });
        $('#cbReferenciaDireccionAtencionCliente').html(html);
    },
    LoadTipoTelefono : function ( ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            async : false,
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListarTipoTelefono'
            },
            success : function ( obj ) {
                AtencionClienteDAO.FillCampoTelefonoTipoTelefono(obj);
                AtencionClienteDAO.FillAtencionClienteTelefonoTipoTelefono(obj);
            },
            error : this.error_ajax
        });
    },
    EnviarCargo:function(xidcuenta){
        $.ajax({
            url:this.url,
            type:'GET',
            async:false,
            dataType:'json',
            data:{
                command:'atencion_cliente',
                action:'EnviarCargo',
                idcuenta:xidcuenta,
                idcliente_cartera:$('#IdClienteCarteraMain').val(),
                usuario_creacion:$('#hdCodUsuario').val()
            },
            success:function(obj){
                alert('Enviar Cargo actualizado');
            },
            error:this.error_ajax
        });
    },  		
    FillCampoTelefonoTipoTelefono : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idtipo_telefono+'">'+data.nombre+'</option>';
        });
        $('#cbCampoTelefonoTipo').html(html);
    },
    FillAtencionClienteTelefonoTipoTelefono : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idtipo_telefono+'">'+data.nombre+'</option>';
        });
        $('#cbTipoTelefonoAtencion').html(html);
    },
    
    /*Piro*/
    loadCampania:function(){
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
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
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    ListCartera : function ( idCampania, f_fill, idcbo ) {
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListCartera',
                Campania:idCampania
            },
            success : function ( obj ) {
                f_fill(obj,idcbo);
            },
            error : function ( ) { 
                AtencionClienteDAO.error_ajax();
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
    ListDomicilioByCode : function ( codigoCliente, f_fill, idcbo ) { //piro
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListDomicilio',
                CodigoCliente :codigoCliente
            },
            success : function ( obj ) {
                f_fill(obj,idcbo);
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    FillCargaDomicilio : function ( obj, idcbo ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.iddireccion+'">'+data.direccion+'</option>';
        });
        for( i=0;i<idcbo.length;i++ ) {
            $('#'+idcbo[i].id).html(html);
        }

    },



    /**/


    LoadLineaTelefono : function ( ) {
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'GET',
            async : false,
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListarLineaTelefono'
            },
            success : function ( obj ) {
                AtencionClienteDAO.FillAtencionClienteLineaTelefono(obj);
                AtencionClienteDAO.FillCampoLineaTelefono(obj);
                AtencionClienteDAO.LineasTelefono = obj;
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    FillAtencionClienteLineaTelefono : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idlinea_telefono+'">'+data.nombre+'</option>';
        });
        $('#cbLineaTelefonoAtencion').html(html);
    },
    FillCampoLineaTelefono : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idlinea_telefono+'">'+data.nombre+'</option>';
        });
        $('#cbCampoTelefonoLinea').html(html);
    },
    LoadCargaFinal : function ( xidservicio, function_fill_carga_final ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            async : false,
            data : {
                command:'atencion_cliente',
                action:'ListarCargaFinal' , 
                idservicio:xidservicio
            },
            success : function ( obj ) {
                if(function_fill_carga_final){
                    function_fill_carga_final(obj);
                }else{
                    AtencionClienteDAO.FillAtencionCargaFinal(obj);
                    AtencionClienteDAO.FillCampoCargaFinal(obj);
                    AtencionClienteDAO.FillLlamadaCargaFinal(obj);
                }
            },
            error : function ( ) { 
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    FillAtencionCargaFinal : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idcarga_final+'">'+data.nombre+'</option>';
        });
        $('#cbAgendarCargaFinal').html(html);
    },
    FillCampoCargaFinal : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idcarga_final+'">'+data.nombre+'</option>';
        });
        $('#cbCampoCargaFinal').html(html);
    },
    FillLlamadaCargaFinal : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idcarga_final+'">'+data.nombre+'</option>';
        });
        $('#cbCargaFinalLlamada').html(html);
    },
    LoadClaseFinal : function ( function_fill_clase_final ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListarClaseFinal'
            },
            success : function ( obj ) {
                if(function_fill_clase_final){
                    function_fill_clase_final(obj);
                }else{
                    AtencionClienteDAO.FillAtencionClaseFinal(obj);
                    AtencionClienteDAO.FillCampoClaseFinal(obj);
                }
            },
            error : function ( ) { 
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    FillAtencionClaseFinal : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idclase_final+'">'+data.nombre+'</option>';
        });
        $('#cbAgendarClaseFinal').html(html);
    },
    FillCampoClaseFinal : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idclase_final+'">'+data.nombre+'</option>';
        });
        $('#cbCampoClaseFinal').html(html);
    },
    LoadTipoGestion : function ( function_fill_tipo_gestion ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListarTipoGestion'
            },
            success : function ( obj ) {
                if( function_fill_tipo_gestion ){
                    function_fill_tipo_gestion(obj);
                }else{
                    AtencionClienteDAO.FillAtencionTipoGestion(obj);
                    AtencionClienteDAO.FillCampoTipoGestion(obj);
                }
            },
            error : function ( ) { 
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    FillAtencionTipoGestion : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idtipo_gestion+'">'+data.nombre+'</option>';
        });
        $('#cbAgendarTipoGestion').html(html);
    },
    FillCampoTipoGestion : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idtipo_gestion+'">'+data.nombre+'</option>';
        });
        $('#cbCampoTipoGestion').html(html);
    },
    LoadNivel : function ( xidservicio, xidcarga_final, xidtipo_final, function_fill_nivel ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListarNivel', 
                idservicio : xidservicio, 
                idcarga_final : xidcarga_final,
                idtipo_final : xidtipo_final
            },
            success : function ( obj ) {
                if(function_fill_nivel){
                    function_fill_nivel(obj);
                }else{
                    AtencionClienteDAO.FillAtencionNivel(obj);
                    AtencionClienteDAO.FillCampoNivel(obj);
                    AtencionClienteDAO.FillLlamadaNivel(obj);
                }
            },
            error : function ( ) { 
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    FillAtencionNivel : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idnivel+'">'+data.nombre+'</option>';
        });
        $('#cbAgendarNivel').html(html);
    },
    FillCampoNivel : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idnivel+'">'+data.nombre+'</option>';
        });
        $('#cbCampoNivel').html(html);
    },
    FillLlamadaNivel : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idnivel+'">'+data.nombre+'</option>';
        });
        $('#cbNivelLlamada').html(html);
    },
    LoadTipoFinal : function ( xidservicio, xidcarga_final, function_fill_tipo_final ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListarTipoFinal', 
                idservicio : xidservicio,
                idcarga_final : xidcarga_final
            },
            success : function ( obj ) {
                if(function_fill_tipo_final){
                    function_fill_tipo_final(obj);
                }else{
                    AtencionClienteDAO.FillAtencionTipoFinal(obj);
                    AtencionClienteDAO.FillCampoTipoFinal(obj);
                    AtencionClienteDAO.FillLlamadaTipoFinal(obj);
                }
            },
            error : function ( ) { 
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    LoadFinalServicioDetalle : function ( xidservicio, xidcarga_final, xidtipo_final, xidnivel, function_fill_tipo_final ) {
				
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListarFinalServicioDetalle', 
                idservicio : xidservicio,
                idcarga_final : xidcarga_final,
                idtipo_final : xidtipo_final,
                idnivel : xidnivel 
            },
            success : function ( obj ) {
						   		
                function_fill_tipo_final(obj);
								
            },
            error : function ( ) { 
                AtencionClienteDAO.error_ajax();
            }
        });
				
    },
    FillAtencionTipoFinal : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idtipo_final+'">'+data.nombre+'</option>';
        });
        $('#cbAgendarTipoFinal').html(html);
    },
    FillCampoTipoFinal : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idtipo_final+'">'+data.nombre+'</option>';
        });
        $('#cbCampoTipoFinal').html(html);
    },
    FillLlamadaTipoFinal : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idtipo_final+'">'+data.nombre+'</option>';
        });
        $('#cbTipoFinalLlamada').html(html);
    },
    LoadFinalServicioAgendar : function ( function_fill_final) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListarFinalServicioAgendar',
                Servicio:$('#hdCodServicio').val()
            //Carga:$('#cbAgendarCargaFinal').val(),
            //Tipo:$('#cbAgendarTipoFinal').val(),
            //Nivel:$('#cbAgendarNivel').val()
            },
            success : function ( obj ) {
                if(function_fill_final){
                    function_fill_final(obj);
                }else{
                    AtencionClienteDAO.FillAtencionFinalServicio(obj);
                //AtencionClienteDAO.FillCampoFinalServicio(obj);
                }
            },
            error : function ( ) { 
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    FillAtencionFinalServicio : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idfinal+'">'+data.nombre+'</option>';
        });
        $('#cbAgendarFinal').html(html);
    },
    LoadFinalServicioVisita : function ( function_fill_final) {
        //$.ajax({
        //					   url : this.url,
        //					   type : 'GET',
        //					   dataType : 'json',
        //					   data : {
        //						   	command:'atencion_cliente',
        //					   		action:'ListarFinalServicioVisita',
        //							Servicio:$('#hdCodServicio').val(),
        //							Carga:$('#cbCampoCargaFinal').val(),
        //							Tipo:$('#cbCampoTipoFinal').val(),
        //							Nivel:$('#cbCampoNivel').val()
        //							},
        //					   success : function ( obj ) {
        //						   		if(function_fill_final){
        //							   		function_fill_final(obj);
        //								}else{
        //									//AtencionClienteDAO.FillAtencionFinalServicio(obj);
        //									AtencionClienteDAO.FillCampoFinalServicio(obj);
        //								}
        //								
        //						   },
        //					   error : function ( ) { 
        //					   			AtencionClienteDAO.error_ajax();
        //					   		}
        //					   });
					   
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListarFinalServicioVisita',
                Servicio:$('#hdCodServicio').val()
            },
            success : function ( obj ) {
                if(function_fill_final){
                    function_fill_final(obj);
                }else{
                    //AtencionClienteDAO.FillAtencionFinalServicio(obj);
                    AtencionClienteDAO.FillCampoFinalServicio(obj);
                }
								
            },
            error : function ( ) { 
                AtencionClienteDAO.error_ajax();
            }
        });
					   
    },
    FillCampoFinalServicio : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idfinal+'">'+data.nombre+'</option>';
        });
        $('#cbCampoFinal').html(html);
        var rf=$('#cbCampoFinal').attr('title');
        $('#cbCampoFinal').val(rf);
    },
    LoadFinalServicioLlamada : function ( function_fill_final) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListarFinalServicioLlamada',
                Servicio:$('#hdCodServicio').val(),
                Carga:$('#cbCargaFinalLlamada').val(),
                Tipo:$('#cbTipoFinalLlamada').val(),
                Nivel:$('#cbNivelLlamada').val()
            },
            success : function ( obj ) {
                if(function_fill_final){
                    function_fill_final(obj);
                }else{
                    //AtencionClienteDAO.FillAtencionFinalServicio(obj);
                    AtencionClienteDAO.FillLlamadaFinalServicio(obj);
                }
            },
            error : function ( ) { 
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    FillLlamadaFinalServicio : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idfinal+'">'+data.nombre+'</option>';
        });
        $('#cbFinalLlamada').html(html);
        var rf=$('#cbFinalLlamada').attr('title');
        $('#cbFinalLlamada').val(rf);
    },
    loadAlertasRecientes : function ( xidservicio, xidusuario_servicio, f_success, f_before ) {
        var date = new Date();
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : { 
                command : 'atencion_cliente', 
                action : 'LoadAlertasRecientes', 
                Servicio : xidservicio , 
                Usuario : $('#hdCodUsuario').val(),
                UsuarioServicio: xidusuario_servicio , 
                Hora : ((((date.getHours()).toString().length==1)?'0'+date.getHours():date.getHours()) + ':' + (((date.getMinutes()).toString().length==1)?'0'+date.getMinutes():date.getMinutes()) + ':' + (((date.getSeconds()).toString().length==1)?'0'+date.getSeconds():date.getSeconds())) ,
                Fecha : (date.getFullYear()+'-'+(((date.getMonth()).toString().length==1)?'0'+(date.getMonth()+1):(date.getMonth()+1))+'-'+(((date.getDate()).toString().length==1)?'0'+date.getDate():date.getDate()))
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
    loadInitializeAlertas : function ( ) {
        var date = new Date();
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            async : false,
            data : {
                command : 'atencion_cliente',
                action : 'LoadInitAlertas',
                Cartera : $('#cbAtencionGlobalesCartera').val(),
                Servicio : $('#hdCodServicio').val(),
                UsuarioServicio  :$('#hdCodUsuarioServicio').val(),
                Hora : ((((date.getHours()).toString().length==1)?'0'+date.getHours():date.getHours()) + ':' + (((date.getMinutes()).toString().length==1)?'0'+date.getMinutes():date.getMinutes()) + ':' + (((date.getSeconds()).toString().length==1)?'0'+date.getSeconds():date.getSeconds())) ,
                Fecha : (date.getFullYear()+'-'+(((date.getMonth()).toString().length==1)?'0'+(date.getMonth()+1):(date.getMonth()+1))+'-'+(((date.getDate()).toString().length==1)?'0'+date.getDate():date.getDate()))
            },
            success : function ( obj ) {
                //AlertasUsuario=Array();
                /*for(i=0;i<obj.length;i++){
									AlertasUsuario.push(eval(obj[i]));
								}*/
                //AlertasUsuario=eval(obj.init);
                var html = '';
                for( i=0;i<obj.hoy.length;i++ ) {
                    html+='<tr>';
                    html+='<td style="border-bottom:1px solid #6F9DD9;width:440px;">';
                    html+='<table cellpadding="0" cellspacing="0" border="0">';
                    html+='<tr>';
                    html+='<td align="center" style="width:30px;height:30px;"><img src="../img/bell.png"></td>';
                    html+='<td style="width:240px;height:30px;color:#000000;">'+obj.hoy[i].cliente+'</td>';
                    html+='<td align="center" style="width:102px;height:30px;color:#000000;">'+obj.hoy[i].fecha_format+'</td>';
                    html+='<td rowspan="2" style="width:30px;" align="center" onclick="delete_alerta('+obj.hoy[i].idalerta+',$(this).parent().parent().parent().parent().parent())"><span class="ui-icon ui-icon-cancel"></span></td>';
                    html+='<td rowspan="2" stylw="width:30px;" align="center" onclick="atender_cliente_alerta_espera('+obj.hoy[i].idcliente_cartera+','+obj.hoy[i].idalerta+',$(this).parent().parent().parent().parent().parent())" ><span class="ui-icon ui-icon-check"></span></td>';
                    html+='</tr>';
                    html+='<tr>';
                    html+='<td></td>';
                    html+='<td colspan="2" style="white-space:pre-wrap;color:#808080;" >'+obj.hoy[i].descripcion+'</td>';
                    html+='</tr>';
                    html+='</table>';
                    html+='</td>';
                    html+='</tr>';
                }
                $('#alertaLayerHoyAtencionCliente').find('table:first').html(html);
                $('#alertaLayerHoyAtencionCliente').find('table:first tr').find('span').parent().hover(function(){
                    $(this).addClass('ui-state-default');
                },function(){
                    $(this).removeClass('ui-state-default');
                });
                $('#alertaLayerHoyAtencionCliente table:first tr').click(function() {
                    $('#alertaLayerAyerAtencionCliente table:first tr').find('td').removeClass('ui-state-highlight');
                    $('#alertaLayerAntiguasAtencionCliente table:first tr').find('td').removeClass('ui-state-highlight');
                    $(this).find('td').addClass("ui-state-highlight").parent().siblings().find('td').removeClass("ui-state-highlight");
                });
								
                var html = '';
                for( i=0;i<obj.ayer.length;i++ ) {
                    html+='<tr>';
                    html+='<td style="border-bottom:1px solid #6F9DD9;width:440px;">';
                    html+='<table cellpadding="0" cellspacing="0" border="0">';
                    html+='<tr>';
                    html+='<td align="center" style="width:30px;height:30px;"><img src="../img/bell.png"></td>';
                    html+='<td style="width:240px;height:30px;color:#000000;">'+obj.ayer[i].cliente+'</td>';
                    html+='<td align="center" style="width:102px;height:30px;color:#000000;">'+obj.ayer[i].fecha_format+'</td>';
                    html+='<td rowspan="2" style="width:30px;" align="center" onclick="delete_alerta('+obj.ayer[i].idalerta+',$(this).parent().parent().parent().parent().parent())"><span class="ui-icon ui-icon-cancel"></span></td>';
                    html+='<td rowspan="2" stylw="width:30px;" align="center" onclick="atender_cliente_alerta_espera('+obj.ayer[i].idcliente_cartera+','+obj.ayer[i].idalerta+',$(this).parent().parent().parent().parent().parent())" ><span class="ui-icon ui-icon-check"></span></td>';
                    html+='</tr>';
                    html+='<tr>';
                    html+='<td></td>';
                    html+='<td colspan="2" style="white-space:pre-wrap;color:#808080;" >'+obj.ayer[i].descripcion+'</td>';
                    html+='</tr>';
                    html+='</table>';
                    html+='</td>';
                    html+='</tr>';
                }
                $('#alertaLayerAyerAtencionCliente').find('table:first').html(html);
                $('#alertaLayerAyerAtencionCliente').find('table:first tr').find('span').parent().hover(function(){
                    $(this).addClass('ui-state-default');
                },function(){
                    $(this).removeClass('ui-state-default');
                });
                $('#alertaLayerAyerAtencionCliente table:first tr').click(function() {
                    $('#alertaLayerHoyAtencionCliente table:first tr').find('td').removeClass('ui-state-highlight');
                    $('#alertaLayerAntiguasAtencionCliente table:first tr').find('td').removeClass('ui-state-highlight');
                    $(this).find('td').addClass("ui-state-highlight").parent().siblings().find('td').removeClass("ui-state-highlight");
                });
								
                var html = '';
                for( i=0;i<obj.antigua.length;i++ ) {
                    html+='<tr>';
                    html+='<td style="border-bottom:1px solid #6F9DD9;width:440px;">';
                    html+='<table cellpadding="0" cellspacing="0" border="0">';
                    html+='<tr>';
                    html+='<td align="center" style="width:30px;height:30px;"><img src="../img/bell.png"></td>';
                    html+='<td style="width:240px;height:30px;color:#000000;">'+obj.antigua[i].cliente+'</td>';
                    html+='<td align="center" style="width:102px;height:30px;color:#000000;">'+obj.antigua[i].fecha_format+'</td>';
                    html+='<td rowspan="2" style="width:30px;" align="center" onclick="delete_alerta('+obj.antigua[i].idalerta+',$(this).parent().parent().parent().parent().parent())"><span class="ui-icon ui-icon-cancel"></span></td>';
                    html+='<td rowspan="2" stylw="width:30px;" align="center" onclick="atender_cliente_alerta_espera('+obj.antigua[i].idcliente_cartera+','+obj.antigua[i].idalerta+',$(this).parent().parent().parent().parent().parent())" ><span class="ui-icon ui-icon-check"></span></td>';
                    html+='</tr>';
                    html+='<tr>';
                    html+='<td></td>';
                    html+='<td colspan="2" style="white-space:pre-wrap;color:#808080;" >'+obj.antigua[i].descripcion+'</td>';
                    html+='</tr>';
                    html+='</table>';
                    html+='</td>';
                    html+='</tr>';
                }
                $('#alertaLayerAntiguasAtencionCliente').find('table:first').html(html);
                $('#alertaLayerAntiguasAtencionCliente').find('table:first tr').find('span').parent().hover(function(){
                    $(this).addClass('ui-state-default');
                },function(){
                    $(this).removeClass('ui-state-default');
                });
                $('#alertaLayerAntiguasAtencionCliente table:first tr').click(function() {
                    $('#alertaLayerHoyAtencionCliente table:first tr').find('td').removeClass('ui-state-highlight');
                    $('#alertaLayerAyerAtencionCliente table:first tr').find('td').removeClass('ui-state-highlight');
                    $(this).find('td').addClass("ui-state-highlight").parent().siblings().find('td').removeClass("ui-state-highlight");
                });

            //$('#alertaLayerAntiguasAtencionCliente').find('table:first tr').find('td').hover(function(){ $(this).addClass('ui-state-default'); },function(){ $(this).removeClass('ui-state-default'); });
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        });	

    },
    FillAllAtencionCliente : function ( obj ) {
				
        if( obj.length==1 ) {
            /********/
            
            $('#table_datos_adicionales_cuenta').empty();
            $('#table_cuenta').empty();
            $('#table_operaciones').empty();
            $('#table_pagos').empty();
            $('#tb_adicional_cuenta').empty();
            $('#tb_detalle_factura_operacion').empty();
            $('#lbMessageFechasCartera').empty();
            
            cancel_llamada();
            
            CountLoadTelefonos = 0 ;
            /********/
            /*$('#txtResultadoCodigoCliente').val(obj[0].codigo);
				$('#txtResultadoNombreCodigoCliente').val(obj[0].nombre);
				$('#txtResultadoNumeroDocumento').val(obj[0].numero_documento);
				$('#txtResultadoTipoDocumento').val(obj[0].tipo_documento);*/
            $('#IdClienteCartera').val(obj[0].idcliente_cartera);
            $('#IdCartera').val(obj[0].idcartera);

            /*mostrando botones de marcaciones*/
            $('#Flag_Provincia').val(obj[0].flag_provincia);
/*            if(obj[0].flag_provincia=="1"){
                $('#btnAtencionClientePhoneCallNeotel').css('display','none');
                $('#btnAtencionClientePhoneCall').css('display','block');
                $('#btnAtencionClientePhoneHungupNeotel').css('display','none');
                $('#btnAtencionClientePhoneHungup').css('display','block');
                $('#btnAtencionClienteShowAnexoNeotel').css('display','none');    
                $('#btnAtencionClienteShowAnexo').css('display','block');
            }else{
                $('#btnAtencionClientePhoneCallNeotel').css('display','block');
                $('#btnAtencionClientePhoneCall').css('display','none');
                $('#btnAtencionClientePhoneHungupNeotel').css('display','block');
                $('#btnAtencionClientePhoneHungup').css('display','none');
                $('#btnAtencionClienteShowAnexoNeotel').css('display','block');    
                $('#btnAtencionClienteShowAnexo').css('display','none');
            }*/
            
            //$('#IdCartera').val(obj[0].idcartera);
            var recibio='';
            if(obj[0].recibio_eexx==0){
                recibio='EECC : Sin descripcion';
            } 

            if(obj[0].recibio_eexx==1){
                recibio='EECC : Recibio Estado de Cuenta';
            }             

            if(obj[0].recibio_eexx==2){
                recibio='EECC : No Recibio Estado de Cuenta';
            }             

            $('#msjRecibioEecc').text(recibio);
            $('#msgmotivonopago').text("Motivo no pago: " + obj[0].ul_motivo_no_pago);
            $('#msgprovision').text("Provision: " + formato_numero(Math.round((obj[0].provision)*100/100)));            
            $('#msgsituacion').text("Situacion: " + obj[0].situacion);
            $('#msgcontacto').text("Contacto: " + obj[0].estadocontacto);


            $('#IdClienteCarteraMain').val(obj[0].idcliente_cartera);
            $('#idClienteMain').val(obj[0].idcliente);
				
            $('#CodigoClienteMain').val(obj[0].codigo);
				
            /*****************/
            $('#preGestorClienteCartera').text('GESTOR : '+obj[0].gestor);
            /*****************/
            $('#txtMontoPagoCuotificacion').val(obj[0].monto_cuota);
            /***********/
            $('#lbMessageGlobalGest').text('');
            $('#lbMessageGlobalGest').css('display','none');
            if(obj[0].estado_cliente!=''){
                $('#lbMessageGlobalGest').text(obj[0].estado_cliente);
                $('#lbMessageGlobalGest').slideDown();
            }
            if( obj[0].retiro == 1 || obj[0].estado == 0 ) {
                var obs = '';
                if( obj[0].motivo_retiro != 'null' && obj[0].motivo_retiro != null && obj[0].motivo_retiro != '' ) {
                    obs = ', RAZON : '+obj[0].motivo_retiro;
                }
                $('#lbMessageGlobalGest').text('CLIENTE RETIRADO '+obs);
                $('#lbMessageGlobalGest').slideDown();
            //$('#lbMessageGlobalGest').css('display','block');
            }
            if( obj[0].reclamo == 1 ) {
                $('#lbMessageGlobalGest').text('RECLAMO');
                $('#lbMessageGlobalGest').slideDown();
            }
            if( $('#lbMessageGlobalGest').text() == '' ) {
            	$('#lbMessageGlobalGest').text(obj[0].status);
                $('#lbMessageGlobalGest').slideDown();
            }
            /***********/
			/*16-11-2015 adicionado para la vista de fecha_creacion y fecha_modificacion de la cartera del cliente*/
            $('#txtFechaCreacion_tblDCBC').val(obj[0].fecha_creacion);
            $('#txtFechaModificacion_tblDCBC').val(obj[0].fecha_modificacion);	
            
				
            //reloadJQGRID_direccion(obj[0].codigo,obj[0].idcartera);
            //reloadJQGRID_telefono(obj[0].codigo,obj[0].idcartera);
            //reloadJQGRID_llamadas(obj[0].idcliente_cartera);
            
            listar_cliente(obj[0].idservicio, obj[0].codigo, obj[0].idcartera,obj[0].idcliente_cartera);	
            //AtencionClienteDAO.ListarCuenta();
            listar_cuenta(obj[0].idservicio,obj[0].idcartera,obj[0].idcliente_cartera);
            reloadJQGRID_numero_telefono();
            
            listar_detalle_cuenta(obj[0].idcartera,'',obj[0].codigo,this)

			$('#tabAC2Llamada').unbind('click');
			if( $('#layerTabAC2Llamada').css('display') == 'block' ) {
				reloadJQGRID_llamadas(obj[0].codigo,obj[0].idcliente);
                reloadJQGRID_visita_one(obj[0].idcliente_cartera);
			}else{
				$('#tabAC2Llamada').one('click', function ( ){ reloadJQGRID_llamadas(obj[0].codigo,obj[0].idcliente); 
                    reloadJQGRID_visita_one(obj[0].idcliente_cartera);  
                });
			}
			
			/*$('#tabAC2CuentaPagos').unbind('click');
			if( $('#layerTabAC2CuentaPagos').css('display') == 'block' ) {*/
				//listar_cuenta(obj[0].idservicio,obj[0].idcartera,obj[0].idcliente_cartera);
			/*}else{
				$('#tabAC2CuentaPagos').one('click', function ( ){ listar_cuenta(obj[0].idservicio,obj[0].idcartera,obj[0].idcliente_cartera); });
			}*/
			
			$('#tabAC2Telefonos').unbind('click');
			if( $('#layerTabAC2Telefonos').css('display') == 'block' ) {
				reloadJQGRID_telefono(obj[0].codigo,obj[0].idcartera);
			}else{
				$('#tabAC2Telefonos').one('click', function ( ){ reloadJQGRID_telefono(obj[0].codigo,obj[0].idcartera); });
			}
			
			$('#tabAC2Direcciones').unbind('click');
			if( $('#layerTabAC2Direcciones').css('display') == 'block' ) {
				reloadJQGRID_direccion(obj[0].codigo,obj[0].idcartera);
			}else{
				$('#tabAC2Direcciones').one('click', function ( ){ reloadJQGRID_direccion(obj[0].codigo,obj[0].idcartera); });
			}
            
            $('#tabAC2DFacturaDigital').unbind('click');
            if( $('#layerTabAC2FacturaDigital').css('display') == 'block' ) {
                AtencionClienteDAO.GetLineasFacturaDigitalXcliente();
                AtencionClienteDAO.ListarSupervisores();
            }else{
            
                $('#tabAC2DFacturaDigital').one('click', function ( ) {  
                    AtencionClienteDAO.GetLineasFacturaDigitalXcliente();
                    AtencionClienteDAO.ListarSupervisores();
                } );
                
            }
            
            $('#btnVisitas').unbind('click');
            if( $('#layerTabAC2Visita').css('display') == 'block' ) {
                reloadJQGRID_visita_atencion_cliente();
            }else{
                $('#btnVisitas').one('click', function( ) { reloadJQGRID_visita_atencion_cliente(); } );
            }
            
            $('#tab_ref_cuenta_pagos').unbind('click');
            $('#tabAC2DCuotidicacion').unbind('click');
            if( $('#layerTabRefCuentaPagos').css('display') == 'block' && $('#layerTabAC2Cuotificacion').css('display') == 'block' ) {
            	listar_pago_ref();
            }else if( $('#layerTabRefCuentaPagos').css('display') == 'none' && $('#layerTabAC2Cuotificacion').css('display') == 'block' ){
            	$('#tabAC2DCuotidicacion').one('click',function( ){ listar_pago_ref(); });
            }else{
            	$('#tab_ref_cuenta_pagos').one('click',function( ){ listar_pago_ref(); });
            }
            
            //reloadJQGRID_numero_telefono(obj[0].codigo,obj[0].idcartera);
            
            /**********/
            //reloadJQGRID_visita_atencion_cliente(obj[0].idcliente_cartera);
            /************/
            //reloadJQGRID_cuenta(obj[0].codigo,$('#cbAtencionGlobalesCartera').val());
            //reloadJQGRID_historico(obj[0].codigo,obj[0].idcliente);
            /***********/
            
            /***********/
            /*********/
            
            /*********/
            
            //listar_historico_cuenta(obj[0].idcliente,obj[0].idcartera);
            //listar_detalle_cuenta('','',obj[0].codigo);
            /*********/
            //$('#table_operaciones').jqGrid('setGridParam',{url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_operaciones'}).trigger('reloadGrid');
            //$('#table_pagos').jqGrid('setGridParam',{url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_pagos'}).trigger('reloadGrid');
            /*$('#table_agendados').jqGrid('setGridParam',{
                url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_agendados'
            }).trigger('reloadGrid');*/
            /***********/
            //$('#table_datos_adicionales_operacion').empty();
            
            // listar_direccion_atencion_cliente(obj[0].idcartera,obj[0].codigo);

            

            /*mantiene en el combo la ultima tipificacion*/
            
            if($('#hdCodServicio').val()!=10 && $('#hdCodServicio').val()!=11){
                // if(obj[0].idmotivo_no_pago==null){
                //     $('#cbLlamadaMotivoNoPago').val(0);
                // }else{
                //     $('#cbLlamadaMotivoNoPago').val(obj[0].idmotivo_no_pago);
                // }

                // if(obj[0].idparentesco==null){
                //     $('#cbLlamadaParentesco').val(0); 
                // }else{
                //     alert(obj[0].idparentesco);
                //     $('#cbLlamadaParentesco').val(obj[0].idparentesco); 
                // }
               
            }

            $('#cbSituacionLaboral').val( obj[0].idsituacion_laboral );
            $('#cbDisposicionRefinanciar').val( obj[0].iddisposicion_refinanciar );
            // $('#cbEstadoDelCliente').val( obj[0].idestado_cliente );
                   
            
            
            /***********/
            //$('#table_operaciones').jqGrid('addJSONData',{"page":0,"total":0,"records":0,"rows":[]});
            //AtencionClienteDAO.GetLineasFacturaDigitalXcliente();
        }
    },
    FillAllCampo : function ( obj ) {
				
        //if( obj.length==1 ) {
            $('#txtCampoCodigoCliente').text(obj[0].codigo);
            $('#txtCampoNombreCodigoCliente').text(obj[0].nombre);
            $('#txtCampoNumeroDocumentoCliente').text(obj[0].numero_documento);
            $('#txtCampoTipoDocumentoCliente').val(obj[0].tipo_documento);
					
            $('#IdClienteCarteraCampoMain').val(obj[0].idcliente_cartera);
            $('#IdClienteCampoMain').val(obj[0].idcliente);
            $('#IdCarteraCampoMain').val(obj[0].idcartera);
            $('#CodigoClienteCampoMain').val(obj[0].codigo);
					
            /*************/
            AtencionClienteDAO.ListarCuentaVisita(obj[0].idcartera, obj[0].idcliente_cartera);
            /*************/
					
            //$('#IdCarteraCampoMain').val(obj[0].idcartera);
					
            //				reloadJQGRID_campo_direccion(obj[0].idcliente,$('#cbCampoGlobalesCartera').val());
            //				reloadJQGRID_campo_telefono(obj[0].idcliente,$('#cbCampoGlobalesCartera').val());
            //				$('#table_campo_visita').jqGrid('setGridParam',{url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_campo_visita'}).trigger('reloadGrid');
					
            //reloadJQGRID_campo_direccion(obj[0].codigo,$('#cbCampoGlobalesCartera').val());
            //reloadJQGRID_campo_telefono(obj[0].codigo,$('#cbCampoGlobalesCartera').val());

			AtencionClienteDAO.Listar_Representantes(obj[0].codigo);
			
            /***********/
            AtencionClienteDAO.ListarDireccionVisita(obj[0].idcartera,obj[0].codigo,function( obj ){
                var html = '';
                html+='<option value="0">--Seleccione--</option>';
                for( i=0;i<obj.length;i++ ) {
                    html+='<option value="'+obj[i].iddireccion+'">'+obj[i].direccion+'</option>';
                }
                $('#cbCampoDireccionVisita').html(html);
            },function(){});
            /***********/
            // piro 13-08-2015 para q ya no filtre por idcliente cartera y me limite solo a la primera cartera de la consulta cuando no seleccionen cartera en el cobrast
            if(obj.length==1){
            reloadJQGRID_visita_2(obj[0].idcliente_cartera);
            } else{
                alert('No elegio cartera, por lo que mostrara todas las visitas');
                reloadJQGRID_visita_3(obj[0].codigo);
            }
            

        /***********/
			$('#tabCampoDireccion').unbind('click');
			if( $('#layerTabCampoDireccion').css('display')=='block' ) {
				reloadJQGRID_campo_direccion(obj[0].codigo, obj[0].idcartera );
			}else{
				$('#tabCampoDireccion').one('click', function ( ){ reloadJQGRID_campo_direccion(obj[0].codigo, obj[0].idcartera ); });
			}
			
			$('#tabCampoTelefono').unbind('click');
			if( $('#layerTabCampoTelefono').css('display') == 'block' ) {
				reloadJQGRID_campo_telefono(obj[0].codigo, obj[0].idcartera );
			}else{
				$('#tabCampoTelefono').one('click', function ( ){ reloadJQGRID_campo_telefono(obj[0].codigo,obj[0].idcartera); });
			}
        //$('#table_campo_visita').jqGrid('setGridParam',{url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_campo_visita'}).trigger('reloadGrid');
        //}
    },
    FillAllCampo2 : function ( obj ) { 
                
        //if( obj.length==1 ) {
            $('#txtTerritorio').val(obj[0].territorio);
            $('#txtOficina').val(obj[0].oficina);
            $('#txtCliente').val(obj[0].cliente);
            $('#txtRuc').val(obj[0].ruc);
                    
           
            $('#txtDeudaTotal').val(Math.round(obj[0].total_deuda * 100) /100 );
            $('#txtClasificacion').val(obj[0].clasificacion);
            $('#txtProvision').val(obj[0].provision);
            $('#txtPosicionSistemaFinanciero').val(obj[0].sistema_financiero);
            $('#txtTipoCredito').val(obj[0].tipo_credito);
            $('#txtTramo').val(obj[0].tramo);
            $('#txtPersonaCargo1').val(obj[0].contacto1);
            $('#txtPersonaCargo2').val(obj[0].contacto2);
            $('#txtNivelDeRiesgo').val(obj[0].nivel_riesgo);
                    
            /*************/
          
           
            
    },
    LoadDataCliente : function ( idClienteCartera ) {			
        $.ajax({
            url : this.url,
            type : 'GET',
            async:false,
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'LoadDataCliente',
                ClienteCartera:idClienteCartera,
                Servicio:$('#hdCodServicio').val()
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Cargando...',250);
                $('#lbMessageGlobalGest').text('');
                $('#lbMessageGlobalGest').css('display','none');
                $("#cargando").css("display","")
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if(obj.length==1){
                    AtencionClienteDAO.FillAllAtencionCliente(obj);
                    $('#tabAC1Resultado').trigger('click');
                }
				//$('#lbMessageGlobalGest').text('');
				//$('#lbMessageGlobalGest').css('display','none');
                $("#cargando").css("display","none")
            },
            error : function ( ) { 
                _noneBeforeSend();
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    LoadFiltrosTablaAtencionCliente : function ( ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListarFiltrosTablaAtencionCliente',
                Servicio:$('#hdCodServicio').val()
            },
            success : function ( obj ) {
                AtencionClienteDAO.FillAtencionBusquedaManualTabla(obj);
            },
            error : this.error_ajax
        });	
    },
    FillAtencionBusquedaManualTabla : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.tabla+'">'+data.tabla_mostrar+'</option>';
        });
        $('#cbTablaBusquedaManualAtencionCliente').html(html);
    },
    LoadFiltrosCampoAtencionCliente : function ( ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListarFiltrosCampoAtencionCliente',
                Servicio:$('#hdCodServicio').val(),
                Tabla:$('#cbTablaBusquedaManualAtencionCliente').val()
            },
            success : function ( obj ) {
                AtencionClienteDAO.FillAtencionBusquedaManualCampo(obj);
            },
            error : this.error_ajax
        });	
    },
    FillAtencionBusquedaManualCampo : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.tipo_campo+'">'+data.campo+'</option>';
        });
        $('#cbCampoBusquedaManualAtencionCliente').html(html);
    },
    CallAsterisk : function ( ) {

        var xnumero = $.trim( $('#txtAtencionClienteNumeroCall').val() );
        var xprefijo = $('#txtAtencionClienteNumeroCall').attr('prefixs');
        var xlinea = $('#txtAtencionClienteNumeroCall').attr('line');

        var prefijo_frt = "";

        if( xprefijo == '' ) {
            
            var xnum = (xnumero).substring(0,1);
            var xlon_num = (xnumero).length ;
            if( xnum == '9' ) {
                
                if( xlon_num == 9 ) {
//                    alert("Actualize linea ( claro, movistar o nextel ) de telefono");
                    prefijo_frt = $('#hdprefijo_default').val();
                }else{
                    alert("Formato de numero incorrecto , actualize numero");
                    return false;                    
                }


            }else{
                
                if( xlon_num == 7 || xlon_num == 8 || xlon_num == 9 || xlon_num == 6 ) {
                    prefijo_frt = $('#hdprefijo_fijo').val();
                }else{
                    alert("Formato de numero incorrecto , actualize numero");
                    return false;
                }

            }

        }else{
            
            var xdata_pref = (xprefijo).split("-");
            if( xdata_pref.length > 1 ) {
                
                var data_rnd = Math.floor( Math.random()*( xdata_pref.length ) )
                
                prefijo_frt = $('#hd'+xdata_pref[ data_rnd ] ).val();

            }else{
                prefijo_frt = $('#hd'+xprefijo).val();
            }

        }
			
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'asteriskAGI',
                action:'originate', 
                Prefijo : prefijo_frt,
                CallCenterIp : $('#hdCallCenterIp').val(),
                UserCallCenter : $('#hdUserCallCenter').val(),
                PasswordCallCenter : $('#hdPasswordCallCenter').val(),
                NombreServicio : $('#hdNomServicio').val(),
                Anexo : $('#hdAnexoOp').val(),
                idcliente_cartera:$('#IdClienteCarteraMain').val(),
                codigo_cliente:$('#CodigoClienteMain').val(),
                Phone : xnumero
            },
            success : function ( obj ) {
                //alert(obj);
                if( obj.rst ) {
                    //alert(obj.fecha_llamada);
                    $('#LlamadaFechaInicioTMO').val(obj.fecha_llamada);
                    if( obj.callerid != '' ) {
                        $('#LlamadaCallerIdAsterisk').val(obj.callerid);
                    }                    
                }else{
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'200px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                    $('#LlamadaFechaInicioTMO').val(obj.fecha_llamada);
                }
							
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        });

    },
    HungupAsterisk : function ( ) {
        var xnumero = $.trim( $('#txtAtencionClienteNumeroCall').val() );
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'asteriskAGI',
                action:'Hungup',
                Prefijo : $('#hdPrefijo').val(),
                CallCenterIp : $('#hdCallCenterIp').val(),
                UserCallCenter : $('#hdUserCallCenter').val(),
                PasswordCallCenter : $('#hdPasswordCallCenter').val(),
                NombreServicio : $('#hdNomServicio').val(),
                Anexo : $('#hdAnexoOp').val(),
                Phone:xnumero
            },
            success : function ( obj ) {
                //alert(obj);
                if( obj.rst ) {
                    //alert(obj.fecha_llamada);
                    $('#LlamadaFechaFinTMO').val(obj.fecha_llamada);
                }else{
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'200px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                    $('#LlamadaFechaFinTMO').val(obj.fecha_llamada);
                }
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    save_nota : function ( idClienteCartera ) {
        $.ajax({
            url : this.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'GuardarNota',
                Servicio:$('#hdCodServicio').val(),
                FechaNota:$('#txtFechaNota').val(),
                Nota:$.trim( $('#txtDescripcionNota').val() ),
                ClienteCartera:idClienteCartera,
                UsuarioCreacion:$('#hdCodUsuario').val()
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Guardando Nota...',250);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if(obj.rst){
                    $('#dialogNotas').dialog('close');
                    $('#dialogNotas').find(':text,textarea').val('');
                    $('#txtAbonadoNota').empty();
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'200px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                    AtencionClienteDAO.ListarNotas();
                }else{
                    $('#NotasLayerMessage').html(templates.MsgError(obj.msg,'200px'));
                    $('#NotasLayerMessage').effect('pulsate',{},'slow',function(){
                        $(this).empty();
                    });
                }
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        })
    },
    ListarNotas : function ( carteras ) {
        $.ajax({
            url : this.url,
            async : false,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListarNotas',
                Cartera:carteras,
                Servicio : $('#hdCodServicio').val(),
                UsuarioServicio:$('#hdCodUsuarioServicio').val()
            },
            success : function ( obj ) {
                //var html='';
                //								$.each(obj,function (key,data){
                //									html+='<li>'+data.descripcion+'</li>';
                //								});
                //								$('#dialogNotasHoy #ulNotas').html(html);
								
                var html='';
                $.each(obj,function(key,data){
                    if( data.reading==0 ) {
                        //#0066FF
                        html+='<tr style="color:#0066FF;" class="ui-widget-content" id="nota_'+data.idnota+'" >';
                    }else{
                        html+='<tr class="ui-widget-content" id="nota_'+data.idnota+'" >';
                    }
                    //html+='<tr class="ui-widget-content" id="nota_'+data.idnota+'" >';
                    html+='<td style="border-bottom:1px solid #E0CFC2;white-space:pre-line;padding:3px;width:3%;" align="center">::</td>';
                    html+='<td style="border-bottom:1px solid #E0CFC2;white-space:pre-line;padding:3px;width:5%;" align="center"><input value="'+data.idnota+'" type="checkbox" ></td>';
                    if( data.important==1 ){
                        html+='<td style="border-bottom:1px solid #E0CFC2;white-space:pre-line;padding:3px;width:5%;" title="importante" align="center"><img style="cursor:pointer;" onclick="desmarcar_nota_como_importante('+data.idnota+',this)" src="../img/star.png" /></td>';
                    }else{
                        html+='<td style="border-bottom:1px solid #E0CFC2;white-space:pre-line;padding:3px;width:5%;" title="importante" align="center"></td>';
                    }
                    //html+='<td style="border-bottom:1px solid #E0CFC2;white-space:pre-line;padding:3px;width:5%;" title="importante" align="center"></td>';
                    html+='<td onclick="ver_nota('+data.idnota+');marcar_nota_como_leida('+data.idnota+',this)" style="cursor:pointer;border-bottom:1px solid #E0CFC2;white-space:pre-line;padding:3px;width:15%;" align="center">'+data.codigo+'</td>';
                    html+='<td style="border-bottom:1px solid #E0CFC2;white-space:pre-line;padding:3px;width:25%;" align="center">'+data.cliente+'</td>';
                    html+='<td style="border-bottom:1px solid #E0CFC2;white-space:pre-line;padding:3px;width:48%;" align="center">'+data.descripcion+'</td>';
                    html+='<td style="border-bottom:1px solid #E0CFC2;white-space:pre-line;width:5%;" align="center">&nbsp;</td>';
                    html+='</tr>';
                });
                $('#dialogNotasHoy #tableNotas').html(html);
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    ListarEstadoTransaccion : function ( function_fill_estado_llamada, function_fill_estado_visita ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListarEstadoTransaccion',
                Servicio:$('#hdCodServicio').val()
            },
            success : function ( obj ) {
                if(function_fill_estado_llamada && function_fill_estado_visita){
                    function_fill_estado_llamada(obj.llamada);
                    function_fill_estado_visita(obj.visita);
                }else{
                    AtencionClienteDAO.FillLlamadaEstadoLlamada(obj.llamada);
                    AtencionClienteDAO.FillLlamadaEstadoVisita(obj.visita);
                }
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    FillLlamadaEstadoLlamada : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idestado_transaccion+'">'+data.nombre+'</option>';
        });
        $('#cbLlamadaEstadoLlamada').html(html);
    },
    FillLlamadaEstadoVisita : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idestado_transaccion+'">'+data.nombre+'</option>';
        });
        $('#cbCampoEstadoVisita').html(html);
    },
    FillLlamadaEstadoCuotificacion : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idfinal+'">'+data.nombre+'</option>';
        });
        $('#cbEstadoCuotificacion').html(html);
    },
    ListarPesoTransaccion : function ( id , function_fill_peso_transaccion ) {
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListarPesoTransaccion',
                Servicio:$('#hdCodServicio').val(),
                EstadoTransaccion:id
            },
            success : function ( obj ) {
                function_fill_peso_transaccion(obj);
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    FillLlamadaPesoLlamada : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idpeso_transaccion+'">'+data.peso+'</option>';
        });
        $('#cbLlamadaPeso').html(html);
        var rf=$('#cbLlamadaPeso').attr('title');
        $('#cbLlamadaPeso').val(rf);
    },
    FillLlamadaPesoVisita : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idpeso_transaccion+'">'+data.peso+'</option>';
        });
        $('#cbCampoPrioridadVisita').html(html);
        var rf=$('#cbCampoPrioridadVisita').attr('title');
        $('#cbCampoPrioridadVisita').val(rf);
    },
    save_llamada : function ( xCuentas ) {
    
        var xdt = $('textarea');
        var djsd=$('#tabAC2Direcciones > div');//pa jalar data direccion por js
        
        $.ajax({
            url : this.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'GuardarLlamada',
                Servicio:$('#hdCodServicio').val(),
                ClienteCartera:$('#IdClienteCarteraMain').val(),
                Observacion:($.trim( $('#txtObservacionLlamada').val() )).replace(/\t/g,''),
                UsuarioCreacion:$('#hdCodUsuario').val(),
                UsuarioServicio : $('#hdCodUsuarioServicio').val(),
                //Final:$('#cbLlamadaEstadoDetaleResptIncidencia').val(),
                CargaFinal : $('#cbLlamadaEstado option:selected').parent().attr('id'),
                Final:$('#cbLlamadaEstado').val(),
                FechaLlamada:$('#txtAtencionLlamadaFecha').val(),
                TMO_inicio : $('#LlamadaFechaInicioTMO').val(),
                TMO_fin : $('#LlamadaFechaFinTMO').val(),
                Contacto : $('#cbLlamadaContacto').val(),
                NombreContacto : $.trim( $('#txtLlamadaNombreContacto').val() ),
                motivo_no_pago : $('#cbLlamadaMotivoNoPago').val(),
                parentesco : $('#cbLlamadaParentesco').val(),
                //PesoLlamada:$('#cbLlamadaPeso').val(),
                Telefono:$('#HdIdTelefono').val(),
                NumeroTelefono : $('#txtAtencionClienteNumeroCall').val(),
                PesoEstado : $('#cbLlamadaEstado option:selected').attr('weight'),
                CodigoCliente : $('#CodigoClienteMain').val(),
                EnviarCampo : ( $('#chkcampo').attr('checked') )?1:0 ,
                Cuentas : xCuentas,
                //Estado : $('#cbLlamadaEstadoDetaleResptIncidencia').val(),
                Estado : $('#cbLlamadaEstado').val(),
                FechaCP : $('#txtAtencionLlamadaFechaCp').val(),
                MontoCP : $('#txtAtencionLlamadaMontoCp').val(),
                recibio_eecc:$('#slcteecc').val(),/*jmore31012014*/
                CallerId : $('#LlamadaCallerIdAsterisk').val(),
                //DireccionEst : $.data( document.body ) ,
                idsituacion_laboral : $('#cbSituacionLaboral').val(),
                iddisposicion_refinanciar : $('#cbDisposicionRefinanciar').val(),
                idestado_cliente : $('#cbEstadoDelCliente').val(),
                /*pato*/
                DireccionEst : $.data( djsd[0] ) ,                
                TelefonosEst : $.data( xdt[0] ),
                sustento_pago:$('#cbLlamadaSustentoPago').val(),
                alerta_gestion:$('#cbLlamadaAlertaGestion').val(),
                /*jc neotel*/
                call_id:$('#txtIdLlamadaN').val()//idllamada_neotel
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Guardando Llamada...',250);
            },
            success : function ( obj ) {
                _noneBeforeSend();

                _displayBeforeSendDl(obj.msg,400);

                if(obj.rst){
                    $('#chkcampo').attr('checked',false);
                    $('#txtAtencionClienteNumeroCall').val('');
                    $('#HdIdTelefono').val('');
                    $('#LlamadaFechaInicioTMO').val('');
                    $('#LlamadaFechaFinTMO').val('');
                    $('#LlamadaCallerIdAsterisk').val('');

                    var carga_estado = $('#cbLlamadaEstado option:selected').parent().attr('label');
                    var modo_marcacion = $(':radio[name="modo_marcacion_telefono"]:checked').val();
                    
                    $.removeData( xdt[0] );
                    $.removeData( djsd[0] );
                    cancel_llamada();
                    $('#hdAlerta').val('0');                    
                    
                    if( modo_marcacion == 'modo_marcacion_automatica' ) {

                        //$('#btnAtencionClientePhoneCall').trigger('click');

                        /*var rs = confirm("Desea pasar al siguiente item");
                        if( rs ) {
                            $('#btnNextClienteAtencionCliente').trigger('click');
                        }else{*/

                            var rs_ll = confirm("Desea llamar al siguiente numero");
                            if( rs_ll ) {
                                $('#table_llamada').jqGrid().trigger('reloadGrid');
                                $('#btnAtencionClientePhoneCall').trigger('click');
                            }else{
                                var rs = confirm("Desea pasar al siguiente item");
                                if( rs ) {
                                    $('#btnNextClienteAtencionCliente').trigger('click');
                                }else{
                                    $('#table_llamada').jqGrid().trigger('reloadGrid');
                                } 
                            }

                            
                            
                        //}

                    }else if( modo_marcacion == 'modo_marcacion_barrido' || modo_marcacion == 'modo_marcacion_barrido_peso' ){
                        
                        if( carga_estado == 'NOC' ) {
                            
                            var rs_ll = confirm("Desea llamar al siguiente numero");
                            if( rs_ll ) {
                                $('#btnAtencionClientePhoneCall').trigger('click');
                            }

                            $('#table_llamada').jqGrid().trigger('reloadGrid');

                        }else{
                            
                            var rs = confirm("Desea pasar al siguiente item");
                            if( rs ) {
                                $('#btnNextClienteAtencionCliente').trigger('click');
                            }else{
                                $('#table_llamada').jqGrid().trigger('reloadGrid');
                            }            

                        }

                    }else{
                        //lo comento pq... si no es ninguno de los anteriores es pq es modo_manual, entonces q avance manualmente JC :P
						if( $('#flg_modo_neotel').val() == 1)
						{
	                        $('#table_llamada').jqGrid().trigger('reloadGrid');//JC							
						}
						else 
						{
			                var rs = confirm("Desea pasar al siguiente item");
		                    if( rs ) {
		                        $('#btnNextClienteAtencionCliente').trigger('click');
		                    }else{
		                        $('#table_llamada').jqGrid().trigger('reloadGrid');
		                    }
						}
                            
                    }

                    
                }else{
                    
                }
                
                
                
            },
            error : function ( ) {
                _noneBeforeSend();
                AtencionClienteDAO.error_ajax();
            }
        });
    },  
    update_llamada : function ( ) {
        $.ajax({
            url : this.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'UpdateLlamada',
                Servicio:$('#hdCodServicio').val(),
                IdTransaccion:$('#HdIdTransaccionAtencionCliente').val(),
                IdLlamada:$('#HdIdLlamadaAtencionCliente').val(),
                IdCpg:$('#HdIdCpgAtencionCliente').val(),
                Observacion:$.trim( $('#txtObservacionLlamada').val() ),
                UsuarioModificacion:$('#hdCodUsuario').val(),
                Final:$('#cbFinalLlamada').val(),
                FechaLlamada:$('#txtAtencionLlamadaFecha').val(),
                PesoLlamada:$('#cbLlamadaPeso').val(),
                FechaCP:$('#txtAtencionLlamadaFechaCp').val(),
                MontoCP:$('#txtAtencionLlamadaMontoCp').val()
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Actualizando Llamada...',250);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if(obj.rst){
                    $('#txtAtencionClienteNumeroCall').val('');
                    $('#HdIdTelefono').val('');
                    cancel_llamada();
                    $('#table_llamada').jqGrid().trigger('reloadGrid');
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                }else{
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'250px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                _noneBeforeSend();
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    save_consulta : function ( ) { 
        $.ajax({
            url : this.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'GuardarConsulta',
                Servicio:$('#hdCodServicio').val(),
                ClienteCartera:$('#IdClienteCarteraMain').val(),
                Consulta : $.trim( $('#txtDescripcionConsulta').val() ),
                Asunto : $trim( $('#txtAsuntoConsulta').val() ),
                Supervisor : $('#cbParaSupervisorConsulta').val(),
                UsuarioCreacion:$('#hdCodUsuario').val()
            },
            beforeSend : function ( ) {
								
            },
            success : function ( obj ) {
								
            },
            error : function ( ) {}
        });
    },
    save_detalle_consulta : function ( ) {
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'GuardarReenvio',
                IdConsulta : $('#').val(),
                Consulta : $('#').val(),
                UsuarioCreacion : $('#').val()
            },
            beforeSend : function ( ) {
								
            },
            success : function ( obj ) {
								
            },
            error : function ( ) {
								
            }
        });
    },
    Calendar : {
        IdLayerCalendar : 'GestionPanelCalendar',
        HeaderLayerCalendar : 'HeaderGestionPanelCalendar',
        Anio : 0,
        Mes : 0,
        initCalendar : function ( init ) {
            if( init==1 ) {
                var hoy=new Date();
                AtencionClienteDAO.Calendar.Mes=hoy.getMonth();
                AtencionClienteDAO.Calendar.Anio=hoy.getFullYear();
                AtencionClienteDAO.Calendar.BuildingCalendar(hoy.getFullYear(),hoy.getMonth());
            }else if( init==2 ){
							
                AtencionClienteDAO.Calendar.Mes=AtencionClienteDAO.Calendar.Mes+1;
                if( AtencionClienteDAO.Calendar.Mes==12 ){
                    AtencionClienteDAO.Calendar.Anio=AtencionClienteDAO.Calendar.Anio+1;
                    AtencionClienteDAO.Calendar.Mes=0;
                }
							
                AtencionClienteDAO.Calendar.BuildingCalendar(AtencionClienteDAO.Calendar.Anio,AtencionClienteDAO.Calendar.Mes);
            }else if( init==3 ) {
                AtencionClienteDAO.Calendar.Mes=AtencionClienteDAO.Calendar.Mes-1;
                if( AtencionClienteDAO.Calendar.Mes==-1 ){
                    AtencionClienteDAO.Calendar.Anio=AtencionClienteDAO.Calendar.Anio-1;
                    AtencionClienteDAO.Calendar.Mes=11;
                }
                AtencionClienteDAO.Calendar.BuildingCalendar(AtencionClienteDAO.Calendar.Anio,AtencionClienteDAO.Calendar.Mes);
							
            }
        },
        BuildingCalendar : function ( anio,mes ) {
            var array=['Dom','Lun','Mar','Mie','Jue','Vie','Sab'];
            var meses=['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre'];
            var date=new Date();
            var html='';
            date.setFullYear(anio);
            date.setMonth(mes);
            date.setDate(1);
            var CantidadDias=AtencionClienteDAO.Calendar.CantidadDias(date.getMonth()+1,date.getFullYear());
					
            var initDate=date.getDay();
            html+='<table border="0" cellpadding="0" cellspacing="0" >';
            html+='<tr>';
            for( i=0;i<array.length;i++ ){
                html+='<th class="ui-widget-header" style="padding:3px;">'+array[i]+'</th>';
            }
            html+='</tr>';
            var contadorDias=0;
            var contadorBreak=0;
            for ( i=0;i<6;i++ ) {
                if( i==0 ){
                    html+='<tr>';
                    for( j=0;j<7;j++ ) {
                        if( j>=initDate ) {
                            contadorDias++;
                            html+='<td style="width:120px;height:80px;border:1px solid #E0CFC2;" valign="top" ><div align="right" style="padding:2px 4px;background-color:#F0E8D9;"><strong>'+contadorDias+'</strong></div><div></div></td>';	
                        }else{
                            html+='<td style="width:120px;height:80px;" valign="top"></td>';	
                        }
                    }
                    html+='</tr>';
                }else{
                    html+='<tr>';
                    if( contadorBreak>0 ) {
                        break;
                    }
								
                    for( j=0;j<7;j++ ) {
                        contadorDias++;
                        if( contadorDias==CantidadDias ){
                            html+='<td style="width:120px;height:80px;border:1px solid #E0CFC2;" valign="top" ><div align="right" style="padding:2px 4px;background-color:#F0E8D9;"><strong>'+contadorDias+'</strong></div><div></div></td>';
                            contadorBreak++;
                            break;
                        }else{
                            html+='<td style="width:120px;height:80px;border:1px solid #E0CFC2;" valign="top" ><div align="right" style="padding:2px 4px;background-color:#F0E8D9;"><strong>'+contadorDias+'</strong></div><div></div></td>';
                        }
                    }
                    html+='</tr>';
                }
            }
            html+='</table>';
						
            $('#'+AtencionClienteDAO.Calendar.IdLayerCalendar).html(html);
            $('#'+AtencionClienteDAO.Calendar.HeaderLayerCalendar).text((meses[date.getMonth()]+' '+anio));
        },
        CantidadDias : function ( humanMonth, year ) {
            return new Date(year || new Date().getFullYear(), humanMonth, 0).getDate();
        }
    },
    
    ListCarteraOperador : function ( idCampania,idUsuario_servicio, f_fill, cboCart, cluster, evento, segmento, modo ) {
        $('#tbCarterasMultiples').jqGrid('setGridParam',
        {
			datatype : 'json',
            url : '../controller/ControllerCobrast.php?command=carga-cartera&action=ListCarteraOperador&Campania='+idCampania+'&idusuario_servicio='+idUsuario_servicio+'&Cluster='+cluster+'&Evento='+evento+'&Segmento='+segmento+'&Modo='+modo
        }
        ).trigger('reloadGrid');
        //$('#tbCarterasMultiples').trigger('reloadGrid');
    /*$.ajax({
					url : AtencionClienteDAO.url,
					type : 'GET',
					dataType : 'json',
					data : {command:'carga-cartera',action:'ListCarteraOperador',Campania:idCampania,idusuario_servicio:idUsuario_servicio},
					success : function ( obj ) {
							f_fill(obj,cboCart);
						},
					error : function ( ) { 
							AtencionClienteDAO.error_ajax();
						}
				});*/
    },
    FillAtencionClienteCartera : function ( obj,cboCart ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            if(data.vencido==1){
                html+='<option value="'+data.idcartera+'" title="Inicio Gestion:'+data.fecha_inicio+', Fin Gestion: '+data.fecha_fin+'" style="color:#F00;">'+data.nombre_cartera+'</option>';
            
            }else{
                html+='<option value="'+data.idcartera+'" title="Inicio Gestion:'+data.fecha_inicio+', Fin Gestion: '+data.fecha_fin+'" >'+data.nombre_cartera+'</option>';
            }
        });
        $('#'+cboCart).html(html);
    },
			
    ListarUsuariosAyudar : function ( ) {
        $.ajax({
            url : this.url,
            async : false,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'ayuda_gestion_usuario',
                action:'ListarUsuariosAyudar',
                Cartera:$('#cbCarteraApoyo').val(),
                Servicio:$('#hdCodServicio').val(),
                UsuarioServicio:$('#hdCodUsuarioServicio').val()
            },
            beforeSend : function ( ) {
                //$('#LayerTableUsuariosAyudar').html(templates.IMGloadingContentLayer());
            },
            success : function ( obj ) {
                AtencionClienteDAO.FillTableUsuariosAyudar(obj);
            },
            error : function ( ) {}
        });
    },
		
    ListarUsuariosAsignar : function ( ) {
        $.ajax({
            url : this.url,
            async : false,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'ayuda_gestion_usuario',
                action:'ListarUsuariosAsignarConDist',
                Cartera:$('#cbCarteraApoyo').val(),
                UsuarioServicio:$('#hdCodUsuarioServicio').val(),
                Servicio:$('#hdCodServicio').val()
            },
            beforeSend : function ( ) {
                //$('#LayerTableUsuariosAsignar').html(templates.IMGloadingContentLayer());
            },
            success : function ( obj ) {
                AtencionClienteDAO.FillTableUsuariosAsignar(obj)
            },
            error : function ( ) {}
        });
    },
			
    DeleteUsuarioAyudar : function ( ids ) {
        $.ajax({
            url : this.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'ayuda_gestion_usuario',
                action:'DeleteUsuarioAyudar',
                Cartera:$('#cbCarteraApoyo').val(),
                UsuarioServicio:$('#hdCodUsuarioServicio').val(),
                IdsUsuarioServicio:ids,
                UsuarioModificacion : $('#hdCodUsuario').val()
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Eliminando usuarios...',250);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if( obj.rst ){
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
                    $('#'+AtencionClienteDAO.idLayerMessage).effect('pulsate',{},'slow',function(){
                        $(this).empty();
                    });
                    AtencionClienteDAO.ListarUsuariosAsignar();
                    AtencionClienteDAO.ListarUsuariosAyudar();
                }else{
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'350px'));
                    $('#'+AtencionClienteDAO.idLayerMessage).effect('pulsate',{},'slow',function(){
                        $(this).empty();
                    });
                }
            },
            error : function ( ) {
                _noneBeforeSend();
            }
        });
    },	
			
    SaveUsuarioAyudar : function ( ids ) {
        $.ajax({
            url : this.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'ayuda_gestion_usuario',
                action:'SaveUsuarioAyudar',
                Cartera:$('#cbCarteraApoyo').val(),
                UsuarioServicio:$('#hdCodUsuarioServicio').val(),
                IdsUsuarioServicio:ids,
                UsuarioCreacion:$('#hdCodUsuario').val()
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Guardando usuarios...',250);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if( obj.rst ){
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
                    $('#'+AtencionClienteDAO.idLayerMessage).effect('pulsate',{},'slow',function(){
                        $(this).empty();
                    });
                    AtencionClienteDAO.ListarUsuariosAsignar();
                    AtencionClienteDAO.ListarUsuariosAyudar();
                }else{
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'350px'));
                    $('#'+AtencionClienteDAO.idLayerMessage).effect('pulsate',{},'slow',function(){
                        $(this).empty();
                    });
                }
            },
            error : function ( ) {
                _noneBeforeSend();
            }
        });
    },	
			
    FillTableUsuariosAsignar : function ( obj ) {
        var html='';
        
        for( i=0;i<obj.length;i++ ) {
            html+='<tr style="display:block;float:left;border:0px;" id="'+obj[i].idusuario_servicio+'" class="ui-widget-content" >';
                html+='<td align="center" style="width:25px;padding:3px 0;" class="ui-state-default">'+(i+1)+'</td>';
                html+='<td align="center" style="width:250px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+(obj[i].operador).toUpperCase()+'</td>';
                html+='<td align="center" style="width:200px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+obj[i].clientes_asignados+'</td>';
                html+='<td align="center" style="width:150px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+obj[i].clientes_gestionados+'</td>';
                html+='<td align="center" style="width:150px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+obj[i].clientes_sin_gestionar+'</td>';
                html+='<td align="center" style="width:25px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;"><input value="'+obj[i].idusuario_servicio+'" type="checkbox" /></td>';
            html+='</tr>';
        }
        
        $('#DataLayerTableUsuariosAsignar').html(html);
    },
			
    FillTableUsuariosAyudar : function ( obj ) {
        var html='';
        
        var combo='';
        combo+='<option value="0">--Seleccione--</option>';
        
        for( i=0;i<obj.length;i++ ) {
        
            if( $('#hdCodUsuarioServicio').val()!=obj[i].idusuario_servicio ) {
                combo+='<option value="'+obj[i].idusuario_servicio+'">'+obj[i].operador+'</option>';
            }
            
            html+='<tr style="display:block;float:left;border:0px;" id="'+obj[i].idusuario_servicio+'" class="ui-widget-content" >';
                html+='<td align="center" style="width:25px;padding:3px 0;" class="ui-state-default">'+(i+1)+'</td>';
                html+='<td align="center" style="width:250px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+(obj[i].operador).toUpperCase()+'</td>';
                html+='<td align="center" style="width:200px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+obj[i].clientes_asignados+'</td>';
                html+='<td align="center" style="width:150px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+obj[i].clientes_gestionados+'</td>';
                html+='<td align="center" style="width:150px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+obj[i].clientes_sin_gestionar+'</td>';
                html+='<td align="center" style="width:25px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;"><input value="'+obj[i].idusuario_servicio+'" type="checkbox" /></td>';
            html+='</tr>';
        }
        
        $('#DataLayerTableUsuariosAyudar').html(html);
        $("#cbOperadoresMatrizBusqueda").html(combo);
        
    },	
		
    FillCampoCartera : function ( obj ) {
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        $.each(obj,function(key,data){
            html+='<option value="'+data.idcartera+'">'+data.nombre_cartera+'</option>';
        });
        $('#cbCampoGlobalesCartera').html(html);
    },
    update_numero_telefono : function ( ) {
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'UpdateNumeroTelefono',
                Id:$('#hdIdTelefonoCartera').val(),
                Numero:$('#txtNumeroTelefonoAtencionCliente').val()
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Actualizando Numero',320);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if( obj.rst ) {
									
                }else{
									
            }
            },
            error : function ( ) {
                _noneBeforeSend();
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    save_telefono_atencion_cliente : function ( ) {
        $.ajax({
            url : this.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command : 'atencion_cliente',
                action : 'save_telefono',
                Cliente : $('#idClienteMain').val(),
                CodigoCliente : $('#CodigoClienteMain').val(),
                IdClienteCartera : $('#IdClienteCarteraMain').val(),
                Cartera : $('#IdCartera').val(),
                IdCuenta : ('['+$('#table_cuenta_aplicar_gestion').find(':checked').map(function( ){ return '{"cuenta":"'+$(this).val()+'"}'; }).get().join(",")+']'),
                Numero : parseInt($.trim( $('#txtNumero2TelefonoAtencionCliente').val() )),
                Anexo : $.trim( $('#txtAnexoTelefonoAtencion').val() ),
                TipoReferencia : $('#cbReferenciaTelefonoAtencion').val(),
                TipoTelefono : $('#cbTipoTelefonoAtencion').val(),
                Origen : $('#cbOrigenTelefonoAtencion').val(),
                LineaTelefono : $('#cbLineaTelefonoAtencion').val(),
                UsuarioCreacion : $('#hdCodUsuario').val(),
                IsCampo : 0,
                Observacion : $.trim( $('#txtObservacionTelefonoAtencion').val() )
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Guardando Telefono...',250);
            },
            success : function ( obj ) {
                $('#beforeSendShadow,#MsgBeforeSend').css('display','none');
                if(obj.rst){
                
                    var xdt = $('textarea');

                    $.data( xdt[0], obj.id, { cuenta : obj.idcuenta, est : 'NUEVO', numero : $.trim( $('#txtNumero2TelefonoAtencionCliente').val() ), idcliente_cartera : $('#IdClienteCarteraMain').val(), codigo_cliente : $('#CodigoClienteMain').val() } );
                
                    $('#table_telefonos').jqGrid().trigger('reloadGrid');
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
                    $('#DialogAddTelefonoCartera').find('select').val(0);
                    $('#DialogAddTelefonoCartera').find(':text').val('');
                    AtencionClienteDAO.setTimeOut_hide_message();
                }else{
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'250px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                }
            },
            error : this.error_ajax
        });
    },
    update_telefono_atencion_cliente : function ( ) {
        $.ajax({
            url : this.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command : 'atencion_cliente',
                action : 'update_telefono',
                Id : $('#hdIdAddTelefonoCartera').val(),
                Cartera : $('#cbAtencionGlobalesCartera').val(),
                Numero : parseInt($.trim( $('#txtNumero2TelefonoAtencionCliente').val() )),
                Anexo : $.trim( $('#txtAnexoTelefonoAtencion').val() ),
                TipoReferencia : $('#cbReferenciaTelefonoAtencion').val(),
                TipoTelefono : $('#cbTipoTelefonoAtencion').val(),
                Origen : $('#cbOrigenTelefonoAtencion').val(),
                LineaTelefono : $('#cbLineaTelefonoAtencion').val(),
                UsuarioModificacion : $('#hdCodUsuario').val(),
                Observacion : $.trim( $('#txtObservacionTelefonoAtencion').val() )
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Actualizando Telefono...',300);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if(obj.rst){
                    $('#table_telefonos').jqGrid().trigger('reloadGrid');
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'250px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                    $('#DialogAddTelefonoCartera').find('select').val(0);
                    $('#DialogAddTelefonoCartera').find(':text').val('');
                }else{
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'250px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();	
            }
        });
    },
    save_direccion_atencion_cliente : function ( ) {
        $.ajax({
            url : this.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'save_direccion',
                Cliente:$('#idClienteMain').val(),
                CodigoCliente:$('#CodigoClienteMain').val(),
                IdClienteCartera : $('#IdClienteCarteraMain').val(),
                Cartera:$('#IdCartera').val(),
                IdCuenta : ('['+$('#table_cuenta_aplicar_gestion').find(':checked').map(function( ){ return '{"cuenta":"'+$(this).val()+'"}'; }).get().join(",")+']'),
                Direccion : $.trim( $('#txtDireccionAtencionCliente').val() ),
                Referencia : $.trim( $('#txtDireccionReferenciaAtencionCliente').val() ),
                Ubigeo : $.trim( $('#txtUbigeoAtencionCliente').val() ),
                Departamento : $.trim( $('#txtDepartamentoAtencionCliente').val() ),
                Provincia : $.trim( $('#txtProvinciaAtencionCliente').val() ),
                Distrito : $.trim( $('#txtDistritoAtencionCliente').val() ),
                Origen : $('#cbOrigenDireccionAtencionCliente').val(),
                TipoReferencia : $('#cbReferenciaDireccionAtencionCliente').val(),
                UsuarioCreacion : $('#hdCodUsuario').val(),
                IsCampo : 0,
                Observacion : $.trim( $('#txtObservacionDireccionAtencionCliente').val() )
            },
            beforeSend : function ( obj ) {
                _displayBeforeSend('Guardando Direccion...',300);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if(obj.rst){
                    $('#table_direccion').jqGrid().trigger('reloadGrid');
                    /*pato*/
                    var djsd=$('#tabAC2Direcciones > div');//data_js_direccion
                    
                    $.data( djsd[0] , obj.id, { cuenta : obj.cuenta, est : 'NUEVO' } );
                    
                }else{
                    
                }
                
                _displayBeforeSendDl(obj.msg,300);
                
            },
            error : function ( ) {
                _noneBeforeSend();
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    update_direccion_atencion_cliente : function ( ) {
        $.ajax({
            url : this.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'update_direccion',
                Id:$('#HdIdDireccionAtencionCliente').val(),
                Direccion:$.trim( $('#txtDireccionAtencionCliente').val() ),
                Referencia:$.trim( $('#txtDireccionReferenciaAtencionCliente').val() ),
                Ubigeo:$.trim( $('#txtUbigeoAtencionCliente').val() ),
                Departamento:$.trim( $('#txtDepartamentoAtencionCliente').val() ),
                Provincia:$.trim( $('#txtProvinciaAtencionCliente').val() ),
                Distrito:$.trim( $('#txtDistritoAtencionCliente').val() ),
                Origen:$('#cbOrigenDireccionAtencionCliente').val(),
                TipoReferencia:$('#cbReferenciaDireccionAtencionCliente').val(),
                UsuarioModificacion:$('#hdCodUsuario').val(),
                Observacion: $.trim( $('#txtObservacionDireccionAtencionCliente').val() )
            },
            beforeSend : function ( obj ) {
                _displayBeforeSend('Actualizando Direccion...',300);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if(obj.rst){
                    $('#table_direccion').jqGrid().trigger('reloadGrid');
                    
                    //$.data( $('#txtObservacionLlamada') , $('#HdIdDireccionAtencionCliente').val() , { cuenta : xidcuenta, est : 'ACT' } );
                    
                }else{
                    
                }
                
                _displayBeforeSendDl(obj.msg,300);
                
            },
            error : function ( ) {
                _noneBeforeSend();
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    DataTelefonoPorId : function ( idTelefono, f_edit ) {
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'DataTelefonoPorId',
                Id:idTelefono
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Trayendo datos de telefono...',300);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                f_edit(obj);
            },
            error : function ( ) {
                _noneBeforeSend();
            }
        });
    },
    FillBoxFormTelefonoAtencionCliente : function ( obj ) {
        $('#DialogAddTelefonoCartera #txtNumero2TelefonoAtencionCliente').val(obj[0].numero);
        $('#DialogAddTelefonoCartera #txtAnexoTelefonoAtencion').val(obj[0].anexo);
        $('#DialogAddTelefonoCartera #cbTipoTelefonoAtencion').val(obj[0].idtipo_telefono);
        $('#DialogAddTelefonoCartera #cbReferenciaTelefonoAtencion').val(obj[0].idtipo_referencia);
        $('#DialogAddTelefonoCartera #cbLineaTelefonoAtencion').val(obj[0].idlinea_telefono);
        $('#DialogAddTelefonoCartera #cbOrigenTelefonoAtencion').val(obj[0].idorigen);
        $('#DialogAddTelefonoCartera #txtObservacionTelefonoAtencion').val(obj[0].observacion);
        $('#DialogAddTelefonoCartera #hdIdAddTelefonoCartera').val(obj[0].idtelefono);
    },
    FillBoxFormTelefonoCampo : function ( obj ){
        $('#layerFormCampoTelefono #txtCampoTelefonoNumero').val(obj[0].numero);
        $('#layerFormCampoTelefono #txtCampoTelefonoAnexo').val(obj[0].anexo);
        $('#layerFormCampoTelefono #cbCampoTelefonoTipo').val(obj[0].idtipo_telefono);
        $('#layerFormCampoTelefono #cbCampoTelefonoReferencia').val(obj[0].idtipo_referencia);
        $('#layerFormCampoTelefono #cbCampoTelefonoLinea').val(obj[0].idlinea_telefono);
        $('#layerFormCampoTelefono #txtCampoTelefonoObservacion').val(obj[0].observacion);
        $('#layerFormCampoTelefono #cbCampoTelefonoOrigen').val(obj[0].idorigen);
        $('#layerFormCampoTelefono #HdIdTelefonoCampo').val(obj[0].idtelefono);
    },
    DataDireccionPorId : function ( idDireccion, f_edit ) {
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'DataDireccionPorId',
                Id:idDireccion
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Trayendo datos de direccion...',300);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                f_edit(obj);
            },
            error : function ( ) {
                _noneBeforeSend();
            }
        });
    },
    FillBoxFormDireccionCampo : function ( obj ) {
        $('#layerFormCampoDireccion #txtCampoDireccionDireccion').val(obj[0].direccion);
        $('#layerFormCampoDireccion #txtCampoDireccionDireccionReferencia').val(obj[0].referencia);
        $('#layerFormCampoDireccion #cbCampoDireccionReferencia').val(obj[0].idtipo_referencia);
        $('#layerFormCampoDireccion #txtCampoDireccionUbigeo').val(obj[0].ubigeo);
        $('#layerFormCampoDireccion #txtCampoDireccionDepartamento').val(obj[0].departamento);
        $('#layerFormCampoDireccion #txtCampoDireccionDepartamento').blur();
        $('#layerFormCampoDireccion #txtCampoDireccionProvincia').val(obj[0].provincia);
        $('#layerFormCampoDireccion #txtCampoDireccionProvincia').blur();
        $('#layerFormCampoDireccion #txtCampoDireccionDistrito').val(obj[0].distrito);
        $('#layerFormCampoDireccion #txtCampoDireccionObservacion').val(obj[0].observacion);
        $('#layerFormCampoDireccion #cbCampoDireccionOrigen').val(obj[0].idorigen);
        $('#layerFormCampoDireccion #HdIdDireccionCampo').val(obj[0].iddireccion);
    },
    FillBoxFormDireccionAtencionCliente : function ( obj ) {
        $('#DialogAddDireccionCartera #txtDireccionAtencionCliente').val(obj[0].direccion);
        $('#DialogAddDireccionCartera #txtDireccionReferenciaAtencionCliente').val(obj[0].referencia);
        $('#DialogAddDireccionCartera #cbReferenciaDireccionAtencionCliente').val(obj[0].idtipo_referencia);
        $('#DialogAddDireccionCartera #cbOrigenDireccionAtencionCliente').val(obj[0].idorigen);
        $('#DialogAddDireccionCartera #txtUbigeoAtencionCliente').val(obj[0].ubigeo);
        $('#DialogAddDireccionCartera #txtDepartamentoAtencionCliente').val(obj[0].departamento);
        $('#DialogAddDireccionCartera #txtDepartamentoAtencionCliente').blur();
        $('#DialogAddDireccionCartera #txtProvinciaAtencionCliente').val(obj[0].provincia);
        $('#DialogAddDireccionCartera #txtProvinciaAtencionCliente').blur();
        $('#DialogAddDireccionCartera #txtDistritoAtencionCliente').val(obj[0].distrito);
        $('#DialogAddDireccionCartera #txtObservacionDireccionAtencionCliente').val(obj[0].observacion);
        $('#DialogAddDireccionCartera #HdIdDireccionAtencionCliente').val(obj[0].iddireccion);
    },
    DataVisitaPorId : function ( idVisita, f_edit ) {
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'DataVisitaPorId',
                Id:idVisita
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Traendo datos de visita...',300);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                f_edit(obj);
            },
            error : function ( ) {
                _noneBeforeSend();
            }
        });
    },
    FillBoxFormVisitaCampo : function ( obj ) {
        if( obj.length==1 ){
            //$('#layerFormCampoVisita #cbCampoCargaFinal').val(obj[0].carga_final);
            //$('#layerFormCampoVisita #cbCampoTipoFinal').val(obj[0].tipo_final);
            //$('#layerFormCampoVisita #cbCampoNivel').val(obj[0].nivel);
            //$('#layerFormCampoVisita #cbCampoNivel').trigger('change');
            $('#layerFormCampoVisita #txtCampoFechaVisita').val(obj[0].fecha);
            //$('#layerFormCampoVisita #cbCampoEstadoVisita').val(obj[0].idestado_transaccion);
            //$('#layerFormCampoVisita #cbCampoEstadoVisita').trigger('change');
            //$('#layerFormCampoVisita #cbCampoPrioridadVisita').attr("title",obj[0].idpeso_transaccion);
            //$('#layerFormCampoVisita #cbCampoFinal').attr("title",obj[0].idfinal);
            $('#layerFormCampoVisita #txtCampoFechaCP').val(obj[0].fecha_cp);
            $('#layerFormCampoVisita #txtCampoMontoCP').val(obj[0].monto_cp);
            $('#layerFormCampoVisita #txtCampoObservacion').val(obj[0].observacion);
            $('#layerFormCampoVisita #lbDireccionCampo').text(obj[0].direccion);
            $('#layerFormCampoVisita #HdCodIdCampoDireccion').val(obj[0].idtransaccion);
            $('#layerFormCampoVisita #HdIdTransaccionCampo').val(obj[0].idtransaccion);
            $('#layerFormCampoVisita #HdIdVisitaCampo').val(obj[0].idvisita);
            $('#layerFormCampoVisita #HdIdCpgCampo').val(obj[0].idcompromiso_pago);
            /**********/
            $('#layerFormCampoVisita #cbCampoFinal').val(obj[0].idfinal);
        /**********/
        }
    },
    DataLlamadaPorId : function ( idLlamada , f_edit ) {
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'DataLlamadaPorId',
                Id:idLlamada
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Traendo datos de llamada...',300);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                f_edit(obj);
            },
            error : function ( ) {
                _noneBeforeSend();
            }
        });
    },
    FillBoxFormLlamadaAtencionCliente : function ( obj ) {
        if( obj.length==1 ) {
            $('#layerFormAtencionLlamada #cbLlamadaEstadoLlamada').val(obj[0].idestado_transaccion);
            $('#layerFormAtencionLlamada #cbLlamadaPeso').attr("title",obj[0].idpeso_transaccion);
            $('#layerFormAtencionLlamada #cbCargaFinalLlamada').val(obj[0].carga_final);
            $('#layerFormAtencionLlamada #cbTipoFinalLlamada').val(obj[0].tipo_final);
            $('#layerFormAtencionLlamada #cbNivelLlamada').val(obj[0].nivel);
            $('#layerFormAtencionLlamada #cbFinalLlamada').attr("title",obj[0].idfinal);
            $('#layerFormAtencionLlamada #txtAtencionLlamadaFechaCp').val(obj[0].fecha_cp);
            $('#layerFormAtencionLlamada #txtAtencionLlamadaMontoCp').val(obj[0].monto_cp);
            $('#layerFormAtencionLlamada #txtObservacionLlamada').val(obj[0].observacion);
            $('#txtAtencionClienteNumeroCall').val(obj[0].numero);
            $('#HdIdTelefono').val(obj[0].idtelefono);
            $('#layerFormAtencionLlamada #cbLlamadaEstadoLlamada').trigger('change');
            $('#layerFormAtencionLlamada #cbNivelLlamada').trigger('change');
            $('#layerFormAtencionLlamada #HdIdLlamadaAtencionCliente').val(obj[0].idllamada);
            $('#layerFormAtencionLlamada #HdIdCpgAtencionCliente').val(obj[0].idcompromiso_pago);
            $('#layerFormAtencionLlamada #HdIdTransaccionAtencionCliente').val(obj[0].idtransaccion);
					
        }
    },
    ListarEventosHoy : function ( ) {
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'GET',
            dataType : 'json',
            async : false,
            data : {
                command:'atencion_cliente',
                action:'ListarEventosHoy',
                UsuarioServicio:$('#hdCodUsuarioServicio').val()
            },
            beforeSend : function (){
                $('#tableEventToDay').html(templates.IMGloadingContentLayer());
            },
            success : function ( obj ) {
                var html='';
                if( obj.length==0 ){
                    html+='<div class="LayerTareaEventoHoy" align="center">No hay eventos</div>';
                    $('#tableEventToDay').html(html);
                    return false;
                }
                $.each(obj,function (key,data) {
                    html+='<div align="left" style="paddig:2px 0;" class="LayerHeaderTareaEvento" id="LayerToDayEvent_'+data.idevento+'" >';
                    html+='<label style="margin-left:2px;" class="text-black">'+data.hora+'</label>';
                    html+='</div>';
                    html+='<div align="left" style="padding:2px 0;" class="LayerTareaEventoHoy">';
                    html+='<label style="margin-left:2px;">'+data.evento+'</label>';
                    html+='</div>';
                });
                $('#tableEventToDay').html(html);
                var height=$('#tableEventToDay').height();
                if( height>=150 ) {
                    $('#tableEventToDay').css('height','150px');
                }else{
                    $('#tableEventToDay').css('height','auto');
                }
            },
            error : function ( ){
								
            }
        });
    },
    ListarTareasHoy : function ( ) {
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListarTareasHoy',
                UsuarioServicio:$('#hdCodUsuarioServicio').val()
            },
            beforeSend : function (){
                $('#tableWorkToDay').html(templates.IMGloadingContentLayer());
            },
            success : function ( obj ) {
                var html='';
								
                if( obj.length==0 ){
                    html+='<div class="LayerTareaEventoHoy" align="center">No hay tarea</div>';
                    $('#tableWorkToDay').html(html);
                    return false;
                }
								
								
                $.each(obj,function (key,data){
                    html+='<div align="left" style="padding:2px 0;" class="LayerHeaderTareaEvento" id="LayerToDayEvent_'+data.idevento+'" >';
                    html+='<label style="margin-left:2px;" class="text-black">'+data.hora+'</label>';
                    html+='</div>';
                    html+='<div align="left" style="padding:2px 0;" class="LayerTareaEventoHoy">';
                    html+='<label style="margin-left:2px;">'+data.titulo+'</label>';
                    html+='</div>';
                });
                $('#tableWorkToDay').html(html);
								
                var height=$('#tableWorkToDay').height();
                if( height>=150 ) {
                    $('#tableWorkToDay').css('height','150px');
                }else{
                    $('#tableWorkToDay').css('height','auto');
                }
            },
            error : function ( ){
								
            }
        });
    },
    ListarSpeechArgumentario : function ( ) {
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListarSpeechArgumentario',
                Servicio:$('#hdCodServicio').val()
            },
            beforeSend : function (){
                $('#tableSpeechArgumentario').html(templates.IMGloadingContentLayer());
            },
            success : function ( obj ) {
                var html='';
								
                if( obj.length==0 ){
                    html+='<div class="LayerTareaEventoHoy" align="center">No hay data</div>';
                    $('#tableSpeechArgumentario').html(html);
                    return false;
                }
								
								
                $.each(obj,function (key,data){
                    html+='<div align="left" style="padding:2px 0;" class="LayerHeaderTareaEvento" >';
                    html+='<label style="margin-left:2px;" class="text-black">'+data.tipo+'</label>';
                    html+='</div>';
                    html+='<div align="left" style="padding:2px 0;" class="LayerTareaEventoHoy">';
                    html+='<table cellpadding="0" cellspacing="0" border="0">';
                    html+='<tr>';
                    html+='<td align="left" style="width:160px;">';
                    html+='<label style="margin-left:2px;white-space:pre-line;">'+data.nombre+'</label>';
                    html+='</td>';
                    html+='<td align="center">';
                    html+='<span class="ui-icon ui-icon-search" onclick="leer_ayuda_gestion('+data.idayuda_gestion+','+data.is_text+')" ></span>';
                    html+='</td>';
                    html+='</tr>';
                    html+='</table>';
                    html+='</div>';
                });
                $('#tableSpeechArgumentario').html(html);
								
                var height=$('#tableSpeechArgumentario').height();
                if( height>=150 ) {
                    $('#tableSpeechArgumentario').css('height','150px');
                }else{
                    $('#tableSpeechArgumentario').css('height','auto');
                }
            },
            error : function ( ){
								
            }
        });
    },
    LeerAyudaGestion : function ( idAyudaGestion, xisText, f_fill_text, f_fill_file ) {
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'LeerAyudaGestion',
                Id:idAyudaGestion,
                IsText:xisText
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Trayendo data...',320);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if( xisText==1 ){
                    if( f_fill_text ){
                        f_fill_text(obj);
                    }else{
                        AtencionClienteDAO.FillAyudaGestionText(obj);
                    }
                }else{
                    if( f_fill_file ) {
                        f_fill_file(obj);
                    }else{
                        AtencionClienteDAO.FillAyugaGestionFile(obj);
                    }
                }
            },
            error : function ( ) {
                _noneBeforeSend();
            }
        });
    },
    FillAyugaGestionFile : function ( obj ) {
        var html='';
        for(  i=0;i<obj.msg.length;i++ ) {
            html+='<span>'+obj.msg[i].line+'</span><br>';
        }
        $('#DataReadFileAndText #DataSpeechArgument').html(html);
        $('#DataReadFileAndText').dialog('open');
    },
    FillAyudaGestionText : function ( obj ) {
        if( obj.length>0 ){
            $('#DataReadFileAndText #DataSpeechArgument').html(obj[0]['texto']);
            $('#DataReadFileAndText').dialog('open');
        }
    },
    SearchTelefonosCliente : function ( ) {
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'SearchTelefonosCliente',
                Cliente:$.trim( $('#DialogBuscarTelefono #txtSearchTelefonoCliente').val()),
                Servicio:$('#hdCodServicio').val()
            },
            beforeSend : function ( ) {
                $('#layerTableResultSearchTelefonoCliente').html(templates.IMGloadingContentLayer());
            },
            success : function ( obj ) {
                AtencionClienteDAO.FillSearchTelefonoCliente(obj);
            },
            error : function ( ) {
                var html='';
                html+='<table cellspacing="0" cellpadding="0" border="0" class="ui-corner-top" >';
                html+='<tr class="ui-state-default" >'
                html+='<th style="width:25px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" ></th>'
                html+='<th style="width:80px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >Codigo</th>'
                html+='<th style="width:200px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >Cliente</th>'
                html+='<th style="width:86px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >Servicio</th>'
                html+='<th style="width:86px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >Numero</th>'
                html+='<th style="width:60px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >Anexo</th>'
                html+='<th style="width:80px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >Origen</th>'
                html+='<th style="width:100px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >Tipo Telefono</th>'
                html+='<th style="width:100px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >Tipo Referencia</th>'
                html+='<th style="width:60px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >Carga</th>'
                html+='<th style="width:25px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" ></th>'
                html+='</tr>'
                html+='</table>'
                $('#layerTableResultSearchTelefonoCliente').html(html);
            }
        });
    },
    FillSearchTelefonoCliente : function ( obj )  {
        var html='';
        html+='<table cellspacing="0" cellpadding="0" border="0" class="ui-corner-top" >';
        html+='<tr class="ui-state-default" >';
        html+='<th style="width:25px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" ></th>';
        html+='<th style="width:80px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >Codigo</th>';
        html+='<th style="width:200px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >Cliente</th>';
        html+='<th style="width:80px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >Servicio</th>';
        html+='<th style="width:86px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >Numero</th>';
        html+='<th style="width:60px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >Anexo</th>';
        html+='<th style="width:80px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >Origen</th>';
        html+='<th style="width:100px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >Tipo Telefono</th>';
        html+='<th style="width:100px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >Tipo Referencia</th>';
        html+='<th style="width:60px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >Carga</th>';
        html+='<th style="width:25px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" ></th>';
        html+='</tr>';
        html+='</table>';
        html+='<div style="height:170px;overflow-x:auto;width:925px;">';
        html+='<table cellspacing="0" cellpadding="0" border="0" >';
        for( i=0;i<obj.length;i++ ) {
            html+='<tr id="'+obj[i].idtelefono+'" class="ui-widget-content" >';
            html+='<td align="center" style="width:25px;padding:3px 0;" class="ui-state-default">'+(i+1)+'</td>';
            html+='<td align="center" style="width:80px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+obj[i].codigo+'</td>';
            html+='<td align="center" style="width:200px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+obj[i].cliente+'</td>';
            html+='<td align="center" style="width:80px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+obj[i].servicio+'</td>';
            html+='<td align="center" style="width:80px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+obj[i].numero+'</td>';
            html+='<td align="center" style="width:60px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+obj[i].anexo+'</td>';
            html+='<td align="center" style="width:80px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+obj[i].origen+'</td>';
            html+='<td align="center" style="width:100px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+obj[i].tipo_telefono+'</td>';
            html+='<td align="center" style="width:100px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+obj[i].tipo_referencia+'</td>';
            html+='<td align="center" style="width:60px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+obj[i].carga_final+'</td>';
            html+='<td align="center" style="width:25px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;"><input value="'+obj[i].idtelefono+'" type="checkbox" /></td>';
            html+='</tr>';
        }
        html+='</table>';
        html+='<div>';
        $('#layerTableResultSearchTelefonoCliente').html(html);
    },
    AtencionClienteImportarTelefonosGestionActual : function ( ids ) {
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ImportarTelefonosGestion',
                Cliente : $('#idClienteMain').val(),
                Cartera:$('#cbAtencionGlobalesCartera').val(),
                UsuarioCreacion:$('#hdCodUsuario').val(),
                IdsTelefono:ids
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Importando telefonos seleccionados...',400);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if( obj.rst ) {
                    $('#table_telefonos').jqGrid().trigger('reloadGrid');
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'300px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                }else{
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                }
            },
            error : function ( ) {
                _noneBeforeSend();
            }
        });
    },
    ListarTipoEstado : function ( ) {
				
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListStateType',
                Servicio:$('#hdCodServicio').val(),
                TipoTransaccion:1
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                AtencionClienteDAO.FillAtencionTipoEstadoLLamada(obj);
            },
            error : function ( ) {}
        });
				
    },
    FillAtencionTipoEstadoLLamada : function ( obj ) {
        var html='';
        html+='<option value="0" >--Seleccione--</option>';
        for( i=0;i<obj.length;i++ ) {
            html+='<option value="'+obj[i].idtipo_estado+'" >'+obj[i].nombre+'</option>';
        }
        $('#cbLlamadaTipoEstado').html(html);
    },
    ListarEstado : function ( ) {
				
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'GET',
            dataType : 'json',
            async : false,
            data : {
                command:'atencion_cliente',
                action:'ListState',
                Servicio : $('#hdCodServicio').val()
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                AtencionClienteDAO.FillAtencionEstadoLLamada(obj.llamada);
                AtencionClienteDAO.FillAtencionFiltroEstadoLLamada(obj.llamada);
				AtencionClienteDAO.FillCampoEstadoVisita(obj.visita);
                AtencionClienteDAO.FillLlamadaEstadoCuotificacion(obj.cuotificacion);
                AtencionClienteDAO.EstadosCuenta=obj.cuenta;
                AtencionClienteDAO.EstadosLlamada=obj.llamada;
                AtencionClienteDAO.EstadosVisita=obj.visita;
                /***********/
                AtencionClienteDAO.FillAtencionEstadoLLamadaBusquedaEstado(obj.llamada);
            /************/
            },
            error : function ( ) {}
        });
				
    },
	FillCampoEstadoVisita : function ( obj ) {
		var html='';
        html+='<option value="0" >--Seleccione--</option>';
        for( i=0;i<obj.length;i++ ) {
            html+='<option code="'+obj[i].codigo+'" carga = "'+obj[i].idcarga_final+'" weight = "'+obj[i].peso+'" value="'+obj[i].idfinal+'" >'+obj[i].nombre+'</option>';
        }
        $('#cbCampoFinal').html(html);
        $('#cbCampoFinal_vis').html(html);
	},
    FillAtencionEstadoLLamada : function ( obj ) {
        var html='';
        html+='<option value="0" >--Seleccione--</option>';
        for( i=0;i<obj.length;i++ ) {
            var data = (obj[i].data).split('|');
            html+='<optgroup id="'+obj[i].idcarga_final+'" label="'+obj[i].CARGA+'" >';
            for( j=0;j<data.length;j++ ) {
                var final = data[j].split('@#');	
//                html+='<option code="'+final[3]+'" weight="'+final[2]+'" value="'+final[0]+'" flg_volver_llamar="'+final[4]+'">'+final[1]+'</option>';
				html+='<option code="'+final[3]+'" weight="'+final[2]+'" value="'+final[0]+'" flg_volver_llamar="'+final[4]+'" estado_observa="'+final[5]+'" flag_compromiso_pago="'+final[6]+'" dias_fecha_cp="'+final[7]+'">'+final[1]+'</option>';
            }
            html+='</optgroup>';
        //html+='<option value="'+obj[i].idestado+'" >'+obj[i].descripcion+'</option>';
        //html+='<option value="'+obj[i].idfinal+'" >'+obj[i].nombre+'</option>';
        }
        $('#cbLlamadaEstado').html(html);
    },
    FillAtencionFiltroEstadoLLamada : function ( obj ) {
        var html='';
        //html+='<option value="0" >--Seleccione--</option>';
        //html+='<option value="-1" >SIN GESTION</option>';
        for( i=0;i<obj.length;i++ ) {
            var data = (obj[i].data).split('|');
            //html+='<optgroup label="'+obj[i].CARGA+'" >';
            html+='<label class="ui-helper-reset ui-state-highlight" style="font-size:14px;font-weight:bold;" >'+obj[i].CARGA+'</label>';
            html+='<div>';
            html+='<table>';
            for( j=0;j<data.length;j++ ) {
                var final = data[j].split('@#');
                //html+='<option value="'+final[0]+'" >'+final[1]+'</option>';
                html+='<tr>';
                html+='<td align="center" style="width:20px;"><input type="checkbox" value="'+final[0]+'" /><td>';
                html+='<td>'+final[1]+'<td>';
                html+='<tr>';
            }
            html+='</table>';
            //html+='</optgroup>';
            html+='</div>';
        }
        //$('#cbFiltroEstado').html(html);
        $('#layerContentFiltroEstado').html(html);
				
    },
    FillAtencionEstadoLLamadaBusquedaEstado : function ( obj ) {
        var html='';
        html+='<option value="0" >--Seleccione--</option>';
        for( i=0;i<obj.length;i++ ) {
            var data = (obj[i].data).split('|');
            html+='<optgroup label="'+obj[i].CARGA+'" >';
            for( j=0;j<data.length;j++ ) {
                var final = data[j].split('@#');	
                html+='<option value="'+final[0]+'" >'+final[1]+'</option>';
            }
            html+='</optgroup>';
        //html+='<option value="'+obj[i].idestado+'" >'+obj[i].descripcion+'</option>';
        //html+='<option value="'+obj[i].idfinal+'" >'+obj[i].nombre+'</option>';
        }
        $('#cbEstadosLLamadaBusquedaEstado').html(html);
    },
		
    DeleteTodasNotas : function ( ) {
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'DeleteAllNota',
                UsuarioServicio:$('#hdCodUsuarioServicio').val(), 
                UsuarioModificacion:$('#hdCodUsuario').val()
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                if( obj.rst ) {
                    $('#dialogNotasHoy #tableNotas').empty();
                }else{
									
            }
            },
            error : function ( ) {}
        });
    },
    DeleteNotas : function ( xIdNotas ) {
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'DeleteNota',
                notas:xIdNotas,
                UsuarioModificacion:$('#hdCodUsuario').val()
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                if( obj.rst ) {
                    $('#dialogNotasHoy #tableNotas').find("input:checked").parent().parent().remove();
                }else{
									
            }
            },
            error : function ( ) {}
        });
    },
    MarcarNoLeidoNotas : function ( xIdNotas ) {
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'MarcarNoLeidoNotas',
                notas:xIdNotas,
                UsuarioModificacion:$('#hdCodUsuario').val()
            },
            beforeSend : function ( ) {
								
            },
            success : function ( obj ) {
                if( obj.rst ) {
                    $('#dialogNotasHoy #tableNotas').find("input:checked").parent().parent().css('color','#0066FF');
                }else{
									
            }
            },
            error : function ( ) {}
        });
    },
    MarcarLeidoNotas : function ( idNota, element ) {
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'MarcarLeidoNotas',
                Id:idNota,
                UsuarioModificacion:$('#hdCodUsuario').val()
            },
            beforeSend : function ( ) {
								
            },
            success : function ( obj ) {
                if( obj.rst ) {
                    $(element).parent().css('color','');
                }else{
									
            }
            },
            error : function ( ) {}
        });
    },
    MarcarNotaComoImportante : function ( ids ) {
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'MarcarNotasComoImportante',
                notas:ids,
                UsuarioModificacion:$('#hdCodUsuario').val()
            },
            beforeSend : function ( ) {
								
            },
            success : function ( obj ) {
                if( obj.rst ) {
                    var objCHECKED=$('#dialogNotasHoy #tableNotas').find("input:checked");
                    $.each(objCHECKED,function( key, data ){
                        var id=$(data).val();
                        $(data).parent().parent().find("td[title^='importante']").html('<img style="cursor:pointer;" onclick="desmarcar_nota_como_importante('+id+',this)" src="../img/star.png" />');
                    });
                }else{
									
            }
            },
            error : function ( ) {}
        });
    },
    DesmarcaNotaComoImportante : function ( idNota, element ) {
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'DesmarcarNotasComoImportante',
                Id:idNota,
                UsuarioModificacion:$('#hdCodUsuario').val()
            },
            beforeSend : function ( ) {
								
            },
            success : function ( obj ) {
                if( obj.rst ) {
                    $(element).remove();
                }else{
									
            }
            },
            error : function ( ) {}
        });
    },
    NotasPorId : function ( idNota ) {
				
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'MostrarNotaPorId',
                Id:idNota
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                $('#lbNotaCodigoCliente').text(obj[0].codigo);
                $('#ldNotaNombreCliente').text(obj[0].cliente);
                $('#ldNotaFechaCreacion').text(obj[0].fecha_creacion);
                $('#ldNotaUsuarioCreacion').text(obj[0].usuario_creacion);
                $('#layerNotaDescripcion').text(obj[0].descripcion);
                $('#dialogNotasHoy #layerTableNotas').hide();
                $('#dialogNotasHoy #layerDetalleNota').fadeIn();
            },
            error : function ( ) {
								
            }
        });
				
    },
    save_etiqueta : function ( ) {
				
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command : 'atencion_cliente',
                action : 'GuardarEtiqueta',
                Nombre : $('#txtNombreEtiqueta').val(),
                Descripcion : $('#txtDescripcionEtiqueta').val(),
                UsuarioServicio : $('#hdCodUsuarioServicio').val(),
                UsuarioCreacion : $('#hdCodUsuario').val()
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                if( obj.rst ){
									
                }else{
									
            }
            },
            error : function ( ) {}
        });
				
    },
    ListarEtiquetas : function ( ) {
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente'
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
						
                var html='';
                for( i=0;i<obj.length;i++ ) {
                    html+='<option>'+'</option>';
                }
							
            },
            error : function ( ) {}
        });
    },
    ListarTramoAtencionCliente : function ( carteras ) {
				
        $.ajax({
            url : AtencionClienteDAO.url,
            async : false,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListTramo',
                Cartera : carteras
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                AtencionClienteDAO.FillAtencionClienteTramo(obj);
            },
            error : function ( ) {
								
            }
        });
				
    },
    FillAtencionClienteTramo :  function ( obj ) {
        var html='<option value="0">--</option>';
        for( i=0;i<obj.length;i++ ){
            html+='<option value="'+obj[i].tramo+'" >'+obj[i].tramo+'</option>';
        }
        $('#cbFiltroTramo').html(html);
    },
			
    ListarRankingUsuarioServicio : function ( ) {
        var carteras = $('#tbCarterasMultiples').jqGrid('getGridParam','selarrrow');
        if(carteras.length > 0)
        {
            $.ajax({
                url : AtencionClienteDAO.url,
                type : 'GET',
                dataType : 'json',
                data : {
                    command:'atencion_cliente',
                    action:'ranking_usuario_servicio',
                    Servicio : $('#hdCodServicio').val(), 
                    Cartera : carteras,
                    UsuarioServicio : $('#hdCodUsuarioServicio').val()
                },
                beforeSend : function ( ) {
                    $('#TableRankingUsuarioServicio').html(templates.IMGloadingContentTable());
                },
                success : function ( obj ) {
                    var html='';
                    var data = eval(obj);
                    for( i=0;i<data.length;i++ ) {
                        if( i==0 ) {
                            var html_h='';
                            var html_d='';
                            for( j in data[i] ) {
                                if( j=='FECHA' ){
                                    html_h+='<td align="center" style="padding:3px;border:1px solid #E0CFC2;width:150px;">'+j+'</td>';
                                    html_d+='<td align="center" style="padding:3px;border:1px solid #E0CFC2;width:150px;">'+data[i][j]+'</td>';
                                }else{
                                    html_h+='<td align="center" style="padding:3px;border:1px solid #E0CFC2;width:100px;">'+j+'</td>';
                                    html_d+='<td align="center" style="padding:3px;border:1px solid #E0CFC2;width:100px;">'+data[i][j]+'</td>';
                                }
                            }
                            html+='<tr class="ui-state-default">'+html_h+'</tr><tr class="ui-widget-content" >'+html_d+'</tr>';
                        }else{
                            html+='<tr class="ui-widget-content">';
                            for( j in data[i] ) {
                                if( j=='FECHA' ){
                                    html+='<td align="center" style="padding:3px;border:1px solid #E0CFC2;width:150px;">'+data[i][j]+'</td>';
                                }else{
                                    html+='<td align="center" style="padding:3px;border:1px solid #E0CFC2;width:100px;">'+data[i][j]+'</td>';
                                }
                            }
                            html+='</tr>';
                        }
                    }
                    $('#TableRankingUsuarioServicio').html(html);
                },
                error : function ( ) {}
            });
        }else{
            alert('No hay cartera seleccionada, seleccione almenos una cartera');
        }
    },
    update_anexo : function ( )	{
				
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command : 'atencion_cliente', 
                action : 'update_anexo', 
                Servicio : $('#hdCodServicio').val(),
                UsuarioServicio : $('#hdCodUsuarioServicio').val(), 
                Anexo : $.trim( $('#DialogActualizarAnexo #txtAnexoTeleoperador').val() ) 
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Guardando Anexo ...',250);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if( obj.rst ) {
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
                    AtencionClienteDAO.setTimeOut_hide_message();

                    $('#hdAnexoOp').val( $.trim( $('#DialogActualizarAnexo #txtAnexoTeleoperador').val() ) );
                    $('#DialogActualizarAnexo').hide();

                }else{
                    $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'350px'));
                    AtencionClienteDAO.setTimeOut_hide_message();
                }
								
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        });
				
    },
    ListarCuentaVisita : function ( xidcartera, xIdClienteCartera ) {
				
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'GET',
            async : false,
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'ListCuenta',
                Cartera : xidcartera, 
                IdClienteCartera : xIdClienteCartera,
                empresa: $("#cbCampoEmpresa").find(":selected").val(),
                td: $("#vis_xtd").val(),
                doc: $("#xvis_doc").val(),
                contado: $('#adelantado').attr('checked') ? 1 : 0
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                var html='';
                var activar="";
                var cantidad_contratos_campo=0;
                var total_cuotas_opcion_campo=0;
                var total_seguro_opcion_campo=0;
                var total_otro_opcion_campo=0;
                var total_opcion_campo=0;
                var contrato_x_reg = '';
                var arrcontratos=[];

                var xvidcant_docum=0;
                var xvitot_orig=0;
                var xvitot_saldos_sol=0;
                var xvitot_saldos_dol=0;

                    html+='<thead>';
                    html+='<tr class="ui-state-default">';
                    html+='<th align="center" style="font-size: 9px;width: 60px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #E0CFC2; border-top: 1px solid #E0CFC2; border-bottom: 1px solid #E0CFC2; border-left: 1px solid #E0CFC2;">EMPRESA</th>';
                    html+='<th align="center" style="font-size: 9px;width: 25px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #E0CFC2; border-top: 1px solid #E0CFC2; border-bottom: 1px solid #E0CFC2;">TD</th>';                    
                    html+='<th align="center" style="font-size: 9px;width: 80px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #E0CFC2; border-top: 1px solid #E0CFC2; border-bottom: 1px solid #E0CFC2;">DOCUM</th>';
                    html+='<th align="center" style="font-size: 9px;width: 60px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #E0CFC2; border-top: 1px solid #E0CFC2; border-bottom: 1px solid #E0CFC2;">FECH.EMI.</th>';
                    html+='<th align="center" style="font-size: 9px;width: 60px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #E0CFC2; border-top: 1px solid #E0CFC2; border-bottom: 1px solid #E0CFC2;">FECH.VENC.</th>';
                    html+='<th align="center" style="font-size: 9px;width: 53px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #E0CFC2; border-top: 1px solid #E0CFC2; border-bottom: 1px solid #E0CFC2;">D.MORA</th>';
                    html+='<th align="center" style="font-size: 9px;width: 100px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #E0CFC2; border-top: 1px solid #E0CFC2; border-bottom: 1px solid #E0CFC2;">RANGO</th>';
                    html+='<th align="center" style="font-size: 9px;width: 50px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #E0CFC2; border-top: 1px solid #E0CFC2; border-bottom: 1px solid #E0CFC2;">ESTADO</th>';
                    html+='<th align="center" style="font-size: 9px;width: 50px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #E0CFC2; border-top: 1px solid #E0CFC2; border-bottom: 1px solid #E0CFC2;">BANCO</th>';
                    html+='<th align="center" style="font-size: 9px;width: 80px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #E0CFC2; border-top: 1px solid #E0CFC2; border-bottom: 1px solid #E0CFC2;">NUM.COBR.</th>';
                    html+='<th align="center" style="font-size: 9px;width: 25px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #E0CFC2; border-top: 1px solid #E0CFC2; border-bottom: 1px solid #E0CFC2;">MON</th>';
                    html+='<th align="center" style="font-size: 9px;width: 68px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #E0CFC2; border-top: 1px solid #E0CFC2; border-bottom: 1px solid #E0CFC2;">IMP.ORIG</th>';
                    html+='<th align="center" style="font-size: 9px;width: 68px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #E0CFC2; border-top: 1px solid #E0CFC2; border-bottom: 1px solid #E0CFC2;">SALDO $</th>';
                    html+='<th align="center" style="font-size: 9px;width: 68px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #E0CFC2; border-top: 1px solid #E0CFC2; border-bottom: 1px solid #E0CFC2;">SALDO S/.</th>';
                    html+='<th align="center" style="font-size: 9px;width: 70px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #E0CFC2; border-top: 1px solid #E0CFC2; border-bottom: 1px solid #E0CFC2;">FECHA CP</th>';
                    html+='<th align="center" style="font-size: 9px;width: 70px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #E0CFC2; border-top: 1px solid #E0CFC2; border-bottom: 1px solid #E0CFC2;">MONTO CP</th>';
                    html+='<th align="center" style="font-size: 9px;width: 80px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #E0CFC2; border-top: 1px solid #E0CFC2; border-bottom: 1px solid #E0CFC2;">MONEDA CP</th>';
                    html+='<th align="center" style="font-size: 9px;width: 25px; padding: 3px 0pt; border-right: 1px solid #E0CFC2; border-top: 1px solid #E0CFC2; border-bottom: 1px solid #E0CFC2;"><input type="checkbox" value="" id="checkheader" onclick="checked_all(this.checked,\'table_cuenta_aplicar_gestion_visita\')"></th>';
                    html+='<th align="center" style="font-size: 9px;width: 25px; padding: 3px 0pt; border-right: 1px solid #E0CFC2; border-top: 1px solid #E0CFC2; border-bottom: 1px solid #E0CFC2;">&nbsp;</th>';
                    html+='</tr>';
                    html+='</thead>';

                    html+='<tbody style="border-bottom: 1px solid #E0CFC2;border-right: 1px solid #E0CFC2;border-left: 1px solid #E0CFC2;">';
                    for( i=0;i<obj.length;i++ ) {

                        if(i==0){
                            activar="checked='checked'";
                        }else{
                            activar="";
                        }

                        // total_cuotas_opcion_campo=total_cuotas_opcion_campo+parseFloat(obj[i].cuota_mensual);
                        // total_seguro_opcion_campo=total_seguro_opcion_campo+parseFloat(obj[i].seguros);
                        // total_otro_opcion_campo=total_otro_opcion_campo+parseFloat(obj[i].otros);
                        // total_opcion_campo=total_opcion_campo+parseFloat(obj[i].cuota_mensual)+parseFloat(obj[i].seguros)+parseFloat(obj[i].otros);

                        //var ximporte_origin=obj[i].importe_original;

                        xvidcant_docum=xvidcant_docum+1;
                        xvitot_orig=xvitot_orig+parseFloat(obj[i].importe_original);
                        xvitot_saldos_sol=xvitot_saldos_sol+parseFloat(obj[i].total_convertido_a_soles);
                        xvitot_saldos_dol=xvitot_saldos_dol+parseFloat(obj[i].total_convertido_a_dolares);
                        
                        html+='<tr class="ui-widget-content" id="'+obj[i].idcuenta+'">';
                        html+='<td align="center" style="width: 60px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size: 9px;height: 15px;">'+obj[i].empresa+'</td>';
                        html+='<td align="center" style="width: 25px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size: 9px;height: 15px;">'+obj[i].td+'</td>';                        
                        html+='<td align="center" style="width: 80px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size: 9px;height: 15px;">'+obj[i].num_doc+'</td>';
                        html+='<td align="center" style="width: 60px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size: 9px;height: 15px;">'+obj[i].fecha_doc+'</td>';
                        html+='<td align="center" style="width: 60px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size: 9px;height: 15px;">'+obj[i].fecha_vcto+'</td>';
                        html+='<td align="center" style="width: 53px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size: 9px;height: 15px;">'+obj[i].dias_transc_vcto_of+'</td>';
                        html+='<td align="center" style="width: 100px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size: 9px;height: 15px;">'+obj[i].rango_vcto+'</td>';
                        html+='<td align="center" style="width: 50px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size: 9px;height: 15px;">'+obj[i].est_letr+'</td>';
                        html+='<td align="center" style="width: 50px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size: 9px;height: 15px;">'+obj[i].banco+'</td>';
                        html+='<td align="center" style="width: 80px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size: 9px;height: 15px;">'+obj[i].num_cobranza+'</td>';
                        html+='<td align="center" style="width: 25px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size: 9px;height: 15px;">'+obj[i].mon+'</td>';
                        html+='<td align="center" style="width: 68px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size: 9px;height: 15px;text-align:right;">'+formato_numero(parseFloat(obj[i].importe_original).toFixed(2))+'</td>';
                        html+='<td align="center" style="width: 68px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size: 9px;height: 15px;text-align:right;">'+formato_numero(obj[i].total_convertido_a_dolares)+'</td>';
                        html+='<td align="center" style="width: 68px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size: 9px;height: 15px;text-align:right;">'+formato_numero(obj[i].total_convertido_a_soles)+'</td>';
                        html+='<td align="center" style="width: 70px;white-space:pre-line;padding:2px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;height: 15px;"><input name="txtFechaCpCuenta" style="width:60px;" type="text" /></td>';
                        html+='<td align="center" style="width: 70px;white-space:pre-line;padding:2px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;height: 15px;"><input name="txtMontoCpCuenta"  style="width:60px;" type="text" /></td>';
                        html+='<td align="center" style="width: 80px;white-space:pre-line;padding:2px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;height: 15px;"><select name="cbMonedaCpCuenta"><option value="SOLES">SOLES</option><option value="DOLARES">DOLARES</option><option value="EUROS">EUROS</option></select></td>';
                        html+='<td align="center" style="width: 24px;padding:2px 0;border-bottom:1px solid #E0CFC2;height: 15px;float: left;" class="ui-state-default"><input type="checkbox" value="'+obj[i].idcuenta+'" /></td>'; 
                        html+='<td align="center" style="padding:2px 0;border-bottom:1px solid #E0CFC2;height: 15px;"></td>'; 
                        html+='</tr>';                        

                        if(contrato_x_reg!=obj[i].contrato){
                            arrcontratos.push(obj[i].contrato);
                        }
                        contrato_x_reg=obj[i].contrato;
                    }
                    html+='</tbody>';
                // $("#cantidad_contratos_campo").text(arrcontratos.length);
                // $("#total_cuotas_opcion_campo").text(total_cuotas_opcion_campo.toFixed(2));
                // $("#total_seguro_opcion_campo").text(total_seguro_opcion_campo.toFixed(2));
                // $("#total_otro_opcion_campo").text(total_otro_opcion_campo.toFixed(2));
                // $("#total_opcion_campo").text(total_opcion_campo.toFixed(2));

                $("#vidcant_docum").text(xvidcant_docum.toFixed(2));
                $("#vitot_orig").text(xvitot_orig.toFixed(2));
                $("#vitot_saldos_sol").text(xvitot_saldos_sol.toFixed(2));
                $("#vitot_saldos_dol").text(xvitot_saldos_dol.toFixed(2));

                $('#table_cuenta_aplicar_gestion_visita').html(html);

                $('#table_cuenta_aplicar_gestion_visita').find("tr").find(":text[name='txtFechaCpCuenta']").datepicker({
                    dateFormat:'yy-mm-dd',
                    //  minDate : 0,
                    //  maxDate : +15,
                    dayNamesMin:['D','L','M','M','J','V','S'],
                    monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre'], 
                    currentText : 'Now',
                    showButtonPanel: true,
                    //CAMBIOS 19-06-2016
                    beforeShow: function (input) {
                            var buttonPane = $(input).datepicker("widget").find(".ui-datepicker-buttonpane");
                            var btn = $('<button class="ui-datepicker-current ui-state-default ui-priority-secondary ui-corner-all" type="button">Limpiar</button>');
                            btn.unbind("click").bind("click", function () {
                                $(input).val('');
                            });
                            btn.appendTo(buttonPane);
                    }
                    //CAMBIOS 19-06-2016
                });
                

                
            },
            error : function ( ) {}
        });
				
    },
    /*jmore300612*/
    ListarDataDetalleCuenta : function ( xidcuenta, xidcartera, f_success, f_before ) { 
				
				$.ajax({
					   	url : this.url,
						type : 'GET',
						dataType : 'json',
						data : { command : 'atencion_cliente', action : 'ListarDataDetalleCuenta', idcuenta : xidcuenta, idcartera : xidcartera },
						beforeSend : function ( ) {
								f_before();
							},
						success : function ( obj ) {
								f_success(obj);
							},
						error : function ( ) {}
					   });
			},
    GuardarPago : function ( xidcartera,xiddetalle_cuenta, xnumero_cuenta, xmoneda, xcodigo_operacion, xmonto_pagado, xfecha, xestado_pago, xagencia, xobservacion, xusuario_creacion, f_success, f_before ) {
				
				$.ajax({
					   	url : this.url,
						type : 'GET',
						dataType : 'json',
						data : { 
								command : 'atencion_cliente', 
								action : 'GuardarPago', 
								idcartera : xidcartera,
								iddetalle_cuenta : xiddetalle_cuenta,
								numero_cuenta : xnumero_cuenta, 
								moneda : xmoneda,
								codigo_operacion : xcodigo_operacion, 
								monto_pagado : xmonto_pagado,
								fecha : xfecha, 
								estado_pago : xestado_pago,
								observacion : xobservacion ,
								agencia : xagencia,
								usuario_creacion : xusuario_creacion
								},
						beforeSend : function ( ) {
								_displayBeforeSend('Guardando Pagos...',250);
								f_before();
							},
						success : function ( obj ) {
								_noneBeforeSend();
								f_success(obj);
							},
						error : function ( ) {
								_noneBeforeSend();
								AtencionClienteDAO.error_ajax();
							}
					   });
				
			},                        
    /*jmore300612*/    
    DeleteTelefono : function ( xidTelefono, f_success, f_error ) {
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command: 'atencion_cliente',
                action:'DeleteTelefono', 
                idTelefono: xidTelefono
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Eliminando Telefono ...',300);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                f_success(obj);
            },
            error : function () {
                _noneBeforeSend();
                f_error();
            }
        });
    },
    ListarDireccion : function ( xidcartera, xcodigo_cliente, f_success, f_error ) {
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'GET',
            dataType : 'json',
            async : false,
            data : {
                command: 'atencion_cliente',
                action:'ListarDireccion', 
                idcartera : xidcartera, 
                codigo_cliente : xcodigo_cliente
            },
            beforeSend : function ( ) {

            },
            success : function ( obj ) {
                f_success(obj);
            },
            error : function () {
                f_error();
            }
        });
    },
    ListarDireccionVisita : function ( xidcartera, xcodigo_cliente, f_success, f_error ) {
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'GET',
            dataType : 'json',
            async : false,
            data : {
                command: 'atencion_cliente',
                action:'ListarDireccionVisita', 
                idcartera : xidcartera, 
                codigo_cliente : xcodigo_cliente
            },
            beforeSend : function ( ) {

            },
            success : function ( obj ) {
                f_success(obj);
            },
            error : function () {
                f_error();
            }
        });
    },    
    ListarNotificador : function ( f_success, f_error ) {
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command: 'atencion_cliente',
                action:'ListarNotificador', 
                Servicio : $('#hdCodServicio').val()
            },
            beforeSend : function ( ) {

            },
            success : function ( obj ) {
                f_success(obj);
            },
            error : function () {
                f_error();
            }
        });
    },
		
    ListarDetalleCuenta : function ( xidcartera, xidcuenta,xcodigo_cliente, f_success ) {
				
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command: 'atencion_cliente',
                action:'ListarOperacion', 
                idcartera : xidcartera, 
                idcuenta : xidcuenta,
                codigo_cliente:xcodigo_cliente
            },
            beforeSend : function ( ) {
								$("#cargando").css("display","")
            },
            success : function ( obj ) {
                f_success(obj);
            },
            error : function () {
								

            }
        });
			
    },
    ListarPago : function ( xidcartera, xcodigo_operacion, f_success ) {
				
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command: 'atencion_cliente',
                action:'ListarPago', 
                idcartera : xidcartera, 
                codigo_operacion : xcodigo_operacion
            },
            beforeSend : function ( ) {
								
            },
            success : function ( obj ) {
                f_success(obj);
            },
            error : function () {
								

            }
        });
			
    },
    ListarCliente : function ( xidservicio, xidcartera, xcodigo_cliente,xidcliente_cartera, f_success ) {
				
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'GET',
            dataType : 'json',
            async : false,
            data : {
                command: 'atencion_cliente',
                action:'ListarCliente', 
                idcartera : xidcartera, 
                codigo_cliente : xcodigo_cliente, 
                servicio : xidservicio,
                idcliente_cartera : xidcliente_cartera
            },
            beforeSend : function ( ) {
								
            },
            success : function ( obj ) {
                f_success(obj);
            },
            error : function () {
								

            }
        });
			
    },
    NextBack : function ( xcartera, xposition, xmonto, xtramo, xtabla, xcampo, xdato, xreferencia, xis_ha, xhora_inicio, xhora_fin, xotros, xdepartamento, xidfinal, xusuario_matriz, xmodo, f_success ) {
				
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'GET',
            dataType : 'json',
            data : { 
                command : 'atencion_cliente', 
                action : 'NextBack', 
                servicio : $('#hdCodServicio').val(), 
                idusuario_servicio : $('#hdCodUsuarioServicio').val() ,
                cartera : xcartera,
                sordmonto : xmonto,
                tramo : xtramo, 
                xitem : xposition,
                tabla : xtabla,
                campo : xcampo,
                dato : xdato,
                referencia : xreferencia,
                is_ha : xis_ha,
                hora_inicio : xhora_inicio,
                hora_fin : xhora_fin,
                departamento : xdepartamento,
                otros : xotros,
                idfinal : xidfinal,
                usuario_matriz : xusuario_matriz,
                modo : xmodo 
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Datos de gestion...',300);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                f_success(obj);
            },
            error : function ( ) {
                _noneBeforeSend();
            }
        });
				
    },
    CantidadClientesAsignados : function ( xcartera, xusuario_servicio, f_success  ) {
				
        $.ajax({
            url : AtencionClienteDAO.url,
            async : false,
            type : 'GET',
            dataType : 'json',
            data : {
                command : 'atencion_cliente', 
                action: 'data_distribucion_usuario', 
                cartera : xcartera, 
                usuario_servicio : xusuario_servicio
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                f_success(obj);
            },
            error : function ( ) {}
        });
				
    },
    save_correo : function ( xcorreo, xobservacion , xusuario_creacion, xidcliente , f_before,f_success , f_error ) {  
				
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command : 'atencion_cliente', 
                action : 'save_correo', 
                correo : xcorreo, 
                observacion : xobservacion, 
                usuario_creacion : xusuario_creacion ,
                idcliente : xidcliente 
            },
            beforeSend : function ( ) { 
                f_before();
            },
            success : function ( obj ) {
                f_success(obj);
                $('#txtAtencionClienteCorreo').val('');
                $('#txtObservacionAtencionClienteCorreo').val('');
            },
            error : function ( ) {
                f_error();
                
                AtencionClienteDAO.error_ajax();
            }
        });
				
    },
    save_horario_atencion : function ( xhorario_atencion, xobservacion , xusuario_creacion, xidcliente , f_before,f_success , f_error ) {
				
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command : 'atencion_cliente', 
                action : 'save_horario_atencion', 
                horario_atencion : xhorario_atencion, 
                observacion : xobservacion, 
                usuario_creacion : xusuario_creacion ,
                idcliente : xidcliente 
            },
            beforeSend : function ( ) { 
                f_before();
            },
            success : function ( obj ) {
                f_success(obj);
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
                f_error();
            }
        });
				
    },
    save_observacion : function ( xidcliente, xobservacion, f_success ) {
				
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command : 'atencion_cliente', 
                action : 'GuardarObservacion', 
                idcliente : xidcliente, 
                observacion : xobservacion, 
                usuario_creacion : $('#hdCodUsuario').val()
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                f_success(obj);
            },
            error : function ( ) {
                AtencionClienteDAO.error_ajax();
            }
        });
				
    },
    listar_correos_cliente : function ( xidcliente, f_success ) {
				
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command : 'atencion_cliente', 
                action : 'ListarCorreosCliente', 
                idcliente : xidcliente
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                f_success(obj);
            },
            error : function ( ) {}
        });
				
    },
    listar_horarios_atencion_cliente : function ( xidcliente, f_success ) {
			
        $.ajax({
            url : AtencionClienteDAO.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command : 'atencion_cliente', 
                action : 'ListarHorariosAtencionCliente', 
                idcliente : xidcliente
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                f_success(obj);
            },
            error : function ( ) {}
        });
					
    },
    LoadDataGlobal : function ( xidcliente_cartera ) {
				
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command:'atencion_cliente',
                action:'LoadDataGlobal',
                ClienteCartera:xidcliente_cartera
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Data Gestion...',250);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if(obj.length==1){
                    AtencionClienteDAO.FillAllAtencionCliente(obj);
                    $('#tabAC1Resultado').trigger('click');
                }
            },
            error : function ( ) { 
                _noneBeforeSend();
                AtencionClienteDAO.error_ajax();
            }
        });
				
    },
    save_noticia : function	( xtitle, xcontent, f_success ) {
        $.ajax({
            url : this.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command : '', 
                action : '', 
                title : xtitle, 
                content : xcontent
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                f_success(obj);
            },
            error : function ( ) {}
        });
    },
    ListarNoticia : function ( xservicio, f_success ) {
			
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command : '', 
                action : '', 
                idservicio : xservicio
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                f_success(obj);
            },
            error : function ( ) {}
        });
					
    },
    ListarContacto : function ( xidservicio, f_success ) {
			
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            async : false,
            data : {
                command : 'atencion_cliente', 
                action : 'ListarContacto', 
                idservicio : xidservicio
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                f_success(obj);
            },
            error : function ( ) {}
        });
    },
    ListarMotivoNoPago : function ( xidservicio, f_success ) {
				
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            async : false,
            data : {
                command : 'atencion_cliente', 
                action : 'ListarMotivoNoPago', 
                idservicio : xidservicio
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                f_success(obj);
            },
            error : function ( ) {}
        });
			
    },
    ListarSustentoPago : function ( xidservicio, f_success ) {//jmore18112014
                
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            async : false,
            data : {
                command : 'atencion_cliente', 
                action : 'ListarSustentoPago', 
                idservicio : xidservicio
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                f_success(obj);
            },
            error : function ( ) {}
        });
            
    },
    ListarAlertaGestion : function ( xidservicio, f_success ) {//jmore18112014
                
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            async : false,
            data : {
                command : 'atencion_cliente', 
                action : 'ListarAlertaGestion', 
                idservicio : xidservicio
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                f_success(obj);
            },
            error : function ( ) {}
        });
            
    },
    listarSituacionLaboral : function( xidservicio, f_success){
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command: 'atencion_cliente',
                action : 'listarSituacionLaboral',
                idServicio : xidservicio
            },
            beforeSend : function(){

            },
            success : function(obj){
                f_success(obj);
            }
        });
    },
    listarDisposicionRefinanciamiento : function( xidservicio, f_success){
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command: 'atencion_cliente',
                action : 'listarDisposicionRefinanciamiento',
                idServicio : xidservicio
            },
            beforeSend : function(){

            },
            success : function(obj){
                f_success(obj);
            }
        });
    },
    listarEstadoCliente : function( xidservicio, f_success){
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command: 'atencion_cliente',
                action : 'listarEstadoCliente',
                idServicio : xidservicio
            },
            beforeSend : function(){

            },
            success : function(obj){
                f_success(obj);
            }
        });
    },     
    GetLineasFacturaDigitalXcliente : function()
    {
        $.ajax(
        {
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command : 'atencion_cliente', 
                action : 'GetLineasFacturaDigitalXcliente', 
                idClienteCartera : $('#IdClienteCartera').val()
            },
            beforeSend : function ( ) {
						
            },
            success : function ( obj ) {
                var opt = '<option>--Seleccione--</option>';
                if(obj.rst)
                {
                    $.each(
                        obj.data,
                        function(i,value)
                        {
                            opt += '<option value="'+value.idcuenta+'">'+value.telefono+'</option>';
                        }
                        );
                }else{
                    opt = obj.msg;
                }
                $('#cboLinea').html(opt);
            },
            error : function ( ) {}
        }
        );
    },
    CantidadClientesAsignadosFiltros : function ( xidservicio, xidcartera, xusuario_servicio, xordermonto, xtramo, xdepartamento,xprovincia, xotros, xidfinal, xmatriz_usuario, xmodo, xestado_pago, xtabla, xcampo, xdato, xreferencia, xtipo_f_estado,xsemana_opcion,xfiltro_con_sin_gestion,xdepto,xprovin,xdistri,xrango_vcto,xtipo_cliente, f_success ) {
				
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : { 
                command : 'atencion_cliente', 
                action : 'CantidadClientesAsignadosFiltros', 
                idservicio : xidservicio ,
                cartera : xidcartera,
                usuario_servicio : xusuario_servicio,
                monto : xordermonto,
                tramo : xtramo,
                departamento : xdepartamento,
                provincia : xprovincia,
                otros : xotros,
                idfinal : xidfinal,
                matriz_usuario : xmatriz_usuario,
                modo : xmodo,
                estado_pago : xestado_pago ,
                tabla : xtabla,
                campo : xcampo,
                dato : xdato,
                referencia : xreferencia,
                tipo_f_estado : xtipo_f_estado,
                semana_opcion : xsemana_opcion,
                filtro_con_sin_gestion:xfiltro_con_sin_gestion,
                depto : xdepto,
                provin:xprovin,
                distri:xdistri,
                rango_vcto:xrango_vcto,
                tipo_cliente:xtipo_cliente
            },
            beforeSend : function ( ) {
                $('#closeWindowCobrastOverlay').css('display','block');
                $('#closeWindowCobrastProgressBar').css('display','block');
            },
            success : function ( obj ) {
                $('#closeWindowCobrastOverlay').css('display','none');
                $('#closeWindowCobrastProgressBar').css('display','none');
                f_success(obj);
            },
            error : function ( ) {}
        });
				
    },
    cantidadDiasMora : function(xcartera,xusuario_servicio,xmodo){
        $.ajax({
            url:this.url,
            type:'GET',
            dataType:'json',
            data:{
                command:'atencion_cliente',
                action:'cantidadDiasMora',
                idusuario_servicio : xusuario_servicio,  
                modo:xmodo,              
                idcartera:xcartera
            },
            beforeSend:function(){

            },
            success:function(obj){
                var html='';
                html+='<option value="0">--Seleccione--</option>';
                $.each(obj,function(key,data){
                    html+='<option value="DiasMora_'+data.dias_mora+'">'+data.dias_mora+' Dias -> '+data.CANTIDAD+'</option>';
                })
                $('#cbFiltroDiasMora').html(html);
            },
            error:function(){}
        })
    },    
    cantidadTerritorio : function(xcartera,xusuario_servicio,xmodo){/*jmore200813*/
        $.ajax({
            url:this.url,
            type:'GET',
            dataType:'json',
            data:{
                command:'atencion_cliente',
                action:'cantidadTerritorio',
                idusuario_servicio : xusuario_servicio,  
                modo:xmodo,              
                idcartera:xcartera
            },
            beforeSend:function(){

            },
            success:function(obj){
                var html='';
                html+='<option value="0">--Seleccione--</option>';
                $.each(obj,function(key,data){
                    html+='<option value="Territorio_'+data.TERRITORIO+'">'+data.TERRITORIO+' -> '+data.CANTIDAD+'</option>';
                })
                $('#cbFiltroTerritorio').html(html);
            },
            error:function(){}
        })
    },        
    ListarHistoricoCuenta : function ( xidcliente, xcartera, f_success ) {
				
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command : 'atencion_cliente', 
                action : 'ListarHistoricoCuenta', 
                idcliente : xidcliente, 
                cartera : xcartera
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                f_success(obj);
            },
            error : function ( ) {
            }
        });
				
    },
    ListarEstadoPago : function ( xidcartera, f_success ) {
				
        $.ajax({
            url : this.url,
            async : false,
            type : 'GET',
            dataType : 'json',
            data : {
                command : 'atencion_cliente', 
                action : 'ListarEstadoPago', 
                idcartera : xidcartera
            },
            beforeSend : function ( ) {
            },
            success : function ( obj ) {
                f_success(obj);
            },
            error : function ( ) {}
        });
				
    },
    RankingTotalUsuarioPorDia : function ( xpor, xidusuario_servicio, xidservicio, xfecha_inicio, xfecha_fin, f_success, f_before ) {
				
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command : 'ranking_operador', 
                action : 'RankingTotalUsuarioPorDia', 
                por : xpor,
                idusuario_servicio : xidusuario_servicio, 
                idservicio : xidservicio, 
                fecha_inicio : xfecha_inicio, 
                fecha_fin : xfecha_fin
            },
            beforeSend : function ( ) {
                f_before();
            },
            success : function ( obj ) {
                f_success(obj);
            },
            error : function ( ) {}
        });
				
    },
    RankingTotalServicioPorDia : function ( xpor, xidservicio, xfecha_inicio, xfecha_fin, f_success, f_before ) {
				
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command : 'ranking_operador', 
                action : 'RankingServicioTotalUsuarioPorDia', 
                por : xpor,
                idservicio : xidservicio, 
                fecha_inicio : xfecha_inicio, 
                fecha_fin : xfecha_fin
            },
            beforeSend : function ( ) {
                f_before();
            },
            success : function ( obj ) {
                f_success(obj);
            },
            error : function ( ) {}
        });
				
    },
    MetaClienteCuentaUsuarioServicio : function ( xidservicio, xidcartera, xidusuario_servicio, f_success, f_before ) { 
				
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            data : {
                command : 'ranking_operador', 
                action : 'MetaClienteCuentaUsuarioServicio', 
                idservicio : xidservicio, 
                idcartera : xidcartera, 
                idusuario_servicio : xidusuario_servicio
            },
            beforeSend : function ( ) {
                f_before();
            },
            success : function ( obj ) {
                f_success(obj);
            },
            error : function ( ) {}
        });
				
    } ,
    listarAlertaTelefono : function(){
        $.ajax({
            url:this.url,
            type:'GET',
            dataType:'json',
            data:{
                command:'atencion_cliente',
                action:'listarAlertaTelefono',
                idcartera:$('#IdCartera').val(),
                codigo_cliente:$('#CodigoClienteMain').val()
            },
            beforeSend:function(){},
            success:function(obj){
                var html='';
                html+='<option value="0">--Seleccione--</option>';
                $.each(obj,function(key,data){
                    html+='<option value="'+data.numero+'">'+data.numero+'</option>';
                });
                $('#cboNumeroAlertaTelefono').html(html);
            },
            error:function(){}
        })
    },
    ListarGestorCampo : function ( xidservicio, f_success ) {
				
        $.ajax({
            url : this.url,
            type : 'GET',
            async : false,
            dataType : 'json',
            data : {
                command : 'atencion_cliente', 
                action : 'ListarGestorCampo', 
                idservicio : xidservicio
            },
            beforeSend : function ( ) {
            //f_before();
            },
            success : function ( obj ) {
                f_success(obj);
            },
            error : function ( ) {}
        });
				
    },
	ListarCarteraEvento : function ( f_success, f_before ) {
		
		$.ajax({
            url : this.url,
            type : 'GET',
            async : false,
            dataType : 'json',
            data : {
                command : 'atencion_cliente', 
                action : 'ListarCarteraEvento', 
                idservicio : $('#hdCodServicio').val()
            },
            beforeSend : function ( ) {
				f_before();
            },
            success : function ( obj ) {
                f_success(obj);
            },
            error : function ( ) {}
        });
	
	},
	ListarCarteraCluster : function ( f_success, f_before ) {
		
		$.ajax({
            url : this.url,
            type : 'GET',
            async : false,
            dataType : 'json',
            data : {
                command : 'atencion_cliente', 
                action : 'ListarCarteraCluster', 
                idservicio : $('#hdCodServicio').val()
            },
            beforeSend : function ( ) {
				f_before();
            },
            success : function ( obj ) {
                f_success(obj);
            },
            error : function ( ) {}
        });
		
	},
	ListarCarteraSegmento : function( f_success, f_before ) {
		
		$.ajax({
            url : this.url,
            type : 'GET',
            async : false,
            dataType : 'json',
            data : {
                command : 'atencion_cliente', 
                action : 'ListarCarteraSegmento', 
                idservicio : $('#hdCodServicio').val()
            },
            beforeSend : function ( ) {
				f_before();
            },
            success : function ( obj ) {
                f_success(obj);
            },
            error : function ( ) {}
        });
		
	},
    ListarParentesco : function ( f_success ) {

        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            async : false,
            data : {
                command : 'atencion_cliente', 
                action : 'ListarParentesco', 
                idservicio : $('#hdCodServicio').val()
            },
            beforeSend : function ( ) {},
            success : function ( obj ) {
                f_success(obj);
            },
            error : function ( ) {}
        });

    },
    GrabarCuotificacion : function ( xcuentas ) {
        $.ajax({
            url : this.url,
            type : 'POST',
            dataType : 'json',
            async : false,
            data : {
                command : 'atencion_cliente', 
                action : 'GrabarCuotificacion', 
                idservicio : $('#hdCodServicio').val(),
                idusuario_servicio : $('#hdCodServicio').val(),
                idcliente_cartera : $('#IdClienteCarteraMain').val(),
                idtelefono : $('#HdIdTelefono').val(),
                cuentas : xcuentas,
                estado : $('#cbEstadoCuotificacion').val(),
                objecion : $('#cbObjecionCuotificacion').val(),
                tipo : $('#cbTipoCuotificacion').val(),
                deuda : $('#txtDeudaCuotificacion').val(),
                numero_cuotas : $('#txtNCuotaCuotificacion').val(),
                monto_cuota : $('#txtMontoCuotaCuotificacion').val(),
                observacion : $('#txtObservacionCuotificacion').val(),
                usuario_creacion : $('#hdCodUsuario').val()
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Guardando Refinanciamiento ...',300);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                if( obj.rst ) {
                
                    $('#txtNCuotaCuotificacion,#txtMontoCuotaCuotificacion,#cbEstadoCuotificacion').val('0');
                    $('#txtObservacionCuotificacion,#cbObjecionCuotificacion').val('');
                
                }else{
                    
                }
                
                _displayBeforeSendDl(obj.msg,450);
            },
            error : function ( ) {
                _noneBeforeSend();
            }
        });
    },
    ListarCuotificacion : function ( xfecha_inicio, xfecha_fin, f_success ) {
        
        $.ajax({
            url : this.url,
            type : 'GET',
            dataType : 'json',
            async : false,
            data : {
                command : 'atencion_cliente', 
                action : 'ListarCuotificacion', 
                idservicio : $('#hdCodServicio').val(), 
                fecha_inicio : xfecha_inicio,
                fecha_fin : xfecha_fin,
                idcliente_cartera : $('#IdClienteCarteraMain').val()
            },
            beforeSend : function ( ) {
                _displayBeforeSend('Listando Refinanciamiento ...',400);
            },
            success : function ( obj ) {
                _noneBeforeSend();
                f_success( obj );
            },
            error : function ( ) {
                _noneBeforeSend();
            }
        });
        
    },/*jmore050712*/
		Refinanciamiento : {
			Listar : function ( xidcliente_cartera, f_success ) {
				
				$.ajax({
						url : AtencionClienteDAO.url,
						type : 'GET',
						dataType : 'json',
						data : {
								command : 'atencion_cliente',
								action : 'ListarRefinanciamiento',
								idcliente_cartera : xidcliente_cartera
								
							},
						success : function ( obj ) {
							f_success(obj);
						},
						error : function ( ) {}
				});

			},
			Grabar : function ( xidcuenta, xnumero_cuenta, xmoneda, xdeuda, xdescuento, xn_cuotas, xtipo_monto, xmonto_pago, xobservacion, f_success ) {
				
				$.ajax({
						url : AtencionClienteDAO.url,
						type : 'POST',
						dataType : 'json',
						data : {
								command : 'atencion_cliente',
								action : 'GrabarRefinanciamiento',
								idcliente_cartera : $('#IdClienteCartera').val() ,
								idcliente : $('#idClienteMain').val() ,
								idservicio : $('#hdCodServicio').val() ,
								usuario_creacion : $('#hdCodUsuario').val() ,
								idusuario_servicio : $('#hdCodUsuarioServicio').val() ,
								idcuenta : xidcuenta,
								numero_cuenta : xnumero_cuenta,
								moneda : xmoneda,
								deuda : xdeuda ,
								descuento : xdescuento ,
								n_cuotas : xn_cuotas ,
								tipo_monto : xtipo_monto ,
								monto_pago : xmonto_pago ,
								observacion : xobservacion 
							},
						success : function ( obj ) {
							f_success( obj );
						},
						error : function ( ) {
							AtencionClienteDAO.error_ajax();
						}
				});

			}
		},    /*jmore050712*/
	Ref : {
		Grabar : function ( xded, xdesc, xint, xcoms, xmor, xgascob, xncuot, xtippag, xfechpripag, xobs, f_before, f_success, f_error ) {
			
			$.ajax({
					url : AtencionClienteDAO.url,
					type : 'POST',
					dataType : 'json',
					data : {
							command : 'atencion_cliente',
							action : 'GuardarRef',
							idcliente_cartera : $('#IdClienteCarteraMain').val(),
							idcartera : $('#IdCartera').val(),
							codigo_cliente : $('#CodigoClienteMain').val(),
							deuda : xded,
							descuento : xdesc,
							interes : xint,
							comision : xcoms,
							mora : xmor,
							gastos_cobranza : xgascob,
							n_cuotas : xncuot,
							tipo_pago : xtippag,
							fecha_primer_pago : xfechpripag,
							observacion : xobs,
							usuario_creacion : $('#hdCodUsuario').val(),
							servicio : $('#hdCodServicio').val()
						},
					beforeSend : function ( ) {
						_displayBeforeSend('Grabando Refinanciamiento ...',400);
						f_before();
					},
					success : function ( obj ) {
						_noneBeforeSend();
						f_success(obj);
					},
					error : function ( ) {
						_noneBeforeSend();
						AtencionClienteDAO.error_ajax();
						f_error();
					}
			});
			
		},
		Pago : {
			Grabar : function ( xmon, xmone, xobs, f_before, f_success, f_error ) {
				
				$.ajax({
						url : AtencionClienteDAO.url,
						type : 'POST',
						dataType : 'json',
						data : {
								command : 'atencion_cliente',
								action : 'GuardarPagoRef',
								idcliente_cartera : $('#IdClienteCarteraMain').val(),
								idcartera : $('#IdCartera').val(),
								codigo_cliente : $('#CodigoClienteMain').val(),
								monto : xmon,
								moneda : xmone,
								observacion : xobs,
								usuario_creacion : $('#hdCodUsuario').val(),
								servicio : $('#hdCodServicio').val()
								
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
				});
				
			},
			Listar : function ( f_before, f_success, f_error ) {
				
				$.ajax({
						url : AtencionClienteDAO.url,
						type : 'POST',
						dataType : 'json',
						data : {
								command : 'atencion_cliente',
								action : 'ListarPagoRef',
								idcartera : $('#IdCartera').val(),
								idcliente_cartera : $('#IdClienteCarteraMain').val() ,
								servicio : $('#hdCodServicio').val()
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
					});
				
			}
		}
	},
    hide_message : function ( )  {
        $('#'+AtencionClienteDAO.idLayerMessage).effect('blind',{
            direction:'vertical'
        },'slow',function(){
            $(this).empty().css('display','block');
        });
    },
    setTimeOut_hide_message : function ( ) {
        setTimeout("AtencionClienteDAO.hide_message()",2000);
    },
    error_ajax : function ( ) {
        _noneBeforeSend();
        _displayBeforeSendDl('Error en ejecucion de proceso',450);
    },
    actualizarVisitaGestionComercial: function(){
        $.ajax({
            url:AtencionClienteDAO.url,
        type:'GET',
            datatype: 'json',
            data:{
                command : 'atencion_cliente',
                action : 'actualizarVisitaGestionComercial',


            }
        })
    },
    searchClienteCartera : function(){//piro
        $.ajax({
            url: AtencionClienteDAO.url,
            type: 'GET',
            dataType: 'json',
            data:{
                command : 'atencion_cliente',
                action : 'searchClienteCartera',
                codigoCliente : $('#txtCampoCodigoSearch2').val(),
                idcartera: $('#cboCartera').val()
            },
            success : function (obj) {

                $('#idcliente_cartera').val(obj[0].idcliente_cartera);
            }

        });
    },
    FillIdCuentaByCode : function(){//piro
        $.ajax({
            url: AtencionClienteDAO.url,
            type: 'GET',
            dataType: 'json',
            data:{
                command : 'atencion_cliente',
                action : 'FillIdCuentaByCode',
                codigoCliente : $('#txtCampoCodigoSearch2').val(),
                idcartera: $('#cboCartera').val()
            },
            success : function (obj) {
                $('#idcuenta').val(obj[0].idcuenta);
            }

        });
    },
    save_visita_comercial : function (GiroNegocio,detaGiroExtraNegocio,AfrontarPago,detaAfronPago,menorigual10pers,mayor10pers,tipVisita,numVisita) { // piro
        $.ajax({
            url : this.url,
            type : 'POST',
            dataType : 'json',
            data : {
                command : 'atencion_cliente',
                action : 'save_visita_comercial',
                idClienteCartera : $('#idcliente_cartera').val(),
                idDireccion: $('#cboDomicilio').val(),
                idNotificador: $('#hdCodUsuarioServicio').val(),
                idCuenta: $('#idcuenta').val(),
                idUsuarioServicio: $('#hdCodUsuarioServicio').val(),
                fechaCompromisoPago: $('#txtFechaCompromisoPago').val(),
                fechaVisita: $('#txtFechaVisita').val(),
                horaVisita: $('#txtCampoHora').val()+':00',
                idGiroNegocio: GiroNegocio,
                detalleGiroExtraNegocio: detaGiroExtraNegocio,
                idAfrontarPago: AfrontarPago,
                detalleAfronPago: detaAfronPago,
                idMotivoAtraso: $('input:radio[name=chkMotivoAtraso]:checked').val(),
                detalleMotAtr: $('#txtSustentoMotivAtraso').val(),
                idCuestionaCobranza: $('input:radio[name=chkCuestionaCobranza]:checked').val(),
                idObservacionEspecialista : $('input:radio[name=chkObservacionEspecialista]:checked').val(),
                caracteristicaNegocioEnActividad : $('input:radio[name=chkCaracNegoEnActividad]:checked').val(),
                caracteristicaNegocioTieneExistencias: $('input:radio[name=chkCaracNegoTieneExistencias]:checked').val(),
                caracteristicaNegocioLaborArtesanal: $('input:radio[name=chkCaracNegoLaborArtesanal]:checked').val(),
                caracteristicaNegocioLocalPropio: $('input:radio[name=chkCaracNegoLocalPropio]:checked').val(),
                caracteristicaNegocioOficinaAdministrativa: $('input:radio[name=chkCaracNegoOfiAdmi]:checked').val(),
                menorigual10personas : menorigual10pers,
                mayor10personas : mayor10pers,
                caracteristicaNegocioPlantaIndustrial: $('input:radio[name=chkCaracNegoPlantaIndustrial]:checked').val(),
                caracteristicaNegocioCasaNegocio: $('input:radio[name=chkCaracNegoCasaNego]:checked').val(),
                caracteristicaNegocioPuertaCalle: $('input:radio[name=chkCaracNegoPueCalle]:checked').val(),
                caracteristicaNegocioActividadAdicional : $('#txtOtroCaracNego').val(),
                tipoVisita: tipVisita,
                numeroVisita: numVisita,
                nuevaDireccion : $('#txtNuevoDomiActualizacionBaseDatos').val(),
                nuevoTelefono : $('#txtNuevosTelefonosActualizacionBaseDatos').val(),
                direccionVisita2 : $('#txtDireccVisi2iActualizacionBaseDatos').val()
                
               
            },
            beforeSend : function ( ) {
               _displayBeforeSend('Guardando Visita Comercial...',600);
               
            },  
            success : function ( obj ) {
                 _noneBeforeSend();
                 //limpieza form
                $('input:radio').removeAttr('checked');
                $('input:text').val('');
                $('textarea').val('');
                $('#cboDomicilio').val('');
               
				
                
            },
            error : function ( ) {
                 _noneBeforeSend();
                AtencionClienteDAO.error_ajax();
            }
        });
    },
    aval_telefono : function(codigo_cliente){
        $.ajax({
            url: AtencionClienteDAO.url,
            type: 'POST',
            dataType: 'json',
            data:{
                command : 'atencion_cliente',
                action : 'Listar_Telefono_Aval',
                codigo_cliente : codigo_cliente,
                idcartera: $('#cboCartera').val()
            },
            success : function (obj) {
                if (obj.rst) {
                    var html="";
                    html+="<table id='aval_telf_det'>"
                    html+="<tr>"
                    html+="<td>Numero</td>"
                    html+="</tr>"
                    html+="<tr>"
                    for(i=0;i<=obj.telefono.length-1;i++){
                        html+="<td><span onclick='selecte_telf_aval("+obj.telefono[i]['numero']+","+obj.telefono[i]['idtelefono']+")' >"+obj.telefono[i]['numero']+"</span></td>";
                    }
                    html+="</tr>"
                    html+="</table>"
                    $('#table_aval_telf').html(html);
                    // alert(html);
                    $('#table_aval_telf').css({'display':'block'});
                    $('#table_aval_telf').dialog('open');
                }
            }

        });
    },
    aval_direccion : function(codigo_cliente){
        $.ajax({
            url: AtencionClienteDAO.url,
            type: 'POST',
            dataType: 'json',
            data:{
                command : 'atencion_cliente',
                action : 'Listar_Direccion_Aval',
                codigo_cliente : codigo_cliente,
                idcartera: $('#cboCartera').val()
            },
            success : function (obj) {
                if (obj.rst) {
                    var html="";
                    html+="<table cellspacing=0>"
                    html+="<tr class='ui-state-default'>"
                    html+="<td class='ui-state-default' style='text-align:center;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;'>Direccion</td>"
                    html+="<td style='text-align:center;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;'>Urbanización</td>"
                    html+="<td style='text-align:center;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;'>Departamento</td>"
                    html+="<td style='text-align:center;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;'>Provincia</td>"
                    html+="<td style='text-align:center;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;'>Distrito</td>"
                    html+="</tr>"
                    html+="<tr>"
                    for(i=0;i<=obj.direccion.length-1;i++){
                        html+="<td style=\"padding:0 3px 0 3px;pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;border-left:1px solid #E0CFC2;\">"+obj.direccion[i]['direccion']+"</td>";
                        html+="<td style=\"padding:0 3px 0 3px;pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;\">"+obj.direccion[i]['referencia']+"</td>";
                        html+="<td style=\"padding:0 3px 0 3px;pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;\">"+obj.direccion[i]['departamento']+"</td>";
                        html+="<td style=\"padding:0 3px 0 3px;pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;\">"+obj.direccion[i]['provincia']+"</td>";
                        html+="<td style=\"padding:0 3px 0 3px;pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;\">"+obj.direccion[i]['distrito']+"</td>";
                    }
                    html+="</tr>"
                    html+="</table>"
                    //$('#table_aval_direccion').html(html);
                    $('#table_aval_direccion_campo').html(html);
                    //$('#table_aval_direccion').css({'display':'block'});
                    $('#table_aval_direccion_campo').css({'display':'block'});
                }
            }

        });
    },
    aval_direccion_campo : function(codigo_cliente){
        $.ajax({
            url: AtencionClienteDAO.url,
            type: 'POST',
            dataType: 'json',
            data:{
                command : 'atencion_cliente',
                action : 'Listar_Direccion_Aval',
                codigo_cliente : codigo_cliente,
                idcartera: $('#cboCartera').val()
            },
            success : function (obj) {
                if (obj.rst) {
                    var html="";
                    html+="<table cellspacing=0>"
                    html+="<tr class='ui-state-default'>"
                    html+="<td class='ui-state-default' style='text-align:center;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;'>Direccion</td>"
                    html+="<td style='text-align:center;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;'>Urbanización</td>"
                    html+="<td style='text-align:center;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;'>Departamento</td>"
                    html+="<td style='text-align:center;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;'>Provincia</td>"
                    html+="<td style='text-align:center;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;'>Distrito</td>"
                    html+="<td style='text-align:center;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;'>CHK</td>"
                    html+="<td style='text-align:center;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;'>Dir.</td>"
                    html+="</tr>"
                    html+="<tr>"
                    for(i=0;i<=obj.direccion.length-1;i++){
                        html+="<td style=\"padding:0 3px 0 3px;pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;border-left:1px solid #E0CFC2;\">"+obj.direccion[i]['direccion']+"</td>";
                        html+="<td style=\"padding:0 3px 0 3px;pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;\">"+obj.direccion[i]['referencia']+"</td>";
                        html+="<td style=\"padding:0 3px 0 3px;pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;\">"+obj.direccion[i]['departamento']+"</td>";
                        html+="<td style=\"padding:0 3px 0 3px;pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;\">"+obj.direccion[i]['provincia']+"</td>";
                        html+="<td style=\"padding:0 3px 0 3px;pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;\">"+obj.direccion[i]['distrito']+"</td>";
                        html+="<td style=\"padding:0 3px 0 3px;pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;\"><input type='radio' name='direccion_campo' value='"+obj.direccion[i]['iddireccion']+"'></td>";
                        html+="<td style=\"padding:0 3px 0 3px;pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;\"><img onclick='mostrar_direct_aval("+obj.direccion[i]['codigo_cliente']+");' style='width:16px;cursor:pointer;' src='../img/location_fav.png' class='imgtelf'></td>";
                    }
                    html+="</tr>"
                    html+="</table>"
                    $('#table_aval_direccion_campo').html(html);
                    $('#table_aval_direccion_campo').css({'display':'block'});
                }
            }

        });
    },
    ListarSemana_opcion : function(){
        $.ajax({
            url: AtencionClienteDAO.url,
            type: 'GET',
            dataType: 'json',
            data:{
                command : 'atencion_cliente',
                action : 'ListarSemana_opcion',
                idcartera: 1
            },
            success : function (obj) {
                if (obj.rst) {
                    $("#semana_opcion option").remove();
                    $("#semana_opcion").append('<option value="0">--Seleccione--</option>');
                    for (var i = 0; i <= obj.semana.length-1; i++) {
                    
                        //alert(obj.semana[i]['semana']);

                        $("#semana_opcion").append('<option>'+obj.semana[i]['semana']+'</option>');

                    }
                }
            }

        });
    },
    Listar_Representantes : function(xcodigo_cliente){

        $.ajax({
            url: AtencionClienteDAO.url,
            type: 'GET',
            dataType: 'json',
            data:{
                command : 'atencion_cliente',
                action : 'Listar_Representantes',
                codigo_cliente: xcodigo_cliente
            },
            success : function (obj) {
                if (obj.rst) {
                    var html_representante='';
                    html_representante+='<tr class="ui-state-default">';
                    html_representante+='<td align="center" style="width:30px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;">&nbsp;</td>';
                    html_representante+='<td align="center" style="width:80px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;">Contrato</td>';
                    html_representante+='<td align="center" style="width:80px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;">Doi</td>';
                    html_representante+='<td align="center" style="width:220px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;">Avalista</td>';
                    html_representante+='<td align="center" style="width:70px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;">Tipo Persona</td>';        
                    html_representante+='<td align="center" style="width:70px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;">Telefono</td>';
                    html_representante+='<td align="center" style="width:70px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;">Dirección</td>';
                    html_representante+='<td align="center" style="width:30px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;display:none;">Editar</td>';        
                    html_representante+='<td align="center" style="width:30px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;display:none;">Nuevo</td>';                
                    html_representante+='<td align="center" style="width:30px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;display:none;">Eliminar</td>';                        
                    html_representante+='</tr>'; 
                    var cont=0;
                    for(i=0;i<obj.representante.length;i++){
                            html_representante+='<tr class="ui-widget-content">';
                            html_representante+='<td align="center" class="ui-state-default" style="border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;">'+(cont+1)+'</td>';
                            html_representante+='<td align="center" style="pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;">'+obj.representante[i].contrato+'</td>';
                            html_representante+='<td align="center" style="pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;">'+obj.representante[i].doi+'</td>';
                            html_representante+='<td align="center" style="pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;">'+obj.representante[i].datos+'</td>';
                            html_representante+='<td align="center" style="pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;">'+obj.representante[i].tipo_persona+'</td>';                
                            html_representante+='<td align="center" style="pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;"><img class="imgtelf" src="../img/telephone_blue-128.png" style="width:16px;cursor:pointer;" onclick="mostrar_telf_aval(\''+obj.representante[i].doi+'\');"></td>';
                            html_representante+='<td align="center" style="pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;"><img class="imgtelf" src="../img/74_location.png" style="width:16px;cursor:pointer;" onclick="mostrar_direct_aval_campo(\''+obj.representante[i].doi+'\');"></td>';
                            html_representante+='<td align="center" style="pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;display:none;"><span class="ui-icon-pencil" onclick="editar_representante_legal(this,'+obj.representante[i].idrepresentante_legal+')">&nbsp&nbsp</span></td>';
                            html_representante+='<td align="center" style="pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;display:none;"><span class="ui-icon-new" onclick="new_representante_legal(this)">&nbsp&nbsp</span></td>';                
                            html_representante+='<td align="center" style="pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;display:none;"><span class="ui-icon-clear2" onclick="delete_representante_legal(this,'+obj.representante[i].idrepresentante_legal+')">&nbsp&nbsp</span></td>';                                
                            html_representante+='</tr>';                
                            cont++;            
                    }
                    $('#Representante_aval_campo').html(html_representante);

                    $("#input[name=direccion_campo]:radio").live( "change", function() {
                        $('#cbCampoDireccionVisita').val(0);
                    });
                }
            }

        });
    },
    //MANTTELF
    Listtipotelf:function(nombre_tip_telf){
        $.ajax({
            url:this.url,
            type:'POST',
            dataType:'json',
            data:{
                command:'atencion_cliente',
                action:'Listtipotelf',
            },
            success:function(obj){
                if( obj.rst ){
                    var html = '';
                    html+="<option value=''>.:Selecciones:.</option>";
                    for(var i=0;i<obj.datos.fila.length;i++){
                        
                        html+="<option value='"+obj.datos.fila[i]['cell'][0]+"'>"+obj.datos.fila[i]['cell'][1]+"</option>";
                    }

                    var elemt="tipo";

                    $("#"+elemt).html(html);

                    $("#"+elemt).multiselect({
                        noneSelectedText: '.:Seleccione:.',
                        //height: 150,
                        header: false,
                        multiple:false,
                        open: function(event, ui)
                        {
                            this.click(
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').parent().parent().parent().css('width', '200px'),
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').removeClass( "ui-state-active" ),
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').removeAttr('checked')
                            );
                        },
                        click: function(event, ui)
                        {
                            /*var idresponsable = $("#"+elemt).multiselect("getChecked").map(function(){
                               return this.value;    
                            }).get();*/
                            /*var nombre_responsable =$('#tipo').multiselect().find('option[value="'+idresponsable+'"]').text();
                            $('#idresponsable').val(idresponsable);*/

                            /*var idresponsable = $("#"+elemt).multiselect("getChecked").map(function(){
                               return this.value;    
                            }).get();*/

                        },
                        selectedText: function(numChecked, numTotal, checkedItems) {
                            var tipo = $("#"+elemt).multiselect("getChecked").map(function(){return this.value;}).get();
                            var nombre_tipo =$("#"+elemt).multiselect().find('option[value="'+tipo+'"]').text()
                            return nombre_tipo;
                        }
                    }).multiselectfilter();
                    //$("#tipo").multiselect("uncheckAll");

                    if(nombre_tip_telf!=""){                        
                        $("#"+elemt).multiselect().find('option[text="'+nombre_tip_telf+'"]').attr("selected","selected");
                        $("#"+elemt).multiselect('refresh');
                    }else{
                        $("#"+elemt).multiselect("uncheckAll");                        
                    }

                    $('#tr_'+elemt+' button').width(160);

                }else{
                }
            },
            complete:function(){
            },
            error:function(){
            } 
        });
    },
    Listreferenciatelf:function(nombre_ref_telf){
        $.ajax({
            url:this.url,
            type:'POST',
            dataType:'json',
            data:{
                command:'atencion_cliente',
                action:'Listreferenciatelf',
            },
            success:function(obj){
                if( obj.rst ){
                    var html = '';
                    html+="<option value=''>.:Selecciones:.</option>";
                    for(var i=0;i<obj.datos.fila.length;i++){
                        
                        html+="<option value='"+obj.datos.fila[i]['cell'][0]+"'>"+obj.datos.fila[i]['cell'][1]+"</option>";
                    }

                    var elemt="referencia";

                    $("#"+elemt).html(html);

                    $("#"+elemt).multiselect({
                        noneSelectedText: '.:Seleccione:.',
                        height: 150,
                        header: false,
                        multiple:false,
                        open: function(event, ui)
                        {
                            this.click(
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').parent().parent().parent().css('width', '200px'),
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').removeClass( "ui-state-active" ),
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').removeAttr('checked')
                            );
                        },
                        click: function(event, ui)
                        {
                            /*var idresponsable = $("#tipo").multiselect("getChecked").map(function(){
                               return this.value;    
                            }).get();
                            var nombre_responsable =$('#tipo').multiselect().find('option[value="'+idresponsable+'"]').text();
                            $('#idresponsable').val(idresponsable);*/

                            /*$("#"+elemt).multiselect("getChecked").map(function(){
                               return this.value;    
                            }).get();*/



                        },
                        selectedText: function(numChecked, numTotal, checkedItems) {
                            var referencia = $("#"+elemt).multiselect("getChecked").map(function(){return this.value;}).get();
                            var nombre_referencia =$("#"+elemt).multiselect().find('option[value="'+referencia+'"]').text()
                            return nombre_referencia;
                        }
                    }).multiselectfilter();
                    //$("#tipo").multiselect("uncheckAll");

                    if(nombre_ref_telf!=""){                        
                        $("#"+elemt).multiselect().find('option[text="'+nombre_ref_telf+'"]').attr("selected","selected");
                        $("#"+elemt).multiselect('refresh');
                    }else{
                        $("#"+elemt).multiselect("uncheckAll");                        
                    }

                    $('#tr_'+elemt+' button').width(160);

                }else{                           
                }
            },
            complete:function(){
            },
            error:function(){
            } 
        });
    },
    Listlineatelf:function(nombre_lin_telf){
        $.ajax({
            url:this.url,
            type:'POST',
            dataType:'json',
            data:{
                command:'atencion_cliente',
                action:'Listlineatelf',
            },
            success:function(obj){
                if( obj.rst ){
                    var html = '';
                    html+="<option value=''>.:Selecciones:.</option>";
                    for(var i=0;i<obj.datos.fila.length;i++){
                        
                        html+="<option value='"+obj.datos.fila[i]['cell'][0]+"'>"+obj.datos.fila[i]['cell'][1]+"</option>";
                    }

                    var elemt="prefijos";

                    $("#"+elemt).html(html);

                    $("#"+elemt).multiselect({
                        noneSelectedText: '.:Seleccione:.',
                        height: 150,
                        header: false,
                        multiple:false,
                        open: function(event, ui)
                        {
                            this.click(
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').parent().parent().parent().css('width', '200px'),
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').removeClass( "ui-state-active" ),
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').removeAttr('checked')
                            );
                        },
                        click: function(event, ui)
                        {
                            /*var idresponsable = $("#tipo").multiselect("getChecked").map(function(){
                               return this.value;    
                            }).get();
                            var nombre_responsable =$('#tipo').multiselect().find('option[value="'+idresponsable+'"]').text();
                            $('#idresponsable').val(idresponsable);*/

                            /*$("#"+elemt).multiselect("getChecked").map(function(){
                               return this.value;    
                            }).get();*/

                        },
                        selectedText: function(numChecked, numTotal, checkedItems) {
                            var prefijos = $("#"+elemt).multiselect("getChecked").map(function(){return this.value;}).get();
                            var nombre_prefijos =$("#"+elemt).multiselect().find('option[value="'+prefijos+'"]').text()
                            return nombre_prefijos;
                        }
                    }).multiselectfilter();
                    //$("#tipo").multiselect("uncheckAll");

                    if(nombre_lin_telf!=""){                        
                        $("#"+elemt).multiselect().find('option[text="'+nombre_lin_telf+'"]').attr("selected","selected");
                        $("#"+elemt).multiselect('refresh');
                    }else{
                        $("#"+elemt).multiselect("uncheckAll");                        
                    }

                    $('#tr_'+elemt+' button').width(160);

                }else{                           
                }
            },
            complete:function(){
            },
            error:function(){
            } 
        });
    },
    Listorigentelf:function(nombre_ori_telf){
        $.ajax({
            url:this.url,
            type:'POST',
            dataType:'json',
            data:{
                command:'atencion_cliente',
                action:'Listorigentelf',
            },
            success:function(obj){
                if( obj.rst ){
                    var html = '';
                    html+="<option value=''>.:Selecciones:.</option>";
                    for(var i=0;i<obj.datos.fila.length;i++){
                        
                        html+="<option value='"+obj.datos.fila[i]['cell'][0]+"'>"+obj.datos.fila[i]['cell'][1]+"</option>";
                    }

                    var elemt="origen";

                    $("#"+elemt).html(html);

                    $("#"+elemt).multiselect({
                        noneSelectedText: '.:Seleccione:.',
                        height: 150,
                        header: false,
                        multiple:false,
                        open: function(event, ui)
                        {
                            this.click(
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').parent().parent().parent().css('width', '200px'),
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').removeClass( "ui-state-active" ),
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').removeAttr('checked')
                            );
                        },
                        click: function(event, ui)
                        {
                            /*var idresponsable = $("#tipo").multiselect("getChecked").map(function(){
                               return this.value;    
                            }).get();
                            var nombre_responsable =$('#tipo').multiselect().find('option[value="'+idresponsable+'"]').text();
                            $('#idresponsable').val(idresponsable);*/

                            /*$("#"+elemt).multiselect("getChecked").map(function(){
                               return this.value;    
                            }).get();*/

                        },
                        selectedText: function(numChecked, numTotal, checkedItems) {
                            var origen = $("#"+elemt).multiselect("getChecked").map(function(){return this.value;}).get();
                            var nombre_prefijos =$("#"+elemt).multiselect().find('option[value="'+origen+'"]').text()
                            return nombre_prefijos;
                        }
                    }).multiselectfilter();
                    //$("#tipo").multiselect("uncheckAll");

                    if(nombre_ori_telf!=""){                        
                        $("#"+elemt).multiselect().find('option[text="'+nombre_ori_telf+'"]').attr("selected","selected");
                        $("#"+elemt).multiselect('refresh');
                    }else{
                        $("#"+elemt).multiselect("uncheckAll");                        
                    }

                    $('#tr_'+elemt+' button').width(160);

                }else{                           
                }
            },
            complete:function(){
            },
            error:function(){
            } 
        });
    },
    List_number_exist : function(number){
        $.ajax({
            url: AtencionClienteDAO.url,
            type: 'GET',
            dataType: 'json',
            data:{
                command : 'atencion_cliente',
                action : 'List_number_exist',
                numero :number
            },
            success : function (obj) {

                $('#number_if_exist').val(obj.exist);
            }

        });
    },
    //MANTTELF
    // CAMBIO 20-06-2016
    List_Departamento:function(xdpto){
        $.ajax({
            url:this.url,
            type:'POST',
            dataType:'json',
            data:{
                command:'atencion_cliente',
                action:'List_Departamento',
            },
            success:function(obj){
                if( obj.rst ){
                    var html = '';
                    html+="<option value=''>.:Selecciones:.</option>";
                    for(var i=0;i<obj.datos.fila.length;i++){
                        
                        html+="<option value='"+obj.datos.fila[i]['cell'][0]+"'>"+obj.datos.fila[i]['cell'][1]+"</option>";
                    }

                    var elemt="departamento";

                    $("#"+elemt).html(html);

                    $("#"+elemt).multiselect({
                        noneSelectedText: '.:Seleccione:.',
                        height: 150,
                        header: true,
                        multiple:false,
                        open: function(event, ui)
                        {
                            this.click(
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').parent().parent().parent().css('width', '200px'),
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').removeClass( "ui-state-active" ),
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').removeAttr('checked')
                            );
                        },
                        click: function(event, ui)
                        {
                            var xdepartamento = $("#"+elemt).multiselect("getChecked").map(function(){
                               return this.value;    
                            }).get();
                            /*var idresponsable = $("#tipo").multiselect("getChecked").map(function(){
                               return this.value;    
                            }).get();
                            var nombre_responsable =$('#tipo').multiselect().find('option[value="'+idresponsable+'"]').text();
                            $('#idresponsable').val(idresponsable);*/

                            /*$("#"+elemt).multiselect("getChecked").map(function(){
                               return this.value;    
                            }).get();*/
                            AtencionClienteDAO.List_Provincia(xdepartamento,'I');

                            $("#distrito option").remove();
                            $("#provincia option").remove();

                            AtencionClienteDAO.List_Provincia('','M');
                            AtencionClienteDAO.List_Distrito('','M');

                        },
                        selectedText: function(numChecked, numTotal, checkedItems) {
                            var origen = $("#"+elemt).multiselect("getChecked").map(function(){return this.value;}).get();
                            var nombre_prefijos =$("#"+elemt).multiselect().find('option[value="'+origen+'"]').text()
                            return nombre_prefijos;
                        }
                    }).multiselectfilter({
                        label: 'FILTRAR',
                        placeholder:'BUSCAR DPTO',
                        width:'140'
                    });
                    //$("#tipo").multiselect("uncheckAll");

                    if(xdpto!=""){                        
                        $("#"+elemt).multiselect().find('option[text="'+xdpto+'"]').attr("selected","selected");
                        $("#"+elemt).multiselect('refresh');
                    }else{
                        $("#"+elemt).multiselect("uncheckAll");                        
                    }

                    $('#tr_'+elemt+' button').width(160);

                }else{                           
                }
            },
            complete:function(){
            },
            error:function(){
            } 
        });
    },
    List_Distrito:function(xprov,xmod){
        $.ajax({
            url:this.url,
            type:'POST',
            dataType:'json',
            data:{
                command : 'atencion_cliente',
                action : 'List_Distrito',
                prov : xprov,
                xmod :  xmod
            },
            success:function(obj){
                if( obj.rst ){
                    var html = '';

                    var elemt="distrito";
                    $("#"+elemt+" option").remove();

                    html+="<option value=''>.:Selecciones:.</option>";
                    for(var i=0;i<obj.datos.fila.length;i++){                        
                        html+="<option value='"+obj.datos.fila[i]['cell'][0]+"'>"+obj.datos.fila[i]['cell'][1]+"</option>";
                    }
                    $("#"+elemt).html(html);
                    $("#"+elemt).multiselect('refresh');

                }else{
                    var elemt="distrito";

                    $("#"+elemt).html(html);

                    $("#"+elemt).multiselect({
                        noneSelectedText: '.:Seleccione:.',
                        height: 150,
                        header: true,
                        multiple:false,
                        open: function(event, ui)
                        {
                            this.click(
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').parent().parent().parent().css('width', '200px'),
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').removeClass( "ui-state-active" ),
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').removeAttr('checked')
                            );
                        },
                        click: function(event, ui){
                            // var xdistrito = $("#"+elemt).multiselect("getChecked").map(function(){
                            //    return this.value;    
                            // }).get();

                            // AtencionClienteDAO.List_Provincia(xdistrito);
                        },
                        selectedText: function(numChecked, numTotal, checkedItems) {
                            var origen = $("#"+elemt).multiselect("getChecked").map(function(){return this.value;}).get();
                            var nombre_prefijos =$("#"+elemt).multiselect().find('option[value="'+origen+'"]').text()
                            return nombre_prefijos;
                        }
                    }).multiselectfilter({
                        label: 'FILTRAR',
                        placeholder:'BUSCAR DISTRITO',
                        width:'140'
                    });
                }
            },
            complete:function(){
                var elemt="distrito";
                if($('#hddistrito_opcion').val()!=""){
                    $("#"+elemt).multiselect().find('option[text="'+$('#hddistrito_opcion').val()+'"]').attr("selected","selected");
                    $("#"+elemt).multiselect('refresh');
                }else{
                    $("#"+elemt).multiselect("uncheckAll");                        
                }

                $('#tr_'+elemt+' button').width(160);
            },
            error:function(){
            } 
        });
    },
    List_Provincia:function(xdepartamento,xmod){
        $.ajax({
            url:this.url,
            type:'POST',
            dataType:'json',
            data:{
                command:'atencion_cliente',
                action:'List_Provincia',
                dpto:xdepartamento,
                xmod :  xmod
            },
            success:function(obj){
                if( obj.rst ){
                    var html = '';                  

                    var elemt="provincia";
                    $("#"+elemt+" option").remove();

                    html+="<option value=''>.:Selecciones:.</option>";
                    for(var i=0;i<obj.datos.fila.length;i++){                        
                        html+="<option value='"+obj.datos.fila[i]['cell'][0]+"'>"+obj.datos.fila[i]['cell'][1]+"</option>";
                    }
                    $("#"+elemt).html(html);
                    $("#"+elemt).multiselect('refresh');

                }else{
                    var elemt="provincia";

                    $("#"+elemt).html(html);

                    $("#"+elemt).multiselect({
                        noneSelectedText: '.:Seleccione:.',
                        height: 150,
                        header: true,
                        multiple:false,
                        open: function(event, ui)
                        {
                            this.click(
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').parent().parent().parent().css('width', '200px'),
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').removeClass( "ui-state-active" ),
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').removeAttr('checked')
                            );
                        },
                        click: function(event, ui){
                            var xprov = $("#"+elemt).multiselect("getChecked").map(function(){
                               return this.value;    
                            }).get();
                            AtencionClienteDAO.List_Distrito(xprov,'I');
                        },
                        selectedText: function(numChecked, numTotal, checkedItems) {
                            var origen = $("#"+elemt).multiselect("getChecked").map(function(){return this.value;}).get();
                            var nombre_prefijos =$("#"+elemt).multiselect().find('option[value="'+origen+'"]').text()
                            return nombre_prefijos;
                        }
                    }).multiselectfilter({
                        label: 'FILTRAR',
                        placeholder:'BUSCAR PROVINCIA',
                        width:'140'
                    });
                    

                }
            },
            complete:function(){
                var elemt="provincia";
                if($('#hdprovincia_opcion').val()!=""){
                    $("#"+elemt).multiselect().find('option[text="'+$('#hdprovincia_opcion').val()+'"]').attr("selected","selected");
                    $("#"+elemt).multiselect('refresh');
                }else{
                    $("#"+elemt).multiselect("uncheckAll");                        
                }

                $('#tr_'+elemt+' button').width(160);

            },
            error:function(){
            } 
        });
    },
    ListreferenciaDir:function(nombre_ref_telf){
        $.ajax({
            url:this.url,
            type:'POST',
            dataType:'json',
            data:{
                command:'atencion_cliente',
                action:'Listreferenciatelf',
            },
            success:function(obj){
                if( obj.rst ){
                    var html = '';
                    html+="<option value=''>.:Selecciones:.</option>";
                    for(var i=0;i<obj.datos.fila.length;i++){
                        
                        html+="<option value='"+obj.datos.fila[i]['cell'][0]+"'>"+obj.datos.fila[i]['cell'][1]+"</option>";
                    }

                    var elemt="referencia_dir";

                    $("#"+elemt).html(html);

                    $("#"+elemt).multiselect({
                        noneSelectedText: '.:Seleccione:.',
                        height: 150,
                        header: false,
                        multiple:false,
                        open: function(event, ui)
                        {
                            this.click(
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').parent().parent().parent().css('width', '200px'),
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').removeClass( "ui-state-active" ),
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').removeAttr('checked')
                            );
                        },
                        click: function(event, ui)
                        {
                            /*var idresponsable = $("#tipo").multiselect("getChecked").map(function(){
                               return this.value;    
                            }).get();
                            var nombre_responsable =$('#tipo').multiselect().find('option[value="'+idresponsable+'"]').text();
                            $('#idresponsable').val(idresponsable);*/

                            /*$("#"+elemt).multiselect("getChecked").map(function(){
                               return this.value;    
                            }).get();*/

                            //alert("ASDASDA");


                        },
                        selectedText: function(numChecked, numTotal, checkedItems) {
                            var referencia = $("#"+elemt).multiselect("getChecked").map(function(){return this.value;}).get();
                            var nombre_referencia =$("#"+elemt).multiselect().find('option[value="'+referencia+'"]').text()
                            return nombre_referencia;
                        }
                    }).multiselectfilter();
                    //$("#tipo").multiselect("uncheckAll");

                    if(nombre_ref_telf!=""){                        
                        $("#"+elemt).multiselect().find('option[text="'+nombre_ref_telf+'"]').attr("selected","selected");
                        $("#"+elemt).multiselect('refresh');
                    }else{
                        $("#"+elemt).multiselect("uncheckAll");                        
                    }

                    $('#tr_'+elemt+' button').width(160);

                }else{                           
                }
            },
            complete:function(){
            },
            error:function(){
            } 
        });
    },
    ListorigenDir:function(nombre_ori_telf){
        $.ajax({
            url:this.url,
            type:'POST',
            dataType:'json',
            data:{
                command:'atencion_cliente',
                action:'Listorigentelf',
            },
            success:function(obj){
                if( obj.rst ){
                    var html = '';
                    html+="<option value=''>.:Selecciones:.</option>";
                    for(var i=0;i<obj.datos.fila.length;i++){
                        
                        html+="<option value='"+obj.datos.fila[i]['cell'][0]+"'>"+obj.datos.fila[i]['cell'][1]+"</option>";
                    }

                    var elemt="origen_dir";

                    $("#"+elemt).html(html);

                    $("#"+elemt).multiselect({
                        noneSelectedText: '.:Seleccione:.',
                        height: 150,
                        header: false,
                        multiple:false,
                        open: function(event, ui)
                        {
                            this.click(
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').parent().parent().parent().css('width', '200px'),
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').removeClass( "ui-state-active" ),
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').removeAttr('checked')
                            );
                        },
                        click: function(event, ui)
                        {
                            /*var idresponsable = $("#tipo").multiselect("getChecked").map(function(){
                               return this.value;    
                            }).get();
                            var nombre_responsable =$('#tipo').multiselect().find('option[value="'+idresponsable+'"]').text();
                            $('#idresponsable').val(idresponsable);*/

                            /*$("#"+elemt).multiselect("getChecked").map(function(){
                               return this.value;    
                            }).get();*/

                        },
                        selectedText: function(numChecked, numTotal, checkedItems) {
                            var origen = $("#"+elemt).multiselect("getChecked").map(function(){return this.value;}).get();
                            var nombre_prefijos =$("#"+elemt).multiselect().find('option[value="'+origen+'"]').text()
                            return nombre_prefijos;
                        }
                    }).multiselectfilter();
                    //$("#tipo").multiselect("uncheckAll");

                    if(nombre_ori_telf!=""){                        
                        $("#"+elemt).multiselect().find('option[text="'+nombre_ori_telf+'"]').attr("selected","selected");
                        $("#"+elemt).multiselect('refresh');
                    }else{
                        $("#"+elemt).multiselect("uncheckAll");                        
                    }

                    $('#tr_'+elemt+' button').width(160);

                }else{                           
                }
            },
            complete:function(){
            },
            error:function(){
            } 
        });
    },
    // CAMBIO 20-06-2016
    List_Departamento_filtro:function(){
        $.ajax({
            url:this.url,
            type:'POST',
            dataType:'json',
            data:{
                command:'atencion_cliente',
                action:'List_Departamento',
            },
            success:function(obj){
                if( obj.rst ){
                    var html = '';
                    html+="<option value=''>.:Selecciones:.</option>";
                    for(var i=0;i<obj.datos.fila.length;i++){
                        
                        html+="<option value='"+obj.datos.fila[i]['cell'][0]+"'>"+obj.datos.fila[i]['cell'][1]+"</option>";
                    }

                    var elemt="departamento_filtro";

                    $("#"+elemt).html(html);

                    $("#"+elemt).multiselect({
                        noneSelectedText: '.:Seleccione:.',
                        height: 150,
                        header: true,
                        multiple:false,
                        open: function(event, ui)
                        {
                            this.click(
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').parent().parent().parent().css('width', '200px'),
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').removeClass( "ui-state-active" ),
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').removeAttr('checked')
                            );
                        },
                        click: function(event, ui)
                        {
                            var xdepartamento = $("#"+elemt).multiselect("getChecked").map(function(){
                               return this.value;    
                            }).get();

                            AtencionClienteDAO.List_Provincia_filtro(xdepartamento,'I');
                            $("#provincia_filtro option").remove();
                            $("#distrito option").remove();
                            AtencionClienteDAO.List_Provincia_filtro('','M');
                            AtencionClienteDAO.List_Distrito_filtro('','M');
                            carga_cantidad_clientes_filtro();
                        },
                        selectedText: function(numChecked, numTotal, checkedItems) {
                            var origen = $("#"+elemt).multiselect("getChecked").map(function(){return this.value;}).get();
                            var nombre_prefijos =$("#"+elemt).multiselect().find('option[value="'+origen+'"]').text()
                            return nombre_prefijos;
                        }
                    }).multiselectfilter({
                        label: 'FILTRAR',
                        placeholder:'BUSCAR DPTO',
                        width:'140'
                    });

                }else{                           
                }
            },
            complete:function(){
            },
            error:function(){
            } 
        });
    },
    List_Provincia_filtro:function(xdepartamento,xmod){
        $.ajax({
            url:this.url,
            type:'POST',
            dataType:'json',
            data:{
                command:'atencion_cliente',
                action:'List_Provincia',
                dpto:xdepartamento,
                xmod :  xmod
            },
            success:function(obj){
                if( obj.rst ){
                    var html = '';                  

                    var elemt="provincia_filtro";
                    $("#"+elemt+" option").remove();

                    html+="<option value=''>.:SELECCCIONAR:.</option>";
                    for(var i=0;i<obj.datos.fila.length;i++){                        
                        html+="<option value='"+obj.datos.fila[i]['cell'][0]+"'>"+obj.datos.fila[i]['cell'][1]+"</option>";
                    }
                    $("#"+elemt).html(html);
                    $("#"+elemt).multiselect('refresh');

                }else{
                    var elemt="provincia_filtro";

                    $("#"+elemt).html(html);

                    $("#"+elemt).multiselect({
                        noneSelectedText: '.:Seleccione:.',
                        height: 150,
                        header: true,
                        multiple:false,
                        open: function(event, ui)
                        {
                            this.click(
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').parent().parent().parent().css('width', '200px'),
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').removeClass( "ui-state-active" ),
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').removeAttr('checked')
                            );
                        },
                        click: function(event, ui){
                            var xprov = $("#"+elemt).multiselect("getChecked").map(function(){
                               return this.value;    
                            }).get();
                            AtencionClienteDAO.List_Distrito_filtro(xprov,'I');
                            carga_cantidad_clientes_filtro();
                        },
                        selectedText: function(numChecked, numTotal, checkedItems) {
                            var origen = $("#"+elemt).multiselect("getChecked").map(function(){return this.value;}).get();
                            var nombre_prefijos =$("#"+elemt).multiselect().find('option[value="'+origen+'"]').text()
                            return nombre_prefijos;
                        }
                    }).multiselectfilter({
                        label: 'FILTRAR',
                        placeholder:'BUSCAR PROVINCIA',
                        width:'140'
                    });
                }
            },
            complete:function(){


            },
            error:function(){
            } 
        });
    },
    List_Distrito_filtro:function(xprov,xmod){
        $.ajax({
            url:this.url,
            type:'POST',
            dataType:'json',
            data:{
                command : 'atencion_cliente',
                action : 'List_Distrito',
                prov : xprov,
                xmod :  xmod
            },
            success:function(obj){
                if( obj.rst ){
                    var html = '';

                    var elemt="distrito_filtro";
                    $("#"+elemt+" option").remove();

                    html+="<option value=''>.:Selecciones:.</option>";
                    for(var i=0;i<obj.datos.fila.length;i++){                        
                        html+="<option value='"+obj.datos.fila[i]['cell'][0]+"'>"+obj.datos.fila[i]['cell'][1]+"</option>";
                    }
                    $("#"+elemt).html(html);
                    $("#"+elemt).multiselect('refresh');

                }else{
                    var elemt="distrito_filtro";

                    $("#"+elemt).html(html);

                    $("#"+elemt).multiselect({
                        noneSelectedText: '.:Seleccione:.',
                        height: 150,
                        header: true,
                        multiple:false,
                        open: function(event, ui)
                        {
                            this.click(
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').parent().parent().parent().css('width', '200px'),
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').removeClass( "ui-state-active" ),
                                $('label[for="ui-multiselect-'+elemt+'-option-0"]').removeAttr('checked')
                            );
                        },
                        click: function(event, ui){
                            // var xdistrito = $("#"+elemt).multiselect("getChecked").map(function(){
                            //    return this.value;    
                            // }).get();

                            // AtencionClienteDAO.List_Provincia(xdistrito);
                            carga_cantidad_clientes_filtro();
                        },
                        selectedText: function(numChecked, numTotal, checkedItems) {
                            var origen = $("#"+elemt).multiselect("getChecked").map(function(){return this.value;}).get();
                            var nombre_prefijos =$("#"+elemt).multiselect().find('option[value="'+origen+'"]').text()
                            return nombre_prefijos;
                        }
                    }).multiselectfilter({
                        label: 'FILTRAR',
                        placeholder:'BUSCAR DISTRITO',
                        width:'140'
                    });
                }
            },
            complete:function(){
            },
            error:function(){
            } 
        });
    },
    Listtipotelf_andina:function(nombre_tip_telf){
        $.ajax({
            url:this.url,
            type:'POST',
            dataType:'json',
            data:{
                command:'atencion_cliente',
                action:'Listtipotelf',
            },
            success:function(obj){
                if( obj.rst ){
                    var html = '';
                    html+="<option value=''>--Seleccione--</option>";
                    for(var i=0;i<obj.datos.fila.length;i++){
                        
                        html+="<option value='"+obj.datos.fila[i]['cell'][0]+"'>"+obj.datos.fila[i]['cell'][1]+"</option>";
                    }

                    $("#slctipo_cob_save").html(html);
                    $("#slctipo_cob_edit").html(html);

                }
            },
            complete:function(){

                var valor=$('#hdslctipo_cob_edit').val();
                $('#slctipo_cob_edit').val(valor);

            },
            error:function(){
            } 
        });
    },
    Listreferenciatelf_andina:function(nombre_ref_telf){
        $.ajax({
            url:this.url,
            type:'POST',
            dataType:'json',
            data:{
                command:'atencion_cliente',
                action:'Listreferenciatelf',
            },
            success:function(obj){
                if( obj.rst ){
                    var html = '';
                    html+="<option value=''>--Seleccione--</option>";
                    for(var i=0;i<obj.datos.fila.length;i++){
                        
                        html+="<option value='"+obj.datos.fila[i]['cell'][0]+"'>"+obj.datos.fila[i]['cell'][1]+"</option>";
                    }

                    $("#slcreferencia_cob_save").html(html);
                    $("#slcreferencia_cob_edit").html(html);
                    $("#slcref_dir_cob_save").html(html);
                    $("#slcref_dir_cob_edit").html(html);

                }
            },
            complete:function(){
                var valor=$('#hdslcreferencia_cob_edit').val();
                $('#slcreferencia_cob_edit').val(valor);

                var tip_ref=$("#hddir_tipref_andina").val();
                // $("#slcref_dir_cob_edit option[value='"+origen+"']").attr('selected', 'selected');
                $("#slcref_dir_cob_edit").find('option[text="'+tip_ref+'"]').attr('selected', 'selected');

            },
            error:function(){
            } 
        });
    },
    Listlineatelf_andina:function(nombre_lin_telf){
        $.ajax({
            url:this.url,
            type:'POST',
            dataType:'json',
            data:{
                command:'atencion_cliente',
                action:'Listlineatelf',
            },
            success:function(obj){
                if( obj.rst ){
                    var html = '';
                    html+="<option value=''>--Seleccione--</option>";
                    for(var i=0;i<obj.datos.fila.length;i++){
                        
                        html+="<option value='"+obj.datos.fila[i]['cell'][0]+"'>"+obj.datos.fila[i]['cell'][1]+"</option>";
                    }

                    $("#slclinea_cob_save").html(html);
                    $("#slclinea_cob_edit").html(html);

                }else{                           
                }
            },
            complete:function(){
                var valor=$('#hdslclinea_cob_edit').val();
                $('#slclinea_cob_edit').val(valor);

            },
            error:function(){
            } 
        });
    },
    Listorigentelf_andina:function(nombre_ori_telf){
        $.ajax({
            url:this.url,
            type:'POST',
            dataType:'json',
            data:{
                command:'atencion_cliente',
                action:'Listorigentelf',
            },
            success:function(obj){
                if( obj.rst ){
                    var html = '';
                    html+="<option value=''>--Seleccione--</option>";
                    for(var i=0;i<obj.datos.fila.length;i++){
                        
                        html+="<option value='"+obj.datos.fila[i]['cell'][0]+"'>"+obj.datos.fila[i]['cell'][1]+"</option>";
                    }

                    $("#slcorigem_cob_save").html(html);
                    $("#slcorigem_cob_edit").html(html);
                    $("#slcorig_dir_cob_save").html(html);
                    $("#slcorig_dir_cob_edit").html(html);

                }else{                           
                }
            },
            complete:function(){
                var valor=$('#hdslcorigem_cob_edit').val();
                $('#slcorigem_cob_edit').val(valor);

                var origen=$("#hddir_orig_andina").val();
                // $("#slcorig_dir_cob_edit option[value='"+origen+"']").attr('selected', 'selected');
                $("#slcorig_dir_cob_edit").find('option[text="'+origen+'"]').attr('selected', 'selected');
            },
            error:function(){
            } 
        });
    },
    si_elnumero_telf_existe : function(number){
        $.ajax({
            url: AtencionClienteDAO.url,
            type: 'GET',
            dataType: 'json',
            data:{
                command : 'atencion_cliente',
                action : 'si_elnumero_telf_existe',
                numero :number
            },
            success : function (obj) {
                if (obj.rst) {
                    if(obj.exist=='SI'){
                        // alert("El Telefono existe en el/los cliente(s) con DNI/RUC: "+obj.codigo_cliente);
                        return false
                    }
                }                
            }

        });
    },
    save_telf_cobranza_andina:function(xnumero,xanexo,xtipo,xreferencia,xlinea,xorigen,xcondi,xobs){
        $.ajax({
            url: AtencionClienteDAO.url,
            type: 'GET',
            dataType: 'json',
            data:{
                command : 'atencion_cliente',
                action : 'save_telf_cobranza_andina',
                numero:xnumero,
                anexo:xanexo,
                tipo:xtipo,
                referencia:xreferencia,
                linea:xlinea,
                origen:xorigen,
                condi:xcondi,
                obs:xobs,
                idcliente_cartera:$("#IdClienteCarteraMain").val(),
                codigo_cliente:$("#CodigoClienteMain").val(),
                idcartera:$("#IdCartera").val(),
                usuario_creacion:$("#hdCodUsuarioServicio").val()             
            },
            success : function (obj) {
                if (obj.rst) {
                    // alert(obj.rpta);

                    var codigo_cliente=$("#CodigoClienteMain").val();
                    $('#table_Lista_telf_cobranzas').jqGrid('setGridParam',{
                    datatype : 'json',
                        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=List_Telf_cobranzas_andina&codigo_cliente='+codigo_cliente,
                    }).trigger('reloadGrid');

                    $('#Dialoggestiontelefonos_cobranzas_save').dialog('close');

                    $('#table_telefonos_cliente').jqGrid().trigger('reloadGrid');

                }else{
                    alert(obj.rpta);
                }
            }

        });
    },
    List_Update_Telf_Andina:function(xidtelefono){
        $.ajax({
            url: AtencionClienteDAO.url,
            type: 'GET',
            dataType: 'json',
            data:{
                command : 'atencion_cliente',
                action : 'List_Update_Telf_Andina',
                idtelefono : xidtelefono          
            },
            success : function (obj) {
                if (obj.rst) {        

                    $('#hdslctipo_cob_edit').val(obj.rpta[0]['tipo']);
                    $('#hdslcreferencia_cob_edit').val(obj.rpta[0]['referencia']);
                    $('#hdslclinea_cob_edit').val(obj.rpta[0]['prefijos']);
                    $('#hdslcorigem_cob_edit').val(obj.rpta[0]['origen']);
                    $('#hdslcstate_cob_edit').val(obj.rpta[0]['state']);
                    $('#hdslcstatus_cob_edit').val(obj.rpta[0]['status']);
                    $('#hdslccondi_cob_edit').val(obj.rpta[0]['condicion']);

                    $('#hdtxtnumero_cob_edit').val(obj.rpta[0]['numero']);
                    $('#hdanexo_cob_edit').val(obj.rpta[0]['anexo']);
                    $('#hdareaobs_cob_edit').val(obj.rpta[0]['observacion']);

                    // var numero=$('#hdtxtnumero_cob_edit').val();
                    // var anexo=$('#hdanexo_cob_edit').val();
                    // var obs=$('#hdareaobs_cob_edit').val();                 
                    // alert(obj.rpta[0]['numero']);
                    // $('txtnumero_cob_edit').val(obj.rpta[0]['numero']);
                    // $('anexo_cob_edit').val(obj.rpta[0]['anexo']);
                    // $('areaobs_cob_edit').val(obj.rpta[0]['observacion']);


                    // $('slctipo_cob_edit').val();
                    // $('slcreferencia_cob_edit').val();
                    // $('slclinea_cob_edit').val();
                    // $('slcorigem_cob_edit').val();
                    // $('slcstate_cob_edit').val();
                    // $('slcstatus_cob_edit').val();

                }else{
                    
                }
            },
            complete:function(){
                var numero=$('#hdtxtnumero_cob_edit').val();
                var anexo=$('#hdanexo_cob_edit').val();
                var obs=$('#hdareaobs_cob_edit').val();
                var state=$('#hdslcstate_cob_edit').val();
                var status=$('#hdslcstatus_cob_edit').val();
                var condi=$('#hdslccondi_cob_edit').val();
                $('#txtnumero_cob_edit').val(numero);
                $('#anexo_cob_edit').val(anexo);
                $('#areaobs_cob_edit').val(obs);
                $('#slcstate_cob_edit').val(state);
                $('#slcstatus_cob_edit').val(status);
                $('#slccondi_cob_edit').val(condi);
            }

        });
    },
    update_telf_andina:function(xidtelefono,xnumero,xanexo,xtipo,xreferencia,xlinea,xorigen,xstate,xstatus,xcondi,xobs){
        $.ajax({
            url: AtencionClienteDAO.url,
            type: 'GET',
            dataType: 'json',
            data:{
                command : 'atencion_cliente',
                action : 'update_telf_andina',
                idtelefono:xidtelefono,
                numero:xnumero,
                anexo:xanexo,
                tipo:xtipo,
                referencia:xreferencia,
                linea:xlinea,
                origen:xorigen,
                obs:xobs,
                state:xstate,
                status:xstatus,
                condi:xcondi
            },
            success : function (obj) {
                if (obj.rst) {

                    var codigo_cliente=$("#CodigoClienteMain").val();
                    $('#table_Lista_telf_cobranzas').jqGrid('setGridParam',{
                    datatype : 'json',
                        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=List_Telf_cobranzas_andina&codigo_cliente='+codigo_cliente,
                    }).trigger('reloadGrid');

                    $('#Dialoggestiontelefonos_cobranzas_edit').dialog('close');

                    $('#table_telefonos_cliente').jqGrid().trigger('reloadGrid');

                    // alert(obj.msg);

                }else{
                    alert(obj.msg);
                }
            }

        });
    },
    eliminar_telf_andina : function(xidTelefono){
        $.ajax({
            url: AtencionClienteDAO.url,
            type: 'GET',
            dataType: 'json',
            data:{
                command : 'atencion_cliente',
                action : 'eliminar_telf_andina',
                idTelefono :xidTelefono
            },
            success : function (obj) {
                if (obj.rst) {

                    var codigo_cliente=$("#CodigoClienteMain").val();
                    $('#table_Lista_telf_cobranzas').jqGrid('setGridParam',{
                    datatype : 'json',
                        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=List_Telf_cobranzas_andina&codigo_cliente='+codigo_cliente,
                    }).trigger('reloadGrid');

                    $('#table_telefonos_cliente').jqGrid().trigger('reloadGrid');

                    // alert("El Telefono fue dado de baja...!!!");
                }                
            }

        });
    },
    List_Departamento_andina:function(xdpto){
        $.ajax({
            url:this.url,
            type:'POST',
            dataType:'json',
            data:{
                command:'atencion_cliente',
                action:'List_Departamento',
            },
            success:function(obj){
                if( obj.rst ){
                    var html = '';
                    html+="<option value=''>--Seleccione--</option>";
                    for(var i=0;i<obj.datos.fila.length;i++){
                        
                        html+="<option value='"+obj.datos.fila[i]['cell'][0]+"'>"+obj.datos.fila[i]['cell'][1]+"</option>";
                    }

                    $("#slcdepa_dir_cob_save").html(html);
                    $("#slcdepa_dir_cob_edit").html(html);

                }else{                           
                }
            },
            complete:function(){

                var departamento=$("#hddir_dep_andina").val();
                $("#slcdepa_dir_cob_edit option[value='"+departamento+"']").attr('selected', 'selected');
                AtencionClienteDAO.List_Provincia_andina(departamento);

            },
            error:function(){
            } 
        });
    },
    List_Provincia_andina:function(xdepartamento){
        $.ajax({
            url:this.url,
            type:'POST',
            dataType:'json',
            data:{
                command:'atencion_cliente',
                action:'List_Provincia',
                dpto:xdepartamento
            },
            success:function(obj){
                if( obj.rst ){
                    var html = '';                  

                    html+="<option value=''>--Seleccione--</option>";
                    for(var i=0;i<obj.datos.fila.length;i++){                        
                        html+="<option value='"+obj.datos.fila[i]['cell'][0]+"'>"+obj.datos.fila[i]['cell'][1]+"</option>";
                    }

                    $("#slcprov_dir_cob_save").html(html);
                    $("#slcprov_dir_cob_edit").html(html);

                }else{                    
                }
            },
            complete:function(){
                var provincia=$("#hddir_prov_andina").val();
                $("#slcprov_dir_cob_edit option[value='"+provincia+"']").attr('selected', 'selected');
                AtencionClienteDAO.List_Distrito_andina(provincia);
            },
            error:function(){
            } 
        });
    },
    List_Distrito_andina:function(xprov){
        $.ajax({
            url:this.url,
            type:'POST',
            dataType:'json',
            data:{
                command : 'atencion_cliente',
                action : 'List_Distrito',
                prov : xprov
            },
            success:function(obj){
                if( obj.rst ){
                    var html = '';
                    html+="<option value=''>--Seleccione--</option>";
                    for(var i=0;i<obj.datos.fila.length;i++){                        
                        html+="<option value='"+obj.datos.fila[i]['cell'][0]+"'>"+obj.datos.fila[i]['cell'][1]+"</option>";
                    }
                    $("#slcdistri_dir_cob_save").html(html);
                    $("#slcdistri_dir_cob_edit").html(html);

                }else{
                }
            },
            complete:function(){
                var distrito=$("#hddir_dis_andina").val();
                $("#slcdistri_dir_cob_edit option[value='"+distrito+"']").attr('selected', 'selected');
            },
            error:function(){
            } 
        });
    },
    insertar_nueva_direccion_andina:function(xidcliente_cartera,xcodigo_cliente,xidcartera,xidusuario_servicio,dir,dep,prov,dis,reg,zon,cod,num,call,xtxtref,ref,orig,condi,obs){
        $.ajax({
            url: AtencionClienteDAO.url,
            type: 'GET',
            dataType: 'json',
            data:{
                command : 'atencion_cliente',
                action : 'insertar_nueva_direccion_andina',
                direccion:dir,
                departamento:dep,
                provincia:prov,
                distrito:dis,
                region:reg,
                zona:zon,
                codigo_postal:cod,
                numero:num,
                calle:call,
                referencia:ref,
                txtref:xtxtref,
                origen:orig,
                condicion:condi,
                observacion:obs,
                idcliente_cartera:xidcliente_cartera,
                codigo_cliente:xcodigo_cliente,
                idcartera:xidcartera,
                idusuario_servicio:xidusuario_servicio             
            },
            success : function (obj) {
                if (obj.rst) {
                    // alert(obj.msg);

                }else{
                    alert(obj.msg);
                }
            },
            complete : function(){               
                if($("#hdmantdatoscontacto").val()=="CALL"){
                    var codigo_cliente= $("#CodigoClienteMain").val();
                    $('#table_Lista_Direc_cobranzas').jqGrid('setGridParam',{
                    datatype : 'json',
                        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=lista_direccion_cobranzas&codigo_cliente='+codigo_cliente,
                    }).trigger('reloadGrid');
                }else if($("#hdmantdatoscontacto").val()=="VISIT"){
                    var codigo_cliente= $("#CodigoClienteCampoMain").val();
                    var idcartera=$("#IdCarteraCampoMain").val();

                    $('#table_Lista_Direc_cobranzas').jqGrid('setGridParam',{
                    datatype : 'json',
                        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=lista_direccion_cobranzas&codigo_cliente='+codigo_cliente,
                    }).trigger('reloadGrid');

                    AtencionClienteDAO.ListarDireccionVisita(idcartera,codigo_cliente,function( obj ){
                        var html = '';
                        html+='<option value="0">--Seleccione--</option>';
                        for( i=0;i<obj.length;i++ ) {
                            html+='<option value="'+obj[i].iddireccion+'">'+obj[i].direccion+'</option>';
                        }
                        $('#cbCampoDireccionVisita').html(html);
                    },function(){});

                }
                $('#Dialoggestiondireccion_cobranzas_save').dialog('close');               
            }

        });
    },
    modificar_direccion_andina:function(xiddireccion,xdireccion,xdepartamento,xprovincia,xdistrito,xregion,xzona,xcodigo_postal,xnumero,xcalle,xreferencia,xtipo_referencia,xorigen,xcondicion,xestado,xobservacion){
        $.ajax({
            url: AtencionClienteDAO.url,
            type: 'GET',
            dataType: 'json',
            data:{
                command : 'atencion_cliente',
                action : 'modificar_direccion_andina',
                iddireccion:xiddireccion,
                direccion:xdireccion,
                departamento:xdepartamento,
                provincia:xprovincia,
                distrito:xdistrito,
                region:xregion,
                zona:xzona,
                codigo_postal:xcodigo_postal,
                numero:xnumero,
                calle:xcalle,
                referencia:xreferencia,
                tipo_referencia:xtipo_referencia,
                origen:xorigen,
                condicion:xcondicion,
                estado:xestado,
                observacion:xobservacion
            },
            success : function (obj) {
                if (obj.rst) {
                    // alert(obj.msg);

                }else{
                    alert(obj.msg);
                }
            },
            complete : function(){
                var codigo_cliente=$("#CodigoClienteMain").val();
                $('#table_Lista_Direc_cobranzas').jqGrid('setGridParam',{
                datatype : 'json',
                    url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=lista_direccion_cobranzas&codigo_cliente='+codigo_cliente,
                }).trigger('reloadGrid');

                $('#Dialoggestiondireccion_cobranzas_edit').dialog('close');
            }

        });
    },
    eliminar_direccion_andina:function(xiddireccion){
        $.ajax({
            url: AtencionClienteDAO.url,
            type: 'GET',
            dataType: 'json',
            data:{
                command : 'atencion_cliente',
                action : 'eliminar_direccion_andina',
                iddireccion:xiddireccion
            },
            success : function (obj) {
                if (obj.rst) {
                    // alert(obj.msg);

                }else{
                    alert(obj.msg);
                }
            },
            complete : function(){
                var codigo_cliente=$("#CodigoClienteMain").val();
                $('#table_Lista_Direc_cobranzas').jqGrid('setGridParam',{
                datatype : 'json',
                    url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=lista_direccion_cobranzas&codigo_cliente='+codigo_cliente,
                }).trigger('reloadGrid');

            }

        });
    },
    save_mail_andina:function(xmail,xobs){
        $.ajax({
            url: AtencionClienteDAO.url,
            type: 'GET',
            dataType: 'json',
            data:{
                command : 'atencion_cliente',
                action : 'save_mail_andina',
                mail:xmail,
                obs:xobs,
                idusuario_servicio:$("#hdCodUsuarioServicio").val(),
                idcliente:$("#idClienteMain").val()
            },
            success : function (obj) {
                if (obj.rst) {
                    // alert(obj.msg);

                }else{
                    alert(obj.msg);
                }
            },
            complete : function(){
                var idcliente=$("#idClienteMain").val();

                $('#table_Lista_Correo_cobranzas').jqGrid('setGridParam',{
                datatype : 'json',
                    url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=List_Correo_cobranzas_andina&idcliente='+idcliente,
                }).trigger('reloadGrid');

                $('#Dialoggestionmail_cobranzas_save').dialog('close');

            }

        });
    },
    UPDATE_Correo:function(xidcorreo,xcorreo,xobservacion,xusuario_creacion,xestado){
        $.ajax({
            url: AtencionClienteDAO.url,
            type: 'GET',
            dataType: 'json',
            data:{
                command : 'atencion_cliente',
                action : 'UPDATE_Correo',
                idcorreo:xidcorreo,
                correo:xcorreo,
                observacion:xobservacion,
                usuario_creacion:xusuario_creacion,
                estado:xestado
            },
            success : function (obj) {
                if (obj.rst) {
                    // alert(obj.msg);

                }else{
                    alert(obj.msg);
                }
            },
            complete : function(){
                var idcliente=$("#idClienteMain").val();

                $('#table_Lista_Correo_cobranzas').jqGrid('setGridParam',{
                datatype : 'json',
                    url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=List_Correo_cobranzas_andina&idcliente='+idcliente,
                }).trigger('reloadGrid');

                $('#Dialoggestionmail_cobranzas_edit').dialog('close');

            }

        });
    },
    eliminar_mail_andina:function(xidcorreo){
        $.ajax({
            url: AtencionClienteDAO.url,
            type: 'GET',
            dataType: 'json',
            data:{
                command : 'atencion_cliente',
                action : 'eliminar_mail_andina',
                idcorreo:xidcorreo
            },
            success : function (obj) {
                if (obj.rst) {
                    alert(obj.msg);

                }else{
                    alert(obj.msg);
                }
            },
            complete : function(){
                var idcliente=$("#idClienteMain").val();

                $('#table_Lista_Correo_cobranzas').jqGrid('setGridParam',{
                datatype : 'json',
                    url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=List_Correo_cobranzas_andina&idcliente='+idcliente,
                }).trigger('reloadGrid');
            }
        });
    },
    resumen_deuda:function(){
        $.ajax({
            url: AtencionClienteDAO.url,
            type: 'POST',
            dataType: 'json',
            data:{
                command : 'atencion_cliente',
                action : 'resumen_deuda',
                idcartera:$("#IdCarteraCampoMain").val(),
                codigo_cliente: $("#CodigoClienteCampoMain").val(),
                empresa: $("#cbCampoEmpresa").find(":selected").val(),
                td: $("#vis_xtd").val(),
                doc: $("#xvis_doc").val(),
                contado: $('#adelantado').attr('checked') ? 1 : 0
            },
            success : function (obj) {
                if (obj.rst) {                  

                    $("#resumen_deuda").html(obj.resumen)

                }else{
                    alert(obj.data);
                }
            },
            complete : function(){
                
            }

        });
    },
    consultar_datos_cliente:function(xidservicio,xidcartera,xcodigo_cliente,xidcliente_cartera){
        $.ajax({
            url: AtencionClienteDAO.url,
            type: 'GET',
            dataType: 'json',
            data:{
                command : 'atencion_cliente',
                action : 'consultar_datos_cliente',
                idcartera:$("#IdCarteraCampoMain").val(),
                codigo_cliente: $("#CodigoClienteCampoMain").val(),
                idservicio: xidservicio,
                idcartera: xidcartera,
                codigo_cliente: xcodigo_cliente,
                idcliente_cartera: xidcliente_cartera
            },
            success : function (obj) {
                if (obj.rst) {                  

                    $("#dato_idcliente").text(obj.data[0]['CODIGO_CLIENTE']);
                    $("#dato_razon_social").text(obj.data[0]['CLIENTE']);
                    $("#dato_nro_doc").text(obj.data[0]['NUMERO_DOCUMENTO']);
                    $("#dato_linea_credito").text(obj.data[0]['LINEA_CREDITO']);

                    // obj.data[0]['CODIGO_CLIENTE']
                    // obj.data[0]['CLIENTE']
                    // obj.data[0]['NUMERO_DOCUMENTO']
                    // obj.data[0]['LINEA_CREDITO']


                }else{
                    alert(obj.data);
                }
            },
            complete : function(){
                
            }

        });
    },
    Listar_Contactos_telf : function(xidpersona){
        $.ajax({
            url:AtencionClienteDAO.url,
            type:'GET',
            dataType:'json',
            data:{
                command: 'atencion_cliente',
                action: 'Listar_Contactos_telf',
                idpersona: xidpersona
            },
            success:function(obj){
                if( obj.rst ){
                    var html="";
                    for(var i=0;i<obj.datos.length;i++){                        
                        html+='<tr class="" id="" style="height: 22px;" onclick="actualizar_contacto_telf('+obj.datos[i]['idtelefono_pers']+')">';
                        html+='<td align="center" style="width: 50px;white-space:pre-line;border-left: 1px solid #8f9192;border-right:1px solid #8f9192;border-bottom:1px solid #8f9192;font-size: 9px;display:none;">'+obj.datos[i]['idtelefono_pers']+'</td>';
                        html+='<td align="center" style="width: 100px;white-space:pre-line;border-left: 1px solid #8f9192;border-right:1px solid #8f9192;border-bottom:1px solid #8f9192;font-size: 9px;">'+obj.datos[i]['numero']+'</td>';
                        html+='<td align="center" style="width: 200px;white-space:pre-line;border-right:1px solid #8f9192;border-bottom:1px solid #8f9192;font-size: 9px;height: 15px;display:none;">'+obj.datos[i]['idorigen']+'</td>';
                        html+='<td align="center" style="width: 200px;white-space:pre-line;border-right:1px solid #8f9192;border-bottom:1px solid #8f9192;font-size: 9px;height: 15px;">'+obj.datos[i]['origen']+'</td>';
                        html+='<td align="center" style="width: 200px;white-space:pre-line;border-right:1px solid #8f9192;border-bottom:1px solid #8f9192;font-size: 9px;display:none;">'+obj.datos[i]['idtipo_telefono']+'</td>';
                        html+='<td align="center" style="width: 200px;white-space:pre-line;border-right:1px solid #8f9192;border-bottom:1px solid #8f9192;font-size: 9px;">'+obj.datos[i]['tipo_telefono']+'</td>';
                        html+='<td align="center" style="width: 200px;white-space:pre-line;border-right:1px solid #8f9192;border-bottom:1px solid #8f9192;font-size: 9px;display:none;">'+obj.datos[i]['idlinea_telefono']+'</td>';
                        html+='<td align="center" style="width: 200px;white-space:pre-line;border-right:1px solid #8f9192;border-bottom:1px solid #8f9192;font-size: 9px;">'+obj.datos[i]['linea_telefono']+'</td>';
                        html+='<td align="center" style="width: 50px;white-space:pre-line;border-right:1px solid #8f9192;border-bottom:1px solid #8f9192;font-size: 9px;display:none;">'+obj.datos[i]['estado']+'</td>';
                        html+='<td align="center" style="width: 110px;white-space:pre-line;border-right:1px solid #8f9192;border-bottom:1px solid #8f9192;font-size: 9px;display:none;">'+obj.datos[i]['idpersona']+'</td>';
                        html+='<td align="center" style="width: 20px;white-space:pre-line;border-right:1px solid #8f9192;border-bottom:1px solid #8f9192;font-size: 9px;" class="cambiante" onclick="borrar_contacto_tefl('+obj.datos[i]['idtelefono_pers']+');"><img src="../img/Minus.png"></td>';
                        html+='<td align="center" style="padding:2px 0;height: 15px;"></td>';
                        html+='</tr>';                        
                    }

                    $("#telf_contacto tbody").html(html);

                    $('#telf_contacto tbody tr').hover(
                        function () {
                            $(this).addClass('ui-state-hover');
                        },
                        function () {
                            $(this).removeClass('ui-state-hover');
                        }
                    );

                    $( '#telf_contacto tbody tr' ).click(function() {
                        $( this ).toggleClass( "ui-state-highlight");
                    });

                }else{

                }
            },
            complete:function(){
            },
            error:function(){
            } 
        });
    },
    cbo_listar_origen: function(){
        $.ajax({
            url:AtencionClienteDAO.url,
            type:'POST',
            dataType:'json',
            data:{
                command: 'atencion_cliente',
                action: 'cbo_listar_origen'
            },
            success:function(obj){
                if( obj.rst ){
                    var cbo="pers_origen";
                    var html = '';
                    html+="<option value=''>.:|:.</option>";
                    for(var i=0;i<obj.datos.fila.length;i++){

                        // var selected="";
                        // if (obj.datos.fila[i]['cell'][0]==xseleccionado) {
                        //     selected="selected";
                        // }

                        html+="<option value='"+obj.datos.fila[i]['cell'][0]+"' >"+obj.datos.fila[i]['cell'][1]+"</option>";                    
                    }
                    $('#'+cbo).html(html);
                }else{
                }
            },
            complete:function(){
            },
            error:function(){
            } 
        });
    },
    cbo_tipo_telefono: function(){
        $.ajax({
            url:AtencionClienteDAO.url,
            type:'POST',
            dataType:'json',
            data:{
                command: 'atencion_cliente',
                action: 'cbo_tipo_telefono'
            },
            success:function(obj){
                if( obj.rst ){
                    var cbo="pers_tip_telf";
                    var html = '';
                    html+="<option value=''>.:|:.</option>";
                    for(var i=0;i<obj.datos.fila.length;i++){

                        // var selected="";
                        // if (obj.datos.fila[i]['cell'][0]==xseleccionado) {
                        //     selected="selected";
                        // }

                        html+="<option value='"+obj.datos.fila[i]['cell'][0]+"' >"+obj.datos.fila[i]['cell'][1]+"</option>";                    
                    }
                    $('#'+cbo).html(html);
                }else{
                }
            },
            complete:function(){
            },
            error:function(){
            } 
        });
    },
    cbo_linea_telefono: function(){
        $.ajax({
            url:AtencionClienteDAO.url,
            type:'POST',
            dataType:'json',
            data:{
                command: 'atencion_cliente',
                action: 'cbo_linea_telefono'
            },
            success:function(obj){
                if( obj.rst ){
                    var cbo="pers_lin_telf";
                    var html = '';
                    html+="<option value=''>.:|:.</option>";
                    for(var i=0;i<obj.datos.fila.length;i++){

                        // var selected="";
                        // if (obj.datos.fila[i]['cell'][0]==xseleccionado) {
                        //     selected="selected";
                        // }

                        html+="<option value='"+obj.datos.fila[i]['cell'][0]+"' >"+obj.datos.fila[i]['cell'][1]+"</option>";                    
                    }
                    $('#'+cbo).html(html);
                }else{
                }
            },
            complete:function(){
            },
            error:function(){
            } 
        });
    },
    insertar_contacto_tefl : function(xidpersona,xnro_telf,xori_telf,xtip_telf,xlin_telf){
        $.ajax({
            url:AtencionClienteDAO.url,
            type:'POST',
            dataType:'json',
            data:{
                command: 'atencion_cliente',
                action: 'insertar_contacto_tefl',
                idpersona: xidpersona,
                nro_telf: xnro_telf,
                ori_telf: xori_telf,
                tip_telf: xtip_telf,
                lin_telf: xlin_telf
            },
            success:function(obj){
                if( obj.rst ){
                    AtencionClienteDAO.Listar_Contactos_telf($("#hpidpersona").val());
                }else{
                }
            },
            complete:function(){
            },
            error:function(){
            } 
        });
    },
    modificar_contacto_tefl : function(xidtelefono_pers,xnro_telf,xori_telf,xtip_telf,xlin_telf){
        $.ajax({
            url:AtencionClienteDAO.url,
            type:'POST',
            dataType:'json',
            data:{
                command: 'atencion_cliente',
                action: 'modificar_contacto_tefl',
                idtelefono_pers: xidtelefono_pers,
                nro_telf: xnro_telf,
                ori_telf: xori_telf,
                tip_telf: xtip_telf,
                lin_telf: xlin_telf
            },
            success:function(obj){
                if( obj.rst ){
                    AtencionClienteDAO.Listar_Contactos_telf($("#hpidpersona").val());
                }else{
                }
            },
            complete:function(){
            },
            error:function(){
            } 
        });
    },
    borrar_contacto_tefl : function(xidtelefono_pers){
        $.ajax({
            url:AtencionClienteDAO.url,
            type:'POST',
            dataType:'json',
            data:{
                command: 'atencion_cliente',
                action: 'borrar_contacto_tefl',
                idtelefono_pers: xidtelefono_pers
            },
            success:function(obj){
                if( obj.rst ){
                    AtencionClienteDAO.Listar_Contactos_telf($("#hpidpersona").val());
                }else{
                }
            },
            complete:function(){
            },
            error:function(){
            } 
        });
    },
    Listar_Contactos_mail : function(xidpersona){
        $.ajax({
            url:AtencionClienteDAO.url,
            type:'GET',
            dataType:'json',
            data:{
                command: 'atencion_cliente',
                action: 'Listar_Contactos_mail',
                idpersona: xidpersona
            },
            success:function(obj){
                if( obj.rst ){
                    var html="";
                    for(var i=0;i<obj.datos.length;i++){                        
                        html+='<tr class="" id="" style="height: 22px;" onclick="actualizar_contacto_mail('+obj.datos[i]['idemail_pers']+')">';
                        html+='<td align="center" style="width: 50px;white-space:pre-line;border-left: 1px solid #8f9192;border-right:1px solid #8f9192;border-bottom:1px solid #8f9192;font-size: 9px;display:none;">'+obj.datos[i]['idemail_pers']+'</td>';
                        html+='<td align="left" style="width: 200px;white-space:pre-line;border-left: 1px solid #8f9192;border-right:1px solid #8f9192;border-bottom:1px solid #8f9192;font-size: 9px;">'+obj.datos[i]['email']+'</td>';
                        html+='<td align="center" style="width: 200px;white-space:pre-line;border-right:1px solid #8f9192;border-bottom:1px solid #8f9192;font-size: 9px;height: 15px;display:none;">'+obj.datos[i]['estado']+'</td>';
                        html+='<td align="center" style="width: 200px;white-space:pre-line;border-right:1px solid #8f9192;border-bottom:1px solid #8f9192;font-size: 9px;height: 15px;display:none;">'+obj.datos[i]['idcliente']+'</td>';
                        html+='<td align="center" style="width: 110px;white-space:pre-line;border-right:1px solid #8f9192;border-bottom:1px solid #8f9192;font-size: 9px;display:none;">'+obj.datos[i]['idpersona']+'</td>';
                        html+='<td align="center" style="width: 20px;white-space:pre-line;border-right:1px solid #8f9192;border-bottom:1px solid #8f9192;font-size: 9px;" class="cambiante" onclick="borrar_contacto_mail('+obj.datos[i]['idemail_pers']+');"><img src="../img/Minus.png"></td>';
                        html+='<td align="center" style="padding:2px 0;height: 15px;"></td>';
                        html+='</tr>';                        
                    }

                    $("#mail_contacto tbody").html(html);

                    $('#mail_contacto tbody tr').hover(
                        function () {
                            $(this).addClass('ui-state-hover');
                        },
                        function () {
                            $(this).removeClass('ui-state-hover');
                        }
                    );

                    $( '#mail_contacto tbody tr' ).click(function() {
                        $( this ).toggleClass( "ui-state-highlight");
                    });

                }else{

                }
            },
            complete:function(){
            },
            error:function(){
            } 
        });
    },
    insertar_contacto_mail : function(xidpersona,xemail){
        $.ajax({
            url:AtencionClienteDAO.url,
            type:'POST',
            dataType:'json',
            data:{
                command: 'atencion_cliente',
                action: 'insertar_contacto_mail',
                idpersona: xidpersona,
                email: xemail
            },
            success:function(obj){
                if( obj.rst ){
                    AtencionClienteDAO.Listar_Contactos_mail($("#hpidpersona").val());
                }else{
                }
            },
            complete:function(){
            },
            error:function(){
            } 
        });
    },
    modificar_contacto_mail : function(xidemail_pers,xemail){
        $.ajax({
            url:AtencionClienteDAO.url,
            type:'POST',
            dataType:'json',
            data:{
                command: 'atencion_cliente',
                action: 'modificar_contacto_mail',
                idemail_pers: xidemail_pers,
                email: xemail
            },
            success:function(obj){
                if( obj.rst ){
                    AtencionClienteDAO.Listar_Contactos_mail($("#hpidpersona").val());
                }else{
                }
            },
            complete:function(){
            },
            error:function(){
            } 
        });
    },
    borrar_contacto_mail : function(xidemail_pers){
        $.ajax({
            url:AtencionClienteDAO.url,
            type:'POST',
            dataType:'json',
            data:{
                command: 'atencion_cliente',
                action: 'borrar_contacto_mail',
                idemail_pers: xidemail_pers
            },
            success:function(obj){
                if( obj.rst ){
                    AtencionClienteDAO.Listar_Contactos_mail($("#hpidpersona").val());
                }else{
                }
            },
            complete:function(){
            },
            error:function(){
            } 
        });
    },
}
    