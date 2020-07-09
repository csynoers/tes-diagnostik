<table>
  <thead>
  <tr>
    <th>No</th>
    <th>NIK</th>
    <th>Nama Lengkap</th>
    <th>Title</th>
    <th>Total Soal</th>
    <th>Poin</th>
    <th>Passing Grade</th>
    <th>Create At</th>
  </tr>
  </thead>
  <tbody>
  <?php
    foreach ($rows as $key => $value) {
      $value->no          = ($key+1);
      
      echo "
        <tr>
          <td>{$value->no}</td>
          <td>{$value->nik}</td>
          <td>{$value->fullname}</td>
          <td>{$value->question_title}</td>
          <td>{$value->total_questions} Soal</td>
          <td>".( ($value->passing_grade*($value->total_questions*5))/100 )." poin dari total ".($value->total_questions*5)." poin</td>
          <td>{$value->passing_grade}%</td>
          <td>{$value->create_at_mod}</td>
        </tr>
      ";
    }
  ?>
  
  </tbody>
</table>