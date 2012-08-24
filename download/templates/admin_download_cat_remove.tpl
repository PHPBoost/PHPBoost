		# INCLUDE admin_download_menu #
		
		<div id="admin_contents">
			<form action="{U_FORM_TARGET}" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend>{L_REMOVING_CATEGORY}</legend>
					<p>{L_EXPLAIN_REMOVING}</p>
					
					<label>
						<input type="radio" name="action" value="delete" /> {L_DELETE_CATEGORY_AND_CONTENT}
					</label>
					<br /> <br />
					<label>
						<input type="radio" name="action" value="move" checked="checked" /> {L_MOVE_CONTENT}
					</label>
					&nbsp;
					{CATEGORY_TREE}
				</fieldset>
				
				<fieldset class="fieldset_submit">
					<legend>{L_SUBMIT}</legend>
					<input type="hidden" name="cat_to_del" value="{IDCAT}" />
					<input type="submit" name="submit" value="{L_SUBMIT}" class="submit" />
					<input type="hidden" name="token" value="{TOKEN}" />	
				</fieldset>
			</form>
		</div>