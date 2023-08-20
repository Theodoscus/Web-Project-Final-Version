    // Function to handle the delete offer button click for the admin_map

src="https://code.jquery.com/jquery-3.6.0.min.js"


$(document).ready(function() {
    $(".delete-offer-btn").click(function() {
        var offerId = $(this).data("offer-id");
        var confirmation = confirm("Are you sure you want to delete this offer?");

        if (confirmation) {
            $.ajax({
                type: "POST",
                url: "quick_view.php", // The same file you're in (you can change it if needed)
                data: { delete_offer_id: offerId },
                success: function(response) {
                    // Handle the response, e.g., show a success message or reload the page
                    alert("Offer deleted successfully");
                    window.location.reload();
                }
            });
        }
    });
});

