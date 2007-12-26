		# START article #
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div style="float:left">
					{article.NAME} 
				</div>
				<div style="float:right">
					{article.COM} {EDIT} {DEL}
				</div>
			</div>
			<div class="module_contents">
				{article.PAGINATION}
				{article.CONTENTS}
				{article.PAGINATION}
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<div style="float:left" class="text_small">
					{article.L_NOTE}: {article.NOTE}
				</div>
				<div style="float:right" class="text_small">
					{article.L_WRITTEN}: <a href="../member/member{article.U_MEMBER_ID}">{article.PSEUDO}</a>, {article.L_ON}: {article.DATE}
				</div>
			</div>
		</div>
		
		<br /><br />
		{HANDLE_COM}
		
		# END article #


		# START note #
		<form action="../articles/articles{note.U_ARTICLE_ACTION_NOTE}" method="post" class="fieldset_content">
			<span id="note"></span>
			<fieldset>
				<legend>{L_NOTE}</legend>
				<dl>
					<dt><label for="note_select">{L_NOTE}</label></dt>
					<dd>
						<span class="text_small">{L_ACTUAL_NOTE}: {note.NOTE}</span>	
						<label>
							<select id="note_select" name="note">
								{note.SELECT}
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
		# END note #
		