<?php
// Databaseconfiguratie
$servername = "localhost";
$username = "87237_portfolio";
$password = "Shemar_18";
$dbname = "Port";

// Verbinding maken met de database
$conn = new mysqli($servername, $username, $password, $dbname);

// Controleren op fouten bij de verbinding
if ($conn->connect_error) {
    die("Fout bij de verbinding met de database: " . $conn->connect_error);
}

// Voeg een nieuwe URL en projectnaam toe
if (isset($_POST['add_url'])) {
    $url = $_POST['url'];
    $project_name = $_POST['project_name'];
    $sql = "INSERT INTO projects (name, url) VALUES ('$project_name', '$url')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Project en URL bewerkt!);</script>";
    } else {
        echo "<script>alert('Fout bij het bewerken van het project en de URL:');</script> " . $conn->error;
    }
}

// Verwijder een project en de bijbehorende URL
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM projects WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Project en URL bewerkt!);</script>";
    } else {
        echo "<script>alert('Fout bij het bewerken van het project en de URL:');</script> " . $conn->error;
    }
}

// Bewerk een project en de bijbehorende URL
if (isset($_POST['edit_url'])) {
    $id = $_POST['edit_id'];
    $url = $_POST['url'];
    $project_name = $_POST['project_name'];
    $sql = "UPDATE projects SET name='$project_name', url='$url' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Project en URL bewerkt!);</script>";
    } else {
        echo "<script>alert('Fout bij het bewerken van het project en de URL:');</script> " . $conn->error;
    }
}

// Handle image upload
if (isset($_FILES['image_upload']) && $_FILES['image_upload']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/'; // Create a directory for image uploads
    $uploadFile = $uploadDir . basename($_FILES['image_upload']['name']);
    
    if (move_uploaded_file($_FILES['image_upload']['tmp_name'], $uploadFile)) {
        // Image uploaded successfully, save the file path in the database
        $imagePath = $uploadFile;
        $sql = "UPDATE projects SET image_path = '$imagePath' WHERE id = $id";
        
        if ($conn->query($sql) === TRUE) {
            // Success message for adding a project and URL
          echo "<script>alert('Project en URL toegevoegd!');</script>";

        } else {
          // Success message for adding a project and URL
               echo "<script>alert('Project en URL toegevoegd!');</script>";

        }
    } 
    else {
        echo "<script>alert('Error uploading image.');</script>";
    }
}


?>
<?php
// Inside the loop that displays projects
echo "<li>{$row['name']} - <a href='{$row['url']}'>{$row['url']}</a>";

if (!empty($row['image_path'])) {
    echo "<br><img src='{$row['image_path']}' alt='{$row['name']} Image'>";
}

echo " || <a href='index.php?delete={$row['id']}'>Verwijderen</a> || <a href='#' onclick='editProject({$row['id']}, \"{$row['name']}\", \"{$row['url']}\");'>Bewerken</a></li>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL en Project CRUD</title>
    
</head>
<body>
    <h1>URL's en Projecten beheren</h1>
    
    <!-- Formulier voor het toevoegen van een URL en Projectnaam -->
    <form method="post">
        <input type="text" name="project_name" placeholder="Voer de projectnaam in" required>
        <input type="text" name="url" placeholder="Voer een URL in" required>
        <input type="file" name="image_upload" accept="image/*">

        <button type="submit" name="add_url">Toevoegen</button>
    </form>

    
    <!-- Lijst van URL's en Projecten -->
    <ul>
        <?php
        $sql = "SELECT * FROM projects";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<li>{$row['name']} - <a href='{$row['url']}'>{$row['url']}</a> || <a href='index.php?delete={$row['id']}'>Verwijderen</a> || <a href='#' onclick='editProject({$row['id']}, \"{$row['name']}\", \"{$row['url']}\");'>Bewerken</a></li>";
            }
        } else {
            echo "<li>Geen projecten gevonden.</li>";
        }

        $conn->close();
        ?>
    </ul>
<div class="home">
<a href="https://87237.stu.sd-lab.nl/Portfolio/home.html">terug naar Portfolio</a>

</div>

    <!-- JavaScript voor het bewerken van een project -->
    <script>
        function editProject(id, name, url) {
            var newName = prompt("Voer de nieuwe projectnaam in:", name);
            var newUrl = prompt("Voer de nieuwe URL in:", url);

            if (newName !== null && newUrl !== null) {
                var form = document.createElement("form");
                form.method = "post";
                form.style.display = "none";
                document.body.appendChild(form);

                var inputId = document.createElement("input");
                inputId.type = "hidden";
                inputId.name = "edit_id";
                inputId.value = id;
                form.appendChild(inputId);

                var inputName = document.createElement("input");
                inputName.type = "hidden";
                inputName.name = "project_name";
                inputName.value = newName;
                form.appendChild(inputName);

                var inputUrl = document.createElement("input");
                inputUrl.type = "hidden";
                inputUrl.name = "url";
                inputUrl.value = newUrl;
                form.appendChild(inputUrl);

                form.submit();
            }
        }

        function showPopup(popupId) {
            document.getElementById(popupId).classList.add('show');
            document.getElementById('popupOverlay').classList.add('show');
        }

        function closePopup(popupId) {
            document.getElementById(popupId).classList.remove('show');
            document.getElementById('popupOverlay').classList.remove('show');
        }
    </script>
<!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>
</body>
</html>
