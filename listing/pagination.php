<?php
    $server = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "form_database";

    $database = mysqli_connect($server, $user, $pass, $dbname);
    if(!$database){
        echo "Not connected with database";
        echo "<br>";
        echo mysqli_connect_errorno();
        die;
    }

    if (isset($_GET['page_no']) && $_GET['page_no']!="") {
        $page_no = $_GET['page_no'];
    } 
    else {
        $page_no = 1;
    }
    	
    $total_records_per_page = 15;
    $offset = ($page_no-1) * $total_records_per_page;
    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;
    $adjacents = "2";

    $result_count = mysqli_query(
        $database,
        "SELECT COUNT(*) As total_records FROM `countries_table`"
    );
    $total_records = mysqli_fetch_array($result_count);
    // echo "<pre>"; print_r($total_records); echo "</pre>";
    $total_records = $total_records['total_records'];
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
    // echo "<pre>"; print_r($total_no_of_pages); echo "</pre>";
    $second_last = $total_no_of_pages - 1;

    $selectquery = "SELECT * FROM countries_table limit $offset, $total_records_per_page";
    $runselectqry = mysqli_query($database, $selectquery);
    
?>    
    <!DOCTYPE html>
    <html>
    <head>
    <style>
    table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
    }

    td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
    }

    tr:nth-child(even) {
    background-color: #dddddd;
    }

    /* .pagination>li:first-child>a, .pagination>li:first-child>span {
    margin-left: 0;
    border-top-left-radius: 4px;
    border-bottom-left-radius: 4px;
    } */

    .pagination>li {
    position:relative;
    list-style-type:none;
    float: left;
    padding: 6px 12px;
    margin-left: -1px;
    line-height: 1.42857143;
    color: #337ab7;
    text-decoration: none;
    background-color: #fff;
    border: 1px solid #ddd;
    }
    .pagination>li>a{
    text-decoration:none;
    }
    </style>
    </head>
    <body>

    <h2>Pagination</h2>
    
    <table>
    <tr>
        <th>id</th>
        <th>countryCode</th>
        <th>countryName</th>
        <th>Languages</th>
    </tr>

    <?php $count=1; ?>
    <?php while($fetchdata = mysqli_fetch_assoc($runselectqry)):?>        
            <tr>          
                <td><?php echo $fetchdata['id']; ?></td>
                <td><?php echo $fetchdata['countryCode']; ?></td>
                <td><?php echo $fetchdata['countryName']; ?></td>        
                <td><?php echo $fetchdata['languages']; ?></td>        
            </tr>
    <?php endwhile; ?>    
    </table>
    <div style='padding: 10px 20px 0px; border-top: dotted 1px #CCC;'>
    <strong>Page <?php echo $page_no." of ".$total_no_of_pages; ?></strong>
    </div>
    <ul class="pagination">
    <?php
        if($page_no <= 1){
            echo "";
        }else{
            echo "<li><a href='pagination.php?page_no=".$previous_page."'>Previous Page</a></li>";
        }
        
        for ($counter = 1; $counter <= $total_no_of_pages; $counter++){
            if ($counter == $page_no) {
                echo "<li class='active'><a>$counter</a></li>"; 
            }
            else{
                echo "<li><a href='pagination.php?page_no=".$counter."'>$counter</a></li>";
            }            
        }
        if($page_no<$total_no_of_pages){
            echo "<li><a href='pagination.php?page_no=".$next_page."'>Next Page</a></li>";
        }
        else{echo "";}
        
    ?>   
    </ul>
</body>
</html>
