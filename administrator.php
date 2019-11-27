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
    <link type="text/css" rel="stylesheet" href="includes/jquery-ui-1.8/themes/humanity/jquery-ui.css" />
    <link type="text/css" rel="stylesheet" href="css/css-cobrast.css" />
    <script type="text/javascript" src="js/includes/jquery-1.4.2.js" ></script>
    
    <script type="text/javascript" src="includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.core.min.js" ></script>
	<script type="text/javascript" src="includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.widget.min.js" ></script>
	<script type="text/javascript" src="includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.ui.button.min.js" ></script>
    <script type="text/javascript" src="includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.effects.core.min.js" ></script>
	<script type="text/javascript" src="includes/jquery-ui-1.8.1/development-bundle/ui/minified/jquery.effects.pulsate.min.js" ></script>
    
    <script type="text/javascript" src="js/templates.js" ></script>
 	<script type="text/javascript" src="js/validacion.js" ></script>
    <script type="text/javascript" src="js/IndexDAO.js" ></script>
    <script type="text/javascript" src="js/index.js" ></script>
    <body class="ui-widget-content" style="border:0 none">
		<div class="Login">
        	<table cellpadding="0" border="0" cellspacing="0" >
                <tr>
                    <td align="center">
                        <h1 class="h1Login">COBRAST</h1>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <div id="layerMessage" align="center"></div>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                    	<div class="ui-widget-content ui-corner-all" style="padding:25px 25px; margin: 5px;" align="center">
                            <table border="0" cellpadding="3">
                               	<tr>
                                    <td align="right"><label class="labelLogin">Usuario:</label></td>
                                    <td align="left"><input type="text" id="txtUsuario" name="txtUsuario" class="cajaLogin" /></td>
                                </tr>
                                <tr>
                                    <td align="right"><label class="labelLogin">Password:</label></td>
                                    <td align="left"><input type="password" id="txtPsw" name="txtpsw" class="cajaLogin" /></td>
                                </tr>
                                <tr>
                                    <td align="center" colspan="2">
                                        <button id="btnIndex" onClick="login_admin()" >Iniciar Sesion</button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
		</div>
        <div id="layerOverlay" class="ui-widget-overlay" style="display: none;"></div>
        <div id="layerLoading" style="position:absolute ;left: 50%;top: 45%; width: 100px; font-weight: bold; font-size: 18px; color: #AFAFAF; z-index: 100;display: none;">Loading...</div>
        <script type="text/javascript">
			    $('#btnIndex').button();
				/***************/
				$('#txtUsuario').focus();
        </script>
    </body>
</html>