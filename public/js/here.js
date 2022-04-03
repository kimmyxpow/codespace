if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition((position) => {
        let localCoord = position.coords;
        objLocalCoord = {
            lat: localCoord.latitude,
            lng: localCoord.longitude,
        };

        let platform = new H.service.Platform({
            apikey: hereApiKey,
        });

        // Obtain the default map types from the platform object
        let defaultLayers = platform.createDefaultLayers();

        // Instantiate (and display) a map object:
        let map = new H.Map(
            document.getElementById("mapContainer"),
            defaultLayers.vector.normal.map,
            {
                zoom: 13,
                center: objLocalCoord,
                pixelRatio: window.devicePixelRatio || 1,
            }
        );

        window.addEventListener("resize", () => map.getViewPort().resize());

        let ui = H.ui.UI.createDefault(map, defaultLayers);
        let mapEvents = new H.mapevents.MapEvents(map);
        let behavior = new H.mapevents.Behavior(mapEvents);

        // Draggable Marker Function
        function addDragableMarker(map, behavior) {
            let inputLat = document.getElementById("lat");
            let inputLng = document.getElementById("lng");

            if (inputLat.value != "" && inputLng.value != "") {
                objLocalCoord = {
                    lat: inputLat.value,
                    lng: inputLng.value,
                };
            }

            let marker = new H.map.Marker(objLocalCoord, {
                volatility: true,
            });

            marker.draggable = true;
            map.addObject(marker);

            // disable the default draggability of the underlying map
            // and calculate the offset between mouse and target's position
            // when starting to drag a marker object:
            map.addEventListener(
                "dragstart",
                function (ev) {
                    let target = ev.target,
                        pointer = ev.currentPointer;
                    if (target instanceof H.map.Marker) {
                        let targetPosition = map.geoToScreen(
                            target.getGeometry()
                        );
                        target["offset"] = new H.math.Point(
                            pointer.viewportX - targetPosition.x,
                            pointer.viewportY - targetPosition.y
                        );
                        behavior.disable();
                    }
                },
                false
            );

            // Listen to the drag event and move the position of the marker
            // as necessary
            map.addEventListener(
                "drag",
                function (ev) {
                    let target = ev.target,
                        pointer = ev.currentPointer;
                    if (target instanceof H.map.Marker) {
                        target.setGeometry(
                            map.screenToGeo(
                                pointer.viewportX - target["offset"].x,
                                pointer.viewportY - target["offset"].y
                            )
                        );
                    }
                },
                false
            );

            // re-enable the default draggability of the underlying map
            // when dragging has completed
            map.addEventListener(
                "dragend",
                function (ev) {
                    let target = ev.target;
                    if (target instanceof H.map.Marker) {
                        behavior.enable();
                        let resultCoord = map.screenToGeo(
                            ev.currentPointer.viewportX,
                            ev.currentPointer.viewportY
                        );
                        // console.log(resultCoord)
                        inputLat.value = resultCoord.lat.toFixed(5);
                        inputLng.value = resultCoord.lng.toFixed(5);
                    }
                },
                false
            );
        }

        if (window.action == "submit") {
            addDragableMarker(map, behavior);
        }

        // Browse location codespace
        let spaces = [];
        const fetchSpaces = function (latitude, longitude, radius) {
            return new Promise(function (resolve, reject) {
                resolve(
                    fetch(
                        `/api/spaces?lat=${latitude}&lng=${longitude}&rad=${radius}`
                    )
                        .then((res) => res.json())
                        .then(function (data) {
                            data.forEach(function (value, index) {
                                let marker = new H.map.Marker({
                                    lat: value.latitude,
                                    lng: value.longitude,
                                });
                                spaces.push(marker);
                            });
                        })
                );
            });
        };

        function clearSpace() {
            map.removeObjects(spaces);
            spaces = [];
        }

        function init(latitude, longitude, radius) {
            clearSpace();
            fetchSpaces(latitude, longitude, radius).then(function () {
                map.addObjects(spaces);
            });
        }

        if (window.action == "browse") {
            map.addEventListener(
                "dragend",
                function (ev) {
                    let resultCoord = map.screenToGeo(
                        ev.currentPointer.viewportX,
                        ev.currentPointer.viewportY
                    );
                    init(resultCoord.lat, resultCoord.lng, 40);
                },
                false
            );

            init(objLocalCoord.lat, objLocalCoord.lng, 40);
        }

        // Route to space
        let urlParams = new URLSearchParams(window.location.search);

        function calculateRouteAtoB(platform) {
            let router = platform.getRoutingService(null, 8),
                routeRequestParam = {
                    routingMode: "fast",
                    transportMode: "car",
                    // representation: "display",
                    // routeattributes: "summary",
                    // maneuverattributes: "direction,action",
                    origin: urlParams.get("from"),
                    destination: urlParams.get("to"),
                    return: "polyline,summary",
                };

            router.calculateRoute(routeRequestParam, onSuccess, onError);
        }

        function onSuccess(result) {
            route = result.routes[0];

            addRouteShapeToMap(route);
            addSummaryToPanel(route.sections[0].summary);
        }

        function onError(error) {
            alert("Can't reach the remote server" + error);
        }

        function addRouteShapeToMap(route) {
            let routeShape = route.sections,
                startPoint,
                endPoint,
                polyline,
                routeline,
                svgStartMark,
                iconStart,
                startMarker,
                svgEndMark,
                iconEnd,
                endMarker;

            routeShape.forEach(function (point) {
                linestring = new H.geo.LineString.fromFlexiblePolyline(
                    point.polyline
                );
                startPoint = point.departure.place.location;
                endPoint = point.arrival.place.location;
            });

            polyline = new H.map.Polyline(linestring, {
                style: {
                    lineWidth: 5,
                    strokeColor: "rgba(0, 128, 255, 0.7)",
                    lineTailCap: "arrow-tail",
                    lineHeadCap: "arrow-head",
                },
            });

            routeline = new H.map.Polyline(linestring, {
                style: {
                    lineWidth: 5,
                    fillColor: "white",
                    strokeColor: "rgba(255, 255, 255, 1)",
                    lineDash: [0, 2],
                    lineTailCap: "arrow-tail",
                    lineHeadCap: "arrow-head",
                },
            });

            svgStartMark = `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 52 52" style="enable-background:new 0 0 52 52;" xml:space="preserve" width="512px" height="512px"><g><path d="M38.853,5.324L38.853,5.324c-7.098-7.098-18.607-7.098-25.706,0h0  C6.751,11.72,6.031,23.763,11.459,31L26,52l14.541-21C45.969,23.763,45.249,11.72,38.853,5.324z M26.177,24c-3.314,0-6-2.686-6-6  s2.686-6,6-6s6,2.686,6,6S29.491,24,26.177,24z" data-original="#1081E0" class="active-path" data-old_color="#1081E0" fill="#C12020"/></g> </svg>`;

            iconStart = new H.map.Icon(svgStartMark, {
                size: { h: 45, w: 45 },
            });

            startMarker = new H.map.Marker(
                {
                    lat: startPoint.lat,
                    lng: startPoint.lng,
                },
                { icon: iconStart }
            );

            svgEndMark = `<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 52 52" style="enable-background:new 0 0 52 52;" xml:space="preserve"> <path style="fill:#1081E0;" d="M38.853,5.324L38.853,5.324c-7.098-7.098-18.607-7.098-25.706,0h0 C6.751,11.72,6.031,23.763,11.459,31L26,52l14.541-21C45.969,23.763,45.249,11.72,38.853,5.324z M26.177,24c-3.314,0-6-2.686-6-6 s2.686-6,6-6s6,2.686,6,6S29.491,24,26.177,24z"/></svg>`;

            iconEnd = new H.map.Icon(svgEndMark, {
                size: { h: 45, w: 45 },
            });

            endMarker = new H.map.Marker(
                {
                    lat: endPoint.lat,
                    lng: endPoint.lng,
                },
                { icon: iconEnd }
            );

            // Add the polyline to the map
            map.addObjects([polyline, routeline, startMarker, endMarker]);

            // And zoom to its bounding rectangle
            map.getViewModel().setLookAtData({
                bounds: polyline.getBoundingBox(),
            });
        }

        function addSummaryToPanel(summary) {
            const sumDiv = document.getElementById("summary");
            const markup = `
                <ul>
                    <li>Total Distance: ${summary.length / 1000}Km</li>
                    <li>Travel Time: ${summary.duration.toMMSS()} (in current traffic)</li>
                </ul>
            `;
            sumDiv.innerHTML = markup;
        }

        if (window.action == "direction") {
            calculateRouteAtoB(platform);

            Number.prototype.toMMSS = function () {
                return (
                    Math.floor(this / 60) +
                    " minutes " +
                    (this % 60) +
                    " seconds."
                );
            };
        }
    });

    // Open url direction
    function openDirection(lat, lng, id) {
        window.open(
            `/space/${id}?from=${objLocalCoord.lat},${objLocalCoord.lng}&to=${lat},${lng}`,
            "_self"
        );
    }
} else {
    console.error("Geolocation is not supported");
}
