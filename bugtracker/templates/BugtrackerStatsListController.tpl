<script src="https://www.google.com/jsapi"></script>
<script>
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
	var status_chart_data = google.visualization.arrayToDataTable([
	  ['{@labels.fields.status}', ${TextHelper::to_js_string(LangLoader::get_message('items_number', 'common'))}],
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

<div class="spacer"></div>

<section class="block">
	<header>
		<h1>{@labels.fields.status}</h1>
	</header>
	<div class="content">
		<div class="align-center">
		# IF C_BUGS #
			<div id="status-chart"></div>
		# ELSE #
			<div class="message-helper bgc notice">{@notice.no_bug}</div>
		# ENDIF #
		</div>
	</div>
</section>

<div class="spacer"></div>

# IF C_DISPLAY_VERSIONS #
<section class="block">
	<header>
		<h1>{@labels.fix_bugs_per_version}</h1>
	</header>
	<div class="content">
		# IF C_FIXED_BUGS #
		<table class="table">
			<thead>
				<tr>
					<th>
						{@labels.fields.version}
					</th>
					<th>
						{@labels.fields.version_release_date}
					</th>
					<th>
						{@labels.number_fixed}
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
						# IF C_ROADMAP_ENABLED #<a href="{fixed_version.LINK_VERSION_ROADMAP}">{fixed_version.NUMBER}</a># ELSE #{fixed_version.NUMBER}# ENDIF #
					</td>
				</tr>
				# END fixed_version #
			</tbody>
		</table>
		# ELSE #
		<div class="message-helper bgc notice">{@notice.no_bug_solved}</div>
		# ENDIF #
	</div>
</section>

<div class="spacer"></div>
# ENDIF #

# IF C_DISPLAY_TOP_POSTERS #
<section class="block">
	<header>
		<h1>{@labels.top_posters}</h1>
	</header>
	<div class="spacer"></div>
	<div class="content">
		# IF C_POSTERS #
		<table class="table">
			<thead>
				<tr>
					<th>
						N&deg;
					</th>
					<th>
						{@labels.login}
					</th>
					<th>
						${LangLoader::get_message('items_number', 'common')}
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
						# IF top_poster.AUTHOR #<a href="{top_poster.LINK_AUTHOR_PROFILE}" class="{top_poster.AUTHOR_LEVEL_CLASS}" # IF top_poster.C_AUTHOR_GROUP_COLOR # style="color:{top_poster.AUTHOR_GROUP_COLOR}" # ENDIF #>{top_poster.AUTHOR}</a># ELSE #${LangLoader::get_message('visitor', 'user-common')}# ENDIF #
					</td>
					<td>
						{top_poster.USER_BUGS}
					</td>
				</tr>
				# END top_poster #
			</tbody>
		</table>
		# ELSE #
		<div class="message-helper bgc notice">{@notice.no_bug}</div>
		# ENDIF #
	</div>
</section>
# ENDIF #
