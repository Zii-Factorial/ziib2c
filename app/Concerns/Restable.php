<?php

namespace App\Concerns;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

trait Restable
{
    protected int $statusCode = HttpResponse::HTTP_OK;

    /**
     * Will return a response with message
     *
     * @param  array  $data  The given data
     * @param  array  $headers  The given headers
     * @return JsonResponse The JSON-response
     */
    public function respondWithMessage(array|string $message, array $headers = []): JsonResponse
    {
        if (is_array($message)) {
            $message = Arr::get($message, 'message');
        }

        return $this->respond([
            'message' => $message,
            'status_code' => $this->getStatusCode(),
        ], $headers);
    }

    /**
     * Will return a response.
     *
     * @param  array  $data  The given data
     * @param  array  $headers  The given headers
     * @return JsonResponse The JSON-response
     */
    public function respond(mixed $data, array $headers = []): JsonResponse
    {
        return Response::json($data, $this->getStatusCode(), $headers);
    }

    /**
     * Getter for the status code.
     *
     * @return int The status code
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Setter for the status code.
     *
     * @param  int  $statusCode  The given status code
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * Will result in a 200 code.
     *
     * @param  string  $message  The given message
     * @param  array  $headers  The headers that should be sent with the JSON response
     * @return JsonResponse The JSON-response with the message
     */
    protected function respondSuccess(string $message, array $headers = []): JsonResponse
    {
        $this->setStatusCode(HttpResponse::HTTP_OK);

        return $this->respondWithMessage($message, $headers);
    }

    /**
     * Will result in a 400 error code.
     *
     * @param  string  $message  The given message
     * @param  array  $headers  The headers that should be sent with the JSON response
     * @return JsonResponse The JSON-response with the error code
     */
    protected function respondBadRequest(string $message = 'Bad request', array $headers = []): JsonResponse
    {
        $this->setStatusCode(HttpResponse::HTTP_BAD_REQUEST);

        return $this->respondWithMessage($message, $headers);
    }

    /**
     * Will result in a 405 error code.
     *
     * @return JsonResponse The JSON-response with the error code
     */
    protected function respondMethodNotAllowed(): JsonResponse
    {
        $this->setStatusCode(HttpResponse::HTTP_METHOD_NOT_ALLOWED);

        return $this->respondWithMessage('Method not allowed');
    }

    /**
     * Will result in a 401 error code.
     *
     * @param  string|null  $message  The given message
     * @param  array  $headers  The headers that should be sent with the JSON response
     * @return JsonResponse The JSON-response with the error code
     */
    protected function respondUnauthorized(?string $message = null, array $headers = []): JsonResponse
    {
        $this->setStatusCode(HttpResponse::HTTP_UNAUTHORIZED);

        return $this->respondWithMessage($message ?: __('auth.token.expired.title'), $headers);
    }

    /**
     * Will result in a 403 error code.
     *
     * @param  string  $message  The given message
     * @param  array  $headers  The headers that should be sent with the JSON response
     * @return JsonResponse The JSON-response with the error message
     */
    protected function respondForbidden(string $message = 'Forbidden', array $headers = []): JsonResponse
    {
        $this->setStatusCode(HttpResponse::HTTP_FORBIDDEN);

        return $this->respondWithMessage($message, $headers);
    }

    /**
     * Will result in a 404 error code.
     *
     * @param  string  $message  The given message
     * @return JsonResponse The JSON-response with the error message
     */
    protected function respondNotFound(string $message = 'Not found'): JsonResponse
    {
        $this->setStatusCode(HttpResponse::HTTP_NOT_FOUND);

        return $this->respondWithMessage($message);
    }

    /**
     * Will result in a 405 error code.
     *
     * @param  string  $message  The given message
     * @param  array  $headers  The headers that should be sent with the JSON response
     * @return JsonResponse The JSON-response with the error message
     */
    protected function respondNotAllowed(string $message = 'Method not allowed', array $headers = []): JsonResponse
    {
        $this->setStatusCode(HttpResponse::HTTP_METHOD_NOT_ALLOWED);

        return $this->respondWithMessage($message, $headers);
    }

    /**
     * Will result in a 422 error code.
     *
     * @param  string  $message  The given message
     * @param  array  $headers  The headers that should be sent with the JSON response
     * @return JsonResponse The JSON-response with the error code
     */
    protected function respondUnprocessableEntity(string $message = 'Unprocessable', array $headers = []): JsonResponse
    {
        $this->setStatusCode(HttpResponse::HTTP_UNPROCESSABLE_ENTITY);

        return $this->respondWithMessage($message, $headers);
    }

    /**
     * Will result in a 429 error code.
     *
     * @param  string  $message  The given message
     * @param  array  $headers  The headers that should be sent with the JSON response
     * @return JsonResponse The JSON-response with the error message
     */
    protected function respondTooManyRequests(string $message = 'Too many requests', array $headers = []): JsonResponse
    {
        $this->setStatusCode(HttpResponse::HTTP_TOO_MANY_REQUESTS);

        return $this->respondWithMessage($message, $headers);
    }

    /**
     * Will result in a 500 error code.
     *
     * @param  string  $message  The given message
     * @param  array  $headers  The headers that should be sent with the JSON response
     * @return JsonResponse The JSON-response with the error message
     */
    protected function respondInternalError(string $message = 'Internal Server Error', array $headers = []): JsonResponse
    {
        $this->setStatusCode(HttpResponse::HTTP_INTERNAL_SERVER_ERROR);

        return $this->respondWithMessage($message, $headers);
    }
}
