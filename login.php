<?php
$title = 'Login';
require_once('header.php');
?>

    <main class="container">
        <h1>Login</h1>
        <?php
        if (!empty($_GET['invalid'])) {
            if ($_GET['invalid'] == "true") {
                echo '<div class="alert alert-danger">Invalid Login</div>';
            } else {
                echo '<div class="alert alert-info">Please enter your credentials</div>';
            }
        } else {
            echo '<div class="alert alert-info">Please enter your credentials</div>';
        }
        ?>

        <form method="post" action="validate.php">
            <fieldset class="form-group">
                <label for="username" class="col-md-2">Username:</label>
                <input name="username" id="username" required type="email" placeholder="email@email.com"/>
            </fieldset>
            <fieldset class="form-group">
                <label for="password" class="col-md-2">Password:</label>
                <input type="password" name="password" id="password" required/>
            </fieldset>
            <div class="offset-md-2">
                <input type="submit" value="Login" class="btn btn-info"/>
            </div>
        </form>
    </main>

<?php
require_once 'footer.php';
?>