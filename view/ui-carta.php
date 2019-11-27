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

<script type="text/javascript" src="../js/js-cobrast.js" ></script>
<script type="text/javascript" src="../js/templates.js"  ></script>
<script type="text/javascript" src="../js/CartaDAO.js"  ></script>
<script type="text/javascript" src="../js/js-carta.js"  ></script>
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
                    <div align="center">
                        <table>
                            <tr>
                                <td align="center">
<?php
if (isset($_SESSION['cobrast'])) {
    if (isset($_SESSION['cobrast']['avatar'])) {
        if (trim($_SESSION['cobrast']['avatar']) != '') {
            ?><img onclick="$('#_dialogEditAvatar').dialog('open')" src="../img/avatars/<?= trim($_SESSION['cobrast']['avatar']) ?>" /><?php
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
                    <div class=" backPanel headerPanel">
                        <div class="backPanel iconPinUp" onClick="_slideBarLayer(this,'panelMenu')">Menu</div>
                    </div>
                    <div id="panelMenu" class="backPanel contentBarLayer" style="display:block;" >
                        <div style="width:100%;">
                            <div style="margin-left:20px; margin-bottom:2px;"><a class="text-blue" onClick="_display_panel('panelMainHome')">Home</a></div>
                        </div>       
                    </div>
                    <div class=" backPanel headerPanel">
                        <div class="backPanel iconPinUp" onClick="_slideBarLayer(this,'panelCalendario')" >Calendario</div>
                    </div>
                    <div align="center" id="panelCalendario" style="padding:3px 0;display:block;">
                        <div id="layerDatepicker"></div>
                    </div>
                </div>
            </td>
            <td id="showhide" width="10px" class="showHide ui-widget-header">
                <a onClick="_sliderFadeBarLayer()">
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
                        <table>
                            <tr>
                                <td>
                                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                        <tr>
                                            <td class="ui-widget-header ui-corner-all">
                                                <div>
                                                    <table>
                                                        <tr>
                                                            <td>Campa&ntilde;a</td>
                                                            <td><select accesskey="c" id="cbCampaniaCarta" onchange="load_cartera_by_id( this.value, 'cbCarteraCarta' )" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                            <td>Cartera</td>
                                                            <td><select id="cbCarteraCarta" onchange="listar_data_cartera(this.value)" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                            <td><button onclick=" window.location.href = '../rpt/word/carta_modelo_movil_pj.php?cartera='+$('#cbCarteraCarta').val()+'&servicio='+$('#hdCodServicio').val()+'&idfinal='+$('#cbEstadoCarta').val()+'&departamento='+$('#cbDepartamentoCarta').val()" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-extlink"></span></button></td>
                                                        </tr>
                                                    </table>
                                                    <table>
                                                        <tr>
                                                            <td>Estado</td>
                                                            <td><select style="width:300px;" id="cbEstadoCarta" class="combo" ><option value="0">--Seleccione--</option></select></select></td>
                                                            <td>Departamento</td>
                                                            <td><select id="cbDepartamentoCarta" class="combo"><option value="0">--Seleccione--</option></select></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                    <div style="width:800px;background-color:#FFF;" align="center">
                                        <table style="width:600px;">
                                            <tr>
                                                <td>
                                                    <img width="120" height="50"  src="../img/cartas/gestion_legal.JPG" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="height:40px;"></td>
                                            </tr>
                                            <tr>
                                                <td><p>San Isidro, 20 de Enero del 2011.</p></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <p style="margin:0px;">Se&ntilde;or(a):&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;orden &nbsp;&nbsp;&nbsp;&laquo;orden&raquo;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p> 
                                                                    <p style="margin:0px;"><strong><em>&laquo;NOMBRE&raquo;</em></strong><strong><em></em></strong></p> 
                                                                    <p style="margin:0px;">&laquo;DIRECCION&raquo;</p> 
                                                                    <p style="margin:0px;"><span style="text-decoration: underline;">&laquo;DISTRITO&raquo;</span>.-</p> 
                                                                    <p></p> 
                                                                    <h3 style="margin:0px;">Tel&eacute;fono(s)&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &laquo;CELULAR&raquo;&nbsp;&nbsp;&nbsp;&nbsp;</h3> 
                                                                    <h3 style="margin:0px;">Anexo(s):&nbsp;&nbsp; &laquo;NROINS&raquo;&nbsp;</h3> 
                                                                    <p style="margin:0px;font-size:17px;"><strong>Deuda Total: &laquo;MONEDA&raquo;&nbsp; &laquo;DEUDA&raquo;</strong></p>
                                                                </div>
                                                            </td>
                                                            <td valign="bottom">
                                                                <div style="width:200px;border:1px solid #000;padding:1px;">
                                                                    <div style="border:4px solid #000;font-size:16px;font-weight:bold;text-align:center;" >
                                                                    	CANCELE Y EVITE LA BAJA FINAL DE SU LINEA.
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <p>De nuestra consideraci&oacute;n:</p> 
                                                        <p>&nbsp;</p> 
                                                        <p>Como es de&nbsp; su conocimiento usted mantiene una deuda con <strong>Telef&oacute;nica M&oacute;viles SA., </strong>por el servicio telef&oacute;nico de la referencia, la cual asciende a <strong>&laquo;MONEDA&raquo;</strong><strong> </strong><strong>&laquo;DEUDA&raquo;</strong><strong></strong></p> 
                                                        <p><strong>&nbsp;</strong></p> 
                                                        <p>Al ver el escaso inter&eacute;s que tiene con saldar la misma, cumplimos con informarle que <strong><span style="text-decoration: underline;">su n&uacute;mero celular est&aacute; programado para&nbsp; la baja final de servicio</span></strong>, 
                                                            <strong><span style="text-decoration: underline;">lo que significa la PERDIDA DE DICHO N&Uacute;MERO</span></strong>.&nbsp; Adicionalmente, le informamos que El art&iacute;culo 7.1 de la Ley No. 27489 -Ley que regula las centrales privadas de informaci&oacute;n de riesgos y de protecci&oacute;n al titular de la informaci&oacute;n- dispone que las centrales de riesgo podr&aacute;n recolectar informaci&oacute;n de riesgos para sus bancos de datos tanto de fuentes p&uacute;blicas como privadas.&nbsp; Siendo esto as&iacute;, <strong><span style="text-decoration: underline;">cumplimos con comunicarle que en virtud de los contratos suscritos con las respectivas centrales de riesgo, nos encontramos en la obligaci&oacute;n de entregar informaci&oacute;n mensual sobre aquellos clientes que mantengan deudas pendientes con la empresa.</span></strong></p> <p></p> <p><strong>Cancele el total con un descuento de&nbsp; $ &nbsp;</strong><strong>&laquo;descto&raquo;</strong><strong>&nbsp;&nbsp; &nbsp;y pague $ </strong><strong>&laquo;saldo&raquo;</strong><strong>.</strong></p> <p><strong>&nbsp;</strong></p> <p><strong>Financie (*) su deuda con 40% de inicial, solo para recepci&oacute;n de llamadas y con 50% de inicial servicio total. Consultas al 5139060 anexo 2056 &ndash; 2029 &ndash; 2041 o al Email : mtoledo@hdec.pe</strong></p>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <table>
                                                        <tr>
                                                            <td valign="top"><p>Atentamente,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></td>
                                                            <td><img width="200" height="100" src="../img/cartas/firma_movil.JPG" /></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="border:1px solid #000;padding:1px;">
                                                        <div style="border:4px solid #000;" align="center">
                                                            <p><strong><span style="text-decoration: underline;">CENTROS DE PAGO MOVISTAR</span></strong></p> 

                                                            <ul style="margin:0px;"> 
                                                                <li>
                                                                    <strong>Camino Real 208 1er piso (Financiamiento) San Isidro, Srta. Nieves Napa.</strong>
                                                                </li> 
                                                            </ul> 
                                                            <label>Horario de Atenci&oacute;n: Lunes a Viernes de 9:00am a 6:00 pm&nbsp; - S&aacute;bados: 9:00&nbsp; a 1:00 pm.</label> 
                                                            <ul style="margin:0px;"> 
                                                                <li><strong>Javier Prado Este 3190 1er piso (Financiamiento) San Borja, Sr. Edgar G&oacute;mez.</strong></li> 
                                                            </ul> <label>Horario de Atenci&oacute;n: <strong>Lunes a Viernes de 9:00am a 8:00 pm</strong>- S&aacute;bados: 9:00&nbsp; a 1:00 pm.</label> 
                                                            <ul style="margin:0px;"> 
                                                                <li><strong>Jr. Mantaro S/N (Ex Chamaya) - San Miguel (a la espalda de Plaza San Miguel). </strong></li> 
                                                            </ul> 
                                                            <label>Lunes a Domingo de 9.00 am a 9:00pm.</label> 
                                                            <label><strong>Av</strong><strong>. Alfredo Mendiola 3698 Tda. 80 &ndash; Independencia.</strong></label> 
                                                            <label>Lunes a Viernes 9:00 am a 6:00pm</label> 
                                                            <label>S&aacute;bados 9:00 am a 6:00 pm.<strong></strong></label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td align="center">
                                                    <div>
                                                        <p style="margin:0px;"><strong>(1)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SE ACEPTAN TARJETAS DE CR&Eacute;DITO</strong></p> 
                                                        <p style="margin:0px;">S&iacute;rvase dejar sin efecto el presente documento si al momento de su recepci&oacute;n Ud. ya hubiese cancelado la deuda.</p> 
                                                        <div> 
                                                            <p style="margin:0px;">Le agradeceremos no&nbsp; entregar dinero al portador de la presente ya que no se encuentra autorizado.</p> 
                                                        </div> 
                                                        <p style="margin:0px;"><strong>(*) No debe tener Financiamiento pendiente de pago, Deudas mayores de U$40.00</strong></p>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr><td><hr /></td></tr>
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
