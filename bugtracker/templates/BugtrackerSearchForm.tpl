<div class="form-element">
	<label for="BugtrackerWhere">{@search.where}</label>
	<div class="form-field">
		<label><input type="radio" id="BugtrackerWhere" name="BugtrackerWhere" value="title" {IS_TITLE_CHECKED}> {@search.where.title}</label>
		<label><input type="radio" name="BugtrackerWhere" id="where" value="contents" {IS_CONTENTS_CHECKED}> {@search.where.contents}</label>
		<label><input type="radio" name="BugtrackerWhere" value="all" {IS_ALL_CHECKED}> {@search.where.title} / {@search.where.contents}</label>
	</div>
</div>