<?php

$input = false;
$update = false;
$delete = false;
// connet to the database
$server = "localhost";
$username = "root";
$password = "";
$dbname = "notes";

//connection to database
$conn = mysqli_connect($server, $username, $password, $dbname);

if (!$conn) {
    die("Enable to connect: " . mysqli_connect_error());
}


if (isset($_GET['delete'])) {
    $sno = $_GET['delete'];
    $query = "DELETE FROM `notes` WHERE `sno` = '$sno' LIMIT 1";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $delete = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['snoEdit'])) {
        //Update the Data
        $snoEdit = $_POST['snoEdit'];
        $titleEdit = $_POST['titleEdit'];
        $descriptionEdit = $_POST['descriptionEdit'];
        $query = "UPDATE `notes` SET `title` = '$titleEdit' , `description` = '$descriptionEdit' WHERE  `sno` = '$snoEdit'";
        $result = mysqli_query($conn, $query);
        if ($result) {
            $update = true;
        }
    } else {
        $title = $_POST['title'];
        $description = $_POST['description'];

        $sql = "INSERT INTO `notes` (`title`,`description`) VALUES ('$title','$description')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $input = true;
        }
    }
}

?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <!-- DataTable CSS  -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

    <title>CodeNote</title>
</head>

<body>

    <!-- edit modal  -->

    <!-- Button trigger modal -->
    <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal">
        Launch demo modal
    </button> -->

    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Note</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="/curd/index.php">
                    <div class="modal-body">
                        <input type="hidden" name="snoEdit" id="snoEdit">
                        <div class="form-group">
                            <label for="title">Edit Title</label>
                            <input type="text" class="form-control" id="titleEdit" name="titleEdit">
                        </div>
                        <div class="form-group">
                            <label for="description">Edit Description</label>
                            <textarea class="form-control" id="descriptionEdit" rows="3" name="descriptionEdit"></textarea>
                        </div>
                        <!-- <button type="submit" class="btn btn-outline-primary">UpdateNote</button> -->
                        <div class="modal-footer d-block mr-auto">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                </form>

            </div>
        </div>
    </div>
    </div>





    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">CodeNote</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact Us</a>
                </li>
            </ul>
    </nav>

    <?php

    if ($input) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>Success!</strong> Your Note Has been Inserted sucessfully.
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
              <span aria-hidden='true'>&times;</span>
            </button>
          </div>";
    }
    if ($update) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>Success!</strong> Your Note Has been Updated sucessfully.
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
              <span aria-hidden='true'>&times;</span>
            </button>
          </div>";
    }
    if ($delete) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>Success!</strong> Your Note Has been deleted sucessfully.
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
              <span aria-hidden='true'>&times;</span>
            </button>
          </div>";
    }

    ?>

    <div class="container mt-4">
        <h2>Add a Note</h2>
        <form method="POST" action="/curd/index.php">
            <div class="form-group">
                <label for="title">Note Title</label>
                <input type="text" class="form-control" id="title" name="title">
            </div>
            <div class="form-group">
                <label for="description">Note Description</label>
                <textarea class="form-control" id="description" rows="3" name="description"></textarea>
            </div>
            <button type="submit" class="btn btn-outline-primary">Add Note</button>
        </form>
    </div>
    <div class="container my-4">
        <table class="table table-striped" id="myTable">
            <thead>
                <tr>
                    <th scope="col">S.no</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $selectQuery = "SELECT * FROM `notes`";
                $result = mysqli_query($conn, $selectQuery);
                $sno = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <th scope='row'>" . $sno . "</th>
                            <td>" . $row['title'] . "</td>
                            <td>" . $row['description'] . "</td>
                                <td>
                                <button class='btn btn-sm btn-primary edit' id=" . $row['sno'] . ">Edit</button>&nbsp;&nbsp;
                                <button class='btn btn-sm btn-primary delete' id=d" . $row['sno'] . ">Delete</button>
                                </td>
                        </tr>";
                    $sno++;
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- FOOTER -->
    <footer class="container">
        <p class="float-right"><a href="#">Back to top</a></p>
        <p>&copy; 2020 codeNote, Inc. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
    </footer>




    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <!-- datatable js  -->
    <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });


        // for edit data though modal 

        edits = document.getElementsByClassName('edit'); // all edit button
        Array.from(edits).forEach((element) => {
            element.addEventListener("click", (e) => {
                // console.log("edit ", e.target.parentNode.parentNode);
                tr = e.target.parentNode.parentNode; // get all tr of the each row on the website
                title = tr.getElementsByTagName('td')[0].innerText; //get text from table
                description = tr.getElementsByTagName('td')[1].innerText; // getting description from table
                // console.log(title, description);
                titleEdit.value = title;
                descriptionEdit.value = description;
                snoEdit.value = e.target.id;
                $('#editModal').modal('toggle') // toggling the modal
            })
        });

        // delete the note 

        deletes = document.getElementsByClassName('delete'); // all edit button
        Array.from(deletes).forEach((element) => {
            element.addEventListener("click", (e) => {
                sno = e.target.id.substr(1, );
                if (confirm("Do You Really Want to delete this note")) {
                    window.location = `/curd/index.php?delete=${sno}`;
                }
            })
        });
    </script>

</body>
</html>