<script type="text/javascript">
 var RecaptchaOptions = {
    theme : 'custom',
    lang : '{LANG}',
    custom_theme_widget: 'recaptcha_widget'
 };
 </script>

<div id="recaptcha_widget" style="display:none">

	
	<div id="recaptcha_container">
		<div id="recaptcha_image"></div>
	
		<div class="recaptcha_only_if_incorrect_sol" style="color:red">{@incorrect_sol}</div>

		<span class="recaptcha_only_if_image">{@input_text}</span>
		<span class="recaptcha_only_if_audio">{@input_numbers}</span>

		<input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />
	</div>
	<div class="options">
		<a href="javascript:Recaptcha.reload()"><i class="icon-refresh"></i></a>
		<div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')" title="{@audio_captcha}"><i class="icon-volume-up"></i></a></div>
		<div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')" title="{@image_captcha}"><i class="icon-picture-o"></i></a></div>
		<div><a href="javascript:Recaptcha.showhelp()" title="{@captcha_help}"><i class="icon-question-circle"></i></a></div>
	</div>
	
</div>

<script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k={PUBLIC_KEY}"></script>