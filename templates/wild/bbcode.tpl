		# START tinymce_mode #			
		<script language="javascript" type="text/javascript" src="../includes/tinymce/tiny_mce.js"></script>
		<script language="javascript" type="text/javascript">
		<!--
		tinyMCE.init({
			mode : "exact",
			elements : "{FIELD}", 
			theme : "advanced",
			language : "fr",
			content_css : "../templates/{THEME}/tinymce.css",
			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,sub,sup,charmap,|,undo,redo,|,image,link,unlink,anchor", 
			theme_advanced_buttons2 : "forecolor,backcolor,|,outdent,indent,|,fontsizeselect,formatselect,|,cleanup,removeformat,|,table,split_cells,merge_cells,flash", 
			theme_advanced_buttons3 : "",
			theme_advanced_toolbar_location : "top", 
			theme_advanced_toolbar_align : "center", 
			theme_advanced_statusbar_location : "bottom",
			plugins : "table,flash",
			extended_valid_elements : "font[face|size|color|style],span[class|align|style],a[href|name]",
			theme_advanced_resize_horizontal : false, 
			theme_advanced_resizing : true
		});
		-->
		</script>
		# END tinymce_mode #
		
		<script type="text/javascript" src="../templates/{THEME}/images/bbcode.js"></script>
		<script type="text/javascript">
		<!--
		function XMLHttpRequest_preview()
		{
			var xhr_object = null;
			var data = null;
			var filename = "../includes/xmlhttprequest.php?preview=1";
			{TINYMCE_TRIGGER}
			var contents = document.getElementById('{FIELD}').value;
			var forbidden_tags = "";
			var data = null;
			
			if( document.getElementById('{FIELD}_ftags') )
				forbidden_tags = document.getElementById('{FIELD}_ftags').value;
					
			if(window.XMLHttpRequest) // Firefox
			   xhr_object = new XMLHttpRequest();
			else if(window.ActiveXObject) // Internet Explorer
			   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
			else // XMLHttpRequest non support� par le navigateur
			    return;
			
			if( contents != "" )
			{
				contents = escape_xmlhttprequest(contents);
				data = "contents=" + contents + "&ftags=" + forbidden_tags;			
			  
				xhr_object.open("POST", filename, true);
				xhr_object.onreadystatechange = function() 
				{
					if( xhr_object.readyState == 4 ) 
					{
						show_bbcode_div('xmlhttprequest_preview', 0);						
						document.getElementById("xmlhttprequest_preview").innerHTML = xhr_object.responseText;
					}
				}

				xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

				xhr_object.send(data);
			}	
			else
				alert("{L_REQUIRE_TEXT}");
		}
		-->
		</script>
		<div style="display:none;" class="xmlhttprequest_preview" id="xmlhttprequest_preview"></div>
		
		# START bbcode_mode #
		<script type="text/javascript">
		<!--
		function bbcode_color_{FIELD}()
		{
			var i;
			var br;
			var contents;
			var color = new Array(
			'black', 'maroon', '#333300', '#003300', '#003366', '#000080', '#333399', '#333333',
			'#800000', 'orange', '#808000', 'green', '#008080', 'blue', '#666699', '#808080',
			'red', '#FF9900', '#99CC00', '#339966', '#33CCCC', '#3366FF', '#800080', '#ACA899',
			'pink', '#FFCC00', 'yellow', '#00FF00', '#00FFFF', '#00CCFF', '#993366', '#C0C0C0',
			'#FF99CC', '#FFCC99', '#FFFF99', '#CCFFCC', '#CCFFFF', '#CC99FF', '#CC99FF', 'white');							
			
			
			contents = '<table style="border-collapse:collapse;margin:auto;"><tr>';
			for(i = 0; i < 40; i++)
			{
				br = (i+1) % 8;
				br = (br == 0 && i != 0 && i < 39) ? '</tr><tr>' : '';
				contents += '<td style="padding:2px;"><a onclick="insertbbcode(\'[color=' + color[i] + ']\', \'[/color]\', \'{FIELD}\');" class="bbcode_hover"><span style="background:' + color[i] + ';padding:0px 4px;border:1px solid #ACA899;">&nbsp;</span></a></td>' + br;								
			}
			document.getElementById("bbcolor{FIELD}").innerHTML = contents + '</tr></table>';
		}		
		function bbcode_url_{FIELD}()
		{
			var url = prompt("{L_URL_PROMPT}");
			if( url != null && url != '' )
				insertbbcode('[url=' + url + ']', '[/url]', '{FIELD}');
			else
				insertbbcode('[url]', '[/url]', '{FIELD}');
		}
		-->
		</script>		
		<table style="margin:4px;margin-left:auto;margin-right:auto;">
			<tr>
				<td>
					<table class="bbcode">
						<tr>
							<td style="padding: 2px;">
								<img src="../templates/{THEME}/images/form/separate.png" alt="" />

								<div style="position:relative;z-index:100;margin-left:-70px;float:left;display:none;" id="bb_block1{FIELD}">
									<div class="bbcode_block" style="width:130px;" onmouseover="bb_hide_block('1', '{FIELD}', 1);" onmouseout="bb_hide_block('1', '{FIELD}', 0);">
										# START bbcode_mode.smiley #
										<a onclick="insertbbcode('{bbcode_mode.smiley.CODE}', 'smile', '{FIELD}');" class="bbcode_hover" title="{bbcode_mode.smiley.CODE}">{bbcode_mode.smiley.IMG}</a>{bbcode_mode.smiley.END_LINE}
										# END bbcode_mode.smiley #
										# START bbcode_mode.more #
										<br />
										<a style="font-size: 10px;" href="#" onclick="window.open('../includes/bbcode.php?show=true&amp;field={FIELD}', '{bbcode_mode.more.L_SMILEY}', 'height=550,width=650,resizable=yes,scrollbars=yes');return false;">{bbcode_mode.more.L_ALL_SMILEY}</a>
										# END bbcode_mode.more #
									</div>
								</div>
								<a href="javascript:bb_display_block('1', '{FIELD}');" onmouseover="bb_hide_block('1', '{FIELD}', 1);" onmouseout="bb_hide_block('1', '{FIELD}', 0);" class="bbcode_hover" title="{L_BB_SMILEYS}"><img src="../templates/{THEME}/images/form/smileys.png" alt="{L_BB_SMILEYS}" /></a>
								
								<img src="../templates/{THEME}/images/form/separate.png" alt="" />
								
								<img src="../templates/{THEME}/images/form/bold.png" class="bbcode_hover" onclick="insertbbcode('[b]', '[/b]', '{FIELD}');" alt="{L_BB_BOLD}" title="{L_BB_BOLD}" />
								<img src="../templates/{THEME}/images/form/italic.png" class="bbcode_hover" onclick="insertbbcode('[i]', '[/i]', '{FIELD}');" alt="{L_BB_ITALIC}" title="{L_BB_ITALIC}" />
								<img src="../templates/{THEME}/images/form/underline.png" class="bbcode_hover" onclick="insertbbcode('[u]', '[/u]', '{FIELD}');" alt="{L_BB_UNDERLINE}" title="{L_BB_UNDERLINE}" />
								<img src="../templates/{THEME}/images/form/strike.png" class="bbcode_hover" onclick="insertbbcode('[s]', '[/s]', '{FIELD}');" alt="{L_BB_STRIKE}" title="{L_BB_STRIKE}" />
								
								<img src="../templates/{THEME}/images/form/separate.png" alt="" />
								
								<div style="position:relative;z-index:100;float:left;display:none;" id="bb_block2{FIELD}">
									<div style="margin-left:110px;" class="bbcode_block" onmouseover="bb_hide_block('2', '{FIELD}', 1);" onmouseout="bb_hide_block('2', '{FIELD}', 0);">
										<select id="title{FIELD}" onchange="insertbbcode_select('title', '[/title]', '{FIELD}')">
											<option value="" selected="selected" disabled="disabled">{L_TITLE}</option>
											<option value="1">{L_TITLE}1</option>
											<option value="2">{L_TITLE}2</option>
										</select>	
									</div>
								</div>
								<a href="javascript:bb_display_block('2', '{FIELD}');" onmouseover="bb_hide_block('2', '{FIELD}', 1);" onmouseout="bb_hide_block('2', '{FIELD}', 0);" class="bbcode_hover" title="{L_BB_TITLE}"><img src="../templates/{THEME}/images/form/title.png" alt="{L_BB_TITLE}" /></a>
								
								<div style="position:relative;z-index:100;float:left;display:none;" id="bb_block3{FIELD}">
									<div style="margin-left:135px;" class="bbcode_block" onmouseover="bb_hide_block('3', '{FIELD}', 1);" onmouseout="bb_hide_block('3', '{FIELD}', 0);">
										<select id="stitle{FIELD}" onchange="insertbbcode_select('stitle', '[/stitle]', '{FIELD}')">
											<option value="" selected="selected" disabled="disabled">{L_SUBTITLE}</option>
											<option value="1">{L_SUBTITLE}1</option>
											<option value="2">{L_SUBTITLE}2</option>
										</select>	
									</div>
								</div>
								<a href="javascript:bb_display_block('3', '{FIELD}');" onmouseover="bb_hide_block('3', '{FIELD}', 1);" onmouseout="bb_hide_block('3', '{FIELD}', 0);" class="bbcode_hover" title="{L_BB_SUBTITLE}"><img src="../templates/{THEME}/images/form/subtitle.png" alt="{L_BB_SUBTITLE}" /></a>
								
								<div style="position:relative;z-index:100;float:left;display:none;" id="bb_block4{FIELD}">
									<div style="margin-left:160px;" class="bbcode_block" onmouseover="bb_hide_block('4', '{FIELD}', 1);" onmouseout="bb_hide_block('4', '{FIELD}', 0);">
										<select id="style{FIELD}" onchange="insertbbcode_select('style', '[/style]', '{FIELD}')">
											<option value="" selected="selected" disabled="disabled">{L_STYLE}</option>
											<option value="success">{L_SUCCESS}</option>
											<option value="question">{L_QUESTION}</option>
											<option value="notice">{L_NOTICE}</option>
											<option value="warning">{L_WARNING}</option>
											<option value="error">{L_ERROR}</option>
										</select>	
									</div>
								</div>
								<a href="javascript:bb_display_block('4', '{FIELD}');" onmouseover="bb_hide_block('4', '{FIELD}', 1);" onmouseout="bb_hide_block('4', '{FIELD}', 0);" class="bbcode_hover" title="{L_BB_STYLE}"><img src="../templates/{THEME}/images/form/style.png" alt="{L_BB_STYLE}" /></a>
								
								<img src="../templates/{THEME}/images/form/separate.png" alt="" />
								
								<img src="../templates/{THEME}/images/form/url.png" class="bbcode_hover" onclick="bbcode_url_{FIELD}();" alt="{L_BB_URL}" title="{L_BB_URL}" />
								<img src="../templates/{THEME}/images/form/image.png" class="bbcode_hover" onclick="insertbbcode('[img]', '[/img]', '{FIELD}');" alt="{L_BB_IMG}" title="{L_BB_IMG}" />			
								<img src="../templates/{THEME}/images/form/quote.png" class="bbcode_hover" onclick="insertbbcode('[quote]', '[/quote]', '{FIELD}');" alt="{L_BB_QUOTE}" title="{L_BB_QUOTE}" />		
								<img src="../templates/{THEME}/images/form/hide.png" class="bbcode_hover" onclick="insertbbcode('[hide]', '[/hide]', '{FIELD}');" alt="{L_BB_HIDE}" title="{L_BB_HIDE}" />	
								<img src="../templates/{THEME}/images/form/list.png" class="bbcode_hover" onclick="insertbbcode('[list][*]', '[/list]', '{FIELD}');" alt="{L_BB_LIST}" title="{L_BB_LIST}" />
								
								<img src="../templates/{THEME}/images/form/separate.png" alt="" />
								
								<div style="position:relative;z-index:100;float:right;display:none;" id="bb_block5{FIELD}">
									<div id="bbcolor{FIELD}" class="bbcode_block" style="margin-left:-170px;background:white;width:130px;" onmouseover="bb_hide_block('5', '{FIELD}', 1);" onmouseout="bb_hide_block('5', '{FIELD}', 0);">
									</div>
								</div>
								<a href="javascript:bbcode_color_{FIELD}();bb_display_block('5', '{FIELD}');" onmouseout="bb_hide_block('5', '{FIELD}', 0);" onmouseover="bb_hide_block('5', '{FIELD}', 1);" class="bbcode_hover" title="{L_BB_COLOR}"><img src="../templates/{THEME}/images/form/color.png" alt="{L_BB_COLOR}" /></a>					
								
								<div style="position:relative;z-index:100;margin-left:-70px;float:right;display:none;" id="bb_block6{FIELD}">
									<div style="margin-left:-120px;" class="bbcode_block" onmouseover="bb_hide_block('6', '{FIELD}', 1);" onmouseout="bb_hide_block('6', '{FIELD}', 0);">
										<select id="size{FIELD}" onchange="insertbbcode_select('size', '[/size]', '{FIELD}')">
											<option value="" selected="selected" disabled="disabled">{L_SIZE}</option>
											<option value="5">5</option>
											<option value="10">10</option>
											<option value="15">15</option>
											<option value="20">20</option>
											<option value="25">25</option>
											<option value="30">30</option>
											<option value="35">35</option>
											<option value="40">40</option>
											<option value="45">45</option>
										</select>	
									</div>
								</div>
								<a href="javascript:bb_display_block('6', '{FIELD}');" onmouseover="bb_hide_block('6', '{FIELD}', 1);" onmouseout="bb_hide_block('6', '{FIELD}', 0);" class="bbcode_hover" title="{L_BB_SIZE}"><img src="../templates/{THEME}/images/form/size.png" alt="{L_BB_SIZE}" /></a>			

								<img src="../templates/{THEME}/images/form/separate.png" alt="" />

								<img src="../templates/{THEME}/images/form/minus.png" style="cursor: pointer;cursor: hand;" onclick="textarea_resize('{FIELD}', -100, 'height');textarea_resize('xmlhttprequest_preview', -100, 'height');" alt="{L_BB_SMALL}" title="{L_BB_SMALL}" />
								<img src="../templates/{THEME}/images/form/plus.png" style="cursor: pointer;cursor: hand;" onclick="textarea_resize('{FIELD}', 100, 'height');textarea_resize('xmlhttprequest_preview', 100, 'height');" alt="{L_BB_LARGE}" title="{L_BB_LARGE}" />

								<img src="../templates/{THEME}/images/form/more.png" alt="" class="bbcode_hover" onclick="show_bbcode_div('bbcode_more{FIELD}', 1);" />
							</td>
						</tr>	
					</table>
					<table class="bbcode" id="bbcode_more{FIELD}" style="display:none;margin-top:-1px;padding-right:23px;">
						<tr>
							<td style="width:100%;">
								<img src="../templates/{THEME}/images/form/separate.png" alt="" />
								
								<img src="../templates/{THEME}/images/form/left.png" class="bbcode_hover" onclick="insertbbcode('[align=left]', '[/align]', '{FIELD}');" alt="{L_BB_LEFT}" title="{L_BB_LEFT}" />
								<img src="../templates/{THEME}/images/form/center.png" class="bbcode_hover" onclick="insertbbcode('[align=center]', '[/align]', '{FIELD}');" alt="{L_BB_CENTER}" title="{L_BB_CENTER}" />
								<img src="../templates/{THEME}/images/form/right.png" class="bbcode_hover" onclick="insertbbcode('[align=right]', '[/align]', '{FIELD}');" alt="{L_BB_RIGHT}" title="{L_BB_RIGHT}" />	
								<img src="../templates/{THEME}/images/form/float_left.png" class="bbcode_hover" onclick="insertbbcode('[float=left]', '[/float]', '{FIELD}');" alt="{L_BB_FLOAT_LEFT}" title="{L_BB_FLOAT_LEFT}" />
								<img src="../templates/{THEME}/images/form/float_right.png" class="bbcode_hover" onclick="insertbbcode('[float=right]', '[/float]', '{FIELD}');" alt="{L_BB_FLOAT_RIGHT}" title="{L_BB_FLOAT_RIGHT}" />
								<img src="../templates/{THEME}/images/form/sup.png" class="bbcode_hover" onclick="insertbbcode('[sup]', '[/sup]', '{FIELD}');" alt="{L_BB_SUP}" title="{L_BB_SUP}" />
								<img src="../templates/{THEME}/images/form/sub.png" class="bbcode_hover" onclick="insertbbcode('[sub]', '[/sub]', '{FIELD}');" alt="{L_BB_SUB}" title="{L_BB_SUB}" />
								<img src="../templates/{THEME}/images/form/indent.png" class="bbcode_hover" onclick="insertbbcode('[indent]', '[/indent]', '{FIELD}');" alt="{L_BB_INDENT}" title="{L_BB_INDENT}" />
								<img src="../templates/{THEME}/images/form/table.png" class="bbcode_hover" onclick="insertbbcode('[table][row][col]', '[/col][/row][/table]', '{FIELD}');" alt="{L_BB_TABLE}" title="{L_BB_TABLE}" />
																		
								<img src="../templates/{THEME}/images/form/separate.png" alt="" />
								
								<img src="../templates/{THEME}/images/form/flash.png" class="bbcode_hover" onclick="insertbbcode('[swf=100,100]', '[/swf]', '{FIELD}');" alt="{L_BB_SWF}" title="{L_BB_SWF}" />
								<img src="../templates/{THEME}/images/form/movie.png" class="bbcode_hover" onclick="insertbbcode('[movie=100,100]', '[/movie]', '{FIELD}');" alt="{L_BB_MOVIE}" title="{L_BB_MOVIE}" />
								<img src="../templates/{THEME}/images/form/sound.png" class="bbcode_hover" onclick="insertbbcode('[sound]', '[/sound]', '{FIELD}');" alt="{L_BB_SOUND}" title="{L_BB_SOUND}" />
								
								<img src="../templates/{THEME}/images/form/separate.png" alt="" />
								
								<div style="position:relative;z-index:100;margin-left:-70px;float:right;display:none;" id="bb_block7{FIELD}">
									<div style="margin-left:-120px;" class="bbcode_block" onmouseover="bb_hide_block('7', '{FIELD}', 1);" onmouseout="bb_hide_block('7', '{FIELD}', 0);">
										<select id="code{FIELD}" onchange="insertbbcode_select('code', '[/code]', '{FIELD}')">
											<option value="" selected="selected" disabled="disabled">{L_CODE}</option>
											<optgroup label="{L_TEXT}">
												<option value="text">Text</option>
												<option value="sql">Sql</option>												
												<option value="xml">Xml</option>												
											</optgroup>
											<optgroup label="{L_SCRIPT}">
												<option value="php">PHP</option>
												<option value="asp">Asp</option>
												<option value="python">Python</option>
												<option value="perl">Perl</option>
												<option value="ruby">Ruby</option>
												<option value="bash">Bash</option>
											</optgroup>
											<optgroup label="{L_WEB}">	
												<option value="html">Html</option>
												<option value="css">Css</option>
												<option value="javascript">Javascript</option>
											</optgroup>
											<optgroup label="{L_PROG}">
												<option value="c">C</option>
												<option value="cpp">C++</option>
												<option value="c#">C#</option>
												<option value="d">D</option>
												<option value="java">Java</option>
												<option value="pascal">Pascal</option>
												<option value="delphi">Delphi</option>
												<option value="fortran">Fortran</option>
												<option value="vb">Vb</option>
												<option value="asm">Asm</option>
											</optgroup>
										</select>	
									</div>
								</div>
								<a href="javascript:bb_display_block('7', '{FIELD}');" onmouseover="bb_hide_block('7', '{FIELD}', 1);" onmouseout="bb_hide_block('7', '{FIELD}', 0);" class="bbcode_hover" title="{L_BB_CODE}"><img src="../templates/{THEME}/images/form/code.png" alt="{L_BB_CODE}" /></a>
								
								<img src="../templates/{THEME}/images/form/math.png" class="bbcode_hover" onclick="insertbbcode('[math]', '[/math]', '{FIELD}');" alt="{L_BB_MATH}" title="{L_BB_MATH}" />	
								<img src="../templates/{THEME}/images/form/anchor.png" class="bbcode_hover" onclick="insertbbcode('[anchor]', '[/anchor]', '{FIELD}');" alt="{L_BB_ANCHOR}" title="{L_BB_ANCHOR}" />						
							</td>
							<td style="width:3px;">
								<img src="../templates/{THEME}/images/form/separate.png" alt="" />
							</td>
							<td style="padding: 2px;width:22px;">
								<a href="http://www.phpboost.com/wiki/bbcode" title="{L_BB_HELP}"><img src="../templates/{THEME}/images/form/help.png" alt="{L_BB_HELP}" /></a>
							</td>
						</tr>	
					</table>
				</td>
				<td style="vertical-align:top;padding-left:8px;padding-top:5px;">
					{UPLOAD_MANAGEMENT}
				</td>
			</tr>				
		</table>
		
		<script type="text/javascript">
		<!--
		set_bbcode_preference('bbcode_more{FIELD}');
		-->
		</script>
		# END bbcode_mode #
		