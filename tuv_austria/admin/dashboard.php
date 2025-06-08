<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data_no = $_POST['data_no'] ?? '';
    $card_no = $_POST['card_no'] ?? '';
    $name = $_POST['name'] ?? '';
    $id_no = $_POST['id_no'] ?? '';
    $issue_date = $_POST['issue_date'] ?? '';
    $expiry_date = $_POST['expiry_date'] ?? '';
    $company = $_POST['company'] ?? '';
    $model = $_POST['model'] ?? '';
    $ref_no = $_POST['ref_no'] ?? '';
    $issuance_no = $_POST['issuance_no'] ?? '';
    $certification = $_POST['certification'] ?? '';
    $assessor = $_POST['assessor'] ?? '';
    $cr_number = $_POST['cr_number'] ?? '';

    // Handle photo upload
    $photo = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $tmp_name = $_FILES['photo']['tmp_name'];
        $filename = basename($_FILES['photo']['name']);
        $target_file = $upload_dir . $filename;
        if (move_uploaded_file($tmp_name, $target_file)) {
            $photo = $filename;
        } else {
            $error = 'Failed to upload photo.';
        }
    }

    if (!$error) {
        try {
            if ($action === 'edit' && $id) {
                // Update existing record
                if ($photo) {
                    $stmt = $pdo->prepare("UPDATE cards SET data_no=?, card_no=?, name=?, id_no=?, issue_date=?, expiry_date=?, company=?, model=?, ref_no=?, issuance_no=?, certification=?, assessor=?, cr_number=?, photo=? WHERE id=?");
                    $stmt->execute([$data_no, $card_no, $name, $id_no, $issue_date, $expiry_date, $company, $model, $ref_no, $issuance_no, $certification, $assessor, $cr_number, $photo, $id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE cards SET data_no=?, card_no=?, name=?, id_no=?, issue_date=?, expiry_date=?, company=?, model=?, ref_no=?, issuance_no=?, certification=?, assessor=?, cr_number=? WHERE id=?");
                    $stmt->execute([$data_no, $card_no, $name, $id_no, $issue_date, $expiry_date, $company, $model, $ref_no, $issuance_no, $certification, $assessor, $cr_number, $id]);
                }
                $success = 'Record updated successfully.';
            } else {
                // Insert new record
                $stmt = $pdo->prepare("INSERT INTO cards (data_no, card_no, name, id_no, issue_date, expiry_date, company, model, ref_no, issuance_no, certification, assessor, cr_number, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$data_no, $card_no, $name, $id_no, $issue_date, $expiry_date, $company, $model, $ref_no, $issuance_no, $certification, $assessor, $cr_number, $photo]);
                $success = 'Record added successfully.';
            }
        } catch (Exception $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}

// Handle delete action
if ($action === 'delete' && $id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM cards WHERE id = ?");
        $stmt->execute([$id]);
        $success = 'Record deleted successfully.';
    } catch (Exception $e) {
        $error = 'Database error: ' . $e->getMessage();
    }
}

// Fetch all records
$stmt = $pdo->query("SELECT * FROM cards ORDER BY id DESC");
$cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If editing, fetch the record
$edit_card = null;
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM cards WHERE id = ?");
    $stmt->execute([$id]);
    $edit_card = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../styles.css" />
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?> | <a href="logout.php">Logout</a></p>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <h2><?php echo $action === 'edit' ? 'Edit Record' : 'Add New Record'; ?></h2>
        <form method="post" action="?action=<?php echo $action === 'edit' ? 'edit&id=' . htmlspecialchars($id) : 'add'; ?>" enctype="multipart/form-data" class="card-form">
            <label>Data No</label>
            <input type="text" name="data_no" required value="<?php echo $edit_card['data_no'] ?? ''; ?>" />
            <label>Card No</label>
            <input type="text" name="card_no" value="<?php echo $edit_card['card_no'] ?? ''; ?>" />
            <label>Name</label>
            <input type="text" name="name" value="<?php echo $edit_card['name'] ?? ''; ?>" />
            <label>ID NO</label>
            <input type="text" name="id_no" value="<?php echo $edit_card['id_no'] ?? ''; ?>" />
            <label>Issue Date</label>
            <input type="date" name="issue_date" value="<?php echo $edit_card['issue_date'] ?? ''; ?>" />
            <label>Expiry Date</label>
            <input type="date" name="expiry_date" value="<?php echo $edit_card['expiry_date'] ?? ''; ?>" />
            <label>Company</label>
            <input type="text" name="company" value="<?php echo $edit_card['company'] ?? ''; ?>" />
            <label>Model</label>
            <input type="text" name="model" value="<?php echo $edit_card['model'] ?? ''; ?>" />
            <label>Ref No</label>
            <input type="text" name="ref_no" value="<?php echo $edit_card['ref_no'] ?? ''; ?>" />
            <label>Issuance No</label>
            <input type="text" name="issuance_no" value="<?php echo $edit_card['issuance_no'] ?? ''; ?>" />
            <label>Certification</label>
            <input type="text" name="certification" value="<?php echo $edit_card['certification'] ?? ''; ?>" />
            <label>Assessor</label>
            <input type="text" name="assessor" value="<?php echo $edit_card['assessor'] ?? ''; ?>" />
            <label>CR Number</label>
            <input type="text" name="cr_number" value="<?php echo $edit_card['cr_number'] ?? ''; ?>" />
            <label>Photo</label>
            <input type="file" name="photo" accept="image/*" />
            <?php if ($edit_card && $edit_card['photo'] && file_exists('../uploads/' . $edit_card['photo'])): ?>
                <img src="../uploads/<?php echo htmlspecialchars($edit_card['photo']); ?>" alt="Current Photo" class="card-photo" />
            <?php endif; ?>
            <button type="submit"><?php echo $action === 'edit' ? 'Update' : 'Add'; ?></button>
            <?php if ($action === 'edit'): ?>
                <a href="dashboard.php" class="cancel-btn">Cancel</a>
            <?php endif; ?>
        </form>

        <h2>All Records</h2>
        <table class="records-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data No</th>
                    <th>Card No</th>
                    <th>Name</th>
                    <th>ID NO</th>
                    <th>Issue Date</th>
                    <th>Expiry Date</th>
                    <th>Company</th>
                    <th>Model</th>
                    <th>Ref No</th>
                    <th>Issuance No</th>
                    <th>Certification</th>
                    <th>Assessor</th>
                    <th>CR Number</th>
                    <th>Photo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cards as $card): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($card['id']); ?></td>
                        <td><?php echo htmlspecialchars($card['data_no']); ?></td>
                        <td><?php echo htmlspecialchars($card['card_no']); ?></td>
                        <td><?php echo htmlspecialchars($card['name']); ?></td>
                        <td><?php echo htmlspecialchars($card['id_no']); ?></td>
                        <td><?php echo htmlspecialchars($card['issue_date']); ?></td>
                        <td><?php echo htmlspecialchars($card['expiry_date']); ?></td>
                        <td><?php echo htmlspecialchars($card['company']); ?></td>
                        <td><?php echo htmlspecialchars($card['model']); ?></td>
                        <td><?php echo htmlspecialchars($card['ref_no']); ?></td>
                        <td><?php echo htmlspecialchars($card['issuance_no']); ?></td>
                        <td><?php echo htmlspecialchars($card['certification']); ?></td>
                        <td><?php echo htmlspecialchars($card['assessor']); ?></td>
                        <td><?php echo htmlspecialchars($card['cr_number']); ?></td>
                        <td>
                            <?php if ($card['photo'] && file_exists('../uploads/' . $card['photo'])): ?>
                                <img src="../uploads/<?php echo htmlspecialchars($card['photo']); ?>" alt="Photo" class="table-photo" />
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="?action=edit&id=<?php echo $card['id']; ?>">Edit</a> |
                            <a href="?action=delete&id=<?php echo $card['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
