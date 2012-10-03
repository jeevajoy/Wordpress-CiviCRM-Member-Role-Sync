<?php

civicrm_wp_initialize( );

$MembershipTypeDetials=civicrm_api("MembershipType","get", array (version => '3','sequential' =>'1'));
 foreach( $MembershipTypeDetials['values'] as $key => $values){
   $MembershipType[$values['id']] = $values['name']; 
 }


$MembershipStatusDetials=civicrm_api("MembershipStatus","get", array (version => '3','sequential' =>'1'));
 foreach( $MembershipStatusDetials['values'] as $key => $values){
   $MembershipStatus[$values['id']] =$values['name']; 
 }

function get_names($values,$memArray){   
    $memArray = array_flip($memArray); 
    $current_rule =  unserialize($values); 
    if(empty($current_rule)) {
      $current_rule = $values; 
    }    
     $current_roles =""; 
     if(!empty($current_rule)){ 
         if(is_array($current_rule)){    
            foreach( $current_rule as $ckey =>$cvalue){
                 $current_roles .= array_search($ckey, $memArray)."<br>";
            }
         }else{
          $current_roles = array_search($current_rule, $memArray)."<br>";
         }    
     } 
    return $current_roles;
}  
?>    