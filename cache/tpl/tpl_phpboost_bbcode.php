		<?php if( !isset($this->_block['tinymce_mode']) || !is_array($this->_block['tinymce_mode']) ) $this->_block['tinymce_mode'] = array();
foreach($this->_block['tinymce_mode'] as $tinymce_mode_key => $tinymce_mode_value) {
$_tmpb_tinymce_mode = &$this->_block['tinymce_mode'][$tinymce_mode_key]; ?>			
		<script language="javascript" type="text/javascript" src="../includes/tinymce/tiny_mce.js"></script>
		<script language="javascript" type="text/javascript">
		<!--
		tinyMCE.init({
			mode : "exact",
			elements : "<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>", 
			theme : "advanced",
			language : "fr",
			content_css : "../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/tinymce.css",
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
		<?php } ?>
		
		<script type="text/javascript" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/bbcode.js"></script>
		<script type="text/javascript">
		<!--
		function XMLHttpRequest_preview()
		{
			var xhr_object = null;
			var data = null;
			var filename = "../includes/xmlhttprequest.php?preview=1";
			<?php echo isset($this->_var['TINYMCE_TRIGGER']) ? $this->_var['TINYMCE_TRIGGER'] : ''; ?>
			var contents = document.getElementById('<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>').value;
			var forbidden_tags = "";
			var data = null;
			
			if( document.getElementById('<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>_ftags') )
				forbidden_tags = document.getElementById('<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>_ftags').value;
					
			if(window.XMLHttpRequest) // Firefox
			   xhr_object = new XMLHttpRequest();
			else if(window.ActiveXObject) // Internet Explorer
			   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
			else // XMLHttpRequest non supporté par le navigateur
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
				alert("<?php echo isset($this->_var['L_REQUIRE_TEXT']) ? $this->_var['L_REQUIRE_TEXT'] : ''; ?>");
		}
		-->
		</script>
		<div style="display:none;" class="xmlhttprequest_preview" id="xmlhttprequest_preview"></div>
		
		<?php if( !isset($this->_block['bbcode_mode']) || !is_array($this->_block['bbcode_mode']) ) $this->_block['bbcode_mode'] = array();
foreach($this->_block['bbcode_mode'] as $bbcode_mode_key => $bbcode_mode_value) {
$_tmpb_bbcode_mode = &$this->_block['bbcode_mode'][$bbcode_mode_key]; ?>
		<script type="text/javascript">
		<!--
		function bbcode_color_<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>()
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
				contents += '<td style="padding:2px;"><a onclick="insertbbcode(\'[color=' + color[i] + ']\', \'[/color]\', \'<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>\');" class="bbcode_hover"><span style="background:' + color[i] + ';padding:0px 4px;border:1px solid #ACA899;">&nbsp;</span></a></td>' + br;								
			}
			document.getElementById("bbcolor<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>").innerHTML = contents + '</tr></table>';
		}		
		function bbcode_url_<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>()
		{
			var url = prompt("<?php echo isset($this->_var['L_URL_PROMPT']) ? $this->_var['L_URL_PROMPT'] : ''; ?>");
			if( url != null && url != '' )
				insertbbcode('[url=' + url + ']', '[/url]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');
			else
				insertbbcode('[url]', '[/url]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');
		}
		-->
		</script>		
		<table style="margin:4px;margin-left:auto;margin-right:auto;">
			<tr>
				<td>
					<table class="bbcode">
						<tr>
							<td style="padding: 2px;">
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/separate.png" alt="" />

								<div style="position:relative;z-index:100;margin-left:-70px;float:left;display:none;" id="bb_block1<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>">
									<div class="bbcode_block" style="width:130px;" onmouseover="bb_hide_block('1', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 1);" onmouseout="bb_hide_block('1', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 0);">
										<?php if( !isset($_tmpb_bbcode_mode['smiley']) || !is_array($_tmpb_bbcode_mode['smiley']) ) $_tmpb_bbcode_mode['smiley'] = array();
foreach($_tmpb_bbcode_mode['smiley'] as $smiley_key => $smiley_value) {
$_tmpb_smiley = &$_tmpb_bbcode_mode['smiley'][$smiley_key]; ?>
										<a onclick="insertbbcode('<?php echo isset($_tmpb_smiley['CODE']) ? $_tmpb_smiley['CODE'] : ''; ?>', 'smile', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" class="bbcode_hover" title="<?php echo isset($_tmpb_smiley['CODE']) ? $_tmpb_smiley['CODE'] : ''; ?>"><?php echo isset($_tmpb_smiley['IMG']) ? $_tmpb_smiley['IMG'] : ''; ?></a><?php echo isset($_tmpb_smiley['END_LINE']) ? $_tmpb_smiley['END_LINE'] : ''; ?>
										<?php } ?>
										<?php if( !isset($_tmpb_bbcode_mode['more']) || !is_array($_tmpb_bbcode_mode['more']) ) $_tmpb_bbcode_mode['more'] = array();
foreach($_tmpb_bbcode_mode['more'] as $more_key => $more_value) {
$_tmpb_more = &$_tmpb_bbcode_mode['more'][$more_key]; ?>
										<br />
										<a style="font-size: 10px;" href="#" onclick="window.open('../includes/bbcode.php?show=true&amp;field=<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', '<?php echo isset($_tmpb_more['L_SMILEY']) ? $_tmpb_more['L_SMILEY'] : ''; ?>', 'height=550,width=650,resizable=yes,scrollbars=yes');return false;"><?php echo isset($_tmpb_more['L_ALL_SMILEY']) ? $_tmpb_more['L_ALL_SMILEY'] : ''; ?></a>
										<?php } ?>
									</div>
								</div>
								<a href="javascript:bb_display_block('1', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" onmouseover="bb_hide_block('1', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 1);" onmouseout="bb_hide_block('1', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 0);" class="bbcode_hover" title="<?php echo isset($this->_var['L_BB_SMILEYS']) ? $this->_var['L_BB_SMILEYS'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/smileys.png" alt="<?php echo isset($this->_var['L_BB_SMILEYS']) ? $this->_var['L_BB_SMILEYS'] : ''; ?>" /></a>
								
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/separate.png" alt="" />
								
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/bold.png" class="bbcode_hover" onclick="insertbbcode('[b]', '[/b]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" alt="<?php echo isset($this->_var['L_BB_BOLD']) ? $this->_var['L_BB_BOLD'] : ''; ?>" title="<?php echo isset($this->_var['L_BB_BOLD']) ? $this->_var['L_BB_BOLD'] : ''; ?>" />
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/italic.png" class="bbcode_hover" onclick="insertbbcode('[i]', '[/i]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" alt="<?php echo isset($this->_var['L_BB_ITALIC']) ? $this->_var['L_BB_ITALIC'] : ''; ?>" title="<?php echo isset($this->_var['L_BB_ITALIC']) ? $this->_var['L_BB_ITALIC'] : ''; ?>" />
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/underline.png" class="bbcode_hover" onclick="insertbbcode('[u]', '[/u]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" alt="<?php echo isset($this->_var['L_BB_UNDERLINE']) ? $this->_var['L_BB_UNDERLINE'] : ''; ?>" title="<?php echo isset($this->_var['L_BB_UNDERLINE']) ? $this->_var['L_BB_UNDERLINE'] : ''; ?>" />
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/strike.png" class="bbcode_hover" onclick="insertbbcode('[s]', '[/s]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" alt="<?php echo isset($this->_var['L_BB_STRIKE']) ? $this->_var['L_BB_STRIKE'] : ''; ?>" title="<?php echo isset($this->_var['L_BB_STRIKE']) ? $this->_var['L_BB_STRIKE'] : ''; ?>" />
								
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/separate.png" alt="" />
								
								<div style="position:relative;z-index:100;float:left;display:none;" id="bb_block2<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>">
									<div style="margin-left:110px;" class="bbcode_block" onmouseover="bb_hide_block('2', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 1);" onmouseout="bb_hide_block('2', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 0);">
										<select id="title<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>" onchange="insertbbcode_select('title', '[/title]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>')">
											<option value="" selected="selected" disabled="disabled"><?php echo isset($this->_var['L_TITLE']) ? $this->_var['L_TITLE'] : ''; ?></option>
											<option value="1"><?php echo isset($this->_var['L_TITLE']) ? $this->_var['L_TITLE'] : ''; ?>1</option>
											<option value="2"><?php echo isset($this->_var['L_TITLE']) ? $this->_var['L_TITLE'] : ''; ?>2</option>
										</select>	
									</div>
								</div>
								<a href="javascript:bb_display_block('2', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" onmouseover="bb_hide_block('2', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 1);" onmouseout="bb_hide_block('2', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 0);" class="bbcode_hover" title="<?php echo isset($this->_var['L_BB_TITLE']) ? $this->_var['L_BB_TITLE'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/title.png" alt="<?php echo isset($this->_var['L_BB_TITLE']) ? $this->_var['L_BB_TITLE'] : ''; ?>" /></a>
								
								<div style="position:relative;z-index:100;float:left;display:none;" id="bb_block3<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>">
									<div style="margin-left:135px;" class="bbcode_block" onmouseover="bb_hide_block('3', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 1);" onmouseout="bb_hide_block('3', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 0);">
										<select id="stitle<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>" onchange="insertbbcode_select('stitle', '[/stitle]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>')">
											<option value="" selected="selected" disabled="disabled"><?php echo isset($this->_var['L_SUBTITLE']) ? $this->_var['L_SUBTITLE'] : ''; ?></option>
											<option value="1"><?php echo isset($this->_var['L_SUBTITLE']) ? $this->_var['L_SUBTITLE'] : ''; ?>1</option>
											<option value="2"><?php echo isset($this->_var['L_SUBTITLE']) ? $this->_var['L_SUBTITLE'] : ''; ?>2</option>
										</select>	
									</div>
								</div>
								<a href="javascript:bb_display_block('3', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" onmouseover="bb_hide_block('3', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 1);" onmouseout="bb_hide_block('3', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 0);" class="bbcode_hover" title="<?php echo isset($this->_var['L_BB_SUBTITLE']) ? $this->_var['L_BB_SUBTITLE'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/subtitle.png" alt="<?php echo isset($this->_var['L_BB_SUBTITLE']) ? $this->_var['L_BB_SUBTITLE'] : ''; ?>" /></a>
								
								<div style="position:relative;z-index:100;float:left;display:none;" id="bb_block4<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>">
									<div style="margin-left:160px;" class="bbcode_block" onmouseover="bb_hide_block('4', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 1);" onmouseout="bb_hide_block('4', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 0);">
										<select id="style<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>" onchange="insertbbcode_select('style', '[/style]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>')">
											<option value="" selected="selected" disabled="disabled"><?php echo isset($this->_var['L_STYLE']) ? $this->_var['L_STYLE'] : ''; ?></option>
											<option value="success"><?php echo isset($this->_var['L_SUCCESS']) ? $this->_var['L_SUCCESS'] : ''; ?></option>
											<option value="question"><?php echo isset($this->_var['L_QUESTION']) ? $this->_var['L_QUESTION'] : ''; ?></option>
											<option value="notice"><?php echo isset($this->_var['L_NOTICE']) ? $this->_var['L_NOTICE'] : ''; ?></option>
											<option value="warning"><?php echo isset($this->_var['L_WARNING']) ? $this->_var['L_WARNING'] : ''; ?></option>
											<option value="error"><?php echo isset($this->_var['L_ERROR']) ? $this->_var['L_ERROR'] : ''; ?></option>
										</select>	
									</div>
								</div>
								<a href="javascript:bb_display_block('4', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" onmouseover="bb_hide_block('4', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 1);" onmouseout="bb_hide_block('4', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 0);" class="bbcode_hover" title="<?php echo isset($this->_var['L_BB_STYLE']) ? $this->_var['L_BB_STYLE'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/style.png" alt="<?php echo isset($this->_var['L_BB_STYLE']) ? $this->_var['L_BB_STYLE'] : ''; ?>" /></a>
								
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/separate.png" alt="" />
								
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/url.png" class="bbcode_hover" onclick="bbcode_url_<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>();" alt="<?php echo isset($this->_var['L_BB_URL']) ? $this->_var['L_BB_URL'] : ''; ?>" title="<?php echo isset($this->_var['L_BB_URL']) ? $this->_var['L_BB_URL'] : ''; ?>" />
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/image.png" class="bbcode_hover" onclick="insertbbcode('[img]', '[/img]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" alt="<?php echo isset($this->_var['L_BB_IMG']) ? $this->_var['L_BB_IMG'] : ''; ?>" title="<?php echo isset($this->_var['L_BB_IMG']) ? $this->_var['L_BB_IMG'] : ''; ?>" />			
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/quote.png" class="bbcode_hover" onclick="insertbbcode('[quote]', '[/quote]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" alt="<?php echo isset($this->_var['L_BB_QUOTE']) ? $this->_var['L_BB_QUOTE'] : ''; ?>" title="<?php echo isset($this->_var['L_BB_QUOTE']) ? $this->_var['L_BB_QUOTE'] : ''; ?>" />		
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/hide.png" class="bbcode_hover" onclick="insertbbcode('[hide]', '[/hide]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" alt="<?php echo isset($this->_var['L_BB_HIDE']) ? $this->_var['L_BB_HIDE'] : ''; ?>" title="<?php echo isset($this->_var['L_BB_HIDE']) ? $this->_var['L_BB_HIDE'] : ''; ?>" />	
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/list.png" class="bbcode_hover" onclick="insertbbcode('[list][*]', '[/list]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" alt="<?php echo isset($this->_var['L_BB_LIST']) ? $this->_var['L_BB_LIST'] : ''; ?>" title="<?php echo isset($this->_var['L_BB_LIST']) ? $this->_var['L_BB_LIST'] : ''; ?>" />
								
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/separate.png" alt="" />
								
								<div style="position:relative;z-index:100;float:right;display:none;" id="bb_block5<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>">
									<div id="bbcolor<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>" class="bbcode_block" style="margin-left:-170px;background:white;width:130px;" onmouseover="bb_hide_block('5', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 1);" onmouseout="bb_hide_block('5', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 0);">
									</div>
								</div>
								<a href="javascript:bbcode_color_<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>();bb_display_block('5', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" onmouseout="bb_hide_block('5', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 0);" onmouseover="bb_hide_block('5', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 1);" class="bbcode_hover" title="<?php echo isset($this->_var['L_BB_COLOR']) ? $this->_var['L_BB_COLOR'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/color.png" alt="<?php echo isset($this->_var['L_BB_COLOR']) ? $this->_var['L_BB_COLOR'] : ''; ?>" /></a>					
								
								<div style="position:relative;z-index:100;margin-left:-70px;float:right;display:none;" id="bb_block6<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>">
									<div style="margin-left:-120px;" class="bbcode_block" onmouseover="bb_hide_block('6', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 1);" onmouseout="bb_hide_block('6', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 0);">
										<select id="size<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>" onchange="insertbbcode_select('size', '[/size]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>')">
											<option value="" selected="selected" disabled="disabled"><?php echo isset($this->_var['L_SIZE']) ? $this->_var['L_SIZE'] : ''; ?></option>
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
								<a href="javascript:bb_display_block('6', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" onmouseover="bb_hide_block('6', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 1);" onmouseout="bb_hide_block('6', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 0);" class="bbcode_hover" title="<?php echo isset($this->_var['L_BB_SIZE']) ? $this->_var['L_BB_SIZE'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/size.png" alt="<?php echo isset($this->_var['L_BB_SIZE']) ? $this->_var['L_BB_SIZE'] : ''; ?>" /></a>			

								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/separate.png" alt="" />

								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/minus.png" style="cursor: pointer;cursor: hand;" onclick="textarea_resize('<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', -100, 'height');textarea_resize('xmlhttprequest_preview', -100, 'height');" alt="<?php echo isset($this->_var['L_BB_SMALL']) ? $this->_var['L_BB_SMALL'] : ''; ?>" title="<?php echo isset($this->_var['L_BB_SMALL']) ? $this->_var['L_BB_SMALL'] : ''; ?>" />
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/plus.png" style="cursor: pointer;cursor: hand;" onclick="textarea_resize('<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 100, 'height');textarea_resize('xmlhttprequest_preview', 100, 'height');" alt="<?php echo isset($this->_var['L_BB_LARGE']) ? $this->_var['L_BB_LARGE'] : ''; ?>" title="<?php echo isset($this->_var['L_BB_LARGE']) ? $this->_var['L_BB_LARGE'] : ''; ?>" />

								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/more.png" alt="" class="bbcode_hover" onclick="show_bbcode_div('bbcode_more<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 1);" />
							</td>
						</tr>	
					</table>
					<table class="bbcode" id="bbcode_more<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>" style="display:none;margin-top:-1px;padding-right:23px;">
						<tr>
							<td style="width:100%;">
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/separate.png" alt="" />
								
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/left.png" class="bbcode_hover" onclick="insertbbcode('[align=left]', '[/align]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" alt="<?php echo isset($this->_var['L_BB_LEFT']) ? $this->_var['L_BB_LEFT'] : ''; ?>" title="<?php echo isset($this->_var['L_BB_LEFT']) ? $this->_var['L_BB_LEFT'] : ''; ?>" />
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/center.png" class="bbcode_hover" onclick="insertbbcode('[align=center]', '[/align]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" alt="<?php echo isset($this->_var['L_BB_CENTER']) ? $this->_var['L_BB_CENTER'] : ''; ?>" title="<?php echo isset($this->_var['L_BB_CENTER']) ? $this->_var['L_BB_CENTER'] : ''; ?>" />
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/right.png" class="bbcode_hover" onclick="insertbbcode('[align=right]', '[/align]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" alt="<?php echo isset($this->_var['L_BB_RIGHT']) ? $this->_var['L_BB_RIGHT'] : ''; ?>" title="<?php echo isset($this->_var['L_BB_RIGHT']) ? $this->_var['L_BB_RIGHT'] : ''; ?>" />	
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/float_left.png" class="bbcode_hover" onclick="insertbbcode('[float=left]', '[/float]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" alt="<?php echo isset($this->_var['L_BB_FLOAT_LEFT']) ? $this->_var['L_BB_FLOAT_LEFT'] : ''; ?>" title="<?php echo isset($this->_var['L_BB_FLOAT_LEFT']) ? $this->_var['L_BB_FLOAT_LEFT'] : ''; ?>" />
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/float_right.png" class="bbcode_hover" onclick="insertbbcode('[float=right]', '[/float]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" alt="<?php echo isset($this->_var['L_BB_FLOAT_RIGHT']) ? $this->_var['L_BB_FLOAT_RIGHT'] : ''; ?>" title="<?php echo isset($this->_var['L_BB_FLOAT_RIGHT']) ? $this->_var['L_BB_FLOAT_RIGHT'] : ''; ?>" />
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/sup.png" class="bbcode_hover" onclick="insertbbcode('[sup]', '[/sup]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" alt="<?php echo isset($this->_var['L_BB_SUP']) ? $this->_var['L_BB_SUP'] : ''; ?>" title="<?php echo isset($this->_var['L_BB_SUP']) ? $this->_var['L_BB_SUP'] : ''; ?>" />
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/sub.png" class="bbcode_hover" onclick="insertbbcode('[sub]', '[/sub]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" alt="<?php echo isset($this->_var['L_BB_SUB']) ? $this->_var['L_BB_SUB'] : ''; ?>" title="<?php echo isset($this->_var['L_BB_SUB']) ? $this->_var['L_BB_SUB'] : ''; ?>" />
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/indent.png" class="bbcode_hover" onclick="insertbbcode('[indent]', '[/indent]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" alt="<?php echo isset($this->_var['L_BB_INDENT']) ? $this->_var['L_BB_INDENT'] : ''; ?>" title="<?php echo isset($this->_var['L_BB_INDENT']) ? $this->_var['L_BB_INDENT'] : ''; ?>" />
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/table.png" class="bbcode_hover" onclick="insertbbcode('[table][row][col]', '[/col][/row][/table]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" alt="<?php echo isset($this->_var['L_BB_TABLE']) ? $this->_var['L_BB_TABLE'] : ''; ?>" title="<?php echo isset($this->_var['L_BB_TABLE']) ? $this->_var['L_BB_TABLE'] : ''; ?>" />
																		
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/separate.png" alt="" />
								
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/flash.png" class="bbcode_hover" onclick="insertbbcode('[swf=100,100]', '[/swf]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" alt="<?php echo isset($this->_var['L_BB_SWF']) ? $this->_var['L_BB_SWF'] : ''; ?>" title="<?php echo isset($this->_var['L_BB_SWF']) ? $this->_var['L_BB_SWF'] : ''; ?>" />
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/movie.png" class="bbcode_hover" onclick="insertbbcode('[movie=100,100]', '[/movie]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" alt="<?php echo isset($this->_var['L_BB_MOVIE']) ? $this->_var['L_BB_MOVIE'] : ''; ?>" title="<?php echo isset($this->_var['L_BB_MOVIE']) ? $this->_var['L_BB_MOVIE'] : ''; ?>" />
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/sound.png" class="bbcode_hover" onclick="insertbbcode('[sound]', '[/sound]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" alt="<?php echo isset($this->_var['L_BB_SOUND']) ? $this->_var['L_BB_SOUND'] : ''; ?>" title="<?php echo isset($this->_var['L_BB_SOUND']) ? $this->_var['L_BB_SOUND'] : ''; ?>" />
								
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/separate.png" alt="" />
								
								<div style="position:relative;z-index:100;margin-left:-70px;float:right;display:none;" id="bb_block7<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>">
									<div style="margin-left:-120px;" class="bbcode_block" onmouseover="bb_hide_block('7', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 1);" onmouseout="bb_hide_block('7', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 0);">
										<select id="code<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>" onchange="insertbbcode_select('code', '[/code]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>')">
											<option value="" selected="selected" disabled="disabled"><?php echo isset($this->_var['L_CODE']) ? $this->_var['L_CODE'] : ''; ?></option>
											<optgroup label="<?php echo isset($this->_var['L_TEXT']) ? $this->_var['L_TEXT'] : ''; ?>">
												<option value="text">Text</option>
												<option value="sql">Sql</option>												
												<option value="xml">Xml</option>												
											</optgroup>
											<optgroup label="<?php echo isset($this->_var['L_SCRIPT']) ? $this->_var['L_SCRIPT'] : ''; ?>">
												<option value="php">PHP</option>
												<option value="asp">Asp</option>
												<option value="python">Python</option>
												<option value="perl">Perl</option>
												<option value="ruby">Ruby</option>
												<option value="bash">Bash</option>
											</optgroup>
											<optgroup label="<?php echo isset($this->_var['L_WEB']) ? $this->_var['L_WEB'] : ''; ?>">	
												<option value="html">Html</option>
												<option value="css">Css</option>
												<option value="javascript">Javascript</option>
											</optgroup>
											<optgroup label="<?php echo isset($this->_var['L_PROG']) ? $this->_var['L_PROG'] : ''; ?>">
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
								<a href="javascript:bb_display_block('7', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" onmouseover="bb_hide_block('7', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 1);" onmouseout="bb_hide_block('7', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', 0);" class="bbcode_hover" title="<?php echo isset($this->_var['L_BB_CODE']) ? $this->_var['L_BB_CODE'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/code.png" alt="<?php echo isset($this->_var['L_BB_CODE']) ? $this->_var['L_BB_CODE'] : ''; ?>" /></a>
								
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/math.png" class="bbcode_hover" onclick="insertbbcode('[math]', '[/math]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" alt="<?php echo isset($this->_var['L_BB_MATH']) ? $this->_var['L_BB_MATH'] : ''; ?>" title="<?php echo isset($this->_var['L_BB_MATH']) ? $this->_var['L_BB_MATH'] : ''; ?>" />	
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/anchor.png" class="bbcode_hover" onclick="insertbbcode('[anchor]', '[/anchor]', '<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');" alt="<?php echo isset($this->_var['L_BB_ANCHOR']) ? $this->_var['L_BB_ANCHOR'] : ''; ?>" title="<?php echo isset($this->_var['L_BB_ANCHOR']) ? $this->_var['L_BB_ANCHOR'] : ''; ?>" />						
							</td>
							<td style="width:3px;">
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/separate.png" alt="" />
							</td>
							<td style="padding: 2px;width:22px;">
								<a href="http://www.phpboost.com/wiki/bbcode" title="<?php echo isset($this->_var['L_BB_HELP']) ? $this->_var['L_BB_HELP'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/help.png" alt="<?php echo isset($this->_var['L_BB_HELP']) ? $this->_var['L_BB_HELP'] : ''; ?>" /></a>
							</td>
						</tr>	
					</table>
				</td>
				<td style="vertical-align:top;padding-left:8px;padding-top:5px;">
					<?php echo isset($this->_var['UPLOAD_MANAGEMENT']) ? $this->_var['UPLOAD_MANAGEMENT'] : ''; ?>
				</td>
			</tr>				
		</table>
		
		<script type="text/javascript">
		<!--
		set_bbcode_preference('bbcode_more<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>');
		-->
		</script>
		<?php } ?>
		