<ul class="navbar-nav ml-auto">
    <?php
        //Convert the static menu to a dynamic menu using a multi-dimension
        //array:  (label, link, icon)
        $links = array(
            'Home'      =>array('link'=>SITE_URI,'icon'=>'home'),
            'About'     =>array('link'=>SITE_URI.'about.php','icon'=>'question-circle'),
            'Contact'   =>array('link'=>SITE_URI.'contact.php','icon'=>'envelope')
        );
        
        //var_dump($links);
        //exit();
        
        //Find out which page user is viewing
        $current_page = $_SERVER['REQUEST_URI'];
        //echo $current_page;   
        
        
        //exit();
        
        //Loop the array and check if array page matches the current page
        foreach($links as $page=>$link){
            echo '<li class="nav-item';
            if($current_page == $link['link']){
                echo ' active">';
            }else{
                echo '">';
            }
            echo "<a class='nav-link' href='{$link['link']}'>
                     <i class='fas fa-{$link['icon']}'></i> $page</a></li>";
        }               
                
        
    ?>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownArticles" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Articles
      </a>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownPortfolio">
          <a class="dropdown-item" href="articles.php"><i class="fas fa-file-alt"></i> All Articles</a>
          <?php
            //1.  Build the query
            $q = "SELECT id, category,Summary.total 
                  FROM categories JOIN (SELECT COUNT(*) AS total, category_id
                                        FROM pages
                                        GROUP BY category_id) AS Summary
                  WHERE categories.id = Summary.category_id
                  ORDER BY category";
                    
            
            //2.  Execute the query (remember, we are using PDO, not MYSQLI)
            $stmt = $dbc->query($q);
            $category_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            //var_dump($category_list);
            //exit();
            foreach($category_list as $row){
                echo "<a class='dropdown-item' href='articles.php?id={$row['id']}'><span class='badge badge-pill badge-primary'>{$row['total']}</span> {$row['category']}</a>";
            }
          
          ?>
      </div>
    </li>
  </ul>

