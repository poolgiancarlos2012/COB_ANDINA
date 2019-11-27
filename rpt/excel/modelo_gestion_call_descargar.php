<?php


$ruta = '../excel/modelo_gestion_call.xlsx';

if(is_file($ruta))
{
header('Content-Type: application/force-download');
header('Content-Disposition: attachment; filename=modelo_gestion_call.xlsx');
header('Content-Transfer-Encoding: binary');
header('Content-Length: '.filesize($ruta));

readfile($ruta);
}