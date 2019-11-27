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
        <title>Distribucion</title>
        <!--<link type="text/css" rel="stylesheet" href="../includes/jqgrid-3.6.5/css/ui.jqgrid.css" />-->
        <link type="text/css" rel="stylesheet" media="screen" href="../includes/jqgrid-3.8.2/css/ui.jqgrid.css" />
        <link type="text/css" rel="stylesheet" href="../includes/jquery-ui-1.8/themes/humanity/jquery-ui.css" />
        <link type="text/css" rel="stylesheet" href="../includes/font-awesome-4.3.0/css/font-awesome.css" />

        <link type="text/css" rel="stylesheet" href="../css/css-cobrast.css" />

        <script type="text/javascript" src="../js/includes/jquery-1.4.2.js" ></script>
        <script type="text/javascript" src="../js/includes/jquery.upload-1.0.2.js" ></script>

        <script type="text/javascript" src="../includes/jqgrid-3.8.2/js/i18n/grid.locale-es.js" ></script>
        <script type="text/javascript" src="../includes/jqgrid-3.8.2/js/jquery.jqGrid.min.js" ></script>


<!--<script type="text/javascript" src="../includes/jqgrid-3.6.5/src/i18n/grid.locale-en.js" ></script>
<script type="text/javascript" src="../includes/jqgrid-3.6.5/src/jqModal.js" ></script>
<script type="text/javascript" src="../includes/jqgrid-3.6.5/src/grid.base.js" ></script>
<script type="text/javascript" src="../includes/jqgrid-3.6.5/src/grid.celledit.js" ></script>
<script type="text/javascript" src="../includes/jqgrid-3.6.5/src/grid.common.js" ></script>
<script type="text/javascript" src="../includes/jqgrid-3.6.5/src/grid.custom.js" ></script>
<script type="text/javascript" src="../includes/jqgrid-3.6.5/src/grid.formedit.js" ></script>
<script type="text/javascript" src="../includes/jqgrid-3.6.5/src/grid.import.js" ></script>
<script type="text/javascript" src="../includes/jqgrid-3.6.5/src/grid.inlinedit.js" ></script>-->

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
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.effects.pulsate.min.js" ></script>

        <script type="text/javascript" src="../js/js-cobrast.js" ></script>
        <script type="text/javascript" src="../js/templates.js" ></script>
        <script type="text/javascript" src="../js/validacion.js" ></script>
        <script type="text/javascript" src="../js/DistribucionJQGRID.js" ></script>
        <script type="text/javascript" src="../js/DistribucionDAO.js" ></script>
        <script type="text/javascript" src="../js/js-distribucion.js" ></script>
        <style type="text/css">
            #table_retirar_clientes .ui-selected { background:url("../includes/jquery-ui-1.8/themes/excite-bike/images/ui-bg_highlight-soft_100_f9f9f9_1x100.png") repeat-x scroll 50% 50% #F9F9F9;border:1px solid #CCCCCC;color:#E69700;font-weight:bold;}
            #table_retirar_clientes .ui-selectee {}
            body {
                 background: #F4F0EC url(../img/bg_.jpg)
            }
        </style>
    </head>
    <body>
        <div class="divContentMain">
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
                    <td class="vAlignBottom tabsLine">
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
                                <div class="backPanel iconPinUp" onclick="_slideBarLayer(this,'panelMenu')">Menu</div>
                            </div>
                            <div id="panelMenu" class="backPanel contentBarLayer" style="display:block;" >
                                <div style="width:100%;">
                                    <div style="margin-left:20px; margin-bottom:2px;"><a id="aDisplayPanelDistribucion" class="text-blue" onClick="_display_panel('panelDistribucion')">Distribucion</a></div>
                                    <div style="margin-left:20px; margin-bottom:2px;"><a id="aDisplayPanelClienteGestClienteSinGest" class="text-blue" onClick="_display_panel('panelClientesGestionadosSinGestionar')">Clientes Gest. y Sin Gest.</a></div>
                                    <div style="margin-left:20px; margin-bottom:2px;"><a id="aDisplayPanelRetirarClientes" class="text-blue" onClick="_display_panel('panelRetirarClientes')">Retirar Clientes</a></div>
                                    <div style="margin-left:20px; margin-bottom:2px;"><a id="aDisplayPanelTrasladoCartera" class="text-blue" onClick="_display_panel('panelTraspasoCartera')">Traslado de Cartera</a></div>
                                    <div style="margin-left:20px; margin-bottom:2px;"><a id="aDisplayPanelRedistribucion" class="text-blue" onClick="_display_panel('panelRedistribucion')">Redistribuci&oacute;n</a></div>
                                    <div style="margin-left:20px; margin-bottom:2px;"><a id="aDisplayPanelRegistrarZona" class="text-blue" onClick="_display_panel('panelRegistrarZona')">Registrar Zona</a></div>
                                </div>
                            </div>
                            <div class=" backPanel headerPanel">
                                <div class="backPanel iconPinUp" onclick="_slideBarLayer(this,'panelCrear')">Crear</div>
                            </div>
                            <div id="panelCrear" class="backPanel contentBarLayer" style="display:block;" align="center" >
                                <table>
                                    <tr>
                                        <td>
                                            <select onchange="_showCrear(this.value)" >
                                                <option value="crear">Crear...</option>
                                                <option value="campania">Campaña</option>
                                            </select>
                                        </td>
                                    </tr>
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
                            <div id="iconSlider" class="slider icon sliderIconDown"></div>
                        </a>
                    </td>
                    <td width="100%" valign="top">	
                        <div id="cobrastHOME" style="background-color:#FFFFFF; width:100% !important; height:100%; ">
                            <input type="hidden" id="hdCodUsuario" name="hdCodUsuario" value="<?= $_SESSION['cobrast']['idusuario'] ?>" />
                            <input type="hidden" id="hdCodServicio" name="hdCodServicio" value="<?= $_SESSION['cobrast']['idservicio'] ?>" />
                            <input type="hidden" id="hdCodUsuarioServicio" name="hdCodUsuarioServicio" value="<?= $_SESSION['cobrast']['idusuario_servicio'] ?>" />
                            <input type="hidden" id="hdNomServicio" name="hdNomServicio" value="<?= $_SESSION['cobrast']['servicio'] ?>" />
                            <div>
                                <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                    <tr bgcolor="#daedff" align="center">
                                        <td>
                                            <table border="0" class="ui-state-highlight ui-corner-all"> 
                                                <tr>
                                                    <td width="125" align="center">
                                                        <label class="text-blue">Estado de Carteras</label>
                                                    </td>
                                                    <td width="200" align="center">
                                                        <div class="ui-datepicker-header ui-widget-header ui-helper-clearfix ui-corner-all" align="center">
                                                            <table id="tbEstadoCarteraDistribucion" border="0">
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
                                </table>
                            </div>
                            <div id="panelDistribucion" style="display:block;" align="center">
                                <div>
                                    <table cellpadding="0" cellspacing="0" border="0" style="width:99%;">
                                        <tr>
                                            <td id="content_distribucion_bottom" >
                                                <div id="panel_Form_distribucion_automatica" class="ui-widget-content" style="display:block; padding:5px 0; width:99.5%;" align="center">
                                                    <table align="center" class="tableForm" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td>
                                                                <div align="center">
                                                                    <table>
                                                                        <tr>
                                                                            <td><button class="btn" onclick="generar_distribucion_automatica()">Generar</button></td>
                                                                            <td><button class="btn" onclick="cancel_distribucion_automatica()">Cancelar</button></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <table align="center" class="tableForm BoxContent" cellpadding="0" cellspacing="0">
                                                                        <tr>
                                                                            <!--<td class="rowBoxContent textForm">Usuario Creacion:</td>
                                                                            <td class="rowBoxContent"><input type="text" id="txtUsuarioCreacion" readonly="readonly" class="cajaForm" value="<?= $_SESSION['cobrast']['usuario'] ?>" /></td>-->
                                                                            <td class="rowBoxContent textForm">Campa&ntilde;a:</td>
                                                                            <td class="rowBoxContent">
                                                                                <!--<select id="cbCampaniaDistribucionAutomatica" class="combo" onchange="cargar_cartera_distribucion_automatica(this.value)">
                                                                                    <option value="0">--Seleccione--</option>
                                                                                </select>-->
                                                                                <select class="combo" id="cbCampaniaDistribucionAutomatica" onchange="load_cartera_by_id_rpte_rank(this.value,'cbCarteraDistribucionAutomatica')">
                                                                                    <option value="0">--Seleccione--</option>
                                                                                </select>
                                                                            </td>
                                                                            <td class="rowBoxContent textForm">Cartera:</td>
                                                                            <td class="rowBoxContent">
                                                                                <select id="cbCarteraDistribucionAutomatica" class="combo" onchange="cargar_data_distribucion_automatica(this.value)">
                                                                                    <option value="0">--Seleccione--</option>
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>    
                                                                            <td class="rowBoxContent textForm">Clientes sin asignar:</td>
                                                                            <td class="rowBoxContent"><input type="text" class="cajaForm" readonly="readonly" id="txtClientesSinAsignar" /></td>
                                                                            <td class="rowBoxContent textForm">Clientes Asignados:</td>
                                                                            <td class="rowBoxContent"><input type="text" class="cajaForm" readonly="readonly" id="txtClientesAsignados" /></td>
                                                                        </tr>
                                                                        <tr>    
                                                                            <td class="rowBoxContent textForm">Cantidad de Operadores:</td>
                                                                            <td class="rowBoxContent"><input type="text" class="cajaForm" readonly="readonly" id="txtCantidadOperadores" /></td>
                                                                            <td class="rowLastBoxContent textForm">Clientes X Operador:</td>
                                                                            <td class="rowLastBoxContent"><input type="text" class="cajaForm" readonly="readonly" id="txtClientesXOperador" /></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div align="center">
                                                                    <table>
                                                                        <tr>
                                                                            <td><button class="btn" onclick="generar_distribucion_automatica()">Generar</button></td>
                                                                            <td><button class="btn" onclick="cancel_distribucion_automatica()" >Cancelar</button></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div id="panel_Form_distribucion_manual" class="ui-widget-content" style="display:none; padding:10px 0;width:99.5%;" align="center" >
                                                    <table cellpadding="0" cellspacing="0" border="0" >
                                                        <tr>
                                                            <td align="left">
                                                                <div>
                                                                    <table>
                                                                        <tr>
                                                                            <td align="right">Campa&ntilde;a</td>
                                                                            <td><select class="combo" id="cbCampaniaDistribucionManual" onchange="load_cartera_by_id_rpte_rank(this.value,'cbCarteraDistribucionManual')"><option value="0">--Seleccione--</option></select></td>
                                                                            <td align="right">Cartera</td>
                                                                            <td><select class="combo" id="cbCarteraDistribucionManual" onchange="cargar_data_distribucion_manual(this.value,'cbCarteraDistribucionManual')" ><option value="0">--Seleccione--</option></select></td>
                                                                            <td><button class="ui-state-default ui-corner-all" onclick="window.location.href='../rpt/excel/distribucion_manual.php?servicio='+$('#hdCodServicio').val()+'&cartera='+$('#cbCarteraDistribucionManual').val()" ><span class="ui-icon ui-icon-extlink"></span></button></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <table cellpadding="0" cellspacing="0" border="0" style="width:785px;">
                                                                        <tr>
                                                                            <td valign="top">
                                                                                <div id="layerManualOperadores" style="width:440px;">
                                                                                    <div class="ui-widget-content ui-corner-all" style="padding:3px 5px;width:430px;margin:0 4px;" >
                                                                                        <table cellpadding="0" cellspacing="0" border="0" style="width:430px;">
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <div class="headerPanel2 ui-corner-all" style="margin-bottom:3px;" align="center">Operadores</div>
                                                                                                    <div>
                                                                                                        <table>
                                                                                                            <tr>
                                                                                                                <td align="center"><div class="ui-widget-header ui-corner-all" style="padding:2px 1px;margin:1px;width:210px;">Operador</div></td>
                                                                                                                <td align="center"><div class="ui-widget-header ui-corner-all" style="padding:2px 1px;margin:1px;width:60px;">Asignados</div></td>
                                                                                                                <td align="center"><div class="ui-widget-header ui-corner-all" style="padding:4px 1px;margin:1px;font-size:8px;width:40px;">Sin Gest.</div></td>
                                                                                                                <td align="center"><div class="ui-widget-header ui-corner-all" style="padding:2px 1px;margin:1px;width:60px;">Gestionados</div></td>
                                                                                                            </tr>
                                                                                                        </table>
                                                                                                    </div>
                                                                                                    <div align="center" style="overflow:auto;height:200px;">
                                                                                                        <table id="table_operador_distribucion_manual"></table>
                                                                                                    </div>
                                                                                                    <div>
                                                                                                        <table>
                                                                                                            <tr>
                                                                                                                <td>Buscar:</td>
                                                                                                                <td><input type="text" class="cajaForm" onkeyup="search_operadores_distribucion(this.value,'table_operador_distribucion_manual')" /></td>	
                                                                                                                <td>Filtro Cluster:</td>
                                                                                                                <td><select id="FiltroClusterManual" class="comboAdd" onchange="cargar_data_distribucion_manual_cluster('cbCarteraDistribucionManual',this.value)"></select></td>
                                                                                                            </tr>
                                                                                                        </table>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </table>
                                                                                    </div>
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td align="right">Clientes Sin Asignar</td>
                                                                                            <td><input type="text" class="cajaForm" id="txtClienteSinAsignarManual" style="width:50px;" readonly="readonly" /></td>
                                                                                            <td><input type="hidden" id="hdClienteSinAsignar" /></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                            </td>
                                                                            <td style="width:10px;"></td>
                                                                            <td valign="top">
                                                                                <div id="layerManualAsignacion" style="width:310px;">
                                                                                    <div class="ui-corner-all ui-widget-content" style="padding:3px 5px;width:300px;" >
                                                                                        <table border="0" cellpadding="0" cellspacing="0" style="width:300px;">
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <div class="headerPanel2 ui-corner-all" style="margin-bottom:3px;" align="center">Asignacion</div>
                                                                                                    <div>
                                                                                                        <table id="table_asignacion">
                                                                                                            <tr id="placeHolder">
                                                                                                                <td>Arrastre operadores aqui..</td>
                                                                                                            </tr>
                                                                                                        </table>
                                                                                                    </div>
                                                                                                    <div>
                                                                                                        <div class="ui-state-default ui-corner-all" style="padding:4px;width:16px;" onclick="grabar_distribucion_manual()">
                                                                                                            <span class="ui-icon ui-icon-disk"></span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </td>
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
                                                </div>
                                                <div id="panel_Form_distribucion_por_operador" class="ui-widget-content" style="display:none; padding:10px 0;width:99.5%;" align="center" >
                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <table>
                                                                        <tr>
                                                                            <td align="left">Operador</td>
                                                                            <td align="left"><select id="cbOperadoresDistribucionPorOperador" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                            <td align="left">Campaña</td>
                                                                            <td align="left"><select id="cbCampaniaDistribucionPorOperador" onchange="load_cartera_by_id_rpte_rank(this.value,'cbCarteraDistribucionPorOperador')" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                            <td align="left">Cartera</td>
                                                                            <td align="left"><select id="cbCarteraDistribucionPorOperador" onchange="reload_jqgrid_clientes_por_cartera(this.value)" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top">
                                                                <div>
                                                                    <table>
                                                                        <tr>
                                                                            <td valign="top">
                                                                                <div align="center">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <div>
                                                                                                    <table id="table_asignacion_por_operador"></table>
                                                                                                    <div id="pager_table_asignacion_por_operador"></div>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                            </td>
                                                                            <td style="display:20px;"></td>
                                                                            <td valign="top">
                                                                                <table cellspacing="0" cellpadding="0" border="0" >
                                                                                    <tr class="ui-state-default">
                                                                                        <td style="width:25px;padding:3px 0;border:1px solid #E0CFC2;" align="center"></td>
                                                                                        <td style="width:60px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" align="center">Codigo</td>
                                                                                        <td style="width:250px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" align="center">Cliente</td>
                                                                                        <td style="width:25px;padding:3px 0;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" align="center"></td>
                                                                                    </tr>
                                                                                </table>
                                                                                <div style="width:382px;height:200px;overflow-y:auto;">
                                                                                    <table id="tableDataClientesDistribucionPorOperador" cellspacing="0" cellpadding="0" border="0"></table>
                                                                                </div>
                                                                                <table>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <button onclick="save_distribucion_por_operador()" class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all"><span class="ui-button-text">Guardar</span></button>
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
                                                <div id="panel_Form_distribucion_por_departamento" class="ui-widget-content" style="display:none; padding:10px 0;width:99.5%;" align="center" >
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <table>
                                                                        <tr>
                                                                            <td>Campa&ntilde;a</td>
                                                                            <td><select id="cbCampaniaDistribucionPorDepartamento" onchange="load_cartera_by_id_rpte_rank(this.value,'cbCarteraDistribucionPorDepartamento')"><option value="0">--Seleccione--</option></select></td>
                                                                            <td>Cartera</td>
                                                                            <td><select onchange="cargar_departamento_distribucion_por_departamento(this.value)" id="cbCarteraDistribucionPorDepartamento"><option value="0">--Seleccione--</option></select></td>
                                                                            <td>Departamentos</td>
                                                                            <td><select onchange="cantidad_de_clientes_por_departamento(this.value)" id="cbDepartamento"><option value="0">--Seleccione--</option></select></td>
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
                                                                            <td class="ui-state-default" style="padding:3px 5px;">Cantidad de Clientes Sin Asignar</td>
                                                                            <td class="ui-widget-content" style="width:80px;" align="center"><label id="lbCantidadClientesDisponibleDistribucionDepartamento"></label></td>
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
                                                                            <td>
                                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                                    <tr class="ui-state-default">
                                                                                        <td style="width:30px;padding:3px 0;" class="ui-state-default ui-corner-tl" align="center">&nbsp;</td>
                                                                                        <td style="width:70px;padding:3px 0;" class="ui-state-default" align="center">Id</td>
                                                                                        <td style="width:300px;padding:3px 0;" class="ui-state-default" align="center">Operador</td>
                                                                                        <td style="width:60px;padding:3px 0;" class="ui-state-default" align="center">Asignados</td>
                                                                                        <td style="width:70px;padding:3px 0;" class="ui-state-default" align="center">Gestionados</td>
                                                                                        <td style="width:30px;padding:3px 0;" class="ui-state-default" align="center">&nbsp;</td>
                                                                                        <td style="width:18px;padding:3px 0;" class="ui-state-default ui-corner-tr" align="center">&nbsp;</td>
                                                                                    </tr>
                                                                                </table>
                                                                                <div style="overflow:auto;height:200px;">
                                                                                    <table id="tableOperadoresDistribucionPorDepartamento" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                </div>
                                                                                <div style="" class="ui-corner-bottom ui-state-default">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td>Buscar</td>
                                                                                            <td><input onkeyup="search_operadores_distribucion(this.value,'tableOperadoresDistribucionPorDepartamento')" id="txtSearchOperadoresDistribucionPorDepartamento" type="text" /></td>
                                                                                            <td>Filtro Cluster:</td>
                                                                                            <td><select id="FiltroClusterDepartamento" class="combo" onchange="cargar_departamento_distribucion_por_departamento_cluster('cbCarteraDistribucionPorDepartamento',this.value)"></select></td>
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
                                                                            <td><button onclick="save_distribucion_por_departamento()" class="ui-state-default ui-corner-all"><span>Guardar</span></button></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div id="panel_Form_distribucion_por_tramo" class="ui-widget-content" style="display:none; padding:10px 0;width:99.5%;" align="center" >
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <table>
                                                                        <tr>
                                                                            <td>Campa&ntilde;a</td>
                                                                            <td><select class="combo" id="cbCampaniaDistribucionPorTramo" onchange="load_cartera_by_id_rpte_rank(this.value,'cbCarteraDistribucionPorTramo')"><option value="0">--Seleccione--</option></select></td>
                                                                            <td>Cartera</td>
                                                                            <!--<td><select class="combo" onchange="cargar_tramo_distribucion_por_tramo(this.value)" id="cbCarteraDistribucionPorTramo"><option value="0">--Seleccione--</option></select></td>-->
                                                                            <td><select class="combo" onchange="cargar_tramo_distribucion_por_tramo_especial(this.value)" id="cbCarteraDistribucionPorTramo"><option value="0">--Seleccione--</option></select></td>
                                                                            <td>Tramos</td>
                                                                            <!--<td><select class="combo" onchange="cantidad_de_clientes_por_tramo(this.value)" id="cbTramo"><option value="0">--Seleccione--</option><option value="TRAMO_1">TRAMO 1</option><option value="TRAMO_2">TRAMO 2</option><option value="TRAMO_3">TRAMO 3</option></select></td>-->
                                                                            <td><select class="combo" onchange="cantidad_de_clientes_por_tramo_especial(this.value)" id="cbTramo"><option value="0">--Seleccione--</option><option value="1 AND 30">TRAMO 1</option><option value="31 AND 60">TRAMO 2</option><option value="61 AND 99999">TRAMO 3</option></select></td>
                                                                            <td>Modo</td>
                                                                            <td>
                                                                                <select id="cbModoDistribucionPorTramo" class="combo">
                                                                                    <option value="cartera">Cartera</option>
                                                                                    <option value="seguimiento">Seguimiento</option>
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
                                                                            <td class="ui-state-default" style="padding:3px 5px;">Cantidad de Clientes Sin Asignar</td>
                                                                            <td class="ui-widget-content" style="width:80px;" align="center"><label id="lbCantidadClientesDisponibleDistribucionTramo"></label></td>
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
                                                                            <td>
                                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                                    <tr class="ui-state-default">
                                                                                        <td style="width:30px;padding:3px 0;" class="ui-state-default ui-corner-tl" align="center">&nbsp;</td>
                                                                                        <td style="width:70px;padding:3px 0;" class="ui-state-default" align="center">Id</td>
                                                                                        <td style="width:300px;padding:3px 0;" class="ui-state-default" align="center">Operador</td>
                                                                                        <td style="width:60px;padding:3px 0;" class="ui-state-default" align="center">Asignados</td>
                                                                                        <td style="width:70px;padding:3px 0;" class="ui-state-default" align="center">Gestionados</td>
                                                                                        <td style="width:30px;padding:3px 0;" class="ui-state-default" align="center">&nbsp;</td>
                                                                                        <td style="width:18px;padding:3px 0;" class="ui-state-default ui-corner-tr" align="center">&nbsp;</td>
                                                                                    </tr>
                                                                                </table>
                                                                                <div style="overflow:auto;height:200px;">
                                                                                    <table id="tableOperadoresDistribucionPorTramos" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                </div>
                                                                                <div style="" class="ui-corner-bottom ui-state-default">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td>Buscar</td>
                                                                                            <td><input onkeyup="search_operadores_distribucion(this.value,'tableOperadoresDistribucionPorTramos')" id="txtSearchOperadoresDistribucionPorTramo" type="text" /></td>
                                                                                            <td>Filtro Cluster:</td>
                                                                                            <td><select id="FiltroClusterTramo" class="combo" onchange="cargar_tramo_distribucion_por_tramo_cluster('cbCarteraDistribucionPorTramo',this.value)"></select></td>
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
                                                                            <td><button class="ui-state-default ui-corner-all" onclick="save_distribucion_por_tramo()"><span>Guardar</span></button></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div id="panel_Form_distribucion_por_campos" class="ui-widget-content" style="display:none; padding:10px 0;width:99.5%;" align="center" >
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <table>
                                                                        <tr>
                                                                            <td>Campa&ntilde;a</td>
                                                                            <td><select id="cbCampaniaDistribucionCampos" onchange="load_cartera_by_id_rpte_rank(this.value,'cbCarteraDistribucionCampos')" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                            <td>Cartera</td>
                                                                            <td><select id="cbCarteraDistribucionCampos" onchange="cargar_teleoperadores_distribucion_por_campos(this.value)" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                        </tr>
                                                                    </table>
                                                                    <table>
                                                                        <tr>
                                                                            <td>Grupo</td>
                                                                            <td>
                                                                                <select id="cbGrupoDistribucionCampos" class="combo" onchange="cargar_data_campos(this.value)" >
                                                                                    <option value="0">--Seleccione--</option>
                                                                                    <option value="ca_cliente|cliente" label="ca_cliente">Cliente</option>
                                                                                    <option value="ca_cuenta|cuenta" label="ca_cuenta">Cuenta</option>
                                                                                    <option value="ca_detalle_cuenta|detalle_cuenta" label="ca_detalle_cuenta">Operacion</option>
                                                                                    <optgroup label="Adicionales">
                                                                                        <option value="ca_datos_adicionales_cliente|adicionales" label="ca_cliente_cartera">Cliente</option>
                                                                                        <option value="ca_datos_adicionales_cuenta|adicionales" label="ca_cuenta">Cuenta</option>
                                                                                        <option value="ca_datos_adicionales_detalle_cuenta|adicionales" label="ca_detalle_cuenta">Operacion</option>
                                                                                    </optgroup>
                                                                                    <optgroup label="Direccion">
                                                                                        <option value="direccion_predeterminado|direccion" label="ca_direccion">Predeterminado</option>
                                                                                        <option value="direccion_oficina|direccion" label="ca_direccion">Oficina</option>
                                                                                        <option value="direccion_domicilio|direccion" label="ca_direccion">Domicilio</option>
                                                                                        <option value="direccion_negocio|direccion" label="ca_direccion">Negocio</option>
                                                                                        <option value="direccion_laboral|direccion" label="ca_direccion">Laboral</option>
                                                                                    </optgroup>
                                                                                </select>
                                                                            </td>
                                                                            <td>Campo</td>
                                                                            <td>
                                                                                <select id="cbCamposDistribucionCampos" class="combo" onchange="carga_lista_data_campo()" >
                                                                                    <option value="0">--Seleccione--</option>
                                                                                </select>
                                                                            </td>
                                                                            <td>Datos</td>
                                                                            <td>
                                                                                <select id="cbDataCamposDistribucionCampos" class="combo" onchange="mostrar_cantidad_clientes_sin_gestionar()" >
                                                                                    <option value="0">--Seleccione--</option>
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
                                                                            <td class="ui-state-default" style="padding:3px 5px;">Cantidad de Clientes Sin Asignar</td>
                                                                            <td class="ui-widget-content" style="width:80px;" align="center"><label id="lbCantidadClientesDisponibleDistribucionCampo"></label></td>
                                                                            <td><input type="hidden" id="hdCodigoClienteDistribucionPorCampo" /></td>
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
                                                                            <td>
                                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                                    <tr class="ui-state-default">
                                                                                        <td style="width:30px;padding:3px 0;" class="ui-state-default ui-corner-tl" align="center">&nbsp;</td>
                                                                                        <td style="width:70px;padding:3px 0;" class="ui-state-default" align="center">Id</td>
                                                                                        <td style="width:300px;padding:3px 0;" class="ui-state-default" align="center">Operador</td>
                                                                                        <td style="width:60px;padding:3px 0;" class="ui-state-default" align="center">Asignados</td>
                                                                                        <td style="width:70px;padding:3px 0;" class="ui-state-default" align="center">Gestionados</td>
                                                                                        <td style="width:30px;padding:3px 0;" class="ui-state-default" align="center"><input type="checkbox" onclick="check_all_table(this.checked,'tableOperadoresDistribucionPorCampos')" /></td>
                                                                                        <td style="width:20px;padding:3px 0;" class="ui-state-default ui-corner-tr" align="center">&nbsp;</td>
                                                                                    </tr>
                                                                                </table>
                                                                                <div style="overflow:auto;height:200px;width:590px;">
                                                                                    <table id="tableOperadoresDistribucionPorCampos" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                </div>
                                                                                <div style="" class="ui-corner-bottom ui-state-default">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td>Buscar</td>
                                                                                            <td><input onkeyup="search_operadores_distribucion(this.value,'tableOperadoresDistribucionPorCampos')" id="txtSearchOperadoresDistribucionPorTramo" type="text" /></td>
                                                                                            <td>Filtro Cluster:</td>
                                                                                            <td><select id="FiltroClusterCampos" class="combo" onchange="cargar_teleoperadores_distribucion_por_campos_cluster('cbCarteraDistribucionCampos',this.value)"></select></td>
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
                                                            <td><button class="ui-state-default ui-corner-all" onclick="save_distribucion_por_campos()" >Distribuir</button></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div id="panel_Form_distribucion_por_especial" class="ui-widget-content" style="display:none; padding:10px 0;width:99.5%;" align="center" >
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <table>
                                                                        <tr>
                                                                            <td>Campa&ntilde;a</td>
                                                                            <td><select id="cbCampaniaDistribucionEspecial" onchange="load_cartera_by_id_rpte_rank(this.value,'cbCarteraDistribucionEspecial')" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                            <td>Cartera</td>
                                                                            <td><select id="cbCarteraDistribucionEspecial" onchange="reload_jqgrid_especial(this.value)" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                            <td>Operador</td>
                                                                            <td><select id="cbOperadorDistribucionEspecial" onchange="reload_jqgrid_especial_asignados(this.value)" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <h3 class="ui-widget-header ui-corner-all" style="padding:3px 5px;width:150px;">Clientes de Cartera</h3>
                                                                <div>
                                                                    <table id="table_clientes_distribucion_especial" cellpadding="0" cellspacing="0" border="0" ></table>
                                                                    <div id="pager_clientes_distribucion_especial"></div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <table>
                                                                        <tr>
                                                                            <td><button class="btn" onclick="save_cliente_distribucion_especial()">Asignar</button></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <h3 class="ui-widget-header ui-corner-all" style="padding:3px 5px;width:150px;">Clientes Asignados</h3>
                                                                <div>
                                                                    <table id="table_clientes_asignados_distribucion_especial" cellpadding="0" cellspacing="0" border="0"></table>
                                                                    <div id="pager_clientes_asignados_distribucion_especial"></div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <table>
                                                                        <tr>
                                                                            <td><button class="btn" onclick="delete_cliente_distribucion_especial()">Desasignar</button></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div id="panel_Form_distribucion_por_montos_iguales" class="ui-widget-content" style="display:none; padding:10px 0;width:99.5%;" align="center" >
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <table>
                                                                        <tr>
                                                                            <td>Campa&ntilde;a</td>
                                                                            <td><select id="cbCampaniaDistribucionMontosIguales" onchange="load_cartera_by_id_rpte_rank(this.value,'cbCarteraDistribucionMontosIguales')" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                            <td>Cartera</td>
                                                                            <!--<td><select id="cbCarteraDistribucionMontosIguales" onchange="cargar_teleoperadores_distribucion_montos_iguales(this.value);listar_zonas(this.value);CantidadClientesSinAsignarCartera();CantidadCuentasPorCartera();" class="combo" ><option value="0">--Seleccione--</option></select></td>-->
                                                                            <td><select id="cbCarteraDistribucionMontosIguales" onchange="cargar_teleoperadores_distribucion_montos_iguales(this.value);CantidadClientesSinAsignarCartera();CantidadCuentasPorCartera();" class="combo" ><option value="0">--Seleccione--</option></select></td>                                                                            
                                                                            <td>Zona</td>
                                                                            <td><select id="cbZonaDistribucionMontosIguales" onchange="CantidadClientesSinAsignarZonas(this.value)" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        </tr>
                                                                    </table>
                                                                    <table >
                                                                        <tr>
                                                                            <td class="ui-state-default" style="padding:3px 5px;">Cantidad de Clientes Sin Asignar</td>
                                                                            <td class="ui-widget-content" align="center" style="width:80px;"><label id="lbCantidadClientesSinAsignarZona"></label></td>

                                                                            <td class="ui-state-default" style="padding:3px 5px;">Cantidad de Cuentas de la Cartera </td>
                                                                            <td class="ui-widget-content" align="center" style="width:80px;"><label id="lbCantidadCuentasPorCartera"></label></td>
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
                                                                            <td>
                                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                                    <tr class="ui-state-default">
                                                                                        <td style="width:30px;padding:3px 0;" class="ui-state-default ui-corner-tl" align="center">&nbsp;</td>
                                                                                        <td style="width:70px;padding:3px 0;" class="ui-state-default" align="center">Id</td>
                                                                                        <td style="width:300px;padding:3px 0;" class="ui-state-default" align="center">Operador</td>
                                                                                        <td style="width:60px;padding:3px 0;" class="ui-state-default" align="center">Asignados</td>
                                                                                        <td style="width:70px;padding:3px 0;" class="ui-state-default" align="center">Gestionados</td>
                                                                                        <td style="width:30px;padding:3px 0;" class="ui-state-default" align="center"><input type="checkbox" onclick="check_all_table(this.checked,'tableOperadoresDistribucionMontosIguales')" /></td>
                                                                                        <td style="width:20px;padding:3px 0;" class="ui-state-default ui-corner-tr" align="center">&nbsp;</td>
                                                                                    </tr>
                                                                                </table>
                                                                                <div style="overflow:auto;height:200px;width:590px;">
                                                                                    <table id="tableOperadoresDistribucionMontosIguales" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                </div>
                                                                                <div style="" class="ui-corner-bottom ui-state-default">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td>Buscar</td>
                                                                                            <td><input onkeyup="search_operadores_distribucion(this.value,'tableOperadoresDistribucionMontosIguales')" id="txtSearchOperadoresDistribucionMontosIguales" type="text" /></td>
                                                                                            <td>Filtro Cluster:</td>
                                                                                            <td><select id="FiltroClusterMontosIguales" class="combo" onchange="cargar_teleoperadores_distribucion_montos_iguales_cluster('cbCarteraDistribucionMontosIguales',this.value)"></select></td>
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
                                                                <button class="ui-state-default ui-corner-all" onclick="save_distribucion_montos_iguales()">Distribuir</button>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div id="panel_Form_distribucion_constante" class="ui-widget-content" style="display:none; padding:10px 0;width:99.5%;" align="center" >
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <table>
                                                                        <tr>
                                                                            <td>Campa&ntilde;a</td>
                                                                            <td><select id="cbCampaniaDistribucionConstante" onchange="load_cartera_by_id_rpte_rank(this.value,'cbCarteraDistribucionConstante')" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                            <td>Cartera</td>
                                                                            <td><select id="cbCarteraDistribucionConstante" onchange="cargar_teleoperadores_distribucion_constante(this.value);CANTIDAD_CLIENTES_SIN_ASIGNAR_DCONSTANTE(this.value);" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                            <td>Cartera Referencia</td>
                                                                            <td><select id="cbCarteraReferenciaDistribucionConstante" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                        </tr>
                                                                    </table>
                                                                    <table >
                                                                        <tr>
                                                                            <td class="ui-state-default" style="padding:3px 5px;">Cantidad de Clientes Sin Asignar</td>
                                                                            <td class="ui-widget-content" align="center" style="width:80px;"><label id="lbCantidadClientesSinAsignarConstante"></label></td>
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
                                                                            <td>
                                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                                    <tr class="ui-state-default">
                                                                                        <td style="width:30px;padding:3px 0;" class="ui-state-default ui-corner-tl" align="center">&nbsp;</td>
                                                                                        <td style="width:70px;padding:3px 0;" class="ui-state-default" align="center">Id</td>
                                                                                        <td style="width:300px;padding:3px 0;" class="ui-state-default" align="center">Operador</td>
                                                                                        <td style="width:60px;padding:3px 0;" class="ui-state-default" align="center">Asignados</td>
                                                                                        <td style="width:70px;padding:3px 0;" class="ui-state-default" align="center">Gestionados</td>
                                                                                        <td style="width:30px;padding:3px 0;" class="ui-state-default" align="center"><input type="checkbox" onclick="check_all_table(this.checked,'tableOperadoresDistribucionConstante')" /></td>
                                                                                        <td style="width:20px;padding:3px 0;" class="ui-state-default ui-corner-tr" align="center">&nbsp;</td>
                                                                                    </tr>
                                                                                </table>
                                                                                <div style="overflow:auto;height:200px;width:590px;">
                                                                                    <table id="tableOperadoresDistribucionConstante" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                </div>
                                                                                <div style="" class="ui-corner-bottom ui-state-default">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td>Buscar</td>
                                                                                            <td><input onkeyup="search_operadores_distribucion(this.value,'tableOperadoresDistribucionConstante')" id="txtSearchOperadoresDistribucionMontosIguales" type="text" /></td>
                                                                                            <td>Filtro Cluster:</td>
                                                                                            <td><select id="FiltroClusterDistribucionConstante" class="combo" onchange="cargar_teleoperadores_distribucion_constante_cluster('cbCarteraDistribucionConstante',this.value)"></select></td>
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
                                                                <button class="ui-state-default ui-corner-all" onclick="GUARDAR_DISTRIBUCION_CONSTANTE()">Distribuir</button>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div id="panel_Form_distribucion_sin_gestion" class="ui-widget-content" style="display:none; padding:10px 0;width:99.5%;" align="center" >
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <table>
                                                                        <tr>
                                                                            <td>Campa&ntilde;a</td>
                                                                            <td><select id="cbCampaniaDistribucionSinGestion" onchange="load_cartera_by_id_rpte_rank(this.value,'cbCarteraDistribucionSinGestion')" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                            <td>Cartera</td>
                                                                            <td><select id="cbCarteraDistribucionSinGestion" onchange="cargar_teleoperadores_distribucion_sin_gestion(this.value);CANTIDAD_CLIENTES_SIN_ASIGNAR_SIN_GESTION(this.value);" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                        </tr>
                                                                    </table>
                                                                    <table >
                                                                        <tr>
                                                                            <td class="ui-state-default" style="padding:3px 5px;">Cantidad de Clientes Sin Asignar</td>
                                                                            <td class="ui-widget-content" align="center" style="width:80px;"><label id="lbCantidadClientesSinAsignarSinGestion"></label></td>
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
                                                                            <td>
                                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                                    <tr class="ui-state-default">
                                                                                        <td style="width:30px;padding:3px 0;" class="ui-state-default ui-corner-tl" align="center">&nbsp;</td>
                                                                                        <td style="width:70px;padding:3px 0;" class="ui-state-default" align="center">Id</td>
                                                                                        <td style="width:300px;padding:3px 0;" class="ui-state-default" align="center">Operador</td>
                                                                                        <td style="width:60px;padding:3px 0;" class="ui-state-default" align="center">Asignados</td>
                                                                                        <td style="width:70px;padding:3px 0;" class="ui-state-default" align="center">Gestionados</td>
                                                                                        <td style="width:30px;padding:3px 0;" class="ui-state-default" align="center"><input type="checkbox" onclick="check_all_table(this.checked,'tableOperadoresDistribucionSinGestion')" /></td>
                                                                                        <td style="width:20px;padding:3px 0;" class="ui-state-default ui-corner-tr" align="center">&nbsp;</td>
                                                                                    </tr>
                                                                                </table>
                                                                                <div style="overflow:auto;height:200px;width:590px;">
                                                                                    <table id="tableOperadoresDistribucionSinGestion" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                </div>
                                                                                <div style="" class="ui-corner-bottom ui-state-default">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td>Buscar</td>
                                                                                            <td><input onkeyup="search_operadores_distribucion(this.value,'tableOperadoresDistribucionSinGestion')" id="txtSearchOperadoresDistribucionSinGestion" type="text" /></td>
                                                                                            <td>Filtro Cluster:</td>
                                                                                            <td><select id="FiltroClusterSinGestion" class="combo" onchange="cargar_teleoperadores_distribucion_sin_gestion_cluster('cbCarteraDistribucionSinGestion',this.value)"></select></td>
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
                                                                <button class="ui-state-default ui-corner-all" onclick="GUARDAR_DISTRIBUCION_SIN_GESTION()">Distribuir</button>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div id="panel_Form_distribucion_mecanica" class="ui-widget-content" style="display:none; padding:10px 0;width:99.5%;" align="center" >
                                                    <table>	
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <table>
                                                                        <tr>
                                                                            <td>Campa&ntilde;a</td>
                                                                            <td><select id="cbCampaniaDistribucionMecanico" onchange="load_cartera_by_id_rpte_rank(this.value,'cbCarteraDistribucionMecanico')" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                            <td>Cartera</td>
                                                                            <td><select id="cbCarteraDistribucionMecanico" onchange="cargar_teleoperadores_distribucion_mecanica(this.value)" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                            <td>Modo</td>
                                                                            <td>
                                                                                <select id="cbModoDistribucionMecanico" class="combo">
                                                                                    <option value="cartera">Cartera</option>
                                                                                    <option value="seguimiento">Seguimiento</option>
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                                <div>
                                                                    <table>
                                                                        <tr>
                                                                            <td><input type="file" name="fileDistribucionMecanica" id="fileDistribucionMecanica" /></td>
                                                                            <td>Caracter Separador</td>
                                                                            <td><select id="cbSeparadorDistribucionMecanico" class="combo"><option value="tab">TAB</option><option value="|">|</option><option value=";">;</option></select></td>
                                                                            <td><button onclick="upload_file_distribucion_mecanica()" class="ui-state-default ui-corner-all" style="padding:2px;">Subir Archivo</button></td>
                                                                            <td><input type="hidden" id="archivoDistribucionMecanica" /></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><button onclick="if( $('#cbCarteraDistribucionMecanico').val() != 0 ) { window.location.href='../rpt/excel/fotocartera_distribucion.php?Servicio='+$('#hdCodServicio').val()+'&Cartera='+$('#cbCarteraDistribucionMecanico').val() }else{ alert('Seleccione cartera'); }" class="ui-state-default ui-corner-all" style="padding:2px;">Exportar Fotocartera</button></td>
                                                                            <td></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><button onclick="window.location.href='../rpt/excel/usuario/ListUsuarioByService.php?Servicio='+$('#hdCodServicio').val()" class="ui-state-default ui-corner-all" style="padding:2px;">Exportar Usuarios</button></td>
                                                                            <td></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <table>
                                                                        <tr id="trHeaderDistribucionMecanico">
                                                                            <td>Codigo Cliente</td>
                                                                            <td><select id="codigo_cliente" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                            <td>Codigo Usuario</td>
                                                                            <td><select id="codigo" class="combo"><option value="0">--Seleccione--</option></select></td>
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
                                                                            <td>
                                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                                    <tr class="ui-state-default">
                                                                                        <td style="width:30px;padding:3px 0;" class="ui-state-default ui-corner-tl" align="center">&nbsp;</td>
                                                                                        <td style="width:70px;padding:3px 0;" class="ui-state-default" align="center">Id</td>
                                                                                        <td style="width:300px;padding:3px 0;" class="ui-state-default" align="center">Operador</td>
                                                                                        <td style="width:60px;padding:3px 0;" class="ui-state-default" align="center">Asignados</td>
                                                                                        <td style="width:70px;padding:3px 0;" class="ui-state-default" align="center">Gestionados</td>
                                                                                        <td style="width:30px;padding:3px 0;" class="ui-state-default" align="center"><input type="checkbox" onclick="check_all_table(this.checked,'tableOperadoresDistribucionMecanica')" /></td>
                                                                                        <td style="width:20px;padding:3px 0;" class="ui-state-default ui-corner-tr" align="center">&nbsp;</td>
                                                                                    </tr>
                                                                                </table>
                                                                                <div style="overflow:auto;height:200px;width:590px;">
                                                                                    <table id="tableOperadoresDistribucionMecanica" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                </div>
                                                                                <div style="" class="ui-corner-bottom ui-state-default">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td>Buscar</td>
                                                                                            <td><input onkeyup="search_operadores_distribucion(this.value,'tableOperadoresDistribucionMecanica')" id="txtSearchOperadoresDistribucionMecanica" type="text" /></td>
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
                                                                <button class="ui-state-default ui-corner-all" onclick="save_distribucion_mecanica()" >Distribuir</button>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div id="panel_Form_distribucion_pagos" class="ui-widget-content" style="display:none; padding:10px 0;width:99.5%;" align="center" >
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <table>
                                                                        <tr>
                                                                            <td>Campa&ntilde;a</td>
                                                                            <td><select id="cbCampaniaDistribucionPagos" onchange="load_cartera_by_id_rpte_rank(this.value,'cbCarteraDistribucionPagos')" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                            <td>Cartera</td>
                                                                            <td><select id="cbCarteraDistribucionPagos" onchange="cargar_teleoperadores_distribucion_pagos(this.value);CANTIDAD_CLIENTES_SIN_ASIGNAR_DISTRIBUCION_PAGOS();" class="combo" ><option value="0">--Seleccione--</option></select></td>
                                                                            <td>Modo</td>
                                                                            <td>
                                                                                <select id="cbModoDistribucionPagos" class="combo">
                                                                                    <option value="cartera">Cartera</option>
                                                                                    <option value="seguimiento">Seguimiento</option>
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                    <table>
                                                                        <tr>
                                                                            <td><input type="checkbox" title="sin_pago" onclick="CANTIDAD_CLIENTES_SIN_ASIGNAR_DISTRIBUCION_PAGOS()" name="ckbDistribucionPagosSinPagos" /></td>
                                                                            <td>Sin Pagos</td>
                                                                            <td style="width:30px;"></td>
                                                                            <td><input type="checkbox" title="amortizado" onclick="CANTIDAD_CLIENTES_SIN_ASIGNAR_DISTRIBUCION_PAGOS()" name="ckbDistribucionPagosAmortizados" /></td>
                                                                            <td>Amortizados</td>
                                                                            <td style="width:30px;"></td>
                                                                            <td><input type="checkbox" title="cancelado" onclick="CANTIDAD_CLIENTES_SIN_ASIGNAR_DISTRIBUCION_PAGOS()" name="ckbDistribucionPagosCancelados" /></td>
                                                                            <td>Cancelados</td>
                                                                        </tr>
                                                                    </table>
                                                                    <table>
                                                                        <tr>
                                                                            <td class="ui-state-default" style="padding:3px 5px;">Cantidad de Clientes Sin Asignar</td>
                                                                            <td align="center" class="ui-widget-content" style="width:80px;">
                                                                                <label id="lbCantidadClientesSinAsignarDistribucionPagos"></label>
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
                                                                            <td>
                                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                                    <tr class="ui-state-default">
                                                                                        <td style="width:30px;padding:3px 0;" class="ui-state-default ui-corner-tl" align="center">&nbsp;</td>
                                                                                        <td style="width:70px;padding:3px 0;" class="ui-state-default" align="center">Id</td>
                                                                                        <td style="width:300px;padding:3px 0;" class="ui-state-default" align="center">Operador</td>
                                                                                        <td style="width:60px;padding:3px 0;" class="ui-state-default" align="center">Asignados</td>
                                                                                        <td style="width:70px;padding:3px 0;" class="ui-state-default" align="center">Gestionados</td>
                                                                                        <td style="width:30px;padding:3px 0;" class="ui-state-default" align="center"><input type="checkbox" onclick="check_all_table(this.checked,'tableOperadoresDistribucionPagos')" /></td>
                                                                                        <td style="width:20px;padding:3px 0;" class="ui-state-default ui-corner-tr" align="center">&nbsp;</td>
                                                                                    </tr>
                                                                                </table>
                                                                                <div style="overflow:auto;height:200px;width:590px;">
                                                                                    <table id="tableOperadoresDistribucionPagos" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                </div>
                                                                                <div style="" class="ui-corner-bottom ui-state-default">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td>Buscar</td>
                                                                                            <td><input onkeyup="search_operadores_distribucion(this.value,'tableOperadoresDistribucionPagos')" id="txtSearchOperadoresDistribucionPagos" type="text" /></td>
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
                                                                <button class="ui-state-default ui-corner-all" onclick="save_distribucion_pagos()" >Distribuir</button>
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
                                                    <table id="table_tab_distribucion_bottom" cellpadding="0" cellspacing="0" border="0">
                                                        <tr valign="top">
                                                            <td><div onClick="_activeTabLayer('table_tab_distribucion_bottom','tab_distribucion_bottom_',this,'content_distribucion_bottom','panel_Form_distribucion_','panel_Form_distribucion_automatica')" id="tab_distribucion_bottom_automatica" class="itemTabActive border-radius-bottom pointer ui-widget-header" style="margin: 0px 1px 0pt 0pt;height:100%;" ><div class="text-white">Automatica</div></div></td>
                                                            <td><div onClick="_activeTabLayer('table_tab_distribucion_bottom','tab_distribucion_bottom_',this,'content_distribucion_bottom','panel_Form_distribucion_','panel_Form_distribucion_manual')" id="tab_distribucion_bottom_manual" class="itemTab border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;height:100%;" ><div class="AitemTab">Manual</div></div></td>
                                                            <td><div onClick="_activeTabLayer('table_tab_distribucion_bottom','tab_distribucion_bottom_',this,'content_distribucion_bottom','panel_Form_distribucion_','panel_Form_distribucion_por_operador')" id="tab_distribucion_bottom_operador" class="itemTab border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;height:100%;" ><div class="AitemTab">Por Cliente</div></div></td>
                                                            <td><div onClick="_activeTabLayer('table_tab_distribucion_bottom','tab_distribucion_bottom_',this,'content_distribucion_bottom','panel_Form_distribucion_','panel_Form_distribucion_por_departamento')" id="tab_distribucion_bottom_departamento" class="itemTab border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;height:100%;" ><div class="AitemTab">Por Departamento</div></div></td>
                                                            <td><div onClick="_activeTabLayer('table_tab_distribucion_bottom','tab_distribucion_bottom_',this,'content_distribucion_bottom','panel_Form_distribucion_','panel_Form_distribucion_por_tramo')" id="tab_distribucion_bottom_tramo" class="itemTab border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;height:100%;" ><div class="AitemTab">Por Tramo</div></div></td>
                                                            <td><div onClick="_activeTabLayer('table_tab_distribucion_bottom','tab_distribucion_bottom_',this,'content_distribucion_bottom','panel_Form_distribucion_','panel_Form_distribucion_por_campos')" id="tab_distribucion_bottom_campo" class="itemTab border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;height:100%;" ><div class="AitemTab">Por Campos</div></div></td>
                                                            <td><div onClick="_activeTabLayer('table_tab_distribucion_bottom','tab_distribucion_bottom_',this,'content_distribucion_bottom','panel_Form_distribucion_','panel_Form_distribucion_por_especial')" id="tab_distribucion_bottom_especial" class="itemTab border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;height:100%;" ><div class="AitemTab">Especial</div></div></td>
                                                            <td><div onClick="_activeTabLayer('table_tab_distribucion_bottom','tab_distribucion_bottom_',this,'content_distribucion_bottom','panel_Form_distribucion_','panel_Form_distribucion_por_montos_iguales')" id="tab_distribucion_bottom_montos_iguales" class="itemTab border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;height:100%;" ><div class="AitemTab">Montos Iguales</div></div></td>
                                                            <td><div onClick="_activeTabLayer('table_tab_distribucion_bottom','tab_distribucion_bottom_',this,'content_distribucion_bottom','panel_Form_distribucion_','panel_Form_distribucion_constante')" id="tab_distribucion_bottom_constante" class="itemTab border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;height:100%;" ><div class="AitemTab">Constante</div></div></td>
                                                            <td><div onClick="_activeTabLayer('table_tab_distribucion_bottom','tab_distribucion_bottom_',this,'content_distribucion_bottom','panel_Form_distribucion_','panel_Form_distribucion_sin_gestion')" id="tab_distribucion_bottom_sin_gestion" class="itemTab border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;height:100%;" ><div class="AitemTab">Sin Gestion</div></div></td>
                                                            <td><div onClick="_activeTabLayer('table_tab_distribucion_bottom','tab_distribucion_bottom_',this,'content_distribucion_bottom','panel_Form_distribucion_','panel_Form_distribucion_mecanica')" id="tab_distribucion_bottom_mecanico" class="itemTab border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;" ><div class="AitemTab">Mecanico</div></div></td>
                                                            <td><div onClick="_activeTabLayer('table_tab_distribucion_bottom','tab_distribucion_bottom_',this,'content_distribucion_bottom','panel_Form_distribucion_','panel_Form_distribucion_pagos')" id="tab_distribucion_bottom_pagos" class="itemTab border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;" ><div class="AitemTab">Pagos</div></div></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>    
                            <div id="panelClientesGestionadosSinGestionar" style="display:none;" align="center">
                                <table border="0" cellpadding="10" cellspacing="2">
                                    <tr>
                                        <td>
                                            <div>
                                                <table>
                                                    <tr>
                                                        <td align="right">Campania</td>
                                                        <td>
                                                            <select class="combo" id="cbCampaniaClientesGestSinGest" onchange="load_cartera_by_id_rpte_rank(this.value,'cbCarteraClientesGestSinGest')">
                                                                <option value="0">--Seleccione--</option>
                                                            </select>
                                                        </td>
                                                        <td align="right">Cartera</td>
                                                        <td>
                                                            <select class="combo" id="cbCarteraClientesGestSinGest" onchange="reload_jqgrid_clientes_GSG(this.value)">
                                                                <option value="0">--Seleccione--</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                </table>	
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <div onclick="_slide2(this,'PanelTableClientesGestionados')">
                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                        <tr>
                                            <td style="width:25px; height:25px;">
                                                <div class="backPanel iconPinBlueDown" ></div>
                                            </td>
                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                <div style="direction:ltr;">
                                                    <a class="text-blue">Clientes Gestionados</a>
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
                                <div id="PanelTableClientesGestionados" style="display:block;margin-top:5px;">
                                    <table id="table_clientes_gestionados"></table>
                                    <div id="pager_table_clientes_gestionados"></div>
                                    <table>
                                        <tr>
                                            <td>
                                                <button onclick="link_exportar_clientes_gestionados()" id="btnExportarClientesGestionados">Exportar</button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div onclick="_slide2(this,'PanelTableClientesSinGestionar')">
                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                        <tr>
                                            <td style="width:25px; height:25px;">
                                                <div class="backPanel iconPinBlueDown" ></div>
                                            </td>
                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                <div style="direction:ltr;">
                                                    <a class="text-blue">Clientes Sin Gestionar</a>
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
                                <div id="PanelTableClientesSinGestionar" style="display:block; margin:5px 0;">
                                    <table id="table_clientes_sin_gestionar"></table>
                                    <div id="pager_table_clientes_sin_gestionar"></div>
                                    <table>
                                        <tr>
                                            <td>
                                                <button onclick="link_exportar_clientes_sin_gestionar()" id="btnExportarClientesSinGestionar">Exportar</button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div id="panelRetirarClientes" style="display:none;padding-top:5px;" align="center">
                                <table cellpadding="0" cellspacing="0" border="0" style="width:99%">
                                    <tr>
                                        <td id="content_retirar_cliente_bottom">
                                            <div id="layer_content_retirar_cliente_bottom_cantidad" class="ui-widget-content" align="center" style="display: block; padding: 5px 0pt; width: 99.5%;">
                                                <table border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td>
                                                            <div>
                                                                <table>
                                                                    <tr>
                                                                        <td align="right">Campa&ntilde;a</td>
                                                                        <td><select class="combo" id="cbCampaniaRetirarCliente" onchange="load_cartera_by_id_rpte_rank(this.value,'cbCarteraRetirarCliente')" ><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right">Cartera</td>
                                                                        <td><select class="combo" id="cbCarteraRetirarCliente" onchange="cargar_data_retirar_clientes(this.value)" ><option value="0">--Seleccione--</option></select></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="ui-widget-content ui-corner-all" style="padding:3px 5px;width:620px;" >
                                                                <table cellpadding="0" cellspacing="0" border="0" style="width:620px;">
                                                                    <tr>
                                                                        <td>
                                                                            <div class="headerPanel2 ui-corner-all" style="margin-bottom:3px;" align="center">Operadores</div>
                                                                            <div>
                                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                                    <tr>
                                                                                        <td align="center"><div class="ui-widget-header ui-corner-all" style="padding:2px 4px;margin:2px;width:180px;">Operador</div></td>
                                                                                        <td align="center"><div class="ui-widget-header ui-corner-all" style="padding:2px 4px;margin:2px;width:100px;">Clts. Asignados</div></td>
                                                                                        <td align="center"><div class="ui-widget-header ui-corner-all" style="padding:2px 4px;margin:2px;width:100px;">Clts. Gestionados</div></td>
                                                                                        <td align="center"><div class="ui-widget-header ui-corner-all" style="padding:2px 4px;margin:2px;width:105px;">Clts. Sin Gestionar</div></td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                            <div align="left" style="overflow:auto;height:200px;">
                                                                                <table id="table_retirar_clientes" cellpadding="0" cellspacing="0" border="0"></table>
                                                                            </div>
                                                                            <div>
                                                                                <table>
                                                                                    <tr>
                                                                                        <td>Buscar:</td>
                                                                                        <td><input type="text" onkeyup="search_operadores_distribucion(this.value,'table_retirar_clientes')" class="cajaForm" /></td>
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
                                            <div id="layer_content_retirar_cliente_bottom_teleoperador" class="ui-widget-content" align="center" style="display: none; padding: 5px 0pt; width: 99.5%;">
                                                <table cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td>
                                                            <div>
                                                                <table>
                                                                    <tr>
                                                                        <td align="right">Campa&ntilde;a</td>
                                                                        <td><select class="combo" id="cbCampaniaRetirarClienteTeleoperador" onchange="load_cartera_by_id_rpte_rank(this.value,'cbCarteraRetirarClienteTeleoperador')" ><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right">Cartera</td>
                                                                        <td><select class="combo" id="cbCarteraRetirarClienteTeleoperador" onchange="cargar_data_retirar_clientes(this.value)" ><option value="0">--Seleccione--</option></select></td>
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
                                                                        <td>
                                                                            <div>
                                                                                <div class="ui-state-default ui-corner-top">Operadores</div>
                                                                                <div style="overflow:auto;height:200px;">
                                                                                    <table cellpadding="0" cellspacing="0" border="0" id="table_retirar_cliente_por_teleoperador"></table>
                                                                                </div> 
                                                                                <div class="ui-state-default ui-corner-bottom">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td>Buscar:</td>
                                                                                            <td><input type="text" class="cajaForm" /></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td><span class="ui-icon ui-icon-arrowthick-1-e" ></span></td>
                                                                        <td><select class="combo"><option value="0">--Seleccione--</option></select></td>
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
                                <table cellpadding="0" cellspacing="0" border="0" style="width:99%;margin-bottom:5px;"> 
                                    <tr>
                                        <td class="lineTab ui-widget-header"></td>
                                    </tr>
                                    <tr>
                                        <td valign="top">
                                            <div style="margin-left:100px;">
                                                <table id="tab_table_retirar_cliente_bottom" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td>
                                                            <div onclick="_activeTabLayer('tab_table_retirar_cliente_bottom','tab_retirar_cliente_bottom_',this,'content_retirar_cliente_bottom','layer_content_retirar_cliente_bottom_','layer_content_retirar_cliente_bottom_cantidad')" id="tab_retirar_cliente_bottom_manual" class="border-radius-bottom pointer itemTab ui-widget-header" style="margin: 0px 1px 0pt 0pt;height:100%;">
                                                                <div>Cantidad</div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div onclick="_activeTabLayer('tab_table_retirar_cliente_bottom','tab_retirar_cliente_bottom_',this,'content_retirar_cliente_bottom','layer_content_retirar_cliente_bottom_','layer_content_retirar_cliente_bottom_teleoperador')" id="tab_retirar_cliente_bottom_teleoperador" class="border-radius-bottom pointer itemTab ui-widget-content" style="margin: 0px 1px 0pt 0pt;height:100%;">
                                                                <div>Por Teleoperador</div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </table>

                            </div>
                            <div id="panelTraspasoCartera" style="display:none;padding-top:5px;" align="left">
                                <div  align="center">
                                    <div class="backPanel headerPanel ui-corner-top" style="height:20px; padding-top:5px; font-weight:bold" align="center">TRASLADO DE CARTERA ENTRE OPERADORES</div>	
                                    <div align="center" class="ui-widget-content ui-corner-bottom" style="padding:10px 0px 10px 0px">
                                        <table border="0" cellpadding="0" cellspacing="5">
                                            <tr>
                                                <td colspan="3" align="center">
                                                    <table>
                                                        <tr>
                                                            <td align="right">Campa&ntilde;a</td>
                                                            <td><select class="combo" id="cbCampaniaTraspasoCartera" onchange="load_cartera_by_id_rpte_rank(this.value,'cbCarteraTraspasoCartera')" ><option value="0">--Seleccione--</option></select></td>
                                                            <td align="right">Cartera</td>
                                                            <td><select class="combo" id="cbCarteraTraspasoCartera" onchange="cargar_data_traspaso_clientes(this.value)" ><option value="0">--Seleccione--</option></select></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr valign="top" align="center">
                                                <td>
                                                    <table cellpadding="0" cellspacing="0" border="0" class="ui-state-highlight ui-corner-top text-blue" style="width:550px; height:25px;">
                                                        <tr  align="center"><td>DE &nbsp;&nbsp;<input onkeyup="search_text_table(this.value,'table_traspaso_clientes_DE')" type="text" class="text-blue"/></td></tr>
                                                    </table>
                                                    <div class="ui-widget-content ui-corner-bottom" style="padding:3px 5px;" >    
                                                        <div align="left">
                                                            <table cellpadding="0" cellspacing="0" border="0">
                                                                <tr>
                                                                    <td align="center" style="padding:2px 4px;width:255px"><div class="ui-widget-header ui-corner-all" >Operador</div></td>
                                                                    <td align="center" style="padding:2px 2px;width:60px"><div class="ui-widget-header ui-corner-all" >Clts. Asig.</div></td>
                                                                    <td align="center" style="padding:2px 2px;width:60px"><div class="ui-widget-header ui-corner-all" >Clts. Gest.</div></td>
                                                                    <td align="center" style="padding:2px 2px;width:80px"><div class="ui-widget-header ui-corner-all" >Clts. Sin Gest.</div></td>
                                                                    <td align="center" style="padding:2px 2px;width:10px"><div class="ui-widget-header ui-corner-all" ></div></td>
                                                                </tr>
                                                            </table>
                                                            <div style="height:250px;overflow:auto;">
                                                                <table id="table_traspaso_clientes_DE" cellpadding="0" cellspacing="0" border="0"></table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td align="center">
                                                    <span class="ui-icon ui-icon-arrowthick-1-e"></span>
                                                </td>
                                                <td>
                                                    <table cellpadding="0" cellspacing="0" border="0" class="ui-state-highlight ui-corner-top text-blue" style="width:550px; height:25px;">
                                                        <tr  align="center"><td>HACIA &nbsp;&nbsp;<input onkeyup="search_text_table(this.value,'table_traspaso_clientes_PARA')" type="text" class="text-blue"/></td></tr>
                                                    </table>
                                                    <div class="ui-widget-content ui-corner-bottom" style="padding:3px 5px;" >
                                                        <div align="left">
                                                            <table cellpadding="0" cellspacing="0" border="0">
                                                                <tr>
                                                                    <td align="center" style="padding:2px 4px;width:255px"><div class="ui-widget-header ui-corner-all" >Operador</div></td>
                                                                    <td align="center" style="padding:2px 2px;width:60px"><div class="ui-widget-header ui-corner-all" >Clts. Asig.</div></td>
                                                                    <td align="center" style="padding:2px 2px;width:60px"><div class="ui-widget-header ui-corner-all" >Clts. Gest.</div></td>
                                                                    <td align="center" style="padding:2px 2px;width:80px"><div class="ui-widget-header ui-corner-all" >Clts. Sin Gest.</div></td>
                                                                    <td align="center" style="padding:2px 2px;width:10px"><div class="ui-widget-header ui-corner-all" ></div></td>
                                                                </tr>
                                                            </table>
                                                            <div style="height:250px;overflow:auto;">
                                                                <table id="table_traspaso_clientes_PARA" cellpadding="0" cellspacing="0" border="0"></table>
                                                            </div>
                                                        </div>
                                                    </div>    
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <div>
                                                        <table>
                                                            <tr>
                                                                <td><input value="amortizados" id="chkTrCarAmortizados" type="checkbox" ></td>
                                                                <td>Amortizados</td>
                                                                <td><input value="sin_pago" id="chkTrCarSinPago" type="checkbox" ></td>
                                                                <td>Sin pago</td>
                                                                <td><input value="cancelados"  id="chkTrCarCancelados" type="checkbox" ></td>
                                                                <td>Cancelados</td>
                                                            </tr>
                                                        </table>
                                                    </diiv>
                                                </td>
                                            </tr>
                                            <tr height="50">
                                                <td colspan="3" align="center">
                                                    <button style="padding:7px" id="btnTraspasoCartera" class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all" onclick="save_traspaso_carteras_operadores()">
                                                        <span class="ui-button-text text-alert">TRASLADAR CARTERAS</span>
                                                    </button>
                                                </td> 
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="panelRedistribucion" style="display:none;padding-top:5px;" align="center">
                                <table cellpadding="0" cellspacing="0" border="0" style="width:90%">
                                    <tr>
                                        <td id="content_redistribucion_bottom">
                                            <div id="layer_content_redistribucion_bottom_sinpago" align="center" style="display: block; padding: 0px 0pt; width: 99.5%;">
                                                <div class="backPanel headerPanel ui-corner-top" style="height:20px; padding-top:5px; font-weight:bold" align="center">REDISTRIBUCION SIN PAGOS</div>	
                                                <div align="center" class="ui-widget-content" style="padding:10px 0px 0px 0px">

                                                    <table cellpadding="0" cellspacing="0" border="0" >
                                                        <tr>
                                                            <td align="left">
                                                                <div>
                                                                    <table>
                                                                        <tr>
                                                                            <td align="right">Campa&ntilde;a</td>
                                                                            <td><select class="combo" id="cbCampaniaRedistribSinPago" onchange="load_cartera_by_id_rpte_rank(this.value,'cbCarteraRedistribSinPago')"><option value="0">--Seleccione--</option></select></td>
                                                                            <td align="right">Cartera</td>
                                                                            <td><select class="combo" id="cbCarteraRedistribSinPago" onchange="cargar_data_distribucion_sinpago(this.value,'table_operador_RedistribSinPago','clientes_modulo_sinpago')" ><option value="0">--Seleccione--</option></select></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <table cellpadding="0" cellspacing="0" border="0" style="width:785px;">
                                                                        <tr>
                                                                            <td valign="top">
                                                                                <div id="layerRedistribucionOperadores" style="width:460px;" align="center">
                                                                                    <table class="ui-state-highlight ui-corner-top text-blue" cellspacing="0" cellpadding="0" border="0" style="width:450px; height:20px;"><tbody><tr align="center"><td>Operadores</td></tr></tbody>
                                                                                    </table>
                                                                                    <div class="ui-widget-content ui-corner-bottom" style="width:448px;" >
                                                                                        <table cellpadding="0" cellspacing="0" border="0" style="width:430px;">
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <div>
                                                                                                        <table>
                                                                                                            <tr>
                                                                                                                <td align="center"><div class="ui-widget-header ui-corner-all" style="padding:2px 1px;margin:1px;width:210px;">Operador</div></td>
                                                                                                                <td align="center"><div class="ui-widget-header ui-corner-all" style="padding:2px 1px;margin:1px;width:60px;">Asignados</div></td>
                                                                                                                <td align="center"><div class="ui-widget-header ui-corner-all" style="padding:4px 1px;margin:1px;font-size:8px;width:40px;">Sin Gest.</div></td>
                                                                                                                <td align="center"><div class="ui-widget-header ui-corner-all" style="padding:2px 1px;margin:1px;width:60px;">Gestionados</div></td>
                                                                                                            </tr>
                                                                                                        </table>
                                                                                                    </div>
                                                                                                    <div align="center" style="overflow:auto;height:200px;">
                                                                                                        <table id="table_operador_RedistribSinPago"></table>
                                                                                                    </div>
                                                                                                    <div>
                                                                                                        <table>
                                                                                                            <tr>
                                                                                                                <td>Buscar:</td>
                                                                                                                <td><input type="text" class="cajaForm" onkeyup="search_operadores_distribucion(this.value,'table_operador_RedistribSinPago')" /></td>
                                                                                                            </tr>
                                                                                                        </table>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </table>
                                                                                    </div>
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td align="right" class="text-blue">Clientes</td>
                                                                                            <td style="padding:1px 5px" class="ui-state-highlight ui-corner-all text-blue" id="clientes_modulo_sinpago" align="center"></td>
                                                                                            <td width="20"></td>
                                                                                            <td align="left" class="text-blue">Clientes Sin Pagos</td>
                                                                                            <td><input type="text" class="cajaForm" id="txtClienteSinPago" style="width:50px;" readonly="readonly" /></td>
                                                                                            <td><input type="hidden" id="hdClienteSinPago" /></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                            </td>
                                                                            <td style="width:10px;"></td>
                                                                            <td valign="top">
                                                                                <div id="layerRedistribucionAsignacion" style="width:312px;">
                                                                                    <table class="ui-state-highlight ui-corner-top text-blue" cellspacing="0" cellpadding="0" border="0" style="width:312px; height:20px;"><tr align="center"><td>Asignacion</td></tr></table>                                                                      
                                                                                    <div class="ui-corner-bottom ui-widget-content" style="padding:3px 5px;width:300px;" >
                                                                                        <table border="0" cellpadding="0" cellspacing="0" style="width:300px;">
                                                                                            <tr>
                                                                                                <td align="center">
                                                                                                    <div class="formHeader ui-corner-all">
                                                                                                        <table id="table_asignacion_RedistribSinPago" style=" width:100%">
                                                                                                            <tr id="placeHolder">
                                                                                                                <td>Coloque operadores aqui..</td>
                                                                                                            </tr>
                                                                                                        </table>
                                                                                                    </div>
                                                                                                    <div style="padding:5px 0px">
                                                                                                        <div class="ui-state-default ui-corner-all" style="padding:4px; width:100px" align="center" onclick="grabar_distribucion_sinpago()">
                                                                                                            <table cellpadding="0" cellspacing="0"><tr><td><span class="ui-icon ui-icon-disk"></span></td><td><span>REDISTRIBUIR</span></td></tr></table>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </td>
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

                                                </div>
                                            </div>
                                            <div id="layer_content_redistribucion_bottom_amortizado" align="center" style="display: none; padding: 5px 0pt; width: 99.5%;">
                                                <div class="backPanel headerPanel ui-corner-top" style="height:20px; padding-top:5px; font-weight:bold" align="center">REDISTRIBUCION AMORTIZADOS</div>	
                                                <div align="center" class="ui-widget-content" style="padding:10px 0px 0px 0px">
                                                    <table cellpadding="0" cellspacing="0" border="0" >
                                                        <tr>
                                                            <td align="left">
                                                                <div>
                                                                    <table>
                                                                        <tr>
                                                                            <td align="right">Campa&ntilde;a</td>
                                                                            <td><select class="combo" id="cbCampaniaRedistribAmortizado" onchange="load_cartera_by_id_rpte_rank(this.value,'cbCarteraRedistribAmortizado')"><option value="0">--Seleccione--</option></select></td>
                                                                            <td align="right">Cartera</td>
                                                                            <td><select class="combo" id="cbCarteraRedistribAmortizado" onchange="cargar_data_distribucion_amortizado(this.value,'table_operador_RedistribAmortizado','txt_clientes_modulo_amortizado')" ><option value="0">--Seleccione--</option></select></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <table cellpadding="0" cellspacing="0" border="0" style="width:785px;">
                                                                        <tr>
                                                                            <td valign="top">
                                                                                <div id="layerRedistribucionOperadores" style="width:460px;" align="center">
                                                                                    <table class="ui-state-highlight ui-corner-top text-blue" cellspacing="0" cellpadding="0" border="0" style="width:450px; height:20px;"><tbody><tr align="center"><td>Operadores</td></tr></tbody>
                                                                                    </table>
                                                                                    <div class="ui-widget-content ui-corner-bottom" style="width:448px;" >
                                                                                        <table cellpadding="0" cellspacing="0" border="0" style="width:430px;">
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <div>
                                                                                                        <table>
                                                                                                            <tr>
                                                                                                                <td align="center"><div class="ui-widget-header ui-corner-all" style="padding:2px 1px;margin:1px;width:210px;">Operador</div></td>
                                                                                                                <td align="center"><div class="ui-widget-header ui-corner-all" style="padding:2px 1px;margin:1px;width:60px;">Asignados</div></td>
                                                                                                                <td align="center"><div class="ui-widget-header ui-corner-all" style="padding:4px 1px;margin:1px;font-size:8px;width:40px;">Sin Gest.</div></td>
                                                                                                                <td align="center"><div class="ui-widget-header ui-corner-all" style="padding:2px 1px;margin:1px;width:60px;">Gestionados</div></td>
                                                                                                            </tr>
                                                                                                        </table>
                                                                                                    </div>
                                                                                                    <div align="center" style="overflow:auto;height:200px;">
                                                                                                        <table id="table_operador_RedistribAmortizado"></table>
                                                                                                    </div>
                                                                                                    <div>
                                                                                                        <table>
                                                                                                            <tr>
                                                                                                                <td>Buscar:</td>
                                                                                                                <td><input type="text" class="cajaForm" onkeyup="search_operadores_distribucion(this.value,'table_operador_RedistribAmortizado')" /></td>
                                                                                                            </tr>
                                                                                                        </table>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </table>
                                                                                    </div>
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td align="right" class="text-blue">Clientes</td>
                                                                                            <td style="padding:1px 5px" class="ui-state-highlight ui-corner-all text-blue" id="txt_clientes_modulo_amortizado" align="center"></td>
                                                                                            <td width="20"></td>
                                                                                            <td align="left" class="text-blue">Clientes Amortizados</td>
                                                                                            <td><input type="text" class="cajaForm" id="txtClienteAmortizado" style="width:50px;" readonly="readonly" /></td>
                                                                                            <td><input type="hidden" id="hdClienteAmortizado" /></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                            </td>
                                                                            <td style="width:10px;"></td>
                                                                            <td valign="top">
                                                                                <div id="layerRedistribucionAsignacion" style="width:312px;">
                                                                                    <table class="ui-state-highlight ui-corner-top text-blue" cellspacing="0" cellpadding="0" border="0" style="width:312px; height:20px;"><tr align="center"><td>Asignacion</td></tr></table>                                                                      
                                                                                    <div class="ui-corner-bottom ui-widget-content" style="padding:3px 5px;width:300px;" >
                                                                                        <table border="0" cellpadding="0" cellspacing="0" style="width:300px;">
                                                                                            <tr>
                                                                                                <td align="center">
                                                                                                    <div class="formHeader ui-corner-all">
                                                                                                        <table id="table_asignacion_RedistribAmortizado" style=" width:100%">
                                                                                                            <tr id="placeHolder">
                                                                                                                <td>Coloque operadores aqui..</td>
                                                                                                            </tr>
                                                                                                        </table>
                                                                                                    </div>
                                                                                                    <div style="padding:5px 0px">
                                                                                                        <div class="ui-state-default ui-corner-all" style="padding:4px; width:100px" align="center" onclick="grabar_distribucion_amortizado()">
                                                                                                            <table cellpadding="0" cellspacing="0"><tr><td><span class="ui-icon ui-icon-disk"></span></td><td><span>REDISTRIBUIR</span></td></tr></table>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </td>
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
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <table cellpadding="0" cellspacing="0" border="0" style="width:90%;margin-bottom:5px;"> 
                                    <tr>
                                        <td class="lineTab ui-widget-header ui-corner-bottom"></td>
                                    </tr>
                                    <tr>
                                        <td valign="top">
                                            <div style="margin-left:100px;">
                                                <table id="tab_table_redistribucion_bottom" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td>
                                                            <div onclick="_activeTabLayer('tab_table_redistribucion_bottom','tab_redistribucion_bottom_',this,'content_redistribucion_bottom','layer_content_redistribucion_bottom_','layer_content_redistribucion_bottom_sinpago')" id="tab_redistribucion_bottom_sinpago" class="border-radius-bottom pointer itemTab ui-widget-header" style="margin: 0px 1px 0pt 0pt;height:100%;">
                                                                <div>Sin Pagos</div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div onclick="_activeTabLayer('tab_table_redistribucion_bottom','tab_redistribucion_bottom_',this,'content_redistribucion_bottom','layer_content_redistribucion_bottom_','layer_content_redistribucion_bottom_amortizado')" id="tab_redistribucion_bottom_amortizado" class="border-radius-bottom pointer itemTab ui-widget-content" style="margin: 0px 1px 0pt 0pt;height:100%;">
                                                                <div>Amortizados</div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </table>

                            </div>
                            <div id="panelRegistrarZona" style="display:none;padding:5px;" align="center" >
                                <table>
                                    <tr>
                                        <td>
                                            <div>
                                                <table>
                                                    <tr>
                                                        <td>Campa&ntilde;a</td>
                                                        <td><select class="combo" onchange="load_cartera_by_id_rpte_rank(this.value,'cbCarteraRegistroZona')" id="cbCampaniaRegistroZona"><option value="0">--Seleccione--</option></select></td>
                                                        <td>Cartera</td>
                                                        <td><select class="combo" id="cbCarteraRegistroZona" ><option value="0">--Seleccione--</option></select></td>
                                                        <td><button onclick="guardar_departamentos_a_zona()" class="ui-state-default ui-corner-all">Grabar Departamentos</button></td>
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
                                                        <td>
                                                            <div>
                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                    <tr>
                                                                        <td class="ui-widget-header ui-corner-tl" style="padding:3px 0;width:30px;" align="center">&nbsp;</td>
                                                                        <td class="ui-widget-header" style="padding:3px 0;width:300px;" align="center">Departamento</td>
                                                                        <td class="ui-widget-header" style="padding:3px 0;width:150px;" align="center">Zona</td>
                                                                        <td class="ui-widget-header ui-corner-tr" style="padding:3px 0;width:18px;" align="center">&nbsp;</td>
                                                                    </tr>
                                                                </table>
                                                                <div style="height:400px;overflow:auto;"><table cellpadding="0" cellspacing="0" border="0" id="table_zonas"></table></div>
                                                                <div class="ui-widget-header ui-corner-bottom" style="height:20px;"></div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <button class="ui-state-default ui-corner-all" onclick="guardar_zonas()">Grabar Zonas</button>
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
            <div style="width:100%; height:20px; border:0 none;" class="ui-widget-header ui-corner-bottom"></div>
        </div>
        <div id="beforeSendShadow" class="ui-widget-shadow" style="height:30px;position:absolute;top:32%;left:45%;display:none;"></div>
        <div id="MsgBeforeSend" class="ui-widget-content ui-corner-all" style="height:30px;position:absolute;top:32%;left:45%;font-weight:bold;font-size:20px;vertical-align:middle;color:#333366;display:none;" align="center"  ></div>
        <div id="dialogCampania">
            <div align="center">
                <table>
                    <tr>	
                        <td colspan="2"><div align="center" id="CampaniaLayerMessage"></div></td>
                    </tr>
                    <tr>
                        <td align="right">Nombre</td>
                        <td align="left"><input type="text" id="txtCampaniaNombre" class="cajaForm" style="width:200px;"  /></td>
                    </tr>
                    <tr>
                        <td align="right">Fecha Inicio</td>
                        <td align="left"><input readonly="readonly" type="text" id="txtCampaniaFechaInicio" class="cajaForm" style="width:200px;"  /></td>
                    </tr>
                    <tr>
                        <td align="right">Fecha Fin</td>
                        <td align="left"><input readonly="readonly" type="text" id="txtCampaniaFechaFin" class="cajaForm" style="width:200px;"  /></td>
                    </tr>
                    <tr>
                        <td align="right" valign="top">Descripcion</td>
                        <td align="left">
                            <textarea id="txtCampaniDescripcion" style="width:280px;height:75px;" class="textareaForm"></textarea>
                        </td>
                    </tr>
                </table>
            </div>
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

