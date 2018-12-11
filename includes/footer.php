    <!-- Footer -->
    <footer class="py-5 bg-primary">
      <div class="container">
          <p class="m-0 text-center text-white">Copyright &copy; Knowledge Is Power <span id="year"></span></p>
      </div>
      <!-- /.container -->
    </footer>

    <!-- Bootstrap core JavaScript -->
<!--    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>-->
    <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <!--    Custom script-->
    <script src="js/main.js"></script>
    
    
    
    
    
    
    
    <?php
        $current_page = $_SERVER['REQUEST_URI'];
        //echo $current_page;
        if($current_page=='/InClassPDODemos/contact.php'){
            
    ?>
    <!-- Contact form JavaScript -->
    <!-- Do not edit these files! In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
    <script src="js/jqBootstrapValidation.js"></script>
    <script src="js/contact_me.js"></script>
    <?php
        }
        
    ?>
    <?php
        $current_page = $_SERVER['REQUEST_URI'];
        //echo $current_page;
        if($current_page=='/InClassPDODemos/allarticles.php'){
            
    ?>
    <!-- DataTables plugin JavaScript-->   
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <?php
        }
        
    ?>
    </body>
</html>