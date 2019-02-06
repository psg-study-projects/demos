<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="Peter S Gorgone">
        <title>Startup Test</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
        <style>
            .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            }
            @media (min-width: 768px) {
            .bd-placeholder-img-lg {
            font-size: 3.5rem;
            }
            }
        </style>
        <!-- Custom styles for this template -->
        <!--
            <link href="jumbotron.css" rel="stylesheet">
            -->
    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#">Disabled</a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="jumbotron">
            <div class="container">
                <form action="controller.php" method="post">
                    <div class="form-group">
                        <label for="product-name">Product name</label>
                        <input type="text" name="product_name" class="form-control" id="product-name" placeholder="Enter Product name">
                    </div>
                    <div class="form-group">
                        <label for="product-name">Quanity in Stock</label>
                        <input type="text" name="stock_quantity" class="form-control" id="stock-quantity" placeholder="Enter quantity in stock">
                    </div>
                    <div class="form-group">
                        <label for="item-price">Price Per Item</label>
                        <input type="text" name="item_price" class="form-control" id="item-price" placeholder="Enter price-per-item">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
                <div>
                    <table class="table">
                        <tr id="headers">
                            <th>Product Name</th>
                            <th>Quanity in Stock</th>
                            <th>Price Per Item</th>
                            <th>Datetime submitted</th>
                            <th>Total Value Number</th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <footer class="container">
            <p>&copy; Company 2017-2018</p>
        </footer>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function() {
                $("form").submit(function(e) {
                    e.preventDefault();
            
                    var thisForm = $(this);
                    var url = thisForm.attr('action');
                
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: thisForm.serialize(),
                        success: function(response) {
                            var items = response.items;
                            //var t = $('table');
                            var html = '';
                            var i;
            
                            for (i = 0; i < items.length; i++) {
                                html += '<tr>';
                                html += '<td>'+items[i].product_name+'</td>';
                                html += '<td>$ '+items[i].stock_quantity+'</td>';
                                html += '<td>'+items[i].item_price+'</td>';
                                html += '<td>'+items[i].datetime+'</td>';
                                html += '<td>$ '+items[i].total_value+'</td>';
                                html += '</tr>';
                            }
                            html += '<tr id="sum"><td>Total:</td><td>'+response.sum+'</td></tr>';
                            //$('table tr#sum').remove();
                            $('table').find("tr:gt(0)").remove(); // remove all trs except header (1st)
                            $('table tr#headers').after(html);
                        }
                    });
                
                });
            });
        </script>
    </body>
</html>


