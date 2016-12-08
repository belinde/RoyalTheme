<?php
/**
 * Created by PhpStorm.
 * User: belinde
 * Date: 08/12/16
 * Time: 18.18
 */

namespace Royal;

/**
 * Class Tools
 * @package Royal
 */
trait Tools {

	/**
	 * @param string $tag
	 * @param array $attrs
	 * @param string|null $content
	 *
	 * @return string
	 */
	protected function htmlTag( $tag, $attrs, $content = null ) {
		$string = "<$tag";
		foreach ( $attrs as $key => $val ) {
			if ( ! is_null( $val ) ) {
				$string .= ' ' . $key . '="' . esc_attr( $val ) . '"';
			}
		}
		$string .= '>';
		if ( ! is_null( $content ) ) {
			$string .= $content . "</$tag>";
		}

		return $string;
	}
}