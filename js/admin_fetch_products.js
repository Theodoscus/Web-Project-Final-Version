const dropdown = document.querySelector('.dropdown');
  const selectedOption = document.querySelector('.selected-option');
  const searchInput = document.getElementById('search');
  const optionsList = document.querySelector('.options');
  const submitButton = document.getElementById('submit-btn');

  // Function to fetch options from the database and populate the dropdown
  function populateDropdown() {
    // Make an AJAX request to fetch data from the server
    // Replace 'fetch_options.php' with the appropriate server-side script to fetch data from the database.
    fetch('components/get_products.php')
      .then(response => response.json())
      .then(data => {
        optionsList.innerHTML = ''; // Clear existing options

        // Loop through the data and add options to the dropdown
        data.forEach(option => {
          const optionElement = document.createElement('li');
          optionElement.textContent = option.product_name;
          optionElement.addEventListener('click', () => {
            selectedOption.textContent = option.product_name;
            searchInput.value = option.product_name; // Update search field with selected option
            dropdown.style.display = 'none';
          });
          optionsList.appendChild(optionElement);
        });
      })
      .catch(error => console.error('Error fetching options:', error));
  }

  // Function to filter dropdown options based on search input
  function filterDropdown() {
    const searchValue = searchInput.value.toLowerCase();
    const options = optionsList.querySelectorAll('li');

    options.forEach(option => {
      const text = option.textContent.toLowerCase();
      option.style.display = text.includes(searchValue) ? 'block' : 'none';
    });

    dropdown.style.display = 'block';
  }

  // Event listener to handle search input
  searchInput.addEventListener('input', filterDropdown);

  // Call the function to populate the dropdown when the page loads
  populateDropdown();

  // Event listener to handle submit button click
  