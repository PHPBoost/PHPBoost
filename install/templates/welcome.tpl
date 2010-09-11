${resources('install/install')}
<h1>${StringVars::replace_vars(i18n('step.welcome.distribution'), array('distribution' => DISTRIBUTION))}</h1>
${i18n('step.welcome.explanation')}
<br />
${DISTRIBUTION_DESCRIPTION}