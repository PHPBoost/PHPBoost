<section id="module-pages">
	<header>
		<h1>{TITLE}</h1>
	</header>
	<div id="article-pages-{ID}" class="article-pages">
		<div class="actions">
			# IF C_ACTIV_COM #<a href="{U_COM}" aria-label="{L_COM}"><i class="fa fa-comments-o" title="{L_COM}"></i></a># ENDIF #
			# IF C_TOOLS_AUTH #
			<a href="{U_RENAME}" aria-label="{L_RENAME}"><i class="fa fa-magic" title="{L_RENAME}"></i></a>
			<a href="{U_EDIT}" aria-label="{L_EDIT}"><i class="fa fa-edit" title="{L_EDIT}"></i></a>
			<a href="{U_DELETE}" aria-label="{L_DELETE}" data-confirmation="delete-element"><i class="fa fa-delete" title="{L_DELETE}"></i></a>
			# ENDIF #
			# IF C_PRINT #<a href="{U_PRINT}" aria-label="{L_PRINT}"><i class="fa fa-print" title="{L_PRINT}"></i></a># ENDIF #
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
	<footer class="center"><span class="page-count-hit">{COUNT_HITS}</span></footer>
</section>
