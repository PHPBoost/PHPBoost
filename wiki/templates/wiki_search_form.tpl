<div class="form-element">
    <label for="WikiWhere">{L_WHERE}</label>
    <div class="form-field form-field-select">
        <select id="WikiWhere" name="WikiWhere" class="search_field">
            <option value="title" {IS_TITLE_SELECTED}>{L_TITLE}</option>
            <option value="contents" {IS_CONTENTS_SELECTED}>{L_CONTENTS}</option>
            <option value="all" {IS_ALL_SELECTED}>{L_TITLE} / {L_CONTENTS}</option>
        </select>
    </div>
</div>
