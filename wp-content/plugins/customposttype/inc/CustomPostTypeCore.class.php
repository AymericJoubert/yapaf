<?php
    /**
     * Classe facilitant la gestion des custom post type
     * Class CustomPostTypeCore
     * @author : Maxime Caboche
     */
    class CustomPostTypeCore {

        protected $override = false;

        /**
         * Constructeur de la classe. Pas besoin de paramètres :
         *  + Enregistre le custom post type (function init)
         *  + Ajoute l'affichage (function showBox)
         *  + Gere l'enregestriment des données (function save_data)
         */
        public function __construct($override=false)
        {
            $this->override = $override;
            if(!$this->override){
                add_action('init', array($this, 'init'), 9);
            }
            add_action('admin_menu', array($this, 'showBox'), 1);
            add_action('save_post', array($this, 'save_data'),1);
            add_action('edit_post', array($this, 'save_data'),1);
            if(is_array(static::$taxonomy)){
                add_action( 'init', array($this, 'register_taxonomy'), 0 );
            }
        }

        /**
         * Function to register post type
         */
        public function init()
        {
            register_post_type(static::$postType, static::$customOption);
        }

        /**
         * Function to add box
         */
        public function showBox() {
            foreach(static::$metabox as $meta){
                add_meta_box($meta['id'], $meta['title'], array($this,'generateBox'), static::$postType, $meta['context'], $meta['priority'], $meta['fields']);
            }
        }

        /**
         * Genere l'affichage des input
         */
        public function generateBox($post, $meta) {
                echo '<input type="hidden" name="mytheme_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
                echo '<table class="form-table">';
                foreach ($meta['args'] as $field) {
                    // get current post meta data

                    echo '<tr>',
                    '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
                    '<td>';
                    switch ($field['type']) {
                        case 'text':
                            $this->text($field);
                            break;
                        case 'textarea':
                            $this->textarea($field);
                            break;
                        case 'select':
                            $this->select($field);
                            break;
                        case 'radio':
                            $this->radio($field);
                            break;
                        case 'checkbox':
                            $this->checkbox($field);
                            break;

                        case 'editor':
                            $this->editor($field);
                            break;
                    }
                    echo     '</td><td>',
                    '</td></tr>';
                }
                echo '</table>';


        }

        /**
         * Helper pour generer input type text
         * @param $field
         */
        private function text($field){
            global $post;
            $meta = get_post_meta($post->ID, $field['id'], true);
            echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />', '<br />', '<span class="aide">'.$field['desc'].'</span>';
        }

        /**
         * Helper pour generer textarea
         * @param $field
         */
        private function textarea($field){
            global $post;
            $meta = get_post_meta($post->ID, $field['id'], true);
            echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>', '<br />', '<span class="aide">'.$field['desc'].'</span>';
        }

        /**
         * Helper pour generer textarea
         * @param $field
         */
        private function select($field){
            global $post;
            $meta = get_post_meta($post->ID, $field['id'], true);
            echo '<select name="', $field['id'], '" id="', $field['id'], '">';
            foreach ($field['options'] as $option) {
                echo '<option  value="'.$option["value"].'" ', $meta == $option["value"] ? ' selected="selected"' : '', '>', $option['name'], '</option>';
            }
            echo '</select>';
            echo '<span class="aide">'.$field['desc'].'</span>';
        }

        /**
         * Helper pour generer radio button
         * @param $field
         */
        private function radio($field){
            global $post;
            $meta = get_post_meta($post->ID, $field['id'], true);
            foreach ($field['options'] as $option) {
                echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
                echo '<br>'.'<span class="aide">'.$field['desc'].'</span>';
            }
        }

        /**
         * Helper pour generer les checkbox
         * @param $field
         */
        private function checkbox($field){
            global $post;
            $meta = get_post_meta($post->ID, $field['id'], true);
            echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
        }

        /**
         * Helper pour generer Wysiwyg
         * @param $field
         */
        private function editor($field){
            global $post;
            $meta = get_post_meta($post->ID, $field['id'], true);
            echo wp_editor( $meta ? $meta : $field['std'], $field['id'], array(
                'wpautop'       => true,
                'media_buttons' => true,
                'textarea_name' => $field['id'],
                'textarea_rows' => 10,
                'teeny'         => false
            ) );
            echo "<em>".'<span class="aide">'.$field['desc'].'</span>'."</em>";
        }

        /**
         * Enregistre les données
         * @param $post_id
         * @return mixed
         */
        public function save_data($post_id) {
            $meta = static::$metabox;
            // verify nonce
            if(isset($_POST['mytheme_meta_box_nonce'])){
                foreach($meta as $meta_box) {
                    // check permissions
                    foreach ($meta_box['fields'] as $field) {
                        if($field['type'] != "fichier"){
                            $old = get_post_meta($post_id, $field['id'], true);
                            $new = $_POST[$field['id']];
                            /*if($field['id'] == 'homepage_paragraphe1'){
                                die(var_dump($new));
                            }*/
                            if(is_array($new)){
                                $new = base64_encode(serialize($new));
                                //wp_die(var_dump($new));
                            }
                            if ($new && $new != $old) {

                                update_post_meta($post_id, $field['id'], $new);
                            } elseif ('' == $new && $old) {
                                if ($field['id'] != "brochure") {
                                    delete_post_meta($post_id, $field['id'], $old);
                                }
                            }else {
                                update_post_meta($post_id, $field['id'], $new);
                            }
                        }else{
                            if(!empty($_FILES[$field['id']]['name'])) {

                                $supported_types = array('video/webm', 'video/mp4', 'video/ogg');
                                $arr_file_type = wp_check_filetype(basename($_FILES[$field['id']]['name']));
                                $uploaded_type = $arr_file_type['type'];

                                if(in_array($uploaded_type, $supported_types)) {
                                    $upload = wp_upload_bits($_FILES[$field['id']]['name'], null, file_get_contents($_FILES[$field['id']]['tmp_name']));
                                    if(isset($upload['error']) && $upload['error'] != 0) {
                                        wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
                                    } else {
                                        //wp_die(var_dump(base64_encode(serialize($upload))));
                                        update_post_meta($post_id, $field['id'], base64_encode(serialize($upload)));
                                    }
                                }
                                else {
                                    wp_die("The file type that you've uploaded is not a PDF.");
                                }
                            }

                        }
                        if (!wp_verify_nonce($_POST['mytheme_meta_box_nonce'], basename(__FILE__))) {
                            return $post_id;
                        }
                        // check autosave
                        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                            return $post_id;
                        }
                    }
                }
            }
        }

        /**
         * Enregistre une taxonomy
         */
        public function register_taxonomy(){
            foreach(static::$taxonomy as $array){

                register_taxonomy($array[0],$array[1],$array[2]);
            }
        }

    }