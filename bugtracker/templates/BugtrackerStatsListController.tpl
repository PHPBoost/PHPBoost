<script src="https://www.google.com/jsapi"></script>
<script>
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
	var status_chart_data = google.visualization.arrayToDataTable([
	  ['{@labels.fields.status}', '{@labels.number}'],
	  # START status #
	  ['{status.NAME}',     {status.NUMBER}],
	  # END status #
	]);

	var status_chart_options = {
	  title: '',
	  is3D: 'true',
	  pieSliceText: 'none'
	};

	var status_chart = new google.visualization.PieChart(document.getElementById('status_chart'));
	status_chart.draw(status_chart_data, status_chart_options);
  }
</script>

<div class="spacer">&nbsp;</div>

<section class="block" style="background:#FFF">
	<header>
		<h1>{@labels.fields.status}</h1>
	</header>
	<div class="content">
		<div class="center">
		# IF C_BUGS #
			<div id="status_chart"></div>
		# ELSE #
			<div class="message-helper notice">
			<i class="fa fa-notice"></i>
			<div class="message-helper-content">{@notice.no_bug}</div>
			</div>
		# ENDIF #
		</div>
	</div>
</section>

<div class="spacer">&nbsp;</div>

# IF C_DISPLAY_VERSIONS #
<section class="block" style="background:#FFF">
	<header>
		<h1>{@labels.fix_bugs_per_version}</h1>
	</header>
	<div class="content">
		# IF C_FIXED_BUGS #
		<table>
			<thead>
				<tr>
					<th>
						{@labels.fields.version}
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
						# IF C_ROADMAP_ENABLED #<a href="{fixed_version.LINK_VERSION_ROADMAP}">{fixed_version.NUMBER}</a># ELSE #{fixed_version.NUMBER}# ENDIF #
					</td>
				</tr>
				# END fixed_version #
			</tbody>
		</table>
		# ELSE #
		<div class="message-helper notice">
		<i class="fa fa-notice"></i>
		<div class="message-helper-content">{@notice.no_bug_solved}</div>
		</div>
		# ENDIF #
	</div>
</section>

<div class="spacer">&nbsp;</div>
# ENDIF #

# IF C_DISPLAY_TOP_POSTERS #
<section class="block" style="background:#FFF">
	<header>
		<h1>{@labels.top_posters}</h1>
	<header>
	<div class="spacer">&nbsp;</div>
	<div class="content">
		# IF C_POSTERS #
		<table>
			<thead>
				<tr>
					<th>
						N&deg;
					</th>
					<th>
						{@labels.login}
					</th>
					<th>
						{@labels.number}
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
						# IF top_poster.AUTHOR #<a href="{top_poster.LINK_AUTHOR_PROFILE}" class="small {top_poster.AUTHOR_LEVEL_CLASS}" # IF top_poster.C_AUTHOR_GROUP_COLOR # style="color:{top_poster.AUTHOR_GROUP_COLOR}" # ENDIF #>{top_poster.AUTHOR}</a># ELSE #${LangLoader::get_message('guest', 'main')}# ENDIF #
					</td>
					<td>
						{top_poster.USER_BUGS}
					</td>
				</tr>
				# END top_poster #
			</tbody>
		</table>
		# ELSE #
		<div class="message-helper notice">
		<i class="fa fa-notice"></i>
		<div class="message-helper-content">{@notice.no_bug}</div>
		</div>
		# ENDIF #
	</div>
</section>
# ENDIF #
