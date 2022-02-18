<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\User;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class QuestionController
 * @package App\Controller
 * @Route("/questions")
 */
class QuestionController
{
    /**
     * @Route(name="api_questions_collection_get", methods={"GET"})
     * @param QuestionRepository $questionRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function collection(QuestionRepository $questionRepository, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($questionRepository->findAll(), "json", ["groups" => "get"]),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_questions_item_get", methods={"GET"})
     * @param Question $question
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function item(Question $question, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($question, "json", ["groups" => "get"]),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route(name="api_questions_collection_post", methods={"POST"})
     * @param Question $question
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @param UrlGeneratorInterface $urlGenerator
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function post(
        Question $question,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator,
        ValidatorInterface $validator
    ): JsonResponse {
        $question->setAuthor($entityManager->getRepository(User::class)->findOneBy([]));

        $errors = $validator->validate($question);

        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $entityManager->persist($question);
        $entityManager->flush();

        return new JsonResponse(
            $serializer->serialize($question, "json", ["groups" => "get"]),
            JsonResponse::HTTP_CREATED,
            ["Location" => $urlGenerator->generate("api_questions_item_get", ["id" => $question->getId()])],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_questions_item_put", methods={"PUT"})
     * @param Question $question
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function put(
        Question $question,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ): JsonResponse {
        $errors = $validator->validate($question);

        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $entityManager->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/{id}", name="api_questions_item_delete", methods={"DELETE"})
     * @param Question $question
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function delete(
        Question $question,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $entityManager->remove($question);
        $entityManager->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}