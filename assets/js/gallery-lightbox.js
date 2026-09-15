/**
 * Click-to-expand SLIDER for .bio-photo-gallery__gallery (build-pages.js's bioPhotoGallery()) --
 * same shared-modal/backdrop/close-button/Escape-key pattern already established by
 * testimonial-marquee.js's click-to-expand modal, upgraded (2026-09-15, direct request) from a
 * single static image into a real prev/next slider through every photo in that same gallery
 * section, with a counter and a crossfade transition between images.
 *
 * Each .bio-photo-gallery__gallery is its own independent slide set -- opening a photo in the
 * "Exterior" section only ever cycles through Exterior's own photos, never spilling into
 * "Interior" etc.
 *
 * Progressive enhancement: the grid already renders as plain images with no JS (patterns.css), so
 * a blocked/failed script just means no click-to-enlarge/slide -- nothing broken either way.
 */
( function () {
	document.addEventListener( 'DOMContentLoaded', function () {
		var galleries = document.querySelectorAll( '.bio-photo-gallery__gallery' );

		if ( ! galleries.length ) {
			return;
		}

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

		var currentSlides = [];
		var currentIndex = 0;

		function render() {
			var img = currentSlides[ currentIndex ];
			modal.classList.add( 'is-transitioning' );
			// Wait one frame so the opacity-0 state from adding this class actually paints before
			// swapping the src -- otherwise the browser coalesces both changes into a single
			// paint and the crossfade never visibly happens.
			window.requestAnimationFrame( function () {
				modalImage.src = img.currentSrc || img.src;
				modalImage.alt = img.alt || '';
				window.requestAnimationFrame( function () {
					modal.classList.remove( 'is-transitioning' );
				} );
			} );
			counter.textContent = ( currentIndex + 1 ) + ' / ' + currentSlides.length;
			var multiple = currentSlides.length > 1;
			prevButton.style.display = multiple ? '' : 'none';
			nextButton.style.display = multiple ? '' : 'none';
			counter.style.display = multiple ? '' : 'none';
		}

		function showPrev() {
			currentIndex = ( currentIndex - 1 + currentSlides.length ) % currentSlides.length;
			render();
		}

		function showNext() {
			currentIndex = ( currentIndex + 1 ) % currentSlides.length;
			render();
		}

		function openModal( slides, index ) {
			currentSlides = slides;
			currentIndex = index;
			lastFocused = document.activeElement;
			modal.classList.add( 'is-open' );
			document.body.classList.add( 'gallery-lightbox-open' );
			render();
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
		prevButton.addEventListener( 'click', showPrev );
		nextButton.addEventListener( 'click', showNext );
		document.addEventListener( 'keydown', function ( e ) {
			if ( ! modal.classList.contains( 'is-open' ) ) {
				return;
			}
			if ( e.key === 'Escape' ) {
				closeModal();
			} else if ( e.key === 'ArrowLeft' ) {
				showPrev();
			} else if ( e.key === 'ArrowRight' ) {
				showNext();
			}
		} );

		galleries.forEach( function ( gallery ) {
			var images = Array.prototype.slice.call( gallery.querySelectorAll( 'img' ) );
			images.forEach( function ( img, i ) {
				var figure = img.closest( 'figure' ) || img;
				figure.setAttribute( 'role', 'button' );
				figure.setAttribute( 'tabindex', '0' );
				figure.addEventListener( 'click', function () {
					openModal( images, i );
				} );
				figure.addEventListener( 'keydown', function ( e ) {
					if ( e.key === 'Enter' || e.key === ' ' || e.key === 'Spacebar' ) {
						e.preventDefault();
						openModal( images, i );
					}
				} );
			} );
		} );
	} );
} )();
