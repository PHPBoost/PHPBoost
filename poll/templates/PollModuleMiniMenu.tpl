<div class="cell-body" id="poll-mini">
	# IF NOT IS_USER_CONNECTED #
		# IF C_DISPLAYING_POLLS_MAP #
			<div class="cell-content">
				# IF C_MULTIPLE_POLL_ITEMS #
					{@poll.mini.participate.multiple}
				# ELSE #
					{@poll.mini.participate.single}
				# ENDIF #
			</div>
			<div class="cell-list">
				<ul>
					# START polls_map #
						<li>
							<a class="offload" href="{polls_map.U_ITEM}">
								<span>{polls_map.TITLE}</span>
							</a>
						</li>
					# END polls_map #
				</ul>
			</div>
		# ELSE #
			<div class="cell-content">{@poll.mini.no.poll.available}</div>
		# ENDIF #
	# ELSE #
		<script>
			function poll_submit(){
				var sendFormData = $("#poll_vote_form").serialize();

				$.ajax({
					beforeSend : function(){
						$("#pollmini_loading").css("display", "block");
					},
					url: '${relative_url(PollUrlBuilder::ajax_send())}',
					type: 'post',
					data: {
						'sendFormData' : sendFormData + '&poll_vote_form_submit=on',
						'token' : '{TOKEN}'
					},
					dataType: 'json',
					success: function(returnData) {
						if (returnData.validated >= 0) {
							$("#poll-mini").html(returnData.html);
						}
						else {
							if(returnData.message) {
								alert(returnData.message);
							}
						}
					}
				});
			}

			function poll_mini_previous_nav(){
				if ($('#poll_vote_form_previous').length > 0)
					var previous_id = parseInt($("#poll_vote_form_previous").text());

				$.ajax({
					beforeSend : function(){
						$("#pollmini_loading").css("visibility", "visible");
					},
					url: '${relative_url(PollUrlBuilder::ajax_send())}',
					type: 'post',
					data: {
						'sendNavData_previous' : previous_id,
						'token' : '{TOKEN}'
					},
					dataType: 'json',
					success: function(returnData) {
						if(returnData.validated >= 0) {
							$("#poll-mini").html(returnData.html);
						}
						else {
							if(returnData.message) {
								alert(returnData.message);
							}
						}
					}
				});
			}

			function poll_mini_next_nav(){
				if ($('#poll_vote_form_next').length > 0)
					var next_id = parseInt($("#poll_vote_form_next").text());

				$.ajax({
					beforeSend : function(){
						$("#pollmini_loading").css("visibility", "visible");
					},
					url: '${relative_url(PollUrlBuilder::ajax_send())}',
					type: 'post',
					data: {
						'sendNavData_next' : next_id,
						'token' : '{TOKEN}'
					},
					dataType: 'json',
					success: function(returnData) {
						if(returnData.validated >= 0) {
							$("#poll-mini").html(returnData.html);
						}
						else {
							if(returnData.message) {
								alert(returnData.message);
							}
						}
					}
				});
			}

		</script>
		# INCLUDE POLL_MINI_MSG #
		<div id="pollmini_loading">
			<div id="pollmini_loading_1" class="pollmini_loadingprogress"></div>
		</div>
		# IF C_ENABLED_COUNTDOWN #
			<div class="cell-content"># INCLUDE COUNTDOWN #</div>
		# ENDIF #
		# IF C_VOTE_FORM_AND_RESULTS #
			# INCLUDE VOTE_FORM #
			<div class="cell-content">
				# INCLUDE VOTES_RESULT #
			</div>
		# ELSE #
			<div class="cell-content">{@poll.mini.no.poll.available}</div>
		# ENDIF #
	# ENDIF #
	<div class="cell-footer align-center">
		<a class="button small offload" href="${relative_url(ModulesUrlBuilder::home('poll'))}">{@poll.mini.more}</a>
	</div>
</div>
