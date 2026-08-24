/**
 * Scroll-reveal fade-up for every top-level page section (each direct child of main.page-content,
 * plus the newsletter-signup template part) — site-wide polish, not a per-pattern edit, so it
 * covers every existing and future page automatically.
 *
 * Progressive-enhancement order matters here: a section is only hidden (.is-pending) once this
 * script has actually run AND confirmed IntersectionObserver exists, right before observing it —
 * never hidden by default in CSS. That way a JS failure (blocked script, ad blocker, old browser)
 * just means sections show up already visible instead of staying invisible forever.
 */
( function () {
	if ( typeof IntersectionObserver === 'undefined' ) {
		return;
	}

	if ( window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
		return;
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		var targets = document.querySelectorAll(
			'main.page-content > *, .newsletter-section'
		);

		if ( ! targets.length ) {
			return;
		}

		var observer = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						entry.target.classList.remove( 'is-pending' );
						observer.unobserve( entry.target );
					}
				} );
			},
			{ threshold: 0.12, rootMargin: '0px 0px -60px 0px' }
		);

		targets.forEach( function ( el, i ) {
			el.classList.add( 'reveal' );
			// Skip the very first section (the hero) — it already gets its own on-load entrance
			// animation and is above the fold, so scroll-revealing it too would mean it starts
			// invisible on a fast-loading page before the observer has even registered it.
			if ( i === 0 ) {
				return;
			}
			el.classList.add( 'is-pending' );
			observer.observe( el );
		} );
	} );
} )();
