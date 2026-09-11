/**
 * Homepage 2 only (page-templates/homepage-2.php) — three independent, progressively-enhanced
 * behaviors. Every one degrades to fully-visible/functional-but-static content if JS fails or
 * IntersectionObserver is unavailable: nothing here is required to read or use the page, only to
 * animate it. Supersedes the old stat-count.js (merged in below) now that this page needs more
 * than one small script.
 */
( function () {
	var reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	/* ---------- Hero: headline line-reveal + background parallax ---------- */
	function initHero() {
		var hero = document.querySelector( '.hp2-hero' );
		if ( ! hero ) {
			return;
		}

		// Reveal is pure CSS (transform on .is-loaded), JS just flips the class once the page is
		// ready to paint it — if this never runs, the headline's own base state has no opacity:0,
		// only a translateY the browser clips inside overflow:hidden, so worst case it's static
		// but still fully visible, never invisible.
		requestAnimationFrame( function () {
			hero.classList.add( 'is-loaded' );
		} );

		if ( reduceMotion ) {
			return;
		}

		var bg = hero.querySelector( '[data-parallax]' );
		if ( ! bg ) {
			return;
		}

		var ticking = false;
		function updateParallax() {
			var rect = hero.getBoundingClientRect();
			// Only move the layer while the hero is actually on screen — no work, no jank, once
			// scrolled past.
			if ( rect.bottom > 0 && rect.top < window.innerHeight ) {
				var offset = rect.top * 0.28;
				bg.style.transform = 'translateY(' + offset + 'px)';
			}
			ticking = false;
		}
		window.addEventListener(
			'scroll',
			function () {
				if ( ! ticking ) {
					requestAnimationFrame( updateParallax );
					ticking = true;
				}
			},
			{ passive: true }
		);
		updateParallax();
	}

	/* ---------- Stat strip: count up from 0 once scrolled into view ---------- */
	function initStats() {
		var nums = document.querySelectorAll( '.hp2-stat__num[data-count]' );
		if ( ! nums.length ) {
			return;
		}

		if ( typeof IntersectionObserver === 'undefined' ) {
			nums.forEach( function ( el ) {
				el.textContent = el.getAttribute( 'data-count' );
			} );
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
	}

	/* ---------- Why Skyline: pinned scroll stack ----------
	 * .hp2-why-stack is given an explicit height (panel count * 100vh) so its sticky child has
	 * real scroll distance to pin across. On scroll, work out how far through that distance the
	 * user is, map it to a panel index, and toggle .is-active/.is-prev accordingly — the "next
	 * panel overlaps the current one in place" effect the design calls for. Skipped entirely
	 * (falls back to the plain stacked-rows CSS in homepage-2.css) under reduced-motion or when
	 * the viewport is short/narrow enough that pinning would feel cramped rather than elegant —
	 * same breakpoint as the CSS fallback (1100px) so JS and CSS never disagree about which mode
	 * is active.
	 */
	function initWhyStack() {
		var stack = document.querySelector( '[data-pin-stack]' );
		if ( ! stack ) {
			return;
		}

		var panels = Array.prototype.slice.call( stack.querySelectorAll( '.hp2-why-panel' ) );
		var dots = Array.prototype.slice.call( stack.querySelectorAll( '[data-dot]' ) );
		if ( ! panels.length ) {
			return;
		}

		function unwind() {
			stack.classList.add( 'no-pin-stack' );
			stack.style.height = '';
			panels.forEach( function ( p ) {
				p.classList.remove( 'is-active', 'is-prev' );
			} );
			if ( panels[ 0 ] ) {
				panels[ 0 ].classList.add( 'is-active' );
			}
		}

		if ( reduceMotion || window.innerWidth <= 1100 ) {
			unwind();
			return;
		}

		stack.style.height = ( panels.length * 100 ) + 'vh';

		var current = 0;
		var ticking = false;

		function setActive( index ) {
			if ( index === current ) {
				return;
			}
			panels.forEach( function ( panel, i ) {
				panel.classList.remove( 'is-active', 'is-prev' );
				if ( i === index ) {
					panel.classList.add( 'is-active' );
				} else if ( i < index ) {
					panel.classList.add( 'is-prev' );
				}
			} );
			dots.forEach( function ( dot, i ) {
				dot.classList.toggle( 'is-active', i === index );
			} );
			current = index;
		}

		function update() {
			var rect = stack.getBoundingClientRect();
			var scrollable = rect.height - window.innerHeight;
			if ( scrollable <= 0 ) {
				ticking = false;
				return;
			}
			var progress = ( -rect.top ) / scrollable;
			progress = Math.max( 0, Math.min( 1, progress ) );
			var index = Math.min( panels.length - 1, Math.floor( progress * panels.length ) );
			setActive( index );
			ticking = false;
		}

		window.addEventListener(
			'scroll',
			function () {
				if ( ! ticking ) {
					requestAnimationFrame( update );
					ticking = true;
				}
			},
			{ passive: true }
		);
		update();

		// If the viewport is resized across the 1100px line (e.g. rotating a tablet), re-evaluate
		// rather than staying stuck in whichever mode the page happened to load in.
		var resizeTimer;
		window.addEventListener( 'resize', function () {
			clearTimeout( resizeTimer );
			resizeTimer = setTimeout( function () {
				if ( window.innerWidth <= 1100 && ! stack.classList.contains( 'no-pin-stack' ) ) {
					unwind();
				}
			}, 200 );
		} );
	}

	/* ---------- FAQ: animate the native <details> open/close instead of the instant snap ----------
	 * Every other section on this page eases in; the FAQ's native disclosure toggling in a single
	 * frame was the one spot that still read as an un-modernized default control. Web Animations
	 * API (Element.animate), the standard accessible pattern for this (no library) — height is
	 * animated between measured start/end values, the real `open` attribute is only flipped once
	 * the matching animation finishes, so keyboard/screen-reader semantics stay exactly native
	 * throughout. Skipped entirely under reduced motion: native instant toggle, already accessible
	 * and already fine on its own.
	 */
	function initFaqAccordion() {
		if ( reduceMotion ) {
			return;
		}

		var items = document.querySelectorAll( '.hp2-faq-item' );

		items.forEach( function ( item ) {
			var summary = item.querySelector( 'summary' );
			var answer = item.querySelector( 'p' );
			if ( ! summary || ! answer ) {
				return;
			}

			var animation = null;
			var busy = false;

			summary.addEventListener( 'click', function ( e ) {
				e.preventDefault();
				if ( busy ) {
					return;
				}
				item.open ? closeItem() : openItem();
			} );

			function closedHeight() {
				return summary.getBoundingClientRect().height;
			}
			function openHeight() {
				return summary.getBoundingClientRect().height + answer.getBoundingClientRect().height;
			}

			function runAnimation( from, to, onFinish ) {
				busy = true;
				item.style.overflow = 'hidden';
				if ( animation ) {
					animation.cancel();
				}
				animation = item.animate(
					{ height: [ from + 'px', to + 'px' ] },
					{ duration: 320, easing: 'cubic-bezier(0.16, 0.84, 0.44, 1)' }
				);
				animation.onfinish = function () {
					item.style.height = '';
					item.style.overflow = '';
					animation = null;
					busy = false;
					onFinish();
				};
			}

			function openItem() {
				item.open = true;
				var to = openHeight();
				runAnimation( closedHeight(), to, function () {} );
			}

			function closeItem() {
				var from = openHeight();
				runAnimation( from, closedHeight(), function () {
					item.open = false;
				} );
			}
		} );
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		initHero();
		initStats();
		initWhyStack();
		initFaqAccordion();
	} );
} )();
