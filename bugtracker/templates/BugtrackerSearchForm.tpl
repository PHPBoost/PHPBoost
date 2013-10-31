<div class="form-element">
	<label for="BugtrackerWhere">{@bugs.search.where}</label>
	<div class="form-field">
		<label><input type="radio" id="BugtrackerWhere" name="BugtrackerWhere" value="title" {IS_TITLE_CHECKED}> {@bugs.search.where.title}</label>
		<br />
		<label><input type="radio" name="BugtrackerWhere" id="where" value="contents" {IS_CONTENTS_CHECKED}> {@bugs.search.where.contents}</label>
		<br />
		<label><input type="radio" name="BugtrackerWhere" value="all" {IS_ALL_CHECKED}> {@bugs.search.where.title} / {@bugs.search.where.contents}</label>
	</div>
</div>