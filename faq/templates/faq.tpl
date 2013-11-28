<script type="text/javascript">
<!--
	function show_answer(id_question)
	{
		if( document.getElementById("a" + id_question).style.display == "none" )
		{
			Effect.Appear("a" + id_question);
			document.getElementById("faq_i" + id_question).className="icon-caret-down icon-2x"
		}
		else
		{
			Effect.Fade("a" + id_question);
			document.getElementById("faq_i" + id_question).className="icon-caret-right icon-2x"
		}
	}
-->
</script>

<section>
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('faq', ID_FAQ))}" class="icon-syndication" title="${LangLoader::get_message('syndication', 'main')}"></a>
			{TITLE}
			# IF C_ADMIN #
			<span class="actions">
				<a href="{U_ADMIN_CAT}" title="${LangLoader::get_message('edit', 'main')}" class="icon-edit"></a>
			</span>
			# ENDIF #
		</h1>
	</header>
	<div class="content">
		# START description #
			{description.DESCRIPTION}
			<hr style="margin-top:25px;" />
		# END description #
		
		# IF C_FAQ_CATS #
			# START row #
				# START row.list_cats #
					<div style="float:left;width:{row.list_cats.WIDTH}%;text-align:center;margin:20px 0px;">
						# IF C_CAT_IMG #
							<a href="{row.list_cats.U_CAT}" title="{row.list_cats.IMG_NAME}"><img src="{row.list_cats.SRC}" alt="{row.list_cats.IMG_NAME}" /></a>
							<br />
						# ENDIF #
						<a href="{row.list_cats.U_CAT}">{row.list_cats.NAME}</a>
						# IF C_ADMIN #
						<a href="{row.list_cats.U_ADMIN_CAT}" title="${LangLoader::get_message('edit', 'main')}" class="icon-edit"></a>
						# ENDIF #
						<div class="smaller">
							{row.list_cats.NUM_QUESTIONS}
						</div>
					</div>
				# END row.list_cats #
				<div class="spacer">&nbsp;</div>
			# END row #
			<hr style="margin-bottom:25px;" />
		# ENDIF #		
		
		# START questions #
			# START questions.faq #
				<div style="margin-top:15px;" id="q{questions.faq.ID_QUESTION}">
					<div class="row1">
						<span style="float:left;">
							<a href="javascript:show_answer({questions.faq.ID_QUESTION});" >
								<i id="faq_i{questions.faq.ID_QUESTION}" class="icon-caret-right icon-2x"></i>
							</a>
							<a id="faq_l{questions.faq.ID_QUESTION}" href="{questions.faq.U_QUESTION}">{questions.faq.QUESTION}</a>
							<script type="text/javascript">
							<!--
								document.getElementById("faq_l{questions.faq.ID_QUESTION}").href = 'javascript:show_answer({questions.faq.ID_QUESTION});';
								
							-->
							</script>
						</span>
						<span style="float:right;">
							<a href="{questions.faq.U_QUESTION}" title="{L_QUESTION_URL}" class="icon-flag"></a>
							# IF C_ADMIN_TOOLS #
								<a href="{questions.faq.U_MOVE}" title="{L_MOVE}" class="icon-move"></a>
								# START questions.faq.up #
									<a href="{questions.faq.U_UP}" title="{L_UP}" class="icon-arrow-up"></a>
								# END questions.faq.up #
								# START questions.faq.down #
									<a href="{questions.faq.U_DOWN}" title="{L_DOWN}" class="icon-arrow-down"></a>
								# END questions.faq.down #
								<a href="{questions.faq.U_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="icon-edit"></a>
								<a href="{questions.faq.U_DEL}" title="${LangLoader::get_message('delete', 'main')}" class="icon-delete" data-confirmation="delete-element"></a>
								# ENDIF #
						</span>
						<div style="clear:both"></div>
					</div>
					<br />
					<div id="a{questions.faq.ID_QUESTION}" class="blockquote">
						<div>{questions.faq.ANSWER}</div>
					</div>
					# IF questions.faq.C_HIDE_ANSWER #
					<script type="text/javascript">
						document.getElementById("a{questions.faq.ID_QUESTION}").style.display = "none";
						document.getElementById("faq_i{questions.faq.ID_QUESTION}").className="icon-caret-right icon-2x";
					</script>
					# ENDIF #
					# IF questions.faq.C_SHOW_ANSWER #
					<script type="text/javascript">
						document.getElementById("faq_i{questions.faq.ID_QUESTION}").className="icon-caret-down icon-2x";
					</script>
					# ENDIF #		
				</div>
			# END questions.faq #
		# END questions #
		
		# START questions_block #		
			<ol class="bb_ol">
			# START questions_block.header #
				<li>
					<a href="#q{questions_block.header.ID}">{questions_block.header.QUESTION}</a>
				</li>
			# END questions_block.header #
			</ol>			
			<hr style="margin:20px 0;" />			
			# START questions_block.contents #
				<div class="row1" id="q{questions_block.contents.ID}">
					<span style="float:left;">
						<i class="icon-caret-right icon-2x"></i>
						{questions_block.contents.QUESTION}
					</span>
					<span class="row2" style="float:right;">
						<a href="{questions_block.contents.U_QUESTION}" title="{L_QUESTION_URL}" class="icon-flag"></a>
						# IF C_ADMIN_TOOLS #
							<a href="{questions_block.contents.U_MOVE}" title="{L_MOVE}" class="icon-move"></a>
							# START questions_block.contents.up #
							<a href="{questions_block.contents.U_UP}" title="{L_UP}" class="icon-arrow-up"></a>
							# END questions_block.contents.up #
							# START questions_block.contents.down #
								<a href="{questions_block.contents.U_DOWN}" title="{L_DOWN}" class="icon-arrow-down"></a>
							# END questions_block.contents.down #
							<a href="{questions_block.contents.U_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="icon-edit"></a>
							<a href="{questions_block.contents.U_DEL}" title="${LangLoader::get_message('delete', 'main')}" class="icon-delete" data-confirmation="delete-element"></a>
							# ENDIF #
					</span>
					<div style="clear:both"></div>
				</div>
				<br />
				<div class="blockquote" style="margin-bottom:30px;">
					{questions_block.contents.ANSWER}
				</div>	
			# END questions_block.contents #		
		# END questions_block #
		
		# START no_question #
		<p class="center">{L_NO_QUESTION_THIS_CATEGORY}</p>
		# END no_question #
		<div class="spacer"></div>
	</div>
	<footer></footer>
</section>