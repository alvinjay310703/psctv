@extends('layouts.admin')

@section('title', 'Announcements')

@section('content')
<div class="p-6 bg-white rounded-xl shadow space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Announcements</h1>

        <div class="flex gap-3">
            <button id="btn-new" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm shadow">
                + New Announcement
            </button>
        </div>
    </div>

    {{-- Controls --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex gap-2 items-center">
            <input id="searchInput" type="text" placeholder="Search title or content..."
                class="w-64 border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-300">
            <select id="audienceFilter" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">All Audiences</option>
                <option value="customers">Customers</option>
                <option value="technicians">Technicians</option>
                <option value="all">All</option>
            </select>
            <select id="statusFilter" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">All Statuses</option>
                <option value="Scheduled">Scheduled</option>
                <option value="Active">Active</option>
                <option value="Expired">Expired</option>
            </select>
            <select id="priorityFilter" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">All Priorities</option>
                <option value="high">High</option>
                <option value="normal">Normal</option>
            </select>
        </div>

        <div class="text-sm text-gray-600" id="resultsInfo">Showing 0 announcements</div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table id="annTable" class="w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
            <thead class="bg-gray-50 text-gray-700 text-xs uppercase">
                <tr>
                    <th class="p-3 text-left">Title</th>
                    <th class="p-3 text-left">Audience</th>
                    <th class="p-3 text-left">Schedule</th>
                    <th class="p-3 text-left">Priority</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="annTbody" class="divide-y divide-gray-200">
                {{-- JS populated --}}
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="flex items-center justify-between mt-4">
        <div id="pageInfo" class="text-sm text-gray-600">Page 1</div>
        <div class="flex gap-1" id="pagination">
            {{-- JS builds buttons --}}
        </div>
    </div>
</div>

<!-- Create / Edit / View Modal -->
<div id="modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <!-- backdrop -->
    <div class="absolute inset-0 bg-black opacity-40"></div>

    <!-- modal content -->
    <div role="dialog" aria-modal="true" aria-labelledby="modalTitle"
         class="relative bg-white rounded-2xl shadow-xl w-full max-w-3xl p-6 z-10">
        <div class="flex items-center justify-between border-b pb-3 mb-4">
            <h2 id="modalTitle" class="text-lg font-semibold">New Announcement</h2>
            <button id="modalClose" class="text-gray-600 hover:text-gray-800">✕</button>
        </div>

        <!-- View Mode -->
       <div id="viewArea" class="hidden space-y-4">
    <div>
        <h3 id="viewTitle" class="text-xl font-bold text-gray-800"></h3>
        <p id="viewContent" class="mt-2 text-sm text-gray-700"></p>
    </div>

    <!-- Badges for audience & priority -->
    <div class="flex flex-wrap gap-3 text-xs">
        <span id="viewAudience" class="bg-indigo-50 text-indigo-700 px-2 py-1 rounded-full"></span>
        <span id="viewPriority" class="bg-red-50 text-red-700 px-2 py-1 rounded-full"></span>
    </div>

    <!-- Improved schedule block -->
    <div class="space-y-2 border-t pt-3 text-sm">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 4h10M5 11h14M5 19h14M5 15h14" />
            </svg>
            <span id="viewStart" class="text-gray-700">Start: —</span>
        </div>
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0h-2m-8 0H5" />
            </svg>
            <span id="viewEnd" class="text-gray-700">End: —</span>
        </div>
        <!-- Optional: live status -->
        <div class="mt-2">
            <span id="viewStatus" class="px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                Status: —
            </span>
        </div>
    </div>
</div>


        <!-- Create/Edit Form -->
        <form id="annForm" class="space-y-4">
            <input type="hidden" id="annId">

            <div>
                <label class="block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
                <input id="title" type="text" class="w-full border rounded-lg px-3 py-2 text-sm" required>
                <p id="titleError" class="text-xs text-red-500 hidden">Title is required.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Audience</label>
                <select id="audience" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="customers">Customers</option>
                    <option value="technicians">Technicians</option>
                    <option value="all">All</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Priority</label>
                <select id="priority" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="normal">Normal</option>
                    <option value="high">High</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Start (publish)</label>
                <input id="startDate" type="datetime-local" class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">End (expiry)</label>
                <input id="endDate" type="datetime-local" class="w-full border rounded-lg px-3 py-2 text-sm">
                <p id="dateError" class="text-xs text-red-500 hidden">End date must be after start date.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Content <span class="text-red-500">*</span></label>
                <textarea id="content" rows="4" class="w-full border rounded-lg px-3 py-2 text-sm" required></textarea>
                <p id="contentError" class="text-xs text-red-500 hidden">Content is required.</p>
            </div>

            <div class="flex items-center gap-3">
                <button id="saveBtn" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm">Save</button>
                <button id="previewBtn" type="button" class="bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-lg text-sm">Preview</button>
                <button id="cancelBtn" type="button" class="text-sm text-gray-600">Cancel</button>
            </div>

            <div id="previewArea" class="hidden border rounded-lg p-4 bg-gray-50 text-sm"></div>
        </form>
    </div>
</div>

<!-- Toast area -->
<div id="toastBox" class="fixed bottom-6 right-6 space-y-2 z-50"></div>

{{-- JS logic --}}
<script>
/* ---------------------------
   Front-end Announcements App
   - Stores announcements in localStorage (demo)
   - Fields: id, title, content, audience, priority, start, end, created_at, status
   --------------------------- */

const LS_KEY = 'pctvs_announcements_v1';
const PAGE_SIZE = 6;

const seed = [
    {
        id: generateId(),
        title: 'Planned Maintenance - Zone A',
        content: 'Scheduled maintenance on main node in Zone A. Services may be intermittent between 10:00 PM - 2:00 AM.',
        audience: 'all',
        priority: 'high',
        start: new Date(Date.now() + 1000 * 10).toISOString(), // will become Active soon
        end: new Date(Date.now() + 1000 * 60 * 60).toISOString(),
        created_at: new Date().toISOString()
    },
    {
        id: generateId(),
        title: 'Promo: 50% off installation fee',
        content: 'For the month of September, new customers get 50% off installation fee. Terms apply.',
        audience: 'customers',
        priority: 'normal',
        start: '',
        end: '',
        created_at: new Date().toISOString()
    },
    {
        id: generateId(),
        title: 'Routing change: team meeting',
        content: 'All technicians: team meeting on Friday at 9AM. Expect schedule adjustments.',
        audience: 'technicians',
        priority: 'normal',
        start: '',
        end: '',
        created_at: new Date().toISOString()
    }
];

// util
function generateId() {
    return 'ANN-' + Math.random().toString(36).slice(2,9).toUpperCase();
}

function nowISO() {
    return new Date().toISOString();
}

/* --- Storage helpers --- */
function loadAnnouncements() {
    const raw = localStorage.getItem(LS_KEY);
    if (!raw) {
        localStorage.setItem(LS_KEY, JSON.stringify(seed));
        return seed.slice();
    }
    try {
        return JSON.parse(raw);
    } catch (e) {
        localStorage.setItem(LS_KEY, JSON.stringify(seed));
        return seed.slice();
    }
}

function saveAnnouncements(arr) {
    localStorage.setItem(LS_KEY, JSON.stringify(arr));
}

/* --- Status engine --- */
function computeStatus(a) {
    // a.start/a.end may be empty strings
    const now = new Date();
    if (a.start) {
        const s = new Date(a.start);
        if (s > now) return 'Scheduled';
    }
    if (a.end) {
        const e = new Date(a.end);
        if (e <= now) return 'Expired';
    }
    if (a.start) {
        const s = new Date(a.start);
        if (s <= now) return 'Active';
    }
    // if no schedule, we consider it Active by default
    return 'Active';
}

/* --- State --- */
let announcements = loadAnnouncements();
let filtered = [];
let currentPage = 1;

/* --- Elements --- */
const tbody = document.getElementById('annTbody');
const resultsInfo = document.getElementById('resultsInfo');
const pageInfo = document.getElementById('pageInfo');
const paginationDiv = document.getElementById('pagination');
const modal = document.getElementById('modal');
const annForm = document.getElementById('annForm');
const btnNew = document.getElementById('btn-new');
const modalClose = document.getElementById('modalClose');
const cancelBtn = document.getElementById('cancelBtn');
const saveBtn = document.getElementById('saveBtn');
const previewBtn = document.getElementById('previewBtn');
const previewArea = document.getElementById('previewArea');

const searchInput = document.getElementById('searchInput');
const audienceFilter = document.getElementById('audienceFilter');
const statusFilter = document.getElementById('statusFilter');
const priorityFilter = document.getElementById('priorityFilter');

/* --- Init --- */
syncStatus();
render();

btnNew.addEventListener('click', () => openModal('new'));
modalClose.addEventListener('click', closeModal);
cancelBtn.addEventListener('click', closeModal);

/* prevent normal form submit */
annForm.addEventListener('submit', (e) => {
    e.preventDefault();
    saveAnnouncement();
});

previewBtn.addEventListener('click', () => {
    const t = document.getElementById('title').value.trim();
    const c = document.getElementById('content').value.trim();
    previewArea.classList.remove('hidden');
    previewArea.innerHTML = `<h3 class="font-semibold">${escapeHtml(t || 'Preview')}</h3>
                             <div class="mt-2 text-sm">${escapeHtml(c || '(no content)')}</div>`;
});

/* filters */
searchInput.addEventListener('input', () => { currentPage = 1; render(); });
audienceFilter.addEventListener('change', () => { currentPage = 1; render(); });
statusFilter.addEventListener('change', () => { currentPage = 1; render(); });
priorityFilter.addEventListener('change', () => { currentPage = 1; render(); });

/* recurring check: update statuses and auto-publish */
setInterval(() => {
    syncStatus();
    render(); // keep UI updated for scheduled items
}, 3000);

/* --- Functions --- */

function syncStatus() {
    announcements.forEach(a => {
        a.status = computeStatus(a);
    });
    saveAnnouncements(announcements);
}

function render() {
    // apply search + filters
    const q = (searchInput.value || '').toLowerCase().trim();
    const aud = audienceFilter.value;
    const st = statusFilter.value;
    const pr = priorityFilter.value;

    filtered = announcements.filter(a => {
        if (aud && a.audience !== aud) return false;
        if (pr && a.priority !== pr) return false;
        if (st) {
            const s = computeStatus(a);
            if (s !== st) return false;
        }
        if (!q) return true;
        return (a.title + ' ' + a.content).toLowerCase().includes(q);
    });

    resultsInfo.textContent = `Showing ${filtered.length} announcement${filtered.length !== 1 ? 's' : ''}`;

    // pagination
    const total = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));
    if (currentPage > total) currentPage = total;
    const start = (currentPage - 1) * PAGE_SIZE;
    const pageSlice = filtered.slice(start, start + PAGE_SIZE);

    // build rows
    tbody.innerHTML = pageSlice.map(a => {
        const status = computeStatus(a);
        const statusClass = status === 'Scheduled' ? 'bg-yellow-100 text-yellow-700' :
                            status === 'Active' ? 'bg-green-100 text-green-700' :
                            'bg-gray-100 text-gray-600';
        const audLabel = a.audience === 'all' ? 'All' : a.audience.charAt(0).toUpperCase() + a.audience.slice(1);
        const startText = a.start ? new Date(a.start).toLocaleString() : '—';
        const endText = a.end ? new Date(a.end).toLocaleString() : '—';
        const priorityBadge = a.priority === 'high' ? '<span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">High</span>' : '<span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">Normal</span>';

        return `
            <tr class="hover:bg-gray-50 transition">
                <td class="p-3">
                    <div class="font-semibold">${escapeHtml(a.title)}</div>
                    <div class="text-xs text-gray-500 mt-1">${escapeHtml(a.content.slice(0, 110))}${a.content.length>110? '...' : ''}</div>
                </td>
                <td class="p-3">${audLabel}</td>
                <td class="p-3">${escapeHtml(startText)} <span class="text-gray-400">→</span> ${escapeHtml(endText)}</td>
                <td class="p-3">${priorityBadge}</td>
                <td class="p-3"><span class="${statusClass} px-2 py-1 rounded-full text-xs font-semibold">${status}</span></td>
              <td class="p-3 text-center">
    <div class="flex justify-center space-x-4">
        <!-- View -->
        <button onclick="viewAnnouncement('${a.id}')" 
            class="flex items-center text-blue-600 hover:text-blue-800 transition text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 
                         9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            View
        </button>

        <!-- Edit -->
        <button onclick="editAnnouncement('${a.id}')" 
            class="flex items-center text-yellow-600 hover:text-yellow-700 transition text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-5-9l5 5M13 6l7 7" />
            </svg>
            Edit
        </button>

        <!-- Delete -->
        <button onclick="deleteAnnouncement('${a.id}')" 
            class="flex items-center text-red-600 hover:text-red-700 transition text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0h-2m-8 0H5" />
            </svg>
            Delete
        </button>
    </div>
</td>
`;
    }).join('');

    // pagination buttons
    buildPagination(Math.ceil(filtered.length / PAGE_SIZE) || 1);
}

