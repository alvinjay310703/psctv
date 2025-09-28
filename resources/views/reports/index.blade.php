@extends('layouts.admin')

@section('title', 'Reports')

@section('content')
<div class="p-6 space-y-6">

  <!-- Header -->
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold">Reports</h1>
    <div class="flex items-center gap-3">
      <label class="text-sm text-gray-600 mr-2">Range</label>
      <input id="fromDate" type="date" class="border rounded px-2 py-1 text-sm">
      <input id="toDate" type="date" class="border rounded px-2 py-1 text-sm">
      <button id="btnRefresh" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-sm shadow">
        Refresh
      </button>
    </div>
  </div>

  <!-- KPI Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="p-4 bg-white rounded-lg shadow flex items-center justify-between">
      <div>
        <p class="text-sm text-gray-500">Total Jobs</p>
        <p id="kpiTotalJobs" class="text-2xl font-semibold">0</p>
      </div>
      <div class="text-gray-400">
        <!-- icon -->
        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 17v-6a2 2 0 00-2-2H5l7-7 7 7h-2a2 2 0 00-2 2v6" />
        </svg>
      </div>
    </div>

    <div class="p-4 bg-white rounded-lg shadow flex items-center justify-between">
      <div>
        <p class="text-sm text-gray-500">Completed</p>
        <p id="kpiCompleted" class="text-2xl font-semibold text-green-600">0</p>
      </div>
      <div class="text-gray-400">
        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 13l4 4L19 7" />
        </svg>
      </div>
    </div>

    

    <div class="p-4 bg-white rounded-lg shadow flex items-center justify-between">
      <div>
        <p class="text-sm text-gray-500">Average Completion (hrs)</p>
        <p id="kpiAvgCompletion" class="text-2xl font-semibold">0</p>
      </div>
      <div class="text-gray-400">
        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4l3 3" />
        </svg>
      </div>
    </div>

    <div class="p-4 bg-white rounded-lg shadow flex items-center justify-between">
      <div>
        <p class="text-sm text-gray-500">Pending</p>
        <p id="kpiPending" class="text-2xl font-semibold text-yellow-600">0</p>
      </div>
      <div class="text-gray-400">
        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 4v1m0 14v1m8-8h-1M5 12H4" />
        </svg>
      </div>
    </div>
  </div>

  <!-- Charts Row -->
<!-- Revenue Section -->
<div class="bg-white rounded-lg shadow p-4">
  <div class="flex items-center justify-between mb-3">
    <h3 class="text-sm font-semibold">Revenue Report</h3>
    <button id="exportRevenueCSV" class="text-xs bg-gray-100 px-2 py-1 rounded hover:bg-gray-200">Export CSV</button>
  </div>
  <canvas id="revenueChart" height="120"></canvas>

  <div class="overflow-x-auto mt-4">
    <table class="w-full text-sm border rounded-lg overflow-hidden">
      <thead class="bg-gray-100 text-gray-700 text-xs">
        <tr>
          <th class="p-2 text-left">Date</th>
          <th class="p-2 text-left">Revenue (₱)</th>
        </tr>
      </thead>
      <tbody id="revenueTbody"></tbody>
    </table>
  </div>

  <!-- Pagination & Count -->
  <div class="mt-3 flex items-center justify-between">
    <div id="revenueCount" class="text-xs text-gray-600"></div>
    <div id="revenuePagination" class="flex gap-1"></div>
  </div>
</div>


  <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
  <div class="bg-white rounded-lg shadow p-4">
    <h3 class="text-sm font-semibold mb-2">Jobs per Day</h3>
    <canvas id="jobsLineChart" height="180"></canvas>
  </div>

  <div class="bg-white rounded-lg shadow p-4">
    <h3 class="text-sm font-semibold mb-2">Job Types</h3>
    <canvas id="typeDoughnut" height="180"></canvas>
  </div>
