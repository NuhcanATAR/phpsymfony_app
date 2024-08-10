<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppViewGeneralController extends AbstractController
{
    private array $viewList = [
       ['title' => 'Hello', 'created' => '2024/01/10',],
       ['title' => 'Bye', 'created' => '2022/05/12',],
    ];

    #[Route('/{index<\d+>?3}', name: 'app_index')]
    public function initializeApp(int $index = 3): Response
    {
        return $this->render('view/main_page.html.twig', [
            'viewList' => $this->viewList,
            'index' => $index,
        ]);
    }
    
    #[Route('/viewList/{id<\d+>}', name: 'first_direct')]
    public function showFirstDirect(int $id): Response
    {
        if (!isset($this->viewList[$id])) {
            throw $this->createNotFoundException('Page not found');
        }
    
        return $this->render('view/detail/detail_view.html.twig', [
            'viewList' => $this->viewList[$id],
        ]);
    }
    
    #[Route('/{id<\d+>}', name: 'second_direct')]
    public function showSecondDirect(int $id): Response
    {
        if (!isset($this->viewList[$id])) {
            throw $this->createNotFoundException('Message not found');
        }
    
        return $this->render('view/detail/detail_view.html.twig', [
            'viewList' => $this->viewList[$id],
        ]);
    }
}
