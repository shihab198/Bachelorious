<?php
require_once '../includes/config.php';
require_once '../includes/db.php';

$database = new Database();
$conn = $database->getConnection();

// Get search parameters from URL
$type = isset($_GET['type']) ? $_GET['type'] : '';
$city = isset($_GET['city']) ? $_GET['city'] : '';
$min_price = isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 0;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

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

// Add sorting
switch($sort) {
    case 'price_low':
        $query .= " ORDER BY p.price ASC";
        break;
    case 'price_high':
        $query .= " ORDER BY p.price DESC";
        break;
    case 'newest':
    default:
        $query .= " ORDER BY p.created_at DESC";
        break;
}

$stmt = $conn->prepare($query);
$stmt->execute($params);
$properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../includes/header.php';
?>

<div class="container">
    <div class="properties">
        <div class="section-title">
            <h2>Available Properties</h2>
            <p>Find your perfect bachelor pad</p>
        </div>
        
        <div class="search-filters">
            <form id="property-search-form" method="GET" action="search.php">
                <div class="form-row">
                    <div class="form-group">
                        <select name="type" id="type">
                            <option value="">All Types</option>
                            <option value="house" <?php echo $type == 'house' ? 'selected' : ''; ?>>House</option>
                            <option value="room" <?php echo $type == 'room' ? 'selected' : ''; ?>>Room</option>
                            <option value="seat" <?php echo $type == 'seat' ? 'selected' : ''; ?>>Seat</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <select name="city" id="city">
                            <option value="">All Cities</option>
                            <option value="dhaka" <?php echo $city == 'dhaka' ? 'selected' : ''; ?>>Dhaka</option>
                            <option value="chittagong" <?php echo $city == 'chittagong' ? 'selected' : ''; ?>>Chittagong</option>
                            <option value="sylhet" <?php echo $city == 'sylhet' ? 'selected' : ''; ?>>Sylhet</option>
                            <option value="khulna" <?php echo $city == 'khulna' ? 'selected' : ''; ?>>Khulna</option>
                            <option value="rajshahi" <?php echo $city == 'rajshahi' ? 'selected' : ''; ?>>Rajshahi</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <input type="number" name="min_price" id="min_price" placeholder="Min Price" value="<?php echo $min_price > 0 ? $min_price : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <input type="number" name="max_price" id="max_price" placeholder="Max Price" value="<?php echo $max_price > 0 ? $max_price : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <select name="sort" id="sort-properties">
                            <option value="newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Newest First</option>
                            <option value="price_low" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                            <option value="price_high" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn">Apply Filters</button>
                    </div>
                </div>
            </form>
        </div>
        
        <?php if(empty($properties)): ?>
            <div class="alert alert-info">
                <p>No properties found matching your criteria. Try adjusting your search filters.</p>
                <span class="close-alert">&times;</span>
            </div>
        <?php else: ?>
            <div class="property-grid" id="property-grid">
                <?php foreach($properties as $property): ?>
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
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>