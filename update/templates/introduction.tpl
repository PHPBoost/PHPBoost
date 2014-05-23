<span style="float:right;padding:8px;padding-top:0px;padding-right:25px">
	<img src="templates/images/PHPBoost_box.png" alt="Logo PHPBoost" />
</span>
<div style="width:520px">
	<h1>{@step.introduction.message}</h1>
	<span class="spacer">&nbsp;</span>
	{@H|step.introduction.explanation}
	# IF C_PUT_UNDER_MAINTENANCE #{@H|step.introduction.maintenance_notice}# ENDIF #
	{@H|step.introduction.team_signature}
</div>
<div style="margin-bottom:60px;">&nbsp;</div>

# INCLUDE SERVER_FORM #