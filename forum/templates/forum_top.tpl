		<script type="text/javascript">
		<!--
		function Confirm_read_topics() {
			return confirm("{L_CONFIRM_READ_TOPICS}");
		}
		var delay_forum = 1000; //Délai après lequel le bloc est automatiquement masqué, après le départ de la souris.
		var timeout_forum;
		var displayed_forum = false;
		var previous_forumblock;

		//Affiche le bloc.
		function forum_display_block(divID)
		{
			var i;
			
			if( timeout_forum )
				clearTimeout(timeout_forum);
			
			var block = document.getElementById('forum_block' + divID);
			if( block.style.display == 'none' )
			{
				if( document.getElementById(previous_forumblock) )
					document.getElementById(previous_forumblock).style.display = 'none';
				block.style.display = 'block';
				displayed_forum = true;
				previous_forumblock = 'forum_block' + divID;
			}
			else
			{
				block.style.display = 'none';
				displayed_forum = false;
			}
		}

		//Cache le bloc.
		function forum_hide_block(forumid, stop)
		{
			if( stop && timeout_forum )
			{	
				clearTimeout(timeout_forum);
			}
			else if( displayed_forum )
			{
				clearTimeout(timeout_forum);
				timeout_forum = setTimeout('forum_display_block(\'' + forumid + '\')', delay_forum);
			}	
		}
		-->
		</script>
		
		
		<div class="module_position" style="margin-bottom:15px;background:none;border:none">
			<div class="forum_title_l"></div>
			<div class="forum_title_r"></div>
			<div class="forum_title">
				<div style="padding:10px;">
					<span style="float:left;"><h2>{FORUM_NAME}</h2></span>
					<span style="float:right;text-align:right">
						<form action="search.php{SID}" method="post">
							<label><input type="text" size="14" id="search" name="search" value="{L_SEARCH}..." class="text" style="background:#FFFFFF url(../templates/{THEME}/images/search.png) no-repeat;background-position:2px 1px;padding-left:22px;" onclick="if(this.value=='{L_SEARCH}...')this.value='';" onblur="if(this.value=='')this.value='{L_SEARCH}...';" /></label>
							<input class="submit" value="{L_SEARCH}" type="submit" name="valid_search" style="font-weight:normal;padding:1px" /><br />
							<a href="search.php{SID}" title="{L_ADVANCED_SEARCH}" class="small_link">{L_ADVANCED_SEARCH}</a>
							
							<input type="hidden" name="time" value="30000" />
							<input type="hidden" name="where" value="contents" />
							<input type="hidden" name="colorate_result" value="1" />
						</form>
					</span>	
					<div class="spacer"></div>					
				</div>
			</div>
			<div class="forum_links" style="border-top:none;">
				<span style="float:left;">
					&bull; <a href="index.php{SID}">{L_FORUM_INDEX}</a>
				</span>
				<span style="float:right;">
					<img src="{MODULE_DATA_PATH}/images/favorite_mini.png" alt="" class="valign_middle" /> {U_TOPIC_TRACK} &bull;
					<img src="{MODULE_DATA_PATH}/images/last_mini.png" alt="" class="valign_middle" /> {U_LAST_MSG_READ} &bull;
					<img src="{MODULE_DATA_PATH}/images/new_mini.png" alt="" class="valign_middle" /> {U_MSG_NOT_READ} 
					
					# IF C_DISPLAY_UNREAD_DETAILS #
					<div style="position:relative;float:left;">
						<div style="position:absolute;z-index:100;float:left;margin-left:160px;display:none;" id="forum_blockforum_unread">
							<div class="row2" style="width:408px;height:{MAX_UNREAD_HEIGHT}px;overflow:auto;padding:0px;" onmouseover="forum_hide_block('forum_unread', 1);" onmouseout="forum_hide_block('forum_unread', 0);">
								<table class="module_table" style="margin:2px;width:99%">
									# START forum_unread_list #
									<tr>
										<td class="text_small row2" style="padding:4px;width:100%">
											{forum_unread_list.U_TOPICS}
										</td>
										<td class="text_small row2" style="padding:4px;">
											[{forum_unread_list.LOGIN}]
										</td>
										<td class="text_small row2" style="padding:4px;white-space:nowrap">
											{forum_unread_list.DATE}
										</td>
									</tr>
									# END forum_unread_list #
								</table>
							</div>
						</div>
					</div>
					<a href="javascript:forum_display_block('forum_unread');" onmouseover="forum_hide_block('forum_unread', 1);" onmouseout="forum_hide_block('forum_unread', 0);" class="bbcode_hover"><img src="../templates/{THEME}/images/upload/plus.png" alt="" class="valign_middle" /></a>
					# ENDIF #

					&bull;					
					<img src="{MODULE_DATA_PATH}/images/read_mini.png" alt="" class="valign_middle" /> {U_MSG_SET_VIEW}
				</span>
				<div class="spacer"></div>
			</div>
		</div>
