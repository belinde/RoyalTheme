<?php
/**
 * Created by PhpStorm.
 * User: belinde
 * Date: 02/12/16
 * Time: 14.53
 */

namespace Royal\Fields;

use Royal\Tools;

/**
 * Class AbstractField
 * @package Royal
 */
abstract class AbstractField {
	const SEARCH_DISABLED = 'disabled';
	const SEARCH_EXACT = 'exact';
	const SEARCH_RANGE = 'range';
	const SEARCH_TEXT = 'text';

	use Tools;

	/**
	 * @var string
	 */
	protected $slug;
	/**
	 * @var string
	 */
	protected $label;
	/**
	 * @var string
	 */
	protected $append;
	/**
	 * @var string
	 */
	protected $empty;
	/**
	 * @var string
	 */
	protected $help;
	/**
	 * @var string
	 */
	protected $search;
	/**
	 * @var bool
	 */
	protected $internal;

	/**
	 * @return string
	 */
	protected function fieldName() {
		return 'royalmeta[' . $this->slug . ']';
	}

	/**
	 * Field constructor.
	 *
	 * @param string $slug
	 * @param string $label
	 */
	public function __construct( $slug, $label = null ) {
		if ( ! $label ) {
			$label = ucfirst( $slug );
		}
		$this->setSlug( $slug )->setLabel( $label );
		$this->setSearch( self::SEARCH_DISABLED );
	}

	/**
	 * @return string
	 */
	public function metaSlug() {
		return 'royal_meta_' . $this->slug;
	}

	/**
	 * @param mixed $value
	 *
	 * @return void
	 */
	abstract protected function html( $value );

	/**
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	abstract protected function filter( $value );

	/**
	 * @param mixed $value
	 *
	 * @return string
	 */
	abstract protected function format( $value );

	/**
	 * @param \WP_Post $post
	 */
	public function save( \WP_Post $post ) {
		$value = $this->filter( isset( $_POST['royalmeta'][ $this->slug ] ) ? $_POST['royalmeta'][ $this->slug ] : null );
		update_post_meta( $post->ID, $this->metaSlug(), $value );
	}

	/**
	 * @param \WP_Post $post
	 */
	public function formTableRow( \WP_Post $post ) {
		echo '<tr>';
		echo '<th scope="row">';
		printf( '<label for="%s">%s</label>', $this->slug, $this->label );
		echo '</th>';
		echo '<td>';
		$this->html( get_post_meta( $post->ID, $this->metaSlug(), true ) );
		if ( $this->append ) {
			echo '<span class="appended">' . $this->append . '</span>';
		}
		if ( $this->empty ) {
			echo '<p class="description smaller">Se lasciato vuoto verrà scritto <strong>' . $this->empty . '</strong>.</p>';
		}
		if ( $this->help ) {
			echo '<p class="description">' . $this->help . '</p>';
		}
		echo '</td>';
		echo '<tr>';
	}

	/**
	 * @param integer $fieldNum
	 * @param array $metaQuery
	 *
	 * @return string
	 */
	protected function searchFieldExact( &$fieldNum, $metaQuery ) {
		return $this->htmlMetaTag( $fieldNum, $metaQuery, '=' );
	}

	/**
	 * @param integer $fieldNum
	 * @param array $metaQuery
	 *
	 * @return string
	 */
	protected function searchFieldText( &$fieldNum, $metaQuery ) {
		return $this->htmlMetaTag( $fieldNum, $metaQuery, 'LIKE' );
	}

	/**
	 * @param integer $fieldNum
	 * @param array $metaQuery
	 *
	 * @return string
	 */
	protected function searchFieldRange( &$fieldNum, $metaQuery ) {
		$min = $this->htmlMetaTag( $fieldNum, $metaQuery, '>=' );
		$fieldNum ++;
		$max = $this->htmlMetaTag( $fieldNum, $metaQuery, '<=' );

		return "tra $min e $max";
	}

