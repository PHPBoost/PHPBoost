		# INCLUDE forum_top #
		
		<script>
		<!--
		function check_form_post(){
			if(document.getElementById('contents').value == "") {
				alert("{L_REQUIRE_TEXT}");
				return false;
			}
		}
		-->
		</script>
		
		<article itemscope="itemscope" itemtype="http://schema.org/Creativework" id="article-forum-edit-message">
			<header>
				<h2>
					&bull; <a href="index.php" title="{L_FORUM_INDEX}">{L_FORUM_INDEX}</a> &raquo; {U_FORUM_CAT} &raquo; {U_TITLE_T} <span><em>{DESC}</em></span>
				</h2>
			</header>
			<div class="content">
				<form action="{U_ACTION}" method="post" onsubmit="return check_form_post();">
					# INCLUDE message_helper #

					# IF C_FORUM_PREVIEW_MSG #
					<article id="article-forum-edit-message-preview">
						<header>
							<h3>
								<span class="float-left">{L_PREVIEW}</span>
							</h3>
						</header>
						<div class="msg-position">
							<div class="msg-container">
								<div class="msg-pseudo-mbr"></div>
								<div class="msg-top-row">
									<div class="float-left"><i class="fa fa-hand-o-right"></i> {DATE}</div>
									<div class="float-right"><i class="fa fa-quote-right"></i></div>
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
								<span class="float-left">
									<span class="basic-button smaller">MP</span>
								</span>
								<span class="float-right">
								</span>&nbsp;
							</div>
						</div>
						<footer>
							&nbsp;
						</footer>
					</article>
					# ENDIF #
					
					<div class="fieldset-content">
						<p class="center">{L_REQUIRE}</p>
						<fieldset>
							<legend>{L_EDIT_MESSAGE}</legend>
							<div class="form-element-textarea">
								<label for="contents">* {L_MESSAGE}</label>
								{KERNEL_EDITOR}
								<div class="form-field-textarea">
									<textarea rows="25" cols="66" id="contents" name="contents">{CONTENTS}</textarea>
								</div>
							</div>
						</fieldset>
						
						<fieldset class="fieldset-submit">
						<legend>{L_SUBMIT}</legend>
							<input type="hidden" name="p_update" value="{P_UPDATE}">
							<input type="hidden" name="token" value="{TOKEN}">
							<button type="submit" name="edit_msg" value="true" class="submit">{L_SUBMIT}</button>
							<button onclick="XMLHttpRequest_preview();" type="button">{L_PREVIEW}</button>
							<button type="reset" value="true">{L_RESET}</button>
						</fieldset>
					</div>
				</form>
			</div>
			<footer>
				&bull; <a href="index.php" title="{L_FORUM_INDEX}">{L_FORUM_INDEX}</a> &raquo; {U_FORUM_CAT} &raquo; {U_TITLE_T} <span><em>{DESC}</em></span>
			</footer>
		</article>
		
		# INCLUDE forum_bottom #
		