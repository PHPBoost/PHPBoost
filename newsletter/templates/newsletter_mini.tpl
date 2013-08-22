# IF C_VERTICAL #
<form action="{PATH_TO_ROOT}/newsletter/?url=/subscribe/" method="post">
	<div class="module_mini_container">
		<div class="module_mini_top">
			<h5 class="sub_title">{L_NEWSLETTER}</h5>
		</div>
		<div class="module_mini_contents">
			<p>
				<input type="text" name="mail_newsletter" maxlength="50" class="text" value="{USER_MAIL}">
			</p>
			<p>
				<label><input type="radio" name="subscribe" value="subscribe" checked="checked"> {SUBSCRIBE}</label>
				<br />
				<label><input type="radio" name="subscribe" value="unsubscribe"> {UNSUBSCRIBE}</label>
			</p>
			<p>
				<input type="hidden" name="token" value="{TOKEN}">
				<input type="submit" value="{L_SUBMIT}" class="submit">	
			</p>
			<p class="newsletter_link">
				<a href="${relative_url(NewsletterUrlBuilder::archives())}" class="small_link">{L_ARCHIVES}</a>
			</p>
		</div>
		<div class="module_mini_bottom">
		</div>
	</div>
</form>
# ELSE #
<div id="newsletter">
	<form action="{PATH_TO_ROOT}/newsletter/?url=/subscribe/" method="post">
		<div class="newsletter_form">
			<span class="newsletter_title">{L_NEWSLETTER}</span> 
			<input type="text" name="mail_newsletter" maxlength="50" class="text newsletter_text" value="{USER_MAIL}">
			<input type="submit" class="newsletter_img" value="">
			<input type="hidden" name="subscribe" value="subscribe">
		</div>
	</form>
</div>
# ENDIF #