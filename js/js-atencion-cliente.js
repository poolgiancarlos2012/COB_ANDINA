// divContentMain -- 1170px
// barraflotante  -- 1170px
// tableMenu -- 1170px
// cobrastHOME -- 1152px    
// <div style="width: 1170px; height: 20px; border: 0 none;margin:0px auto" class="ui-widget-header ui-corner-bottom"></div>
$(document).ready(function(){

    
    $("#tabs_gestion").tabs({
        select: function(event, ui){
            $("#table_list_orden").jqGrid('setGridParam',{
                datatype : 'json',
                url:'../controller/ControllerAlfa.php?command=produccion&action=ConsultarOrden',
            }).trigger('reloadGrid');
            
        }
    });

    $("#tabs_gestion_visita").tabs({
        select: function(event, ui){
            $("#table_list_orden").jqGrid('setGridParam',{
                datatype : 'json',
                url:'../controller/ControllerAlfa.php?command=produccion&action=ConsultarOrden',
            }).trigger('reloadGrid');
            
        }
    });

    verificar_carga = function(){
        document.getElementById('cargando').style. display = 'none';
        $("#cuerpo").css("display","block");
    };

    $('#fcp_cuenta_all').datepicker({
        dateFormat:'yy-mm-dd',
      //  minDate : 0,
      //  maxDate : +15,
        dayNamesMin:['D','L','M','M','J','V','S'],
        monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre'], 
        //currentText : 'Now',
        onSelect: function (date) {
            var selectedDate = new Date(date);
            $('#table_cuenta_aplicar_gestion').find("tr").find(":text[name='txtFechaCpCuenta']").val(date);
        },
        showButtonPanel: true,
        beforeShow: function (input) {
                var buttonPane = $(input).datepicker("widget").find(".ui-datepicker-buttonpane");
                var btn = $('<button class="ui-datepicker-current ui-state-default ui-priority-secondary ui-corner-all" type="button">Limpiar</button>');
                btn.unbind("click").bind("click", function () {
                    $("#fcp_cuenta_all").val('');
                    $('#table_cuenta_aplicar_gestion').find("tr").find(":text[name='txtFechaCpCuenta']").val('');
                });
                btn.appendTo(buttonPane);
        }
    });

	$("#call_ini").mask("2099-99-99");
	$("#call_fin").mask("2099-99-99");

    //para neotel, pa q se desloguee si cierra o sale de la ventana
    window.onbeforeunload=cerrarNeotel;
    //
    //$('#divTbTelefonosCliente').draggable();
    //AtencionClienteJQGRID.historico();
    AtencionClienteJQGRID.carteras_multiples();
    /****/
    AtencionClienteJQGRID.telefonos_cliente();
    //AtencionClienteJQGRID.centro_pago();
    /****/
    AtencionClienteJQGRID.telefonos();
    AtencionClienteJQGRID.direcciones();
    AtencionClienteJQGRID.llamada();
    /******************/
    AtencionClienteJQGRID.visita();
    AtencionClienteJQGRID.visitas_one();
    AtencionClienteJQGRID.llamadas_two();
   
    /******************/
    AtencionClienteJQGRID.campo_direcciones();
    AtencionClienteJQGRID.campo_telefonos();
    AtencionClienteJQGRID.campo_agendados();
    // AtencionClienteJQGRID.busquedaGestionados();
    AtencionClienteJQGRID.busquedaSinGestion();
    AtencionClienteJQGRID.busquedaEstado();
    AtencionClienteJQGRID.busquedaBase();
    AtencionClienteJQGRID.matrizBusqueda();
    AtencionClienteJQGRID.busquedaManual();
    // AtencionClienteJQGRID.gestion_telefono(); ###
    AtencionClienteJQGRID.lista_telefonos_cobtranzas();
    AtencionClienteJQGRID.lista_direccion_cobranzas();
    AtencionClienteJQGRID.List_Correo_cobranzas_andina();
    AtencionClienteJQGRID.Listar_Contactos();
    // AtencionClienteJQGRID.Listar_Contactos_Telefono();
  //  AtencionClienteJQGRID.gestionComercial();//Piro 30-12-2014
   
    // CAMBIO 20-06-2016
    // AtencionClienteJQGRID.gestion_direccion_opcion(); ###
    // CAMBIO 20-06-2016
        
    /******/
    //AtencionClienteDAO.ListarCampanias();
    /*********/
    AtencionClienteJQGRID.facturas_digitales();
    /*********/

    load_alertas_recientes_hoy();
    AtencionClienteDAO.ListarCampanias();
    //AtencionClienteDAO.ListarEventosHoy();
    /*****************/
    listar_contacto();
    listar_motivo_no_pago();
    listar_parentesco();
    // listar_carga_final(); ###
    // listar_sustento_pago();//jmore18112014 ###
    listar_alerta_gestion();//jmore18112014  

    listar_estado_cliente();//piro
    listar_disposicion_refinanciamiento();//piro
    listar_situacion_laboral();  //piro
    
    AtencionClienteDAO.ListarEstado();

    AtencionClienteJQGRID.Buscar_Cliente();

    // AtencionClienteDAO.LoadTipoReferencia(); ###
    // AtencionClienteDAO.LoadOrigen(); ###
    // AtencionClienteDAO.ListarSemana_opcion(); ###
  //  AtencionClienteDAO.loadCampania();//piro

    // AtencionClienteDAO.List_Departamento_filtro(); ###
    // AtencionClienteDAO.List_Provincia_filtro(); ###
    // AtencionClienteDAO.List_Distrito_filtro(); ###
    /*****************/
    // AtencionClienteDAO.LoadLineaTelefono(); ###
    //$('#txtDepartamentoAtencionCliente,#txtProvinciaAtencionCliente,#txtDistritoAtencionCliente,#txtCampoDireccionDepartamento,#txtCampoDireccionProvincia,#txtCampoDireccionDistrito').autocomplete();
    // listar_departamento(); ###
    //isNumber(); //14-01-2015 Validacion campo de observacion
    /*****************/
    //MANTTELF
        $("#btnGestionTelefonos").click(function(){

            if($("#CodigoClienteMain").val()!=''){        
                $("#add_telf_titu_aval").val(1);
                $('#table_gestion_telefono').jqGrid('setGridParam',{
                    datatype : 'json',
                    url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=gestion_telefono&codigo_cliente='+$("#CodigoClienteMain").val(),
                }).trigger('reloadGrid');

                $('#DialogGestionTelefonos').dialog('open');

                //$("#DialogGestionTelefonos").parent().css({'z-index':'1001'});

            }else{
                alert("Es necesario realizar la busqueda de un cliente");
                //return false;
            }
        });
    //MANTTELF
    $("#btnGestionTelefonos_campo").click(function(){

            if($("#txtCampoCodigoSearch").val()!=''){        
                $("#add_telf_titu_aval").val(1);
                $('#table_gestion_telefono').jqGrid('setGridParam',{
                    datatype : 'json',
                    url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=gestion_telefono&codigo_cliente='+$("#txtCampoCodigoSearch").val(),
                }).trigger('reloadGrid');

                $('#DialogGestionTelefonos').dialog('open');

                //$("#DialogGestionTelefonos").parent().css({'z-index':'1001'});

            }else{
                alert("Es necesario realizar la busqueda de un cliente");
                //return false;
            }
        });

    $("#idconsultar_cliente_campo").click(function(){
        var idservicio=$("#hdCodServicio").val();
        var codigo_cliente=$("#vis_codigo_cliente").val();
        var cliente=$("#vis_cliente").val();
        var td=$("#vis_td").val();
        var documento=$("#vis_doc").val();

        $('#table_Lista_cliente_campo').jqGrid('setGridParam',{
        datatype : 'json',
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=Buscar_Cliente&idservicio='+idservicio+'&codigo_cliente='+codigo_cliente+'&cliente='+cliente+'&td='+td+'&doc='+documento,
        }).trigger('reloadGrid');
    });

    $("#vis_codigo_cliente,#vis_cliente,#vis_td,#vis_doc").keypress(function (ev) {
        var keycode = (ev.keyCode ? ev.keyCode : ev.which);
        if (keycode == '13') {
            var idservicio=$("#hdCodServicio").val();
            var codigo_cliente=$("#vis_codigo_cliente").val();
            var cliente=$("#vis_cliente").val();
            var td=$("#vis_td").val();
            var documento=$("#vis_doc").val();

            $('#table_Lista_cliente_campo').jqGrid('setGridParam',{
            datatype : 'json',
                url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=Buscar_Cliente&idservicio='+idservicio+'&codigo_cliente='+codigo_cliente+'&cliente='+cliente+'&td='+td+'&doc='+documento,
            }).trigger('reloadGrid');
        }
    })

    $("#cbCampoEmpresa,#vis_xtd,#xvis_doc,#adelantado").change(function(){

        $('#table_cuenta_aplicar_gestion_visita').empty();
        $('#resumen_deuda').empty();

        var idcliente_cartera=$('#IdClienteCarteraCampoMain').val();
        var idcartera=$('#IdCarteraCampoMain').val();


        AtencionClienteDAO.ListarCuentaVisita(idcartera, idcliente_cartera);
        AtencionClienteDAO.resumen_deuda();
    });

    // $('#textbox1').val($(this).is(':checked'));

$('#chkbSintelf').change(function() {
    if($(this).is(":checked")) {      
        AtencionClienteDAO.si_elnumero_telf_existe('000000000');
        AtencionClienteDAO.save_telf_cobranza_andina('000000000','','9','13','6','17','CORRECTO','Cliente No dispone de ningun numerico de tel.')
        $('#table_telefonos_cliente').jqGrid().trigger('reloadGrid');
    }
       
});

    filtro_gestiones = function(){
        var elemt="filtro_con_sin_gestion";
        $("#"+elemt).multiselect({
            noneSelectedText: 'SELECCIONAR',
            checkAllText:"TODO",
            uncheckAllText:"NINGUNO",
            height: 150,
            header: true,
            multiple:true,
            open: function(event, ui){
                this.click(
                    $('label[for="ui-multiselect-'+elemt+'-option-0"]').parent().parent().parent().css('width', '350px'),
                    $('label[for="ui-multiselect-'+elemt+'-option-0"]').removeClass( "ui-state-active" ),
                    $('label[for="ui-multiselect-'+elemt+'-option-0"]').removeAttr('checked')
                );
            },
            click: function(event, ui){
                var filter_con_sin_gestion = $("#"+elemt).multiselect("getChecked").map(function(){
                   return this.value;    
                }).get();

                $('#hdfiltro_con_sin_gestion').val(filter_con_sin_gestion);
                
                /*RESETEO DEL BARRIDO MANUAL*/            
                // carga_cantidad_clientes_filtro();
                // $('#txtItemAtencionClienteMain').val('0');
                // $('#txtCantidadClientesAtencionClienteMain').text('0');
                // load_change_campania_atencion_cliente();
                $('#hTipoGestion').val('propias');
                $('#txtItemAtencionClienteMain').val('0');
                $('#txtCantidadClientesAtencionClienteMain').text('0');
                carga_cantidad_clientes_filtro();
                cargar_dias_mora();
                cargar_territorio();
                /*RESETEO DEL BARRIDO MANUAL*/


            },
            
            selectedText: function(numChecked,numTotal,checkedItems){
                var selected = $("#"+elemt).multiselect("getChecked").map(function(){return this.value;}).get();
                var nombre_selected =$("#"+elemt).multiselect().find('option[value="'+selected+'"]').text()
                return nombre_selected;
            }
        });
        $("#"+elemt).multiselect("uncheckAll");
        $('.ui-multiselect').css({
            'border':'0',
            'border': '1px solid #ACACAC',
            'color':'#171f23',
            'font-size':'12',
            'font-family':'gotham_bookregular',
            'height':'20'
        });
        $('.ui-multiselect-menu').css({
            'border':'0',
            'font-size':'12',
            'font-family':'gotham_bookregular',
            'margin-top':'5'
        });
        $('.ui-multiselect .ui-widget-header').css({
            'background':'0',
            'border':'0'
        });
    }
    filtro_gestiones();
    //FILTRO GESTIONES
    
    $('input:radio[name=chkTipoContacto1]').click(function(){
        $('input:radio[name=chkTipoContacto2]').removeAttr('checked');
        $('#txtFechaVisita2').val('');
        $('#txtCampoHora2').val('');
    });
    $('input:radio[name=chkTipoContacto2]').click(function(){
        $('input:radio[name=chkTipoContacto1]').removeAttr('checked');
        $('#txtFechaVisita').val('');
        $('#txtCampoHora').val('');
    });

    irInicio();
    $('#txtFechaVisita,#txtFechaVisita2').datepicker({
        dateFormat:'yy-mm-dd',
        //minDate : 0,
        //maxDate : +5,
        dayNamesMin:['D','L','M','M','J','V','S'],
        monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre'], 
        currentText : 'Now'
    });
     $('#txtFechaCompromisoPago').datepicker({
        dateFormat:'yy-mm-dd',
        minDate : 0,
        maxDate : +4,
        dayNamesMin:['D','L','M','M','J','V','S'],
        monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre'], 
        currentText : 'Now'
    });
    $('#dlgInformeVisitaGestion').dialog({
        autoOpen: false,
        title: 'Informe de Visita de Gesti&oacute;n',
        modal:true,
        width:200,
        height:70,
        position: "top",
        draggable:false,
        resizable:false
    });
//FIN PIRO
     $('#txtFechaAcuerdoCovinoc, .standarFechaAcuerdo').datepicker({
        dateFormat:'yy-mm-dd',
        minDate : 0,
        //maxDate : +5,
        dayNamesMin:['D','L','M','M','J','V','S'],
        monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre'], 
        currentText : 'Now'
    });
    $('#btnShowAlert').one('click',
        function ( ) {
            AtencionClienteDAO.loadInitializeAlertas();
        }
    );
    /*****************/
    $('#btnACAgregarTelefono').one('click', 
        function ( ) {
            //AtencionClienteDAO.LoadLineaTelefono();
            AtencionClienteDAO.LoadTipoTelefono();
        }
    );
    $('#tabAC1Globales').one('click',
        function ( ) {
            
            listar_ac_segmento('cbAtencionGlobalesSegmento');
            listar_ac_evento('cbAtencionGlobalesEvento');
            listar_ac_cluster('cbAtencionGlobalesCluster');
        }
    );
    $('#tabCampoGlobales').one('click',
        function ( ) {
            AtencionClienteDAO.ListarCampanias('dd');
        }
    );
    $('#iconSlider').one('click',
        function ( ) 
        {
            AtencionClienteDAO.ListarSpeechArgumentario();
            AtencionClienteDAO.ListarTareasHoy();
        }
    );
    /*********/
    /*$('#tabAC2Direcciones').one('click',
        function( ) {
            AtencionClienteJQGRID.direcciones();
        }
    );
    $('#tabAC2Telefonos').one('click',
        function ( ) {
            AtencionClienteJQGRID.telefonos();
        }
    );*/
    /**********/
    $('#btnCentroPagos').one('click',
        function ( ) {
            AtencionClienteJQGRID.centro_pago();
        }
    );
    /*$('#btnVisitas').one('click',
        function()
        {
            reloadJQGRID_visita_atencion_cliente();
        }
    );*/
    $('#tabAC1Ranking').one('click',
        function ( ) {
            ranking_total_usuario_por_dia();
        //ranking_total_servicio_por_dia();
        }
    );
    $('#tab_meta_ranking_mi_meta').one('click',function(){
        load_meta_cliente_cuenta_usuario_servicio();
    });
    $('#tab_meta_ranking_otros').one('click',function(){
        ranking_total_servicio_por_dia();
    });
    /*********/
    /*$('#tabAC2DFacturaDigital').click(
        function()
        {
            AtencionClienteDAO.GetLineasFacturaDigitalXcliente();
            AtencionClienteDAO.ListarSupervisores();
        }
    );*/
    /*$('#tabAC2Direcciones').click(
        function()
        {
            reloadJQGRID_direccion();
        }
    );*/   
    /*$('#tabAC2Telefonos').click(
        function()
        {
            reloadJQGRID_telefono();
        }
    );*/
    /*$('#tabAC2CuentaPagos').click(
        function()
        {
            AtencionClienteDAO.ListarCuenta();
            listar_historico_cuenta();
        }
    );*/
    var date = new Date();
    $('#txtFechaFinRptRefinan,#txtFechaInicioRptRefinan,#txtFechaInicioRankingTotalUsuarioPorDia,#txtFechaFinRankingTotalUsuarioPorDia,#txtFechaInicioRankingTotalServicioPorDia,#txtFechaFinRankingTotalServicioPorDia').val((date.getFullYear()+'-'+(((date.getMonth()).toString().length==1)?'0'+(date.getMonth()+1):(date.getMonth()+1))+'-'+(((date.getDate()).toString().length==1)?'0'+date.getDate():date.getDate())));
    /*********/
    //listar_carga_final();
    /*AtencionClienteDAO.LoadLineaTelefono();
            AtencionClienteDAO.LoadTipoTelefono();
            AtencionClienteDAO.LoadTipoReferencia();
            AtencionClienteDAO.LoadOrigen();
            AtencionClienteDAO.ListarNotificador(
                function( obj ){
                    var html = '';
                        html+='<option value="0">--Seleccione--</option>';
                    for( i=0;i<obj.length;i++ ) {
                        html+='<option value="'+obj[i].idnotificador+'">'+obj[i].notificador+'</option>';
                    }
                    $('#cbNotificadorCampoVisita').html(html);
                },
                function( ){ }
            );*/
    //      }
    //  );
    /************/
    $('#aGestorCampo').one('click',
        function()
        {
            /*AtencionClienteJQGRID.campo_direcciones();
            AtencionClienteJQGRID.campo_telefonos();
            AtencionClienteJQGRID.campo_agendados();*/
            //listar_contacto();
            //listar_motivo_no_pago();
            //listar_carga_final();
            //AtencionClienteDAO.LoadLineaTelefono();
            AtencionClienteDAO.LoadTipoTelefono();
            //AtencionClienteDAO.LoadTipoReferencia();
            //AtencionClienteDAO.LoadOrigen();
            AtencionClienteDAO.ListarGestorCampo( $('#hdCodServicio').val(),
                function( obj ){
                    
                    AtencionClienteDAO.Notificadores = obj;
                    
                    var html = '';
                    html+='<option value="0">--Seleccione--</option>';
                    for( i=0;i<obj.length;i++ ) {
                        //html+='<option value="'+obj[i].idnotificador+'">'+obj[i].notificador+'</option>';
                        html+='<option value="'+obj[i].idusuario_servicio+'">'+obj[i].operador+'</option>';
                    }
                    $('#cbNotificadorCampoVisita').html(html);
                },
                function( ){ }
                );
        }
        );
    /*************/
    $('#tabAC1Agendar').one('click',
        function()
        {
            AtencionClienteJQGRID.agendados();
            AtencionClienteDAO.LoadFinalServicioAgendar();
        }
        );
    
    $('#tabBusquedaGestionados').one('click', 
        function ()
        {
            $("#table_busqueda_gestionados").jqGrid().trigger('reloadGrid');    
        }
    );
    
    $('#tabBusquedaSinGestion').one('click', 
        function ()
        {
            $("#table_busqueda_sin_gestion").jqGrid().trigger('reloadGrid');    
        }
    );
    
    $('#tabBusquedaManual').one('click',
        function ( ) {
            
            AtencionClienteDAO.LoadFiltrosTablaAtencionCliente();
        }
        );
    $('#tabBusquedaGlobal').one('click',
        function(){
            AtencionClienteJQGRID.busquedaGlobal();
        }
        );
        
    /*$('#tabAC1Resultado').one('click',
        function()
        {
            listar_contacto('cbLlamadaContacto');
            listar_motivo_no_pago();
            listar_carga_final();
            AtencionClienteDAO.LoadLineaTelefono();
            AtencionClienteDAO.LoadTipoTelefono();
            AtencionClienteDAO.LoadTipoReferencia();
            //AtencionClienteDAO.LoadOrigen();
            AtencionClienteDAO.ListarSupervisores();
            AtencionClienteJQGRID.facturas_digitales();
            AtencionClienteDAO.ListarEstado();

        }
        );*/
    /******************/
    
    
    
    /******************/
    $('#layerDatepicker').datepicker({
        inline:true,
        autoSize:true,
        dayNamesMin:['D','L','M','M','J','V','S'],
        monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']
    });
    $('#txtFechaFinRptRefinan,#txtFechaInicioRptRefinan,#txtAgendarBuscarFechaFin,#txtAgendarBuscarFechaInicio,#txtCampoVisitaFechaInicio,#txtCampoVisitaFechaFin,#txtFechaNota,#txtFechaInicioRankingTotalUsuarioPorDia,#txtFechaFinRankingTotalUsuarioPorDia,#txtFechaInicioRankingTotalServicioPorDia,#txtFechaFinRankingTotalServicioPorDia,#txtFechaVencimiento,#txtFechaPrimerPagoCuotificacion,#txtFechaAcuerdoCovinoc').datepicker({
        dateFormat:'yy-mm-dd',
        dayNamesMin:['D','L','M','M','J','V','S'],
        monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']
    });
    $('#txtFechaInicioRankingTotalUsuarioPorDia,#txtFechaFinRankingTotalUsuarioPorDia,#txtFechaInicioRankingTotalServicioPorDia,#txtFechaFinRankingTotalServicioPorDia').datepicker({
        dateFormat:'yy-mm-dd',
        dayNamesMin:['D','L','M','M','J','V','S'],
        monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre'], 
        appendText: '(yyyy-mm-dd)', 
        currentText : 'Now'
    });
    /******************/
    $('#txtAgendarFechaCP,#txtAtencionLlamadaFechaCp,#txtCampoFechaCP,#txtCampoFechaVisita,#txtCampoFechaRecepcion').mask("2099-99-99");
    $('#txtCampoHoraUbicacion,#txtCampoHoraSalida,#txtCampoHora,#txtCampoHora2').mask("99:99"); //PIRO
    /***************/
    $('#txtFechaAlertaTelefono,#txtFechaAgendar,#txtFechaAlerta,#txtAtencionLlamadaFecha,').datetimepicker({
        showSecond:true,
        dateFormat:'yy-mm-dd',
        timeFormat:'hh:mm:ss',
        autoSize:true,
        dayNamesMin:['D','L','M','M','J','V','S'],
        monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']
    });
    $('#txtAtencionClienteHorarioAtencion,#txtFiltroHorarioInicio,#txtFiltroHorarioFin').timepicker({
        showSecond:true,
        timeFormat:'hh:mm:ss',
        autoSize:true
    });
    /******************/
    setInterval("showAlert()",4000);
    /******************/
    //$('#btnShowAlert').button({icons:{primary:'ui-icon-alert'}});
    //$('#btnShowNotas').button({icons:{primary:'ui-icon-script'}});
    
    $('#txtDeudaCuotificacion,#txtNCuotaCuotificacion,#txtMontoCuotaCuotificacion').numeric({allow:"."});
    $('#txtMontoPagoCuotificacion,#txtDescuentoCuotificacion,#txtInteresCuotificacion,#txtComisionCuotificacion,#txtMoraCuotificacion,#txtGastosCobranzaCuotificacion,#txtNroCuotasCuotificacion').numeric();
    
    $('#btnShowConsultar,#btnGrabarCuotificacion').button();
    $('#btnMetaClienteCuentaUsuarioServicio').button({
        icons:{
            primary:'ui-icon-refresh'
        }
    });
    /******************/
    $('#btnShowFolder').click(function(){ $('#DialogArchivosYCarpetas').dialog('open');  });
    $('#btnShowFolder').one('click',function( ){ FilesDAO.read_directory( $('#router_directory').val(), "" ); });
    /******************/
    //$('#btnMasAccionesNotas').button().next().button({text:false,icons:{primary:''}}).parent().buttonset();
    //$('#btnMasAccionesNotas').menubutton({plain:true});
    /******************/
    $('#txtAtencionSearchBaseByCodigo').watermark("Ingrese Codigo");
    $('#txtAtencionSearchBaseByName').watermark("Ingrese Nombre");
    $('#txtAtencionSearchBaseByNumeroDocumento').watermark("Ingrese Numero Doc");
    $('#txtAtencionSearchBaseTipoDocumento').watermark("Ingrese Tipo Doc");
    $('#txtAtencionSearchBaseByPhone').watermark("Ingrese Telefono");
    $('#txtAtencionSearchBaseByNumberAccount').watermark("Ingrese Numero Cuenta");
    $('#txtAtencionSearchBaseByIdClienteCartera').watermark("Ingrese Data");    

    /*******************/

    /******************/
    /*****************/
    /*var googie1 = new GoogieSpell("../includes/googiespell/googiespell_v4_4/googiespell/", "https://www.google.com/tbproxy/spell?lang=");
    googie1.setLanguages({'es': 'Spanish'});
    googie1.dontUseCloseButtons();*/

    /*****************/
    $('#dialogEtiqueta').dialog({
        height : 120,
        autoOpen : false,
        width : 400 ,
        title : 'Crear nueva etiqueta',
        modal : true,
        buttons : {
            Cancel : function ( ) {
                $(this).dialog('close');
            },
            Grabar : function ( ) { 

            }
        }
    });
    /*****************/
    $('#dialogNotasHoy').dialog({
        height : 450,
        autoOpen : false,
        width : 750 ,
        title : 'Notas de hoy',
        buttons : {
            Cancel : function ( ) {
                $(this).dialog('close');
            }
        }
    });
    /******************/
    $('#dialogAlertaEspera').dialog({
        height : 550,
        autoOpen : false,
        width : 500 ,
        title : 'Alertas en espera',
        modal : true,
        buttons : {
            Cancel : function ( ) {
                $(this).dialog('close');
            },
            Actualizar : function ( ) {
                AtencionClienteDAO.loadInitializeAlertas();
            }
        }
    });
    /******************/

    /*****************/
    $('#dialogAlerta').dialog({
        height : 310,
        autoOpen : false,
        width : 410 ,
        title : 'Crear Alerta',
        modal : true,
        buttons : {
            Cancel : function ( ) {
                $(this).dialog('close');
                $('#dialogAlerta').find(':text,textarea').val('');
                $('#txtAbonadoAlerta').empty();
            },
            Aceptar : function ( ) {
                save_alerta();
            }
        }
    });
    $('#dialogAlertaTelefono').dialog({
        height : 310,
        autoOpen : false,
        width : 410 ,
        title : 'Crear Alerta',
        modal : true,
        buttons : {
            Cancel : function ( ) {
                $(this).dialog('close');
                $('#dialogAlertaTelefono').find(':text,textarea').val('');
                $('#txtAbonadoAlertaTelefono').empty();
            },
            Aceptar : function ( ) {
                var telefono=$('#cboNumeroAlertaTelefono').val();
                if (telefono=='0'){
                    alert('Seleccione un Telefono');
                    return false;
                }
                save_alerta_telefono();
                // save_agenda_neotel_telefono();
            }
        }
    });    

//~ Vic I
    $('#dialogSaldoInicialVigente').dialog({
        height : 310,
        autoOpen : false,
        width : 600 ,
        title : 'Saldo Inicial Vigente',
        modal : true,
        buttons : {
            Aceptar : function ( ) {
                $(this).dialog('close');
            }
        }
    });

    $('#dialogCuotas').dialog({
        height : 250,
        autoOpen : false,
        width : 500 ,
        title : 'Cuotas',
        modal : true,
        buttons : {
            Aceptar : function ( ) {
                $(this).dialog('close');
            }
        }
    });

    $('#dialogFiadores').dialog({
        height : 300,
        autoOpen : false,
        width : 900 ,
        title : 'Fiadores',
        modal : true,
        buttons : {
            Aceptar : function ( ) {
                $(this).dialog('close');
            }
        }
    });
//~ Vic F

    /******************/
    $('#dialogNotas').dialog({
        height : 310,
        autoOpen : false,
        width : 410 ,
        title : 'Crear Nota',
        modal : true,
        buttons : {
            Cancel : function ( ) {
                $(this).dialog('close');
                $('#dialogNotas').find(':text,textarea').val('');
                $('#txtAbonadoNota').empty();
            },
            Aceptar : function ( ) {
                save_nota();
            }
        }
    });

    $('#DialogEditTelefonoCartera').dialog({
        height : 130,
        autoOpen : false,
        position : 'center',
        width : 300 ,
        title : 'Modificar Numero de Cartera',
        buttons : {
            Cancel : function ( ) {
                $(this).dialog('close');
                $(this).find(':text,:hidden').val('');
            },
            Actualizar : function ( ) {
                update_edit_numero_telefono();
            }
        }
    });


    $('#DialogAddDireccionCartera').dialog({
        height : 330,
        autoOpen : false,
        position : 'center',
        width : 450 ,
        title : 'Agregar Direccion',
        buttons : {
            Cancel : function ( ) {
                $(this).dialog('close');
                //$(this).find(':text,:hidden,textarea').val('');
                //$(this).find('select').val('0');
            },
            Grabar : function ( ) {
                save_direccion_atencion_cliente();
            },
            Actualizar : function ( ) {
                update_direccion_atencion_cliente();
            }
        }
    });
    //DialogBuscarTelefono                                  
    $('#DialogBuscarTelefono').dialog({
        height : 330,
        autoOpen : false,
        position : 'center',
        width : 960 ,
        title : 'Buscar telefonos',
        buttons : {
            Cancel : function ( ) {
                $(this).dialog('close');
            },
            Importar : function ( ) {
                atencion_cliente_importar_telefonos_gestion_actual();
            }
        }
    });
    //Ayuda Gestion
    $('#DataReadFileAndText').dialog({
        height : 400,
        autoOpen : false,
        width : 300 ,
        title : 'Speech y Argumentario',
        buttons : {
            Cancel : function ( ) {
                $(this).dialog('close');
            }
        }
    });

    $('#DialogNuevoCorreo').dialog({
        height : 200,
        autoOpen : false,
        width : 500 ,
        title : 'Nuevo Correo',
        modal : true,
        buttons : {
            Cancel : function ( ) {
                $(this).dialog('close');
            },
            Grabar : function ( ){
                guardar_correo();
            }
        }
    });

    //MANTTELF
    $('#DialogGestionTelefonos').dialog({
        height : 395,
        autoOpen : false,
        width : 650 ,
        title : 'Gestion de Telefono',
        modal : true,
        buttons : {
            
        }
    });
    //MANTTELF

    // CAMBIO 20-06-2016
    $('#DialogGestionDireccion_opcion').dialog({
        height : 215,
        autoOpen : false,
        width : 1080 ,
        title : 'Gestion de Direccón',
        modal : true,
        buttons : {
            
        }
    });
    // CAMBIO 20-06-2016

    $('#DialogArchivosYCarpetas').dialog({
                    height : 450,
                    autoOpen : false,
                    width : 750 ,
                    title : 'CARPETAS Y ARCHIVOS',
                    buttons : {
                            Cancel : function ( ) {
                                    $(this).dialog('close');
                                }
                            }
                });

    $('#DialogNuevoHorarioAtencion').dialog({
        height : 200,
        autoOpen : false,
        width : 500 ,
        title : 'Nuevo Horario de Atencion',
        modal : true,
        buttons : {
            Cancel : function ( ) {
                $(this).dialog('close');
            },
            Grabar : function ( ){
                guardar_horario_atencion();
            }
        }
    });
    $('#divTabCuentaDetalle').tabs();

    $('#table_aval_telf').dialog({
        height : 310,
        autoOpen : false,
        width : 410 ,
        title : 'Telefonos',
        modal : true,
        buttons : {
            Cancel : function ( ) {
                $(this).dialog('close');
            }
        }
    });
    $('#Dialoggestiontelefonos_cobranzas').dialog({
        height : 290,
        autoOpen : false,
        width : 1120 ,
        title : 'Gestionar Teléfonos del Cliente',
        modal : true,
        buttons : {
            Cancel : function ( ) {
                $(this).dialog('close');
            }   
        }
    });
    $('#Dialoggestiontelefonos_cobranzas_save').dialog({
        height : 321,
        autoOpen : false,
        width : 350 ,
        title : 'Agregar Teléfono del Cliente',
        modal : true,
        buttons : {
            Cancel : function ( ) {
                $(this).dialog('close');
            },
            Grabar : function ( ){
                save_telf_andina();
            }
        }
    });
    $('#Dialoggestiontelefonos_cobranzas_edit').dialog({
        height : 366,
        autoOpen : false,
        width : 350 ,
        title : 'Editar Teléfono del Cliente',
        modal : true,
        buttons : {
            Cancel : function ( ) {
                $(this).dialog('close');
            },
            Editar : function ( ){
                update_telf_andina();
            }
        }
    });

    $('#Dialoggestiondireccion_cobranzas').dialog({
        height : 366,
        autoOpen : false,
        width : 1288 ,
        title : 'Gestionar Dirección del Cliente',
        modal : true,
        buttons : {
            Cancel : function ( ) {
                $(this).dialog('close');
            }
        }
    });
    $('#Dialoggestiondireccion_cobranzas_save').dialog({
        height : 448,
        autoOpen : false,
        width : 350 ,
        title : 'Agregar Dirección del Cliente',
        modal : true,
        buttons : {
            Cancel : function ( ) {
                $(this).dialog('close');
            },
            Grabar : function ( ){
                save_direccion_andina();
            }
        }
    });
    $('#Dialoggestiondireccion_cobranzas_edit').dialog({
        height : 448,
        autoOpen : false,
        width : 350 ,
        title : 'Editar Dirección del Cliente',
        modal : true,
        buttons : {
            Cancel : function ( ) {
                $(this).dialog('close');
            },
            Editar : function ( ){
                modificar_direccion_andina();
            }
        }
    });
    $('#Dialoggestioncorreo_cobranzas').dialog({
        height : 366,
        autoOpen : false,
        width : 516 ,
        title : 'Gestionar Correos del Cliente',
        modal : true,
        buttons : {
            Cancel : function ( ) {
                $(this).dialog('close');
            }
        }
    });


    $('#Dialoggestionmail_cobranzas_save').dialog({
        height : 250,
        autoOpen : false,
        width : 350 ,
        title : 'Agregar Email del Cliente',
        modal : true,
        buttons : {
            Cancel : function ( ) {
                $(this).dialog('close');
            },
            Grabar : function ( ){
                save_mail_andina();
            }
        }
    });
    $('#Dialoggestionmail_cobranzas_edit').dialog({
        height : 250,
        autoOpen : false,
        width : 350 ,
        title : 'Editar Email del Cliente',
        modal : true,
        buttons : {
            Cancel : function ( ) {
                $(this).dialog('close');
            },
            Editar : function ( ){
                UPDATE_Correo();
            }
        }
    });
    $('#Dialog_contacto_cobranzas').dialog({
        height : 360,
        autoOpen : false,
        width : 920 ,
        title : 'CONTACTOS',
        modal : true,
        buttons : {
            Cancel : function ( ) {
                $(this).dialog('close');
            },
            Editar : function ( ){
                UPDATE_Correo();
            }
        }
    });
    $('#Dialog_contacto_telefono').dialog({
        height : 307,
        autoOpen : false,
        width : 839 ,
        title : 'TELEFONO CONTACTO',
        modal : true,
        // buttons : {
        //     Cancel : function ( ) {
        //         $(this).dialog('close');
        //     },
        //     Editar : function ( ){
        //         UPDATE_Correo();
        //     }
        // }
        close: function(ev, ui) {
            $("#telf_contacto tbody").empty();
            $("#contactopers_nro").val("");
            $("#pers_origen").val("");
            $("#pers_tip_telf").val("");
            $("#pers_lin_telf").val("");
            $("#idenviardata").val("AGREGAR")
        }
    });

    $('#Dialog_contacto_correo').dialog({
        height : 307,
        autoOpen : false,
        width : 359 ,
        title : 'TELEFONO CORREO',
        modal : true,
        // buttons : {
        //     Cancel : function ( ) {
        //         $(this).dialog('close');
        //     },
        //     Editar : function ( ){
        //         UPDATE_Correo();
        //     }
        // }
        close: function(ev, ui) {
            // $("#telf_contacto tbody").empty();
            // $("#contactopers_nro").val("");
            // $("#pers_origen").val("");
            // $("#pers_tip_telf").val("");
            // $("#pers_lin_telf").val("");
            // $("#idenviardata").val("AGREGAR")
        }
    });

//PIRO
    $('#cboCampania').change(function(){
        $('#txtCampoCodigoSearch2').val('');
        $('#txtTerritorioOficina').val('');
        $('#txtOficina').val('');
        $('#txtCliente').val('');
        $('#txtRuc').val('');
        $('#txtDeudaTotal').val('');
        $('#txtClasificacion').val('');
        $('#txtProvision').val('');
        $('#txtPosicionSistemaFinanciero').val('');
        $('#txtTipoCredito').val('');
        $('#txtTramo').val('');
        $('#txtPersonaCargo1').val('');
        $('#txtPersonaCargo2').val('');
        $('#txtNivelDeRiesgo').val('');
        $('#col2,#col1').css({'display':''});
        $('#txtTerritorio').val('');
    });

    $('#cboCartera').change(function(){
        $('#txtCampoCodigoSearch2').val('');
        $('#txtTerritorioOficina').val('');
        $('#txtOficina').val('');
        $('#txtCliente').val('');
        $('#txtRuc').val('');
        $('#txtDeudaTotal').val('');
        $('#txtClasificacion').val('');
        $('#txtProvision').val('');
        $('#txtPosicionSistemaFinanciero').val('');
        $('#txtTipoCredito').val('');
        $('#txtTramo').val('');
        $('#txtPersonaCargo1').val('');
        $('#txtPersonaCargo2').val('');
        $('#txtNivelDeRiesgo').val('');
        $('#txtTerritorio').val('');
    });

    
    $('#chkActNo').click(function(){
        $('#txtNuevoDomiActualizacionBaseDatos').removeAttr('readonly');
        $('#txtDireccVisi2iActualizacionBaseDatos').removeAttr('readonly');
        $('#txtNuevosTelefonosActualizacionBaseDatos').removeAttr('readonly');
    });
    $('#chkActSi').click(function(){
        $('#txtNuevoDomiActualizacionBaseDatos').attr({'readonly':'true'});
        $('#txtDireccVisi2iActualizacionBaseDatos').attr({'readonly':'true'});
        $('#txtNuevosTelefonosActualizacionBaseDatos').attr({'readonly':'true'});
        $('#txtNuevoDomiActualizacionBaseDatos').val("");
        $('#txtDireccVisi2iActualizacionBaseDatos').val("");
        $('#txtNuevosTelefonosActualizacionBaseDatos').val("");
    });

    
    $('#txtOtrosGiroNegocio').click(function(){
        $('input:radio[name=chkGiroNegocio]').removeAttr('checked');
        $('#txtOtrosGiroNegocio').removeAttr('readonly');
    });
    $('input:radio[name=chkGiroNegocio]').click(function(){
        $('#txtOtrosGiroNegocio').attr({'readonly':'true'});
        $('#txtOtrosGiroNegocio').val("");

    });
    
   
    $('#txtEspecificarOtros4').click(function(){
        $('input:radio[name=chkComoAfrontaraPAgo]').removeAttr('checked');
        $('#txtEspecificarOtros4').removeAttr('readonly');

    });
    $('input:radio[name=chkComoAfrontaraPAgo]').click(function(){
        $('#txtEspecificarOtros4').val("");

    });
//FIN PIRO

    $("#idenviardata").click(function(){

        var idpersona=""
        var nro_telf=""
        var ori_telf=""
        var tip_telf=""
        var lin_telf=""

        idpersona=$("#hpidpersona").val();
        idtelefono_pers=$("#idtelefono_pers").val();
        nro_telf=$("#contactopers_nro").val();
        ori_telf=$("#pers_origen").find(":selected").val();
        tip_telf=$("#pers_tip_telf").find(":selected").val();
        lin_telf=$("#pers_lin_telf").find(":selected").val();

        if (nro_telf=="") {
            alert("Ingresar Nro");
            return false;
        }

        if ($(this).val()=='AGREGAR') {           
            AtencionClienteDAO.insertar_contacto_tefl(idpersona,nro_telf,ori_telf,tip_telf,lin_telf);
        }else if ($(this).val()=='MODIFICAR') {
            AtencionClienteDAO.modificar_contacto_tefl(idtelefono_pers,nro_telf,ori_telf,tip_telf,lin_telf)
        }

        // AtencionClienteDAO.Listar_Contactos_telf(idpersona);

    });

    $("#idlimpiardata").click(function(){
        // $("#hpidpersona").val("");
        $("#contactopers_nro").val("");
        $("#pers_origen").val("");
        $("#pers_tip_telf").val("");
        $("#pers_lin_telf").val("");
        $("#idenviardata").val("AGREGAR")
    });
    $("#idlimpiarmail").click(function(){
        // $("#hpidpersona").val("");

        $("#idemail_pers").val("");
        $("#email").val("");
        $("#idenviarmail").val("AGREGAR")
    });

    $("#idenviarmail").click(function(){
    var idpersona=""
    var nro_telf=""


    idpersona=$("#hpidpersona").val();
    idemail_pers=$("#idemail_pers").val();
    email=$("#email").val();

    if (email=="") {
        alert("Ingresar email");
        return false;
    }


    if ($(this).val()=='AGREGAR') {           
        AtencionClienteDAO.insertar_contacto_mail(idpersona,email);
    }else if ($(this).val()=='MODIFICAR') {
        AtencionClienteDAO.modificar_contacto_mail(idemail_pers,email);
    }
});


});
/*********************************************************************************************************************************************************************************/
/*NEOTEL*/
cerrarNeotel=function(){
    if($('#flg_modo_neotel').val()=='1'){//solo cierra neotel si se esta en modo neotel :P
        neotelDAO.Logout();
        alert('Cerrando Sesion NEOTEL');
    }else if($('#txtUsuarioN').val()!=''){
        neotelDAO.Logout();
        alert('Cerrando Sesion NEOTEL');        
    }
}
verifica_neotel=function(){
    if($.trim($('#txtUsuarioN').val())==''){
        $('#layerMessage').html(templates.MsgInfo('Ingrese Usuario NEOTEL','250px'));
        $('#layerMessage').effect('pulsate',{},1500,function(){ $(this).empty(); });
        return false;
    }
    $('#dialog_usuario_neotel').fadeOut();

    neotelDAO.getStatus(function(){//si status es OK ejecutara el function
        //oculta opciones de cobrast q ya no se usaran
        $('#barraflotante').fadeOut('slow');;
        $('#tableMenu').animate({marginTop: '-=38'},500);
        $('.divContentMain').animate({marginBottom: '+=40'},500);
        $('#menufiltros,#slidefiltros,#tabAC1Globales,#tabAC1Apoyo,#tabAC1MatrizBusqueda').fadeOut('slow');;
        //
        inicia_modo_neotel();
    });
}
verifica_neotel_manual=function(){
    if($.trim($('#txtUsuarioManualNeotel').val())==''){
        $('#layerMessage').html(templates.MsgInfo('Ingrese Usuario NEOTEL','250px'));
        $('#layerMessage').effect('pulsate',{},1500,function(){ $(this).empty(); });
        return false;
    }
    
    neotelDAO.getStatusByUser(function(){//si status es OK ejecutara el function
        //oculta opciones de cobrast q ya no se usaran
        _displayBeforeSend('COMIENZE CON SU GESTIÓN',320);
        setTimeout(function(){
            _noneBeforeSend();
        },1500);
        $('#dlgManualNeotel').dialog('close');
        //botones de gestion
        $('#btnAtencionClientePhoneCall,#btnAtencionClientePhoneHungup').css('display','none');
        $('#btnAtencionClientePhoneCallNeotel,#btnAtencionClientePhoneHungupNeotel').css('display','block');

        neotelDAO.ponerCampaniaManual($('#txtCampaniaManualNeotel').val(),'txtUsuarioManualNeotel');


    },'txtUsuarioManualNeotel');
}
inicia_modo_neotel=function(){
    //setTimeout(fucntion(){"evaluaPosition()",2000);//cada 2 seg corre
    evaluaPosition();
    //habilita o modifica opciones exclusivas de neotel
    $('#barra_neotel').fadeIn('fast');
    $('#li_modo_neotel').css('display','none');
    $('#flg_modo_neotel').val('1');
    $('#flg_guardar_agenda').val('0');
    $('#txtFechaAgendaN').val('');
    $('#txtIdLlamadaN').val('');
    //$('#closeWindowCobrastOverlay').fadeIn();
    //_displayBeforeSend('Esperando Llamada',250);
};
evaluaPosition=function(){
    //setIntervalNeotel=window.clearInterval(setIntervalNeotel);  
    $('#closeWindowCobrastOverlay').fadeIn();
    _displayBeforeSend('Esperando Llamada',250);      
    neotelDAO.getPosition(function(obj){
        html="";
        $.each(obj,function(key,data){
            html+=key+" : "+data+"<br>";
        });
        $('#resumePositionN').html(html);
        
        status=obj['STATUS'];
        anexo=obj['DEVICE'];
        usuario=obj['USUARIO'];
        campania=obj['SAL_CAMPANA_DEFAULT'];
        subtipodescanso=obj['SUBTIPO_DESCANSO'];
        estadocrm=obj['ESTADO_CRM'];
        base=obj['BASE'];
        idcontacto=obj['IDCONTACTO'];
        data=obj['DATA'];
        telefono=obj['TELEFONO'];
        idllamada=obj['IDLLAMADA'];
        direccion=obj['DIRECCION'];

        desc_camp={"0":"<i class='ui-icon ui-icon-circle-close ilb'></i>","7":"PRUEBA","5":"BBVA 5","4":"BBVA 4","3":"BBVA 3","12":"NATURAL"};//desc_campania
//        desc_std={"0":"...","3":"Descanso","4":"Tiempo Administrativo","53":"Capacitacion","54":"RT4","55":"Sabre/Notify","56":"Emergencia","57":"Briefing",
//            "58":"SSHH","59":"Consulta Supervisor","60":"FeedBack","61":"Topico","62":"Almuerzo","63":"Esperando Contingencia"};//desc_subtipodescanso
        desc_std={"0":"...","1":"Descanso","2":"Tiempo Administrativo","3":"Capacitacion",
            "8":"SSHH","9":"Consulta Supervisor","10":"FeedBack","11":"Topico","12":"Almuerzo","13":"Esperando Contingencia","4":"Break"};//desc_subtipodescanso            

        $('#tagStatusN').html(status=='OK'?'<i class="ui-icon ui-icon ui-icon-circle-check ilb"></i>':'<i class="ui-icon ui-icon ui-icon-circle-close ilb"></i>');
        $('#tagAnexoN').html(anexo.substring(4));
        $('#tagCampaniaN').html(desc_camp[campania]);
        $('#tagPausaN').html(desc_std[subtipodescanso]);
        $('#tagEstadoCrmN').html(estadocrm);
        $('#tagUsuarioN').html(usuario);
        $('#tagNumLlamN').html(telefono==''?'...':telefono);
        $('#tagIdLlamadaN').html(idllamada==''?'...':idllamada);
        $('#txtIdLlamadaN').val(idllamada);

        $('#txtBaseN').val(base);
        $('#txtIdContactoN').val(idcontacto);
        $('#txtDataN').val(data);

        if(estadocrm=='GetContact'){
            //setIntervalNeotel=window.clearInterval(setIntervalNeotel);
            if(direccion=='entrante'){
                $('#layerMessage').html(templates.MsgInfo('LLAMADA ENTRANTE','250px'));
                $('#layerMessage').effect('pulsate',{},1500,function(){ $(this).empty(); });
                _activeTabLayer('table_tab_AC1','tabAC1','#tabAC1Busqueda','content_table_tab_AC1','layerTabAC1','layerTabAC1Busqueda');
            }
            showDataCliente(data,$('#hdCodServicio').val());
            ponerShowingContact(base,idcontacto,data,telefono);
        }else{
            inicia_modo_neotel();            
        }
    })
}

