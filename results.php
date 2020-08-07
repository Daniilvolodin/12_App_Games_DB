<?php
            
            if($count < 1){
             ?>
            <div class="error">
                Sorry! There are no results that match your search.
                Please use the search box in the side bar to try again.
            </div> <!-- end error -->
            
            <?php
            } // end no results if 
            else {
                do
                {
                   ?>
            
            <!-- Results go here -->
            
            <div class="results">
                    <!-- Heading and subtitle -->
                <div class="flex-container">
                    <div>

                     <span class="sub_heading">
                     <a href="<?php echo $find_rs['URL']; ?>">
                     <?php  echo $find_rs['Name'];?>
                     </a>
                     </span> 
                    
                    </div> <!-- / Title -->
                    <?php 
                        if($find_rs['Subtitle'] !='') 
                        {
                        ?>
                    <div>
                    &nbsp; &nbsp; | &nbsp; &nbsp; 
                       <?php echo $find_rs['Subtitle']?> 
                    </div> 
                    
                    <?php
                    
                            
                            
                        }
                    ?>
                
                </div>
                    <!-- / Heading and subtitle -->
                
                <div class="flex-container">
                    <div class="star-ratings-sprite">
                    <span style="width:<?php echo $find_rs['User Rating'] / 5 * 100?>%" class="star-ratings-sprite-rating"></span>
                    </div> <!-- / star rating div -->
                    
                    <div class="actual-rating">
                     
                        <b>Rating: </b>
                        (<?php echo $find_rs['User Rating']?> based on <?php echo $find_rs['Rating Count'] ?> votes)
                      
                        
                    </div> <!-- / ratings flex box-->
                    
                    
                </div> <!-- Rating flexbox -->
                
                
                <!-- Rating Area -->
                 
                
                
                
                <!-- Price -->
                <?php
                    if($find_rs['Price'] == 0) {
                    ?>
                    <p>
                        
                        <b>Price: </b>Free
                        <?php
                            if ($find_rs['In App'] == 1)
                            {
                             ?>
                                (In App Purchase)
                            <?php
                            } // end In App if
                        ?>
                        
                    
                
                    </p> 
                <?php    
                } // end price if
                    else{
                    ?>    
                    <p></p>
                    <b>Price: </b> $<?php echo $find_rs['Price'] ?>
                
                    <?php    
                    } // end price else (displays cost)
                
                ?>
                 
                <p> 
                    <b>Developer: </b>
                    <?php echo $find_rs['DevName'] ?>
                  </p>  
                  <p>         
                    <b>Genre: </b>
                    <?php echo $find_rs['Genre'] ?>
                   </p>  
                    <p>
                        <b>Age Rating:</b>  Suitable for ages <?php echo $find_rs['Age']?> and up
                    </p>
                   
                <p>
                    
                    <i><hr /><?php echo $find_rs['Description'] ?></i>
                </p>
                     
                    
            </div> <!-- / results -->
             
            <br />
           
            
            <?php
                    
                } // end results 'do'
                
                while
                    ($find_rs = mysqli_fetch_assoc($find_query));
            } // end else
            ?>