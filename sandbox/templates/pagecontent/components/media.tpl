<div id="fwkboost-media" class="sandbox-block">
    <h2>{@fwkboost.title.media}</h2>
    <article>
        <header>
			<h5>{@fwkboost.image}</h5>
		</header>
		<div class="content">
            <figure style="max-width:300px">
                <img src="{PATH_TO_ROOT}/sandbox/templates/images/paysage.png" alt="{@fwkboost.image}" />
            </figure>
            <figure style="max-width:300px">
                <img src="{PATH_TO_ROOT}/sandbox/templates/images/square.png" alt="{@fwkboost.image}" />
                <figcaption>{@fwkboost.caption.image}</figcaption>
            </figure>
		</div>
    </article>
    <article id="lightbox">
		<header>
			<h5>{@fwkboost.lightbox}</h5>
		</header>
		<div class="content">
			<a href="{PATH_TO_ROOT}/sandbox/templates/images/paysage.png" data-lightbox="formatter" data-rel="lightcase:collection">
				<img style="max-width: 150px" src="{PATH_TO_ROOT}/sandbox/templates/images/paysage.png" alt="Lorem ipsum" />
			</a>
			<a href="{PATH_TO_ROOT}/sandbox/templates/images/square.png" data-lightbox="formatter" data-rel="lightcase:collection">
				<img style="max-width: 150px" src="{PATH_TO_ROOT}/sandbox/templates/images/square.png" alt="Sit dolor amet" />
			</a>
		</div>
	</article>

	<article id="youtube">
		<header>
			<h5>{@fwkboost.youtube}</h5>
		</header>
		<div class="media-content" style="width: 800px; height: 450px">
			<iframe class="youtube-player" src="https://www.youtube.com/embed/YE7VzlLtp-4" allowfullscreen=""></iframe>
		</div>
	</article>
	<article id="movie">
		<header>
			<h5>{@fwkboost.movie}</h5>
		</header>
		<div class="media-content" style="width: 800px; height: 450px">
			<video class="video-player" controls="">
				<source src="http://data.babsoweb.com/private/logo-pbt.mp4" type="video/mp4" />
			</video>
		</div>
	</article>

	<article id="audio">
		<header>
			<h5>{@fwkboost.audio}</h5>
		</header>
		<div class="content">
			<audio class="audio-player" controls>
				<source src="http://data.babsoweb.com/babsodata/tom/herbiestyle.mp3" />
			</audio>
		</div>
	</article>

    <!-- Source code -->
    <div class="formatter-container formatter-hide no-js">
        <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
        <div class="formatter-content formatter-code">
            <div class="formatter-content">
<pre class="language-html line-numbers"><code class="language-html">// Image
&lt;figure style="max-width:300px">
    &lt;img src="path/to/picture.ext" alt="{@fwkboost.image}" />
&lt;/figure>
&lt;figure style="max-width:300px">
    &lt;img src="path/to/picture.ext" alt="{@fwkboost.image}" />
    &lt;figcaption>{@fwkboost.caption.image}&lt;/figcaption>
&lt;/figure>
<br />
// Lightbox
&lt;a href="path/to/picture.ext" data-lightbox="formatter" data-rel="lightcase:collection" aria-label="Lorem ipsum">
    &lt;img style="max-width: 150px" src="path/to/picture.ext" alt="Lorem ipsum" />
&lt;/a>
&lt;a href="path/to/picture.ext" data-lightbox="formatter" data-rel="lightcase:collection" aria-label="Lorem ipsum">
    &lt;img style="max-width: 150px" src="path/to/picture.ext" alt="Sit dolor amet" />
&lt;/a>
<br />
// Youtube
&lt;div class="media-content" style="width: 800px; height: 450px">
    &lt;iframe class="youtube-player" src="url/to/youtube/embeded/link" allowfullscreen=""></iframe> // Resize height="" to the right size
&lt;/div>
<br />
// Movie
&lt;div class="media-content" style="width: 800px; height: 450px">
    &lt;video class="video-player" controls>
        &lt;source src="path/to/video/file.ext" type="video/mp4" />
    &lt;/video>
&lt;/div>
<br />
// Sound
&lt;audio class="audio-player" controls>
    &lt;source src="path/to/video/audio.ext" />
&lt;/audio></code></pre>
            </div>
        </div>
    </div>
</div>
