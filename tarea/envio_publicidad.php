<?php

date_default_timezone_set('America/Lima');
error_reporting(E_ALL);

require_once 'C:/xampp/htdocs/COB_ANDINA/conexion/config.php';
require_once 'C:/xampp/htdocs/COB_ANDINA/conexion/MYSQLConnectionPDO.php';
require_once 'C:/xampp/htdocs/COB_ANDINA/factory/FactoryConnection.php';
require_once 'C:/xampp/htdocs/COB_ANDINA/includes/class.phpmailer.php';
require_once 'C:/xampp/htdocs/COB_ANDINA/includes/class.smtp.php';

$factoryConnection = FactoryConnection::create('mysql');
$connection = $factoryConnection->getConnection();

$fecha_envio=date('Y-m-d H:i:s');

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
				idcorreo_asunto=3 AND
				DATE(fecha_envio)=DATE(NOW())";
$prasunto = $connection->prepare($sqlasunto);
$prasunto->execute();
$dataasunto=$prasunto->fetchAll(PDO::FETCH_ASSOC);

$speech=$dataasunto[0]['cuerpo'];
$idcorreo_asunto=$dataasunto[0]['idcorreo_asunto'];

if(!empty($dataasunto) AND count($dataasunto)==1){

		$sqlcor="	SELECT cor.idcorreo,cor.correo FROM ca_correo_envio cor WHERE fecha_envio IS NULL LIMIT 10";
		$prcor = $connection->prepare($sqlcor);
		$prcor->execute();

		while($datos_cor=$prcor->fetch(PDO::FETCH_ASSOC)) {

			$xidcorreo=$datos_cor['idcorreo'];
			$correo=$datos_cor['correo'];

			$layout = file_get_contents('C:/xampp/htdocs/COB_ANDINA/tarea/layout/layout_publicidad.php');
			$buscar = array("cuerpomensaje");
			$cambiar = array($speech);
			$speechini = str_replace($buscar, $cambiar, $layout);


			setlocale(LC_TIME,"es_ES");

			$asunto="¡Grupo Andina viste a tu equipo de hincha! - Publicidad";

			$objMailer = new PHPMailer();
			$objMailer->SMTPAuth = true;
			$objMailer->WordWrap = 50;
			$objMailer->SMTPDebug  = 1;
			$objMailer->Mailer = 'smtp';                                                
			$objMailer->Host = 'smtp.gmail.com';
			$objMailer->Timeout = 120;
			$objMailer->SMTPSecure = "tls";
			$objMailer->IsHTML(true);
			$objMailer->IsSMTP();
			$objMailer->CharSet = "utf-8";
			$objMailer->Port = '587';
			$objMailer->Username = 'marketing@grupoandina.com.pe';
			$objMailer->From = 'marketing@grupoandina.com.pe';
			$objMailer->Password = 'marketing2017';
			$objMailer->FromName = 'Grupo Andina S.A.C.';
			$objMailer->Subject = $asunto;

			$objMailer->ClearAddresses();

			$objMailer->AddAddress($correo);

			$objMailer->Body = $speechini;
			//$archivo = 'C:/xampp/htdocs/COB_ANDINA/documents/correo_masivo/'.$namefile.'.xlsx';
			//$objMailer->AddAttachment($archivo,$namefile.'.xlsx');

			if(!$objMailer->send()){
				// error al enviar (estado=2)
				echo "<br>"."no enviado ".$correo."<br>"."\n";
				$insert_email_send="INSERT INTO `cob_andina`.`ca_envio_historico` ( `idcorreo`, `idcorreo_asunto`, `fecha_enviado`, `enviado`) VALUES ( $xidcorreo, $idcorreo_asunto, NOW(), 2);";
		        $pr_insert_email_send=$connection->prepare($insert_email_send);
		        if($pr_insert_email_send->execute()){
		        }
			}else{
				// enviado (estado=1)
				echo "<br>"."enviado ".$correo." ".$fecha_envio."\n";
				$insert_email_send="INSERT INTO `cob_andina`.`ca_envio_historico` ( `idcorreo`, `idcorreo_asunto`, `fecha_enviado`, `enviado`) VALUES ( $xidcorreo, $idcorreo_asunto, NOW(), 1);";
		        $pr_insert_email_send=$connection->prepare($insert_email_send);
		        if($pr_insert_email_send->execute()){
		        }
			}


			$updatesend="UPDATE ca_correo_envio SET fecha_envio=NOW() WHERE idcorreo=$xidcorreo";
			$pr_updatesend=$connection->prepare($updatesend);
	        $pr_updatesend->execute();
	        


		}




}else{
	echo "NO HAY EVENTOS PROGRAMADOS PARA HOY";
}





?>