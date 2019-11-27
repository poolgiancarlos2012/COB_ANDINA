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
        <title>Reportes</title>
        <link type="text/css" rel="stylesheet" href="../includes/jqgrid6/css/ui.jqgrid.css" />
        <link type="text/css" rel="stylesheet" href="../includes/jquery-ui-1.8/themes/humanity/jquery-ui.css" />

        <link type="text/css" rel="stylesheet" href="../css/css-cobrast.css" />
        <script type="text/javascript" src="../js/includes/jquery-1.4.2.js" ></script>
        <script type="text/javascript" src="../js/includes/jquery.upload-1.0.2.js" ></script>

        <script type="text/javascript" src="../includes/jqgrid6/src/i18n/grid.locale-en.js" ></script>
        <script type="text/javascript" src="../includes/jqgrid6/src/jqModal.js" ></script>
        <script type="text/javascript" src="../includes/jqgrid6/src/grid.base.js" ></script>
        <script type="text/javascript" src="../includes/jqgrid6/src/grid.common.js" ></script>

        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.core.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.widget.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.button.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.datepicker.min.js" ></script>

        <script type="text/javascript" src="../js/js-cobrast.js" ></script>
        <script type="text/javascript" src="../js/ReporteDAO.js" ></script>
        <script type="text/javascript" src="../js/js-reporte.js" ></script>
    </head>
    <body>
        <div class="divContentMain">
            <table class="tableTab" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td rowspan="2" width="100"></td>
                    <td>
                        <div class="rightItem">
                            <div class="fltRight">
                                <a class="itemTop" href="../close.php">Cerrar Sesion</a>
                            </div>
                            <div class="fltRight">
                                <a class="itemTop">Ayuda</a>
                            </div>
                            <div class="fltRight">
                                <a class="itemTop">Whats' New</a>
                            </div>
                            <strong style="margin-right: 5px;">Bienvenido: <?= $_SESSION['cobrast']['usuario'] ?></strong>
                            <strong style="margin-right: 5px;">Servicio: <?= $_SESSION['cobrast']['servicio'] ?></strong>
                            <strong style="margin-right: 5px;">Privilegio: <?= $_SESSION['cobrast']['privilegio'] ?></strong>                            
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="vAlignBottom tabsLine">
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
                                            } else if ($_SESSION['cobrast']['privilegio'] == 'reporte') {
                                                require_once('../menus/menu-reporte.php');
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
                    <td class="lineTab ui-widget-header" colspan="2"></td>
                </tr>
            </table>
            <table class="tableContent" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="barLayer">
                        <div id="barLayer" style="width:210px; display:none; background:#fffbf2;border: 1px solid #666;margin:0;position:absolute;z-index:9999;height:100%;overflow:auto;" >
                            <div align="right"><img src="../img/cancel.png" style="cursor:pointer;margin:3px;" onClick="$('#barLayer').css('display','none');"/></div>
                            <div class=" backPanel headerPanel">
                                <div class="backPanel iconPinUp" onclick="_slideBarLayer(this,'panelMenu')">Menu</div>
                            </div>
                            <div id="panelMenu" class="backPanel contentBarLayer" style="display:block;" >
                                <div style="width:100%;">
                                    <div style="margin-left:20px; margin-bottom:2px;"><a class="text-blue" onClick="_display_panel('panelHomeReporte')">Home</a></div>
                                    <div style="margin-left:20px; margin-bottom:2px;"><a class="text-blue" onClick="_display_panel('panelFinancieroReporte')">Financiero</a></div>
                                </div>
                            </div>
                            <div class=" backPanel headerPanel">
                                <div class="backPanel iconPinUp" onclick="_slideBarLayer(this,'panelCalendario')" >Calendario</div>
                            </div>
                            <div align="center" id="panelCalendario" style="padding:3px 0;display:block;">
                                <div id="layerDatepicker"></div>
                            </div>
                        </div>
                    </td>
                    <td id="showhide" width="10px" class="showHide ui-widget-header" >
                        <a onclick="_sliderFadeBarLayer();">
                            <div id="iconSlider" class="slider icon sliderIconUp"></div>
                        </a>
                    </td>
                    <td width="100%">	
                        <div id="cobrastHOME" style="width:100%; height:100%; border:0 none;" class="ui-widget-content">
                            <input type="hidden" id="hdCodUsuario" name="hdCodUsuario" value="<?= $_SESSION['cobrast']['idusuario'] ?>" />
                            <input type="hidden" id="hdCodServicio" name="hdCodServicio" value="<?= $_SESSION['cobrast']['idservicio'] ?>" />
                            <input type="hidden" id="hdCodUsuarioServicio" name="hdCodUsuarioServicio" value="<?= $_SESSION['cobrast']['idusuario_servicio'] ?>" />
                            <input type="hidden" id="hdNomServicio" name="hdNomServicio" value="<?= $_SESSION['cobrast']['servicio'] ?>" />
                            <div id="panelHomeReporte" align="center" style="display:block;">
                                <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                    <tr>
                                        <td>
                                            <div align="center">
                                                <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                    <tr bgcolor="#daedff" align="center">
                                                        <td style="padding:2px">
                                                            <table border="0" class="ui-state-highlight ui-corner-all"> 
                                                                <tr>
                                                                    <td width="125" align="center">
                                                                        <label class="text-blue">Estado de Carteras</label>
                                                                    </td>
                                                                    <td width="200" align="center">
                                                                        <div class="ui-datepicker-header ui-widget-header ui-helper-clearfix ui-corner-all" align="center">
                                                                            <table id="tbEstadoCarteraReporte" border="0">
                                                                                <tr>
                                                                                    <td>No Vencida&nbsp;</td>
                                                                                    <td><input type="checkbox" onclick="limpiaCamposReporte();" value="0" name="no_vencida" checked="checked" /></td>
                                                                                    <td width="15"></td>
                                                                                    <td>Vencida&nbsp;</td>
                                                                                    <td><input type="checkbox" onclick="limpiaCamposReporte();" value="1" name"vencida" /></td>
                                                                                </tr>
                                                                            </table>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td id="content_reportes_bottom">
                                                            <div id="layer_Form_reportes_una_cartera" class="ui-widget-content" style="display:block; padding:5px 0; width:100%;height:100%;overflow:auto;" align="center">
                                                                <div>
                                                                    <table border="0">
                                                                        <tr>          
                                                                            <td valign="top">
                                                                                <table>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div class="ui-helper-reset" >
                                                                                                <h3 onclick="_slide3(this,'rptPremium',0)" style="padding-left:3px;" class="ui-helper-reset pointer ui-widget-header ui-corner-top">Premiun</h3>
                                                                                                <div id="rptPremium" style="display:none" class="ui-helper-reset ui-widget-content ui-corner-bottom" align="center">
                                                                                                    <table>
                                                                                                        <tr>
                                                                                                            <td>Campa&ntilde;a</td>
                                                                                                            <td><select class="combo" id="cbCampaniaPremiun" onchange="load_cartera_by_id_rpte_rank(this.value,'cbCarteraPremiun')"><option value="0">--Seleccione--</option></select></td>
                                                                                                            <td>Cartera</td>
                                                                                                            <td><select class="combo" id="cbCarteraPremiun" ><option value="0">--Seleccione--</option></select></td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <td colspan="4">
                                                                                                                <button onclick="link_premiun()" class="ui-state-default ui-corner-all"><img src="../img/page_excel.png" /><span>Exportar</span></button>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </table>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div id="layer_Form_reportes_varias_carteras" class="ui-widget-content" style="display:none; padding:5px 0; width:100%;height:100%;overflow:auto;" align="left">
                                                                <div>
                                                                    <table border="0">
                                                                        <tr>
<!--                                                                            <td valign="top">
                                                                                <table>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div class="ui-helper-reset ui-widget-content ui-corner-all" >
                                                                                                <h3 class="ui-helper-reset ui-widget-header ui-corner-top" style="padding-left:3px;">TRANSFERENCIA POR INSATISFACCION</h3>
                                                                                                <div align="center" class="ui-helper-reset" style="padding:5px;" >
                                                                                                    <table style="width:100%;">
                                                                                                        <tr>
                                                                                                            <td>Campa&ntilde;a</td>
                                                                                                            <td><select class="combo" id="cbCampania_transferencia_insatisfaccion" name="campania" onchange="load_reporte_cartera_tb_rpte_rank(this.value,'tbRKCartera_transferencia_insatisfaccion');"><option value="0">--Seleccione--</option></select></td>
                                                                                                        </tr>
                                                                                                    </table>
                                                                                                    <div class="ui-widget-header ui-corner-top" style="width:260px;" align="left">
                                                                                                        <table style="width:100%;">
                                                                                                            <tr>
                                                                                                                <td align="left">Carteras</td>
                                                                                                                <td align="right"><input type="checkbox" onclick="if( this.checked ){ $('#tbRKCartera_transferencia_insatisfaccion :checkbox').attr('checked',true); }else{ $('#tbRKCartera_transferencia_insatisfaccion :checkbox').attr('checked',false); }" /></td>
                                                                                                            </tr>
                                                                                                        </table>
                                                                                                    </div>
                                                                                                    <div style="overflow:auto;height:150px;width:260px;"><table id="tbRKCartera_transferencia_insatisfaccion" cellpadding="0" cellspacing="0" border="0"></table></div>
                                                                                                    <div class="ui-widget-header ui-corner-bottom" style="width:260px;" align="left">
                                                                                                        <table>
                                                                                                            <tr>
                                                                                                                <td>Buscar:</td>
                                                                                                                <td>
                                                                                                                    <input onkeyup="search_text_table(this.value,'tbRKCartera_transferencia_insatisfaccion')" type="text" class="cajaForm" />
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                        </table>
                                                                                                    </div>
                                                                                                    <div style="margin-top:5px;margin-bottom:5px;">
                                                                                                        <div style="width:260px;" class="ui-widget-header ui-corner-top">Estados de Insatisfaccion</div>
                                                                                                        <div class="ui-state-active" align="left" style="overflow:auto;height:200px;width:260px;display: none;" id="layerContent_estado_transferencia_insatisfaccion"></div>
                                                                                                        <div style="width:260px;" class="ui-state-active ui-corner-bottom" align="left">
                                                                                                            <table>
                                                                                                                <tr>
                                                                                                                    <td>Buscar:</td>
                                                                                                                    <td>
                                                                                                                        <input onkeyup="search_text_table(this.value,'layerContent_estado_transferencia_insatisfaccion')" type="text" class="cajaForm" />
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                            </table>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <button onclick="link_transferencia_insatisfaccion()" class="ui-state-default ui-corner-all"><img src="../img/page_excel.png" />Exportar</button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </td>-->
                                                                            <td valign="top">
                                                                                <table>
                                                                                    <tr> <!-- jc AVANCE GESTION -->
                                                                                        <td>
                                                                                            <div class="ui-helper-reset" style="width:720px" >
                                                                                                <h3 style="padding-left:3px;" class="ui-helper-reset ui-widget-header ui-corner-top pointer">
                                                                                                    Reporte
                                                                                                    <select class="combo" id="cboReporte" style="width:300px;" onchange="handlerChangeCboReporte();">
                                                                                                       <!-- <optgroup label="GENERAL">
                                                                                                            <option name="CENCOSUD,MOVIL,COBRANZA Y EJECUCION" value="1">AVANCE CARTERAS</option>
                                                                                                            <option name="MOVIL" value="2">GESTION DIARIA 2</option>
                                                                                                            <option name="OTROS" value="3">GENERAR IVR</option>
                                                                                                            <option name="CENCOSUD,MOVIL" value="4">RETIROS</option>
                                                                                                            <option name="OTROS" value="5">EMPRESA</option>
                                                                                                            <option name="OTROS" value="6">DIRECCION CORREGIDA</option>
                                                                                                            <option name="OTROS" value="7">CORTE FOCALIZADO(PARA DEPURACION)</option>
                                                                                                            <option name="OTROS" value="8">FACTURA DIGITAL</option>
                                                                                                            <option name="OTROS" value="9">CLIENTES SIN RECIBOS FISICOS</option>
                                                                                                            <option name="OTROS" value="10">TMO</option>
                                                                                                            <option name="OTROS" value="11">FACTURACION</option>
                                                                                                            <option name="OTROS" value="12">GESTION LLAMADAS 2</option>
                                                                                                            <option name="OTROS" value="13">GENERAR SIG</option>
                                                                                                            <option name="OTROS" value="14">TRANSFERENCIA POR INSATISFACCION</option>
                                                                                                            <option name="OTROS" value="15">P_CAMPAÑA</option>
                                                                                                            <option name="MOVIL,COBRANZA Y EJECUCION" value="16">LLAMADAS POR ESTADO</option>
                                                                                                            <option name="OTROS,COBRANZA Y EJECUCION" value="17">CONTACTABILIDAD</option>
                                                                                                            <option name="CENCOSUD,MOVIL,COBRANZA Y EJECUCION" value="18">FOTOCARTERA</option>
                                                                                                            <option name="MOVIL,COBRANZA Y EJECUCION" value="19">GESTION DIARIA</option>
                                                                                                            <option name="OTROS,PREMIUM" value="20">PREMIUN</option>
                                                                                                            <option name="CENCOSUD,MOVIL,COBRANZA Y EJECUCION" value="21">RESUMEN DE CARGAS</option>
                                                                                                            <option name="CENCOSUD,MOVIL,COBRANZA Y EJECUCION" value="22">GESTION DE LLAMADAS</option>
                                                                                                            <option name="CENCOSUD,MOVIL,COBRANZA Y EJECUCION" value="23">VISITAS</option>
                                                                                                            <option name="OTROS,COBRANZA Y EJECUCION" value="24">NOTIFICACION</option>
                                                                                                            <option name="CENCOSUD,MOVIL,COBRANZA Y EJECUCION" value="25">RELACION DE GESTORES</option>
                                                                                                            <option name="MOVIL,COBRANZA Y EJECUCION" value="26">CLIENTES</option>
                                                                                                            <option name="OTROS,COBRANZA Y EJECUCION" value="27">ENVIO CALL A CAMPO</option>
                                                                                                            <option name="OTROS" value="29">FACTURAS</option>
                                                                                                            <option name="CENCOSUD,MOVIL,COBRANZA Y EJECUCION" value="30">PAGOS</option>
                                                                                                            <option name="OTROS,MOVIL,COBRANZA Y EJECUCION" value="32">DIRECCIONES</option>
                                                                                                            <option name="OTROS,MOVIL,COBRANZA Y EJECUCION" value="33">COMPROMISO DE PAGO</option>
                                                                                                            <option name="CENCOSUD,MOVIL" value="34">TELEFONOS</option>
                                                                                                            <option name="CENCOSUD,MOVIL" value="35">RESUMEN DE GESTIONES</option>
                                                                                                            <option name="CENCOSUD" value="36">CLIENTES MAS DE 1 CUENTA</option>
                                                                                                            <option name="CENCOSUD,MOVIL" value="37">CUENTAS DESACTIVADAS</option>
                                                                                                        </optgroup>
                                                                                                        <optgroup label="CENCOSUD" >
                                                                                                            <option name="CENCOSUD" value="38">LLAMADAS ( + DIRECCIONES VALIDADAS )</option>
                                                                                                            <option name="CENCOSUD" value="43">LLAMADAS ( FORMATO CONTROL OPERACIONES )</option>
                                                                                                            <option name="CENCOSUD" value="39">REFINANCIAMIENTO</option>
                                                                                                            <option name="CENCOSUD" value="40">PRIORIDAD</option>
                                                                                                            <option name="CENCOSUD" value="41">DIARIO</option>
                                                                                                            <option name="CENCOSUD" value="42">DIARIO RESUMEN</option>
                                                                                                            <option name="CENCOSUD" value="46">CAMPO</option>
                                                                                                            <option name="CENCOSUD" value="90">INCREMENTAL DE PROVISIONES DETALLE</option>
                                                                                                            <option name="CENCOSUD" value="91">INCREMENTAL DE PROVISIONES RESUMEN</option>
                                                                                                        </optgroup>
                                                                                                        <optgroup label="CONTROL" >
                                                                                                            <option name="CENCOSUD" value="28">MARCACIONES</option>
                                                                                                            <option name="MOVIL" value="31">CONTACTABILIDAD HORARIA OPERADOR</option>
                                                                                                            <option name="CENCOSUD" value="44">CONTACTABILIDAD HORARIA</option>
                                                                                                            <option name="CENCOSUD" value="45">PRODUCTIVIDAD</option>
                                                                                                        </optgroup>
                                                                                                        <optgroup label="MOVIL" >
                                                                                                            <option name="MOVIL" value="47">ANALISIS DE CARTERA POR ZONAL</option>
                                                                                                            <option name="MOVIL" value="48">ANALISIS DE LLAMADAS POR ESTADO</option>
                                                                                                            <option name="MOVIL" value="49">ANALISIS DE LLAMADAS POR CONTACTO</option>
                                                                                                            <option name="MOVIL" value="50">ANALISIS DE LLAMADAS POR ABONADO Y LLAMADAS</option>
                                                                                                            <option name="MOVIL" value="51">ESTADISTICO DE ABONADO POR MONTO Y GESTION</option>
                                                                                                            <option name="MOVIL" value="52">INUBICADOS NEGOCIOS</option>
                                                                                                            <option name="MOVIL" value="53">MODELO DE DEVOLUCION DETALLE</option>
                                                                                                            <option name="MOVIL" value="60">MODELO DE DEVOLUCION RESUMEN</option>
                                                                                                            <option name="MOVIL" value="54">CUADRO DE COMPOSICION DE CARTERA Y UBICABILIDAD - CONTACTABILIDAD POR ZONAL</option>
                                                                                                            <option name="MOVIL" value="55">CUADRO DE COMPOSICION DE CARTERA Y UBICABILIDAD - DETALLE DIARIO CONTACTO</option>
                                                                                                            <option name="MOVIL" value="56">CUADRO DE COMPOSICION DE CARTERA Y UBICABILIDAD - TEMATICO</option>
                                                                                                            <option name="MOVIL" value="57">RANKING RESUMEN</option>
                                                                                                            <option name="MOVIL" value="58">RANKING DETALLE</option>
                                                                                                            <option name="MOVIL" value="62">RECUPERO DETALLE</option>
                                                                                                            <option name="MOVIL" value="63">RECUPERO CUADRO RESUMEN</option>
                                                                                                            <option name="MOVIL" value="64">PLAN DETALLE</option>
                                                                                                            <option name="MOVIL" value="65">PLAN CUADRO RESUMEN</option>
                                                                                                            <option name="MOVIL" value="66">SALDO DE CARTERA POR DISTRITO</option>
                                                                                                            <option name="MOVIL" value="67">CLIENTE X TELEOPERADORES</option>
                                                                                                            <option name="MOVIL" value="68">CLIENTE X RANGO DEUDA</option>
                                                                                                            <option name="MOVIL" value="69">CLIENTE X RANGO DISTRITO</option>
                                                                                                            <option name="MOVIL" value="70">CLIENTE X RANGO STATUS</option>
                                                                                                            <option name="MOVIL" value="71">CLIENTE X RANGO CICLO FACTURACION</option>
                                                                                                            <option name="MOVIL" value="72">CLIENTE X RANGO PLANES MAX Y SMARTPHONES</option>
                                                                                                            <option name="MOVIL" value="73">RANKING DE GESTORES TELEFONICOS</option>
                                                                                                            <option name="MOVIL" value="74">CAMPA&Ntilde;A</option>
                                                                                                        </optgroup>-->
                                                                                                        <option value="0">--Seleccione--</option>
                                                                                                        <optgroup label="GENERALES">
                                                                                                            <option name="COVINOC" value="147">ARCHIVO PLANO DE GESTIONES - LLAMADAS Y VISITAS</option>
                                                                                                            <option name="COVINOC" value="139">ARCHIVO PLANO DE ACUERDOS DE PAGO</option>
                                                                                                            <option name="COVINOC" value="140">ARCHIVO PLANO DE OBLIG. DE LOS ACUERDOS DE PAGO</option>
                                                                                                            <option name="COVINOC" value="141">ARCHIVO PLANO DE CUOTAS DE LOS ACUERDOS DE PAGO</option>
                                                                                                            <option name="COVINOC" value="142">ARCHIVO PLANO DE TELÉFONOS (INGRESADOS EN GESTION)</option>
                                                                                                            <option name="COVINOC" value="143">ARCHIVO PLANO DE DIRECCIONES (INGRESADOS EN GESTION)</option>
                                                                                                            <option name="COVINOC" value="144">ARCHIVO PLANO DE EMAILS (INGRESADOS EN GESTION)</option>
                                                                                                            <option name="COVINOC" value="145">ARCHIVO PLANO DE CODIGO GESTIÓN (LA ACTUAL PALETA DE ESTADO)</option>
                                                                                                            <option name="COVINOC" value="146" style="color:green;font-weight:bold">ARCHIVO PLANO DE CODIGO GESTIÓN HISTORIAL(PALETA DE ESTADO TOTAL UTILIZADA PARA TODAS LAS CARTERAS)</option>
                                                                                                            
                                                                                                            <!--<option name="COVINOC" value="138">ARCHIVO PLANO DE GESTIONES - SOLO LLAMADAS(PARA LAS CARTERAS CON LA ANTIGUA ESTRUCTURA - 8 CARTERAS)</option>-->
                                                                                                            <option name="COVINOC,SAGA" value="148">TELEFONOS ( TOTAL )</option>


                                                                                                            <option name="COBRANZA Y EJECUCION,AGENCIA,GLOBAL COM,SCI,BBVA" value="18">FOTOCARTERA</option>
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL,COVINOC" value="114">FOTOCARTERA COMPLETO</option>
                                                                                                            <option name="CONECTA" value="150">FOTOCARTERA</option>
                                                                                                            <option name="FORUM" value="123">FOTOCARTERA</option>
                                                                                                            <option name="SAGA" value="93">FOTOCARTERA SAGA</option>
                                                                                                            <option name="SAGA" value="136">FORMATO DE GESTIÓN DIARIA - ESTUDIOS EXTERNOS ( SAGA )</option>
                                                                                                            <option name="SAGA" value="137">FORMATO DE GESTIÓN DIARIA - ESTUDIOS EXTERNOS ( HDEC )</option>
																											<option name="BBVA,EXTRA_JUDICIAL" value="94">RESPUESTA DE GESTION</option>
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="116">RESPUESTA DE GESTION 2</option>
                                                                                                            <option name="FORUM,BBVA, EXTRA_JUDICIAL, SAGA, COVINOC, CONECTA,OPCION" value="22">GESTION DE LLAMADAS</option>  
                                                                                                            <option name="FORUM,BBVA, EXTRA_JUDICIAL" value="149">GESTION DE LLAMADAS (SÓLO CALL)</option>                                                                                                           
                                                                                                            <option name="FORUM,BBVA,EXTRA_JUDICIAL,COVINOC,CONECTA" value="23">VISITAS</option>                                                                                                            
                                                                                                            <option name="COBRANZA Y EJECUCION,AGENCIA,GLOBAL COM,SCI,SAGA" value="77">FOTOCARTERA (SOLO GESTIONADOS)</option>
                                                                                                            <option name="COBRANZA Y EJECUCION,AGENCIA,GLOBAL COM,SCI,SAGA,BBVA,EXTRA_JUDICIAL" value="16">LLAMADAS POR ESTADO</option>                                                                                                            
                                                                                                            <option name="COBRANZA Y EJECUCION,AGENCIA,GLOBAL COM,SAGA" value="30">PAGOS</option>                                                                                                            
                                                                                                            <option name="COBRANZA Y EJECUCION,AGENCIA,GLOBAL COM,SCI,SAGA,BBVA,EXTRA_JUDICIAL" value="33">COMPROMISO DE PAGO</option>                                                                                                            
                                                                                                            <option name="COBRANZA Y EJECUCION,AGENCIA,GLOBAL COM,SAGA" value="32">DIRECCIONES NUEVAS</option>                                                                                                            
                                                                                                            <option name="COBRANZA Y EJECUCION,AGENCIA,GLOBAL COM,SAGA" value="39">REFINANCIAMIENTO</option>                                                                                                            
                                                                                                            <option name="COBRANZA Y EJECUCION,AGENCIA,GLOBAL COM,SAGA,BBVA,EXTRA_JUDICIAL" value="75">REPORTE VERTICAL CLIENTE</option>
                                                                                                            <option name="COBRANZA Y EJECUCION,AGENCIA,GLOBAL COM,SAGA" value="27">ENVIO CALL A CAMPO</option>
                                                                                                            <option name="COBRANZA Y EJECUCION,AGENCIA,GLOBAL COM,SAGA,BBVA,EXTRA_JUDICIAL" value="76">REPORTE DE ESTADOS POR LLAMADA HORIZONTAL</option>                                                                                                            
                                                                                                            <option name="COBRANZA Y EJECUCION,AGENCIA,GLOBAL COM,SCI,SAGA,BBVA,EXTRA_JUDICIAL" value="78">MEJOR LLAMADAS</option>                                                                                                                                                                                                                        
                                                                                                            <option name="COBRANZA Y EJECUCION,AGENCIA,GLOBAL COM,SCI,SAGA" value="79">TELEFONOS NUEVOS</option>                                                                                                                                                                                                                                                                                                                                    
                                                                                                            <option name="COBRANZA Y EJECUCION,SCI,SAGA" value="80">TELEFONOS POR CLIENTE (HORIZONTAL)</option>
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="95">TELEFONOS POR CLIENTE (HORIZONTAL) NEOTEL</option>
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="108">TELEFONOS POR CLIENTE X ESTADOS - NEOTEL</option>
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="113">TELEFONOS NIVEL CLIENTE Y ESTADOS - NEOTEL</option>
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="115">TELEFONOS SIN GESTION - NEOTEL</option>
                                                                                                            <option name="COBRANZA Y EJECUCION,AGENCIA,GLOBAL COM,SCI,SAGA,BBVA,EXTRA_JUDICIAL" value="81">VISITAS POR CLIENTE</option>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
                                                                                                            <option name="COBRANZA Y EJECUCION,AGENCIA,GLOBAL COM,SCI,SAGA" value="82">TELEFONOS CORRECTOS E INCORRECTOS</option>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
                                                                                                            <option name="COBRANZA Y EJECUCION,AGENCIA,GLOBAL COM,SCI,SAGA" value="85">DIRECCIONES CORRECTOS E INCORRECTOS</option>
                                                                                                            <option name="COBRANZA Y EJECUCION,AGENCIA,GLOBAL COM,SAGA,BBVA,EXTRA_JUDICIAL" value="83">LLAMADAS POR CLIENTE(HORIZONTAL)</option>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
                                                                                                            <option name="SCI" value="84">CUENTA POR CLIENTE</option>
                                                                                                            <option name="COBRANZA Y EJECUCION,AGENCIA,GLOBAL COM,SAGA" value="86">DIRECCIONES(VERTICAL)</option>
                                                                                                            <option name="GLOBAL COM,SAGA" value="87">ESTADO DEL CLIENTE</option>  
                                                                                                            <option name="GLOBAL COM,SAGA" value="92">GESTION DE LLAMADAS GLOBAL COM</option>                                                                                                              
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="96">CARTA DE CAMPO</option>
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="105">SIN GESTION POR CARTERA</option>
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="106">SIN GESTION POR FECHA</option>
																											<option name="BBVA,EXTRA_JUDICIAL" value="107">LISTADO DE TELEFONOS VERTICALES</option>
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="109">REPORTE</option>
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="110">RESUMEN DE DISTRIBUCION</option>
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="112">POR GESTIONAR CAMPO</option>
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="117">REPORTE ESTADO DE CUENTA</option>
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="118">TELEFONOS POR CLIENTE (HORIZONTAL) NEOTEL - PROGRESIVO</option>                                                                                                            
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="119">TELEFONOS POR CLIENTE X ESTADOS - NEOTEL - PROGRESIVO</option>                                                                                                            
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="120">TELEFONOS NIVEL CLIENTE Y ESTADOS - NEOTEL - PROGRESIVO</option>                                                                                                            
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="121">TELEFONOS SIN GESTION - NEOTEL - PROGRESIVO</option>   
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="122">RESUMEN DE CARGA</option>  
                                                                                                            <option name="FORUM" value="125">RESPUESTA DIARIA</option>
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="126">CARGA FACTURAS</option>
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="127">REPORTE PROVISION</option>
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="128">REPORTE CONTACTABILIDAD GESTION</option> 
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="129">REPORTE MODELO CALL(CDR)</option> 
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="130">DIRECCIONES HIPOTECARIO</option>
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="131">RESPUESTA PRUEBA</option>
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="132">ENVIAR CARGO</option>      
                                                                                                            <option name="EXTRA_JUDICIAL" value="133">RESPUESTA GESTION</option>
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="134">REPORTE PROVISION TOTAL</option>
                                                                                                            <option name="OPCION" value="151">FOTOCARTERA OPCION</option>
                                                                                                            <option name="OPCION" value="152">VISITAS OPCION</option>
                                                                                                            <option name="OPCION" value="153">REPORTE DIARIO DE LLAMADAS</option>
                                                                                                            <option name="OPCION" value="154">REPORTE DIARIO DE VISITAS</option>
                                                                                                            <option name="OPCION" value="155">REPORTE DE COBERTURA DIARIA</option>
                                                                                                            <option name="ANDINA" value="156">FOTOCARTERA</option>
                                                                                                            <option name="ANDINA" value="157">GESTION LLAMADAS</option>
                                                                                                            <option name="ANDINA" value="158">STATUS COLOCACION</option>
                                                                                                        </optgroup>
                                                                                                        <optgroup label="CDO">                                                                                                               
																											<option name="BBVA,EXTRA_JUDICIAL" value="28">MARCACIONES</option>                                                                                                            
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="97">REPORTE DE CONTACTABILIDAD</option>
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL,EXTRA_JUDICIAL" value="98">REPORTE DE CONTACTABILIDAD POR CORTE</option>
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="99">REPORTE DE EFECTIVIDAD DIARIA</option>
																											<option name="BBVA,EXTRA_JUDICIAL" value="101">REPORTE DE FOTOCARTERA</option>
																											<option name="BBVA,EXTRA_JUDICIAL" value="102">INFORME DE CARTERIZACION</option>
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="100">GESTION DE LLAMADAS</option>
																											<option name="BBVA,EXTRA_JUDICIAL" value="103">VISITAS POR CLIENTE</option>
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="104">FOTOCARTERA</option>
																											<option name="BBVA,EXTRA_JUDICIAL" value="111">REPORTE DE COBERTURA DIARIA</option>
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="124">REPORTE ACUMULADO</option>
                                                                                                            <option name="BBVA,EXTRA_JUDICIAL" value="135">INDICADORES CONTRATOS RETIRADOS</option>
                                                                                                        </optgroup>
                                                                                                        
                                                                                                    </select>
                                                                                                </h3>
                                                                                                <div id="divReportes" style="display:block" class="ui-helper-reset ui-widget-content ui-corner-bottom" align="center">
                                                                                                    <table>
                                                                                                        <!--<tr>
                                                                                                            <td colspan="2">
                                                                                                                <a href="#" id="linkPCcart" style="display:block;" onclick=" $(this).css('display','none');$('#trCampania').css('display','block'); " >Por Campania</a>
                                                                                                                <a href="#" id="linkMulticart" style="display:none;" onclick=" $(this).css('display','none');$('#trCampania').css('display','none'); " >Multiple</a>
                                                                                                            </td>
                                                                                                        </tr>-->
                                                                                                        <tr style="display:none;" id="trCampania" >
                                                                                                            <td colspan="4">
                                                                                                                <span class="inlineBlock">Campa&ntilde;a  <select class="combo" id="cbCampania_reporte" name="campania" onchange="load_reporte_cartera_tb_rpte_rank(this.value,'tbRKCartera_divReportes');des_checked('sel_all_av');load_provincia_by_id(this.value,'cbProvincia_reporte');"><option value="0">--Seleccione--</option></select></span>
                                                                                                                <span id="sProvinciaReporte" style="visibility: hidden;" class="inlineBlock">Provincia<select class="combo" id="cbProvincia_reporte"><option value="0">--Seleccione--</option></select></span>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr style="display:none;" id="trMontoReporte">
                                                                                                            <td colspan="4">
                                                                                                                <div>
                                                                                                                    <table>
                                                                                                                        <tr>
                                                                                                                            <td style="font-weight:bold;">Ingrese Monto</td>
                                                                                                                            <td>Desde</td>
                                                                                                                            <td><input id="txtMontoMenorReporte" type="text" class="cajaForm" style="width:50px;" /></td>
                                                                                                                            <td>Hasta</td>
                                                                                                                            <td><input id="txtMontoMayorReporte" type="text" class="cajaForm" style="width:50px;" /></td>
                                                                                                                        </tr>
                                                                                                                    </table>
                                                                                                                </div>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr style="display:none;" id="trTipoTransaccionReporte">
                                                                                                            <td colspan="4">
                                                                                                                <table>
                                                                                                                    <tr>
                                                                                                                        <td>Tipo Transaccion</td>
                                                                                                                        <td>
                                                                                                                            <select class="combo" id="cbTipoTransacccionReporte">
                                                                                                                                <option value="distribucion">DISTRIBUCION</option>
                                                                                                                                <option value="gestion">GESTION</option>
                                                                                                                            </select>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </table>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr id="trFiltroFechaReporte" style="display: none;">
                                                                                                            <td colspan="4">
                                                                                                                <table>
                                                                                                                    <tbody><tr>
                                                                                                                        <td>Año</td>
                                                                                                                        <td><select class="combo" id="cbAnioReporte"><option value="2009">2009</option><option value="2010">2010</option><option value="2011" selected="selected">2011</option><option value="2012">2012</option></select></td>
                                                                                                                        <td>Mes</td>
                                                                                                                        <td><select class="combo" id="cbMesReporte"><option value="1">Enero</option><option value="2">Febrero</option><option value="3">Marzo</option><option value="4">Abril</option><option value="5">Mayo</option><option value="6">Junio</option><option value="7" selected="selected">Julio</option><option value="8">Agosto</option><option value="9">Setiembre</option><option value="10">Octubre</option><option value="11">Noviembre</option><option value="12">Diciembre</option></select></td>
                                                                                                                        <td>Inicio</td>
                                                                                                                        <td><select class="combo" id="cbDiaIReporte"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5" selected="selected">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select></td>
                                                                                                                        <td>Fin</td>
                                                                                                                        <td><select class="combo" id="cbDiaFReporte"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5" selected="selected">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select></td>
                                                                                                                    </tr>
                                                                                                                </tbody></table>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr id="trImportPCampania" style="display: none;">
                                                                                                            <td colspan="4">
                                                                                                                <table>
                                                                                                                    <tr>
                                                                                                                        <td><input type="file" name="fileImportPCampania" id="fileImportPCampania"  /></td>
                                                                                                                        <td><button onclick="import_pcampania()">Importar</button></td>
                                                                                                                    </tr>
                                                                                                                </table>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr align="center" id="trTablaCarteras">
                                                                                                            <td colspan="4">
                                                                                                                <table>
                                                                                                                    <tr>
                                                                                                                        <td>
                                                                                                                            <div id="LayerTableCarterasAsignar_avance">
                                                                                                                                <div class="ui-widget-header ui-corner-top" style="padding:2px 0;width:700px;">
                                                                                                                                    <table width="700" cellpadding="0">
                                                                                                                                        <tr>
                                                                                                                                            <td>Carteras</td>
                                                                                                                                            <td align="right"><input id="sel_all_av" name="sel_all_av" type="checkbox" onClick="checked_all(this.checked,'tbRKCartera_divReportes')" />&nbsp;</td>
                                                                                                                                        </tr>
                                                                                                                                    </table>
                                                                                                                                </div>
                                                                                                                                <div style="overflow:auto;width:700px;">
                                                                                                                                    <div id="DataLayerTableCarterasAsignar_avance">
                                                                                                                                        <table id="tbRKCartera_divReportes" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                                <div class="ui-widget-header ui-corner-bottom" style="width:700px;">
                                                                                                                                    <table>
                                                                                                                                        <tr>
                                                                                                                                            <td>Buscar:</td>
                                                                                                                                            <td>
                                                                                                                                                <input onkeyup="search_text_table(this.value,'tbRKCartera_divReportes')" type="text" class="cajaForm" />
                                                                                                                                            </td>
                                                                                                                                            <td>Lugar:</td>
                                                                                                                                            <td>
                                                                                                                                                <select id="slctlugar" onchange="searchLugar(this.value)">
                                                                                                                                                    <option value="">--Seleccione--</option>
                                                                                                                                                    <option value="0">LIMA</option>
                                                                                                                                                    <option value="1">PROVINCIA</option>
                                                                                                                                                </select>
                                                                                                                                            </td>
                                                                                                                                        </tr>
                                                                                                                                    </table>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </table>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td><input id="tiporpt_avance_gestion" name="tipo_rpte" readonly="readonly" type="hidden" value="rpt_avcance_gestion"/></td>
                                                                                                        </tr>
                                                                                                        <tr id="trTipoLLamada" style="display: none;">
                                                                                                            <td colspan="4">
                                                                                                                <span>Todo <input checked="checked" type="radio" value="todo" name="rdTipoLlamada" ></span>
                                                                                                                <span>Mejor <input type="radio" value="mejor" name="rdTipoLlamada" ></span>
                                                                                                                <span>Ultimo <input type="radio" value="ultimo" name="rdTipoLlamada" ></span>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr id="trTipoTelefono" style="display: none;">
                                                                                                            <td colspan="4">
                                                                                                                <span>Nuevos <input checked="checked" type="radio" value="nuevo" name="rdTipoTelefono" ></span>
                                                                                                                <span>Todo <input type="radio" value="todo" name="rdTipoTelefono" ></span>
                                                                                                                <span>Celulares <input type="radio" value="celular" name="rdTipoTelefono" ></span>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr id="trFecha" style="display: none;">
                                                                                                            <td colspan="4">
                                                                                                                <span>Inicio <input id="txtFechaInicioReporte" readonly="readonly"  type="text" style="width:100px;" class="cajaForm "></span>
                                                                                                                <span>Fin <input id="txtFechaFinReporte"  readonly="readonly" type="text" style="width:100px;" class="cajaForm "></span>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <!--	VIC INI		-->
                                                                                                        <tr id="trFechaBBVA" style="display: none;">
                                                                                                            <td colspan="4">
																												<span>Llamadas</span>
																												<br/>
																												<span>Inicio: <input id="txtFechaInicioRptBBVA" readonly="readonly"  type="text" style="width:100px;" class="cajaForm "></span>
																												<span>Fin: <input id="txtFechaFinRptBBVA"  readonly="readonly" type="text" style="width:100px;" class="cajaForm "></span>
																												<span>Fecha de Proceso: <input id="txtFechaProcesoRptBBVA"  readonly="readonly" type="text" style="width:100px;" class="cajaForm "></span>
																												<br/>
																												<span>Visitas</span>
																												<br/>
																												<span>Inicio: <input id="txtFechaInicioVisitaRptBBVA" readonly="readonly"  type="text" style="width:100px;" class="cajaForm "></span>
																												<span>Fin: <input id="txtFechaFinVisitaRptBBVA"  readonly="readonly" type="text" style="width:100px;" class="cajaForm "></span>
                                                                                                            </td>
                                                                                                        </tr>
																										<tr id="trClienteNuevoRetirado" style="display: none;">
																											<td colspan="4">
                                                                                                                <input type="button" value="Fecha Proceso" class="ui-state-default ui-corner-all ui-button ui-widget ui-button-text-only" onclick="listarfproceso2()">                                                                                                               
                                                                                                                <br/>
																												<span>Inicio:</span>
																												<select name="sltCliNewRetIni" id="sltCliNewRetIni" class='combo'></select>
																												<span>Fin:</span>
																												<select name="sltCliNewRetFin" id="sltCliNewRetFin" class='combo'></select>
																												<br/>
																												&nbsp;
																												<br/>
																												<span>Agencia:</span>
																												<select name="sltCliNewRetAgencia" id="sltCliNewRetAgencia" class='combo'></select>&nbsp;
																												<span>Detalle Agencia:</span>
																												<select name="sltCliNewRetDetalleAgencia" id="sltCliNewRetDetalleAgencia" class='combo'>
																													<option value='TODO'>Todo</option>
																													<option value='COMERCIAL'>Comercial</option>
																													<option value='NATURAL'>Natural</option>
																												</select>
																											</td>
																										</tr>
																										<tr id="trInformeCarterizacion" style="display: none;">
																											<td colspan="4">
                                                                                                                <input type="button" value="Fecha Proceso" class="ui-state-default ui-corner-all ui-button ui-widget ui-button-text-only" onclick="listarfproceso2()">                                                                                                                                                                                                                              
                                                                                                                <br/>
																											<span>Inicio: 
																												<input id="txtFechaInfoCarteraIni" readonly="readonly"  type="text" style="width:100px;" class="cajaForm "></span>
																											<span>Fin: 
																												<input id="txtFechaInfoCarteraFin" readonly="readonly" type="text" style="width:100px;" class="cajaForm "></span>
																											<br/>
																											<span>Proceso:</span>
																											<select name="sltInfoCartera" id="sltInfoCartera" class='combo'></select>
																											<span>Agencia:</span>
																											<select name="sltInformeCarteraAgencia" id="sltInformeCarteraAgencia" class='combo'></select>
																											</td>
																										</tr>
																										<tr id="trReporteCobertura" style="display: none;">
																											<td colspan="4">
                                                                                                            <input type="button" value="Fecha Proceso" class="ui-state-default ui-corner-all ui-button ui-widget ui-button-text-only" onclick="listarfproceso2()">                                                                                                                                                                                                                               
                                                                                                            <br/>
																											<span>Fecha de Proceso: 
																												<select name="sltProcesoCober" id="sltProcesoCober" class='combo'></select>
																											</span>
																											<span>Tipo de Cambio:</span>
																											<span>
																												<input id="txtDolarCober" type="text" style="width:50px;" value='2.8' class="cajaForm ">
																											</span>
																											<span>Tipo VAC:</span>
																											<span>
																												<input id="txtVacCober" type="text" style="width:50px;" value='7' class="cajaForm ">
																											</span>
																											</td>
																										</tr>
                                                                                                        <!--	VIC FIN		-->
                                                                                                        <tr id="trFechaUnica" style="display: none;">
                                                                                                            <td colspan="4">
                                                                                                                <span>Fecha <input id="txtFechaUnicaReporte" readonly="readonly"  type="text" style="width:100px;" class="cajaForm "></span>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <td colspan="4">
                                                                                                                <div style="margin-top:5px;margin-bottom:5px;display: none;" id="divEstadoTransferenciaInsatisfaccion">
                                                                                                                    <div style="width:380px;" class="ui-widget-header ui-corner-top">Estados<span style="margin-left:300px"><input type="checkbox" id="estadosMarcacion" onclick="checked_all(this.checked,'layerContent_estado_transferencia_insatisfaccion')"></span></div>
                                                                                                                    <div class="ui-state-active" align="left" style="overflow:auto;height:100px;width:380px;" id="layerContent_estado_transferencia_insatisfaccion"></div>
                                                                                                                    <div style="width:380px;" class="ui-state-active ui-corner-bottom" align="left">
                                                                                                                        <table>
                                                                                                                            <tr>
                                                                                                                                <td>Buscar:</td>
                                                                                                                                <td>
                                                                                                                                    <input onkeyup="search_text_table(this.value,'layerContent_estado_transferencia_insatisfaccion')" type="text" class="cajaForm" />
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                        </table>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr id="divTipoCambio" style="display:none">
                                                                                                            <td colspan="4">
                                                                                                                <table>
                                                                                                                    <tr>
                                                                                                                        <td>Tipo de cambio</td>
                                                                                                                        <td>
                                                                                                                            <input type="text" id="txttipocambio" value="2.80" size="10">
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td>Tipo VAC</td>
                                                                                                                        <td>
                                                                                                                            <input type="text" id="txttipovac" value="7" size="10">
                                                                                                                        </td>
                                                                                                                    </tr>          
                                                                                                                </table>   
                                                                                                            </td>     
                                                                                                        </tr>                                                                                      
                                                                                                        <tr id="divDistritosporCartera" style="display:none">
                                                                                                            <td colspan="4">
                                                                                                                <table>
                                                                                                                    <tr>
                                                                                                                        <td>Gestor Campo</td>
                                                                                                                        <td>
                                                                                                                            <select id="listGestorCampo" class="combo"></select>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td colspan="2">
                                                                                                                            <input onclick="listarDistritoCartera()" type="button" class="ui-state-default ui-corner-all ui-button ui-widget ui-button-text-only" value="Buscar Distritos">
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td colspan="2">
                                                                                                                            <div style="width:300px;" class="ui-widget-header ui-corner-top">Distritos<span style="margin-left:230px"><input type="checkbox" id="tododistrito" onclick="checked_all(this.checked,'listarDistrito')"></span></div>
                                                                                                                            <div class="ui-state-active" align="left" style="overflow:auto;height:100px;width:300px;" id="listarDistrito"></div>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </table>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr id="divFProceso" style="display:none">
                                                                                                            <td colspan="4">
                                                                                                                <table>
                                                                                                                    <tr>
                                                                                                                        <td colspan="2">
                                                                                                                            <input onclick="listarfproceso()" type="button" class="ui-state-default ui-corner-all ui-button ui-widget ui-button-text-only" value="Fecha Proceso">                                                                                                                            
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td colspan="2">
                                                                                                                            <div style="width:300px;" class="ui-widget-header ui-corner-top">Fecha Proceso<span style="margin-left:200px"><input type="checkbox" id="todofproceso" onclick="checked_all(this.checked,'listarFproceso')"></span></div>
                                                                                                                            <div class="ui-state-active" align="left" style="overflow:auto;height:100px;width:300px;" id="listarFproceso"></div>
                                                                                                                        </td>
                                                                                                                    </tr>

                                                                                                                </table>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr id="divBotonDetalle" style="display:none">
                                                                                                            <td colspan="4">
                                                                                                                <table>
                                                                                                                    <tr>
                                                                                                                        <td colspan="2">
                                                                                                                            <input onclick="reportedetalle()" type="button" class="ui-state-default ui-corner-all ui-button ui-widget ui-button-text-only" value="BD-DETALLE">                                                                                                                            
                                                                                                                        </td>                                                                                                                        
                                                                                                                    </tr>
                                                                                                                </table>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr id="divFProcesoMultiple" style="display:none">
                                                                                                            <td colspan="4">
                                                                                                                <table>
                                                                                                                    <tr>
                                                                                                                        <td colspan="2">
                                                                                                                            <input onclick="listarfprocesomultiple()" type="button" class="ui-state-default ui-corner-all ui-button ui-widget ui-button-text-only" value="Fecha Proceso">                                                                                                                            
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td colspan="2">
                                                                                                                            <div style="width:300px;" class="ui-widget-header ui-corner-top">Fecha Proceso<span style="margin-left:200px"><input type="checkbox" id="todofprocesomultiple" onclick="checked_all(this.checked,'listarFprocesoMultiple')"></span></div>
                                                                                                                            <div class="ui-state-active" align="left" style="overflow:auto;height:100px;width:300px;" id="listarFprocesoMultiple"></div>
                                                                                                                        </td>
                                                                                                                    </tr>

                                                                                                                </table>
                                                                                                            </td>
                                                                                                        </tr>                                                                                                        
                                                                                                        <tr id="divTerritorio" style="display:none">
                                                                                                            <td colspan="4">
                                                                                                                <table>
                                                                                                                    <tr>
                                                                                                                        <td colspan="2">
                                                                                                                            <input onclick="listarterritorio()" type="button" class="ui-state-default ui-corner-all ui-button ui-widget ui-button-text-only" value="Territorio">                                                                                                                            
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td colspan="2">
                                                                                                                            <div style="width:300px;" class="ui-widget-header ui-corner-top">Territorio<span style="margin-left:200px"><input type="checkbox" id="todoterritorio" onclick="checked_all(this.checked,'listarterritorio')"></span></div>
                                                                                                                            <div class="ui-state-active" align="left" style="overflow:auto;height:100px;width:300px;" id="listarterritorio"></div>
                                                                                                                        </td>
                                                                                                                    </tr>

                                                                                                                </table>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr id="trSinGestion" style="display: none;">
                                                                                                            <td colspan="3">
                                                                                                                Sin Gestion <input type="checkbox" id="flgSinGestion">
                                                                                                            </td>
                                                                                                            <td>
                                                                                                                CDO <input type="checkbox" id="flgCDO">
                                                                                                            </td>                                                                                                            
                                                                                                        </tr>
                                                                                                        <tr id="trProvTotal" style="display:none;padding:12px;">
                                                                                                            
                                                                                                            <td colspan="3" style="margin-left:4px">
                                                                                                               <label>Tipo Cambio:  <input type="text" id="txtTipoCambioProvtot" value="2.80"></label> 
                                                                                                            </td>
                                                                                                            <td colspan="3" style="margin-left:4px">
                                                                                                               <label>VAC:  <input type="text" id="txtVacProvtot" value="7"></label> 
                                                                                                            </td>
                                                                                                        </tr>

                                                                                                        <!-- <tr id="trCobertura_Diaria_Opcion" style="display:none;">
                                                                                                            <td>Fecha proceso:</td>
                                                                                                            <td>
                                                                                                                <input type="text" id="cartera_mes_opcion"></select>
                                                                                                            </td>
                                                                                                        </tr> -->

                                                                                                        <tr>
                                                                                                            <td colspan="4" align="left"><button type="button" onclick="link_valida_reporte(this)" class="ui-state-default ui-corner-all"><img src="../img/page_excel.png" /><span>Exportar</span></button></td>		
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <td>
                                                                                                                <div class="ui-helper-reset ui-widget-header ui-corner-bottom " style="opacity:0;transition:opacity 0.5s ease;padding: 4px; text-align: center;" id="msgProceso"></div>                                                                                                    
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </table>
                                                                                                    </form>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr> <!-- /jc -->
                                                                                </table>
                                                                            </td>            
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </td>

                                                    </tr>
                                                </table>
                                                <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                    <tr>
                                                        <td class="lineTab ui-widget-header"></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center">
                                                            <div style="margin-left:50px;">
                                                                <table id="table_tab_reportes_home" cellpadding="0" cellspacing="0" border="0">
                                                                    <tr>
                                                                        <td><div onClick="_activeTabLayer('table_tab_reportes_home','tab_reportes_home_bottom_',this,'content_reportes_bottom','layer_Form_reportes_','layer_Form_reportes_una_cartera')" id="tab_reportes_home_bottom_una" class="itemTabActive border-radius-bottom pointer ui-widget-header" style="margin: 0px 1px 0pt 0pt;" ><div>Una Cartera</div></div></td>
                                                                        <td><div onClick="_activeTabLayer('table_tab_reportes_home','tab_reportes_home_bottom_',this,'content_reportes_bottom','layer_Form_reportes_','layer_Form_reportes_varias_carteras')" id="tab_reportes_home_bottom_varias" class="itemTabActive border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;" ><div>Multiples Carteras</div></div></td>
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
                            <div id="panelFinancieroReporte" align="center" style="display:none;">
                                <table>
                                    <tr>
                                        <td>

                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </td>
                    <td class="showHide ui-widget-header">
                        <div style="width:10px;"></div>
                    </td>
                </tr>
            </table>
            <div style="width:100%; height:20px; border:0 none;" class="ui-widget-header"></div>
        </div>
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

    </body>
</html>


