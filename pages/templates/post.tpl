		<script type="text/javascript">
		<!--
			var path = '{PICTURES_DATA_PATH}';
			var selected_cat = {SELECTED_CAT};
			function check_form_post(){
				# IF C_BBCODE_TINYMCE_MODE #
					tinyMCE.triggerSave();
				# ENDIF #
			
				if(document.getElementById('title') && document.getElementById('title').value == "") {
					alert("{L_ALERT_TITLE}");
					return false;
				}
				if(document.getElementById('contents').value == "") {
					alert("{L_ALERT_CONTENTS}");
					return false;
				}
				return true;
			}
			var disabled = {OWN_AUTH_DISABLED};
			function disable_own_auth()
			{
				if( disabled )
				{
					disabled = false;
					document.getElementById("own_auth_display").style.display = 'block';
				}
				else
				{
					document.getElementById("own_auth_display").style.display = 'none';
					disabled = true;
				}
			}
		-->
		</script>

		<script type="text/javascript" src="{PICTURES_DATA_PATH}/images/pages.js"></script>
	
		# IF C_ERROR_HANDLER #
			<div class="{ERRORH_CLASS}">
				<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
				<br />
			</div>
			<br />
		# ENDIF #
		
		# START previewing #
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">{L_PREVIEWING} {previewing.TITLE}</div>
			<div class="module_contents">{previewing.PREVIEWING}</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
		</div>
		# END previewing #
			
		<form action="{TARGET}" method="post"  onsubmit="return check_form_post();" class="fieldset_content">					
			<fieldset>
				<legend>{L_TITLE_POST}</legend>
				# START create #
				<dl>
					<dt><label for="title">{L_TITLE_FIELD}</label></dt>
					<dd><label><input type="text" class="text" id="title" name="title" size="70" maxlength="250" value="{PAGE_TITLE}" /></label></dd>					
				</dl>
				# END create #
				<br />
				<label for="contents">{L_CONTENTS}</label>
				<br />
				<br />
				{KERNEL_EDITOR}
				<label><textarea rows="25" cols="66" id="contents" name="contents">{CONTENTS}</textarea></label>
				<br />
			</fieldset>	
			
			<fieldset>
				<legend>{L_PATH}</legend>
				<dl>
					<dt><label for="is_cat">{L_IS_CAT}</label></dt>
					<dd><label><input type="checkbox" name="is_cat" id="is_cat" {CHECK_IS_CAT} /></label></dd>					
				</dl>
				<dl>
					<dt><label>{L_CAT}</label></dt>
					<dd>
						<input type="hidden" name="id_cat" id="id_cat" value="{ID_CAT}"/>
						<span style="padding-left:17px;"><a href="javascript:select_cat(0);"><img src="{PICTURES_DATA_PATH}/images/cat_root.png" alt="" /> <span id="class_0" class="{CAT_0}">{L_ROOT}</span></a></span>
						<br />
						<ul style="margin:0;padding:0;list-style-type:none;line-height:normal;">
						{CAT_LIST}
						</ul>
					</dd>					
				</dl>
			</fieldset>
			
			<fieldset>
				<legend>{L_PROPERTIES}</legend>
				<dl>
					<dt><label for="count_hits">{L_COUNT_HITS}</label></dt>
					<dd><label><input type="checkbox" id="count_hits" name="count_hits" {COUNT_HITS_CHECKED} /></label></dd>					
				</dl>
				<dl>
					<dt><label for="activ_com">{L_ACTIV_COM}</label></dt>
					<dd><label><input type="checkbox" id="activ_com" name="activ_com" {ACTIV_COM_CHECKED} /></label></dd>					
				</dl>
				<dl>
					<dt><label for="activ_com">{L_DISPLAY_PRINT_LINK}</label></dt>
					<dd><label><input type="checkbox" id="display_print_link" name="display_print_link" {DISPLAY_PRINT_LINK_CHECKED} /></label></dd>					
				</dl>
			</fieldset>
			
			<fieldset>
				<legend>{L_AUTH}</legend>
				<dl>
					<dt><label for="own_auth">{L_OWN_AUTH}</label></dt>
					<dd><label><input type="checkbox" name="own_auth" id="own_auth" onclick="disable_own_auth();" {OWN_AUTH_CHECKED} /></label></dd>					
				</dl>
				<span id="own_auth_display" style="{DISPLAY}">
					<dl>
						<dt><label>{L_READ_PAGE}</label></dt>
						<dd><label>{SELECT_READ_PAGE}</label></dd>					
					</dl>
					<dl>
						<dt><label>{L_EDIT_PAGE}</label></dt>
						<dd><label>{SELECT_EDIT_PAGE}</label></dd>					
					</dl>
					<dl>
						<dt><label>{L_READ_COM}</label></dt>
						<dd><label>{SELECT_READ_COM}</label></dd>					
					</dl>
				</span>
			</fieldset>
			
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				<input type="hidden" name="id_edit" value="{ID_EDIT}" />
				<input type="submit" class="submit" value="{L_SUBMIT}" />
				<input type="submit" class="submit" value="{L_PREVIEW}" name="preview" />
				<input value="{L_RESET}" class="reset" type="reset" />
			</fieldset>
		</form>