<script>
<!--
var PHPBoostOfficialVersions = function(){
	this.integer = {NBR_VERSIONS};
	this.id_input = ${escapejs(HTML_ID)};
	this.max_input = {MAX_INPUT};
};

PHPBoostOfficialVersions.prototype = {
	add : function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '_' + this.integer;
			
			jQuery('<div/>', {'id' : id}).appendTo('#input_versions_' + this.id_input);
			
			jQuery('<input/> ', {type : 'text', id : 'field_major_version_number_' + id, name : 'field_major_version_number_' + id, class : 'major-version-number', placeholder : '{@major_version_number}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');
			
			jQuery('<input/> ', {type : 'text', id : 'field_minor_version_number_' + id, name : 'field_minor_version_number_' + id, class : 'minor-version-number', placeholder : '{@minor_version_number}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');
			
			jQuery('<input/> ', {type : 'text', id : 'field_minimal_php_version_' + id, name : 'field_minimal_php_version_' + id, class : 'minimal-php-version', placeholder : '{@minimal_php_version}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');
			
			jQuery('<input/> ', {type : 'text', id : 'field_name_' + id, name : 'field_name_' + id, class : 'name', placeholder : '${LangLoader::get_message('form.name', 'common')}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');
			
			jQuery('<a/> ', {onclick : 'PHPBoostOfficialVersions.delete('+ this.integer +');return false;', title : "${LangLoader::get_message('delete', 'common')}"}).html('<i class="fa fa-delete"></i>').appendTo('#' + id);
			
			this.integer++;
		}
		if (this.integer == this.max_input) {
			jQuery('#add-' + this.id_input).hide();
		}
	},
	delete : function (id) {
		var id = this.id_input + '_' + id;
		jQuery('#' + id).remove();
		this.integer--;
		jQuery('#add-' + this.id_input).show();
	},
};

var PHPBoostOfficialVersions = new PHPBoostOfficialVersions();
-->
</script>

<div id="input_versions_${escape(HTML_ID)}">
<div class="text-strong"><span class="major-version-number-title">{@major_version_number}</span><span class="minor-version-number-title">{@minor_version_number}</span><span class="minimal-php-version-title">{@minimal_php_version}</span><span class="name-title">${LangLoader::get_message('form.name', 'common')}</span></div>
# START fieldelements #
	<div id="${escape(HTML_ID)}_{fieldelements.ID}">
		<input type="text" name="field_major_version_number_${escape(HTML_ID)}_{fieldelements.ID}" id="field_major_version_number_${escape(HTML_ID)}_{fieldelements.ID}" value="{fieldelements.MAJOR_VERSION_NUMBER}" class="major-version-number" required="required" placeholder="{@major_version_number}">
		<input type="text" name="field_minor_version_number_${escape(HTML_ID)}_{fieldelements.ID}" id="field_minor_version_number_${escape(HTML_ID)}_{fieldelements.ID}" value="{fieldelements.MINOR_VERSION_NUMBER}" class="minor-version-number" placeholder="{@minor_version_number}">
		<input type="text" name="field_minimal_php_version_${escape(HTML_ID)}_{fieldelements.ID}" id="field_minimal_php_version_${escape(HTML_ID)}_{fieldelements.ID}" value="{fieldelements.MINIMAL_PHP_VERSION}" class="minimal-php-version" required="required" placeholder="{@minimal_php_version}">
		<input type="text" name="field_name_${escape(HTML_ID)}_{fieldelements.ID}" id="field_name_${escape(HTML_ID)}_{fieldelements.ID}" value="{fieldelements.NAME}"  class="name" required="required" placeholder="${LangLoader::get_message('form.name', 'common')}">
		<a href="" onclick="PHPBoostOfficialVersions.delete({fieldelements.ID});return false;" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
	</div>
# END fieldelements #
</div>
<a href="" onclick="PHPBoostOfficialVersions.add();return false;" id="add-${escape(HTML_ID)}"><i class="fa fa-plus"></i></a>
