<script type="text/javascript">
	<!--
	function Confirm() {
		return confirm("{L_CONFIRM_DEL_BUG}");
	}
	
	function hidediv(id) {
		//safe function to hide an element with a specified id
		if (document.getElementById) { // DOM3 = IE5, NS6
			document.getElementById(id).style.display = 'none';
		}
		else {
			if (document.layers) { // Netscape 4
				document.id.display = 'none';
			}
			else { // IE 4
				document.all.id.style.display = 'none';
			}
		}
	}

	function showdiv(id) {
		//safe function to show an element with a specified id
		if (document.getElementById) { // DOM3 = IE5, NS6
			document.getElementById(id).style.display = 'block';
		}
		else {
			if (document.layers) { // Netscape 4
				document.id.display = 'block';
			}
			else { // IE 4
				document.all.id.style.display = 'block';
			}
		}
	}
	-->
</script>
# START list #
	<div class="module_position">
		<div class="module_top_l"></div>		
		<div class="module_top_r"></div>
		<div class="module_top">
			<div style="float:left">
				<a href="bugtracker.php{SID}">{L_BUGS_LIST}</a> {ADD_BUG}
			</div>
		</div>
		<div class="module_contents">
			<table  class="module_table">
				<tr style="text-align:center;">
					<th>
					<a href="../bugtracker/bugtracker{U_BUG_ID_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						{L_ID}
					<a href="../bugtracker/bugtracker{U_BUG_ID_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					<th style="width:15%">
					<a href="../bugtracker/bugtracker{U_BUG_TITLE_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						{L_TITLE}
					<a href="../bugtracker/bugtracker{U_BUG_TITLE_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					# IF NOT C_EMPTY_TYPES #
					<th>
					<a href="../bugtracker/bugtracker{U_BUG_TYPE_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						{L_TYPE}
					<a href="../bugtracker/bugtracker{U_BUG_TYPE_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					# ENDIF #
					<th>
					<a href="../bugtracker/bugtracker{U_BUG_SEVERITY_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						{L_SEVERITY}
					<a href="../bugtracker/bugtracker{U_BUG_SEVERITY_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					<th>
					<a href="../bugtracker/bugtracker{U_BUG_PRIORITY_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						{L_PRIORITY}
					<a href="../bugtracker/bugtracker{U_BUG_PRIORITY_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					<th>
					<a href="../bugtracker/bugtracker{U_BUG_DATE_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						{L_DATE}
					<a href="../bugtracker/bugtracker{U_BUG_DATE_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					# IF C_IS_ADMIN #
					<th>
						{L_ACTIONS}
					</th>
					# ENDIF #
				</tr>
				# IF C_NO_BUGS #
				<tr style="text-align:center;"> 
					<td colspan="# IF NOT C_EMPTY_TYPES ## IF C_IS_ADMIN #7# ELSE #6# ENDIF ## ELSE ## IF C_IS_ADMIN #6# ELSE #5# ENDIF ## ENDIF #" class="row2">
						{L_NO_BUG}
					</td>
				</tr>
				# ELSE #
				# START list.bug #
				<tr style="text-align:center;"{list.bug.LINE_COLOR}> 
					<td class="row2">
						<a href="../bugtracker/bugtracker.php?view=true&amp;id={list.bug.ID}">#{list.bug.ID}</a>
					</td>
					<td class="row2">
						{list.bug.TITLE}
					</td>
					# IF NOT C_EMPTY_TYPES #
					<td class="row2">
						{list.bug.TYPE}
					</td>
					# ENDIF #
					<td class="row2"{bug.COLOR}> 
						<b>{list.bug.SEVERITY}</b>
					</td>
					<td class="row2"> 
						{list.bug.PRIORITY}
					</td>
					<td class="row2">
						{list.bug.DATE}
					</td>
					# IF C_IS_ADMIN #
					<td class="row2"> 
						<a href="bugtracker.php?edit=true&amp;id={list.bug.ID}"><img src="../templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" /></a>
						<a href="bugtracker.php?history=true&amp;id={list.bug.ID}"><img src="{PATH_TO_ROOT}/bugtracker/templates/images/history.png" alt="{L_HISTORY}" title="{L_HISTORY}" /></a>
						<a href="bugtracker.php?delete=true&amp;id={list.bug.ID}&amp;token={TOKEN}" onclick="javascript:return Confirm();"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
					</td>
					# ENDIF #
				</tr>
				# END list.bugs #
				# ENDIF #
			</table>

			<br /><br />
			<p style="text-align: center;">{PAGINATION}</p>
		</div>
		<div class="module_bottom_l"></div>
		<div class="module_bottom_r"></div>
		<div class="module_bottom">
			<div style="float:left" class="text_small"></div>
			<div style="float:right" class="text_small"></div>
		</div>
	</div>
