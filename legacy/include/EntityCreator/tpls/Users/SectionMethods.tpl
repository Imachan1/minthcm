{$start_sectionmethods}
    public function __construct()
    {ldelim}
{foreach from=$constructorFields item=constructedField}
        {$constructedField}
{/foreach}
    {rdelim}
{literal}
    public function getIdentifier(): string
    {
        return $this->id;
    }
{/literal}
{literal}
    /**
    * Get the fullname 
    *
    * @return string
    */
    public function getFullName(): string
    {
        $names = [];
        if (!empty($this->first_name)) {
            $names[] = $this->first_name;
        }
        if (!empty($this->last_name)) {
            $names[] = $this->last_name;
        }

        return !empty($names) ? implode(' ', $names) : '';
    }
{/literal}
{$end_sectionmethods}