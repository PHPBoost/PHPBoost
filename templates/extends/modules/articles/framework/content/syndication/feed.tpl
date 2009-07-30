# START item #
<!-- ITEM -->
<div style="margin-bottom:2px;padding:2px;">
	<div style="float:left;width:75px;height:57px;text-align:center;border:1px solid #8883B9;">
		# IF item.C_IMG #
		<img src="{item.U_IMG}" alt="" style="border:1px solid #FFFFFF" />
		# ENDIF #
	</div>
	<div style="float:left;width:250px;padding-left:6px;">
	    <a href="{item.U_LINK}">{item.TITLE}</a>
	    <p class="text_small">{L_ON} {item.DATE} - <a href="{item.U_LINK}" class="small_link">{L_READ}</a></p>
	</div>
	<div class="spacer"></div>
</div>
<!-- END ITEM -->
# END item #