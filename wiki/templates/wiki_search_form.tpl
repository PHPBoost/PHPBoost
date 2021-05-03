<div class="form-element">
    <label for="WikiWhere">{@common.options}</label>
    <div class="form-field form-field-select">
        <select id="WikiWhere" name="WikiWhere" class="search_field">
            <option value="title" {IS_TITLE_SELECTED}>{@common.title}</option>
            <option value="contents" {IS_CONTENT_SELECTED}>{@common.content}</option>
            <option value="all" {IS_ALL_SELECTED}>{@common.title} / {@common.content}</option>
        </select>
    </div>
</div>
