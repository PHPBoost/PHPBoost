		# INCLUDE forum_top #

		<script>
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

		<article itemscope="itemscope" itemtype="http://schema.org/Creativework" id="article-forum-move" class="forum-contents">
			<header>
				<h2><a href="{U_FORUM_CAT}">{FORUM_CAT}</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i> <a href="{U_TITLE_T}">{TITLE_T}</a></h2>
			</header>

			<div class="content">
				<form method="post" action="move.php" onsubmit="javascript:return check_form_move();" class="fieldset-content">
					<fieldset>
						<legend>{L_MOVE_SUBJECT} : {TITLE}</legend>
						<div class="form-element">
							<label for="to">{L_CAT}</label>
							<div class="form-field">
							   <select id="to" name="to">
									{CATEGORIES}
								</select>
							</div>
						</div>
					</fieldset>

					<fieldset class="fieldset-submit">
						<legend>{L_SUBMIT}</legend>
						<input type="hidden" name="id" value="{ID}">
						<input type="hidden" name="token" value="{TOKEN}">
						<button type="submit" name="submit" value="true" class="button submit">{L_SUBMIT}</button>
					</fieldset>
				</form>
			</div>
			<footer><a href="{U_FORUM_CAT}">{FORUM_CAT}</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i> <a href="{U_TITLE_T}">{TITLE_T}</a></footer>
		</article>

		# INCLUDE forum_bottom #
