import apiFetch from '@wordpress/api-fetch';
import domReady from '@wordpress/dom-ready';
import { delegate, extractClassValue } from '../../utility';

/**
 * Called when one of the portfolio category filter buttons is clicked.
 *
 * @param {event}   event
 * @this  {Element}
 */
function filterButtonClick( event ) {
	event.preventDefault();

	let item = this.parentElement;

	if ( item ) {
		let cat = extractClassValue( item, 'cat-item-' );

		if ( cat && cat.length > 0 ) {
			let wrap = item.closest( '.wpzoom-blocks_portfolio-block' ).querySelector( '.wpzoom-blocks_portfolio-block_items-list' ),
			    show = 'all' == cat ? wrap.querySelectorAll( '[data-category]' ) : wrap.querySelectorAll( '[data-category="' + cat + '"]' ),
			    hide = 'all' == cat ? [] : wrap.querySelectorAll( '[data-category]:not([data-category="' + cat + '"])' );

			item.parentNode.querySelectorAll( 'li' ).forEach( filterBtn => {
				filterBtn.classList.remove( 'current-cat' );
			} );
			item.classList.add( 'current-cat' );

			show.forEach( theItem => {
				let classList = theItem.classList;

				if ( classList.contains( 'fade-out' ) ) {
					classList.remove( 'fade-out' );
				}

				if ( ! classList.contains( 'fade-in' ) ) {
					classList.add( 'fade-in' );
				}
			} );

			hide.forEach( theItem => {
				let classList = theItem.classList;

				if ( classList.contains( 'fade-in' ) ) {
					classList.remove( 'fade-in' );
				}

				if ( ! classList.contains( 'fade-out' ) ) {
					classList.add( 'fade-out' );
				}
			} );
		}
	}
}

function portfolioMasonry( event ) {

	let container = document.getElementsByClassName('wpzoom-blocks_portfolio-block');

	[].forEach.call(container, function(el) {

		if( el.classList.contains( 'layout-masonry' ) ) {

			var elem = el.querySelector('.wpzoom-blocks_portfolio-block_items-list');
			var msnry = new Masonry( elem, {
				// options
				itemSelector: '.wpzoom-blocks_portfolio-block_item',
				//columnWidth: 200
			});

			// element
			imagesLoaded( el ).on( 'progress', function() {
				msnry.layout();
			});

			// layout Masonry after each image loads
			// elem.imagesLoaded().progress( function() {
			// 	$grid.masonry('layout');
			// });
		}
	});

}

/**
 * Called when one of the portfolio items is clicked.
 *
 * @param {event}   event
 * @this  {Element}
 */
function portfolioItemClick( event ) {
	let item = this.closest( '.wpzoom-blocks_portfolio-block_item' );

	if ( item.querySelector( '.wpzoom-blocks_portfolio-block_item-bgvid, .wpzoom-blocks_portfolio-block_item-thumbnail' ) ) {
		event.preventDefault();

		item.classList.add( 'lightbox' );

		if ( item.classList.contains( 'fade-in' ) ) {
			item.classList.remove( 'fade-in' );
		}

		if ( item.classList.contains( 'fade-out' ) ) {
			item.classList.remove( 'fade-out' );
		}
	}
}

/**
 * Called when the close button in a lightbox is clicked.
 *
 * @param {event}   event
 * @this  {Element}
 */
function portfolioItemLightboxClose( event ) {
	let item = this.closest( '.wpzoom-blocks_portfolio-block_item' );

	if ( item.classList.contains( 'lightbox' ) && event.target.matches( '.wpzoom-blocks_portfolio-block_item-bgvid, .wpzoom-blocks_portfolio-block_item-thumbnail' ) ) {
		event.preventDefault();

		item.classList.remove( 'lightbox' );
	}
}

/**
 * Called when the show more portfolio items button is clicked.
 *
 * @param {event}   event
 * @this  {Element}
 */
