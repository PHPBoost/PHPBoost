		# INCLUDE forum_top #
		
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top"><strong>{L_STATS}</strong></div>
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
							<a href="{PATH_TO_ROOT}/forum/topic{last_msg.U_TOPIC_ID}">{last_msg.TITLE}</a>
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
							<a href="{PATH_TO_ROOT}/forum/topic{popular.U_TOPIC_ID}">{popular.TITLE}</a>
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
							<a href="{PATH_TO_ROOT}/forum/topic{answers.U_TOPIC_ID}">{answers.TITLE}</a>
						</td>
					</tr>
					# END answers #
				</table>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
		</div>
				
		# INCLUDE forum_bottom #
		