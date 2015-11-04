<div# IF NOT C_VERTICAL # class="lang_horizontal"# ENDIF #>
	<form action="" method="get">
			<p style="padding-top: 5px;">
				<select name="switchlang" onchange="document.location = '?switchlang=' + this.options[this.selectedIndex].value;">
				# START langs #
					<option value="{langs.IDNAME}"{langs.SELECTED}>{langs.NAME}</option>
				# END langs #
				</select>
				<img src="{IMG_LANG_IDENTIFIER}" alt="" class="valign-middle" />
			</p>
			<a href="?switchlang={DEFAULT_LANG}">{@default_lang}</a>
	</form>
</div>
