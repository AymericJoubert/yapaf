<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

<div style="height:100vh;position:relative;background:#000;">
	<div style="font-size:10vw;color:#80805B;position:absolute;top:50%;left:50%;display:block;transform:translate(-50%, -50%);">
		R.T.F.M
	</div>
</div>

<?php get_footer(); ?>

