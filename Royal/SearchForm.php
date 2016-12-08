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
		$this->engine = Engine::getInstance();
	}

	/**
	 * @return string
	 */
	public function __toString() {
		$str = '<form method="POST" action="' . esc_attr( $this->action ) . '">';
		$str .= '<table><tbody>';
		$str .= $this->rowTaxonomy( 'contratto', "Tipo di contratto" );
		$str .= $this->rowTaxonomy( 'tipologia', "Tipologia di immobile" );
		$str .= $this->rowTaxonomy( 'comune', "Comune" );
//		foreach ( $this->engine->getFields() as $field ) {
//			$str .= $this->row( $field->getLabel(), $field->getSearchField() );
//		}
		$str .= '</tbody><tfoot>';
		$str .= $this->row( '&nbsp;', '<input type="submit" value="Cerca immobile">' );
		$str .= '</tfoot></table>';
		$str .= '</form>';

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
	 * @param string $label
	 *
	 * @return string
	 */
	private function rowTaxonomy( $taxonomy, $label ) {
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
					$values = isset( $query['terms'] ) ? $query['terms'] : [];
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

		return $this->row( $label, $composite );
	}

}