<div class="module_position">
<div class="module_top_l"></div>
<div class="module_top_r"></div>
<div class="module_top">Unit tests</div>
<div class="module_contents">
<form name="phpunit_launcher">
<fieldset><legend>Command line</legend>
<dl>
	<dt><label for="params">Command</label></dt>
	<dd><input type="text" name="params" id="params" value="{PARAMS}"
		class="text" style="width: 75%;" /></dd>
	<dt><label for="is_html">HTML output</label></dt>
	<dd><input type="checkbox" name="is_html" id="is_html" /></dd>
</dl>
</fieldset>
<fieldset class="fieldset_submit"><input type="button"
	name="run" value="run command" class="submit" class="run-button"
	onclick="self.frames['phpunit'].location='run.php?is_html=' + (document.getElementById('is_html').checked ? '1' : '0') + '&amp;params=' + document.getElementById('params').value;" />
</fieldset>
<fieldset><legend>Unit tests</legend>
<dl>
    <dt><label for="tus">Choose in the list</label></dt>
    <dd><select id="tus" name="tus">
        # START tests #
        <option value="./kernel/{tests.PATH}">{tests.NAME}</option>
        # END tests #
    </select></dd>
</dl>
</fieldset>
<fieldset class="fieldset_submit"><input type="button"
    name="run_tu" value="run unit test" class="submit" class="run-button"
    onclick="self.frames['phpunit'].location='run.php?is_html=0' + '&amp;params=' + document.getElementById('tus').value;" />
</fieldset>
<fieldset><legend>Tests suite</legend>
<dl>
    <dt><label for="ts">Choose in the list</label></dt>
    <dd><select id="ts" name="ts">
        # START tests_suite #
        <option value="./kernel{tests_suite.NAME}">{tests_suite.NAME}</option>
        # END tests_suite #
    </select></dd>
</dl>
</fieldset>
<fieldset class="fieldset_submit"><input type="button"
    name="run_ts" value="run test suite" class="submit" class="run-button"
    onclick="self.frames['phpunit'].location='run.php?is_html=0' + '&amp;params=' + document.getElementById('ts').value;" />
</fieldset>
</form>

<hr />
<br />
<iframe src="run.php?params={PARAMS}" style="width: 100%; height: 500px"
	name="phpunit"></iframe></div>
<div class="module_bottom_l"></div>
<div class="module_bottom_r"></div>
<div class="module_bottom"></div>
</div>