function portfolioShowMoreClick( event ) {
	event.preventDefault();

	let container = this.closest( '.wpzoom-blocks_portfolio-block' ),
		moreBtn = container.querySelector( '.wpzoom-blocks_portfolio-block_show-more' ),
	    itemsContainer = container.querySelector( '.wpzoom-blocks_portfolio-block_items-list' ),
	    page = parseInt( extractClassValue( container, 'page-' ) ) || 2,
	    params = new URLSearchParams( {
	        layout:         extractClassValue( container, 'layout-' ),
	        order:          extractClassValue( container, 'order-' ),
	        order_by:       extractClassValue( container, 'orderby-' ),
	        per_page:       parseInt( extractClassValue( container, 'perpage-' ) ) || 6,
			cats:           extractClassValue( container, 'category-' ),
	        page:           page,
	        show_thumbnail: container.classList.contains( 'show-thumbnail' ),
	        thumbnail_size: extractClassValue( container, 'thumbnail-size-' ),
	        show_video:     container.classList.contains( 'show-video' ),
	        show_author:    container.classList.contains( 'show-author' ),
	        show_date:      container.classList.contains( 'show-date' ),
	        show_excerpt:   container.classList.contains( 'show-excerpt' ),
	        show_read_more: container.classList.contains( 'show-readmore' ),
			source: container.classList.contains( 'post_type-post' ) ? 'post' : 'portfolio_item'
	    } ),
	    fetchRequest = apiFetch( { path: '/wpzoom-blocks/v1/portfolio-posts?' + params.toString() } );

		moreBtn.children[0].textContent = 'Loading...';

	fetchRequest.then( response => {
		if ( response ) {
			let items = 'items' in response ? response.items : [],
			    hasMore = 'has_more' in response ? response.has_more : true;

			if ( items ) {
				itemsContainer.insertAdjacentHTML( 'beforeend', items );
				moreBtn.children[0].textContent = 'Load More...';
				let filterTrigger = container.querySelector( '.wpzoom-blocks_portfolio-block_filter .current-cat a' );
				if( !container.classList.contains( 'layout-masonry' ) && typeof(filterTrigger) != 'undefined' && filterTrigger != null ) {
					filterTrigger.click();
				}
				portfolioMasonry();

				if ( container.classList.contains( 'page-' + page ) ) {
					container.classList.replace( 'page-' + page, 'page-' + ( page + 1 ) );
				} else {
					container.classList.add( 'page-' + ( page + 1 ) );
				}

				if ( ! hasMore ) {
					moreBtn.style.display = 'none';
					moreBtn.parentElement.classList.add( 'single-button' );
				}
			}
		}
	} );
}

domReady( () => {
	delegate(
		'click',
		'.wpzoom-blocks_portfolio-block .wpzoom-blocks_portfolio-block_filter a',
		filterButtonClick
	);

	delegate(
		'click',
		'.wpzoom-blocks_portfolio-block.use-lightbox .wpzoom-blocks_portfolio-block_lightbox_icon',
		portfolioItemClick
	);

	delegate(
		'click',
		`.wpzoom-blocks_portfolio-block.use-lightbox .wpzoom-blocks_portfolio-block_item-bgvid,
		.wpzoom-blocks_portfolio-block.use-lightbox .wpzoom-blocks_portfolio-block_item-thumbnail`,
		portfolioItemLightboxClose
	);

	delegate(
		'click',
		'.wpzoom-blocks_portfolio-block .wpzoom-blocks_portfolio-block_show-more a',
		portfolioShowMoreClick
	);

	delegate(
		'load',
		document,
		portfolioMasonry()
	);

	document.onkeydown = function( evt ) {
		evt = evt || window.event;
		var isEscape = false;
		if ( "key" in evt ) {
			isEscape = ( evt.key === "Escape" || evt.key === "Esc" );
		} else {
			isEscape = (evt.keyCode === 27);
		}
		if ( isEscape ) {
			let elems = document.getElementsByClassName('wpzoom-blocks_portfolio-block_item lightbox');
			[].forEach.call(elems, function(el) {
				el.classList.remove('lightbox');
			});
		}
	};

} );

// document.addEventListener('DOMContentLoaded', function(event) {
// 	portfolioMasonry();
// });

// wrapped into IIFE - to leave global space clean.
( function( window, wp ){

	//console.log( wp.hooks );
    // just to keep it cleaner - we refer to our link by id for speed of lookup on DOM.

    // check if gutenberg's editor root element is present.
    var editorEl = document.getElementById( 'editor' );
    if( !editorEl ){ // do nothing if there's no gutenberg root element on page.
        return;
    }

	//console.log( wp.data );

    var unsubscribe = wp.data.subscribe( function () {

        setTimeout( function () {

			var container = document.getElementsByClassName('wpzoom-blocks_portfolio-block');

			[].forEach.call(container, function(el) {

				if( el.classList.contains( 'layout-masonry' ) ) {
		
					var elem = el.querySelector('.wpzoom-blocks_portfolio-block_items-list');
					var msnry = new Masonry( elem, {
						// options
						itemSelector: '.wpzoom-blocks_portfolio-block_item',
						//columnWidth: 200
					});
		
					// element
					imagesLoaded( el ).on( 'progress', function() {
						msnry.layout();
					});
		
					// layout Masonry after each image loads
					// elem.imagesLoaded().progress( function() {
					// 	$grid.masonry('layout');
					// });
				}
			});

			
        }, 1 )
    });
})( window, wp );