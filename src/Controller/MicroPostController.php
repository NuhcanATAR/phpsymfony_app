<?php
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Form\CommentType;
use App\Form\MicroPostType;
use App\Repository\CommentRepository;
use App\Repository\MicroPostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(EntityManagerInterface $entityManager, MicroPostRepository $posts): Response 
    {
        return $this->render('micro_post/index.html.twig', [
            'posts' => $posts->findAllWithComments(),
        ]);
    }

    #[Route('/micro-post/{post}', name: 'app_micro_post_show')]
    #[IsGranted(MicroPost::VIEW, 'post')]
    public function showOne(MicroPost $post): Response 
    {
        return $this->render('micro_post/show.html.twig', [
            'post' => $post,
        ],);
    }

    #[Route('micro-post/add', name: 'app_micro_post_add', priority: 2)]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function add(Request $request, EntityManagerInterface $entityManager) : Response{  
        $microPost = new MicroPost();
        $form = $this->createForm(MicroPostType::class, $microPost);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try{
               
                $microPost->setAuthor($this->getUser());
                $entityManager->persist($microPost);
                $entityManager->flush(); 
                $this->addFlash('notice', 'Your micre post have been added');
                return $this->redirectToRoute('app_micro_post');
            } catch (Exception $e) {
                $this->addFlash('error', 'An error occurred while saving the post: ' . $e->getMessage());
                $entityManager->close();
                return $this->redirectToRoute('app_micro_post');
            } 
        }

        return $this->render('micro_post/add.html.twig',[
            'form' => $form->createView(),
        ]);
    }


    #[Route('micro-post/{post}/edit', name: 'app_micro_post_edit', priority: 2)] 
    #[IsGranted(MicroPost::EDIT, 'post')]
    public function edit(
        MicroPost $post,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {

        $form = $this->createForm(MicroPostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try{
                $entityManager->persist($post);
                $entityManager->flush(); 
                
                $this->addFlash('success', 'Your micre post have been updated');

               
            } catch (Exception $e) {
                $this->addFlash('error', 'An error occurred while saving the post: ' . $e->getMessage());
                $entityManager->close();
            }

            return $this->redirectToRoute('app_micro_post');
            
        }

        return $this->render('micro_post/edit.html.twig',[
            'form' => $form->createView(),
            'post' => $post
        ]);
    }



    #[Route('/micro-post/{post}/comment', name: 'app_micro_post_comment')]
    #[IsGranted('ROLE_COMMENTER')]
    public function addComment(
        MicroPost $post,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(CommentType::class, new Comment());
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setPost($post);
            $comment->setAuthor($this->getUser());
            $comment->setCreated(new DateTime()); // created alanını şu anki tarih ve zamanla set et
    
            // Kaydetmek için EntityManager'ı kullan
            $entityManager->persist($comment); // Comment nesnesini kaydet
            $entityManager->flush(); // Veritabanına yaz
    
            // Flash mesajı ekle
            $this->addFlash('success', 'Your comment has been added.');
    
            // Yönlendir
            return $this->redirectToRoute(
                'app_micro_post_show',
                ['post' => $post->getId()]
            );
        }
    
        return $this->render(
            'micro_post/comment.html.twig',
            [
                'form' => $form->createView(), // Formu render et
                'post' => $post
            ]
        );
    }
    
    
}