function buildPagination(totalPages) {
    paginationDiv.innerHTML = '';
    // prev
    const prevBtn = elt('button', { class: 'px-3 py-1 bg-gray-100 hover:bg-gray-200 text-sm rounded' , onclick: () => { if (currentPage>1) { currentPage--; render(); } } }, 'Prev');
    paginationDiv.appendChild(prevBtn);

    // pages (compact)
    const start = Math.max(1, currentPage - 2);
    const end = Math.min(totalPages, currentPage + 2);
    for (let p = start; p <= end; p++) {
        const active = p === currentPage;
        const cls = active ? 'px-3 py-1 bg-indigo-600 text-white rounded' : 'px-3 py-1 bg-gray-100 hover:bg-gray-200 text-sm rounded';
        const btn = elt('button', { class: cls, onclick: (() => { currentPage = p; render(); }) }, p.toString());
        paginationDiv.appendChild(btn);
    }

    // next
    const nextBtn = elt('button', { class: 'px-3 py-1 bg-gray-100 hover:bg-gray-200 text-sm rounded', onclick: () => { if (currentPage < totalPages) { currentPage++; render(); } } }, 'Next');
    paginationDiv.appendChild(nextBtn);

    pageInfo.textContent = `Page ${currentPage} of ${totalPages || 1}`;
}

