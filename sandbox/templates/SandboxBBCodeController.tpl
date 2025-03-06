<section id="module-sandbox-bbcode">
	<header class="section-header">
		<h1>
			{@sandbox.module.title} - {@sandbox.bbcode}
		</h1>
	</header>

	# INCLUDE SANDBOX_SUBMENU #

	<div class="sub-section"><div class="content-container"><div class="content">{@H|sandbox.bbcode.description}</div></div></div>

	<div class="sub-section"><div class="content-container"><div class="content"># INCLUDE TYPOGRAPHY #</div></div></div>

	<div class="sub-section"><div class="content-container"><div class="content"># INCLUDE BLOCKS #</div></div></div>

	<div class="sub-section"><div class="content-container"><div class="content"># INCLUDE CODE #</div></div></div>

	<div class="sub-section"><div class="content-container"><div class="content"># INCLUDE LIST #</div></div></div>

	<div class="sub-section"><div class="content-container"><div class="content"># INCLUDE TABLE #</div></div></div>

	<div class="sub-section">
		<div class="content-container">
			<div class="content">
				<div id="bbcode-wiki" class="sandbox-block">
					<h2>{@sandbox.bbcode.wiki.module}</h2>
					# IF C_WIKI #
						<article>
							<p class="message-helper bgc notice">{@sandbox.bbcode.wiki.conditions}</p>
							<div class="content">
								# START wikimenu #
									<div class="wiki-summary">
										<div class="wiki-summary-title">{@sandbox.bbcode.wiki.table.of.contents}</div>
										{wikimenu.MENU}
									</div>
								# END wikimenu #
								{WIKI_CONTENTS}
							</div>
						</article>
					# ELSE #
						{@sandbox.bbcode.wiki.disabled}
					# ENDIF #
				</div>
			</div>
		</div>
	</div>
	<footer></footer>
</section>
