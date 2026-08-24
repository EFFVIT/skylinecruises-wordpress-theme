/**
 * Nav dropdown interaction (template-parts/site-header.php + .nav-dropdown in patterns.css).
 * The dropdowns already open on plain CSS :hover/:focus-within with zero JS — this file only
 * adds what CSS can't: tap-to-toggle for touch/no-hover devices, closing an open panel on
 * Escape or an outside click, and keeping aria-expanded in sync for screen readers.
 */
( function () {
	function closeAll( except ) {
		document.querySelectorAll( '.nav-pill__item--has-dropdown.is-open' ).forEach( function ( li ) {
			if ( li === except ) {
				return;
			}
			li.classList.remove( 'is-open' );
			var trigger = li.querySelector( '.nav-pill__trigger' );
			if ( trigger ) {
				trigger.setAttribute( 'aria-expanded', 'false' );
			}
		} );
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		var items = document.querySelectorAll( '.nav-pill__item--has-dropdown' );

		items.forEach( function ( li ) {
			var trigger = li.querySelector( '.nav-pill__trigger' );
			if ( ! trigger ) {
				return;
			}

			trigger.addEventListener( 'click', function ( e ) {
				// Only intercept on devices without real hover (touch) — desktop hover already
				// opens the panel, and the trigger's href should still navigate to the hub page
				// on a real click there.
				var hasHover = window.matchMedia( '(hover: hover)' ).matches;
				if ( ! hasHover ) {
					var isOpen = li.classList.contains( 'is-open' );
					if ( ! isOpen ) {
						e.preventDefault();
						closeAll( li );
						li.classList.add( 'is-open' );
						trigger.setAttribute( 'aria-expanded', 'true' );
					}
					// Second tap on an already-open item falls through and navigates normally.
				}
			} );
		} );

		document.addEventListener( 'click', function ( e ) {
			if ( ! e.target.closest( '.nav-pill__item--has-dropdown' ) ) {
				closeAll();
			}
		} );

		document.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' ) {
				closeAll();
			}
		} );
	} );
} )();