/* CRUD actions */

function openModal(mode='new', ann = null) {
    document.getElementById('modalTitle').textContent = mode === 'new' ? 'New Announcement' : 'Edit Announcement';
    document.getElementById('annId').value = ann ? ann.id : '';
    document.getElementById('title').value = ann ? ann.title : '';
    document.getElementById('audience').value = ann ? ann.audience : 'customers';
    document.getElementById('priority').value = ann ? ann.priority : 'normal';
    document.getElementById('startDate').value = ann && ann.start ? isoLocal(ann.start) : '';
    document.getElementById('endDate').value = ann && ann.end ? isoLocal(ann.end) : '';
    document.getElementById('content').value = ann ? ann.content : '';
    document.getElementById('titleError').classList.add('hidden');
    document.getElementById('contentError').classList.add('hidden');
    document.getElementById('dateError').classList.add('hidden');
    previewArea.classList.add('hidden');

    modal.classList.remove('hidden');
    modal.querySelector('input, textarea, select, button').focus();
}

function closeModal() {
    modal.classList.add('hidden');
}

function saveAnnouncement() {
    const id = document.getElementById('annId').value;
    const title = document.getElementById('title').value.trim();
    const content = document.getElementById('content').value.trim();
    const audience = document.getElementById('audience').value;
    const priority = document.getElementById('priority').value;
    const start = document.getElementById('startDate').value;
    const end = document.getElementById('endDate').value;

    // validation
    let ok = true;
    if (!title) { document.getElementById('titleError').classList.remove('hidden'); ok = false; } else document.getElementById('titleError').classList.add('hidden');
    if (!content) { document.getElementById('contentError').classList.remove('hidden'); ok = false; } else document.getElementById('contentError').classList.add('hidden');
    if (start && end && new Date(start) >= new Date(end)) { document.getElementById('dateError').classList.remove('hidden'); ok = false; } else document.getElementById('dateError').classList.add('hidden');
    if (!ok) return;

    if (id) {
        // update
        const idx = announcements.findIndex(a => a.id === id);
        if (idx !== -1) {
            announcements[idx].title = title;
            announcements[idx].content = content;
            announcements[idx].audience = audience;
            announcements[idx].priority = priority;
            announcements[idx].start = start ? new Date(start).toISOString() : '';
            announcements[idx].end = end ? new Date(end).toISOString() : '';
            // keep created_at
            announcements[idx].updated_at = nowISO();
        }
        showToast('Announcement updated');
    } else {
        const a = {
            id: generateId(),
            title,
            content,
            audience,
            priority,
            start: start ? new Date(start).toISOString() : '',
            end: end ? new Date(end).toISOString() : '',
            created_at: nowISO()
        };
        announcements.unshift(a);
        showToast('Announcement created');
    }

    saveAnnouncements(announcements);
    syncStatus();
    closeModal();
    currentPage = 1;
    render();
}

