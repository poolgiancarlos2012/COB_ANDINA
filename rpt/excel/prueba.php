<?php

	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=archivo.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	echo '<table>';
		echo '<tr><td>kennedy</td></tr>';
	echo '</table>';
	
	echo '</br>';

	echo '<table>';
		echo '<tr><td>kennedy 2</td></tr>';
	echo '</table>';
	

?>