# END list #

# START add #
	<script type="text/javascript">
	<!--
	function check_form()
	{
		# IF C_BBCODE_TINYMCE_MODE #
		tinyMCE.triggerSave();
		# ENDIF #

		if(document.getElementById('title').value == "") {
			alert("{L_REQUIRE_TITLE}");
			return false;
		}
		if(document.getElementById('contents').value == "") {
			alert("{L_REQUIRE_TEXT}");
			return false;
		}
		# IF C_DISPLAY_CATEGORIES #
		if(document.getElementById('category').value == "") {
			alert("{L_REQUIRE_CATEGORY}");
			return false;
		}
		# ENDIF #
		return true;
	}

	function bbcode_page()
	{
		var page = prompt("{L_PAGE_PROMPT}");
		if( page != null && page != '' )
			insertbbcode('[page]' + page, '[/page]', 'contents');
	}
	-->
	</script>

	<div class="module_position">
		<div class="module_top_l"></div>		
		<div class="module_top_r"></div>
		<div class="module_top">
			<div style="float:left">
				{L_ADD_BUG}
			</div>
		</div>
		<div class="module_contents">
			# IF C_ERROR_HANDLER #
			<div class="error_handler_position">
				<span id="errorh"></span>
				<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
					<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
					<br />	
				</div>
			</div>
			# ENDIF #	
			
			<form action="bugtracker.php?token={TOKEN}" name="form" method="post" style="margin:auto;" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<p>{L_REQUIRE}</p>
					<dl>
						<dt><label for="title">* {L_TITLE}</label></dt>
						<dd><label><input type="text" size="40" maxlength="100" id="title" name="title" class="text" /></label></dd>
					</dl>
					<label for="contents">* {L_CONTENT}</label>
					{KERNEL_EDITOR}
					<label><textarea rows="20" cols="86" id="contents" name="contents">{CONTENTS}</textarea></label>
					<br />
					# IF C_DISPLAY_TYPES #
					<dl>
						<dt><label for="type">{L_TYPE}</label></dt>
						<dd><label>
							<select id="type" name="type">				
							# START select_type #				
								{select_type.TYPE}				
							# END select_type #				
							</select>
						</label></dd>
					</dl>
					# ENDIF #
					# IF C_DISPLAY_CATEGORIES #
					<dl>
						<dt><label for="category">* {L_CATEGORY}</label></dt>
						<dd><label>
							<select id="category" name="category">				
							# START select_category #				
								{select_category.CATEGORY}				
							# END select_category #				
							</select>
						</label></dd>
					</dl>
					# ENDIF #
					# IF C_DISPLAY_ADVANCED #
					<dl>
						<dt><label for="severity">{L_SEVERITY}</label></dt>
						<dd><label>
							<select id="severity" name="severity">				
							# START select_severity #				
								{select_severity.SEVERITY}				
							# END select_severity #				
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="priority">{L_PRIORITY}</label></dt>
						<dd><label>
							<select id="priority" name="priority">				
							# START select_priority #				
								{select_priority.PRIORITY}				
							# END select_priority #				
							</select>
						</label></dd>
					</dl>
					# ENDIF #
					# IF C_DISPLAY_VERSIONS #
					<dl>
						<dt><label for="detected_in">{L_DETECTED_IN}</label></dt>
						<dd><label>
							<select id="detected_in" name="detected_in">				
							# START select_detected_in #				
								{select_detected_in.VERSION}				
							# END select_detected_in #				
							</select>
						</label></dd>
					</dl>
					# ENDIF #
					<dl>
						<dt><label for="reproductible">{L_REPRODUCTIBLE}</label></dt>
						<dd>
							<label><input type="radio" {REPRODUCTIBLE_ENABLED} name="reproductible" id="reproductible" onclick="javascript:showdiv('reproduction');" value="1" /> {L_YES}</label>
							&nbsp;&nbsp;
							<label><input type="radio" {REPRODUCTIBLE_DISABLED} name="reproductible" onclick="javascript:hidediv('reproduction');" value="0" /> {L_NO}</label>
						</dd>
					</dl>
					<div id="reproduction">
						<label for="reproduction_method">{L_REPRODUCTION_METHOD}</label>
						{KERNEL_EDITOR}
						<label><textarea rows="20" cols="86" id="reproduction_method" name="reproduction_method">{REPRODUCTION_METHOD}</textarea></label>
						<br />
					</div>
					<br />
				</fieldset>	
				
				<fieldset class="fieldset_submit">
					<legend>{L_SUBMIT}</legend>
					<input type="submit" name="valid_add" value="{L_SUBMIT}" class="submit" />
					&nbsp;&nbsp; 
					<input value="{L_PREVIEW}" type="submit" name="previs" id="previs_add" class="submit" />
					<script type="text/javascript">
					<!--				
					document.getElementById('previs_add').style.display = 'none';
					document.write('<input value="{L_PREVIEW}" onclick="XMLHttpRequest_preview();" type="button" class="submit" />');
					-->
					</script>
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />				
				</fieldset>	
			</form>
		</div>
		<div class="module_bottom_l"></div>
		<div class="module_bottom_r"></div>
		<div class="module_bottom">
			<div style="float:left" class="text_small"></div>
			<div style="float:right" class="text_small"></div>
		</div>
	</div>