showDataCliente = function(data,idservicio){
    neotelDAO.showDataCliente(data,idservicio)
}

ponerShowingContact=function(base,idcontacto,data,telefono){
    neotelDAO.ponerShowingContact(base,idcontacto,data,function(){
        $('#closeWindowCobrastOverlay').fadeOut();
        _noneBeforeSend();
        $('#tagEstadoCrmN').html('ShowingContact');

        AtencionClienteDAO.LoadDataCliente(data);//carga data del contacto
        neotelDAO.getIdTelefonoCliente(telefono,data,//busco idtelefono 
            function(obj){//funcionOK, pongo valores del telefono
                $('#txtAtencionClienteNumeroCall').val(obj['numero']);
                $('#txtAtencionClienteNumeroCall').attr('prefixs',obj['prefijos']);
                $('#txtAtencionClienteNumeroCall').attr('line',obj['linea']);
                $('#HdIdTelefono').val(obj['idtelefono']);
            },
            function(obj){//funcionFail, mando mensaje q telefono no existe
                $('#layerMessage').html(templates.MsgInfo(obj.msg,'250px'));
                $('#layerMessage').effect('pulsate',{},1500,function(){ $(this).empty(); });
            }
        );
    });
}
save_llamada_neotel=function(){
    if($('#flg_modo_neotel').val()=='1'){//solo guardar alerta si se esta en modo neotel :P
        if($('#flg_guardar_agenda').val()=='1'){
            save_agenda_neotel();
        }else if( $('#cbLlamadaEstado option:selected').attr('flg_volver_llamar')!='1' ){
            setCloseContact();
        }

        ponerCrmAvailable(function(){
            inicia_modo_neotel();
            /*$('#flg_guardar_agenda').val('0');
            $('#txtFechaAgendaN').val('');
            $('#closeWindowCobrastOverlay').fadeIn();*/
        });
    }
}
save_agenda_neotel=function(){
    if($('#flg_modo_neotel').val()=='1'){//solo guardar alerta si se esta en modo neotel :P
        neotelDAO.AddScheduleCall();
    }
}
save_agenda_neotel_telefono=function(){
    neotelDAO.AddScheduleCallTelefono();
}
setCloseContact=function(){
    neotelDAO.setCloseContact();
}
ponerCrmUnAvailable=function(){
    neotelDAO.ponerCrmUnAvailable();
}
ponerCrmAvailable=function(successx){
    neotelDAO.ponerCrmAvailable(successx);
}
ponerLogoutCampania=function(){
    neotelDAO.ponerLogoutCampania();
}
ponerCampania=function(idcampania){
    neotelDAO.ponerCampania(idcampania);
}
ponerPausa=function(idsubtipo_descanso){
    neotelDAO.ponerPausa(idsubtipo_descanso);
}
ponerUnPausa=function(){
    neotelDAO.ponerUnPausa();
}
/****fin neotel****/
link_export_refinan = function ( ) {
    
    var fecha_inicio = $('#txtFechaInicioRptRefinan').val();
    var fecha_fin = $('#txtFechaFinRptRefinan').val();
    
    if( fecha_inicio == '' ) {
        alert("Ingrese fecha inicio");
        return false;
    }
    
    if( fecha_fin == '' ) {
        alert("Ingrese fecha fin");
        return false;
    }
        
    window.location.href="../rpt/excel/refinanciamiento.php?Cartera=&Servicio="+$('#hdCodServicio').val()+"&FechaInicio="+fecha_inicio+"&FechaFin="+fecha_fin; 
    
}
listar_multiples_cartera_ref = function ( ) {
    ReporteDAO.ListarTodasCartera(
        function ( obj ) {
            
            var html='';

            for( i=0;i<obj.length;i++ ) {

                html+='<tr style="display:block;" >';
                    html+='<td title="Campania '+obj[i].campania+'" style="width:220px;padding:2px 0;" align="center" class="ui-widget-content" >'+obj[i].nombre_cartera+'</td>';
                    html+='<td style="width:20px;padding:2px 0;" align="center" class="ui-widget-content" ><input type="checkbox" name="rd_RP3_pagos_comercial_l" value="'+obj[i].idcartera+'" /></td>';
                html+='</tr>';

            }

            $('#tableCarterasRefinanciamiento').html(html);
            
        },
        ''
    );
}
listar_departamento = function ( ) {
    var html = '';
    AtencionClienteDAO.Ubigeo.Listar.Departamento( 
        function ( obj ) {
            
            html += '<option value="">--Seleccione--</option>';
            for( i=0;i<obj.length;i++ ) {
                html += '<option value="'+obj[i].departamento+'">'+obj[i].departamento+'</option>';
            }
            
        } 
    ); 
    
    //$('#txtDepartamentoAtencionCliente,#txtCampoDireccionDepartamento').autocomplete("option","source",dep);
    $('#txtDepartamentoAtencionCliente,#txtCampoDireccionDepartamento').html(html);
    
}
listar_provincia = function ( xdep, xidtxt ) {
    if( $.trim( xdep  ) == '' ) {
        return false;
    }
    var html = '';
    html+='<option value="">--Selecione--</option>';
    AtencionClienteDAO.Ubigeo.Listar.Provincia( 
        $.trim( xdep ),
        function ( obj ) {
            for( i=0;i<obj.length;i++ ) {
                html+='<option value="'+obj[i].provincia+'">'+obj[i].provincia+'</option>';
            }
        } 
    ); 
    var id = '#'+xidtxt;
    $(id).html(html);
    
}
listar_distrito = function ( xdep, xprov, xidtxt ) {
    
    if( $.trim( xdep  ) == '' ) {
        return false;
    }
    
    if( $.trim( xprov ) == '' ) {
        return false;
    }
    
    var html='';
    html+='<option value="">--Seleccione--</option>';
    AtencionClienteDAO.Ubigeo.Listar.Distrito( 
        $.trim(xdep) , $.trim( xprov ) ,
        function ( obj ) {
            for( i=0;i<obj.length;i++ ) {
                html+='<option value="'+obj[i].distrito+'">'+obj[i].distrito+'</option>';
            }
        } 
    ); 
    var id = '#'+xidtxt;
    $(id).html(html);
    
}
table_operaciones_autowidth = function ( ) {
    var width=$('#PanelTableOperacionAtencionCliente').width();
    
    if( width==700 || width==680 ) {
        $('#PanelTableOperacionAtencionCliente,#PanelTableDatosAdicionalesCliente,#PanelTableDatosAdicionalesCuentaAtencionCliente,#PanelTableDatosAdicionalesOperacionAtencionCliente').width(900);
    }else if( width==900 || width==880 ){
        $('#PanelTableOperacionAtencionCliente,#PanelTableDatosAdicionalesCliente,#PanelTableDatosAdicionalesCuentaAtencionCliente,#PanelTableDatosAdicionalesOperacionAtencionCliente').width(700);
    }

}
get_data_cuenta = function(idcuenta)
{
    /*$('#tb_adicional_cuenta').empty();
    $('#tb_detalle_factura_operacion').empty();
    listar_cuenta($('#hdCodServicio').val(),$('#IdCartera').val(),idcuenta);*/
    $('#tabAC2CuentaPagos').trigger('click');
}
showCrear = function ( value ) {
    if(value=='alerta'){
        show_box_model_alerta();
    }
    $('#cbMenuCrear').val('crear');
}
show_box_model_alerta = function ( ) {
    var idClienteCartera=$('#IdClienteCarteraMain').val();
    var Cliente=$('#txtResultadoNombreCodigoCliente').val();
    if(idClienteCartera==''){
        return false;
    }
    $('#txtAbonadoAlerta').text(Cliente);
    $('#dialogAlerta').dialog('open');
}

show_box_model_alerta_telefono = function(){
    var idClienteCartera=$('#IdClienteCarteraMain').val();
    var Cliente=$('#txtResultadoNombreCodigoCliente').val();
    if(idClienteCartera==''){
        return false;
    }
    $('#txtAbonadoAlertaTelefono').text(Cliente);
    AtencionClienteDAO.listarAlertaTelefono();    
    $('#dialogAlertaTelefono').dialog('open');   

}
//~ Vic I
show_saldo_inicial_vigente = function ( ) {
    var idClienteCartera=$('#IdClienteCarteraMain').val();

    if(idClienteCartera=='') {
        return false;
    }

    AtencionClienteDAO.select_saldo_inicial(idClienteCartera);

    $('#dialogSaldoInicialVigente').dialog('open');
}

show_popup_cuotas = function ( ) {
    var idClienteCartera=$('#IdClienteCarteraMain').val();

    if(idClienteCartera=='') {
        return false;
    }

    AtencionClienteDAO.select_contrato_cuota(idClienteCartera);

    $('#dialogCuotas').dialog('open');
}

show_popup_fiadores = function ( ) {
    var idClienteCartera=$('#IdClienteCarteraMain').val();

    if(idClienteCartera=='') {
        return false;
    }

    AtencionClienteDAO.select_contrato_fiador(idClienteCartera);

    $('#dialogFiadores').dialog('open');
}

txt_estado_observacion = function () {
    var txtObserva = $('#cbLlamadaEstado option:selected').attr('estado_observa');
    var observaVal = $("#txtObservacionLlamada").val();
    $("#txtObservacionLlamada").val($.trim(txtObserva + " " + observaVal));
}

//~ Vic F

