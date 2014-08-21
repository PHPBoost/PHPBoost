# IF HAS_TIME #
<meta http-equiv="refresh" content="{TIME};url=${U_LINK}">
# ENDIF #
<section>
	<header><h1>${escape(TITLE)}</h1></header>
	<div class="content">
		<div class="{ERROR_TYPE}">{MESSAGE}</div>
	    # IF HAS_LINK #
		<div style="padding:30px;text-align:center;">
			<strong><a href="{U_LINK}" title="${escape(LINK_NAME)}">${escape(LINK_NAME)}</a></strong>
		</div>
	    # ENDIF #
	</div>
	<footer></footer>	
</section>