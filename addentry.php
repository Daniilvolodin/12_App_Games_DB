<?php include("topbit.php");

// Get Genre list from database
$genre_sql = "SELECT * FROM `genre` ORDER BY `genre`.`Genre` ASC";
$genre_query = mysqli_query($dbconnect,$genre_sql);
$genre_rs = mysqli_fetch_assoc($genre_query);
// Initialise form variables
$app_name = "";
$subtitle = "";
$url = "";
$dev_name = "";
$age = "";
$rating = "";
$rate_count = "";
$cost = "";
$in_app = 1; 
$description = "Please enter a description";
$has_errors = "no";


// set up error field colours / visibility (no errors at first)
$app_error = $url_error = $dev_error = $description_error = $genre_error = $rating_error = $count_error =$cost_error = $age_error =  "no-error";
$app_field = $url_field = $dev_field = $description_field = $genre_field = $rating_field = $count_field = $cost_field = $age_field = "form-ok";


// Code below executes when the form is submitted...
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get values from form...
    $app_name = mysqli_real_escape_string($dbconnect, $_POST['app_name']);
    $subtitle = mysqli_real_escape_string($dbconnect, $_POST['subtitle']);
    $url = mysqli_real_escape_string($dbconnect, $_POST['url']);
    $genreID = mysqli_real_escape_string($dbconnect, $_POST['genre']);
   
    
    // if genreid is not blank, get genre so that genre box does not lose its value if there are errors
    
    if ($genreID != "") {
        $genreitem_sql = "SELECT * FROM `genre` WHERE `GenreID` = $genreID";
        $genreitem_query = mysqli_query($dbconnect, $genreitem_sql);
        $genreitem_rs = mysqli_fetch_assoc($genreitem_query);
        $genre = $genreitem_rs['Genre'];
    } // End GenreID if
   
    
    
    $dev_name = mysqli_real_escape_string($dbconnect, $_POST['dev_name']);
    $age = mysqli_real_escape_string($dbconnect, $_POST['age']);
    $rating = mysqli_real_escape_string($dbconnect, $_POST['rating']);
    $rate_count = mysqli_real_escape_string($dbconnect, $_POST['rate_count']);
    $cost = mysqli_real_escape_string($dbconnect, $_POST['cost']);
    $in_app = mysqli_real_escape_string($dbconnect, $_POST['in_app']);
    $description = mysqli_real_escape_string($dbconnect, $_POST['description']);

     
    // error checking will go here...
    // Check App Name is not blank
    if($app_name == "") {
        $has_errors = "yes";
        $app_error = "error-text";
        $app_field = "form-error";
    }
    // Remove all illegal characters from a url
    $url = filter_var($url, FILTER_SANITIZE_URL);
    if(filter_var($url,FILTER_VALIDATE_URL) == false){
       $has_errors = "yes";
       $url_error = "error-text";
       $url_field = "form-error"; 
    }
    // Check If Genre Isn't blank
    if ($genreID ==""){
       $has_errors = "yes";
       $genre_error = "error-text";
       $genre_field = "form-error";  
    }
    if ($age == "" || $age == "0"){
        $age = 0;
        $age_message = "The age has been set to 0 (ie: all ages)";
        $age_error = "defaulted";
        
    }
    else if (!ctype_digit($age) || $age<0){
        $age_message = "Please enter an integer that is 0 or more";
        $has_errors = "yes";
        $age_field = "form-error";
    }
    if ($cost == "" || $cost == "0") {
        $cost = 0;
        $cost_message = "The price has been set to 0 (ie: free)";
        $cost_error = "defaulted";
    }
    else if (!is_numeric($cost) || $cost < 0) {
        $cost_message = "Please enter a number that is 0 or more";
        $cost_error = "error-text";
        $cost_field = "form-error";
    }
    
    
    // Check If DevName isn't blank
    if ($dev_name == ""){
       $has_errors = "yes";
       $dev_error = "error-text";
       $dev_field = "form-error";  
    }
    // check rating is a decimal between 0 and 5
    if (!is_numeric($rating) || $rating < 0 || $rating > 5){
       $has_errors = "yes";
       $rating_error = "error-text";
       $rating_field = "form-error";     
    }
    // check number of ratings is an integer that is more than 0
    if (!ctype_digit($rate_count) || $rate_count < 1){
        $has_errors = "yes";
        $count_error = "error-text";
        $count_field = "form-error";
    }
    
    if ($description == "" || $description == "Please enter a description"){
      $has_errors = "yes";
      $description_error = "error-text";
      $description_field = "form-error";   
      $description = "";
    }
    // if there are no errors...
    if ($has_errors == "no") {
       // Go to success page...
    header('Location: add_success.php');
       // get developer ID if ut exists...
       $dev_sql = "SELECT * FROM `Developer` WHERE `DevName` LIKE '$dev_name'";
       $dev_query = mysqli_query($dbconnect, $dev_sql);
       $dev_rs = mysqli_fetch_assoc($dev_query);
       $dev_count = mysqli_num_rows($dev_query);
       
        // if developer not already in developer table, add them and get the 'new' developerID
        if ($dev_count > 0) {
            $developerID = $dev_rs['DeveloperID'];
            
        }
        else {
        $add_dev_sql = "INSERT INTO `volodind70370`.`Developer` (`DeveloperID`, `DevName`) VALUES (NULL, '$dev_name');";
        $add_dev_query = mysqli_query($dbconnect,$add_dev_sql);
        
        // Get developer ID
        $newdev_sql = "SELECT * FROM `Developer` WHERE `DevName` LIKE '$dev_name'";
        $newdev_query = mysqli_query($dbconnect, $newdev_sql);
        $newdev_rs = mysqli_fetch_assoc($newdev_query);
        $developerID = $newdev_rs['DeveloperID'];
        } // end adding developer to developer table
    
       // Add entry to database
       $addentry_sql = "INSERT INTO `volodind70370`.`game_details` (`ID`, `Name`, `Subtitle`, `URL`, `GenreID`, `DeveloperID`, `Age`, `User Rating`, `Rating Count`, `Price`, `In App`, `Description`) VALUES (NULL, '$app_name', '$subtitle', '$url', '$genreID', '$developerID', '$age', '$rating', '$rate_count', '$cost', '$in_app', '$description');";
        $addentry_query = mysqli_query($dbconnect,$addentry_sql); 
        
        $getid_sql = "SELECT * FROM `game_details` WHERE 
        `Name` LIKE '$app_name'
        AND `Subtitle` LIKE '$subtitle'
        AND `URL` LIKE '$url'
        AND `GenreID` LIKE '$genreID'
        AND `DeveloperID` LIKE '$developerID'
        AND `Age` = $age
        AND `User Rating` = $rating
        AND `Rating Count` = $rate_count
        AND `Price` = $cost
        AND `In App` = $in_app
        ";
        $getid_query = mysqli_query($dbconnect, $getid_sql);
        $getid_rs = mysqli_fetch_assoc($getid_query);
        
        $ID = $getid_rs['ID'];
        $_SESSION['ID'] = $ID;
        
        
    } // end of 'no errors' if
   
    
    
    
} // end of button submitted code


