<?php

namespace Statix\Tailor;

use Closure;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use ReflectionClass;
use ReflectionMethod;

abstract class Component implements Htmlable
{
    protected ReflectionClass $reflector;

    public function reflector()
    {
        if ($this->reflector) {
            $this->reflector = new ReflectionClass($this);
        }

        return $this->reflector;
    }

    public function extractPublicMethods()
    {
        $methods = $this->reflector()->getMethods(ReflectionMethod::IS_PUBLIC);

        $publicMethods = [];

        foreach ($methods as $method) {
            $publicMethods[$method->getName()] = Closure::fromCallable([$this, $method->getName()]);
        }

        return $publicMethods;
    }

    public function getViewVariables(): array
    {
        return $this->extractPublicMethods();
    }

    abstract public function render(): View;

    public function toHtml()
    {
        return $this->render()->render();
    }
}
