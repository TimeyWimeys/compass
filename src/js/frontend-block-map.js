/**
 * @typedef {Object} Location
 * @property {string} title - Location title
 * @property {number} lat - Latitude
 * @property {number} lng - Longitude
 * @property {string} content - Location content/description
 * @property {string} icon - URL to marker icon
 * @property {string} post_id - WordPress post ID
 * @property {string[]} types - Array of location types/categories
 */

/**
 * @typedef {Object} MapBounds
 * @property {number} lat - Center latitude
 * @property {number} lng - Center longitude
 * @property {number} zoom - Zoom level
 */

// Add shared bounds variable at the top level
let sharedMapBounds = null;

/**
 * Utility Module - Contains helper functions used across other modules
 */
let OUMUtils = (function () {
  function getParameterByName(name) {
    name = name.replace(/\[/, "\\[").replace(/\]/, "\\]");
    let regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null
      ? ""
      : decodeURIComponent(results[1].replace(/\+/g, " "));
  }

  function latLngToBounds(lat, lng, zoom, width, height) {
    // Convert all inputs to numbers first, handling string inputs
    lat =
      typeof lat === "string"
        ? parseFloat(lat.replace(/['"]+/g, ""))
        : parseFloat(lat);
    lng =
      typeof lng === "string"
        ? parseFloat(lng.replace(/['"]+/g, ""))
        : parseFloat(lng);
    zoom =
      typeof zoom === "string"
        ? parseFloat(zoom.replace(/['"]+/g, ""))
        : parseFloat(zoom);
    width =
      typeof width === "string"
        ? parseFloat(width.replace(/['"]+/g, ""))
        : parseFloat(width);
    height =
      typeof height === "string"
        ? parseFloat(height.replace(/['"]+/g, ""))
        : parseFloat(height);

    // Add zoom offset to match settings map view
    zoom = zoom + 0.7;

    // Validate coordinates
    if (!validateCoordinates(lat, lng)) {
      console.warn("Invalid coordinates for latLngToBounds, using defaults");
      return [
        [OUMConfig.defaults.map.lat, OUMConfig.defaults.map.lng],
        [OUMConfig.defaults.map.lat, OUMConfig.defaults.map.lng],
      ];
    }

    // Validate dimensions
    if (isNaN(width) || width <= 0 || isNaN(height) || height <= 0) {
      console.warn("Invalid dimensions for latLngToBounds");
      width = 570; // Default width
      height = 372; // Default height
    }

    // Validate and adjust zoom
    if (isNaN(zoom)) {
      zoom = OUMConfig.defaults.map.zoom;
    } else {
      // Ensure zoom is between 2 and 20
      zoom = Math.max(2, Math.min(20, zoom));
    }

    // Calculate the visible area based on zoom level and Mercator projection
    let EARTH_RADIUS = 6378137; // Earth's radius in meters
    let scale = Math.pow(2, zoom);

    // Convert pixel dimensions to meters at this zoom level
    let metersPerPixel = (2 * Math.PI * EARTH_RADIUS) / (256 * scale);
    let widthMeters = width * metersPerPixel;
    let heightMeters = height * metersPerPixel;

    // Calculate the latitude bounds, accounting for Mercator projection
    let latRad = (lat * Math.PI) / 180;
    let latDelta = heightMeters / 2 / EARTH_RADIUS;
    let latitudeNorth = ((latRad + latDelta) * 180) / Math.PI;
    let latitudeSouth = ((latRad - latDelta) * 180) / Math.PI;

    // Calculate the longitude bounds (simpler as it's linear in Mercator)
    let lngDelta = widthMeters / 2 / (EARTH_RADIUS * Math.cos(latRad));
    let longitudeEast = lng + (lngDelta * 180) / Math.PI;
    let longitudeWest = lng - (lngDelta * 180) / Math.PI;

    return [
      [latitudeSouth, longitudeWest],
      [latitudeNorth, longitudeEast],
    ];
  }

  function customAutoSuggestText(text, val) {
    return (
      '<div><img src="' +
      val.layer.options.icon.options.iconUrl +
      '"  alt=""/>' +
      val.layer.options.title +
      "</div>"
    );
  }

  function initGeosearchProvider() {
    let provider;
    switch (cbn_geosearch_provider) {
      case "osm":
        provider = new GeoSearch.OpenStreetMapProvider();
        break;
      case "geoapify":
        provider = new GeoSearch.GeoapifyProvider({
          params: {
            apiKey: cbn_geosearch_provider_geoapify_key,
          },
        });
        break;
      case "here":
        provider = new GeoSearch.HereProvider({
          params: {
            apiKey: cbn_geosearch_provider_here_key,
          },
        });
        break;
      case "mapbox":
        provider = new GeoSearch.MapBoxProvider({
          params: {
            access_token: cbn_geosearch_provider_mapbox_key,
          },
        });
        break;
      default:
        provider = new GeoSearch.OpenStreetMapProvider();
        break;
    }
    return provider;
  }

  /**
   * Validates and sanitizes coordinates
   * @param {number|string} lat
   * @param {number|string} lng
   * @returns {boolean}
   */
  function validateCoordinates(lat, lng) {
    let parsedLat = parseFloat(lat);
    let parsedLng = parseFloat(lng);
    return (
      !isNaN(parsedLat) &&
      !isNaN(parsedLng) &&
      parsedLat >= -90 &&
      parsedLat <= 90 &&
      parsedLng >= -180 &&
      parsedLng <= 180
    );
  }

  /**
   * Safely parses a JSON string
   * @param {string} str
   * @param {*} fallback
   * @returns {*}
   */
  function safeJSONParse(str, fallback = null) {
    try {
      return JSON.parse(str);
    } catch (e) {
      return fallback;
    }
  }

  /**
   * Debounces a function
   * @param {Function} func
   * @param {number} wait
   * @returns {Function}
   */
  function debounce(func, wait = 250) {
    let timeout;
    return function executedFunction(...args) {
      let later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }

  // Public interface
  return {
    getParameterByName,
    latLngToBounds,
    customAutoSuggestText,
    initGeosearchProvider,
    validateCoordinates,
    safeJSONParse,
    debounce,
  };
})();

/**
 * Error Handler Module - Centralizes error management
 */
let OUMErrorHandler = (function () {
  function showError(message, type = "error") {
    console.error(`OUM Error: ${message}`);

    // Show error in UI if error container exists
    let errorContainer = document.getElementById("cbn_add_location_error");
    if (errorContainer) {
      errorContainer.innerHTML = `${message}<br>`;
      errorContainer.style.display = "block";
    }
  }

  function handleAjaxError(error) {
    showError(`Ajax request failed: ${error.message}`);
  }

  function validateCoordinates(lat, lng) {
    let parsedLat = parseFloat(lat);
    let parsedLng = parseFloat(lng);

    if (isNaN(parsedLat) || isNaN(parsedLng)) {
      showError("Invalid coordinates provided");
      return false;
    }

    if (
      parsedLat < -90 ||
      parsedLat > 90 ||
      parsedLng < -180 ||
      parsedLng > 180
    ) {
      showError("Coordinates out of valid range");
      return false;
    }

    return true;
  }

  return {
    showError,
    handleAjaxError,
    validateCoordinates,
  };
})();

/**
 * Configuration Module - Centralizes all configuration settings
 */
let OUMConfig = (function () {
  // Private variables
  let defaults = {
    map: {
      lat: 28,
      lng: 0,
      zoom: 1,
      bounds: L.latLngBounds(
        L.latLng(-85, -200), // Southwest corner (adjusted to prevent grey areas)
        L.latLng(85, 200)    // Northeast corner (adjusted to prevent grey areas)
      ),
    },
    media: {
      maxFiles: 5,
      validImageExtensions: ["jpeg", "jpg", "png", "webp"],
      maxImageSize: (cbn_max_image_filesize || 10) * 1048576, // Convert MB to bytes
    },
    search: {
      zoomLevel: cbn_searchmarkers_zoom || 8,
      addressLabel: cbn_searchaddress_label || "Search for address",
      markersLabel: cbn_searchmarkers_label || "Find marker",
    },
  };

  function getMapStyle() {
    return window.mapStyle || "Esri.WorldStreetMap";
  }

  function getTileProviderKey() {
    return window.cbn_tile_provider_mapbox_key || "";
  }

  function getGeosearchProvider() {
    let provider;
    switch (cbn_geosearch_provider) {
      case "geoapify":
        provider = new GeoSearch.GeoapifyProvider({
          params: {
            apiKey: cbn_geosearch_provider_geoapify_key,
          },
        });
        break;
      case "here":
        provider = new GeoSearch.HereProvider({
          params: {
            apiKey: cbn_geosearch_provider_here_key,
          },
        });
        break;
      case "mapbox":
        provider = new GeoSearch.MapBoxProvider({
          params: {
            access_token: cbn_geosearch_provider_mapbox_key,
          },
        });
        break;
      default:
        provider = new GeoSearch.OpenStreetMapProvider();
    }
    return provider;
  }

  return {
    defaults,
    getMapStyle,
    getTileProviderKey,
    getGeosearchProvider,
  };
})();

/**
 * Map Core Module - Handles the main map initialization and configuration
 */
let OUMMap = (function () {
  // Private variables
  let map = null;
  let world_bounds = null;
  let startPosition = {
    lat:
      typeof start_lat !== "undefined"
        ? Number(start_lat)
        : OUMConfig.defaults.map.lat,
    lng:
      typeof start_lng !== "undefined"
        ? Number(start_lng)
        : OUMConfig.defaults.map.lng,
    zoom:
      typeof start_zoom !== "undefined"
        ? Number(start_zoom)
        : OUMConfig.defaults.map.zoom,
  };

  // Private functions
  function initializeStartPosition() {
    // Validate coordinates
    if (!OUMUtils.validateCoordinates(startPosition.lat, startPosition.lng)) {
      console.warn("Invalid coordinates, using defaults");
      startPosition.lat = OUMConfig.defaults.map.lat;
      startPosition.lng = OUMConfig.defaults.map.lng;
    }

    // Validate zoom level (between 1 and 20)
    if (
      isNaN(startPosition.zoom) ||
      startPosition.zoom < 1 ||
      startPosition.zoom > 20
    ) {
      console.warn("Invalid zoom level, using default");
      startPosition.zoom = OUMConfig.defaults.map.zoom;
    }
  }

  function setupMapBounds() {
    // Set bounds if fixed map bounds is enabled
    if (cbn_enable_fixed_map_bounds) {
      // Calculate bounds based on initial position
      let boundsArray = OUMUtils.latLngToBounds(
        startPosition.lat,
        startPosition.lng,
        startPosition.zoom,
        570, // Width of settings map
        372 // Height of settings map
      );

      // Convert the bounds array to a Leaflet LatLngBounds object
      world_bounds = L.latLngBounds(
        L.latLng(boundsArray[0][0], boundsArray[0][1]),
        L.latLng(boundsArray[1][0], boundsArray[1][1])
      );

      // Store bounds globally for form map to use
      sharedMapBounds = world_bounds;
    } else {
      // Use default world bounds when fixed map bounds is disabled
      world_bounds = OUMConfig.defaults.map.bounds;
      sharedMapBounds = world_bounds;
    }

    // Set the minimum zoom level to prevent zooming out too far
    let maxVisibleBounds = map.getBoundsZoom(world_bounds);
    map.setMinZoom(maxVisibleBounds);

    // Set max bounds without padding
    map.setMaxBounds(world_bounds);

    let isAdjusting = false;

    // Handle map movement without recursion
    map.on("moveend", function () {
      if (isAdjusting) return;
      isAdjusting = true;

      let zoom = map.getZoom();

      // Only enforce bounds if we're zoomed in beyond the minimum zoom
      if (zoom > maxVisibleBounds) {
        let currentBounds = map.getBounds();
        let currentCenter = map.getCenter();

        let needsAdjustment = false;
        let newLat = currentCenter.lat;
        let newLng = currentCenter.lng;

        // Calculate current viewport dimensions
        let viewportHeight =
          currentBounds.getNorth() - currentBounds.getSouth();
        let viewportWidth =
          currentBounds.getEast() - currentBounds.getWest();

        // Check and adjust latitude (north/south)
        if (currentBounds.getNorth() > world_bounds.getNorth()) {
          newLat = world_bounds.getNorth() - viewportHeight / 2;
          needsAdjustment = true;
        } else if (currentBounds.getSouth() < world_bounds.getSouth()) {
          newLat = world_bounds.getSouth() + viewportHeight / 2;
          needsAdjustment = true;
        }

        // Check and adjust longitude (east/west)
        if (currentBounds.getEast() > world_bounds.getEast()) {
          newLng = world_bounds.getEast() - viewportWidth / 2;
          needsAdjustment = true;
        } else if (currentBounds.getWest() < world_bounds.getWest()) {
          newLng = world_bounds.getWest() + viewportWidth / 2;
          needsAdjustment = true;
        }

        if (needsAdjustment) {
          map.setView([newLat, newLng], zoom, { animate: false });
        }
      }

      isAdjusting = false;
    });
  }

  function setupTileLayer(mapStyle) {
    let tileLayer;

    if (mapStyle === "Custom1") {
      tileLayer = L.tileLayer(
        "https://{s}.basemaps.cartocdn.com/light_nolabels/{z}/{x}/{y}.png"
      ).addTo(map);
      L.tileLayer(
        "https://{s}.basemaps.cartocdn.com/rastertiles/voyager_only_labels/{z}/{x}/{y}{r}.png",
        {
          tileSize: 512,
          zoomOffset: -1,
        }
      ).addTo(map);
    } else if (mapStyle === "Custom2") {
      tileLayer = L.tileLayer(
        "https://{s}.basemaps.cartocdn.com/dark_nolabels/{z}/{x}/{y}.png"
      ).addTo(map);
      L.tileLayer(
        "https://{s}.basemaps.cartocdn.com/rastertiles/voyager_only_labels/{z}/{x}/{y}{r}.png",
        {
          tileSize: 512,
          zoomOffset: -1,
        }
      ).addTo(map);
    } else if (mapStyle === "Custom3") {
      tileLayer = L.tileLayer(
        "https://{s}.basemaps.cartocdn.com/dark_nolabels/{z}/{x}/{y}.png"
      ).addTo(map);
      L.tileLayer(
        "https://{s}.basemaps.cartocdn.com/rastertiles/voyager_only_labels/{z}/{x}/{y}{r}.png",
        {
          tileSize: 512,
          zoomOffset: -1,
        }
      ).addTo(map);
    } else if (mapStyle === "MapBox.streets") {
      tileLayer = L.tileLayer
        .provider("MapBox", {
          id: "mapbox/streets-v12",
          accessToken: OUMConfig.getTileProviderKey(),
        })
        .addTo(map);
    } else if (mapStyle === "MapBox.outdoors") {
      tileLayer = L.tileLayer
        .provider("MapBox", {
          id: "mapbox/outdoors-v12",
          accessToken: OUMConfig.getTileProviderKey(),
        })
        .addTo(map);
    } else if (mapStyle === "MapBox.light") {
      tileLayer = L.tileLayer
        .provider("MapBox", {
          id: "mapbox/light-v11",
          accessToken: OUMConfig.getTileProviderKey(),
        })
        .addTo(map);
    } else if (mapStyle === "MapBox.dark") {
      tileLayer = L.tileLayer
        .provider("MapBox", {
          id: "mapbox/dark-v11",
          accessToken: OUMConfig.getTileProviderKey(),
        })
        .addTo(map);
    } else if (mapStyle === "MapBox.satellite") {
      tileLayer = L.tileLayer
        .provider("MapBox", {
          id: "mapbox/satellite-v9",
          accessToken: OUMConfig.getTileProviderKey(),
        })
        .addTo(map);
    } else if (mapStyle === "MapBox.satellite-streets") {
      tileLayer = L.tileLayer
        .provider("MapBox", {
          id: "mapbox/satellite-streets-v12",
          accessToken: OUMConfig.getTileProviderKey(),
        })
        .addTo(map);
    } else {
      // Default
      tileLayer = L.tileLayer.provider(mapStyle).addTo(map);
    }

    return tileLayer;
  }

  function setupControls() {
    // Search markers control as button
    if (cbn_enable_searchmarkers_button) {
      L.control
        .search({
          textPlaceholder: cbn_searchmarkers_label,
          layer: window.oumMarkersLayer,
          propertyName: "content",
          initial: false,
          buildTip: OUMUtils.customAutoSuggestText,
          firstTipSubmit: true,
          autoCollapse: true,
          zoom: cbn_searchmarkers_zoom,
        })
        .addTo(map);
    }

    // Search markers control as searchbar
    if (cbn_enable_searchbar && cbn_searchbar_type === "markers") {
      L.control
        .search({
          textPlaceholder: cbn_searchmarkers_label,
          layer: window.oumMarkersLayer,
          propertyName: "content",
          initial: false,
          buildTip: OUMUtils.customAutoSuggestText,
          firstTipSubmit: true,
          autoCollapse: false,
          collapsed: false,
          zoom: cbn_searchmarkers_zoom,
          container: 'cbn_search_marker'
        })
        .addTo(map);
    }

    // Add searchbar for address search
    if (cbn_enable_searchbar && cbn_searchbar_type === "address") {
      let searchBar = new GeoSearch.GeoSearchControl({
        style: "bar",
        showMarker: false,
        provider: OUMUtils.initGeosearchProvider(),
        searchLabel: cbn_searchaddress_label,
        updateMap: false,
      });
      map.addControl(searchBar);
    }

    // Add button for address search
    if (cbn_enable_searchaddress_button) {
      let searchButton = new GeoSearch.GeoSearchControl({
        style: "button",
        showMarker: false,
        provider: OUMUtils.initGeosearchProvider(),
        searchLabel: cbn_searchaddress_label,
        updateMap: false,
      });
      map.addControl(searchButton);
    }

    // Current location control
    if (cbn_enable_currentlocation) {
      window.map_locate_process = L.control
        .locate({
          flyTo: true,
          showPopup: false,
        })
        .addTo(map);
    }
  }

  function setupMapEvents() {
    // Event: pan or zoom Map
    map.on("moveend", function (ev) {
      startPosition.lat = map.getCenter().lat;
      startPosition.lng = map.getCenter().lng;
      startPosition.zoom = map.getZoom();
    });

    // Event: Enter/Exit Fullscreen
    let addLocationPopup = document.querySelector("#add-location-overlay");
    let originalContainer = addLocationPopup?.parentElement;
    let fullscreenContainer = document.querySelector(
      ".Compass .map-wrap"
    );

    if (addLocationPopup) {
      map.on("enterFullscreen", function () {
        fullscreenContainer.appendChild(addLocationPopup);
      });

      map.on("exitFullscreen", function () {
        originalContainer.appendChild(addLocationPopup);
      });
    }

    // Event: geosearch success
    map.on("geosearch/showlocation", function(e) {
      let coords = e.marker._latlng;
      let isInBounds = map.getBounds().contains(coords);

      if (!isInBounds && cbn_enable_fixed_map_bounds === 'on') {
        console.log("This search result is out of reach.");
        let searchBar = document.querySelector(`#${map_el} .leaflet-geosearch-bar form`);
        if (searchBar) {
          searchBar.style.boxShadow = "0 0 10px rgb(255, 111, 105)";
          setTimeout(function () {
            searchBar.style.boxShadow = "0 1px 5px rgba(255, 255, 255, 0.65)";
          }, 2000);
        }
      } else {
        // Handle valid search result
        if (e.location.bounds) {
          map.flyToBounds(e.location.bounds);
        } else if (e.location.raw && e.location.raw.mapView) {
          map.flyToBounds([
            [e.location.raw.mapView.south, e.location.raw.mapView.west],
            [e.location.raw.mapView.north, e.location.raw.mapView.east]
          ]);
        } else {
          map.flyTo([e.location.y, e.location.x], 17);
        }
      }
    });
  }

  function setupRegionEvents() {
    document
      .querySelectorAll(".Compass .change_region")
      .forEach(function (btn) {
        btn.onclick = function (event) {
          let el = event.currentTarget;
          let region_lat = el.getAttribute("data-lat");
          let region_lng = el.getAttribute("data-lng");
          let region_zoom = el.getAttribute("data-zoom");

          let region_bounds = OUMUtils.latLngToBounds(
            parseFloat(region_lat),
            parseFloat(region_lng),
            parseFloat(region_zoom),
            570,
            372
          );
          let region_bounds_zoom = map.getBoundsZoom(region_bounds);

          // Center Map
          map.flyTo([region_lat, region_lng], region_bounds_zoom);

          // Update active state
          document
            .querySelectorAll(".Compass .change_region")
            .forEach(function (el) {
              el.classList.remove("active");
            });
          el.classList.add("active");
        };

        // Event: Change Region on ?region=Europe
        let REGION_ID = OUMUtils.getParameterByName("region");
        if (btn.textContent === REGION_ID) {
          btn.click();
        }
      });
  }

  // Public interface
  return {
    init: function (mapEl) {
      try {
        initializeStartPosition();

        // Initialize map
        map = L.map(mapEl, {
          gestureHandling: !cbn_enable_scrollwheel_zoom_map,
          minZoom: 1, // Set default minimum zoom
          attributionControl: true,
          fullscreenControl: cbn_enable_fullscreen,
          fullscreenControlOptions: {
            position: "topleft",
            fullscreenElement: document.querySelector(
              ".Compass .map-wrap"
            ),
          },
        });

        map.attributionControl.setPrefix(false);

        // First set up the tile layer
        setupTileLayer(OUMConfig.getMapStyle());

        // Calculate initial bounds based on settings map dimensions
        let boundsArray = OUMUtils.latLngToBounds(
          startPosition.lat,
          startPosition.lng,
          startPosition.zoom,
          570, // Width of settings map
          372 // Height of settings map
        );

        // Convert to Leaflet bounds
        let initialBounds = L.latLngBounds(
          L.latLng(boundsArray[0][0], boundsArray[0][1]),
          L.latLng(boundsArray[1][0], boundsArray[1][1])
        );

        // Set view to match settings map exactly, with zoom offset
        let zoomOffset = 0.7; // Zoom in a bit more to match settings map
        map.fitBounds(initialBounds, {
          animate: false,
          padding: [0, 0], // No padding to match exactly
          maxZoom: map.getBoundsZoom(initialBounds) + zoomOffset,
        });

        // Set up other components
        setupMapBounds();
        setupMapEvents();
        setupRegionEvents();

        window.oumMap = map;

        // Listen for markers initialized event
        document.addEventListener('oum:markers_initialized', function(e) {
          if (e.detail.mapId === mapEl) {
            // Set up controls after markers are ready
            setupControls();
          }
        }, { once: true });  // Use once: true to ensure it only runs once

        // Dispatch map initialized event when everything is ready
        document.dispatchEvent(new CustomEvent('oum:map_initialized', {
          detail: {
            mapId: mapEl,
            map: oumMap
          }
        }));

        return map;
      } catch (error) {
        OUMErrorHandler.showError("Error initializing map: " + error.message);
        throw error;
      }
    },
    getMap: function () {
      return map;
    },
    getStartPosition: function () {
      return startPosition;
    },
  };
})();

/**
 * Markers Module - Handles all marker-related functionality
 */
let OUMMarkers = (function () {
  // Private variables
  let markersLayer = null;
  let allMarkers = [];
  let map = null;

  // Private functions
  function initializeMarkersLayer() {
    markersLayer = !cbn_enable_cluster
      ? L.layerGroup({ chunkedLoading: true })
      : L.markerClusterGroup({
          showCoverageOnHover: false,
          removeOutsideVisibleBounds: false,
          maxClusterRadius: 40,
          chunkedLoading: true,
        });
  }

  function createMarker(location) {
    let contentText = (
      location.title +
      " | " +
      location.content.replace(/(<([^>]+)>)/gi, " ").replace(/\s\s+/g, " ")
    ).toLowerCase();

    let marker = L.marker([location.lat, location.lng], {
      title: location.title,
      post_id: location.post_id,
      content: contentText,
      icon: L.icon({
        iconUrl: location.icon,
        iconSize: [26, 41],
        iconAnchor: [13, 41],
        popupAnchor: [0, -25],
        shadowUrl: marker_shadow_url,
        shadowSize: [41, 41],
        shadowAnchor: [13, 41],
      }),
      types: location.types || [],
    });

    let popup = L.responsivePopup().setContent(location.content);
    marker.bindPopup(popup);

    return marker;
  }

  function setupMarkerEvents() {
    // Event: Open Location Bubble
    map.on("popupopen", function (locationBubble) {
      let el = document.querySelector(
        ".Compass #location-fullscreen-container"
      );
      el.querySelector(".location-content-wrap").innerHTML =
        locationBubble.popup.getContent();
      el.classList.add("visible");
      document.querySelector("body").classList.add("oum-location-opened");
    });

    // Event: Close Location Bubble
    map.on("popupclose", function () {
      let el = document.querySelector(
        ".Compass #location-fullscreen-container"
      );
      el.classList.remove("visible");
      document.querySelector("body").classList.remove("oum-location-opened");
    });
  }

  function filterMarkers() {
    // Get filter values
    let markerFilterInput = document.getElementById("cbn_filter_markers");
    let filter = markerFilterInput
      ? markerFilterInput.value.toLowerCase()
      : "";
    let categoryInputs = document.querySelectorAll(
      '.Compass .oum-filter-controls [name="type"]'
    );
    let checkedCategories = Array.from(categoryInputs)
      .filter((input) => input.checked)
      .map((input) => input.value);

    // Clear existing markers
    markersLayer.clearLayers();

    // Filter and re-add markers
    allMarkers.forEach((marker) => {
      let contentText = marker.options.content.toLowerCase();
      let markerTypes = marker.options.types || [];

      let matchesTextFilter = !filter || contentText.includes(filter);
      let matchesCategoryFilter =
        markerTypes.length === 0 ||
        (checkedCategories.length === 0 && markerTypes.length === 0) ||
        markerTypes.some((type) => checkedCategories.includes(type));

      if (matchesTextFilter && matchesCategoryFilter) {
        markersLayer.addLayer(marker);
      }
    });
  }

  function setupFilterListEvents() {
    let filterControls = document.querySelector(
      ".Compass .oum-filter-controls"
    );
    if (!filterControls) return;

    // Function to show the filter list
    function showFilterList() {
      filterControls.classList.add("active");
    }

    // Function to hide the filter list
    function hideFilterList() {
      filterControls.classList.remove("active");
    }

    // Event: Open Filter List (mouseover for collapsed design)
    if (filterControls.classList.contains("use-collapse")) {
      filterControls
        .querySelector(".oum-filter-toggle")
        ?.addEventListener("mouseover", showFilterList);
      filterControls
        .querySelector(".oum-filter-list")
        ?.addEventListener("mouseleave", hideFilterList);
    }

    // Event: Open Filter List (click)
    filterControls
      .querySelector(".oum-filter-toggle")
        ?.addEventListener("click", showFilterList);

    // Event: Close Filter List (click on close button)
    filterControls
      .querySelector(".oum-filter-list .close-filter-list")
        ?.addEventListener("click", hideFilterList);
  }

  function handleAutoOpenMarker(markerId) {
    let targetMarker = allMarkers.find((m) => m.options.post_id === markerId);
    if (targetMarker) {
      if (cbn_enable_cluster) {
        // For clustered markers:
        // First zoom to the marker's location
        map.setView(targetMarker.getLatLng(), cbn_searchmarkers_zoom - 1);

        // Then wait a bit for the clustering to update
        setTimeout(() => {
          // Try to zoom to the specific marker and show it
          markersLayer.zoomToShowLayer(targetMarker, () => {
            targetMarker.openPopup();
          });
        }, 500);
      } else {
        // For non-clustered markers, we can open the popup directly
        map.setView(targetMarker.getLatLng(), cbn_searchmarkers_zoom);
        targetMarker.openPopup();
      }
    }
  }

  // Public interface
  return {
    init: function (mapInstance) {
      map = mapInstance;
      initializeMarkersLayer();
      markersLayer.addTo(map);
      setupMarkerEvents();
      setupFilterListEvents();

      // Make layer globally available (for backward compatibility)
      window.oumMarkersLayer = markersLayer;
      window.oumAllMarkers = allMarkers;

      // Dispatch markers initialized event
      document.dispatchEvent(new CustomEvent('oum:markers_initialized', {
        detail: {
          mapId: map._container.id,
          markersLayer: markersLayer
        }
      }));

      return this;
    },
    addMarkers: function (locations) {
      locations.forEach((location) => {
        let marker = createMarker(location);
        allMarkers.push(marker);
        markersLayer.addLayer(marker);
      });

      // After adding all markers, check if we need to auto-open one
      let POPUP_MARKER_ID = OUMUtils.getParameterByName("markerid");
      if (POPUP_MARKER_ID) {
        handleAutoOpenMarker(POPUP_MARKER_ID);
      }
    },
    filterMarkers: filterMarkers,
    getMarkersLayer: function () {
      return markersLayer;
    },
    getAllMarkers: function () {
      return allMarkers;
    },
  };
})();

/**
 * Form Map Module - Handles all map-related functionality for the form
 */
let OUMFormMap = (function () {
  // Private variables
  let formMap = null;
  let locationMarker = null;
  let markerIsVisible = false;
  let isAdjusting = false;
  let isInitialized = false;

  // Private functions
  function initializeFormMap() {
    if (isInitialized) {
      return;
    }

    formMap = L.map("mapGetLocation", {
      attributionControl: false,
      gestureHandling: true,
      minZoom: 1,
      zoomSnap: 1,
      zoomDelta: 1,
      fullscreenControl: cbn_enable_fullscreen,
      fullscreenControlOptions: {
        position: "topleft",
      },
    });

    // Make form map globally available (for backward compatibility)
    window.oumMap2 = formMap;

    setupTileLayer();
    setupControls();
    setupMarker();
    setupMapEvents();

    // Always apply bounds to prevent showing repeated maps
    let boundsToUse = cbn_enable_fixed_map_bounds
      ? sharedMapBounds
      : OUMConfig.defaults.map.bounds;

    // Set the bounds
    formMap.setMaxBounds(boundsToUse);

    // Set minimum zoom level based on bounds
    let maxVisibleBounds = formMap.getBoundsZoom(boundsToUse);
    formMap.setMinZoom(maxVisibleBounds);

    // Add moveend event to enforce bounds
    formMap.on("moveend", function () {
      if (isAdjusting) return;
      isAdjusting = true;

      let zoom = formMap.getZoom();

      // Only enforce bounds if we're zoomed in beyond the minimum zoom
      if (zoom > maxVisibleBounds) {
        let currentBounds = formMap.getBounds();
        let currentCenter = formMap.getCenter();

        let needsAdjustment = false;
        let newLat = currentCenter.lat;
        let newLng = currentCenter.lng;

        // Calculate current viewport dimensions
        let viewportHeight = currentBounds.getNorth() - currentBounds.getSouth();
        let viewportWidth = currentBounds.getEast() - currentBounds.getWest();

        // Check and adjust latitude (north/south)
        if (currentBounds.getNorth() > boundsToUse.getNorth()) {
          newLat = boundsToUse.getNorth() - viewportHeight / 2;
          needsAdjustment = true;
        } else if (currentBounds.getSouth() < boundsToUse.getSouth()) {
          newLat = boundsToUse.getSouth() + viewportHeight / 2;
          needsAdjustment = true;
        }

        // Check and adjust longitude (east/west)
        if (currentBounds.getEast() > boundsToUse.getEast()) {
          newLng = boundsToUse.getEast() - viewportWidth / 2;
          needsAdjustment = true;
        } else if (currentBounds.getWest() < boundsToUse.getWest()) {
          newLng = boundsToUse.getWest() + viewportWidth / 2;
          needsAdjustment = true;
        }

        if (needsAdjustment) {
          formMap.setView([newLat, newLng], zoom, { animate: false });
        }
      }

      isAdjusting = false;
    });

    isInitialized = true;
  }

  function setupTileLayer() {
    // Default to OpenStreetMap if mapStyle is undefined
    let mapStyle = window.mapStyle || 'OpenStreetMap.Mapnik';

    if (mapStyle === "Custom1") {
      L.tileLayer("https://{s}.basemaps.cartocdn.com/light_nolabels/{z}/{x}/{y}.png").addTo(formMap);
      L.tileLayer("https://{s}.basemaps.cartocdn.com/rastertiles/voyager_only_labels/{z}/{x}/{y}{r}.png", {
        tileSize: 512,
        zoomOffset: -1,
      }).addTo(formMap);
    } else if (mapStyle === "Custom2") {
      L.tileLayer("https://{s}.basemaps.cartocdn.com/dark_nolabels/{z}/{x}/{y}.png").addTo(formMap);
      L.tileLayer("https://{s}.basemaps.cartocdn.com/rastertiles/voyager_only_labels/{z}/{x}/{y}{r}.png", {
        tileSize: 512,
        zoomOffset: -1,
      }).addTo(formMap);
    } else if (mapStyle === "Custom3") {
      L.tileLayer("https://{s}.basemaps.cartocdn.com/dark_nolabels/{z}/{x}/{y}.png").addTo(formMap);
      L.tileLayer("https://{s}.basemaps.cartocdn.com/rastertiles/voyager_only_labels/{z}/{x}/{y}{r}.png", {
        tileSize: 512,
        zoomOffset: -1,
      }).addTo(formMap);
    } else if (mapStyle.startsWith("MapBox.")) {
      L.tileLayer.provider("MapBox", {
        id: mapStyle.replace("MapBox.", "mapbox/") + (mapStyle.includes("-v") ? "" : "-v12"),
        accessToken: OUMConfig.getTileProviderKey(),
      }).addTo(formMap);
    } else {
      // Default
      L.tileLayer.provider(mapStyle).addTo(formMap);
    }
  }

  function setupControls() {
    // Add searchbar: address
    let search = new GeoSearch.GeoSearchControl({
      style: "bar",
      showMarker: false,
      provider: OUMUtils.initGeosearchProvider(),
      searchLabel: cbn_searchaddress_label,
      updateMap: false,
    });
    formMap.addControl(search);

    // Add control: get current location
    if (cbn_enable_currentlocation) {
      window.map2_locate_process = L.control.locate({
        flyTo: true,
        showPopup: false,
      }).addTo(formMap);
    }
  }

  function setupMarker() {
    // Marker Icon
    let markerIcon = L.icon({
      iconUrl: marker_icon_url,
      iconSize: [26, 41],
      iconAnchor: [13, 41],
      popupAnchor: [0, -25],
      shadowUrl: marker_shadow_url,
      shadowSize: [41, 41],
      shadowAnchor: [13, 41],
    });

    locationMarker = L.marker([0, 0], {
      icon: markerIcon,
      draggable: true,
    });

    // Make marker globally available (for backward compatibility)
    window.locationMarker = locationMarker;
    window.markerIsVisible = markerIsVisible;

    // Event: drag marker
    locationMarker.on("dragend", function (e) {
      setLocationLatLng(e.target.getLatLng());
    });
  }

  function setupMapEvents() {
    // Event: click on map to set marker OR location found
    formMap.on("click locationfound", function (e) {
      let coords = e.latlng;
      locationMarker.setLatLng(coords);

      if (!markerIsVisible) {
        locationMarker.addTo(formMap);
        markerIsVisible = true;
        window.markerIsVisible = true;
      }

      setLocationLatLng(coords);
    });

    // Event: geosearch success
    formMap.on("geosearch/showlocation", handleGeosearchSuccess);
  }

  function handleGeosearchSuccess(e) {
    let coords = e.marker._latlng;
    let label = e.location.label;
    let isInBounds = formMap.getBounds().contains(coords);
    let searchBar = document.querySelector(`#mapGetLocation .leaflet-geosearch-bar form`);

    if (!isInBounds && cbn_enable_fixed_map_bounds) {
      console.log("This search result is out of reach.");
      searchBar.style.boxShadow = "0 0 10px rgb(255, 111, 105)";
      setTimeout(function () {
        searchBar.style.boxShadow = "0 1px 5px rgba(255, 255, 255, 0.65)";
      }, 2000);
    } else {
      handleValidGeosearchResult(e.location);
      locationMarker.setLatLng(coords);

      if (!markerIsVisible) {
        locationMarker.addTo(formMap);
        markerIsVisible = true;
        window.markerIsVisible = true;
      }

      setLocationLatLng(coords);
    }
  }

  function handleValidGeosearchResult(location) {
    if (location.bounds !== null) {
      formMap.flyToBounds(location.bounds);
    } else if (location.raw.mapView) {
      formMap.flyToBounds([
        [location.raw.mapView.south, location.raw.mapView.west],
        [location.raw.mapView.north, location.raw.mapView.east],
      ]);
    } else {
      formMap.flyTo([location.y, location.x], 17);
    }
  }

  function setLocationLatLng(markerLatLng) {
    document.getElementById("cbn_location_lat").value = markerLatLng.lat;
    document.getElementById("cbn_location_lng").value = markerLatLng.lng;
  }

  // Public interface
  return {
    init: function() {
      if (!document.getElementById("mapGetLocation")) {
        return;
      }
      initializeFormMap();
    },
    setLocation: function(lat, lng) {
      if (!locationMarker) return;
      locationMarker.setLatLng([lat, lng]);
      if (!markerIsVisible) {
        locationMarker.addTo(formMap);
        markerIsVisible = true;
        window.markerIsVisible = true;
      }
      setLocationLatLng({lat, lng});
    },
    clearMarker: function() {
      if (locationMarker && formMap) {
        locationMarker.remove();
        markerIsVisible = false;
        window.markerIsVisible = false;
      }
    },
    invalidateSize: function() {
      if (formMap) {
        formMap.invalidateSize();
      }
    },
    getMap: function() {
      return formMap;
    },
    setView: function(lat, lng, zoom) {
      if (formMap) {
        formMap.setView([lat, lng], zoom);
      }
    }
  };
})();

/**
 * Form Controller Module - Handles all form-related functionality
 */
let OUMFormController = (function () {
  // Private variables
  let isEditMode = false;
  let currentLocationId = null;
  let selectedFiles = [];

  // Private functions
  function showFormMessage(type, headline, message, buttonText = null, buttonCallback = null) {
    let form = document.getElementById('cbn_add_location');
    let errorDiv = document.getElementById('cbn_add_location_error');
    let thankyouDiv = document.getElementById('cbn_add_location_thankyou');

    if (!form || !errorDiv || !thankyouDiv) {
      console.error('Required form elements not found');
      return;
    }

    // Hide form and error
    form.style.display = 'none';
    errorDiv.style.display = 'none';

    // Update thank you message
    let headlineEl = thankyouDiv.querySelector('h3');
    let messageEl = thankyouDiv.querySelector('.oum-add-location-thankyou-text');
    let buttonEl = thankyouDiv.querySelector('button');

    if (!headlineEl || !messageEl || !buttonEl) {
      // Create elements if they don't exist
      if (!headlineEl) {
        let newHeadline = document.createElement('h3');
        thankyouDiv.appendChild(newHeadline);
      }
      if (!messageEl) {
        let newMessage = document.createElement('p');
        newMessage.className = 'oum-add-location-thankyou-text';
        thankyouDiv.appendChild(newMessage);
      }
      if (!buttonEl) {
        let newButton = document.createElement('button');
        thankyouDiv.appendChild(newButton);
      }
    }

    // Get elements again (they should exist now)
    let finalHeadlineEl = thankyouDiv.querySelector('h3');
    let finalMessageEl = thankyouDiv.querySelector('.oum-add-location-thankyou-text');
    let finalButtonEl = thankyouDiv.querySelector('button');

    // Add specific class for delete confirmation
    thankyouDiv.className = type === 'confirm-delete' ? 'oum-delete-confirmation' : '';

    if (finalHeadlineEl) finalHeadlineEl.textContent = headline || '';
    if (finalMessageEl) finalMessageEl.textContent = message || '';

    // Handle button
    if (finalButtonEl) {
      if (buttonText && buttonCallback) {
        finalButtonEl.textContent = buttonText;
        finalButtonEl.onclick = buttonCallback;
        finalButtonEl.style.display = 'inline-block';
      } else {
        finalButtonEl.style.display = 'none';
      }
    }

    thankyouDiv.style.display = 'block';
  }

  function setupDeleteButton() {
    let deleteBtn = document.getElementById('cbn_delete_location_btn');
    if (deleteBtn) {
      deleteBtn.addEventListener('click', function(e) {
        e.preventDefault();

        // Show confirmation using the message system
        showFormMessage(
          'confirm-delete',
          wp.i18n.__('Delete this location?', 'Compass'),
          wp.i18n.__('This action cannot be undone. The location will be permanently removed from the map.', 'Compass'),
          wp.i18n.__('Yes, delete location', 'Compass'),
          function() {
            // Set delete flag
            document.getElementById('cbn_delete_location').value = 'true';

            // Get the form
            let form = document.getElementById('cbn_add_location');
            let formData = new FormData(form);
            formData.append('action', 'cbn_add_location_from_frontend');

            // Submit via AJAX
            jQuery.ajax({
              type: 'POST',
              url: cbn_ajax.ajaxurl,
              cache: false,
              contentType: false,
              processData: false,
              data: formData,
              success: function(response) {
                if (response.success) {
                  showFormMessage(
                    'success',
                    wp.i18n.__('Location deleted', 'Compass'),
                    wp.i18n.__('The location has been successfully removed from the map.', 'Compass'),
                    wp.i18n.__('Close and refresh map', 'Compass'),
                    function() {
                      window.location.reload();
                    }
                  );
                } else {
                  oumShowError(response.data);
                }
              },
              error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(errorThrown);
                oumShowError([{message: wp.i18n.__('An error occurred while deleting the location. Please try again.', 'Compass')}]);
              }
            });
          }
        );
      });
    }
  }

  function setupFormEvents() {
    // Event: click on "+ Add Location" button
    let addLocationBtn = document.getElementById("open-add-location-overlay");
    if (addLocationBtn) {
      addLocationBtn.addEventListener("click", handleAddLocationClick);
    }

    // Event: click on "Edit Location" button
    document.addEventListener('click', function(e) {
      if (e.target && e.target.classList.contains('edit-location-button')) {
        e.preventDefault();
        let locationId = e.target.getAttribute('data-post-id');
        let location = window.cbn_all_locations.find(loc => loc.post_id === locationId);

        if (location) {
          resetForm();
          openForm(location);
        }
      }
    });

    setupCloseEvents();
    setupNotificationEvents();
    setupMediaEvents();
    setupValidation();
  }

  function handleAddLocationClick() {
    resetForm();
    openForm();
  }

  function openForm(location = null) {
    document.querySelector(".add-location").classList.add("active");
    document.body.classList.add("oum-add-location-opened");

    setTimeout(function () {
      // Initialize map if needed
      OUMFormMap.init();

      if (location) {
        populateForm(location);
      } else {
        // Set view to match main map
        let mainMapEl = document.querySelector(`#${map_el}`);
        if (mainMapEl) {
          let mainMap = window.oumMap;
          let mainCenter = mainMap.getCenter();
          let mainZoom = mainMap.getZoom();
          OUMFormMap.setView(mainCenter.lat, mainCenter.lng, mainZoom);
        }
      }

      // Add a separate timeout for invalidateSize
      setTimeout(() => {
        OUMFormMap.invalidateSize();
      }, 200);
    }, 150);
  }

  function setupCloseEvents() {
    let closeBtn = document.getElementById("close-add-location-overlay");
    if (!closeBtn) return;

    // Close button click
    closeBtn.addEventListener("click", closeForm);

    // ESC key
    document.addEventListener("keydown", function(evt) {
      evt = evt || window.event;
      if (evt.key === "Escape" && document.getElementById("add-location-overlay").classList.contains("active")) {
        closeForm();
      }
    });

    // Backdrop click
    document.getElementById("add-location-overlay").addEventListener("click", function(event) {
      if (event.target === this) {
        closeForm();
      }
    });
  }

  function closeForm() {
    let addLocationOverlay = document.getElementById("add-location-overlay");
    if (addLocationOverlay) {
      addLocationOverlay.classList.remove("active");
    }

    // Stop locate process
    if (window.map2_locate_process) {
      window.map2_locate_process.stop();
    }

    // Allow body scrolling
    document.querySelector("body").classList.remove("oum-add-location-opened");

    // Reset form and clear marker
    resetForm();
    OUMFormMap.clearMarker();
  }

  function setupNotificationEvents() {
    let notificationCheckbox = document.getElementById("cbn_location_notification");
    if (notificationCheckbox) {
      notificationCheckbox.addEventListener("change", function() {
        let authorFields = document.getElementById("cbn_author");
        let nameField = document.getElementById("cbn_location_author_name");
        let emailField = document.getElementById("cbn_location_author_email");

        if (this.checked) {
          authorFields.classList.add("active");
          nameField.required = true;
          emailField.required = true;
        } else {
          authorFields.classList.remove("active");
          nameField.required = false;
          emailField.required = false;
        }
      });
    }
  }

  function setupMediaEvents() {
    // Image upload
    let imageInput = document.getElementById("cbn_location_images");
    if (imageInput) {
      // Let OUMMedia handle the image upload
      OUMMedia.initializeImageUpload(imageInput);
    }

    // Remove image button
    let removeImageBtn = document.getElementById("cbn_remove_image");
    if (removeImageBtn) {
      removeImageBtn.addEventListener("click", function() {
        document.getElementById("cbn_location_images_preview").innerHTML = "";
        document.getElementById("cbn_remove_existing_image").value = "1";
      });
    }

    // Remove audio button
    let removeAudioBtn = document.getElementById("cbn_remove_audio");
    if (removeAudioBtn) {
      removeAudioBtn.addEventListener("click", function() {
        let audioInput = document.getElementById("cbn_location_audio");
        let previewContainer = audioInput.nextElementSibling;
        let previewDiv = previewContainer.querySelector('.audio-preview');

        // Clear the file input
        audioInput.value = "";

        // Clear the preview
        if (previewDiv) {
          previewDiv.innerHTML = '';
        }

        // Remove active state
        previewContainer.classList.remove("active");

        // Set remove flag
        document.getElementById("cbn_remove_existing_audio").value = "1";
      });
    }
  }

  function setupValidation() {
    // Add validation logic here
  }

  function populateForm(location) {
    isEditMode = true;
    currentLocationId = location.post_id;

    // Add edit-location class
    let addLocationEl = document.querySelector(".add-location");
    if (addLocationEl) {
        addLocationEl.classList.add("edit-location");
    }

    // Set post_id
    let postIdField = document.getElementById("cbn_post_id");
    if (postIdField) {
      postIdField.value = location.post_id;
    }

    // Basic fields
    let titleField = document.getElementById("cbn_location_title");
    let latField = document.getElementById("cbn_location_lat");
    let lngField = document.getElementById("cbn_location_lng");
    let addressField = document.getElementById("cbn_location_address");

    if (titleField) titleField.value = location.title || "";
    if (latField) latField.value = location.lat || "";
    if (lngField) lngField.value = location.lng || "";
    if (addressField) addressField.value = location.address || "";

    // Marker types/categories
    if (location.types && Array.isArray(location.types)) {
        if (typeof cbn_enable_multiple_marker_types !== 'undefined' && cbn_enable_multiple_marker_types === 'true') {
            // Handle multiple marker types (checkboxes)
            let checkboxes = document.querySelectorAll('input[name="cbn_marker_icon[]"]');
            if (checkboxes.length > 0) {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = location.types.includes(checkbox.value);
                });
            }
        } else {
            // Handle single marker type (select)
            let markerSelect = document.querySelector('select#cbn_marker_icon');
            if (markerSelect) {
                markerSelect.value = location.types[0] || '';
            }
        }
    }

    // Description
    let descriptionField = document.getElementById("cbn_location_text");
    if (descriptionField) {
        descriptionField.value = location.text || "";
    }

    // Video field
    let videoField = document.getElementById("cbn_location_video");
    if (videoField && location.video) {
        videoField.value = location.video;
    }

    // Handle custom fields
    if (location.custom_fields && Array.isArray(location.custom_fields)) {
        location.custom_fields.forEach(field => {
            if (!field || typeof field.index === 'undefined') return;

            if (field.fieldtype === 'checkbox') {
                let fieldValues = Array.isArray(field.val) ? field.val : [field.val];
                let checkboxes = document.querySelectorAll(
                    `input[type="checkbox"][name="cbn_location_custom_fields[${field.index}][]"]`
                );

                if (checkboxes.length > 0) {
                    checkboxes.forEach(checkbox => {
                        if (checkbox) {
                            let checkboxValue = checkbox.value.trim();
                            let normalizedValues = fieldValues.map(val => (val || '').toString().trim());
                            checkbox.checked = normalizedValues.includes(checkboxValue);
                        }
                    });
                }
            } else if (field.fieldtype === 'radio') {
                let radioInputs = document.querySelectorAll(
                    `input[type="radio"][name="cbn_location_custom_fields[${field.index}]"]`
                );

                if (radioInputs.length > 0) {
                    radioInputs.forEach(radio => {
                        if (radio) {
                            radio.checked = radio.value === field.val;
                        }
                    });
                }
            } else {
                let input = document.querySelector(`[name="cbn_location_custom_fields[${field.index}]"]`);
                if (input) {
                    input.value = field.val || '';
                }
            }
        });
    }

    // Handle images
    if (location.image) {
      let imageUrls = location.image.split('|').filter(url => url);
      let previewContainer = document.getElementById('cbn_location_images_preview');

      if (previewContainer) {
        previewContainer.innerHTML = '';

        imageUrls.forEach(url => {
          if (!url) return;

          let previewItem = document.createElement('div');
          previewItem.className = 'image-preview-item existing-image';
          previewItem.dataset.url = url;

          previewItem.innerHTML = `
            <img src="${url}" alt="Preview">
            <div class="remove-image" title="Remove image">&times;</div>
            <div class="drag-handle" title="Drag to reorder">⋮⋮</div>
            <input type="hidden" name="existing_images[]" value="${url}">
          `;

          // Add event listener for remove button
          let removeButton = previewItem.querySelector('.remove-image');
          if (removeButton) {
            removeButton.addEventListener('click', OUMMedia.handleRemoveImage);
          }

          // Set up drag and drop for existing images
          OUMMedia.setupDragAndDrop(previewItem);

          previewItem.style.opacity = "0";
          previewItem.style.transform = "scale(0.9)";
          previewItem.style.transition = "all 0.3s ease";
          previewItem.style.opacity = "1";
          previewItem.style.transform = "scale(1)";
          previewContainer.appendChild(previewItem);
        });
      }
    }

    // Handle audio
    if (location.audio) {
      OUMMedia.setExistingAudio(location.audio);
      document.getElementById("cbn_remove_existing_audio").value = "0";
    }

    // Set map view to location
    if (location.lat && location.lng) {
      OUMFormMap.setView(location.lat, location.lng, 16);
      OUMFormMap.setLocation(location.lat, location.lng);
    }
  }

  function resetForm() {
    isEditMode = false;
    currentLocationId = null;
    selectedFiles = [];
    window.oumSelectedFiles = [];

    let addLocationEl = document.querySelector(".add-location");
    if (addLocationEl) {
      addLocationEl.classList.remove("edit-location");
    }

    // Reset form and message system
    let form = document.getElementById("cbn_add_location");
    let errorDiv = document.getElementById("cbn_add_location_error");
    let thankyouDiv = document.getElementById("cbn_add_location_thankyou");

    // Reset form if it exists
    if (form) {
      form.reset();
      form.style.display = 'block';
    }

    // Reset custom fields
    let customFields = document.querySelectorAll('[name^="cbn_location_custom_fields"]');
    if (customFields) {
      customFields.forEach(field => {
        if (field.type === 'checkbox' || field.type === 'radio') {
          field.checked = false;
        } else if (field.type === 'select-one') {
          field.selectedIndex = 0;
        } else {
          field.value = '';
        }
      });
    }

    // Reset post_id field
    let postIdField = document.getElementById("cbn_post_id");
    if (postIdField) {
      postIdField.value = "";
    }

    // Reset cbn_delete_location field
    let deleteLocationField = document.getElementById("cbn_delete_location");
    if (deleteLocationField) {
        deleteLocationField.value = "";
    }

    // Reset error message if it exists
    if (errorDiv) {
      errorDiv.style.display = 'none';
    }

    // Reset thank you message if it exists
    if (thankyouDiv) {
      thankyouDiv.style.display = 'none';
      thankyouDiv.classList.remove('oum-delete-confirmation');
    }

    // Reset image preview
    let previewContainer = document.getElementById("cbn_location_images_preview");
    if (previewContainer) {
      previewContainer.innerHTML = "";
    }

    // Reset audio preview
    let audioInput = document.getElementById("cbn_location_audio");
    if (audioInput) {
      let previewContainer = audioInput.nextElementSibling;
      let previewDiv = previewContainer.querySelector('.audio-preview');

      // Clear the file input
      audioInput.value = "";

      // Clear the preview
      if (previewDiv) {
        previewDiv.innerHTML = '';
      }

      // Remove active state
      previewContainer.classList.remove("active");
    }

    // Reset hidden fields
    let removeExistingImage = document.getElementById("cbn_remove_existing_image");
    if (removeExistingImage) {
      removeExistingImage.value = "0";
    }

    let removeExistingAudio = document.getElementById("cbn_remove_existing_audio");
    if (removeExistingAudio) {
      removeExistingAudio.value = "0";
    }

    // Reset author section
    let authorSection = document.getElementById("cbn_author");
    if (authorSection) {
      authorSection.classList.remove("active");
    }

    // Reset name and email fields
    let nameField = document.getElementById("cbn_location_author_name");
    let emailField = document.getElementById("cbn_location_author_email");
    if (nameField && emailField) {
      nameField.required = false;
      emailField.required = false;
    }
  }

  // Public interface
  return {
    init: function() {
      setupFormEvents();
      setupDeleteButton(); // Add delete button handler
    },
    showFormMessage: showFormMessage,
    openForm: openForm,
    closeForm: closeForm,
    resetForm: resetForm,
    populateForm: populateForm,
    isEditMode: function() {
      return isEditMode;
    },
    getCurrentLocationId: function() {
      return currentLocationId;
    }
  };
})();

/**
 * Media Module - Handles image upload and preview functionality
 */
let OUMMedia = (function () {
  // Private variables
  let selectedFiles = [];
  let startX, startY, originalPosition, placeholder;
  let isDragging = false;

  // Private functions
  function initializeImageUpload(imageInput) {
    if (!imageInput) return;

    // Add click handler for upload icon label
    let uploadLabel = imageInput.parentElement.querySelector(
      'label[for="cbn_location_images"]'
    );
    if (uploadLabel) {
      uploadLabel.addEventListener("click", function (e) {
        e.preventDefault();
        imageInput.click();
      });
    }

    // Setup drag and drop handlers
    document.addEventListener("mousemove", handleDragMove);
    document.addEventListener("mouseup", handleDragEnd);
    if (imageInput.parentElement.querySelector(".image-preview-container")) {
      imageInput.parentElement.querySelector(".image-preview-container").addEventListener("dragover", handleDragOver);
    }

    // Setup image input change handler
    imageInput.addEventListener("change", handleImageInputChange);
  }

  function initializeAudioUpload() {
    let audioInput = document.getElementById('cbn_location_audio');
    if (!audioInput) return;

    // Add click handler for upload icon label
    let uploadLabel = audioInput.parentElement.querySelector(
      'label[for="cbn_location_audio"]'
    );
    if (uploadLabel) {
      uploadLabel.addEventListener("click", function (e) {
        e.preventDefault();
        audioInput.click();
      });
    }

    // Setup audio input change handler
    audioInput.addEventListener("change", handleAudioInputChange);
  }

  function handleAudioInputChange(e) {
    let audioInput = e.target;
    let previewContainer = audioInput.nextElementSibling;
    let previewDiv = previewContainer.querySelector('.audio-preview');

    if (audioInput.files && audioInput.files[0]) {
      let file = audioInput.files[0];

      previewContainer.classList.add('active');

      // Create audio preview element
      let audio = document.createElement('audio');
      audio.controls = true;
      audio.style.width = '100%';

      let source = document.createElement('source');
      source.src = URL.createObjectURL(file);
      source.type = file.type;

      audio.appendChild(source);

      // Replace existing audio preview if any
      previewDiv.innerHTML = '';
      previewDiv.appendChild(audio);
    }
  }

  function setExistingAudio(audioUrl) {
    if (!audioUrl) return;

    let audioInput = document.getElementById('cbn_location_audio');
    if (!audioInput) return;

    let previewContainer = audioInput.nextElementSibling;
    let previewDiv = previewContainer.querySelector('.audio-preview');

    previewContainer.classList.add('active');

    // Create audio preview element
    let audio = document.createElement('audio');
    audio.controls = true;
    audio.style.width = '100%';

    let source = document.createElement('source');
    source.src = audioUrl;
    source.type = 'audio/' + audioUrl.split('.').pop();

    audio.appendChild(source);

    // Replace existing audio preview if any
    previewDiv.innerHTML = '';
    previewDiv.appendChild(audio);
  }

  function handleImageInputChange(e) {
    let imageInput = e.target;
    let previewContainer = document.getElementById(
      "cbn_location_images_preview"
    );
    let maxFiles = parseInt(imageInput.dataset.maxFiles) || 5;
    let maxFileSize = OUMConfig.defaults.media.maxImageSize; // in bytes

    // Convert FileList to Array and store in a variable
    let files = Array.prototype.slice.call(e.target.files);
    let existingCount = selectedFiles.length;
    let totalFiles = existingCount + files.length;

    if (totalFiles > maxFiles) {
      alert(
        wp.i18n.sprintf(
          /* translators: %1$d: maximum number of files, %2$d: number of files that will be used */
          wp.i18n.__('Maximum %1$d images allowed. Only the first %2$d new images will be used.', 'Compass'),
          maxFiles,
          maxFiles - existingCount
        )
      );
    }

    // Process only up to remaining slots
    let remainingSlots = maxFiles - existingCount;
    let filesToProcess = files.slice(0, remainingSlots);

    // Validate file sizes and collect valid files
    let validFiles = [];
    let invalidFiles = [];

    filesToProcess.forEach(file => {
      if (file.size > maxFileSize) {
        invalidFiles.push(file.name);
      } else {
        validFiles.push(file);
      }
    });

    // Show error message for invalid files
    if (invalidFiles.length > 0) {
      let maxSizeMB = Math.round(maxFileSize / (1024 * 1024));
      alert(
        wp.i18n.sprintf(
          /* translators: %1$d: maximum file size in MB, %2$s: list of files */
          wp.i18n.__('The following images exceed the maximum file size of %1$dMB:\n%2$s', 'Compass'),
          maxSizeMB,
          invalidFiles.join('\n')
        )
      );
    }

    // Update selected files with only valid ones
    selectedFiles = [...selectedFiles, ...validFiles];

    // Create previews for valid files only
    createImagePreviews(validFiles, previewContainer);

    // Make selectedFiles available globally for the form submission
    window.oumSelectedFiles = selectedFiles;
  }

  function createImagePreviews(files, container) {
    files.forEach((file) => {
      let reader = new FileReader();

      reader.onload = function (e) {
        let previewItem = createPreviewItem(e.target.result, file.name);

        // Add the item with a fade-in animation
        previewItem.style.opacity = "0";
        previewItem.style.transform = "scale(0.9)";
        container.appendChild(previewItem);

        // Trigger animation after a brief delay
        setTimeout(() => {
          previewItem.style.transition = "all 0.3s ease";
          previewItem.style.opacity = "1";
          previewItem.style.transform = "scale(1)";
        }, 50);
      };

      reader.readAsDataURL(file);
    });
  }

  function createPreviewItem(imgSrc, fileName) {
    let previewItem = document.createElement("div");
    previewItem.className = "image-preview-item";
    previewItem.dataset.fileName = fileName;

    previewItem.innerHTML = `
      <img src="${imgSrc}" alt="Preview">
      <div class="remove-image" title="Remove image">&times;</div>
      <div class="drag-handle" title="Drag to reorder">⋮⋮</div>
    `;

    // Add event listener for remove button
    let removeButton = previewItem.querySelector('.remove-image');
    if (removeButton) {
      removeButton.addEventListener('click', handleRemoveImage);
    }

    // Set up drag and drop for existing images
    setupDragAndDrop(previewItem);

    return previewItem;
  }

  function handleRemoveImage(e) {
    e.preventDefault();
    let previewItem = this.closest(".image-preview-item");

    // If it's an existing image, handle removal differently
    if (previewItem.classList.contains("existing-image")) {
      let imgUrl = previewItem.querySelector("[name='existing_images[]']").value;
      let removedImagesInput = document.getElementById("cbn_remove_existing_image");
      let currentValue = removedImagesInput.value === "0" ? [] : removedImagesInput.value.split('|');
      currentValue.push(imgUrl);
      removedImagesInput.value = currentValue.join('|');
    } else {
      // Remove from selectedFiles array if it's a new image
      let fileName = previewItem.dataset.fileName;
      selectedFiles = selectedFiles.filter(file => file.name !== fileName);
      window.oumSelectedFiles = selectedFiles;
    }

    // Animate and remove the preview item
    previewItem.style.transition = "all 0.3s ease";
    previewItem.style.transform = "scale(0.8)";
    previewItem.style.opacity = "0";

    setTimeout(() => {
      previewItem.remove();
    }, 300);
  }

  function setupDragAndDrop(previewItem) {
    previewItem.setAttribute('draggable', 'true');

    previewItem.addEventListener('mousedown', function(e) {
      if (e.target.classList.contains('remove-image')) return;

      isDragging = true;
      this.classList.add('dragging');

      // Get element dimensions once at start
      let rect = this.getBoundingClientRect();
      this.style.width = rect.width + 'px';
      this.style.height = rect.height + 'px';

      // Store initial grid container for safety check
      this.initialContainer = this.closest('.oum-image-preview-grid');

      // Create placeholder immediately
      createPlaceholder(this);

      // Set up dragged element
      this.style.position = 'fixed';
      this.style.zIndex = '1000';
      this.style.opacity = '0.9';
      this.style.transform = 'scale(1.05) rotate(1deg)';
      this.style.pointerEvents = 'none';
      this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.15)';

      // Set initial position
      moveDraggedElement(this, e);

      document.body.style.cursor = 'grabbing';
    });

    previewItem.addEventListener('touchstart', handleTouchStart);
  }

  function createPlaceholder(element) {
    placeholder = document.createElement("div");
    placeholder.classList.add("image-preview-placeholder");
    placeholder.style.width = element.offsetWidth + "px";
    placeholder.style.height = element.offsetHeight + "px";
    placeholder.style.transition = "transform 0.2s ease";
    placeholder.style.border = "2px dashed #e02aaf";
    placeholder.style.borderRadius = "4px";
    placeholder.style.backgroundColor = "rgba(224, 42, 175, 0.05)";
    element.parentNode.insertBefore(placeholder, element);
  }

  function moveDraggedElement(draggable, e) {
    let rect = draggable.getBoundingClientRect();
    let centerOffsetX = rect.width / 2;
    let centerOffsetY = rect.height / 2;

    // Position element directly at cursor with center offset
    draggable.style.left = (e.clientX - centerOffsetX) + 'px';
    draggable.style.top = (e.clientY - centerOffsetY) + 'px';
  }

  function updatePlaceholderPosition(e) {
    let previewContainer = document.getElementById("cbn_location_images_preview");
    if (!previewContainer) return;

    let draggable = document.querySelector('.dragging');
    if (!draggable) return;

    let siblings = [...previewContainer.querySelectorAll(".image-preview-item:not(.dragging)")];

    // Find the closest sibling based on mouse position
    let closestSibling = siblings.reduce((closest, child) => {
      let rect = child.getBoundingClientRect();
      let centerX = rect.left + rect.width / 2;
      let offset = e.clientX - centerX;

      if (offset < 0 && (!closest.element || offset > closest.offset)) {
        return { offset: offset, element: child };
      }
      return closest;
    }, { offset: Number.NEGATIVE_INFINITY, element: null });

    if (closestSibling.element) {
      previewContainer.insertBefore(placeholder, closestSibling.element);
    } else {
      previewContainer.appendChild(placeholder);
    }
  }

  function handleDragMove(e) {
    if (!isDragging) return;

    let draggable = document.querySelector(".dragging");
    if (!draggable) return;

    // Update dragged element position
    moveDraggedElement(draggable, e);

    // Check if cursor is still within any grid container
    let gridContainer = document.getElementById("cbn_location_images_preview");
    if (!gridContainer) return;

    let gridRect = gridContainer.getBoundingClientRect();
    let isWithinGrid = e.clientX >= gridRect.left - 50 &&
                        e.clientX <= gridRect.right + 50 &&
                        e.clientY >= gridRect.top - 50 &&
                        e.clientY <= gridRect.bottom + 50;

    // If cursor is outside grid boundaries, hide placeholder
    if (!isWithinGrid && placeholder) {
      placeholder.style.display = 'none';
    } else if (placeholder) {
      placeholder.style.display = 'block';
      updatePlaceholderPosition(e);
    }
  }

  function handleDragEnd() {
    let draggable = document.querySelector(".dragging");
    if (!draggable) return;

    // Reset cursor
    document.body.style.cursor = "";

    // Check if we're still within the grid
    let gridContainer = document.getElementById("cbn_location_images_preview");
    if (!gridContainer) {
      // If no grid found, return item to its initial position
      if (draggable.initialContainer) {
        draggable.initialContainer.appendChild(draggable);
      }
    } else {
      // Place draggable element at placeholder position if within grid
      if (placeholder && placeholder.style.display !== 'none') {
        draggable.style.transition = "none";
        placeholder.parentNode.insertBefore(draggable, placeholder);
      } else {
        // If placeholder is hidden (outside grid), append to end
        gridContainer.appendChild(draggable);
      }
    }

    // Remove placeholder
    if (placeholder) {
      placeholder.remove();
    }

    // Reset draggable element styles
    draggable.style.position = "";
    draggable.style.zIndex = "";
    draggable.style.top = "";
    draggable.style.left = "";
    draggable.style.width = "";
    draggable.style.height = "";
    draggable.style.transform = "";
    draggable.style.pointerEvents = "";
    draggable.style.boxShadow = "";
    draggable.classList.remove("dragging");

    isDragging = false;
  }

  function handleTouchStart(e) {
    let touch = e.touches[0];
    let mouseEvent = new MouseEvent("mousedown", {
      clientX: touch.clientX,
      clientY: touch.clientY
    });
    this.dispatchEvent(mouseEvent);
  }

  function handleDragOver(e) {
    e.preventDefault();
    e.stopPropagation();

    let draggable = document.querySelector(".dragging");
    if (!draggable) return;

    let afterElement = getDragAfterElement(this, e.clientX);
    if (afterElement == null) {
      this.appendChild(placeholder);
    } else {
      this.insertBefore(placeholder, afterElement);
    }
  }

  // Public interface
  return {
    init: function () {
      initializeAudioUpload();
    },
    initializeImageUpload: function(imageInput) {
      initializeImageUpload(imageInput);
    },
    getSelectedFiles: function () {
      return selectedFiles;
    },
    setupDragAndDrop: setupDragAndDrop,
    handleRemoveImage: handleRemoveImage,
    setExistingAudio: setExistingAudio
  };
})();

// Main initialization
window.addEventListener("load", function () {
  // Only proceed if we have a map element
  if (!document.getElementById(map_el)) {
    return;
  }

  // Restore the extended L object
  window.L = window.OUMLeaflet.L;

  // Initialize map and get instance
  let mapInstance = OUMMap.init(map_el);

  // Initialize markers
  let markersModule = OUMMarkers.init(mapInstance);

  // Add markers from the global cbn_all_locations
  if (
    typeof cbn_all_locations !== "undefined" &&
    Array.isArray(cbn_all_locations)
  ) {
    markersModule.addMarkers(cbn_all_locations);
  }

  // Initialize location form
  OUMFormMap.init(mapInstance);

  // Initialize form controller
  OUMFormController.init();

  // Initialize media handling
  OUMMedia.init();

  // Setup filter events
  let markerFilterInput = document.getElementById("cbn_filter_markers");
  if (markerFilterInput) {
    markerFilterInput.addEventListener("input", OUMMarkers.filterMarkers);
  }

  let categoryInputs = document.querySelectorAll(
    '.Compass .oum-filter-controls [name="type"]'
  );
  if (categoryInputs.length > 0) {
    categoryInputs.forEach((input) => {
      input.addEventListener("change", OUMMarkers.filterMarkers);
    });
  }

  // Execute custom JS from settings
  if (typeof custom_js !== "undefined" && custom_js.snippet) {
    try {
      // Wrap custom JS execution in a try-catch with proper element existence checks
      let wrappedJS = `
        try {
          if (typeof document !== 'undefined') {
            // Defer map2-related code execution
            if (${custom_js.snippet.includes("oumMap2")}) {
              // Create a MutationObserver to watch for the form map initialization
              let observer = new MutationObserver((mutations) => {
                if (window.oumMap2) {
                  // Execute the custom JS only when oumMap2 is available
                  try {
                    ${custom_js.snippet}
                  } catch (e) {
                    console.warn('Custom JS execution error (deferred):', e);
                  }
                  observer.disconnect();
                }
              });

              // Start observing the document for the form map to be added
              observer.observe(document.body, {
                childList: true,
                subtree: true
              });
            } else {
              // Execute non-map2 related code immediately
              ${custom_js.snippet}
            }
          }
        } catch (e) {
          console.warn('Custom JS execution error:', e);
        }
      `;
      Function(wrappedJS)();
    } catch (error) {
      console.warn("Error executing custom JS:", error);
    }
  }
});
