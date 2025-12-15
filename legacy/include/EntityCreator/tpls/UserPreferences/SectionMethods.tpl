{$start_sectionmethods}
{literal}
    public function getContentsAsArray(): array
    {
        if (!is_string($this->contents)) {
            return [];
        }
        
        $contents = unserialize(base64_decode($this->contents) ?: '');
        if (!is_array($contents)) {
            $contents = [];
        }
        return $contents;
    }
{/literal}
{foreach from=$additionalMethods item=method}
        {$method}
{/foreach}
{$end_sectionmethods}