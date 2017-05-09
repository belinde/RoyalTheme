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
	const URL_RISULTATI = '/ricerca/risultati/';
	const GOOGLE_APIKEY = 'AIzaSyDJSI3JyE0TU8DiXQAHHfHUXVt-qo3OZWE';
	const MAIL_INFO = 'f.traversaro@gmail.com';
	use Tools;
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
		add_action( 'after_switch_theme', [ $this, 'actionAfterSwitchTheme' ] );
		add_action( 'init', [ $this, 'actionInit' ] );
		add_action( 'admin_init', [ $this, 'actionAdminInit' ] );
		add_action( 'admin_menu', [ $this, 'actionAdminMenu' ] );
		add_action( 'save_post_annuncio', [ $this, 'actionSavePostAnnuncio' ], 10, 2 );
		add_action( 'save_post_ricerca', [ $this, 'actionSavePostRicerca' ], 10, 2 );
		add_action( 'admin_print_styles-post.php', [ $this, 'actionAdminPrintStylesPostPhp' ] );
		add_action( 'after_setup_theme', [ $this, 'actionAfterSetupTheme' ] );
		add_action( 'manage_posts_custom_column', [ $this, 'actionManagePostsCustomColumn' ], 10, 2 );
		add_action( 'wp_enqueue_scripts', [ $this, 'actionWpEnqueueScripts' ] );
		add_action( 'wp_ajax_royalmail', [ $this, 'actionAjaxMail' ] );
		add_action( 'wp_ajax_nopriv_royalmail', [ $this, 'actionAjaxMail' ] );

		add_filter( 'template_include', [ $this, 'filterTemplateInclude' ] );
		add_filter( 'manage_annuncio_posts_columns', [ $this, 'filterManageAnnuncioPostsColumns' ] );
	}

	public function actionAdminMenu() {
		add_options_page( 'Royal', 'Royal', 'manage_options', 'royal', [ $this, 'optionsPage' ] );
	}

	public function optionsPage() {
		?>
		<div class="wrap">
			<h2>Configurazioni Royal</h2>
			<form action="options.php" method="POST">
				<?php settings_fields( 'royal-group' ); ?>
				<?php do_settings_sections( 'royal' ); ?>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	public function actionAdminInit() {
		add_settings_section( 'section-mail', 'Invio mail', [ $this, 'sectionMailing' ], 'royal' );
		$this->optfield( 'royal_mail_address', 'Indirizzo di spedizione' );
		$this->optfield( 'royal_mail_from', 'Nome visualizzato' );
		$this->optfield( 'royal_mail_smtp_host', 'SMTP host' );
		$this->optfield( 'royal_mail_smtp_port', 'SMTP port' );
		$this->optfield( 'royal_mail_smtp_user', 'SMTP username' );
		$this->optfield( 'royal_mail_smtp_pass', 'SMTP password' );
		$this->optfield( 'royal_mail_subject', 'Oggetto della mail' );
		$this->optfield( 'royal_mail_body', 'Testo, inserire {LINK}', 'fieldText' );
	}

	/**
	 * @param $slug
	 * @param $label
	 * @param string $render
	 */
	private function optfield( $slug, $label, $render = 'fieldString' ) {
		add_settings_field(
			$slug,
			$label,
			[ $this, $render ],
			'royal',
			'section-mail',
			[ 'opt' => $slug ]
		);
		register_setting( 'royal-group', $slug );
	}

	/**
	 * @param $args
	 */
	public function fieldString( $args ) {
		echo $this->htmlTag( 'input', [
			'name'  => $args['opt'],
			'id'    => $args['opt'],
			'type'  => 'text',
			'class' => 'regular-text',
			'value' => get_option( $args['opt'] )
		] );
	}

	/**
	 * @param $args
	 */
	public function fieldText( $args ) {
		$content = get_option( $args['opt'] );
		echo $this->htmlTag( 'textarea', [
			'name'  => $args['opt'],
			'id'    => $args['opt'],
			'type'  => 'text',
			'class' => 'regular-text',
			'rows'  => 30
		], $content ? $content : '' );
	}

	public function sectionMailing() {
		echo "<p>Parametri per l'invio delle mail da parte del sistema.</p>";
	}

	/**
	 * @param array $errors
	 * @param string $field
	 * @param integer $check
	 * @param string $message
	 *
	 * @return mixed
	 */
	private function check( &$errors, $field, $check, $message ) {
		$value = filter_input( INPUT_POST, $field, $check );
		if ( ! $value ) {
			$errors[] = $message;
		}

		return $value;
	}

	public function actionAjaxMail() {
		$errors   = [ ];
		$nome     = $this->check( $errors, 'nome', FILTER_SANITIZE_STRING, "Il nome immesso non valido" );
		$testo    = $this->check( $errors, 'testo', FILTER_SANITIZE_STRING, "Il messaggio non può essere vuoto" );
		$mail     = $this->check( $errors, 'email', FILTER_SANITIZE_EMAIL, "L'indirizzo email non è valido" );
		$annuncio = $this->check( $errors, 'annuncio', FILTER_SANITIZE_STRING, "Annuncio non valido" );

		if ( ! $errors ) {
			$headers = [
				sprintf( 'From: %s <%s>', $nome, $mail )
			];
			if ( ! wp_mail( self::MAIL_INFO, "Richiesta informazioni su $annuncio", $testo, $headers ) ) {
				$errors[] = "Impossibile inviare il messaggio, riprovare più tardi.";
			}
		}
		ob_clean();
		header( 'Content-Type: application/json' );
		echo json_encode( $errors );
		die();
	}

	public function actionWpEnqueueScripts() {
		wp_enqueue_script(
			'royalScripts',
			get_template_directory_uri() . '/js/royal.js',
			[ 'jquery' ],
			null,
			true
		);
		wp_enqueue_script(
			'royalScripts',
			get_template_directory_uri() . '/js/royal.js',
			[ 'jquery' ],
			null,
			true
		);
		wp_localize_script( 'royalScripts', 'royalconf', [ 'ajax' => admin_url( 'admin-ajax.php' ) ] );
		wp_enqueue_script(
			'royalGoogleMaps',
			'https://maps.googleapis.com/maps/api/js?callback=royalInitMap&key=' . self::GOOGLE_APIKEY,
			[ 'royalScripts' ],
			null,
			true
		);
		wp_enqueue_style( 'royalRobotoFont', 'https://fonts.googleapis.com/css?family=Roboto:300,400,400i,700' );
		wp_enqueue_style( 'royalStyle', get_template_directory_uri() . '/style.css' );
	}

	/**
	 * @param $column
	 * @param $post_id
	 */
	public function actionManagePostsCustomColumn( $column, $post_id ) {
		if ( isset( $this->fields[ $column ] ) ) {
			$this->fields[ $column ]->printer( $post_id );
		}
	}

	/**
	 * @param array $columns
	 *
	 * @return array
	 */
	public function filterManageAnnuncioPostsColumns( $columns ) {
		unset( $columns['date'] );
		$columns['proprietario'] = $this->fields['proprietario']->getLabel();
		$columns['prezzo']       = $this->fields['prezzo']->getLabel();
		$columns['status']       = $this->fields['status']->getLabel();
		$columns['evidenza']     = $this->fields['evidenza']->getLabel();

		return $columns;
	}

	public function actionAfterSetupTheme() {
		add_theme_support( 'post-thumbnails', [ 'annuncio' ] );
		add_theme_support( 'title-tag' );
		add_image_size( 'royaltile', 383, 253, true );
		add_image_size( 'royalslide', 1280, 800, true );
		add_image_size( 'royalmap', 1280, 800, true );
		$args = [
			'width'         => 1920,
			'height'        => 700,
			'default-image' => get_template_directory_uri() . '/images/background.jpg',
			'uploads'       => true,
		];
		add_theme_support( 'custom-header', $args );
	}

	/**
	 * @param $data
	 * @param null|integer $singlePostId
	 *
	 * @return \WP_Query
	 */
	public function queryRicerca( $data, $singlePostId = null ) {
		$query = [
			'post_type'      => 'annuncio',
			'post_status'    => 'publish',
			'posts_per_page' => isset( $data['posts_per_page'] ) ? $data['posts_per_page'] : 20,
			'meta_query'     => [
				[ 'key' => $this->fields['status']->metaSlug(), 'value' => 'disponibile' ]
			]
		];
		royalQueryOverrider( $query, 'rs_com', 'comune' );
		royalQueryOverrider( $query, 'rs_con', 'contratto' );
		royalQueryOverrider( $query, 'rs_tip', 'tipologia' );

		$this->decorateQuery( $query, $data, 'tax_query', 'terms' );
		$this->decorateQuery( $query, $data, 'meta_query', 'value' );
		if ( $singlePostId ) {
			$query['p'] = $singlePostId;
		}

		return new \WP_Query( $query );
	}

	/**
	 * @param array $query
	 * @param array $data
	 * @param string $section
	 * @param string $check
	 */
	private function decorateQuery( &$query, $data, $section, $check ) {
		if ( isset( $data[ $section ] ) ) {
			foreach ( $data[ $section ] as $metaQuery ) {
				if ( isset( $metaQuery[ $check ] ) and $this->isTrue( $metaQuery[ $check ] ) ) {
					$query[ $section ][] = $metaQuery;
				}
			}
		}
	}

	/**
	 * @param string $template
	 *
	 * @return string
	 */
	public function filterTemplateInclude( $template ) {
		/** @var \WP_Query $wp_query */
		global $wp_query;
		if ( is_home() ) {
			$ricerca = get_query_var( 'ricerca' );
			if ( in_array( $ricerca, [ 'risultati', 'mappa', 'avanzata', 'salva' ] ) ) {
				$wp_query->is_home = false;

				return get_query_template( 'ricerca-' . $ricerca );
			}
		}

		return $template;
	}

	public function actionAdminPrintStylesPostPhp() {
		wp_enqueue_style( 'royal-admin', get_template_directory_uri() . '/admin.css' );
	}

	public function actionAfterSwitchTheme() {
		$this->actionInit();
		flush_rewrite_rules();
		wp_insert_term( 'Affitto', 'contratto' );
		wp_insert_term( 'Vendita', 'contratto' );
		wp_insert_term( 'Nuda proprietà', 'contratto' );

		wp_insert_term( 'Appartamento', 'tipologia' );
		wp_insert_term( 'Villa', 'tipologia' );
		wp_insert_term( 'Box', 'tipologia' );
		wp_insert_term( 'Terreno', 'tipologia' );
		wp_insert_term( 'Rustico', 'tipologia' );
		wp_insert_term( 'Negozio', 'tipologia' );
		wp_insert_term( 'Ufficio', 'tipologia' );
		wp_insert_term( 'Capannone', 'tipologia' );

		wp_insert_term( 'Chiavari', 'comune' );
		wp_insert_term( 'Lavagna', 'comune' );
		wp_insert_term( 'Zoagli', 'comune' );
		wp_insert_term( 'Leivi', 'comune' );
		wp_insert_term( 'Cogorno', 'comune' );
		wp_insert_term( 'Carasco', 'comune' );
		wp_insert_term( 'San Colombano Certenoli', 'comune' );
		wp_insert_term( 'Sestri Levante', 'comune' );
		wp_insert_term( 'Casarza', 'comune' );
		wp_insert_term( 'Rapallo', 'comune' );
		wp_insert_term( 'Santa Margherita Ligure', 'comune' );
		wp_insert_term( 'Borgotaro', 'comune' );
		wp_insert_term( 'Recco', 'comune' );
		wp_insert_term( 'Ne', 'comune' );
		wp_insert_term( 'Mezzanego', 'comune' );
		wp_insert_term( 'Castiglione Chiavarese', 'comune' );
	}

	public function actionInit() {
		static $init = false;
		if ( $init ) {
			return;
		}
		$init = true;
		register_nav_menu( 'menuprincipale', 'Menù principale' );
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
			'supports'             => [ 'title', 'editor', 'thumbnail' ],
			'register_meta_box_cb' => [ $this, 'metaboxCallbackAnnuncio' ],
			'taxonomies'           => [ 'contratto', 'tipologia', 'comune' ],
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

		register_post_type( 'ricerca', [
			'label'                => 'Ricerche',
			'labels'               => [
				'name'                  => 'Ricerche',
				'singular_name'         => 'Ricerca',
				'menu_name'             => 'Ricerche salvate',
				'name_admin_bar'        => 'Ricerca',
				'add_new'               => 'Aggiungi',
				'add_new_item'          => 'Aggiungi ricerca',
				'new_item'              => 'Nuova ricerca',
				'edit_item'             => 'Modifica ricerca',
				'view_item'             => 'Visualizza ricerca',
				'all_items'             => 'Tutte le ricerche',
				'search_items'          => 'Cerca tra le ricerche',
				'parent_item_colon'     => 'Ricerca genitore',
				'not_found'             => 'Nessuna ricerca trovata',
				'not_found_in_trash'    => 'Nessuna ricerca trovata nel cestino',
				'archives'              => 'Archivio ricerche',
				'insert_into_item'      => "Inserisci nella ricerca",
				'uploaded_to_this_item' => "Carica in questa ricerca"
			],
			'description'          => "Ricerche salvate da utenti e amministratori",
			'public'               => true,
			'menu_icon'            => 'dashicons-search',
			'supports'             => [ 'title' ],
			'register_meta_box_cb' => [ $this, 'metaboxCallbackRicerca' ],
			'has_archive'          => false,
			'exclude_from_search'  => true,
			'publicly_queryable'   => false,
			'show_in_nav_menus'    => false
		] );
	}

	public function metaboxCallbackRicerca() {
		add_meta_box(
			'royal_ricerca_form',
			"Ricerca",
			[ $this, 'metaboxRicercaCallback' ],
			'ricerca',
			'advanced',
			'high'
		);
		add_meta_box(
			'royal_ricerca_form_persona',
			"Persona interessata",
			[ $this, 'metaboxRicercaPersonaCallback' ],
			'ricerca',
			'advanced',
			'high'
		);
	}

	public function metaboxCallbackAnnuncio() {
		add_meta_box(
			'royal_gallery_annuncio',
			"Galleria fotografica",
			[ $this, 'metaboxGalleryCallback' ],
			'annuncio',
			'advanced',
			'low'
		);
		add_meta_box(
			'royal_maps_annuncio',
			"Planimetrie",
			[ $this, 'metaboxMapsCallback' ],
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
		add_meta_box(
			'royal_dati_ricerche',
			"Contatti interessati",
			[ $this, 'metaboxPersoneCallback' ],
			'annuncio',
			'side',
			'high'
		);
	}

	/**
	 * @param \WP_Post $post
	 */
	public function metaboxRicercaCallback( \WP_Post $post ) {
		wp_nonce_field( __FUNCTION__, 'royal_ricerca_nonce' );
		$query = get_post_meta( $post->ID, 'royal_ricerca', true );
		if ( ! is_array( $query ) ) {
			$query = [ ];
		}
		echo new SearchForm( null, $query );
	}

	/**
	 * @param \WP_Post $post
	 */
	public function metaboxRicercaPersonaCallback( \WP_Post $post ) {
		wp_nonce_field( __FUNCTION__, 'royal_ricercapersona_nonce' );
		$persona = get_post_meta( $post->ID, 'royal_interesse', true );
		if ( $persona instanceof Interesse ) {
			echo '<p><strong>Inserimento:</strong> ' . $persona->getSince()->format( 'j/n/Y, H:i' ) . '</p>';
		} else {
			$persona = new Interesse();
		}
		echo '<table class="form-table"><tbody>';
		echo '<tr>';
		echo '<th scope="row"><label for="royalNome">Nome:</label></th>';
		echo '<td><input type="text" name="interesse[nome]" id="royalNome" value="' . esc_attr( $persona->getName() ) . '"></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th scope="row"><label for="royalMail">E-mail:</label></th>';
		echo '<td><input type="text" name="interesse[mail]" id="royalMail" value="' . esc_attr( $persona->getMail() ) . '"></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th scope="row"><label for="royalPhone">Telefono:</label></th>';
		echo '<td><input type="text" name="interesse[telefono]" id="royalPhone" value="' . esc_attr( $persona->getPhone() ) . '"></td>';
		echo '</tr>';
		echo '</tbody></table>';
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @return string
	 */
	private function mapUrl( \WP_Post $post ) {
		$indirizzo = get_post_meta( $post->ID, $this->fields['indirizzo']->metaSlug(), true );
		$params    = [
			'size'     => '500x350',
			'maptype'  => 'roadmap', // roadmap, satellite, hybrid, terrain
			'language' => 'it',
			'markers'  => $indirizzo,
			'zoom'     => 14,
			'key'      => self::GOOGLE_APIKEY
		];

		return 'https://maps.googleapis.com/maps/api/staticmap?' . http_build_query( $params );
	}

	/**
	 * @param \WP_Post $post
	 */
	public function metaboxPersoneCallback( \WP_Post $post ) {
		global $wpdb;
		$ricerche = $wpdb->get_results( "
			SELECT
			    r.meta_value AS ricerca,
			    i.meta_value AS interesse
			FROM {$wpdb->postmeta} AS r
			    JOIN {$wpdb->postmeta} AS i
			        ON r.post_id = i.post_id
			        AND i.meta_key = 'royal_interesse'
			WHERE r.meta_key = 'royal_ricerca'" );
		echo '<table>';
		echo '<thead><tr><th>Persona</th><th>Contatti</th></tr></thead>';
		echo '<tbody>';
		$printed = false;
		foreach ( $ricerche as $r ) {
			$interesse = @unserialize( $r->interesse );
			$ricerca   = @unserialize( $r->ricerca );
			if ( is_array( $ricerca ) and $interesse instanceof Interesse ) {
				$query = $this->queryRicerca( $ricerca, $post->ID );
				if ( $query->have_posts() ) {
					echo '<tr>';
					echo '<td>' . $interesse->getName() . '<br>' . $interesse->getSince()->format( 'j/n/Y' ) . '</td>';
					echo '<td>';
					echo '<a href="tel:' . $interesse->getPhone() . '">' . $interesse->getPhone() . '</a><br>';
					echo '<a href="mailto:' . $interesse->getMail() . '">' . $interesse->getMail() . '</a>';
					echo '</td>';
					echo '</tr>';
					$printed = true;
				}
			}
		}
		if ( ! $printed ) {
			echo '<tr><td colspan="2">Nessun contatto è interessato a questo annuncio.</td></tr>';
		}
		echo '</tbody></table>';
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
		echo '<img src="' . $this->mapUrl( $post ) . '">';
	}

	/**
	 * @return Fields\AbstractField[]
	 */
	public function getFields() {
		return $this->fields;
	}

	/**
	 * @param $slug
	 */
	public function theSingleInfo( $slug ) {
		if ( isset( $this->fields[ $slug ] ) ) {
			$field = $this->fields[ $slug ];
			$post  = get_post();
			if ( $field->hasValue( $post ) ) {
				$field->printer( $post );
			}
		}
	}

	public function theInformations() {
		$post = get_post();
		foreach ( $this->fields as $field ) {
			if ( $field->isPublic() and $field->hasValue( $post ) ) {
				$field->show( $post );
			}
		}
	}

	/**
	 * @return null|\WP_Query
	 */
	public function queryRelateds() {
		$dataContr = wp_get_post_terms( get_the_ID(), 'contratto' );
		$dataTipo  = wp_get_post_terms( get_the_ID(), 'tipologia' );
		if ( $dataTipo and $dataContr ) {
			/** @var \WP_Term $contratto */
			$contratto = array_shift( $dataContr );
			/** @var \WP_Term $tipologia */
			$tipologia = array_shift( $dataTipo );
			printf( '<h3>%s in %s</h3>', $tipologia->name, $contratto->name );

			return $this->queryRicerca( [
				'posts_per_page' => 4,
				'tax_query'      => [
					[
						'terms'    => [ $contratto->term_id ],
						'field'    => 'term_id',
						'taxonomy' => 'contratto'
					],
					[
						'terms'    => [ $tipologia->term_id ],
						'field'    => 'term_id',
						'taxonomy' => 'tipologia'
					]
				]
			] );
		}

		return null;
	}

	/**
	 * @return bool
	 */
	public function hasMap() {
		return (bool) trim( get_post_meta( get_the_ID(), $this->fields['indirizzo']->metaSlug(), true ) );
	}

	public function theMap() {
		echo $this->htmlTag( 'div', [
			'id'             => 'royalMap',
			'style'          => 'height:300px;width:100%',
			'data-indirizzo' => get_post_meta( get_the_ID(), $this->fields['indirizzo']->metaSlug(), true )
		], '' );
	}

	/**
	 * @param $type
	 *
	 * @return string
	 */
	private function galleryShortcode( $type ) {
		$meta = ( $type == 'photos' ) ? 'royal_gallery_annuncio' : 'royal_maps_annuncio';

		return strip_tags( get_post_meta( get_the_ID(), $meta, true ) );
	}

	/**
	 * @param string $type
	 *
	 * @return bool
	 */
	public function hasGallery( $type = 'photos' ) {
		return (bool) $this->galleryShortcode( $type );
	}

	/**
	 * @param string $type
	 */
	public function theGallery( $type = 'photos' ) {
		add_shortcode( 'gallery', function ( $atts ) use ( $type ) {
			$_attachments = get_posts( [
				'include'        => $atts['ids'],
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => 'ASC',
				'orderby'        => 'post__in',
				'posts_per_page' => - 1
			] );

			$output = '';
			$size   = ( $type == 'photos' ) ? 'royalslide' : 'royalmap';
			$count  = 0;
			if ( $type == 'photos' ) {
				$url = get_the_post_thumbnail_url( null, 'royalslide' );
				if ( $url ) {
					$output .= '<div data-slideshowId="' . $count . '" class="' . ( $count == 0 ? 'selected' : '' ) . '" style="background-image:url(\'' . $url . '\')"></div>';
					$count ++;
				}
			}
			foreach ( $_attachments as $val ) {
				$url = wp_get_attachment_image_src( $val->ID, $size );
				if ( $url ) {
					$output .= '<div data-slideshowId="' . $count . '" class="' . ( $count == 0 ? 'selected' : '' ) . '" style="background-image:url(\'' . $url[0] . '\')"></div>';
					$count ++;
				}
			}

			return $output;
		} );
		add_filter( 'use_default_gallery_style', '__return_false' );
		echo do_shortcode( $this->galleryShortcode( $type ) );
		add_shortcode( 'gallery', 'gallery_shortcode' );
		remove_filter( 'use_default_gallery_style', '__return_false' );
	}

	/**
	 * @param string $type
	 */
	public function theGalleryThumbs( $type = 'photos' ) {
		add_shortcode( 'gallery', function ( $atts ) use ( $type ) {
			$_attachments = get_posts( [
				'include'        => $atts['ids'],
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => 'ASC',
				'orderby'        => 'post__in',
				'posts_per_page' => - 1
			] );

			$output = '';
			$size   = ( $type == 'photos' ) ? 'royalslide' : 'royalmap';
			$count  = 0;
			if ( $type == 'photos' ) {
				$url = get_the_post_thumbnail_url( null, 'royalslide' );
				if ( $url ) {
					$output .= '<div onClick="moveGallery(' . $count . ', \'' . $type . '\')" data-slideshowId="' . $count . '" class="selected" style="background-image:url(\'' . $url . '\')"></div>';
					$count ++;
				}
			}
			foreach ( $_attachments as $val ) {
				$url = wp_get_attachment_image_src( $val->ID, $size );
				if ( $url ) {
					$output .= '<div onClick="moveGallery(' . $count . ', \'' . $type . '\')" data-slideshowId="' . $count . '" style="background-image:url(\'' . $url[0] . '\')"></div>';
					$count ++;
				}
			}

			return $output;
		} );
		add_filter( 'use_default_gallery_style', '__return_false' );
		echo do_shortcode( $this->galleryShortcode( $type ) );
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
	 * @param \WP_Post $post
	 */
	public function metaboxMapsCallback( \WP_Post $post ) {
		wp_nonce_field( __FUNCTION__, 'royal_maps_nonce' );
		$nobuttons = function () {
			return [ [ ] ];
		};
		add_filter( 'teeny_mce_buttons', $nobuttons );
		wp_editor(
			get_post_meta( $post->ID, 'royal_maps_annuncio', true ),
			'royalMapsEditor',
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
	 * @param integer|null $postId
	 *
	 * @return integer|null
	 */
	public function editRicerca( $postId = null ) {
		if ( ! $postId ) {
			$postId = wp_insert_post( [
				'post_title'  => wp_generate_uuid4(),
				'post_status' => 'publish',
				'post_type'   => 'ricerca'
			] );
		}
		if ( ! $postId ) {
			return null;
		}
		update_post_meta(
			$postId,
			'royal_ricerca',
			isset( $_POST['royalsearch'] ) ? $_POST['royalsearch'] : [ ]
		);
		if ( isset( $_POST['interesse'] ) ) {
			$int = $_POST['interesse'];
			update_post_meta(
				$postId,
				'royal_interesse',
				new Interesse(
					isset( $int['nome'] ) ? $int['nome'] : null,
					isset( $int['mail'] ) ? $int['mail'] : null,
					isset( $int['telefono'] ) ? $int['telefono'] : null
				)
			);
		}

		return $postId;
	}

	/**
	 * @param integer $postId
	 * @param \WP_Post $post
	 */
	public function actionSavePostRicerca( $postId, \WP_Post $post ) {
		$postType = get_post_type_object( $post->post_type );
		if ( current_user_can( $postType->cap->edit_post, $postId ) and
		     isset( $_POST['royal_ricerca_nonce'], $_POST['royal_ricercapersona_nonce'] ) and
		     wp_verify_nonce( $_POST['royal_ricerca_nonce'], 'metaboxRicercaCallback' ) and
		     wp_verify_nonce( $_POST['royal_ricercapersona_nonce'], 'metaboxRicercaPersonaCallback' )
		) {
			$this->editRicerca( $postId );
		}
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
		if ( isset( $_POST['royal_maps_nonce'] )
		     and wp_verify_nonce( $_POST['royal_maps_nonce'], 'metaboxMapsCallback' )
		) {
			update_post_meta(
				$postId,
				'royal_maps_annuncio',
				isset( $_POST['royalMapsEditor'] ) ? $_POST['royalMapsEditor'] : ''
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
