/**
 * Testimonial marquee (About/Info "Clients & Testimonials" page) — turns the static
 * .testimonial-quote__grid card grid into a continuous right-to-left scrolling strip.
 *
 * Progressive enhancement: the grid renders as a normal static wrap with no JS (see
 * patterns.css's .testimonial-quote__grid rules), so a blocked/failed script just means the
 * section looks like an ordinary card list instead of breaking.
 *
 * Cards are moved into a new .testimonial-quote__track flex row, then the whole set is
 * duplicated once so a `translateX(-50%)` loop is seamless (no jump/reset visible). Under
 * prefers-reduced-motion the duplicate + animation are both skipped entirely, matching the
 * same check scroll-reveal.js already uses site-wide.
 */
( function () {
	document.addEventListener( 'DOMContentLoaded', function () {
		var grids = document.querySelectorAll( '.testimonial-quote__grid' );

		if ( ! grids.length ) {
			return;
		}

		var reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

		grids.forEach( function ( grid ) {
			var cards = Array.prototype.slice.call( grid.children );

			if ( ! cards.length ) {
				return;
			}

			var track = document.createElement( 'div' );
			track.className = 'testimonial-quote__track';
			cards.forEach( function ( card ) {
				track.appendChild( card );
			} );
			grid.appendChild( track );

			if ( reduceMotion ) {
				track.classList.add( 'testimonial-quote__track--static' );
				return;
			}

			// Duplicate the real set once — translateX(-50%) then lands exactly on the start
			// of the clone, so the loop never visibly resets.
			cards.forEach( function ( card ) {
				track.appendChild( card.cloneNode( true ) );
			} );

			// Fixed px/sec speed rather than a fixed duration, so the strip scrolls at the same
			// visual speed regardless of how many testimonials a given page has.
			var SPEED_PX_PER_SEC = 45;
			var setWidth = track.scrollWidth / 2;
			var duration = setWidth / SPEED_PX_PER_SEC;

			track.style.setProperty( '--marquee-duration', duration + 's' );
			track.classList.add( 'testimonial-quote__track--animated' );
		} );
	} );
} )();
