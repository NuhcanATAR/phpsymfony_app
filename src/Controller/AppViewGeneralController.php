<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\UserProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppViewGeneralController extends AbstractController
{
    private array $viewList = [
       ['title' => 'Flutter', 'created' => '2024/01/10',],
       ['title' => 'Php', 'created' => '2022/05/12',],
       ['title' => 'Dart', 'created' => '2022/05/12',],
       ['title' => 'Firebase', 'created' => '2022/05/12',],
       ['title' => 'Docker', 'created' => '2022/05/12',],
    ];
    
    #[Route('/{index<\d+>?3}', name: 'app_index')]
    public function initializeApp(UserProfileRepository $profiles): Response
    {
        // $user = new User();
        // $user->setEmail('email@email.com');
        // $user->setPassword('12345678');

        // $profile = new UserProfile();
        // $profile->setUser($user);
        // $profiles->add($profile, true);

        // $profile = $profiles->find(1);
        // $profiles->remove($profile, true);

        return $this->render('view/main_page.html.twig', [
            'viewList' => $this->viewList,
            'index' => 3,
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
            throw $this->render('view/detail/detail_view.html.twig', [
            
            ]);
        }
    
        return $this->render('view/detail/detail_view.html.twig', [
            'viewList' => $this->viewList[$id],
        ]);
    }
}
