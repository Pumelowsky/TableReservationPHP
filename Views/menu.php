<div class="container py-5">
    <h2 class="text-center mb-4">Nasze Menu</h2>
    <div class="row g-4">
        <?php
        if (!empty($menuItems)) {
            foreach ($menuItems as $item) {
                echo '
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-primary">' . htmlspecialchars($item["name"]) . '</h5>
                                <p class="card-text">' . htmlspecialchars($item["description"]) . '</p>
                                <p class="card-text fw-bold">Cena: ' . number_format($item["price"], 2) . ' PLN</p>
                            </div>
                        </div>
                    </div>';
            }
        } else {
            echo '<p class="text-center">Brak pozycji w menu.</p>';
        }
        ?>
    </div>
</div>