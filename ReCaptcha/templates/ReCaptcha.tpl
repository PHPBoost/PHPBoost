<div id="recaptcha-widget" style="display:none">
	<div id="recaptcha_response_field" style="display:none;"></div>
	<div id="recaptcha-container">
		<div id="recaptcha_image"></div>
		<div class="recaptcha_only_if_incorrect_sol" style="color:red">{@incorrect_sol}</div>
		<input type="text" id="{HTML_ID}" name="{HTML_ID}" placeholder="{@type_the_answer_here}"/>
	</div>
	<div class="options">
		<a href="javascript:Recaptcha.reload()" title="{@refresh_captcha}" class="fa fa-refresh"></a>
		<div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')" title="{@audio_captcha}" class="fa fa-volume-up"></a></div>
		<div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')" title="{@image_captcha}" class="fa fa-picture-o"></a></div>
		<div><a href="javascript:Recaptcha.showhelp()" title="{@captcha_help}" class="fa fa-question-circle"></a></div>
	</div>
</div>

<script>
var RecaptchaOptions = {
	theme : 'custom',
	lang : '{LANG}',
	custom_theme_widget: 'recaptcha-widget'
};
</script>
<script src="http://www.google.com/recaptcha/api/challenge?k={PUBLIC_KEY}"></script>