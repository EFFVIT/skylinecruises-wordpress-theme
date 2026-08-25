/**
 * Mobile nav (template-parts/site-header.php's .mobile-header-bar / .mobile-drawer).
 * Only relevant below the mobile breakpoint (patterns.css hides this markup entirely above it),
 * but this script itself doesn't need to care — it just wires up elements that only exist/are
 * visible in that state.
 */
( function () {
	document.addEventListener( 'DOMContentLoaded', function () {
		var toggle = document.querySelector( '.mobile-menu-toggle' );
		var drawer = document.getElementById( 'mobile-drawer' );
		if ( ! toggle || ! drawer ) {
			return;
		}

		function closeDrawer() {
			document.body.classList.remove( 'mobile-nav-open' );
			toggle.setAttribute( 'aria-expanded', 'false' );
		}

		function openDrawer() {
			document.body.classList.add( 'mobile-nav-open' );
			toggle.setAttribute( 'aria-expanded', 'true' );
		}

		toggle.addEventListener( 'click', function () {
			var isOpen = document.body.classList.contains( 'mobile-nav-open' );
			if ( isOpen ) {
				closeDrawer();
			} else {
				openDrawer();
			}
		} );

		document.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' ) {
				closeDrawer();
			}
		} );

		// Each top-level accordion item (Cruises/Special Events/Weddings/The Ship) toggles
		// independently — not an exclusive accordion, matching the real Biltmore reference
		// (Learn/About stayed expandable alongside Treatments in the screenshot check).
		drawer.querySelectorAll( '.mobile-drawer__trigger' ).forEach( function ( trigger ) {
			trigger.addEventListener( 'click', function () {
				var item = trigger.closest( '.mobile-drawer__item--accordion' );
				var isOpen = item.classList.contains( 'is-open' );
				item.classList.toggle( 'is-open', ! isOpen );
				trigger.setAttribute( 'aria-expanded', ! isOpen ? 'true' : 'false' );
			} );
		} );

		// A real link inside the drawer (not an accordion trigger) should close the drawer before
		// the browser navigates, so a same-page anchor or the back button doesn't leave it open.
		drawer.querySelectorAll( 'a' ).forEach( function ( link ) {
			link.addEventListener( 'click', closeDrawer );
		} );
	} );
} )();
