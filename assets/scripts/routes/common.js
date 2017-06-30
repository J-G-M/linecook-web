/* globals nf, google */

import './functions-common';

export default {
	init() {

		// Toggle buttons
		$('[data-toggle]').toggler();

		$('[data-modal]').click( function( event) {
			if(event.preventDefault) { event.preventDefault(); }

			var id = $(this).data('modal');
			$('#' + id).toggleClass('active');
		});
	},
	finalize() {

		/**
		 * Generate Markers and bounds from user list
		 */
		function generate_markers($data) {

			var markers = [];

			$.each($data.locations, function() {

				for (var i = $data.locations.length - 1; i >= 0; i--) {

					var loc = $data.locations[i];

					var marker = {
						address: loc.address,
						lat: loc.lat,
						lng: loc.lng,
					};

					markers.push(marker);
				}
			});

			return markers;
		}


		function load_map_data($map) {


			$.ajax({
				url: nf.ajax_url,
				data: {
					action: 'do_get_locations',
					nonce: nf.nonce,
					data: $($map).data('type'),
				},
				type: 'post',
				dataType: 'json',
				success: function(data) {

					var bounds = new google.maps.LatLngBounds();
					var markers = generate_markers(data);
					var icon = nf.assets + 'icons/map-pin.png';

					for ( var i = 0; i < markers.length; i++) {

						var loc = markers[i];
						var pos = new google.maps.LatLng(loc.lat, loc.lng);

						var marker = new google.maps.Marker({
							map: $map,
							icon: icon,
							position: pos,
						});

						bounds.extend(pos);
						$map.fitBounds(bounds);
						$map.setZoom(12);
					}

					/*console.log( data );
					console.log( markers );
					// console.log( XMLHttpRequest );*/
				},
				error: function(MLHttpRequest) {

					// console.log(MLHttpRequest);
				},
			});
		}




		/**
		 * Run Google maps
		 */
		function initMap() {

			var style_arr = [
				{
					"featureType": "water",
					"elementType": "all",
					"stylers": [
						{
							"visibility": "simplified",
						},
						{
							"hue": "#e9ebed",
						},
						{
							"saturation": -78,
						},
						{
							"lightness": 67,
						},
					],
				},
				{
					"featureType": "landscape",
					"elementType": "all",
					"stylers": [
						{
							"visibility": "simplified",
						},
						{
							"hue": "#ffffff",
						},
						{
							"saturation": -100,
						},
						{
							"lightness": 100,
						},
					],
				},
				{
					"featureType": "road",
					"elementType": "geometry",
					"stylers": [
						{
							"visibility": "simplified",
						},
						{
							"hue": "#bbc0c4",
						},
						{
							"saturation": -93,
						},
						{
							"lightness": 31,
						},
					],
				},
				{
					"featureType": "poi",
					"elementType": "all",
					"stylers": [
						{
							"visibility": "off",
						},
						{
							"hue": "#ffffff",
						},
						{
							"saturation": -100,
						},
						{
							"lightness": 100,
						},
					],
				},
				{
					"featureType": "road.local",
					"elementType": "geometry",
					"stylers": [
						{
							"visibility": "simplified",
						},
						{
							"hue": "#e9ebed",
						},
						{
							"saturation": -90,
						},
						{
							"lightness": -8,
						},
					],
				},
				{
					"featureType": "transit",
					"elementType": "all",
					"stylers": [
						{
							"visibility": "on",
						},
						{
							"hue": "#e9ebed",
						},
						{
							"saturation": 10,
						},
						{
							"lightness": 69,
						},
					],
				},
				{
					"featureType": "administrative.locality",
					"elementType": "all",
					"stylers": [
						{
							"visibility": "on",
						},
						{
							"hue": "#2c2e33",
						},
						{
							"saturation": 7,
						},
						{
							"lightness": 19,
						},
					],
				},
				{
					"featureType": "road",
					"elementType": "labels",
					"stylers": [
						{
							"visibility": "on",
						},
						{
							"hue": "#bbc0c4",
						},
						{
							"saturation": -93,
						},
						{
							"lightness": 31,
						},
					],
				},
				{
					"featureType": "road.arterial",
					"elementType": "labels",
					"stylers": [
						{
							"visibility": "simplified",
						},
						{
							"hue": "#bbc0c4",
						},
						{
							"saturation": -93,
						},
						{
							"lightness": -2,
						},
					],
				},
			];

			var map = new google.maps.Map(document.getElementById('map'), {
				zoom: 8,
				center: new google.maps.LatLng(51.5074, 0.1278),
				scrollwheel: false,
				mapTypeControl: false,
				streetViewControl: false,
				rotateControl: false,
				fullscreenControl: false,
				styles: style_arr,
			});

			load_map_data(map);
		}

		function runMap() {

			if ($('#map').length) {
				google.maps.event.addDomListener(window, 'load', initMap);
			}
		}

		runMap();
	},
};