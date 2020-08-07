<?php include("topbit.php");
   
    $name_dev = $_POST['dev_name'];
    $find_sql = "SELECT * FROM `game_details`
     JOIN `genre` ON (game_details.GenreID = genre.GenreID)
     JOIN `Developer` ON (game_details.DeveloperID = Developer.DeveloperID)
     WHERE `Name` LIKE '%$name_dev%' OR `DevName` LIKE '%$name_dev%'
    ";
    $find_query = mysqli_query($dbconnect,$find_sql);
    $find_rs = mysqli_fetch_assoc($find_query);
    $count = mysqli_num_rows($find_query);  
?>
                       
            
        <div class="box main">
            <h2>Welcome</h2>
            
            <?php include("results.php");?>
            <p>
                This database includes some of the most popular apps available for mobile devices. The <a href="https://www.kaggle.com/tristan581/17k-apple-app-store-strategy-games">original dataset</a> can be found at the preceding link.
            </p>
            
            

            
        </div> <!-- / main -->
        
<?php include("bottombit.php")?>