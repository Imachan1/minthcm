<?php

namespace MintHCM\MintCLI\Questions;

class ElasticSearchReindex extends ConfirmationQuestion
{
    protected $question = "Start reindexing?";
    protected $defaultValue = false;
    protected $defaultDisplayValue = 'no';
}
