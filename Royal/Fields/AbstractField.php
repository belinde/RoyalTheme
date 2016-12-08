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
	}

	/**
	 * @return string
	 */
	private function metaSlug() {
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
	 * @return string
	 */
	function searchFieldExact() {
		return sprintf( 'esattamente <input type="text" name="royalsearch[exact][%s]">', $this->slug );
	}

	/**
	 * @return string
	 */
	function searchFieldText() {
		return sprintf( 'grossomodo <input type="text" name="royalsearch[text][%s]">', $this->slug );
	}

	/**
	 * @return string
	 */
	function searchFieldRange() {
		return sprintf( 'tra <input type="text" name="royalsearch[range][%s][min]"> e <input type="text" name="royalsearch[range][%s][max]">',
			$this->slug, $this->slug );
	}

	/**
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * @return null|string
	 */
	public function getSearchField() {
		switch ( $this->search ) {
			case self::SEARCH_EXACT:
				return $this->searchFieldExact();
				break;
			case self::SEARCH_TEXT:
				return $this->searchFieldText();
				break;
			case self::SEARCH_RANGE:
				return $this->searchFieldRange();
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

		return ( $raw or $this->empty );
	}

	/**
	 * @param \WP_Post $post
	 */
	public function show( \WP_Post $post ) {
		$raw = get_post_meta( $post->ID, $this->metaSlug(), true );
		if ( $raw ) {
			$value = $this->format( $raw );
		} else {
			if ( $this->empty ) {
				$value = $this->empty;
			} else {
				return;
			}
		}
		printf(
			'<dt>%s</dt><dd>%s%s</dd>',
			$this->label,
			$value,
			$this->append ? '&thinsp;' . $this->append : ''
		);
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

}