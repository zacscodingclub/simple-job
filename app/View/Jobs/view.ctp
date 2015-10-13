<h3><?php echo $job['Job']['title']; ?></h3>
<ul>
  <li><strong>Company: </strong>  <?php echo $job['Job']['company_name']; ?></li>
  <li><strong>Location: </strong>  <?php echo $job['Job']['city']; ?>, <?php echo $job['Job']['state']; ?></li>
  <li><strong>Job Type: </strong> <?php echo $job['Type']['name']; ?></li>
  <li><strong>Description: </strong><p> <?php echo $job['Job']['description']; ?></p></li>
  <li><strong>Contact Email: </strong> <a href="mailto:<?php echo $job['Job']['contact_email']; ?>"><?php echo $job['Job']['contact_email']; ?></a></li>
</ul>
<br>
<?php  if($userData['id'] == $job['Job']['user_id']) : ?>
  <?php echo $this->Html->link('Edit', array('action' => 'edit', $job['Job']['id'])); ?> |
  <?php echo $this->Html->link('Delete', array('action' => 'delete', $job['Job']['id']), array('confirm' => 'Are you sure?')); ?> 
<br><br>
<?php  endif; ?>
<p><a href="<?php echo $this->webroot; ?>jobs/browse">Back to Jobs</a></p>