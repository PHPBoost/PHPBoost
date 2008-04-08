		# IF C_DISPLAY_ARTICLE #
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div style="float:left">
					<strong>{NAME}</strong>
				</div>
				<div style="float:right">
					{COM} {EDIT} {DEL}
				</div>
			</div>
			<div class="module_contents">
				# IF PAGINATION_ARTICLES #
				<p style="text-align:center">{PAGINATION_ARTICLES}</p>
				# ENDIF #
				
				{CONTENTS}
				
				# IF PAGINATION_ARTICLES #
				<p style="text-align:center">{PAGINATION_ARTICLES}</p>
				# ENDIF #
				<div class="spacer"></div>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<span style="float:left" class="text_small">
					{L_NOTE}: {NOTE}
				</span>
				<span style="float:right" class="text_small">
					{L_WRITTEN}: <a href="../member/member{U_MEMBER_ID}">{PSEUDO}</a>, {L_ON}: {DATE}
				</span>
				<div class="spacer"></div>
			</div>
		</div>
		<br /><br />
		# INCLUDE handle_com #
		
		# ENDIF #


		# IF C_DISPLAY_ARTICLE_NOTE #
		<form action="../articles/articles{U_ARTICLE_ACTION_NOTE}" method="post" class="fieldset_content">
			<span id="note"></span>
			<fieldset>
				<legend>{L_NOTE}</legend>
				<dl>
					<dt><label for="note_select">{L_NOTE}</label></dt>
					<dd>
						<span class="text_small">{L_ACTUAL_NOTE}: {NOTE}</span>	
						<label>
							<select id="note_select" name="note">
								{SELECT}
							</select>
						</label>
					</dd>					
				</dl>
			</fieldset>
			<fieldset class="fieldset_submit">
				<legend>{L_VOTE}</legend>
				<input type="submit" name="valid_note" value="{L_VOTE}" class="submit" />
			</fieldset>
		</form>
		# ENDIF #
		