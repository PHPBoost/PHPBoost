
		# INCLUDE online_table #

		<script type="text/javascript">
		new Ajax.PeriodicalUpdater('online_table', '../online/xmlhttprequest.php?page=1',
		{
			method: 'get',
			frequency: {FREQUENCY},
			decay: 1
		});
		</script>