# IF C_GOT_ERROR #
<div class="message-helper warning">
	<i class="fa fa-warning"></i>
	<div class="message-helper-content">${i18nraw('generation_failed')}</div>
</div>
# ELSE #
<div class="message-helper success">
	<i class="fa fa-success"></i>
	<div class="message-helper-content">${i18nraw('generation_succeeded')}</div>
</div>
# ENDIF #
<br />
<div style="text-align:center;">
	<button type="button" onclick="window.location = '{GENERATE}'">{@try_again}</button>
</div>