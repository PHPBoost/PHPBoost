<div class="module_position">
	<div class="module_top_l"></div>
	<div class="module_top_r"></div>
	<div class="module_top">{L_GUESTBOOK} </div>
		<script type="text/javascript">
		<!--
		function Confirm() {
			return confirm("{L_DELETE_MSG}");
		}
		-->
		</script>

		# INCLUDE GUESTBOOK_FORM #
		
		# IF C_ERROR_WRITING_AUTH #
		<div class="error_writing_auth">
			{L_ERROR_WRITING_AUTH}
		</div>
		# ENDIF #
		
		# IF C_PAGINATION #
		<div class="msg_position">
			<div class="msg_top_l"></div>
			<div class="msg_top_r"></div>
			<div class="msg_top text_center">{PAGINATION}&nbsp;</div>
		</div>
		# ENDIF #
		# START guestbook #
		<div class="msg_position">
			<div class="msg_container{guestbook.CLASS_COLOR}">
				<span id="m{guestbook.ID}"></span>
				<div class="msg_top_row">
					<div class="msg_pseudo_mbr">
						{guestbook.USER_ONLINE} {guestbook.USER_PSEUDO}
					</div>
					<div class="float_left">&nbsp;&nbsp;<a href="{guestbook.U_ANCHOR}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/ancre.png" alt="{guestbook.ID}" /></a> {guestbook.DATE}</div>
					<div class="float_right">{guestbook.EDIT}{guestbook.DEL}&nbsp;&nbsp;</div>
				</div>
				<div class="msg_contents_container">
					<div class="msg_info_mbr">
						<p class="text_center">{guestbook.USER_RANK}</p>
						<p class="text_center">{guestbook.USER_IMG_ASSOC}</p>
						<p class="text_center">{guestbook.USER_AVATAR}</p>
						<p class="text_center">{guestbook.USER_GROUP}</p>
						{guestbook.USER_SEX}
						{guestbook.USER_DATE}<br />
						{guestbook.USER_MSG}<br />
						{guestbook.USER_LOCAL}
					</div>
					<div class="msg_contents{guestbook.CLASS_COLOR}">
						<div class="msg_contents_overflow">
							{guestbook.CONTENTS}
						</div>
					</div>
				</div>
			</div>
			<div class="msg_sign{guestbook.CLASS_COLOR}">
				<div class="msg_sign_overflow">
					{guestbook.USER_SIGN}
				</div>
				<hr />
				<div class="float_left">
					{guestbook.U_USER_PM} {guestbook.USER_MAIL} {guestbook.USER_MSN} {guestbook.USER_YAHOO} {guestbook.USER_WEB}
				</div>
				<div class="warning_punishment">
					{guestbook.WARNING} {guestbook.PUNISHMENT}
				</div>&nbsp;
			</div>
		</div>
		# END guestbook #
		# IF C_PAGINATION #
		<div class="msg_position">
			<div class="msg_bottom_l"></div>
			<div class="msg_bottom_r"></div>
			<div class="msg_bottom text_center">{PAGINATION}&nbsp;</div>
		</div>
		# ENDIF #
	
	<div class="module_bottom_l"></div>
	<div class="module_bottom_r"></div>
	<div class="module_bottom"></div>
</div>