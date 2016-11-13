<?php
/**
 * On définit le header par défaut du site web
 * Soit la balise head & body (on ferme la balise body dans le fichier footer.php)
 **/
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	
	<?php 
	/**
	 * On charge ici HTML5shiv[https://github.com/aFarkas/html5shiv].
	 * si le navigateur est inferieur à IE 9 (Less Than[lt] IE 9)
     **/
	?>
	<!--[if lt IE 9]>
		<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
	
	<?php
	/**
	 * La fonction wp_head permet de charger les scripts et les style à l'aide des fonctions : 
	 * - wp_enqueue_style
	 * - wp_enqueue_script
	 **/
	?>
	
	<?php wp_head(); ?>
</head>

<?php 
/**
 * On ajoute l'attribut style et on recupere la valeur de l'option de theme. 
 * Si cette option n'est pas renseigné, on utilise le second parametre comme valeur
 * Cf inc/customizer.php
 **/
?>
<body <?php body_class(); ?> style="background-color:<?= get_theme_mod( 'background_color', '#FFF' ); ?>" >
	
	<?php
	/**
	 * On définit ici le header
	 **/
	?>
	<header>
	
		<div id="logo">
			<?php
			$custom_logo_id = get_theme_mod( 'custom_logo' ); // On recuperer l'option de theme 'custom_logo' definis dans le customizer (cf function.php);
			$image = wp_get_attachment_image_src( $custom_logo_id , 'full' );  // On recupere l'attribut src de l'image
			if($image[0] !=  ''): // Si src est renseigné, on affiche le logo 
			?>
				<a href="<?= esc_url( home_url( '/' )); ?>"> <?php // La fonction home_url('/') permet de recuperer l'url de la racine du site ?>
					<?php 
					/**
					 * La fonction get_bloginfo permet de recuperer des informations sur le site 
					 * @see : https://developer.wordpress.org/reference/functions/get_bloginfo/
					 **/
					 ?>
					<img src="<?= $image[0] ?>" alt="<?= get_bloginfo('description'); ?>"> 
				</a>
			<?php endif; ?>
		</div>
		
		
		<div id="menu">
			<?php
			/**
			 * La fonction wp_nav_menu permet de charger un menu définit dans le back office
			 * La déclaration de ce menu ce fait dans le fichier function.php
			 **/
			?>
			<?php wp_nav_menu( array( 'theme_location' => 'Top' ) ); ?>
		</div>
	</header>
	