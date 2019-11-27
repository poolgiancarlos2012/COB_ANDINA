var templates={
            combo: function ( obj, id ) {
                    var combo='';
                        combo+='<option value="0">--Seleccione--</option>';
                   $.each(obj,function ( key,data ) {
                        combo+='<option value='+data.id+'>'+data.nombre+'</option>';
                   } );
                   $('#'+id).html(combo);
            },
            MsgError: function ( message, width ) {
                  var html='';
                        html+='<div>';
                            html+='<div class="ui-state-error ui-corner-all paddingMsg" style="width:'+width+'  ">'
                                html+='<p>';
                                    html+='<span class="ui-icon ui-icon-alert" style="float: left; margin-right: 0.3em;"></span>';
                                    html+='<strong>Alert:</strong>';
                                    html+=message;
                                html+='</p>';
                            html+='</div>';
                        html+='</div>';
                 return html;
            },
            MsgInfo: function ( message, width ) {
                  var html='';
                        html+='<div>';
                            html+='<div class="ui-state-highlight ui-corner-all paddingMsg" style=" width:'+width+' ">'
                                html+='<p>';
                                    html+='<span class="ui-icon ui-icon-alert" style="float: left; margin-right: 0.3em;"></span>';
                                    html+='<strong>Alert:</strong>';
                                    html+=message;
                                html+='</p>';
                            html+='</div>';
                        html+='</div>';
                 return html;
            },
			IMGloadingContentTable : function ( ) {
				var html='';
					html+='<tr>';
						html+='<td align="center"><img src="../img/loading_.gif" /></td>';
					html+='</tr>';
				return html;	
			},
			IMGloadingContentLayer : function ( ) {
				var html='';
					html+='<div align="center"><img src="../img/loading_.gif" /></div>';
				return html;	
			},
			LoadingCombo : function ( ) {
					var html='';
					html+='<option value="0">Cargando...</option>';
					return html;
				},
			IMGloadingContent : function ( ) {
				var html='';
					html+='<img src="../img/loading.gif">';
				return html;
			}
			
}