function editAnnouncement(id) {
    const a = announcements.find(x => x.id === id);
    if (!a) return alert('Announcement not found');
    openModal('edit', a);
}

function viewAnnouncement(id) {
    const a = announcements.find(x => x.id === id);
    if (!a) return alert('Announcement not found');

    openModal('view');
    document.getElementById('modalTitle').textContent = 'View Announcement';

    // hide form, show view area
    annForm.classList.add('hidden');
    document.getElementById('viewArea').classList.remove('hidden');

    // fill fields
    document.getElementById('viewTitle').textContent = a.title;
    document.getElementById('viewContent').innerHTML = escapeHtml(a.content);

    document.getElementById('viewAudience').textContent = `Audience: ${a.audience}`;
    document.getElementById('viewPriority').textContent = `Priority: ${a.priority}`;

    document.getElementById('viewStart').textContent =
        "Start: " + (a.start ? new Date(a.start).toLocaleString() : "Not set");
    document.getElementById('viewEnd').textContent =
        "End: " + (a.end ? new Date(a.end).toLocaleString() : "Not set");

    // compute status badge
    const status = computeStatus(a);
    const statusEl = document.getElementById('viewStatus');

    let statusClass = "bg-gray-100 text-gray-600";
    if (status === "Active") statusClass = "bg-green-100 text-green-700";
    if (status === "Scheduled") statusClass = "bg-yellow-100 text-yellow-700";
    if (status === "Expired") statusClass = "bg-red-100 text-red-700";

    statusEl.className = `px-2 py-1 rounded-full text-xs font-semibold ${statusClass}`;
    statusEl.textContent = `Status: ${status}`;
}


