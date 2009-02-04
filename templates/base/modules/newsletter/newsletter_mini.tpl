<div style="margin:10px 10px">
    <form action="{PATH_TO_ROOT}/newsletter/newsletter.php{SID}" method="post">
        <div class="newsletter_form" style="float:right;">
            <span class="newsletter_title">{L_NEWSLETTER}</span> 
            <input type="text" name="mail_newsletter" maxlength="50" size="16" class="text newsletter_text" value="{USER_MAIL}" />
            <input type="image" class="newsletter_img" value="1" src="{PATH_TO_ROOT}/templates/{THEME}/modules/newsletter/images/newsletter_submit.png" />
            <input type="hidden" name="subscribe" value="subscribe" />
        </div>
    </form>
</div> 
