<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function show(PostRepository $postRepository): Response
    {

        $posts = $postRepository -> findAll();
        // $users = $userRepository -> findAll();
        // $user = $this->getUser();
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
            // 'user' => $user
        ]);
    }
}
