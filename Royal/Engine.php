<?php
/**
 * Created by PhpStorm.
 * User: belinde
 * Date: 03/12/16
 * Time: 19.02
 */

namespace Royal;

use Royal\Fields\AbstractField;

/**
 * Class Engine
 * @package Royal
 */
class Engine {
	/**
	 * @var Engine
	 */
	private static $instance;
	/**
	 * @var AbstractField[]
	 */
	private $fields = [ ];

	/**
	 * Plugin constructor.
	 */
	private function __construct() {
		/** @var AbstractField[] $fields */
		$fields = require __DIR__ . '/fields.php';
		foreach ( $fields as $field ) {
			$this->fields[ $field->getSlug() ] = $field;
		}
		register_activation_hook( __FILE__, [ $this, 'activationHook' ] );
		add_action( 'init', [ $this, 'actionInit' ] );
		add_filter( 'template_include', [ $this, 'filterTemplateInclude' ] );
		add_action( 'save_post_annuncio', [ $this, 'actionSavePostAnnuncio' ], 10, 2 );
		add_action( 'admin_print_styles-post.php', [ $this, 'actionAdminPrintStylesPostPhp' ] );
	}

	/**
	 * @param $data
	 *
	 * @return \WP_Query
	 */
	public function queryRicerca( $data ) {
		$query = [
			'post_type'      => 'annuncio',
			'post_status'    => 'publish',
			'posts_per_page' => 20,
			'meta_query' => [
				[ 'key' => $this->fields['status']->metaSlug(), 'value' => 'disponibile' ]
			]
		];
		$this->decorateQuery($query, $data, 'tax_query', 'terms');
		$this->decorateQuery($query, $data, 'meta_query', 'value');

		return new \WP_Query( $query );
	}

	/**
	 * @param array $query
	 * @param array $data
	 * @param string $section
	 * @param string $check
	 */
	private function decorateQuery(&$query, $data, $section, $check) {
		if ( isset( $data[$section] ) ) {
			foreach ( $data[$section] as $metaQuery ) {
				if ( isset( $metaQuery[$check] ) and $this->isValue($metaQuery[$check]) ) {
					$query[$section][] = $metaQuery;
				}
			}
		}
	}

	/**
	 * @param mixed $val
	 *
	 * @return bool
	 */
	private function isValue($val) {
		if ( is_numeric($val)) {
			return (bool)intval($val);
		}
		if ( is_array($val) or is_object($val)) {
			return (bool)$val;
		}
		return (bool)trim($val);
	}

	/**
	 * @param string $template
	 *
	 * @return string
	 */
	public function filterTemplateInclude( $template ) {
		if ( is_home() ) {
			$ricerca = get_query_var( 'ricerca' );
			if ( in_array( $ricerca, [ 'risultati' ] ) ) {
				return get_query_template( 'ricerca-risultati' );
			}
		}

		return $template;
	}

	public function actionAdminPrintStylesPostPhp() {
		wp_enqueue_style( 'royal-admin', get_template_directory_uri() . '/style/admin.css' );
	}

	public function activationHook() {
		$this->actionInit();
		flush_rewrite_rules();
	}

	public function actionInit() {
		add_rewrite_endpoint( 'ricerca', \EP_ROOT );
		register_post_type( 'annuncio', [
			'label'                => 'Annunci',
			'labels'               => [
				'name'                  => 'Annunci',
				'singular_name'         => 'Annuncio',
				'menu_name'             => 'Annunci',
				'name_admin_bar'        => 'Annuncio',
				'add_new'               => 'Aggiungi',
				'add_new_item'          => 'Crea nuovo annuncio',
				'new_item'              => 'Nuovo annuncio',
				'edit_item'             => 'Modifica annuncio',
				'view_item'             => 'Visualizza annuncio',
				'all_items'             => 'Tutti gli annunci',
				'search_items'          => 'Cerca tra gli annunci',
				'parent_item_colon'     => 'Annuncio genitore',
				'not_found'             => 'Nessun annuncio trovato',
				'not_found_in_trash'    => 'Nessun annuncio trovato nel cestino',
				'archives'              => 'Archivio annunci',
				'insert_into_item'      => "Inserisci nell'annuncio",
				'uploaded_to_this_item' => "Carica in questo annuncio"
			],
			'description'          => "Annunci di vendita o affitto immobili",
			'public'               => true,
			'menu_icon'            => 'dashicons-admin-multisite',
			'supports'             => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
			'register_meta_box_cb' => [ $this, 'metaboxCallback' ],
			'taxonomies'           => [ 'contratto', 'tipologia' ],
			'has_archive'          => true
		] );
		register_taxonomy( 'contratto', 'annuncio', [
			'label'             => 'Contratti',
			'labels'            => [
				'name'          => 'Contratti',
				'menu_name'     => 'Tipi di contratto',
				'singular_name' => 'Contratto',
				'all_items'     => "Tutti i contratti",
				'edit_item'     => "Modifica contratto",
				'view_item'     => "Visualizza contratto",
				'update_item'   => "Aggiorna contratto",
				'add_new_item'  => "Aggiungi nuovo contratto",
				'new_item_name' => "Nuovo nome di contratto",
				'search_items'  => "Cerca contratti",
				'popular_items' => "Contratti più comuni",
				'not_found'     => "Nessun contratto trovato"
			],
			'public'            => true,
			'show_admin_column' => true,
			'description'       => "Tipo di contratto",
			'hierarchical'      => true
		] );
		register_taxonomy( 'comune', 'annuncio', [
			'label'             => 'Comuni',
			'labels'            => [
				'name'          => 'Comuni',
				'menu_name'     => 'Comuni',
				'singular_name' => 'Comune',
				'all_items'     => "Tutti i comuni",
				'edit_item'     => "Modifica comune",
				'view_item'     => "Visualizza comune",
				'update_item'   => "Aggiorna comune",
				'add_new_item'  => "Aggiungi nuovo comune",
				'new_item_name' => "Nuovo nome di comune",
				'search_items'  => "Cerca comuni",
				'popular_items' => "Comuni con più annunci",
				'not_found'     => "Nessun comune trovato"
			],
			'public'            => true,
			'show_admin_column' => true,
			'description'       => "Comuni",
			'hierarchical'      => true
		] );
		register_taxonomy( 'tipologia', 'annuncio', [
			'label'             => 'Tipologie',
			'labels'            => [
				'name'          => 'Tipologie',
				'menu_name'     => 'Tipologie di immobile',
				'singular_name' => 'Tipologia',
				'all_items'     => "Tutte le tipologie",
				'edit_item'     => "Modifica tipologia",
				'view_item'     => "Visualizza tipologia",
				'update_item'   => "Aggiorna tipologia",
				'add_new_item'  => "Aggiungi nuova tipologia",
				'new_item_name' => "Nuovo nome di tipologia",
				'search_items'  => "Cerca tipologie",
				'popular_items' => "Tipologie più comuni",
				'not_found'     => "Nessuna tipologia trovata"
			],
			'public'            => true,
			'show_admin_column' => true,
			'description'       => "Tipologia di immobile",
			'hierarchical'      => true
		] );
		register_taxonomy_for_object_type( 'contratto', 'annuncio' );
		register_taxonomy_for_object_type( 'tipologia', 'annuncio' );
		register_taxonomy_for_object_type( 'comune', 'annuncio' );


//		flush_rewrite_rules();
	}

