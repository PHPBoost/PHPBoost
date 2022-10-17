<script src="https://www.google.com/jsapi"></script>
<script>
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var status_chart_data = google.visualization.arrayToDataTable([
            ['${escapejs(@common.status)}, ${TextHelper::to_js_string(@common.items.number)}],
            # START status #
                ['{status.NAME}',     {status.NUMBER}],
            # END status #
        ]);

        var status_chart_options = {
            title: '',
            is3D: 'true',
            pieSliceText: 'none'
        };

        var status_chart = new google.visualization.PieChart(document.getElementById('status-chart'));
        status_chart.draw(status_chart_data, status_chart_options);
    }
</script>

<article class="bugtracker-item several-items">
	<header>
		<h2>{@common.status}</h2>
	</header>
	<div class="content align-center">
		# IF C_BUGS #
			<div id="status-chart"></div>
		# ELSE #
			<div class="message-helper bgc notice">{@common.no.item.now}</div>
		# ENDIF #
	</div>
</article>

# IF C_DISPLAY_VERSIONS #
    <article class="bugtracker-item several-items">
		<header>
			<h2>{@bugtracker.solved.per.version}</h2>
		</header>
		# IF C_FIXED_BUGS #
			<table class="table">
				<thead>
					<tr>
						<th class="col-25">
							{@common.version}
						</th>
						<th>
							{@bugtracker.version.release.date}
						</th>
						<th class="col-large">
							{@bugtracker.items.number}
						</th>
					</tr>
				</thead>
				<tbody>
					# START fixed_version #
						<tr>
							<td>
								{fixed_version.NAME}
							</td>
							<td>
								{fixed_version.RELEASE_DATE}
							</td>
							<td>
								# IF C_ROADMAP_ENABLED #<a class="offload" href="{fixed_version.LINK_VERSION_ROADMAP}">{fixed_version.NUMBER}</a># ELSE #{fixed_version.NUMBER}# ENDIF #
							</td>
						</tr>
					# END fixed_version #
				</tbody>
			</table>
		# ELSE #
			<div class="message-helper bgc notice">{@common.no.item.now}</div>
		# ENDIF #
	</article>
	# ENDIF #

	# IF C_DISPLAY_TOP_POSTERS #
	<article class="bugtracker-item several-items">
		<header>
			<h2>{@bugtracker.top.contributors}</h2>
		</header>
		# IF C_POSTERS #
			<table class="table">
				<thead>
					<tr>
						<th class="col-25">
							N&deg;
						</th>
						<th>
							{@common.author}
						</th>
						<th class="col-large">
							{@bugtracker.items.number}
						</th>
					</tr>
				</thead>
				<tbody>
					# START top_poster #
					<tr>
						<td>
							{top_poster.ID}
						</td>
						<td>
							# IF top_poster.AUTHOR #<a itemprop="author" href="{top_poster.LINK_AUTHOR_PROFILE}" class="{top_poster.AUTHOR_LEVEL_CLASS} offload" # IF top_poster.C_AUTHOR_GROUP_COLOR # style="color:{top_poster.AUTHOR_GROUP_COLOR}" # ENDIF #>{top_poster.AUTHOR}</a># ELSE #{@user.guest}# ENDIF #
						</td>
						<td>
							{top_poster.USER_BUGS}
						</td>
					</tr>
					# END top_poster #
				</tbody>
			</table>
		# ELSE #
			<div class="message-helper bgc notice">{@common.no.item.now}</div>
		# ENDIF #
    </article>
# ENDIF #
