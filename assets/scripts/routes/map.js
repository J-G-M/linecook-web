/* globals nf, google */

export default {
	init() {


		/**
		 * Generate Markers and bounds from user list
		 */
		function generate_markers($data) {

			var markers = [];

			console.log($data);

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
					data: $map.data('type'),
				},
				type: 'post',
				dataType: 'json',
				success: function(data) {

					var bounds = new google.maps.LatLngBounds();
					var markers = generate_markers(data);
					var icon = nf.assets + 'icons/map-pin.png';

					for ( var i = 0; i < markers.length; i++) {

						var loc    = markers[i];
						var marker = new google.maps.Marker({
							map: $map,
							icon: icon,
							position: new google.maps.LatLng(loc.lat, loc.lng),
						});

						$map.fitBounds(bounds);
					}

					console.log( data );
					console.log( marker );
					// console.log( XMLHttpRequest );
				},
				error: function(MLHttpRequest) {

					console.log(MLHttpRequest);
				},
			});
		}




		/**
		 * Run Google maps
		 */
		function initMap() {

			var map = new google.maps.Map(document.getElementById('map'), {
				zoom: 8,
				center: new google.maps.LatLng(51.5074, 0.1278),
				scrollwheel: false,
				mapTypeControl: false,
				streetViewControl: false,
				rotateControl: false,
				fullscreenControl: false,
			});

			load_map_data(map);
		}

		function runMap() {

			console.log($('#map'));

			if ($('#map').length) {
				google.maps.event.addDomListener(window, 'load', initMap);
			}
		}
	},
};