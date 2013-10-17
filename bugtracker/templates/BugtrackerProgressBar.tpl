<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="{PATH_TO_ROOT}/bugtracker/js/jquery.progressbar.min.js"></script>
<script type="text/javascript">
<!--
	var $jQ = jQuery.noConflict();
	
	$jQ(document).ready(function() {
		$jQ(".progress0").progressBar(0, { boxImage: '{PATH_TO_ROOT}/bugtracker/templates/images/progressbar.gif', barImage: '{PATH_TO_ROOT}/bugtracker/templates/images/progressbg_black.gif'} );
		for (i = 1; i < 30; i++) 
		{
			$jQ(".progress" + i).progressBar(i, { boxImage: '{PATH_TO_ROOT}/bugtracker/templates/images/progressbar.gif', barImage: '{PATH_TO_ROOT}/bugtracker/templates/images/progressbg_red.gif'} );
		}
		for (i = 30; i < 50; i++) 
		{
			$jQ(".progress" + i).progressBar(i, { boxImage: '{PATH_TO_ROOT}/bugtracker/templates/images/progressbar.gif', barImage: '{PATH_TO_ROOT}/bugtracker/templates/images/progressbg_orange.gif'} );
		}
		for (i = 50; i < 90; i++) 
		{
			$jQ(".progress" + i).progressBar(i, { boxImage: '{PATH_TO_ROOT}/bugtracker/templates/images/progressbar.gif', barImage: '{PATH_TO_ROOT}/bugtracker/templates/images/progressbg_yellow.gif'} );
		}
		for (i = 90; i <= 100; i++) 
		{
			$jQ(".progress" + i).progressBar(i, { boxImage: '{PATH_TO_ROOT}/bugtracker/templates/images/progressbar.gif', barImage: '{PATH_TO_ROOT}/bugtracker/templates/images/progressbg_green.gif'} );
		}
	});
-->
</script>