?>
                       
            
        <div class="box main">
            <div class="add-entry">
            <h2>Add an Entry</h2>
            
            <form method="post"  enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            
            <div class="<?php echo $app_error; ?>">
                Please fill in the 'App Name' field    
            </div>
            <!-- App Name (Required) -->
            <input class="new-input <?php echo $app_field; ?>" type="text" name = "app_name" 
             value="<?php echo $app_name; ?>"  placeholder="App Name (Required) ..."/> 
            <div>    
            <!-- Subtitle (optional) -->
            <input class="new-input" type="text" name="subtitle" value="<?php echo $app_name; ?>" placeholder="Subtitle (optional)..."/>
            </div>
            <div>
            <!-- URL (required, must start http://) -->
            <div class="<?php echo $url_error;?>">
                Please provide a valid URL    
            </div>
            
            <input class="new-input <?php echo $url_field;?>" type="text" name="url"  size="40" value="<?php echo $url; ?>"  placeholder="URL (Required)"/>
            </div>
            
            <!-- Genre dropdown (Required) -->
            <div class = "<?php echo $genre_error; ?>">
                Please Choose A Genre
            </div>
            <div>    
            <select class="genre_drop <?php echo $genre_field; ?>" name="genre">
                <?php
                
                if($genreID =="") {
                
                ?>
                <option  value=""  selected>Genre (Choose something)...</option>
                
                <?php
                }
                else{
                    ?>
                <option value="<?php echo $genreID; ?>" selected><?php echo $genre; ?></option>
                
                <?php
                }
                
                ?>
                
            <!-- get options from the database -->
            <?php
            do{
                ?>
                <option value="<?php echo $genre_rs['GenreID'];?>"><?php echo $genre_rs['Genre']; ?></option>
            <?php
            } // end genre do loop
            while ($genre_rs = mysqli_fetch_assoc($genre_query))
            ?>
            </select>
            </div>    
            
            <div class="<?php echo $dev_error; ?>">
                Developer Name Cannot Be Blank
            </div>
           
            <!-- Developer Name (Required) -->
            <input class="new-input <?php echo $dev_field; ?>" type="text" name="dev_name" value="<?php echo $dev_name; ?>" size="40"  placeholder="Developer Name (Required) ..."/>
                <div class="<?php echo $age_error; ?>">
                <?php echo $age_message; ?>
                </div>
            
            <!-- Age (Set to 0 if left blank) -->
            <input class="new-input <?php echo $age_field; ?>" type="text" name="age" value="<?php echo $age; ?>" placeholder="Age (0 for all)..."/>
                
            
            <!-- Rating (Number between 0 and 5, 1dp) -->
            <div class="<?php echo $rating_error; ?>">
                Please Enter A Valid Rating
            </div>
            <div>
                <input class="new-input <?php echo $rating_field; ?>" type="number" name="rating"  value="<?php echo $rating;?>"  step="0.1" min=0 max=5 placeholder="Rating (0-5)"/>
                
            </div>
                
            <!-- # number of ratings (integer more than 0) -->
            <div class="<?php echo $count_error; ?>">
                Please Enter A Valid Rating Count
            </div>
            
            <input class="new-input <?php echo $count_field; ?>" type="number" name="rate_count"  value="<?php echo $rate_count; ?>"  step='1'  placeholder="Number Of Ratings"/>
            
            <!-- Cost (Decimal 2dp, must be more than 0) -->
            <div class="<?php echo $cost_error; ?>">
                <?php echo $cost_message; ?>
            
            </div>
            
            
            <input class="new-input <?php echo $cost_field; ?>" type="text" name="cost" value="<?php echo $cost;?>" placeholder="Cost (Numbers Only)"/>
            
            
                
            <!-- In App Purchase Radio buttons -->
            <div>
                <b>In App Purchases:</b>
                <?php
                if($in_app == 1){
                    // Default value, 'Yes' is selected
                ?>
               
                <input type="radio" name="in_app" value="1" checked="checked">Yes
                <input type="radio"  name="in_app" value="0">No
                
                <?php
                }
                else{
                    ?>
                
                <input type="radio" name="in_app" value="1" >Yes
                <input type="radio"  name="in_app" value="0" checked="checked">No
                <?php
                } // end 'in_app' else
                ?>
                
                <!-- defaults to 'yes' -->
                <!-- NOTE: value in database is boolean, so 'no' becomes 0 and 'yes' becomes 1 -->
                
               
            
                
            
            
            </div>    
                
            <br/>
            <!-- Description text area -->
            <div class="<?php echo $description_error; ?>" placeholder="Please Enter A Description"> </div>
            <textarea class="add-field <?php echo $description_field; ?>" name="description"  value ="<?php echo $description_field; ?>" placeholder="Description..." rows="6"><?php echo $description; ?></textarea>
          
            <!-- Submit Button -->
            <p>
                <input class="submit submit_button" type="submit" value="Submit" />    
                   
            
            </p>
                
            </form>
    

           </div> <!-- /add entry --->
        </div> <!-- / main -->
        
<?php include("bottombit.php")?>