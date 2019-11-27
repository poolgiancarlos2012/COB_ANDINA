var AtencionClienteJQGRID={
    type : 'json',
    telefonos_cliente : function ( ) {
        $("#table_telefonos_cliente").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_numero_telefono',
            datatype:'local',
            gridview:true,
            ajaxGridOptions : { async : false },
            height:70,
            colNames:['Numero','Anexo','Linea','Estado','Referencia','Prefijos','Peso','Origen'],
            colModel:[
                {name:'t1.numero',index:'t1.numero',align:'center',width:100,sortable:false },
                {name:'t1.anexo',index:'t1.anexo',align:'center',width:50,sortable:false },
                {name:'t1.linea',index:'t1.linea',align:'center',width:50,sortable:false, editable:true, edittype:'select'},
                {name:'t1.estado',index:'t1.estado',align:'center',width:50,sortable:false },
                {name:'t1.referencia',index:'t1.referencia',align:'center',width:50,hidden:false,sortable:false },
                {name:'t1.prefijos',index:'t1.prefijos',align:'center',width:50,hidden:true,sortable:false},
                {name:'t1.peso',index:'t1.peso',align:'center',width:50,hidden:true,sortable:false},
                {name:'t1.origen',index:'t1.origen',align:'center',hidden:false,width:50,sortable:false}


            ],
            rowNum:10,
            rowList:[20,25],
            rownumbers:true,
            pager:'#pager_table_telefonos_cliente',
            pgbuttons : false,
            pginput : false,
            pgtext : 'No',
            sortname:'t1.idtelefono',
            ondblClickRow : function ( rowid, irow, icol, e ) {

                $('#txtAtencionClienteNumeroCall').val('');
                $('#txtAtencionClienteNumeroCall').attr('prefixs','');
                $('#txtAtencionClienteNumeroCall').attr('line','');

                var xnumero = $( $("#table_telefonos_cliente").jqGrid("getRowData",rowid)['t1.numero'] ).text();

                var LinTel_grd_gt = new Array();
                for( i=0;i<AtencionClienteDAO.LineasTelefono.length;i++ ) {
                    LinTel_grd_gt.push( '"'+AtencionClienteDAO.LineasTelefono[i].idlinea_telefono+'":"'+AtencionClienteDAO.LineasTelefono[i].nombre+'"' );
                }
                var Pr_LinTel_grd_gt = $.parseJSON( '{'+LinTel_grd_gt.join(",")+'}' );
                $('#table_telefonos_cliente').jqGrid('setColProp','t1.linea',{editoptions:{ value : Pr_LinTel_grd_gt }});
                $('#table_telefonos_cliente').jqGrid('editRow',rowid,true,
                        function ( ) {},
                        function ( response ) 
                        {
                                var json_d = $.parseJSON(response.responseText);
                                if( json_d.rst  ){
                                    $('#table_telefonos_cliente').jqGrid().trigger('reloadGrid');
                                }else{
                                    
                                }
                                _displayBeforeSendDl(json_d.msg,450);
                                
                        },
                        '../controller/ControllerCobrast.php',
                        {command:'atencion_cliente',action:'ActualizarLineaTelefono', numero : xnumero, usuario_modificacion:$('#hdCodUsuario').val()}
                );
                
            },
            onSelectRow: function ( id ) { 
                getParamNumeroTelefono();
                Call(2);
            },
            loadComplete : function ( data ) {
                /*if( data.rows.length >0 ) {
								//var rowids = $('#table_telefonos_cliente').jqGrid('getDataIDs');
								$('#table_telefonos_cliente').jqGrid('setSelection',data.rows[0].id);
								$('#btnAtencionClientePhoneCall').trigger('click');
							}
							//var rowid = $('#table_telefonos_cliente').jqGrid('getRowParam','selrow');
							*/
                if( CountLoadTelefonos == 0 ) {
                    Call(0);
                }
                CountLoadTelefonos++;
            },
            sortorder:'asc',
            loadui: "block"
        });
        $("#table_telefonos_cliente").jqGrid('navGrid','#pager_table_telefonos_cliente',
                {edit:false,add:false,del:false,view:false,search:false}
        );
        $("#table_telefonos_cliente").jqGrid('navButtonAdd','#pager_table_telefonos_cliente',{ 
                        caption : "",
                        buttonicon : "ui-icon ui-icon-minus",
                        position : 'first',
                        title : 'Deshabilitar',
                        onClickButton:function(){
                            if($('#txtAtencionClienteNumeroCall').val()==''){
                                alert('Seleccione Numero Telefonico');
                                return false;
                            }
                            $.ajax({
                                url:'../controller/ControllerCobrast.php',
                                type:'POST',
                                dataType:'json',
                                data:{
                                    command : 'atencion_cliente',
                                    action  : 'deshabilitarTelefono',
                                    numero  : $('#txtAtencionClienteNumeroCall').val(),
                                    codigo_cliente : $('#CodigoClienteMain').val(),
                                    servicio : $('#hdCodServicio').val()
                                },
                                beforeSend:function(){},
                                success:function(obj){
                                    $('#table_telefonos_cliente').jqGrid().trigger('reloadGrid');
                                },
                                error:function(){}

                            });
                        }
                    }
        );
    },
    carteras_multiples : function()
    {
        $("#tbCarterasMultiples").jqGrid({
            url:'../controller/ControllerCobrast.php?command=carga-cartera&action=ListCarteraOperador&Campania=1&idusuario_servicio=318',
            datatype:'local',
            gridview:true,
            height:100,
            width : 500,
            colNames:['ID','CARTERA','FECHA INICIO','FECHA FIN'],
            colModel:[
            {
                name:'id',
                index:'idcartera',
                align:'center',
                width:15
            },
{
                name:'cartera',
                index:'nombre_cartera',
                align:'center',
                width:130
            },
{
                name:'fechaInicio',
                index:'fecha_inicio',
                align:'center',
                width:60
            },
{
                name:'fechaFin',
                index:'fecha_fin',
                align:'center',
                width:60
            }
            ],
            rowNum:10,
            rowList:[10,15,20],
            rownumbers:true,
            multiselect : true,
            toolbar: [true,"top"],
            pager:'#pager_tbCarterasMultiples',
            sortname:'idcartera',
            sortorder:'desc',
            loadui: "block"
        });
        $('#t_tbCarterasMultiples').html("<button type=\"button\" id=\"btnCargarDataCarterasMultiples\" onclick=\"$('#txtItemAtencionClienteMain').val('0');$('#txtCantidadClientesAtencionClienteMain').text('0');$('#hTipoGestion').val('propias');load_change_campania_atencion_cliente();$('#txtItemAtencionClienteMain').val('0');$('#txtCantidadClientesAtencionClienteMain').text('0');carga_cantidad_clientes_filtro();cargar_dias_mora();cargar_territorio();\">Cargar</button>");
        $('#btnCargarDataCarterasMultiples').button();
    /*$("#table_llamada").jqGrid('navGrid','#pager_table_llamada',{edit:false,add:false,del:false,view:true});
			$('#t_table_llamada').addClass('ui-corner-top');
			$('#t_table_llamada').attr('align','left');
			$('#t_table_llamada').css('height','25px');*/
    },
    facturas_digitales : function()
    {
        $("#table_facturasDigitales").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_facturas_digitales',
            datatype:'local',
            multiselect : true,
            gridview:true,
            height:170,
            width : 850,
            shrinkToFit : false,
            colNames:['ID','SOLICITO','FECHA_VENCIMIENTO','CORREO','OPERADOR RECEPCION','ENVIADO','RUTA'],
            colModel:[
            {
                name:'idfactura_digital',
                index:'idfactura_digital',
                align:'center',
                width:20
            },
{
                name:'solicita',
                index:'solicita',
                align:'center',
                width:180
            },
{
                name:'fechaVencimiento',
                index:'fecha_vencimiento',
                align:'center',
                width:80
            },
{
                name:'correo',
                index:'correo',
                align:'center',
                width:100
            },
{
                name:'operador',
                index:'operador',
                align:'center',
                width:180
            },
{
                name:'enviado',
                index:'is_send',
                align:'center',
                width:60
            },
{
                name:'rutaAbsoluta',
                index:'ruta_absoluta',
                align:'center',
                width:200
            }
            ],
            rowNum:10,
            rowList:[10,15],
            rownumbers:true,
            toolbar : [true,'top'],
            pager:'#pager_table_facturasDigitales',
            sortname:'idfactura_digital',
            /*onSelectRow: function ( id ) { 
													getParamNumeroTelefono();
												},*/
            sortorder:'desc',
            loadui: "block"
        });
        $("#table_facturasDigitales").jqGrid('navGrid','#pager_table_busqueda_manual',{
            edit:false,
            add:false,
            del:false,
            view:true
        });				
        $('#t_table_facturasDigitales').html('<button id="btnSendEmail" onClick="sendEmailFacturasDigitales()">Enviar Factura</button>');
        $('#btnSendEmail').button(
        {
            icons : {
                primary : 'ui-icon-mail-closed'
            }
        }
        );
    },
    busquedaManual : function ( ) {
        $("#table_busqueda_manual").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_busquedaManual',
            datatype:'local',
            gridview:true,
            height:100,
            colNames:['Codigo','Cliente','Numero Doc','Tipo Doc'],
            colModel:[
            {
                name:'ca_cliente.codigo',
                index:'ca_cliente.codigo',
                align:'center',
                width:80
            },
{
                name:'cliente',
                index:'cliente',
                align:'center',
                width:340
            },
{
                name:'ca_cliente.numero_documento',
                index:'ca_cliente.numero_documento',
                align:'center',
                width:100
            },
{
                name:'ca_cliente.tipo_documento',
                index:'ca_cliente.tipo_documento',
                align:'center',
                width:80
            }
            ],
            rowNum:10,
            rowList:[10,15,20],
            rownumbers:true,
            pager:'#pager_table_busqueda_manual',
            sortname:'ca_cliente.codigo',
            onSelectRow: function ( id ) { 
                AtencionClienteDAO.LoadDataCliente(id);
            },
            sortorder:'desc',
            loadui: "block"
        });
        $("#table_busqueda_manual").jqGrid('navGrid','#pager_table_busqueda_manual',{
            edit:false,
            add:false,
            del:false,
            view:true
        });
			
    },
    busquedaBase : function ( ) {
        $("#table_busqueda_base").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_busquedaBase',
            datatype:'local',
            gridview:true,
            height:150,
            colNames:['Cartera','Codigo','Nombre','Numero Doc','Tipo Doc'],
            colModel:[
            {
                name:'car.nombre_cartera',
                index:'car.nombre_cartera',
                align:'center',
                width:200
            },
{
                name:'cli.codigo',
                index:'cli.codigo',
                align:'center',
                width:100
            },
{
                name:'cli.nombre',
                index:'cli.nombre',
                align:'center',
                width:300
            },
{
                name:'cli.numero_documento',
                index:'cli.numero_documento',
                align:'center',
                width:100
            },
{
                name:'cli.tipo_documento',
                index:'cli.tipo_documento',
                align:'center',
                width:100
            }
            ],
            rowNum:10,
            rowList:[10,15,20],
            rownumbers:true,
            onSelectRow: function ( id ) { 
                AtencionClienteDAO.LoadDataCliente(id);
            },
            pager:'#pager_table_busqueda_base',
            sortname:'car.idcartera',
            sortorder:'desc',
            loadui: "block"
        });
        $("#table_busqueda_base").jqGrid('navGrid','#pager_table_busqueda_base',{
            edit:false,
            add:false,
            del:false,
            view:true
        });
    },
    busquedaEstado : function ( ) {
        $("#table_busqueda_estado").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_busquedaEstado',
            datatype:'local',
            gridview:true,
            height:150,
            colNames:['Codigo','Nombre','Numero Doc','Tipo Doc','Llamadas'],
            colModel:[
            {
                name:'cli.codigo',
                index:'cli.codigo',
                align:'center',
                width:100
            },
{
                name:'cli.nombre',
                index:'cli.nombre',
                align:'center',
                width:300
            },
{
                name:'cli.numero_documento',
                index:'cli.numero_documento',
                align:'center',
                width:100
            },
{
                name:'cli.tipo_documento',
                index:'cli.tipo_documento',
                align:'center',
                width:100
            },
{
                name:'llamadas',
                index:'llamadas',
                align:'center',
                width:80,
                search:false
            }
            ],
            rowNum:10,
            rowList:[10,15,20],
            rownumbers:true,
            onSelectRow: function ( id ) { 
                AtencionClienteDAO.LoadDataCliente(id);
            },
            pager:'#pager_table_busqueda_estado',
            sortname:'cli.nombre',
            sortorder:'desc',
            loadui: "block"
        });
        $("#table_busqueda_estado").jqGrid('navGrid','#pager_table_busqueda_estado',{
            edit:false,
            add:false,
            del:false,
            view:true
        });
    },
    busquedaGestionados : function ( ) {
        $("#table_busqueda_gestionados").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_busquedaGestionados',
            datatype:'local',
            gridview:true,
            height:200,
            colNames:['Codigo','Nombre','Numero Doc','Tipo Doc','Llamadas'],
            colModel:[
            {
                name:'cli.codigo',
                index:'cli.codigo',
                align:'center',
                width:100
            },
{
                name:'cli.nombre',
                index:'cli.nombre',
                align:'center',
                width:300
            },
{
                name:'cli.numero_documento',
                index:'cli.numero_documento',
                align:'center',
                width:100
            },
{
                name:'cli.tipo_documento',
                index:'cli.tipo_documento',
                align:'center',
                width:100
            },
{
                name:'llamadas',
                index:'llamadas',
                align:'center',
                width:80,
                search:false
            }
            ],
            rowNum:10,
            rowList:[10,15,20],
            rownumbers:true,
            onSelectRow: function ( id ) { 
                AtencionClienteDAO.LoadDataCliente(id);
            },
            pager:'#pager_table_busqueda_gestionados',
            sortname:'cli.nombre',
            sortorder:'desc',
            loadui: "block"
        });
        $("#table_busqueda_gestionados").jqGrid('filterToolbar');
        $("#table_busqueda_gestionados").jqGrid('navGrid','#pager_table_busqueda_gestionados',{
            edit:false,
            add:false,
            del:false,
            view:true
        });
    },
    busquedaSinGestion : function ( ) {
        $("#table_busqueda_sin_gestion").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_busquedaSinGestion',
            datatype:'local',
            gridview:true,
            height:200,
            colNames:['Codigo','Nombre','Numero Doc','Tipo Doc'],
            colModel:[
            {
                name:'cli.codigo',
                index:'cli.codigo',
                align:'center',
                width:100
            },
{
                name:'cli.nombre',
                index:'cli.nombre',
                align:'center',
                width:300
            },
{
                name:'cli.numero_documento',
                index:'cli.numero_documento',
                align:'center',
                width:100
            },
{
                name:'cli.tipo_documento',
                index:'cli.tipo_documento',
                align:'center',
                width:100
            }
            ],
            rowNum:10,
            rowList:[10,15,20],
            rownumbers:true,
            onSelectRow: function ( id ) { 
                AtencionClienteDAO.LoadDataCliente(id);
            },
            pager:'#pager_table_busqueda_sin_gestion',
            sortname:'cli.nombre',
            sortorder:'desc',
            loadui: "block"
        });
        $("#table_busqueda_sin_gestion").jqGrid('filterToolbar');
        $("#table_busqueda_sin_gestion").jqGrid('navGrid','#pager_table_busqueda_sin_gestion',{
            edit:false,
            add:false,
            del:false,
            view:true
        });
    },
    busquedaGlobal : function ( ) {
        $("#table_busqueda_global").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_busquedaGlobal',
            datatype:this.type,
            gridview:true,
            height:200,
            colNames:['Codigo','Nombre','Numero Doc','Tipo Doc','Servicio','Cartera','Ultima Llamada'],
            colModel:[
            {
                name:'cli.codigo',
                index:'cli.codigo',
                align:'center',
                width:80
            },
{
                name:'cli.nombre',
                index:'cli.nombre',
                align:'center',
                width:200
            },
{
                name:'cli.numero_documento',
                index:'cli.numero_documento',
                align:'center',
                width:100
            },
{
                name:'cli.tipo_documento',
                index:'cli.tipo_documento',
                align:'center',
                width:80
            },
{
                name:'ser.nombre',
                index:'ser.nombre',
                align:'center',
                width:80
            },
{
                name:'car.nombre_cartera',
                index:'car.nombre_cartera',
                align:'center',
                width:100
            },
{
                name:'tran.fecha',
                index:'tran.fecha',
                align:'center',
                width:80, 
                sorttype : 'date'
            }
            ],
            rowNum:10,
            rowList:[10,15,20],
            rownumbers:true,
            onSelectRow: function ( id ) { 
                AtencionClienteDAO.LoadDataGlobal(id);
            },
            pager:'#pager_table_busqueda_global',
            sortname:'cli.nombre',
            sortorder:'desc',
            loadui: "block",
            grouping : true,
            groupingView : {
                groupField : ['cli.codigo']
            }
        });
        $("#table_busqueda_global").jqGrid('filterToolbar');
        $("#table_busqueda_global").jqGrid('navGrid','#pager_table_busqueda_global',{
            edit:false,
            add:false,
            del:false,
            view:true
        });
    },
    matrizBusqueda : function ( ) {
        $("#table_matriz_busqueda").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_busquedaEstado',
            datatype:'local',
            gridview:true,
            height:200,
            colNames:['Codigo','Nombre','Numero Doc','Tipo Doc'],
            colModel:[
            {
                name:'cli.codigo',
                index:'cli.codigo',
                align:'center',
                width:110
            },
{
                name:'cli.nombre',
                index:'cli.nombre',
                align:'center',
                width:370
            },
{
                name:'cli.numero_documento',
                index:'cli.numero_documento',
                align:'center',
                width:110
            },
{
                name:'cli.tipo_documento',
                index:'cli.tipo_documento',
                align:'center',
                width:100
            }
            ],
            rowNum:10,
            rowList:[10,15,20],
            rownumbers:true,
            onSelectRow: function ( id ) {
                AtencionClienteDAO.LoadDataCliente(id);
            },
            pager:'#pager_table_matriz_busqueda',
            sortname:'nombre',
            sortorder:'desc',
            loadui: "block"
        });
        $("#table_matriz_busqueda").jqGrid('navGrid','#pager_table_matriz_busqueda',{
            edit:false,
            add:false,
            del:false,
            view:true
        });
    /*$("#table_matriz_busqueda").jqGrid('navButtonAdd',
												   #pager_table_matriz_busqueda',
												   {	buttonicon:'ui-icon-calculator',
												   		caption:'',
													   	onClickButton: function () {
																var xurl=$('#table_matriz_busqueda').getGridParam('url').replace('jqgrid_matrizBusqueda','export_excel_jqgrid_matrizBusqueda');
													   			$('#table_matriz_busqueda').excelExport({
																										url:'../rpt/excel.php',
																										exportOptions:{kennedy:'dsafasd'}
																										});
																} 
															});*/
    },
    llamada : function ( ) {
        $("#table_llamada").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_llamada',
            datatype:'local',
            gridview:true,
            ajaxGridOptions : { async : false },
            height:120,
            colNames:['Cartera','Cuenta(Inscripcion)','Telefono','Fecha Llamada','Hora Llamada','EECC','Est. Direcc','Estado','Contacto','Nombre Contacto','Parentesco','Motivo No Pago','Fecha CP','Monto CP','Observacion','Teleoperador'],
            colModel:[
            {name:'cartera',index:'cartera',align:'center',width:120,sortable:false,hidden:false},
            {name:'numero_cuenta',index:'numero_cuenta',align:'center',width:90},
            {name:'telefono',index:'telefono',align:'center',width:65,sortable:false},
            {name:'lla.fecha',index:'lla.fecha',align:'center',width:85},
            {name:'lla.fecha',index:'lla.fecha',align:'center',width:75},
            {name:'lla.status_cuenta',index:'lla.status_cuenta',align:'center',width:60,hidden:true},
            {name:'status_dir',index:'status_dir',align:'center',width:80,sortable:false,hidden:true},
            {name:'estado',index:'estado',align:'center',width:170,editable:true,edittype:'select',sortable:false},
            {name:'contacto',index:'contacto',align:'center',width:150,sortable:false,hidden:true},
            {name:'lla.nombre_contacto',index:'lla.nombre_contacto',align:'center',width:120,sortable:false,hidden:true},
            {name:'parentesco',index:'parentesco',align:'center',width:100,sortable:false,hidden:true},
            {name:'motivo_no_pago',index:'motivo_no_pago',align:'center',width:80,sortable:false,hidden:true},
            {name:'fecha_cp',index:'lla.fecha_cp',editable:true,align:'center',width:70},
            {name:'monto_cp',index:'lla.monto_cp',editable:true,align:'center',width:60},
            {name:'observacion',index:'lla.observacion',editable:true,align:'center',width:240,sortable:false},
            {name:'lla.idusuario_servicio',index:'lla.idusuario_servicio',align:'center',width:160,sortable:false}
            ],
            ondblClickRow : function ( rowid, irow, icol,e ) {
                
                var Est_grd_l = new Array();
                for( i=0;i<AtencionClienteDAO.EstadosLlamada.length;i++ ) {
                    var data = (AtencionClienteDAO.EstadosLlamada[i].data).split('|');
                    for( j=0;j<data.length;j++ ) {
                        var final = data[j].split('@#');
                        Est_grd_l.push( '"'+final[0]+'":"'+final[1]+'"' );
                    }
                }
                var Pr_Est_grd_l = $.parseJSON( '{'+Est_grd_l.join(",")+'}' );
                $('#table_llamada').jqGrid('setColProp','estado',{editoptions:{ value : Pr_Est_grd_l }});
                $('#table_llamada').jqGrid('editRow',rowid,true,
                        function ( ) {
                            $('#'+rowid+'_fecha_cp').datepicker({
                                dateFormat:'yy-mm-dd',
                                dayNamesMin:['D','L','M','M','J','V','S'],
                                monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']
                            });                            
                            var observacion_data=$('#'+rowid+'_observacion').val();
                            observacion_data=observacion_data.replace('<pre style="white-space:normal;word-wrap: break-word;">','');
                            observacion_data=observacion_data.replace('</pre>','');                            
                            $('#'+rowid+'_observacion').val(observacion_data);
                        },
                        function ( response ) 
                        {
                                var json_d = $.parseJSON(response.responseText);
                                if( json_d.rst  ){
                                    $('#table_llamada').jqGrid().trigger('reloadGrid');
                                }else{
                                    
                                }
                                _displayBeforeSendDl(json_d.msg,450);
                                
                        },
                        '../controller/ControllerCobrast.php',
                        {command:'atencion_cliente',action:'ActualizarEstado',usuario_modificacion:$('#hdCodUsuario').val(),cartera:$('#IdCartera').val(),cliente_cartera:$('#IdClienteCartera').val()}
                );
            },
            rowNum:10,
            rowList:[10,15,20],
            rownumbers:true,
            toolbar: [true,"top"],
            pager:'#pager_table_llamada',
            sortname:'lla.fecha ',
            sortorder:'desc',
            loadui: "block"
        });
        $("#table_llamada").jqGrid('navGrid','#pager_table_llamada',{
            edit:false,
            add:false,
            del:false,
            view:true,
            search:false
        });
    /*$('#t_table_llamada').addClass('ui-corner-top');
				$('#t_table_llamada').attr('align','left');
				$('#t_table_llamada').css('height','0px');*/
    /*var html="";
					html+="<table>";
						html+="<tr>";
							html+="<td><div id='toolbar_llamada_icon_edit' class='ui-pg-button ui-corner-all' style='width:19px;margin:0 2px;' onclick='getParamEditLlamadaAtencionCliente()' ><div class='ui-pg-div'><span class='ui-icon ui-icon-pencil'></span></div></div></td>";
						html+="</tr>";
					html+="</table>";
				$('#t_table_llamada').append(html);
				$('#toolbar_llamada_icon_edit').hover(function(){$('#toolbar_llamada_icon_edit').addClass('ui-state-hover');},function(){$('#toolbar_llamada_icon_edit').removeClass('ui-state-hover');});*/
    },
    historico : function ( ) {
        $("#table_historico").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_historico',
            datatype:'local',
            gridview:true,
            height:150,
            colNames:['Cuenta(Inscripcion)','Telefono','Fecha LLamada','Hora Llamada','Estado','Fecha CP','Monto CP','Observacion','Teleoperador'],
            colModel:[
            {
                name:'numero_cuenta',
                index:'numero_cuenta',
                align:'center',
                width:90
            },
{
                name:'telefono',
                index:'telefono',
                align:'center',
                width:70
            },
{
                name:'lla.fecha',
                index:'lla.fecha',
                align:'center',
                width:80
            },
{
                name:'lla.fecha',
                index:'lla.fecha',
                align:'center',
                width:70
            },
{
                name:'estado',
                index:'estado',
                align:'center',
                width:130
            },
{
                name:'lla.fecha_cp',
                index:'lla.fecha_cp',
                align:'center',
                width:90
            },
{
                name:'lla.monto_cp',
                index:'lla.monto_cp',
                align:'center',
                width:80
            },
{
                name:'lla.observacion',
                index:'lla.observacion',
                align:'center',
                width:160
            },
{
                name:'lla.idusuario_servicio',
                index:'lla.id_usuario_servicio',
                align:'center',
                width:160
            }
            ],
            rowNum:10,
            rowList:[10,15,20],
            rownumbers:true,
            pager:'#pager_table_historico', 
            sortname:'lla.fecha_creacion',
            sortorder:'desc',
            loadui: "block"
        });
        $("#table_historico").jqGrid('navGrid','#pager_table_historico',{
            edit:false,
            add:false,
            del:false,
            view:true
        });
    },
    direcciones : function ( ) {
        $("#table_direccion").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_direcciones',
            datatype:'local',
            gridview:true,
            height:100,
            colNames:['Status','Cuenta','Direccion','Referencia','Tipo Referencia','Origen','Ubigeo','Distrito','Provincia','Departamento','Codigo Postal','Observacion'],
            colModel:[
                {name:'dir.status',index:'dir.status', align:'center', width:80},
                {name:'dir.idcuenta',index:'dir.idcuenta', align:'center', width:80,hidden:true},
                {name:'dir.direccion',index:'dir.direccion', align:'center', width:300},
                {name:'dir.referencia',index:'dir.referencia',align:'center',width:80},
                {name:'tipref.nombre',index:'tipref.nombre',align:'center',width:110},
                {name:'org.nombre',index:'org.nombre',align:'center',width:80},
                {name:'dir.ubigeo',index:'dir.ubigeo',align:'center',width:80},
                {name:'dir.distrito',index:'dir.distrito',align:'center',width:200},
                {name:'dir.provincia',index:'dir.provincia',align:'center',width:80},
                {name:'dir.departamento',index:'dir.departamento',align:'center',width:80},
                {name:'dir.codigo_postal',index:'dir.codigo_postal',align:'center',width:80},
                {name:'dir.observacion',index:'dir.observacion',align:'center',width:100}
            ],
            rowNum:10,
            rowList:[10,15,20],
            rownumbers:true,
            toolbar: [true,"top"],
            ondblClickRow : function ( rowid, irow, icol, e ) {
                
                $('#table_direccion').jqGrid('editRow',rowid,true,
                        function ( ) {},
                        function ( response ) 
                        {
                                var json_d = $.parseJSON(response.responseText);
                                if( json_d.rst  ){
                                    $('#table_direccion').jqGrid().trigger('reloadGrid');
                                }else{

                                }
                                _displayBeforeSendDl(json_d.msg,450);

                        },
                        '../controller/ControllerCobrast.php',
                        {command:'atencion_cliente',action:'ActualizarGRDDireccion', iddireccion : rowid, usuario_modificacion:$('#hdCodUsuario').val()}
                );
                
                
            },
            pager:'#pager_table_direccion',
            sortname:'dir.direccion',
            sortorder:'desc',
            loadui: "block"
        });
        $("#table_direccion").jqGrid('navGrid','#pager_table_direccion',
                {
                edit:true,
                edittitle:'MARCAR DIRECCION CORRECTA',
                editicon:'ui-icon-star',
                editfunc : function ( xid ) 
                        {
                                
                                
                                $.ajax({
                                        url : '../controller/ControllerCobrast.php',
                                        type : 'POST',
                                        dataType:'json',
                                        data : { 
                                                command : 'atencion_cliente', 
                                                action : 'ActualizarStatusDireccion', 
                                                usuario_modificacion : $('#hdCodUsuario').val() , 
                                                id : xid, 
                                                dir_status : 'CORRECTA' 
                                                },
                                        success : function ( obj ) {

                                                        if( obj.rst ) {
                                                                var xidcuenta=$("#table_direccion").jqGrid("getRowData",xid)['dir.idcuenta'];
                                                                $.data( document.body , xid, { cuenta : xidcuenta, est : 'CORRECTA' } );
                                                                
                                                                $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
                                                                AtencionClienteDAO.setTimeOut_hide_message();
                                                                $('#table_direccion').jqGrid().trigger('reloadGrid');
                                                        }else{
                                                                $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'350px'));
                                                                AtencionClienteDAO.setTimeOut_hide_message();
                                                        }

                                                },
                                        error : function ( ) {
                                                        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError("Error al marca direccion",'350px'));
                                                        AtencionClienteDAO.setTimeOut_hide_message();
                                                }
                                });
                        },
                add:false,
                del:false,
                view:true,
                search:false
                }
        );
        $("#table_direccion").jqGrid('navButtonAdd','#pager_table_direccion',
        	{  
        		caption : "",
        		buttonicon : "ui-icon-circle-close",
        		position : 'first',
        		title : 'MARCAR DIRECCION INCORRECTA',
        		onClickButton : function ( ) {
        			var xid = $('#table_direccion').getGridParam('selrow');
        			if( !xid ) {
        				return false;
        			}
                                
                                $.ajax({
                                        url : '../controller/ControllerCobrast.php',
                                        type : 'POST',
                                        dataType:'json',
                                        data : { 
                                                command : 'atencion_cliente', 
                                                action : 'ActualizarStatusDireccion', 
                                                usuario_modificacion : $('#hdCodUsuario').val() ,
                                                id : xid,
                                                dir_status : 'INCORRECTA'
                                                },
                                        success : function ( obj ) {

                                                        if( obj.rst ) {
                                                                var xidcuenta=$("#table_direccion").jqGrid("getRowData",xid)['dir.idcuenta'];
                                                                $.data( document.body , xid, { cuenta : xidcuenta, est : 'INCORRECTA' } );
                                                                
                                                                $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
                                                                AtencionClienteDAO.setTimeOut_hide_message();
                                                                $('#table_direccion').jqGrid().trigger('reloadGrid');
                                                        }else{
                                                                $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'350px'));
                                                                AtencionClienteDAO.setTimeOut_hide_message();
                                                        }

                                                },
                                        error : function ( ) {
                                                        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError("Error al marca direccion",'350px'));
                                                        AtencionClienteDAO.setTimeOut_hide_message();
                                                }
                                });
        		}
        	}
	    );
	    $("#table_direccion").jqGrid('navButtonAdd','#pager_table_direccion',
        	{
        		caption : '',
        		buttonicon : 'ui-icon-bullet',
        		position : 'first',
        		title : 'MARCAR DIRECCION NO VALIDA',
        		onClickButton : function ( ) {
        			var xid = $('#table_direccion').getGridParam('selrow');
        			if( !xid ) {
        				return false;
        			}
                                
        			$.ajax({
                                        url : '../controller/ControllerCobrast.php',
                                        type : 'POST',
                                        dataType:'json',
                                        data : { 
                                                command : 'atencion_cliente', 
                                                action : 'ActualizarStatusDireccion', 
                                                usuario_modificacion : $('#hdCodUsuario').val() ,
                                                id : xid,
                                                dir_status : 'NO VALIDA'
                                                },
                                        success : function ( obj ) {

                                                        if( obj.rst ) {
                                                                var xidcuenta=$("#table_direccion").jqGrid("getRowData",xid)['dir.idcuenta'];
                                                                $.data( document.body , xid, { cuenta : xidcuenta, est : 'NO VALIDA' } );
                                                                
                                                                $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
                                                                AtencionClienteDAO.setTimeOut_hide_message();
                                                                $('#table_direccion').jqGrid().trigger('reloadGrid');
                                                        }else{
                                                                $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'350px'));
                                                                AtencionClienteDAO.setTimeOut_hide_message();
                                                        }

                                                },
                                        error : function ( ) {
                                                        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError("Error al marca direccion",'350px'));
                                                        AtencionClienteDAO.setTimeOut_hide_message();
                                                }
                                });
        		}
        	}
        	
    	);
	    $("#table_direccion").jqGrid('navButtonAdd','#pager_table_direccion',
        	{
        		caption : '',
        		buttonicon : 'ui-icon-clear',
        		position : 'first',
        		title : 'LIMPIAR MARCACION',
        		onClickButton : function ( ) {
        			var xid = $('#table_direccion').getGridParam('selrow');
        			if( !xid ) {
        				return false;
        			}
                                
                                
        			$.ajax({
                                        url : '../controller/ControllerCobrast.php',
                                        type : 'POST',
                                        dataType:'json',
                                        data : { 
                                                command : 'atencion_cliente', 
                                                action : 'ActualizarStatusDireccion', 
                                                usuario_modificacion : $('#hdCodUsuario').val() ,
                                                id : xid,
                                                dir_status : ''
                                                },
                                        success : function ( obj ) {

                                                        if( obj.rst ) {
                                                                var xidcuenta=$("#table_direccion").jqGrid("getRowData",xid)['dir.idcuenta'];
                                                                $.data( document.body , xid, { cuenta : xidcuenta, est : '' } );
                                                                $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
                                                                AtencionClienteDAO.setTimeOut_hide_message();
                                                                $('#table_direccion').jqGrid().trigger('reloadGrid');
                                                        }else{
                                                                $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'350px'));
                                                                AtencionClienteDAO.setTimeOut_hide_message();
                                                        }

                                                },
                                        error : function ( ) {
                                                        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError("Error al marca direccion",'350px'));
                                                        AtencionClienteDAO.setTimeOut_hide_message();
                                                }
                                });
        		}
        	}
        	
    	);
        $('#t_table_direccion').addClass('ui-corner-top');
        $('#t_table_direccion').attr('align','left');
        $('#t_table_direccion').css('height','25px');
        var html="";
        html+="<table>";
        html+="<tr>";
        html+="<td><div id='toolbar_direccion_icon_edit' class='ui-pg-button ui-corner-all' style='width:19px;margin:0 2px;' onclick='getParamEditDireccionAtencionCliente()' ><div class='ui-pg-div'><span class='ui-icon ui-icon-pencil'></span></div></div></td>";
        html+="<td><div id='toolbar_direccion_icon_add' class='ui-pg-button ui-corner-all' style='width:19px;margin:0 2px;' onclick='display_box_agregar_direccion()' ><div class='ui-pg-div'><span class='ui-icon ui-icon-plus'></span></div></div></td>";
        html+="</tr>";
        html+="</table>";
        $('#t_table_direccion').append(html);
        $('#toolbar_direccion_icon_edit').hover(function(){
            $('#toolbar_direccion_icon_edit').addClass('ui-state-hover');
        },function(){
            $('#toolbar_direccion_icon_edit').removeClass('ui-state-hover');
        });
        $('#toolbar_direccion_icon_add').hover(function(){
            $('#toolbar_direccion_icon_add').addClass('ui-state-hover');
        },function(){
            $('#toolbar_direccion_icon_add').removeClass('ui-state-hover');
        });
    },
    campo_direcciones : function ( ) {
        $("#table_campo_direcciones").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_campo_direcciones',
            datatype:'local',
            gridview:true,
            height:100,
            colNames:['Status','Cuenta','Direccion','Referencia','Tipo Referencia','Origen','Ubigeo','Distrito','Provincia','Departamento','Observacion'],
            colModel:[
                {name:'dir.status',index:'dir.status',align:'center',width:80},
                {name:'dir.idcuenta',index:'dir.idcuenta',align:'center',width:80,hidden:true},
                {name:'dir.direccion',index:'dir.direccion',align:'center',width:120},
                {name:'dir.referencia',index:'dir.referencia',align:'center',width:100},
                {name:'tipref.nombre',index:'tipref.nombre',align:'center',width:100},
                {name:'org.nombre',index:'org.nombre',align:'center',width:80},
                {name:'dir.ubigeo',index:'dir.ubigeo',align:'center',width:80,hidden:true},
                {name:'dir.distrito',index:'dir.distrito',align:'center',width:100},
                {name:'dir.provincia',index:'dir.provincia',align:'center',width:100},
                {name:'dir.departamento',index:'dir.departamento',align:'center',width:100},
                {name:'dir.observacion',index:'dir.observacion',align:'center',width:80}
            ],
            rowNum:10,
            rowList:[10,15,20],
            rownumbers:true,
            onSelectRow: function ( id ) { 
            //getParamCampoDireccion();
            },
            pager:'#pager_table_campo_direcciones',
            sortname:'dir.direccion',
            toolbar: [true,"top"],
            sortorder:'desc',
            loadui: "block"
        });
        $("#table_campo_direcciones").jqGrid('navGrid','#pager_table_campo_direcciones',
                {
                edit:true,
                editicon:'ui-icon-check',
                edittitle : 'MARCAR DIRECCION CORRECTA',
                editfunc : function ( xid ) 
                        {
                                $.ajax({
                                        url : '../controller/ControllerCobrast.php',
                                        type : 'POST',
                                        dataType:'json',
                                        data : { 
                                                command : 'atencion_cliente', 
                                                action : 'ActualizarStatusDireccion', 
                                                usuario_modificacion : $('#hdCodUsuario').val() ,
                                                id : xid,
                                                dir_status : 'CORRECTA'
                                                },
                                        success : function ( obj ) {

                                                        if( obj.rst ) {
                                                                
                                                                var xdt = $('textarea');
                                                                var xidcuenta=$("#table_campo_direcciones").jqGrid("getRowData",xid)['dir.idcuenta'];
                                                                $.data( xdt[1] , xid, { cuenta : xidcuenta, est : 'CORRECTA' } );
                                                                
                                                                $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
                                                                AtencionClienteDAO.setTimeOut_hide_message();
                                                                $('#table_campo_direcciones').jqGrid().trigger('reloadGrid');
                                                        }else{
                                                                $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'350px'));
                                                                AtencionClienteDAO.setTimeOut_hide_message();
                                                        }

                                                },
                                        error : function ( ) {
                                                        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError("Error al marca direccion",'350px'));
                                                        AtencionClienteDAO.setTimeOut_hide_message();
                                                }
                                });
                        },
                add:false,
                del:false,
                view:true
                }
        );
        $("#table_campo_direcciones").jqGrid('navButtonAdd','#pager_table_campo_direcciones',
                {
                        caption : '',
                        buttonicon : 'ui-icon-bullet',
                        position : 'first',
                        title : 'MARCAR DIRECCION NO VALIDA',
                        onClickButton : function ( ) {
                                var xid = $('#table_campo_direcciones').getGridParam('selrow');
                                if( !xid ) {
                                        return false;
                                }
                                $.ajax({
                                        url : '../controller/ControllerCobrast.php',
                                        type : 'POST',
                                        dataType:'json',
                                        data : { 
                                                command : 'atencion_cliente', 
                                                action : 'ActualizarStatusDireccion', 
                                                usuario_modificacion : $('#hdCodUsuario').val() ,
                                                id : xid,
                                                dir_status : 'NO VALIDA'
                                                },
                                        success : function ( obj ) {

                                                        if( obj.rst ) {
                                                        
                                                                var xdt = $('textarea');
                                                                var xidcuenta=$("#table_campo_direcciones").jqGrid("getRowData",xid)['dir.idcuenta'];
                                                                $.data( xdt[1] , xid, { cuenta : xidcuenta, est : 'NO VALIDA' } );
                                                        
                                                                $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
                                                                AtencionClienteDAO.setTimeOut_hide_message();
                                                                $('#table_campo_direcciones').jqGrid().trigger('reloadGrid');
                                                        }else{
                                                                $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'350px'));
                                                                AtencionClienteDAO.setTimeOut_hide_message();
                                                        }

                                                },
                                        error : function ( ) {
                                                        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError("Error al marca direccion",'350px'));
                                                        AtencionClienteDAO.setTimeOut_hide_message();
                                                }
                                });
                        }
                }

        );
        
        $("#table_campo_direcciones").jqGrid('navButtonAdd','#pager_table_campo_direcciones',
        	{  
        		caption : "",
        		buttonicon : "ui-icon-circle-close",
        		position : 'first',
        		title : 'MARCAR DIRECCION INCORRECTA',
        		onClickButton : function ( ) {
        			var xid = $('#table_campo_direcciones').getGridParam('selrow');
        			if( !xid ) {
        				return false;
        			}
        			$.ajax({
                                        url : '../controller/ControllerCobrast.php',
                                        type : 'POST',
                                        dataType:'json',
                                        data : { 
                                                command : 'atencion_cliente', 
                                                action : 'ActualizarStatusDireccion', 
                                                usuario_modificacion : $('#hdCodUsuario').val() ,
                                                id : xid,
                                                dir_status : 'INCORRECTA'
                                                },
                                        success : function ( obj ) {

                                                        if( obj.rst ) {
                                                        
                                                                var xdt = $('textarea');
                                                                var xidcuenta=$("#table_campo_direcciones").jqGrid("getRowData",xid)['dir.idcuenta'];
                                                                $.data( xdt[1] , xid, { cuenta : xidcuenta, est : 'INCORRECTA' } );
                                                        
                                                                $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
                                                                AtencionClienteDAO.setTimeOut_hide_message();
                                                                $('#table_campo_direcciones').jqGrid().trigger('reloadGrid');
                                                        }else{
                                                                $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'350px'));
                                                                AtencionClienteDAO.setTimeOut_hide_message();
                                                        }

                                                },
                                        error : function ( ) {
                                                        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError("Error al marca direccion",'350px'));
                                                        AtencionClienteDAO.setTimeOut_hide_message();
                                                }
                                });
        		}
        	}
	    );
	    $("#table_campo_direcciones").jqGrid('navButtonAdd','#pager_table_campo_direcciones',
        	{
        		caption : '',
        		buttonicon : 'ui-icon-clear',
        		position : 'first',
        		title : 'LIMPIAR MARCACION',
        		onClickButton : function ( ) {
        			var xid = $('#table_campo_direcciones').getGridParam('selrow');
        			if( !xid ) {
        				return false;
        			}
        			$.ajax({
                                        url : '../controller/ControllerCobrast.php',
                                        type : 'POST',
                                        dataType:'json',
                                        data : { 
                                                command : 'atencion_cliente', 
                                                action : 'ActualizarStatusDireccion', 
                                                usuario_modificacion : $('#hdCodUsuario').val() ,
                                                id : xid,
                                                dir_status : ''
                                                },
                                        success : function ( obj ) {

                                                        if( obj.rst ) {
                                                        
                                                                var xdt = $('textarea');
                                                                var xidcuenta=$("#table_campo_direcciones").jqGrid("getRowData",xid)['dir.idcuenta'];
                                                                $.data( xdt[1] , xid, { cuenta : xidcuenta, est : '' } );
                                                        
                                                                $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
                                                                AtencionClienteDAO.setTimeOut_hide_message();
                                                                $('#table_campo_direcciones').jqGrid().trigger('reloadGrid');
                                                        }else{
                                                                $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'350px'));
                                                                AtencionClienteDAO.setTimeOut_hide_message();
                                                        }

                                                },
                                        error : function ( ) {
                                                        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError("Error al marca direccion",'350px'));
                                                        AtencionClienteDAO.setTimeOut_hide_message();
                                                }
                                });
        		}
        	}
        	
    	);
        
        
        $('#t_table_campo_direcciones').addClass('ui-corner-top');
        $('#t_table_campo_direcciones').attr('align','left');
        $('#t_table_campo_direcciones').css('height','25px');
        var html="";
        html+="<table>";
        html+="<tr>";
        html+="<td><div id='toolbar_campo_direcciones_icon_edit' class='ui-pg-button ui-corner-all' style='width:19px;margin:0 2px;' onclick='getParamEditDireccionCampo()' ><div class='ui-pg-div'><span class='ui-icon ui-icon-pencil'></span></div></div></td>";
        html+="</tr>";
        html+="</table>";
        $('#t_table_campo_direcciones').append(html);
        $('#toolbar_campo_direcciones_icon_edit').hover(function(){
            $('#toolbar_campo_direcciones_icon_edit').addClass('ui-state-hover');
        },function(){
            $('#toolbar_campo_direcciones_icon_edit').removeClass('ui-state-hover');
        });
    },
    centro_pago : function ( ) {
        $("#table_centro_pago").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_centro_pago&Servicio='+$('#hdCodServicio').val(),
            datatype:this.type,
            height:200,
            colNames:['Agencia','Tipo Canal','Direccion','Zona','Horario','Departamento','Provincia','Distrito'],
            colModel:[
            {
                name:'agencia',
                index:'agencia',
                align:'center',
                width:90
            },
{
                name:'tipo_canal',
                index:'tipo_canal',
                align:'center',
                width:100
            },
{
                name:'direccion',
                index:'direccion',
                align:'center',
                width:180
            },
{
                name:'zona',
                index:'zona',
                align:'center',
                width:70
            },
{
                name:'horario',
                index:'horario',
                align:'center',
                width:70
            },
{
                name:'departamento',
                index:'departamento',
                align:'center',
                width:90
            },
{
                name:'provincia',
                index:'provincia',
                align:'center',
                width:90
            },
{
                name:'distrito',
                index:'distrito',
                align:'center',
                width:90
            }
            ],
            rowNum:10,
            rowList:[10,15,20],
            rownumbers:true,
            pager:'#pager_table_centro_pago',
            sortname:'agencia',
            sortorder:'desc',
            loadui: "block"
        });
        $("#table_centro_pago").jqGrid('filterToolbar'); 
        $("#table_centro_pago").jqGrid('navGrid','#pager_table_centro_pago',{
            edit:false,
            add:false,
            del:false,
            view:true
        });
    },
    cuenta : function ( ) {
        $("#table_cuenta").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_cuenta',
            datatype:this.type,
            gridview:true,
            height:100,
            colNames:['Numero de Cuenta','Moneda','Total Deuda','Monto Pagado','Total Comision','Saldo','Total Interes','Total Descuento','Telefono'],
            colModel:[
            {
                name:'numero_cuenta',
                index:'numero_cuenta',
                align:'center',
                width:120
            },
{
                name:'moneda',
                index:'moneda',
                align:'center',
                width:80
            },
{
                name:'total_deuda',
                index:'total_deuda',
                align:'center',
                width:90
            },
{
                name:'monto_pagado',
                index:'monto_pagado',
                align:'center',
                width:90
            },
{
                name:'total_comision',
                index:'total_comision',
                align:'center',
                width:90
            },
{
                name:'saldo',
                index:'saldo',
                align:'center',
                width:80
            },
{
                name:'total_interes',
                index:'total_deuda',
                align:'center',
                width:90
            },
{
                name:'total_descuento',
                index:'total_deuda',
                align:'center',
                width:90
            },
{
                name:'telefono',
                index:'telefono',
                align:'center',
                width:90
            }
            ],
            rowNum:10,
            rowList:[10,15,20],
            rownumbers:true,
            pager:'#pager_table_cuenta',
            sortname:'numero_cuenta',
            onSelectRow: function ( id ) {
                reloadJQGRID_operacion(id);
                loadDataAditionalCuenta();
            },
            sortorder:'desc',
            toolbar: [true,"top"],
            loadui: "block"
        });
        $("#table_cuenta").jqGrid('navGrid','#pager_table_cuenta',{
            edit:false,
            add:false,
            del:false,
            view:true
        });
        $('#t_table_cuenta').addClass('ui-corner-top');
        $("#t_table_cuenta").append("<div id='toolbar_datos_adicionales_cuenta' class='ui-pg-button ui-corner-all' style='width:19px;margin:0 2px;' onclick='loadDataAditionalCuenta()' ><div class='ui-pg-div'><span class='ui-icon ui-icon-document'></span></div></div>");
    },
    operaciones : function ( ) {
        $("#table_operaciones").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_operaciones',
            datatype:this.type,
            gridview:true,
            autowidth:true,
            height:100,
            colNames:['Codigo','Moneda','Refinanciamiento','N&ordm; Cuotas','N&ordm; Cuotas Pagadas','Dias Mora','Tramo','Comision','Total Deuda','Total Deuda Soles','Total Deuda Dolares','Monto Mora','Monto Mora Soles','Monto Mora Dolares','Saldo Capital','Saldo Capital Soles','Saldo Capital Dolares','Fecha Asignacion'],
            colModel:[
            {
                name:'codigo_operacion',
                index:'codigo_operacion',
                align:'center',
                width:70
            },
{
                name:'moneda',
                index:'moneda',
                align:'center',
                width:70
            },
{
                name:'refinanciamiento',
                index:'refinanciamiento',
                align:'center',
                width:70
            },
{
                name:'numero_cuotas',
                index:'numero_cuotas',
                align:'center',
                width:70
            },
{
                name:'numero_cuotas_pagadas',
                index:'numero_cuotas_pagadas',
                align:'center',
                width:70
            },
{
                name:'dias_mora',
                index:'dias_mora',
                align:'center',
                width:70
            },
{
                name:'tramo',
                index:'tramo',
                align:'center',
                width:70
            },
{
                name:'comision',
                index:'comision',
                align:'center',
                width:70
            },
{
                name:'total_deuda',
                index:'total_deuda',
                align:'center',
                width:70
            },
{
                name:'total_deuda_soles',
                index:'total_deuda_soles',
                align:'center',
                width:70
            },
{
                name:'total_deuda_dolares',
                index:'total_deuda_dolares',
                align:'center',
                width:70
            },
{
                name:'monto_mora',
                index:'monto_mora',
                align:'center',
                width:70
            },
{
                name:'monto_mora_soles',
                index:'monto_mora_soles',
                align:'center',
                width:70
            },
{
                name:'monto_mora_dolares',
                index:'monto_mora_dolares',
                align:'center',
                width:70
            },
{
                name:'saldo_capital',
                index:'saldo_capital',
                align:'center',
                width:70
            },
{
                name:'saldo_capital_soles',
                index:'saldo_capital_soles',
                align:'center',
                width:100
            },
{
                name:'saldo_capital_dolares',
                index:'saldo_capital_dolares',
                align:'center',
                width:70
            },
{
                name:'fecha_asignacion',
                index:'fecha_asignacion',
                align:'center',
                width:70
            }
            ],
            rowNum:10,
            rowList:[10,15,20],
            rownumbers:true,
            pager:'#pager_table_operaciones',
            sortname:'codigo_operacion',
            onSelectRow : function ( id ) {
                reloadJQGRID_pagos(id);
                loadDataAditionalOperation();
            },
            sortorder:'desc',
            toolbar: [true,"top"],
            loadui: "block"
        });
        $("#table_operaciones").jqGrid('navGrid','#pager_table_operaciones',{
            edit:false,
            add:false,
            del:false,
            view:true
        });
        $('#t_table_operaciones').addClass('ui-corner-top');
        $("#t_table_operaciones").append("<div id='toolbar_datos_adicionales_cuenta' class='ui-pg-button ui-corner-all' style='width:19px;margin:0 2px;' onclick='loadDataAditionalOperation()' ><div class='ui-pg-div'><span class='ui-icon ui-icon-document'></span></div></div>");
    },
    pagos : function ( ) {
        $("#table_pagos").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_pagos',
            datatype:this.type,
            gridview:true,
            height:100,
            colNames:['Monto Pagado','Total Deuda','Monto Mora','Saldo Capital','Dias Mora','Moneda','Fecha Pago','Fecha Envio','Agencia'],
            colModel:[
            {
                name:'monto_pagado',
                index:'monto_pagado',
                align:'center',
                width:80
            },
{
                name:'total_deuda',
                index:'total_deuda',
                align:'center',
                width:80
            },
{
                name:'monto_mora',
                index:'monto_mora',
                align:'center',
                width:80
            },
{
                name:'saldo_capital',
                index:'saldo_capital',
                align:'center',
                width:80
            },
{
                name:'dias_mora',
                index:'dias_mora',
                align:'center',
                width:80
            },
{
                name:'moneda',
                index:'moneda',
                align:'center',
                width:80
            },
{
                name:'fecha',
                index:'fecha',
                align:'center',
                width:80
            },
{
                name:'fecha_envio',
                index:'fecha_envio',
                align:'center',
                width:80
            },
{
                name:'agencia',
                index:'agencia',
                align:'center',
                width:80
            }
            ],
            rowNum:10,
            rowList:[10,15,20],
            rownumbers:true,
            pager:'#pager_table_pagos',
            sortname:'fecha',
            sortorder:'desc',
            loadui: "block"
        });
        $("#table_pagos").jqGrid('navGrid','#pager_table_pagos',{
            edit:false,
            add:false,
            del:false,
            view:true
        });
    },
    telefonos : function ( ) {
        $("#table_telefonos").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_telefonos',
            datatype:'local',
            gridview:true,
            height:140,
            colNames:['Status','Numero','Codigo Cliente','Cuenta','Cliente Cartera','Anexo','Tipo','Referencia','Origen','Observacion'],
            colModel:[
                { name:'t1.status', index:'t1.status', align:'center', width:80 },
                { name:'t1.numero', index:'t1.numero', align:'center', width:100 },
                { name:'t1.codigo_cliente', index:'t1.codigo_cliente', align:'center', width:50, hidden:true },
                { name:'t1.idcuenta', index:'t1.idcuenta', align:'center', width:50, hidden:true },
                { name:'t1.idcliente_cartera', index:'t1.idcliente_cartera', align:'center', width:50, hidden:true },
                { name:'t1.anexo', index:'t1.anexo', align:'center', width:100 },
                { name:'t1.tipo_telefono', index:'t1.tipo_telefono', align:'center', width:100 },
                { name:'t1.tipo_referencia', index:'t1.tipo_referencia', align:'center', width:100 },
                { name:'t1.origen', index:'t1.origen', align:'center', width:100 },
                { name:'t1.observacion', index:'t1.observacion', align:'center',width:200 }
                ],
            rowNum:10,
            rowList:[10,15,20],
            rownumbers:true,
            pager:'#pager_table_telefonos',
            sortname:'t1.numero',
            toolbar: [true,"top"],
            onSelectRow: function ( id ) { 
            //getParamTelefono();
            },
            sortorder:'desc',
            loadui: "block"
        });
        $("#table_telefonos").jqGrid('navGrid','#pager_table_telefonos',{ edit:false, add:false, del:false, view:true });
         $("#table_telefonos").jqGrid('navButtonAdd','#pager_table_telefonos',
                {  
                        caption : "",
                        buttonicon : "ui-icon-circle-close",
                        position : 'first',
                        title : 'MARCAR TELEFONO INCORRECTA',
                        onClickButton : function ( ) {
                                var xid = $('#table_telefonos').getGridParam('selrow');
                                if( !xid ) {
                                        return false;
                                }
                                var xnumero=$("#table_telefonos").jqGrid("getRowData",xid)['t1.numero'];
                                var xidcuenta=$("#table_telefonos").jqGrid("getRowData",xid)['t1.idcuenta'];
                                var xidcliente_cartera=$("#table_telefonos").jqGrid("getRowData",xid)['t1.idcliente_cartera'];
                                var xcodigo_cliente = $('#CodigoClienteMain').val();
                                if( !xid ) {
                                        return false;
                                }
                                
                                $.ajax({
                                        url : '../controller/ControllerCobrast.php',
                                        type : 'POST',
                                        dataType:'json',
                                        data : { 
                                                command : 'atencion_cliente', 
                                                action : 'ActualizarStatusTelefono', 
                                                usuario_modificacion : $('#hdCodUsuario').val() ,
                                                id : xid,
                                                codigo_cliente : xcodigo_cliente,
                                                numero : xnumero,
                                                status : 'INCORRECTA'
                                                },
                                        success : function ( obj ) {

                                                        if( obj.rst ) {
                                                                var xdt = $('textarea');
                                                                
                                                                $.data( xdt[0], xid, { cuenta : xidcuenta, est : 'INCORRECTA', numero : xnumero, idcliente_cartera : xidcliente_cartera, codigo_cliente : xcodigo_cliente } );
                                                                
                                                                $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
                                                                AtencionClienteDAO.setTimeOut_hide_message();
                                                                $('#table_telefonos').jqGrid().trigger('reloadGrid');
                                                        }else{
                                                                $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'350px'));
                                                                AtencionClienteDAO.setTimeOut_hide_message();
                                                        }

                                                },
                                        error : function ( ) {
                                                        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError("Error al marca direccion",'350px'));
                                                        AtencionClienteDAO.setTimeOut_hide_message();
                                                }
                                });
                        }
                }
            );
        $("#table_telefonos").jqGrid('navButtonAdd','#pager_table_telefonos',
                {  
                        caption : "",
                        buttonicon : "ui-icon-star",
                        position : 'first',
                        title : 'MARCAR TELEFONO CORRECTO',
                        onClickButton : function ( ) {
                                var xid = $('#table_telefonos').getGridParam('selrow');
                                
                                if( !xid ) {
                                        return false;
                                }
                                
                                var xidcuenta=$("#table_telefonos").jqGrid("getRowData",xid)['t1.idcuenta'];
                                var xnumero=$("#table_telefonos").jqGrid("getRowData",xid)['t1.numero'];
                                var xidcliente_cartera=$("#table_telefonos").jqGrid("getRowData",xid)['t1.idcliente_cartera'];
                                var xcodigo_cliente = $('#CodigoClienteMain').val();

                                $.ajax({
                                        url : '../controller/ControllerCobrast.php',
                                        type : 'POST',
                                        dataType:'json',
                                        data : { 
                                                command : 'atencion_cliente', 
                                                action : 'ActualizarStatusTelefono', 
                                                usuario_modificacion : $('#hdCodUsuario').val() ,
                                                id : xid,
                                                codigo_cliente : xcodigo_cliente,
                                                numero : xnumero,
                                                status : 'CORRECTO'
                                                },
                                        success : function ( obj ) {

                                                        if( obj.rst ) {
                                                                
                                                                var xdt = $('textarea');

                                                                $.data( xdt[0], xid, { cuenta : xidcuenta, est : 'CORRECTO', numero : xnumero, idcliente_cartera : xidcliente_cartera, codigo_cliente : xcodigo_cliente } );
                                                                
                                                                $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
                                                                AtencionClienteDAO.setTimeOut_hide_message();
                                                                $('#table_telefonos').jqGrid().trigger('reloadGrid');
                                                        }else{
                                                                $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'350px'));
                                                                AtencionClienteDAO.setTimeOut_hide_message();
                                                        }

                                                },
                                        error : function ( ) {
                                                        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError("Error al marca direccion",'350px'));
                                                        AtencionClienteDAO.setTimeOut_hide_message();
                                                }
                                });
                        }
                }
            );
        $("#table_telefonos").jqGrid('navButtonAdd','#pager_table_telefonos',
                {  
                        caption : "",
                        buttonicon : "ui-icon-bullet",
                        position : 'first',
                        title : 'MARCAR TELEFONO NO VALIDA',
                        onClickButton : function ( ) {
                                var xid = $('#table_telefonos').getGridParam('selrow');
                                
                                if( !xid ) {
                                        return false;
                                }
                                
                                var xidcuenta=$("#table_telefonos").jqGrid("getRowData",xid)['t1.idcuenta'];
                                var xnumero=$("#table_telefonos").jqGrid("getRowData",xid)['t1.numero'];
                                var xidcliente_cartera=$("#table_telefonos").jqGrid("getRowData",xid)['t1.idcliente_cartera'];
                                var xcodigo_cliente = $('#CodigoClienteMain').val();

                                $.ajax({
                                        url : '../controller/ControllerCobrast.php',
                                        type : 'POST',
                                        dataType:'json',
                                        data : { 
                                                command : 'atencion_cliente', 
                                                action : 'ActualizarStatusTelefono', 
                                                usuario_modificacion : $('#hdCodUsuario').val() ,
                                                id : xid,
                                                codigo_cliente : xcodigo_cliente,
                                                numero : xnumero,
                                                status : 'NO VALIDA'
                                                },
                                        success : function ( obj ) {

                                                        if( obj.rst ) {
                                                        
                                                                var xdt = $('textarea');

                                                                $.data( xdt[0], xid, { cuenta : xidcuenta, est : 'NO VALIDA', numero : xnumero, idcliente_cartera : xidcliente_cartera, codigo_cliente : xcodigo_cliente } );
                                                        
                                                                $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
                                                                AtencionClienteDAO.setTimeOut_hide_message();
                                                                $('#table_telefonos').jqGrid().trigger('reloadGrid');
                                                        }else{
                                                                $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'350px'));
                                                                AtencionClienteDAO.setTimeOut_hide_message();
                                                        }

                                                },
                                        error : function ( ) {
                                                        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError("Error al marca direccion",'350px'));
                                                        AtencionClienteDAO.setTimeOut_hide_message();
                                                }
                                });
                        }
                }
            );
        $("#table_telefonos").jqGrid('navButtonAdd','#pager_table_telefonos',
                {  
                        caption : "",
                        buttonicon : "ui-icon-clear",
                        position : 'first',
                        title : 'LIMPIAR MARCACION',
                        onClickButton : function ( ) {
                                var xid = $('#table_telefonos').getGridParam('selrow');
                                
                                if( !xid ) {
                                        return false;
                                }
                                
                                var xidcuenta=$("#table_telefonos").jqGrid("getRowData",xid)['t1.idcuenta'];
                                var xnumero=$("#table_telefonos").jqGrid("getRowData",xid)['t1.numero'];
                                var xidcliente_cartera=$("#table_telefonos").jqGrid("getRowData",xid)['t1.idcliente_cartera'];
                                var xcodigo_cliente = $('#CodigoClienteMain').val();

                                $.ajax({
                                        url : '../controller/ControllerCobrast.php',
                                        type : 'POST',
                                        dataType:'json',
                                        data : { 
                                                command : 'atencion_cliente', 
                                                action : 'ActualizarStatusTelefono', 
                                                usuario_modificacion : $('#hdCodUsuario').val() ,
                                                id : xid,
                                                codigo_cliente : xcodigo_cliente,
                                                numero : xnumero,
                                                status : ''
                                                },
                                        success : function ( obj ) {

                                                        if( obj.rst ) {
                                                        
                                                                var xdt = $('textarea');

                                                                $.data( xdt[0], xid, { cuenta : xidcuenta, est : '', numero : xnumero, idcliente_cartera : xidcliente_cartera, codigo_cliente : xcodigo_cliente } );
                                                        
                                                                $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo(obj.msg,'350px'));
                                                                AtencionClienteDAO.setTimeOut_hide_message();
                                                                $('#table_telefonos').jqGrid().trigger('reloadGrid');
                                                        }else{
                                                                $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError(obj.msg,'350px'));
                                                                AtencionClienteDAO.setTimeOut_hide_message();
                                                        }

                                                },
                                        error : function ( ) {
                                                        $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgError("Error al marca direccion",'350px'));
                                                        AtencionClienteDAO.setTimeOut_hide_message();
                                                }
                                });
                        }
                }
            );
        $('#t_table_telefonos').addClass('ui-corner-top');
        $('#t_table_telefonos').attr('align','left');
        $('#t_table_telefonos').css('height','25px');
        var html="";
        html+="<table>";
        html+="<tr>";
        html+="<td><div id='toolbar_telefonos_icon_edit' class='ui-pg-button ui-corner-all' style='width:19px;margin:0 2px;' onclick='getParamEditTelefonos()' alt='Editar' title='Editar' ><div class='ui-pg-div'><span class='ui-icon ui-icon-pencil'></span></div></div></td>";
        //html+="<td><div id='toolbar_telefonos_icon_add' class='ui-pg-button ui-corner-all' style='width:19px;margin:0 2px;' onclick='DisplayDialogAddTelefonoCartera()' alt='Nuevo' title='Nuevo' ><div class='ui-pg-div'><span class='ui-icon ui-icon-plus'></span></div></div></td>";
        html+="<td><div id='toolbar_telefonos_icon_delete' class='ui-pg-button ui-corner-all' style='width:19px;margin:0 2px;' onclick='delete_telefono_atencion_cliente()' alt='Eliminar' title='Eliminar' ><div class='ui-pg-div'><span class='ui-icon ui-icon-trash'></span></div></div></td>";
        html+="</tr>";
        html+="</table>";
        $('#t_table_telefonos').append(html);
        $('#toolbar_telefonos_icon_edit').hover(function(){
            $(this).addClass('ui-state-hover');
        },function(){
            $(this).removeClass('ui-state-hover');
        });
        $('#toolbar_telefonos_icon_add').hover(function(){
            $(this).addClass('ui-state-hover');
        },function(){
            $(this).removeClass('ui-state-hover');
        });
        $('#toolbar_telefonos_icon_delete').hover(function(){
            $(this).addClass('ui-state-hover');
        },function(){
            $(this).removeClass('ui-state-hover');
        });
		
    },
    campo_telefonos : function ( ) {
        $("#table_campo_telefono").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_campo_telefonos',
            datatype:'local',
            gridview:true,
            height:100,
            colNames:['Status','Numero','Anexo','Tipo','Referencia','Origen','Observacion'],
            colModel:[
                { name:'tel.status', index:'tel.status', align:'center', width:80 },
                { name:'tel.numero', index:'tel.numero', align:'center', width:100 },
                { name:'tel.anexo', index:'tel.anexo', align:'center', width:100 },
                { name:'tipo_telefono', index:'tipo_telefono', align:'center', width:100 },
                { name:'tipref.nombre', index:'tipref.nombre', align:'center', width:100 },
                { name:'org.nombre', index:'org.nombre', align:'center', width:100 },
                { name:'refcli.observacion', index:'refcli.observacion', align:'center', width:200 }
                ],
            rowNum:10,
            rowList:[10,15,20],
            rownumbers:true,
            pager:'#pager_table_campo_telefono',
            sortname:'tel.numero',
            toolbar: [true,"top"],
            sortorder:'desc',
            loadui: "block"
        });
        $("#table_campo_telefono").jqGrid('navGrid','#pager_table_campo_telefono',{
            edit:false,
            add:false,
            del:false,
            view:true
        });
        $('#t_table_campo_telefono').addClass('ui-corner-top');
        $('#t_table_campo_telefono').attr('align','left');
        $('#t_table_campo_telefono').css('height','25px');
        var html="";
        html+="<table>";
        html+="<tr>";
        html+="<td><div id='toolbar_campo_telefonos_icon_edit' class='ui-pg-button ui-corner-all' style='width:19px;margin:0 2px;' onclick='getParamEditTelefonoCampo()' ><div class='ui-pg-div'><span class='ui-icon ui-icon-pencil'></span></div></div></td>";
        html+="</tr>";
        html+="</table>";
        $('#t_table_campo_telefono').append(html);
        $('#toolbar_campo_telefonos_icon_edit').hover(function(){
            $('#toolbar_campo_telefonos_icon_edit').addClass('ui-state-hover');
        },function(){
            $('#toolbar_campo_telefonos_icon_edit').removeClass('ui-state-hover');
        });
    },/*
    gestionComercial : function ( ){ //Piro 30-12-2014
        $('#jqGridReporteComercial').jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_gestion_comercial',
            datatype: 'json',
            gridview :true,
            height:100,
            width:906,
            loadonce:true,
            colNames : ["Codigo",'N°. Cuenta',"Cliente","Moneda","Total Deuda","Territorio","Oficina","R.U.C","Direccion","Fecha Visita","Hora Visita","Giro Negocio",
                "Detalle Giro Negocio","Motivo Atraso Negocio","Detalle Motivo Atraso Negocio ","Afrontar  Pago Negocio","Detalle Afrontar Pago Negocio",
                'Cuestiona Cobranza','Observacion Especilista Cobranza','Tiene Existencias','Labor Artesanal','Local Propio ','Oficina Administrativa','Menor Igual a Diez Personas','Mayor a Diez Personas',
                'Planta Industrial','Casa Negocio','Puerta a Calle','Actividad Adicional','Nueva Direccion','Numero de Visita','Nuevo Telefono','Tipo contacto','Direccion Visita 2'
            ],
            colModel : [
                {name: 'codigocliente',
                 index : 'codigocliente',
                searchoptions:
                            {
                            sopt: ['eq','ne','cn']
                            },
                resizable:true
                        
                },
                {name: 'numerocuenta',index:'numerocuenta',resizable:true},
                {name: 'nombre',
                 index: 'nombre',
               // width:150,
                resizable:true,
                searchoptions :{
                                sopt: ['eq','ne','cn','nc','bw','bn','ew','en','cn','nc']
                                }
                },
                {name: 'moneda',index:'moneda',resizable:true},
                {name: 'totaldeuda',index:'totaldeuda',resizable:true},
                {name: 'territorio',index:'territorio',resizable:true},
                {name: 'oficina',index:'oficina',resizable:true},
                {name: 'ruc',index:'ruc',resizable:true},
                {name: 'direccion',index:'direccion',resizable:true},
                {name: 'fechavisita',index:'fechavisita',resizable:true,searchoptions:{
                        dataInit: function (element) {
                                $(element).datepicker({
                                    id: 'orderDate_datePicker',
                                    dateFormat: 'yy-mm-dd',
                                    //minDate: new Date(2010, 0, 1),
                                    maxDate: new Date(2020, 0, 1),
                                    showOn: 'focus'
                                });
                            },
                        sopt:['eq','ne','gt','ge','lt','le'] },
                    search:true
                        
                        
                },
                {name: 'horavisita',index:'horavisita',resizable:true},
                {name: 'gironegocio',index:'gironegocio',resizable:true},
                {name: 'detallegironegocio',index:'detallegironegocio',resizable:true},
                {name: 'motivoatrasonegocio',index:'motivoatrasonegocio',resizable:true},
                {name: 'detallemotivoatrasonegocio',index:'detallemotivoatrasonegocio',resizable:true},
                {name: 'afrotnarpagonegocio',index:'afrotnarpagonegocio',resizable:true},
                {name: 'detalleafrontarpagonegocio',index:'detalleafrontarpagonegocio',resizable:true}, 
                {name: 'cuestionacobranza',index:'cuestionacobranza',resizable:true}, 
                {name: 'observacionespecialistanegocio',index:'observacionespecialistanegocio',resizable:true}, 
                {name: 'tieneexistencias',index:'tieneexistencias',resizable:true}, 
                {name: 'laborartesanal',index:'laborartesanal',resizable:true}, 
                {name: 'localpropio',index:'localpropio',resizable:true}, 
                {name: 'oficinaadministrativa',index:'oficinaadministrativa',resizable:true}, 
                {name: 'menorigualdiezpersonas',index:'menorigualdiezpersonas',resizable:true}, 
                {name: 'mayordiezpersonas',index:'mayordiezpersonas',resizable:true}, 
                {name: 'plantaindustrial',index:'plantaindustrial',resizable:true}, 
                {name: 'casanegocio',index:'casanegocio',resizable:true}, 
                {name: 'puertaacalle',index:'puertaacalle',resizable:true}, 
                {name: 'actividadadiconal',index:'actividadadiconal',resizable:true}, 
                {name: 'nuevadireccion',index:'nuevadireccion',resizable:true}, 
                {name: 'numerovisita',index:'numerovisita',resizable:true}, 
                {name: 'nuevotelefono',index:'nuevotelefono',resizable:true}, 
                {name: 'tipocontacto',index:'tipocontacto',resizable:true}, 
                {name: 'direccionvisita2',index:'direccionvisita2',resizable:true}
            ],
            pager : pagerReporteComercial,
            toolbar: [true,"top"],
            viewrecords: true,
            ignorecase :true,
            caption : 'Reporte Gestion Comercial',
            loadui:'block',
            rowList:[25,50,100],
            autowidth:true,
            rownumbers:true
        });
         $('#jqGridReporteComercial').navGrid('#pagerReporteComercial',
                {edit: false, add: false, del: false, search: true, refresh: true, view: true, position: "left"});
         $('#jqGridReporteComercial').navButtonAdd('#pagerReporteComercial',
                {
                    buttonicon: "ui-icon ui-icon-arrowthickstop-1-s",
                    title: "Excel",
                    caption: "Descargar",
                    position: "last",
                    onClickButton: function() {
                        window.location.href="../rpt/excel/atencion_cliente/gestionComercial.php";
                    }
        });
        
    },*/
    agendados : function ( ) {
        $("#table_agendados").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_agendados',
            datatype:this.type,
            height:100,
            colNames:['Cliente','Numero Doc','Tipo Doc','Tipo Gestion','Final','Fecha CP','Monto CP'],
            colModel:[
            {
                name:'cliente',
                index:'tel.numero',
                align:'center',
                width:200
            },
{
                name:'cli.numero_documento',
                index:'cli.numero_documento',
                align:'center',
                width:80
            },
{
                name:'cli.tipo_documento',
                index:'cli.tipo_documento',
                align:'center',
                width:80
            },
{
                name:'tipo_gestion',
                index:'tipo_gestion',
                align:'center',
                width:80
            },
{
                name:'final',
                index:'final',
                align:'center',
                width:100
            },
{
                name:'cp.fecha_cp',
                index:'cp.fecha_cp',
                align:'center',
                width:80
            },
{
                name:'cp.monto_cp',
                index:'cp.monto_cp',
                align:'center',
                width:80
            }
            ],
            rowNum:10,
            rowList:[10,15,20],
            rownumbers:true,
            pager:'#pager_table_agendados',
            sortname:'cliente',
            sortorder:'desc',
            loadui: "block"
        });
        $("#table_agendados").jqGrid('navGrid','#pager_table_agendados',{
            edit:false,
            add:false,
            del:false,
            view:true
        });
    },
    campo_agendados : function ( ) {
        $("#table_campo_visita").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_campo_visita',
            datatype:'local',
            ajaxGridOptions : { async : false },
            gridview:true,
            height:100,
            colNames:['Numero Cuenta','Fecha Visita','Fecha Recepcion','Estado','Direccion','Distrito','Provincia','Notificador','Fecha CP','Monto CP','Contacto','Nombre Contacto','Hora Llegada','Hora Salida','Observacion','idcuenta'],/*jmore201208*/
            colModel:[
                { name:'numero_cuenta',index:'numero_cuenta',align:'center',width:80, sortable:false },
                { name:'vis_fecha_visita',index:'vis.fecha_visita',align:'center',width:100,editable:true},
                { name:'vis_fecha_recepcion',index:'vis.fecha_recepcion',align:'center',width:100,editable:true},
                { name:'estado',index:'estado',align:'center',width:150, sortable:false },
                { name:'direccion',index:'direccion',align:'center',width:150, sortable:false },
                { name:'distrito',index:'distrito',align:'center',width:150, sortable:false },
                { name:'provincia',index:'provincia',align:'center',width:150, sortable:false },
                { name:'notificador', index:'notificador', align:'center', width:150, editable:true, edittype:'select', sortable:false },
                { name:'vis_fecha_cp',index:'vis.fecha_cp',align:'center',width:80, sortable:false, editable:true },
                { name:'vis_monto_cp',index:'vis.monto_cp',align:'center',width:80, sortable:false, editable:true },
                { name:'contacto', index:'contacto', align:'align', width:100, sortable:false },
                { name:'vis.nombre_contacto', index:'vis.nombre_contacto', align:'center', width:140, sortable:false },
                { name:'vis.hora_visita', index:'vis.hora_visita', align:'center', width:70, sortable:false },
                { name:'vis.hora_salida', index:'vis.hora_salida', align:'center', width:70, sortable:false },
                { name:'vis.observacion',index:'vis.observacion',align:'center',width:200, sortable:false,editable:true, edittype:'textarea' },
                { name:'idcuenta', index:'idcuenta', align:'align', width:100, sortable:false,hidden:true,editable:true }/*jmore201208*/
            ],
            rowNum:10,
            rowList:[10,15,20],
            rownumbers:true,
            ondblClickRow : function ( rowid, irow, icol,e ) {

                var Not_grd_v = new Array();
                for( i=0;i<AtencionClienteDAO.Notificadores.length;i++ ) {
                    Not_grd_v.push( '"'+AtencionClienteDAO.Notificadores[i].idusuario_servicio+'":"'+AtencionClienteDAO.Notificadores[i].operador+'"' );
                }
                var Pr_Not_grd_v = $.parseJSON( '{'+Not_grd_v.join(",")+'}' );
                //alert(Not_grd_v);
                $('#table_campo_visita').jqGrid('setColProp','notificador',{editoptions:{ value : Pr_Not_grd_v }});
                $('#table_campo_visita').jqGrid('editRow',rowid,true,
                        function ( ) {
                        	
                        	$('#table_campo_visita').find('tr[id="'+rowid+'"]').find(':text[name="vis_fecha_visita"],:text[name="vis_fecha_cp"]').mask("2099-99-99");
                        	$('#table_campo_visita').find('tr[id="'+rowid+'"]').find(':text[name="vis_fecha_recepcion"]').mask("9999-99-99");
                        	$('#table_campo_visita').find('tr[id="'+rowid+'"]').find(':text[name="vis_monto_cp"]').numeric({allow:"."});
                        	
                        },
                        function ( response ) 
                        {
                                var json_d = $.parseJSON(response.responseText);
                                if( json_d.rst  ){
                                    $('#table_campo_visita').jqGrid().trigger('reloadGrid');
                                }else{

                                }
                                _displayBeforeSendDl(json_d.msg,450);

                        },
                        '../controller/ControllerCobrast.php',
                        {
                            command:'atencion_cliente',
                            action:'ActualizarVisitaGrd',
                            usuario_modificacion:$('#hdCodUsuario').val()
                        }
                );
            },
            toolbar: [true,"top"],
            pager:'#pager_table_campo_visita',
            sortname:'vis.fecha_creacion',
            sortorder:'desc',
            loadui: "block"
        });
        $("#table_campo_visita").jqGrid('navGrid','#pager_table_campo_visita',{
            edit:false,
            add:false,
            del:false,
            view:true
        });
        $('#t_table_campo_visita').addClass('ui-corner-top');
        $('#t_table_campo_visita').attr('align','left');
        $('#t_table_campo_visita').css('height','25px');
    //				var html="";
    //					html+="<table>";
    //						html+="<tr>";
    //							html+="<td><div id='toolbar_campo_visita_icon_edit' class='ui-pg-button ui-corner-all' style='width:19px;margin:0 2px;' onclick='getParamEditVisitaCampo()' ><div class='ui-pg-div'><span class='ui-icon ui-icon-pencil'></span></div></div></td>";
    //						html+="</tr>";
    //					html+="</table>";
    //				$('#t_table_campo_visita').append(html);
    //				$('#toolbar_campo_visita_icon_edit').hover(function(){$('#toolbar_campo_visita_icon_edit').addClass('ui-state-hover');},function(){$('#toolbar_campo_visita_icon_edit').removeClass('ui-state-hover');});
    },
    visita : function ( ) {
        $("#table_visita").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_campo_visita',
            datatype:'local',
            gridview:true,
            height:100,
            colNames:['Numero Cuenta','Fecha Visita','Fecha Recepcion','Estado','Direccion','Distrito','Provincia','Notificador','Fecha CP','Monto CP','Contacto','Nombre Contacto','Hora Llegada','Hora Salida','Observacion'],
            colModel:[
                { name:'numero_cuenta', index:'numero_cuenta', align:'center', width:100, sortable:false },
                { name:'vis.fecha_visita', index:'vis.fecha_visita', align:'center', width:100 },
                { name:'vis.fecha_recepcion', index:'vis.fecha_recepcion', align:'center', width:100 },
                { name:'estado', index:'estado', align:'center', width:150, sortable:false },
                { name:'direccion', index:'direccion', align:'center', width:180, sortable:false },                
                { name:'distrito', index:'distrito', align:'center', width:150, sortable:false },                
                { name:'provincia', index:'provincia', align:'center', width:150, sortable:false },                                                
                { name:'notificador', index:'notificador', align:'center', width:150, sortable:false },
                { name:'vis.fecha_cp', index:'vis.fecha_cp', align:'center', width:100, sortable:false },
                { name:'vis.monto_cp', index:'vis.monto_cp', align:'center', width:100, sortable:false },
                { name:'contacto', index:'contacto', align:'align', width:100, sortable:false },
                { name:'vis.nombre_contacto', index:'vis.nombre_contacto', align:'center', width:100, sortable:false },
                { name:'vis.hora_visita', index:'vis.hora_visita', align:'center', width:70, sortable:false },
                { name:'vis.hora_salida', index:'vis.hora_salida', align:'center', width:70, sortable:false },
                { name:'vis.observacion', index:'vis.observacion', align:'center', width:200, sortable:false }
            ],
            rowNum:10,
            rowList:[10,15,20],
            rownumbers:true,
            toolbar: [true,"top"],
            pager:'#pager_table_visita',
            sortname:'vis.fecha_creacion',
            sortorder:'desc',
            loadui: "block"
        });
        $("#table_visita").jqGrid('navGrid','#pager_table_visita',{
            edit:false,
            add:false,
            del:false,
            view:true
        });
        $('#table_visita').addClass('ui-corner-top');
        $('#table_visita').attr('align','left');
        $('#table_visita').css('height','25px');
    }
}
