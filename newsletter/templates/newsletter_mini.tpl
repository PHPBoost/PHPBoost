		<div class="module_mini_container">
			<div class="module_mini_top">
				<h5 class="sub_title">{L_NEWSLETTER}</h5>
			</div>
			<div class="module_mini_table">
				<form action="{ACTION}" method="post">
					<p>
						<input type="text" name="mail_newsletter" maxlength="50" size="18" class="text" value="{USER_MAIL}" />
					</p>
					<p>
						<label><input type="radio" name="subscribe" value="subscribe" checked="checked" /> {SUBSCRIBE}</label>
						<br />
						<label><input type="radio" name="subscribe" value="unsubscribe" /> {UNSUBSCRIBE}</label>
					</p>
					<p>		
						<input type="submit" value="{L_SUBMIT}" class="submit" />	
					</p>
					<p>
						<a href="{ARCHIVES_LINK}" style=" font-size:10px;">{L_ARCHIVES}</a>
					</p>
				</form>&nbsp;
			</div>
			<div class="module_mini_bottom">
			</div>
		</div>
		