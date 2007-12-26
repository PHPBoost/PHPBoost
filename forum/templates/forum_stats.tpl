		<div class="forum_title">{FORUM_NAME}</div>
		<div class="module_position">
			<div class="row2" colspan="4">
				<span style="float:left;">
				&bull; <a href="index.php{SID}">{L_FORUM_INDEX}</a>
				</span>
				<span style="float:right;">
				{U_SEARCH}
				{U_TOPIC_TRACK}
				{U_LAST_MSG_READ}
				{U_MSG_NOT_READ}
				</span>&nbsp;
			</div>
		</div>
		
		<br />	
		
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">{L_STATS}</div>
			<div class="module_contents">
				<table class="module_table">
					<tr>
						<th colspan="2">	
							{L_STATS}
						</th>
					</tr>
					<tr>
						<td class="row2">
							{L_NBR_TOPICS}: <strong>{NBR_TOPICS}</strong><br />
							{L_NBR_MSG}: <strong>{NBR_MSG}</strong>
							<br /><br />
							{L_NBR_TOPICS_DAY}: <strong>{NBR_TOPICS_DAY}</strong><br />
							{L_NBR_MSG_DAY}: <strong>{NBR_MSG_DAY}</strong><br />
							{L_NBR_TOPICS_TODAY}: <strong>{NBR_TOPICS_TODAY}</strong><br />
							{L_NBR_MSG_TODAY}: <strong>{NBR_MSG_TODAY}</strong>
						</td>
					</tr>
				</table>

				<br /><br />
				
				<table class="module_table">
					<tr>
						<th colspan="2">	
							{L_LAST_MSG}
						</th>
					</tr>
					# START last_msg #
					<tr>
						<td class="row2">
							<a href="../forum/topic{last_msg.U_TOPIC_ID}">{last_msg.TITLE}</a>
						</td>
					</tr>
					# END last_msg #
				</table>

				<br /><br />

				<table class="module_table">
					<tr>
						<th colspan="2">	
							{L_POPULAR}
						</th>
					</tr>
					# START popular #
					<tr>
						<td class="row2">
							<a href="../forum/topic{popular.U_TOPIC_ID}">{popular.TITLE}</a>
						</td>
					</tr>
					# END popular #
				</table>

				<br /><br />

				<table class="module_table">
					<tr>
						<th colspan="2">	
							{L_ANSWERS}
						</th>
					</tr>
					# START answers #
					<tr>
						<td class="row2">
							<a href="../forum/topic{answers.U_TOPIC_ID}">{answers.TITLE}</a>
						</td>
					</tr>
					# END answers #
				</table>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
		</div>
				
		<br />

		<div class="module_position">
			<div class="row2">
				<span style="float:left;">
					&bull; <a href="index.php{SID}">{L_FORUM_INDEX}</a> &bull; <a href="stats.php{SID}">{L_STATS}</a> <a href="stats.php{SID}"><img src="{MODULE_DATA_PATH}/images/stats.png" alt="" style="vertical-align:middle" /></a>
				</span>
				<span style="float:right;">
					{U_SEARCH}
					{U_TOPIC_TRACK}
					{U_LAST_MSG_READ}
					{U_MSG_NOT_READ}
				</span>&nbsp;
			</div>
			<div class="forum_online">
				<span style="float:left;">
					{TOTAL_ONLINE} {L_USER} {L_ONLINE} :: {ADMIN} {L_ADMIN}, {MODO} {L_MODO}, {MEMBER} {L_MEMBER} {L_AND} {GUEST} {L_GUEST}
					<br />
					{L_MEMBER} {L_ONLINE}: 
					# START online #		
						{online.ONLINE}
					# END online #
				</span>
				<span style="float:right;">
					<form action="topic{U_CHANGE_CAT}" method="post">
						<select name="change_cat" onchange="document.location = {U_ONCHANGE};">
							{SELECT_CAT}
						</select>
						<noscript>
							<input type="submit" name="valid_change_cat" value="Go" class="submit" />
						</noscript>
					</form>
				</span>
				<br /><br />
			</div>
		</div>
		