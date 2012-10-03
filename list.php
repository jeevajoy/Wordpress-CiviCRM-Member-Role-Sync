<?php 
require_once('civi.php');
 if(isset($_GET['q']) && $_GET['q'] == "delete" ){
    if(!empty($_GET['id'])) {
        $delete = $wpdb->get_results( "DELETE FROM `mtl_civi_member_sync` WHERE `id`=".$_GET['id']);        
    }
 }
$addNew_url = get_bloginfo('url')."/wp-admin/admin.php?&page=civi_member_sync/settings.php"; 
$manual_sync_url = get_bloginfo('url')."/wp-admin/admin.php?&page=civi_member_sync/manual_sync.php";  
?>
<div id="icon-edit-pages" class="icon32"></div>
<div class="wrap">     
    <h2>LIST ASSOCIATION RULE(S)<a class="add-new-h2" href=<?php echo $addNew_url ?>>Add Association Rule</a><a class="add-new-h2" href=<?php echo $manual_sync_url ?>>Manually Synchronize</a></h2> 
</div>

<?php
$select = $wpdb->get_results( "SELECT * FROM `mtl_civi_member_sync`"); ?>
<table cellspacing="0" class="wp-list-table widefat fixed users">
 <thead>
    <tr>
        <th style="" class="manage-column column-role" id="role" scope="col">Civi Membership Type</th>
        <th style="" class="manage-column column-role" id="role" scope="col">Wordpress Role</th>
        <th style="" class="manage-column column-role" id="role" scope="col">Current Codes</th>
        <th style="" class="manage-column column-role" id="role" scope="col">Expired Codes</th>
        <th style="" class="manage-column column-role" id="role" scope="col">Expiry Assign Role</th>
    </tr>
 </thead>    
 <tbody class="list:civimember-role-sync" id="the-list">
 <?php foreach ($select as $key => $value){?>
 <tr>   
  <td><?php  echo get_names($value->civi_mem_type, $MembershipType);  ?>
  <br />
  <?php $edit_url = get_bloginfo('url')."/wp-admin/admin.php?&q=edit&id=".$value->id."&page=civi_member_sync/settings.php";  ?>
  <?php $delete_url = get_bloginfo('url')."/wp-admin/admin.php?&q=delete&id=".$value->id."&page=civi_member_sync/list.php";  ?>
  <div class="row-actions">
      <span class="edit">
      <a href="<?php echo $edit_url ?>">Edit</a> | </span>
      <span class="delete"><a href="<?php echo $delete_url ?>" class="submitdelete">Delete</a></span>
  </div>
  </td>
  <td><?php echo $value->wp_role; ?></td>   
  <td><?php echo get_names($value->current_rule, $MembershipStatus); ?></td>
  <td><?php echo get_names($value->expiry_rule, $MembershipStatus);?></td>
  <td><?php echo $value->expire_wp_role;  	?></td>
  </tr> 
  <?php } ?>
  </tbody>
</table>  