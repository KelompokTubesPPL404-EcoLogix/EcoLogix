<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard EcoLogix</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    .display-6 { font-size: 2rem; }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="row g-4" id="dashboardCards"></div>

    <div class="mt-5 chart-container">
      <h5 class="mb-3">Statistics: Total Emission Carbon</h5>
      <canvas id="carbonChart" height="100"></canvas>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const data = {
      totalEmisiApprovedTon: 124.567,
      totalEmisiPerTahun: 132000,
      totalKompensasi: 35.000,
      totalEmisiPerTahunPending: 98000,
      totalEmisiPending: 12,
      persentaseKompensasi: 26.5
    };

    const cardData = [
      {
        title: "Total Karbon Emisi (Approved)",
        value: `${data.totalEmisiApprovedTon.toLocaleString(undefined, { minimumFractionDigits: 3 })}`,
        sub: "ton CO₂e",
        note: `Konversi dari ${(data.totalEmisiApprovedTon * 1000).toLocaleString(undefined, { minimumFractionDigits: 2 })} kg CO₂`
      },
      {
        title: "Total Emisi (Approved)",
        value: `${(data.totalEmisiPerTahun / 1000).toLocaleString(undefined, { minimumFractionDigits: 3 })}`,
        sub: "ton CO₂",
        note: null
      },
      {
        title: "Total Kompensasi",
        value: `${data.totalKompensasi.toLocaleString(undefined, { minimumFractionDigits: 3 })}`,
        sub: "ton CO₂e",
        note: null
      },
      {
        title: "Emisi Carbon Pending",
        value: `${(data.totalEmisiPerTahunPending / 1000).toLocaleString(undefined, { minimumFractionDigits: 3 })}`,
        sub: "ton CO₂",
        note: null
      },
      {
        title: "Emisi Pending",
        value: data.totalEmisiPending,
        sub: "pengajuan",
        note: null
      },
      {
        title: "Persentase Kompensasi",
        value: `${data.persentaseKompensasi.toFixed(1)}`,
        sub: "%",
        note: "dari total emisi approved"
      }
    ];

    const dashboardCards = document.getElementById("dashboardCards");

    cardData.forEach(card => {
      dashboardCards.innerHTML += `
        <div class="col-md-4">
          <div class="card p-3 text-center">
            <h5 class="card-text display-6">
              ${card.value} <small class="fs-6">${card.sub}</small>
            </h5>
            <p class="mb-0">${card.title}</p>
            ${card.note ? `<small>${card.note}</small>` : ""}
            <small class="text-success mt-1 d-block">+2.5% sejak bulan lalu</small>
          </div>
        </div>
      `;
    });

    const ctx = document.getElementById('carbonChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL'],
        datasets: [{
          label: 'Ton CO₂e',
          data: [0.2, 0.35, 0.5, 0.3, 0.1, 0.4, 0.25],
          backgroundColor: '#198754'
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 0.1
            }
          }
        }
      }
    });
  </script>
</body>
</html>