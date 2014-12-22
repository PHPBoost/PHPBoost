		# INCLUDE forum_top #
		
		<script type='text/javascript'>
		<!--
		function check_form_move(){
			if(document.getElementById('to').value == "") {
				alert("{L_SELECT_SUBCAT}");
				return false;
			}
			return true;
		}
		-->
		</script>
		
		<div class="module-position">
			<div class="module-top-l"></div>
			<div class="module-top-r"></div>
			<div class="module-top">&bull; {U_FORUM_CAT} &raquo; {U_TITLE_T}</div>
			<div class="module-contents">
				<form method="post" action="move{U_MOVE}" onsubmit="javascript:return check_form_move();" class="fieldset-content">
					<fieldset>
						<legend>{L_MOVE_SUBJECT} : {TITLE}</legend>
						<div class="form-element">
							<label for="to">{L_CAT}</label>
							<div class="form-field"><label>
							   <select id="to" name="to">
									{CATEGORIES}
								</select>
							</label></div>
						</div>
					</fieldset>
					
					<fieldset class="fieldset-submit">
						<legend>{L_SUBMIT}</legend>
						<input type="hidden" name="id" value="{ID}">
						<button type="submit" name="submit" value="true" class="submit">{L_SUBMIT}</button>			
					</fieldset>	
				</form>
			</div>
			<div class="module-bottom-l"></div>		
			<div class="module-bottom-r"></div>
			<div class="module-bottom">&bull; {U_FORUM_CAT} &raquo; {U_TITLE_T}</div>
		</div>
		
		# INCLUDE forum_bottom #
