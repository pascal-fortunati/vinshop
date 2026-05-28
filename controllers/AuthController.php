<?php

class AuthController extends Controller
{

    // Page de connexion
    public function login()
    {
        $this->render('auth.login', [
            'title' => 'Connexion - VinShop'
        ]);
    }

    // Traitement de la connexion
    public function loginPost()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->redirect('/login', 'Veuillez remplir tous les champs', 'error');
        }

        $userModel = new User();
        $user = $userModel->login($email, $password);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];

            // Redirection selon le rôle
            if ($user['role'] === 'admin') {
                $this->redirect('/admin/dashboard', 'Bienvenue ' . $user['first_name'] . ' !', 'success');
            } else {
                $this->redirect('/', 'Bienvenue ' . $user['first_name'] . ' !', 'success');
            }
        } else {
            $this->redirect('/login', 'Email ou mot de passe incorrect', 'error');
        }
    }

    // Page d'inscription
    public function register()
    {
        $this->render('auth.register', [
            'title' => 'Inscription - VinShop'
        ]);
    }

    // Traitement de l'inscription
    public function registerPost()
    {
        $data = [
            'username' => trim($_POST['username'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? '',
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
        ];

        // Validation
        $rules = [
            'username' => 'required|min:3|max:50',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
        ];

        if (!$this->validate($data, $rules)) {
            $this->redirect('/register');
        }

        if ($data['password'] !== $data['confirm_password']) {
            $this->redirect('/register', 'Les mots de passe ne correspondent pas', 'error');
        }

        $userModel = new User();

        if ($userModel->emailExists($data['email'])) {
            $this->redirect('/register', 'Cet email est déjà utilisé', 'error');
        }

        if ($userModel->usernameExists($data['username'])) {
            $this->redirect('/register', 'Ce nom d\'utilisateur est déjà pris', 'error');
        }

        // Création du compte
        $userModel->username = $data['username'];
        $userModel->email = $data['email'];
        $userModel->password = $data['password'];
        $userModel->first_name = $data['first_name'];
        $userModel->last_name = $data['last_name'];
        $userModel->phone = $data['phone'];
        $userModel->address = $data['address'];
        $userModel->role = 'user';

        if ($userModel->register()) {
            clearOld();
            $this->redirect('/login', 'Compte créé avec succès ! Vous pouvez maintenant vous connecter.', 'success');
        } else {
            $this->redirect('/register', 'Une erreur est survenue lors de l\'inscription', 'error');
        }
    }

    // Déconnexion
    public function logout()
    {
        session_destroy();
        $this->redirect('/login', 'Vous avez été déconnecté', 'info');
    }

    // Profil utilisateur
    public function profile()
    {
        $userModel = new User();
        $user = $userModel->getById($_SESSION['user_id']);

        $this->render('auth.profile', [
            'title' => 'Mon Profil - VinShop',
            'user' => $user
        ]);
    }

    // Mettre à jour le profil
    public function updateProfile()
    {
        $userModel = new User();

        $data = [
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
        ];

        $rules = [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
        ];

        if (!$this->validate($data, $rules)) {
            $this->back();
        }

        $userModel->id = $_SESSION['user_id'];
        $userModel->first_name = $data['first_name'];
        $userModel->last_name = $data['last_name'];
        $userModel->phone = $data['phone'];
        $userModel->address = $data['address'];

        // Mise à jour du mot de passe si fourni
        if (!empty($_POST['new_password'])) {
            if ($_POST['new_password'] !== $_POST['confirm_password']) {
                $this->redirect('/profile', 'Les mots de passe ne correspondent pas', 'error');
            }

            if (strlen($_POST['new_password']) < 6) {
                $this->redirect('/profile', 'Le mot de passe doit contenir au moins 6 caractères', 'error');
            }

            $userModel->password = $_POST['new_password'];
        }

        if ($userModel->updateProfile()) {
            clearOld();
            $_SESSION['username'] = $data['first_name'] . ' ' . $data['last_name'];
            $this->redirect('/profile', 'Profil mis à jour avec succès', 'success');
        } else {
            $this->redirect('/profile', 'Erreur lors de la mise à jour', 'error');
        }
    }
}
