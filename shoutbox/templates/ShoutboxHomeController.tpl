<!--<section id="module-shoutbox">
	<header>
		<h1>{@module_title}</h1>
	</header>
	<div class="content">
		# INCLUDE MSG #
		# INCLUDE FORM #

		# IF C_PAGINATION #
			<div class="center"># INCLUDE PAGINATION #</div>
			<div class="spacer"></div>
		# ENDIF #
		# IF C_NO_MESSAGE #
			<div class="message-helper notice message-helper-small center">${LangLoader::get_message('no_item_now', 'common')}</div>
		# ENDIF #
		# START messages #
			<article id="article-shoutbox-{messages.ID}" class="article-shoutbox article-several message">
				<header>
					<h2>${LangLoader::get_message('message', 'main')}</h2>
				</header>
				<div class="message-container">

					<div class="message-user-infos">
						<div class="message-pseudo">
							# IF messages.C_AUTHOR_EXIST #
							<a href="{messages.U_AUTHOR_PROFILE}" class="{messages.USER_LEVEL_CLASS}" # IF messages.C_USER_GROUP_COLOR # style="color:{messages.USER_GROUP_COLOR}" # ENDIF #>{messages.PSEUDO}</a>
							# ELSE #
							{messages.PSEUDO}
							# ENDIF #
						</div>
						# IF messages.C_AVATAR #<img src="{messages.U_AVATAR}" alt="${LangLoader::get_message('avatar', 'user-common')}" title="${LangLoader::get_message('avatar', 'user-common')}" class="message-avatar" /># ENDIF #
						# IF messages.C_USER_GROUPS #
							<div class="spacer"></div>
							# START messages.user_groups #
								# IF messages.user_groups.C_GROUP_PICTURE #
								<img src="{PATH_TO_ROOT}/images/group/{messages.user_groups.GROUP_PICTURE}" alt="{messages.user_groups.GROUP_NAME}" title="{messages.user_groups.GROUP_NAME}" class="message-user-group message-user-group-{messages.user_groups.GROUP_ID}" />
								# ELSE #
								${LangLoader::get_message('group', 'main')}: {messages.user_groups.GROUP_NAME}
								# ENDIF #
							# END user_groups #
						# ENDIF #
					</div>

					<div class="message-date">
						<span class="actions">
							# IF messages.C_EDIT #
							<a href="{messages.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true" title="${LangLoader::get_message('edit', 'common')}"></i></a>
							# ENDIF #
							# IF messages.C_DELETE #
							<a href="{messages.U_DELETE}" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete" aria-hidden="true" title="${LangLoader::get_message('delete', 'common')}"></i></a>
							# ENDIF #
						</span>
						<a href="{messages.U_ANCHOR}"><i class="fa fa-hand-o-right" aria-hidden="true"></i></a> ${LangLoader::get_message('the', 'common')} {messages.DATE}
					</div>

					<div class="message-message">
						<div class="message-content">{messages.CONTENTS}</div>
					</div>

				</div>
				<footer></footer>
			</article>
		# END messages #
	</div>
	<footer># IF C_PAGINATION #<div class="center"># INCLUDE PAGINATION #</div># ENDIF #</footer>
</section>
-->

<section class="download-or-create-container">
	<header><h1>Télécharger PHPBoostCMS</h1></header>
	<div id="download-or-create-buttons" class="content">
		<a href="" class="button-download">
			<div class="button-download-img"><img src="{PATH_TO_ROOT}/templates/base/theme/images/logo.png" /></div>
			<h2>PHPBoost CMS</h2>
			<span>Télécharger une archive de base contenant le minimum du framework</span>
		</a>
		<a href="" class="button-download">
			<div class="button-download-img"><i class="fa fa-cubes fa-2x"></i></div>
			<h2>Personnaliser votre archive</h2>
			<span>Télécharger une archive personnaliser contenant les élements de votre choix</span>
		</a>
	</div>
	<footer></footer>
</section>

<section id="create-archive-form" class="create-archive-container">
	<header> 
		<h2><i class="fa fa-cubes"></i> Créer votre propre archive PHPBoost en selectionnant les élements de votre choix</h2>
	</header>
	<div class="content">
		<div class="filter-container">
			<a href="" class="filter-button filter-button-modules">modules</a>
			<a href="" class="filter-button filter-button-templates">templates</a>
			<a href="" class="filter-button filter-button-langs">langues</a>
			
			<div class="filter-input-container">
				<label for="filter-input" class="filter-input-desc">Filtre</label>
				<input id="filter-input" type="text" class="filter-input"/>
			</div>

			<div class="filter-preselect-container">
				<a href="" class="filter-preselect">social</a>,
				<a href="" class="filter-preselect">rédaction</a> 
			</div>
		</div>
		
		<div class="list-elements-available">
			<a href="" class="seletable-element element-modules">
				<i class="fa fa-cubes"></i>
				<span>News</span><br />
				<span>Un module pour informer</span>
				<span class="data-for-filter filter-type">modules</span>
				<span class="data-for-filter filter-keywords">animation, redaction, communautaires</span>
			</a>
			<a href="" class="seletable-element element-templates">
				<i class="fa fa-cubes"></i>
				<span>Around the world</span><br />
				<span>Un thème pour voyager</span>
				<span class="data-for-filter filter-type">templates</span>
				<span class="data-for-filter filter-keywords">animation, redaction, communautaires</span>
			</a>

		</div>
		
		<div class="list-elements-selected-container">
			<div class="list-elements-selected element-modules">
				<a href><i class="fa fa-times"></i></a> <span>kernel</span>
			</div>
			<div class="list-elements-selected element-templates">
				<a href><i class="fa fa-times"></i></a> <span>default</span>
			</div>
			<div class="list-elements-selected element-langs">
				<a href><i class="fa fa-times"></i></a> <span>french</span>
			</div>
		</div>
	</div>
	<footer></footer>
</section>





<style>

.download-or-create-container {
	text-align: center;
}

.button-download {
	display: block;
	padding: 5px 10px;
	width: 420px;
	border: 1px solid #CCC;
	margin: 10px auto;
}

.button-download:hover {
	text-decoration: none;
}

.button-download-img {
	float : left;
	padding: 2px;
	margin: 0 10px 0 0;
	width: 32px;
}

.button-download h2 {
	font-size: 1.5em;
}

.button-download span {
	font-size: 0.6em;
	line-height: 1px;
	text-align: left;
}


.create-archive-container h2 {
	font-size: 1.2em;
	text-align: center;
}

.filter-container {
	margin: 20px 0 0 0;
	text-align: center;
}

.filter-button {
	display: inline-block;
	padding: 5px;
	margin: 5px;
	border: 1px solid #CCC;
}

.filter-input-container > label {
	background-color: #CCC;
	border: 1px solid #CCC;
	padding: 6px 10px 4px 8px;
	margin-right: -4px;
}

.filter-preselect-container {
	font-size: 0.8em;
	font-style: italic;
}


.list-elements-available {
	float: left;
	width: calc(100% - 100px);
}

.list-elements-selected-container {
	float: right;
	width: 100px;
}

.seletable-element {
	display: inline-block;
	border: 1px solid #CCC;
	padding: 5px;
	width: auto;
}

.seletable-element:hover {
	text-decoration: none;
}


.list-elements-selected {
	display: block;
	border: 1px solid #CCC;
	padding: 5px;
	height: 100px;
	overflow-y: auto;
	margin: 10px 0;
}

.data-for-filter {
	opacity: 0;
}

</style>