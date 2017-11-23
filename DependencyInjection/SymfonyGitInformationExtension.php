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
	/**
	 * {@inheritdoc}
	 */
	public function load(array $configs, ContainerBuilder $container)
	{
		$configuration = new Configuration();
		$config = $this->processConfiguration($configuration, $configs);

		$loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
		$loader->load('container.yml');

        if (!empty($config['max_commits'])) {
            $container->setParameter('symfony_git_information.max_commits', $config['max_commits']);
        } else {
            $container->setParameter('symfony_git_information.max_commits', 10);
        }

        if (!empty($config['repository_name'])) {
            $container->setParameter('symfony_git_information.repository_name', $config['repository_name']);
        } else {
            $container->setParameter('symfony_git_information.repository_name', "Repository");
        }

        if (!empty($config['repository_url'])) {
            $container->setParameter('symfony_git_information.repository_url', $config['repository_url']);
        } else {
            $container->setParameter('symfony_git_information.repository_url', null);
        }

        if (!empty($config['repository_commit_url'])) {
            $container->setParameter('symfony_git_information.repository_commit_url', $config['repository_commit_url']);
        } else {
            $container->setParameter('symfony_git_information.repository_commit_url', null);
        }

        if (!empty($config['repository_branch_url'])) {
            $container->setParameter('symfony_git_information.repository_branch_url', $config['repository_branch_url']);
        } else {
            $container->setParameter('symfony_git_information.repository_branch_url', null);
        }
	}
}