# END add #

# START edit #
	<script type="text/javascript">
	<!--
	function check_form()
	{
		# IF C_BBCODE_TINYMCE_MODE #
		tinyMCE.triggerSave();
		# ENDIF #

		if(document.getElementById('title').value == "") {
			alert("{L_REQUIRE_TITLE}");
			return false;
		}
		if(document.getElementById('contents').value == "") {
			alert("{L_REQUIRE_CONTENT}");
			return false;
		}
		# IF C_DISPLAY_CATEGORIES #
		if(document.getElementById('category').value == "") {
			alert("{L_REQUIRE_CATEGORY}");
			return false;
		}
		# ENDIF #
		return true;
	}

	function bbcode_page()
	{
		var page = prompt("{L_PAGE_PROMPT}");
		if( page != null && page != '' )
			insertbbcode('[page]' + page, '[/page]', 'contents');
	}
	
	# IF edit.C_IS_ADMIN #
	function XMLHttpRequest_search()
	{
		var login = document.getElementById("login").value;
		if( login != "" )
		{
			if( document.getElementById('loading_members') )
				document.getElementById('loading_members').innerHTML = '<img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />';
			
			data = 'login=' + login;
			var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/kernel/framework/ajax/member_xmlhttprequest.php?token={TOKEN}&insert_member=1');
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
				{
					document.getElementById("xmlhttprequest_result_search").innerHTML = xhr_object.responseText;
					show_div("xmlhttprequest_result_search");
					if( document.getElementById('loading_members') )
						document.getElementById('loading_members').innerHTML = '';
				}
				else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
				{	
					if( document.getElementById('loading_members') )
						document.getElementById('loading_members').innerHTML = '';
				}
			}
			xmlhttprequest_sender(xhr_object, data);
		}	
		else
			alert("{L_REQUIRE_LOGIN}");
	}
	# ENDIF #
	-->
	</script>

	<div class="module_position">
		<div class="module_top_l"></div>		
		<div class="module_top_r"></div>
		<div class="module_top">
			<div style="float:left">
				{L_EDIT_BUG} : #{edit.ID}
			</div>
		</div>
		<div class="module_contents">
			# IF C_ERROR_HANDLER #
			<div class="error_handler_position">
				<span id="errorh"></span>
				<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
					<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
					<br />	
				</div>
			</div>
			# ENDIF #	
			
			<form action="bugtracker.php?token={TOKEN}" name="form" method="post" style="margin:auto;" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<p>{L_REQUIRE}</p>
					<dl>
						<dt><label for="title">* {L_TITLE}</label></dt>
						<dd><label><input type="text" size="40" maxlength="100" id="title" name="title" value="{edit.TITLE}" class="text" /></label></dd>
					</dl>
					<label for="contents">* {L_CONTENT}</label>
					{KERNEL_EDITOR}
					<label><textarea rows="20" cols="86" id="contents" name="contents">{edit.CONTENTS}</textarea></label>
					<br />
					# IF edit.C_IS_ADMIN #
					<dl>
						<dt><label for="author">{L_AUTHOR}</label></dt>
						<dd><label>{edit.AUTHOR}</label></dd>
					</dl>
					<dl>
						<dt><label for="status">{L_STATUS}</label><br /><span>{L_STATUS_EXPLAIN}</span></dt>
						<dd><label>
							<select id="status" name="status">				
							# START edit.select_status #				
								{edit.select_status.STATUS}				
							# END edit.select_status #				
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="assigned_to">{L_ASSIGNED_TO}</label><br /><span>{L_JOKER}</span></dt>
						<dd>
							<input type="text" size="20" maxlength="25" id="login" value="{edit.ASSIGNED_TO}" name="assigned_to" class="text" /> 
							<span id="loading_members"></span>
							<script type="text/javascript">
							<!--								
								document.write('<input value="{L_SEARCH}" onclick="XMLHttpRequest_search();" type="button" class="submit">');
							-->
							</script>
							<div id="xmlhttprequest_result_search" style="display:none;" class="xmlhttprequest_result_search"></div>
						</dd>
					</dl>
					# ENDIF #
					# IF C_DISPLAY_TYPES #
					<dl>
						<dt><label for="type">{L_TYPE}</label></dt>
						<dd><label>
							<select id="type" name="type">				
							# START edit.select_type #				
								{edit.select_type.TYPE}				
							# END edit.select_type #				
							</select>
						</label></dd>
					</dl>
					# ENDIF #
					# IF C_DISPLAY_CATEGORIES #
					<dl>
						<dt><label for="category">* {L_CATEGORY}</label></dt>
						<dd><label>
							<select id="category" name="category">				
							# START edit.select_category #				
								{edit.select_category.CATEGORY}				
							# END edit.select_category #				
							</select>
						</label></dd>
					</dl>
					# ENDIF #
					# IF C_DISPLAY_ADVANCED #
					<dl>
						<dt><label for="severity">{L_SEVERITY}</label></dt>
						<dd><label>
							<select id="severity" name="severity">				
							# START edit.select_severity #				
								{edit.select_severity.SEVERITY}				
							# END edit.select_severity #				
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="priority">{L_PRIORITY}</label></dt>
						<dd><label>
							<select id="priority" name="priority">				
							# START edit.select_priority #				
								{edit.select_priority.PRIORITY}				
							# END edit.select_priority #				
							</select>
						</label></dd>
					</dl>
					# ENDIF #
					# IF C_DISPLAY_VERSIONS #
					<dl>
						<dt><label for="detected_in">{L_DETECTED_IN}</label></dt>
						<dd><label>
							<select id="detected_in" name="detected_in">				
							# START edit.select_detected_in #				
								{edit.select_detected_in.VERSION}				
							# END edit.select_detected_in #				
							</select>
						</label></dd>
					</dl>
					# IF edit.C_IS_ADMIN #
					<dl>
						<dt><label for="fixed_in">{L_FIXED_IN}</label></dt>
						<dd><label>
							<select id="fixed_in" name="fixed_in">				
							# START edit.select_fixed_in #				
								{edit.select_fixed_in.VERSION}				
							# END edit.select_fixed_in #				
							</select>
						</label></dd>
					</dl>
					# ENDIF #
					# ENDIF #
					<dl>
						<dt><label for="reproductible">{L_REPRODUCTIBLE}</label></dt>
						<dd>
							<label><input type="radio" {edit.REPRODUCTIBLE_ENABLED} name="reproductible" id="reproductible" onclick="javascript:showdiv('reproduction');" value="1" /> {L_YES}</label>
							&nbsp;&nbsp;
							<label><input type="radio" {edit.REPRODUCTIBLE_DISABLED} name="reproductible" onclick="javascript:hidediv('reproduction');" value="0" /> {L_NO}</label>
						</dd>
					</dl>
					<div id="reproduction" # IF NOT C_REPRODUCTIBLE #style="display:none;"# ENDIF #>
						<label for="reproduction_method">{L_REPRODUCTION_METHOD}</label>
						{KERNEL_EDITOR}
						<label><textarea rows="20" cols="86" id="reproduction_method" name="reproduction_method">{edit.REPRODUCTION_METHOD}</textarea></label>
						<br />
					</div>
					<br />
				</fieldset>	
				
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="hidden" name="id" value="{edit.ID}" class="submit" />
					<input type="submit" name="valid_edit" value="{L_UPDATE}" class="submit" />
					&nbsp;&nbsp; 
					<input value="{L_PREVIEW}" type="submit" name="previs" id="previs_edit" class="submit" />
					<script type="text/javascript">
					<!--				
					document.getElementById('previs_edit').style.display = 'none';
					document.write('<input value="{L_PREVIEW}" onclick="XMLHttpRequest_preview();" type="button" class="submit" />');
					-->
					</script>
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />				
				</fieldset>	
			</form>
		</div>
		<div class="module_bottom_l"></div>
		<div class="module_bottom_r"></div>
		<div class="module_bottom">
			<div style="float:left" class="text_small"></div>
			<div style="float:right" class="text_small"></div>
		</div>
	</div>
