/**
 * Sticky header on scroll — site-wide (every page uses the same site-header.php, including the
 * homepage, on purpose: navigation stays identical wherever a visitor lands, see that file's own
 * header comment). Desktop-only in effect: .site-header is already position:fixed unconditionally
 * below the 900px breakpoint (patterns.css), so toggling this class there is a harmless no-op.
 *
 * Threshold is the pill's own resting offset (86px, patterns.css .site-header) rather than a
 * round number — that's the exact point the floating pill would start overlapping page content
 * if it didn't switch to fixed, so it flips right as it would otherwise start looking wrong.
 */
( function () {
	var header = document.querySelector( '.site-header' );
	if ( ! header ) {
		return;
	}

	var THRESHOLD = 86;
	var ticking = false;

	function update() {
		header.classList.toggle( 'is-scrolled', window.scrollY > THRESHOLD );
		ticking = false;
	}

	window.addEventListener(
		'scroll',
		function () {
			if ( ! ticking ) {
				window.requestAnimationFrame( update );
				ticking = true;
			}
		},
		{ passive: true }
	);

	update();
} )();
