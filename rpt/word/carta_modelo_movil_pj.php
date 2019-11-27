<?php

	header("Content-Type: application/vnd.ms-word");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("content-disposition: attachment;filename=Carta_Modelo_Movil_PJ.doc");
	
	require_once '../../conexion/config.php';
    require_once '../../conexion/MYSQLConnectionMYSQLI.php';
    require_once '../../conexion/MYSQLConnectionPDO.php';

    require_once '../../factory/DAOFactory.php';
    require_once '../../factory/FactoryConnection.php';
	
	$cartera = $_GET['cartera'];
	$servicio = $_GET['servicio'];
	$idfinal = $_GET['idfinal'];
	$departamento = $_GET['departamento'];
	$param = array();
	$sql = "";
	$filtroDepartamento = "";
	if( $departamento == '0' ) {
		$filtroDepartamento = "";
	}else{
		$filtroDepartamento = " AND TRIM( dir.departamento ) = '".$departamento."' ";
	}
	
	if( $idfinal == '0' ) {
		
		$sql = " SELECT CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS 'CLIENTE',
			dir.direccion AS 'DIRECCION', dir.distrito AS 'DISTRITO',
			cu.telefono AS 'TELEFONO', cu.numero_cuenta AS 'NUMERO_CUENTA',
			cu.moneda AS 'MONEDA',cu.total_deuda AS 'DEUDA'
			FROM ca_direccion dir INNER JOIN ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cuenta cu
			ON cu.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente = cli.idcliente AND cli.idcliente = dir.idcliente
			WHERE cu.idcartera = :cartera_cuenta AND clicar.idcartera = :cliente_cartera AND cli.idservicio = :servicio AND dir.idcartera = :cartera_direccion AND dir.idtipo_referencia = 3 ".$filtroDepartamento." ";
		$param = array( ':cartera_cuenta'=>$cartera,':cliente_cartera'=>$cartera,':servicio'=>$servicio,':cartera_direccion'=>$cartera );
	}else{
		
		$sql = " SELECT CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS 'CLIENTE',
			dir.direccion AS 'DIRECCION', dir.distrito AS 'DISTRITO',
			cu.telefono AS 'TELEFONO', cu.numero_cuenta AS 'NUMERO_CUENTA',
			cu.moneda AS 'MONEDA',cu.total_deuda AS 'DEUDA'
			FROM ca_direccion dir INNER JOIN ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cuenta cu INNER JOIN ca_transaccion tran
			ON tran.idtransaccion = clicar.id_ultima_llamada AND cu.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente = cli.idcliente AND cli.idcliente = dir.idcliente
			WHERE cu.idcartera = :cartera_cuenta AND clicar.idcartera = :cliente_cartera AND cli.idservicio = :servicio AND dir.idcartera = :cartera_direccion AND dir.idtipo_referencia = 3 AND tran.idfinal = :final ".$filtroDepartamento." ";
		$param = array( ':cartera_cuenta'=>$cartera,':cliente_cartera'=>$cartera,':servicio'=>$servicio,':cartera_direccion'=>$cartera,':final'=>$idfinal);
	}
	
	
			
	$factoryConnection= FactoryConnection::create('mysql');	
	$connection = $factoryConnection->getConnection();
	
	$pr = $connection->prepare($sql);
	/*$pr->bindParam(1,$cartera,PDO::PARAM_INT);
	$pr->bindParam(2,$cartera,PDO::PARAM_INT);
	$pr->bindParam(3,$servicio,PDO::PARAM_INT);
	$pr->bindParam(4,$cartera,PDO::PARAM_INT);*/
	$pr->execute($param);
	while( $row = $pr->fetch(PDO::FETCH_ASSOC) ) {
		?>
        <div style="width:800px;background-color:#FFF;font-size:9px;" align="center">
            <table style="width:600px;">
                <tr>
                    <td>
                        <img width="120" height="50"  src="../../img/cartas/gestion_legal.JPG" />
                    </td>
                </tr>
                <tr>
                    <td style="height:20px;"></td>
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
                                        <p style="margin:0px;">Se&ntilde;or(a):&nbsp;
                                        <p style="margin:0px;"><strong><?php echo $row['CLIENTE'] ?></strong></p> 
                                        <p style="margin:0px;">DIRECCION : <?php echo $row['DIRECCION'] ?></p> 
                                        <p style="margin:0px;"><span>DISTRITO : </span><?php echo $row['DISTRITO'] ?></p> 
                                        <p></p> 
                                        <h3 style="margin:0px;">Tel&eacute;fono(s)&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $row['TELEFONO'] ?></h3> 
                                        <h3 style="margin:0px;">Anexo(s):&nbsp;&nbsp;<?php echo $row['NUMERO_CUENTA'] ?></h3> 
                                        <p style="margin:0px;font-size:16px;"><strong>Deuda Total: <?php echo $row['MONEDA'] ?>;&nbsp; <?php echo $row['DEUDA'] ?></strong></p>
                                    </div>
                                </td>
                                <td valign="bottom" style="padding-left:10px;">
                                    <table cellpadding="0" cellspacing="0" border="1" style="border:3px solid #000;" >
                                    	<tr>
                                        	<td style="font-size:20px;font-weight:bold;text-align:center;width:200px;padding:5px;">
                                            CANCELE Y EVITE LA BAJA FINAL DE SU LINEA.
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="font-size:14px;text-align:justify;">
                            <p>De nuestra consideraci&oacute;n:</p> 
                            <p>Como es de&nbsp; su conocimiento usted mantiene una deuda con <strong>Telef&oacute;nica M&oacute;viles SA., </strong>por el servicio telef&oacute;nico de la referencia, la cual asciende a <strong><?php echo $row['MONEDA'] ?></strong><strong> </strong><strong><?php echo $row['DEUDA']?></strong><strong></strong></p> 
                            <p>Al ver el escaso inter&eacute;s que tiene con saldar la misma, cumplimos con informarle que <strong><span style="text-decoration: underline;">su n&uacute;mero celular est&aacute; programado para&nbsp; la baja final de servicio</span></strong>, 
                            <strong><span style="text-decoration: underline;">lo que significa la PERDIDA DE DICHO N&Uacute;MERO</span></strong>.&nbsp; Adicionalmente, le informamos que El art&iacute;culo 7.1 de la Ley No. 27489 -Ley que regula las centrales privadas de informaci&oacute;n de riesgos y de protecci&oacute;n al titular de la informaci&oacute;n- dispone que las centrales de riesgo podr&aacute;n recolectar informaci&oacute;n de riesgos para sus bancos de datos tanto de fuentes p&uacute;blicas como privadas.&nbsp; Siendo esto as&iacute;, <strong><span style="text-decoration: underline;">cumplimos con comunicarle que en virtud de los contratos suscritos con las respectivas centrales de riesgo, nos encontramos en la obligaci&oacute;n de entregar informaci&oacute;n mensual sobre aquellos clientes que mantengan deudas pendientes con la empresa.</span></strong></p> <p></p> <p><strong>Cancele el total con un descuento de&nbsp; $ &nbsp;</strong><strong>&laquo;descto&raquo;</strong>
                            <strong>&nbsp;&nbsp; &nbsp;y pague $ </strong><strong>&laquo;saldo&raquo;</strong><strong>.</strong>
                            <strong>&nbsp;</strong>
                            </p> <p><strong>Financie (*) su deuda con 40% de inicial, solo para recepci&oacute;n de llamadas y con 50% de inicial servicio total. Consultas al 5139060 anexo 2056 &ndash; 2029 &ndash; 2041 o al Email : mtoledo@hdec.pe</strong></p>
                        </div>
                    </td>
                </tr>
                 <tr>
                    <td>
                        <table>
                            <tr>
                                <td valign="top"><p>Atentamente,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></td>
                                <td><img width="200" height="100" src="cartas/firma_movil.jpeg" /></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                       	<table cellpadding="0" cellspacing="0" border="1" style="border:3px solid #000;font-size:12px;" >
                        	<tr>
                            	<td style="padding:3px 40px;">
                                    <strong><span style="text-decoration: underline;font-size:16px;">CENTROS DE PAGO MOVISTAR</span></strong>
                                    <table cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td style="font-size:13px;">
                                            <li>
                                            <strong style="font-size:13px;">Camino Real 208 1er piso (Financiamiento) San Isidro, Srta. Nieves Napa.</strong>
                                            </li> 
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:13px;padding-left:10px;">Horario de Atenci&oacute;n: Lunes a Viernes de 9:00am a 6:00 pm&nbsp; - S&aacute;bados: 9:00&nbsp; a 1:00 pm.</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:13px;"><li style="font-size:12px;"><strong>Javier Prado Este 3190 1er piso (Financiamiento) San Borja, Sr. Edgar G&oacute;mez.</strong></li> </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:13px;padding-left:10px;">Horario de Atenci&oacute;n: <strong>Lunes a Viernes de 9:00am a 8:00 pm</strong>- S&aacute;bados: 9:00&nbsp; a 1:00 pm.</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:13px;"><li style="font-size:12px;"><strong>Jr. Mantaro S/N (Ex Chamaya) - San Miguel (a la espalda de Plaza San Miguel). </strong></li> </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:13px;padding-left:10px;">Lunes a Domingo de 9.00 am a 9:00pm. </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:13px;padding-left:10px;"><strong>Av</strong><strong>. Alfredo Mendiola 3698 Tda. 80 &ndash; Independencia.</strong> </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:13px;padding-left:10px;">Lunes a Viernes 9:00 am a 6:00pm </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:13px;padding-left:10px;">S&aacute;bados 9:00 am a 6:00 pm.<strong></strong></td>
                                        </tr>
                                    </table>
	                            </td>
                            </tr>
                        </table>
                    </td>
                </tr>
               
                <tr>
                    <td align="center">
                        <div>
                            <p style="margin:0px;"><strong>(1)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SE ACEPTAN TARJETAS DE CR&Eacute;DITO</strong></p> 
                            <p style="margin:0px;font-size:10px;">S&iacute;rvase dejar sin efecto el presente documento si al momento de su recepci&oacute;n Ud. ya hubiese cancelado la deuda.</p> 
                            <div> 
                            <p style="margin:0px;font-size:10px;">Le agradeceremos no&nbsp; entregar dinero al portador de la presente ya que no se encuentra autorizado.</p> 
                            </div> 
                            <p style="margin:0px;font-size:10px;"><strong>(*) No debe tener Financiamiento pendiente de pago, Deudas mayores de U$40.00</strong></p>
                        </div>
                    </td>
                </tr>
                <tr><td><hr /></td></tr>
            </table>
        </div>
		<?php
	}
	

?>
