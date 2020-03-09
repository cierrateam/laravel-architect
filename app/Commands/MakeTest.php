<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Foundation\Console\TestMakeCommand;
use Illuminate\Support\Str;
use function ucfirst;

final class MakeTest extends TestMakeCommand
{
    /**
     * {@inheritdoc}
     */
    protected $description = 'Create a new test for your package';

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

        return $dir.'/stubs/test.stub';
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

        return getcwd().'/tests'.str_replace('\\', '/', $name).'.php';
    }

}
