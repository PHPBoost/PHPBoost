# INCLUDE ERROR_MESSAGE #
# INCLUDE LOGIN_FORM #
<div style="text-align:center;">
	# IF C_REGISTRATION_ENABLED # 
	<a href="{U_REGISTER}"><i class="fa fa-ticket"></i> {@registration}</a><br />
	# ENDIF #
	<a href="{U_FORGET_PASSWORD}"><i class="fa fa-question-circle"></i> {L_FORGET_PASSWORD}</a>
</div>