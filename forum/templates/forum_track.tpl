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
			<div class="module-position">
				<div class="module-top-l"></div>
				<div class="module-top-r"></div>
				<div class="module-top">
					<span>
						&bull; {U_FORUM_CAT}
					</span>
					# IF C_PAGINATION #<span class="float-right"># INCLUDE PAGINATION #</span># ENDIF #
				</div>
				<div class="module-contents forum-contents">
					<div class="text_small">{L_EXPLAIN_TRACK}</div>
				</div>
			</div>
			<div class="module-position">
				<div class="module-contents forum-contents">
					<table class="module-table forum-table">
						<thead>
							<tr class="forum-text-column">
								<th colspan="3" >{L_TOPIC}</th>
								<th style="width:100px;">{L_AUTHOR}</th>
								<th style="width:60px;">{L_MESSAGE}</th>
								<th style="width:60px;">{L_VIEW}</th>
								<th style="width:40px;"><input type="checkbox" class="valign-middle" onclick="check_convers(this.checked, 'p');"> {L_PM}</th>
								<th style="width:50px;"><input type="checkbox" class="valign-middle" onclick="check_convers(this.checked, 'm');"> {L_MAIL}</th>
								<th style="width:85px;"><input type="checkbox" class="valign-middle" onclick="check_convers(this.checked, 'd');"> {L_DELETE}</th>
								<th style="width:150px;">{L_LAST_MESSAGE}</th>
							</tr>
						</thead>
						<tbody>
							# IF C_NO_TRACKED_TOPICS #
							<tr>
								<td colspan="10" class="forum-sous-cat" style="text-align:center;">
									<strong>{L_NO_TRACKED_TOPICS}</strong>
								</td>
							</tr>
							# ENDIF #
	
							# START topics #
							<tr>
								<td class="forum-sous-cat" style="width:40px;text-align:center;">
									# IF NOT topics.C_HOT_TOPIC # 
									<i class="fa {topics.IMG_ANNOUNCE}"></i>
									# ELSE #
									<i class="fa # IF topics.C_BLINK #blink # ENDIF #{topics.IMG_ANNOUNCE}-hot"></i>
									# ENDIF #
								</td>
								<td class="forum-sous-cat" style="width:35px;text-align:center;">
									{topics.DISPLAY_MSG} {topics.TRACK} # IF C_POLL #<i class="fa fa-tasks"></i># ENDIF #
								</td>
								<td class="forum-sous-cat">
									# IF topics.C_PAGINATION #<span class="pagin-forum"># INCLUDE topics.PAGINATION #</span># ENDIF #
									{topics.ANCRE} <strong>{topics.TYPE}</strong> <a href="topic{topics.U_TOPIC_VARS}">{topics.L_DISPLAY_MSG} {topics.TITLE}</a>
									<br />
									<span class="smaller">{topics.DESC}</span>
								</td>
								<td class="forum-sous-cat-compteur" style="width:100px;">
									{topics.AUTHOR}
								</td>
								<td class="forum-sous-cat-compteur">
									{topics.MSG}
								</td>
								<td class="forum-sous-cat-compteur">
									{topics.VUS}
								</td>
								<td class="forum-sous-cat-compteur" style="width:40px;text-align:center;">
									<input type="checkbox" id="p{topics.INCR}" name="p{topics.ID}" {topics.CHECKED_PM}>
								</td>
								<td class="forum-sous-cat-compteur" style="width:50px;text-align:center;">
									<input type="checkbox" id="m{topics.INCR}" name="m{topics.ID}" {topics.CHECKED_MAIL}>
								</td>
								<td class="forum-sous-cat-compteur" style="width:85px;text-align:center;">
									<input type="checkbox" id="d{topics.INCR}" name="d{topics.ID}">
								</td>
								<td class="forum-sous-cat-last">
									{topics.U_LAST_MSG}
								</td>
							</tr>
							# END topics #
						</tbody>
					</table>
					<div style="margin:10px;text-align:center"><button type="submit" name="valid" value="true" class="submit">{L_SUBMIT}</button></div>
				</div>
			</div>
		</form>
		
		<div class="module-position">
			<div class="module-bottom-l"></div>
			<div class="module-bottom-r"></div>
			<div class="module-bottom">
				<span style="float:left;" class="text-strong">
					&bull; {U_FORUM_CAT}
				</span>
				# IF C_PAGINATION #<span class="float-right"># INCLUDE PAGINATION #</span><div class="spacer"></div># ENDIF #
			</div>
		</div>
		
		# INCLUDE forum_bottom #
		