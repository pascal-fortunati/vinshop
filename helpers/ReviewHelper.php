<?php

/**
 * Helper pour afficher les étoiles de notation
 */
function renderStars($rating, $maxStars = 5, $showRating = true)
{
    $rating = round($rating * 2) / 2; // Arrondir à 0.5
    $output = '<div class="stars-rating d-inline-block">';

    for ($i = 1; $i <= $maxStars; $i++) {
        if ($rating >= $i) {
            // Étoile pleine
            $output .= '<i class="fas fa-star text-warning"></i>';
        } elseif ($rating >= $i - 0.5) {
            // Demi-étoile
            $output .= '<i class="fas fa-star-half-alt text-warning"></i>';
        } else {
            // Étoile vide
            $output .= '<i class="far fa-star text-warning"></i>';
        }
    }

    if ($showRating) {
        $output .= ' <span class="ms-1">' . number_format($rating, 1) . '</span>';
    }

    $output .= '</div>';
    return $output;
}

/**
 * Helper pour le sélecteur d'étoiles interactif
 */
function renderStarSelector($name = 'rating', $required = true)
{
    $output = '<div class="star-selector" data-name="' . $name . '">';

    for ($i = 1; $i <= 5; $i++) {
        $output .= '<i class="far fa-star star-selectable" data-value="' . $i . '"></i>';
    }

    $output .= '</div>';
    $output .= '<input type="hidden" name="' . $name . '" id="' . $name . '" value="" ' . ($required ? 'required' : '') . '>';

    return $output;
}

/**
 * Récupérer les informations de notation d'un produit
 */
function getProductRating($product_id)
{
    require_once __DIR__ . '/../models/Review.php';
    $reviewModel = new Review();
    return $reviewModel->getAverageRating($product_id);
}
