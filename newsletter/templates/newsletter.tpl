		# START arch_title #
		
		<script type="text/javascript">
		<!--
		function popup(page,name)
		{
		   var screen_height = screen.height;
		   var screen_width = screen.width;

			if( screen_height == 600 && screen_width == 800 )
				window.open(page, name, "width=680, height=540,location=no,status=no,toolbar=no,scrollbars=yes");
			else if( screen_height == 768 && screen_width == 1024 )
				window.open(page, name, "width=672, height=620,location=no,status=no,toolbar=no,scrollbars=yes");
			else if( screen_height == 864 && screen_width == 1152 )
				window.open(page, name, "width=672, height=620,location=no,status=no,toolbar=no,scrollbars=yes");
			else
				window.open(page, name, "width=672, height=620,location=no,status=no,toolbar=no,scrollbars=yes");
		}
		//-->
		</script>
		
		<table class="module_table">
			<tr>
				<th>
					{L_NEWSLETTER_ARCHIVES}
				</th>
			</tr>
			<tr>
				<td class="row2">
					{L_NEWSLETTER_ARCHIVES_EXPLAIN}
				</td>
			</tr>
		</table>
		
		<br /><hr style="width:70%; margin:auto;" /><br />
		
		# END arch_title #
		
		# INCLUDE message_helper #
		
		# START arch #
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<span class="text_strong" style="float:left;">{arch.TITLE}</span>
				<span class="text_small" style="float: right;">{arch.DATE}</span>
			</div>
			<div class="module_contents">
				&nbsp;&nbsp;{arch.MESSAGE}
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<div style="float:right">
					{arch.NBR_SENT_NEWSLETTERS}
				</div>
			</div>
		</div>
		<br /><br />
		# END arch #

		<div style="text-align:center;">{PAGINATION}</div>


		# START mail #
			<p style="text-align:center;" class="text_subtitle">
				{mail.MSG}
			</p>
		# END mail #
		