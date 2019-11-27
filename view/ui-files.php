<?php
	session_start();
	
	//if( !isset($_SESSION['cobrast']) && $_SESSION['cobrast']['activo']!=1 ) {
//		header('Location: ../index.php');
//	}
	if( empty($_SESSION['cobrast']) || !isset($_SESSION['cobrast']) || $_SESSION['cobrast']['activo']!=1  ) {
		header('Location: ../index.php');
	}
	//var_dump($_SESSION['cobrast']);
?>
<title>Home</title>

<link type="text/css" rel="stylesheet" href="../includes/jquery-ui-1.8/themes/humanity/jquery-ui.css" />

<link type="text/css" rel="stylesheet" href="../css/css-cobrast.css" />

<script type="text/javascript" src="../js/includes/jquery-1.4.2.js" ></script>

<script type="text/javascript" src="../js/includes/jquery.upload-1.0.2.js" ></script>

<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.core.min.js" ></script>
<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.widget.min.js" ></script>
<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.datepicker.min.js" ></script>
<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.dialog.min.js" ></script>
<script type="text/javascript" src="../includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.button.min.js" ></script>

<script type="text/javascript" src="../js/js-cobrast.js" ></script>
<script type="text/javascript" src="../js/templates.js"  ></script>
<script type="text/javascript" src="../js/js-home.js"  ></script>
<script type="text/javascript" src="../js/js-files.js"  ></script>
<script type="text/javascript" src="../js/FilesDAO.js"  ></script>
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

<div style="width:100%; margin:0 auto;">
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
                    <strong style="margin-right: 5px;">Bienvenido: <?=$_SESSION['cobrast']['usuario'] ?></strong>
                    <strong style="margin-right: 5px;">Servicio: <?=$_SESSION['cobrast']['servicio'] ?></strong>
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
									if( $_SESSION['cobrast']['privilegio']=='administrador' ) {
										require_once('../menus/menu-sistemas.php');
									}else if( $_SESSION['cobrast']['privilegio']=='supervisor' ) {
										require_once('../menus/menu-supervisor.php');	
									}else if( $_SESSION['cobrast']['privilegio']=='operador' || $_SESSION['cobrast']['privilegio']=='gestor de campo' ){
                                        require_once('../menus/menu-operador.php'); 
                                    }else if( $_SESSION['cobrast']['privilegio']=='externo' ) {
                                        require_once('../menus/menu-externo.php');  
                                    }else{
                                        header('Location:../index.php');
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
            	<div id="barLayer" style="width:210px; display:block;">	
                	<div class=" backPanel headerPanel">
                    	<div class="backPanel iconPinUp" onclick="_slideBarLayer(this,'panelMenu')">Menu</div>
                    </div>
                    <div id="panelMenu" class="backPanel contentBarLayer" style="display:block;" >
                    	<div style="width:100%;">
                        	<div style="margin-left:20px; margin-bottom:2px;"><a class="text-blue" onClick="_display_panel('panelMainHome')">Home</a></div>
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
            	<div id="cobrastHOME" style="background-color:#FFFFFF; width:100%; height:100%; " >
                	<input type="hidden" id="hdCodUsuario" name="hdCodUsuario" value="<?=$_SESSION['cobrast']['idusuario']?>" />
                    <input type="hidden" id="hdCodServicio" name="hdCodServicio" value="<?=$_SESSION['cobrast']['idservicio']?>" />
                    <input type="hidden" id="hdCodUsuarioServicio" name="hdCodUsuarioServicio" value="<?=$_SESSION['cobrast']['idusuario_servicio']?>" />
                    <input type="hidden" id="hdNomServicio" name="hdNomServicio" value="<?=$_SESSION['cobrast']['servicio']?>" />
                	<div id="panelMainHome" class="ui-widget-content" style="border:0 none;width:100%;height:100%;" align="center">
                		<div class="ui-widget-header ui-corner-all" style="padding:2px;margin:2px;">
	                		<table>
	                			<tr>
	                				<td><button onclick="$('#layer_window_modal_create_directory').dialog('open');" title="CREAR DIRECTORIO" alt="CREAR DIRECTORIO">Crear Directorio</button></td>
	                				<!--<td><button title="ELIMINAR DIRECTORIO" alt="ELIMINAR DIRECTORIO">Eliminar Directorio</button></td>-->
	                				<td><button onclick="refresh_directory()" id="update_directory_btn" title="ACTUALIZAR" alt="ACTUALIZAR">Actualizar</button></td>
                                    <td>
                                        <form class="file_upload">
                                            <input type="file" id="_fl_upload_file_vf_" name="_fl_upload_file_vf_" />
                                            <button onclick="upload_file()" title="SUBIR ARCHIVO" alt="SUBIR ARCHIVO" type="button">Subir Archivo</button>
                                        </form>
                                    </td>
	                			</tr>
	                		</table>
                		</div>
                		<div>
                			<input type="hidden" id="router_directory" val="/" />
                			<div id="lb_router_directory" class="ui-state-highlight ui-corner-all" style="font-weight:bold;font-size:15px;text-align:left;padding:2px;margin:2px;" >/</div>
                            <div align="left" id="layer_back_directory" onclick="back_directory()" style="display:none;" ><span class="ui-icon ui-icon-arrowreturnthick-1-n"></span></div>
                			<!--<table>
                				<tr>
                					<td>/</td>
                				</tr>
                			</table>-->
                		</div>
                		<div style="margin:2px;">
                			<ul style="margin:0;padding:0;list-style:none;" class="ui-widget ui-helper-clearfix" id="table_directory" >
                			</ul>
                		</div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <div style="width:100%; height:20px; border:0 none;" class="ui-widget-header"></div>
</div>
<div id="layer_window_modal_create_directory" style="display:none">
	<table>
		<tr>
			<td>Ingrese Nombre:</td>
			<td><input id="txtNameDirectory" type="text" maxlength="200" style="padding:2px;height:20px;" class="ui-corner-all ui-widget-content" /></td>
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
<script type="text/javascript">
$(document).ready(function( ){

    $('button[alt="CREAR DIRECTORIO"]').button({icons:{primary:"ui-icon-folder-collapsed"}});
    //$('button[alt="ELIMINAR DIRECTORIO"]').button({icons:{primary:"ui-icon-trash"}});
    $('button[alt="SUBIR ARCHIVO"]').button({icons:{primary:"ui-icon-document"}});
    $('button[alt="ACTUALIZAR"]').button({icons:{primary:"ui-icon-refresh"}});
    
    $('#layer_window_modal_create_directory').dialog(
        {
            autoOpen : false,
            title : "Crear Directorio",
            modal : true,
            buttons : {
                    Cancel : function ( ) {
                        $(this).dialog("close");
                    },
                    Aceptar : function ( ) {
                        create_directory();
                    }
                }
        }
    );

    FilesDAO.read_directory( $('#router_directory').val(), "" );

});
</script>
