<table>
  <thead>
  <tr>
    <th>No</th>
    <th>Tgl Pendaftaran</th>
    <th>Waktu Mulai Pengerjaan</th>
    <th>NISN</th>
    <th>Nama Lengkap</th>
    <th>Tgl Lahir</th>
    <th>Asal Sekolah</th>
    <th>Email</th>
    <th>Telepon</th>
  </tr>
  </thead>
  <tbody>
  <?php
    foreach ($rows as $key => $value) {
      $value->no              = ($key+1);
      $value->tglPendaftaran  = date("d/m/Y H:i:s", strtotime($value->create_at));
      $value->birthDate       = date("d/m/Y", strtotime($value->birth_date));
      $value->start_exam      = date("d/m/Y H:i:s", strtotime(json_decode($value->session)->exam_start));
      echo "
        <tr>
          <td>{$value->no}</td>
          <td>{$value->tglPendaftaran}</td>
          <td>{$value->start_exam}</td>
          <td>{$value->nik}</td>
          <td>{$value->fullname}</td>
          <td>{$value->birthDate}</td>
          <td>{$value->schools}</td>
          <td>{$value->email}</td>
          <td>{$value->telp}</td>
        </tr>
      ";
    }
  ?>
  
  </tbody>
</table>