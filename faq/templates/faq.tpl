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
			<div class="module_top">{TITLE}</div>
			<div class="module_contents">
				# START description #
					{description.DESCRIPTION}
					<hr style="margin:20px 0;" />
				# END description #
				# START cats #
					<table style="width:100%;">
					# START row #
						<tr>
							# START col #
								<td style="width:{cats.row.col.WIDTH}%;text-align:center;padding-bottom:40px;">
									# START image #
										<a href="{cats.row.col.U_CAT}" title="{cats.row.col.image.NAME}"><img src="{cats.row.col.image.SRC}" alt="{cats.row.col.image.NAME}" /></a>
										<br />
									# END image #
									<a href="{cats.row.col.U_CAT}">{cats.row.col.NAME}</a>
								</td>
							# END col #
						</tr>
					# END row #
					</table>
					<hr style="margin:20px 0;" />
				# END cats #		
				
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
					# START faq #
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
					# END faq #
				# END questions #
				
				# START questions_block #		
					<ol class="bb_ol">
					# START header #
						<li>
							<a href="#q{questions_block.header.ID}">{questions_block.header.QUESTION}</a>
						</li>
					# END header #
					</ol>			
					<hr style="margin:20px 0;" />			
					# START contents #
						<div class="row1">
							<img src="{MODULE_DATA_PATH}/images/line.png" alt="arrow" class="image_left" style="vertical-align:middle;" />
							{questions_block.contents.QUESTION}
						</div>
						<br />
						<div class="blockquote" id="q{questions_block.contents.ID}" style="margin-bottom:30px;">
							{questions_block.contents.ANSWER}
						</div>	
					# END contents #		
				# END questions_block #
				
				# START no_question #
					{L_NO_QUESTION_THIS_CATEGORY}
				# END no_question #
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom" style="text-align:center"></div>
		</div>