<div class="container">  
    <nav aria-label="breadcrumb" class="my-4">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item"><a href="articles.php">Articles</a></li>
          <li class="breadcrumb-item active" aria-current="page">Article</li>
        </ol>
    </nav>
    <?php
        if (isset($_GET['id'])  && (is_numeric($_GET['id']))   ){
               //FOR GET ONLY - Retrieve the URL parameter called id
               //good - retrieve the value
               $id = $_GET['id'];
              //Get single article based on id (PK)
               $q= "SELECT id, title, description, content 
                    FROM pages WHERE id = $id";  
               
               //echo $q;
               $stmt = $dbc->query($q);
               $article= $stmt->fetch(PDO::FETCH_ASSOC);
               //var_dump($article);
               
               echo "<h3 class=\"my-4\">{$article['title']}</h3>";
               echo "<p>{$article['description']}</p>";
               echo "<div>{$article['content']}</div>";
               

        }else{
            //Show an alert
            echo "<h3 class=\"my-4\">Error Retrieving Article</h3>";
            echo "<div class=\"alert alert-danger\" role=\"alert\">
                    This page has been accessed in error! <br>
                    View all <a href='articles.php'>articles</a>
                 </div>";
            
        
        }
    
    ?>
    
</div>
