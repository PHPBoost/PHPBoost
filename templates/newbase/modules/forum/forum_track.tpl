		# INCLUDE forum_top #
		
		<script type="text/javascript">
		<!--
			function check_convers(status, id)
			{
				for(i = 0; i < {NBR_TOPICS}; i++)
					document.getElementById(id + i).checked = status;
			}	 
		-->
		</script>
		
		<form action="track{U_TRACK_ACTION}" method="post">	
			<div class="module_position">					
				<div class="module_top_l"></div>		
				<div class="module_top_r"></div>
				<div class="module_top">
					<span style="float:left;">
						&bull; {U_FORUM_CAT}
					</span>
					<span style="float:right;">{PAGINATION}</span>
				</div>
				<div class="row2 text_small">{L_EXPLAIN_TRACK}</div>
			</div>
			
			<div class="module_position" style="border-bottom:none;border-color:#366393;">
				<div class="module_contents forum_contents">
					<table class="module_table forum_table">
						<tr >			
							<td class="forum_text_column" style="border-left:none;">{L_TOPIC}</td>
							<td class="forum_text_column" style="width:80px;">{L_AUTHOR}</td>
							<td class="forum_text_column" style="width:60px;">{L_MESSAGE}</td>
							<td class="forum_text_column" style="width:60px;">{L_VIEW}</td>
							<td class="forum_text_column" style="width:40px;"><input type="checkbox" class="valign_middle" onclick="check_convers(this.checked, 'p');" /><br/>{L_PM}</td>
							<td class="forum_text_column" style="width:50px;"><input type="checkbox" class="valign_middle" onclick="check_convers(this.checked, 'm');" /><br/>{L_MAIL}</td>
							<td class="forum_text_column" style="width:60px;"><input type="checkbox" class="valign_middle" onclick="check_convers(this.checked, 'd');" /><br/>{L_DELETE}</td>
							<td class="forum_text_column" style="width:96px;border-right:none;">{L_LAST_MESSAGE}</td>
						</tr>
					</table>
				</div>			
			</div>	
			<div class="module_position" style="">
				<div class="module_contents forum_contents">
					<table class="module_table forum_table" style="border-bottom: 1px solid #CCCCCC;">
						# IF C_NO_TRACKED_TOPICS #
						<tr>
							<td class="forum_sous_cat" style="text-align:center;">
								<strong>{L_NO_TRACKED_TOPICS}</strong>
							</td>
						</tr>	
						# ENDIF #

						# START topics #		
						<tr>
							<td class="forum_sous_cat" style="width:25px;text-align:center;">
								# IF NOT topics.C_HOT_TOPIC # 
								<img src="{PICTURES_DATA_PATH}/images/{topics.IMG_ANNOUNCE}.png" alt="" />
								# ELSE #
								<img src="{PICTURES_DATA_PATH}/images/{topics.IMG_ANNOUNCE}_hot.gif" alt="" /> 
								# ENDIF #
							</td>
							<td class="forum_sous_cat" >
								{topics.ANCRE} <strong>{topics.TYPE}</strong> <a href="topic{topics.U_TOPIC_VARS}">{topics.L_DISPLAY_MSG} {topics.TITLE}</a>
								<br />
								<span class="text_small">{topics.DESC}</span> &nbsp;<span class="pagin_forum">{topics.PAGINATION_TOPICS}</span>
							</td>
							<td class="forum_sous_cat_compteur" style="width:80px;">
								{topics.AUTHOR}
							</td>
							<td class="forum_sous_cat_compteur"style="width:60px;">
								{topics.MSG}
							</td>
							<td class="forum_sous_cat_compteur" style="width:60px;">
								{topics.VUS}
							</td>
							<td class="forum_sous_cat_compteur" style="width:40px;text-align:center;">
								<input type="checkbox" id="p{topics.INCR}" name="p{topics.ID}" {topics.CHECKED_PM} />
							</td>
							<td class="forum_sous_cat_compteur" style="width:50px;text-align:center;">
								<input type="checkbox" id="m{topics.INCR}" name="m{topics.ID}" {topics.CHECKED_MAIL} />
							</td>
							<td class="forum_sous_cat_compteur" style="width:60px;text-align:center;">
								<input type="checkbox" id="d{topics.INCR}" name="d{topics.ID}" />
							</td>
							<td class="forum_sous_cat_last" style="width:96px;">
								{topics.U_LAST_MSG}
							</td>
						</tr>	
						# END topics #
					</table>
					<div style="margin:10px 0px;text-align:center"><input type="submit" name="valid" value="{L_SUBMIT}" class="submit" /></div>
				</div>
			</div>
		
		
		<div class="module_position" style="border-top:none;margin-bottom:15px;">
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<span style="float:left;" class="text_strong">
					&bull; {U_FORUM_CAT}
				</span>
				<span style="float:right;">{PAGINATION}</span>&nbsp;
			</div>
		</div>		
		</form>
		# INCLUDE forum_bottom #
		