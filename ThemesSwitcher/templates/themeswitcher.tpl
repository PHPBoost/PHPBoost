<div# IF NOT C_VERTICAL # class="theme_horizontal"# ENDIF #>
	<form action="" method="get">
		<p style="padding-top: 5px;">
			<select name="switchtheme" onchange="document.location = '?switchtheme=' + this.options[this.selectedIndex].value;">
			# START themes #
				<option value="{themes.IDNAME}"{themes.SELECTED}>{themes.NAME}</option>
			# END themes #
			</select>
		</p>
		<a href="?switchtheme={DEFAULT_THEME}">{@defaut_theme}</a>
	</form>
</div>
