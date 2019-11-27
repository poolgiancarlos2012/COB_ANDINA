/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.dialog.add('specialchar',function(a){var b,c=a.lang.specialChar,d=function(k){var l=a.getSelection(),m=l.getRanges(),n,o;a.fire('saveSnapshot');for(var p=0,q=m.length;p<q;p++){n=m[p];n.deleteContents();o=CKEDITOR.dom.element.createFromHtml(k);n.insertNode(o);}n.moveToPosition(o,CKEDITOR.POSITION_AFTER_END);n.select();a.fire('saveSnapshot');},e=function(k){var l,m;if(k.data)l=k.data.getTarget();else l=new CKEDITOR.dom.element(k);if(l.getName()=='a'&&(m=l.getChild(0).getHtml())){l.removeClass('cke_light_background');b.hide();if(CKEDITOR.env.gecko)d(m);else a.insertHtml(m);}},f=CKEDITOR.tools.addFunction(e),g,h=function(k,l){var m;l=l||k.data.getTarget();if(l.getName()=='span')l=l.getParent();if(l.getName()=='a'&&(m=l.getChild(0).getHtml())){if(g)i(null,g);var n=b.getContentElement('info','htmlPreview').getElement();b.getContentElement('info','charPreview').getElement().setHtml(m);n.setHtml(CKEDITOR.tools.htmlEncode(m));l.getParent().addClass('cke_light_background');g=l;}},i=function(k,l){l=l||k.data.getTarget();if(l.getName()=='span')l=l.getParent();if(l.getName()=='a'){b.getContentElement('info','charPreview').getElement().setHtml('&nbsp;');b.getContentElement('info','htmlPreview').getElement().setHtml('&nbsp;');l.getParent().removeClass('cke_light_background');g=undefined;}},j=CKEDITOR.tools.addFunction(function(k){k=new CKEDITOR.dom.event(k);var l=k.getTarget(),m,n,o=k.getKeystroke(),p=a.lang.dir=='rtl';switch(o){case 38:if(m=l.getParent().getParent().getPrevious()){n=m.getChild([l.getParent().getIndex(),0]);n.focus();i(null,l);h(null,n);}k.preventDefault();break;case 40:if(m=l.getParent().getParent().getNext()){n=m.getChild([l.getParent().getIndex(),0]);if(n&&n.type==1){n.focus();i(null,l);h(null,n);}}k.preventDefault();break;case 32:e({data:k});k.preventDefault();break;case p?37:39:case 9:if(m=l.getParent().getNext()){n=m.getChild(0);if(n.type==1){n.focus();i(null,l);h(null,n);k.preventDefault(true);}else i(null,l);}else if(m=l.getParent().getParent().getNext()){n=m.getChild([0,0]);if(n&&n.type==1){n.focus();i(null,l);h(null,n);k.preventDefault(true);}else i(null,l);}break;case p?39:37:case CKEDITOR.SHIFT+9:if(m=l.getParent().getPrevious()){n=m.getChild(0);n.focus();i(null,l);h(null,n);k.preventDefault(true);}else if(m=l.getParent().getParent().getPrevious()){n=m.getLast().getChild(0);n.focus();i(null,l);h(null,n);k.preventDefault(true);}else i(null,l);break;default:return;}});return{title:c.title,minWidth:430,minHeight:280,buttons:[CKEDITOR.dialog.cancelButton],charColumns:17,chars:['!','&quot;','#','$','%','&amp;',"'",'(',')','*','+','-','.','/','0','1','2','3','4','5','6','7','8','9',':',';','&lt;','=','&gt;','?','@','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','[',']','^','_','`','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','{','|','}','~','&euro;(EURO SIGN)','&lsquo;(LEFT SINGLE QUOTATION MARK)','&rsquo;(RIGHT SINGLE QUOTATION MARK)','&ldquo;(LEFT DOUBLE QUOTATION MARK)','&rdquo;(RIGHT DOUBLE QUOTATION MARK)','&ndash;(EN DASH)','&mdash;(EM DASH)','&iexcl;(INVERTED EXCLAMATION MARK)','&cent;(CENT SIGN)','&pound;(POUND SIGN)','&curren;(CURRENCY SIGN)','&yen;(YEN SIGN)','&brvbar;(BROKEN BAR)','&sect;(SECTION SIGN)','&uml;(DIAERESIS)','&copy;(COPYRIGHT SIGN)','&ordf;(FEMININE ORDINAL INDICATOR)','&laquo;(LEFT-POINTING DOUBLE ANGLE QUOTATION MARK)','&not;(NOT SIGN)','&reg;(REGISTERED SIGN)','&macr;(MACRON)','&deg;(DEGREE SIGN)','&plusmn;(PLUS-MINUS SIGN)','&sup2;(SUPERSCRIPT TWO)','&sup3;(SUPERSCRIPT THREE)','&acute;(ACUTE ACCENT)','&micro;(MICRO SIGN)','&para;(PILCROW SIGN)','&middot;(MIDDLE DOT)','&cedil;(CEDILLA)','&sup1;(SUPERSCRIPT ONE)','&ordm;(MASCULINE ORDINAL INDICATOR)','&raquo;(RIGHT-POINTING DOUBLE ANGLE QUOTATION MARK)','&frac14;(VULGAR FRACTION ONE QUARTER)','&frac12;(VULGAR FRACTION ONE HALF)','&frac34;(VULGAR FRACTION THREE QUARTERS)','&iquest;(INVERTED QUESTION MARK)','&Agrave;(LATIN CAPITAL LETTER A WITH GRAVE)','&Aacute;(LATIN CAPITAL LETTER A WITH ACUTE)','&Acirc;(LATIN CAPITAL LETTER A WITH CIRCUMFLEX)','&Atilde;(LATIN CAPITAL LETTER A WITH TILDE)','&Auml;(LATIN CAPITAL LETTER A WITH DIAERESIS)','&Aring;(LATIN CAPITAL LETTER A WITH RING ABOVE)','&AElig;(LATIN CAPITAL LETTER AE)','&Ccedil;(LATIN CAPITAL LETTER C WITH CEDILLA)','&Egrave;(LATIN CAPITAL LETTER E WITH GRAVE)','&Eacute;(LATIN CAPITAL LETTER E WITH ACUTE)','&Ecirc;(LATIN CAPITAL LETTER E WITH CIRCUMFLEX)','&Euml;(LATIN CAPITAL LETTER E WITH DIAERESIS)','&Igrave;(LATIN CAPITAL LETTER I WITH GRAVE)','&Iacute;(LATIN CAPITAL LETTER I WITH ACUTE)','&Icirc;(LATIN CAPITAL LETTER I WITH CIRCUMFLEX)','&Iuml;(LATIN CAPITAL LETTER I WITH DIAERESIS)','&ETH;(LATIN CAPITAL LETTER ETH)','&Ntilde;(LATIN CAPITAL LETTER N WITH TILDE)','&Ograve;(LATIN CAPITAL LETTER O WITH GRAVE)','&Oacute;(LATIN CAPITAL LETTER O WITH ACUTE)','&Ocirc;(LATIN CAPITAL LETTER O WITH CIRCUMFLEX)','&Otilde;(LATIN CAPITAL LETTER O WITH TILDE)','&Ouml;(LATIN CAPITAL LETTER O WITH DIAERESIS)','&times;(MULTIPLICATION SIGN)','&Oslash;(LATIN CAPITAL LETTER O WITH STROKE)','&Ugrave;(LATIN CAPITAL LETTER U WITH GRAVE)','&Uacute;(LATIN CAPITAL LETTER U WITH ACUTE)','&Ucirc;(LATIN CAPITAL LETTER U WITH CIRCUMFLEX)','&Uuml;(LATIN CAPITAL LETTER U WITH DIAERESIS)','&Yacute;(LATIN CAPITAL LETTER Y WITH ACUTE)','&THORN;(LATIN CAPITAL LETTER THORN)','&szlig;(LATIN SMALL LETTER SHARP S)','&agrave;(LATIN SMALL LETTER A WITH GRAVE)','&aacute;(LATIN SMALL LETTER A WITH ACUTE)','&acirc;(LATIN SMALL LETTER A WITH CIRCUMFLEX)','&atilde;(LATIN SMALL LETTER A WITH TILDE)','&auml;(LATIN SMALL LETTER A WITH DIAERESIS)','&aring;(LATIN SMALL LETTER A WITH RING ABOVE)','&aelig;(LATIN SMALL LETTER AE)','&ccedil;(LATIN SMALL LETTER C WITH CEDILLA)','&egrave;(LATIN SMALL LETTER E WITH GRAVE)','&eacute;(LATIN SMALL LETTER E WITH ACUTE)','&ecirc;(LATIN SMALL LETTER E WITH CIRCUMFLEX)','&euml;(LATIN SMALL LETTER E WITH DIAERESIS)','&igrave;(LATIN SMALL LETTER I WITH GRAVE)','&iacute;(LATIN SMALL LETTER I WITH ACUTE)','&icirc;(LATIN SMALL LETTER I WITH CIRCUMFLEX)','&iuml;(LATIN SMALL LETTER I WITH DIAERESIS)','&eth;(LATIN SMALL LETTER ETH)','&ntilde;(LATIN SMALL LETTER N WITH TILDE)','&ograve;(LATIN SMALL LETTER O WITH GRAVE)','&oacute;(LATIN SMALL LETTER O WITH ACUTE)','&ocirc;(LATIN SMALL LETTER O WITH CIRCUMFLEX)','&otilde;(LATIN SMALL LETTER O WITH TILDE)','&ouml;(LATIN SMALL LETTER O WITH DIAERESIS)','&divide;(DIVISION SIGN)','&oslash;(LATIN SMALL LETTER O WITH STROKE)','&ugrave;(LATIN SMALL LETTER U WITH GRAVE)','&uacute;(LATIN SMALL LETTER U WITH ACUTE)','&ucirc;(LATIN SMALL LETTER U WITH CIRCUMFLEX)','&uuml;(LATIN SMALL LETTER U WITH DIAERESIS)','&uuml;(LATIN SMALL LETTER U WITH DIAERESIS)','&yacute;(LATIN SMALL LETTER Y WITH ACUTE)','&thorn;(LATIN SMALL LETTER THORN)','&yuml;(LATIN SMALL LETTER Y WITH DIAERESIS)','&OElig;(LATIN CAPITAL LIGATURE OE)','&oelig;(LATIN SMALL LIGATURE OE)','&#372;(LATIN CAPITAL LETTER W WITH CIRCUMFLEX)','&#374(LATIN CAPITAL LETTER Y WITH CIRCUMFLEX)','&#373(LATIN SMALL LETTER W WITH CIRCUMFLEX)','&#375;(LATIN SMALL LETTER Y WITH CIRCUMFLEX)','&sbquo;(SINGLE LOW-9 QUOTATION MARK)','&#8219;(SINGLE HIGH-REVERSED-9 QUOTATION MARK)','&bdquo;(DOUBLE LOW-9 QUOTATION MARK)','&hellip;(HORIZONTAL ELLIPSIS)','&trade;(TRADE MARK SIGN)','&#9658;(BLACK RIGHT-POINTING POINTER)','&bull;(BULLET)','&rarr;(RIGHTWARDS ARROW)','&rArr;(RIGHTWARDS DOUBLE ARROW)','&hArr;(LEFT RIGHT DOUBLE ARROW)','&diams;(BLACK DIAMOND SUIT)','&asymp;(ALMOST EQUAL TO)'],onLoad:function(){var k=this.definition.charColumns,l=this.definition.chars,m='specialchar_table_label'+CKEDITOR.tools.getNextNumber(),n=['<table role="listbox" aria-labelledby="'+m+'"'+' style="width: 320px; height: 100%; border-collapse: separate;"'+' align="center" cellspacing="2" cellpadding="2" border="0">'],o=0,p=l.length,q,r;
while(o<p){n.push('<tr>');for(var s=0;s<k;s++,o++){if(q=l[o]){r='';q=q.replace(/\((.*?)\)/,function(u,v){r=v;return '';});r=r||q;var t='cke_specialchar_label_'+o+'_'+CKEDITOR.tools.getNextNumber();n.push('<td class="cke_dark_background" style="cursor: default" role="presentation"><a href="javascript: void(0);" role="option" aria-posinset="'+(o+1)+'"',' aria-setsize="'+p+'"',' aria-labelledby="'+t+'"',' style="cursor: inherit; display: block; height: 1.25em; margin-top: 0.25em; text-align: center;" title="',CKEDITOR.tools.htmlEncode(r),'" onkeydown="CKEDITOR.tools.callFunction( '+j+', event, this )"'+' onclick="CKEDITOR.tools.callFunction('+f+', this); return false;"'+' tabindex="-1">'+'<span style="margin: 0 auto;cursor: inherit">'+q+'</span>'+'<span class="cke_voice_label" id="'+t+'">'+r+'</span></a>');}else n.push('<td class="cke_dark_background">&nbsp;');n.push('</td>');}n.push('</tr>');}n.push('</tbody></table>','<span id="'+m+'" class="cke_voice_label">'+c.options+'</span>');this.getContentElement('info','charContainer').getElement().setHtml(n.join(''));},contents:[{id:'info',label:a.lang.common.generalTab,title:a.lang.common.generalTab,padding:0,align:'top',elements:[{type:'hbox',align:'top',widths:['320px','90px'],children:[{type:'html',id:'charContainer',html:'',onMouseover:h,onMouseout:i,focus:function(){var k=this.getElement().getElementsByTag('a').getItem(0);setTimeout(function(){k.focus();h(null,k);});},onShow:function(){var k=this.getElement().getChild([0,0,0,0,0]);setTimeout(function(){k.focus();h(null,k);});},onLoad:function(k){b=k.sender;}},{type:'hbox',align:'top',widths:['100%'],children:[{type:'vbox',align:'top',children:[{type:'html',html:'<div></div>'},{type:'html',id:'charPreview',className:'cke_dark_background',style:"border:1px solid #eeeeee;font-size:28px;height:40px;width:70px;padding-top:9px;font-family:'Microsoft Sans Serif',Arial,Helvetica,Verdana;text-align:center;",html:'<div>&nbsp;</div>'},{type:'html',id:'htmlPreview',className:'cke_dark_background',style:"border:1px solid #eeeeee;font-size:14px;height:20px;width:70px;padding-top:2px;font-family:'Microsoft Sans Serif',Arial,Helvetica,Verdana;text-align:center;",html:'<div>&nbsp;</div>'}]}]}]}]}]};});
