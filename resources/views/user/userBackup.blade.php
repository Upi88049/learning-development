@extends('layouts.admin')

@section('title', 'Dashboard | Learning & Development')

@section('content')
<style>
  .vertical-header {
    /* height: 150px; */
    vertical-align: bottom !important;
    padding: 8px 2px !important;
  }
  /* .vertical-header,.mandatory {
    height: 75px;
    vertical-align: bottom !important;
    padding: 8px 2px !important;
  } */
  .vertical-text {
    writing-mode: vertical-rl;
    transform: rotate(180deg);
    text-align: left;
    display: inline-block;
    /* line-height: 1.15; */
    font-size: 11px;
    font-weight: bold;
  }
  .center-table {
    vertical-align: middle;
    text-align: center;
  }
  .table tbody td {
    min-width: 100%;
  }
  .peserta {
    min-width: 120px !important;
  }
  /* table {
    border-collapse: collapse !important;
    width: max-content !important;
    font-size: 11px !important;
    text-align: center !important;
  } */
  th,
  td {
    border: 1px solid #000000;
    padding: 3px 2px;
    white-space: nowrap;
  }
</style>

<main class="dashboard-content">
  <div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
      <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
        <div>
          <p class="eyebrow mb-1">TNA</p>
          <h1 class="h3 mb-1">Asset MGT</h1>
        </div>
      </div>
      <div class="heading-actions">
        <a class="btn btn-outline-secondary btn-sm" href="tables.html">
          <i class="bi bi-download" aria-hidden="true"></i> Export</a>
          <a class="btn btn-primary btn-sm" href="{{ route('users.create') }}"><i class="bi bi-person-plus" aria-hidden="true"></i> Add User</a>
      </div>
    </div>

    <section class="panel mt-3">
      <div class="panel-header">
        <div>
          <h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>Staff List</span></h2>
        </div>
        <div class="d-flex flex-wrap gap-1">
          <p class="text-muted mb-0">TRAINING NEED ANALYSIS 2025/2026</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
          <input class="form-control form-control-sm table-search" type="search" placeholder="Search users" data-table-search="usersTable" aria-label="Search users">
        </div>
      </div>
      <div class="table-responsive">
        <table class="table align-middle mb-0" id="usersTable" data-searchable-table>
          <thead>
            <tr>
              <th colspan="5" rowspan="3" class="center-table">PROFIL</th>
              <th class="row-header" class="center-table">JENIS TRAINING</th>
              <th colspan="17" class="center-table">MANDATORY TRAINING</th>
              <th colspan="9" class="center-table">MANAGERIAL TRAINING</th>
              <th colspan="13" class="center-table">TECHNICAL TRAINING</th>
            </tr>

            <!-- JUDUL TRAINING -->
            <tr>
              <th class="row-header center-table">JUDUL TRAINING</th>
              <!-- Mandatory Training (17 kolom) -->
              <th class="vertical-header"><div class="vertical-text">DLTP 3</div></th>
              <th class="vertical-header"><div class="vertical-text">PCSH</div></th>
              <th class="vertical-header"><div class="vertical-text">PCGH</div></th>
              <th class="vertical-header"><div class="vertical-text">DLTP 1-2</div></th>
              <th class="vertical-header"><div class="vertical-text">TMDP</div></th>
              <th class="vertical-header"><div class="vertical-text">TGMDP</div></th>
              <th class="vertical-header"><div class="vertical-text">Sugestion System</div></th>
              <th class="vertical-header"><div class="vertical-text">QCC for Tema<br>Leader</div></th>
              <th class="vertical-header"><div class="vertical-text">GQCC</div></th>
              <th class="vertical-header"><div class="vertical-text">PPS</div></th>
              <th class="vertical-header"><div class="vertical-text">SHE</div></th>
              <th class="vertical-header"><div class="vertical-text">5R</div></th>
              <th class="vertical-header"><div class="vertical-text">Masa Persiapan<br>Pensiun</div></th>
              <th class="vertical-header"><div class="vertical-text">Coaching &amp;<br>Counselling</div></th>
              <th class="vertical-header"><div class="vertical-text">Supervisory Role</div></th>
              <th class="vertical-header"><div class="vertical-text">Alignment TDNA<br>&amp; Culture</div></th>
              <th class="vertical-header"><div class="vertical-text">Triputra<br>Mentoring</div></th>

              <!-- Managerial Training (9 Kolom) -->
              <th class="vertical-header"><div class="vertical-text">Dharma Basic<br>Training Program</div></th>
              <th class="vertical-header"><div class="vertical-text">Comunication<br>Skill</div></th>
              <th class="vertical-header"><div class="vertical-text">Training For<br>Trainer</div></th>
              <th class="vertical-header"><div class="vertical-text">Risk Management</div></th>
              <th class="vertical-header"><div class="vertical-text">Process Auditing</div></th>
              <th class="vertical-header"><div class="vertical-text">Presentation Skill</div></th>
              <th class="vertical-header"><div class="vertical-text">Negotiation skill</div></th>
              <th class="vertical-header"><div class="vertical-text">DISC</div></th>
              <th class="vertical-header"><div class="vertical-text">Effective<br>Leadership</div></th>

              <!-- Technical Training (13 Kolom) -->
              <th class="vertical-header"><div class="vertical-text">Awr / Interpretasi< <br>IATF 16949</div></th>
              <th class="vertical-header"><div class="vertical-text">Awr / Interpretasi<br>ISO 14001</div></th>
              <th class="vertical-header"><div class="vertical-text">FMEA</div></th>
              <th class="vertical-header"><div class="vertical-text">MSA</div></th>
              <th class="vertical-header"><div class="vertical-text">SPC</div></th>
              <th class="vertical-header"><div class="vertical-text">APQP & PPAP</div></th>
              <th class="vertical-header"><div class="vertical-text">Standarisasi Kerja</div></th>
              <th class="vertical-header"><div class="vertical-text">PICA Making</div></th>
              <th class="vertical-header"><div class="vertical-text">BIQ</div></th>
              <th class="vertical-header"><div class="vertical-text">Ms. Office Excel <br> INTERMEDIATE</div></th>
              <th class="vertical-header"><div class="vertical-text">Ms. Office Excel <br> BASIC</div></th>
              <th class="vertical-header"><div class="vertical-text">Pengukuran <br> Alat Ukur</div></th>
              <th class="vertical-header"><div class="vertical-text">CSR</div></th>
            </tr>

            <!-- MANDATORY UNTUK -->
            <tr>
              <th class="bg-label-training center-table">MANDATORY <br>TRAINING UNTUK:</th>
              <th class="center-table">SH</th>
              <th class="center-table">SH</th>
              <th class="center-table">GH</th>
              <th class="vertical-header"><div class="vertical-text">GH-UH</div></th>
              <th class="center-table">DH</th>
              <th class="vertical-header"><div class="vertical-text">SDV-VH</div></th>
              <th class="vertical-header"><div class="vertical-text">TM-SF</div></th>
              <th class="vertical-header"><div class="vertical-text">GH & SF</div></th>
              <th class="center-table">SH</th>
              <th class="vertical-header"><div class="vertical-text">SH-VH</div></th>
              <th class="center-table">ALL</th>
              <th class="center-table">ALL</th>
              <th class="vertical-header"><div class="vertical-text">Pensiun</div></th>
              <th class="vertical-header"><div class="vertical-text">GH-SH</div></th>
              <th class="vertical-header"><div class="vertical-text">GH-DH</div></th>
              <th class="center-table">ALL</th>
              <th class="vertical-header"><div class="vertical-text">SH-VH</div></th>
              <th class="center-table">All</th>
              <th class="vertical-header"><div class="vertical-text">GH-SF</div></th>
              <th class="bg-black"></th>
              <th class="vertical-header"><div class="vertical-text">GH-VH</div></th>
              <th class="bg-black"></th>
              <th class="vertical-header"><div class="vertical-text">GH-SH</div></th>
              <th class="bg-black"></th>
              <th class="vertical-header"><div class="vertical-text">GH-SH</div></th>
              <th class="bg-black"></th>
              <th colspan="13" class="bg-black"></th>
            </tr>

            <tr>
              <th scope="col" class="center-table">NPK</th>
              <th scope="col" class="center-table" style="min-width: 200px;">Nama Peserta</th>
              <th scope="col" class="center-table">Bagian</th>
              <th scope="col" class="center-table">Level jabatan</th>
              <th scope="col" class="center-table">Umur</th>
              <th class="bg-kader">DAPAT DIIKUTKAN <br> KADER DARI GOL:</th>
              <th class="vertical-header"><div class="vertical-text">3E-4E</div></th>
              <th class="vertical-header"><div class="vertical-text">3E-4C</div></th>
              <th class="vertical-header"><div class="vertical-text">1B-2E</div></th>
              <th class="vertical-header"><div class="vertical-text">GH-UH</div></th>
              <th class="vertical-header"><div class="vertical-text">4E-5E</div></th>
              <th class="vertical-header"><div class="vertical-text">5A-5F</div></th>
              <th class="vertical-header"><div class="vertical-text">1A-3F</div></th>
              <th class="vertical-header"><div class="vertical-text">ALL</div></th>
              <th class="vertical-header"><div class="vertical-text">3E-4E</div></th>
              <th class="bg-black"></th>
              <th class="vertical-header"><div class="vertical-text">ALL</div></th>
              <th class="vertical-header"><div class="vertical-text">ALL</div></th>
              <th class="vertical-header"><div class="vertical-text">ALL</div></th>
              <th class="vertical-header"><div class="vertical-text">1A-4C</div></th>
              <th class="vertical-header"><div class="vertical-text">1B-5A</div></th>
              <th class="vertical-header"><div class="vertical-text">ALL</div></th>
              <th class="bg-black"></th>
              <th class="vertical-header"><div class="vertical-text">New <br> Employee</div></th>
              <th class="vertical-header"><div class="vertical-text">All</div></th>
              <th class="vertical-header"><div class="vertical-text">Calon <br> Trainer</div></th>
              <th class="vertical-header"><div class="vertical-text">All</div></th>
              <th class="vertical-header"><div class="vertical-text">Internal <br> Auditor</div></th>
              <th class="vertical-header"><div class="vertical-text">All</div></th>
              <th class="vertical-header"><div class="vertical-text">All</div></th>
              <th class="vertical-header"><div class="vertical-text">All</div></th>
              <th class="vertical-header"><div class="vertical-text">Staf f3 up</div></th>
              <th class="vertical-header"><div class="vertical-text">ALL</div></th>
              <th class="vertical-header"><div class="vertical-text">ALL</div></th>
              <th class="vertical-header"><div class="vertical-text">ALL</div></th>
              <th class="vertical-header"><div class="vertical-text">ALL</div></th>
              <th class="vertical-header"><div class="vertical-text">ALL</div></th>
              <th class="vertical-header"><div class="vertical-text">ALL</div></th>
              <th class="vertical-header"><div class="vertical-text">ALL</div></th>
              <th class="vertical-header"><div class="vertical-text">ALL</div></th>
              <th class="vertical-header"><div class="vertical-text">ALL</div></th>
              <th class="vertical-header"><div class="vertical-text">ALL</div></th>
              <th class="vertical-header"><div class="vertical-text">ALL</div></th>
              <th class="vertical-header"><div class="vertical-text">ALL</div></th>
              <th class="vertical-header"><div class="vertical-text">ALL</div></th>
            </tr>
          </thead>
          <tbody>
            <!-- PESERTA 1 -->
            <tr>
              <td class="peserta center-table">11990935</td>
              <td class="peserta">
                  <div>
                    <p class="fw-semibold mb-0">Haniful Qayyim Apip</p>
                  </div>
              </td>
              <td class="peserta">Asset Management</td>
              <td class="peserta">SH</td>
              <td class="peserta">48 Tahun</td>
              <td></td>
              <td>O</td>
              <td></td>
              <td>O</td>
              <td></td>
              <td></td>
              <td></td>
              <td>O</td>
              <td>O</td>
              <td>O</td>
              <td>O</td>
              <td>O</td>
              <td></td>
              <td></td>
              <td>O</td>
              <td>O</td>
              <td></td>
              <td>O</td>
              <td>1</td>
              <td>O</td>
              <td></td>
              <td>O</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td>O</td>
              <td></td>
              <td>O</td>
              <td>O</td>
              <td>O</td>
              <td>O</td>
              <td></td>
              <td></td>
              <td></td>
              <td>1</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <!-- PESERTA 2 -->
            <tr>
              <td class="peserta center-table">11210509</td>
              <td>
                  <div>
                    <p class="fw-semibold mb-0">Rizal Adrian Saputra</p>
                  </div>
              </td>
              <td>Asset Management</td>
              <td>SF</td>
              <td>28 Tahun</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td>O</td>
              <td></td>
              <td></td>
              <td>O</td>
              <td>O</td>
              <td></td>
              <td></td>
              <td>O</td>
              <td></td>
              <td></td>
              <td>1</td>
              <td>1</td>
              <td></td>
              <td></td>
              <td>1</td>
              <td></td>
              <td>1</td>
              <td></td>
              <td>O</td>
              <td>O</td>
              <td></td>
              <td></td>
              <td></td>
              <td>1</td>
              <td>O</td>
              <td>1</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <!-- REKAPITULASI SUMMARY -->
            <tr class="bg-summary">
              <td colspan="5" class="text-right">TOTAL PERMINTAAN TRAINING IN-HOUSE</td>
              <td></td>
              <td class="peserta-table">0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>1</td>
              <td>1</td>
              <td>2</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>1</td>
              <td>0</td>
              <td>0</td>
              <td>1</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>1</td>
              <td>0</td>
              <td>0</td>
              <td>2</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
            </tr>
            <tr class="bg-summary">
              <td colspan="5" class="text-right">TOTAL TERLAKSANA</td>
              <td></td>
              <td class="peserta-table">1</td>
              <td>0</td>
              <td>0</td>
              <td>1</td>
              <td>0</td>
              <td>0</td>
              <td>1</td>
              <td>1</td>
              <td>1</td>
              <td>1</td>
              <td>2</td>
              <td>2</td>
              <td>0</td>
              <td>0</td>
              <td>1</td>
              <td>2</td>
              <td>0</td>
              <td>1</td>
              <td>0</td>
              <td>1</td>
              <td>0</td>
              <td>1</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>2</td>
              <td>1</td>
              <td>2</td>
              <td>1</td>
              <td>1</td>
              <td>1</td>
              <td>0</td>
              <td>1</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
            </tr>
            <tr class="bg-summary">
              <td colspan="5" class="text-right">TOTAL KETIDAKHADIRAN TRAINING</td>
              <td class="peserta-table"></td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3">
        <p class="text-muted small mb-0">Showing 1 to 5 of 124 users</p>
        <nav aria-label="Users pagination"><ul class="pagination pagination-sm mb-0"><li class="page-item disabled"><a class="page-link" href="#">Previous</a></li><li class="page-item active"><a class="page-link" href="#">1</a></li><li class="page-item"><a class="page-link" href="#">2</a></li><li class="page-item"><a class="page-link" href="#">Next</a></li></ul></nav>
      </div>
    </section>
  </div>
</main>
@endsection