function closeModal() {
    modal.classList.add('hidden');
    annForm.classList.remove('hidden');
    document.getElementById('viewArea').classList.add('hidden');
}

function deleteAnnouncement(id) {
    if (!confirm('Delete this announcement?')) return;
    announcements = announcements.filter(a => a.id !== id);
    saveAnnouncements(announcements);
    render();
    showToast('Announcement deleted');
}

/* Utilities */
function showToast(msg) {
    const box = document.getElementById('toastBox');
    const el = document.createElement('div');
    el.className = 'bg-black text-white px-4 py-2 rounded shadow';
    el.textContent = msg;
    box.appendChild(el);
    setTimeout(() => {
        el.classList.add('opacity-0');
        setTimeout(() => el.remove(), 350);
    }, 2800);
}

function elt(tag, attrs = {}, text = '') {
    const e = document.createElement(tag);
    Object.entries(attrs).forEach(([k, v]) => {
        if (k === 'onclick') e.addEventListener('click', v);
        else e.setAttribute(k, v);
    });
    if (text) e.textContent = text;
    return e;
}

function escapeHtml(s = '') {
    return String(s)
        .replaceAll('&','&amp;')
        .replaceAll('<','&lt;')
        .replaceAll('>','&gt;')
        .replaceAll('"','&quot;')
        .replaceAll("'",'&#039;')
        .replaceAll('\n','<br>');
}

function isoLocal(iso) {
    // convert ISO to input datetime-local value
    const d = new Date(iso);
    const tzoffset = d.getTimezoneOffset() * 60000;
    const localISO = new Date(d - tzoffset).toISOString().slice(0,16);
    return localISO;
}

/* ensure saveBtn visible again after view */
modal.addEventListener('transitionend', () => {
    saveBtn.style.display = '';
    previewBtn.style.display = '';
});

/* close modal on escape */
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
});

/* initial render helper */
function initIfEmpty() {
    if (!localStorage.getItem(LS_KEY)) {
        saveAnnouncements(seed);
        announcements = loadAnnouncements();
    }
}
initIfEmpty();
render();
</script>

@endsection
