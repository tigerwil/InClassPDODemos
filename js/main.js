$(function(){
    //spinner code for navbar-brand
    $('a.navbar-brand i').hover(
        //in handler
        function(){
                $(this).addClass('fa-spin');
        },

        //out handler
        function(){
                $(this).removeClass('fa-spin');
        }
    );
    
   //print current year in footer
    var curDate = new Date();
    var curYear = curDate.getFullYear();
    $("#year").text(curYear);
    

    
    
    
    
    
    
    
    
    


    //Initialising DataTables 
    //$('#tablesorted').DataTable(); //must add id to your table
    $("#tablesorted").DataTable({
        "columnDefs": [
            {"orderable":false,"targets":-1}//stop sorting on last column
        ],
        "lengthMenu":[ [5,10,25,50,-1], [5,10,25,50,"All"] ]//Drop down for how many entries
    });     
       
       
});



