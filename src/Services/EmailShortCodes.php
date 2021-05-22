<?php

namespace TwinDots\EmailService\Services;

class EmailShortCodes
{
    /**
     * Shortcodes
     * @var array
     */
    protected array $shortcodes;

    /**
     * Shortcodes group
     * @var string
     */
    protected string $group;

    /**
     * Body to be compiled
     * @var string
     */
    protected string $body;

    /**
     * Objects used in compile
     * @var array
     */
    protected array $objects;

    /**
     * EmailShortCodes constructor.
     * @param string|null $group
     */
    public function __construct($group = '')
    {
        $this->group($group);
    }

    /**
     * Set the body.
     * @param string $body
     * @return EmailShortCodes
     */
    public function body(string $body): static
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Set the objects.
     * @param array $objects
     * @return EmailShortCodes
     */
    public function objects(array $objects = []): static
    {
        $this->objects = $objects;
        return $this;
    }

    /**
     * Set the group.
     * @param string $group
     * @return EmailShortCodes
     */
    public function group(string $group): static
    {
        $this->shortcodes = $this->getGroup($group);
        return $this;
    }

    /**
     * Add a new shortcodes group to the existing list
     * @param string $group
     * @return $this
     */
    public function addGroup(string $group) : static
    {
        $this->shortcodes += $this->getGroup($group);
        return $this;
    }

    /**
     * Auto add user group to shortcodes
     * @return $this
     */
    public function withUser(): static
    {
        $userGroup = config('email_service.user_shortcode_group');
        $this->shortcodes += $this->getGroup($userGroup);
        return $this;
    }

    /**
     * Get the group shortcodes from the config file.
     * @param string $group_slug
     * @return array
     */
    public function getGroup(string $group_slug): array
    {
        return config('email_service.shortcodes')[$group_slug] ?? [];
    }

    /**
     * Show the shortcodes list
     * @return array
     */
    public function shortcodes(): array
    {
        return $this->shortcodes;
    }

    /**
     * Compile given body using a shortcodes list and a set of given objects.
     * @return string
     */
    public function compile(): string
    {
        $compiled = $this->body;

        foreach ($this->shortcodes as $code => $options) {

            if (!isset($options['type']))
                continue;

            // Compile variable
            if ($options['type'] == 'variable') {

                if (isset($options['object'])
                    && isset($options['param'])
                    && isset($this->objects[$options['object']])
                )
                    $parameter = $this->objects[$options['object']]->{$options['param']} ?: '';

            }

            // Compile function
            if ($options['type'] == 'function') {

                if (isset($options['object'])
                    && isset($options['param'])
                    && isset($this->objects[$options['object']])
                )
                    $parameter = method_exists($this->objects[$options['object']], $options['param'])
                        ? call_user_func([$this->objects[$options['object']], $options['param']])
                        : '';
            }

            // Compile view
            if ($options['type'] == 'view') {

                if (isset($options['object'])
                    && isset($options['param']))
                    $parameter = view()->exists($options['object'])
                        ? view($options['object'])
                            ->with($this->objects)
                            ->render()
                        : '';
            }

            if (isset($parameter))
                $compiled = str_replace('{' . $code . '}', $parameter, $compiled);
        }

        return $compiled;
    }

    /**
     * Show the objects needed
     * @return array
     */
    public function objectsNeeded(): array
    {

        $shortcodes_objects = array_filter($this->shortcodes, function ($item) {
            return $item['type'] != 'view';
        });

        $shortcodes_objects = array_column($shortcodes_objects, 'object');
        $shortcodes_objects = array_unique($shortcodes_objects);
        $shortcodes_objects = array_values($shortcodes_objects);

        $views_objects = array_filter($this->shortcodes, function ($item) {
            return $item['type'] == 'view';
        });


        $words = $file_words = [];
        foreach ($views_objects as $view) {
            $path = config('email_service.view_path') . '/';

            $file = file_get_contents(resource_path($path . str_replace('.', '\\', $view['object']) . '.blade.php'));

            $regex = '~(\$\w+)~';
            if (preg_match_all($regex, $file, $matches, PREG_PATTERN_ORDER)) {
                foreach ($matches[1] as $word) {
                    $file_words[] = $word;
                }
            }

            $file_words = array_unique($file_words);
            array_push($words, [$view['object'] => $file_words]);
        }

        return [
            'Shortcode variables' => $shortcodes_objects,
            'Views variables' => $words
        ];
    }
}