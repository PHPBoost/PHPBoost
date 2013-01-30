		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_BUGS_MANAGEMENT}</li>
				<li>
					<a href="{U_CONFIGURATION}"><img src="bugtracker.png" alt="" /></a>
					<br />
					<a href="{U_CONFIGURATION}" class="quick_link">{L_BUGS_CONFIG}</a>
				</li>
				
				<li>
					<a href="{U_AUTHORIZATIONS}"><img src="bugtracker.png" alt="" /></a>
					<br />
					<a href="{U_AUTHORIZATIONS}" class="quick_link">{L_BUGS_AUTHORIZATIONS}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
			# INCLUDE message_helper #
			
			<form action="{U_FORM}" method="post" class="fieldset_content">
				<fieldset>
					<legend>
						{L_AUTH}
					</legend>
					<dl>
						<dt>
							<label>{L_READ_AUTH}</label>
						</dt>
						<dd>
							{BUG_READ_AUTH}
						</dd>
					</dl>
					<dl>
						<dt>
							<label>{L_CREATE_AUTH}</label>
						</dt>
						<dd>
							{BUG_CREATE_AUTH}
						</dd>
					</dl>
					<dl>
						<dt>
							<label>{L_CREATE_ADVANCED_AUTH}</label>
							<br />
							<span>{L_CREATE_ADVANCED_AUTH_EXPLAIN}</lspan>
						</dt>
						<dd>
							{BUG_CREATE_ADVANCED_AUTH}
						</dd>
					</dl>
					<dl>
						<dt>
							<label>{L_MODERATE_AUTH}</label>
						</dt>
						<dd>
							{BUG_MODERATE_AUTH}
						</dd>
					</dl>
				</fieldset>
				
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />
				</fieldset>
			</form>
		</div>