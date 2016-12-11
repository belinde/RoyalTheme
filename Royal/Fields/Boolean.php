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
	protected function searchFieldExact( &$fieldNum, $metaQuery ) {
		return $this->htmlTag( 'input', [
			'type'    => 'checkbox',
			'name'    => 'royalsearch[meta_query][' . $fieldNum . '][value]',
			'value'   => '1',
			'checked' => $this->findValue( $metaQuery, '=' ) ? 'checked' : null
		] ) . $this->htmlTag( 'input', [
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
	protected function searchFieldText() {
		return null;
	}

	/**
	 * @return null
	 */
	protected function searchFieldRange() {
		return null;
	}


}