		# INCLUDE forum_top #
		
		<script>
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
					<span style="float:left;"><i class="fa fa-msg-track"></i> {U_FORUM_CAT}</span>
					# IF C_PAGINATION #<span class="float-right"># INCLUDE PAGINATION #</span># ENDIF #
					<div class="spacer"></div>
				</div>
				<div class="forum_position_subcat">
					<div class="small center">{L_EXPLAIN_TRACK}</div>
					<div class="module_contents forum_contents forum_contents_subcat">
						
						<table class="module-table forum_table">
							<thead>
								<tr class="forum_text_column">
									<th style="width:390px;">{L_TOPIC}</th>
									<th style="width:100px;">{L_AUTHOR}</th>
									<th style="width:170px;text-align:right;">{L_MESSAGE}/{L_VIEW}</th>
									<th style="width:40px;"><input type="checkbox" class="valign-middle" onclick="check_convers(this.checked, 'p');"> {L_PM}</th>
									<th style="width:40px;"><input type="checkbox" class="valign-middle" onclick="check_convers(this.checked, 'm');"> {L_MAIL}</th>
									<th style="width:70px;"><input type="checkbox" class="valign-middle" onclick="check_convers(this.checked, 'd');"> {L_DELETE}</th>
									<th style="width:130px;">{L_LAST_MESSAGE}</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
			<div class="module_position forum_position_subcat">
				<div class="module_contents forum_contents forum_contents_subcat">
					<table class="module-table forum_table">
						<tbody>
							# IF C_NO_TRACKED_TOPICS #
							<tr>
								<td class="forum_sous_cat" style="text-align:center;">
									<strong>{L_NO_TRACKED_TOPICS}</strong>
								</td>
							</tr>
							# ENDIF #
	
							# START topics #
							<tr>
								<td class="forum_sous_cat forum_sous_cat_pbt" style="width:40px;text-align:center;">
									# IF NOT topics.C_HOT_TOPIC # 
									<img src="{PICTURES_DATA_PATH}/images/{topics.IMG_ANNOUNCE}.png" alt="" />
									# ELSE #
									<img src="{PICTURES_DATA_PATH}/images/{topics.IMG_ANNOUNCE}_hot.gif" alt="" /> 
									# ENDIF #
								</td>
								<td class="forum_sous_cat forum_sous_cat_pbt" style="width:35px;text-align:center;">
									{topics.DISPLAY_MSG} {topics.TRACK} # IF C_POLL #<i class="fa fa-tasks"></i># ENDIF #
								</td>
								<td class="forum_sous_cat forum_sous_cat_pbt">
									{topics.ANCRE} <strong>{topics.TYPE}</strong> <a href="topic{topics.U_TOPIC_VARS}">{topics.L_DISPLAY_MSG} {topics.TITLE}</a>
									<br />
									<span class="smaller">{topics.DESC}</span># IF topics.C_PAGINATION # &nbsp;<span class="pagin_forum"># INCLUDE topics.PAGINATION #</span># ENDIF #
								</td>
								<td class="forum_sous_cat_compteur forum_sous_cat_pbt" style="width:100px;">
									<span class="smaller">Par </span>{topics.AUTHOR}
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
									<input type="checkbox" id="p{topics.INCR}" name="p{topics.ID}" {topics.CHECKED_PM}>
								</td>
								<td class="forum_sous_cat_compteur forum_sous_cat_pbt" style="width:40px;text-align:center;">
									<input type="checkbox" id="m{topics.INCR}" name="m{topics.ID}" {topics.CHECKED_MAIL}>
								</td>
								<td class="forum_sous_cat_compteur forum_sous_cat_pbt" style="width:70px;text-align:center;">
									<input type="checkbox" id="d{topics.INCR}" name="d{topics.ID}">
								</td>
								<td class="forum_sous_cat_last forum_sous_cat_pbt" style="width:140px;">
									{topics.U_LAST_MSG}
								</td>
							</tr>
							# END topics #
						</tbody>
					</table>
				</div>
			</div>
			<div class="module_position forum_position_subcat">
				<div class="forum_position_subcat-bottom"></div>
			</div>
			<div style="text-align:center;margin-top:10px;">
				<button type="submit" name="valid" value="true">{L_SUBMIT}</button>
			</div>
		</form>
		
		<div class="module_position">
			<div class="module_bottom_l"></div>
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				# IF C_PAGINATION #<span class="float-right"># INCLUDE PAGINATION #</span># ENDIF #
			</div>
		</div>
		
		# INCLUDE forum_bottom #
		