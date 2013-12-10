<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
	var status_chart_data = google.visualization.arrayToDataTable([
	  ['{@bugs.labels.fields.status}', '{@bugs.labels.number}'],
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

<section class="block" style="background:#FFF">
	<header>
		<h1>{@bugs.labels.fields.status}</h1>
	</header>
	<div class="content">
		# IF C_BUGS #
		<div class="center">
			<div id="status_chart"></div>
		</div>
		# ELSE #
		<div class="center">
			{@bugs.notice.no_bug}
		</div>
		# ENDIF #
	</div>
</table>
</section>

<div class="spacer">&nbsp;</div>

# IF C_DISPLAY_VERSIONS #
<table>
	<thead>
		<tr>
			<th class="column_version">
				{@bugs.labels.fields.version}
			</th>
			<th>
				{@bugs.labels.number_fixed}
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
				<a href="{fixed_version.LINK_VERSION_ROADMAP}">{fixed_version.NUMBER}</a>
			</td>
		</tr>
		# END fixed_version #
		# IF NOT C_FIXED_BUGS #
		<tr> 
			<td colspan="2">
				{@bugs.notice.no_bug_solved}
			</td>
		</tr>
		# ENDIF #
	</tbody>
</table>

<div class="spacer">&nbsp;</div>
# ENDIF #

# IF C_DISPLAY_TOP_POSTERS #
<section class="block" style="background:#FFF">
	<header>
		<h1>{@bugs.labels.top_posters}</h1>
	<header>
	<div class="spacer">&nbsp;</div>
	<div class="content">
		<table>
			<thead>
				<tr>
					<th>
						N&deg;
					</th>
					<th>
						{@bugs.labels.login}
					</th>
					<th>
						{@bugs.labels.number}
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
						# IF top_poster.AUTHOR #<a href="{top_poster.LINK_AUTHOR_PROFILE}" class="small_link {top_poster.AUTHOR_LEVEL_CLASS}" # IF top_poster.C_AUTHOR_GROUP_COLOR # style="color:{top_poster.AUTHOR_GROUP_COLOR}" # ENDIF #>{top_poster.AUTHOR}</a># ELSE #{L_GUEST}# ENDIF #
					</td>
					<td>
						{top_poster.USER_BUGS}
					</td>
				</tr>
				# END top_poster #
				# IF NOT C_POSTERS #
				<tr> 
					<td colspan="3">
						{@bugs.notice.no_bug}
					</td>
				</tr>
				# ENDIF #
			</tbody>
		</table>
	</div>
</section>
# ENDIF #
