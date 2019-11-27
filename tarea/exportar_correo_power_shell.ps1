<#EXPORTANDO DATOS DE LA BD SQL SERVER A UN ARCHIVO PLANO#>
$a = Get-Date; 														# colocando fecha en una variable
$fecha=$a.ToString("yyyy_MM_dd_HH_mm_ss"); 							# dandole formato a la fecha original 
$pathfile="C:\xampp\htdocs\COB_ANDINA\documents\loadmail\"; 		# definiendo la ruta donde lo archivos seran trabajados
$fileancii=$pathfile+"DOCS_ANSI_"+$fecha+".txt"; 					# referencia del archivo ansi
$fileutf8=$pathfile+"DOCS_UTF8_"+$fecha+".txt"; 					# referencia del archivo utf-8
$namefile="DOCS_UTF8_"+$fecha+".txt";								# solo el nombre del archivo final
bcp "SELECT 'CODIGO_CLIENTE','CORREO' UNION SELECT * FROM RSFACCAR..EXPTCOR  TMP ORDER BY 1 DESC ,2 DESC" queryout $fileancii -c -T -w -U sa -P Andinars08; # exportando la BD a un .txt con caracter ansi
# Get-Content $fileancii | Set-Content -Encoding utf8 $fileutf8; 		# comando para convertir de ansi a utf-8
Get-Content $fileancii | Set-Content $fileutf8; 		# comando para convertir de ansi a utf-8
Remove-Item $fileancii;												# eliminado archivo ansi que se creo
<#EJECUTANDO PHP PASANDOLE VARIABLE PARA CONTROLARLO POR BD MYSQL#>
C:\xampp\php\php.exe -f C:\xampp\htdocs\COB_ANDINA\tarea\exportar_correo.php $namefile >> C:\xampp\htdocs\COB_ANDINA\tarea\exportar_correo.log