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

			// Real bug found 2026-08-24: clicking a focusable descendant (a 'tabs'-style dropdown's
			// tab button) leaves it holding keyboard focus, and :focus-within in patterns.css keeps
			// this LI's dropdown open on that basis alone — so moving the mouse away to hover a
			// DIFFERENT nav item left the old dropdown stuck open underneath/behind the new one,
			// both visible at once. Clearing focus on mouseleave (only when focus is actually inside
			// this LI) fixes it without touching real keyboard-only navigation at all: a keyboard
			// user tabbing through items never fires mouseleave in the first place.
			li.addEventListener( 'mouseleave', function () {
				if ( li.contains( document.activeElement ) ) {
					document.activeElement.blur();
				}
			} );
		} );

		// 'tabs' style dropdowns (Cruises): click a tab in the left-hand list to switch which
		// content panel shows on the right — mirrors Biltmore's own Surgical/Non-Surgical tabs.
		document.querySelectorAll( '.nav-dropdown--tabs' ).forEach( function ( dropdown ) {
			var tabs = dropdown.querySelectorAll( '.nav-tabs__tab' );
			var panels = dropdown.querySelectorAll( '.nav-tabs__panel' );

			tabs.forEach( function ( tab ) {
				tab.addEventListener( 'click', function () {
					var index = tab.getAttribute( 'data-tab-index' );

					tabs.forEach( function ( t ) {
						var active = t === tab;
						t.classList.toggle( 'is-active', active );
						t.setAttribute( 'aria-selected', active ? 'true' : 'false' );
					} );

					panels.forEach( function ( panel ) {
						panel.classList.toggle( 'is-active', panel.getAttribute( 'data-tab-panel' ) === index );
					} );
				} );
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
