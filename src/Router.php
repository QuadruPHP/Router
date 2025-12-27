<?php

namespace QuadruPHP\Router;

use QuadruPHP\Router\Exceptions\InvalidNameException;

class Router
{
    private array $collection = [];

    /**
     * Adds a new route to the collection.
     *
     * @param string $method The HTTP method associated with the route (e.g., GET, POST, etc.).
     * @param string $path The URI path of the route.
     * @param string|callable $callable The handler for the route, either as a string or a callable.
     * @param string $name An optional name for the route, defaulting to an empty string if not provided.
     * @return void
     * @throws InvalidNameException
     */
    public function add(string $method, string $path, string|callable $callable, string $name = ''): void
    {
        if (!empty($name) && $this->hasByName($name)) {
            throw new InvalidNameException('Route with name "' . $name . '" already exists.');
        }

        $this->collection[] = [
            'method' => $method,
            'path' => $path,
            'callable' => $callable,
            'name' => $name
        ];
    }

    /**
     * Searches for a route in the collection matching the specified method and path.
     *
     * @param string $method The HTTP method to match (e.g., GET, POST).
     * @param string $path The URL path to match.
     * @return array The matching route as an associative array, or an empty array if no match is found.
     */
    public function find(string $method, string $path): array
    {
        foreach ($this->collection as $route) {
            if ($route['method'] === $method && $route['path'] === $path) {
                return $route;
            }
        }

        return [];
    }

    /**
     * Checks if a route exists in the collection matching the given method and path.
     *
     * @param string $method The HTTP method of the route to check.
     * @param string $path The path of the route to check.
     * @return bool True if the route exists in the collection, otherwise false.
     */
    public function has(string $method, string $path): bool
    {
        $array = $this->find($method, $path);

        if (!empty($array)) {
            return true;
        }

        return false;
    }

    /**
     * Searches for a route in the collection by its name.
     *
     * @param string $name The name of the route to find.
     * @return array An array representing the route if found, or an empty array if no route matches the given name.
     */
    public function findByName(string $name): array
    {
        foreach ($this->collection as $route) {
            if ($route['name'] === $name) {
                return $route;
            }
        }

        return [];
    }

    public function hasByName(string $name): bool
    {
        $array = $this->findByName($name);

        if (!empty($array)) {
            return true;
        }

        return false;
    }
}
