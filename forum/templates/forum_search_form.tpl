<div class="form-element">
    <label for="ForumTime">{@date.date}</label>
    <div class="form-field form-field-select">
        <select id="ForumTime" name="ForumTime" class="search-field">
            <option value="30000" {IS_SELECTED_30000}>{@common.all.alt}</option>
            <option value="1" {IS_SELECTED_1}>1 {@date.day}</option>
            <option value="7" {IS_SELECTED_7}>7 {@date.days}</option>
            <option value="15" {IS_SELECTED_15}>15 {@date.days}</option>
            <option value="30" {IS_SELECTED_30}>1 {@date.month}</option>
            <option value="180" {IS_SELECTED_180}>6 {@date.months}</option>
            <option value="360" {IS_SELECTED_360}>1 {@date.year}</option>
        </select>
    </div>
</div>
<div class="form-element">
    <label for="ForumIdcat">{@common.category}</label>
    <div class="form-field form-field-select">
        <select name="ForumIdcat" id="ForumIdcat" class="search-field">
            <option value="-1" {IS_ALL_CATS_SELECTED}>{@common.all.alt}</option>
            {CATS}
        </select>
    </div>
</div>
<div class="form-element">
    <label for="ForumWhere">{@common.options}</label>
    <div class="form-field form-field-radio-button">
        <div class="form-field-radio">
            <label clas="radio">
                <input type="radio" id="ForumWhere" name="ForumWhere" value="title" {IS_TITLE_CHECKED}/>
                <span>{@common.title}</span>
            </label>
        </div>
        <div class="form-field-radio">
            <label clas="radio">
                <input type="radio" name="ForumWhere" value="content" {IS_CONTENT_CHECKED}/>
                <span>{@common.content}</span>
            </label>
        </div>
        <div class="form-field-radio">
            <label clas="radio">
                <input type="radio" name="ForumWhere" value="all" {IS_ALL_CHECKED}/>
                <span>{@common.title} / {@common.content}</span>
            </label>
        </div>
    </div>
</div>
