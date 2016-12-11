<?php
/**
 * Created by PhpStorm.
 * User: belinde
 * Date: 05/12/16
 * Time: 23.26
 */

namespace Royal\Fields;

/**
 * Class Select
 * @package Royal\Fields
 */
class Select extends AbstractField {
	/**
	 * @var array
	 */
	protected $values = [ ];

	/**
	 * @param array $values
	 *
	 * @return $this
	 */
	public function setValues( $values ) {
		$this->values = (array) $values;

		return $this;
	}

	/**
	 * @param mixed $selected
	 *
	 * @return string
	 */
	private function asOptions( $selected = null ) {
		$content = $this->htmlTag( 'option', [ 'value' => '' ], '&nbsp;' );
		foreach ( $this->values as $val => $label ) {
			$content .= $this->htmlTag( 'option', [
				'value'    => $val,
				'selected' => ( $val == $selected ) ? 'selected' : null
			], $label );
		}

		return $content;
	}

	/**
	 * @param mixed $value
	 *
	 * @return void
	 */
	protected function html( $value ) {
		echo $this->htmlTag( 'select', [
			'name'  => $this->fieldName(),
			'id'    => $this->slug,
			'class' => 'regular-text'
		], $this->asOptions( $value ) );
	}

	/**
	 * @param integer $fieldNum
	 * @param array $metaQuery
	 *
	 * @return string
	 */
	protected function searchFieldExact( &$fieldNum, $metaQuery ) {
		return $this->htmlTag( 'select', [
			'name' => 'royalsearch[meta_query][' . $fieldNum . '][value]'
		], $this->asOptions( $this->findValue( $metaQuery, '=' ) ) ) . $this->htmlTag( 'input', [
			'type'  => 'hidden',
			'name'  => 'royalsearch[meta_query][' . $fieldNum . '][key]',
			'value' => $this->metaSlug()
		] ) . $this->htmlTag( 'input', [
			'type'  => 'hidden',
			'name'  => 'royalsearch[meta_query][' . $fieldNum . '][compare]',
			'value' => '='
		] );
	}

	/**
	 * @return null
	 */
	function searchFieldText() {
		return null;
	}

	/**
	 * @return string
	 */
	function searchFieldRange( &$fieldNum, $metaQuery ) {
		$options = [ ];
		$value   = $this->findValue( $metaQuery, 'IN' );
		if ( ! $value ) {
			$value = [ ];
		}
		foreach ( $this->values as $key => $label ) {
			$options[] = sprintf(
				'<label>%s&nbsp;%s</label>',
				$this->htmlTag( 'input', [
					'type'    => 'checkbox',
					'name'    => 'royalsearch[meta_query][' . $fieldNum . '][value][]',
					'value'   => $key,
					'checked' => in_array( $key, $value ) ? 'checked' : null
				] ),
				$label
			);
		}

		return implode( '<br>', $options ) . $this->htmlTag( 'input', [
			'type'  => 'hidden',
			'name'  => 'royalsearch[meta_query][' . $fieldNum . '][key]',
			'value' => $this->metaSlug()
		] ) . $this->htmlTag( 'input', [
			'type'  => 'hidden',
			'name'  => 'royalsearch[meta_query][' . $fieldNum . '][compare]',
			'value' => 'IN'
		] );
	}

	/**
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	protected function filter( $value ) {
		return isset( $this->values[ $value ] ) ? $value : null;
	}

	/**
	 * @param mixed $value
	 *
	 * @return string
	 */
	protected function format( $value ) {
		return isset( $this->values[ $value ] ) ? $this->values[ $value ] : null;
	}
}