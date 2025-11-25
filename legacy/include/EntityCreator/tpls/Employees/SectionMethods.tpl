{$start_sectionmethods}
    public function __construct()
    {ldelim}
{foreach from=$constructorFields item=constructedField}
        {$constructedField}
{/foreach}
    {rdelim}
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
{literal}
    public function getName(): ?string
    {
        return $this->getFullName();
    }
{/literal}
{$end_sectionmethods}