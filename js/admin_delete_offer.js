    // Function to handle the delete offer button click for the admin_map
    document.addEventListener("DOMContentLoaded", function () {
        var deleteBtn = document.getElementById("deleteOfferBtn");
  
        deleteBtn.addEventListener("click", function () {
            var confirmDelete = confirm("Are you sure you want to delete this offer?");
            
            if (confirmDelete) {
                // Get the offer ID using data attributes
                var offerId = deleteBtn.getAttribute("data-offer-id");
  
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "delete_offer.php");
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            // Handle success (if needed)
                            window.location.href = "http://localhost/Web-Project-Final-Version/admin/view_sm_offers.php";
                        } else {
                            // Handle error (if needed)
                            console.error("Error:", xhr.statusText);
                        }
                    }
                };
                xhr.send("offer_id=" + encodeURIComponent(offerId));
            }
        });
    });