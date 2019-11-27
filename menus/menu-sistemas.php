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
	if( $_GET['menu']=='servicio' ) {
	?>	
		<div id="tabMaestro" class="itemTabActive border-radius-top pointer ui-widget-header">
			<a style="font-weight:bold;text-decoration:none;" >Servicios</a>    
		</div>    
	<?php
	}else{
		?>
		<div id="tabMaestro" class="itemTab border-radius-top pointer ui-widget-content" onclick="_activeTab()">
			<a class="AitemTab" href="../view/ui-servicio.php?menu=servicio">Servicios</a>
		</div>
		<?php
	}
?>
</td>
<td>
<?php
	if( $_GET['menu']=='usuario_admin' ) {
	?>	
		<div id="tabMaestro" class="itemTabActive border-radius-top pointer ui-widget-header">
			<a style="font-weight:bold;text-decoration:none;" >Usuarios</a>    
		</div>    
	<?php
	}else{
		?>
		<div id="tabMaestro" class="itemTab border-radius-top pointer ui-widget-content" onclick="_activeTab()">
			<a class="AitemTab" href="../view/ui-usuario-admin.php?menu=usuario_admin">Usuarios</a>
		</div>
		<?php
	}
?>
</td>
<td>
<?php
	if( $_GET['menu']=='campania' ) {
	?>	
		<div id="tabMaestro" class="itemTabActive border-radius-top pointer ui-widget-header">
			<a style="font-weight:bold;text-decoration:none;" >Campa&ntilde;as</a>    
		</div>    
	<?php
	}else{
		?>
		<div id="tabMaestro" class="itemTab border-radius-top pointer ui-widget-content" onclick="_activeTab()">
			<a class="AitemTab" href="../view/ui-campania.php?menu=campania">Campa&ntilde;as</a>
		</div>
		<?php
	}
?>
</td>
<td>
<?php
	if( $_GET['menu']=='cartera' ) {
	?>	
		<div id="tabMaestro" class="itemTabActive border-radius-top pointer ui-widget-header">
			<a style="font-weight:bold;text-decoration:none;" >Cartera</a>    
		</div>    
	<?php
	}else{
		?>
		<div id="tabMaestro" class="itemTab border-radius-top pointer ui-widget-content" onclick="_activeTab()">
			<a class="AitemTab" href="../view/ui-cargar-cartera.php?menu=cartera">Cartera</a>
		</div>
		<?php
	}
?>
</td>
<td>
<?php
	if( $_GET['menu']=='distribucion' ) {
	?>	
		<div id="tabMaestro" class="itemTabActive border-radius-top pointer ui-widget-header">
			<a style="font-weight:bold;text-decoration:none;" >Distribucion</a>    
		</div>    
	<?php
	}else{
		?>
		<div id="tabMaestro" class="itemTab border-radius-top pointer ui-widget-content" onclick="_activeTab()">
			<a class="AitemTab" href="../view/ui-distribucion.php?menu=distribucion">Distribucion</a>
		</div>
		<?php
	}
?>
</td>
<td>
<?php
	if( $_GET['menu']=='speech' ) {
	?>	
		<div id="tabMaestro" class="itemTabActive border-radius-top pointer ui-widget-header">
			<a style="font-weight:bold;text-decoration:none;" >Speech</a>    
		</div>    
	<?php
	}else{
		?>
		<div id="tabMaestro" class="itemTab border-radius-top pointer ui-widget-content" onclick="_activeTab()">
			<a class="AitemTab" href="../view/ui-speech.php?menu=speech">Speech</a>
		</div>
		<?php
	}
?>
</td>
<td>
<?php
	if( $_GET['menu']=='AyudaGestionUsuario' ) {
	?>	
		<!-- <div id="tabMaestro" align="center" style="width:135px;" class="itemTabActive border-radius-top pointer ui-widget-header">
			<a style="font-weight:bold;text-decoration:none;" >Ayuda Gestion Usuario</a>    
		</div>   -->  
	<?php
	}else{
		?>
		<!-- <div id="tabMaestro" align="center" style="width:135px;" class="itemTab border-radius-top pointer ui-widget-content" onclick="_activeTab()">
			<a class="AitemTab" href="../view/ui-ayuda-gestion-usuario.php?menu=AyudaGestionUsuario">Ayuda Gestion Usuario</a>
		</div> -->
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
	if( $_GET['menu']=='adicionales' ) {
	?>	
		<!-- <div id="tabMaestro" class="itemTabActive border-radius-top pointer ui-widget-header">
			<a style="font-weight:bold;text-decoration:none;" >Adicionales</a>    
		</div>    --> 
	<?php
	}else{
		?>
		<!-- <div id="tabMaestro" class="itemTab border-radius-top pointer ui-widget-content" onclick="_activeTab()">
			<a class="AitemTab" href="../view/ui-adicionales.php?menu=adicionales">Adicionales</a>
		</div> -->
		<?php
	}
?>
</td>
<td>
<button id="btnOtrosMenuMainCobrast" onclick="$('#cbOtrosMenuMainCobrast').slideToggle()" >Otros</button>
<div style="overflow: auto; position: absolute; display: none;z-index:110;" class="ui-widget-content ui-corner-bottom" id="cbOtrosMenuMainCobrast">
	<table>
    	<tr>
        	<td>
				<?php
                    if( $_GET['menu']=='control_gestion' ) {
                    ?>	
                        <div id="tabMaestro" class="itemTabActive ui-corner-all pointer ui-widget-header">
                            <a style="font-weight:bold;text-decoration:none;" >Control Gestion</a>    
                        </div>    
                    <?php
                    }else{
                        ?>
                        <div id="tabMaestro" class="itemTab ui-corner-all pointer ui-widget-content" onclick="_activeTab()">
                            <a class="AitemTab" href="../view/ui-control-gestiones.php?menu=control_gestion">Control Gestion</a>
                        </div>
                        <?php
                    }
                ?>
            </td>
     	</tr>
        <tr>
            <td>
            	<?php
					if( $_GET['menu']=='ranking' ) {
					?>	
						<div id="tabMaestro" class="itemTabActive ui-corner-all pointer ui-widget-header">
							<a style="font-weight:bold;text-decoration:none;" >Ranking</a>    
						</div>    
					<?php
					}else{
						?>
						<div id="tabMaestro" class="itemTab ui-corner-all pointer ui-widget-content" onclick="_activeTab()">
							<a class="AitemTab" href="../view/ui-ranking.php?menu=ranking">Ranking</a>
						</div>
						<?php
					}
				?>
			</td>
        </tr>
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