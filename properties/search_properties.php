<?php
require_once '../includes/config.php';
require_once '../includes/db.php';

$database = new Database();
$conn = $database->getConnection();

// Get search parameters from GET request
$type = isset($_GET['type']) ? $_GET['type'] : '';
$city = isset($_GET['city']) ? $_GET['city'] : '';
$min_price = isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 0;

// Build query based on search parameters
$query = "SELECT p.*, u.full_name, u.phone 
          FROM properties p 
          JOIN users u ON p.user_id = u.id 
          WHERE p.is_approved = 1";

$params = [];

if(!empty($type)) {
    $query .= " AND p.type = ?";
    $params[] = $type;
}

if(!empty($city)) {
    $query .= " AND p.city = ?";
    $params[] = $city;
}

if($min_price > 0) {
    $query .= " AND p.price >= ?";
    $params[] = $min_price;
}

if($max_price > 0) {
    $query .= " AND p.price <= ?";
    $params[] = $max_price;
}

$query .= " ORDER BY p.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->execute($params);
$properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(empty($properties)) {
    echo '<div class="alert alert-info">No properties found matching your criteria.</div>';
} else {
    foreach($properties as $property): ?>
        <div class="property-card">
            <div class="property-img">
                <img src="<?php echo $property['image_path'] ? BASE_URL . $property['image_path'] : BASE_URL . 'assets/images/default-property.jpg'; ?>" alt="<?php echo htmlspecialchars($property['title']); ?>">
            </div>
            <div class="property-info">
                <h3><?php echo htmlspecialchars($property['title']); ?></h3>
                <p class="location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($property['address']); ?></p>
                
                <div class="property-meta">
                    <span><i class="fas fa-bed"></i> <?php echo $property['bedrooms']; ?> Beds</span>
                    <span><i class="fas fa-bath"></i> <?php echo $property['bathrooms']; ?> Baths</span>
                    <span><i class="fas fa-ruler-combined"></i> <?php echo $property['size']; ?> sqft</span>
                </div>
                
                <div class="property-price">
                    ৳<?php echo number_format($property['price']); ?> <span>/month</span>
                </div>
                
                <a href="view.php?id=<?php echo $property['id']; ?>" class="btn btn-outline">View Details</a>
            </div>
        </div>
    <?php endforeach;
}
?>