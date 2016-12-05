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
		$content = '<option value="">&nbsp;</option>';
		foreach ( $this->values as $val => $label ) {
			$content .= sprintf(
				'<option%s value="%s">%s</option>',
				( $val == $selected ) ? ' selected="selected"' : '',
				esc_attr( $val ),
				esc_html( $label )
			);
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
	 * @return string
	 */
	function searchFieldExact() {
		return $this->htmlTag( 'select', [
			'name' => 'royalsearch[exact][' . $this->slug . ']',
		], $this->asOptions() );
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
	function searchFieldRange() {
		$options = [ ];
		foreach ( $this->values as $key => $label ) {
			$options[] = sprintf(
				'<label>%s&nbsp;%s</label>',
				$this->htmlTag( 'input', [
					'type'  => 'checkbox',
					'name'  => 'royalsearch[range][' . $this->slug . '][]',
					'value' => $key
				] ),
				$label
			);
		}

		return implode( '<br>', $options );
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