# INCLUDE FORUM_TOP #

<script>
	function check_form_move(){
		if(document.getElementById('to').value == "") {
			alert("{@warning.subcat}");
			return false;
		}
		return true;
	}
</script>

<article itemscope="itemscope" itemtype="https://schema.org/Creativework" id="article-forum-move" class="forum-content">
	<header>
		<h2><a class="offload" href="{U_CATEGORY}">{CATEGORY_NAME}</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i> <a class="offload" href="{U_TITLE_T}">{TITLE_T}</a></h2>
	</header>

	<div class="content">
		<form method="post" action="move.php" onsubmit="javascript:return check_form_move();" class="fieldset-content">
			<fieldset>
				<div class="form-element">
					<label for="to">{@category.category}</label>
					<div class="form-field">
					   <select id="to" name="to">
							{CATEGORIES}
						</select>
					</div>
				</div>
			</fieldset>

			<fieldset class="fieldset-submit">
				<legend>{@form.submit}</legend>
				<input type="hidden" name="id" value="{ID}">
				<input type="hidden" name="token" value="{TOKEN}">
				<button type="submit" name="submit" value="true" class="button submit">{@form.submit}</button>
			</fieldset>
		</form>
	</div>
	<footer><a class="offload" href="{U_CATEGORY}">{CATEGORY_NAME}</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i> <a class="offload" href="{U_TITLE_T}">{TITLE_T}</a></footer>
</article>

# INCLUDE FORUM_BOTTOM #
