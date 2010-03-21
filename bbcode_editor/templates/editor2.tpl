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
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/framework/js/bbcode.js"></script>
		# ENDIF #
		<script type="text/javascript">
		
		var path = '{PATH_TO_ROOT}/templates/{THEME}/';
		var field = '{FIELD}';
				
		var BBcodeEditor = Class.create(
		{
			initialize: function(element, options)
			{
				this.element = element;
				this.options = options;
				$(element).insert({before: this.get_menu(element)});
				this.display(this.block1);
			},
			
			get_menu: function(element)
			{
				var menu = '\
					<table id="table-'+element+'" style="margin:4px;margin-left:auto;margin-right:auto;">\
						<tr>\
							<td>\
								<table class="bbcode">\
									<tr>\
										<td style="padding:1px;"></td>\
									</tr>\
								</table>\
								<table class="bbcode2" id="bbcode_more{FIELD}">\
									<tr>\
										<td style="width:100%;padding:1px;">\
										</td>\
										<td style="width:3px;">\
											<img src="'+path+'images/form/separate.png" alt="" />\
										</td>\
										<td style="padding:0px 2px;width:22px;">\
											<img src="'+path+'images/form/help.png" alt="{L_BB_HELP}" />\
										</td>\
									</tr>\
								</table>\
							</td>\
							<td style="vertical-align:top;padding-left:8px;padding-top:5px;">\
								# IF C_UPLOAD_MANAGEMENT #\
								<a href="#" onclick="window.open(\'{PATH_TO_ROOT}/member/upload.php?popup=1&amp;fd={FIELD}&amp;edt={EDITOR_NAME}\', \'\', \'height=500,width=720,resizable=yes,scrollbars=yes\');return false;">\
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/files_add.png" alt="" title="{L_BB_UPLOAD}" />\
								</a>\
								# ENDIF #\
							</td>\
						</tr>\
					</table>';
					
				return menu;
			},
			
			balise: function(attrs)
			{
				var values = Object.extend({'fname': '', 'label':'', 'classe':'bbcode_hover', 'onclick':''}, attrs || {});
			
				return new Element('img',
					{'src': path+'images/form/'+values.fname,
					'alt': values.label,
					'class':values.classe,
					'title':values.label,
					'onclick':values.onclick});
			},
		
			separator: function()
			{
				var sep = {'fname':'separate.png'};
				return this.balise(sep);
			},

			balise2: function(attrs)
			{
				if(attrs.disabled != "")
					var fn = '';
				else
					var fn = function() {
						begin = '[' + attrs.bbcode + ']';
						end = '[/' + attrs.bbcode + ']';			
						insertbbcode(begin, end, field);
					}.bind(attrs);
					
				attrs.onclick = fn;
				return this.balise(attrs);
			},
			
			block1: [
				{'type':'separator'},
				{'type':'balise', 'fname':'smileys.png', 'label':'{L_BB_SMILEYS}', 'onclick': function() {bb_display_block('1', field);}},
				{'type':'separator'},
				{'type':'balise2', 'fname':'bold.png', 'label':'{L_BB_BOLD}', 'bbcode': 'b', 'disabled':'{DISABLED_B}'},
				{'type':'balise2', 'fname':'italic.png', 'label':'{L_BB_ITALIC}', 'bbcode': 'i', 'disabled':'{DISABLED_I}'},
				{'type':'balise2', 'fname':'underline.png', 'label':'{L_BB_UNDERLINE}', 'bbcode': 'u', 'disabled':'{DISABLED_U}'},
				{'type':'balise2', 'fname':'strike.png', 'label':'{L_BB_STRIKE}', 'bbcode': 's', 'disabled':'{DISABLED_S}'},
				{'type':'separator'}
			],
			
			display: function(bloc)
			{
				var elt = $$('table#table-'+this.element+' table.bbcode td');
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
