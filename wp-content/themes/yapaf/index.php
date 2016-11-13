<?php
/**
 * Ficher principal du theme. 
 * On charge le header (header.php)
 */
 get_header();  
?>


<?php if(have_posts()): // Si il y a des posts on boucle dessus?> 
	<?php while(have_posts()): the_post(); ?>
		<article>
			<?php 
			/**
			 * ATTENTION : the_title() et get_the_title() sont deux fonctions différentes : 
			 * the_title() <==> echo apply_filters('the_title', get_the_title) 
			 */
			 ?>
			<h1><?php the_title(); ?></h1>
			
			<?php
			/**
			 * ATTENTION : De même que pour les fonctions title et get_the_title
			 **/
			 ?>
			<div>
				<?php the_content(); ?>
			</div>
			
			<?php 
			/**
			 *	the_post_thumbnail() permet d'afficher l'image à la une. 
			 *	Note : Si vous souhaitez ajouter un attribut à l'image il faut utiliser la fonction comme ceci : 
			 *	the_post_thumbnail('post-thumbnail', array('class' => 'img-responsive', 'rel' => 'toto' ...);
			 **/
			 ?>
			<div>
				<?php the_post_thumbnail(); ?>
			<div>
		</article>
	<?php endwhile; ?>
<?php else: // Sinon on affiche un message d'erreur?>
	<div class="errors">
		<?php 
		/**
		 * on utilise ici la fonction _e() qui permet de rendre la chaine traductible. 
		 * Le second paramètre représente le "text domain" définis dans le fichier style.css de notre theme.
		 **/
		 ?>
		<?php _e('Veuillez ecrire votre priemier article','skeleton'); ?>
	</div>
<?php endif; ?>


<?php
/**
 * On charge le foooter
 **/
get_footer(); ?>

