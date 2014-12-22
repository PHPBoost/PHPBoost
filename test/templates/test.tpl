<div class="module-position">
<div class="module-top-l"></div>
<div class="module-top-r"></div>
<div class="module-top">Unit tests</div>
<div class="module-contents">
<form name="phpunit_launcher">
<fieldset><legend>Command line</legend>
<div class="form-element">
	<label for="params">Command</label>
	<div class="form-field"><input type="text" name="params" id="params" value="{PARAMS}"
		style="width: 75%;" /></div>
	<label for="is_html">HTML output</label>
	<div class="form-field"><input type="checkbox" name="is_html" id="is_html"></div>
</div>
</fieldset>
<fieldset class="fieldset-submit">
	<button name="run" value="true" onclick="self.frames['phpunit'].location='run.php?is_html=' + (document.getElementById('is_html').checked ? '1' : '0') + '&amp;params=' + document.getElementById('params').value;">run command</button>
</fieldset>
<fieldset><legend>Unit tests</legend>
<div class="form-element">
    <label for="tus">Choose in the list</label>
    <div class="form-field"><select id="tus" name="tus">
        # START tests #
        <option value="./kernel/{tests.PATH}">{tests.NAME}</option>
        # END tests #
    </select></div>
</div>
</fieldset>
<fieldset class="fieldset-submit">
	<button name="run_tu" value="true" onclick="self.frames['phpunit'].location='run.php?is_html=0' + '&amp;params=' + document.getElementById('tus').value;">run unit test</button>
</fieldset>
<fieldset><legend>Tests suite</legend>
<div class="form-element">
    <label for="ts">Choose in the list</label>
    <div class="form-field"><select id="ts" name="ts">
        # START tests_suite #
        <option value="./kernel{tests_suite.NAME}">{tests_suite.NAME}</option>
        # END tests_suite #
    </select></div>
</div>
</fieldset>
<fieldset class="fieldset-submit">
	<button name="run_ts" value="true" onclick="self.frames['phpunit'].location='run.php?is_html=0' + '&amp;params=' + document.getElementById('ts').value;">run test suite</button>
</fieldset>
</form>

<hr />
<br />
<iframe src="run.php?params={PARAMS}" style="width: 100%; height: 500px"
	name="phpunit"></iframe></div>
<div class="module-bottom-l"></div>
<div class="module-bottom-r"></div>
<div class="module-bottom"></div>
</div>