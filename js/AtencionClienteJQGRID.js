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
            gridComplete: function(){   
                var grid = $('#table_telefonos_cliente');
                var grid_ids=grid.jqGrid('getDataIDs');
                for(var i=0; i<grid_ids.length; i++){
                    var rowid = grid_ids[i];
                    var aRow = grid.jqGrid('getRowData',rowid);
                   
                    if ($(aRow['t1.numero']).text() == "000000000" ) {
                        if($('#chkbSintelf').is(":checked")){
                            grid.jqGrid('setSelection',rowid);
                        }
                    }
                }
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
    //MANTTELF
    // CAMBIO 20-06-2016
    gestion_direccion_opcion:function(){

        $("#table_gestion_direccion_opcion").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=gestion_direccion_opcion',
            datatype: "json",
            width:1041,
            height:100,
            colNames:['ID','UBIGEO','DEPARTAMENTO','PROVINCIA','DISTRITO','DIRECCION','IDORIGEN','ORIGEN','IDREFERENCIA','REFERENCIA'],
            colModel:[
                { name:'iddireccion', index:'iddireccion', align:'center', width:100, sortable:false, hidden:true},
                { name:'ubigeo', index:'ubigeo', align:'center', width:150,editable:true,hidden:true,editoptions:{size:28}},
                { name:'departamento', index:'departamento', align:'center', width:150,editable:true,edittype:"select",editoptions:{value:{},size:30},editrules:{required:false}},
                { name:'provincia', index:'provincia', align:'center', width:70,editable:true,edittype:"select",editoptions:{value:{},size:30},editrules:{required:false} },
                { name:'distrito', index:'distrito', align:'center', width:150, sortable:false, editable:true,hidden:false,edittype:"select",editoptions:{value:{},size:30},editrules:{required:false} },
                { name:'direccion', index:'direccion', align:'center', width:150, sortable:false, editable:true,hidden:false },
                { name:'idorigen', index:'idorigen', align:'center', width:150, sortable:false, hidden:true },
                //{ name:'origen', index:'origen', align:'center', width:150, sortable:false, editable:true,hidden:false },
                { name:'origen_dir', index:'origen_dir', align:'center', width:90, sortable:false,editable:true,edittype:"select",editoptions:{value:{},size:30},editrules:{required:false} },
                { name:'idtipo_referencia', index:'idtipo_referencia', align:'center', width:150, sortable:false, hidden:true },
                //{ name:'referencia', index:'referencia', align:'center', width:150, sortable:false, editable:true,hidden:false }
                { name:'referencia_dir', index:'referencia_dir', align:'center', width:100, sortable:false,editable:true,edittype:"select",editoptions:{value:{},size:30},editrules:{required:false} },                                                
               ],
            rowNum:10,
            rownumbers:true,
            rowList:[10,20,30],
            pager: '#pager_table_gestion_direccion_opcion',
            sortname: 'iddireccion',
            viewrecords: true,
            sortorder: "ASC",
            onSelectRow: function(delegado){
                var rowId =$("#table_gestion_direccion_opcion").jqGrid('getGridParam','selrow');
                var rowData = jQuery("#table_gestion_direccion_opcion").getRowData(rowId);

                // var nombre_tip_telf = rowData['tipo'];
                // $('#nombre_tip_telf').val(nombre_tip_telf);

                var nombre_ref_telf = rowData['referencia_dir'];
                var nombre_ori_telf = rowData['origen_dir'];
                var nombre_departamento = rowData['departamento'];
                var nombre_provincia = rowData['provincia'];
                var nombre_distrito = rowData['distrito'];

                // var nombre_lin_telf = rowData['prefijos'];
                // $('#nombre_lin_telf').val(nombre_lin_telf);


                $('#nombre_ref_telf').val(nombre_ref_telf);
                $('#nombre_ori_telf').val(nombre_ori_telf);
                $('#hddepartament_opcion').val(nombre_departamento);
                $('#hdprovincia_opcion').val(nombre_provincia);
                $('#hddistrito_opcion').val(nombre_distrito);
           },
            ondblClickRow: function(rowid){
                // var grid = $("#table_gestion_telefono");
                // var codigo_cliente_opcion =$("#codigo_cliente_aval_opcion").val();
                // $("#txtAtencionClienteNumeroCall").val(codigo_cliente_opcion);
                // $('#DialogGestionTelefonos').dialog('close');
            }
        });


        $("#table_gestion_direccion_opcion").jqGrid('navGrid','#pager_table_gestion_direccion_opcion',
            {
                edit:true,edittitle:"EDITAR DIRECCION", editicon: 'ui-icon-wrench',
                add:true,addtitle:"AGREGAR NUEVA DIRECCION", addicon:'ui-icon-plus',
                del:true,deltitle:"ELIMINAR DIRECCION",delicon:'ui-icon-minus',
                search:false,searchtitle:"Search"
            },
            {
                url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=Mant_Direct_Opcion',
                mtype: 'GET',
                editCaption:"EDITAR DIRECCION",
                edittext:"Edit",
                bSubmit: "EDITAR DIRECCION",
                bCancel: "CANCELAR",                
                closeOnEscape:true, 
                savekey: [true,13],
                errorTextFormat:commonError,
                width:"410",
                reloadAfterSubmit:true,
                bottominfo:"Los campos marcados con (*) son importantes",
                my: "center", at: "center", of: window,
                beforeShowForm:beforeShowEdit,
                zIndex:"1003",
                closeAfterEdit: true,
                beforeSubmit: function(postdata, formid){

                        var departamento=$("#departamento").multiselect("getChecked").map(function(){return this.value;}).get();
                        var provincia=$("#provincia").multiselect("getChecked").map(function(){return this.value;}).get();
                        var distrito=$("#distrito").multiselect("getChecked").map(function(){return this.value;}).get();
                        var origen_dir=$("#origen_dir").multiselect("getChecked").map(function(){return this.value;}).get();
                        var referencia_dir=$("#referencia_dir").multiselect("getChecked").map(function(){return this.value;}).get();


                        if(departamento==''){
                            return [false,'POR FAVOR, INGRESE DEPARTAMENTO']; 
                        }else if(provincia==''){
                            return [false,'POR FAVOR, INGRESE PROVINCIA']; 
                        }else if(distrito==''){
                            return [false,'POR FAVOR, INGRESE DISTRITO'];
                        }else if(origen_dir==''){
                            return [false,'POR FAVOR, INGRESE ORIGEN']; 
                        }else if(referencia_dir==''){
                            return [false,'POR FAVOR, INGRESE REFERNCIA']; 
                        }else{
                            return [true,'SE AGREGO CON EXITO...!!!'];
                        }
                },
                onclickSubmit: function(params, postdata) {//BOTON A GRABAR

                    return{
                        idcliente_cartera:$('#IdClienteCarteraMain').val(),
                        codigo_cliente : $('#add_telf_titu_aval').val()==1?$('#CodigoClienteMain').val():$('#codigo_cliente_aval_opcion').val(),
                        cartera:$('#IdCartera').val(),
                        // numero:$("#numero").val(),
                        // anexo:$("#anexo").val(),
                        // tipo:$("#tipo").multiselect("getChecked").map(function(){return this.value;}).get(),
                        referencia:$("#referencia").multiselect("getChecked").map(function(){return this.value;}).get(),
                        // prefijos:$("#prefijos").multiselect("getChecked").map(function(){return this.value;}).get(),
                        origen:$("#origen").multiselect("getChecked").map(function(){return this.value;}).get(),
                        // observacion:$("#observacion").val(),
                        idusuario_servicio:$('#hdCodUsuario').val()

                        

                    }
                    
                    $('.ui-widget-overlay').css({'z-index':'1001'});
                    $("#DialogGestionTelefonos").parent().css({'z-index':'1002'});                   
                },
                afterSubmit : function(response, postdata){

                    return [true,"OK"];

                },
                onClose:function(){//BOTON AL CANCELAR O CERRAR

                    $('.ui-widget-overlay').css({'z-index':'1001'});
                    $("#DialogGestionTelefonos").parent().css({'z-index':'1002'});

                    $('label[for="ui-multiselect-tipo-option-0"]').parent().parent().parent().css('display', 'none');
                    $('label[for="ui-multiselect-referencia-option-0"]').parent().parent().parent().css('display', 'none');
                    $('label[for="ui-multiselect-prefijos-option-0"]').parent().parent().parent().css('display', 'none');
                    $('label[for="ui-multiselect-origen-option-0"]').parent().parent().parent().css('display', 'none');
                },
                afterComplete:function(){

                    var codCli = $('#CodigoClienteMain').val();
                    var idCartera = $('#IdCartera').val();
                    $("#table_telefonos_cliente").jqGrid('setGridParam',{
                        datatype : 'json',
                        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_numero_telefono&CodigoCliente='+codCli+'&Cartera='+idCartera
                    }).trigger('reloadGrid');

                    listar_direccion_atencion_cliente($('#IdCartera').val(),$("#codigo_cliente_aval_opcion").val());
                    
                }
            },
            {
                url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=Mant_Direct_Opcion',
                mtype: 'GET',
                addCaption:"AGREGAR DIRECCION",
                bSubmit: "GRABAR DIRECCION",
                bCancel: "CANCELAR",
                closeOnEscape:true,
                savekey: [true,13],
                errorTextFormat:commonError,
                width:"410",
                reloadAfterSubmit:true,
                bottominfo:"Los campos marcados con (*) son importantes",
                my: "center", at: "center", of: window,
                beforeShowForm:beforeShowAdd,
                closeAfterAdd: true,
                beforeSubmit: function(postdata, formid){

                        // var numero=$("#numero").val();
                        // var anexo=$("#anexo").val();
                        // var tipo=$("#tipo").multiselect("getChecked").map(function(){return this.value;}).get();
                        // var referencia=$("#referencia").multiselect("getChecked").map(function(){return this.value;}).get();
                        // var prefijos=$("#prefijos").multiselect("getChecked").map(function(){return this.value;}).get();
                        // var origen=$("#origen").multiselect("getChecked").map(function(){return this.value;}).get();

                        var departamento=$("#departamento").multiselect("getChecked").map(function(){return this.value;}).get();
                        var provincia=$("#provincia").multiselect("getChecked").map(function(){return this.value;}).get();
                        var distrito=$("#distrito").multiselect("getChecked").map(function(){return this.value;}).get();
                        var origen_dir=$("#origen_dir").multiselect("getChecked").map(function(){return this.value;}).get();
                        var referencia_dir=$("#referencia_dir").multiselect("getChecked").map(function(){return this.value;}).get();


                        if(departamento==''){
                            return [false,'POR FAVOR, INGRESE DEPARTAMENTO']; 
                        }else if(provincia==''){
                            return [false,'POR FAVOR, INGRESE PROVINCIA']; 
                        }else if(distrito==''){
                            return [false,'POR FAVOR, INGRESE DISTRITO'];
                        }else if(origen_dir==''){
                            return [false,'POR FAVOR, INGRESE ORIGEN']; 
                        }else if(referencia_dir==''){
                            return [false,'POR FAVOR, INGRESE REFERNCIA']; 
                        }else{
                            return [true,'SE AGREGO CON EXITO...!!!'];
                        }

                },
                onclickSubmit: function(params, postdata) {//BOTON A GRABAR
                    return{
                        idcliente_cartera:$('#IdClienteCarteraMain').val(),
                        codigo_cliente : $('#add_telf_titu_aval').val()==1?$('#CodigoClienteMain').val():$('#codigo_cliente_aval_opcion').val(),
                        cartera:$('#IdCartera').val(),
                        // numero:$("#numero").val(),
                        // anexo:$("#anexo").val(),
                        // tipo:$("#tipo").multiselect("getChecked").map(function(){return this.value;}).get(),
                        referencia:$("#referencia").multiselect("getChecked").map(function(){return this.value;}).get(),
                        // prefijos:$("#prefijos").multiselect("getChecked").map(function(){return this.value;}).get(),
                        origen:$("#origen").multiselect("getChecked").map(function(){return this.value;}).get(),
                        // observacion:$("#observacion").val(),
                        idusuario_servicio:$('#hdCodUsuario').val()
                    }
                    $('.ui-widget-overlay').css({'z-index':'1001'});
                    $("#DialogGestionDireccion_opcion").parent().css({'z-index':'1002'});
                },
                afterSubmit : function(response, postdata){ 
                    
                    return [true,"OK"];

                },
                onClose:function(){//BOTON AL CANCELAR O CERRAR   

                    $('.ui-widget-overlay').css({'z-index':'1001'});
                    $("#DialogGestionDireccion_opcion").parent().css({'z-index':'1002'});

                    $('label[for="ui-multiselect-tipo-option-0"]').parent().parent().parent().css('display', 'none');
                    $('label[for="ui-multiselect-referencia-option-0"]').parent().parent().parent().css('display', 'none');
                    $('label[for="ui-multiselect-prefijos-option-0"]').parent().parent().parent().css('display', 'none');
                    $('label[for="ui-multiselect-origen-option-0"]').parent().parent().parent().css('display', 'none');

                },
                afterComplete:function(){

                    var codCli = $('#CodigoClienteMain').val();
                    var idCartera = $('#IdCartera').val();
                    $("#table_telefonos_cliente").jqGrid('setGridParam',{
                        datatype : 'json',
                        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_numero_telefono&CodigoCliente='+codCli+'&Cartera='+idCartera
                    }).trigger('reloadGrid');
                    listar_direccion_atencion_cliente($('#IdCartera').val(),$("#codigo_cliente_aval_opcion").val());
                }
            },
            {
                url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=Mant_Direct_Opcion',
                mtype: 'GET',
                caption:"Delete User",
                closeOnEscape:true,
                errorTextFormat:commonError,
                my: "center", at: "center", of: window,
                reloadAfterSubmit:true,
                beforeShowForm:beforeShowDelete,
                onclickSubmit: function(params, postdata) {//BOTON A GRABAR
                    $('.ui-widget-overlay').css({'z-index':'1001'});
                    $("#DialogGestionDireccion_opcion").parent().css({'z-index':'1002'}); 
                },
                onClose : function(){
                    $('.ui-widget-overlay').css({'z-index':'1001'});
                    $("#DialogGestionDireccion_opcion").parent().css({'z-index':'1002'});  
                },      
                afterComplete:function(){
                    var codCli = $('#CodigoClienteMain').val();
                    var idCartera = $('#IdCartera').val();
                    $("#table_telefonos_cliente").jqGrid('setGridParam',{
                        datatype : 'json',
                        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_numero_telefono&CodigoCliente='+codCli+'&Cartera='+idCartera
                    }).trigger('reloadGrid');

                    listar_direccion_atencion_cliente($('#IdCartera').val(),$("#codigo_cliente_aval_opcion").val());

                }
            },
            {
                errorTextFormat:commonError,
                Find:"Search",
                closeOnEscape:true,
                caption:"Search Users",
                multipleSearch:true,
                closeAfterSearch:true
            }
        );

        function beforeShowAdd(formId) {

            $('.ui-widget-overlay').css({'z-index':'1002'});
            $("#DialogGestionDireccion_opcion").parent().css({'z-index':'1001'});

            // var nombre_tip_telf="";

            // AtencionClienteDAO.Listtipotelf(nombre_tip_telf);

            $("#table_gestion_direccion_opcion").jqGrid("resetSelection");

            $('#hddepartament_opcion').val('');
            $('#hdprovincia_opcion').val('');
            $('#hddistrito_opcion').val('');


            $("#distrito option").remove();
            $("#provincia option").remove();

            AtencionClienteDAO.ListreferenciaDir();
            // AtencionClienteDAO.Listlineatelf();
            AtencionClienteDAO.ListorigenDir();
            AtencionClienteDAO.List_Departamento('');
            AtencionClienteDAO.List_Provincia('','I');
            AtencionClienteDAO.List_Distrito('','I');



        }
        function beforeShowEdit(){
            $('.ui-widget-overlay').css({'z-index':'1002'});
            $("#DialogGestionDireccion_opcion").parent().css({'z-index':'1001'});

            // var nombre_tip_telf=$('#nombre_tip_telf').val(); 
            // AtencionClienteDAO.Listtipotelf(nombre_tip_telf);


            var nombre_ref_telf=$('#nombre_ref_telf').val();
            var nombre_lin_telf=$('#nombre_lin_telf').val();
            var nombre_ori_telf=$('#nombre_ori_telf').val();

            var nombre_departament_opcion=$('#hddepartament_opcion').val();
            var nombre_provincia_opcion=$('#hdprovincia_opcion').val();
            var nombre_distrito_opcion=$('#hddistrito_opcion').val();

            AtencionClienteDAO.ListreferenciaDir(nombre_ref_telf);
            // AtencionClienteDAO.Listlineatelf(nombre_lin_telf);
            AtencionClienteDAO.ListorigenDir(nombre_ori_telf);
            AtencionClienteDAO.List_Departamento(nombre_departament_opcion);
            AtencionClienteDAO.List_Provincia('','M');
            AtencionClienteDAO.List_Provincia(nombre_departament_opcion,'M');
            AtencionClienteDAO.List_Distrito('','M');
            AtencionClienteDAO.List_Distrito(nombre_provincia_opcion,'M');

            


        }
        function beforeShowDelete(){
            // $('.ui-widget-overlay').css({'z-index':'1002'});
            // $("#DialogGestionDireccion_opcion").parent().css({'z-index':'1001'});
        }
        function commonError(data)
        {     
            return "Error Occured during Operation Durante la operacion a ocurrido un error. Por favor porbar nuevamente";
        }

    },
    // CAMBIO 20-06-2016
    gestion_telefono : function(){
        
        $("#table_gestion_telefono").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=gestion_telefono&codigo_cliente='+$("#CodigoClienteMain").val(),
            datatype: "json",
            width:620,
            height:260,
            colNames:['ID','NUMERO','ANEXO','TIPO','IS_NEW','IS_CAMPO','IS_CARGA','REFERENCIA','ESTADO','LINEA','PESO','ORIGEN','OBSERVACION'],
            colModel:[
                { name:'idtelefono', index:'idtelefono', align:'center', width:100, sortable:false, hidden:true},
                { name:'numero', index:'numero', align:'center', width:150,editable:true,editoptions:{size:28}},
                { name:'anexo', index:'anexo', align:'center', width:150,editable:true,editoptions:{size:28}},
                { name:'tipo', index:'tipo', align:'center', width:70,editable:true,edittype:"select",editoptions:{value:{},size:30},editrules:{required:false} },
                { name:'is_new', index:'is_new', align:'center', width:150, sortable:false, hidden:true },
                { name:'is_campo', index:'is_campo', align:'center', width:180, sortable:false, hidden:true },                
                { name:'is_carga', index:'is_carga', align:'center', width:150, sortable:false, hidden:true },                
                { name:'referencia', index:'referencia', align:'center', width:100, sortable:false,editable:true,edittype:"select",editoptions:{value:{},size:30},editrules:{required:false} },                                                
                { name:'estado', index:'estado', align:'center', width:100, sortable:false},
                { name:'prefijos', index:'prefijos', align:'center', width:100, sortable:false,editable:true,edittype:"select",editoptions:{value:{},size:30},editrules:{required:false} },
                { name:'peso', index:'peso', align:'center', width:100, sortable:false, hidden:true },
                { name:'origen', index:'origen', align:'center', width:90, sortable:false,editable:true,edittype:"select",editoptions:{value:{},size:30},editrules:{required:false} },
                { name:'observacion', index:'observacion', align:'center', width:90, sortable:false,editable:true,edittype:'textarea',editoptions:{cols:25}}
            ],
            rowNum:10,
            rownumbers:true,
            rowList:[10,20,30],
            pager: '#pager_table_gestion_telefono',
            sortname: 'idtelefono',
            viewrecords: true,
            sortorder: "asc",
            onSelectRow: function(delegado){
                var rowId =$("#table_gestion_telefono").jqGrid('getGridParam','selrow');
                var rowData = jQuery("#table_gestion_telefono").getRowData(rowId);

                var nombre_tip_telf = rowData['tipo'];
                $('#nombre_tip_telf').val(nombre_tip_telf);

                var nombre_ref_telf = rowData['referencia'];
                $('#nombre_ref_telf').val(nombre_ref_telf);

                var nombre_lin_telf = rowData['prefijos'];
                $('#nombre_lin_telf').val(nombre_lin_telf);

                var nombre_ori_telf = rowData['origen'];
                $('#nombre_ori_telf').val(nombre_ori_telf);           
           },
            ondblClickRow: function(rowid, irow, icol, e){
                var grid = $("#table_gestion_telefono");
                var codigo_cliente_opcion =$("#codigo_cliente_aval_opcion").val();

                // var rowId =$("#table_gestion_telefono").jqGrid('getGridParam','selrow');
                // var rowData = jQuery("#table_gestion_telefono").getRowData(rowId);
                var xidtelefono = $("#table_gestion_telefono").jqGrid("getRowData",rowid)['idtelefono'];
                var xnumero = $("#table_gestion_telefono").jqGrid("getRowData",rowid)['numero'];

                // $("#txtAtencionClienteNumeroCall").val(codigo_cliente_opcion);
                $("#HdIdTelefono").val(xidtelefono);
                $("#txtAtencionClienteNumeroCall").val(xnumero);

                $('#DialogGestionTelefonos').dialog('close');
            }
        });


        $("#table_gestion_telefono").jqGrid('filterToolbar',{searchOperators : true});

        $("#table_gestion_telefono").jqGrid('navGrid','#pager_table_gestion_telefono',
            {                
                edit:true,edittitle:"EDITAR TELEFONO", editicon: 'ui-icon-wrench',
                add:true,addtitle:"AGREGAR NUEVO TELEFONO", addicon:'ui-icon-plus',
                del:true,deltitle:"ELIMINAR TELEFONO",delicon:'ui-icon-minus',
                search:false,searchtitle:"Search"
            },
            {
                url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=Mant_Telf',
                mtype: 'GET',
                editCaption:"EDITAR TELEFONO",
                edittext:"Edit",
                bSubmit: "EDITAR TELEFONO",
                bCancel: "CANCELAR",                
                closeOnEscape:true, 
                savekey: [true,13],
                errorTextFormat:commonError,
                width:"330",
                reloadAfterSubmit:true,
                bottominfo:"Los campos marcados con (*) son importantes",
                my: "center", at: "center", of: window,
                beforeShowForm:beforeShowEdit,
                zIndex:"1003",
                closeAfterEdit: true,
                beforeSubmit: function(postdata, formid){

                        var numero=$("#numero").val();
                        var anexo=$("#anexo").val();
                        var tipo=$("#tipo").multiselect("getChecked").map(function(){return this.value;}).get();
                        var referencia=$("#referencia").multiselect("getChecked").map(function(){return this.value;}).get();
                        var prefijos=$("#prefijos").multiselect("getChecked").map(function(){return this.value;}).get();
                        var origen=$("#origen").multiselect("getChecked").map(function(){return this.value;}).get();

                        if(numero=='' && anexo==''){
                            return [false,'POR FAVOR, INGRESE NUMERO O ANEXO']; 
                        }else if(tipo==''){
                            return [false,'POR FAVOR, INGRESE TIPO TELEFONO']; 
                        }else if(referencia==''){
                            return [false,'POR FAVOR, INGRESE REFERENCIA'];
                        }else if(prefijos==''){
                            return [false,'POR FAVOR, INGRESE LINEA']; 
                        }else if(origen==''){
                            return [false,'POR FAVOR, INGRESE ORIGEN']; 
                        }else{
                            return [true,'SE AGREGO CON EXITO...!!!'];
                        }
                },
                onclickSubmit: function(params, postdata) {//BOTON A GRABAR

                    return{
                        idcliente_cartera:$('#IdClienteCarteraMain').val(),
                        codigo_cliente : $('#add_telf_titu_aval').val()==1?$('#CodigoClienteMain').val():$('#codigo_cliente_aval_opcion').val(),
                        cartera:$('#IdCartera').val(),
                        numero:$("#numero").val(),
                        anexo:$("#anexo").val(),
                        tipo:$("#tipo").multiselect("getChecked").map(function(){return this.value;}).get(),
                        referencia:$("#referencia").multiselect("getChecked").map(function(){return this.value;}).get(),
                        prefijos:$("#prefijos").multiselect("getChecked").map(function(){return this.value;}).get(),
                        origen:$("#origen").multiselect("getChecked").map(function(){return this.value;}).get(),
                        observacion:$("#observacion").val(),
                        idusuario_servicio:$('#hdCodUsuario').val()
                    }
                    
                    $('.ui-widget-overlay').css({'z-index':'1001'});
                    $("#DialogGestionTelefonos").parent().css({'z-index':'1002'});                   
                },
                afterSubmit : function(response, postdata){

                    return [true,"OK"];

                },
                onClose:function(){//BOTON AL CANCELAR O CERRAR

                    $('.ui-widget-overlay').css({'z-index':'1001'});
                    $("#DialogGestionTelefonos").parent().css({'z-index':'1002'});

                    $('label[for="ui-multiselect-tipo-option-0"]').parent().parent().parent().css('display', 'none');
                    $('label[for="ui-multiselect-referencia-option-0"]').parent().parent().parent().css('display', 'none');
                    $('label[for="ui-multiselect-prefijos-option-0"]').parent().parent().parent().css('display', 'none');
                    $('label[for="ui-multiselect-origen-option-0"]').parent().parent().parent().css('display', 'none');
                },
                afterComplete:function(){

                    var codCli = $('#CodigoClienteMain').val();
                    var idCartera = $('#IdCartera').val();
                    $("#table_telefonos_cliente").jqGrid('setGridParam',{
                        datatype : 'json',
                        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_numero_telefono&CodigoCliente='+codCli+'&Cartera='+idCartera
                    }).trigger('reloadGrid');
                    
                }
            },
            {
                url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=Mant_Telf',
                mtype: 'GET',
                addCaption:"AGREGAR TELEFONO",
                bSubmit: "GRABAR TELEFONO",
                bCancel: "CANCELAR",
                closeOnEscape:true,
                savekey: [true,13],
                errorTextFormat:commonError,
                width:"370",
                reloadAfterSubmit:true,
                bottominfo:"Los campos marcados con (*) son importantes",
                my: "center", at: "center", of: window,
                beforeShowForm:beforeShowAdd,
                closeAfterAdd: true,
                beforeSubmit: function(postdata, formid){

                        var numero=$("#numero").val();
                        var anexo=$("#anexo").val();
                        var tipo=$("#tipo").multiselect("getChecked").map(function(){return this.value;}).get();
                        var referencia=$("#referencia").multiselect("getChecked").map(function(){return this.value;}).get();
                        var prefijos=$("#prefijos").multiselect("getChecked").map(function(){return this.value;}).get();
                        var origen=$("#origen").multiselect("getChecked").map(function(){return this.value;}).get();


                        if(numero=='' && anexo==''){
                            return [false,'POR FAVOR, INGRESE NUMERO O ANEXO']; 
                        }else if(tipo==''){
                            return [false,'POR FAVOR, INGRESE TIPO TELEFONO']; 
                        }else if(referencia==''){
                            return [false,'POR FAVOR, INGRESE REFERENCIA'];
                        }else if(prefijos==''){
                            return [false,'POR FAVOR, INGRESE LINEA']; 
                        }else if(origen==''){
                            return [false,'POR FAVOR, INGRESE ORIGEN']; 
                        }else if($('#number_if_exist').val()=='SI'){
                            return [false,'NUMERO EXISTE, INGRESAR OTRO NUMERO'];
                        }else{
                            return [true,'SE AGREGO CON EXITO...!!!'];
                        }

                },
                onclickSubmit: function(params, postdata) {//BOTON A GRABAR
                    return{
                        idcliente_cartera:$('#IdClienteCarteraMain').val(),
                        codigo_cliente : $('#add_telf_titu_aval').val()==1?$('#CodigoClienteMain').val():$('#codigo_cliente_aval_opcion').val(),
                        cartera:$('#IdCartera').val(),
                        numero:$("#numero").val(),
                        anexo:$("#anexo").val(),
                        tipo:$("#tipo").multiselect("getChecked").map(function(){return this.value;}).get(),
                        referencia:$("#referencia").multiselect("getChecked").map(function(){return this.value;}).get(),
                        prefijos:$("#prefijos").multiselect("getChecked").map(function(){return this.value;}).get(),
                        origen:$("#origen").multiselect("getChecked").map(function(){return this.value;}).get(),
                        observacion:$("#observacion").val(),
                        idusuario_servicio:$('#hdCodUsuario').val()
                    }
                    $('.ui-widget-overlay').css({'z-index':'1001'});
                    $("#DialogGestionTelefonos").parent().css({'z-index':'1002'});
                },
                afterSubmit : function(response, postdata){ 
                    
                    return [true,"OK"];

                },
                onClose:function(){//BOTON AL CANCELAR O CERRAR   

                    $('.ui-widget-overlay').css({'z-index':'1001'});
                    $("#DialogGestionTelefonos").parent().css({'z-index':'1002'});

                    $('label[for="ui-multiselect-tipo-option-0"]').parent().parent().parent().css('display', 'none');
                    $('label[for="ui-multiselect-referencia-option-0"]').parent().parent().parent().css('display', 'none');
                    $('label[for="ui-multiselect-prefijos-option-0"]').parent().parent().parent().css('display', 'none');
                    $('label[for="ui-multiselect-origen-option-0"]').parent().parent().parent().css('display', 'none');

                },
                afterComplete:function(){

                    var codCli = $('#CodigoClienteMain').val();
                    var idCartera = $('#IdCartera').val();
                    $("#table_telefonos_cliente").jqGrid('setGridParam',{
                        datatype : 'json',
                        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_numero_telefono&CodigoCliente='+codCli+'&Cartera='+idCartera
                    }).trigger('reloadGrid');
                    
                }
            },
            {
                url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=Mant_Telf',
                mtype: 'GET',
                caption:"Delete User",
                closeOnEscape:true,
                errorTextFormat:commonError,
                my: "center", at: "center", of: window,
                reloadAfterSubmit:true,
                beforeShowForm:beforeShowDelete,
                onclickSubmit: function(params, postdata) {//BOTON A GRABAR
                    $('.ui-widget-overlay').css({'z-index':'1001'});
                    $("#DialogGestionTelefonos").parent().css({'z-index':'1002'}); 
                },
                onClose : function(){
                    $('.ui-widget-overlay').css({'z-index':'1001'});
                    $("#DialogGestionTelefonos").parent().css({'z-index':'1002'});  
                },      
                afterComplete:function(){
                    var codCli = $('#CodigoClienteMain').val();
                    var idCartera = $('#IdCartera').val();
                    $("#table_telefonos_cliente").jqGrid('setGridParam',{
                        datatype : 'json',
                        url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_numero_telefono&CodigoCliente='+codCli+'&Cartera='+idCartera
                    }).trigger('reloadGrid');

                }
            },
            {
                errorTextFormat:commonError,
                Find:"Search",
                closeOnEscape:true,
                caption:"Search Users",
                multipleSearch:true,
                closeAfterSearch:true
            }
        );

        function beforeShowAdd(formId) {

            $('.ui-widget-overlay').css({'z-index':'1002'});
            $("#DialogGestionTelefonos").parent().css({'z-index':'1001'});

            var nombre_tip_telf="";

            AtencionClienteDAO.Listtipotelf(nombre_tip_telf);
            AtencionClienteDAO.Listreferenciatelf();
            AtencionClienteDAO.Listlineatelf();
            AtencionClienteDAO.Listorigentelf();




        }
        function beforeShowEdit(){
            $('.ui-widget-overlay').css({'z-index':'1002'});
            $("#DialogGestionTelefonos").parent().css({'z-index':'1001'});

            var nombre_tip_telf=$('#nombre_tip_telf').val(); 
            AtencionClienteDAO.Listtipotelf(nombre_tip_telf);


            var nombre_ref_telf=$('#nombre_ref_telf').val();
            var nombre_lin_telf=$('#nombre_lin_telf').val();
            var nombre_ori_telf=$('#nombre_ori_telf').val();

            AtencionClienteDAO.Listreferenciatelf(nombre_ref_telf);
            AtencionClienteDAO.Listlineatelf(nombre_lin_telf);
            AtencionClienteDAO.Listorigentelf(nombre_ori_telf);

        }
        function beforeShowDelete(){
            $('.ui-widget-overlay').css({'z-index':'1002'});
            $("#DialogGestionTelefonos").parent().css({'z-index':'1001'});
        }
        function commonError(data)
        {     
            return "Error Occured during Operation Durante la operacion a ocurrido un error. Por favor porbar nuevamente";
        }
    },
    //MANTTELF
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
            rowNum:1,
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
            colNames:['Cartera','Contrato','Codigo','Nombre','Numero Doc','Tipo Doc'],
            colModel:[
                {name:'car.nombre_cartera',index:'car.nombre_cartera',align:'center',width:200},
                {name:'cli.contrato',index:'cli.contrato',align:'center',width:100,hidden:true},
                {name:'cli.codigo',index:'cli.codigo',align:'center',width:100},
                {name:'cli.nombre',index:'cli.nombre',align:'center',width:300},
                {name:'cli.numero_documento',index:'cli.numero_documento',align:'center',width:100},
                {name:'cli.tipo_documento',index:'cli.tipo_documento',align:'center',width:100}
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
        // var lastsel2
        $("#table_llamada").jqGrid({
            // url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_llamada',
            datatype:'local',
            gridview:true,
            ajaxGridOptions : { async : false },
            height:120,
            width:800,
            shrinkToFit:false,
            forceFit:true,
            hidegrid: false,
            colNames:['IDLLAMADA','CARTERA','TD','DOCUMENTO','TELEFONO','FECHA_LLAMADA','HORA_LLAMADA','ESTADO','FECHA_CP','MONEDA_CP','MONTO_CP','CONTACTO','NOMBRE_CONTACTO','PARENTESCO','MOTIVO_NO_PAGO','ESTADO_CLIENTE','OBS','USUARIO'],
            colModel:[
                {name:'IDLLAMADA',index:'IDLLAMADA',align:'center',width:120,sortable:false,hidden:false},
                {name:'CARTERA',index:'CARTERA',align:'center',width:120,sortable:false,hidden:false},
                {name:'TD',index:'TD',align:'center',width:120,sortable:false,hidden:false},
                {name:'DOCUMENTO',index:'DOCUMENTO',align:'center',width:120,sortable:false,hidden:false},
                {name:'TELEFONO',index:'TELEFONO',align:'center',width:120,sortable:false,hidden:false},
                {name:'FECHA_LLAMADA',index:'FECHA_LLAMADA',align:'center',width:120,sortable:false,hidden:false},
                {name:'HORA_LLAMADA',index:'HORA_LLAMADA',align:'center',width:120,sortable:false,hidden:false},
                {name:'ESTADO',index:'ESTADO',align:'center',width:120,sortable:false,hidden:false,editable:true,edittype:'select'},
                {name:'FECHA_CP',index:'FECHA_CP',align:'center',width:120,sortable:false,hidden:false,editable:true,width:70,edittype:'text'},
                {name:'MONEDA_CP',index:'MONEDA_CP',align:'center',width:120,sortable:false,hidden:false},
                {name:'MONTO_CP',index:'MONTO_CP',align:'center',width:120,sortable:false,hidden:false,editable:true},
                {name:'CONTACTO',index:'CONTACTO',align:'center',width:120,sortable:false,hidden:false},
                {name:'NOMBRE_CONTACTO',index:'NOMBRE_CONTACTO',align:'center',width:120,sortable:false,hidden:false},
                {name:'PARENTESCO',index:'PARENTESCO',align:'center',width:120,sortable:false,hidden:false},
                {name:'MOTIVO_NO_PAGO',index:'MOTIVO_NO_PAGO',align:'center',width:120,sortable:false,hidden:false},
                {name:'ESTADO_CLIENTE',index:'ESTADO_CLIENTE',align:'center',width:120,sortable:false,hidden:false},
                {name:'OBS',index:'OBS',align:'center',width:120,sortable:false,hidden:false,editable:true},
                {name:'USUARIO',index:'USUARIO',align:'center',width:120,sortable:false,hidden:false},
            ],
            rowNum:10,
            rowList:[10,15,20],
            rownumbers:true,
            toolbar: [true,"top"],
            pager:'#pager_table_llamada',
            sortname:'FECHA_LLAMADA',
            sortorder:'desc',
            loadui: "block",
            ondblClickRow : function ( rowid, irow, icol, e ) {
                //estado
                var Est_grd_l = new Array();
                for( i=0;i<AtencionClienteDAO.EstadosLlamada.length;i++ ) {
                    var data = (AtencionClienteDAO.EstadosLlamada[i].data).split('|');
                    for( j=0;j<data.length;j++ ) {
                        var final = data[j].split('@#');
                        Est_grd_l.push( '"'+final[0]+'":"'+final[1]+'"' );
                    }
                }

                var Pr_Est_grd_l = $.parseJSON( '{'+Est_grd_l.join(",")+'}' );
                $('#table_llamada').jqGrid('setColProp','ESTADO',{editoptions:{ value : Pr_Est_grd_l }});

                $('#table_llamada').jqGrid('editRow',rowid,true,
                        function(){
                            // fecha cp
                            $('#'+rowid+'_FECHA_CP').datepicker({
                                dateFormat:'yy-mm-dd',
                                dayNamesMin:['D','L','M','M','J','V','S'],
                                monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']
                            });
                            //obs
                            var observacion_data=$('#'+rowid+'_OBS').val();
                            observacion_data=observacion_data.replace('<pre style="white-space:normal;word-wrap: break-word;">','');
                            observacion_data=observacion_data.replace('</pre>','');                            
                            $('#'+rowid+'_OBS').val(observacion_data);                                                     
                        },
                        function(response){
                                var json_d = $.parseJSON(response.responseText);
                                if( json_d.rst  ){
                                    $('#table_llamada').jqGrid().trigger('reloadGrid');
                                }else{
                                    
                                }
                                _displayBeforeSendDl(json_d.msg,450);

                        },
                        '../controller/ControllerCobrast.php',
                        {
                            command:'atencion_cliente',
                            action:'ActualizarEstado',
                            usuario_modificacion:$('#hdCodUsuario').val(),
                            cartera:$('#IdCartera').val(),
                            cliente_cartera:$('#IdClienteCartera').val()
                        }
                );
            }
        });
        $("#table_llamada").jqGrid('navGrid','#pager_table_llamada',{
            edit:false,
            add:false,
            del:false,
            view:true,
            search:false
        });

        $("#t_table_llamada").css({'display':'none'});
        
        // $("#gbox_table_llamada").css({'margin':'0 auto'})
        // $("#pager_table_llamada").height(30);
        // $("#pager_table_llamada_center .ui-pg-input").css({'height':15,'font-size':10});
        // $("#pager_table_llamada_center .ui-pg-selbox").css({'height':19,'font-size':10});
        // $("#t_table_llamada").css({"display":"none"});

        var jqrid="table_llamada";

        $("#gbox_"+jqrid).css({'margin':'0 auto'})
        $("#gbox_"+jqrid).css({'box-shadow':'0 0 20px 0px black'})
        $("#pager_"+jqrid).height(30);
        $("#pager_"+jqrid+"_center .ui-pg-input").css({'height':15,'font-size':10});
        $("#pager_"+jqrid+"_center .ui-pg-selbox").css({'height':20,'font-size':10});

        // var origwidth=$("#gbox_"+jqrid).width();
        // $("#gview_"+jqrid).css({'width':origwidth+30});
        // $("#gbox_"+jqrid).css({'width':origwidth+30});
        // $("#gview_"+jqrid+" .ui-jqgrid-hdiv").css({'width':origwidth+30});
        // $("#gview_"+jqrid+" .ui-jqgrid-bdiv").css({'width':origwidth+30});
        $("#gview_"+jqrid+" .ui-jqgrid-bdiv").css({'background-color':'#FFFFFF'});
        // $("#pager_"+jqrid).css({'width':origwidth+30});


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
    },
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
    campo_agendados : function(){

        var jqrid="table_campo_visita";
        var pager="pager_table_campo_visita";

        $("#"+jqrid).jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_campo_visita',
            datatype:'local',
            gridview:true,
            width : 980,
            height: 200,
            shrinkToFit: false,
            forceFit: true,
            hidegrid: false,
            colNames:['IDVISITA','CARTERA','TD','DOCUMENTO','DIRECCION','FECHA_VISITA','HORA_LLEGADA','HORA_SALIDA','DESCRIP_INMUEBLE','ESTADO','FECHA_CP','MONEDA_CP','MONTO_CP','CONTACTO','NOMBRE_CONTACTO','PARENTESCO','MOTIVO_NO_PAGO','ESTADO_CLIENTE','OBS','USUARIO','IDCUENTA'],/*jmore201208*/
            colModel:[

                { name:'IDVISITA',index:'IDVISITA',align:'center',width:100,editable:false,hidden:true},
                { name:'CARTERA',index:'CARTERA',align:'center',width:100,editable:false},
                { name:'TD',index:'TD',align:'center',width:30,editable:false},
                { name:'DOCUMENTO',index:'DOCUMENTO',align:'center',width:100,editable:false},
                { name:'DIRECCION',index:'DIRECCION',align:'center',width:100,editable:false},
                { name:'FECHA_VISITA',index:'FECHA_VISITA',align:'center',width:100,editable:true},
                { name:'HORA_LLEGADA',index:'HORA_LLEGADA',align:'center',width:100,editable:false},
                { name:'HORA_SALIDA',index:'HORA_SALIDA',align:'center',width:100,editable:false},
                { name:'DESCRIP_INMUEBLE',index:'DESCRIP_INMUEBLE',align:'center',width:100,editable:false},
                // { name:'ESTADO',index:'ESTADO',align:'center',width:100,sortable:false,hidden:false,editable:false,edittype:'select'},
                {name:'ESTADO',index:'ESTADO',align:'center',width:120,sortable:false,hidden:false,editable:true,edittype:'select'},
                { name:'FECHA_CP',index:'FECHA_CP',align:'center',width:100,editable:true},
                { name:'MONEDA_CP',index:'MONEDA_CP',align:'center',width:100,editable:false},
                { name:'MONTO_CP',index:'MONTO_CP',align:'center',width:100,editable:true},
                { name:'CONTACTO',index:'CONTACTO',align:'center',width:100,editable:false},
                { name:'NOMBRE_CONTACTO',index:'NOMBRE_CONTACTO',align:'center',width:100,editable:false},
                { name:'PARENTESCO',index:'PARENTESCO',align:'center',width:100,editable:false},
                { name:'MOTIVO_NO_PAGO',index:'MOTIVO_NO_PAGO',align:'center',width:100,editable:false},
                { name:'ESTADO_CLIENTE',index:'ESTADO_CLIENTE',align:'center',width:100,editable:false},
                { name:'OBS',index:'OBS',align:'center',width:100,editable:true},
                { name:'USUARIO',index:'USUARIO',align:'center',width:100,editable:false},
                { name:'IDCUENTA',index:'IDCUENTA',align:'center',width:100,editable:true,hidden:true}
            ],
            rowNum:30,
            rowList:[30,35,40],
            rownumbers:true,
            loadonce: true,
            pager:'#'+pager,
            sortname:'numero_cuenta',
            sortorder:'ASC',
            caption : '',
            recordtext: "View {0} - {1} of {2}",
            emptyrecords: "Cargando...",
            loadtext: "Cargando...",
            pgtext : "P&aacute;gina {0} de {1}",
            ondblClickRow : function ( rowid, irow, icol,e ){
                var Est_grd_l = new Array();

                var arr=AtencionClienteDAO.EstadosVisita;

                for (i=0;i<arr.length;i++){
                    Est_grd_l.push( '"'+arr[i]['idfinal']+'":"'+arr[i]['nombre']+'"' );
                }

                var Pr_Est_grd_l = $.parseJSON( '{'+Est_grd_l.join(",")+'}' );
                $('#'+jqrid).jqGrid('setColProp','ESTADO',{editoptions:{ value : Pr_Est_grd_l }});

                $('#'+jqrid).jqGrid('editRow',rowid,true,
                    function(){
                        // fecha visista
                        // $('#'+rowid+'_FECHA_VISITA').datepicker({
                        //     dateFormat:'yy-mm-dd',
                        //     dayNamesMin:['D','L','M','M','J','V','S'],
                        //     monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']
                        // });
                        // // fecha cp
                        // $('#'+rowid+'_FECHA_CP').datepicker({
                        //     dateFormat:'yy-mm-dd',
                        //     dayNamesMin:['D','L','M','M','J','V','S'],
                        //     monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']
                        // });

                        $('#'+jqrid).find('tr[id="'+rowid+'"]').find(':text[name="FECHA_VISITA"]').mask("9999-99-99");
                        $('#'+jqrid).find('tr[id="'+rowid+'"]').find(':text[name="FECHA_CP"]').mask("9999-99-99");

                        //obs
                        var observacion_data=$('#'+rowid+'_OBS').val();
                        observacion_data=observacion_data.replace('<pre style="white-space:normal;word-wrap: break-word;">','');
                        observacion_data=observacion_data.replace('</pre>','');                            
                        $('#'+rowid+'_OBS').val(observacion_data);

                        $('#'+jqrid).find('tr[id="'+rowid+'"]').find(':text[name="MONTO_CP"]').numeric({allow:"."});

                    },
                    function(response){
                        var json_d = $.parseJSON(response.responseText);
                        if( json_d.rst  ){
                            $('#'+jqrid).jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
                            // jQuery("#table_visita_one").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
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
            }
        });
        $("#"+jqrid).jqGrid('navGrid','#pager_table_campo_visita',{
            edit:false,
            add:false,
            del:false,
            view:true
        });
        $('#t_'+jqrid).addClass('ui-corner-top');
        $('#t_'+jqrid).attr('align','left');
        $('#t_'+jqrid).css('height','25px');
        $('#gbox_'+jqrid).css('margin','0 auto');
    },
    visita : function ( ) {
        $("#table_visita").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_campo_visita',
            datatype:'local',
            gridview:true,
            height:100,
            colNames:['Numero Cuenta','Fecha Visita','Fecha Recepcion','Estado','tipo_persona','persona_direccion','Direccion','Distrito','Provincia','Notificador','Fecha CP','Monto CP','Contacto','Nombre Contacto','Hora Llegada','Hora Salida','Observacion','idcuenta'],
            colModel:[
                { name:'numero_cuenta', index:'numero_cuenta', align:'center', width:100, sortable:false },
                { name:'vis.fecha_visita', index:'vis.fecha_visita', align:'center', width:100 },
                { name:'vis.fecha_recepcion', index:'vis.fecha_recepcion', align:'center', width:100 },
                { name:'estado', index:'estado', align:'center', width:150, sortable:false },
                { name:'tipo_persona',index:'tipo_persona',align:'center',width:150, sortable:false },
                { name:'persona_direccion',index:'persona_direccion',align:'center',width:150, sortable:false },
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
                { name:'vis.observacion', index:'vis.observacion', align:'center', width:200, sortable:false },
                { name:'idcuenta', index:'idcuenta', align:'align', width:100, sortable:false,hidden:true,editable:true }/*jmore201208*/
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
    },
    lista_telefonos_cobtranzas:function(){
        var rowsToColor = [];
        $("#table_Lista_telf_cobranzas").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=List_Telf_cobranzas_andina',
            datatype:'local',
            gridview:true,
            height:100,
            colNames:['IDTELEFONO','NUMERO','ANEXO','PESO','TIPO','IS_NEW','IS_CAMPO','IS_CARGA','REFERENCIA','ESTADO','PREFIJOS','ORIGEN','CONDICION','STATE','STATUS','OBSERVACION','ACTION'],
            colModel:[
                { name:'idtelefono', index:'idtelefono', align:'center', width:100, sortable:false,hidden:true },
                { name:'numero', index:'numero', align:'center', width:100, sortable:false },
                { name:'anexo', index:'anexo', align:'center', width:85, sortable:false },
                { name:'peso', index:'peso', align:'center', width:61, sortable:false },
                { name:'tipo', index:'tipo', align:'center', width:74, sortable:false },
                { name:'is_new', index:'is_new', align:'center', width:100, sortable:false,hidden:true },
                { name:'is_campo', index:'is_campo', align:'center', width:100, sortable:false,hidden:true },
                { name:'is_carga', index:'is_carga', align:'center', width:100, sortable:false,hidden:true },
                { name:'referencia', index:'referencia', align:'center', width:90, sortable:false },
                { name:'estado', index:'estado', align:'center', width:92, sortable:false },
                { name:'prefijos', index:'prefijos', align:'center', width:92, sortable:false },
                { name:'origen', index:'origen', align:'center', width:100, sortable:false },
                { name:'condicion', index:'condicion', align:'center', width:100, sortable:false },
                { name:'state', index:'state', align:'center', width:72, sortable:false },
                { name:'status', index:'status', align:'center', width:53, sortable:false },
                { name:'observacion', index:'observacion', align:'center', width:98, sortable:false },
                {name:'act', index:'act', width:48,sortable:false},
            ],
            rowNum:10,
            rowList:[10,15,20],
            rownumbers:true,
            toolbar: [true,"top"],
            pager:'#pager_Lista_telf_cobranzas',
            sortname:'idtelefono',
            sortorder:'ASC',
            loadui: "block",
            gridComplete: function () {

                var mygrid = "table_Lista_telf_cobranzas";
                var rows = $("#"+mygrid).getDataIDs(); 
                for (var i = 0; i < rows.length; i++)
                {
                    var status = $("#"+mygrid).getCell(rows[i],"status");
                    if(status == "BAJA")
                    {
                        $("#"+mygrid).jqGrid('setRowData',rows[i],false, {  color:'white',weightfont:'bold',background:'#E19596'});            
                    }
                }

                var ids = jQuery("#table_Lista_telf_cobranzas").getDataIDs(); 
                for(var i=0;i<ids.length;i++){ 
                    var cl = ids[i]; 
                    // be = "<input style='height:22px;width:20px;' type='button' value='E' onclick=jQuery('#table_Lista_telf_cobranzas').editRow("+cl+"); ></ids>"; 
                    be = '<img src="../img/phone_edit.png" title="EDITAR" class="boton_imagen" style="cursor:pointer;" width="21"  onClick="editar_telf_andina('+cl+');"  /></ids>'; 
                    se = '<img src="../img/phone_deletes.png" title="ELIMINAR" class="boton_imagen" style="cursor:pointer;" width="21"  onClick="eliminar_telf_andina('+cl+');"  /></ids>';
                    jQuery("#table_Lista_telf_cobranzas").setRowData(ids[i],{act:be+se}) 
                } 

             }
        });

        $('#t_table_Lista_telf_cobranzas').css({"border-radius":"5px 5px 0 0","border":"none","width":1090,"height":35});
        $('#t_table_Lista_telf_cobranzas').css({"border-bottom":"1px solid #A6C9E2"});
        $('#t_table_Lista_telf_cobranzas').html('<div class="boton_estilo fondo_gradiente_azul" style="width:120px;margin-top:5px;margin-left: 5px;" id="idadd_telf_cobranzas_save"><img src="../img/phone_add.png" width="20" class="boton_imagen" style="position: absolute;left: 6px;top:2px;"><div class="lin_vet"></div><span class="boton_letra">AGREGAR</span></div>  ');
    },
    lista_direccion_cobranzas:function(){
        var rowsToColor = [];
        $("#table_Lista_Direc_cobranzas").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=lista_direccion_cobranzas',
            datatype:'local',
            gridview:true,
            height:100,
            colNames:['IDDIRECCION','DIRECCION','UBIGEO','DEPARTAMENTO','PROVINCIA','DISTRITO','REGION','ZONA','CODIGO_POSTAL','NUMERO','CALLE','REFERENCIA','OBSERVACION','ORIGEN','TIPO_REFERENCIA','FECHA_CREACION','USUARIO_CREACION','IDCARTERA','CODIGO_CLIENTE','IS_NEW','ESTADO','IDCLIENTE_CARTERA','CONDICION','ACTION'],
            colModel:[
                { name:'iddireccion', index:'iddireccion', align:'center', width:100, sortable:false,hidden:true },
                { name:'direccion', index:'direccion', align:'center', width:100, sortable:false },
                { name:'ubigeo', index:'ubigeo', align:'center', width:85, sortable:false,hidden:true },
                { name:'departamento', index:'departamento', align:'center', width:117, sortable:false },
                { name:'provincia', index:'provincia', align:'center', width:103, sortable:false },
                { name:'distrito', index:'distrito', align:'center', width:112, sortable:false},
                { name:'region', index:'region', align:'center', width:57, sortable:false},
                { name:'zona', index:'zona', align:'center', width:49, sortable:false},
                { name:'codigo_postal', index:'codigo_postal', align:'center', width:99, sortable:false },
                { name:'numero', index:'numero', align:'center', width:58, sortable:false },
                { name:'calle', index:'calle', align:'center', width:58, sortable:false },
                { name:'referencia', index:'referencia', align:'center', width:77, sortable:false },
                { name:'observacion', index:'observacion', align:'center', width:90, sortable:false },
                { name:'origen', index:'origen', align:'center', width:56, sortable:false },
                { name:'tipo_referencia', index:'tipo_referencia', align:'center', width:108, sortable:false },
                {name:'fecha_creacion', index:'fecha_creacion', width:48,sortable:false,hidden:true},
                {name:'usuario_creacion', index:'usuario_creacion', width:48,sortable:false,hidden:true},
                {name:'idcartera', index:'idcartera', width:48,sortable:false,hidden:true},
                {name:'codigo_cliente', index:'codigo_cliente', width:48,sortable:false,hidden:true},
                {name:'is_new', index:'is_new', width:48,sortable:false,hidden:true},
                {name:'estado', index:'estado', width:53,sortable:false},
                {name:'idcliente_cartera', index:'idcliente_cartera', width:48,sortable:false,hidden:true},
                {name:'condicion', index:'condicion', width:48,sortable:false},
                {name:'act', index:'act', width:48,sortable:false},
            ],
            rowNum:10,
            rowList:[10,15,20],
            rownumbers:true,
            toolbar: [true,"top"],
            pager:'#pager_Lista_Direc_cobranzas',
            sortname:'iddireccion',
            sortorder:'ASC',
            loadui: "block",
            gridComplete: function () {

                var mygrid = "table_Lista_Direc_cobranzas";
                var rows = $("#"+mygrid).getDataIDs(); 
                for (var i = 0; i < rows.length; i++)
                {
                    var estado = $("#"+mygrid).getCell(rows[i],"estado");
                    if(estado == "BAJA")
                    {
                        $("#"+mygrid).jqGrid('setRowData',rows[i],false, {  color:'white',weightfont:'bold',background:'#E19596'});            
                    }
                }

                var ids = jQuery("#table_Lista_Direc_cobranzas").getDataIDs(); 
                for(var i=0;i<ids.length;i++){ 
                    var cl = ids[i]; 
                    be = '<img src="../img/location_pencil.png" title="EDITAR" class="boton_imagen" style="cursor:pointer;" width="21"  onClick="editar_direccion_andina('+cl+');"  /></ids>'; 
                    se = '<img src="../img/location_cross.png" title="ELIMINAR" class="boton_imagen" style="cursor:pointer;" width="21"  onClick="eliminar_direccion_andina('+cl+');"  /></ids>';
                    jQuery("#table_Lista_Direc_cobranzas").setRowData(ids[i],{act:be+se}) 
                } 

             }
        });

        $('#t_table_Lista_Direc_cobranzas').css({"border-radius":"5px 5px 0 0","border":"none","width":1258,"height":35});
        $('#t_table_Lista_Direc_cobranzas').css({"border-bottom":"1px solid #A6C9E2"});
        $('#t_table_Lista_Direc_cobranzas').html('<div class="boton_estilo fondo_gradiente_azul" style="width:120px;margin-top:5px;" id="idadd_direccion_cobranzas_save"><img src="../img/location_add.png" width="25" class="boton_imagen" style="position: absolute;left: 6px;top:0px;"><div class="lin_vet"></div><span class="boton_letra">AGREGAR</span></div>  ');
    },
    List_Correo_cobranzas_andina:function(){
        var rowsToColor = [];
        $("#table_Lista_Correo_cobranzas").jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=List_Correo_cobranzas_andina',
            datatype:'local',
            gridview:true,
            height:100,
            colNames:['IDCORREO','CORREO','OBSERVACION','ESTADO','USUARIO_CREACION','FECHA_CREACION','IDCLIENTE','ACTION'],
            colModel:[
                { name:'idcorreo', index:'idcorreo', align:'center', width:100, sortable:false,hidden:true },
                { name:'correo', index:'correo', align:'center', width:100, sortable:false },
                { name:'observacion', index:'observacion', align:'center', width:200, sortable:false },
                { name:'estado', index:'estado', align:'center', width:117, sortable:false},
                { name:'usuario_creacion', index:'usuario_creacion', align:'center', width:103, sortable:false,hidden:true },
                { name:'fecha_creacion', index:'fecha_creacion', align:'center', width:112, sortable:false,hidden:true},
                { name:'idcliente', index:'idcliente', align:'center', width:57, sortable:false,hidden:true},
                { name:'act', index:'act', width:48,sortable:false},
            ],
            rowNum:10,
            rowList:[10,15,20],
            rownumbers:true,
            toolbar: [true,"top"],
            pager:'#pager_Lista_Correo_cobranzas',
            sortname:'idcorreo',
            sortorder:'ASC',
            loadui: "block",
            gridComplete: function () {

                var mygrid = "table_Lista_Correo_cobranzas";
                var rows = $("#"+mygrid).getDataIDs(); 
                for (var i = 0; i < rows.length; i++)
                {
                    var estado = $("#"+mygrid).getCell(rows[i],"estado");
                    if(estado == "BAJA")
                    {
                        $("#"+mygrid).jqGrid('setRowData',rows[i],false, {  color:'white',weightfont:'bold',background:'#E19596'});            
                    }
                }

                var ids = jQuery("#table_Lista_Correo_cobranzas").getDataIDs(); 
                for(var i=0;i<ids.length;i++){ 
                    var cl = ids[i]; 
                    be = '<img src="../img/arroba-edit.png" title="EDITAR" class="boton_imagen" style="cursor:pointer;" width="21"  onClick="editar_mail_andina('+cl+');"  /></ids>'; 
                    se = '<img src="../img/arroba-delete.png" title="ELIMINAR" class="boton_imagen" style="cursor:pointer;" width="21"  onClick="eliminar_mail_andina('+cl+');"  /></ids>';
                    jQuery("#table_Lista_Correo_cobranzas").setRowData(ids[i],{act:be+se}) 
                } 

             },
             loadComplete : function(){
                $("#table_Lista_Correo_cobranzas td:[aria-describedby='table_Lista_Correo_cobranzas_act']").css({'background-color':'#535F6B'});
             }
        });

        

        $('#t_table_Lista_Correo_cobranzas').css({"border-radius":"5px 5px 0 0","border":"none","width":490,"height":35});
        $('#t_table_Lista_Correo_cobranzas').css({"border-bottom":"1px solid #A6C9E2"});
        $('#t_table_Lista_Correo_cobranzas').html('<div class="boton_estilo fondo_gradiente_azul" style="width:120px;margin-top:5px;" id="idadd_mail_cobranzas_save"><img src="../img/arroba-add.png" width="20" class="boton_imagen" style="position: absolute;left: 6px;top:3px;"><div class="lin_vet"></div><span class="boton_letra">AGREGAR</span></div>  ');
    },
    Buscar_Cliente:function(){
        var jqrid="table_Lista_cliente_campo";
        $("#"+jqrid).jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=Buscar_Cliente',
            datatype:'local',
            gridview:true,
            height:100,
            width:600,
            shrinkToFit:false,
            forceFit:true,
            hidegrid: false,
            colNames:['IDCLIENTECARTERA','IDCLIENTE','IDCARTERA','CARTERA','CODIGO CLIENTE','CLIENTE','NRO DOC','TIPO DOC'],
            colModel:[
                {name:'idcliente_cartera', index:'idcliente_cartera', align:'center', width:100, sortable:false,hidden:true},
                {name:'idcliente', index:'idcliente', align:'center', width:100, sortable:false,hidden:true},
                {name:'idcartera', index:'idcartera', align:'center', width:100, sortable:false,hidden:true},
                {name:'nombre_cartera', index:'nombre_cartera', align:'left', width:138, sortable:false},
                {name:'codigo', index:'codigo', align:'center', width:150},
                {name:'cliente', index:'cliente', align:'left', width:150},
                {name:'numero_documento', index:'numero_documento', align:'center', width:80, sortable:false},
                {name:'tipo_documento',index:'tipo_documento',align:'center',width:50, sortable:false,hidden:true }
            ],
            rowNum:10,
            rowList:[10,15,20],
            rownumbers:true,
            toolbar: [true,"top"],
            pager:'#pager_table_Lista_cliente_campo',
            sortname:'idcliente_cartera',
            sortorder:'desc',
            loadui: "block",
            ondblClickRow: function(rowid){
                var grid = $("#"+jqrid);    
                var codigo_cliente = grid.jqGrid('getCell', rowid, 'codigo');
                var idcliente = grid.jqGrid('getCell', rowid, 'idcliente');
                var cliente = grid.jqGrid('getCell', rowid, 'cliente');
                var numero_documento = grid.jqGrid('getCell', rowid, 'numero_documento');
                var idcartera = grid.jqGrid('getCell', rowid, 'idcartera');
                var idcliente_cartera = grid.jqGrid('getCell', rowid, 'idcliente_cartera');

                // alert(idcliente_cartera);

                // $("#dato_idcliente").text(codigo_cliente);
                // $("#dato_razon_social").text(cliente);
                // $("#dato_nro_doc").text(numero_documento);

                var idservicio= $("#hdCodServicio").val();

                AtencionClienteDAO.consultar_datos_cliente(idservicio,idcartera,codigo_cliente,idcliente_cartera);



                $('#IdClienteCarteraCampoMain').val(idcliente_cartera);
                $('#IdClienteCampoMain').val(idcliente);
                $('#IdCarteraCampoMain').val(idcartera);
                $('#CodigoClienteCampoMain').val(codigo_cliente);

                AtencionClienteDAO.ListarDireccionVisita(idcartera,codigo_cliente,function( obj ){
                    var html = '';
                    html+='<option value="0">--Seleccione--</option>';
                    for( i=0;i<obj.length;i++ ) {
                        html+='<option value="'+obj[i].iddireccion+'">'+obj[i].direccion+'</option>';
                    }
                    $('#cbCampoDireccionVisita').html(html);
                },function(){});

                AtencionClienteDAO.ListarCuentaVisita(idcartera, idcliente_cartera);

                reloadJQGRID_visita_2(idcliente_cartera);
                reloadJQGRID_llamadas_two(codigo_cliente,idcliente);

                $('#cbCampoEmpresa').val("");
                $('#vis_xtd').val("");
                $('#xvis_doc').val("");
                $('#adelantado').attr('checked', false);

                AtencionClienteDAO.resumen_deuda();

            },

        });
        $("#gbox_"+jqrid).css({'margin':'0 auto'})
        $("#pager_"+jqrid).height(30);
        $("#pager_"+jqrid+"_center .ui-pg-input").css({'height':15,'font-size':10});
        $("#pager_"+jqrid+"_center .ui-pg-selbox").css({'height':19,'font-size':10});
        $("#t_"+jqrid).css({"display":"none"});

    },
    visitas_one : function(){
        var jqrid="table_visita_one";
        var pager="pager_table_visita_one";
        $("#"+jqrid).jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_campo_visita',
            datatype:'local',
            gridview:true,
            //ajaxGridOptions : { async : false },
            width : 980,
            height: 200,
            shrinkToFit: false,
            forceFit: true,
            hidegrid: false,
            colNames:['IDVISITA','CARTERA','TD','DOCUMENTO','DIRECCION','FECHA_VISITA','HORA_LLEGADA','HORA_SALIDA','DESCRIP_INMUEBLE','ESTADO','FECHA_CP','MONEDA_CP','MONTO_CP','CONTACTO','NOMBRE_CONTACTO','PARENTESCO','MOTIVO_NO_PAGO','ESTADO_CLIENTE','OBS','USUARIO','IDCUENTA'],
            colModel:[
                { name:'IDVISITA',index:'IDVISITA',align:'center',width:100,editable:false,hidden:true},
                { name:'CARTERA',index:'CARTERA',align:'center',width:100,editable:false},
                { name:'TD',index:'TD',align:'center',width:30,editable:false},
                { name:'DOCUMENTO',index:'DOCUMENTO',align:'center',width:100,editable:false},
                { name:'DIRECCION',index:'DIRECCION',align:'center',width:100,editable:false},
                { name:'FECHA_VISITA',index:'FECHA_VISITA',align:'center',width:100,editable:true},
                { name:'HORA_LLEGADA',index:'HORA_LLEGADA',align:'center',width:100,editable:false},
                { name:'HORA_SALIDA',index:'HORA_SALIDA',align:'center',width:100,editable:false},
                { name:'DESCRIP_INMUEBLE',index:'DESCRIP_INMUEBLE',align:'center',width:100,editable:false},
                { name:'ESTADO',index:'ESTADO',align:'center',width:100,sortable:false,editable:true,edittype:'select'},
                { name:'FECHA_CP',index:'FECHA_CP',align:'center',width:100,editable:true},
                { name:'MONEDA_CP',index:'MONEDA_CP',align:'center',width:100,editable:false},
                { name:'MONTO_CP',index:'MONTO_CP',align:'center',width:100,editable:true},
                { name:'CONTACTO',index:'CONTACTO',align:'center',width:100,editable:false},
                { name:'NOMBRE_CONTACTO',index:'NOMBRE_CONTACTO',align:'center',width:100,editable:false},
                { name:'PARENTESCO',index:'PARENTESCO',align:'center',width:100,editable:false},
                { name:'MOTIVO_NO_PAGO',index:'MOTIVO_NO_PAGO',align:'center',width:100,editable:false},
                { name:'ESTADO_CLIENTE',index:'ESTADO_CLIENTE',align:'center',width:100,editable:false},
                { name:'OBS',index:'OBS',align:'center',width:100,editable:true},
                { name:'USUARIO',index:'USUARIO',align:'center',width:100,editable:false},
                { name:'IDCUENTA',index:'IDCUENTA',align:'center',width:100,editable:true,hidden:true}
            ],
            rowNum:30,
            rowList:[30,35,40],
            rownumbers:true,
            loadonce: true,
            pager:'#'+pager,
            sortname:'FECHA_VISITA',
            sortorder:'ASC',
            caption : '',
            recordtext: "View {0} - {1} of {2}",
            emptyrecords: "Cargando...",
            loadtext: "Cargando...",
            pgtext : "P&aacute;gina {0} de {1}",
            ondblClickRow : function ( rowid, irow, icol, e ) {
                var Est_grd_l = new Array();

                var arr=AtencionClienteDAO.EstadosVisita;

                for (i=0;i<arr.length;i++){

                    // alert('"'+arr[i]['idfinal']+'":"'+arr[i]['nombre']+'"');

                    Est_grd_l.push( '"'+arr[i]['idfinal']+'":"'+arr[i]['nombre']+'"' );
                }

                // alert('{'+Est_grd_l.join(",")+'}');

                var Pr_Est_grd_l = $.parseJSON( '{'+Est_grd_l.join(",")+'}' );
                $('#'+jqrid).jqGrid('setColProp','ESTADO',{editoptions:{ value : Pr_Est_grd_l }});

                $('#'+jqrid).jqGrid('editRow',rowid,true,
                    function(){
                        // fecha visista
                        // $('#'+rowid+'_FECHA_VISITA').datepicker({
                        //     dateFormat:'yy-mm-dd',
                        //     dayNamesMin:['D','L','M','M','J','V','S'],
                        //     monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']
                        // });
                        // // fecha cp
                        // $('#'+rowid+'_FECHA_CP').datepicker({
                        //     dateFormat:'yy-mm-dd',
                        //     dayNamesMin:['D','L','M','M','J','V','S'],
                        //     monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']
                        // });

                        $('#'+jqrid).find('tr[id="'+rowid+'"]').find(':text[name="FECHA_VISITA"]').mask("9999-99-99");
                        $('#'+jqrid).find('tr[id="'+rowid+'"]').find(':text[name="FECHA_CP"]').mask("9999-99-99");

                        //obs
                        var observacion_data=$('#'+rowid+'_OBS').val();
                        observacion_data=observacion_data.replace('<pre style="white-space:normal;word-wrap: break-word;">','');
                        observacion_data=observacion_data.replace('</pre>','');                            
                        $('#'+rowid+'_OBS').val(observacion_data);

                        $('#'+jqrid).find('tr[id="'+rowid+'"]').find(':text[name="MONTO_CP"]').numeric({allow:"."});

                    },
                    function(response){
                        var json_d = $.parseJSON(response.responseText);
                        if( json_d.rst  ){
                            $('#table_visita_one').jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
                            // jQuery("#table_visita_one").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
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

            }
        });
        $("#"+jqrid).jqGrid('navGrid','#'+pager,{
            edit:false,
            add:false,
            del:false,
            view:true
        });
        $("#t_"+pager).css({'display':'none'});
        $('#t_'+pager).addClass('ui-corner-top');
        $('#t_'+pager).attr('align','left');
        $('#t_'+pager).css('height','25px');
        $('#gbox_'+pager).css('margin','0 auto');

        $("#gbox_"+jqrid).css({'margin':'0 auto'})
        $("#gbox_"+jqrid).css({'box-shadow':'0 0 20px 0px black'})
        $("#pager_"+jqrid).height(30);
        $("#pager_"+jqrid+"_center .ui-pg-input").css({'height':15,'font-size':10});
        $("#pager_"+jqrid+"_center .ui-pg-selbox").css({'height':20,'font-size':10});
        $("#gview_"+jqrid+" .ui-jqgrid-bdiv").css({'background-color':'#FFFFFF'});

    },
    llamadas_two : function(){
        $("#table_llamada_two").jqGrid({
            // url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=jqgrid_llamada',
            datatype:'local',
            gridview:true,
            ajaxGridOptions : { async : false },
            height:120,
            width:800,
            shrinkToFit:false,
            forceFit:true,
            hidegrid: false,
            colNames:['IDLLAMADA','CARTERA','TD','DOCUMENTO','TELEFONO','FECHA_LLAMADA','HORA_LLAMADA','ESTADO','FECHA_CP','MONEDA_CP','MONTO_CP','CONTACTO','NOMBRE_CONTACTO','PARENTESCO','MOTIVO_NO_PAGO','ESTADO_CLIENTE','OBS','USUARIO'],
            colModel:[

                {name:'IDLLAMADA',index:'IDLLAMADA',align:'center',width:120,sortable:false,hidden:false},
                {name:'CARTERA',index:'CARTERA',align:'center',width:120,sortable:false,hidden:false},
                {name:'TD',index:'TD',align:'center',width:120,sortable:false,hidden:false},
                {name:'DOCUMENTO',index:'DOCUMENTO',align:'center',width:120,sortable:false,hidden:false},
                {name:'TELEFONO',index:'TELEFONO',align:'center',width:120,sortable:false,hidden:false},
                {name:'FECHA_LLAMADA',index:'FECHA_LLAMADA',align:'center',width:120,sortable:false,hidden:false},
                {name:'HORA_LLAMADA',index:'HORA_LLAMADA',align:'center',width:120,sortable:false,hidden:false},
                {name:'ESTADO',index:'ESTADO',align:'center',width:120,sortable:false,hidden:false,editable:true,edittype:'select'},
                {name:'FECHA_CP',index:'FECHA_CP',align:'center',width:120,sortable:false,hidden:false,editable:true,width:70,edittype:'text'},
                {name:'MONEDA_CP',index:'MONEDA_CP',align:'center',width:120,sortable:false,hidden:false},
                {name:'MONTO_CP',index:'MONTO_CP',align:'center',width:120,sortable:false,hidden:false,editable:true},
                {name:'CONTACTO',index:'CONTACTO',align:'center',width:120,sortable:false,hidden:false},
                {name:'NOMBRE_CONTACTO',index:'NOMBRE_CONTACTO',align:'center',width:120,sortable:false,hidden:false},
                {name:'PARENTESCO',index:'PARENTESCO',align:'center',width:120,sortable:false,hidden:false},
                {name:'MOTIVO_NO_PAGO',index:'MOTIVO_NO_PAGO',align:'center',width:120,sortable:false,hidden:false},
                {name:'ESTADO_CLIENTE',index:'ESTADO_CLIENTE',align:'center',width:120,sortable:false,hidden:false},
                {name:'OBS',index:'OBS',align:'center',width:120,sortable:false,hidden:false,editable:true},
                {name:'USUARIO',index:'USUARIO',align:'center',width:120,sortable:false,hidden:false},

            ],
            ondblClickRow : function ( rowid, irow, icol,e ) {
                //estado
                var Est_grd_l = new Array();
                for( i=0;i<AtencionClienteDAO.EstadosLlamada.length;i++ ) {
                    var data = (AtencionClienteDAO.EstadosLlamada[i].data).split('|');
                    for( j=0;j<data.length;j++ ) {
                        var final = data[j].split('@#');
                        Est_grd_l.push( '"'+final[0]+'":"'+final[1]+'"' );
                    }
                }

                var Pr_Est_grd_l = $.parseJSON( '{'+Est_grd_l.join(",")+'}' );
                $('#table_llamada_two').jqGrid('setColProp','ESTADO',{editoptions:{ value : Pr_Est_grd_l }});

                $('#table_llamada_two').jqGrid('editRow',rowid,true,
                        function(){
                            // fecha cp
                            // $('#'+rowid+'_FECHA_CP').datepicker({
                            //     dateFormat:'yy-mm-dd',
                            //     dayNamesMin:['D','L','M','M','J','V','S'],
                            //     monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre']
                            // });

                            $('#table_llamada_two').find('tr[id="'+rowid+'"]').find(':text[name="FECHA_CP"]').mask("9999-99-99");

                            //obs
                            var observacion_data=$('#'+rowid+'_OBS').val();
                            observacion_data=observacion_data.replace('<pre style="white-space:normal;word-wrap: break-word;">','');
                            observacion_data=observacion_data.replace('</pre>','');                            
                            $('#'+rowid+'_OBS').val(observacion_data);                                                     
                        },
                        function(response){
                                var json_d = $.parseJSON(response.responseText);
                                if( json_d.rst  ){
                                    $('#table_llamada_two').jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
                                }else{
                                    
                                }
                                _displayBeforeSendDl(json_d.msg,450);

                        },
                        '../controller/ControllerCobrast.php',
                        {
                            command:'atencion_cliente',
                            action:'ActualizarEstado',
                            usuario_modificacion:$('#hdCodUsuario').val(),
                            cartera:$('#IdCarteraCampoMain').val(),
                            cliente_cartera:$('#IdClienteCarteraCampoMain').val()
                        }
                );
            },
            rowNum:10,
            rowList:[10,15,20],
            rownumbers:true,
            toolbar: [true,"top"],
            pager:'#pager_table_llamada_two',
            sortname:'FECHA_LLAMADA ',
            sortorder:'desc',
            loadui: "block"
        });
        $("#table_llamada_two").jqGrid('navGrid','#pager_table_llamada_two',{
            edit:false,
            add:false,
            del:false,
            view:true,
            search:false
        });

        $("#t_table_llamada_two").css({'display':'none'});

        var jqrid="table_llamada_two";

        $("#gbox_"+jqrid).css({'margin':'0 auto'})
        $("#gbox_"+jqrid).css({'box-shadow':'0 0 20px 0px black'})
        $("#pager_"+jqrid).height(30);
        $("#pager_"+jqrid+"_center .ui-pg-input").css({'height':15,'font-size':10});
        $("#pager_"+jqrid+"_center .ui-pg-selbox").css({'height':20,'font-size':10});
        $("#gview_"+jqrid+" .ui-jqgrid-bdiv").css({'background-color':'#FFFFFF'});
        
    },
    Listar_Contactos : function(){

        var jqrid="table_Lista_contacto_cobranzas";
        var pager="pager_Lista_contacto_cobranzas";
        $("#"+jqrid).jqGrid({
            url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=Listar_Contactos',
            datatype:'local',
            gridview:true,
            // ajaxGridOptions : { async : false },
            width : 900,
            height: 200,
            shrinkToFit: false,
            forceFit: true,
            hidegrid: false,
            colNames:['IDPERSONA','RAZON_SOCIAL','NOMBRE','PATERNO','MATERNO','TIPO_DOCUMENTO','NUMERO_DOCUMENTO','ESTADO','IDCLIENTE','-'],
            colModel:[
                { name:'idpersona',index:'idpersona',align:'center',width:100,editable:false,hidden:true},
                { name:'razon_social',index:'razon_social',align:'center',width:147,editable:true,edittype:"text",sorttype:"text",hidden:false},
                { name:'nombre',index:'nombre',align:'center',width:100,editable:true,edittype:"text",sorttype:"text",hidden:false},
                { name:'paterno',index:'paterno',align:'center',width:100,editable:true,edittype:"text",sorttype:"text",hidden:false},
                { name:'materno',index:'materno',align:'center',width:100,editable:true,edittype:"text",sorttype:"text",hidden:false},
                { name:'tipo_documento',index:'tipo_documento',align:'center',width:147,editable:true,hidden:false, formatter: 'select', edittype: 'select', editoptions: { value: { '': '.:SELECCIONE:.','DNI': 'DNI', 'RUC': 'RUC' }}},
                { name:'numero_documento',index:'numero_documento',align:'center',width:147,editable:true,edittype:"text",sorttype:"text",hidden:false},
                { name:'estado',index:'estado',align:'center',width:100,editable:false,hidden:true},
                { name:'idcliente',index:'idcliente',align:'center',width:100,editable:false,hidden:true},
                { name:'act',index:'act',align:'center', width:150,sortable:false},
            ],
            rowNum:30,
            rowList:[30,35,40],
            rownumbers:true,
            loadonce: true,
            pager:'#'+pager,
            sortname:'idpersona',
            sortorder:'ASC',
            caption : '',
            recordtext: "View {0} - {1} of {2}",
            emptyrecords: "Cargando...",
            loadtext: "Cargando...",
            pgtext : "P&aacute;gina {0} de {1}",
            gridComplete: function(){
                var ids = jQuery("#"+jqrid).jqGrid('getDataIDs');
                for(var i=0;i < ids.length;i++){
                    var cl = ids[i];
                    se = "<input style='height:22px;width:40px;cursor:pointer;margin: 0 5px 0 0;' type='button' value='TELF.' onclick=\"Listar_Contactos_Telefonos('"+cl+"');\"  />"+""; 
                    be = "<input style='height:22px;width:40px;cursor:pointer;margin: 0 5px 0 0;' type='button' value='EMAIL' onclick=\"Listar_Contactos_Correo('"+cl+"');\"  />"+""; 
                    // ce = "<input style='height:22px;width:40px;cursor:pointer;margin: 0 5px 0 0;' type='button' value='DIREC' onclick=\"jQuery('#rowed2').restoreRow('"+cl+"');\" />";
                    jQuery("#"+jqrid).jqGrid('setRowData',ids[i],{act:se+be});
                }   
            },
        });

        $("#"+jqrid).jqGrid('navGrid','#'+pager,
            {
                edit:true,edittitle:"EDITAR",editicon: 'ui-icon-wrench',
                add:true,addtitle:"AGREGAR",addicon:'ui-icon-plus',
                del:true,deltitle:"ELIMINAR",delicon:'ui-icon-minus',
                refresh:true,
                search:false,searchtitle:"Search"
            },
            // EDIT
            {
                url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=Mant_contactos',
                mtype: 'GET',
                editCaption:"EDITAR",
                edittext:"Edit",
                bSubmit: "EDITAR",
                bCancel: "CANCELAR",                
                closeOnEscape:true, 
                savekey: [true,13],
                errorTextFormat:commonError,
                width:"900",
                reloadAfterSubmit:true,
                bottominfo:"Los campos marcados con (*) son importantes",
                top:"60",
                left:"70",
                beforeShowForm:beforeShowEdit,
                zIndex:"1003",
                closeAfterEdit: true,
                beforeSubmit: function(postdata, formid){
                       return [true,'SE AGREGO CON EXITO...!!!'];
                },
                onclickSubmit: function(params, postdata) {//BOTON A GRABAR           

                    // return{
                    //     idcliente:$('#idClienteMain').val()
                    // }

                    $('.ui-widget-overlay').css({'z-index':'1001'});
                    $("#dialogGrupo_Cliente").parent().css({'z-index':'1002'});                   
                },
                afterSubmit : function(response, postdata){ 
                    return [true];
                },
                onClose:function(){//BOTON AL CANCELAR O CERRAR
                    $('.ui-widget-overlay').css({'z-index':'1001'});
                    $("#dialogGrupo_Cliente").parent().css({'z-index':'1002'});
                }
            },
            // ADD
            {
                url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=Mant_contactos',
                mtype: 'GET',
                addCaption:"AGREGAR",
                bSubmit: "GRABAR",
                bCancel: "CANCELAR",
                closeOnEscape:true,
                savekey: [true,13],
                errorTextFormat:commonError,
                width:"900",
                reloadAfterSubmit:true,
                bottominfo:"Los campos marcados con (*) son importantes",
                top:"60",
                left:"70",
                beforeShowForm: beforeShowAdd,
                closeAfterAdd: true,
                beforeSubmit: function(postdata, formid){
                     return [true,'SE AGREGO CON EXITO...!!!'];
                },
                onclickSubmit: function(params, postdata) {//BOTON A GRABAR
               
                    return{
                        idcliente:$('#idClienteMain').val()
                    }

                    $('.ui-widget-overlay').css({'z-index':'1001'});
                    $("#dialogGrupo_Cliente").parent().css({'z-index':'1002'});
                },
                afterSubmit : function(response, postdata){ 
                    return [true];
                },
                onClose:function(){//BOTON AL CANCELAR O CERRAR                   
                    $('.ui-widget-overlay').css({'z-index':'1001'});
                    $("#dialogGrupo_Cliente").parent().css({'z-index':'1002'});
                },

            },
            // DELETE
            {
                url:'../controller/ControllerCobrast.php?command=atencion_cliente&action=Mant_contactos',
                mtype: 'GET',
                caption:"Delete User",
                closeOnEscape:true,
                errorTextFormat:commonError,
                top:"60",
                left:"70",
                reloadAfterSubmit:true,
                onclickSubmit: function(params, postdata) {//BOTON A GRABAR
                },
                afterComplete:function(){
                    return [true];
                }
            },
            {
                errorTextFormat:commonError,
                Find:"Search",
                closeOnEscape:true,
                caption:"Search Users",
                multipleSearch:true,
                closeAfterSearch:true
            }
        );

        $("#t_"+pager).css({'display':'none'});
        $('#t_'+pager).addClass('ui-corner-top');
        $('#t_'+pager).attr('align','left');
        $('#t_'+pager).css('height','25px');
        $('#gbox_'+pager).css('margin','0 auto');

        $("#gbox_"+jqrid).css({'margin':'0 auto'})
        $("#gbox_"+jqrid).css({'box-shadow':'0 0 20px 0px black'})
        $("#pager_"+jqrid).height(30);
        $("#pager_"+jqrid+"_center .ui-pg-input").css({'height':15,'font-size':10});
        $("#pager_"+jqrid+"_center .ui-pg-selbox").css({'height':20,'font-size':10});
        $("#gview_"+jqrid+" .ui-jqgrid-bdiv").css({'background-color':'#2d3e50'});




        function beforeShowAdd(formId){
            $('.ui-widget-overlay').css({'z-index':'1002'});
            $("#dialogGrupo_Cliente").parent().css({'z-index':'1001'});          
        }
        function commonError(data){
            return "Error Occured during Operation Durante la operacion a ocurrido un error. Por favor porbar nuevamente";
        }
        function beforeShowEdit(){
            $('.ui-widget-overlay').css({'z-index':'1002'});
            $("#dialogGrupo_Cliente").parent().css({'z-index':'1001'});
        }
    },
    
}
