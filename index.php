<?php
	session_start();
	//var_dump($_SESSION['cobrast']);
	if( isset($_SESSION['cobrast']) && $_SESSION['cobrast']['activo']==1 ) {
		header('Location: view/ui-cobrast.php?menu=home');
	}
	
?>
<html>
	<head>
    	<title>Acceso a Cobrast</title>

    </head>
   
    <link type="text/css" rel="stylesheet" href="css/css-cobrast.css" />
    <link type="text/css" rel="stylesheet" href="includes/font-awesome-4.3.0/css/font-awesome.css" />
    <script type="text/javascript" src="js/includes/jquery-1.4.2.js" ></script>
    <link rel="shortcut icon" href="img/andina.ico" type="image/x-icon">
    
    <script type="text/javascript" src="includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.core.min.js" ></script>
	<script type="text/javascript" src="includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.widget.min.js" ></script>
	<script type="text/javascript" src="includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.button.min.js" ></script>
    <script type="text/javascript" src="includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.effects.core.min.js" ></script>
    <script type="text/javascript" src="includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.effects.slide.min.js" ></script>
    <script type="text/javascript" src="includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.effects.blind.min.js" ></script>
    <!-- <script src="includes/theme/themeswitchertool.js" type="text/javascript"></script> -->
    <link rel="stylesheet" href="includes/jquery-ui.css" type="text/css">
    <script type="text/javascript" src="js/templates.js" ></script>
 	<script type="text/javascript" src="js/validacion.js" ></script>
    <script type="text/javascript" src="js/indexDAO.js" ></script>
    <script type="text/javascript" src="js/index.js" ></script>

    
    <body class="ui-widget-content" style="border:0 none;background-color:#C5FF96">
        <div class="Login">
        	<table cellpadding="0" border="0" cellspacing="0" >
                <tr>
                    <td colspan="2" align="center"><img src="img/logo-grupo.png" /></td>
                </tr>
                <td align="center">
                    <span style=" text-shadow: 0px 8px rgba(0, 0, 0, 0.1);;cursor:default;-moz-user-select: none;font-size: 50px; font-family: Verdana; font-weight: bold; color: rgb(255, 255, 255);" class="textLogo "></span>
                </td>
                <tr>
                    <td align="right">
                    	<div class=" ui-corner-all" style="background-color:#2d3e50;padding:25px 25px; margin: 5px;box-shadow: 0 0 5px #404c6c;border: 3px solid #212e3b;" align="center">
                            <table border="0" cellpadding="3">
                                
                               	<tr>
                                    <td align="center"><span style="color:#C4C4C4;cursor:default;-moz-user-select: none;" class="fa fa-user fa-2x "></span></td>
                                    <td align="left"><input type="text" id="txtUsuario" placeholder="Usuario" name="txtUsuario" class="cajaLogin" style="text-align:center;text-align: center;border: 1px solid rgba(121, 187, 238, 0.75) !important;" /></td>
                                </tr>
                                <tr>
                                    <td align="center"><span style="color:#C4C4C4;cursor:default;-moz-user-select: none;display:block" class="fa fa fa-key fa-2x "></span></td>
                                    <td align="left"><input type="password" placeHolder="Password" id="txtPsw" name="txtpsw" class="cajaLogin" style="text-align:center;text-align: center;border: 1px solid rgba(121, 187, 238, 0.75) !important;" /></td>
                                </tr>
                                <tr>
                                    <td align="right"><label class="labelLogin" style="width: 39px !important;-moz-user-select: none;">Servicio:</label></td>
                                    <td align="left"><select class="selectLogin" id="cbServicio" style="text-align:center;text-align: center;border: 1px solid rgba(121, 187, 238, 0.75) !important;" ><option value="0">--Seleccione--</option></select></td>
                                </tr>
                                <!-- <tr>
                                    <td align="center" colspan="2">
                                        <button  id="btnIndex" onClick="login()" style="cursor: pointer; border-radius: 6px; font-family: Roboto; padding: 2px; margin-top: 14px; margin-left: 17%; width: 83%;" >Iniciar Sesi&oacute;n</button>
                                    </td>
                                </tr> -->
                                <tr>
                                    <td  colspan="2">
                                        <div class="boton_estilo fondo_gradiente_azul" style="width:240px;left:47px;" id="btnIndex" onClick="login()">
                                            <img src="img/login.png" width="20" class="boton_imagen" style="position: absolute;left: 9px;top:3px;">
                                            <div class="lin_vet"></div> 
                                            <span style="float:left;position:absolute;top:4px;left: 80px;font-size:13px;color: #ffffff;-webkit-touch-callout: none;-webkit-user-select: none;-khtml-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;">Iniciar Sesi&oacute;n</span>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <div id="layerMessage" align="center" style="margin-top:15px"></div>
                    </td>
                </tr>
                <!--<tr>
                    <td align="center">
                        <div style="font-size: 10px; font-family: Arial,Helvetica,sans-serif;"><a href="#">�Has olvidado tu contrase&ntilde;a?</a></div>
                    </td>
                </tr>-->
            </table>
		</div>
        <div id="layerOverlay" class="ui-widget-overlay" style="display: none;"></div>
        <div id="layerLoading" style="position:absolute ;left: 50%;top: 45%; width: 100px; font-weight: bold; font-size: 18px; color: #AFAFAF; z-index: 100;display: none;">Loading...</div>
        <div class="submenuLogin" style="display:none;background: F4F0EC url(images/ui-bg_inset-soft_100_f4f0ec_1x100.png) repeat-x scroll 50% bottom; box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.25), 0px 0px 10px 0px rgba(0, 0, 0, 0.08);">
        	<ul>
                <div style="vertical-align: top; margin-bottom: -22px; margin-top: -22px; width: 100%; text-align: center;"><img style="width: 105px;" src="img/logo-hdec5.png"></div>
            	<li><a href="help.php">Acerca de </a></li>
                <li><a href="help.php">Prensa</a></li>
                <li><a href="help.php">Blog</a></li>
                <li><a href="help.php">Desarrolladores</a></li>
                <li><a href="help.php">Ayuda</a></li>
            </ul>
        </div>
        <script type="text/javascript">
			    indexDAO.servicio();
				/***************/
				//$('#btnIndex').button();
				/***************/
				$('#txtUsuario').focus();
                
                // $('#switcher').themeswitcher({initialText : 'Lista de Temas',buttonPreText:'Tema: ',loadTheme: "Humanity"});
                
        </script>
       
    </body>
</html>