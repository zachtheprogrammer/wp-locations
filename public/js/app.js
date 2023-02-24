let map,
  markers = [];

//Get list of availabe states and update dropdown
const states = wp_locations_data.locations.reduce((acc, location) => {
  if (!acc.includes(location.LocationState)) {
    acc.push(location.LocationState);
  }
  return acc;
}, []);

//Sort states array and add to states dropdown
const stateSelect = document.getElementById("state_search");
states.sort().forEach((state) => {
  stateSelect.add(new Option(state, state));
});

//Populate Form Values
const URLParams = Object.fromEntries(
  new URLSearchParams(window.location.search)
);
document.getElementById("location_search").value = URLParams.location_search
  ? URLParams.location_search
  : "";
document.getElementById("state_search").value = URLParams.state_search
  ? URLParams.state_search
  : "";
document.getElementById("min_size_search").value = URLParams.min_size_search
  ? URLParams.min_size_search
  : "";
document.getElementById("max_size_search").value = URLParams.max_size_search
  ? URLParams.max_size_search
  : "";

console.log(URLParams);

// Initialize and load map
function initMap() {
  const mapProp = {
    center: new google.maps.LatLng(36.186745, -94.128815),
    zoom: 5,
    mapTypeId: "hybrid",
  };
  map = new google.maps.Map(document.getElementById("map"), mapProp);

  // Create LatLngBounds object.
  var latlngbounds = new google.maps.LatLngBounds();
  // Create an info window to share between markers.

  const infoWindow = new google.maps.InfoWindow();

  // Loop locations to add markers
  for (let i = 0; i < wp_locations_data.locations.length; i++) {
    const location = wp_locations_data.locations[i];

    const markerLatLng = new google.maps.LatLng(
      parseFloat(location.LocationLatitude),
      parseFloat(location.LocationLongitude)
    );

    const marker = new google.maps.Marker({
      position: markerLatLng,
      map,
      title: location.LocationAddress,
      optimized: false,
    });

    // Add a click listener for each marker and set up the info window.
    marker.addListener("click", () => {
      infoWindow.close();
      infoWindow.setContent(`
      <div class="row" id="marker_infoWindow_${i}">
        <div class="col-4">
            <img src="${location.LocationImage}" style="width:80%;">
            <span class="size">${location.LocationSize} Sq. Ft.</span>
        </div>
        <div class="col-8">
            <strong>${location.LocationName}</strong><br />
            <p class="address">
                ${location.LocationAddress}<br />
                ${location.LocationCity}, ${location.LocationState} ${location.LocationPostal}<br />
                <a href="${location.LocationPermalink}" class="btn btn-outline-success btn-sm">View</a>
            </p>
        </div>
    </div>
      `);
      infoWindow.open(marker.getMap(), marker);
    });

    // Save our markers in array
    markers.push(marker);

    document.getElementById(
      "locations_list"
    ).innerHTML += `<div class="location row" id="marker_link_${i}">
        <div class="col-4">
            <img src="${location.LocationImage}" style="width:80%;">
            <span class="size">${location.LocationSize} Sq. Ft.</span>
        </div>
        <div class="col-8">
            <a href="#" onClick="new google.maps.event.trigger( markers[${i}], 'click' );">${location.LocationName}</a><br />
            <p class="address">
                ${location.LocationAddress}<br />
                ${location.LocationCity}, ${location.LocationState} ${location.LocationPostal}<br />
                <a href="${location.LocationPermalink}" class="btn btn-outline-success">View Property</a>
            </p>
        </div>
    </div>
    `;

    //Extend each marker's position in LatLngBounds object.
    latlngbounds.extend(marker.position);
  }
  // Ran when the map becomes idle after panning or zooming.
  map.addListener("idle", () => {
    Array.from(document.querySelectorAll(".location")).forEach((el) =>
      el.classList.remove("show")
    );
    const bounds = map.getBounds();
    let count = 0;
    for (let i = 0; i < markers.length; i++) {
      // const marker = markers[i];
      if (bounds.contains(markers[i].getPosition()) === true) {
        document.getElementById("marker_link_" + i).classList.add("show");
        count++;
      }
    }
    document.getElementById("results").innerHTML = count + " Results";
  });

  //Get the boundaries of the map.
  var bounds = new google.maps.LatLngBounds();

  // adjust map to center amongst bounds
  map.setCenter(latlngbounds.getCenter());
  map.fitBounds(latlngbounds);
}

window.initMap = initMap;
