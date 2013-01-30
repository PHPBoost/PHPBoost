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
			<div class="module_position forum_position_cat">					
				<div class="module_top_l"></div>		
				<div class="module_top_r"></div>
				<div class="module_top forum_top_cat">
					<span style="float:left;">
						{U_FORUM_CAT}
					</span>
					<span style="float:right;">{PAGINATION}</span>
				</div>
				<div class="forum_position_subcat">
					<div class="module_contents forum_contents forum_contents_subcat">
						<div class="row2 text_small">{L_EXPLAIN_TRACK}</div>
						<table class="module_table forum_table">
							<tr class="forum_text_column">			
								<td>{L_TOPIC}</td>
								<td style="width:100px;">{L_AUTHOR}</td>
								<td style="width:120px;"></td>
								<td style="width:40px;"><input type="checkbox" class="valign_middle" onclick="check_convers(this.checked, 'p');" /> {L_PM}</td>
								<td style="width:50px;"><input type="checkbox" class="valign_middle" onclick="check_convers(this.checked, 'm');" /> {L_MAIL}</td>
								<td style="width:85px;"><input type="checkbox" class="valign_middle" onclick="check_convers(this.checked, 'd');" /> {L_DELETE}</td>
								<td style="width:150px;">{L_LAST_MESSAGE}</td>
							</tr>
						</table>
					</div>
				</div>			
			</div>	
			<div class="module_position forum_position_subcat">
				<div class="module_contents forum_contents forum_contents_subcat">
					<table class="module_table forum_table">
						# IF C_NO_TRACKED_TOPICS #
						<tr>
							<td class="forum_sous_cat" style="text-align:center;">
								<strong>{L_NO_TRACKED_TOPICS}</strong>
							</td>
						</tr>	
						# ENDIF #

						# START topics #		
						<tr>
							<td class="forum_sous_cat forum_sous_cat_pbt" style="width:25px;text-align:center;">
								# IF NOT topics.C_HOT_TOPIC # 
								<img src="{PICTURES_DATA_PATH}/images/{topics.IMG_ANNOUNCE}.png" alt="" />
								# ELSE #
								<img src="{PICTURES_DATA_PATH}/images/{topics.IMG_ANNOUNCE}_hot.gif" alt="" /> 
								# ENDIF #
							</td>
							<td class="forum_sous_cat forum_sous_cat_pbt" style="width:35px;text-align:center;">
								{topics.DISPLAY_MSG} {topics.TRACK} {topics.POLL}
							</td>
							<td class="forum_sous_cat forum_sous_cat_pbt">
								{topics.ANCRE} <strong>{topics.TYPE}</strong> <a href="topic{topics.U_TOPIC_VARS}">{topics.L_DISPLAY_MSG} {topics.TITLE}</a>
								<br />
								<span class="text_small">{topics.DESC}</span> &nbsp;<span class="pagin_forum">{topics.PAGINATION_TOPICS}</span>
							</td>
							<td class="forum_sous_cat_compteur forum_sous_cat_pbt" style="width:100px;">
								<span class="text_small">Par </span>{topics.AUTHOR}
							</td>
							<td class="forum_sous_cat_compteur_nbr forum_sous_cat_compteur forum_sous_cat_pbt">
								{topics.MSG}<BR />{topics.VUS}
							</td>
							<td class="forum_sous_cat_compteur_text forum_sous_cat_compteur forum_sous_cat_pbt">
								{L_MESSAGE}
								<BR />
								{L_VIEW}
							</td>
							<td class="forum_sous_cat_compteur forum_sous_cat_pbt" style="width:40px;text-align:center;">
								<input type="checkbox" id="p{topics.INCR}" name="p{topics.ID}" {topics.CHECKED_PM} />
							</td>
							<td class="forum_sous_cat_compteur forum_sous_cat_pbt" style="width:50px;text-align:center;">
								<input type="checkbox" id="m{topics.INCR}" name="m{topics.ID}" {topics.CHECKED_MAIL} />
							</td>
							<td class="forum_sous_cat_compteur forum_sous_cat_pbt" style="width:85px;text-align:center;">
								<input type="checkbox" id="d{topics.INCR}" name="d{topics.ID}" />
							</td>
							<td class="forum_sous_cat_last forum_sous_cat_pbt">
								{topics.U_LAST_MSG}
							</td>
						</tr>	
						# END topics #
					</table>
				</div>
			</div>
			<div class="module_position forum_position_subcat">
				<div class="forum_position_subcat-bottom" style="border-top:none;">
					<div style="text-align:right;padding:5px;">{PAGINATION}</div>
				</div>
			</div>
			<div style="text-align:center;margin-top:10px;">
				<input type="submit" name="valid" value="{L_SUBMIT}" class="submit" />
			</div>
		</form>
		
		<div class="module_position">
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
			</div>
		</div>		
		
		# INCLUDE forum_bottom #
		