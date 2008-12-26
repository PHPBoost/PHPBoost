        <div style="position:relative;bottom:0px;top:30px;margin-left:450px;">
			<form action="{PATH_TO_ROOT}/newsletter/newsletter.php{SID}" method="post">
				<div class="newsletter_form">
					<span class="newsletter_title">{L_NEWSLETTER}</span> 
					<span style="float:right;">
						<input type="text" name="mail_newsletter" maxlength="50" size="16" class="text newsletter_text" value="{USER_MAIL}" />
						<input type="image" class="newsletter_img" value="1" src="{PATH_TO_ROOT}/templates/{THEME}/modules/newsletter/images/newsletter_submit.png" />
						<input type="hidden" name="subscribe" value="subscribe" />
					</span> 
				</div>
			</form>
            </div>