</div>


  <!-- Technician Performance & Jobs Table -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <!-- Tech performance -->
    <div class="lg:col-span-1 bg-white rounded-lg shadow p-4">
      <div class="flex items-center justify-between mb-3">
        <h3 class="text-sm font-semibold">Technician Performance</h3>
        <button id="exportTechCSV" class="text-xs bg-gray-100 px-2 py-1 rounded hover:bg-gray-200">Export CSV</button>
      </div>
      <div class="overflow-y-auto max-h-96">
        <table id="techTable" class="w-full text-sm">
          <thead class="text-xs text-gray-500">
            <tr>
              <th class="text-left p-2">Technician</th>
              <th class="text-right p-2">Completed</th>
              <th class="text-right p-2">Avg hrs</th>
            </tr>
          </thead>
          <tbody id="techTbody" class="text-sm"></tbody>
        </table>
      </div>
    </div>

    <!-- Jobs table -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow p-4">
      <div class="flex items-center justify-between mb-3">
        <h3 class="text-sm font-semibold">Recent Jobs</h3>
        <div class="flex items-center gap-2">
          <input id="searchJobs" type="text" placeholder="Search by customer or ID" class="border rounded px-2 py-1 text-sm">
          <button id="exportJobsCSV" class="text-xs bg-gray-100 px-2 py-1 rounded hover:bg-gray-200">Export CSV</button>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table id="jobsTable" class="w-full text-sm table-auto">
          <thead class="text-xs text-gray-500">
            <tr>
              <th class="p-2 text-left">Request ID</th>
              <th class="p-2 text-left">Customer</th>
              <th class="p-2 text-left">Technician</th>
              <th class="p-2 text-left">Zone</th>
              <th class="p-2 text-left">Type</th>
              <th class="p-2 text-left">Status</th>
              <th class="p-2 text-left">Assigned</th>
              <th class="p-2 text-left">Completed</th>
            </tr>
          </thead>
          <tbody id="jobsTbody" class="text-sm"></tbody>
        </table>
      </div>

      <div class="mt-3 flex items-center justify-between">
        <div id="jobsCount" class="text-xs text-gray-600"></div>
        <div id="jobsPagination" class="flex gap-1"></div>
      </div>
    </div>
  </div>

</div>

<!-- Chart.js CDN (for demo) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
/* -------------------------------
   Front-end Reports (demo)
   - Client-side mock data generator
   - Aggregations for KPIs & charts
   - CSV export
   ------------------------------- */

const PAGE_SIZE_JOBS = 8;
let mockJobs = []; // will hold generated or fetched jobs
let techs = [];    // technicians
let currentJobsPage = 1;

/* ---------- Mock data generator ---------- */
function generateMockData(days = 30, jobsPerDay = 12) {
  const zones = ['Zone A','Zone B','Zone C','Zone D','Zone E'];
  const types = ['Installation','Repair','Upgrade'];
  const technicians = [
    { id: 1, name: 'John Doe' },
    { id: 2, name: 'Jane Smith' },
    { id: 3, name: 'Mark Lee' },
    { id: 4, name: 'Ana Cruz' }
  ];
  techs = technicians;

  const jobs = [];
  const now = new Date();
  for (let d = days - 1; d >= 0; d--) {
    for (let j = 0; j < jobsPerDay; j++) {
      const created = new Date(now.getFullYear(), now.getMonth(), now.getDate() - d, Math.floor(Math.random()*10)+7, Math.floor(Math.random()*60));
      const assignedAt = new Date(created.getTime() + Math.floor(Math.random()*3)*60*60*1000); // 0-2h after creation
      const completedDelayHours = Math.floor(Math.random()*48); // 0-48h
      const completedAt = Math.random() > 0.25 ? new Date(assignedAt.getTime() + completedDelayHours*3600000) : null; // 75% completed
      const status = completedAt ? (Math.random()>0.02 ? 'Completed' : 'Verified') : (Math.random()>0.5 ? 'In Progress' : 'Pending');
      const tech = technicians[Math.floor(Math.random()*technicians.length)];
      jobs.push({
        id: 'REQ-' + (1000 + jobs.length),
        customer: `Customer ${Math.floor(Math.random()*200)+1}`,
        zone: zones[Math.floor(Math.random()*zones.length)],
        type: types[Math.floor(Math.random()*types.length)],
        technician: Math.random() > 0.2 ? tech.name : null,
        technician_id: tech.id,
        created_at: created.toISOString(),
        assigned_at: assignedAt.toISOString(),
        completed_at: completedAt ? completedAt.toISOString() : null,
        status
      });
    }
  }
  mockJobs = jobs.sort((a,b) => new Date(b.created_at) - new Date(a.created_at));
}

/* ---------- Aggregation helpers ---------- */
function calculateKPIs(jobs) {
  const total = jobs.length;
  const completed = jobs.filter(j => j.status === 'Completed' || j.status === 'Verified').length;
  const pending = jobs.filter(j => j.status === 'Pending' || j.status === 'In Progress').length;
  // average completion hours (only completed)
  const completedJobs = jobs.filter(j => j.completed_at && j.assigned_at);
  const avgHours = completedJobs.length ? (completedJobs.reduce((sum, j) => {
    const assigned = new Date(j.assigned_at);
    const completed = new Date(j.completed_at);
    return sum + (completed - assigned)/3600000;
  }, 0) / completedJobs.length) : 0;
  return { total, completed, pending, avgHours: avgHours.toFixed(1) };
}

