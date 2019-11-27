<?php
date_default_timezone_set('America/Lima');
error_reporting(E_ALL);


ini_set('include_path', ini_get('include_path').';C:/xampp/htdocs/COB_ANDINA/phpincludes/phpexcel/Classes/');
require_once 'C:/xampp/htdocs/COB_ANDINA/phpincludes/phpexcel/Classes/PHPExcel.php';/** PHPExcel_Writer_Excel2007 */
require_once 'C:/xampp/htdocs/COB_ANDINA/phpincludes/phpexcel/Classes/PHPExcel/Writer/Excel2007.php';
require_once 'C:/xampp/htdocs/COB_ANDINA/phpincludes/phpexcel/Classes/PHPExcel/IOFactory.php';

require_once 'C:/xampp/htdocs/COB_ANDINA/conexion/config.php';
require_once 'C:/xampp/htdocs/COB_ANDINA/conexion/MYSQLConnectionPDO.php';
require_once 'C:/xampp/htdocs/COB_ANDINA/factory/FactoryConnection.php';
require_once 'C:/xampp/htdocs/COB_ANDINA/includes/class.phpmailer.php';
require_once 'C:/xampp/htdocs/COB_ANDINA/includes/class.smtp.php';

$factoryConnection = FactoryConnection::create('mysql');
$connection = $factoryConnection->getConnection();

$columna=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ");

$font = array(
'font'  => array(
    'bold'  => true,
    'color' => array('rgb' => '000000'),
    'size'  => 8,
    'name'  => 'Arial'
));
$title = array(
'font'  => array(
    'bold'  => true,
    'color' => array('rgb' => '000000'),
    'size'  => 13,
    'name'  => 'Verdana'
));
$font_cabe_blue = array(
'font'  => array(
    'bold'  => true,
    'color' => array('rgb' => '002060'),
    'size'  => 10,
    'name'  => 'Verdana'
));
$font_header = array(
'font'  => array(
    'bold'  => true,
    'color' => array('rgb' => '002060'),
    'size'  => 8,
    'name'  => 'Verdana'
));
$font_empresa_blue = array(
'font'  => array(
    'bold'  => true,
    'color' => array('rgb' => '1F4E78'),
    'size'  => 10,
    'name'  => 'Verdana'
));
$font_subtotal = array(
'font'  => array(
    'bold'  => true,
    'color' => array('rgb' => '000000'),
    'size'  => 10,
    'name'  => 'Verdana'
));
$border_mefium = array(
  'borders' => array(
      'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_MEDIUM  
      )
));
$border_thin = array(
  'borders' => array(
      'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN  
      )
    )
);

$fondo_amarillo = array(
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'FFFF00')
    )
);

$fondo_celeste = array(
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'C1EFFF')
    )
);
$fondo_morado_claro = array(
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'D9E1F2')
    )
);
$fondo_celeste_claro = array(
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'DDEBF7')
    )
);

$fondo_rojo_claro = array(
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'FFE1E1')
    )
);

$fondo_verde_claro = array(
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'D4FDCF')
    )
);

$fecha_envio=date('Y-m-d H:i:s');

#VALIDA SI HAY PROGRAMACION PARA ESTADO DE CUENTA

$sqlasunto="	SELECT 
				idcorreo_asunto,
				asunto,
				cuerpo,
				fecha_envio,
				estado 
				FROM 
				ca_correo_asunto 
				WHERE 
				estado=1 AND 
				idcorreo_asunto=1 AND
				DATE(fecha_envio)=DATE(NOW())";
$prasunto = $connection->prepare($sqlasunto);
$prasunto->execute();
$dataasunto=$prasunto->fetchAll(PDO::FETCH_ASSOC);

