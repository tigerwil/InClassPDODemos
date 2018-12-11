<div class="icon-bar">
    <!--<a href='' class='gridview' title='Grid View'><i class="fas fa-grip-horizontal"></i></a>-->
    <a href='allarticles.php' class='tabularview'  title='Tabular View'><i class='fas fa-table'></i></a>
    
</div>
<div class="container">
    <h1 class="my-4">Articles</h1>
    <?php
        if (isset($_GET['id'])  && (is_numeric($_GET['id']))   ){
               //FOR GET ONLY - Retrieve the URL parameter called id
               //good - retrieve the value
               $id = $_GET['id'];
              //Get specific articles based on id 
               $q= "SELECT id, title, description FROM pages 
                    WHERE category_id = $id ORDER BY title";               
               

        }else{
            
            //Get all the categories
            //1.  Build the query
            $q="SELECT id, title, description FROM pages ORDER BY title";           
           
        }
        
        //echo $q;
        
        //2.  Execute the query (remember, we are using PDO, not MYSQLI)
        $stmt = $dbc->query($q);
        $articles= $stmt->fetchAll(PDO::FETCH_ASSOC);

        //var_dump($articles);
        //exit();
        echo "<div class='row'>";
        foreach($articles as $row){
            echo "<div class='col-md-6 col-lg-4 mb-4'>
                    <div class='card h-100'>
                      <h4 class='card-header'>{$row['title']}</h4>
                      <div class='card-body'>
                        <p class='card-text'>{$row['description']}</p>
                      </div>
                      <div class='card-footer'>
                        <a href='article.php?id={$row['id']}' class='btn btn-primary'>Read More</a>
                      </div>
                    </div>
                </div>";
        }
        echo "</div>";
    
    ?>


</div>