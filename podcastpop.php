<?php
/*
Plugin Name: Podcast Pop
Plugin URI:  healthdrugsmarts.com
Description: Podcast pop bookmarks
Version:     0.1-alpha
Author:      Anthony Guevara
Author URI:  http://wordpress.org/extend/plugins/health-check/
Text Domain: Hey
Domain Path: /lang
 */
 define('WP_DEBUG', true);
 function pippin_example_shortcode( $atts )	{
    global $wpdb;
    $table_plugin = $wpdb->prefix . "pcpbplugin";

    $a = shortcode_atts( array( 'episode' => '0'
        ), $atts );

    $episodeNumber = $a['episode'];

    $bookmarks = $wpdb->get_results("SELECT * FROM $table_plugin WHERE episodeNumber = " .
        $episodeNumber . " ORDER BY startTime ASC");

    $title = get_option('title_for_display');

    if (!$title)
        $title = "Episode Bookmarks";

    $concat = "<div id='podcast-bookmarks'><h2>$title</h2>
    <table border='0' cellpadding='0' cellspacing='0' style='border: none;'>";

        if ($bookmarks) {
            foreach ($bookmarks as $bookmark) {
                $concat .= "<tr>";
                $concat .= "<ul>";
                $concat .= "<td width='30%' style='border: none;'><li>";
                $concat .= $bookmark->startTime;
                $concat .= "</td>";
                $concat .= "</li></ul>";
                $concat .= "<td width='70%' style='border: none;'>";
                $concat .= $bookmark->text;
                $concat .= "</td>";
                $concat .= "</tr>";
            }
        }
        else {
            $concat .= "No bookmarks for this episode.";
        }

        $concat .= "</table></div>";

	// this will display our message before the content of the shortcode
        return $concat;

    }
    add_shortcode('podcastpop', 'pippin_example_shortcode');
    register_deactivation_hook( __FILE__, 'pcbb_deactivate' );
    /*add_shortcode('podcastpop', 'podcastpop_shortcode');*/
    function pcbb_deactivate () {
  //deactivate function does here
    }
/*
  Create new database tables for plugin and title
*/
  function pcpb_install () {
    global $wpdb;
    $pcpb_db_version = "1.0";

    $pluginTable = $wpdb->prefix . "pcpbplugin";

    $charset_collate = $wpdb->get_charset_collate();

    $pluginTable = "CREATE TABLE $pluginTable (
       id mediumint(9) NOT NULL AUTO_INCREMENT,
       episodeNumber smallint NOT NULL,
       startTime time NOT NULL DEFAULT '00:00:00',
       text varchar(60) DEFAULT '' NOT NULL,
       updatedAt timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       UNIQUE KEY id (id)
       ) $charset_collate;";


 require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
 dbDelta( $pluginTable );

 add_option( 'pcpb_db_version', $pcpb_db_version );
 add_option("search_key", '');
 add_option("title_for_display", 'Episode Highlights');
}

// Hook for adding admin menus
add_action('admin_menu', 'mt_add_pages');
register_activation_hook( __FILE__, 'pcpb_install' );
add_action( 'admin_init', 'wpse_remove_footer' );

function wpse_remove_footer()
{
    add_filter( 'admin_footer_text',    '__return_false', 11 );
    add_filter( 'update_footer',        '__return_false', 11 );
}

function mt_add_pages() {
    add_menu_page("Hello World", "Podcast Pop Bookmarks", 'manage_options', 'mt-top-level-handle', 'mt_settings_page' );
}

