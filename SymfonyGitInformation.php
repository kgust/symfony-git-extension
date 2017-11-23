<?php

namespace Jbtcd\SymfonyGitInformation;

use Jbtcd\SymfonyGitInformation\DependencyInjection\SymfonyGitInformationExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SymfonyGitInformation extends Bundle
{
    public function getContainerExtension()
    {
        return new SymfonyGitInformationExtension();
    }
}