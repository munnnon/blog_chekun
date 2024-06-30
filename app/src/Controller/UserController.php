<?php
/**
 * UserController.
 */

namespace App\Controller;

use App\Form\Type\ChangePasswordType;
use App\Form\Type\EditProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * UserController class.
 */
class UserController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param TranslatorInterface $translator Translator
     */
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    /**
     * Change user password action.
     *
     * @param int                         $id             User index
     * @param Request                     $request        Request
     * @param UserPasswordHasherInterface $passwordHasher Password hasher
     * @param EntityManagerInterface      $em             Entity Manager
     *
     * @return RedirectResponse|Response HTTP Response
     */
    #[Route('/user/{id}/change-password', name: 'changePassword')]
    public function changePassword(int $id, Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em)
    {
        $user = $this->getUser();

        if (!$user || $user->getId() !== $id) {
            $this->addFlash(
                'error',
                $this->translator->trans('message.access_denied')
            );
        }

        if (!$user instanceof \Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface) {
            throw new \LogicException('The user entity must implement PasswordAuthenticatedUserInterface.');
        }

        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $oldPassword = $form->get('oldPassword')->getData();

            if (!$passwordHasher->isPasswordValid($user, $oldPassword)) {
                $this->addFlash(
                    'error',
                    $this->translator->trans('message.incorrect_password')
                );

                return $this->redirectToRoute('changePassword', ['id' => $id]);
            }

            $newPassword = $data['newPassword'];
            $confirmPassword = $form->get('confirmPassword')->getData();

            if ($newPassword !== $confirmPassword) {
                $this->addFlash(
                    'error',
                    $this->translator->trans('message.password_do_not_match')
                );

                return $this->redirectToRoute('changePassword', ['id' => $id]);
            }

            $encodedPassword = $passwordHasher->hashPassword($user, $newPassword);
            $user->setPassword($encodedPassword);
            $em->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('message.updated_successfully')
            );

            return $this->redirectToRoute('post_index');
        }
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash(
                'error',
                $this->translator->trans('message.something_went_wrong')
            );
        }

        return $this->render('user/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit action.
     *
     * @param int                    $id      User index
     * @param Request                $request Request
     * @param EntityManagerInterface $em      Entity Manager
     *
     * @return RedirectResponse|Response HTTP Response
     */
    #[Route('/user/{id}/edit', name: 'user_edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $em)
    {
        $user = $this->getUser();

        if (!$user || $user->getId() !== $id) {
            $this->addFlash(
                'error',
                $this->translator->trans('message.access_denied')
            );
        }

        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash(
                    'error',
                    $this->translator->trans('message.something_went_wrong')
                );
            } else {
                $submittedData = $form->getData();
                if (null === $submittedData->getNickname()) {
                    $submittedData->setNickname($user->getNickname());
                }
                if (null === $submittedData->getEmail()) {
                    $submittedData->setEmail($user->getEmail());
                }
                $em->flush();

                $this->addFlash(
                    'success',
                    $this->translator->trans('message.updated_successfully')
                );

                return $this->redirectToRoute('post_index');
            }
        }
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash(
                'error',
                $this->translator->trans('message.something_went_wrong')
            );
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
