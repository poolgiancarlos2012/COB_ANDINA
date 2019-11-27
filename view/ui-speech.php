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
        <title>Speech</title>
        <link type="text/css" rel="stylesheet" href="../includes/jqgrid6/css/ui.jqgrid.css" />
        <link type="text/css" rel="stylesheet" href="../includes/jquery-ui-1.8/themes/humanity/jquery-ui.css" />
        <link type="text/css" rel="stylesheet" href="../includes/font-awesome-4.3.0/css/font-awesome.css" />

        <link type="text/css" rel="stylesheet" href="../css/css-cobrast.css" />

        <script type="text/javascript" src="../js/includes/jquery-1.4.2.js" ></script>
        <script type="text/javascript" src="../js/includes/jquery.upload-1.0.2.js" ></script>
        <script type="text/javascript" src="../js/includes/jquery.inherit.js" ></script>

        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.core.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.widget.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.mouse.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.draggable.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.datepicker.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.dialog.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.button.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.effects.core.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.effects.blind.min.js" ></script>

        <script type="text/javascript" src="../includes/jqgrid6/src/i18n/grid.locale-en.js" ></script>
        <script type="text/javascript" src="../includes/jqgrid6/src/jqModal.js" ></script>
        <script type="text/javascript" src="../includes/jqgrid6/src/grid.base.js" ></script>
        <script type="text/javascript" src="../includes/jqgrid6/src/grid.celledit.js" ></script>
        <script type="text/javascript" src="../includes/jqgrid6/src/grid.common.js" ></script>
        <script type="text/javascript" src="../includes/jqgrid6/src/grid.custom.js" ></script>
        <script type="text/javascript" src="../includes/jqgrid6/src/grid.formedit.js" ></script>
        <script type="text/javascript" src="../includes/jqgrid6/src/grid.import.js" ></script>
        <script type="text/javascript" src="../includes/jqgrid6/src/grid.inlinedit.js" ></script>

        <script type="text/javascript" src="../includes/tinymce/jscripts/tiny_mce/jquery.tinymce.js" ></script>

        <script type="text/javascript" src="../js/js-cobrast.js" ></script>
        <script type="text/javascript" src="../js/templates.js" ></script>
        <script type="text/javascript" src="../js/validacion.js" ></script>
        <script type="text/javascript" src="../js/SpeechJQGRID.js" ></script>
        <script type="text/javascript" src="../js/SpeechDAO.js" ></script>
        <script type="text/javascript" src="../js/js-speech.js" ></script>

        <style type="text/css">
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
                                    <div style="margin-left:20px; margin-bottom:2px;"><a class="text-blue" onClick="_display_panel('panelSpeech')">Carga de Speech</a></div>
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
                    <td width="100%" valign="top">	
                        <div id="cobrastHOME" style="background-color:#FFFFFF; width:100% !important; height:100%; ">
                            <input type="hidden" id="hdCodUsuario" name="hdCodUsuario" value="<?= $_SESSION['cobrast']['idusuario'] ?>" />
                            <input type="hidden" id="hdCodServicio" name="hdCodServicio" value="<?= $_SESSION['cobrast']['idservicio'] ?>" />
                            <input type="hidden" id="hdCodUsuarioServicio" name="hdCodUsuarioServicio" value="<?= $_SESSION['cobrast']['idusuario_servicio'] ?>" />
                            <input type="hidden" id="hdNomServicio" name="hdNomServicio" value="<?= $_SESSION['cobrast']['servicio'] ?>" />
                            <div id="panelSpeech" align="center" style="padding:5px 5px 5px 0;display:block;" class="ui-widget-content">
                                <div onclick="_slide2(this,'panelCargarSpeech')" style="cursor:pointer;">
                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                        <tr>
                                            <td style="width:25px; height:25px;">
                                                <div class="backPanel iconPinBlueDown" onclick="_slide(this,'panelCargarSpeech')"></div>
                                            </td>
                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                <div style="direction:ltr;">
                                                    <a class="text-blue">Carga de Speech</a>
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
                                <div id="panelCargarSpeech" style="margin-top:5px;">
                                    <table>
                                        <tr>
                                            <td align="left">
                                                <div>
                                                    <table>
                                                        <tr>
                                                            <td align="right">Nombre</td>
                                                            <td><input type="text" id="txtNombreAyudaGestionNoText" maxlength="80" class="cajaForm" /></td>
                                                            <td align="right">Seleccione archivo</td>
                                                            <td>
                                                                <div id="uploadFileSpeech" >
                                                                    <input type="file" id="fileSpeech" name="fileSpeech"  />
                                                                </div>
                                                            </td>
                                                            <td align="right">Tipo</td>
                                                            <td><select class="combo" id="cbTipoAyudaGestion" ><option value="0">--Seleccione--</option></select></td>
                                                            <td>
                                                                <button onclick="ajaxFileUpload()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowreturnthick-1-n"></span></button>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div onclick="_slide2(this,'panelListadoSpeech')" style="cursor:pointer;">
                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                        <tr>
                                            <td style="width:25px; height:25px;">
                                                <div class="backPanel iconPinBlueDown" onclick="_slide(this,'panelListadoSpeech')"></div>
                                            </td>
                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                <div style="direction:ltr;">
                                                    <a class="text-blue">Listado de Speech</a>
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
                                <div id="panelListadoSpeech" align="center" style="padding:5px 0;" >
                                    <table>
                                        <tr>
                                            <td>
                                                <div class="ui-widget-content ui-corner-all" style="padding:3px 5px;">
                                                    <table id="ListDownloadAyudaGestion"></table>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div onclick="_slide2(this,'panelCargarSpeechModoTexto')"  style="cursor:pointer;">
                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                        <tr>
                                            <td style="width:25px; height:25px;">
                                                <div class="backPanel iconPinBlueDown" onclick="_slide(this,'panelCargarSpeechModoTexto')"></div>
                                            </td>
                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                <div style="direction:ltr;">
                                                    <a class="text-blue">Modo texto</a>
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
                                <div id="panelCargarSpeechModoTexto" style="margin-top:5px;">
                                    <table>
                                        <tr>
                                            <td>
                                                <div align="left">
                                                    <table>	
                                                        <tr>
                                                            <td><input type="hidden"  id="hdIdSpeechIsText"/></td>
                                                            <td align="right">Nombre</td>
                                                            <td><input type="text" id="txtNombreAyudaGestionModoTexto" maxlength="80" class="cajaForm" /></td>
                                                            <td align="right">Tipo</td>
                                                            <td align="left"><select class="combo" id="cbTipoAyudaGestionModoTexto"><option value="0">--Seleccione--</option></select></td>
                                                            <td><button class="btn" onclick="save_speech_modo_texto()">Grabar</button></td>
                                                            <td><button class="btn" onclick="update_speech_modo_texto()" >Actualizar</button></td>
                                                            <td><button class="btn" onclick="cancel_speech_modo_texto()" >Cancelar</button></td>
                                                        </tr>
                                                    </table>      
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left">Speech Texto</td>
                                        </tr>
                                        <tr>
                                            <td align="left">
                                                <textarea id="txtRichTextSpeech" ></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div onclick="_slide2(this,'panelListadoSpeechIsText')" style="cursor:pointer;">
                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                        <tr>
                                            <td style="width:25px; height:25px;">
                                                <div class="backPanel iconPinBlueDown" onclick="_slide(this,'panelListadoSpeechIsText')"></div>
                                            </td>
                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                <div style="direction:ltr;">
                                                    <a class="text-blue">Listado de Speech Modo texto</a>
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
                                <div id="panelListadoSpeechIsText" align="center" style="padding:5px 0;" >
                                    <table>
                                        <tr>
                                            <td>
                                                <div class="ui-widget-content ui-corner-all" style="padding:3px 5px;">
                                                    <table id="ListAyudaGestionIsText"></table>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
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

        <div id="DataReadFileAndText">
            <div id="DataSpeechArgument"></div>
        </div>

        <div id="layerOverlay" class="ui-widget-overlay" style="display: none;"></div>
        <div id="layerLoading" style="position:absolute ;left: 50%;top: 45%; width: 100px; font-weight: bold; font-size: 18px; color: #AFAFAF; z-index: 100;display: none;">Loading...</div>

        <div id="beforeSendShadow" class="ui-widget-shadow" style="width:150px;height:30px;position:absolute;top:32%;left:45%;display:none;z-index:1010;"></div>
        <div id="MsgBeforeSend" class="ui-widget-content ui-corner-all" style="width:150px;height:30px;position:absolute;top:32%;left:45%;font-weight:bold;font-size:20px;vertical-align:middle;color:#333366;display:none;z-index:1020;" align="center"  ></div>

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