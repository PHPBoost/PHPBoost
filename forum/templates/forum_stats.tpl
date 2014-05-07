		# INCLUDE forum_top #
		
		<div class="module-position">					
			<div class="module-top-l"></div>		
			<div class="module-top-r"></div>
			<div class="module-top"><strong>{L_STATS}</strong></div>
			<div class="module-contents">
				<table>
					<thead>
						<tr>
							<th colspan="2">	
								{L_STATS}
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								{L_NBR_TOPICS}: <strong>{NBR_TOPICS}</strong><br />
								{L_NBR_MSG}: <strong>{NBR_MSG}</strong>
								<br /><br />
								{L_NBR_TOPICS_DAY}: <strong>{NBR_TOPICS_DAY}</strong><br />
								{L_NBR_MSG_DAY}: <strong>{NBR_MSG_DAY}</strong><br />
								{L_NBR_TOPICS_TODAY}: <strong>{NBR_TOPICS_TODAY}</strong><br />
								{L_NBR_MSG_TODAY}: <strong>{NBR_MSG_TODAY}</strong>
							</td>
						</tr>
					</tbody>
				</table>

				<br /><br />
				
				<table>
					<thead>
						<tr>
							<th colspan="2">	
								{L_LAST_MSG}
							</th>
						</tr>
					</thead>
					<tbody>
						# START last_msg #
						<tr>
							<td>
								<a href="{PATH_TO_ROOT}/forum/topic{last_msg.U_TOPIC_ID}">{last_msg.TITLE}</a>
							</td>
						</tr>
						# END last_msg #
					</tbody>
				</table>

				<br /><br />

				<table>
					<thead>
						<tr>
							<th colspan="2">	
								{L_POPULAR}
							</th>
						</tr>
					</thead>
					<tbody>
						# START popular #
						<tr>
							<td>
								<a href="{PATH_TO_ROOT}/forum/topic{popular.U_TOPIC_ID}">{popular.TITLE}</a>
							</td>
						</tr>
						# END popular #
					</tbody>
				</table>

				<br /><br />

				<table>
					<thead>
						<tr>
							<th colspan="2">	
								{L_ANSWERS}
							</th>
						</tr>
					</thead>
					<tbody>
						# START answers #
						<tr>
							<td>
								<a href="{PATH_TO_ROOT}/forum/topic{answers.U_TOPIC_ID}">{answers.TITLE}</a>
							</td>
						</tr>
						# END answers #
					</tbody>
				</table>
			</div>
			<div class="module-bottom-l"></div>		
			<div class="module-bottom-r"></div>
			<div class="module-bottom"></div>
		</div>
				
		# INCLUDE forum_bottom #