# END edit #

# START view #
	<div class="module_position">
		<div class="module_top_l"></div>		
		<div class="module_top_r"></div>
		<div class="module_top">
			<div style="float:left">
				{L_VIEW_BUG} #{view.ID}
			</div>
			<div style="float:right">
				# IF C_COM #<img src="{PATH_TO_ROOT}/bugtracker/templates/images/comments.png" alt="" class="valign_top" /> {view.U_COM}# ENDIF #
				# IF C_EDIT_BUG #&nbsp;<a href="bugtracker.php?edit=true&amp;id={view.ID}"><img src="../templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" /></a># ENDIF #
				# IF C_HISTORY_BUG #&nbsp;<a href="bugtracker.php?history=true&amp;id={view.ID}"><img src="{PATH_TO_ROOT}/bugtracker/templates/images/history.png" alt="{L_HISTORY}" title="{L_HISTORY}" /></a># ENDIF #
				# IF C_DELETE_BUG #&nbsp;<a href="admin_bugtracker.php?delete=true&amp;id={view.ID}&amp;token={TOKEN}" onclick="javascript:return Confirm();"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a># ENDIF #
			</div>
		</div>
		<div class="module_contents">
			<fieldset>
				<legend>{view.TITLE}</legend>
				<br />
				{view.CONTENTS}
				<br /><br />
			</fieldset>
			
			<fieldset>
				<legend>{L_BUG_INFOS}</legend>
				<br />
				# IF NOT C_EMPTY_TYPES #
				<dl>
					<dt>{L_TYPE}</dt>
					<dd>{view.TYPE}</dd>
				</dl>
				# ENDIF #
				# IF NOT C_EMPTY_CATEGORIES #
				<dl>
					<dt>{L_CATEGORY}</dt>
					<dd>{view.CATEGORY}</dd>
				</dl>
				# ENDIF #
				<dl>
					<dt>{L_SEVERITY}</dt>
					<dd>{view.SEVERITY}</dd>
				</dl>
				<dl>
					<dt>{L_PRIORITY}</dt>
					<dd>{view.PRIORITY}</dd>
				</dl>
				# IF NOT C_EMPTY_VERSIONS #
				<dl>
					<dt>{L_DETECTED_IN}</dt>
					<dd>{view.DETECTED_IN}</dd>
				</dl>
				# ENDIF #
				<dl>
					<dt>{L_REPRODUCTIBLE}</dt>
					<dd>{view.REPRODUCTIBLE}</dd>
				</dl>
			</fieldset>
			
			# IF C_REPRODUCTIBLE #
			<fieldset>
				<legend>{L_REPRODUCTION_METHOD}</legend>
				<br />
				{view.REPRODUCTION_METHOD}
				<br /><br />
			</fieldset>
			# ENDIF #
			
			<fieldset>
				<legend>{L_BUG_TREATMENT_STATE}</legend>
				<br />
				<dl>
					<dt>{L_STATUS}</dt>
					<dd>{view.STATUS}</dd>
				</dl>
				# IF NOT C_EMPTY_VERSIONS #
				<dl>
					<dt>{L_FIXED_IN}</dt>
					<dd>{view.FIXED_IN}</dd>
				</dl>
				# ENDIF #
				<dl>
					<dt>{L_ASSIGNED_TO}</dt>
					<dd>{view.USER_ASSIGNED}</dd>
				</dl>
			</fieldset>
		</div>
		<div class="module_bottom_l"></div>
		<div class="module_bottom_r"></div>
		<div class="module_bottom">
			<div style="float:left" class="text_small">
				&nbsp;
			</div>
			<div style="float:right" class="text_small">
				{L_DETECTED_BY}: {view.AUTHOR}, {L_ON}: {view.SUBMIT_DATE}
			</div>
		</div>
	</div>

	<br /><br />
	{COMMENTS}
