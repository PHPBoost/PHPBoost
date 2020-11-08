# IF C_VERTICAL #
	<script>
		function set_subscribe_link() {
			jQuery('#newsletter-mini-subscription-form').attr('action', "{PATH_TO_ROOT}/newsletter/?url=/subscribe/");
			jQuery('button[name="mail_newsletter_button"]').attr('aria-label', "{@newsletter.subscribe_newsletters}");
		};
		function set_unsubscribe_link() {
			jQuery('#newsletter-mini-subscription-form').attr('action', "{PATH_TO_ROOT}/newsletter/?url=/unsubscribe/");
			jQuery('button[name="mail_newsletter_button"]').attr('aria-label', "{@newsletter.unsubscribe_newsletters}");
		};
	</script>
	<form class="cell-form" id="newsletter-mini-subscription-form" action="{PATH_TO_ROOT}/newsletter/?url=/subscribe/" method="post">
		<fieldset>
			<legend class="sr-only">${LangLoader::get_message('email', 'user-common')}</legend>
			<label for="newsletter-email" class="sr-only">${LangLoader::get_message('email', 'user-common')}</label>
			<div class="cell-form grouped-inputs">
				<input id="newsletter-email" class="grouped-element" type="text" name="mail_newsletter" maxlength="50" value="{USER_MAIL}" placeholder="${LangLoader::get_message('email', 'user-common')}">
				<input type="hidden" name="token" value="{TOKEN}">
				<button class="grouped-element button submit" type="submit" name="mail_newsletter_button" value="true" aria-label="{@subscribe.newsletters}"><i class="fa fa-file-signature" aria-hidden="true"></i></button>
			</div>
			<div class="cell-list">
				<ul>
					<li><label class="radio"><input type="radio" name="subscribe" value="subscribe" onclick="set_subscribe_link();" checked="checked"> <span>{@newsletter.subscribe_newsletters}</span></label></li>
					<li><label class="radio"><input type="radio" name="subscribe" value="unsubscribe" onclick="set_unsubscribe_link();"> <span>{@newsletter.unsubscribe_newsletters}</span></label></li>
				</ul>
			</div>
			<div class="cell-body">
				<div class="cell-content align-center">
					<a href="${relative_url(NewsletterUrlBuilder::archives())}" class="button small">{@newsletter.archives}</a>
				</div>
			</div>
		</fieldset>
	</form>
# ELSE #
	<div id="newsletter" class="cell-mini# IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #">
		<div class="cell">
			<form class="cell-form" action="{PATH_TO_ROOT}/newsletter/?url=/subscribe/" method="post">
				<fieldset>
					<legend class="sr-only">${LangLoader::get_message('email', 'user-common')}</legend>
					<div class="cell-form grouped-inputs grouped-auto grouped-right">
						<span class="newsletter-title grouped-element">{@newsletter}</span>
						<label class="sr-only" for="newsletter-email">${LangLoader::get_message('email', 'user-common')}</label>
						<input class="grouped-element" id="newsletter-email" type="text" name="mail_newsletter" maxlength="50" value="{USER_MAIL}" placeholder="${LangLoader::get_message('email', 'user-common')}" aria-labelledby="NewsletterButton">
						<input type="hidden" name="subscribe" value="subscribe">
						<input type="hidden" name="token" value="{TOKEN}">
						<button id="NewsletterButton" name="mail_newsletter_button" type="submit" class="grouped-element button submit" aria-label="{@subscribe.newsletters}"><i class="fa fa-file-signature" aria-hidden="true"></i></button>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
# ENDIF #
