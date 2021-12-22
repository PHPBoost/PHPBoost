# IF C_VERTICAL #
	<script>
		function set_subscribe_link() {
			jQuery('#newsletter-mini-subscription-form').attr('action', "{PATH_TO_ROOT}/newsletter/?url=/subscribe/");
			jQuery('button[name="mail_newsletter_button"]').attr('aria-label', "{@newsletter.subscribe.item}");
		};
		function set_unsubscribe_link() {
			jQuery('#newsletter-mini-subscription-form').attr('action', "{PATH_TO_ROOT}/newsletter/?url=/unsubscribe/");
			jQuery('button[name="mail_newsletter_button"]').attr('aria-label', "{@newsletter.unsubscribe.item}");
		};
	</script>
	<form class="cell-form" id="newsletter-mini-subscription-form" action="{PATH_TO_ROOT}/newsletter/?url=/subscribe/" method="post">
		<fieldset>
			<legend class="sr-only">{@user.email}</legend>
			<label for="newsletter-email" class="sr-only">{@user.email}</label>
			<div class="cell-form grouped-inputs">
				<input id="newsletter-email" class="grouped-element" type="text" name="mail_newsletter" maxlength="50" value="{USER_MAIL}" placeholder="{@user.email}">
				<input type="hidden" name="token" value="{TOKEN}">
				<button class="grouped-element button submit" type="submit" name="mail_newsletter_button" value="true" aria-label="{@newsletter.subscribe.streams}"><i class="fa fa-file-signature" aria-hidden="true"></i></button>
			</div>
			<div class="cell-list">
				<ul>
					<li><label class="radio"><input type="radio" name="subscribe" value="subscribe" onclick="set_subscribe_link();" checked="checked"> <span>{@newsletter.subscribe.item}</span></label></li>
					<li><label class="radio"><input type="radio" name="subscribe" value="unsubscribe" onclick="set_unsubscribe_link();"> <span>{@newsletter.unsubscribe.item}</span></label></li>
				</ul>
			</div>
			<div class="cell-body">
				<div class="cell-content align-center">
					<a href="${relative_url(NewsletterUrlBuilder::archives())}" class="button small offload">{@newsletter.archives}</a>
				</div>
			</div>
		</fieldset>
	</form>
# ELSE #
	<div id="newsletter" class="cell-mini# IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #">
		<div class="cell">
			<form class="cell-form" action="{PATH_TO_ROOT}/newsletter/?url=/subscribe/" method="post">
				<fieldset>
					<legend class="sr-only">{@user.email}</legend>
					<div class="cell-form grouped-inputs grouped-auto grouped-right">
						<span class="newsletter-title grouped-element">{@newsletter.module.title}</span>
						<label class="sr-only" for="newsletter-email">{@user.email}</label>
						<input class="grouped-element" id="newsletter-email" type="text" name="mail_newsletter" maxlength="50" value="{USER_MAIL}" placeholder="{@user.email}" aria-labelledby="NewsletterButton">
						<input type="hidden" name="subscribe" value="subscribe">
						<input type="hidden" name="token" value="{TOKEN}">
						<button id="NewsletterButton" name="mail_newsletter_button" type="submit" class="grouped-element button submit" aria-label="{@newsletter.subscribe.streams}"><i class="fa fa-file-signature" aria-hidden="true"></i></button>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
# ENDIF #