function aggregateJobsPerDay(jobs, days = 30) {
  const map = {};
  for (let i=days-1;i>=0;i--) {
    const dt = new Date();
    dt.setDate(dt.getDate() - i);
    const key = dt.toISOString().slice(0,10);
    map[key] = 0;
  }
  jobs.forEach(j => {
    const k = j.created_at.slice(0,10);
    if (map[k] !== undefined) map[k]++;
  });
  return Object.entries(map).map(([k,v])=>({date:k,count:v}));
}

/* ---------- Chart instances ---------- */
let jobsLineChart, zoneBarChart, typeDoughnut;

function renderCharts(jobs) {
  const perDay = aggregateJobsPerDay(jobs, 30);
  const labels = perDay.map(x => x.date);
  const data = perDay.map(x => x.count);

  // Jobs per Day (line)
  const ctxLine = document.getElementById('jobsLineChart').getContext('2d');
  if (jobsLineChart) jobsLineChart.destroy();
  jobsLineChart = new Chart(ctxLine, {
    type: 'line',
    data: { labels, datasets: [{ label: 'Jobs', data, fill: true, borderWidth: 2, tension: 0.3, backgroundColor: 'rgba(79,70,229,0.06)', borderColor: '#4f46e5' }] },
    options: { responsive: true, plugins:{ legend:{ display:false } }, scales:{ x:{ ticks:{ maxRotation:0 } } } }
  });

  
  // Job Types (doughnut)
  const types = {};
  jobs.forEach(j=> types[j.type] = (types[j.type]||0) + 1);
  const typeLabels = Object.keys(types);
  const typeData = Object.values(types);
  const ctxDon = document.getElementById('typeDoughnut').getContext('2d');
  if (typeDoughnut) typeDoughnut.destroy();
  typeDoughnut = new Chart(ctxDon, {
    type:'doughnut',
    data:{ labels:typeLabels, datasets:[{ data:typeData, backgroundColor:['#f97316','#10b981','#60a5fa'] }] },
    options:{ responsive:true, plugins:{ legend:{ position:'bottom' } } }
  });
}

/* ---------- Render tables ---------- */
function renderKPIs(jobs) {
  const k = calculateKPIs(jobs);
  document.getElementById('kpiTotalJobs').textContent = k.total;
  document.getElementById('kpiCompleted').textContent = k.completed;
  document.getElementById('kpiPending').textContent = k.pending;
  document.getElementById('kpiAvgCompletion').textContent = k.avgHours;
}

function renderTechTable(jobs) {
  // aggregate by technician
  const byTech = {};
  jobs.forEach(j=>{
    const tech = j.technician || 'Unassigned';
    if (!byTech[tech]) byTech[tech] = { name: tech, completed:0, hours:0, count:0 };
    if (j.completed_at && j.assigned_at) {
      byTech[tech].completed++;
      byTech[tech].hours += (new Date(j.completed_at) - new Date(j.assigned_at))/3600000;
    }
    byTech[tech].count++;
  });
  const arr = Object.values(byTech).sort((a,b)=> b.completed - a.completed);
  const tbody = document.getElementById('techTbody');
  tbody.innerHTML = arr.map(t => {
    const avg = t.completed ? (t.hours / t.completed).toFixed(1) : '-';
    return `<tr>
      <td class="p-2">${escapeHtml(t.name)}</td>
      <td class="p-2 text-right">${t.completed}</td>
      <td class="p-2 text-right">${avg}</td>
    </tr>`;
  }).join('');
}

