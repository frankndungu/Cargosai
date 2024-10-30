<template>
  <div class="admin-dashboard-container">
    <!-- Top Navigation Bar -->
    <header class="admin-dashboard-header">
      <div class="admin-nav-top">
        <div class="admin-nav-links">
          <a href="#">Overview</a>
          <a href="#">Products</a>
          <a href="#">Customers</a>
          <a href="#">Orders</a>
        </div>
      </div>

      <!-- Dashboard Title and Actions -->
      <div class="admin-dashboard-actions">
        <h1 class="admin-dashboard-title">Dashboard</h1>
        <div class="admin-actions">
          <input type="date" class="admin-date-picker" />
          <button class="admin-download">Download</button>
        </div>
      </div>
    </header>

    <!-- Main Dashboard Content -->
    <div class="admin-dashboard-main">
      <div class="admin-overview-tabs">
        <button class="admin-tab-button active">Overview</button>
        <button class="admin-tab-button">Analytics</button>
        <button class="admin-tab-button">Reports</button>
        <button class="admin-tab-button">Notifications</button>
      </div>

      <!-- Cards Section -->
      <div class="admin-cards">
        <div class="admin-card">
          <h3>Total Revenue</h3>
          <p class="admin-card-value">$45,231.89</p>
          <p class="admin-card-change positive">+20.1% from last month</p>
        </div>
        <div class="admin-card">
          <h3>Subscriptions</h3>
          <p class="admin-card-value">+2350</p>
          <p class="admin-card-change positive">+18.0% from last month</p>
        </div>
        <div class="admin-card">
          <h3>Sales</h3>
          <p class="admin-card-value">+12,234</p>
          <p class="admin-card-change positive">+19% from last month</p>
        </div>
        <div class="admin-card">
          <h3>Active Now</h3>
          <p class="admin-card-value">+573</p>
          <p class="admin-card-change neutral">+201 since last hour</p>
        </div>
      </div>

      <!-- Main Content Section -->
      <div class="admin-content">
        <div class="admin-sales-chart">Sales Chart</div>
        <div class="admin-recent-sales">
          <h3>Recent Sales</h3>
          <p>You made 265 sales this month.</p>
          <ul>
            <li>
              <div class="admin-sales-item">
                <span class="admin-sales-name">Olivia Martin</span>
                <span class="admin-sales-amount">+$1,999.00</span>
              </div>
              <p class="admin-sales-email">olivia.martin@email.com</p>
            </li>
            <li>
              <div class="admin-sales-item">
                <span class="admin-sales-name">Jackson Lee</span>
                <span class="admin-sales-amount">+$39.00</span>
              </div>
              <p class="admin-sales-email">jackson.lee@email.com</p>
            </li>
            <li>
              <div class="admin-sales-item">
                <span class="admin-sales-name">Isabella Nguyen</span>
                <span class="admin-sales-amount">+$299.00</span>
              </div>
              <p class="admin-sales-email">isabella.nguyen@email.com</p>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// Optional script setup if needed
</script>

<style>
/* Admin Dashboard Styles */
.admin-dashboard-container {
  width: 100vw;
  padding: 40px 50px;
  background-color: #f4f4f9;
  overflow-x: hidden;
}

.admin-dashboard-header {
  padding-bottom: 20px;
  border-bottom: 1px solid #ccc;
}

.admin-nav-top {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 20px;
}

.admin-nav-links {
  display: flex;
  gap: 20px;
}

.admin-nav-links a {
  text-decoration: none;
  color: #333;
}

.admin-dashboard-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
}

.admin-dashboard-title {
  font-size: 1.8em;
  color: #333;
}

.admin-actions {
  display: flex;
  gap: 10px;
  align-items: center;
}

.admin-date-picker {
  padding: 5px 10px;
}

.admin-download {
  background: #ff6347;
  color: #fff;
  border: none;
  padding: 5px 10px;
  cursor: pointer;
}

.admin-dashboard-main {
  width: 100%;
}

.admin-overview-tabs {
  display: flex;
  margin-top: 20px;
}

.admin-tab-button {
  padding: 10px 20px;
  margin-right: 10px;
  border: none;
  background-color: #ddd;
  color: #333;
  cursor: pointer;
  border-radius: 5px;
}

.admin-tab-button.active {
  background-color: #333;
  color: #fff;
}

.admin-cards {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
  margin-top: 20px;
}

.admin-card {
  background-color: #222;
  padding: 20px;
  color: #fff;
  text-align: center;
  border-radius: 10px;
  transition: background-color 0.3s;
}

.admin-card h3 {
  margin: 0;
  font-size: 1.2em;
  color: #ff6347;
}

.admin-card-value {
  font-size: 1.8em;
  margin: 10px 0;
}

.admin-card-change {
  font-size: 0.9em;
}

.positive {
  color: #4caf50;
}

.neutral {
  color: #ffeb3b;
}

.admin-content {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 20px;
  margin-top: 20px;
}

.admin-sales-chart {
  background-color: #ddd;
  padding: 20px;
  height: auto;
  text-align: center;
  border-radius: 10px;
}

.admin-recent-sales {
  background-color: #333;
  padding: 20px;
  color: #fff;
  border-radius: 10px;
}

.admin-recent-sales h3 {
  margin-bottom: 10px;
}

.admin-recent-sales p {
  margin: 2px 0 20px;
  color: #bbb;
}

.admin-sales-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.admin-sales-name {
  font-weight: bold;
  color: #fff;
}

.admin-sales-amount {
  font-weight: bold;
  color: #4caf50;
  font-size: 1.2em;
  margin-top: 5px;
}

.admin-sales-email {
  color: #bbb;
  font-size: 0.9em;
}

/* Responsive Styles */
@media (max-width: 768px) {
  .admin-dashboard-container {
    padding: 20px;
    width: 100vw;
  }

  .admin-nav-top {
    flex-direction: column;
    align-items: flex-start;
  }

  .admin-nav-links {
    margin-bottom: 10px;
  }

  .admin-dashboard-actions {
    flex-direction: column;
    align-items: flex-start;
  }

  .admin-actions {
    flex-direction: row;
    justify-content: space-between;
    width: 100%;
    margin-top: 10px;
  }

  .admin-overview-tabs {
    flex-direction: column;
    gap: 10px; /* Adds spacing between tabs in mobile view */
    width: 100%; /* Ensures tabs use full width */
  }

  .admin-tab-button {
    width: 100%; /* Full width for tabs in mobile view */
    text-align: left;
    padding: 15px;
  }

  .admin-cards {
    grid-template-columns: repeat(2, 1fr);
  }

  .admin-card-value {
    font-size: 1.5em;
  }

  .admin-content {
    grid-template-columns: 1fr;
  }
}
</style>
