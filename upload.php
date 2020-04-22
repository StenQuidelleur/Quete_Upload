<?php
if(!empty($_FILES['files']['name'][0])) {
    $files = $_FILES['files'];

    $uploaded = array();
    $failed = array();

    $allowed = array('jpg','png','gif');

    foreach ($files['name'] as $position => $file_name) {
        $file_tmp = $files['tmp_name'][$position];
        $file_size = $files['size'][$position];
        $file_error = $files['error'][$position];

        $file_ext = explode('.', $file_name);
        $file_ext = strtolower(end($file_ext));

        if(in_array($file_ext, $allowed)) {
            if($file_error === 0) {
                if($file_size <= 8388608) {
                    $file_name_new = uniqid('', true) . '.' . $file_ext;
                    $file_destination = 'uploads/' . $file_name_new;
                    if(move_uploaded_file($file_tmp, $file_destination)) {
                        $uploaded[$position] = $file_destination;
                    } else {
                        $failed[$position] = "[{$file_name}] failed to upload.";
                    }
                } else {
                    $failed[$position] = "[{$file_name}] is too large.";
                }
            } else {
                $failed[$position] = "[{$file_name}] errored with code {$file_error}.";
            }
        } else {
            $failed[$position] = "[{$file_name}] file extension '{$file_ext}' is not allowed.";
        }

    }
}

if(!empty($failed)) {
    print_r($failed);
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Upload</title>
</head>
<body>
    <div class="container">
        <div class="card mt-3">
            <div class="card-header">
                Upload
            </div>
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="file" class="form-control-file" name="files[]" multiple>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>

        <div class="row">
        <?php $it = new FilesystemIterator('uploads');
                foreach ($it as $fileinfo) {
        ?>
            <div class="card col-4 mt-3">
                <figure>
                    <img src="uploads/<?= $fileinfo->getFilename() ?>" alt="">
                    <figcaption><?= $fileinfo->getFilename() ?></figcaption>
                </figure>
                <form method="POST">
                    <button type="submit" class="btn btn-danger" name="delete">Delete</button>
                </form>
            </div>
        <?php }

        if (isset($_POST['delete'])) {
            if (file_exists($fileinfo->getFilename())) {
                unlink('upload/' . $fileinfo->getFilename());
                header('Location: upload.php');
            }
        }

        ?>
        </div>
    </div>




    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>