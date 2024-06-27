<?php
/**
 * Comment controller.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\Type\CommentType;
use App\Repository\CommentRepository;
use App\Service\CommentServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class CommentController.
 */
#[Route('/comment')]
class CommentController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param CommentServiceInterface $commentService    Comment service
     * @param TranslatorInterface     $translator        Translator
     * @param CommentRepository       $commentRepository Comment repository
     */
    public function __construct(private readonly CommentServiceInterface $commentService, private readonly TranslatorInterface $translator, private readonly CommentRepository $commentRepository)
    {
    }

    /**
     * Create action.
     *
     * @param Post    $post    Post entity
     * @param Request $request HTTP Request
     *
     * @return Response HTTP Response
     */
    #[Route(
        '/create/{post}',
        name: 'comment_create',
        methods: 'GET|POST'
    )]
    public function create(Post $post, Request $request): Response
    {
        $comment = new Comment();
        $comment->setPost($post);

        $form = $this->createForm(
            CommentType::class,
            $comment,
            [
                'is_user_logged_in' => (bool) $this->getUser(),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->getUser()) {
                $comment->setUser($this->getUser());
            } else {
                $comment->setNickname($form->get('nickname')->getData());
                $comment->setEmail($form->get('email')->getData());
            }

            $this->commentService->saveComment($comment);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash(
                'error',
                $this->translator->trans('message.something_went_wrong')
            );
        }

        $comments = $this->commentRepository->findBy(['post' => $post]);

        return $this->render('comment/create.html.twig', [
            'post' => $post,
            'comments' => $comments,
            'form' => $form,
        ]);
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP Request
     * @param Comment $comment Comment entity
     *
     * @return Response HTTP Response
     */
    #[Route('/{id}/delete', name: 'comment_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(
            FormType::class,
            $comment,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl('comment_delete', ['id' => $comment->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentService->deleteComment($comment);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('post_show', ['id' => $comment->getPost()->getId()]);
        }

        return $this->render(
            'comment/delete.html.twig',
            [
                'form' => $form->createView(),
                'comment' => $comment,
                'postId' => $comment->getPost()->getId(),
            ]
        );
    }
}