function renderJobsTable(jobs) {
  const search = document.getElementById('searchJobs').value.trim().toLowerCase();
  let filtered = jobs.filter(j =>
    (!search || (j.customer + ' ' + j.id).toLowerCase().includes(search))
  );

  // date range filter if selected
  const from = document.getElementById('fromDate').value;
  const to = document.getElementById('toDate').value;
  if (from) filtered = filtered.filter(j => j.created_at.slice(0,10) >= from);
  if (to) filtered = filtered.filter(j => j.created_at.slice(0,10) <= to);

  const total = filtered.length;
  const pages = Math.max(1, Math.ceil(total / PAGE_SIZE_JOBS));
  if (currentJobsPage > pages) currentJobsPage = pages;
  const start = (currentJobsPage - 1) * PAGE_SIZE_JOBS;
  const slice = filtered.slice(start, start + PAGE_SIZE_JOBS);

  document.getElementById('jobsTbody').innerHTML = slice.map(j => `
    <tr class="border-t">
      <td class="p-2">${escapeHtml(j.id)}</td>
      <td class="p-2">${escapeHtml(j.customer)}</td>
      <td class="p-2">${escapeHtml(j.technician || 'Unassigned')}</td>
      <td class="p-2">${escapeHtml(j.zone)}</td>
      <td class="p-2">${escapeHtml(j.type)}</td>
      <td class="p-2">${escapeHtml(j.status)}</td>
      <td class="p-2">${j.assigned_at ? new Date(j.assigned_at).toLocaleString() : '—'}</td>
      <td class="p-2">${j.completed_at ? new Date(j.completed_at).toLocaleString() : '—'}</td>
    </tr>
  `).join('');

  document.getElementById('jobsCount').textContent = `Showing ${start+1}-${Math.min(start+slice.length, total)} of ${total} jobs`;
  buildJobsPagination(pages);
}

function buildJobsPagination(pages) {
  const wrap = document.getElementById('jobsPagination');
  wrap.innerHTML = '';
  const prev = document.createElement('button');
  prev.className = 'px-2 py-1 bg-gray-100 rounded text-sm'; prev.textContent = 'Prev';
  prev.onclick = () => { if (currentJobsPage>1) { currentJobsPage--; renderAll(); } };
  wrap.appendChild(prev);

  for (let p=1; p<=pages; p++) {
    const b = document.createElement('button');
    b.className = (p===currentJobsPage ? 'px-2 py-1 bg-indigo-600 text-white rounded text-sm' : 'px-2 py-1 bg-gray-100 rounded text-sm');
    b.textContent = p;
    b.onclick = (() => { currentJobsPage = p; renderAll(); });
    wrap.appendChild(b);
  }

  const next = document.createElement('button');
  next.className = 'px-2 py-1 bg-gray-100 rounded text-sm'; next.textContent = 'Next';
  next.onclick = () => { currentJobsPage++; renderAll(); };
  wrap.appendChild(next);
}

/* ---------- CSV export ---------- */
function exportTableCSV(filename, rows) {
  const csv = rows.map(r => r.map(cell => `"${String(cell).replace(/"/g,'""')}"`).join(',')).join('\n');
  const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
  const link = document.createElement('a');
  const url = URL.createObjectURL(blob);
  link.href = url; link.setAttribute('download', filename);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
  URL.revokeObjectURL(url);
}

/* ---------- Utilities ---------- */
function escapeHtml(s='') {
  return String(s).replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;');
}

/* ---------- Glue: generate, render, bind ---------- */
function renderAll() {
  renderKPIs(mockJobs);
  renderCharts(mockJobs);
  renderTechTable(mockJobs);
  renderJobsTable(mockJobs);
  renderRevenue(); // <-- this line makes your revenue chart + table show
}


/* event binding */
document.getElementById('btnRefresh').addEventListener('click', () => { currentJobsPage = 1; renderAll(); });
document.getElementById('searchJobs').addEventListener('input', () => { currentJobsPage = 1; renderAll(); });

document.getElementById('exportTechCSV').addEventListener('click', () => {
  // build rows: header + data
  const rows = [['Technician','Completed','Avg Hours']];
  // build data from tech table DOM (or recompute from mock)
  const byTech = {};
  mockJobs.forEach(j=>{
    const tech = j.technician || 'Unassigned';
    if (!byTech[tech]) byTech[tech] = { completed:0, hours:0 };
    if (j.completed_at && j.assigned_at) { byTech[tech].completed++; byTech[tech].hours += (new Date(j.completed_at) - new Date(j.assigned_at))/3600000; }
  });
  Object.keys(byTech).forEach(k => rows.push([k, byTech[k].completed, byTech[k].completed ? (byTech[k].hours/byTech[k].completed).toFixed(1) : '-']));
  exportTableCSV('technicians_report.csv', rows);
});

document.getElementById('exportJobsCSV').addEventListener('click', () => {
  const rows = [['RequestID','Customer','Technician','Zone','Type','Status','AssignedAt','CompletedAt']];
  mockJobs.forEach(j => rows.push([j.id,j.customer,j.technician||'','',j.type,j.status,j.assigned_at||'', j.completed_at||'']));
  exportTableCSV('jobs_report.csv', rows);
});

/* ---------- Revenue demo data ---------- */
let mockRevenue = [];

