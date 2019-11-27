<#EXPORTANDO DATOS DE LA BD SQL SERVER A UN ARCHIVO PLANO#>
$a = Get-Date; 														# colocando fecha en una variable
$fecha=$a.ToString("yyyy_MM_dd_HH_mm_ss"); 							# dandole formato a la fecha original 
$pathfile="C:\xampp\htdocs\COB_ANDINA\documents\historicosporzona\"; 			# definiendo la ruta donde lo archivos seran trabajados
$fileancii=$pathfile+"DOCS_ANSI_"+$fecha+".txt"; 					# referencia del archivo ansi
$fileutf8=$pathfile+"DOCS_UTF8_"+$fecha+".txt"; 					# referencia del archivo utf-8
$namefile="DOCS_UTF8_"+$fecha+".txt";								# solo el nombre del archivo final
bcp "SELECT * FROM RSFACCAR..EXPTHIS" queryout $fileancii -c -T -w -U sa -P Andinars08; # exportando la BD a un .txt con caracter ansi
