<section>
	# INCLUDE SANDBOX_SUBMENU #
	<header>
		<h1>
			{@sandbox.module.title} - {@title.bbcode}
		</h1>
	</header>

	{@H|bbcode.explain}

	# INCLUDE TYPOGRAPHY #

	# INCLUDE BLOCKS #

	# INCLUDE CODE #

	# INCLUDE LIST #

	# INCLUDE TABLE #

	<div id="bbcode-wiki" class="sandbox-block">
		<h2>{@wiki.module}</h2>
		# IF C_WIKI #
			<article>
				<p class="message-helper bgc notice">{@wiki.conditions}</p>
				<div class="content">
					# START wikimenu #
						<div class="wiki-summary">
							<div class="wiki-summary-title">{@wiki.table.of.contents}</div>
							{wikimenu.MENU}
						</div>
					# END wikimenu #
					{WIKI_CONTENTS}
				</div>
			</article>
		# ELSE #
		 	{@wiki.not}
		# ENDIF #
	</div>

	<footer></footer>
</section>
<script src="{PATH_TO_ROOT}/sandbox/templates/js/sandbox.js"></script>
