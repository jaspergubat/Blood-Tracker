<?php
  $conn = mysqli_connect("localhost", "root", "", "blood_bank");

  if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (isset($_POST['removeBloodBank'])) {
          $bankIdToRemove = $_POST['id'];

          mysqli_begin_transaction($conn);

          try {
              $deleteReviewsQuery = "DELETE FROM reviews WHERE bank_id = ?";
              $stmt = mysqli_prepare($conn, $deleteReviewsQuery);
              mysqli_stmt_bind_param($stmt, 'i', $bankIdToRemove);
              mysqli_stmt_execute($stmt);
              mysqli_stmt_close($stmt);

              $deleteBankQuery = "DELETE FROM blood_bank WHERE id = ?";
              $stmt = mysqli_prepare($conn, $deleteBankQuery);
              mysqli_stmt_bind_param($stmt, 'i', $bankIdToRemove);
              mysqli_stmt_execute($stmt);
              mysqli_stmt_close($stmt);

              mysqli_commit($conn);
              $_SESSION['feedback'] = "Blood bank removed successfully.";
          } catch (mysqli_sql_exception $exception) {
              mysqli_rollback($conn);
              $_SESSION['feedback'] = "Error removing blood bank: " . $exception->getMessage();
          }
      } elseif (isset($_POST['removeUser'])) {
          $userIdToRemove = $_POST['id'];
          $loggedInUserId = $_SESSION['user_id'];

          if ($userIdToRemove == $loggedInUserId) {
              $_SESSION['feedback'] = "You cannot delete your own account.";
          } else {
              mysqli_begin_transaction($conn);

              try {
                  $deleteReviewsQuery = "DELETE FROM reviews WHERE user_id = ?";
                  $stmt = mysqli_prepare($conn, $deleteReviewsQuery);
                  mysqli_stmt_bind_param($stmt, 'i', $userIdToRemove);
                  mysqli_stmt_execute($stmt);
                  mysqli_stmt_close($stmt);

                  $deleteUserQuery = "DELETE FROM users WHERE id = ?";
                  $stmt = mysqli_prepare($conn, $deleteUserQuery);
                  mysqli_stmt_bind_param($stmt, 'i', $userIdToRemove);
                  mysqli_stmt_execute($stmt);
                  mysqli_stmt_close($stmt);

                  mysqli_commit($conn);
                  $_SESSION['feedback'] = "User removed successfully.";
              } catch (mysqli_sql_exception $exception) {
                  mysqli_rollback($conn);
                  $_SESSION['feedback'] = "Error removing user: " . $exception->getMessage();
              }
          }
      } elseif (isset($_POST['update'])) {
          $idToUpdate = $_POST['id'];
          $name = $_POST['name'];
          $location = $_POST['location'];
          $region = $_POST['region'];
          $landline = $_POST['landline'];
          $date = $_POST['date'];
          $time = $_POST['time'];
          $bloodAvailable = $_POST['bloodAvailable'];

          $bloodTypesArray = explode(",", $bloodAvailable);
          $bloodTypesString = json_encode(array_map('trim', $bloodTypesArray));

          $updateQuery = "UPDATE blood_bank SET name = ?, location = ?, region = ?, telephone_num = ?, date = ?, time = ?, blood_types = ? WHERE id = ?";
          $stmt = mysqli_prepare($conn, $updateQuery);
          mysqli_stmt_bind_param($stmt, 'sssssssi', $name, $location, $region, $landline, $date, $time, $bloodTypesString, $idToUpdate);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_close($stmt);

          $_SESSION['feedback'] = "Blood bank updated successfully.";
      } else if (isset($_POST['bloodBankName']) && isset($_POST['location']) && isset($_POST['LandlineNum']) && isset($_POST['contactNumber']) && isset($_POST['bloodAvailable'])) {
          $bloodBankName = $_POST['bloodBankName'];
          $location = $_POST['location'];
          $landlineNum = $_POST['LandlineNum'];
          $region = "Visayas";
          $time = "8:00 AM to 8:00 PM";
          $bloodAvailable = implode(",", $_POST['bloodAvailable']);
          $status = "Active";
          $date = date("Y-m-d");

          $insertQuery = "INSERT INTO blood_bank (name, location, telephone_num, region, time, blood_types, status, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
          $stmt = mysqli_prepare($conn, $insertQuery);
          mysqli_stmt_bind_param($stmt, 'ssssssss', $bloodBankName, $location, $landlineNum, $region, $time, $bloodAvailable, $status, $date);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_close($stmt);

          $_SESSION['feedback'] = "Blood bank added successfully.";
      } else if (isset($_POST['removeReview'])) {
          $reviewIdToRemove = $_POST['id'];
          $deleteQuery = "DELETE FROM reviews WHERE id = ?";
          $stmt = mysqli_prepare($conn, $deleteQuery);
          mysqli_stmt_bind_param($stmt, 'i', $reviewIdToRemove);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_close($stmt);

          $_SESSION['feedback'] = "Review removed successfully.";
      }
  }

  function getBloodBankCount($conn) {
      $query = "SELECT COUNT(*) AS count FROM blood_bank";
      $result = mysqli_query($conn, $query);
      $row = mysqli_fetch_assoc($result);
      return $row['count'];
  }

  function getUserCount($conn) {
      $query = "SELECT COUNT(*) AS count FROM users";
      $result = mysqli_query($conn, $query);
      $row = mysqli_fetch_assoc($result);
      return $row['count'];
  }

  $bloodBankCount = getBloodBankCount($conn);
  $userCount = getUserCount($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BloodTrackr Dashboard</title>
  <link rel="stylesheet" href="styles2.css" type="text/css">
  <!-- Boxicons CDN Link -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <style>
    .modal {
      display: none;
      position: fixed;
      z-index: 9999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
    }
    .modal-content {
      background-color: #fff;
      margin: auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      max-width: 500px;
      border-radius: 10px;
      text-align: center;
    }
    .modal-content p {
      margin: 0;
    }
    .modal-content button {
      margin-top: 20px;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      background-color: #007BFF;
      color: white;
      cursor: pointer;
    }

    body {
      display: flex;
      font-family: Arial, sans-serif;
    }
    .content-container {
      flex: 1;
      padding: 20px;
    }
    .content {
      display: none;
    }
    ul {
      list-style: none;
      padding: 0;
    }
    li {
      display: flex;
      justify-content: flex-start;
      padding: 10px;
      margin: 5px 0;
    }
    .form-inline {
      display: flex;
      gap: 10px;
    }
    input[type="text"],
    input[type="tel"] {
      padding: 5px;
      margin-right: 5px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .update-btn, .delete-btn {
      padding: 5px 10px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .update-btn {
      background-color: #007BFF;
      color: white;
    }
    .delete-btn {
      background-color: #FF0000;
      color: white;
    }
  </style>
</head>
<body>
  <nav class="sidebar close">
    <header>
      <div class="image-text">
        <span class="image">
          <img src="assets/images/logo.png" alt="Logo">
        </span>
        <div class="text logo-text">
          <span class="name">BloodTrackr</span>
        </div>
      </div>
      <i class="bx bx-chevron-right toggle"></i>
    </header>
    <div class="menu-bar">
      <div class="menu">
        <ul class="menu-links">
          <li class="nav-link">
            <a href="#" onclick="showPage('dashboard')">
              <i class="bx bx-grid-alt icon"></i>
              <span class="text nav-text">Dashboard</span>
            </a>
          </li>
          <li class="nav-link">
            <a href="#" onclick="showPage('bloodBanks')">
              <i class="bx bx-donate-blood icon"></i>
              <span class="text nav-text">Blood Banks</span>
            </a>
          </li>
          <li class="nav-link">
            <a href="#" onclick="showPage('addBloodBank')">
              <i class="bx bx-plus-circle icon"></i>
              <span class="text nav-text">Add Blood Bank</span>
            </a>
          </li>
          <li class="nav-link">
            <a href="#" onclick="showPage('users')">
              <i class="bx bx-user icon"></i>
              <span class="text nav-text">Users</span>
            </a>
          </li>
          <li class="nav-link">
            <a href="#" onclick="showPage('reviews')">
              <i class="bx bx-message-square-dots icon"></i>
              <span class="text nav-text">Reviews</span>
            </a>
          </li>
        </ul>
      </div>
      <div class="bottom-content">
        <ul>
          <li>
            <a href="core/user-logout.php">
              <i class="bx bx-log-out icon"></i>
              <span class="text nav-text">Logout</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <section class="home">
  <div class="content-container">
  <div id="dashboard" class="content">
    <h2>Dashboard</h2>
    <div class="grid-container">
        <div class="grid-child purple">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-user fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="bloodBankTotal"><?php echo $bloodBankCount; ?></div>
                            <div>Total Blood Bank Count</div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer" onclick="showPage('bloodBanks')">
                    <button class="pull-left">View Details</buttton>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="grid-child green">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-tasks fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="usersTotal"><?php echo $userCount; ?></div>
                            <div>Total User Count</div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer" onclick="showPage('users')">
                    <button class="pull-left">View Details</button>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>

<div id="bloodBanks" class="content">
    <h2>Blood Banks</h2>
    <ul>
        <?php
          $query = "SELECT DISTINCT * FROM blood_bank ORDER BY name ASC";
          $result = mysqli_query($conn, $query);

          if (!$result) {
              die("Error: " . mysqli_error($conn));
          }
          if ($result) {
              while ($row = mysqli_fetch_assoc($result)) {
                  $bloodTypesString = str_replace(['[', ']', '"'], '', $row['blood_types']);
                  $bloodTypesArray = explode(",", $bloodTypesString);
        ?>
                <li>
                    <form class="form-inline" action="" method="POST" onsubmit="return confirmRemove(event)">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <input type="text" name="name" value="<?php echo $row['name']; ?>">
                        <input type="text" name="location" value="<?php echo $row['location']; ?>">
                        <input type="text" name="region" value="<?php echo $row['region']; ?>">
                        <input type="tel" name="landline" value="<?php echo $row['telephone_num']; ?>">
                        <input type="text" name="date" value="<?php echo $row['date']; ?>">
                        <input type="text" name="time" value="<?php echo $row['time']; ?>">
                        <input type="text" name="bloodAvailable" value="<?php echo implode(", ", $bloodTypesArray); ?>">
                        <button type="submit" name="update" class="update-btn">Update</button>
                        <button type="submit" name="removeBloodBank" class="delete-btn">Remove</button>
                    </form>
                </li>
        <?php
              }
          } else {
              echo "Error: " . mysqli_error($conn);
          }
        ?>
    </ul>
</div>

<script>
function confirmRemove(event) {
    if (event.submitter && event.submitter.name === 'removeBloodBank') {
        return confirm('Are you sure you want to delete this blood bank?');
    }
    return true;
}
</script>

<div id="addBloodBank" class="content">
    <div class="container">
        <h2>Add Blood Bank</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="bloodBankName">Blood Bank Name</label>
                <input type="text" id="bloodBankName" name="bloodBankName" required>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <textarea id="location" name="location" required></textarea>
            </div>
            <div class="form-group">
                <label for="LandlineNum">Landline Number</label>
                <input type="tel" id="LandlineNum" name="LandlineNum" required>
            </div>
            <div class="form-group">
                <label for="contactNumber">Contact Number</label>
                <input type="tel" id="contactNumber" name="contactNumber" required>
            </div>
            <div class="form-group">
                <label for="bloodAvailable">Blood Available</label>
                <div id="bloodAvailable">
                    <label>
                        <input type="checkbox" name="bloodAvailable[]" value="A"> A
                    </label>
                    <label>
                        <input type="checkbox" name="bloodAvailable[]" value="B"> B
                    </label>
                    <label>
                        <input type="checkbox" name="bloodAvailable[]" value="AB"> AB
                    </label>
                    <label>
                        <input type="checkbox" name="bloodAvailable[]" value="O"> O
                    </label>
                </div>
            </div>
            <button type="submit">Add Blood Bank</button>
        </form>
    </div>
</div>

<div id="users" class="content">
    <h2>Users</h2>
    <div id="userTableContainer" class="section-container">
        <table class="table" id="usersTable">
            <tr>
                <th>Name</th>
                <th>Home Address</th>
                <th>Blood Type</th>
                <th>Actions</th>
            </tr>
            <?php
            $query = "SELECT DISTINCT * FROM users ORDER BY username ASC";
            $result = mysqli_query($conn, $query);

            if (!$result) {
                die("Error: " . mysqli_error($conn));
            }

            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <form action="" method="POST" onsubmit="return confirmDelete()">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <td><input disabled type="text" name="name" value="<?php echo $row['username']; ?>"></td>
                        <td><input disabled type="text" name="home_address" value="<?php echo $row['home_address']; ?>"></td>
                        <td><input disabled type="text" name="blood_type" value="<?php echo $row['blood_type']; ?>"></td>
                        <td>
                            <button type="submit" style="width: 100%" name="removeUser" class="delete-btn">Remove</button>
                        </td>
                    </form>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
</div>

<script>
function confirmDelete() {
    return confirm('Are you sure you want to remove this user?');
}
</script>

<div id="reviews" class="content">
    <h2>Reviews</h2>
    <div class="section-container">
        <div class="search-bar">
            <input type="text" id="reviewSearch" placeholder="Search reviews by name..." onkeyup="searchReviews()">
        </div>
        <div id="reviewContainer">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 30%;">Name</th>
                        <th style="width: 30%;">Rating</th>
                        <th style="width: 30%;">Message</th>
                        <th style="width: 10%;">Action</th>
                    </tr>
                </thead>
                <tbody id="reviewTableBody">
                    <?php
                    $query = "SELECT * FROM reviews ORDER BY user_name ASC";
                    $result = mysqli_query($conn, $query);

                    if (!$result) {
                        die("Error fetching reviews: " . mysqli_error($conn));
                    }

                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <tr class="review">
                            <td>
                                  <span class="value"><?php echo $row['user_name']; ?></span>
                            </td>
                            <td>
                                  <span class="value"><?php echo $row['rating']; ?></span>
                            </td>
                            <td>
                                  <span class="value"><?php echo $row['message']; ?></span>
                            </td>
                            <td>
                                <form action="" method="POST" onsubmit="return confirmRemoveReview(event)">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" style="width: 100%;" name="removeReview">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function confirmRemoveReview(event) {
    if (event.submitter && event.submitter.name === 'removeReview') {
        return confirm('Are you sure you want to delete this review?');
    }
    return true;
}
</script>

<script>
    function searchReviews() {
        var input = document.getElementById('reviewSearch').value.toUpperCase();
        var table = document.getElementById('reviewTableBody');
        var tr = table.getElementsByTagName('tr');

        for (var i = 0; i < tr.length; i++) {
            var td = tr[i].getElementsByTagName('td')[0];
            if (td) {
                var txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(input) > -1) {
                    tr[i].style.display = '';
                } else {
                    tr[i].style.display = 'none';
                }
            }
        }
    }
</script>

<div id="feedbackModal" class="modal">
    <div class="modal-content">
      <p id="feedbackMessage"></p>
      <button onclick="closeModal()">OK</button>
    </div>
  </div>

<script>
    const body = document.querySelector('body'),
      sidebar = body.querySelector('.sidebar'),
      toggle = body.querySelector(".toggle"),
      modeSwitch = body.querySelector(".toggle-switch"),
      modeText = body.querySelector(".mode-text");

    toggle.addEventListener("click", () => {
      sidebar.classList.toggle("close");
    });


    function showPage(pageId) {
      const contents = document.querySelectorAll('.content');
      contents.forEach(content => {
        content.style.display = 'none';
      });
      document.getElementById(pageId).style.display = 'block';
    }

    showPage('dashboard');

    function closeModal() {
      var modal = document.getElementById('feedbackModal');
      modal.style.display = 'none';
    }

    <?php if (isset($_SESSION['feedback'])): ?>
      document.getElementById('feedbackMessage').innerText = "<?php echo $_SESSION['feedback']; ?>";
      document.getElementById('feedbackModal').style.display = 'flex';
      <?php unset($_SESSION['feedback']); ?>
    <?php endif; ?>

  </script>
</body>
</html>