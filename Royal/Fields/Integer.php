<?php
/**
 * Created by PhpStorm.
 * User: belinde
 * Date: 02/12/16
 * Time: 15.05
 */

namespace Royal\Fields;

/**
 * Class Integer
 * @package Royal\Fields
 */
class Integer extends Text {
	/**
	 * @return string
	 */
	protected function fieldType() {
		return 'number';
	}

	/**
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	protected function filter( $value ) {
		return (integer) $value;
	}

	/**
	 * @return string
	 */
	protected function metaQueryType() {
		return 'UNSIGNED';
	}

	/**
	 * @param mixed $value
	 *
	 * @return string
	 */
	protected function format( $value ) {
		return number_format( $value, 0, ',', '.' );
	}
}