<span style="float:right;padding:8px;padding-top:0px;padding-right:25px">
	<img src="templates/images/PHPBoost_box3.0.png" alt="Logo PHPBoost" />
</span>
<h1>${i18n('step.welcome.message')}</h1>
${i18nraw('step.welcome.explanation')}
<div style="margin-bottom:60px;">&nbsp;</div>
<h1>${setvars(i18n('step.welcome.distribution'), array('distribution' => i18n('distribution.name')))}</h1>
${i18nraw('step.welcome.distribution.explanation')}
<br />
${i18nraw('distribution.description')}
												
<fieldset class="submit_case">
	<a href="{NEXT_STEP_URL}" title="${i18n('step.license.title')}" >
    	<img src="templates/images/right.png" alt="${i18n('step.license.title')}" class="valign_middle" />
    </a>
</fieldset>	