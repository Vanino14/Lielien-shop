<!DOCTYPE html>
<html>
<head>
    <title>Add New Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Add New Record</h2>
        <form action="process.php" method="POST">
            <div class="form-group">
                <label>Name:</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</body>
</html>





