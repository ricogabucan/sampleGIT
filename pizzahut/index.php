<?php
session_start();
include 'includes/auth.php';
include 'includes/db.php'; // $pdo initialized
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>PIZZA HUT</title>
    <link rel="icon" type="image/x-icon" href="assets/clothes.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/navbar.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="css/footer.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="css/index.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="css/products.css?v=<?= time(); ?>"> <!-- Your custom products CSS -->

    <style>
        html {
            scroll-behavior: smooth;
        }
        /* Offset scroll for fixed navbar height */
        #all-products-section {
            scroll-margin-top: 70px; /* Adjust this value based on your navbar height */
        }
    </style>
</head>

<body class="d-flex flex-column h-100">

<!-- Session Alerts -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success text-center mb-0">
        <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger text-center mb-0">
        <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<main class="flex-shrink-0">
    <?php 
    // IMPORTANT: Navbar link for Products should be <a href="#all-products-section" class="nav-link">Products</a>
    include 'includes/navbar.php'; 
    ?>

    <!-- Carousel -->
    <div id="homepageCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active"><img src="images/ad1.jpg" class="d-block w-100" alt="Ad 1"></div>
            <div class="carousel-item"><img src="images/ad2.jpg" class="d-block w-100" alt="Ad 2"></div>
            <div class="carousel-item"><img src="images/ad3.jpg" class="d-block w-100" alt="Ad 3"></div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#homepageCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"><i class="bi bi-chevron-left"></i></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#homepageCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"><i class="bi bi-chevron-right"></i></span>
        </button>
    </div>

    <!-- All Products Section -->
    <div id="all-products-section" class="container my-5">
        <h3 class="mb-4 text-center">ALL PRODUCTS</h3>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-4 g-4">
            <?php
            try {
                $stmt = $pdo->prepare("SELECT * FROM products ORDER BY created_at DESC");
                $stmt->execute();
                foreach ($stmt as $product): ?>
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-img-wrapper">
                                <img src="<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>" class="img-fluid" />
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($product['name']); ?></h5>
                                <p class="card-text"><?= htmlspecialchars($product['description']); ?></p>
                                <p class="card-text fw-bold mt-auto">$<?= number_format($product['price'], 2); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach;
            } catch (PDOException $e) {
                echo "<div class='alert alert-danger'>Error fetching products: " . htmlspecialchars($e->getMessage()) . "</div>";
            }
            ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <?php if (file_exists('includes/user.php')) include 'includes/user.php'; ?>
</main>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Smooth scroll for Products nav link -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const productLinks = document.querySelectorAll('a[href="#all-products-section"]');
    productLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector('#all-products-section');
        if (target) {
          const yOffset = -70; // Adjust offset to match navbar height
          const y = target.getBoundingClientRect().top + window.pageYOffset + yOffset;
          window.scrollTo({ top: y, behavior: 'smooth' });
        }
      });
    });
  });
</script>

</body>
</html>
