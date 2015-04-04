<?php
/*
Plugin Name: Podcast Pop Bookmarks
Plugin URI:  http://wordpress.org/extend/plugins/health-check/
Description: Checks the health of your WordPress install
Version:     0.1-alpha
Author:      The Health Check Team
Author URI:  http://wordpress.org/extend/plugins/health-check/
Text Domain: health-check
Domain Path: /lang
 */
   function pcpb_install () {
      global $wpdb;
      $pcpb_db_version = "1.0";

      $table_name = $wpdb->prefix . "pcpbplugin";

      $charset_collate = $wpdb->get_charset_collate();

      $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        episodeNumber smallint NOT NULL,
        startTime time NOT NULL DEFAULT '00:00:00',
        text varchar(60) DEFAULT '' NOT NULL,
        updatedAt timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY id (id)
      ) $charset_collate;";

      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      dbDelta( $sql );

      add_option( 'pcpb_db_version', $pcpb_db_version );

      $wpdb->insert( 
         $table_name, 
         array( 
            'episodeNumber' => 1,
            'startTime'     => '252525',
            'text'          => 'yo',
         ) 
      );
   }
   // Hook for adding admin menus
   add_action('admin_menu', 'mt_add_pages');
   register_activation_hook( __FILE__, 'pcpb_install' );

   function mt_add_pages() {
       add_options_page(__('Test Settings','menu-test'), __('Test Settings','menu-test'), 'manage_options', 'testsettings', 'mt_settings_page');
       add_menu_page("Hello World", "Podcast Pop Bookmarks", 'manage_options', 'mt-top-level-handle', 'mt_settings_page' );
   }

   // mt_toplevel_page() displays the page content for the custom Test Toplevel menu
   function podcastpop_bookmarks_toplevel_page() {
   	if (!current_user_can('manage_options'))  {
         wp_die( __('You do not have sufficient permissions to access this page.') );
      }
      echo "<h2> DOOOOOOOOOOD </h2>";
   }


      // mt_settings_page() displays the page content for the Test settings submenu
   function mt_settings_page() {

       //must check that the user has the required capability 
       if (!current_user_can('manage_options'))
       {
         wp_die( __('You do not have sufficient permissions to access this page.') );
       }

       // variables for the field and option names 
       $opt_name = 'mt_favorite_color';
       $hidden_field_name = 'mt_submit_hidden';
       $data_field_name = 'mt_favorite_color';

       // Read in existing option value from database
       $opt_val = get_option( $opt_name );

       // See if the user has posted us some information
       // If they did, this hidden field will be set to 'Y'
       if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
           // Read their posted value
           $opt_val = $_POST[ $data_field_name ];

           // Save the posted value in the database
           update_option( $opt_name, $opt_val );

           // Put an settings updated message on the screen

?>
         <div class="updated"><p><strong>Settings Saved EOR</strong></p></div>
         <?php

       }

       // Now display the settings editing screen

       echo '<div class="wrap">';

       // header

       echo "<h2>" . "Podcast Pop Bookmarks" . "</h2>";

       // settings form
          
      ?>
      Episode Number
      <select required id="selectEpisodeNumber"></select>
      Title for Display
      <input type="text" name="titleForDisplay" size=88>
      <input id="inputSaveTitle" type="submit" name="Submit" class="btn btn-primary" value="Save" />
      <hr/>
      <form action="" method="POST">
         Time <input class="time" name="inputTime" type="text" readonly>
         Bookmark Text <input id="idInputBookmarkText" name="inputBookmarkText" type="text" size=50>
      
         <input id="idInputNewBookmark" type="submit" name="inputNewBookmark" class="btn btn-primary" value="+ New Bookmark" />

      <?php
         if ( isset($_POST['inputBookmarkText']) ) {
            $x = $_POST['inputBookmarkText'];
            echo $x;
         }

         if ( isset($_POST['inputTime']) ) {
            $x = $_POST['inputTime'];
            echo $x;
         }
      ?>

      </form>

      
      Search:
      <input id="inputSearchBookmark" type="text" />
      <hr />

      <!-- bookmarks table -->
      <table id="tableBookmark" class="table table-striped">
         <thead>
            <tr>
               <th>Time</th>
               <th>Bookmark Text</th>
            </tr>
         </thead>
         <tr>
            <td>hi</td>
            <td>bye</td>
         </tr>
         <tr>
            <td>hi</td>
            <td>bye</td>
         </tr>
         <tr>
            <td>hi</td>
            <td>bye</td>
         </tr>
      </table>

      <input id="spinner" name="value">
      <input type="number" min="0" max="60" step="1" />

      </div>

      <script>
      console.log("poop");
      console.log("<?php global $wpdb; echo $wpdb->prefix ?>");
      </script>

//DIALOG MESSAGE
<div id="dialog-message" title="Pick a Time">
  <p>
    <span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
    Your files have downloaded successfully into the My Downloads folder.
  </p>
  <p>
    Currently using <b>36% of your storage space</b>.
  </p>
</div>


   </html>
<head>
   <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
   <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
</head>
      <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
      <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
      <?php $dir = plugins_url() . "/podcastpop" . "/js.js"; ?>
      <script src="<?php echo $dir ?>"></script>


      <?php
   }

?>
