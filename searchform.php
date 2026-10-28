<?php
/**
 * Search form (get_search_form()) — pill input + gold button, same shape as the newsletter
 * form (.newsletter-form), styled as .site-search in patterns.css. Used on 404.php and search.php.
 */
?>
<form role="search" method="get" class="site-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="site-search-input">Search for:</label>
	<input type="search" id="site-search-input" name="s" placeholder="Search cruises, events, ports..." value="<?php echo esc_attr( get_search_query() ); ?>" />
	<button type="submit" class="btn btn-gold">Search</button>
</form>
