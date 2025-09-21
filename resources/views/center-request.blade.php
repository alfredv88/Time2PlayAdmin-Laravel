<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Centre Requests</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Fonts & Styles -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ url('public/assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ url('public/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/adminlte.min.css') }}">
    <link rel="icon" href="{{ url('public/assets/dist/img/favicon.png') }}" type="image/x-icon">

    <!-- Firebase (compat) -->
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore-compat.js"></script>
    <!-- jsPDF export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

    <style>
        /* keep the loader layout static, toggle visibility with a class */
        #tableLoader {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            background: rgba(255, 255, 255, 0.85);
            z-index: 5;
        }

        #tableLoader.hidden {
            display: none !important;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('inc.header')
        @include('inc.aside')

        <div class="content-wrapper p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-start mb-4">
                <div class="w-100 d-flex justify-content-between align-items-start flex-wrap">
                    <div>
                        <h3 class="font-weight-bold mb-1">Centre Requests</h3>
                        <p class="text-muted mb-0">Review and manage pending centre submissions</p>
                    </div>

                    <!-- Export Button -->
                    <div>
                        <button id="exportBtn" class="btn btn-primary" style="border-radius: 10px;">
                            <i class="fas fa-download mr-1"></i> Export
                        </button>
                    </div>
                </div>

                <!-- Search + Summary -->
                <div class="w-100 d-flex justify-content-between align-items-center flex-wrap mt-3">
                    <!-- Search box -->
                    <div class="flex-grow-1 me-3" style="max-width: 320px;">
                        <input id="tableSearch" type="text" class="form-control" placeholder="🔍 Search centre...">
                    </div>

                    <!-- Status summary boxes -->
                    <div
                        class="mt-2 col-12 col-md-6 d-flex flex-wrap gap-2 justify-content-start justify-content-md-end">
                        <!-- Pending -->
                        <div class="mb-2 text-center px-4 py-2 rounded" style="background-color:#FFF5CC;">
                            <div id="countPending" class="font-weight-bold" style="color:#B76E00;font-size:18px;">0
                            </div>
                            <small style="color:#B76E00;">Pending</small>
                        </div>
                        <!-- Approved -->
                        <div class="mb-2 mx-2 text-center px-4 py-2 rounded" style="background-color:#E3FBEB;">
                            <div id="countApproved" class="font-weight-bold" style="color:#127947;font-size:18px;">0
                            </div>
                            <small style="color:#127947;">Approved</small>
                        </div>
                        <!-- Rejected -->
                        <div class="mb-2 text-center px-4 py-2 rounded" style="background-color:#FDE6E6;">
                            <div id="countRejected" class="font-weight-bold" style="color:#E63946;font-size:18px;">0
                            </div>
                            <small style="color:#E63946;">Rejected</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DataTable -->
            <div class="card pos-relative">
                <div id="tableLoader" class="hidden">
                    <div class="spinner-border text-primary" role="status"></div>
                    <div class="mt-2 text-muted">Loading centres…</div>
                </div>
                <div class="card-body">
                    <table id="centreTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Centre Details</th>
                                <th>Date</th>
                                <th>Submitted By</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th style="min-width:200px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="centreTbody">
                            <tr>
                                <td colspan="6" class="text-center text-muted">Loading…</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @include('inc.footer')
    </div>

    <!-- Scripts -->
    <script src="{{ url('public/assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ url('public/assets/dist/js/adminlte.min.js') }}"></script>

    <script>
        // ---------- Firebase ----------
        const firebaseConfig = {
            apiKey: "AIzaSyDtnVhbX6cczcCydCB1DLxrAJVQWU2H8S0",
            authDomain: "time2play-ed370.firebaseapp.com",
            projectId: "time2play-ed370",
            messagingSenderId: "988354704853",
            appId: "1:988354704853:web:YOUR_WEB_APP_ID"
        };
        if (!firebase.apps.length) firebase.initializeApp(firebaseConfig);
        const db = firebase.firestore();

        // ---------- Helpers ----------
        const $loader = document.getElementById('tableLoader');

        function showLoader(b) {
            $loader.style.display = b ? 'flex' : 'none';
        }

        function esc(s) {
            return String(s ?? '').replace(/[&<>"']/g, c => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;'
            } [c]));
        }

        function fmtDate(ts) {
            if (!ts) return '-';
            let d = ts.toDate ? ts.toDate() : new Date(ts);
            const opts = {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            return d.toLocaleDateString(undefined, opts);
        }

        function timeAgo(ts) {
            if (!ts) return '';
            const d = ts.toDate ? ts.toDate() : new Date(ts);
            const diff = (Date.now() - d.getTime()) / 1000;
            const days = Math.floor(diff / 86400);
            const hours = Math.floor((diff % 86400) / 3600);
            if (days > 0) return `${days} day${days>1?'s':''} ago`;
            if (hours > 0) return `${hours} hour${hours>1?'s':''} ago`;
            return 'Just now';
        }

        function statusBadge(status) {
            const s = String(status || '').toLowerCase();
            if (s === 'approved') return '<span class="badge bg-success">Approved</span>';
            if (s === 'rejected') return '<span class="badge bg-danger">Rejected</span>';
            return '<span class="badge bg-warning">Pending</span>';
        }

        function sportsBadges(arr) {
            if (!Array.isArray(arr) || !arr.length) return '';
            return arr.slice(0, 3).map((n, i) => {
                const color = i === 0 ? '#0d6efd' : (i === 1 ? '#8b5cf6' : '#10b981');
                const extraClass = i === 0 ? 'bg-primary' : 'text-white';
                return `<span class="badge ${i===0?'bg-primary': ''} ${i>0?'': 'text-white'} mr-1" style="background-color:${color};">${esc(n)}</span>`;
            }).join(' ');
        }

        // Batch fetch users by uid (in chunks of 10)
        async function fetchUsersMap(uids) {
            const map = {};
            const chunks = [];
            for (let i = 0; i < uids.length; i += 10) chunks.push(uids.slice(i, i + 10));
            for (const chunk of chunks) {
                const snap = await db.collection('users').where('uid', 'in', chunk).get();
                snap.forEach(doc => {
                    const d = doc.data();
                    if (d && d.uid) {
                        map[d.uid] = d;
                    }
                });
            }
            return map;
        }

        // ---------- Rendering ----------
        let dt = null;
        let rowsCache = []; // for export

        function buildRowHtml(field, user) {
            const img = Array.isArray(field.images) && field.images.length ? field.images[0] : '';
            const name = field.location?.name || field.name || '—';
            const sports = sportsBadges(field.sports);
            const created = fmtDate(field.createdAt);
            const ago = timeAgo(field.createdAt);
            const uName = user?.fullName || user?.email || 'Unknown';
            const uId = user?.uid || field.uid || '—';
            const address = field.location?.address || '—';
            const stat = field.status || 'pending';

            return `
        <tr data-id="${esc(field._id)}">
            <td>
                <div class="d-flex align-items-center">
                    <div class="bg-secondary text-white rounded d-flex justify-content-center align-items-center" style="width:40px;height:40px;overflow:hidden;">
                        ${img ? `<img src="${esc(img)}" alt="" style="width:40px;height:40px;object-fit:cover;border-radius:4px;">` : `<i class="fas fa-image"></i>`}
                    </div>
                    <div class="ml-2">
                        <div class="fw-bold">${esc(name)}</div>
                        <div>${sports}</div>
                    </div>
                </div>
            </td>
            <td>
                <div>${created}</div>
                <small class="text-muted">${ago}</small>
            </td>
            <td>
                <div class="fw-semibold">${esc(uName)}</div>
                <small class="text-muted">ID: ${esc(uId)}</small>
            </td>
            <td>
                <div class="fw-semibold">${esc(address)}</div>
                <small class="text-muted">${field.location?.city || ''}</small>
            </td>
            <td class="status-cell">${statusBadge(stat)}</td>
            <td>
                <div class="d-flex">
                    <a href="{{ url('center-request-details') }}/${esc(field._id)}" class="btn btn-sm btn-primary">View</a>
                    <button class="btn btn-sm btn-success mx-2 act-approve" data-id="${esc(field._id)}">Approve</button>
                    <button class="btn btn-sm btn-danger act-reject" data-id="${esc(field._id)}">Reject</button>
                </div>
            </td>
        </tr>`;
        }

        function updateSummaryCounts(list) {
            const p = list.filter(x => (x.status || 'pending') === 'pending').length;
            const a = list.filter(x => x.status === 'approved').length;
            const r = list.filter(x => x.status === 'rejected').length;
            document.getElementById('countPending').textContent = p;
            document.getElementById('countApproved').textContent = a;
            document.getElementById('countRejected').textContent = r;
        }

        async function loadCentres() {
            showLoader(true);

            // 1) Get fields
            const snap = await db.collection('fields').orderBy('createdAt', 'desc').get();
            const fields = snap.docs.map(d => ({
                _id: d.id,
                ...d.data()
            }));

            // 2) Join users by uid
            const uniqUids = [...new Set(fields.map(f => f.uid).filter(Boolean))];
            const usersMap = uniqUids.length ? await fetchUsersMap(uniqUids) : {};

            // 3) Build rows
            const tbody = document.getElementById('centreTbody');
            tbody.innerHTML = fields.map(f => buildRowHtml(f, usersMap[f.uid])).join('');

            // cache for export
            rowsCache = fields.map(f => ({
                centre: f.location?.name || f.name || '',
                sports: Array.isArray(f.sports) ? f.sports.join(', ') : '',
                date: fmtDate(f.createdAt),
                submittedBy: (usersMap[f.uid]?.fullName || usersMap[f.uid]?.email || 'Unknown') +
                    ` (${f.uid||''})`,
                location: f.location?.address || '',
                status: (f.status || 'pending')
            }));

            updateSummaryCounts(fields);

            // 4) Init or refresh DataTable
            if (!$.fn.DataTable.isDataTable('#centreTable')) {
                dt = $('#centreTable').DataTable({
                    responsive: true,
                    autoWidth: false,
                    searching: true,
                    lengthChange: true,
                    order: [
                        [1, 'desc']
                    ]
                });
            } else {
                dt.clear();
                $('#centreTable tbody tr').each(function() {
                    dt.row.add(this);
                });
                dt.draw(false);
            }

            showLoader(false);
        }

        // Search box -> DataTable search
        document.getElementById('tableSearch').addEventListener('input', function() {
            if (dt) dt.search(this.value).draw();
        });

        // Approve / Reject actions
        document.addEventListener('click', async (e) => {
            const approveBtn = e.target.closest('.act-approve');
            const rejectBtn = e.target.closest('.act-reject');
            if (!approveBtn && !rejectBtn) return;

            const id = (approveBtn || rejectBtn).getAttribute('data-id');
            const newStatus = approveBtn ? 'approved' : 'rejected';
            if (!confirm(`Mark this centre as ${newStatus}?`)) return;

            try {
                // update in Firestore
                await db.collection('fields').doc(id).set({
                    status: newStatus,
                    updatedAt: firebase.firestore.FieldValue.serverTimestamp()
                }, {
                    merge: true
                });

                // update badge in row
                const row = document.querySelector(`tr[data-id="${id}"] .status-cell`);
                if (row) row.innerHTML = (newStatus === 'approved') ?
                    '<span class="badge bg-success">Approved</span>' :
                    (newStatus === 'rejected') ? '<span class="badge bg-danger">Rejected</span>' :
                    '<span class="badge bg-warning">Pending</span>';

                // refresh counts quickly (lightweight: re-scan DOM)
                const all = Array.from(document.querySelectorAll('#centreTbody tr')).map(tr => {
                    const s = tr.querySelector('.status-cell')?.innerText?.trim().toLowerCase() ||
                        'pending';
                    return {
                        status: s
                    };
                });
                updateSummaryCounts(all.map(x => ({
                    status: x.status
                })));
            } catch (err) {
                alert(err?.message || 'Update failed');
            }
        });

        // Export to PDF (current filtered table)
        document.getElementById('exportBtn').addEventListener('click', function() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF({
                orientation: 'landscape'
            });
            doc.setFontSize(14);
            doc.text('Centre Requests', 14, 16);

            // get filtered rows from DataTable (fallback to cache if dt missing)
            let rows = [];
            if (dt) {
                const nodes = dt.rows({
                    search: 'applied'
                }).nodes().to$();
                nodes.each(function() {
                    const tds = $(this).find('td');
                    const centre = tds.eq(0).find('.fw-bold').text().trim();
                    const sports = tds.eq(0).find('.badge').map(function() {
                        return $(this).text().trim();
                    }).get().join(', ');
                    const date = tds.eq(1).find('div').text().trim();
                    const submitted = tds.eq(2).text().replace(/\s+/g, ' ').trim();
                    const location = tds.eq(3).find('.fw-semibold').text().trim();
                    const status = tds.eq(4).text().trim();
                    rows.push([centre, sports, date, submitted, location, status]);
                });
            }
            if (!rows.length && rowsCache.length) {
                rows = rowsCache.map(r => [r.centre, r.sports, r.date, r.submittedBy, r.location, r.status]);
            }

            doc.autoTable({
                head: [
                    ['Centre', 'Sports', 'Date', 'Submitted By', 'Location', 'Status']
                ],
                body: rows,
                startY: 22,
                styles: {
                    fontSize: 9
                }
            });
            doc.save('centre-requests.pdf');
        });

        // Init
        (async function() {
            showLoader(true);
            await loadCentres();
        })();
    </script>
