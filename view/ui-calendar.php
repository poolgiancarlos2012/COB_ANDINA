<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Calendar</title>
        <link type="text/css" rel="stylesheet" href="../includes/jqgrid6/css/ui.jqgrid.css" />
        <link type="text/css" rel="stylesheet" href="../includes/jquery-ui-1.8/themes/humanity/jquery-ui.css" />

        <link type="text/css" rel="stylesheet" href="../css/css-cobrast.css" />

        <script type="text/javascript" src="../js/includes/jquery-1.4.2.js" ></script>

        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.core.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.widget.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.mouse.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.draggable.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.selectable.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.datepicker.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.dialog.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.button.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.slider.min.js" ></script>
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

        <script type="text/javascript" src="../js/includes/jquery-ui-timepicker.js" ></script>

        <script type="text/javascript" src="../js/js-cobrast.js" ></script>
        <script type="text/javascript" src="../js/validacion.js" ></script>
        <script type="text/javascript" src="../js/templates.js"  ></script>
        <script type="text/javascript" src="../js/CalendarJQGRID.js"  ></script>
        <script type="text/javascript" src="../js/CalendarDAO.js"  ></script>
        <script type="text/javascript" src="../js/js-calendar.js"  ></script>
        <style type="text/css">
            .ui-selecting {  }
            .ui-selected { background-color: #F9F9D6; }
            .ui-selectee { }
        </style>
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
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="vAlignBottom tabsLine">
                        <div id="layerMessage" align="center"  style="display:block;"></div>
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
                                    <div style="margin-left:20px; margin-bottom:2px;"><a class="text-blue" onClick="_display_panel('panelAtencionCalendar')">Calendar</a></div>
                                    <div style="margin-left:20px; margin-bottom:2px;"><a class="text-blue" onClick="_display_panel('panelAtencionCalendarWeek')">Week Calendar</a></div>
                                    <div style="margin-left:20px; margin-bottom:2px;"><a class="text-blue" id="ApinPanelAtencionEventoTarea" onClick="_display_panel('panelAtencionEventosTareas')">Eventos y Tareas</a></div>
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
                            <div id="panelAtencionCalendar" style="display:block;" align="center" class="ui-widget-content">
                                <table border="0" cellpadding="0" cellspacing="0" >
                                    <tr>
                                        <td>
                                            <div class="lineTab ui-widget-header ui-corner-all" style="padding:2px 5px;height:30px;margin:2px;" >
                                                <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                    <tr>
                                                        <td align="left">
                                                            <div>
                                                                <table>
                                                                    <tr>
                                                                        <td><button onclick="backCalendar()" class="ui-state-default ui-corner-all" style="padding:3px 1px;"><span class="ui-icon ui-icon-circle-triangle-w"></span></button></td>
                                                                        <td><button onclick="nextCalendar()" class="ui-state-default ui-corner-all" style="padding:3px 1px;"><span class="ui-icon ui-icon-circle-triangle-e"></span></button></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                        <td align="left">
                                                            <div id="HeaderGestionPanelCalendar" style="font-size:18px;color:#FFF;"></div>
                                                        </td>
                                                    </tr>
                                                </table>

                                            </div>
                                            <div id="GestionPanelCalendar" ></div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div id="panelAtencionEventosTareas" style="display:none;" align="center" class="ui-widget-content" >
                                <div ondblclick="_slide2(this,'content_table_eventos')">
                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                        <tr>
                                            <td style="width:25px; height:25px;">
                                                <div class="backPanel iconPinBlueDown" onclick="_slide(this,'content_table_eventos')"></div>
                                            </td>
                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                <div style="direction:ltr;">
                                                    <a class="text-blue">Eventos</a>
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
                                <div id="content_table_eventos" style="display:block; padding:10px 0;" align="center">
                                    <table>
                                        <tr>
                                            <td>
                                                <div>
                                                    <table id="table_eventos"></table>
                                                    <div id="pager_table_eventos"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div ondblclick="_slide2(this,'content_table_tarea')">
                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                        <tr>
                                            <td style="width:25px; height:25px;">
                                                <div class="backPanel iconPinBlueDown" onclick="_slide(this,'content_table_tarea')"></div>
                                            </td>
                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                <div style="direction:ltr;">
                                                    <a class="text-blue">Tarea</a>
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
                                <div id="content_table_tarea" style="display:block; padding:10px 0;" align="center">
                                    <table>
                                        <tr>
                                            <td>
                                                <div>
                                                    <table id="table_tarea" ></table>
                                                    <div id="pager_table_tarea" ></div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div id="panelAtencionCalendarWeek" style="display:none;" align="center" class="ui-widget-content">
                                <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                    <tr>
                                        <td align="left">
                                            <div class="lineTab ui-corner-all ui-widget-header" style="padding:2px 5px;height:30px;margin:2px;" >
                                                <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                                    <tr>
                                                        <td align="left">
                                                            <div>
                                                                <table>
                                                                    <tr>
                                                                        <td><button onclick="BackWeek()" class="ui-state-default ui-corner-all" style="padding:3px 1px;"><span class="ui-icon ui-icon-circle-triangle-w"></span></button></td>
                                                                        <td><button onclick="NextWeek()" class="ui-state-default ui-corner-all" style="padding:3px 1px;"><span class="ui-icon ui-icon-circle-triangle-e"></span></button></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                        <td align="left">
                                                            <div id="HeaderGestionPanelCalendar" style="font-size:18px;color:#FFF;" align="center"></div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div id="GestionPanelCalendarWeek" align="center" ></div>
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
        <div id="FormCalendar" style="display:none" class="FormCalendar ui-corner-all">
            <div class="HeaderFormCalendar" id="HeaderFormCalendar">
                <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                    <tr>
                        <td align="left" id="Title" class="text-white"></td>
                        <td align="right"><div class="CloseFormCalendar" onclick="CloseFormCalendar()"></div></td>
                    </tr>
                </table>
            </div>
            <div style="background-color:#FFF;">
                <table>
                    <tr>
                        <td><label onclick="DisplaySubFormCalendar('SubFormCalendarEvent')" style="color:#D8D6D6;font-weight:bold;">Evento</label></td>
                        <td>|</td>
                        <td><label onclick="DisplaySubFormCalendar('SubFormCalendarWork')"  style="color:#D8D6D6;font-weight:bold;">Tarea</label></td>
                        <td><input type="hidden" id="HdFecha" /><input type="hidden" id="HdIdEvento" /><input type="hidden" id="HdIdTarea" /></td>
                    </tr>
                </table>
                <table id="SubFormCalendarEvent" style="display:block;width:100%;">
                    <tr>
                        <td colspan="2"><div class="text-black" style="font-size:14px">Evento</div></td>
                    </tr>
                    <tr>
                        <td align="right"><label class="text-black" >Tiempo:</label></td>
                        <td align="left"><input type="text" id="txtTiempoEvento" readonly="readonly" class="cajaForm" style="width:70px;" /></td>
                    </tr>
                    <tr>
                        <td align="right"><label class="text-black">Evento:</label></td>
                        <td align="left"><input type="text" class="cajaForm" style="width:280px;" id="txtEvento" maxlength="25" /></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button class="btn" onclick="guardar_evento()">Crear Evento</button>
                        </td>
                    </tr>
                </table>
                <table id="SubFormCalendarWork" style="display:none;width:100%;">
                    <tr>
                        <td colspan="2"><div class="text-black" style="font-size:14px">Tarea</div></td>
                    </tr>
                    <tr>
                        <td align="right"><label class="text-black">Tarea:</label></td>
                        <td align="left"><input type="text" class="cajaForm" id="txtTarea" maxlength="25" /></td>
                    </tr>
                    <tr>
                        <td align="right"><label class="text-black">Tiempo:</label></td>
                        <td align="left"><input type="text" readonly="readonly" id="txtTiempoTarea" class="cajaForm" style="width:70px;"/></td>
                    </tr>
                    <tr>
                        <td align="right" valign="top"><label class="text-black">Nota:</label></td>
                        <td align="left"><textarea class="textareaForm" style="width:286px;height:100px;" id="txtNota"></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button class="btn" onclick="guardar_tarea()">Crear Tarea</button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- Formulario de semana -->
        <div id="FormCalendarWeek" style="display:none" class="FormCalendar ui-corner-all">
            <div class="HeaderFormCalendar" id="HeaderFormCalendarWeek">
                <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                    <tr>
                        <td align="left" id="Title" class="text-white"></td>
                        <td align="right"><div class="CloseFormCalendar" onclick="CloseFormCalendarWeek()"></div></td>
                    </tr>
                </table>
            </div>
            <div style="background-color:#FFF;">
                <table>
                    <tr>
                        <td><label onclick="DisplaySubFormCalendarWeek('SubFormCalendarWeekEvent')">Evento</label> | </td>
                        <td><label onclick="DisplaySubFormCalendarWeek('SubFormCalendarWeekWork')">Tarea</label></td>
                        <td><input type="hidden" id="HdFecha_w" /><input type="hidden" id="HdIdPanel_w" /></td>
                    </tr>
                </table>
                <table id="SubFormCalendarWeekEvent" style="display:block;width:100%;">
                    <tr>
                        <td colspan="2"><div class="text-black" style="font-size:14px">Evento</div></td>
                    </tr>
                    <tr>
                        <td align="right"><label class="text-black" >Tiempo:</label></td>
                        <td align="left"><input type="text" id="txtTiempoEvento_w" readonly="readonly" class="cajaForm" style="width:70px;" /></td>
                    </tr>
                    <tr>
                        <td align="right"><label class="text-black">Evento:</label></td>
                        <td align="left"><input type="text" class="cajaForm" style="width:280px;" id="txtEvento_w" maxlength="25" /></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button class="btn" onclick="guardar_evento_w()">Crear Evento</button>
                        </td>
                    </tr>
                </table>
                <table id="SubFormCalendarWeekWork" style="display:none;width:100%;">
                    <tr>
                        <td colspan="2"><div class="text-black" style="font-size:14px">Tarea</div></td>
                    </tr>
                    <tr>
                        <td align="right"><label class="text-black">Tarea:</label></td>
                        <td align="left"><input type="text" class="cajaForm" id="txtTarea_w" maxlength="25" /></td>
                    </tr>
                    <tr>
                        <td align="right"><label class="text-black">Tiempo:</label></td>
                        <td align="left"><input type="text" readonly="readonly" id="txtTiempoTarea_w" class="cajaForm" style="width:70px;"/></td>
                    </tr>
                    <tr>
                        <td align="right" valign="top"><label class="text-black">Nota:</label></td>
                        <td align="left"><textarea class="textareaForm" style="width:286px;height:100px;" id="txtNota_w"></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button class="btn" onclick="guardar_tarea_w()">Crear Tarea</button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <!--  -->
        <div id="FormCalendar2" style="display:none" class="FormCalendar ui-corner-all">
            <div class="HeaderFormCalendar" id="HeaderFormCalendar">
                <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                    <tr>
                        <td align="left" id="Title" class="text-white"></td>
                        <td align="right"><div class="CloseFormCalendar" onclick="CloseFormCalendar()"></div></td>
                    </tr>
                </table>
            </div>
            <div style="background-color:#FFF;">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td>
                            <input type="hidden" id="HdFechaInicio" />
                            <input type="hidden" id="HdFechaFin" />
                        </td>
                    </tr>
                </table>
                <table id="SubFormCalendarEvent" style="display:block;width:100%;">
                    <tr>
                        <td colspan="2"><div class="text-black" style="font-size:14px" >Evento</div></td>
                    </tr>
                    <tr>
                        <td align="right"><label class="text-black" >Tiempo:</label></td>
                        <td align="left"><input type="text" id="txtTiempoEvento" class="cajaForm" style="width:70px;" maxlength="25" /></td>
                    </tr>
                    <tr>
                        <td align="right"><label class="text-black">Evento:</label></td>
                        <td align="left"><input type="text" class="cajaForm" style="width:280px;" id="txtEvento" /></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button class="btn" onclick="guardar_evento_rango_fecha()">Crear Evento</button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>