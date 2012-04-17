<dl>
    <dt><label for="BugtrackerWhere">{L_WHERE}</label></dt>
    <dd>
        <label><input type="radio" id="BugtrackerWhere" name="BugtrackerWhere" value="title" {IS_TITLE_CHECKED}/> {L_TITLE}</label>
        <br />
        <label><input type="radio" name="BugtrackerWhere" id="where" value="contents" {IS_CONTENTS_CHECKED}/> {L_CONTENTS}</label>
        <br />
        <label><input type="radio" name="BugtrackerWhere" value="all" {IS_ALL_CHECKED}/> {L_TITLE} / {L_CONTENTS}</label>
    </dd>
</dl>