	<div id="admin_quick_menu">
		<ul>
			<li class="title_menu">{L_EXTEND_FIELD}</li>
			<li>
				<a href="admin_extend_field.php"><img src="../templates/{THEME}/images/admin/extendfield.png" alt="" /></a>
				<br />
				<a href="admin_extend_field.php" class="quick_link">{L_EXTEND_FIELD_MANAGEMENT}</a>
			</li>
			<li>
				<a href="admin_extend_field_add.php"><img src="../templates/{THEME}/images/admin/extendfield.png" alt="" /></a>
				<br />
				<a href="admin_extend_field_add.php" class="quick_link">{L_EXTEND_FIELD_ADD}</a>
			</li>
		</ul>
	</div>
	
	<div id="admin_contents">	
		# START field_management #
		<form action="admin_config.php" method="post" onsubmit="return check_form_conf();" class="fieldset_content">
			<table class="module_table">
				<tr> 
					<th colspan="4">
						{L_EXTEND_FIELD_MANAGEMENT}
					</th>
				</tr>
				<tr style="text-align:center;">
					<td class="row1">
						{L_NAME}
					</td>
					<td class="row1" style="width:180px">
						{L_POSITION}
					</td>
					<td class="row1" style="width:180px">
						{L_UPDATE}
					</td>				
					<td class="row1" style="width:180px">
						{L_DELETE}
					</td>
				</tr>
				
				# START field_management.field #
				<tr style="text-align:center;"> 
					<td class="row2">
						<span id="e{field_management.field.ID}"></span>
						{field_management.field.NAME}
					</td>				
					<td class="row2">
						{field_management.field.TOP}
						{field_management.field.BOTTOM}
					</td>
					<td class="row2"> 
						<a href="admin_extend_field.php?id={field_management.field.ID}"><img src="../templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" /></a>
					</td>
					<td class="row2">
						<a href="admin_extend_field.php?del=1&amp;id={field_management.field.ID}" onClick="javascript:return Confirm();"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
					</td>
				</tr>
				# END field_management.field #
			</table>
		</form>
		# END field_management #

		# START field_edit #
		<script type="text/javascript">
		<!--
		function check_form_field(){
			if(document.getElementById('name').value == "") {
				alert("{L_REQUIRE_NAME}");
				return false;
			}		
			return true;
		}
		function change_status(regexid){
			if( regexid > 2 ) 
			{
				document.getElementById('possible_values').innerHTML = '{L_DEFAULT_FIELD_VALUE}'; 
				document.getElementById('possible_values').disabled = '';
				document.getElementById('regex1').disabled = 'disabled';
				document.getElementById('regex2').disabled = 'disabled';
				document.getElementById('regex_type1').disabled = 'disabled';
				document.getElementById('regex_type2').disabled = 'disabled';
			}
			else
			{ 
				document.getElementById('possible_values').innerHTML = '';
				document.getElementById('possible_values').disabled = 'disabled';
				document.getElementById('regex1').disabled = '';
				document.getElementById('regex2').disabled = '';
				document.getElementById('regex_type1').disabled = '';
				document.getElementById('regex_type2').disabled = '';
			}
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
				
		<form action="admin_extend_field.php?id={field_edit.ID}" method="post" onsubmit="return check_form_field();" class="fieldset_content">
			<fieldset>
				<legend>{L_EXTEND_FIELD_EDIT}</legend>
				<dl> 
					<dt><label for="name">* {L_NAME}</label></dt>
					<dd><label><input type="text" size="40" maxlength="100" id="name" name="name" value="{field_edit.NAME}" class="text" /></select></label></dd>
				<dl>
				<dl> 
					<dt><label for="contents">{L_DESC}</label></dt>
					<dd><label><textarea type="text" class="post" rows="4" cols="30" name="contents">{field_edit.CONTENTS}</textarea> </label></dd>
				</dl>
				<dl> 
					<dt><label for="field">* {L_TYPE}</label></dt>
					<dd><label>
						<select name="field" onchange="change_status(this.options[selectedIndex].value)">
						# START field_edit.field #
						{field_edit.field.FIELD}
						# END field_edit.field #
					</select>
					</label></dd>
				</dl>
				<dl> 
					<dt><label for="possible_values">{L_POSSIBLE_VALUES}</label><br /><span>{L_POSSIBLE_VALUES_EXPLAIN}</span></dt>
					<dd><label><textarea type="text" class="post" rows="2" cols="30" name="possible_values" id="possible_values" style="width:50%">{field_edit.POSSIBLE_VALUES}</textarea></label></dd>
				</dl>
				<dl> 
					<dt><label for="default_values">{L_DEFAULT_VALUE}</label><br /><span>{L_DEFAULT_VALUE_EXPLAIN}</span></dt>
					<dd>
						<label><textarea type="text" class="post" rows="2" cols="30" name="default_values" id="default_values" style="width:50%">{field_edit.DEFAULT_VALUES}</textarea></label>
					</dd>
				</dl>
				<dl> 
					<dt><label for="regex_type1">{L_REGEX}</label><br /><span>{L_REGEX_EXPLAIN}</span></dt>
					<dd>
						<label><input type="radio" name="regex_type" id="regex_type1" value="0" {field_edit.REGEX1_CHECKED}{field_edit.DISABLED} /> {L_PREDEF_REGEXP}</label>
						<label>
							<select name="regex1" id="regex1" onclick="document.getElementById('regex_type1').checked = true;"{field_edit.DISABLED}>
								# START field_edit.regex #
								{field_edit.regex.REGEX}
								# END field_edit.regex #
							</select>
						</label>
						<br /> 
						<label><input type="radio" name="regex_type" id="regex_type2" {field_edit.REGEX2_CHECKED}{field_edit.DISABLED} value="1" /> {L_PERSO_REGEXP}</label>
						<label><input type="text" size="40" id="regex2" name="regex2" value="{field_edit.REGEX}" class="text" onclick="document.getElementById('regex_type2').checked = true;"{field_edit.DISABLED} /></label>							
					</dd>
				</dl>
			</fieldset>
			
			<fieldset class="fieldset_submit">
				<legend>{L_UPDATE}</legend>
				<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
				<input type="reset" value="{L_RESET}" class="reset" />					
			</fieldset>
		</form>
		# END field_edit #
	</div>
	