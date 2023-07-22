
const toRadians = (degrees, precision = 8) => { return parseFloat(((parseFloat(degrees) * Math. PI) / 180). toFixed(precision)); };

function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371e3; // Earth's radius in meters
    const φ1 = toRadians(lat1);
    const φ2 = toRadians(lat2);
    const Δφ = toRadians(lat2 - lat1);
    const Δλ = toRadians(lon2 - lon1);

    const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
              Math.cos(φ1) * Math.cos(φ2) *
              Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    return R * c;
}

if ("geolocation" in navigator) {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        // Success callback - the user's position is available
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;
  
        // Now you can use the latitude and longitude as needed
        fetch("components/get_supermarkets.php")
        .then((response) => {
            if(!response.ok){ 
            throw new Error("Something went wrong!");
        }

            return response.json(); 
        })
        .then((data) => {
        for (var i = 0; i < data.length; i++)
        {
            var x_coord = data[i].x_coord;
            var y_coord = data[i].y_coord;
        
        
        distance = calculateDistance(x_coord, y_coord, latitude, longitude);
        if (distance<1000){
            $.ajax({
                type: "GET",
                url: "quick_view.php",
                data: {key1: x_coord, key2: y_coord},
                success: function(response) {
                  // Process the response from the PHP script
                  console.log("Response from server: ", response);
                },
                error: function(xhr, status, error) {
                  // Handle errors here
                  console.error("Error sending data: ", error);
                }
              });
        } else{
        
        }
        }
        })
        },
        (error) => {
            // Error callback - handle errors here
            console.error("Error getting location: ", error.message);
        }
        );
    }