show_agendar = function ( ) {
    /*var cliente=$('#txtResultadoNombreCodigoCliente').val();
    var idClienteCartera=$('#IdClienteCartera').val();
    if(idClienteCartera==''){
        return false;
    }
    $('#tdAbonadoAgendar').text(cliente);
    $('#IdClienteCarteraAgendar').val(idClienteCartera);*/
    $('#tabAC1Agendar').trigger('click');
}
show_box_model_alertas_sin_atender = function ( ) {
    $('#dialogAlertaEspera').dialog('open');
}
save_alerta = function ( ) {
    var idClienteCartera=$('#IdClienteCartera').val();
    if(idClienteCartera==''){
        return false;
    }
    
    var rs=validacion.check([
    {
        id:'txtFechaAlerta',
        required:true,
        errorRequiredFunction:function( ){
            $('#AlertaLayerMessage').html(templates.MsgError('Ingrese fecha de alerta','250px'));
            $('#AlertaLayerMessage').effect('pulsate',{},'slow',function(){
                $(this).empty();
            }); 
        }
    },

    {
        id:'txtDescripcionAlerta',
        required:true,
        isAlphaNumeric:true,
        errorRequiredFunction:function( ){
            $('#AlertaLayerMessage').html(templates.MsgError('Ingrese descripcion de alerta','250px'));
            $('#AlertaLayerMessage').effect('pulsate',{},'slow',function(){
                $(this).empty();
            }); 
        },
        errorAlphaNumericFunction:function( ){
            $('#AlertaLayerMessage').html(templates.MsgError('Ingrese solo letras y numeros','260px'));
            $('#AlertaLayerMessage').effect('pulsate',{},'slow',function(){
                $(this).empty();
            }); 
        }
    }
    ]);
    
    if( rs ){
        var rsC=confirm("Verifique si los datos ingresados son los correctos");
        if( rsC ){
            AtencionClienteDAO.save_alerta(idClienteCartera);
        }
    }
}
save_alerta_telefono = function ( ) {
    var idClienteCartera=$('#IdClienteCartera').val();
    if(idClienteCartera==''){
        return false;
    }
    
    var rs=validacion.check([
    {
        id:'txtFechaAlertaTelefono',
        required:true,
        errorRequiredFunction:function( ){
            $('#AlertaTelefonoLayerMessage').html(templates.MsgError('Ingrese fecha de alerta','250px'));
            $('#AlertaTelefonoLayerMessage').effect('pulsate',{},'slow',function(){
                $(this).empty();
            }); 
        }
    },

    {
        id:'txtDescripcionAlertaTelefono',
        required:true,
        isAlphaNumeric:true,
        errorRequiredFunction:function( ){
            $('#AlertaTelefonoLayerMessage').html(templates.MsgError('Ingrese descripcion de alerta','250px'));
            $('#AlertaTelefonoLayerMessage').effect('pulsate',{},'slow',function(){
                $(this).empty();
            }); 
        },
        errorAlphaNumericFunction:function( ){
            $('#AlertaTelefonoLayerMessage').html(templates.MsgError('Ingrese solo letras y numeros','260px'));
            $('#AlertaTelefonoLayerMessage').effect('pulsate',{},'slow',function(){
                $(this).empty();
            }); 
        }
    }
    ]);
    
    if( rs ){
        var rsC=confirm("Verifique si los datos ingresados son los correctos");
        if( rsC ){
            AtencionClienteDAO.save_alerta_telefono(idClienteCartera);
        }
    }
}
cancel_agenda = function ( ) {
    $('#layerFormAtencionAgendar').find(':text').val('');
    $('#layerFormAtencionAgendar').find('select').val(0);
    $('#txtObservacionAgendar').val('');
}
activar_row = function(row)
{
    // $(row).parent().find('td').css('background','#f7f8fc');
    // $(row).find('td').css('background','#f9f9d5');
}
cambioUsuario=function(marcacion){
    if(marcacion=="0"){
        $('#btnAtencionClienteShowAnexoNeotel').css('display','block');
        $('#btnAtencionClienteShowAnexo').css('display','none');
        $('#btnAtencionClientePhoneHungupNeotel').css('display','block');        
        $('#btnAtencionClientePhoneHungup').css('display','none');
        $('#btnAtencionClientePhoneCallNeotel').css('display','block');
        $('#btnAtencionClientePhoneCall').css('display','none');        
    }else{
        $('#btnAtencionClienteShowAnexoNeotel').css('display','none');
        $('#btnAtencionClienteShowAnexo').css('display','block');
        $('#btnAtencionClientePhoneHungupNeotel').css('display','none');        
        $('#btnAtencionClientePhoneHungup').css('display','block');
        $('#btnAtencionClientePhoneCallNeotel').css('display','none');
        $('#btnAtencionClientePhoneCall').css('display','block');        
    }
}
save_agenda = function ( ) {
    
    var rs=validacion.check([
    {
        id:'IdClienteCarteraMain',
        required:true,
        errorRequiredFunction:function ( ) {
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Gestione cliente','400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },

    {
        id:'txtFechaAgendar',
        required:true,
        errorRequiredFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese fecha','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    {
        id:'cbAgendarFinal',
        isNotValue:0,
        errorNotValueFunction : function ( ) {
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccion final','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    {
        id:'txtObservacionAgendar',
        required:true,
        isAlphaNumeric:true,
        errorRequiredFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese observacion de agenda','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        },
        errorAlphaNumericFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese solo letras y numeros','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    {
        id:'txtAgendarMontoCP',
        required:false,
        isDecimal:true,
        isDependence:true,
        idDependence:'txtAgendarFechaCP',
        errorRequiredFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese Monto de Compromiso Pago','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        },
        errorDecimalFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese solo numeros decimales','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        },
        errorDependenceFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese Fecha de compromiso de pago','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    }
    ]);
    
    if( rs ) {
        var rsC=confirm("Verifique si los datos son los correctos");
        if( rsC ){
            AtencionClienteDAO.save_agendado(); 
        }
    }
}
cancel_visita = function ( ) {
    $('#layerFormCampoVisita').find(':text,textarea,:hidden').not('#txtCampoFechaVisita,#txtCampoFechaRecepcion').val('');
    $('#layerFormCampoVisita').find('#lbDireccionCampo').text('');
    $('#layerFormCampoVisita').find('select').not('#cbNotificadorCampoVisita').val(0);
    
    //$('#txtCampoCodigoCliente').val('');
    $('#txtCampoNombreCodigoCliente').val('');
    $('#txtCampoNumeroDocumentoCliente').val('');
    $('#txtCampoTipoDocumentoCliente').val('');
    
    $('#IdClienteCarteraCampoMain').val('');
    $('#IdClienteCampoMain').val('');
    $('#CodigoClienteCampoMain').val('');
    
    $('#table_cuenta_aplicar_gestion_visita').empty();
    $('#tabCampoVisita').trigger('click');
    //$('#txtCampoCodigoSearch').val('');
    $('#txtCampoNumeroCuentaSearch').select();
    $('#txtCampoNumeroCuentaSearch').focus();
}
update_visita = function ( ) {
    
    var rs=validacion.check([
    {
        id:'HdIdTransaccionCampo',
        required:true,
        errorRequiredFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione visita a actualizar','400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },

    {
        id:'txtCampoFechaVisita',
        required:true,
        errorRequiredFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese fecha de visita','400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    //{id:'cbCampoPrioridadVisita',isNotValue:0,errorNotValueFunction : function ( ) {
    //              $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione prioridad de visita','400px'));
    //              AtencionClienteDAO.setTimeOut_hide_message();
    //          } },
    {
        id:'cbCampoFinal',
        isNotValue:0,
        errorNotValueFunction : function ( ) {
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione final de visita','400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    {
        id:'txtCampoObservacion',
        required:true,
        isAlphaNumeric:true,
        errorRequiredFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese observacion de visita','400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        },
        errorAlphaNumericFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese solo letras y numeros','400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    {
        id:'txtCampoMontoCP',
        required:false,
        isDecimal:true,
        isDependence:true,
        idDependence:'txtCampoFechaCP',
        errorRequiredFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese Monto de Compromiso Pago','400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        },
        errorDecimalFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese solo numeros decimales','400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        },
        errorDependenceFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese Fecha de compromiso de pago','400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    }
    ]);
    
    
    if( rs ) {
        var rsC=confirm("Verifique si los datos ingresados son los correctos");
        if( rsC ) {
            AtencionClienteDAO.update_visita();
        }
    }
}
save_visita = function ( ) {
    //  var idClienteCartera=$('#IdClienteCarteraCampoMain').val();
    //  var idDireccion=$('#HdCodIdCampoDireccion').val();
    //  if( idClienteCartera=='' ){
    //      alert('Seleccione Cliente');
    //      return false;   
    //  }else if( idDireccion=='' ){
    //      alert('Seleccione Direccion');
    //      return false;   
    //  }
    
    var LENGTHCuenta=$('#table_cuenta_aplicar_gestion_visita').find('input:checked').length;
    if( LENGTHCuenta==0 ) {
        alert("Seleccione cuentas");
        return false;
    }

    
    
    var rs=validacion.check([
    {
        id:'IdClienteCarteraCampoMain',
        required:true,
        errorRequiredFunction:function ( ) {
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Gestione cliente','400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },

    // {
    //     id:'cbCampoDireccionVisita',
    //     isNotValue:0,
    //     errorNotValueFunction:function ( ) {
    //         $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione direccion','400px'));
    //         AtencionClienteDAO.setTimeOut_hide_message();
    //     }
    // },
    {
        id:'txtCampoFechaVisita',
        required:true,
        errorRequiredFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese fecha de visita','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    // {
    //     id:'cbCampoFinal',
    //     isNotValue:0,
    //     errorNotValueFunction : function ( ) {
    //         $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione final de visita','400px'));
    //         AtencionClienteDAO.setTimeOut_hide_message();
    //     }
    // }
    ]);

    // if($("#cbCampoFinal").val=="" ||  $("#cbCampoFinal").val=="0" ){
    //     alert("Ingresar Estado");
    //     return false;
    // }


    var iddireccion_campo="";

    if($('#cbCampoDireccionVisita').val()==0 && $("input[name='direccion_campo']:checked").val()==undefined){
        alert('Seleccione direccion');
        return false;
    }else if($('#cbCampoDireccionVisita').val()==0 && $("input[name='direccion_campo']:checked").val()!=undefined){
        iddireccion_campo=$("input[name='direccion_campo']:checked").val();
    }else if ($('#cbCampoDireccionVisita').val()!=0 && $("input[name='direccion_campo']:checked").val()==undefined) {
        iddireccion_campo=$('#cbCampoDireccionVisita').val();
    }    
    

    // var Cuentas = '['+$('#table_cuenta_aplicar_gestion_visita').find('input:checked').map(function(){
    //     return '{"Cuenta":"'+$(this).val()+'","FechaCp":"'+$(this).parent().parent().find(":text[name='txtFechaCpCuenta']").val()+'","MontoCp":"'+($(this).parent().parent().find(":text[name='txtMontoCpCuenta']").val()).replace(/\t/g,'').replace(',','')+'","MonedaCp":"'+$(this).parent().parent().find("select[name='cbMonedaCpCuenta']").val()+'"}';
    // }).get().join(",")+']';
    var acum ="";
    $("#table_cuenta_aplicar_gestion_visita tbody tr").each(function( index ) {
        

        var tr=$(this);


        if(tr.find('td:eq(17) input[type="checkbox"]').is(":checked")){
            var idcuenta    = tr.attr('id');
            var FechaCp     = tr.find('td:eq(14) input:text[name="txtFechaCpCuenta"]').val();
            var MontoCp     = tr.find('td:eq(15) input:text[name="txtMontoCpCuenta"]').val().replace(/\t/g,'').replace(',','');
            var MonedaCp    = tr.find('td:eq(16) select[name="cbMonedaCpCuenta"]').val();

            acum=acum+'{"Cuenta":"'+idcuenta+'","FechaCp":"'+FechaCp+'","MontoCp":"'+MontoCp+'","MonedaCp":"'+MonedaCp+'"}'+','
        }

        
    });
    var Cuentas ="";
    Cuentas = "["+acum.substring(0,acum.length-1)+"]";
    // console.log(Cuentas);

    
    if( rs ){
        var rsC=confirm("Verifique si los datos ingresados son los correctos");
        if( rsC ){
            // alert(Cuentas);
            AtencionClienteDAO.save_visita(Cuentas,iddireccion_campo);
        }
    }
}
cancel_direccion = function ( ) {
    $('#layerFormCampoDireccion').find(':text,:hidden,textarea').val('');
    $('#layerFormCampoDireccion').find('select').val(0);
}
update_direccion = function ( ) {
    
    var idDireccion=$('#HdIdDireccionCampo').val();
    if( idDireccion=='' ){
        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione direccion','400px'));
        $('#'+AtencionClienteDAO.idLayerMessage).effect('pulsate',{},'slow',function(){
            $(this).empty();
        }); 
        return false;
    }
    
    var rs=validacion.check([
    {
        id:'txtCampoDireccionDireccion',
        required:true,
        isAlphaNumeric:true,
        errorRequiredFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese direccion de cliente','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        },
        errorAlphaNumericFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese solo letras y numeros','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },

    {
        id:'txtCampoDireccionDireccionReferencia',
        required:false,
        isAlphaNumeric:true,
        errorRequiredFunction:function( ){},
        errorAlphaNumericFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese solo letras y numeros','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    {
        id:'txtCampoDireccionUbigeo',
        required:false,
        isAlphaNumeric:true,
        errorRequiredFunction:function( ){},
        errorAlphaNumericFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese solo letras y numeros','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    {
        id:'txtCampoDireccionDepartamento',
        required:true,
        isAlphaNumeric:true,
        errorRequiredFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese departamento','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        },
        errorAlphaNumericFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese solo letras y numeros','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    {
        id:'txtCampoDireccionProvincia',
        required:true,
        isAlphaNumeric:true,
        errorRequiredFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese provincia','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        },
        errorAlphaNumericFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese solo letras y numeros','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    {
        id:'txtCampoDireccionDistrito',
        required:true,
        isAlphaNumeric:true,
        errorRequiredFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese distrito','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        },
        errorAlphaNumericFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese solo letras y numeros','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    {
        id:'cbCampoDireccionOrigen',
        isNotValue:0,
        errorNotValueFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione origen de direccion','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    {
        id:'cbCampoDireccionReferencia',
        isNotValue:0,
        errorNotValueFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese referencia de direccion','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    }
    ]);
    
    if( rs ){
        var rsC=confirm("Verifique si los datos ingresados son los correctos");
        if( rsC ) {
            AtencionClienteDAO.update_direccion();
        }
    }
}
save_direccion  = function ( ) {
    //  var cliente=$('#IdClienteCampoMain').val();
    //  var cartera=$('#IdCarteraCampoMain').val();
    //  if( cliente=='' ){
    //      $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione cliente','300px'));
    //      $('#'+AtencionClienteDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });    
    //      return false;
    //  }else if( cartera=='' ){
    //      $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Error al traer datos de cartera','300px'));
    //      $('#'+AtencionClienteDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });    
    //      return false;
    //  }
    var rs=validacion.check([
    {
        id:'IdClienteCampoMain',
        required:true,
        errorRequiredFunction:function ( ) {
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Gestione cliente','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },

    {
        id:'IdCarteraCampoMain',
        required:true,
        errorNotValueFunction:function ( ) {
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione cartera','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    {
        id:'txtCampoDireccionDireccion',
        required:true,
        errorRequiredFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese direccion de cliente','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    {
        id:'cbCampoDireccionReferencia',
        isNotValue:0,
        errorNotValueFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese referencia de direccion','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    }
    ]);
    
    if( rs ){
        var rsC=confirm("Verifique que los datos ingresados son los correctos");
        if( rsC ){
            AtencionClienteDAO.save_direccion();
        }
    }
}
cancel_telefono = function ( ) {
    $('#layerFormCampoTelefono').find(':text,:hidden,textarea').val('');
    $('#layerFormCampoTelefono').find('select').val(0);
}
update_telefono = function ( ) {
    //  var idTelefono=$('#HdIdTelefonoCampo').val();
    //  if( idTelefono=='' ) {
    //      $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione telefono','400px'));
    //      $('#'+AtencionClienteDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });
    //      return false;
    //  }
    var rs=validacion.check([
    {
        id:'HdIdTelefonoCampo',
        required:true,
        errorRequiredFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione telefono','400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },

    {
        id:'txtCampoTelefonoNumero',
        required:true,
        errorRequiredFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese numero telefonico','400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    {
        id:'cbCampoTelefonoOrigen',
        isNotValue:0,
        errorNotValueFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione origen de numero telefonico','400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    {
        id:'cbCampoTelefonoTipo',
        isNotValue:0,
        errorNotValueFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione tipo de numero telefonico','400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    {
        id:'cbCampoTelefonoReferencia',
        isNotValue:0,
        errorNotValueFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione referencia de numero telefonico','400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    }
    ]);
    
    if( rs ) {
        var rsC=confirm("Verifique que los datos ingresados son los correctos");
        if( rsC ){
            AtencionClienteDAO.update_telefono();
        }
    }
}
save_telefono = function ( ) {
    //  var cliente=$('#IdClienteCampoMain').val();
    //  var cartera=$('#IdCarteraCampoMain').val();
    //  if( cliente=='' ){
    //      $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione cliente','300px'));
    //      $('#'+AtencionClienteDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });    
    //      return false;
    //  }else if( cartera=='' ){
    //      $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Error al traer datos de cartera','300px'));
    //      $('#'+AtencionClienteDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });    
    //      return false;
    //  } 
    var rs=validacion.check([
    {
        id:'IdClienteCampoMain',
        required:true,
        errorRequiredFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Gestione cliente','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },

    {
        id:'IdCarteraCampoMain',
        required:true,
        errorNotValueFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione cartera','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    {
        id:'txtCampoTelefonoNumero',
        required:true,
        errorRequiredFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese numero telefonico','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    {
        id:'cbCampoTelefonoOrigen',
        isNotValue:0,
        errorNotValueFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione origen de numero telefonico','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    {
        id:'cbCampoTelefonoReferencia',
        isNotValue:0,
        errorNotValueFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione referencia de numero telefonico','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    }
    ]);

    
    if( rs ){
        var rsC=confirm("Verifique que los datos ingresados son los correctos");
        if( rsC ){
            //AtencionClienteDAO.save_telefono();
            alert("ASD");
        }
    }
}
cliente_next = function ( ) {
    
    
    var idClienteCartera=$('#IdClienteCartera').val();
    if(idClienteCartera==''){
        AtencionClienteDAO.InitClienteGestion();
    }else{
        AtencionClienteDAO.DefaultNext(idClienteCartera);
    }
}
save_telefono_atencion_cliente = function ( ) {

  
    var rs=validacion.check([
    {
        id:'idClienteMain',
        required:true,
        errorRequiredFunction:function ( ) {
            _displayBeforeSendDl('Gestione cliente',400);
        }
    },

    {
        id:'IdCartera',
        isNotValue:0,
        errorNotValueFunction:function ( ) {
            _displayBeforeSendDl('Seleccione cartera',400);
        }
    },
            
    {
        id:'txtNumero2TelefonoAtencionCliente',
        required:true,
        errorRequiredFunction:function ( ) {
            _displayBeforeSendDl('Ingrese numero',300);
        }
    },
    {
        id:'cbTipoTelefonoAtencion',
        isNotValue:0,
        errorNotValueFunction:function ( ) {
            _displayBeforeSendDl('Seleccione tipo de telefono',450);
        }
    },
    {
        id:'cbReferenciaTelefonoAtencion',
        isNotValue:0,
        errorNotValueFunction:function ( ) {
            _displayBeforeSendDl('Seleccione referencia de telefono',400);
        }
    },
    {
        id:'cbOrigenTelefonoAtencion',
        isNotValue:0,
        errorNotValueFunction:function ( ) {
            _displayBeforeSendDl('Seleccione origen de telefono',450);
        }
    }
    ]);


    if($('#table_cuenta_aplicar_gestion').find(':checked').map(function( ){ return '{"cuenta":"'+$(this).val()+'"}'; }).get().join(",")==''){
        _displayBeforeSendDl('Selecciones al menos una Cuota',400);
    }

    

    if( rs ) {
        var rsC=confirm("Verifique si los datos ingresados son los correctos");
        if( rsC ) {
            AtencionClienteDAO.save_telefono_atencion_cliente();
        }
    }
}
update_telefono_atencion_cliente = function ( ) {
    var rs=validacion.check([
    {
        id:'hdIdAddTelefonoCartera',
        required:true,
        errorRequiredFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione telefono a actualizar','400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },

    {
        id:'txtNumero2TelefonoAtencionCliente',
        required:true,
        errorRequiredFunction:function ( ) {
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese numero','400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    {
        id:'cbTipoTelefonoAtencion',
        isNotValue:0,
        errorNotValueFunction:function ( ) {
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione tipo de telefono','400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    {
        id:'cbReferenciaTelefonoAtencion',
        isNotValue:0,
        errorNotValueFunction:function ( ) {
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione referencia de telefono','400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    {
        id:'cbOrigenTelefonoAtencion',
        isNotValue:0,
        errorNotValueFunction:function ( ) {
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione origen de telefono','400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    }
    ]);
    if( rs ){
        var rsC=confirm("Verifique si los datos ingresados son los correctos");
        if( rsC ){
            AtencionClienteDAO.update_telefono_atencion_cliente();
        }
    }
}
save_direccion_atencion_cliente = function ( ) {
    
    if( $('#idClienteMain').val() == '' ) {
        _displayBeforeSendDl('Seleccione cliente...',250);
        return false;
    }
    
    if( $('#IdCartera').val() == '' ) {
        _displayBeforeSendDl('Seleccione cartera...',250);
        return false;
    }
    
    if( $('#txtDireccionAtencionCliente').val() == '' ) {
        _displayBeforeSendDl('Ingrese direccion...',250);
        return false;
    }
    
    if( $('#cbOrigenDireccionAtencionCliente').val() == '0' ) {
        _displayBeforeSendDl('Seleccione origen...',250);
        return false;
    }
    
    if( $('#cbReferenciaDireccionAtencionCliente').val() == '0' ) {
        _displayBeforeSendDl('Seleccione tipo referencia...',350);
        return false;
    }
    
    var cuentas = $('#table_cuenta_aplicar_gestion').find(':checked').map(function( ){ return $(this).val(); }).get().join(",");
    if( cuentas == '' ){
        alert("Seleccione cuentas");
        return false;
    }
    
    
    var rsC=confirm("Verifique que los datos ingresados son los correctos");
    if( rsC ){
        AtencionClienteDAO.save_direccion_atencion_cliente();
    }
    
}
update_direccion_atencion_cliente = function ( ) {

    if( $('#HdIdDireccionAtencionCliente').val() == '' ) {
        _displayBeforeSendDl('Seleccione direccion a actualizar...',400);
        return false;
    }
    
    if( $('#txtDireccionAtencionCliente').val() == '' ) {
        _displayBeforeSendDl('Ingrese direccion...',250);
        return false;
    }
    
    if( $('#cbOrigenDireccionAtencionCliente').val() == '0' ) {
        _displayBeforeSendDl('Seleccione origen...',250);
        return false;
    }
    
    if( $('#cbReferenciaDireccionAtencionCliente').val() == '0' ) {
        _displayBeforeSendDl('Seleccione tipo referencia...',350);
        return false;
    }

    
    var rsC=confirm("Verifique que los datos ingresados son los correctos");
    if( rsC ) {
        AtencionClienteDAO.update_direccion_atencion_cliente();
    }
    
}
cliente_back = function ( ) {
    var idClienteCartera=$('#IdClienteCartera').val();
    if(idClienteCartera==''){
        return false;   
    }
    AtencionClienteDAO.DefaultBack(idClienteCartera);
}
showAlert = function ( ) {
    var date=new Date();
    var hours=date.getHours();
    var minutes=date.getMinutes();
    var seconds=date.getSeconds();
    
    var countBreak=0;
    
    for( i=0;i<AlertasUsuario.length;i++ ) {
        if( countBreak>0 ) {
            break;
        }
        var time=AlertasUsuario[i].fecha_alerta.split(' ')[1].split(':');
        var alerta=AlertasUsuario[i].idalerta;
        var fecha_alerta=AlertasUsuario[i].fecha_alerta;
        var cliente_cartera=AlertasUsuario[i].idcliente_cartera;
        var idcliente=AlertasUsuario[i].idcliente;
        var cliente=AlertasUsuario[i].cliente;
        var descripcion=AlertasUsuario[i].descripcion;
        var fecha_format = AlertasUsuario[i].fecha_format;
        
        var html='';
        html+='<table>';
        html+='<tr>';
        html+='<td>';
        html+='<div>';
        html+='<table>';
        html+='<tr>';
        html+='<td colspan="2">';
        html+='<input type="hidden" id="txtMsgAlertIdAlerta" value="'+alerta+'" />';
        html+='<input type="hidden" id="txtMsgAlertFechaAlerta" value="'+fecha_alerta+'" />';
        html+='<input type="hidden" id="txtMsgAlertIdClienteCartera" value="'+cliente_cartera+'" />';
        html+='<input type="hidden" id="txtMsgAlertIdCliente" value="'+idcliente+'" />';
        html+='</td>';
        html+='</tr>';
        html+='<tr>';
        html+='<td align="right">Abonado</td>';
        html+='<td><strong>'+cliente+'</strong></td>';
        html+='</tr>';
        html+='<tr>';
        html+='<td colspan="2">';
        html+='<div style="overflow:auto;width:350px;height:150px;">';
        html+=descripcion;
        html+='</div>';
        html+='</td>';
        html+='</tr>';
        html+='<tr>';
        html+='<td align="right"><button class="btn" onclick="atender_cliente_alerta('+cliente_cartera+','+alerta+')">Atender</button></td>';
        html+='<td align="left"></td>';
        html+='</tr>';
        html+='</table>';
        html+='</div>';
        html+='</td>';
        html+='</tr>';
        html+='</table>';
        
        var opts = {
            pnotify_title: "Alerta",
            pnotify_text: html,
            pnotify_addclass: "stack-bottomright",
            //pnotify_stack: {"dir1": "up", "dir2": "left", "firstpos1": 15, "firstpos2": 15},
            pnotify_width: '450px',
            pnotify_min_height: '50px',
            pnotify_animation: {
                effect_in: 'show', 
                effect_out: 'slide'
            }
        };
                
        if( hours==time[0] && minutes==time[1] ) {
            if( AlertasUsuario[i]['estado']==1 ) {
                AlertasUsuario[i]['estado']=0;
                $.pnotify(opts);
                /*var phtml='';
                   phtml+='<tr id="'+alerta+'@">';
                      phtml+='<td align="center">'+cliente+'</td>';
                      phtml+='<td align="center">'+fecha_alerta+'</td>';
                      phtml+='<td align="center">'+descripcion+'</td>';
                      phtml+='<td align="center"><button onclick="delete_alerta(this)" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-cancel"></span></button></td>';
                      phtml+='<td align="center"><button onclick="atender_cliente_alerta_espera('+cliente_cartera+',this)" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-check"></span></button></td>';
                   phtml+='</tr>';*/
                var phtml='';
                phtml+='<tr id="'+alerta+'" >';
                phtml+='<td style="border-bottom:1px solid #6F9DD9;width:440px;">';
                phtml+='<table cellpadding="0" cellspacing="0" border="0">';
                phtml+='<tr>';
                phtml+='<td align="center" style="width:30px;height:30px;"><img src="../img/bell.png"></td>';
                phtml+='<td style="width:240px;height:30px;color:#000000;">'+cliente+'</td>';
                phtml+='<td align="center" style="width:102px;height:30px;color:#000000;">'+fecha_format+'</td>';
                phtml+='<td rowspan="2" style="width:30px;" align="center" onclick="delete_alerta('+alerta+',$(this).parent().parent().parent().parent().parent())"><span class="ui-icon ui-icon-cancel"></span></td>';
                phtml+='<td rowspan="2" stylw="width:30px;" align="center" onclick="atender_cliente_alerta_espera('+cliente_cartera+','+alerta+',$(this).parent().parent().parent().parent().parent())" ><span class="ui-icon ui-icon-check"></span></td>';
                phtml+='</tr>';
                phtml+='<tr>';
                phtml+='<td></td>';
                phtml+='<td colspan="2" style="white-space:pre-wrap;color:#808080;" >'+descripcion+'</td>';
                phtml+='</tr>';
                phtml+='</table>';
                phtml+='</td>';
                phtml+='</tr>';
                //$('#tableContentLastAlertsToday').append(phtml);
                $('#alertaLayerRecientesAtencionCliente table:first').append(phtml);
                countBreak++;
                break;
            }
        }


    }
    
    
}
delete_alerta = function ( idalerta, elementJquery ) {
    //var id=$(element).parent().parent().attr('id').split('@');
    //AtencionClienteDAO.DeleteAlerta(id[0],element);
    //AtencionClienteDAO.DeleteAlerta(idalerta,element);
    AtencionClienteDAO.DeleteAlerta(idalerta,function( obj ){
        if( obj.rst ) {
            elementJquery.remove();
        }else{
            $('#layerMessageAlerta').html(templates.MsgError(obj.msg,'200px'));
            $('#layerMessageAlerta').fadeIn('slow').fadeOut(4000);
        }
    });
}
atender_cliente_alerta_espera = function ( idClienteCartera,idalerta,elementJquery ) {
    AtencionClienteDAO.LoadDataCliente(idClienteCartera);
    delete_alerta(idalerta,elementJquery);
    $('#dialogAlertaEspera').dialog('close');
    $('#table_tab_AC1 #tabAC1Resultado').trigger('click');
}
atender_cliente_alerta = function ( idClienteCartera, idAlerta ) {
    AtencionClienteDAO.LoadDataCliente(idClienteCartera);
    //$('#tableContentLastAlertsToday').find('tr[id="'+idAlerta+'@"]').remove();
    $('#alertaLayerRecientesAtencionCliente table:first').find('tr[id="'+idAlerta+'"]').remove();
}
loadDataCampaniaGlobal = function ( element ) {
    //AtencionClienteDAO.ListarCampaniasWithIdServicioParam(element.value,AtencionClienteDAO.CampaniaMatrizBusqueda);   
    AtencionClienteDAO.ListarOperadoresWithParam(element.value,AtencionClienteDAO.OperadoresMatrizBusqueda);
}
loadDataAditionalCuenta = function (  ) {
    var id=$('#table_cuenta').getGridParam('selrow');
    /*****/
    if( id==null ) {
        return false;
    }
    /******/
    var NumeroCuenta=$("#table_cuenta").jqGrid("getRowData",id)['numero_cuenta'];
    /******/
    var Moneda=$("#table_cuenta").jqGrid("getRowData",id)['moneda'];
    /******/
    AtencionClienteDAO.DatosAdicionalesCuenta(NumeroCuenta,Moneda);
}
loadDataAditionalOperation = function ( ) {
    var id=$('#table_operaciones').getGridParam('selrow');
    /*****/
    if( id==null ) {
        return false;
    }
    /****/
    var CodigoOperacion=$("#table_operaciones").jqGrid("getRowData",id)['codigo_operacion'];
    /*****/
    //AtencionClienteDAO.DatosAdicionalesOperacion(id);
    AtencionClienteDAO.DatosAdicionalesOperacion(CodigoOperacion);
}
loadClientes_matriz_busqueda = function ( ) {
    var cartera=$('#cbCarteraApoyo').val();
    var servicio=$('#hdCodServicio').val();
    var operador=$('#cbOperadoresMatrizBusqueda').val();
    if( operador==0 ) {
        return false;
    }else if( cartera==0 ){
        return false;
    }
    $('#table_matriz_busqueda').jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_matrizBusqueda&Cartera='+cartera+'&Servicio='+servicio+'&Operador='+operador
    }).trigger('reloadGrid');
}
loadClientes_busqueda_base_by_name = function ( ) {
    var cartera=$('#cbAtencionGlobalesCartera').val();
    var servicio=$('#hdCodServicio').val();
    var usuario_servicio=$('#hdCodUsuarioServicio').val();
    var nombre=$.trim( $('#txtAtencionSearchBaseByName').val() );
    if( nombre=='' || nombre=='Ingrese Nombre' ){
        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese nombre de cliente','300px'));
        AtencionClienteDAO.setTimeOut_hide_message();
        return false;
    }
    $('#table_busqueda_base').jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_busquedaBase&Cartera='+cartera+'&Servicio='+servicio+'&UsuarioServicio='+usuario_servicio+'&Nombre='+nombre
    }).trigger('reloadGrid');
}
loadClientes_busqueda_base_by_code = function ( ) {
    
    var cartera=$('#cbAtencionGlobalesCartera').val();
    var servicio=$('#hdCodServicio').val();
    var usuario_servicio=$('#hdCodUsuarioServicio').val();
    var codigo=$.trim( $('#txtAtencionSearchBaseByCodigo').val() );
    if( codigo=='' || codigo=='Ingrese Codigo' ) {
        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese codigo de cliente','300px'));
        AtencionClienteDAO.setTimeOut_hide_message();
        return false;
    }
    $('#table_busqueda_base').jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_busquedaBase&Cartera='+cartera+'&Servicio='+servicio+'&UsuarioServicio='+usuario_servicio+'&Codigo='+codigo
    }).trigger('reloadGrid');
    
}
loadClientes_busqueda_base_by_numero_documento = function ( ) {
    var cartera=$('#cbAtencionGlobalesCartera').val();
    var servicio=$('#hdCodServicio').val();
    var usuario_servicio=$('#hdCodUsuarioServicio').val();
    var numero_documento=$.trim( $('#txtAtencionSearchBaseByNumeroDocumento').val() );
    if( numero_documento=='' || numero_documento=='Ingrese Numero Doc' ) {
        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese Numero Doc','300px'));
        AtencionClienteDAO.setTimeOut_hide_message();
        return false;
    }
    $('#table_busqueda_base').jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_busquedaBase&Cartera='+cartera+'&Servicio='+servicio+'&UsuarioServicio='+usuario_servicio+'&NumeroDocumento='+numero_documento
    }).trigger('reloadGrid');
}
loadClientes_busqueda_base_by_tipo_documento = function ( ) {
    var cartera=$('#cbAtencionGlobalesCartera').val();
    var servicio=$('#hdCodServicio').val();
    var usuario_servicio=$('#hdCodUsuarioServicio').val();
    var tipo_documento=$.trim( $('#txtAtencionSearchBaseTipoDocumento').val() );
    if( tipo_documento=='' || tipo_documento=='Ingrese Tipo Doc' ) {
        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese Tipo Doc','300px'));
        AtencionClienteDAO.setTimeOut_hide_message();
        return false;
    }
    $('#table_busqueda_base').jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_busquedaBase&Cartera='+cartera+'&Servicio='+servicio+'&UsuarioServicio='+usuario_servicio+'&TipoDocumento='+tipo_documento
    }).trigger('reloadGrid');
}
loadClientes_busqueda_base_by_phone = function ( ) {
    var cartera=$('#cbAtencionGlobalesCartera').val();
    var servicio=$('#hdCodServicio').val();
    var usuario_servicio=$('#hdCodUsuarioServicio').val();
    var telefono=$.trim( $('#txtAtencionSearchBaseByPhone').val() );
    if( telefono=='' || telefono=='Ingrese Telefono' ) {
        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese telefono','300px'));
        AtencionClienteDAO.setTimeOut_hide_message();
        return false;
    }
    $('#table_busqueda_base').jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_busquedaBase&Cartera='+cartera+'&Servicio='+servicio+'&UsuarioServicio='+usuario_servicio+'&Telefono='+telefono
    }).trigger('reloadGrid');
}
loadClientes_busqueda_base_by_number_account = function ( ) {
    var cartera=$('#cbAtencionGlobalesCartera').val();
    var servicio=$('#hdCodServicio').val();
    var usuario_servicio=$('#hdCodUsuarioServicio').val();
    var numero_cuenta=$.trim( $('#txtAtencionSearchBaseByNumberAccount').val() );
    if( numero_cuenta=='' || numero_cuenta=='Ingrese Numero Cuenta' ) {
        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese Numero Cuenta','300px'));
        AtencionClienteDAO.setTimeOut_hide_message();
        return false;
    }
    $('#table_busqueda_base').jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_busquedaBase&Cartera='+cartera+'&Servicio='+servicio+'&UsuarioServicio='+usuario_servicio+'&NumeroCuenta='+numero_cuenta
    }).trigger('reloadGrid');
}
loadClientes_busqueda_base_by_idcliente_cartera = function ( ) {
    var cartera=$('#cbAtencionGlobalesCartera').val();
    var servicio=$('#hdCodServicio').val();
    var usuario_servicio=$('#hdCodUsuarioServicio').val();
    var codigo_predictivo=$.trim( $('#txtAtencionSearchBaseByIdClienteCartera').val() );
    if( codigo_predictivo=='' || codigo_predictivo=='Ingrese Codigo Predictivo' ) {
        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese Codigo Predictivo','300px'));
        AtencionClienteDAO.setTimeOut_hide_message();
        return false;
    }
    $('#table_busqueda_base').jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_busquedaBase&Cartera='+cartera+'&Servicio='+servicio+'&UsuarioServicio='+usuario_servicio+'&Idcliente_cartera='+codigo_predictivo
    }).trigger('reloadGrid');
}
/************/
loadClientes_busqueda_estado = function ( xvalue ) {

    //var cartera=$('#cbAtencionGlobalesCartera').val();
    var cartera = $('#tbCarterasMultiples').jqGrid('getGridParam','selarrrow');
    var servicio=$('#hdCodServicio').val();
    var usuario_servicio=$('#hdCodUsuarioServicio').val();
    if( xvalue == 0 ) {
        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione estado','300px'));
        AtencionClienteDAO.setTimeOut_hide_message();
        alert(1);
        return false;
    }else if( cartera.length==0 ) {
        alert(2);
        return false;
    }
    $('#table_busqueda_estado').jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_busquedaEstado&Cartera='+cartera.join(",")+'&Servicio='+servicio+'&Operador='+usuario_servicio+'&IdFinal='+xvalue
    }).trigger('reloadGrid');
    
}
/************/
reloadJQGRID_telefono = function ( /*CodigoCliente , idCartera */) {
    var codCli = $('#CodigoClienteMain').val();
    var idCartera = $('#IdCartera').val();
    $("#table_telefonos").jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_telefonos&CodigoCliente='+codCli+'&Cartera='+idCartera
    }).trigger('reloadGrid');
}
reloadJQGRID_numero_telefono = function ( /*CodigoCliente , idCartera*/ ) {
    var codCli = $('#CodigoClienteMain').val();
    var idCartera = $('#IdCartera').val();
    $("#table_telefonos_cliente").jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_numero_telefono&CodigoCliente='+codCli+'&Cartera='+idCartera
    }).trigger('reloadGrid');
}
reloadJQGRID_campo_telefono = function ( CodigoCliente, idCartera ) {
    //reloadJQGRID_campo_telefono = function ( idCliente, idCartera ) {
    //$("#table_campo_telefono").jqGrid('setGridParam',{url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_campo_telefonos&Cliente='+idCliente+'&Cartera='+idCartera}).trigger('reloadGrid');
    $("#table_campo_telefono").jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_campo_telefonos&CodigoCliente='+CodigoCliente+'&Cartera='+idCartera
    }).trigger('reloadGrid');
}
/*reloadJQGRID_llamadas = function ( idClienteCartera ) {
    $("#table_llamada").jqGrid('setGridParam',{url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_llamada&ClienteCartera='+idClienteCartera}).trigger('reloadGrid');       
}*/
reloadJQGRID_llamadas = function ( CodigoCliente, idCliente ) {
    $("#table_llamada").jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_historico&CodigoCliente='+CodigoCliente+'&idcliente='+idCliente
    }).trigger('reloadGrid');
}
reloadJQGRID_llamadas_two = function ( CodigoCliente, idCliente ) {
    $("#table_llamada_two").jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_historico&CodigoCliente='+CodigoCliente+'&idcliente='+idCliente
    }).trigger('reloadGrid');
}
reloadJQGRID_visita_one = function ( idcliente_cartera ) {
    $("#table_visita_one").jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_campo_visita&ClienteCartera='+idcliente_cartera
    }).trigger('reloadGrid'); 
}
reloadJQGRID_cuenta = function ( CodigoCliente, idCartera ) {
    //reloadJQGRID_cuenta = function ( idClienteCartera, idCartera ) {
    //$("#table_cuenta").jqGrid('setGridParam',{url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_cuenta&ClienteCartera='+idClienteCartera+'&Cartera='+idCartera}).trigger('reloadGrid'); 
    $("#table_cuenta").jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_cuenta&CodigoCliente='+CodigoCliente+'&Cartera='+idCartera
    }).trigger('reloadGrid');   
}
//reloadJQGRID_operacion = function ( idCuenta ) {
reloadJQGRID_operacion = function ( idCuenta ) {
    //$("#table_operaciones").jqGrid('setGridParam',{url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_operaciones&Cuenta='+idCuenta}).trigger('reloadGrid');
    var idCartera=$('#cbAtencionGlobalesCartera').val();
    var NumeroCuenta=$("#table_cuenta").jqGrid("getRowData",idCuenta)['numero_cuenta'];
    var Moneda=$("#table_cuenta").jqGrid("getRowData",idCuenta)['moneda'];
    $("#table_operaciones").jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_operaciones&NumeroCuenta='+NumeroCuenta+'&Cartera='+idCartera+'&Moneda='+Moneda
    }).trigger('reloadGrid');
}
reloadJQGRID_pagos = function ( idDetalleCuenta ) {
    var cartera=$('#cbAtencionGlobalesCartera').val();
    if( cartera==0 ) {
        return false;
    }
    /***/
    var CodigoOperacion=$("#table_operaciones").jqGrid("getRowData",idDetalleCuenta)['codigo_operacion'];
    /****/
    //$("#table_pagos").jqGrid('setGridParam',{url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_pagos&DetalleCuenta='+idDetalleCuenta+'&Cartera='+cartera}).trigger('reloadGrid');   
    $("#table_pagos").jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_pagos&CodigoOperacion='+CodigoOperacion+'&Cartera='+cartera
    }).trigger('reloadGrid');   
}
reloadJQGRID_direccion = function ( /*CodigoCliente , idCartera*/ ) {
    var codCli = $('#CodigoClienteMain').val();
    var idCartera = $('#IdCartera').val();
    $("#table_direccion").jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_direcciones&CodigoCliente='+codCli+'&Cartera='+idCartera
    }).trigger('reloadGrid');   
}
reloadJQGRID_campo_direccion = function ( CodigoCliente, idCartera ) {
    //reloadJQGRID_campo_direccion = function ( idCliente, idCartera ) {
    //$("#table_campo_direcciones").jqGrid('setGridParam',{url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_campo_direcciones&Cliente='+idCliente+'&Cartera='+idCartera}).trigger('reloadGrid'); 
    $("#table_campo_direcciones").jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_campo_direcciones&CodigoCliente='+CodigoCliente+'&Cartera='+idCartera
    }).trigger('reloadGrid');   
}
reloadJQGRID_historico = function ( CodigoCliente, idCliente ) {
    //reloadJQGRID_historico = function ( idCliente ) {
    //$("#table_historico").jqGrid('setGridParam',{url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_historico&Cliente='+idCliente}).trigger('reloadGrid');
    $("#table_historico").jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_historico&CodigoCliente='+CodigoCliente+'&idcliente='+idCliente
    }).trigger('reloadGrid');
}
reloadJQGRID_agendados = function ( ) {
    var cartera=$('#cbAtencionGlobalesCartera').val();
    var fecha_inicio=$('#txtAgendarBuscarFechaInicio').val();
    var fecha_fin=$('#txtAgendarBuscarFechaFin').val();
    var usuario_servicio=$('#hdCodUsuarioServicio').val();
    
    if( fecha_inicio=='' || fecha_fin=='' ) {
        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese fecha de inicio y fecha fin','300px'));
        $('#'+AtencionClienteDAO.idLayerMessage).effect('pulsate',{},'slow',function(){
            $(this).empty();
        });
        return false;   
    }else if( cartera==0 ) {
        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione cartera','300px'));
        $('#'+AtencionClienteDAO.idLayerMessage).effect('pulsate',{},'slow',function(){
            $(this).empty();
        });
        return false;
    }
    
    //$("#table_agendados").jqGrid('setGridParam',{url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_agendados&Cartera='+cartera+'&FechaInicio='+fecha_inicio+'&FechaFin='+fecha_fin+'&UsuarioServicio='+usuario_servicio}).trigger('reloadGrid');    
    $("#table_agendados").jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_agendados&Cartera='+cartera+'&FechaInicio='+fecha_inicio+'&FechaFin='+fecha_fin+'&UsuarioServicio='+usuario_servicio+'&Servicio='+$('#hdCodServicio').val()
    }).trigger('reloadGrid');   
}
reloadJQGRID_visita_2 = function ( idcliente_cartera ) {
    $("#table_campo_visita").jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_campo_visita&ClienteCartera='+idcliente_cartera
    }).trigger('reloadGrid');   
}
reloadJQGRID_visita_3 = function ( codigo_cliente ) {
    $("#table_campo_visita").jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_campo_visita3&codigoCliente='+codigo_cliente
    }).trigger('reloadGrid');   
}
reloadJQGRID_visita = function ( ) {
    var cartera=$('#cbCampoGlobalesCartera').val();
    var fecha_inicio=$('#txtCampoVisitaFechaInicio').val();
    var fecha_fin=$('#txtCampoVisitaFechaFin').val();
    var usuario_servicio=$('#hdCodUsuarioServicio').val();
    var servicio=$('#hdCodServicio').val();
    var idcliente_cartera = $('#IdClienteCarteraCampoMain').val();
    
    if( fecha_inicio=='' || fecha_fin=='' ) {
        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese fecha de inicio y fecha fin','400px'));
        AtencionClienteDAO.setTimeOut_hide_message();
        return false;   
    }else if( cartera==0 ) {
        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione cartera','300px'));
        AtencionClienteDAO.setTimeOut_hide_message();
        return false;   
    }else if( idcliente_cartera == '' ) {
        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Realize busqueda de cliente','400px'));
        AtencionClienteDAO.setTimeOut_hide_message();
    }
    
    $("#table_campo_visita").jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_campo_visita&Cartera='+cartera+'&Servicio='+servicio+'&FechaInicio='+fecha_inicio+'&FechaFin='+fecha_fin+'&UsuarioServicio='+usuario_servicio+'&ClienteCartera='+idcliente_cartera
    }).trigger('reloadGrid');   
}
reloadJQGRID_visita_atencion_cliente = function ( /*idcliente_cartera*/ ) {
    var idClienteCartera = $('#IdClienteCartera').val();
    $("#table_visita").jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_campo_visita&ClienteCartera='+idClienteCartera
    }).trigger('reloadGrid');   
}
searchClientePorCodigo = function ( ) {
    var rs=validacion.check([
    {
        id:'txtCampoCodigoSearch',
        required:true,
        isAlphaNumeric:true,
        errorRequiredFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese codigo de cliente a buscar','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        },
        errorAlphaNumericFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese solo letras y numeros','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    }
    ]);
    if( rs ){
        AtencionClienteDAO.SearchClientByCode();
    }
}
searchClientePorCodigo2 = function ( ) {/*piro*/
    var rs=validacion.check([
    {
        id:'txtCampoCodigoSearch2',
        required:true,
        isAlphaNumeric:true,
        errorRequiredFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese codigo de cliente a buscar','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        },
        errorAlphaNumericFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese solo letras y numeros','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    }
    ]);
    if( rs ){
        AtencionClienteDAO.SearchClientByCode2();
    }
}
searchClientePorNumeroDocumento = function ( ) {
    var rs=validacion.check([
    {
        id:'txtCampoNumeroDocumentoSearch',
        required:true,
        errorRequiredFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese dni a buscar','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    }
    ]);
    
    if( rs ){
        AtencionClienteDAO.SearchClientByNumeroDocumento();
    }
}
searchClientePorNumeroCuenta = function ( xnumero_cuenta, xcartera ) {
    var rs=validacion.check([
    {
        id:'txtCampoNumeroCuentaSearch',
        required:true,
        errorRequiredFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese numero cuenta a buscar','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    }
    ]);

    if( rs ){
        AtencionClienteDAO.SearchClientByNumeroCuenta( xnumero_cuenta, xcartera, AtencionClienteDAO.FillAllCampo );
    }
}
searchClientePorTelefono = function ( xtelefono, xcartera ) {
    var rs=validacion.check([
    {
        id:'txtCampoTelefonoSearch',
        required:true,
        errorRequiredFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese telefono a buscar','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    }
    ]);

    if( rs ){
        AtencionClienteDAO.SearchClientByTelefono( xtelefono, xcartera, AtencionClienteDAO.FillAllCampo );
    }
}
loadFinalServicioAtencionAgendar = function ( ) {
    AtencionClienteDAO.LoadFinalServicioAgendar();  
}
loadFinalServicioCampoVisita = function ( ) {
    AtencionClienteDAO.LoadFinalServicioVisita();
}
loadFinalServicioCampoLlamada = function ( ) {
    AtencionClienteDAO.LoadFinalServicioLlamada();
}

loadDataCliente = function ( idClienteCartera ) {
    AtencionClienteDAO.LoadDataCliente(idClienteCartera);   
}
fillAtencionClienteBusquedaManualCampo = function ( ) {
    AtencionClienteDAO.LoadFiltrosCampoAtencionCliente();   
}
addFilter = function ( ) {
    var tabla=$('#cbTablaBusquedaManualAtencionCliente').val();
    var tabla_mostrar=$('#cbTablaBusquedaManualAtencionCliente option:selected').text();
    var campo=$('#cbCampoBusquedaManualAtencionCliente option:selected').text();
    var tipo_dato=$('#cbCampoBusquedaManualAtencionCliente').val();
    var dato=$.trim( $('#txtAtencionBusquedaManualDato').val() );
    var html='';
    
    if( tabla==0 ) {
        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione tabla','300px'));
        AtencionClienteDAO.setTimeOut_hide_message();
        return false;
    }
    
    if( campo==0 ) {
        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione campo','300px'));
        AtencionClienteDAO.setTimeOut_hide_message();
        return false;
    }
    if( dato=='' ) {
        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese dato a buscar','300px'));
        AtencionClienteDAO.setTimeOut_hide_message();
        return false;
    }
    
    var lengthTR=$('#table_filtros').find('tr[id="'+tabla+"@"+campo+'"]').length;
    
    if(lengthTR>0){
        return false;
    }
    
    html+='<tr class="ui-widget-content" id="'+tabla+"@"+campo+'" title="'+dato+'@'+tipo_dato+'" >';
    html+='<td class="ui-state-default" style="width:25px;padding:3px 0;border:1px solid #E0CFC2;" align="center" ></td>';
    html+='<td align="center" style="width:100px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+tabla_mostrar+'</td>';
    html+='<td align="center" style="width:100px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+campo+'</td>';
    html+='<td align="center" style="width:100px;white-space:pre-line;padding:3px 0;border-bottom:1px solid #E0CFC2;">'+dato+'</td>';
    html+='<td align="center" class="ui-state-default" style="width:25px;padding:3px 0;border-bottom:1px solid #E0CFC2;" onclick="$(this).parent().remove()"><span class="ui-icon ui-icon-trash"></span></td>';
    html+='</tr>';
    $('#table_filtros').append(html);
}
load_lista_operadores_ayudar = function(){
    var Cartera=$('#cbCarteraApoyo').val();
    if( Cartera==0 ){
        return false;
    }
    AtencionClienteDAO.ListarOperadoresAyudar(AtencionClienteDAO.OperadoresMatrizBusqueda);
}

load_change_campania_atencion_cliente = function ( ) {
    //var Cartera=$('#cbAtencionGlobalesCartera').val();
    var Cartera;
    ;
    if($('#hTipoGestion').val() == 'propias')
    {
        Cartera=$('#tbCarterasMultiples').jqGrid('getGridParam','selarrrow');
    }else if($('#hTipoGestion').val() == 'apoyo'){
        Cartera=$('#cbCarteraApoyo').val();
    }
    if( Cartera.length == 0 ){
        $('#dataInformationGestionMain').html('');
        alert('seleccione por lo moneos una cartera');
        return false;
    }
    //AtencionClienteDAO.loadInitializeAlertas();
    AtencionClienteDAO.ListarNotas(Cartera);
    //AtencionClienteDAO.ListarOperadoresAyudar(AtencionClienteDAO.OperadoresMatrizBusqueda);
    /**********/
    AtencionClienteDAO.ListarTramoAtencionCliente(Cartera);
    //AtencionClienteDAO.ListarRankingUsuarioServicio(Cartera);
   

   /* DistribucionDAO.ListarDepartamentos( Cartera, function ( obj ) {
        var html = '';
        html+='<option value="0">--Seleccione--</option>';
        for( i=0;i<obj.length;i++ ) {
            html+='<option value="'+obj[i].departamento+'">'+obj[i].departamento+'</option>';
        }
        $('#cbFiltroDepartamento').html(html);
    } ); */
    /*********/
    AtencionClienteDAO.ListarEstadoPago( Cartera, function ( obj ) {
        var html = '';
        html+='<option value="0">--Seleccione--</option>';
        for( i=0;i<obj.length;i++ ) {
            html+='<option value="'+obj[i].estado_pago+'">'+obj[i].estado_pago+'</option>'; 
        }
        $('#cbFiltroEstadoPago').html(html);
    } );
    /***************/
    
    reloadJQGRID_busqueda_gestionados();
    reloadJQGRID_busqueda_sin_gestion();
    /*********/
    informacion_gestion();
/********/
}
load_cartera_atencion_cliente = function ( idCampania,cboCart ) {
    AtencionClienteDAO.ListCartera( idCampania,AtencionClienteDAO.FillAtencionClienteCartera,cboCart );
}
load_cartera_atencion_clienteOperador = function ( idCampania,idUsuario_servicio, cboCart, cluster, evento, segmento, modo ) {
    AtencionClienteDAO.ListCarteraOperador( idCampania,idUsuario_servicio,AtencionClienteDAO.FillAtencionClienteCartera, cboCart, cluster, evento, segmento, modo );
}

