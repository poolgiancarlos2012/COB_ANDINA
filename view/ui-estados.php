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
        <title>Estados y Prioridad</title>
        <link type="text/css" rel="stylesheet" href="../includes/jqgrid6/css/ui.jqgrid.css" />
        <link type="text/css" rel="stylesheet" href="../includes/jquery-ui-1.8/themes/humanity/jquery-ui.css" />

        <link type="text/css" rel="stylesheet" href="../css/css-cobrast.css" />
        <script type="text/javascript" src="../js/includes/jquery-1.4.2.js" ></script>

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

        <script type="text/javascript" src="../js/js-cobrast.js" ></script>
        <script type="text/javascript" src="../js/templates.js" ></script>
        <script type="text/javascript" src="../js/validacion.js" ></script>
        <script type="text/javascript" src="../js/EstadosDAO.js" ></script>
        <script type="text/javascript" src="../js/EstadosJQGRID.js" ></script>
        <script type="text/javascript" src="../js/js-estados.js" ></script>
        <style type="text/css">
            *html .IE6LongTextUsuario {
                width:145px;		
            }
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
                                <table border="0" style="margin-left:20px;" >
                                    <tr>
                                        <td><a id="pinPanelNuevoEstadoPrioridad" class="text-blue" style="cursor:pointer;" onClick="_display_panel('panelNuevoEstadoPrioridad')">Nuevo</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" style="cursor:pointer;" onClick="_display_panel('panelRegistrosEstadosPrioridad')">Estados y Prioridad</a></td>
                                    </tr>
                                </table>
                            </div>
                            <div class=" backPanel headerPanel">
                                <div class="backPanel iconPinUp" onclick="_slideBarLayer(this,'panelCrear')">Crear</div>
                            </div>
                            <div id="panelCrear" class="backPanel contentBarLayer" style="display:block;padding:3px 0;" align="center" >
                                <table>
                                    <tr>
                                        <td>
                                            <select onchange="_showCrear(this.value)" class="combo">
                                                <option value="crear">Crear..</option>
                                                <option value="usuario">Usuario</option>
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
                            <div id="iconSlider" class="slider icon sliderIconUp"></div>
                        </a>
                    </td>
                    <td width="100%" valign="top">	
                        <div id="cobrastHOME" style="background-color:#FFFFFF; width:100%; height:100%; ">
                            <input type="hidden" id="hdCodUsuario" name="hdCodUsuario" value="<?= $_SESSION['cobrast']['idusuario'] ?>" />
                            <input type="hidden" id="hdNomUsuario" name="hdNomUsuario" value="<?= $_SESSION['cobrast']['usuario'] ?>" />
                            <input type="hidden" id="hdCodServicio" name="hdCodServicio" value="<?= $_SESSION['cobrast']['idservicio'] ?>" />
                            <input type="hidden" id="hdCodUsuarioServicio" name="hdCodUsuarioServicio" value="<?= $_SESSION['cobrast']['idusuario_servicio'] ?>" />
                            <input type="hidden" id="hdNomServicio" name="hdNomServicio" value="<?= $_SESSION['cobrast']['servicio'] ?>" />
                            <div id="panelNuevoEstadoPrioridad" style="display:block;">

                                <table cellpadding="0" cellspacing="0" border="0" class="tableForm">
                                    <tr>
                                        <td></td>
                                        <td class="text-black" style="font-size:18px; border-bottom:1px solid #F1F1F1;">Crear Estados</td>
                                    </tr>
                                </table>
                                <table id="tFormTableEstado" cellpadding="0" cellspacing="0" border="0" class="tableForm">
                                    <tr>
                                        <td><input type="hidden" id="IdEstado" name="IdEstado" /></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div align="center">
                                                <table>
                                                    <tr>
                                                        <td><button onclick="save_estado()" class="btn" id="btnGuardar">Guardar</button></td>
                                                        <td><button onclick="update_estado()" class="btn" id="btnActualizar">Actualizar</button></td>
                                                        <td><button onclick="delete_estado()" class="btn" id="btnEliminar">Eliminar</button></td>
                                                        <td><button onclick="cancel_estado()" class="btn" id="btnCancelar">Cancelar</button></td>
                                                    </tr>
                                                </table>
                                                <div style="float:right; color:#AB0D05; font-weight:bold; ">*Campos requeridos</div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="text-black" style="margin-left:5px;font-size:15px;">Informacion sobre Estado</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>
                                                <table align="center" class="tableForm BoxContent" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td class="rowBoxContent textForm">Propietario de campa&ntilde;a:</td>
                                                        <td class="rowBoxContent"><input type="text" class="cajaForm" readonly="readonly" id="txtUsuarioCreacion" value="<?= $_SESSION['cobrast']['usuario'] ?>" /></td>
                                                        <td class="rowBoxContent text-alert" style="text-align:right;">*Tipo</td>
                                                        <td class="rowBoxContent"><select class="combo" id="cbTipoTransaccion"><option value="0">--Seleccione--</option></select></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="rowLastBoxContent text-alert" style="text-align:right;">*Nombre de Estado:</td>
                                                        <td class="rowLastBoxContent"><input type="text" class="cajaForm" id="txtNombreEstado" /></td>
                                                        <td class="rowLastBoxContent text-alert" style="text-align:right;">*Peso:</td>
                                                        <td class="rowLastBoxContent"><input type="text" class="cajaForm" id="txtPesoEstado" style="width:130px;" /></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="text-black" style="margin-left:5px;font-size:15px;">Descripcion/Informacion</span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>
                                                <table align="center" class="tableForm BoxContent" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td valign="top" class="rowLastBoxContent textForm">Descripcion:</td>
                                                        <td class="rowLastBoxContent" align="center"><textarea class="textareaForm" id="txtDescripcionEstado"></textarea></td>
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
                                                        <td><button onclick="save_estado()" class="btn" id="btnGuardar">Guardar</button></td>
                                                        <td><button onclick="update_estado()" class="btn" id="btnActualizar">Actualizar</button></td>
                                                        <td><button onclick="delete_estado()" class="btn" id="btnEliminar">Eliminar</button></td>
                                                        <td><button onclick="cancel_estado()" class="btn" id="btnCancelar">Cancelar</button></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div id="panelRegistrosEstadosPrioridad" style="display:none; padding:5px 10px;">
                                <div onclick="_slide2(this,'content_table_estados_prioridades')">
                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                        <tr>
                                            <td style="width:25px; height:25px;">
                                                <div class="backPanel iconPinBlueDown" onclick="_slide(this,'content_table_estados_prioridades')"></div>
                                            </td>
                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                <div style="direction:ltr;">
                                                    <a class="text-blue">Estados y Prioridades</a>
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
                                <div id="content_table_estados_prioridades" style="display:block;padding:5px 0;" align="center" >
                                    <table>
                                        <tr>
                                            <td>
                                                <div>
                                                    <table id="table_estado"></table>
                                                    <div id="pager_table_estado"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>	
                                            <td align="center">
                                                <div>
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <table id="table_prioridad"></table>
                                                                <div id="pager_table_prioridad"></div>
                                                            </td>
                                                        </tr>     
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div align="center">
                                <table class="tools">
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
                                                                    <li><a href="../rpt/excel/estados/EstadosPorServicio.php?Servicio=<?= $_SESSION['cobrast']['idservicio'] ?>" >Exportar Estados</a></li>
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
                    <td class="showHide ui-widget-header">
                        <div style="width:10px;"></div>
                    </td>
                </tr>
            </table>
            <div style="width:100%; height:20px; border:0 none;" class="ui-widget-header"></div>
        </div>
        <div id="beforeSendShadow" class="ui-widget-shadow" style="width:150px;height:30px;position:absolute;top:32%;left:45%;display:none;z-index:1010;"></div>
        <div id="MsgBeforeSend" class="ui-widget-content ui-corner-all" style="width:150px;height:30px;position:absolute;top:32%;left:45%;font-weight:bold;font-size:20px;vertical-align:middle;color:#333366;display:none;z-index:1020;" align="center"  ></div>
        <div id="dialogPrioridad" align="center">
            <table>
                <tr>
                    <td colspan="2"><input id="HdIdPrioridad" type="hidden" /></td>
                </tr>
                <tr>
                    <td>Ingrese Prioridad</td>
                    <td><input type="text" id="txtPesoPrioridad" class="cajaForm" /></td>
                </tr>
            </table>
        </div>
        <div id="dialogUsuario">
            <div align="center">
                <table>
                    <tr>	
                        <td colspan="2"><div align="center" id="UsuarioLayerMessage"></div></td>
                    </tr>
                    <tr>
                        <td align="right">Nombre</td>
                        <td align="left"><input type="text" id="txtUsuarioNombre" class="cajaForm IE6LongTextUsuario" /></td>
                    </tr>
                    <tr>
                        <td align="right">Paterno</td>
                        <td align="left"><input type="text" id="txtUsuarioPaterno" class="cajaForm IE6LongTextUsuario" /></td>
                    </tr>
                    <tr>
                        <td align="right">Materno</td>
                        <td align="left"><input type="text" id="txtUsuarioMaterno" class="cajaForm IE6LongTextUsuario" /></td>
                    </tr>
                    <tr>
                        <td align="right">Dni</td>
                        <td align="left"><input type="text" id="txtUsuarioDni" class="cajaForm IE6LongTextUsuario" /></td>
                    </tr>
                    <tr>
                        <td align="right">Email</td>
                        <td align="left"><input type="text" id="txtServicioEmail" class="cajaForm IE6LongTextUsuario" /></td>
                    </tr>
                    <tr>
                        <td align="right">Tipo Usuario</td>
                        <td align="left"><select id="cbTipoUsuario" class="combo"><option value="0">--Seleccione--</option></select></td>
                    </tr>
                    <tr>
                        <td align="right">Privilegio</td>
                        <td align="left"><select id="cbPrivilegioUsuario" class="combo"><option value="0">--Seleccione--</option></select></td>
                    </tr>
                    <tr>
                        <td align="right">Fecha Inicio</td>
                        <td align="left"><input type="text" id="txtUsuarioFechaInicio"  class="cajaForm IE6LongTextUsuario" /></td>
                    </tr>
                    <tr>
                        <td align="right">Fecha Fin</td>
                        <td align="left"><input type="text" id="txtUsuarioFechaFin"  class="cajaForm IE6LongTextUsuario" /></td>
                    </tr>
                    <tr>
                        <td align="right">Clave</td>
                        <td align="left"><input type="password" id="txtUsuarioClave"  class="cajaForm IE6LongTextUsuario" /></td>
                    </tr>
                    <tr>
                        <td align="right">Confirmar Clave</td>
                        <td align="left"><input type="password" id="txtUsuarioConfClave"  class="cajaForm IE6LongTextUsuario" /></td>
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