if(!empty($dataasunto) AND count($dataasunto)==1){
	
	# RECORRE CADA CLIENTE CON DEUDA

	$cli_deuda="SELECT
				cu.codigo_cliente AS 'CODIGO_CLIENTE',
				(SELECT cli.razon_social FROM ca_cliente cli WHERE  cli.codigo=detcu.codigo_cliente) AS 'RAZON_SOCIAL',
				(SELECT cli.numero_documento FROM ca_cliente cli WHERE  cli.codigo=detcu.codigo_cliente) AS 'NUMERO_DOCUMENTO',
				'' AS 'DIRECION',
				CONCAT(detcu.dato1,' ',detcu.dato6) AS 'VENDEDOR'
				FROM
				ca_cuenta cu
				INNER JOIN ca_detalle_cuenta detcu ON detcu.idcuenta=cu.idcuenta
				INNER JOIN ca_cliente_cartera clicar ON cu.idcliente_cartera=clicar.idcliente_cartera
				INNER JOIN ca_cartera car ON car.idcartera=clicar.idcartera
				INNER JOIN ca_campania cam ON cam.idcampania=car.idcampania
				INNER JOIN ca_servicio serv ON serv.idservicio=cam.idservicio
				WHERE
				serv.idservicio=1 AND
				cam.idcampania=1 AND
				car.idcartera=1 AND
				cu.estado=1 AND
				detcu.codigo_cliente='20268784625'
				GROUP BY detcu.codigo_cliente";
	$prcli_deuda = $connection->prepare($cli_deuda);
	$prcli_deuda->execute();
	$datacli_deuda=$prcli_deuda->fetchAll(PDO::FETCH_ASSOC);

	$xls = new PHPExcel();
	$xls->setActiveSheetIndex(0)->setTitle("DOCUMENTOS");

	for ($i=0; $i <=count($datacli_deuda)-1 ; $i++) {

		$sqldocumentos="	SELECT
							(SELECT emp.codigo FROM ca_empresa emp WHERE emp.nom_cruze=detcu.dato2) AS 'CODIGO_EMPRESA',
							(SELECT emp.nombre FROM ca_empresa emp WHERE emp.nom_cruze=detcu.dato2 ) AS 'EMPRESA',
							(SELECT cli.razon_social FROM ca_cliente cli WHERE cli.codigo=detcu.codigo_cliente) AS 'CLIENTE',
							(SELECT cli.numero_documento FROM ca_cliente cli WHERE cli.codigo=detcu.codigo_cliente) AS 'RUC',
							'' AS 'DIRECCION',
							detcu.dato1 AS 'CODIGO_VENDEDOR',
							detcu.dato6 AS 'VENDEDOR',
							detcu.dato8 AS 'TD',
							detcu.codigo_operacion AS 'SERIE_DOCUMENTO',
							DATE_FORMAT(detcu.fecha_emision,'%d/%m/%Y') AS 'FECHA_EMISION',
							DATE_FORMAT(detcu.fecha_vencimiento,'%d/%m/%Y') AS 'FECHA_VENCIMIENTO',
							detcu.moneda AS 'MONEDA',
							detcu.total_deuda AS 'IMPORTE',
							detcu.saldo_capital AS 'SALDO',
							detcu.dato11 AS 'DIAS_PLAZO',
							detcu.dato22 AS 'EST_LETR',
							detcu.dato23 AS 'BANCO',
							detcu.dato24 AS 'NUM_COBRANZA'
							FROM
							ca_cuenta cu
							INNER JOIN ca_detalle_cuenta detcu ON detcu.idcuenta=cu.idcuenta
							INNER JOIN ca_cliente_cartera clicar ON cu.idcliente_cartera=clicar.idcliente_cartera
							INNER JOIN ca_cartera car ON car.idcartera=clicar.idcartera
							INNER JOIN ca_campania cam ON cam.idcampania=car.idcampania
							INNER JOIN ca_servicio serv ON serv.idservicio=cam.idservicio
							WHERE
							serv.idservicio=1 AND
							cam.idcampania=1 AND
							car.idcartera=1 AND
							cu.estado=1 AND
							detcu.codigo_cliente='$codigo_cliente'
							ORDER BY 1 ASC
							";
		$prdocumentos = $connection->prepare($sqldocumentos);
		$prdocumentos->execute();

		$cod="";
		$nroreg=0;
		$caisacsoles=0;$caisacdolares=0;
		$andexsoles=0;$andexdolares=0;
		$semillassoles=0;$semillasdolares=0;
		$cantcaisac=0;
		$cantandex=0;
		$cantsemillas=0;
		$totaldolares=0;
		$totalsoles=0;
		$cantreg=$prdocumentos->rowCount();

		while($datos_muestra=$prdocumentos->fetch(PDO::FETCH_ASSOC)) {
			
		}

	}
}
?>