load_cartera_campo = function ( idCampania ) {
    AtencionClienteDAO.ListCartera( idCampania,AtencionClienteDAO.FillCampoCartera );
}
MapTableFilter = function ( ) {
    var tr_LENGTH=$('#table_filtros').find('tr').length;
    if( tr_LENGTH==0 ){
        return false;
    }
    
    var metadata='['+$('#table_filtros').find('tr').map(function(){
        return '{"metadata":"'+this.id+'@'+this.title+'"}';
    }).get().join(',')+']';
        
    reloadJQGRID_busqueda_manual(metadata);
}
reloadJQGRID_busqueda_manual = function ( metadata ) {
    var cartera=$('#cbAtencionGlobalesCartera').val();
    var usuario_servicio=$('#hdCodUsuarioServicio').val();
    var servicio=$('#hdCodServicio').val();
    $('#table_busqueda_manual').jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_busquedaManual&Cartera='+cartera+'&Servicio='+servicio+'&UsuarioServicio='+usuario_servicio+'&Metadata='+metadata
    }).trigger('reloadGrid');
}
getParamTelefono = function ( ) {
    var id=$("#table_telefonos").jqGrid("getGridParam",'selrow');
    var nm=$("#table_telefonos").jqGrid("getRowData",id)['tel.numero'];
    $('#txtAtencionClienteNumeroCall').val(nm);
    $('#HdIdTelefono').val(id);
    $('#tabAC2Llamada').trigger('click');
}
getParamNumeroTelefono = function ( ) {
    var id=$("#table_telefonos_cliente").jqGrid("getGridParam",'selrow');
    var nm = $( $("#table_telefonos_cliente").jqGrid("getRowData",id)['t1.numero'] ).text();
    var pre = $("#table_telefonos_cliente").jqGrid("getRowData",id)['t1.prefijos'];
    var lin = $("#table_telefonos_cliente").jqGrid("getRowData",id)['t1.linea'];
    /*if( $.trim(nm) == "" ) {
        nm = $("#table_telefonos_cliente").jqGrid("getRowData",id)['t1.numero'];
    }*/
    $('#txtAtencionClienteNumeroCall').val(nm);
    $('#txtAtencionClienteNumeroCall').attr('prefixs',pre);
    $('#txtAtencionClienteNumeroCall').attr('line',lin);
    $('#HdIdTelefono').val(id);
    
//$('#tabAC2Llamada').trigger('click');
}
getParamCampoDireccion = function ( ) {
    var id=$("#table_campo_direcciones").jqGrid("getGridParam",'selrow');
    var nm=$("#table_campo_direcciones").jqGrid("getRowData",id)['dir.direccion'];
    //$('#lbDireccionCampo').text(nm);
    $('#lbDireccionCampo').html(nm);
    $('#HdCodIdCampoDireccion').val(id);
    $('#tabCampoVisita').trigger('click');
}
getParamEditTelefonos = function ( ) {
    var id=$("#table_telefonos").jqGrid("getGridParam",'selrow');
    var nm=$("#table_telefonos").jqGrid("getRowData",id)['tel.numero'];
    //var origen = ($("#table_telefonos").jqGrid("getRowData",id)['org.nombre']).toLowerCase();
    //if( origen=='cartera' ) {
    //$('#DialogEditTelefonoCartera #hdIdTelefonoCartera').val(id);
    //$('#DialogEditTelefonoCartera #txtNumeroTelefonoAtencionCliente').val(nm);
    //$('#DialogEditTelefonoCartera').dialog('open');
    //}else if( origen=='gestion' ){
    AtencionClienteDAO.DataTelefonoPorId(id,AtencionClienteDAO.FillBoxFormTelefonoAtencionCliente);
//$('#DialogAddTelefonoCartera').dialog('open');
//}
//$('#DialogEditTelefonoCartera').dialog('option','position',[200,200]);
    
}
delete_telefono_atencion_cliente = function ( ) {
    var id=$("#table_telefonos").jqGrid("getGridParam",'selrow');
    if( id!=null ) {
        AtencionClienteDAO.DeleteTelefono(id,function ( obj ) {
            if( obj.rst ) {
                $("#table_telefonos").jqGrid().trigger('reloadGrid');
            }else{
                $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'350px'));
                AtencionClienteDAO.setTimeOut_hide_message();
            }
        },function ( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Error en ejecucion de proceso','350px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        });
    }
}
getParamEditTelefonoCampo = function ( ) {
    var id=$("#table_campo_telefono").jqGrid("getGridParam",'selrow');
    AtencionClienteDAO.DataTelefonoPorId(id,AtencionClienteDAO.FillBoxFormTelefonoCampo);
}
getParamEditDireccionCampo = function ( ) {
    var id=$("#table_campo_direcciones").jqGrid("getGridParam",'selrow');
    AtencionClienteDAO.DataDireccionPorId(id,AtencionClienteDAO.FillBoxFormDireccionCampo);
}
getParamEditDireccionAtencionCliente = function ( ) {
    var id=$("#table_direccion").jqGrid("getGridParam",'selrow');
    AtencionClienteDAO.DataDireccionPorId(id,AtencionClienteDAO.FillBoxFormDireccionAtencionCliente);
    $('#DialogAddDireccionCartera').dialog('open');
}
getParamEditVisitaCampo = function ( ) {
    var id=$("#table_campo_visita").jqGrid("getGridParam",'selrow');
    AtencionClienteDAO.DataVisitaPorId(id,AtencionClienteDAO.FillBoxFormVisitaCampo);
}
getParamEditLlamadaAtencionCliente = function ( ) {
    var id=$("#table_llamada").jqGrid("getGridParam",'selrow');
    AtencionClienteDAO.DataLlamadaPorId(id,AtencionClienteDAO.FillBoxFormLlamadaAtencionCliente);
}
display_box_agregar_direccion = function ( ) {
    $('#DialogAddDireccionCartera').dialog('open');
}
update_edit_numero_telefono = function ( ) {
    AtencionClienteDAO.update_numero_telefono();
}
DisplayDialogAddTelefonoCartera = function ( ){
    $('#DialogAddTelefonoCartera').dialog('open');
}
Call_Sweep_Weight = function ( ) {
    
    
    
}
listar_carteras = function ( id, idcbo ) { //PIRO 
    AtencionClienteDAO.ListCartera(id,AtencionClienteDAO.FillCargaCartera,idcbo);
}
listarDomicilioPorCodigo = function (id, idcbo){ //piro 04-12-2014 8:47 am
    AtencionClienteDAO.ListDomicilioByCode(id,AtencionClienteDAO.FillCargaDomicilio,idcbo);
}
Call_Sweep = function ( )  {
    var modo_marcacion = $(':radio[name="modo_marcacion_telefono"]:checked').val();
    var carga_estado = $('#cbLlamadaEstado option:selected').parent().attr('label');
    var rowids = $('#table_telefonos_cliente').jqGrid('getDataIDs');
    var rowid = $('#table_telefonos_cliente').jqGrid('getGridParam','selrow');
    var rs = true;
    if( modo_marcacion == 'modo_marcacion_barrido' ) {
        if( carga_estado == 'CEF' ) {
            rs = true ;
        }else{
            
            if( rowids.length>1 ) {
                var position = -2;
                for( i=0;i<rowids.length;i++ ) {
                    if( rowids[i] == rowid ) {
                        position = i;
                        break;
                    }
                }
                
                if( (position+1)>1 ) {
                    $('#table_telefonos_cliente').jqGrid('setSelection',rowids[position+1]);
                    rs = false;
                }else{
                    rs = true;
                }
                
                
            }else{
                rs = true;
            }
            
        }
        
    }else{
        rs = true;
    }
    
    return rs ;
}
Call = function ( type ) {
    /************/
    var modo_marcacion = $(':radio[name="modo_marcacion_telefono"]:checked').val();

    var rowids = $('#table_telefonos_cliente').jqGrid('getDataIDs');

    if( type == 0 ) {
        if( modo_marcacion == 'modo_marcacion_manual' ) {
            return false;
        }
        if( rowids.length > 0 ) {
            $('#table_telefonos_cliente').jqGrid('setSelection',rowids[0]);
            return false;
        }else{
            return false;
        }
    }else if( type == 1 ){
        if( modo_marcacion == 'modo_marcacion_automatica' ) {
            if( rowids.length > 0 ) {
                var rowid = $('#table_telefonos_cliente').jqGrid('getGridParam','selrow');
                var position = 0;
                if( rowid ){
                    for( i=0;i<rowids.length;i++ ) {
                        if( rowids[i] == rowid ) {
                            position = i;
                            break;
                        }
                    }
                    if( (position+1)<=( rowids.length - 1 ) ) {
                        $('#table_telefonos_cliente').jqGrid('setSelection',rowids[position+1]);
                        return false;
                    }else{
                        var rs = confirm("Desea pasar al siguiente item");
                        if( rs ) {
                            $('#btnNextClienteAtencionCliente').trigger('click');
                        }else{
                            $('#table_llamada').jqGrid().trigger('reloadGrid');
                        }
                        return false;
                    }
                    
                }else{
                    if( rowids.length > 0 ) {
                        $('#table_telefonos_cliente').jqGrid('setSelection',rowids[0]);
                        return false;
                    }else{
                        var rs = confirm("Desea pasar al siguiente item");
                        if( rs ) {
                            $('#btnNextClienteAtencionCliente').trigger('click');
                        }else{
                            $('#table_llamada').jqGrid().trigger('reloadGrid');
                        }
                        return false;
                    }
                    
                }
                
            }else{
                return false;
            }

        }else if( modo_marcacion == 'modo_marcacion_barrido' ) {

            

            if( rowids.length > 0 ) {
                var rowid = $('#table_telefonos_cliente').jqGrid('getGridParam','selrow');
                var position = 0;
                if( rowid ){
                    for( i=0;i<rowids.length;i++ ) {
                        if( rowids[i] == rowid ) {
                            position = i;
                            break;
                        }
                    }
                    if( (position+1)<=( rowids.length - 1 ) ) {
                        $('#table_telefonos_cliente').jqGrid('setSelection',rowids[position+1]);
                        return false;
                    }else{
                        var rs = confirm("Desea pasar al siguiente item");
                        if( rs ) {
                            $('#btnNextClienteAtencionCliente').trigger('click');
                        }else{
                            $('#table_llamada').jqGrid().trigger('reloadGrid');
                        }
                        return false;
                    }
                    
                }else{
                    if( rowids.length > 0 ) {
                        $('#table_telefonos_cliente').jqGrid('setSelection',rowids[0]);
                        return false;
                    }else{
                        return false;
                    }
                    
                }
                
            }else{
                return false;
            }


        }else if( modo_marcacion == 'modo_marcacion_barrido_peso' ) {
            
            if( rowids.length > 0 ) {
                
                var pesos = new Array();

                for( i=0;i<rowids.length;i++ ) {
                    pesos.push( { "id_": rowids[i], "peso" : $("#table_telefonos_cliente").jqGrid("getRowData",rowids[i])['t1.peso'] } );
                }

                var aux = {};
                    
                for( j=0;j<(pesos.length-1);j++ ) {
                        
                    for( k=0;k<(pesos.length-1);k++ ) {

                        var peso1 = parseFloat( pesos[k].peso );
                        var peso2 = parseFloat( pesos[k+1].peso );

                        if( peso1 < peso2 ) {
                                
                            aux = pesos[k];
                            pesos[k] = pesos[k+1];
                            pesos[k+1] = aux;

                        }

                    }

                }

                var rowid = $('#table_telefonos_cliente').jqGrid('getGridParam','selrow');

                var position = 0;

                if( rowid ) {

                    var peso_ = parseFloat( $("#table_telefonos_cliente").jqGrid("getRowData",rowid)['t1.peso'] );

                    var flag_pt = 0;

                    for( k=0;k<pesos.length;k++ ) {
                        
                        var id_ = pesos[k].id_;
                        var weight = parseFloat( pesos[k].peso );

                        if( weight == peso_ && id_ == rowid ) {
                            flag_pt = k;
                            break;
                        }
                            
                    }

                    for( k=flag_pt;k<pesos.length;k++ ) {
                        
                        var id_ = pesos[k].id_;
                        var weight = parseFloat( pesos[k].peso );

                        if( weight <= peso_ && id_ != rowid ) {
                            position = k;
                            break;
                        }
                            
                    }
                    
                    if( position != 0 ) {
                        $('#table_telefonos_cliente').jqGrid('setSelection',pesos[position].id_);
                        return false;
                    }else{
                        var rs = confirm("Desea pasar al siguiente item");
                        if( rs ) {
                            $('#btnNextClienteAtencionCliente').trigger('click');
                        }else{
                            $('#table_llamada').jqGrid().trigger('reloadGrid');
                        }
                        return false;
                    }
                    
                }else{

                    if( rowids.length > 0 ) {
                        
                        $('#table_telefonos_cliente').jqGrid('setSelection',pesos[0].id_);

                        return false;
                    }else{
                        return false;
                    }

                }
                

            }else{
                return false;
            }

        }


    }else if( type == 2 ) {
        if( modo_marcacion == 'modo_marcacion_manual' ) {
            return false;
        }
    }else{
        return false;
    }

    /************/

    var xidTelefono = $('#HdIdTelefono').val();
    if( xidTelefono != '' ) {
        //$('#LlamadaFechaInicioTMO,#LlamadaFechaFinTMO').val('');
        $('#LlamadaFechaFinTMO').val('');
        AtencionClienteDAO.CallAsterisk();
    }else{
        alert('Seleccione telefono');
    }

}
CallNeotel = function ( type ) {
    /************/
    var modo_marcacion = $(':radio[name="modo_marcacion_telefono"]:checked').val();

    var rowids = $('#table_telefonos_cliente').jqGrid('getDataIDs');

    if( type == 0 ) {
        if( modo_marcacion == 'modo_marcacion_manual' ) {
            return false;
        }
        if( rowids.length > 0 ) {
            $('#table_telefonos_cliente').jqGrid('setSelection',rowids[0]);
            return false;
        }else{
            return false;
        }
    }else if( type == 1 ){
        if( modo_marcacion == 'modo_marcacion_automatica' ) {
            if( rowids.length > 0 ) {
                var rowid = $('#table_telefonos_cliente').jqGrid('getGridParam','selrow');
                var position = 0;
                if( rowid ){
                    for( i=0;i<rowids.length;i++ ) {
                        if( rowids[i] == rowid ) {
                            position = i;
                            break;
                        }
                    }
                    if( (position+1)<=( rowids.length - 1 ) ) {
                        $('#table_telefonos_cliente').jqGrid('setSelection',rowids[position+1]);
                        return false;
                    }else{
                        var rs = confirm("Desea pasar al siguiente item");
                        if( rs ) {
                            $('#btnNextClienteAtencionCliente').trigger('click');
                        }else{
                            $('#table_llamada').jqGrid().trigger('reloadGrid');
                        }
                        return false;
                    }
                    
                }else{
                    if( rowids.length > 0 ) {
                        $('#table_telefonos_cliente').jqGrid('setSelection',rowids[0]);
                        return false;
                    }else{
                        var rs = confirm("Desea pasar al siguiente item");
                        if( rs ) {
                            $('#btnNextClienteAtencionCliente').trigger('click');
                        }else{
                            $('#table_llamada').jqGrid().trigger('reloadGrid');
                        }
                        return false;
                    }
                    
                }
                
            }else{
                return false;
            }

        }else if( modo_marcacion == 'modo_marcacion_barrido' ) {

            

            if( rowids.length > 0 ) {
                var rowid = $('#table_telefonos_cliente').jqGrid('getGridParam','selrow');
                var position = 0;
                if( rowid ){
                    for( i=0;i<rowids.length;i++ ) {
                        if( rowids[i] == rowid ) {
                            position = i;
                            break;
                        }
                    }
                    if( (position+1)<=( rowids.length - 1 ) ) {
                        $('#table_telefonos_cliente').jqGrid('setSelection',rowids[position+1]);
                        return false;
                    }else{
                        var rs = confirm("Desea pasar al siguiente item");
                        if( rs ) {
                            $('#btnNextClienteAtencionCliente').trigger('click');
                        }else{
                            $('#table_llamada').jqGrid().trigger('reloadGrid');
                        }
                        return false;
                    }
                    
                }else{
                    if( rowids.length > 0 ) {
                        $('#table_telefonos_cliente').jqGrid('setSelection',rowids[0]);
                        return false;
                    }else{
                        return false;
                    }
                    
                }
                
            }else{
                return false;
            }


        }else if( modo_marcacion == 'modo_marcacion_barrido_peso' ) {
            
            if( rowids.length > 0 ) {
                
                var pesos = new Array();

                for( i=0;i<rowids.length;i++ ) {
                    pesos.push( { "id_": rowids[i], "peso" : $("#table_telefonos_cliente").jqGrid("getRowData",rowids[i])['t1.peso'] } );
                }

                var aux = {};
                    
                for( j=0;j<(pesos.length-1);j++ ) {
                        
                    for( k=0;k<(pesos.length-1);k++ ) {

                        var peso1 = parseFloat( pesos[k].peso );
                        var peso2 = parseFloat( pesos[k+1].peso );

                        if( peso1 < peso2 ) {
                                
                            aux = pesos[k];
                            pesos[k] = pesos[k+1];
                            pesos[k+1] = aux;

                        }

                    }

                }

                var rowid = $('#table_telefonos_cliente').jqGrid('getGridParam','selrow');

                var position = 0;

                if( rowid ) {

                    var peso_ = parseFloat( $("#table_telefonos_cliente").jqGrid("getRowData",rowid)['t1.peso'] );

                    var flag_pt = 0;

                    for( k=0;k<pesos.length;k++ ) {
                        
                        var id_ = pesos[k].id_;
                        var weight = parseFloat( pesos[k].peso );

                        if( weight == peso_ && id_ == rowid ) {
                            flag_pt = k;
                            break;
                        }
                            
                    }

                    for( k=flag_pt;k<pesos.length;k++ ) {
                        
                        var id_ = pesos[k].id_;
                        var weight = parseFloat( pesos[k].peso );

                        if( weight <= peso_ && id_ != rowid ) {
                            position = k;
                            break;
                        }
                            
                    }
                    
                    if( position != 0 ) {
                        $('#table_telefonos_cliente').jqGrid('setSelection',pesos[position].id_);
                        return false;
                    }else{
                        var rs = confirm("Desea pasar al siguiente item");
                        if( rs ) {
                            $('#btnNextClienteAtencionCliente').trigger('click');
                        }else{
                            $('#table_llamada').jqGrid().trigger('reloadGrid');
                        }
                        return false;
                    }
                    
                }else{

                    if( rowids.length > 0 ) {
                        
                        $('#table_telefonos_cliente').jqGrid('setSelection',pesos[0].id_);

                        return false;
                    }else{
                        return false;
                    }

                }
                

            }else{
                return false;
            }

        }


    }else if( type == 2 ) {
        if( modo_marcacion == 'modo_marcacion_manual' ) {
            return false;
        }
    }else{
        return false;
    }

    /************/

    var xidTelefono = $('#HdIdTelefono').val();
    if( xidTelefono != '' ) {
        //$('#LlamadaFechaInicioTMO,#LlamadaFechaFinTMO').val('');
        $('#LlamadaFechaFinTMO').val('');
        neotelDAO.setDialManual();
    }else{
        alert('Seleccione telefono');
    }

}
Hungup = function ( ) {
    AtencionClienteDAO.HungupAsterisk();
}
Hungup_neotel = function ( ) {
    neotelDAO.setHungup();
}
save_nota = function ( ) {
    var idClienteCartera=$('#IdClienteCartera').val();
    //  if(idClienteCartera==''){
    //      return false;
    //  }
    
    var rs=validacion.check([
    {
        id:'IdClienteCartera',
        required:true,
        errorRequiredFunction:function ( ) {
            $('#NotasLayerMessage').html(templates.MsgError('Gestione cliente','250px')).effect('pulsate',{},'slow',function(){
                $(this).empty();
            }); 
        }
    },

    {
        id:'txtFechaNota',
        required:true,
        errorRequiredFunction:function( ){
            $('#NotasLayerMessage').html(templates.MsgError('Ingrese fecha de nota','250px'));
            $('#NotasLayerMessage').effect('pulsate',{},'slow',function(){
                $(this).empty();
            }); 
        }
    },
    {
        id:'txtDescripcionNota',
        required:true,
        isAlphaNumeric:true,
        errorRequiredFunction:function( ){
            $('#NotasLayerMessage').html(templates.MsgError('Ingrese nota','250px'));
            $('#NotasLayerMessage').effect('pulsate',{},'slow',function(){
                $(this).empty();
            }); 
        },
        errorAlphaNumericFunction:function( ){
            $('#NotasLayerMessage').html(templates.MsgError('Ingrese solo letras y numeros','260px'));
            $('#NotasLayerMessage').effect('pulsate',{},'slow',function(){
                $(this).empty();
            }); 
        }
    }
    ]);
    
    if( rs ){
        var rsC=confirm("Verifique si los datos ingresados son los correctos");
        if( rsC ){
            AtencionClienteDAO.save_nota(idClienteCartera);
        }
    }
}
show_box_model_nota = function ( ) {
    var idClienteCartera=$('#IdClienteCarteraMain').val();
    var Cliente=$('#txtResultadoNombreCodigoCliente').val();
    if(idClienteCartera==''){
        return false;
    }
    $('#txtAbonadoNota').text(Cliente);
    $('#dialogNotas').dialog('open');
}
show_box_model_notas_hoy = function ( ) {
    $('#dialogNotasHoy').dialog('open');
}
show_box_model_consulta = function ( ) {
    $('#dialogConsulta').dialog('open');
}
guardar_cuotificacion = function ( ) {

    var LENGTHCuenta=$('#table_cuenta_aplicar_gestion').find('input:checked').length;
    if( LENGTHCuenta==0 ) {
        alert("Seleccione cuentas");
        return false;
    }
    
    var deuda_total = parseFloat( ( $('#txtDeudaCuotificacion').val() == '' ) ? 0 : $('#txtDeudaCuotificacion').val() ) ;
    var monto_total = parseFloat( ( $('#lbMontoTotalCuotificacion').text() == '' ) ? 0 : $('#lbMontoTotalCuotificacion').text() ) ;
    
    if( monto_total<deuda_total ) {
        alert("Monto total debe ser mayor a deuda");
        return false;
    }
    
    var Cuentas = '['+$('#table_cuenta_aplicar_gestion').find('input:checked').map(function(){
        return '{"Cuenta":"'+$(this).val()+'"}';
    }).get().join(",")+']';

    var rs=validacion.check([
    {
        id:'IdClienteCarteraMain',
        required:true,
        errorRequiredFunction:function ( ) {
            _displayBeforeSendDl("Gestione cliente",400);
        }
    },
    {
        id:'HdIdTelefono',
        required:true,
        errorRequiredFunction:function ( ) {
            _displayBeforeSendDl("Seleccione telefono",400);
        }
    },
    {
        id:'cbEstadoCuotificacion',
        isNotValue:0,
        errorNotValueFunction : function ( ) {
            _displayBeforeSendDl("Seleccione estado de refinanciamiento",500);
        }
    },
    {
        id:'txtDeudaCuotificacion',
        required:true,
        errorNotValueFunction : function ( ) {
            _displayBeforeSendDl("Ingrese deuda",300);
        }
    },
    {
        id:'txtNCuotaCuotificacion',
        required:true,
        errorNotValueFunction : function ( ) {
            _displayBeforeSendDl("Ingrese numero de cuotas",450);
        }
    },
    {
        id:'txtMontoCuotaCuotificacion',
        required:true,
        errorNotValueFunction : function ( ) {
            _displayBeforeSendDl("Ingrese monto de cuota",400);
        }
    }
    ]);

    if( rs ) {
        var rsC=confirm("Verifique si los datos son los correctos");
        if( rsC ){
            AtencionClienteDAO.GrabarCuotificacion(Cuentas);
        }
    }
    
}
save_llamada = function ( ) {
    //  var idClienteCarteraAgendar=$('#IdClienteCarteraMain').val();
    //  var idTelefono=$('#HdIdTelefono').val();
    //  if(idClienteCarteraAgendar==''){
    //      $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccion Cliente','300px'));
    //      AtencionClienteDAO.setTimeOut_hide_message();
    //      return false;   
    //  }else if( idTelefono=='' ) {
    //      $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccion Telefono','300px'));
    //      AtencionClienteDAO.setTimeOut_hide_message();
    //      return false;   
    //  }

    var LENGTHCuenta=$('#table_cuenta_aplicar_gestion').find('input:checked').length;
    if( LENGTHCuenta==0 ) {
        alert("Seleccione cuentas");
        return false;
    }

    
    var rs=validacion.check([
    {
        id:'IdClienteCarteraMain',
        required:true,
        errorRequiredFunction:function ( ) {
            _displayBeforeSendDl('Gestione cliente',400);
        }
    },

    {
        id:'HdIdTelefono',
        required:true,
        errorRequiredFunction:function ( ) {
            _displayBeforeSendDl('Seleccione telefono',400);
        }
    },
    {
        id:'cbLlamadaEstado',
        isNotValue:0,
        errorNotValueFunction : function ( ) {
            _displayBeforeSendDl('Seleccione estado de llamada',450);
        }
    }
    ]);
    
    //var estado = $('#cbLlamadaEstado option:selected').attr('code');
    var estado = $('#cbLlamadaEstado option:selected').attr('flag_compromiso_pago');
    var estado_llamada = $('#cbLlamadaEstado').val();   
    var dias_fecha_cp= $('#cbLlamadaEstado option:selected').attr('dias_fecha_cp');
    if( estado == "1"  || estado_llamada=="764" || estado_llamada=="763") {

        var idcuenta_nst = new Array();
        var montos_t = $('#table_cuenta_aplicar_gestion').find('input:checked').parent().parent().find(':text[name="txtMontoCpCuenta"]').length;

        var montos_cp_l = 0;
        $('#table_cuenta_aplicar_gestion').find('input:checked').parent().parent().find(':text[name="txtMontoCpCuenta"]').map( function ( ) {
            if( $(this).val()!='' ){
                montos_cp_l++;
            }else{
                idcuenta_nst.push( $(this).attr('xaria') );
            }

        } );

        if( montos_cp_l == 0 ) {
            alert("Ingrese monto de compromiso de pago");
            return false;
        }

        var fechas_t = $('#table_cuenta_aplicar_gestion').find('input:checked').parent().parent().find(':text[name="txtFechaCpCuenta"]').length;
        var fechas_cp_l = 0;
        var condicion_fecha_cp=0;
        var condicion_fecha_cp2=0;
        $('#table_cuenta_aplicar_gestion').find('input:checked').parent().parent().find(':text[name="txtFechaCpCuenta"]').map( function ( ){
            if( $(this).val()!=''  ) {
                fechas_cp_l++;
            }
                    //condicion de fecha mayor a cierta cantidad de dias de la fecha_cp
                    var fecha_actual=new Date();
                    var fecha_hasta=new Date();
                    var xdia=(fecha_actual.getDate()).toString();
                    var xmes=(fecha_actual.getMonth()+1).toString();

                    fecha=fecha_actual.getFullYear()+'-'+((xmes.length==1)?('0'+xmes):xmes)+'-'+((xdia.length==1)?('0'+xdia):xdia);
                    fecha_hasta.setDate(fecha_actual.getDate() + parseInt(dias_fecha_cp));

                    var xdia_hasta=(fecha_hasta.getDate()).toString();
                    var xmes_hasta=(fecha_hasta.getMonth()+1).toString();
                    fecha_hasta=fecha_hasta.getFullYear()+'-'+((xmes_hasta.length==1)?('0'+xmes_hasta):xmes_hasta)+'-'+((xdia_hasta.length==1)?('0'+xdia_hasta):xdia_hasta);

                    if(fecha>$(this).val()){//fecha actual>fecha_cp
                      alert('Fecha cp menor a Fecha Actual');
                      condicion_fecha_cp++;
                    }
                    if(fecha_hasta<$(this).val()){
                      alert('Fecha cp mayor a ' + dias_fecha_cp + ' dias a fecha actual');
                       condicion_fecha_cp2++;
                    }       
                    //*****************/            
        } );

        if( fechas_cp_l == 0 ) {
            alert("Ingrese fecha de compromiso de pago");
            return false;
        }
        if( condicion_fecha_cp > 0 ) {
            return false;
        }
        if( condicion_fecha_cp2 > 0 ) {
            return false;
        }

        if( montos_cp_l==fechas_cp_l ) {

            if( montos_cp_l!= montos_t ){

                for( i=0;i<idcuenta_nst.length;i++ ) {
                    $('#table_cuenta_aplicar_gestion').find(':checked[value="'+idcuenta_nst[i]+'"]').attr('checked',false);
                }

            }

        }else{
            alert("Ingrese los datos faltantes de compromiso de pago ( Monto � Fecha )");
            return false;
        }


    }
    
    var Cuentas = '['+$('#table_cuenta_aplicar_gestion').find('input:checked').map(function(){
        return '{"Cuenta":"'+$(this).val()+'","estado":"'+$(this).parent().parent().find("select[name='cbEstadoCuenta']").val()+'","FechaCp":"'+$(this).parent().parent().find(":text[name='txtFechaCpCuenta']").val()+'","MontoCp":"'+($(this).parent().parent().find(":text[name='txtMontoCpCuenta']").val()).replace(/\t/g,'').replace(',','')+'","MonedaCp":"'+$(this).parent().parent().find("select[name='cbMonedaCpCuenta']").val()+'","EstadoCuenta":"'+$(this).parent().parent().find("select[name='cbStatusVlCuenta']").val()+'"}';
    }).get().join(",")+']';

    var valor_alerta=$('#hdAlerta').val();
    
/*  if(estado_llamada=='4'){
        if(valor_alerta=='0'){
            alert("Ingrese una Alerta para la promesa de Pago");
            return false;
        }
    }*/

    /***VALIDACION POR NOC QUE NO ESTE FECHA DE COMPROMISO**/
    var cont=0;
    var carga_final=$('#cbLlamadaEstado').find('option:selected').parent().attr('label');
    var Valor_monto_cp='';
    var Valor_fecha_cp='';

    if(carga_final=="NOC"){
        $('#table_cuenta_aplicar_gestion').find('input:checked').map(function(){
            Valor_monto_cp=$(":text[name='txtMontoCpCuenta']").val();
            Valor_fecha_cp=$(":text[name='txtFechaCpCuenta']").val();
            if(Valor_monto_cp!='' || Valor_fecha_cp!=''){
                cont=cont+1;
            }
        })
    }
    if (cont>0){
        alert("Error tipificacion Fecha y Monto CP(solo CEF)");
        $(":text[name='txtMontoCpCuenta']").val('');
        $(":text[name='txtFechaCpCuenta']").val('');
        return false;
    }
    /*******************************************/

    if( rs ) {
        var rsC=confirm("Verifique si los datos son los correctos");
        if( rsC ){
            AtencionClienteDAO.save_llamada(Cuentas);
            save_llamada_neotel();
        }else{
            $('#table_cuenta_aplicar_gestion').find(':checkbox').attr('checked',true);
        }
    }
}
update_llamada = function ( ) {
    
    //  var idTransaccion=$('#HdIdTransaccionAtencionCliente').val();
    //  if( idTransaccion=='' ) {
    //      $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione llamada','400px'));
    //      $('#'+AtencionClienteDAO.idLayerMessage).effect('pulsate',{},'slow',function(){ $(this).empty(); });    
    //      return false;
    //  }
    
    var rs=validacion.check([
    {
        id:'HdIdTransaccionAtencionCliente',
        required:true,
        errorRequiredFunction:function ( ) {
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione llamada a actualizar','400px'));   
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },

    {
        id:'txtAtencionLlamadaFecha',
        required:true,
        errorRequiredFunction:function ( ) {
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese fecha y hora de llamada','400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    {
        id:'cbLlamadaPeso',
        isNotValue:0,
        errorNotValueFunction : function ( ) {
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccion Peso de llamada','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    {
        id:'cbFinalLlamada',
        isNotValue:0,
        errorNotValueFunction : function ( ) {
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccion final','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    {
        id:'txtAtencionLlamadaMontoCp',
        required:false,
        isDecimal:true,
        isDependence:true,
        idDependence:'txtAtencionLlamadaFechaCp',
        errorRequiredFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese Monto de Compromiso Pago','400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        },
        errorDecimalFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese solo numeros decimales','400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        },
        errorDependenceFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese Fecha de compromiso de pago','400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    },
    {
        id:'txtObservacionLlamada',
        required:true,
        isAlphaNumeric:true,
        errorRequiredFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese observacion de llamada','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        },
        errorAlphaNumericFunction:function( ){
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese solo letras y numeros','300px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    }
    ]);
    
    if( rs ){
        var rsC=confirm("Verifique si los datos ingresados son los correctos");
        if( rsC ) {
            AtencionClienteDAO.update_llamada();
        }
    }
}
cancel_llamada = function ( ) {
    $('#layerFormAtencionLlamada').find('textarea,:text').val('');
    $('#layerFormAtencionLlamada').find('select').val(0);
    $('#txtAtencionClienteNumeroCall,#HdIdTelefono').val('');
    $('#LlamadaFechaInicioTMO,#LlamadaFechaFinTMO').val('');
    $('#hdAlerta').val('0');    
}

loadPrioridadLlamadaAtencionCliente = function ( id ) {
    AtencionClienteDAO.ListarPesoTransaccion( id, AtencionClienteDAO.FillLlamadaPesoLlamada );
//AtencionClienteDAO.ListarPesoTransaccion( );
}                                  
loadPrioridadVisitaAtencionCliente = function ( id ) {
    AtencionClienteDAO.ListarPesoTransaccion( id, AtencionClienteDAO.FillLlamadaPesoVisita );
}
loadEstadoLlamadaAtencionCliente = function ( id ) {
    AtencionClienteDAO.ListarEstado( id, AtencionClienteDAO.FillAtencionEstadoLLamada );
},
load_data_searchTelefonoCliente = function ( ) {
    AtencionClienteDAO.SearchTelefonosCliente();
}

load_data_usuarios_ayudar = function ( ) {
    AtencionClienteDAO.ListarUsuariosAyudar();
    AtencionClienteDAO.ListarUsuariosAsignar();
}

delete_usuarios_asignados = function ( ) {
    
    var ids=$('#DataLayerTableUsuariosAyudar').find(':checked').map(function ( ) {
        return $(this).val();
    }).get().join(",");
    
    var idsLENGTH=$('#DataLayerTableUsuariosAyudar').find(':checked').length;
    
    if( idsLENGTH==0 ) {
        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione usuarios','400px'));
        AtencionClienteDAO.setTimeOut_hide_message();
        return false;
    }
    
    var rs=confirm("Verifique si los usuarios seleccionados son los correctos");
    if( rs ){
        AtencionClienteDAO.DeleteUsuarioAyudar(ids);    
    }
}

save_usuarios_asignar = function ( ) {
    
    var ids=$('#DataLayerTableUsuariosAsignar').find(':checked').map(function ( ) {
        return $(this).val();
    }).get().join(",");
    
    var idsLENGTH=$('#DataLayerTableUsuariosAsignar').find(':checked').length;
    
    if( idsLENGTH==0 ){
        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Seleccione usuarios','400px'));
        AtencionClienteDAO.setTimeOut_hide_message();
        return false;
    }
    
    var rs=confirm("Verifique si los usuarios seleccionados son los correctos");
    if( rs ){
        AtencionClienteDAO.SaveUsuarioAyudar(ids);
    }
}

atencion_cliente_importar_telefonos_gestion_actual = function ( ) {
    var ids=$('#DialogBuscarTelefono #layerTableResultSearchTelefonoCliente').find('input:checked').map(function(){
        return $(this).val();
    }).get().join(",");
    var rs=confirm("Verifique si los telefonos seleccionados son los correctos");
    if( rs ){
        AtencionClienteDAO.AtencionClienteImportarTelefonosGestionActual(ids);
    }
}
display_box_serach_telefonos = function ( ) {
    var idCliente=$('#idClienteMain').val();
    var idCartera=$('#cbAtencionGlobalesCartera').val();
    if( idCliente=='' ) {
        return false;
    }else if( idCartera==0 ) {
        return false;
    }
    $('#DialogBuscarTelefono').dialog('open')
},
leer_ayuda_gestion = function ( id,is_text ){
    AtencionClienteDAO.LeerAyudaGestion(id,is_text); 
}
delete_notas = function ( ) {
    var id=$('#tableNotas').find("input:checked").map(function(){
        return this.value;
    }).get().join(",");
        
    AtencionClienteDAO.DeleteNotas(id);
}
marcar_como_no_leidas = function ( ) {
    var id=$('#tableNotas').find("input:checked").map(function(){
        return this.value;
    }).get().join(",");
    AtencionClienteDAO.MarcarNoLeidoNotas(id);
}
ver_nota = function ( id ) {
    AtencionClienteDAO.NotasPorId(id);      
}
marcar_nota_como_leida = function ( id,element ) {
    var color=$(element).parent().css('color');
    if( color=='rgb(0, 102, 255)' ) {
        AtencionClienteDAO.MarcarLeidoNotas(id,element);
    }
}
marcar_notas_como_importante = function ( ) {
    var id=$('#tableNotas').find("input:checked").map(function(){
        return this.value;
    }).get().join(",");
    AtencionClienteDAO.MarcarNotaComoImportante(id);
}
desmarcar_nota_como_importante = function ( id, element ) {
    AtencionClienteDAO.DesmarcaNotaComoImportante(id,element);
}
prueba_notify = function ( ) {
    //show_box_model_nota
    var opts = {
        pnotify_title: "Alerta",
        pnotify_text: "<button>click</button>",
        pnotify_addclass: "stack-bottomright",
        //pnotify_stack: {"dir1": "up", "dir2": "left", "firstpos1": 15, "firstpos2": 15},
        pnotify_width: '400px',
        pnotify_min_height: '200px',
        pnotify_animation: {
            effect_in: 'show', 
            effect_out: 'slide'
        }
    };
    $.pnotify(opts);
    
}
show_window_actualizar_anexo = function ( element ) {
    var xoffset = $(element).offset();
    $('#DialogActualizarAnexo').css('top',(parseInt(xoffset.top) + 20 )).css('left',( parseInt(xoffset.left) + 110 ));
    $('#DialogActualizarAnexo').slideDown('slow');
}
show_window_actualizar_anexo_neotel = function ( element ) {
    var xoffset = $(element).offset();
    $('#DialogActualizarAnexoNeotel').css('top',(parseInt(xoffset.top) + 20 )).css('left',( parseInt(xoffset.left) + 110 ));
    $('#DialogActualizarAnexoNeotel').slideDown('slow');
}
update_anexo = function ( ) {
    var rs=validacion.check([
    {
        id:'DialogActualizarAnexo #txtAnexoTeleoperador',
        required:true,
        errorRequiredFunction:function ( ) {
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese Anexo','400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    }
    ]);
    
    if( rs ) {
        var rsC = confirm("Verifique si el anexo ingresado es el correcto");
        if( rsC ) {
            AtencionClienteDAO.update_anexo();
        }
    }
    
}
update_anexo_neotel = function ( ) {
    var rs=validacion.check([
    {
        id:'DialogActualizarAnexoNeotel #txtUsuarioNeotelTeleoperador',
        required:true,
        errorRequiredFunction:function ( ) {
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese Usuario','400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    }
    ]);
    
    if( rs ) {
        var rsC = confirm("Verifique si el Usuario ingresado es el correcto");
        if( rsC ) {
            $('#hdUsuarioNeotelTeleoperador').val($('#DialogActualizarAnexoNeotel #txtUsuarioNeotelTeleoperador').val());
            $('#txtUsuarioN').val($('#DialogActualizarAnexoNeotel #txtUsuarioNeotelTeleoperador').val());
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo('Se grabo Usuario','400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
            $('#DialogActualizarAnexoNeotel').slideUp('slow');
            neotelDAO.ponerCampania('10');
        }
    }
    
}
listar_detalle_cuenta = function ( xidcartera, xidcuenta,xcodigo_cliente, element ) {
    AtencionClienteDAO.ListarDetalleCuenta(xidcartera,xidcuenta,xcodigo_cliente,function ( xobject ){
        var html = '';
        var field = '';
        var footer = '';
        var count=0;
        var xservicio=$('#hdCodServicio').val();
        for( i=0;i<xobject.dataDetalleCuenta.length;i++) {
            var operacion = eval(xobject.dataDetalleCuenta[i]);
                
            html+='<tr onclick="listar_pago('+xidcartera+',\''+operacion[xobject.codigo_operacion]+'\', this )" >';
            html+='<td align="center" class="ui-state-default">'+(count+1)+'</td>';
            var fieldcount = 0;
            for( index in operacion ) {
                if( count==0 ) {
                    

                    if(index=='CODIGO OPERACION' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">DOCUMENTO</td>';
                    }
                    else if(index=='ID' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">ID</td>';
                    }
                    else if(index=='MONEDA' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">MONEDA</td>';
                    }

                    else if(index=='DIAS MORA' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">DIAS MORA</td>';
                    }

                    else if(index=='TOTAL DEUDA' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">TOTAL DEUDA</td>';
                    }

                    else if(index=='SALDO CAPITAL' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">SALDO CAPITAL</td>';
                    }

                    else if(index=='SALDO CAPITAL SOLES' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">SALDO CAPITAL SOLES</td>';
                    }

                    else if(index=='SALDO CAPITAL DOLARES' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">SALDO CAPITAL DOLARES</td>';
                    }

                    else if(index=='FECHA VENCIMIENTO' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">FECHA VENCIMIENTO</td>';
                    }

                    else if(index=='FECHA EMISION' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">FECHA EMISION</td>';
                    }

                    else if(index=='MONTO PAGADO' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">MONTO PAGADO</td>';
                    }

                    else if(index=='ULT. F. PAGO' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">ULT. F. PAGO</td>';
                    }

                    else if(index=='cod_zon' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">COD_ZON</td>';
                    }

                    else if(index=='empresa' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">EMPRESA</td>';
                    }

                    else if(index=='zona' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">ZONA</td>';
                    }

                    else if(index=='localidad' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">LOCALIDAD</td>';
                    }

                    else if(index=='vend_actual' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">VEND_ACTUAL</td>';
                    }

                    else if(index=='vend_rtc_actual' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">VEND_RTC_ACTUAL</td>';
                    }

                    else if(index=='supervisor' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">SUPERVISOR</td>';
                    }

                    else if(index=='td' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">TD</td>';
                    }

                    else if(index=='mes_emis' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">MES_EMIS</td>';
                    }

                    else if(index=='ano_emis' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">ANIO_EMIS</td>';
                    }

                    else if(index=='dias_plazo' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">DIAS_PLAZO</td>';
                    }

                    else if(index=='m_vcto' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">M_VCTO</td>';
                    }

                    else if(index=='ano_vcto' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">ANIO_VCTO</td>';
                    }

                    else if(index=='tipo_de_operacion' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">TIPO_OPERACION</td>';
                    }

                    else if(index=='rango_vcto' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">RANGO_VCTO</td>';
                    }

                    else if(index=='linea_de_credito' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">LINEA_DE_CREDITO</td>';
                    }

                    else if(index=='ind_vcto' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">IND_VCTO</td>';
                    }

                    else if(index=='semaforo_de_vencimiento' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">SEMAFORO_DE_VENCTO</td>';
                    }

                    else if(index=='total_convertido_a_dolares' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">TOTAL_CONVERTIDO_A_DOLARES</td>';
                    }

                    else if(index=='total_convertido_a_soles' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">TOTAL_CONVERTIDO_A_SOLES</td>';
                    }

                    else if(index=='glosa' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">GLOSA</td>';
                    }

                    else if(index=='est_letr' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">EST_LETR</td>';
                    }

                    else if(index=='banco' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">BANCO</td>';
                    }

                    else if(index=='num_cobranza' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">NUM_COBRANZA</td>';
                    }

                    else if(index=='referencia' && xservicio!=11 ){
                        field+='<td align="center" class="ui-state-default" style="padding:4px 2px;">REFERENCIA</td>';
                    }

                    

                }

                if(  index=='TOTAL DEUDA' || index=='seguros'|| index=='otros'){
                    html+='<td class="ui-widget-content" style="padding:4px 2px;" align="center">'+formato_numero(operacion[index])+'</td>';
                    fieldcount++;
                }else if(index=='NUMERO DE CUOTAS' || index=='FECHA VENCIMIENTO' || index=='MONEDA'){
                    html+='<td class="ui-widget-content" style="padding:4px 2px;" align="center">'+operacion[index]+'</td>';
                }else{
                    html+='<td class="ui-widget-content" style="padding:4px 2px;" align="center">'+operacion[index]+'</td>';
                }
                
            }
            html+='</tr>';
            if( count == 0 ) {
                footer+='';
            }
            count++;
        }
            

        $('#tb_detalle_factura_operacion').html('<tr><td class="ui-state-default ui-corner-tl" style="padding:0px 3px;">&nbsp;</td>'+field+'</tr>'+html+footer);
        $('#tb_detalle_factura_operacion').find('tr:gt(0)').hover(function(){
            $(this).find('td:gt(0)').addClass('ui-state-hover');
        },function(){
            $(this).find('td:gt(0)').removeClass('ui-state-hover');
        });
        $('#tb_detalle_factura_operacion').find('tr:gt(0)').click(function() {
            $(this).find('td:gt(0)').addClass("ui-state-highlight").parent().siblings().find('td:gt(0)').removeClass("ui-state-highlight");
        });

    });
$("#cargando").css("display","none")
}
listar_pago = function ( xidcartera, xcodigo_operacion, element ) {
    AtencionClienteDAO.ListarPago(xidcartera, xcodigo_operacion,function( xobject ) {
            
        var html = '';
        var field = '';
        var footer = '';
        var count=0;
        //for( i=0;i<xobject.dataPago.length;i++) {
        //              var pago = eval(xobject.dataPago[i]);
        //              
        //              html+='<tr>';
        //              html+='<td align="center" class="ui-state-default" style="width:30px;padding:1px 10px;">'+(count+1)+'</td>';
        //              var fieldcount = 0;
        //              for( index in pago ) {
        //                  if( count==0 ) {
        //                      field+='<td align="center" class="ui-state-default" style="padding:3px 15px;">'+(index.toUpperCase())+'</td>';
        //                  }
        //                  html+='<td class="ui-widget-content" style="padding:3px 15px;" align="center">'+pago[index]+'</td>';
        //                  fieldcount++;
        //              }
        //              html+='</tr>';
        //              if( count == 0 ) {
        //                  footer+='<tr><td style="height:20px;" class="ui-state-default ui-corner-bottom" colspan="'+(fieldcount+1)+'"></td></tr>';
        //              }
        //              count++;
        //          }
            
        for( i=0;i<xobject.dataPago.length;i++) {
            var pago = eval(xobject.dataPago[i]);
                
            html+='<tr>';
            html+='<td align="center" class="ui-state-default" >'+(count+1)+'</td>';
            var fieldcount = 0;
            for( index in pago ) {
                if( count==0 ) {
                    field+='<td align="center" class="ui-state-default" style="padding:0px 3px;">'+(index.toUpperCase())+'</td>';
                }
                html+='<td class="ui-widget-content" style="padding:0px 3px;" align="center">'+pago[index]+'</td>';
                fieldcount++;
            }
            html+='</tr>';
            if( count == 0 ) {
                //footer+='<tr><td style="height:20px;" class="ui-state-default ui-corner-bottom" colspan="'+(fieldcount+1)+'"></td></tr>';
                footer+='';
            }
            count++;
        }
        //$('#table_cuenta').empty();
        //$(field+'</tr>'+html+footer).find('tr:eq(1)').children('td:last').addClass('ui-corner-tr').appendTo('#table_cuenta');
            
        $(element).parent().parent().parent().find('table[title="pagos"]').html('<tr><td class="ui-state-default ui-corner-tl" style="padding:0px 3px;">&nbsp;</td>'+field+'</tr>'+html+footer);
        //$('#tabPagos').trigger('click');
        $('#table_pagos').html('<tr><td class="ui-state-default ui-corner-tl" style="padding:0px 3px;">&nbsp;</td>'+field+'</tr>'+html+footer);
        $('#table_pagos tr:eq(0)').find('td:last').addClass('ui-corner-tr');
        $('#table_pagos tr:gt(0)').hover(function(){
            $(this).find('td:gt(0)').addClass('ui-state-hover');
        },function(){
            $(this).find('td:gt(0)').removeClass('ui-state-hover');
        });
        $('#table_pagos tr:gt(0)').click(function() {
            $(this).find('td:gt(0)').addClass("ui-state-highlight").parent().siblings().find('td:gt(0)').removeClass("ui-state-highlight");
        });
            
    });
}
/*LLENA CUENTA-DETALLE DE CUENTA PAGOS*/
listar_cuenta = function ( xidservicio, xcartera, xidcliente_cartera ) {
    AtencionClienteDAO.ListarDataCuenta( xcartera, xidcliente_cartera ,function(xobject){
        /*****************/
        var xhtml = '';
        var html = '';
        var cuentasAcuerdoPago = "<tr style='text-align: center;font-weight: bold;font-family: Robotto;'><td>Número Cuenta</td><td>Deuda</td><td>Saldo Actual</td><td></td></tr>";
        var label_total_deuda = '';
        var label_saldo_total = 'SALDO TOTAL';
        var gb_total_deuda = 0;

        var retirado = '';
        var sum_total_saldo=0;

        var cant_docum=0;
        var tot_impo_orig_dol=0;
        var tot_impo_orig_sol=0;
        var tot_saldos_sol=0;
        var tot_saldos_dol=0;

        for( i=0;i<xobject.dataCuenta.length;i++ ) {
            cant_docum=cant_docum+1;
            var dhtml = '';
            if( i==0 ) {
                dhtml+='<td class="ui-state-default ui-corner-tl" style="padding:4px 2px;">&nbsp;</td>';
                for( j=0;j<xobject.dataCuenta[i].length;j++ ) {
                    if( xobject.dataCuenta[i][j].campoT == 'total_deuda' ) {
                        label_total_deuda = (xobject.dataCuenta[i][j].label).toLowerCase();
                    }
                    if( xobject.dataCuenta[i][j].campoT == 'saldo_capital' ) {
                        label_saldo_total = ( xobject.dataCuenta[i][j].label ).toLowerCase();
                    }
                    if( xobject.dataCuenta[i][j].campoT != 'status' && xobject.dataCuenta[i][j].campoT != 'estado_pago' && xobject.dataCuenta[i][j].campoT != 'estado_cuenta' && xobject.dataCuenta[i][j].campoT != 'idcuenta' && xobject.dataCuenta[i][j].campoT != 'ul_fcpg' && xobject.dataCuenta[i][j].campoT != 'ultimo_monto_cp' && xobject.dataCuenta[i][j].campoT != 'ul_estado' && xobject.dataCuenta[i][j].campoT != 'is_send' && xobject.dataCuenta[i][j].campoT != 'corte_focalizado' && xobject.dataCuenta[i][j].campoT != 'estado' ) {
                        dhtml+='<td class="ui-state-default" style="padding:4px 2px;" align="center" >'+(xobject.dataCuenta[i][j].label.toUpperCase())+'</td>';
                    }
                }
                dhtml+='<td class="ui-state-default ui-corner-tr" style="padding:4px 2px;">&nbsp;</td>';
                xhtml+='<tr>'+dhtml+'</tr>';
                dhtml='';
            }
            
            var idcuenta = 0;
            var is_send = '';
            var corte_focalizado = '';
            var estado = '';
            var estado_cuenta = '';
            var numero_cuenta = '';
            var moneda = '';
            var total_deuda = '';
            var monto_pagado = '';
            var saldo_actual = '';
            var telefono = '';
            
            var tipo_tarjeta = '';
            var dias_mora = '';
            var saldo_total = 0;
            var deuda_vencida = 0 ; 
            var deuda_por_vencer = 0 ; 
            var cantidad = 0 ;
            var enviar_cargo='';
            
            dhtml+='<td class="ui-state-default" style="padding:4px 2px;">'+(i+1)+'</td>';
            for( j=0;j<xobject.dataCuenta[i].length;j++ ) {
                //alert(xobject.dataCuenta[i][j].dato);
                if( xobject.dataCuenta[i][j].campoT == 'status' ) {
                    if( xobject.dataCuenta[i][j].dato != '' ) {
                        $('#lbMessageGlobalGest').text(xobject.dataCuenta[i][j].dato);
                        $('#lbMessageGlobalGest').slideDown();
                    }
                }else if( xobject.dataCuenta[i][j].campoT == 'estado_pago' ) {
                    if( xobject.dataCuenta[i][j].dato != '' ) {
                        $('#lbMessageGlobalGest').text(xobject.dataCuenta[i][j].dato);
                        $('#lbMessageGlobalGest').slideDown();
                    }
                }else if( xobject.dataCuenta[i][j].campoT == 'idcuenta' ){
                    idcuenta = xobject.dataCuenta[i][j].dato ;
                }else if( xobject.dataCuenta[i][j].campoT == 'ul_fcpg' || xobject.dataCuenta[i][j].campoT == 'ultimo_monto_cp' || xobject.dataCuenta[i][j].campoT == 'ul_estado' ){
                    
                }else if( xobject.dataCuenta[i][j].campoT == 'numero_cuenta' ){
                    dhtml+='<td class="ui-widget-content" style="padding:4px 2px;" align="center">'+xobject.dataCuenta[i][j].dato+'</td>';
                    numero_cuenta = xobject.dataCuenta[i][j].dato ;
                }else if( xobject.dataCuenta[i][j].campoT == 'moneda' ){
                    dhtml+='<td class="ui-widget-content" style="padding:4px 2px;" align="center">'+xobject.dataCuenta[i][j].dato+'</td>';
                    moneda = xobject.dataCuenta[i][j].dato;
                }else if( xobject.dataCuenta[i][j].campoT == 'telefono' ){
                    dhtml+='<td class="ui-widget-content" style="padding:4px 2px;" align="center">'+xobject.dataCuenta[i][j].dato+'</td>';
                    telefono = xobject.dataCuenta[i][j].dato ;
                }else if( xobject.dataCuenta[i][j].campoT == 'total_deuda' ){
                    dhtml+='<td class="ui-widget-content" style="padding:4px 2px;" align="center">'+formato_numero(xobject.dataCuenta[i][j].dato)+'</td>';
                    total_deuda = xobject.dataCuenta[i][j].dato;
                    gb_total_deuda = gb_total_deuda + parseFloat( xobject.dataCuenta[i][j].dato );
                }else if( xobject.dataCuenta[i][j].campoT == 'monto_pagado' ){
                    dhtml+='<td class="ui-widget-content" style="padding:4px 2px;" align="center">'+formato_numero(xobject.dataCuenta[i][j].dato)+'</td>';
                    monto_pagado = xobject.dataCuenta[i][j].dato ;                    
                }else if( xobject.dataCuenta[i][j].campoT == 'SALDO' ){
                    dhtml+='<td class="ui-widget-content" style="padding:4px 2px;" align="center">'+formato_numero(xobject.dataCuenta[i][j].dato)+'</td>';
                    saldo_actual = xobject.dataCuenta[i][j].dato ;
                }else if( xobject.dataCuenta[i][j].campoT == 'is_send'  ){
                    is_send = xobject.dataCuenta[i][j].dato ;
                }else if( xobject.dataCuenta[i][j].campoT == 'corte_focalizado' ){
                    corte_focalizado = xobject.dataCuenta[i][j].dato ;
                }else if( xobject.dataCuenta[i][j].campoT == 'estado' ){
                    estado = xobject.dataCuenta[i][j].dato ;
                }else if( xobject.dataCuenta[i][j].campoT == 'RETIRADO' ){
                    dhtml+='<td class="ui-widget-content" style="padding:4px 2px;" align="center">'+xobject.dataCuenta[i][j].dato+'</td>';
                    if( xobject.dataCuenta[i][j].dato == 'NO' ){
                        retirado = xobject.dataCuenta[i][j].dato ;
                    }else{
                        retirado = '<font color="red" size=2><b>'+xobject.dataCuenta[i][j].dato+'</b></font>';
                        cantidad = cantidad + 1;
                    }                    
                }else if( xobject.dataCuenta[i][j].campoT == 'TIPO TARJETA' ){
                    dhtml+='<td class="ui-widget-content" style="padding:4px 2px;" align="center">'+xobject.dataCuenta[i][j].dato+'</td>';
                    tipo_tarjeta = xobject.dataCuenta[i][j].dato ;
                }else if( xobject.dataCuenta[i][j].campoT == 'dias_mora' ){
                    dhtml+='<td class="ui-widget-content" style="padding:4px 2px;" align="center">'+xobject.dataCuenta[i][j].dato+'</td>';
                    dias_mora = xobject.dataCuenta[i][j].dato ;
                }else if( xobject.dataCuenta[i][j].campoT == 'saldo_capital' ){
                    dhtml+='<td class="ui-widget-content" style="padding:4px 2px;" align="center">'+formato_numero(xobject.dataCuenta[i][j].dato)+'</td>';
                    saldo_total = xobject.dataCuenta[i][j].dato ;
                }else if( xobject.dataCuenta[i][j].campoT == 'MONTO_VENCIDO' ){
                    dhtml+='<td class="ui-widget-content" style="padding:4px 2px;" align="center">'+formato_numero(xobject.dataCuenta[i][j].dato)+'</td>';
                    deuda_vencida = xobject.dataCuenta[i][j].dato ;
                }else if( xobject.dataCuenta[i][j].campoT == 'MONTO_POR_VENCER' ){
                    dhtml+='<td class="ui-widget-content" style="padding:4px 2px;" align="center">'+formato_numero(xobject.dataCuenta[i][j].dato)+'</td>';
                    deuda_por_vencer = xobject.dataCuenta[i][j].dato ;
                }else if( xobject.dataCuenta[i][j].campoT == 'enviar_cargo' ){
                    enviar_cargo = xobject.dataCuenta[i][j].dato ;
                }else if( xobject.dataCuenta[i][j].campoT == 'num_doc' ){
                    num_doc = xobject.dataCuenta[i][j].dato ;
                }else if( xobject.dataCuenta[i][j].campoT == 'empresa' ){
                    empresa = xobject.dataCuenta[i][j].dato ;
                }else if( xobject.dataCuenta[i][j].campoT == 'td' ){
                    td = xobject.dataCuenta[i][j].dato ;
                }else if( xobject.dataCuenta[i][j].campoT == 'mon' ){
                    mon = xobject.dataCuenta[i][j].dato ;
                }else if( xobject.dataCuenta[i][j].campoT == 'fecha_doc' ){
                    fecha_doc = xobject.dataCuenta[i][j].dato ;
                }else if( xobject.dataCuenta[i][j].campoT == 'fecha_vcto' ){
                    fecha_vcto = xobject.dataCuenta[i][j].dato ;
                }else if( xobject.dataCuenta[i][j].campoT == 'dias_transc_vcto_of' ){
                    dias_transc_vcto_of = xobject.dataCuenta[i][j].dato ;
                }else if( xobject.dataCuenta[i][j].campoT == 'importe_original' ){
                    importe_original = xobject.dataCuenta[i][j].dato ;
                }else if( xobject.dataCuenta[i][j].campoT == 'total_convertido_a_dolares' ){
                    total_convertido_a_dolares = xobject.dataCuenta[i][j].dato ;
                }else if( xobject.dataCuenta[i][j].campoT == 'total_convertido_a_soles' ){
                    total_convertido_a_soles = xobject.dataCuenta[i][j].dato ;
                }else if( xobject.dataCuenta[i][j].campoT == 'rango_vcto' ){
                    rango_vcto = xobject.dataCuenta[i][j].dato ;
                }else if( xobject.dataCuenta[i][j].campoT == 'semaforo_de_vencimiento' ){
                    semaforo_de_vencimiento = xobject.dataCuenta[i][j].dato ;
                }else if(xobject.dataCuenta[i][j].campoT == 'marca_cat'){
                    marca_cat = xobject.dataCuenta[i][j].dato ;
                }else if(xobject.dataCuenta[i][j].campoT == 'est_letr'){
                    est_letr = xobject.dataCuenta[i][j].dato ;
                }else if(xobject.dataCuenta[i][j].campoT == 'banco'){
                    banco = xobject.dataCuenta[i][j].dato ;
                }else if(xobject.dataCuenta[i][j].campoT == 'num_cobranza'){
                    num_cobranza = xobject.dataCuenta[i][j].dato ;
                }else{
                    dhtml+='<td class="ui-widget-content" style="padding:4px 2px;" align="center">'+xobject.dataCuenta[i][j].dato+'</td>';
                }

                

                
            }
            dhtml+='<td class="ui-state-default" style="padding:4px 2px;">&nbsp;</td>';
            xhtml+='<tr onclick="AtencionClienteDAO.DatosAdicionalesCuenta( '+xidservicio+','+xcartera+','+idcuenta+',this);listar_detalle_cuenta('+xcartera+','+idcuenta+',this)" >'+dhtml+'</tr>';
            
            var estadoFacturaDigital = '';
            if( is_send == '1'){
                estadoFacturaDigital = '<span style="color:red;">Enviado</span>';
            }
            var corteFocalizado = '';
            if( corte_focalizado == '1'){
                corteFocalizado = '<span style="color:red;">Si</span>';
            }
            var xclass = "ui-widget-content";
            if( estado == '0' ) {
                xclass = "ui-state-error";
            }

            //alert(importe_original);

            // tot_impo_orig=tot_impo_orig+parseFloat(importe_original);
            var imp_dol=0;
            var imp_sol=0;
            if(mon=="US"){
                imp_dol=parseFloat(importe_original);
            }else if(mon=="MN"){
                imp_sol=parseFloat(importe_original);
            }

            tot_impo_orig_dol=tot_impo_orig_dol+imp_dol;
            tot_impo_orig_sol=tot_impo_orig_sol+imp_sol;
            tot_saldos_sol=tot_saldos_sol+parseFloat(total_convertido_a_soles);
            tot_saldos_dol=tot_saldos_dol+parseFloat(total_convertido_a_dolares);

            color_rango="";
            if(rango_vcto=='1-(09 A 30 DIAS)' || rango_vcto=='2-(31 A 60 DIAS)' ||  rango_vcto=='3-(61 A 90 DIAS)' ||  rango_vcto=='4-(91 A 120 DIAS)' ||  rango_vcto=='5-(121 A 360 DIAS)'){
                color_rango="background:red;color:white;";
            }else if(rango_vcto=='0-(01 A 08 DIAS)'){
                color_rango="background:yellow;color:black;";
            }else if(rango_vcto=='8-(VIGENTE)'){
                color_rango="background-color:#39FF02;color:black;";
            }else if(rango_vcto=='6-(MAS DE 360 DIAS)'){
                color_rango="background:black;color:white;";
            }else if(rango_vcto=='9-(SALDO A FAVOR)'){
                color_rango="background-color:#5F6607;color:white;";
            }else if(rango_vcto=='7-(COB. JUDICIAL)'){
                color_rango="background-color:#727271;color:yellow;";
            }

            disabled="";
            if(td=='NC' || td=='PA'){
                disabled="disabled";
            }




                html+='<tr class="'+xclass+'" onclick="activar_row(this);" value="'+idcuenta+'">';
                cuentasAcuerdoPago+='<tr class="'+xclass+'" onclick="activar_row(this);" value="'+idcuenta+'">';
                if( estado_cuenta != '' ){
                    html+='<td><div style="position:absolute;padding:3px;margin-top:6px;" class="ui-state-highlight ui-corner-all">'+estado_cuenta+'</div></td>';
                }
                html+='<td class="ui-state-default" style="padding:2px 0px;height:15px;width:15px;border:1px solid #E0CFC2;" align="center"></td>';
                html+='<td align="center" style="padding:2px 0px;height:15px;width:50px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:9px;" >'+retirado+'</td>';
                html+='<td align="center" style="padding:2px 0px;height:15px;width:60px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-left:1px solid #E0CFC2;font-size:9px;" >'+empresa+'</td>';
                html+='<td align="center" style="padding:2px 0px;height:15px;width:25px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:9px;" >'+td+'</td>';
                
                html+='<td align="center" style="padding:2px 0px;height:15px;width:80px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:9px;" >'+num_doc+'</td>';                                              
                html+='<td align="center" style="padding:2px 0px;height:15px;width:60px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:9px;" >'+fecha_doc+'</td>';                                              
                html+='<td align="center" style="padding:2px 0px;height:15px;width:60px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:9px;" >'+fecha_vcto+'</td>';                                              
                html+='<td align="center" style="padding:2px 0px;height:15px;width:53px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:9px;" >'+dias_transc_vcto_of+'</td>';
                html+='<td align="center" style="padding:2px 0px;height:15px;width:100px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:9px;'+color_rango+'" >'+rango_vcto+'</td>';
                html+='<td align="center" style="padding:2px 0px;height:15px;width:50px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:9px;" >'+est_letr+'</td>';
                html+='<td align="center" style="padding:2px 0px;height:15px;width:50px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:9px;" >'+banco+'</td>';
                html+='<td align="center" style="padding:2px 0px;height:15px;width:80px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:9px;" >'+num_cobranza+'</td>';
                // html+='<td align="center" style="padding:2px 0px;height:15px;width:50px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:9px;" >'+marca_cat+'</td>';
                //html+='<td align="center" style="padding:2px 0px;height:15px;width:53px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:9px;" >'+semaforo_de_vencimiento+'</td>';
                html+='<td align="center" style="padding:2px 0px;height:15px;width:25px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:9px;" >'+mon+'</td>';
                html+='<td align="center" style="padding:2px 0px;height:15px;width:68px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:9px;text-align:right;" >'+formato_numero(importe_original)+'</td>';                                              
                html+='<td align="center" style="padding:2px 0px;height:15px;width:68px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:9px;text-align:right;" >'+formato_numero(total_convertido_a_dolares)+'</td>';                                              
                html+='<td align="center" style="padding:2px 0px;height:15px;width:68px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:9px;text-align:right;" >'+formato_numero(total_convertido_a_soles)+'</td>';                                              
                
                html+='<td align="center" style="padding:2px 0px;height:15px;width:70px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;"><input '+disabled+' xaria="'+idcuenta+'" name="txtFechaCpCuenta" readonly="readonly" class="inputText" style="width:60px;height:10px; font-size:11px" type="text" /></td>';
                html+='<td align="center" style="padding:2px 0px;height:15px;width:70px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;"><input '+disabled+' xaria="'+idcuenta+'" name="txtMontoCpCuenta" class="inputText" style="width:60px;height:10px; font-size:11px" type="text" /></td>';
                html+='<td align="center" style="padding:2px 0px;height:15px;width:80px;white-space:pre-line;padding:2px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;"><select '+disabled+' name="cbMonedaCpCuenta"><option value="SOLES">SOLES</option><option value="DOLARES">DOLARES</option><option value="EUROS">EUROS</option></select></td>';
                
                
                

                var attr = '';
                if( estado == '1' ) {
                attr = '  ';
                }
                //html+='<td align="center" style="width:20px;white-space:pre-line;height:15px;padding:3px 0;border-bottom:1px solid #E0CFC2;" onclick="load_detalle_cuenta_pago('+idcuenta+')" ><span class="ui-icon ui-icon-plusthick"></span></td>';
                //html+='<td align="center" style="width:20px;white-space:pre-line;padding:3px 0;border-bottom:1px solid #E0CFC2;" onclick="mostrar_refinanciamiento('+idcuenta+',\''+numero_cuenta+'\',\''+moneda+'\','+total_deuda+')" ><span class="ui-icon ui-icon-suitcase"></span></td>';                             
                html+='<td align="center" class="ui-state-default" style="padding:2px 0px;height:14px;width:25px;border-bottom:1px solid #E0CFC2;font-size:10px"><input '+attr+' type="checkbox" value="'+idcuenta+'" /></td>';
                
                var fecha_creacion_cartera = $('#txtFechaCreacion_tblDCBC').val();
                var fecha_modificacion_cartera = $('#txtFechaModificacion_tblDCBC').val();
                var fecha_filtro=fecha_modificacion_cartera;
                var dias_actuales = $('#txtDiferenciaFechaActualAndFiltroFecha_tblDCBC').val();
                var lbl_dias_mora_permitido="";
                if($.trim(fecha_modificacion_cartera) == ""){
                    fecha_filtro=fecha_creacion_cartera;
                }
                
                if(parseInt(fecha_filtro)+parseInt(dias_mora) <=90){
                    // lbl_dias_mora_permitido ='<td align="center" style="font-family:Robotto;color:green;font-size:10px;padding:2px 0px;height:27px;width:64px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" >DIAS MORA ACEPTADO</td>';
                }else{
                    // lbl_dias_mora_permitido ='<td align="center" style="font-family:Robotto;color:red;font-size:10px;padding:2px 0px;height:27px;width:64px;white-space:pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" >DIAS MORA RECHAZADO</td>';
                }

                html+= lbl_dias_mora_permitido;

                html+='</tr>';
                cuentasAcuerdoPago+='</tr>';
                
                sum_total_saldo+=parseFloat(saldo_actual);
                //alert(sum_total_saldo);
                //alert(saldo_actual);

        }



        xhtml+='<tr><td class="ui-state-default ui-corner-bottom" style="height:20px;" colspan="'+(xobject.dataCuenta[0].length-8)+'"></td></tr>';
        
        $('#txtDeudaCuotificacion').val(saldo_total);
        $('#txtGBDeudaCuotificacion,#txtTotalRefinanciarCuotificacion').val(gb_total_deuda);      

        $('#table_cuenta_new').html( xhtml );
        
        $('#table_cuenta_new').find('tr:gt(0)').hover(function(){
            $(this).find('td:gt(0)').addClass('ui-state-hover');
        },function(){
            $(this).find('td:gt(0)').removeClass('ui-state-hover');
        });
        $('#table_cuenta_new').find('tr:gt(0)').click(function() {
            $(this).find('td:gt(0)').addClass("ui-state-highlight").parent().siblings().find('td:gt(0)').removeClass("ui-state-highlight");
        });
        //alert(html);
        $('#table_cuenta_aplicar_gestion').html(html);
        $('#tblCuentasAcuerdoPago').html(cuentasAcuerdoPago);
        $('#trAtencionCienteLabelCuentaTotalDeudaAplicar').text(label_total_deuda);
        $('#trAtencionCienteLabelCuentaSaldoTotalAplicar').text(label_saldo_total);
        
        $('#table_cuenta_aplicar_gestion').find("tr").find(":text[name='txtFechaCpCuenta']").datepicker({
            dateFormat:'yy-mm-dd',
            dayNamesMin:['D','L','M','M','J','V','S'],
            monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre'], 
            currentText : 'Now'
        });

        
        
        $('#tr_header_cuenta_aplicar_gestion').find('td').css( { 'float':'left','display':'none' } );
        $('#table_cuenta_aplicar_gestion tr').find('td').css( { 'float':'left','display':'none' } );
        //alert($('#hdNomServicio').val());
        var lhc_data = AtencionClienteDAO.IndexCabecerasCuenta[ $('#hdNomServicio').val() ].split(",");
        for( i=0;i<lhc_data.length;i++ ) {
            $('#tr_header_cuenta_aplicar_gestion').find('td:eq('+lhc_data[i]+')').css('display','block');
            if( estado_cuenta != '' ) {
                $('#table_cuenta_aplicar_gestion tr').find('td:eq('+(lhc_data[i]+1)+')').css('display','block');
            }else{
                $('#table_cuenta_aplicar_gestion tr').find('td:eq('+lhc_data[i]+')').css('display','block');
            }
        }

        
        var soles = 0;
        var dolar = 0;
        var vac = 0;
        for (var t=0;t<xobject['dataCuenta'].length;t++)
        { 
            jQuery.each(xobject['dataCuenta'][t], function(k, v) {

                    if((v.dato=='NO' || v.dato=='BIMONEDA') && v.campoT=='RETIRADO') {

                        jQuery.each(xobject['dataCuenta'][t], function(kn, vn) {

                            if(vn.dato=='PEN') {
                                jQuery.each(xobject['dataCuenta'][t], function(kp, vp) {
                                    if(vp.campoT=='total_deuda') {
                                        soles = soles + parseFloat(vp.dato)
                                    }
                                });
                            }
                            if(vn.dato=='USD') {
                                jQuery.each(xobject['dataCuenta'][t], function(ku, vu) {
                                    if(vu.campoT=='total_deuda') {
                                        dolar = dolar + parseFloat(vu.dato)
                                    }
                                });
                            }
                            if(vn.dato=='VAC') {
                                jQuery.each(xobject['dataCuenta'][t], function(kv, vv) {
                                    if(vv.campoT=='total_deuda') {
                                        vac = vac + parseFloat(vv.dato)
                                    }
                                });
                            }

                        });

                    }

            });
        }

        

        // $("#visor_sumtotal").html('<div style="display:inline;">    Cant. Docum.= <span style="color:#6E1C3A;font-weight:bold;font-size:12px;" id="cant_docum"></span> &#9646;     Total Imp.Orig. $= <span style="color:#6E1C3A;font-weight:bold;font-size:12px;" id="tot_impo_orig_dol"> &#9646;     Total Imp.Orig. S/.= <span style="color:#6E1C3A;font-weight:bold;font-size:12px;" id="tot_impo_orig_sol"> &#9646;     Total Saldos S/.= <span style="color:#6E1C3A;font-weight:bold;font-size:12px;" id="tot_saldos_sol"></span> &#9646;     Total Saldos $= <span style="color:#6E1C3A;font-weight:bold;font-size:12px;" id="tot_saldos_dol"></span></div>');

        var htmltot="";

        htmltot+='<div style="display:inline;">';
            htmltot+='Cant. Docum.= <span style="color:#6E1C3A;font-weight:bold;font-size:12px;" id="cant_docum">123</span> &#9646; ';
            htmltot+='Total Imp.Orig. $= <span style="color:#6E1C3A;font-weight:bold;font-size:12px;" id="tot_impo_orig_dol"></span> &#9646; ';
            htmltot+='Total Imp.Orig. S/.= <span style="color:#6E1C3A;font-weight:bold;font-size:12px;" id="tot_impo_orig_sol"></span> &#9646; ';
            htmltot+='Total Saldos S/.= <span style="color:#6E1C3A;font-weight:bold;font-size:12px;" id="tot_saldos_sol"></span></span> &#9646; ';
            htmltot+='Total Saldos $= <span style="color:#6E1C3A;font-weight:bold;font-size:12px;" id="tot_saldos_dol"></span>'
        htmltot+='</div>';
        $("#visor_sumtotal").html(htmltot)

        $("#cant_docum").text(cant_docum);
        $("#tot_impo_orig_dol").text(formato_numero(tot_impo_orig_dol));
        $("#tot_impo_orig_sol").text(formato_numero(tot_impo_orig_sol));
        $("#tot_saldos_sol").text(formato_numero(tot_saldos_sol));
        $("#tot_saldos_dol").text(formato_numero(tot_saldos_dol));

    });
}
/*jmore300612*/
enviar_cargo=function (xidcuenta){
    AtencionClienteDAO.EnviarCargo(xidcuenta);
}
load_detalle_cuenta_pago = function ( xidcuenta ) {
    
    var html = '';
    var field = '';
    var idcartera = $('#IdCartera').val();
    var moneda = '';
    var numero_cuenta = '';
    var codigo_operacion = '';
    var iddetalle_cuenta = '';
    
    AtencionClienteDAO.ListarDataDetalleCuenta( xidcuenta, idcartera, 
                                               function ( obj ) {
                                                   
                                                   for( i=0;i<obj.Ini.length;i++ ) {
                                                      var data = eval(obj.Ini[i]); 
                                                      html+='<tr>';
                                                      for( index in data  ) {
                                                          if( index == 'iddetalle_cuenta' ) {
                                                             iddetalle_cuenta = data[index];
                                                          }else if ( index == 'codigo_operacion' ) {
                                                              codigo_operacion = data[index];
                                                          }else if ( index == 'numero_cuenta' ) {
                                                              numero_cuenta = data[index];
                                                          }else if ( index == 'moneda' ) {
                                                              moneda = data[index];
                                                          }else {
                                                              html+='<td class="ui-widget-content" align="center" style="padding: 2px 0px;">'+data[index]+'</td>';
                                                              field+='<td class="ui-state-default" align="center" style="padding: 3px 0px;">'+index+'</td>';
                                                          }
                                                      }
                                                        html+='<td class="ui-widget-content" align="center" style="padding: 2px 0px;"><input name="txtmonto_pagar_'+iddetalle_cuenta+'" type="text" style="width:70px;" class="cajaForm" /></td>';
                                                        html+='<td class="ui-widget-content" align="center" style="padding: 2px 0px;"><input name="txtfecha_pago_'+iddetalle_cuenta+'" type="text" style="width:70px;" class="cajaForm" /></td>';
                                                        html+='<td class="ui-widget-content" align="center" style="padding: 2px 0px;"><select name="cbestado_pago_'+iddetalle_cuenta+'" class="combo"><option value="0">--Seleccione--</option><option value="CANCELADO">CANCELADO</option><option value="AMORTIZADO">AMORTIZADO</option><select/></td>';
                                                        html+='<td class="ui-widget-content" align="center" style="padding: 2px 0px;"><input name="txtagencia_'+iddetalle_cuenta+'" type="text" style="width:120px;" class="cajaForm" /></td>';
                                                        html+='<td class="ui-widget-content" align="center" style="padding: 2px 0px;"><textarea name="txtobservacion_'+iddetalle_cuenta+'" style="width:180px;height:40px;" class="textareaForm"></textarea></td>';
                                                        html+='<td onclick="guardar_pago('+iddetalle_cuenta+',\''+numero_cuenta+'\',\''+moneda+'\',\''+codigo_operacion+'\', $(this).parent() )" class="ui-widget-content" align="center" style="padding: 2px 0px;"><span class="ui-icon ui-icon-disk"></span></td>';
                                                      html+='</tr>';
                                                   }
                                                   field+='<td class="ui-state-default" align="center" style="padding: 3px 0px;">MONTO PAGAR</td>';
                                                   field+='<td class="ui-state-default" align="center" style="padding: 3px 0px;">FECHA PAGO</td>';
                                                   field+='<td class="ui-state-default" align="center" style="padding: 3px 0px;">ESTADO PAGO</td>';
                                                   field+='<td class="ui-state-default" align="center" style="padding: 3px 0px;">AGENCIA</td>';
                                                   field+='<td class="ui-state-default" align="center" style="padding: 3px 0px;">OBSERVACION</td>';
                                                   field+='<td class="ui-state-default" align="center" style="padding: 3px 0px;"></td>';
                                                   html='<tr class="ui-state-default">'+field+'</tr>'+html;
                                                   
                                                   $('#tbCuentasPagarAtencionCliente').html(html);
                                                   $('#tbCuentasPagarAtencionCliente tr:gt(0)').find(':text[name^="txtfecha_pago"]').mask("2099-99-99");
                                                   $('#contentTbCuentasPagarAtencionCliente').fadeIn();
                                                   
                                                }, 
                                                function ( ) {
                                                    
                                                    } );
    
}
guardar_pago = function ( xiddetalle_cuenta, xnumero_cuenta, xmoneda, xcodigo_operacion, p_jquery ) {
    var idcartera = $('#IdCartera').val(); 
    var usuario_creacion = $('#hdCodUsuario').val(); 
    var monto_pagado = p_jquery.find(':text[name^="txtmonto_pagar"]').val();
    var fecha_pago = p_jquery.find(':text[name^="txtfecha_pago"]').val();
    var estado_pago = p_jquery.find('select[name^="cbestado_pago"]').val();
    var agencia = p_jquery.find(':text[name^="txtagencia"]').val();
    var observacion = p_jquery.find('textarea[name^="txtobservacion"]').val();
    
    var rs = confirm("Verifique si los datos ingresados son los correctos");
    
    if( rs ) {
    
        AtencionClienteDAO.GuardarPago( idcartera, xiddetalle_cuenta, xnumero_cuenta, xmoneda, xcodigo_operacion, monto_pagado, fecha_pago, estado_pago, agencia, observacion, usuario_creacion,
                                       function ( obj ) {
                                                    if(obj.rst){
                                                        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
                                                        AtencionClienteDAO.setTimeOut_hide_message();
                                                    }else{
                                                        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'350px'));
                                                        AtencionClienteDAO.setTimeOut_hide_message();
                                                    }
                                                  },
                                       function ( ) {
                                                    
                                                  });
    }
     
}
/*jmore300612*/
/* /LLENA CUNETA-DETALLE DE CUETA PAGOS*/
listar_cliente = function ( xidservicio, xcodigo_cliente, xidcartera,xidcliente_cartera ) {
    AtencionClienteDAO.ListarCliente(xidservicio, xidcartera, xcodigo_cliente,xidcliente_cartera, function ( obj ){
        var html='';
        for( i=0;i<obj.dataCliente.length;i++ ) { 

            // console.log(obj.dataCliente[i]);

            var cliente = eval(obj.dataCliente[i]);
            html+='<tr>';
            for( indice in cliente ) {
                if(indice=='CODIGO_CLIENTE' ||  indice=='CLIENTE' ||  indice=='NUMERO DOC' || indice=='LINEA_CREDITO'){
                html+='<td align="center" style="padding:3px 8px;" class="ui-state-default">'+indice+'</td>';
                html+='<td align="center" style="padding:3px 8px;" class="ui-widget-content" >'+cliente[indice]+'</td>';
                }
            }
            html+='</tr>';
            html+='<tr>';
            for (indice in cliente){
                if(indice=='CARTERA' || indice=='FECHA_AL'){
                html+='<td align="center" style="padding:3px 8px;" class="ui-state-default">'+indice+'</td>';
                html+='<td align="center" style="padding:3px 8px;" class="ui-widget-content" >'+cliente[indice]+'</td>';                    
                }                
            }
            // html+='<td><div style="float:left"><button onclick="$(\'#PanelTableDatosAdicionalesCliente\').slideToggle(\'slow\');" class="ui-state-default ui-corner-all" id="btnVerDatosAdicionales">Datos Adicionales</button></div></td>';
            html+='<td><div style="float:left"></div><span style="display:none;" class="ui-icon-new" onclick="new_representante_legal(this)">&nbsp&nbsp</span></td>';            
            html+="<td>&nbsp;</td><td colspan='6' id='totalAtencionCliente'></td>";
            html+='</tr>';
        }
        /*representante legal*/
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
        for(i=0;i<obj.datarepresentante.length;i++){
                html_representante+='<tr class="ui-widget-content">';
                html_representante+='<td align="center" class="ui-state-default" style="border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;">'+(cont+1)+'</td>';
                html_representante+='<td align="center" style="pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;">'+obj.datarepresentante[i].contrato+'</td>';
                html_representante+='<td align="center" style="pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;">'+obj.datarepresentante[i].doi+'</td>';
                html_representante+='<td align="center" style="pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;">'+obj.datarepresentante[i].datos+'</td>';
                html_representante+='<td align="center" style="pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;">'+obj.datarepresentante[i].tipo_persona+'</td>';                
                html_representante+='<td align="center" style="pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;"><img class="imgtelf" src="../img/telephone_blue-128.png" style="width:16px;cursor:pointer;" onclick="mostrar_telf_aval(\''+obj.datarepresentante[i].doi+'\');"></td>';
                html_representante+='<td align="center" style="pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;"><img class="imgtelf" src="../img/pin.png" style="width:16px;cursor:pointer;" onclick="mostrar_direct_aval(\''+obj.datarepresentante[i].doi+'\');"></td>';
                html_representante+='<td align="center" style="pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;display:none;"><span class="ui-icon-pencil" onclick="editar_representante_legal(this,'+obj.datarepresentante[i].idrepresentante_legal+')">&nbsp&nbsp</span></td>';
                html_representante+='<td align="center" style="pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;display:none;"><span class="ui-icon-new" onclick="new_representante_legal(this)">&nbsp&nbsp</span></td>';                
                html_representante+='<td align="center" style="pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;display:none;"><span class="ui-icon-clear2" onclick="delete_representante_legal(this,'+obj.datarepresentante[i].idrepresentante_legal+')">&nbsp&nbsp</span></td>';                                
                html_representante+='</tr>';                
                cont++;            
        }       
        $('#table_datos_cliente').html(html);
        $('#table_representante_legal').html(html_representante);
        $('#table_datos_cliente').find('tr:first').find('td:first').addClass('ui-corner-tl');
        $('#table_datos_cliente').find('tr:first').find('td:last').addClass('ui-corner-tr');
        $('#PanelTableDatosAdicionalesCliente').css('display','none');
        $('#btnVerDatosAdicionales').unbind('click');
        $('#btnVerDatosAdicionales').one('click',
            function()
            {
                listar_correos();
                listar_horarios_atencion();
            }
        );
        //$('#lbMessageGlobalGest').text('');
        //$('#lbMessageGlobalGest').css('display','none');
        if( obj.isGestion[0]['COUNT'] == 0 ) {
            $('#lbMessageGlobalGest').text("NO GESTIONAR");
        //$('#lbMessageGlobalGest').slideDown();
        }
            $("#cargando").css("display","none")
    }); 
}
cambiarFuncionXpestaña = function(origenDato)
{
    $('#btnNextClienteAtencionCliente').attr('onclick','');
    $('#btnBackClienteAtencionCliente').attr('onclick','');
    $('#btnNextClienteAtencionCliente').unbind('click');
    $('#btnNextClienteAtencionCliente').bind('click',function(){
        gestion_next_back('next',origenDato);
    });
    $('#btnBackClienteAtencionCliente').unbind('click');
    $('#btnBackClienteAtencionCliente').bind('click',function(){
        gestion_next_back('back',origenDato);
    });
    $('#cbOperadoresMatrizBusqueda').val('0');
}
reloadJQGRID_busqueda_gestionados = function (  ) {
    var cartera = $('#tbCarterasMultiples').jqGrid('getGridParam','selarrrow');//$('#cbAtencionGlobalesCartera').val();
    var servicio = $('#hdCodServicio').val();
    var usuario_servicio = $('#hdCodUsuarioServicio').val();
    if( cartera.length == 0 ) {
        return false;
    }
    $("#table_busqueda_gestionados").jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_busquedaGestionados&Cartera='+cartera+'&Servicio='+servicio+'&Operador='+usuario_servicio
    }); 
}
reloadJQGRID_busqueda_sin_gestion = function (  ) {
    var cartera = $('#tbCarterasMultiples').jqGrid('getGridParam','selarrrow');//$('#cbAtencionGlobalesCartera').val();
    var servicio = $('#hdCodServicio').val();
    var usuario_servicio = $('#hdCodUsuarioServicio').val();
    if( cartera.length == 0 ) {
        return false;
    }
    $("#table_busqueda_sin_gestion").jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_busquedaSinGestion&Cartera='+cartera+'&Servicio='+servicio+'&Operador='+usuario_servicio
    }); 
}
gestion_next_back = function ( xtype , origen) {
    origen = origen || 'grilla';
    var cartera;
    if(origen == 'grilla')
    {
        cartera = $('#tbCarterasMultiples').jqGrid('getGridParam','selarrrow');
    }else{
        cartera = $('#cbCarteraApoyo').val();
    }
    var xitem = parseFloat( $('#txtItemAtencionClienteMain').val() ) ;
    //var cartera = cartera//$('#cbAtencionGlobalesCartera').val();
    var xmonto = $('#cbFiltroMonto').val();
    var xtramo = $('#cbFiltroTramo').val();
    /**********/
    var xtabla = $('#cbGrupoFiltroCampos option:selected').attr('label');
    var xcampo = $('#cbCamposFiltroCampos').val();
    var xdato = $('#cbDataCamposFiltroCampos').val();
    var xreferencia = (($('#cbGrupoFiltroCampos').val()).split('|'))[0];
    var xis_ha = $('#chbFiltroHorarioAtencion').attr('checked');
    var xhora_inicio = $.trim( $('#txtFiltroHorarioInicio').val() );
    var xhora_fin = $.trim( $('#txtFiltroHorarioFin').val() );
    var xdepartamento = $('#cbFiltroDepartamento').val();
    var xotros = $('#cbFiltroOtros').val();
    //var xidfinal = $('#cbFiltroEstado').val();
    var xidfinal = $('#layerContentFiltroEstado :checked').map(function ( ) {
        return $(this).val();
    }).get().join(",");
    var xusuario_matriz = $('#cbOperadoresMatrizBusqueda').val();
    var xmodo = $('#cbAtencionGlobalesModo').val();
    /**********/
    if( cartera == 0 ) {
        return false;
    }
    if( xtype == 'next' ) {
        xitem ++;
    }else{
        xitem--;
    }
    if( xitem == 0 ) {
        return false;
    }
    if( xis_ha ) {
        if( xhora_inicio == ''  ) {
            alert("Seleccione hora inicio");
            return false;
        }
        if( xhora_fin == '' ) {
            alert("Seleccione hora fin");
            return false;
        }
    }
    AtencionClienteDAO.NextBack(cartera, xitem, xmonto, xtramo, xtabla, xcampo, xdato, xreferencia, xis_ha, xhora_inicio, xhora_fin, xotros, xdepartamento, xidfinal, xusuario_matriz, xmodo, function( obj ){
        AtencionClienteDAO.FillAllAtencionCliente( obj );
        if( obj.length > 0 ) {
            $('#txtItemAtencionClienteMain').val(xitem);
        }
    });

    $("#checkheader").attr('checked',false);

}
informacion_gestion = function ( ) {
    var cartera ;
    if($('#hTipoGestion').val()  == 'propias')
    {
        cartera = $('#tbCarterasMultiples').jqGrid('getGridParam','selarrrow');
    }else if($('#hTipoGestion').val()  == 'apoyo')
    {
        cartera = $('#cbCarteraApoyo').val();
    }
    var usuario_servicio = $('#hdCodUsuarioServicio').val();
    AtencionClienteDAO.CantidadClientesAsignados( cartera, usuario_servicio, function ( obj ) {
        if( obj.length>0 ) {
            var html = '';
            html+='<table cellpadding="0" cellspacing="0" border="0">';
            html+='<tr>';
            html+='<td style="padding:0 2px;">Asignados : '+obj[0].cliente_asignados+' </td>';
            html+='<td style="padding:0 2px;">Sin Gestionar : '+obj[0].clientes_sin_gestionar+' </td>';
            html+='<td style="padding:0 2px;">Gestionados : '+obj[0].clientes_gestionados+' </td>';
            html+='</tr>';
            html+='</table>';
            $('#dataInformationGestionMain').html(html);
        }else{
            $('#dataInformationGestionMain').text("Informacion de gestion");
        }
    } );
}
jump_item = function ( ) {
    var xitem = parseFloat( $('#txtItemAtencionClienteMain').val() ) ;
    
    var cartera = $('#tbCarterasMultiples').jqGrid('getGridParam','selarrrow');
    if( cartera == "" ) {
        cartera = $('#cbCarteraApoyo').val();
    }
    
    //var cartera = $('#tbCarterasMultiples').jqGrid('getGridParam','selarrrow');
    //$('#cbAtencionGlobalesCartera').val();
    
    var xmonto = $('#cbFiltroMonto').val();
    var xtramo = $('#cbFiltroTramo').val();
    /**********/
    var xtabla = $('#cbGrupoFiltroCampos option:selected').attr('label');
    var xcampo = $('#cbCamposFiltroCampos').val();
    var xdato = $('#cbDataCamposFiltroCampos').val();
    var xreferencia = (($('#cbGrupoFiltroCampos').val()).split('|'))[0];
    var xis_ha = $('#chbFiltroHorarioAtencion').attr('checked');
    var xhora_inicio = $.trim( $('#txtFiltroHorarioInicio').val() );
    var xhora_fin = $.trim( $('#txtFiltroHorarioFin').val() );
    var xdepartamento = $('#cbFiltroDepartamento').val();
    var xotros = $('#cbFiltroOtros').val();
    //var xidfinal = $('#cbFiltroEstado').val();
    var xidfinal = $('#layerContentFiltroEstado :checked').map(function( ){
        return $(this).val();
    }).get().join(",");
    var xusuario_matriz = $('#cbOperadoresMatrizBusqueda').val();
    var xmodo = $('#cbAtencionGlobalesModo').val();
    /**********/
    if( xitem == 0 ) {
        return false;
    }
    if( cartera == 0 ) {
        return false;
    }
    
    
    AtencionClienteDAO.NextBack(cartera, xitem, xmonto, xtramo,xtabla, xcampo, xdato, xreferencia,  xis_ha, xhora_inicio, xhora_fin, xotros, xdepartamento, xidfinal, xusuario_matriz, xmodo, function( obj ){
        AtencionClienteDAO.FillAllAtencionCliente( obj );
    });
}
guardar_observacion = function ( xobservacion, f_true ) {
    var idcliente = $('#idClienteMain').val();
    if( idcliente == '' ) {
        return false;
    }
    if( $.trim( xobservacion ) == '' ) {
        return false;
    }
    AtencionClienteDAO.save_observacion( idcliente, xobservacion, function ( obj ) {
        if( obj.rst ) {
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
            f_true();
        }else{
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'400px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
    } );
}
cargar_data_campos = function ( xvalue ) {
    var xidcartera = $('#tbCarterasMultiples').jqGrid('getGridParam','selarrrow');//$('#cbAtencionGlobalesCartera').val();
    if($('#hTipoGestion').val() == 'propias')
    {
        xidcartera = $('#tbCarterasMultiples').jqGrid('getGridParam','selarrrow');
    }else if($('#hTipoGestion').val() == 'apoyo')
    {
        xidcartera = $('#cbCarteraApoyo').val();
    }
    var xvalue = xvalue.split("|");
    var xcampo = xvalue[1];
    var xreferencia = xvalue[0];
    DistribucionDAO.ListarCampos( xidcartera, xcampo, xreferencia, function ( obj ) { 
        var html='';
        html+='<option value="0">--Seleccione--</option>';
        for( i=0;i<obj.length;i++ ) {
            html+='<option value="'+obj[i].campoT+'">'+obj[i].campoTMP+'</option>';
        }
        $('#cbCamposFiltroCampos').html(html);
    } );
}
carga_lista_data_campo = function ( ) {
    
    var xcartera;
    if($('#hTipoGestion').val() == 'propias')
    {
        xcartera = $('#tbCarterasMultiples').jqGrid('getGridParam','selarrrow');
    }else if($('#hTipoGestion').val() == 'apoyo')
    {
        xcartera = $('#cbCarteraApoyo').val();
    }
    var xtabla = $('#cbGrupoFiltroCampos option:selected').attr('label');
    var xcampo = $('#cbCamposFiltroCampos').val();

    DistribucionDAO.ListarDataCampo( xcartera, xtabla, xcampo, function ( obj ) {

        var html='';
        html+='<option value="0">--Seleccione--</option>';
        for( i=0;i<obj.data.length;i++ ) {
            var data = eval('obj.data['+i+'].'+xcampo);
            html+='<option value="'+data+'">'+data+'</option>';
        }
        $('#cbDataCamposFiltroCampos').html(html);
    } );
    
}
carga_cantidad_clientes_asignados = function ( ) {
    var xcartera;
    if($('#hTipoGestion').val() == 'propias')
    {
        xcartera = $('#tbCarterasMultiples').jqGrid('getGridParam','selarrrow');
    }else if($('#hTipoGestion').val() == 'apoyo')
    {
        xcartera = $('#cbCarteraApoyo').val();
    }
    var xtabla = $('#cbGrupoFiltroCampos option:selected').attr('label');
    var xreferencia = (($('#cbGrupoFiltroCampos').val()).split('|'))[0];
    var xcampo = $('#cbCamposFiltroCampos').val();
    var xdato = $('#cbDataCamposFiltroCampos').val();
    var xusuario_servicio = $('#hdCodUsuarioServicio').val();

    DistribucionDAO.MostrarCantidadClientesSinGestionarPorUsuario( xcartera, xtabla, xcampo, xdato, xreferencia, xusuario_servicio, function ( obj ) {
            
        $('#txtCantidadClientesAtencionClienteMain').text(obj[0]['COUNT']);
            
    } );
    
}
carga_cantidad_clientes_filtro = function (  ) {    
    var xcartera;
    if($('#hTipoGestion').val() == 'propias')
    {
        xcartera = $('#tbCarterasMultiples').jqGrid('getGridParam','selarrrow');
    }else if($('#hTipoGestion').val() == 'apoyo'){
        xcartera = $('#cbCarteraApoyo').val();
    }
    var xservicio = $('#hdCodServicio').val();
    var xusuario_servicio = $('#hdCodUsuarioServicio').val();
    var xordermonto = $('#cbFiltroMonto').val();
    var xtramo = $('#cbFiltroTramo').val();
    var xdepartamento = $('#cbFiltroDepartamento').val();
    var xprovincia=$('#cbFiltroProvincia').val();    
    var xotros = $('#cbFiltroOtros').val();
    var xidfinal = $('#layerContentFiltroEstado').find(':checked').map(function(  ){ return $(this).val(); }).get().join(",");
    /***********/
    var xtipo_f_estado = $(':radio[name="rdTipoPContentFiltroEstado"]:checked').val();
    /************/
    var xusuario_matriz = $('#cbOperadoresMatrizBusqueda').val();
    var xmodo = $('#cbAtencionGlobalesModo').val();
    var xestado_pago = $('#cbFiltroEstadoPago').val();
    /**********************/
    var xtabla = $('#cbGrupoFiltroCampos option:selected').attr('label');
    var xcampo = $('#cbCamposFiltroCampos').val();
    var xdato = $('#cbDataCamposFiltroCampos').val();
    var xreferencia = (($('#cbGrupoFiltroCampos').val()).split('|'))[0];
    var xsemana_opcion=$("#semana_opcion").val();
    /**********************/
    var xfiltro_con_sin_gestion = $('#hdfiltro_con_sin_gestion').val();

    var xdepto = $("#departamento_filtro").multiselect("getChecked").map(function(){return this.value;}).get();
    var xprovin = $("#provincia_filtro").multiselect("getChecked").map(function(){return this.value;}).get();
    var xdistri = $("#distrito_filtro").multiselect("getChecked").map(function(){return this.value;}).get();

    var xrango_vcto = $('#idrango_cobranzas').val();
    var xtipo_cliente = $('#idtipo_cliente_andina').val();

    if(xcartera.length == 0 || xcartera == 0)
    {

    }else{
        AtencionClienteDAO.CantidadClientesAsignadosFiltros(
            xservicio, xcartera, xusuario_servicio, xordermonto, xtramo, xdepartamento, xprovincia,
            xotros, xidfinal, xusuario_matriz, xmodo, xestado_pago, xtabla, xcampo, xdato, 
            xreferencia, xtipo_f_estado, xsemana_opcion,xfiltro_con_sin_gestion,xdepto,xprovin,xdistri,xrango_vcto,xtipo_cliente,
            function( obj ){
            if( obj.length>0 ) {
                $('#txtCantidadClientesAtencionClienteMain').text(obj[0]['COUNT']);
                $('#tabAC1Resultado').click();
            }
        });
    }
    
}
carga_cantidad_dias_mora=function(){
    var xcartera;
    if($('#hTipoGestion').val() == 'propias')
    {
        xcartera = $('#tbCarterasMultiples').jqGrid('getGridParam','selarrrow');
    }else if($('#hTipoGestion').val() == 'apoyo'){
        xcartera = $('#cbCarteraApoyo').val();
    }
    var xservicio = $('#hdCodServicio').val();
    var xusuario_servicio = $('#hdCodUsuarioServicio').val();
    var xordermonto = $('#cbFiltroMonto').val();
    var xtramo = $('#cbFiltroTramo').val();
    var xdepartamento = $('#cbFiltroDepartamento').val();
    var xprovincia=$('#cbFiltroProvincia').val();    
    var xotros = $('#cbFiltroDiasMora').val();
    var xidfinal = $('#layerContentFiltroEstado').find(':checked').map(function(  ){ return $(this).val(); }).get().join(",");
    /***********/
    var xtipo_f_estado = $(':radio[name="rdTipoPContentFiltroEstado"]:checked').val();
    /************/
    var xusuario_matriz = $('#cbOperadoresMatrizBusqueda').val();
    var xmodo = $('#cbAtencionGlobalesModo').val();
    var xestado_pago = $('#cbFiltroEstadoPago').val();
    /**********************/
    var xtabla = $('#cbGrupoFiltroCampos option:selected').attr('label');
    var xcampo = $('#cbCamposFiltroCampos').val();
    var xdato = $('#cbDataCamposFiltroCampos').val();
    var xreferencia = (($('#cbGrupoFiltroCampos').val()).split('|'))[0];
    /**********************/
    if(xcartera.length == 0 || xcartera == 0)
    {

    }else{
        AtencionClienteDAO.CantidadClientesAsignadosFiltros(
            xservicio, xcartera, xusuario_servicio, xordermonto, xtramo, xdepartamento, xprovincia,
            xotros, xidfinal, xusuario_matriz, xmodo, xestado_pago, xtabla, xcampo, xdato, 
            xreferencia, xtipo_f_estado, 
            function( obj ){
            if( obj.length>0 ) {
                $('#txtCantidadClientesAtencionClienteMain').text(obj[0]['COUNT']);
                $('#tabAC1Resultado').click();
            }
        });
    }

}
cargar_dias_mora=function(){
    var xcartera;
    var xusuario_servicio = $('#hdCodUsuarioServicio').val();
    var xmodo = $('#cbAtencionGlobalesModo').val();
    if($('#hTipoGestion').val() == 'propias')
    {
        xcartera = $('#tbCarterasMultiples').jqGrid('getGridParam','selarrrow');
    }else if($('#hTipoGestion').val() == 'apoyo'){
        xcartera = $('#cbCarteraApoyo').val();
    }    

   if(xcartera.length == 0 || xcartera == 0)
    {

    }else{    
        AtencionClienteDAO.cantidadDiasMora(xcartera,xusuario_servicio,xmodo);
    }
}
carga_cantidad_territorio=function(){
    var xcartera;
    if($('#hTipoGestion').val() == 'propias')
    {
        xcartera = $('#tbCarterasMultiples').jqGrid('getGridParam','selarrrow');
    }else if($('#hTipoGestion').val() == 'apoyo'){
        xcartera = $('#cbCarteraApoyo').val();
    }
    var xservicio = $('#hdCodServicio').val();
    var xusuario_servicio = $('#hdCodUsuarioServicio').val();
    var xordermonto = $('#cbFiltroMonto').val();
    var xtramo = $('#cbFiltroTramo').val();
    var xdepartamento = $('#cbFiltroDepartamento').val();
    var xprovincia=$('#cbFiltroProvincia').val();    
    var xotros = $('#cbFiltroTerritorio').val();
    var xidfinal = $('#layerContentFiltroEstado').find(':checked').map(function(  ){ return $(this).val(); }).get().join(",");
    /***********/
    var xtipo_f_estado = $(':radio[name="rdTipoPContentFiltroEstado"]:checked').val();
    /************/
    var xusuario_matriz = $('#cbOperadoresMatrizBusqueda').val();
    var xmodo = $('#cbAtencionGlobalesModo').val();
    var xestado_pago = $('#cbFiltroEstadoPago').val();
    /**********************/
    var xtabla = $('#cbGrupoFiltroCampos option:selected').attr('label');
    var xcampo = $('#cbCamposFiltroCampos').val();
    var xdato = $('#cbDataCamposFiltroCampos').val();
    var xreferencia = (($('#cbGrupoFiltroCampos').val()).split('|'))[0];
    /**********************/
    if(xcartera.length == 0 || xcartera == 0)
    {

    }else{
        AtencionClienteDAO.CantidadClientesAsignadosFiltros(
            xservicio, xcartera, xusuario_servicio, xordermonto, xtramo, xdepartamento, xprovincia,
            xotros, xidfinal, xusuario_matriz, xmodo, xestado_pago, xtabla, xcampo, xdato, 
            xreferencia, xtipo_f_estado, 
            function( obj ){
            if( obj.length>0 ) {
                $('#txtCantidadClientesAtencionClienteMain').text(obj[0]['COUNT']);
                $('#tabAC1Resultado').click();
            }
        });
    }

}
carga_cantidad_filtro_llamada=function(){
    // Vic I
    var filtro_ini = $.trim($('#txtFechaFiLlaIni').val());
    var filtro_fin = $.trim($('#txtFechaFiLlaFin').val());
    if (filtro_ini=='' || filtro_fin=='')
    {
        alert('Ingresar fechas para el filtro de llamadas');
        return false;
    }
    var filtro = "filtro-llamada_" + filtro_ini + "_" + filtro_fin;

    var xcartera;
    if($('#hTipoGestion').val() == 'propias')
    {
        xcartera = $('#tbCarterasMultiples').jqGrid('getGridParam','selarrrow');
    }else if($('#hTipoGestion').val() == 'apoyo'){
        xcartera = $('#cbCarteraApoyo').val();
    }
    var xservicio = $('#hdCodServicio').val();
    var xusuario_servicio = $('#hdCodUsuarioServicio').val();
    var xordermonto = $('#cbFiltroMonto').val();
    var xtramo = $('#cbFiltroTramo').val();
    var xdepartamento = $('#cbFiltroDepartamento').val();
    var xprovincia=$('#cbFiltroProvincia').val();    
    var xotros = filtro;
    var xidfinal = $('#layerContentFiltroEstado').find(':checked').map(function(  ){ return $(this).val(); }).get().join(",");
    /***********/
    var xtipo_f_estado = $(':radio[name="rdTipoPContentFiltroEstado"]:checked').val();
    /************/
    var xusuario_matriz = $('#cbOperadoresMatrizBusqueda').val();
    var xmodo = $('#cbAtencionGlobalesModo').val();
    var xestado_pago = $('#cbFiltroEstadoPago').val();
    /**********************/
    var xtabla = $('#cbGrupoFiltroCampos option:selected').attr('label');
    var xcampo = $('#cbCamposFiltroCampos').val();
    var xdato = $('#cbDataCamposFiltroCampos').val();
    var xreferencia = (($('#cbGrupoFiltroCampos').val()).split('|'))[0];
    /**********************/
    var xsemana_opcion=$("#semana_opcion").val();
    var xfiltro_con_sin_gestion = $('#hdfiltro_con_sin_gestion').val();
    var xdepto = $("#departamento_filtro").multiselect("getChecked").map(function(){return this.value;}).get();
    var xprovin = $("#provincia_filtro").multiselect("getChecked").map(function(){return this.value;}).get();
    var xdistri = $("#distrito_filtro").multiselect("getChecked").map(function(){return this.value;}).get();

    if(xcartera.length == 0 || xcartera == 0)
    {

    }else{
        // AtencionClienteDAO.CantidadClientesAsignadosFiltros(
        //  xservicio, xcartera, xusuario_servicio, xordermonto, xtramo, xdepartamento, xprovincia,
        //  xotros, xidfinal, xusuario_matriz, xmodo, xestado_pago, xtabla, xcampo, xdato, 
        //  xreferencia, xtipo_f_estado, 
        //  function( obj ){
        //  if( obj.length>0 ) {
        //      $('#txtCantidadClientesAtencionClienteMain').text(obj[0]['COUNT']);
        //      $('#tabAC1Resultado').click();
        //  }
        // });

        AtencionClienteDAO.CantidadClientesAsignadosFiltros(
            xservicio, xcartera, xusuario_servicio, xordermonto, xtramo, xdepartamento, xprovincia,
            xotros, xidfinal, xusuario_matriz, xmodo, xestado_pago, xtabla, xcampo, xdato, 
            xreferencia, xtipo_f_estado, xsemana_opcion,xfiltro_con_sin_gestion,xdepto,xprovin,xdistri,
            function( obj ){
            if( obj.length>0 ) {
                $('#txtCantidadClientesAtencionClienteMain').text(obj[0]['COUNT']);
                $('#tabAC1Resultado').click();
            }
        });

        // AtencionClienteDAO.CantidadClientesAsignadosFiltros(
        //     xservicio, xcartera, xusuario_servicio, xordermonto, xtramo, xdepartamento, xprovincia,
        //     xotros, xidfinal, xusuario_matriz, xmodo, xestado_pago, xtabla, xcampo, xdato, 
        //     xreferencia, xtipo_f_estado, xsemana_opcion,xfiltro_con_sin_gestion,xdepto,xprovin,xdistri,
        //     function( obj ){
        //     if( obj.length>0 ) {
        //         $('#txtCantidadClientesAtencionClienteMain').text(obj[0]['COUNT']);
        //         $('#tabAC1Resultado').click();
        //     }
        // });

    }
}
cargar_territorio=function(){/*jmore200813*/
    var xcartera;
    var xusuario_servicio = $('#hdCodUsuarioServicio').val();
    var xmodo = $('#cbAtencionGlobalesModo').val();
    if($('#hTipoGestion').val() == 'propias')
    {
        xcartera = $('#tbCarterasMultiples').jqGrid('getGridParam','selarrrow');
    }else if($('#hTipoGestion').val() == 'apoyo'){
        xcartera = $('#cbCarteraApoyo').val();
    }    

   if(xcartera.length == 0 || xcartera == 0)
    {

    }else{    
        AtencionClienteDAO.cantidadTerritorio(xcartera,xusuario_servicio,xmodo);
    }
}
listar_provincias=function(){
    var xcartera;
    if($('#hTipoGestion').val() == 'propias')
    {
        xcartera = $('#tbCarterasMultiples').jqGrid('getGridParam','selarrrow');
    }else if($('#hTipoGestion').val() == 'apoyo'){
        xcartera = $('#cbCarteraApoyo').val();
    }    
    DistribucionDAO.ListarProvincias(xcartera,function(obj){
        var html = '';
        html+='<option value="0">--Seleccione--</option>';
        for( i=0;i<obj.length;i++ ) {
            html+='<option value="'+obj[i].provincia+'">'+obj[i].provincia+'</option>';
        }
        $('#cbFiltroProvincia').html(html);        
    });
}
guardar_correo = function ( ) {
    var idcliente = $('#idClienteMain').val();
    var correo = $.trim( $('#txtAtencionClienteCorreo').val() );
    var observacion = $.trim( $('#txtObservacionAtencionClienteCorreo').val() );
    var usuario_creacion = $('#hdCodUsuario').val();
    if( idcliente == '' ) {
        return false;
    }
    if( correo == '' ) {
        return false;
    }
    
    AtencionClienteDAO.save_correo( correo, observacion, usuario_creacion, idcliente , function ( ) {}, function ( obj ) {
        if( obj.rst ) {
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }else{
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'350px'));
            AtencionClienteDAO.setTimeOut_hide_message();
        }
        
    }, function ( ) {
        
    } );
    $('#DialogNuevoCorreo').dialog('close');
}
guardar_horario_atencion = function ( ) {
    var idcliente = $('#idClienteMain').val();
    var horario = $.trim( $('#txtAtencionClienteHorarioAtencion').val() );
    var observacion = $.trim( $('#txtObservacionAtencionClienteHorarioAtencion').val() );
    var usuario_creacion = $('#hdCodUsuario').val();
    if( idcliente == '' ) {
        return false;
    }
    if( horario == '' ) {
        return false;
    }
    
    AtencionClienteDAO.save_horario_atencion( horario, observacion, usuario_creacion, idcliente, function ( ) {}, function ( obj ) {
        if( obj.rst ) {
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
        }else{
            $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'350px'));
        }
    }, function ( ) {} );
    
    $('#DialogNuevoHorarioAtencion').dialog('close');
}
listar_correos = function ( /*idcliente*/ ) {
    var idCliente = $('#idClienteMain').val();
    AtencionClienteDAO.listar_correos_cliente( idCliente, function ( obj ) {
        var html='';
        for( i=0;i<obj.length;i++ ) {
            html+='<tr title = "'+obj[i].observacion+'">';
            html+='<td style="width:30px;" class="ui-widget-header" style="padding:2px 0;">'+(i+1)+'</td>';
            html+='<td style="width:200px;" class="ui-widget-content" style="padding:2px 0;">'+obj[i].correo+'</td>';
            html+='<td style="width:30px;" class="ui-widget-content" style="padding:2px 0;"></td>';
            html+='</tr>';
        }
        $('#table_datos_cliente_correo').html(html);
    } );
    
}
listar_horarios_atencion = function ( /*idcliente*/ ) {
    var idCliente = $('#idClienteMain').val();
    AtencionClienteDAO.listar_horarios_atencion_cliente( idCliente, function ( obj ) {
        var html='';
        for( i=0;i<obj.length;i++ ) {
            html+='<tr title = "'+obj[i].observacion+'">';
            html+='<td style="width:30px;" class="ui-widget-header" style="padding:2px 0;">'+(i+1)+'</td>';
            html+='<td style="width:100px;" class="ui-widget-content" style="padding:2px 0;">'+obj[i].hora+'</td>';
            html+='<td style="width:30px;" class="ui-widget-content" style="padding:2px 0;"></td>';
            html+='</tr>';
        }
        $('#table_datos_cliente_horario_atencion').html(html);
    } );
}
listar_contacto = function ( ) {
    
    AtencionClienteDAO.ListarContacto( $('#hdCodServicio').val(), function ( obj ) {
        var html = '';
        html+='<option value="0">--Seleccione--</option>';
        for( i=0;i<obj.length;i++ ) {
            html+='<option code="'+obj[i].codigo+'" value="'+obj[i].idcontacto+'" >'+obj[i].nombre+'</option>';
        }

        
        $('#cbLlamadaContacto,#cbCampoContacto').html(html);
    } );
    
}
listar_parentesco = function ( ) {

    AtencionClienteDAO.ListarParentesco(
        function ( obj )
        {
            var html = '';
            html+='<option value="0">--Seleccione--</option>';
            for( i=0;i<obj.length;i++ ) {
                html+='<option code="'+obj[i].codigo+'" value="'+obj[i].idparentesco+'" >'+obj[i].nombre+'</option>';
            }

            $('#cbLlamadaParentesco,#cbCampoParentesco').html(html);
        }
    );

}
listar_motivo_no_pago = function ( ) {
    
    AtencionClienteDAO.ListarMotivoNoPago( $('#hdCodServicio').val(), function ( obj ) {
        var html = '';
        AtencionClienteDAO.ArrayMotivoNoPago=obj;//jmore17112014        
        html+='<option value="0">--Seleccione--</option>';
        for( i=0;i<obj.length;i++ ) {
            html+='<option code="'+obj[i].codigo+'" value="'+obj[i].idmotivo_no_pago+'" >'+obj[i].nombre+'</option>';
        }
        $('#cbLlamadaMotivoNoPago,#cbCampoMotivoNoPago').html(html);
    } );
    
}
listar_sustento_pago=function( ) {//jmore18112014
    AtencionClienteDAO.ListarSustentoPago($('#hdCodServicio').val(),function( obj ){
        var html='';
        AtencionClienteDAO.ArraySustentoPago=obj;
        html+='<option value="0">--Seleccione--</option>';
        for(i=0;i<obj.length;i++){
            html+='<option value="'+obj[i].idsustento_pago+'">'+obj[i].nombre+'</option>';
        }
        $('#cbLlamadaSustentoPago').html(html);
    } );
}
listar_alerta_gestion=function( ) {//jmore18112014
    AtencionClienteDAO.ListarAlertaGestion($('#hdCodServicio').val(),function( obj ){
        var html='';
        AtencionClienteDAO.ArrayAlertaGestion=obj;
        html+='<option value="0">--Seleccione--</option>';
        for(i=0;i<obj.length;i++){
            html+='<option value="'+obj[i].idalerta_gestion+'">'+obj[i].nombre+'</option>';
        }
        $('#cbLlamadaAlertaGestion').html(html);
    } );
}
armar_combo_motivo_no_pago=function(){//jmore17112014
    var contador_comercial=0;
    for(j=0;j<AtencionClienteDAO.ArrayCuenta.dataCuenta.length;j++){
      if(AtencionClienteDAO.ArrayCuenta.dataCuenta[j][13]['dato']=='COMERCIAL' && AtencionClienteDAO.ArrayCuenta.dataCuenta[j][0]['dato']=='NO'){
      contador_comercial++;
      }
    }

    var tipo_producto='';
    if(contador_comercial>0){
      tipo_producto='COMERCIAL';
    }else{
      tipo_producto='NATURAL';
    }

    var html='';
    html+='<option value="0">--Seleccione--</option>';
    for(i=0;i<AtencionClienteDAO.ArrayMotivoNoPago.length;i++){
      //if(AtencionClienteDAO.ArrayMotivoNoPago[i]['tipo_producto']==tipo_producto){
        html+='<option value="'+AtencionClienteDAO.ArrayMotivoNoPago[i]['idmotivo_no_pago']+'" code="'+AtencionClienteDAO.ArrayMotivoNoPago[i]['codigo']+'">'+AtencionClienteDAO.ArrayMotivoNoPago[i]['nombre']+'</option>';
      //}
    }
    $('#cbLlamadaMotivoNoPago').html(html);

    html='<option value="0">--Seleccione--</option>';//jmore18112014
    for(i=0;i<AtencionClienteDAO.ArraySustentoPago.length;i++){
      if(AtencionClienteDAO.ArraySustentoPago[i]['tipo_producto']==tipo_producto){
        html+='<option value="'+AtencionClienteDAO.ArraySustentoPago[i]['idsustento_pago']+'">'+AtencionClienteDAO.ArraySustentoPago[i]['nombre']+'</option>';        
      }        
    }    
    $('#cbLlamadaSustentoPago').html(html);

    html='<option value="0">--Seleccione--</option>';
    for(i=0;i<AtencionClienteDAO.ArrayAlertaGestion.length;i++){
      if(AtencionClienteDAO.ArrayAlertaGestion[i]['tipo_producto']==tipo_producto){
        html+='<option value="'+AtencionClienteDAO.ArrayAlertaGestion[i]['idalerta_gestion']+'">'+AtencionClienteDAO.ArrayAlertaGestion[i]['nombre']+'</option>';        
      }        
    }    
    $('#cbLlamadaAlertaGestion').html(html);     
}
listar_direccion_atencion_cliente = function ( idcartera, codigo_cliente ) { 
    
    AtencionClienteDAO.ListarDireccion( idcartera, codigo_cliente, function ( obj ) {
        var html='';
        html+='<tr class="ui-state-default">';
        // CAMBIO 20-06-2016
        html+='<td align="center" style="width:30px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;"><img class="imgtelf" src="../img/pin.png" style="width:16px;cursor:pointer;" onclick="mostrar_direccion_opcion(\''+codigo_cliente+'\')"></td>';
        // CAMBIO 20-06-2016
        html+='<td align="center" style="width:100px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;">Origen</td>';                
        html+='<td align="center" style="width:300px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;">Direccion</td>';
        html+='<td align="center" style="width:150px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;">Departamento</td>';
        html+='<td align="center" style="width:150px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;">Provincia</td>';
        html+='<td align="center" style="width:200px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;font-size:9px;">Distrito</td>';
        html+='</tr>';
        for( i=0;i<obj.length;i++ ) {
            html+='<tr class="ui-widget-content">';
            html+='<td align="center" class="ui-state-default" style="width:30px;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;">'+(i+1)+'</td>';
            html+='<td align="center" style="width:100px;pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;">'+obj[i].Origen+'</td>';                        
            html+='<td align="center" style="width:300px;pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;">'+obj[i].direccion+'</td>';
            html+='<td align="center" style="width:150px;pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;">'+obj[i].departamento+'</td>';
            html+='<td align="center" style="width:150px;pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;">'+obj[i].provincia+'</td>';
            html+='<td align="center" style="width:200px;pre-line;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;font-size:10px;">'+obj[i].distrito+'</td>';
            html+='</tr>';
        }
        $('#table_direccion_vista_rapida').html(html);
            
    }, function ( ) {} );
    
}/*jmore050712*/
mostrar_refinanciamiento = function ( idcuenta, numero_cuenta, moneda, total_deuda ) {
    
    $('#txtDeudaRefinanc').val(total_deuda);
    $('#contentTbRefinanciamiento').attr('idcuenta',idcuenta);
    $('#contentTbRefinanciamiento').attr('numero_cuenta',numero_cuenta);
    $('#contentTbRefinanciamiento').attr('moneda',moneda);
    $('#contentTbRefinanciamiento').fadeIn();

}
grabar_refinanciamiento = function ( ) {

    var numero_cuenta = $('#contentTbRefinanciamiento').attr('numero_cuenta');
    var moneda = $('#contentTbRefinanciamiento').attr('moneda');
    var idcuenta = $('#contentTbRefinanciamiento').attr('idcuenta');
    var deuda = $('#txtDeudaRefinanc').val();
    var descuento = $('#txtDescuentoRefinanc').val();
    var n_cuotas = $('#txtNCuotasRefinanc').val();
    var tipo_monto = $('#cbTipoMontoRefinanc').val();
    var monto_pago = $('#txtMontoCuotaRefinanc').val();
    var observacion = $('#txtObservacionRefinanc').val();

    if( deuda == '' ){
        _displayBeforeSendDl( "Ingrese deuda", 400 );
        return false;
    }
    if( n_cuotas == '' ) {
        _displayBeforeSendDl( "Ingrese n cuotas", 400 );
        return false;
    }
    if( monto_pago == '' ) {
        _displayBeforeSendDl( "Ingrese monto de pago", 400 );
        return false;
    }

    
    AtencionClienteDAO.Refinanciamiento.Grabar( 
        idcuenta, numero_cuenta, moneda, deuda, descuento, n_cuotas, tipo_monto, monto_pago, observacion,
        function ( obj ) {
            
            if( obj ) {
                
            }else{
                
            }

            _displayBeforeSendDl( obj.msg, 400 );

        }
     );

}
/*jmore050712*/
listar_historico_cuenta = function ( /*xidcliente, xcartera */) {
    var idCartera = $('#IdCartera').val();
    var idCliente = $('#idClienteMain').val();
    AtencionClienteDAO.ListarHistoricoCuenta( idCliente, idCartera, function ( obj ) {
        var html = '';
        for( i=0;i<obj.length;i++ ) {
            if( i==0 ) {
                html+='<tr>';
                var field = eval(obj[i]);
                html+='<td class="ui-state-default ui-corner-tl" style="padding:3px;width:30px;">&nbsp;</td>';
                for( index in field ) {
                    html+='<td align="center" class="ui-state-default" style="padding:3px;">'+index+'</td>';
                }
                html+='</tr>';
            }
            html+='<tr>';
            var data = eval(obj[i]);
            var count = 0;
            html+='<td class="ui-state-default" align="center" style="padding:3px;width:30px;" >'+(i+1)+'</td>';
            for( index in data ) {
                count++;
                html+='<td align="center" class="ui-widget-content" style="padding:3px;" >'+data[index]+'</td>';
            }
            html+='</tr>';
            if( i == ( obj.length-1 ) ) {
                html+='<tr><td style="height:20px;" class="ui-state-default ui-corner-bottom" colspan="'+(count+1)+'"></t></tr>';
            }
        }
        $('#table_historico_cuenta').html(html);
            
    } );
    
}
listar_carga_final = function ( ) {
    AtencionClienteDAO.LoadCargaFinal( $('#hdCodServicio').val(), function ( obj ) {
        var html = '';
        html+='<option value="0">--Seleccione--</option>';
        for( i=0;i<obj.length;i++ ) {
            html+='<option value="'+obj[i].idcarga_final+'">'+obj[i].nombre+'</option>';
        }
        $('#cbLlamadaEstadoDetalleContactabilidad').html(html);
    } );
}
listar_tipo_final = function ( )  {
    AtencionClienteDAO.LoadTipoFinal( $('#hdCodServicio').val(), $('#cbLlamadaEstadoDetalleContactabilidad').val(),  function ( obj ) {
        var html = '';
        html+='<option value="0">--Seleccione--</option>';
        for( i=0;i<obj.length;i++ ) {
            html+='<option value="'+obj[i].idtipo_final+'">'+obj[i].nombre+'</option>';
        }
        $('#cbLlamadaEstadoDetalleTipo').html(html);
    } );
}
listar_nivel_final = function ( )  {
    AtencionClienteDAO.LoadNivel( $('#hdCodServicio').val(), $('#cbLlamadaEstadoDetalleContactabilidad').val(), $('#cbLlamadaEstadoDetalleTipo').val() ,  function ( obj ) {
        var html = '';
        html+='<option value="0">--Seleccione--</option>';
        for( i=0;i<obj.length;i++ ) {
            html+='<option value="'+obj[i].idnivel+'">'+obj[i].nombre+'</option>';
        }
        $('#cbLlamadaEstadoDetalleResptGestion').html(html);
    } );
}
listar_detalle_estado = function ( ) {
    AtencionClienteDAO.LoadFinalServicioDetalle( $('#hdCodServicio').val(), $('#cbLlamadaEstadoDetalleContactabilidad').val(), $('#cbLlamadaEstadoDetalleTipo').val(), $('#cbLlamadaEstadoDetalleResptGestion').val() ,  function ( obj ) {
        var html = '';
        html+='<option value="0">--Seleccione--</option>';
        for( i=0;i<obj.length;i++ ) {
            html+='<option value="'+obj[i].idfinal+'">'+obj[i].nombre+'</option>';
        }
        $('#cbLlamadaEstadoDetaleResptIncidencia').html(html);
    } );
}
guardar_factura_digital = function()
{
    var solicito = $('#txtFacturaDigitalPersonaSolicita').val();
    var correo = $('#txtFacturaDigitalCorreo').val();
    var supervisor = $('#cboFacturaDigitalSupervisor').val();
    var fechaVencimiento = $().val();
    var idClienteCartera = $('#IdClienteCartera').val();
    var idCuenta = $('#cboLinea').val();
    if(idClienteCartera == '')
    {
        alert('No hay un cliente en gestion');
    }else if(solicito == '' || correo == '' || supervisor == '0' || fechaVencimiento == '' || idCuenta == '' ){
        alert('Todos los campos son obligatorios');
    }else{
        $('#fileFacturaDigital').upload(
            '../controller/ControllerCobrast.php',
            {
                solicito : solicito,
                correo : correo,
                idUsuarioServicio : supervisor,
                observacion : $('#txtFacturaDigitalObservacion').val(),
                action : 'uploadFactura',
                command : 'atencion_cliente',
                idClienteCartera : idClienteCartera,
                idCuenta : idCuenta,
                fechaVencimiento : $('#txtFechaVencimiento').val()
            },
            function(rpt)
            {
                alert(rpt.msg);
            },
            'json'
            );
    }
}
checked_all = function ( element,idtb ) {
    if( element ) {
        $('#'+idtb).find(':checkbox').attr('checked',true);
    }else{
        $('#'+idtb).find(':checkbox').attr('checked',false);
    }
}

