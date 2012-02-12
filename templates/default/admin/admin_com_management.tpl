		<script type="text/javascript">
		<!--
		function Confirm() {
			return confirm("{L_CONFIRM_DELETE}");
		}
		-->
		</script>

		
		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_COM}</li>
				<li>
					<a href="admin_com.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/com.png" alt="" /></a>
					<br />
					<a href="admin_com.php" class="quick_link">{L_COM_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_com_config.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/com.png" alt="" /></a>
					<br />
					<a href="admin_com_config.php" class="quick_link">{L_COM_CONFIG}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
			<div class="block_container" style="width:98%;">
				<div class="block_top">
					{L_COM_MANAGEMENT}
				</div>					
				<br />
				<p class="text_center">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/com_mini.png" alt="" class="valign_middle" /> <a href="admin_com.php">{L_DISPLAY_RECENT}</a>
					<br /><br />
					# START modules_com #
					<a href="admin_com.php?module={modules_com.U_MODULES}" class="small_link">{modules_com.MODULES}</a> |
					# END modules_com #
				</p>					
				
				<br />
				<div class="msg_position">
					<div class="msg_top_l"></div>			
					<div class="msg_top_r"></div>
					<div class="msg_top text_center">{PAGINATION_COM}&nbsp;</div>
				</div>

				# START com #	
				<div class="msg_position">
					<div class="msg_container" style="clear:right;">
						<div class="msg_top_row">
							<div class="msg_pseudo_mbr">{com.USER_ONLINE} {com.USER_PSEUDO}</div>
							<span style="float:left;">&nbsp;&nbsp;<a href="{com.U_PROV}#anchor_{com.COM_SCRIPT}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/ancre.png" alt="{com.ID}" /></a> {com.DATE}</span>
							<span style="float:right;">&nbsp;&nbsp;<a href="{com.U_EDIT_COM}#{com.COM_SCRIPT}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_EDIT}" title="{L_EDIT}" class="valign_middle" /></a>&nbsp;&nbsp;<a href="{com.U_DEL_COM}#{com.COM_SCRIPT}" onclick="javascript:return Confirm();"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" class="valign_middle" /></a>&nbsp;&nbsp;</span>
						</div>
						<div class="msg_contents_container">
							<div class="msg_info_mbr">
								<p style="text-align:center;">{com.USER_RANK}</p>
								<p style="text-align:center;">{com.USER_IMG_ASSOC}</p>
								<p style="text-align:center;">{com.USER_AVATAR}</p>
								<p style="text-align:center;">{com.USER_GROUP}</p>
								{com.USER_SEX}
								{com.USER_DATE}<br />
								{com.USER_MSG}<br />
								{com.USER_LOCAL}
							</div>
							<div class="msg_contents">
								<div class="msg_contents_overflow">
									{com.CONTENTS}
									<br /><br /><br />
									<a href="{com.U_PROV}#{com.COM_SCRIPT}">{L_DISPLAY_TOPIC_COM}</a> <a href="{com.U_PROV}#{com.COM_SCRIPT}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/right.png" alt="" class="valign_middle" /></a>
								</div>
							</div>
						</div>
					</div>	
					<div class="msg_sign">				
						<div class="msg_sign_overflow">
							{com.USER_SIGN}
						</div>				
						<hr />
						<div style="float:left;">
							{com.U_USER_PM} {com.USER_MAIL} {com.USER_MSN} {com.USER_YAHOO} {com.USER_WEB}
						</div>
						<div style="float:right;font-size:10px;">
						</div>&nbsp;
					</div>	
				</div>	
				# END com #
				<div class="msg_position">		
					<div class="msg_bottom_l"></div>		
					<div class="msg_bottom_r"></div>
					<div class="msg_bottom" style="text-align:center;">{PAGINATION_COM}&nbsp;</div>
				</div>
			</div>
		</div>
		