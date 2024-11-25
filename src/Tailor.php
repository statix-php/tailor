<?php

namespace Statix\Tailor;

class Tailor
{
    private static ?Tailor $instance = null;

    protected bool $usingTailwindMerge = false;

    /**
     * The components that have been registered with the Tailor.
     *
     * @var VariantsManager[]
     */
    private array $components = [];

    public static function getInstance(): Tailor
    {
        if (self::$instance === null) {
            self::$instance = new Tailor;
        }

        return self::$instance;
    }

    public function usingTailwindMerge(): bool
    {
        return $this->usingTailwindMerge;
    }

    public function enableTailwindMerge(bool $state = true): static
    {
        $this->usingTailwindMerge = $state;

        if (!class_exists(\TailwindMerge\TailwindMerge::class)) {
            throw new \Exception(
                'TailwindMerge is not installed. Please run `composer require gehrisandro/tailwind-merge-php`'
            );
        }

        return $this;
    }

    public function make(string $name, bool $overide = true): VariantsManager
    {
        if (! $this->has($name)) {
            $this->components[$name] = new VariantsManager($name);
        } elseif ($overide) {
            $this->components[$name] = new VariantsManager($name);
        }

        return $this->get($name);
    }

    public function get(string $name): VariantsManager
    {
        /** @var VariantsManager */
        $manager = $this->components[$name];

        return $manager;
    }

    /**
     * Check if a component has been registered with Tailor.
     */
    public function has(string $name): bool
    {
        return isset($this->components[$name]);
    }

    /**
     * Get all components that been registered with Tailor.
     *
     * @return VariantsManager[]
     */
    public function components(): array
    {
        return $this->components;
    }
}
