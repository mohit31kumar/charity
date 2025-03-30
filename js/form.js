const showFoodFormButton = document.getElementById("show-food-form");
        const foodDetailsContainer = document.getElementById("food-details-container");
        const addFoodButton = document.getElementById("add-food");
        const foodList = document.getElementById("food-items");

        showFoodFormButton.addEventListener("click", () => {
            foodDetailsContainer.style.display = "block";
        });

        addFoodButton.addEventListener("click", () => {
            const foodName = document.getElementById("food-name").value;
            const foodType = document.getElementById("food-type").value;
            const foodTiming = document.getElementById("food-timing").value;
            const foodQuantity = document.getElementById("food-quantity").value;
            const foodPicture = document.getElementById("food-picture").files[0];

            if (!foodName || !foodType || !foodTiming || !foodQuantity || !foodPicture) {
                alert("Please fill in all food details.");
                return;
            }

            if (foodType === "cooked") {
                const cookedTime = new Date(foodTiming);
                const currentTime = new Date();
                const timeDifference = (currentTime - cookedTime) / (1000 * 60 * 60); // Convert ms to hours

                if (timeDifference > 10) {
                    alert("Cooked food cannot be older than 10 hours.");
                    return;
                }
            }

            const foodItem = document.createElement("div");
            foodItem.classList.add("food-item");
            foodItem.innerHTML = `
                <div>
                    <p><strong>Food Name:</strong> ${foodName}</p>
                    <p><strong>Type:</strong> ${foodType}</p>
                    <p><strong>Timing:</strong> ${foodTiming}</p>
                    <p><strong>Quantity:</strong> ${foodQuantity} people</p>
                    <p><strong>Picture:</strong> ${foodPicture.name}</p>
                </div>
                <button class="remove-btn">Remove</button>
            `;

            foodItem.querySelector(".remove-btn").addEventListener("click", () => {
                foodItem.remove();
            });

            foodList.appendChild(foodItem);
            foodDetailsContainer.style.display = "none";
            foodDetailsContainer.querySelectorAll("input, select").forEach((field) => (field.value = ""));
        });

        document.getElementById("donation-form").addEventListener("submit", (event) => {
            event.preventDefault();
            alert("Form submitted successfully!");
        });