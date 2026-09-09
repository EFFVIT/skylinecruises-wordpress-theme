/**
 * Sticky header on scroll — site-wide (every page uses the same site-header.php, including the
 * homepage, on purpose: navigation stays identical wherever a visitor lands, see that file's own
 * header comment). Desktop-only in effect: .site-header is already position:fixed unconditionally
 * below the 900px breakpoint (patterns.css), so toggling this class there is a harmless no-op.
 *
 * THRESHOLD is 70, not the pill's 86px resting offset — see patterns.css's own comment on
 * .site-header.is-scrolled for the seamless-handoff math (86 - 70 = 16, matching that rule's
 * `top: 16px`). Switching at 86 itself left an 16px pop/jump at the handoff instant; 70 is the
 * value that makes the absolute->fixed switch land the header in the exact same screen position
 * it was already in, so it reads as one continuous scroll instead of a snap.
 */
( function () {
	var header = document.querySelector( '.site-header' );
	if ( ! header ) {
		return;
	}

	var THRESHOLD = 70;
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
