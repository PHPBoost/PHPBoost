		<script type="text/javascript">
		<!--
		function Confirm_shout() {
		return confirm("{L_DELETE_MSG}");
		}
		-->
		</script>

		# INCLUDE SHOUTBOX_FORM #
		
		<br />
		# IF C_ERROR_HANDLER #
		<span id="errorh"></span>
		<div class="{ERRORH_CLASS}">
			<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
		</div>
		<br />		
		# ENDIF #
		
		<div class="msg_position">
			<div class="msg_top_l"></div>			
			<div class="msg_top_r"></div>
			<div class="msg_top" style="text-align:center;">{PAGINATION}&nbsp;</div>	
		</div>
		# START shoutbox #
		<div class="msg_position">
			<div class="msg_container{shoutbox.CLASS_COLOR}">
				<span id="m{shoutbox.ID}"></span>
				<div class="msg_top_row">
					<div class="msg_pseudo_mbr">
						{shoutbox.USER_ONLINE} {shoutbox.USER_PSEUDO}
					</div>
					<div style="float:left;">&nbsp;&nbsp;<a href="{shoutbox.U_ANCHOR}"><img src="../templates/{THEME}/images/ancre.png" alt="{shoutbox.ID}" /></a> {shoutbox.DATE}</div>
					<div style="float:right;">{shoutbox.EDIT}{shoutbox.DEL}&nbsp;&nbsp;</div>
				</div>
				<div class="msg_contents_container">
					<div class="msg_info_mbr">
						<p style="text-align:center;">{shoutbox.USER_RANK}</p>
						<p style="text-align:center;">{shoutbox.USER_IMG_ASSOC}</p>
						<p style="text-align:center;">{shoutbox.USER_AVATAR}</p>
						<p style="text-align:center;">{shoutbox.USER_GROUP}</p>
						{shoutbox.USER_SEX}
						{shoutbox.USER_DATE}<br />
						{shoutbox.USER_MSG}<br />
						{shoutbox.USER_LOCAL}
					</div>
					<div class="msg_contents{shoutbox.CLASS_COLOR}">
						<div class="msg_contents_overflow">
							{shoutbox.CONTENTS}
						</div>
					</div>
				</div>
			</div>	
			<div class="msg_sign{shoutbox.CLASS_COLOR}">				
				<div class="msg_sign_overflow">
					{shoutbox.USER_SIGN}
				</div>				
				<hr />
				<div style="float:left;">
					{shoutbox.U_USER_PM} {shoutbox.USER_MAIL} {shoutbox.USER_MSN} {shoutbox.USER_YAHOO} {shoutbox.USER_WEB}
				</div>
				<div style="float:right;font-size:10px;">
					{shoutbox.WARNING} {shoutbox.PUNISHMENT}
				</div>&nbsp;
			</div>	
		</div>				
		# END shoutbox #		
		<div class="msg_position">		
			<div class="msg_bottom_l"></div>		
			<div class="msg_bottom_r"></div>
			<div class="msg_bottom" style="text-align:center;">{PAGINATION}&nbsp;</div>
		</div>
		