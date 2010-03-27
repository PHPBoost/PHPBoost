		<div id="div1"></div>

		<script type="text/javascript">
		<!--
		var displayed = new Array();
		displayed['{FIELD}'] = false;
		function XMLHttpRequest_preview(field)
		{
			if( XMLHttpRequest_preview.arguments.length == 0 )
 			    field = '{FIELD}';

			{TINYMCE_TRIGGER}
			var contents = document.getElementById(field).value;
			
			if( contents != "" )
			{
				if( !displayed[field] ) 
					Effect.BlindDown('xmlhttprequest_preview' + field, { duration: 0.5 });
					
				if( document.getElementById('loading_preview' + field) )
					document.getElementById('loading_preview' + field).style.display = 'block';
				displayed[field] = true;			

				new Ajax.Request(
					'{PATH_TO_ROOT}/kernel/framework/ajax/content_xmlhttprequest.php',
					{
						method: 'post',
						parameters: {
							token: '{TOKEN}',
							path_to_root: '{PHP_PATH_TO_ROOT}',
							editor: '{EDITOR_NAME}',
							page_path: '{PAGE_PATH}',  
							contents: contents,
							ftags: '{FORBIDDEN_TAGS}'
						 },
						onSuccess: function(response)
						{
							document.getElementById('xmlhttprequest_preview' + field).innerHTML = response.responseText;
							if( document.getElementById('loading_preview' + field) )
								document.getElementById('loading_preview' + field).style.display = 'none';
						}
					}
				);
			}	
			else
				alert("{L_REQUIRE_TEXT}");
		}
		-->
		</script>
		<div style="position:relative;display:none;" id="loading_preview{FIELD}"><div style="margin:auto;margin-top:90px;width:100%;text-align:center;position:absolute;"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading.gif" alt="" /></div></div>
		<div style="display:none;" class="xmlhttprequest_preview" id="xmlhttprequest_preview{FIELD}"></div>
		
		# IF C_BBCODE_NORMAL_MODE #
		# IF C_EDITOR_NOT_ALREADY_INCLUDED #
		<script type="text/javascript" src="{PATH_TO_ROOT}/bbcode_editor/templates/bbcode.js"></script>

		<script type="text/javascript">
		var BBcodeEditor_Core = Class.create(
		{
			initialize: function(element, options)
			{
				this.element = element;
				this.options = options;
				$(element).insert({before: this.get_menu(element)});
				this.display(this.block1, 'bbcode');
				this.display(this.block2, 'bbcode2');
				this.menuSmileys(1);
				this.menuTitles(2);
				this.menuSubTitles(3);
				this.menuStyles(4);
				this.menuColors(5);
				this.menuSizes(6);
				this.menuCodes(7);
			},
			
			balise: function(attrs)
			{
				var values = Object.extend(
				{
					'id': '',
					'fname': '',
					'label':'',
					'classe':'bbcode_hover',
					'onclick':''
				}, attrs || {});
				
				var img = new Element('img',
				{
					'src': this.path+'images/form/'+values.fname,
					'alt': '',
					'class': values.classe,
					'title': values.label
				});

				if(values.id)
				{
					img.setAttribute('id', values.id + this.element);
				}

				if(Object.isFunction(values.onclick))
					Event.observe(img, 'click', values.onclick);

				return img;
			},
		
			separator: function()
			{
				var sep = {'fname':'separate.png'};
				return this.balise(sep);
			},
			
			balise2: function(attrs)
			{
				var t = this.balise(attrs);
				
				var fn = function()
					{
						insertbbcode(tags.begin, tags.end, this.element);
					}.bind(this);
				
				if(attrs.disabled != '')
				{
					t.setStyle({opacity:0.3, cursor: 'default'});
					Event.stopObserving(t, 'click', fn);
				}
				else
				{
					if (attrs.tag)
						var tags = this.getTags(attrs.tag, attrs.tag);
					else if ((attrs.begintag) && (attrs.endtag))
						var tags = this.getTags(attrs.begintag, attrs.endtag);
					else
						var tags = {begin: '', end: ''};
					
					Event.observe(t, 'click', fn);
				}
				return t;
			},
			
			getTags: function(begin, end)
			{
				return {'begin': "["+begin+"]", 'end': "[/"+end+"]"};
			},

			display: function(bloc, classe)
			{
				var elt = $$('table#table-'+this.element+' table.'+classe+' td');
				elt = elt.first();
				
				bloc.each( function(x)
				{
					if (x.type == 'separator')
						$(elt).insert(this.separator());
					else if (x.type == 'balise') {
						var t = this.balise(x);
						$(elt).insert(t);
					}
					else if (x.type == 'balise2')
						$(elt).insert(this.balise2(x));
				}.bind(this));
			},
							
			menuSmileys: function(index)
			{
				var div = new Element('div', {id: 'bb_block'+index+this.element});
				div.setStyle({
					'position':'relative',
					'zIndex':100,
					'marginLeft':'-50px',
					'float':'left',
					'display':'none'
				});
					
				var elt = new Element('div', {'class':'bbcode_block'});
				elt.setStyle({width: '130px'});
				var i = 0;
				this.smileys.each( function(x) {
					if(x.url)
					{
						var d = new Element('img', {
									src: this.path_img+x.url,
									height: x.height,
									width: x.width
						});
						if(x.code)
						{
							var fn = function() {
								insertbbcode(' '+x.code+' ', '', this.element);
							}.bind(this);
							Event.observe(d, 'click', fn);
						}

						elt.insert(d);
						i++;
						if ((i % 5) == 0) elt.insert('<br />');
					}
				}.bind(this));
				
				div.update(elt);
				$('smileys'+this.element).insert({before:div});
			},
			
			menuTitles: function(index)
			{
				var div = new Element('div', {'id': 'bb_block'+index+this.element});
				div.setStyle({
					'position':'relative',
					'zIndex':100,
					'marginLeft':'0px',
					'float':'left',
					'display':'none'
				});
					
				var elt = new Element('div', {'class':'bbcode_block'});
				var sel = new Element('select');
				
				var first = true;
				this.titles.each(function(x)
				{
					if (first)
					{
						var opt = new Element('option', {'value':x.value, 'selected':'selected', 'disabled':'disabled'});
						first = false;
					}
					else
					{
						var opt = new Element('option', {'value':x.value});
					}
					opt.update(x.label);
					sel.insert(opt);
				});			
				
				elt.insert(sel);
				div.update(elt);
				$('title'+this.element).insert({before:div});
			},
			
			menuSubTitles: function(index)
			{
				var div = new Element('div', {id: 'bb_block'+index+this.element});
				div.setStyle({
					'position':'relative',
					'zIndex':100,
					'marginLeft':'0px',
					'float':'left',
					'display':'none'
				});
					
				var elt = new Element('div', {'class':'bbcode_block'});
				var sel = new Element('select');
				
				var first = true;
				this.subtitles.each(function(x)
				{
					if (first)
					{
						var opt = new Element('option', {'value':x.value, 'selected':'selected', 'disabled':'disabled'});
						first = false;
					}
					else
					{
						var opt = new Element('option', {'value':x.value});
					}
					opt.update(x.label);
					sel.insert(opt);
				});								
				
				elt.insert(sel);
				div.update(elt);
				$('subtitle'+this.element).insert({before:div});
			},
			
			menuStyles: function(index)
			{
				var div = new Element('div', {'id': 'bb_block'+index+this.element});
				div.setStyle({
					'position':'relative',
					'zIndex':100,
					'marginLeft':'0px',
					'float':'left',
					'display':'none'
				});
					
				var elt = new Element('div', {'class':'bbcode_block'});
				var sel = new Element('select');
				
				var first = true;
				this.styles.each(function(x)
				{
					if (first)
					{
						var opt = new Element('option', {'value':x.value, 'selected':'selected', 'disabled':'disabled'});
						first = false;
					}
					else
					{
						var opt = new Element('option', {'value':x.value});
					}
					opt.update(x.label);
					sel.insert(opt);
				});								
				
				elt.insert(sel);
				div.update(elt);
				$('style'+this.element).insert({before:div});
			},
			
			menuSizes: function(index)
			{
				var div = new Element('div', {id: 'bb_block'+index+this.element});
				div.setStyle({
					'position':'relative',
					'zIndex':100,
					'marginLeft':'0px',
					'float':'left',
					'display':'none'
				});
					
				var elt = new Element('div', {'class':'bbcode_block'});
				var sel = new Element('select');
				
				this.sizes.each(function(x)
				{
					if (!x.value)
					{
						var opt = new Element('option', {'value':x.value, 'selected':'selected', 'disabled':'disabled'});
					}
					else
					{
						var opt = new Element('option', {'value':x.value});
					}
					opt.update(x.label);
					sel.insert(opt);
				});								
				
				elt.insert(sel);
				div.update(elt);
				$('size'+this.element).insert({before:div});
			},
			
			menuColors: function(index)
			{
				var div = new Element('div', {'id': 'bb_block'+index+this.element});
				div.setStyle({
					'position':'relative',
					'zIndex':100,
					'marginLeft':'0px',
					'float':'left',
					'display':'none'
				});
					
				var elt = new Element('div', {'class':'bbcode_block'});

				var xtable = new Element('table');
				xtable.setStyle({margin:'auto'});
				var xtr = new Element('tr');
				xtable.insert(xtr);
				for(i = 0; i < 40; i++)
				{
					br = (i+1) % 8;
					if (br == 0)
					{
						var xtr = new Element('tr');
						xtable.insert(xtr);
					}
					else
					{
						var xtd = new Element('td');
						xtd.setStyle({padding:'2px'});
						var xspan = new Element('span');
						xspan.setStyle({'background':this.colors[i], 'padding':'0px 4px', 'border':'1px solid #ACA899'});
						xspan.update('&nbsp;');
						xtd.insert(xspan);
						xtr.insert(xtd);
					}
				}
				
				elt.insert(xtable);
				
				div.update(elt);
				$('color'+this.element).insert({before:div});
			},
			
			menuCodes: function(index)
			{
				var div = new Element('div', {'id': 'bb_block'+index+this.element});
				div.setStyle({
					'position':'relative',
					'zIndex':100,
					'marginLeft':'0px',
					'float':'left',
					'display':'none'
				});
					
				var elt = new Element('div', {'class':'bbcode_block'});
				var sel = new Element('select');
				
				this.codes.each(function(x)
				{
					if (!x.group)
					{
						var opt = new Element('option', {'value':x.value, 'selected':'selected', 'disabled':'disabled'});
					}
					else
					{
						var optgrp = new Element('optgroup', {'label': x.group});
						x.data.each( function(y)
						{
							var opt = new Element('option', {'value':y.value});
							opt.update(y.label);
							optgrp.insert(opt);
						});
						var opt = optgrp;
					}
					if (x.label)
						opt.update(x.label);
					sel.insert(opt);
				});		
				
				elt.insert(sel);
				div.update(elt);
				$('size'+this.element).insert({before:div});
			}
			
		});
		
		function displayDiv(divID)
		{
			var div = $('bbcode_more'+divID);
			if (div)
			{
				var tmp = div.getStyle('display');
				if(tmp != 'block') tmp = 'block'; else tmp = 'none';
				div.setStyle({'display': tmp});
			}
		}
		
		</script>
		# ENDIF #
		
		<script type="text/javascript">
		
		var BBcodeEditor = Class.create(BBcodeEditor_Core,
		{
			field: '{FIELD}',
			path: '{PATH_TO_ROOT}/templates/{THEME}/',
			path_img: '{PATH_TO_ROOT}/images/smileys/',
			
			get_menu: function(element)
			{
				var xtd = new Element('td');
				xtd.setStyle({padding:'1px'});
				var xtr = new Element('tr');
				xtr.insert(xtd);
				var xtbody = new Element('tbody');
				xtbody.insert(xtr);
				var xtable1 = new Element('table',{'class':'bbcode'});
				xtable1.insert(xtbody);
				
				var xtd2 = new Element('td');
				xtd2.setStyle({padding:'1px', width:'100%'});
				var xtd3 = new Element('td');
				xtd3.setStyle({width:'3px'});
				var img = new Element('img', {
					'src': this.path+'images/form/separate.png',
					'alt': ''});
				xtd3.update(img);
				var xtd4 = new Element('td');
				xtd4.setStyle({width:'22px', padding:'0px 2px'});
				var img2 = new Element('img', {
					'src': this.path+'images/form/help.png',
					'alt': '',
					'title': '{L_BB_HELP}'});
				xtd4.update(img2);
				var xtr2 = new Element('tr');
				xtr2.insert(xtd2);
				xtr2.insert(xtd3);
				xtr2.insert(xtd4);
				var xtbody2 = new Element('tbody');
				xtbody2.insert(xtr2);
				var xtable2 = new Element('table',{'class': 'bbcode2', 'id':'bbcode_more'+element});
				xtable2.setStyle({display:'none'});
				xtable2.insert(xtbody2);
				
				var xtd10 = new Element('td');
				xtd10.insert(xtable1);
				xtd10.insert(xtable2);
				var xtr10 = new Element('tr');
				xtr10.insert(xtd10);
				var xtbody10 = new Element('tbody');
				xtbody10.insert(xtr10);
				var xtable10 = new Element('table', {'id': 'table-'+element});
				xtable10.setStyle({'margin':'4px', 'marginLeft':'auto', 'marginRight':'auto'});
				xtable10.insert(xtbody10);
				
				menu = xtable10;
				return menu;
			},
		
			block1: [
				{'type':'separator'},
				{'type':'balise', 'id':'smileys', 'fname':'smileys.png', 'label':'{L_BB_SMILEYS}',
						'disabled':'{DISABLED_SMILEYS}', 'onclick': function() {bb_display_block('1', 'contents');} },
				{'type':'separator'},
				{'type':'balise2', 'fname':'bold.png', 'label':'{L_BB_BOLD}', 'tag': 'b', 'disabled':'{DISABLED_B}'},
				{'type':'balise2', 'fname':'italic.png', 'label':'{L_BB_ITALIC}', 'tag': 'i', 'disabled':'{DISABLED_I}'},
				{'type':'balise2', 'fname':'underline.png', 'label':'{L_BB_UNDERLINE}', 'tag': 'u', 'disabled':'{DISABLED_U}'},
				{'type':'balise2', 'fname':'strike.png', 'label':'{L_BB_STRIKE}', 'tag': 's', 'disabled':'{DISABLED_S}'},
				{'type':'separator'},
				{'type':'balise', 'id':'title', 'fname':'title.png', 'label':'{L_BB_TITLE}', 'tag': 'title',
						'disabled':'{DISABLED_TITLE}', 'onclick': function() {bb_display_block('2', 'contents');}},
				{'type':'balise', 'id':'subtitle', 'fname':'subtitle.png', 'label':'{L_BB_CONTAINER}', 'tag': 'subtitle',
						'disabled':'{DISABLED_BLOCK}', 'onclick': function() {bb_display_block('3', 'contents');}},
				{'type':'balise', 'id':'style', 'fname':'style.png', 'label':'{L_BB_STYLE}', 'tag': 'style',
						'disabled':'{DISABLED_STYLE}', 'onclick': function() {bb_display_block('4', 'contents');}},
				{'type':'separator'},
				{'type':'balise2', 'fname':'url.png', 'label':'{L_BB_URL}', 'tag': 'url', 'disabled':'{DISABLED_URL}'},
				{'type':'balise2', 'fname':'image.png', 'label':'{L_BB_IMAGE}', 'tag': 'img', 'disabled':'{DISABLED_IMAGE}'},
				{'type':'balise2', 'fname':'quote.png', 'label':'{L_BB_QUOTE}', 'tag': 'quote', 'disabled':'{DISABLED_QUOTE}'},
				{'type':'balise2', 'fname':'hide.png', 'label':'{L_BB_HIDE}', 'tag': 'hide', 'disabled':'{DISABLED_HIDE}'},
				{'type':'balise2', 'fname':'list.png', 'label':'{L_BB_LIST}', 'tag': 'list', 'disabled':'{DISABLED_LIST}'},
				{'type':'separator'},
				{'type':'balise', 'id':'color', 'fname':'color.png', 'label':'{L_BB_COLOR}', 'tag': 'color',
						'disabled':'{DISABLED_COLOR}', 'onclick': function() {bb_display_block('5', 'contents');}},
				{'type':'balise', 'id':'size', 'fname':'size.png', 'label':'{L_BB_SIZE}', 'tag': 'size',
						'disabled':'{DISABLED_SIZE}', 'onclick': function() {bb_display_block('6', 'contents');}},
				{'type':'separator'},
				{'type':'balise', 'fname':'minus.png', 'label':'{L_BB_SMALL}'},
				{'type':'balise', 'fname':'plus.png', 'label':'{L_BB_LARGE}'},
				{'type':'balise', 'fname':'more.png', 'label':'TODO',
						'onclick': function() {displayDiv('contents');}}
			],
			
			block2: [
				{'type':'separator'},
				{'type':'balise2', 'fname':'left.png', 'label':'{L_BB_LEFT}', 'begintag': 'align=left', 'endtag': 'align',
						'disabled':'{DISABLED_ALIGN}'},
				{'type':'balise2', 'fname':'center.png', 'label':'{L_BB_CENTER}', 'begintag': 'align=center', 'endtag': 'align',
						'disabled':'{DISABLED_ALIGN}'},
				{'type':'balise2', 'fname':'right.png', 'label':'{L_BB_RIGHT}', 'begintag': 'align=right', 'endtag':'align',
						'disabled':'{DISABLED_ALIGN}'},
				{'type':'balise2', 'fname':'justify.png', 'label':'{L_BB_JUSTIFY}', 'begintag': 'align=justify', 'endtag':'align',
						'disabled':'{DISABLED_ALIGN}'},
				{'type':'separator'},
				{'type':'balise2', 'fname':'float_left.png', 'label':'{L_BB_FLOAT_LEFT}', 'begintag': 'float=left', 'endtag':'float',
						'disabled':'{DISABLED_FLOAT}'},
				{'type':'balise2', 'fname':'float_right.png', 'label':'{L_BB_FLOAT_RIGHT}', 'begintag': 'float=right', 'endtag':'float',
						'disabled':'{DISABLED_FLOAT}'},
				{'type':'balise2', 'fname':'sup.png', 'label':'{L_BB_SUP}', 'tag': 'sup',
						'disabled':'{DISABLED_SUP}'},
				{'type':'balise2', 'fname':'sub.png', 'label':'{L_BB_SUB}', 'tag': 'sub',
						'disabled':'{DISABLED_SUB}'},
				{'type':'balise2', 'fname':'indent.png', 'label':'{L_BB_INDENT}', 'tag': 'indent',
						'disabled':'{DISABLED_INDENT}'},
				{'type':'balise', 'fname':'table.png', 'label':'{L_BB_TABLE}'},
				{'type':'separator'},
				{'type':'balise', 'fname':'flash.png', 'label':'{L_BB_FLASH}'},
				{'type':'balise', 'fname':'movie.png', 'label':'{L_BB_MOVIE}'},
				{'type':'balise', 'fname':'sound.png', 'label':'{L_BB_SOUND}'},
				{'type':'separator'},
				{'type':'balise', 'id':'code', 'fname':'code.png', 'label':'{L_BB_CODE}',
					'disabled':'{DISABLED_CODE}', 'onclick': function() {bb_display_block('7', 'contents');}},
				{'type':'balise2', 'fname':'math.png', 'label':'{L_BB_MATH}', 'tag': 'math', 'disabled':'{DISABLED_MATH}'},
				{'type':'balise2', 'fname':'html.png', 'label':'{L_BB_HTML}', 'tag': 'html', 'disabled':'{DISABLED_HTML}'}
			],
			
			smileys: [
			# START smileys #
			{'url': '{smileys.URL}', 'height': {smileys.HEIGHT}, 'width': {smileys.WIDTH}, 'code': '{smileys.CODE}'}, 
			# END smileys #
			{}
			],
			
			titles: [
				{'value':'', 'label':'{L_TITLE}'},
				{'value':1, 'label':'{L_TITLE}'+1},
				{'value':2, 'label':'{L_TITLE}'+2},
				{'value':3, 'label':'{L_TITLE}'+3},
				{'value':4, 'label':'{L_TITLE}'+4}
			],
			
			subtitles: [
				{'value':'', 'label':'{L_CONTAINER}'},
				{'value':'block', 'label':'{L_BLOCK}'},
				{'value':'fieldset', 'label':'{L_FIELDSET}'}
			],
			
			styles: [
				{'value':'', 'label':'{L_STYLE}'},
				{'value':'success', 'label':'{L_SUCCESS}'},
				{'value':'question', 'label':'{L_QUESTION}'},
				{'value':'notice', 'label':'{L_NOTICE}'},
				{'value':'warning', 'label':'{L_WARNING}'},
				{'value':'error', 'label':'{L_ERROR}'}
			],
			
			sizes: [
				{'value':'', 'label':'{L_SIZE}'},
				{'value':5, 'label':5},
				{'value':10, 'label':10},
				{'value':15, 'label':15},
				{'value':20, 'label':20},
				{'value':25, 'label':25},
				{'value':30, 'label':30},
				{'value':35, 'label':35},
				{'value':40, 'label':40},
				{'value':45, 'label':45}
			],
			
			colors: new Array(
				'black', 'maroon', '#333300', '#003300', '#003366', '#000080', '#333399', '#333333',
				'#800000', 'orange', '#808000', 'green', '#008080', 'blue', '#666699', '#808080',
				'red', '#FF9900', '#99CC00', '#339966', '#33CCCC', '#3366FF', '#800080', '#ACA899',
				'pink', '#FFCC00', 'yellow', '#00FF00', '#00FFFF', '#00CCFF', '#993366', '#C0C0C0',
				'#FF99CC', '#FFCC99', '#FFFF99', '#CCFFCC', '#CCFFFF', '#CC99FF', '#CC99FF', 'white'
			),
			
			codes: [
				{'value':'', 'label':'{L_CODE}'},
				{'group': '{L_TEXT}', 'data': [
					{'value':'text', 'label':'Text'},
					{'value':'sql', 'label':'Sql'},
					{'value':'xml', 'label':'Xml'}					
					]},
				{'group': '{L_PHPBOOST_LANGUAGES}', 'data': [
					{'value':'bbcode', 'label':'BBCode'},
					{'value':'tpl', 'label':'Template'}
					]},
				{'group': '{L_SCRIPT}', 'data': [
					{'value':'php', 'label':'PHP'},
					{'value':'asp', 'label':'Asp'},
					{'value':'python', 'label':'Python'},
					{'value':'perl', 'label':'Perl'},
					{'value':'ruby', 'label':'Ruby'},
					{'value':'bash', 'label':'Bash'}
					]},
				{'group': '{L_WEB}', 'data': [
					{'value':'html', 'label':'Html'},
					{'value':'css', 'label':'Css'},
					{'value':'javascript', 'label':'Javascript'}
					]},
				{'group': '{L_PROG}', 'data': [
					{'value':'c', 'label':'C'},
					{'value':'cpp', 'label':'C++'},
					{'value':'c#', 'label':'C#'},
					{'value':'d', 'label':'D'},
					{'value':'java', 'label':'Java'},
					{'value':'delphi', 'label':'Delphi'},
					{'value':'fortran', 'label':'Fortran'},
					{'value':'vb', 'label':'VB'},
					{'value':'asm', 'label':'Asm'}
					]}
			]
		});
		
		</script>
		# ENDIF #
