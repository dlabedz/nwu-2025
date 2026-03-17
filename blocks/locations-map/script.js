( function () {
	'use strict';

	function initMapScrollFix() {
		var wrappers = document.querySelectorAll( '.locations-map__embed-wrapper' );
		if ( ! wrappers.length ) return;

		wrappers.forEach( function ( wrapper ) {
			var overlay = wrapper.querySelector( '.locations-map__scroll-overlay' );
			if ( ! overlay ) return;

			// Tap overlay → activate map, hide overlay
			overlay.addEventListener( 'click', function () {
				wrapper.classList.add( 'is-active' );
			} );

			// Touch anywhere outside → reset lock
			document.addEventListener( 'touchstart', function ( e ) {
				if ( ! wrapper.contains( e.target ) ) {
					wrapper.classList.remove( 'is-active' );
				}
			}, { passive: true } );
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initMapScrollFix );
	} else {
		initMapScrollFix();
	}

} )();
