<style>
<!--
.fieldset_content {
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
	<a href="{U_REGISTER}"><img src="{PATH_TO_ROOT}/templates/base/images/register_mini.png" alt="" class="valign_middle" />{@registration}</a><br />
	# ENDIF #
	<a href="{U_FORGET_PASSWORD}"><img src="{PATH_TO_ROOT}/templates/base/images/forget_mini.png" alt="" class="valign_middle" />{L_FORGET_PASSWORD}</a>
</div>