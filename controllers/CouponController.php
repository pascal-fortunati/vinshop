<?php

class CouponController extends Controller
{
    private $couponModel;

    public function __construct()
    {
        $this->couponModel = new Coupon();
    }

    /**
     * Liste des coupons (Admin)
     */
    public function index()
    {
        Auth::requireAdmin();

        $coupons = $this->couponModel->getAll();

        // Enrichir chaque coupon avec ses stats
        foreach ($coupons as &$coupon) {
            $stats = $this->couponModel->getStats($coupon['id']);
            $coupon['stats'] = $stats;
        }

        $this->render('admin.coupons', [
            'title' => 'Gestion des Codes Promo - VinShop',
            'coupons' => $coupons
        ]);
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        Auth::requireAdmin();
        $this->render('admin.coupon_form', [
            'title' => 'Créer un Code Promo - VinShop'
        ]);
    }

    /**
     * Enregistrer un nouveau coupon
     */
    public function store()
    {
        Auth::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/coupons');
            return;
        }

        // Validation
        $errors = [];

        if (empty($_POST['code'])) {
            $errors[] = 'Le code est requis';
        }

        if (empty($_POST['type']) || !in_array($_POST['type'], ['percentage', 'fixed'])) {
            $errors[] = 'Type de réduction invalide';
        }

        if (empty($_POST['value']) || $_POST['value'] <= 0) {
            $errors[] = 'La valeur doit être supérieure à 0';
        }

        if ($_POST['type'] === 'percentage' && $_POST['value'] > 100) {
            $errors[] = 'Le pourcentage ne peut pas dépasser 100%';
        }

        // Vérifier que le code n'existe pas déjà
        if ($this->couponModel->getByCode($_POST['code'])) {
            $errors[] = 'Ce code promo existe déjà';
        }

        if (!empty($errors)) {
            setFlash('error', implode('<br>', $errors));
            $this->redirect('/admin/coupons/create');
            return;
        }

        // Créer le coupon
        $data = [
            'code' => $_POST['code'],
            'type' => $_POST['type'],
            'value' => $_POST['value'],
            'min_amount' => !empty($_POST['min_amount']) ? $_POST['min_amount'] : null,
            'max_uses' => !empty($_POST['max_uses']) ? $_POST['max_uses'] : null,
            'valid_from' => !empty($_POST['valid_from']) ? $_POST['valid_from'] : null,
            'valid_until' => !empty($_POST['valid_until']) ? $_POST['valid_until'] : null,
            'active' => isset($_POST['active']) ? 1 : 0,
            'first_order_only' => isset($_POST['first_order_only']) ? 1 : 0,
            'description' => $_POST['description'] ?? null
        ];

        if ($this->couponModel->create($data)) {
            setFlash('success', 'Code promo créé avec succès');
        } else {
            $this->redirect(url('/admin/coupons/create'),  'Erreur lors de la création du code promo');
        }

