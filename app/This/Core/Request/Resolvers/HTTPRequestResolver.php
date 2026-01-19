<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Core\Request\Resolvers;

use App\This\Core\Request\Request;
use This\Contracts\RequestInterface;
use This\Contracts\RequestMethodsEnum;
use This\Contracts\RequestResolverInterface;

final readonly class HTTPRequestResolver implements RequestResolverInterface
{
    public function supports(): bool
    {
        return PHP_SAPI !== 'cli' && PHP_SAPI !== 'phpdbg';
    }

    public function resolve(): RequestInterface
    {
        return (new Request())
            ->setMethod(
            !empty($_SERVER['REQUEST_METHOD'])
                ? RequestMethodsEnum::from(strtoupper($_SERVER['REQUEST_METHOD']))
                : null
            )
            ->setUri($_SERVER['REQUEST_URI'])
            ->setBody(file_get_contents('php://input'))
            ->setHeaders(headers_list())
            ->setScheme($_SERVER['REQUEST_SCHEME'])
            ->setHost($_SERVER['HTTP_HOST'])
            ->setPort((int) $_SERVER['SERVER_PORT'])
            ->setPath(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))
            ->setQueryString($_SERVER['QUERY_STRING'])
            ->setGet($_GET)
            ->setPost($_POST)
            ->setFiles($_FILES)
            ->setCookies($_COOKIE)
            ->setServer($_SERVER)
        ;
    }
}
