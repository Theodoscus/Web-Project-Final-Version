// Function to handle the delete offer button click for the admin_map
$(document).ready(function () {
    $(".delete-offer-warning-btn").click(function () {
        var offerId = $(this).data("offer-id");
        var confirmation = confirm("Are you sure you want to delete this offer?");

        if (confirmation) {
            $.ajax({
                type: "POST",
                url: "quick_view.php",
                data: { delete_offer_id: offerId },
                dataType: "json", // Expect JSON response
                success: function (response) {
                    if (response.status === "success") {
                        var supermarketId = response.supermarket_id;
                        // Redirect to the appropriate URL with the manipulated query parameter
                        // window.location.href = "view_sm_offers.php?sid=" + supermarketId;
                        window.location.href = "admin_map.php";
                    } else {
                        alert("An error occurred while deleting the offer.");
                    }
                },
                error: function () {
                    alert("An error occurred while processing your request.");
                }
            });
        }
    });
});


