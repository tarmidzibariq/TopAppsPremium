/**
 * Top Apps Premium — Dashboard charts
 */
'use strict';

document.addEventListener('DOMContentLoaded', function () {
  const data = window.dashboardData;
  if (!data || typeof ApexCharts === 'undefined') {
    return;
  }

  const cardColor = config.colors.cardColor;
  const headingColor = config.colors.headingColor;
  const labelColor = config.colors.textMuted;
  const borderColor = config.colors.borderColor;
  const fontFamily = config.fontFamily;

  const formatRp = (val) => {
    if (val >= 1_000_000) {
      return 'Rp ' + (val / 1_000_000).toFixed(1) + 'jt';
    }
    if (val >= 1_000) {
      return 'Rp ' + (val / 1_000).toFixed(0) + 'rb';
    }
    return 'Rp ' + Math.round(val).toLocaleString('id-ID');
  };

  const baseChart = {
    fontFamily,
    foreColor: labelColor,
    toolbar: { show: false }
  };

  const categoryEl = document.querySelector('#chartStockCategory');
  if (categoryEl && data.categoryLabels.length) {
    new ApexCharts(categoryEl, {
      chart: { ...baseChart, height: 320, type: 'bar' },
      plotOptions: {
        bar: {
          borderRadius: 6,
          columnWidth: '45%',
          distributed: true
        }
      },
      colors: [
        config.colors.primary,
        config.colors.success,
        config.colors.info,
        config.colors.warning,
        config.colors.danger,
        '#8592a3'
      ],
      dataLabels: { enabled: false },
      legend: { show: false },
      grid: { borderColor, strokeDashArray: 4 },
      series: [{ name: 'Stock', data: data.categoryStock }],
      xaxis: {
        categories: data.categoryLabels,
        labels: { style: { colors: labelColor, fontSize: '12px' } }
      },
      yaxis: {
        labels: { style: { colors: labelColor } }
      },
      tooltip: {
        y: { formatter: (v) => v + ' unit' }
      }
    }).render();
  } else if (categoryEl) {
    categoryEl.innerHTML =
      '<p class="text-center text-body-secondary py-5 mb-0">Belum ada data kategori. Tambahkan kategori & layanan terlebih dahulu.</p>';
  }

  const stockMovementEl = document.querySelector('#chartStockMovement');
  if (stockMovementEl) {
    new ApexCharts(stockMovementEl, {
      chart: { ...baseChart, height: 320, type: 'line' },
      colors: [config.colors.warning, config.colors.success],
      stroke: { width: 3, curve: 'smooth' },
      markers: { size: 4 },
      dataLabels: { enabled: false },
      legend: {
        position: 'top',
        labels: { colors: headingColor }
      },
      grid: { borderColor, strokeDashArray: 4 },
      series: [
        { name: 'Stock Pesan (Keluar)', data: data.pesanSeries },
        { name: 'Stock Masuk', data: data.masukSeries }
      ],
      xaxis: {
        categories: data.monthLabels,
        labels: { style: { colors: labelColor } }
      },
      yaxis: {
        labels: { style: { colors: labelColor } }
      },
      tooltip: {
        y: { formatter: (v) => v + ' unit' }
      }
    }).render();
  }

  const revenueEl = document.querySelector('#chartRevenue');
  if (revenueEl) {
    new ApexCharts(revenueEl, {
      chart: { ...baseChart, height: 320, type: 'area' },
      colors: [config.colors.primary],
      fill: {
        type: 'gradient',
        gradient: {
          shadeIntensity: 1,
          opacityFrom: 0.45,
          opacityTo: 0.05,
          stops: [0, 100]
        }
      },
      stroke: { width: 2, curve: 'smooth' },
      dataLabels: { enabled: false },
      grid: { borderColor, strokeDashArray: 4 },
      series: [{ name: 'Pemasukan', data: data.revenueSeries }],
      xaxis: {
        categories: data.monthLabels,
        labels: { style: { colors: labelColor } }
      },
      yaxis: {
        labels: {
          style: { colors: labelColor },
          formatter: (v) => formatRp(v)
        }
      },
      tooltip: {
        y: { formatter: (v) => 'Rp ' + Number(v).toLocaleString('id-ID') }
      }
    }).render();
  }

  const donutEl = document.querySelector('#chartCategoryDonut');
  if (donutEl && data.categoryLabels.length) {
    new ApexCharts(donutEl, {
      chart: { ...baseChart, height: 280, type: 'donut' },
      labels: data.categoryLabels,
      series: data.categoryStock,
      colors: [
        config.colors.primary,
        config.colors.success,
        config.colors.info,
        config.colors.warning,
        config.colors.danger
      ],
      legend: {
        position: 'bottom',
        labels: { colors: headingColor }
      },
      dataLabels: { enabled: true },
      plotOptions: {
        pie: {
          donut: {
            size: '65%',
            labels: {
              show: true,
              total: {
                show: true,
                label: 'Total Stock',
                formatter: () => data.totalStock
              }
            }
          }
        }
      },
      tooltip: {
        y: { formatter: (v) => v + ' unit' }
      }
    }).render();
  } else if (donutEl) {
    donutEl.innerHTML =
      '<p class="text-center text-body-secondary py-5 mb-0">Belum ada distribusi stock.</p>';
  }
});
