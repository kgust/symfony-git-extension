<?php

namespace Jbtcd\SymfonyGitInformation\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 */
class Configuration implements ConfigurationInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function getConfigTreeBuilder()
	{
		$treeBuilder = new TreeBuilder();
		$rootNode = $treeBuilder->root('symfony_git_information');

		$rootNode->children()->scalarNode('max_commits')->end();
        $rootNode->children()->scalarNode('repository_name')->end();
        $rootNode->children()->scalarNode('repository_url')->end();
        $rootNode->children()->scalarNode('repository_commit_url')->end();
        $rootNode->children()->scalarNode('repository_branch_url')->end();
        $rootNode->children()->scalarNode('commit_id_length')->end();

		return $treeBuilder;
	}
}
