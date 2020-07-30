<?php

namespace SCHOENBECK\Webservices;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class TwigTemplateRender
{

    protected $templateLoader;

    /**
     * @var Environment
     */
    private $twigEnvironment;

    /**
     * TemplateRender constructor.
     * @param string $templateRootPath
     */
    public function __construct($twigEnvironmentOptions = null)
    {
        $this->templateLoader = new FilesystemLoader();
        $this->twigEnvironment = new Environment($this->templateLoader);
        if(null !== $twigEnvironmentOptions) {
            $this->twigEnvironment = new Environment($this->templateLoader, $twigEnvironmentOptions);
        }
    }

    public function addTemplateDirectory(string $directoryPath, string $nameSpace = "main")
    {
        if("main" === $nameSpace || null === $nameSpace) {
            $this->templateLoader->addPath($directoryPath);
        } else {
            $this->templateLoader->addPath($directoryPath, $nameSpace);
        }
    }

    public function addTemplateDirectories(array $paths) 
    {
        foreach ($paths as $path) {
            $this->addTemplateDirectory($path);
        }
    }

    public function addTemplateDirectoriesWithNameSpaces(array $pathNameSpace) 
    {
        foreach ($pathNameSpace as $path => $nameSpace) {
            $this->addTemplateDirectory($path, $nameSpace);
        }
    }

    public function addTemplateRootDirectory(string $rootPath)
    {
        $this->scanDirectory($rootPath);
    }

    /**
     * @param string $templateName
     * @param array $context
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderTemplate($templateName, $context = [])
    {
        return $this->twigEnvironment->load($templateName)->render($context);
    }
    
    protected function scanDirectory($directory)
    {
        $this->addTemplateDirectory($directory);
        $scanDir = scandir($directory);
        foreach($scanDir as $key => $value) {
            if(!in_array($value, ['.','..'])) {
                if(is_dir($directory . DIRECTORY_SEPARATOR . $value)) {
                    $this->scanDirectory($directory . DIRECTORY_SEPARATOR . $value);
                } 
            }
        }
    }

}
