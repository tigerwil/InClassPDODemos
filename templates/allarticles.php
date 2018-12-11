<div class="icon-bar">
    <a href='articles.php' class='gridview' title='Grid View'><i class="fas fa-grip-horizontal"></i></a>
    <!--<a href='allarticles.php' class='tabularview'  title='Tabular View'><i class='fas fa-table'></i></a>-->
    
</div>
<div class="container">
    <h1 class="my-4">All Articles</h1>
    <?php           
        //Get all the pages
        //1.  Build the query
        $q="SELECT id, title, description FROM pages ORDER BY title";
        
        
        //2.  Execute the query (remember, we are using PDO, not MYSQLI)
        $stmt = $dbc->query($q);
        $articles= $stmt->fetchAll(PDO::FETCH_ASSOC);

        //var_dump($articles);
        //exit();
        //
        //start the table
        echo '<table id="tablesorted" class="table table-striped table-hover table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">View</th>
                    </tr>
                </thead>
                <tbody>';
                    foreach($articles as $article){
                        $id = $article['id'];
                        $title = $article['title'];
                        $description = $article['description'];

                        //create a tr for each record
                        echo "<tr>
                                <th scope='row'>$title</th>
                                <td>$description</td>
                                <td><a href='article.php?id=$id'>Read Article</a>  
                                     <i class='fa fa-eye' aria-hidden='true'></i>
                                </td>
                             </tr>";
                    }//end of foreach
            
            //end the table
            echo '</tbody></table>';
    
    ?>


</div>