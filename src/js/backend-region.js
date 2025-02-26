/* jshint esversion: 6 */
/* jshint browser: true, devel: true */
/* global jQuery, ajaxurl, wp, L, mapStyle, GeoSearch, console */

(function () {

	// Restore the extended L object (CBNLeaflet.L) to the global scope (prevents conflicts with other Leaflet instances)
	"use strict";
	window.L = window.CBNLeaflet.L;

	let map = L.map(
		'mapGetRegion',
		{
			scrollWheelZoom: false,
			zoomSnap: 0.5,
			zoomDelta: 0.5,
		}
	);

	// prevent moving/zoom outside main world bounds
	let world_bounds   = L.latLngBounds( L.latLng( -85, -200 ), L.latLng( 85, 200 ) );
	let world_min_zoom = map.getBoundsZoom( world_bounds ), cbn_geosearch_selected_provider;
	map.setMaxBounds( world_bounds );
	map.setMinZoom( Math.ceil( world_min_zoom ) );
	map.on(
		'drag',
		function () {
			map.panInsideBounds( world_bounds, {animate: false} );
		}
	);

	// Set map style
	if (mapStyle === 'Custom1') {

		L.tileLayer( 'https://{s}.basemaps.cartocdn.com/light_nolabels/{z}/{x}/{y}.png' ).addTo( map );
		L.tileLayer(
			'https://{s}.basemaps.cartocdn.com/rastertiles/voyager_only_labels/{z}/{x}/{y}{r}.png',
			{
				tileSize: 512,
				zoomOffset: -1
			}
		).addTo( map );

	} else if (mapStyle === 'Custom2') {

		L.tileLayer( 'https://{s}.basemaps.cartocdn.com/dark_nolabels/{z}/{x}/{y}.png' ).addTo( map );
		L.tileLayer(
			'https://{s}.basemaps.cartocdn.com/rastertiles/voyager_only_labels/{z}/{x}/{y}{r}.png',
			{
				tileSize: 512,
				zoomOffset: -1
			}
		).addTo( map );

	} else if (mapStyle === 'Custom3') {

		L.tileLayer( 'https://{s}.basemaps.cartocdn.com/dark_nolabels/{z}/{x}/{y}.png' ).addTo( map );
		L.tileLayer(
			'https://{s}.basemaps.cartocdn.com/rastertiles/voyager_only_labels/{z}/{x}/{y}{r}.png',
			{
				tileSize: 512,
				zoomOffset: -1
			}
		).addTo( map );

	} else if (mapStyle === 'MapBox.streets') {

		L.tileLayer.provider(
			'MapBox',
			{
				id: 'mapbox/streets-v12',
				accessToken: cbn_tile_provider_mapbox_key
			}
		).addTo( map );

	} else if (mapStyle === 'MapBox.outdoors') {

		L.tileLayer.provider(
			'MapBox',
			{
				id: 'mapbox/outdoors-v12',
				accessToken: cbn_tile_provider_mapbox_key
			}
		).addTo( map );

	} else if (mapStyle === 'MapBox.light') {

		L.tileLayer.provider(
			'MapBox',
			{
				id: 'mapbox/light-v11',
				accessToken: cbn_tile_provider_mapbox_key
			}
		).addTo( map );

	} else if (mapStyle === 'MapBox.dark') {

		L.tileLayer.provider(
			'MapBox',
			{
				id: 'mapbox/dark-v11',
				accessToken: cbn_tile_provider_mapbox_key
			}
		).addTo( map );

	} else if (mapStyle === 'MapBox.satellite') {

		L.tileLayer.provider(
			'MapBox',
			{
				id: 'mapbox/satellite-v9',
				accessToken: cbn_tile_provider_mapbox_key
			}
		).addTo( map );

	} else if (mapStyle === 'MapBox.satellite-streets') {

		L.tileLayer.provider(
			'MapBox',
			{
				id: 'mapbox/satellite-streets-v12',
				accessToken: cbn_tile_provider_mapbox_key
			}
		).addTo( map );

	} else {
		// Default
		L.tileLayer.provider( mapStyle ).addTo( map );
	}

	// Geosearch Provider
	switch (cbn_geosearch_provider) {
		case 'osm':
			cbn_geosearch_selected_provider = new GeoSearch.OpenStreetMapProvider();
			break;
		case 'geoapify':
			cbn_geosearch_selected_provider = new GeoSearch.GeoapifyProvider(
				{
					params: {
						apiKey: cbn_geosearch_provider_geoapify_key
					}
				}
			);
			break;
		case 'here':
			cbn_geosearch_selected_provider = new GeoSearch.HereProvider(
				{
					params: {
						apiKey: cbn_geosearch_provider_here_key
					}
				}
			);
			break;
		case 'mapbox':
			cbn_geosearch_selected_provider = new GeoSearch.MapBoxProvider(
				{
					params: {
						access_token: cbn_geosearch_provider_mapbox_key
					}
				}
			);
			break;
		default:
			cbn_geosearch_selected_provider = new GeoSearch.OpenStreetMapProvider();
			break;
	}

	let search = new GeoSearch.GeoSearchControl(
		{
			style: 'bar',
			showMarker: false,
			provider: cbn_geosearch_selected_provider,
			searchLabel: cbn_searchaddress_label
		}
	);
	map.addControl( search );

	map.setView( [lat, lng], zoom );

	// set Initial view by move/zoom
	map.on(
		'move',
		function () {
			setInitialLatLngZoom( map.getCenter(), map.getZoom() );
		}
	);

	//set lat & lng & zoom input fields
	function setInitialLatLngZoom(mapCenterLatLng, mapZoom) {
		jQuery( '#cbn_lat' ).val( mapCenterLatLng.lat );
		jQuery( '#cbn_lng' ).val( mapCenterLatLng.lng );
		jQuery( '#cbn_zoom' ).val( mapZoom );
	}

})();
