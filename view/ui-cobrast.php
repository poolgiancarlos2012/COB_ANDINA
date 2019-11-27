<?php
session_start();

//if( !isset($_SESSION['cobrast']) && $_SESSION['cobrast']['activo']!=1 ) {
//		header('Location: ../index.php');
//	}
if (empty($_SESSION['cobrast']) || !isset($_SESSION['cobrast']) || $_SESSION['cobrast']['activo'] != 1) {
    header('Location: ../index.php');
}
//var_dump($_SESSION['cobrast']);
?>
<title>Home</title>
<link rel="shortcut icon" href="../img/andina.ico" type="image/x-icon">
<link type="text/css" rel="stylesheet" href="../includes/font-awesome-4.3.0/css/font-awesome.css" />
<link type="text/css" rel="stylesheet" href="../includes/jquery-ui-1.8/themes/humanity/jquery-ui.css" />

<link type="text/css" rel="stylesheet" href="../css/css-cobrast.css" />

<script type="text/javascript" src="../js/includes/jquery-1.4.2.js" ></script>

<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.core.min.js" ></script>
<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.widget.min.js" ></script>
<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.datepicker.min.js" ></script>
<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.dialog.min.js" ></script>
<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.button.min.js" ></script>

<script type="text/javascript" src="../js/js-cobrast.js" ></script>
<script type="text/javascript" src="../js/templates.js"  ></script>
<script type="text/javascript" src="../js/js-home.js"  ></script>
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
    body {
         background: #F4F0EC url(../img/fondo1.jpg)
    }
</style>

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
                            <div style="margin-left:20px; margin-bottom:2px;"><a class="text-blue" onClick="_display_panel('panelMainHome')">Home</a></div>
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
            <td id="showhide" width="10px" class="showHide ui-widget-header ">
                <a onclick="_sliderFadeBarLayer()">
                    <div id="iconSlider" class="slider icon sliderIconUp"></div>
                </a>
            </td>
            <td width="100%" style="background:#D8EBFF">	
                <div id="cobrastHOME" style="background-color:#D8EBFF; width:100% !important; height:100%; ">
                    <input type="hidden" id="hdCodUsuario" name="hdCodUsuario" value="<?= $_SESSION['cobrast']['idusuario'] ?>" />
                    <input type="hidden" id="hdCodServicio" name="hdCodServicio" value="<?= $_SESSION['cobrast']['idservicio'] ?>" />
                    <input type="hidden" id="hdCodUsuarioServicio" name="hdCodUsuarioServicio" value="<?= $_SESSION['cobrast']['idusuario_servicio'] ?>" />
                    <input type="hidden" id="hdNomServicio" name="hdNomServicio" value="<?= $_SESSION['cobrast']['servicio'] ?>" />
                    <div id="panelMainHome" class="" style="border:0 none;width:100%;height:100%;;cursor:default;-moz-user-select: none;" align="center">
                        <table>
                            <tr>
                                <td align="center">
                                    <span style=" text-shadow: 0px 8px rgba(0, 0, 0, 0.1);font-size: 100px; font-family: Verdana; font-weight: bold; color: rgb(255, 255, 255);" class="textLogo "></span>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <p class="textInfo"></p>
                                    <p class="textInfo"></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
             <td class="showHide ui-widget-header ">
                <div style="width:10px;"></div>
            </td>
        </tr>
    </table>
    <div style="width:100%; height:20px; border:0 none;" class="ui-widget-header ui-corner-bottom"></div>
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
