<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form enctype="multipart/form-data" id="addImageForm">
        <label for="imageBlob">BLOB</label>
        <input type="file" name="imageBlob" id="imageBlob" accept=".jpeg, .jpg, .png"><br>

        <label for="imageVarchar">VARCHAR</label>
        <input type="file" name="imageVarchar" id="imageVarchar" required><br>

        <label for="justText">TEXT</label>
        <input type="text" name="justText" id="justText"><br>

        <input type="submit" value="Enter">
    </form>

    <script>
    document.getElementById("addImageForm").addEventListener("submit", function(event) {
        event.preventDefault();
        saveToDatabase();
    });

    function saveToDatabase() {
        var form = document.getElementById("addImageForm");
        var formData = new FormData(form);

        fetch('save.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                // You can handle the response as needed
            })
            .catch(error => console.error('Error:', error));
    }
    </script>
</body>

</html>