        $this->redirect('/admin/coupons');
    }

    /**
     * Formulaire d'édition
     */
    public function edit($id)
    {
        Auth::requireAdmin();

        $coupon = $this->couponModel->getById($id);
        if (!$coupon) {
            setFlash('error', 'Code promo introuvable');
            $this->redirect('/admin/coupons');
            return;
        }

        $this->render('admin.coupon_form', [
            'title' => 'Modifier un Code Promo - VinShop',
            'coupon' => $coupon
        ]);
    }

    /**
     * Mettre à jour un coupon
     */
    public function update($id)
    {
        Auth::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/coupons');
            return;
        }

        $coupon = $this->couponModel->getById($id);
        if (!$coupon) {
            $this->redirect(url('/admin/coupons/create'),  'Code promo introuvable');
            $this->redirect('/admin/coupons');
            return;
        }

        // Validation
        $errors = [];

        if (empty($_POST['code'])) {
            $errors[] = 'Le code est requis';
        }

        // Vérifier que le code n'existe pas déjà (sauf pour ce coupon)
        $existingCoupon = $this->couponModel->getByCode($_POST['code']);
        if ($existingCoupon && $existingCoupon['id'] != $id) {
            $errors[] = 'Ce code promo existe déjà';
        }

        if (empty($_POST['type']) || !in_array($_POST['type'], ['percentage', 'fixed'])) {
            $errors[] = 'Type de réduction invalide';
        }

        if (empty($_POST['value']) || $_POST['value'] <= 0) {
            $errors[] = 'La valeur doit être supérieure à 0';
        }

        if ($_POST['type'] === 'percentage' && $_POST['value'] > 100) {
            $errors[] = 'Le pourcentage ne peut pas dépasser 100%';
        }

        if (!empty($errors)) {
            $this->redirect(url('/admin/coupons/create'),  implode('<br>', $errors));
            $this->redirect('/admin/coupons/edit/' . $id);
            return;
        }

        // Mettre à jour
        $data = [
            'code' => $_POST['code'],
            'type' => $_POST['type'],
            'value' => $_POST['value'],
            'min_amount' => !empty($_POST['min_amount']) ? $_POST['min_amount'] : null,
            'max_uses' => !empty($_POST['max_uses']) ? $_POST['max_uses'] : null,
            'valid_from' => !empty($_POST['valid_from']) ? $_POST['valid_from'] : null,
            'valid_until' => !empty($_POST['valid_until']) ? $_POST['valid_until'] : null,
            'active' => isset($_POST['active']) ? 1 : 0,
            'first_order_only' => isset($_POST['first_order_only']) ? 1 : 0,
            'description' => $_POST['description'] ?? null
        ];

        if ($this->couponModel->update($id, $data)) {
            setFlash('success', 'Code promo mis à jour avec succès');
        } else {
            $this->redirect(url('/admin/coupons/create'),  'Erreur lors de la mise à jour du code promo');
        }

        $this->redirect('/admin/coupons');
    }

    /**
     * Supprimer un coupon
     */
    public function delete($id)
    {
        Auth::requireAdmin();

        if ($this->couponModel->delete($id)) {
            setFlash('success', 'Code promo supprimé avec succès');
        } else {
            $this->redirect(url('/admin/coupons/create'),  'Erreur lors de la suppression du code promo');
        }

        $this->redirect('/admin/coupons');
    }

    /**
     * Activer/Désactiver un coupon
     */
    public function toggle($id)
    {
        Auth::requireAdmin();

        if ($this->couponModel->toggleActive($id)) {
            setFlash('success', 'Statut du code promo modifié');
        } else {
            $this->redirect(url('/admin/coupons/create'),  'Erreur lors de la modification du statut');
        }

        $this->redirect('/admin/coupons');
    }

    /**
     * API: Vérifier et appliquer un code promo
     */
    public function validateCoupon()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }

        $code = $_POST['code'] ?? '';
        $orderAmount = floatval($_POST['amount'] ?? 0);

        if (empty($code)) {
            echo json_encode(['success' => false, 'message' => 'Code promo requis']);
            return;
        }

        if ($orderAmount <= 0) {
            echo json_encode(['success' => false, 'message' => 'Montant invalide']);
            return;
        }

        // Récupérer l'ID utilisateur si connecté
        $userId = $_SESSION['user_id'] ?? null;

        // Vérifier la validité du coupon
        $validation = $this->couponModel->isValid($code, $orderAmount, $userId);

        if (!$validation['valid']) {
            echo json_encode([
                'success' => false,
                'message' => $validation['message']
            ]);
            return;
        }

        // Récupérer le coupon et calculer la réduction
        $coupon = $this->couponModel->getByCode($code);
        $discount = $this->couponModel->calculateDiscount($coupon, $orderAmount);
        $newTotal = max(0, $orderAmount - $discount);

        echo json_encode([
            'success' => true,
            'message' => $validation['message'],
            'coupon' => [
                'code' => $coupon['code'],
                'type' => $coupon['type'],
                'value' => $coupon['value'],
                'discount' => $discount,
                'formatted_discount' => formatPrice($discount)
            ],
            'totals' => [
                'subtotal' => $orderAmount,
                'discount' => $discount,
                'total' => $newTotal,
                'formatted_subtotal' => formatPrice($orderAmount),
                'formatted_discount' => formatPrice($discount),
                'formatted_total' => formatPrice($newTotal)
            ]
        ]);
    }
}
