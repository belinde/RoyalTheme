<?php
/**
 * Created by PhpStorm.
 * User: belinde
 * Date: 02/12/16
 * Time: 15.04
 */

namespace Royal\Fields;

/**
 * Class Text
 * @package Royal\Fields
 */
class Text extends AbstractField {
	/**
	 * @return string
	 */
	protected function fieldType() {
		return 'text';
	}

	/**
	 * @param mixed $value
	 *
	 * @return void
	 */
	protected function html( $value ) {
		echo $this->htmlTag( 'input', [
			'name'  => $this->fieldName(),
			'type'  => $this->fieldType(),
			'id'    => $this->slug,
			'class' => 'regular-text',
			'value' => $value
		] );
	}

	/**
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	protected function filter( $value ) {
		return esc_html( trim( $value ) );
	}

	/**
	 * @param mixed $value
	 *
	 * @return string
	 */
	protected function format( $value ) {
		return $value;
	}
}