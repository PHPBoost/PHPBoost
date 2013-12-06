# IF C_GOT_ERROR #
<div id="id-message-helper" class="message-helper warning">
	<i class="icon-warning"></i>
	<div class="message-helper-content">${i18nraw('generation_failed')}</div>
</div>
# ELSE #
<div id="id-message-helper" class="message-helper success">
	<i class="icon-success"></i>
	<div class="message-helper-content">${i18nraw('generation_succeeded')}</div>
</div>
# ENDIF #
<br />
<div style="text-align:center;">
	<button type="button" onclick="window.location = '{GENERATE}'">{@try_again}</button>
</div>