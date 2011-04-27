# IF C_GOT_ERROR #
<div class="warning">${i18nraw('generation_failed')}</div>
# ELSE #
<div class="success">${i18nraw('generation_succeeded')}</div>
# ENDIF #
<br />
<div style="text-align:center;">
	<input type="button" class="submit" value="{@try_again}" onclick="window.location = '{GENERATE}'" />
</div>