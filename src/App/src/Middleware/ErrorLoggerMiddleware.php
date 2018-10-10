<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Diactoros\Response\TextResponse;
use Zend\Diactoros\Response\HtmlResponse;

class ErrorLoggerMiddleware implements MiddlewareInterface
{
    
    const ERROR_MESSAGE_FORMAT = "=======\n[%d] %s\n=======\n%s\n=======\n";
    
    /**
     * @var bool $isDebugMode
     */
    private $isDebugMode;
    
    /**
     * @var TemplateRendererInterface $templateRenderer
     */
    private $templateRenderer;
    
    public function __construct(TemplateRendererInterface $templateRenderer, $isDebugMode)
    {
        $this->templateRenderer = $templateRenderer;
        $this->isDebugMode = $isDebugMode;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {

        try {
            $response = $handler->handle($request);            
        } catch (\Throwable $e) {
            $error = $this->formatErrorMessageToString($e);
            if ($this->isDebugMode) {
                $response = new TextResponse($error, 500);
            } else {
                $this->toStdOut($error);
                $response = new HtmlResponse($this->templateRenderer->render('error::error'));
            }
        }
        
        return $response;        
    }
    
    
    private function formatErrorMessageToString($e) : string
    {
        return sprintf(
            self::ERROR_MESSAGE_FORMAT,
            $e->getCode(),
            $e->getMessage(),
            $e->getTraceAsString()
        );
    }
    
    private function toStdOut($errorText) : void
    {
        file_put_contents("/tmp/stdout", $errorText);
    }
    
}