sendEmailFacturasDigitales = function()
{
    var arrIdFacturas = $('#table_facturasDigitales').jqGrid('getGridParam','selarrrow');
    if(arrIdFacturas.length > 0)
    {
        var param = [];
        $.each(arrIdFacturas,
            function(index,id)
            {
                
                param[index] = $('#table_facturasDigitales').jqGrid('getRowData',id);;
            }
            );
        param = $.toJSON(param);
        $.ajax(
        {
            url : '../controller/ControllerCobrast.php',
            data : 'clientes='+param+'&command=atencion_cliente&action=sendCorreoFacturaDigital',
            type : 'post',
            dataType : 'json',
            beforeSend : function()
            {
                $('#closeWindowCobrastOverlay,#closeWindowCobrastProgressBar').css('display','block');
            },
            success : function(response)
            {
                $('#closeWindowCobrastOverlay,#closeWindowCobrastProgressBar').css('display','none');
                alert(response.msg);
                $('#table_facturasDigitales').trigger('reloadGrid');
            }
        }
        );
    }else{
        /*$('body').after('<div id="divMessage">Seleccione por lo menos un registro</div>');
        $('#divMessage').dialog(
            {
                modal : true,
                title : 'Error Seleccion'
            }
        );*/
        alert('Seleccione por lo menos un registro');
    }
    
}


