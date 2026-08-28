<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
  table { border-collapse: collapse; width: 100%; font-family: Calibri, Arial, sans-serif; font-size: 11pt; }
  th { background-color: #0d6efd; color: #ffffff; font-weight: bold; border: 1px solid #000000; padding: 8px; text-align: left; }
  td { border: 1px solid #cccccc; padding: 6px; vertical-align: middle; }
  .text-center { text-align: center; }
  .text-number { mso-number-format:"\@"; }
</style>
</head>
<body>
<table>
  <thead>
    <tr>
      <th style="background-color: #0d6efd; color: #ffffff; width: 120px;">NPK</th>
      <th style="background-color: #0d6efd; color: #ffffff; width: 220px;">Nama Staff</th>
      <th style="background-color: #0d6efd; color: #ffffff; width: 160px; text-align: center;">Tanggal Lahir (YYYY-MM-DD)</th>
      <th style="background-color: #0d6efd; color: #ffffff; width: 180px;">Department</th>
      <th style="background-color: #0d6efd; color: #ffffff; width: 120px; text-align: center;">Level Jabatan</th>
      <th style="background-color: #0d6efd; color: #ffffff; width: 160px;">NPK Immediate Manager</th>
    </tr>
  </thead>
  <tbody>
    @forelse($staffList as $s)
    <tr>
      <td class="text-number" style="mso-number-format:'\@';">{{ $s->npk_staff }}</td>
      <td>{{ $s->nama_staff }}</td>
      <td class="text-center">{{ $s->tanggal_lahir ? $s->tanggal_lahir->format('Y-m-d') : '' }}</td>
      <td>{{ $s->department ? $s->department->nama_department : '' }}</td>
      <td class="text-center">{{ $s->levelJabatan ? $s->levelJabatan->kode_level_jabatan : '' }}</td>
      <td class="text-number" style="mso-number-format:'\@';">{{ $s->immediateManager ? $s->immediateManager->npk_staff : '' }}</td>
    </tr>
    @empty
    <tr>
      <td colspan="6" style="text-align: center; color: #888888;">Tidak ada data staff.</td>
    </tr>
    @endforelse
  </tbody>
</table>
</body>
</html>
