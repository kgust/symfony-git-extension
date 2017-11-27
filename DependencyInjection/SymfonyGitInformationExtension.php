<?php

namespace Jbtcd\SymfonyGitInformation\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class SymfonyGitInformationExtension
 */
class SymfonyGitInformationExtension extends Extension
{
    /** @var array */
    private $config;

	/**
	 * {@inheritdoc}
	 */
	public function load(array $configs, ContainerBuilder $container)
	{
		$configuration = new Configuration();
		$this->config = $this->processConfiguration($configuration, $configs);

		$loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
		$loader->load('container.yml');

        $container->setParameter('symfony_git_information.max_commits', $this->getConfigParameter('max_commits', 10));

        $container->setParameter('symfony_git_information.repository_name', $this->getConfigParameter('repository_name', 'Repository'));

        $container->setParameter('symfony_git_information.repository_url', $this->getConfigParameter('repository_url'));

        $container->setParameter('symfony_git_information.repository_commit_url', $this->getConfigParameter('repository_commit_url'));

        $container->setParameter('symfony_git_information.repository_branch_url', $this->getConfigParameter('repository_branch_url'));

        $container->setParameter('symfony_git_information.commit_id_length', $this->getConfigParameter('commit_id_length', 7));
	}

    /**
     * @param string $parameter
     * @param mixed|null $default
     *
     * @return mixed|null
     */
	private function getConfigParameter($parameter, $default = null)
    {
        if (!empty($this->config[$parameter])) {
            return $this->config[$parameter];
        }

        return null;
    }
}
