<?php
/*
Plugin Name: Show Content by User Level
Plugin URI: http://www.nicholascaporusso.com
Description: Shows content according to user levels. I acknowledge Rex Reed for his contribution.
Author: Nicholas Caporusso
Version: 0.1
Author URI: http://www.nicholascaporusso.com
*/

// ##### This plugin should be completely self contained and work totally on its own without source editing.   
// ##### ---------- NOTHING USER-CONFIGURABLE AFTER HERE ------------

// Just in case someone's loaded up the page standalone for whatever reason,
// make sure it doesn't crash in an ugly way
if (!function_exists('add_action'))
{
  die("This page must be loaded as part of WordPress");
}

// Defer setup function until pluggable functions have been loaded
// (required since we need to call get_currentuserinfo)
add_action('init', 'showcontentuserlevel_setup');

// Initialize plugin variables and functions, called during the 
// 'init' action by WordPress
function showcontentuserlevel_setup()
{
  // Setup options stored in database
  showcontentuserlevel_setup_options();
  
  // Defer admin menu setup to only run if we're in admin section
  add_action('admin_menu', 'showcontentuserlevel_admin_hook');

  // Don't bother with anything more unless someone is logged in
  if (is_user_logged_in())
  {
    // Make sure private pages aren't cached publicly
    header('Cache-Control: private');
    header('Pragma: no-cache');

    // Initialize global variables      
    $showcontentuserlevel_exception_text = get_option('showcontentuserlevel_exception_text');
  }
			
   // Setup filters
	 add_filter('the_content', 'Wp_ShowContent_by_UserLevel');
}

// Gets the user level of the current user
// If the user isn't logged in, returns null
function get_user_level()
{
  if (!is_user_logged_in())
  {
  	return null;
  }
  
  global $user_ID, $user_key;
  get_currentuserinfo();
  
  $current_user_level = get_usermeta($user_ID, $table_prefix . "user_level", true);
  // Only want numbers here
  if (($current_user_level == '') || !is_numeric($current_user_level))
  {
    return intval(0);
  }
  return intval($current_user_level);
}

function Wp_ShowContent_by_UserLevel($text) {
    
	global $user_ID;
	
	// gets user level
	$userlevel=get_user_level();
	
	// gets error text
   $showcontentuserlevel_exception_text = get_option('showcontentuserlevel_exception_text');
   
	// sets minimum user level to read text
  if ($user_ID == '') $userlevel = 0;
  else $userlevel++;
  
  // hides the content 
	for($i=$userlevel+1;$i<8;$i++)
	{
	$replacement = "/\[hide\s".$i."\].*\[hide\s".$i."\]/";
	$text =  preg_replace($replacement, $showcontentuserlevel_exception_text, $text);
	}
	// replaces [hide X] and [\hide X] tags
	$text = preg_replace("/\[hide\s\d\]/", "", $text);
	
	return $text; 
}

// UserLevelContent configuration page
function showcontentuserlevel_conf()
{
  global $table_prefix;
  $default_user_key = $table_prefix . 'user_level';

  ?>

  <div class="wrap">
    <h2><?php _e('Show Content by User Level Configuration'); ?></h2>
    <p>Show User Level Content allows you to include tags that will hide parts of your content based upon the user's access level.</p>
  <?php 
  if (isset($_POST['submit']))
  {
    check_admin_referer();

    // Clean the incoming values
    $showcontentuserlevel_exception_text = stripslashes($_POST['showcontentuserlevel_exception_text']);
    if (current_user_can('unfiltered_html') == false)
    {
      $showcontentuserlevel_exception_text = wp_filter_post_kses($showcontentuserlevel_exception_text);
    }
    
    // Update values
    update_option('showcontentuserlevel_exception_text', $showcontentuserlevel_exception_text);
    
    echo '<div id="message" class="updated fade"><p><strong>' . __('Options saved.') . '</strong></p></div>';
  }

  global $showcontentuserlevel_exception_text;
  $showcontentuserlevel_exception_text = get_option('showcontentuserlevel_exception_text');
  ?>
    <form action="" method="post" id="showcontentuserlevel-conf">
      <table width="100%" cellspacing="2" cellpadding="5" class="editform"> 
        <tr valign="top">
          <th scope="row">Exception text:</th>
          <td><textarea name="showcontentuserlevel_exception_text" type="text" rows=6 cols=60 id="showcontentuserlevel_exception_text" size="45" class="code" /><?php echo form_option('showcontentuserlevel_exception_text'); ?></textarea> 
          <br />
          Input here the text replaces that the hidden text if user is not at a sufficient level to see it.
          </td>
        <tr>
      </table>
    
      <p class="submit"><input type="submit" name="submit" value="<?php _e('Update Show User Level Content &raquo;'); ?>" /></p>
    </form>
  </div>

<?php 
}

// Sets up the admin menu
function showcontentuserlevel_admin_hook()
{
  if (function_exists('add_submenu_page'))
  {
    add_submenu_page('plugins.php', 'Show Content by User Level  Configuration', 'Show Content by User Level  Configuration', 8, __FILE__, 'showcontentuserlevel_conf');
  }
}

// Creates the database-stored options for the plugins that customize
// the plugin's behavior
function showcontentuserlevel_setup_options()
{
  add_option('showcontentuserlevel_exception_text', 'showcontentuserlevel', 
             "Text to replace hide text if user is not at a sufficient level",
             'yes');
}

?>