<section class="grid-container">
	<% loop Blocks.Sort('ArtisanSort') %>
	<div class="grid-$ArtisanWidth $LayoutCSSClasses">
		$Me
	</div>
	<% end_loop %>
</section>
