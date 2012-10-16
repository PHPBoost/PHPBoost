		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_BUGS_MANAGEMENT}</li>
				<li>
					<a href="admin_bugtracker.php"><img src="bugtracker.png" alt="" /></a>
					<br />
					<a href="admin_bugtracker.php" class="quick_link">{L_BUGS_CONFIG}</a>
				</li>
				
				<li>
					<a href="admin_bugtracker_authorizations.php"><img src="bugtracker.png" alt="" /></a>
					<br />
					<a href="admin_bugtracker_authorizations.php" class="quick_link">{L_AUTH}</a>
				</li>
			</ul>
		</div>
		
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
		
		function check_priority(){
			if(document.getElementById('priority').value == "") {
				alert("{L_REQUIRE_PRIORITY}");
				return false;
		    }
			return true;
		}
		
		function check_severity(){
			if(document.getElementById('severity').value == "") {
				alert("{L_REQUIRE_SEVERITY}");
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
		
		function Confirm_del_priority() {
			return confirm("{L_CONFIRM_DEL_PRIORITY}");
		}
		
		function Confirm_del_severity() {
			return confirm("{L_CONFIRM_DEL_SEVERITY}");
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
		
		function display_default_version_radio(version_id)
		{
			if (document.getElementById('detected_in' + version_id).checked)
				document.getElementById('default_version' + version_id).style.display = ""
			else
			{
				document.getElementById('default_version' + version_id).style.display = "none";
				document.getElementById('default_version' + version_id).checked = "";
			}
		}
		-->
		</script>
	
		<div id="admin_contents">
			# INCLUDE message_helper #
			
			<form action="admin_bugtracker.php?token={TOKEN}" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend>{L_BUGS_CONFIG}</legend>
					<p>{L_REQUIRE}</p>
					<dl>
						<dt><label for="items_per_page">* {L_ITEMS_PER_PAGE}</label></dt>
						<dd><label><input type="text" size="3" name="items_per_page" id="items_per_page" value="{ITEMS_PER_PAGE}" class="text" /></label></dd>
					</dl>
					<dl class="overflow_visible">
						<dt><label for="rejected_bug_color">{L_REJECTED_BUG_COLOR}</label></dt>
						<dd>#<input type="text" size="7" name="rejected_bug_color" id="rejected_bug_color" value="{REJECTED_BUG_COLOR}" style="background-color:\#{REJECTED_BUG_COLOR};" class="text" />
							<a href="javascript:bbcode_color('rejected_bug_color');bb_display_block('1', '');" onmouseout="bb_hide_block('1', '', 0);" class="bbcode_hover"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/color.png" alt="" class="valign_middle" /></a>	
							<div style="position:relative;z-index:100;display:none;" id="bb_block1">
								<div id="rejected_bug_color_list" class="bbcode_block" style="background:white;width:150px;" onmouseover="bb_hide_block('1', '', 1);" onmouseout="bb_hide_block('1', '', 0);">
								</div>
							</div>
						</dd>
					</dl>
					<dl class="overflow_visible">
						<dt><label for="fixed_bug_color">{L_FIXED_BUG_COLOR}</label></dt>
						<dd>#<input type="text" size="7" name="fixed_bug_color" id="fixed_bug_color" value="{FIXED_BUG_COLOR}" style="background-color:\#{FIXED_BUG_COLOR};" class="text" />
							<a href="javascript:bbcode_color('fixed_bug_color');bb_display_block('2', '');" onmouseout="bb_hide_block('2', '', 0);" class="bbcode_hover"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/color.png" alt="" class="valign_middle" /></a>	
							<div style="position:relative;z-index:100;display:none;" id="bb_block2">
								<div id="fixed_bug_color_list" class="bbcode_block" style="background:white;width:150px;" onmouseover="bb_hide_block('2', '', 1);" onmouseout="bb_hide_block('2', '', 0);">
								</div>
							</div>
						</dd>
					</dl>
					<dl>
						<dt>
							<label for="date_format">{L_DATE_FORMAT}</label><br />
						</dt>
						<dd>
							<span><input type="radio" name="date_format" value="date_format_short" # IF NOT C_DATE_FORMAT #checked=checked# ENDIF # /> {L_DATE}</span>
							<span style="margin-left:30px;"><input type="radio" name="date_format" value="date_format" # IF C_DATE_FORMAT #checked=checked# ENDIF # /> {L_DATE_TIME}</span>
						</dd>
					</dl>
					<dl>
						<dt><label for="comments_activated">{L_ACTIV_COM}</label></dt>
						<dd> 
							<input type="checkbox" name="comments_activated" {COM_CHECKED} />
						</dd>
					</dl>
					<dl>
						<dt><label for="cat_in_title_activated">{L_ACTIV_CAT_IN_TITLE}</label></dt>
						<dd> 
							<input type="checkbox" name="cat_in_title_activated" {CAT_IN_TITLE_CHECKED} />
						</dd>
					</dl>
					<dl>
						<dt>
							<label for="roadmap_activated">{L_ACTIV_ROADMAP}</label><br />
							<span>{L_ROADMAP_EXPLAIN}</span>
						</dt>
						<dd> 
							<input type="checkbox" name="roadmap_activated" {ROADMAP_CHECKED} />
						</dd>
					</dl>
					<dl>
						<dt>
							<label for="pm_activated">{L_ACTIV_PM}</label><br />
							<span>{L_PM_EXPLAIN}</span>
						</dt>
						<dd> 
							<input type="checkbox" name="pm_activated" {PM_CHECKED} />
						</dd>
					</dl>
				</fieldset>
				
				<fieldset>
					<legend>{L_CONTENT_VALUE_TITLE}</legend>
					<span>{L_CONTENT_VALUE_EXPLAIN}</span><br /><br />
					<label for="contents_value">{L_CONTENT_VALUE}</label>
					<div style="position:relative;display:none;" id="loading_previewcontents_value">
						<div style="margin:auto;margin-top:90px;width:100%;text-align:center;position:absolute;"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading.gif" alt="" /></div>
					</div>
					<div style="display:none;" class="xmlhttprequest_preview" id="xmlhttprequest_previewcontents_value"></div>
					{CONTENTS_KERNEL_EDITOR}
					<label><textarea rows="8" cols="86" id="contents_value" name="contents_value">{CONTENTS_VALUE}</textarea></label>
					<div style="text-align:center;"><input type="button" value="{L_PREVIEW}" onclick="XMLHttpRequest_preview('contents_value');" class="submit" /></div>
					<br />
				</fieldset>
				
				<fieldset>
					<legend>{L_DISPONIBLE_TYPES}</legend>
					<span>{L_TYPE_EXPLAIN}</span>
					# IF C_DISPLAY_TYPES #
					<dl>
						<dt>
							<label for="type_mandatory">{L_TYPE_MANDATORY}</label><br />
						</dt>
						<dd>
							<span><input type="radio" name="type_mandatory" value="1" # IF C_TYPE_MANDATORY #checked=checked# ENDIF # /> {L_YES}</span>
							<span style="margin-left:30px;"><input type="radio" name="type_mandatory" value="0" # IF NOT C_TYPE_MANDATORY #checked=checked# ENDIF # /> {L_NO}</span>
						</dd>
					</dl>
					# ENDIF #
					<table id="versions_list" class="module_table">
						<tr style="text-align:center;">
							<th>
								{L_DEFAULT}
							</th>
							<th style="width:80%">
								{L_NAME}
							</th>
							<th>
								{L_DELETE}
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
								<input type="radio" name="default_type" value="{types.ID}" {types.IS_DEFAULT} />
							</td>
							<td class="row2">
								<input type="text" maxlength="100" size="40" name="type{types.ID}" value="{types.NAME}" class="text" />
							</td>
							<td class="row2">
								<a href="admin_bugtracker.php?delete_type&amp;id={types.ID}&amp;token={TOKEN}" onclick="javascript:return Confirm_del_type();"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
							</td>
						</tr>
						# END types #
						# IF C_DISPLAY_DEFAULT_TYPE_DELETE_BUTTON #
						<tr>
							<td colspan="3" class="row3">
								<div style="float:left;"><input type="submit" name="delete_default_type" value="{L_DELETE_DEFAULT_VALUE}" class="submit" /></div>
							</td>
						</tr>
						# ENDIF #
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
					<legend>{L_DISPONIBLE_CATEGORIES}</legend>
					<span>{L_CATEGORY_EXPLAIN}</span>
					# IF C_DISPLAY_CATEGORIES #
					<dl>
						<dt>
							<label for="category_mandatory">{L_CATEGORY_MANDATORY}</label><br />
						</dt>
						<dd>
							<span><input type="radio" name="category_mandatory" value="1" # IF C_CATEGORY_MANDATORY #checked=checked# ENDIF # /> {L_YES}</span>
							<span style="margin-left:30px;"><input type="radio" name="category_mandatory" value="0" # IF NOT C_CATEGORY_MANDATORY #checked=checked# ENDIF # /> {L_NO}</span>
						</dd>
					</dl>
					# ENDIF #
					<table class="module_table">
						<tr style="text-align:center;">
							<th>
								{L_DEFAULT}
							</th>
							<th style="width:80%">
								{L_NAME}
							</th>
							<th>
								{L_DELETE}
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
								<input type="radio" name="default_category" value="{categories.ID}" {categories.IS_DEFAULT} />
							</td>
							<td class="row2">
								<input type="text" maxlength="100" size="40" name="category{categories.ID}" value="{categories.NAME}" class="text" />
							</td>
							<td class="row2">
								<a href="admin_bugtracker.php?delete_category&amp;id={categories.ID}&amp;token={TOKEN}" onclick="javascript:return Confirm_del_category();"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
							</td>
						</tr>
						# END categories #
						# IF C_DISPLAY_DEFAULT_CATEGORY_DELETE_BUTTON #
						<tr>
							<td colspan="3" class="row3">
								<div style="float:left;"><input type="submit" name="delete_default_category" value="{L_DELETE_DEFAULT_VALUE}" class="submit" /></div>
							</td>
						</tr>
						# ENDIF #
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
					<legend>{L_DISPONIBLE_SEVERITIES}</legend>
					<span>{L_SEVERITY_EXPLAIN}</span>
					# IF C_DISPLAY_SEVERITIES #
					<dl>
						<dt>
							<label for="severity_mandatory">{L_SEVERITY_MANDATORY}</label><br />
						</dt>
						<dd>
							<span><input type="radio" name="severity_mandatory" value="1" # IF C_SEVERITY_MANDATORY #checked=checked# ENDIF # /> {L_YES}</span>
							<span style="margin-left:30px;"><input type="radio" name="severity_mandatory" value="0" # IF NOT C_SEVERITY_MANDATORY #checked=checked# ENDIF # /> {L_NO}</span>
						</dd>
					</dl>
					# ENDIF #
					<table class="module_table">
						<tr style="text-align:center;">
							<th style="width:12%">
								{L_DEFAULT}
							</th>
							<th>
								{L_NAME}
							</th>
							<th style="width:15%">
								{L_COLOR}
							</th>
							<th style="width:10%">
								{L_DELETE}
							</th>
						</tr>
						# IF C_NO_SEVERITY #
						<tr style="text-align:center;"> 
							<td colspan="3" class="row2">
								{L_NO_SEVERITY}
							</td>
						</tr>
						# ELSE #
						# START severities #
						<tr style="text-align:center;">
							<td class="row2">
								<input type="radio" name="default_severity" value="{severities.ID}" {severities.IS_DEFAULT} />
							</td>
							<td class="row2">
								<input type="text" maxlength="100" size="40" name="severity{severities.ID}" value="{severities.NAME}" class="text" />
							</td> 
							<td class="row2">
								\#<input type="text" size="7" name="s_color{severities.ID}" id="s_color{severities.ID}" value="{severities.COLOR}" style="background-color:\#{severities.COLOR};" class="text" />
								<a href="javascript:bbcode_color('s_color{severities.ID}');bb_display_block('{severities.ID_BBCODE_COLOR}', '');" onmouseout="bb_hide_block('{severities.ID_BBCODE_COLOR}', '', 0);" class="bbcode_hover"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/color.png" alt="" class="valign_middle" /></a>	
								<div style="position:relative;z-index:100;display:none;" id="bb_block{severities.ID_BBCODE_COLOR}">
									<div id="s_color{severities.ID}_list" class="bbcode_block" style="background:white;width:150px;" onmouseover="bb_hide_block('{severities.ID_BBCODE_COLOR}', '', 1);" onmouseout="bb_hide_block('{severities.ID_BBCODE_COLOR}', '', 0);">
									</div>
								</div>
							</td>
							<td class="row2"> 
								<a href="admin_bugtracker.php?delete_severity&amp;id={severities.ID}&amp;token={TOKEN}" onclick="javascript:return Confirm_del_severity();"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
							</td>
						</tr>
						# END severities #
						# IF C_DISPLAY_DEFAULT_SEVERITY_DELETE_BUTTON #
						<tr>
							<td colspan="3" class="row3">
								<div style="float:left;"><input type="submit" name="delete_default_severity" value="{L_DELETE_DEFAULT_VALUE}" class="submit" /></div>
							</td>
						</tr>
						# ENDIF #
						# ENDIF #
					</table>
					<br />
					<dl>
						<dt><label for="severity">{L_ADD_SEVERITY}</label></dt>
						<dd>
							<input type="text" size="40" maxlength="100" name="severity" id="severity" value="{SEVERITY}" class="text" />
						</dd>
					</dl>	
					<dl class="overflow_visible">
						<dt><label for="s_color">{L_COLOR}</label></dt>
						<dd> 
							\#<input type="text" size="7" name="s_color" id="s_color" value="{S_COLOR}" style="background-color:\#{S_COLOR};" class="text" />
							<a href="javascript:bbcode_color('s_color');bb_display_block('3', '');" onmouseout="bb_hide_block('3', '', 0);" class="bbcode_hover"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/color.png" alt="" class="valign_middle" /></a>	
							<div style="position:relative;z-index:100;display:none;" id="bb_block3">
								<div id="s_color_list" class="bbcode_block" style="background:white;width:150px;" onmouseover="bb_hide_block('3', '', 1);" onmouseout="bb_hide_block('3', '', 0);">
								</div>
							</div>
						</dd>
					</dl>
					<dl>
						<dd>
							<input type="submit" name="valid_add_severity" onclick="return check_severity();" value="{L_ADD}" class="submit" />
						</dd>
					</dl>
				</fieldset>
				
				<fieldset>
					<legend>{L_DISPONIBLE_PRIORITIES}</legend>
					<span>{L_PRIORITY_EXPLAIN}</span>
					# IF C_DISPLAY_PRIORITIES #
					<dl>
						<dt>
							<label for="priority_mandatory">{L_PRIORITY_MANDATORY}</label><br />
						</dt>
						<dd>
							<span><input type="radio" name="priority_mandatory" value="1" # IF C_PRIORITY_MANDATORY #checked=checked# ENDIF # /> {L_YES}</span>
							<span style="margin-left:30px;"><input type="radio" name="priority_mandatory" value="0" # IF NOT C_PRIORITY_MANDATORY #checked=checked# ENDIF # /> {L_NO}</span>
						</dd>
					</dl>
					# ENDIF #
					<table class="module_table">
						<tr style="text-align:center;">
							<th>
								{L_DEFAULT}
							</th>
							<th style="width:80%">
								{L_NAME}
							</th>
							<th>
								{L_DELETE}
							</th>
						</tr>
						# IF C_NO_PRIORITY #
						<tr style="text-align:center;"> 
							<td colspan="2" class="row2">
								{L_NO_PRIORITY}
							</td>
						</tr>
						# ELSE #
						# START priorities #
						<tr style="text-align:center;">
							<td class="row2">
								<input type="radio" name="default_priority" value="{priorities.ID}" {priorities.IS_DEFAULT} />
							</td>
							<td class="row2">
								<input type="text" maxlength="100" size="40" name="priority{priorities.ID}" value="{priorities.NAME}" class="text" />
							</td>
							<td class="row2">
								<a href="admin_bugtracker.php?delete_priority&amp;id={priorities.ID}&amp;token={TOKEN}" onclick="javascript:return Confirm_del_priority();"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
							</td>
						</tr>
						# END priorities #
						# IF C_DISPLAY_DEFAULT_PRIORITY_DELETE_BUTTON #
						<tr>
							<td colspan="3" class="row3">
								<div style="float:left;"><input type="submit" name="delete_default_priority" value="{L_DELETE_DEFAULT_VALUE}" class="submit" /></div>
							</td>
						</tr>
						# ENDIF #
						# ENDIF #
					</table>
					<br />
					<dl>
						<dt><label for="priority">{L_ADD_PRIORITY}</label></dt>
						<dd>
							<input type="text" size="40" maxlength="100" name="priority" id="priority" value="{PRIORITY}" class="text" />
							&nbsp;&nbsp; 
							<input type="submit" name="valid_add_priority" onclick="return check_priority();" value="{L_ADD}" class="submit" />
						</dd>
					</dl>
				</fieldset>
				
				<fieldset>
					<legend>{L_DISPONIBLE_VERSIONS}</legend>
					<span>{L_VERSION_EXPLAIN}</span>
					# IF C_DISPLAY_VERSIONS #
					<dl>
						<dt>
							<label for="detected_in_mandatory">{L_DETECTED_IN_MANDATORY}</label><br />
						</dt>
						<dd>
							<span><input type="radio" name="detected_in_mandatory" value="1" # IF C_DETECTED_IN_MANDATORY #checked=checked# ENDIF # /> {L_YES}</span>
							<span style="margin-left:30px;"><input type="radio" name="detected_in_mandatory" value="0" # IF NOT C_DETECTED_IN_MANDATORY #checked=checked# ENDIF # /> {L_NO}</span>
						</dd>
					</dl>
					# ENDIF #
					<table class="module_table">
						<tr style="text-align:center;">
							<th style="width:12%">
								{L_DEFAULT}
							</th>
							<th>
								{L_NAME}
							</th>
							<th style="width:10%">
								{L_VERSION_DETECTED}
							</th>
							<th style="width:10%">
								{L_DELETE}
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
								<input type="radio" id="default_version{versions.ID}" name="default_version" value="{versions.ID}" {versions.IS_DEFAULT} {versions.DISPLAY_DEFAULT}/>
							</td>
							<td class="row2">
								<input type="text" maxlength="100" size="40" name="version{versions.ID}" value="{versions.NAME}" class="text" />
							</td> 
							<td class="row2">
								<input type="checkbox" id="detected_in{versions.ID}" name="detected_in{versions.ID}" onclick="javascript:display_default_version_radio('{versions.ID}');" {versions.DETECTED_IN} />
							</td> 
							<td class="row2">
								<a href="admin_bugtracker.php?delete_version&amp;id={versions.ID}&amp;token={TOKEN}" onclick="javascript:return Confirm_del_version();"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
							</td>
						</tr>
						# END versions #
						# IF C_DISPLAY_DEFAULT_VERSION_DELETE_BUTTON #
						<tr>
							<td colspan="3" class="row3">
								<div style="float:left;"><input type="submit" name="delete_default_version" value="{L_DELETE_DEFAULT_VALUE}" class="submit" /></div>
							</td>
						</tr>
						# ENDIF #
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
						<dd>
							<input type="submit" name="valid_add_version" onclick="return check_version();" value="{L_ADD}" class="submit" />
						</dd>
					</dl>
				</fieldset>
				
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />			
				</fieldset>
			</form>
		</div>
