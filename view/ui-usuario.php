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
        <title>Usuario</title>
        <!--<link type="text/css" rel="stylesheet" href="../includes/jqgrid6/css/ui.jqgrid.css" />-->
        <link type="text/css" rel="stylesheet" media="screen" href="../includes/jqgrid-3.8.2/css/ui.jqgrid.css" />
        <link type="text/css" rel="stylesheet" href="../includes/jquery-ui-1.8/themes/humanity/jquery-ui.css" />

        <link type="text/css" rel="stylesheet" href="../css/css-cobrast.css" />

        <script type="text/javascript" src="../js/includes/jquery-1.4.2.js" ></script>

        <script type="text/javascript" src="../js/includes/jquery-ui-1.8.13.custom.min.js" ></script>

        <script type="text/javascript" src="../includes/jqgrid-3.8.2/js/i18n/grid.locale-es.js" ></script>
        <script type="text/javascript" src="../includes/jqgrid-3.8.2/js/jquery.jqGrid.min.js" ></script>

        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.core.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.widget.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.mouse.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.datepicker.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.draggable.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.dialog.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.button.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.effects.core.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.effects.pulsate.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.effects.blind.min.js" ></script>

<!--<script type="text/javascript" src="../includes/jqgrid6/src/i18n/grid.locale-en.js" ></script>-->
<!--<script type="text/javascript" src="../includes/jqgrid6/src/jqModal.js" ></script>
<script type="text/javascript" src="../includes/jqgrid6/src/grid.base.js" ></script>
<script type="text/javascript" src="../includes/jqgrid6/src/grid.celledit.js" ></script>
<script type="text/javascript" src="../includes/jqgrid6/src/grid.common.js" ></script>
<script type="text/javascript" src="../includes/jqgrid6/src/grid.custom.js" ></script>
<script type="text/javascript" src="../includes/jqgrid6/src/grid.formedit.js" ></script>
<script type="text/javascript" src="../includes/jqgrid6/src/grid.import.js" ></script>
<script type="text/javascript" src="../includes/jqgrid6/src/grid.inlinedit.js" ></script>-->

        <script type="text/javascript" src="../js/js-cobrast.js" ></script>
        <script type="text/javascript" src="../js/templates.js" ></script>
        <script type="text/javascript" src="../js/validacion.js" ></script>
        <script type="text/javascript" src="../js/UsuarioDAO.js" ></script>
        <script type="text/javascript" src="../js/UsuarioJQGRID.js" ></script>
        <script type="text/javascript" src="../js/js-usuario.js" ></script>
    </head>
    <body>
        <div class="divContentMain">
            <table class="tableTab" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td rowspan="2" width="100"></td>
                    <td>
                        <div class="rightItem">
                            <div class="fltRight">
                                <a class="itemTop" href="../close.php">Cerra Sesion</a>
                            </div>
                            <div class="fltRight">
                                <a class="itemTop">Ayuda</a>
                            </div>
                            <div class="fltRight">
                                <a class="itemTop">Whats' New</a>
                            </div>
                            <strong style="margin-right: 5px;">Bienvenido: <?php echo $_SESSION['cobrast']['usuario'] ?></strong>
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
                                                <!--<td>
                                    	<div id="tabMaestro" class="itemTab border-radius-top pointer" >
                                        	<a class="AitemTab" href="ui-servicio.php" >Servicios</a>
                                                </div>
                                            </td>
                                            <td>
                                    	<div id="tabCartera" class="itemTabActive border-radius-top pointer" >
                                        	<a class="text-white" >Usuarios</a>
                                                </div>
                                            </td>
                                            <td>
                                    	<div id="tabCartera" class="itemTab border-radius-top pointer" >
                                                    <a class="AitemTab" href="ui-campania.php" >Campa&ntilde;as</a>
                                                </div>
                                            </td>
                                            <td>
                                    	<div id="tabCartera" class="itemTab border-radius-top pointer" >
                                        	<a class="AitemTab" href="#" >Cartera</a>
                                                </div>
                                            </td>
                                            <td>
                                    	<div id="tabDistribucion" class="itemTab border-radius-top pointer" >
                                        	<a class="AitemTab" href="ui-distribucion.php" >Distribucion</a>
                                                </div>
                                            </td>
                                            <td>
                                    	<div id="tabProcesos" class="itemTab border-radius-top pointer" >
                                        	<a class="AitemTab" href="#" >Procesos</a>
                                                </div>
                                            </td>
                                            <td>
                                    	<div id="tabSpeech" class="itemTab border-radius-top pointer" >
                                        	<a class="AitemTab" href="ui-speech.php" >Speech</a>
                                                </div>
                                            </td>
                                            <td>
                                    	<div id="tabGestion" class="itemTab border-radius-top pointer" >
                                        	<a class="AitemTab" href="ui-attention-client.php" >Gestion</a>
                                                </div>
                                            </td>
                                            <td>
                                    	<div id="tabGestion" class="itemTab border-radius-top pointer" >
                                        	<a class="AitemTab" href="ui-reporte.php" >Reportes</a>
                                                </div>
                                            </td>
                                            <td>
                                    	<div id="tabPapelera" class="itemTab border-radius-top pointer" >
                                        	<a class="AitemTab" href="ui-adicionales.php" >Adicionales</a>
                                                </div>
                                            </td>-->
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
                                        <td><a class="text-blue" style="cursor:pointer;" onClick="_display_panel('panelNuevoUsuario')">Nuevo</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" style="cursor:pointer;" onClick="_display_panel('panelRegistrosUsuario')">Registros</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" style="cursor:pointer;" onClick="_display_panel('panelRegistrosClusterUsuario')">Asignar Cluster</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" style="cursor:pointer;" onClick="_display_panel('panelRegistrosNotificador')">Notificador</a></td>
                                    </tr>
                                </table>
                            </div>
                            <div class=" backPanel headerPanel">
                                <div class="backPanel iconPinUp" onclick="_slideBarLayer(this,'panelCrear')">Crear</div>
                            </div>
                            <div id="panelCrear" class="backPanel contentBarLayer" style="display:block;" align="center" >
                                <table>
                                    <tr>
                                        <td>
                                            <select onchange="_showCrear(this.value)" class="combo" >
                                                <option value="crear">Crear...</option>
                                                <option value="campania">Campa&ntilde;a</option>
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
                            <div id="panelRegistrosNotificador" style="display:none;">
                                <table>
                                    <tr>
                                        <td><h3 class="ui-widget-header ui-corner-all" style="padding:3px 0px;width:200px;margin:0;" align="center">Notificadores</h3></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>
                                                <table id="table_form_notificador" style="width:90%;">
                                                    <tr>
                                                        <td colspan="4"><input type="hidden" id="hdCodNotificador" /></td>
                                                    </tr>
                                                    <tr class="ui-state-active">
                                                        <td style="padding:3px;">Nombre</td>
                                                        <td style="padding:3px;"><input maxlength="50" id="txtNombreNotificador" type="text" class="cajaForm" /></td>
                                                        <td style="padding:3px;">Paterno</td>
                                                        <td style="padding:3px;"><input maxlength="50" id="txtPaternoNotificador" type="text" class="cajaForm" /></td>
                                                    </tr>
                                                    <tr class="ui-state-active">
                                                        <td style="padding:3px;">Materno</td>
                                                        <td style="padding:3px;"><input maxlength="50" id="txtMaternoNotificador" type="text" class="cajaForm" /></td>
                                                        <td style="padding:3px;">Correo</td>
                                                        <td style="padding:3px;"><input maxlength="50" id="txtCorreoNotificador" type="text" class="cajaForm" /></td>
                                                    </tr>
                                                    <tr class="ui-state-active">
                                                        <td style="padding:3px;">Telefono</td>
                                                        <td style="padding:3px;"><input maxlength="15" id="txtTelefonoNotificador" type="text" class="cajaForm" /></td>
                                                        <td style="padding:3px;">Direccion</td>
                                                        <td style="padding:3px;"><input id="txtDireccionNotificador" type="text" class="cajaForm" /></td>
                                                    </tr>
                                                    <tr class="ui-state-active">
                                                        <td colspan="2" align="right"><button class="btn" onclick="save_notificador()">Grabar</button></td>
                                                        <td colspan="2" align="left"><button class="btn" onclick="cancel_notificador()">Cancelar</button></td>
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
                                                        <td style="width:30px;padding:3px 0;" align="center" class="ui-widget-header ui-corner-tl"></td>
                                                        <td style="width:100px;padding:3px 0;" align="center" class="ui-widget-header">Nombre</td>
                                                        <td style="width:100px;padding:3px 0;" align="center" class="ui-widget-header">Paterno</td>
                                                        <td style="width:100px;padding:3px 0;" align="center" class="ui-widget-header">Materno</td>
                                                        <td style="width:100px;padding:3px 0;" align="center" class="ui-widget-header">Correo</td>
                                                        <td style="width:100px;padding:3px 0;" align="center" class="ui-widget-header">Telefono</td>
                                                        <td style="width:100px;padding:3px 0;" align="center" class="ui-widget-header">Direccion</td>
                                                        <td style="width:30px;padding:3px 0;" align="center" class="ui-widget-header">&nbsp;</td>
                                                        <td style="width:30px;padding:3px 0;" align="center" class="ui-widget-header">&nbsp;</td>
                                                        <td style="width:20px;padding:3px 0;" align="center" class="ui-widget-header ui-corner-tr">&nbsp;</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div style="overflow:auto;height:150px;">
                                                <table id="tableNotificadores" cellpadding="0" cellspacing="0" border="0"></table>
                                            </div>
                                            <div class="ui-widget-header ui-corner-bottom">	
                                                <table>
                                                    <tr>
                                                        <td>Buscar:</td>
                                                        <td><input type="text" class="cajaForm" onkeyup="search_operadores_distribucion(this.value,'tableNotificadores')" /></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div id="panelRegistrosUsuario" style="display:none;">
                                <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                    <tr>
                                        <td style="width:25px; height:25px;">
                                            <div class="backPanel iconPinBlueDown" onclick="_slide(this,'content_table_user_teleoperador')"></div>
                                        </td>
                                        <td style=" border-bottom:1px solid #EADEC8;">
                                            <div style="direction:ltr;">
                                                <a class="text-blue">Usuario Activos</a>
                                            </div>
                                        </td>
                                        <td style=" border-bottom:1px solid #EADEC8;">
                                            <div style="direction:rtl;">
                                                <span class="text-gris">Nuevo</span>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <div id="content_table_user_teleoperador" style="display:block; padding:10px 0;" align="center">
                                    <table>
                                        <tr>
                                            <td>
                                                <div>
                                                    <table id="table_user_teleoperador"></table>
                                                    <div id="pager_table_user_teleoperador"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div id="panelNuevoUsuario">
                                <input type="hidden" id="IdUsuario" name="IdUsuario" />
                                <input type="hidden" id="IdUsuarioServicio" name="IdUsuarioServicio" />
                                <table cellpadding="0" cellspacing="0" border="0" class="tableForm">
                                    <tr>
                                        <td></td>
                                        <td class="text-black" style="font-size:18px; border-bottom:1px solid #F1F1F1;">Crear Usuarios</td>
                                    </tr>
                                </table>
                                <table cellpadding="0" cellspacing="0" border="0" class="tableForm">
                                    <tr>
                                        <td>
                                            <div align="center">
                                                <table>
                                                    <tr>
                                                        <td><button class="btn" id="btnGuardar" onclick="save_usuario()">Guardar</button></td>
                                                        <td><button class="btn" id="btnActualizar" onclick="update_usuario()">Actualizar</button></td>
                                                        <td><button class="btn" id="btnEliminar" onclick="delete_usuario()">Eliminar</button></td>
                                                        <td><button class="btn" id="btnCancelar" onclick="cancel()">Cancelar</button></td>
                                                    </tr>
                                                </table>
                                                <div style="float:right; color:#AB0D05; font-weight:bold; ">*Campos requeridos</div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <div>

                                    <table cellpadding="0" cellspacing="0" border="0" class="tableForm">
                                        <tr>
                                            <td>
                                                <span class="text-black" style="margin-left:5px;font-size:15px;">Informacion sobre Usuario</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div>
                                                    <table align="center" class="tableForm BoxContent" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td class="rowBoxContent textForm">Usuario de creacion:</td>
                                                            <td class="rowBoxContent"><input type="text" class="cajaForm" id="UsuarioCreacion" value="<?= $_SESSION['cobrast']['usuario'] ?>" readonly="readonly" /></td>
                                                            <td class="rowBoxContent text-alert" style="text-align:right;">*Nombre:</td>
                                                            <td class="rowBoxContent"><input type="text" class="cajaForm" id="Nombre" name="Nombre" /></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="rowBoxContent text-alert" style="text-align:right;" >*Paterno:</td>
                                                            <td class="rowBoxContent"><input type="text" class="cajaForm" id="Paterno" name="Paterno" /></td>
                                                            <td class="rowBoxContent text-alert" style="text-align:right;" >*Materno:</td>
                                                            <td class="rowBoxContent"><input type="text" class="cajaForm" id="Materno" name="Materno" /></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="rowBoxContent text-alert" style="text-align:right;" >*DNI:</td>
                                                            <td class="rowBoxContent"><input type="text" class="cajaForm" id="DNI" name="DNI" /></td>
                                                            <td class="rowBoxContent textForm" style="text-align:right;">Email:</td>
                                                            <td class="rowBoxContent"><input type="text" class="cajaForm" id="Email" name="Email" /></td>
                                                        </tr>
                                                        <tr>    
                                                            <td class="rowBoxContent text-alert" style="text-align:right;">*Clave:</td>
                                                            <td class="rowBoxContent"><input type="password" class="cajaForm" id="Clave" id="Clave" /></td>
                                                            <td class="rowBoxContent text-alert" align="right">*Confirmar Clave</td>
                                                            <td class="rowBoxContent"><input type="password" class="cajaForm" id="ConfClave" id="Clave" /></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="rowBoxContent text-alert" align="right" >*Tipo Usuario</td>
                                                            <td class="rowBoxContent"><select id="TipoUsuario" class="combo"><option value="0">--Seleccione--</option></select></td>	
                                                            <td class="rowBoxContent text-alert" align="right">*Privilegio</td>
                                                            <td class="rowBoxContent"><select id="Privilegio" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="rowLastBoxContent text-alert" align="right">*Fecha Inicio</td>
                                                            <td class="rowLastBoxContent"><input readonly="readonly" type="text" id="FechaInicio" class="cajaForm" style="width:130px;" /></td>
                                                            <td class="rowLastBoxContent text-alert" align="right">*Fecha Fin</td>
                                                            <td class="rowLastBoxContent"><input readonly="readonly" type="text" id="FechaFin" class="cajaForm" style="width:130px;" /></td>
                                                        </tr>
                                                        <!--<tr>
                                                            <td class="rowLastBoxContent textForm" style="text-align:right;" valign="top">Servicios:</td>
                                                            <td class="rowLastBoxContent" colspan="3">
                                                	<div>
                                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                                            <tr>
                                                                            <td>
                                                                                    <select size="8" id="cbServicioSystem"></select>
                                                                            </td>
                                                                            <td>
                                                                	<div>
                                                                                    <table>
                                                                                            <tr>
                                                                                                            <td><button class="button" onclick="agregar_servicio_usuario()">>></button></td>
                                                                    		</tr>
                                                                                        <tr>
                                                                                            <td><button class="button" onclick="eliminar_servicio_usuario()"><<</button></td>
                                                                                        </tr>
                                                                    	</table>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                    <select size="8" id="cbServicioUsuario"></select>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>-->
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>

                                </div>
                                <table cellpadding="0" cellspacing="0" border="0" class="tableForm">
                                    <tr>
                                        <td>
                                            <div align="center">
                                                <table>
                                                    <tr>
                                                        <td><button class="btn" id="btnGuardar" onclick="save_usuario()">Guardar</button></td>
                                                        <td><button class="btn" id="btnActualizar" onclick="update_usuario()">Actualizar</button></td>
                                                        <td><button class="btn" id="btnEliminar" onclick="delete_usuario()">Eliminar</button></td>
                                                        <td><button class="btn" id="btnCancelar" onclick="cancel()">Cancelar</button></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div id="panelRegistrosClusterUsuario" style="display:none;" align="center">
                                <div style="overflow:auto; margin-top:5px; width:85%" align="center">
                                    <div class="backPanel headerPanel ui-corner-top" style="height:20px; padding-top:5px; font-weight:bold" align="center">ASIGNAR CLUSTER DE TRABAJO A OPERADORES</div>	
                                    <div align="center" class="ui-widget-content ui-corner-bottom" style="padding:2px 0px 10px 0px">
                                        <table cellpadding="0" cellspacing="5">
                                            <tr>
                                                <td colspan="2" align="right">
                                                    <div onclick="_mostrarFrm('mantenimientoCluster')" style="width:135px; padding:4px 2px; cursor:pointer" class="ui-state-highlight ui-corner-all text-blue" align="center" id="btnMantenimientoCluster">
                                                        Mantenimiento Cluster
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <!-- data operador -->
                                                <td width="50%">
                                                    <div>
                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                            <tr>
                                                                <td style="width:30px;padding:3px 0;" align="center" class="backPanel headerPanel ui-corner-tl"></td>
                                                                <td style="width:300px;padding:3px 5px;font-weight:bold;color:#c25700" align="center" class="backPanel headerPanel">TELEOPERADOR</td>
                                                                <td style="width:100px;padding:3px 0;font-weight:bold;color:#c25700" align="center" class="backPanel headerPanel">DNI</td>
                                                                <td style="width:30px;padding:3px 0;" align="center" class="backPanel headerPanel">&nbsp;</td>
                                                                <td style="width:20px;padding:3px 0;" align="center" class="backPanel headerPanel ui-corner-tr">&nbsp;</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div style="overflow:auto;height:160px;">
                                                        <table id="tableOperadoresCluster" cellpadding="0" cellspacing="0" border="0"></table>
                                                    </div>
                                                    <div class="backPanel headerPanel ui-corner-bottom">	
                                                        <table>
                                                            <tr>
                                                                <td><label style="padding:10px;font-weight:bold;color:#c25700">Buscar:</label></td>
                                                                <td><input type="text" class="cajaForm" onkeyup="search_operadores_distribucion(this.value,'tableOperadoresCluster')" /></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </td>
                                                <!-- data detalle cluster operador -->
                                                <td width="50%" align="center">

                                                    <div id="layerDetalleCluster" style="display:none"><table><tr><td>
                                                                    <div>
                                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                                            <tr>
                                                                                <td style="width:30px;padding:3px 0;" align="center" class="ui-state-default ui-th-column ui-th-ltr ui-corner-tl"></td>
                                                                                <td style="width:100px;padding:3px 5px;font-weight:bold;color:#c25700" align="center" class="ui-state-default ui-th-column ui-th-ltr">CLUSTER</td>
                                                                                <td style="width:100px;padding:3px 0;font-weight:bold;color:#c25700" align="center" class="ui-state-default ui-th-column ui-th-ltr">ESTADO</td>
                                                                                <td style="width:30px;padding:3px 0;" align="center" class="ui-state-default ui-th-column ui-th-ltr">&nbsp;</td>
                                                                                <td style="width:20px;padding:3px 0;" align="center" class="ui-state-default ui-th-column ui-th-ltr ui-corner-tr">&nbsp;</td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                    <div style="overflow:auto;height:120px;">
                                                                        <table id="tableOperadoresClusterDetalle" cellpadding="0" cellspacing="0" border="0"></table>
                                                                    </div>
                                                                    <div>	
                                                                        <table class="ui-state-default ui-th-column ui-th-ltr ui-corner-bottom" style="width:100%; height:20px">
                                                                            <tr>
                                                                                <td>
                                                                                    <label>Cluster </label>

                                                                                    <input type="hidden" id="idususerAddCluster" value="" />
                                                                                    <select id="tipoCluster" class="combo"><option value="0">--Seleccione--</option></select>

                                                                                    <div id="btnAddClusterUsuSer" class="ui-state-default ui-corner-all ui-widget " onclick="add_cluster_operador();" style="width:70px; color:#c25700; font-weight:bold;" role="button" aria-disabled="false">
                                                                                        <span>A&Ntilde;ADIR</span>
                                                                                    </div>
                                                                                </td>
                                                                                <!--<td><label style="padding:10px;font-weight:bold;color:#c25700">Buscar:</label></td>
                                                                                <td><input type="text" class="cajaForm" onkeyup="search_operadores_distribucion(this.value,'tableOperadoresClusterDetalle')" /></td>-->
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </td></tr></table></div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
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
                                                                        <td><div class="tools-header">Prospecto Usuarios</div></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div>
                                                                <ul class="tools-ul">
                                                                    <li><a href="../rpt/excel/usuario/ListUsuarioByService.php?Servicio=<?= $_SESSION['cobrast']['idservicio'] ?>" >Exportar Usuarios</a></li>
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
        <div id="dialogCampania">
            <div align="center" class="borderBoxForm">
                <table>
                    <tr>	
                        <td colspan="2"><div align="center" id="CampaniaLayerMessage"></div></td>
                    </tr>
                    <tr>
                        <td align="right">Nombre</td>
                        <td align="left"><input type="text" id="txtCampaniaNombre" class="cajaForm" style="width:200px;" /></td>
                    </tr>
                    <tr>
                        <td align="right">Fecha Inicio</td>
                        <td align="left"><input readonly="readonly" type="text" id="txtCampaniaFechaInicio" class="cajaForm" style="width:200px;" /></td>
                    </tr>
                    <tr>
                        <td align="right">Fecha Fin</td>
                        <td align="left"><input readonly="readonly" type="text" id="txtCampaniaFechaFin" class="cajaForm" style="width:200px;" /></td>
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
        <div id="mantenimientoCluster" align="center" style="background-color:#FFF">
            <div id="content_table_mantenimiento_cluster" style="padding:10px 0;" align="center">
                <div>
                    <table id="table_mantenimiento_cluster"></table>
                    <div id="pager_table_mantenimiento_cluster"></div>
                </div>
            </div>
        </div>

        <div id="dialogMantenimientoClusterEdit" align="center" style="background-color:#FFF">
            <table cellspacing="2">	
                <tr>
                    <td colspan="2"><input type="hidden" id="IdClusterUpd" name="IdUsuarioServicio" /></td>
                </tr>
                <tr class="ui-state-active">
                    <td align="left"><label style="padding:3px 5px">Nombre</label></td>
                    <td align="left"><input type="text" id="nombreClusterUpd" class="cajaForm" style="width:130px;" /></td>
                </tr>
                <tr class="ui-state-active">
                    <td align="left"><label style="padding:3px 5px">Descripcion</label></td>
                    <td align="left"><input type="text" id="descripClusterUpd" class="cajaForm" style="width:130px;" /></td>
                </tr>
                <tr class="ui-state-active">
                    <td align="left"><label style="padding:3px 5px">Estado</label></td>
                    <td align="left"><select id="estadoClusterUpd" class="combo"><option value="1">ACTIVO</option><option value="0">INACTIVO</option></select></td>
                </tr>
                <tr height="5px"><td></td></tr>
                <tr>
                    <td colspan="2" align="center"><div id="btnClusterServEdit" class="ui-state-default ui-corner-all ui-button ui-widget ui-button-text-only" style="width:100px; color:#c25700; font-weight:bold;" onclick="update_cluster_servicio();">ACTUALIZAR</div></td>
                </tr>
            </table>
        </div>
        <div id="dialogMantenimientoClusterAdd" align="center" style="background-color:#FFF">
            <table cellspacing="2">	
            <!--<tr>
                    <td colspan="2"><input type="hidden" id="IdClusterAdd" name="IdUsuarioServicio" /></td>
            </tr>-->
                <tr class="ui-state-active">
                    <td align="left"><label style="padding:3px 5px">Nombre</label></td>
                    <td align="left"><input type="text" id="nombreClusterAdd" class="cajaForm" style="width:130px;" /></td>
                </tr>
                <tr class="ui-state-active">
                    <td align="left"><label style="padding:3px 5px">Descripcion</label></td>
                    <td align="left"><input type="text" id="descripClusterAdd" class="cajaForm" style="width:130px;" /></td>
                </tr>
                <tr height="5px"><td></td></tr>
                <tr>
                    <td colspan="2" align="center"><div id="btnClusterServAdd" class="ui-state-default ui-corner-all ui-button ui-widget ui-button-text-only" style="width:100px; color:#c25700; font-weight:bold;" onclick="save_cluster_servicio();">AGREGAR</div></td>
                </tr>
                <!--<tr>
                        <td align="left">Estado</td>
                    <td align="left"><select id="estadoCluster" class="comboAdd"><option value="1">ACTIVO</option><option value="0">INACTIVO</option></select></td>
                </tr>-->
            </table>
        </div>

    </body>
</html>
