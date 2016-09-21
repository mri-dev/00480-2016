<?php get_header(); ?>
<?
	$hotel_id = $wp_query->query_vars['hotel_id'];
	$ajanlat 	= new ViasaleAjanlat($hotel_id);
?>
<div id="content" class="full-width">
 	<pre>
		<? print_r($ajanlat->term_data); ?>
 	</pre>
</div>
<?php get_footer();
