<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Foundation\Console\ConsoleMakeCommand;
use Illuminate\Support\Str;
use function ucfirst;

final class MakeCommand extends ConsoleMakeCommand
{
    /**
     * {@inheritdoc}
     */
    protected $description = 'Create a new command for your package';

    /**
     * {@inheritdoc}
     */
    protected function getNameInput(): string
    {
        return ucfirst(parent::getNameInput());
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..';
//        if ($this->option('unit')) {
//            return $dir.'/stubs/unit-test.stub';
//        }

        return $dir. DIRECTORY_SEPARATOR .'stubs'. DIRECTORY_SEPARATOR .'command.stub';
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace;
    }


    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        $dir = getcwd(). DIRECTORY_SEPARATOR .'src'. DIRECTORY_SEPARATOR .'Commands'. DIRECTORY_SEPARATOR;
        if(!is_dir($dir)) {
            mkdir($dir);
        }

        return $dir.str_replace('\\', '/', $name).'.php';
    }

}
