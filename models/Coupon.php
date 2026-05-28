<?php
require_once 'config/Database.php';

class Coupon
{
    private $conn;
    private $table = 'coupons';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Récupérer tous les coupons
     */
    public function getAll($active_only = false)
    {
        $sql = "SELECT * FROM " . $this->table;
        if ($active_only) {
            $sql .= " WHERE active = 1";
        }
        $sql .= " ORDER BY created_at DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer un coupon par son code
     */
    public function getByCode($code)
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE code = ? LIMIT 1");
        $stmt->execute([$code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer un coupon par son ID
     */
    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Créer un nouveau coupon
     */
    public function create($data)
    {
        $sql = "INSERT INTO " . $this->table . " (code, type, value, min_amount, max_uses, valid_from, valid_until, active, first_order_only, description) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            strtoupper($data['code']),
            $data['type'],
            $data['value'],
            $data['min_amount'] ?? null,
            $data['max_uses'] ?? null,
            $data['valid_from'] ?? null,
            $data['valid_until'] ?? null,
            $data['active'] ?? 1,
            $data['first_order_only'] ?? 0,
            $data['description'] ?? null
        ]);
    }

    /**
     * Mettre à jour un coupon
     */
    public function update($id, $data)
    {
        $sql = "UPDATE " . $this->table . " 
                SET code = ?, type = ?, value = ?, min_amount = ?, max_uses = ?, 
                    valid_from = ?, valid_until = ?, active = ?, first_order_only = ?, description = ?
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            strtoupper($data['code']),
            $data['type'],
            $data['value'],
            $data['min_amount'] ?? null,
            $data['max_uses'] ?? null,
            $data['valid_from'] ?? null,
            $data['valid_until'] ?? null,
            $data['active'] ?? 1,
            $data['first_order_only'] ?? 0,
            $data['description'] ?? null,
            $id
        ]);
    }

    /**
     * Supprimer un coupon
     */
    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Basculer l'état actif d'un coupon
     */
    public function toggleActive($id)
    {
        $stmt = $this->conn->prepare("UPDATE " . $this->table . " SET active = NOT active WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Vérifier si un coupon est valide
     * Retourne un array avec 'valid' (bool) et 'message' (string)
     */
    public function isValid($code, $orderAmount, $userId = null)
    {
        $coupon = self::getByCode($code);

        if (!$coupon) {
            return ['valid' => false, 'message' => 'Code promo invalide'];
        }

        if (!$coupon['active']) {
            return ['valid' => false, 'message' => 'Ce code promo n\'est plus actif'];
        }

        // Vérifier la date de début
        if ($coupon['valid_from'] && strtotime($coupon['valid_from']) > time()) {
            return ['valid' => false, 'message' => 'Ce code promo n\'est pas encore valide'];
        }

        // Vérifier la date de fin
        if ($coupon['valid_until'] && strtotime($coupon['valid_until']) < time()) {
            return ['valid' => false, 'message' => 'Ce code promo a expiré'];
        }

        // Vérifier le montant minimum
        if ($coupon['min_amount'] && $orderAmount < $coupon['min_amount']) {
            return [
                'valid' => false,
                'message' => 'Montant minimum de ' . formatPrice($coupon['min_amount']) . ' requis'
            ];
        }

        // Vérifier le nombre d'utilisations maximum
        if ($coupon['max_uses'] && $coupon['times_used'] >= $coupon['max_uses']) {
            return ['valid' => false, 'message' => 'Ce code promo a atteint sa limite d\'utilisation'];
        }

        // Vérifier si le code est réservé aux nouveaux clients
        if (isset($coupon['first_order_only']) && $coupon['first_order_only'] == 1 && $userId) {
            if ($this->hasUserOrderedBefore($userId)) {
                return ['valid' => false, 'message' => 'Code promo réservé aux nouveaux clients'];
            }
        }

        return ['valid' => true, 'message' => 'Code promo appliqué avec succès'];
    }

    /**
     * Vérifier si un utilisateur a déjà passé des commandes
     */
    private function hasUserOrderedBefore($userId)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM orders WHERE user_id = ? AND status != 'cancelled'");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    /**
     * Calculer le montant de la réduction
     */
    public function calculateDiscount($coupon, $orderAmount)
    {
        if ($coupon['type'] === 'percentage') {
            return round($orderAmount * $coupon['value'] / 100, 2);
        } else {
            // Montant fixe, mais ne peut pas dépasser le montant de la commande
            return min($coupon['value'], $orderAmount);
        }
    }

    /**
     * Incrémenter le nombre d'utilisations
     */
    public function incrementUsage($code)
    {
        $stmt = $this->conn->prepare("UPDATE " . $this->table . " SET times_used = times_used + 1 WHERE code = ?");
        return $stmt->execute([$code]);
    }

    /**
     * Obtenir les statistiques d'un coupon
     */
    public function getStats($id)
    {
        $coupon = $this->getById($id);
        if (!$coupon) {
            return null;
        }

        // Calculer le nombre d'utilisations restantes
        $remaining = null;
        if ($coupon['max_uses']) {
            $remaining = max(0, $coupon['max_uses'] - $coupon['times_used']);
        }

        // Statut
        $status = 'active';
        if (!$coupon['active']) {
            $status = 'inactive';
        } elseif ($coupon['valid_until'] && strtotime($coupon['valid_until']) < time()) {
            $status = 'expired';
        } elseif ($coupon['valid_from'] && strtotime($coupon['valid_from']) > time()) {
            $status = 'upcoming';
        }

        return [
            'coupon' => $coupon,
            'remaining_uses' => $remaining,
            'status' => $status
        ];
    }
}
