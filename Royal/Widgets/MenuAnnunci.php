<?php
/**
 * Created by PhpStorm.
 * User: belinde
 * Date: 13/12/16
 * Time: 21.57
 */

namespace Royal\Widgets;

/**
 * Class MenuAnnunci
 * @package Royal\Widgets
 */
class MenuAnnunci extends \WP_Widget {
	/**
	 * @var \WP_Term[]
	 */
	private $contratti = [ ];
	/**
	 * @var \WP_Term[]
	 */
	private $comuni = [ ];
	/**
	 * @var \WP_Term[]
	 */
	private $tipologie = [ ];

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		parent::__construct(
			'royal_menu_annunci',
			'Menù annunci',
			[
				'classname'   => 'royal_menu_annunci',
				'description' => 'Menù degli annunci, ordinati per contratto, categoria e comune',
			]
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		foreach ( get_terms( 'comune' ) as $term ) {
			$this->comuni[ $term->term_id ] = $term;
		}
		foreach ( get_terms( 'contratto' ) as $term ) {
			$this->contratti[ $term->term_id ] = $term;
		}
		foreach ( get_terms( 'tipologia' ) as $term ) {
			$this->tipologie[ $term->term_id ] = $term;
		}
		global $wpdb;
		echo $args['before_widget'];
		echo $args['before_title'] . "Menù annunci" . $args['after_title'];
		$data = $wpdb->get_results( "
		SELECT
		    p.ID,
		    tr.term_taxonomy_id,
		    tt.term_id,
		    tt.taxonomy,
		    t.name
		FROM
		    {$wpdb->posts} AS p
		    INNER JOIN {$wpdb->postmeta} AS pm
		        ON pm.post_id = p.ID
		        AND pm.meta_key = 'royal_meta_status'
		        AND pm.meta_value = 'disponibile'
		    LEFT JOIN {$wpdb->term_relationships} AS tr
		        ON tr.object_id = p.ID
		    LEFT JOIN {$wpdb->term_taxonomy} AS tt
		        ON tt.term_taxonomy_id = tr.term_taxonomy_id
		    LEFT JOIN {$wpdb->terms} AS t
		        ON t.term_id = tt.term_id
		WHERE p.post_status = 'publish'
		    AND p.post_type = 'annuncio'" );

		$structured = [ ];
		$posts      = [ ];
		foreach ( $data as $row ) {
			$posts[ $row->ID ][ $row->taxonomy ][ $row->term_id ] = $row->name;
			if ( $row->taxonomy == 'contratto' ) {
				$structured[ $row->term_id ]['posts'][ $row->ID ] = $row->ID;
			}
		}
		foreach ( $structured as $contratto => $rowContratto ) {
			foreach ( $rowContratto['posts'] as $postId ) {
				foreach ( $posts[ $postId ]['tipologia'] as $tipologia => $tiponame ) {
					$structured[ $contratto ][ $tipologia ]['posts'][ $postId ] = $postId;
				}
			}
			unset( $structured[ $contratto ]['posts'] );
			foreach ( $structured[ $contratto ] as $tipologia => $rowTipologia ) {
				foreach ( $rowTipologia['posts'] as $postId ) {
					foreach ( $posts[ $postId ]['comune'] as $comune => $comunename ) {
						$structured[ $contratto ][ $tipologia ][ $comune ][ $postId ] = $postId;
					}
				}
				unset( $structured[ $contratto ][ $tipologia ]['posts'] );
			}
		}

		echo '<ul>';
		foreach ( $structured as $contratto => $listaContratto ) {
			echo '<li>';
			$this->linker( $this->contratti[ $contratto ] );
			echo '<ul>';
			foreach ( $listaContratto as $tipologia => $listaTipologia ) {
				echo '<li>';
				$this->linker( $this->tipologie[ $tipologia ] );
				echo '<ul>';
				foreach ( $listaTipologia as $comune => $listaPosts ) {
					echo '<li>';
					$this->linker( $this->comuni[ $comune ] );
					echo ' (' . count( $listaPosts ) . ')';
					echo '</li>';
				}
				echo '</ul>';
				echo '</li>';
			}
			echo '</ul>';
			echo '</li>';
		}
		echo '</ul>';
		echo $args['after_widget'];
	}

	private function linker( \WP_Term $term ) {
		$term_link = get_term_link( $term );

		if ( is_wp_error( $term_link ) ) {
			return;
		}

		echo '<a href="' . esc_url( $term_link ) . '">' . $term->name . '</a>';
	}
}