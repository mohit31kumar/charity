document.addEventListener("DOMContentLoaded", () => {
  // Select necessary DOM elements
  const foodDetailsContainer = document.getElementById("food-details-container");
  const addFoodButton = document.getElementById("add-food");
  const foodTableBody = document.querySelector("#food-table tbody");
  const donationForm = document.getElementById("donation-form");
  const pickupPointInput = document.getElementById("pickup-point");
  const chooseFromMapButton = document.getElementById("choose-from-map");
  const mapPopup = document.getElementById("map-popup");
  const closeMapPopupButton = document.getElementById("close-map-popup");

  // Array to hold added food items
  const foodItems = [];

  // Initialize Google Maps variables
  let map, marker;

  // Event listener for adding food items
  addFoodButton.addEventListener("click", () => {
    const foodName = document.getElementById("food-name").value;
    const foodType = document.getElementById("food-type").value;
    const foodTiming = document.getElementById("food-timing").value;
    const foodQuantity = document.getElementById("food-quantity").value;
    const foodPicture = document.getElementById("food-picture").files[0];

    // Validate input fields
    if (!foodName || !foodType || !foodTiming || !foodQuantity || !foodPicture) {
      alert("Please fill out all food details, including the picture.");
      return;
    }

    // Create a food item object
    const foodItem = {
      name: foodName,
      type: foodType,
      timing: foodTiming,
      quantity: foodQuantity,
      picture: foodPicture.name,
    };
    foodItems.push(foodItem);

    // Add the food item to the table
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${foodName}</td>
      <td>${foodType}</td>
      <td>${foodTiming}</td>
      <td>${foodQuantity}</td>
      <td>${foodPicture.name}</td>
      <td><button class="remove-btn" onclick="removeFoodItem(${foodItems.length - 1})">Remove</button></td>
    `;
    foodTableBody.appendChild(row);

    // Clear input fields
    document.getElementById("food-name").value = "";
    document.getElementById("food-type").value = "";
    document.getElementById("food-timing").value = "";
    document.getElementById("food-quantity").value = "";
    document.getElementById("food-picture").value = "";

    // Update hidden input field for form submission
    document.getElementById("foodItems").value = JSON.stringify(foodItems);
  });

  // Function to remove a food item
  window.removeFoodItem = function (index) {
    // Remove the item from the array
    foodItems.splice(index, 1);

    // Rebuild the table
    foodTableBody.innerHTML = "";
    foodItems.forEach((food, i) => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td>${food.name}</td>
        <td>${food.type}</td>
        <td>${food.timing}</td>
        <td>${food.quantity}</td>
        <td>${food.picture}</td>
        <td><button class="remove-btn" onclick="removeFoodItem(${i})">Remove</button></td>
      `;
      foodTableBody.appendChild(row);
    });

    // Update hidden input field for form submission
    document.getElementById("foodItems").value = JSON.stringify(foodItems);
  };

  // Event listener for form submission
  donationForm.addEventListener("submit", (event) => {
    if (foodItems.length === 0) {
      alert("Please add at least one food item before submitting.");
      event.preventDefault(); // Prevent form submission if no food items are added
      return;
    }

    // Add food items to the hidden input field
    document.getElementById("foodItems").value = JSON.stringify(foodItems);
  });

  // Event listener for opening the map popup
  chooseFromMapButton.addEventListener("click", () => {
    mapPopup.style.display = "block"; // Show the popup modal
  

    // Initialize the map if not already initialized
    if (!map) {
      map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 26.9124, lng: 75.7873 }, // Default location (Jaipur, Rajasthan)
        zoom: 13,
      });

      marker = new google.maps.Marker({
        map: map,
        draggable: true, // Allow the marker to be dragged
      });

      // Center the map on the current location if available
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((position) => {
          const pos = {
            lat: position.coords.latitude,
            lng: position.coords.longitude,
          };
          map.setCenter(pos);
          marker.setPosition(pos);
        });
      }

      // Update the input field when the marker is dragged
      google.maps.event.addListener(marker, "dragend", () => {
        const position = marker.getPosition();
        const geocoder = new google.maps.Geocoder();
        geocoder.geocode({ location: position }, (results, status) => {
          if (status === "OK" && results[0]) {
            pickupPointInput.value = results[0].formatted_address;
          }
        });
      });

      // Update the marker position when the map is clicked
      google.maps.event.addListener(map, "click", (event) => {
        marker.setPosition(event.latLng);
        const geocoder = new google.maps.Geocoder();
        geocoder.geocode({ location: event.latLng }, (results, status) => {
          if (status === "OK" && results[0]) {
            pickupPointInput.value = results[0].formatted_address;
          }
        });
      });
    }
  });

  // Event listener for closing the map popup
  closeMapPopupButton.addEventListener("click", () => {
    mapPopup.style.display = "none";
  });
});

// Include Google Maps API with async and defer attributes
const script = document.createElement("script");
script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyCkeeIS4rmOn2To1pKRsipADKGnta8GxBM&libraries=places";
script.async = true;
script.defer = true;
document.head.appendChild(script);

// https://maps.googleapis.com/maps/api/js?key=AIzaSyCkeeIS4rmOn2To1pKRsipADKGnta8GxBM&callback=console.debug&libraries=maps,marker&v=beta
