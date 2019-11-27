$(document).ready(function(){
	//SpeechJQGRID.Listar();
	/*************/
	SpeechDAO.LoadTipoAyudaGestion();
	SpeechDAO.ListadoSpeech();
	SpeechDAO.listar_speech_is_text();
	/************/
	$('#layerDatepicker').datepicker({inline:true,autoSize:true});
	/***********/
	$('#DataReadFileAndText').dialog({
									height : 400,
									autoOpen : false,
									width : 700 ,
									title : 'Speech y Argumentario',
									modal : true,
									buttons : {
											Cancel : function ( ) {
													$(this).dialog('close');
												}
										}
									});
	/***********/
	$('#txtRichTextSpeech').tinymce({
				script_url : '../includes/tinymce/jscripts/tiny_mce/tiny_mce.js',
				theme : "advanced",
				plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
				theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
				theme_advanced_buttons2 : "search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,|,insertdate,inserttime,preview,|,forecolor,backcolor",
				theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,ltr,rtl,|,fullscreen",
				theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_statusbar_location : "bottom",
				theme_advanced_resizing : true,
				content_css : "css/content.css",
				template_external_list_url : "lists/template_list.js",
				external_link_list_url : "lists/link_list.js",
				external_image_list_url : "lists/image_list.js",
				media_external_list_url : "lists/media_list.js",
				template_replace_values : {
					username : "Some User",
					staffid : "991234"
					}
	
				});
				
});
ajaxFileUpload = function ( ) {
	var rs=validacion.check([
		{id:'fileSpeech',required:true,errorRequiredFunction:function( ){
				$('#'+SpeechDAO.idLayerMessage).html(templates.MsgError('Seleccione archivo a subir al servidor','350px'));
				SpeechDAO.setTimeOut_hide_message();
			}},
		{id:'cbTipoAyudaGestion',isNotValue:0,errorNotValueFunction:function( ){
				$('#'+SpeechDAO.idLayerMessage).html(templates.MsgError('Seleccione tipo','300px'));
				SpeechDAO.setTimeOut_hide_message();
			}}
	]);
	if( rs ){
		var rsC=confirm("Verifique si el archivo seleccionado es el correcto");
		if( rsC ){
			SpeechDAO.Upload();
		}
	}
}
save_speech_modo_texto = function ( ) {
	var rs=validacion.check([
		{id:'txtNombreAyudaGestionModoTexto',required:true,errorRequiredFunction:function ( ) {
				$('#'+SpeechDAO.idLayerMessage).html(templates.MsgError('Ingrese nombre de speech','400px'));
				SpeechDAO.setTimeOut_hide_message();
			}},
		{id:'cbTipoAyudaGestionModoTexto',isNotValue:0,errorNotValueFunction:function( ){
				$('#'+SpeechDAO.idLayerMessage).html(templates.MsgError('Seleccione tipo de ayuda gestion','400px'));
				SpeechDAO.setTimeOut_hide_message();
			}}
		]);
	
	if( rs ){
		var rsC=confirm("Verifique que los datos ingresados son los correctos");
		if( rsC ) {
			SpeechDAO.save_speech_modo_texto();
		}
	}
}
update_speech_modo_texto = function ( ) {
	var rs=validacion.check([
		{id:'hdIdSpeechIsText',required:true,errorRequiredFunction:function(){
				$('#'+SpeechDAO.idLayerMessage).html(templates.MsgError('Seleccione speech o argumentario a actualizar','400px'));
				SpeechDAO.setTimeOut_hide_message();
			}},
		{id:'txtNombreAyudaGestionModoTexto',required:true,errorRequiredFunction:function ( ) {
				$('#'+SpeechDAO.idLayerMessage).html(templates.MsgError('Ingrese nombre de speech','400px'));
				SpeechDAO.setTimeOut_hide_message();
			}},
		{id:'cbTipoAyudaGestionModoTexto',isNotValue:0,errorNotValueFunction:function( ){
				$('#'+SpeechDAO.idLayerMessage).html(templates.MsgError('Seleccione tipo de ayuda gestion','400px'));
				SpeechDAO.setTimeOut_hide_message();
			}}
		]);
	
	if( rs ){
		var rsC=confirm("Verifique que los datos ingresados son los correctos");
		if( rsC ) {
			SpeechDAO.update_speech_modo_texto();
		}
	}
}
cancel_speech_modo_texto = function ( ) {
	$('#panelCargarSpeechModoTexto').find(':text,:hidden').val('');
	$('#panelCargarSpeechModoTexto').find('select').val(0);
}
getParamUpdate = function ( id ) {
//	var id=$(element).parent().parent().attr('id');
//	var nombre=$(element).parent().parent().children('td:eq(0)').text();
//	var content=$(element).parent().parent().children('td:eq(3)').html();
//	var tipo=$(element).parent().parent().children('td:eq(2)').attr('id');
//	$('#hdIdSpeechIsText').val(id);
//	$('#txtNombreAyudaGestionModoTexto').val(nombre);
//	$('#cbTipoAyudaGestionModoTexto').val(tipo);
//	$('#txtRichTextSpeech').tinymce().execCommand('mceSetContent',false,content);
	
	SpeechDAO.DataText(id);
}
read_file = function ( id ) {
	SpeechDAO.read_file( 'ReadFile',id,SpeechDAO.show_read_file );
}
read_text = function ( id ) {
	SpeechDAO.read_file( 'ReadText',id,SpeechDAO.show_read_text );
}
