<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\MicroPostRepository;
use App\Repository\UserProfileRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
    public function initializeApp(MicroPostRepository $postRepository, EntityManagerInterface $entityManager): Response
    {
        try{
            $post = new MicroPost();
            $post->setTitle('Hello');
            $post->setText('This is the first post');
            $post->setCreated(new DateTime());
        
            $comment = new Comment();
            $comment->setText('This is the first comment');
            $post->addComment($comment);
        
            // Veritabanına kaydet
            $entityManager->persist($post);
            $entityManager->persist($comment);
            $entityManager->flush(); // Veritabanına yazma işlemi
        }catch (Exception $e){
            // Hata mesajı ile birlikte render ediliyor
            $this->addFlash('error', 'An error occurred while saving the post: '. $e->getMessage());
        }
    
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
