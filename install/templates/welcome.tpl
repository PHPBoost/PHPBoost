<span style="float:right;padding:8px;padding-top:0px;padding-right:25px">
	<img src="templates/images/PHPBoost_box3.0.png" alt="Logo PHPBoost" />
</span>
<h1>{@step.welcome.message}</h1>
{@H|step.welcome.explanation}
<div style="margin-bottom:60px;">&nbsp;</div>
<h1>${set(@step.welcome.distribution, ['distribution': @distribution.name])}</h1>
${html(@step.welcome.distribution.explanation)}
<br />
${html(@distribution.description)}
												
# INCLUDE LICENSE_FORM #