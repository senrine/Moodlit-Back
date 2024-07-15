<?php

namespace App\EventListener;

use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if($exception instanceof ValidationException) {
            $errors = [];

            foreach ($exception->getViolations() as $violation) {
                $errors[$violation->getPropertyPath()][] = $violation->getMessage();
            }
            $response = new JsonResponse([
                "message" => "Validation failed",
                "errors" => $errors
            ], 400);
        }
        else {
            $response = new JsonResponse([
                "error" => $exception->getMessage()
            ], 500);
        }
        $event->setResponse($response);
    }

}