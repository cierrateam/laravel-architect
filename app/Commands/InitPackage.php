<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use LaravelZero\Framework\Commands\Command;
use function ucfirst;

class InitPackage extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'init';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a fresh package';

    protected $package_name;

    protected $creator;

    protected $full_directory;

    protected $author;

    protected $author_mail;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $creator = $this->getCreator();
        $package_name = $this->getPackageName();
        $this->getAuthorName();
        $this->getAuthorMail();
        $directory = 'packages' . DIRECTORY_SEPARATOR . $creator . DIRECTORY_SEPARATOR . $package_name;
        $full_directory = getcwd() . DIRECTORY_SEPARATOR . $directory;
        $this->full_directory = getcwd() . DIRECTORY_SEPARATOR . $directory;
        if ($this->confirm('Do you wish to create the directory ' . $directory . '?', 'yes')) {
            $this->task("Creating " . $directory, function () use ($full_directory) {

                // exit if directory exists
                if (is_dir($full_directory)) {
                    $this->error($full_directory . ' already exists!');
                    return false;
                }
                // try to create the directory, failing if not successful
                try {
                    return mkdir($full_directory, 0775, true);
                } catch (\Exception $exception) {
                    return false;
                }
            });
        } else {
            // if he don't want to create, does he want to work in current directory instead?
            if ($this->confirm('So you want to continue in current directory?')) {
                $full_directory = getcwd();
            } else {
                $this->info('Ok. Bye bye 👋🏼');
                return true; // end here, no work to do.
            }
        }


        $this->createPackageBoilerplate($full_directory);


    }

    private function createPackageBoilerplate(string $full_directory)
    {
        $context = $this;
        $this->info('Installing boilerplate.');
        $this->task('Creating src directory', function () use ($full_directory) {
            return mkdir($full_directory . DIRECTORY_SEPARATOR . 'src');
        });
        $this->task('Creating Service Provider', function () use ($context) {
            return $context->createServiceProvider();
        });
        $this->task('Creating config file', function () use ($context) {
            return $context->createConfig();
        });
        $this->task('Creating the composer file for you', function () use($context) {
            return $context->createComposerFile();
        });
    }

    /**
     * @return mixed
     */
    public function getCreator()
    {
        return $this->getRememberable('creator', 'Who is the whats the Namespace of the package?');
    }

    /**
     * @return mixed
     */
    public function getPackageName()
    {
        $package_name = $this->ask('Whats the name of the package?');
        $this->package_name = $package_name;
        return $package_name;
    }

    /**
     * @return mixed
     */
    public function getAuthorName()
    {
        return $this->getRememberable('author', 'Who is the author of the package?');
    }

    /**
     * @return mixed
     */
    public function getAuthorMail()
    {
        return $this->getRememberable('author_mail', 'Whats the mail of the author?');
    }

    public function createServiceProvider()
    {
        $content = $this->generateFileContents('provider');
        return file_put_contents($this->getSrcDir() . ucfirst($this->package_name) . 'ServiceProvider.php', $content);
    }

    public function createConfig()
    {
        $content = $this->generateFileContents('config');
        $confdir = $this->getSrcDir() . 'Config';
        try {
            mkdir($confdir, 0755, true);
        } catch (\Exception $exception) {
            return false;
        }
        return file_put_contents($confdir . DIRECTORY_SEPARATOR . $this->package_name . '.php', $content);
    }

    /**
     * @return string
     */
    public function getStubDir(): string
    {
        $baseDir = __DIR__ . DIRECTORY_SEPARATOR .
            '..' . DIRECTORY_SEPARATOR .
            '..' . DIRECTORY_SEPARATOR .
            'stubs' . DIRECTORY_SEPARATOR .
            'clean-package';
        return $baseDir;
    }

    /**
     * @param string $stubName
     * @return false|string|string[]
     */
    public function generateFileContents(string $stubName)
    {
        $stubDir = $this->getStubDir();
        $content = file_get_contents($stubDir . DIRECTORY_SEPARATOR . $stubName . '.stub');
        $content = str_replace('{{ NAMESPACE }}', $this->getPackageNamespace(), $content);
        $content = str_replace('{{ UCNAME }}', $this->getUcPackageName(), $content);
        $content = str_replace('{{ NAME }}', $this->package_name, $content);
        return $content;
    }

    private function getSrcDir()
    {
        return $this->full_directory . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
    }

    /**
     * @param string $storeKey
     * @param string $question
     * @return mixed
     */
    public function getRememberable(string $storeKey, string $question)
    {
        $suggestion = Storage::exists($storeKey . '.txt') ? Storage::get($storeKey . '.txt') : null;
        $answer = $this->ask($question, $suggestion);
        Storage::put($storeKey . '.txt', $answer);
        $this->$storeKey = $answer;
        return $answer;
    }

    private function getBaseDir()
    {
        return $this->full_directory . DIRECTORY_SEPARATOR;
    }

    public function getPackageNamespace()
    {
        return ucfirst($this->creator) . '\\' . ucfirst($this->package_name);
    }

    public function getUcPackageName()
    {
        return ucfirst($this->package_name);
    }

    public function createComposerFile()
    {
        return file_put_contents($this->getBaseDir() . 'composer.json', $this->getComposerFileContent());
    }

    public function getComposerFileContent()
    {
        return Collection::make([
            'name' => $this->package_name,
            'description' => 'Another awesome laravel package',
            'license' => 'MIT',
            'type' => 'library',
            'require' => [
                'php' => ">=7.2",
                'illuminate/support' => '7.*'
            ],
            'authors' => [[
                'name' => $this->author,
                'email' => $this->author_mail
            ]],
            'autoload' => [
                'psr-4' => [
                    $this->getPackageNamespace() . '\\' => 'src/',
                ]
            ],
            'extra' => [
                'laravel' => [
                    'providers' => [
                        $this->getPackageNamespace() . '\\' . ucfirst($this->package_name) . 'ServiceProvider'
                    ]
                ]
            ],
            'minimum-stability' => "dev",
        ])->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

}
