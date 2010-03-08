		<div class="module_mini_container">
			<div class="module_mini_top">
				<h5 class="sub_title">{L_ONLINE}</h5>
			</div>

			# INCLUDE online_mini_body #
			
			<div class="module_mini_bottom">
			</div>
			<script type="text/javascript">
			new Ajax.PeriodicalUpdater('online_mini', '../online/xmlhttprequest.php?mini=1',
			{
				method: 'get',
				frequency: {FREQUENCY},
				decay: 1
			});
			</script>
		</div>
		