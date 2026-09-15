/**
 * Fanned 3D coverflow carousel + click-to-expand SLIDER for .bio-photo-gallery__gallery
 * (build-pages.js's bioPhotoGallery()) -- redesigned 2026-09-15 to match a real screenshot
 * reference (the Framify.design landing page): cards tilt in 3D perspective around whichever card
 * is currently "centered", fanning out and shrinking/receding further from center, clipped at the
 * container edge. Each .bio-photo-gallery__gallery gets its own independent center index -- moving
 * one category's fan never affects another's.
 *
 * Interaction: clicking an off-center card brings it to center (standard coverflow behavior);
 * clicking the already-centered card opens the full-size slider (same shared modal as before,
 * prev/next through every photo in that gallery, not just the ones currently fanned into view).
 * Prev/next buttons are added below each fan for the same centering action without a mouse click
 * directly on a card.
 *
 * Progressive enhancement: patterns.css lays every figure on top of the last with no JS (all
 * absolutely positioned, centered, stacked) -- a blocked/failed script means a single static photo
 * per category rather than a broken layout, and no click-to-enlarge/fan navigation either way.
 */
( function () {
	document.addEventListener( 'DOMContentLoaded', function () {
		var galleries = document.querySelectorAll( '.bio-photo-gallery__gallery' );

		if ( ! galleries.length ) {
			return;
		}

		// ---- shared lightbox/slider modal, one instance for the whole page ----
		var modal = document.createElement( 'div' );
		modal.className = 'gallery-lightbox';
		modal.innerHTML =
			'<div class="gallery-lightbox__backdrop"></div>' +
			'<div class="gallery-lightbox__dialog" role="dialog" aria-modal="true" aria-label="Photo">' +
			'<button type="button" class="gallery-lightbox__close" aria-label="Close">&times;</button>' +
			'<button type="button" class="gallery-lightbox__nav gallery-lightbox__nav--prev" aria-label="Previous photo">&#8249;</button>' +
			'<img class="gallery-lightbox__image" alt="" />' +
			'<button type="button" class="gallery-lightbox__nav gallery-lightbox__nav--next" aria-label="Next photo">&#8250;</button>' +
			'<div class="gallery-lightbox__counter"></div>' +
			'</div>';
		document.body.appendChild( modal );

		var modalImage = modal.querySelector( '.gallery-lightbox__image' );
		var closeButton = modal.querySelector( '.gallery-lightbox__close' );
		var prevButton = modal.querySelector( '.gallery-lightbox__nav--prev' );
		var nextButton = modal.querySelector( '.gallery-lightbox__nav--next' );
		var counter = modal.querySelector( '.gallery-lightbox__counter' );
		var backdrop = modal.querySelector( '.gallery-lightbox__backdrop' );
		var lastFocused = null;
		var modalSlides = [];
		var modalIndex = 0;

		function renderModal() {
			var img = modalSlides[ modalIndex ];
			modal.classList.add( 'is-transitioning' );
			window.requestAnimationFrame( function () {
				modalImage.src = img.currentSrc || img.src;
				modalImage.alt = img.alt || '';
				window.requestAnimationFrame( function () {
					modal.classList.remove( 'is-transitioning' );
				} );
			} );
			counter.textContent = ( modalIndex + 1 ) + ' / ' + modalSlides.length;
			var multiple = modalSlides.length > 1;
			prevButton.style.display = multiple ? '' : 'none';
			nextButton.style.display = multiple ? '' : 'none';
			counter.style.display = multiple ? '' : 'none';
		}

		function openModal( slides, index ) {
			modalSlides = slides;
			modalIndex = index;
			lastFocused = document.activeElement;
			modal.classList.add( 'is-open' );
			document.body.classList.add( 'gallery-lightbox-open' );
			renderModal();
			closeButton.focus();
		}

		function closeModal() {
			modal.classList.remove( 'is-open' );
			document.body.classList.remove( 'gallery-lightbox-open' );
			modalImage.src = '';
			if ( lastFocused && typeof lastFocused.focus === 'function' ) {
				lastFocused.focus();
			}
		}

		closeButton.addEventListener( 'click', closeModal );
		backdrop.addEventListener( 'click', closeModal );
		prevButton.addEventListener( 'click', function () {
			modalIndex = ( modalIndex - 1 + modalSlides.length ) % modalSlides.length;
			renderModal();
		} );
		nextButton.addEventListener( 'click', function () {
			modalIndex = ( modalIndex + 1 ) % modalSlides.length;
			renderModal();
		} );
		document.addEventListener( 'keydown', function ( e ) {
			if ( ! modal.classList.contains( 'is-open' ) ) {
				return;
			}
			if ( e.key === 'Escape' ) {
				closeModal();
			} else if ( e.key === 'ArrowLeft' ) {
				modalIndex = ( modalIndex - 1 + modalSlides.length ) % modalSlides.length;
				renderModal();
			} else if ( e.key === 'ArrowRight' ) {
				modalIndex = ( modalIndex + 1 ) % modalSlides.length;
				renderModal();
			}
		} );

		// ---- one fan carousel per gallery, each with its own center index ----
		galleries.forEach( function ( gallery ) {
			var images = Array.prototype.slice.call( gallery.querySelectorAll( 'img' ) );
			var figures = images.map( function ( img ) {
				return img.closest( 'figure' ) || img;
			} );
			if ( ! figures.length ) {
				return;
			}

			var center = Math.min( 2, Math.floor( figures.length / 2 ) );
			var VISIBLE_RANGE = 3; // cards more than this many steps from center fully fade/clip out

			function layoutFan() {
				figures.forEach( function ( fig, i ) {
					var offset = i - center;
					var abs = Math.abs( offset );
					if ( abs > VISIBLE_RANGE ) {
						fig.style.opacity = '0';
						fig.style.pointerEvents = 'none';
						fig.style.zIndex = '0';
						fig.style.transform = 'translateX(' + ( offset * 40 ) + 'px) translateZ(-500px) rotateY(' + ( offset < 0 ? 70 : -70 ) + 'deg)';
						return;
					}
					fig.style.pointerEvents = '';
					fig.style.opacity = String( 1 - abs * 0.18 );
					var translateX = offset * 130;
					var translateZ = -abs * 90;
					var rotateY = offset * -24;
					var scale = 1 - abs * 0.12;
					fig.style.transform = 'translateX(' + translateX + 'px) translateZ(' + translateZ + 'px) rotateY(' + rotateY + 'deg) scale(' + scale + ')';
					fig.style.zIndex = String( 100 - abs );
				} );
			}

			function goTo( index ) {
				center = ( index + figures.length ) % figures.length;
				layoutFan();
			}

			layoutFan();

			var navWrap = document.createElement( 'div' );
			navWrap.className = 'bio-photo-gallery__fan-nav-wrap';
			navWrap.innerHTML =
				'<button type="button" class="bio-photo-gallery__fan-nav" aria-label="Previous photo">&#8249;</button>' +
				'<button type="button" class="bio-photo-gallery__fan-nav" aria-label="Next photo">&#8250;</button>';
			gallery.parentNode.insertBefore( navWrap, gallery.nextSibling );
			var prevFanButton = navWrap.children[ 0 ];
			var nextFanButton = navWrap.children[ 1 ];
			prevFanButton.addEventListener( 'click', function () {
				goTo( center - 1 );
			} );
			nextFanButton.addEventListener( 'click', function () {
				goTo( center + 1 );
			} );

			figures.forEach( function ( fig, i ) {
				fig.setAttribute( 'role', 'button' );
				fig.setAttribute( 'tabindex', '0' );
				fig.addEventListener( 'click', function () {
					if ( i !== center ) {
						goTo( i );
						return;
					}
					openModal( images, i );
				} );
				fig.addEventListener( 'keydown', function ( e ) {
					if ( e.key === 'Enter' || e.key === ' ' || e.key === 'Spacebar' ) {
						e.preventDefault();
						if ( i !== center ) {
							goTo( i );
						} else {
							openModal( images, i );
						}
					}
				} );
			} );
		} );
	} );
} )();
