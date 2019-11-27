<#EXPORTANDO DATOS DE LA BD SQL SERVER A UN ARCHIVO PLANO#>
$a = Get-Date; 														# colocando fecha en una variable
$fecha=$a.ToString("yyyy_MM_dd_HH_mm_ss"); 							# dandole formato a la fecha original 
$pathfile="C:\xampp\htdocs\COB_ANDINA\documents\loaddireccion\";    # definiendo la ruta donde lo archivos seran trabajados
$fileancii=$pathfile+"DOCS_ANSI_"+$fecha+".txt"; 					# referencia del archivo ansi
$fileutf8=$pathfile+"DOCS_UTF8_"+$fecha+".txt"; 					# referencia del archivo utf-8
$namefile="DOCS_UTF8_"+$fecha+".txt";								# solo el nombre del archivo final
bcp "SELECT 'COD_CLIENTE', 'DEPARTAMENTO', 'PROVINCIA', 'DISTRITO', 'DIRECCION' UNION ALL SELECT COD_CLIENTE, DEPARTAMENTO, PROVINCIA, DISTRITO, DIRECCION FROM RSFACCAR..VIEW_ALL_CLIENT_ACTIVE WHERE COD IN ('0002','0003','0004','0016') " queryout $fileancii -c -T -w -U sa -P Andinars08; # exportando la BD a un .txt con caracter ansi
Get-Content $fileancii | Set-Content -Encoding utf8 $fileutf8; 		# comando para convertir de ansi a utf-8
# Get-Content $fileancii | Set-Content -Encoding Ascii $fileutf8; 	# comando para convertir de ansi a utf-8
# Get-Content $fileancii | Set-Content -Encoding Unicode $fileutf8; 	# comando para convertir de ansi a utf-8
Remove-Item $fileancii;												# eliminado archivo ansi que se creo
<#EJECUTANDO PHP PASANDOLE VARIABLE PARA CONTROLARLO POR BD MYSQL#>
C:\xampp\php\php.exe -f C:\xampp\htdocs\COB_ANDINA\tarea\exportar_direccion.php $namefile >> C:\xampp\htdocs\COB_ANDINA\tarea\exportar_direccion.log