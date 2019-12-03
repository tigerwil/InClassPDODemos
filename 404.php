<?php
    $page_title = '404';
    $current= "index";
    include 'includes/header.php';
?>
<div class="container">
    <h1 class="mt-4 mb-3">404
        <small>Page Not Found</small>
    </h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="./">Home</a>
        </li>
        <li class="breadcrumb-item active">404</li>
        </ol>
        <div class="jumbotron">
                <h1 class="display-1">404</h1>
                <p>The page you're looking for could not be found. 
                    Here are some helpful links to get you back on track:
                </p>    
        
        <?php
            $files = glob("./{*.php}", GLOB_BRACE);
            //var_dump($files);

            $exclude = array('./403.php', './404.php', './activate.php');

            echo "<ul>";
            foreach($files as $file){ //only get the value 
                if(! in_array($file, $exclude)){
                    //echo basename($file).'<br>';
                    echo "<li><a href='". basename($file)."'>". basename($file). "</a></li>";
                }                
            }
            echo "</ul>";
        ?>
    </div>
</div>
<?php
    
    include 'includes/footer.php';
?>

