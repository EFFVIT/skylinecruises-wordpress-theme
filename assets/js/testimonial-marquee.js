/**
 * Testimonial marquee (About/Info "Clients & Testimonials" page) — turns the static
 * .testimonial-quote__grid card grid into a continuous right-to-left scrolling strip, plus a
 * click-to-expand modal so the 7-line card clamp (patterns.css) never actually loses content.
 *
 * Progressive enhancement: the grid renders as a normal static wrap with no JS (see
 * patterns.css's .testimonial-quote__grid rules), so a blocked/failed script just means the
 * section looks like an ordinary card list instead of breaking — no modal, no clamp-escape, but
 * nothing broken either.
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
		var tracks = [];

		// One shared modal for every card on the page rather than one per card — built once,
		// populated from whichever card was activated. Quote/attribution text is read straight
		// from the card's own paragraphs, so it's always the real, un-clamped content already in
		// the page source (nothing invented, nothing re-fetched).
		var modal = document.createElement( 'div' );
		modal.className = 'testimonial-modal';
		modal.innerHTML =
			'<div class="testimonial-modal__backdrop"></div>' +
			'<div class="testimonial-modal__dialog" role="dialog" aria-modal="true" aria-label="Guest testimonial">' +
			'<button type="button" class="testimonial-modal__close" aria-label="Close">&times;</button>' +
			'<p class="testimonial-modal__quote"></p>' +
			'<p class="testimonial-modal__attribution"></p>' +
			'</div>';
		document.body.appendChild( modal );

		var modalQuote = modal.querySelector( '.testimonial-modal__quote' );
		var modalAttribution = modal.querySelector( '.testimonial-modal__attribution' );
		var closeButton = modal.querySelector( '.testimonial-modal__close' );
		var backdrop = modal.querySelector( '.testimonial-modal__backdrop' );
		var lastFocused = null;

		function openModal( card ) {
			var paragraphs = card.querySelectorAll( 'p' );
			modalQuote.textContent = paragraphs[ 0 ] ? paragraphs[ 0 ].textContent : '';
			modalAttribution.textContent = paragraphs[ 1 ] ? paragraphs[ 1 ].textContent : '';

			lastFocused = document.activeElement;
			modal.classList.add( 'is-open' );
			document.body.classList.add( 'testimonial-modal-open' );
			// Explicit pause independent of :hover/:focus-within — once the modal is open the
			// pointer/focus is very likely to leave the card (into the dialog itself), and the
			// strip shouldn't keep scrolling behind it while it's open.
			tracks.forEach( function ( t ) {
				t.style.animationPlayState = 'paused';
			} );
			closeButton.focus();
		}

		function closeModal() {
			modal.classList.remove( 'is-open' );
			document.body.classList.remove( 'testimonial-modal-open' );
			tracks.forEach( function ( t ) {
				t.style.animationPlayState = '';
			} );
			if ( lastFocused && typeof lastFocused.focus === 'function' ) {
				lastFocused.focus();
			}
		}

		closeButton.addEventListener( 'click', closeModal );
		backdrop.addEventListener( 'click', closeModal );
		document.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' && modal.classList.contains( 'is-open' ) ) {
				closeModal();
			}
		} );

		function makeCardInteractive( card ) {
			card.setAttribute( 'role', 'button' );
			card.setAttribute( 'tabindex', '0' );
			card.addEventListener( 'click', function () {
				openModal( card );
			} );
			card.addEventListener( 'keydown', function ( e ) {
				if ( e.key === 'Enter' || e.key === ' ' || e.key === 'Spacebar' ) {
					e.preventDefault();
					openModal( card );
				}
			} );
		}

		grids.forEach( function ( grid ) {
			var cards = Array.prototype.slice.call( grid.children );

			if ( ! cards.length ) {
				return;
			}

			var track = document.createElement( 'div' );
			track.className = 'testimonial-quote__track';
			cards.forEach( function ( card ) {
				track.appendChild( card );
				makeCardInteractive( card );
			} );
			grid.appendChild( track );
			tracks.push( track );

			if ( reduceMotion ) {
				track.classList.add( 'testimonial-quote__track--static' );
				return;
			}

			// Duplicate the real set once — translateX(-50%) then lands exactly on the start
			// of the clone, so the loop never visibly resets. Clones need their own listeners;
			// they're separate DOM nodes from the originals moved above.
			cards.forEach( function ( card ) {
				var clone = card.cloneNode( true );
				makeCardInteractive( clone );
				track.appendChild( clone );
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
