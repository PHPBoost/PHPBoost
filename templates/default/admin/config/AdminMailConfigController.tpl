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
	Effect.Appear("mail_sending_config_smtp_configuration_fieldset");
	HTMLForms.getField("mail_sending_config_smtp_host").enable();
	HTMLForms.getField("mail_sending_config_smtp_port").enable();
	HTMLForms.getField("mail_sending_config_smtp_login").enable();
	HTMLForms.getField("mail_sending_config_smtp_password").enable();
	HTMLForms.getField("mail_sending_config_secure_protocol").enable();
}

function hide_smtp_config()
{
	Effect.Fade("mail_sending_config_smtp_configuration_fieldset");
	HTMLForms.getField("mail_sending_config_smtp_host").disable();
	HTMLForms.getField("mail_sending_config_smtp_port").disable();
	HTMLForms.getField("mail_sending_config_smtp_login").disable();
	HTMLForms.getField("mail_sending_config_smtp_password").disable();
	HTMLForms.getField("mail_sending_config_secure_protocol").disable();
}
-->
</script>

# INCLUDE SMTP_FORM #