	/**
	 */
	public function metaboxCallback() {
		add_meta_box(
			'royal_gallery_annuncio',
			"Galleria fotografica",
			[ $this, 'metaboxGalleryCallback' ],
			'annuncio',
			'advanced',
			'low'
		);
		add_meta_box(
			'royal_dati_annuncio',
			"Informazioni",
			[ $this, 'metaboxDatiCallback' ],
			'annuncio',
			'advanced',
			'high'
		);
	}

	/**
	 * @param \WP_Post $post
	 */
	public function metaboxDatiCallback( \WP_Post $post ) {
		wp_nonce_field( __FUNCTION__, 'royal_dati_nonce' );
		echo '<table class="form-table"><tbody>';
		foreach ( $this->fields as $field ) {
			$field->formTableRow( $post );
		}
		echo '</tbody></table>';
	}

	/**
	 * @return Fields\AbstractField[]
	 */
	public function getFields() {
		return $this->fields;
	}

	public function theInformations() {
		$post = get_post();
		echo '<dl class="royal_informations">';
		foreach ( $this->fields as $field ) {
			if ( $field->hasValue( $post ) ) {
				$field->show( $post );
			}
		}
		echo '</dl>';
	}

	public function theGallery() {
		$postId    = get_the_ID();
		$shortcode = strip_tags( get_post_meta( $postId, 'royal_gallery_annuncio', true ) );
		add_shortcode( 'gallery', function ( $attr ) {
			$attr['orderby']    = 'menu_order';
			$attr['order']      = 'ASC';
			$attr['columns']    = 2;
			$attr['size']       = 'medium';
			$attr['itemtag']    = 'div';
			$attr['icontag']    = 'div';
			$attr['captiontag'] = 'span';
			$attr['link']       = 'file';

			return gallery_shortcode( $attr );
		} );
		add_filter( 'use_default_gallery_style', '__return_false' );
		echo do_shortcode( $shortcode );
		add_shortcode( 'gallery', 'gallery_shortcode' );
		remove_filter( 'use_default_gallery_style', '__return_false' );
	}

	/**
	 * @param \WP_Post $post
	 */
	public function metaboxGalleryCallback( \WP_Post $post ) {
		wp_nonce_field( __FUNCTION__, 'royal_gallery_nonce' );
		$nobuttons = function () {
			return [ [ ] ];
		};
		add_filter( 'teeny_mce_buttons', $nobuttons );
		wp_editor(
			get_post_meta( $post->ID, 'royal_gallery_annuncio', true ),
			'royalGalleryEditor',
			[
				'wpautop'          => false,
				'media_buttons'    => true,
				'drag_drop_upload' => true,
				'textarea_rows'    => 5,
				'teeny'            => true,
				'tinymce'          => true,
				'quicktags'        => false
			]
		);
		remove_filter( 'teeny_mce_buttons', $nobuttons );
	}


	/**
	 * @param integer $postId
	 * @param \WP_Post $post
	 */
	public function actionSavePostAnnuncio( $postId, \WP_Post $post ) {
		$postType = get_post_type_object( $post->post_type );
		if ( ! current_user_can( $postType->cap->edit_post, $postId ) ) {
			return;
		}
		if ( isset( $_POST['royal_gallery_nonce'] )
		     and wp_verify_nonce( $_POST['royal_gallery_nonce'], 'metaboxGalleryCallback' )
		) {
			update_post_meta(
				$postId,
				'royal_gallery_annuncio',
				isset( $_POST['royalGalleryEditor'] ) ? $_POST['royalGalleryEditor'] : ''
			);
		}

		if ( isset( $_POST['royal_dati_nonce'] )
		     and wp_verify_nonce( $_POST['royal_dati_nonce'], 'metaboxDatiCallback' )
		) {
			foreach ( $this->fields as $field ) {
				$field->save( $post );
			}
		}
	}

	/**
	 * @return Engine
	 */
	static public function getInstance() {
		if ( ! ( self::$instance instanceof self ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}