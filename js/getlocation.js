
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

navigator.geolocation.getCurrentPosition(onSuccess, onError);

        function onSuccess(position) {
            const userLatitude = position.coords.latitude;
            const userLongitude = position.coords.longitude;

            // Function to calculate distance between two points using haversine formula
            

            // Function to enable/disable buttons based on distance
            function updateButtons(mapPoints) {
                const calculateButton = document.getElementById("calculateButton");

                
                   
                    const distance = calculateDistance(userLatitude, userLongitude, mapPoints[0], mapPoints[1]);
                    
                    if( distance<=1000){
                        


                    }else{
                        document.getElementById("quick_view_1").disabled = true;
                        document.getElementById("quick_view_2").disabled = true;
                        document.getElementById("quick_view_3").disabled = true;
                        document.getElementById("quick_view_4").disabled = true;
                    }
                    
               

                
            }

            // AJAX request to retrieve map points from the database
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        const mapPoints = JSON.parse(xhr.responseText);
                        updateButtons(mapPoints);
                        
                        
                    } else {
                        console.error("Failed to retrieve map points.");
                        document.getElementById("quick_view_1").disabled = true;
                        document.getElementById("quick_view_2").disabled = true;
                        document.getElementById("quick_view_3").disabled = true;
                        document.getElementById("quick_view_4").disabled = true;
                    }
                }
            };

            xhr.open("GET", "components/set_location.php", true);
            xhr.send();
        }

        function onError(error) {
            console.error("Error getting user location:", error.message);
            document.getElementById("quick_view_1").disabled = true;
            document.getElementById("quick_view_2").disabled = true;
            document.getElementById("quick_view_3").disabled = true;
            document.getElementById("quick_view_4").disabled = true;
        }

        function update_stock(stock){
            if( stock=='false'){
                document.getElementById("quick_view_1").disabled = false;
                document.getElementById("quick_view_2").disabled = false;
                document.getElementById("quick_view_3").disabled = false;
                document.getElementById("quick_view_4").disabled = true;



            }else{
                document.getElementById("quick_view_1").disabled = true;
                document.getElementById("quick_view_2").disabled = true;
                document.getElementById("quick_view_3").disabled = true;
                document.getElementById("quick_view_4").disabled = false;
            }
        }

        const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        const stock = JSON.parse(xhr.responseText);
                        update_stock(stock);
                        
                        
                    } else {
                        console.error("Failed to retrieve stock.");
                        document.getElementById("quick_view_1").disabled = true;
                        document.getElementById("quick_view_2").disabled = true;
                        document.getElementById("quick_view_3").disabled = true;
                        document.getElementById("quick_view_4").disabled = true;
                    }
                }
            };

            xhr.open("GET", "components/get_stock.php", true);
            xhr.send();
        

        const inStockButton = document.getElementById('quick_view_4');
        const outOfStockButton = document.getElementById('quick_view_1');
        

        
        function inStockButtonClickHandler(event) {
            const dataToSend={
                key1: 'false',
            };
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'components/send_stock.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                // Request was successful, handle the response from PHP here
                console.log(xhr.responseText);
                } else {
                // Request failed
                console.error('Request failed');
                }
            }
            };

// Convert JavaScript object to a URL-encoded string
            const urlEncodedDataPairs = [];
            for (const key1 in dataToSend) {
            urlEncodedDataPairs.push(encodeURIComponent(key1) + '=' + encodeURIComponent(dataToSend[key1]));
            }
            const urlEncodedData = urlEncodedDataPairs.join('&');

            xhr.send(urlEncodedData);
            
            
              
            
          }
        
        function outOfStockButtonClickHandler(event) {
            const dataToSend={
                key1:'true',
            };
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'components/send_stock.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                // Request was successful, handle the response from PHP here
                console.log(xhr.responseText);
                } else {
                // Request failed
                console.error('Request failed');
                }
            }
            };

// Convert JavaScript object to a URL-encoded string
            const urlEncodedDataPairs = [];
            for (const key1 in dataToSend) {
            urlEncodedDataPairs.push(encodeURIComponent(key1) + '=' + encodeURIComponent(dataToSend[key1]));
            }
            const urlEncodedData = urlEncodedDataPairs.join('&');

            xhr.send(urlEncodedData);
            
            
              
            
          }

        inStockButton.addEventListener('click', inStockButtonClickHandler);
        outOfStockButton.addEventListener('click', outOfStockButtonClickHandler);