/*listar_estado_pago = function ( idcartera ) {
    
    AtencionClienteDAO.ListarEstadoPago( idcartera, function ( obj ) {
            var html = '';
                html+='<option value="0">--Seleccione--</option>';
            for( i=0;i<obj.length;i++ ) {
                html+='<option value="'+obj[i].estado_pago+'">'+obj[i].estado_pago+'</option>'; 
            }
            $('#cbFiltroEstadoPago').html(html);
        } );
        
}*/

ranking_total_usuario_por_dia = function ( ) {
    
    var por = $('#cbPorRankingTotalUsuarioPorDia').val();
    var idusuario_servicio = $('#hdCodUsuarioServicio').val();
    var idservicio = $('#hdCodServicio').val();
    var fecha_inicio = $('#txtFechaInicioRankingTotalUsuarioPorDia').val();
    var fecha_fin = $('#txtFechaFinRankingTotalUsuarioPorDia').val();
    
    AtencionClienteDAO.RankingTotalUsuarioPorDia( por, idusuario_servicio, idservicio, fecha_inicio, fecha_fin, function ( obj ) {
        var html = '';
        for( i=0;i<obj.RankingUsuario.length;i++ ) {
            var data = eval( obj.RankingUsuario[i] );
            if( i == 0 ) {
                html+='<tr class="ui-state-default">';
                for( index in data ) {
                    html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;">'+index+'</td>';
                }
                html+='</tr>';
            }
            if( i == ( obj.RankingUsuario.length - 1 ) ) {
                html+='<tr class="ui-state-default">';
                for( index in data ) {
                    if( index == 'CONTACTABILIDAD' ) {
                        html+='<td align="center" style="width:250px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">TOTALES</td>';
                    }else{
                        html+='<td align="center" style="width:250px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
                    }
                }
                html+='</tr>';
            }else{
                html+='<tr class="ui-widget-content">';
                for( index in data ) {
                    html+='<td align="center" style="width:250px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
                }
                html+='</tr>';
            }
        }
        $('#TableRankingTotalUsuarioPorDia').html(html);
        $('#TableRankingTotalUsuarioPorDia tr:gt(0)').hover(function(){
            $(this).addClass('ui-state-hover');
        },function(){
            $(this).removeClass('ui-state-hover');
        });
        $('#TableRankingTotalUsuarioPorDia tr:gt(0)').click(function(){
            $(this).addClass('ui-state-highlight').siblings().removeClass('ui-state-highlight');
        });
    }, function ( ) {
        $('#TableRankingTotalUsuarioPorDia').html(templates.IMGloadingContentTable());
    } );
        
}
ranking_total_servicio_por_dia = function ( ) {
    
    var por = $('#cbPorInicioRankingTotalServicioPorDia').val();
    var idservicio = $('#hdCodServicio').val();
    var fecha_inicio = $('#txtFechaInicioRankingTotalServicioPorDia').val();
    var fecha_fin = $('#txtFechaFinRankingTotalServicioPorDia').val();
    
    AtencionClienteDAO.RankingTotalServicioPorDia( por, idservicio, fecha_inicio, fecha_fin, function ( obj ) {
        var html = '';
        for( i=0;i<obj.Ranking.length;i++ ) {
            var data = eval( obj.Ranking[i] );
            if( i == 0 ) {
                html+='<tr class="ui-state-default" >';
                for( index in data ) {
                    html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;">'+index+'</td>';
                }
                html+='</tr>';
            }
            html+='<tr class="ui-widget-content">';
            for( index in data ) {
                html+='<td align="center" style="width:250px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
            }
            html+='</tr>';
        }
        $('#TableRankingTotalServicioPorDia').html(html);
        $('#TableRankingTotalServicioPorDia tr:gt(0)').hover(function(){
            $(this).addClass('ui-state-hover');
        },function(){
            $(this).removeClass('ui-state-hover');
        });
        $('#TableRankingTotalServicioPorDia tr:gt(0)').click(function(){
            $(this).addClass('ui-state-highlight').siblings().removeClass('ui-state-highlight');
        });
            
    }, function ( ) {
        $('#TableRankingTotalServicioPorDia').html(templates.IMGloadingContentTable());
    } );
    
}
load_alertas_recientes_hoy = function ( ) {
    
    var idservicio = $('#hdCodServicio').val();
    var idusuario_servicio = $('#hdCodUsuarioServicio').val();
    
    AtencionClienteDAO.loadAlertasRecientes( idservicio, idusuario_servicio, function ( obj ) {
        AlertasUsuario=eval(obj.init);
    }, function ( ) {} );
    
}
load_meta_cliente_cuenta_usuario_servicio = function ( ) {
    
    var idservicio = $('#hdCodServicio').val();
    var idusuario_servicio = $('#hdCodUsuarioServicio').val();
    var idcartera = ($('#tbCarterasMultiples').jqGrid('getGridParam','selarrrow')).join(",");
    
    if( idcartera == '' ) {
        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo("Seleccione carteras",'350px'));
        AtencionClienteDAO.setTimeOut_hide_message();
        return false;   
    }
    
    AtencionClienteDAO.MetaClienteCuentaUsuarioServicio( idservicio, idcartera , idusuario_servicio, function ( obj ) {
        var html = '';
        for( i=0;i<obj.MetaIni.length;i++  ) {
            var data = eval( obj.MetaIni[i] );
            if( i == 0 ) {
                html+='<tr class="ui-state-default" >';
                for( index in data ) {
                    html+='<td align="center" style="white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;">'+index+'</td>';
                }
                html+='</tr>';
            }
            html+='<tr class="ui-widget-content">';
            for( index in data ) {
                html+='<td align="center" style="width:250px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;">'+data[index]+'</td>';
            }
            html+='</tr>';
        }
        $('#TableMetaClienteCuentaUsuarioServicio').html(html);
        $('#TableMetaClienteCuentaUsuarioServicio tr:gt(0)').hover(function(){
            $(this).addClass('ui-state-hover');
        },function(){
            $(this).removeClass('ui-state-hover');
        });
        $('#TableMetaClienteCuentaUsuarioServicio tr:gt(0)').click(function(){
            $(this).addClass('ui-state-highlight').siblings().removeClass('ui-state-highlight');
        });
    }, function ( ) {
        $('#TableMetaClienteCuentaUsuarioServicio').html(templates.IMGloadingContentTable());
    } );
}
listar_ac_cluster = function ( idc ) {
    
    AtencionClienteDAO.ListarCarteraCluster( 
        function ( obj )
        {
            var html='';
            html+='<option value="0">--Seleccione--</option>';
            for( i=0;i<obj.length;i++ ) {
                html+='<option value="'+obj[i].cluster+'">'+obj[i].cluster+'</option>';
            }
            $('#'+idc).html(html);
        },
        function ( ) 
        {}
    );
    
    
}
listar_ac_evento = function ( idc ) {
    
    AtencionClienteDAO.ListarCarteraEvento( 
        function ( obj )
        {
            var html='';
            html+='<option value="0">--Seleccione--</option>';
            for( i=0;i<obj.length;i++ ) {
                html+='<option value="'+obj[i].evento+'">'+obj[i].evento+'</option>';
            }
            $('#'+idc).html(html);
        },
        function ( ) 
        {}
    );
    
    
}
listar_ac_segmento = function ( idc ) {
    
    AtencionClienteDAO.ListarCarteraSegmento( 
        function ( obj )
        {
            var html='';
            html+='<option value="0">--Seleccione--</option>';
            for( i=0;i<obj.length;i++ ) {
                html+='<option value="'+obj[i].segmento+'">'+obj[i].segmento+'</option>';
            }
            $('#'+idc).html(html);
        },
        function ( ) 
        {}
    );
    
    
}
Monto_total_refinanciamiento = function ( ) {
    
    var numero_cuotas = parseFloat( ( $('#txtNCuotaCuotificacion').val() == '' ) ? 0 : $('#txtNCuotaCuotificacion').val() ) ;
    var monto_cuota = parseFloat( ( $('#txtMontoCuotaCuotificacion').val() == '' ) ? 0 : $('#txtMontoCuotaCuotificacion').val() ) ;
    
    $('#lbMontoTotalCuotificacion').text( numero_cuotas * monto_cuota );
    
}
search_text_table = function ( xtext, idtable ) {
    var text = $.trim( xtext );
    text = text.toUpperCase();
    $('#'+idtable+' tr').css('display','none');
    $('#'+idtable+' tr').find('td:contains("'+text+'")').parent().css('display','block');
    
}
search_operadores_en_tabla = function ( xtext, xidtable ) {
    var text = xtext;
    text = text.toUpperCase();
    $('#'+xidtable).find('tr').css('display','none');
    $('#'+xidtable+' tr').find('td:contains("'+text+'")').parent().css('display','block');
}
listar_refinanciamientos = function ( ) {
    
    var fecha_inicio = $('#').val();
    var fecha_fin = $('#').val();
    
    AtencionClienteDAO.ListarCuotificacion( 
        fecha_inicio, fecha_fin, 
        function ( obj ) 
        {
            var html = '';
            
            for( i=0;i<obj.length;i++ ) {
                html+='<tr>';
                    html+='<td align="center">'+(i+1)+'</td>';
                    html+='<td align="center">'+obj[i].numero_cuenta+'</td>';
                    html+='<td align="center">'+obj[i].fecha+'</td>';
                    html+='<td align="center">'+obj[i].total_deuda+'</td>';
                    html+='<td align="center">'+obj[i].tipo_cuota+'</td>';
                    html+='<td align="center">'+obj[i].numero_cuota+'</td>';
                    html+='<td align="center">'+obj[i].monto_cuota+'</td>';
                    html+='<td align="center">'+obj[i].observacion+'</td>';
                    html+='<td align="center"></td>';
                html+='</tr>';
            }
            
        } 
    );
    
}
calcular_monto_refinanciar = function ( ) {
    
    var deuda = parseFloat( ( $.trim($('#txtGBDeudaCuotificacion').val())=='' )?0:$('#txtGBDeudaCuotificacion').val() );
    var descuento = parseFloat( ( $.trim($('#txtDescuentoCuotificacion').val())=='' )?0:$('#txtDescuentoCuotificacion').val() );
    var monto_descuento = Math.round( (deuda*descuento)/100 );
    var subtotal_deuda = deuda - monto_descuento ;
    var interes = parseFloat( ( $.trim($('#txtInteresCuotificacion').val())=='' )?0:$('#txtInteresCuotificacion').val() );
    var monto_interes =Math.round( (subtotal_deuda*interes)/100 );
    var comision = parseFloat( ( $.trim($('#txtComisionCuotificacion').val())=='' )?0:$('#txtComisionCuotificacion').val() );
    var monto_comision = Math.round(( subtotal_deuda*comision )/100);
    var mora = parseFloat( ( $.trim($('#txtMoraCuotificacion').val())=='' )?0:$('#txtMoraCuotificacion').val() );
    var monto_mora = Math.round( (subtotal_deuda*mora) /100 );
    var gastos_cobranza = parseFloat( ( $.trim($('#txtGastosCobranzaCuotificacion').val())=='' )?0:$('#txtGastosCobranzaCuotificacion').val() );
    var total_refinanciar = subtotal_deuda + monto_interes + monto_comision + gastos_cobranza ;
    
    var numero_cuotas = parseFloat( ( $.trim($('#txtNroCuotasCuotificacion').val())=='' )?1:$('#txtNroCuotasCuotificacion').val() );
    var monto_cuota = Math.round( total_refinanciar / numero_cuotas );
    
    $('#txtMontoDescuentoCuotificacion').val( monto_descuento );
    $('#txtSubTotalDeudaCuotificacion').val( subtotal_deuda );
    $('#txtMontoInteresCuotificacion').val( monto_interes );
    $('#txtMontoComisionCuotificacion').val( monto_comision );
    $('#txtMontoMoraCuotificacion').val( monto_mora );
    $('#txtTotalRefinanciarCuotificacion').val( total_refinanciar );
    
    $('#txtMontoCuotaCuotificacion').val( monto_cuota );
    
    $('#tableVistaPreviaCuotificacion').empty();
    
        
}
vista_previa_refinanciamiento = function ( ) {
    
    var total_refinanciar = parseFloat( ( $('#txtTotalRefinanciarCuotificacion').val()=='' )?0:$('#txtTotalRefinanciarCuotificacion').val() );  
    var tipo_pago = $('#txtTipoPagoCuotificacion').val();
    var numero_cuotas = parseFloat( ( $.trim($('#txtNroCuotasCuotificacion').val())=='' )?0:$('#txtNroCuotasCuotificacion').val() );
    var monto_cuota = parseFloat( $('#txtMontoCuotaCuotificacion').val() );
    var monto_mora = parseFloat( $('#txtMontoMoraCuotificacion').val() );
    var primer_fecha_pago = $('#txtFechaPrimerPagoCuotificacion').val().split("-");
    var anio = parseFloat( primer_fecha_pago[0] );
    var mes = (parseFloat( primer_fecha_pago[1] )-1);
    var dia = parseFloat( primer_fecha_pago[2] );
    
    var html = '';
    var total_pago = 0;
    var total_mora = 0;
    var total_deuda = 0;
    
    html+='<tr>';
        html+='<td align="center" class="ui-state-default ui-corner-tl" style="width:20px;padding:3px 0;">&nbsp;</td>';
        html+='<td align="center" class="ui-state-default" style="width:80px;padding:3px 0;">Fecha Vcta.</td>';
        html+='<td align="center" class="ui-state-default" style="width:80px;padding:3px 0;">Deuda</td>';
        html+='<td align="center" class="ui-state-default" style="width:80px;padding:3px 0;">Monto Cuota</td>';
        html+='<td align="center" class="ui-state-default" style="width:80px;padding:3px 0;">Total Pago</td>';
        html+='<td align="center" class="ui-state-default" style="width:80px;padding:3px 0;">Saldo Deuda</td>';
        html+='<td align="center" class="ui-state-default ui-corner-tr" style="width:80px;padding:3px 0;">Saldo Mora</td>';
    html+='</tr>';
    for( i=0;i<numero_cuotas;i++ ) {
        var fecha = "";
        if( i>0 ) {
            if( tipo_pago == 'SEMANAL' ) {
                dia += 7;
            }else if( tipo_pago == 'QUINCENAL' ) {
                dia += 15;
            }else if( tipo_pago == 'MENSUAL' ) {
                mes += 1;
            }else if( tipo_pago == 'BIMESTRAL' ) {
                mes += 2;
            }else if( tipo_pago == 'TRIMESTRAL' ) {
                mes += 3;
            }
            var obj_fecha = new Date(anio,mes,dia);
            var dia_cl = obj_fecha.getDate().toString();
            var mes_cl = (obj_fecha.getMonth()+1).toString();
            fecha = obj_fecha.getFullYear()+'-'+( (mes_cl.length==1)?('0'+mes_cl):mes_cl )+'-'+( (dia_cl.length==1)?('0'+dia_cl):dia_cl );
            
            total_mora = total_mora + monto_mora;
            total_deuda = total_deuda - monto_cuota;
        }else{
            fecha = $('#txtFechaPrimerPagoCuotificacion').val();
            total_deuda = total_refinanciar;
        }
        
        total_pago = total_pago + monto_cuota;
        
        html += '<tr>'; 
            html += '<td align="center" class="ui-state-default" style="width:20px;padding:2px 0;">'+(i+1)+'</td>';
            html += '<td align="center" class="ui-widget-content" style="width:80px;padding:2px 0;">'+fecha+'</td>';
            html += '<td align="center" class="ui-widget-content" style="width:80px;padding:2px 0;">'+(Math.round(total_deuda*100)/100)+'</td>';
            html += '<td align="center" class="ui-widget-content" style="width:80px;padding:2px 0;">'+monto_cuota+'</td>';
            html += '<td align="center" class="ui-widget-content" style="width:80px;padding:2px 0;">'+total_pago+'</td>';
            html += '<td align="center" class="ui-widget-content" style="width:80px;padding:2px 0;">'+(Math.round((total_refinanciar - total_pago)*100)/100)+'</td>';
            html += '<td align="center" class="ui-widget-content" style="width:80px;padding:2px 0;">'+(total_refinanciar+total_mora)+'</td>';
        html += '</tr>';    
    }
    $('#tableVistaPreviaCuotificacion').html(html);
}
grabar_ref = function ( ) {
    
    var deuda = $('#txtGBDeudaCuotificacion').val();
    var descuento = $('#txtDescuentoCuotificacion').val();
    var interes = $('#txtInteresCuotificacion').val();
    var comision = $('#txtComisionCuotificacion').val();
    var mora = $('#txtMoraCuotificacion').val();
    var gastos_cobranza = $('#txtGastosCobranzaCuotificacion').val();
    
    var n_cuotas = $('#txtNroCuotasCuotificacion').val();
    var tipo_pago = $('#txtTipoPagoCuotificacion').val();
    var fech_pri_pag = $('#txtFechaPrimerPagoCuotificacion').val();
    var obs = $.trim( $('#txtObservacionCuotificacion').val() );
    
    if( n_cuotas == '' ) {
        alert("Ingrese numero de cuotas");
        return false;
    }
    if( fech_pri_pag == '' ) {
        alert("Ingrese primera fecha de pago");
        return false;
    }
    
    var rs = confirm("Verifique si los datos ingresados son los correctos");
    if( rs ) {
        
        AtencionClienteDAO.Ref.Grabar(
            deuda, descuento, interes, comision, mora, gastos_cobranza, n_cuotas, tipo_pago, fech_pri_pag, obs,
            function ( ) {},
            function ( obj ) {
                if( obj.rst ) {
                    
                }else{
                    
                }
                _displayBeforeSendDl(obj.msg,400);
            },
            function ( ) {}
        );
        
    }
    
}
grabar_pago_ref = function ( ) {
    
    var monto = $('#txtMontoPagoCuotificacion').val();
    var moneda = $('#cbMonedaPagoCuotificacion').val();
    var obs = $('#txtObsPagoCuotificacion').val();
    
    var rs = confirm("Verifique si loos datos ingresados son los correctos");
    
    if( rs ) {
        
        AtencionClienteDAO.Ref.Pago.Grabar( 
            monto, moneda, obs,
            function ( ) {},
            function ( obj ) {
                if( obj.rst ) {
                    
                    var obj_fecha = new Date();
                    var dia_cl = obj_fecha.getDate().toString();
                    var mes_cl = (obj_fecha.getMonth()+1).toString();
                    fecha = obj_fecha.getFullYear()+'-'+( (mes_cl.length==1)?('0'+mes_cl):mes_cl )+'-'+( (dia_cl.length==1)?('0'+dia_cl):dia_cl );
                    
                    var html = '';
                    html+='<tr>';
                        html+='<td align="center" style="width:20px;padding:2px 0;" class="ui-state-default">1</td>';
                        html+='<td align="center" style="width:80px;padding:2px 0;" class="ui-widget-content">'+fecha+'</td>';
                        html+='<td align="center" style="width:100px;padding:2px 0;" class="ui-widget-content">'+monto+'</td>';
                        html+='<td align="center" style="width:80px;padding:2px 0;" class="ui-widget-content">'+moneda+'</td>';
                        html+='<td align="center" style="width:200px;padding:2px 0;" class="ui-widget-content">'+obs+'</td>';
                    html+='</tr>';
                    if( $('#table_list_ref_cuenta_pago > tbody').find('tr').length == 0 ) {
                        $('#table_list_ref_cuenta_pago > tbody').html(html);
                    }else{
                        $(html).insertBefore('#table_list_ref_cuenta_pago > tbody > tr:eq(0)');
                    }
                    
                }else{
                    
                }
                _displayBeforeSendDl(obj.msg,400);
            },
            function ( ) {}
        );
        
    }
    
}
listar_pago_ref = function ( ) {
    
    AtencionClienteDAO.Ref.Pago.Listar(
        function ( ) {},
        function ( obj ) {
            var html = '';
            for( i=0;i<obj.length;i++ ) {
                html+='<tr>';
                    html+='<td align="center" style="width:20px;padding:2px 0;" class="ui-state-default">'+(i+1)+'</td>';
                    html+='<td align="center" style="width:80px;padding:2px 0;" class="ui-widget-content">'+obj[i].fecha+'</td>';
                    html+='<td align="center" style="width:100px;padding:2px 0;" class="ui-widget-content">'+obj[i].monto_pagado+'</td>';
                    html+='<td align="center" style="width:80px;padding:2px 0;" class="ui-widget-content">'+obj[i].moneda_pago+'</td>';
                    html+='<td align="center" style="width:200px;padding:2px 0;" class="ui-widget-content">'+obj[i].observacion+'</td>';
                html+='</tr>';
            }
            $('#table_list_ref_cuenta_pago').html(html);
            
        },
        function ( ) {}
    );
    
}
editar_representante_legal = function ( element,xid ) {
    var xoffset = $(element).offset();
    $('#DialogEditarRepresentanteLegal').css('top',(parseInt(xoffset.top) + 40 )).css('left',( parseInt(xoffset.left) - 50 ));
    $('#DialogEditarRepresentanteLegal').slideDown('slow');

    /*datos*/
    var asesor_comercial='';
    var representante_legal='';
    var responsable_pago='';
    var observacion='';

    $('#txtidrepresentante_legal').val(xid);
    asesor_comercial=$(element).parent().prev().prev().prev().prev().text();
    representante_legal=$(element).parent().prev().prev().prev().text();
    responsable_pago=$(element).parent().prev().prev().text();
    observacion=$(element).parent().prev().text();

    $('#txtasesorcomercial_representante').val(asesor_comercial);
    $('#txtrepresentantelegal_representante').val(representante_legal);
    $('#txtresponsablepago_representante').val(responsable_pago);
    $('#txtobservacion_representante').val(observacion);
}
new_representante_legal=function(element){
    var xoffset = $(element).offset();
    $('#DialogNuevoRepresentanteLegal').css('top',(parseInt(xoffset.top) + 40 )).css('left',( parseInt(xoffset.left) - 100 ));
    $('#DialogNuevoRepresentanteLegal').slideDown('slow');
}
actualizar_representante_legal=function(){
    AtencionClienteDAO.actualizarRepresentanteLegal();
}
nuevo_representante_legal=function(){
    AtencionClienteDAO.nuevoRepresentanteLegal();
}
delete_representante_legal=function(element,xid){
    var rsc=confirm("Desea Eliminar?");
    if(rsc){
        AtencionClienteDAO.deleteRepresentanteLegal(xid);        
    }

}
listar_situacion_laboral= function (){
    AtencionClienteDAO.listarSituacionLaboral( $('#hdCodServicio').val() ,function(obj){

      var html='<option value="0">--Seleccione--</option>';
      for(i=0;i<obj.length;i++){
       html+='<option value="'+obj[i].idsituacion_laboral+'">'+obj[i].nombre+'</option>';
      }
      $('#cbSituacionLaboral').html(html);

    });
}

