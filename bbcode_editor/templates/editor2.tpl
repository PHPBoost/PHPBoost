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
			},
			
			balise: function(attrs)
			{
				var values = Object.extend(
				{
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
					'title': values.label,
					'onclick': values.onclick
				});
					
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
				
				if(attrs.disabled != '')
				{
					t.setStyle({opacity:0.3, cursor: 'default'});
					t.setAttribute('onclick', 'return false;');
				}
				else
				{
					if (attrs.bbcode != undefined)
						var tags = this.getTags(attrs.bbcode, attrs.bbcode);
					else if ((attrs.begintag != undefined) && (attrs.endtag != undefined))
						var tags = this.getTags(attrs.begintag, attrs.endtag);
					else
						var tags = {begin: '', end: ''};
					
					var str = "insertbbcode('"+tags.begin+"', '"+tags.end+"', '"+this.element+"');";
					t.setAttribute('onclick', str);
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
					else if (x.type == 'balise')
						$(elt).insert(this.balise(x));
					else if (x.type == 'balise2')
						$(elt).insert(this.balise2(x));
				}.bind(this));
			}
			
		});
		</script>
		# ENDIF #
		
		<script type="text/javascript">
		
		var BBcodeEditor = Class.create(BBcodeEditor_Core,
		{
			path: '{PATH_TO_ROOT}/templates/{THEME}/',
			
			get_menu: function(element)
			{
				var menu = '\
					<table id="table-'+element+'" style="margin:4px;margin-left:auto;margin-right:auto;" >\
						<tr>\
							<td>\
								<table class="bbcode">\
									<tr>\
										<td style="padding:1px;"></td>\
									</tr>\
								</table>\
								<table class="bbcode2">\
									<tr>\
										<td style="width:100%;padding:1px;">\
										</td>\
										<td style="width:3px;">\
											<img src="'+this.path+'images/form/separate.png" alt="" />\
										</td>\
										<td style="padding:0px 2px;width:22px;">\
											<img src="'+this.path+'images/form/help.png" alt="" title="{L_BB_HELP}" />\
										</td>\
									</tr>\
								</table>\
							</td>\
							<td style="vertical-align:top;padding-left:8px;padding-top:5px;">\
								# IF C_UPLOAD_MANAGEMENT #\
								<a href="#" onclick="window.open(\'{PATH_TO_ROOT}/member/upload.php?popup=1&amp;fd={FIELD}&amp;edt={EDITOR_NAME}\', \'\', \'height=500,width=720,resizable=yes,scrollbars=yes\');return false;">\
								<img src="'+this.path+'images/upload/files_add.png" alt="" title="{L_BB_UPLOAD}" />\
								</a>\
								# ENDIF #\
							</td>\
						</tr>\
					</table>';
					
				return menu;
			},
		
			block1: [
				{'type':'separator'},
				{'type':'balise', 'fname':'smileys.png', 'label':'{L_BB_SMILEYS}'},
				{'type':'separator'},
				{'type':'balise2', 'fname':'bold.png', 'label':'{L_BB_BOLD}', 'bbcode': 'b', 'disabled':'{DISABLED_B}'},
				{'type':'balise2', 'fname':'italic.png', 'label':'{L_BB_ITALIC}', 'bbcode': 'i', 'disabled':'{DISABLED_I}'},
				{'type':'balise2', 'fname':'underline.png', 'label':'{L_BB_UNDERLINE}', 'bbcode': 'u', 'disabled':'{DISABLED_U}'},
				{'type':'balise2', 'fname':'strike.png', 'label':'{L_BB_STRIKE}', 'bbcode': 's', 'disabled':'{DISABLED_S}'},
				{'type':'separator'},
				{'type':'balise2', 'fname':'title.png', 'label':'{L_BB_TITLE}', 'bbcode': 'title', 'disabled':'{DISABLED_TITLE}'},
				{'type':'balise2', 'fname':'subtitle.png', 'label':'{L_BB_CONTAINER}', 'bbcode': 'subtitle', 'disabled':'{DISABLED_BLOCK}'},
				{'type':'balise2', 'fname':'style.png', 'label':'{L_BB_STYLE}', 'bbcode': 'style', 'disabled':'{DISABLED_STYLE}'},
				{'type':'separator'},
				{'type':'balise2', 'fname':'url.png', 'label':'{L_BB_URL}', 'bbcode': 'url', 'disabled':'{DISABLED_URL}'},
				{'type':'balise2', 'fname':'image.png', 'label':'{L_BB_IMAGE}', 'bbcode': 'img', 'disabled':'{DISABLED_IMAGE}'},
				{'type':'balise2', 'fname':'quote.png', 'label':'{L_BB_QUOTE}', 'bbcode': 'quote', 'disabled':'{DISABLED_QUOTE}'},
				{'type':'balise2', 'fname':'hide.png', 'label':'{L_BB_HIDE}', 'bbcode': 'hide', 'disabled':'{DISABLED_HIDE}'},
				{'type':'balise2', 'fname':'list.png', 'label':'{L_BB_LIST}', 'bbcode': 'list', 'disabled':'{DISABLED_LIST}'},
				{'type':'separator'},
				{'type':'balise2', 'fname':'color.png', 'label':'{L_BB_COLOR}', 'bbcode': 'color', 'disabled':'{DISABLED_COLOR}'},
				{'type':'balise2', 'fname':'size.png', 'label':'{L_BB_SIZE}', 'bbcode': 'size', 'disabled':'{DISABLED_SIZE}'},
				{'type':'separator'},
				{'type':'balise', 'fname':'minus.png', 'label':'{L_BB_SMALL}'},
				{'type':'balise', 'fname':'plus.png', 'label':'{L_BB_LARGE}'},
				{'type':'balise', 'fname':'more.png', 'label':'TODO'}
			],
			
			block2: [
				{'type':'separator'},
				{'type':'balise2', 'fname':'left.png', 'label':'{L_BB_LEFT}', 'begintag': 'align=left', 'endtag': 'align', 'disabled':'{DISABLED_ALIGN}'},
				{'type':'balise2', 'fname':'center.png', 'label':'{L_BB_CENTER}', 'begintag': 'align=center', 'endtag': 'align', 'disabled':'{DISABLED_ALIGN}'},
				{'type':'balise2', 'fname':'right.png', 'label':'{L_BB_RIGHT}', 'begintag': 'align=right', 'endtag':'align', 'disabled':'{DISABLED_ALIGN}'},
				{'type':'balise2', 'fname':'justify.png', 'label':'{L_BB_JUSTIFY}', 'begintag': 'align=justify', 'endtag':'align', 'disabled':'{DISABLED_ALIGN}'},
				{'type':'separator'},
				{'type':'balise2', 'fname':'float_left.png', 'label':'{L_BB_FLOAT_LEFT}', 'begintag': 'float=left', 'endtag':'float', 'disabled':'{DISABLED_FLOAT}'},
				{'type':'balise2', 'fname':'float_right.png', 'label':'{L_BB_FLOAT_RIGHT}', 'begintag': 'float=right', 'endtag':'float', 'disabled':'{DISABLED_FLOAT}'},
				{'type':'balise2', 'fname':'sup.png', 'label':'{L_BB_SUP}', 'bbcode': 'sup', 'disabled':'{DISABLED_SUP}'},
				{'type':'balise2', 'fname':'sub.png', 'label':'{L_BB_SUB}', 'bbcode': 'sub', 'disabled':'{DISABLED_SUB}'},
				{'type':'balise2', 'fname':'indent.png', 'label':'{L_BB_INDENT}', 'bbcode': 'indent', 'disabled':'{DISABLED_INDENT}'},
				{'type':'balise', 'fname':'table.png', 'label':'{L_BB_TABLE}'},
				{'type':'separator'},
				{'type':'balise', 'fname':'flash.png', 'label':'{L_BB_FLASH}'},
				{'type':'balise', 'fname':'movie.png', 'label':'{L_BB_MOVIE}'},
				{'type':'balise', 'fname':'sound.png', 'label':'{L_BB_SOUND}'},
				{'type':'separator'},
				{'type':'balise', 'fname':'code.png', 'label':'{L_BB_CODE}'},
				{'type':'balise', 'fname':'math.png', 'label':'{L_BB_MATH}'},
				{'type':'balise', 'fname':'html.png', 'label':'{L_BB_HTML}'}
			]
			
		});
		
		</script>
		# ENDIF #
