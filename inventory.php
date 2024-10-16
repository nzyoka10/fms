<?php
// Include functions file
include 'includes/functions.php';

// Get the processed bookings (if needed)
$processedBookings = getProcessedBookings($conn);

// Get booking too (if needed)
$bookings = getBookings();

// Fetch inventory data
$inventoryData = fetchInventoryData($conn);

// Include HTML templates
include './includes/header.php';
include './includes/sidebar.php';

// Add new item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_item'])) {
    $itemName = $_POST['item_name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];

    $stmt = $conn->prepare("INSERT INTO inventory (item_name, category, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $itemName, $category, $quantity);
    
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Item added successfully!</div>";
        // Refresh inventory data after adding a new item
        $inventoryData = fetchInventoryData($conn);
    } else {
        echo "<div class='alert alert-danger'>Error adding item: " . $conn->error . "</div>";
    }
}

// Update item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_item'])) {
    $itemId = $_POST['item_id'];
    $itemName = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $category = $_POST['category'];

    $stmt = $conn->prepare("UPDATE inventory SET item_name = ?, quantity = ?, category = ? WHERE id = ?");
    $stmt->bind_param("ssii", $itemName, $quantity, $category, $itemId);
    
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Item updated successfully!</div>";
        // Refresh inventory data after updating an item
        $inventoryData = fetchInventoryData($conn);
    } else {
        echo "<div class='alert alert-danger'>Error updating item: " . $conn->error . "</div>";
    }
}
?>

<!-- Main section -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-2">
    <h4 class="text-muted text-uppercase mt-2">Inventory</h4>

    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item">
            <a class="text-decoration-none hover-underline" href="inventory.php">Inventory</a>
        </li>
    </ol>

  
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title">Add New Item</h5>
        </div>
    
        <div class="card-body">
            <form method="POST">
                <div class="row mb-3">
                    <div class="col-md-4 form-group">
                        <label for="item_name">Item Name</label>
                        <input type="text" name="item_name" class="form-control" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="category">Category</label>
                        <select name="category" class="form-control" required>
                            <option value="" disabled selected>Select Category</option>
                            <option value="coffin">Coffin</option>
                            <option value="urn">Urn</option>
                            <option value="chapel">Chapel</option>
                            <option value="cremator">Cremator</option>
                            <option value="meeting_room">Meeting Room</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" name="quantity" class="form-control" required>
                    </div>
                </div>
                <button type="submit" name="add_item" class="btn btn-sm btn-outline-dark">Add Item</button>
            </form>
        </div>
    </div>

    <h5 class="text-dark text-uppercase mt-2">Current Inventory</h5>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Category</th>
                <th>Quantity</th>            
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inventoryData as $item): ?>
            <tr>
                <td class="text-muted text-capitalize"><?php echo htmlspecialchars($item['item_name']); ?></td>
                <td class="text-muted text-capitalize"><?php echo htmlspecialchars($item['category']); ?></td>
                <td class="text-muted text-capitalize"><?php echo htmlspecialchars($item['quantity']); ?></td>               
                <td>
                    <button class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#editItemModal<?php echo $item['id']; ?>">Edit</button>
                    <!-- Edit Item Modal -->
                    <div class="modal fade" id="editItemModal<?php echo $item['id']; ?>" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Item</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                        <div class="form-group">
                                            <label for="item_name">Item Name</label>
                                            <input type="text" name="item_name" class="form-control" value="<?php echo htmlspecialchars($item['item_name']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="category">Category</label>
                                            <select name="category" class="form-control" required>
                                                <option value="coffin" <?php echo $item['category'] === 'coffin' ? 'selected' : ''; ?>>Coffin</option>
                                                <option value="urn" <?php echo $item['category'] === 'urn' ? 'selected' : ''; ?>>Urn</option>
                                                <option value="chapel" <?php echo $item['category'] === 'chapel' ? 'selected' : ''; ?>>Chapel</option>
                                                <option value="cremator" <?php echo $item['category'] === 'cremator' ? 'selected' : ''; ?>>Cremator</option>
                                                <option value="meeting_room" <?php echo $item['category'] === 'meeting_room' ? 'selected' : ''; ?>>Meeting Room</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="quantity">Quantity</label>
                                            <input type="number" name="quantity" class="form-control" value="<?php echo htmlspecialchars($item['quantity']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="update_item" class="btn btn-primary">Update Item</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<!-- Footer -->
<?php include './includes/footer.php'; ?>
