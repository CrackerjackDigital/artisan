	<article class="$ExtraCSSClasses">
		<div class="fotorama" data-arrows="false">
			<% loop $ArtisanHasImages %>
				$Me
			<% end_loop %>
		</div>
	</article>
<% require css('artisan/css/plugin-fotorama.css') %>
<% require javascript('artisan/js/plugin-fotorama.js') %>