# END view #

# START history #
	<div class="module_position">
		<div class="module_top_l"></div>		
		<div class="module_top_r"></div>
		<div class="module_top">
			<div style="float:left">
				{L_HISTORY_BUG} : #{ID}
			</div>
		</div>
		<div class="module_contents">
			<table  class="module_table">
				<tr style="text-align:center;">
					<th>
						{L_UPDATER}
					</th>
					<th>
						{L_UPDATED_FIELD}
					</th>
					<th>
						{L_OLD_VALUE}
					</th>
					<th>
						{L_NEW_VALUE}
					</th>
					<th>
						{L_DATE}
					</th>
					<th>
						{L_COMMENT}
					</th>
				</tr>
				# IF C_NO_HISTORY #
				<tr style="text-align:center;"> 
					<td colspan="6" class="row2">
						{L_NO_HISTORY}
					</td>
				</tr>
				# ELSE #
				# START history.bug #
				<tr style="text-align:center;"> 
					<td class="row2"> 
						{bug.UPDATER}
					</td>
					<td class="row2"> 
						{bug.UPDATED_FIELD}
					</td>
					<td class="row2"> 
						{bug.OLD_VALUE}
					</td>
					<td class="row2"> 
						{bug.NEW_VALUE}
					</td>	
					<td class="row2">
						{bug.DATE}
					</td>
					<td class="row2">
						{bug.COMMENT}
					</td>
				</tr>
				# END history.bug #
				# ENDIF #
			</table>
		</div>
		<div class="module_bottom_l"></div>
		<div class="module_bottom_r"></div>
		<div class="module_bottom">
	</div>
# END history #