		<script type="text/javascript">
		<!--

		function check_msg(){
			if(document.getElementById('contents').value == "") {
				alert("{L_REQUIRE_TEXT}");
				return false;
		    }
			return true;
		}

		-->
		</script>

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_TERMS}</li>
				<li>
					<a href="admin_terms.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/terms.png" alt="" /></a>
					<br />
					<a href="admin_terms.php" class="quick_link">{L_TERMS}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">			
			<form action="admin_terms.php?token={TOKEN}" method="post" class="fieldset_content">
				<fieldset>
					<legend>{L_TERMS}</legend>
					<p>{L_EXPLAIN_TERMS}</p>
					<label for="contents">{L_CONTENTS}</label>
					{KERNEL_EDITOR}
					<label><textarea rows="20" cols="63" id="contents" name="contents">{CONTENTS}</textarea></label> 
				</fieldset>
				
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="msg_register" value="{L_UPDATE}" class="submit" />
					<script type="text/javascript">
					<!--				
					document.write('&nbsp;&nbsp;<input value="{L_PREVIEW}" onclick="XMLHttpRequest_preview();" type="button" class="submit" />');
					-->
					</script>
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />		
				</fieldset>	
			</form>
		</div>
		