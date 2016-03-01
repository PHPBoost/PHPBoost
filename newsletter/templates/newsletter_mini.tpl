# IF C_VERTICAL #
<form action="{PATH_TO_ROOT}/newsletter/?url=/subscribe/" method="post">
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
		<button type="submit" name="" value="true"><i class="fa fa-envelope-o"></i></button>
	</p>
	<p class="newsletter-link">
		<a href="${relative_url(NewsletterUrlBuilder::archives())}" class="small">{@newsletter.archives}</a>
	</p>
</form>
# ELSE #
<div id="newsletter"# IF C_HIDDEN_WITH_SMALL_SCREENS # class="hidden-small-screens"# ENDIF #>
	<form action="{PATH_TO_ROOT}/newsletter/?url=/subscribe/" method="post">
		<div class="newsletter-form input-element-button">
			<span class="newsletter-title">{@newsletter}</span> 
			<input type="text" name="mail_newsletter" maxlength="50" value="{USER_MAIL}" placeholder="${LangLoader::get_message('email', 'user-common')}">
			<input type="hidden" name="subscribe" value="subscribe">
			<input type="hidden" name="token" value="{TOKEN}">
			<button type="submit" class="newsletter-submit"><i class="fa fa-envelope-o"></i></button>
		</div>
	</form>
</div>
# ENDIF #