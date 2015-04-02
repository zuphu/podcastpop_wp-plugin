<?php
   // Hook for adding admin menus
   add_action('admin_menu', 'mt_add_pages');


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
      <select required id="selectEpisodeNumber">
         <option value="1">1</option>
         <option value="2">2</option>
         <option value="3">3</option>
         <option value="4">4</option>
         <option value="5">5</option>
         <option value="6">6</option>
      </select>
      Title for Display
      <input type="text" name="titleForDisplay" size=88>
      <form name="form1" method="post" action="">
      <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

      <p>Favourite Colour 
      <input type="text" name="<?php echo $data_field_name; ?>" value="<?php echo $opt_val; ?>" size="20">
      </p><hr />

      <p class="submit">
      <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
      </p>

      </form>
      </div>
      <script>
      document.body.onload = function(e) {
        var x = document.getElementById("selectEpisodeNumber");
        for (xx = 0; xx <= 500; ++xx) {
           var option = document.createElement("option");
           option.text = xx;
           x.add(option);
        }
      }
      </script>

      <?php
   }

?>
