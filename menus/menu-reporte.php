<td>
<?php
	if( $_GET['menu']=='home' ) {
	?>	
		<div id="tabMaestro" class="itemTabActive border-radius-top pointer ui-widget-header">
			<a style="font-weight:bold;text-decoration:none;" >Home</a>    
		</div>    
	<?php
	}else{
		?>
		<div id="tabMaestro" class="itemTab border-radius-top pointer ui-widget-content" onclick="_activeTab()">
			<a class="AitemTab" href="../view/ui-cobrast.php?menu=home">Home</a>
		</div>
		<?php
	}
?>
</td>
<td>
<?php
	if( $_GET['menu']=='reportes' ) {
	?>	
		<div id="tabMaestro" class="itemTabActive border-radius-top pointer ui-widget-header">
			<a style="font-weight:bold;text-decoration:none;" >Reportes</a>    
		</div>    
	<?php
	}else{
		?>
		<div id="tabMaestro" class="itemTab border-radius-top pointer ui-widget-content" onclick="_activeTab()">
			<a class="AitemTab" href="../view/ui-reporte.php?menu=reportes">Reportes</a>
		</div>
		<?php
	}
?>
</td>
<td>
<?php
	if( $_GET['menu']=='ranking' ) {
	?>	
		<div id="tabMaestro" class="itemTabActive border-radius-top pointer ui-widget-header">
			<a style="font-weight:bold;text-decoration:none;" >Ranking</a>    
		</div>    
	<?php
	}else{
		?>
		<div id="tabMaestro" class="itemTab border-radius-top pointer ui-widget-content" onclick="_activeTab()">
			<a class="AitemTab" href="../view/ui-ranking.php?menu=ranking">Ranking</a>
		</div>
		<?php
	}
?>
</td>