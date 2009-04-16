		# INCLUDE forum_top #
		
		<script type='text/javascript'>
		<!--
		function check_form_move(){
			if(document.getElementById('to').value == "") {
				alert("{L_SELECT_SUBCAT}");
				return false;
		    }
				if(document.getElementById('contents').value == "") {
				alert("{L_REQUIRE_TEXT}");
				return false;
		    }
			if(document.getElementById('titre').value == "") {
				alert("{L_REQUIRE_TITLE}");
				return false;
		    }
			return true;
		}
		-->
		</script>
		
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">&bull; {U_FORUM_CAT} &raquo; {U_TITLE_T}</div>
			<div class="module_contents">			
				<form method="post" action="move{U_MOVE}" onsubmit="javascript:return check_form_move();" class="fieldset_content">
					<fieldset>
						<legend>{L_MOVE_SUBJECT} :: {TITLE}</legend>
						<dl>
							<dt><label for="to">{L_CAT}</label></dt>
							<dd><label>
							   <select id="to" name="to">
									{CATEGORIES}
								</select>
							</label></dd>
						</dl>
					</fieldset>	
					
					<fieldset class="fieldset_submit">
						<legend>{L_SUBMIT}</legend>
						<input type="hidden" name="id" value="{ID}" />
						<input type="submit" value="{L_SUBMIT}" class="submit" />			
					</fieldset>	
				</form>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">&bull; {U_FORUM_CAT} &raquo; {U_TITLE_T}</div>
		</div>
		
		# INCLUDE forum_bottom #
