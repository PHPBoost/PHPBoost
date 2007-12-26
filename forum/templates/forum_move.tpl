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

		<div class="forum_title">{L_MOVE_SUBJECT} :: {TITLE}</div>
		<div class="module_position">
			<div class="row2"><a href="index.php{SID}">{L_FORUM_INDEX}</a> &raquo; {U_FORUM_CAT} &raquo; {TOPIC_LINK}</div>
		</div>

		<form method="post" action="move.php{SID}" onsubmit="javascript:return check_form_move();" class="fieldset_content">
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