function mt_settings_page() {

    //must check that the user has the required capability
    if (!current_user_can('manage_options'))
    {
        wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    ?>
<!DOCTYPE html>
<html lang="en">
<h2>Podcast Pop Bookmarks</h2>
<div class="container-fluid">

    <form id="inputForm" method="POST">
        <div class="row">
            <div class="col-md-2" style="padding-top:15px;">
                <select class="form-control" style="width:190px;height:40px;background: linear-gradient(rgb(208,208,208), white);font-size:17px;" required name="episodeNumber" id="selectEpisodeNumber">
                </select>
            </div>
            <br/>
            <div class="col-md-5" stlye="padding:0px;">
                <div class="form-inline">
                    <div class="form-group" style="padding-bottom:15px;">
                        <label data-toggle="tooltip" title="This is the heading that will be shown above the bookmarks-list on your web pages." for="inputTitlte">Title for Display</label>
                        <input class="form-control" style="width:370px;" id="inputTitle" type="text" style="width:350px" name="titleForDisplay" placeholder="Enter a title" value="<?php
                            $title = get_option('title_for_display');
                            $new_title = $_POST['titleForDisplay'];
                            
                            if (!empty($_POST) && isset($new_title)) {
                                update_option('title_for_display', $new_title);
                                echo $new_title;
                            }
                            else {
                                echo $title;
                            }?>" size=88>
                            <div style="padding:0px;">
                                <?php $dir = plugins_url() . "/podcastpop" . "/title-for-display-example.jpg"; ?>
                                <a href="<?php echo $dir ?>" target="_blank">Example</a>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
            </div>
            <div class="col-md-2" style="padding:0px">
                <button style="float:right;" id="inputSaveTitle" type="submit" name="inputSaveTitle" class="glyphicon glyphicon-floppy-disk btn btn-primary" value="Save">Save</button>
            </div>
        </div><!--end row-->
        <hr/>
        <div id="bookmarkForm" class="form-inline">
            <div class="row">
                <div class="col-md-2" style="padding:0px">
                    <label data-toggle="tooltip" title="Enter the start time of the bookmark, using format hh:mm:ss."><b>Time</b></label>
                    <div class="input-group">
                        <input placeholder="hh:mm:ss" style="width:130px;" name="inputTime" id="inputTime" type="text" class="form-control">
                        <div class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </div>
                    </div> <!-- end input-group -->
                </div>
                <div class="col-md-7">
                    <b>Bookmark Text</b>
                    <input id="idInputBookmarkText" class="form-control" name="inputBookmarkText" placeholder="Enter bookmark text" type="text" size=50>
                    <input id="idInputNewBookmark" onClick='validateBookMark();' name="inputNewBookmark" class="btn btn-primary" value="+ New Bookmark"/>
                </div>
                <div class="col-md-3" style="float:right;">
                   <b>Search</b> <input placeholder="Search bookmark" name="search" id="inputSearchBookmark" type="text" class="form-control">
                </div>
            </div> <!-- end row -->
        </div> <!-- end form inline -->
    </form> <!-- end form -->

     <?php
     global $wpdb;
     $table_plugin = $wpdb->prefix . "pcpbplugin";
     $errorMessage = "";
     $title = $_POST['titleForDisplay'];
     $episodeNumber = $_POST['episodeNumber'];
     $bmtext = "";
     $inputTime = "";
     $search = "";

     if (isset($_POST['inputNewBookmark'])) {
        if ( !empty($_POST['inputBookmarkText']) && !empty($_POST['inputTime']) ) {
            $bmtext = $_POST['inputBookmarkText'];
    //base64_encode($article_code);
            $inputTime = $_POST['inputTime'];

            if  ( (substr_count($inputTime, ":")) == 1)
             $inputTime = "00:" . $inputTime;

         $wpdb->insert(
            $table_plugin,
            array(
                'episodeNumber' => $episodeNumber,
                'startTime'     => $inputTime,
                'text'          => $bmtext,
                )
            );
        }
    }
    else if (isset($_POST['buttonDelete'])) {
        $id =  $_POST['buttonDelete'];
        $wpdb->query("DELETE FROM $table_plugin WHERE `id` = " . $id);
    }
?>

    <hr/>

    <!-- bookmarks table -->
        <?php
        global $wpdb;
        $table_plugin = $wpdb->prefix . "pcpbplugin";

        if (!isset($_COOKIE[$episodeNumber])) {
            $episodeNumber = $_COOKIE['episodeNumber'];
        } else {
            $episodeNumber = "default";
        }

        if (isset($_POST['search']))
            $search = $_POST['search'];

        $explodedSearch = explode(" ", $search);
        $startSearch = array_shift($explodedSearch);
        $ee = "";
        foreach ($explodedSearch as $es)
            if (!empty($es))
                $ee .= "OR text LIKE '%$es%' ";

            $bookmarks = $wpdb->get_results("SELECT * FROM $table_plugin WHERE episodeNumber =
             $episodeNumber AND (text LIKE '%$startSearch%' $ee) ORDER BY startTime ASC");

            $title = $wpdb->get_row("SELECT * FROM $table_title WHERE episodeNumber = " .
                $episodeNumber);


            if ($bookmarks) {
                    echo "<table id='tableBookmark' class='table table-striped'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Time</th>";
                    echo "<th>Bookmark Text</th>";
                    echo "</tr>";
                    echo "</thead>";
                foreach ( $bookmarks as $bookmark )
                {
                    echo "<tr>";
                    echo "<td width='15%''>";
                    echo "<div class='input-group'>";
                    echo "<input class='form-control' id='time$id' value=$bookmark->startTime class='time'></input>";
                    echo "<div class='input-group-addon'>";
                    echo "<span class='glyphicon glyphicon-time'></span>";
                    echo "</div>";//end input group addon
                    echo "</div>";//end input group
                    echo "</td>";
                    echo "<td width='80%'><textarea style='width: 65em; height: 2em;resize:none' multiline='true'>$bookmark->text</textarea></td>";
                    $id = $bookmark->id;
                    echo "<form id='deleteForm' method='POST'>";
                    echo "<td><button onClick='Javascript:alert('hi');' type='button' name='buttonDelete' value=$id class='deleteButton glyphicon glyphicon-trash'></button></td>";
                    echo "</form>";
                    echo "</tr>";
                }
            }
            else
                echo "There are no bookmarks to display.";

            ?>

    </table>

</div> <!-- end container -->
<!-- </div> -->
<!-- retired code for time selection
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
-->

<head>
    <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <?php $css = plugins_url() . "/podcastpop" . "/style.css"; ?>
    <link rel="stylesheet" href="<?php echo $css ?>" >
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
    <?php $dir = plugins_url() . "/podcastpop" . "/js.js"; ?>
    <script src="<?php echo $dir ?>"></script>
</head>
</html>
    <?php
}

add_action( 'admin_footer', 'my_action_javascript' ); // Write our JS below here

function my_action_javascript() { ?>
    <script type="text/javascript" >
    jQuery(document).ready(function($) {

        var data = {
            'action': 'my_action',
            'whatever': 10
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        $.post(ajaxurl, data, function(response) {
            alert('Got this from the server: ' + response);
        });
    });
    </script> <?php
}

add_action( 'wp_ajax_my_action', 'my_action_callback' );

function my_action_callback() {
    global $wpdb; // this is how you get access to the database

    $whatever = intval( $_POST['whatever'] );

    //$whatever += 10;

        echo $action;

    //wp_die(); // this is required to terminate immediately and return a proper response
}

?>
