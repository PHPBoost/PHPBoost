		<script type="text/javascript">
		<!--
			theme = "{THEME}";
			module_data_path = "{MODULE_DATA_PATH}";
			function show_answer(id_question)
			{
				if( document.getElementById("a" + id_question).style.display == "block" )
					document.getElementById("a" + id_question).style.display = "none";
				else
					document.getElementById("a" + id_question).style.display = "block";
			}
		-->
		</script>
		<script type="text/javascript" src="{MODULE_DATA_PATH}/images/faq.js">
		</script>

		<div class="module_position">			
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				{TITLE}
				# IF C_ADMIN #
				<a href="{U_ADMIN_CAT}">
					<img class="valign_middle" src="../templates/{THEME}/images/{LANG}/edit.png" alt="">
				</a>
				# END IF #
			</div>
			<div class="module_contents">
			
				# START description #
					{description.DESCRIPTION}
					<hr style="margin:25px 0px;" />
				# END description #
				
				# IF C_FAQ_CATS #
					# START list_cats #
						<div style="float:left;width:{list_cats.WIDTH}%;text-align:center;margin-bottom:30px;">
							# IF C_CAT_IMG #
								<a href="{list_cats.U_CAT}" title="{list_cats.IMG_NAME}"><img src="{list_cats.SRC}" alt="{list_cats.IMG_NAME}" /></a>
								<br />
							# ENDIF #
							<a href="{list_cats.U_CAT}">{list_cats.NAME}</a>
							# IF C_ADMIN #
							<a href="{list_cats.U_ADMIN_CAT}">
								<img class="valign_middle" src="../templates/{THEME}/images/{LANG}/edit.png" alt="">
							</a>
							# ENDIF #
						</div>
					# END list_cats #
					<div class="spacer">&nbsp;</div>
					<hr style="margin-bottom:25px;" />
				# ENDIF #		
				
				# START management #
					<div style="text-align:center; margin:10px;">
						<a href="{U_MANAGEMENT}">
							<img src="{MODULE_DATA_PATH}/images/category_management.png" alt="{L_CATEGORY_MANAGEMENT}" title="{L_CAT_MANAGEMENT}" />
						</a>
						<br />
						<a href="{U_MANAGEMENT}">{L_CAT_MANAGEMENT}</a>
					</div>
				# END management #
				
				# START questions #
					# START questions.faq #
						<div style="margin-bottom:10px;" id="q{questions.faq.ID_QUESTION}">
							<div class="row1">
								<img src="{MODULE_DATA_PATH}/images/line.png" alt="arrow" class="image_left" style="vertical-align:middle;" />
								<script type="text/javascript">
								<!--
									document.write("<a href=\"javascript:show_answer({questions.faq.ID_QUESTION});\">{questions.faq.QUESTION}</a>");
								-->
								</script>
								<noscript>
									<a href="{questions.faq.U_QUESTION}">
									{questions.faq.QUESTION}
									</a>
								</noscript>
							</div>
							<br />
							<div id="a{questions.faq.ID_QUESTION}" class="blockquote" style="{questions.faq.DISPLAY_ANSWER}">
								{questions.faq.ANSWER}
							</div>
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
						<div class="row1">
							<img src="{MODULE_DATA_PATH}/images/line.png" alt="arrow" class="image_left" style="vertical-align:middle;" />
							{questions_block.contents.QUESTION}
						</div>
						<br />
						<div class="blockquote" id="q{questions_block.contents.ID}" style="margin-bottom:30px;">
							{questions_block.contents.ANSWER}
						</div>	
					# END questions_block.contents #		
				# END questions_block #
				
				# START no_question #
					<div class="notice">
						{L_NO_QUESTION_THIS_CATEGORY}
					</div>
				# END no_question #
				<div class="spacer">&nbsp;</div>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
		</div>