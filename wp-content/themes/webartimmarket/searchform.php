<form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div>
		<input placeholder="поиск по сайту" type="text" value="<?php echo get_search_query(); ?>" name="s" id="s" />
	</div>
</form>