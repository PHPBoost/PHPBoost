		<div style="margin:10px 10px">
			<form action="{PATH_TO_ROOT}/newsletter/newsletter.php{SID}" method="post">
				<div class="newsletter_form">
					<span class="newsletter_title">{L_NEWSLETTER}</span> 
					<span style="float:right;">
						<input type="text" name="mail_newsletter" maxlength="50" size="16" class="text newsletter_text" value="{USER_MAIL}" />
						<input type="image" style="margin-left:-4px;padding:0;border:none" value="1" src="{PATH_TO_ROOT}/templates/{THEME}/modules/newsletter/images/newsletter_submit.png" />
						<input type="hidden" name="subscribe" value="subscribe" />
					</span> 
				</div>
			</form>
		</div> 