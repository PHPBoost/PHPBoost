
# INCLUDE online_table #

<script type="text/javascript">
new Ajax.PeriodicalUpdater('online_table', '../online/xmlhttprequest.php?page=1',
{
	method: 'get',
	frequency: {FREQUENCY},
	decay: 1
});
		
function ajax_display_desc(id)
{
	url = '../online/xmlhttprequest.php?display_desc='+id;
	
	$('l' + id).update('<img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />');

	new Ajax.Request(url,
		{
			method:'get',
			onSuccess: function(transport)
			{
				var tmp = $('td_'+id).getStyle('display');
				if(tmp!='none') tmp = 'none'; else tmp = 'block';
				$('td_'+id).setStyle({display:tmp});
				$('l'+id).update('');
			},
			onFailure: function()
			{
				$('l'+id).update('Error');
			}
		});
}

</script>