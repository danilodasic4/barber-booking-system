<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\RegistrationService;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormError;

class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly RegistrationService $registrationService,
        private readonly UserRepository $userRepository
    ) {}

    #[Route('/register', name: 'app_register', methods: ['POST', 'GET'])]
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
 $form->handleRequest($request);

if ($form->isSubmitted()) {
    $existingUser = $this->userRepository->findOneByEmail($form->get('email')->getData());
    if ($existingUser) {
        $form->get('email')->addError(new FormError('This email is already taken.'));
    }

    if ($form->isValid()) {
        $plainPassword = $form->get('plainPassword')->getData();

        try {
            $message = $this->registrationService->registerUser($user, $plainPassword);

            $this->addFlash('success', $message);
            return $this->redirectToRoute('registration_success');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Registration failed. Please try again.');
        }
    }
}

        // If the form isn't valid or after handling success/error, render the form again
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/registration/success', name: 'registration_success')]
    public function registrationSuccess(): Response
    {
        return $this->render('registration/success.html.twig');
    }
}

