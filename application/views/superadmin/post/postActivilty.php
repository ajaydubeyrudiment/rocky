<h2><?php echo $this->input->get('type', TRUE); ?><?php echo ' '.$this->input->get('types', TRUE); ?> user List</h2>
<table class="table table-bordered">
  <tr>
    <th class="text-center">S.No.</th>
    <th>User Name</th>
    <th>Email</th>
    <th>Date & Time</th>
    <?php
      if($this->input->get('types')=='comments'){
        echo '<th>Comments</th>';
      }
    ?>
  </tr>
  <?php
  $im=1;
  if(!empty($rows)){
    foreach($rows as $row){
      echo '<tr>';
      echo '<td class="text-center">';
      echo !empty($im)?$im:'';
      echo '</td>';
      echo '<td>';
      echo !empty($row->first_name)?ucfirst($row->first_name):'';
      echo !empty($row->last_name)?ucfirst($row->last_name):'';
      echo '</td>';
      echo '<td>';
      echo !empty($row->email)?ucfirst($row->email):'';
      echo '</td>';
      echo '<td>';
      echo !empty($row->created_date)?date('d M Y h:i A', strtotime($row->created_date)):'';
      echo '</td>';
      if($this->input->get('types')=='comments'&&!empty($row->comments)){
        echo '<td>'.ucfirst($row->comments).'</td>';
      }
      $im++;
    }
  }?>
</table>