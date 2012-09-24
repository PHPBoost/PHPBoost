		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_BUGS_MANAGEMENT}</li>
				<li>
					<a href="admin_bugtracker.php"><img src="bugtracker.png" alt="" /></a>
					<br />
					<a href="admin_bugtracker.php" class="quick_link">{L_BUGS_CONFIG}</a>
				</li>
			</ul>
		</div>
		
		# START config #
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/bbcode.js"></script>
		<script type="text/javascript">
		<!--
		function check_form(){
			if(document.getElementById('items_per_page').value == "" || document.getElementById('items_per_page').value == 0) {
				alert("{L_REQUIRE_ITEMS_PER_PAGE}");
				return false;
		    }
			return true;
		}
		
		function check_type(){
			if(document.getElementById('type').value == "") {
				alert("{L_REQUIRE_TYPE}");
				return false;
		    }
			return true;
		}
		
		function check_category(){
			if(document.getElementById('category').value == "") {
				alert("{L_REQUIRE_CATEGORY}");
				return false;
		    }
			return true;
		}
		
		function check_version(){
			if(document.getElementById('version').value == "") {
				alert("{L_REQUIRE_VERSION}");
				return false;
		    }
			return true;
		}
		
		function Confirm_del_version() {
			return confirm("{L_CONFIRM_DEL_VERSION}");
		}
		
		function Confirm_del_type() {
			return confirm("{L_CONFIRM_DEL_TYPE}");
		}
		
		function Confirm_del_category() {
			return confirm("{L_CONFIRM_DEL_CATEGORY}");
		}
		
		function insert_color(color, field)
		{
			document.getElementById(field).value = color.replace(/#/g, '');
			document.getElementById(field).style.backgroundColor = color;
		}
		
		function bbcode_color(field)
		{
			var i;
			var br;
			var contents;
			var color = new Array(
			'#000000', '#433026', '#333300', '#003300', '#003366', '#000080', '#333399', '#333333',
			'#800000', '#ffa500', '#808000', '#008000', '#008080', '#0000ff', '#666699', '#808080',
			'#F04343', '#FF9900', '#99CC00', '#339966', '#33CCCC', '#3366FF', '#800080', '#ACA899',
			'#ffc0cb', '#FFCC00', '#ffff00', '#00FF00', '#00FFFF', '#00CCFF', '#993366', '#C0C0C0',
			'#FF99CC', '#FFCC99', '#FFFF99', '#CCFFCC', '#CCFFFF', '#CC99FF', '#CC99FF', '#FFFFFF');							
			
			contents = '<table style="border-collapse:collapse;margin:auto;"><tr>';
			for(i = 0; i < 40; i++)
			{
				br = (i+1) % 8;
				br = (br == 0 && i != 0 && i < 39) ? '</tr><tr>' : '';
				contents += '<td style="padding:2px;"><a onclick="javascript:insert_color(\'' + color[i] + '\', \'' + field + '\');" class="bbcode_hover"><span style="background:' + color[i] + ';padding:0px 4px;border:1px solid #ACA899;">&nbsp;</span></a></td>' + br;								
			}
			document.getElementById(field + '_list').innerHTML = contents + '</tr></table>';
		}
		-->
		</script>
	
		<div id="admin_contents">
			# IF C_ERROR_HANDLER #
				<div class="error_handler_position">
					<span id="errorh"></span>
					<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
						<br />	
					</div>
				</div>
			# ENDIF #
			
			<form action="admin_bugtracker.php?token={TOKEN}" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend>{L_BUGS_CONFIG}</legend>
					<p>{L_REQUIRE}</p>
					<dl>
						<dt><label for="items_per_page">* {L_ITEMS_PER_PAGE}</label></dt>
						<dd><label><input type="text" size="3" name="items_per_page" id="items_per_page" value="{ITEMS_PER_PAGE}" class="text" /></label></dd>
					</dl>
					<dl class="overflow_visible">
						<dt><label for="severity_minor_color">{L_SEVERITY_MINOR_COLOR}</label></dt>
						<dd>\#<input type="text" size="7" name="severity_minor_color" id="severity_minor_color" value="{SEVERITY_MINOR_COLOR}" style="background-color:\#{SEVERITY_MINOR_COLOR};" class="text" />
							<a href="javascript:bbcode_color('severity_minor_color');bb_display_block('1', '');" onmouseout="bb_hide_block('1', '', 0);" class="bbcode_hover"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/color.png" alt="" class="valign_middle" /></a>	
							<div style="position:relative;z-index:100;display:none;" id="bb_block1">
								<div id="severity_minor_color_list" class="bbcode_block" style="background:white;width:150px;" onmouseover="bb_hide_block('1', '', 1);" onmouseout="bb_hide_block('1', '', 0);">
								</div>
							</div>
						</dd>
					</dl>
					<dl class="overflow_visible">
						<dt><label for="severity_major_color">{L_SEVERITY_MAJOR_COLOR}</label></dt>
						<dd>\#<input type="text" size="7" name="severity_major_color" id="severity_major_color" value="{SEVERITY_MAJOR_COLOR}" style="background-color:\#{SEVERITY_MAJOR_COLOR};" class="text" />
							<a href="javascript:bbcode_color('severity_major_color');bb_display_block('2', '');" onmouseout="bb_hide_block('2', '', 0);" class="bbcode_hover"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/color.png" alt="" class="valign_middle" /></a>	
							<div style="position:relative;z-index:100;display:none;" id="bb_block2">
								<div id="severity_major_color_list" class="bbcode_block" style="background:white;width:150px;" onmouseover="bb_hide_block('2', '', 1);" onmouseout="bb_hide_block('2', '', 0);">
								</div>
							</div>
						</dd>
					</dl>
					<dl class="overflow_visible">
						<dt><label for="severity_critical_color">{L_SEVERITY_CRITICAL_COLOR}</label></dt>
						<dd>\#<input type="text" size="7" name="severity_critical_color" id="severity_critical_color" value="{SEVERITY_CRITICAL_COLOR}" style="background-color:\#{SEVERITY_CRITICAL_COLOR};" class="text" />
							<a href="javascript:bbcode_color('severity_critical_color');bb_display_block('3', '');" onmouseout="bb_hide_block('3', '', 0);" class="bbcode_hover"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/color.png" alt="" class="valign_middle" /></a>	
							<div style="position:relative;z-index:100;display:none;" id="bb_block3">
								<div id="severity_critical_color_list" class="bbcode_block" style="background:white;width:150px;" onmouseover="bb_hide_block('3', '', 1);" onmouseout="bb_hide_block('3', '', 0);">
								</div>
							</div>
						</dd>
					</dl>
					<dl class="overflow_visible">
						<dt><label for="rejected_bug_color">{L_REJECTED_BUG_COLOR}</label></dt>
						<dd>#<input type="text" size="7" name="rejected_bug_color" id="rejected_bug_color" value="{REJECTED_BUG_COLOR}" style="background-color:\#{REJECTED_BUG_COLOR};" class="text" />
							<a href="javascript:bbcode_color('rejected_bug_color');bb_display_block('4', '');" onmouseout="bb_hide_block('4', '', 0);" class="bbcode_hover"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/color.png" alt="" class="valign_middle" /></a>	
							<div style="position:relative;z-index:100;display:none;" id="bb_block4">
								<div id="rejected_bug_color_list" class="bbcode_block" style="background:white;width:150px;" onmouseover="bb_hide_block('4', '', 1);" onmouseout="bb_hide_block('4', '', 0);">
								</div>
							</div>
						</dd>
					</dl>
					<dl class="overflow_visible">
						<dt><label for="closed_bug_color">{L_CLOSED_BUG_COLOR}</label></dt>
						<dd>#<input type="text" size="7" name="closed_bug_color" id="closed_bug_color" value="{CLOSED_BUG_COLOR}" style="background-color:\#{CLOSED_BUG_COLOR};" class="text" />
							<a href="javascript:bbcode_color('closed_bug_color');bb_display_block('5', '');" onmouseout="bb_hide_block('5', '', 0);" class="bbcode_hover"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/color.png" alt="" class="valign_middle" /></a>	
							<div style="position:relative;z-index:100;display:none;" id="bb_block5">
								<div id="closed_bug_color_list" class="bbcode_block" style="background:white;width:150px;" onmouseover="bb_hide_block('5', '', 1);" onmouseout="bb_hide_block('5', '', 0);">
								</div>
							</div>
						</dd>
					</dl>
					<dl>
						<dt><label for="comments_activated">{L_ACTIV_COM}</label></dt>
						<dd> 
							<input type="checkbox" name="comments_activated" {COM_CHECKED} />
						</dd>
					</dl>
				</fieldset>
				
				<fieldset>
					<legend>{L_CONTENT_VALUE_TITLE}</legend>
					<span>{L_CONTENT_VALUE_EXPLAIN}</span><br /><br />
					<label for="contents_value">{L_CONTENT_VALUE}</label>
					<div style="position:relative;display:none;" id="loading_previewcontents_value">
						<div style="margin:auto;margin-top:90px;width:100%;text-align:center;position:absolute;"><img src="{PATH_TO_ROOT}/templates/base/images/loading.gif" alt="" /></div>
					</div>
					<div style="display:none;" class="xmlhttprequest_preview" id="xmlhttprequest_previewcontents_value"></div>
					{CONTENTS_KERNEL_EDITOR}
					<label><textarea rows="8" cols="86" id="contents_value" name="contents_value">{CONTENTS_VALUE}</textarea></label>
					<div style="text-align:center;"><input type="button" value="{L_PREVIEW}" onclick="XMLHttpRequest_preview('contents_value');" class="submit" /></div>
					<br />
				</fieldset>
				
				<fieldset>
					<legend>{L_BUGS_DISPONIBLE_TYPES}</legend>
					<span>{L_BUGS_TYPE_EXPLAIN}</span>
					<table id="versions_list" class="module_table">
						<tr style="text-align:center;">
							<th style="width:80%">
								{L_TYPE}
							</th>
							<th>
								{L_ACTIONS}
							</th>
						</tr>
						# IF C_NO_TYPE #
						<tr style="text-align:center;"> 
							<td colspan="2" class="row2">
								{L_NO_TYPE}
							</td>
						</tr>
						# ELSE #
						# START types #
						<tr id="version_{types.ID}" style="text-align:center;"> 
							<td class="row2">
								{types.TYPE}
							</td>
							<td class="row2"> 
								<a href="admin_bugtracker.php?edit_type=true&amp;id={types.ID}"><img src="../templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" /></a>
								<a href="admin_bugtracker.php?delete_type=true&amp;id={types.ID}&amp;token={TOKEN}" onclick="javascript:return Confirm_del_type();"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
							</td>
						</tr>
						# END types #
						# ENDIF #
					</table>
					<br />
					<dl>
						<dt><label for="type">{L_ADD_TYPE}</label></dt>
						<dd>
							<input type="text" size="40" maxlength="100" name="type" id="type" value="{TYPE}" class="text" />
							&nbsp;&nbsp; 
							<input type="submit" name="valid_add_type" onclick="return check_type();" value="{L_ADD}" class="submit" />
						</dd>
					</dl>
				</fieldset>
				
				<fieldset>
					<legend>{L_BUGS_DISPONIBLE_CATEGORIES}</legend>
					<span>{L_BUGS_CATEGORY_EXPLAIN}</span>
					<table class="module_table">
						<tr style="text-align:center;">
							<th style="width:80%">
								{L_CATEGORY}
							</th>
							<th>
								{L_ACTIONS}
							</th>
						</tr>
						# IF C_NO_CATEGORY #
						<tr style="text-align:center;"> 
							<td colspan="2" class="row2">
								{L_NO_CATEGORY}
							</td>
						</tr>
						# ELSE #
						# START categories #
						<tr style="text-align:center;"> 
							<td class="row2">
								{categories.CATEGORY}
							</td>
							<td class="row2"> 
								<a href="admin_bugtracker.php?edit_category=true&amp;id={categories.ID}"><img src="../templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" /></a>
								<a href="admin_bugtracker.php?delete_category=true&amp;id={categories.ID}&amp;token={TOKEN}" onclick="javascript:return Confirm_del_category();"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
							</td>
						</tr>
						# END categories #
						# ENDIF #
					</table>
					<br />
					<dl>
						<dt><label for="category">{L_ADD_CATEGORY}</label></dt>
						<dd>
							<input type="text" size="40" maxlength="100" name="category" id="category" value="{CATEGORY}" class="text" />
							&nbsp;&nbsp; 
							<input type="submit" name="valid_add_category" onclick="return check_category();" value="{L_ADD}" class="submit" />
						</dd>
					</dl>
				</fieldset>
				
				<fieldset>
					<legend>{L_BUGS_DISPONIBLE_VERSIONS}</legend>
					<span>{L_BUGS_VERSION_EXPLAIN}</span>
					<table class="module_table">
						<tr style="text-align:center;">
							<th>
								{L_VERSION}
							</th>
							<th>
								{L_VERSION_DETECTED}
							</th>
							<th>
								{L_VERSION_FIXED}
							</th>
							<th>
								{L_ACTIONS}
							</th>
						</tr>
						# IF C_NO_VERSION #
						<tr style="text-align:center;"> 
							<td colspan="4" class="row2">
								{L_NO_VERSION}
							</td>
						</tr>
						# ELSE #
						# START versions #
						<tr style="text-align:center;"> 
							<td class="row2">
								{versions.VERSION}
							</td> 
							<td class="row2">
								<input type="checkbox" {versions.DETECTED_IN} disabled=disabled />
							</td> 
							<td class="row2">
								<input type="checkbox" {versions.FIXED_IN} disabled=disabled />
							</td> 
							<td class="row2"> 
								<a href="admin_bugtracker.php?edit_version=true&amp;id={versions.ID}"><img src="../templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" /></a>
								<a href="admin_bugtracker.php?delete_version=true&amp;id={versions.ID}&amp;token={TOKEN}" onclick="javascript:return Confirm_del_version();"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
							</td>
						</tr>
						# END versions #
						# ENDIF #
					</table>
					<br />
					<dl>
						<dt><label for="version">{L_ADD_VERSION}</label></dt>
						<dd>
							<input type="text" size="40" maxlength="100" name="version" id="version" value="{VERSION}" class="text" />
						</dd>
					</dl>	
					<dl>
						<dt><label for="detected_in">{L_VERSION_DETECTED_IN}</label></dt>
						<dd> 
							<input type="checkbox" name="detected_in" {DETECTED_IN} />
						</dd>
					</dl>
					<dl>
						<dt><label for="fixed_in">{L_VERSION_FIXED_IN}</label></dt>
						<dd> 
							<input type="checkbox" name="fixed_in" {FIXED_IN} />
						</dd>
					</dl>
					<dl>
						<dd>
							<input type="submit" name="valid_add_version" onclick="return check_version();" value="{L_ADD}" class="submit" />
						</dd>
					</dl>
				</fieldset>
				
				<fieldset>
					<legend>
						{L_AUTH}
					</legend>
					<dl>
						<dt>
							<label>{L_READ_AUTH}</label>
						</dt>
						<dd>
							{BUG_READ_AUTH}
						</dd>
					</dl>
					<dl>
						<dt>
							<label>{L_CREATE_AUTH}</label>
						</dt>
						<dd>
							{BUG_CREATE_AUTH}
						</dd>
					</dl>
					<dl>
						<dt>
							<label>{L_CREATE_ADVANCED_AUTH}</label>
							<br />
							<span>{L_CREATE_ADVANCED_AUTH_EXPLAIN}</lspan>
						</dt>
						<dd>
							{BUG_CREATE_ADVANCED_AUTH}
						</dd>
					</dl>
					<dl>
						<dt>
							<label>{L_MODERATE_AUTH}</label>
						</dt>
						<dd>
							{BUG_MODERATE_AUTH}
						</dd>
					</dl>
				</fieldset>
				
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />			
				</fieldset>
			</form>
		</div>
		# END config #
		
		# START edit_type #
		<div id="admin_contents">
		<script type="text/javascript">
		<!--
		function check_form()
		{
			if(document.getElementById('type').value == "") {
				alert("{L_REQUIRE_TYPE}");
				return false;
			}
			return true;
		}
		-->
		</script>
			# IF C_ERROR_HANDLER #
			<div class="error_handler_position">
				<span id="errorh"></span>
				<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
					<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
					<br />	
				</div>
			</div>
			# ENDIF #	
			
			<form action="admin_bugtracker.php?token={TOKEN}" name="form" method="post" style="margin:auto;" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend>{L_EDIT_TYPE}</legend>
					<p>{L_REQUIRE}</p>
					<dl>
						<dt><label for="type">* {L_TYPE}</label></dt>
						<dd><label><input type="text" size="40" maxlength="100" id="type" name="type" value="{edit_type.TYPE}" class="text" /></label></dd>
					</dl>
				</fieldset>	
				
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="hidden" name="id" value="{edit_type.ID}" class="submit" />
					<input type="submit" name="valid_edit_type" value="{L_UPDATE}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />				
				</fieldset>	
			</form>
		</div>
		# END edit_type #
		
		# START edit_category #
		<div id="admin_contents">
		<script type="text/javascript">
		<!--
		function check_form()
		{
			if(document.getElementById('category').value == "") {
				alert("{L_REQUIRE_CATEGORY}");
				return false;
			}
			return true;
		}
		-->
		</script>
			# IF C_ERROR_HANDLER #
			<div class="error_handler_position">
				<span id="errorh"></span>
				<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
					<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
					<br />	
				</div>
			</div>
			# ENDIF #	
			
			<form action="admin_bugtracker.php?token={TOKEN}" name="form" method="post" style="margin:auto;" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend>{L_EDIT_CATEGORY}</legend>
					<p>{L_REQUIRE}</p>
					<dl>
						<dt><label for="category">* {L_CATEGORY}</label></dt>
						<dd><label><input type="text" size="40" maxlength="100" id="category" name="category" value="{edit_category.CATEGORY}" class="text" /></label></dd>
					</dl>
				</fieldset>	
				
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="hidden" name="id" value="{edit_category.ID}" class="submit" />
					<input type="submit" name="valid_edit_category" value="{L_UPDATE}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />				
				</fieldset>	
			</form>
		</div>
		# END edit_category #
		
		# START edit_version #
		<div id="admin_contents">
		<script type="text/javascript">
		<!--
		function check_form()
		{
			if(document.getElementById('version').value == "") {
				alert("{L_REQUIRE_VERSION}");
				return false;
			}
			return true;
		}
		-->
		</script>
			# IF C_ERROR_HANDLER #
			<div class="error_handler_position">
				<span id="errorh"></span>
				<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
					<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
					<br />	
				</div>
			</div>
			# ENDIF #	
			
			<form action="admin_bugtracker.php?token={TOKEN}" name="form" method="post" style="margin:auto;" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend>{L_EDIT_VERSION}</legend>
					<p>{L_REQUIRE}</p>
					<dl>
						<dt><label for="version">* {L_VERSION}</label></dt>
						<dd><label><input type="text" size="40" maxlength="100" id="version" name="version" value="{edit_version.VERSION}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="detected_in">{L_VERSION_DETECTED_IN}</label></dt>
						<dd> 
							<input type="checkbox" name="detected_in" {edit_version.DETECTED_IN} />
						</dd>
					</dl>
					<dl>
						<dt><label for="fixed_in">{L_VERSION_FIXED_IN}</label></dt>
						<dd> 
							<input type="checkbox" name="fixed_in" {edit_version.FIXED_IN} />
						</dd>
					</dl>
				</fieldset>	
				
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="hidden" name="id" value="{edit_version.ID}" class="submit" />
					<input type="submit" name="valid_edit_version" value="{L_UPDATE}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />				
				</fieldset>	
			</form>
		</div>
		# END edit_version #