</body>

</html>







{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Centre Requests</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Fonts & Styles -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ url('public/assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ url('public/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/adminlte.min.css') }}">
    <link rel="icon" href="{{ url('public/assets/dist/img/favicon.png') }}" type="image/x-icon">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('inc.header')
        @include('inc.aside')

        <div class="content-wrapper p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-start mb-4">
                <!-- Left section: Title + Subtext -->
                <div class="w-100 d-flex justify-content-between align-items-start flex-wrap">
                    <div>
                        <h3 class="font-weight-bold mb-1">Centre Requests</h3>
                        <p class="text-muted mb-0">Review and manage pending centre submissions</p>
                    </div>

                    <!-- Export Button -->
                    <div>
                        <a href="#" class="btn btn-primary" style="border-radius: 10px;">
                            <i class="fas fa-download mr-1"></i> Export
                        </a>
                    </div>
                </div>

                <!-- Second row: Search + Summary boxes -->
                <div class="w-100 d-flex justify-content-between align-items-center flex-wrap mt-3">
                    <!-- Search box -->
                    <div class="flex-grow-1 me-3" style="max-width: 320px;">
                        <input type="text" class="form-control" placeholder="🔍 Search centre...">
                    </div>


                    <!-- Status summary boxes -->
                    <!-- Summary boxes -->
                    <div
                        class="mt-2 col-12 col-md-6 d-flex flex-wrap gap-2 justify-content-start justify-content-md-end">
                        <!-- Pending -->
                        <div class="mb-2 text-center px-4 py-2 rounded" style="background-color: #FFF5CC;">
                            <div class="font-weight-bold" style="color: #B76E00; font-size: 18px;">24</div>
                            <small style="color: #B76E00;">Pending</small>
                        </div>

                        <!-- Approved -->
                        <div class="mb-2 mx-2 ext-center px-4 py-2 rounded" style="background-color: #E3FBEB;">
                            <div class="font-weight-bold" style="color: #127947; font-size: 18px;">156</div>
                            <small style="color: #127947;">Approved</small>
                        </div>

                        <!-- Rejected -->
                        <div class="mb-2 text-center px-4 py-2 rounded" style="background-color: #FDE6E6;">
                            <div class="font-weight-bold" style="color: #E63946; font-size: 18px;">8</div>
                            <small style="color: #E63946;">Rejected</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DataTable -->
            <div class="card">
                <div class="card-body">
                    <table id="centreTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Centre Details</th>
                                <th>Date</th>
                                <th>Submitted By</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 10; $i++)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-secondary text-white rounded d-flex justify-content-center align-items-center"
                                                style="width: 40px; height: 40px;">
                                                <i class="fas fa-image"></i>
                                            </div>
                                            <div class="ml-2">
                                                <div class="fw-bold">Downtown Sports Arena</div>
                                                <span class="badge bg-primary me-1">Football</span>
                                                <span class="badge text-white"
                                                    style="background-color:#8b5cf6;">Basketball</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>March 22, 2025</div>
                                        <small class="text-muted">2 days ago</small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">John Smith</div>
                                        <small class="text-muted">ID: 12345</small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">New York, NY</div>
                                        <small class="text-muted">Manhattan District</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">Pending</span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ url('center-request-details') }}"
                                                class="btn btn-sm btn-primary">View</a>
                                            <a href="#" class="btn btn-sm btn-success mx-2">Approve</a>
                                            <a href="#" class="btn btn-sm btn-danger">Reject</a>
                                        </div>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @include('inc.footer')
    </div>

    <!-- Scripts -->
    <script src="{{ url('public/assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ url('public/assets/dist/js/adminlte.min.js') }}"></script>

    <script>
        $(function() {
            $('#centreTable').DataTable({
                responsive: true,
                autoWidth: false,
                searching: false, // 🔴 hides the top-right search box
                lengthChange: false // 🔴 hides the top-left record length dropdown
            });
        });
    </script>


</body>

</html> --}}
