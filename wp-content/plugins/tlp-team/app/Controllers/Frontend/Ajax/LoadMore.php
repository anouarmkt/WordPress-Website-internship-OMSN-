<?php
/**
 * Load More Ajax Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Frontend\Ajax;

use RT\Team\Helpers\Fns;
use RT\Team\Helpers\Options;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Load More Ajax Class.
 */
class LoadMore {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'wp_ajax_ttp_Layout_Ajax_Action', [ $this, 'response' ] );
		add_action( 'wp_ajax_nopriv_ttp_Layout_Ajax_Action', [ $this, 'response' ] );
	}

	/**
	 * Ajax Response.
	 *
	 * @return void
	 */
	public function response() {
		$error = true;
		$msg   = $data = null;
		$paged = $total_pares = 1;

		if ( Fns::verifyNonce() ) {
			$scID = absint( $_REQUEST['scID'] );

			if ( $scID && ! is_null( get_post( $scID ) ) ) {
				$scMeta = get_post_meta( $scID );
				$layout = ( isset( $scMeta['layout'][0] ) ? $scMeta['layout'][0] : 'layout1' );

				if ( ! in_array( $layout, array_keys( Options::scLayout() ) ) ) {
					$layout = 'layout1';
				}

				$isIsotope  = preg_match( '/isotope/', $layout );
				$isCarousel = preg_match( '/carousel/', $layout );
				$isGrid     = preg_match( '/layout/', $layout );
				$allCol     = ! empty( $scMeta['ttp_column'][0] ) ? unserialize( $scMeta['ttp_column'][0] ) : [];
				$dCol       = ( ! empty( $allCol['desktop'] ) ? absint( $allCol['desktop'] ) : 4 );
				$tCol       = ( ! empty( $allCol['tab'] ) ? absint( $allCol['tab'] ) : 2 );
				$mCol       = ( ! empty( $allCol['mobile'] ) ? absint( $allCol['mobile'] ) : 1 );

				if ( ! in_array( $dCol, array_keys( Options::scColumns() ) ) ) {
					$dCol = 3;
				}

				if ( ! in_array( $tCol, array_keys( Options::scColumns() ) ) ) {
					$tCol = 2;
				}

				if ( ! in_array( $dCol, array_keys( Options::scColumns() ) ) ) {
					$mCol = 1;
				}

				$fImg            = ( ! empty( $scMeta['ttp_image'][0] ) ? true : false );
				$fImgSize        = ( isset( $scMeta['ttp_image_size'][0] ) ? $scMeta['ttp_image_size'][0] : 'medium' );
				$defaultImgId    = ( ! empty( $scMeta['default_preview_image'][0] ) ? absint( $scMeta['default_preview_image'][0] ) : null );
				$customImgSize   = ( ! empty( $scMeta['ttp_custom_image_size'][0] ) ? unserialize( $scMeta['ttp_custom_image_size'][0] ) : [] );
				$character_limit = ( isset( $scMeta['character_limit'][0] ) ? absint( $scMeta['character_limit'][0] ) : 0 );

				/* Argument create */
				$args                = [];
				$args['post_type']   = [ rttlp_team()->post_type ];
				$args['post_status'] = 'publish';

				/* post__in */
				if ( ! empty( $scMeta['ttp_post__in'] ) && is_array( $scMeta['ttp_post__in'] ) ) {
					$post__in         = $scMeta['ttp_post__in'];
					$args['post__in'] = $post__in;
				}
				/* post__not_in */
				if ( ! empty( $scMeta['ttp_post__not_in'] ) && is_array( $scMeta['ttp_post__not_in'] ) ) {
					$args['post__not_in'] = $scMeta['ttp_post__not_in'];
				}
				/* LIMIT */
				$limit                  = ( ( empty( $scMeta['ttp_limit'][0] ) || $scMeta['ttp_limit'][0] === '-1' ) ? 10000000 : (int) $scMeta['limit'][0] );
				$args['posts_per_page'] = $limit;
				$pagination             = ( ! empty( $scMeta['ttp_pagination'][0] ) ? true : false );

				if ( $pagination && ! $isCarousel ) {
					$posts_per_page = ( isset( $scMeta['ttp_posts_per_page'][0] ) ? absint( $scMeta['ttp_posts_per_page'][0] ) : $limit );

					if ( $posts_per_page > $limit ) {
						$posts_per_page = $limit;
					}
					// Set 'posts_per_page' parameter
					$args['posts_per_page'] = $posts_per_page;

					if ( is_front_page() ) {
						$paged = ! empty( $_REQUEST['page'] ) ? absint( $_REQUEST['page'] ) : 1;
					} else {
						$paged = ! empty( $_REQUEST['paged'] ) ? absint( $_REQUEST['paged'] ) : 1;
					}

					$offset        = $posts_per_page * ( (int) $paged - 1 );
					$args['paged'] = $paged;

					// Update posts_per_page
					if ( absint( $args['posts_per_page'] ) > $limit - $offset ) {
						$args['posts_per_page'] = $limit - $offset;
						$args['offset']         = $offset;
					}
				}

				$order_by = ( isset( $scMeta['order_by'][0] ) ? $scMeta['order_by'][0] : null );
				$order    = ( isset( $scMeta['order'][0] ) ? $scMeta['order'][0] : null );

				if ( $order ) {
					$args['order'] = $order;
				}

				if ( $order_by ) {
					$args['orderby'] = $order_by;
				}

				$taxQ        = [];
				$departments = ( isset( $scMeta['ttp_departments'] ) ? $scMeta['ttp_departments'] : [] );

				if ( ! empty( $departments ) && is_array( $departments ) ) {
					$taxQ[] = [
						'taxonomy' => rttlp_team()->taxonomies['department'],
						'field'    => 'term_id',
						'terms'    => $departments,
						'operator' => 'IN',
					];
				}

				$designation = ( isset( $scMeta['ttp_designations'] ) ? $scMeta['ttp_designations'] : [] );

				if ( ! empty( $designation ) && is_array( $designation ) ) {
					$taxQ[] = [
						'taxonomy' => rttlp_team()->taxonomies['designation'],
						'field'    => 'term_id',
						'terms'    => $designation,
						'operator' => 'IN',
					];
				}

				if ( count( $taxQ ) >= 2 ) {
					$relation         = ( isset( $scMeta['ttp_taxonomy_relation'][0] ) ? $scMeta['ttp_taxonomy_relation'][0] : 'AND' );
					$taxQ['relation'] = $relation;
				}

				if ( ! empty( $taxQ ) ) {
					$args['tax_query'] = $taxQ;
				}

				if ( $isCarousel ) {
					$cOpt                   = isset( $scMeta['carousel'][0] ) ? unserialize( $scMeta['carousel'][0] ) : [];
					$args['posts_per_page'] = isset( $cOpt['total_items'] ) ? ( $cOpt['total_items'] ? intval( $cOpt['total_items'] ) : 8 ) : 8;
				}

				// Advance Filter
				$action_taxonomy = ! empty( $_REQUEST['taxonomy'] ) ? sanitize_text_field( $_REQUEST['taxonomy'] ) : null;
				$action_term     = ! empty( $_REQUEST['term'] ) ? ( sanitize_text_field( $_REQUEST['term'] ) == 'all' ? 'all' : absint( $_REQUEST['term'] ) ) : 0;

				if ( $action_taxonomy && $action_term && $action_term != 'all' ) {
					$taxQ              = [
						[
							'taxonomy' => $action_taxonomy,
							'field'    => 'term_id',
							'terms'    => $action_term,
						],
					];
					$args['tax_query'] = $taxQ;
				}

				// override shortcode filter
				$action_order = ! empty( $_REQUEST['order'] ) ? sanitize_text_field( $_REQUEST['order'] ) : null;

				if ( $action_order ) {
					$args['order'] = $action_order;
				}

				$action_order_by = ! empty( $_REQUEST['order_by'] ) ? sanitize_text_field( $_REQUEST['order_by'] ) : null;

				if ( $action_order_by ) {
					$args['orderby'] = $action_order_by;
				}

				$this->order = ! empty( $args['order'] ) ? sanitize_text_field( $args['order'] ) : 'DESC';

				$sAction = ( ! empty( $_REQUEST['search'] ) ? sanitize_text_field( $_REQUEST['search'] ) : null );

				if ( $sAction ) {
					$this->s = [
						'taxonomy' => $action_taxonomy,
						'term'     => $action_term,
						's'        => $sAction,
					];

					$args['post_status'] = 'publish';

					add_filter( 'posts_where', [ $this, 'tlp_team_search_where' ] );
					// add_filter( 'posts_join', [ $this, 'tlp_team_search_join' ] );
					add_filter( 'posts_groupby', [ $this, 'tlp_team_search_groupby' ] );

					$args['meta_query'] = [
						'relation' => 'OR',
						[
							'key'     => 'location',
							'value'   => $sAction,
							'compare' => 'LIKE',
						],
						[
							'key'     => 'short_bio',
							'value'   => $sAction,
							'compare' => 'LIKE',
						],
					];
				}

				// Validation
				$dCol = $dCol == 5 ? '24' : round( 12 / $dCol );
				$tCol = $dCol == 5 ? '24' : round( 12 / $tCol );
				$mCol = $dCol == 5 ? '24' : round( 12 / $mCol );

				if ( $isCarousel ) {
					$dCol = $tCol = $mCol = 12;
				}

				$arg         = [];
				$arg['grid'] = "rt-col-md-{$dCol} rt-col-sm-{$tCol} rt-col-xs-{$mCol}";

				if ( ( $layout == 'layout2' ) || ( $layout == 'layout3' ) ) {
					$iCol                = ( isset( $scMeta['ttp_layout2_image_column'][0] ) ? absint( $scMeta['ttp_layout2_image_column'][0] ) : 4 );
					$iCol                = $iCol > 12 ? 4 : $iCol;
					$cCol                = 12 - $iCol;
					$arg['image_area']   = "rt-col-sm-{$iCol} rt-col-xs-12 ";
					$arg['content_area'] = "rt-col-sm-{$cCol} rt-col-xs-12 ";
				}

				$gridType     = ! empty( $scMeta['grid_style'][0] ) ? $scMeta['grid_style'][0] : 'even';
				$arg['class'] = null;

				if ( ! $isCarousel ) {
					$arg['class'] = $gridType . '-grid-item';
				}

				$arg['class'] .= ' rt-grid-item';

				if ( $isIsotope ) {
					$arg['class'] .= ' isotope-item';
				}

				if ( $isCarousel ) {
					$arg['class'] .= ' carousel-item';
				}

				$margin = ! empty( $scMeta['margin_option'][0] ) ? $scMeta['margin_option'][0] : 'default';
				if ( $margin == 'no' ) {
					$arg['class'] .= ' no-margin';
				}

				if ( ! empty( $scMeta['image_style'][0] ) && $scMeta['image_style'][0] == 'round' ) {
					$arg['class'] .= ' round-img';
				}

				$arg['anchorClass'] = null;
				$link               = ! empty( $scMeta['ttp_detail_page_link'][0] ) ? $scMeta['ttp_detail_page_link'][0] : null;

				if ( ! $link ) {
					$arg['link']        = false;
					$arg['anchorClass'] = ' disabled';
				} else {
					$arg['link'] = true;
				}

				$linkType = ! empty( $scMeta['ttp_detail_page_link_type'][0] ) ? $scMeta['ttp_detail_page_link_type'][0] : 'popup';

				if ( $link && $linkType == 'popup' ) {
					$popupType = ! empty( $scMeta['ttp_popup_type'][0] ) ? $scMeta['ttp_popup_type'][0] : 'single';

					if ( $popupType == 'single' ) {
						$arg['anchorClass'] .= ' ttp-single-md-popup';
					} elseif ( $popupType == 'multiple' ) {
						$arg['anchorClass'] .= ' ttp-multi-popup';
					} elseif ( $popupType == 'smart' ) {
						$arg['anchorClass'] .= ' ttp-smart-popup';
					}
				}

				$arg['target'] = null;

				if ( $link && $linkType == 'new_page' ) {
					$arg['target'] = ! empty( $scMeta['ttp_link_target'][0] ) ? $scMeta['ttp_link_target'][0] : '_self';
				}

				$arg['items'] = ! empty( $scMeta['ttp_selected_field'] ) ? $scMeta['ttp_selected_field'] : [];

				$isoFilterTaxonomy = ! empty( $scMeta['ttp_isotope_filter_taxonomy'] ) ? $scMeta['ttp_isotope_filter_taxonomy'] : null;

				$teamQuery = new \WP_Query( $args );

				if ( $sAction ) {
					remove_filter( 'posts_where', [ $this, 'tlp_team_search_where' ] );
					// remove_filter( 'posts_join', [ $this, 'tlp_team_search_join' ] );
					remove_filter( 'posts_groupby', [ $this, 'tlp_team_search_groupby' ] );
				}

				$error = false;

				// Start layout
				if ( $teamQuery->have_posts() ) {
					while ( $teamQuery->have_posts() ) {
						$teamQuery->the_post();
						/* Argument for single member */
						$mID                = get_the_ID();
						$arg['mID']         = $mID;
						$arg['title']       = get_the_title();
						$cLink              = get_post_meta( $mID, 'ttp_custom_detail_url', true );
						$arg['pLink']       = $cLink ? $cLink : get_permalink();
						$arg['designation'] = strip_tags(
							get_the_term_list(
								$mID,
								rttlp_team()->taxonomies['designation'],
								null,
								', '
							)
						);
						$arg['email']       = get_post_meta( $mID, 'email', true );
						$arg['web_url']     = get_post_meta( $mID, 'web_url', true );
						$arg['telephone']   = get_post_meta( $mID, 'telephone', true );
						$arg['fax']         = get_post_meta( $mID, 'fax', true );
						$arg['mobile']      = get_post_meta( $mID, 'mobile', true );
						$arg['location']    = get_post_meta( $mID, 'location', true );
						$short_bio          = get_post_meta( $mID, 'short_bio', true );
						$arg['short_bio']   = $character_limit ? substr(
							$short_bio,
							0,
							$character_limit
						) : $short_bio;
						$arg['sLink']       = get_post_meta( $mID, 'social', true );
						$arg['tlp_skill']   = unserialize( get_post_meta( $mID, 'skill', true ) );
						$arg['imgHtml']     = ! $fImg ? Fns::getFeatureImageHtml(
							$mID,
							$fImgSize,
							$defaultImgId,
							$customImgSize
						) : null;
						$arg['isoFilter']   = '';

						if ( $isIsotope && $isoFilterTaxonomy ) {
							$termAs    = wp_get_post_terms( $mID, $isoFilterTaxonomy, [ 'fields' => 'all' ] );
							$isoFilter = null;
							if ( ! empty( $termAs ) ) {
								foreach ( $termAs as $term ) {
									$isoFilter .= ' ' . 'iso_' . $term->term_id;
								}
							}
							$arg['isoFilter'] = $isoFilter;
						}

						$data .= Fns::render( 'layouts/' . $layout, $arg, true );
					}
				} else {
					$data = '<p>' . esc_html__( 'No more member to load', 'tlp-team' ) . '</p>';
					$msg  = esc_html__( 'No more member to load', 'tlp-team' );
				}

				$total_pares = $teamQuery->max_num_pages;
				wp_reset_postdata();

			} else {
				$msg = esc_html__( 'Shortcode id missing', 'tlp-team' );
			}
		} else {
			$msg = esc_html__( 'Session Error', 'tlp-team' );
		}

		wp_send_json(
			[
				'error'       => $error,
				'msg'         => $msg,
				'data'        => $data,
				'paged'       => $paged,
				'total_pages' => $total_pares,
				'l4toggle'    => ( $this->l4toggleLoadMore ? 1 : null ),
			]
		);

		die();
	}

	function tlp_team_search_where( $where ) {
		global $wpdb, $wp_query;
		$term = $wpdb->esc_like( $this->s['s'] );
		$where .= "OR ({$wpdb->posts}.post_title LIKE '%{$term}%'
			OR {$wpdb->posts}.post_content LIKE '%{$term}%'
			OR {$wpdb->posts}.post_excerpt LIKE '%{$term}%')";
		$where .= " AND {$wpdb->posts}.post_status = 'publish'";
		$where .= " AND {$wpdb->posts}.post_type = 'team'";

		return $where;
	}

	function tlp_team_search_join( $join ) {
		global $wpdb;
		$join .= "LEFT JOIN {$wpdb->term_relationships} tr ON {$wpdb->posts}.ID = tr.object_id INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id=tr.term_taxonomy_id INNER JOIN {$wpdb->terms} t ON t.term_id = tt.term_id";

		return $join;
	}

	function tlp_team_search_groupby( $groupby ) {
		global $wpdb;

		// we need to group on post ID
		$groupby_id = "{$wpdb->posts}.ID";
		if ( strpos( $groupby, $groupby_id ) !== false ) {
			return $groupby;
		}

		// groupby was empty, use ours
		if ( ! strlen( trim( $groupby ) ) ) {
			return $groupby_id;
		}

		// wasn't empty, append ours
		return $groupby . ', ' . $groupby_id;
	}
}
