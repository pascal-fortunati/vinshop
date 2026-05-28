<?php

class LegalController extends Controller
{
    public function privacy()
    {
        $this->render('legal.privacy', [
            'title' => 'Politique de Confidentialité - VinShop'
        ]);
    }

    public function terms()
    {
        $this->render('legal.terms', [
            'title' => 'Mentions Légales - VinShop'
        ]);
    }

    public function cookies()
    {
        $this->render('legal.cookies', [
            'title' => 'Politique des Cookies - VinShop'
        ]);
    }

    public function accessibility()
    {
        $this->render('legal.accessibility', [
            'title' => 'Déclaration d\'Accessibilité - VinShop'
        ]);
    }
}
