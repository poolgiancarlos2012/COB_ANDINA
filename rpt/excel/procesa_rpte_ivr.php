<?php

include_once("../../libreria/funciones.php");

$carteras=$_REQUEST['cartera'];

header('Content-Type: text/html; charset=UTF-8');
header("Content-Disposition:atachment;filename=ivr.txt");
header("Content-Type: application/force-download");
header("Content-Transfer-Encoding: binary");
header("Pragma:no-cache");
header("Expires:0");

/*$data=lee("select data.tel from
(select cta.telefono ,cta.total_deuda, cta.total_comision,cta.monto_pagado, cta.idcartera, if((ifnull(cta.total_deuda,0)+ ifnull(cta.total_comision,0)>ifnull(cta.monto_pagado,0)),1,0) as adeuda,
if(  (length(cta.telefono)=8) and (substr(cta.telefono,1,1)!=1) , concat('0',cta.telefono) , if( length(cta.telefono)=7,concat('1',cta.telefono),cta.telefono  )     ) as tel
from ca_cuenta cta
inner join ca_cartera on ca_cartera.idcartera=cta.idcartera
and ca_cartera.estado=1 and cta.retirado = 0 and cta.idcartera in (".$carteras.")) data
where data.adeuda=1
");*/

$data=lee("select data.tel from
(select cta.telefono ,cta.total_deuda, cta.total_comision,cta.monto_pagado, cta.idcartera, if((ifnull(cta.total_deuda,0)+ ifnull(cta.total_comision,0)>ifnull(cta.monto_pagado,0)),1,0) as adeuda,
if(  (length(cta.telefono)=8) and (substr(cta.telefono,1,1)!=1) , concat('0',cta.telefono) , if( length(cta.telefono)=8 and (substr(cta.telefono,1,1)=1) ,substr(cta.telefono,2,8),cta.telefono  )     ) as tel
from ca_cuenta cta
inner join ca_cartera on ca_cartera.idcartera=cta.idcartera
and ca_cartera.estado=1 and cta.retirado = 0 and cta.idcartera in (".$carteras.")) data
where data.adeuda=1");


for($i=0;$i<count($data);$i++){
	echo($data[$i][0]."\r\n");
}
?>