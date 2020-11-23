<?php

namespace App\Controller\Api;

use Exception;
use App\Entity\User;
use FOS\UserBundle\Model\UserManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * @Route("/auth")
 */
class ApiAuthController extends AbstractController
{
    /**
     * @Route("/register", name="api_auth_register",  methods={"POST"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Create a new user player and redirect to login endpoint"
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     description="Send parameters in body with a JSON format like :",
     *
     *     @SWG\Schema(
     *     type="object",
     *     example={"username": "john", "password": "doe", "email":"johnDoe@gmail.com"},
     *         @SWG\Property(property="username", type="string", minLength=3),
     *         @SWG\Property(property="password", type="string", minLength=3),
     *         @SWG\Property(property="email", type="email")
     *     )
     * )
     * @SWG\Tag(name="Register")
     * @Security(name="Bearer")
     *
     * @param Request $request
     * @param UserManagerInterface $userManager
     * @return JsonResponse|RedirectResponse
     */
    public function register(Request $request, UserManagerInterface $userManager)
    {
        $data = json_decode(
            $request->getContent(),
            true
        );

        if (empty($data)) {

            $data = [
                "message" => "Please do not forget to send the body request",
                "links" => [
                    "href" => "http://localhost:8000/api/doc",
                ],
            ];

            return new JsonResponse($data, Response::HTTP_BAD_REQUEST);
        }

        $validator = Validation::createValidator();

        $constraint = new Collection(array(
            'username' => [new Length(['min' => 3]), new NotBlank(), new NotNull()],
            'password' => [new Length(['min' => 3]), new NotBlank(), new NotNull()],
            'email' => [new Email(), new NotBlank(), new NotNull()],
        ));

        $violations = $validator->validate($data, $constraint);

        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string)$violations], Response::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $user
            ->setUsername($data['username'])
            ->setPlainPassword($data['password'])
            ->setEmail($data['email'])
            ->setEnabled(true)
            ->setRoles(['ROLE_PLAYER'])
            ->setSuperAdmin(false);

        try {

            $userManager->updateUser($user);

        } catch (Exception $e) {
            $data = [
                "message" => "Player is already registered, use the following link :",
                "links" => [
                    "href" => "http://localhost:8000/api/auth/login",
                    "rel" => "login",
                    "type" => "POST",
                ],
            ];

            return new JsonResponse($data, 500);
        }

        # Code 307 preserves the request method, while redirectToRoute() is a shortcut method.
        return $this->redirectToRoute('api_auth_login', [
            'username' => $data['username'],
            'password' => $data['password'],
        ], 307);
    }

    /**
     * @Route("/login", name="api_auth_login",  methods={"POST"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Log into the application and receive a new JWT"
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     description="Send parameters in body with a JSON format like :",
     *
     *     @SWG\Schema(
     *     type="object",
     *     example={"username": "john", "password": "doe", "email":"johnDoe@gmail.com"},
     *         @SWG\Property(property="username", type="string"),
     *         @SWG\Property(property="password", type="string"),
     *         @SWG\Property(property="email", type="string")
     *     )
     * )
     * @SWG\Tag(name="Login")
     * @Security(name="Bearer")
     *
     * @param Request $request
     * @param UserManagerInterface $userManager
     * @return JsonResponse|RedirectResponse
     */
    public function login(Request $request, UserManagerInterface $userManager)
    {
        // todo hack to show the doc of login but it doesn't go here
        // todo + fix when nothing is send in body, loop
    }
}