	/**
	 * @param integer $fieldNum
	 * @param array $metaQuery
	 * @param string $comparation
	 *
	 * @return string
	 */
	protected function htmlMetaTag( $fieldNum, $metaQuery, $comparation ) {
		return $this->htmlTag( 'input', [
			'type'  => 'text',
			'name'  => 'royalsearch[meta_query][' . $fieldNum . '][value]',
			'value' => $this->findValue( $metaQuery, $comparation )
		] ) . $this->htmlTag( 'input', [
			'type'  => 'hidden',
			'name'  => 'royalsearch[meta_query][' . $fieldNum . '][key]',
			'value' => $this->metaSlug()
		] ) . $this->htmlTag( 'input', [
			'type'  => 'hidden',
			'name'  => 'royalsearch[meta_query][' . $fieldNum . '][compare]',
			'value' => $comparation
		] ) . $this->htmlTag( 'input', [
			'type'  => 'hidden',
			'name'  => 'royalsearch[meta_query][' . $fieldNum . '][type]',
			'value' => $this->metaQueryType()
		] );
	}

	/**
	 * @return string
	 */
	protected function metaQueryType() {
		return 'CHAR';
	}

	/**
	 * @param $metaQuery
	 * @param $comparation
	 *
	 * @return null
	 */
	protected function findValue( $metaQuery, $comparation ) {
		foreach ( $metaQuery as $meta ) {
			if ( $meta['compare'] == $comparation and $meta['key'] == $this->metaSlug() ) {
				return $meta['value'];
			}
		}

		return null;
	}

	/**
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * @return bool
	 */
	public function isSearcheable() {
		return ( $this->search != self::SEARCH_DISABLED );
	}

	/**
	 * @param integer $fieldNum
	 * @param array $metaQuery
	 *
	 * @return null|string
	 */
	public function getSearchField( &$fieldNum, $metaQuery ) {
		switch ( $this->search ) {
			case self::SEARCH_EXACT:
				return $this->searchFieldExact( $fieldNum, $metaQuery );
				break;
			case self::SEARCH_TEXT:
				return $this->searchFieldText( $fieldNum, $metaQuery );
				break;
			case self::SEARCH_RANGE:
				return $this->searchFieldRange( $fieldNum, $metaQuery );
				break;
		}

		return null;
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @return bool
	 */
	public function hasValue( \WP_Post $post ) {
		$raw = get_post_meta( $post->ID, $this->metaSlug(), true );

		return ( $this->isTrue( $raw ) or $this->empty );
	}

	/**
	 * @param $post
	 */
	public function show( $post ) {
		echo '<dt>' . $this->label . '</dt><dd>';
		$this->printer( $post );
		echo '</dd>';
	}

	/**
	 * @param mixed $post
	 */
	public function printer( $post ) {
		$postId = ( $post instanceof \WP_Post ) ? $post->ID : intval( $post );
		$raw    = get_post_meta( $postId, $this->metaSlug(), true );
		if ( $this->isTrue( $raw ) ) {
			$value    = $this->format( $raw );
			$hasValue = true;
		} else {
			if ( ! $this->empty ) {
				return;
			}
			$value    = $this->empty;
			$hasValue = false;
		}
		echo $value;
		if ( $this->append and $hasValue ) {
			echo '&thinsp;' . $this->append;
		}
	}

	/**
	 * @param string $slug
	 *
	 * @return $this
	 */
	public function setSlug( $slug ) {
		$this->slug = $slug;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getSlug() {
		return $this->slug;
	}

	/**
	 * @param string $text
	 *
	 * @return $this
	 */
	public function setHelp( $text ) {
		$this->help = $text;

		return $this;
	}

	/**
	 * @param string $label
	 *
	 * @return $this
	 */
	public function setLabel( $label ) {
		$this->label = $label;

		return $this;
	}

	/**
	 * @param string $append
	 *
	 * @return $this
	 */
	public function setAppend( $append ) {
		$this->append = $append;

		return $this;
	}

	/**
	 * @param string $empty
	 *
	 * @return $this
	 */
	public function setEmpty( $empty ) {
		$this->empty = $empty;

		return $this;
	}

	/**
	 * @param string $type
	 *
	 * @return $this
	 */
	public function setSearch( $type ) {
		$this->search = $type;

		return $this;
	}

	/**
	 * @return $this
	 */
	public function setInternal() {
		$this->internal = true;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function isPublic() {
		return ! $this->internal;
	}

}