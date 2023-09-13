document.addEventListener("DOMContentLoaded", function () {
    const cancelSearchButtonStores = document.getElementById("cancelSearchStores");

    cancelSearchButtonStores.addEventListener("click", function () {
        window.location.href = "stores.php";
    });
});
