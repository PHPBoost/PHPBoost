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
			<div class="module_position">
				<div class="module_top_l"></div>
				<div class="module_top_r"></div>
				<div class="module_top">
					<span style="float:left;">
						&bull; {U_FORUM_CAT}
					</span>
					# IF C_PAGINATION #<span class="float-right"># INCLUDE PAGINATION #</span># ENDIF #
				</div>
				<div class="module_contents forum_contents">
					<div class="text_small">{L_EXPLAIN_TRACK}</div>
					<table class="module-table forum_table">
						<thead>
							<tr class="forum_text_column">
								<th>{L_TOPIC}</td>
								<th style="width:100px;">{L_AUTHOR}</td>
								<th style="width:60px;">{L_MESSAGE}</td>
								<th style="width:60px;">{L_VIEW}</td>
								<th style="width:40px;"><input type="checkbox" class="valign-middle" onclick="check_convers(this.checked, 'p');"> {L_PM}</td>
								<th style="width:50px;"><input type="checkbox" class="valign-middle" onclick="check_convers(this.checked, 'm');"> {L_MAIL}</td>
								<th style="width:85px;"><input type="checkbox" class="valign-middle" onclick="check_convers(this.checked, 'd');"> {L_DELETE}</td>
								<th style="width:150px;">{L_LAST_MESSAGE}</td>
							</tr>
						</thead>
					</table>
				</div>			
			</div>	
			<div class="module_position">
				<div class="module_contents forum_contents">
					<table class="module-table forum_table">
						<tbody>
							# IF C_NO_TRACKED_TOPICS #
							<tr>
								<td colspan="8" class="forum_sous_cat" style="text-align:center;">
									<strong>{L_NO_TRACKED_TOPICS}</strong>
								</td>
							</tr>	
							# ENDIF #
	
							# START topics #		
							<tr>
								<td class="forum_sous_cat" style="width:25px;text-align:center;">
									# IF NOT topics.C_HOT_TOPIC # 
									<i class="fa {topics.IMG_ANNOUNCE}"></i>
									# ELSE #
									<i class="fa {topics.IMG_ANNOUNCE}-hot"></i>
									# ENDIF #
								</td>
								<td class="forum_sous_cat" style="width:35px;text-align:center;">
									{topics.DISPLAY_MSG} {topics.TRACK} # IF C_POLL #<i class="fa fa-tasks"></i># ENDIF #
								</td>
								<td class="forum_sous_cat">
									{topics.ANCRE} <strong>{topics.TYPE}</strong> <a href="topic{topics.U_TOPIC_VARS}">{topics.L_DISPLAY_MSG} {topics.TITLE}</a>
									<br />
									<span class="smaller">{topics.DESC}</span># IF topics.C_PAGINATION # &nbsp;<span class="pagin_forum"># INCLUDE topics.PAGINATION #</span># ENDIF #
								</td>
								<td class="forum_sous_cat_compteur" style="width:100px;">
									{topics.AUTHOR}
								</td>
								<td class="forum_sous_cat_compteur">
									{topics.MSG}
								</td>
								<td class="forum_sous_cat_compteur">
									{topics.VUS}
								</td>
								<td class="forum_sous_cat_compteur" style="width:40px;text-align:center;">
									<input type="checkbox" id="p{topics.INCR}" name="p{topics.ID}" {topics.CHECKED_PM}>
								</td>
								<td class="forum_sous_cat_compteur" style="width:50px;text-align:center;">
									<input type="checkbox" id="m{topics.INCR}" name="m{topics.ID}" {topics.CHECKED_MAIL}>
								</td>
								<td class="forum_sous_cat_compteur" style="width:85px;text-align:center;">
									<input type="checkbox" id="d{topics.INCR}" name="d{topics.ID}">
								</td>
								<td class="forum_sous_cat_last">
									{topics.U_LAST_MSG}
								</td>
							</tr>
							# END topics #
						</tbody>
					</table>
					<div style="margin-top:10px;text-align:center"><button type="submit" name="valid" value="true">{L_SUBMIT}</button></div>
				</div>
			</div>
		</form>
		
		<div class="module_position">
			<div class="module_bottom_l"></div>
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<span style="float:left;" class="text-strong">
					&bull; {U_FORUM_CAT}
				</span>
				# IF C_PAGINATION #<span class="float-right"># INCLUDE PAGINATION #</span># ENDIF #
			</div>
		</div>
		
		# INCLUDE forum_bottom #
		