/**
 * Initializes the Leaflet/OpenStreetMap embed inside .route-map__canvas (patterns/route-map.php).
 * Real departure + landmark pins only — no fabricated route line, since we don't have the actual
 * vessel GPS track (2026-08-21 decision). Reads coordinates from the container's own data
 * attributes so each page can supply a different real route later (Long Island Lighthouse,
 * Connecticut Cruises) without touching this file.
 */
( function () {
	function navyPin() {
		return L.divIcon( {
			className: 'route-map__pin route-map__pin--departure',
			html: '<span></span>',
			iconSize: [ 22, 22 ],
			iconAnchor: [ 11, 11 ],
		} );
	}

	function goldPin() {
		return L.divIcon( {
			className: 'route-map__pin route-map__pin--landmark',
			html: '<span></span>',
			iconSize: [ 18, 18 ],
			iconAnchor: [ 9, 9 ],
		} );
	}

	function initMap( el ) {
		var departure = JSON.parse( el.getAttribute( 'data-departure' ) || 'null' );
		var landmarks = JSON.parse( el.getAttribute( 'data-landmarks' ) || '[]' );
		var routePath = JSON.parse( el.getAttribute( 'data-route-path' ) || '[]' );

		if ( ! departure ) {
			return;
		}

		var map = L.map( el, {
			scrollWheelZoom: false,
			attributionControl: true,
		} );

		L.tileLayer( 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 18,
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
		} ).addTo( map );

		var bounds = [ [ departure.lat, departure.lng ] ];

		// Real navigable-waterway route line (East River -> Upper NY Bay) — traced along open
		// water the whole way, not a straight-line fabrication. Drawn first so the pins sit on
		// top of it. A thin white halo underneath the green line matches the brand's existing
		// gold-on-navy contrast approach and keeps the line legible over busy OSM tile detail.
		if ( routePath.length > 1 ) {
			var latlngs = routePath.map( function ( pt ) {
				return [ pt.lat, pt.lng ];
			} );
			L.polyline( latlngs, {
				color: '#ffffff',
				weight: 9,
				opacity: 0.9,
				lineCap: 'round',
				lineJoin: 'round',
			} ).addTo( map );
			L.polyline( latlngs, {
				color: '#1b5e20',
				weight: 5,
				opacity: 0.95,
				lineCap: 'round',
				lineJoin: 'round',
			} ).addTo( map );
			latlngs.forEach( function ( ll ) {
				bounds.push( ll );
			} );
		}

		L.marker( [ departure.lat, departure.lng ], { icon: navyPin() } )
			.addTo( map )
			.bindPopup( '<strong>' + departure.label + '</strong>' );

		landmarks.forEach( function ( pt ) {
			bounds.push( [ pt.lat, pt.lng ] );
			L.marker( [ pt.lat, pt.lng ], { icon: goldPin() } )
				.addTo( map )
				.bindPopup( pt.label );
		} );

		map.fitBounds( bounds, { padding: [ 48, 48 ] } );

		// Don't trap page-scroll on an embedded map — only zoom on scroll once a visitor has
		// deliberately clicked into it, release again when they click away.
		map.on( 'click', function () {
			map.scrollWheelZoom.enable();
		} );
		el.addEventListener( 'mouseleave', function () {
			map.scrollWheelZoom.disable();
		} );
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		var canvases = document.querySelectorAll( '.route-map__canvas' );
		if ( ! canvases.length || typeof L === 'undefined' ) {
			return;
		}
		canvases.forEach( initMap );
	} );
} )();
