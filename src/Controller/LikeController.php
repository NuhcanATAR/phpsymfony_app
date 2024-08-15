<?php

namespace App\Controller;

use App\Entity\MicroPost;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class LikeController extends AbstractController
{
    #[Route('/like/{id}', name: 'app_like')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function like(MicroPost $post, EntityManagerInterface $posts, Request $request): Response
    {
       $currentUser = $this->getUser();
       $post->addLikedBy($currentUser);
       try{
        $posts->persist($post);
       $posts->flush();
       return $this->redirect($request->headers->get('referer'));
       }catch (Exception $e){
        $posts->close();
       return $this->redirect($request->headers->get('referer'));
       }
    
       
    }

    #[Route('/unlike/{id}', name: 'app_unlike')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function unlike(MicroPost $post, EntityManagerInterface $posts, Request $request): Response
    {
        $currentUser = $this->getUser();
        $post->removeLikedBy($currentUser);
        
        try{
         $posts->persist($post);
        $posts->flush();
        return $this->redirect($request->headers->get('referer'));
        }catch(Exception $ex){
           
            $posts->close();
    
            return $this->redirect($request->headers->get('referer'));
        }
    }
}
