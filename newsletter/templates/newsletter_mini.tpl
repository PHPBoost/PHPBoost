		# IF C_VERTICAL #
		<form action="{PATH_TO_ROOT}/newsletter/?url=/subscribe/" method="post">
			<div class="module_mini_container">
				<div class="module_mini_top">
					<h5 class="sub_title">{L_NEWSLETTER}</h5>
				</div>
				<div class="module_mini_contents">
					<p>
						<input type="text" name="mail_newsletter" maxlength="50" size="18" class="text" value="{USER_MAIL}" />
					</p>
					<p>
						<label><input type="radio" name="subscribe" value="subscribe" checked="checked" /> {SUBSCRIBE}</label>
						<br />
						<label><input type="radio" name="subscribe" value="unsubscribe" /> {UNSUBSCRIBE}</label>
					</p>
					<p>
						<input type="hidden" name="token" value="{TOKEN}" />
						<input type="submit" value="{L_SUBMIT}" class="submit" />	
					</p>
					<p style="margin:0;margin-top:10px;">
						<a href="{PATH_TO_ROOT}/newsletter/newsletter.php{SID}" class="small_link">{L_ARCHIVES}</a>
					</p>
				</div>
				<div class="module_mini_bottom">
				</div>
			</div>
		</form>
		# ELSE #
		<div style="margin:10px 10px">
			<form action="{PATH_TO_ROOT}/newsletter/?url=/subscribe/" method="post">
				<div class="newsletter_form" style="float:right;">
					<span class="newsletter_title">{L_NEWSLETTER}</span> 
					<input type="text" name="mail_newsletter" maxlength="50" size="16" class="text newsletter_text" value="{USER_MAIL}" />
					<input type="image" class="newsletter_img" value="1" src="{PATH_TO_ROOT}/newsletter/templates/images/newsletter_submit.png" />
					<input type="hidden" name="token" value="{TOKEN}" />
				</div>
			</form>
		</div>
		# ENDIF #

		
