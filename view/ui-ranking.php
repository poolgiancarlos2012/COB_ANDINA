<?php
session_start();

if (empty($_SESSION['cobrast']) || !isset($_SESSION['cobrast']) || $_SESSION['cobrast']['activo'] != 1) {
    header('Location: ../index.php');
}

?>
<title>Home</title>

<link type="text/css" rel="stylesheet" href="../includes/jquery-ui-1.8/themes/humanity/jquery-ui.css" />

<link type="text/css" rel="stylesheet" href="../css/css-cobrast.css" />

<script type="text/javascript" src="../js/includes/jquery-1.4.2.js" ></script>

<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.core.min.js" ></script>
<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.widget.min.js" ></script>
<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.datepicker.min.js" ></script>
<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.button.min.js" ></script>
<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.dialog.min.js" ></script>
<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.effects.core.min.js" ></script>
<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.effects.pulsate.min.js" ></script>
<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.effects.slide.min.js" ></script>
<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.effects.blind.min.js" ></script>

<script type="text/javascript" src="../includes/Highcharts-2.1.3/js/highcharts.js"></script>
<script type="text/javascript" src="../includes/Highcharts-2.1.3/js/themes/grid.js"></script>
<script type="text/javascript" src="../includes/Highcharts-2.1.3/js/modules/exporting.js"></script>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
</script>

<script type="text/javascript" src="../js/js-cobrast.js" ></script>
<script type="text/javascript" src="../js/validacion.js" ></script>
<script type="text/javascript" src="../js/templates.js"  ></script>
<script type="text/javascript" src="../js/RankingDAO.js"  ></script>
<script type="text/javascript" src="../js/js-ranking.js"  ></script>
<style type="text/css">
    .textLogo {
        font-size:100px;
        font-family:Verdana;
        font-weight:bold;
        color:#FFF;
    }
    .textInfo {
        color:#E0CFC2;
        font-size:18px;
        font-family:Verdana;
        font-weight:bold;	
    }
