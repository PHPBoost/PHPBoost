# IF C_GOT_ERROR #
<div class="warning">${i18n('generation_failed')}</div>
# ELSE #
<div class="success">${i18n('generation_succeeded')}</div>
# ENDIF #
<br />
<div style="text-align:center;">
	<input type="button" class="submit" value="${i18n('try_again')}" onclick="window.location = '{GENERATE}'" />
</div>