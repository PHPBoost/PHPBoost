<section id="module-pages">
	<header>
		<h1>{TITLE}</h1>
	</header>
	<div id="article-pages-{ID}" class="article-pages">
		<div class="controls">
			# IF C_ACTIV_COM #<a href="{U_COM}" aria-label="{L_COM}"><i class="fa fa-comments" aria-hidden="true"></i></a># ENDIF #
			# IF C_TOOLS_AUTH #
			<a href="{U_RENAME}" aria-label="{L_RENAME}"><i class="fa fa-magic" aria-hidden="true"></i></a>
			<a href="{U_EDIT}" aria-label="{L_EDIT}"><i class="fa fa-edit" aria-hidden="true"></i></a>
			<a href="{U_DELETE}" aria-label="{L_DELETE}" data-confirmation="delete-element"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
			# ENDIF #
			# IF C_PRINT #<a href="{U_PRINT}" aria-label="{L_PRINT}"><i class="fa fa-print" aria-hidden="true"></i></a># ENDIF #
		</div>
		<div class="content">
			# START redirect #
				<div class="options">
				{redirect.REDIRECTED_FROM} {redirect.DELETE_REDIRECTION}
				</div>
			# END redirect #

			<div class="spacer"></div>
				{CONTENTS}
			<div class="spacer"></div>
		</div>
		<aside>
			${ContentSharingActionsMenuService::display()}
		</aside>
	</div>
	<footer class="align-center"><span class="page-count-hit">{COUNT_HITS}</span></footer>
</section>
