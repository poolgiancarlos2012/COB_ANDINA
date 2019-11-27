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
        <title>Gestion</title>
        <link rel="shortcut icon" href="../img/andina.ico" type="image/x-icon">

        <link type="text/css" rel="stylesheet" href="../includes/font-awesome-4.3.0/css/font-awesome.css" />
        <link type="text/css" rel="stylesheet" media="screen" href="../includes/jqgrid-3.8.2/css/ui.jqgrid.css" />
        <link type="text/css" rel="stylesheet" href="../includes/jquery-ui-1.8/themes/redmond/jquery-ui.css" />
        <link type="text/css" rel="stylesheet" href="../includes/jquery.sheet/jquery.sheet.css" />
        <link type="text/css" rel="stylesheet" href="../includes/pnotify-1.0.1/jquery.pnotify.default.css" />
        <link type="text/css" rel="stylesheet" href="../includes/googiespell/googiespell_v4_4/googiespell/googiespell.css" />
        <link type="text/css" rel="stylesheet" href="../css/css-cobrast.css" />      
        <script type="text/javascript" src="../js/includes/jquery-1.4.2.js" ></script>
        <!-- <script type="text/javascript"  src="../includes/theme/themeswitchertool.js"></script> -->
        <script type="text/javascript" src="../includes/jqgrid-3.8.2/js/i18n/grid.locale-en.js" ></script>
        <script type="text/javascript" src="../includes/jqgrid-3.8.2/js/jquery.jqGrid.min.js" ></script>
        <script type="text/javascript" src="../js/includes/jquery.upload-1.0.2.js" ></script>
        <script type="text/javascript" src="../includes/jquery.json-2.2.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/js/jquery-ui-1.8.1.custom.min.js" ></script>
        <script type="text/javascript" src="../js/includes/jquery-ui-timepicker.js" ></script>
        <script type="text/javascript" src="../includes/jquery.watermark/jquery.watermark.js" ></script>
        <script type="text/javascript" src="../js/includes/jquery.alphanumeric.js" ></script>        
        <script type="text/javascript" src="../includes/pnotify-1.0.1/jquery.pnotify.js" ></script>
        <script type="text/javascript" src="../js/includes/jquery.maskedinput-1.2.2.js" ></script>
        <script type="text/javascript" src="../js/validacion.js" ></script>
        <script type="text/javascript" src="../js/js-cobrast.js" ></script>
        <script type="text/javascript" src="../js/templates.js" ></script>
        <link type="text/css" rel="stylesheet" href="../includes/AnythingSlider/anythingslider.css" />
        <script type="text/javascript" src="../includes/AnythingSlider/jquery.anythingslider.js" ></script>

        <!--COMBOBOX JQUERY FILTER-->
        <link type="text/css" rel="stylesheet" href="../css/jquery.multiselect.css" />
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.multiselect.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.multiselect.filter.js" ></script>
        <link type="text/css" rel="stylesheet" media="screen" href="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.multiselect.filter.css" />
        <!--COMBOBOX JQUERY FILTER-->

        <script type="text/javascript" >
            var AlertasUsuario = new Array();
            var CountLoadTelefonos = 0;
            var setIntervalNeotel;

			<!-- Vic I -->
			$(document).ready(function(){
				$("#txtFechaFiLlaIni, #txtFechaFiLlaFin").datepicker({
					dateFormat:'yy-mm-dd',
					dayNamesMin:['D','L','M','M','J','V','S'],
					monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre'], 
					currentText : 'Now'
				});
			});
        </script>
        <script type="text/ecmascript" src="../js/AtencionClienteDAO.js" ></script>
        <script type="text/ecmascript" src="../js/AtencionClienteJQGRID.js" ></script>         
        <script type="text/ecmascript" src="../js/js-atencion-cliente.js" ></script>
		<script type="text/ecmascript" src="../js/FilesDAO.js" ></script>
        <script type="text/ecmascript" src="../js/js-files.js" ></script>
        <script type="text/ecmascript" src="../js/DistribucionDAO.js" ></script>
        <script type="text/ecmascript" src="../js/ReporteDAO.js" ></script>
        <script type="text/ecmascript" src="../js/neotelDAO.js" ></script>
        <!---->
         <style type="text/css">
            body {
                 /*background: #F4F0EC url(../img/fondo1.jpg)*/
                 /*background-color: #CBDDF0;*/
                 /*position: absolute;
                 left: 0px;
                 top: 0px;
                 width: 100%;
                 height: 100%;
                 background: url("../img/cargando.gif") center center no-repeat white;
                 display: block;*/

                /*background-image: url(../img/please_wait.jpg);
                background-position: center top;
                background-size: 100% auto;*/
                 
            }
            #slider { width: 699px; height: 390px; }
            #slider_encuestados { width: 699px; height: 390px; }
            #slider_resueltos { width: 700px; height: 390px; }
        </style>
    </head>
    <body onload="verificar_carga()">
        <div id="cuerpo" style="display:none">
        <div class="divContentMain">
            <!--<div id="layerMenuMain"  onmouseover="$('#layerMenuMainHeader').css('display','none');$('#layerMenuMainContent').css('display','block');" style="position:fixed;width:80px;display:block;top:0;z-index: 9999;" >
                <div id="layerMenuMainHeader" class="ui-state-active ui-corner-bottom" style="padding:3px 0; height: 25px;" align="center">MENU</div>
                <div id="layerMenuMainContent" onmouseout="$('#layerMenuMainContent').css('display','none');$('#layerMenuMainHeader').css('display','block');" style="display:none;" class="ui-state-active ui-corner-bottom"  align="left"> 
                    <table cellpadding="0" cellspacing="0" border="0" >
                        <tr>
                            <td class="ui-widget-content ui-corner-all" style="padding:3px;" onclick="$('#layerTabAC2Visita').slideToggle();">Visita</td>
                        </tr>
                        <tr>
                            <td id="tdLinkMenuMainCentroPago" class="ui-widget-content ui-corner-all" style="padding:3px;" onclick="$('#layerTabAC2CentroPago').slideToggle();">Centro de Pago</td>
                        </tr>
                        <tr>
                                <td class="ui-widget-content ui-corner-all" style="padding:3px;" onclick="$('#layerTabAC2Historico').slideToggle();">Historico</td>
				</tr>
                    </table>
                </div>
            </div>-->
            <table id="tableMenu" class="tableTab" cellpadding="0" cellspacing="0" border="0" style="width: 1170px;margin-top:40px;">
                <tr >
                    <td rowspan="2" width="100" valign="bottom"><div id="switcher"></div></td>
                    <td>
                        <div class="rightItem" style="position:relative;z-index:10;padding: 6px;font-family: Roboto;">
                            <div class="fltRight" style="margin:0px 20px 0px 20px;">
                                <a title="Cerrar Sesión" style="margin-left: 5px; margin-right: 5px; color: rgb(203, 116, 49);" href="../close.php">
                                    <img  src="../img/1477452294_exit.png" width="15" class="boton_imagen" style="">
                                </a>

                            </div>
                            
                            <!--<div class="fltRight">
                    	       <a class="itemTop">Whats' New</a>
                            </div>-->
                            <label style="margin-right: 5px;"><b>Bienvenido:</b> <?php echo $_SESSION['cobrast']['usuario'] ?></label>
                            <label style="margin-right: 5px;"><b>Servicio:</b> <?= $_SESSION['cobrast']['servicio'] ?></label>
                            <div id="btn_herramientas" class="inlineBlock" style="right:5px;position:absolute;z-index:2;">
                                <ul class="ul1">
                                    <li class="ui-state-highlight lcd nav-sub" style="margin-top: -3px;display:none;">
                                        <a href="javascript:void(0)">
                                            <span class="ui-icon ui-icon-triangle-1-se inlineBlock"></span><span class="ui-icon ui-icon-wrench inlineBlock"></span>
                                        </a>
                                        <ul class="t-right" style="right: 0px; margin-top: 0px; min-width: 112px; display: none;">
                                            <li id="li_modo_neotel" onclick="$('#dialog_usuario_neotel').fadeIn();"><span><i style="float:left;" class="ui-icon ui-icon-transferthick-e-w"></i> MODO NEOTEL</span></li>
                                            <li onclick="location.reload();"><span><i style="float:left;" class="ui-icon ui-icon-arrowreturnthick-1-w"></i> MODO NORMAL</span></li>
                                            <li onclick="$('#dlgManualNeotel').dialog('open');" style=" "><div><i style="width: 15px; display: inline-block; float: left; margin-left: 3px;" class="ui-icon ui-icon-arrowreturnthick-1-w"></i> <div style="display: inline-block; font-size: 10px; margin-right: 4px;">NEOTEL MANUAL</div></div></li>
                                            <!--Dialog Manual Neotel-->
                                            <div id="dlgManualNeotel" style="display: none">
                                                <div style="text-align:center">
                                                    <label style="font-weight: 700;">USUARIO NEOTEL :<input style="border-radius: 6px; padding: 4px;" class="cajaForm longCajaForm" type="text" id="txtUsuarioManualNeotel" placeholder="Tu Usuario NEOTEL">
                                                    </label>
                                                    <label style="font-weight: 700;">CAMPAÑA ( dígito ) :<input style="border-radius: 6px; padding: 4px;" class="cajaForm longCajaForm" type="text" id="txtCampaniaManualNeotel" placeholder="Campaña de tu USUARIO" value="5" onkeypress="return isTelefono(event)"></label>
                                                </div>

                                            </div>
                                            <script type="text/javascript">
                                                $(document).ready(function(){
                                                    $('#dlgManualNeotel').dialog({
                                                        height : 250,
                                                        autoOpen : false,
                                                        modal:true,
                                                        width : 200 ,
                                                        title : 'MODO NEOTEL MANUAL',
                                                        buttons : {
                                                            Cancel : function ( ) {
                                                                $(this).dialog('close');
                                                            },
                                                            Iniciar : function(){
                                                                verifica_neotel_manual();
                                                            }
                                                        }
                                                    });
                                                });

                                            </script>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr height="40" valign="bottom">
                    <!--<td class="vAlignBottom tabsLine">-->
                    <td>
                        <div id="layerMessage" align="center"  style="display:block;width:100%;position:fixed;left:0px;margin:0px auto;top:45px;z-index:200;"></div>
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
                            <div align="center">
                                <table>
                                    <tr>
                                        <td align="center">
                                            <?php
                                            if (isset($_SESSION['cobrast'])) {
                                                if (isset($_SESSION['cobrast']['avatar'])) {
                                                    if (trim($_SESSION['cobrast']['avatar']) != '') {
                                                        ?>
                                                            <!-- <img onclick="$('#_dialogEditAvatar').dialog('open')" src="../img/avatars/<?= trim($_SESSION['cobrast']['avatar']) ?>" /> -->
                                                        <?php
                                                    } else {
                                                        ?><img onclick="$('#_dialogEditAvatar').dialog('open')" src="../img/avatars/unknown_small.png" /><?php
                                                    }
                                                } else {
                                                    ?><img src="../img/avatars/unknown_small.png" /><?php
                                                }
                                            } else {
                                                ?><img src="../img/avatars/unknown_small.png" /><?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong><?= $_SESSION['cobrast']['usuario'] ?></strong>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="  headerPanel" style="background-color: #2d3e50;color: white;">
                                <div class="backPanel iconPinUp" onclick="_slideBarLayer(this,'panelMenu')">Menu</div>
                            </div>
                            <div id="panelMenu" class="backPanel contentBarLayer" style="display:block;" >
                                <div style="width:100%;">
                                    <div style="margin-left:20px; margin-bottom:2px;"><a class="text-blue" onClick="_display_panel('panelAtencionCliente')">Atencion al Cliente</a></div>
                                    <div style="margin-left:20px; margin-bottom:2px;"><a class="text-blue" onClick="_display_panel('panelGestorCampo')" id="aGestorCampo">Gestor de Campo</a></div>
                                    <!-- Piro
                                    <div style="margin-left:20px; margin-bottom:2px;"><a class="text-blue" onClick="_display_panel('panelVisitaComercial')" id="aVistaComercial">Vista comercial</a></div>
                                    -->
                                    <!--<div style="margin-left:20px; margin-bottom:2px;"><a class="text-blue" onClick="_display_panel('panelAtencionCalendar')">Calendar</a></div>-->
                                </div>       
                            </div>
                            <div class="  headerPanel" style="background-color: #2d3e50;color: white;">
                                <div class="backPanel iconPinUp" onclick="_slideBarLayer(this,'panelCrear')">Crear</div>
                            </div>
                            <div id="panelCrear" class="backPanel contentBarLayer" style="display:block;" align="center" >
                                <table>
                                    <tr>
                                        <td>
                                            <select id="cbMenuCrear" class="combo" onchange="$('#'+this.value).dialog('open');$(this).val('crear');">
                                                <option value="crear">Crear...</option>
                                                <option value="DialogNuevoCorreo">correo</option>
                                                <option value="DialogNuevoHorarioAtencion">horario de atencion</option>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="  headerPanel" style="background-color: #2d3e50;color: white;">
                                <div class="backPanel iconPinUp" onclick="_slideBarLayer(this,'panelCalendario')" >Calendario</div>
                            </div>
                            <div align="center" id="panelCalendario" style="padding:3px 0;display:block;">
                                <div id="layerDatepicker"></div>
                            </div>
                            <div align="center" style="padding:5px 0;">
                                <div>
                                    <table cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td style="width:185px;">
                                                <div style="padding:2px 10px;" class="ui-widget-header ui-corner-top"><label class="text-white">Speech y Argumentario</label></div>
                                                <div style="overflow-y:auto;background-color:#FFF;border-left:1px solid #E0CFC2;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" align="center" id="tableSpeechArgumentario"></div>
                                                <div style="height:5px;" class="ui-widget-header"></div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div align="center" style="padding:5px 0;">
                                <div>
                                    <table cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td style="width:185px;">
                                                <div style="padding:2px 10px;" class="ui-widget-header ui-corner-top"><label class="text-white">Mis Eventos de hoy</label></div>
                                                <div style="overflow-y:auto;background-color:#FFF;border-left:1px solid #E0CFC2;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" align="center" id="tableEventToDay"></div>
                                                <div style="height:5px;" class="ui-widget-header"></div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div align="center" style="padding:5px 0;">
                                <div>
                                    <table cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td style="width:185px;">
                                                <div style="padding:2px 10px;" class="ui-widget-header ui-corner-top"><label class="text-white">Mis Tareas de hoy</label></div>
                                                <div style="overflow-y:auto;background-color:#FFF;border-left:1px solid #E0CFC2;border-right:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" align="center" id="tableWorkToDay"></div>
                                                <div style="height:5px;" class="ui-widget-header"></div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div align="center" style="padding:5px 0;">
                                <div>
                                    <table cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td style="width:200px;">
                                                <div class="ui-widget-header ui-corner-top" style="padding:3px 10px;font-weight:bold;">Ultimas Noticias</div>
                                                <div style="overflow-y:auto;height:100px;" class="ui-widget-content ui-helper-reset"></div>
                                                <div style="height:15px;" class="ui-widget-header ui-corner-bottom"></div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div align="center">
                                <table class="tools" style="width:auto; margin:5px;">
                                    <tr>
                                        <td>
                                            <div align="center">
                                                <table cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td>
                                                            <div>
                                                                <table>
                                                                    <tr>
                                                                        <td><div class="tools-icon"></div></td>
                                                                        <td><div class="tools-header">Prospecto Informes</div></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div>
                                                                <ul class="tools-ul">
                                                                    <li><a href="../rpt/excel/atencion_cliente/ListAlertByOperator.php?Servicio=<?= $_SESSION['cobrast']['idservicio'] ?>&UsuarioServicio=<?= $_SESSION['cobrast']['idusuario_servicio'] ?>" >Exportar Alertas</a></li>
                                                                    <li><a href="../rpt/excel/atencion_cliente/ListAgendByOperator.php?Servicio=<?= $_SESSION['cobrast']['idservicio'] ?>&UsuarioServicio=<?= $_SESSION['cobrast']['idusuario_servicio'] ?>" >Exportar Agendados</a></li>
                                                                </ul>
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
                    <td id="showhide" width="10px" class="showHide ui-widget-header" >
                        <a onClick="$('#barLayer').css('display','block');">
                            <div id="iconSlider" class="slider icon sliderIconDown"></div>
                        </a>
                    </td>
                    <td width="100%" valign="top">	
                        <div id="cobrastHOME" class="divContent">
                            <input type="hidden" id="hdCodUsuario" name="hdCodUsuario" value="<?= $_SESSION['cobrast']['idusuario'] ?>" />
                            <input type="hidden" id="hdCodServicio" name="hdCodServicio" value="<?= $_SESSION['cobrast']['idservicio'] ?>" />
                            <input type="hidden" id="hdCodUsuarioServicio" name="hdCodUsuarioServicio" value="<?= $_SESSION['cobrast']['idusuario_servicio'] ?>" />
                            <input type="hidden" id="hdNomServicio" name="hdNomServicio" value="<?= $_SESSION['cobrast']['servicio'] ?>" />
                            <input type="hidden" id="hdPorInteres" name="hdPorInteres" value="<?= $_SESSION['cobrast']['interes'] ?>" />
                            <input type="hidden" id="hdPorDescuento" name="hdPorDescuento" value="<?= $_SESSION['cobrast']['descuento'] ?>" />
                            <input type="hidden" id="hdIsInteresDescuento" name="hdIsInteresDescuento" value="<?= $_SESSION['cobrast']['is_interes_descuento'] ?>" />
                            <input type="hidden" id="hdIsMontoCobrar" name="hdIsMontoCobrar" value="<?= $_SESSION['cobrast']['is_monto_cobrar'] ?>" />
                            <input type="hidden" id="hdIsMontoVencidoPorVencer" name="hdIsMontoVencidoPorVencer" value="<?= $_SESSION['cobrast']['is_monto_vencido_por_vencer'] ?>" />
                            <input type="hidden" id="hdCallCenterIp" name="hdCallCenterIp" value="<?= $_SESSION['cobrast']['call_center_ip'] ?>" />
                            <input type="hidden" id="hdUserCallCenter" name="hdUserCallCenter" value="<?= $_SESSION['cobrast']['user_call_center'] ?>" />
                            <input type="hidden" id="hdPasswordCallCenter" name="hdPasswordCallCenter" value="<?= $_SESSION['cobrast']['password_call_center'] ?>" />
                            <input type="hidden" id="hdprefijo_default" name="hdprefijo_default" value="<?= $_SESSION['cobrast']['prefijo_default'] ?>" />
                            <input type="hidden" id="hdprefijo" name="hdprefijo" value="<?= $_SESSION['cobrast']['prefijo'] ?>" />
                            <input type="hidden" id="hdprefijo2" name="hdprefijo2" value="<?= $_SESSION['cobrast']['prefijo2'] ?>" />
                            <input type="hidden" id="hdprefijo_claro" name="hdprefijo_claro" value="<?= $_SESSION['cobrast']['prefijo_claro'] ?>" />
                            <input type="hidden" id="hdprefijo_claro2" name="hdprefijo_claro2" value="<?= $_SESSION['cobrast']['prefijo_claro2'] ?>" />
                            <input type="hidden" id="hdprefijo_movistar" name="hdprefijo_movistar" value="<?= $_SESSION['cobrast']['prefijo_movistar'] ?>" />
                            <input type="hidden" id="hdprefijo_movistar2" name="hdprefijo_movistar2" value="<?= $_SESSION['cobrast']['prefijo_movistar2'] ?>" />
                            <input type="hidden" id="hdprefijo_nextel" name="hdprefijo_nextel" value="<?= $_SESSION['cobrast']['prefijo_nextel'] ?>" />
                            <input type="hidden" id="hdprefijo_nextel2" name="hdprefijo_nextel2" value="<?= $_SESSION['cobrast']['prefijo_nextel2'] ?>" />
                            <input type="hidden" id="hdprefijo_fijo" name="hdprefijo_fijo" value="<?= $_SESSION['cobrast']['prefijo_fijo'] ?>" />
                            <input type="hidden" id="hdprefijo_fijo2" name="hdprefijo_fijo2" value="<?= $_SESSION['cobrast']['prefijo_fijo2'] ?>" />
                            <input type="hidden" id="hdAnexoOp" name="hdAnexoOp" value="0" />
                            <input type="hidden" id="hdAlerta" name="hdAlerta" value="0"/> 
                            <input type="hidden" id="hdUsuarioNeotelTeleoperador" name="hdUsuarioNeotelTeleoperador" value="0"/>
                            <input type="hidden" name="hdmantdatoscontacto" id="hdmantdatoscontacto">

                            <div id="panelAtencionCliente" style="display:block;" >
                                <table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
                                    <tr>
                                        <td>
                                            <input type="hidden" id="IdClienteCarteraMain" name="IdClienteCarteraMain" />
                                            <input type="hidden" id="idClienteMain" name="idClienteMain" />
                                            <input type="hidden" id="CodigoClienteMain" name="CodigoClienteMain" />
                                            <input type="hidden" id="itemMain" name="itemMain" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="hidden" id="LlamadaFechaInicioTMO" name="LlamadaFechaInicioTMO" />
                                            <input type="hidden" id="LlamadaFechaFinTMO" name="LlamadaFechaFinTMO" />
                                            <input type="hidden" id="LlamadaCallerIdAsterisk" name="LlamadaCallerIdAsterisk" />                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top">
                                            <div style="padding:0px;border:none;background-color:#FFFFFF;" class="" >
                                                <!--MENU FILTROS-->
                                                <div id="menufiltros" style="display:none;">
                                                    <?php
                                                        if ($_SESSION['cobrast']['servicio'] == 'COVINOC') { 
                                                    ?>
                                                    <!--PARA EL SERVICIO COVINOC-->
                                                    <table style="width:100%;" cellpadding="0" cellspacing="0" border="0">
                                                        <tr class="">
                                                            <td class="ui-widget-header" align="left">
                                                                <table>
                                                                    <tr>
                                                                        <td style="display:none">Grupo</td>
                                                                        <td style="display:none">
                                                                            <select id="cbGrupoFiltroCampos" class="combo" onchange="cargar_data_campos(this.value);$('#cbCamposFiltroCampos,#cbDataCamposFiltroCampos,#cbFiltroMonto,#cbFiltroTramo,#cbFiltroOtros,#cbFiltroDepartamento').val(0);$('#txtItemAtencionClienteMain').val(0);" >
                                                                                <option value="0">--Seleccione--</option>
                                                                                <option value="ca_cliente|cliente" label="ca_cliente">Cliente</option>
                                                                                <option value="ca_cuenta|cuenta" label="ca_cuenta">Cuenta</option>
                                                                                <option value="ca_detalle_cuenta|detalle_cuenta" label="ca_detalle_cuenta">Operacion</option>
                                                                                <optgroup label="Adicionales">
                                                                                    <option value="ca_datos_adicionales_cliente|adicionales" label="ca_datos_adicionales_cliente">Cliente</option>
                                                                                    <option value="ca_datos_adicionales_cuenta|adicionales" label="ca_datos_adicionales_cuenta">Cuenta</option>
                                                                                    <option value="ca_datos_adicionales_detalle_cuenta|adicionales" label="ca_datos_adicionales_detalle_cuenta">Operacion</option>
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
                                                                        <td style="display:none">Campo</td>
                                                                        <td style="display:none">
                                                                            <select id="cbCamposFiltroCampos" class="combo" onchange="$('#txtCantidadClientesAtencionClienteMain').text(0);carga_lista_data_campo();$('#cbDataCamposFiltroCampos').val(0);$('#txtItemAtencionClienteMain').val(0);" >
                                                                                <option value="0">--Seleccione--</option>
                                                                            </select>
                                                                        </td>
                                                                        <td style="display:none">Datos</td>
                                                                        <td style="display:none">
                                                                            <select style="width:130px;" id="cbDataCamposFiltroCampos" class="combo" onchange="carga_cantidad_clientes_asignados();$('#txtItemAtencionClienteMain').val(0);" >
                                                                                <option value="0">--Seleccione--</option>
                                                                            </select>
                                                                        </td>
                                                                        <td align="right" ><label style="color:#FFF;font-weight:bold;">Monto:</label></td>
                                                                        <td align="left">
                                                                            <select class="combo" id="cbFiltroMonto" onchange="$('#txtCantidadClientesAtencionClienteMain').text(0);$('#txtItemAtencionClienteMain').val(0);carga_cantidad_clientes_filtro();">
                                                                                <option value="0">--</option>
                                                                                <option value="DESC">MAYORES</option>
                                                                                <option value="ASC">MENORES</option>
                                                                            </select>
                                                                        </td>
                                                                        <td align="right" style="display:none"><label style="color:#FFF;font-weight:bold;">Tramo:</label></td>
                                                                        <td align="left" style="display:none">
                                                                            <select class="combo" id="cbFiltroTramo" onchange="$('#txtItemAtencionClienteMain').val(0);carga_cantidad_clientes_filtro();"><option value="0">--</option></select>
                                                                        </td>
                                                                        <td align="right" style="display:none"><label style="color:#FFF;font-weight:bold;">Estado Pago:</label></td>
                                                                        <td align="left" style="display:none">
                                                                            <select class="combo" id="cbFiltroEstadoPago" onchange="$('#txtItemAtencionClienteMain').val(0);carga_cantidad_clientes_filtro();"><option value="0">--</option></select>
                                                                        </td>
                                                                        <td align="right" style="display:none"><label style="color:#FFF;font-weight:bold;">Dias Mora:</label></td>                                                                        
                                                                        <td style="display:none"><!--jmore190813-->
                                                                            <select class="combo" id="cbFiltroDiasMora" onchange="$('#txtItemAtencionClienteMain').val(0);carga_cantidad_dias_mora();"><option value="0">--Seleccione--</option></select>
                                                                        </td>                                                                        
                                                                    </tr>
                                                                </table>

                                                                <table>
                                                                    <tr>
                                                                        <td style="display:none">Departamento</td>
                                                                        <td style="display:none">
                                                                            <select class="combo" id="cbFiltroDepartamento" onchange="$('#txtItemAtencionClienteMain').val('0');$('#txtCantidadClientesAtencionClienteMain').text('0');carga_cantidad_clientes_filtro();listar_provincias();">
                                                                                <option value="0">--Seleccione--</option>
                                                                            </select>
                                                                        </td>
                                                                        <td style="display:none">Provincia</td>
                                                                        <td style="display:none">
                                                                            <select class="combo" id="cbFiltroProvincia" onchange="$('#txtItemAtencionClienteMain').val('0');$('#txtCantidadClientesAtencionClienteMain').text('0');carga_cantidad_clientes_filtro();">
                                                                                <option value="0">--Seleccione--</option>
                                                                            </select> 
                                                                        </td>                                                                        
                                                                        <td >Estado</td>
                                                                        <td>
                                                                            <!--<select style="width:130px;" class="combo" id="cbFiltroEstado" onchange="$('#txtCantidadClientesAtencionClienteMain').text('0');$('#txtItemAtencionClienteMain').val('0');carga_cantidad_clientes_filtro();" >
                                                                                <option value="0">--Seleccione--</option>
                                                                            </select>-->
                                                                            <button class="ui-state-default ui-corner-all" onclick="$('#p_layerContentFiltroEstado').slideToggle()">Seleccionar</button>
                                                                            <div id="p_layerContentFiltroEstado" class="ui-state-active ui-corner-bottom" style="height: 270px; width: 200px; position: absolute; display: none;z-index:120;">
                                                                                <div id="layerContentFiltroEstado" style="overflow: auto; height: 200px; width: 200px" ></div>
                                                                                <div>
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td>Buscar:</td>
                                                                                            <td><input onkeyup="search_text_table(this.value,'layerContentFiltroEstado')" type="text" class="cajaForm" /></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td><input type="radio" name="rdTipoPContentFiltroEstado" checked="checked" value="llamada" /></td>
                                                                                            <td>Estado llamada</td>
                                                                                            <td><input type="radio" name="rdTipoPContentFiltroEstado" value="telefono" /></td>
                                                                                            <td>Cruce telefono</td>
                                                                                        </tr>
                                                                                    </table>
                                                                                    <button class="ui-state-default ui-corner-all" onclick="carga_cantidad_clientes_filtro()" >Filtrar</button>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td style="display:none">Otros</td>
                                                                        <td style="display:none">
                                                                            <select class="combo" id="cbFiltroOtros" onchange="$('#txtItemAtencionClienteMain').val('0');$('#txtCantidadClientesAtencionClienteMain').text('0');carga_cantidad_clientes_filtro()">
                                                                                <option value="0">--Seleccione--</option>
                                                                                <option value="tramo_1">1 a 30 dias</option>
                                                                                <option value="tramo_2">31 a 60 dias</option>
                                                                                <option value="tramo_3">61 a mas</option>                                                                                
                                                                                <option value="pago">Pagos</option>
                                                                                <option value="sin_pago">Sin Pagos</option>
                                                                                <option value="sin_gestion">Sin Gestion Real</option>
                                                                                <option value="sin_gestion_total">Sin Gestion Total</option>
                                                                                <option value="gestionados">Gestionados</option>
                                                                                <option value="provision">Provision</option>
                                                                                <!--<option value="sin_gestion_no_retirados">Sin Gestion No retirados</option>-->
                                                                                <!--<option value="visita">Visitas</option>-->
                                                                               <!--<option value="inactivos">Inactivos</option>-->
                                                                               <!-- <option value="factura_digital">Factura Digital</option> -->
                                                                                <!--<option value="corte_focalizado">Corte Focalizado</option>-->
                                                                                <optgroup label="BBVA">
                                                                                    <option value="llamar">LLAMAR</option>
                                                                                    <option value="no_llamar">NO LLAMAR</option>
                                                                                    <option value="gestionados_llamar">Gestionados LLAMAR</option>
                                                                                    <option value="gestionados_no_llamar">Gestionados NO LLAMAR</option>
                                                                                    <option value="sin_gestion_llamar">Sin Gestion LLAMAR</option>
                                                                                    <option value="sin_gestion_no_llamar">Sin Gestion NO LLAMAR</option>
                                                                                </optgroup>
                                                                                <optgroup label="CELULARES">
                                                                                    <option value="celulares_todo">Todos</option>
                                                                                    <option value="celulares_sin_gestion">Sin gestion</option>
                                                                                    <option value="celulares_con_gestion">Con gestion</option>
                                                                                    <option value="celulares_sin_pago">Sin pago</option>
                                                                                    <option value="celulares_amortizados">Amortizados</option>
                                                                                    <option value="celulares_cancelados">Cancelados</option>
                                                                                </optgroup>
                                                                                <optgroup label="ULTIMA LLAMADA">
                                                                                    <option value="de_0_2_dias">De 0 a 2 dias</option>                                                                                    
                                                                                    <option value="de_3_4_dias">De 3 a 4 dias</option>
                                                                                    <option value="de_5_6_dias">De 5 a 6 dias</option>
                                                                                    <option value="de_7_8_dias">De 7 a 8 dias</option>
                                                                                    <option value="de_9_mas_dias">De 9 a mas dias</option>
                                                                                </optgroup>
                                                                            </select>
                                                                        </td>
                                                                        <td style="display:none"><input id="chbFiltroHorarioAtencion" type="checkbox" onclick="$('#txtItemAtencionClienteMain').val('0');$('#txtCantidadClientesAtencionClienteMain').text('0');"  /></td>
                                                                        <td style="display:none">Horario Inicio</td>
                                                                        <td style="display:none"><input type="text" id="txtFiltroHorarioInicio" readonly="readonly" class="cajaForm" style="width:50px;" /></td>
                                                                        <td style="display:none">Horario Fin</td>
                                                                        <td style="display:none"><input type="text" id="txtFiltroHorarioFin" readonly="readonly" class="cajaForm" style="width:50px;" /></td>

                                                                    </tr>
                                                                </table>
                                                                <table style="display:none">
                                                                    <tr>
                                                                        <td align="right"><label style="color:#FFF;font-weight:bold;">Territorio:</label></td>                                                                        
                                                                        <td><!--jmore200813-->
                                                                            <select class="combo" id="cbFiltroTerritorio" onchange="$('#txtItemAtencionClienteMain').val(0);carga_cantidad_territorio();"><option value="0">--Seleccione--</option></select>
                                                                        </td>                                                                                                                                                
                                                                        <!-- Vic I -->
                                                                        <td align="right">
                                                                            <label style="color:#FFF;font-weight:bold;">&nbsp;&nbsp;&nbsp;Filtro de Llamadas&nbsp;&nbsp;&nbsp;</label>
                                                                        </td>
                                                                        <td align="right">
                                                                            <label style="color:#FFF;font-weight:bold;">Inicio:</label>
                                                                        </td>
                                                                        <td><input id="txtFechaFiLlaIni" name='txtFechaFiLlaIni' readonly="readonly"  type="text" style="width:80px;" class="cajaForm "></td>
                                                                        <td align="right">
                                                                            <label style="color:#FFF;font-weight:bold;">Final:</label>
                                                                        </td>
                                                                        <td><input id="txtFechaFiLlaFin" name='txtFechaFiLlaFin' readonly="readonly" type="text" style="width:80px;" class="cajaForm "></td>
                                                                        <td>
                                                                            <button class="ui-state-default ui-corner-all" onclick="$('#txtItemAtencionClienteMain').val(0);carga_cantidad_filtro_llamada();" title="Filtro Llamada" alt="Filtro Llamada"><img src="../img/filtro.png" /></button>
                                                                        </td>
                                                                        <td align="right"><label style="color:#FFF;font-weight:bold;">Modo De marcacion:</label></td>                                                                                                                                                                                                                    
                                                                        <td>
                                                                            <select class="combo" id="slctUsuario" onchange="cambioUsuario(this.value)">
                                                                                <option value="0">NEOTEL</option>
                                                                                <option value="1" selected>HDEC</option>
                                                                            </select>
                                                                        </td>                                                                        
                                                                    </tr>
                                                                </table>                                                                
                                                            </td>
                                                        </tr>
                                                    </table> <!--/MENU FILTROS-->
                                                    <?php
                                                        }else{ 
                                                    ?>
                                                    <!--PARA EL SERVICIO QUE NO SEA COVINOC-->
                                                    <table style="width:100%;" cellpadding="0" cellspacing="0" border="0">
                                                        <tr class="">
                                                            <td class="ui-widget-header" align="left">
                                                                <table>
                                                                    <tr>                                                                        
                                                                        <td style="display:none;">Grupo</td>
                                                                        <td style="display:none;">
                                                                            <select id="cbGrupoFiltroCampos" class="combo" onchange="cargar_data_campos(this.value);$('#cbCamposFiltroCampos,#cbDataCamposFiltroCampos,#cbFiltroMonto,#cbFiltroTramo,#cbFiltroOtros,#cbFiltroDepartamento').val(0);$('#txtItemAtencionClienteMain').val(0);" >
                                                                                <option value="0">--Seleccione--</option>
                                                                                <option value="ca_cliente|cliente" label="ca_cliente">Cliente</option>
                                                                                <option value="ca_cuenta|cuenta" label="ca_cuenta">Cuenta</option>
                                                                                <option value="ca_detalle_cuenta|detalle_cuenta" label="ca_detalle_cuenta">Operacion</option>
                                                                                <optgroup label="Adicionales">
                                                                                    <option value="ca_datos_adicionales_cliente|adicionales" label="ca_datos_adicionales_cliente">Cliente</option>
                                                                                    <option value="ca_datos_adicionales_cuenta|adicionales" label="ca_datos_adicionales_cuenta">Cuenta</option>
                                                                                    <option value="ca_datos_adicionales_detalle_cuenta|adicionales" label="ca_datos_adicionales_detalle_cuenta">Operacion</option>
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
                                                                        <td style="display:none;">Campo</td>
                                                                        <td style="display:none;">
                                                                            <select id="cbCamposFiltroCampos" class="combo" onchange="$('#txtCantidadClientesAtencionClienteMain').text(0);carga_lista_data_campo();$('#cbDataCamposFiltroCampos').val(0);$('#txtItemAtencionClienteMain').val(0);" >
                                                                                <option value="0">--Seleccione--</option>
                                                                            </select>
                                                                        </td>
                                                                        <td style="display:none;">Datos</td>
                                                                        <td style="display:none;">
                                                                            <select style="width:130px;" id="cbDataCamposFiltroCampos" class="combo" onchange="carga_cantidad_clientes_asignados();$('#txtItemAtencionClienteMain').val(0);" >
                                                                                <option value="0">--Seleccione--</option>
                                                                            </select>
                                                                        </td>
                                                                        <!--FIDELIZACION-->
                                                                        
                                                                        <td align="right"><label style="color:#FFF;font-weight:bold;">MONTO:</label></td>
                                                                        <td align="left">
                                                                            <select class="combo" id="cbFiltroMonto" onchange="$('#txtCantidadClientesAtencionClienteMain').text(0);$('#txtItemAtencionClienteMain').val(0);carga_cantidad_clientes_filtro();">
                                                                                <option value="0">--</option>
                                                                                <option value="DESC">DESCENDENTE</option>
                                                                                <option value="ASC">ASCENDENTE</option>
                                                                            </select>
                                                                        </td>
                                                                        <td><span style="color:#07182A;margin:0 5px;">&#9646;</span></td>
                                                                        <!--FIDELIZACION-->
                                                                        <td style="display:none;" align="right"><label style="color:#FFF;font-weight:bold;">Tramo:</label></td>
                                                                        <td style="display:none;" align="left">
                                                                            <select class="combo" id="cbFiltroTramo" onchange="$('#txtItemAtencionClienteMain').val(0);carga_cantidad_clientes_filtro();"><option value="0">--</option></select>
                                                                        </td>
                                                                        <td style="display:none;" align="right"><label style="color:#FFF;font-weight:bold;">Estado Pago:</label></td>
                                                                        <td style="display:none;" align="left">
                                                                            <select class="combo" id="cbFiltroEstadoPago" onchange="$('#txtItemAtencionClienteMain').val(0);carga_cantidad_clientes_filtro();"><option value="0">--</option></select>
                                                                        </td>
                                                                        <td style="display:none;" align="right"><label style="color:#FFF;font-weight:bold;">Dias Mora:</label></td>                                                                        
                                                                        <td style="display:none;"><!--jmore190813-->
                                                                            <select class="combo" id="cbFiltroDiasMora" onchange="$('#txtItemAtencionClienteMain').val(0);carga_cantidad_dias_mora();"><option value="0">--Seleccione--</option></select>
                                                                        </td>
                                                                        <td>ESTADO</td>
                                                                        <td>
                                                                            <!--<select style="width:130px;" class="combo" id="cbFiltroEstado" onchange="$('#txtCantidadClientesAtencionClienteMain').text('0');$('#txtItemAtencionClienteMain').val('0');carga_cantidad_clientes_filtro();" >
                                                                                <option value="0">--Seleccione--</option>
                                                                            </select>-->
                                                                            <button class="ui-state-default ui-corner-all" onclick="$('#p_layerContentFiltroEstado').slideToggle()">Seleccionar</button>
                                                                            <div id="p_layerContentFiltroEstado" class="ui-state-active ui-corner-bottom" style="height: 270px; width: 200px; position: absolute; display: none;z-index:120;">
                                                                                <div id="layerContentFiltroEstado" style="overflow: auto; height: 200px; width: 200px" ></div>
                                                                                <div>
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td>Buscar:</td>
                                                                                            <td><input onkeyup="search_text_table(this.value,'layerContentFiltroEstado')" type="text" class="cajaForm" /></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td><input type="radio" name="rdTipoPContentFiltroEstado" checked="checked" value="llamada" /></td>
                                                                                            <td>Estado llamada</td>
                                                                                            <td><input type="radio" name="rdTipoPContentFiltroEstado" value="telefono" /></td>
                                                                                            <td>Cruce telefono</td>
                                                                                        </tr>
                                                                                    </table>
                                                                                    <button class="ui-state-default ui-corner-all" onclick="carga_cantidad_clientes_filtro()" >Filtrar</button>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td style="display:none;">SEMANA</td>
                                                                        <td>
                                                                            <select id="semana_opcion" style="display:none;" class="combo" onchange="carga_cantidad_clientes_filtro();">
                                                                                <option value="0">--Seleccione--</option>                                                                                
                                                                            </select>
                                                                        </td>
                                                                        <td><span style="color:#07182A;margin:0 5px;">&#9646;</span></td>
                                                                        <td>CON/SIN GESTION:</td>
                                                                        <td>
                                                                            <input type="hidden" id="hdfiltro_con_sin_gestion"/>
                                                                            <select id="filtro_con_sin_gestion" >
                                                                                <option value="CONGESTION">CON GESTION</option>
                                                                                <option value="SINGESTION">SIN GESTION</option>
                                                                            </select>
                                                                        </td>
                                                                        <td><span style="color:#07182A;margin:0 5px;">&#9646;</span></td>
                                                                        <td style="" align="right">
                                                                            <label style="color:#FFF;font-weight:bold;">&nbsp;&nbsp;&nbsp;FILTRO DE LLAMADAS&nbsp;&nbsp;&nbsp;</label>
                                                                        </td>
                                                                        <td style="" align="right">
                                                                            <label style="color:#FFF;font-weight:bold;">INICIO:</label>
                                                                        </td>
                                                                        <td style=""><input id="txtFechaFiLlaIni" name='txtFechaFiLlaIni' readonly="readonly"  type="text" style="width:80px;" class="cajaForm "></td>
                                                                        <td style="" align="right">
                                                                            <label style="color:#FFF;font-weight:bold;">FINAL:</label>
                                                                        </td>
                                                                        <td style=""><input id="txtFechaFiLlaFin" name='txtFechaFiLlaFin' readonly="readonly" type="text" style="width:80px;" class="cajaForm "></td>
                                                                        <td style="">
                                                                            <button class="ui-state-default ui-corner-all" onclick="$('#txtItemAtencionClienteMain').val(0);carga_cantidad_filtro_llamada();" title="Filtro Llamada" alt="Filtro Llamada"><img src="../img/filtro.png" /></button>
                                                                        </td>

                                                                    </tr>

                                                                </table>

                                                                <table>
                                                                    <tr>
                                                                        <td>RANGO:</td>
                                                                        <td>
                                                                            <select id="idrango_cobranzas" class="combo" onChange="carga_cantidad_clientes_filtro();">
                                                                                <option value="">--</option>
                                                                                <option value="0-(01 a 08 dias)">0-(01 a 08 dias)</option>
                                                                                <option value="1-(09 a 30 dias)">1-(09 a 30 dias)</option>
                                                                                <option value="2-(31 a 60 dias)">2-(31 a 60 dias)</option>
                                                                                <option value="3-(61 a 90 dias)">3-(61 a 90 dias)</option>
                                                                                <option value="4-(91 a 120 dias)">4-(91 a 120 dias)</option>
                                                                                <option value="5-(121 a 360 dias)">5-(121 a 360 dias)</option>
                                                                                <option value="6-(mas de 360 dias)">6-(mas de 360 dias)</option>
                                                                                <option value="7-(Cob. Judicial)">7-(Cob. Judicial)</option>
                                                                                <option value="8-(Vigente)">8-(Vigente)</option>
                                                                                <option value="9-(Saldo a favor)">9-(Saldo a favor)</option>
                                                                            </select>
                                                                        </td>
                                                                        <td><span style="color:#07182A;margin:0 5px;">&#9646;</span></td>
                                                                        <td>TIPO CLIENTE:</td>
                                                                        <td>
                                                                            <select id="idtipo_cliente_andina" class="combo" onChange="carga_cantidad_clientes_filtro();">
                                                                                <option value="">--</option>
                                                                                <option value="RIESGO ALTO">RIESGO ALTO</option>
                                                                                <option value="RIESGO MEDIO">RIESGO MEDIO</option>
                                                                                <option value="RIESGO BAJO">RIESGO BAJO</option>
                                                                                <option value="COB. JUD">COB. JUD</option>
                                                                            </select>
                                                                        </td>

                                                                        <td style="display:none;">DEPARTAMENTO</td>
                                                                        <td style="display:none;">
                                                                            <select id="departamento_filtro" class="combo">
                                                                            </select>
                                                                        </td>                                                                        
                                                                        <td style="display:none;">PROVINCIA</td>
                                                                        <td style="display:none;">
                                                                            <select id="provincia_filtro" class="combo">
                                                                            </select>
                                                                        </td>
                                                                        <td style="display:none;">DISTRITO</td>
                                                                        <td style="display:none;">
                                                                            <select id="distrito_filtro" class="combo">
                                                                            </select>
                                                                        </td>


                                                                        


                                                                        
                                                                        <td style="display:none;">Departamento</td>
                                                                        <td style="display:none;">
                                                                            <select class="combo" id="cbFiltroDepartamento" onchange="$('#txtItemAtencionClienteMain').val('0');$('#txtCantidadClientesAtencionClienteMain').text('0');carga_cantidad_clientes_filtro();listar_provincias();">
                                                                                <option value="0">--Seleccione--</option>
                                                                            </select>
                                                                        </td>
                                                                        <td style="display:none;">Provincia</td>
                                                                        <td style="display:none;">
                                                                            <select class="combo" id="cbFiltroProvincia" onchange="$('#txtItemAtencionClienteMain').val('0');$('#txtCantidadClientesAtencionClienteMain').text('0');carga_cantidad_clientes_filtro();">
                                                                                <option value="0">--Seleccione--</option>
                                                                            </select> 
                                                                        </td>                                                                        
                                                                        
                                                                        <td style="display:none;">Otros</td>
                                                                        <td style="display:none;">
                                                                            <select class="combo" id="cbFiltroOtros" onchange="$('#txtItemAtencionClienteMain').val('0');$('#txtCantidadClientesAtencionClienteMain').text('0');carga_cantidad_clientes_filtro()">
                                                                                <option value="0">--Seleccione--</option>
                                                                                <option value="tramo_1">1 a 30 dias</option>
                                                                                <option value="tramo_2">31 a 60 dias</option>
                                                                                <option value="tramo_3">61 a mas</option>                                                                                
                                                                                <option value="pago">Pagos</option>
                                                                                <option value="sin_pago">Sin Pagos</option>
                                                                                <option value="sin_gestion">Sin Gestion Real</option>
                                                                                <option value="sin_gestion_total">Sin Gestion Total</option>
                                                                                <option value="gestionados">Gestionados</option>
                                                                                <option value="provision">Provision</option>
                                                                                <!--<option value="sin_gestion_no_retirados">Sin Gestion No retirados</option>-->
                                                                                <!--<option value="visita">Visitas</option>-->
                                                                               <!--<option value="inactivos">Inactivos</option>-->
                                                                               <!-- <option value="factura_digital">Factura Digital</option> -->
                                                                                <!--<option value="corte_focalizado">Corte Focalizado</option>-->
                                                                                <optgroup label="BBVA">
                                                                                    <option value="llamar">LLAMAR</option>
                                                                                    <option value="no_llamar">NO LLAMAR</option>
                                                                                    <option value="gestionados_llamar">Gestionados LLAMAR</option>
                                                                                    <option value="gestionados_no_llamar">Gestionados NO LLAMAR</option>
                                                                                    <option value="sin_gestion_llamar">Sin Gestion LLAMAR</option>
                                                                                    <option value="sin_gestion_no_llamar">Sin Gestion NO LLAMAR</option>
                                                                                </optgroup>
                                                                                <optgroup label="CELULARES">
                                                                                    <option value="celulares_todo">Todos</option>
                                                                                    <option value="celulares_sin_gestion">Sin gestion</option>
                                                                                    <option value="celulares_con_gestion">Con gestion</option>
                                                                                    <option value="celulares_sin_pago">Sin pago</option>
                                                                                    <option value="celulares_amortizados">Amortizados</option>
                                                                                    <option value="celulares_cancelados">Cancelados</option>
                                                                                </optgroup>
                                                                                <optgroup label="ULTIMA LLAMADA">
                                                                                    <option value="de_0_2_dias">De 0 a 2 dias</option>                                                                                    
                                                                                    <option value="de_3_4_dias">De 3 a 4 dias</option>
                                                                                    <option value="de_5_6_dias">De 5 a 6 dias</option>
                                                                                    <option value="de_7_8_dias">De 7 a 8 dias</option>
                                                                                    <option value="de_9_mas_dias">De 9 a mas dias</option>
                                                                                </optgroup>
                                                                            </select>
                                                                        </td>
                                                                        <td style="display:none;"><input id="chbFiltroHorarioAtencion" type="checkbox" onclick="$('#txtItemAtencionClienteMain').val('0');$('#txtCantidadClientesAtencionClienteMain').text('0');"  /></td>
                                                                        <td style="display:none;">Horario Inicio</td>
                                                                        <td style="display:none;"><input type="text" id="txtFiltroHorarioInicio" readonly="readonly" class="cajaForm" style="width:50px;" /></td>
                                                                        <td style="display:none;">Horario Fin</td>
                                                                        <td style="display:none;"><input type="text" id="txtFiltroHorarioFin" readonly="readonly" class="cajaForm" style="width:50px;" /></td>

                                                                    </tr>
                                                                </table>
                                                                <table>
                                                                    <tr>
                                                                        <td style="display:none;" align="right"><label style="color:#FFF;font-weight:bold;">Territorio:</label></td>                                                                        
                                                                        <td style="display:none;"><!--jmore200813-->
                                                                            <select class="combo" id="cbFiltroTerritorio" onchange="$('#txtItemAtencionClienteMain').val(0);carga_cantidad_territorio();"><option value="0">--Seleccione--</option></select>
                                                                        </td>                                                                                                                                                
                                                                        <!-- Vic I -->
                                                                        
                                                                        <td style="display:none;" align="right"><label style="color:#FFF;font-weight:bold;">Modo De marcacion:</label></td>                                                                                                                                                                                                                    
                                                                        <td style="display:none;">
                                                                            <select class="combo" id="slctUsuario" onchange="cambioUsuario(this.value)">
                                                                                <option value="0">NEOTEL</option>
                                                                                <option value="1" selected>HDEC</option>
                                                                            </select>
                                                                        </td>
                                                                        
                                                                    </tr>
                                                                </table>                                                                
                                                            </td>
                                                        </tr>
                                                    </table> <!--/MENU FILTROS-->
                                                    <?php
                                                        }
                                                    ?>
                                                    
                                                </div>
                                                <!--OCULTAR FILTROS-->
                                                <div onclick="_slide3(this,'menufiltros',0)" align="center" id="slidefiltros" class="pointer">
                                                <!--div id="slidefiltros" align="center"-->
                                                    <table cellpadding="0" cellspacing="0" border="0" background="../img/sombra.png" width="800" height="5">
                                                        <tr>
                                                            <td align="center">
                                                                <img src="../img/ab.png" alt="expandir/contraer" border="0" />
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>       
                                                <!--/OCULTAR FILTROS-->
                                                <!--BARRA FLOTANTE-->
                                                <div  id="barraflotante" class="ui-state-active ui-corner-bottom" style="position: fixed; z-index: 22; top: 0px; width: 1170px; margin-left: -8px;">
                                                    <div class="inlineBlock" style ="margin:0px">
                                                        <table cellpadding="0" cellspacing="0">
                                                            <tbody><tr>
                                                                    <!-- <td style="width:27px;">
                                                                        <button id="btnBackClienteAtencionCliente" class="ui-state-default ui-corner-all" onclick="gestion_next_back('back')" style="padding:3px 1px;"><span class="ui-icon ui-icon-circle-triangle-w"></span></button>
                                                                    </td> -->
                                                                    <td>&nbsp;</td>
                                                                    <td>
                                                                        <div class="boton_estilo fondo_gradiente_azul" style="width:30px;margin:0 2px;" id="btnBackClienteAtencionCliente" onclick="gestion_next_back('back')">
                                                                            <img src="../img/go.png" width="20" class=" img_espejo" style="position: absolute;left: 5px;top:2px;">
                                                                        </div>
                                                                    </td>
                                                                    <td>&nbsp;</td>
                                                                    <td style="width:20px;"><input class="cajaForm" onkeyup="if( event.keyCode == 13 ){ jump_item() }" id="txtItemAtencionClienteMain" value="0" style="width:35px;text-align:center;"></td>
                                                                    <td style="width:20px;"><label style="width:20px;text-align:center;padding: 2px;" class="ui-widget-content" id="txtCantidadClientesAtencionClienteMain">0</label></td>
                                                                    <!-- <td style="width:27px;">
                                                                        <button id="btnNextClienteAtencionCliente" class="ui-state-default ui-corner-all" onclick="gestion_next_back('next')" style="padding:3px 1px;"><span class="ui-icon ui-icon-circle-triangle-e"></span></button>
                                                                    </td> -->
                                                                    <td>
                                                                        <div class="boton_estilo fondo_gradiente_azul" style="width:30px;margin:0 2px;" id="btnNextClienteAtencionCliente" onclick="gestion_next_back('next')">
                                                                            <img src="../img/go.png" width="20"  style="position: absolute;left: 5px;top:2px;">
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="inlineBlock" style ="margin:0px">
                                                        <table cellpadding="0" cellspacing="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="width:1px;">
                                                                        <input type="hidden" id="IdClienteCartera" name="IdClienteCartera" value="">
                                                                        <input type="hidden" id="IdCartera" name="IdCartera" value="">
                                                                        <input type="hidden" id="Flag_Provincia" value="0">
                                                                    </td>
                                                                    <td>
                                                                        <table>
                                                                            <tr>
                                                                                <td><div id="dataInformationGestionMain">Informacion de gestion</div></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><label style="font-size:10px;" id="preGestorClienteCartera">GESTOR : </label></td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                    <!--<td><div id="dataInformationGestionMain"></div></td>-->
                                                                    <!-- <td><button class="ui-state-default ui-corner-all" onclick="informacion_gestion()"><span class="ui-icon ui-icon-refresh"></span></button></td> -->
                                                                    <td>&nbsp;</td>
                                                                    <td>
                                                                        <div class="boton_estilo fondo_gradiente_azul" style="width:30px;margin:0 2px;" onclick="informacion_gestion()" title="Actualizar" alt="Actualizar">
                                                                            <img src="../img/view_refresh.png" width="20" class="boton_imagen" style="position: absolute;left: 5px;top:2px;">
                                                                        </div>
                                                                    </td>
                                                                        

                                                                    <!--<td align="center">
                                                                        <label style="font-size:10px;" id="preGestorClienteCartera">GESTOR : </label>
                                                                    </td>-->
                                                                    <!-- <td>
                                                                        <button class="ui-state-default ui-corner-all" onclick="show_box_model_alerta_telefono()" title="Agregar Alerta por telefono" alt="Agregar Alerta"><img src="../img/bell_add.png" /></button>
                                                                    </td> -->
                                                                    <td>
                                                                        <div class="boton_estilo fondo_gradiente_azul" style="width:30px;margin:0 2px;" onclick="show_box_model_alerta_telefono()" title="Agregar Alerta por telefono" alt="Agregar Alerta">
                                                                            <img src="../img/bell_add.png" width="16" class="boton_imagen" style="position: absolute;left: 6px;top:5px;">
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="boton_estilo fondo_gradiente_azul" style="width:30px;margin:0 2px;" onclick="show_box_model_alertas_sin_atender()" title="Alertas" alt="Alertas">
                                                                            <img src="../img/bell_go.png" width="16" class="boton_imagen" style="position: absolute;left: 6px;top:5px;">
                                                                        </div>
                                                                    </td> 
                                                                    <!-- <td>
                                                                        <button onclick="show_box_model_nota()" class="ui-state-default ui-corner-all" title="Agregar Nota" alt="Agregar Nota" style="padding:1px 1px;"><img src="../img/note_new.gif"></button>
                                                                    </td> -->
                                                                    <td>
                                                                        <div class="boton_estilo fondo_gradiente_azul" style="width:30px;margin:0 2px;" onclick="show_box_model_nota()" title="Agregar Nota" alt="Agregar Nota">
                                                                            <img src="../img/note_new.gif" width="16" class="boton_imagen" style="position: absolute;left: 6px;top:5px;">
                                                                        </div>
                                                                    </td>
                                                                    <!-- <td style="width:71px"> -->
                                                                        <!--<button id="btnShowConsultar" onclick="show_box_model_consulta()" ><span>Consultar Supervisor</span></button>-->
                                                                        <!-- <button id="btnShowAlert" class="ui-state-default ui-corner-all" onclick="show_box_model_alertas_sin_atender()"><img src="../img/bell_go.png"><span>Alertas</span></button> -->
                                                                    <!-- </td> -->
                                                                    
                                                                    <!-- <td style="width:55px">
                                                                        <button id="btnShowNotas" class="ui-state-default ui-corner-all" style="padding:1px 1px;" onclick="show_box_model_notas_hoy()"><img src="../img/note_go.png"><span>Notas</span></button>
                                                                    </td> -->
                                                                    <td>
                                                                        <div class="boton_estilo fondo_gradiente_azul" style="width:30px;margin:0 2px;" onclick="show_box_model_notas_hoy()" title="Notas" alt="Notas">
                                                                            <img src="../img/note_go.png" width="16" class="boton_imagen" style="position: absolute;left: 6px;top:5px;">
                                                                        </div>
                                                                    </td> 
                                                                    <!-- <td style="width:90px">
                                                                        <button id="btnShowFolder" class="ui-state-default ui-corner-all" style="padding:1px 1px;display:none;" ><img src="../img/page_txt.png"><span>Archivos</span></button>
                                                                    </td> -->
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div style="margin:0px;" class="inlineBlock">
                                                        <table style="display:none;">
                                                            <tr>
                                                                <td>Automatico</td>
                                                                <td><input type="radio" value="modo_marcacion_automatica"  name="modo_marcacion_telefono" /></td>
                                                                <td>Manual</td>
                                                                <td><input type="radio" value="modo_marcacion_manual" checked="checked" name="modo_marcacion_telefono" /></td>
                                                                <td>Barrido</td>
                                                                <td><input type="radio" value="modo_marcacion_barrido" name="modo_marcacion_telefono" /></td>
                                                                <td>Prioritario</td>
                                                                <td><input type="radio" value="modo_marcacion_barrido_peso" name="modo_marcacion_telefono" /></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div style="margin-top: 17px; float: right;margin-right:5px;color: red;font-weight: bold;"   class="inlineBlock">
                                                        <div id="isNeotel"></div>
                                                        <input type="hidden" id="hdIsNeotel" value="false">
                                                    </div>
                                                </div>
                                                <!--/BARRA FLOTANTE-->
                                                <!--BARRA MENU-->
                                                <table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
                                                    <tr>
                                                        <!--AVANZAR RETROCEDER-->
                                                        <!--<td valign="bottom">  
                                                            <table cellpadding="0" cellspacing="0">
                                                                <tr>
                                                                    <td style="width:27px;">
                                                                        <button id="btnBackClienteAtencionCliente" class="ui-state-default ui-corner-all" onclick="gestion_next_back('back')" style="padding:3px 1px;"><span class="ui-icon ui-icon-circle-triangle-w"></span></button>
                                                                    </td>
                                                                    <td style="width:20px;"><input class="cajaForm" onkeyup="if( event.keyCode == 13 ){ jump_item() }" id="txtItemAtencionClienteMain" value="0" style="width:35px;text-align:center;" /></td>
                                                                    <td style="width:20px;"><label style="width:20px;text-align:center;" class="ui-widget-content" id="txtCantidadClientesAtencionClienteMain">0</label></td>
                                                                    <td style="width:27px;">
                                                                        <button id="btnNextClienteAtencionCliente" class="ui-state-default ui-corner-all" onclick="gestion_next_back('next')" style="padding:3px 1px;"><span class="ui-icon ui-icon-circle-triangle-e"></span></button>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>-->
                                                        <!--AVANZAR RETROCEDER-->
                                                        <!--MENU-->
                                                        <td valign="bottom">
                                                            <div>
                                                                <input type="hidden" id="hTipoGestion" value="propias"/>
                                                                <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom:-2px;" class="inlineBlock">
                                                                    <tr id="table_tab_AC1">
                                                                        <td>
                                                                            <div onclick="_activeTabLayer('table_tab_AC1','tabAC1',this,'content_table_tab_AC1','layerTabAC1','layerTabAC1Busqueda');" id="tabAC1Busqueda" class="itemTab border-radius-top pointer ui-widget-header">
                                                                                <div class="text-white">Busqueda</div>
                                                                            </div>
                                                                        </td>

                                                                        <td>
                                                                            <div style="display: none;" onclick="_activeTabLayer('table_tab_AC1','tabAC1',this,'content_table_tab_AC1','layerTabAC1','layerTabAC1Agendar');" id="tabAC1Agendar" class="itemTab border-radius-top pointer ui-widget-content">
                                                                                <div class="AitemTab" style="width:45px;">Agendar</div>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div style="display: none;" onclick="_activeTabLayer('table_tab_AC1','tabAC1',this,'content_table_tab_AC1','layerTabAC1','layerTabAC1Ranking');" id="tabAC1Ranking" class="itemTab border-radius-top pointer ui-widget-content">
                                                                                <div class="AitemTab" style="width:45px;">Ranking</div>
                                                                            </div>
                                                                        </td>

                                                                        <td>
                                                                            <div style="display: none;" onclick="_activeTabLayer('table_tab_AC1','tabAC1',this,'content_table_tab_AC1','layerTabAC1','layerTabAC1Globales');cambiarFuncionXpestaña('grilla');" id="tabAC1Globales" class="itemTab border-radius-top pointer ui-widget-content">
                                                                                <div class="AitemTab" style="width:45px;">Globales</div>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div onclick="_activeTabLayer('table_tab_AC1','tabAC1',this,'content_table_tab_AC1','layerTabAC1','layerTabAC1Resultado');" id="tabAC1Resultado" class="itemTab border-radius-top pointer ui-widget-content">
                                                                                <div class="AitemTab">Resultado</div>
                                                                            </div>
                                                                        </td>

                                                                        <td width="10">
                                                                        </td>

                                                                        <td>
                                                                            <div style="display: none;" onclick="_activeTabLayer('table_tab_AC1','tabAC1',this,'content_table_tab_AC1','layerTabAC1','layerTabAC1Apoyo');" id="tabAC1Apoyo" class="itemTab border-radius-top pointer ui-widget-content">
                                                                                <div class="AitemTab" style="width:35px;">Apoyo</div>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div style="display: none;" onclick="_activeTabLayer('table_tab_AC1','tabAC1',this,'content_table_tab_AC1','layerTabAC1','layerTabAC1MatrizBusqueda');cambiarFuncionXpestaña ('combo');" id="tabAC1MatrizBusqueda" class="itemTab border-radius-top pointer ui-widget-content">
                                                                                <div class="AitemTab" style="width:115px;">Matriz de Busqueda</div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <div id="lbMessageFechasCartera" style="font-size:11px;font-weight:bold; right:0px;padding:3px;vertical-align: top;display:none;" class=" ui-corner-all inlineBlock" ></div>
                                                            </div>
                                                        </td>
                                                        <!--/MENU-->
                                                        <!--ALERTAS-->
                                                        <!--<td align="right" valign="bottom"> 
                                                            <table cellpadding="0" cellspacing="0">
                                                                <tr>
                                                                    <td style="width:1px;">
                                                                        <input type="hidden" id="IdClienteCartera" name="IdClienteCartera" value="" />
                                                                        <input type="hidden" id="IdCartera" name="IdCartera" value="0" />
                                                                    </td>
                                                                    <td><div id="dataInformationGestionMain">Informacion de gestion</div></td>
                                                                    <td><div id="dataInformationGestionMain"></div></td>
                                                                    <td><button class="ui-state-default ui-corner-all" onclick="informacion_gestion()" ><span class="ui-icon ui-icon-refresh"></span></button></td>
                                                                    <td align="center">
                                                                        <label style="font-size:10px;" id="preGestorClienteCartera" ></label>
                                                                    </td>
                                                                    <td>
                                                                        <button onclick="show_box_model_nota()" class="ui-state-default ui-corner-all" title="Agregar Nota" alt="Agregar Nota" style="padding:1px 1px;" ><img src="../img/note_new.gif" /></button>
                                                                    </td>
                                                                    <td style="width:71px">
                                                                        <button id="btnShowConsultar" onclick="show_box_model_consulta()" ><span>Consultar Supervisor</span></button>
                                                                        <button id="btnShowAlert" class="ui-state-default ui-corner-all" onclick="show_box_model_alertas_sin_atender()" ><img src="../img/bell_go.png" /><span>Alertas</span></button>
                                                                    </td>     
                                                                    <td style="width:55px">
                                                                        <button id="btnShowNotas" class="ui-state-default ui-corner-all" style="padding:1px 1px;" onclick="show_box_model_notas_hoy()" ><img src="../img/note_go.png" /><span>Notas</span></button>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>-->
                                                        <!--/ALERTAS-->
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" class="lineTab ui-widget-header"></td>
                                                    </tr>
                                                </table>
                                                <!--BARRA MENU-->
                                                <table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
                                                    <tr>
                                                        <td id="content_table_tab_AC1">
                                                            <div id="layerTabAC1Busqueda" style="display:block;" class="ui-widget-content">
                                                                <table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
                                                                    <tr>
                                                                        <td valign="top">
                                                                            <div style="margin-top:20px;">
                                                                                <table id="table_tab_busqueda" style="width:100%;" cellpadding="0" cellspacing="0" border="0">
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div id="tabBusquedaManual" class="itemTab pointer border-radius-left ui-widget-content" onclick="_activeTabLayer('table_tab_busqueda','tabBusqueda',this,'content_tab_busqueda','layerTabBusqueda','layerTabBusquedaManual')">
                                                                                                <div class="AitemTab">Busqueda Manual</div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div id="tabBusquedaBase" class="itemTabActive pointer border-radius-left ui-widget-header" onclick="_activeTabLayer('table_tab_busqueda','tabBusqueda',this,'content_tab_busqueda','layerTabBusqueda','layerTabBusquedaBase')">
                                                                                                <div class="text-whit">Busqueda Base</div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div id="tabBusquedaEstado" class="itemTab pointer border-radius-left ui-widget-content" onclick="_activeTabLayer('table_tab_busqueda','tabBusqueda',this,'content_tab_busqueda','layerTabBusqueda','layerTabBusquedaEstado')">
                                                                                                <div class="AitemTab">Busqueda Estado</div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div id="tabBusquedaGestionados" class="itemTab pointer border-radius-left ui-widget-content" onclick="_activeTabLayer('table_tab_busqueda','tabBusqueda',this,'content_tab_busqueda','layerTabBusqueda','layerTabBusquedaGestionados')">
                                                                                                <div class="AitemTab">Busqueda Gestionados</div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div id="tabBusquedaSinGestion" class="itemTab pointer border-radius-left ui-widget-content" onclick="_activeTabLayer('table_tab_busqueda','tabBusqueda',this,'content_tab_busqueda','layerTabBusqueda','layerTabBusquedaSinGestionados')">
                                                                                                <div class="AitemTab">Busqueda Sin Gestion</div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div id="tabBusquedaGlobal" class="itemTab pointer border-radius-left ui-widget-content" onclick="_activeTabLayer('table_tab_busqueda','tabBusqueda',this,'content_tab_busqueda','layerTabBusqueda','layerTabBusquedaGlobal')">
                                                                                                <div class="AitemTab">Busqueda Global</div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                        </td>
                                                                        <td style="width:5px" class="ui-widget-header"></td>
                                                                        <td id="content_tab_busqueda" align="center">
                                                                            <div style="display:none;padding:5px 0;" id="layerTabBusquedaManual" align="center" >
                                                                                <table border="0" cellpadding="0" cellspacing="0">
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div align="left">
                                                                                                <table>
                                                                                                    <tr>
                                                                                                        <td valign="top">
                                                                                                            <div>
                                                                                                                <table>
                                                                                                                    <tr>
                                                                                                                        <td align="right">Tabla</td>
                                                                                                                        <td><select onchange="fillAtencionClienteBusquedaManualCampo()" id="cbTablaBusquedaManualAtencionCliente" class="combo" ><option>--Seleccione--</option></select></td>
                                                                                                                    </tr>
                                                                                                                    <tr>       
                                                                                                                        <td align="right">Campo</td>
                                                                                                                        <td><select class="combo" id="cbCampoBusquedaManualAtencionCliente"><option>--Seleccione--</option></select></td>
                                                                                                                    </tr>
                                                                                                                    <tr>       
                                                                                                                        <td align="right">Dato</td>
                                                                                                                        <td><input maxlength="80" class="cajaForm longCajaForm" type="text" id="txtAtencionBusquedaManualDato" /></td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td></td>    
                                                                                                                        <td>
                                                                                                                            <button onclick="addFilter()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-plus"></span></button>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </table>
                                                                                                            </div>
                                                                                                        </td>
                                                                                                        <td valign="top" align="center">
                                                                                                            <div>
                                                                                                                <table border="0">
                                                                                                                    <tr>
                                                                                                                        <td>
                                                                                                                            <table cellspacing="0" cellpadding="0" border="0">
                                                                                                                                <tr class="ui-state-default">
                                                                                                                                    <td style="width:25px;padding:3px 0;border:1px solid #E0CFC2;"></td>
                                                                                                                                    <td style="width:100px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" align="center">Tabla</td>
                                                                                                                                    <td style="width:100px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" align="center">Campo</td>
                                                                                                                                    <td style="width:100px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" align="center">Data</td>
                                                                                                                                    <td style="width:25px;padding:3px 0;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" align="center" ></td>
                                                                                                                                </tr>
                                                                                                                            </table>
                                                                                                                            <div style="width:370px;height:100px;overflow-y:auto;"><table id="table_filtros"  cellspacing="0" cellpadding="0" border="0"></table></div>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td>
                                                                                                                            <button onclick="MapTableFilter()" class="ui-corner-all ui-state-default"><span class="ui-icon ui-icon-search"></span></button>
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
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div>
                                                                                                <table id="table_busqueda_manual"></table>
                                                                                                <div id="pager_table_busqueda_manual"></div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                            <div style="display:block;padding:5px 0;" id="layerTabBusquedaBase" align="center">
                                                                                <table border="0" cellpadding="0" cellspacing="0">
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div>
                                                                                                <table>
                                                                                                        <!--<tr>
                                                                                                        <td align="right">Campaña</td>
                                                                                                        <td colspan="5"><select id="cbCampaniaBusquedaBase"><option value="0">--Seleccione--</option></select><td>
                                                                                                    </tr>-->
                                                                                                    <tr>
                                                                                                        <td><button onclick="loadClientes_busqueda_base_by_code()" class="ui-state-default ui-corner-all" title="Codigo"><span class="ui-icon ui-icon-search"></span></button></td>
                                                                                                        <td><input onkeyup="if( event.keyCode == 13 ){ loadClientes_busqueda_base_by_code(); }" type="text" id="txtAtencionSearchBaseByCodigo" /></td>
                                                                                                        <td><button onclick="loadClientes_busqueda_base_by_name()" class="ui-state-default ui-corner-all" title="Nombre"><span class="ui-icon ui-icon-search"></span></button></td>
                                                                                                        <td><input onkeyup="if( event.keyCode == 13 ){ loadClientes_busqueda_base_by_name(); }" type="text" id="txtAtencionSearchBaseByName" /></td>
                                                                                                        <td><button onclick="loadClientes_busqueda_base_by_numero_documento()" class="ui-state-default ui-corner-all" id="Dni"><span class="ui-icon ui-icon-search"></span></button></td>
                                                                                                        <td><input onkeyup="if( event.keyCode == 13 ){ loadClientes_busqueda_base_by_numero_documento(); }" type="text" id="txtAtencionSearchBaseByNumeroDocumento" /></td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td><button onclick="loadClientes_busqueda_base_by_tipo_documento()" class="ui-state-default ui-corner-all" title="Ruc"><span class="ui-icon ui-icon-search"></span></button></td>
                                                                                                        <td><input onkeyup="if( event.keyCode == 13 ){ loadClientes_busqueda_base_by_tipo_documento(); }" type="text" id="txtAtencionSearchBaseTipoDocumento" /></td>
                                                                                                        <td><button onclick="loadClientes_busqueda_base_by_phone()" class="ui-state-default ui-corner-all" title="Telefono"><span class="ui-icon ui-icon-search"></span></button></td>
                                                                                                        <td><input onkeyup="if( event.keyCode == 13 ) { loadClientes_busqueda_base_by_phone(); }" type="text" id="txtAtencionSearchBaseByPhone" /></td>
                                                                                                        <td><button onclick="loadClientes_busqueda_base_by_number_account()" class="ui-state-default ui-corner-all" title="Numero Cuenta"><span class="ui-icon ui-icon-search"></span></button></td>
                                                                                                        <td><input onkeyup="if( event.keyCode == 13 ) { loadClientes_busqueda_base_by_number_account(); }" type="text" id="txtAtencionSearchBaseByNumberAccount" /></td>
                                                                                                        <td><button onclick="loadClientes_busqueda_base_by_idcliente_cartera()" class="ui-state-default ui-corner-all" title="idcliente_cartera"><span class="ui-icon ui-icon-search"></span></button></td>
                                                                                                        <td><input onkeyup="if( event.keyCode == 13 ) { loadClientes_busqueda_base_by_idcliente_cartera(); }" type="text" id="txtAtencionSearchBaseByIdClienteCartera" /></td>                                                                                                        
                                                                                                    </tr>
                                                                                                </table>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div>
                                                                                                <table id="table_busqueda_base"></table>
                                                                                                <div id="pager_table_busqueda_base"></div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                            <div style="display:none;padding:5px 0;" id="layerTabBusquedaEstado" align="center">
                                                                                <table>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div>
                                                                                                <table>
                                                                                                    <tr>
                                                                                                        <td>Estado</td>
                                                                                                        <td><select class="combo" id="cbEstadosLLamadaBusquedaEstado" onchange="loadClientes_busqueda_estado(this.value)"><option value="0">--Seleccione--</option></select></td>
                                                                                                    </tr>
                                                                                                </table>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div>
                                                                                                <table id="table_busqueda_estado" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                                <div id="pager_table_busqueda_estado"></div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                            <div style="display:none;padding:5px 0;" id="layerTabBusquedaGestionados" align="center">
                                                                                <table>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div>
                                                                                                <table id="table_busqueda_gestionados" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                                <div id="pager_table_busqueda_gestionados"></div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>     
                                                                            </div>
                                                                            <div style="display:none;padding:5px 0;" id="layerTabBusquedaSinGestionados" align="center">
                                                                                <table>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div>
                                                                                                <table id="table_busqueda_sin_gestion" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                                <div id="pager_table_busqueda_sin_gestion"></div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>     
                                                                            </div>
                                                                            <div style="display:none;padding:5px 0;" id="layerTabBusquedaGlobal" align="center">
                                                                                <table>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div>
                                                                                                <table id="table_busqueda_global" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                                <div id="pager_table_busqueda_global" ></div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <div id="layerTabAC1Resultado" style="display:none;background-color:#FFFFFF;" class="" align="center"> 
                                                                <!--DATOS CLIENTE-->
                                                                <!--<div id="lbMessageFechasCartera" style="font-size:11px;font-weight:bold;position:fixed; width:120px; right:0px;display:none;padding:3px;" class="ui-state-error ui-corner-all" ></div>-->
                                                                <div id="PanelTableDatosCliente" style="display:block;" align="center">
                                                                    <table cellpadding="0" cellspacing="0">
                                                                        <tr>
                                                                            <td id="table_datos_cliente"></td>
                                                                        </tr>                                                                        
                                                                    </table>
                                                                    <!-- <table id="table_direccion_vista_rapida" cellpadding="0" cellspacing="0" border="0" ></table> -->
                                                                    <div id="PanelTableRepresentanteLegal" style="display:none;padding:5px 10px;" align="center" >
                                                                        <br>
                                                                        <table id="table_representante_legal" cellpadding="0" cellspacing="0" border="0" style="display:block;" ></table>
                                                                        <br>
                                                                    </div>
                                                                    <div id="table_aval_direccion" style="display:none;">
                                                                        
                                                                    </div>
                                                                    <div id="table_aval_telf" style="display:none;">
                                                                        
                                                                    </div>
                                                                </div>
                                                                <!--DATOS ADICIONALES CLIENTE-->
                                                                <div id="PanelTableDatosAdicionalesCliente" style="display:none;padding:5px 10px;" align="center" >
                                                                    <table>	
                                                                        <tr>
                                                                            <td align="center" class="ui-state-active" style="padding:4px 3px;">Correos</td>
                                                                            <td align="center" class="ui-state-active" style="padding:4px 3px;">Horarios de Atencion</td>

                                                                        </tr>
                                                                        <tr>
                                                                            <td valign="top">
                                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                                    <tr>
                                                                                        <td align="center" style="width:30px;" class="ui-widget-header ui-corner-tl">&nbsp;</td>
                                                                                        <td align="center" style="width:200px;" class="ui-widget-header">Correo</td>
                                                                                        <td align="center" style="width:30px;" class="ui-widget-header">&nbsp;</td>
                                                                                        <td align="center" style="width:20px;" class="ui-widget-header ui-corner-tr">&nbsp;</td>
                                                                                    </tr>
                                                                                </table>
                                                                                <div style="width:160px;height:50px;overflow:auto;" >
                                                                                    <table id="table_datos_cliente_correo" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                </div>
                                                                                <div class="ui-widget-header ui-corner-bottom" style="height:20px;"></div>
                                                                            </td>
                                                                            <td valign="top">
                                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                                    <tr>
                                                                                        <td align="center" style="width:30px;" class="ui-widget-header ui-corner-tl">&nbsp;</td>
                                                                                        <td align="center" style="width:100px;" class="ui-widget-header">Horario</td>
                                                                                        <td align="center" style="width:30px;" class="ui-widget-header">&nbsp;</td>
                                                                                        <td align="center" style="width:20px;" class="ui-widget-header ui-corner-tr">&nbsp;</td>
                                                                                    </tr>
                                                                                </table>
                                                                                <div style="width:160px;height:50px;overflow:auto;" >
                                                                                    <table id="table_datos_cliente_horario_atencion" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                </div>
                                                                                <div class="ui-widget-header ui-corner-bottom" style="height:20px;"></div>
                                                                            </td>

                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                                <!--/DATOS ADICIONALES CLIENTE-->
                                                                <!--DATOS CLIENTE-->
                                                                <div id="result_tmp"><!--PANEL GUARDAR LLAMADA-->
                                                                    <table border="0" width="100%" cellpadding="0" cellspacing="0">
                                                                        <!-- GUARDAR LLAMADA -->
                                                                        <tr>
                                                                            <td>
                                                                                <div id="layerFormAtencionLlamada"  style="display:block;width:100%;" >
                                                                                    <!--FORM DE ATENCION LLAMADA-->
                                                                                    <!--Inicio Dato de la cartera a la que pertenece el cliente  -->
                                                                                    <table style="display:none" id="tblDatoCartByCliente">
                                                                                        <tr>
                                                                                            <td><input type="text" id="txtFechaCreacion_tblDCBC" /></td>
                                                                                            <td><input type="text" id="txtFechaModificacion_tblDCBC" /></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                    <!--Fin Dato de la cartera a la que pertenece el cliente  -->

                                                                                    <table cellpadding="0" cellspacing="4" style="width: 100%;">
                                                                                        <tr valign="top">
                                                                                            <td>
                                                                                                <table cellpadding="0" cellspacing="0" >
                                                                                                    <tr >
                                                                                                        <td align="center">
                                                                                                            
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr valign="top">
                                                                                                        <td>

                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </table>
                                                                                            </td>
                                                                                            <td valign="top">
                                                                                                <input type="hidden" id="HdIdLlamadaAtencionCliente" />
                                                                                                <input type="hidden" id="HdIdCpgAtencionCliente" />
                                                                                                <input type="hidden" id="HdIdTransaccionAtencionCliente" />
                                                                                                <!-- FIDELIZACION -->
                                                                                                <?php if($_SESSION['cobrast']['idservicio']!=2 && $_SESSION['cobrast']['idservicio']!=3 ){ ?>
                                                                                                <table cellpadding="0" cellspacing="0" border="0" style="z-index:100;width: 100%;" class="ui-corner-all">
                                                                                                    <!--REALIZAR LLAMADA-->
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <div align="left">
                                                                                                                <table>
                                                                                                                    <tr>
																					                                    <td>
    																						                                
                                                                                                                            <!-- <button id="btnShowGridTelefono" class="ui-state-default ui-corner-all" onclick="_slide3(this,'divGridTelefonos',0)"><img src="../img/telefono.jpg"><span>Telefonos</span></button> -->
                                                                                                                            
                                                                                                                            <div class="boton_estilo fondo_gradiente_azul" style="width:120px;" id="btnShowGridTelefono" onclick="_slide3(this,'divGridTelefonos',0)">
                                                                                                                                <img src="../img/kcall.png" width="25" class="boton_imagen" style="position: absolute;left: 6px;top:0px;">
                                                                                                                                <div class="lin_vet"></div> 
                                                                                                                                <span class="boton_letra">TELEFONO</span>
                                                                                                                            </div>    
                                                                                                                            <div id="divGridTelefonos" style="display:none;position:absolute;border:2px solid #e1cfc3;background : #f4f1ec; padding : 10px;z-index: 2" align="center" >
                                                                                                                            	<div id="divTbTelefonosCliente">
                                    																								<table id="table_telefonos_cliente" cellpadding="0" cellspacing="0" border="0"></table>
                                    																								<div id="pager_table_telefonos_cliente"></div>
                                    																							</div>
                                                                                                                        	</div>
   																					                                    </td>	
                                                                                                                        <td align="center" style="padding: 5px;">Telefono</td>
                                                                                                                        <td><input type="text" class="cajaForm longCajaForm" id="txtAtencionClienteNumeroCall" disabled="disabled" /></td>
                                                                                                                        <!--<td><button id="btnAtencionClientePhoneCall" onclick="Call(1)" class="ui-state-default ui-corner-all"><img src="../img/telephone_go.png" /><span>Llamar</span></button></td>-->
                                                                                                                        
                                                                                                                        <td align="center" style="padding: 5px;">Sin telefono</td>
                                                                                                                        <td><input type="checkbox" name="chkbSintelf" id="chkbSintelf" /></td>

                                                                                                                        <td>
                                                                                                                            <button style="display:none" id="btnAtencionClientePhoneCallNeotel" onclick="CallNeotel(1)" class="ui-state-default ui-corner-all"><img src="../img/telephone_go.png" /><span>Llamar</span></button>
                                                                                                                            <!-- Call(1)-->
                                                                                                                            <button style="display:none" id="btnAtencionClientePhoneCall" onclick="Call(1)" class="ui-state-default ui-corner-all"><img src="../img/telephone_go.png" /><span>Llamar</span></button>                                                                                                                            
                                                                                                                        </td>                                                                                                                                                                                                                                               
                                                                                                                        <!--<td><button onclick="Hungup()" class="ui-state-default ui-corner-all"><img src="../img/telephone_delete.png" /><span>Colgar</span></button></td>-->

                                                                                                                        <td>
                                                                                                                            <button style="display:none" id="btnAtencionClientePhoneHungupNeotel" onclick="Hungup_neotel()" class="ui-state-default ui-corner-all"><img src="../img/telephone_delete.png" /><span>Colgar</span></button>
                                                                                                                            <button style="display:none" id="btnAtencionClientePhoneHungup" onclick="Hungup()" class="ui-state-default ui-corner-all"><img src="../img/telephone_delete.png" /><span>Colgar</span></button>
                                                                                                                        </td>
                                                                                                                        <!--<td><button class="ui-state-default ui-corner-all" onclick="show_window_actualizar_anexo(this)" ><img src="../img/telephone_edit.png" /><span>Ingresar Anexo</span></button></td>-->
                                                                                                                        <td>
                                                                                                                            <button style="display:none" id="btnAtencionClienteShowAnexoNeotel" class="ui-state-default ui-corner-all" onclick="show_window_actualizar_anexo_neotel(this)" ><img src="../img/telephone_edit.png" /><span>Usuario Neotel</span></button>
                                                                                                                            <button style="display:none" id="btnAtencionClienteShowAnexo" class="ui-state-default ui-corner-all" onclick="show_window_actualizar_anexo(this)" ><img src="../img/telephone_edit.png" /><span>Ingresar Anexo</span></button>
                                                                                                                        </td>                                                                                                                                                                                                                                                
                                                                                                                        <td><input type="hidden" id="HdIdTelefono" /></td>
                                                                                                                        <td>
                                                                                                                            <button id="btnACAgregarTelefono" style="display:none;" class="ui-state-default ui-corner-all" onclick="_slide3(this,'DialogAddTelefonoCartera',0)"><img src="../img/telephone_edit.png"><span>Agregar Telefono</span></button>
                                                                                                                            <div id="DialogAddTelefonoCartera" style="display:none;position:absolute;border:2px solid #e1cfc3;background : #f4f1ec; padding : 10px;" align="center" >
                                                                                                                                <table cellpadding="0" cellspacing="0">
                                                                                                                                    <tr>
                                                                                                                                        <td align="left">Numero</td>
                                                                                                                                        <td align="left"><input onkeypress="return isTelefono(event)" type="text" class="cajaForm" style="width:100px;" maxlength="9" id="txtNumero2TelefonoAtencionCliente" /><input type="hidden" id="hdIdAddTelefonoCartera" /></td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        <td align="left">Anexo</td>
                                                                                                                                        <td align="left"><input type="text" class="cajaForm" style="width:100px;" id="txtAnexoTelefonoAtencion" /></td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        <td align="left">Tipo</td>
                                                                                                                                        <td align="left"><select id="cbTipoTelefonoAtencion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>     
                                                                                                                                        <td align="left">Referencia</td>
                                                                                                                                        <td align="left"><select id="cbReferenciaTelefonoAtencion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>       
                                                                                                                                        <td align="left">Linea</td>
                                                                                                                                        <td align="left"><select id="cbLineaTelefonoAtencion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        <td align="left">Origen</td>
                                                                                                                                        <td align="left"><select id="cbOrigenTelefonoAtencion" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        <td align="left" colspan="2" >
                                                                                                                                            <textarea id="txtObservacionTelefonoAtencion" class="textareaForm" style="width:160px;height:30px; font-size:11px"></textarea>
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        <td colspan="2">
                                                                                                                                            <div align="center">
                                                                                                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                                                                                                    <tr>
                                                                                                                                                        <td><button onclick="save_telefono_atencion_cliente()" class="ui-state-default ui-corner-all" style="font-size:11px">Grabar</button></td>
                                                                                                                                                        <td style="width:10px;"></td>
                                                                                                                                                        <td><button onclick="update_telefono_atencion_cliente()" class="ui-state-default ui-corner-all" style="font-size:11px">Actualizar</button></td>
                                                                                                                                                    </tr>
                                                                                                                                                </table>
                                                                                                                                            </div>
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                </table>
                                                                                                                            </div>
                                                                                                                            <!-- FIDELIZACION -->
                                                                                                                            <?php if($_SESSION['cobrast']['idservicio']!=2){ ?>
                                                                                                                            <!--<td>
                                                                                                                                <button class="ui-state-default ui-corner-all" onclick="$('#layerTabAC2Visita').slideToggle();" id="btnVisitas"><img src="../img/telephone_edit.png"><span>Visitas</span></button>
                                                                                                                            </td>-->
                                                                                                                            <td>
                                                                                                                                <button style="display:none;" class="ui-state-default ui-corner-all" onclick="$('#layerTabAC2CentroPago').slideToggle();" id="btnCentroPagos"><img src="../img/telephone_edit.png"><span>Centro de pagos</span></button>
                                                                                                                            </td>
                                                                                                                            <?php } ?>
                                                                                                                            <!-- FIDELIZACION -->
                                                                                                                            <td>
                                                                                                                                <button style="font-size:11px;padding:1px;display:none;" class="ui-state-default ui-corner-all" onclick="$('#DialogNuevoCorreo').dialog('open');" id="btnAgregarCorreo"><span class="ui-icon ui-icon-mail-closed" style="float:left;"></span>Agregar Correo</button>
                                                                                                                            </td>
                                                                                                                            <td>
                                                                                                                                <button style="display:none;" id="btnGestionTelefonos" class="ui-state-default ui-corner-all" onclick=""><img src="../img/telephone_blue-128.png" width="16"><span>Telefono del Titular</span></button>
                                                                                                                            </td>
                                                                                                                            <td>
                                                                                                                                <div style="display:none;" id="Dialoggestiontelefonos_cobranzas">
                                                                                                                                    <div id="Lista_telf_cobranzas">
                                                                                                                                        <table id="table_Lista_telf_cobranzas" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                                                                        <div id="pager_Lista_telf_cobranzas"></div>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                                <div style="display:none;" id="Dialoggestiontelefonos_cobranzas_save">
                                                                                                                                    <table>
                                                                                                                                        <tr><td>NUMERO</td><td><input id="txtnumero_cob_save" type="text" name="" class="cajaForm"></td></tr>
                                                                                                                                        <tr><td>ANEXO</td><td><input id="anexo_cob_save" type="text" name="" class="cajaForm"></td></tr>
                                                                                                                                        <tr><td>TIPO</td><td><select id="slctipo_cob_save" class="combo" style="width:148px;"></select></td></tr>
                                                                                                                                        <tr><td>REFERENCIA</td><td><select id="slcreferencia_cob_save"  class="combo" style="width:148px;"></select></td></tr>
                                                                                                                                        <tr><td>LINEA</td><td><select id="slclinea_cob_save"  class="combo" style="width:148px;"></select></td></tr>
                                                                                                                                        <tr><td>ORIGEN</td><td><select id="slcorigem_cob_save"  class="combo" style="width:148px;"></select></td></tr>
                                                                                                                                        <tr><td>CONDICION</td><td><select id="slccondi_cob_save"  class="combo" style="width:148px;"><option value="CORRECTO">CORRECTO</option><option value="INCORRECTO">INCORRECTO</option><option value="NO VALIDA">NO VALIDA</option></select></td></tr>
                                                                                                                                        <tr><td>OBSERVACION</td><td><textarea id="areaobs_cob_save" class="textareaForm"></textarea></td></tr>
                                                                                                                                        </tr>
                                                                                                                                    </table>
                                                                                                                                </div>
                                                                                                                                <div style="display:none;" id="Dialoggestiontelefonos_cobranzas_edit">
                                                                                                                                    <input type="hidden" id="hdidtelefono_edit_andina" />

                                                                                                                                    <input type="hidden" id="hdtxtnumero_cob_edit" />
                                                                                                                                    <input type="hidden" id="hdanexo_cob_edit" />
                                                                                                                                    <input type="hidden" id="hdslctipo_cob_edit" />
                                                                                                                                    <input type="hidden" id="hdslcreferencia_cob_edit" />
                                                                                                                                    <input type="hidden" id="hdslclinea_cob_edit" />
                                                                                                                                    <input type="hidden" id="hdslcorigem_cob_edit" />
                                                                                                                                    <input type="hidden" id="hdslcstate_cob_edit" />
                                                                                                                                    <input type="hidden" id="hdslcstatus_cob_edit" />
                                                                                                                                    <input type="hidden" id="hdslccondi_cob_edit" />
                                                                                                                                    <input type="hidden" id="hdareaobs_cob_edit" />
                                                                                                                                    <table>
                                                                                                                                        <tr><td>NUMERO</td><td><input disabled="disabled" id="txtnumero_cob_edit" type="text" name="" class="cajaForm"></td></tr>
                                                                                                                                        <tr><td>ANEXO</td><td><input id="anexo_cob_edit" type="text" name="" class="cajaForm"></td></tr>
                                                                                                                                        <tr><td>TIPO</td><td><select id="slctipo_cob_edit" class="combo" style="width:148px;"></select></td></tr>
                                                                                                                                        <tr><td>REFERENCIA</td><td><select id="slcreferencia_cob_edit"  class="combo" style="width:148px;"></select></td></tr>
                                                                                                                                        <tr><td>LINEA</td><td><select id="slclinea_cob_edit"  class="combo" style="width:148px;"></select></td></tr>
                                                                                                                                        <tr><td>ORIGEN</td><td><select id="slcorigem_cob_edit"  class="combo" style="width:148px;"></select></td></tr>
                                                                                                                                        <tr><td>STATE</td><td><select id="slcstate_cob_edit"  class="combo" style="width:148px;"><option value='1'>ACTIVO</option><option value='0'>NO ACTIVO</option></select></td></tr>
                                                                                                                                        <tr><td>STATUS</td><td><select id="slcstatus_cob_edit"  class="combo" style="width:148px;"><option value='1'>ALTA</option><option value='0'>BAJA</option></select></td></tr>
                                                                                                                                        <tr><td>CONDICION</td><td><select id="slccondi_cob_edit"  class="combo" style="width:148px;"><option value="CORRECTO">CORRECTO</option><option value="INCORRECTO">INCORRECTO</option><option value="NO VALIDA">NO VALIDA</option></select></td></tr>
                                                                                                                                        <tr><td>OBSERVACION</td><td><textarea id="areaobs_cob_edit" class="textareaForm"></textarea></td></tr>
                                                                                                                                        </tr>
                                                                                                                                    </table>
                                                                                                                                </div>
                                                                                                                                <div class="boton_estilo fondo_gradiente_azul" style="width:120px;" id="idmantemiento_telf_cobranzas">
                                                                                                                                    <img src="../img/telephone_blue-128.png" width="25" class="boton_imagen" style="position: absolute;left: 6px;top:0px;">
                                                                                                                                    <div class="lin_vet"></div> 
                                                                                                                                    <span class="boton_letra">TELEFONO</span>
                                                                                                                                </div>
                                                                                                                            </td>
                                                                                                                            <td>
                                                                                                                                <div style="display:none;" id="Dialoggestioncorreo_cobranzas">
                                                                                                                                    <div id="Lista_Correo_cobranzas">
                                                                                                                                        <table id="table_Lista_Correo_cobranzas" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                                                                        <div id="pager_Lista_Correo_cobranzas"></div>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                                <div style="display:none;" id="Dialoggestionmail_cobranzas_save">
                                                                                                                                    <table>
                                                                                                                                        <tr><td>CORREO</td><td><input id="txtcorreo_mail_cob_save" type="text" name="" class="cajaForm"></td></tr>
                                                                                                                                        <tr><td>OBS</td><td><textarea id="obs_mail_cob_save" class="textareaForm"></textarea></td></tr>
                                                                                                                                    </table>
                                                                                                                                </div>
                                                                                                                                <div style="display:none;" id="Dialoggestionmail_cobranzas_edit">
                                                                                                                                    <input type="hidden" id="hdidcorreo_andina" />
                                                                                                                                    <table>
                                                                                                                                        <tr><td>CORREO</td><td><input id="txtcorreo_mail_cob_edit" type="text" name="" class="cajaForm"></td></tr>
                                                                                                                                        <tr><td>OBS</td><td><textarea id="obs_mail_cob_edit" class="textareaForm"></textarea></td></tr>
                                                                                                                                        <tr><td>STATUS</td><td><select id="slcstatusmail_cob_edit"  class="combo" style="width:148px;"><option value='1'>ALTA</option><option value='0'>BAJA</option></select></td></tr>
                                                                                                                                    </table>
                                                                                                                                </div>
                                                                                                                                <div class="boton_estilo fondo_gradiente_azul" style="width:107px;" id="idmantemiento_correo_cobranzas">
                                                                                                                                    <img src="../img/correo-electronico-azul.png" width="22" class="boton_imagen" style="position: absolute;left: 6px;top:2px;">
                                                                                                                                    <div class="lin_vet"></div> 
                                                                                                                                    <span class="boton_letra">CORREO</span>
                                                                                                                                </div>                                                                                                                                
                                                                                                                            </td>

                                                                                                                            <td>
                                                                                                                                <div style="display:none;" id="Dialoggestiondireccion_cobranzas">
                                                                                                                                    <div id="Lista_Direc_cobranzas">
                                                                                                                                        <table id="table_Lista_Direc_cobranzas" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                                                                        <div id="pager_Lista_Direc_cobranzas"></div>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                                <div style="display:none;" id="Dialoggestiondireccion_cobranzas_save">
                                                                                                                                    <table width=300>
                                                                                                                                        <tr><td>DIRECCION</td><td><input id="txtdireccion_cob_save" type="text" name="" class="cajaForm"></td></tr>
                                                                                                                                        <tr><td>DEPARTAMENTO</td><td><select id="slcdepa_dir_cob_save" class="combo" style="width:190px;"></select></td></tr>
                                                                                                                                        <tr><td>PROVINCIA</td><td><select id="slcprov_dir_cob_save" class="combo" style="width:190px;"></select></td></tr>
                                                                                                                                        <tr><td>DISTRITO</td><td><select id="slcdistri_dir_cob_save" class="combo" style="width:190px;"></select></td></tr>
                                                                                                                                        <tr><td>REGION</td><td><input id="txtregion_dir_cob_save" type="text" name="" class="cajaForm"></td></tr>
                                                                                                                                        <tr><td>ZONA</td><td><input id="txtzona_dir_cob_save" type="text" name="" class="cajaForm"></td></tr>
                                                                                                                                        <tr><td>CODIGO_POSTAL</td><td><input id="txtcodpostdir_cob_save" type="text" name="" class="cajaForm"></td></tr>
                                                                                                                                        <tr><td>NUMERO</td><td><input id="txtnumero_dir_cob_save" type="text" name="" class="cajaForm"></td></tr>
                                                                                                                                        <tr><td>CALLE</td><td><input id="txtcalle_dir_cob_save" type="text" name="" class="cajaForm"></td></tr>
                                                                                                                                        <tr><td>REFERENCIA</td><td><input id="txtref_dir_cob_save" type="text" name="" class="cajaForm"></td></tr>
                                                                                                                                        <tr><td>TIPO REFERENCIA</td><td><select id="slcref_dir_cob_save" style="width:190px;" class="combo"></select></td></tr>
                                                                                                                                        <tr><td>ORIGEN</td><td><select id="slcorig_dir_cob_save" style="width:190px;" class="combo"></select></td></tr>
                                                                                                                                        <tr><td>CONDICION</td><td><select id="slccondi_dir_cob_save"  class="combo" style="width:190px;"><option value="CORRECTO">CORRECTO</option><option value="INCORRECTO">INCORRECTO</option><option value="NO VALIDA">NO VALIDA</option></select></td></tr>
                                                                                                                                        <tr><td>OBSERVACION</td><td><textarea id="areaobs_dir_cob_save" class="textareaForm"></textarea></td></tr>
                                                                                                                                        </tr>
                                                                                                                                    </table>
                                                                                                                                </div>
                                                                                                                                <div style="display:none;" id="Dialoggestiondireccion_cobranzas_edit">
                                                                                                                                    <input type="hidden" id="hdiddireccion_andina" />
                                                                                                                                    <input type="hidden" id="hddir_dep_andina" />
                                                                                                                                    <input type="hidden" id="hddir_prov_andina" />
                                                                                                                                    <input type="hidden" id="hddir_dis_andina" />
                                                                                                                                    <input type="hidden" id="hddir_tipref_andina" />
                                                                                                                                    <input type="hidden" id="hddir_orig_andina" />
                                                                                                                                    <table width=300>
                                                                                                                                        <tr><td>DIRECCION</td><td><input id="txtdireccion_cob_edit" type="text" name="" class="cajaForm"></td></tr>
                                                                                                                                        <tr><td>DEPARTAMENTO</td><td><select id="slcdepa_dir_cob_edit" class="combo" style="width:190px;"></select></td></tr>
                                                                                                                                        <tr><td>PROVINCIA</td><td><select id="slcprov_dir_cob_edit" class="combo" style="width:190px;"></select></td></tr>
                                                                                                                                        <tr><td>DISTRITO</td><td><select id="slcdistri_dir_cob_edit" class="combo" style="width:190px;"></select></td></tr>
                                                                                                                                        <tr><td>REGION</td><td><input id="txtregion_dir_cob_edit" type="text" name="" class="cajaForm"></td></tr>
                                                                                                                                        <tr><td>ZONA</td><td><input id="txtzona_dir_cob_edit" type="text" name="" class="cajaForm"></td></tr>
                                                                                                                                        <tr><td>CODIGO_POSTAL</td><td><input id="txtcodpostdir_cob_edit" type="text" name="" class="cajaForm"></td></tr>
                                                                                                                                        <tr><td>NUMERO</td><td><input id="txtnumero_dir_cob_edit" type="text" name="" class="cajaForm"></td></tr>
                                                                                                                                        <tr><td>CALLE</td><td><input id="txtcalle_dir_cob_edit" type="text" name="" class="cajaForm"></td></tr>
                                                                                                                                        <tr><td>REFERENCIA</td><td><input id="txtref_dir_cob_edit" type="text" name="" class="cajaForm"></td></tr>
                                                                                                                                        <tr><td>TIPO REFERENCIA</td><td><select id="slcref_dir_cob_edit" style="width:190px;" class="combo"></select></td></tr>
                                                                                                                                        <tr><td>ORIGEN</td><td><select id="slcorig_dir_cob_edit" style="width:190px;" class="combo"></select></td></tr>
                                                                                                                                        <tr><td>CONDICION</td><td><select id="slccondi_dir_cob_edit"  class="combo" style="width:190px;"><option value="CORRECTO">CORRECTO</option><option value="INCORRECTO">INCORRECTO</option><option value="NO VALIDA">NO VALIDA</option></select></td></tr>
                                                                                                                                        <tr><td>ESTADO</td><td><select id="slcestado_cob_edit"  class="combo" style="width:190px;"><option value='1'>ALTA</option><option value='0'>BAJA</option></select></td></tr>
                                                                                                                                        <tr><td>OBSERVACION</td><td><textarea id="areaobs_dir_cob_edit" class="textareaForm"></textarea></td></tr>
                                                                                                                                        </tr>
                                                                                                                                    </table>
                                                                                                                                </div>
                                                                                                                                <div class="boton_estilo fondo_gradiente_azul" style="width:120px;" id="idmantemiento_direccion_cobranzas">
                                                                                                                                    <img src="../img/location.png" width="22" class="boton_imagen" style="position: absolute;left: 6px;top:1px;">
                                                                                                                                    <div class="lin_vet"></div> 
                                                                                                                                    <span class="boton_letra">DIRECCION</span>
                                                                                                                                </div>                                                                                                                                
                                                                                                                            </td>
                                                                                                                            <td>

                                                                                                                                <!-- TELEFONO CONTACTO -->

                                                                                                                                <div style="display:none;" id="Dialog_contacto_cobranzas">
                                                                                                                                    <br>
                                                                                                                                    <table id="table_Lista_contacto_cobranzas"></table>
                                                                                                                                    <div id="pager_Lista_contacto_cobranzas"></div>
                                                                                                                                </div>
                                                                                                                                <div class="boton_estilo fondo_gradiente_azul" style="width:128px;" id="idmantemiento_contactos_cobranzas">
                                                                                                                                    <img src="../img/user_green.png" width="22" class="boton_imagen" style="position: absolute;left: 6px;top:1px;">
                                                                                                                                    <div class="lin_vet"></div> 
                                                                                                                                    <span class="boton_letra">CONTACTOS</span>
                                                                                                                                </div>

                                                                                                                                <div style="display:none;" id="Dialog_contacto_telefono">
                                                                                                                                    <br>
                                                                                                                                    <input type="hidden" name="" id="hpidpersona">
                                                                                                                                    <!-- <input type="hidden" name="" id="hpidpersona_vis"> -->
                                                                                                                                    <table>
                                                                                                                                        <tr>
                                                                                                                                            <td><input  type="hidden" name="" style="width: 100px;" id="idtelefono_pers"></td>
                                                                                                                                            <td style="font-size: 10px;">NUMERO</td>
                                                                                                                                            <td><input  type="text" name="" style="width: 100px;" id="contactopers_nro"></td>
                                                                                                                                            <td>&nbsp;</td>
                                                                                                                                            <td style="font-size: 10px;">ORIGEN</td>
                                                                                                                                            <td><select style="width: 100px;" id="pers_origen"></select></td>
                                                                                                                                            <td>&nbsp;</td>
                                                                                                                                            <td style="font-size: 10px;">TIPO_TELEFONO</td>
                                                                                                                                            <td><select style="width: 100px;" id="pers_tip_telf"></select></td>
                                                                                                                                            <td>&nbsp;</td>
                                                                                                                                            <td style="font-size: 10px;">LINEA_TELEFONO</td>
                                                                                                                                            <td><select style="width: 100px;" id="pers_lin_telf"></select></td>
                                                                                                                                            <td>&nbsp;</td>
                                                                                                                                            <td><input type="button" value="AGREGAR" name="" id="idenviardata"></td>
                                                                                                                                            <td>&nbsp;</td>
                                                                                                                                            <td><input type="button" value="CLEAR" name="" id="idlimpiardata"></td>
                                                                                                                                        </tr>
                                                                                                                                    </table>

                                                                                                                                    <br>
                                                                                                                                    <div style="overflow: auto;background-color: #616060;padding: 15px 0;" class="">
                                                                                                                                        <table id="telf_contacto" cellspacing="0" cellpadding="0" style="margin: 0 auto;box-shadow: 0 0 20px 0px black;background-color: #FFFFFF;">
                                                                                                                                            <thead> 
                                                                                                                                            <tr class="ui-state-default"> 
                                                                                                                                                <th align="center" style="font-size: 10px;width: 50px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #8f9192; border-top: 1px solid #8f9192; border-bottom: 1px solid #8f9192; border-left: 1px solid #8f9192;display:none;">IDTELEFONO_PERS</th>
                                                                                                                                                <th align="center" style="font-size: 10px;width: 100px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #8f9192; border-top: 1px solid #8f9192; border-bottom: 1px solid #8f9192; border-left: 1px solid #8f9192;">NUMERO</th>
                                                                                                                                                <th align="center" style="font-size: 10px;width: 200px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #8f9192; border-top: 1px solid #8f9192; border-bottom: 1px solid #8f9192;display:none;">IDORIGEN</th>
                                                                                                                                                <th align="center" style="font-size: 10px;width: 200px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #8f9192; border-top: 1px solid #8f9192; border-bottom: 1px solid #8f9192;">ORIGEN</th>
                                                                                                                                                <th align="center" style="font-size: 10px;width: 200px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #8f9192; border-top: 1px solid #8f9192; border-bottom: 1px solid #8f9192;display:none;">IDTIPO_TELEFONO</th>
                                                                                                                                                <th align="center" style="font-size: 10px;width: 200px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #8f9192; border-top: 1px solid #8f9192; border-bottom: 1px solid #8f9192;">TIPO_TELEFONO</th>
                                                                                                                                                <th align="center" style="font-size: 10px;width: 200px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #8f9192; border-top: 1px solid #8f9192; border-bottom: 1px solid #8f9192;display:none;">IDLINEA_TELEFONO</th>
                                                                                                                                                <th align="center" style="font-size: 10px;width: 200px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #8f9192; border-top: 1px solid #8f9192; border-bottom: 1px solid #8f9192;">LINEA_TELEFONO</th>
                                                                                                                                                <th align="center" style="font-size: 10px;width: 50px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #8f9192; border-top: 1px solid #8f9192; border-bottom: 1px solid #8f9192;display:none;">ESTADO</th>
                                                                                                                                                <th align="center" style="font-size: 10px;width: 110px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #8f9192; border-top: 1px solid #8f9192; border-bottom: 1px solid #8f9192;display:none;">IDPERSONA</th>
                                                                                                                                                <th align="center" style="font-size: 10px;width: 20px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #8f9192; border-top: 1px solid #8f9192; border-bottom: 1px solid #8f9192;"></th>
                                                                                                                                                <th align="center" style="font-size: 10px;width: 25px; padding: 3px 0pt; border-right: 1px solid #8f9192; border-top: 1px solid #8f9192; border-bottom: 1px solid #8f9192;">&nbsp;</th>
                                                                                                                                            </tr>
                                                                                                                                            </thead>
                                                                                                                                            <tbody style=""></tbody>
                                                                                                                                        </table>
                                                                                                                                    </div>
                                                                                                                                </div>

                                                                                                                                <!-- CORREO CONTACTO -->

                                                                                                                                <div style="display:none;" id="Dialog_contacto_correo">

                                                                                                                                    <br>
                                                                                                                                    <input type="hidden" name="" id="hpidpersona">
                                                                                                                                    <table>
                                                                                                                                        <tr>
                                                                                                                                            <td><input  type="hidden" name="" style="width: 100px;" id="idemail_pers"></td>
                                                                                                                                            <td style="font-size: 10px;">NUMERO</td>
                                                                                                                                            <td><input  type="text" name="" style="width: 100px;" id="email"></td>                                                                                                                                            
                                                                                                                                            <td>&nbsp;</td>
                                                                                                                                            <td><input type="button" value="AGREGAR" name="" id="idenviarmail"></td>
                                                                                                                                            <td>&nbsp;</td>
                                                                                                                                            <td><input type="button" value="CLEAR" name="" id="idlimpiarmail"></td>
                                                                                                                                        </tr>
                                                                                                                                    </table>

                                                                                                                                    <br>

                                                                                                                                    <div style="overflow: auto;background-color: #616060;padding: 15px 0;" class="">
                                                                                                                                        <table id="mail_contacto" cellspacing="0" cellpadding="0" style="margin: 0 auto;box-shadow: 0 0 20px 0px black;background-color: #FFFFFF;">
                                                                                                                                            <thead> 
                                                                                                                                            <tr class="ui-state-default"> 
                                                                                                                                                <th align="center" style="font-size: 10px;width: 50px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #8f9192; border-top: 1px solid #8f9192; border-bottom: 1px solid #8f9192; border-left: 1px solid #8f9192;display:none;">IDEMAIL_PERS</th>
                                                                                                                                                <th align="center" style="font-size: 10px;width: 100px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #8f9192; border-top: 1px solid #8f9192; border-bottom: 1px solid #8f9192; border-left: 1px solid #8f9192;">EMAIL</th>
                                                                                                                                                <th align="center" style="font-size: 10px;width: 200px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #8f9192; border-top: 1px solid #8f9192; border-bottom: 1px solid #8f9192;display:none;">ESTADO</th>
                                                                                                                                                <th align="center" style="font-size: 10px;width: 200px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #8f9192; border-top: 1px solid #8f9192; border-bottom: 1px solid #8f9192;display:none;">IDCLIENTE</th>
                                                                                                                                                <th align="center" style="font-size: 10px;width: 110px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #8f9192; border-top: 1px solid #8f9192; border-bottom: 1px solid #8f9192;display:none;">IDPERSONA</th>
                                                                                                                                                <th align="center" style="font-size: 10px;width: 20px; white-space: pre-line; padding: 3px 0pt; border-right: 1px solid #8f9192; border-top: 1px solid #8f9192; border-bottom: 1px solid #8f9192;"></th>
                                                                                                                                                <th align="center" style="font-size: 10px;width: 25px; padding: 3px 0pt; border-right: 1px solid #8f9192; border-top: 1px solid #8f9192; border-bottom: 1px solid #8f9192;">&nbsp;</th>
                                                                                                                                            </tr>
                                                                                                                                            </thead>
                                                                                                                                            <tbody style=""></tbody>
                                                                                                                                        </table>
                                                                                                                                    </div>
                                                                                                                                </div>

                                                                                                                            </td>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </table>
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <!--/REALIZAR LLAMADA-->
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <div class="">
                                                                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                                                                    <tr>
                                                                                                                        <td style="width:130px;">
                                                                                                                            <div style="display:inline;"><label>&nbsp;Estado de Llamada</label></div>
                                                                                                                        </td>
                                                                                                                        <td><select id="cbLlamadaEstado" class="combo"  onchange="txt_estado_observacion()" ><option value="0">--Seleccione--</option></select></td>
                                                                                                            <!--jmore--><td style="display:none;"><input type="checkbox" id="chkcampo" style="margin-left: 30px;">&nbsp;<b>Enviar a Campo</b>                                                                                                                                                        
                                                                                                                        <td style="display:none;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Recibió EECC?</b></td>
                                                                                                                        <td style="display:none;">
                                                                                                                            <select id="slcteecc">
                                                                                                                                <option value="0">--Seleccionar--</option>
                                                                                                                                <option value="1">SI</option>
                                                                                                                                <option value="2">NO</option>
                                                                                                                            </select>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </table>
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td style="background-color:#FFF;" align="center">
                                                                                                            <div class="" style="text-align:center;padding: 10px 0;" id="visor_sumtotal">
                                                                                                                
                                                                                                            </div>
                                                                                                            <br>
                                                                                                            <table cellpadding="0" cellspacing="0" border="0" style="position:relative;width: 1120px;">
                                                                                                                <tr>
                                                                                                                    <td align="center">
                                                                                                                        <div style="max-height: 300px;overflow-y: scroll;border-bottom: 1px solid #0000ff;">
                                                                                                                            <table cellspacing="0" cellpadding="0" border="0" >
                                                                                                                                <tr>
                                                                                                                                    <td>
                                                                                                                                        <table cellspacing="0" cellpadding="0" border="0" style="display: block; position: absolute; top: 0px; left: 0px;">
                                                                                                                                            <tr class="ui-state-default" id="tr_header_cuenta_aplicar_gestion" style="left: 0;top: 0;">
                                                                                                                                                <td style="width:15px;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;"></td>
                                                                                                                                                <td style="width:50px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;border-left:1px solid #E0CFC2;height:18px;padding:4px 0 0;font-size: 9px;" align="center">RETIRADO</td>
                                                                                                                                                <td style="width:60px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;border-left:1px solid #E0CFC2;height:18px;padding:4px 0 0;font-size: 9px;" align="center">EMPRESA</td>
                                                                                                                                                <td style="width:25px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;height:18px;padding:4px 0 0;font-size: 9px;" align="center">TD</td>
                                                                                                                                                
                                                                                                                                                <td style="width:80px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;height:18px;padding:4px 0 0;font-size: 9px;" align="center">DOCUM</td>
                                                                                                                                                <td style="width:60px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;height:18px;padding:4px 0 0;font-size: 9px;" align="center">FECH.EMI.</td>
                                                                                                                                                <td style="width:60px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;height:18px;padding:4px 0 0;font-size: 9px;" align="center">FECH.VENC.</td>
                                                                                                                                                <td style="width:53px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;height:18px;padding:4px 0 0;font-size: 9px;" align="center">D.MORA</td>
                                                                                                                                                <td style="width:100px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;height:18px;padding:4px 0 0;font-size: 9px;" align="center">RANGO</td>
                                                                                                                                                <td style="width:50px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;height:18px;padding:4px 0 0;font-size: 9px;" align="center">ESTADO</td>
                                                                                                                                                <td style="width:50px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;height:18px;padding:4px 0 0;font-size: 9px;" align="center">BANCO</td>
                                                                                                                                                <td style="width:80px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;height:18px;padding:4px 0 0;font-size: 9px;" align="center">NUM.COBRANZA</td>
                                                                                                                                                <!-- <td style="width:50px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;height:18px;padding:4px 0 0;font-size: 9px;" align="center">MARCA</td> -->
                                                                                                                                                <!-- <td style="width:53px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;height:18px;padding:4px 0 0;font-size: 9px;" align="center">SEMAF</td>-->
                                                                                                                                                <td style="width:25px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;height:18px;padding:4px 0 0;font-size: 9px;" align="center">MON</td>
                                                                                                                                                <td style="width:68px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;height:18px;padding:4px 0 0;font-size: 9px;" align="center">IMP.ORIG</td>
                                                                                                                                                <td style="width:68px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;height:18px;padding:4px 0 0;font-size: 9px;" align="center">SALDO $</td>
                                                                                                                                                <td style="width:68px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;height:18px;padding:4px 0 0;font-size: 9px;" align="center">SALDO S/.</td>

                                                                                                                                                <!-- <td style="width:80px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" align="center">Tipo Tarjeta</td> -->
                                                                                                                                                <!-- <td style="width:80px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" align="center">Dias Mora</td> -->
                                                                                                                                                <!-- <td style="width:70px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;" align="center">Telefono</td> -->
                                                                                                                                                <!-- <td style="width:80px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;" align="center">Deuda Vencida</td> -->
                                                                                                                                                <!-- <td style="width:90px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;" align="center">Deuda Por Vencer</td> -->
                                                                                                                                                <!-- <td style="width:70px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" align="center" id="trAtencionCienteLabelCuentaSaldoTotalAplicar" >Saldo Total</td> -->
                                                                                                                                                <!-- <td style="width:85px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" align="center" id="trAtencionCienteLabelCuentaTotalDeudaAplicar" >Total Deuda</td> -->
                                                                                                                                                <!-- <td style="width:70px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" align="center">Monto Pagado</td> -->
                                                                                                                                                <!-- <td style="width:70px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" align="center">Saldo Actual</td> -->
                                                                                                                                                <td style="width:70px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;height:18px;padding:4px 0 0;font-size: 9px;" align="center"><input id="fcp_cuenta_all" type="text" style="width:60px;height:10px; font-size:11px" placeholder="FECHA CP" readonly="readonly"></td>
                                                                                                                                                <td style="width:70px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;height:18px;padding:4px 0 0;font-size: 9px;" align="center">MONTO CP</td>
														                                                                                        <td style="width:80px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;display:none;height:18px;padding:4px 0 0;font-size: 9px;" align="center">MONEDA CP</td>
                                                                                                                                                <!-- <td style="width:55px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" align="center">EECC</td> -->
                                                                                                                                                <!-- <td style="width:150px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" align="center">Estado</td> -->
                                                                                                                                                <!-- <td style="width:60px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;" align="center" title="Factura Digital">FD</td> -->
                                                                                                                                                <!-- <td style="width:30px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;display:none;" align="center" title="Corte Focalizado">CF</td> -->
                                                                                                                                                <!-- <td style="width:40px;white-space:pre-line;padding:3px 0;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2" align="center">Cargo</td> -->
                                                                                                                                                <!-- <td style="width:20px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;height:18px;padding: 4px 0 0;" align="center">---</td> -->
                                                                                                                                                <!-- <td style="width:70px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" align="center">Seguro</td> -->
                                                                                                                                                <!-- <td style="width:70px;white-space:pre-line;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;" align="center">Otros</td> -->
                                                                                                                                                
                                                                                                                                                <td style="width:26px;height:18px;padding:4px 0 0;border-bottom:1px solid #e0cfc2;border-top:1px solid #e0cfc2;border-right:1px solid #e0cfc2;display:none;" align="center" ><input type="checkbox" value="" id='checkheader' onclick="checked_all(this.checked,'table_cuenta_aplicar_gestion')"></td>    

                                                                                                                                            </tr>
                                                                                                                                        </table>
                                                                                                                                    </td>
                                                                                                                                </tr>
                                                                                                                                <tr>
                                                                                                                                    <td style="padding-top:24px;">
                                                                                                                                        <div style="width: 1120px;"><table id="table_cuenta_aplicar_gestion"  cellspacing="0" cellpadding="0" border="0"></table></div>
                                                                                                                <!--jmore300612-->      <div id="contentTbCuentasPagarAtencionCliente" style="display:none;position:absolute;">
                                                                                                                                            <div style="width:99.8%;padding:2px 0px;" class="ui-state-active ui-corner-top" align="right"><span onclick="$('#contentTbCuentasPagarAtencionCliente').fadeOut();" class="ui-icon ui-icon-circle-close"></span></div>
                                                                                                                                            <table cellpadding="0" cellspacing="0" border="0" id="tbCuentasPagarAtencionCliente"></table>
                                                                                                                 <!--jmore300612-->     </div> 
                                                                                                                                        <div id="contentTbRefinanciamiento" class="ui-widget-content" style="display:none;padding:5px;position:absolute;z-index: 999">
                                                                                                                                        <div style="width:99.8%;padding:2px 0px;" class="ui-state-active ui-corner-top" align="right"><span onclick="$('#contentTbRefinanciamiento').fadeOut();" class="ui-icon ui-icon-circle-close"></span></div>
                                                                                                                                        <table>
                                                                                                                                            <tr>
                                                                                                                                                <td class="ui-state-default" style="padding:2px;">Deuda</td>
                                                                                                                                                <td align="center"><input type="text" id="txtDeudaRefinanc" style="width:50px;" /></td>
                                                                                                                                                <td class="ui-state-default" style="padding:2px;">Descuento</td>
                                                                                                                                                <td align="center"><input onkeyup = " if( event.keyCode == 13 ) { calcular_total_deuda_refinanc() } " type="text" value="0" id="txtDescuentoRefinanc" class="cajaForm" style="width:50px;" /></td>
                                                                                                                                            </tr>
                                                                                                                                            <tr>
                                                                                                                                                <td class="ui-state-default" style="padding:2px;">Total Deuda</td>
                                                                                                                                                <td align="center"><input type="text" id="txtTotalDeudaRefinanc" class="cajaForm" style="width:50px;" /></td>
                                                                                                                                                <td class="ui-state-default" style="padding:2px;">N. Cuotas</td>
                                                                                                                                                <td align="center"><input onkeyup = " if( event.keyCode == 13 ) { calcular_monto_total_refinanc() } " type="text" id="txtNCuotasRefinanc" class="cajaForm" style="width:50px;" /></td>
                                                                                                                                            </tr>
                                                                                                                                            <tr>
                                                                                                                                                <td class="ui-state-default" style="padding:2px;">Tipo Pago</td>
                                                                                                                                                <td align="center">
                                                                                                                                                    <select id="cbTipoMontoRefinanc" class="combo">
                                                                                                                                                        <option value="SEMANAL">SEMANAL</option>
                                                                                                                                                        <option value="QUINCENAL">QUINCENAL</option>
                                                                                                                                                        <option value="MENSUAL">MENSUAL</option>
                                                                                                                                                    </select>
                                                                                                                                                </td>
                                                                                                                                                <td class="ui-state-default" style="padding:2px;">Monto Cuota</td>
                                                                                                                                                <td align="center"><input onkeyup = " if( event.keyCode == 13 ) { calcular_monto_total_refinanc() } " type="text" id="txtMontoCuotaRefinanc" class="cajaForm" style="width:50px;" /></td>
                                                                                                                                            </tr>
                                                                                                                                            <tr>
                                                                                                                                                <td class="ui-state-default" style="padding:2px;">Monto Total</td>
                                                                                                                                                <td align="center"><label id="lbMontoTotalRefinanc" style="width:50px;">0</label></td>
                                                                                                                                                <td></td>
                                                                                                                                                <td></td>
                                                                                                                                            </tr>
                                                                                                                                            <tr>
                                                                                                                                                <td valign="top" class="ui-state-default" style="padding:2px;">Observacion</td>
                                                                                                                                                <td colspan="3">
                                                                                                                                                    <textarea id="txtObservacionRefinanc" style="width:250px;height:60px;"></textarea>
                                                                                                                                                </td>
                                                                                                                                            </tr>
                                                                                                                                            <tr>
                                                                                                                                                <td colspan="4">
                                                                                                                                                    <button onclick="grabar_refinanciamiento()" class="ui-state-default ui-corner-all">Grabar</button>
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
                                                                                                            <br>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <div class="">
                                                                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                                                                    <tr>
                                                                                                                        <td>&nbsp;</td>
                                                                                                                        <td></td>
                                                                                                                        <td></td>
                                                                                                                        <td></td>
                                                                                                                        <td></td>
                                                                                                                        <td></td>
                                                                                                                        <td></td>
                                                                                                                        <td></td>
                                                                                                                        <td></td>
                                                                                                                        <td></td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td style="padding:0 5px;display:none;">
                                                                                                                            <div style="display:inline;"><label>Enviar a Campo</label></div>
                                                                                                                        </td>
                                                                                                                        <td style="display:none;"><input type="checkbox" value="1" id="chkEnviarCampoLlamada"  ></td>
                                                                                                                        <td style="padding:0 5px;">
                                                                                                                            <div style="display:inline;"><label>Contacto</label></div>
                                                                                                                        </td>
                                                                                                                        <td><select style="width:150px;" id="cbLlamadaContacto" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                                                        <td style="padding:0 5px;">
                                                                                                                            <div style="display:inline;"><label>Nombre Contacto</label></div>
                                                                                                                        </td>
                                                                                                                        <td><input id="txtLlamadaNombreContacto" class="cajaForm" /></td>
                                                                                                                        <td style="padding:0 5px;">
                                                                                                                            <div style="display:inline;"><label>Parentesco</label></div>
                                                                                                                        </td>
                                                                                                                        <td><select style="width:150px;" id="cbLlamadaParentesco" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                                                        <td style="padding:0 5px;">
                                                                                                                            <div style="display:inline;"><label>Motivo No Pago</label></div>
                                                                                                                        </td>
                                                                                                                        <td><select style="width:150px;" id="cbLlamadaMotivoNoPago" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                                                    </tr>
                                                                                                                    <tr><!--jmore18112014-->
                                                                                                                        <td style="padding:0 5px;">
                                                                                                                            <div style="display:none;"><label>Sustento Pago</label></div>
                                                                                                                        </td>
                                                                                                                        <td><select style="width:150px;display:none;" id="cbLlamadaSustentoPago" class="combo"><option value="0">--Seleccione--</option></select></td>

                                                                                                                        <td style="padding:0 5px;">
                                                                                                                            <div style="display:none;"><label>Alerta Gestion</label></div>
                                                                                                                        </td>
                                                                                                                        <td><select style="width:150px;display:none;" id="cbLlamadaAlertaGestion" class="combo"><option value="0">--Seleccione--</option></select></td>    

                                                                                                                        <td style="padding:0 5px;">
                                                                                                                            <div style="display:none;"><label>Situación Laboral</label></div>
                                                                                                                        </td>
                                                                                                                        <td ><select style="width:150px;display:none;" id="cbSituacionLaboral" class="combo"><option value="0">--Seleccione--</option></select></td>

                                                                                                                        <td style="padding:0 5px;">
                                                                                                                            <div style="display:none;"><label>Disposición Negociar</label></div>
                                                                                                                        </td>
                                                                                                                        <td ><select style="width:150px;display:none;" id="cbDisposicionRefinanciar" class="combo"><option value="0">--Seleccione--</option></select></td>                                                                                                                    
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        

                                                                                                                        <td style="padding:0 5px;">
                                                                                                                            <div style="display:inline;"><label>Estado del Cliente</label></div>
                                                                                                                        </td>
                                                                                                                        <td ><select style="width:150px;" id="cbEstadoDelCliente" class="combo"><option value="0">--Seleccione--</option></select></td>                                                                                                                    
                                                                                                                    </tr>                                                                                                                       
                                                                                                                </table>
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <div class="formHeader" style="display:none;">
                                                                                                                <div style="display:inline;"><label>&nbsp;Detalle de Estado</label></div>

                                                                                                                <table style="width:100%;" cellpadding="0" cellspacing="0" border="0">
                                                                                                                    <tr>
                                                                                                                        <td style="text-align: left; width: auto; white-space: normal;"><label>&nbsp;Contac</label></td>
                                                                                                                        <td><select onchange="listar_tipo_final()" style="width:100px;" id="cbLlamadaEstadoDetalleContactabilidad" onkeyup=" if( event.keyCode == 13 ){ $(this).blur(); $('#cbLlamadaEstadoDetalleTipo').focus(); } " class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                                                        <td style="text-align: left; width: auto; white-space: normal;"><label>Tipo</label></td>
                                                                                                                        <td><select onchange="listar_nivel_final()" style="width:100px;" id="cbLlamadaEstadoDetalleTipo" onkeyup=" if( event.keyCode == 13 ){ $(this).blur(); $('#cbLlamadaEstadoDetalleResptGestion').focus(); } " class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                                                        <td style="text-align: left; width: auto; white-space: normal;"><label>Respt. Gestion</label></td>
                                                                                                                        <td><select onchange="listar_detalle_estado()" style="width:100px;" id="cbLlamadaEstadoDetalleResptGestion" onkeyup=" if( event.keyCode == 13 ){ $(this).blur(); $('#cbLlamadaEstadoDetaleResptIncidencia').focus(); } " class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                                                        <td style="text-align: left; width: auto; white-space: normal;"><label>Respt. Incidencia</label></td>
                                                                                                                        <td><select onchange=" var idfinal = this.value; $('#cbLlamadaEstado').val(idfinal); " style="width:100px;" id="cbLlamadaEstadoDetaleResptIncidencia" onkeyup=" if( event.keyCode == 13 ){ $(this).blur(); $('#txtObservacionLlamada').focus(); } " class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                                                    </tr>
                                                                                                                </table>
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <table style="width:100%;" cellpadding="0" cellspacing="0" border="0">
                                                                                                                <tr>
                                                                                                                    <td style="text-align: left; width: auto; "><label>&nbsp;Observacion</label></td>
                                                                                                                    <td ><form><textarea onkeypress="return isNumbers(event);" onkeyup="if( event.keyCode == 13 ){ $(this).blur();$('#btnGrabarLLamadaAtencionCliente').trigger('click');   }" class="textareaForm textarea" id="txtObservacionLlamada" cols="70" style="height:30px;"></textarea></form></td>
                                                                                                                </tr>
                                                                                                            </table>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr style="display:none;">
                                                                                                        <td>
                                                                                                            <div id="msgmotivonopago" style="color:#7E531D;font-size:12px;float:left">Motivo no Pago:</div>
                                                                                                            <div id="msgcontacto" style="color:#7E531D;font-size:12px;float:right"></div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr style="display:none;">
                                                                                                        <td>
                                                                                                            <div id="msgprovision" style="color:#7E531D;font-size:12px;float:left">Provision:</div>
                                                                                                        </td>                                                                                                                                                                                                                
                                                                                                    </tr>
                                                                                                    <tr style="display:none;">
                                                                                                        <td>
                                                                                                            <div id="msgsituacion" style="color:#7E531D;font-size:12px">Situacion:</div>
                                                                                                        </td>                                                                                                                                                                                                                
                                                                                                    </tr>                                                                                                    
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <div align="center" class="">
                                                                                                                <table>
                                                                                                                    <tr>
                                                                                                                        <!-- <td><button id="btnGrabarLLamadaAtencionCliente" onclick="save_llamada()" title="Grabar" class="btn">Guardar</button></td> -->
                                                                                                                        <!-- <td><button onclick="cancel_llamada()" title="Cancelar" class="btn">Cancelar</button></td> -->
                                                                                                                        <td>
                                                                                                                            <div class="boton_estilo fondo_gradiente_azul" style="width:120px;" id="btnGrabarLLamadaAtencionCliente" onclick="save_llamada()">
                                                                                                                                <img src="../img/gnome_media_floppy.png" width="25" class="boton_imagen" style="position: absolute;left: 6px;top:0px;">
                                                                                                                                <div class="lin_vet"></div> 
                                                                                                                                <span class="boton_letra">GUARDAR</span>
                                                                                                                            </div>  
                                                                                                                        </td>
                                                                                                                        <td>
                                                                                                                            <div class="boton_estilo fondo_gradiente_azul" style="width:120px;" onclick="cancel_llamada()">
                                                                                                                                <img src="../img/cancels.png" width="25" class="boton_imagen" style="position: absolute;left: 6px;top:0px;">
                                                                                                                                <div class="lin_vet"></div> 
                                                                                                                                <span class="boton_letra">CANCELAR</span>
                                                                                                                            </div>  
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </table>
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </table>                                                                                              
                                                                                                <table style="display:none;"> <!--ALERTAS-->
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <div style="padding:0 5px;" >
                                                                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                                                                    <tr>
                                                                                                                        <td>
                                                                                                                            <textarea id="txtObservacionCliente" onblur="if( $.trim( this.value ) == '' ){$(this).val('Escribe una observacion');$(this).css('height','17px');$(this).parent().parent().parent().find('tr:last').css('display','none');}" onfocus="$(this).val('');$(this).css('height','34px');$(this).parent().parent().parent().find('tr:last').css('display','block');" style="height:17px;border:1px solid #9ACCF2;width:400px;font-family:Comic Sans MS;font-size:11px;">Escribe una observacion...</textarea>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <tr style="display:none;">
                                                                                                                            <td align="right"><button onclick="guardar_observacion( $('#txtObservacionCliente').val(), function ( ) { $('#txtObservacionCliente').val('');$('#txtObservacionCliente').trigger('blur'); } )" class="ui-state-default ui-corner-all">Observar</button></td>
                                                                                                                        </tr>
                                                                                                                </table>	
                                                                                                            </div>
                                                                                                        </td>
                                                                                                        <td valign="top">
                                                                                                            <table>
                                                                                                                <tr>
                                                                                                                    <td>
                                                                                                                        <button class="ui-state-default ui-corner-all" onclick="show_agendar()" title="Agregar Agenda" alt="Agregar Agenda"><img src="../img/page_add.png" /></button>
                                                                                                                    </td>
                                                                                                                    <td style="display:none">
                                                                                                                        <button class="ui-state-default ui-corner-all" onclick="show_box_model_alerta()" title="Agregar Alerta" alt="Agregar Alerta"><img src="../img/bell_add.png" /></button>
                                                                                                                    </td>
                                                                                                                    <td style="display:block">
                                                                                                                        <button class="ui-state-default ui-corner-all" onclick="show_box_model_alerta_telefono()" title="Agregar Alerta por telefono" alt="Agregar Alerta"><img src="../img/bell_add.png" /></button>
                                                                                                                    </td>                                                                                                                    
																																					<!-- Vic I -->
																																					<td>
																																						<button class="ui-state-default ui-corner-all" onclick="show_saldo_inicial_vigente()">Ver Saldo Inicial Vigente</button>
																																					</td>
																																					<td>&nbsp;&nbsp;</td>
																																					<td>
																																						<button class="ui-state-default ui-corner-all" onclick="show_popup_cuotas()">Cuotas</button>
																																					</td>
                                                                                                                                                    <td>
                                                                                                                                                        <button class="ui-state-default ui-corner-all" id="msjRecibioEecc"></span></button>
                                                                                                                                                    </td>
																													<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
																													<td style="display:none">
																														<button class="ui-state-default ui-corner-all" onclick="show_popup_fiadores()">Fiadores</button>
																													</td>
																																					<!-- Vic F -->
                                                                                                                </tr>
                                                                                                            </table>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </table> <!--/ALERTAS-->
                                                                                                <!-- FIDELIZACION -->
                                                                                                <?php 

                                                                                                }
                                                                                                else
                                                                                                {
                                                                                                    // $estate_encuest="ENCUESTADO";
                                                                                                    $estate_encuest="NO ENCUESTADO";
                                                                                                    if($_SESSION['cobrast']['idservicio']==2 AND $estate_encuest=="NO ENCUESTADO")
                                                                                                    {
                                                                                                ?>
                                                                                                    <div style="background:red;width:auto;">
                                                                                                            <table style="width:100px;height:30px;border:1px solid blue;margin:0 auto;">
                                                                                                                    <tr>
                                                                                                                        <td>Tel&eacute;fono</td>
                                                                                                                        <td><input type="text" style="width:100px;" class="cajaForm"/></td>
                                                                                                                        <td ><input type="button" value="DIRECCION" style="margin:0 15px;"/></td>
                                                                                                                        <td><input type="button" value="TELEFONO" style="margin:0 15px;"/></td>
                                                                                                                        <td><input type="button" value="CORREO" style="margin:0 15px;"/></td>
                                                                                                                    </tr>
                                                                                                            </table>
                                                                                                    </div>
                                                                                                    <ul id="slider">
                                                                                                        <li>
                                                                                                            <div style="background:none;">
                                                                                                                <div style="width:670px;background:none;padding:10px;">
                                                                                                                    <p class="letra_futura_ultra_bold" style="text-align:center;">ENCUESTA FIDELIZACION</p>
                                                                                                                    <br><br><br>
                                                                                                                    <ul style="list-style-type:none">
                                                                                                                        <li>
                                                                                                                            <span class="letra_futura_bold">1. PRESENTACION</span>
                                                                                                                            <br>
                                                                                                                            <br>
                                                                                                                            <span class="letra_futura_medium">Buenos días/tardes/noches con el Sr. (1er nombre y apellido), por favor:</span>                                                                                                                          
                                                                                                                            <br>
                                                                                                                            <br>
                                                                                                                            <ul class="letra_futura_medium">
                                                                                                                                <li>Si contesta titular ir a punto 2.1</li>
                                                                                                                                <li>Si contesta tercero ir a punto 2.2</li>
                                                                                                                            </ul>
                                                                                                                        </li>
                                                                                                                        <br>
                                                                                                                        <li>
                                                                                                                            <span class="letra_futura_bold">2. CONTACTO</span>
                                                                                                                            <ul style="list-style-type:none">
                                                                                                                                <li>
                                                                                                                                <br>
                                                                                                                                <span style="font-family:'futura_md_btmedium';font-weight:bold">2.1 Contesta Titular</span>
                                                                                                                                <br>
                                                                                                                                <br>
                                                                                                                                <span class="letra_futura_medium">
                                                                                                                                Muy buenos días / tardes, mi nombre es ……………… de la agencia HDC por encargo de promotora OPCION, aprovecho la oportunidad para saludarlo y recordarle que usted es muy importante para nosotros, nos interesa saber cómo fue su experiencia al contactar con nosotros y tomar nuestros servicios. 
                                                                                                                                </span>                                                                                                                                
                                                                                                                                <br>
                                                                                                                                <span class="letra_futura_medium">Antes de empezar confírmeme la siguiente información:</span>                                                                                                                          
                                                                                                                                <br>
                                                                                                                                <br>
                                                                                                                                <ul class="letra_futura_medium">
                                                                                                                                    <li>Nombre completo</li>
                                                                                                                                    <li>Fecha de nacimiento</li>
                                                                                                                                    <li>Dirección</li>
                                                                                                                                    <li>Teléfonos</li>
                                                                                                                                </ul>


                                                                                                                                </li>
                                                                                                                            </ul>                                                                                                                       
                                                                                                                        </li>
                                                                                                                         
                                                                                                                    </ul>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </li>
                                                                                                        <li>
                                                                                                            <div style="background:none;">
                                                                                                            <div style="width:670px;background:none;padding:10px;">
                                                                                                                <p class="letra_futura_ultra_bold" style="text-align:center;">ENCUESTA FIDELIZACION</p>
                                                                                                                <br><br><br>
                                                                                                                <ol class="letra_futura_medium">
                                                                                                                    <li>Sr./Srta.…. Dígame Del 1 al 5  ¿Cómo califica a OPCIÓN? 
                                                                                                                        <select id="first_cuest_fideli" class="letra_futura_medium">
                                                                                                                            <option value="">.:Seleccione:.</option>
                                                                                                                            <option value="1">1</option>
                                                                                                                            <option value="2">2</option>
                                                                                                                            <option value="3">3</option>
                                                                                                                            <option value="4">4</option>
                                                                                                                            <option value="5">5</option>
                                                                                                                        </select>
                                                                                                                    </li>
                                                                                                                    <br>
                                                                                                                    <li>¿Cómo le gustaría que OPCION se comunique con UD? 
                                                                                                                        <select id="second_cuest_fideli" class="letra_futura_medium">
                                                                                                                            <option value="">.:Seleccione:.</option>
                                                                                                                            <option value="1">Que lo llamen 2 días antes de la asamblea</option>
                                                                                                                            <option value="2">Que le envié correo electrónico</option>
                                                                                                                            <option value="3">Por mensajes de texto</option>
                                                                                                                            <option value="4">Mensajes pregrabados</option>
                                                                                                                        </select>
                                                                                                                    </li>
                                                                                                                    <br>
                                                                                                                    <li>¿Qué tipo de información le gustaría recibir?
                                                                                                                        <select id="third_cuest_fideli" class="letra_futura_medium">
                                                                                                                            <option value="">.:Seleccione:.</option>
                                                                                                                            <option value="1">Como va el avance de su grupo</option>
                                                                                                                            <option value="2">Que cuota va de los pagos o las faltantes</option>
                                                                                                                            <option value="3">Otros</option>
                                                                                                                        </select>
                                                                                                                    </li>
                                                                                                                    <br>
                                                                                                                    <li>¿Recomendaría nuestros servicios?
                                                                                                                        <br>
                                                                                                                        <input type="radio" name="group2" value="1"> SI<br>
                                                                                                                        <input type="radio" name="group2" value="0"> NO 
                                                                                                                        <select id="fourth_cuest_fideli" class="letra_futura_medium">
                                                                                                                            <option value="">.:Seleccione:.</option>
                                                                                                                            <option value="1">Mala venta</option>
                                                                                                                            <option value="2">Demasiada documentación para entregar el bien</option>
                                                                                                                            <option value="3">Por las penalidades</option>
                                                                                                                            <option value="4">Mal servicio en oficinas</option>
                                                                                                                            <option value="5">No indica motivo</option>
                                                                                                                        </select>
                                                                                                                    </li>
                                                                                                                </ol>
                                                                                                                <br>
                                                                                                                <p class="letra_futura_medium" style="text-align:justify;">
                                                                                                                    Muchas gracias por su atención, tendremos en cuenta todos sus aportes para seguir mejorando y brindarle un mejor servicio. Asimismo, lo invitamos para que ingrese a nuestra página web www.opcion.com.peo ingrese a nuestro Facebook para que pueda enterarse de las últimas novedades.
                                                                                                                </p>
                                                                                                                <p class="letra_futura_medium" style="text-align:justify;">
                                                                                                                    Finalmente le recordamos que su asambleas es el………………………………. Recuerde que puede pagar hasta una hora antes de la asamblea así poder participar y evitar el recargo de mora y carta de cobranza.
                                                                                                                </p>
                                                                                                                <p style="text-align:center;"><input type="button" value="GUARDAR" /></p>
                                                                                                            </div>
                                                                                                            </div>
                                                                                                        </li>
                                                                                                        <li>
                                                                                                            <div style="background:none;">
                                                                                                            <div style="width:670px;background:none;padding:10px;">   
                                                                                                                <p class="letra_futura_ultra_bold" style="text-align:center;">ENCUESTA FIDELIZACION</p>
                                                                                                                <br><br><br>                                                                                                           
                                                                                                                <ul style="list-style-type:none">
                                                                                                                    <li><span style="font-family:'futura_md_btmedium';font-weight:bold">2.2 Contesta Tercero</span>
                                                                                                                        <p class="letra_futura_medium" style="text-align:justify;">Muy buenos días / tardes, mi nombre es……….. y aprovecho la oportunidad para saludarlo a nombre de la Promotora Opción.¿Con quién tengo el gusto? ¿Es Ud. el encargado de los aportes mensuales?</p>
                                                                                                                        <ul style="list-style-type:none;"">
                                                                                                                            <li><span style="font-family:'futura_md_btmedium';font-weight:bold;">2.1.2   Si contesta Si regresar al punto 2.1 (contesta titular)</span></li>
                                                                                                                            <li><span style="font-family:'futura_md_btmedium';font-weight:bold;">2.1.2   Si contesta NO seguir con la siguiente pregunta</span>
                                                
                                                                                                                            <p class="letra_futura_medium" style="text-align:justify;">Titular, algún otro número donde me pueda comunicar con el titular, si el cliente desea que le dejen el encargo mencionar que es una información importante relacionada con Promotora Opción.</p>
                                                                                                                            </li>
                                                                                                                        </ul>
                                                                                                                    </li>
                                                                                                                </ul>
                                                                                                                <ul style="list-style-type:none">
                                                                                                                    <li>
                                                                                                                        <span class="letra_futura_bold">3. DESPEDIDA</span>
                                                                                                                        <p class="letra_futura_medium">
                                                                                                                            Sr. (apellido) muchas gracias por su tiempo y recuerde que Promotora Opción está trabajando cada día para brindarle un mejor servicio. Buenos días/tardes/noches
                                                                                                                        </p>
                                                                                                                    </li>
                                                                                                                </ul>
                                                                                                                <p style="text-align:center">
                                                                                                                    <img width="140" height="90" src="../img/Manos_cruzadas.png" >
                                                                                                                </p>
                                                                                                            </div>
                                                                                                            </div>
                                                                                                        </li>
                                                                                                    </ul>                                                                                                    
                                                                                                <?php 
                                                                                                    } else if($_SESSION['cobrast']['idservicio']==2 AND $estate_encuest=="ENCUESTADO")
                                                                                                        {
                                                                                                        ?>
                                                                                                        <ul id="slider_encuestados">
                                                                                                            <li>
                                                                                                                <div style="background:none;">
                                                                                                                <div style="width:670px;background:none;padding:10px;">   
                                                                                                                    <p class="letra_futura_ultra_bold" style="text-align:center;">ENCUESTA FIDELIZACION</p>
                                                                                                                    <br>                                                                                                         
                                                                                                                    
                                                                                                                    <ul style="list-style-type:none">
                                                                                                                        <li>
                                                                                                                            <span class="letra_futura_bold">Clientes Ya Encuestados</span>
                                                                                                                            <p class="letra_futura_medium">
                                                                                                                                Muy buenos días / tardes, mi nombre es…………………… De la agencia HDC por encargo de promotora OPCION, me podría comunicar con……………… 
                                                                                                                            </p>
                                                                                                                            <p class="letra_futura_medium">
                                                                                                                                Nuestra comunicación es para que nos pueda confirmar que tipo de información le gustaría recibir?
                                                                                                                            </p>
                                                                                                                            <ul>
                                                                                                                                <li>Cómo va el avance de su grupo</li>
                                                                                                                                <li>Que numero de cuota va</li>
                                                                                                                                <li>Las que le faltan pagar</li>
                                                                                                                                <li>Otros</li>
                                                                                                                            </ul>
                                                                                                                            <p class="letra_futura_medium">¿Recomendaría nuestro servicio?</p>
                                                                                                                            <input type="radio" name="group3" value="1"> SI<br>
                                                                                                                            <input type="radio" name="group3" value="0"> NO 
                                                                                                                            <select id="first_cuest_fideli_2" class="letra_futura_medium">
                                                                                                                                <option value="">.:Seleccione:.</option>
                                                                                                                                <option value="1">Mala venta</option>
                                                                                                                                <option value="2">Demasiada documentación para entregar el bien</option>
                                                                                                                                <option value="3">Por las penalidades</option>
                                                                                                                                <option value="4">Mal servicio en las oficinas </option>
                                                                                                                                <option value="5">No indica motivo</option>
                                                                                                                            </select>
                                                                                                                            <p class="letra_futura_medium">
                                                                                                                                Aprovecho la oportunidad para recordarle que usted es muy importante para nosotros, por lo cual le informamos que su asamblea es el……………….. Recuerde que puede pagar hasta una hora antes de la asamblea así poder participar y evitar el recargo de mora y carta de cobranza.
                                                                                                                            <p>
                                                                                                                            <p style="text-align:center;"><input type="button" value="GUARDAR" /></p>
                                                                                                                        </li>
                                                                                                                    </ul>
                                                                                                                </div>
                                                                                                                </div>
                                                                                                            </li>

                                                                                                        </ul>
                                                                                                        <?php
                                                                                                        }else if($_SESSION['cobrast']['idservicio']==3){
                                                                                                            ?>
                                                                                                            <div style="background:red;width:auto;">
                                                                                                            <table style="width:100px;height:30px;border:1px solid blue;margin:0 auto;">
                                                                                                                    <tr>
                                                                                                                        <td>Tel&eacute;fono</td>
                                                                                                                        <td><input type="text" style="width:100px;" class="cajaForm"/></td>
                                                                                                                        <td ><input type="button" value="DIRECCION" style="margin:0 15px;"/></td>
                                                                                                                        <td><input type="button" value="TELEFONO" style="margin:0 15px;"/></td>
                                                                                                                        <td><input type="button" value="CORREO" style="margin:0 15px;"/></td>
                                                                                                                    </tr>
                                                                                                            </table>
                                                                                                            </div>
                                                                                                            <ul id="slider_resueltos">
                                                                                                                <li>
                                                                                                                    <p class="letra_futura_ultra_bold" style="text-align:center;">ENCUESTA RESUELTOS</p>                                                                                                                    
                                                                                                                    
                                                                                                                    <p class="letra_futura_bold">1. PRESENTACION</p>
                                                                                                                    <p class="letra_futura_medium">Buenos días/tardes/noches con el Sr. (1er nombre y apellido), por favor:</p>
                                                                                                                    <p class="letra_futura_medium" style="font-weight:bold;">CONTACTO DIRECTO</p>
                                                                                                                    <p class="letra_futura_medium">Muy buenos días / tardes, mi nombre es…………………… lo saludamos de promotora OPCION, nos comunicamos con el fin de recordarle que usted es muy importante para nosotros, verificamos que su CC…………………… a la fecha se encuentra resuelto</p>
                                                                                                                    <p class="letra_futura_medium">¿Es correcto? </p>
                                                                                                                    <p class="letra_futura_medium">Y dígame ¿Cuál fue la razón por la que dejo de realizar sus aportes mensuales?</p>
                                                                                                                    <select id="first_cuest_resuelto" class="letra_futura_medium">
                                                                                                                        <option value="">.:Seleccione:.</option>
                                                                                                                        <option value="1">Menos ingresos </option>
                                                                                                                        <option value="2">Demasiados requisitos para el momento de la adjudicación</option>
                                                                                                                        <option value="3">Mala información al momento de la venta</option>
                                                                                                                        <option value="4">No quieren pagar penalidad</option>
                                                                                                                        <option value="5">No indica motivo</option>
                                                                                                                    </select>
                                                                                                                    <p class="letra_futura_medium">El motivo de mi llamada es poder ayudarlo a recuperar su inversión mejorando nuestro servicio y con mejor calidad.</p>
                                                                                                                    <p class="letra_futura_medium">Dígame cuando podemos visitarlo o a que números podemos comunicarnos para concretar una reunión  (PEDIR DATOS AL CLIENTE: TELEFONOS, CORREO Y  DIRECCION)</p>
                                                                                                                    <p class="letra_futura_medium">Gracias por su atención, esperamos darle la solución que UD. Espera, Cualquier duda o inquietud comunicarse con el Sr. Julio Lastarria al 998170152.</p>
                                                                                                                    <p style="text-align:center;"><input type="button" value="GUARDAR" /></p>
                                                                                                                    
                                                                                                                </li>
                                                                                                                <li>
                                                                                                                    <p class="letra_futura_ultra_bold" style="text-align:center;">ENCUESTA RESUELTOS</p>                                                                                                                    
                                                                                                                    <p class="letra_futura_medium" style="font-weight:bold;">CONTACTO CON TERCERO</p>
                                                                                                                    <p class="letra_futura_medium">Muy buenos días / tardes, mi nombre es………………..  y aprovecho la oportunidad para saludarlo a nombre de la empresa Promotora Opción. ¿Con quién tengo el gusto? ¿Es Ud. el encargado de los aportes mensuales?</p>
                                                                                                                    <p class="letra_futura_bold">3. DESPEDIDA</p>
                                                                                                                    <p class="letra_futura_medium">Sr. (apellido) muchas gracias por su tiempo y recuerde que Promotora Opción está trabajando cada día para brindarle un mejor servicio. </p>
                                                                                                                    <p class="letra_futura_medium">Buenos días/tardes/noches</p>
                                                                                                                </li>
                                                                                                            </ul>
                                                                                                            <?php
                                                                                                        }
                                                                                                }
                                                                                                ?>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                    <!--/FORM DE ATENCION LLAMADA-->
                                                                                    <!--DATOS DE GESTION CLIENTE-->
                                                                                    <div  class="lineTab ui-widget-header"></div>
                                                                                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                                                                        <tr valign="top" align="left">
                                                                                            <td >
                                                                                                <!--MENU DATOS DE GESTION CLIENTE-->
                                                                                                <div style="margin-top:10px;"></div>
                                                                                                <table border="0" cellpadding="0" cellspacing="0" style="width:100%;"></table>
                                                                                                <table id="table_tab_AC2" border="0" cellpadding="0" cellspacing="0" style="width:100%;">
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <div onclick="_activeTabLayer('table_tab_AC2','tabAC2',this,'content_table_tab_AC2','layerTabAC2','layerTabAC2Llamada')" id="tabAC2Llamada" class="itemTabActive border-radius-left pointer ui-widget-header" style="margin:1px 1px 0 0">
                                                                                                                <div class="text-white">Llamada</div>
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <!-- FIDELIZACION -->
                                                                                                    <?php if($_SESSION['cobrast']['idservicio']!=2 AND $_SESSION['cobrast']['idservicio']!=3){ ?>
                                                                                                    <tr>    
                                                                                                        <td>
                                                                                                            <div onclick="_activeTabLayer('table_tab_AC2','tabAC2',this,'content_table_tab_AC2','layerTabAC2','layerTabAC2CuentaPagos');" id="tabAC2CuentaPagos" class="itemTab border-radius-left pointer ui-widget-content" style="margin:1px 1px 0 0">
                                                                                                                <div class="AitemTab">Cuenta-Pagos</div>
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>    
                                                                                                        <td>
                                                                                                            <div onclick="_activeTabLayer('table_tab_AC2','tabAC2',this,'content_table_tab_AC2','layerTabAC2','layerTabAC2Telefonos')" id="tabAC2Telefonos" class="itemTab border-radius-left pointer ui-widget-content" style="margin:1px 1px 0 0">
                                                                                                                <div class="AitemTab" >Telefonos</div>
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <div onclick="_activeTabLayer('table_tab_AC2','tabAC2',this,'content_table_tab_AC2','layerTabAC2','layerTabAC2Direcciones')" id="tabAC2Direcciones" class="itemTab border-radius-left pointer ui-widget-content" style="margin:1px 1px 0 0">
                                                                                                                <div class="AitemTab" >Direcciones</div>
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr> 
                                                                                                        <td>
                                                                                                            <div onclick="_activeTabLayer('table_tab_AC2','tabAC2',this,'content_table_tab_AC2','layerTabAC2','layerTabAC2FacturaDigital')" id="tabAC2DFacturaDigital" class="itemTab border-radius-left pointer ui-widget-content" style="margin:1px 1px 0 0;display:none;">
                                                                                                                <div class="AitemTab" >Factura Digital</div>
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr> 
                                                                                                        <td>
                                                                                                            <div onclick="_activeTabLayer('table_tab_AC2','tabAC2',this,'content_table_tab_AC2','layerTabAC2','layerTabAC2Cuotificacion')" id="tabAC2DCuotidicacion" class="itemTab border-radius-left pointer ui-widget-content" style="margin:1px 1px 0 0;display:none;">
                                                                                                                <div class="AitemTab" >Refinanciamiento</div>
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr> 
                                                                                                        <td>
                                                                                                            <div onclick="_activeTabLayer('table_tab_AC2','tabAC2',this,'content_table_tab_AC2','layerTabAC2','layerTabAC2AcuerdosDePago');  $('#txtNumeroDeCuotasCovinoc').focus()" id="tabAC2DCuotidicacion" class="itemTab border-radius-left pointer ui-widget-content" style="margin:1px 1px 0 0;display:none;">
                                                                                                                <div class="AitemTab" >Acuerdos de Pago</div>
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <!-- FIDELIZACION -->
                                                                                                    <?php } ?>
                                                                                                </table>
                                                                                                <!--/MENU DATOS DE GESTION CLIENTE-->
                                                                                            </td>
                                                                                            <td class="ui-widget-header" style="width:5px;"></td>
                                                                                            <td style="overflow:auto;">
                                                                                                <!--DATOS DE GESTION CLIENTE-->
                                                                                                <table style="width:99%;">
                                                                                                    <tr>
                                                                                                        <td id="content_table_tab_AC2" valign="top" align="center" >
                                                                                                            <div id="layerTabAC2Llamada" class="ui-widget-content" style="display:block;overflow:auto;border: 0px" align="center" >
                                                                                                                <br>
                                                                                                                                            <!-- FIDELIZACION -->
                                                                                                                                            <?php if($_SESSION['cobrast']['idservicio']!=2 AND $_SESSION['cobrast']['idservicio']!=3){ ?>
                                                                                                                                            

                                                                                                                                            <div id="tabs_gestion">
                                                                                                                                                <ul>
                                                                                                                                                    <li><a href="#tabs-1">Llamadas</a></li>
                                                                                                                                                    <li><a href="#tabs-2">Visitas</a></li>
                                                                                                                                                </ul>
                                                                                                                                                <div id="tabs-1">
                                                                                                                                                    <table id="table_llamada" style=""></table>
                                                                                                                                                    <div id="pager_table_llamada" style=""></div>
                                                                                                                                                </div>
                                                                                                                                                <div id="tabs-2">
                                                                                                                                                    <table id="table_visita_one"></table>
                                                                                                                                                    <div id="pager_table_visita_one"></div>
                                                                                                                                                </div>
                                                                                                                                            </div>

                                                                                                                                            <?php }else{echo "HOLA MUNDO CALL";} ?>
                                                                                                                                            <!-- FIDELIZACION -->
                                                                                                                <br>                    
                                                                                                            </div>
                                                                                                            <div id="layerTabAC2CuentaPagos" class="ui-widget-content" style="display:none;width:900px;" align="center">
                                                                                                                <!-- <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                                                    <tr>
                                                                                                                        <td>
                                                                                                                            <div style="margin-left:100px;">
                                                                                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                                                                                    <tr id="table_tab_cuenta_detalle_pago">
                                                                                                                                        <td><div onclick="_activeTabLayer('table_tab_cuenta_detalle_pago','tabSub_',this,'content_table_tab_cuenta_detalle_pago','layerTabSub_','layerTabSub_Cuenta')" id="tabSub_Cuenta" class="itemTabActive border-radius-top pointer ui-widget-header"><div class="text-white">Cuenta - Detalle</div></div></td> -->
                                                                                                                                        <!--<td><div onclick="_activeTabLayer('table_tab_cuenta_detalle_pago','tabSub_',this,'content_table_tab_cuenta_detalle_pago','layerTabSub_','layerTabSub_Detalle')" id="tabSub_Detalle" class="itemTab border-radius-top pointer ui-widget-content"><div class="AitemTab">Detalle Cuenta</div></div></td>-->
                                                                                                                                        <!--<td><div onclick="_activeTabLayer('table_tab_cuenta_detalle_pago','tabSub_',this,'content_table_tab_cuenta_detalle_pago','layerTabSub_','layerTabSub_Pago')" id="tabSub_Pago" class="itemTab border-radius-top pointer ui-widget-content"><div class="AitemTab">Pago</div></div></td>-->
                                                                                                                                        <!-- <td><div onclick="_activeTabLayer('table_tab_cuenta_detalle_pago','tabSub_',this,'content_table_tab_cuenta_detalle_pago','layerTabSub_','layerTabSub_Historico')" id="tabSub_Historico" class="itemTab border-radius-top pointer ui-widget-content"><div class="AitemTab">Historico</div></div></td>
                                                                                                                                    </tr>
                                                                                                                                </table>
                                                                                                                            </div>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td class="lineTab ui-widget-header"  style="height:5px;"></td>
                                                                                                                    </tr>
                                                                                                                </table> -->
                                                                                                                <div id="content_table_tab_cuenta_detalle_pago" >
                                                                                                                    <div id="layerTabSub_Cuenta" align="center" style="display:block;overflow:auto;">
                                                                                                                        <div onclick="_slide2(this,'PanelTableCuentaAtencionCliente')">
                                                                                                                            <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                                                                <!--<tr>
                                                                                                                                    <td style="width:25px;">
                                                                                                                                        <div class="backPanel iconPinBlueDown" ></div>
                                                                                                                                    </td>
                                                                                                                                    <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                                                        <div style="direction:ltr;" align="left">
                                                                                                                                            <a class="text-blue">Cuenta</a>
                                                                                                                                        </div>
                                                                                                                                    </td>
                                                                                                                                    <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                                                        <div style="direction:rtl;">
                                                                                                                                            <span class="text-gris"></span>
                                                                                                                                        </div>
                                                                                                                                    </td>
                                                                                                                                </tr>-->
                                                                                                                            </table>
                                                                                                                        </div>
                                                                                                                        <div id="PanelTableCuentaAtencionCliente" style="display:block;overflow:auto;" align="left">
                                                                                                                            <table border="0" cellpadding="0" cellspacing="0" >
                                                                                                                                <tr>
                                                                                                                                    <td>
                                                                                                                                        <div id="divTabCuentaDetalle" style="overflow-y:auto; height: 250px; ">
                                                                                                                                            <ul>
                                                                                                                                                <!-- <li><a href="#divCuentas" id="tabCuentas">Cuentas</a></li> -->
                                                                                                                                                <!-- <li><a href="#divDatosAdicionales" id="tabDatosAdicionales">Datos Adicionales</a></li> -->
                                                                                                                                                <li><a href="#divDetalleFacturaOperacion" id="tabFacturaOperacion">Detalle - Factura - Operacion</a></li>
                                                                                                                                                <!-- <li><a href="#divPagos" id="tabPagos">Pagos</a></li>
                                                                                                                                                <li><a href="#layerTabSub_Historico" id="tabPagos">Historico</a></li> -->
                                                                                                                                            </ul>
                                                                                                                                            <!-- <div id="divCuentas">
                                                                                                                                                <table id="table_cuenta_new" border="0" cellpadding="0" cellspacing="0"  ></table>
                                                                                                                                            </div> -->
                                                                                                                                            <!-- <div id="divDatosAdicionales">
                                                                                                                                                <div style="overflow-x:auto;width:2800px;" >
                                                                                                                                                    <table title="adicional_cuenta" border="0" cellpadding="0" cellspacing="0" id="tb_adicional_cuenta">

                                                                                                                                                    </table>
                                                                                                                                                </div>
                                                                                                                                            </div> -->
                                                                                                                                            <div id="divDetalleFacturaOperacion">
                                                                                                                                                <div style="overflow-x:auto;" >
                                                                                                                                                    <table title="detalle" border="0" cellpadding="0" cellspacing="0" id="tb_detalle_factura_operacion"></table>
                                                                                                                                                </div>
                                                                                                                                            </div>
                                                                                                                                            <!-- <div id="divPagos">
                                                                                                                                                <div style="overflow-x:auto;" >
                                                                                                                                                    <table id="table_pagos" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                                                                                    <div id="pager_table_pagos"></div>
                                                                                                                                                </div>
                                                                                                                                            </div>
                                                                                                                                            <div id="layerTabSub_Historico" align="center" style="display:none;padding:5px;">
                                                                                                                                                <div style="overflow-x:auto;" >
                                                                                                                                                    <table border="0" cellpadding="0" cellspacing="0" id="table_historico_cuenta"></table>
                                                                                                                                                </div>
                                                                                                                                            </div> -->
                                                                                                                                        </div>
                                                                                                                                    </td>
                                                                                                                                </tr>
                                                                                                                            </table>
                                                                                                                        </div>
                                                                                                                        <!--<div onclick="_slide2(this,'PanelTableDatosAdicionalesCuentaAtencionCliente')">
                                                                                                                             <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                                                                 <tr>
                                                                                                                                     <td style="width:25px; height:25px;">
                                                                                                                                         <div class="backPanel iconPinBlueDown" ></div>
                                                                                                                                     </td>
                                                                                                                                     <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                                                         <div style="direction:ltr;" align="left">
                                                                                                                                             <a class="text-blue">Datos Adicionales Cuenta</a>
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
                                                                                                                         <div id="PanelTableDatosAdicionalesCuentaAtencionCliente" style="display:block;padding:5px 10px; overflow:auto;width:700px;" align="center">
                                                                                                                             <table cellpadding="0" cellspacing="0" border="0" id="table_datos_adicionales_cuenta"></table>
                                                                                                                         </div>-->
                                                                                                                    </div>
                                                                                                                    <!--<div id="layerTabSub_Detalle" align="center" style="display:none;">
                                                                                                                    
                                                                                                                        <div onclick="_slide2(this,'PanelTableOperacionAtencionCliente')">
                                                                                                                            <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                                                                <tr>
                                                                                                                                    <td style="width:25px; height:25px;">
                                                                                                                                        <div class="backPanel iconPinBlueDown" ></div>
                                                                                                                                    </td>
                                                                                                                                    <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                                                        <div style="direction:ltr;" align="left">
                                                                                                                                            <a class="text-blue">Operacion</a>
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
                                                                                                                        <div id="PanelTableOperacionAtencionCliente" style="display:block;padding:5px 10px;overflow:auto;width:700px;" align="center">
                                                                                                                            <table border="0" cellpadding="0" cellspacing="0">
                                                                                                                                <tr>
                                                                                                                                    <td>
                                                                                                                                        <div>
                                                                                                                                            <ol id="table_operaciones" style="list-style-type:none;padding:0;margin:0;" ></ol>
                                                                                                                                            <div id="pager_table_operaciones"></div>
                                                                                                                                        </div>
                                                                                                                                    </td>
                                                                                                                                </tr>
                                                                                                                            </table>
                                                                                                                        </div>-->
                                                                                                                    <!--<div onclick="_slide2(this,'PanelTableDatosAdicionalesOperacionAtencionCliente')">
                                                                                                                        <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                                                            <tr>
                                                                                                                                <td style="width:25px; height:25px;">
                                                                                                                                    <div class="backPanel iconPinBlueDown" ></div>
                                                                                                                                </td>
                                                                                                                                <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                                                    <div style="direction:ltr;" align="left">
                                                                                                                                        <a class="text-blue">Datos Adicionales Operacion</a>
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
                                                                                                                    <div id="PanelTableDatosAdicionalesOperacionAtencionCliente" style="display:block;padding:5px 10px; overflow:auto; width:700px;" align="center">
                                                                                                                        <table cellpadding="0" cellspacing="0" border="0" id="table_datos_adicionales_operacion" ></table>
                                                                                                                    </div>-->
                                                                                                                    <!--</div>-->
                                                                                                                    <!--<div id="layerTabSub_Pago" align="center" style="display:none;">
                                                                                                                        <div onclick="_slide2(this,'PanelTablePagosAtencionCliente')">
                                                                                                                            <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                                                                <tr>
                                                                                                                                    <td style="width:25px;">
                                                                                                                                        <div class="backPanel iconPinBlueDown" ></div>
                                                                                                                                    </td>
                                                                                                                                    <td style=" border-bottom:1px solid #EADEC8;">
                                                                                                                                        <div style="direction:ltr;" align="left">
                                                                                                                                            <a class="text-blue">Pagos</a>
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
                                                                                                                        <div id="PanelTablePagosAtencionCliente" style="display:block;padding:5px 10px;overflow:auto;" align="center">
                                                                                                                            <table border="0" cellpadding="0" cellspacing="0">
                                                                                                                                <tr>
                                                                                                                                    <td>
                                                                                                                                        <div>
                                                                                                                                            
                                                                                                                                        </div>
                                                                                                                                    </td>
                                                                                                                                </tr>
                                                                                                                            </table>
                                                                                                                        </div>
                                                                                                                    </div>-->
                                                                                                                    <!-- <div id="layerTabSub_Historico" align="center" style="display:none;padding:5px;">
                                                                                                                        <table border="0" cellpadding="0" cellspacing="0" id="table_historico_cuenta"></table>
                                                                                                                    </div> -->
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <!--<div id="layerTabAC2Visita" class="ui-widget-content" style="display:none;padding:5px;" align="center" >
                                                                                                                <table>
                                                                                                                    <tr>
                                                                                                                        <td>
                                                                                                                            <div>
                                                                                                                                <table id="table_visita"></table>
                                                                                                                                <div id="pager_table_visita"></div>
                                                                                                                            </div>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </table>
                                                                                                            </div>-->
                                                                                                            <div id="layerTabAC2Telefonos" class="ui-widget-content" style="display:none;" align="center">
                                                                                                                <table border="0" cellpadding="0" cellspacing="0">
                                                                                                                    <tr>
                                                                                                                        <td>
                                                                                                                            <div>
                                                                                                                                <table id="table_telefonos"></table>
                                                                                                                                <div id="pager_table_telefonos"></div>
                                                                                                                            </div>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                    <!--<tr>
                                                                                                                        <td align="right" >
                                                                                                                            <button onclick="display_box_serach_telefonos()" class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all"><span class="ui-button-text">Ver telefonos de otras lineas</span></button>
                                                                                                                        </td>
                                                                                                                    </tr>-->
                                                                                                                </table>
                                                                                                            </div>
                                                                                                            <div id="layerTabAC2Direcciones" class="ui-widget-content" style="display:none;width:900px;overflow:auto;" align="center">
                                                                                                                <table cellpadding="0" cellspacing="0">
                                                                                                                    <!--<tr>
                                                                                                                        <td align="right">
                                                                                                                            <button class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all"><span class="ui-button-text">Ver direccion de otras lineas</span></button>
                                                                                                                        </td>
                                                                                                                    </tr>-->
                                                                                                                    <tr>
                                                                                                                        <td>
                                                                                                                            <div>
                                                                                                                                <table id="table_direccion"></table>
                                                                                                                                <div id="pager_table_direccion"></div>
                                                                                                                            </div>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </table>
                                                                                                            </div>
                                                                                                            <div id="layerTabAC2FacturaDigital" class="ui-widget-content" style="display:none;padding:5px;" align="left">
                                                                                                                <table cellpadding="0" cellspacing="0">
                                                                                                                    <tr>
                                                                                                                        <td>
                                                                                                                            <?php
                                                                                                                            if ($_SESSION['cobrast']['privilegio'] == 'supervisor') {
                                                                                                                            ?>		
                                                                                                                            <table border="0" cellpadding="0" cellspacing="0">
                                                                                                                                <tr>
                                                                                                                                    <td>
                                                                                                                                        <div>
                                                                                                                                            <table id="table_facturasDigitales"></table>
                                                                                                                                            <div id="pager_table_facturasDigitales"></div>
                                                                                                                                        </div>
                                                                                                                                    </td>
                                                                                                                                </tr>
                                                                                                                            </table>
                                                                                                                            <?php
                                                                                                                            } else if ($_SESSION['cobrast']['privilegio'] == 'operador' or $_SESSION['cobrast']['privilegio'] == 'administrador') {
                                                                                                                            ?>
                                                                                                                            <div>
                                                                                                                                <form class="formulario">
                                                                                                                                    <div class="inlineBlock divDataFacturaDigital">
                                                                                                                                        <div>
                                                                                                                                            <label for="txtFacturaDigitalPersonaSolicita">Persona Solicitada</label>
                                                                                                                                            <input id="txtFacturaDigitalPersonaSolicita" type="text" class="cajaForm" />
                                                                                                                                        </div>
                                                                                                                                        <div>
                                                                                                                                            <label for="txtFacturaDigitalCorreo">Correo</label>
                                                                                                                                            <input id="txtFacturaDigitalCorreo" type="text" class="cajaForm" />
                                                                                                                                        </div>
                                                                                                                                        <div>
                                                                                                                                            <label for="txtFechaVencimiento">Fecha Vencimiento</label>
                                                                                                                                            <input id="txtFechaVencimiento" type="text" class="cajaForm" />
                                                                                                                                        </div>
                                                                                                                                        <div>
                                                                                                                                            <label for="cboLinea">Linea</label>
                                                                                                                                            <select id="cboLinea" class="combo">
                                                                                                                                                <option>--Seleccione--</option>
                                                                                                                                            </select>
                                                                                                                                        </div>
                                                                                                                                        <div>
                                                                                                                                            <label for="cboFacturaDigitalSupervisor">Supervisor</label>
                                                                                                                                            <select id="cboFacturaDigitalSupervisor" class="combo">
                                                                                                                                                <option value="0">--Seleccione--</option>
                                                                                                                                            </select>
                                                                                                                                        </div>
                                                                                                                                        <div>
                                                                                                                                            <label for="txtFacturaDigitalPersonaSolicita">Archivo</label>
                                                                                                                                            <input id="fileFacturaDigital" name="fileFacturaDigital" type="file" />
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                    <div class="inlineBlock divDataFacturaDigital">
                                                                                                                                        <label for="txtFacturaDigitalObservacion" style="display:block;">Observacion</label>
                                                                                                                                        <textarea id="txtFacturaDigitalObservacion" style="width:200px;height:120px" class="textareaForm"></textarea>
                                                                                                                                    </div>
                                                                                                                                    <div style="text-align:right;">
                                                                                                                                        <button type="button" onclick="guardar_factura_digital()" class="ui-state-default ui-corner-all" id="btnUploadFactDigital"><span class="ui-icon ui-icon-disk"></span></button>
                                                                                                                                    </div>
                                                                                                                                </form>
                                                                                                                                <!--<table>
                                                                                                                                    <tr>
                                                                                                                                        <td>Persona Solicita</td>
                                                                                                                                        <td><input id="txtFacturaDigitalPersonaSolicita" type="text" class="cajaForm" /></td>
                                                                                                                                        <td>Correo</td>
                                                                                                                                        <td><input id="txtFacturaDigitalCorreo" type="text" class="cajaForm" /></td>
                                                                                                                                        <td>Fecha Vencimiento</td>
                                                                                                                                        <td><input id="txtFechaVencimiento" type="text" class="cajaForm" /></td>
                                                                                                                                        <td>Linea</td>
                                                                                                                                        <td>
                                                                                                                                                                                                                                                                                <select id="cboLinea">
                                                                                                                        																								<option>--Seleccione--</option>
                                                                                                                        																							</select>
                                                                                                                                        </td>
                                                                                                                                        <td>Supervisor</td>
                                                                                                                                        <td><select id="cboFacturaDigitalSupervisor" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                                                                        <td>Archivo</td>
                                                                                                                                        <td><input id="fileFacturaDigital" name="fileFacturaDigital" type="file" /></td>
                                                                                                                                        <td><button onclick="guardar_factura_digital()" class="ui-state-default ui-corner-all" id="btnUploadFactDigital"><span class="ui-icon ui-icon-disk"></span></button></td>
                                                                                                                                    </tr>
                                                                                                                                </table>
                                                                                                                                <table>
                                                                                                                                    <tr>
                                                                                                                                        <td>Observacion</td>
                                                                                                                                        <td><textarea id="txtFacturaDigitalObservacion" style="width:500px;" class="textareaForm"></textarea></td>
                                                                                                                                    </tr>
                                                                                                                                </table>-->
                                                                                                                            </div>
                                                                                                                            <?php
                                                                                                                            }
                                                                                                                            ?>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td></td>
                                                                                                                    </tr>
                                                                                                                </table>
                                                                                                            </div>
                                                                                                            <div id="layerTabAC2Cuotificacion" class="ui-widget-content" style="display:none;padding:5px;" align="center">
                                                                                                        	    <table cellpadding="0" cellspacing="0" border="0">
                                                                                                            		<tr>
                                                                                                            			<td valign="top">
                                                                                                            				<table id="table_tab_ref_cuenta" cellpadding="0" cellspacing="0" border="0" style="margin-left:50px;">
                                                                                                            					<tr>
                                                                                                            						<td>
                                                                                                            							<div id="tab_ref_cuenta_datos" onclick="_activeTabLayer2('table_tab_ref_cuenta','tab_ref_cuenta_',this,'content_table_tab_ref_cuenta','layerTabRefCuenta','layerTabRefCuentaDatos')" class="text-alert" style="margin:0 10px 0 10px">
                                                                                                            								Ref.
                                                                                                            							</div>
                                                                                                            						</td>
                                                                                                            						<td>
                                                                                                            							<div id="tab_ref_cuenta_pagos" onclick="_activeTabLayer2('table_tab_ref_cuenta','tab_ref_cuenta_',this,'content_table_tab_ref_cuenta','layerTabRefCuenta','layerTabRefCuentaPagos')" class="text-gris" style="margin:0 10px 0 10px">
                                                                                                            								Pagos
                                                                                                            							</div>
                                                                                                            						</td>
                                                                                                            					</tr>
                                                                                                            				</table>
                                                                                                            			</td>
                                                                                                            		</tr>
                                                                                                            		<tr>
                                                                                                            			<td style="height:5px;"></td>
                                                                                                            		</tr>
                                                                                                            		<tr>
                                                                                                            			<td valign="top" style="overflow:auto;" id="content_table_tab_ref_cuenta">
                                                                                                            	
                                                                                                                        	<div style="display:block;" id="layerTabRefCuentaDatos">
                                                                                                                            	<table class="tableForm BoxContent">
                                                                                                                            		<tr>
                                                                                                                            			<td class="rowBoxContent textForm">Deuda</td>
                                                                                                                            			<td class="rowBoxContent"><input id="txtGBDeudaCuotificacion" readonly="readonly" type="text" class="cajaForm" /></td>
                                                                                                                            			<td class="rowBoxContent textForm">Descuento(%)</td>
                                                                                                                            			<td class="rowBoxContent"><input onkeyup=" calcular_monto_refinanciar() "  id="txtDescuentoCuotificacion" type="text" class="cajaForm" value="0" /></td>
                                                                                                                            			<td class="rowBoxContent textForm">Monto Descuento</td>
                                                                                                                            			<td class="rowBoxContent"><input id="txtMontoDescuentoCuotificacion" readonly="readonly" type="text" class="cajaForm" value="0" /></td>
                                                                                                                            		</tr>
                                                                                                                            		<tr>
                                                                                                                            			<td class="rowBoxContent text-alert" align="right">SubTotal Deuda</td>
                                                                                                                            			<td class="rowBoxContent"><input id="txtSubTotalDeudaCuotificacion" type="text" class="cajaForm" /></td>
                                                                                                                            			<td class="rowBoxContent textForm"></td>
                                                                                                                            			<td class="rowBoxContent"></td>
                                                                                                                            			<td class="rowBoxContent textForm"></td>
                                                                                                                            			<td class="rowBoxContent"></td>
                                                                                                                            		</tr>
                                                                                                                            		<tr>
                                                                                                                            			<td class="rowBoxContent textForm">Interes(%)</td>
                                                                                                                            			<td class="rowBoxContent"><input onkeyup=" calcular_monto_refinanciar() " id="txtInteresCuotificacion" type="text" class="cajaForm" value="0" /></td>
                                                                                                                            			<td class="rowBoxContent textForm">Monto Interes</td>
                                                                                                                            			<td class="rowBoxContent"><input id="txtMontoInteresCuotificacion" readonly="readonly" type="text" class="cajaForm" value="0" /></td>
                                                                                                                            			<td class="rowBoxContent textForm">Comision(%)</td>
                                                                                                                            			<td class="rowBoxContent"><input onkeyup=" calcular_monto_refinanciar() " id="txtComisionCuotificacion" type="text" class="cajaForm" value="0" /></td>
                                                                                                                            		</tr>
                                                                                                                            		<tr>
                                                                                                                            			<td class="rowBoxContent textForm">Monto Comision</td>
                                                                                                                            			<td class="rowBoxContent"><input id="txtMontoComisionCuotificacion" readonly="readonly" type="text" class="cajaForm" value="0" /></td>
                                                                                                                            			<td class="rowBoxContent textForm">Mora(%)</td>
                                                                                                                            			<td class="rowBoxContent"><input onkeyup=" calcular_monto_refinanciar() " id="txtMoraCuotificacion" type="text" class="cajaForm" value="0" /></td>
                                                                                                                            			<td class="rowBoxContent textForm">Monto Mora</td>
                                                                                                                            			<td class="rowBoxContent"><input id="txtMontoMoraCuotificacion" readonly="readonly" type="text" class="cajaForm" value="0" /></td>
                                                                                                                            		</tr>
                                                                                                                            		<tr>
                                                                                                                            			<td class="rowBoxContent textForm">Gastos Cobranza</td>
                                                                                                                            			<td class="rowBoxContent"><input onkeyup="calcular_monto_refinanciar()" id="txtGastosCobranzaCuotificacion" type="text" class="cajaForm" value="0" /></td>
                                                                                                                            			<td class="rowBoxContent text-alert" align="right">Total Refinanciar</td>
                                                                                                                            			<td class="rowBoxContent"><input id="txtTotalRefinanciarCuotificacion" readonly="readonly" type="text" class="cajaForm" /></td>
                                                                                                                            			<td class="rowBoxContent"></td>
                                                                                                                            			<td class="rowBoxContent"></td>
                                                                                                                            		</tr>
                                                                                                                            		<tr>
                                                                                                                            			<td class="rowBoxContent textForm">Nro. de Cuotas</td>
                                                                                                                            			<td class="rowBoxContent"><input onkeyup="calcular_monto_refinanciar()" id="txtNroCuotasCuotificacion" type="text" class="cajaForm" value="1" /></td>
                                                                                                                            			<td class="rowBoxContent textForm">Monto de Cuota</td>
                                                                                                                            			<td class="rowBoxContent"><input readonly="readonly" id="txtMontoCuotaCuotificacion" type="text" class="cajaForm" value="0" /></td>
                                                                                                                            			<td class="rowBoxContent textForm">Tipo de Pago</td>
                                                                                                                            			<td class="rowBoxContent">
                                                                                                                            				<select class="combo" id="txtTipoPagoCuotificacion">
                                                                                                                            					<option value="SEMANAL">SEMANAL</option>
                                                                                                                            					<option value="QUINCENAL">QUINCENAL</option>
                                                                                                                            					<option value="MENSUAL">MENSUAL</option>
                                                                                                                            					<option value="BIMESTRAL">BIMESTRAL</option>
                                                                                                                            					<option value="TRIMESTRAL">TRIMESTRAL</option>
                                                                                                                            				</select>
                                                                                                                            			</td>
                                                                                                                            		</tr>
                                                                                                                            		<tr>
                                                                                                                            			<td class="rowBoxContent textForm">Fecha Primer Pago</td>
                                                                                                                            			<td class="rowBoxContent"><input id="txtFechaPrimerPagoCuotificacion" type="text" class="cajaForm" /></td>
                                                                                                                            			<td class="rowBoxContent textForm"></td>
                                                                                                                            			<td class="rowBoxContent"></td>
                                                                                                                            			<td class="rowBoxContent textForm"></td>
                                                                                                                            			<td class="rowBoxContent"></td>
                                                                                                                            		</tr>
                                                                                                                            		<tr>
                                                                                                                            			<td class="rowBoxContent textForm">Observacion</td>
                                                                                                                            			<td class="rowBoxContent" colspan="5"><textarea id="txtObservacionCuotificacion" class="textareaForm"></textarea></td>
                                                                                                                            		</tr>
                                                                                                                            	</table>
                                                                                                                            	<table>
                                                                                                                            		<tr>
                                                                                                                            			<td><button class="ui-state-default ui-corner-all" style="padding:2px;" onclick="vista_previa_refinanciamiento()">Vista Previa</button></td>
                                                                                                                            			<td><button class="ui-state-default ui-corner-all" style="padding:2px;" onclick="grabar_ref()" >Guardar</button></td>
                                                                                                                            		</tr>
                                                                                                                            	</table>
                                                                                                                            	<table id="tableVistaPreviaCuotificacion" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                                                        	</div>
                                                                                                                        	<div style="display:none;" id="layerTabRefCuentaPagos">
                                                                                                                        		<table class="tableForm BoxContent">
                                                                                                                        			<tr>
                                                                                                                        				<td class="rowBoxContent textForm">Monto Pago</td>
                                                                                                                        				<td class="rowBoxContent"><input id="txtMontoPagoCuotificacion" style="width:80px;" type="text" class="cajaForm" /></td>
                                                                                                                        				<td class="rowBoxContent textForm">Moneda</td>
                                                                                                                        				<td class="rowBoxContent">
                                                                                                                        					<select class="combo" id="cbMonedaPagoCuotificacion">
                                                                                                                        						<option value="SOLES">SOLES</option>
                                                                                                                        					</select>
                                                                                                                        				</td>
                                                                                                                        				<td class="rowBoxContent textForm">Observacion</td>
                                                                                                                        				<td class="rowBoxContent"><textarea id="txtObsPagoCuotificacion"></textarea></td>
                                                                                                                        				<td class="rowBoxContent"><button onclick="grabar_pago_ref()" class="ui-state-default ui-corner-all">Grabar</button></td>
                                                                                                                        			</tr>
                                                                                                                        		</table>
                                                                                                                        		<div style="width:560px;">
                                                                                                                        			<table cellpadding="0" cellspacing="0" border="0">
                                                                                                                        				<tr>
                                                                                                                        					<td style="width:20px;padding:3px 0;" align="center" class="ui-state-default ui-corner-tl">&nbsp;</td>
                                                                                                                        					<td style="width:80px;padding:3px 0;" align="center" class="ui-state-default">Fecha</td>
                                                                                                                        					<td style="width:100px;padding:3px 0;" align="center" class="ui-state-default">Monto</td>
                                                                                                                        					<td style="width:80px;padding:3px 0;" align="center" class="ui-state-default">Moneda</td>
                                                                                                                        					<td style="width:250px;padding:3px 0;" align="center" class="ui-state-default">Observacion</td>
                                                                                                                        					<td style="width:18px;padding:3px 0;" align="center" class="ui-state-default ui-corner-tr">&nbsp;</td>
                                                                                                                        				</tr>
                                                                                                                        			</table>
                                                                                                                        			<div style="overflow:auto;height:150px;">
                                                                                                                        				<table id="table_list_ref_cuenta_pago" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                                                        			</div>
                                                                                                                        			<div class="ui-state-default ui-corner-bottom" style="height:20px;"></div>
                                                                                                                        		</div>
                                                                                                                        	</div>
                                                                                                                        </td>
                                                                                                            		</tr>
                                                                                                            	</table>
                                                                                                                <!--<table>
                                                                                                                    <tr>
                                                                                                                            <td colspan="2">
                                                                                                                                <table>
                                                                                                                                    <tr>
                                                                                                                                        <td><select id="cbEstadoCuotificacion" class="combo" style="width:200px;"><option value="0" >--Seleccione--</option></select></td>
                                                                                                                                        <td>Objecion</td>
                                                                                                                                        <td><select class="combo" id="cbObjecionCuotificacion"><option value="">--</option><option value="SI">SI</option><option value="NO">NO</option></select></td>
                                                                                                                                        <td>Deuda</td>
                                                                                                                                        <td><input type="text" class="cajaForm" value="0" id="txtDeudaCuotificacion" /></td>
                                                                                                                                        <td>N Cuotas</td>
                                                                                                                                        <td><input type="text" onkeyup=" if( event.keyCode == 13 ){ Monto_total_refinanciamiento(); }" class="cajaForm" value="0" id="txtNCuotaCuotificacion" /></td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        
                                                                                                                                        <td align="right">Tipo</td>
                                                                                                                                        <td><select class="combo" id="cbTipoCuotificacion"><option value="MESES">MESES</option><option value="DIAS">DIAS</option><option value="A?OS">A&Ntilde;OS</option></select></td>
                                                                                                                                        <td>Monto Cuota</td>
                                                                                                                                        <td><input type="text" onkeyup=" if( event.keyCode == 13 ){ Monto_total_refinanciamiento(); } " class="cajaForm" value="0" id="txtMontoCuotaCuotificacion" /></td>
                                                                                                                                        <td>Monto Total</td>
                                                                                                                                        <td><label style="width:40px;" id="lbMontoTotalCuotificacion">0</label></td>
                                                                                                                                        <td><button id="btnGrabarCuotificacion" onclick="guardar_cuotificacion()">Grabar</button></td>
                                                                                                                                        
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        <td colspan="12">
                                                                                                                                            <div>
                                                                                                                                                <table>
                                                                                                                                                    <tr>
                                                                                                                                                        <td>Observacion</td>
                                                                                                                                                        <td><textarea id="txtObservacionCuotificacion" class="textareaForm textarea" style="height:30px;width:600px;"></textarea></td>
                                                                                                                                                    </tr>
                                                                                                                                                </table>
                                                                                                                                            </div>
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                </table>
                                                                                                                            </td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                            <td valign="top">
                                                                                                                                <table>
                                                                                                                                    <tr>
                                                                                                                                        <td>Inicio</td>
                                                                                                                                        <td><input style="width:70px;" readonly="readonly" id="txtFechaInicioRptRefinan" type="text" class="cajaForm" /></td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        <td>Fin</td>
                                                                                                                                        <td><input style="width:70px;" readonly="readonly" id="txtFechaFinRptRefinan" type="text" class="cajaForm" /></td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        <td colspan="2">
                                                                                                                                            <button onclick="link_export_refinan()" class="ui-state-default ui-corner-all">Exportar</button>
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                </table>
                                                                                                                            </td>
                                                                                                                            <td>
                                                                                                                                <div class="ui-widget-content" style="padding:4px;height:150px;overflow:auto;">
                                                                                                                                    <p>Speech de Validaci&oacute;n:</p>

                                                                                                                                    <p>Muy Buenos D&iacute;as (tardes/noche)  Sr. XXXXXXXXXXX, le saluda (Nombre de Validador) <span style="font-weight:bold;">DEL &Aacute;REA DE VALIDACI&Oacute;N.</span></p>

                                                                                                                                    <p>A continuaci&oacute;n procederemos a validar sus datos, asimismo le informamos que por su seguridad realizaremos una grabaci&oacute;n de voz a modo de firma electr&oacute;nica.</p>

                                                                                                                                    <p>Est&aacute; Ud. de acuerdo? Favor responder con SI o un SI ACEPTO.
                                                                                                                                    Rpta. (Si o Si Acepto).
                                                                                                                                    Rpta. cliente NO (se deniega la solicitud)</p>


                                                                                                                                    <p>Su Nombre Completo es:            (Cliente indica nombres y apellidos)</p>
                                                                                                                                    <p>Su Nro. de DNI es:                           (cliente indica nro. de DNI).</p>
                                                                                                                                    <p>Su Fecha de nacimiento es:         (Cliente indica fecha de nacimiento)</p>
                                                                                                                                    <p>Me indica su nro. Telef&oacute;nico:    (Cliente indica nro. de tel&eacute;fono).</p>

                                                                                                                                    <p>Sr. XXXXXXXXXXXXXXX entonces, <span style="color:red;text-decoration:underline;">Ud. Acepta <span style="font-weight:bold;">REFINANCIAR SU DEUDA</span> por s/. XXXX  a XX cuotas, mas s/. 10 de comisi&oacute;n: considerando que esta operaci&oacute;n ser&aacute; procesada como una disposici&oacute;n en efectivo<span>
                                                                                                                                    , Es esto conforme? </p>
                                                                                                                                    <p>Rpta valida del Cliente: SI, Conforme, Acepto</p>
                                                                                                                                    <p>Rpta negativa del Cliente (solicitud se deniega)</p>


                                                                                                                                    <p>De parte del grupo Wong y Metro le agradecemos la confianza depositada en nosotros y le agradecemos por formar parte de nuestros clientes preferentes, estaremos en contacto con Ud. para nuevas campa&ntilde;as.</p>


                                                                                                                                    <p>Esto ser&iacute;a todo muchas gracias por su atenci&oacute;n, que tenga un buen d&iacute;a.</p>

                                                                                                                                    <p>(Fin de proceso de validaci&oacute;n).</p>
                                                                                                                                    
                                                                                                                                    <p>Colocar en Observaci&oacute;n: </p>
                                                                                                                                    
                                                                                                                                    <p style="font-weight:bold;">CLIENTE EN TRAMITE DE NORMALIZACION</p>


                                                                                                                                </div>
                                                                                                                            </td>
                                                                                                                    </tr>
                                                                                                                </table>-->
                                                                                                            </div>
                                                                                                            <div id="layerTabAC2AcuerdosDePago" class="ui-widget-content" style="display:none;padding:5px;" align="center">
                                                                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                                                                    <tr>
                                                                                                                        <td valign="top">
                                                                                                                            <table id="table_tab_acuerdo_pago" cellpadding="0" cellspacing="0" border="0" style="margin-left:50px;">
                                                                                                                                <tr>
                                                                                                                                    <td>
                                                                                                                                        <div id="tab_acuerdo_pago" onclick="_activeTabLayer2('table_tab_acuerdo_pago','tab_ref_cuenta_',this,'content_table_tab_acuerdo_pago','layerTabAcuerdoPago','layerTabAcuerdoPago')" class="text-alert" style="margin:0 10px 0 10px">
                                                                                                                                            Acuerdos de Pago.
                                                                                                                                        </div>
                                                                                                                                    </td>
                                                                                                                                </tr>
                                                                                                                            </table>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td style="height: 5px; text-align: right;">
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td valign="top" style="overflow:auto;" id="content_table_tab_acuerdo_pago">
                                                                                                                
                                                                                                                            <div style="display:block;" id="layerTabAcuerdoPago">
                                                                                                                                <table id="tblCuentasAcuerdoPago" class="tableForm BoxContent">

                                                                                                                                </table>
                                                                                                                                <table id="tblAcuerdoPago" class="tableForm BoxContent" style="text-align:center">
                                                                                                                                    <tr>
                                                                                                                                        <td style="display:none"><input type="text" id="txtidcliente_cartera" /><td>
                                                                                                                                        <td class="rowBoxContent textForm" style="font-weight:bold;-moz-user-select: none;cursor:default">Número Pagaré</td>
                                                                                                                                        <td class="rowBoxContent"><input style="text-align:center;cursor:not-allowed" id="txtNumeroPagareCovinoc" readonly="readonly" disabled="disabled" type="text" class="cajaForm" /></td>
                                                                                                                                        <td class="rowBoxContent textForm" style="font-weight:bold;-moz-user-select: none;cursor:default">Número de Cuotas</td>
                                                                                                                                        <td class="rowBoxContent"><input style="text-align:center" onkeyUp="if(event.keyCode==13){ generarTablaAcuerdaPago(); }" onkeypress="return isNumero(event);" id="txtNumeroDeCuotasCovinoc" type="text" class="cajaForm"  /></td>
                                                                                                                                        <td class="rowBoxContent textForm" style="font-weight:bold;-moz-user-select: none;cursor:default">Fecha Acuerdo</td>
                                                                                                                                        <td class="rowBoxContent"><input style="text-align:center"  id="txtFechaAcuerdoCovinoc"  type="text" class="cajaForm"  /></td>                                                                                                                                        
                                                                                                                                        <td class="rowBoxContent textForm" style="font-weight:bold;-moz-user-select: none;cursor:default">Valor Acuerdo</td>
                                                                                                                                        <td class="rowBoxContent"><input style="text-align:center;" onkeyUp="if(event.keyCode==13){ generarTablaAcuerdaPago(); }"  id="txtValorAcuerdoCovinoc"  type="text" class="cajaForm" /></td>                
                                                                                                                                    </tr>
                                                                                                                                </table>
                                                                                                                                <div id="tblAcuPago" style="margin-bottom:7px">
                                                                                                                                </div>
                                                                                                                                <table style="width:100%">
                                                                                                                                    <tr>
                                                                                                                                        <td><button id="btngrabar_acuerdo_de_pago_covinoc" style="width:100%;padding:2px;opacity:0;transition:opacity 0.4s ease" onclick="grabar_acuerdo_de_pago_covinoc()" >Guardar</button></td>

                                                                                                                                    </tr>
                                                                                                                                </table>
                                                                                                                                
                                                                                                                                
                                                                                                                            </div>
                                                                                                                            
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </table>
                                                                                                            </div>
                                                                                                            <!--<div id="layerTabAC2CentroPago" class="ui-widget-content" style="display:none;padding:5px;" align="center">
                                                                                                                <table>
                                                                                                                    <tr>
                                                                                                                        <td>
                                                                                                                            <div>
                                                                                                                                <table id="table_centro_pago"></table>
                                                                                                                                <div id="pager_table_centro_pago"></div>
                                                                                                                            </div>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </table>
                                                                                                            </div>-->
                                                                                                            <!--<div id="layerTabAC2Visita" class="ui-widget-content" style="display:none;padding:5px;" align="center"></div>-->
                                                                                                            <!--<div id="layerTabAC2Historico" class="ui-widget-content" style="display:none;padding:5px;" align="center">
                                                                                                                <table>
                                                                                                                    <tr>
                                                                                                                        <td>
                                                                                                                            <div>
                                                                                                                                <table id="table_historico"></table>
                                                                                                                                <div id="pager_table_historico"></div>
                                                                                                                            </div>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </table>
                                                                                                            </div>-->
                                                                                                            <!--<div id="layerTabAC2HorarioNuevoTramo" class="ui-widget-content" style="display:none;padding:5px;" align="center"></div>-->
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </table>
                                                                                                <!--/DATOS DE GESTION CLIENTE-->
                                                                                            </td>
                                                                                        </tr> 
                                                                                    </table>
                                                                                    <!--/DATOS DE GESTION CLIENTE-->
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </table> 
                                                                </div>
                                                            </div>
                                                            <div id="layerTabAC1Agendar" style="display:none;" class="ui-widget-content" align="center">
                                                                <table>
                                                                    <tr>
                                                                        <td>
                                                                            <div onclick="_slide2(this,'layerFormAtencionAgendar')">
                                                                                <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                    <tr>
                                                                                        <td style="width:25px; height:25px;">
                                                                                            <div class="backPanel iconPinBlueUp" ></div>
                                                                                        </td>
                                                                                        <td style=" border-bottom:1px solid #EADEC8;">
                                                                                            <div style="direction:ltr;" align="left">
                                                                                                <a class="text-blue">Grabar Agenda</a>
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
                                                                            <div id="layerFormAtencionAgendar" style="display:none;" align="center">
                                                                                <table border="0" cellpadding="0" cellspacing="0" class="ui-corner-all" style="border: 6px solid rgb(224, 207, 194); z-index: 100;">
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div class="formHeader">
                                                                                                <table border="0" cellpadding="0" cellspacing="0">
                                                                                                    <tr>
                                                                                                        <td style="width:130px;"><div style="display:inline;"><label class="text-black">Agenda</label></div></td>
                                                                                                        <td><input type="text" id="txtFechaAgendar" class="inputText longCajaForm" /></td>
                                                                                                    </tr>
                                                                                                </table>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>     
                                                                                        <td>
                                                                                            <div class="formHeader">
                                                                                                <table border="0" cellpadding="0" cellspacing="0">
                                                                                                    <tr>
                                                                                                        <td style="width:130px;"><div style="display:inline;"><label class="text-black">Finales</label></div></td>
                                                                                                        <td><select id="cbAgendarFinal" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                                    </tr>
                                                                                                </table>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div class="formHeader">
                                                                                                <table border="0" cellpadding="0" cellspacing="0">
                                                                                                    <tr>
                                                                                                        <td  style="width:130px;"><label class="text-black">Fecha Cpg</label></td>
                                                                                                        <td ><input type="text" id="txtAgendarFechaCP" class="inputText longCajaForm" /></td>
                                                                                                        <td  style="width:130px;"><label class="text-black">Monto Cpg</label></td>
                                                                                                        <td ><input type="text" id="txtAgendarMontoCP" class="inputText longCajaForm" /></td>
                                                                                                    </tr>
                                                                                                </table>
                                                                                            </div>
                                                                                            <!--<table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                                <tr>
                                                                                                    <td class="labelHeader" style="text-align: left; width: auto; white-space: normal;"><label class="text-black">Fecha Compromiso de Pago</label></td>
                                                                                                    <td class="inputHeader"><input type="text" id="txtAgendarFechaCP" class="inputText longCajaForm" /></td>
                                                                                                    <td class="labelHeader" style="text-align: left; width: auto; white-space: normal;"><label class="text-black">Monto Compromiso de Pago</label></td>
                                                                                                    <td class="inputHeader"><input type="text" id="txtAgendarMontoCP" class="inputText longCajaForm" /></td>
                                                                                                </tr>
                                                                                            </table>-->
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div class="formHeader">
                                                                                                <table border="0" cellpadding="0" cellspacing="0">
                                                                                                    <tr>
                                                                                                        <td style="width:130px;" ><div style="display:inline;"><label class="text-black">Observacion</label></div></td>
                                                                                                        <td ><textarea class="textareaForm" id="txtObservacionAgendar" style="width:565px;height:90px;"></textarea></td>
                                                                                                    </tr>
                                                                                                </table>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div class="lastRowButton" align="center">
                                                                                                <table>
                                                                                                    <tr>
                                                                                                        <td><button onclick="save_agenda()" title="Grabar" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-disk"></span></button></td>
                                                                                                        <td><button onclick="cancel_agenda()" title="Cancelar" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-cancel"></span></button></td>
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
                                                                                        <td>
                                                                                            <div>
                                                                                                <table>
                                                                                                    <tr>
                                                                                                        <td align="right">Fecha Inicio</td>
                                                                                                        <td><input type="text" class="cajaForm longCajaForm" id="txtAgendarBuscarFechaInicio" /></td>
                                                                                                        <td align="right">Fecha Fin</td>
                                                                                                        <td><input type="text" class="cajaForm longCajaForm" id="txtAgendarBuscarFechaFin" /></td>
                                                                                                        <td>
                                                                                                            <button onclick="reloadJQGRID_agendados()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-search"></span></button>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </table>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div>
                                                                                                <table id="table_agendados"></table>
                                                                                                <div id="pager_table_agendados"></div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <div id="layerTabAC1Ranking" style="display:none;" class="ui-widget-content" align="center">
                                                                <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                    <tr>
                                                                        <td valign="top" >
                                                                            <div style="margin-top:10px;">
                                                                                <table id="table_tab_meta_ranking_atencion_cliente" cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div onclick="_activeTabLayer('table_tab_meta_ranking_atencion_cliente','tab_meta_ranking',this,'content_layer_meta_ranking','content_meta_ranking','content_meta_ranking_mi_ranking')" id="tab_meta_ranking_mi_ranking" class="itemTab border-radius-left pointer ui-widget-header" style="margin:1px 1px 0 0;">
                                                                                                <div>Mi Ranking</div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div onclick="_activeTabLayer('table_tab_meta_ranking_atencion_cliente','tab_meta_ranking',this,'content_layer_meta_ranking','content_meta_ranking','content_meta_ranking_otros')" id="tab_meta_ranking_otros" class="itemTab border-radius-left pointer ui-widget-content" style="margin:1px 1px 0 0;">
                                                                                                <div>Otros</div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div onclick="_activeTabLayer('table_tab_meta_ranking_atencion_cliente','tab_meta_ranking',this,'content_layer_meta_ranking','content_meta_ranking','content_meta_ranking_mi_meta')" id="tab_meta_ranking_mi_meta" class="itemTab border-radius-left pointer ui-widget-content" style="margin:1px 1px 0 0;">
                                                                                                <div>Mi Meta</div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                        </td>
                                                                        <td class="ui-widget-header" style="width:5px;"></td>
                                                                        <td id="content_layer_meta_ranking" valign="top">
                                                                            <!--<div style="padding:3px;">
                                                                                <table>
                                                                                    <tr>
                                                                                        <td align="left">
                                	                                                       <button onclick="AtencionClienteDAO.ListarRankingUsuarioServicio()" class="ui-state-default ui-corner-all" title="Actualizar" alt="Actualizar"><span class="ui-icon ui-icon-refresh"></span></button>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                	                                                        <div><table id="TableRankingUsuarioServicio" cellpadding="0" cellspacing="0" border="0"></table></div>
                                                                                		</td>
                                                                                    <tr>
                                                                                </table>
                                                                            </div>-->
                                                                            <div id="content_meta_ranking_mi_ranking" style="display:block;" align="center" class="ui-widget-content">
                                                                                <table>
                                                                                    <tr>
                                                                                        <td>Por</td>
                                                                                        <td>
                                                                                            <select id="cbPorRankingTotalUsuarioPorDia" class="combo">
                                                                                                <option value="gestion">GESTION</option>
                                                                                                <option value="distribucion">DISTRIBUCION</option>
                                                                                            </select>
                                                                                        </td>
                                                                                        <td>Fecha Inicio</td>
                                                                                        <td><input type="text" id="txtFechaInicioRankingTotalUsuarioPorDia" readonly="readonly" class="cajaForm" style="width:70px;" /></td>
                                                                                        <td>Fecha Fin</td>
                                                                                        <td><input type="text" id="txtFechaFinRankingTotalUsuarioPorDia" readonly="readonly" class="cajaForm" style="width:70px;" /></td>
                                                                                        <td align="left">
                                                                                            <button onclick="ranking_total_usuario_por_dia()" class="ui-state-default ui-corner-all" title="Actualizar" alt="Actualizar"><span class="ui-icon ui-icon-refresh"></span></button>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                                <table>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div><table id="TableRankingTotalUsuarioPorDia" cellpadding="0" cellspacing="0" border="0"></table></div>
                                                                                        </td>
                                                                                    <tr>
                                                                                </table>
                                                                            </div>
                                                                            <div id="content_meta_ranking_otros" style="display:none;" align="center" class="ui-widget-content">
                                                                                <table>
                                                                                    <tr>
                                                                                        <td>Por</td>
                                                                                        <td>
                                                                                            <select id="cbPorInicioRankingTotalServicioPorDia" class="combo">
                                                                                                <option value="gestion">GESTION</option>
                                                                                                <option value="distribucion">DISTRIBUCION</option>
                                                                                            </select>
                                                                                        </td>
                                                                                        <td>Fecha Inicio</td>
                                                                                        <td><input type="text" id="txtFechaInicioRankingTotalServicioPorDia" readonly="readonly" class="cajaForm" style="width:70px;" /></td>
                                                                                        <td>Fecha Fin</td>
                                                                                        <td><input type="text" id="txtFechaFinRankingTotalServicioPorDia" readonly="readonly" class="cajaForm" style="width:70px;" /></td>
                                                                                        <td align="left">
                                                                                            <button onclick="ranking_total_servicio_por_dia()" class="ui-state-default ui-corner-all" title="Actualizar" alt="Actualizar"><span class="ui-icon ui-icon-refresh"></span></button>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                                <table>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div><table id="TableRankingTotalServicioPorDia" cellpadding="0" cellspacing="0" border="0"></table></div>
                                                                                        </td>
                                                                                        <tr>
                                                                                </table>
                                                                            </div>
                                                                            <div id="content_meta_ranking_mi_meta" style="display:none;" align="center" class="ui-widget-content">
                                                                                <table>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <button id="btnMetaClienteCuentaUsuarioServicio" onclick="load_meta_cliente_cuenta_usuario_servicio()" >Actualizar</button>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div align="center">
                                                                                                <table id="TableMetaClienteCuentaUsuarioServicio" cellpadding="0" cellspacing="0" border="0"></table>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <div id="layerTabAC1Globales" style="display:none;" class="ui-widget-content" align="center">
                                                                <table>
                                                                    <tr>
                                                                        <td align="right">Campaña</td>
                                                                        <!--<td><select id="cbAtencionGlobalesCompania" class="combo" onchange="load_cartera_atencion_cliente(this.value)" ><option value="0">--Seleccione--</option></select></td>-->
                                                                        <td>
																			<select id="cbAtencionGlobalesCompania" class="combo" onchange="load_cartera_atencion_clienteOperador(this.value,$('#hdCodUsuarioServicio').val(),'cbAtencionGlobalesCartera',$('#cbAtencionGlobalesCluster').val(),$('#cbAtencionGlobalesEvento').val(),$('#cbAtencionGlobalesSegmento').val(),$('#cbAtencionGlobalesModo').val())" ><option value="0">--Seleccione--</option></select>
																		</td>
																		<td align="right">Evento</td>
																		<td>
																			<select id="cbAtencionGlobalesEvento" class="combo" onchange="load_cartera_atencion_clienteOperador($('#cbAtencionGlobalesCompania').val(),$('#hdCodUsuarioServicio').val(),'cbAtencionGlobalesCartera',$('#cbAtencionGlobalesCluster').val(), this.value ,$('#cbAtencionGlobalesSegmento').val())">
																				<option value="0">--Seleccione--</option>
																			</select>
																		</td>
																		<td align="right">Cluster</td>
																		<td>
																			<select id="cbAtencionGlobalesCluster" class="combo" onchange="load_cartera_atencion_clienteOperador($('#cbAtencionGlobalesCompania').val(),$('#hdCodUsuarioServicio').val(),'cbAtencionGlobalesCartera', this.value,$('#cbAtencionGlobalesEvento').val(),$('#cbAtencionGlobalesSegmento').val())">
																				<option value="0">--Seleccione--</option>
																			</select>
																		</td>
																		</td>
																		<td align="right">Segmento</td>
																		<td>
																			<select id="cbAtencionGlobalesSegmento" class="combo" onchange="load_cartera_atencion_clienteOperador($('#cbAtencionGlobalesCompania').val(),$('#hdCodUsuarioServicio').val(),'cbAtencionGlobalesCartera',$('#cbAtencionGlobalesCluster').val(),$('#cbAtencionGlobalesEvento').val(), this.value )">
																				<option value="0">--Seleccione--</option>
																			</select>
																		</td>
                                                                        <!--<td>
																			<select class="combo" id="cbAtencionGlobalesCartera" onchange="$('#lbMessageDataAdicionalGlobalGest').text( $(this).find('option:selected').attr('title') );load_change_campania_atencion_cliente();$('#txtItemAtencionClienteMain').val('0');$('#txtCantidadClientesAtencionClienteMain').text('0');carga_cantidad_clientes_filtro();"><option value="0">--Seleccione--</option></select>
																		</td>-->
                                                                        <!--$('#IdCartera').val($(this).find('option:selected').val());-->
                                                                        <td align="right">Modo</td>
                                                                        <td>
                                                                            <select class="combo" id="cbAtencionGlobalesModo" onchange="$('#txtItemAtencionClienteMain').val('0');$('#txtCantidadClientesAtencionClienteMain').text('0');carga_cantidad_clientes_filtro();" >
                                                                                <option value="cartera">Cartera</option>
                                                                                <option value="seguimiento">Seguimiento</option>
                                                                            </select>
                                                                        </td>
                                                                        <td><strong id="lbMessageDataAdicionalGlobalGest" style="color:red;font-weight:bold;margin-left: 15px;"></strong></td>                                                            
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="11">
                                                                            <table id="tbCarterasMultiples"></table>
                                                                            <div id="pager_tbCarterasMultiples"></div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <div id="layerTabAC1Apoyo" style="display:none;" class="ui-widget-content" align="center">
                                                                <table width="100%">
                                                                    <tr>
                                                                        <td valign="top" >

                                                                            <div class="ui-widget-content" style="border:0 none;width:100%;height:100%;" align="center">
                                                                                <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div align="center" style="padding:5px 0;">
                                                                                                <table>
                                                                                                    <tr>
                                                                                                        <td align="left">Usuario: </td>
                                                                                                        <td align="left" style="color:#1f497d; font-weight: bold;">
                                                                                                            <?php echo $_SESSION['cobrast']['usuario'] ?>
                                                                                                                <!--<select class="combo" id="cbUsuarioServicio"><option value="0">--Seleccione--</option></select>-->
                                                                                                        </td>
                                                                                                        <td width="30"></td>
                                                                                                        <td align="left">Campa&ntilde;a</td>
                                                                                                        <td align="left"><select class="combo" id="cbCampaniaApoyo" onChange="load_cartera_atencion_cliente(this.value,'cbCarteraApoyo')"><option value="0">--Seleccione--</option></select></td>
                                                                                                        <td align="left">Cartera</td>
                                                                                                        <!--<td align="left"><select class="combo" id="cbCarteraApoyo" onChange="load_data_usuarios_ayudar();load_lista_operadores_ayudar();"><option value="0">--Seleccione--</option></select></td>-->
                                                                                                        <td align="left"><select class="combo" id="cbCarteraApoyo" onChange="load_data_usuarios_ayudar()"><option value="0">--Seleccione--</option></select></td>
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
                                                                                                                        <td align="center">
                                                                                                                            <div>
                                                                                                                                <table cellspacing="0" cellpadding="0" border="0" class="ui-corner-top" >
                                                                                                                                    <tr class="ui-state-default" >
                                                                                                                                        <td style="width:25px;padding:3px 0;border:1px solid #E0CFC2;" class="ui-corner-tl" >&nbsp;</td>
                                                                                                                                        <td style="width:250px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Usuario</td>
                                                                                                                                        <td style="width:200px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Clientes Asignados</td>
                                                                                                                                        <td style="width:150px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Clientes Gestionados</td>
                                                                                                                                        <td style="width:150px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Clientes Sin Gestionar</td>
                                                                                                                                        <td style="width:25px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" align="center" ><input type="checkbox" onClick="checked_all(this.checked,'DataLayerTableUsuariosAyudar')" /></td>
                                                                                                                                        <td style="width:20px;padding:3px 0;border:1px solid #E0CFC2;" align="center" class="ui-corner-tr" ></td>
                                                                                                                                    </tr>
                                                                                                                                </table>
                                                                                                                            </div>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td align="center">
                                                                                                                            <div align="left" style="overflow:auto;height:170px;" >
                                                                                                                                <table id="DataLayerTableUsuariosAyudar" cellspacing="0" cellpadding="0" border="0" ></table>
                                                                                                                            </div>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td align="center">
                                                                                                                            <div align="right" class="ui-state-default ui-corner-bottom">
                                                                                                                                <table>
                                                                                                                                    <tr>
                                                                                                                                        <td>Buscar:</td>
                                                                                                                                        <td><input type="text" onkeyup = "search_operadores_en_tabla( this.value,'DataLayerTableUsuariosAyudar' )" class="cajaForm" /></td>
                                                                                                                                        <td><button onClick="delete_usuarios_asignados();load_lista_operadores_ayudar()" class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all"><span class="ui-button-text">Eliminar usuarios</span></button></td>
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
                                                                                                                        <td align="center">
                                                                                                                            <div >
                                                                                                                                <table cellspacing="0" cellpadding="0" border="0" >
                                                                                                                                    <tr class="ui-state-default" >
                                                                                                                                        <td style="width:25px;padding:3px 0;border:1px solid #E0CFC2;" class="ui-corner-tl" >&nbsp;</td>
                                                                                                                                        <td style="width:250px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Usuario</td>
                                                                                                                                        <td style="width:200px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Clientes Asignados</td>
                                                                                                                                        <td style="width:150px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Clientes Gestionados</td>
                                                                                                                                        <td style="width:150px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" >Clientes Sin Gestionar</td>
                                                                                                                                        <td style="width:25px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;text-align:center;" align="center" ><input type="checkbox" onClick="checked_all(this.checked,'DataLayerTableUsuariosAsignar')" /></td>
                                                                                                                                        <td style="width:20px;padding:3px 0;border:1px solid #E0CFC2;" align="center" class="ui-corner-tr" ></td>
                                                                                                                                    </tr>
                                                                                                                                </table>
                                                                                                                            </div>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td align="center">
                                                                                                                            <div align="left" style="overflow:auto;height:170px;width:830px;"  >
                                                                                                                                <table cellspacing="0" cellpadding="0" border="0" id="DataLayerTableUsuariosAsignar" ></table>
                                                                                                                            </div>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td align="center">
                                                                                                                            <div align="right" class="ui-state-default ui-corner-bottom">
                                                                                                                                <table>
                                                                                                                                    <tr>
                                                                                                                                        <td>Buscar:</td>
                                                                                                                                        <td><input type="text" onkeyup = "search_operadores_en_tabla( this.value,'DataLayerTableUsuariosAsignar' )" class="cajaForm" /></td>
                                                                                                                                        <td><button onClick="save_usuarios_asignar();load_lista_operadores_ayudar()" class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all"><span class="ui-button-text">Asignar usuarios</span></button></td>
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
                                                                                                            <div style="margin-left:100px;" align="center">
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

                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <div id="layerTabAC1MatrizBusqueda" style="display:none;padding:5px 10px;" class="ui-widget-content" align="center">
                                                                <table border="0" cellpadding="0" cellspacing="0">
                                                                    <tr>
                                                                        <td>
                                                                            <div>
                                                                                <table>
                                                                                    <tr>
                                                                                    <!--<td align="right">Servicio</td>
                                                                                    <td><select id="cbServicioMatrizBusqueda" onchange="loadCampania_matriz_busqueda(this)"><option value="0">--Seleccione--</option></select></td>
                                                                                    <td align="right">Campa&ntilde;a</td>
                                                                                    <td><select id="cbCampaniaMatrizBusqueda"><option value="0">--Seleccione--</option></select></td>-->
                                                                                        <td align="right">Operador</td>
                                                                                        <td><select id="cbOperadoresMatrizBusqueda" onchange="$('#txtItemAtencionClienteMain').val('0');$('#txtCantidadClientesAtencionClienteMain').text('0');$('#hTipoGestion').val('apoyo');carga_cantidad_clientes_filtro();" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        <td><button onclick="loadClientes_matriz_busqueda();" class="ui-state-default ui-corner-all" style="padding:2px 4px;"><span class="ui-icon ui-icon-search"></span></button></td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <div>
                                                                                <table id="table_matriz_busqueda"></table>
                                                                                <div id="pager_table_matriz_busqueda"></div>
                                                                            </div>
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
                            <div id="panelGestorCampo" style="display:none;" align="center" class="ui-widget-content">
                                <!-- <table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
                                    <tr>
                                        <td>
                                            <input type="hidden" id="IdClienteCarteraCampoMain" name="IdClienteCarteraCampoMain" />
                                            <input type="hidden" id="IdClienteCampoMain" name="IdClienteCampoMain" />
                                            <input type="hidden" id="IdCarteraCampoMain" name="IdCarteraCampoMain" />
                                            <input type="hidden" id="CodigoClienteCampoMain" name="CodigoClienteCampoMain" />
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <div align="center" style="padding:5px;">
                                                <table>
                                                    <tr>
                                                        <td align="right">Codigo</td>
                                                        <td><input class="cajaForm longCajaForm" onkeyup="if( event.keyCode==13 ){ searchClientePorCodigo();$('#cbCampoDireccionVisita').focus(); }" type="text" id="txtCampoCodigoSearch" /></td>
                                                        <td><button onclick="searchClientePorCodigo()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-search"></span></button></td>
                                                        <td align="right">Numero Doc</td>
                                                        <td><input class="cajaForm longCajaForm" onkeyup="if( event.keyCode==13 ){ searchClientePorNumeroDocumento();$('#cbCampoDireccionVisita').focus(); }" type="text" id="txtCampoNumeroDocumentoSearch" /></td>
                                                        <td><button onclick="searchClientePorNumeroDocumento()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-search"></span></button></td>
                                                        <td style="width:200px;" align="right"><label id="lbCantidadVisitasCampo" style="font-weight:bold;font-size:24px;">0</label></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right">Telefono</td>
                                                        <td><input class="cajaForm longCajaForm" onkeyup="if( event.keyCode==13 ){ searchClientePorTelefono( this.value, $('#cbCampoGlobalesCartera').val() );$('#cbCampoDireccionVisita').focus(); }" type="text" id="txtCampoTelefonoSearch" /></td>
                                                        <td><button onclick=" searchClientePorTelefono( $('#txtCampoTelefonoSearch').val(), $('#cbCampoGlobalesCartera').val() ) " class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-search"></span></button></td>
                                                        <td align="right">Numero Cuenta</td>
                                                        <td><input class="cajaForm longCajaForm" onkeyup="if( event.keyCode==13 ){ searchClientePorNumeroCuenta( this.value, $('#cbCampoGlobalesCartera').val() );$('#cbCampoDireccionVisita').focus(); }" type="text" id="txtCampoNumeroCuentaSearch" /></td>
                                                        <td><button onclick=" searchClientePorNumeroCuenta( $('#txtCampoNumeroCuentaSearch').val(), $('#cbCampoGlobalesCartera').val() ) " class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-search"></span></button></td>
                                                        <td></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div onclick="_slide2(this,'PanelCampoTableDatosCuenta')">
                                                <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                    <tr>
                                                        <td style="width:25px; height:25px;">
                                                            <div class="backPanel iconPinBlueDown" ></div>
                                                        </td>
                                                        <td style=" border-bottom:1px solid #EADEC8;">
                                                            <div style="direction:ltr;">
                                                                <a class="text-blue">Datos de Cliente</a>
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
                                            <div id="PanelCampoTableDatosCuenta" style="display:block;padding:5px 10px;" align="center">
                                                <table cellpadding="0" cellspacing="0" id="table_datos_cliente_campo" style="width: 916px;">
                                                    <tbody>
                                                        <tr>
                                                            <td align="center" style="padding:3px 8px;" class="ui-state-default ui-corner-tl">CODIGO_CLIENTE</td>
                                                            <td align="center" style="padding:3px 8px;" class="ui-widget-content" id="txtCampoCodigoCliente"></td>
                                                            <td align="center" style="padding:3px 8px;" class="ui-state-default">CLIENTE</td>
                                                            <td align="center" style="padding:3px 8px;" class="ui-widget-content" id="txtCampoNombreCodigoCliente"></td>
                                                            <td align="center" style="padding:3px 8px;" class="ui-state-default">TIPO_CLIENTE</td>
                                                            <td align="center" style="padding:3px 8px;" class="ui-widget-content"></td>
                                                            <td align="center" style="padding:3px 8px;" class="ui-state-default">NUMERO DOC</td>
                                                            <td align="center" style="padding:3px 8px;" class="ui-widget-content ui-corner-tr" id="txtCampoNumeroDocumentoCliente"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="center" style="padding:3px 8px;" class="ui-state-default">CARTERA</td>
                                                            <td align="center" style="padding:3px 8px;" class="ui-widget-content"></td>
                                                            <td></td>
                                                            <td>&nbsp;</td>
                                                            <td colspan="6"></td>
                                                        </tr>                                                                      
                                                    </tbody>
                                                </table>
                                                <br>
                                                <div class="boton_estilo fondo_gradiente_azul" style="width:120px;" id="idmantemiento_telf_cobranzas">
                                                    <img src="../img/telephone_blue-128.png" width="25" class="boton_imagen" style="position: absolute;left: 6px;top:0px;">
                                                    <div class="lin_vet"></div> 
                                                    <span class="boton_letra">TELEFONO</span>
                                                </div>
                                                <input type="hidden" id="add_telf_titu_aval" value="1" />
                                                <input type="hidden" id="codigo_cliente_aval_opcion" value="1" />
                                                <br>
                                                <br>
                                                <table id="Representante_aval_campo" style="display:none"></table>
                                                <br>
                                                <div id="table_aval_direccion_campo" style="display:none"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>
                                                <table style="width:100%;">
                                                    <tr>
                                                        <td id="content_table_tab_campo">
                                                            <div id="layerTabCampoVisita" style="padding:0px;display:block;" align="center">
                                                                <div id="layerFormCampoVisita">
                                                                    <input type="hidden" id="HdIdTransaccionCampo" />
                                                                    <input type="hidden" id="HdIdVisitaCampo" />
                                                                    <input type="hidden" id="HdIdCpgCampo" />
                                                                    <table border="0" cellpadding="0" cellspacing="0" class="ui-corner-all" style="border: 0px solid rgb(224, 207, 194); z-index: 100;width: 100%;" >
                                                                        <tr>
                                                                            <td>
                                                                                <div>
                                                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                                                        <tr>
                                                                                            <td style="width:130px;">
                                                                                                <div style="display:inline;"><label class="text-black">DIRECCIÓN</label></div>
                                                                                            </td>
                                                                                            <td><select class="combo" onkeyup = " if( event.keyCode==13 ){ $(this).blur();$('#txtCampoFechaVisita').focus(); } " style="width:500px;" id="cbCampoDireccionVisita"><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <div>
                                                                                    <div style="display:inline;"><label class="formTitle">Datos de Visita</label></div>
                                                                                </div>
                                                                                <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                    <tr>
                                                                                        <td class="labelHeader" style="text-align: left; width: 90px; white-space: normal;"><label class="text-black">Fecha Visita</label></td>
                                                                                        <td class="inputHeader"><input style="width:70px;" onkeyup="if( event.keyCode == 13 ){ $(this).blur();$('#txtCampoFechaRecepcion').focus(); }" class="cajaForm longCajaForm" type="text" id="txtCampoFechaVisita" /></td>
                                                                                        <td class="labelHeader" style="text-align: left; width: 100px; white-space: normal;"><label class="text-black">Fecha Recepcion</label></td>
                                                                                        <td class="inputHeader"><input style="width:70px;" onkeyup="if( event.keyCode == 13 ){ $(this).blur();$('#cbNotificadorCampoVisita').focus(); }" class="cajaForm longCajaForm" type="text" id="txtCampoFechaRecepcion" /></td>
                                                                                        <td class="labelHeader" style="text-align: left; width: 60px; white-space: normal;"><label class="text-black">Notificador</label>
                                                                                        <td class="inputHeader"><select style="width:200px;" onkeyup="if( event.keyCode == 13 ){ $(this).blur();$('#txtCampoHoraUbicacion').focus(); }" class="combo" id="cbNotificadorCampoVisita"><option value="0">--Seleccione--</option></select></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td class="labelHeader" style="text-align: left; width: 90px; white-space: normal;"><label class="text-black">Hora Llegada</label></td>
                                                                                        <td class="inputHeader"><input style="width:70px;" onkeyup="if( event.keyCode == 13 ){ $(this).blur();$('#txtCampoHoraSalida').focus(); }" class="cajaForm longCajaForm" type="text" id="txtCampoHoraUbicacion" /></td>
                                                                                        <td class="labelHeader" style="text-align: left; width: 90px; white-space: normal;"><label class="text-black">Hora Salida</label></td>
                                                                                        <td class="inputHeader"><input style="width:70px;" onkeyup="if( event.keyCode == 13 ){ $(this).blur();$('#cbCampoFinal').focus(); }" class="cajaForm longCajaForm" type="text" id="txtCampoHoraSalida" /></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                    </tr>
                                                                                </table>

                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >
                                                                                <div>
                                                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                                                        <tr>
                                                                                            <td>
                                                                                                <div style="display:inline;"><label class="text-black">Estados</label></div>
                                                                                            </td>
                                                                                            <td><select style="width:150px;" onkeyup="if( event.keyCode == 13 ){ $(this).blur();$('#cbCampoContacto').focus(); }" id="cbCampoFinal" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td><div style="display:inline;"><label class="text-black">Contacto</label></div></td>
                                                                                            <td><select style="width:150px;" onkeyup="if( event.keyCode == 13 ){ $(this).blur();$('#txtCampoNombreContacto').focus(); }" id="cbCampoContacto" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td><div style="display:inline;"><label class="text-black">Nombre Contacto</label></div></td>
                                                                                            <td><input onkeyup="if( event.keyCode == 13 ){ $(this).blur();$('#cbCampoParentesco').focus(); }" id="txtCampoNombreContacto" type="text" class="cajaForm" /></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <div >
                                                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                                                        <tr>
                                                                                            <td><div style="display:inline;"><label class="text-black">Parentesco</label></div></td>
                                                                                            <td><select style="width:150px;" onkeyup="if( event.keyCode == 13 ){ $(this).blur();$('#cbCampoMotivoNoPago').focus(); }" id="cbCampoParentesco" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                            <td><div style="display:inline;"><label class="text-black">Motivo No Pago</label></div></td>
                                                                                            <td><select style="width:150px;" onkeyup="if( event.keyCode == 13 ){ $(this).blur();$('#txtCampoObservacion').focus(); }" id="cbCampoMotivoNoPago" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="background-color: #FFF;">
                                                                                <div>
                                                                                    <div style="display:inline;">
                                                                                        <label class="formTitle">Cuentas A Aplicar Gestion</label>
                                                                                        <br>
                                                                                        Cant. Docum.=<span style="color:#6E1C3A;font-weight:bold;font-size:12px;" id="vidcant_docum"></span> &#9646; Total Imp.Orig. $=<span style="color:#6E1C3A;font-weight:bold;font-size:12px;" id="vitot_orig"></span> &#9646; Total Saldos S/.=<span style="color:#6E1C3A;font-weight:bold;font-size:12px;" id="vitot_saldos_sol"></span> &#9646; Total Saldos $=<span style="color:#6E1C3A;font-weight:bold;font-size:12px;" id="vitot_saldos_dol"></span>
                                                                                    </div>
                                                                                </div>
                                                                                <table style="width:100%;">
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div>
                                                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                                                    <tr>
                                                                                                        <td align="center">
                                                                                                            <table cellpadding="0" cellspacing="0" border="0" style="width:825px">
                                                                                                                
                                                                                                            </table>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td align="center">
                                                                                                            <div style="width: 1060px; height: auto; overflow-y: auto;">
                                                                                                                <table id="table_cuenta_aplicar_gestion_visita" cellpadding="0" cellspacing="0" border="0"></table>
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
                                                                        <tr>
                                                                            <td>
                                                                                <div >
                                                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                                                        <tr>
                                                                                            <td style="width:130px;">
                                                                                                <div style="display:inline;"><label class="text-black">Observacion</label></div>
                                                                                            </td>
                                                                                            <td><textarea onkeyup=" if( event.keyCode == 13 ){ $(this).blur();$('#txtCampoDescripcionInmueble').focus(); } " id="txtCampoObservacion" class="textareaForm" style="width:600px;height:40px;"></textarea></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <div>
                                                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                                                        <tr>
                                                                                            <td style="width:130px;">
                                                                                                <div style="display:inline;"><label class="text-black">Descripcion de Inmueble</label></div>
                                                                                            </td>
                                                                                            <td><textarea onkeyup="if( event.keyCode==13 ){ $(this).blur();$('#btnCampoGrabarVisita').trigger('click'); }" id="txtCampoDescripcionInmueble" class="textareaForm" style="width:600px;height:40px;"></textarea></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="lastRowButton" align="center">
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td><button id="btnCampoGrabarVisita" onclick="save_visita()" class="btn">Guardar</button></td>
                                                                                            <td><button onclick="cancel_visita()" class="btn">Cancelar</button></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                                <table>
                                                                    <tr>
                                                                        <td>
                                                                            <div>
                                                                                <table>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div>
                                                                                                <table>
                                                                                                        <tr>
                                                                                                        <td align="right">Fecha Inicio</td>
                                                                                                        <td><input class="cajaForm longCajaForm" type="text" id="txtCampoVisitaFechaInicio" /></td>
                                                                                                        <td align="right">Fecha Fin</td>
                                                                                                        <td><input class="cajaForm longCajaForm" type="text" id="txtCampoVisitaFechaFin" /></td>
                                                                                                        <td>
            	                                                                                            <button onclick="reloadJQGRID_visita()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-search"></span></button>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </table>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div style="width:900px;overflow-x:auto;">
                                                                                                <table id="table_campo_visita"></table>
                                                                                                <div id="pager_table_campo_visita"></div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <div id="layerTabCampoTelefono" class="ui-widget-content" style="display:none;padding:5px;" align="center">
                                                                <table style="width:100%;">
                                                                    <tr>
                                                                        <td align="center">
                                                                            <div align="center">
                                                                                <table>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div id="layerFormCampoTelefono">
                                                                                                <input type="hidden" id="HdIdTelefonoCampo" />
                                                                                                <table class="ui-corner-all" cellpadding="0" cellspacing="0" border="0" style="border: 6px solid rgb(224, 207, 194); z-index: 100;">
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <div class="formHeader">
                                                                                                                <div style="display:inline;"><label class="formTitle">Datos de Telefono</label></div>
                                                                                                            </div>
                                                                                                            <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                                                <tr>
                                                                                                                    <td class="labelHeader" style="text-align: left; width: auto; white-space: normal;"><label class="text-black">Numero</label></td>
                                                                                                                    <td class="inputHeader"><input class="cajaForm longCajaForm" type="text" id="txtCampoTelefonoNumero" /></td>
                                                                                                                    <td class="labelHeader" style="text-align: left; width: auto; white-space: normal;"><label class="text-black">Anexo</label></td>
                                                                                                                    <td class="inputHeader"><input class="cajaForm longCajaForm" type="text" id="txtCampoTelefonoAnexo" /></td>
                                                                                                                </tr>
                                                                                                                <tr>    
                                                                                                                    <td class="labelHeader" style="text-align: left; width: auto; white-space: normal;"><label class="text-black">Tipo</label></td>
                                                                                                                    <td class="inputHeader"><select class="combo" id="cbCampoTelefonoTipo"><option value="0">--Seleccione--</option></select></td>
                                                                                                                    <td class="labelHeader" style="text-align: left; width: auto; white-space: normal;"><label class="text-black">Referencia</label></td>
                                                                                                                    <td class="inputHeader"><select class="combo" id="cbCampoTelefonoReferencia"><option value="0">--Seleccione--</option></select></td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td class="labelHeader" style="text-align: left; width: auto; white-space: normal;"><label class="text-black">Linea</label></td>
                                                                                                                    <td class="inputHeader"><select class="combo" id="cbCampoTelefonoLinea"><option value="0">--Seleccione--</option></select></td>
                                                                                                                    <td class="labelHeader" style="text-align: left; width: auto; white-space: normal;"><label class="text-black">Origen</label></td>
                                                                                                                    <td class="inputHeader"><select class="combo" id="cbCampoTelefonoOrigen" ><option value="0">--Seleccione--</option></select></td>
                                                                                                                </tr>
                                                                                                            </table>            
                                                                                                        </td> 	    
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <div class="formHeader">
                                                                                                                <div style="display:inline;"><label class="formTitle">Datos Adicionales</label></div>
                                                                                                            </div>
                                                                                                            <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">     
                                                                                                                <tr>
                                                                                                                    <td class="labelHeader" style="text-align: left; width: auto; white-space: normal;"><label class="text-black">Observacion</label></td>
                                                                                                                    <td class="inputHeader"><textarea id="txtCampoTelefonoObservacion" class="textareaForm" style="width:600px;height:90px;"></textarea></td>
                                                                                                                </tr>
                                                                                                            </table>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <div class="lastRowButton" align="center">
                                                                                                                <table>
                                                                                                                    <tr>
                                                                                                                        <td><button onclick="save_telefono()" class="btn">Guardar</button></td>
                                                                                                                        <td><button onclick="update_telefono()" class="btn">Actualizar</button></td>
                                                                                                                        <td><button onclick="cancel_telefono()" class="btn">Cancelar</button></td>
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
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="center">
                                                                            <div>
                                                                                <table>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div>
                                                                                                <table id="table_campo_telefono"></table>
                                                                                                <div id="pager_table_campo_telefono"></div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <div id="layerTabCampoDireccion" class="ui-widget-content" style="display:none;padding:5px;" align="center">
                                                                <table style="width:100%;">
                                                                    <tr>
                                                                        <td align="center">
                                                                            <div align="center">
                                                                                <table>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div id="layerFormCampoDireccion">
                                                                                                <input type="hidden" id="HdIdDireccionCampo" />
                                                                                                <table cellpadding="0" cellspacing="0" border="0" class="ui-corner-all" style="border: 6px solid rgb(224, 207, 194); z-index: 100;">
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <div class="formHeader">
                                                                                                                <div style="display:inline;"><label class="formTitle">Datos de Direccion</label></div>
                                                                                                            </div>
                                                                                                            <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                                                                                <tr>
                                                                                                                    <td class="labelHeader" style="text-align: left; width: auto; white-space: normal;"><label class="text-black">Direccion</label></td>
                                                                                                                    <td class="inputHeader"><input class="cajaForm longCajaForm" type="text" id="txtCampoDireccionDireccion" /></td>
                                                                                                                    <td class="labelHeader" style="text-align: left; width: auto; white-space: normal;"><label class="text-black">Direccion Referencia</label></td>
                                                                                                                    <td class="inputHeader"><input class="cajaForm longCajaForm" type="text" id="txtCampoDireccionDireccionReferencia" /></td>
                                                                                                                    <td class="labelHeader" style="text-align: left; width: auto; white-space: normal;"><label class="text-black"></label></td>
                                                                                                                    <td class="inputHeader"></td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td class="labelHeader" style="text-align: left; width: auto; white-space: normal;"><label class="text-black">Referencia</label></td>
                                                                                                                    <td class="inputHeader"><select id="cbCampoDireccionReferencia" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                                                    <td class="labelHeader" style="text-align: left; width: auto; white-space: normal;"><label class="text-black">Origen</label></td>
                                                                                                                    <td class="inputHeader"><select id="cbCampoDireccionOrigen" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                                                                                    <td class="labelHeader" style="text-align: left; width: auto; white-space: normal;"><label class="text-black">Ubigeo</label></td>
                                                                                                                    <td class="inputHeader"><input class="cajaForm longCajaForm" type="text" id="txtCampoDireccionUbigeo" /></td>
                                                                                                                </tr>
                                                                                                                <tr>   
                                                                                                                    <td class="labelHeader" style="text-align: left; width: auto; white-space: normal;"><label class="text-black">Departamento</label></td>
                                                                                                                    <td class="inputHeader"><select onblur=" $('#txtCampoDireccionProvincia,#txtCampoDireccionDistrito').val(''); listar_provincia( this.value, 'txtCampoDireccionProvincia' ) " style="width:130px;" class="combo" id="txtCampoDireccionDepartamento" ><option value="">--Seleccione--</option></select></td>
                                                                                                                    <td class="labelHeader" style="text-align: left; width: auto; white-space: normal;"><label class="text-black">Provincia</label></td>
                                                                                                                    <td class="inputHeader"><select onblur = " $('#txtCampoDireccionDistrito').val('#') ;listar_distrito ( $('#txtCampoDireccionDepartamento').val(), this.value, 'txtCampoDireccionDistrito' ) " style="width:130px;" class="combo" id="txtCampoDireccionProvincia" ><option value="">--Seleccione--</option></select></td>
                                                                                                                    <td class="labelHeader" style="text-align: left; width: auto; white-space: normal;"><label class="text-black">Distrito</label></td>
                                                                                                                    <td class="inputHeader"><select style="width:130px;" class="combo" id="txtCampoDireccionDistrito" ><option value="">--Seleccione--</option></select></td>
                                                                                                                </tr>
                                                                                                            </table>
                                                                                                        </td>     
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <div class="formHeader">
                                                                                                                <div style="display:inline;"><label class="formTitle">Datos Adicionales</label></div>
                                                                                                            </div>
                                                                                                            <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">     
                                                                                                                <tr>
                                                                                                                    <td class="labelHeader" style="text-align: left; width: auto; white-space: normal;"><label class="text-black">Observacion</label></td>
                                                                                                                    <td class="inputHeader"><textarea class="textareaForm" id="txtCampoDireccionObservacion" style="width:600px;height:80px;" ></textarea></td>
                                                                                                                </tr>
                                                                                                            </table>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <div class="lastRowButton" align="center">
                                                                                                                <table>
                                                                                                                    <tr>
                                                                                                                        <td><button onclick="save_direccion()" class="btn">Guardar</button></td>
                                                                                                                        <td><button onclick="update_direccion()" class="btn">Actualizar</button></td>
                                                                                                                        <td><button onclick="cancel_direccion()" class="btn">Cancelar</button></td>
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
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="center">
                                                                            <div>
                                                                                <table>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <table id="table_campo_direcciones"></table>
                                                                                            <div id="pager_table_campo_direcciones"></div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <div id="layerTabCampoGlobales" class="ui-widget-content" style="display:none;padding:5px;" align="center">
                                                                <table>
                                                                    <tr>
                                                                        <td align="right">Campa&ntilde;a</td>
                                                                        <td><select id="cbCampoGlobalesCampania" class="combo" onchange="load_cartera_campo(this.value)" ><option value="0">--Seleccione--</option></select></td>
                                                                        <td align="right">Cartera</td>
                                                                        <td><select class="combo" id="cbCampoGlobalesCartera" ><option value="0">--Seleccione--</option></select></td>
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
                                                            <div style="margin-left:150px;">
                                                                <table cellpadding="0" cellspacing="0" border="0" id="table_tab_campo">
                                                                    <tr>
                                                                        <td>
                                                                            <div id="tabCampoVisita" onclick="_activeTabLayer('table_tab_campo','tabCampo',this,'content_table_tab_campo','layerTabCampo','layerTabCampoVisita')" class="itemTabActive border-radius-bottom pointer ui-widget-header" style="margin: 0px 1px 0pt 0pt;"><div class="text-white">Visita</div></div>
                                                                        </td>
                                                                        <td>
                                                                            <div id="tabCampoTelefono" onclick="_activeTabLayer('table_tab_campo','tabCampo',this,'content_table_tab_campo','layerTabCampo','layerTabCampoTelefono')" class="itemTab border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;"><div class="AitemTab">Telefonos</div></div>
                                                                        </td>
                                                                        <td>
                                                                            <div id="tabCampoDireccion" onclick="_activeTabLayer('table_tab_campo','tabCampo',this,'content_table_tab_campo','layerTabCampo','layerTabCampoDireccion')" class="itemTab border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;"><div class="AitemTab">Direcciones</div></div>
                                                                        </td>
                                                                        <td>
                                                                            <div id="tabCampoGlobales" onclick="_activeTabLayer('table_tab_campo','tabCampo',this,'content_table_tab_campo','layerTabCampo','layerTabCampoGlobales')" class="itemTab border-radius-bottom pointer ui-widget-content" style="margin: 0px 1px 0pt 0pt;"><div class="AitemTab">Globales</div></div>
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
                                </table> -->
                                
                                <div style="background-color: white;">
                                    <input type="hidden" id="IdClienteCarteraCampoMain" name="IdClienteCarteraCampoMain" />
                                    <input type="hidden" id="IdClienteCampoMain" name="IdClienteCampoMain" />
                                    <input type="hidden" id="IdCarteraCampoMain" name="IdCarteraCampoMain" />
                                    <input type="hidden" id="CodigoClienteCampoMain" name="CodigoClienteCampoMain" />
                                    <table cellpadding="0" cellspacing="0" border="0" style="background-color: white;width: 1150px;">
                                        <tr>
                                            <td colspan="13" style="text-align: center;padding: 8px;"><span style="font-size: 22px;font-family: Arial-Black;">GESTIÓN VISITA</span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="13"><p style="font-size: 12px;text-align: left;font-family: Arial-Black;">BUSQUEDA DEL CLIENTE</p></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="width: 150px;"><span style="font-size: 11px;text-align: center;font-family: Arial;">CODIGO CLIENTE</span></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td><input type="text" class="cajaForm longCajaForm" name=""  style="width:150px;text-align: left;font-family: Arial;" id="vis_codigo_cliente" placeholder=""></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="width: 150px;"><span style="font-size: 11px;text-align: center;font-family: Arial;">CLIENTE</span></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td><input type="text" class="cajaForm longCajaForm" name=""  style="width:150px;text-align: left;font-family: Arial;" id="vis_cliente" placeholder=""></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="width: 150px;"><span style="font-size: 11px;text-align: center;font-family: Arial;">DOCUMENTO</span></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="width: 150px;"><input type="text" class="cajaForm longCajaForm" name="" style="width: 30px;text-align: left;font-family: Arial;" id="vis_td" placeholder=""><input type="text" class="cajaForm longCajaForm" name="vis_doc" id="vis_doc" style="width:110px;text-align: left;font-family: Arial;" id="" placeholder=""></td>
                                            <td style="width: 10px;">&nbsp;</td>                                                                               
                                        </tr>
                                        <tr>
                                            <td colspan="13" style="text-align: center;"><input style="font-weight: 900;font-size: 11px;" type="button" name="" class="btndestacado" value="CONSULTAR"  id="idconsultar_cliente_campo"/></td> 
                                        </tr>
                                        <tr>
                                            <td colspan="13" style="padding: 3px;">
                                                <table id="table_Lista_cliente_campo"></table>
                                                <div id="pager_table_Lista_cliente_campo"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="13"><p style="font-size: 12px;text-align: left;font-family: Arial-Black;">DATOS DEL CLIENTE</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="13">
                                                <div>
                                                    <span style="font-size: 10px;text-align: center;font-family: Arial;font-weight: 900;">CODIGO CLIENTE: </span><span style="font-size: 10px;text-align: center;font-family: Arial;" id="dato_idcliente"></span>&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <span style="font-size: 10px;text-align: center;font-family: Arial;font-weight: 900;">RAZON SOCIAL: </span><span style="font-size: 10px;text-align: center;font-family: Arial;" id="dato_razon_social"></span>&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <span style="font-size: 10px;text-align: center;font-family: Arial;font-weight: 900;">NRO DOC: </span><span style="font-size: 10px;text-align: center;font-family: Arial;" id="dato_nro_doc"></span>&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <span style="font-size: 10px;text-align: center;font-family: Arial;font-weight: 900;">LINEA DE CREDITO: </span><span style="font-size: 10px;text-align: center;font-family: Arial;" id="dato_linea_credito"></span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="13" style="text-align: center;padding: 5px 0 5px 0;">
                                                <!-- <input style="font-weight: 900;font-size: 11px;" type="button" name="" class="btndestacado" value="TELEFONO"  id="idconsultar_telefono_campo"/> -->
                                                <input style="font-weight: 900;font-size: 11px;" type="button" name="" class="btndestacado" value="DIRECCION"  id="idconsultar_direccion_campo"/>
                                                <input style="font-weight: 900;font-size: 11px;" type="button" name="" class="btndestacado" value="CONTACTOS"  id="idmantemiento_contactos_cobranzas_vis"/>
                                                <!-- <input style="font-weight: 900;font-size: 11px;" type="button" name="" class="btndestacado" value="CORREO"  id="idconsultar_correo_campo"/> -->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="13"><p style="font-size: 12px;text-align: left;font-family: Arial-Black;">INGRESAR DATOS VISITA</p></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="width: 150px;"><span style="font-size: 11px;text-align: center;font-family: Arial;">DIRECCION</span></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td colspan="9">
                                                <select style="width:870px;height: 17px;margin: 2px;" id="cbCampoDireccionVisita" class="combo"></select>
                                            </td>
                                            <td style="width: 10px;">&nbsp;</td>                                                                               
                                        </tr>
                                        <tr>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="width: 150px;"><span style="font-size: 11px;text-align: center;font-family: Arial;">FECHA VISITA</span></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td><input type="text" class="cajaForm longCajaForm" name=""  style="width:150px;text-align: left;font-family: Arial;" id="txtCampoFechaVisita" placeholder=""></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="width: 150px;"><span style="font-size: 11px;text-align: center;font-family: Arial;">HORA LLEGADA</span></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td><input type="text" class="cajaForm longCajaForm" name=""  style="width:150px;text-align: left;font-family: Arial;" id="txtCampoHoraUbicacion" placeholder=""></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="width: 150px;"><span style="font-size: 11px;text-align: center;font-family: Arial;">HORA SALIDA</span></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="width: 150px;"><input type="text" class="cajaForm longCajaForm" name=""  style="width:150px;text-align: left;font-family: Arial;" id="txtCampoHoraSalida" placeholder=""></td>
                                            <td style="width: 10px;">&nbsp;</td>                                                                               
                                        </tr>
                                        <tr>
                                            <td colspan="13"><p style="font-size: 12px;text-align: left;font-family: Arial-Black;">INGRESAR DATOS DE GESTION</p></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="width: 150px;"><span style="font-size: 11px;text-align: center;font-family: Arial;">ESTADOS</span></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td><select style="width:153px;height: 17px;margin: 2px;" id="cbCampoFinal" class="combo"></select></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="width: 150px;"><span style="font-size: 11px;text-align: center;font-family: Arial;">CONTACTO</span></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td>
                                                <select style="width:153px;height: 17px;margin: 2px;" id="cbCampoContacto" class="combo"></select>
                                            </td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="width: 150px;"><span style="font-size: 11px;text-align: center;font-family: Arial;">NOMBRE CONTACTO</span></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="width: 150px;"><input type="text" class="cajaForm longCajaForm" name=""  style="width:150px;text-align: left;font-family: Arial;" id="txtCampoNombreContacto" placeholder=""></td>
                                            <td style="width: 10px;">&nbsp;</td>                                                                               
                                        </tr>
                                        <tr>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="width: 150px;"><span style="font-size: 11px;text-align: center;font-family: Arial;">PARENTESCO</span></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td>
                                                <select style="width:153px;height: 17px;margin: 2px;" id="cbCampoParentesco" class="combo"></select>
                                            </td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="width: 150px;"><span style="font-size: 11px;text-align: center;font-family: Arial;">MOTIVO NO PAGO</span></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td>
                                                <select style="width:153px;height: 17px;margin: 2px;" id="cbCampoMotivoNoPago" class="combo"></select>
                                            </td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="width: 150px;"><span style="font-size: 11px;text-align: center;font-family: Arial;">ESTADO DE CLIENTE</span></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="width: 150px;">
                                                <select style="width:153px;height: 17px;margin: 2px;" id="cbCampoEstadoCliente" class="combo"></select>
                                            </td>
                                            <td style="width: 10px;">&nbsp;</td>                                                                               
                                        </tr>
                                        <tr>
                                            <td colspan="13"><p style="font-size: 12px;text-align: left;font-family: Arial-Black;">GESTIONAR CUENTAS</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="13"><p style="font-size: 12px;text-align: left;font-family: Arial-Black;"></p></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="width: 150px;"><span style="font-size: 11px;text-align: center;font-family: Arial;">EMPRESA</span></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td>
                                                <select style="width:153px;height: 17px;margin: 2px;" id="cbCampoEmpresa" class="combo">
                                                    <option value="">.:Seleccione:.</option>
                                                    <option value="CAISAC">CAISAC</option>
                                                    <option value="ANDEX">ANDEX</option>
                                                    <option value="SEMILLAS">SEMILLAS</option>
                                                    <option value="SUNNY">SUNNY</option>
                                                </select>
                                            </td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="width: 150px;"><span style="font-size: 11px;text-align: center;font-family: Arial;">DOCUMENTO</span></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td><input type="text" class="cajaForm longCajaForm" name="" style="width: 30px;text-align: left;font-family: Arial;" id="vis_xtd" placeholder=""><input type="text" class="cajaForm longCajaForm" name="vis_doc" id="xvis_doc" style="width:100px;text-align: left;font-family: Arial;" id="" placeholder=""></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="width: 150px;" colspan="3"><span style="font-size: 11px;text-align: center;font-family: Arial;"><input type="checkbox" name="contado" value="" id="adelantado"> PAGO ADELANTADO / CONTADO / EFECTIVO</span></td>

                                            <td style="width: 10px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td colspan="13"><p style="font-size: 12px;text-align: left;font-family: Arial-Black;"></p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="13">
                                                <table id="table_cuenta_aplicar_gestion_visita" cellpadding="0" cellspacing="0" border="0"></table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="13" style="padding: 10px;" id="resumen_deuda">
                                                
                                                    

                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="width: 150px;vertical-align: top;"><span style="font-size: 11px;text-align: center;font-family: Arial;">OBS</span></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td colspan="3"><textarea style="margin: 0px;width: 302px;height: 58px;" id="txtCampoObservacion"></textarea></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="width: 150px;vertical-align: top;"><span style="font-size: 11px;text-align: center;font-family: Arial;">DESCRIP. INMUEBLE</span></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td colspan="3"><textarea style="margin: 0px;width: 302px;height: 58px;" id="txtCampoDescripcionInmueble"></textarea></td>
                                            <td style="width: 10px;">&nbsp;</td>                                                                               
                                        </tr>
                                        <tr>
                                            <td colspan="13" style="text-align: center;"><input style="font-weight: 900;font-size: 11px;" type="button" name="" class="btndestacado" onclick="save_visita()" value="GUARDAR"  id="idconsultar_cliente_campo"/></td> 
                                        </tr>
                                        <tr>
                                            <td colspan="13"><p style="font-size: 12px;text-align: left;font-family: Arial-Black;">VISITAS REGISTRADAS</p></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="width: 150px;"><span style="font-size: 11px;text-align: center;font-family: Arial;">FECHA INICIO</span></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td><input type="text" class="cajaForm longCajaForm" name=""  style="width:150px;text-align: left;font-family: Arial;" id="call_ini" placeholder=""></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="width: 150px;"><span style="font-size: 11px;text-align: center;font-family: Arial;">FECHA FIN</span></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td><input type="text" class="cajaForm longCajaForm" name=""  style="width:150px;text-align: left;font-family: Arial;" id="call_fin" placeholder=""></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td style="width: 150px;"><span style="font-size: 11px;text-align: center;font-family: Arial;display: none;">ESTADO</span></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                            <td>
                                                <select style="width:153px;height: 17px;margin: 2px;display: none;" id="cbCampoFinal_vis" class="combo">
                                                    
                                                </select></td>
                                            <td style="width: 10px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <!--<td colspan="13" style="text-align: center;"><input style="font-weight: 900;font-size: 11px;" type="button" name="" class="btndestacado" value="CONSULTAR"  id="idconsultar_cliente_campo"/></td>--> 
                                        </tr>
                                        <tr>
                                            <td colspan="13" style="padding:3px;">
                                                <div style="margin: 0 auto;">
                                                    

                                                    <div id="tabs_gestion_visita">
                                                        <ul>
                                                            <li><a href="#tabsg-1">Visitas</a></li>
                                                            <li><a href="#tabsg-2">Llamadas</a></li>
                                                        </ul>
                                                        <div id="tabsg-1">
                                                            <table id="table_campo_visita"></table>
                                                            <div id="pager_table_campo_visita"></div>                                                    
                                                        </div>
                                                        <div id="tabsg-2">
                                                            <table id="table_llamada_two" style=""></table>
                                                            <div id="pager_table_llamada_two" style=""></div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </td>
                                        </tr>                                        
                                    </table>
                                </div>
                            </div>                           
                        </div>
                    </td>
                    <td id="showhide" class="showHide ui-widget-header" width="10px">
                                            <a >
                                                <div style="width:8px"></div>
                                            </a>
                                        </td>                  
                </tr>

                  
            </table>
                        
            
           <!-- <div class="ui-widget-header divFooter"></div>-->
            <div style="width: 1170px; height: 20px; border: 0 none;margin:0px auto" class="ui-widget-header ui-corner-bottom"></div>
        </div>
        <!--contenido-->

        <!--DIALOGS Y BARRAS ALGUNAS-->
        <div class="ui-widget-overlay" id="closeWindowCobrastOverlay" style="display:none;position:fixed;"></div>
        <!--NEOTEL-->
        <div id="dialog_usuario_neotel" class="sombra1 ui-widget-content t-center" style="display:none;position: fixed;top: 35px;right: 50px;">
            <div style="margin:5px 10px;">
                <span class="AitemTab">Usuario NEOTEL</span>
                <input type="text" id="txtUsuarioN" class="cajaForm shortCajaForm">
            </div>
            <div style="margin:5px 10px">
                <button class="btn" title="IR MODO NEOTEL" onclick="verifica_neotel();">NEOTEL</button>
                <button class="btn" title="CANCELAR" onclick="$('#dialog_usuario_neotel').fadeOut();">CANCELAR</button>
            </div>
        </div>
        <!--2016-03-11 para agregar campania en neotel-->
        <div id="dialog_campania_neotel" class="sombra1 ui-widget-content t-center" style="display:none;position: fixed;top: 113px;right: 50px;">
            <div style="margin:5px 10px;">
                <span class="AitemTab">Nro. de Campaña (ESTABLECER SÓLO PARA GESTIÓN MANUAL IMPLEMENTADO CON NEOTEL)</span>
                <input type="text" id="txtCAmpaniaNeotelManual" class="cajaForm shortCajaForm" value="5">
            </div>
            <div style="margin:5px 10px">
                <button class="btn" title="ESTABLECER CAMPAÑA" onclick="neotelDAO.ponerCampania();">ESTABLECER CAMPAÑA</button>
                <button class="btn" title="CANCELAR" onclick="$('#dialog_campania_neotel').fadeOut();">CANCELAR</button>
            </div>
            <div class="border-radius-top pointer ui-widget-content" id="msgCampania" style="display:none;height: 19px; padding-top: 8px;">
            </div>
        </div>


        <div id="barra_neotel" class="ui-widget-header" style="display:none">
            <div style="width:1000px;margin:0px auto;">
                <ul class="ul1">
                    <li class="lcdbi">
                        <div class="pri t-center">
                            <div  class="pri1">Status</div><div class="pri2" id="tagStatusN">...</div>
                        </div>
                    </li>
                    <li class="lcdbi">
                        <div class="pri t-center">
                            <div class="pri1">Anexo</div><div class="pri2" id="tagAnexoN">...</div>
                        </div>
                    </li>
                    <li class="lcdbi">
                        <div class="pri t-center">
                            <div class="pri1">Usuario</div><div class="pri2" id="tagUsuarioN">...</div>
                        </div>
                    </li>
                    <li class="lcdbi nav-sub">
                        <div class="pri t-center btnd">
                            <div class="pri1">Campaña</div><div class="pri2" id="tagCampaniaN">...</div>
                        </div>
                        <ul class="ui-state-highlight" style="bottom:40px;min-width:100px;margin-left:-15px;">
                            <li onclick="ponerCampania('12');"><span><i style="float:left;margin-right:5px;" class="ui-icon ui-icon-circle-check"></i> NATURAL</span></li>
                            <li onclick="ponerCampania('3');"><span><i style="float:left;margin-right:5px;" class="ui-icon ui-icon-circle-check"></i> BBVA 3</span></li>                                                        
                            <li onclick="ponerCampania('4');"><span><i style="float:left;margin-right:5px;" class="ui-icon ui-icon-circle-check"></i> BBVA 4</span></li>                            
                            <li onclick="ponerCampania('5');"><span><i style="float:left;margin-right:5px;" class="ui-icon ui-icon-circle-check"></i> BBVA 5</span></li>
                            <li onclick="ponerCampania('7');"><span><i style="float:left;margin-right:5px;" class="ui-icon ui-icon-circle-check"></i> PRUEBA</span></li>
                            <li onclick="ponerLogoutCampania();"><span><i style="float:left;margin-right:5px;" class="ui-icon ui-icon-circle-close"></i> ...</span></li>
                        </ul>
                    </li>
                    <li class="lcdbi nav-sub">
                        <div class="pri t-center btnd">
                            <div class="pri1">Pausas</div><div class="pri2" id="tagPausaN">...</div>
                        </div>
                        <ul class="ui-state-highlight" style="bottom:40px;min-width:100px;margin-left:-15px;">
                            <?
                            $std=array("1"=>"Descanso","2"=>"Tiempo Administrativo","3"=>"Capacitacion",
                                "8"=>"SSHH","9"=>"Consulta Supervisor","10"=>"FeedBack","11"=>"Topico","12"=>"Almuerzo","13"=>"Esperando Contingencia","4"=>"Break");
                            foreach ($std as $k => $v) { ?>
                            <li onclick="ponerPausa('<?=$k?>');"><span><i style="float:left;margin-right:5px;" class="ui-icon ui-icon ui-icon-circle-close"></i> <?=$v?></span></li>   
                            <?}?>
                            <li onclick="ponerUnPausa();"><span><i style="float:left;margin-right:5px;" class="ui-icon ui-icon-circle-check"></i> ...</span></li>
                        </ul>
                    </li>
                    <li class="lcdbi">
                        <div class="pri t-center">
                            <div class="pri1">Estado CRM</div><div class="pri2" id="tagEstadoCrmN">...</div>
                        </div>
                        <!--ul class="ui-state-highlight" style="bottom:40px;min-width:100px;margin-left:-10px;">
                            <li onclick="ponerCrmAvailable(function(){});"><span><i style="float:left;margin-right:5px;" class="ui-icon ui-icon-circle-check"></i> Available</span></li>
                            <li onclick="ponerCrmUnAvailable();"><span><i style="float:left;margin-right:5px;" class="ui-icon ui-icon-circle-close"></i> UnAvailable</span></li>
                        </ul-->
                    </li>
                    <li class="lcdbi">
                        <div class="pri t-center">
                            <i class="ui-icon ui-icon-comment inlineBlock"></i><span id="tagNumLlamN">...</span>
                        </div>
                    </li>
                    <li class="lcdbi">
                        <div class="pri t-center">
                            <div class="pri1">ID Llamada</div><div class="pri2" id="tagIdLlamadaN">...</div>
                        </div>
                    </li>
                </ul>

                <ul class="ul1" style="float:right;">
                    <li class="lcdbi nav-sub">
                        <div class="pri t-center btnd">
                            <i class="ui-icon ui-icon-notice inlineBlock" style="margin:10px 0px;"></i>
                        </div>
                        <ul class="ui-state-highlight" style="bottom:40px;margin-left:-30px;">
                            <li>
                                <div id="resumePositionN" style="margin:3px;max-height:500px;overflow:auto;padding:0px 20px 0px 0px"></div>
                            </li>
                        </ul>   
                    </li>
                </ul>
                
                <div style="display:none;">
                    <input type="text" id="flg_modo_neotel" class="input1" title="flg_modo_neotel" readonly>
                    <input type="text" id="flg_guardar_agenda" class="input1" title="flg_guardar_agenda" readonly>
                    <input type="text" id="txtFechaAgendaN" class="input2" title="txtFechaAgendaN" readonly>
                    <input type="text" id="txtBaseN" class="input2" title="txtBaseN" readonly>
                    <input type="text" id="txtIdContactoN" class="input2" title="txtIdContactoN" readonly>
                    <input type="text" id="txtDataN" class="input2" title="txtDataN" readonly>
                    <input type="text" id="txtIdLlamadaN" class="input2" title="txtIdLlamadaN" readonly>
                </div>
            </div>
            <div id="layerMessageNeotel" align="center"  style="bottom: 20px;display: block;position: absolute;right: 10px;"></div>
        </div>
        <!--/NEOTEL-->

        <div id="dialogAlerta" >
            <div align="center">
                <table>
                    <tr>
                        <td colspan="2"><div id="AlertaLayerMessage"></div></td>
                    </tr>
                    <tr>
                        <td align="right">Abonado</td>
                        <td align="left" id="txtAbonadoAlerta"></td>
                    </tr>
                    <tr>
                        <td align="right">Fecha</td>
                        <td align="left"><input class="cajaForm longCajaForm" type="text" id="txtFechaAlerta" /></td>
                    </tr>
                    <tr>
                        <td valign="top" align="right">Descripcion</td>
                        <td>
                            <div align="left">
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td>
                                            <textarea class="textareaForm" id="txtDescripcionAlerta" style="width:280px;height:150px;" ></textarea>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    <div id="DialogEditarRepresentanteLegal" style="display:none;z-index:1000;position:absolute;border:2px solid #FFF;width:300px;" class="ui-corner-all ui-widget-content">
        <div style="padding:5px 3px;background-color:#4D6185;" class="ui-corner-top"><table style="width:100%;"><tr><td><label style="color:#FFF;font-weight:bold;width:150px;">Actualizacion de Representante</label></td><td><img style="float:right;background-color:#4D6185;" onclick="$('#DialogEditarRepresentanteLegal').hide()" src="../img/action_stop.gif" /></td></tr></table></div>
        <input type="hidden" id="txtidrepresentante_legal" value="0">
        <table>
            <tr>
                <td><strong>Asesor Comercial</strong></td>
                <td><input type="text" style="width:180px" class="cajaForm longCajaForm" id="txtasesorcomercial_representante"/></td>
            </tr>
            <tr>
                <td><strong>Representante legal</strong></td>
                <td><input type="text" style="width:180px" class="cajaForm longCajaForm" id="txtrepresentantelegal_representante"/></td>
            </tr>                                                                                                        
            <tr>
                <td><strong>Responsable Pago</strong></td>
                <td><input type="text" style="width:180px" class="cajaForm longCajaForm" id="txtresponsablepago_representante" /></td>
            </tr>
            <tr>
                <td><strong>Observacion</strong></td>
                <td><input type="text" style="width:180px" class="cajaForm longCajaForm" id="txtobservacion_representante" /></td>
            </tr>                                                                          
            <tr align="center">
                <!--<td><button onclick="update_anexo()" class="btn" >Actualizar</button></td>-->
                <td colspan="2"><button onclick="actualizar_representante_legal()" class="btn" >Actualizar</button></td>
            </tr>
        </table>
    </div>    
    <div id="DialogNuevoRepresentanteLegal" style="display:none;z-index:1000;position:absolute;border:2px solid #FFF;width:300px;" class="ui-corner-all ui-widget-content">
        <div style="padding:5px 3px;background-color:#4D6185;" class="ui-corner-top"><table style="width:100%;"><tr><td><label style="color:#FFF;font-weight:bold;width:150px;">Nuevo de Representante</label></td><td><img style="float:right;background-color:#4D6185;" onclick="$('#DialogNuevoRepresentanteLegal').hide()" src="../img/action_stop.gif" /></td></tr></table></div>
        <table>
            <tr>
                <td><strong>Asesor Comercial</strong></td>
                <td><input type="text" style="width:180px" class="cajaForm longCajaForm" id="txtasesorcomercial_representantenew"/></td>
            </tr>
            <tr>
                <td><strong>Representante legal</strong></td>
                <td><input type="text" style="width:180px" class="cajaForm longCajaForm" id="txtrepresentantelegal_representantenew"/></td>
            </tr>                                                                                                        
            <tr>
                <td><strong>Responsable Pago</strong></td>
                <td><input type="text" style="width:180px" class="cajaForm longCajaForm" id="txtresponsablepago_representantenew" /></td>
            </tr>
            <tr>
                <td><strong>Observacion</strong></td>
                <td><input type="text" style="width:180px" class="cajaForm longCajaForm" id="txtobservacion_representantenew" /></td>
            </tr>                                                                          
            <tr align="center">
                <!--<td><button onclick="update_anexo()" class="btn" >Actualizar</button></td>-->
                <td colspan="2"><button onclick="nuevo_representante_legal()" class="btn" >Nuevo</button></td>
            </tr>
        </table>
    </div>            
        <div id="dialogAlertaTelefono" >
            <div align="center">
                <table>
                    <tr>
                        <td colspan="2"><div id="AlertaTelefonoLayerMessage"></div></td>
                    </tr>
                    <tr>
                        <td align="right">Abonado</td>
                        <td align="left" id="txtAbonadoAlertaTelefono"></td>
                    </tr>
                    <tr>
                        <td align="right">Telefono</td>
                        <td align="left"><select id="cboNumeroAlertaTelefono"><option value="0">--Seleccione--</option></select></td>
                    </tr>
                    <tr>
                        <td align="right">Fecha</td>
                        <td align="left"><input class="cajaForm longCajaForm" type="text" id="txtFechaAlertaTelefono" /></td>
                    </tr>
                    <tr>
                        <td valign="top" align="right">Descripcion</td>
                        <td>
                            <div align="left">
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td>
                                            <textarea class="textareaForm" id="txtDescripcionAlertaTelefono" style="width:280px;height:150px;" ></textarea>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>        

																								<!-- Vic I -->
																								<div id="dialogSaldoInicialVigente" >
																									<div class="sltNroContrato"></div>
																									<br/>
																									<div class="grillaSaldoInicialVigente"></div>
																								</div>
																								<div id="dialogCuotas" >
																									<div class="sltNroContratoCuota"></div>
																									<br/>
																									<div class="grillaCuotas"></div>
																								</div>
																								<div id="dialogFiadores">
																									<div class="divNroContratoFiador"></div>
																									<br/>
																									<div class="grillaFiadores"></div>
																								</div>
																								<!-- Vic F -->

        <div id="beforeSendShadow" class="ui-widget-shadow" style="height:30px;position:fixed;top:32%;left:45%;display:none;z-index:1010;"></div>
        <div id="MsgBeforeSend" class="ui-widget-content ui-corner-all" style="height:30px;position:fixed;top:32%;left:45%;font-weight:bold;font-size:20px;vertical-align:middle;color:#333366;display:none;z-index:1020;" align="center"  ></div>
        <div id="dialogAlertaEspera" align="center" >
            <div align="center" >
                <table cellpadding="0" cellspacing="0" border="0" width="100px">
                    <tr>
                        <td align="center">
                            <div id="layerMessageAlerta" align="center"></div>
                        </td>
                    </tr>
                </table>
                <div ondblclick="_slide2(this,'alertaLayerRecientesAtencionCliente')" >
                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                        <tr>
                            <td style="width:25px; height:25px;">
                                <div class="backPanel iconPinBlueDown" onclick="_slide(this,'alertaLayerRecientesAtencionCliente')" ></div>
                            </td>
                            <td style=" border-bottom:1px solid #EADEC8;">
                                <div style="direction:ltr;">
                                    <a class="text-blue">Recientes</a>
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
                <div id="alertaLayerRecientesAtencionCliente" style="display:block;">
                    <table cellpadding="0" cellspacing="0" border="0" style="border:2px solid #6F9DD9;"></table>
                </div>
                <div ondblclick="_slide2(this,'alertaLayerHoyAtencionCliente')" >
                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                        <tr>
                            <td style="width:25px; height:25px;">
                                <div class="backPanel iconPinBlueDown" onclick="_slide(this,'alertaLayerHoyAtencionCliente')" ></div>
                            </td>
                            <td style=" border-bottom:1px solid #EADEC8;">
                                <div style="direction:ltr;">
                                    <a class="text-blue">Hoy</a>
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
                <div id="alertaLayerHoyAtencionCliente" style="display:block;">
                    <table cellpadding="0" cellspacing="0" border="0" style="border:2px solid #6F9DD9;"></table>
                </div>
                <div ondblclick="_slide2(this,'alertaLayerAyerAtencionCliente')" >
                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                        <tr>
                            <td style="width:25px; height:25px;">
                                <div class="backPanel iconPinBlueUp" onclick="_slide(this,'alertaLayerAyerAtencionCliente')" ></div>
                            </td>
                            <td style=" border-bottom:1px solid #EADEC8;">
                                <div style="direction:ltr;">
                                    <a class="text-blue">Ayer</a>
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
                <div id="alertaLayerAyerAtencionCliente" style="display:none;">
                    <table cellpadding="0" cellspacing="0" border="0" style="border:2px solid #6F9DD9;"></table>
                </div>
                <div ondblclick="_slide2(this,'alertaLayerAntiguasAtencionCliente')" >
                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                        <tr>
                            <td style="width:25px; height:25px;">
                                <div class="backPanel iconPinBlueUp" onclick="_slide(this,'alertaLayerAntiguasAtencionCliente')" ></div>
                            </td>
                            <td style=" border-bottom:1px solid #EADEC8;">
                                <div style="direction:ltr;">
                                    <a class="text-blue">Antiguas</a>
                                </div>
                            </td>
                            <td style=" border-bottom:1px solid #EADEC8;" >
                                <div style="direction:rtl;">
                                    <span class="text-gris"></span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="alertaLayerAntiguasAtencionCliente" style="display:none;">
                    <table cellpadding="0" cellspacing="0" border="0" style="border:2px solid #6F9DD9;"></table>
                </div>
                <!--<table id="tableContentLastAlertsToday" style="width:100%;">
                    <tr>
                        <td align="center"><div style="padding:2px 4px;width:210px;" class="ui-widget-header ui-corner-all">Cliente</div></td>
                        <td align="center"><div style="padding:2px 4px;width:120px;" class="ui-widget-header ui-corner-all">Fecha Alerta</div></td>
                        <td align="center"><div style="padding:2px 4px;width:210px;" class="ui-widget-header ui-corner-all">Descripcion</div></td>
                        <td><div style="width:40px;"></div></td>
                        <td><div style="width:40px;"></div></td>
                    </tr>
                </table>-->
            </div>
        </div>
        <div id="dialogEtiqueta" >
            <table>
                <tr>
                    <td>Nombre</td>
                    <td><input type="text" id="txtNombreEtiqueta" /></td>
                </tr>
                <tr>
                    <td>Descripcion</td>
                    <td><textarea id="txtDescripcionEtiqueta" ></textarea></td>
                </tr>
            </table>
        </div>
        <div id="dialogNotasHoy" >
            <div id="layerTableNotas" >
                <table style="display:block;">
                    <tr>
                        <td valign="top">
                            <table cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td>
                                        <button onclick="delete_notas()" class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-left "><span class="ui-button-text">Eliminar</span></button>
                                    </td>
                                    <td>
                                        <button onclick="marcar_como_no_leidas()" class="ui-button ui-widget ui-button-text-only ui-state-default "><span class="ui-button-text">Marcar como no le&iacute;da</span></button>
                                    </td>
                                    <td>
                                        <button onclick="marcar_notas_como_importante()" class="ui-button ui-widget ui-button-text-only ui-state-default ui-corner-right "><span class="ui-button-text">Marcar como importante</span></button>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td valign="top">
                            <button class=" ui-button ui-widget ui-button-text-only ui-state-default ui-corner-all " ><span class="ui-button-text" >Mas Acciones</span></button>
                        </td>
                    </tr>
                </table>
                <table style="width:100%;">
                    <tr>
                        <td class="noteDiv">
                            <div class="note">Notas:</div>
                            <!--<ul id="ulNotas">
                            </ul>-->
                            <table cellpadding="0" cellspacing="0" border="0">
                                <tr class="ui-state-default">
                                    <td align="center" style="border-bottom:1px solid #E0CFC2;padding:3px;width:3%;">&nbsp;</td>
                                    <td align="center" style="border-bottom:1px solid #E0CFC2;padding:3px;width:5%;">&nbsp;</td>
                                    <td align="center" style="border-bottom:1px solid #E0CFC2;padding:3px;width:5%;">&nbsp;</td>
                                    <td align="center" title="importante" style="border-bottom:1px solid #E0CFC2;padding:3px;width:15%;">Codigo</td>
                                    <td align="center" style="border-bottom:1px solid #E0CFC2;padding:3px;width:25%;">Cliente</td>
                                    <td align="center" style="border-bottom:1px solid #E0CFC2;padding:3px;width:46%;">Descripcion</td>
                                    <td align="center" style="border-bottom:1px solid #E0CFC2;padding:3px;width:5%;">&nbsp;</td>
                                </tr>
                            </table>
                            <div style="overflow-y:auto;height:245px;">
                                <table id="tableNotas" cellpadding="0" cellspacing="0" border="0"></table>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="layerDetalleNota" style="background-color:#FFF;padding:5px;border:1px solid #D8D8D8;display:none;" class="ui-corner-top">
                <a style="color:#0055CF;" onclick="$('#dialogNotasHoy #layerDetalleNota').hide();$('#dialogNotasHoy #layerTableNotas').fadeIn();$(this).next().next().hide();" href="#">Volver a Notas</a>
                <a style="color:#0055CF;" onclick="$(this).next().fadeIn();" href="#">Mostrar Detalles</a>
                <div align="center" style="display:none;">
                    <table>
                        <tr>
                            <td style="color:#4D82CF;" align="right">Codigo Cliente</td>
                            <td><label id="lbNotaCodigoCliente"></label></td>
                        </tr>
                        <tr>
                            <td style="color:#4D82CF;" align="right">Nombre Cliente</td>
                            <td><label id="ldNotaNombreCliente"></label></td>
                        </tr>
                        <tr>
                            <td style="color:#4D82CF;" align="right">Fecha Creacion</td>
                            <td><label id="ldNotaFechaCreacion"></label></td>
                        </tr>
                        <tr>
                            <td style="color:#4D82CF;" align="right">Usuario Creacion</td>
                            <td><label id="ldNotaUsuarioCreacion"></label></td>
                        </tr>
                    </table>
                </div>
                <div style="border-top:1px solid #0055CF;"></div>
                <div align="center">
                    <table>
                        <tr>
                            <td><div id="layerNotaDescripcion" style="background-color:#FFF;"></div></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div id="dialogNotas">
            <div align="center">
                <table>
                    <tr>
                        <td colspan="2" align="center"><div id="NotasLayerMessage"></div></td>
                    </tr>
                    <tr>
                        <td align="right">Abonado</td>
                        <td id="txtAbonadoNota"></td>
                    </tr>
                    <tr>
                        <td align="right">Fecha</td>
                        <td><input class="cajaForm longCajaForm" type="text" id="txtFechaNota" /></td>
                    </tr>
                    <tr>
                        <td valign="top" align="right">Nota</td>
                        <td>
                            <div>
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td>
                                            <textarea class="textareaForm" id="txtDescripcionNota" rows="10" cols="40"></textarea>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <!--<div id="dialogConsulta" align="center">
            <div align="center" >
                <table cellpadding="0" cellspacing="0" border="0" style="width:99%;">
                    <tr>
                        <td>
                            <div style="margin-left:50px;">
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tr id="table_tab_consulta">
                                        <td><div id="tabConsultaEnviar" onclick="_activeTabLayer('table_tab_consulta','tabConsulta',this,'content_table_consulta','layerTabConsulta','layerTabConsultaEnviar')" class="itemTabActive border-radius-top"><div class="text-white">Enviar Consulta</div></div></td>
                                        <td><div id="tabConsultaCreada" onclick="_activeTabLayer('table_tab_consulta','tabConsulta',this,'content_table_consulta','layerTabConsulta','layerTabConsultaCreada')" class="itemTab border-radius-top"><div class="AitemTab">Consultas Creadas</div></div></td>
                                        <td><div id="tabConsultaAceptada" onclick="_activeTabLayer('table_tab_consulta','tabConsulta',this,'content_table_consulta','layerTabConsulta','layerTabConsultaAceptada')" class="itemTab border-radius-top"><div class="AitemTab">Consultas Aceptadas</div></div></td>
                                    </tr>
                                </table>
                                <table></table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="lineTab"></td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" border="0" style="width:99%;">
                    <tr>
                        <td id="content_table_consulta">
                            <div id="layerTabConsultaEnviar" class="ui-widget-content" style="display:block;" align="left">
                                <table>
                                    <tr>
                                        <td colspan="2" align="center">
                                            <button class="ui-button ui-widget ui-state-default ui-corner-all" style="padding:5px 12px;" ><span class="ui-button-text"><span>Enviar</span></span></button>
                                            <button class="ui-button ui-widget ui-state-default ui-corner-all" style="padding:5px 12px;" ><span class="ui-button-text"><span>Cancelar</span></span></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">Para</td>
                                        <td><select id="cbParaSupervisorConsulta" class="combo"></select></td>
                                    </tr>
                                    <tr>
                                        <td align="right">Asunto</td>
                                        <td><input class="cajaForm longCajaForm" id="txtAsuntoConsulta" type="text" /></td>
                                    </tr>
                                    <tr>
                                        <td align="right" valign="top">Consulta</td>
                                        <td>
                                            <textarea class="textareaForm" id="txtDescripcionConsulta" style="width:650px;height:140px;"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="center">
                                            <button class="ui-button ui-widget ui-state-default ui-corner-all" style="padding:5px 12px;" ><span class="ui-button-text"><span>Enviar</span></span></button>
                                            <button class="ui-button ui-widget ui-state-default ui-corner-all" style="padding:5px 12px;" ><span class="ui-button-text"><span>Cancelar</span></span></button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div id="layerTabConsultaCreada" class="ui-widget-content" style="display:none;" align="center">
                            </div>
                            <div id="layerTabConsultaAceptada" class="ui-widget-content" style="display:none;" align="center"></div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>-->
        <!--<div class="barAlert ui-widget-content ui-corner-top" onclick="show_box_model_alertas_sin_atender()"></div>-->
        <div id="DialogEditTelefonoCartera">
            <table>
                <tr>
                    <td align="left">Numero de Telefono</td>
                    <td><input type="hidden" id="hdIdTelefonoCartera" /></td>
                    <td align="left"><input type="text" class="cajaForm" maxlength="15" id="txtNumeroTelefonoAtencionCliente" /></td>
                </tr>
            </table>
        </div>
        <!--<div id="DialogAddTelefonoCartera" >
                <table>
                    <tr>
                        <td align="left">Numero</td>
                    <td align="left"><input type="text" class="cajaForm" style="width:125px;" id="txtNumero2TelefonoAtencionCliente" /><input type="hidden" id="hdIdAddTelefonoCartera" /></td>
                    <td align="left">Anexo</td>
                    <td align="left"><input type="text" class="cajaForm" style="width:125px;" id="txtAnexoTelefonoAtencion" /></td>
                </tr>
                <tr>	
                    <td align="left">Tipo</td>
                    <td align="left"><select id="cbTipoTelefonoAtencion" class="combo"><option value="0">--Seleccione--</option></select></td>
                    <td align="left">Referencia</td>
                    <td align="left"><select id="cbReferenciaTelefonoAtencion" class="combo"><option value="0">--Seleccione--</option></select></td>
                </tr>
                <tr>
                    <td align="left">Linea</td>
                    <td align="left"><select id="cbLineaTelefonoAtencion" class="combo"><option value="0">--Seleccione--</option></select></td>
                    <td align="left">Origen</td>
                    <td align="left"><select id="cbOrigenTelefonoAtencion" class="combo"><option value="0">--Seleccione--</option></select></td>
                </tr>
                <tr>
                    <td align="left" valign="top">Observacion</td>
                    <td align="left" colspan="3">
                        <textarea id="txtObservacionTelefonoAtencion" class="textareaForm" style="width:330px;height:80px;"></textarea>
                    </td>
                </tr>
            </table>
        </div>-->
        <div id="DialogAddDireccionCartera" >
            <table>
                <tr>
                    <td colspan="4"><input type="hidden" id="HdIdDireccionAtencionCliente" /></td>
                </tr>
                <tr>
                    <td align="left">Direccion</td>
                    <td align="left" colspan="3"><input onkeyup=" if( event.keyCode == 13 ){ $(this).blur();$('#txtDireccionReferenciaAtencionCliente').focus(); } " type="text" class="cajaForm" style="width:330px;" id="txtDireccionAtencionCliente" /></td>
                </tr>
                <tr>     
                    <td align="left">Direcc. Ref.</td>
                    <td align="left" colspan="3"><input onkeyup=" if( event.keyCode == 13 ){ $(this).blur();$('#cbOrigenDireccionAtencionCliente').focus(); } " type="text" class="cajaForm" style="width:330px;" id="txtDireccionReferenciaAtencionCliente" /></td>
                </tr>
                <tr>    
                    <td align="left">Origen</td>
                    <td align="left"><select onkeyup=" if( event.keyCode == 13 ){ $(this).blur();$('#cbReferenciaDireccionAtencionCliente').focus(); } " id="cbOrigenDireccionAtencionCliente" class="combo"><option value="0">--Seleccione--</option></select></td>
                    <td align="left">Referencia</td>
                    <td align="left"><select onkeyup=" if( event.keyCode == 13 ){ $(this).blur();$('#txtUbigeoAtencionCliente').focus(); } " id="cbReferenciaDireccionAtencionCliente" class="combo"><option value="0">--Seleccione--</option></select></td>
                </tr>
                <tr>     
                    <td align="left">Ubigeo</td>
                    <td><input onkeyup=" if( event.keyCode == 13 ){ $(this).blur();$('#txtDepartamentoAtencionCliente').focus(); } " type="text" class="cajaForm" style="width:125px;" id="txtUbigeoAtencionCliente" /></td>
                    <td>Departamento</td>
                    <td><select onkeyup=" if( event.keyCode == 13 ){ $(this).blur();$('#txtProvinciaAtencionCliente').focus(); } " onblur=" $('#txtProvinciaAtencionCliente,#txtDistritoAtencionCliente').val('');listar_provincia( this.value, 'txtProvinciaAtencionCliente' ) " class="combo" style="width:125px;" id="txtDepartamentoAtencionCliente" ><option value="">--Seleccione--</option></select></td>
                </tr>
                <tr>	            
                    <td align="left">Provincia</td>
                    <td align="left"><select onkeyup=" if( event.keyCode == 13 ){ $(this).blur();$('#txtDistritoAtencionCliente').focus(); } " onblur=" $('#txtDistritoAtencionCliente').val('');listar_distrito( $('#txtDepartamentoAtencionCliente').val(), this.value, 'txtDistritoAtencionCliente' ) " class="combo" style="width:125px;" id="txtProvinciaAtencionCliente" ><option value="">--Seleccione--</option></select></td>
                    <td align="left">Distrito</td>
                    <td align="left"><select onkeyup=" if( event.keyCode == 13 ){ $(this).blur();$('#txtObservacionDireccionAtencionCliente').focus(); } " class="combo" style="width:125px;" id="txtDistritoAtencionCliente" ><option value="">--Seleccione--</option></select></td>
                </tr>
                <tr>
                    <td align="left" valign="top">Observacion</td>
                    <td align="left" colspan="3">
                        <textarea id="txtObservacionDireccionAtencionCliente" class="textareaForm" style="width:330px;height:80px;"></textarea>
                    </td>
                </tr>
            </table>
        </div>
        <div id="DialogBuscarTelefono" >
            <table>
                <tr>
                    <td>
                        <div>
                            <table>
                                <tr>
                                    <td align="left">Cliente</td>
                                    <td align="left"><input type="text" class="cajaForm" style="width:200px;" id="txtSearchTelefonoCliente" /></td>
                                    <td align="left"><button onclick="load_data_searchTelefonoCliente()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-search"></span></button></td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div id="layerTableResultSearchTelefonoCliente" >
                            <table cellspacing="0" cellpadding="0" border="0" class="ui-corner-top" >
                                <tr class="ui-state-default" >
                                    <th style="width:25px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" ></th>
                                    <th style="width:80px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >Codigo</th>
                                    <th style="width:200px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >Cliente</th>
                                    <th style="width:86px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >Servicio</th>
                                    <th style="width:86px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >Numero</th>
                                    <th style="width:60px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >Anexo</th>
                                    <th style="width:80px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >Origen</th>
                                    <th style="width:100px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >Tipo Telefono</th>
                                    <th style="width:100px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >Tipo Referencia</th>
                                    <th style="width:60px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" >Carga</th>
                                    <th style="width:25px;padding:3px 0;border-left:1px solid #E0CFC2;border-bottom:1px solid #E0CFC2;border-right:1px solid #E0CFC2;border-top:1px solid #E0CFC2;" ></th>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div id="DialogNuevoCorreo">
            <table>
                <tr>
                    <td>Correo</td>
                    <td><input style="width:150px;" maxlength="100" type="text" class="cajaForm" id="txtAtencionClienteCorreo" /></td>
                </tr>
                <tr>
                    <td>Observacion</td>
                    <td><textarea style="width:390px;height:50px;" class="textareaForm" id="txtObservacionAtencionClienteCorreo"></textarea></td>
                </tr>
            </table>
        </div>
        <!--MANTTELF-->
        <div id="DialogGestionTelefonos">
            <input type="hidden" id="nombre_tip_telf">
            <input type="hidden" id="nombre_ref_telf">
            <input type="hidden" id="nombre_lin_telf">
            <input type="hidden" id="nombre_ori_telf">
            <input type="hidden" id="number_if_exist">
            <!-- CAMBIO 20-06-2016 -->
            <input type="hidden" id="hddepartament_opcion">
            <input type="hidden" id="hdprovincia_opcion">
            <input type="hidden" id="hddistrito_opcion">
            <!-- CAMBIO 20-06-2016 -->
            <table id="table_gestion_telefono"></table>
            <div id="pager_table_gestion_telefono"></div>
        </div>
        <!--MANTTELF-->
        <!-- CAMBIO 20-06-2016 -->
        <div id="DialogGestionDireccion_opcion">
            <table id="table_gestion_direccion_opcion"></table>
            <div id="pager_table_gestion_direccion_opcion"></div>
        </div>
        <!-- CAMBIO 20-06-2016 -->
        <div id="DialogNuevoHorarioAtencion">
            <table>
                <tr>
                    <td>Horario Atencion</td>
                    <td><input style="width:150px;" maxlength="8" readonly="readonly" type="text" class="cajaForm" id="txtAtencionClienteHorarioAtencion" /></td>
                </tr>
                <tr>
                    <td>Observacion</td>
                    <td><textarea style="width:390px;height:50px;" class="textareaForm" id="txtObservacionAtencionClienteHorarioAtencion" ></textarea></td>
                </tr>
            </table>
        </div>
        <div id="DataReadFileAndText">
            <div id="DataSpeechArgument"></div>
        </div>
        <div id="DialogActualizarAnexo" style="display:none;z-index:1000;position:absolute;border:2px solid #FFF;width:200px;" class="ui-corner-all ui-widget-content">
            <div style="padding:5px 3px;background-color:#4D6185;" class="ui-corner-top"><table style="width:100%;"><tr><td><label style="color:#FFF;font-weight:bold;width:150px;">Ingresar Anexo</label></td><td><img style="float:right;background-color:#4D6185;" onclick="$('#DialogActualizarAnexo').hide()" src="../img/action_stop.gif" /></td></tr></table></div>
            <table>
                <tr>
                    <td><strong>Anexo</strong></td>
                </tr>
                <tr>
                    <td><input type="text" style="width:190px;" class="cajaForm longCajaForm" id="txtAnexoTeleoperador" maxlength="6" /></td>
                </tr>
                <tr>
                    <td><button onclick="$('#hdAnexoOp').val($.trim( $('#DialogActualizarAnexo #txtAnexoTeleoperador').val() ));$('#DialogActualizarAnexo').hide(); $('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo('Anexo Grabado Correctamente','350px')); AtencionClienteDAO.setTimeOut_hide_message();" class="btn" >Actualizar</button></td>
                </tr>
            </table>
        </div>
        <div id="DialogActualizarAnexoNeotel" style="display:none;z-index:1001;position:absolute;border:2px solid #FFF;width:200px;" class="ui-corner-all ui-widget-content">
            <div style="padding:5px 3px;background-color:#4D6185;" class="ui-corner-top"><table style="width:100%;"><tr><td><label style="color:#FFF;font-weight:bold;width:150px;">Ingresar Usuario</label></td><td><img style="float:right;background-color:#4D6185;" onclick="$('#DialogActualizarAnexoNeotel').hide()" src="../img/action_stop.gif" /></td></tr></table></div>
            <table>
                <tr><!--jmore120813-->
                    <td><strong>Usuario Neotel</strong></td>
                </tr>
                <tr>
                    <td><input type="text" style="width:190px;" class="cajaForm longCajaForm" id="txtUsuarioNeotelTeleoperador" maxlength="6" /></td>                
                </tr>
                <tr>
                    <td><button onclick="$('#hdUsuarioNeotelTeleoperador').val($('#DialogActualizarAnexoNeotel #txtUsuarioNeotelTeleoperador').val());$('#txtUsuarioN').val($('#DialogActualizarAnexoNeotel #txtUsuarioNeotelTeleoperador').val());$('#'+AtencionClienteDAO.idLayerMessage).html(templates.MsgInfo('Se grabo Usuario','400px'));AtencionClienteDAO.setTimeOut_hide_message();$('#DialogActualizarAnexoNeotel').slideUp('slow');neotelDAO.ponerCampania('10');" class="btn" >Actualizar</button></td>
                </tr>
            </table>
        </div>                
        <!--<div class="MsgAlert ui-corner-top ui-widget-content" id="alertaMain" style="display:none;">
                <table style="width:100%;">
                <tr>
                    <td>
                    	<div>
                        	<div class="ui-state-error ui-corner-all" style="padding: 0 0.7em;" align="center" >
                                <p>
                                    <span class="ui-icon ui-icon-alert" style="float: left; margin-right: 0.3em;"></span>
                                    <Strong>Nuevo Alerta</Strong>
                                </p>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div>
                            <table border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <div>
                                            <table id="tableContentAlert">
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
        <!--<div id="layerMenuMainDesigner" onmouseover="$(this).slideUp();$('#layerMenuMainContentDesigner').slideDown();" style="position:fixed;width:100px;right:0px;bottom:0px;display:block;" >
            <div class="ui-state-active ui-corner-top" style="padding:3px 0;" align="center">DISE&Ntilde;O</div>
        </div>
        <div id="layerMenuMainContentDesigner"  style="position:fixed;right:0px;bottom:0px;padding:3px 0;display:none;" class="ui-state-active ui-corner-bottom"  align="left">
            <button onclick="$(this).parent().slideUp();$('#layerMenuMainDesigner').slideDown();" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-close"></span></button>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td onclick="$('table.tableTab').find('tr:lt(2)').toggle();"><div style="height:20px;width:100%;" class="ui-state-default ui-corner-all"></div></td>
                </tr>
                <tr>
                    <td>
                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td onclick="$('#showhide').find('a').trigger('click');"><div style="height:80px;width:20px;" class="ui-state-default ui-corner-all"></div></td>
                                <td>
                                    <div style="height:20px;width:80px;" class="ui-state-default ui-corner-all"></div>
                                    <div style="height:60px;width:80px;" class="ui-state-default ui-corner-all"></div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>-->
        <div id="layerTabAC2Visita" class="ui-widget-content" style="display:none;padding:5px;position:fixed;left:auto;bottom:0px;" align="center" >
            <table>
                <tr>
                    <td>
                        <h3 class="ui-widget-header ui-corner-all" style="padding:5px;width:70px;margin:0px;float:left;">Visita</h3>
                        <span onclick="$('#layerTabAC2Visita').slideToggle();" style="float:right;" class="ui-icon ui-icon-close"></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="width:900px;overflow-x:auto;">
                            <table id="table_visita"></table>
                            <div id="pager_table_visita"></div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <!--<div id="layerTabAC2Historico" class="ui-widget-content" style="display:none;padding:5px;position:fixed;left:auto;bottom:0px;" align="center">
            <table>
                <tr>
                        <td><h3 class="ui-widget-header ui-corner-all" style="padding:5px;width:70px;margin:0px;">Historico</h3></td>
                </tr>
                <tr>
                    <td>
                        <div>
                            <table id="table_historico"></table>
                            <div id="pager_table_historico"></div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>-->
        <div id="layerTabAC2CentroPago" class="ui-widget-content" style="display:none;padding:5px;left:auto;bottom:0px;position:fixed;" align="center">
            <table>
                <tr>
                    <tr>
                        <td>
                                <h3 class="ui-widget-header ui-corner-all" style="padding:5px;width:120px;margin:0px;float:left;">Centro de Pagos</h3>
                                <span onclick="$('#layerTabAC2CentroPago').slideToggle();" style="float:right;" class="ui-icon ui-icon-close"></span>
                        </td>
                    </tr>
                    <td>
                        <div>
                            <table id="table_centro_pago"></table>
                            <div id="pager_table_centro_pago"></div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
		<div id="DialogArchivosYCarpetas" style="display:none;">
			<div class="ui-widget-header ui-corner-all" style="padding:2px;margin:2px;">
				<table>
					<tr>
						<td><button id="update_directory_btn" onclick="refresh_directory()" title="ACTUALIZAR" alt="ACTUALIZAR">Actualizar</button></td>
					</tr>
				</table>
			</div>
			<div>
				<input type="hidden" id="router_directory" val="/" />
				<div id="lb_router_directory" class="ui-state-highlight ui-corner-all" style="font-weight:bold;font-size:15px;text-align:left;padding:2px;margin:2px;"  id="lb_router_directory">/</div>
				<div align="left" id="layer_back_directory" onclick="back_directory()" style="display:none;" ><span class="ui-icon ui-icon-arrowreturnthick-1-n"></span></div>
			</div>
			<div style="margin:2px;">
				<ul style="margin:0;padding:0;list-style:none;" class="ui-widget ui-helper-clearfix" id="table_directory" >
				</ul>
			</div>
		</div>
        <div id="lbMessageGlobalGest" style="font-size:22px;font-weight:bold;position:fixed;right:0px;bottom:0px;display:none;padding:3px;" class="ui-state-error ui-corner-top" ></div>
        <!--<div style="position:fixed;top:0px;right:45%;" class="ui-widget-content ui-corner-bottom">
        <table>
                <tr>
                <td>Automatico</td>
                <td><input type="radio" value="modo_marcacion_automatica" checked="checked"  name="modo_marcacion_telefono" /></td>
                <td>Manual</td>
                <td><input type="radio" value="modo_marcacion_manual" name="modo_marcacion_telefono" /></td>
            </tr>
        </table>
        </div>-->
        </div>
        <div id="cargando" style="position: absolute; left: 0; top: 0; width: 100%; height: 100%; background: white url(../img/cargando_sin_fondo.gif) no-repeat center center"></div>

    </body>
    <script type="text/javascript">
        // $('#switcher').themeswitcher({initialText : 'Lista de Temas',buttonPreText:'Tema: '});

        // FIDELIZACION
        $('#slider').anythingSlider({
            autoPlay          : false,
            startStopped      : false,
            startText         : "Start",
            stopText          : "Stop",
            buildNavigation   : true,
            resizeContents    : true
        });
        $('.anythingControls .start-stop').css({'display':'none'});
        $('.anythingControls .thumbNav li a span').text('');
        
        $('#slider_encuestados').anythingSlider({
            autoPlay          : false,
            startStopped      : false,
            startText         : "Start",
            stopText          : "Stop",
            buildNavigation   : true,
            resizeContents    : true
        });
        $('.anythingControls .start-stop').css({'display':'none'});
        $('.anythingControls .thumbNav li a span').text('');

        $('#slider_resueltos').anythingSlider({
            autoPlay          : false,
            startStopped      : false,
            startText         : "Start",
            stopText          : "Stop",
            buildNavigation   : true,
            resizeContents    : true
        });
        $("#slider_resueltos").parent().parent().find(".anythingControls ul a").css({'width':334})
        $('.anythingControls .start-stop').css({'display':'none'});
        $('.anythingControls .thumbNav li a span').text('');



        // FIDELIZACION

    </script>


</html>
