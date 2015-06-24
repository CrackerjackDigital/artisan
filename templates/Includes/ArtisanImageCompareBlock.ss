<div class="grid-$ArtisanWidth $LayoutCSSClasses">
	<article class="$ExtraCSSClasses">
	<%-- <hr> --%>
		<figure class="cd-image-container">
			$ArtisanHasImages.Last
			<%-- <img src="assets/img/ph-modified.jpg" alt="Modified Image"> --%>
			<span class="cd-image-label" data-type="original">After</span>

			<div class="cd-resize-img"> <!-- the resizable image on top -->
				$ArtisanHasImages.First
				<span class="cd-image-label" data-type="modified">Before</span>
			</div>

			<span class="cd-handle"></span>
		</figure> <!-- cd-image-container -->
	</article>
</div>
<% require css('artisan/css/plugin-img-compare.css') %>
<% require javascript('artisan/js/plugin-img-compare.js') %>