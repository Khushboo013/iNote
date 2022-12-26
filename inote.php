<?php
include './partials/_connectdb.php';

$update = false;
$insert = false;
$delete = false;

if (isset($_GET['delete'])) {
    $sno = $_GET['delete'];
    $del_sql =  "DELETE FROM `inote` WHERE `inote`.`sno` = '$sno' ";
    $result = mysqli_query($conn, $del_sql);
    if ($result) {
        $delete = true;
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['snoEdit'])) {

        $sno = $_POST['snoEdit'];
        $title = $_POST['titleEdit'];
        $desc = $_POST['descEdit'];

        $up_sql = "UPDATE `inote` SET `title` = '$title',`desc`='$desc'  WHERE `inote`.`sno` = $sno";
        $result = mysqli_query($conn, $up_sql);

        if ($result) {
            $update = true;
        }
    } else {
        $title = $_POST['title'];
        $desc = $_POST['desc'];


        $in_sql =  "INSERT INTO `inote` (`title`, `desc`, `date`) VALUES ('$title', '$desc', current_timestamp())";
        $result = mysqli_query($conn, $in_sql);

        if ($result) {
            $insert = true;
        }
    }
}
?>
<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: /myProjects/login.php");
    exit;
}

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>iNotes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.css">
</head>

<body>

    <?php include './partials/_nav.php' ?>

    <!-- Modal -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Note</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="snoEdit" id="snoEdit">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Note Title</label>
                            <input type="text" class="form-control" placeholder="title" id="titleEdit" name="titleEdit">
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Description</label>
                            <textarea class="form-control" rows="8" id="descEdit" name="descEdit"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="submit">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <?php
    if ($update) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                     <strong>Hurray!</strong> Your note is updated.
                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                     </div>';
    }
    if ($insert) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                     <strong>Hurray!</strong> Your note is inserted.
                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                     </div>';
    }
    if ($delete) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Hurray!</strong> Your note is deleted successfully.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
    }
   
    ?>

    <div class="container my-4">
        <h2>iNote - Note Taking App</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Title</label>
                <input type="text" class="form-control" id="text" placeholder="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Description</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="8" name="desc" required></textarea>
            </div>
            <input class="btn btn-primary" type="submit" value="Submit" name="submit">

        </form>
    </div>
    <div class="container my-4">

        <table class="table" id="myTable">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM `inote`";
                $result = mysqli_query($conn, $sql);

                $sno = 0;
               
                while ($row = mysqli_fetch_assoc($result)) {
                    $sno = $sno + 1;
                    echo ' <tr><th scope="row">' . $sno . '</th>
                                    <td>' . $row["title"] . '</td>
                                    <td>' . $row["desc"] . '</td>
                                    <td><button type="button" class="edit btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#editModal" id= ' . $row["sno"] . ' >
                                    Edit
                                </button><button type="button" class="delete btn btn-primary" id= d' . $row["sno"] . '>Delete</button></td> </tr>';
                    
                }
                // }
                ?> </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>

    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>

    <script>
        edits = document.getElementsByClassName('edit');
        Array.from(edits).forEach((element) => {
            element.addEventListener("click", (e) => {
                console.log("edit ");
                tr = e.target.parentNode.parentNode;
                title = tr.getElementsByTagName("td")[0].innerText;
                desc = tr.getElementsByTagName("td")[1].innerText;
                console.log(title, desc);
                titleEdit.value = title;
                descEdit.value = desc;
                snoEdit.value = e.target.id;
                console.log(e.target.id)
                // $('#editModal').modal('toggle');
            })
        })

        deletes = document.getElementsByClassName('delete');
        Array.from(deletes).forEach((element) => {
            element.addEventListener("click", (e) => {
                console.log("edit ");
                sno = e.target.id.substr(1);

                if (confirm("Are you sure you want to delete this note!")) {
                    console.log("yes");
                    window.location = `/myProjects/inote.php?delete=${sno}`;
                } else {
                    console.log("no");
                }
            })
        })
    </script>

    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>

</html>