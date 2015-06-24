	<article class="$ExtraCSSClasses">
		<h5>$Title</h5>
		<hr/>

		<% if ArtisanHasFiles %>
		<p>
			<% loop ArtisanHasFiles %>
					<a href="$Link" title="$LinkTitle">$Name&nbsp;($Extension)</a><br/>
			<% end_loop %>
		</p>
		<% end_if %>
	</article>



