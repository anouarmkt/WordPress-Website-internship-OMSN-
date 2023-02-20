<?php
/**
 * Class to build up query args.
 *
 * @package RT_Team
 */

namespace RT\Team\Helpers;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Query Args Class
 */
class QueryArgs {

	/**
	 * Query Args.
	 *
	 * @var array
	 */
	private $args = [];

	/**
	 * Meta values.
	 *
	 * @var array
	 */
	private $meta = [];

	/**
	 * Method to build args
	 *
	 * @param array $meta Meta values.
	 * @param bool  $isCarousel Layout type.
	 *
	 * @return array
	 */
	public function buildArgs( array $meta, bool $isCarousel = false ) {
		$this->meta = $meta;

		// Post Type.
		$this->getPostType();

		// Building Args.
		$this
			->postParams()
			->orderParams()
			->paginationParams( $isCarousel )
			->taxParams();

		return $this->args;
	}

	/**
	 * Post type.
	 *
	 * @return void
	 */
	private function getPostType() {
		$this->args['post_type']   = [ rttlp_team()->post_type ];
		$this->args['post_status'] = 'publish';
	}

	/**
	 * Post parameters.
	 *
	 * @return AQueryArgs
	 */
	private function postParams() {
		$post_in     = ( isset( $this->meta['postIn'] ) ? sanitize_text_field( implode( ', ', $this->meta['postIn'] ) ) : null );
		$post_not_in = ( isset( $this->meta['postNotIn'] ) ? sanitize_text_field( implode( ', ', $this->meta['postNotIn'] ) ) : null );
		$limit       = ( ( empty( $this->meta['limit'] ) || $this->meta['limit'] === '-1' ) ? 10000000 : (int) $this->meta['limit'] );

		if ( $post_in ) {
			$post_in                = explode( ',', $post_in );
			$this->args['post__in'] = $post_in;
		}

		if ( $post_not_in ) {
			$post_not_in                = explode( ',', $post_not_in );
			$this->args['post__not_in'] = $post_not_in;
		}

		$this->args['posts_per_page'] = $limit;

		return $this;
	}

	/**
	 * Order & Orderby parameters.
	 *
	 * @return AQueryArgs
	 */
	private function orderParams() {
		$order_by = ( isset( $this->meta['order_by'] ) ? esc_html( $this->meta['order_by'] ) : null );
		$order    = ( isset( $this->meta['order'] ) ? esc_html( $this->meta['order'] ) : null );

		if ( $order ) {
			$this->args['order'] = $order;
		}

		if ( $order_by ) {
			$this->args['orderby'] = $order_by;
		}

		return $this;
	}

	/**
	 * Pagination parameters.
	 *
	 * @param bool $isCarousel Layout type.
	 *
	 * @return AQueryArgs
	 */
	private function paginationParams( $isCarousel ) {
		$pagination = ! empty( $this->meta['pagination'] );
		$limit      = ( ( empty( $this->meta['limit'] ) || $this->meta['limit'] === '-1' ) ? 10000000 : (int) $this->meta['limit'] );

		if ( $pagination ) {
			$posts_per_page = ( ! empty( $this->meta['postsPerPage'] ) ? intval( $this->meta['postsPerPage'] ) : $limit );

			if ( $posts_per_page > $limit ) {
				$posts_per_page = $limit;
			}

			$this->args['posts_per_page'] = $posts_per_page;

			if ( is_front_page() ) {
				$paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
			} else {
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			}

			$offset              = $posts_per_page * ( (int) $paged - 1 );
			$this->args['paged'] = $paged;

			if ( intval( $this->args['posts_per_page'] ) > $limit - $offset ) {
				$this->args['posts_per_page'] = $limit - $offset;
				$this->args['offset']         = $offset;
			}
		}

		if ( $isCarousel ) {
			$this->args['posts_per_page'] = $limit;
		}

		return $this;
	}

	/**
	 * Taxonomy parameters.
	 *
	 * @return AQueryArgs
	 */
	private function taxParams() {
		$departmentId  = ( isset( $this->meta['department_ids'] ) ? array_filter( $this->meta['department_ids'] ) : [] );
		$designationId = ( isset( $this->meta['designation_ids'] ) ? array_filter( $this->meta['designation_ids'] ) : [] );
		$taxQ          = [];

		if ( is_array( $departmentId ) && ! empty( $departmentId ) ) {
			$taxQ[] = [
				'taxonomy' => rttlp_team()->taxonomies['department'],
				'field'    => 'term_id',
				'terms'    => $departmentId,
				'operator' => 'IN',
			];
		}

		if ( ! empty( $designationId ) && is_array( $designationId ) ) {
			$taxQ[] = [
				'taxonomy' => rttlp_team()->taxonomies['designation'],
				'field'    => 'term_id',
				'terms'    => $designationId,
				'operator' => 'IN',
			];
		}

		if ( count( $taxQ ) >= 2 ) {
			$taxQ['relation'] = $this->meta['relation'];
		}

		if ( ! empty( $taxQ ) ) {
			$this->args['tax_query'] = $taxQ;
		}

		if ( in_array( '_taxonomy_filter', $this->meta['filters'], true ) && $this->meta['taxFilter'] && $this->meta['action_term'] ) {
			$this->args['tax_query'] = [
				[
					'taxonomy' => $this->meta['taxFilter'],
					'field'    => 'term_id',
					'terms'    => [ $this->meta['action_term'] ],
				],
			];
		}

		return $this;
	}

}
