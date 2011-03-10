#{resources('install/install')}
<fieldset class="submit_case">
    # IF HAS_PREVIOUS_STEP #
    <a href="{PREVIOUS_STEP_URL}" title="{@step.previous}" >
        <img src="templates/images/left.png" alt="{@step.previous}" class="valign_middle" />
    </a>
    # END #
    <input src="templates/images/right.png" title="{@step.next}" class="img_submit" type="image">
</fieldset> 