function generateRevenue(days = 30) {
  const data = [];
  const now = new Date();
  for (let d = days - 1; d >= 0; d--) {
    const dt = new Date(now.getFullYear(), now.getMonth(), now.getDate() - d);
    data.push({
      date: dt.toISOString().slice(0,10),
      amount: Math.floor(Math.random() * 20000) + 5000 // ₱5k - ₱25k
    });
  }
  mockRevenue = data;
}

let revenueChart;

function renderRevenue() {
  const ctx = document.getElementById('revenueChart').getContext('2d');
  if (revenueChart) revenueChart.destroy();

  revenueChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: mockRevenue.map(r => r.date),
      datasets: [{
        label: 'Revenue (₱)',
        data: mockRevenue.map(r => r.amount),
        borderColor: 'rgba(37, 99, 235, 1)',
        backgroundColor: 'rgba(37, 99, 235, 0.2)',
        fill: true,
        tension: 0.3,
        borderWidth: 2,
        pointBackgroundColor: 'rgba(37, 99, 235, 1)',
        pointRadius: 3
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        y: {
          ticks: { callback: val => '₱' + val.toLocaleString() }
        }
      }
    }
  });

  // Fill table
  document.getElementById('revenueTbody').innerHTML = mockRevenue.map(r => `
    <tr class="border-t">
      <td class="p-2">${r.date}</td>
      <td class="p-2 font-semibold">₱${r.amount.toLocaleString()}</td>
    </tr>
  `).join('');
}

document.getElementById('exportRevenueCSV').addEventListener('click', () => {
  const rows = [['Date','Revenue']];
  mockRevenue.forEach(r => rows.push([r.date, r.amount]));
  exportTableCSV('revenue_report.csv', rows);
});

/* ---------- Revenue Pagination ---------- */
const PAGE_SIZE_REVENUE = 10;
let currentRevenuePage = 1;

function renderRevenue() {
  const ctx = document.getElementById('revenueChart').getContext('2d');
  if (revenueChart) revenueChart.destroy();

  revenueChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: mockRevenue.map(r => r.date),
      datasets: [{
        label: 'Revenue (₱)',
        data: mockRevenue.map(r => r.amount),
        borderColor: 'rgba(37, 99, 235, 1)',
        backgroundColor: 'rgba(37, 99, 235, 0.2)',
        fill: true,
        tension: 0.3,
        borderWidth: 2,
        pointBackgroundColor: 'rgba(37, 99, 235, 1)',
        pointRadius: 3
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        y: {
          ticks: { callback: val => '₱' + val.toLocaleString() }
        }
      }
    }
  });

  // Paginated Table Rendering
  const total = mockRevenue.length;
  const pages = Math.max(1, Math.ceil(total / PAGE_SIZE_REVENUE));
  if (currentRevenuePage > pages) currentRevenuePage = pages;

  const start = (currentRevenuePage - 1) * PAGE_SIZE_REVENUE;
  const slice = mockRevenue.slice(start, start + PAGE_SIZE_REVENUE);

  document.getElementById('revenueTbody').innerHTML = slice.map(r => `
    <tr class="border-t">
      <td class="p-2">${r.date}</td>
      <td class="p-2 font-semibold">₱${r.amount.toLocaleString()}</td>
    </tr>
  `).join('');

  document.getElementById('revenueCount').textContent =
    `Showing ${start + 1}-${Math.min(start + slice.length, total)} of ${total} records`;

  buildRevenuePagination(pages);
}

function buildRevenuePagination(pages) {
  const wrap = document.getElementById('revenuePagination');
  wrap.innerHTML = '';

  const prev = document.createElement('button');
  prev.className = 'px-2 py-1 bg-gray-100 rounded text-sm';
  prev.textContent = 'Prev';
  prev.onclick = () => { if (currentRevenuePage > 1) { currentRevenuePage--; renderRevenue(); } };
  wrap.appendChild(prev);

  for (let p = 1; p <= pages; p++) {
    const b = document.createElement('button');
    b.className = (p === currentRevenuePage
      ? 'px-2 py-1 bg-indigo-600 text-white rounded text-sm'
      : 'px-2 py-1 bg-gray-100 rounded text-sm');
    b.textContent = p;
    b.onclick = (() => { currentRevenuePage = p; renderRevenue(); });
    wrap.appendChild(b);
  }

  const next = document.createElement('button');
  next.className = 'px-2 py-1 bg-gray-100 rounded text-sm';
  next.textContent = 'Next';
  next.onclick = () => { if (currentRevenuePage < pages) { currentRevenuePage++; renderRevenue(); } };
  wrap.appendChild(next);
}


/* init demo */
generateMockData(30, 8); // jobs
generateRevenue(30);     // revenue
renderAll();


</script>
@endsection
