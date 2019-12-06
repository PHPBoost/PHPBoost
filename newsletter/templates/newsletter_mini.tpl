# IF C_VERTICAL #
	<form action="{PATH_TO_ROOT}/newsletter/?url=/subscribe/" method="post">
		<div class="cell-form grouped-inputs">
			<input class="grouped-element" type="text" name="mail_newsletter" maxlength="50" value="{USER_MAIL}" placeholder="${LangLoader::get_message('email', 'user-common')}">
			<input type="hidden" name="token" value="{TOKEN}">
			<button class="grouped-element button submit" type="submit" name="" value="true" aria-label="{@subscribe.newsletter}"><i class="fa fa-envelope" aria-hidden="true"></i></button>
		</div>
		<div class="cell-list">
			<ul>
				<li><label><input type="radio" name="subscribe" value="subscribe" checked="checked"> {@newsletter.subscribe_newsletters}</label></li>
				<li><label><input type="radio" name="subscribe" value="unsubscribe"> {@newsletter.unsubscribe_newsletters}</label></li>
			</ul>
		</div>
		<div class="cell-body">
			<div class="cell-content align-center">
				<a href="${relative_url(NewsletterUrlBuilder::archives())}" class="button small">{@newsletter.archives}</a>
			</div>
		</div>
	</form>
# ELSE #
	<div id="newsletter" class="cell-mini# IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #">
		<div class="cell">
			<form class="cell-form grouped-inputs grouped-auto grouped-right" action="{PATH_TO_ROOT}/newsletter/?url=/subscribe/" method="post">
				<span class="newsletter-title grouped-element">{@newsletter}</span>
				<input type="text" name="mail_newsletter" maxlength="50" value="{USER_MAIL}" placeholder="${LangLoader::get_message('email', 'user-common')}" aria-labelledby="NewsletterButton">
				<input type="hidden" name="subscribe" value="subscribe">
				<input type="hidden" name="token" value="{TOKEN}">
				<button id="NewsletterButton" type="submit" class="grouped-element button submit" aria-label="{@subscribe.newsletter}"><i class="fa fa-envelope" aria-hidden="true"></i></button>
			</form>
		</div>
	</div>
# ENDIF #
