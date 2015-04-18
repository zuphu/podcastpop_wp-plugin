 <?php
/*
  Plugin Name: Podcast Pop Bookmarks
  Plugin URI:  http://wordpress.org/extend/plugins/health-check/
  Description: Bookmark your podcasts!!!
  Version:     0.1-alpha
  Author:      The Health Check Team
  Author URI:  http://wordpress.org/extend/plugins/health-check/
  Text Domain: health-check
  Domain Path: /lang
*/
/*
  function podcastpop_shortcode( $atts, $content = null)	{

  extract( shortcode_atts( array('message' => ''),
  $atts ));
   
  // this will display our message before the content of the shortcode
  return 'boobs ' . $message . 'boogers' . $content;
  }
*/
function pippin_example_shortcode( $atts )	{
    global $wpdb;
    $table_plugin = $wpdb->prefix . "pcpbplugin";
    $table_title  = $wpdb->prefix . "pcpbptitle";

    $a = shortcode_atts( array( 'episode' => '0'
    ), $atts );

    $episodeNumber = $a['episode'];
    
    $bookmarks = $wpdb->get_results("SELECT * FROM $table_plugin WHERE episodeNumber = " . 
                                    $episodeNumber . " ORDER BY startTime DESC");

    $title = $wpdb->get_row("SELECT * FROM $table_title WHERE episodeNumber = " .
                            $episodeNumber);
    
    $concat = "<h2>$title->episodeTitle</h2>
    <table>
    <thead>
    <tr>
    <th>Time</th>
    <th>Bookmark Text</th>
    </tr>
    </thead>";
    
    foreach ($bookmarks as $bookmark) {
        $concat .= "<tr>";
        $concat .= "<td>";
        $concat .= $bookmark->startTime;
        $concat .= "</td>";
        $concat .= "<td>";
        $concat .= $bookmark->text;
        $concat .= "</td>";
        $concat .= "</tr>";
    }

    $concat .= "</table>";


	// this will display our message before the content of the shortcode
	return $concat;
 
}
add_shortcode('podcastpop', 'pippin_example_shortcode');

/*add_shortcode('podcastpop', 'podcastpop_shortcode');*/

/*
  Create new database tables for plugin and title
*/
function pcpb_install () {
    global $wpdb;
    $pcpb_db_version = "1.0";

    $pluginTable = $wpdb->prefix . "pcpbplugin";
    $pluginTitle = $wpdb->prefix . "pcpbptitle";

    $charset_collate = $wpdb->get_charset_collate();

    $pluginTable = "CREATE TABLE $pluginTable (
     id mediumint(9) NOT NULL AUTO_INCREMENT,
     episodeNumber smallint NOT NULL,
     startTime time NOT NULL DEFAULT '00:00:00',
     text varchar(60) DEFAULT '' NOT NULL,
     updatedAt timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
     UNIQUE KEY id (id)
   ) $charset_collate;";

    $pluginTitle = "CREATE TABLE $pluginTitle (
     id mediumint(9) NOT NULL AUTO_INCREMENT,
     episodeNumber smallint NOT NULL,
     episodeTitle varchar(60) DEFAULT '' NOT NULL,
     updatedAt timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
     UNIQUE KEY id (id)
   ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $pluginTable );
    dbDelta( $pluginTitle );

    add_option( 'pcpb_db_version', $pcpb_db_version );

    $wpdb->insert( 
        $table_name, 
        array( 
            'episodeNumber' => 1,
            'startTime'     => '252525',
            'text'          => 'yo',
        ) 
    );
    add_option("search_key", '');
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
    <form id="inputForm" method="POST">
    Episode Number
        <select required name="episodeNumber" id="selectEpisodeNumber">
    <option value="default">Select an episode...</option>
    </select>

    Title for Display 
        <input type="text" name="titleForDisplay" placeholder="Enter a title" value="
<?php 
global $wpdb;
$episodeNumber = $_COOKIE['episodeNumber'];
$table_title  = $wpdb->prefix . "pcpbptitle";

if (isset($_POST['inputSaveTitle'])) {
   if ( !empty($_POST['titleForDisplay']) ) {
      $title = $_POST['titleForDisplay'];
      $wpdb->query("DELETE FROM $table_title WHERE `episodeNumber` = " . $episodeNumber);
                           
            $wpdb->insert(
                $table_title,
                array(
                    'episodeNumber' => $episodeNumber,
                    'episodeTitle'  => $title,
                )
            );
        }
        else {
            echo "Please enter a valid title!";
        }
    }

$title = $wpdb->get_row("SELECT * FROM $table_title WHERE episodeNumber = " .
   $episodeNumber);

