<?php
namespace Royal;

/**
 * Class SearchForm
 * @package Royal
 */
class SearchForm {
	use Tools;
	/**
	 * @var string
	 */
	private $action;
	/**
	 * @var Engine
	 */
	private $engine;
	/**
	 * @var int
	 */
	private $tax = 0;
	/**
	 * @var int
	 */
	private $meta = 0;
	/**
	 * @var array
	 */
	private $query = [ ];

	/**
	 * SearchForm constructor.
	 *
	 * @param string $action
	 * @param array $query
	 */
	public function __construct( $action, $query ) {
		$this->action = $action;
		$this->query  = $query;
		royalQueryOverrider( $this->query, 'rs_com', 'comune' );
		royalQueryOverrider( $this->query, 'rs_con', 'contratto' );
		royalQueryOverrider( $this->query, 'rs_tip', 'tipologia' );

		$this->engine = Engine::getInstance();
	}

	/**
	 * @return string
	 */
	public function __toString() {
		$str = $this->action
			? '<form method="POST" action="' . esc_attr( $this->action ) . '">'
			: '';
		$str .= '<table class="searchform"><tbody>';
		$str .= $this->rowTaxonomy( 'contratto' );
		$str .= $this->rowTaxonomy( 'tipologia' );
		$str .= $this->rowTaxonomy( 'comune' );
		$metaQuery = isset( $this->query['meta_query'] ) ? $this->query['meta_query'] : [ ];
		foreach ( $this->engine->getFields() as $field ) {
			if ( $field->isSearcheable() ) {
				$str .= $this->row( $field->getLabel(), $field->getSearchField( $this->meta, $metaQuery ) );
				$this->meta ++;
			}
		}
		$str .= '</tbody>';
		if ( $this->action ) {
			$str .= '<tfoot>';
			$str .= $this->row( '&nbsp;', '<input type="submit" value="Cerca immobile">' );
			$str .= '</tfoot>';
		}
		$str .= '</table>';
		if ( $this->action ) {
			$str .= '</form>';
		}

		return $str;
	}

	/**
	 * @param string $label
	 * @param string $field
	 *
	 * @return string
	 */
	private function row( $label, $field ) {
		return sprintf( '<tr><th>%s</th><td>%s</td></tr>', $label, $field );
	}


	/**
	 * @param string $taxonomy
	 *
	 * @return string
	 */
	private function rowTaxonomy( $taxonomy ) {
		$tax = get_taxonomy( $taxonomy );
		if ( ! $tax ) {
			return '';
		}
		/** @var \WP_Term[] $terms */
		$terms  = get_terms( [
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
		] );
		$fields = [ ];
		$values = [ ];
		if ( isset( $this->query['tax_query'] ) ) {
			foreach ( $this->query['tax_query'] as $query ) {
				if ( $query['taxonomy'] == $taxonomy ) {
					$values = isset( $query['terms'] ) ? $query['terms'] : [ ];
					break;
				}
			}
		}
		foreach ( $terms as $term ) {
			$current  = $this->htmlTag( 'input', [
				'type'    => 'checkbox',
				'name'    => 'royalsearch[tax_query][' . $this->tax . '][terms][]',
				'value'   => $term->term_id,
				'checked' => in_array( $term->term_id, $values ) ? 'checked' : null
			] );
			$fields[] = '<label>' . $current . '&nbsp;' . $term->name . '</label>';
		}

		$composite = implode( '<br>', $fields );
		$composite .= $this->htmlTag( 'input', [
			'type'  => 'hidden',
			'name'  => 'royalsearch[tax_query][' . $this->tax . '][field]',
			'value' => 'term_id'
		] );
		$composite .= $this->htmlTag( 'input', [
			'type'  => 'hidden',
			'name'  => 'royalsearch[tax_query][' . $this->tax . '][taxonomy]',
			'value' => $taxonomy
		] );

		$this->tax ++;

		return $this->row( $tax->labels->menu_name, $composite );
	}

}