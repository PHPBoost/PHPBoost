<script>
<!--
	function bbcode_page()
	{
		var page = prompt("Titre de la nouvelle page");

		if (page) {
			var textarea = $('ArticlesFormController_contents');
			var start = textarea.selectionStart;
			var end = textarea.selectionEnd;

			if (start == end) {
				var insert_value = '[page]' + page + '[/page]';
				textarea.value = textarea.value.substr(0, start) + insert_value + textarea.value.substr(end);
			}
			else {
				var value = textarea.value;
				var insert_value = '[page]' + value.substring(start, end) + '[/page]';
				textarea.value = textarea.value.substr(0, start) + insert_value + textarea.value.substr(end);
			}

			textarea.selectionStart = start + insert_value.length;
			textarea.selectionEnd = start + insert_value.length;
		}
	}
	
	function page_to_edit(page_number, page) 
	{
		var searchText = page;
		var t = $('ArticlesFormController_contents');
		var l = t.value.indexOf(searchText);

		if (l != -1)
		{
			t.focus();
			t.selectionStart = l;
			t.selectionEnd = l + searchText.length;
			if (page_number > 1)
				t.scrollTop = t.scrollHeight;
		}
	}

	function setPagePosition (page_number, page) {
		page_to_edit(page_number, page);
	}
	
	# IF C_PAGE #
	window.onload = function(){setPagePosition('{CURRENT_PAGE}', {PAGE})};
	# ENDIF #
-->
</script>
# INCLUDE MSG # # INCLUDE FORM #