// Select necessary DOM elements
const foodDetailsContainer = document.getElementById("food-details-container");
const addFoodButton = document.getElementById("add-food");
const foodTableBody = document.querySelector("#food-table tbody");
const donationForm = document.getElementById("donation-form");

// Array to hold added food items
const foodItems = [];

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
function removeFoodItem(index) {
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
}

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