</style>

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
                            <div style="margin-left:20px; margin-bottom:2px;"><a class="text-blue" onClick="_display_panel('panelRankingOperador')">Ranking</a></div>
                            <div style="margin-left:20px; margin-bottom:2px;"><a class="text-blue" onClick="_display_panel('panelRankingOperadorFija')">Ranking Fija</a></div>
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
            <td id="showhide" width="10px" class="showHide ui-widget-header">
                <a onclick="_sliderFadeBarLayer()">
                    <div id="iconSlider" class="slider icon sliderIconDown"></div>
                </a>
            </td>
            <td width="100%">	
                <!--<div id="cobrastHOME" style="background-color:#FFFFFF; width:100%; height:100%; ">-->
                <div id="cobrastHOME" style="width:100%; height:100%; border:0 none;" class="ui-widget-content">
                    <input type="hidden" id="hdCodUsuario" name="hdCodUsuario" value="<?= $_SESSION['cobrast']['idusuario'] ?>" />
                    <input type="hidden" id="hdCodServicio" name="hdCodServicio" value="<?= $_SESSION['cobrast']['idservicio'] ?>" />
                    <input type="hidden" id="hdCodUsuarioServicio" name="hdCodUsuarioServicio" value="<?= $_SESSION['cobrast']['idusuario_servicio'] ?>" />
                    <input type="hidden" id="hdNomServicio" name="hdNomServicio" value="<?= $_SESSION['cobrast']['servicio'] ?>" />
                    <div>
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
                                                            <td class="text-white">No Vencida&nbsp;</td>
                                                            <td><input type="checkbox" onclick="limpiaCamposReporte();" value="0" name="no_vencida" checked="checked" /></td>
                                                            <td width="15"></td>
                                                            <td class="text-white">Vencida&nbsp;</td>
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
                    <div id="panelRankingOperador" class="ui-widget-content" style="border:0 none;width:100%;height:100%;" align="center">
                        <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                            <tr>
                                <td align="center">
                                    <div style="height:100%; width:100%">
                                        <table cellpadding="0" cellspacing="0" border="0" style="width:100%; height:90%">
                                            <tr>
                                                <td id="content_ranking_bottom">
                                                    <div id="layer_Form_ranking_contacto" class="ui-widget-content" style="display:block; padding:5px 0; width:100%;height:420px;overflow:auto;" align="center">
                                                        <div>
                                                            <table>
                                                                <tr valign="top">
                                                                    <td>
                                                                        <div>
                                                                            <div class="ui-widget-header ui-corner-top" style="padding:2px 0;width:320px;">
                                                                                <table width="320" cellpadding="0">
                                                                                    <tr>
                                                                                        <td><font color="#FFFFFF"><b>Carteras</b></font></td>
                                                                                        <td align="right"><input  id="sel_all_por_contacto" type="checkbox" onClick="checked_all(this.checked,'tbRKCartera_por_contacto')" />&nbsp;</td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                            <div style="overflow:auto;width:320px;">
                                                                                <table id="tbRKCartera_por_contacto" cellpadding="0" cellspacing="0" border="0"></table>
                                                                            </div>
                                                                            <div class="ui-widget-header ui-corner-bottom" style="padding:2px 0;height:20px;width:320px;"></div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div>
                                                                            <div><label>Campa&ntilde;a</label>
                                                                                <select onChange="load_ranking_cartera_tb_rpte_rank(this.value,'tbRKCartera_por_contacto');des_checked('sel_all_por_contacto')" id="cbRKCampania_por_contacto" class="combo"><option value="0">--Seleccione--</option></select>
                                                                            </div>
                                                                            <div style="width:80"><label>FechaInicio</label>
                                                                                <input id="txtRKFecha" readonly class="cajaForm" type="text" size="8" />
                                                                            </div>
                                                                            <div style="width:80"><label>FechaFin</label>
                                                                                <input id="txtRKFechaFin" readonly class="cajaForm" type="text" size="8" />
                                                                            </div>
                                                                            <div align="left"><button onClick="load_data_ranking()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-refresh"></span></button>
                                                                                <button onClick="load_data_ranking_rpte()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-extlink"></span></button>
                                                                            </div>
                                                                        </div>
                                                                    </td>    
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div id="layerDataRanking">
                                                            <table cellpadding="0" cellspacing="0" border="0">
                                                                <tr class="ui-state-default">
                                                                    <td style="width:25px;padding:3px 0;border:1px solid #E0CFC2;" align="center">&nbsp;</td>
                                                                    <td style="width:300px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;padding:3px 0;" align="center">Operador</td>
                                                                    <td style="width:90px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;padding:3px 0;" align="center">Abonados</td>
                                                                    <td style="width:90px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;padding:3px 0;" align="center">Llamadas</td>
                                                                    <td style="width:90px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;padding:3px 0;" align="center">NC</td>
                                                                    <td style="width:90px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;padding:3px 0;" align="center">CE</td>
                                                                    <td style="width:90px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;padding:3px 0;" align="center">CNE</td>
                                                                    <td style="width:25px; border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;padding:3px 0;" align="center">&nbsp;</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div id="layer_chart_ranking_contacto" class="ui-state-active ui-corner-all" style="width:1000px;height:400px;margin-top:10px;">
                                                        </div>
                                                    </div>
                                                    <div id="layer_Form_ranking_pago" class="ui-widget-content" style="display:none; padding:5px 0; width:100%;height:420px;overflow:auto;" align="center">
                                                        <div>
                                                            <table>
                                                                <tr valign="top">
                                                                    <td>
                                                                        <div>
                                                                            <div class="ui-widget-header ui-corner-top" style="padding:2px 0;width:320px;">
                                                                                <table width="320" cellpadding="0">
                                                                                    <tr>
                                                                                        <td><font color="#FFFFFF"><b>Carteras</b></font></td>
                                                                                        <td align="right"><input  id="sel_all_ranking_pago" type="checkbox" onClick="checked_all(this.checked,'tbRKCartera_ranking_pago')" />&nbsp;</td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                            <div style="overflow:auto;width:320px;">
                                                                                <table id="tbRKCartera_ranking_pago" cellpadding="0" cellspacing="0" border="0"></table>
                                                                            </div>
                                                                            <div class="ui-widget-header ui-corner-bottom" style="padding:2px 0;height:20px;width:320px;"></div>
                                                                        </div>
                                                                    </td>
                                                                    <td align="left">
                                                                        <div>
                                                                            <div>
                                                                                <label>Campa&ntilde;a</label>
                                                                                <select onChange="load_ranking_cartera_tb_rpte_rank(this.value,'tbRKCartera_ranking_pago');des_checked('sel_all_ranking_pago')" id="cbRKCampania_pago" class="combo"><option value="0">--Seleccione--</option></select>
                                                                            </div>
                                                                            <div>                                        
                                                                                <button onClick="load_ranking_pago()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-refresh"></span></button><button onClick="load_ranking_pago_rpte()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-extlink"></span></button>
                                                                            </div>    
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div>
                                                            <table cellpadding="0" cellspacing="0" border="0" id="table_ranking_pago"></table>
                                                        </div>
                                                        <div id="layer_chart_ranking_pago" class="ui-state-active ui-corner-all" style="width:1000px;height:400px;margin-top:10px;">
                                                        </div>
                                                    </div>
                                                    <div id="layer_Form_ranking_estado" class="ui-widget-content" style="display:none; padding:5px 0; width:100%;height:420px;overflow:auto;" align="center">
                                                        <div>
                                                            <table>
                                                                <tr valign="top">
                                                                    <td>
                                                                        <div>
                                                                            <div class="ui-widget-header ui-corner-top" style="padding:2px 0;width:320px;">
                                                                                <table width="320" cellpadding="0">
                                                                                    <tr>
                                                                                        <td><font color="#FFFFFF"><b>Carteras</b></font></td>
                                                                                        <td align="right"><input  id="sel_all_ranking_estado" type="checkbox" onClick="checked_all(this.checked,'tbRKCartera_ranking_estado')" />&nbsp;</td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                            <div style="overflow:auto;width:320px;">
                                                                                <table id="tbRKCartera_ranking_estado" cellpadding="0" cellspacing="0" border="0"></table>
                                                                            </div>
                                                                            <div class="ui-widget-header ui-corner-bottom" style="padding:2px 0;height:20px;width:320px;"></div>
                                                                        </div>
                                                                    </td>
                                                                    <td align="left">
                                                                        <table>
                                                                            <tr>
                                                                                <td colspan="2">
                                                                                    <label>Campa&ntilde;a</label><select onChange="load_ranking_cartera_tb_rpte_rank(this.value,'tbRKCartera_ranking_estado');des_checked('sel_all_ranking_estado')" id="cbRKCampania_estado" class="combo"><option value="0">--Seleccione--</option></select>
                                                                                </td>
                                                                            </tr>         
                                                                            <tr>
                                                                                <td width="120">
                                                                                    <label>A&ntilde;o </label><select class="combo" id="cbAnio_estado"></select>
                                                                                </td>
                                                                                <td>
                                                                                    <label>Mes </label><select class="combo" id="cbMes_estado"></select>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <label>DiaI </label><select class="combo" id="cbDiaI_estado"></select>
                                                                                </td>
                                                                                <td>
                                                                                    <label>DiaF </label><select class="combo" id="cbDiaF_estado"></select>
                                                                                </td>
                                                                            </tr>    
                                                                            <tr>
                                                                                <td colspan="2">
                                                                                    <button onClick="load_ranking_estado()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-refresh"></span></button>
                                                                                    <button onClick="load_ranking_estado_rpte()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-extlink"></span></button>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div>
                                                            <table cellpadding="0" cellspacing="0" border="0" id="table_ranking_estado"></table>
                                                        </div>
                                                        <!--<div id="layer_chart_ranking_estado" class="ui-state-active ui-corner-all" style="width:750px;height:400px;margin-top:10px;">
                                                        </div>-->
                                                    </div>
                                                    <div id="layer_Form_ranking_abonado_llamadas" class="ui-widget-content" style="display:none; padding:5px 0; width:100%;height:420px;overflow:auto;" align="center">
                                                        <div>
                                                            <table>
                                                                <tr valign="top">
                                                                    <td>
                                                                        <div>
                                                                            <div class="ui-widget-header ui-corner-top" style="padding:2px 0;width:320px;">
                                                                                <table width="320" cellpadding="0">
                                                                                    <tr>
                                                                                        <td><font color="#FFFFFF"><b>Carteras</b></font></td>
                                                                                        <td align="right"><input  id="sel_all_ranking_abonado_llamadas" type="checkbox" onClick="checked_all(this.checked,'tbRKCartera_ranking_abonado_llamadas')" />&nbsp;</td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                            <div style="overflow:auto;width:320px;">
                                                                                <table id="tbRKCartera_ranking_abonado_llamadas" cellpadding="0" cellspacing="0" border="0"></table>
                                                                            </div>
                                                                            <div class="ui-widget-header ui-corner-bottom" style="padding:2px 0;height:20px;width:320px;"></div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <table>
                                                                            <tr>
                                                                                <td colspan="2">
                                                                                    <label>Campa&ntilde;a</label><select onChange="load_ranking_cartera_tb_rpte_rank(this.value,'tbRKCartera_ranking_abonado_llamadas');des_checked('sel_all_ranking_abonado_llamadas')" id="cbRKCampania_abonado_llamadas" class="combo"><option value="0">--Seleccione--</option></select>
                                                                                </td>
                                                                            </tr>         
                                                                            <tr>
                                                                                <td width="120">
                                                                                    <label>A&ntilde;o </label><select class="combo" id="cbAnio_abonado_llamada"></select>
                                                                                </td>
                                                                                <td>
                                                                                    <label>Mes </label><select class="combo" id="cbMes_abonado_llamada"></select>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <label>DiaI </label><select class="combo" id="cbDiaI_abonado_llamada"></select>
                                                                                </td>
                                                                                <td>
                                                                                    <label>DiaF </label><select class="combo" id="cbDiaF_abonado_llamada"></select>
                                                                                </td>
                                                                            </tr>    
                                                                            <tr>
                                                                                <td colspan="2">
                                                                                    <button onClick="load_ranking_abonado_llamada()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-refresh"></span></button>
                                                                                    <button onClick="load_ranking_abonado_llamada_rpte()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-extlink"></span></button>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div>
                                                            <table cellpadding="0" cellspacing="0" border="0" id="table_ranking_abonado_llamada"></table>
                                                        </div>
                                                        <!--<div id="layer_chart_ranking_abonado_llamada" class="ui-state-active ui-corner-all" style="width:750px;height:400px;margin-top:10px;">
                                                        </div>-->
                                                    </div>
                                                    <div id="layer_Form_ranking_llamadas_hora" class="ui-widget-content" style="display:none; padding:5px 0; width:100%;height:420px;overflow:auto;" align="center">
                                                        <div>
                                                            <table>
                                                                <tr valign="top">
                                                                    <td>
                                                                        <div>
                                                                            <div class="ui-widget-header ui-corner-top" style="padding:2px 0;width:320px;">
                                                                                <table width="320" cellpadding="0">
                                                                                    <tr>
                                                                                        <td><font color="#FFFFFF"><b>Carteras</b></font></td>
                                                                                        <td align="right"><input  id="sel_all_ranking_llamadas_hora" type="checkbox" onClick="checked_all(this.checked,'tbRKCartera_ranking_llamadas_hora')" />&nbsp;</td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                            <div style="overflow:auto;width:320px;">
                                                                                <table id="tbRKCartera_ranking_llamadas_hora" cellpadding="0" cellspacing="0" border="0"></table>
                                                                            </div>
                                                                            <div class="ui-widget-header ui-corner-bottom" style="padding:2px 0;height:20px;width:320px;"></div>
                                                                        </div>
                                                                    </td>
                                                                    <td valign="top"><br><br>
                                                                        <table>
                                                                            <tr>
                                                                                <td>Campa&ntilde;a
                                                                                </td>
                                                                                <td><select onChange="load_ranking_cartera_tb_rpte_rank(this.value,'tbRKCartera_ranking_llamadas_hora');des_checked('sel_all_ranking_llamadas_hora')" id="cbRKCampania_llamada_hora" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Fecha Inicio</td>
                                                                                <td><input style="width:70px;" type="text" readonly="readonly" class="cajaForm" id="txtRKFechaInicio_llamada_hora" /></td>
                                                                                <td>Fecha Fin
                                                                                </td>
                                                                                <td><input style="width:70px;" type="text" readonly="readonly" class="cajaForm" id="txtRKFechaFin_llamada_hora" />
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><button onClick="load_ranking_llamada_hora()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-refresh"></span></button>
                                                                                </td>
                                                                                <td><button onClick="load_ranking_llamada_hora_rpte()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-refresh"></span></button>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div>
                                                            <table cellpadding="0" cellspacing="0" border="0" id="table_ranking_llamada_hora"></table>
                                                        </div>
                                                    </div>
                                                    <div id="layer_Form_ranking_llamadas_hora_detalle" class="ui-widget-content" style="display:none; padding:5px 0; width:100%;height:420px;overflow:auto;" align="center">
                                                        <div>
                                                            <table>
                                                                <tr valign="top">
                                                                    <td>
                                                                        <div>
                                                                            <div class="ui-widget-header ui-corner-top" style="padding:2px 0;width:320px;">
                                                                                <table width="320" cellpadding="0">
                                                                                    <tr>
                                                                                        <td><font color="#FFFFFF"><b>Carteras</b></font></td>
                                                                                        <td align="right"><input  id="sel_all_ranking_llamadas_hora_detalle" type="checkbox" onClick="checked_all(this.checked,'tbRKCartera_ranking_llamadas_hora_detalle')" />&nbsp;</td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                            <div style="overflow:auto;width:320px;">
                                                                                <table id="tbRKCartera_ranking_llamadas_hora_detalle" cellpadding="0" cellspacing="0" border="0"></table>
                                                                            </div>
                                                                            <div class="ui-widget-header ui-corner-bottom" style="padding:2px 0;height:20px;width:320px;"></div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <table>
                                                                            <tr>
                                                                                <td colspan="2">
                                                                                    <label>Campa&ntilde;a</label><select onChange="load_ranking_cartera_tb_rpte_rank(this.value,'tbRKCartera_ranking_llamadas_hora_detalle');des_checked('sel_all_ranking_llamadas_hora_detalle')" id="cbRKCampania_llamadas_hora_detalle" class="combo"><option value="0">--Seleccione--</option></select>
                                                                                </td>
                                                                            </tr>         
                                                                            <tr>
                                                                                <td width="120">
                                                                                    <label>A&ntilde;o </label><select class="combo" id="cbAnio_llamada_hora_detalle"></select>
                                                                                </td>
                                                                                <td>
                                                                                    <label>Mes </label><select class="combo" id="cbMes_llamada_hora_detalle"></select>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <label>DiaI </label><select class="combo" id="cbDiaI_llamada_hora_detalle"></select>
                                                                                </td>
                                                                                <td>
                                                                                    <label>DiaF </label><select class="combo" id="cbDiaF_llamada_hora_detalle"></select>
                                                                                </td>
                                                                            </tr>    
                                                                            <tr>
                                                                                <td colspan="2">
                                                                                    <button onClick="load_ranking_llamada_hora_detalle()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-refresh"></span></button>
                                                                                    <button onClick="load_ranking_llamada_hora_detalle_rpte()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-extlink"></span></button>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div>
                                                            <table cellpadding="0" cellspacing="0" border="0" id="table_ranking_llamada_hora_detalle"></table>
                                                        </div>
                                                    </div>
                                                    <div id="layer_Form_ranking_visita" class="ui-widget-content" style="display:none; padding:5px 0; width:100%;height:420px;overflow:auto;" align="center">
                                                        <div>
                                                            <table>
                                                                <tr valign="top">
                                                                    <td>
                                                                        <div>
                                                                            <div class="ui-widget-header ui-corner-top" style="padding:2px 0;width:320px;">
                                                                                <table width="320" cellpadding="0">
                                                                                    <tr>
                                                                                        <td><font color="#FFFFFF"><b>Carteras</b></font></td>
                                                                                        <td align="right"><input  id="sel_all_ranking_visita" type="checkbox" onClick="checked_all(this.checked,'tbRKCartera_ranking_visita')" />&nbsp;</td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                            <div style="overflow:auto;width:320px;">
                                                                                <table id="tbRKCartera_ranking_visita" cellpadding="0" cellspacing="0" border="0"></table>
                                                                            </div>
                                                                            <div class="ui-widget-header ui-corner-bottom" style="padding:2px 0;height:20px;width:320px;"></div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <table>
                                                                            <tr>
                                                                                <td colspan="2">
                                                                                    <label>Campa&ntilde;a</label><select onChange="load_ranking_cartera_tb_rpte_rank(this.value,'tbRKCartera_ranking_visita');des_checked('sel_all_ranking_visita')" id="cbRKCampania_visita" class="combo"><option value="0">--Seleccione--</option></select>
                                                                                </td>
                                                                            </tr>         
                                                                            <tr>
                                                                                <td width="120">
                                                                                    <label>A&ntilde;o </label><select class="combo" id="cbAnio_visita"></select>
                                                                                </td>
                                                                                <td>
                                                                                    <label>Mes </label><select class="combo" id="cbMes_visita"></select>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <label>DiaI </label><select class="combo" id="cbDiaI_visita"></select>
                                                                                </td>
                                                                                <td>
                                                                                    <label>DiaF </label><select class="combo" id="cbDiaF_visita"></select>
                                                                                </td>
                                                                            </tr>    
                                                                            <tr>
                                                                                <td colspan="2">
                                                                                    <button onClick="load_ranking_visita()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-refresh"></span></button>
                                                                                    <button onClick="load_ranking_visita_rpte()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-extlink"></span></button>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div>
                                                            <table cellpadding="0" cellspacing="0" border="0" id="table_ranking_visita"></table>
                                                        </div>
                                                    </div>
                                                    <div id="layer_Form_ranking_semaforo" class="ui-widget-content" style="display:none; padding:5px 0; width:100%;height:420px;overflow:auto;" align="center">
                                                        <div>
                                                            <table border="0">
                                                                <tr valign="top">
                                                                    <td>
                                                                        <div>
                                                                            <div class="ui-widget-header ui-corner-top" style="padding:2px 0;width:320px;">
                                                                                <table width="320" cellpadding="0">
                                                                                    <tr>
                                                                                        <td><font color="#FFFFFF"><b>Carteras</b></font></td>
                                                                                        <td align="right"><input  id="sel_all_ranking_semaforo" type="checkbox" onClick="checked_all(this.checked,'tbRKCartera_ranking_semaforo')" />&nbsp;</td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                            <div style="overflow:auto;width:320px;">
                                                                                <table id="tbRKCartera_ranking_semaforo" cellpadding="0" cellspacing="0" border="0"></table>
                                                                            </div>
                                                                            <div class="ui-widget-header ui-corner-bottom" style="padding:2px 0;height:20px;width:320px;"></div>
                                                                        </div>
                                                                    </td>
                                                                    <td valign="top"><br><br>
                                                                        <table>
                                                                            <tr>
                                                                                <td>Campa&ntilde;a
                                                                                </td>
                                                                                <td><select onChange="load_ranking_cartera_tb_rpte_rank(this.value,'tbRKCartera_ranking_semaforo');des_checked('sel_all_ranking_semaforo')" id="cbRKCampania_semaforo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Fecha Inicio</td>
                                                                                <td><input style="width:70px;" type="text" readonly="readonly" class="cajaForm" id="txtRKFechaInicio_semaforo" /></td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Fecha Fin
                                                                                </td>
                                                                                <td><input style="width:70px;" type="text" readonly="readonly" class="cajaForm" id="txtRKFechaFin_semaforo" />
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><button onClick="load_ranking_semaforo()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-refresh"></span></button>
                                                                                </td>
                                                                                <td><button onClick="load_ranking_semaforo_rpte()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-refresh"></span></button>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                    <td>
                                                                        <div align="left">
                                                                            <table cellpadding="0" cellspacing="0" border="0" style="margin-left:30">
                                                                                <caption>Rangos</caption>
                                                                                <tr>
                                                                                    <td style="width:80px;">Mayor a 25</td>
                                                                                    <td align="center" style="background:#00B050;color:#FF0;">VERDE</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="width:80px;">de 20 a 24</td>
                                                                                    <td align="center" style="background-color:#FFFF00;color:#F00;">AMARILLO</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="width:80px;">Menor a 20</td>
                                                                                    <td align="center" style="background-color:#FF0000;color:#00B050;">ROJO</td>
                                                                                </tr>
                                                                            </table>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div>
                                                            <table cellpadding="0" cellspacing="0" border="0" id="table_ranking_semaforo"></table>
                                                        </div>
                                                    </div>
                                                    <div id="layer_Form_ranking_cp" class="ui-widget-content" style="display:none; padding:5px 0; width:100%;height:420px;overflow:auto;" align="center">
                                                        <div>
                                                            <table>
                                                                <tr valign="top">
                                                                    <td>
                                                                        <div>
                                                                            <div class="ui-widget-header ui-corner-top" style="padding:2px 0;width:320px;">
                                                                                <table width="320" cellpadding="0">
                                                                                    <tr>
                                                                                        <td><font color="#FFFFFF"><b>Carteras</b></font></td>
                                                                                        <td align="right"><input  id="sel_all_ranking_cp" type="checkbox" onClick="checked_all(this.checked,'tbRKCartera_ranking_cp')" />&nbsp;</td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                            <div style="overflow:auto;width:320px;">
                                                                                <table id="tbRKCartera_ranking_cp" cellpadding="0" cellspacing="0" border="0"></table>
                                                                            </div>
                                                                            <div class="ui-widget-header ui-corner-bottom" style="padding:2px 0;height:20px;width:320px;"></div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <table>
                                                                            <tr>
                                                                                <td colspan="2">
                                                                                    <label>Campa&ntilde;a</label><select onChange="load_ranking_cartera_tb_rpte_rank(this.value,'tbRKCartera_ranking_cp');des_checked('sel_all_ranking_cp')" id="cbRKCampania_cp" class="combo"><option value="0">--Seleccione--</option></select>
                                                                                </td>
                                                                            </tr>         
                                                                            <tr>
                                                                                <td width="120">
                                                                                    <label>A&ntilde;o </label><select class="combo" id="cbAnio_cp"></select>
                                                                                </td>
                                                                                <td>
                                                                                    <label>Mes </label><select class="combo" id="cbMes_cp"></select>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <label>DiaI </label><select class="combo" id="cbDiaI_cp"></select>
                                                                                </td>
                                                                                <td>
                                                                                    <label>DiaF </label><select class="combo" id="cbDiaF_cp"></select>
                                                                                </td>
                                                                            </tr>    
                                                                            <tr>
                                                                                <td colspan="2">
                                                                                    <button onClick="load_ranking_compromiso_pago()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-refresh"></span></button>
                                                                                    <button onClick="load_ranking_compromiso_pago_rpte()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-extlink"></span></button>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div>
                                                            <table cellpadding="0" cellspacing="0" border="0" id="table_ranking_cp"></table>
                                                        </div>
                                                        <div id="layer_chart_cp" class="ui-state-active ui-corner-all" style="width:850px;height:300px;margin-top:10px;">
                                                        </div>
                                                    </div>
                                                    
                                                    <div id="layer_Form_ranking_carga_fecha" class="ui-widget-content" style="display:none; padding:5px 0; width:100%;height:420px;overflow:auto;" align="center" >
                                                        
                                                        <div>
                                                            <table>
                                                                <tr valign="top">
                                                                    <td>
                                                                        <div>
                                                                            <div class="ui-widget-header ui-corner-top" style="padding:2px 0;width:320px;">
                                                                                <table width="320" cellpadding="0">
                                                                                    <tr>
                                                                                        <td><font color="#FFFFFF"><b>Carteras</b></font></td>
                                                                                        <td align="right"><input  id="sel_all_ranking_carga_fecha" type="checkbox" onClick="checked_all(this.checked,'tbRKCartera_ranking_carga_fecha')" />&nbsp;</td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                            <div style="overflow:auto;width:320px;">
                                                                                <table id="tbRKCartera_ranking_carga_fecha" cellpadding="0" cellspacing="0" border="0"></table>
                                                                            </div>
                                                                            <div class="ui-widget-header ui-corner-bottom" style="padding:2px 0;height:20px;width:320px;"></div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <table>
                                                                            <tr>
                                                                                <td colspan="2">
                                                                                    <label>Campa&ntilde;a</label><select onChange="load_ranking_cartera_tb_rpte_rank(this.value,'tbRKCartera_ranking_carga_fecha');des_checked('sel_all_ranking_carga_fecha')" id="cbRKCampania_carga_fecha" class="combo"><option value="0">--Seleccione--</option></select>
                                                                                    <label>Tipo</label><select id="cbRKTipo_carga_fecha" class="combo"><option value="gestion">GESTION</option><option value="distribucion">DISTRIBUCION</option></select>
                                                                                </td>
                                                                            </tr>         
                                                                            <tr>
                                                                                <td width="120">
                                                                                    <label>A&ntilde;o </label><select class="combo" id="cbAnio_carga_fecha"></select>
                                                                                </td>
                                                                                <td>
                                                                                    <label>Mes </label><select class="combo" id="cbMes_carga_fecha"></select>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <label>DiaI </label><select class="combo" id="cbDiaI_carga_fecha"></select>
                                                                                </td>
                                                                                <td>
                                                                                    <label>DiaF </label><select class="combo" id="cbDiaF_carga_fecha"></select>
                                                                                </td>
                                                                            </tr>    
                                                                            <tr>
                                                                                <td colspan="2">
                                                                                    <button onClick="load_ranking_carga_fecha()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-refresh"></span></button>
                                                                                    <button onClick="load_ranking_carga_fecha_rpte()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-extlink"></span></button>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div style="height:250px;width:950px;overflow:auto;">
                                                            <table cellpadding="0" cellspacing="0" border="0" id="table_ranking_carga_fecha"></table>
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
                                                <td>
                                                    <div style="margin-left:50px;">
                                                        <table id="table_tab_ranking_botttom" cellpadding="0" cellspacing="0" border="0">
                                                            <tr>
                                                                <td><div onClick="_activeTabLayer('table_tab_ranking_botttom','tab_ranking_bottom_',this,'content_ranking_bottom','layer_Form_ranking_','layer_Form_ranking_contacto')" id="tab_ranking_bottom_contacto" class="itemTabActive border-radius-bottom pointer ui-widget-header" style="margin: 0px 1px 0pt 0pt;" ><div>Por Contacto</div></div></td>
                                                                <td><div onClick="_activeTabLayer('table_tab_ranking_botttom','tab_ranking_bottom_',this,'content_ranking_bottom','layer_Form_ranking_','layer_Form_ranking_pago')" id="tab_ranking_bottom_pago" class="itemTabActive border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;" ><div>Pagos</div></div></td>
                                                                <td><div onClick="_activeTabLayer('table_tab_ranking_botttom','tab_ranking_bottom_',this,'content_ranking_bottom','layer_Form_ranking_','layer_Form_ranking_estado')" id="tab_ranking_bottom_estado" class="itemTabActive border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;" ><div>Por Estado</div></div></td>
                                                                <td><div onClick="_activeTabLayer('table_tab_ranking_botttom','tab_ranking_bottom_',this,'content_ranking_bottom','layer_Form_ranking_','layer_Form_ranking_abonado_llamadas')" id="tab_ranking_bottom_abonado_llamada" class="itemTabActive border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;" ><div>Abonados y Llamadas</div></div></td>
                                                                <td><div onClick="_activeTabLayer('table_tab_ranking_botttom','tab_ranking_bottom_',this,'content_ranking_bottom','layer_Form_ranking_','layer_Form_ranking_llamadas_hora')" id="tab_ranking_bottom_llamada_hora" class="itemTabActive border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;" ><div>Llamadas por Hora</div></div></td>
                                                                <td><div onClick="_activeTabLayer('table_tab_ranking_botttom','tab_ranking_bottom_',this,'content_ranking_bottom','layer_Form_ranking_','layer_Form_ranking_llamadas_hora_detalle')" id="tab_ranking_bottom_llamada_hora_detalle" class="itemTabActive border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;" ><div>Llamadas por Hora Detalle</div></div></td>
                                                                <td><div onClick="_activeTabLayer('table_tab_ranking_botttom','tab_ranking_bottom_',this,'content_ranking_bottom','layer_Form_ranking_','layer_Form_ranking_visita')" id="tab_ranking_bottom_visita" class="itemTabActive border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;" ><div>Visitas</div></div></td>
                                                                <td><div onClick="_activeTabLayer('table_tab_ranking_botttom','tab_ranking_bottom_',this,'content_ranking_bottom','layer_Form_ranking_','layer_Form_ranking_semaforo')" id="tab_ranking_bottom_semaforo" class="itemTabActive border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;" ><div>Semaforo</div></div></td>
                                                                <td><div onClick="_activeTabLayer('table_tab_ranking_botttom','tab_ranking_bottom_',this,'content_ranking_bottom','layer_Form_ranking_','layer_Form_ranking_cp')" id="tab_ranking_bottom_cp" class="itemTabActive border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;" ><div>CP</div></div></td>
                                                                <td><div onClick="_activeTabLayer('table_tab_ranking_botttom','tab_ranking_bottom_',this,'content_ranking_bottom','layer_Form_ranking_','layer_Form_ranking_carga_fecha')" id="tab_ranking_bottom_carga_fecha" class="itemTabActive border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;" ><div>Por Carga y Fecha</div></div></td>
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
                    <div id="panelRankingOperadorFija" class="ui-widget-content" style="border:0 none;width:100%;display:none;height:100%;" align="center">
                        <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                            <tr>
                                <td align="center" valign="top">
                                    <div style="height:100%"; width:"100%">  
                                         <table cellpadding="0" cellspacing="0" border="0" style="width:100%; height:90%">
                                            <tr>
                                                <td id="content_ranking_fija_bottom">
                                                    <div id="layer_Form_ranking_fija_estado" class="ui-widget-content" style="display:block; padding:5px 0; width:100%;height:420px;overflow:auto;" align="center">
                                                        <div>
                                                            <table>
                                                                <tr valign="top">
                                                                    <td>
                                                                        <div>
                                                                            <div class="ui-widget-header ui-corner-top" style="padding:2px 0;width:320px;">
                                                                                <table width="320" cellpadding="0">
                                                                                    <tr>
                                                                                        <td><font color="#FFFFFF"><b>Carteras</b></font></td>
                                                                                        <td align="right" id="sel_all_fija_estado" ><input type="checkbox" onClick="checked_all_carteras_asignar_fija_estado(this.checked)" />&nbsp;</td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                            <div style="overflow:auto;width:320px;">
                                                                                <table id="tbRKCartera_fija_estado" cellpadding="0" cellspacing="0" border="0"></table>
                                                                            </div>
                                                                            <div class="ui-widget-header ui-corner-bottom" style="padding:2px 0;height:20px;width:320px;"></div>
                                                                        </div>
                                                                    </td>
                                                                    <td valign="top">
                                                                        <table>
                                                                            <tr>
                                                                                <td align="left">Campa&ntilde;a</td>
                                                                                <td align="left"><select onChange="load_ranking_cartera_tb_rpte_rank(this.value,'tbRKCartera_fija_estado');des_checked_fija_estado()" id="cbRKCampania_fija_estado" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <!--<td align="left">Cartera</td>
                                                                                <td align="left"><select id="cbRKCartera_fija_estado" class="combo"><option value="0">--Seleccione--</option></select></td>-->
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left">Anio</td>
                                                                                <td align="left"><select class="combo" id="cbAnio_fija_estado"></select></td>
                                                                                <td align="left">Mes</td>
                                                                                <td align="left"><select class="combo" id="cbMes_fija_estado"></select></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left">DiaI</td>
                                                                                <td align="left"><select class="combo" id="cbDiaI_fija_estado"></select></td>
                                                                                <td align="left">DiaF</td>
                                                                                <td align="left"><select class="combo" id="cbDiaF_fija_estado"></select></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left"><button onClick="loda_ranking_fija_contactabilidad_diario()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-refresh"></span></button></td>
                                                                                <td align="left"><button onClick="loda_ranking_fija_contactabilidad_diario_rpte_xls_jc()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-extlink"></span></button></td>
                                                                                <td></td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                               	</tr>
                                                            </table>
                                                        </div>
                                                        <div>
                                                            <table>
                                                                <tr>
                                                                    <td>
                                                                        <table cellpadding="0" cellspacing="0" border="0" id="table_ranking_fija_estado"></table>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <table cellpadding="0" cellspacing="0" border="0" id="table_ranking_fija_estado_porcentaje"></table>
                                                                    </td>
                                                               	</tr>
                                                            </table>
                                                        </div>
                                                        <div id="layer_chart_cont_diaria_fija">
                                                        </div>
                                                    </div>
                                                    <div id="layer_Form_ranking_fija_respuesta_gestion" class="ui-widget-content" style="display:none; padding:5px 0; width:100%;height:420px;overflow:auto;" align="center">
                                                        <div>
                                                            <table>
                                                                <tr valign="top">
                                                                    <td>
                                                                        <div>
                                                                            <div class="ui-widget-header ui-corner-top" style="padding:2px 0;width:320px;">
                                                                                <table width="320" cellpadding="0">
                                                                                    <tr>
                                                                                        <td><font color="#FFFFFF"><b>Carteras</b></td>
                                                                                        <td align="right" id="sel_all_fija_rpt_gest" ><input type="checkbox" onClick="checked_all_carteras_asignar_fija_rpt_gest(this.checked)" />&nbsp;</td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                            <div style="overflow:auto;width:320px;">
                                                                                <table id="tbRKCartera_fija_rpt_gest" cellpadding="0" cellspacing="0" border="0"></table>
                                                                            </div>
                                                                            <div class="ui-widget-header ui-corner-bottom" style="padding:2px 0;height:20px;width:320px;"></div>
                                                                        </div>
                                                                    </td>
                                                                    <td valign="top">
                                                                        <table>
                                                                            <tr>
                                                                                <td align="left">Campa&ntilde;a</td>
                                                                                <td align="left"><select onChange="load_ranking_cartera_tb_rpte_rank(this.value,'tbRKCartera_fija_rpt_gest');des_checked_fija_rpt_gest()" id="cbRKCampania_fija_rpt_gest" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left">Anio</td>
                                                                                <td align="left"><select class="combo" id="cbAnio_fija_rpt_gest"></select></td>
                                                                                <td align="left">Mes</td>
                                                                                <td align="left"><select class="combo" id="cbMes_fija_rpt_gest"></select></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left">DiaI</td>
                                                                                <td align="left"><select class="combo" id="cbDiaI_fija_rpt_gest"></select></td>
                                                                                <td align="left">DiaF</td>
                                                                                <td align="left"><select class="combo" id="cbDiaF_fija_rpt_gest"></select></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left"><button onClick="load_ranking_fija_respuesta_gestion()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-refresh"></span></button></td>
                                                                                <td align="left"><button onClick="load_ranking_fija_respuesta_gestion_rpte_xls_jc()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-extlink"></span></button></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div>
                                                            <table cellpadding="0" cellspacing="0" border="0" id="table_ranking_fija_rpt_gest"></table>
                                                        </div>
                                                    </div>
                                                    <div id="layer_Form_ranking_fija_contactabilidad_hora" class="ui-widget-content" style="display:none; padding:5px 0; width:100%;height:420px;overflow:auto;" align="center">
                                                        <div>
                                                            <table>
                                                                <tr valign="top">
                                                                    <td>
                                                                        <div>
                                                                            <div class="ui-widget-header ui-corner-top" style="padding:2px 0;width:320px;">
                                                                                <table width="320" cellpadding="0">
                                                                                    <tr>
                                                                                        <td><font color="#FFFFFF"><b>Carteras</b></td>
                                                                                        <td align="right" id="sel_all_fija_cont_hora" ><input type="checkbox" onClick="checked_all_carteras_asignar_fija_cont_hora(this.checked)" />&nbsp;</td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                            <div style="overflow:auto;width:320px;">
                                                                                <table id="tbRKCartera_fija_cont_hora" cellpadding="0" cellspacing="0" border="0"></table>
                                                                            </div>
                                                                            <div class="ui-widget-header ui-corner-bottom" style="padding:2px 0;height:20px;width:320px;"></div>
                                                                        </div>
                                                                    </td>
                                                                    <td valign="top">
                                                                        <table>
                                                                            <tr>
                                                                                <td align="left">Campa&ntilde;a</td>
                                                                                <td align="left"><select onChange="load_ranking_cartera_tb_rpte_rank(this.value,'tbRKCartera_fija_cont_hora');des_checked_fija_cont_hora()" id="cbRKCampania_fija_cont_hora" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <!--<td align="left">Cartera</td>
                                                                                <td align="left"><select id="cbRKCartera_fija_cont_hora" class="combo"><option value="0">--Seleccione--</option></select></td>-->
                                                                            </tr>
                                                                            <tr>     
                                                                                <td align="left">Fecha Inicio</td>
                                                                                <td align="left"><input readonly="readonly" style="width:70px;" type="text" class="cajaForm" id="txtRKFecha_inicio_cont_hora" /></td>
                                                                                <td align="left">Fecha Fin</td>
                                                                                <td align="left"><input readonly="readonly" style="width:70px;" type="text" class="cajaForm" id="txtRKFecha_fin_cont_hora" /></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left"><button onClick="load_ranking_fija_contactabilidad_hora()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-refresh"></span></button></td>
                                                                                <td align="left"><button onClick="loda_ranking_fija_contactabilidad_hora_rpte_xls_jc()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-extlink"></span></button></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div>
                                                            <table>
                                                                <tr>
                                                                    <td valign="top">
                                                                        <table cellpadding="0" cellspacing="0" border="0" id="table_ranking_fija_cont_hora"></table>
                                                                    </td>
                                                                    <td valign="top">
                                                                        <table cellpadding="0" cellspacing="0" border="0" id="table_ranking_fija_cont_hora_por"></table>
                                                                    </td>
                                                               	</tr>
                                                            </table>
                                                        </div>
                                                        <div id="layer_chart_ranking_fija_cont_hora_por" class="ui-state-active ui-corner-all" style="width:900px;height:300px;margin-top:10px;">
                                                        </div>
                                                        <div id="layer_chart_ranking_fija_cont_hora_por_2" class="ui-state-active ui-corner-all" style="width:900px;height:300px;margin-top:10px;">
                                                        </div>
                                                    </div>
                                                    <div id="layer_Form_ranking_fija_semaforo" class="ui-widget-content" style="display:none; padding:5px 0; width:100%;height:420px;overflow:auto;" align="center">
                                                        <div>
                                                            <table>
                                                                <tr>
                                                                    <td valign="top">
                                                                        <div>
                                                                            <div class="ui-widget-header ui-corner-top" style="padding:2px 0;width:320px;">
                                                                                <table width="320" cellpadding="0">
                                                                                    <tr>
                                                                                        <td><font color="#FFFFFF"><b>Carteras</b></td>
                                                                                        <td align="right" id="sel_all_fija_semaforo" ><input type="checkbox" onClick="checked_all_carteras_asignar_fija_semaforo(this.checked)" />&nbsp;</td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                            <div style="overflow:auto;width:320px;">
                                                                                <table id="tbRKCartera_fija_semaforo" cellpadding="0" cellspacing="0" border="0"></table>
                                                                            </div>
                                                                            <div class="ui-widget-header ui-corner-bottom" style="padding:2px 0;height:20px;width:320px;"></div>
                                                                        </div>
                                                                    </td>
                                                                    <td valign="top">
                                                                        <table>
                                                                            <tr>
                                                                                <td align="left">Campa&ntilde;a</td>
                                                                                <td align="left"><select onChange="load_ranking_cartera_tb_rpte_rank(this.value,'tbRKCartera_fija_semaforo');des_checked_fija_semaforo()" id="cbRKCampania_fija_semaforo" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="2">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <div class="ui-widget-header ui-corner-top" style="padding:2px 0;">Valores</div>
                                                                                                <table id="tbRK_Carga_Cartera_fija_semaforo" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                                <div class="ui-widget-header ui-corner-bottom" style="height:20px;"></div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                    <td valign="top">
                                                                        <table>
                                                                            <tr>     
                                                                               	<td align="left">Fecha Inicio</td>
                                                                                <td align="left"><input readonly="readonly" style="width:70px;" type="text" class="cajaForm" id="txtRKFecha_inicio_fija_semaforo" /></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left">Fecha Fin</td>
                                                                                <td align="left"><input readonly="readonly" style="width:70px;" type="text" class="cajaForm" id="txtRKFecha_fin_fija_semaforo" /></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left">Meta</td>
                                                                                <td><input type="text" value="25" class="cajaForm" style="width:50px;" id="txtRK_meta_fija_semaforo" /></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left"><button onClick="load_ranking_semaforo_fija()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-refresh"></span></button></td>
                                                                                <td align="left"><button onClick="load_ranking_semaforo_fija_rpte_xls_jc()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-extlink"></span></button></td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div>
                                                            <table>
                                                            <!--<tr>
                                                                    <td>
                                                                            <table cellpadding="0" cellspacing="0" border="0" id="table_ranking_fija_semaforo"></table>
                                                                	</td>
                                                               	</tr>-->
                                                                <tr>
                                                                    <td>
                                                                        <table cellpadding="0" cellspacing="0" border="0" id="table_ranking_fija_semaforo_objetivo"></table>
                                                                    </td>
                                                               	</tr>
                                                            </table>
                                                        </div>
                                                        <div id="layer_chart_semaforo_fija">
                                                        </div>
                                                    </div>
                                                    <div id="layer_Form_ranking_fija_cp" class="ui-widget-content" style="display:none; padding:5px 0; width:100%;height:420px;overflow:auto;" align="center">
                                                        <div>
                                                            <table>
                                                                <tr>
                                                                    <td valign="top">
                                                                        <div>
                                                                            <div class="ui-widget-header ui-corner-top" style="padding:2px 0;width:320px;">
                                                                                <table width="320" cellpadding="0">
                                                                                    <tr>
                                                                                        <td><font color="#FFFFFF"><b>Carteras</b></font></td>
                                                                                        <td align="right" ><input  id="sel_all_fija_cp" type="checkbox" onClick="checked_all(this.checked,'tbRKCartera_fija_cp')" />&nbsp;</td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                            <div style="overflow:auto;width:320px;">
                                                                                <table id="tbRKCartera_fija_cp" cellpadding="0" cellspacing="0" border="0"></table>
                                                                            </div>
                                                                            <div class="ui-widget-header ui-corner-bottom" style="padding:2px 0;height:20px;width:320px;"></div>
                                                                        </div>
                                                                    </td>
                                                                    <td valign="top">
                                                                        <table>
                                                                            <tr>
                                                                                <td align="left">Campa&ntilde;a</td>
                                                                                <td align="left"><select onChange="load_ranking_cartera_tb_rpte_rank(this.value,'tbRKCartera_fija_cp');des_checked('sel_all_fija_cp')" id="cbRKCampania_fija_cp" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="2">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <div class="ui-widget-header ui-corner-top" style="padding:2px 0;">Valores</div>
                                                                                                <table id="tbRK_Carga_Cartera_fija_cp" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                                <div class="ui-widget-header ui-corner-bottom" style="height:20px;"></div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                    <td valign="top">
                                                                        <table>
                                                                            <tr>         
                                                                                <td align="left">Anio</td>
                                                                                <td align="left"><select class="combo" id="cbAnio_fija_cp"></select></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left">Mes</td>
                                                                                <td align="left"><select class="combo" id="cbMes_fija_cp"></select></td>
                                                                            </tr>
                                                                            <tr>     
                                                                                <td align="left">DiaI</td>
                                                                                <td align="left"><select class="combo" id="cbDiaI_fija_cp"></select></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left">DiaF</td>
                                                                                <td align="left"><select class="combo" id="cbDiaF_fija_cp"></select></td>
                                                                            </tr>
                                                                            <tr>      
                                                                                <td align="left"><button onClick="load_ranking_fija_compromiso_pago()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-refresh"></span></button></td>
                                                                                <td align="left"><button onClick="load_ranking_fija_compromiso_pago_dia_rpte_xls_jc()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-extlink"></span></button></td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                    <!--<td align="left"><select id="cbRKCartera_fija_cp" class="combo"><option value="0">--Seleccione--</option></select></td>-->
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div>
                                                            <table cellpadding="0" cellspacing="0" border="0" id="table_ranking_fija_cp_dia"></table>
                                                        </div>
                                                    </div>
                                                    <div id="layer_Form_ranking_fija_final" class="ui-widget-content" style="display:none; padding:5px 0; width:100%;height:420px;overflow:auto;" align="center">
                                                        <div>
                                                            <table>
                                                                <tr>
                                                                    <td valign="top">
                                                                        <div>
                                                                            <div class="ui-widget-header ui-corner-top" style="padding:2px 0;width:320px;">
                                                                                <table width="320" cellpadding="0">
                                                                                    <tr>
                                                                                        <td><font color="#FFFFFF"><b>Carteras</b></font></td>
                                                                                        <td align="right"><input id="sel_all_fija_final" type="checkbox" onClick="checked_all(this.checked,'tbRKCartera_fija_final')" />&nbsp;</td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                            <div style="overflow:auto;width:320px;">
                                                                                <table id="tbRKCartera_fija_final" cellpadding="0" cellspacing="0" border="0"></table>
                                                                            </div>
                                                                            <div class="ui-widget-header ui-corner-bottom" style="padding:2px 0;height:20px;width:320px;"></div>
                                                                        </div>
                                                                    </td>
                                                                    <td valign="top">
                                                                        <table>
                                                                            <tr>
                                                                                <td align="left">Campa&ntilde;a</td>
                                                                                <td align="left"><select onChange="load_ranking_cartera_tb_rpte_rank(this.value,'tbRKCartera_fija_final');des_checked('sel_all_fija_final')" id="cbRKCampania_fija_final" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                            </tr>
                                                                            <tr>     
                                                                                <td align="left">Fecha Inicio</td>
                                                                                <td align="left"><input readonly="readonly" style="width:70px;" type="text" class="cajaForm" id="txtRKFecha_inicio_final" /></td>
                                                                                <td align="left">Fecha Fin</td>
                                                                                <td align="left"><input readonly="readonly" style="width:70px;" type="text" class="cajaForm" id="txtRKFecha_fin_final" /></td>
                                                                            </tr>
                                                                            <tr>      
                                                                                <td align="left"><button onClick="load_ranking_fija_final()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-refresh"></span></button></td>
                                                                                <td align="left"><button onClick="load_ranking_fija_final_rpte_xls_jc()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-extlink"></span></button></td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div>
                                                            <table cellpadding="0" cellspacing="0" border="0" id="table_ranking_fija_final"></table>
                                                        </div>
                                                        <!--<div id="layer_chart_ranking_fija_final" class="ui-state-active ui-corner-all" style="width:900px;height:300px;margin-top:10px;">
                                                        </div>-->
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                        <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                            <tr>
                                                <td class="lineTab ui-widget-header"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="margin-left:50px;">
                                                        <table id="table_tab_ranking_fija_botttom" cellpadding="0" cellspacing="0" border="0">
                                                            <tr>
                                                                <td><div onClick="_activeTabLayer('table_tab_ranking_fija_botttom','tab_ranking_fija_bottom_',this,'content_ranking_fija_bottom','layer_Form_ranking_fija_','layer_Form_ranking_fija_estado')" id="tab_ranking_fija_bottom_estado" class="itemTabActive border-radius-bottom pointer ui-widget-header" style="margin: 0px 1px 0pt 0pt;" ><div>Contactabilidad</div></div></td>
                                                                <td><div onClick="_activeTabLayer('table_tab_ranking_fija_botttom','tab_ranking_fija_bottom_',this,'content_ranking_fija_bottom','layer_Form_ranking_fija_','layer_Form_ranking_fija_respuesta_gestion')" id="tab_ranking_fija_bottom_respuesta_gestion" class="itemTabActive border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;" ><div>Respuesta de Gestion</div></div></td>
                                                                <td><div onClick="_activeTabLayer('table_tab_ranking_fija_botttom','tab_ranking_fija_bottom_',this,'content_ranking_fija_bottom','layer_Form_ranking_fija_','layer_Form_ranking_fija_contactabilidad_hora')" id="tab_ranking_fija_bottom_contactabilidad_hora" class="itemTabActive border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;" ><div>Contactabilidad por Hora</div></div></td>
                                                                <td><div onClick="_activeTabLayer('table_tab_ranking_fija_botttom','tab_ranking_fija_bottom_',this,'content_ranking_fija_bottom','layer_Form_ranking_fija_','layer_Form_ranking_fija_semaforo')" id="tab_ranking_fija_bottom_semaforo" class="itemTabActive border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;" ><div>Semaforo</div></div></td>
                                                                <td><div onClick="_activeTabLayer('table_tab_ranking_fija_botttom','tab_ranking_fija_bottom_',this,'content_ranking_fija_bottom','layer_Form_ranking_fija_','layer_Form_ranking_fija_cp')" id="tab_ranking_fija_bottom_cp" class="itemTabActive border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;" ><div>Compromisos por dia</div></div></td>
                                                                <td><div onClick="_activeTabLayer('table_tab_ranking_fija_botttom','tab_ranking_fija_bottom_',this,'content_ranking_fija_bottom','layer_Form_ranking_fija_','layer_Form_ranking_fija_final')" id="tab_ranking_fija_bottom_final" class="itemTabActive border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;" ><div>Ranking Final</div></div></td>
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
