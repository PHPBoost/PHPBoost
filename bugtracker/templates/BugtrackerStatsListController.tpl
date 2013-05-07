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

<table class="module_table">
	<tr>
		<th colspan="2">
			{@bugs.labels.fields.status}
		</th>
	</tr>
	<tr class="text_center">
		# IF C_BUGS #
		<td class="row2 chart">
			<div id="status_chart"></div>
		</td>
		# ELSE #
		<td class="row2">
			{@bugs.notice.no_bug}
		</td>
		# ENDIF #

</table>
<br /><br />
# IF C_DISPLAY_VERSIONS #
<table class="module_table">
	<tr class="text_center">
		<th class="column_version">
			{@bugs.labels.fields.version}
		</th>
		<th>
			{@bugs.labels.number_fixed}
		</th>
	</tr>
	# START fixed_version #
	<tr class="text_center"> 
		<td class="row2"> 
			{fixed_version.NAME}
		</td>
		<td class="row2"> 
			<a href="{fixed_version.LINK_VERSION_ROADMAP}">{fixed_version.NUMBER}</a>
		</td>
	</tr>
	# END fixed_version #
	# IF NOT C_FIXED_BUGS #
	<tr class="text_center"> 
		<td colspan="2" class="row2">
			{@bugs.notice.no_bug_solved}
		</td>
	</tr>
	# ENDIF #
</table>
<br /><br />
# ENDIF #
# IF C_DISPLAY_TOP_POSTERS #
<table class="module_table">
	<tr>
		<th colspan="3">
			{@bugs.labels.top_posters}
		</th>
	</tr>
	<tr class="text_center">
		<td class="row1">
			N&deg;
		</td>
		<td class="row1">
			{@bugs.labels.login}
		</td>
		<td class="row1">
			{@bugs.labels.number}
		</td>
	</tr>
	# START top_poster #
	<tr class="text_center">
		<td class="row2">
			{top_poster.ID}
		</td>
		<td class="row2">
			<a href="{top_poster.LINK_USER_PROFILE}">{top_poster.LOGIN}</a>
		</td>
		<td class="row2">
			{top_poster.USER_BUGS}
		</td>
	</tr>
	# END top_poster #
	# IF NOT C_BUGS_NOT_REJECTED #
	<tr class="text_center"> 
		<td colspan="3" class="row2">
			{@bugs.notice.no_bug}
		</td>
	</tr>
	# ENDIF #
</table>
# ENDIF #
