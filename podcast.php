<!DOCTYPE html>
<html lang="en">
<h2>Podcast Pop Bookmarks</h2>
<div class="container-fluid">
    <form id="inputForm" method="POST">
        <div class="row">
            <div class="col-md-2" style="padding:0px;">
                Episode Number
                <select style="width:60px;" required name="episodeNumber" id="selectEpisodeNumber">
                    <option value="default">Select an episode...</option>
                </select>
            </div>
            <div class="col-md-5">
                Title for Display
                <input type="text" style="width:350px" name="titleForDisplay" placeholder="Enter a title" value?>" size=88>
            </div>
            <div class="col-md-2">
            </div>
            <div class="col-md-2" style="padding:0px;">
                <button style="float:right;" id="inputSaveTitle" type="submit" name="inputSaveTitle" class="glyphicon glyphicon-floppy-disk btn btn-primary" value="Save">Save</button>
            </div>
        </div><!--end row-->
        <p style="padding:0px;">
            <hr>
            <p>
                <div class="form-inline">
                    <div class="row">
                        <div class="col-md-2" style="padding:0px">
                            <div class="form-group has-feedback">
                                Time
                                <input placeholder="hh:mm:ss" style="width:130px;" name="inputTime" id="inputTime" type="text" class="form-control time">
                                <span class="glyphicon glyphicon-time form-control-feedback" aria-hidden="true"></span>
                            </div> <!-- end form-group -->
                        </div>
                        <div class="col-md-7">
                            Bookmark Text
                            <input id="idInputBookmarkText" class="form-control" name="inputBookmarkText" placeholder="Enter bookmark text" type="text" size=50>
                            <input id="idInputNewBookmark" onClick='validateBookMark()' type="submit" name="inputNewBookmark" class="btn btn-primary" value="+ New Bookmark"/>
                        </div>
                        <div class="col-md-3" style="float:right;">
                           Search <input placeholder="Search bookmark" name="search" id="inputSearchBookmark" type="text" value="<?php
                           if (isset($_POST['search'])) {
                             update_option("search_key", $_POST['search']);
                         }
                         echo get_option("search_key");
                         ?>"></input>
                     </div>
                 </div> <!-- end row -->
             </div>


         </div>

     </form>

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
</div><!-- End form inline -->
<br/>

<!-- bookmarks table -->

<table id="tableBookmark" class="table table-striped">
    <thead>
        <tr>
            <th>Time</th>
            <th>Bookmark Text</th>
        </tr>
    </thead>
    <?php
    global $wpdb;
    $table_plugin = $wpdb->prefix . "pcpbplugin";

    if (!isset($_COOKIE[$episodeNumber])) {
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
            echo "<td><div class='form-inline'><div class='form-group has-feedback'><input id='time$id' type='text' value=$bookmark->startTime class='time'></input>";
            echo "<span class='glyphicon glyphicon-time form-control-feedback' aria-hidden='true'></span>";
            echo "</div></div>";
            echo "</td>";
            echo "<td><textarea style='width: 50em; height: 2em;resize:none' multiline='true'>$bookmark->text</textarea></td>";
            $id = $bookmark->id;
            echo "<form method='POST'>";
            echo "<td><button type='submit' name='buttonDelete' value=$id class='optionButtonStyle glyphicon glyphicon-trash'></button></td>";
            echo "</form>";
            echo "</tr>";
        }

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
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <?php $css = plugins_url() . "/podcastpop" . "/style.css"; ?>
    <link rel="stylesheet" href="<?php echo $css ?>" >
</head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
<?php $dir = plugins_url() . "/podcastpop" . "/js.js"; ?>
<script src="<?php echo $dir ?>"></script>
</html>