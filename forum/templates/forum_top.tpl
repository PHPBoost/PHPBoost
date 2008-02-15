		<script type="text/javascript">
		<!--
		function Confirm_read_topics() {
			return confirm("{L_CONFIRM_READ_TOPICS}");
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
							<input class="submit" value="{L_SEARCH}" type="submit" style="font-weight:normal;padding:1px" /><br />
							<a href="search.php{SID}" title="{L_ADVANCED_SEARCH}" class="small_link">{L_ADVANCED_SEARCH}</a>
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
					<img src="{MODULE_DATA_PATH}/images/favorite_mini.png" alt="" class="valign_middle" /> {U_TOPIC_TRACK}
					<img src="{MODULE_DATA_PATH}/images/last_mini.png" alt="" class="valign_middle" /> {U_LAST_MSG_READ}
					<img src="{MODULE_DATA_PATH}/images/new_mini.png" alt="" class="valign_middle" /> {U_MSG_NOT_READ}
					<img src="{MODULE_DATA_PATH}/images/read_mini.png" alt="" class="valign_middle" /> {U_MSG_SET_VIEW}
				</span>
				<div class="spacer"></div>
			</div>
		</div>
		