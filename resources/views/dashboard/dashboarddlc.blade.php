@extends('layouts.admindlc')

@section('title', 'Dashboard | Learning & Development')

@section('content')
<main class="dashboard-content">
  <div class="container-fluid px-3 px-lg-4 py-4">
    <div class="hero-header-card mb-4">
      <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="page-icon bg-primary bg-opacity-10 text-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; font-size: 1.5rem; flex-shrink: 0;">
            <i class="bi bi-speedometer2"></i>
          </div>
          <div>
            <div class="d-flex align-items-center gap-2 mb-1">
              <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1">Administrator Portal</span>
              <span class="text-muted small">Dharma Learning Center</span>
            </div>
            <h1 class="h3 mb-1 fw-bold text-dark">DLC Dashboard</h1>
            <p class="text-muted mb-0 small">Pusat kendali pelatihan, verifikasi permintaan training out-house, dan manajemen formulir penugasan.</p>
          </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
          <a class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1 shadow-sm px-3 py-2" href="{{ route('member-list') }}">
            <i class="bi bi-people"></i> Member List
          </a>
          <a class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1 shadow-sm px-3 py-2" href="{{ route('outhouse.index') }}">
            <i class="bi bi-box-arrow-up-right"></i> Request OH
          </a>
        </div>
      </div>
    </div>

    <section class="row g-3 mt-1" aria-label="Dashboard metrics">
      <div class="col-12 col-sm-6 col-xl-3">
        <article class="metric-card metric-primary">
          <div class="metric-top">
            <span class="metric-label">Revenue</span>
            <span class="metric-icon"><i class="bi bi-currency-dollar" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value">$48,240</div>
          <div class="metric-meta">
            <span class="text-success">+12.5%</span>
            <span>from last month</span>
          </div>
        </article>
      </div>

      <div class="col-12 col-sm-6 col-xl-3">
        <article class="metric-card metric-success">
          <div class="metric-top">
            <span class="metric-label">Orders</span>
            <span class="metric-icon"><i class="bi bi-bag-check" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value">1,284</div>
          <div class="metric-meta">
            <span class="text-success">+8.2%</span>
            <span>new orders</span>
          </div>
        </article>
      </div>

      <div class="col-12 col-sm-6 col-xl-3">
        <article class="metric-card metric-warning">
          <div class="metric-top">
            <span class="metric-label">Customers</span>
            <span class="metric-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value">8,742</div>
          <div class="metric-meta">
            <span class="text-success">+5.1%</span>
            <span>active users</span>
          </div>
        </article>
      </div>

      <div class="col-12 col-sm-6 col-xl-3">
        <article class="metric-card metric-danger">
          <div class="metric-top">
            <span class="metric-label">Tickets</span>
            <span class="metric-icon"><i class="bi bi-life-preserver" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value">36</div>
          <div class="metric-meta">
            <span class="text-danger">3 urgent</span>
            <span>need review</span>
          </div>
        </article>
      </div>
    </section>

    <section class="row g-3 mt-1">
      <div class="col-12 col-xl-8">
        <div class="panel">
          <div class="panel-header">
            <div>
              <h2 class="h5 mb-1 section-title"><i class="bi bi-graph-up-arrow" aria-hidden="true"></i><span>Sales Performance</span></h2>
              <p class="text-muted mb-0">Monthly revenue compared with operational targets.</p>
            </div>
            <a class="btn btn-light btn-sm" href="charts.html">View Details</a>
          </div>

          <div class="chart-bars" aria-label="Sales performance chart">
            <div class="chart-column bar-42"><span></span><small>Jan</small></div>
            <div class="chart-column bar-58"><span></span><small>Feb</small></div>
            <div class="chart-column bar-51"><span></span><small>Mar</small></div>
            <div class="chart-column bar-72"><span></span><small>Apr</small></div>
            <div class="chart-column bar-66"><span></span><small>May</small></div>
            <div class="chart-column bar-83"><span></span><small>Jun</small></div>
          </div>
        </div>
      </div>

      <div class="col-12 col-xl-4">
        <div class="panel h-100">
          <div class="panel-header">
            <div>
              <h2 class="h5 mb-1 section-title"><i class="bi bi-activity" aria-hidden="true"></i><span>Team Activity</span></h2>
              <p class="text-muted mb-0">Recent operational updates.</p>
            </div>
          </div>

          <div class="activity-list">
            <div class="activity-item"><span class="activity-dot bg-primary"></span><div><p class="mb-1 fw-semibold">New campaign launched</p><p class="text-muted small mb-0">Marketing team published the May offer.</p></div></div>
            <div class="activity-item"><span class="activity-dot bg-success"></span><div><p class="mb-1 fw-semibold">Payment batch cleared</p><p class="text-muted small mb-0">246 invoices were processed successfully.</p></div></div>
            <div class="activity-item"><span class="activity-dot bg-warning"></span><div><p class="mb-1 fw-semibold">Support queue rising</p><p class="text-muted small mb-0">Average first response time is 18 minutes.</p></div></div>
          </div>
        </div>
      </div>
    </section>
    
  </div>
</main>
@endsection
