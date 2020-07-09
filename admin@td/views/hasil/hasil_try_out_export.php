<table>
  <thead>
  <tr>
    <th>No</th>
    <th>NISN</th>
    <th>Nama Lengkap</th>
    <th>Asal Sekolah</th>
    <th>Tgl Lahir</th>
    <th>Telepon</th>
    <th>Waktu Pengerjaan</th>
    <th>Jawaban</th>
  </tr>
  </thead>
  <tbody>
  <?php
    foreach ($rows as $key => $value) {
      $value->no          = ($key+1);
      $value->birthDate   = date("d-m-Y", strtotime($value->birth_date));
      echo "
        <tr>
          <td>{$value->no}</td>
          <td><span>{$value->nik}</span></td>
          <td>{$value->fullname}</td>
          <td>{$value->schools}</td>
          <td>{$value->birthDate}</td>
          <td>{$value->telp}</td>
          <td>{$value->timeDiff}</td>
          <td>{$value->jawaban}</td>
        </tr>
      ";
    }
  ?>
  
  </tbody>
</table>