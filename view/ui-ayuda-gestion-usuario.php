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
<title>Ayuda Usuario </title>

<link type="text/css" rel="stylesheet" href="../includes/jquery-ui-1.8/themes/humanity/jquery-ui.css" />
<link type="text/css" rel="stylesheet" href="../includes/font-awesome-4.3.0/css/font-awesome.css" />

<link type="text/css" rel="stylesheet" href="../css/css-cobrast.css" />

<script type="text/javascript" src="../js/includes/jquery-1.4.2.js" ></script>

<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.core.min.js" ></script>
<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.widget.min.js" ></script>
<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.datepicker.min.js" ></script>
<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.dialog.min.js" ></script>
<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.button.min.js" ></script>
<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.effects.core.min.js" ></script>
<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.effects.pulsate.min.js" ></script>
<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.effects.blind.min.js" ></script>

<script type="text/javascript" src="../js/js-cobrast.js" ></script>
<script type="text/javascript" src="../js/templates.js"  ></script>
<script type="text/javascript" src="../js/AyudaGestionUsuarioDAO.js"  ></script>
<script type="text/javascript" src="../js/js-ayuda-gestion-usuario.js"  ></script>
<style type="text/css">
            body {
                 background: #F4F0EC url(../img/bg_.jpg)
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
                            <div style="margin-left:20px; margin-bottom:2px;"><a class="text-blue" onClick="_display_panel('panelAsignarUsuarios')">Asignar Usuarios</a></div>
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
                    <div id="panelAsignarUsuarios" class="ui-widget-content" style="border:0 none;width:100%;height:100%;" align="center">
                        <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                            <tr>
                                <td>
                                    <div align="center" style="padding:5px 0;">
                                        <table>
                                            <tr>
                                                <td align="left">Usuarios</td>
                                                <td align="left"><select class="combo" id="cbUsuarioServicio"><option value="0">--Seleccione--</option></select></td>
                                                <td align="left">Campa&ntilde;a</td>
                                                <td align="left"><select class="combo" id="cbCampania" onChange="listar_cartera(this.value)"><option value="0">--Seleccione--</option></select></td>
                                                <td align="left">Cartera</td>
                                                <td align="left"><select class="combo" id="cbCartera" onChange="load_data_usuarios_ayudar()"><option value="0">--Seleccione--</option></select></td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div align="center">
                                        <table cellpadding="0" cellspacing="0" border="0" style="width:99%;">
                                            <tr>
                                                <td id="content_ayuda_gestion_usuario_bottom">
                                                    <div id="AGU_layer_usuario_asignados_bottom" class="ui-widget-content" align="center" style="display: block; padding: 5px;">
                                                        <table>
                                                            <tr>
                                                                <td>
                                                                    <div id="LayerTableUsuariosAyudar">
                                                                        <table cellspacing="0" cellpadding="0" border="0" class="ui-corner-top" >
                                                                            <tr class="ui-state-default" >
                                                                                <td style="width:25px;padding:3px 0;border:1px solid #E0CFC2;" class="ui-corner-tl" >&nbsp;</td>
                                                                                <td style="width:200px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Usuario</td>
                                                                                <td style="width:200px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Clientes Asignados</td>
                                                                                <td style="width:200px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Clientes Gestionados</td>
                                                                                <td style="width:200px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Clientes Sin Gestionar</td>
                                                                                <td style="width:25px;padding:3px 0;border:1px solid #E0CFC2;" align="center"><input type="checkbox" onClick="checked_all_usuarios_ayudar(this.checked)" /></td>
                                                                                <td style="width:25px;padding:3px 0;border:1px solid #E0CFC2;" align="center" class="ui-corner-tr" >&nbsp;</td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="ui-state-default ui-corner-bottom">
                                                                        <table>
                                                                            <tr>
                                                                                <td>Buscar:</td>
                                                                                <td><input type="text" class="cajaForm" onkeyup="search_operadores_ayuda_gestion(this.value,'tb_teleoperadores_ayudar_data')" /></td>
                                                                                <td><button onClick="delete_usuarios_asignados()" class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all"><span class="ui-button-text">Eliminar usuarios</span></button></td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div id="AGU_layer_usuario_disponibles_bottom" class="ui-widget-content" align="center" style="display: none; padding: 5px;">
                                                        <table>
                                                            <tr>
                                                                <td>
                                                                    <div id="LayerTableUsuariosAsignar">
                                                                        <table cellspacing="0" cellpadding="0" border="0" >
                                                                            <tr class="ui-state-default" >
                                                                                <td style="width:25px;padding:3px 0;border:1px solid #E0CFC2;" class="ui-corner-tl" >&nbsp;</td>
                                                                                <td style="width:200px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Usuario</td>
                                                                                <td style="width:200px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Clientes Asignados</td>
                                                                                <td style="width:200px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Clientes Gestionados</td>
                                                                                <td style="width:200px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Clientes Sin Gestionar</td>
                                                                                <td style="width:25px;padding:3px 0;border:1px solid #E0CFC2;" align="center" ><input type="checkbox" onClick="checked_all_usuarios_asignar(this.checked)" /></td>
                                                                                <td style="width:25px;padding:3px 0;border:1px solid #E0CFC2;" align="center" class="ui-corner-tr" >&nbsp;</td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="ui-state-default ui-corner-bottom">
                                                                        <table>
                                                                            <tr>
                                                                                <td>Buscar:</td>
                                                                                <td><input type="text" class="cajaForm" onkeyup="search_operadores_ayuda_gestion(this.value,'tb_teleoperadores_asignar_data')" /></td>
                                                                                <td><button onClick="save_usuarios_asignar()" class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all"><span class="ui-button-text">Asignar usuarios</span></button></td>
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
                                        <table cellpadding="0" cellspacing="0" border="0" style="width:99%;">
                                            <tr>
                                                <td class="lineTab ui-widget-header"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="margin-left:100px;">
                                                        <table id="table_tab_ayuda_gestion_usuario_bottom" cellpadding="0" cellspacing="0" border="0">
                                                            <tr>
                                                                <td><div id="tab_ayuda_gestion_usuario_bottom_usuario_asignados" class="itemTabActive border-radius-bottom pointer ui-widget-header" style="margin: 0px 1px 0pt 0pt;" onClick="_activeTabLayer('table_tab_ayuda_gestion_usuario_bottom','tab_ayuda_gestion_usuario_bottom',this,'content_ayuda_gestion_usuario_bottom','AGU_layer_','AGU_layer_usuario_asignados_bottom')"><div class="text-white">Usuarios Asignados</div></div></td>
                                                                <td><div id="tab_ayuda_gestion_usuario_bottom_usuario_disponibles" class="itemTab border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;" onClick="_activeTabLayer('table_tab_ayuda_gestion_usuario_bottom','tab_ayuda_gestion_usuario_bottom',this,'content_ayuda_gestion_usuario_bottom','AGU_layer_','AGU_layer_usuario_disponibles_bottom')"><div class="AitemTab">Usuarios Disponibles</div></div></td>
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
    <div style="width:100%; height:20px; border:0 none;" class="ui-widget-header ui-corner-bottom"></div>
</div>
<div id="beforeSendShadow" class="ui-widget-shadow" style="height:30px;position:absolute;top:32%;left:45%;display:none;z-index:1010;"></div>
<div id="MsgBeforeSend" class="ui-widget-content ui-corner-all" style="height:30px;position:absolute;top:32%;left:45%;font-weight:bold;font-size:20px;vertical-align:middle;color:#333366;display:none;z-index:1020;" align="center"  ></div>
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
