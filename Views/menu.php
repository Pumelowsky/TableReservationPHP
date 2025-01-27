<div class="container py-5">
    <h2 class="text-center mb-4">Nasze Menu</h2>
    <div class="row g-4">
        <?php
        if (!empty($menuItems)) {
            foreach ($menuItems as $item) {
                $isFavorite = !empty($item['is_favorite']) && $item['is_favorite'];

                echo '<div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm ' . ($isFavorite ? 'favorite' : '') . '">
                            <div class="card-body">
                                <h5 class="card-title text-primary">';
                if ($isFavorite) {
                    echo '<i class="bi bi-star-fill text-warning me-2"></i>';
                }
                echo htmlspecialchars($item["name"]) . '</h5>
                                <p class="card-text">' . htmlspecialchars($item["description"]) . '</p>
                                <p class="card-text fw-bold">Cena: ' . number_format($item["price"], 2) . ' PLN</p>';
                if (!$isFavorite) {
                    echo '<form method="POST" action="">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="menu_id" value="' . htmlspecialchars($item['id']) . '">
                            <button type="submit" class="btn btn-primary">Dodaj do ulubionych</button>
                          </form>';
                } else {
                    echo '<form method="POST" action="">
                            <input type="hidden" name="action" value="remove">
                            <input type="hidden" name="menu_id" value="' . htmlspecialchars($item['id']) . '">
                            <button type="submit" class="btn btn-danger">Usuń z ulubionych</button>
                          </form>';
                }

                echo '</div>
                    </div>
                </div>';
            }
        } else {
            echo '<p class="text-center">Brak pozycji w menu.</p>';
        }
        ?>
    </div>
</div>