var map = L.map('map');
var greenIcon = new L.Icon({
  iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
  shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
  iconSize: [25, 41],
  iconAnchor: [12, 41],
  popupAnchor: [1, -34],
  shadowSize: [41, 41]
});
var redIcon = new L.Icon({
  iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
  shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
  iconSize: [25, 41],
  iconAnchor: [12, 41],
  popupAnchor: [1, -34],
  shadowSize: [41, 41]
});
// Get the tile layer from OpenStreetMaps
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 18, attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);



// Add the search bar control to the map


function onLocationFound(e) {
var radius = 1000;
var marker = new L.marker(e.latlng).addTo(map)
  .bindPopup("You are here!").openPopup();
circle = new L.circle(e.latlng, radius).addTo(map);
}

map.on('locationfound', onLocationFound);
map.locate({setView: true, watch: true, maxZoom: 16});

fetch("components/get_supermarkets.php")
.then((response) => {
  if(!response.ok){ 
      throw new Error("Something went wrong!");
  }

  return response.json(); 
})
.then((data) => {
  const markerLayers=[];
  for (var i = 0; i < data.length; i++)
  {
    var location = new L.LatLng(data[i].x_coord, data[i].y_coord);
    var has_offers = data[i].has_offers;
    var name = data[i].supermarket_name
    var id = data[i].supermarket_id;
    var address = data[i].supermarket_address;
    let button1 = "view_sm_offers.php?sid=";
    let button2 = "create_offer.php?sid=";
    if (has_offers>=1){
      marker = new L.marker(location,{icon: greenIcon}).addTo(map);
      if (circle.getBounds().contains(marker.getLatLng())){
        
        const markerLayer = marker.bindPopup("Όνομα supermarket: " + name + "<br> Διεύθυνση supermarket: " + address + "<br> <a href="+button1+id+"> Δείτε τις προσφορές!</a> <br> <a href="+button2+id+">Δημιουργήστε μια καινούργια προσφορά!</a>");
        markerLayers.push(markerLayer);
        
      } else {
        
        const markerLayer = marker.bindPopup("Όνομα supermarket: " + name + "<br> Διεύθυνση supermarket: " + address + "<br> <a href="+button1+id+"> Δείτε τις προσφορές!</a>");
        markerLayers.push(markerLayer);
      }
    }
    else if(has_offers==0) {
      marker = new L.marker(location,{icon: redIcon}).bindPopup("Όνομα supermarket: " + name).addTo(map);
      if (circle.getBounds().contains(marker.getLatLng())){
        
        const markerLayer = marker.bindPopup("Όνομα supermarket: " + name + "<br> Διεύθυνση supermarket: " + address + "<br> Δεν υπάρχουν διαθέσιμες προσφορές! <br> <a href="+button2+id+">Δημιουργήστε μια καινούργια προσφορά!</a>");
        markerLayers.push(markerLayer);
      } else {
        const markerLayer = marker.bindPopup("Όνομα supermarket: " + name + "<br> Διεύθυνση supermarket: " + address + "<br> Δεν υπάρχουν διαθέσιμες προσφορές!");
        markerLayers.push(markerLayer);
      }
      }

     
  }
  function filterMarkers(searchValue) {
    // Convert the search input to lowercase for case-insensitive filtering
    const searchTerm = searchValue.toLowerCase();
  
    markerLayers.forEach((markerLayer) => {
      const markerName = markerLayer.getPopup().getContent().toLowerCase();
      
     
      // Check if the marker name includes the search term
      if (markerName.includes(searchTerm)) {
        // Show the marker on the map
        markerLayer.addTo(map);
      } else {
        // Hide the marker on the map
        map.removeLayer(markerLayer);
      }
    });
  }
  
  // Add markers to the map
  
  
  // Add an event listener to the search input
  const searchInput = document.getElementById("searchInput");
  searchInput.addEventListener("input", (event) => {
    const searchValue = event.target.value;
    filterMarkers(searchValue);
  });



    
})
.catch((error) => {
   
   console.log(error);
});










      
  



