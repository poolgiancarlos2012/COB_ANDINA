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
        <link type="text/css" rel="stylesheet" media="screen" href="../includes/jqgrid-3.8.2/css/ui.jqgrid.css" />
        <!--<link type="text/css" rel="stylesheet" href="../includes/jqgrid6/css/ui.jqgrid.css" />-->
        <link type="text/css" rel="stylesheet" href="../includes/jquery-ui-1.8/themes/humanity/jquery-ui.css" />
        <link type="text/css" rel="stylesheet" href="../includes/font-awesome-4.3.0/css/font-awesome.css" />

        <link type="text/css" rel="stylesheet" href="../css/css-cobrast.css" />

        <script type="text/javascript" src="../js/includes/jquery-1.4.2.js" ></script>

        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.core.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.widget.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.mouse.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.datepicker.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.draggable.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.dialog.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.button.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.effects.core.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.effects.pulsate.min.js" ></script>

        <script type="text/javascript" src="../includes/jqgrid-3.8.2/js/i18n/grid.locale-es.js" ></script>
        <script type="text/javascript" src="../includes/jqgrid-3.8.2/js/jquery.jqGrid.min.js" ></script>

<!--<script type="text/javascript" src="../includes/jqgrid6/src/i18n/grid.locale-en.js" ></script>
<script type="text/javascript" src="../includes/jqgrid6/src/jqModal.js" ></script>
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
        <script type="text/javascript" src="../js/UsuarioAdminDAO.js" ></script>
        <script type="text/javascript" src="../js/UsuarioAdminJQGRID.js" ></script>
        <script type="text/javascript" src="../js/js-usuario-admin.js" ></script>
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
                                <table border="0" style="margin-left:20px;" >
                                    <tr>
                                        <td><a id="ApinNuevo" class="text-blue" style="cursor:pointer;" onClick="_display_panel('panelNuevoUsuario')">Nuevo</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" style="cursor:pointer;" onClick="_display_panel('panelRegistrosUsuario')">Registros</a></td>
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
                        <div id="cobrastHOME" style="background-color:#FFFFFF; width:100% !important; height:100%; ">
                            <input type="hidden" id="hdCodUsuario" name="hdCodUsuario" value="<?= $_SESSION['cobrast']['idusuario'] ?>" />
                            <input type="hidden" id="hdNomUsuario" name="hdNomUsuario" value="<?= $_SESSION['cobrast']['usuario'] ?>" />
                            <input type="hidden" id="hdCodServicio" name="hdCodServicio" value="<?= $_SESSION['cobrast']['idservicio'] ?>" />
                            <input type="hidden" id="hdCodUsuarioServicio" name="hdCodUsuarioServicio" value="<?= $_SESSION['cobrast']['idusuario_servicio'] ?>" />
                            <input type="hidden" id="hdNomServicio" name="hdNomServicio" value="<?= $_SESSION['cobrast']['servicio'] ?>" />
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
                                                    <table id="table_user_admin"></table>
                                                    <div id="pager_table_user_admin"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                    <tr>
                                        <td style="width:25px; height:25px;">
                                            <div class="backPanel iconPinBlueDown" onclick="_slide(this,'content_table_servicios_usuario')"></div>
                                        </td>
                                        <td style=" border-bottom:1px solid #EADEC8;">
                                            <div style="direction:ltr;">
                                                <a class="text-blue">Servicios de usuario</a>
                                            </div>
                                        </td>
                                        <td style=" border-bottom:1px solid #EADEC8;">
                                            <div style="direction:rtl;">
                                                <span class="text-gris">Nuevo</span>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <div id="content_table_servicios_usuario" style="display:block; padding:10px 0;" align="center">
                                    <table>
                                        <tr>
                                            <td>
                                                <div>
                                                    <table id="table_servicios_usuario"></table>
                                                    <div id="pager_table_servicios_usuario"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div id="panelNuevoUsuario">
                                <input type="hidden" id="IdUsuario" name="IdUsuario" />

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
                                                            <td class="rowBoxContent textForm" style="text-align:right;" >Codigo :</td>
                                                            <td class="rowBoxContent"><input readonly="readonly" style="color:#CBCBCB;font-weight:bold;" value="Generando" type="text" class="cajaForm" id="Codigo" name="Codigo" /></td>
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
                                                            <td class="rowBoxContent"><input type="text" maxlength="8" class="cajaForm" id="DNI" name="DNI" /></td>
                                                            <td class="rowBoxContent textForm" style="text-align:right;">Email:</td>
                                                            <td class="rowBoxContent"><input type="text" class="cajaForm" id="Email" name="Email" /></td>
                                                        </tr>
														<tr>
                                                            <td class="rowBoxContent textForm" style="text-align:right;" >Celular:</td>
                                                            <td class="rowBoxContent"><input type="text" class="cajaForm" maxlength="15" id="Celular" name="Celular" /></td>
                                                            <td class="rowBoxContent textForm" style="text-align:right;">Telefono:</td>
                                                            <td class="rowBoxContent"><input type="text" class="cajaForm" maxlength="15" id="Telefono" name="Telefono" /></td>
                                                        </tr>
														<tr>
                                                            <td class="rowBoxContent textForm" style="text-align:right;" >Telefono 2 :</td>
                                                            <td class="rowBoxContent"><input type="text" class="cajaForm" maxlength="15" id="Telefono2" name="Telefono2" /></td>
                                                            <td class="rowBoxContent textForm" style="text-align:right;">Direccion:</td>
                                                            <td class="rowBoxContent"><input type="text" class="cajaForm" id="Direccion" name="Direccion" /></td>
                                                        </tr>
														<tr>
                                                            <td class="rowBoxContent textForm" style="text-align:right;" >Fecha Nacimiento :</td>
                                                            <td class="rowBoxContent"><input type="text" readonly="readonly" class="cajaForm" id="FechaNacimiento" name="FechaNacimiento" /></td>
                                                            <td class="rowBoxContent textForm" style="text-align:right;">Estado Civil :</td>
                                                            <td class="rowBoxContent">
                                                                <select class="combo" name="EstadoCivil" id="EstadoCivil">
                                                                    <option value="SOLTERO">SOLTERO(A)</option>
                                                                    <option value="CASADO">CASADO(A)</option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="rowBoxContent textForm" style="text-align:right;" >Genero :</td>
                                                            <td class="rowBoxContent">
                                                                <select class="combo" name="Genero" id="Genero">
                                                                    <option value="FEMENINO">FEMENINO</option>
                                                                    <option value="MASCULINO">MASCULINO</option>
                                                                </select>
                                                            </td>
                                                            <td class="rowBoxContent textForm" style="text-align:right;">Tipo Trabajo :</td>
                                                            <td class="rowBoxContent">
                                                                <select class="combo" name="TipoTrabajo" id="TipoTrabajo">
                                                                    <option value="MAÑANA">MA&Ntilde;ANA</option>
                                                                    <option value="TARDE">TARDE</option>
                                                                    <option value="FULL">FULL</option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="rowBoxContent textForm" style="text-align:right;" >Planilla :</td>
                                                            <td class="rowBoxContent">
                                                                <select class="combo" name="Planilla" id="Planilla">
                                                                    <option value="SI">SI</option>
                                                                    <option value="NO">NO</option>
                                                                </select>
                                                            </td>
                                                            <td class="rowBoxContent textForm" style="text-align:right;"></td>
                                                            <td class="rowBoxContent"></td>
                                                        </tr>
                                                        <tr>    
                                                            <td class="rowBoxContent text-alert" style="text-align:right;">*Clave:</td>
                                                            <td class="rowBoxContent"><input type="password" class="cajaForm" id="Clave" id="Clave" /></td>
                                                            <td class="rowBoxContent text-alert" align="right">*Confirmar Clave</td>
                                                            <td class="rowBoxContent"><input type="password" class="cajaForm" id="ConfClave" id="Clave" /></td>
                                                        </tr>
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
            <div style="width:100%; height:20px; border:0 none;" class="ui-widget-header ui-corner-bottom"></div>
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
                        <td align="left"><input type="text" id="txtCampaniaFechaInicio" class="cajaForm" style="width:200px;" /></td>
                    </tr>
                    <tr>
                        <td align="right">Fecha Fin</td>
                        <td align="left"><input type="text" id="txtCampaniaFechaFin" class="cajaForm" style="width:200px;" /></td>
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
        <div id="dialogServiciosUsuario" align="center">
            <table>	
                <tr>
                    <td colspan="4"><input type="hidden" id="IdUsuarioServicio" name="IdUsuarioServicio" /></td>
                </tr>
                <tr>
                    <td align="left">Servicio</td>
                    <td align="left"><select id="cbServicio" class="combo"><option value="0">--Seleccione--</option></select></td>
                    <td align="left">Tipo Usuario</td>
                    <td align="left"><select id="TipoUsuario" class="combo"><option value="0">--Seleccione--</option></select></td>
                </tr>
                <tr>
                    <td align="left">Privilegio</td>
                    <td align="left"><select id="Privilegio" class="combo"><option value="0">--Seleccione--</option></select></td>
                    <td align="left"></td>
                    <td align="left"></td>
                </tr>
                <tr>
                    <td align="left">Fecha Inicio</td>
                    <td align="left"><input type="text" id="FechaInicio" class="cajaForm" style="width:130px;" /></td>
                    <td align="left">Fecha Fin</td>
                    <td align="left"><input type="text" id="FechaFin" class="cajaForm" style="width:130px;" /></td>
                </tr>
            </table>
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
