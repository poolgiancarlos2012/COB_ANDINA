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
        <title>Datos</title>
        <link type="text/css" rel="stylesheet" href="../includes/jqgrid6/css/ui.jqgrid.css" />
        <link type="text/css" rel="stylesheet" href="../includes/jquery-ui-1.8/themes/humanity/jquery-ui.css" />

        <link type="text/css" rel="stylesheet" href="../css/css-cobrast.css" />
        <link type="text/css" rel="stylesheet" href="../includes/sexy/sexyalertbox.css" />

        <script type="text/javascript" src="../js/includes/jquery-1.4.2.js" ></script>

        <script type="text/javascript" src="../includes/jqgrid-3.6.5/src/i18n/grid.locale-en.js" ></script>
        <script type="text/javascript" src="../includes/jqgrid-3.6.5/src/jqModal.js" ></script>
        <script type="text/javascript" src="../includes/jqgrid-3.6.5/src/grid.base.js" ></script>
        <script type="text/javascript" src="../includes/jqgrid-3.6.5/src/grid.celledit.js" ></script>
        <script type="text/javascript" src="../includes/jqgrid-3.6.5/src/grid.common.js" ></script>
        <script type="text/javascript" src="../includes/jqgrid-3.6.5/src/grid.custom.js" ></script>
        <script type="text/javascript" src="../includes/jqgrid-3.6.5/src/grid.formedit.js" ></script>
        <script type="text/javascript" src="../includes/jqgrid-3.6.5/src/grid.import.js" ></script>
        <script type="text/javascript" src="../includes/jqgrid-3.6.5/src/grid.inlinedit.js" ></script>

        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.core.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.widget.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.mouse.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.draggable.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.droppable.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.selectable.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.datepicker.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.dialog.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.button.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.slider.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.effects.core.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.effects.pulsate.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.effects.slide.min.js" ></script>
        <script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.effects.blind.min.js" ></script>


        <script type="text/javascript" src="../js/js-cobrast.js" ></script>
        <script type="text/javascript" src="../js/templates.js" ></script>
        <script type="text/javascript" src="../js/validacion.js" ></script>
        <script type="text/javascript" src="../js/AdicionalesJQGRID.js" ></script>
        <script type="text/javascript" src="../js/AdicionalesDAO.js" ></script>
        <script type="text/javascript" src="../js/js-adicionales.js" ></script>
        <style type="text/css">
            .formItem{
                margin: 10px auto;
            }
            .formLabel{
                width: 150px;
                float: left;
            }
            #tblFinales{
                overflow-y: auto;
                height:150px;                
            }
            .th-id,.dt-id{width: 70px;text-align: center} .th-final,.dt-final{width: 170px;text-align: center} .th-detal,.dt-detal{width: 100px;text-align: center}
            table.scroll {
                table-layout: fixed;
                border-right: 1px solid #D4D0C8;
            }
            table.scroll tr {
                background-color: #ffffff;
                font-family:Calibri;
                font-size:10px;
            }
            table.scroll th{
                background-image:url(../img/bg.png);
                border-bottom:1px solid #cbc7b8;
                border-left:1px solid #d4d0c8;
                font-size:12px;
                font-weight:bold;
                overflow:hidden;
                padding:5px;
                text-align:center;
                white-space:nowrap;
            }
            table.scroll tr.selected td {
                background: #BEDCC4;
                color: #333333;
            }
            table.scroll td  {
                padding: 5px;
                text-align: left;
                border-bottom: 1px solid #D4D0C8;
                border-left: 1px solid #D4D0C8;
                text-overflow: ellipsis;
                overflow: hidden;
                white-space: pre-wrap;
                font-family:Verdana;
                text-transform:uppercase;
            }
            tr.odd td { background-color:#F7F7F7;}
            tr.even td { background-color:#fff; }
            table.scroll tr.over td{background-color: #FFEEE6;}

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
                                    <div style="margin-left:20px; margin-bottom:2px;"><a class="text-blue" onClick="_display_panel('panelRegistrosServicio')">Registros</a></div>

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
                        <div id="cobrastHOME" style="background-color:#FFFFFF; width:100%; height:100%; ">
                            <input type="hidden" id="hdCodUsuario" name="hdCodUsuario" value="<?= $_SESSION['cobrast']['idusuario'] ?>" />
                            <input type="hidden" id="hdCodServicio" name="hdCodServicio" value="<?= $_SESSION['cobrast']['idservicio'] ?>" />
                            <input type="hidden" id="hdCodUsuarioServicio" name="hdCodUsuarioServicio" value="<?= $_SESSION['cobrast']['idusuario_servicio'] ?>" />
                            <input type="hidden" id="hdNomServicio" name="hdNomServicio" value="<?= $_SESSION['cobrast']['servicio'] ?>" />
                            <div id="panelRegistrosServicio" >
                                <!-- Tabla Finales -->
                                <div onclick="_slide2(this,'content_table_final')">
                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                        <tr>
                                            <td style="width:25px; height:25px;">
                                                <div class="backPanel iconPinBlueUp"></div>
                                            </td>
                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                <div style="direction:ltr;">
                                                    <a class="text-blue">Final</a>
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
                                <div id="content_table_final" style="display:block; padding:5px 0;" align="center">
                                    <table>
                                        <tr>
                                            <td>
                                                <div>
                                                    <table id="table_final"></table>
                                                    <div id="pager_table_final"></div>
                                                    <input type="hidden" id="hddIdFinal"/>
                                                    <div style="margin-top:10px; ">
                                                        <button class="btn" onclick="open_dialog_final_servicio()">Agregar a mi linea</button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <!-- -->
                                <!-- Tabla Servicios Finales -->
                                <div  onclick="_slide2(this,'content_table_servicio_final')">
                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                        <tr>
                                            <td style="width:25px; height:25px;">
                                                <div class="backPanel iconPinBlueUp" ></div>
                                            </td>
                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                <div style="direction:ltr;">
                                                    <a class="text-blue">Mis Finales</a>
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
                                <div id="content_table_servicio_final" style="display:block; padding:5px 0;" align="center">
                                    <table>
                                        <tr>
                                            <td>
                                                <div>
                                                    <table id="table_final_servicios"></table>
                                                    <div id="pager_table_final_servicios"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <!-- -->
                                <div onclick="_slide2(this,'content_table_tipo_final')">
                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                        <tr>
                                            <td style="width:25px; height:25px;">
                                                <div class="backPanel iconPinBlueUp" ></div>
                                            </td>
                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                <div style="direction:ltr;">
                                                    <a class="text-blue">Tipo Final</a>
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
                                <div id="content_table_tipo_final" style="display:none; padding:5px 0;" align="center">
                                    <table>
                                        <tr>
                                            <td>
                                                <div>
                                                    <table id="table_tipo_final"></table>
                                                    <div id="pager_table_tipo_final"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div onclick="_slide2(this,'content_table_carga_final')">
                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                        <tr>
                                            <td style="width:25px; height:25px;">
                                                <div class="backPanel iconPinBlueUp" ></div>
                                            </td>
                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                <div style="direction:ltr;">
                                                    <a class="text-blue">Carga Final</a>
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
                                <div id="content_table_carga_final" style="display:none; padding:5px 0;" align="center">
                                    <table>
                                        <tr>
                                            <td>
                                                <div>
                                                    <table id="table_carga_final"></table>
                                                    <div id="pager_table_carga_final"></div>                                                    
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div onclick="_slide2(this,'content_table_clase_final')">
                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                        <tr>
                                            <td style="width:25px; height:25px;">
                                                <div class="backPanel iconPinBlueUp" ></div>
                                            </td>
                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                <div style="direction:ltr;">
                                                    <a class="text-blue">Clase Final</a>
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
                                <div id="content_table_clase_final" style="display:none; padding:5px 0;" align="center">
                                    <table>
                                        <tr>
                                            <td>
                                                <div>
                                                    <table id="table_clase_final"></table>
                                                    <div id="pager_table_clase_final"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div onclick="_slide2(this,'content_table_nivel')">
                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                        <tr>
                                            <td style="width:25px; height:25px;">
                                                <div class="backPanel iconPinBlueUp" ></div>
                                            </td>
                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                <div style="direction:ltr;">
                                                    <a class="text-blue">Nivel</a>
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
                                <div id="content_table_nivel" style="display:none; padding:5px 0;" align="center">
                                    <table>
                                        <tr>
                                            <td>
                                                <div>
                                                    <table id="table_nivel"></table>
                                                    <div id="pager_table_nivel"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div onclick="_slide2(this,'content_table_tipo_gestion')">
                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                        <tr>
                                            <td style="width:25px; height:25px;">
                                                <div class="backPanel iconPinBlueUp" ></div>
                                            </td>
                                            <td style=" border-bottom:1px solid #EADEC8;">
                                                <div style="direction:ltr;">
                                                    <a class="text-blue">Tipo Gestion</a>
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
                                <div id="content_table_tipo_gestion" style="display:none; padding:5px 0;" align="center">
                                    <table>
                                        <tr>
                                            <td>
                                                <div>
                                                    <table id="table_tipo_gestion"></table>
                                                    <div id="pager_table_tipo_gestion"></div>
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
            <div style="width:100%; height:20px; border:0 none;" class="ui-widget-header"></div>
        </div>
        <div id="dialogFinal" align="center">
            <table>
                <tr>
                    <td colspan="2"><input type="hidden" id="hdIdFinal" /></td>
                </tr>
                <tr>
                    <td align="left">Nombre</td>
                    <td align="left"><input class="combo" type="text" id="txtNombreFinal" maxlength="80" style="width:300px;" /></td>
                </tr>
                <tr>
                    <td align="left">Tipo</td>
                    <td align="left"><select class="combo" id="cbTipoFinal"><option value="0">--Seleccione--</option></select></td>
                </tr>
                <tr>
                    <td align="left">Carga</td>
                    <td align="left"><select class="combo" id="cbCargaFinal"><option value="0">--Seleccione--</option></select></td>
                </tr>
                <tr>
                    <td align="left">Clase</td>
                    <td align="left"><select class="combo" id="cbClaseFinal"><option value="0">--Seleccione--</option></select></td>
                </tr>
                <tr>
                    <td align="left">Nivel</td>
                    <td align="left"><select class="combo" id="cbNivelFinal"><option value="0">--Seleccione--</option></select></td>
                </tr>
                <tr>
                    <td align="left" valign="top">Descripcion</td>
                    <td align="left"><textarea class="combo" id="txtDescripcionFinal" style="width:300px;"></textarea></td>
                </tr>
            </table>
        </div>
        <div id="dialogFinalServicio" align="center" >
            <table>
                <tr>
                    <td colspan="2"><input type="hidden" id="hdServicioIdFinal" name="hdServicioIdFinal" /></td>
                </tr>
                <tr>
                    <td>Estado</td>
                    <td id="tdServicioFinalNombre"></td>
                </tr>
                <tr>
                    <td>Clase</td>
                    <td id="tdServicioClaseNombre"></td>
                </tr>
                <tr>
                    <td>Efecto</td>
                    <td><input type="text" id="txtEfectoFinal" class="combo" maxlength="100" style="width:300px;" /></td>
                </tr>
                <tr>
                    <td>Peso</td>
                    <td><input type="text" id="txtPesoFinal" class="combo" maxlength="11" style="width:100px;" /></td>
                </tr>
                <tr>
                    <td>Prioridad</td>
                    <td><input type="text" id="txtPrioridadFinal" class="combo" maxlength="11" style="width:100px;" /></td>
                </tr>
            </table>
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
    </body>
</html>