listar_disposicion_refinanciamiento= function (){
    AtencionClienteDAO.listarDisposicionRefinanciamiento( $('#hdCodServicio').val() ,function(obj){

      var html='<option value="0">--Seleccione--</option>';
      for(i=0;i<obj.length;i++){
       html+='<option value="'+obj[i].iddisposicion_refinanciar+'">'+obj[i].nombre+'</option>';
      }
      $('#cbDisposicionRefinanciar').html(html);

    });
}

listar_estado_cliente= function (){
    AtencionClienteDAO.listarEstadoCliente( $('#hdCodServicio').val() ,function(obj){

      var html='<option value="0">--Seleccione--</option>';
      for(i=0;i<obj.length;i++){
       html+='<option value="'+obj[i].idestado_cliente+'">'+obj[i].nombre+'</option>';
      }
      $('#cbEstadoDelCliente').html(html);
      $('#cbCampoEstadoCliente').html(html);

    });
}

//PIRO
enviar_Informe_Visita_Gestion = function() {
    var rpt = confirm('¿Procesar?');
    if (rpt == true) {
        var idGiroNegocio = "";
        var detalleGiroExtraNegocio = "";
        var idAfrontarPago = "" ;
        var detalleAfronPago = "";
        var menorigual10personas = "0";
        var mayor10personas = "0";
        var numeroVisita=0;
        var tipoVisita=0;
        
        if($('input:radio[name=chkGiroNegocio]').is(':checked')){

            idGiroNegocio = $('input:radio[name=chkGiroNegocio]:checked').val();

        }else{
            idGiroNegocio = "7";
            detalleGiroExtraNegocio = $('#txtOtrosGiroNegocio').val();
        }

        if($('input:radio[name=chkComoAfrontaraPAgo]').is(':checked')){
            idAfrontarPago =  $('input:radio[name=chkComoAfrontaraPAgo]:checked').val();
        }else {
            idAfrontarPago = '6';
            detalleAfronPago = $('#txtEspecificarOtros4').val();
        }
        
        if($('input:radio[name=chkCaracNegoCantPerso]:checked').val()=="MayorA10"){
            mayor10personas="1";
        }else
            menorigual10personas="1";
        
        if($('input:radio[name=chkTipoContacto1]').is(':checked')){
            tipoVisita = $('input:radio[name=chkTipoContacto1]:checked').val();
            numeroVisita = "1";
        }
        if($('input:radio[name=chkTipoContacto2]').is(':checked')){
            tipoVisita  = $('input:radio[name=chkTipoContacto2]:checked').val();
            numeroVisita = "2";
        }
        
        
        // alert(idGiroNegocio+'\n'+detalleGiroExtraNegocio+'\n'+idAfrontarPago+'\n'+detalleAfronPago);
        AtencionClienteDAO.save_visita_comercial(idGiroNegocio,detalleGiroExtraNegocio,idAfrontarPago,detalleAfronPago,menorigual10personas,mayor10personas,tipoVisita, numeroVisita);
    }
}

