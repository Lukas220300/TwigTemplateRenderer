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
     * @var FilesystemLoader
     */
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

    /**
     * Add a directory where a twig template is located. You can optional add a namespace for the given directory.
     * 
     * @param string $directoryPath
     * @param string $nameSpace
     */
    public function addTemplateDirectory(string $directoryPath, string $nameSpace = "main")
    {
        if("main" === $nameSpace || null === $nameSpace) {
            $this->templateLoader->addPath($directoryPath);
        } else {
            $this->templateLoader->addPath($directoryPath, $nameSpace);
        }
    }

    /**
     * Add a array of directories where templates are located.
     * 
     * @param array $paths
     */
    public function addTemplateDirectories(array $paths) 
    {
        foreach ($paths as $path) {
            $this->addTemplateDirectory($path);
        }
    }

    /**
     * Add a array of directories and there namespaces.
     * Should look like this:
     * 
     * ['./templates' => 'root', './nextTemplates' => 'project']
     * 
     * @param array $pathNameSpace
     */
    public function addTemplateDirectoriesWithNameSpaces(array $pathNameSpace) 
    {
        foreach ($pathNameSpace as $path => $nameSpace) {
            $this->addTemplateDirectory($path, $nameSpace);
        }
    }

    /**
     * Add a directory recursive.
     * 
     * @param string $rootPath
     */
    public function addTemplateRootDirectory(string $rootPath)
    {
        $this->scanDirectory($rootPath); //TODO: refactor recursive function to only load a directory if a twig template is in it.
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
