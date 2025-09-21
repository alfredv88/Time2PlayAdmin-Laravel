<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Time2Play</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ url('public/assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/adminlte.min.css') }}">
    <link rel="icon" type="image/png" href="{{ url('public/assets/dist/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/varibales.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Firebase (compat) -->
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore-compat.js"></script>

    <style>
        .loader-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, .8);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            z-index: 5;
        }

        .pos-relative {
            position: relative;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('inc.header')
        @include('inc.aside')

        <div class="content-wrapper" style="background:#F4F4F6">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><b>Dashboard</b></h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">

                    <div class="row">
                        <!-- Total Earning (from subscriptions of selected year) -->
                        <div class="col-xl-3 col-md-6 col-sm-12 mb-4">
                            <a href="#" style="text-decoration:none;">
                                <div
                                    style="display:flex;align-items:center;background:#fff;padding:20px;border-radius:16px;">
                                    <div
                                        style="background-color:var(--buttons-primary-color);border-radius:50%;padding:15px;">
                                        <i class="fas fa-chart-bar text-white" style="font-size:20px;"></i>
                                    </div>
                                    <div style="margin-left:15px;">
                                        <p class="mb-0 text-muted" style="font-size:14px;">Total Earning</p>
                                        <div class="d-flex align-items-center">
                                            <h3 id="totalEarningAmount" class="mb-0 text-dark font-weight-bold me-2">
                                                $0.00</h3>
                                            <span id="totalEarningSpinner"
                                                class="spinner-border spinner-border-sm text-secondary" role="status"
                                                style="display:none;"></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Total Users -->
                        <div class="col-xl-3 col-md-6 col-sm-12 mb-4">
                            <a href="{{ url('/users') }}" style="text-decoration:none;">
                                <div
                                    style="display:flex;align-items:center;background:#fff;padding:20px;border-radius:16px;">
                                    <div
                                        style="background-color:var(--buttons-primary-color);border-radius:50%;padding:15px;">
                                        <i class="fas fa-users text-white" style="font-size:20px;"></i>
                                    </div>
                                    <div style="margin-left:15px;">
                                        <p class="mb-0 text-muted" style="font-size:14px;">Total Users</p>
                                        <div class="d-flex align-items-center">
                                            <h3 id="totalUsersCount" class="mb-0 text-dark font-weight-bold me-2">—</h3>
                                            <span id="totalUsersSpinner"
                                                class="spinner-border spinner-border-sm text-secondary"
                                                role="status"></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Active Users -->
                        <div class="col-xl-3 col-md-6 col-sm-12 mb-4">
                            <a href="{{ url('/users/active') }}" style="text-decoration:none;">
                                <div
                                    style="display:flex;align-items:center;background:#fff;padding:20px;border-radius:16px;">
                                    <div
                                        style="background-color:var(--buttons-primary-color);border-radius:50%;padding:15px;">
                                        <i class="fas fa-users text-white" style="font-size:20px;"></i>
                                    </div>
                                    <div style="margin-left:15px;">
                                        <p class="mb-0 text-muted" style="font-size:14px;">Active Users</p>
                                        <div class="d-flex align-items-center">
                                            <h3 id="activeUsersCount" class="mb-0 text-dark font-weight-bold me-2">—
                                            </h3>
                                            <span id="activeUsersSpinner"
                                                class="spinner-border spinner-border-sm text-secondary"
                                                role="status"></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Blocked Users -->
                        <div class="col-xl-3 col-md-6 col-sm-12 mb-4">
                            <a href="{{ url('/users/blocked') }}" style="text-decoration:none;">
                                <div
                                    style="display:flex;align-items:center;background:#fff;padding:20px;border-radius:16px;">
                                    <div
                                        style="background-color:var(--buttons-primary-color);border-radius:50%;padding:15px;">
                                        <i class="fas fa-user-slash text-white" style="font-size:20px;"></i>
                                    </div>
                                    <div style="margin-left:15px;">
                                        <p class="mb-0 text-muted" style="font-size:14px;">Blocked Users</p>
                                        <div class="d-flex align-items-center">
                                            <h3 id="blockedUsersCount" class="mb-0 text-dark font-weight-bold me-2">—
                                            </h3>
                                            <span id="blockedUsersSpinner"
                                                class="spinner-border spinner-border-sm text-secondary"
                                                role="status"></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Signups Chart -->
                    <div id="signupsCard" class="mt-4 pos-relative"
                        style="background:#fff;padding:20px;border-radius:16px;">
                        <div id="signupsLoader" class="loader-overlay" style="display:flex;">
                            <div class="spinner-border text-primary" role="status"></div>
                            <div class="mt-2 text-muted">Loading data…</div>
                        </div>

                        <div class="row justify-content-between align-items-center">
                            <div class="col-md-6">
                                <h4 class="mb-2">
                                    New User Sign-ups Overview<br>
                                    <span style="color:var(--buttons-primary-color);font-size:1.25rem;"
                                        class="font-weight-bolder" id="signupsTotal">0</span>
                                </h4>
                            </div>
                            <div class="col-md-3 text-end">
                                <select id="yearSelect" class="form-select"
                                    style="border-radius:8px;padding:6px 12px;">
                                    @for ($y = now()->year; $y >= 2020; $y--)
                                        <option value="{{ $y }}"
                                            {{ $selectedYear == $y ? 'selected' : '' }}>Year {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div id="signupsChart" class="mt-3"></div>
                    </div>

                    <!-- Earnings (from subscriptions) -->
                    <div id="earningsCard" class="mt-4 mb-4 pos-relative"
                        style="background:#fff;padding:20px;border-radius:16px;">
                        <div id="earningsLoader" class="loader-overlay" style="display:none;">
                            <div class="spinner-border text-primary" role="status"></div>
                            <div class="mt-2 text-muted">Loading earnings…</div>
                        </div>

                        <div class="row justify-content-between align-items-center">
                            <div class="col-md-6">
                                <h4 class="mb-2">
                                    Total Admin Earnings Insights<br>
                                    <span id="earningsTotal"
                                        style="color:var(--buttons-primary-color);font-size:1.25rem;"
                                        class="font-weight-bolder">$0.00</span>
                                </h4>
                            </div>
                            <div class="col-md-3 text-end">
                                <select id="earningsYearSelect" class="form-select"
                                    style="border-radius:8px;padding:6px 12px;">
                                    @for ($y = now()->year; $y >= 2020; $y--)
                                        <option value="{{ $y }}"
                                            {{ $selectedYear == $y ? 'selected' : '' }}>Year {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div id="earningsChart" class="mt-3"></div>
                    </div>

                </div>
            </section>
        </div>

        @include('inc.footer')
    </div>

    <script>
        // Firebase init
        const firebaseConfig = {
            apiKey: "AIzaSyDtnVhbX6cczcCydCB1DLxrAJVQWU2H8S0",
            authDomain: "time2play-ed370.firebaseapp.com",
            projectId: "time2play-ed370",
            messagingSenderId: "988354704853",
            appId: "1:988354704853:web:YOUR_WEB_APP_ID"
        };
        if (!firebase.apps.length) firebase.initializeApp(firebaseConfig);
        const db = firebase.firestore();

        // Chart color
        const chartColor = getComputedStyle(document.documentElement)
            .getPropertyValue('--buttons-primary-color').trim();

        // Signups chart
        const signupsChart = new ApexCharts(document.querySelector("#signupsChart"), {
            chart: {
                type: 'bar',
                height: 250
            },
            series: [{
                name: 'Signups',
                data: new Array(12).fill(0)
            }],
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            colors: [chartColor]
        });
        signupsChart.render();

        // Earnings chart
        const earningsChart = new ApexCharts(document.querySelector("#earningsChart"), {
            chart: {
                type: 'bar',
                height: 250
            },
            series: [{
                name: 'Earnings',
                data: new Array(12).fill(0)
            }],
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            colors: [chartColor]
        });
        earningsChart.render();

        // Loaders
        function toggleCountsLoading(show) {
            document.getElementById('totalUsersSpinner').style.display = show ? 'inline-block' : 'none';
            document.getElementById('activeUsersSpinner').style.display = show ? 'inline-block' : 'none';
            document.getElementById('blockedUsersSpinner').style.display = show ? 'inline-block' : 'none';
        }

        function toggleSignupsLoading(show) {
            document.getElementById('signupsLoader').style.display = show ? 'flex' : 'none';
            document.getElementById('yearSelect').disabled = !!show;
        }

        function toggleEarningsLoading(show) {
            document.getElementById('earningsLoader').style.display = show ? 'flex' : 'none';
            document.getElementById('earningsYearSelect').disabled = !!show;
            document.getElementById('totalEarningSpinner').style.display = show ? 'inline-block' : 'none';
        }

        // Counts
        async function loadUserCounts() {
            toggleCountsLoading(true);
            const snap = await db.collection('users').get();
            const all = snap.docs.map(d => d.data() || {});
            const total = all.length;
            const active = all.filter(u => u.isActive === true || u.status === true).length;
            const blocked = all.filter(u => u.isActive === false || u.status === false).length;
            document.getElementById('totalUsersCount').textContent = total;
            document.getElementById('activeUsersCount').textContent = active;
            document.getElementById('blockedUsersCount').textContent = blocked;
            toggleCountsLoading(false);
        }

        // Utilities for year ranges
        const startOfYear = y => new Date(y, 0, 1, 0, 0, 0, 0);
        const startOfNextYear = y => new Date(y + 1, 0, 1, 0, 0, 0, 0);

        // Signups by year
        async function loadYearlySignups(year) {
            toggleSignupsLoading(true);
            const q = db.collection('users')
                .where('createdAt', '>=', startOfYear(year))
                .where('createdAt', '<', startOfNextYear(year));
            const snap = await q.get();
            const monthly = new Array(12).fill(0);
            snap.forEach(doc => {
                const ts = doc.data().createdAt;
                if (ts && ts.toDate) {
                    const m = ts.toDate().getMonth();
                    monthly[m] += 1;
                }
            });
            document.getElementById('signupsTotal').textContent = monthly.reduce((a, b) => a + b, 0);
            await signupsChart.updateSeries([{
                name: 'Signups',
                data: monthly
            }], true);
            toggleSignupsLoading(false);
        }

        // Earnings by year (subscriptions)

        async function loadYearlyEarnings(year) {
            toggleEarningsLoading(true);

            const monthly = new Array(12).fill(0);
            let total = 0;

            // prevent double counting if a doc contains both schemas and matches both queries
            const seen = new Set();

            const from = startOfYear(year);
            const to = startOfNextYear(year);

            try {
                // ⚠️ No isActive filter here -> avoids composite index requirement
                const [snapA, snapB] = await Promise.all([
                    db.collection('subscriptions')
                    .where('subscribedAt', '>=', from)
                    .where('subscribedAt', '<', to)
                    .get(),
                    db.collection('subscriptions')
                    .where('startDate', '>=', from)
                    .where('startDate', '<', to)
                    .get()
                ]);

                // helper to accumulate safely
                function add(doc, whenField, preferredAmtField, fallbackAmtField) {
                    const id = doc.id;
                    const d = doc.data() || {};

                    // client-side isActive check
                    if (d.isActive !== true) return;

                    // if this doc already counted from the other schema, skip to avoid double counting
                    if (seen.has(id)) return;

                    const ts = d[whenField];
                    if (!ts || typeof ts.toDate !== 'function') return;

                    const amt = Number(d[preferredAmtField] ?? d[fallbackAmtField] ?? 0);
                    if (!isFinite(amt) || amt <= 0) return;

                    const m = ts.toDate().getMonth();
                    monthly[m] += amt;
                    total += amt;
                    seen.add(id);
                }

                snapA.forEach(doc => add(doc, 'subscribedAt', 'totalAmount', 'proPackageAmount'));
                snapB.forEach(doc => add(doc, 'startDate', 'proPackageAmount', 'totalAmount'));

                // Update UI
                document.getElementById('totalEarningAmount').textContent = '$' + total.toFixed(2);
                document.getElementById('earningsTotal').textContent = '$' + total.toFixed(2);
                await earningsChart.updateSeries([{
                    name: 'Earnings',
                    data: monthly
                }], true);

            } catch (err) {
                console.error(err);
                alert('Failed to load earnings. (Tip: create Firestore composite indexes for faster queries.)');
            } finally {
                // always hide loader
                toggleEarningsLoading(false);
            }
        }

        // Sync both sections to same year if you like:
        document.getElementById('yearSelect').addEventListener('change', async function() {
            const y = parseInt(this.value, 10);
            // also update earnings select to match
            document.getElementById('earningsYearSelect').value = String(y);
            await loadYearlySignups(y);
            await loadYearlyEarnings(y);
        });

        document.getElementById('earningsYearSelect').addEventListener('change', async function() {
            const y = parseInt(this.value, 10);
            // keep total earning (top-left card) in sync with earnings year
            await loadYearlyEarnings(y);
        });

        // Init
        (async function init() {
            const initialYear = parseInt(document.getElementById('yearSelect').value, 10);
            document.getElementById('earningsYearSelect').value = String(initialYear);

            toggleCountsLoading(true);
            toggleSignupsLoading(true);
            toggleEarningsLoading(true);

            await loadUserCounts();
            await loadYearlySignups(initialYear);
            await loadYearlyEarnings(initialYear);
        })();
    </script>

    <!-- Scripts -->
    <script src="{{ url('public/assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('public/assets/dist/js/adminlte.min.js') }}"></script>
</body>

</html>









{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Time2Play</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ url('public/assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/adminlte.min.css') }}">
    <link rel="icon" type="image/png" href="{{ url('public/assets/dist/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/varibales.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Firebase (compat) -->
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore-compat.js"></script>

    <style>
        /* card overlay for signups chart */
        .loader-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            z-index: 5;
        }

        .pos-relative {
            position: relative;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('inc.header')
        @include('inc.aside')

        <div class="content-wrapper" style="background:#F4F4F6">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><b>Dashboard</b></h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">

                    <div class="row">
                        <!-- Total Earning (dummy) -->
                        <div class="col-xl-3 col-md-6 col-sm-12 mb-4">
                            <a href="#" style="text-decoration:none;">
                                <div
                                    style="display:flex;align-items:center;background:#fff;padding:20px;border-radius:16px;">
                                    <div
                                        style="background-color:var(--buttons-primary-color);border-radius:50%;padding:15px;">
                                        <i class="fas fa-chart-bar text-white" style="font-size:20px;"></i>
                                    </div>
                                    <div style="margin-left:15px;">
                                        <p class="mb-0 text-muted" style="font-size:14px;">Total Earning</p>
                                        <h3 class="mb-0 text-dark font-weight-bold">$39.6</h3>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Total Users -->
                        <div class="col-xl-3 col-md-6 col-sm-12 mb-4">
                            <a href="{{ url('/users') }}" style="text-decoration:none;">
                                <div
                                    style="display:flex;align-items:center;background:#fff;padding:20px;border-radius:16px;">
                                    <div
                                        style="background-color:var(--buttons-primary-color);border-radius:50%;padding:15px;">
                                        <i class="fas fa-users text-white" style="font-size:20px;"></i>
                                    </div>
                                    <div style="margin-left:15px;">
                                        <p class="mb-0 text-muted" style="font-size:14px;">Total Users</p>
                                        <div class="d-flex align-items-center">
                                            <h3 id="totalUsersCount" class="mb-0 text-dark font-weight-bold me-2">—</h3>
                                            <span id="totalUsersSpinner"
                                                class="spinner-border spinner-border-sm text-secondary"
                                                role="status"></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Active Users -->
                        <div class="col-xl-3 col-md-6 col-sm-12 mb-4">
                            <a href="{{ url('/users/active') }}" style="text-decoration:none;">
                                <div
                                    style="display:flex;align-items:center;background:#fff;padding:20px;border-radius:16px;">
                                    <div
                                        style="background-color:var(--buttons-primary-color);border-radius:50%;padding:15px;">
                                        <i class="fas fa-users text-white" style="font-size:20px;"></i>
                                    </div>
                                    <div style="margin-left:15px;">
                                        <p class="mb-0 text-muted" style="font-size:14px;">Active Users</p>
                                        <div class="d-flex align-items-center">
                                            <h3 id="activeUsersCount" class="mb-0 text-dark font-weight-bold me-2">—
                                            </h3>
                                            <span id="activeUsersSpinner"
                                                class="spinner-border spinner-border-sm text-secondary"
                                                role="status"></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Blocked Users -->
                        <div class="col-xl-3 col-md-6 col-sm-12 mb-4">
                            <a href="{{ url('/users/blocked') }}" style="text-decoration:none;">
                                <div
                                    style="display:flex;align-items:center;background:#fff;padding:20px;border-radius:16px;">
                                    <div
                                        style="background-color:var(--buttons-primary-color);border-radius:50%;padding:15px;">
                                        <i class="fas fa-user-slash text-white" style="font-size:20px;"></i>
                                    </div>
                                    <div style="margin-left:15px;">
                                        <p class="mb-0 text-muted" style="font-size:14px;">Blocked Users</p>
                                        <div class="d-flex align-items-center">
                                            <h3 id="blockedUsersCount" class="mb-0 text-dark font-weight-bold me-2">—
                                            </h3>
                                            <span id="blockedUsersSpinner"
                                                class="spinner-border spinner-border-sm text-secondary"
                                                role="status"></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Signups Chart (with overlay loader) -->
                    <div id="signupsCard" class="mt-4 pos-relative"
                        style="background:#fff;padding:20px;border-radius:16px;">
                        <div id="signupsLoader" class="loader-overlay" style="display:flex;">
                            <div class="spinner-border text-primary" role="status"></div>
                            <div class="mt-2 text-muted">Loading data…</div>
                        </div>

                        <div class="row justify-content-between align-items-center">
                            <div class="col-md-6">
                                <h4 class="mb-2">
                                    New User Sign-ups Overview<br>
                                    <span style="color:var(--buttons-primary-color);font-size:1.25rem;"
                                        class="font-weight-bolder" id="signupsTotal">0</span>
                                </h4>
                            </div>
                            <div class="col-md-3 text-end">
                                <select id="yearSelect" class="form-select"
                                    style="border-radius:8px;padding:6px 12px;">
                                    @for ($y = now()->year; $y >= 2020; $y--)
                                        <option value="{{ $y }}"
                                            {{ $selectedYear == $y ? 'selected' : '' }}>Year {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div id="signupsChart" class="mt-3"></div>
                    </div>

                    <!-- Earnings (dummy) -->
                    <div class="mt-4 mb-4" style="background:#fff;padding:20px;border-radius:16px;">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-md-6">
                                <h4 class="mb-2">
                                    Total Admin Earnings Insights<br>
                                    <span style="color:var(--buttons-primary-color);font-size:1.25rem;"
                                        class="font-weight-bolder">$13.78k</span>
                                </h4>
                            </div>
                            <div class="col-md-3 text-end">
                                <select class="form-select" style="border-radius:8px;padding:6px 12px;">
                                    <option value="2025" selected>Year 2025</option>
                                    <option value="2024">Year 2024</option>
                                </select>
                            </div>
                        </div>
                        <div id="earningsChart" class="mt-3"></div>
                    </div>

                </div>
            </section>
        </div>

        @include('inc.footer')
    </div>

    <script>
        // Dummy earnings series from server
        var earningsData = @json($earningsCounts);

        // Firebase init
        const firebaseConfig = {
            apiKey: "AIzaSyDtnVhbX6cczcCydCB1DLxrAJVQWU2H8S0",
            authDomain: "time2play-ed370.firebaseapp.com",
            projectId: "time2play-ed370",
            messagingSenderId: "988354704853",
            appId: "1:988354704853:web:YOUR_WEB_APP_ID"
        };
        if (!firebase.apps.length) firebase.initializeApp(firebaseConfig);
        const db = firebase.firestore();

        // Chart color
        const chartColor = getComputedStyle(document.documentElement)
            .getPropertyValue('--buttons-primary-color').trim();

        // Signups chart shell
        let signupsSeries = new Array(12).fill(0);
        const signupsChart = new ApexCharts(document.querySelector("#signupsChart"), {
            chart: {
                type: 'bar',
                height: 250
            },
            series: [{
                name: 'Signups',
                data: signupsSeries
            }],
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            colors: [chartColor]
        });
        signupsChart.render();

        // Earnings chart (dummy)
        const earningsChart = new ApexCharts(document.querySelector("#earningsChart"), {
            chart: {
                type: 'bar',
                height: 250
            },
            series: [{
                name: 'Earnings',
                data: earningsData
            }],
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            colors: [chartColor]
        });
        earningsChart.render();

        // ------ Loaders helpers ------
        function toggleCountsLoading(show) {
            document.getElementById('totalUsersSpinner').style.display = show ? 'inline-block' : 'none';
            document.getElementById('activeUsersSpinner').style.display = show ? 'inline-block' : 'none';
            document.getElementById('blockedUsersSpinner').style.display = show ? 'inline-block' : 'none';
        }

        function toggleSignupsLoading(show) {
            document.getElementById('signupsLoader').style.display = show ? 'flex' : 'none';
            document.getElementById('yearSelect').disabled = !!show;
        }

        // ------ Counts (overall) ------
        async function loadUserCounts() {
            toggleCountsLoading(true);
            const snap = await db.collection('users').get();
            const all = snap.docs.map(d => d.data() || {});
            const total = all.length;
            const active = all.filter(u => u.isActive === true || u.status === true).length;
            const blocked = all.filter(u => u.isActive === false || u.status === false).length;

            document.getElementById('totalUsersCount').textContent = total;
            document.getElementById('activeUsersCount').textContent = active;
            document.getElementById('blockedUsersCount').textContent = blocked;
            toggleCountsLoading(false);
        }

        // ------ Yearly signups ------
        function startOfYear(year) {
            return new Date(year, 0, 1, 0, 0, 0, 0);
        }

        function startOfNextYear(year) {
            return new Date(year + 1, 0, 1, 0, 0, 0, 0);
        }

        async function loadYearlySignups(year) {
            toggleSignupsLoading(true);
            const q = db.collection('users')
                .where('createdAt', '>=', startOfYear(year))
                .where('createdAt', '<', startOfNextYear(year));
            const snap = await q.get();

            const monthly = new Array(12).fill(0);
            snap.forEach(doc => {
                const d = doc.data();
                const ts = d.createdAt;
                if (ts && typeof ts.toDate === 'function') {
                    const dt = ts.toDate();
                    monthly[dt.getMonth()] += 1;
                }
            });

            document.getElementById('signupsTotal').textContent = monthly.reduce((a, b) => a + b, 0);
            await signupsChart.updateSeries([{
                name: 'Signups',
                data: monthly
            }], true);
            toggleSignupsLoading(false);
        }

        // Year change re-load
        document.getElementById('yearSelect').addEventListener('change', async function() {
            await loadYearlySignups(parseInt(this.value, 10));
        });

        // Init
        (async function init() {
            toggleCountsLoading(true);
            toggleSignupsLoading(true);
            await loadUserCounts();
            await loadYearlySignups(parseInt(@json($selectedYear), 10));
        })();
    </script>

    <!-- Scripts -->
    <script src="{{ url('public/assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('public/assets/dist/js/adminlte.min.js') }}"></script>
</body>

</html> --}}








{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Time2Play</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ url('public/assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/adminlte.min.css') }}">
    <link rel="icon" type="image/png" href="{{ url('public/assets/dist/img/favicon.png') }}">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/varibales.css') }}">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('inc.header')
        @include('inc.aside')

        <div class="content-wrapper" style="background: #F4F4F6">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><b>Dashboard</b></h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">

                        <!-- Total Earning -->
                        <div class="col-xl-3 col-md-6 col-sm-12 mb-4">
                            <a href="#" style="text-decoration: none;">
                                <div
                                    style="display: flex; align-items: center; background: #fff; padding: 20px; border-radius: 16px;">
                                    <div
                                        style="background-color: var(--buttons-primary-color); border-radius: 50%; padding: 15px;">
                                        <i class="fas fa-chart-bar text-white" style="font-size: 20px;"></i>
                                    </div>
                                    <div style="margin-left: 15px;">
                                        <p class="mb-0 text-muted" style="font-size: 14px;">Total Earning</p>
                                        <h3 class="mb-0 text-dark font-weight-bold">$39.6
                                        </h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- Total Users -->
                        <div class="col-xl-3 col-md-6 col-sm-12 mb-4">
                            <a href="{{ url('/users') }}" style="text-decoration: none;">
                                <div
                                    style="display: flex; align-items: center; background: #fff; padding: 20px; border-radius: 16px;">
                                    <div
                                        style="background-color: var(--buttons-primary-color); border-radius: 50%; padding: 15px;">
                                        <i class="fas fa-users text-white" style="font-size: 20px;"></i>
                                    </div>
                                    <div style="margin-left: 15px;">
                                        <p class="mb-0 text-muted" style="font-size: 14px;">Total Users</p>
                                        <h3 class="mb-0 text-dark font-weight-bold">3
                                        </h3>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Active Users -->
                        <div class="col-xl-3 col-md-6 col-sm-12 mb-4">
                            <a href="{{ url('/users/active') }}" style="text-decoration: none;">
                                <div
                                    style="display: flex; align-items: center; background: #fff; padding: 20px; border-radius: 16px;">
                                    <div
                                        style="background-color: var(--buttons-primary-color); border-radius: 50%; padding: 15px;">
                                        <i class="fas fa-users text-white" style="font-size: 20px;"></i>
                                    </div>
                                    <div style="margin-left: 15px;">
                                        <p class="mb-0 text-muted" style="font-size: 14px;">Active Users</p>
                                        <h3 class="mb-0 text-dark font-weight-bold">67
                                        </h3>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Blocked Users -->
                        <div class="col-xl-3 col-md-6 col-sm-12 mb-4">
                            <a href="{{ url('/users/blocked') }}" style="text-decoration: none;">
                                <div
                                    style="display: flex; align-items: center; background: #fff; padding: 20px; border-radius: 16px;">
                                    <div
                                        style="background-color: var(--buttons-primary-color); border-radius: 50%; padding: 15px;">
                                        <i class="fas fa-user-slash text-white" style="font-size: 20px;"></i>
                                    </div>
                                    <div style="margin-left: 15px;">
                                        <p class="mb-0 text-muted" style="font-size: 14px;">Blocked Users</p>
                                        <h3 class="mb-0 text-dark font-weight-bold">6
                                        </h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Signups Chart -->
                    <div class="mt-4" style="background: #fff; padding: 20px; border-radius: 16px;">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-md-6">
                                <h4 class="mb-2">
                                    New User Sign-ups Overview
                                    <br>
                                    <span style="color: var(--buttons-primary-color); font-size: 1.25rem;"
                                        class="font-weight-bolder">4.54k</span>
                                </h4>
                            </div>
                            <div class="col-md-3" style="text-align: right">
                                <form class="m-0 p-0" method="GET" action="{{ url('/') }}">
                                    <select name="year" class="form-select" onchange="this.form.submit()"
                                        style="border-radius: 8px; padding: 6px 12px;">
                                        @for ($y = now()->year; $y >= 2020; $y--)
                                            <option value="{{ $y }}"
                                                {{ $selectedYear == $y ? 'selected' : '' }}>Year {{ $y }}
                                            </option>
                                        @endfor
                                    </select>
                                </form>
                            </div>
                        </div>
                        <div id="signupsChart" class="mt-3"></div>
                    </div>

                    <!-- Earnings Chart (Commented Out) -->

                    <div class="mt-4 mb-4" style="background: #fff; padding: 20px; border-radius: 16px;">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-md-6">
                                <h4 class="mb-2">
                                    Total Admin Earnings Insights
                                    <br>
                                    <span style="color: var(--buttons-primary-color); font-size: 1.25rem;"
                                        class="font-weight-bolder">$13.78k</span>
                                </h4>
                            </div>
                            <div class="col-md-3 text-end" style="text-align: right">
                                <select class="form-select" style="border-radius: 8px; padding: 6px 12px;">
                                    <option value="2025" selected>Year 2025</option>
                                    <option value="2024">Year 2024</option>
                                </select>
                            </div>
                        </div>
                        <div id="earningsChart" class="mt-3"></div>
                    </div>

                </div>
            </section>
        </div>
        @include('inc.footer')
    </div>

    <script>
        var signupData = @json($signupCounts);
        var earningsData = @json($earningsCounts); // Add this line
    </script>


    <script>
        // Fetch the CSS variable from :root
        const chartColor = getComputedStyle(document.documentElement)
            .getPropertyValue('--buttons-primary-color')
            .trim();

        var signupsChart = new ApexCharts(document.querySelector("#signupsChart"), {
            chart: {
                type: 'bar',
                height: 250
            },
            series: [{
                name: 'Signups',
                data: signupData
            }],
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            colors: [chartColor], // Set the color from CSS variable
            // plotOptions: {
            //     bar: {
            //         distributed: true,
            //         borderRadius: 4
            //     }
            // }
        });

        signupsChart.render();

        var earningsChart = new ApexCharts(document.querySelector("#earningsChart"), {
            chart: {
                type: 'bar',
                height: 250
            },
            series: [{
                name: 'Earnings',
                data: earningsData
            }],
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            colors: [chartColor],
            // plotOptions: {
            //     bar: {
            //         distributed: true,
            //         borderRadius: 4
            //     }
            // }
        });

        earningsChart.render();
    </script>


    <!-- Scripts -->
    <script src="{{ url('public/assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('public/assets/dist/js/adminlte.min.js') }}"></script>
</body>

</html> --}}
