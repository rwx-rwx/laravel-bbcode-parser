<?php

namespace Rwxrwx\BBCode;

class BBParser
{ 
    /**
     * Enabled bbcodes for parse.
     *
     * @var array
     */
    private array $enabledBBCodes;

    /**
     * Create parser instance.
     *
     * @param array $bbcodes
     */
    public function __construct(public array $bbcodes)
    {
        $this->enabledBBCodes = $this->bbcodes;
    }

    /**
     * Parses the BBCode string
     *
     * @param  string  $source
     * @param  bool  $caseInsensitive
     * @return string
     */
    public function parse(string $source, bool $caseInsensitive = false) : string
    {
        foreach ($this->enabledBBCodes as $name => $parser) {
            $pattern = ($caseInsensitive) ? $parser['pattern'] . 'i' : $parser['pattern'];

            $source = $this->searchAndReplace($pattern, $parser['replace'], $source);
        }

        return $source;
    }

    /**
     * strip all BBCode tags
     *
     * @param  string  $source
     * @return string
     */
    public function stripTags(string $source) : string
    {
        foreach ($this->bbcodes as $name => $parser) {
            $source = $this->searchAndReplace($parser['pattern'] . 'i', $parser['content'], $source);
        }

        return $source;
    }

    /**
     * Searches after a specified pattern and replaces it with provided structure
     *
     * @param  string  $pattern
     * @param  string  $replace
     * @param  string  $source
     * @return string
     */
    protected function searchAndReplace(string $pattern, string $replace, string $source) : string
    {
        while (preg_match($pattern, $source)) {
            $source = preg_replace($pattern, $replace, $source);
        }

        return $source;
    }

    /**
     * Helper function to parse case sensitive
     *
     * @param  string  $source
     * @return string
     */
    public function parseCaseSensitive(string $source) : string
    {
        return $this->parse($source, false);
    }

    /**
     * Helper function to parse case insensitive
     *
     * @param  string  $source
     * @return string
     */
    public function parseCaseInsensitive(string $source) : string
    {
        return $this->parse($source, true);
    }

    /**
     * Limits the parsers to only those you specify
     *
     * @param  string|array  $tag
     * @return self
     */
    public function only(string|array $tag) : self
    {
        $only = (is_array($tag)) ? $tag : func_get_args();
        $this->enabledBBCodes = array_intersect_key($this->bbcodes, array_flip((array) $only));

        return $this;
    }

    /**
     * Parse all tags except 
     *
     * @param  string|array  $except
     * @return self
     */
    public function except(string|array $tag) : self
    {
        $except = (is_array($tag)) ? $tag : func_get_args();

        $this->enabledBBCodes = array_diff_key($this->bbcodes, array_flip((array) $except));

        return $this;
    }

    /**
     * List of chosen parsers
     *
     * @return array
     */
    public function getBBCodes() : array
    {
        return $this->enabledBBCodes;
    }

    /**
     * Sets the parser pattern and replace.
     * This can be used for new parsers or overwriting existing ones.
     *
     * @param  string  $name
     * @param  string  $pattern
     * @param  string  $replace
     * @param  string  $content
     * @return self
     */
    public function addTag(string $name, string $search, string $replace, string $content) : self
    {
        $this->bbcodes[$name] =compact('search', 'replace', 'content');
        $this->enabledBBCodes[$name] = $this->bbcodes[$name];

        return $this;
    }
}
