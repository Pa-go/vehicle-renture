<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tenant Cars Listing | Renture</title>
    <link rel="stylesheet" href="style.css">
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background-color: #E6F0F4;
        color: #333;
      }

      /* Custom styles for tenant cars listing page */
      .main-content-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
      }

      .header-section {
        text-align: center;
        margin-bottom: 40px;
      }

      .header-section h1 {
        color: #001f3f;
        font-size: 32px;
        margin-bottom: 10px;
      }

      .header-section p {
        color: #666;
        font-size: 16px;
      }

      /* Filter & Sort Section */
      .filter-sort-section {
        display: flex;
        gap: 20px;
        margin-bottom: 40px;
        flex-wrap: wrap;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      }

      .filter-sort-section select,
      .filter-sort-section input {
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        background-color: #f9f9f9;
        color: #333;
        cursor: pointer;
        transition: border-color 0.3s ease;
      }

      .filter-sort-section select:focus,
      .filter-sort-section input:focus {
        outline: none;
        border-color: #001f3f;
        background-color: #fff;
      }

      .filter-sort-section select option {
        background-color: #fff;
        color: #333;
      }

      .filter-label {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #001f3f;
        font-weight: 600;
      }

      /* Cars Grid */
      .cars-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
      }

      .car-card {
        background-color: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
      }

      .car-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      }

      .car-image {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        text-align: center;
        padding: 20px;
        position: relative;
      }

      .car-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        font-size: 12px;
      }

      .availability-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background-color: #28a745;
        color: #fff;
      }

      .availability-badge.unavailable {
        background-color: #dc3545;
      }

      .car-details {
        padding: 20px;
      }

      .car-name {
        font-size: 20px;
        font-weight: 700;
        color: #001f3f;
        margin-bottom: 10px;
      }

      .car-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-size: 14px;
      }

      .car-info-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
      }

      .car-info-label {
        color: #999;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
      }

      .price {
        color: #001f3f;
        font-size: 18px;
        font-weight: 700;
      }

      .distance {
        color: #666;
        font-size: 14px;
      }

      .car-actions {
        display: flex;
        gap: 10px;
        padding-top: 15px;
        border-top: 1px solid #eee;
      }

      .btn-view-details {
        flex: 1;
        padding: 10px 15px;
        background-color: #001f3f;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease;
      }

      .btn-view-details:hover {
        background-color: #ffd700;
        color: #001f3f;
      }

      /* Responsive */
      @media (max-width: 768px) {
        .filter-sort-section {
          flex-direction: column;
        }

        .filter-sort-section select,
        .filter-sort-section input {
          width: 100%;
        }

        .cars-grid {
          grid-template-columns: 1fr;
        }

        .header-section h1 {
          font-size: 24px;
        }
      }
    </style>
  </head>
  <body>

<!-- Temporary Message Box -->
<div id="messageBox" style="display:none;position:fixed;top:10px;right:10px;background:#001F3F;color:#fff;padding:8px 12px;border-radius:6px;z-index:10000;font-family:Arial, sans-serif;"></div>

