<div class="formatter-container formatter-hide no-js tpl">
    <span class="formatter-title title-perso">{@sandbox.source.code} : checkbox</span>
    <div class="formatter-content formatter-code">
        <div class="no-style">
<pre class="precode"><code>&lt;div class="form-element form-element-checkbox"> // mini-checkbox || custom-checkbox (with checkbox.css)
    &lt;label for="[ID]">...&lt;/label>
    &lt;div class="form-field form-field-checkbox">
        &lt;label class="checkbox" for="[ID]">
            &lt;input type="checkbox" name="[NAME]" id="[ID]">
            &lt;span>&nbsp;&lt;span class="sr-only">...&lt;/span>&lt;/span>
        &lt;/label>
    &lt;/div>
&lt;/div>

// Multiple checkboxes
&lt;div class="form-element"> // inline-checkbox
    &lt;label for="[ID]">...&lt;/label>
    &lt;div id="[ID]" class="form-field form-field-multiple-checkbox">
        &lt;div class="form-field-checkbox">
            &lt;label class="checkbox" for="[ID]_1">
                &lt;input type="checkbox" name="[NAME]_1" id="[ID]_1" checked="checked">
                &lt;span>...&lt;/span>
            &lt;/label>
        &lt;/div>
        &lt;div class="form-field-checkbox">
            &lt;label class="checkbox" for="[ID]_2">
                &lt;input type="checkbox" name="[NAME]_2" id="[ID]_2">
                &lt;span>...&lt;/span>
            &lt;/label>
        &lt;/div>
    &lt;/div>
&lt;/div></code></pre>
        </div>
    </div>
</div>
