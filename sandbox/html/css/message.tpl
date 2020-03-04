<pre class="language-html line-numbers"><code class="language-html">&lt;article id="Id" class="several-items message-container (message-small/message-offset)" itemscope="itemscope" itemtype="http://schema.org/Comment">
    &lt;header class="message-header-container (#IF CURRENT#current-user-message)">
        &lt;img class="message-user-avatar" src="Url" alt="Text">
        &lt;div class="message-header-infos">
            &lt;div class="message-user-infos hidden-small-screens">
                &lt;div>&lt;/div>
                &lt;div class="message-user-links">&lt;/div>
            &lt;/div>
            &lt;div class="message-user">
                &lt;h3 class="message-user-pseudo">
                    &lt;a class="Level" href="UrlProfil" itemprop="author">MemberName&lt;/a>
                &lt;/h3>
                &lt;div class="message-actions">
                    &lt;a href="UrlAction" aria-label="ActionName">&lt;i class="fa fa-fw fa-action" data-confirmation="delete-element">&lt;/i>&lt;/a>
                &lt;/div>
            &lt;/div>
            &lt;div class="message-infos">
                &lt;time datetime="Date" itemprop="datePublished">Date&lt;/time>
                &lt;a href="UrlAnchor" aria-label="${LangLoader::get_message('link.to.anchor', 'comments-common')}">AnchorName&lt;/a>
            &lt;/div>
        &lt;/div>
    &lt;/header>

    &lt;div class="message-content">
        ...
    &lt;/div>

    &lt;div class="message-user-sign">&lt;/div>

    &lt;footer class="message-footer-container">
        &lt;div class="message-user-assoc">
            &lt;div class="message-group-level">RankImg - GroupImg&lt;/div>
            &lt;div class="message-user-rank">UserLevel&lt;/div>
        &lt;/div>
        &lt;div class="message-user-management">
            &lt;div class="">&lt;/div>
            &lt;div class="message-moderation-level">Lorem ipsum&lt;/div>
        &lt;/div>
    &lt;/footer>

&lt;/article></code></pre>
