// ---------------- BLUE GRADIENT FUNCTION ----------------
function createBlueGradient(ctx) {
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, "rgba(0, 91, 255, 0.7)");
    gradient.addColorStop(1, "rgba(0, 91, 255, 0.05)");
    return gradient;
}

// ===================== MONTHLY BOOKINGS (LINE) =====================
const bookingsCtx = document.getElementById("bookingsChart").getContext("2d");
const bookingsGradient = createBlueGradient(bookingsCtx);

new Chart(bookingsCtx, {
    type: "line",
    data: {
        labels: bookingMonths,
        datasets: [{
            label: "Bookings",
            data: bookingCounts,
            borderColor: "#005bff",
            backgroundColor: bookingsGradient,
            borderWidth: 3,
            pointRadius: 6,
            pointBackgroundColor: "#005bff",
            tension: 0.35
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: "#e6eaff" } },
            x: { grid: { display: false } }
        }
    }
});

// ===================== BOOKING STATUS PIE =====================
new Chart(document.getElementById("statusPie"), {
    type: "pie",
    data: {
        labels: ["Booked", "Cancelled"],
        datasets: [{
            data: [booked, cancelled],
            backgroundColor: ["#005bff", "#ff4d4d"]
        }]
    },
    options: { responsive: true }
});

// ===================== REVENUE TREND (BAR) =====================
const revenueCtx = document.getElementById("revenueChart").getContext("2d");
const revenueGradient = createBlueGradient(revenueCtx);

new Chart(revenueCtx, {
    type: "bar",
    data: {
        labels: revenueMonths,
        datasets: [{
            label: "Revenue (₹)",
            data: revenueValues,
            backgroundColor: revenueGradient,
            borderColor: "#005bff",
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: "#e6eaff" } },
            x: { grid: { display: false } }
        }
    }
});

// ===================== USER GROWTH (BAR) =====================
const userCtx = document.getElementById("userGrowthChart").getContext("2d");
const userGradient = createBlueGradient(userCtx);

new Chart(userCtx, {
    type: "bar",
    data: {
        labels: ugMonths,
        datasets: [{
            label: "New Users",
            data: ugValues,
            backgroundColor: userGradient,
            borderColor: "#005bff",
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: "#e6eaff" } },
            x: { grid: { display: false } }
        }
    }
});
