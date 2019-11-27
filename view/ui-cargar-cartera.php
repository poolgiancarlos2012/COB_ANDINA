<?php
session_start();
if (!isset($_SESSION['cobrast'])) {
    header('Location:../index.php');
} else if (!$_SESSION['cobrast']['activo']) {
    header('Location:../index.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Carga Cartera</title>
        <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">

        <link type="text/css" rel="stylesheet" href="../includes/jquery-ui-1.8/themes/redmond/jquery-ui.css" />
        <link type="text/css" rel="stylesheet" href="../includes/font-awesome-4.3.0/css/font-awesome.css" />

        <link type="text/css" rel="stylesheet" href="../css/css-cobrast.css" />
        <link type="text/css" rel="stylesheet" href="../css/jquery.fileupload-ui.css" />
        <link type="text/css" rel="stylesheet" href="../includes/font-awesome-4.3.0/css/font-awesome.css" />

        <script type="text/javascript" src="../js/includes/jquery-1.4.2.js" ></script>
        <script type="text/javascript" src="../js/includes/jquery.upload-1.0.2.js" ></script>
        <script type="text/javascript" src="../js/includes/jquery.fileupload.js" ></script>
        <script type="text/javascript" src="../js/includes/jquery.fileupload-ui.js" ></script>

        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.core.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.widget.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.mouse.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.draggable.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.droppable.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.selectable.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.dialog.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.button.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.datepicker.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.effects.core.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.effects.blind.min.js" ></script>

        <script type="text/javascript" src="../js/includes/ui.spinner.js" ></script>
        <script type="text/javascript" src="../js/includes/jquery.alphanumeric.js" ></script>

        <script type="text/javascript" src="../js/js-cobrast.js"></script>
        <script type="text/javascript" src="../js/validacion.js" ></script>
        <script type="text/javascript" src="../js/templates.js" ></script>
        <script type="text/javascript" src="../js/CargaCarteraDAO.js"></script>
        <script type="text/javascript" src="../js/ReporteDAO.js" ></script>
        <script type="text/javascript" src="../js/js-carga-cartera.js"></script>
        <style>
            .covinoc {
                display: inline-block;
                width: 320px;
                padding:3px;
            }
            .covinoc_estado_carga {
                display: inline-block;
                width: 73px;
                text-align:center;
            }
            .title_prep_car_covinoc{
                text-align: center; 
                width: 100%; 
                font-weight: bold; 
                margin-bottom: 10px;
                padding-bottom : 5px;
                padding-top : 5px;
                letter-spacing: 0.8px;;
                cursor : inherit;
            }
            body {
                 background: #F4F0EC url(../img/bg_.jpg)
            }
        </style>
    </head>
    <body>
        <div class="divContentMain" style="width: 1038px;">
            <table class="tableTab" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td rowspan="2" width="100"></td>
                    <td>
                        <div class="rightItem" style="position:relative;z-index:10;padding: 6px;font-family: Roboto;-moz-user-select: none;">
                            <div class="fltRight" style="margin:0px 40px 0px 0px;">
                                <a title="Cerrar Sesión" style="margin-left: 5px; margin-right: 5px; color: rgb(203, 116, 49);" class=" fa fa-sign-out" href="../close.php"></a>
                            </div>
                            
                            <label style="margin-right: 5px;"><b>Bienvenido:</b> <?= $_SESSION['cobrast']['usuario'] ?></label>
                            <label style="margin-right: 5px;"><b>Servicio:</b> <?= $_SESSION['cobrast']['servicio'] ?></label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="vAlignBottom tabsLine" style="height:47px !important">
                        <div id="layerMessage" align="center"></div>
                        <div class="menuHome">
                            <span>
                                <div>
                                    <table cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <?php
                                            if ($_SESSION['cobrast']['privilegio'] == 'administrador') {
                                                require_once('../menus/menu-sistemas.php');
                                            } else if ($_SESSION['cobrast']['privilegio'] == 'supervisor') {
                                                require_once('../menus/menu-supervisor.php');
                                            } else {
                                                require_once('../menus/menu-operador.php');
                                            }
                                            ?>
                                        </tr>
                                    </table>
                                </div>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="lineTab ui-widget-header ui-corner-top" colspan="2"></td>
                </tr>
            </table>
            <table class="tableContent" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="barLayer">
                        <div id="barLayer" style="width:210px; display:none; background:#fffbf2;border: 1px solid #666;margin:0;position:absolute;z-index:9999;height:100%;overflow:auto;" >
                            <div align="right"><img src="../img/cancel.png" style="cursor:pointer;margin:3px;" onClick="$('#barLayer').css('display','none');"/></div>
                            <div class=" backPanel headerPanel">
                                <div class="backPanel iconPinUp"
                                     onclick="_slideBarLayer(this,'panelMenu')">Menu</div>
                            </div>
                            <div id="panelMenu" class="backPanel contentBarLayer"
                                 style="display: block;">
                                <table border="0" style="margin-left: 20px;">
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelCargarCartera" style="cursor: pointer;" onClick="_display_panel('panelCargarCarteraMain');$('#panelLimpiarCarteraMain').css('display','block')">Cartera</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelCargarPago"  style="cursor: pointer;" onClick="_display_panel('panelCargarPagoMain');$('#panelLimpiarCarteraMain').css('display','block')">Pagos</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelCargarCentroPago"  style="cursor: pointer;" onClick="_display_panel('panelCargarCentroPago');$('#panelLimpiarCarteraMain').css('display','block')">Centro de Pago</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelComision"  style="cursor: pointer;" onClick="_display_panel('panelComision');$('#panelLimpiarCarteraMain').css('display','block')">Comision</a></td>
                                    </tr>
                                    <!--<tr>
                                        <td><a class="text-blue" id="aDisplayPanelPlanta"  style="cursor: pointer;" onClick="_display_panel('panelCargarPlanta');$('#panelLimpiarCarteraMain').css('display','block')">Planta</a></td>
                                    </tr>-->
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelTelefono"  style="cursor: pointer;" onClick="_display_panel('panelCargarTelefono');$('#panelLimpiarCarteraMain').css('display','block')">Telefono</a></td>
                                    </tr>
                                    <!--<tr>
                                        <td><a class="text-blue" id="aDisplayPanelDetalle"  style="cursor: pointer;" onClick="_display_panel('panelCargarDetalle');$('#panelLimpiarCarteraMain').css('display','block')">Detalle</a></td>
                                    </tr>-->
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelReclamo"  style="cursor: pointer;" onClick="_display_panel('panelCargarReclamos');$('#panelLimpiarCarteraMain').css('display','block')">Reclamo</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelRRRLL"  style="cursor: pointer;" onClick="_display_panel('panelCargarRRLL');$('#panelLimpiarCarteraMain').css('display','block')">RRLL</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelNOCpredicitivo"  style="cursor: pointer;" onClick="_display_panel('panelCargarNOCpredictivo');$('#panelLimpiarCarteraMain').css('display','block')">NOC Predictivo</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelRetiros"  style="cursor: pointer;" onClick="_display_panel('panelCargarRetiros');$('#panelLimpiarCarteraMain').css('display','block')">RETIROS</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelIVR"  style="cursor: pointer;" onClick="_display_panel('panelCargarIVR');$('#panelLimpiarCarteraMain').css('display','block')">IVR</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelCabeceras"  style="cursor: pointer;" onClick="_display_panel('panelCabecerasCarteraMain');$('#panelLimpiarCarteraMain').css('display','block')">Cabeceras</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelCorteFocalizado"  style="cursor: pointer;" onClick="_display_panel('panelCorteFocalizado');$('#panelLimpiarCarteraMain').css('display','block')">Corte Focalizado</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelFacturacion"  style="cursor: pointer;" onClick="_display_panel('panelFacturacion');$('#panelLimpiarCarteraMain').css('display','block')">Facturacion</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelCourier"  style="cursor: pointer;" onClick="_display_panel('panelCourier');$('#panelLimpiarCarteraMain').css('display','block')">Courier y Visitas</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelEstadoCuenta"  style="cursor: pointer;" onClick="_display_panel('panelEstadoCuenta');$('#panelLimpiarCarteraMain').css('display','block')">Estado de Cuenta</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelDeudaTotalC"  style="cursor: pointer;" onClick="_display_panel('panelDeudaTotalC');$('#panelLimpiarCarteraMain').css('display','block')">Deuda Total ( CENCOSUD )</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelDetalleM"  style="cursor: pointer;" onClick="_display_panel('panelDetalleM');$('#panelLimpiarCarteraMain').css('display','block')">Detalle ( MOVIL )</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelRP3"  style="cursor: pointer;" onClick="_display_panel('panelRP3');$('#panelLimpiarCarteraMain').css('display','block')">RP3</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelEditHeader"  style="cursor: pointer;" onClick="_display_panel('panelEditHeader');$('#panelLimpiarCarteraMain').css('display','block')">Editar Cabeceras</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelCruceTelefono"  style="cursor: pointer;" onClick="_display_panel('panelCruceTelefono');$('#panelLimpiarCarteraMain').css('display','block')">Cruce telefonos</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelNormalizacionTelefono"  style="cursor: pointer;" onClick="_display_panel('panelNormalizacionTelefono');$('#panelLimpiarCarteraMain').css('display','block')">Normalizacion Telefonos</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelCargaGestionAdicional"  style="cursor: pointer;" onClick="_display_panel('panelCargaGestionAdicional');$('#panelLimpiarCarteraMain').css('display','block')">Carga Gestion Adicional</a></td>
                                    </tr>
                                    <!-- Vic I -->
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelInsertarLlamadas"  style="cursor: pointer;" onClick="_display_panel('panelInsertarLlamadas');$('#panelLimpiarCarteraMain').css('display','block')">Insertar Llamadas</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelCargaCuota"  style="cursor: pointer;" onClick="_display_panel('panelCargaCuota');$('#panelLimpiarCarteraMain').css('display','block')">Carga Cuota</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelClienteContrato"  style="cursor: pointer;" onClick="_display_panel('panelClienteContrato');$('#panelLimpiarCarteraMain').css('display','block')">Unir Clientes y Contratos</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelFiadores"  style="cursor: pointer;" onClick="_display_panel('panelFiadores');$('#panelLimpiarCarteraMain').css('display','block')">Cargar Fiadores</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelCargaFacturacion"  style="cursor: pointer;" onClick="_display_panel('panelCargaFacturacion');$('#panelLimpiarCarteraMain').css('display','block')">Cargar Facturacion</a></td>
                                    </tr>                                    
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelCargaProvision"  style="cursor: pointer;" onClick="_display_panel('panelCargaProvision');$('#panelLimpiarCarteraMain').css('display','block')">Cargar Provision</a></td>
                                    </tr> 
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelCargaProvisionTotal"  style="cursor: pointer;" onClick="_display_panel('panelCargaProvisionTotal');$('#panelLimpiarCarteraMain').css('display','block')">Cargar Provision Total</a></td>
                                    </tr> 
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelMontoPagado"  style="cursor: pointer;" onClick="_display_panel('panelMontoPagado');$('#panelLimpiarCarteraMain').css('display','block')">Monto Pagado</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelNormalizarTelefono2"  style="cursor: pointer;" onClick="_display_panel('panelNormalizarTelefono2');$('#panelLimpiarCarteraMain').css('display','block')">Normalizar Telefono ( COVINOC - SAGA )</a></td>
                                    </tr>                                                                         
                                    <!-- Vic F -->
                                    <!-- poolpg -->
                                    <tr>
                                        <td><a class="text-blue" id="aDisplayPanelLoadCallCenter"  style="cursor: pointer;" onClick="_display_panel('panelLoadCallCenter');$('#panelLoadCallCenter').css('display','block')">Cargar Llamadas</a></td>
                                    </tr> 
                                    <!-- poolpg -->
                                </table>
                            </div>
                            <div class=" backPanel headerPanel">
                                <div class="backPanel iconPinUp" onclick="_slideBarLayer(this,'panelCalendario')" >Calendario</div>
                            </div>
                            <div align="center" id="panelCalendario" style="padding:3px 0;display:block;">
                                <div id="layerDatepicker"></div>
                            </div>
                        </div>
                    </td>
                    <td id="showhide" width="10px" class="showHide ui-widget-header">
                        <a onclick="_sliderFadeBarLayer()">
                            <div id="iconSlider" class="slider icon sliderIconUp"></div>
                        </a>
                    </td>
                    <td width="100%" valign="top">
                        <div id="cobrastHOME" class="ui-widget-content" style="width:100% !important; height:100%;border:0 none; " >
                            <input type="hidden" id="hdCodUsuario" name="hdCodUsuario" value="<?= $_SESSION['cobrast']['idusuario'] ?>" />
                            <input type="hidden" id="hdNomUsuario" name="hdNomUsuario" value="<?= $_SESSION['cobrast']['usuario'] ?>" />
                            <input type="hidden" id="hdCodServicio" name="hdCodServicio" value="<?= $_SESSION['cobrast']['idservicio'] ?>" />
                            <input type="hidden" id="hdCodUsuarioServicio" name="hdCodUsuarioServicio" value="<?= $_SESSION['cobrast']['idusuario_servicio'] ?>" />
                            <input type="hidden" id="hdNomServicio" name="hdNomServicio" value="<?= $_SESSION['cobrast']['servicio'] ?>" />
                            <!-- <div id="panelLimpiarCarteraMain" style="display:block;width:100%;border:0 none;" >
                                 <table>
                                         <tr>
                                         <td align="center">
                                    <div style="padding:10px 0;">
                                                 <table>
                                                         <tr>
                                                         <td valign="top">
                                                    <div>
                                                                 <table>
                                                                     <tr>
                                                                         <td align="right">Tipo Archivo</td>
                                                                         <td>
                                                                                 <select id="cbTipoArchivo" class="combo">
                                                                        <option value="cartera">Cartera</option>
                                                                                 <option value="pago">Pago</option>
                                                                                 <option value="centro_pago">Centro de pago</option>
                                                                             </select>
                                                                         </td>
                                                                         <td align="right">Elejir Archivo </td>
                                                                         <td><input type="file" id="uploadLimpiarCartera" name="uploadLimpiarCartera" ></td>
                                                                         <td colspan="2" align="center"><button id="btnLimpiarCartera" onClick="uploadLimpiarCartera()" >Limpiar Cartera</button></td>
                                                                     </tr>
                                                                 </table>
                                                             </div>
                                                         </td>
                                                     </tr>
                                                 </table>
                                             </div>
                                         </td>
                                     </tr>
                                 </table>
                             </div>-->
                            <div id="panelCargarCarteraMain" style="width: 819px; margin: 10px auto 0px; padding: 8px" >
                                
                                <?php   if( $_SESSION['cobrast']['idservicio'] == 10){

                                    ?>
                                <div  class="ui-widget-content ui-corner-bottom ui-corner-top" style=";width: 819px; margin: 0px auto;padding-bottom:10px" id="container_preparacion_covinoc">
                                    <div class=" border-radius-top pointer ui-widget-header title_prep_car_covinoc"> PREPARAR ARCHIVOS PLANOS </div>
                                    <div>
                                        <div class='covinoc'>Fecha de Proceso:  <input type ="text" id="txtFechaProcesoCovinoc" /></div>
                                    </div>
                                    <div>
                                        <div class='covinoc'>Archivo Plano de <b>Datos Demográficos</b></div>
                                        <div class='covinoc'><input type="file" id="fileDatosDemograficos" name="fileDatosDemograficos" /></div>
                                        <div class='covinoc_estado_carga'><input type="button" value="Acumular" id="btnAcuDatDem"></div>
                                    </div>
                                    <div>
                                        <div class='covinoc'>Archivo Plano de <b>Obligaciones en Mora</b></div>
                                        <div class='covinoc'><input type="file" id="fileObligacionesMora" name="fileObligacionesMora" /></div>
                                        <div class='covinoc_estado_carga'><input type="button" value="Acumular" id="btnAcuOblMor"></div>
                                    </div>
                                    <div>
                                        <div class='covinoc'>Archivo Plano de <b>Datos adicionales de Obligaciones</b></div>
                                        <div class='covinoc'><input type="file" id="fileDatosAdicObligaciones" name="fileDatosAdicObligaciones" /></div>
                                        <div class='covinoc_estado_carga'><input type="button" value="Acumular" id="btnAcuDatAdiObl"></div>
                                    </div>
                                    <div>
                                        <div class='covinoc'>Archivo Plano de <b>Otros Datos Adicionales de Obligaciones</b></div>
                                        <div class='covinoc'><input type="file" id="fileOtrosDatosAdicObligaciones" name="fileOtrosDatosAdicObligaciones" /></div>
                                        <div class='covinoc_estado_carga'><input type="button" value="Acumular" id="btnAcuOtrDatAdiObl"></div>
                                    </div>
                                    <div>
                                        <div class='covinoc'>Archivo Plano de <b>Datos Teléfonos</b></div>
                                        <div class='covinoc'><input type="file" id="fileDatosTelefonos" name="fileDatosTelefonos" /></div>
                                        <div class='covinoc_estado_carga'><input type="button" value="Acumular" id="btnAcuDatTel"></div>
                                    </div>
                                    <div>
                                        <div class='covinoc'>Archivo Plano de <b>Datos Direcciones</b></div>
                                        <div class='covinoc'><input type="file" id="fileDatosDirecciones" name="fileDatosDirecciones" /></div>
                                        <div class='covinoc_estado_carga'><input type="button" value="Acumular" id="btnAcuDatDir"></div>
                                    </div>
                                    <div>
                                        <div class='covinoc'>Archivo Plano de <b>Datos Emails</b></div>
                                        <div class='covinoc'><input type="file" id="fileDatosEmails" name="fileDatosEmails" /></div>
                                        <div class='covinoc_estado_carga'><input type="button" value="Acumular" id="btnAcuDatEma"></div>
                                    </div>
                                
                                    
                                </div>
                                <div class=" ui-widget-header ui-corner-bottom ui-corner-top" style="margin-bottom: 22px; margin-top: 22px;"></div>
                                <?php   }?>
                                <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                    <tr>
                                        <td>
                                            <div align="center">
                                                <table>
                                                    <tr>
                                                        <td valign="top">
                                                            <div>
                                                                <table>
                                                                    <tr>
                                                                        <td><input type="hidden" id="hddFile"/></td>
                                                                        <td align="right">Campa&ntilde;a</td>
                                                                        <td><select id="cboCampania" class="combo" onchange="listar_carteras(this.value,[{id:'cboCarteraActualizar'}])" ><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right">Nombre de cartera</td>
                                                                        <td><input type="text" id="txtNombreCartera" class="cajaForm" /></td>
                                                                        <td align="right">Cabeceras</td>
                                                                        <td><select id="cbCabecerasCarteraMain" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right">Caracter Separador</td>
                                                                        <td>
                                                                            <select id="txtCaracterSeparador" class="combo">
                                                                                <option value="|">|</option>
                                                                                <option value="^^">^^</option>
                                                                                <option selected="selected" value="tab">TAB</option>
                                                                                <!--
                                                                                <option value="|">|</option>
                                                                                <option value=";">;</option>
                                                                                <option value="tab">TAB</option>
                                                                                -->
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div>
                                                                <table>
                                                                    <tr>
                                                                        <td align="right">Modo</td>
                                                                        <td><select id="cboModoProceso" class="combo"><option value="0">--Seleccione--</option><option value="agregar_dividir">Agregar cabeceras y dividir</option><option value="agregar">Agregar cabeceras</option></select></td>
                                                                        <td align="right">Proceso</td>
                                                                        <td><select id="cboTipoProceso" class="combo"><option value="carga">Carga</option><option value="actualizacion">Actualizacion</option><option value="agregar">Agregar</option></select></td>
                                                                        <td align="right">Cartera Actualizar</td>
                                                                        <td><select id="cboCarteraActualizar" onchange=" if( this.value!= 0 ){ $('#txtNombreCartera').val( $(this).find('option:selected').text() ); } " class="combo"><option value="0">--Seleccione--</option></select></select></td>
                                                                        <!--<td align="right">Elejir Archivo(s) </td>-->
                                                                        <!--<td><input type="file" id="uploadFileCartera" name="uploadFileCartera" ></td>-->
                                                                        <td colspan="2" align="center">
                                                                            <div id="file_upload" class="file_upload">
                                                                                <form action="" method="POST" enctype="multipart/form-data" class="file_upload">
                                                                                    <input type="hidden" name="error" value="0" id="loadHeaderError" />
                                                                                    <input type="hidden" name="error" value="" id="loadHeaderErrorMsg" />
                                                                                    <input type="file" name="file[]" multiple="">
                                                                                        <button type="submit">Upload</button>
                                                                                        <div class="file_upload_label">Seleccionar Archivo(s)</div>
                                                                                </form>
                                                                            </div>
                                                                            <!--<button class="ui-state-default ui-corner-all" id="btnUploadFile" onClick="upLoadFile()">Levantar Informacion</button>-->
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div>
                                                                <table>
                                                                    <tr>
                                                                        <td align="right">Plantillas</td>
                                                                        <td><select onchange="parser_data_template(this.value)" class="combo" id="cbPlantillasCargaCarteraMain"><option value="0">--Seleccione--</option></select></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <table id="files">
                                                                <tbody>
                                                                    <tr class="file_upload_template" style="display:none;">
                                                                        <td class="file_upload_preview"></td>
                                                                        <td class="file_name"></td>
                                                                        <td class="file_size"></td>
                                                                        <td class="file_upload_progress"><div></div></td>
                                                                        <td class="file_upload_start"><button>Start</button></td>
                                                                        <td class="file_upload_cancel"><button>Cancel</button></td>
                                                                    </tr>
                                                                    <tr class="file_download_template" style="display:none;">
                                                                        <td class="file_download_preview"></td>
                                                                        <td class="file_name"><a></a></td>
                                                                        <td class="file_size"></td>
                                                                        <td class="file_download_delete" colspan="3"><button>Delete</button></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <div class="file_upload_overall_progress"><div style="display: none; " class="ui-progressbar ui-widget ui-widget-content ui-corner-all" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="ui-progressbar-value ui-widget-header ui-corner-left" style="display: none; width: 0%; "></div></div></div>
                                                            <div class="file_upload_buttons">
                                                                    <!--<button class="file_upload_start ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" role="button" aria-disabled="false"><span class="ui-button-icon-primary ui-icon ui-icon-circle-arrow-e"></span><span class="ui-button-text">Start All</span></button> -->
                                                                    <!--<button class="file_upload_cancel ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" role="button" aria-disabled="false"><span class="ui-button-icon-primary ui-icon ui-icon-cancel"></span><span class="ui-button-text">Cancel All</span></button> -->
                                                                    <!--<button class="file_download_delete ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" role="button" aria-disabled="false"><span class="ui-button-icon-primary ui-icon ui-icon-trash"></span><span class="ui-button-text">Delete All</span></button>-->
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div style="padding:10px 0;">
                                                <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                    <tr>
                                                        <td>
                                                            <div style="padding:5px;" align="center">
                                                                <table>
                                                                    <tr>
                                                                        <td>
                                                                            <!--<button class="ui-state-default ui-corner-all" onClick="generateCartera()" id="btnGenerateTable" >Carga Manual de Cartera</button>-->
                                                                            <button class="ui-state-default ui-corner-all" onClick="generateCartera()" id="btnGenerateTable" >Cargar Cartera</button>
                                                                        </td>
                                                                        <!--<td>
                                                                            <button class="ui-state-default ui-corner-all" onClick="generateTableAutomatic()" id="btnGenerateTableAutomatic" >Carga Automatica de Cartera</button>
                                                                        </td>-->
                                                                        <td>
                                                                            <button class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all" onclick="cancel_carga_cartera()"><span class="ui-button-text">Cancelar</span></button>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <table>
                                                                        <tr>
                                                                                <td><input type="radio" checked="checked" name="rdTipoCargaCarteraMain" value="1" ></td>
                                                                                <td>Todo</td>
                                                                                <td><input type="radio" name="rdTipoCargaCarteraMain" value="2" ></td>
                                                                                <td>Solo Lima y Callao</td>
                                                                                <td><input type="radio" name="rdTipoCargaCarteraMain" value="3" ></td>
                                                                                <td>Solo Provincia</td>
                                                                                <td style="width:20px;"></td>
                                                                                <td>Cabecera departamento</td>
                                                                                <td><select id="cbCabeceraDepartamentoCargaCarteraMain" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        </tr>
                                                                </table>
                                                                <table style="width:99%;">
                                                                    <tr>
                                                                        <td align="center" >
                                                                            <div id="selectHeaderNotCarteraMain" style="display:none" align="center" >
                                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                                    <tr class="ui-state-default" >
                                                                                        <td style="padding:3px 15px;border:1px solid #CDC3B7;width:730px;" ><strong>Nuevas Cabeceras</strong></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="center" id="TDnewHeaderCarteraMain" style="border-left:1px solid #CDC3B7; border-right:1px solid #CDC3B7; border-bottom:1px solid #CDC3B7;color:#F00;width:730px;">

                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td id="selectHeader">
                                                                            <div align="center" class="ui-widget-content" id="layerTabCargaCarteraCliente" style="display:block;padding:5px 10px;">
                                                                                <table>
                                                                                    <tr>
                                                                                        <td align="right">Codigo</td>
                                                                                        <td><select id="codigo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_codigo" value="CODIGO" /></td>
                                                                                        <td align="right" >Nombre</td>
                                                                                        <td><select id="nombre" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_nombre" value="NOMBRE" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right">Paterno</td>
                                                                                        <td><select id="paterno" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_paterno" value="PATERNO" /></td>
                                                                                        <td align="right" >Materno</td>
                                                                                        <td><select id="materno" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_materno" value="MATERNO" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right" >Numero Documento</td>
                                                                                        <td><select id="numero_documento" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_numero_documento" value="NUMERO DOCUMENTO" /></td>
                                                                                        <td align="right">Tipo Documento</td>
                                                                                        <td><select id="tipo_documento" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_tipo_documento" value="TIPO DOCUMENTO" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right" >Razon Social</td>
                                                                                        <td><select id="razon_social" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_razon_social" value="RAZON SOCIAL" /></td>
                                                                                        <td align="right">Tipo Persona</td>
                                                                                        <td><select id="tipo_persona" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_tipo_persona" value="TIPO PERSONA" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right" >Contrato</td>
                                                                                        <td><select id="contrato" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_contrato" value="CONTRATO" /></td>
                                                                                        <td align="right">Tipo Adjudicacion</td>
                                                                                        <td><select id="tipo_adjudicacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_tipo_adjudicacion" value="TIPO ADJUDICACION" /></td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                            <div align="center" class="ui-widget-content" id="layerTabCargaCarteraCartera" style="display:none;padding:5px 10px;">
                                                                                <table>
                                                                                    <tr>
                                                                                        <td align="right">Nombre</td>
                                                                                        <td><select id="nombre_cartera" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_nombre_cartera" value="NOMBRE" /></td>
                                                                                        <td align="right" >Fecha Inicio</td>
                                                                                        <td><select id="fecha_inicio" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_fecha_inicio" value="FECHA INICIO" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right">Fecha Fin</td>
                                                                                        <td><select id="fecha_fin" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_fecha_fin" value="FECHA FIN" /></td>
                                                                                        <td align="right" >Evento</td>
                                                                                        <td><select id="evento" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_evento" value="EVENTO" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right" >Cluster</td>
                                                                                        <td><select id="cluster" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_cluster" value="CLUSTER" /></td>
                                                                                        <td align="right">Segmento</td>
                                                                                        <td><select id="segmento" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_segmento" value="SEGMENTO" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right" >Negocio</td>
                                                                                        <td><select id="negocio" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_negocio" value="NEGOCIO" /></td>
                                                                                        <td align="right" >Semana</td>
                                                                                        <td><select id="semana" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_semana" value="SEMANA" /></td>
                                                                                    </tr>
                                                                                    <tr>                                                                                        
                                                                                        <td align="right" >Tipo Cobranza</td>
                                                                                        <td><select id="tipo_cobranza" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_tipo_cobranza" value="TIPO COBRANZA" /></td>
                                                                                        <td align="right" >Valor_Certificado</td>
                                                                                        <td><select id="valor_certificado" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_valor_certificado" value="VALOR CERTIFICADO" /></td>
                                                                                    </tr>
                                                                                    <tr>                                                                                        
                                                                                        <td align="right" >Contrato</td>
                                                                                        <td><select id="contrato" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_contrato" value="CONTRATO" /></td>
                                                                                        <td align="right" >Tipo_cliente</td>
                                                                                        <td><select id="tipo_cliente" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_tipo_cliente" value="CONTRATO" /></td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                            <div align="center" class="ui-widget-content" id="layerTabCargaCarteraCuenta" style="display:none;padding:5px 10px;">
                                                                                <table>
                                                                                    <tr>
                                                                                        <td align="right" >Numero de Cuenta</td>
                                                                                        <td><select id="numero_cuenta" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_numero_cuenta" value="NUMERO CUENTA" /></td>
                                                                                        <td align="right" >Moneda</td>
                                                                                        <td><select id="moneda" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_moneda" value="MONEDA" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right" >Total_deuda</td>
                                                                                        <td><select id="total_deuda" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_total_deuda" value="TOTAL DEUDA" /></td>
                                                                                        <td align="right" >Saldo Capital</td>
                                                                                        <td><select id="saldo_capital" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_saldo_capital" value="SALDO CAPITAL" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right" >Monto Mora</td>
                                                                                        <td><select id="monto_mora" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_monto_mora" value="MONTO MORA" /></td>
                                                                                        <td align="right" >Telefono</td>
                                                                                        <td><select id="telefono" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_telefono" value="TELEFONO" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right" >Total Comision</td>
                                                                                        <td><select id="total_comision" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_total_comision" value="TOTAL COMISION" /></td>
                                                                                        <td align="right" >Producto</td>
                                                                                        <td><select id="producto" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_producto" value="PRODUCTO" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right" >Monto Pagado</td>
                                                                                        <td><select id="monto_pagado" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_monto_pagado" value="MONTO PAGADO" /></td>
                                                                                        <td align="right">Inscripcion</td>
                                                                                        <td><select id="inscripcion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_inscripcion" value="INSCRIPCION" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right">Negocio</td>
                                                                                        <td><select id="negocio" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_negocio" value="NEGOCIO" /></td>
                                                                                        <td align="right">Sub Negocio</td>
                                                                                        <td><select id="subnegocio" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_subnegocio" value="SUB NEGOCIO" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right">Tramo</td>
                                                                                        <td><select id="tramo_cuenta" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_tramo_cuenta" value="TRAMO" /></td>
                                                                                        <td align="right">Cuota Mensual</td>
                                                                                        <td><select id="cuota_mensual" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_cuota_mensual" value="CUOTA MENSUAL" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right">seguros</td>
                                                                                        <td><select id="seguros" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_seguros" value="SEGUROS" /></td>
                                                                                        <td align="right">Otros</td>
                                                                                        <td><select id="otros" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_otros" value="OTROS" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right">Gestor Cobranza</td>
                                                                                        <td><select id="gestor_cobranza" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_gestor_cobranza" value="GESTOR COBRANZA" /></td>
                                                                                        <td align="right">Anexo</td>
                                                                                        <td><select id="anexo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_anexo" value="ANEXO" /></td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                            <div align="center" class="ui-widget-content" id="layerTabCargaCarteraOperacion" style="display:none;padding:5px 10px;">
                                                                                <table>
                                                                                    <tr>
                                                                                        <td align="right" >Codigo de Operacion</td>
                                                                                        <td><select id="codigo_operacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_codigo_operacion" value="CODIGO OPERACION" /></td>
                                                                                        <td align="right">Moneda</td>
                                                                                        <td><select id="moneda" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_moneda" value="MONEDA" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right">Tramo</td>
                                                                                        <td><select id="tramo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_tramo" value="TRAMO" /></td>
                                                                                        <td align="right">Refinanciamiento</td>
                                                                                        <td><select id="refinanciamiento" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_refinanciamiento" value="REFINANCIAMIENTO" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right" >Dia Mora</td>
                                                                                        <td><select id="dias_mora" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_dias_mora" value="DIAS MORA" /></td>
                                                                                        <td align="right">Numero de Cuotas</td>
                                                                                        <td><select id="numero_cuotas" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_numero_cuotas" value="NUMERO DE CUOTAS" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right">Numero de Cuotas Pagadas</td>
                                                                                        <td><select id="numero_cuotas_pagadas" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_numero_cuotas_pagadas" value="NUMERO CUOTAS PAGADAS" /></td>
                                                                                        <td align="right">Total Deuda</td>
                                                                                        <td><select id="total_deuda" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_total_deuda" value="TOTAL DEUDA" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right">Total Deuda Soles</td>
                                                                                        <td><select id="total_deuda_soles" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_total_deuda_soles" value="TOTAL DEUDA SOLES" /></td>
                                                                                        <td align="right">Total Deuda Dolares</td>
                                                                                        <td><select id="total_deuda_dolares" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_total_deuda_dolares" value="TOTAL DEUDA DOLARES" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right">Monto Mora</td>
                                                                                        <td><select id="monto_mora" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_monto_mora" value="MONTO MORA" /></td>
                                                                                        <td align="right">Monto Mora Soles</td>
                                                                                        <td><select id="monto_mora_soles" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_monto_mora_soles" value="MONTO MORA SOLES" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right">Monto Mora Dolares</td>
                                                                                        <td><select id="monto_mora_dolares" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_monto_mora_dolares" value="MONTO MORA DOLARES" /></td>
                                                                                        <td align="right">Saldo Capital</td>
                                                                                        <td><select id="saldo_capital" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_saldo_capital" value="SALDO CAPITAL" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right">Saldo Capital Soles</td>
                                                                                        <td><select id="saldo_capital_soles" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_saldo_capital_soles" value="SALDO CAPITAL SOLES" /></td>
                                                                                        <td align="right">Saldo Capital Dolares</td>
                                                                                        <td><select id="saldo_capital_dolares" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_saldo_capital_dolares" value="SALDO CAPITAL DOLARES" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right">Fecha Asignacion</td>
                                                                                        <td><select id="fecha_asignacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_fecha_asignacion" value="FECHA ASIGNACION" /></td>
                                                                                        <td align="right">Descripcion servicio</td>
                                                                                        <td><select id="descripcion_servicio" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_descripcion_servicio" value="DESCRIPCION SERVICIO" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right">Descripcion Fogapi</td>
                                                                                        <td><select id="descripcion_fogapi" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_descripcion_fogapi" value="DESCRIPCION FOGAPI" /></td>
                                                                                        <td align="right">Nombre Agencia</td>
                                                                                        <td><select id="nombre_agencia" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_nombre_agencia" value="NOMBRE AGENCIA" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right">CLAS SBS</td>
                                                                                        <td><select id="clas_sbs" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_clas_sbs" value="CLAS SBS" /></td>
                                                                                        <td align="right">CAT SBS</td>
                                                                                        <td><select id="cat_sbs" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_cat_sbs" value="CAT SBS" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right">Mora Contable</td>
                                                                                        <td><select id="mora_contable" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_mora_contable" value="MORA CONTABLE" /></td>
                                                                                        <td align="right">Comision</td>
                                                                                        <td><select id="comision" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_comision" value="COMISION" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right">Fecha Vencimiento</td>
                                                                                        <td><select id="fecha_vencimiento" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_fecha_vencimiento" value="FECHA VENCIMIENTO" /></td>
                                                                                        <td align="right" >Monto Pagado</td>
                                                                                        <td><select id="monto_pagado" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_monto_pagado" value="MONTO PAGADO" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right">Fecha Alta</td>
                                                                                        <td><select id="fecha_alta" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_fecha_alta" value="FECHA ALTA" /></td>
                                                                                        <td align="right">Fecha Baja</td>
                                                                                        <td><select id="fecha_baja" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_fecha_baja" value="FECHA BAJA" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right">Fecha Ciclo</td>
                                                                                        <td><select id="fecha_ciclo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_fecha_ciclo" value="FECHA CICLO" /></td>
                                                                                        <td align="right">Fecha Emision</td>
                                                                                        <td><select id="fecha_emision" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_fecha_emision" value="FECHA EMISION" /></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right">Marca_cat</td>
                                                                                        <td><select id="marca_cat" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><input type="text" id="txt_marca_cat" value="MARCA CAT" /></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                            <div align="center" class="ui-widget-content" id="layerTabCargaCarteraTelefono" style="display:none;padding:5px 10px;">
                                                                                <div onclick="_slide2(this,'PanelTableTelefonoPredeterminado')">
                                                                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                        <tr>
                                                                                            <td style="width:25px; height:25px;">
                                                                                                <div class="backPanel iconPinBlueDown" ></div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:ltr;">
                                                                                                    <a class="text-blue">Predeterminado</a>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:rtl;">
                                                                                                    <span class="text-gris"></span>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div id="PanelTableTelefonoPredeterminado" style="display:block;padding:5px 10px;" title="telefono_predeterminado" align="center">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td align="right">Telefono</td>
                                                                                            <td><select id="numero" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Anexo</td>
                                                                                            <td><select id="anexo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Observacion</td>
                                                                                            <td><select id="observacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Referencia</td>
                                                                                            <td><select id="referencia" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div onclick="_slide2(this,'PanelTableTelefonoDomicilio')">
                                                                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                        <tr>
                                                                                            <td style="width:25px; height:25px;">
                                                                                                <div class="backPanel iconPinBlueDown" ></div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:ltr;">
                                                                                                    <a class="text-blue">Domicilio</a>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:rtl;">
                                                                                                    <span class="text-gris"></span>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div id="PanelTableTelefonoDomicilio" style="display:block;padding:5px 10px;" title="telefono_domicilio" align="center">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td align="right">Telefono</td>
                                                                                            <td><select id="numero" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Anexo</td>
                                                                                            <td><select id="anexo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Observacion</td>
                                                                                            <td><select id="observacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Referencia</td>
                                                                                            <td><select id="referencia" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div onclick="_slide2(this,'PanelTableTelefonoOficina')">
                                                                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                        <tr>
                                                                                            <td style="width:25px; height:25px;">
                                                                                                <div class="backPanel iconPinBlueDown" ></div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:ltr;">
                                                                                                    <a class="text-blue">Oficina</a>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:rtl;">
                                                                                                    <span class="text-gris"></span>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div id="PanelTableTelefonoOficina" style="display:block;padding:5px 10px;" title="telefono_oficina" align="center">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td align="right">Telefono</td>
                                                                                            <td><select id="numero" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Anexo</td>
                                                                                            <td><select id="anexo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Observacion</td>
                                                                                            <td><select id="observacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Referencia</td>
                                                                                            <td><select id="referencia" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div onclick="_slide2(this,'PanelTableTelefonoNegocio')">
                                                                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                        <tr>
                                                                                            <td style="width:25px; height:25px;">
                                                                                                <div class="backPanel iconPinBlueDown" ></div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:ltr;">
                                                                                                    <a class="text-blue">Negocio</a>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:rtl;">
                                                                                                    <span class="text-gris"></span>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div id="PanelTableTelefonoNegocio" style="display:block;padding:5px 10px;" title="telefono_negocio" align="center">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td align="right">Telefono</td>
                                                                                            <td><select id="numero" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Anexo</td>
                                                                                            <td><select id="anexo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Observacion</td>
                                                                                            <td><select id="observacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Referencia</td>
                                                                                            <td><select id="referencia" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div onclick="_slide2(this,'PanelTableTelefonoLaboral')">
                                                                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                        <tr>
                                                                                            <td style="width:25px; height:25px;">
                                                                                                <div class="backPanel iconPinBlueDown" ></div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:ltr;">
                                                                                                    <a class="text-blue">Laboral</a>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:rtl;">
                                                                                                    <span class="text-gris"></span>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div id="PanelTableTelefonoLaboral" style="display:block;padding:5px 10px;" title="telefono_laboral" align="center">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td align="right">Telefono</td>
                                                                                            <td><select id="numero" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Anexo</td>
                                                                                            <td><select id="anexo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Observacion</td>
                                                                                            <td><select id="observacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Referencia</td>
                                                                                            <td><select id="referencia" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div onclick="_slide2(this,'PanelTableTelefonoFamiliar')">
                                                                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                        <tr>
                                                                                            <td style="width:25px; height:25px;">
                                                                                                <div class="backPanel iconPinBlueDown" ></div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:ltr;">
                                                                                                    <a class="text-blue">Familiar</a>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:rtl;">
                                                                                                    <span class="text-gris"></span>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div id="PanelTableTelefonoFamiliar" style="display:block;padding:5px 10px;" title="telefono_familiar" align="center">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td align="right">Telefono</td>
                                                                                            <td><select id="numero" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Anexo</td>
                                                                                            <td><select id="anexo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Observacion</td>
                                                                                            <td><select id="observacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Referencia</td>
                                                                                            <td><select id="referencia" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div onclick="_slide2(this,'PanelTableTelefonoPersonal')">
                                                                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                        <tr>
                                                                                            <td style="width:25px; height:25px;">
                                                                                                <div class="backPanel iconPinBlueDown" ></div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:ltr;">
                                                                                                    <a class="text-blue">Personal</a>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:rtl;">
                                                                                                    <span class="text-gris"></span>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div id="PanelTableTelefonoPersonal" style="display:block;padding:5px 10px;" title="telefono_personal" align="center">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td align="right">Telefono</td>
                                                                                            <td><select id="numero" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Anexo</td>
                                                                                            <td><select id="anexo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Observacion</td>
                                                                                            <td><select id="observacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Referencia</td>
                                                                                            <td><select id="referencia" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                              <div onclick="_slide2(this,'PanelTableTelefonoTercero')">
                                                                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                        <tr>
                                                                                            <td style="width:25px; height:25px;">
                                                                                                <div class="backPanel iconPinBlueDown" ></div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:ltr;">
                                                                                                    <a class="text-blue">Tercero</a>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:rtl;">
                                                                                                    <span class="text-gris"></span>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div id="PanelTableTelefonoTercero" style="display:block;padding:5px 10px;" title="telefono_tercero" align="center">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td align="right">Telefono</td>
                                                                                            <td><select id="numero" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Anexo</td>
                                                                                            <td><select id="anexo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Observacion</td>
                                                                                            <td><select id="observacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Referencia</td>
                                                                                            <td><select id="referencia" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div onclick="_slide2(this,'PanelTableTelefonoConyuge')">
                                                                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                        <tr>
                                                                                            <td style="width:25px; height:25px;">
                                                                                                <div class="backPanel iconPinBlueDown" ></div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:ltr;">
                                                                                                    <a class="text-blue">Conyuge</a>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:rtl;">
                                                                                                    <span class="text-gris"></span>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div id="PanelTableTelefonoConyuge" style="display:block;padding:5px 10px;" title="telefono_conyuge" align="center">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td align="right">Telefono</td>
                                                                                            <td><select id="numero" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Anexo</td>
                                                                                            <td><select id="anexo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Observacion</td>
                                                                                            <td><select id="observacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Referencia</td>
                                                                                            <td><select id="referencia" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div onclick="_slide2(this,'PanelTableTelefonoAval')">
                                                                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                        <tr>
                                                                                            <td style="width:25px; height:25px;">
                                                                                                <div class="backPanel iconPinBlueDown" ></div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:ltr;">
                                                                                                    <a class="text-blue">Aval</a>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:rtl;">
                                                                                                    <span class="text-gris"></span>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div id="PanelTableTelefonoAval" style="display:block;padding:5px 10px;" title="telefono_aval" align="center">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td align="right">Telefono</td>
                                                                                            <td><select id="numero" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Anexo</td>
                                                                                            <td><select id="anexo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Observacion</td>
                                                                                            <td><select id="observacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Referencia</td>
                                                                                            <td><select id="referencia" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                            <div align="center" class="ui-widget-content" id="layerTabCargaCarteraDireccion" style="display:none;padding:5px 10px;">
                                                                                <div onclick="_slide2(this,'PanelTableDireccionPredeterminado')">
                                                                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                        <tr>
                                                                                            <td style="width:25px; height:25px;">
                                                                                                <div class="backPanel iconPinBlueDown" ></div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:ltr;">
                                                                                                    <a class="text-blue">Predeterminado</a>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:rtl;">
                                                                                                    <span class="text-gris"></span>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div id="PanelTableDireccionPredeterminado" style="display:block;padding:5px 10px;" title="direccion_predeterminado" align="center">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td align="right">Direccion</td>
                                                                                            <td><select id="direccion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Direccion Referencia</td>
                                                                                            <td><select id="referencia" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Ubigeo</td>
                                                                                            <td><select id="ubigeo" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td align="right">Calle</td>
                                                                                            <td><select id="calle" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Numero</td>
                                                                                            <td><select id="numero" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Observacion</td>
                                                                                            <td><select id="observacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td align="right">Dpto</td>
                                                                                            <td><select id="departamento" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Prov</td>
                                                                                            <td><select id="provincia" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Distrito</td>
                                                                                            <td><select id="distrito" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td align="right">Region</td>
                                                                                            <td><select id="region" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Zona</td>
                                                                                            <td><select id="zona" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Codigo Postal</td>
                                                                                            <td><select id="codigo_postal" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div onclick="_slide2(this,'PanelTableDireccionDomicilio')">
                                                                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                        <tr>
                                                                                            <td style="width:25px; height:25px;">
                                                                                                <div class="backPanel iconPinBlueDown" ></div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:ltr;">
                                                                                                    <a class="text-blue">Domicilio</a>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:rtl;">
                                                                                                    <span class="text-gris"></span>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div id="PanelTableDireccionDomicilio" style="display:block;padding:5px 10px;" title="direccion_domicilio" align="center">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td align="right">Direccion</td>
                                                                                            <td><select id="direccion" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Direccion Referencia</td>
                                                                                            <td><select id="referencia" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Ubigeo</td>
                                                                                            <td><select id="ubigeo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td align="right">Calle</td>
                                                                                            <td><select id="calle" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Numero</td>
                                                                                            <td><select id="numero" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Observacion</td>
                                                                                            <td><select id="observacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td align="right">Dpto</td>
                                                                                            <td><select id="departamento" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Prov</td>
                                                                                            <td><select id="provincia" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Distrito</td>
                                                                                            <td><select id="distrito" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td align="right">Region</td>
                                                                                            <td><select id="region" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Zona</td>
                                                                                            <td><select id="zona" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Codigo Postal</td>
                                                                                            <td><select id="codigo_postal" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div onclick="_slide2(this,'PanelTableDireccionOficina')">
                                                                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                        <tr>
                                                                                            <td style="width:25px; height:25px;">
                                                                                                <div class="backPanel iconPinBlueDown" ></div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:ltr;">
                                                                                                    <a class="text-blue">Oficina</a>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:rtl;">
                                                                                                    <span class="text-gris"></span>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div id="PanelTableDireccionOficina" style="display:block;padding:5px 10px;" title="direccion_oficina" align="center">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td align="right">Direccion</td>
                                                                                            <td><select id="direccion" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Direccion Referencia</td>
                                                                                            <td><select id="referencia" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Ubigeo</td>
                                                                                            <td><select id="ubigeo" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td align="right">Calle</td>
                                                                                            <td><select id="calle" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Numero</td>
                                                                                            <td><select id="numero" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Observacion</td>
                                                                                            <td><select id="observacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td align="right">Dpto</td>
                                                                                            <td><select id="departamento" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Prov</td>
                                                                                            <td><select id="provincia" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Distrito</td>
                                                                                            <td><select id="distrito" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td align="right">Region</td>
                                                                                            <td><select id="region" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Zona</td>
                                                                                            <td><select id="zona" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Codigo Postal</td>
                                                                                            <td><select id="codigo_postal" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div onclick="_slide2(this,'PanelTableDireccionNegocio')">
                                                                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                        <tr>
                                                                                            <td style="width:25px; height:25px;">
                                                                                                <div class="backPanel iconPinBlueDown" ></div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:ltr;">
                                                                                                    <a class="text-blue">Negocio</a>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:rtl;">
                                                                                                    <span class="text-gris"></span>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div id="PanelTableDireccionNegocio" style="display:block;padding:5px 10px;" title="direccion_negocio" align="center">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td align="right">Direccion</td>
                                                                                            <td><select id="direccion" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Direccion Referencia</td>
                                                                                            <td><select id="referencia" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Ubigeo</td>
                                                                                            <td><select id="ubigeo" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td align="right">Calle</td>
                                                                                            <td><select id="calle" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Numero</td>
                                                                                            <td><select id="numero" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Observacion</td>
                                                                                            <td><select id="observacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td align="right">Dpto</td>
                                                                                            <td><select id="departamento" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Prov</td>
                                                                                            <td><select id="provincia" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Distrito</td>
                                                                                            <td><select id="distrito" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td align="right">Region</td>
                                                                                            <td><select id="region" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Zona</td>
                                                                                            <td><select id="zona" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Codigo Postal</td>
                                                                                            <td><select id="codigo_postal" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div onclick="_slide2(this,'PanelTableDireccionLaboral')">
                                                                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                        <tr>
                                                                                            <td style="width:25px; height:25px;">
                                                                                                <div class="backPanel iconPinBlueDown" ></div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:ltr;">
                                                                                                    <a class="text-blue">Laboral</a>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                <div style="direction:rtl;">
                                                                                                    <span class="text-gris"></span>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div id="PanelTableDireccionLaboral" style="display:block;padding:5px 10px;" title="direccion_laboral" align="center">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td align="right">Direccion</td>
                                                                                            <td><select id="direccion" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Direccion Referencia</td>
                                                                                            <td><select id="referencia" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Ubigeo</td>
                                                                                            <td><select id="ubigeo" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td align="right">Calle</td>
                                                                                            <td><select id="calle" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Numero</td>
                                                                                            <td><select id="numero" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Observacion</td>
                                                                                            <td><select id="observacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td align="right">Dpto</td>
                                                                                            <td><select id="departamento" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Prov</td>
                                                                                            <td><select id="provincia" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Distrito</td>
                                                                                            <td><select id="distrito" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td align="right">Region</td>
                                                                                            <td><select id="region" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Zona</td>
                                                                                            <td><select id="zona" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="right">Codigo Postal</td>
                                                                                            <td><select id="codigo_postal" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                            <div align="center" class="ui-widget-content" id="layerTabCargaCarteraDatosAdicionales" style="display:none;padding:5px 10px;">
                                                                                <table>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <table>
                                                                                                <tr><td><button id="btnLimpiar" onClick="clean_adicionales()" >Limpiar</button></td></tr>
                                                                                                <tr><td><input type="text" id="txt_adicionales_cartera" /></td></tr>
                                                                                                <tr><td align="center"><select style="width:150px;" class="combo" size="10" id="adicionales_cartera"></select></td></tr>
                                                                                                <tr><td><button class="btn" onclick="agregar_adicional_cliente('cliente')">Agregar Cliente</button></td></tr>
                                                                                                <tr><td><button class="btn" onclick="agregar_adicional_cliente('cuenta')">Agregar Cuenta</button></td></tr>
                                                                                                <tr><td><button class="btn" onclick="agregar_adicional_cliente('detalle_cuenta')">Agregar Detalle Cuenta</button></td></tr>
                                                                                            </table>
                                                                                        </td>
                                                                                        <td>
                                                                                            <table cellspacing="10">
                                                                                                <tr>
                                                                                                    <td><div class="ui-corner-all ui-widget-header" style="padding:2px 5px;">Cliente</div></td>
                                                                                                    <td><div class="ui-corner-all ui-widget-header" style="padding:2px 5px;">Cuenta</div></td>
                                                                                                    <td><div class="ui-corner-all ui-widget-header" style="padding:2px 5px;">Operacion</div></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td align="center"><select class="combo" size="10" id="ca_datos_adicionales_cliente"></select></td>
                                                                                                    <td align="center"><select class="combo" size="10" id="ca_datos_adicionales_cuenta"></select></td>
                                                                                                    <td align="center"><select class="combo" size="10" id="ca_datos_adicionales_detalle_cuenta"></select></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td align="center"ç>
                                                                                                        <button class="ui-state-default ui-corner-all" onClick="remove_adicional_cartera('cliente')" ><span class="ui-icon ui-icon-trash"></span></button>
                                                                                                    </td>
                                                                                                    <td align="center">
                                                                                                        <button class="ui-state-default ui-corner-all" onClick="remove_adicional_cartera('cuenta')"><span class="ui-icon ui-icon-trash"></span></button>
                                                                                                    </td>
                                                                                                    <td align="center">
                                                                                                        <button class="ui-state-default ui-corner-all" onClick="remove_adicional_cartera('detalle_cuenta')"><span class="ui-icon ui-icon-trash"></span></button>
                                                                                                    </td>

                                                                                                </tr>
                                                                                            </table>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <table style="width:99%;" cellpadding="0" cellspacing="0" border="0" >
                                                                    <tr>
                                                                        <td class="lineTab ui-widget-header"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <div style="margin-left:100px;">
                                                                                <table cellpadding="0" cellspacing="0" border="0" id="TabListCargaCartera" >
                                                                                    <tr>
                                                                                        <td><div id="TabListCliente" onClick="_activeTabLayer('TabListCargaCartera','TabList',this,'selectHeader','layerTabCargaCartera','layerTabCargaCarteraCliente')" class="itemTabActive border-radius-bottom pointer ui-widget-header" style="margin: 0px 1px 0pt 0pt;"><div class="text-white">Cliente</div></div></td>
                                                                                        <td><div id="TabListCartera" onClick="_activeTabLayer('TabListCargaCartera','TabList',this,'selectHeader','layerTabCargaCartera','layerTabCargaCarteraCartera')" class="itemTab border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;"><div class="AitemTab">Cartera</div></div></td>
                                                                                        <td><div id="TabListCuenta" onClick="_activeTabLayer('TabListCargaCartera','TabList',this,'selectHeader','layerTabCargaCartera','layerTabCargaCarteraCuenta')" class="itemTab border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;"><div class="AitemTab">Cuenta</div></div></td>
                                                                                        <td><div id="TabListOperacion" onClick="_activeTabLayer('TabListCargaCartera','TabList',this,'selectHeader','layerTabCargaCartera','layerTabCargaCarteraOperacion')" class="itemTab border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;"><div class="AitemTab">Operacion</div></div></td>
                                                                                        <td><div id="TabListTelefono" onClick="_activeTabLayer('TabListCargaCartera','TabList',this,'selectHeader','layerTabCargaCartera','layerTabCargaCarteraTelefono')" class="itemTab border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;"><div class="AitemTab">Telefono</div></div></td>
                                                                                        <td><div id="TabListDireccion" onClick="_activeTabLayer('TabListCargaCartera','TabList',this,'selectHeader','layerTabCargaCartera','layerTabCargaCarteraDireccion')" class="itemTab border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;"><div class="AitemTab">Direccion</div></div></td>
                                                                                        <td><div id="TabListDatosAdicionales" onClick="_activeTabLayer('TabListCargaCartera','TabList',this,'selectHeader','layerTabCargaCartera','layerTabCargaCarteraDatosAdicionales')" class="itemTab border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;"><div class="AitemTab">Datos Adicionales</div></div></td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <table style="width:100%;">
                                                                    <tr>
                                                                        <td class="noteDiv">
                                                                            <table cellpadding="2" cellspacing="2" border="0" style="width:100%;" >
                                                                                <tr>
                                                                                    <td class="">
                                                                                        <div class="note">Nota:</div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <ul>
                                                                                            <b>Formatos Soportados</b>
                                                                                            <p></p>
                                                                                            <li>
                                                                                        Solo soporta archivos de texto ( TXT )
                                                                                            </li>
                                                                                        </ul>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <ul>
                                                                                            <b>Notas importantes</b>
                                                                                            <p></p>
                                                                                            <li>
                                                                                        La primera fila del archivo debe de contener las cabeceras
                                                                                            </li>
                                                                                            <li>
                                                                                        Los campos deben estar separados por tabulaciones
                                                                                            </li>
                                                                                            <li>
                                                                                        Asegurarse que el tamaño del archivo no supere los 20M
                                                                                            </li>
                                                                                            <li style="font-weight:bold;">
                                                                                        Los valores de fecha debe estar en el formato dd/mm/yyyy o yyyy-mm-dd . Las fechas que tengan otros formatos serán ignoradas.
                                                                                            </li>
                                                                                            <li style="font-weight:bold;">
                                                                                        Los campos numericos no deben estar separados por comas
                                                                                            </li>
                                                                                            <li style="font-weight:bold;">
                                                                                        Los campos codigo_cliente , numero_cuenta, moneda y codigo_operacion indica campos de agrupacion
                                                                                            </li>
                                                                                        </ul>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <br />
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div id="panelCargarPagoMain" style="display:none;width:100%;border:0 none;" >
                                <?php   if( $_SESSION['cobrast']['idservicio'] == 11){

                                    ?>
                                <div  class="ui-widget-content ui-corner-bottom ui-corner-top" style=";width: 819px; margin: 0px auto;padding-bottom:10px" id="container_preparacion_covinoc">
                                    <div class=" border-radius-top pointer ui-widget-header title_prep_car_covinoc"> PREPARAR ARCHIVO PLANO PAGO </div>
                                    <div style="margin-top:7px;display:table;margin-bottom:8px !important;margin:0px auto"> <!-- TABLA CARTERAS-->
                                        <div class="ui-widget-header ui-corner-top" style="width:240px" >Carteras<input  style="vertical-align: bottom; margin-left: 90% ! important;" type="checkbox" id="chktotalPrepararPagoSaga" onclick="checked_all(this.checked,'tbCarterasCargaPrepararPagoSaga')"></div>
                                        <div style="overflow:auto;height:100%;width:240px;" >
                                            <table cellpadding="0" cellspacing="0" border="0" id="tbCarterasCargaPrepararPagoSaga"style="width:100%" ></table>
                                        </div>
                                        <div class="ui-widget-header ui-corner-bottom" style="width:240px" >
                                            <table>
                                                <tr>
                                                    <td>Buscar:</td>
                                                    <td><input type="text" onkeyup="search_text_table(this.value,'tbCarterasCargaPrepararPagoSaga')" class="cajaForm" /></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div>
                                        <div class='covinoc'>Archivo Plano de <b>Pago</b></div>
                                        <div class='covinoc'><input type="file" id="filePago" name="filePago" /></div>
                                        <div class='covinoc_estado_carga'><input type="button" value="Acumular" id="btnAcuPago"></div>
                                    </div>
                                    <div class=" ui-widget-header ui-corner-bottom ui-corner-top" style="margin-bottom: 22px; margin-top: 22px;"></div>
                                </div>
                                <?php   }?>

                                <table cellpadding="0" cellspacing="0" border="0" style="width:100%;" >
                                    <tr>
                                        <td align="center">
                                            <div style="padding:10px 0;">
                                                <table>
                                                    <tr>
                                                        <td valign="top">
                                                            <div>
                                                                <table>
                                                                    <tr>
                                                                        <td align="right">Proceso</td>
                                                                        <td><select id="cbProcesoPago" class="combo"><option value="carga">Carga</option><option value="actualizacion">Actualizacion</option></select></td>
                                                                        <td><input type="hidden" id="hddFilePago"/></td>
                                                                        <td align="right">Caracter Separador</td>
                                                                        <td>
                                                                            <select id="cbCaracterSeparadorPago" class="combo">
                                                                                <option value="|">|</option>
                                                                                <option value=";">;</option>
                                                                                <option value="tab">TAB</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>Cabeceras</td>
                                                                        <td><select class="combo" style="width:100px;" id="cbCabecerasCarteraPago" ><option value="0">--Seleccione--</option></select></td>
                                                                        <td>Campa&ntilde;a</td>
                                                                        <td><select class="combo" style="width:100px;" id="cboCampaniaPago" onclick="listar_carteras( this.value, [{id:'cboCarteraPago'}] )"><option>--Seleccione--</option></select></td>
                                                                        <td>Cartera</td>
                                                                        <td><select class="combo" style="width:100px;" id="cboCarteraPago"><option>--Seleccione--</option></select></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <!--<tr>
                                                        <td>
                                                            <div>
                                                                <table>
                                                                    <tr>
                                                                        <td align="right">Elejir Archivo </td>
                                                                        <td><input type="file" id="uploadFileCarteraPagoMain" name="uploadFileCarteraPagoMain" ></td>
                                                                        <td colspan="2" align="center"><button class="ui-state-default ui-corner-all" onClick="uploadFilePago()" id="btnUploadFile" >Cargar Cabeceras</button></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>-->
                                                    <tr>
                                                        <td>
                                                                <div id="file_upload_pago" class="file_upload" align="center">

                                                                        <form action="" method="POST" enctype="multipart/form-data" class="file_upload">
                                                                                 <input type="hidden" name="error" value="0" id="loadHeaderErrorPago" />
                                                                                 <input type="hidden" name="error" value="" id="loadHeaderErrorPagoMsg" />
                                                                                 <input type="file" name="file[]" multiple="">
                                                                                 <button type="submit">Upload</button>
                                                                                 <div class="file_upload_label">Seleccionar Archivo(s)</div>
                                                                         </form>
                                                                 </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <table id="files_pago">

                                                                    <tr class="file_upload_template" style="display:none;">
                                                                        <td class="file_upload_preview"></td>
                                                                        <td class="file_name"></td>

                                                                        <td class="file_size"></td>
                                                                        <td class="file_upload_progress"><div></div></td>
                                                                        <td class="file_upload_start"><button>Start</button></td>
                                                                        <td class="file_upload_cancel"><button>Cancel</button></td>
                                                                    </tr>
                                                                    <tr class="file_download_template" style="display:none;">
                                                                        <td class="file_download_preview"></td>
                                                                        <td class="file_name"><a></a></td>

                                                                        <td class="file_size"></td>
                                                                        <td class="file_download_delete" colspan="3"><button>Delete</button></td>
                                                                    </tr>

                                                            </table>
                                                            <div class="file_upload_overall_progress">
                                                                <div style="display: none; " class="ui-progressbar ui-widget ui-widget-content ui-corner-all" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                                                        <div class="ui-progressbar-value ui-widget-header ui-corner-left" style="display: none; width: 0%; "></div>
                                                                </div>
                                                            </div>
                                                            <div class="file_upload_buttons"></div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <div id="selectHeaderNotCarteraPago" style="display:none;" align="center" >
                                                <table cellpadding="0" cellspacing="0" border="0">
                                                    <tr class="ui-state-default" >
                                                        <td style="padding:3px 15px;border:1px solid #CDC3B7;width:730px;" ><strong>Nuevas Cabeceras</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td id="TDnewHeaderCarteraPago" align="center" style="border-left:1px solid #CDC3B7; border-right:1px solid #CDC3B7; border-bottom:1px solid #CDC3B7;color:#F00;width:730px;">

                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" style="padding:3px;">
                                            <button id="btnCargarPagos" onclick="generarPagos()" >Cargar Cartera de Pagos</button>
                                            <button class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all" onclick="cancel_carga_cartera_pago()"><span class="ui-button-text">Cancelar</span></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <!--<div id="selectHeaderNotCarteraPago" style="display:none;" align="center" >
                                            <table cellpadding="0" cellspacing="0" border="0">
                                                <tr class="ui-state-default" >
                                                    <td style="padding:3px 15px;border:1px solid #CDC3B7;width:730px;" ><strong>Nuevas Cabeceras</strong></td>
                                                </tr>
                                                <tr>
                                                    <td id="TDnewHeaderCarteraPago" align="center" style="border-left:1px solid #CDC3B7; border-right:1px solid #CDC3B7; border-bottom:1px solid #CDC3B7;color:#F00;width:730px;">

                                                    </td>
                                                </tr>
                                            </table>
                                        </div>-->
                                            <div style="padding:10px 0;" id="layerHeaderPago">
                                                <table>
                                                    <tr>
                                                        <td align="right">Codigo Cliente</td>
                                                        <td align="left"><select id="codigo_cliente" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_codigo_cliente" value="CODIGO CLIENTE" /></td>
                                                        <td align="right">Numero de Cuenta</td>
                                                        <td align="left"><select id="numero_cuenta" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_numero_cuenta" value="NUMERO CUENTA" /></td>
                                                        <td align="right">Moneda Cuenta</td>
                                                        <td align="left"><select id="moneda_cuenta" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_moneda_cuenta" value="MONEDA CUENTA" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">Operacion - Factura</td>
                                                        <td align="left"><select id="codigo_operacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_codigo_operacion" value="CODIGO OPERACION" /></td>
                                                        <td align="right">Moneda Operacion</td>
                                                        <td align="left"><select id="moneda_operacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_moneda_operacion" value="MONEDA OPERACION" /></td>
                                                        <td align="right">Moneda Pago</td>
                                                        <td align="left"><select id="moneda" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_moneda" value="MONEDA PAGO" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">Monto Pagado</td>
                                                        <td align="left"><select id="monto_pagado" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_monto_pagado" value="MONTO PAGADO" /></td>
                                                        <td align="right">Total Deuda</td>
                                                        <td align="left"><select id="total_deuda" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_total_deuda" value="TOTAL DEUDA" /></td>
                                                        <td align="right">Monto Mora</td>
                                                        <td align="left"><select id="monto_mora" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_monto_mora" value="MONTO MORA" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">Saldo Capital</td>
                                                        <td align="left"><select id="saldo_capital" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_saldo_capital" value="SALDO CAPITAL" /></td>
                                                        <td align="right">Dias Mora</td>
                                                        <td align="left"><select id="dias_mora" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_dias_mora" value="DIAS MORA" /></td>
                                                        <td align="right">Fecha Pago</td>
                                                        <td align="left"><select id="fecha" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_fecha" value="FECHA PAGO" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">Observacion</td>
                                                        <td align="left"><select id="observacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_observacion" value="OBSERVACION" /></td>
                                                        <td align="right">Call Center</td>
                                                        <td align="left"><select id="call_center" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_call_center" value="CALL CENTER" /></td>
                                                        <td align="right">Agencia</td>
                                                        <td align="left"><select id="agencia" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_agencia" value="AGENCIA" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">Fecha Envio</td>
                                                        <td align="left"><select id="fecha_envio" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_fecha_envio" value="FECHA ENVIO" /></td>
                                                        <td align="right">Estudio</td>
                                                        <td align="left"><select id="estudio" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_estudio" value="ESTUDIO" /></td>
                                                        <td align="right">Codigo Transaccion</td>
                                                        <td align="left"><select id="codigo_transaccion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_codigo_transaccion" value="CODIGO TRANSACCION" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">Estado Pago</td>
                                                        <td align="left"><select id="estado_pago" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_estado_pago" value="ESTADO PAGO" /></td>
                                                        <td align="right">Tramo</td>
                                                        <td align="left"><select id="tramo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_tramo" value="TRAMO" /></td>
                                                        <td align="right">Gestion</td>
                                                        <td align="left"><select id="gestion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_gestion" value="GESTION" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">Retiro de cliente</td>
                                                        <td align="left"><select id="retiro_cliente" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_retiro_cliente" value="RETIRO CLIENTE" /></td>
                                                        <td align="right"></td>
                                                        <td align="left"></td>
                                                        <td></td>
                                                        <td align="right"></td>
                                                        <td align="left"></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">Dato 1</td>
                                                        <td align="left"><select id="dato1" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_dato1" value="DATO 1" /></td>
                                                        <td align="right">Dato 2</td>
                                                        <td align="left"><select id="dato2" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_dato2" value="DATO 2" /></td>
                                                        <td align="right">Dato 3</td>
                                                        <td align="left"><select id="dato3" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_dato3" value="DATO 3" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">Dato 4</td>
                                                        <td align="left"><select id="dato4" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_dato4" value="DATO 4" /></td>
                                                        <td align="right">Dato 5</td>
                                                        <td align="left"><select id="dato5" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_dato5" value="DATO 5" /></td>
                                                        <td align="right">Dato 6</td>
                                                        <td align="left"><select id="dato6" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_dato6" value="DATO 6" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">Dato 7</td>
                                                        <td align="left"><select id="dato7" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_dato7" value="DATO 7" /></td>
                                                        <td align="right">Dato 8</td>
                                                        <td align="left"><select id="dato8" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_dato8" value="DATO 8" /></td>
                                                        <td align="right">Dato 9</td>
                                                        <td align="left"><select id="dato9" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_dato9" value="DATO 9" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">Dato 10</td>
                                                        <td align="left"><select id="dato10" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_dato10" value="DATO 10" /></td>
                                                        <td align="right">Dato 11</td>
                                                        <td align="left"><select id="dato11" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_dato11" value="DATO 11" /></td>
                                                        <td align="right">Dato 12</td>
                                                        <td align="left"><select id="dato12" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_dato12" value="DATO 12" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">Dato 13</td>
                                                        <td align="left"><select id="dato13" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_dato13" value="DATO 13" /></td>
                                                        <td align="right">Dato 14</td>
                                                        <td align="left"><select id="dato14" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_dato14" value="DATO 14" /></td>
                                                        <td align="right">Dato 15</td>
                                                        <td align="left"><select id="dato15" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_dato15" value="DATO 15" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">Dato 16</td>
                                                        <td align="left"><select id="dato16" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_dato16" value="DATO 16" /></td>
                                                        <td align="right">Dato 17</td>
                                                        <td align="left"><select id="dato17" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_dato17" value="DATO 17" /></td>
                                                        <td align="right">Dato 18</td>
                                                        <td align="left"><select id="dato18" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_dato18" value="DATO 18" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">Dato 19</td>
                                                        <td align="left"><select id="dato19" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_dato19" value="DATO 19" /></td>
                                                        <td align="right">Dato 20</td>
                                                        <td align="left"><select id="dato20" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_dato20" value="DATO 20" /></td>
                                                        <td align="right">Dato 21</td>
                                                        <td align="left"><select id="dato21" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_dato21" value="DATO 21" /></td>                                                        
                                                    </tr>
                                                    <tr>
                                                        <td align="right">Dato 22</td>
                                                        <td align="left"><select id="dato22" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_dato22" value="DATO 22" /></td>
                                                        <td align="right">Dato 23</td>
                                                        <td align="left"><select id="dato23" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_dato23" value="DATO 23" /></td>
                                                        <td align="right">Dato 24</td>
                                                        <td align="left"><select id="dato24" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_dato24" value="DATO 24" /></td>                                                        
                                                    </tr>                                                    
                                                    <tr>
                                                        <td align="right">Dato 25</td>
                                                        <td align="left"><select id="dato25" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td><input type="text" id="txt_dato25" value="DATO 25" /></td>
                                                    </tr>                                                                                                        
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <!--<tr>
                                        <td align="center">
                                    <button id="btnCargarPagos" onclick="generarPagos()" >Cargar Cartera de Pagos</button>
                                            <button class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all" onclick="cancel_carga_cartera_pago()"><span class="ui-button-text">Cancelar</span></button>
                                </td>
                                    </tr>-->
                                </table>
                                <table style="width:100%;">
                                    <tr>
                                        <td class="noteDiv">
                                            <table cellpadding="2" cellspacing="2" border="0" style="width:100%;" >
                                                <tr>
                                                    <td class="">
                                                        <div class="note">Nota:</div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <ul>
                                                            <b>Formatos Soportados</b>
                                                            <p></p>
                                                            <li>
                                                                Solo soporta archivos de texto ( TXT )
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <ul>
                                                            <b>Notas importantes</b>
                                                            <p></p>
                                                            <li>
                                                                La primera fila del archivo debe de contener las cabeceras
                                                            </li>
                                                            <li>
                                                                Los campos deben estar separados por tabulaciones
                                                            </li>
                                                            <li>
                                                                Asegurarse que el tamaño del archivo no supere los 20M
                                                            </li>
                                                            <li style="font-weight:bold;">
                                                                Los valores de fecha debe estar en el formato dd/mm/yyyy o yyyy-mm-dd . Las fechas que tengan otros formatos serán ignoradas.
                                                            </li>
                                                            <li style="font-weight:bold;">
                                                                Los campos numericos no deben estar separados por comas
                                                            </li>
                                                            <li style="font-weight:bold;">
                                                                El campo retiro de cliente debe de tener 1 &oacute; 0
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            </table>
                                            <br />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div id="panelCargarPlanta" style="display:none;width:100%;border:0 none;" align="center" >
                                <table  cellpadding="0" cellspacing="0" border="0" style="width:100%;" >
                                    <tr>
                                        <td>
                                            <div style="padding:10px 0;" align="center">
                                                <table>
                                                    <tr>
                                                        <td valign="top">
                                                            <div align="center">
                                                                <table>
                                                                    <tr>
                                                                        <td align="right">Proceso</td>
                                                                        <td><select id="cbProcesoPlanta" class="combo"><option value="carga">Carga</option><option value="actualizacion">Actualizacion</option></select></td>
                                                                        <td><input type="hidden" id="hddFilePlanta"/></td>
                                                                        <td align="right">Campa&ntilde;a</td>
                                                                        <td><select id="cboCampaniaPlanta" class="combo" onchange="listar_cartera_pago(this.value)"><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right">Caracter Separador</td>
                                                                        <td align="right">Nombre Planta</td>
                                                                        <td><input type="text" id="txtNombrePlanta" /></td>
                                                                        <td>
                                                                            <select id="cbCaracterSeparadorPlanta" class="combo">
                                                                                <option value="|">|</option>
                                                                                <option value=";">;</option>
                                                                                <option value=",">,</option>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div>
                                                                <table>
                                                                    <tr>
                                                                        <td align="right">Elejir Archivo </td>
                                                                        <td><input type="file" id="uploadFileCarteraPlanta" name="uploadFileCarteraPlanta" ></td>
                                                                        <td colspan="2" align="center"><button class="ui-state-default ui-corner-all" onClick="uploadFileCarteraPlanta()" id="btnUploadFilePlanta" >Cargar Cabeceras</button></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div style="padding:5px;" align="center">
                                                <table>
                                                    <tr>
                                                        <td>
                                                            <button class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all" onclick="generatePlanta()"><span class="ui-button-text">Generar Planta</span></button>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <table style="width:99%;">
                                                    <tr>
                                                        <td id="selectHeaderPlanta">
                                                            <div align="center" class="ui-widget-content" id="layerTabCargaCarteraPlantaData" style="display:block;padding:5px 10px;">
                                                                <table>
                                                                    <tr>
                                                                        <td align="right">Codigo</td>
                                                                        <td><select id="codigo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right" >Codigo Cliente</td>
                                                                        <td><select id="codigo_cliente" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right">Numero Cuenta</td>
                                                                        <td><select id="numero_cuenta" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right" >Telefono</td>
                                                                        <td><select id="telefono" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right" >Producto Comercial</td>
                                                                        <td><select id="producto_comercial" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right">Fecha Alta</td>
                                                                        <td><select id="fecha_alta" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right" >Zona</td>
                                                                        <td><select id="zona" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right" >Sub Negocio</td>
                                                                        <td><select id="sub_negocio" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right">Agencia</td>
                                                                        <td><select id="agencia" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <div align="center" class="ui-widget-content" id="layerTabCargaCarteraPlantaDatosAdicionales" style="display:none;padding:5px 10px;">
                                                                <table>
                                                                    <tr>
                                                                        <td><button id="btnLimpiar" onClick="clean_adicionales_cliente_planta()" >Limpiar</button></td>
                                                                    </tr>
                                                                </table>
                                                                <table cellspacing="10">
                                                                    <tr>
                                                                        <td><div class="ui-corner-all ui-widget-header" style="padding:2px 5px;">Cliente</div></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="center"><select class="combo" size="10" id="ca_datos_adicionales_planta"></select></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <table style="width:99%;" cellpadding="0" cellspacing="0" border="0" >
                                                    <tr>
                                                        <td class="lineTab ui-widget-header"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div style="margin-left:100px;">
                                                                <table cellpadding="0" cellspacing="0" border="0" id="TabPlantaListCargaCartera" >
                                                                    <tr>
                                                                        <td><div id="TabPlantaListCliente" onClick="_activeTabLayer('TabPlantaListCargaCartera','TabPlantaList',this,'selectHeaderPlanta','layerTabCargaCarteraPlanta','layerTabCargaCarteraPlantaData')" class="itemTabActive border-radius-bottom pointer ui-widget-header" style="margin: 0px 1px 0pt 0pt;"><div class="text-white">Data Planta</div></div></td>
                                                                        <td><div id="TabPlantaListDatosAdicionales" onClick="_activeTabLayer('TabPlantaListCargaCartera','TabPlantaList',this,'selectHeaderPlanta','layerTabCargaCarteraPlanta','layerTabCargaCarteraPlantaDatosAdicionales')" class="itemTab border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;"><div class="AitemTab">Datos Adicionales</div></div></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                    </tr>
                                </table>
                            </div>
                            <div id="panelCargarCentroPago" style="display:none;width:100%;border:0 none;" align="center" >
                                <table>
                                    <tr>
                                        <td>
                                            <div>
                                                <table>
                                                    <tr>
                                                        <td align="left">Nombre</td>
                                                        <td align="left"><input type="text" id="txtCargaCentroPagoNombre" /></td>
                                                        <td align="left">
                                                            <select id="cbCaracterSeparadorCentroPago" class="combo">
                                                                <option value="|">|</option>
                                                                <option value=";">;</option>
                                                            </select>
                                                        </td>
                                                        <td align="right">Elejir Archivo </td>
                                                        <td><input type="file" id="uploadFileCarteraCentroPago" name="uploadFileCarteraCentroPago" ></td>
                                                        <td><input type="hidden" id="hdFileCentroPago" /></td>
                                                        <td colspan="2" align="center"><button onclick="uploadFileCentroPago()" class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all" onClick="()" id="btnUploadFileCentroPago" ><span class="ui-button-text">Cargar Cabeceras</span></button></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div id="layerHeaderCentroPago">
                                                <table>
                                                    <tr>
                                                        <td align="right">Agencia</td>
                                                        <td align="left"><select id="agencia" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Zona</td>
                                                        <td align="left"><select id="zona" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Tipo Canal</td>
                                                        <td align="left"><select id="tipo_canal" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">Horario</td>
                                                        <td align="left"><select id="horario" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Horario Sabado</td>
                                                        <td align="left"><select id="horario_s" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Horario Domingo</td>
                                                        <td align="left"><select id="horario_d" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">Departamento</td>
                                                        <td align="left"><select id="departamento" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Provincia</td>
                                                        <td align="left"><select id="provincia" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Distrito</td>
                                                        <td align="left"><select id="distrito" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">Direccion</td>
                                                        <td align="left"><select id="direccion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">Nombre Canal</td>
                                                        <td align="left"><select id="nombre_canal" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Sub Canal</td>
                                                        <td align="left"><select id="sub_canal" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Comision</td>
                                                        <td align="left"><select id="comision" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div align="center" style="padding:10px 0;">
                                                <button onclick="generarCentroPagos()" class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all"><span class="ui-button-text">Cargar centro de pagos</span></button>
                                                <button class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all" onclick="camcel_carga_cartera_centro_pago()"><span class="ui-button-text">Cancelar</span></button>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div id="panelComision" style="display:none;width:100%;border:0 none;" align="center" >
                                <table cellpadding="0" cellspacing="0" border="0" style="width:99%;" >
                                    <tr>
                                        <td>
                                            <div align="center">
                                                <table cellpadding="0" cellspacing="0" border="0" style="width:99%;">
                                                    <tr>
                                                        <td id="content_comision_bottom">
                                                            <div id="AGU_layer_tramo_bottom" class="ui-widget-content" align="center" style="display: block; padding: 5px;">
                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                    <tr>
                                                                        <td>
                                                                            <div>
                                                                                <div>
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td align="left">Campa&ntilde;a</td>
                                                                                            <td align="left"><select class="combo" id="cbCampaniaComision" onchange="listar_cartera_comision(this.value)" ><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="left">Cartera</td>
                                                                                            <td align="left">
                                                                                                <!--<select class="combo" id="cbCarteraComision" onchange="listar_porcentajes_comision(this.value)"><option value="0">--Seleccione--</option></select>-->
                                                                                                <select class="combo" id="cbCarteraComision" ><option value="0">--Seleccione--</option></select>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <div id="LayerTableComision">
                                                                                <table cellspacing="0" cellpadding="0" border="0" class="ui-corner-top" >
                                                                                    <tr class="ui-state-default" >
                                                                                        <td style="width:25px;padding:3px 0;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-right:1px solid #E0CFC2;text-align:center;" ></td>
                                                                                        <td style="width:200px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Tramo</td>
                                                                                        <td style="width:200px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Porcentaje de comision</td>
                                                                                        <td style="width:25px;padding:3px 0;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-right:1px solid #E0CFC2;text-align:center;" ></td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="center">
                                                                            <div style="padding:5px 0;">
                                                                                <button onclick="save_comision()" class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all"><span class="ui-button-text">Aplicar comision</span></button>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <div id="AGU_layer_generico_bottom" class="ui-widget-content" align="center" style="display: none; padding: 5px;">
                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                    <tr>
                                                                        <td>
                                                                            <div>
                                                                                <div>
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td align="left">Campa&ntilde;a</td>
                                                                                            <td align="left"><select class="combo" id="cbCampaniaComisionGenerico" onchange="listar_cartera_comision_generico(this.value)" ><option value="0">--Seleccione--</option></select></td>
                                                                                            <td align="left">Cartera</td>
                                                                                            <td align="left">
                                                                                                <!--<select class="combo" id="cbCarteraComisionGenerico" onchange=""><option value="0">--Seleccione--</option></select>-->
                                                                                                <select class="combo" id="cbCarteraComisionGenerico" ><option value="0">--Seleccione--</option></select>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <div>
                                                                                <table>
                                                                                    <tr>
                                                                                        <td align="left">Porcentaje Comision</td>
                                                                                        <td align="left"><input type="text" id="txtPorcentajeComisionGenerico" /></td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="center">
                                                                            <div style="padding:5px 0;">
                                                                                <button onclick="save_comision_generico()" class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all"><span class="ui-button-text">Aplicar comision</span></button>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <table cellpadding="0" cellspacing="0" border="0" style="width:99%;">
                                                    <tr>
                                                        <td class="lineTab ui-widget-header"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div style="margin-left:100px;">
                                                                <table id="table_tab_comision_bottom" cellpadding="0" cellspacing="0" border="0">
                                                                    <tr>
                                                                        <td><div id="tab_comision_bottom_tramo" class="itemTabActive border-radius-bottom pointer ui-widget-header" style="margin: 0px 1px 0pt 0pt;" onClick="_activeTabLayer('table_tab_comision_bottom','tab_comision_bottom',this,'content_comision_bottom','AGU_layer_','AGU_layer_tramo_bottom')"><div class="text-white">Por Tramo</div></div></td>
                                                                        <td><div id="tab_comision_bottom_generico" class="itemTab border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;" onClick="_activeTabLayer('table_tab_comision_bottom','tab_comision_bottom',this,'content_comision_bottom','AGU_layer_','AGU_layer_generico_bottom')"><div class="AitemTab">Generico</div></div></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div id="panelCargaFacturacion" style="display:none;width:100%;border:0 none;" align="center">
                                <table cellpadding="0" cellspacing="0" border="0" style="width:100%;" >
                                    <tr>
                                        <td align="center">
                                            <div style="padding:10px 0;">
                                                <table>
                                                    <tr>
                                                        <td>
                                                            <div>
                                                                <div class="ui-widget-header ui-corner-top" >Carteras<input type="checkbox" id="chktotalCargaFacturacion" onclick="checked_all(this.checked,'tbCarterasCargaFacturacion')"></div>
                                                                <div style="overflow:auto;height:200px;width:240px;" >
                                                                    <table cellpadding="0" cellspacing="0" border="0" id="tbCarterasCargaFacturacion" ></table>
                                                                </div>
                                                                <div class="ui-widget-header ui-corner-bottom" >
                                                                    <table>
                                                                        <tr>
                                                                            <td>Buscar:</td>
                                                                            <td><input type="text" onkeyup="search_text_table(this.value,'tbCarterasCargaFacturacion')" class="cajaForm" /></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td valign="top">
                                                            <div>
                                                                <table>
                                                                    <tr>
                                                                        <td align="right">Elejir Archivo </td>
                                                                        <td>
                                                                            <input type="file" id="uploadFileCarteraCargaFacturacion" name="uploadFileCarteraCargaFacturacion" >
                                                                            <input type="hidden" id="tmpArchivoCargaFacturacion">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2" align="center"><button class="ui-state-default ui-corner-all" onClick="file_carga_facturacion()" id="btnUploadFileCargaFacturacion" >Subir Archivo</button></td>
                                                                        <td></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2" align="center"><button style="display:none" id="btnCargaFacturacion" onclick="generateCargaFacturacion()" >Cargar Facturacion</button></td>
                                                                        <td></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="3">
                                                                            <b>NOTA:</b>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="3" >
                                                                            <ul>
                                                                                <li>
                                                                                    El archivo a cargar es en formato .TXT
                                                                                </li>
                                                                                <li>
                                                                                    El caracter separador TAB.
                                                                                </li>
                                                                                <li style="width:300px">
                                                                                    Debe contener las siguientes cabeceras : <b>CONTRATO, CODCENT, OFICINA, NOMB_OF, TERRITORIO, NOMBRE, AGENCIA, SUBPROD, NOMB_PROD, TIPDOC, NRODOC, TPERSONA, TRAMO, MARCA_PAGO, AGENCIA2, AGENCIA3, TCONTACTO3, TCON3, IMP_PAG3, COMISION, HONORARIO, IGV, TOTAL_PAGO, OF_FACTURA</b>        
                                                                                </li>
                                                                            </ul>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>                                    
                                </table>
                            </div>      
                            <div id="panelCargaProvision" style="display:none;width:100%;border:0 none;" align="center">
                                <div class="backPanel headerPanel ui-corner-top" style="width:70%;height:20px; padding-top:5px; font-weight:bold" align="center">CARGA DE PROVISION</div>
                                <div align="center" class="ui-widget-content ui-corner-bottom" style="padding:10px 30px 20px 30px">
                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;" >
                                        <tr>
                                            <td align="center">
                                                <div style="padding:10px 0;">
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <div class="ui-widget-header ui-corner-top" >Carteras<input type="checkbox" id="chktotalCargaProvision" onclick="checked_all(this.checked,'tbCarterasCargaProvision')"></div>
                                                                    <div style="overflow:auto;height:200px;width:240px;" >
                                                                        <table cellpadding="0" cellspacing="0" border="0" id="tbCarterasCargaProvision" ></table>
                                                                    </div>
                                                                    <div class="ui-widget-header ui-corner-bottom" >
                                                                        <table>
                                                                            <tr>
                                                                                <td>Buscar:</td>
                                                                                <td><input type="text" onkeyup="search_text_table(this.value,'tbCarterasCargaProvision')" class="cajaForm" /></td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td valign="top">
                                                                <div>
                                                                    <table>
                                                                        <tr>
                                                                            <td align="right">Elejir Archivo </td>
                                                                            <td>
                                                                                <input type="file" id="uploadFileCarteraCargaProvision" name="uploadFileCarteraCargaProvision" >
                                                                                <input type="hidden" id="tmpArchivoCargaProvision">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2" align="center"><button class="ui-state-default ui-corner-all" onClick="file_carga_provision()" id="btnUploadFileCargaProvision" >Subir Archivo</button></td>
                                                                            <td></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2" align="center"><button style="display:none" id="btnCargaProvision" onclick="generateCargaProvision()" >Cargar Provision</button></td>
                                                                            <td></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="3">
                                                                                <b>NOTA:</b>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="3" >
                                                                                <ul>
                                                                                    <li>
                                                                                        El archivo a cargar es en formato .TXT
                                                                                    </li>
                                                                                    <li>
                                                                                        El caracter separador TAB.
                                                                                    </li>
                                                                                    <li style="width:300px">
                                                                                        Debe contener las siguientes cabeceras : <b>CODCENT, PROVISION, CLASIFICACION</b>        
                                                                                    </li>
                                                                                </ul>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>                                    
                                    </table>
                                </div>
                            </div>                                                      
                            <div id="panelCargarTelefono" style="display:none;width:100%;border:0 none;" align="center">
                                <table cellpadding="0" cellspacing="0" border="0" style="width:100%;" >
                                    <tr>
                                        <td align="center">
                                            <div style="padding:10px 0;">
                                                <table>
                                                    <tr>
                                                        <td>
                                                            <div>
                                                                <div class="ui-widget-header ui-corner-top" >Carteras</div>
                                                                <div style="overflow:auto;height:200px;width:240px;" >
                                                                    <table cellpadding="0" cellspacing="0" border="0" id="tbCarterasCargaTelefono" ></table>
                                                                </div>
                                                                <div class="ui-widget-header ui-corner-bottom" >
                                                                    <table>
                                                                        <tr>
                                                                            <td>Buscar:</td>
                                                                            <td><input type="text" onkeyup="search_text_table(this.value,'tbCarterasCargaTelefono')" class="cajaForm" /></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td valign="top">
                                                            <div>
                                                                <table>
                                                                    <tr>
                                                                        <td><input type="hidden" id="hddFileTelefono"/></td>
                                                                        <!--<td align="right">Campa&ntilde;a</td>
                                                                        <td><select id="cboCampaniaTelefono" class="combo" onchange="listar_cartera_telefono(this.value)"><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right">Cartera</td>
                                                                        <td><select id="cbCarteraTelefono" class="combo"><option value="0">--Seleccione--</option></select></td>-->
                                                                        <td align="right">Caracter Separador</td>
                                                                        <td>
                                                                            <select id="cbCaracterSeparadorTelefono" class="combo">
                                                                                <option value="|">|</option>
                                                                                <option value=";">;</option>
                                                                                <option value="tab">TAB</option>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <table>
                                                                    <tr>
                                                                        <td align="right">Elejir Archivo </td>
                                                                        <td><input type="file" id="uploadFileCarteraTelefono" name="uploadFileCarteraTelefono" ></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2" align="center"><button class="ui-state-default ui-corner-all" onClick="uploadFileTelefono()" id="btnUploadFileTelefono" >Cargar Cabeceras</button></td>
                                                                        <td></td>
                                                                    </tr>
                                                                </table>
                                                                <div>
                                                                    <table>
                                                                        <tr>
                                                                            <td>Origen</td>
                                                                            <td><select class="combo" id="cbCargaTelefonoOrigen"><option value="0">--Seleccione--</option></select></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Tipo</td>
                                                                            <td><select class="combo" id="cbCargaTelefonoTipo"><option value="0">--Seleccione--</option></select></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <!--<table>
                                                        <tr>
                                                        <td valign="top">
                                                    <div>
                                                                <table>
                                                                    <tr>
                                                                        <td><input type="hidden" id="hddFileTelefono"/></td>
                                                                        <td align="right">Campa&ntilde;a</td>
                                                                        <td><select id="cboCampaniaTelefono" class="combo" onchange="listar_cartera_telefono(this.value)"><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right">Cartera</td>
                                                                        <td><select id="cbCarteraTelefono" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right">Caracter Separador</td>
                                                                        <td>
                                                                                <select id="cbCaracterSeparadorTelefono" class="combo">
                                                                        <option value="|">|</option>
                                                                                <option value=";">;</option>
                                                                                <option value=",">,</option>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                            </tr>
                                                    <tr>
                                                        <td>
                                                    <div>
                                                                <table>
                                                                    <tr>
                                                                        <td align="right">Elejir Archivo </td>
                                                                        <td><input type="file" id="uploadFileCarteraTelefono" name="uploadFileCarteraTelefono" ></td>
                                                                        <td colspan="2" align="center"><button class="ui-state-default ui-corner-all" onClick="uploadFileTelefono()" id="btnUploadFileTelefono" >Cargar Cabeceras</button></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>-->
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <div id="selectHeaderNotCarteraTelefono" style="display:none;" align="center" >
                                                <table cellpadding="0" cellspacing="0" border="0">
                                                    <tr class="ui-state-default" >
                                                        <td style="padding:3px 15px;border:1px solid #CDC3B7;width:730px;" ><strong>Nuevas Cabeceras</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td id="TDnewHeaderCarteraTelefono" align="center" style="border-left:1px solid #CDC3B7; border-right:1px solid #CDC3B7; border-bottom:1px solid #CDC3B7;color:#F00;width:730px;">

                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div style="padding:10px 0;" id="layerHeaderTelefono" align="center" >
                                                <table>
                                                    <tr id="tr_ids_carga_telefono">
                                                        <td>Codigo Cliente</td>
                                                        <td><select id="codigo_cliente" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td>Numero Cuenta</td>
                                                        <td><select id="numero_cuenta" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td>Codigo Operacion</td>
                                                        <td><select id="codigo_operacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                    </tr>
                                                </table>
                                                <table cellpadding="5" cellspacing="10">
                                                    <tr>
                                                        <td>
                                                            <h3 class="ui-widget-header ui-corner-all" style="padding:2px;width:100%;">Telefono Predeterminado</h3>
                                                            <div style="display:block;padding:5px 10px;" title="telefono_predeterminado" align="center">
                                                                <table>
                                                                    <tr>
                                                                        <td align="right">Telefono</td>
                                                                        <td><select id="numero" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right">Anexo</td>
                                                                        <td><select id="anexo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right">Observacion</td>
                                                                        <td><select id="observacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right">Referencia</td>
                                                                        <td><select id="referencia" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <h3 class="ui-widget-header ui-corner-all" style="padding:2px;width:100%;">Telefono Domicilio</h3>
                                                            <div style="display:block;padding:5px 10px;" title="telefono_domicilio" align="center">
                                                                <table>
                                                                    <tr>
                                                                        <td align="right">Telefono</td>
                                                                        <td><select id="numero" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right">Anexo</td>
                                                                        <td><select id="anexo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right">Observacion</td>
                                                                        <td><select id="observacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right">Referencia</td>
                                                                        <td><select id="referencia" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h3 class="ui-widget-header ui-corner-all" style="padding:2px;width:100%;">Telefono Oficina</h3>
                                                            <div style="display:block;padding:5px 10px;" title="telefono_oficina" align="center">
                                                                <table>
                                                                    <tr>
                                                                        <td align="right">Telefono</td>
                                                                        <td><select id="numero" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right">Anexo</td>
                                                                        <td><select id="anexo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right">Observacion</td>
                                                                        <td><select id="observacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right">Referencia</td>
                                                                        <td><select id="referencia" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <h3 class="ui-widget-header ui-corner-all" style="padding:2px;width:100%;">Telefono Negocio</h3>
                                                            <div style="display:block;padding:5px 10px;" title="telefono_negocio" align="center">
                                                                <table>
                                                                    <tr>
                                                                        <td align="right">Telefono</td>
                                                                        <td><select id="numero" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right">Anexo</td>
                                                                        <td><select id="anexo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right">Observacion</td>
                                                                        <td><select id="observacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right">Referencia</td>
                                                                        <td><select id="referencia" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h3 class="ui-widget-header ui-corner-all" style="padding:2px;width:100%;">Telefono Laboral</h3>
                                                            <div style="display:block;padding:5px 10px;" title="telefono_laboral" align="center">
                                                                <table>
                                                                    <tr>
                                                                        <td align="right">Telefono</td>
                                                                        <td><select id="numero" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right">Anexo</td>
                                                                        <td><select id="anexo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right">Observacion</td>
                                                                        <td><select id="observacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right">Referencia</td>
                                                                        <td><select id="referencia" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                        <td>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <!--<button id="btnCargarPagos" onclick="generarPagos()" >Carga Manual de Pagos</button>
                                        <button id="btnCargarPagosAutomatica" onclick="generateTablePagoAutomatic()" >Carga Automatica de Pagos</button>-->
                                            <button id="btnCargarTelefono" onclick="generateTelefono()" >Cargar Cartera de Telefonos</button>
                                            <button class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all" onclick="cancel_carga_cartera_telefono()"><span class="ui-button-text">Cancelar</span></button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div id="panelCargarDetalle" style="display:none;width:100%;border:0 none;" align="center">
                                <table>
                                    <tr>
                                        <td>
                                            <div>
                                                <table>
                                                    <tr>
                                                        <td valign="top">
                                                            <div>
                                                                <table>
                                                                    <tr>
                                                                        <td><input type="hidden" id="hddFileDetalle"/></td>
                                                                        <td align="right">Campa&ntilde;a</td>
                                                                        <td><select id="cboCampaniaDetalle" class="combo" onchange="listar_cartera_detalle(this.value)"><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right">Cartera</td>
                                                                        <td><select id="cbCarteraDetalle" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right">Caracter Separador</td>
                                                                        <td>
                                                                            <select id="cbCaracterSeparadorDetalle" class="combo">
                                                                                <option value="|">|</option>
                                                                                <option value=";">;</option>
                                                                                <option value=",">,</option>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div>
                                                                <table>
                                                                    <tr>
                                                                        <td align="right">Elejir Archivo </td>
                                                                        <td><input type="file" id="uploadFileCarteraDetalle" name="uploadFileCarteraDetalle" ></td>
                                                                        <td colspan="2" align="center"><button class="ui-state-default ui-corner-all" onClick="uploadFileDetalle()" id="btnUploadFileDetalle" >Cargar Cabeceras</button></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div id="selectHeaderNotCarteraDetalle" style="display:none;" align="center" >
                                                <table cellpadding="0" cellspacing="0" border="0">
                                                    <tr class="ui-state-default" >
                                                        <td style="padding:3px 15px;border:1px solid #CDC3B7;width:730px;" ><strong>Nuevas Cabeceras</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td id="TDnewHeaderCarteraDetalle" align="center" style="border-left:1px solid #CDC3B7; border-right:1px solid #CDC3B7; border-bottom:1px solid #CDC3B7;color:#F00;width:730px;">

                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div style="padding:10px 0;" id="layerHeaderDetalle" align="center" >
                                                <table>
                                                    <tr>
                                                        <td align="center">
                                                            <div>
                                                                <table id="tableDataCuentaCarteraDetalle">
                                                                    <tr>
                                                                        <td align="right" >Numero de Cuenta</td>
                                                                        <td><select onchange="clean_adicionales_detalle()" id="numero_cuenta" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right">Moneda</td>
                                                                        <td><select onchange="clean_adicionales_detalle()" id="moneda" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                    </tr>
                                                                </table>
                                                                <table id="tableDataOperacionCarteraDetalle">
                                                                    <tr>
                                                                        <td align="right" >Codigo de Operacion</td>
                                                                        <td><select onchange="clean_adicionales_detalle()"  id="codigo_operacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td><input type="text" id="txt_codigo_operacion" value="CODIGO OPERACION" /></td>
                                                                        <td align="right">Moneda</td>
                                                                        <td><select onchange="clean_adicionales_detalle()"  id="moneda" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td><input type="text" id="txt_moneda" value="MONEDA" /></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right">Tramo</td>
                                                                        <td><select onchange="clean_adicionales_detalle()"  id="tramo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td><input type="text" id="txt_tramo" value="TRAMO" /></td>
                                                                        <td align="right">Refinanciamiento</td>
                                                                        <td><select onchange="clean_adicionales_detalle()"  id="refinanciamiento" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td><input type="text" id="txt_refinanciamiento" value="REFINANCIAMIENTO" /></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right" >Dia Mora</td>
                                                                        <td><select onchange="clean_adicionales_detalle()"  id="dias_mora" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td><input type="text" id="txt_dias_mora" value="DIAS MORA" /></td>
                                                                        <td align="right">Numero de Cuotas</td>
                                                                        <td><select onchange="clean_adicionales_detalle()"  id="numero_cuotas" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td><input type="text" id="txt_numero_cuotas" value="NUMERO DE CUOTAS" /></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right">Numero de Cuotas Pagadas</td>
                                                                        <td><select onchange="clean_adicionales_detalle()"  id="numero_cuotas_pagadas" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td><input type="text" id="txt_numero_cuotas_pagadas" value="NUMERO CUOTAS PAGADAS" /></td>
                                                                        <td align="right">Total Deuda</td>
                                                                        <td><select onchange="clean_adicionales_detalle()"  id="total_deuda" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td><input type="text" id="txt_total_deuda" value="TOTAL DEUDA" /></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right">Total Deuda Soles</td>
                                                                        <td><select onchange="clean_adicionales_detalle()"  id="total_deuda_soles" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td><input type="text" id="txt_total_deuda_soles" value="TOTAL DEUDA SOLES" /></td>
                                                                        <td align="right">Total Deuda Dolares</td>
                                                                        <td><select onchange="clean_adicionales_detalle()"  id="total_deuda_dolares" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td><input type="text" id="txt_total_deuda_dolares" value="TOTAL DEUDA DOLARES" /></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right">Monto Mora</td>
                                                                        <td><select onchange="clean_adicionales_detalle()"  id="monto_mora" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td><input type="text" id="txt_monto_mora" value="MONTO MORA" /></td>
                                                                        <td align="right">Monto Mora Soles</td>
                                                                        <td><select onchange="clean_adicionales_detalle()"  id="monto_mora_soles" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td><input type="text" id="txt_monto_mora_soles" value="MONTO MORA SOLES" /></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right">Monto Mora Dolares</td>
                                                                        <td><select onchange="clean_adicionales_detalle()"  id="monto_mora_dolares" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td><input type="text" id="txt_monto_mora_dolares" value="MONTO MORA DOLARES" /></td>
                                                                        <td align="right">Saldo Capital</td>
                                                                        <td><select onchange="clean_adicionales_detalle()"  id="saldo_capital" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td><input type="text" id="txt_saldo_capital" value="SALDO CAPITAL" /></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right">Saldo Capital Soles</td>
                                                                        <td><select onchange="clean_adicionales_detalle()"  id="saldo_capital_soles" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td><input type="text" id="txt_saldo_capital_soles" value="SALDO CAPITAL SOLES" /></td>
                                                                        <td align="right">Saldo Capital Dolares</td>
                                                                        <td><select onchange="clean_adicionales_detalle()"  id="saldo_capital_dolares" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td><input type="text" id="txt_saldo_capital_dolares" value="SALDO CAPITAL DOLARES" /></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right">Fecha Asignacion</td>
                                                                        <td><select onchange="clean_adicionales_detalle()"  id="fecha_asignacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td><input type="text" id="txt_fecha_asignacion" value="FECHA ASIGNACION" /></td>
                                                                        <td align="right">Descripcion servicio</td>
                                                                        <td><select onchange="clean_adicionales_detalle()"  id="descripcion_servicio" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td><input type="text" id="txt_descripcion_servicio" value="DESCRIPCION SERVICIO" /></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right">Descripcion Fogapi</td>
                                                                        <td><select onchange="clean_adicionales_detalle()"  id="descripcion_fogapi" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td><input type="text" id="txt_descripcion_fogapi" value="DESCRIPCION FOGAPI" /></td>
                                                                        <td align="right">Nombre Agencia</td>
                                                                        <td><select onchange="clean_adicionales_detalle()"  id="nombre_agencia" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td><input type="text" id="txt_nombre_agencia" value="NOMBRE AGENCIA" /></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right">CLAS SBS</td>
                                                                        <td><select onchange="clean_adicionales_detalle()"  id="clas_sbs" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td><input type="text" id="txt_clas_sbs" value="CLAS SBS" /></td>
                                                                        <td align="right">CAT SBS</td>
                                                                        <td><select onchange="clean_adicionales_detalle()"  id="cat_sbs" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td><input type="text" id="txt_cat_sbs" value="CAT SBS" /></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right">Mora Contable</td>
                                                                        <td><select onchange="clean_adicionales_detalle()"  id="mora_contable" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td><input type="text" id="txt_mora_contable" value="MORA CONTABLE" /></td>
                                                                        <td align="right">Comision</td>
                                                                        <td><select onchange="clean_adicionales_detalle()"  id="comision" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td><input type="text" id="txt_comision" value="COMISION" /></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right">Fecha Vencimiento</td>
                                                                        <td><select onchange="clean_adicionales_detalle()"  id="fecha_vencimiento" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        <td><input type="text" id="txt_fecha_vencimiento" value="FECHA VENCIMIENTO" /></td>
                                                                        <td></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                        <td valign="top">
                                                            <table>
                                                                <tr>
                                                                    <td>Datos Adicionales</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><input type="text" id="txt_adicionales_cartera_detalle" /></td>
                                                                </tr>
                                                                <tr id="trDataAdicionalesCarteraDetalle">
                                                                    <td align="center"><select style="width:125px;" class="combo" size="10" id="adicionales_cartera_detalle"></select></td>
                                                                </tr>
                                                                <tr><td><button class="btn" onclick="agregar_adicional_detalle()">Agregar</button></td></tr>
                                                                <tr>
                                                                    <td>Adicionales Detalle</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><select class="combo" size="10" id="ca_datos_adicionales_detalle_cuenta"></select></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><button onclick="remove_adicional_cartera_detalle()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-trash"></span></button></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <button id="btnCargarDetalle" onclick="generateDetalle()" >Cargar Cartera Detalle</button>
                                            <button class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all" onclick=""><span class="ui-button-text">Cancelar</span></button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div id="panelCargarReclamos" style="display:none;width:100%;border:0 none;" align="center">
                                <table>
                                    <tr>
                                        <td>
                                            <div>
                                                <table>
                                                    <tr>
                                                        <td valign="top">
                                                            <div>
                                                                <div class="ui-widget-header ui-corner-top">Carteras</div>
                                                                <div style="overflow:auto;height:200px;width:240px;">
                                                                    <table cellpadding="0" cellspacing="0" border="0" id="tbCarterasCargaReclamo" ></table>
                                                                </div>
                                                                <div class="ui-widget-header ui-corner-bottom">
                                                                    <table>
                                                                        <tr>
                                                                            <td>Buscar:</td>
                                                                            <td><input type="text" onkeyup="search_text_table(this.value,'tbCarterasCargaReclamo')" class="cajaForm" /></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td valign="top">
                                                            <div>
                                                                <table>
                                                                    <tr>
                                                                        <!--<td align="right">Campa&ntilde;a</td>
                                                                        <td><select id="cboCampaniaReclamo" class="combo" onchange="listar_cartera_reclamo(this.value)"><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right">Cartera</td>
                                                                        <td><select id="cbCarteraReclamo" class="combo"><option value="0">--Seleccione--</option></select></td>-->
                                                                        <td align="right">Caracter Separador</td>
                                                                        <td align="left">
                                                                            <select id="cbCaracterSeparadorReclamo" class="combo">
                                                                                <option value="|">|</option>
                                                                                <option value=";">;</option>
                                                                                <option value="tab">TAB</option>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <table>
                                                                    <tr>
                                                                        <td align="right">Elejir Archivo </td>
                                                                        <td><input type="file" id="uploadFileCarteraReclamo" name="uploadFileCarteraReclamo" ></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2" align="center"><button onclick="uploadFileReclamo()" class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all" id="btnUploadFileReclamo" ><span class="ui-button-text">Cargar Cabeceras</span></button></td>
                                                                        <td><input type="hidden" id="hddFileReclamo"/></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>

                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div id="selectHeaderNotCarteraReclamo" style="display:none;" align="center" >
                                                <table cellpadding="0" cellspacing="0" border="0">
                                                    <tr class="ui-state-default" >
                                                        <td style="padding:3px 15px;border:1px solid #CDC3B7;width:730px;" ><strong>Nuevas Cabeceras</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td id="TDnewHeaderCarteraReclamo" align="center" style="border-left:1px solid #CDC3B7; border-right:1px solid #CDC3B7; border-bottom:1px solid #CDC3B7;color:#F00;width:730px;">

                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div id="layerHeaderReclamo">
                                                <table>
                                                    <tr>
                                                        <td align="right">Codigo Cliente</td>
                                                        <td align="left"><select id="codigo_cliente" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Numero Cuenta</td>
                                                        <td align="left"><select id="numero_cuenta" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Moneda</td>
                                                        <td align="left"><select id="moneda" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Telefono</td>
                                                        <td align="left"><select id="telefono" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">Codigo Operacion</td>
                                                        <td align="left"><select id="codigo_operacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Nro Reclamo</td>
                                                        <td align="left"><select id="numero_reclamo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Estado</td>
                                                        <td align="left"><select id="estado_reclamo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Fecha Reclamo</td>
                                                        <td align="left"><select id="fecha_reclamo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">Fecha Liquidacion</td>
                                                        <td align="left"><select id="fecha_liquidacion_reclamo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Fecha Recepcion</td>
                                                        <td align="left"><select id="fecha_recepcion_reclamo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Observacion</td>
                                                        <td align="left"><select id="observacion_reclamo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right"></td>
                                                        <td align="left"></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div align="center" style="padding:10px 0;">
                                                <button onclick="generateReclamo()" class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all"><span class="ui-button-text">Cargar reclamos</span></button>
                                                <button class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all" onclick="cancel_carga_cartera_reclamo()"><span class="ui-button-text">Cancelar</span></button>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div id="panelCargarRRLL" style="display:none;width:100%;border:0 none;" align="center">
                                <table>
                                    <tr>
                                        <td>
                                            <div>
                                                <table>
                                                    <tr>
                                                        <td align="right">Campa&ntilde;a</td>
                                                        <td><select id="cboCampaniaRRLL" class="combo" onchange="listar_cartera_rrll(this.value)"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Cartera</td>
                                                        <td><select id="cbCarteraRRLL" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Caracter Separador</td>
                                                        <td align="left">
                                                            <select id="cbCaracterSeparadorRRLL" class="combo">
                                                                <option value="|">|</option>
                                                                <option value=";">;</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <table>
                                                    <tr>
                                                        <td align="right">Elejir Archivo </td>
                                                        <td><input type="file" id="uploadFileCarteraRRLL" name="uploadFileCarteraRRLL" ></td>
                                                        <td colspan="2" align="center"><button onclick="uploadFileRRLL()" class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all" id="btnUploadFileRRLL" ><span class="ui-button-text">Cargar Cabeceras</span></button></td>
                                                        <td><input type="hidden" id="hddFileRRLL"/></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div id="selectHeaderNotCarteraRRLL" style="display:none;" align="center" >
                                                <table cellpadding="0" cellspacing="0" border="0">
                                                    <tr class="ui-state-default" >
                                                        <td style="padding:3px 15px;border:1px solid #CDC3B7;width:730px;" ><strong>Nuevas Cabeceras</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td id="TDnewHeaderCarteraRRLL" align="center" style="border-left:1px solid #CDC3B7; border-right:1px solid #CDC3B7; border-bottom:1px solid #CDC3B7;color:#F00;width:730px;">

                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div id="layerHeaderRRLL">
                                                <table>
                                                    <tr>
                                                        <td align="right">Codigo Cliente</td>
                                                        <td align="left"><select id="codigo_cliente" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Numero Cuenta</td>
                                                        <td align="left"><select id="numero_cuenta" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Moneda</td>
                                                        <td align="left"><select id="moneda" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">Telefono</td>
                                                        <td align="left"><select id="telefono" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Contacto</td>
                                                        <td align="left"><select id="contacto" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right"></td>
                                                        <td align="left"></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div align="center" style="padding:10px 0;">
                                                <button onclick="generateRRLL()" class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all"><span class="ui-button-text">Cargar RRLL</span></button>
                                                <button class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all" onclick="cancel_carga_cartera_rrll()"><span class="ui-button-text">Cancelar</span></button>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div id="panelCargarNOCpredictivo" style="display:none;width:100%; height:100%;border:0 none; padding-top:7px" align="center">
                                <div style="width:50%">
                                    <div class="backPanel headerPanel ui-corner-top" style="height:20px; padding-top:5px; font-weight:bold" align="center">CARGA DE NOC PREDICTIVO</div>
                                    <div align="center" class="ui-widget-content ui-corner-bottom" style="padding:10px 30px 20px 30px"><br>
                                            <table><!--formulario de datos-->
                                                <tr>
                                                    <td align="right" class="text-blue">Caracter Separador</td>
                                                    <td align="left">
                                                        <select id="cbCaracterSeparadorNOCPre" class="combo">
                                                            <option value=";">;</option>
                                                            <option value="|">Tab</option>
                                                            <option value="|">|</option>
                                                        </select>
                                                    </td>
                                                    <td width="20"></td>
                                                    <td align="right" class="text-blue">Formato de Fechas</td>
                                                    <td align="left">
                                                        <select id="cbFormatoFechasNOCPre" class="combo">
                                                            <option value="%d/%m/%Y">dd/mm/aaaa</option>
                                                            <option value="%Y-%m-%d">aaaa-mm-dd</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </table><!--/formulario de datos--><br />
                                            <table>
                                                <tr>
                                                        <td align="right" class="text-blue">Estado Noc Predictivo</td>
                                                        <td align="left">
                                                                <select style="width:300px;" id="cbEstadoNocPre" class="combo">
                                                                        <option value="0">--Seleccione--</option>
                                                                </select>
                                                        </td>
                                                </tr>
                                            </table>
                                            <table><!--carga archivo archivo-->
                                                <tr>
                                                    <td align="center">
                                                        <input type="hidden" id="hddFileNocPre"/>
                                                        <div id="file_uploadNocPre" class="file_upload" style="width:250px">
                                                            <form action="" method="POST" enctype="multipart/form-data" class="file_upload" style="width:250px">
                                                                <input type="hidden" name="error" value="0" id="loadHeaderError" />
                                                                <input type="hidden" name="error" value="" id="loadHeaderErrorMsg" />
                                                                <input type="file" name="file[]" multiple="">
                                                                    <button type="submit">Upload</button>
                                                                    <div class="file_upload_label">Subir Archivo(s) NOC Predictivo</div>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table id="filesNocPre">
                                                            <tbody>
                                                                <tr class="file_upload_template" style="display:none;">
                                                                    <td class="file_upload_preview"></td>
                                                                    <td class="file_name"></td>
                                                                    <td class="file_size"></td>
                                                                    <td class="file_upload_progress"><div></div></td>
                                                                    <td class="file_upload_start"><button>Start</button></td>
                                                                    <td class="file_upload_cancel"><button>Cancel</button></td>
                                                                </tr>
                                                                <tr class="file_download_template" style="display:none;">
                                                                    <td class="file_download_preview"></td>
                                                                    <td class="file_name"><a></a></td>
                                                                    <td class="file_size"></td>
                                                                    <td class="file_download_delete" colspan="3"><button>Delete</button></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <div class="file_upload_overall_progress"><div style="display: none; " class="ui-progressbar ui-widget ui-widget-content ui-corner-all" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="ui-progressbar-value ui-widget-header ui-corner-left" style="display: none; width: 0%; "></div></div></div>
                                                        <div class="file_upload_buttons">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                            <table width="400">
                                                <tr>
                                                    <td>
                                                        <div id="msg_resultado_masivo_nocpre" class="ui-state-error ui-corner-all paddingMsg" align="center"></div>
                                                    </td>
                                                </tr>
                                            </table><!--/carga archivo archivo-->
                                    </div>
                                </div><br />
                                <div class="noteDiv ui-corner-all" style="width:75%">
                                    <table cellpadding="2" cellspacing="2" border="0" style="width:100%;" >
                                        <tr>
                                            <td class="">
                                                <div class="text-alert">IMPORTANTE</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <ul>
                                                    <b>Formatos Soportados</b>
                                                    <li class="text-alert">
                                                        Solo soporta archivos de texto <b>( .txt )</b>
                                                    </li>
                                                </ul>
                                                <ul>
                                                    <b>Notas importantes</b>
                                                    <li class="text-alert">
                                                El archivo txt debe contener 5 campos : <b>ID;ESTADO;Fecha/Hora;Hora;Telefono</b>, que es el formato comun que trabaja la Empresa.
                                                    </li>
                                                    <li>
                                                        La primera fila del archivo debe de contener las cabeceras
                                                    </li>
                                                    <li>
                                                        Los campos deben estar separados por "<b>;</b>", "<b>tab</b>" o "<b>|</b>", lo que debera indicar en el combo <b>Caracter Separador</b>
                                                    </li>
                                                    <li>
                                                        Las fechas debe estar en el formato <b>dd/mm/aaaa</b> o <b>aaaa-mm-dd</b> lo que debe indicar en le combo <b>Formato de Fechas</b>. Las fechas que tengan otros formatos serán ignoradas.
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div id="panelCargarRetiros" style="display:none;width:100%; height:100%;border:0 none; padding-top:7px" align="center">
                                <div style="width:60%">
                                    <div class="backPanel headerPanel ui-corner-top" style="height:20px; padding-top:5px; font-weight:bold" align="center">CARGA DE RETIROS</div>
                                    <div align="center" class="ui-widget-content ui-corner-bottom" style="padding:10px 30px 20px 30px"><br/>
                                        <table>
                                                <tr>
                                                        <td valign="top">
                                                                <table><!--formulario de datos-->
                                                                        <tr>
                                                                            <td align="right" class="text-blue">Campa&ntilde;a</td>
                                                                            <td align="left">
                                                                                <select id="cboCampaniaRetiro" onchange="load_cartera_tb_a_d( this.value, 'tb2CarterasCargaRetiro' )" class="combo">
                                                                                    <option value="0">--Seleccione--</option>
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right" class="text-blue">Caracter Separador</td>
                                                                            <td align="left">
                                                                                <select id="cbCaracterSeparadorRetiro" class="combo">
                                                                                    <option value="tab">Tab</option>
                                                                                    <option value=";">;</option>
                                                                                    <option value="|">|</option>
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right" class="text-blue">Formato de Fechas</td>
                                                                            <td align="left">
                                                                                <select id="cbFormatoFechasRetiro" class="combo">
                                                                                    <!--<option value="%d/%m/%Y">dd/mm/aaaa</option>-->
                                                                                    <option value="%Y-%m-%d">aaaa-mm-dd</option>
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                    </table><!--/formulario de datos-->
                                                        </td>
                                                        <td>
                                                                <div>
                                                                        <div class="ui-widget-header ui-corner-top" style="padding:0px 3px;" >Carteras</div>
                                                                        <div style="overflow:auto;height:150px;width:240px;" >
                                                                            <table cellpadding="0" cellspacing="0" border="0" id="tb2CarterasCargaRetiro" ></table>
                                                                        </div>
                                                                        <div class="ui-widget-header ui-corner-bottom" >
                                                                            <table>
                                                                                <tr>
                                                                                    <td>Buscar:</td>
                                                                                    <td><input type="text" onkeyup="search_text_table(this.value,'tb2CarterasCargaRetiro')" class="cajaForm" /></td>
                                                                                </tr>
                                                                            </table>
                                                                        </div>
                                                                </div>
                                                        </td>
                                                </tr>
                                            
                                        </table>
                                            <br />
                                            <table><!--carga archivo archivo-->
                                                <tr>
                                                    <td align="center">
                                                        <input type="hidden" id="hddFileRetiro"/>
                                                        <div id="file_uploadRetiro" class="file_upload">
                                                            <form action="" method="POST" enctype="multipart/form-data" class="file_upload">
                                                                <input type="hidden" name="error" value="0" id="loadHeaderError" />
                                                                <input type="hidden" name="error" value="" id="loadHeaderErrorMsg" />
                                                                <input type="file" name="file[]" multiple="">
                                                                    <button type="submit">Upload</button>
                                                                    <div class="file_upload_label">Subir Archivo(s) Retiros</div>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table id="filesRetiro">
                                                            <tbody>
                                                                <tr class="file_upload_template" style="display:none;">
                                                                    <td class="file_upload_preview"></td>
                                                                    <td class="file_name"></td>
                                                                    <td class="file_size"></td>
                                                                    <td class="file_upload_progress"><div></div></td>

                                                                    <td class="file_upload_start"><button>Start</button></td>
                                                                    <td class="file_upload_cancel"><button>Cancel</button></td>
                                                                </tr>
                                                                <tr class="file_download_template" style="display:none;">
                                                                    <td class="file_download_preview"></td>
                                                                    <td class="file_name"><a></a></td>
                                                                    <td class="file_size"></td>
                                                                    <td class="file_download_delete" colspan="3"><button>Delete</button></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <div class="file_upload_overall_progress"><div style="display: none; " class="ui-progressbar ui-widget ui-widget-content ui-corner-all" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="ui-progressbar-value ui-widget-header ui-corner-left" style="display: none; width: 0%; "></div></div></div>
                                                        <div class="file_upload_buttons">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                            <table width="400">
                                                <tr>
                                                    <td>
                                                        <div id="msg_resultado_masivo_retiro" class="ui-state-error ui-corner-all paddingMsg" align="center"></div>
                                                    </td>
                                                </tr>
                                            </table><!--/carga archivo archivo-->
                                    </div>
                                </div><br />
                                <div class="noteDiv ui-corner-all" style="width:75%">
                                    <table cellpadding="2" cellspacing="2" border="0" style="width:100%;" >
                                        <tr>
                                            <td class="">
                                                <div class="text-alert">IMPORTANTE</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <ul>
                                                    <b>Formatos Soportados</b>
                                                    <li class="text-alert">
                                                        Solo soporta archivos de texto <b>( .txt )</b>
                                                    </li>
                                                    <li>
                                                Si el archivo esta en formato Excel ( .xls ), copielo de excel a Block de Notas y guardelo como txt. Si tiene dudas para realizar esto, porfavor solicite apoyo al Area de Sistemas.
                                                    </li>
                                                </ul>
                                                <ul>
                                                    <b>Notas importantes</b>
                                                    <li class="text-alert">
                                                El archivo txt debe contener 7 campos : <b>Inscripcion(Numero Cuenta), Gestion, Fecha_Ini_Ges, Fecha_Fin_ges, Des_Agencia, Fecha_Retiro, Motivo_Retiro, Marca</b>. Separados por tab, que es el formato comun que trabaja la Empresa. Tambien acepta sin el campo Marca.
                                                    </li>
                                                    <li>
                                                        La primera fila del archivo debe de contener las cabeceras
                                                    </li>
                                                    <li>
                                                        Los campos deben estar separados por "<b>tab</b>".
                                                    </li>
                                                    <li>
                                                        Las fechas debe estar en el formato <b>aaaa-mm-dd</b>. Las fechas que tengan otros formatos serán ignoradas.
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div id="panelCorteFocalizado" style="display:none;width:100%; height:100%;border:0 none; padding-top:7px" align="center">
                                <div style="width:50%">
                                    <div class="backPanel headerPanel ui-corner-top" style="height:20px; padding-top:5px; font-weight:bold" align="center">CARGA DE CORTES FOCALIZADOS</div>
                                    <div align="center" class="ui-widget-content ui-corner-bottom" style="padding:10px 30px 20px 30px"><br>
                                            <!--formulario de datos-->
                                            <table>
                                                <tr>
                                                    <td align="right" class="text-blue">Caracter Separador</td>
                                                    <td align="left">
                                                        <select id="cbCaracterSeparadorCorteFocalizado" class="combo">
                                                            <option value="|">Tab</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </table>
                                            <!--/formulario de datos--><br />
                                            <!--carga archivo archivo-->
                                            <table>
                                                <tr>
                                                    <td align="center">
                                                        <input type="hidden" id="hddFileCorteFocalizado"/>
                                                        <div id="file_uploadCorteFocalizado" class="file_upload">
                                                            <form action="" method="POST" enctype="multipart/form-data" class="file_upload">
                                                                <input type="hidden" name="error" value="0" id="loadHeaderError" />
                                                                <input type="hidden" name="error" value="" id="loadHeaderErrorMsg" />
                                                                <input type="file" name="file[]" multiple="">
                                                                    <button type="submit">Upload</button>
                                                                    <div class="file_upload_label">Subir Archivo(s)</div>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table id="filesCorteFocalizado">
                                                            <tbody>
                                                                <tr class="file_upload_template" style="display:none;">
                                                                    <td class="file_upload_preview"></td>
                                                                    <td class="file_name"></td>
                                                                    <td class="file_size"></td>
                                                                    <td class="file_upload_progress"><div></div></td>

                                                                    <td class="file_upload_start"><button>Start</button></td>
                                                                    <td class="file_upload_cancel"><button>Cancel</button></td>
                                                                </tr>
                                                                <tr class="file_download_template" style="display:none;">
                                                                    <td class="file_download_preview"></td>
                                                                    <td class="file_name"><a></a></td>
                                                                    <td class="file_size"></td>
                                                                    <td class="file_download_delete" colspan="3"><button>Delete</button></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <div class="file_upload_overall_progress"><div style="display: none; " class="ui-progressbar ui-widget ui-widget-content ui-corner-all" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="ui-progressbar-value ui-widget-header ui-corner-left" style="display: none; width: 0%; "></div></div></div>
                                                        <div class="file_upload_buttons">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                            <!--<div>
                                                                                     <textarea id="txtIdsCuenta" style="width : 600px;height:300px;"></textarea>
                                    <button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" style="padding : 7px;" onclick="actuliazar_corte_focalizado()">Actualziar Cortes Focalizado</button>
                                            </div>-->
                                            <table width="400">
                                                <tr>
                                                    <td>
                                                        <div id="msg_resultado_corte_focalizado" class="ui-state-error ui-corner-all paddingMsg" align="center"></div>
                                                    </td>
                                                </tr>
                                            </table><!--/carga archivo archivo-->
                                    </div>
                                </div><br />
                                <div class="noteDiv ui-corner-all" style="width:75%">
                                    <table cellpadding="2" cellspacing="2" border="0" style="width:100%;" >
                                        <tr>
                                            <td class="">
                                                <div class="text-alert">IMPORTANTE</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <ul>
                                                    <b>Formatos Soportados</b>
                                                    <li class="text-alert">
                                                        Solo soporta archivos de texto <b>( .txt )</b>
                                                    </li>
                                                    <li>
                                                Si el archivo esta en formato Excel ( .xls ), copielo de excel a Block de Notas y guardelo como txt. Si tiene dudas para realizar esto, porfavor solicite apoyo al Area de Sistemas.
                                                    </li>
                                                </ul>
                                                <!--<ul>
                                                    <b>Notas importantes</b>
                                                    <li class="text-alert">
                                                El archivo txt debe contener 7 campos : <b>Inscripcion, Gestion, Fecha_Ini_Ges, Fecha_Fin_ges, Des_Agencia, Fecha_Retiro, Motivo_Retiro, Marca</b>. Separados por tab, que es el formato comun que trabaja la Empresa. Tambien acepta sin el campo Marca.
                                                    </li>
                                                    <li>
                                                        La primera fila del archivo debe de contener las cabeceras
                                                    </li>
                                                    <li>
                                                        Los campos deben estar separados por "<b>tab</b>".
                                                    </li>
                                                    <li>
                                                        Las fechas debe estar en el formato <b>aaaa-mm-dd</b>. Las fechas que tengan otros formatos serán ignoradas.
                                                    </li>
                                                </ul>-->
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div id="panelFacturacion" style="display:none;width:100%; height:100%;border:0 none; padding-top:7px" align="center">
                                <div style="width:50%">
                                    <div class="backPanel headerPanel ui-corner-top" style="height:20px; padding-top:5px; font-weight:bold" align="center">PROCESAR FACTURACION Y COMISIONES</div>
                                    <div align="center" class="ui-widget-content ui-corner-bottom" style="padding:10px 30px 20px 30px">
                                            <!--formulario de datos-->
                                            <table>
                                                <tr>
                                                    <td align="right" class="text-blue">Caracter Separador</td>
                                                    <td align="left">
                                                        <select id="cbCaracterSeparadorFacturacion" class="combo">
                                                            <option value="|">Tab</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </table>
                                            <!--/formulario de datos--><br />
                                            <!--carga archivo archivo-->
                                            <table>
                                                <tr>
                                                    <td align="center">
                                                        <input type="hidden" id="hddFileFacturacion"/>
                                                        <div id="file_uploadFacturacion" class="file_upload">
                                                            <form action="" method="POST" enctype="multipart/form-data" class="file_upload">
                                                                <input type="hidden" name="error" value="0" id="loadHeaderError" />
                                                                <input type="hidden" name="error" value="" id="loadHeaderErrorMsg" />
                                                                <input type="file" name="file[]" multiple="">
                                                                    <button type="submit">Upload</button>
                                                                    <div class="file_upload_label">Subir Archivo(s)</div>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table id="filesFacturacion">
                                                            <tbody>
                                                                <tr class="file_upload_template" style="display:none;">
                                                                    <td class="file_upload_preview"></td>
                                                                    <td class="file_name"></td>
                                                                    <td class="file_size"></td>
                                                                    <td class="file_upload_progress"><div></div></td>

                                                                    <td class="file_upload_start"><button>Start</button></td>
                                                                    <td class="file_upload_cancel"><button>Cancel</button></td>
                                                                </tr>
                                                                <tr class="file_download_template" style="display:none;">
                                                                    <td class="file_download_preview"></td>
                                                                    <td class="file_name"><a></a></td>
                                                                    <td class="file_size"></td>
                                                                    <td class="file_download_delete" colspan="3"><button>Delete</button></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <div class="file_upload_overall_progress"><div style="display: none; " class="ui-progressbar ui-widget ui-widget-content ui-corner-all" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="ui-progressbar-value ui-widget-header ui-corner-left" style="display: none; width: 0%; "></div></div></div>
                                                        <div class="file_upload_buttons">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                            <!--<div>
                                                                                     <textarea id="txtIdsCuenta" style="width : 600px;height:300px;"></textarea>
                                    <button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" style="padding : 7px;" onclick="actuliazar_corte_focalizado()">Actualziar Cortes Focalizado</button>
                                            </div>-->
                                            <table width="400">
                                                <tr>
                                                    <td>
                                                        <div id="msg_resultado_facturacion" class="ui-state-error ui-corner-all paddingMsg" align="center"></div>
                                                    </td>
                                                </tr>
                                            </table><!--/carga archivo archivo-->
                                    </div>
                                </div>
                                <div class="noteDiv ui-corner-all" style="width:75%">
                                    <table cellpadding="2" cellspacing="2" border="0" style="width:100%;" >
                                        <tr>
                                            <td class="">
                                                <div class="text-alert">IMPORTANTE</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <ul>
                                                    <b>Formatos Soportados</b>
                                                    <li class="text-alert">
                                                        Solo soporta archivos de texto <b>( .txt )</b>
                                                    </li>
                                                    <li>
                                                Si el archivo esta en formato Excel ( .xls ), copielo de excel a Block de Notas y guardelo como txt. Si tiene dudas para realizar esto, porfavor solicite apoyo al Area de Sistemas.
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div id="panelCargarIVR" style="display:none;width:100%; height:100%;border:0 none; padding-top:7px" align="center">
                                <div style="width:50%">
                                    <div class="backPanel headerPanel ui-corner-top" style="height:20px; padding-top:5px; font-weight:bold" align="center">CARGA DE IVR</div>
                                    <div align="center" class="ui-widget-content ui-corner-bottom" style="padding:10px 30px 20px 30px"><br>
                                            <!--<table>
                                                <tr>
                                                    <td align="right" class="text-blue">Campa&ntilde;a</td>
                                                    <td align="left"><select class="combo" id="cboCampaniaIVR" onchange="listar_carteras( this.value, [{id:'cboCarteraIVR'}] )" style="width:140px;"><option value="0">--Seleccione--</option></select></td>
                                                    <td align="right" class="text-blue">Cartera</td>
                                                    <td align="left"><select class="combo" id="cboCarteraIVR" style="width:140px;"><option value="0">--Seleccione--</option></select></td>
                                                </tr>
                                            </table>-->
                                            <table><!--formulario de datos-->
                                                <tr>
                                                    <td align="right" class="text-blue">Caracter Separador</td>
                                                    <td align="left">
                                                        <select id="cbCaracterSeparadorIVR" class="combo">
                                                            <option value="tab">Tab</option>
                                                            <option value=";">;</option>
                                                            <option value="|">|</option>
                                                        </select>
                                                    </td>
                                                    <td width="20"></td>
                                                    <td align="right" class="text-blue">Formato de Fechas</td>
                                                    <td align="left">
                                                        <select id="cbFormatoFechasIVR" class="combo">
                                                            <!--<option value="%d/%m/%Y">dd/mm/aaaa</option>-->
                                                            <option value="%Y-%m-%d">aaaa-mm-dd</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </table><!--/formulario de datos--><br />
                                            <table>
                                                <tr>
                                                        <td align="right" class="text-blue">Estado Contactados</td>
                                                        <td align="left">
                                                             <select style="width:300px;" id="cbEstadoContacIVR" class="combo">
                                                                 <option value="0">--Seleccione--</option>
                                                             </select>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td align="right" class="text-blue">Estado No Contactados</td>
                                                        <td align="left">
                                                                <select style="width:300px;" id="cbEstadoNoContacIVR" class="combo">
                                                                        <option value="0">--Seleccione--</option>
                                                                </select>
                                                        </td>
                                                </tr>
                                            </table>
                                            <table><!--carga archivo archivo-->
                                                <tr>
                                                    <td align="center">
                                                        <input type="hidden" id="hddFileIVR"/>
                                                        <div id="file_uploadIVR" class="file_upload">
                                                            <form action="" method="POST" enctype="multipart/form-data" class="file_upload">
                                                                <input type="hidden" name="error" value="0" id="loadHeaderError" />
                                                                <input type="hidden" name="error" value="" id="loadHeaderErrorMsg" />
                                                                <input type="file" name="file[]" multiple="">
                                                                    <button type="submit">Upload</button>
                                                                    <div class="file_upload_label">Subir Archivo(s) IVR</div>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table id="filesIVR">
                                                            <tbody>
                                                                <tr class="file_upload_template" style="display:none;">
                                                                    <td class="file_upload_preview"></td>
                                                                    <td class="file_name"></td>
                                                                    <td class="file_size"></td>
                                                                    <td class="file_upload_progress"><div></div></td>
                                                                    <td class="file_upload_start"><button>Start</button></td>
                                                                    <td class="file_upload_cancel"><button>Cancel</button></td>
                                                                </tr>
                                                                <tr class="file_download_template" style="display:none;">
                                                                    <td class="file_download_preview"></td>
                                                                    <td class="file_name"><a></a></td>
                                                                    <td class="file_size"></td>
                                                                    <td class="file_download_delete" colspan="3"><button>Delete</button></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <div class="file_upload_overall_progress"><div style="display: none; " class="ui-progressbar ui-widget ui-widget-content ui-corner-all" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="ui-progressbar-value ui-widget-header ui-corner-left" style="display: none; width: 0%; "></div></div></div>
                                                        <div class="file_upload_buttons">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                            <table width="400">
                                                <tr>
                                                    <td>
                                                        <div id="msg_resultado_masivo_IVR" class="ui-state-error ui-corner-all paddingMsg" align="center"></div>
                                                    </td>
                                                </tr>
                                            </table><!--/carga archivo archivo-->
                                    </div>
                                </div><br />
                                <div class="noteDiv ui-corner-all" style="width:75%">
                                    <table cellpadding="2" cellspacing="2" border="0" style="width:100%;" >
                                        <tr>
                                            <td class="">
                                                <div class="text-alert">IMPORTANTE</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <ul>
                                                    <b>Formatos Soportados</b>
                                                    <li class="text-alert">
                                                        Solo soporta archivos de texto <b>( .txt )</b>
                                                    </li>
                                                    <li>
                                                Si el archivo esta en formato Excel ( .xls ), copielo de excel a Block de Notas y guardelo como txt. Si tiene dudas para realizar esto, porfavor solicite apoyo al Area de Sistemas.
                                                    </li>
                                                </ul>
                                                <ul>
                                                    <b>Notas importantes</b>
                                                    <li class="text-alert">
                                                El archivo txt debe contener 5 campos : <b>Numero, Campa&ntilde;a, Estado, Fecha, Hora</b>. Que es el formato comun que trabaja la Empresa.
                                                    </li>
                                                    <li>
                                                        La primera fila del archivo debe de contener las cabeceras
                                                    </li>
                                                    <li>
                                                        Los campos deben estar separados por "<b>tab</b>".
                                                    </li>
                                                    <li>
                                                        Las fechas debe estar en el formato <b>aaaa-mm-dd</b>. Las fechas que tengan otros formatos serán ignoradas.
                                                    </li>
                                                    <li>
                                                        Las horas debe estar en el formato <b>hh:mm:ss</b>. Las horas que tengan otros formatos serán ignoradas.
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div id="panelCourier" style="display:none;width:100%; height:100%;border:0 none; padding-top:7px" align="center">
                                        <div style="width:50%">
                                                <div class="backPanel headerPanel ui-corner-top" style="height:20px; padding-top:5px; font-weight:bold" align="center">CARGA DE COURIER Y VISITAS</div>
                                                <div align="center" class="ui-widget-content ui-corner-bottom" style="padding:10px 30px 20px 30px">
                                                        </br>
                                                        <table>
                                                                <tr>
                                                                    <td align="right" class="text-blue">Caracter Separador</td>
                                                                    <td align="left">
                                                                        <select id="cbCaracterSeparadorCourier" class="combo">
                                                                            <option value="tab">Tab</option>
                                                                            <option value=";">;</option>
                                                                            <option value="|">|</option>
                                                                        </select>
                                                                    </td>
                                                                    <td width="20"></td>
                                                                    <td align="right" class="text-blue">Tipo</td>
                                                                    <td align="left">
                                                                        <select id="cbTipoCargaCourier" class="combo">
                                                                            <option value="VIS">VISITA</option>
                                                                            <option value="COUR">COURIER</option>
                                                                        </select>
                                                                    </td>
                                                                    <td width="20"></td>
                                                                    <td align="right" class="text-blue">Formato de Fechas</td>
                                                                    <td align="left">
                                                                        <select id="cbFormatoFechasCourier" class="combo">
                                                                            <!--<option value="%d/%m/%Y">dd/mm/aaaa</option>-->
                                                                            <option value="%Y-%m-%d">aaaa-mm-dd</option>
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                        </table>
                                                        <table>
                                                                <tr>
                                                                        <td align="center">
                                                                                <input type="hidden" id="hddFileCourier"/>
                                                                                <div id="file_uploadCourier" class="file_upload">
                                                                                    <form action="" method="POST" enctype="multipart/form-data" class="file_upload" style="width:18em;" >
                                                                                        <input type="hidden" name="error" value="0" id="loadHeaderError" />
                                                                                        <input type="hidden" name="error" value="" id="loadHeaderErrorMsg" />
                                                                                        <input type="file" name="file[]" multiple=""/>
                                                                                        <button type="submit">Upload</button>
                                                                                        <div class="file_upload_label">Subir Archivo(s) Courier o Visita</div>
                                                                                    </form>
                                                                                </div>
                                                                        </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <table id="filesCourier">
                                                                            <tbody>
                                                                                <tr class="file_upload_template" style="display:none;">
                                                                                    <td class="file_upload_preview"></td>
                                                                                    <td class="file_name"></td>
                                                                                    <td class="file_size"></td>
                                                                                    <td class="file_upload_progress"><div></div></td>
                                                                                    <td class="file_upload_start"><button>Start</button></td>
                                                                                    <td class="file_upload_cancel"><button>Cancel</button></td>
                                                                                </tr>
                                                                                <tr class="file_download_template" style="display:none;">
                                                                                    <td class="file_download_preview"></td>
                                                                                    <td class="file_name"><a></a></td>
                                                                                    <td class="file_size"></td>
                                                                                    <td class="file_download_delete" colspan="3"><button>Delete</button></td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <div class="file_upload_overall_progress"><div style="display: none; " class="ui-progressbar ui-widget ui-widget-content ui-corner-all" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="ui-progressbar-value ui-widget-header ui-corner-left" style="display: none; width: 0%; "></div></div></div>
                                                                        <div class="file_upload_buttons">
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                        </table>
                                                        <table width="400">
                                                                <tr>
                                                                    <td>
                                                                        <div id="msg_resultado_masivo_courier" class="ui-state-error ui-corner-all paddingMsg" align="center"></div>
                                                                    </td>
                                                                </tr>
                                                        </table>
                                                </div>
                                        </div>
                                        <br/>
                                        <div class="noteDiv ui-corner-all" style="width:75%">
                                            <table cellpadding="2" cellspacing="2" border="0" style="width:100%;" >
                                                <tr>
                                                    <td class="">
                                                        <div class="text-alert">IMPORTANTE</div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <ul>
                                                            <b>Formatos Soportados</b>
                                                            <li class="text-alert">
                                                                Solo soporta archivos de texto <b>( .txt )</b>
                                                            </li>
                                                            <li>
                                                        Si el archivo esta en formato Excel ( .xls ), copielo de excel a Block de Notas y guardelo como txt. Si tiene dudas para realizar esto, porfavor solicite apoyo al Area de capacitacion.
                                                            </li>
                                                        </ul>
                                                        <ul>
                                                            <b>Notas importantes</b>
                                                            <li class="text-alert">
                                                                El archivo txt debe contener 11 campos : <b>Tipo Visita , Inscripcion , Codigo Gestor, Codigo Estado Gestion, Fecha Visita, Fecha Recepcion, Fecha Cp, Monto Cp, Moneda Cp, Observacion, Descripcion Inmueble</b>. En el orden indicado.
                                                            </li>
                                                            <li>
                                                                La primera fila del archivo debe de contener las cabeceras
                                                            </li>
                                                            <li>
                                                                Las fechas debe estar en el formato <b>aaaa-mm-dd</b>. Las fechas que tengan otros formatos serán ignoradas.
                                                            </li>
                                                            <li>
                                                                Los montos deben estar en formato numerico . Los montos que tengan otros formatos serán ignoradas.
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                                        </div>
                            <div id="panelEstadoCuenta" style="display:none;width:100%;border:0 none;padding-top:7px" align="center">

                                        <div style="width:50%">
                                                <div class="backPanel headerPanel ui-corner-top" style="height:20px; padding-top:5px; font-weight:bold" align="center">CARGA DE ESTADOS DE CUENTA</div>
                                                <div align="center" class="ui-widget-content ui-corner-bottom" style="padding:10px 30px 20px 30px">
                                                        </br>
                                                        <table>
                                                                <tr>
                                                                    <td align="right">Campa&ntilde;a</td>
                                                                    <td align="left">
                                                                        <select class="combo" id="cboCampaniaEstadoCuenta">
                                                                                <option value="0">--Seleccione--</option>
                                                                        </select>
                                                                    </td>
                                                                    <td align="right" class="text-blue">Caracter Separador</td>
                                                                    <td align="left">
                                                                        <select id="cbCaracterSeparadorEstadoCuenta" class="combo">
                                                                            <option value="tab">Tab</option>
                                                                            <option value=";">;</option>
                                                                            <option value="|">|</option>
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                        </table>
                                                        <table>
                                                                <tr>
                                                                        <td align="center">
                                                                                <input type="hidden" id="hddFileEstadoCuenta"/>
                                                                                <div id="file_uploadEstadoCuenta" class="file_upload">
                                                                                    <form action="" method="POST" enctype="multipart/form-data" class="file_upload" style="width:18em;" >
                                                                                        <input type="hidden" name="error" value="0" id="loadHeaderEstadoCuentaError" />
                                                                                        <input type="hidden" name="error" value="" id="loadHeaderEstadoCuentaErrorMsg" />
                                                                                        <input type="file" name="file[]" multiple=""/>
                                                                                        <button type="submit">Upload</button>
                                                                                        <div class="file_upload_label">Subir Archivo(s) Estado de Cuenta</div>
                                                                                    </form>
                                                                                </div>
                                                                        </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <table id="filesEstadoCuenta">
                                                                            <tbody>
                                                                                <tr class="file_upload_template" style="display:none;">
                                                                                    <td class="file_upload_preview"></td>
                                                                                    <td class="file_name"></td>
                                                                                    <td class="file_size"></td>
                                                                                    <td class="file_upload_progress"><div></div></td>
                                                                                    <td class="file_upload_start"><button>Start</button></td>
                                                                                    <td class="file_upload_cancel"><button>Cancel</button></td>
                                                                                </tr>
                                                                                <tr class="file_download_template" style="display:none;">
                                                                                    <td class="file_download_preview"></td>
                                                                                    <td class="file_name"><a></a></td>
                                                                                    <td class="file_size"></td>
                                                                                    <td class="file_download_delete" colspan="3"><button>Delete</button></td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <div class="file_upload_overall_progress"><div style="display: none; " class="ui-progressbar ui-widget ui-widget-content ui-corner-all" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="ui-progressbar-value ui-widget-header ui-corner-left" style="display: none; width: 0%; "></div></div></div>
                                                                        <div class="file_upload_buttons">
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                        </table>
                                                        <table width="400">
                                                                <tr>
                                                                    <td>
                                                                        <div id="msg_resultado_masivo_estado_cuenta" class="ui-state-error ui-corner-all paddingMsg" align="center"></div>
                                                                    </td>
                                                                </tr>
                                                        </table>
                                                </div>
                                        </div>
                                        <br/>
                                        <div class="noteDiv ui-corner-all" style="width:75%">
                                            <table cellpadding="2" cellspacing="2" border="0" style="width:100%;" >
                                                <tr>
                                                    <td class="">
                                                        <div class="text-alert">IMPORTANTE</div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <ul>
                                                            <b>Formatos Soportados</b>
                                                            <li class="text-alert">
                                                                Solo soporta archivos de texto <b>( .txt )</b>
                                                            </li>
                                                            <li>
                                                        Si el archivo esta en formato Excel ( .xls ), copielo de excel a Block de Notas y guardelo como txt. Si tiene dudas para realizar esto, porfavor solicite apoyo al Area de capacitacion.
                                                            </li>
                                                        </ul>
                                                        <ul>
                                                            <b>Notas importantes</b>
                                                            <li class="text-alert">
                                                                El archivo txt debe contener 5 campos : <b>Codigo Cliente, Numero de Cuenta, Moneda, Grupo, Status, Estado</b>. En el orden indicado.
                                                            </li>
                                                            <li class="text-alert">
                                                                Si su linea de negocio las cuentas no poseen MONEDA ni GRUPO entonces dejar esos campos vacios. EL campo Status solo debe tomar dos valores ACTIVO o DESACTIVO . En el campo ESTADO debe ir los estados de cuenta que maneja su linea de negocio
                                                            </li>
                                                            <li>
                                                                La primera fila del archivo debe de contener las cabeceras
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                            </div>
                            <div id="panelDeudaTotalC" style="display:none;width:100%;border:0 none;padding-top:7px" align="center">
                                
                                <div style="width:62%">
                                    <div class="backPanel headerPanel ui-corner-top" style="height:20px; padding-top:5px; font-weight:bold" align="center">DEUDA TOTAL</div>
                                    <div align="center" class="ui-widget-content ui-corner-bottom" style="padding:10px 30px 20px 30px">
                                        </br>
                                        <table>
                                            <tr>
                                                <td align="right" class="text-blue">Caracter Separador</td>
                                                <td align="left">
                                                    <select id="cbCaracterSeparadorSaldoTotal" class="combo">
                                                        <option value="tab">Tab</option>
                                                        <option value=";">;</option>
                                                        <option value="|">|</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                        <table>
                                            <tr>
                                                <td align="center">
                                                    <input type="hidden" id="hddFileSaldoTotal"/>
                                                    <div id="file_uploadSaldoTotal" class="file_upload">
                                                        <form action="" method="POST" enctype="multipart/form-data" class="file_upload" style="width:18em;" >
                                                            <input type="hidden" name="error" value="0" id="loadHeaderSaldoTotalError" />
                                                            <input type="hidden" name="error" value="" id="loadHeaderSaldoTotalErrorMsg" />
                                                            <input type="file" name="file[]" multiple=""/>
                                                            <button type="submit">Upload</button>
                                                            <div class="file_upload_label">Subir Archivo(s) Saldo Total</div>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <table id="filesSaldoTotal">
                                                        <tbody>
                                                            <tr class="file_upload_template" style="display:none;">
                                                                <td class="file_upload_preview"></td>
                                                                <td class="file_name"></td>
                                                                <td class="file_size"></td>
                                                                <td class="file_upload_progress"><div></div></td>
                                                                <td class="file_upload_start"><button>Start</button></td>
                                                                <td class="file_upload_cancel"><button>Cancel</button></td>
                                                            </tr>
                                                            <tr class="file_download_template" style="display:none;">
                                                                <td class="file_download_preview"></td>
                                                                <td class="file_name"><a></a></td>
                                                                <td class="file_size"></td>
                                                                <td class="file_download_delete" colspan="3"><button>Delete</button></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <div class="file_upload_overall_progress">
                                                        <div style="display: none; " class="ui-progressbar ui-widget ui-widget-content ui-corner-all" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                                            <div class="ui-progressbar-value ui-widget-header ui-corner-left" style="display: none; width: 0%; "></div>
                                                        </div>
                                                    </div>
                                                    <div class="file_upload_buttons"></div>
                                                </td>
                                            </tr>
                                        </table>
                                        <table width="400">
                                            <tr>
                                                <td>
                                                    <div id="msg_resultado_masivo_saldo_total" class="ui-state-error ui-corner-all paddingMsg" align="center"></div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="backPanel headerPanel ui-corner-top" style="height:20px; padding-top:5px; font-weight:bold;margin-top:10px;" align="center">FECHA VENCIMIENTO</div>
                                    <div align="center" class="ui-widget-content ui-corner-bottom" style="padding:10px 30px 20px 30px">
                                        <table>
                                            <tr>
                                                <td>Campa&ntilde;a:</td>
                                                <td>
                                                    <select class="combo" onchange="listar_carteras( this.value, [{id:'cboCarteraFechaVenc'}] )" id="cboCampaniaFechaVenc">
                                                        <option value="0">--Seleccione--</option>
                                                    </select>
                                                </td>
                                                <td>Cartera</td>
                                                <td>
                                                    <select class="combo" id="cboCarteraFechaVenc">
                                                        <option value="0">--Seleccione--</option>
                                                    </select>
                                                </td>
                                                <td>Fecha Vencimiento</td>
                                                <td><input readonly="readonly" id="txtCagCarFechaVencimiento" style="width:70px;" type="text" class="cajaForm" /></td>
                                                <td>
                                                    <button class="ui-state-default ui-corner-all" onclick="update_fecha_vencimiento()">Guardar</button>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <br/>
                                <div class="noteDiv ui-corner-all" style="width:75%">
                                    
                                    <table cellpadding="2" cellspacing="2" border="0" style="width:100%;" >
                                        <tr>
                                            <td class="">
                                                <div class="text-alert">IMPORTANTE</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <ul>
                                                    <b>Formatos Soportados</b>
                                                    <li class="text-alert">Solo soporta archivos de texto <b>( .txt )</b></li>
                                                    <li>Si el archivo esta en formato Excel ( .xls ), copielo de excel a Block de Notas y guardelo como txt. Si tiene dudas para realizar esto, porfavor solicite apoyo al Area de capacitacion.</li>
                                                </ul>
                                                <ul>
                                                    <b>Notas importantes</b>
                                                    <li class="text-alert">
                                                        El archivo txt debe contener 7 campos : <b>Empresa, Numero de Cuenta, Numero de Documento, Fecha Nacimiento, Saldo Total, Cod Suc y Grp Afinidad</b>. En el orden indicado.
                                                    </li>
                                                    <li>
                                                        El campo <span style="font-weight:bold;">SALDO TOTAL</span> debe estar en formato numerico ( ######.## )
                                                    </li>
                                                    <li>
                                                        La primera fila del archivo debe de contener las cabeceras
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div id="panelDetalleM" style="display:none;width:100%;border:0 none;padding-top:7px;" align="center" >
                                <div style="width:50%">
                                    <div class="backPanel headerPanel ui-corner-top" style="height:20px; padding-top:5px; font-weight:bold" align="center">DETALLE ( MOVIL )</div>
                                    <div align="center" class="ui-widget-content ui-corner-bottom" style="padding:10px 30px 20px 30px">
                                        </br>
                                        <table>
                                            <tr>
                                                <td align="right">Campa&ntilde;a</td>
                                                <td align="left">
                                                    <select class="combo" id="cboCampaniaDetalleM" onchange="listar_carteras(this.value,[{id:'cboCarteraDetalleM'}])" >
                                                        <option value="0">--Seleccione--</option>
                                                    </select>
                                                </td>
                                                <td align="right">Cartera</td>
                                                <td align="left">
                                                    <select class="combo" id="cboCarteraDetalleM">
                                                        <option value="0">--Seleccione--</option>
                                                    </select>
                                                </td>
                                                <td align="right" class="text-blue">Caracter Separador</td>
                                                <td align="left">
                                                    <select id="cbCaracterSeparadorDetalleM" class="combo">
                                                        <option value="tab">Tab</option>
                                                        <option value=";">;</option>
                                                        <option value="|">|</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                        <table>
                                            <tr>
                                                <td align="center">
                                                    <input type="hidden" id="hddFileDetalleM"/>
                                                    <div id="file_uploadDetalleM" class="file_upload">
                                                        <form action="" method="POST" enctype="multipart/form-data" class="file_upload" style="width:18em;" >
                                                            <input type="hidden" name="error" value="0" id="loadHeaderDetalleMError" />
                                                            <input type="hidden" name="error" value="" id="loadHeaderDetalleMErrorMsg" />
                                                            <input type="file" name="file[]" multiple=""/>
                                                            <button type="submit">Upload</button>
                                                            <div class="file_upload_label">Subir Archivo(s) Saldo Total</div>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <table id="filesDetalleM">
                                                        <tbody>
                                                            <tr class="file_upload_template" style="display:none;">
                                                                <td class="file_upload_preview"></td>
                                                                <td class="file_name"></td>
                                                                <td class="file_size"></td>
                                                                <td class="file_upload_progress"><div></div></td>
                                                                <td class="file_upload_start"><button>Start</button></td>
                                                                <td class="file_upload_cancel"><button>Cancel</button></td>
                                                            </tr>
                                                            <tr class="file_download_template" style="display:none;">
                                                                <td class="file_download_preview"></td>
                                                                <td class="file_name"><a></a></td>
                                                                <td class="file_size"></td>
                                                                <td class="file_download_delete" colspan="3"><button>Delete</button></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <div class="file_upload_overall_progress">
                                                        <div style="display: none; " class="ui-progressbar ui-widget ui-widget-content ui-corner-all" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                                            <div class="ui-progressbar-value ui-widget-header ui-corner-left" style="display: none; width: 0%; "></div>
                                                        </div>
                                                    </div>
                                                    <div class="file_upload_buttons"></div>
                                                </td>
                                            </tr>
                                        </table>
                                        <table width="400">
                                            <tr>
                                                <td>
                                                    <div id="msg_resultado_masivo_detalle_m" class="ui-state-error ui-corner-all paddingMsg" align="center"></div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <br/>
                                <div class="noteDiv ui-corner-all" style="width:75%">

                                    <table cellpadding="2" cellspacing="2" border="0" style="width:100%;" >
                                        <tr>
                                            <td class="">
                                                <div class="text-alert">IMPORTANTE</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <ul>
                                                    <b>Formatos Soportados</b>
                                                    <li class="text-alert">Solo soporta archivos de texto <b>( .txt )</b></li>
                                                    <li>Si el archivo esta en formato Excel ( .xls ), copielo de excel a Block de Notas y guardelo como txt. Si tiene dudas para realizar esto, porfavor solicite apoyo al Area de capacitacion.</li>
                                                </ul>
                                                <ul>
                                                    <b>Notas importantes</b>
                                                    <li class="text-alert">
                                                        El archivo txt debe contener 17 campos : <b>
                                                        Codigo Gestion , Codigo Empresa Cobranza, Cod, Gestor, Anexo, Codigo Zonal, Zonal, 
                                                        Nombre Tipo Documento, Ccl Doc, Nro Documento, Cuota, Anio, Mes, Dia, Deuda, Moneda y Descripcion </b>. En el orden indicado.
                                                    </li>
                                                    <li>
                                                        El campo <span style="font-weight:bold;">DEUDA</span> debe estar en formato numerico ( ######.## )
                                                    </li>
                                                    <li>
                                                        La primera fila del archivo debe de contener las cabeceras
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                                <!--jmore-->                                                                        
                            <div id="panelNormalizacionTelefono" style="display:none;width:100%;border:0 none;padding-top:7px;" align="center" >
                                <div style="width:50%">
                                    <div class="backPanel headerPanel ui-corner-top" style="height:20px; padding-top:5px; font-weight:bold" align="center">NORMALIZACION DE TELEFONOS</div>
                                    <div align="center" class="ui-widget-content ui-corner-bottom" style="padding:10px 30px 20px 30px">
                                        </br>
                                        <table>
                                            <tr>
                                                <td align="right">Campa&ntilde;a</td>
                                                <td align="left">
                                                    <select class="combo" id="cboCampaniaNormalizacionTelefono" onchange="listar_carteras(this.value,[{id:'cboCarteraNormalizacionTelefono'}])" >
                                                        <option value="0">--Seleccione--</option>
                                                    </select>
                                                </td>
                                                <td align="right">Cartera</td>
                                                <td align="left">
                                                    <select class="combo" id="cboCarteraNormalizacionTelefono">
                                                        <option value="0">--Seleccione--</option>
                                                    </select>
                                                </td>

                                            </tr>
                                        </table>
                                        <table>
                                            <tr>
                                                <td align="center">
                                                    
                                                            <input type="button" class="ui-state-default ui-corner-all ui-button ui-widget ui-button-text-only" id="procesarNormalizacion" value="Normalizaci&oacute;n">
                                                </td>
                                            </tr>
                                       </table>
                                        <table width="400">
                                            <tr>
                                                <td>
                                                    <div id="msg_resultado_masivo_detalle_m" class="ui-state-error ui-corner-all paddingMsg" align="center"></div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <br/>
                                
                            </div>                                
                            <div id="panelCabecerasCarteraMain" style="display:none;width:100%;border:0 none;" align="center" >
                                            <table>
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <table>
                                                                <tr>
                                                                    <td>
                                                                        <div>
                                                                            <table cellpadding="0" cellspacing="0" border="0">
                                                                                <tr>
                                                                                    <td class="ui-widget-header ui-corner-tl" align="center" style="width:30px;padding:2px 0;">&nbsp;</td>
                                                                                    <td class="ui-widget-header" align="center" style="width:250px;padding:2px 0;">Nombre</td>
                                                                                    <td class="ui-widget-header" align="center" style="width:150px;padding:2px 0;">Tipo</td>
                                                                                    <td class="ui-widget-header" align="center" style="width:50px;padding:2px 0;">&nbsp;</td>
                                                                                    <td class="ui-widget-header" align="center" style="width:50px;padding:2px 0;">&nbsp;</td>
                                                                                    <td class="ui-widget-header ui-corner-tr" align="center" style="width:20px;padding:2px 0;">&nbsp;</td>
                                                                                </tr>
                                                                            </table>
                                                                            <div style="overflow:auto;height:150px;">
                                                                                <table cellpadding="0" cellspacing="0" border="0" id="tableModuloCabeceras"></table>
                                                                            </div>
                                                                            <div style="height:20px;" class="ui-widget-header ui-corner-bottom"></div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <table>
                                                                <tr>
                                                                    <td>Nombre de Cabecera</td>
                                                                    <td><input type="text" id="txtNombreCabecera" class="cajaForm" /></td>
                                                                    <td>Longitud</td>
                                                                    <td><input type="text" id="txtLongitudCabecera" class="cajaForm" /></td>
                                                                    <td><button onclick="agregar_cabecera()" title="Agregar" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-plusthick"></span></button></td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <table>
                                                                <tr>
                                                                    <td><input type="hidden" id="hdIdCabeceras" /></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div>
                                                                            <table>
                                                                                <tr>
                                                                                    <td>
                                                                                        <select size="15" style="width:300px;" class="combo" id="cbCabeceras">
                                                                                        </select>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div>
                                                                                            <table>
                                                                                                <tr>
                                                                                                    <td><button onclick="_cobrast_arriba_item( document.getElementById('cbCabeceras') )" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-circle-triangle-n"></span></button></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td><button onclick="_cobrast_abajo_item( document.getElementById('cbCabeceras') )" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-circle-triangle-s"></span></button></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td><button onclick="$('#cbCabeceras option:selected').remove()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-trash"></span></button></td>
                                                                                                </tr>
                                                                                            </table>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div>
                                                                            <table>
                                                                                <tr>
                                                                                    <td>Nombre</td>
                                                                                    <td><input id="txtNombreGrupoCabeceras" type="text" class="cajaForm" /></td>
                                                                                    <td>Tipo</td>
                                                                                    <td>
                                                                                        <select class="combo" id="cbTipoGrupoCabeceras">
                                                                                            <option value="cartera">Cartera</option>
                                                                                            <option value="pago">Pago</option>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <button onclick="save_header()" class="ui-state-default ui-corner-all" style="padding:3px;">Guardar</button>
                                                                            <button onclick="cancel_cabeceras()" class="ui-state-default ui-corner-all" style="padding:3px;">Cancelar</button>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                            </div>

                            <div id="panelRP3" style="display:none;width:100%;border:0 none;padding-top:7px" align="center" >

                                <table>
                                    <tr>
                                        <td>
                                            <div>
                                                <table>
                                                    <tr>
                                                        <td>Fecha Inicio :</td>
                                                        <td><input type="text" readonly="readonly" class="cajaForm" id="txtFechaEnvioInicioRP3" /></td>
                                                        <td>Fecha Fin :</td>
                                                        <td><input type="text" readonly="readonly" class="cajaForm" id="txtFechaEnvioFinRP3" /></td>
                                                        <td><button id="btnBuscarFechaEnvioRP3" onclick="rp3_buscar_por_fecha_envio()">Buscar</button></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>
                                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                                    <tr valign="top" align="left" >
                                                        <td>
                                                            <div style="margin-top:10px;"></div>
                                                            <table id="table_tab_CC_RP3" style="width:100%;" cellpadding="0" cellspacing="0" border="0">
                                                                <tr>
                                                                    <td>
                                                                        <div onclick="_activeTabLayer('table_tab_CC_RP3','tabCCRP3',this,'content_table_tab_CC_RP3','layerTabCCRP3','layerTabCCRP3Comercial')" id="tabCCRP3Comercial" class="itemTabActive border-radius-left pointer ui-widget-header" style="margin:1px 1px 0 0">
                                                                            <div class="text-white">Comercial</div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div onclick="_activeTabLayer('table_tab_CC_RP3','tabCCRP3',this,'content_table_tab_CC_RP3','layerTabCCRP3','layerTabCCRP3Bancos')" id="tabCCRP3Banco" class="itemTab border-radius-left pointer ui-widget-content" style="margin:1px 1px 0 0">
                                                                            <div class="AitemTab">Bancos</div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                        <td class="ui-widget-header" style="width:5px;"></td>
                                                        <td>
                                                            <table style="width:99%;">
                                                                <tr>
                                                                    <td id="content_table_tab_CC_RP3" valign="top" align="center">
                                                                        <div id="layerTabCCRP3Comercial" class="ui-widget-content" style="display:block;width:900px;overflow:auto;" align="center">
                                                                            <table>
                                                                                <tr>
                                                                                    <td>
                                                                                        <div class="ui-widget-header ui-corner-all" style="font-size:14px;padding:3px;margin:3px;width:100px;">Cartera</div>
                                                                                        <div>
                                                                                            <table cellpadding="0" cellspacing="0" border="0">
                                                                                                <tr>
                                                                                                    <td align="center" class="ui-state-default ui-corner-tl" style="width:30px;padding:3px 0;">&nbsp;</td>
                                                                                                    <td align="center" class="ui-state-default" style="width:70px;padding:3px 0;">Secuencia</td>
                                                                                                    <td align="center" class="ui-state-default" style="width:70px;padding:3px 0;">Cantidad</td>
                                                                                                    <td align="center" class="ui-state-default" style="width:70px;padding:3px 0;">Fecha</td>
                                                                                                    <td align="center" class="ui-state-default" style="width:70px;padding:3px 0;">Hora</td>
                                                                                                    <td align="center" class="ui-state-default" style="width:20px;padding:3px 0;">&nbsp;</td>
                                                                                                    <td align="center" class="ui-state-default ui-corner-tr" style="width:18px;padding:3px 0;">&nbsp;</td>
                                                                                                </tr>
                                                                                            </table>
                                                                                            <div style="overflow:auto;height:200px;">
                                                                                                <table id="table_list_cartera_RP3_comercial" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                            </div>
                                                                                            <div class="ui-state-default ui-corner-bottom">
                                                                                                <table>
                                                                                                    <tr>
                                                                                                        <td>Buscar</td>
                                                                                                        <td><input type="text" onkeyup="search_gestion_RP3( this.value, 'table_list_cartera_RP3_comercial' )" class="cajaForm" /></td>
                                                                                                        <td>
                                                                                                            <button class="ui-corner-all ui-state-default" onclick="rp3_cargar_data_cartera( 'comercial' ) ">Crear cartera</button>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </table>
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td style="width:30px;"></td>
                                                                                    <td>
                                                                                        <div class="ui-widget-header ui-corner-all" style="font-size:14px;padding:3px;margin:3px;width:100px;">Pago</div>
                                                                                        <div>
                                                                                            <table cellpadding="0" cellspacing="0" border="0">
                                                                                                <tr>
                                                                                                    <td align="center" class="ui-state-default ui-corner-tl" style="width:30px;padding:3px 0;">&nbsp;</td>
                                                                                                    <td align="center" class="ui-state-default" style="width:70px;padding:3px 0;">Grp. Factura</td>
                                                                                                    <td align="center" class="ui-state-default" style="width:70px;padding:3px 0;">Cantidad</td>
                                                                                                    <td align="center" class="ui-state-default" style="width:70px;padding:3px 0;">Fecha</td>
                                                                                                    <td align="center" class="ui-state-default" style="width:70px;padding:3px 0;">Hora</td>
                                                                                                    <td align="center" class="ui-state-default" style="width:20px;padding:3px 0;">&nbsp;</td>
                                                                                                    <td align="center" class="ui-state-default ui-corner-tr" style="width:18px;padding:3px 0;">&nbsp;</td>
                                                                                                </tr>
                                                                                            </table>
                                                                                            <div style="overflow:auto;height:200px;">
                                                                                                <table id="table_list_pagos_RP3_comercial" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                            </div>
                                                                                            <div class="ui-state-default ui-corner-bottom">
                                                                                                <table>
                                                                                                    <tr>
                                                                                                        <td>Buscar</td>
                                                                                                        <td><input type="text" onkeyup="search_gestion_RP3( this.value, 'table_list_pagos_RP3_comercial' )" class="cajaForm" /></td>
                                                                                                        <td><button onclick="rp3_cargar_data_pagos( 'comercial' )" class="ui-corner-all ui-state-default">Crear Pagos</button></td>
                                                                                                    </tr>
                                                                                                </table>
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                    <tr>
                                                                                        <td colspan="3">
                                                                                                <div class="ui-widget-header ui-corner-all" style="font-size:14px;padding:3px;margin:3px;width:100px;">Respuesta</div>
                                                                                                <div>
                                                                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                                                                                <tr>
                                                                                                                        <td align="center" class="ui-state-default ui-corner-tl" style="width:30px;padding:3px 0;">&nbsp;</td>
                                                                                                                        <td align="center" class="ui-state-default" style="width:240px;padding:3px 0;">Campania</td>
                                                                                                                        <td align="center" class="ui-state-default" style="width:240px;padding:3px 0;">Cartera</td>
                                                                                                                        <td align="center" class="ui-state-default" style="width:80px;padding:3px 0;">Fecha Inicio</td>
                                                                                                                        <td align="center" class="ui-state-default" style="width:80px;padding:3px 0;">Fecha Fin</td>
                                                                                                                        <td align="center" class="ui-state-default" style="width:20px;padding:3px 0;">&nbsp;</td>
                                                                                                                        <td align="center" class="ui-state-default ui-corner-tr" style="width:18px;padding:3px 0;">&nbsp;</td>
                                                                                                                </tr>
                                                                                                        </table>
                                                                                                        <div style="overflow:auto;height:200px;width:722px;">
                                                                                                                <table id="table_list_carteras_comercial" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                                        </div>
                                                                                                        <div class="ui-state-default ui-corner-bottom" style="width:721px;">
                                                                                                                <table>
                                                                                                                        <tr>
                                                                                                                                <td>Buscar</td>
                                                                                                                                <td><input type="text" onkeyup="search_gestion_RP3( this.value, 'table_list_carteras_comercial' )" class="cajaForm" /></td>
                                                                                                                                <td>Inicio</td>
                                                                                                                                <td><input style="width:70px;" readonly="readonly" class="cajaForm" type="text" id="txtFechaInicioRespuestaComercial" /></td>
                                                                                                                                <td>Fin</td>
                                                                                                                                <td><input style="width:70px;" readonly="readonly" class="cajaForm" type="text" id="txtFechaFinRespuestaComercial" /></td>
                                                                                                                                <td><button onclick="exportar_formato_respuesta_rp3( $('#table_list_carteras_comercial').find(':checked').map( function ( ) { return $(this).val(); } ).get().join(','), $('#txtFechaInicioRespuestaComercial').val(), $('#txtFechaFinRespuestaComercial').val() )" class="ui-corner-all ui-state-default">Exportar</button></td>
                                                                                                                                <td><button onclick="rp3_enviar_respuesta( 'comercial', $('#table_list_carteras_comercial').find(':checked').map( function ( ) { return $(this).val(); } ).get().join(','), $('#txtFechaInicioRespuestaComercial').val(), $('#txtFechaFinRespuestaComercial').val() )" class="ui-corner-all ui-state-default">Enviar Respuesta</button></td>
                                                                                                                        </tr>
                                                                                                                </table>
                                                                                                        </div>
                                                                                                </div>
                                                                                        </td>
                                                                                </tr>
                                                                            </table>
                                                                        </div>
                                                                        <div id="layerTabCCRP3Bancos" class="ui-widget-content" style="display:none;width:900px;overflow:auto;" align="center">
                                                                            <table>
                                                                                <tr>
                                                                                    <td>
                                                                                        <div class="ui-widget-header ui-corner-all" style="font-size:14px;padding:3px;margin:3px;width:100px;">Cartera</div>
                                                                                        <div>
                                                                                            <table cellpadding="0" cellspacing="0" border="0">
                                                                                                <tr>
                                                                                                    <td align="center" class="ui-state-default ui-corner-tl" style="width:30px;padding:3px 0;">&nbsp;</td>
                                                                                                    <td align="center" class="ui-state-default" style="width:70px;padding:3px 0;">Secuencia</td>
                                                                                                    <td align="center" class="ui-state-default" style="width:70px;padding:3px 0;">Cantidad</td>
                                                                                                    <td align="center" class="ui-state-default" style="width:70px;padding:3px 0;">Fecha</td>
                                                                                                    <td align="center" class="ui-state-default" style="width:70px;padding:3px 0;">Hora</td>
                                                                                                    <td align="center" class="ui-state-default" style="width:20px;padding:3px 0;">&nbsp;</td>
                                                                                                    <td align="center" class="ui-state-default ui-corner-tr" style="width:18px;padding:3px 0;">&nbsp;</td>
                                                                                                </tr>
                                                                                            </table>
                                                                                            <div style="overflow:auto;height:200px;">
                                                                                                <table id="table_list_cartera_RP3_banco" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                            </div>
                                                                                            <div class="ui-state-default ui-corner-bottom">
                                                                                                <table>
                                                                                                    <tr>
                                                                                                        <td>Buscar</td>
                                                                                                        <td><input type="text" onkeyup="search_gestion_RP3( this.value )" class="cajaForm" /></td>
                                                                                                        <td>
                                                                                                            <button class="ui-corner-all ui-state-default" onclick="rp3_cargar_data_cartera( 'banco' )">Crear cartera</button>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </table>
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td style="width:30px;"></td>
                                                                                    <td>
                                                                                        <div class="ui-widget-header ui-corner-all" style="font-size:14px;padding:3px;margin:3px;width:100px;">Pago</div>
                                                                                        <div>
                                                                                            <table cellpadding="0" cellspacing="0" border="0">
                                                                                                <tr>
                                                                                                    <td align="center" class="ui-state-default ui-corner-tl" style="width:30px;padding:3px 0;">&nbsp;</td>
                                                                                                    <td align="center" class="ui-state-default" style="width:70px;padding:3px 0;">Grp. Factura</td>
                                                                                                    <td align="center" class="ui-state-default" style="width:70px;padding:3px 0;">Cantidad</td>
                                                                                                    <td align="center" class="ui-state-default" style="width:70px;padding:3px 0;">Fecha</td>
                                                                                                    <td align="center" class="ui-state-default" style="width:70px;padding:3px 0;">Hora</td>
                                                                                                    <td align="center" class="ui-state-default" style="width:20px;padding:3px 0;">&nbsp;</td>
                                                                                                    <td align="center" class="ui-state-default ui-corner-tr" style="width:18px;padding:3px 0;">&nbsp;</td>
                                                                                                </tr>
                                                                                            </table>
                                                                                            <div style="overflow:auto;height:200px;">
                                                                                                <table id="table_list_pagos_RP3_banco" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                            </div>
                                                                                            <div class="ui-state-default ui-corner-bottom">
                                                                                                <table>
                                                                                                    <tr>
                                                                                                        <td>Buscar</td>
                                                                                                        <td><input type="text" onkeyup="search_gestion_RP3( this.value, 'table_list_pagos_RP3_banco' )" class="cajaForm" /></td>
                                                                                                        <td><button onclick="rp3_cargar_data_pagos( 'banco' )" class="ui-corner-all ui-state-default">Crear Pagos</button></td>
                                                                                                    </tr>
                                                                                                </table>
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="3">
                                                                                        <div class="ui-widget-header ui-corner-all" style="font-size:14px;padding:3px;margin:3px;width:100px;">Respuesta</div>
                                                                                        <div>
                                                                                            <table cellpadding="0" cellspacing="0" border="0">
                                                                                                    <tr>
                                                                                                            <td align="center" class="ui-state-default ui-corner-tl" style="width:30px;padding:3px 0;">&nbsp;</td>
                                                                                                            <td align="center" class="ui-state-default" style="width:240px;padding:3px 0;">Campania</td>
                                                                                                            <td align="center" class="ui-state-default" style="width:240px;padding:3px 0;">Cartera</td>
                                                                                                            <td align="center" class="ui-state-default" style="width:80px;padding:3px 0;">Fecha Inicio</td>
                                                                                                            <td align="center" class="ui-state-default" style="width:80px;padding:3px 0;">Fecha Fin</td>
                                                                                                            <td align="center" class="ui-state-default" style="width:20px;padding:3px 0;">&nbsp;</td>
                                                                                                            <td align="center" class="ui-state-default ui-corner-tr" style="width:18px;padding:3px 0;">&nbsp;</td>
                                                                                                    </tr>
                                                                                            </table>
                                                                                            <div style="overflow:auto;height:200px;width:722px;">
                                                                                                    <table id="table_list_carteras_banco" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                            </div>
                                                                                            <div class="ui-state-default ui-corner-bottom" style="width:721px;">
                                                                                                <table>
                                                                                                        <tr>
                                                                                                                <td>Buscar</td>
                                                                                                                <td><input type="text" onkeyup="search_gestion_RP3( this.value, 'table_list_carteras_banco' )" class="cajaForm" /></td>
                                                                                                                <td>Inicio</td>
                                                                                                                <td><input style="width:70px;" readonly="readonly" class="cajaForm" type="text" id="txtFechaInicioRespuestaBanco" /></td>
                                                                                                                <td>Fin</td>
                                                                                                                <td><input style="width:70px;" readonly="readonly" class="cajaForm" type="text" id="txtFechaFinRespuestaBanco" /></td>
                                                                                                                <td><button onclick="exportar_formato_respuesta_rp3( $('#table_list_carteras_banco').find(':checked').map( function ( ) { return $(this).val(); } ).get().join(','), $('#txtFechaInicioRespuestaBanco').val(), $('#txtFechaFinRespuestaBanco').val() )" class="ui-corner-all ui-state-default">Exportar</button></td>
                                                                                                                <td><button onclick="rp3_enviar_respuesta( 'banco', $('#table_list_carteras_banco').find(':checked').map( function ( ) { return $(this).val(); } ).get().join(','), $('#txtFechaInicioRespuestaBanco').val(), $('#txtFechaFinRespuestaBanco').val() )" class="ui-corner-all ui-state-default">Enviar Respuesta</button></td>
                                                                                                        </tr>
                                                                                                </table>
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div id="panelEditHeader" style="display:none;width:100%;border:0 none;padding-top:7px" align="center" >
                                <div class="ui-widget-content ui-corner-all" style="padding:3px;margin:5px 20px;">
                                    <table>
                                        <tr>
                                            <td>Campa&ntilde;a</td>
                                            <td><select class="combo" id="cboCampaniaEditHeader" onchange="listar_carteras( this.value, [{id:'cboCarteraEditHeader'}] )" ><option value="0">--Seleccione--</option></select></td>
                                            <td>Cartera</td>
                                            <td><select class="combo" id="cboCarteraEditHeader" onchange="edit_header_load_data( this.value )"><option value="0">--Seleccione--</option></select></td>
                                        </tr>
                                    </table>
                                </div>
                                <div>
                                    <table>
                                        <tr>
                                            <td valign="top">
                                                <table id="table_tab_EHCartera" style="width:100%;" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td>
                                                            <div id="tabEHCarteraCuenta" onclick="_activeTabLayer('table_tab_EHCartera','tabEHCartera',this,'content_table_tab_EH_Cartera','layerTabEHCartera','layerTabEHCarteraCuenta')" class="itemTabActive border-radius-left pointer ui-widget-header" style="margin:1px 1px 0 0">
                                                                <div class="text-white">Cuenta</div>
                                                            </div>
                                                        </td>
                                                        </tr>
                                                        <tr>
                                                        <td>
                                                            <div id="tabEHCarteraDetalle" onclick="_activeTabLayer('table_tab_EHCartera','tabEHCartera',this,'content_table_tab_EH_Cartera','layerTabEHCartera','layerTabEHCarteraDetalle')" class="itemTab border-radius-left pointer ui-widget-content" style="margin:1px 1px 0 0">
                                                                <div class="AitemTab">Detalle</div>
                                                            </div>
                                                        </td>
                                                        </tr>
                                                        <tr>
                                                        <td>
                                                            <div id="tabEHCarteraAdicionales" onclick="_activeTabLayer('table_tab_EHCartera','tabEHCartera',this,'content_table_tab_EH_Cartera','layerTabEHCartera','layerTabEHCarteraAdicionales')" class="itemTab border-radius-left pointer ui-widget-content" style="margin:1px 1px 0 0">
                                                                <div class="AitemTab">Adicionales</div>
                                                            </div>
                                                        </td>
                                                        </tr>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td class="ui-widget-header" style="width:5px;"></td>
                                            <td id="content_table_tab_EH_Cartera" style="overflow:auto;" valign="top">
                                                <div id="layerTabEHCarteraCuenta" style="display:block;width:900px;overflow:auto;" class="ui-widget-content" align="center">
                                                    
                                                </div>
                                                <div id="layerTabEHCarteraDetalle" style="display:none;width:900px;overflow:auto;" class="ui-widget-content" align="center">
                                                    
                                                </div>
                                                <div id="layerTabEHCarteraAdicionales" style="display:none;width:900px;overflow:auto;" class="ui-widget-content" align="center">
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                                        
                            <div id="panelCruceTelefono" style="display:none;width:100%;border:0 none;padding-top:7px" align="center">
                                
                                <div style="width:880px;height:450px;padding:5px;" class="ui-widget-content ui-corner-all">
                                    
                                    <table>
                                        <tr>
                                            <td colspan="4" class="ui-state-highlight" align="center" style="padding:3px 0px;font-weight:bold;">CRUCE TELEFONOS</td>
                                        </tr>
                                        <tr>
                                            <td class="ui-state-default" style="height:30px;padding:0px 3px;">Campa&ntilde;a</td>
                                            <td class="ui-widget-content" style="height:30px;padding:0px 3px;">
                                                <select class="combo" style="width:150px;" id="cboCampaniaCruceTelefono" onchange="listar_carteras( this.value, [{id:'cboCarteraCruceTelefono'}] )">
                                                    <option value="0">--Seleccione--</option>
                                                </select>
                                            </td>
                                            <td class="ui-state-default" style="height:30px;padding:0px 3px;">Cartera</td>
                                            <td class="ui-widget-content" style="height:30px;padding:0px 3px;">
                                                <select class="combo" style="width:150px;" id="cboCarteraCruceTelefono">
                                                    <option value="0">--Seleccione--</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ui-state-default" style="height:30px;padding:0px 3px;">Cartera Filtro</td>
                                            <td colspan="3" class="ui-widget-content" style="height:30px;padding:3px;">
                                                <div>
                                                    <div class="ui-state-default ui-corner-top" style="padding: 0px 5px;">Carteras</div>
                                                    <div style="overflow:auto;height:150px;width:390px;">
                                                        <table id="tbCarterasCargaCruceTelefono" cellspacing="0" cellpadding="0" border="0" ></table>
                                                    </div>
                                                    <div class="ui-state-default ui-corner-bottom">
                                                        <table>
                                                            <tr>
                                                                <td>Buscar:</td>
                                                                <td><input onkeyup="search_text_table(this.value,'tbCarterasCargaCruceTelefono')" type="text" class="cajaForm" style="width:150px;" /></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ui-state-default" style="height:30px;padding:0px 3px;">Mejor Llamada</td>
                                            <td class="ui-widget-content" style="height:30px;padding:0px 3px;"><input type="radio" value="mejor" checked="checked" name="rdCruceLlamadaMeLLUlLL" /></td>
                                            <td class="ui-state-default" style="height:30px;padding:0px 3px;">Ultima Llamada</td>
                                            <td class="ui-widget-content" style="height:30px;padding:0px 3px;"><input type="radio" value="ultima" name="rdCruceLlamadaMeLLUlLL" /></td>
                                        </tr>
                                        <tr>
                                            <td class="ui-state-default" style="height:30px;padding:0px 3px;">Fecha Inicio</td>
                                            <td class="ui-widget-content" style="height:30px;padding:0px 3px;"><input id="txtCruceTelefonoFechaInicio" type="text" readonly="readonly" class="cajaForm" style="width:70px;" /></td>
                                            <td class="ui-state-default" style="height:30px;padding:0px 3px;">Fecha Fin</td>
                                            <td class="ui-widget-content" style="height:30px;padding:0px 3px;"><input id="txtCruceTelefonoFechaFin" type="text" readonly="readonly" class="cajaForm" style="width:70px;" /></td>
                                        </tr>
                                        <tr>
                                            <td class="ui-state-highlight" colspan="4" align="center" style="padding:3px 0px;">
                                                <button onclick="iniciar_cruce_telefono()" class="ui-state-default ui-corner-all" style="padding:3px;">Aceptar</button>
                                            </td>
                                        </tr>
                                    </table>
                                    <br/>
                                    <div class="noteDiv ui-corner-all" style="width:75%">

                                        <table cellpadding="2" cellspacing="2" border="0" style="width:100%;" >
                                            <tr>
                                                <td class="">
                                                    <div class="text-alert">IMPORTANTE</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <ul>
                                                        <b>Notas importantes</b>
                                                        <li class="text-alert">
                                                            Los campos Fecha Inicio y Fecha Fin hacen referencia a fecha de llamada.
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                </div>
                                
                            </div>
                            <div id="panelCargaCuota" style="display:none;width:100%;border:0 none;padding-top:7px" align="center">                            
                                <div style="width:880px;height:450px;padding:5px;" class="ui-widget-content ui-corner-all">
                                    <table>
                                        <tr>
                                            <td colspan="4" class="ui-state-highlight" align="center" style="padding:3px 0px;font-weight:bold;">CARGA DE CUOTAS</td>
                                        </tr>
                                        <tr>
                                            <td class="ui-state-default" style="height:30px;padding:0px 3px;">Campa&ntilde;a</td>
                                            <td class="ui-widget-content" style="height:30px;padding:0px 3px;">
                                                <select class="combo" style="width:200px;" id="cboCampaniaCargaCuota" onchange="listar_carteras( this.value, [{id:'cboCarteraCargaCuota'}] )">
                                                    <option value="0">--Seleccione--</option>
                                                </select>
                                            </td>
                                            <td class="ui-state-default" style="height:30px;padding:0px 3px;">Cartera</td>
                                            <td class="ui-widget-content" style="height:30px;padding:0px 3px;">
                                                <select class="combo" style="width:200px;" id="cboCarteraCargaCuota">
                                                    <option value="0">--Seleccione--</option>
                                                </select>
                                            </td>
                                        </tr>   
                                        <tr>
                                            <td>
                                                <input type="file" id="uploadFileCargaCuota" name="uploadFileCargaCuota" >
                                            </td>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                            <td>
                                                <button onclick="file_cargar_cuota()" class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all" >
                                                    <span class="ui-button-text">Subir Archivo</span>
                                                </button>
                                            </td>
                                            <td><div id="msgCargaCuota"></div></td>
                                        </tr>
                                        <!-- <tr id="showbtncargarcuota" style="display:none;" align="center">
                                            <td colspan="4" align="center">
                                                <button onclick="cargar_cuota()" class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all" >
                                                    <span class="ui-button-text">Realizar Carga Cuota</span>
                                                </button>
                                            </td>  
                                            <td><div id="msgCargaCuota"></div></td>
                                        </tr> -->
                                    </table>
                                </div>
                            </div>

                            <div id="panelFiadores" style="display:none;width:100%;border:0 none;padding-top:7px" align="center">                            
                                <div style="display:table;padding:15px 15px 15px 15px;" class="ui-widget-content ui-corner-all">
                                    <div style="display:table;float:left;padding:3px 3px 3px 3px"><b>CAMPA&Ntilde;A</b></div>
                                    <div style="display:table;float:left">
                                        <select class="combo" style="width:200px;" id="cboCampaniaFiadores" onchange="listar_carteras( this.value, [{id:'cboCarteraFiadores'}] )">
                                            <option value="0">--Seleccione--</option>
                                        </select>
                                    </div>
                                    <div style="display:table;float:left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>

                                    <div style="display:table;float:left;padding:3px 3px 3px 3px"><b>CARTERA</b></div>
                                    <div style="display:table;float:left">
                                        <select class="combo" style="width:200px;" id="cboCarteraFiadores">
                                            <option value="0">--Seleccione--</option>
                                        </select>
                                    </div>
                                    <div style="clear:both;">&nbsp;</div>

                                    <div style="display:table;float:left;padding:3px 3px 3px 3px"><b>ARCHIVO</b></div>
                                    <div style="display:table;float:left">
                                        <input type="file" id="uploadFileFiadores" name="uploadFileFiadores" >
                                    </div>
                                    <div style="clear:both;">&nbsp;</div>
                                    <div style="clear:both;">&nbsp;</div>
                                    <div style="clear:both;">&nbsp;</div>
                                    
                                    <div style="display:table;float:left">
                                        <button onclick="file_fiadores_txt()" class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all" >
                                            <span class="ui-button-text">SUBIR ARCHIVO</span>
                                        </button>
                                    </div>
                                    <div style="display:table;float:left;margin-left:100px" class='msgFiadores'></div>
                                    <div style="clear:both;">&nbsp;</div>
                                </div>
                            </div>

                            <div id="panelClienteContrato" style="display:none;width:100%;border:0 none;padding-top:7px" align="center">                            
                                <div style="display:table;padding:5px;" class="ui-widget-content ui-corner-all">
                                    <div style="display:table"><b>CLIENTES</b></div>
                                    <div style="display:table;float:left">
                                        <input type="file" id="uploadFileCargaClienteNew" name="uploadFileCargaClienteNew" >
                                    </div>
                                    <div style="display:table;float:left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                    <div style="display:table;float:left" class='msgJoinCliente text-alert'></div>
                                    <div style="clear:both;">&nbsp;</div>

                                    <div style="display:table"><b>CONTRATOS</b></div>
                                    <div style="display:table;float:left">
                                        <input type="file" id="uploadFileCargaContratoNew" name="uploadFileCargaContratoNew" >
                                    </div>
                                    <div style="display:table;float:left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                    <div style="display:table;float:left" class='msgJoinContrato text-alert'></div>
                                    <div style="clear:both;">&nbsp;</div>

                                    <div style="display:table;">
                                        <button onclick="file_cliente_contrato_new()" class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all" >
                                            <span class="ui-button-text">SUBIR ARCHIVOS</span>
                                        </button>
                                    </div>
                                    <div style="clear:both;">&nbsp;</div>

                                    <div style="display:table;">
                                        <input type="hidden" name="txtJoinClienteRspta" id="txtJoinClienteRspta" />
                                        <input type="hidden" name="txtJoinContratoRspta" id="txtJoinContratoRspta" />
                                        <input type="hidden" name="txtJoinClienteTime" id="txtJoinClienteTime" />
                                        <input type="hidden" name="txtJoinContratoTime" id="txtJoinContratoTime" />
                                    </div>
                                    <div style="clear:both;">&nbsp;</div>
                                    <div style="clear:both;">&nbsp;</div>

                                    <div style="display:none;float:left" class="divExportarCarteraJoin">
                                        <div style="display:table;">
                                            <button onclick="btn_generar_join_carteras()" class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all" >
                                                <span class="ui-button-text">GENERAR ARCHIVOS</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div style="float:left;margin-left:200px">&nbsp;</div>
                                    <div style="display:none;float:left" class="divTxtDescarga text-alert"></div>

                                </div>
                                <div style="clear:both;">&nbsp;</div>
                            </div>

                                        <!-- Vic I -->
                            <div id="panelInsertarLlamadas" style="display:none;width:100%;border:0 none;padding-top:7px" align="center">
                                <table>
                                        <tr><td><table><tr>
                                            <th>Subir Archivo:</th>
                                            <td><input type="file" id="uploadFileInsertarLlamada" name="uploadFileInsertarLlamada" ></td>
                                            <td>
                                                <button onclick="file_cargar_llamadas()" class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all" >
                                                    <span class="ui-button-text">Cargar Llamadas</span>
                                                </button>
                                            </td>
                                            <th>Tipo de Llamada</th>
                                            <td>
                                                <select id="slcttipollamada">
                                                    <option value="S">FICTICIO</option>
                                                    <option value="SA">REAL</option>
                                                </select>
                                            </td>
                                            <td><div class="msgCargarLlamada"></div></td>
                                        </tr></table></td></tr>
                                        <tr><td>&nbsp;</td></tr>
                                        <tr><td><table>
                                            <tr class="divCruceLlamadas" style="display:none;">
                                                <td><div class="sltCarterasLlam"></div></td>
                                                <!--    <td><div class="sltTipificacionLlam"></div></td>    -->
                                                <td>
                                                    <button onclick="cruce_llamadas()" class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all" >
                                                        <span class="ui-button-text">Realizar Cruce de Llamadas</span>
                                                    </button>
                                                </td>
                                            </tr>
                                        </table></td></tr>
                                        <tr><td><table>
                                            <tr class="divMsgCruceLlamadas" style="display:none;">
                                                <td><div class="msgCruceLlamada"></div></td>
                                            </tr>
                                            <tr class="divBotonInsertar" style="display:none;">
                                                <td><button onclick="insertarLlamadasManual()" class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all" >
                                                        <span class="ui-button-text">Ingresar Llamadas</span>
                                                    </button></td>
                                                <td><div class="msgInsertarLlamadasManuales"></div></td>
                                            </tr>
                                            <tr class="divBotonAgregarFono" style="display:none;">
                                                <td><button onclick="agregarTelefonoManual()" class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all" >
                                                        <span class="ui-button-text">Agregar Telefonos</span>
                                                    </button></td>
                                                <td><input type="hidden" name="nroAgregarFonoGestion" id="nroAgregarFonoGestion" /></td>
                                                <td><div class="msgInsertarNewTelefonos"></div></td>
                                            </tr>
                                            </table></td></tr>
                                        <tr><td>&nbsp;</td></tr>
                                </table>
                                <table style="width:100%;">
                                    <tr>
                                        <td class="noteDiv">
                                            <table cellpadding="2" cellspacing="2" border="0" style="width:100%;" >
                                            <tr>
                                                <td class="">
                                                    <div class="note">Nota:</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <ul>
                                                        <b>Formatos Soportados</b>
                                                        <p></p>
                                                        <li>
                                                            Solo soporta archivos de texto ( TXT )
                                                        </li>
                                                        <li>
                                                            <a href="../formato_insertar_llamadas.xlsx">Descargar Formato de Archivo</a>
                                                        </li>
                                                        <li>
                                                            <a href="../rpt/excel/ListadoEstadosPorServicio.php?Servicio=<?= $_SESSION['cobrast']['idservicio'] ?>" & >Descargar Paleta de Estado</a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <ul>
                                                        <b>Notas importantes</b>
                                                        <p></p>
                                                        <li>
                                                            La primera fila del archivo debe de contener las cabeceras
                                                        </li>
                                                        <li>
                                                            Los campos deben estar separados por tabulaciones
                                                        </li>
                                                        <li>
                                                            Asegurarse que el tamaño del archivo no supere los 20M
                                                        </li>
                                                        <li style="font-weight:bold;">
                                                            Los valores de fecha debe estar en el formato yyyy-mm-dd Las fechas que tengan otros formatos serán ignoradas.
                                                        </li>
                                                        <li style="font-weight:bold;">
                                                            Los campos numericos no deberan tener comas
                                                        </li>
                                                        <li style="font-weight:bold;">
                                                            La estructura de archivo es: Fecha_Llamada, Hora_Llamada, Codigo_Cliente, Nro_Contrato, Observacion, Telefono, Fecha_CP, Monto_CP, Nombre_Contacto
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            </table>
                                            <br />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div id="panelCargaProvisionTotal" style="display:none;width:100%;border:0 none;" align="center"><!-- Piro -->
                                <div class="backPanel headerPanel ui-corner-top" style="width:70%;height:20px; padding-top:5px; font-weight:bold" align="center">CARGA DE PROVISION TOTAL</div>
                                <div align="center" class="ui-widget-content ui-corner-bottom ui-corner-top " style="padding:10px 30px 20px 30px"> 
                                    <div style="display:inline-block"> 
                                       <input type="text" id="txtFechaProvisionTotal" placeholder="Fecha de la provisi&oacute;n a cargar" style="width: 150px; padding: 4px; margin-left: 11px; margin-right: 4px;">
                                       <span>Elegir archivo : <input type="file" name="uploadFileProvisionTotal" id="uploadFileProvisionTotal"></span><button style="margin-left:20px" id="btnCargarProvisionTotal" onClick="cargarProvisionTotal();" class="ui-state-default ui-corner-all ui-button ui-widget ui-button-text-only" aria-disabled="false" role="button" ><span style="padding : 3px;text-align:center" class="ui-button-text">Cargar Provisi&oacute;n Total</span></button>
                                    </div>
                                    <button onClick="window.location.href='../rpt/cabProTot.php'" title="Descargar Cabeceras" aria-disabled="false" role="button"  type="button" style="height: 33px;vertical-align: bottom;display:inline-block;margin-left:8px " class="ui-state-default ui-corner-all ui-button ui-widget ui-button-text-only" id="btnDescargarCabecera">
                                        <span class="ui-icon ui-icon-arrowthickstop-1-s" ></span>
                                    </button>
                                    <div id="esperando" style="opacity:0;transition:opacity 0.5s ease-in 0.1s;width: 50px;display: inline-block;vertical-align: middle;"><span class="fa fa-spinner fa-spin fa-2x"></span></div>
                                    <div style="margin-top:7px"> <!-- TABLA CARTERAS-->
                                        <div class="ui-widget-header ui-corner-top" style="width:240px" >Carteras<input type="checkbox" id="chktotalCargaProvisionTotal" onclick="checked_all(this.checked,'tbCarterasCargaProvisionTotal')"></div>
                                        <div style="overflow:auto;height:200px;width:240px;" >
                                            <table cellpadding="0" cellspacing="0" border="0" id="tbCarterasCargaProvisionTotal" ></table>
                                        </div>
                                        <div class="ui-widget-header ui-corner-bottom" style="width:240px" >
                                            <table>
                                                <tr>
                                                    <td>Buscar:</td>
                                                    <td><input type="text" onkeyup="search_text_table(this.value,'tbCarterasCargaProvisionTotal')" class="cajaForm" /></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>


                                    <table style="width:100%;margin-top: 18px;" border="0" cellpadding="2" cellspacing="2">
                                                                                <tbody><tr>
                                                                                    <td class="">
                                                                                        <div class="note">Nota:</div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <ul>
                                                                                            <b>Formatos Soportados</b>
                                                                                            <p></p>
                                                                                            <li>
                                                                                        Solo soporta archivos de texto ( TXT )
                                                                                            </li>
                                                                                        </ul>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <ul>
                                                                                            <b>Notas importantes</b>
                                                                                            <p></p>
                                                                                            <li>
                                                                                        La primera fila del archivo debe de contener las cabeceras
                                                                                            </li>
                                                                                            <li>
                                                                                        Los campos deben estar separados por tabulaciones
                                                                                            </li>
                                                                                            
                                                                                            <li style="font-weight:bold;">
                                                                                        Los valores de fecha debe estar en el formato yyyy/mm/dd o yyyy-mm-dd . Las fechas que tengan otros formatos serán ignoradas.
                                                                                            </li>
                                                                                            <li style="font-weight:bold;">
                                                                                        Los campos numericos no deben estar separados por comas
                                                                                            </li>
                                                                                           
                                                                                        </ul>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody></table>
                                </div>
                            </div>
                            <div id="panelMontoPagado" style="display:none;width:100%;border:0 none;" align="center">
                                <div class="ui-widget-content ui-corner-bottom ui-corner-top" style=";width: 819px; margin: 0px auto;" >
                                    <div class=" border-radius-top pointer ui-widget-header title_prep_car_covinoc"> ACTUALIZAR MONTO PAGADO  </div>
                                    

                                    <div style="margin-top:7px;display:inline-block"> <!-- TABLA CARTERAS-->
                                        <div class="ui-widget-header ui-corner-top" style="width:240px" >Carteras<input  style="vertical-align: bottom; margin-left: 90% ! important;" type="checkbox" id="chktotalCargaUpdateMontoPagado" onclick="checked_all(this.checked,'tbCarterasCargaUpdateMontoPagado')"></div>
                                        <div style="overflow:auto;height:100%;width:240px;" >
                                            <table cellpadding="0" cellspacing="0" border="0" id="tbCarterasCargaUpdateMontoPagado"style="width:100%" ></table>
                                        </div>
                                        <div class="ui-widget-header ui-corner-bottom" style="width:240px" >
                                            <table>
                                                <tr>
                                                    <td>Buscar:</td>
                                                    <td><input type="text" onkeyup="search_text_table(this.value,'tbCarterasCargaUpdateMontoPagado')" class="cajaForm" /></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div style="margin-top:7px;display:inline-block;vertical-align:top" > <!-- TABLA CARTERAS-->
                                        <div style="overflow:auto;height:100%;width:240px;" >
                                            <input type="button" value="ACTUALIZAR MONTOS PAGADOS" id="btnUpdateMontoPagado" onClick="updateMontosPagado();">
                                            <div id="msgUpdateMontoPagado" class="ui-widget-header ui-corner-bottom" style="margin-top:8px;width:223px;padding:2px;opacity:0;transition:opacity 0.3s ease"><span></span></div>
                                        </div>
                                    </div>
                                    <div class="ui-state-default" style="padding-bottom:8px;padding-top:8px;">
                                        <div style="text-align:left">
                                            <span style="font-weight: bold;font-family: Robotto;font-size:13px;color: gray;margin-left: 21px;"><i class="fa fa-exclamation-circle"></i> Importante </span>
                                        </div>
                                        <div style="text-align:left">
                                            <span style="font-weight: 200;font-family: Robotto;font-size:13px;color: gray;margin-left: 25px;"><i class="fa fa-circle-o"></i> Seleccionar la(s) cartera(s) que desee actualizar el monto pagado. </span>
                                        </div>
                                        <div style="text-align:left">
                                            <span style="font-weight: 200;font-family: Robotto;font-size:13px;color: gray;margin-left: 25px;"><i class="fa fa-circle-o"></i> Realizar este procedimiento cada vez que se carge o actualize un pago. </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="panelNormalizarTelefono2" style="display:none;width:100%;border:0 none;" align="center">
                                <div class="ui-widget-content ui-corner-bottom ui-corner-top" style=";width: 819px; margin: 0px auto;" >
                                    <div class=" border-radius-top pointer ui-widget-header title_prep_car_covinoc"> NORMALIZAR TELEFONO ( COVINOC - SAGA )  </div>
                                    

                                    <div style="margin-top:7px;display:inline-block"> <!-- TABLA CARTERAS-->
                                        <div class="ui-widget-header ui-corner-top" style="width:240px" >Carteras<input  style="vertical-align: bottom; margin-left: 90% ! important;" type="checkbox" id="chktotalCargaNormalizarTelefono2" onclick="checked_all(this.checked,'tbCarterasCargaNormalizarTelefono2')"></div>
                                        <div style="overflow:auto;height:100%;width:240px;" >
                                            <table cellpadding="0" cellspacing="0" border="0" id="tbCarterasCargaNormalizarTelefono2"style="width:100%" ></table>
                                        </div>
                                        <div class="ui-widget-header ui-corner-bottom" style="width:240px" >
                                            <table>
                                                <tr>
                                                    <td>Buscar:</td>
                                                    <td><input type="text" onkeyup="search_text_table(this.value,'tbCarterasCargaNormalizarTelefono2')" class="cajaForm" /></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div style="margin-top:7px;display:inline-block;vertical-align:top" > <!-- TABLA CARTERAS-->
                                        <div style="overflow:auto;height:100%;width:240px;" >
                                            <input type="button" value="Normalizar Teléfonos" id="btnNormalizarTelefono2" onClick=" NormalizarTelefono2();">
                                            <div id="msgNormalizarTelefono2" class="ui-widget-header ui-corner-bottom" style="margin-top:8px;width:223px;padding:2px;opacity:0;transition:opacity 0.3s ease"><span></span></div>
                                        </div>
                                    </div>
                                    <div class="ui-state-default" style="padding-bottom:8px;padding-top:8px;">
                                        <div style="text-align:left">
                                            <span style="font-weight: bold;font-family: Robotto;font-size:13px;color: gray;margin-left: 21px;"><i class="fa fa-exclamation-circle"></i> Importante </span>
                                        </div>
                                        <div style="text-align:left">
                                            <span style="font-weight: 200;font-family: Robotto;font-size:13px;color: gray;margin-left: 25px;"><i class="fa fa-circle-o"></i> Seleccionar la(s) cartera(s) que desee normalizar el telefono. </span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>

                            <div id="panelLoadCallCenter" style="display:none;width:100%;border:0 none;" align="center">
                                <table>
                                    <tr>
                                        <td>
                                            <div>
                                                <div class="ui-state-highlight ui-corner-top" style="font-weight:bold;padding:3px 0;" align="center">CARGA DE LLAMADAS</div>
                                            </div>
                                            <input type="hidden" id="tmparchivoCargaLlamada" value=""/>
                                            <div>
                                                <table>
                                                    <tr>
                                                        <td style="font-weight:bold">
                                                            <input type="file" id="btnCargaLlamadas" name="btnCargaLlamadas"/>
                                                        </td>
                                                        <td>
                                                            Cartera:
                                                            <select id="xcarterames">
                                                                
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <button id="btnSubirCargaLlamada" onclick="generaSubirCargaLlamada()" class="ui-state-default ui-corner-all ui-button ui-widget ui-button-text-only" role="button" aria-disabled="false">
                                                                <span class="ui-button-text">Subir Archivo</span>
                                                            </button>                                                        
                                                        </td>
                                                        <td align="center">
                                                            <button id="btnProcesarCargaLlamada" style="display:none" onclick="generaProcesarCargaLlamada()" class="ui-state-default ui-corner-all ui-button ui-widget ui-button-text-only" role="button" aria-disabled="false">
                                                                <span class="ui-button-text">Realizar Carga de Llamadas</span>
                                                            </button>                                                                                                                    
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        </br>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <div style="width:350px" class="noteDiv ui-corner-all">
                                                                <table border="0" cellspacing="2" cellpadding="2" style="width:100%;">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td class="">
                                                                                <div class="text-alert">IMPORTANTE</div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <ul>
                                                                                    <b>Formatos Soportados</b>
                                                                                    <li class="text-alert">
                                                                                        Solo soporta archivos de texto <b>( .txt )</b>
                                                                                    </li>
                                                                                    <li>
                                                                                Si el archivo esta en formato Excel ( .xls ), copielo de excel a Block de Notas y guardelo como txt. Si tiene dudas para realizar esto, porfavor solicite apoyo al Area de Sistemas.
                                                                                    </li>
                                                                                </ul>
                                                                                <ul>
                                                                                    <b>Notas importantes</b>
                                                                                    <li class="text-alert">
                                                                                        El archivo txt debe contener los campos : <b>NOMBRE_GESTION, NUMERO_CUENTA  ,FACTURA, TELEFONO  ,  CODIGO_CLIENTE,  FECHA_LLAMADA,   HORA_LLAMADA,    RESPUESTA,   RESPUESTA_INCIDENCIA,    CODIGO_OPERADOR_ASIGNADO ,   OBSERVACION , MONEDA_LLAMADA , LETRA_LLAMADA, TIPO_DOCUMENTO_LLAMADA, CUOTA_DOCUMENTO_LLAMADA, DOC_INTERNO_LLAMADA, CODIGO_EMPRESA_LLAMADA</b>. Que es el formato comun que trabaja la Empresa.
                                                                                    </li>
                                                                                    <li>
                                                                                        Siendo el RESPUESTA_INCIDENCIA el codigo del estado
                                                                                    </li>                                                                                
                                                                                    <li>
                                                                                        La primera fila del archivo debe de contener las cabeceras
                                                                                    </li>
                                                                                    <li>
                                                                                        Los campos deben estar separados por "<b>tab</b>".
                                                                                    </li>
                                                                                    <li>
                                                                                        Las fechas debe estar en el formato <b>aaaa-mm-dd</b>. Las fechas que tengan otros formatos serán ignoradas.
                                                                                    </li>
                                                                                    <li>
                                                                                        Las horas debe estar en el formato <b>hh:mm:ss</b>. Las horas que tengan otros formatos serán ignoradas.
                                                                                    </li>
                                                                                    <li>
                                                                                        En el Campo OBSERVACION no incluir acentos en los textos. Para evitar cualquier alteracion de la misma.
                                                                                    </li>
                                                                                </ul>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>   
                                                        </td>                                                     
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" align="center">
                                                            <table class="tools" style="width:300px;">
                                                                <tr>
                                                                    <td>
                                                                        <div align="center">
                                                                            <table border="0" cellspacing="0" cellpadding="0">
                                                                                <tbody><tr>
                                                                                    <td>
                                                                                        <div>
                                                                                            <table>
                                                                                                <tbody><tr>
                                                                                                    <td><div class="tools-icon"></div></td>
                                                                                                    <td><div class="tools-header">Prospecto Estados</div></td>
                                                                                                </tr>
                                                                                            </tbody></table>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <div>
                                                                                            <ul class="tools-ul">
                                                                                                <li><a href="../rpt/excel/estados/PaletadeEstado.php?Servicio=1">Exportar Estados</a></li>
                                                                                            </ul>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <div>
                                                                                            <ul class="tools-ul">
                                                                                                <li><a href="../rpt/excel/usuario/ListUsuarioByService.php?Servicio=<?= $_SESSION['cobrast']['idservicio'] ?>" >Exportar Usuarios</a></li>
                                                                                            </ul>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody></table>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>                                                            
                                                        </td>
                                                    </tr>
                                                </table>                                                
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div id="panelCargaGestionAdicional" style="display:none;width:100%;border:0 none;padding-top:7px" align="center">
                                <table>
                                    <tr>
                                        <td>
                                            <div>
                                                <table>
                                                    <tr>
                                                        <td>Campa&ntilde;a</td>
                                                        <td>
                                                            <select id="cboCampaniaEnvioMensaje" onchange="listar_carteras( this.value, [{id:'cboCarteraEnvioMensaje'}] )" class="combo">
                                                                <option value="0">--Seleccione--</option>
                                                            </select>
                                                        </td>
                                                        <td>Cartera</td>
                                                        <td>
                                                            <select class="combo" id="cboCarteraEnvioMensaje">
                                                                <option value="0">--Seleccione--</option>
                                                            </select>
                                                        </td>
                                                        <td>Caracter Separador</td>
                                                        <td>
                                                            <select id="cbCaracterSeparadorEnvioMensaje" class="combo">
                                                                <option value="tab">TAB</option>
                                                                <option value="|">|</option>
                                                                <option value=";">;</option>
                                                            </select>
                                                        </td>
                                                        <td>Tipo</td>
                                                        <td>
                                                            <select id="cboTipoEnvioMensaje" class="combo">
                                                                <option value="ENMSN">Envio Mensaje</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        </td>
                                            

                                                <table class="ui-widget-content ui-corner-all">
                                                    <tr>
                                                        <td align="right">Codigo Cliente</td>
                                                        <td><select id="codigo_cliente" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Numero Cuenta</td>
                                                        <td><select id="numero_cuenta" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Moneda Cuenta</td>
                                                        <td><select id="moneda" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Grupo1 Cuenta</td>
                                                        <td><select id="grupo1" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">Fecha</td>
                                                        <td><select id="fecha" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Hora</td>
                                                        <td><select id="hora" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Evento</td>
                                                        <td><select id="evento" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Telefono</td>
                                                        <td><select id="telefono" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">Mensaje</td>
                                                        <td><select id="mensaje" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Observacion</td>
                                                        <td><select id="observacion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td align="right">Codigo Estado</td>
                                                        <td><select id="codigo_estado" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </table>
                                        </td>
                                        <td id="showhide" class="showHide ui-widget-header" width="10px">
                                            <a >
                                                <div style="width:8px"></div>
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!--  Aqui Termina el contenido de mi pagina. -->
                    </td>

                </tr>
            </table>        
            <div style="width: 1038px; height: 20px; border: 0 none;margin:0px auto" class="ui-widget-header ui-corner-bottom"></div>

        </div>

        <div id="layerOverlay" class="ui-widget-overlay" style="display: none;"></div>
        <div id="layerLoading" style="position:absolute ;left: 50%;top: 45%; width: 100px; font-weight: bold; font-size: 18px; color: #AFAFAF; z-index: 100;display: none;">Loading...</div>

        <div id="beforeSendShadow" class="ui-widget-shadow" style="height:30px;position:absolute;top:32%;left:40%;display:none;z-index:1010;"></div>
        <div id="MsgBeforeSend" class="ui-widget-content ui-corner-all" style="height:30px;position:absolute;top:32%;left:40%;font-weight:bold;font-size:20px;vertical-align:middle;color:#333366;display:none;z-index:1020;" align="center"  ></div>

        <div class="ui-widget-overlay" id="closeWindowCobrastOverlay" style="display:none"></div>
        <div class="status-window" id="closeWindowCobrastProgressBar" style="top:35%;left:46%;display:none;"  >
            <table class="status-window-table" cellspacing="0" cellpadding="3" border="0" >
                <tr align="left" valign="top">
                    <td colspan="2" valign="top" height="30px" width="100%" nowrap="nowrap" style="font-family:Verdana;font-size:12px;">Please wait......</td>
                </tr>
                <tr align="left" valign="top">
                    <td colspan="2" align="center" nowrap="nowrap" ><img height="7" width="100" src="../img/progressbarsmall.gif" /></td>
                </tr>
            </table>
        </div>
        <div id="msgCovinocPreparacion" style="transition: opacity 0.2s ease 0s; padding: 15px; margin: 0px auto; width: 200px; position: fixed; top: 0px; right: 0px;opacity:0" class="ui-widget-content ui-corner-all">
            <span class="fa fa-exclamation" style="margin-left:15px;margin-right:15px"></span>
        </div>
    </body>
</html>
