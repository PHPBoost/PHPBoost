		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_ONLINE_CONFIG}</li>
				<li>
					<a href="admin_online.php"><img src="online.png" alt="" /></a>
					<br />
					<a href="admin_online.php" class="quick_link">{L_ONLINE_CONFIG}</a>
				</li>
			</ul>
		</div>

		<div id="admin_contents">
			<form action="admin_online.php?token={TOKEN}" method="post" class="fieldset_content">
				<fieldset>
					<legend>{L_ONLINE_CONFIG}</legend>
					<dl>
						<dt><label for="online_displayed">* {L_NBR_ONLINE_DISPLAYED}</label></dt>
						<dd><label><input type="text" maxlength="3" size="3" name="online_displayed" id="online_displayed" value="{NBR_ONLINE_DISPLAYED}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="display_order_online">* {L_DISPLAY_ORDER}</label></dt>
						<dd><label>
							<select name="display_order_online" id="display_order_online">
								# START display_order #
								{display_order.ORDER}
								# END display_order #
							</select>
						</label></dd>
					</dl>
				</fieldset>	
				
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					&nbsp;
					<input type="reset" value="{L_RESET}" class="reset" />			
				</fieldset>
			</form>
		</div>
		