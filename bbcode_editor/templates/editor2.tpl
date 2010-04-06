		<script type="text/javascript">
		<!--
		var displayed = new Array();
		displayed['{FIELD}'] = false;
		function XMLHttpRequest_preview(field)
		{
			if( XMLHttpRequest_preview.arguments.length == 0 )
 			    field = '{FIELD}';

			{TINYMCE_TRIGGER}
			var contents = $(field).value;
			
			if( contents != "" )
			{
				if( !displayed[field] ) 
					Effect.BlindDown('xmlhttprequest_preview' + field, { duration: 0.5 });
				
				var elt = $('loading_preview' + field);
				if( elt )
					elt.style.display = 'block';
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
							$('xmlhttprequest_preview' + field).update(response.responseText);
							var elt = $('loading_preview' + field);
							if( elt )
								elt.style.display = 'none';
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
		<script type="text/javascript" src="{PATH_TO_ROOT}/bbcode_editor/templates/bbcode_editor.js"></script>
		# ENDIF #
		
		<script type="text/javascript">
		var BBcodeEditor = Class.create(BBcodeEditor_Core,
		{
			root: '{PATH_TO_ROOT}',
			path: '{PATH_TO_ROOT}/templates/{THEME}/',
			path_img: '{PATH_TO_ROOT}/images/smileys/',
			
			upload: {'type':{C_UPLOAD_MANAGEMENT} , 'title':'{L_BB_UPLOAD}', 'editor':'{EDITOR_NAME}' },
		
			block1: [
				{'type':'separator'},
				{'type':'menu_smileys', 'id':'smileys', 'fname':'smileys.png', 'label':'{L_BB_SMILEYS}', 'disabled':'{DISABLED_SMILEYS}'},
				{'type':'separator'},
				{'type':'balise2', 'fname':'bold.png', 'label':'{L_BB_BOLD}', 'tag': 'b', 'disabled':'{DISABLED_B}'},
				{'type':'balise2', 'fname':'italic.png', 'label':'{L_BB_ITALIC}', 'tag': 'i', 'disabled':'{DISABLED_I}'},
				{'type':'balise2', 'fname':'underline.png', 'label':'{L_BB_UNDERLINE}', 'tag': 'u', 'disabled':'{DISABLED_U}'},
				{'type':'balise2', 'fname':'strike.png', 'label':'{L_BB_STRIKE}', 'tag': 's', 'disabled':'{DISABLED_S}'},
				{'type':'separator'},
				{'type':'menu_title', 'id':'title', 'fname':'title.png', 'label':'{L_BB_TITLE}', 'tag': 'title', 'disabled':'{DISABLED_TITLE}'},
				{'type':'menu_subtitle', 'id':'subtitle', 'fname':'subtitle.png', 'label':'{L_BB_CONTAINER}', 'disabled':'{DISABLED_BLOCK}'},
				{'type':'menu_style', 'id':'style', 'fname':'style.png', 'label':'{L_BB_STYLE}', 'tag': 'style', 'disabled':'{DISABLED_STYLE}'},
				{'type':'separator'},
				{'type':'action_prompt', 'fname':'url.png', 'label':'{L_BB_URL}', 'tag': 'url', 'disabled':'{DISABLED_URL}', 'prompt':'{L_URL_PROMPT}' },
				{'type':'balise2', 'fname':'image.png', 'label':'{L_BB_IMAGE}', 'tag': 'img', 'disabled':'{DISABLED_IMAGE}'},
				{'type':'balise2', 'fname':'quote.png', 'label':'{L_BB_QUOTE}', 'tag': 'quote', 'disabled':'{DISABLED_QUOTE}'},
				{'type':'balise2', 'fname':'hide.png', 'label':'{L_BB_HIDE}', 'tag': 'hide', 'disabled':'{DISABLED_HIDE}'},
				{'type':'menu_list', 'id':'list', 'fname':'list.png', 'label':'{L_BB_LIST}', 'disabled':'{DISABLED_LIST}'},
				{'type':'separator'},
				{'type':'menu_color', 'id':'color', 'fname':'color.png', 'label':'{L_BB_COLOR}', 'tag': 'color', 'disabled':'{DISABLED_COLOR}'},
				{'type':'menu_size', 'id':'size', 'fname':'size.png', 'label':'{L_BB_SIZE}', 'tag': 'size', 'disabled':'{DISABLED_SIZE}'},
				{'type':'separator'},
				{'type':'action_resize', 'fname':'minus.png', 'label':'{L_BB_SMALL}', 'height':-100 },
				{'type':'action_resize', 'fname':'plus.png', 'label':'{L_BB_LARGE}', 'height':100 },
				{'type':'action_more', 'fname':'more.png', 'label':'{L_BB_MORE}'}
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
				{'type':'balise2', 'fname':'sup.png', 'label':'{L_BB_SUP}', 'tag': 'sup', 'disabled':'{DISABLED_SUP}'},
				{'type':'balise2', 'fname':'sub.png', 'label':'{L_BB_SUB}', 'tag': 'sub', 'disabled':'{DISABLED_SUB}'},
				{'type':'balise2', 'fname':'indent.png', 'label':'{L_BB_INDENT}', 'tag': 'indent', 'disabled':'{DISABLED_INDENT}'},
				{'type':'menu_table', 'id':'table', 'fname':'table.png', 'label':'{L_BB_TABLE}', 'disabled':'{DISABLED_TABLE}'},
				{'type':'separator'},
				{'type':'balise2', 'fname':'flash.png', 'label':'{L_BB_FLASH}', 'begintag': 'swf=425,344', 'endtag':'swf', 'disabled':'{DISABLED_ALIGN}'},
				{'type':'balise2', 'fname':'movie.png', 'label':'{L_BB_MOVIE}', 'begintag': 'movie=100,100', 'endtag':'movie', 'disabled':'{DISABLED_ALIGN}'},
				{'type':'balise2', 'fname':'sound.png', 'label':'{L_BB_SOUND}', 'tag': 'sound', 'disabled':'{DISABLED_SOUND}'},
				{'type':'separator'},
				{'type':'menu_code', 'id':'code', 'fname':'code.png', 'label':'{L_BB_CODE}', 'tag':'code', 'disabled':'{DISABLED_CODE}'},
				{'type':'balise2', 'fname':'math.png', 'label':'{L_BB_MATH}', 'tag': 'math', 'disabled':'{DISABLED_MATH}'},
				{'type':'balise2', 'fname':'html.png', 'label':'{L_BB_HTML}', 'tag': 'html', 'disabled':'{DISABLED_HTML}'},
				{'type':'separator'},
				{'type':'action_help', 'fname':'help.png', 'label':'{L_BB_HELP}', 'disabled':'{DISABLED_HELP}'}				
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
			
			colors: [
				'black', 'maroon', '#333300', '#003300', '#003366', '#000080', '#333399', '#333333',
				'#800000', 'orange', '#808000', 'green', '#008080', 'blue', '#666699', '#808080',
				'red', '#FF9900', '#99CC00', '#339966', '#33CCCC', '#3366FF', '#800080', '#ACA899',
				'pink', '#FFCC00', 'yellow', '#00FF00', '#00FFFF', '#00CCFF', '#993366', '#C0C0C0',
				'#FF99CC', '#FFCC99', '#FFFF99', '#CCFFCC', '#CCFFFF', '#CC99FF', '#CC99FF', 'white'
			],
			
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
			],

			tables: [
				{'type':'text', 'text': '* {L_LINES}', 'id':'bb_lines', 'size':3, 'maxlength':3, 'value':2},
				{'type':'text', 'text': '* {L_COLS}', 'id':'bb_cols', 'size':3, 'maxlength':3, 'value':2},
				{'type':'checkbox', 'text': '{L_ADD_HEAD}', 'id':'bb_head'},
				{'type':'submit', 'text':'{L_INSERT_TABLE}', 'fname':'table.png', 'label':'{L_BB_TABLE}', 'classe':'valign_middle', 'head':'{L_TABLE_HEAD}'}
			],
			
			lists: [
				{'type':'text', 'text': '* {L_LINES}', 'id':'bb_list', 'size':3, 'maxlength':3, 'value':3},
				{'type':'checkbox', 'text': '{L_ORDERED_LIST}', 'id':'bb_ordered_list'},
				{'type':'submit', 'text':'{L_INSERT_LIST}', 'fname':'list.png', 'label':'{L_BB_LIST}', 'classe':'valign_middle' }
			]
		});
		
		</script>
	# ENDIF #
