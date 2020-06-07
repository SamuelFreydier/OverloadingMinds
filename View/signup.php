<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include('../Style/login_style.php'); ?>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300&display=swap" rel="stylesheet">
    <title>Overloading Minds</title>
</head>

<body>

    <content>
        <div id="Image_TopRight"></div>

        <h1 id="Title">OVERLOADING <br>MINDS</h1>
        
        <form id="login_box" style="height :46vh" action ='/created' method='POST'>
            <div><input type="email" name="email" placeholder="e-mail"></div>
            <div><input type="text" name="username" placeholder="username"></div>
            <div><input type="password" name="password" placeholder="password"></div>
            <div><input type="password" name="passwordconf" placeholder="confirm password"></div>
            <button type="submit">Create account</button>
            <a href="/login">Cancel</a>
        </form>

        <div id="Image_BottomLeft"></div>
    </content>
    
</body>
</html>