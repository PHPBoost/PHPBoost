#{resources('install/install')}
<h1>${setvars(i18n('step.welcome.distribution'), array('distribution' => DISTRIBUTION))}</h1>
${i18nraw('step.welcome.explanation')}
<br />
${DISTRIBUTION_DESCRIPTION}