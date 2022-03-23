if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition((position) => {
        let localCoord = position.coords;
        let objLocalCoord = {
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
    });
} else {
    console.error("Geolocation is not supported");
}
