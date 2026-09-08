/**
 * Homepage 2 only — animates each .hp2-stat__num[data-count] from 0 up to its real value once
 * scrolled into view. Static number (already 0 in the markup) if JS fails or IntersectionObserver
 * is unavailable — never depends on JS to show the real number, only to animate toward it.
 */
( function () {
	if ( typeof IntersectionObserver === 'undefined' ) {
		document.querySelectorAll( '.hp2-stat__num[data-count]' ).forEach( function ( el ) {
			el.textContent = el.getAttribute( 'data-count' );
		} );
		return;
	}

	var reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	document.addEventListener( 'DOMContentLoaded', function () {
		var nums = document.querySelectorAll( '.hp2-stat__num[data-count]' );
		if ( ! nums.length ) {
			return;
		}

		var animate = function ( el ) {
			var target = parseInt( el.getAttribute( 'data-count' ), 10 ) || 0;
			if ( reduceMotion ) {
				el.textContent = target;
				return;
			}
			var start = null;
			var duration = 1400;
			function step( ts ) {
				if ( start === null ) {
					start = ts;
				}
				var progress = Math.min( ( ts - start ) / duration, 1 );
				var eased = 1 - Math.pow( 1 - progress, 3 );
				el.textContent = Math.floor( eased * target );
				if ( progress < 1 ) {
					requestAnimationFrame( step );
				} else {
					el.textContent = target;
				}
			}
			requestAnimationFrame( step );
		};

		var observer = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						animate( entry.target );
						observer.unobserve( entry.target );
					}
				} );
			},
			{ threshold: 0.6 }
		);

		nums.forEach( function ( el ) {
			observer.observe( el );
		} );
	} );
} )();
