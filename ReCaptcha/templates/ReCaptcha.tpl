<link rel="stylesheet" href="{PATH_TO_ROOT}/ReCaptcha/templates/ReCaptcha.css" type="text/css" media="screen" />

<div id="recaptcha_widget" style="display:none">
	<div id="recaptcha_response_field" style="display:none;"></div>
	<div id="recaptcha_container">
		<div id="recaptcha_image"></div>
		<div class="recaptcha_only_if_incorrect_sol" style="color:red">{@incorrect_sol}</div>
		<input type="text" id="{HTML_ID}" name="{HTML_ID}" placeholder="{@type_the_answer_here}"/>
	</div>
	<div class="options">
		<a href="javascript:Recaptcha.reload()"><i class="fa fa-refresh"></i></a>
		<div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')" title="{@audio_captcha}"><i class="fa fa-volume-up"></i></a></div>
		<div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')" title="{@image_captcha}"><i class="fa fa-picture-o"></i></a></div>
		<div><a href="javascript:Recaptcha.showhelp()" title="{@captcha_help}"><i class="fa fa-question-circle"></i></a></div>
	</div>
	
</div>

<script>
var RecaptchaOptions = {
	theme : 'custom',
	lang : '{LANG}',
	custom_theme_widget: 'recaptcha_widget'
};
</script>
<script src="http://www.google.com/recaptcha/api/challenge?k={PUBLIC_KEY}"></script>