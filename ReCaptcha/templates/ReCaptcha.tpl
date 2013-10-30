<script type="text/javascript">
 var RecaptchaOptions = {
    theme : 'custom',
    lang : '{LANG}',
    custom_theme_widget: 'recaptcha_widget'
 };
 </script>

<div id="recaptcha_widget" style="display:none">

	<div id="recaptcha_image"></div>
	<div class="recaptcha_only_if_incorrect_sol" style="color:red">Incorrect please try again</div>

	<span class="recaptcha_only_if_image">Enter the words above:</span>
	<span class="recaptcha_only_if_audio">Enter the numbers you hear:</span>

	<input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />

	<a href="javascript:Recaptcha.reload()"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/refresh.png" alt="" class="valign_middle" /></a>
  	<div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')">Get an audio CAPTCHA</a></div>
  	<div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')">Get an image CAPTCHA</a></div>

  	<div><a href="javascript:Recaptcha.showhelp()">Help</a></div>
</div>

<script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k={PUBLIC_KEY}"></script>