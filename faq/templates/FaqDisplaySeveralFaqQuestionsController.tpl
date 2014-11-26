<section>
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('faq', ID_CAT))}" class="fa fa-syndication" title="${LangLoader::get_message('syndication', 'common')}"></a>
			# IF C_PENDING #{@web.pending}# ELSE #{@module_title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF #
		</h1>
		# IF C_CATEGORY_DESCRIPTION #
			<div class="spacer">&nbsp;</div>
			{CATEGORY_DESCRIPTION}
		# ENDIF #
		# IF C_SUB_CATEGORIES #
		<div class="spacer">&nbsp;</div>
		<hr />
		<div class="spacer">&nbsp;</div>
		<div class="cat">
			<div class="subcat">
				# START sub_categories_list #
				<div class="sub-category" style="width:{CATS_COLUMNS_WIDTH}%;">
					<a itemprop="about" href="{sub_categories_list.U_CATEGORY}"><img itemprop="thumbnailUrl" src="{sub_categories_list.CATEGORY_IMAGE}" alt="" /></a><br />
					<a itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME} ({sub_categories_list.QUESTIONS_NUMBER})</a><br />
					<span class="small">{sub_categories_list.CATEGORY_DESCRIPTION}</span>
				</div>
				# END sub_categories_list #
			</div>
		</div>
		<div class="spacer">&nbsp;</div>
		<hr />
		# ELSE #
		<div class="spacer">&nbsp;</div>
		# ENDIF #
	</header>
	<div class="content">
	# IF C_QUESTIONS #
		# IF C_DISPLAY_TYPE_INLINE #
		<script>
		<!--
			function show_answer(id_question)
			{
				if( document.getElementById("a" + id_question).style.display == "none" )
				{
					Effect.Appear("a" + id_question);
					document.getElementById("q" + id_question).className="fa fa-caret-down"
				}
				else
				{
					Effect.Fade("a" + id_question);
					document.getElementById("q" + id_question).className="fa fa-caret-right"
				}
			}
			
			Event.observe(window, 'load', function() {
				var anchor = window.location.hash;
				var id_question;
				 
				id_question = anchor.substring(2,anchor.length);
				if (anchor.substring(0,2) == "#q" && id_question.match(/^[0-9]+$/))
					show_answer(id_question);
			})
		-->
		</script>
		# ELSE #
		<ol>
		# START questions #
			<li>
				<a href="#q{questions.ID}">{questions.QUESTION}</a>
			</li>
		# END questions #
		</ol>
		<hr style="margin:20px 0;" />
		# ENDIF #
		
		# START questions #
		<article itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
			<header>
				<span>
					# IF C_DISPLAY_TYPE_INLINE #
					<a href="" id="q{questions.ID}" onclick="show_answer({questions.ID});return false;" title="" class="fa fa-caret-right"></a>
					<a href="" onclick="show_answer({questions.ID});return false;" title=""><span itemprop="name">{questions.QUESTION}</span></a>
					# ELSE #
					<i class="fa fa-caret-right"></i>
					<span id="q{questions.ID}" itemprop="name">{questions.QUESTION}</span>
					# ENDIF #
				</span>
				
				<span class="actions">
					<a href="{questions.U_LINK}" title="" class="fa fa-flag"></a>
					# IF questions.C_MODERATE #
						# IF C_MORE_THAN_ONE_QUESTION #
						<a href="{questions.U_UP}" title="{L_UP}" class="fa fa-arrow-up"></a>
						<a href="{questions.U_DOWN}" title="{L_DOWN}" class="fa fa-arrow-down"></a>
						# ENDIF #
					# ENDIF #
					# IF questions.C_EDIT #
					<a href="{questions.U_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="fa fa-edit"></a>
					# ENDIF #
					# IF questions.C_DELETE #
					<a href="{questions.U_DELETE}" title="${LangLoader::get_message('delete', 'main')}" class="fa fa-delete" data-confirmation="delete-element"></a>
					# ENDIF #
				</span>
				
				<meta itemprop="url" content="{questions.U_LINK}">
			</header>
			
			<div class="content">
				<div id="a{questions.ID}" class="blockquote"# IF C_DISPLAY_TYPE_INLINE # style="display: none;"# ENDIF #>
					<div itemprop="text">{questions.ANSWER}</div>
				</div>
			</div>
			
			<footer></footer>
		</article>
		# END questions #
	# ELSE #
		# IF NOT C_ROOT_CATEGORY #
		<div class="center">
			${LangLoader::get_message('no_item_now', 'common')}
		</div>
		# ENDIF #
	# ENDIF #
	</div>
	<footer></footer>
</section>