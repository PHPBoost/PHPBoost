# IF C_VERTICAL #
	<div class="module-mini-container">
		<div class="module-mini-top">
			<h5 class="sub-title">{L_SWITCH_LANG}</h5>
		</div>
		<form action="" method="get">
			<div class="module-mini-contents">
				<p style="padding-top: 5px;">
						<select name="switchlang" onchange="document.location = '?switchlang=' + this.options[this.selectedIndex].value;">
							# START langs #
								<option value="{langs.IDNAME}"{langs.SELECTED}>{langs.NAME}</option>
							# END langs #
						</select>
						<img src="{IMG_LANG_IDENTIFIER}" alt="" class="valign-middle" />
 
						<input style="display: none;" value="{L_SUBMIT}" name="valid" id="switchlang_valid" type="submit">
					<script>
					<!--
					document.getElementById('switchlang_valid').style.display = 'none';
					-->
					</script>
				</p>
				<a href="?token={TOKEN}&amp;switchlang={DEFAULT_LANG}">{L_DEFAULT_LANG}</a>
			</div>
		</form>
		<div class="module-mini-bottom">
		</div>
	</div>
# ELSE #
	<div class="lang_horizontal">
		<form action="" method="get">
			<select name="switchlang" onchange="document.location = '?switchlang=' + this.options[this.selectedIndex].value;">
				# START langs #
					<option value="{langs.IDNAME}"{langs.SELECTED}>{langs.NAME}</option>
				# END langs #
			</select>
			<img src="{IMG_LANG_IDENTIFIER}" alt="" class="valign-middle" />
			<input style="display: none;" value="{L_SUBMIT}" name="valid" id="switchlang_valid" type="submit">
				<script>
					<!--
					document.getElementById('switchlang_valid').style.display = 'none';
					-->
				</script>
			<a href="?token={TOKEN}&amp;switchlang={DEFAULT_LANG}">{L_DEFAULT_LANG}</a>
		</form>
	</div>
# ENDIF #