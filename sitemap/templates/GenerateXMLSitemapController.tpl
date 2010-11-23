# IF C_GOT_ERROR #
<div class="warning">{@generation_failed}</div>
# ELSE #
<div class="success">{@generation_succeeded}</div>
# ENDIF #
<br />
<div style="text-align:center;">
	<input type="button" class="submit" value="{@try_again}" onclick="window.location = '{GENERATE}'" />
</div>