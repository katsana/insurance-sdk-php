<?php

namespace Katsana\Insurance;

use BadMethodCallException;

/**
 * @method $this includes(array|string $includes)
 * @method $this excludes(array $excludes)
 * @method $this with(string $name, mixed $value)
 * @method $this forPage(int|null $page = null)
 * @method $this take(int|null $perPage = null)
 */
class Query
{
    /**
     * Set requested page.
     *
     * @var int|null
     */
    protected $page;

    /**
     * Per page limit.
     *
     * @var int|null
     */
    protected $perPage;

    /**
     * Includes data.
     *
     * @var array
     */
    protected $includes = [];

    /**
     * Excludes data.
     *
     * @var array
     */
    protected $excludes = [];

    /**
     * Custom data.
     *
     * @var array
     */
    protected $customs = [];

    /**
     * The methods that should be accessed using magic method.
     *
     * @var array
     */
    protected $passthru = [
        'includes', 'excludes', 'with', 'forPage', 'take',
    ];

    /**
     * Set includes data.
     *
     * @param array|string $includes
     *
     * @return $this
     */
    protected function includes($includes)
    {
        $includes = \is_array($includes) ? $includes : \func_get_args();

        $this->includes = $includes;

        return $this;
    }

    /**
     * Set excludes data.
     *
     * @param array|string $excludes
     *
     * @return $this
     */
    protected function excludes($excludes)
    {
        $excludes = \is_array($excludes) ? $excludes : \func_get_args();

        $this->excludes = $excludes;

        return $this;
    }

    /**
     * Set custom data.
     *
     * @param mixed $value
     *
     * @return $this
     */
    protected function with(string $name, $value)
    {
        $this->customs[$name] = $value;

        return $this;
    }

    /**
     * Set current page.
     *
     * @return $this
     */
    protected function forPage(?int $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Set per page limit.
     *
     * @return $this
     */
    protected function take(?int $perPage = null)
    {
        $this->perPage = $perPage;

        return $this;
    }

    /**
     * Build query string.
     */
    public function toArray(): array
    {
        return $this->build(function ($data, $customs) {
            return \array_merge($customs, $data);
        });
    }

    /**
     * Build query string.
     */
    public function build(callable $callback): array
    {
        $data = [];

        foreach (['includes', 'excludes'] as $key) {
            if (! empty($this->{$key})) {
                $data[$key] = \implode(',', $this->{$key});
            }
        }

        if (\is_int($this->page) && $this->page > 0) {
            $data['page'] = $this->page;

            if (\is_int($this->perPage) && $this->perPage > 5) {
                $data['per_page'] = $this->perPage;
            }
        }

        return \call_user_func($callback, $data, $this->customs);
    }

    /**
     * Handle dynamic method calls into the model.
     *
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        if (\in_array($method, $this->passthru)) {
            return $this->$method(...$parameters);
        }

        throw new BadMethodCallException(__CLASS__."::{$method}() method doesn't exist!");
    }

    /**
     * Handle dynamic static method calls into the method.
     *
     * @return mixed
     */
    public static function __callStatic(string $method, array $parameters)
    {
        return (new static())->$method(...$parameters);
    }
}
