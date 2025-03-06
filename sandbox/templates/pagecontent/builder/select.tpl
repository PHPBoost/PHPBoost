<div class="formatter-container formatter-hide no-js tpl">
    <span class="formatter-title title-perso">{@sandbox.source.code} : select</span>
    <div class="formatter-content formatter-code">
        <div class="no-style">
<pre class="precode"><code>&lt;div class="form-element">
    &lt;label for="[ID]">...&lt;/label>
    &lt;div class="form-field form-field-select"> //  || form-field-multi-select
        &lt;select name="[NAME]" id="[ID]"> // class="select-to-list" for options with images/font-icons / || name="[NAME][]" && multiple="multiple" for multiple options
            &lt;option value="[VALUE]_0" label="...">...&lt;/option> // data-option-img="path-to-picture" for images with "select-to-list"
            &lt;option value="[VALUE]_1" label="...">...&lt;/option> // data-option-icon="icon-values" for font-icons with "select-to-list"
        &lt;/select>
    &lt;/div>
&lt;/div></code></pre>
        </div>
    </div>
</div>
