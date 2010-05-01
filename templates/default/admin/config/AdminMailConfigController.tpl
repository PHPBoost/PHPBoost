# IF C_SUBMIT #
<div class="success" id="mail_config_saved_success">{L_MAIL_CONFIG_SAVED}</div>

<script type="text/javascript">
	<!--
	window.setTimeout(function() { Effect.Fade("mail_config_saved_success"); }, 5000);
	-->
</script>
# ENDIF #

<script type="text/javascript">
<!--
	function show_smtp_config()
	{
		HTMLForms.getFieldset("smtp_configuration").enable();
	}
	
	function hide_smtp_config()
	{
		HTMLForms.getFieldset("smtp_configuration").disable();
	}
-->
</script>

# INCLUDE SMTP_FORM #