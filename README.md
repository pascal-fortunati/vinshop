# Vinshop

Boutique e-commerce PHP (MVC).

## Fonctionnalites

- Authentification, profil utilisateur
- Catalogue produits, recherche, filtres
- Panier, commande, historique
- Coupons, wishlist
- Reviews produits
- Back-office admin

## Prerequis

- PHP 8.x
- MariaDB
- Nginx/Apache
- Docker Compose

## Installation (locale)

1. Cloner le projet.
2. Creer une base de donnees et importer `vinshop.sql`.
3. Configurer `config/Database.php`.
4. Configurer le vhost web vers `public/`.
5. Lancer le serveur web et MySQL.

## Installation (Docker)

1. `docker compose up -d --build`
2. Importer `vinshop.sql` dans la base.
3. Ouvrir le site via le port expose par nginx.

## Arborescence

- `controllers/` logique applicative
- `models/` acces donnees
- `views/` vues PHP
- `routes/` definitions des routes
- `public/` assets web

## MCD (Mermaid ER)

```mermaid
erDiagram
    USER {
        int id
        string name
        string email
        string password_hash
        string role
        datetime created_at
    }

    PRODUCT {
        int id
        string name
        string sku
        string description
        decimal price
        int stock
        string image
        datetime created_at
    }

    CART {
        int id
        int user_id
        datetime created_at
    }

    CART_ITEM {
        int id
        int cart_id
        int product_id
        int quantity
        decimal unit_price
    }

    ORDER {
        int id
        int user_id
        decimal total
        string status
        datetime created_at
    }

    ORDER_ITEM {
        int id
        int order_id
        int product_id
        int quantity
        decimal unit_price
    }

    REVIEW {
        int id
        int user_id
        int product_id
        int rating
        string comment
        datetime created_at
    }

    WISHLIST {
        int id
        int user_id
        datetime created_at
    }

    WISHLIST_ITEM {
        int id
        int wishlist_id
        int product_id
        datetime created_at
    }

    COUPON {
        int id
        string code
        string type
        decimal value
        datetime starts_at
        datetime ends_at
        bool active
    }

    USER ||--o{ CART : "has"
    CART ||--o{ CART_ITEM : "contains"
    PRODUCT ||--o{ CART_ITEM : "in"

    USER ||--o{ ORDER : "places"
    ORDER ||--o{ ORDER_ITEM : "contains"
    PRODUCT ||--o{ ORDER_ITEM : "in"

    USER ||--o{ REVIEW : "writes"
    PRODUCT ||--o{ REVIEW : "receives"

    USER ||--o{ WISHLIST : "has"
    WISHLIST ||--o{ WISHLIST_ITEM : "contains"
    PRODUCT ||--o{ WISHLIST_ITEM : "in"

    COUPON ||--o{ ORDER : "applied_to"
```

## MLD (Mermaid ER)

```mermaid
erDiagram
    users ||--o{ carts : user_id
    carts ||--o{ cart_items : cart_id
    products ||--o{ cart_items : product_id

    users ||--o{ orders : user_id
    orders ||--o{ order_items : order_id
    products ||--o{ order_items : product_id

    users ||--o{ reviews : user_id
    products ||--o{ reviews : product_id

    users ||--o{ wishlists : user_id
    wishlists ||--o{ wishlist_items : wishlist_id
    products ||--o{ wishlist_items : product_id

    coupons ||--o{ orders : coupon_id
```

## Flux commande (Mermaid)

```mermaid
flowchart LR
    A[User] --> B[Ajoute produit au panier]
    B --> C[Valide panier]
    C --> D[Creation commande]
    D --> E[Application coupon]
    E --> F[Maj stock]
    F --> G[Confirmation]
```
