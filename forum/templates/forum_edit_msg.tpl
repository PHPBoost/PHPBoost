		# INCLUDE forum_top #
		
		<script type='text/javascript'>
		<!--
		function check_form_post(){
			# IF C_BBCODE_TINYMCE_MODE #
				tinyMCE.triggerSave();
			# ENDIF #
			
			if(document.getElementById('contents').value == "") {
				alert("{L_REQUIRE_TEXT}");
				return false;
			}
		}
		-->
		</script>
		
		<div class="module-position">
			<div class="module-top-l"></div>
			<div class="module-top-r"></div>
			<div class="module-top"><a href="index.php{SID}">{L_FORUM_INDEX}</a> &raquo; {U_FORUM_CAT} &raquo; {U_TITLE_T} <span style="font-weight:normal"><em>{DESC}</em></span></div>
			<div class="module-contents">
				<form action="{U_ACTION}" method="post" onsubmit="return check_form_post();">
					# INCLUDE message_helper #

					# IF C_FORUM_PREVIEW_MSG #
					<div class="module-position">
						<div class="module-top-l"></div>
						<div class="module-top-r"></div>
						<div class="module-top">
							<span style="float:left;">{L_PREVIEW}</span>
							<span style="float:right;"></span>&nbsp;
						</div>
					</div>
					<div class="msg-position">
						<div class="msg-container">
							<div class="msg-pseudo-mbr"></div>
							<div class="msg-top-row">
								<div style="float:left;"><i class="fa fa-hand-o-right"></i> {DATE}</div>
								<div style="float:right;"><i class="fa fa-quote-right"></i></div>
							</div>
							<div class="msg-contents-container">
								<div class="msg-info-mbr">
								</div>
								<div class="msg-contents">
									<div class="msg-contents-overflow">
										{CONTENTS}
									</div>
								</div>
							</div>
						</div>
						<div class="msg-sign">
							<hr />
							<span style="float:left;">
								<span class="basic-button smaller">MP</span>
							</span>
							<span style="float:right;font-size:10px;">
							</span>&nbsp;
						</div>
					</div>
					<div class="msg-position">
						<div class="msg-bottom-l"></div>
						<div class="msg-bottom-r"></div>
						<div class="msg-bottom">&nbsp;</div>
					</div>
					<br /><br />
					# ENDIF #
					
					<div class="fieldset-content">
						<p class="center">{L_REQUIRE}</p>
						<fieldset>
							<legend>{L_EDIT_MESSAGE}</legend>
							<div class="form-element-textarea">
								<label for="contents">* {L_MESSAGE}</label>
								{KERNEL_EDITOR}
								<textarea rows="25" cols="66" id="contents" name="contents">{CONTENTS}</textarea>
							</div>
						</fieldset>
						
						<fieldset class="fieldset-submit">
						<legend>{L_SUBMIT}</legend>
							<input type="hidden" name="p_update" value="{P_UPDATE}">
							<button type="submit" name="edit_msg" value="true" class="submit">{L_SUBMIT}</button>
							<button onclick="XMLHttpRequest_preview();" type="button">{L_PREVIEW}</button>
							<button type="reset" value="true">{L_RESET}</button>
						</fieldset>
					</div>		
				</form>
			</div>
			<div class="module-bottom-l"></div>
			<div class="module-bottom-r"></div>
			<div class="module-bottom"><a href="index.php{SID}">{L_FORUM_INDEX}</a> &raquo; {U_FORUM_CAT} &raquo; {U_TITLE_T} <span style="font-weight:normal"><em>{DESC}</em></span></div>
		</div>
		
		# INCLUDE forum_bottom #
		