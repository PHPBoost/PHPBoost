<dl>
    <dt><label for="ForumTime">{L_DATE}</label></dt>
    <dd>
        <select id="ForumTime" name="ForumTime" class="search_field">
            <option value="30000" {IS_SELECTED_30000}>Tout</option>
            <option value="1" {IS_SELECTED_1}>1 {L_DAY}</option>
            <option value="7" {IS_SELECTED_7}>7 {L_DAYS}</option>
            <option value="15" {IS_SELECTED_15}>15 {L_DAYS}</option>
            <option value="30" {IS_SELECTED_30}>1 {L_MONTH}</option>
            <option value="180" {IS_SELECTED_180}>6 {L_MONTHS}</option>
            <option value="360" {IS_SELECTED_360}>1 {L_YEAR}</option>
        </select>
    </dd>
</dl>
<dl>
    <dt><label for="ForumIdcat">{L_CATEGORY}</label></dt>
    <dd><label>
        <select name="ForumIdcat" id="ForumIdcat" class="search_field">
            <option value="-1" {IS_ALL_CATS_SELECTED}>{L_ALL_CATS}</option>
            # START cats #
                <option value="{cats.ID}" {cats.IS_SELECTED}>{cats.MARGIN} {cats.L_NAME}</option>
            # END cats #
        </select>
    </label></dd>
</dl>
<dl>
    <dt><label for="ForumWhere">{L_OPTIONS}</label></dt>
    <dd>
        <label><input type="radio" id="ForumWhere" name="ForumWhere" value="title" {IS_TITLE_CHECKED}/> {L_TITLE}</label>
        <br />
        <label><input type="radio" name="ForumWhere" id="where" value="contents" {IS_CONTENTS_CHECKED}/> {L_CONTENTS}</label>
        <br />
        <label><input type="radio" name="ForumWhere" value="all" {IS_ALL_CHECKED}/> {L_TITLE} / {L_CONTENTS}</label>
    </dd>
</dl>
<dl>
    <dt><label for="ForumColorate_result">{L_COLORATE_RESULTS}</label></dt>
    <dd>
        <label><input type="checkbox" name="ForumColorate_result" id="ForumColorate_result" value="1" {IS_COLORATION_CHECKED}/></label>
    </dd>
</dl>