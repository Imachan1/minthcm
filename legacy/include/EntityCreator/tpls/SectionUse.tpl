{$start_sectionuse}
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
{if !empty($additionalUseStatements)}
{foreach from=$additionalUseStatements item=useStatement}
{$useStatement};
{/foreach}
{/if}
{$end_sectionuse}