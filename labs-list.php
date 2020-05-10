<?php
$title = 'Labs & Challenges';
require_once('header.php');
?>

<h1>List of Labs & Challenges</h1>

<div class="row">
    <div class="col-md-9">
        <?php
        $keywords = null; // The entire list of keywords entered as single string

        if (!empty($_GET['keywords'])) {
            $keywords = $_GET['keywords'];
        }

        if (!empty($_SESSION['userId'])) {
            echo '<a href="labs.php">Add A New Lab</a>';
        }
        ?>
    </div>
    <div class="col-md-3">
        <form method="get" action="labs-list.php">
            <label for="keywords"></label><input name="keywords" id="keywords" placeholder="Enter search word(s)"
                                                 value="<?php echo $keywords; ?>"/>
            <button class="btn btn-primary">Go</button>
        </form>
    </div>
</div>

<?php
require_once 'db.php';

// Write the SQL Query to read all the records from the labs table and store in a variable
$query = "SELECT * FROM labs";
//reminder to create labs table in SQL

// Check for keyword search
$wordList = null; // array to be filled from the individual keywords

if (!empty($_GET['keywords'])) {
    // Convert the single string of words to an array of individual values
    $wordList = explode(' ', $keywords);

    // Start appending the WHERE clause to the existing sql variable
    $query .= " WHERE ";

    $counter = 0;

    // Iterate through the wordList array and add a condition for each keyword
    foreach ($wordList as $word) {
        // If it's not the first word, put OR in front of the condition
        if ($counter > 0) {
            $query .= " OR ";
        }

        // Create the condition
        $query .= " name LIKE ?";
        $wordList[$counter] = "%" . $word . "%";
        $counter++;
    }
}

// Create a Command variable $cmd then use it to run the SQL Query
$cmd = $db->prepare($query);
$cmd->execute($wordList);

// Use the fetchAll() method of the PDO Command variable to store the data into a variable called $labs
$labs = $cmd->fetchAll();

$count = $cmd->rowCount();

echo "<h5>$count Labs</h5>";

// 4a. Create a grid with a header row
echo '<table class="table table-striped table-hover sortable"><thead><th><a href="#">Name</a></th><th>
        <a href="#">Date Added</a></th>
        <th><a href="#">Location</a></th><th></th><th></th></thead>';

// 5. Use a foreach loop to iterate (cycle) through all the values in the $labs variable.  Inside this loop, use an echo command to display the name of each lab
foreach ($labs as $value) {
    echo '<tr>';

    if (!empty($_SESSION['userId'])) {
        echo '<td><a href="labs.php?labId=' . $value['lab_id'] . '">' . $value['name'] . '</a></td>';
    } else {
        echo '<td>' . $value['name'] . '</td>';
    }

    echo '<td>' . $value['dateAdded'] . '</td>
            <td>' . '<a href="' . $value['month'] . '" target="_new">' . $value['year'] . '</a></td>';
    // Only show delete to authenticated users
    if (!empty($_SESSION['userId'])) {
        echo '<td><a href="delete-lab.php?lab_id=' . $value['lab_id'] . '" class="btn btn-danger"
                onclick="return confirmDelete();">Delete</a></td>';
        //adding delete-lab now, still have to create the file
    }

    echo '</tr>';
}

// 5a. End the HTML table
echo '</table>';

// 6. Disconnect from the database
$db = null;

require_once 'footer.php';
?>

