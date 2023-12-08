<?php

use MintHCM\MintCLI\Installer\Installer as CLIInstaller;
use SuiteCRM\Search\ElasticSearch\ElasticSearchIndexer;

class Installer extends CLIInstaller
{
    const INSTANCE_DIR = '../legacy';
    const FRONTEND_DIR = '../vue';
    const CLI_DIR = '../legacy/MintCLI/src';
    const INSTALL_LOG_FILE = './install.log';

    /**
     * Czemu tu?
     * Mamy już service od Elastica i wg mnie to powinno tam być
     * Odpalenie reindeksacji powinno mieć miejsce niezależnie od tego czy jest to instalcja z CLI czy z WEB
     */
    function reindexElastic(){
        try {
            $indexer = new ElasticSearchIndexer();
            $indexer->index();
        } catch (\Exception $e) {
        }
    }
}
