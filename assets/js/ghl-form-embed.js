/**
 * EFFVIT canonical GHL form embed — vanilla-JS port of the org's React contract
 * (components/GhlForm.tsx in the Next.js landing-page repos; see also the
 * effvit-ghl-form-embed skill). Three rules, each paid for with a real outage
 * elsewhere in the fleet:
 *
 * 1. PARAMS BEFORE MOUNT. The iframe's src is fully resolved (gclid/utm merged
 *    in) before the iframe is ever added to the DOM. Mutating src after mount
 *    reloads the widget and blanks the form.
 *
 * 2. form_embed.js IS MANDATORY. It is GHL's own parent-page bridge: it posts
 *    the real page URL + referrer + merged query params to the widget. Without
 *    it every lead lands with null attribution (Biltmore, 2026-07-16 to 07-23).
 *    Injected once per page, after every placeholder on the page has mounted.
 *
 * 3. NEVER LOAD A SECOND IFRAME-RESIZER. form_embed.js bundles its own; a
 *    standalone iframe-resizer elsewhere throws and can blank the form entirely.
 *    Confirmed no other iframe-resizer script exists anywhere in this theme
 *    before adding this file.
 *
 * Usage: drop a placeholder anywhere in page content —
 *   <div class="ghl-form-embed" data-ghl-form-id="FORM_ID" data-ghl-form-name="Label" style="min-height:620px"></div>
 * — this script finds every one on the page, mounts a real GHL iframe into it,
 * then injects form_embed.js exactly once.
 */
( function () {
	var UTM_KEYS = [ 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content' ];
	var CLICK_ID_KEYS = [ 'gclid', 'gbraid', 'wbraid', 'fbclid' ];
	var FORM_EMBED_SRC = 'https://link.msgsndr.com/js/form_embed.js';
	var DEFAULT_HOST = 'api.leadconnectorhq.com';
	var embedInjected = false;

	function injectFormEmbedOnce() {
		if ( embedInjected ) { return; }
		embedInjected = true;
		if ( document.querySelector( 'script[src="' + FORM_EMBED_SRC + '"]' ) ) { return; }
		var s = document.createElement( 'script' );
		s.src = FORM_EMBED_SRC;
		s.async = true;
		document.body.appendChild( s );
	}

	// sessionStorage fallback keeps attribution alive across internal navigation,
	// where the landing params are no longer present in the URL.
	function resolveParams() {
		var urlParams = new URLSearchParams( window.location.search );
		var out = new URLSearchParams();
		CLICK_ID_KEYS.concat( UTM_KEYS ).forEach( function ( key ) {
			var val = urlParams.get( key );
			if ( ! val ) {
				try { val = sessionStorage.getItem( key ); } catch ( err ) { val = null; }
			}
			if ( val ) {
				try { sessionStorage.setItem( key, val ); } catch ( err ) { /* private mode */ }
				out.set( key, val );
			}
		} );
		// utm_*/gbraid/wbraid slugs match their params exactly; the two click ids
		// whose GHL field keys differ get aliased to every spelling in use.
		var gclid = out.get( 'gclid' );
		if ( gclid ) { out.set( 'gclid-of', gclid ); out.set( 'gclidof', gclid ); }
		var fbclid = out.get( 'fbclid' );
		if ( fbclid ) { out.set( 'fbclid-of', fbclid ); out.set( 'fbclidof', fbclid ); }
		return out;
	}

	function mountForm( el ) {
		var formId = el.getAttribute( 'data-ghl-form-id' );
		if ( ! formId ) { return; }
		var formName = el.getAttribute( 'data-ghl-form-name' ) || formId;
		var host = el.getAttribute( 'data-ghl-host' ) || DEFAULT_HOST;
		var height = el.getAttribute( 'data-ghl-height' ) || '620';

		var params = resolveParams();
		var qs = params.toString();
		var base = 'https://' + host + '/widget/form/' + formId;
		var src = qs ? base + '?' + qs : base;
		var iframeId = 'inline-' + formId;

		var iframe = document.createElement( 'iframe' );
		iframe.src = src;
		iframe.style.width = '100%';
		iframe.style.height = height + 'px';
		iframe.style.border = 'none';
		iframe.style.display = 'block';
		iframe.id = iframeId;
		iframe.setAttribute( 'data-layout', '{"id":"INLINE"}' );
		iframe.setAttribute( 'data-trigger-type', 'alwaysShow' );
		iframe.setAttribute( 'data-trigger-value', '' );
		iframe.setAttribute( 'data-activation-type', 'alwaysActivated' );
		iframe.setAttribute( 'data-activation-value', '' );
		iframe.setAttribute( 'data-deactivation-type', 'neverDeactivate' );
		iframe.setAttribute( 'data-deactivation-value', '' );
		iframe.setAttribute( 'data-form-name', formName );
		iframe.setAttribute( 'data-height', height );
		iframe.setAttribute( 'data-layout-iframe-id', iframeId );
		iframe.setAttribute( 'data-form-id', formId );
		iframe.title = formName;

		el.appendChild( iframe );
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		var placeholders = document.querySelectorAll( '.ghl-form-embed[data-ghl-form-id]' );
		if ( ! placeholders.length ) { return; }
		placeholders.forEach( mountForm );
		injectFormEmbedOnce();
	} );
} )();
