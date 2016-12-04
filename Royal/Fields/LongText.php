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
class LongText extends AbstractField {
	/**
	 * @param mixed $value
	 *
	 * @return void
	 */
	protected function html( $value ) {
		echo $this->htmlTag( 'textarea', [
			'name'  => $this->fieldName(),
			'id'    => $this->slug,
			'class' => 'large-text',
			'rows'  => 4
		], trim( $value ) );
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
		return $value ;
	}
}