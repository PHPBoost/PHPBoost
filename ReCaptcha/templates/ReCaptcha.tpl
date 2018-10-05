<div id="recaptcha-widget-container">
	<div id="{HTML_ID}" class="g-recaptcha" data-sitekey="{SITE_KEY}"# IF C_INVISIBLE # data-callback="captchaSubmitCallback" data-size="invisible"# ENDIF #></div>
	# IF C_INVISIBLE #
	<script>
		var captchaOnloadCallback = function() {
			grecaptcha.render('{HTML_ID}', {
				'sitekey' : '{SITE_KEY}',
				'callback' : 'captchaSubmitCallback',
				'size' : 'invisible'
			});
		};
		
		jQuery('.submit').on('click', function (e) {
			e.preventDefault();
			grecaptcha.execute();
		});
		
		var captchaSubmitCallback = function(token) {
			jQuery("\#{FORM_ID}").append("<input type='hidden' name='{FORM_ID}_submit' value='true' />");
			jQuery("\#{FORM_ID}").submit();
		};
	</script>
	# ENDIF #
	<script src="//www.google.com/recaptcha/api.js# IF C_INVISIBLE #?onload=captchaOnloadCallback&render=explicit# ENDIF #" async defer></script>
</div>