<?php include 'header.php'; ?>

    <!-- Main Content -->
    <div id="mainContent" class="main-content">
      <div class="main-content-inner">
        <!-- Header -->
        <div class="header-section">
          <h1>Available Vehicles</h1>
          <p>Choose from our wide selection of quality rental cars</p>
        </div>

        <!-- Filter & Sort Section -->
        <div class="filter-sort-section">
          <div>
            <span class="filter-label">Sort By:</span>
            <select id="sortSelect" onchange="sortCars()">
              <option value="default">Default</option>
              <option value="price-low">Price: Low to High</option>
              <option value="price-high">Price: High to Low</option>
              <option value="distance">Distance: Near to Far</option>
            </select>
          </div>

          <div>
            <span class="filter-label">Filter By:</span>
            <select id="filterSelect" onchange="filterCars()">
              <option value="all">All Vehicles</option>
              <option value="available">Available Only</option>
              <option value="unavailable">Unavailable</option>
            </select>
          </div>

          <div>
            <span class="filter-label">Price Range:</span>
            <input
              type="number"
              id="minPrice"
              placeholder="Min Price"
              onchange="filterByPrice()"
              min="0"
            />
            <input
              type="number"
              id="maxPrice"
              placeholder="Max Price"
              onchange="filterByPrice()"
              min="0"
            />
          </div>
        </div>

        <!-- Cars Grid -->
        <div class="cars-grid" id="carsGrid">
          <!-- Car cards will be generated here -->
        </div>
      </div>
    </div>

    <script>
      // Sample car data
      const carsData = [
        {
          id: 1,
          name: "Toyota Camry Hybrid",
          price: 40000,
          availability: true,
          distance: 2.5,
          image: "🚗",
        },
        {
          id: 2,
          name: "Honda Civic",
          price: 25000,
          availability: true,
          distance: 1.2,
          image: "🚙",
        },
        {
          id: 3,
          name: "Tesla Model 3",
          price: 55000,
          availability: false,
          distance: 3.8,
          image: "⚡",
        },
        {
          id: 4,
          name: "Ford F-150",
          price: 35000,
          availability: true,
          distance: 4.2,
          image: "🚕",
        },
        {
          id: 5,
          name: "BMW X5",
          price: 65000,
          availability: true,
          distance: 5.1,
          image: "🚗",
        },
        {
          id: 6,
          name: "Audi A4",
          price: 45000,
          availability: false,
          distance: 2.8,
          image: "🚙",
        },
        {
          id: 7,
          name: "Mercedes-Benz C-Class",
          price: 60000,
          availability: true,
          distance: 6.3,
          image: "🚕",
        },
        {
          id: 8,
          name: "Hyundai Elantra",
          price: 20000,
          availability: true,
          distance: 0.9,
          image: "🚗",
        },
        {
          id: 9,
          name: "Chevrolet Malibu",
          price: 30000,
          availability: true,
          distance: 3.5,
          image: "🚙",
        },
        {
          id: 10,
          name: "Nissan Altima",
          price: 28000,
          availability: false,
          distance: 4.7,
          image: "🚕",
        },
      ];

      let filteredCars = [...carsData];

      function renderCars(cars) {
        const carsGrid = document.getElementById("carsGrid");
        carsGrid.innerHTML = "";

        if (cars.length === 0) {
          carsGrid.innerHTML =
            '<p style="grid-column: 1/-1; text-align: center; color: #666; font-size: 18px; padding: 40px;">No cars match your criteria.</p>';
          return;
        }

        cars.forEach((car) => {
          const carCard = document.createElement("div");
          carCard.className = "car-card";
          carCard.innerHTML = `
                    <div class="car-image">
                        <div style="position: relative; width: 100%;">
                            <div class="car-image-placeholder">${
                              car.image
                            }</div>
                            <div class="availability-badge ${
                              !car.availability ? "unavailable" : ""
                            }">
                                ${
                                  car.availability
                                    ? "✓ Available"
                                    : "✗ Unavailable"
                                }
                            </div>
                        </div>
                    </div>
                    <div class="car-details">
                        <div class="car-name">${car.name}</div>
                        <div class="car-info">
                            <div class="car-info-item">
                                <span class="car-info-label">Price</span>
                                <span class="price">$${car.price.toLocaleString()}</span>
                            </div>
                            <div class="car-info-item">
                                <span class="car-info-label">Distance</span>
                                <span class="distance">${
                                  car.distance
                                } km away</span>
                            </div>
                        </div>
                        <div class="car-actions">
                            <button class="btn-view-details" onclick="viewDetails(${
                              car.id
                            })">View Details</button>
                        </div>
                    </div>
                `;
          carsGrid.appendChild(carCard);
        });
      }

      function sortCars() {
        const sortValue = document.getElementById("sortSelect").value;
        const sortedCars = [...filteredCars];

        switch (sortValue) {
          case "price-low":
            sortedCars.sort((a, b) => a.price - b.price);
            break;
          case "price-high":
            sortedCars.sort((a, b) => b.price - a.price);
            break;
          case "distance":
            sortedCars.sort((a, b) => a.distance - b.distance);
            break;
          default:
            sortedCars.sort((a, b) => a.id - b.id);
        }

        renderCars(sortedCars);
      }

      function filterCars() {
        const filterValue = document.getElementById("filterSelect").value;

        if (filterValue === "available") {
          filteredCars = carsData.filter((car) => car.availability);
        } else if (filterValue === "unavailable") {
          filteredCars = carsData.filter((car) => !car.availability);
        } else {
          filteredCars = [...carsData];
        }

        renderCars(filteredCars);
      }

      function filterByPrice() {
        const minPrice =
          parseFloat(document.getElementById("minPrice").value) || 0;
        const maxPrice =
          parseFloat(document.getElementById("maxPrice").value) || Infinity;

        filteredCars = carsData.filter(
          (car) => car.price >= minPrice && car.price <= maxPrice
        );
        renderCars(filteredCars);
      }

      function viewDetails(carId) {
        const car = carsData.find((c) => c.id === carId);
        alert(
          `Details for ${
            car.name
          }\n\nPrice: $${car.price.toLocaleString()}\nAvailability: ${
            car.availability ? "Available" : "Unavailable"
          }\nDistance: ${car.distance} km`
        );
      }

      // Initialize
      document.addEventListener("DOMContentLoaded", function () {
        // Search functionality for the listings
        const searchInput = document.getElementById("searchInput");
        const carsGrid = document.getElementById("carsGrid");

        function filterListings(query) {
          if (!carsGrid) return;
          const cards = Array.from(carsGrid.querySelectorAll(".car-card"));
          const q = (query || "").trim().toLowerCase();
          let visible = 0;
          cards.forEach((card) => {
            const nameEl = card.querySelector(".car-name");
            const name = nameEl ? nameEl.textContent.trim().toLowerCase() : "";
            const priceEl = card.querySelector(".price");
            const price = priceEl
              ? priceEl.textContent.trim().toLowerCase()
              : "";
            if (!q || name.includes(q) || price.includes(q)) {
              card.style.display = "";
              visible++;
            } else {
              card.style.display = "none";
            }
          });
        }

        if (searchInput) {
          searchInput.addEventListener("input", function (e) {
            filterListings(e.target.value);
          });
          searchInput.addEventListener("keydown", function (e) {
            if (e.key === "Enter") {
              e.preventDefault();
              filterListings(e.target.value);
            }
          });
        }

        // Initialize
        renderCars(carsData);
      });
    </script>

<?php include 'footer.php'; ?>
<script src="script.js"></script>
  </body>
</html>
