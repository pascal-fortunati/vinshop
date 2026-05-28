<?php

class ReviewController extends Controller
{
    /**
     * Créer un nouvel avis
     */
    public function store()
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $this->json(['success' => false, 'message' => 'Vous devez être connecté pour laisser un avis'], 401);
            return;
        }

        // Valider les données
        $product_id = $_POST['product_id'] ?? null;
        $rating = $_POST['rating'] ?? null;
        $comment = trim($_POST['comment'] ?? '');

        if (!$product_id || !$rating) {
            $this->json(['success' => false, 'message' => 'Données invalides'], 400);
            return;
        }

        // Valider la note (1-5)
        if ($rating < 1 || $rating > 5) {
            $this->json(['success' => false, 'message' => 'La note doit être entre 1 et 5'], 400);
            return;
        }

        $reviewModel = new Review();

        // Vérifier si l'utilisateur a déjà laissé un avis
        if ($reviewModel->hasUserReviewed($product_id, $_SESSION['user_id'])) {
            $this->json(['success' => false, 'message' => 'Vous avez déjà laissé un avis pour ce produit'], 400);
            return;
        }

        // Créer l'avis
        $reviewModel->product_id = $product_id;
        $reviewModel->user_id = $_SESSION['user_id'];
        $reviewModel->rating = $rating;
        $reviewModel->comment = $comment;
        $reviewModel->status = 'pending'; // En attente de modération

        if ($reviewModel->create()) {
            $this->json([
                'success' => true,
                'message' => 'Merci pour votre avis ! Il sera publié après modération.'
            ]);
        } else {
            $this->json(['success' => false, 'message' => 'Erreur lors de l\'enregistrement de votre avis'], 500);
        }
    }

    /**
     * Page admin : liste des avis en attente de modération
     */
    public function index()
    {
        $reviewModel = new Review();
        $reviews = $reviewModel->getAll(100);
        $pendingCount = count(array_filter($reviews, fn($r) => $r['status'] === 'pending'));

        $this->render('admin.reviews', [
            'title' => 'Modération des avis - Administration',
            'reviews' => $reviews,
            'pendingCount' => $pendingCount
        ]);
    }

    /**
     * Modérer un avis (approuver ou rejeter)
     */
    public function moderate()
    {
        $id = $_POST['review_id'] ?? null;
        $action = $_POST['action'] ?? null;

        if (!$id || !$action) {
            $this->json(['success' => false, 'message' => 'Données invalides'], 400);
            return;
        }

        if (!in_array($action, ['approve', 'reject', 'delete'])) {
            $this->json(['success' => false, 'message' => 'Action invalide'], 400);
            return;
        }

        $reviewModel = new Review();

        if ($action === 'delete') {
            $success = $reviewModel->delete($id);
            $message = $success ? 'Avis supprimé avec succès' : 'Erreur lors de la suppression';
        } else {
            $status = $action === 'approve' ? 'approved' : 'rejected';
            $success = $reviewModel->moderate($id, $status);
            $message = $success
                ? ($action === 'approve' ? 'Avis approuvé' : 'Avis rejeté')
                : 'Erreur lors de la modération';
        }

        $this->json([
            'success' => $success,
            'message' => $message
        ]);
    }
}
