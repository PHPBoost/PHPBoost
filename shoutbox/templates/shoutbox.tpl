		<script type="text/javascript">
		<!--
		function Confirm_shout() {
		return confirm("{L_DELETE_MSG}");
		}
		-->
		</script>

		# INCLUDE SHOUTBOX_FORM #

		<br />
		# INCLUDE message_helper #

		<div class="msg_position">
			<div class="msg_top_l"></div>
			<div class="msg_top_r"></div>
			<div class="msg_top" style="text-align:center;">{PAGINATION}&nbsp;</div>
		</div>
		# START shoutbox_list #
		<div class="msg_position">
			<div class="msg_container{shoutbox_list.CLASS_COLOR}">
				<span id="m{shoutbox_list.ID}"></span>
				<div class="msg_top_row">
					<div class="msg_pseudo_mbr">
						{shoutbox_list.USER_ONLINE} {shoutbox_list.USER_PSEUDO}
					</div>
					<div style="float:left;">&nbsp;&nbsp;<a href="{shoutbox_list.U_ANCHOR}"><img src="../templates/{THEME}/images/ancre.png" alt="{shoutbox_list.ID}" /></a> {shoutbox_list.DATE}</div>
					<div style="float:right;">{shoutbox_list.EDIT}{shoutbox_list.DEL}&nbsp;&nbsp;</div>
				</div>
				<div class="msg_contents_container">
					<div class="msg_info_mbr">
						<p style="text-align:center;">{shoutbox_list.USER_RANK}</p>
						<p style="text-align:center;">{shoutbox_list.USER_IMG_ASSOC}</p>
						<p style="text-align:center;">{shoutbox_list.USER_AVATAR}</p>
						<p style="text-align:center;">{shoutbox_list.USER_GROUP}</p>
						{shoutbox_list.USER_SEX}
						{shoutbox_list.USER_DATE}<br />
						{shoutbox_list.USER_MSG}<br />
						{shoutbox_list.USER_LOCAL}
					</div>
					<div class="msg_contents{shoutbox_list.CLASS_COLOR}">
						<div class="msg_contents_overflow">
							{shoutbox_list.CONTENTS}
						</div>
					</div>
				</div>
			</div>
			<div class="msg_sign{shoutbox_list.CLASS_COLOR}">
				<div class="msg_sign_overflow">
					{shoutbox_list.USER_SIGN}
				</div>
				<hr />
				<div style="float:left;">
					{shoutbox_list.U_USER_PM} {shoutbox_list.USER_MAIL} {shoutbox_list.USER_MSN} {shoutbox_list.USER_YAHOO} {shoutbox_list.USER_WEB}
				</div>
				<div style="float:right;font-size:10px;">
					{shoutbox_list.WARNING} {shoutbox_list.PUNISHMENT}
				</div>&nbsp;
			</div>
		</div>
		# END shoutbox_list #
		<div class="msg_position">
			<div class="msg_bottom_l"></div>
			<div class="msg_bottom_r"></div>
			<div class="msg_bottom" style="text-align:center;">{PAGINATION}&nbsp;</div>
		</div>