echo $title->episodeTitle;
?>" size=88>
    <input id="inputSaveTitle" type="submit" name="inputSaveTitle" class="btn btn-primary" value="Save" />
    <hr/>
    Time<input id="inputTime" class="time" name="inputTime" placeholder="Click to select time" type="text">

    Bookmark Text<input id="idInputBookmarkText" name="inputBookmarkText" placeholder="Bookmark text" type="text" size=50>
   
    <input id="idInputNewBookmark" type="submit" name="inputNewBookmark" class="btn btn-primary" value="+ New Bookmark"/>
    Search <input placeholder="Search bookmark" name="search" id="inputSearchBookmark" type="text" value="<?php
    if (isset($_POST['search'])) {
       update_option("search_key", $_POST['search']);
    }
    echo get_option("search_key");
?>"></input>
    </form>

<?php
    global $wpdb;
    $table_plugin = $wpdb->prefix . "pcpbplugin";
    $table_title = $wpdb->prefix . "pcpbptitle";
    $errorMessage = "";
    $title = $_POST['titleForDisplay'];
    $episodeNumber = $_POST['episodeNumber'];
    $bmtext = "";
    $inputTime = "";
    $search = "";
    
    if (isset($_POST['inputNewBookmark'])) {
        if ( !empty($_POST['inputBookmarkText']) &&
             !empty($_POST['inputTime'])) {
            $bmtext = $_POST['inputBookmarkText'];
            //base64_encode($article_code);
            $inputTime = $_POST['inputTime'];

            $wpdb->insert( 
                $table_plugin, 
                array( 
                    'episodeNumber' => $episodeNumber,
                    'startTime'     => $inputTime,
                    'text'          => $bmtext,
                ) 
            );
        }
        else
            echo "Both Time and Bookmark text need to be set.";
    }
    else if (isset($_POST['buttonDelete'])) {
        $id =  $_POST['buttonDelete'];
        $wpdb->query("DELETE FROM $table_plugin WHERE `id` = " . $id);
    }
    else {
        $errorMessage = "Please enter a time and bookmark text";
        echo $errorMessage;
    }
    ?>

    <!-- bookmarks table -->

    <table id="tableBookmark" class="table table-striped">
    <thead>
    <tr>
    <th>Time</th>
    <th>Bookmark Text</th>
    <th>Options</th>
    </tr>
    </thead>
<?php
    global $wpdb;
    $table_plugin = $wpdb->prefix . "pcpbplugin";
    $table_title  = $wpdb->prefix . "pcpbptitle";

    if(!isset($_COOKIE[$episodeNumber])) {
        $episodeNumber = $_COOKIE['episodeNumber'];
    } else {
        $episodeNumber = "default";
    }

    $search = get_option("search_key");
    $explodedSearch = explode(" ", $search);
    $startSearch = array_shift($explodedSearch);
    $ee = "";
    foreach ($explodedSearch as $es)
        if (!empty($es))
            $ee .= "OR text LIKE '%$es%' ";

    $bookmarks = $wpdb->get_results("SELECT * FROM $table_plugin WHERE episodeNumber =
       $episodeNumber AND (text LIKE '%$startSearch%' $ee) ORDER BY startTime DESC");

    $title = $wpdb->get_row("SELECT * FROM $table_title WHERE episodeNumber = " .
                            $episodeNumber);

    foreach ( $bookmarks as $bookmark ) 
    {
        echo "<tr>";
        echo "<td><input name='$id' type='text' value=$bookmark->startTime class='time'></input></td>";
        echo "<td><textarea style='width: 50em; height: 2em;resize:none' multiline='true'>$bookmark->text</textarea></td>";
        $id = $bookmark->id;
        echo "<form method='POST'>";
        echo "<td><button type='submit' name='buttonDelete' value=$id class='optionButtonStyle glyphicon glyphicon-trash'></button></td>";
        echo "</form>";
        echo "</tr>";
    }

    ?>

    </table>

    </div>

    <script>
    console.log("<?php global $wpdb; echo $wpdb->prefix ?>");
    </script>

    <div style="visibility:hidden;">
    <div id="dialog-message" title="Pick starting time">
    <table>
    <tr>
    <td>Hour:</td><td><input id="inputHour" maxlength="2" value=0 min="0" max="59" type="number"></td>
    <td><div id="errorHour"></div></td>
    </tr>
    <tr>
    <td>Minute:</td><td><input id="inputMinute" maxlength="2" value=0 min="0" max="59" type="number"></td>
    <td><div id="errorMinute"></div></td>
    </tr>
    <tr>
    <td>Second</td><td><input id="inputSecond" maxlength="2" value=0 min="0" max="59" type="number"></td>
    <td><div id="errorSecond"></div></td>
    </tr>
    </table>
    </div>
    </div>

    </html>
    <head>
    <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <?php $css = plugins_url() . "/podcastpop" . "/style.css"; ?>
    <link rel="stylesheet" href="<?php echo $css ?>" >
    </head>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
<?php $dir = plugins_url() . "/podcastpop" . "/js.js"; ?>
    <script src="<?php echo $dir ?>"></script>


<?php
}

?>
