<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Form\PostType;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function show(PostRepository $postRepository): Response
    {

        $posts = $postRepository -> findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/add', name: 'app_add')]
    public function upload(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            $title = $form->get('title')->getData();
            $description = $form->get('description')->getData();

            if ($imageFile) {
                // Convertir l'image en Base64
                $base64Image = base64_encode(file_get_contents($imageFile->getPathname()));

                // Créer une nouvelle entité Image
                $image = new Post();
                $image->setImage($base64Image);
                $image->setTitle($title);
                $image->setDescription($description); 
                $image->setUser($this->getUser()); 

                // Sauvegarder dans la base de données
                $entityManager->persist($image);
                $entityManager->flush();

                $this->addFlash('success', 'Image uploaded successfully!');
                return $this->redirectToRoute('app_post');
            }
        }

        return $this->render('post/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
