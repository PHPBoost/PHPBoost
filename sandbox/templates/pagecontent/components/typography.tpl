<div id="component-typography">
    <h2>{@sandbox.typography}</h2>

    <article id="component-titles" class="sandbox-block">
        <header>
            <h5>{@component.titles} {@H|sandbox.pinned.bbcode}</h5>
        </header>
        <h1>h1. {@component.title} 1</h1>
        <h2>h2. {@component.title} 2</h2>
        <h3>h3. {@component.title} 3</h3>
        <h4>h4. {@component.title} 4</h4>
        <h5>h5. {@component.title} 5</h5>
        <h6>h6. {@component.title} 6</h6>
    </article>

    <article id="component-sizes" class="sandbox-block">
        <header>
            <h5>{@sandbox.sizes} {@H|sandbox.pinned.bbcode}</h5>
        </header>
        <p class="smallest">{@component.item.smallest}</p>
        <p class="smaller">{@component.item.smaller}</p>
        <p class="small">{@component.item.small}</p>
        <p class="normal">{@component.item}</p>
        <p class="big">{@component.item.big}</p>
        <p class="bigger">{@component.item.bigger}</p>
        <p class="biggest">{@component.item.biggest}</p>
        <!-- Source code -->
        <div class="formatter-container formatter-hide no-js">
            <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
            <div class="formatter-content formatter-code">
                <div class="formatter-content">
<pre class="language-html line-numbers"><code class="language-html">&lt;span class="smallest">{@component.item.smallest}&lt;/span>
&lt;span class="smaller">{@component.item.smaller}&lt;/span>
&lt;span class="small">{@component.item.small}&lt;/span>
&lt;span class="normal">{@component.item}&lt;/span>
&lt;span class="big">{@component.item.big}&lt;/span>
&lt;span class="bigger">{@component.item.bigger}&lt;/span>
&lt;span class="biggest">{@component.item.biggest}&lt;/span></code></pre>
                </div>
            </div>
        </div>
    </article>
    <article id="component-styles" class="sandbox-block">
        <header>
            <h5>{@component.styles} {@H|sandbox.pinned.bbcode}</h5>
        </header>
        <p class="text-strong">{@component.item.bold}</p>
        <p class="text-italic">{@component.item.italic}</p>
        <p class="text-underline">{@component.item.underline}</p>
        <p class="text-strike">{@component.item.strike}</p>
        <p><a href="#">{@component.link}</a></p>
        <p class="ellipsis">{@component.item.ellipsis} - Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iure voluptatum incidunt illum consectetur officia id unde accusantium eaque est, ex!</p>
        <p class="align-left">{@component.item.left}</p>
        <p class="align-center">{@component.item.center}</p>
        <p class="align-right">{@component.item.right}</p>
        <p class="float-left">{@component.item.float.left}</p>
        <p class="float-right">{@component.item.float.right}</p>
        <div class="spacer"></div>
        <p class="flex-between"><span>{@component.items}</span><span>{@component.item.stretch.center}</span><span>{@component.item.stretch.right}</span></p>
        <p class="stacked"><span>{@component.item}</span><span class="stack-event stack-right stack-sup stack-circle bgc member">{@component.item.stack.index}</span></p>
        <div class="spacer"></div>
        <p class="pinned bgc moderator">{@component.item.pinned}</p>
        <!-- Source code -->
        <div class="formatter-container formatter-hide no-js">
            <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
            <div class="formatter-content formatter-code">
                <div class="formatter-content">
<pre class="language-html line-numbers"><code class="language-html">&lt;p class="text-strong">{@component.item.bold}&lt;/p>
&lt;p class="text-italic">{@component.item.italic}&lt;/p>
&lt;p class="text-underline">{@component.item.underline}&lt;/p>
&lt;p class="text-strike">{@component.item.strike}&lt;/p>
&lt;p>&lt;a href="#">{@component.link}&lt;/a>&lt;/p>
&lt;p class="ellipsis">{@component.item.ellipsis} - Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iure voluptatum incidunt illum consectetur officia id unde accusantium eaque est, ex!&lt;/p>
&lt;p class="align-left">{@component.item.left}&lt;/p>
&lt;p class="align-center">{@component.item.center}&lt;/p>
&lt;p class="align-right">{@component.item.right}&lt;/p>
&lt;p class="float-left">{@component.item.float.left}&lt;/p>
&lt;p class="float-right">{@component.item.float.right}&lt;/p>
&lt;p class="flex-between">&lt;span>{@component.items}&lt;/span>&lt;span>{@component.item.stretch.center}&lt;/span>&lt;span>{@component.item.stretch.right}&lt;/span>&lt;/p>
&lt;p class="stacked">&lt;span>{@component.item}&lt;/span>&lt;span class="stack-event stack-right stack-sup stack-circle bgc member">{@component.item.stack.index}&lt;/span>&lt;/p>
&lt;p class="pinned bgc moderator">{@component.item.pinned}&lt;/p></code></pre>
                </div>
            </div>
        </div>
    </article>
</div>
