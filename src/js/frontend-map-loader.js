/* jshint esversion: 6 */
/* jshint browser: true, devel: true */
/* global jQuery, ajaxurl, wp, console */

/**
 * Map Loading State Management
 */

// Listen for map initialization complete
document.addEventListener(
	'cbn:map_initialized',
	(event) => {
		"use strict";
		const {mapId} = event.detail;
		// Show map controls once initialization is complete
		showMapControls( mapId );
	}
);

/**
 * Show map controls and hide loading overlay
 */
const showMapControls = (mapId) => {
	"use strict";
	if ( ! mapId) {
		return;
	}

	const mapWrap = document.getElementById( mapId ).closest( '.map-wrap' );
	if ( ! mapWrap) {
		return;
	}

	const loadingOverlay     = mapWrap.querySelector( '.cbn-loading-overlay' );
	const filterControls     = mapWrap.querySelector( '.cbn-filter-controls' );
	const addLocationBtn     = mapWrap.querySelector( '.open-add-location-overlay' );
	const filterMarkersInput = mapWrap.querySelector( '#cbn_filter_markers' );

	// Hide loading overlay
	if (loadingOverlay) {
		loadingOverlay.classList.add( 'hidden' );
	}

	// Show controls with a slight delay for smooth transition
	setTimeout(
		() => {
			// Remove the cbn-hidden class and add visible class for filter controls
			if (filterControls) {
				filterControls.classList.remove( 'cbn-hidden' );
				filterControls.classList.add( 'visible' );
			}

			// Remove the cbn-hidden class and add visible class for add location button
			if (addLocationBtn) {
				addLocationBtn.classList.remove( 'cbn-hidden' );
				addLocationBtn.classList.add( 'visible' );
			}

			// Handle filter markers input visibility
			if (filterMarkersInput) {
				filterMarkersInput.classList.remove( 'cbn-hidden' );
				filterMarkersInput.classList.add( 'visible' );
			}
		},
		300
	);
};

/**
 * Map Loading State Handler
 */
const CBNLoader = (function () {
	"use strict";
	let loadingStates = {};

	function initLoader(mapId) {
		loadingStates[mapId] = {
			initialized: false
		};
	}

	function setMapInitialized(mapId) {
		if (loadingStates[mapId]) {
			loadingStates[mapId].initialized = true;
			showMapControls( mapId );
			delete loadingStates[mapId]; // Cleanup
		}
	}

	// Public API
	return {
		initLoader,
		setMapInitialized
	};
})(); 