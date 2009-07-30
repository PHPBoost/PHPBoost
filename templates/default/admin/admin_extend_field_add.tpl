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

	<div id="admin_quick_menu">
		<ul>
			<li class="title_menu">{L_EXTEND_FIELD}</li>
			<li>
				<a href="admin_extend_field.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/extendfield.png" alt="" /></a>
				<br />
				<a href="admin_extend_field.php" class="quick_link">{L_EXTEND_FIELD_MANAGEMENT}</a>
			</li>
			<li>
				<a href="admin_extend_field_add.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/extendfield.png" alt="" /></a>
				<br />
				<a href="admin_extend_field_add.php" class="quick_link">{L_EXTEND_FIELD_ADD}</a>
			</li>
		</ul>
	</div>
	
	<div id="admin_contents">
		# IF C_ERROR_HANDLER #
		<div class="error_handler_position">
			<span id="errorh"></span>
			<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
				<br />	
			</div>
			<br />		
		</div>
		# ENDIF #
		
		<form action="admin_extend_field_add.php?token={TOKEN}" method="post" onsubmit="return check_form_field();" class="fieldset_content">
			<fieldset>
				<legend>{L_EXTEND_FIELD_ADD}</legend>
				<dl> 
					<dt><label for="name">* {L_NAME}</label></dt>
					<dd><label><input type="text" size="40" maxlength="100" id="name" name="name" class="text" /></label></dd>
				</dl>
				<dl> 
					<dt><label for="contents">{L_DESC}</label></dt>
					<dd><label><textarea class="post" rows="4" cols="30" name="contents" id="contents"></textarea></label></dd>
				</dl>
				<dl> 
					<dt><label>* {L_REQUIRED_FIELD}</label><br /><span>{L_REQUIRED_FIELD_EXPLAIN}</span></dt>
					<dd><label>
						<label><input type="radio" name="required" id="required_field1" value="1" /> {L_REQUIRED}</label>
						<label><input type="radio" name="required" id="required_field2" value="0" checked="checked" /> {L_NOT_REQUIRED}</label>
					</label></dd>
				</dl>
				<dl> 
					<dt><label for="field">* {L_TYPE}</label></dt>
					<dd><label>
						<select name="field" id="field" onchange="change_status(this.options[selectedIndex].value)">						
							<option value="1">{L_SHORT_TEXT}</option>
							<option value="2">{L_LONG_TEXT}</option>
							<option value="3">{L_SEL_UNIQ}</option>
							<option value="4">{L_SEL_MULT}</option>
							<option value="5">{L_CHECK_UNIQ}</option>
							<option value="6">{L_CHECK_MULT}</option>
						</select>
					</label></dd>
				</dl>
				<dl> 
					<dt><label for="possible_values">{L_POSSIBLE_VALUES}</label><br /><span>{L_POSSIBLE_VALUES_EXPLAIN}</span></dt>
					<dd><label><textarea class="post" rows="2" cols="30" name="possible_values" id="possible_values" style="width:50%;"></textarea> </label></dd>
				</dl>
				<dl> 
					<dt><label for="default_values">{L_DEFAULT_VALUE}</label><br /><span>{L_DEFAULT_VALUE_EXPLAIN}</span></dt>
					<dd>
						<label><textarea class="post" rows="2" cols="30" name="default_values" id="default_values" style="width:50%;"></textarea></label>
					</dd>
				</dl>
				<dl> 
					<dt><label for="regex_type1">{L_REGEX}</label><br /><span>{L_REGEX_EXPLAIN}</span></dt>
					<dd>
						<label><input type="radio" name="regex_type" id="regex_type1" value="0" checked="checked" /> {L_PREDEF_REGEXP}</label>
						<label>
							<select name="regex1" id="regex1" onclick="document.getElementById('regex_type1').checked = true;">
								<option value="0" selected="selected">--</option>
								<option value="1">{L_FIGURES}</option>
								<option value="2">{L_LETTERS}</option>
								<option value="3">{L_FIGURES_LETTERS}</option>
								<option value="4">{L_MAIL}</option>
								<option value="5">{L_WEBSITE}</option>
							</select>
						</label>
						<br /> 
						<label><input type="radio" name="regex_type" id="regex_type2" value="1" /> {L_PERSO_REGEXP}</label>
						<label><input type="text" size="40" id="regex2" name="regex2" class="text" onclick="document.getElementById('regex_type2').checked = true;" /></label>							
					</dd>
				</dl>
			</fieldset>
			
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				<input type="submit" name="valid" value="{L_SUBMIT}" class="submit" />
				<input type="reset" value="{L_RESET}" class="reset" />					
			</fieldset>
		</form>
	</div>
		