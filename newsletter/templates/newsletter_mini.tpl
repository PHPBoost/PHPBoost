		<form action="{PATH_TO_ROOT}/newsletter/newsletter.php?token={TOKEN}" method="post">
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
		
