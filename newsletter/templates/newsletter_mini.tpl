# IF C_VERTICAL #
<form action="{PATH_TO_ROOT}/newsletter/?url=/subscribe/" method="post">
	<div class="module_mini_container">
		<div class="module_mini_top">
			<h5 class="sub-title">{L_NEWSLETTER}</h5>
		</div>
		<div class="module_mini_contents">
			<p>
				<input type="text" name="mail_newsletter" maxlength="50" value="{USER_MAIL}">
			</p>
			<p>
				<label><input type="radio" name="subscribe" value="subscribe" checked="checked"> {SUBSCRIBE}</label>
				<br />
				<label><input type="radio" name="subscribe" value="unsubscribe"> {UNSUBSCRIBE}</label>
			</p>
			<p>
				<input type="hidden" name="token" value="{TOKEN}">
				<button type="submit" name="" value="true">{L_SUBMIT}</button>	
			</p>
			<p class="newsletter_link">
				<a href="${relative_url(NewsletterUrlBuilder::archives())}" class="small">{L_ARCHIVES}</a>
			</p>
		</div>
		<div class="module_mini_bottom">
		</div>
	</div>
</form>
# ELSE #
<div id="newsletter">
	<form action="{PATH_TO_ROOT}/newsletter/?url=/subscribe/" method="post">
		<div class="newsletter_form input-element-button">
			<span class="newsletter_title">{L_NEWSLETTER}</span> 
			<input type="text" name="mail_newsletter" maxlength="50" class="newsletter_text" value="{USER_MAIL}">
			<input type="hidden" name="subscribe" value="subscribe">
			<button type="submit" class="newsletter_submit" ><i class="icon-envelope-o"></i></button>
		</div>
	</form>
</div>
# ENDIF #