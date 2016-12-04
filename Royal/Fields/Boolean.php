<?php
/**
 * Created by PhpStorm.
 * User: belinde
 * Date: 02/12/16
 * Time: 15.11
 */

namespace Royal\Fields;

/**
 * Class Boolean
 * @package Royal\Fields
 */
class Boolean extends AbstractField {
	/**
	 * @param mixed $value
	 *
	 * @return void
	 */
	protected function html( $value ) {
		$attrs = [
			'name'  => $this->fieldName(),
			'type'  => 'checkbox',
			'id'    => $this->slug,
			'value' => '1'
		];
		if ( $value ) {
			$attrs['checked'] = 'checked';
		}
		echo $this->htmlTag( 'input', $attrs );
	}

	/**
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	protected function filter( $value ) {
		return (bool) $value;
	}

	/**
	 * @param mixed $value
	 *
	 * @return string
	 */
	protected function format( $value ) {
		return $value ? 'sì' : 'no';
	}

	/**
	 * @return string
	 */
	function searchFieldExact() {
		return $this->htmlTag( 'input', [
			'name'  => 'royalsearch[exact]['.$this->slug.']',
			'type'  => 'checkbox',
			'value' => '1'
		]);
	}

	/**
	 * @return null
	 */
	function searchFieldText() {
		return null;
	}

	/**
	 * @return null
	 */
	function searchFieldRange() {
		return null;
	}


}