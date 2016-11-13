<?php


/* --------------------
 * Les menus
 * --------------------*/
/**
 * La fonction register_nav_menus permet d'enregistrer plusieurs menu
 * Elle prends en paramettre un tableau associatif
 * La cl� reprensente l'identifiant du menu (ici Header ou Footer)
 * La valeur reprense le nom affich� en back-office
 **/
register_nav_menus( 
	array(
		'Header' => __('Navigation principale','yapaf'),
		'Footer' => __('Navigation alternative','yapaf'),
	) 
);


/* --------------------
 * Style et script
 * --------------------*/
/** 
 * On utilise le hook de Wordpress (Hook utilis� dans la fonction wp_head();) 
 * pour ajouter les scripts et les styles 
 * Ici on lance la fonction 'styleAndScripts' (pr�sente dans le fichier function) lors de l'execution du hook
 **/
add_action('wp_enqueue_scripts', 'styleAndScripts'); 
function styleAndScripts(){
	/**
	 * On charge le fichier style.css
	 * Le premier paramettre est un identifiant unique
	 * Le second est le chemin du script (on utilise la fonctino get_template_directory_uri afin de recuperer le chemin absolu du theme)
	 * Le troisiement facultatif est un tableau de d�pendance
	 **/
    wp_enqueue_style('bootstrap_css', get_template_directory_uri().'/css/bootstrap.min.css');
    wp_enqueue_style('font-awesome_css', get_template_directory_uri().'/css/font-awesome.min.css');
    wp_enqueue_style('style', get_template_directory_uri().'/style.css', array('bootstrap_css', 'font-awesome_css'));

    wp_enqueue_script('jquery', get_template_directory_uri(). '/js/jquery-3.1.1.min.js');
    wp_enqueue_script('bootstrap_js', get_template_directory_uri(). '/js/bootstrap.min.js', array('jquery'));

	// On indique explicitement que le fichier app.js � besoin de jquery pour charger
    wp_enqueue_script('app', get_template_directory_uri(). '/js/app.js', array('jquery'));
}


/* --------------------
 * Language
 * --------------------*/
/**
 * Ici on viens charger implicitement les fichier PO & PO apr�s l'activation du theme. 
 **/
function setup(){
    load_theme_textdomain('yapaf', get_template_directory() . '/lang');
}
add_action('after_setup_theme', 'setup');