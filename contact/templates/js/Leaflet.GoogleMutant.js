// Based on https://github.com/shramov/leaflet-plugins
// GridLayer like https://avinmathew.com/leaflet-and-google-maps/ , but using MutationObserver instead of jQuery


// 🍂class GridLayer.GoogleMutant
// 🍂extends GridLayer
L.GridLayer.GoogleMutant = L.GridLayer.extend({
	includes: L.Mixin.Events,

	options: {
		minZoom: 0,
		maxZoom: 18,
		tileSize: 256,
		subdomains: 'abc',
		errorTileUrl: '',
		attribution: '',	// The mutant container will add its own attribution anyways.
		opacity: 1,
		continuousWorld: false,
		noWrap: false,
		// 🍂option type: String = 'roadmap'
		// Google's map type. Valid values are 'roadmap', 'satellite' or 'terrain'. 'hybrid' is not really supported.
		type: 'terrain',
		maxNativeZoom: 21
	},

	initialize: function (options) {
		L.GridLayer.prototype.initialize.call(this, options);

		this._ready = !!window.google && !!window.google.maps && !!window.google.maps.Map;

// 		L.Util.setOptions(this, options);

		this._GAPIPromise = this._ready ? Promise.resolve(window.google) : new Promise(function (resolve, reject) {
			var checkCounter = 0;
			var intervalId = null;
			intervalId = setInterval(function () {
				if (checkCounter >= 10) {
					clearInterval(intervalId);
					return reject(new Error('window.google not found after 10 attempts'));
				}
				if (!!window.google && !!window.google.maps && !!window.google.maps.Map) {
					clearInterval(intervalId);
					return resolve(window.google);
				}
				checkCounter++;
			}, 500);
		});

		// Couple data structures indexed by tile key
		this._tileCallbacks = {};	// Callbacks for promises for tiles that are expected
		this._freshTiles = {};	// Tiles from the mutant which haven't been requested yet

		this._imagesPerTile = (this.options.type === 'hybrid') ? 2 : 1;
		this.createTile = (this.options.type === 'hybrid') ? this._createMultiTile : this._createSingleTile;
	},

	onAdd: function (map) {
		L.GridLayer.prototype.onAdd.call(this, map)
		this._initMutantContainer();

		this._GAPIPromise.then(function () {
			this._ready = true;
			this._map = map;

			this._initMutant();

			map.on('viewreset', this._reset, this);
			map.on('move', this._update, this);
			map.on('zoomend', this._handleZoomAnim, this);
			map.on('resize', this._resize, this);

			//20px instead of 1em to avoid a slight overlap with google's attribution
			map._controlCorners.bottomright.style.marginBottom = '20px';

			this._reset();
			this._update();
		}.bind(this));
	},

	onRemove: function (map) {
		L.GridLayer.prototype.onRemove.call(this, map);
		map._container.removeChild(this._mutantContainer);
		this._mutantContainer = undefined;

		map.off('viewreset', this._reset, this);
		map.off('move', this._update, this);
		map.off('zoomend', this._handleZoomAnim, this);
		map.off('resize', this._resize, this);

		map._controlCorners.bottomright.style.marginBottom = '0em';
	},

	getAttribution: function () {
		return this.options.attribution;
	},

	setOpacity: function (opacity) {
		this.options.opacity = opacity;
		if (opacity < 1) {
			L.DomUtil.setOpacity(this._mutantContainer, opacity);
		}
	},

	setElementSize: function (e, size) {
		e.style.width = size.x + 'px';
		e.style.height = size.y + 'px';
	},

	_initMutantContainer: function () {
		if (!this._mutantContainer) {
			this._mutantContainer = L.DomUtil.create('div', 'leaflet-google-mutant leaflet-top leaflet-left');
			this._mutantContainer.id = '_MutantContainer_' + L.Util.stamp(this._mutantContainer);
// 			this._mutantContainer.style.zIndex = 'auto';
			this._mutantContainer.style.pointerEvents = 'none';

			this._map.getContainer().appendChild(this._mutantContainer);
		}

		this.setOpacity(this.options.opacity);
		this.setElementSize(this._mutantContainer, this._map.getSize());

		this._attachObserver(this._mutantContainer);
	},

	_initMutant: function () {
		if (!this._ready || !this._mutantContainer) return;
		this._mutantCenter = new google.maps.LatLng(0, 0);

		var map = new google.maps.Map(this._mutantContainer, {
			center: this._mutantCenter,
			zoom: 0,
			tilt: 0,
			mapTypeId: this.options.type,
			disableDefaultUI: true,
			keyboardShortcuts: false,
			draggable: false,
			disableDoubleClickZoom: true,
			scrollwheel: false,
			streetViewControl: false,
			styles: this.options.styles || {},
			backgroundColor: 'transparent'
		});

		this._mutant = map;

		// 🍂event spawned
		// Fired when the mutant has been created.
		this.fire('spawned', {mapObject: map});
	},

	_attachObserver: function _attachObserver(node) {
// 		console.log('Gonna observe', node);

		var observer = new MutationObserver(this._onMutations.bind(this));

		// pass in the target node, as well as the observer options
		observer.observe(this._mutantContainer, { childList: true, subtree: true });
	},

	_onMutations: function _onMutations(mutations){
		for (var i = 0; i < mutations.length; ++i) {
			var mutation = mutations[i];
			for (var j = 0; j < mutation.addedNodes.length; ++j) {
				var node = mutation.addedNodes[j];

				if (node instanceof HTMLImageElement) {
					this._onMutatedImage(node);
				} else if (node instanceof HTMLElement){
					Array.prototype.forEach.call(node.querySelectorAll('img'), this._onMutatedImage.bind(this));
				}
			};
		};
	},

	// Only images which 'src' attrib match this will be considered for moving around.
	// Looks like some kind of string-based protobuf, maybe??
	// Only the roads (and terrain, and vector-based stuff) match this pattern
	_roadRegexp: /!1i(\d+)!2i(\d+)!3i(\d+)!/,

	// On the other hand, raster imagery matches this other pattern
	_satRegexp: /x=(\d+)&y=(\d+)&z=(\d+)/,

	// On small viewports, when zooming in/out, a static image is requested
	// This will not be moved around, just removed from the DOM.
	_staticRegExp: /StaticMapService\.GetMapImage/,

	_onMutatedImage: function _onMutatedImage(imgNode) {
// 		if (imgNode.src) {
// 			console.log('caught mutated image: ', imgNode.src);
// 		}

		var coords;
		var match = imgNode.src.match(this._roadRegexp);
		var sublayer;

		if (match) {
			coords = {
				z: match[1],
				x: match[2],
				y: match[3]
			}
			if (this._imagesPerTile > 1) { imgNode.style.zIndex = 1; }
			sublayer = 1;
		} else {
			match = imgNode.src.match(this._satRegexp);
			if (match) {
				coords = {
					x: match[1],
					y: match[2],
					z: match[3]
				}
			}
// 			imgNode.style.zIndex = 0;
			sublayer = 0;
		}

		if (coords) {
			var key = this._tileCoordsToKey(coords)
			if (this._imagesPerTile > 1) { key += '/' + sublayer; }
			if (key in this._tileCallbacks && this._tileCallbacks[key]) {
// console.log('Fullfilling callback ', key);
				this._tileCallbacks[key].pop()(imgNode);
				if (!this._tileCallbacks[key].length) { delete this._tileCallbacks[key]; }
			} else {
// console.log('Caching for later', key);
				var parent = imgNode.parentNode;
				if (parent) {
					parent.removeChild(imgNode);
					parent.removeChild = L.Util.falseFn;
// 					imgNode.parentNode.replaceChild(L.DomUtil.create('img'), imgNode);
				}
				if (key in this._freshTiles) {
					this._freshTiles[key].push(imgNode);
				} else {
					this._freshTiles[key] = [imgNode];
				}
			}
		} else if (imgNode.src.match(this._staticRegExp)) {
			var parent = imgNode.parentNode;
			if (parent) {
				// Remove the image, but don't store it anywhere.
				// Image needs to be replaced instead of removed, as the container
				// seems to be reused.
				imgNode.parentNode.replaceChild(L.DomUtil.create('img'), imgNode);
			}
		}
	},

	// This will be used as this.createTile for 'roadmap', 'sat', 'terrain'
	_createSingleTile: function createTile(coords, done) {
		var key = this._tileCoordsToKey(coords);
// console.log('Need:', key);

		if (key in this._freshTiles) {
			var tile = this._freshTiles[key].pop();
			if (!this._freshTiles[key].length) { delete this._freshTiles[key]; }
			L.Util.requestAnimFrame(done);
// 			console.log('Got ', key, ' from _freshTiles');
			return tile;
		} else {
			var tileContainer = L.DomUtil.create('div');
			this._tileCallbacks[key] = this._tileCallbacks[key] || [];
			this._tileCallbacks[key].push( (function (c, k) {
				return function(imgNode) {
					var parent = imgNode.parentNode;
					if (parent) {
						parent.removeChild(imgNode);
						parent.removeChild = L.Util.falseFn;
// 						imgNode.parentNode.replaceChild(L.DomUtil.create('img'), imgNode);
					}
					c.appendChild(imgNode);
					done();
// 					console.log('Sent ', key, ' to _tileCallbacks');
				}.bind(this)
			}.bind(this))(tileContainer, key) );

			return tileContainer;
		}
	},

	// This will be used as this.createTile for 'hybrid'
	_createMultiTile: function createTile(coords, done) {
		var key = this._tileCoordsToKey(coords);

		var tileContainer = L.DomUtil.create('div');
		tileContainer.dataset.pending = this._imagesPerTile;

		for (var i = 0; i < this._imagesPerTile; i++) {
			var key2 = key + '/' + i;
			if (key2 in this._freshTiles) {
				tileContainer.appendChild(this._freshTiles[key2].pop());
				if (!this._freshTiles[key2].length) { delete this._freshTiles[key2]; }
				tileContainer.dataset.pending--;
// 				console.log('Got ', key2, ' from _freshTiles');
			} else {
				this._tileCallbacks[key2] = this._tileCallbacks[key2] || [];
				this._tileCallbacks[key2].push( (function (c, k2) {
					return function(imgNode) {
						var parent = imgNode.parentNode;
						if (parent) {
							parent.removeChild(imgNode);
							parent.removeChild = L.Util.falseFn;
// 							imgNode.parentNode.replaceChild(L.DomUtil.create('img'), imgNode);
						}
						c.appendChild(imgNode);
						c.dataset.pending--;
						if (!parseInt(c.dataset.pending)) { done(); }
// 						console.log('Sent ', k2, ' to _tileCallbacks, still ', c.dataset.pending, ' images to go');
					}.bind(this)
				}.bind(this))(tileContainer, key2) );
			}
		}

		if (!parseInt(tileContainer.dataset.pending)) {
			L.Util.requestAnimFrame(done);
		}
		return tileContainer;
	},

	_checkZoomLevels: function () {
		//setting the zoom level on the Google map may result in a different zoom level than the one requested
		//(it won't go beyond the level for which they have data).
		// verify and make sure the zoom levels on both Leaflet and Google maps are consistent
		if ((this._map.getZoom() !== undefined) && (this._mutant.getZoom() !== this._map.getZoom())) {
			//zoom levels are out of sync. Set the leaflet zoom level to match the google one
			this._map.setZoom(this._mutant.getZoom());
		}
	},

	_reset: function () {
		this._initContainer();
	},

	_update: function () {
		L.GridLayer.prototype._update.call(this);
		if (!this._mutant) return;

		var center = this._map.getCenter();
		var _center = new google.maps.LatLng(center.lat, center.lng);

		this._mutant.setCenter(_center);
		var zoom = this._map.getZoom();
		if (zoom !== undefined) {
			this._mutant.setZoom(Math.round(this._map.getZoom()));
		}
	},

	_resize: function () {
		var size = this._map.getSize();
		if (this._mutantContainer.style.width === size.x &&
			this._mutantContainer.style.height === size.y)
			return;
		this.setElementSize(this._mutantContainer, size);
		if (!this._mutant) return;
		google.maps.event.trigger(this._mutant, 'resize');
	},

	_handleZoomAnim: function () {
		var center = this._map.getCenter();
		var _center = new google.maps.LatLng(center.lat, center.lng);

		this._mutant.setCenter(_center);
		this._mutant.setZoom(Math.round(this._map.getZoom()));
	}
});


// 🍂factory gridLayer.googleMutant(options)
// Returns a new `GridLayer.GoogleMutant` given its options
L.gridLayer.googleMutant = function(options) {
	return new L.GridLayer.GoogleMutant(options);
};
