<div class="form-element">
    <label for="ForumTime">{L_DATE}</label>
    <div class="form-field">
        <select id="ForumTime" name="ForumTime" class="search-field">
            <option value="30000" {IS_SELECTED_30000}>Tout</option>
            <option value="1" {IS_SELECTED_1}>1 {L_DAY}</option>
            <option value="7" {IS_SELECTED_7}>7 {L_DAYS}</option>
            <option value="15" {IS_SELECTED_15}>15 {L_DAYS}</option>
            <option value="30" {IS_SELECTED_30}>1 {L_MONTH}</option>
            <option value="180" {IS_SELECTED_180}>6 {L_MONTHS}</option>
            <option value="360" {IS_SELECTED_360}>1 {L_YEAR}</option>
        </select>
    </div>
</div>
<div class="form-element">
    <label for="ForumIdcat">{L_CATEGORY}</label>
    <div class="form-field"><label>
        <select name="ForumIdcat" id="ForumIdcat" class="search-field">
            <option value="-1" {IS_ALL_CATS_SELECTED}>{L_ALL_CATS}</option>
            {CATS}
        </select>
    </label></div>
</div>
<div class="form-element">
    <label for="ForumWhere">{L_OPTIONS}</label>
    <div class="form-field">
        <label><input type="radio" id="ForumWhere" name="ForumWhere" value="title" {IS_TITLE_CHECKED}/> {L_TITLE}</label>
        <label><input type="radio" name="ForumWhere" value="contents" {IS_CONTENTS_CHECKED}/> {L_CONTENTS}</label>
        <label><input type="radio" name="ForumWhere" value="all" {IS_ALL_CHECKED}/> {L_TITLE} / {L_CONTENTS}</label>
    </div>
</div>