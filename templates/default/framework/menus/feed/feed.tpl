<div class="block">
	<h3>
		<a href="{U_LINK}">
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="" style="vertical-align:middle;" />
			# IF C_NAME #{NAME}# ELSE #{TITLE}# ENDIF #
		</a>
	</h3>
	<ul style="text-align:left;margin-left:20px;">
	    # START item #
    	<li><span class="text_small">{item.DATE}</span> <a href="{item.U_LINK} ">{item.TITLE} </a></li>
	    # END item #
	</ul>
</div>