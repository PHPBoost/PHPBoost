<style>
<!--
.fieldset-content {
	margin-left: auto;
	margin-right: auto;
	width: 400px;
}
-->
</style>
# INCLUDE ERROR_MESSAGE #
# INCLUDE LOGIN_FORM #
<div style="text-align:center;">
	# IF C_REGISTRATION_ENABLED # 
	<a href="{U_REGISTER}"><i class="icon-ticket"></i> {@registration}</a><br />
	# ENDIF #
	<a href="{U_FORGET_PASSWORD}"><i class="icon-question-circle"></i> {L_FORGET_PASSWORD}</a>
</div>