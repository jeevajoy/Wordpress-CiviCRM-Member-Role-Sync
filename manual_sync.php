<div id="icon-edit-pages" class="icon32"></div>
<div class="wrap">
 <h2 id="add-new-user">Manual Synchronize</h2>
  <?php if(!$_GET['action']) { ?>
  <table class="form-table">      
      <td>
      <span>Manual Synchronization:</span> <br /> 
      <?php $sync_confirm_url = get_bloginfo('url')."/wp-admin/admin.php?&action=confirm&page=civi_member_sync/manual_sync.php"; ?>                          
       <input class="button-primary" type="submit" value="Synchronize CiviMember Membership Types to WordPress Roles now" onclick="window.location.href='<?php echo $sync_confirm_url; ?>'" />
      </td> 
   </tr> 
  </table> 
</div>
<?php } ?>  


<?php  
if($_GET['action'] == 'confirm') {
    $users = get_users();

    require_once('civi.php');
    require_once 'CRM/Core/BAO/UFMatch.php';

        foreach($users as $user) {       
    	     $uid = $user->ID;
    	     if (empty($uid)) {
    	      continue;
    	     }           
             $sql = "SELECT * FROM civicrm_uf_match WHERE uf_id =$uid";
             $contact = CRM_Core_DAO::executeQuery($sql); 
             
            
            if($contact->fetch()) {
                $cid = $contact->contact_id;               
        	    $memDetails=civicrm_api("Membership","get", array ('version' => '3','page' =>'CiviCRM', 'q' =>'civicrm/ajax/rest', 'sequential' =>'1','contact_id' =>$cid));          
        	    if (!empty($memDetails['values'])) {
        		  foreach($memDetails['values'] as $key => $value){
        		      $memStatusID = $value['status_id']; 
        		      $membershipTypeID = $value['membership_type_id'];  
        		  }         
        	    }
        
                $userData = get_userdata( $uid );
                if(!empty($userData)){
              	  $currentRole = $userData->roles[0];
                }
                //checking membership status and assign role
                $check = member_check($cid,$uid, $currentRole);     
                
            }       
         }
    
    ?>   
    
    
    
    <div id="message" class="updated below-h2">
    <span><p> CiviMember Memberships and WordPress Roles have been synchronized using available rules. Note: if no association rules exist then synchronization has not been completed.</p></span>
    </div> 
<?php } ?>     