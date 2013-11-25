<?php
include 'data_access.php';
global $wpdb;
$this->db = $wpdb;

$data_access = new DataAccess();

$sql_entity_config = array(); 
$sql_entity_config = $data_access->get_table_values($this->db->prefix . 'admin_rating_config', ' * ');
$sql_entity_factors = array();
$sql_entity_factors = $data_access->get_table_values($this->db->prefix . 'rating_factors', ' * ');

foreach($sql_entity_config as $count_con) {
  $count_arr[] = $count_con->id;
}
foreach($sql_entity_factors as $count_factor) {
  $count_fac_arr[] = $count_factor->id;
}
$sql_fact_mode = array();
$cond = " WHERE entity_factors_mode = 1 ";
$sql_fact_mode = $data_access->get_table_values($this->db->prefix . 'admin_rating_config', ' id ', $cond);

$fact_mode_array = array();
foreach($sql_fact_mode as $fact) {
  $fact_mode_array[] = $fact->id;
}
foreach($sql_entity_config as $count_con) {
  $count_arr[] = $count_con->id;
}
foreach($sql_entity_factors as $count_factor) {
  $count_fac_arr[] = $count_factor->id;
}
$con_count = count($count_arr);
$con_fact = count($count_fac_arr); ?>

<?php if(count($fact_mode_array) >= 1) {?>
<form method="post" name="entity_factors" action="#">
  <?php wp_nonce_field('update-options'); ?>
<?php
if(isset($_POST)) {  
  $data_access->save_rating_options();
  unset($_POST);
}
?>
<?php if(isset($_SESSION['rating_message'])) { ?>
  <div class="updated" id="message"><?php print $_SESSION['rating_message']['message'] ?></div>
<?php }
 unset($_SESSION['rating_message']);
?>
  <table class="table_class">
    <input type="hidden" name="option_page" value="assign_entity_factors">
    <tr class="tr_class">
      <td class="td_class">Entity Name</td>
      <td class="td_class">Factors</td>
    </tr>
 <?php if($con_fact >=1 && $con_count >=1) {
   foreach($sql_entity_config as $val) {
      if(in_array($val->id, $fact_mode_array)) { 
     ?> 
    <tr class="tr_class_nb">
      <td class="td_class_label">
        <label><?php print $val->entity_type_name; ?></label></td>        
        <td class="td_class">
        <?php 
        foreach($sql_entity_factors as $value) {
          $name_fac = "'". $value->factor ."'";
          $sql_assigned_factor = $data_access->get_table_values($this->db->prefix . 'entity_rating_factors', ' factor_name, weight ', "WHERE entity_id = $val->id AND factor_name = $name_fac");
          foreach ($sql_assigned_factor as $assigned_val) {
            $value_assigned = $assigned_val->factor_name;
          }

          ?>
          <div class="factors_checkbox">
          <input type="checkbox" name="<?php print 'assign_fact--' . $val->id; ?>[]" value="<?php print $value->factor; ?>" <?php print ($value_assigned == $value->factor) ? ' checked="checked"':'' ?>><?php print $value->factor; ?>
          </div>
        <?php unset($value_assigned);
        } ?>
        </td>    
  </tr>
  
    <?php } } } ?><br>
     </table>    
  <?php submit_button(); ?>

</form>

<?php
}    