FillClienteCartera = function(){
    AtencionClienteDAO.searchClienteCartera();
}

FillIdCuenta = function(){
    AtencionClienteDAO.FillIdCuentaByCode();
}
irInicio = function(){
     $('#irInicio').click(function () {
     $('html, body').animate({ scrollTop: '100px'}, 10);
     return false;
   });  
}
//FIN PIRO
isNumbers=function (e) {
 k = (document.all) ? e.keyCode : e.which;
 
    
 
if (k==8 || k==0 || k==32 || k==16 || k==18  ) return true;
  patron = /[A-Z0-9a-z,\/.$()@-]/;
  n = String.fromCharCode(k);
  return patron.test(n);
}      


isNumero = function   (e) {
 k = (document.all) ? e.keyCode : e.which;
 h = e.keyCode;
  if (h==9) return true;
 if ( k==17 || k==16 || k==8 || k==46 || k==37 || k==38 || k==39 || k==40 || k==13 ) return true;
  patron = /[0-9]/;
  n = String.fromCharCode(k);
  return patron.test(n);
} 

isTelefono = function   (e) {
 k = (document.all) ? e.keyCode : e.which;
 h = e.keyCode;

  if (h==9 || h==37 || h==38 || h==39 || h==40) return true;
 if ( k==17 || k==16 || k==8  || k==13 ) return true;
  patron = /[0-9]/;
  n = String.fromCharCode(k);
  return patron.test(n);
} 

generarTablaAcuerdaPago = function(){
    var html="";
    var filas="";
    var numeroPagare = $('#txtNumeroPagareCovinoc').val();
    var numeroCuotas = $('#txtNumeroDeCuotasCovinoc').val();
    var fechaAcuerdo = $('#txtFechaAcuerdoCovinoc').val();
    var valorAcuerdo = $('#txtValorAcuerdoCovinoc').val();
    var cuota = parseFloat(valorAcuerdo/numeroCuotas);

    if(numeroPagare== undefined || numeroPagare==''){
        _displayBeforeSendDl('Numero de Pagare no establecido',400);
        return false;
    }
    if(numeroCuotas==undefined || numeroCuotas=='' || numeroCuotas==0 ){
        _displayBeforeSendDl('Numero de cuota no establecido',400);
        return false;
    }
    if(fechaAcuerdo==undefined || fechaAcuerdo==''){
        _displayBeforeSendDl('Fecha de acuerdo no establecido',400);
        return false;
    }
    if(valorAcuerdo==undefined || valorAcuerdo==''  || valorAcuerdo==0 ){
        _displayBeforeSendDl('Valor de acuerdo no establecido',400);
        return false;
    }
    for(i=0;i<numeroCuotas;i++){
        filas+= "<tr><td class='rowBoxContent textForm' style='font-weight:bold;-moz-user-select: none;cursor:default'>Número de Cuota</td><td ><input style='cursor:not-allowed' readonly='readonly' class='rowBoxContent nro_cuota' type='text' placeholder='Numero de Cuotas' value='"+(i+1)+"' ></td><td class='rowBoxContent textForm' style='font-weight:bold;-moz-user-select: none;cursor:default'>Fecha Cuota</td><td><input  onkeyup=' if( event.keyCode == 13 ){ aumentarMesesALaTablaAPartirDeUnaFechaByindice($(this).attr(\"indice\"),$(this).val()) }' indice='"+i+"' class='rowBoxContent standarFechaAcuerdo' type='text' placeholder='Fecha Cuota' value='"+fechaAcuerdo+"' ></td><td class='rowBoxContent textForm' style='font-weight:bold;-moz-user-select: none;cursor:default'>Valor Cuota</td><td><input onkeypress='return isNumero(event);'  indice='"+i+"' onkeyup=' if( event.keyCode == 13 ){ generarDemasMonto($(this).attr(\"indice\")) }' class='rowBoxContent' type='text' placeholder='Valor Cuota' value='"+cuota+"'' ></td></tr>";
    }
    html="<table id='tblDataAcu' style='margin:0px auto;text-align:center' class='tableForm BoxContent'>"+filas+"</table>";
    $('#tblAcuPago').html(html);

    aumentarMesesALaTablaAPartirDeUnaFecha();

    $('#btngrabar_acuerdo_de_pago_covinoc,#btnActualizar_acuerdo_de_pago_covinoc').button();
    $('#btngrabar_acuerdo_de_pago_covinoc').css('opacity',1);

    $('.standarFechaAcuerdo').datepicker({
        dateFormat:'yy-mm-dd',
       // minDate : 0,
        //maxDate : +5,
        dayNamesMin:['D','L','M','M','J','V','S'],
        monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre'], 
        currentText : 'Now'
    });

    
}

grabar_acuerdo_de_pago_covinoc = function(){
    var usuarioServicio = $('#hdCodUsuarioServicio').val();
    var idClienteCartera = $('#txtidcliente_cartera').val();
    var numeroPagare = $('#txtNumeroPagareCovinoc').val();
    var numeroCuotas = $('#txtNumeroDeCuotasCovinoc').val();
    var fechaAcuerdo = $('#txtFechaAcuerdoCovinoc').val();
    var valorAcuerdo = $('#txtValorAcuerdoCovinoc').val();


    var cantidadCuentas =   $('#tblCuentasAcuerdoPago').
                            find(':checked').map(function()
                              { 
                                  return $(this).val()
                              }
                            ).get().length;
    var idCuenta = $('#tblCuentasAcuerdoPago').
                    find(':checked').map(function()
                      { 
                          return $(this).val()
                      }
                    ).get().join(',');

    var cantidadFechaCuota = $('#tblDataAcu').find('tr').map(function(){
                                if($(this).find('td:eq(3) input').val()!=""){                      
                                    return $(this).find('td:eq(3) input').val()  ;}
                                }).get().length;

    var cantidadValorCuota = $('#tblDataAcu').find('tr').map(function(){
                                if($(this).find('td:eq(5) input').val()!=""){                      
                                    return $(this).find('td:eq(5) input').val()  ;}
                                }).get().length;

    var arrayCantidadValorCuota = $('#tblDataAcu').find('tr').map(function(){
                                if($(this).find('td:eq(5) input').val()!=""){                      
                                    return parseFloat($(this).find('td:eq(5) input').val())  ;}
                                }).get();

    var suma= 0;
    for(i=0;i<arrayCantidadValorCuota.length;i++){
        suma += arrayCantidadValorCuota[i];
    }

    var detalleAcuerdoPago = '['+$('#tblDataAcu').find('tr').map(function(){
                            return "{\"numero_cuota\" : \"" + $(this).find('td:eq(1) input').val() + "\","+
                                    "\"fecha_cuota\" :\"" + $(this).find('td:eq(3) input').val() +"\","+
                                    "\"valor_cuota\" :\"" + $(this).find('td:eq(5) input').val() + "\"}" ;
                            }).get().join(',')+']';

    if(cantidadCuentas != 1){
        _displayBeforeSendDl('Seleccionar 1 cuenta',400);
        return false;
    }

    if(cantidadFechaCuota != numeroCuotas){
        _displayBeforeSendDl('No se permite fecha de cuota vacia',400);
        return false;
    }
    
    if(cantidadValorCuota != numeroCuotas){
        _displayBeforeSendDl('No se permite valor de cuota vacia',400);
        return false;
    }

    if(Math.round(suma*100)/100 != valorAcuerdo){
        _displayBeforeSendDl('La suma de los Valores de Cuota no concuerda con el Valor de Acuerdo',800);
        return false;
    }

    AtencionClienteDAO.saveAcuerdoPago(usuarioServicio, idClienteCartera, idCuenta, numeroPagare, numeroCuotas, fechaAcuerdo, valorAcuerdo, detalleAcuerdoPago);

}

formato_numero= function(res){ // v2007-08-06
// Variable que contendra el resultado final
    resultado = parseFloat(res).toFixed(2).toString();
    resultado = resultado.split(".");
    var cadena = ""; 
    var cont = 1;
    for(m=resultado[0].length-1; m>=0; m--){
        cadena = resultado[0].charAt(m) + cadena;
        cont%3 == 0 && m >0 ? cadena = "," + cadena : cadena = cadena;
        cont== 3 ? cont = 1 :cont++;
    }
    return cadena + "." + resultado[1];
}

generarDemasMonto = function(indice){
    var array =$('#tblDataAcu').find('tr:lt('+(parseInt(indice)+1)+')').map(function(){
      
      return parseFloat($(this).find('td:eq(5) input').val() ) ;
    }).get();

    var sum=0;
    for(i=0;i<array.length;i++){
    sum += array[i];
    }

    $('#tblDataAcu').find('tr:gt('+(parseInt(indice))+')').map(function(){
      var vAcuerdo = parseFloat($('#txtValorAcuerdoCovinoc').val());               
      var vNCuota = $('#txtNumeroDeCuotasCovinoc').val() - (parseInt(indice)+1);
      var valor = (vAcuerdo - sum )/ vNCuota;
       $(this).find('td:eq(5) input').val(valor)  ;
    });

}

 aumentarMesesALaTablaAPartirDeUnaFecha = function(){ 
    var txtFecha = $('#txtFechaAcuerdoCovinoc').val();
    txtFecha=txtFecha.replace('-','/').replace('-','/');
    var cantAcuerdos = $('#tblDataAcu').find('tr').get().length ;
    var fecha = new Date(txtFecha);
  
    for(i=0;i<cantAcuerdos;i++){
        if(i==0){
            fecha.setMonth(fecha.getMonth() + 0);
            date = new Date(fecha);
            inputFecha = (date.getFullYear()+'-'+(((date.getMonth()+1).toString().length==1)?'0'+
                (date.getMonth()+1):(date.getMonth()+1))+'-'
                +(((date.getDate()).toString().length==1)?'0'+date.getDate():date.getDate()));
            console.log( $('#tblDataAcu').find('tr:eq('+i+')').find('td:eq(3) input').val(inputFecha) );
        }else{
            fecha.setMonth(fecha.getMonth() + 1);
            date = new Date(fecha);
            inputFecha = (date.getFullYear()+'-'+(((date.getMonth()+1).toString().length==1)?'0'+
                (date.getMonth()+1):(date.getMonth()+1))+'-'
               +(((date.getDate()).toString().length==1)?'0'+date.getDate():date.getDate()));   
            console.log( $('#tblDataAcu').find('tr:eq('+i+')').find('td:eq(3) input').val(inputFecha) );
        }
    }
}


aumentarMesesALaTablaAPartirDeUnaFechaByindice = function(indice,xFecha){ 

    var txtFecha = xFecha ;
    txtFecha=txtFecha.replace('-','/').replace('-','/');
    var fecha = new Date(txtFecha);
    var VTemporal = indice;
    var cantAcuerdos = $('#tblDataAcu').find('tr').get().length ;
  
    for(i=0;i<cantAcuerdos;i++){

      if(i>=VTemporal){
        
         $('#tblDataAcu').find('tr:eq('+(parseInt(VTemporal))+')').map(function(){
                
            if(VTemporal==indice){
                fecha.setMonth(fecha.getMonth() + 0);
                date = new Date(fecha);
                inputFecha = (date.getFullYear()+'-'+(((date.getMonth()+1).toString().length==1)?'0'+
                    (date.getMonth()+1):(date.getMonth()+1))+'-'
                    +(((date.getDate()).toString().length==1)?'0'+date.getDate():date.getDate()));
                console.log( $('#tblDataAcu').find('tr:eq('+VTemporal+')').find('td:eq(3) input').val(inputFecha) );
            }else{
                fecha.setMonth(fecha.getMonth() + 1);
                date = new Date(fecha);
                inputFecha = (date.getFullYear()+'-'+(((date.getMonth()+1).toString().length==1)?'0'+
                    (date.getMonth()+1):(date.getMonth()+1))+'-'
                   +(((date.getDate()).toString().length==1)?'0'+date.getDate():date.getDate()));   
                console.log( $('#tblDataAcu').find('tr:eq('+VTemporal+')').find('td:eq(3) input').val(inputFecha) );
            }
            VTemporal++;

        });
      }

    }
}


mostrar_telf_aval=function(codigo_cliente){
    // AtencionClienteDAO.aval_telefono(codigo_cliente);

    $("#add_telf_titu_aval").val(0);
    $("#codigo_cliente_aval_opcion").val(codigo_cliente);

    $('#table_gestion_telefono').jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=gestion_telefono&codigo_cliente='+codigo_cliente,
    }).trigger('reloadGrid');

    $('#DialogGestionTelefonos').dialog('open');

}
mostrar_direct_aval=function(codigo_cliente){
    // AtencionClienteDAO.aval_direccion(codigo_cliente);

    $("#add_telf_titu_aval").val(0);
    $("#codigo_cliente_aval_opcion").val(codigo_cliente);


    $('#table_gestion_direccion_opcion').jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=gestion_direccion_opcion&codigo_cliente='+codigo_cliente,
    }).trigger('reloadGrid');
    $('#DialogGestionDireccion_opcion').dialog('open');

}
mostrar_direct_aval_campo=function(codigo_cliente){
    AtencionClienteDAO.aval_direccion_campo(codigo_cliente);
}
selecte_telf_aval=function(telf_aval,idtelf){
    $('#txtAtencionClienteNumeroCall').val('');
    $('#txtAtencionClienteNumeroCall').val(telf_aval);

    $('#HdIdTelefono').val(idtelf);

    $('#table_aval_telf').dialog('close');

}


$("#cbCampoDireccionVisita").live( "change", function() {
  $('input[name=direccion_campo]:radio').attr("checked", false);
});


$("input[name=direccion_campo]").live("change",function(){
$('#cbCampoDireccionVisita').val(0);   
    
});

$( "#table_cuenta_aplicar_gestion tr" ).live( "click", function() {
    if($(this).attr('id') != ''){           
        $('#table_cuenta_aplicar_gestion tr[aria-contrato="'+$(this).attr('id')+'"]').toggle();
    }else{
    }
});


// CAMBIO 20-06-2016
mostrar_direccion_opcion=function(codigo_cliente){
    $("#add_telf_titu_aval").val(1);
    $("#codigo_cliente_aval_opcion").val(codigo_cliente);
    $('#table_gestion_direccion_opcion').jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=gestion_direccion_opcion&codigo_cliente='+codigo_cliente,
    }).trigger('reloadGrid');
    $('#DialogGestionDireccion_opcion').dialog('open');
}
// CAMBIO 20-06-2016

$("#idmantemiento_telf_cobranzas").live('click',function(){
    if($("#CodigoClienteMain").val()!=''){
        
        // Listar Telefonos
        // AtencionClienteDAO.Listar_Telefono_opcion($("#CodigoClienteMain").val());

        var codigo_cliente=$("#CodigoClienteMain").val();

        $('#table_Lista_telf_cobranzas').jqGrid('setGridParam',{
        datatype : 'json',
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=List_Telf_cobranzas_andina&codigo_cliente='+codigo_cliente,
        }).trigger('reloadGrid');

        $('#Dialoggestiontelefonos_cobranzas').dialog('open');


    }else{
        alert("Es necesario realizar la busqueda de un cliente");
    }
});

$("#idconsultar_telefono_campo").live('click',function(){
    if($("#CodigoClienteCampoMain").val()!=''){
        
        // Listar Telefonos
        // AtencionClienteDAO.Listar_Telefono_opcion($("#CodigoClienteMain").val());

        var codigo_cliente=$("#CodigoClienteCampoMain").val();

        $('#table_Lista_telf_cobranzas').jqGrid('setGridParam',{
        datatype : 'json',
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=List_Telf_cobranzas_andina&codigo_cliente='+codigo_cliente,
        }).trigger('reloadGrid');

        $('#Dialoggestiontelefonos_cobranzas').dialog('open');


    }else{
        alert("Es necesario realizar la busqueda de un cliente");
    }
});


$("#idadd_telf_cobranzas_save").live('click',function(){
    AtencionClienteDAO.Listtipotelf_andina();
    AtencionClienteDAO.Listreferenciatelf_andina();
    AtencionClienteDAO.Listlineatelf_andina();
    AtencionClienteDAO.Listorigentelf_andina();

    $('#txtnumero_cob_save').val('');
    $('#anexo_cob_save').val('');
    $('#slctipo_cob_save').val('');
    $('#slcreferencia_cob_save').val('');
    $('#slclinea_cob_save').val('');
    $('#slcorigem_cob_save').val('');
    $('#slccondi_cob_save').val('');
    $('#areaobs_cob_save').val('');

    $('#Dialoggestiontelefonos_cobranzas_save').dialog('open');    
});

function save_telf_andina(){
    var numero=$('#txtnumero_cob_save').val();
    var anexo=$('#anexo_cob_save').val();
    var tipo=$("#slctipo_cob_save").val();
    var referencia=$("#slcreferencia_cob_save").val();
    var linea=$("#slclinea_cob_save").val();
    var origen=$("#slcorigem_cob_save").val();
    var condi=$("#slccondi_cob_save").val();
    var obs=$("#areaobs_cob_save").val();

    if(numero==''){
        alert("Ingrese Numero del Cliente");
        return false;
    }else{
        AtencionClienteDAO.si_elnumero_telf_existe(numero);
    }

    AtencionClienteDAO.save_telf_cobranza_andina(numero,anexo,tipo,referencia,linea,origen,condi,obs);

}


function editar_telf_andina(id){
    // alert(id);

    AtencionClienteDAO.List_Update_Telf_Andina(id);

    $("#hdidtelefono_edit_andina").val(id);
    AtencionClienteDAO.Listtipotelf_andina();
    AtencionClienteDAO.Listreferenciatelf_andina();
    AtencionClienteDAO.Listlineatelf_andina();
    AtencionClienteDAO.Listorigentelf_andina();

    $('#Dialoggestiontelefonos_cobranzas_edit').dialog('open');

}

function update_telf_andina(){
    var idtelefono=$("#hdidtelefono_edit_andina").val();

    var numero=$('#txtnumero_cob_edit').val();
    var anexo=$('#anexo_cob_edit').val();
    var tipo=$('#slctipo_cob_edit').val();
    var referencia=$('#slcreferencia_cob_edit').val();
    var linea=$('#slclinea_cob_edit').val();
    var origen=$('#slcorigem_cob_edit').val();
    var state=$('#slcstate_cob_edit').val();
    var status=$('#slcstatus_cob_edit').val();
    var condi=$('#slccondi_cob_edit').val();
    var obs=$('#areaobs_cob_edit').val();

    

    if(numero==''){
        alert("Ingrese Numero del Cliente");
        return false;
    }

    AtencionClienteDAO.update_telf_andina(idtelefono,numero,anexo,tipo,referencia,linea,origen,state,status,condi,obs);


    
}

function eliminar_telf_andina(id){
    AtencionClienteDAO.eliminar_telf_andina(id)
}



$("#idmantemiento_direccion_cobranzas").live('click',function(){
    $("#hdmantdatoscontacto").val("CALL");

    if($("#CodigoClienteMain").val()!=''){

        var codigo_cliente=$("#CodigoClienteMain").val();

        $('#table_Lista_Direc_cobranzas').jqGrid('setGridParam',{
        datatype : 'json',
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=lista_direccion_cobranzas&codigo_cliente='+codigo_cliente,
        }).trigger('reloadGrid');

        $('#Dialoggestiondireccion_cobranzas').dialog('open');

    }else{
        alert("Es necesario realizar la busqueda de un cliente");
    }
});

$("#idmantemiento_contactos_cobranzas").live('click',function(){
    // $("#hdmantdatoscontacto").val("CALL");

    if($("#idClienteMain").val()!=''){

        var idcliente=$("#idClienteMain").val();

        $('#table_Lista_contacto_cobranzas').jqGrid('setGridParam',{
        datatype : 'json',
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=Listar_Contactos&idcliente='+idcliente,
        }).trigger('reloadGrid');

        $('#Dialog_contacto_cobranzas').dialog('open');

    }else{
        alert("Es necesario realizar la busqueda de un cliente");
    }
});


$("#idmantemiento_contactos_cobranzas_vis").live('click',function(){
    // $("#hdmantdatoscontacto").val("CALL");

    if($("#IdClienteCampoMain").val()!=''){

        var idcliente=$("#IdClienteCampoMain").val();

        $('#table_Lista_contacto_cobranzas').jqGrid('setGridParam',{
        datatype : 'json',
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=Listar_Contactos&idcliente='+idcliente,
        }).trigger('reloadGrid');

        $('#Dialog_contacto_cobranzas').dialog('open');

    }else{
        alert("Es necesario realizar la busqueda de un cliente");
    }
});

$("#idconsultar_direccion_campo").live('click',function(){
    $("#hdmantdatoscontacto").val("VISIT");
    if($("#CodigoClienteCampoMain").val()!=''){

        var codigo_cliente=$("#CodigoClienteCampoMain").val();

        $('#table_Lista_Direc_cobranzas').jqGrid('setGridParam',{
        datatype : 'json',
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=lista_direccion_cobranzas&codigo_cliente='+codigo_cliente,
        }).trigger('reloadGrid');

        $('#Dialoggestiondireccion_cobranzas').dialog('open');

    }else{
        alert("Es necesario realizar la busqueda de un cliente");
    }
});

$("#idadd_direccion_cobranzas_save").live('click',function(){

    $('#slcprov_dir_cob_save').html("<option value=''>--Seleccione--</option>");
    $('#slcdistri_dir_cob_save').html("<option value=''>--Seleccione--</option>");

    AtencionClienteDAO.List_Departamento_andina();
    AtencionClienteDAO.Listorigentelf_andina();
    AtencionClienteDAO.Listreferenciatelf_andina();

    $('#txtdireccion_cob_save').val('');
    $('#slcdepa_dir_cob_save').val('');
    $('#slcprov_dir_cob_save').val('');
    $('#slcdistri_dir_cob_save').val('');
    $('#txtregion_dir_cob_save').val('');
    $('#txtzona_dir_cob_save').val('');
    $('#txtcodpostdir_cob_save').val('');
    $('#txtnumero_dir_cob_save').val('');
    $('#txtcalle_dir_cob_save').val('');
    $('#txtref_dir_cob_save').val('');
    $('#slcorig_dir_cob_save').val('');
    $('#areaobs_dir_cob_save').val('');

    $('#Dialoggestiondireccion_cobranzas_save').dialog('open');    
});

$("#slcdepa_dir_cob_save").live("change",function(){
    AtencionClienteDAO.List_Provincia_andina($(this).val());
});

$("#slcprov_dir_cob_save").live("change",function(){
    AtencionClienteDAO.List_Distrito_andina($(this).val());
});

function save_direccion_andina(){

    if($("#hdmantdatoscontacto").val()=="CALL"){
        var xidcliente_cartera= $("#IdClienteCarteraMain").val();
        var xcodigo_cliente= $("#CodigoClienteMain").val();
        var xidcartera= $("#IdCartera").val();
    }else if($("#hdmantdatoscontacto").val()=="VISIT"){
        var xidcliente_cartera= $("#IdClienteCarteraCampoMain").val();
        var xcodigo_cliente= $("#CodigoClienteCampoMain").val();
        var xidcartera= $("#IdCarteraCampoMain").val();
    }

    var xidusuario_servicio= $("#hdCodUsuarioServicio").val();

    var dir=$('#txtdireccion_cob_save').val();
    var dep=$('#slcdepa_dir_cob_save').val();
    var prov=$('#slcprov_dir_cob_save').val();
    var dis=$('#slcdistri_dir_cob_save').val();
    var reg=$('#txtregion_dir_cob_save').val();
    var zon=$('#txtzona_dir_cob_save').val();
    var cod=$('#txtcodpostdir_cob_save').val();
    var num=$('#txtnumero_dir_cob_save').val();
    var call=$('#txtcalle_dir_cob_save').val();
    var txtref=$('#txtref_dir_cob_save').val();
    var ref=$('#slcref_dir_cob_save').val();
    var orig=$('#slcorig_dir_cob_save').val();
    var condi=$('#slccondi_dir_cob_save').val();
    var obs=$('#areaobs_dir_cob_save').val();


    if(dir==''){
        alert("Ingresar Dirección del Cliente");
        return false;
    }

    if(dep==""){
        alert("Ingresar Departamento del Cliente");
        return false;
    }
    if(prov==""){
        alert("Ingresar Provincia del Cliente");
        return false;
    }
    if(dis==""){
        alert("Ingresar Distrito del Cliente");
        return false;
    }

    AtencionClienteDAO.insertar_nueva_direccion_andina(xidcliente_cartera,xcodigo_cliente,xidcartera,xidusuario_servicio,dir,dep,prov,dis,reg,zon,cod,num,call,txtref,ref,orig,condi,obs);
    
}


function editar_direccion_andina(id){
    
    $('#hdiddireccion_andina').val(id);

    AtencionClienteDAO.List_Departamento_andina();
    AtencionClienteDAO.Listorigentelf_andina();
    AtencionClienteDAO.Listreferenciatelf_andina();

    var rowData = $("#table_Lista_Direc_cobranzas").getRowData(id);
    var direccion= rowData['direccion'];
    var departamento= rowData['departamento'];
    var provincia= rowData['provincia'];
    var distrito= rowData['distrito'];
    var region= rowData['region'];
    var zona= rowData['zona'];
    var codigo_postal= rowData['codigo_postal'];
    var numero= rowData['numero'];
    var calle= rowData['calle'];
    var txtref= rowData['referencia'];
    var referencia= rowData['tipo_referencia'];
    var origen= rowData['origen'];
    var condicion= rowData['condicion'];
    var estado= rowData['estado'];
    var observacion= rowData['observacion'];

    $('#txtdireccion_cob_edit').val(direccion);
    $('#txtregion_dir_cob_edit').val(region);
    $('#txtzona_dir_cob_edit').val(zona);
    $('#txtcodpostdir_cob_edit').val(codigo_postal);
    $('#txtnumero_dir_cob_edit').val(numero);
    $('#txtcalle_dir_cob_edit').val(calle);
    $('#txtref_dir_cob_edit').val(txtref);
    $('#areaobs_dir_cob_edit').val(observacion);
    $("#slcestado_cob_edit").find('option[text="'+estado+'"]').attr('selected', 'selected');
    $("#slccondi_dir_cob_edit").find('option[text="'+condicion+'"]').attr('selected', 'selected');
    $("#hddir_dep_andina").val(departamento);
    $("#hddir_prov_andina").val(provincia);
    $("#hddir_dis_andina").val(distrito);
    $("#hddir_tipref_andina").val(referencia);
    $("#hddir_orig_andina").val(origen);

    $('#Dialoggestiondireccion_cobranzas_edit').dialog('open');
}

function modificar_direccion_andina(){
    var iddireccion=$('#hdiddireccion_andina').val();
    var direccion=$('#txtdireccion_cob_edit').val();
    var departamento=$('#slcdepa_dir_cob_edit').val();
    var provincia=$('#slcprov_dir_cob_edit').val();
    var distrito=$('#slcdistri_dir_cob_edit').val();
    var region=$('#txtregion_dir_cob_edit').val();
    var zona=$('#txtzona_dir_cob_edit').val();
    var codigo_postal=$('#txtcodpostdir_cob_edit').val();
    var numero=$('#txtnumero_dir_cob_edit').val();
    var calle=$('#txtcalle_dir_cob_edit').val();
    var referencia=$('#txtref_dir_cob_edit').val();
    var tipo_referencia=$('#slcref_dir_cob_edit').val();
    var origen=$('#slcorig_dir_cob_edit').val();
    var condicion=$('#slccondi_dir_cob_edit').val();
    var estado=$('#slcestado_cob_edit').val();
    var observacion=$('#areaobs_dir_cob_edit').val();

    AtencionClienteDAO.modificar_direccion_andina(iddireccion,direccion,departamento,provincia,distrito,region,zona,codigo_postal,numero,calle,referencia,tipo_referencia,origen,condicion,estado,observacion);

    

}

$("#slcdepa_dir_cob_edit").live("change",function(){
    AtencionClienteDAO.List_Provincia_andina($(this).val());
});

$("#slcprov_dir_cob_edit").live("change",function(){
    AtencionClienteDAO.List_Distrito_andina($(this).val());
});

function eliminar_direccion_andina(id){
    AtencionClienteDAO.eliminar_direccion_andina(id);
}


$("#idmantemiento_correo_cobranzas").live('click',function(){
    if($("#CodigoClienteMain").val()!=''){

        var idcliente=$("#idClienteMain").val();

        $('#table_Lista_Correo_cobranzas').jqGrid('setGridParam',{
        datatype : 'json',
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=List_Correo_cobranzas_andina&idcliente='+idcliente,
        }).trigger('reloadGrid');

        $('#Dialoggestioncorreo_cobranzas').dialog('open');

    }else{
        alert("Es necesario realizar la busqueda de un cliente");
    }
});

$("#idconsultar_correo_campo").live('click',function(){
    if($("#CodigoClienteCampoMain").val()!=''){

        var idcliente=$("#IdClienteCampoMain").val();

        $('#table_Lista_Correo_cobranzas').jqGrid('setGridParam',{
        datatype : 'json',
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=List_Correo_cobranzas_andina&idcliente='+idcliente,
        }).trigger('reloadGrid');

        $('#Dialoggestioncorreo_cobranzas').dialog('open');

    }else{
        alert("Es necesario realizar la busqueda de un cliente");
    }
});


$("#idadd_mail_cobranzas_save").live('click',function(){

    $('#txtcorreo_mail_cob_save').val('');
    $('#obs_mail_cob_save').val('');

    $('#Dialoggestionmail_cobranzas_save').dialog('open');    
});

function save_mail_andina(){
    var mail=$("#txtcorreo_mail_cob_save").val();
    var obs=$("#obs_mail_cob_save").val();
    AtencionClienteDAO.save_mail_andina(mail,obs);
}

function editar_mail_andina(id){
    $("#hdidcorreo_andina").val(id);

    var rowData = $("#table_Lista_Correo_cobranzas").getRowData(id);
    var correo= rowData['correo'];
    var observacion= rowData['observacion'];
    var estado= rowData['estado'];

    $('#txtcorreo_mail_cob_edit').val(correo);
    $('#obs_mail_cob_edit').val(observacion);
    $("#slcstatusmail_cob_edit").find('option[text="'+estado+'"]').attr('selected', 'selected');

    $('#Dialoggestionmail_cobranzas_edit').dialog('open');  

}

function UPDATE_Correo(){
    var idcorreo=$("#hdidcorreo_andina").val();
    var correo=$('#txtcorreo_mail_cob_edit').val();
    var observacion=$('#obs_mail_cob_edit').val();
    var idusuario_servicio=$("#hdCodUsuarioServicio").val();
    var estado=$("#slcstatusmail_cob_edit").val();
    AtencionClienteDAO.UPDATE_Correo(idcorreo,correo,observacion,idusuario_servicio,estado);

}

function eliminar_mail_andina(id){
    AtencionClienteDAO.eliminar_mail_andina(id);
}


reloadJQGRID_visitax = function ( ) {

    var fecha_inicio=$('#call_ini').val();
    var fecha_fin=$('#call_fin').val();
    var idcliente_cartera = $('#IdClienteCarteraCampoMain').val();
    var estado=$("#cbCampoFinal_vis").find(":selected").val();

    // if( fecha_inicio=='' || fecha_fin=='' ) {
    //     $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError('Ingrese fecha de inicio y fecha fin','400px'));
    //     AtencionClienteDAO.setTimeOut_hide_message();
    //     return false;   
    // }

    $("#table_campo_visita").jqGrid('setGridParam',{
        datatype : 'json',
        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_campo_visita&FechaInicio='+fecha_inicio+'&FechaFin='+fecha_fin+'&ClienteCartera='+idcliente_cartera+'&estado='+estado
    }).trigger('reloadGrid');
}

// $("#call_ini").change(function(){

// });


$("#call_ini,#call_fin").live('keypress',function (ev) {
    var keycode = (ev.keyCode ? ev.keyCode : ev.which);
    if (keycode == '13') {
        reloadJQGRID_visitax()
    }
})


function Listar_Contactos_Telefonos(idpersona){
    $('#Dialog_contacto_telefono').dialog('open');

    $("#contactopers_nro").val("");
    $("#hpidpersona").val(idpersona);

    AtencionClienteDAO.Listar_Contactos_telf(idpersona);
    AtencionClienteDAO.cbo_listar_origen();
    AtencionClienteDAO.cbo_tipo_telefono();
    AtencionClienteDAO.cbo_linea_telefono();
}


function actualizar_contacto_telf(xidpersona){
    $("#telf_contacto tbody tr").removeClass("ui-state-highlight");

    var sidtelefono_pers="";
    var snumero="";
    var sidorigen="";
    var sidtipo_telefono="";
    var sidlinea_telefono="";

    $("#telf_contacto tbody tr").each(function( index ) {
        var tr=$(this);
        
        var idpersona=tr.find('td:eq(0)').text()

        if (idpersona==xidpersona) {
            sidtelefono_pers=tr.find('td:eq(0)').text();
            snumero=tr.find('td:eq(1)').text();
            sidorigen=tr.find('td:eq(2)').text();
            sidtipo_telefono=tr.find('td:eq(4)').text();
            sidlinea_telefono=tr.find('td:eq(6)').text();
        }
    });

    $("#idenviardata").val("MODIFICAR");

    $("#idtelefono_pers").val(sidtelefono_pers);
    $("#contactopers_nro").val(snumero);
    $("#pers_origen").val(sidorigen);
    $("#pers_tip_telf").val(sidtipo_telefono);
    $("#pers_lin_telf").val(sidlinea_telefono);

}

function borrar_contacto_tefl(xidtelefono_pers){
    AtencionClienteDAO.borrar_contacto_tefl(xidtelefono_pers);
}

function Listar_Contactos_Correo(idpersona){
    $('#Dialog_contacto_correo').dialog('open');
    $("#hpidpersona").val(idpersona);
    AtencionClienteDAO.Listar_Contactos_mail(idpersona);

}

function actualizar_contacto_mail(xidpersona){
    $("#mail_contacto tbody tr").removeClass("ui-state-highlight");

    var sidemail_pers="";
    var semail="";

    $("#mail_contacto tbody tr").each(function( index ) {
        var tr=$(this);
        
        var idpersona=tr.find('td:eq(0)').text()

        if (idpersona==xidpersona) {
            sidemail_pers=tr.find('td:eq(0)').text();
            semail=tr.find('td:eq(1)').text();

        }
    });

    $("#idenviarmail").val("MODIFICAR");

    $("#idemail_pers").val(sidemail_pers);
    $("#email").val(semail);


}

function borrar_contacto_mail(xidemail_pers){
    AtencionClienteDAO.borrar_contacto_mail(xidemail_pers);
}


