		<script type="text/javascript">
		<!--
			theme = "{THEME}";
			PICTURES_DATA_PATH = "{PICTURES_DATA_PATH}";
			function show_answer(id_question)
			{
				if( document.getElementById("a" + id_question).style.display == "none" )
				{
					Effect.Appear("a" + id_question);
					document.getElementById("faq_i" + id_question).src = "{PICTURES_DATA_PATH}/images/opened_line.png";
				}
				else
				{
					Effect.Fade("a" + id_question);
					document.getElementById("faq_i" + id_question).src = "{PICTURES_DATA_PATH}/images/line.png";
				}
			}
		-->
		</script>

		<div class="module_position">			
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				{TITLE}
				# IF C_ADMIN #
				<a href="{U_ADMIN_CAT}">
					<img class="valign_middle" src="../templates/{THEME}/images/{LANG}/edit.png" alt="" />
				</a>
				# END IF #
			</div>
			<div class="module_contents">
			
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
								<a href="{row.list_cats.U_ADMIN_CAT}">
									<img class="valign_middle" src="../templates/{THEME}/images/{LANG}/edit.png" alt="" />
								</a>
								# ENDIF #
								<div class="text_small">
									{row.list_cats.NUM_QUESTIONS}
								</div>
							</div>
						# END row.list_cats #
						<div class="spacer">&nbsp;</div>
					# END row #
					<hr style="margin-bottom:25px;" />
				# ENDIF #		
				
				# START management #
					<div style="text-align:center; margin:10px;">
						<a href="{U_MANAGEMENT}">
							<img src="{PICTURES_DATA_PATH}/images/category_management.png" alt="{L_CATEGORY_MANAGEMENT}" title="{L_CAT_MANAGEMENT}" />
						</a>
						<br />
						<a href="{U_MANAGEMENT}">{L_CAT_MANAGEMENT}</a>
					</div>
				# END management #
				
				# START questions #
					# START questions.faq #
						<div style="margin-top:15px;" id="q{questions.faq.ID_QUESTION}">
							<div class="row1">
								<span style="float:left;">
									<a href="javascript:show_answer({questions.faq.ID_QUESTION});"><img src="{PICTURES_DATA_PATH}/images/line.png" alt="arrow" id="faq_i{questions.faq.ID_QUESTION}" class="image_left" style="vertical-align:middle;" /></a>
									<a id="faq_l{questions.faq.ID_QUESTION}" href="{questions.faq.U_QUESTION}">{questions.faq.QUESTION}</a>
									<script type="text/javascript">
									<!--
										document.getElementById("faq_l{questions.faq.ID_QUESTION}").href = 'javascript:show_answer({questions.faq.ID_QUESTION});';
										
									-->
									</script>
								</span>
								<span class="row2" style="float:right;">
									<a href="{questions.faq.U_QUESTION}" title="{L_QUESTION_URL}"><img src="{PICTURES_DATA_PATH}/images/flag.png" alt="{L_QUESTION_URL}" /></a>
									# IF C_ADMIN_TOOLS #
										<a href="{questions.faq.U_MOVE}" title="{L_MOVE}"><img src="../templates/{THEME}/images/upload/move.png" alt="{L_MOVE}" /></a>
										# START questions.faq.up #
											<a href="{questions.faq.U_UP}" title="{L_UP}"><img src="{PICTURES_DATA_PATH}/images/up.png" alt="{L_UP}" /></a>
										# END questions.faq.up #
										# START questions.faq.down #
											<a href="{questions.faq.U_DOWN}" title="{L_DOWN}"><img src="{PICTURES_DATA_PATH}/images/down.png" alt="{L_DOWN}" /></a>
										# END questions.faq.down #
										<a href="{questions.faq.U_EDIT}" title="{L_EDIT}"><img src="../templates/{THEME}/images/{LANG}/edit.png" alt="{L_EDIT}" /></a>
										<a href="{questions.faq.U_DEL}" onclick="return confirm('{L_CONFIRM_DELETE}');" title="{L_DELETE}"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" /></a>
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
							</script>
							# ENDIF #
							# IF questions.faq.C_SHOW_ANSWER #
							<script type="text/javascript">
								document.getElementById("faq_i{questions.faq.ID_QUESTION}").src = "{PICTURES_DATA_PATH}/images/opened_line.png";
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
								<img src="{PICTURES_DATA_PATH}/images/line.png" alt="arrow" class="image_left" style="vertical-align:middle;" />
								{questions_block.contents.QUESTION}
							</span>
							<span class="row2" style="float:right;">
								<a href="{questions_block.contents.U_QUESTION}" title="{L_QUESTION_URL}"><img src="{PICTURES_DATA_PATH}/images/flag.png" alt="{L_QUESTION_URL}" /></a>
								# IF C_ADMIN_TOOLS #
									<a href="{questions_block.contents.U_MOVE}" title="{L_MOVE}"><img src="../templates/{THEME}/images/upload/move.png" alt="{L_MOVE}" /></a>
									# START questions_block.contents.up #
										<a href="{questions_block.contents.U_UP}" title="{L_UP}"><img src="{PICTURES_DATA_PATH}/images/up.png" alt="{L_UP}" /></a>
									# END questions_block.contents.up #
									# START questions_block.contents.down #
										<a href="{questions_block.contents.U_DOWN}" title="{L_DOWN}"><img src="{PICTURES_DATA_PATH}/images/down.png" alt="{L_DOWN}" /></a>
									# END questions_block.contents.down #
									<a href="{questions_block.contents.U_EDIT}" title="{L_EDIT}"><img src="../templates/{THEME}/images/{LANG}/edit.png" alt="{L_EDIT}" /></a>
									<a href="{questions_block.contents.U_DEL}" onclick="return confirm('{L_CONFIRM_DELETE}');" title="{L_DELETE}"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" /></a>
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
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
		</div>