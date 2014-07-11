# IF C_VERTICAL #
<form action="{PATH_TO_ROOT}/newsletter/?url=/subscribe/" method="post">
	<div class="module-mini-container">
		<div class="module-mini-top">
			<h5 class="sub-title">{@newsletter}</h5>
		</div>
		<div class="module-mini-contents">
			<p>
				<input type="text" name="mail_newsletter" maxlength="50" value="{USER_MAIL}" placeholder="${LangLoader::get_message('email', 'user-common')}">
			</p>
			<p>
				<label><input type="radio" name="subscribe" value="subscribe" checked="checked"> {@newsletter.subscribe_newsletters}</label>
				<br />
				<label><input type="radio" name="subscribe" value="unsubscribe"> {@newsletter.unsubscribe_newsletters}</label>
			</p>
			<p>
				<input type="hidden" name="token" value="{TOKEN}">
				<button type="submit" name="" value="true">{@newsletter.submit}</button>
			</p>
			<p class="newsletter-link">
				<a href="${relative_url(NewsletterUrlBuilder::archives())}" class="small">{@newsletter.archives}</a>
			</p>
		</div>
		<div class="module-mini-bottom">
		</div>
	</div>
</form>
# ELSE #
<div id="newsletter">
	<form action="{PATH_TO_ROOT}/newsletter/?url=/subscribe/" method="post">
		<div class="newsletter-form input-element-button">
			<span class="newsletter-title">{@newsletter}</span> 
			<input type="text" name="mail_newsletter" maxlength="50" value="{USER_MAIL}" placeholder="${LangLoader::get_message('email', 'user-common')}">
			<input type="hidden" name="subscribe" value="subscribe">
			<button type="submit" class="newsletter-submit"><i class="fa fa-envelope-o"></i></button>
		</div>
	</form>
</div>
# ENDIF #