<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/header.php';

$database = new Database();
$conn = $database->getConnection();

// Get featured properties
$query = "SELECT p.*, u.full_name, u.phone 
          FROM properties p 
          JOIN users u ON p.user_id = u.id 
          WHERE p.is_approved = 1 
          ORDER BY p.created_at DESC 
          LIMIT 6";
$stmt = $conn->prepare($query);
$stmt->execute();
$featured_properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>Find Your Perfect Bachelor Pad</h1>
            <p>Discover affordable and comfortable living spaces tailored for bachelors across the city.</p>
            <a href="properties/search.php" class="btn">Browse Properties</a>
            
            <!-- Search Box -->
            <div class="search-box">
                <form action="properties/search.php" method="GET">
                    <div class="form-group">
                        <select name="type" id="type">
                            <option value="">All Types</option>
                            <option value="house">House</option>
                            <option value="room">Room</option>
                            <option value="seat">Seat</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="city" id="city">
                            <option value="">All Cities</option>
                            <option value="dhaka">Dhaka</option>
                            <option value="chittagong">Chittagong</option>
                            <option value="sylhet">Sylhet</option>
                            <option value="khulna">Khulna</option>
                            <option value="rajshahi">Rajshahi</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="number" name="min_price" id="min_price" placeholder="Min Price">
                    </div>
                    <div class="form-group">
                        <input type="number" name="max_price" id="max_price" placeholder="Max Price">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Featured Properties -->
<section class="properties">
    <div class="container">
        <div class="section-title">
            <h2>Featured Properties</h2>
            <p>Check out our latest listings for bachelors</p>
        </div>
        
        <div class="property-grid" id="property-grid">
            <?php foreach($featured_properties as $property): ?>
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
                        
                        <a href="properties/view.php?id=<?php echo $property['id']; ?>" class="btn btn-outline">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="properties/search.php" class="btn">View All Properties</a>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="how-it-works">
    <div class="container">
        <div class="section-title">
            <h2>How It Works</h2>
            <p>Simple steps to find your perfect bachelor pad</p>
        </div>
        
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>Search</h3>
                <p>Browse through our extensive list of properties tailored for bachelors.</p>
            </div>
            
            <div class="step-card">
                <div class="step-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <h3>View</h3>
                <p>Check out property details, photos, and contact the owner.</p>
            </div>
            
            <div class="step-card">
                <div class="step-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <h3>Book</h3>
                <p>Reserve your space with our secure payment system.</p>
            </div>
            
            <div class="step-card">
                <div class="step-icon">
                    <i class="fas fa-home"></i>
                </div>
                <h3>Move In</h3>
                <p>Enjoy your new bachelor pad with all the amenities you need.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>