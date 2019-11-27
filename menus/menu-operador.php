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
	if( $_GET['menu']=='gestion' ) {
	?>	
		<div id="tabMaestro" class="itemTabActive border-radius-top pointer ui-widget-header">
			<a style="font-weight:bold;text-decoration:none;" >Gestion</a>    
		</div>    
	<?php
	}else{
		?>
		<div id="tabMaestro" class="itemTab border-radius-top pointer ui-widget-content" onclick="_activeTab()">
			<a class="AitemTab" href="../view/ui-attention-client.php?menu=gestion">Gestion</a>
		</div>
		<?php
	}
?>
</td>
<td>
<?php
	if( $_GET['menu']=='calendar' ) {
	?>	
		<!-- <div id="tabMaestro" class="itemTabActive border-radius-top pointer ui-widget-header">
			<a style="font-weight:bold;text-decoration:none;" >Calendar</a>    
		</div>   -->  
	<?php
	}else{
		?>
		<!-- <div id="tabMaestro" class="itemTab border-radius-top pointer ui-widget-content" onclick="_activeTab()">
			<a class="AitemTab" href="../view/ui-calendar.php?menu=calendar">Calendar</a>
		</div> -->
		<?php
	}
?>
</td>
<td>
<!-- <button id="btnOtrosMenuMainCobrast" onclick="$('#cbOtrosMenuMainCobrast').slideToggle()" >Otros</button> -->
<div style="overflow: auto; position: absolute; display: none;z-index:110;" class="ui-widget-content ui-corner-bottom" id="cbOtrosMenuMainCobrast">
	<table>
		<tr>
            <td>
            	<?php
					if( $_GET['menu']=='file' ) {
					?>	
						<div id="tabMaestro" class="itemTabActive ui-corner-all pointer ui-widget-header">
							<a style="font-weight:bold;text-decoration:none;" >Archivos</a>    
						</div>    
					<?php
					}else{
						?>
						<div id="tabMaestro" class="itemTab ui-corner-all pointer ui-widget-content" onclick="_activeTab()">
							<a class="AitemTab" href="../view/ui-files.php?menu=files">Archivos</a>
						</div>
						<?php
					}
				?>
			</td>
        </tr>
    </table>
</div>
</td>
