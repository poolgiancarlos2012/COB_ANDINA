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

<link type="text/css" rel="stylesheet" href="../includes/jquery-ui-1.8/themes/humanity/jquery-ui.css" />

<link type="text/css" rel="stylesheet" href="../css/css-cobrast.css" />

<script type="text/javascript" src="../js/includes/jquery-1.4.2.js" ></script>

<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.core.min.js" ></script>
<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.widget.min.js" ></script>
<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.datepicker.min.js" ></script>
<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.dialog.min.js" ></script>
<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.button.min.js" ></script>

<script type="text/javascript" src="../js/includes/jquery.periodicalupdater.js" ></script>

<script type="text/javascript" src="../js/js-cobrast.js" ></script>
<script type="text/javascript" src="../js/templates.js"  ></script>
<script type="text/javascript" src="../js/NoticeDAO.js"  ></script>
<script type="text/javascript" src="../js/js-notice.js"  ></script>
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
            <td id="showhide" width="10px" class="showHide ui-widget-header">
                <a onclick="_sliderFadeBarLayer()">
                    <div id="iconSlider" class="slider icon sliderIconUp"></div>
                </a>
            </td>
            <td width="100%">	
                <div id="cobrastHOME" style="background-color:#FFFFFF; width:100%; height:100%; ">
                    <input type="hidden" id="hdCodUsuario" name="hdCodUsuario" value="<?= $_SESSION['cobrast']['idusuario'] ?>" />
                    <input type="hidden" id="hdCodServicio" name="hdCodServicio" value="<?= $_SESSION['cobrast']['idservicio'] ?>" />
                    <input type="hidden" id="hdCodUsuarioServicio" name="hdCodUsuarioServicio" value="<?= $_SESSION['cobrast']['idusuario_servicio'] ?>" />
                    <input type="hidden" id="hdNomServicio" name="hdNomServicio" value="<?= $_SESSION['cobrast']['servicio'] ?>" />
                    <div id="panelMainHome" class="ui-widget-content" style="border:0 none;width:100%;height:100%;" align="center">
                        <table style="width:100%;">
                            <tr>
                                <td align="center">
                                    <div align="center">
                                        <div style="border:1px solid threedshadow;">
                                            <table>
                                                <tr>
                                                    <td>Canal</td>
                                                    <td><select class="combo"><option value="0">--Seleccione--</option></select></td>
                                                </tr>
                                                <tr>
                                                    <td>Titulo</td>
                                                    <td><input id="txtTitleNotice" class="cajaForm" style="width:300px;" type="text" /></td>
                                                </tr>
                                                <tr>
                                                    <td>Descripcion</td>
                                                    <td><textarea id="txtDescriptionNotice" class="textareaForm" style="width:500px;"></textarea></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" align="center"><button onclick="save_notice()" class="ui-state-default ui-corner-all">Publicar</button></td>
                                                </tr>
                                            </table>
                                        </div>
                                       	<div id="layerContentRss" style="background:none repeat scroll 0 0 -moz-field;border:1px solid threedshadow;margin:2em auto;padding:3em;width:90%;"></div>
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
