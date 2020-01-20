<script>
	var tinymce_editor       = {C_TINYMCE_EDITOR};
	var enter_text           = "{L_PLEASE_ENTER_A_TITLE}";
	var title_link           = "{L_TITLE_LINK}";
	var enter_paragraph_name = "{L_PARAGRAPH_NAME}";
	var title_paragraph      = "{PARAGRAPH_NAME}";
</script>

<script src="{PICTURES_DATA_PATH}/js/wiki.js"></script>

<div class="bbcode wiki-bbcode">
	<div class="bbcode-containers">
		<ul id="wiki-link-container" class="bbcode-container">
			<li class="bbcode-elements">
				<a href="javascript:insert_link();" class="bbcode-wiki-insert">{L_INSERT_LINK} <i class="fa fa-link" aria-hidden="true"></i></a>
			</li>
		</ul>
		<ul id="wiki-paragraph-container" class="bbcode-container">
			<li class="bbcode-elements">
				<a href="javascript:insert_paragraph(1);" aria-label="{L_EXPLAIN_PARAGRAPH_1}"><img src="{PICTURES_DATA_PATH}/images/bbcode/paragraph1.png" alt="{L_EXPLAIN_PARAGRAPH_1}" class="valign-middle" id="link1" /></a>
				<a href="javascript:insert_paragraph(2);" aria-label="{L_EXPLAIN_PARAGRAPH_2}"><img src="{PICTURES_DATA_PATH}/images/bbcode/paragraph2.png" alt="{L_EXPLAIN_PARAGRAPH_2}" class="valign-middle" id="link2" /></a>
				<a href="javascript:insert_paragraph(3);" aria-label="{L_EXPLAIN_PARAGRAPH_3}"><img src="{PICTURES_DATA_PATH}/images/bbcode/paragraph3.png" alt="{L_EXPLAIN_PARAGRAPH_3}" class="valign-middle" id="link3" /></a>
				<a href="javascript:insert_paragraph(4);" aria-label="{L_EXPLAIN_PARAGRAPH_4}"><img src="{PICTURES_DATA_PATH}/images/bbcode/paragraph4.png" alt="{L_EXPLAIN_PARAGRAPH_4}" class="valign-middle" id="link4" /></a>
				<a href="javascript:insert_paragraph(5);" aria-label="{L_EXPLAIN_PARAGRAPH_5}"><img src="{PICTURES_DATA_PATH}/images/bbcode/paragraph5.png" alt="{L_EXPLAIN_PARAGRAPH_5}" class="valign-middle" id="link5" /></a>
			</li>
		</ul>
		<ul id="wiki-help-container" class="bbcode-container">
			<li class="bbcode-elements">
				<a href="https://www.phpboost.com/wiki/" aria-label="{L_HELP_WIKI_TAGS}"><i class="fa fa-fw bbcode-icon-help" aria-hidden="true"></i></a>
			</li>
		</ul>
	</div>
</div>
<noscript><div>{L_NO_JS}</div></noscript>
