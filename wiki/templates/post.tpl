		<script type="text/javascript">
		<!--
			var path = '{PICTURES_DATA_PATH}';
			var selected_cat = {SELECTED_CAT};
			function check_form_post(){
				if(document.getElementById('title').value == "") {
					alert("{L_ALERT_TITLE}");
					return false;
				}
				if(document.getElementById('contents').value == "") {
					alert("{L_ALERT_CONTENTS}");
					return false;
				}
				return true;
			}
		-->
		</script>
		<script type="text/javascript" src="{PICTURES_DATA_PATH}/images/templates/wiki.js"></script>

		# START preview #
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">{L_PREVIEWING}: {preview.TITLE}</div>
			<div class="module_contents" id="preview">
				# START preview.menu #
					<div class="row3" style="width:70%">
						<div style="text-align:center;"><strong>{L_TABLE_OF_CONTENTS}</strong></div>
						{preview.menu.MENU}
					</div>
				# END preview.menu #
				<br /><br />
				{preview.CONTENTS}
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
		</div>
		# END preview #
		# IF C_ERROR_HANDLER #
		<span id="errorh"></span>
		<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
			<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
		</div>
		# ENDIF #
		
		<form action="{TARGET}" method="post" onsubmit="return check_form_post();" class="fieldset_content">					
			<fieldset>
				<legend>{TITLE}</legend>
				# START create #
				<dl>
					<dt><label for="title">{L_TITLE_FIELD}</label></dt>
					<dd><label><input type="text" class="text" id="title" name="title" size="70" maxlength="250" value="{ARTICLE_TITLE}" /></label></dd>					
				</dl>
				<dl>
					<dt><label for="selected_cat">{L_CURRENT_CAT}</label></dt>
					<dd>
						<input type="hidden" name="id_cat" id="id_cat" value="{ID_CAT}"/>
						<div id="selected_cat">{CURRENT_CAT}</div>
					</dd>					
				</dl>		
				<dl>
					<dt><label>{L_CAT}</label></dt>
					<dd>
						<span style="padding-left:17px;"><a href="javascript:select_cat(0);"><img src="{PICTURES_DATA_PATH}/images/cat_root.png" alt="" /> <span id="class_0" class="{CAT_0}">{L_DO_NOT_SELECT_ANY_CAT}</span></a></span>
						<br />
						<ul style="margin:0;padding:0;list-style-type:none;line-height:normal;">
						# START create.list #
							{create.list.DIRECTORY}
						# END create.list #
						{CAT_LIST}
						</ul>
					</dd>					
				</dl>
				# END create #	
				<br />
				<label for="contents">{L_CONTENTS}</label>
				# INCLUDE post_js_tools #
				{KERNEL_EDITOR}
				<label><textarea rows="25" cols="66" id="contents" name="contents">{CONTENTS}</textarea></label>
				<br />
			</fieldset>	
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				<input type="hidden" name="is_cat" value="{IS_CAT}" />
				<input type="hidden" name="id_edit" value="{ID_EDIT}" />
				<input type="hidden" name="token" value="{TOKEN}" />
				<input type="submit" class="submit" value="{L_SUBMIT}" />
				<input type="submit" value="{L_PREVIEW}" class="submit" name="preview" />
				<input value="{L_RESET}" class="reset" type="reset" />
			</fieldset>
		</form>
