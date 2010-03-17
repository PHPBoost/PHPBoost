		<div id="online_mini" class="module_mini_container" style="display:none">
			<div class="module_mini_top">
				<h5 class="sub_title">{L_ONLINE}</h5>
			</div>

			# INCLUDE online_mini_body #
			
			<div class="module_mini_bottom">
			</div>
		</div>
		<script type="text/javascript">
		var ajax_online_mini = new Ajax.PeriodicalUpdater('online_mini_body', '../online/xmlhttprequest.php?mini=1',
		{
			method: 'get',
			frequency: {FREQUENCY},
			decay: 1
		});
		
		var i = window.location.href.indexOf('/online/online.php');
		if(i>=0)
		{
			ajax_online_mini.stop();
		}
		else
		{
			$('online_mini').setStyle({display:'block'});
		}
		</script>