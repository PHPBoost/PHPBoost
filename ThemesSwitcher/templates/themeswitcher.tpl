# IF C_VERTICAL #
	<div class="module-mini-container">
		<div class="module-mini-top">
			<h5 class="sub-title">{L_SWITCH_THEME}</h5>
		</div>
		<form action="" method="get">
			<div class="module-mini-contents">
				<p style="padding-top: 5px;">
					<select name="switchtheme" onchange="document.location = '?switchtheme=' + this.options[this.selectedIndex].value;">
						# START themes #
							<option value="{themes.IDNAME}"{themes.SELECTED}>{themes.NAME}</option>
						# END themes #
					</select>
					<input style="display: none;" value="{L_SUBMIT}" name="valid" id="switchtheme_valid" type="submit">
					<script>
					<!--
					document.getElementById('switchtheme_valid').style.display = 'none';
					-->
					</script>
				</p>
				<a href="?token={TOKEN}&amp;switchtheme={DEFAULT_THEME}">{L_DEFAULT_THEME}</a>
			</div>
		</form>
		<div class="module-mini-bottom">
		</div>
	</div>
# ELSE #
	<div class="theme_horizontal">
		<form action="" method="get">
			<select name="switchtheme" onchange="document.location = '?switchtheme=' + this.options[this.selectedIndex].value;">
					# START themes #
						<option value="{themes.IDNAME}"{themes.SELECTED}>{themes.NAME}</option>
					# END themes #
				</select>
				<input style="display: none;" value="{L_SUBMIT}" name="valid" id="switchtheme_valid" type="submit">
				<script>
				<!--
				document.getElementById('switchtheme_valid').style.display = 'none';
				-->
				</script>
			<a href="?token={TOKEN}&amp;switchtheme={DEFAULT_THEME}">{L_DEFAULT_THEME}</a>
		</form>
	</div>
# ENDIF #