<?php
$title = 'Lab & Challenges';
require_once('header.php');

// Make this page private by prompting the user to login
require_once 'authenticate.php';

// Initialize variables for each field
$lab_id = null;
$lab_name = null;
$dateAdded = null;
$location = null;

// If an id parameter is passed in the url, we are editing the form
if (!empty($_GET['labId'])) {
    $lab_id = $_GET['labId'];

    // Connect to database
    require_once 'db.php';

    // Fetch the selected lab
    $sql = "SELECT * FROM labs WHERE lab_id = :lab_id";
    $cmd = $db->prepare($sql);
    $cmd->bindParam(':labId', $lab_id, PDO::PARAM_INT);
    $cmd->execute();

    // Use fetch without a loop to select a single lab
    $lab = $cmd->fetch();
    $lab_name = $lab['name'];
    $dateAdded = $lab['dateAdded'];
    $location = $lab['location'];

    // Disconnect from database
    $db = null;
}
?>

<h1>Lab Details</h1>
<form action="create-lab.php" method="post" enctype="multipart/form-data">
    <fieldset>
        <label for="name" class="col-sm-2">Name: *</label>
        <input name="name" id="name" required value="<?php echo $lab_name; ?>"/>
    </fieldset>
    <fieldset>
        <label for="dateAdded" class="col-sm-2">Date Added: </label>
        <input name="dateAdded" id="dateAdded" type="datetime-local"> <?php echo $dateAdded; ?>
    </fieldset>
    <fieldset>
        <label for="location" class="col-sm-2">Location:</label>
        <input name="location" id="location" type="url" value="<?php echo $location; ?>"/>
    </fieldset>
    <div id="map"></div>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCmjIqzC2jEw6d7uBWvlXiL2SbAi-ZKVKo&callback=initMap">
    </script>

    <!--Google Maps API start-->

    <?php

    require_once 'db.php';

    // Start XML file, create parent node

    $dom = new DOMDocument("1.0");
    $node = $dom->createElement("markers");
    $parnode = $dom->appendChild($node);

    // Select all the rows in the markers table

    $query = "SELECT * FROM markers WHERE 1";
    $result = mysql_query($query);
    if (!$result) {
        die('Invalid query: ' . mysql_error());
    }

    header("Content-type: text/xml");

    // Iterate through the rows, adding XML nodes for each

    while ($row = @mysql_fetch_assoc($result)) {
        // Add to XML document node
        $node = $dom->createElement("marker");
        $newnode = $parnode->appendChild($node);
        $newnode->setAttribute("id", $row['id']);
        $newnode->setAttribute("name", $row['name']);
        $newnode->setAttribute("address", $row['address']);
        $newnode->setAttribute("lat", $row['lat']);
        $newnode->setAttribute("lng", $row['lng']);
        $newnode->setAttribute("type", $row['type']);
    }

    echo $dom->saveXML();

    ?>

    <!--Google Maps API end-->

    <input name="labId" id="labId" value="<?php echo $lab_id; ?>" type="hidden"/>
    <button class="btn btn-primary offset-sm-2">Save</button>
</form>

<?php
require_once 'footer.php';
?>
