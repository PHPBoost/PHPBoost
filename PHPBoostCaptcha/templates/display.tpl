<script>
<!--
function change_refresh_button()
{
	document.getElementById('refresh_button').className = 'fa fa-refresh';
}

function refresh_img()
{
	document.getElementById('refresh_button').className = 'fa fa-spinner fa-spin';
	
	if ( typeof this.img_id == 'undefined' )
		this.img_id = 0;
	else
		this.img_id++;
	
	document.getElementById('verif_code_img').src = '{PATH_TO_ROOT}/PHPBoostCaptcha/ajax/display_image.php?width={CAPTCHA_WIDTH}&difficulty={CAPTCHA_DIFFICULTY}&height={CAPTCHA_HEIGHT}&font={CAPTCHA_FONT}&new=' + img_id;
	
	setTimeout(function() {
		change_refresh_button();
	}, 1000);
}
-->
</script>
<img src="{PATH_TO_ROOT}/PHPBoostCaptcha/ajax/display_image.php?width={CAPTCHA_WIDTH}&amp;height={CAPTCHA_HEIGHT}&amp;difficulty={CAPTCHA_DIFFICULTY}&amp;font={CAPTCHA_FONT}" id="verif_code_img" alt="" />
<div class="spacer">&nbsp;</div>
<input type="text" name="{HTML_ID}" id="{HTML_ID}">
<a href="javascript:refresh_img()" id="refresh_button" title="${LangLoader::get_message('change_picture', 'common', 'PHPBoostCaptcha')}" class="fa fa-refresh"></a>