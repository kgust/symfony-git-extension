Symfony Git Information
=========================

### Git Symfony Toolbar
![GitSymfonyToolbar](git-extension-toolbar.png "GitSymfonyToolbar")

### Git Symfony Profiler
![GitSymfonyProfiler](git-extension.png "GitSymfonyProfilerExtension")

### Install it with Composer

    composer require --dev jbtcd/symfony-git-information
    
### Note

This plugin makes Symfony significantly slower.
Approximately 10 ms per commit to be read. By default, this means about 100 ms.

### Register into AppKernel

app/AppKernel.php :
```php
    if (in_array($this->getEnvironment(), array('dev', 'test'))) {
        ...
        $bundles[] = new Jbtcd\SymfonyGitInformation\SymfonyGitInformation();
    }
```

### Optional Parameters

app/config/config_dev.yml :
```yaml
    symfony_git_information:
        max_commits: 10
        commit_id_length: 7
        repository_name: "Repository"
        repository_url: ""
        repository_commit_url: ""
        repository_branch_url: ""
```