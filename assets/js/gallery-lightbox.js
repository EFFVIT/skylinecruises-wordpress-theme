/**
 * Click-to-expand lightbox for .bio-photo-gallery__gallery (build-pages.js's bioPhotoGallery()) --
 * same shared-modal/backdrop/close-button/Escape-key pattern already established by
 * testimonial-marquee.js's click-to-expand modal, just showing a full image instead of quote text.
 * Generic across every page that uses this gallery composer, not a one-off for a single page.
 *
 * Progressive enhancement: the grid already renders as plain images with no JS (patterns.css), so
 * a blocked/failed script just means no click-to-enlarge -- nothing broken either way.
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
			'<img class="gallery-lightbox__image" alt="" />' +
			'</div>';
		document.body.appendChild( modal );

		var modalImage = modal.querySelector( '.gallery-lightbox__image' );
		var closeButton = modal.querySelector( '.gallery-lightbox__close' );
		var backdrop = modal.querySelector( '.gallery-lightbox__backdrop' );
		var lastFocused = null;

		function openModal( img ) {
			modalImage.src = img.currentSrc || img.src;
			modalImage.alt = img.alt || '';
			lastFocused = document.activeElement;
			modal.classList.add( 'is-open' );
			document.body.classList.add( 'gallery-lightbox-open' );
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
		document.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' && modal.classList.contains( 'is-open' ) ) {
				closeModal();
			}
		} );

		galleries.forEach( function ( gallery ) {
			var images = gallery.querySelectorAll( 'img' );
			images.forEach( function ( img ) {
				var figure = img.closest( 'figure' ) || img;
				figure.setAttribute( 'role', 'button' );
				figure.setAttribute( 'tabindex', '0' );
				figure.addEventListener( 'click', function () {
					openModal( img );
				} );
				figure.addEventListener( 'keydown', function ( e ) {
					if ( e.key === 'Enter' || e.key === ' ' || e.key === 'Spacebar' ) {
						e.preventDefault();
						openModal( img );
					}
				} );
			} );
		} );
	} );
} )();
