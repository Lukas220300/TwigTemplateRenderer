<?php

namespace SCHOENBECK\Webservices;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class TwigTemplateRender
{
    /**
     * @var Environment
     */
    private $twigEnviorment;

    /**
     * TemplateRender constructor.
     * @param string $templateRootPath
     */
    public function __construct($templateRootPath = './')
    {
        $loader = new FilesystemLoader($templateRootPath);
        $this->twigEnviorment = new Environment($loader);
    }

    /**
     * @param string $templateName
     * @param array $context
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderTemplate($templateName = 'index.twig', $context = [])
    {
        $template = $this->twigEnviorment->load($templateName);
        return $template->render($context);
    }

}
