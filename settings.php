<?php 
 require_once('civi.php'); 
 if(isset($_GET['q']) && $_GET['q'] == "edit" ){
    if(!empty($_GET['id'])) {
        $select = $wpdb->get_results( "SELECT * FROM `mtl_civi_member_sync`WHERE `id` =".$_GET['id']);
        $wp_role = $select[0]->wp_role; 
        $expired_wp_role = $select[0]->expire_wp_role; 
        $civi_member_type  = $select[0]->civi_mem_type;  
        $current_rule =  unserialize($select[0]->current_rule);
        $expiry_rule =  unserialize($select[0]->expiry_rule );
    }      
 }  
?>
<div id="icon-options-general" class="icon32"></div> 
<script type="text/javascript">
jQuery(function() {  
    jQuery(':submit').click(function(e) {       
     jQuery(".required").each(function() {
          var input =   jQuery(this);
          if (!input.attr("value")) {
             jQuery(this).attr('style', 'border-color:#FF0000;');
             e.preventDefault(); 
          }else{
           jQuery(".required").attr('style','border-color:#DFDFDF;');
          }
      });                     
    });
    
    
});
</script>  
 
<div class="wrap">
        <?php  if(isset($_GET['q'])) $title = "EDIT ASSOCIATION RULE"; else  $title = "ADD ASSOCIATION RULE";   ?>  
        <h2 id="add-new-user"><?php echo $title;?></h2>
        <p>Choose a CiviMember Membership Type and a Wordpress Role below. This will associate that Membership with the Role. If you would like the have the same Membership be associated with more than one role, you will need to add a second association rule after you have completed this one.</p>
        <form method="POST" id="theform" > 
           <span class="error"><?php echo $nameErr;?></span>
            <table class="form-table">  
               <tr class="form-field form-required">  
                    <th scope="row">  
                        <label for="user_login">  
                            Select a CiviMember Membership Type *
                        </label>   
                    </th>  
                    <td>  
                         <select name="civi_member_type" id= "civi_member_type" class ="required"> 
                         <option value=""></option>                                               
                         <?php                        
                         foreach( $MembershipType as $key => $value) { ?>                                                    
                         <option value=<?php echo $key; if( $key == $civi_member_type) { ?> selected="selected" <?php } ?>> <?php echo $value; ?></option>                 <?php } ?>
                         </select>
                    </td>  
                </tr>
                <tr class="form-field form-required">  
                    <th scope="row">  
                        <label for="user_login">  
                           Select a Wordpress Role *
                        </label>   
                    </th>  
                    <td>  
                        <select name="wp_role" id ="wp_role" class = "required"> 
                         <option value=""> </option>
                        <?php global $wp_roles;
                              $roles = $wp_roles->get_names();                       
                         foreach( $roles as $key => $value) { ?>                                                    
                         <option value=<?php echo $value; if( $value == $wp_role) { ?> selected="selected" <?php } ?>> <?php echo $value; ?></option>
                         <?php } ?>
                         </select>
                    </td>  
                </tr>                
                <tr>
                   <th scope="row">  
                          <label for="user_login">  
                             Current Status *
                          </label>   
                   </th>  
                   <td>
                       <?php                        
                       foreach( $MembershipStatus as $key => $value) { ?> 
                      <input type="checkbox" name=<?php echo "current[$key]";?> id =<?php echo "current[$key]";?>  value=<?php echo $key;  if(!empty($current_rule))if(array_search($key,$current_rule)) {?> checked="checked"  <?php } ?> class="requiredCheckbox"/>
                       <label for=<?php echo "current[$key]";?>><?php echo $value;  ?> </label><br />
                       <?php } ?> 
                   </td>
                </tr> 
                <tr>
                   <th scope="row">  
                          <label for="user_login">  
                             Expire Status *
                          </label>   
                   </th>  
                   <td>
                       <?php                        
                       foreach( $MembershipStatus as $key => $value) { ?> 
                      <input type="checkbox" name=<?php echo "expire[$key]"; ?> id=<?php echo "expire[$key]";  ?>  value=<?php echo $key;   if(!empty($expiry_rule))if(array_search($key,$expiry_rule)) {?> checked="checked"  <?php } ?> class="requiredCheckbox" />  
                      <label for=<?php echo "expire[$key]";?>><?php echo $value;  ?> </label><br />
                       <?php } ?> 
                   </td>
                </tr> 
                 <tr class="form-field form-required">  
                    <th scope="row">  
                        <label for="user_login">  
                           Select a Wordpress Expiry Role *
                        </label>   
                    </th>  
                    <td>  
                        <select name="expire_assign_wp_role" id ="expire_assign_wp_role" class ="required"> 
                         <option value=""> </option>
                        <?php global $wp_roles;
                              $roles = $wp_roles->get_names();                       
                         foreach( $roles as $key => $value) { ?>                                                    
                         <option value=<?php echo $value; if( $value ==  $expired_wp_role) { ?> selected="selected" <?php } ?>> <?php echo $value; ?></option>
                         <?php } ?>
                         </select>
                    </td>  
                </tr>
  

             
                <?php  if(isset($_GET['q'])) $submit = "Save association rule"; else  $submit = "Add association rule";   ?>                
                    <td>                       
                        <input class="button-primary" type="submit" value="<?php echo $submit; ?>" />
                    </td>  
                             
            </table>  
          
        </form>  
    </div>
<?php
if ($_POST) { 

    if(!empty($_POST['wp_role'])){ 
        $wp_role = $_POST['wp_role'];
    }
    if(!empty($_POST['civi_member_type'])){
        $civi_member_type = $_POST['civi_member_type'];   
    }
    if(!empty($_POST['expire_assign_wp_role'])){ 
        $expired_wp_role = $_POST['expire_assign_wp_role'];
    } 
  
    
    if(!empty($_POST['current'])){
        foreach($_POST['current'] as $key => $value){
           if(!empty($_POST['expire']))
           $sameType .= array_search($key, $_POST['expire']);
        }   
        $current_rule = serialize($_POST['current']);   
    } else{
        $errors[] = "Current Status field is required.";
    }
    if(!empty($_POST['expire'])){   
        $expiry_rule = serialize($_POST['expire']); 
    }else{
        $errors[] = "Expiry Status field is required.";
    }
    
    if(empty($sameType) && empty($errors)) {    
        $insert = $wpdb->get_results( "REPLACE INTO  mtl_civi_member_sync SET `wp_role`='$wp_role', `civi_mem_type`=$civi_member_type, `current_rule`='$current_rule',`expiry_rule`='$expiry_rule', `expire_wp_role`='$expired_wp_role'" );  
        
       $location = get_bloginfo('url')."/wp-admin/options-general.php?page=civi_member_sync/list.php";
        echo "<meta http-equiv='refresh' content='0;url=$location' />";exit;
    }else{
        if(!empty($sameType)){  
        $errors[] = "You can not have the same Status Rule registered as both \"Current\" and \"Expired\".";
        }
        ?> <span class="error"  style="color: #FF0000;"><?php foreach ($errors as $key => $values){ echo $values."<br>"; } ?> </span>  <?php
   }
}
?>