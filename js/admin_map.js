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

fetch("../components/get_supermarkets.php")
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
    var sanitizers = data[i].Sanitizers;
    var baby = data[i].Baby;
    var pets = data[i].Pets;
    var cleaning = data[i].Cleaning
    var drinks = data[i].Drinks;
    var health = data[i].Health;
    var care = data[i].Care;
    var food = data[i].Food;
    let button1 = "../admin/admin_view_sm_offers.php?sid=";
    let button2 = "../admin_create_offer.php?sid=";
    if (has_offers>=1){
      marker = new L.marker(location,{icon: greenIcon}).addTo(map);
      if (circle.getBounds().contains(marker.getLatLng())){
        
        const markerLayer = marker.bindPopup("Όνομα supermarket: " + name + "<br> Διεύθυνση supermarket: " + address + "<br> Προσφορές ανά γενική κατηγορία: <br> Αντισηπτικά ("+sanitizers+")<br> Βρεφικά Είδη ("+baby+")<br> Για κατοικίδια ("+pets+")<br> Καθαριότητα ("+cleaning+")<br> Ποτά-Αναψυκτικά ("+drinks+")<br> Προστασία Υγείας ("+health+")<br> Προσωπική Φροντίδα ("+care+")<br> Τρόφιμα ("+food+")<br><a href="+button1+id+"> Δείτε τις προσφορές!</a> <br> <a href="+button2+id+">Δημιουργήστε μια καινούργια προσφορά!</a>");
        markerLayers.push(markerLayer);
        
      } else {
        
        const markerLayer = marker.bindPopup("Όνομα supermarket: " + name + "<br> Διεύθυνση supermarket: " + address + "<br> Προσφορές ανά γενική κατηγορία: <br> Αντισηπτικά ("+sanitizers+")<br> Βρεφικά Είδη ("+baby+")<br> Για κατοικίδια ("+pets+")<br> Καθαριότητα ("+cleaning+")<br> Ποτά-Αναψυκτικά ("+drinks+")<br> Προστασία Υγείας ("+health+")<br> Προσωπική Φροντίδα ("+care+")<br> Τρόφιμα ("+food+")<br><a href="+button1+id+"> Δείτε τις προσφορές!</a>");
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

  const sanitizersButton = document.getElementById('sanitizers');
  const babyButton = document.getElementById('baby');
  const petsButton = document.getElementById('pets');
  const cleanButton = document.getElementById('clean');
  const drinksButton = document.getElementById('drinks');
  const healthButton = document.getElementById('health');
  const careButton = document.getElementById('care');
  const foodButton = document.getElementById('food');
  const clearFilters = document.getElementById('clear_filters');
  
  
  function sanitizersClickHandler(event) {
    event.preventDefault();
    
    markerLayers.forEach((markerLayer) => {
      const regex = /αντισηπτικά \((\d+)\)/i;
      const markerName = markerLayer.getPopup().getContent().toLowerCase();
      const match = markerName.match(regex);
      
      
      if (match) {
        const number = parseInt(match[1]);
        console.log(number);
        if (number === 0) {
          map.removeLayer(markerLayer);
        } else {
          markerLayer.addTo(map);
        }
      } else {
        map.removeLayer(markerLayer);
      }
    });
    
  }

  function babyClickHandler(event) {
    event.preventDefault();
    
    markerLayers.forEach((markerLayer) => {
      const regex = /βρεφικά είδη \((\d+)\)/i;
      const markerName = markerLayer.getPopup().getContent().toLowerCase();
      const match = markerName.match(regex);
      
      
      if (match) {
        const number = parseInt(match[1]);
        console.log(number);
        if (number === 0) {
          map.removeLayer(markerLayer);
        } else {
          markerLayer.addTo(map);
        }
      } else {
        map.removeLayer(markerLayer);
      }
    });
    
  }

  function petsClickHandler(event) {
    event.preventDefault();
    
    markerLayers.forEach((markerLayer) => {
      const regex = /για κατοικίδια \((\d+)\)/i;
      const markerName = markerLayer.getPopup().getContent().toLowerCase();
      const match = markerName.match(regex);
      
      
      if (match) {
        const number = parseInt(match[1]);
        console.log(number);
        if (number === 0) {
          map.removeLayer(markerLayer);
        } else {
          markerLayer.addTo(map);
        }
      } else {
        map.removeLayer(markerLayer);
      }
    });
    
  }

  function cleanClickHandler(event) {
    event.preventDefault();
    
    markerLayers.forEach((markerLayer) => {
      const regex = /καθαριότητα \((\d+)\)/i;
      const markerName = markerLayer.getPopup().getContent().toLowerCase();
      const match = markerName.match(regex);
      
      
      if (match) {
        const number = parseInt(match[1]);
        console.log(number);
        if (number === 0) {
          map.removeLayer(markerLayer);
        } else {
          markerLayer.addTo(map);
        }
      } else {
        map.removeLayer(markerLayer);
      }
    });
    
  }

  function drinksClickHandler(event) {
    event.preventDefault();
    
    markerLayers.forEach((markerLayer) => {
      const regex = /ποτά-αναψυκτικά \((\d+)\)/i;
      const markerName = markerLayer.getPopup().getContent().toLowerCase();
      const match = markerName.match(regex);
      
      
      if (match) {
        const number = parseInt(match[1]);
        console.log(number);
        if (number === 0) {
          map.removeLayer(markerLayer);
        } else {
          markerLayer.addTo(map);
        }
      } else {
        map.removeLayer(markerLayer);
      }
    });
    
  }

  function healthClickHandler(event) {
    event.preventDefault();
    
    markerLayers.forEach((markerLayer) => {
      const regex = /προστασία υγείας \((\d+)\)/i;
      const markerName = markerLayer.getPopup().getContent().toLowerCase();
      const match = markerName.match(regex);
      
      
      if (match) {
        const number = parseInt(match[1]);
        console.log(number);
        if (number === 0) {
          map.removeLayer(markerLayer);
        } else {
          markerLayer.addTo(map);
        }
      } else {
        map.removeLayer(markerLayer);
      }
    });
    
  }

  function careClickHandler(event) {
    event.preventDefault();
    
    markerLayers.forEach((markerLayer) => {
      const regex = /προσωπική φροντίδα \((\d+)\)/i;
      const markerName = markerLayer.getPopup().getContent().toLowerCase();
      const match = markerName.match(regex);
      
      
      if (match) {
        const number = parseInt(match[1]);
        console.log(number);
        if (number === 0) {
          map.removeLayer(markerLayer);
        } else {
          markerLayer.addTo(map);
        }
      } else {
        map.removeLayer(markerLayer);
      }
    });
    
  }

  function foodClickHandler(event) {
    event.preventDefault();
    
    markerLayers.forEach((markerLayer) => {
      const regex = /τρόφιμα \((\d+)\)/i;
      const markerName = markerLayer.getPopup().getContent().toLowerCase();
      const match = markerName.match(regex);
      
      
      if (match) {
        const number = parseInt(match[1]);
        console.log(number);
        if (number === 0) {
          map.removeLayer(markerLayer);
        } else {
          markerLayer.addTo(map);
        }
      } else {
        map.removeLayer(markerLayer);
      }
    });
    
  }

  function clearFiltersClickHandler(event) {
    event.preventDefault();
    
    markerLayers.forEach((markerLayer) => {
      markerLayer.addTo(map);
    });
    
  }

  sanitizersButton.addEventListener('click', sanitizersClickHandler);
  babyButton.addEventListener('click', babyClickHandler);
  petsButton.addEventListener('click', petsClickHandler);
  cleanButton.addEventListener('click', cleanClickHandler);
  drinksButton.addEventListener('click', drinksClickHandler);
  healthButton.addEventListener('click', healthClickHandler);
  careButton.addEventListener('click', careClickHandler);
  foodButton.addEventListener('click', foodClickHandler);
  clearFilters.addEventListener('click', clearFiltersClickHandler);
  




    
})
.catch((error) => {
   
   console.log(error);
});










      
  



