<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <link type="text/css" rel="stylesheet"
              href="../includes/jqgrid5/css/ui.jqgrid.css" />
        <link type="text/css" rel="stylesheet"
              href="../includes/jquery-ui-1.8/themes/humanity/jquery-ui.css" />
        <link type="text/css" rel="stylesheet" href="../css/css-cobrast.css" />
        <style type="text/css">
            .row {
                width: 100%;
                margin: 15px 0 3px 15px;
            }
            .row-title{
                font-size: 20px;
                font-weight: bold;
            }
            .row-item {
                display: inline-table;
            }

            label,ul,li {
                font-family: Calibri;
                font-size: 13px;
            }

            select {
                font-family: Calibri;
                font-size: 13px;
            }
            ol{
                margin: 0;
                padding: 0;
            }
            ul{
                list-style: none;
                padding-left: 20px;
                padding-top: 10px;
            }
            li{
                list-style: none;
            }
            span input[type='checkbox']{
                margin-left: 100px;
            }
            .row input{
                height: 20px;
                width: 300px;
                margin-left:5px;
            }
        </style>
        <script type="text/javascript" src="../js/includes/jquery-1.4.2.js"></script>
        <script type="text/javascript"
        src="../includes/jqgrid5/src/i18n/grid.locale-en.js"></script>
        <script type="text/javascript" src="../includes/jqgrid5/src/jqModal.js"></script>
        <script type="text/javascript"
        src="../includes/jqgrid5/src/grid.base.js"></script>
        <script type="text/javascript" src="../includes/jqgrid5/src/grid.celledit.js"></script>
        <script type="text/javascript"
        src="../includes/jqgrid5/src/grid.common.js"></script>
        <script type="text/javascript" src="../js/js-cobrast.js"></script>
        <script type="text/javascript" src="../js/UrlDAO.js"></script>
        <script type="text/javascript" src="../js/js-url.js"></script>

    </head>
    <body>
        <div class="divContentMain">
            <table class="tableTab" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td rowspan="2" width="100"></td>
                    <td>
                        <div class="rightItem">
                            <div class="fltRight"><a class="itemTop">Cerra Sesion</a></div>
                            <div class="fltRight"><a class="itemTop">Ayuda</a></div>
                            <div class="fltRight"><a class="itemTop">Whats' New</a></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="vAlignBottom tabsLine">
                        <div class="menuHome"><span>
                                <div>
                                    <table cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td>
                                                <div id="tabMaestro" class="itemTab border-radius-top pointer"
                                                     onclick="_activeTab(this)"><a class="AitemTab">Maestros</a></div>
                                            </td>
                                            <td>
                                                <div id="tabCartera" class="itemTab border-radius-top pointer"
                                                     onclick="_activeTab(this)"><a class="AitemTab">Cartera</a></div>
                                            </td>
                                            <td>
                                                <div id="tabDistribucion" class="itemTab border-radius-top pointer"
                                                     onclick="_activeTab(this)"><a class="AitemTab">Distribucion</a></div>
                                            </td>
                                            <td>
                                                <div id="tabProcesos" class="itemTab border-radius-top pointer"
                                                     onclick="_activeTab(this)"><a class="AitemTab">Procesos</a></div>
                                            </td>
                                            <td>
                                                <div id="tabSpeech" class="itemTab border-radius-top pointer"
                                                     onclick="_activeTab(this)"><a class="AitemTab">Speech</a></div>
                                            </td>
                                            <td>
                                                <div id="tabGestion" class="itemTab border-radius-top pointer"
                                                     onclick="_activeTab(this)"><a class="AitemTab">Gestion</a></div>
                                            </td>
                                            <td>
                                                <div id="tabGestion" class="itemTab border-radius-top pointer"
                                                     onclick="_activeTab(this)"><a class="AitemTab">Reportes</a></div>
                                            </td>
                                            <td>
                                                <div id="tabPapelera" class="itemTab border-radius-top pointer"
                                                     onclick="_activeTab(this)"><a class="AitemTab">Papelera</a></div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </span></div>
                    </td>
                </tr>
                <tr>
                    <td class="lineTab" colspan="2"></td>
                </tr>
            </table>
            <table class="tableContent" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="barLayer">
                        <div id="barLayer" style="width:210px; display:none; background:#fffbf2;border: 1px solid #666;margin:0;position:absolute;z-index:9999;height:100%;overflow:auto;" >
                            <div align="right"><img src="../img/cancel.png" style="cursor:pointer;margin:3px;" onClick="$('#barLayer').css('display','none');"/></div>
                            <div class=" backPanel headerPanel">
                                <div class="backPanel iconPinUp"
                                     onclick="_slideBarLayer(this,'panelMenu')">Menu</div>
                            </div>
                            <div id="panelMenu" class="backPanel contentBarLayer"
                                 style="display: block;">
                                <table border="0" style="margin-left: 20px;">
                                    <tr>
                                        <td><a class="text-blue" style="cursor: pointer;"
                                               onClick="_display_panel('panelRegistrosUsuario')">Nuevo</a></td>
                                    </tr>
                                    <tr>
                                        <td><a class="text-blue" style="cursor: pointer;"
                                               onClick="_display_panel('panelNuevoUsuario')">Registros</a></td>
                                    </tr>
                                    <!--<tr>
                                            <td><a class="text-blue" style="cursor:pointer;">Papelera</a></td>
                                        </tr>-->
                                </table>
                            </div>
                            <div class=" backPanel headerPanel">
                                <div class="backPanel iconPinUp"
                                     onclick="_slideBarLayer(this,'panelCrear')">Crear</div>
                            </div>
                            <div id="panelCrear" class="backPanel contentBarLayer"
                                 style="display: block;">
                                <table>
                                    <tr>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </td>
                    <td id="showhide" width="10px" class="showHide"><a
                            onclick="_sliderFadeBarLayer()">
                            <div id="iconSlider" class="slider icon sliderIconUp"></div>
                        </a></td>
                    <!--  Aqui Comienza el contenido de mi pagina. -->
                    <td width="100%">
                        <div style="width: 100%; height: 100%;">
                            <!--Variables Ocultas -->
                            <input type="hidden" id="hddCodigo"/>
                            <!--Fin-->
                            <div class="row">
                                <div class="row-title">Creacion de Url</div>
                            </div>
                            <div class="row">
                                <div class="row-item"><label>Ingrese Url</label></div>
                                <div class="row-item"><input type="text" id="txtUrl" /></div>
                            </div>
                            <div class="row">
                                <div class="row-item"><label>A que Menu Pertenece</label></div>
                                <div class="row-item"><select id="cboMenu"></select></div>
                            </div>
                            <div class="row">
                                <div clas="row-item"><button id="btnGrabar" class="button">Grabar Url</button></div>
                            </div>
                            <div class="row">
                                <table id="listaMenus" cellpadding="0" cellspacing="0"></table>
                                <div id="eventMenu"></div>
                            </div>
                        </div>
                    </td>
                    <!--  Aqui Termina el contenido de mi pagina. -->
                    <td class="showHide ui-widget-header">
                        <div style="width:10px;"></div>
                    </td>
                </tr>
            </table>
            <div
                style="width: 100%; height: 20px; background-color: #F0E8D9; border: 0 none;"></div>
        </div>
    </body>
</html>
